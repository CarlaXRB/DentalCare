#!/bin/sh

# 1. Chequeo y reparación de permisos del storage (esencial)
chmod -R 777 /var/www/html/storage/

# 2. Comando básico de optimización que limpia configuraciones viejas.
# Solo dejamos optimize:clear, ya que no depende de la base de datos para funcionar.
php /var/www/html/artisan optimize:clear

# 3. Ejecutar migraciones (OPCIONAL: Si necesitas ejecutarlas al inicio, puedes descomentar)
# Si no las necesitas, déjalas comentadas.
# php /var/www/html/artisan migrate --force

# 4. Iniciar Supervisor (en primer plano)
# Esto iniciará Nginx y PHP-FPM, resolviendo el error DEADLINE_EXCEEDED.
exec /usr/bin/supervisord -c /etc/supervisord.conf
