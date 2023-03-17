# PHP version 7|8
FROM php:7.4-apache
# FROM php:8.0-apache
RUN a2enmod rewrite
RUN service apache2 restart
RUN docker-php-ext-install pdo pdo_mysql 