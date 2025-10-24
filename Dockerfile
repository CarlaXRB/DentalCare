# ----------------------------------------------------------------------------------
# 2️⃣ Etapa PHP + Apache (Correcciones aplicadas)
# ----------------------------------------------------------------------------------
FROM php:8.2-apache

# 1. Actualizar el gestor de paquetes (Apt)
RUN apt-get update

# 2. Instalar extensiones PHP y utilidades necesarias
# Instalamos libpq-dev aquí para que pgsql y pdo_pgsql puedan compilar
RUN apt-get install -y \
    libzip-dev zip unzip git curl libsqlite3-dev \
    libpq-dev \
    python3 python3-pip \
    # Limpiar caché del SO aquí para evitar errores al final
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Compilar extensiones PHP y limpiar
# CORRECCIÓN: Eliminado el 'docker-php-ext-configure' obsoleto.
# Instalamos pdo_sqlite, pgsql y pdo_pgsql.
RUN docker-php-ext-install pdo zip pdo_sqlite pgsql pdo_pgsql

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar solo los archivos de dependencias de PHP para optimizar el cache de Composer
COPY composer.json composer.lock ./

# 🔴 SOLUCIÓN CRÍTICA PARA EL ERROR DE COMPOSER:
# Copiar archivos esenciales para que Laravel pueda inicializarse (incluyendo .env y artisan)
# Usaremos .env.example como .env temporal para el build.
COPY .env.example artisan ./

# Generar APP_KEY antes de composer install para que 'package:discover' no falle
# Laravel necesita la APP_KEY para arrancar y ejecutar package:discover
RUN php artisan key:generate

# Instalar dependencias PHP con Composer
# Ahora composer install ejecutará 'package:discover' sin error.
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copiar el resto del proyecto Laravel
# El .env, artisan y composer.* ya existen, solo copiamos el resto del código
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