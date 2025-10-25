# -----------------------------------------------------------------
# 1️⃣ Etapa Node (assets_builder) - Compila CSS/JS (Vite/npm)
# -----------------------------------------------------------------
FROM node:18-bullseye AS assets_builder
WORKDIR /app

# Instalar build tools y limpiar caché.
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

# CRÍTICO: Copiar los assets de Vite compilados desde la etapa anterior
COPY --from=assets_builder /app/public/build /var/www/html/public/build

# Configurar Apache para servir desde /public y habilitar mod_rewrite
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# CRÍTICO: Configurar permisos de escritura para storage y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build

# 🚨 Ajuste CRÍTICO para Cloud Run 🚨
# Copiar el script de inicio y darle permisos
COPY run.sh /usr/local/bin/run
RUN chmod +x /usr/local/bin/run

# Cloud Run requiere escuchar en el puerto 8080 por defecto
EXPOSE 8080 
# Usar el script para que Apache escuche en el puerto $PORT (generalmente 8080)
CMD ["/usr/local/bin/run"]