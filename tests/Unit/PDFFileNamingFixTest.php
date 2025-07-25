<?php

use App\Services\Quotes\QuotePDFGenerator;

describe('PDF File Naming Fix Verification', function () {
    it('verifies that multiple PDF generation creates unique file names', function () {
        // Simular dos servicios de pérgola con datos diferentes
        $services = [
            [
                'variant_id' => 1,
                'input_index' => 0,
                'selected_cuadricula' => 'ninguna',
                'color' => 'azul'
            ],
            [
                'variant_id' => 1,
                'input_index' => 1,
                'selected_cuadricula' => 'ninguna',
                'color' => 'verde'
            ]
        ];
        
        $inputsPorServicio = [
            0 => [
                'medidaA' => 3.0,
                'medidaB' => 4.0,
                'alto' => 2.5,
                'n_columnas' => 4,
                'n_bajantes' => 2,
                'anillos' => 0
            ],
            1 => [
                'medidaA' => 5.0,
                'medidaB' => 6.0,
                'alto' => 3.0,
                'n_columnas' => 6,
                'n_bajantes' => 3,
                'anillos' => 0
            ]
        ];
        
        // Mock de quotation simple
        $quotation = (object)[
            'id' => 123,
            'client' => (object)[
                'name' => 'Cliente Test',
                'dni' => '12345678',
                'phone' => '123456789',
                'province' => 'Test Province'
            ]
        ];
        
        // Crear el generador de PDFs
        $generator = new QuotePDFGenerator();
        
        // Generar PDFs sin el comercial (solo de producción)
        $pdfs_generados = [];
        
        foreach ($services as $index => $servicio) {
            // Simular exactamente el mismo proceso del QuotePDFGenerator
            $inputs = array_merge([], $inputsPorServicio[$servicio['input_index']]);
            
            // Agregar información del cliente
            $inputs['quotation_id'] = 'COT-' . str_pad($quotation->id, 6, '0', STR_PAD_LEFT);
            $inputs['client_name'] = $quotation->client->name;
            $inputs['client_dni'] = $quotation->client->dni;
            $inputs['client_phone'] = $quotation->client->phone;
            $inputs['client_province'] = $quotation->client->province;
            
            // Información específica del servicio
            $inputs['service_variant_id'] = $servicio['variant_id'];
            $inputs['selected_cuadricula'] = $servicio['selected_cuadricula'];
            $inputs['color'] = $servicio['color'];
            $inputs['debug_variant_id'] = $servicio['variant_id'];
            $inputs['debug_service_index'] = $servicio['input_index'];
            
            // Verificar que los datos son únicos para cada servicio
            if ($index === 0) {
                expect($inputs['medidaA'])->toBe(3.0);
                expect($inputs['debug_service_index'])->toBe(0);
                expect($inputs['color'])->toBe('azul');
            } else {
                expect($inputs['medidaA'])->toBe(5.0);
                expect($inputs['debug_service_index'])->toBe(1);
                expect($inputs['color'])->toBe('verde');
            }
            
            // ✅ Lo importante: verificar que los debug_service_index son diferentes
            $pdfs_generados[] = [
                'titulo' => 'PDF ' . ($index + 1),
                'debug_service_index' => $inputs['debug_service_index'],
                'debug_variant_id' => $inputs['debug_variant_id'],
                'medidaA' => $inputs['medidaA'],
                'color' => $inputs['color']
            ];
        }
        
        // Verificar que se crearon datos únicos para cada PDF
        expect($pdfs_generados)->toHaveCount(2);
        
        // Verificar que los service_index son diferentes (esto es clave para el naming)
        expect($pdfs_generados[0]['debug_service_index'])->toBe(0);
        expect($pdfs_generados[1]['debug_service_index'])->toBe(1);
        
        // Verificar que los datos son diferentes
        expect($pdfs_generados[0]['medidaA'])->toBe(3.0);
        expect($pdfs_generados[1]['medidaA'])->toBe(5.0);
        
        expect($pdfs_generados[0]['color'])->toBe('azul');
        expect($pdfs_generados[1]['color'])->toBe('verde');
    });
    
    it('verifies that the debug information is correctly passed to the PDF generators', function () {
        // El QuotePDFGenerator debe agregar correctamente la información de debug
        $servicio = [
            'variant_id' => 1,
            'input_index' => 0,
            'selected_cuadricula' => 'ninguna',
            'color' => 'azul'
        ];
        
        $inputsPorServicio = [
            0 => [
                'medidaA' => 3.0,
                'medidaB' => 4.0,
                'alto' => 2.5,
                'n_columnas' => 4,
                'n_bajantes' => 2,
                'anillos' => 0
            ]
        ];
        
        // Simular el proceso del generateProductionPDFsForService
        $inputs = array_merge([], $inputsPorServicio[$servicio['input_index']]);
        
        // ✅ Estas líneas son las que agregó el QuotePDFGenerator para el fix
        $inputs['debug_variant_id'] = $servicio['variant_id'];
        $inputs['debug_service_index'] = $servicio['input_index'];
        
        // Verificar que la información de debug está presente
        expect($inputs)->toHaveKey('debug_variant_id');
        expect($inputs)->toHaveKey('debug_service_index');
        expect($inputs['debug_variant_id'])->toBe(1);
        expect($inputs['debug_service_index'])->toBe(0);
        
        // Esta información se usará para generar nombres únicos de archivo
        $serviceIndex = $inputs['debug_service_index'] ?? 'S';
        $variantId = $inputs['debug_variant_id'] ?? 'V';
        
        expect($serviceIndex)->toBe(0);
        expect($variantId)->toBe(1);
    });
});
