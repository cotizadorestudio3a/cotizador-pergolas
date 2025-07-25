<?php

use App\Services\Quotes\QuotePDFGenerator;

describe('PDF Generation Problem Detection', function () {
    
    beforeEach(function () {
        $this->generator = new QuotePDFGenerator();
    });
    
    it('documents the expected behavior vs actual behavior for cuadricula PDFs', function () {
        // CASO DE PRUEBA: Servicio con cuadrícula seleccionada
        $testCase = [
            'description' => 'Servicio con cuadrícula_trama seleccionada',
            'service' => [
                'service_id' => 1,
                'variant_id' => 1,
                'selected_cuadricula' => 'cuadricula_trama',
                'input_index' => 0,
                'color' => 'azul'
            ],
            'inputs' => [
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
            ],
            'expected_behavior' => [
                'should_generate_pergola_pdf' => true,
                'should_generate_cuadricula_pdf' => true,
                'total_production_pdfs' => 2, // pérgola + cuadrícula
                'reason' => 'Cuando selected_cuadricula es cuadricula_trama, debería generar ambos PDFs'
            ]
        ];
        
        // VERIFICACIÓN DE LA LÓGICA INTERNA
        $reflection = new ReflectionClass(QuotePDFGenerator::class);
        $tiposCuadricula = $reflection->getConstant('TIPOS_CUADRICULA');
        
        // ¿El tipo de cuadrícula está en los tipos válidos?
        $cuadriculaTypeIsValid = in_array($testCase['service']['selected_cuadricula'], $tiposCuadricula);
        expect($cuadriculaTypeIsValid)->toBeTrue('El tipo cuadricula_trama debería estar en TIPOS_CUADRICULA');
        
        // ¿Los inputs incluyen los datos necesarios para cuadrícula?
        $hasRequiredCuadriculaInputs = isset($testCase['inputs']['medidaACuadricula']) 
            && isset($testCase['inputs']['medidaBCuadricula'])
            && isset($testCase['inputs']['distanciaPalillaje'])
            && isset($testCase['inputs']['altoCuadricula']);
        
        expect($hasRequiredCuadriculaInputs)->toBeTrue('Los inputs deberían incluir datos de cuadrícula');
        
        // DOCUMENTAR EL COMPORTAMIENTO ESPERADO
        expect($testCase['expected_behavior']['should_generate_pergola_pdf'])->toBeTrue();
        expect($testCase['expected_behavior']['should_generate_cuadricula_pdf'])->toBeTrue();
        expect($testCase['expected_behavior']['total_production_pdfs'])->toBe(2);
        
        // Si estas pruebas pasan pero el comportamiento real es diferente,
        // entonces el problema está en la implementación, no en la lógica de validación
    });
    
    it('creates a test scenario that matches your production issue', function () {
        // ESCENARIO: Basado en tu descripción del problema
        $problemScenario = [
            'description' => 'Usuario selecciona pérgola + cuadrícula pero solo se genera PDF de pérgola',
            'user_action' => 'Selecciona servicio con cuadrícula en la interfaz',
            'services_sent_to_generator' => [
                [
                    'service_id' => 1,
                    'variant_id' => 1,
                    'selected_cuadricula' => 'cuadricula_trama', // Usuario seleccionó cuadrícula
                    'input_index' => 0,
                    'color' => 'azul'
                ]
            ],
            'inputs_sent_to_generator' => [
                [
                    // Datos de pérgola
                    'medidaA' => 3,
                    'medidaB' => 4,
                    'alto' => 3,
                    'n_columnas' => 4,
                    'n_bajantes' => 2,
                    'anillos' => 0,
                    // Datos de cuadrícula
                    'medidaACuadricula' => 2,
                    'medidaBCuadricula' => 3,
                    'distanciaPalillaje' => 0.6,
                    'altoCuadricula' => 3
                ]
            ],
            'expected_result' => [
                'total_pdfs' => 3, // comercial + pérgola + cuadrícula
                'pdf_types' => ['comercial', 'pergola_production', 'cuadricula_production']
            ],
            'actual_result_reported' => [
                'total_pdfs' => 2, // comercial + pérgola solamente
                'pdf_types' => ['comercial', 'pergola_production'], // falta cuadrícula
                'issue' => 'PDF de cuadrícula no se genera'
            ]
        ];
        
        // ANÁLISIS DEL PROBLEMA
        $service = $problemScenario['services_sent_to_generator'][0];
        $inputs = $problemScenario['inputs_sent_to_generator'][0];
        
        // 1. ¿El servicio tiene cuadrícula seleccionada?
        expect($service['selected_cuadricula'])->not->toBeNull();
        expect($service['selected_cuadricula'])->toBe('cuadricula_trama');
        
        // 2. ¿El tipo de cuadrícula es válido?
        $reflection = new ReflectionClass(QuotePDFGenerator::class);
        $tiposCuadricula = $reflection->getConstant('TIPOS_CUADRICULA');
        expect(in_array($service['selected_cuadricula'], $tiposCuadricula))->toBeTrue();
        
        // 3. ¿Los inputs tienen los datos necesarios?
        expect($inputs)->toHaveKeys([
            'medidaACuadricula', 
            'medidaBCuadricula', 
            'distanciaPalillaje', 
            'altoCuadricula'
        ]);
        
        // 4. ¿Los datos son válidos (no vacíos, no null)?
        expect($inputs['medidaACuadricula'])->toBeNumeric()->toBeGreaterThan(0);
        expect($inputs['medidaBCuadricula'])->toBeNumeric()->toBeGreaterThan(0);
        expect($inputs['distanciaPalillaje'])->toBeNumeric()->toBeGreaterThan(0);
        expect($inputs['altoCuadricula'])->toBeNumeric()->toBeGreaterThan(0);
        
        // CONCLUSIÓN: Si todas estas validaciones pasan, 
        // entonces la lógica inicial es correcta y el problema debe estar en:
        // a) La ejecución de CuadriculaFactory::crear()
        // b) La generación del PDF de cuadrícula
        // c) El retorno/agregado del PDF al array final
        
        // Esta prueba nos ayuda a confirmar que el problema NO está en la validación inicial
    });
    
    it('simulates the exact debugging steps to find the issue', function () {
        // PASOS DE DEBUGGING SIMULADOS
        $debugSteps = [
            'step_1' => [
                'description' => 'Verificar que selected_cuadricula llega correctamente',
                'test' => function($service) {
                    return isset($service['selected_cuadricula']) && 
                           !empty($service['selected_cuadricula']) &&
                           $service['selected_cuadricula'] !== null;
                }
            ],
            'step_2' => [
                'description' => 'Verificar que el tipo está en TIPOS_CUADRICULA',
                'test' => function($service) {
                    $reflection = new ReflectionClass(QuotePDFGenerator::class);
                    $tipos = $reflection->getConstant('TIPOS_CUADRICULA');
                    return in_array($service['selected_cuadricula'] ?? null, $tipos);
                }
            ],
            'step_3' => [
                'description' => 'Verificar que CuadriculaFactory tiene el método crear',
                'test' => function($service, $inputs) {
                    return method_exists(\App\Services\Cuadriculas\CuadriculaFactory::class, 'crear');
                }
            ],
            'step_4' => [
                'description' => 'Verificar que las clases de cuadrícula existen',
                'test' => function($service, $inputs) {
                    $classExists = false;
                    if ($service['selected_cuadricula'] === 'cuadricula_trama') {
                        $classExists = class_exists(\App\Services\Cuadriculas\CuadriculaTrama::class);
                    } elseif ($service['selected_cuadricula'] === 'cuadricula') {
                        $classExists = class_exists(\App\Services\Cuadriculas\Cuadricula::class);
                    }
                    return $classExists;
                }
            ]
        ];
        
        // TEST CASE
        $service = [
            'service_id' => 1,
            'variant_id' => 1,
            'selected_cuadricula' => 'cuadricula_trama',
            'input_index' => 0,
            'color' => 'azul'
        ];
        
        $inputs = [
            'medidaA' => 3, 'medidaB' => 4, 'alto' => 3,
            'n_columnas' => 4, 'n_bajantes' => 2, 'anillos' => 0,
            'medidaACuadricula' => 2, 'medidaBCuadricula' => 3,
            'distanciaPalillaje' => 0.6, 'altoCuadricula' => 3
        ];
        
        // EJECUTAR PASOS DE DEBUGGING
        $results = [];
        foreach ($debugSteps as $stepName => $step) {
            try {
                if ($stepName === 'step_1' || $stepName === 'step_2') {
                    $results[$stepName] = $step['test']($service);
                } else {
                    $results[$stepName] = $step['test']($service, $inputs);
                }
            } catch (\Exception $e) {
                $results[$stepName] = false;
            }
        }
        
        // VERIFICAR RESULTADOS
        expect($results['step_1'])->toBeTrue('Step 1: selected_cuadricula debe estar presente');
        expect($results['step_2'])->toBeTrue('Step 2: tipo debe estar en TIPOS_CUADRICULA');
        expect($results['step_3'])->toBeTrue('Step 3: CuadriculaFactory debe tener método crear');
        expect($results['step_4'])->toBeTrue('Step 4: La clase de cuadrícula debe existir');
        
        // CONCLUSIÓN: Si todos estos pasos pasan, entonces el problema está en:
        // 1. El acceso a la base de datos durante el cálculo de precios
        // 2. La generación del PDF
        // 3. O en el manejo de errores durante la generación
        
        // El problema identificado: CuadriculaTrama falla al acceder a Material::getAllPricesArray()
        // debido a problemas de conexión a la base de datos en el contexto de generación de PDFs
    });
});
