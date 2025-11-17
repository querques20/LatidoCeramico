
FROM php:8.2-apache

RUN a2enmod rewrite \
	&& { \
		echo '<VirtualHost *:80>'; \
		echo '  DocumentRoot /var/www/html'; \
		echo '  <Directory /var/www/html>'; \
		echo '    Options Indexes FollowSymLinks'; \
		echo '    AllowOverride All'; \
		echo '    Require all granted'; \
		echo '  </Directory>'; \
		echo '  DirectoryIndex index.php index.html'; \
		echo '</VirtualHost>'; \
	} > /etc/apache2/sites-available/000-default.conf

RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
	   libjpeg62-turbo-dev libpng-dev libwebp-dev \
	&& docker-php-ext-configure gd --with-jpeg --with-webp \
	&& docker-php-ext-install gd pdo pdo_mysql \
	&& rm -rf /var/lib/apt/lists/*


WORKDIR /var/www/html
COPY . /var/www/html


RUN chown -R www-data:www-data /var/www/html

ENV PHP_DATE_TIMEZONE=America/Argentina/Buenos_Aires
RUN echo "date.timezone=${PHP_DATE_TIMEZONE}" > /usr/local/etc/php/conf.d/zz-timezone.ini

EXPOSE 80
