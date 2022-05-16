FROM php:8.1.5-fpm-bullseye

RUN docker-php-ext-install \
	pdo_mysql

RUN apt-get -y update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN docker-php-ext-configure opcache --enable-opcache && docker-php-ext-install opcache

RUN pecl install redis && docker-php-ext-enable redis

RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'memory_limit = 800M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'upload_max_filesize = 100M' >> /usr/local/etc/php/conf.d/uploads.ini

RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'post_max_size = 100M' >> /usr/local/etc/php/conf.d/uploads.ini

RUN cd /usr/local/etc/php/conf.d/ && \
  echo 'date.timezone = "Europe/Moscow"' >> /usr/local/etc/php/conf.d/tzone.ini

EXPOSE 9000