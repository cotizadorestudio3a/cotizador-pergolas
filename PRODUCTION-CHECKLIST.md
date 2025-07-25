# 🚀 CHECKLIST DE PRODUCCIÓN - Cotizador Pérgolas

## 🔒 SEGURIDAD
- [ ] APP_ENV=production en .env
- [ ] APP_DEBUG=false en .env  
- [ ] APP_KEY generado y configurado
- [ ] Contraseñas de BD seguras
- [ ] SSL/HTTPS configurado
- [ ] Firewall configurado (solo puertos 22, 80, 443)
- [ ] Usuarios SSH sin contraseña (solo claves)

## 🗄️ BASE DE DATOS
- [ ] Base de datos de producción creada
- [ ] Migraciones ejecutadas: `php artisan migrate --force`
- [ ] Seeders de producción ejecutados
- [ ] Backup automático configurado
- [ ] Usuario admin creado y contraseña cambiada

## ⚡ RENDIMIENTO
- [ ] Cache de configuración: `php artisan config:cache`
- [ ] Cache de rutas: `php artisan route:cache`  
- [ ] Cache de vistas: `php artisan view:cache`
- [ ] Autoloader optimizado: `composer install --optimize-autoloader --no-dev`
- [ ] OPcache PHP habilitado

## 📁 ARCHIVOS Y PERMISOS
- [ ] Storage link creado: `php artisan storage:link`
- [ ] Permisos storage: `chmod -R 755 storage bootstrap/cache`
- [ ] Propietario correcto: `chown -R www-data:www-data storage bootstrap/cache`
- [ ] Directorio de PDFs creado y con permisos

## 🌐 SERVIDOR WEB
- [ ] Nginx/Apache configurado correctamente
- [ ] PHP-FPM configurado (PHP 8.3+)
- [ ] Límites de memoria PHP: min 256MB
- [ ] Límites de subida de archivos: min 100MB
- [ ] SSL certificado instalado y válido

## 📊 MONITOREO
- [ ] Logs configurados correctamente
- [ ] Rotación de logs configurada
- [ ] Monitoreo de errores (opcional: Sentry)
- [ ] Backup automático de BD configurado

## 🧪 TESTING
- [ ] Pruebas básicas ejecutadas
- [ ] Generación de PDFs funciona
- [ ] Sistema de autenticación funciona
- [ ] Formularios de cotización funcionan
- [ ] Carga de archivos funciona

## 📱 FUNCIONALIDADES ESPECÍFICAS
- [ ] Generación de PDFs comerciales
- [ ] Generación de PDFs de producción
- [ ] Cálculo de precios funciona
- [ ] Sistema de roles (admin/vendor)
- [ ] Gestión de clientes
- [ ] Gestión de servicios y variantes

## 🔐 POST-DEPLOY
- [ ] Cambiar contraseña del admin
- [ ] Crear usuarios adicionales si es necesario
- [ ] Configurar backup regular
- [ ] Documentar credenciales de acceso
- [ ] Probar todas las funcionalidades principales

## 📞 CONTACTO DE EMERGENCIA
- Desarrollador: [Tu nombre]
- Teléfono: [Tu teléfono]
- Email: [Tu email]

---
✅ Completado el: _______________
👤 Revisado por: _______________
