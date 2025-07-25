<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Services;
use App\Models\ServiceVariants;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds for production environment.
     */
    public function run(): void
    {
        // Crear roles básicos
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $vendorRole = Role::firstOrCreate(['name' => 'vendor']);

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@cotizador.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('AdminSecure2024!'),
                'role_id' => $adminRole->id,
                'status' => 'active'
            ]
        );

        // Crear servicios básicos
        $pergolaService = Services::firstOrCreate(
            ['name' => 'Pérgolas'],
            ['description' => 'Servicio de pérgolas personalizadas']
        );

        $cuadriculaService = Services::firstOrCreate(
            ['name' => 'Cuadrículas'],
            ['description' => 'Servicio de cuadrículas especializadas']
        );

        // Crear variantes de pérgolas
        $pergolaVariants = [
            'Pérgola de Vidrio',
            'Corintia Policarbonato',
            'Corintia Vidrio',
            'Corrediza Policarbonato',
            'Corrediza Vidrio'
        ];

        foreach ($pergolaVariants as $variant) {
            ServiceVariants::firstOrCreate(
                [
                    'service_id' => $pergolaService->id,
                    'name' => $variant
                ],
                ['description' => "Variante: {$variant}"]
            );
        }

        // Crear variantes de cuadrículas
        $cuadriculaVariants = [
            'Cuadrícula Estándar',
            'Cuadrícula con Trama',
            'Cuadrícula Personalizada'
        ];

        foreach ($cuadriculaVariants as $variant) {
            ServiceVariants::firstOrCreate(
                [
                    'service_id' => $cuadriculaService->id,
                    'name' => $variant
                ],
                ['description' => "Variante: {$variant}"]
            );
        }

        $this->command->info('Datos de producción creados exitosamente.');
        $this->command->info('Usuario admin: admin@cotizador.com / AdminSecure2024!');
        $this->command->warn('¡CAMBIA LA CONTRASEÑA DEL ADMIN INMEDIATAMENTE!');
    }
}
