# ------------------------------
# 1️⃣ Imagen base: PHP 8.2 con Apache
# ------------------------------
FROM php:8.2-apache

# ------------------------------
# 2️⃣ Instalación de dependencias del sistema
# ------------------------------
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl \
    libpq-dev libsqlite3-dev \
    python3 python3-pip \
    && docker-php-ext-install pdo pdo_sqlite pdo_pgsql pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ------------------------------
# 3️⃣ Instalar Composer
# ------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ------------------------------
# 4️⃣ Copiar el proyecto al contenedor
# ------------------------------
COPY . /var/www/html
WORKDIR /var/www/html

# ------------------------------
# 4️⃣1 Configurar Apache para servir desde public/
# ------------------------------
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite

# ------------------------------
# 5️⃣ Configurar permisos para Laravel
# ------------------------------
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ------------------------------
# 6️⃣ Instalar dependencias PHP con Composer
# ------------------------------
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ------------------------------
# 7️⃣ Instalar dependencias de Python (opcional)
# ------------------------------
# COPY requirements.txt /var/www/html/requirements.txt
# RUN pip3 install --no-cache-dir -r requirements.txt

# ------------------------------
# 8️⃣ Exponer puerto 80
# ------------------------------
EXPOSE 80

# ------------------------------
# 🔹 Comando para iniciar Apache
# ------------------------------
CMD ["apache2-foreground"]

