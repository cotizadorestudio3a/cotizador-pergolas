#!/bin/bash

echo "🚀 Optimizando aplicación para producción..."

# 1. Limpiar caché existente
echo "📦 Limpiando caché..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Optimizar para producción
echo "⚡ Optimizando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan livewire:discover

# 3. Compilar assets de frontend
echo "🎨 Compilando assets para producción..."
npm ci
npm run build

# 3.1. Publicar assets de Flux
echo "⚡ Publicando assets de Flux..."
php artisan flux:publish

# 4. Optimizar Composer
echo "📚 Optimizando autoloader..."
composer install --optimize-autoloader --no-dev

# 5. Optimizar Base de Datos
echo "🗄️ Optimizando base de datos..."
php artisan migrate --force

# 6. Crear enlaces simbólicos
echo "🔗 Creando enlaces de almacenamiento..."
php artisan storage:link

# 7. Generar clave de aplicación si no existe
echo "🔑 Verificando clave de aplicación..."
php artisan key:generate --force

# 8. Permisos correctos
echo "🔒 Configurando permisos..."
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "✅ Optimización completada!"
echo "🔐 No olvides configurar el .env de producción"
echo "🎯 Assets compilados y listos para producción"
