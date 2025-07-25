<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductionCheck extends Command
{
    protected $signature = 'production:check';
    protected $description = 'Verificar que la aplicación esté lista para producción';

    public function handle()
    {
        $this->info('🔍 Verificando configuración de producción...');
        
        $checks = [
            'environment' => $this->checkEnvironment(),
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'permissions' => $this->checkPermissions(),
        ];
        
        $this->displayResults($checks);
        
        return $this->allChecksPassed($checks) ? 0 : 1;
    }
    
    private function checkEnvironment(): bool
    {
        $env = config('app.env');
        $debug = config('app.debug');
        $key = config('app.key');
        
        $this->line("🌍 Entorno: {$env}");
        $this->line("🐛 Debug: " . ($debug ? 'ON' : 'OFF'));
        $this->line("🔑 App Key: " . ($key ? 'SET' : 'NOT SET'));
        
        return $env === 'production' && !$debug && !empty($key);
    }
    
    private function checkDatabase(): bool
    {
        try {
            DB::connection()->getPdo();
            $this->line("🗄️ Base de datos: CONECTADA");
            
            $migrations = Artisan::call('migrate:status');
            $this->line("📋 Migraciones: VERIFICADAS");
            
            return true;
        } catch (\Exception $e) {
            $this->error("❌ Error de base de datos: " . $e->getMessage());
            return false;
        }
    }
    
    private function checkStorage(): bool
    {
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');
        
        $linkExists = is_link($publicPath);
        $storageWritable = is_writable($storagePath);
        
        $this->line("📁 Storage link: " . ($linkExists ? 'EXISTS' : 'MISSING'));
        $this->line("✍️ Storage writable: " . ($storageWritable ? 'YES' : 'NO'));
        
        return $linkExists && $storageWritable;
    }
    
    private function checkCache(): bool
    {
        $configCached = file_exists(base_path('bootstrap/cache/config.php'));
        $routesCached = file_exists(base_path('bootstrap/cache/routes-v7.php'));
        
        $this->line("⚡ Config cache: " . ($configCached ? 'EXISTS' : 'MISSING'));
        $this->line("🛣️ Routes cache: " . ($routesCached ? 'EXISTS' : 'MISSING'));
        
        return $configCached && $routesCached;
    }
    
    private function checkPermissions(): bool
    {
        $storagePath = storage_path();
        $cachePath = base_path('bootstrap/cache');
        
        $storagePerms = substr(sprintf('%o', fileperms($storagePath)), -4);
        $cachePerms = substr(sprintf('%o', fileperms($cachePath)), -4);
        
        $this->line("🔒 Storage perms: {$storagePerms}");
        $this->line("📦 Cache perms: {$cachePerms}");
        
        return is_writable($storagePath) && is_writable($cachePath);
    }
    
    private function displayResults(array $checks): void
    {
        $this->newLine();
        $this->info('📊 RESUMEN DE VERIFICACIÓN:');
        
        foreach ($checks as $check => $passed) {
            $status = $passed ? '✅' : '❌';
            $this->line("{$status} " . ucfirst($check));
        }
        
        if ($this->allChecksPassed($checks)) {
            $this->newLine();
            $this->info('🎉 ¡Aplicación lista para producción!');
        } else {
            $this->newLine();
            $this->error('⚠️ Hay problemas que resolver antes del deploy.');
        }
    }
    
    private function allChecksPassed(array $checks): bool
    {
        return !in_array(false, $checks, true);
    }
}
