# Đường dẫn đến repo GitHub
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
BRANCH="live"
cd "$(dirname "$0")"

git clone --branch "$BRANCH" --single-branch "$REPO_URL" temp_folder || { echo "Failed to clone branch $BRANCH"; exit 1; }
rsync -a --remove-source-files temp_folder/ . || { echo "Rsync failed"; exit 1; }
rm -rf temp_folder
chmod +x ./pull_code.sh || { echo "Failed to change permissions on pull_code.sh"; exit 1; }
rm -f ./config/l5-swagger.php

php artisan o:c || { echo "Failed to optimize clear"; exit 1; }

rm -rf vendor
rm -f composer.lock
composer install --no-dev --optimize-autoloader || { echo "Composer install failed"; exit 1; }

php artisan migrate --force || { echo "Migration failed"; exit 1; }
php artisan db:seed || { echo "Seeding failed"; exit 1; }

php artisan o:c || { echo "Failed to optimize clear"; exit 1; }
echo ">>>>>>>Done!"

