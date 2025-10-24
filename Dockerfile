# ----------------------------------------------------------------------------------
# 2️⃣ Etapa PHP + Apache (Se mantiene igual, solo se actualiza el COPY)
# ----------------------------------------------------------------------------------
FROM php:8.2-apache

# 1. Actualizar el gestor de paquetes (Apt)
RUN apt-get update

# 2. Instalar extensiones PHP y utilidades necesarias
RUN apt-get install -y \
    libzip-dev zip unzip git curl libsqlite3-dev \
    libpq-dev \
    python3 python3-pip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Compilar extensiones PHP y limpiar
RUN docker-php-ext-install pdo zip pdo_sqlite pgsql pdo_pgsql

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar solo los archivos de dependencias de PHP para optimizar el cache de Composer
COPY composer.json composer.lock ./

# Solución crítica para el error de Composer
COPY .env.example artisan ./
RUN php artisan key:generate

# Instalar dependencias PHP con Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copiar el resto del proyecto Laravel
COPY . /var/www/html

# Copiar los assets construidos desde la etapa build
COPY --from=assets_builder /app/public/build /var/www/html/public/build <-- ¡CAMBIO AQUÍ!

# Configurar Apache para servir desde public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# Configurar permisos de Laravel y assets
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build

# Exponer puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]