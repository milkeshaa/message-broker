FROM php:8.2.0-alpine

ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

WORKDIR /var/www

COPY --chown=www-data:www-data ./ /var/www

RUN chown -R www-data:www-data /var/www

USER 1000:1000

VOLUME ["/var/www"]
