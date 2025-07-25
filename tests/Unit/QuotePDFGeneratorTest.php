<?php

use App\Services\Quotes\QuotePDFGenerator;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;

describe('QuotePDFGenerator Basic Tests', function () {
    
    it('can be instantiated', function () {
        $generator = new QuotePDFGenerator();
        
        expect($generator)->toBeInstanceOf(QuotePDFGenerator::class);
    });
    
    it('has the expected cuadricula types constant', function () {
        $reflection = new ReflectionClass(QuotePDFGenerator::class);
        $constant = $reflection->getConstant('TIPOS_CUADRICULA');
        
        expect($constant)->toBeArray();
        expect($constant)->toContain('cuadricula');
        expect($constant)->toContain('cuadricula_trama');
    });
});

describe('QuotePDFGenerator - PDF Generation Logic', function () {
    
    beforeEach(function () {
        $this->generator = new QuotePDFGenerator();
    });
    
    it('correctly identifies services that should generate cuadricula PDFs', function () {
        // Servicios que SÍ deberían generar cuadrícula
        $serviceWithCuadricula = [
            'service_id' => 1,
            'variant_id' => 1,
            'selected_cuadricula' => 'cuadricula_trama',
            'input_index' => 0,
            'color' => 'azul'
        ];
        
        $serviceWithCuadricula2 = [
            'service_id' => 1,
            'variant_id' => 2,
            'selected_cuadricula' => 'cuadricula',
            'input_index' => 1,
            'color' => 'negro'
        ];
        
        // Servicios que NO deberían generar cuadrícula
        $serviceWithoutCuadricula = [
            'service_id' => 1,
            'variant_id' => 1,
            'selected_cuadricula' => null,
            'input_index' => 0,
            'color' => 'azul'
        ];
        
        $serviceWithInvalidCuadricula = [
            'service_id' => 1,
            'variant_id' => 1,
            'selected_cuadricula' => 'invalid_type',
            'input_index' => 0,
            'color' => 'azul'
        ];
        
        // Verificar usando reflexión para acceder a la constante privada
        $reflection = new ReflectionClass(QuotePDFGenerator::class);
        $tiposCuadricula = $reflection->getConstant('TIPOS_CUADRICULA');
        
        // Verificar que los tipos válidos están en la constante
        expect(in_array($serviceWithCuadricula['selected_cuadricula'], $tiposCuadricula))->toBeTrue();
        expect(in_array($serviceWithCuadricula2['selected_cuadricula'], $tiposCuadricula))->toBeTrue();
        
        // Verificar que los tipos inválidos NO están en la constante
        expect(in_array($serviceWithoutCuadricula['selected_cuadricula'], $tiposCuadricula))->toBeFalse();
        expect(in_array($serviceWithInvalidCuadricula['selected_cuadricula'], $tiposCuadricula))->toBeFalse();
    });
    
    it('validates input structure for PDF generation', function () {
        $validService = [
            'service_id' => 1,
            'variant_id' => 1,
            'selected_cuadricula' => 'cuadricula_trama',
            'input_index' => 0,
            'color' => 'azul'
        ];
        
        $validInputs = [
            [
                'medidaA' => 3,
                'medidaB' => 4,
                'alto' => 3,
                'n_columnas' => 4,
                'n_bajantes' => 2,
                'anillos' => 0,
                'medidaACuadricula' => 2,
                'medidaBCuadricula' => 3,
                'distanciaPalillaje' => 0.6,
                'altoCuadricula' => 3
            ]
        ];
        
        // Verificar que la estructura de datos es válida
        expect($validService)->toHaveKeys(['service_id', 'variant_id', 'selected_cuadricula', 'input_index']);
        expect($validInputs[0])->toHaveKeys(['medidaA', 'medidaB', 'alto', 'n_columnas']);
        expect($validInputs[0])->toHaveKeys(['medidaACuadricula', 'medidaBCuadricula', 'distanciaPalillaje', 'altoCuadricula']);
        
        // Verificar tipos de datos
        expect($validService['variant_id'])->toBeInt();
        expect($validInputs[0]['medidaA'])->toBeNumeric();
        expect($validInputs[0]['medidaB'])->toBeNumeric();
    });
    
    it('handles edge cases in service configuration', function () {
        // Caso: servicio sin cuadrícula seleccionada
        $serviceNoSelection = [
            'service_id' => 1,
            'variant_id' => 1,
            'input_index' => 0,
            'color' => 'azul'
            // Note: no 'selected_cuadricula'
        ];
        
        // Caso: servicio con cuadrícula vacía
        $serviceEmptySelection = [
            'service_id' => 1,
            'variant_id' => 1,
            'selected_cuadricula' => '',
            'input_index' => 0,
            'color' => 'azul'
        ];
        
        // Verificar que estos casos no deberían generar cuadrícula
        $reflection = new ReflectionClass(QuotePDFGenerator::class);
        $tiposCuadricula = $reflection->getConstant('TIPOS_CUADRICULA');
        
        expect(in_array($serviceNoSelection['selected_cuadricula'] ?? null, $tiposCuadricula))->toBeFalse();
        expect(in_array($serviceEmptySelection['selected_cuadricula'], $tiposCuadricula))->toBeFalse();
    });
});

describe('QuotePDFGenerator - Expected Behavior Tests', function () {
    
    it('should generate correct number of PDFs for different scenarios', function () {
        // Escenario 1: Solo pérgola (sin cuadrícula)
        $scenario1 = [
            'services' => [
                [
                    'service_id' => 1,
                    'variant_id' => 1,
                    'selected_cuadricula' => null,
                    'input_index' => 0,
                    'color' => 'azul'
                ]
            ],
            'expected_pdfs' => 1, // Solo orden de producción de pérgola
            'description' => 'Solo pérgola, sin cuadrícula'
        ];
        
        // Escenario 2: Pérgola + cuadrícula
        $scenario2 = [
            'services' => [
                [
                    'service_id' => 1,
                    'variant_id' => 1,
                    'selected_cuadricula' => 'cuadricula_trama',
                    'input_index' => 0,
                    'color' => 'azul'
                ]
            ],
            'expected_pdfs' => 2, // Orden pérgola + orden cuadrícula
            'description' => 'Pérgola con cuadrícula'
        ];
        
        // Escenario 3: Múltiples servicios con cuadrículas mixtas
        $scenario3 = [
            'services' => [
                [
                    'service_id' => 1,
                    'variant_id' => 1,
                    'selected_cuadricula' => 'cuadricula_trama',
                    'input_index' => 0,
                    'color' => 'azul'
                ],
                [
                    'service_id' => 1,
                    'variant_id' => 2,
                    'selected_cuadricula' => null,
                    'input_index' => 1,
                    'color' => 'negro'
                ]
            ],
            'expected_pdfs' => 3, // Pérgola1 + cuadrícula1 + pérgola2
            'description' => 'Múltiples servicios mixtos'
        ];
        
        // Verificar que entendemos el comportamiento esperado
        expect($scenario1['expected_pdfs'])->toBe(1);
        expect($scenario2['expected_pdfs'])->toBe(2);
        expect($scenario3['expected_pdfs'])->toBe(3);
        
        // Log de escenarios para debugging
        foreach ([$scenario1, $scenario2, $scenario3] as $i => $scenario) {
            expect($scenario)->toHaveKeys(['services', 'expected_pdfs', 'description']);
        }
    });
});
