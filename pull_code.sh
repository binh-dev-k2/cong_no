#!/bin/bash

set -e  # Dừng script ngay khi gặp lỗi

# Đường dẫn đến repo GitHub
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
BRANCH="live1"

# Di chuyển đến thư mục chứa project
cd "$(dirname "$0")" || exit 1

# Kiểm tra nếu chưa có Git repo, thì clone mới
if [ ! -d ".git" ]; then
    echo "🚀 Cloning repository for the first time..."
    git clone --branch "$BRANCH" "$REPO_URL" .
else
    echo "🔄 Fetching latest changes..."
    git fetch --all
    git checkout "$BRANCH"
    git pull origin "$BRANCH"
fi

# Chạy migration nếu có thay đổi
echo "⚙️ Running database migrations..."
php artisan migrate --force || { echo "❌ Migration failed! Exiting."; exit 1; }

# Chạy seeder (nếu cần)
echo "🌱 Seeding database..."
php artisan db:seed || { echo "❌ Seeding failed! Exiting."; exit 1; }

# Dọn dẹp và tối ưu cache
echo "🗑️ Clearing and optimizing cache..."
php artisan optimize:clear

echo "✅ Code update completed successfully!"
