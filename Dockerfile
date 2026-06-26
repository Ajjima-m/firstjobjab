FROM php:8.2-apache

RUN docker-php-ext-install pdo_mysql mysqli
RUN a2enmod rewrite

# 👉 สำคัญ: เอา src เข้าเว็บ
COPY src/ /var/www/html/

# 👉 ตั้งหน้าแรก
RUN echo "DirectoryIndex dashboard.php index.php index.html" > /etc/apache2/conf-available/custom.conf \
    && a2enconf custom

RUN chown -R www-data:www-data /var/www/html