FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql mysqli
RUN a2enmod rewrite

# 👉 ย้าย src เข้า web root โดยตรง
COPY src/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html