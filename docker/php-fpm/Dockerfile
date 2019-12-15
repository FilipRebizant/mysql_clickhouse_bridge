FROM thecodingmachine/php:7.3-v2-fpm

USER root

RUN apt-get update && \
    docker-php-ext-enable xdebug  && \
    echo 'xdebug.remote_port=10000' >> /usr/local/etc/php/php.ini

COPY ./composer.json /var/www/html
COPY ./composer.lock /var/www/html
RUN composer install

USER docker