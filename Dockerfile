FROM php:8.3-cli

# Instala extensões do PHP
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    libicu-dev \
    pkg-config \ 
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring intl


RUN pecl install xdebug && docker-php-ext-enable xdebug

# COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN apt-get update && apt-get install -y nano

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia os arquivos do projeto
COPY . .

# Instala dependências do Laravel
RUN composer install && php artisan key:generate

# Expondo a porta do Laravel
EXPOSE 8000

# Comando padrão do container
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
