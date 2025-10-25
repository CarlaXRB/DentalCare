#!/bin/bash
# run.sh - Ejecución del servicio web Laravel en Cloud Run

# CRÍTICO 1: Asignar permisos de escritura a los directorios de almacenamiento y caché.
# Esto es CRUCIAL para que Laravel funcione en un contenedor sin errores.
echo "===> Setting storage permissions..."
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# CRÍTICO 2: Limpiar cachés y regenerar la configuración
# Se ejecuta DESPUÉS de que Cloud Run inyecte las variables de entorno.
echo "===> Cleaning and regenerating configuration cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
# php artisan package:discover (Opcional, se mantiene si es necesario, pero no es crítico)
php artisan config:cache
# NOTA: Se ELIMINA 'php artisan key:generate' porque la clave se pasa por variable de entorno.

# 3. Configurar el puerto de Apache
# Reemplaza la escucha de Apache por el puerto de Cloud Run ($PORT)
echo "===> Configuring Apache port..."
sed -i "s/8080/${PORT:-8080}/g" /etc/apache2/ports.conf
sed -i "s/8080/${PORT:-8080}/g" /etc/apache2/sites-available/000-default.conf

# 4. Iniciar Apache en primer plano
echo "===> Starting Apache in foreground..."
exec apache2-foreground