ARG PHP_VERSION
FROM php:8.4-fpm
ARG user=default
ARG uid=1000

RUN apt-get update && apt-get install -y \
  git \
  curl \
  libpng-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd gd sockets

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
  chown -R $user:$user /home/$user


WORKDIR /var/www

USER $user