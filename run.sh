# Sobrescribe el archivo run.sh con el contenido corregido:
cat << 'EOF' > run.sh
#!/usr/bin/env bash

# 1. Configurar permisos de escritura
chmod -R 777 storage
chmod -R 777 bootstrap/cache

# 2. Limpiar cachés para forzar la carga de las variables de entorno de Cloud Run
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 3. Optimizar (opcional pero bueno)
php artisan config:cache
php artisan route:cache

# 4. Iniciar el servidor en el puerto 8080
php artisan serve --host=0.0.0.0 --port=8080
EOF