# ----------------------------------------------------------------------------------
# 1️⃣ Etapa Build: Node + Laravel Assets
# ----------------------------------------------------------------------------------
FROM node:18-bullseye AS assets_builder

WORKDIR /app

# 1. Actualizar el gestor de paquetes (Apt)
RUN apt-get update

# 2. Instalar herramientas esenciales de compilación y Yarn
RUN apt-get install -y build-essential && \
    npm install -g yarn --force && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copiar package.json y package-lock.json para cache de dependencias
COPY package.json package-lock.json ./

# Instalar dependencias Node con Yarn
RUN yarn install --force

# Copiar el resto del proyecto Node/Vite
COPY . .

# Construir los assets de Laravel + Vite
RUN yarn run build

---
## 2️⃣ Etapa PHP + Apache (Stage Final)

FROM php:8.2-apache AS final_stage

# 1. Actualizar el gestor de paquetes (Apt)
RUN apt-get update

# 2. Instalar extensiones PHP y utilidades necesarias
RUN apt-get install -y \
    libzip-dev zip unzip git curl libsqlite3-dev \
    libpq-dev \
    python3 python3-pip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Compilar extensiones PHP
RUN docker-php-ext-install pdo zip pdo_sqlite pgsql pdo_pgsql

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos esenciales para el cache de Composer
COPY composer.json composer.lock ./

# Copiar .env.example y artisan (Necesario para los pasos siguientes)
COPY .env.example artisan ./

# -----------------------------------------------------------------
# 🔴 CORRECCIÓN CRÍTICA: Composer antes de Artisan
# -----------------------------------------------------------------

# 4. Instalar dependencias PHP con Composer (Usando --no-scripts)
# Esto crea el vendor/autoload.php
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# 5. Ejecutar los comandos Artisan que fueron omitidos
# Ahora vendor/autoload.php existe y Artisan puede arrancar.
RUN php artisan key:generate
RUN php artisan package:discover

# -----------------------------------------------------------------

# Copiar el resto del proyecto Laravel
COPY . /var/www/html

# Copiar los assets construidos desde la etapa anterior
COPY --from=assets_builder /app/public/build /var/www/html/public/build

# Configurar Apache
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# Configurar permisos de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build

# Exponer puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]