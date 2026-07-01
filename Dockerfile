FROM php:8.2-apache

# Install MySQL extension
RUN docker-php-ext-install mysqli

# Copy all files to the web root
COPY . /var/www/html/

# Enable mod_rewrite (optional)
RUN a2enmod rewrite

EXPOSE 80
