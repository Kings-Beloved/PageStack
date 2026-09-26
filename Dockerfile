FROM php:8.2-apache

# Install MySQL extension for mysqli / PDO
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli

# Enable Apache rewrite module if using routing
RUN a2enmod rewrite

# Copy your application files to standard Apache web root
COPY . /var/www/html/

# Expose port 80
EXPOSE 80