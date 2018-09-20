FROM php:7.2-fpm
RUN apt update && apt install -y \
    curl \
    wget \
    git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    icu-devtools \
    libicu-dev \
    libpq-dev \
    libxpm-dev \
    libvpx-dev \
  && docker-php-ext-install -j$(nproc) intl \
  && docker-php-ext-install -j$(nproc) zip \
  && docker-php-ext-install -j$(nproc) pdo_mysql \
  && docker-php-ext-install -j$(nproc) exif \
