# Usamos PHP 8.1 con Apache como base
FROM php:8.1-apache

# Instalamos dependencias del sistema necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip

# Instalamos Composer (gestor de dependencias PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiamos los archivos de nuestro proyecto al contenedor
COPY . /var/www/html

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Configuramos permisos para storage y bootstrap/cache (necesarios para Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Habilitamos mod_rewrite para URLs amigables en Apache
RUN a2enmod rewrite

# Copiamos configuración personalizada de Apache
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Activamos el sitio Apache con nuestra configuración
RUN a2ensite 000-default.conf

# Instalamos las dependencias PHP con Composer, sin dev para producción
RUN composer install --no-dev --optimize-autoloader

# Exponemos el puerto 80 para el servidor web
EXPOSE 80

# Comando para iniciar Apache en primer plano
CMD ["apache2-foreground"]
