#!/bin/bash
set -e

echo "🚀 Starting application..."

# Cache config & routes for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations (safe for production with --force)
php artisan migrate --force

echo "✅ Application ready!"

# Start Apache
exec "$@"
