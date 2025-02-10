# Đường dẫn đến repo GitHub
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
BRANCH="live" # Nhánh cần pull (sửa nếu cần)
cd "$(dirname "$0")"
sed -i -e 's/\r$//' pull_code.sh
# Pull code mới nhất
echo "Pulling the latest code from $REPO_URL..."
git clone --branch $BRANCH --single-branch $REPO_URL temp_folder
rsync -a temp_folder/ . --remove-source-files
rm -rf temp_folder
chmod +x ./pull_code.sh

php artisan o:c

# Cài đặt các dependencies (composer)
echo "Installing PHP dependencies..."
rm -rf vendor
rm -f composer.lock
composer install --no-dev --optimize-autoloader
# Chạy migration (nếu cần)
echo "Running database migrations..."
php artisan migrate --force
php artisan db:seed
# Dọn dẹp cache (nếu cần)
echo "Clearing and optimizing cache..."
php artisan o:c
echo ">>>>>>>Done!"
