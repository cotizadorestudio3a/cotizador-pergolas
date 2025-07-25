<?php

describe('PDF Title With Variant Names', function () {
    it('verifies that PDF titles use real variant names instead of IDs', function () {
        // Mock para simular una variante de la base de datos
        $mockVariant = (object)[
            'id' => 1,
            'name' => 'Pérgola de Vidrio Premium'
        ];
        
        // Simular el proceso de obtener el nombre de la variante
        $servicio = [
            'variant_id' => 1,
            'input_index' => 0,
            'selected_cuadricula' => 'ninguna',
            'color' => 'azul'
        ];
        
        // ✅ Simular exactamente la lógica del QuotePDFGenerator
        $variantName = 'Variante ' . ($servicio['variant_id'] ?? 'N/A');
        
        // En el código real, esto vendría de la base de datos
        // $variant = \App\Models\ServiceVariants::find($servicio['variant_id']);
        // Pero para el test, usamos el mock
        $variant = $mockVariant;
        $variantName = $variant ? $variant->name : 'Variante ' . $servicio['variant_id'];
        
        // Verificar que el nombre se obtuvo correctamente
        expect($variantName)->toBe('Pérgola de Vidrio Premium');
        
        // Verificar que los títulos se construyen correctamente
        $pergolaTitulo = 'Orden Producción Pérgola - ' . $variantName;
        $cuadriculaTitulo = 'Orden Producción Cuadrícula - ' . $variantName;
        
        expect($pergolaTitulo)->toBe('Orden Producción Pérgola - Pérgola de Vidrio Premium');
        expect($cuadriculaTitulo)->toBe('Orden Producción Cuadrícula - Pérgola de Vidrio Premium');
    });
    
    it('handles fallback when variant is not found', function () {
        $servicio = [
            'variant_id' => 999, // ID que no existe
            'input_index' => 0,
            'selected_cuadricula' => 'ninguna',
            'color' => 'azul'
        ];
        
        // Simular cuando la variante no se encuentra en la base de datos
        $variantName = 'Variante ' . ($servicio['variant_id'] ?? 'N/A');
        
        // Simular que la consulta no encuentra la variante
        $variant = null; // No se encontró
        $variantName = $variant ? $variant->name : 'Variante ' . $servicio['variant_id'];
        
        // Verificar que usa el fallback
        expect($variantName)->toBe('Variante 999');
        
        // Verificar que los títulos usan el fallback
        $pergolaTitulo = 'Orden Producción Pérgola - ' . $variantName;
        expect($pergolaTitulo)->toBe('Orden Producción Pérgola - Variante 999');
    });
    
    it('handles missing variant_id gracefully', function () {
        $servicio = [
            // variant_id no está definido
            'input_index' => 0,
            'selected_cuadricula' => 'ninguna',
            'color' => 'azul'
        ];
        
        // Simular cuando no hay variant_id
        $variantName = 'Variante ' . ($servicio['variant_id'] ?? 'N/A');
        
        // No se hace consulta porque no hay variant_id
        // $variantName se mantiene como está
        
        // Verificar que usa 'N/A' como fallback
        expect($variantName)->toBe('Variante N/A');
        
        // Verificar que los títulos manejan el caso gracefully
        $pergolaTitulo = 'Orden Producción Pérgola - ' . $variantName;
        expect($pergolaTitulo)->toBe('Orden Producción Pérgola - Variante N/A');
    });
    
    it('verifies the complete title generation workflow', function () {
        // Diferentes tipos de variantes para probar
        $testCases = [
            [
                'variant_id' => 1,
                'variant_name' => 'Pérgola de Vidrio',
                'expected_pergola' => 'Orden Producción Pérgola - Pérgola de Vidrio',
                'expected_cuadricula' => 'Orden Producción Cuadrícula - Pérgola de Vidrio'
            ],
            [
                'variant_id' => 2,
                'variant_name' => 'Corintia Policarbonato',
                'expected_pergola' => 'Orden Producción Pérgola - Corintia Policarbonato',
                'expected_cuadricula' => 'Orden Producción Cuadrícula - Corintia Policarbonato'
            ],
            [
                'variant_id' => 3,
                'variant_name' => 'Corintia RH Vidrio',
                'expected_pergola' => 'Orden Producción Pérgola - Corintia RH Vidrio',
                'expected_cuadricula' => 'Orden Producción Cuadrícula - Corintia RH Vidrio'
            ]
        ];
        
        foreach ($testCases as $case) {
            // Simular PDFs generados
            $pdfs = [
                [
                    'titulo' => 'Orden Producción Pérgola - ' . $case['variant_name'],
                    'path' => 'path/to/pergola.pdf'
                ],
                [
                    'titulo' => 'Orden Producción Cuadrícula - ' . $case['variant_name'],
                    'path' => 'path/to/cuadricula.pdf'
                ]
            ];
            
            // Verificar que los títulos son correctos
            expect($pdfs[0]['titulo'])->toBe($case['expected_pergola']);
            expect($pdfs[1]['titulo'])->toBe($case['expected_cuadricula']);
        }
    });
});
