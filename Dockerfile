FROM php:8.2-apache

# ติดตั้ง extensions
RUN docker-php-ext-install pdo_mysql mysqli

# เปิด mod_rewrite
RUN a2enmod rewrite

# 👉 สำคัญมาก: เอาไฟล์เว็บเข้า Apache folder
COPY . /var/www/html

# ตั้ง permission (กัน 403)
RUN chown -R www-data:www-data /var/www/html