#!/bin/bash

# Đường dẫn đến repo GitHub
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
BRANCH="live" # Nhánh cần pull (sửa nếu cần)

cd "$(dirname "$0")"

# Pull code mới nhất
echo "Pulling the latest code from $REPO_URL..."
git pull origin $BRANCH


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
