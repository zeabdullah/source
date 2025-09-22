# Run this script inside the container the first time you run the Laravel project with Docker
# E.g. after running `docker-compose up -d`
# To do so, run: `docker exec -it source-server bash ./<script_name>.sh`

echo "Laravel: Running initialization script..."

composer install
php artisan key:generate
php artisan migrate
php artisan db:seed

echo "Laravel: Initialization script completed! ✨"
