<?php

use App\Services\Quotes\QuotePDFGenerator;
use App\Models\Material;
use App\Models\User;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

describe('PDF Generation Integration Tests', function () {
    
    beforeEach(function () {
        // Configurar base de datos en memoria
        $this->artisan('migrate:fresh');
        
        // Crear roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'vendor']);
        
        // Crear materiales necesarios para los cálculos
        $this->createBasicMaterials();
        
        // Crear datos de prueba
        $this->user = User::factory()->create(['role_id' => 1, 'status' => 'active']);
        $this->client = Client::factory()->create([
            'name' => 'Cliente Test',
            'dni' => '12345678',
            'phone' => '123456789',
            'province' => 'Buenos Aires'
        ]);
        $this->quotation = Quotation::factory()->create([
            'user_id' => $this->user->id,
            'client_id' => $this->client->id
        ]);
        
        $this->generator = new QuotePDFGenerator();
    });
    
    it('generates only pergola PDF when no cuadricula is selected', function () {
        $services = [
            [
                'service_id' => 1,
                'variant_id' => 1,
                'selected_cuadricula' => null, // NO cuadrícula
                'input_index' => 0,
                'color' => 'azul'
            ]
        ];
        
        $inputsPorServicio = [
            [
                'medidaA' => 3,
                'medidaB' => 4,
                'alto' => 3,
                'n_columnas' => 4,
                'n_bajantes' => 2,
                'anillos' => 0
            ]
        ];
        
        $servicesDetail = [
            [
                'service_id' => 1,
                'variant_id' => 1,
                'pvp_pergola' => 1000,
                'inputs' => $inputsPorServicio[0]
            ]
        ];
        
        $pdfs = $this->generator->generateAllPDFs(
            $services,
            $inputsPorServicio,
            $this->quotation,
            $servicesDetail
        );
        
        // Debería generar: 1 comercial + 1 orden pérgola = 2 PDFs
        expect($pdfs)->toBeArray()->toHaveCount(2);
        
        // Verificar tipos de PDFs generados
        $titulos = collect($pdfs)->pluck('titulo')->toArray();
        expect($titulos)->toContain('Cotización Comercial');
        
        // Debería haber un PDF de pérgola
        $hasPergolaProduction = collect($titulos)->contains(function ($titulo) {
            return str_contains($titulo, 'Orden Producción Pérgola');
        });
        expect($hasPergolaProduction)->toBeTrue();
        
        // NO debería haber PDF de cuadrícula
        $hasCuadriculaProduction = collect($titulos)->contains(function ($titulo) {
            return str_contains($titulo, 'Orden Producción Cuadrícula');
        });
        expect($hasCuadriculaProduction)->toBeFalse();
    });
    
    it('generates both pergola and cuadricula PDFs when cuadricula is selected', function () {
        $services = [
            [
                'service_id' => 1,
                'variant_id' => 1,
                'selected_cuadricula' => 'cuadricula_trama', // SÍ cuadrícula
                'input_index' => 0,
                'color' => 'azul'
            ]
        ];
        
        $inputsPorServicio = [
            [
                'medidaA' => 3,
                'medidaB' => 4,
                'alto' => 3,
                'n_columnas' => 4,
                'n_bajantes' => 2,
                'anillos' => 0,
                // Datos de cuadrícula
                'medidaACuadricula' => 2,
                'medidaBCuadricula' => 3,
                'distanciaPalillaje' => 0.6,
                'altoCuadricula' => 3
            ]
        ];
        
        $servicesDetail = [
            [
                'service_id' => 1,
                'variant_id' => 1,
                'selected_cuadricula' => 'cuadricula_trama',
                'pvp_pergola' => 1000,
                'pvp_cuadricula' => 500,
                'inputs' => $inputsPorServicio[0]
            ]
        ];
        
        $pdfs = $this->generator->generateAllPDFs(
            $services,
            $inputsPorServicio,
            $this->quotation,
            $servicesDetail
        );
        
        // Debería generar: 1 comercial + 1 orden pérgola + 1 orden cuadrícula = 3 PDFs
        expect($pdfs)->toBeArray()->toHaveCount(3);
        
        // Verificar tipos de PDFs generados
        $titulos = collect($pdfs)->pluck('titulo')->toArray();
        expect($titulos)->toContain('Cotización Comercial');
        
        // Debería haber PDF de pérgola
        $hasPergolaProduction = collect($titulos)->contains(function ($titulo) {
            return str_contains($titulo, 'Orden Producción Pérgola');
        });
        expect($hasPergolaProduction)->toBeTrue();
        
        // Debería haber PDF de cuadrícula
        $hasCuadriculaProduction = collect($titulos)->contains(function ($titulo) {
            return str_contains($titulo, 'Orden Producción Cuadrícula');
        });
        expect($hasCuadriculaProduction)->toBeTrue();
    });
    
    it('handles multiple services with mixed cuadricula selections', function () {
        $services = [
            [
                'service_id' => 1,
                'variant_id' => 1,
                'selected_cuadricula' => 'cuadricula_trama', // Servicio 1: CON cuadrícula
                'input_index' => 0,
                'color' => 'azul'
            ],
            [
                'service_id' => 1,
                'variant_id' => 2,
                'selected_cuadricula' => null, // Servicio 2: SIN cuadrícula
                'input_index' => 1,
                'color' => 'negro'
            ]
        ];
        
        $inputsPorServicio = [
            [
                'medidaA' => 3,
                'medidaB' => 4,
                'alto' => 3,
                'n_columnas' => 4,
                'n_bajantes' => 2,
                'anillos' => 0,
                'medidaACuadricula' => 2,
                'medidaBCuadricula' => 3,
                'distanciaPalillaje' => 0.6,
                'altoCuadricula' => 3
            ],
            [
                'medidaA' => 2,
                'medidaB' => 3,
                'alto' => 2.5,
                'n_columnas' => 3,
                'n_bajantes' => 1,
                'anillos' => 1
            ]
        ];
        
        $pdfs = $this->generator->generateAllPDFs(
            $services,
            $inputsPorServicio,
            $this->quotation,
            []
        );
        
        // Debería generar: 1 comercial + 1 pérgola1 + 1 cuadrícula1 + 1 pérgola2 = 4 PDFs
        expect($pdfs)->toBeArray()->toHaveCount(4);
        
        $titulos = collect($pdfs)->pluck('titulo')->toArray();
        
        // Contar PDFs de cada tipo
        $comercialCount = collect($titulos)->filter(fn($t) => str_contains($t, 'Comercial'))->count();
        $pergolaCount = collect($titulos)->filter(fn($t) => str_contains($t, 'Orden Producción Pérgola'))->count();
        $cuadriculaCount = collect($titulos)->filter(fn($t) => str_contains($t, 'Orden Producción Cuadrícula'))->count();
        
        expect($comercialCount)->toBe(1);
        expect($pergolaCount)->toBe(2); // 2 servicios = 2 pérgolas
        expect($cuadriculaCount)->toBe(1); // Solo 1 servicio tiene cuadrícula
    });
    
    it('handles invalid cuadricula types gracefully', function () {
        $services = [
            [
                'service_id' => 1,
                'variant_id' => 1,
                'selected_cuadricula' => 'invalid_cuadricula_type', // Tipo inválido
                'input_index' => 0,
                'color' => 'azul'
            ]
        ];
        
        $inputsPorServicio = [
            [
                'medidaA' => 3,
                'medidaB' => 4,
                'alto' => 3,
                'n_columnas' => 4,
                'n_bajantes' => 2,
                'anillos' => 0
            ]
        ];
        
        $pdfs = $this->generator->generateAllPDFs(
            $services,
            $inputsPorServicio,
            $this->quotation,
            []
        );
        
        // Debería generar solo: 1 comercial + 1 pérgola = 2 PDFs (sin cuadrícula)
        expect($pdfs)->toBeArray()->toHaveCount(2);
        
        $titulos = collect($pdfs)->pluck('titulo')->toArray();
        
        // NO debería haber PDF de cuadrícula (debido al tipo inválido)
        $hasCuadriculaProduction = collect($titulos)->contains(function ($titulo) {
            return str_contains($titulo, 'Orden Producción Cuadrícula');
        });
        expect($hasCuadriculaProduction)->toBeFalse();
    });
});

// Helper function para crear materiales básicos
function createBasicMateriales() {
    $materiales = [
        ['name' => 'vidrio', 'code' => 'VID001', 'price' => 45.00],
        ['name' => 'aluminio', 'code' => 'ALU001', 'price' => 15.00],
        ['name' => 'tornillos', 'code' => 'TOR001', 'price' => 0.50],
        ['name' => 'burletes', 'code' => 'BUR001', 'price' => 2.00],
        ['name' => 'mano_de_obra', 'code' => 'MOB001', 'price' => 8.00],
        ['name' => 'columnas', 'code' => 'COL001', 'price' => 120.00],
        ['name' => 'cuadricula', 'code' => 'CUA001', 'price' => 10.00],
        ['name' => 'tornillo_cuadricula', 'code' => 'TCU001', 'price' => 0.06],
        ['name' => 'tornillos_t_cuadricula', 'code' => 'TTC001', 'price' => 0.06],
        ['name' => 't', 'code' => 'T001', 'price' => 5.42],
        ['name' => 'mano_de_obra_cuadricula_trama', 'code' => 'MCT001', 'price' => 5.00],
    ];
    
    foreach ($materiales as $material) {
        Material::create($material);
    }
}

// Usar helper en beforeEach
function createBasicMaterials() {
    createBasicMateriales();
}
