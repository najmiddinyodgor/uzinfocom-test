FROM php:8.1-fpm-alpine

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY conf.d /usr/local/etc/php/conf.d

WORKDIR /app

RUN apk add --no-cache --virtual build-essentials \
    icu-dev icu-libs zlib-dev g++ make automake autoconf

RUN docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-install opcache && \
    docker-php-ext-install exif && \
    apk del build-essentials && rm -rf /usr/src/php*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]
