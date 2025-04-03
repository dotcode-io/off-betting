FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    supervisor

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip xml

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better caching
COPY composer.* ./

# Set permissions
RUN chown -R www-data:www-data /var/www

# Copy existing application directory
COPY . /var/www

# Install dependencies (this will be skipped if auth is not provided)
RUN if [ -f "auth.json" ]; then \
    composer install --no-scripts --no-autoloader; \
    else \
    echo "Skipping composer install - auth.json not found"; \
    fi

# Generate optimized autoload files if vendor exists
RUN if [ -d "vendor" ]; then \
    composer dump-autoload --optimize; \
    fi

# Create log directory for Supervisor
RUN mkdir -p /var/log/supervisor

# Copy Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Use custom entrypoint to start both PHP-FPM and Supervisor
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Install global npm packages
RUN npm install -g vite

# Create public/build directory and set permissions
RUN mkdir -p public/build && chown -R www-data:www-data public/build

# Set proper permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
