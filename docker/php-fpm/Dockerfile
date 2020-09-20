FROM php:7.4-fpm

ENV PATH /usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    zip \
    libmemcached-dev \
    git \
    unzip \
    libpng-dev

RUN docker-php-ext-configure bcmath --enable-bcmath \
    && docker-php-ext-install bcmath

RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql \
    && docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

WORKDIR /app
