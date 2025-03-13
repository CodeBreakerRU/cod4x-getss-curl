# Use the specified PHP Apache image
FROM php:8.4.5RC1-apache-bullseye

# Set working directory
WORKDIR /var/www/html

# Copy application files to the container
COPY app/ /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Enable Apache mod_rewrite (optional, useful for frameworks like Laravel)
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]