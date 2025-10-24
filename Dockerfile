FROM node:18-bullseye AS assets_builder
WORKDIR /app
RUN apt-get update
RUN apt-get install -y build-essential && \
    npm install -g yarn --force && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
COPY package.json package-lock.json ./
RUN yarn install --force
COPY . .
RUN yarn run build

FROM php:8.2-apache AS final_stage
RUN apt-get update
RUN apt-get install -y \
    libzip-dev zip unzip git curl libsqlite3-dev \
    libpq-dev \
    python3 python3-pip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo zip pdo_sqlite pgsql pdo_pgsql
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts
RUN php artisan key:generate
RUN php artisan package:discover
COPY --from=assets_builder /app/public/build /var/www/html/public/build
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf \
    && a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build
EXPOSE 80
CMD ["apache2-foreground"]