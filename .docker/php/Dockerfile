FROM php:8.2.0-cli

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN pecl install redis-6.0.2 \
    && docker-php-ext-enable redis

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www

COPY --chown=www-data:www-data ./ /var/www

RUN chown -R www-data:www-data /var/www

USER 1000:1000

VOLUME ["/var/www"]
