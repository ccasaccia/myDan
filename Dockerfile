FROM php:8.1-apache AS build

# Install additional PHP Packages and WHMCS Requirements
ARG TARGETARCH
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        curl \
        libmemcached-dev \
        libz-dev \
        libpq-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        libzip-dev \
        ssmtp \
        libyaml-dev \
        zlib1g-dev \
        libicu-dev \
        libgmp-dev \
        g++ \
        && rm -rf /var/lib/apt/lists/*

# PHP Extension
RUN docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd zip pdo_mysql mysqli gmp bcmath sockets

# Install the php ioncube loader
# Essential part to run WHMCS
# https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_aarch64.tar.gz (Download the right image based on TARGETARCH)
RUN cd /tmp \
   &&  case ${TARGETARCH} in \
            "arm64")  IONCUBE_IMAGE=aarch64  ;; \
            *)  IONCUBE_IMAGE=x86-64;; \
        esac \
    && curl -o ioncube.tar.gz https://downloads.ioncube.com/loader_downloads/ioncube_loaders_lin_${IONCUBE_IMAGE}.tar.gz \
    && tar zxpf ioncube.tar.gz \
    && mv ioncube/ioncube_loader_lin_8.1.so /usr/local/lib/php/extensions/* \
    && rm -Rf ioncube.tar.gz ioncube \
    && echo "zend_extension=ioncube_loader_lin_8.1.so" > /usr/local/etc/php/conf.d/docker-php-ext-ioncube_loader.ini \
    && rm -rf /tmp/ioncube*


# Install PECL extensions
RUN pecl install xdebug-3.1.6 && \
    docker-php-ext-enable xdebug
RUN pecl install yaml-2.2.2 && \
    docker-php-ext-enable yaml

# Install Composer and Laravel Installer
RUN cd /tmp && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer && \
    composer global require laravel/installer && \
    ln -s /root/.composer/vendor/bin/laravel /usr/local/bin/laravel

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/*
