<?php

use App\Models\User;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Role;
use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Database Integration Tests', function () {
    
    beforeEach(function () {
        // Ejecutar migraciones
        $this->artisan('migrate:fresh');
        
        // Crear roles básicos
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'vendor']);
        
        // Crear algunos materiales básicos para las pruebas
        Material::create([
            'name' => 'vidrio',
            'code' => 'VID001',
            'price' => 50.00
        ]);
        
        Material::create([
            'name' => 'aluminio',
            'code' => 'ALU001', 
            'price' => 25.00
        ]);
    });
    
    it('can create a user with role', function () {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => 1,
            'status' => 'active'
        ]);
        
        expect($user->name)->toBe('Test User');
        expect($user->email)->toBe('test@example.com');
        expect($user->role_id)->toBe(1);
        expect($user->status)->toBe('active');
    });
    
    it('can create a client', function () {
        $client = Client::factory()->create([
            'name' => 'Cliente Test',
            'dni' => '12345678',
            'phone' => '123456789',
            'province' => 'Buenos Aires'
        ]);
        
        expect($client->name)->toBe('Cliente Test');
        expect($client->dni)->toBe('12345678');
        expect($client->phone)->toBe('123456789');
        expect($client->province)->toBe('Buenos Aires');
    });
    
    it('can create a quotation with relations', function () {
        $user = User::factory()->create(['role_id' => 1]);
        $client = Client::factory()->create();
        
        $quotation = Quotation::factory()->create([
            'user_id' => $user->id,
            'client_id' => $client->id
        ]);
        
        expect($quotation->user_id)->toBe($user->id);
        expect($quotation->client_id)->toBe($client->id);
        expect($quotation->user)->toBeInstanceOf(User::class);
        expect($quotation->client)->toBeInstanceOf(Client::class);
    });
    
    it('can retrieve material prices', function () {
        $prices = Material::getAllPricesArray();
        
        expect($prices)->toBeArray();
        expect($prices['vidrio'])->toBe(50.00);
        expect($prices['aluminio'])->toBe(25.00);
    });
    
    it('can filter active users', function () {
        User::factory()->create([
            'role_id' => 1,
            'status' => 'active'
        ]);
        
        User::factory()->create([
            'role_id' => 1, 
            'status' => 'inactive'
        ]);
        
        $activeUsers = User::where('status', 'active')->get();
        $inactiveUsers = User::where('status', 'inactive')->get();
        
        expect($activeUsers)->toHaveCount(1);
        expect($inactiveUsers)->toHaveCount(1);
    });
});
