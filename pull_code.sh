#!/bin/bash
set -e  # Dừng script ngay lập tức nếu có lỗi

# Định nghĩa biến
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
BRANCH="live1"
TEMP_DIR="temp_folder"

# Kiểm tra các công cụ cần thiết
command -v git >/dev/null 2>&1 || { echo "Git chưa được cài đặt!"; exit 1; }
command -v php >/dev/null 2>&1 || { echo "PHP chưa được cài đặt!"; exit 1; }
command -v composer >/dev/null 2>&1 || { echo "Composer chưa được cài đặt!"; exit 1; }

# Xác định thư mục script
cd "$(dirname "$0")" || { echo "Không thể thay đổi thư mục!"; exit 1; }

# Clone repository
echo "🛠 Cloning repository từ branch $BRANCH..."
rm -rf "$TEMP_DIR"
git clone --branch "$BRANCH" --single-branch "$REPO_URL" "$TEMP_DIR"

# Đồng bộ hóa nội dung từ thư mục tạm vào thư mục hiện tại
echo "📂 Đồng bộ hóa files..."
sync
# rsync -a --delete --ignore-missing-args --inplace "$TEMP_DIR/" .
rsync -a --delete --ignore-missing-args --inplace "$TEMP_DIR/" . --remove-source-files
# Xóa thư mục tạm
rm -rf "$TEMP_DIR"

# Cấp quyền thực thi cho script nếu cần
echo "🔧 Cấp quyền thực thi cho pull_code.sh..."
chmod +x ./pull_code.sh || echo "⚠ Không thể cấp quyền cho pull_code.sh"

# Xóa file config l5-swagger nếu tồn tại
echo "🗑 Xóa file config/l5-swagger.php..."
rm -f ./config/l5-swagger.php

# Xóa cache Laravel
echo "🚀 Xóa cache Laravel..."
php artisan optimize:clear || echo "⚠ Không thể xóa cache Laravel"

# Xóa vendor và composer.lock để cài đặt lại dependencies
echo "🛠 Xóa vendor & composer.lock..."
rm -rf vendor composer.lock

# Cài đặt dependencies Composer
echo "📦 Cài đặt dependencies với Composer..."
composer install --no-dev --optimize-autoloader

# Chạy migration & seeding database
echo "🔄 Chạy migrations..."
php artisan migrate --force

echo "🌱 Chạy seeders..."
php artisan db:seed --force

# Xóa cache lần nữa để đảm bảo hệ thống tối ưu
echo "🚀 Tối ưu Laravel..."
php artisan optimize:clear

echo "✅ Hoàn thành!"
