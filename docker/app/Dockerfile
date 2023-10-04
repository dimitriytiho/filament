ARG PHP_VERSION=${PHP_VERSION}

FROM php:${PHP_VERSION}-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk update && apk upgrade
RUN apk add build-base autoconf libzip-dev libpng-dev libjpeg-turbo-dev libwebp-dev bash openssh git nodejs npm

#RUN pecl install xdebug-3.2.2
RUN docker-php-ext-configure gd --with-jpeg --with-webp && docker-php-ext-install gd
RUN docker-php-ext-install exif
RUN docker-php-ext-install zip
RUN docker-php-ext-install bz2
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install bcmath

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Установка и включение расширения GD
RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev icu-dev icu && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd

RUN apk update && apk upgrade
RUN docker-php-ext-configure intl && \
       docker-php-ext-install intl

RUN composer global config minimum-stability alpha
ENV PATH=/root/.composer/vendor/bin:$PATH

ARG USER_NAME
ARG USER_ID
ARG GROUP_ID


# Исполняем только в случае если указаны USER_ID и GROUP_ID
# Создание пользователя с UID и GID, переданными в аргументах. Это необходимо для правильной работы crontab
RUN if [ "$USER_ID" != "0" ] && [ "$GROUP_ID" != "0" ]; then \
      addgroup -S -g $GROUP_ID $USER_NAME && adduser -S -u $USER_ID -G $USER_NAME $USER_NAME; \
    fi

CMD php-fpm -R
