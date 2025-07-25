# Sistema de Gestión de PDFs para Cotizaciones

## Resumen de la implementación

Se ha implementado un sistema completo para gestionar las rutas de los PDFs generados en la base de datos.

## Estructura de la Base de Datos

### Tabla: `quotation_pdfs`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `id` | bigint | ID único del PDF |
| `quotation_id` | bigint | ID de la cotización (FK) |
| `quotation_item_id` | bigint nullable | ID del item de cotización (solo para PDFs de producción) |
| `pdf_type` | enum | Tipo de PDF: 'comercial', 'produccion_pergola', 'produccion_cuadricula' |
| `title` | string | Título del PDF como aparece en la lista |
| `file_path` | string | Ruta del archivo PDF en storage |
| `file_name` | string | Nombre original del archivo |
| `service_variant_id` | int nullable | ID de la variante del servicio |
| `variant_name` | string nullable | Nombre de la variante para referencia |
| `service_index` | int nullable | Índice del servicio en la cotización |
| `file_size` | int nullable | Tamaño del archivo en bytes |
| `generated_at` | timestamp | Fecha y hora de generación del PDF |
| `status` | string | Estado del PDF: 'generated', 'downloaded', 'error' |

## Modelos y Relaciones

### QuotationPdf Model
```php
// Obtener la cotización
$pdf->quotation

// Obtener el item de cotización (si aplica)
$pdf->quotationItem

// Obtener la variante del servicio
$pdf->serviceVariant

// URL completa del archivo
$pdf->full_url

// Verificar si el archivo existe
$pdf->fileExists()

// Tamaño del archivo formateado
$pdf->formatted_file_size
```

### Quotation Model (métodos añadidos)
```php
// Todos los PDFs
$quotation->pdfs

// Solo PDFs comerciales
$quotation->commercialPdfs

// Solo PDFs de producción
$quotation->productionPdfs

// PDFs organizados por tipo
$quotation->getPdfsGroupedByType()

// Último PDF comercial
$quotation->getLatestCommercialPdf()

// Verificar si tiene PDFs
$quotation->hasPdfs()
```

## Ejemplos de Uso

### 1. Obtener todos los PDFs de una cotización
```php
$quotation = Quotation::find(1);
$pdfs = $quotation->getPdfsGroupedByType();

// PDFs comerciales
foreach ($pdfs['comercial'] as $pdf) {
    echo "Comercial: {$pdf->title} - {$pdf->full_url}\n";
}

// PDFs de producción de pérgolas
foreach ($pdfs['produccion_pergola'] as $pdf) {
    echo "Pérgola: {$pdf->title} - Variante: {$pdf->variant_name}\n";
}

// PDFs de producción de cuadrículas
foreach ($pdfs['produccion_cuadricula'] as $pdf) {
    echo "Cuadrícula: {$pdf->title} - Tamaño: {$pdf->formatted_file_size}\n";
}
```

### 2. Buscar PDFs por tipo
```php
// Solo PDFs comerciales
$commercialPdfs = QuotationPdf::commercial()->get();

// Solo PDFs de producción
$productionPdfs = QuotationPdf::production()->get();

// PDFs de un tipo específico
$pergolaPdfs = QuotationPdf::byType('produccion_pergola')->get();
```

### 3. Verificar estado de los archivos
```php
$pdfs = QuotationPdf::all();

foreach ($pdfs as $pdf) {
    if (!$pdf->fileExists()) {
        echo "⚠️ Archivo faltante: {$pdf->file_path}\n";
        $pdf->update(['status' => 'error']);
    }
}
```

### 4. Obtener estadísticas
```php
$quotation = Quotation::find(1);

$stats = [
    'total_pdfs' => $quotation->pdfs()->count(),
    'commercial_pdfs' => $quotation->commercialPdfs()->count(),
    'production_pdfs' => $quotation->productionPdfs()->count(),
    'total_size' => $quotation->pdfs()->sum('file_size'),
    'latest_generated' => $quotation->pdfs()->max('generated_at')
];
```

## Integración Automática

El sistema se integra automáticamente con el `QuotePDFGenerator`:

### Cuando se genera un PDF comercial:
- Se guarda en `quotation_pdfs` con `pdf_type = 'comercial'`
- `quotation_item_id` es null (aplica a toda la cotización)

### Cuando se genera un PDF de producción de pérgola:
- Se guarda con `pdf_type = 'produccion_pergola'`
- Se asocia al `quotation_item_id` correspondiente
- Se incluye información de la variante

### Cuando se genera un PDF de producción de cuadrícula:
- Se guarda con `pdf_type = 'produccion_cuadricula'`
- Se asocia al `quotation_item_id` correspondiente
- Se incluye información de la variante

## Nombres de Archivos Únicos

Los archivos PDF ahora tienen nombres únicos que incluyen:
- ID de cotización
- Índice del servicio
- ID de variante
- Timestamp con microsegundos

Ejemplo: `orden_produccion_COT-000123_servicio_0_variante_1_20250725143045_16745678901234.pdf`

## Beneficios

1. **Trazabilidad completa**: Cada PDF queda registrado en la BD
2. **Organización**: Los PDFs se organizan por tipo y cotización
3. **Metadatos**: Se almacena información útil como tamaño, fecha de generación, etc.
4. **Integridad**: Se puede verificar si los archivos existen físicamente
5. **Historial**: Se mantiene un registro completo de todos los PDFs generados
6. **Búsquedas eficientes**: Índices en la BD para consultas rápidas

## Próximos Pasos Sugeridos

1. **Limpieza automática**: Tarea programada para eliminar PDFs antiguos
2. **Notificaciones**: Alertas cuando fallan las generaciones
3. **Versionado**: Mantener versiones de PDFs regenerados
4. **Compresión**: Optimizar el tamaño de los archivos PDF
5. **API**: Endpoints para gestionar los PDFs desde el frontend
