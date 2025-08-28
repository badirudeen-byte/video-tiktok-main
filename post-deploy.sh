#!/bin/bash

echo "📦 Running post-deploy tasks..."

# Create required Laravel storage directories
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p storage/logs

# Fix permissions
chmod -R 775 storage bootstrap/cache

# Laravel config and optimization
php artisan config:clear
php artisan optimize

echo "✅ Post-deployment script completed."
