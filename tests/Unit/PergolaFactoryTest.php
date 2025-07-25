<?php

use App\Services\Pergolas\PergolaFactory;
use App\Services\Pergolas\PergolaVidrio;
use App\Services\Pergolas\CorintiaPolicarbonato;
use App\Services\Pergolas\CorintiaRHVidrio;
use App\Services\Pergolas\CorintiaRHTejaAsfaltica;

describe('PergolaFactory', function () {
    
    it('creates PergolaVidrio for variant_id 1', function () {
        $data = [
            'medidaA' => 3,
            'medidaB' => 4,
            'alto' => 3,
            'n_columnas' => 4,
            'n_bajantes' => 2,
            'anillos' => 0
        ];
        
        $pergola = PergolaFactory::crear(1, $data);
        
        expect($pergola)->toBeInstanceOf(PergolaVidrio::class);
    });
    
    it('creates CorintiaPolicarbonato for variant_id 2', function () {
        $data = [
            'medidaA' => 3,
            'medidaB' => 4,
            'alto' => 3,
            'n_columnas' => 4,
            'n_bajantes' => 2,
            'anillos' => 0
        ];
        
        $pergola = PergolaFactory::crear(2, $data);
        
        expect($pergola)->toBeInstanceOf(CorintiaPolicarbonato::class);
    });
    
    it('creates CorintiaRHVidrio for variant_id 3', function () {
        $data = [
            'medidaA' => 3,
            'medidaB' => 4,
            'alto' => 3,
            'n_columnas' => 4,
            'n_bajantes' => 2,
            'anillos' => 0
        ];
        
        $pergola = PergolaFactory::crear(3, $data);
        
        expect($pergola)->toBeInstanceOf(CorintiaRHVidrio::class);
    });
    
    it('creates CorintiaRHTejaAsfaltica for variant_id 4', function () {
        $data = [
            'medidaA' => 3,
            'medidaB' => 4,
            'alto' => 3,
            'n_columnas' => 4,
            'n_bajantes' => 2,
            'anillos' => 0
        ];
        
        $pergola = PergolaFactory::crear(4, $data);
        
        expect($pergola)->toBeInstanceOf(CorintiaRHTejaAsfaltica::class);
    });
    
    it('throws exception for invalid variant_id', function () {
        $data = [
            'medidaA' => 3,
            'medidaB' => 4,
            'alto' => 3,
            'n_columnas' => 4,
            'n_bajantes' => 2,
            'anillos' => 0
        ];
        
        expect(fn() => PergolaFactory::crear(999, $data))
            ->toThrow(Exception::class, 'Tipo de pérgola desconocido: 999');
    });
});
