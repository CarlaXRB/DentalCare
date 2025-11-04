# -----------------------------------------------------------------
# 1️⃣ Etapa Node (assets_builder) - Compila CSS/JS (Vite/npm)
# -----------------------------------------------------------------
FROM node:18-bullseye AS assets_builder
WORKDIR /app

# Instalar build tools y limpiar caché. Removida la instalación global de yarn.
RUN apt-get update && \
    apt-get install -y build-essential \
    && apt-get clean && \
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
    --no-install-recommends \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Instalación de extensiones necesarias ---
RUN docker-php-ext-install pdo zip pdo_sqlite pgsql pdo_pgsql bcmath intl gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar archivos de Composer
COPY composer.json composer.lock ./

# Copiar TODO el código de la aplicación
COPY . /var/www/html

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Copiar los assets compilados
COPY --from=assets_builder /app/public/build /var/www/html/public/build

# Ejecutar comandos Artisan
RUN php artisan key:generate || true
RUN php artisan package:discover || true

# -----------------------------------------------------------------
# 🔧 Configuración de Apache y permisos
# -----------------------------------------------------------------
# Establecer DocumentRoot y habilitar mod_rewrite
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf && \
    a2enmod rewrite

# Asegurar que Apache permita sobreescritura .htaccess
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/laravel.conf && \
    a2enconf laravel

# Permisos de escritura
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build

# -----------------------------------------------------------------
# 🌍 Variables de entorno de Laravel (opcional)
# -----------------------------------------------------------------
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_STORAGE=/var/www/html/storage

# Exponer el puerto por defecto de Apache
EXPOSE 80

CMD ["apache2-foreground"]
