#!/bin/bash
# LMS Deployment & Storage Link Fix Script
# Make sure to run this script from the LMS root directory.

echo "🚀 Starting LMS production deployment automation..."

# 1. Pull latest code (if in git environment)
if [ -d ".git" ]; then
    echo "📥 Pulling latest updates from Git..."
    git pull origin main
else
    echo "⚠️ Not a Git repository, skipping git pull."
fi

# 2. Install/Update PHP Dependencies
if [ -f "composer.json" ]; then
    echo "📦 Checking and installing Composer dependencies (production)..."
    composer install --no-dev --optimize-autoloader
fi

# 3. Create required upload folders and correct storage permissions
echo "📁 Setting up upload directories and symlinks..."
php artisan setup:upload-directories

# 4. Fallback symlink setup if directory check was skipped or bypassed
echo "🔗 Verifying storage link..."
php artisan storage:link

# 5. Clear and Cache Configuration/Routes/Views
echo "⚡ Optimizing Larnvel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "🔒 Caching configurations for production speed..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Run Database Migrations (if MySQL/SQLite changes exist)
echo "🗄️ Running migrations..."
php artisan migrate --force

echo "✅ LMS Deployment and Media Setup completed successfully!"
