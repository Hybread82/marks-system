FROM php:8.2-apache

# Install mysqli
RUN docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli

# Enable Apache modules
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80
