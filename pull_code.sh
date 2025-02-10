# Đường dẫn đến repo GitHub
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
BRANCH="live"
cd "$(dirname "$0")"
sed -i 's/'$'\\r''$//' pull_code.sh

git clone --branch $BRANCH --single-branch $REPO_URL temp_folder
rsync -a temp_folder/ . --remove-source-files
rm -rf temp_folder
chmod +x ./pull_code.sh
rm -f ./config/l5-swagger.php

php artisan o:c

rm -rf vendor
rm -f composer.lock
composer install --no-dev --optimize-autoloader

php artisan migrate --force
php artisan db:seed

php artisan o:c
echo ">>>>>>>Done!"
