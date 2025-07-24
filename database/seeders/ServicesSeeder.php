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
            'name' => 'Pérgola con Corintia'
        ]);

        $pergolaCorrediza = Services::create([
            'name' => 'Pérgola corrediza'
        ]);

        // Crear variantes para Pérgola de corintia
        ServiceVariants::create([
            'service_id' => $pergolaVidrio->id,
            'name' => 'Vidrio'
        ]);

        ServiceVariants::create([
            'service_id' => $pergolaVidrio->id,
            'name' => 'Policarbonato'
        ]);

        ServiceVariants::create([
            'service_id' => $pergolaVidrio->id,
            'name' => 'RH - Vidrio'
        ]);


        ServiceVariants::create([
            'service_id' => $pergolaVidrio->id,
            'name' => 'RH - Teja asfaltica'
        ]);

        // Crear variantes para Pérgola corrediza
        ServiceVariants::create([
            'service_id' => $pergolaCorrediza->id,
            'name' => 'Vidrio'
        ]);

        ServiceVariants::create([
            'service_id' => $pergolaCorrediza->id,
            'name' => 'Policarbonato'
        ]);
    }
}
