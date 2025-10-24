# ------------------------------------------------------
# 1️⃣ Etapa Build: Node + Vite (para compilar assets)
# ------------------------------------------------------
FROM node:20-bullseye AS assets_builder

# Crear carpeta de trabajo
WORKDIR /app

# Instalar dependencias necesarias y yarn
RUN apt-get update && \
    apt-get install -y build-essential && \
    npm install -g yarn && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiar solo archivos de dependencias primero
COPY package.json yarn.lock* package-lock.json* ./

# Instalar dependencias Node
RUN yarn install --frozen-lockfile || npm install --legacy-peer-deps

# Copiar el resto del código
COPY . .

# Construir los assets de Vite
RUN yarn run build || npm run build


# ------------------------------------------------------
# 2️⃣ Etapa Final: PHP 8.2 + Apache + Composer
# ------------------------------------------------------
FROM php:8.2-apache AS final_stage

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libsqlite3-dev libpq-dev \
    python3 python3-pip \
    && docker-php-ext-install pdo zip pdo_sqlite pgsql pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos del proyecto Laravel
COPY . /var/www/html

# Instalar dependencias PHP (sin scripts aún)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copiar los assets ya compilados desde la etapa Node
COPY --from=assets_builder /app/public/build /var/www/html/public/build

# Configurar Apache para usar el directorio /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# Generar key y optimizar Laravel
RUN php artisan key:generate --force && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear && \
    php artisan optimize

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build

# Exponer puerto 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]
