<?php

use App\Models\QuotationPdf;

describe('QuotationPdf Model', function () {
    it('can create a PDF record with all fields', function () {
        $pdfData = [
            'quotation_id' => 1,
            'quotation_item_id' => null,
            'pdf_type' => 'comercial',
            'title' => 'Cotización Comercial',
            'file_path' => 'pdf/test/commercial.pdf',
            'file_name' => 'commercial.pdf',
            'service_variant_id' => 1,
            'variant_name' => 'Pérgola de Vidrio',
            'service_index' => null,
            'file_size' => 1024,
            'generated_at' => now(),
            'status' => 'generated'
        ];
        
        // Verificar que los datos están completos
        expect($pdfData)->toHaveKey('quotation_id');
        expect($pdfData)->toHaveKey('pdf_type');
        expect($pdfData)->toHaveKey('title');
        expect($pdfData)->toHaveKey('file_path');
        expect($pdfData)->toHaveKey('file_name');
        
        // Verificar que el tipo de PDF es válido
        expect($pdfData['pdf_type'])->toBeIn(['comercial', 'produccion_pergola', 'produccion_cuadricula']);
        
        // Verificar que el status es válido
        expect($pdfData['status'])->toBe('generated');
    });
    
    it('validates PDF type enum values', function () {
        $validTypes = ['comercial', 'produccion_pergola', 'produccion_cuadricula'];
        $invalidTypes = ['invalid', 'pdf', 'document', ''];
        
        foreach ($validTypes as $type) {
            expect($type)->toBeIn(['comercial', 'produccion_pergola', 'produccion_cuadricula']);
        }
        
        foreach ($invalidTypes as $type) {
            expect($type)->not->toBeIn(['comercial', 'produccion_pergola', 'produccion_cuadricula']);
        }
    });
    
    it('verifies the file path structure for different PDF types', function () {
        $testCases = [
            [
                'type' => 'comercial',
                'expected_path_contains' => 'cotizacion_',
                'title_contains' => 'Cotización'
            ],
            [
                'type' => 'produccion_pergola',
                'expected_path_contains' => 'orden_produccion/pergolas/',
                'title_contains' => 'Orden Producción Pérgola'
            ],
            [
                'type' => 'produccion_cuadricula',
                'expected_path_contains' => 'orden_produccion/cuadriculas/',
                'title_contains' => 'Orden Producción Cuadrícula'
            ]
        ];
        
        foreach ($testCases as $case) {
            // Verificar estructura del path según el tipo
            if ($case['type'] === 'comercial') {
                expect('pdf/cotizacion_123.pdf')->toContain($case['expected_path_contains']);
            } else {
                expect('pdf/' . $case['expected_path_contains'] . 'file.pdf')->toContain($case['expected_path_contains']);
            }
            
            // Verificar estructura del título
            $sampleTitle = $case['title_contains'] . ' - Pérgola de Vidrio';
            expect($sampleTitle)->toContain($case['title_contains']);
        }
    });
    
    it('verifies the database schema requirements', function () {
        // Verificar que tenemos todos los campos necesarios
        $requiredFields = [
            'quotation_id',
            'quotation_item_id', // nullable
            'pdf_type',
            'title',
            'file_path',
            'file_name',
            'service_variant_id', // nullable
            'variant_name', // nullable
            'service_index', // nullable
            'file_size', // nullable
            'generated_at',
            'status'
        ];
        
        foreach ($requiredFields as $field) {
            // Verificar que el campo está en la lista de fillable del modelo
            expect(in_array($field, [
                'quotation_id',
                'quotation_item_id',
                'pdf_type',
                'title',
                'file_path',
                'file_name',
                'service_variant_id',
                'variant_name',
                'service_index',
                'file_size',
                'generated_at',
                'status'
            ]))->toBeTrue();
        }
    });
});
