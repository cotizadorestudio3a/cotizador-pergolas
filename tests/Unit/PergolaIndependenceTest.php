<?php

use App\Services\Pergolas\PergolaFactory;
use App\Services\Quotes\QuotePDFGenerator;

describe('Multiple Pergola PDF Generation Problem', function () {
    it('verifies that multiple pergola instances maintain independent data', function () {
        // Simular datos para dos pérgolas diferentes
        $data1 = [
            'medidaA' => 3.0,
            'medidaB' => 4.0,
            'alto' => 2.5,
            'n_columnas' => 4,
            'n_bajantes' => 2,
            'anillos' => 0,
            'quotation_id' => 'COT-001',
            'client_name' => 'Cliente Uno',
            'debug_variant_id' => 1,
            'debug_service_index' => 0
        ];
        
        $data2 = [
            'medidaA' => 5.0,
            'medidaB' => 6.0,
            'alto' => 3.0,
            'n_columnas' => 6,
            'n_bajantes' => 3,
            'anillos' => 0,
            'quotation_id' => 'COT-002',
            'client_name' => 'Cliente Dos',
            'debug_variant_id' => 1,
            'debug_service_index' => 1
        ];
        
        // Crear dos instancias de pérgola
        $pergola1 = PergolaFactory::crear(1, $data1);
        $pergola2 = PergolaFactory::crear(1, $data2);
        
        // Calcular ambas
        $pergola1->calcular();
        $pergola2->calcular();
        
        // Verificar que son instancias diferentes
        expect($pergola1)->not->toBe($pergola2);
        
        // Verificar que tienen datos diferentes
        expect($pergola1->medidaA)->toBe(3.0);
        expect($pergola2->medidaA)->toBe(5.0);
        
        expect($pergola1->medidaB)->toBe(4.0);
        expect($pergola2->medidaB)->toBe(6.0);
        
        expect($pergola1->n_columnas)->toBe(4);
        expect($pergola2->n_columnas)->toBe(6);
        
        // Verificar que las áreas calculadas son diferentes
        expect($pergola1->area)->toBe(12.0); // 3.0 * 4.0
        expect($pergola2->area)->toBe(30.0); // 5.0 * 6.0
        
        // Verificar que no comparten estado al modificar una propiedad
        $pergola1->medidaA = 999.0;
        expect($pergola2->medidaA)->toBe(5.0); // No debe cambiar
    });
    
    it('simulates the QuotePDFGenerator workflow to identify the problem', function () {
        // Simular los datos como llegan al QuotePDFGenerator
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
        
        // ✅ Verificar que los inputs originales son diferentes
        expect($inputsPorServicio[0]['medidaA'])->toBe(3.0);
        expect($inputsPorServicio[1]['medidaA'])->toBe(5.0);
        
        // Simular el proceso del QuotePDFGenerator
        $pdfs_generados = [];
        
        foreach ($services as $index => $servicio) {
            // ✅ CRÍTICO: Simular exactamente lo que hace el QuotePDFGenerator
            $inputs = array_merge([], $inputsPorServicio[$servicio['input_index']]);
            
            // Agregar información específica del servicio
            $inputs['debug_variant_id'] = $servicio['variant_id'];
            $inputs['debug_service_index'] = $servicio['input_index'];
            $inputs['color'] = $servicio['color'];
            
            // ✅ Verificar que los inputs copiados son diferentes para cada iteración
            if ($index === 0) {
                expect($inputs['medidaA'])->toBe(3.0);
                expect($inputs['color'])->toBe('azul');
            } else {
                expect($inputs['medidaA'])->toBe(5.0);
                expect($inputs['color'])->toBe('verde');
            }
            
            // Crear pérgola y calcular
            $pergola = PergolaFactory::crear($servicio['variant_id'], $inputs);
            $pergola->calcular();
            
            // ✅ Verificar que cada pérgola tiene los datos correctos
            if ($index === 0) {
                expect($pergola->medidaA)->toBe(3.0);
                expect($pergola->area)->toBe(12.0);
            } else {
                expect($pergola->medidaA)->toBe(5.0);
                expect($pergola->area)->toBe(30.0);
            }
            
            $pdfs_generados[] = [
                'titulo' => 'Pergola ' . ($index + 1),
                'medidaA' => $pergola->medidaA,
                'area' => $pergola->area,
                'color' => $inputs['color']
            ];
        }
        
        // ✅ Verificar que se generaron PDFs con datos diferentes
        expect($pdfs_generados)->toHaveCount(2);
        expect($pdfs_generados[0]['medidaA'])->toBe(3.0);
        expect($pdfs_generados[1]['medidaA'])->toBe(5.0);
        expect($pdfs_generados[0]['area'])->toBe(12.0);
        expect($pdfs_generados[1]['area'])->toBe(30.0);
        expect($pdfs_generados[0]['color'])->toBe('azul');
        expect($pdfs_generados[1]['color'])->toBe('verde');
    });
});
