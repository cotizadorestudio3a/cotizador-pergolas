<?php

use App\Services\Cuadriculas\CuadriculaFactory;

describe('Static Variable Fix Verification', function () {
    it('verifies that static variables have been removed from obtenerCodigoMaterial methods', function () {
        // Verificar que ya no hay variables estáticas en los archivos
        $cuadriculaContent = file_get_contents(app_path('Services/Cuadriculas/Cuadricula.php'));
        $cuadriculaTramaContent = file_get_contents(app_path('Services/Cuadriculas/CuadriculaTrama.php'));
        
        // Verificar que las variables estáticas fueron eliminadas
        expect($cuadriculaContent)->not->toContain('static $materialesConCodigos');
        expect($cuadriculaTramaContent)->not->toContain('static $materialesConCodigos');
        
        // Verificar que los métodos siguen funcionando
        expect($cuadriculaContent)->toContain('private function obtenerCodigoMaterial');
        expect($cuadriculaTramaContent)->toContain('private function obtenerCodigoMaterial');
    });
    
    it('verifies that multiple cuadricula instances work independently', function () {
        $data1 = [
            'medidaA' => 100,
            'medidaB' => 200,
            'distanciaPalillaje' => 50,
            'alto' => 30
        ];
        
        $data2 = [
            'medidaA' => 150,
            'medidaB' => 250,
            'distanciaPalillaje' => 75,
            'alto' => 40
        ];
        
        // Crear múltiples instancias
        $cuadricula1 = CuadriculaFactory::crear('cuadricula', $data1);
        $cuadricula2 = CuadriculaFactory::crear('cuadricula', $data2);
        
        // Verificar que son instancias diferentes
        expect($cuadricula1)->not->toBe($cuadricula2);
        
        // Verificar que tienen datos diferentes
        expect($cuadricula1->medidaACuadricula)->toBe(100);
        expect($cuadricula2->medidaACuadricula)->toBe(150);
        
        // Verificar que no comparten estado
        $cuadricula1->medidaACuadricula = 999;
        expect($cuadricula2->medidaACuadricula)->toBe(150); // No debe cambiar
    });
    
    it('verifies that multiple cuadricula trama instances work independently', function () {
        $data1 = [
            'medidaA' => 100,
            'medidaB' => 200,
            'distanciaPalillaje' => 50,
            'alto' => 30
        ];
        
        $data2 = [
            'medidaA' => 150,
            'medidaB' => 250,
            'distanciaPalillaje' => 75,
            'alto' => 40
        ];
        
        // Crear múltiples instancias
        $cuadricula1 = CuadriculaFactory::crear('cuadricula_trama', $data1);
        $cuadricula2 = CuadriculaFactory::crear('cuadricula_trama', $data2);
        
        // Verificar que son instancias diferentes
        expect($cuadricula1)->not->toBe($cuadricula2);
        
        // Verificar que tienen datos diferentes
        expect($cuadricula1->medidaACuadricula)->toBe(100);
        expect($cuadricula2->medidaACuadricula)->toBe(150);
        
        // Verificar que no comparten estado
        $cuadricula1->medidaACuadricula = 999;
        expect($cuadricula2->medidaACuadricula)->toBe(150); // No debe cambiar
    });
});
