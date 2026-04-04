FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       nginx \
       libjpeg62-turbo-dev libpng-dev libwebp-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install gd pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

ENV PHP_DATE_TIMEZONE=America/Argentina/Buenos_Aires
RUN echo "date.timezone=${PHP_DATE_TIMEZONE}" > /usr/local/etc/php/conf.d/zz-timezone.ini

WORKDIR /var/www/html
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80
CMD ["/start.sh"]
