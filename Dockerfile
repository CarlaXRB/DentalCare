# -----------------------------------------------------------------
# 1️⃣ Etapa Node (assets_builder) - Compila CSS/JS (Vite/npm)
# -----------------------------------------------------------------
FROM node:18-bullseye AS assets_builder
WORKDIR /app

RUN apt-get update && apt-get install -y \
    libmagickwand-dev \
    --no-install-recommends && rm -rf /var/lib/apt/lists/*

# 2. Instalar la extensión PHP Imagick
# El comando docker-php-ext-install se encarga de compilar e instalar la extensión
RUN docker-php-ext-install imagick

# Instalar build tools y limpiar caché. Removida la instalación global de yarn.
RUN apt-get update && \
    apt-get install -y build-essential && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# CRÍTICO: Copiar archivos de Node (usando package-lock.json de npm)
COPY package.json package-lock.json ./
RUN npm install

# Copiar el resto del código y compilar assets
COPY . .
RUN npm run build

# -----------------------------------------------------------------
# 2️⃣ Etapa PHP + Apache (final_stage) - Servidor y Ejecución
# -----------------------------------------------------------------
FROM php:8.2-apache AS final_stage
RUN apt-get update && \
    apt-get install -y \
    libzip-dev zip unzip git curl libsqlite3-dev \
    libpq-dev \
    python3 python3-pip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP necesarias (incluyendo PostgreSQL)
RUN docker-php-ext-install pdo zip pdo_sqlite pgsql pdo_pgsql

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos de Composer
COPY composer.json composer.lock ./
# Copiar TODO el código de la aplicación
COPY . /var/www/html

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Ejecutar comandos Artisan
RUN php artisan key:generate
RUN php artisan package:discover

# CRÍTICO: Copiar los assets de Vite compilados
COPY --from=assets_builder /app/public/build /var/www/html/public/build

# Configurar Apache para servir desde /public y habilitar mod_rewrite
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# CRÍTICO: Configurar permisos de escritura para storage y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build

# Exponer el puerto por defecto de Apache
EXPOSE 80
CMD ["apache2-foreground"]