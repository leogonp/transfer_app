FROM php:8.0.3-fpm-alpine3.13

ARG USER=nginx

RUN apk --update add \
        nginx \
        curl \
        supervisor \
    && rm -rf /var/cache/apk/* \
    && docker-php-ext-install \
	    mysqli \
	    pdo \
	    pdo_mysql \
	&& mkdir -p /run/nginx \
	&& chown ${USER}:${USER} -R /run/nginx \
	&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY --chown=${USER}:${USER} files/default.conf /etc/nginx/http.d/default.conf
COPY --chown=${USER}:${USER} src/ /app
COPY --chown=${USER}:${USER} files/supervisord.conf /etc/supervisord.conf
COPY --chown=${USER}:${USER} --chmod=500 files/entrypoint /entrypoint

USER $USER

WORKDIR /app
RUN composer install

ENTRYPOINT ["/bin/sh", "/entrypoint"]
CMD ["/usr/bin/supervisord"]
