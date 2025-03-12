# syntax=docker/dockerfile:1

FROM php:8.3.6-fpm-alpine3.19 as base-runtime

ARG DOCKER_USER="www-data"
ARG DOCKER_USER_UID="1000"
ARG DOCKER_USER_GID="1000"
ARG DOCKER_HOME="/home/www-data"

ENV PS1="\[\033[1;36m\][${DOCKER_USER-user}@\H] \[\033[1;34m\]\w\[\033[0;35m\] \[\033[1;36m\]# \[\033[0m\]"
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME="/tmp/composer"

COPY --from=composer:2.7.2 /usr/bin/composer /usr/bin/composer

RUN mkdir -p --mode=755 /app ${COMPOSER_HOME} \
 && mkdir -p --mode=777 ${COMPOSER_HOME}/cache/repo ${COMPOSER_HOME}/cache/files ${COMPOSER_HOME}/cache/vcs \
 && chown -R www-data:www-data /app ${COMPOSER_HOME}

RUN set -x \
    && apk --update add --no-cache  \
        mysql-client mysql-dev \
        libjpeg-turbo-dev \
        openssh-client \
        freetype-dev \
        libavif-dev \
        libwebp-dev \
        libpng-dev \
        libzip-dev \
        supervisor  \
        shadow  \
        su-exec \
        icu-dev \
        nginx \
        less \
        sudo \
        bash \
        nano \
        git \
        zip \
        npm \
        librdkafka-dev \
    && apk add --no-cache --virtual .build-deps \
        autoconf \
        openssl \
        make \
        g++  \
    && npm install -g redoc-cli \
    && apk add --no-cache fontconfig ttf-dejavu

RUN pecl install rdkafka \
    && docker-php-ext-enable rdkafka

RUN docker-php-ext-configure gd  \
    --with-avif --with-jpeg --with-webp --with-freetype

RUN set -eux;  apk add --no-cache --virtual .sockets-deps  linux-headers  \
    && CFLAGS=${CFLAGS:+${CFLAGS} }-D_GNU_SOURCE docker-php-ext-install -j$(nproc)  \
    pdo pdo_mysql mysqli  \
    sockets \
    opcache \
    pcntl \
    intl \
    zip \
    gd \
    && apk del .sockets-deps

ARG DOCKER_USER
ARG DOCKER_USER_UID
ARG DOCKER_USER_GID

RUN groupmod --gid $DOCKER_USER_GID $DOCKER_USER \
    && usermod --uid $DOCKER_USER_UID --gid $DOCKER_USER_GID $DOCKER_USER \
    && usermod -aG nginx $DOCKER_USER \
    && mkdir -p /etc/sudoers.d \
    && echo "${DOCKER_USER-www-data} ALL=(ALL) NOPASSWD: ALL" > "/etc/sudoers.d/${DOCKER_USER-www-data}" \
    && chmod 0440 /etc/sudoers.d/$DOCKER_USER

RUN chown -R www-data:www-data /app ${COMPOSER_HOME} /tmp

RUN docker-php-source delete && apk del .build-deps

COPY ./back/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./back/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./back/scripts/start-container.dev.sh /usr/local/bin/start-container
COPY ./back/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN chmod 775 /usr/local/bin/start-container \
    && chmod -R 775 /var/lib/nginx/* \
    && chmod -R 775 /var/run/nginx \
    && chown -R www-data:www-data /var/run/nginx

USER www-data

WORKDIR /app

ADD ./back/supervisord/supervisord-php8.3-alpine.conf /etc/supervisord.conf

CMD ["/usr/local/bin/start-container"]
