FROM php:8.3-fpm

# Install dependencies sistem + supervisor
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . /var/www

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Setup permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy Nginx Configuration
COPY .docker/nginx.conf /etc/nginx/sites-available/default

# Copy Supervisor Configuration (Buat nge-run Nginx & PHP-FPM)
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

# Jalankan Supervisor sebagai main process
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
