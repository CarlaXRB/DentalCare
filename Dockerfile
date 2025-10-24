# ------------------------------
# 1️⃣ Etapa Build: Node + Laravel Assets
# ------------------------------
FROM node:20 AS build

WORKDIR /app

# Copiar package.json y package-lock.json
COPY package*.json ./

# Instalar dependencias de Node
RUN npm install

# Copiar todo el proyecto
COPY . .

# Construir los assets (CSS/JS) de Laravel + Vite
RUN npm run build

# ------------------------------
# 2️⃣ Etapa PHP + Apache
# ------------------------------
FROM php:8.2-apache

# Instalar extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpq-dev libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar el proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Copiar los assets construidos desde la etapa build
COPY --from=build /app/public/build /var/www/html/public/build

# Configurar Apache para servir desde public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# Permisos de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Instalar dependencias PHP con Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Exponer puerto 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
