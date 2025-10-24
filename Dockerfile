# ------------------------------
# 1️⃣ Etapa Build: Node + Laravel Assets
# ------------------------------
FROM node:20-bullseye AS build

WORKDIR /app

# Copiar solo package.json y package-lock.json para cache de dependencias
COPY package.json package-lock.json ./

# Instalar dependencias Node (bypass peer deps)
RUN npm install --legacy-peer-deps

# Copiar todo el proyecto (incluyendo index.html, resources, vite.config.js)
COPY . .

# Construir los assets de Laravel + Vite
RUN npm run build

# ------------------------------
# 2️⃣ Etapa PHP + Apache
# ------------------------------
FROM php:8.2-apache

# Instalar extensiones PHP y utilidades necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpq-dev libsqlite3-dev \
    python3 python3-pip \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar todo el proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Copiar los assets construidos desde la etapa build
COPY --from=build /app/public/build /var/www/html/public/build

# Configurar Apache para servir desde public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# Configurar permisos de Laravel y assets
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build

# Instalar dependencias PHP con Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist



# Exponer puerto 80
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]
