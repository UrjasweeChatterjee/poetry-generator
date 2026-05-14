# Use official PHP with Apache
FROM php:8.2-apache

# Fix: "More than one MPM loaded" — disable conflicting modules,
# then enable only mpm_prefork (required for mod_php)
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true \
    && a2enmod mpm_prefork

# Install PHP extensions needed for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (for clean URLs if needed)
RUN a2enmod rewrite

# Copy project files into Apache web root
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80
