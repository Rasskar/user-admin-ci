FROM php:8.3-fpm

# Устанавливаем зависимости и обновляем пакеты
RUN apt-get update && apt-get upgrade -y && apt-get install -y --no-install-recommends \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git curl libonig-dev \
    libicu-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli pdo_pgsql pgsql gd mbstring intl zip

# Устанавливаем Redis
RUN pecl install redis && docker-php-ext-enable redis

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем файлы Composer
COPY ../composer.json ../composer.lock /var/www/html/

# Устанавливаем зависимости
RUN composer install --no-dev --prefer-dist

# Копируем остальные файлы
COPY .. /var/www/html/

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html/writable /var/www/html/public
RUN chmod -R 775 /var/www/html/writable

CMD ["php-fpm"]
