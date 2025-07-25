<?php

use App\Models\User;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\QuotationPdf;
use App\Models\Services;
use App\Models\ServiceVariants;
use Livewire\Livewire;
use App\Livewire\Vendor\Quotations\ViewModal;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->client = Client::factory()->create();
    
    // Crear servicio y variante
    $this->service = Services::factory()->create(['name' => 'Pérgola']);
    $this->variant = ServiceVariants::factory()->create([
        'service_id' => $this->service->id,
        'name' => 'Pérgola de Vidrio'
    ]);
    
    // Crear cotización
    $this->quotation = Quotation::factory()->create([
        'user_id' => $this->user->id,
        'client_id' => $this->client->id,
        'total' => 1000.00,
        'iva' => 150.00,
        'pvp' => 850.00
    ]);
    
    // Crear item de cotización
    $this->quotationItem = QuotationItem::factory()->create([
        'quotation_id' => $this->quotation->id,
        'service_id' => $this->service->id,
        'service_variant_id' => $this->variant->id,
        'calculated_price' => 850.00
    ]);
});

it('can open the view modal with quotation data', function () {
    $this->actingAs($this->user);
    
    Livewire::test(ViewModal::class)
        ->call('openViewModal', $this->quotation->id)
        ->assertSet('showModal', true)
        ->assertSet('quotation.id', $this->quotation->id)
        ->assertSee($this->client->name)
        ->assertSee($this->service->name)
        ->assertSee($this->variant->name);
});

it('displays message when no PDFs are available', function () {
    $this->actingAs($this->user);
    
    Livewire::test(ViewModal::class)
        ->call('openViewModal', $this->quotation->id)
        ->assertSee('No se han generado PDFs para esta cotización aún');
});

it('displays PDFs when available', function () {
    $this->actingAs($this->user);
    
    // Crear PDFs de prueba
    $commercialPdf = QuotationPdf::create([
        'quotation_id' => $this->quotation->id,
        'pdf_type' => 'comercial',
        'title' => 'Cotización Comercial',
        'file_path' => 'quotations/test-commercial.pdf',
        'file_name' => 'test-commercial.pdf',
        'file_size' => 1024,
        'generated_at' => now(),
        'status' => 'generated'
    ]);
    
    $productionPdf = QuotationPdf::create([
        'quotation_id' => $this->quotation->id,
        'quotation_item_id' => $this->quotationItem->id,
        'pdf_type' => 'produccion_pergola',
        'title' => 'PDF de Producción - Pérgola',
        'file_path' => 'quotations/test-production.pdf',
        'file_name' => 'test-production.pdf',
        'service_variant_id' => $this->variant->id,
        'variant_name' => $this->variant->name,
        'service_index' => 1,
        'file_size' => 2048,
        'generated_at' => now(),
        'status' => 'generated'
    ]);
    
    Livewire::test(ViewModal::class)
        ->call('openViewModal', $this->quotation->id)
        ->assertSee('PDFs de la Cotización')
        ->assertSee('PDF Comercial')
        ->assertSee('PDFs de Producción - Pérgolas')
        ->assertSee('Ver PDF')
        ->assertSee('Descargar');
});

it('can close the modal', function () {
    $this->actingAs($this->user);
    
    Livewire::test(ViewModal::class)
        ->call('openViewModal', $this->quotation->id)
        ->assertSet('showModal', true)
        ->call('closeModal')
        ->assertSet('showModal', false)
        ->assertSet('quotation', null);
});

it('only shows quotations for authenticated user', function () {
    $otherUser = User::factory()->create();
    $otherQuotation = Quotation::factory()->create(['user_id' => $otherUser->id]);
    
    $this->actingAs($this->user);
    
    Livewire::test(ViewModal::class)
        ->call('openViewModal', $otherQuotation->id)
        ->assertSet('showModal', false)
        ->assertSet('quotation', null);
});
