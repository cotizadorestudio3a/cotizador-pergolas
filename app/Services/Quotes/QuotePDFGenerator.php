<?php

namespace App\Services\Quotes;

use App\Services\Cuadriculas\CuadriculaFactory;
use App\Services\Pergolas\PergolaFactory;
use App\Models\ServiceVariants;
use App\Models\QuotationPdf;
use Illuminate\Support\Facades\Log;

class QuotePDFGenerator
{
    private const TIPOS_CUADRICULA = ['cuadricula', 'cuadricula_trama'];

    public function generateAllPDFs(array $services, array $inputsPorServicio, $quotation = null, array $servicesDetail = []): array
    {
        $pdfs_generados = [];

        try {
            // 1. Generar UN SOLO PDF comercial con todos los servicios
            if ($quotation) {
                $commercialPDF = $this->generateCommercialPDF($services, $inputsPorServicio, $quotation, $servicesDetail);
                $pdfs_generados[] = $commercialPDF;
            }

            // 2. Generar PDFs de producción individuales para cada servicio
            foreach ($services as $index => $servicio) {
                try {
                    $productionPDFs = $this->generateProductionPDFsForService($servicio, $inputsPorServicio, $quotation, $servicesDetail);
                    $pdfs_generados = array_merge($pdfs_generados, $productionPDFs);
                } catch (\Exception $e) {
                    Log::error('Error generando PDFs de producción para servicio: ' . $e->getMessage(), [
                        'service_index' => $index,
                        'variant_id' => $servicio['variant_id'] ?? 'N/A',
                        'selected_cuadricula' => $servicio['selected_cuadricula'] ?? 'N/A'
                    ]);
                    // Continuar con los demás servicios
                }
            }
        } catch (\Exception $e) {
            Log::error('Error general generando PDFs: ' . $e->getMessage(), [
                'services_count' => count($services),
                'quotation_id' => $quotation->id ?? 'N/A'
            ]);
            throw $e; // Re-lanzar para que el controlador pueda manejarlo
        }

        return $pdfs_generados;
    }

    /**
     * Genera UN SOLO PDF comercial con todos los servicios de la cotización
     */
    private function generateCommercialPDF(array $services, array $inputsPorServicio, $quotation, array $servicesDetail = []): array
    {
        // Usar el primer servicio como base para el PDF comercial
        $firstService = $services[0];
        
        // ✅ CREAR COPIA INDEPENDIENTE también para el PDF comercial
        $inputs = array_merge([], $inputsPorServicio[$firstService['input_index']]);

        // Agregar información del cliente y cotización
        $inputs['quotation_id'] = 'COT-' . str_pad($quotation->id, 6, '0', STR_PAD_LEFT);
        $inputs['client_name'] = $quotation->client->name ?? '';
        $inputs['client_dni'] = $quotation->client->dni ?? '';
        $inputs['client_phone'] = $quotation->client->phone ?? '';
        $inputs['client_province'] = $quotation->client->province ?? '';
        $inputs['total'] = $quotation->total;
        $inputs['iva'] = $quotation->iva;
        $inputs['pvp'] = $quotation->pvp;

        // Agregar detalles de servicios con precios calculados
        $inputs['services_detail'] = $servicesDetail;

        // Preparar información de TODOS los servicios para el PDF (mantener compatibilidad)
        $inputs['all_services'] = [];
        
        foreach ($services as $servicio) {
            // ✅ También crear copia independiente para cada servicio en all_services
            $serviceInputs = array_merge([], $inputsPorServicio[$servicio['input_index']]);
            
            $inputs['all_services'][] = [
                'service_id' => $servicio['service_id'],
                'variant_id' => $servicio['variant_id'] ?? null,
                'selected_cuadricula' => $servicio['selected_cuadricula'] ?? null,
                'color' => $servicio['color'] ?? $servicio['selected_color'] ?? 'azul',
                'inputs' => $serviceInputs
            ];
        }

        // Generar PDF comercial usando el primer servicio como base
        $pergola = PergolaFactory::crear($firstService['variant_id'], $inputs);
        $pergola->calcular();
        
        $pdfPath = $pergola->obtenerPDFCotizacion();
        
        // ✅ Guardar el PDF comercial en la base de datos
        $this->savePdfToDatabase(
            pdfPath: $pdfPath,
            title: 'Cotización Comercial',
            pdfType: 'comercial',
            quotation: $quotation,
            quotationItemId: null, // El PDF comercial no está asociado a un item específico
            serviceVariantId: $firstService['variant_id'],
            variantName: 'Cotización Completa',
            serviceIndex: null
        );
        
        return [
            'titulo' => 'Cotización Comercial',
            'path' => $pdfPath,
        ];
    }

    /**
     * Genera PDFs de producción para un servicio individual
     */
    private function generateProductionPDFsForService(array $servicio, array $inputsPorServicio, $quotation = null, array $servicesDetail = []): array
    {
        $pdfs = [];
        
        // ✅ CRÍTICO: Crear una copia COMPLETAMENTE INDEPENDIENTE de los inputs
        $inputs = array_merge([], $inputsPorServicio[$servicio['input_index']]);

        // Agregar información básica si hay cotización
        if ($quotation) {
            $inputs['quotation_id'] = 'COT-' . str_pad($quotation->id, 6, '0', STR_PAD_LEFT);
            $inputs['client_name'] = $quotation->client->name ?? '';
            $inputs['client_dni'] = $quotation->client->dni ?? '';
            $inputs['client_phone'] = $quotation->client->phone ?? '';
            $inputs['client_province'] = $quotation->client->province ?? '';
        }

        // Agregar detalles del servicio específico si están disponibles
        if (!empty($servicesDetail) && isset($servicesDetail[$servicio['input_index']])) {
            $inputs['service_detail'] = $servicesDetail[$servicio['input_index']];
        }

        // ✅ CRÍTICO: Agregar información ESPECÍFICA de ESTE servicio
        $inputs['service_variant_id'] = $servicio['variant_id'] ?? null;
        $inputs['selected_cuadricula'] = $servicio['selected_cuadricula'] ?? null;
        $inputs['color'] = $servicio['color'] ?? $servicio['selected_color'] ?? 'azul';
        
        // ✅ Agregar información de colores de columnas si existe
        if (isset($servicio['colores_columnas'])) {
            $inputs['colores_columnas'] = $servicio['colores_columnas'];
        }

        // ✅ DEBUG: Agregar información para identificar el servicio
        $inputs['debug_variant_id'] = $servicio['variant_id'];
        $inputs['debug_service_index'] = $servicio['input_index'];

        // ✅ Obtener el nombre de la variante para el título
        $variantName = 'Variante ' . ($servicio['variant_id'] ?? 'N/A');
        try {
            if (isset($servicio['variant_id'])) {
                $variant = ServiceVariants::find($servicio['variant_id']);
                $variantName = $variant ? $variant->name : 'Variante ' . $servicio['variant_id'];
            }
        } catch (\Exception $e) {
            // Si falla la consulta, usar el ID como fallback
            $variantName = 'Variante ' . ($servicio['variant_id'] ?? 'N/A');
        }

        // ✅ DEBUGGING CRÍTICO: Loggear qué variant_id se está usando
        Log::info('Generando PDF de pérgola:', [
            'variant_id' => $servicio['variant_id'],
            'variant_name' => $variantName,
            'service_index' => $servicio['input_index'],
            'selected_cuadricula' => $servicio['selected_cuadricula'] ?? 'ninguna',
            'factory_class' => 'PergolaFactory::crear(' . $servicio['variant_id'] . ')'
        ]);

        // Generar PDF de orden de producción de pérgola
        $pergola = PergolaFactory::crear($servicio['variant_id'], $inputs);
        $pergola->calcular();
        
        // ✅ Log de qué clase se creó realmente
        Log::info('Clase de pérgola creada: ' . get_class($pergola));
        
        $pergolaPdfPath = $pergola->obtenerPDFOrdenProduccion();
        
        // ✅ Guardar el PDF de pérgola en la base de datos
        $this->savePdfToDatabase(
            pdfPath: $pergolaPdfPath,
            title: 'Orden Producción Pérgola - ' . $variantName,
            pdfType: 'produccion_pergola',
            quotation: $quotation,
            quotationItemId: $this->findQuotationItemId($quotation, $servicio),
            serviceVariantId: $servicio['variant_id'],
            variantName: $variantName,
            serviceIndex: $servicio['input_index']
        );
        
        $pdfs[] = [
            'titulo' => 'Orden Producción Pérgola - ' . $variantName,
            'path' => $pergolaPdfPath,
        ];

        // ✅ Generar PDF de cuadrícula si aplica (con datos INDEPENDIENTES)
        if (in_array($servicio['selected_cuadricula'], self::TIPOS_CUADRICULA)) {
            // ✅ DEBUGGING: Log cuadrícula
            Log::info('Generando PDF de cuadrícula:', [
                'variant_id' => $servicio['variant_id'],
                'selected_cuadricula' => $servicio['selected_cuadricula'],
                'tipos_cuadricula_validos' => self::TIPOS_CUADRICULA
            ]);
            
            // Crear OTRA copia independiente específica para la cuadrícula
            $cuadriculaInputs = array_merge([], $inputs);
            
            try {
                $cuadricula = CuadriculaFactory::crear($servicio['selected_cuadricula'], $cuadriculaInputs);
                $cuadricula->calcular();
                
                Log::info('Clase de cuadrícula creada: ' . get_class($cuadricula));
                
                $cuadriculaPdfPath = $cuadricula->obtenerPDFOrdenProduccion();
                
                // ✅ Guardar el PDF de cuadrícula en la base de datos
                $this->savePdfToDatabase(
                    pdfPath: $cuadriculaPdfPath,
                    title: 'Orden Producción Cuadrícula - ' . $variantName,
                    pdfType: 'produccion_cuadricula',
                    quotation: $quotation,
                    quotationItemId: $this->findQuotationItemId($quotation, $servicio),
                    serviceVariantId: $servicio['variant_id'],
                    variantName: $variantName,
                    serviceIndex: $servicio['input_index']
                );
                
                $pdfs[] = [
                    'titulo' => 'Orden Producción Cuadrícula - ' . $variantName,
                    'path' => $cuadriculaPdfPath,
                ];
            } catch (\Exception $e) {
                // ✅ Log del error para debugging
                Log::error('Error generando PDF de cuadrícula: ' . $e->getMessage(), [
                    'servicio' => $servicio,
                    'variant_id' => $servicio['variant_id'],
                    'selected_cuadricula' => $servicio['selected_cuadricula'],
                    'stack_trace' => $e->getTraceAsString()
                ]);
                // No detener la generación de otros PDFs
            }
        } else {
            // ✅ DEBUGGING: Log por qué no se genera cuadrícula
            Log::info('NO se genera PDF de cuadrícula:', [
                'variant_id' => $servicio['variant_id'],
                'selected_cuadricula' => $servicio['selected_cuadricula'] ?? 'null',
                'tipos_validos' => self::TIPOS_CUADRICULA,
                'in_array_result' => in_array($servicio['selected_cuadricula'] ?? null, self::TIPOS_CUADRICULA)
            ]);
        }

        return $pdfs;
    }

    /**
     * Guarda la información del PDF en la base de datos
     */
    private function savePdfToDatabase(
        string $pdfPath, 
        string $title, 
        string $pdfType, 
        $quotation = null, 
        $quotationItemId = null, 
        $serviceVariantId = null, 
        $variantName = null, 
        $serviceIndex = null
    ): QuotationPdf {
        // Obtener información del archivo
        $fullPath = storage_path('app/public/' . $pdfPath);
        $fileSize = file_exists($fullPath) ? filesize($fullPath) : null;
        $fileName = basename($pdfPath);

        // Crear el registro en la base de datos
        $quotationPdf = QuotationPdf::create([
            'quotation_id' => $quotation ? $quotation->id : null,
            'quotation_item_id' => $quotationItemId,
            'pdf_type' => $pdfType,
            'title' => $title,
            'file_path' => $pdfPath,
            'file_name' => $fileName,
            'service_variant_id' => $serviceVariantId,
            'variant_name' => $variantName,
            'service_index' => $serviceIndex,
            'file_size' => $fileSize,
            'generated_at' => now(),
            'status' => 'generated'
        ]);

        Log::info('PDF guardado en BD:', [
            'pdf_id' => $quotationPdf->id,
            'title' => $title,
            'type' => $pdfType,
            'path' => $pdfPath,
            'file_size' => $fileSize
        ]);

        return $quotationPdf;
    }

    /**
     * Encuentra el ID del QuotationItem correspondiente al servicio
     */
    private function findQuotationItemId($quotation, array $servicio): ?int
    {
        if (!$quotation) {
            return null;
        }

        // Buscar el quotation_item que corresponde a este servicio
        $quotationItem = $quotation->quotationItems()
            ->where('service_variant_id', $servicio['variant_id'])
            ->first();

        return $quotationItem ? $quotationItem->id : null;
    }
}
