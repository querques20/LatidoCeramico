#!/bin/sh

# Railway inyecta $PORT — nginx debe escuchar en ese puerto
PORT="${PORT:-80}"
sed -i "s/listen 80;/listen ${PORT};/" /etc/nginx/sites-available/default

# Variables de conexión MySQL
DB_HOST="${MYSQL_HOST:-latidoceramico_db}"
DB_PORT="${MYSQL_PORT:-3306}"
DB_NAME="${MYSQL_DATABASE:-latidoceramico}"
DB_USER="${MYSQL_USER:-latido}"
DB_PASS="${MYSQL_PASSWORD:-latido123}"

# Importar SQL en background (no bloquea el arranque)
(
    echo "[db-init] Esperando MySQL en ${DB_HOST}:${DB_PORT}..."
    for i in $(seq 1 30); do
        if mysqladmin ping -h"${DB_HOST}" -P"${DB_PORT}" -u root -p"${MYSQL_ROOT_PASSWORD:-root}" --skip-ssl --silent 2>/dev/null; then
            echo "[db-init] MySQL listo."
            break
        fi
        sleep 3
    done

    HAS_DATA=$(mysql -h"${DB_HOST}" -P"${DB_PORT}" -u root -p"${MYSQL_ROOT_PASSWORD:-root}" --skip-ssl \
        -e "SELECT COUNT(*) FROM ${DB_NAME}.productos;" \
        --skip-column-names 2>/dev/null || echo "0")

    if [ "${HAS_DATA}" = "0" ] && [ -f /var/www/html/data/latidoceramico.sql ]; then
        echo "[db-init] Importando base de datos inicial..."
        mysql -h"${DB_HOST}" -P"${DB_PORT}" -u root -p"${MYSQL_ROOT_PASSWORD:-root}" --skip-ssl \
            -e "DROP DATABASE IF EXISTS \`${DB_NAME}\`; CREATE DATABASE \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; GRANT ALL ON \`${DB_NAME}\`.* TO '${DB_USER}'@'%';" 2>&1
        mysql -h"${DB_HOST}" -P"${DB_PORT}" -u root -p"${MYSQL_ROOT_PASSWORD:-root}" --skip-ssl "${DB_NAME}" \
            < /var/www/html/data/latidoceramico.sql 2>&1 \
            && echo "[db-init] Importación OK." \
            || echo "[db-init] Error en importación."
    else
        echo "[db-init] DB ya inicializada (${HAS_DATA} productos)."
    fi
) &

# Iniciar php-fpm
php-fpm -D

# Iniciar nginx en primer plano
exec nginx -g "daemon off;"
