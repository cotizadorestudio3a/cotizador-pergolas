<?php

use App\Services\Pergolas\PergolaVidrio;
use Illuminate\Foundation\Testing\RefreshDatabase;

describe('PergolaVidrio Basic Tests', function () {
    
    it('can be instantiated with basic data', function () {
        $data = [
            'medidaA' => 3.0,
            'medidaB' => 4.0,
            'alto' => 3.0,
            'n_columnas' => 4,
            'n_bajantes' => 2,
            'anillos' => 0
        ];
        
        $pergola = new PergolaVidrio($data);
        
        expect($pergola)->toBeInstanceOf(PergolaVidrio::class);
        expect($pergola->medidaA)->toBe(3.0);
        expect($pergola->medidaB)->toBe(4.0);
        expect($pergola->alto)->toBe(3.0);
    });
    
    it('has correct properties after instantiation', function () {
        $data = [
            'medidaA' => 5.0,
            'medidaB' => 6.0,
            'alto' => 2.5,
            'n_columnas' => 6,
            'n_bajantes' => 3,
            'anillos' => 1
        ];
        
        $pergola = new PergolaVidrio($data);
        
        expect($pergola->medidaA)->toBe(5.0);
        expect($pergola->medidaB)->toBe(6.0);
        expect($pergola->alto)->toBe(2.5);
        expect($pergola->n_columnas)->toBe(6);
        expect($pergola->n_bajantes)->toBe(3);
        expect($pergola->anillos)->toBe(1);
    });
    
    it('handles different measurement combinations', function () {
        $smallPergola = new PergolaVidrio([
            'medidaA' => 1.0,
            'medidaB' => 1.0,
            'alto' => 2.0,
            'n_columnas' => 2,
            'n_bajantes' => 1,
            'anillos' => 0
        ]);
        
        $largePergola = new PergolaVidrio([
            'medidaA' => 10.0,
            'medidaB' => 10.0,
            'alto' => 3.0,
            'n_columnas' => 8,
            'n_bajantes' => 4,
            'anillos' => 2
        ]);
        
        expect($smallPergola->medidaA)->toBeLessThan($largePergola->medidaA);
        expect($smallPergola->medidaB)->toBeLessThan($largePergola->medidaB);
        expect($smallPergola->n_columnas)->toBeLessThan($largePergola->n_columnas);
    });
});
