FROM php:8.3-apache

# Installer les dépendances système et extensions PHP (PostgreSQL + MySQL + zip)
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql zip \
    && a2enmod rewrite headers expires deflate \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Configuration d'Apache pour Laravel / PHP MVC avec DocumentRoot sur /var/www/html/public
# et ServerName mohidine.sn
RUN echo '<VirtualHost *:80>\n\
    ServerName mohidine.sn\n\
    ServerAlias www.mohidine.sn localhost 127.0.0.1\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Configurer le ServerName global pour éviter le warning AH00558
RUN echo "ServerName mohidine.sn" >> /etc/apache2/apache2.conf

# Installer les dépendances Composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copier le reste du projet
COPY . .

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage 2>/dev/null || true

EXPOSE 80

CMD ["sh", "-c", "sed -i \"s/^Listen 80/Listen ${PORT:-80}/\" /etc/apache2/ports.conf && sed -i \"s/<VirtualHost \\*:80>/<VirtualHost *:${PORT:-80}>/\" /etc/apache2/sites-available/000-default.conf && apache2-foreground"]
