FROM php:8.2-apache

# ติดตั้งส่วนขยาย PDO MySQL และ mysqli สำหรับเชื่อมต่อฐานข้อมูล
RUN docker-php-ext-install pdo_mysql mysqli

# เปิดใช้งาน mod_rewrite ของ Apache (เผื่อใช้ทำ Clean URL ในอนาคต)
RUN a2enmod rewrite
