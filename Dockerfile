FROM php

RUN apt-get update && apt-get install -y \
        freetds-dev \
        libpq-dev \
        libmcrypt-dev \
    && cp -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/ \
    && docker-php-ext-configure \
        pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install \
        mbstring \
        mcrypt \
        mssql \
        opcache \
        pdo_dblib \
        pdo_mysql \
        pdo_pgsql \
        pgsql