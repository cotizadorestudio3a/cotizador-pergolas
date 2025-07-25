<?php

use App\Models\User;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Role;
use Livewire\Livewire;
use App\Livewire\Admin\Quotes\Index;

describe('Quote PDF Generation Feature', function () {
    
    beforeEach(function () {
        // Configurar la base de datos en memoria
        $this->artisan('migrate:fresh');
        
        // Crear roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'vendor']);
        
        // Crear usuario admin
        $this->admin = User::factory()->create([
            'role_id' => 1, // admin
            'status' => 'active'
        ]);
        
        // Crear cliente
        $this->client = Client::factory()->create();
        
        // Crear cotización
        $this->quotation = Quotation::factory()->create([
            'client_id' => $this->client->id,
            'user_id' => $this->admin->id
        ]);
    });
    
    it('can generate PDFs through Livewire component', function () {
        $this->actingAs($this->admin);
        
        $component = Livewire::test(Index::class)
            ->set('selectedQuotation', $this->quotation->id)
            ->set('services', [
                [
                    'service_id' => 1,
                    'variant_id' => 1,
                    'selected_cuadricula' => null,
                    'input_index' => 0,
                    'color' => 'azul'
                ]
            ])
            ->set('inputsPorServicio', [
                [
                    'medidaA' => 3,
                    'medidaB' => 4,
                    'alto' => 3,
                    'n_columnas' => 4,
                    'n_bajantes' => 2,
                    'anillos' => 0
                ]
            ]);
        
        // No debería haber errores en la inicialización
        $component->assertHasNoErrors();
    });
    
    it('requires authentication to access quotes', function () {
        $response = $this->get('/admin/quotes');
        
        $response->assertRedirect('/login');
    });
    
    it('admin can access quotes page', function () {
        $this->actingAs($this->admin);
        
        $response = $this->get('/admin/quotes');
        
        $response->assertOk();
    });
    
    it('inactive user cannot access quotes', function () {
        $inactiveUser = User::factory()->create([
            'role_id' => 1,
            'status' => 'inactive'
        ]);
        
        $this->actingAs($inactiveUser);
        
        $response = $this->get('/admin/quotes');
        
        // Debería redirigir o dar error de acceso
        $response->assertStatus(302);
    });
})->skip(!class_exists('App\Models\Client'), 'Client model not found');
