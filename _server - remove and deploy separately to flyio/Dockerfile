# Use an official PHP image with Apache and MySQL
FROM php:8.2.12-apache-buster

# Install MySQL extension
RUN docker-php-ext-install pdo_mysql

# Copy your PHP files to the Apache web directory
COPY . /var/www/html/

# Ensure Apache has the right permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Default MySQL credentials
# Host: mysql
# Username: root
# Password: password

