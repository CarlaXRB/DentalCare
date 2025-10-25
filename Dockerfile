FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema operativo y extensiones PHP necesarias
RUN apk add --no-cache \
    nginx \
    git \
    postgresql-client \
    # CRÍTICO: Añadir las herramientas de desarrollo de PostgreSQL para que PDO pueda compilar
    postgresql-dev \ 
    make \
    curl \
    supervisor \
    && docker-php-ext-install pdo_pgsql opcache \
    && rm -rf /var/cache/apk/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuración de Nginx
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copiar el archivo de Supervisor a su ubicación correcta
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Directorio de trabajo y permisos
WORKDIR /var/www/html
COPY . .

# Permisos CRUCIALES para Laravel
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto
EXPOSE 8080

# Iniciar Nginx y PHP-FPM con Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
