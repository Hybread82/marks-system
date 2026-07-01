FROM php:8.2-cli

# Install mysqli
RUN docker-php-ext-install mysqli && \
    docker-php-ext-enable mysqli

# Copy application files
COPY . /app/

WORKDIR /app

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app"]
