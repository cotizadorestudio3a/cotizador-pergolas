<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            [
                'name' => 'admin',
                'description' => 'Control total del sistema',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'name' => 'vendedor',
                'description' => 'Realiza cotizaciones',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
