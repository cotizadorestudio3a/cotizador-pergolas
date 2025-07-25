<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Flux Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for Livewire Flux.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Production Optimization
    |--------------------------------------------------------------------------
    |
    | When enabled, Flux will optimize component rendering and caching
    | for production environments.
    |
    */
    
    'optimize' => env('APP_ENV') === 'production',

    /*
    |--------------------------------------------------------------------------
    | Component Caching
    |--------------------------------------------------------------------------
    |
    | Enable component caching to improve performance in production.
    |
    */
    
    'cache_components' => env('FLUX_CACHE_COMPONENTS', true),

    /*
    |--------------------------------------------------------------------------
    | Asset Publishing
    |--------------------------------------------------------------------------
    |
    | Configure how Flux assets are published and served.
    |
    */
    
    'assets' => [
        'publish_on_build' => true,
        'cache_bust' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | Configure Flux theme settings for production.
    |
    */
    
    'theme' => [
        'cache' => true,
        'minify' => env('APP_ENV') === 'production',
    ],
];
