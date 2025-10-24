# ------------------------------
# 1️⃣ Etapa Build: Node + Laravel Assets
# ------------------------------
FROM node:20-bullseye AS build

WORKDIR /app

# Instalar herramientas para paquetes nativos
RUN apt-get update && apt-get install -y python3 g++ make curl git

# Copiar package.json y package-lock.json primero (para cache de Docker)
COPY package.json package-lock.json ./

# Limpiar cache y reinstalar npm
RUN npm install -g npm@11.6.2

# Instalar dependencias de Node con bypass de peer deps
RUN npm ci --legacy-peer-deps

# Copiar resto del proyecto
COPY . .

# Construir assets de Laravel + Vite
RUN npm run build

# ------------------------------
# 2️⃣ Etapa PHP + Apache
# ------------------------------
FROM php:8.2-apache

# Instalar extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpq-dev libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Copiar assets desde etapa build
COPY --from=build /app/public/build /var/www/html/public/build

# Configurar Apache para servir desde public/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Exponer puerto 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
