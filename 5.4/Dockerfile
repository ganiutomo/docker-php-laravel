FROM php:5.4

RUN apt-get update && apt-get install -y \
        libpq-dev \
        libmcrypt-dev \
    && docker-php-ext-configure \
        pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install \
        mbstring \
        mcrypt \
        opcache \
        pdo_mysql \
        pdo_pgsql \
        pgsql