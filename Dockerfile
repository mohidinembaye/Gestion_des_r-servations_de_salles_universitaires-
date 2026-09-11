FROM php:8.3-fpm

# Extensions PHP nécessaires (pdo_mysql pour Eloquent/illuminate-database, zip pour composer)
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    nginx \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN sed -i 's/^listen = .*/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/docker.conf

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .
RUN rm -f /etc/nginx/conf.d/default.conf \
    && rm -rf /etc/nginx/sites-enabled/default
COPY nginx/render.conf /etc/nginx/conf.d/default.conf

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD php-fpm & nginx -g 'daemon off;'