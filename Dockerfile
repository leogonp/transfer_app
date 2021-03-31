FROM php:8.0.3-fpm-alpine3.13

ARG USER=nginx

RUN apk --update add \
        nginx \
        curl \
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

USER $USER

WORKDIR /app
RUN composer install --no-autoloader

ENTRYPOINT ["nginx", "-g", "daemon off;"]
