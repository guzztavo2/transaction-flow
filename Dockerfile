FROM php:8.3-cli

# Instala extensões do PHP
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    libicu-dev \
    pkg-config \ 
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring intl

RUN apt-get update && apt-get install -y supervisor
RUN docker-php-ext-install bcmath
RUN pecl install xdebug && docker-php-ext-enable xdebug 
#\
 #&& pecl install redis && docker-php-ext-enable redis

COPY ./php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN apt-get update && apt-get install -y nano

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install && php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0"]