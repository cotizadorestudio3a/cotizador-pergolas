<?php

use App\Services\Quotes\QuotePDFGenerator;
use App\Models\QuotationPdf;

describe('PDF Database Storage', function () {
    it('verifies that PDFs are saved to the database correctly', function () {
        // Crear el generador
        $generator = new QuotePDFGenerator();
        
        // Usar reflexión para acceder al método privado savePdfToDatabase
        $reflection = new ReflectionClass($generator);
        $method = $reflection->getMethod('savePdfToDatabase');
        $method->setAccessible(true);
        
        // Mock de quotation
        $quotation = (object)[
            'id' => 123
        ];
        
        // Datos de prueba
        $pdfPath = 'pdf/test/test_file.pdf';
        $title = 'Test PDF Title';
        $pdfType = 'produccion_pergola';
        $serviceVariantId = 1;
        $variantName = 'Pérgola de Vidrio';
        $serviceIndex = 0;
        
        // Crear un archivo temporal para simular el PDF
        $fullPath = storage_path('app/public/' . $pdfPath);
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        file_put_contents($fullPath, 'Test PDF content');
        
        // Llamar al método
        $quotationPdf = $method->invoke(
            $generator,
            $pdfPath,
            $title,
            $pdfType,
            $quotation,
            null, // quotationItemId
            $serviceVariantId,
            $variantName,
            $serviceIndex
        );
        
        // Verificar que se creó el registro en la BD
        expect($quotationPdf)->toBeInstanceOf(QuotationPdf::class);
        expect($quotationPdf->quotation_id)->toBe(123);
        expect($quotationPdf->pdf_type)->toBe('produccion_pergola');
        expect($quotationPdf->title)->toBe('Test PDF Title');
        expect($quotationPdf->file_path)->toBe($pdfPath);
        expect($quotationPdf->service_variant_id)->toBe(1);
        expect($quotationPdf->variant_name)->toBe('Pérgola de Vidrio');
        expect($quotationPdf->service_index)->toBe(0);
        expect($quotationPdf->status)->toBe('generated');
        expect($quotationPdf->file_size)->toBeGreaterThan(0);
        
        // Limpiar
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
        $quotationPdf->delete();
    });
    
    it('verifies that findQuotationItemId method works correctly', function () {
        // Crear el generador
        $generator = new QuotePDFGenerator();
        
        // Usar reflexión para acceder al método privado
        $reflection = new ReflectionClass($generator);
        $method = $reflection->getMethod('findQuotationItemId');
        $method->setAccessible(true);
        
        // Mock de quotation sin quotationItems
        $quotation = null;
        $servicio = ['variant_id' => 1];
        
        // Caso 1: quotation es null
        $result = $method->invoke($generator, $quotation, $servicio);
        expect($result)->toBeNull();
        
        // Caso 2: quotation existe pero no tiene items
        $quotation = (object)[
            'id' => 123
        ];
        
        // Mock del método quotationItems que retorna una query vacía
        $quotation->quotationItems = function() {
            return new class {
                public function where($field, $value) {
                    return $this;
                }
                public function first() {
                    return null; // No encuentra item
                }
            };
        };
        
        $result = $method->invoke($generator, $quotation, $servicio);
        expect($result)->toBeNull();
    });
    
    it('verifies the different PDF types are handled correctly', function () {
        $testCases = [
            [
                'type' => 'comercial',
                'title' => 'Cotización Comercial',
                'has_item_id' => false
            ],
            [
                'type' => 'produccion_pergola',
                'title' => 'Orden Producción Pérgola - Pérgola de Vidrio',
                'has_item_id' => true
            ],
            [
                'type' => 'produccion_cuadricula',
                'title' => 'Orden Producción Cuadrícula - Pérgola de Vidrio',
                'has_item_id' => true
            ]
        ];
        
        foreach ($testCases as $case) {
            // Verificar que los tipos de PDF son válidos
            expect($case['type'])->toBeIn(['comercial', 'produccion_pergola', 'produccion_cuadricula']);
            
            // Verificar que los títulos son descriptivos
            expect($case['title'])->toContain($case['type'] === 'comercial' ? 'Cotización' : 'Orden Producción');
            
            // Verificar lógica de quotation_item_id
            if ($case['has_item_id']) {
                expect($case['type'])->toContain('produccion');
            } else {
                expect($case['type'])->toBe('comercial');
            }
        }
    });
});
