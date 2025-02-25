FROM php:8.2-fpm

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_mysql zip curl mbstring gd mbstring xml intl openssl

WORKDIR /app

ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer /usr/bin/composer /usr/bin/composer


#CMD ["/bin/sh", "-c"," chmod +x install.sh && ./install.sh"]









