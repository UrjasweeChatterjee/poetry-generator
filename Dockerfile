# Use PHP CLI image — avoids all Apache MPM conflicts
FROM php:8.2-cli

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Expose port 80
EXPOSE 80

# Start PHP's built-in web server
CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/html"]
