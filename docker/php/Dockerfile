FROM yiisoftware/yii2-php:8.2-fpm

ENV TIME_ZONE Europe/Moscow
ENV PHP_ENABLE_XDEBUG 1

# Установка PHP и расширений
RUN apt-get update && apt-get -y install git wget libpq-dev ffmpeg
RUN docker-php-ext-install pcntl sockets pdo pdo_pgsql pgsql
RUN rm -rf /var/lib/apt/lists/*

WORKDIR /app

RUN addgroup --gid 1000 --system app

# Скачивание необходимых файлов
RUN wget "http://browscap.org/stream?q=Lite_PHP_BrowsCapINI" -O /usr/local/etc/php/php_browscap.ini

# Копирование конфигурации
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
ADD ./docker/php/output.conf /usr/local/etc/php/conf.d/output.conf
ADD ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
ADD ./docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

# Удаление ненужных файлов
RUN rm /usr/local/etc/php/conf.d/base.ini -f

# Установка Composer
RUN echo "Install Composer"
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version
