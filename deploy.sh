#!/bin/bash

set -e

echo "🚀 Starting deployment..."

# Переходим в директорию проекта
cd /var/www/pervajakniga || exit

# Забираем изменения
git pull

# Разрешаем composer работать под root (если всё же запускаешь под root)
export COMPOSER_ALLOW_SUPERUSER=1

# Установка зависимостей
composer install

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:optimize-clear
php artisan filament:optimize

# Миграции
php artisan migrate --force

# Перезапуск очереди (supervisor)
supervisorctl restart laravel-worker:*

echo "✨ Deployment finished!"
