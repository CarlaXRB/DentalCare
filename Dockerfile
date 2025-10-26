# --- STAGE 1: Composer & PHP Dependencies ---
# Usa composer para instalar las dependencias de PHP
FROM composer:2.7.2 as composer_stage

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apk add --no-cache git icu-dev zlib-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath

WORKDIR /app

# Copiar archivos de composer y descargar dependencias
COPY composer.json composer.lock ./
# Instalar dependencias sin incluir las de desarrollo (más rápido y ligero)
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

# --- STAGE 2: Node.js & Frontend Build Stage ---
# Usamos una imagen de Node para compilar los assets de frontend (Vite/Tailwind)
FROM node:20-alpine as node_stage

WORKDIR /app

# Copiar archivos de configuración de frontend
COPY package.json package-lock.json vite.config.js ./
# Copiar archivos de recursos (CSS, JS, etc.)
COPY resources resources/
COPY public public/

# Instalar dependencias de Node
RUN npm install

# Ejecutar la compilación de producción de los assets (¡ESTO ES CLAVE!)
# Esto crea la carpeta public/build que Nginx necesita para servir el CSS y JS.
RUN npm run build 

# --- STAGE 3: Production Stage (Imagen final de Alpine) ---
FROM php:8.2-fpm-alpine

# Instalar utilidades, Nginx, Supervisor y extensiones de PHP necesarias
RUN apk add --no-cache \
    nginx \
    supervisor \
    # Extensiones de PHP
    php82-pgsql \
    php82-dom \
    php82-xml \
    php82-session \
    php82-ctype \
    php82-mbstring \
    php82-tokenizer \
    php82-xmlwriter \
    php82-json \
    php82-opcache \
    php82-pecl-apcu \
    php82-gd \
    php82-zip \
    && rm -rf /var/cache/apk/*

# Crear directorio de logs de Nginx
RUN mkdir -p /run/nginx

# Reemplazar la configuración por defecto de Nginx y PHP
COPY default.conf /etc/nginx/conf.d/default.conf
COPY supervisord.conf /etc/supervisord.conf

# Copiar el código de la aplicación
WORKDIR /var/www/html
COPY . .

# Copiar el vendor desde la etapa de compilación de composer
COPY --from=composer_stage /app/vendor/ vendor/

# Copiar los assets compilados de frontend (public/build) desde la etapa de Node
COPY --from=node_stage /app/public/build/ public/build/

# Crear y dar permisos al storage de Laravel
RUN chown -R www-data:www-data /var/www/html \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    && chmod -R 777 storage bootstrap/cache

# Copiar el script de ENTRYPOINT y hacerlo ejecutable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exponer el puerto
EXPOSE 8080

# Definir el script de ENTRYPOINT que se ejecuta primero
ENTRYPOINT ["docker-entrypoint.sh"]

# El CMD no es necesario si el ENTRYPOINT ejecuta el supervisor
CMD []
