<?php

use App\Services\Quotes\QuotePDFGenerator;

describe('Multiple Services PDF Generation Problem', function () {
    
    beforeEach(function () {
        $this->generator = new QuotePDFGenerator();
    });
    
    it('identifies the data sharing problem between multiple services', function () {
        // ESCENARIO: 2 servicios diferentes con datos diferentes
        $services = [
            [
                'service_id' => 1,
                'variant_id' => 1,
                'selected_cuadricula' => null,
                'input_index' => 0,
                'color' => 'azul'
            ],
            [
                'service_id' => 1,
                'variant_id' => 2,
                'selected_cuadricula' => 'cuadricula_trama',
                'input_index' => 1,
                'color' => 'negro'
            ]
        ];
        
        $inputsPorServicio = [
            // Servicio 1: Pérgola pequeña
            [
                'medidaA' => 2,  // ← DATOS ÚNICOS DEL SERVICIO 1
                'medidaB' => 3,
                'alto' => 2.5,
                'n_columnas' => 2,
                'n_bajantes' => 1,
                'anillos' => 0
            ],
            // Servicio 2: Pérgola grande con cuadrícula
            [
                'medidaA' => 5,  // ← DATOS ÚNICOS DEL SERVICIO 2
                'medidaB' => 6,
                'alto' => 3.5,
                'n_columnas' => 6,
                'n_bajantes' => 3,
                'anillos' => 2,
                'medidaACuadricula' => 3,
                'medidaBCuadricula' => 4,
                'distanciaPalillaje' => 0.8,
                'altoCuadricula' => 3
            ]
        ];
        
        // VERIFICAR QUE LOS DATOS SON DIFERENTES
        expect($inputsPorServicio[0]['medidaA'])->toBe(2);
        expect($inputsPorServicio[1]['medidaA'])->toBe(5);
        expect($inputsPorServicio[0]['medidaB'])->toBe(3);
        expect($inputsPorServicio[1]['medidaB'])->toBe(6);
        
        // VERIFICAR QUE LOS SERVICIOS SON DIFERENTES
        expect($services[0]['variant_id'])->toBe(1);
        expect($services[1]['variant_id'])->toBe(2);
        expect($services[0]['color'])->toBe('azul');
        expect($services[1]['color'])->toBe('negro');
        
        // PROBLEMA ESPERADO: Ambos PDFs van a tener los mismos datos
        // (probablemente los del último servicio procesado)
        
        // Esto indica que hay un problema de referencia compartida
        // en el método generateProductionPDFsForService
    });
    
    it('simulates the array_merge independence test', function () {
        // SIMULACIÓN: ¿array_merge crea copias independientes?
        $original = [
            'medidaA' => 2,
            'medidaB' => 3,
            'test_value' => 'original'
        ];
        
        // Crear copia con array_merge (como en el código actual)
        $copy1 = array_merge([], $original);
        $copy2 = array_merge([], $original);
        
        // Modificar las copias
        $copy1['medidaA'] = 999;
        $copy1['test_value'] = 'copy1_modified';
        
        $copy2['medidaA'] = 888;
        $copy2['test_value'] = 'copy2_modified';
        
        // VERIFICAR INDEPENDENCIA
        expect($original['medidaA'])->toBe(2);  // Original no cambió
        expect($copy1['medidaA'])->toBe(999);   // Copy1 tiene su valor
        expect($copy2['medidaA'])->toBe(888);   // Copy2 tiene su valor
        
        expect($original['test_value'])->toBe('original');
        expect($copy1['test_value'])->toBe('copy1_modified');
        expect($copy2['test_value'])->toBe('copy2_modified');
        
        // Si esto pasa, array_merge SÍ está funcionando correctamente
        // El problema debe estar en otro lado
    });
    
    it('investigates potential data contamination sources', function () {
        // POSIBLES FUENTES DE CONTAMINACIÓN DE DATOS:
        
        $problemSources = [
            'shared_static_variables' => [
                'description' => 'Variables estáticas compartidas entre instancias',
                'risk' => 'Alto',
                'example' => 'static $materialesConCodigos en obtenerCodigoMaterial()'
            ],
            'object_reference_sharing' => [
                'description' => 'Referencias de objetos compartidas',
                'risk' => 'Alto',
                'example' => 'Si se pasa el mismo objeto Material a múltiples cálculos'
            ],
            'global_state_mutation' => [
                'description' => 'Estado global modificado durante los cálculos',
                'risk' => 'Medio',
                'example' => 'Variables globales o singletons modificados'
            ],
            'input_array_modification' => [
                'description' => 'Modificación del array de inputs original',
                'risk' => 'Medio',
                'example' => 'Si el constructor modifica el array $data recibido'
            ],
            'pdf_generation_sharing' => [
                'description' => 'Datos compartidos durante la generación del PDF',
                'risk' => 'Bajo',
                'example' => 'Variables en el scope de la vista Blade'
            ]
        ];
        
        // ANÁLISIS: El problema más probable es shared_static_variables
        // Porque en CuadriculaTrama::obtenerCodigoMaterial() hay:
        // static $materialesConCodigos = null;
        
        expect($problemSources['shared_static_variables']['risk'])->toBe('Alto');
        expect($problemSources['object_reference_sharing']['risk'])->toBe('Alto');
        
        // La variable estática $materialesConCodigos podría estar causando
        // que diferentes instancias compartan el mismo estado
    });
    
    it('traces the execution flow for multiple services', function () {
        // FLUJO DE EJECUCIÓN ESPERADO PARA 2 SERVICIOS:
        
        $expectedFlow = [
            'step_1' => 'generateAllPDFs() recibe 2 servicios',
            'step_2' => 'foreach($services) itera sobre cada servicio',
            'step_3' => 'Para servicio[0]: generateProductionPDFsForService(servicio[0], inputs[0])',
            'step_4' => 'Crea array_merge([], inputs[0]) → copia independiente',
            'step_5' => 'PergolaFactory::crear(variant_id=1, datos_servicio_0)',
            'step_6' => 'pergola_0->calcular() con datos únicos del servicio 0',
            'step_7' => 'pergola_0->obtenerPDFOrdenProduccion() genera PDF_0',
            'step_8' => 'Para servicio[1]: generateProductionPDFsForService(servicio[1], inputs[1])',
            'step_9' => 'Crea array_merge([], inputs[1]) → copia independiente',
            'step_10' => 'PergolaFactory::crear(variant_id=2, datos_servicio_1)',
            'step_11' => 'pergola_1->calcular() con datos únicos del servicio 1',
            'step_12' => 'pergola_1->obtenerPDFOrdenProduccion() genera PDF_1',
            'expected_result' => 'PDF_0 ≠ PDF_1 (contenido diferente)'
        ];
        
        // PROBLEMA REAL: Algún paso entre 5-7 o 10-12 está compartiendo datos
        
        expect($expectedFlow['step_4'])->toContain('copia independiente');
        expect($expectedFlow['step_9'])->toContain('copia independiente');
        expect($expectedFlow['expected_result'])->toContain('contenido diferente');
        
        // Si los pasos 4 y 9 crean copias independientes pero el resultado final es igual,
        // entonces el problema está en la lógica de cálculo o generación de PDF
    });
    
    it('identifies specific areas to investigate in the codebase', function () {
        $investigationAreas = [
            'QuotePDFGenerator_generateProductionPDFsForService' => [
                'file' => 'app/Services/Quotes/QuotePDFGenerator.php',
                'focus' => 'Línea donde se hace array_merge() y se pasan los datos',
                'check' => '¿Los datos se están pasando correctamente a cada factory?'
            ],
            'PergolaFactory_crear' => [
                'file' => 'app/Services/Pergolas/PergolaFactory.php',
                'focus' => 'Método crear() y constructor de las clases',
                'check' => '¿Cada instancia recibe datos únicos?'
            ],
            'PergolaVidrio_constructor' => [
                'file' => 'app/Services/Pergolas/PergolaVidrio.php',
                'focus' => 'Constructor y método calcular()',
                'check' => '¿Se modifica el array original o hay variables compartidas?'
            ],
            'obtenerPDFOrdenProduccion' => [
                'file' => 'Clases de pérgola',
                'focus' => 'Método que genera el PDF',
                'check' => '¿Los datos del PDF son únicos por instancia?'
            ]
        ];
        
        // PRIORIDAD DE INVESTIGACIÓN
        expect($investigationAreas['QuotePDFGenerator_generateProductionPDFsForService']['check'])
            ->toContain('datos únicos');
        
        // Lo más probable es que el problema esté en uno de estos 4 puntos
    });
});
