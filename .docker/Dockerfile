FROM php:8.2-apache

USER root

RUN mkdir /app
RUN a2enmod rewrite
RUN mkdir /etc/apache2/sites-available/ssl

COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY .docker/apache/junicalmedical.com.co.crt /etc/apache2/sites-available/ssl/
COPY .docker/apache/junicalmedical.com.co.key /etc/apache2/sites-available/ssl/

WORKDIR /app

RUN chown -R www-data:www-data /app && a2enmod rewrite ssl

RUN apt-get update \
    && apt-get install -y default-mysql-client \
    && apt-get install -y curl \
    && apt-get install -y zsh \
    && apt-get install -y wget \
    && apt-get install -y git \
    && apt-get install -y unzip \
    && apt-get install -y sudo \
    && apt-get install -y nano \
    && apt-get install -y cron \
    && apt-get install -y sendmail \
    && apt-get install -y libpng-dev \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && apt-get install -y libonig-dev \
    && apt-get install -y libevent-dev \
    && apt-get install -y libssl-dev \
    && pecl install ev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-enable gd \
    && docker-php-ext-enable zip \
    && a2enmod rewrite \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && sh -c "$(wget -O- https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"


