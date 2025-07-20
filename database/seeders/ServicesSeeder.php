<?php

namespace Database\Seeders;

use App\Models\Services;
use App\Models\ServiceVariants;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Crear servicios básicos
        $pergolaVidrio = Services::create([
            'name' => 'Pérgola de Vidrio'
        ]);

        $pergolaCuadricula = Services::create([
            'name' => 'Pérgola con Cuadrícula'
        ]);

        $pergolaTrama = Services::create([
            'name' => 'Pérgola con Trama'
        ]);

        // Crear variantes para Pérgola de Vidrio
        ServiceVariants::create([
            'service_id' => $pergolaVidrio->id,
            'name' => 'Estándar'
        ]);

        ServiceVariants::create([
            'service_id' => $pergolaVidrio->id,
            'name' => 'Premium'
        ]);

        // Crear variantes para Pérgola con Cuadrícula
        ServiceVariants::create([
            'service_id' => $pergolaCuadricula->id,
            'name' => 'Básica'
        ]);

        ServiceVariants::create([
            'service_id' => $pergolaCuadricula->id,
            'name' => 'Reforzada'
        ]);

        // Crear variantes para Pérgola con Trama
        ServiceVariants::create([
            'service_id' => $pergolaTrama->id,
            'name' => 'Liviana'
        ]);

        ServiceVariants::create([
            'service_id' => $pergolaTrama->id,
            'name' => 'Pesada'
        ]);
    }
}
