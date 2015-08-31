FROM php:5.5-apache

RUN apt-get update \
    && apt-get install -y \
        freetds-dev \
        libpq-dev \
        libmcrypt-dev \
    && cp -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/

RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    bz2

RUN docker-php-ext-install bcmath bz2 calendar dba exif ftp gd gettext gmp imap mbstring mcrypt mssql pcntl pdo_dblib pdo_mysql pdo_pgsql pgsql shmop soap sockets sysvmsg sysvsem sysvshm wddx zip opcache
