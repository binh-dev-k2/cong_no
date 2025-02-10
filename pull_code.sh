# Đường dẫn đến repo GitHub
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
BRANCH="live" # Nhánh cần pull (sửa nếu cần)
cd "$(dirname "$0")"
sed -i -e 's/\r$//' pull_code.sh
# Pull code mới nhất
git clone --branch $BRANCH --single-branch $REPO_URL temp_folder
rsync -a temp_folder/ . --remove-source-files
rm -rf temp_folder
chmod +x ./pull_code.sh
rm -rf ./config/l5-swagger.php

php artisan o:c

# Cài đặt các dependencies (composer)
rm -rf vendor
rm -f composer.lock
composer install --no-dev --optimize-autoloader
# Chạy migration (nếu cần)
php artisan migrate --force
php artisan db:seed
# Dọn dẹp cache (nếu cần)
php artisan o:c
echo ">>>>>>>Done!"
