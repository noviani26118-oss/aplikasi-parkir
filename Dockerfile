FROM php:8.1-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
COPY . /var/www/html/

# Ensure permissions
RUN chown -R www-data:www-data /var/www/html/

# Expose port 80
EXPOSE 80
