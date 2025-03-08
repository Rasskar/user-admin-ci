FROM php:8.3-fpm

# Устанавливаем базовые зависимости (PHP + system packages)
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git curl libonig-dev \
    libicu-dev libpq-dev netcat-openbsd telnet \
    procps net-tools supervisor locales \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli pdo_pgsql pgsql gd mbstring intl zip \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем Redis
RUN pecl install redis && docker-php-ext-enable redis

# Генерируем локали (для работы intl)
RUN locale-gen en_US.UTF-8 && update-locale

# Устанавливаем Swoole отдельно
RUN apt-get update && apt-get install -y --no-install-recommends \
    libssl-dev pkg-config libbrotli-dev \
    && pecl install swoole \
    && docker-php-ext-enable swoole \
    && rm -rf /var/lib/apt/lists/*

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем файлы проекта
COPY . /var/www/html

# Копируем файлы Composer
COPY ./composer.json ./composer.lock /var/www/html/

# Устанавливаем зависимости
RUN composer install --no-dev --prefer-dist

# Настраиваем права доступа
RUN chown -R www-data:www-data /var/www/html/writable /var/www/html/public
RUN chmod -R 777 /var/www/html/writable /var/www/html/writable/cache

# Копируем конфигурацию Supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Запускаем Supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]