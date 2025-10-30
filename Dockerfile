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

# Instalar extensiones PHP principales (pdo, zip, pdo_pgsql, etc.)
# 'zip' se instala aquí para ZipArchive
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

    # Permitir acceso a la carpeta multimedia desde Apache
RUN echo '<Directory "/var/www/html/public/multimedia">\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf


# CRÍTICO: Configurar permisos de escritura para storage, cache y multimedia
RUN mkdir -p /var/www/html/public/multimedia \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build /var/www/html/public/multimedia \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build /var/www/html/public/multimedia

# Exponer el puerto por defecto de Apache
EXPOSE 80
CMD ["apache2-foreground"]