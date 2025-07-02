<?php

use App\Http\Middleware\AdminRoleMiddleware;
use App\Livewire\Admin\Dashboard\Overview;
use App\Livewire\Admin\VendorClients\Assign;
use App\Livewire\Admin\Vendors\Create;
use App\Livewire\Admin\Vendors\Index;
use App\Livewire\Auth\Login;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', Login::class)->name('login');

/*
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
*/

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware(['admin-role'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', Overview::class)->name('dashboard');

    // Vendedores (CRUD)
    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', Index::class)->name('index');
        Route::get('/create', Create::class)->name('create');
    });

    Route::prefix('assign')->name('assign.')->group( function () {
        Route::get('/', \App\Livewire\Admin\VendorClients\Index::class)->name('index');
        Route::get('/vendedores/{vendedor}/asignar-clientes', Assign::class)->name('create');
    });
});

Route::middleware(['vendor-role'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Vendor\Dashboard\Overview::class)->name('dashboard');

    // Clientes (CRUD)
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', \App\Livewire\Vendor\Clients\Index::class)->name('index');
        Route::get('/create', Create::class)->name('create');
    });

    // Quotes
    Route::prefix('quotes')->name('quotes.')->group( function() {
        Route::get('/', \App\Livewire\Vendor\Quotes\Index::class)->name('index');
        //Route::get('/create', Create::class)->name('create');
    });

});

require __DIR__ . '/auth.php';
