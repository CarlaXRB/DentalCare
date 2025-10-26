FROM composer:2.7.2 as composer_stage
RUN apk add --no-cache git icu-dev zlib-dev libzip-dev postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader

FROM node:20-alpine as node_stage
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
COPY resources resources/
COPY public public/
RUN npm install
RUN npm run build 

FROM php:8.2-fpm-alpine
RUN apk add --no-cache \
    nginx \
    supervisor \
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
RUN mkdir -p /run/nginx
COPY default.conf /etc/nginx/conf.d/default.conf
COPY supervisord.conf /etc/supervisord.conf
WORKDIR /var/www/html
COPY . .
COPY --from=composer_stage /app/vendor/ vendor/
COPY --from=node_stage /app/public/build/ public/build/
RUN chown -R www-data:www-data /var/www/html \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    && chmod -R 777 storage bootstrap/cache
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
EXPOSE 8080
ENTRYPOINT ["docker-entrypoint.sh"]
CMD []