#!/bin/bash

set -e  # Dừng script ngay khi gặp lỗi

# Đường dẫn đến repo GitHub
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
REMOTE_NAME="binh.dev.02"  # Đúng remote name
BRANCH="live1"

# Di chuyển đến thư mục chứa project
cd "$(dirname "$0")" || exit 1

# Kiểm tra nếu chưa có Git repo, thì clone mới
if [ ! -d ".git" ]; then
    echo "🚀 Cloning repository for the first time..."
    git clone --branch "$BRANCH" "$REPO_URL" .
else
    echo "🔄 Fetching latest changes..."
    git fetch "$REMOTE_NAME"

    echo "🔄 Checking out branch $BRANCH..."
    git checkout "$BRANCH"

    echo "🔄 Resetting local branch to match remote..."
    git reset --hard "$REMOTE_NAME/$BRANCH"  # Reset về đúng remote

    echo "🔄 Pulling the latest changes..."
    git pull "$REMOTE_NAME" "$BRANCH" --ff-only  # Fast-forward nếu có thể
fi

# Chạy migration nếu có thay đổi
echo "⚙️ Running database migrations..."
php artisan migrate --force || { echo "❌ Migration failed! Exiting."; exit 1; }

# Chạy seeder (nếu cần)
echo "🌱 Seeding database..."
php artisan db:seed --force || { echo "❌ Seeding failed! Exiting."; exit 1; }

# Dọn dẹp và tối ưu cache
echo "🗑️ Clearing and optimizing cache..."
php artisan optimize:clear

echo "✅ Code update completed successfully!"
