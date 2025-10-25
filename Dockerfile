# Usa PHP 8.2 FPM (FastCGI Process Manager)
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema operativo y extensiones PHP necesarias
RUN apk add --no-cache \
    nginx \
    git \
    mysql-client \
    make \
    curl \
    supervisor \
    && docker-php-ext-install pdo_mysql opcache \
    && rm -rf /var/cache/apk/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuración de Nginx
# Copiaremos el archivo de configuración de Nginx
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Directorio de trabajo y permisos
WORKDIR /var/www/html
COPY . .

# Permisos CRUCIALES para Laravel
# Otorgamos permisos al directorio storage y bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto
EXPOSE 8080

# Iniciar Nginx y PHP-FPM con Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]