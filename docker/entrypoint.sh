#!/bin/sh
set -e

# Configure composer authentication
echo "Configuring composer..."
composer config http-basic.composer.fluxui.dev ivankrister.garcia@icloud.com 60882395-527c-4512-9d6c-dd4d62c6993c

# Install composer dependencies
echo "Installing composer dependencies..."
composer install --no-dev
# Optimizing the application
echo "Optimizing the application..."
php artisan optimize

# Install npm dependencies if package.json exists
if [ -f "package.json" ]; then
    echo "Installing npm dependencies..."
    npm ci --audit false
    echo "Running npm build..."
    npm run build
    chown -R www-data:www-data public/build
fi

# Start Supervisor (which will manage PHP-FPM and other processes)
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
