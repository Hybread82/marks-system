FROM php:8.2-apache

# Fix MPM conflict by disabling conflicting modules
RUN a2dismod mpm_event mpm_worker || true && \
    a2enmod mpm_prefork

# Install MySQL extension
RUN docker-php-ext-install mysqli

# Copy all files
COPY . /var/www/html/

# Enable mod_rewrite
RUN a2enmod rewrite

EXPOSE 80
