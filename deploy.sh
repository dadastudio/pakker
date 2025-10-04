git pull origin main
# composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
/usr/local/php83/bin/php $HOME/.local/bin/composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
if [ -f artisan ]; then
# /usr/local/php83/bin/php artisan migrate

php artisan config:clear
# /usr/local/php83/bin/php artisan cache:clear


fi