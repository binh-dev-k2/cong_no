#!/bin/bash

# Đường dẫn đến repo GitHub
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
BRANCH="live1"

# Di chuyển đến thư mục chứa project
cd "$(dirname "$0")" || exit

# Kiểm tra nếu chưa có Git repo, thì clone mới
if [ ! -d ".git" ]; then
    echo "🚀 Cloning repository for the first time..."
    git clone --branch $BRANCH $REPO_URL .
fi

# Pull code mới nhất
echo "🔄 Pulling the latest code from $REPO_URL..."
git reset --hard origin/$BRANCH
git pull origin $BRANCH --rebase

# Kiểm tra lỗi khi pull code
if [ $? -ne 0 ]; then
    echo "❌ Lỗi khi pull code! Kiểm tra lại."
    exit 1
fi

# Chạy migration nếu có thay đổi
echo "⚙️ Running database migrations..."
php artisan migrate --force

php artisan db:seed

# Kiểm tra lỗi migration
if [ $? -ne 0 ]; then
    echo "❌ Lỗi khi chạy migration! Dừng cập nhật."
    exit 1
fi

# Dọn dẹp và tối ưu cache
echo "🗑️ Clearing and optimizing cache..."
php artisan optimize:clear

echo "✅ Code update completed successfully!"
