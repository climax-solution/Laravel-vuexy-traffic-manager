# traffic_manager

## Installation
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
# Email: admin@admin.com
# Password: password
php artisan serve

http://localhost:8000
```