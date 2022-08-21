FROM php:8.1-apache

WORKDIR /var/www/html

COPY ./apache-config/apache-config.conf /etc/apache2/sites-available/000-default.conf
COPY ./apache-config/hosts /etc/hosts
COPY ./apache-config/rewrite.load /etc/apache2/mods-enabled

RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

