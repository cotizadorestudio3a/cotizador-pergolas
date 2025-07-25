<?php

use App\Services\Pergolas\PergolaVidrio;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Pergola Calculations with Database', function () {
    
    beforeEach(function () {
        // Ejecutar migraciones
        $this->artisan('migrate:fresh');
        
        // Crear materiales necesarios para los cálculos
        Material::create(['name' => 'vidrio', 'code' => 'VID001', 'price' => 45.00]);
        Material::create(['name' => 'aluminio', 'code' => 'ALU001', 'price' => 15.00]);
        Material::create(['name' => 'tornillos', 'code' => 'TOR001', 'price' => 0.50]);
        Material::create(['name' => 'burletes', 'code' => 'BUR001', 'price' => 2.00]);
        Material::create(['name' => 'mano_de_obra', 'code' => 'MOB001', 'price' => 8.00]);
        Material::create(['name' => 'columnas', 'code' => 'COL001', 'price' => 120.00]);
    });
    
    it('can calculate pergola with real material prices', function () {
        $data = [
            'medidaA' => 3.0,
            'medidaB' => 4.0,
            'alto' => 3.0,
            'n_columnas' => 4,
            'n_bajantes' => 2,
            'anillos' => 0
        ];
        
        $pergola = new PergolaVidrio($data);
        $resultado = $pergola->calcular();
        
        expect($resultado)->toBeArray();
        expect($resultado)->toHaveKeys(['pvp_pergola', 'total_pergola']);
        expect($resultado['pvp_pergola'])->toBeFloat()->toBeGreaterThan(0);
        expect($resultado['total_pergola'])->toBeFloat()->toBeGreaterThan(0);
    });
    
    it('calculates different costs for different sizes', function () {
        $smallData = [
            'medidaA' => 2.0,
            'medidaB' => 2.0,
            'alto' => 2.5,
            'n_columnas' => 2,
            'n_bajantes' => 1,
            'anillos' => 0
        ];
        
        $largeData = [
            'medidaA' => 6.0,
            'medidaB' => 8.0,
            'alto' => 3.5,
            'n_columnas' => 8,
            'n_bajantes' => 4,
            'anillos' => 1
        ];
        
        $smallPergola = new PergolaVidrio($smallData);
        $largePergola = new PergolaVidrio($largeData);
        
        $smallResult = $smallPergola->calcular();
        $largeResult = $largePergola->calcular();
        
        // La pérgola grande debería costar más
        expect($largeResult['pvp_pergola'])->toBeGreaterThan($smallResult['pvp_pergola']);
    });
    
    it('can generate material details', function () {
        $data = [
            'medidaA' => 3.0,
            'medidaB' => 4.0,
            'alto' => 3.0,
            'n_columnas' => 4,
            'n_bajantes' => 2,
            'anillos' => 0
        ];
        
        $pergola = new PergolaVidrio($data);
        $pergola->calcular();
        
        // Verificar que las propiedades básicas están calculadas
        expect($pergola->area)->toBe(12.0); // 3 * 4
        expect($pergola->medidaA)->toBe(3.0);
        expect($pergola->medidaB)->toBe(4.0);
        expect($pergola->alto)->toBe(3.0);
    });
});
