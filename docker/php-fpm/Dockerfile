FROM thecodingmachine/php:7.3-v2-fpm

USER root

RUN apt-get update && \
    docker-php-ext-enable xdebug  && \
    echo 'xdebug.remote_port=10000' >> /usr/local/etc/php/php.ini

RUN composer install

USER docker