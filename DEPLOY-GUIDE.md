# 🚀 DEPLOY COMPLETO PARA PRODUCCIÓN

## Script de Deploy Rápido
```bash
# 1. Verificar configuración
php artisan production:check

# 2. Ejecutar deploy
chmod +x deploy-production.sh
./deploy-production.sh

# 3. Verificar post-deploy
php artisan production:check
```

## Checklist de Verificación Pre-Deploy

### ✅ CONFIGURACIÓN
- [ ] `.env` configurado para producción
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` generada
- [ ] Base de datos configurada
- [ ] Mail configurado
- [ ] SSL/HTTPS configurado

### ✅ SEGURIDAD
- [ ] Permisos correctos (755/644)
- [ ] Storage protegido
- [ ] Headers de seguridad
- [ ] Rate limiting activo
- [ ] Logs configurados

### ✅ RENDIMIENTO
- [ ] Cache de configuración
- [ ] Cache de rutas
- [ ] Cache de vistas
- [ ] OPcache habilitado
- [ ] Assets compilados

### ✅ MONITOREO
- [ ] Logs funcionando
- [ ] Error tracking
- [ ] Backups configurados
- [ ] Health checks

## Comandos de Mantenimiento

### Deploy Rápido
```bash
# Deploy completo
./deploy-production.sh

# Solo cache
php artisan optimize

# Solo assets
npm run build
```

### Verificación
```bash
# Check completo
php artisan production:check

# Estado específico
php artisan migrate:status
php artisan queue:work --stop-when-empty
```

### Backup
```bash
# Base de datos
mysqldump -u user -p database > backup_$(date +%Y%m%d).sql

# Archivos
tar -czf files_backup_$(date +%Y%m%d).tar.gz storage/ public/storage/
```

### Rollback de Emergencia
```bash
# Restaurar cache anterior
php artisan cache:clear
php artisan config:clear

# Rollback de código
git reset --hard HEAD~1

# Re-deploy
./deploy-production.sh
```

## URLs de Verificación Post-Deploy

- **Aplicación:** https://tu-dominio.com
- **Login Admin:** https://tu-dominio.com/login
- **Health Check:** https://tu-dominio.com/health
- **PDFs:** https://tu-dominio.com/storage/pdfs/

## Credenciales de Prueba

### Admin (creado por ProductionSeeder)
- **Email:** admin@empresa.com
- **Password:** Admin123!

### Cliente de Prueba
- **Email:** cliente@test.com
- **Password:** Cliente123!

## Comandos de Emergencia

### Si algo falla:
```bash
# Modo mantenimiento
php artisan down

# Limpiar todo
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reactivar
php artisan up
```

### Si la base de datos falla:
```bash
# Verificar conexión
php artisan tinker
>>> DB::connection()->getPdo();

# Ejecutar migraciones
php artisan migrate --force
```

### Si los permisos fallan:
```bash
# Corregir permisos
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 775 storage bootstrap/cache
```

---

**🎯 OBJETIVO:** Deploy seguro y verificado en producción

**⏱️ TIEMPO ESTIMADO:** 15-30 minutos

**📞 SOPORTE:** Mantener backup antes del deploy
