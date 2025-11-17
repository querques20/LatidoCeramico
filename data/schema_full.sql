
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `latidoceramico` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `latidoceramico`;

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(120) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `descripcion` TEXT NULL,
  `meta_title` VARCHAR(180) NULL,
  `meta_description` VARCHAR(255) NULL,
  `orden` INT UNSIGNED NOT NULL DEFAULT 0,
  `visible` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_slug_categoria` (`slug`),
  KEY `idx_visible` (`visible`),
  KEY `idx_orden` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `productos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `categoria_id` INT UNSIGNED NOT NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `stock` INT UNSIGNED NOT NULL DEFAULT 0,
  `sku` VARCHAR(60) NULL,
  `imagen_principal` VARCHAR(255) NULL,
  `meta_title` VARCHAR(180) NULL,
  `meta_description` VARCHAR(255) NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_slug_producto` (`slug`),
  KEY `idx_categoria` (`categoria_id`),
  KEY `idx_activo` (`activo`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `secciones` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(100) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `titulo` VARCHAR(180) NULL,
  `descripcion` TEXT NULL,
  `nav` TINYINT(1) NOT NULL DEFAULT 0,
  `orden` DECIMAL(5,2) NOT NULL DEFAULT 999.00,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `view_file` VARCHAR(120) NULL,
  `meta_title` VARCHAR(180) NULL,
  `meta_description` VARCHAR(255) NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_slug_seccion` (`slug`),
  KEY `idx_nav` (`nav`),
  KEY `idx_activo_seccion` (`activo`),
  KEY `idx_orden_seccion` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ajustes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `clave` VARCHAR(100) NOT NULL,
  `valor` TEXT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_clave_ajuste` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ordenes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` BIGINT UNSIGNED NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `telefono` VARCHAR(50) NULL,
  `direccion` VARCHAR(200) NOT NULL,
  `localidad` VARCHAR(120) NULL,
  `cp` VARCHAR(20) NULL,
  `notas` TEXT NULL,
  `subtotal` INT UNSIGNED NOT NULL DEFAULT 0,
  `estado` ENUM('pendiente','pagado','cancelado') NOT NULL DEFAULT 'pagado',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`usuario_id`),
  KEY `idx_estado` (`estado`),
  KEY `idx_created_orden` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `orden_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `orden_id` BIGINT UNSIGNED NOT NULL,
  `producto_id` BIGINT UNSIGNED NULL,
  `nombre` VARCHAR(150) NOT NULL,
  `precio` INT UNSIGNED NOT NULL DEFAULT 0,
  `cantidad` INT UNSIGNED NOT NULL DEFAULT 1,
  `total` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_orden` (`orden_id`),
  CONSTRAINT `fk_orden_items_orden` FOREIGN KEY (`orden_id`) REFERENCES `ordenes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `carritos` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` BIGINT UNSIGNED NULL,
  `session_id` VARCHAR(100) NULL,
  `estado` ENUM('activo','convertido','expirado') NOT NULL DEFAULT 'activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_session` (`session_id`),
  KEY `idx_usuario_carrito` (`usuario_id`),
  KEY `idx_estado_carrito` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `carrito_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `carrito_id` BIGINT UNSIGNED NOT NULL,
  `producto_id` BIGINT UNSIGNED NOT NULL,
  `cantidad` INT UNSIGNED NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_carrito` (`carrito_id`),
  KEY `idx_producto_carrito` (`producto_id`),
  CONSTRAINT `fk_carrito_items_carrito` FOREIGN KEY (`carrito_id`) REFERENCES `carritos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `usuarios_admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NULL,
  `email` VARCHAR(150) NOT NULL,
  `telefono` VARCHAR(50) NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_login` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email_admin` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `admin_secciones` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` VARCHAR(60) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `icono` VARCHAR(60) NULL,
  `en_menu` TINYINT(1) NOT NULL DEFAULT 1,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `orden` INT UNSIGNED NOT NULL DEFAULT 10,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_slug_admin_sec` (`slug`),
  KEY `idx_activo_admin_sec` (`activo`),
  KEY `idx_en_menu_admin_sec` (`en_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
