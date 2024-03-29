FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        oniguruma-dev \
        zlib-dev \
        libxml2-dev \
        imagemagick-dev \
    && apk add --no-cache \
        git \
        shadow \
        supervisor \
        mysql-client \
        libpng-dev \
        libjpeg-turbo-dev \
        libpq-dev \
        libzip-dev \
        php81-pecl-imagick \
    && pecl install \
        pcov \
        redis \
        imagick \
    && docker-php-ext-enable \
        pcov \
        redis \
        imagick \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install \
        mbstring \
        bcmath \
        pcntl \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        gd \
        zip \
        xml \
    && curl -s https://getcomposer.org/installer | \
        php -- --install-dir=/usr/local/bin/ --filename=composer \
    && apk del -f .build-deps

RUN set -eux; \
        docker-php-ext-enable opcache; \
        { \
            echo 'opcache.memory_consumption=128'; \
            echo 'opcache.interned_strings_buffer=8'; \
            echo 'opcache.max_accelerated_files=4000'; \
            echo 'opcache.revalidate_freq=2'; \
            echo 'opcache.fast_shutdown=1'; \
            echo 'upload_max_filesize=128M'; \
            echo 'post_max_size=128M'; \
        } > /usr/local/etc/php/conf.d/opcache-recommended.ini


COPY . /var/www/html/

RUN chmod +x ./scripts/wait-for.sh


# Configure non-root user.
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

USER www-data