#!/bin/bash
# run.sh - Ejecución del servicio web en Cloud Run

# CRÍTICO: Ejecuta comandos Artisan después de que Cloud Run inyecte las variables de entorno
echo "===> Running Laravel commands..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan package:discover
php artisan key:generate 

# Reemplaza el puerto 8080 en Apache con el puerto que Cloud Run proporciona ($PORT)
echo "===> Configuring Apache port..."
sed -i "s/8080/${PORT:-8080}/g" /etc/apache2/ports.conf
sed -i "s/8080/${PORT:-8080}/g" /etc/apache2/sites-available/000-default.conf

# Ejecuta Apache en primer plano
echo "===> Starting Apache..."
apache2-foreground