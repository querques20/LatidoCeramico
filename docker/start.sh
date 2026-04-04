#!/bin/sh
set -e

# Railway inyecta $PORT — nginx debe escuchar en ese puerto
PORT="${PORT:-80}"
sed -i "s/listen 80;/listen ${PORT};/" /etc/nginx/sites-available/default

# Iniciar php-fpm en segundo plano
php-fpm -D

# Iniciar nginx en primer plano
exec nginx -g "daemon off;"
