#!/bin/bash
set -e  # Dừng script ngay lập tức nếu có lỗi

# Đường dẫn đến repo GitHub
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
BRANCH="live"

# Di chuyển đến thư mục chứa script
cd "$(dirname "$0")" || { echo "Failed to change directory"; exit 1; }

# Clone repo từ branch cụ thể
echo "Cloning repository from branch $BRANCH..."
git clone --branch "$BRANCH" --single-branch "$REPO_URL" temp_folder || { echo "Failed to clone branch $BRANCH"; exit 1; }

# Đồng bộ hóa nội dung từ thư mục tạm vào thư mục hiện tại
echo "Syncing files from temp_folder to current directory..."
rsync -a --remove-source-files temp_folder/ . || { echo "Rsync failed"; exit 1; }

# Xóa thư mục tạm
echo "Cleaning up temp_folder..."
if [ -d "temp_folder" ]; then
    rm -rf temp_folder || { echo "Failed to remove temp_folder"; exit 1; }
else
    echo "temp_folder does not exist, skipping removal."
fi

# Cấp quyền thực thi cho script pull_code.sh
echo "Setting execute permissions on pull_code.sh..."
if [ -f "./pull_code.sh" ]; then
    chmod +x ./pull_code.sh || { echo "Failed to change permissions on pull_code.sh"; exit 1; }
else
    echo "pull_code.sh does not exist, skipping permission change."
fi

# Xóa file config/l5-swagger.php nếu tồn tại
echo "Removing config/l5-swagger.php..."
if [ -f "./config/l5-swagger.php" ]; then
    rm -f ./config/l5-swagger.php || { echo "Failed to remove config/l5-swagger.php"; exit 1; }
else
    echo "config/l5-swagger.php does not exist, skipping removal."
fi

# Tối ưu hóa và xóa cache Laravel
echo "Optimizing Laravel..."
php artisan optimize:clear || { echo "Failed to optimize clear"; exit 1; }

# Xóa thư mục vendor và file composer.lock
echo "Cleaning up vendor and composer.lock..."
if [ -d "vendor" ]; then
    rm -rf vendor || { echo "Failed to remove vendor directory"; exit 1; }
else
    echo "vendor directory does not exist, skipping removal."
fi

if [ -f "composer.lock" ]; then
    rm -f composer.lock || { echo "Failed to remove composer.lock"; exit 1; }
else
    echo "composer.lock does not exist, skipping removal."
fi

# Cài đặt các dependencies Composer
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader || { echo "Composer install failed"; exit 1; }

# Chạy migration và seeding
echo "Running migrations..."
php artisan migrate --force || { echo "Migration failed"; exit 1; }

echo "Running seeders..."
php artisan db:seed || { echo "Seeding failed"; exit 1; }

# Tối ưu hóa lại Laravel
echo "Optimizing Laravel again..."
php artisan optimize:clear || { echo "Failed to optimize clear"; exit 1; }

echo ">>>>>>> Done!"
