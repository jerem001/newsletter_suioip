FROM php:8.3-fpm-bookworm

ARG UID=1000
ARG GID=1000

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        libicu-dev \
        libldap2-dev \
        libonig-dev \
        libxml2-dev \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install \
        pdo_pgsql \
        pgsql \
        zip \
        intl \
        ldap \
        bcmath \
        opcache \
        xml \
    && pecl install redis && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd -g ${GID} suioip \
    && useradd -u ${UID} -g suioip -m suioip

WORKDIR /var/www/html

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-suioip.ini

USER suioip

EXPOSE 9000

CMD ["php-fpm"]
