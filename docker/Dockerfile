FROM php:8.3-fpm

# Устанавливаем зависимости и обновляем пакеты
RUN apt-get update && apt-get upgrade -y && apt-get install -y --no-install-recommends \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git curl libonig-dev \
    libicu-dev libpq-dev \
    msmtp-mta netcat-openbsd telnet \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli pdo_pgsql pgsql gd mbstring intl zip \
    && rm -rf /var/lib/apt/lists/*

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
RUN chmod -R 777 /var/www/html/writable /var/www/html/writable/cache

# Добавляем конфигурацию SMTP (MailHog / Mailpit)
RUN echo "account default" > /etc/msmtprc && \
    echo "host mailhog" >> /etc/msmtprc && \
    echo "port 1025" >> /etc/msmtprc && \
    echo "auto_from on" >> /etc/msmtprc && \
    echo "maildomain localhost" >> /etc/msmtprc && \
    echo "set sendmail \"/usr/bin/msmtp -t\"" >> /etc/msmtprc && \
    chmod 600 /etc/msmtprc

# Добавляем конфиг PHP для sendmail
RUN echo "sendmail_path = \"/usr/bin/msmtp -t\"" > /usr/local/etc/php/conf.d/mailhog.ini

# Проверяем, установлен ли sendmail и telnet
RUN which msmtp || echo "msmtp not found"
RUN which telnet || echo "telnet not found"

CMD ["php-fpm"]
