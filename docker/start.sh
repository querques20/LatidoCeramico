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
        if mysqladmin ping -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" --silent 2>/dev/null; then
            echo "[db-init] MySQL listo."
            break
        fi
        sleep 3
    done

    TABLE_EXISTS=$(mysql -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" \
        -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}' AND table_name='productos';" \
        --skip-column-names 2>/dev/null || echo "0")

    if [ "${TABLE_EXISTS}" = "0" ] && [ -f /var/www/html/data/latidoceramico.sql ]; then
        echo "[db-init] Importando base de datos inicial..."
        mysql -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USER}" -p"${DB_PASS}" "${DB_NAME}" \
            < /var/www/html/data/latidoceramico.sql \
            && echo "[db-init] Importación OK." \
            || echo "[db-init] Error en importación."
    else
        echo "[db-init] DB ya inicializada."
    fi
) &

# Iniciar php-fpm
php-fpm -D

# Iniciar nginx en primer plano
exec nginx -g "daemon off;"
