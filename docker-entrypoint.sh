#!/bin/sh

# 1. Chequeo y reparación de permisos del storage
# Esto es vital en Cloud Run, donde el UID del usuario es dinámico.
chmod -R 777 /var/www/html/storage/

# 2. Ejecutar optimizaciones de Laravel
# Esto minimiza el tiempo de arranque y reduce el uso de memoria.
php /var/www/html/artisan optimize:clear
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

# 3. Ejecutar migraciones (OPCIONAL: Solo si tu app lo necesita al inicio)
# Si tus migraciones son lentas, considera hacerlas manualmente o fuera del entrypoint.
# php /var/www/html/artisan migrate --force

# 4. Iniciar Supervisor (en primer plano)
# Una vez que Laravel está optimizado, ejecuta el gestor de procesos.
exec /usr/bin/supervisord -c /etc/supervisord.conf
