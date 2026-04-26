#!/bin/bash
set -e

echo "Starting Laravel application..."

# Wait for MySQL to be ready
if [ -n "$DB_HOST" ]; then
    echo "Waiting for MySQL database at $DB_HOST..."
    until mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" -p"$DB_PASSWORD" &>/dev/null; do
        echo "MySQL is unavailable - sleeping"
        sleep 2
    done
    echo "MySQL is up!"
fi

# Create storage directories if they don't exist
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs

# Set proper permissions
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache

# Run migrations if needed
if [ "$APP_ENV" != "production" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
fi

echo "Starting Apache..."
apache2-foreground
