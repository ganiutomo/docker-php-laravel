FROM php:5.6-apache

MAINTAINER Gani Utomo <ganiutomo@gmail.com>

RUN apt-get update && apt-get install -y \
    freetds-dev \
    libicu-dev \
    libpq-dev \
    libmcrypt-dev \
 && rm -r /var/lib/apt/lists/* \
 && cp -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/ \
 && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
 && docker-php-ext-install \
    intl \
    mbstring \
    mcrypt \
    mssql \
    pcntl \
    pdo_dblib \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    zip \
 && curl -sS https://getcomposer.org/installer | php \
 && mv composer.phar /usr/local/bin/ \
 && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer \
    opcache \
 && cd /usr/src/php \
 && make clean
