#!/bin/sh
set -e

# Install npm dependencies if package.json exists
if [ -f "package.json" ]; then
    echo "Installing npm dependencies..."
    npm install
    echo "Running npm build..."
    npm run build
    chown -R www-data:www-data public/build
fi

# Start Supervisor (which will manage PHP-FPM and other processes)
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
