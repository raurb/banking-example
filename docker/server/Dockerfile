FROM php:8.4-apache

RUN apt-get update \
    && apt-get install -y libzip-dev libicu-dev zip \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install sockets \
    && docker-php-ext-install opcache \
    && docker-php-ext-install intl \
    && a2enmod rewrite \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install APCU
RUN pecl update-channels
RUN pecl install apcu

RUN pecl install xdebug
RUN apt-get install -y supervisor vim

COPY ./php-additional-config.ini /usr/local/etc/php/conf.d/php-additional-config.ini
COPY ./vhost.conf /etc/apache2/sites-enabled/000-default.conf

RUN usermod -u 1000 www-data

RUN echo "ServerName banking-example.local" >> /etc/apache2/apache2.conf

RUN service apache2 restart

WORKDIR /var/www
