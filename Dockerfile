FROM php:8.0.3-fpm-alpine3.13

RUN apk --update add \
        nginx \
    && rm -rf /var/cache/apk/* \
    && docker-php-ext-install \
	    mysqli \
	    pdo \
	    pdo_mysql



COPY src/ /app
COPY --chmod=0644 scripts/ /
#CMD ["nginx", "-g", "daemon off;"]
