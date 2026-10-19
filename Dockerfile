FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl unzip libzip-dev zip libpng-dev libonig-dev libicu-dev libxml2-dev default-mysql-client bash \
    && docker-php-ext-configure gd --with-webp --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd bcmath intl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN composer install --no-interaction --prefer-dist --no-progress --optimize-autoloader \
    && npm install --no-fund --no-audit \
    && npm run build \
    && chown -R www-data:www-data /var/www/html \
    && sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf \
    && chmod +x /var/www/html/docker/entrypoint.sh

EXPOSE 80

CMD ["/var/www/html/docker/entrypoint.sh"]
