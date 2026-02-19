#!/bin/bash
set -e

echo "🚀 Starting application..."

# Ensure storage directories exist with correct permissions
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Cache config & routes (skip if it fails)
php artisan config:cache || echo "⚠️  Config cache skipped"
php artisan route:cache || echo "⚠️  Route cache skipped"
php artisan view:cache || echo "⚠️  View cache skipped"

# Run migrations (don't crash if DB isn't ready yet)
php artisan migrate --force || echo "⚠️  Migration skipped — database may not be ready"

echo "✅ Application ready! Starting Apache..."

# Start Apache
exec "$@"
