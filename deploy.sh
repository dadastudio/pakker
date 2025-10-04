git pull origin main
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

if [ -f artisan ]; then

php artisan config:clear
php artisan cache:clear


fi