# ----------------------------------------------------------------------------------
# 1️⃣ Etapa Build: Node + Laravel Assets (Orden de COPY Corregido)
# ----------------------------------------------------------------------------------
FROM node:18-bullseye AS build

WORKDIR /app

# 1. Actualizar el gestor de paquetes (Apt)
RUN apt-get update

# 2. Instalar herramientas esenciales de compilación y Yarn
RUN apt-get install -y build-essential && \
    # Instalar Yarn globalmente, forzando la sobrescritura del symlink
    npm install -g yarn --force && \
    # Limpiar caché de Apt
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Copiar solo package.json y package-lock.json para cache de dependencias
COPY package.json package-lock.json ./

# Instalar dependencias Node con Yarn
RUN yarn install --force

# --- CAMBIO CRUCIAL AQUÍ ---
# Copiar el resto del proyecto Node/Vite (incluye index.html, vite.config.js, etc.)
COPY . .

# Construir los assets de Laravel + Vite con Yarn (Ahora index.html está presente)
RUN yarn run build

# ----------------------------------------------------------------------------------
# 2️⃣ Etapa PHP + Apache (Se mantiene igual)
# ----------------------------------------------------------------------------------
FROM php:8.2-apache

# 1. Actualizar el gestor de paquetes (Apt)
RUN apt-get update

# 2. Instalar extensiones PHP y utilidades necesarias
RUN apt-get install -y \
    libzip-dev zip unzip git curl libsqlite3-dev \
    libpq-dev \
    python3 python3-pip

# 3. Compilar extensiones PHP y limpiar
RUN docker-php-ext-install pdo zip pdo_sqlite \
    && docker-php-ext-configure pgsql -with-pdo-pgsql=/usr/include/postgresql \
    && docker-php-ext-install pgsql pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar solo los archivos de dependencias de PHP para optimizar el cache de Composer
COPY composer.json composer.lock ./

# Instalar dependencias PHP con Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copiar el resto del proyecto Laravel
COPY . /var/www/html

# Copiar los assets construidos desde la etapa build
COPY --from=build /app/public/build /var/www/html/public/build

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