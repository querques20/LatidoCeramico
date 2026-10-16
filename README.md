# Latido Cerámico

Tienda online para un emprendimiento de cerámica artesanal, con catálogo, carrito, checkout y panel de administración. Está hecha en PHP 8 orientado a objetos con MySQL 8, y el entorno completo se levanta con Docker Compose.

## Qué tiene

**Tienda**

- Catálogo por categorías, detalle de producto, combos y favoritos.
- Carrito que se sincroniza con la base y vence si no se completa la compra.
- Checkout con confirmación y órdenes con estados.
- Registro e inicio de sesión de clientes.
- Clases con preinscripción y formulario de contacto.

**Panel de administración** (`/admin`, con su propio login)

- Alta, edición y baja de productos, categorías, galería y usuarios.
- Órdenes con cambio de estado y seguimiento de carritos.
- Secciones del sitio configurables desde la base: orden, visibilidad en el menú e íconos.
- Configuración general del sitio.

## Stack

| Parte | Tecnologías |
|---|---|
| Backend | PHP 8.2 orientado a objetos, PDO |
| Base de datos | MySQL 8 (14 tablas) |
| Servidor | Nginx + PHP-FPM |
| Entorno | Docker, Docker Compose y phpMyAdmin |

## Cómo correrlo

```bash
docker compose up -d --build
```

| Servicio | Dirección |
|---|---|
| Sitio | http://localhost:8080 |
| Panel de administración | http://localhost:8080/admin |
| phpMyAdmin | http://localhost:8081 |

Al arrancar, el contenedor espera a que MySQL esté listo y, si la base está vacía, importa los datos de `data/latidoceramico.sql`.

La conexión toma las variables `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_DATABASE`, `MYSQL_USER` y `MYSQL_PASSWORD`, y Nginx escucha en `$PORT` si existe, así que el mismo contenedor también corre en servicios como Railway.

## Estructura

```
├── index.php      entrada del sitio y ruteo por secciones
├── secciones/     páginas de la tienda
├── clases/        modelos: Productos, Carrito, Ordenes, Usuario…
├── api/           endpoints del carrito
├── admin/         panel: actions/, classes/ y views/
├── data/          dump de la base
└── docker/        Nginx y script de arranque
```

## Base de datos

14 tablas: `productos`, `producto_imagenes`, `categorias`, `combo_items`, `carritos`, `carrito_items`, `ordenes`, `orden_items`, `usuarios`, `usuarios_admin`, `galeria`, `secciones`, `admin_secciones` y `ajustes`.

## Segunda versión

Después hice una reescritura con Node, Express y PostgreSQL, con el frontend en Vue 3 y TypeScript. Antes de arrancarla audité esta versión y dejé por escrito el plan de migración.
