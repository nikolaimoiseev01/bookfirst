FROM php:8.3-fpm

# Установим системные зависимости
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Установим Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Установка nodejs (если нужен npm/vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Кэшируем composer зависимости (ускорит сборку)
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload

# Установим права (для ubuntu-сервера)
RUN chown -R www-data:www-data /var/www

CMD ["php-fpm"]
