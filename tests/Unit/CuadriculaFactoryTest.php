<?php

use App\Services\Cuadriculas\CuadriculaFactory;
use App\Services\Cuadriculas\CuadriculaTrama;
use App\Services\Cuadriculas\Cuadricula;

describe('CuadriculaFactory', function () {
    
    it('creates CuadriculaTrama for cuadricula_trama type', function () {
        $data = [
            'medidaA' => 2,
            'medidaB' => 3,
            'distanciaPalillaje' => 0.6,
            'alto' => 3,
            'medidaACuadricula' => 2,
            'medidaBCuadricula' => 3,
            'distanciaPalillajeCuadricula' => 0.6,
            'altoCuadricula' => 3
        ];
        
        $cuadricula = CuadriculaFactory::crear('cuadricula_trama', $data);
        
        expect($cuadricula)->toBeInstanceOf(CuadriculaTrama::class);
    });
    
    it('creates Cuadricula for cuadricula type', function () {
        $data = [
            'medidaA' => 2,
            'medidaB' => 3,
            'distanciaPalillaje' => 0.6,
            'alto' => 3,
            'medidaACuadricula' => 2,
            'medidaBCuadricula' => 3,
            'distanciaPalillajeCuadricula' => 0.6,
            'altoCuadricula' => 3
        ];
        
        $cuadricula = CuadriculaFactory::crear('cuadricula', $data);
        
        expect($cuadricula)->toBeInstanceOf(Cuadricula::class);
    });
    
    it('throws exception for invalid cuadricula type', function () {
        $data = [
            'medidaA' => 2,
            'medidaB' => 3,
            'distanciaPalillaje' => 0.6,
            'alto' => 3,
            'medidaACuadricula' => 2,
            'medidaBCuadricula' => 3,
            'distanciaPalillajeCuadricula' => 0.6,
            'altoCuadricula' => 3
        ];
        
        expect(fn() => CuadriculaFactory::crear('invalid_type', $data))
            ->toThrow(\Exception::class);
    });
});
