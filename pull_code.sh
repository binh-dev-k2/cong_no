#!/bin/bash

# Chuyển đến thư mục dự án
cd "$(dirname "$0")"

# Pull code mới nhất từ GitHub
echo "Pulling the latest code from GitHub..."
git pull origin live

# Cài đặt các dependencies (composer)
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Chạy migration (nếu cần)
echo "Running database migrations..."
php artisan migrate --force

# Dọn dẹp cache (nếu cần)
echo "Clearing and optimizing cache..."
php artisan o:c
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache

echo ">>>>>>>Done!"
