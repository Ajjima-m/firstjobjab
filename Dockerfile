FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql mysqli
RUN a2enmod rewrite

COPY src/ /var/www/html/

RUN echo "DirectoryIndex dashboard.php index.php index.html" > /etc/apache2/conf-available/custom.conf \
    && a2enconf custom

RUN chown -R www-data:www-data /var/www/html