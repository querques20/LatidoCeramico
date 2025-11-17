-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 17-11-2025 a las 18:55:35
-- Versión del servidor: 8.0.43
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `latidoceramico`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_secciones`
--

CREATE TABLE `admin_secciones` (
  `id` int UNSIGNED NOT NULL,
  `slug` varchar(60) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `icono` varchar(60) DEFAULT NULL,
  `en_menu` tinyint(1) NOT NULL DEFAULT '1',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int UNSIGNED NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `admin_secciones`
--

INSERT INTO `admin_secciones` (`id`, `slug`, `nombre`, `icono`, `en_menu`, `activo`, `orden`, `created_at`) VALUES
(1, 'inicio', 'Dashboard', 'house-door', 1, 1, 1, '2025-11-11 20:36:19'),
(2, 'productos', 'Productos', 'box-seam', 1, 1, 2, '2025-11-11 20:36:19'),
(3, 'categorias', 'Categorías', 'tags', 1, 1, 3, '2025-11-11 20:36:19'),
(4, 'usuarios', 'Usuarios', 'people', 1, 1, 4, '2025-11-11 20:36:19'),
(5, 'ordenes', 'Órdenes', 'receipt', 1, 1, 5, '2025-11-11 20:36:19'),
(6, 'configuracion', 'Configuración', 'gear', 1, 1, 99, '2025-11-11 20:36:19'),
(7, 'orden_detalle', 'Detalle Orden', 'receipt', 0, 1, 50, '2025-11-11 20:36:19'),
(8, 'crear_producto', 'Crear Producto', 'plus-circle', 0, 1, 20, '2025-11-11 20:36:19'),
(9, 'editar_producto', 'Editar Producto', 'pencil-square', 0, 1, 21, '2025-11-11 20:36:19'),
(10, 'borrar_producto', 'Borrar Producto', 'trash', 0, 1, 22, '2025-11-11 20:36:19'),
(11, 'crear_categoria', 'Crear Categoría', 'plus-circle', 0, 1, 30, '2025-11-11 20:36:19'),
(12, 'editar_categoria', 'Editar Categoría', 'pencil-square', 0, 1, 31, '2025-11-11 20:36:19'),
(13, 'borrar_categoria', 'Borrar Categoría', 'trash', 0, 1, 32, '2025-11-11 20:36:19'),
(14, 'carritos', 'Carritos', 'cart', 1, 1, 6, '2025-11-11 21:07:22'),
(15, 'carrito_detalle', 'Detalle Carrito', 'cart', 0, 1, 51, '2025-11-11 21:07:22'),
(5194, 'galeria', 'Galería', 'images', 1, 1, 7, '2025-11-16 23:53:58'),
(5258, 'editar_galeria', 'Editar Galería', 'pencil-square', 0, 1, 70, '2025-11-16 23:59:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes`
--

CREATE TABLE `ajustes` (
  `id` int UNSIGNED NOT NULL,
  `clave` varchar(100) NOT NULL,
  `valor` text,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `ajustes`
--

INSERT INTO `ajustes` (`id`, `clave`, `valor`, `updated_at`) VALUES
(1, 'site_name', 'Latido Cerámico', '2025-11-15 22:14:07'),
(2, 'contact_email', 'latidoceramico@gmail.com', '2025-11-17 01:34:17'),
(3, 'contact_phone', '+541139109022', '2025-11-17 01:34:55'),
(4, 'whatsapp_link', 'https://api.whatsapp.com/send/?phone=1134031388&text&type=phone_number&app_absent=0', '2025-11-17 01:34:17'),
(5, 'instagram_url', 'https://www.instagram.com/latido.ceramico?igsh=MWk0ajV6c2tlOWJ2Zg==', '2025-11-17 01:34:17'),
(7, 'carrito_exp_min', '120', '2025-11-15 22:14:07'),
(8, 'maintenance_mode', '0', '2025-11-15 22:14:18'),
(49, 'whatsapp_qr', '1763344993_3231.webp', '2025-11-17 02:03:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario_id` bigint UNSIGNED DEFAULT NULL,
  `session_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','convertido','expirado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carritos`
--

INSERT INTO `carritos` (`id`, `usuario_id`, `session_id`, `estado`, `created_at`, `updated_at`) VALUES
(22, NULL, '37b864ba6f3e26134bffc6b96b7de898', 'activo', '2025-11-17 17:49:33', '2025-11-17 17:49:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_items`
--

CREATE TABLE `carrito_items` (
  `id` bigint UNSIGNED NOT NULL,
  `carrito_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` int UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `carrito_items`
--

INSERT INTO `carrito_items` (`id`, `carrito_id`, `producto_id`, `cantidad`, `created_at`) VALUES
(565, 22, 28, 2, '2025-11-17 17:49:34'),
(566, 22, 30, 1, '2025-11-17 17:49:34'),
(567, 22, 29, 1, '2025-11-17 17:49:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `meta_title` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `orden` int UNSIGNED DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `meta_title`, `meta_description`, `orden`, `visible`, `created_at`, `updated_at`) VALUES
(1, 'Tazas', 'tazas', NULL, NULL, NULL, 1, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(2, 'Platos', 'platos', NULL, NULL, NULL, 2, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(3, 'Cuencos', 'cuencos', NULL, NULL, NULL, 3, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(4, 'Teteras', 'teteras', NULL, NULL, NULL, 4, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(5, 'Platos hondos', 'platos hondos', NULL, NULL, NULL, 5, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(6, 'Jarrones', 'jarrones', NULL, NULL, NULL, 6, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(7, 'Macetas', 'macetas', NULL, NULL, NULL, 7, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(8, 'Azucareras', 'azucareras', NULL, NULL, NULL, 8, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(9, 'Portavelas', 'portavelas', NULL, NULL, NULL, 9, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(10, 'Ceniceros', 'ceniceros', NULL, NULL, NULL, 10, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(11, 'Lamparas', 'lamparas', NULL, NULL, NULL, 11, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(12, 'Figuras', 'figuras', NULL, NULL, NULL, 12, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(13, 'Baldosas', 'baldosas', NULL, NULL, NULL, 13, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(14, 'Vasos', 'vasos', NULL, NULL, NULL, 14, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(15, 'Cazuelas', 'cazuelas', NULL, NULL, NULL, 15, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(16, 'Jaboneras', 'jaboneras', NULL, NULL, NULL, 16, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(17, 'Portacepillos', 'portacepillos', NULL, NULL, NULL, 17, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(18, 'Relojes', 'relojes', NULL, NULL, NULL, 18, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(19, 'Platos decorativos', 'platos decorativos', NULL, NULL, NULL, 19, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(20, 'Esculturas', 'esculturas', NULL, NULL, NULL, 20, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(21, 'Jarritas', 'jarritas', NULL, NULL, NULL, 21, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(22, 'Morteros', 'morteros', NULL, NULL, NULL, 22, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(23, 'Incensarios', 'incensarios', NULL, NULL, NULL, 23, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(24, 'Fuentes', 'fuentes', NULL, NULL, NULL, 24, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(25, 'Condimenteros', 'condimenteros', NULL, NULL, NULL, 25, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(26, 'Cafeteras', 'cafeteras', NULL, NULL, NULL, 26, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(27, 'Hueveras', 'hueveras', NULL, NULL, NULL, 27, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(28, 'Paneras', 'paneras', NULL, NULL, NULL, 28, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(29, 'Aromaterapia', 'aromaterapia', NULL, NULL, NULL, 29, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(30, 'Recipientes', 'recipientes', NULL, NULL, NULL, 30, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(31, 'Platos especiales', 'platos especiales', NULL, NULL, NULL, 31, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(32, 'Soperas', 'soperas', NULL, NULL, NULL, 32, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(33, 'Botijos', 'botijos', NULL, NULL, NULL, 33, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04'),
(34, 'Combos', 'combos', NULL, NULL, NULL, 34, 1, '2025-11-17 04:53:04', '2025-11-17 04:53:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `combo_items`
--

CREATE TABLE `combo_items` (
  `id` bigint UNSIGNED NOT NULL,
  `combo_producto_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `cantidad` int UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `combo_items`
--

INSERT INTO `combo_items` (`id`, `combo_producto_id`, `producto_id`, `cantidad`, `created_at`) VALUES
(1, 101, 1, 1, '2025-11-17 04:53:04'),
(2, 101, 26, 1, '2025-11-17 04:53:04'),
(3, 101, 28, 1, '2025-11-17 04:53:04'),
(4, 101, 34, 1, '2025-11-17 04:53:04'),
(5, 102, 7, 4, '2025-11-17 04:53:04'),
(6, 102, 8, 4, '2025-11-17 04:53:04'),
(7, 102, 3, 4, '2025-11-17 04:53:04'),
(8, 102, 31, 1, '2025-11-17 04:53:04'),
(9, 103, 33, 1, '2025-11-17 04:53:04'),
(10, 103, 25, 2, '2025-11-17 04:53:04'),
(11, 103, 5, 1, '2025-11-17 04:53:04'),
(12, 103, 12, 1, '2025-11-17 04:53:04'),
(13, 104, 6, 1, '2025-11-17 04:53:04'),
(14, 104, 4, 4, '2025-11-17 04:53:04'),
(15, 105, 11, 3, '2025-11-17 04:53:04'),
(16, 105, 10, 2, '2025-11-17 04:53:04'),
(17, 105, 30, 1, '2025-11-17 04:53:04'),
(18, 106, 20, 1, '2025-11-17 04:53:04'),
(19, 106, 21, 1, '2025-11-17 04:53:04'),
(20, 106, 13, 3, '2025-11-17 04:53:04'),
(21, 106, 36, 1, '2025-11-17 04:53:04'),
(22, 107, 19, 1, '2025-11-17 04:53:04'),
(23, 107, 29, 1, '2025-11-17 04:53:04'),
(24, 107, 39, 1, '2025-11-17 04:53:04'),
(25, 107, 32, 1, '2025-11-17 04:53:04'),
(26, 108, 24, 1, '2025-11-17 04:53:04'),
(27, 108, 15, 1, '2025-11-17 04:53:04'),
(28, 108, 16, 1, '2025-11-17 04:53:04'),
(29, 108, 13, 5, '2025-11-17 04:53:04'),
(30, 108, 30, 1, '2025-11-17 04:53:04'),
(31, 109, 35, 1, '2025-11-17 04:53:04'),
(32, 109, 37, 1, '2025-11-17 04:53:04'),
(33, 109, 40, 1, '2025-11-17 04:53:04'),
(34, 109, 12, 1, '2025-11-17 04:53:04'),
(35, 110, 38, 4, '2025-11-17 04:53:04'),
(36, 110, 31, 1, '2025-11-17 04:53:04'),
(37, 110, 29, 1, '2025-11-17 04:53:04'),
(38, 110, 18, 4, '2025-11-17 04:53:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `galeria`
--

CREATE TABLE `galeria` (
  `id` bigint UNSIGNED NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `orden` int UNSIGNED NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `galeria`
--

INSERT INTO `galeria` (`id`, `imagen`, `orden`, `visible`, `created_at`) VALUES
(15, '1763342292_2597.webp', 2, 1, '2025-11-17 01:18:12'),
(16, '1763342298_9802.webp', 1, 1, '2025-11-17 01:18:18'),
(17, '1763342302_6229.webp', 3, 1, '2025-11-17 01:18:23'),
(18, '1763342308_3527.webp', 0, 1, '2025-11-17 01:18:28'),
(19, '1763342316_2719.webp', 4, 1, '2025-11-17 01:18:36'),
(20, '1763342570_7180.webp', 0, 1, '2025-11-17 01:22:50'),
(21, '1763345723_3894.webp', 0, 1, '2025-11-17 02:15:23'),
(22, '1763345726_1627.webp', 7, 1, '2025-11-17 02:15:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario_id` bigint UNSIGNED DEFAULT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','pagado','enviado','completado','cancelado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `metodo_pago` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provincia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_postal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `localidad` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id`, `usuario_id`, `codigo`, `total`, `estado`, `metodo_pago`, `notas`, `nombre`, `email`, `telefono`, `direccion`, `ciudad`, `provincia`, `codigo_postal`, `created_at`, `updated_at`, `localidad`, `cp`, `subtotal`) VALUES
(11, NULL, 'ORD-20251112222811-9712', 39000.00, 'pagado', 'tarjeta', 'serfy', 'cecilia', 'cecilia@gmail.com', '01139109022', 'Carlos Tejedor 3170', NULL, NULL, NULL, '2025-11-13 01:28:11', '2025-11-13 01:28:11', 'Carapachay', 'B1605', 39000),
(12, NULL, 'ORD-20251115193237-1445', 89000.00, 'pagado', 'tarjeta', '', 'mateo garcia', 'mateo@gmail.com', '1111111111', 'Carlos Tejedor 3170', NULL, NULL, NULL, '2025-11-15 22:32:37', '2025-11-15 22:32:37', 'Carapachay', 'B1605', 89000),
(13, 13, 'ORD-20251117013021-6301', 17800.00, 'pagado', 'tarjeta', '', 'querque', 'querque@gmail.com', '01139109022', 'Carlos Tejedor 3170', NULL, NULL, NULL, '2025-11-17 04:30:21', '2025-11-17 04:30:21', 'Carapachay', 'B1605', 17800),
(14, NULL, 'ORD-20251117033633-4484', 8900.00, 'pagado', 'tarjeta', '', 'Facundo', 'facundo@gmail.com', '01139109022', 'Carlos Tejedor 3170', NULL, NULL, NULL, '2025-11-17 06:36:33', '2025-11-17 06:36:33', 'Carapachay', 'B1605', 8900),
(15, 15, 'ORD-20251117144636-1644', 80100.00, 'pagado', 'tarjeta', '', 'testeo', 'testeo@gmail.com', '01139109022', 'carlos tejedor 3170', NULL, NULL, NULL, '2025-11-17 17:46:36', '2025-11-17 17:46:36', 'Carapachay', 'B1605', 80100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_items`
--

CREATE TABLE `orden_items` (
  `id` bigint UNSIGNED NOT NULL,
  `orden_id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED DEFAULT NULL,
  `nombre_producto` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `cantidad` int UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `orden_items`
--

INSERT INTO `orden_items` (`id`, `orden_id`, `producto_id`, `nombre_producto`, `precio_unitario`, `cantidad`, `subtotal`, `total`, `created_at`) VALUES
(38, 11, 40, 'Botijo Andaluz Fresco', 18600.00, 1, 18600.00, 18600, '2025-11-13 01:28:11'),
(39, 11, 26, 'Plato Postre Luna', 6800.00, 1, 6800.00, 6800, '2025-11-13 01:28:11'),
(40, 11, 38, 'Plato Parrilla Brasas', 13600.00, 1, 13600.00, 13600, '2025-11-13 01:28:11'),
(41, 12, 28, 'Jarrita Leche Granja', 8900.00, 10, 89000.00, 89000, '2025-11-15 22:32:37'),
(42, 13, 28, 'Jarrita Leche Granja', 8900.00, 2, 17800.00, 17800, '2025-11-17 04:30:21'),
(43, 14, 28, 'Jarrita Leche Granja', 8900.00, 1, 8900.00, 8900, '2025-11-17 06:36:33'),
(44, 15, 28, 'Jarrita Leche Granja', 8900.00, 9, 80100.00, 80100, '2025-11-17 17:46:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint UNSIGNED NOT NULL,
  `categoria_id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `precio_original` decimal(10,2) DEFAULT NULL,
  `descuento_porcentaje` decimal(5,2) DEFAULT NULL,
  `es_combo` tinyint(1) NOT NULL DEFAULT '0',
  `stock` int UNSIGNED NOT NULL DEFAULT '0',
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagen_principal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `slug`, `descripcion`, `precio`, `precio_original`, `descuento_porcentaje`, `es_combo`, `stock`, `sku`, `imagen_principal`, `meta_title`, `meta_description`, `activo`, `created_at`, `updated_at`) VALUES
(1, 1, 'Taza Aurora', 'taza-aurora', 'Taza de desayuno (320ml) con esmalte nacarado en tonos pastel. Su forma ergonómica y acabado suave la convierten en perfecta para comenzar el día. Hecha en torno alfarero con arcilla de alta calidad.', 8500.00, NULL, NULL, 0, 0, NULL, '1763357247_9235.webp', 'Taza Aurora - Latido Cerámico', 'Taza de desayuno (320ml) con esmalte nacarado en tonos pastel. Su forma ergonómica y acabado suave la convierten en perfecta para comenzar el día. Hecha en torno alfarero con arcilla de alta calidad.', 1, '2025-11-17 04:53:04', '2025-11-17 05:27:27'),
(2, 2, 'Plato Brisa', 'plato-brisa', 'Plato llano de 26cm con acabado satinado único. Su superficie ligeramente ondulada refleja la luz de manera sutil, creando un juego visual elegante. Ideal para presentaciones gastronómicas especiales.', 9200.00, NULL, NULL, 0, 6, NULL, '1763357140_1193.webp', 'Plato Brisa - Latido Cerámico', 'Plato llano de 26cm con acabado satinado único. Su superficie ligeramente ondulada refleja la luz de manera sutil, creando un juego visual elegante. Ideal para presentaciones gastronómicas especiales.', 1, '2025-11-17 04:53:04', '2025-11-17 17:49:01'),
(3, 3, 'Cuenco Marea', 'cuenco-marea', 'Cuenco mediano (450ml) con gradiente violeta que evoca las profundidades marinas. Su base amplia y bordes curvos lo hacen perfecto para sopas, ensaladas o como elemento decorativo.', 7800.00, NULL, NULL, 0, 0, NULL, '1763357597_8216.webp', 'Cuenco Marea - Latido Cerámico', 'Cuenco mediano (450ml) con gradiente violeta que evoca las profundidades marinas. Su base amplia y bordes curvos lo hacen perfecto para sopas, ensaladas o como elemento decorativo.', 1, '2025-11-17 04:53:04', '2025-11-17 05:33:17'),
(4, 1, 'Taza Amanecer', 'taza-amanecer', 'Taza artesanal (280ml) con borde orgánico irregular y esmalte mate texturado. Cada pieza es única, modelada a mano para crear una experiencia táctil especial al beber.', 8900.00, NULL, NULL, 0, 0, NULL, '1763357254_8933.webp', 'Taza Amanecer - Latido Cerámico', 'Taza artesanal (280ml) con borde orgánico irregular y esmalte mate texturado. Cada pieza es única, modelada a mano para crear una experiencia táctil especial al beber.', 1, '2025-11-17 04:53:04', '2025-11-17 05:27:35'),
(5, 1, 'Taza Cacao', 'taza-cacao', 'Pequeña taza para espresso (90ml) con paredes gruesas que conservan el calor. Su esmalte café intenso y forma compacta la convierten en la compañera ideal para cafés concentrados.', 5200.00, NULL, NULL, 0, 0, NULL, '1763357266_5294.webp', 'Taza Cacao - Latido Cerámico', 'Pequeña taza para espresso (90ml) con paredes gruesas que conservan el calor. Su esmalte café intenso y forma compacta la convierten en la compañera ideal para cafés concentrados.', 1, '2025-11-17 04:53:04', '2025-11-17 05:27:46'),
(6, 4, 'Tetera Bruma', 'tetera-bruma', 'Tetera artesanal de 600ml con filtro cerámico integrado y pico antigoteo. Su diseño redondeado y asa ergonómica facilitan el servido, mientras que su esmalte gris perla aporta elegancia.', 18900.00, NULL, NULL, 0, 0, NULL, '1763357427_1475.webp', 'Tetera Bruma - Latido Cerámico', 'Tetera artesanal de 600ml con filtro cerámico integrado y pico antigoteo. Su diseño redondeado y asa ergonómica facilitan el servido, mientras que su esmalte gris perla aporta elegancia.', 1, '2025-11-17 04:53:04', '2025-11-17 05:30:27'),
(7, 2, 'Plato Llano Arena', 'plato-llano-arena', 'Plato principal de 26cm con textura arena sutil y bordes levemente elevados. Su color terracota natural y acabado mate lo hacen perfecto para presentaciones rústicas elegantes.', 9900.00, NULL, NULL, 0, 4, NULL, '1763357148_9648.webp', 'Plato Llano Arena - Latido Cerámico', 'Plato principal de 26cm con textura arena sutil y bordes levemente elevados. Su color terracota natural y acabado mate lo hacen perfecto para presentaciones rústicas elegantes.', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:26'),
(8, 5, 'Plato Hondo Río', 'plato-hondo-rio', 'Plato hondo de 22cm con interior esmaltado brillante azul cobalto y exterior mate. Ideal para pastas, risottos y sopas. Su profundidad y forma facilitan el uso de cucharas.', 10400.00, NULL, NULL, 0, 4, NULL, '1763357046_5053.webp', 'Plato Hondo Río - Latido Cerámico', 'Plato hondo de 22cm con interior esmaltado brillante azul cobalto y exterior mate. Ideal para pastas, risottos y sopas. Su profundidad y forma facilitan el uso de cucharas.', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:15'),
(9, 3, 'Bowl Raíz', 'bowl-raiz', 'Cuenco alto multiuso (650ml) con textura orgánica que imita corteza de árbol. Perfecto para cereales, ensaladas o como centro de mesa decorativo. Cada pieza tiene vetas únicas.', 8700.00, NULL, NULL, 0, 0, NULL, '1763357586_3399.webp', 'Bowl Raíz - Latido Cerámico', 'Cuenco alto multiuso (650ml) con textura orgánica que imita corteza de árbol. Perfecto para cereales, ensaladas o como centro de mesa decorativo. Cada pieza tiene vetas únicas.', 1, '2025-11-17 04:53:04', '2025-11-17 05:33:06'),
(10, 6, 'Jarrón Senda', 'jarron-senda', 'Jarrón mediano (25cm altura) con cuello estrecho y base amplia. Su esmalte degradé verde bosque lo convierte en una pieza escultural ideal para ramas secas o flores de tallo largo.', 15800.00, NULL, NULL, 0, 8, NULL, '1763357090_5180.webp', 'Jarrón Senda - Latido Cerámico', 'Jarrón mediano (25cm altura) con cuello estrecho y base amplia. Su esmalte degradé verde bosque lo convierte en una pieza escultural ideal para ramas secas o flores de tallo largo.', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:35'),
(11, 7, 'Maceta Lluvia', 'maceta-lluvia', 'Maceta de 16cm con sistema de drenaje y plato incluido. Su esmalte gris tormentoso con gotas en relieve crea un efecto visual único. Ideal para plantas de interior medianas.', 13200.00, NULL, NULL, 0, 8, NULL, '1763357108_7294.webp', 'Maceta Lluvia - Latido Cerámico', 'Maceta de 16cm con sistema de drenaje y plato incluido. Su esmalte gris tormentoso con gotas en relieve crea un efecto visual único. Ideal para plantas de interior medianas.', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:50'),
(12, 8, 'Azucarera Nube', 'azucarera-nube', 'Azucarera con tapa de calce perfecto y acabado blanco nube mate. Su forma redondeada y suave la hace fácil de manipular. Incluye pequeña abertura para cuchara.', 7600.00, NULL, NULL, 0, 0, NULL, '1763357346_9768.webp', 'Azucarera Nube - Latido Cerámico', 'Azucarera con tapa de calce perfecto y acabado blanco nube mate. Su forma redondeada y suave la hace fácil de manipular. Incluye pequeña abertura para cuchara.', 1, '2025-11-17 04:53:04', '2025-11-17 05:29:06'),
(13, 9, 'Portavelas Alba', 'portavelas-alba', 'Portavelas de base ancha (8cm) con cavidad perfecta para velas tea light. Su esmalte traslúcido permite que la luz cree patrones cálidos en las paredes circundantes.', 5400.00, NULL, NULL, 0, 4, NULL, '1763357216_5645.webp', 'Portavelas Alba - Latido Cerámico', 'Portavelas de base ancha (8cm) con cavidad perfecta para velas tea light. Su esmalte traslúcido permite que la luz cree patrones cálidos en las paredes circundantes.', 1, '2025-11-17 04:53:04', '2025-11-17 17:49:20'),
(14, 10, 'Cenicero Viento', 'cenicero-viento', 'Cenicero con canaletas direccionales que evitan que las cenizas vuelen. Su diseño funcional incluye descansos para cigarrillos y base pesada para mayor estabilidad.', 4300.00, NULL, NULL, 0, 0, NULL, '1763357457_5894.webp', 'Cenicero Viento - Latido Cerámico', 'Cenicero con canaletas direccionales que evitan que las cenizas vuelen. Su diseño funcional incluye descansos para cigarrillos y base pesada para mayor estabilidad.', 1, '2025-11-17 04:53:04', '2025-11-17 05:30:57'),
(15, 11, 'Lámpara Base Loto', 'lampara-base-loto', 'Base de lámpara de mesa (30cm altura) inspirada en flor de loto. Su esmalte craquelado dorado crea reflejos únicos. Compatible con pantallas estándar (no incluida).', 23800.00, NULL, NULL, 0, 5, NULL, '1763357101_2906.webp', 'Lámpara Base Loto - Latido Cerámico', 'Base de lámpara de mesa (30cm altura) inspirada en flor de loto. Su esmalte craquelado dorado crea reflejos únicos. Compatible con pantallas estándar (no incluida).', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:42'),
(16, 12, 'Figura Colibrí', 'figura-colibri', 'Figura decorativa de colibrí (12cm) con esmalte iridiscente que cambia de color según la luz. Cada pluma está detallada a mano, creando una pieza escultural única.', 9800.00, NULL, NULL, 0, 0, NULL, '1763357621_1331.webp', 'Figura Colibrí - Latido Cerámico', 'Figura decorativa de colibrí (12cm) con esmalte iridiscente que cambia de color según la luz. Cada pluma está detallada a mano, creando una pieza escultural única.', 1, '2025-11-17 04:53:04', '2025-11-17 05:33:41'),
(17, 13, 'Baldosa Terra', 'baldosa-terra', 'Baldosa artesanal (15x15cm) con textura rústica natural. Cada pieza tiene variaciones únicas de color y relieve. Ideal para revestimientos decorativos o bases para objetos calientes.', 3200.00, NULL, NULL, 0, 0, NULL, '1763357353_6314.webp', 'Baldosa Terra - Latido Cerámico', 'Baldosa artesanal (15x15cm) con textura rústica natural. Cada pieza tiene variaciones únicas de color y relieve. Ideal para revestimientos decorativos o bases para objetos calientes.', 1, '2025-11-17 04:53:04', '2025-11-17 05:29:13'),
(18, 14, 'Vaso Esmaltado Brillo', 'vaso-esmaltado-brillo', 'Vaso alto (350ml) de cerámica con esmalte brillante resistente. Su acabado liso y forma ergonómica lo hacen ideal para bebidas frías. Disponible en varios colores vibrantes.', 6100.00, NULL, NULL, 0, 0, NULL, '1763357296_3014.webp', 'Vaso Esmaltado Brillo - Latido Cerámico', 'Vaso alto (350ml) de cerámica con esmalte brillante resistente. Su acabado liso y forma ergonómica lo hacen ideal para bebidas frías. Disponible en varios colores vibrantes.', 1, '2025-11-17 04:53:04', '2025-11-17 05:28:16'),
(19, 15, 'Cazuela Horno Marejada', 'cazuela-horno-marejada', 'Cazuela para horno (24cm) con tapa hermética y esmalte interno antiadherente natural. Distribuye el calor uniformemente. Ideal para guisos, braseados y cocción lenta.', 17400.00, NULL, NULL, 0, 0, NULL, '1763357449_6850.webp', 'Cazuela Horno Marejada - Latido Cerámico', 'Cazuela para horno (24cm) con tapa hermética y esmalte interno antiadherente natural. Distribuye el calor uniformemente. Ideal para guisos, braseados y cocción lenta.', 1, '2025-11-17 04:53:04', '2025-11-17 05:30:49'),
(20, 16, 'Jabonera Onda', 'jabonera-onda', 'Jabonera con sistema de drenaje integrado que mantiene el jabón seco. Su forma ondulada evita que el jabón se deslice y añade un toque decorativo al baño.', 4200.00, NULL, NULL, 0, 8, NULL, '1763357073_7687.webp', 'Jabonera Onda - Latido Cerámico', 'Jabonera con sistema de drenaje integrado que mantiene el jabón seco. Su forma ondulada evita que el jabón se deslice y añade un toque decorativo al baño.', 1, '2025-11-17 04:53:04', '2025-11-17 06:05:27'),
(21, 17, 'Portacepillos Duo', 'portacepillos-duo', 'Portacepillos de dientes doble con separadores y base antideslizante. Su diseño higiénico permite que los cepillos se sequen adecuadamente sin tocarse entre sí.', 5400.00, NULL, NULL, 0, 5, NULL, '1763357210_5552.webp', 'Portacepillos Duo - Latido Cerámico', 'Portacepillos de dientes doble con separadores y base antideslizante. Su diseño higiénico permite que los cepillos se sequen adecuadamente sin tocarse entre sí.', 1, '2025-11-17 04:53:04', '2025-11-17 17:49:13'),
(22, 18, 'Reloj Pared Arcilla', 'reloj-pared-arcilla', 'Reloj de pared (28cm diámetro) con base de cerámica texturada y mecanismo silencioso. Los números están marcados en relieve y las manecillas son de bronce envejecido.', 22800.00, NULL, NULL, 0, 5, NULL, '1763357229_7753.webp', 'Reloj Pared Arcilla - Latido Cerámico', 'Reloj de pared (28cm diámetro) con base de cerámica texturada y mecanismo silencioso. Los números están marcados en relieve y las manecillas son de bronce envejecido.', 1, '2025-11-17 04:53:04', '2025-11-17 17:49:25'),
(23, 19, 'Plato Pared Sol', 'plato-pared-sol', 'Plato decorativo para pared (30cm) con diseño solar en relieve. Su esmalte dorado mate crea reflejos cálidos. Incluye sistema de colgado invisible en la parte posterior.', 11200.00, NULL, NULL, 0, 7, NULL, '1763357182_5232.webp', 'Plato Pared Sol - Latido Cerámico', 'Plato decorativo para pared (30cm) con diseño solar en relieve. Su esmalte dorado mate crea reflejos cálidos. Incluye sistema de colgado invisible en la parte posterior.', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:39'),
(24, 20, 'Escultura Nido', 'escultura-nido', 'Pieza artística única (35cm altura) que representa un nido abstracto. Cada curva está moldeada a mano, creando una obra escultural contemporánea irrepetible.', 39200.00, NULL, NULL, 0, 0, NULL, '1763357614_1983.webp', 'Escultura Nido - Latido Cerámico', 'Pieza artística única (35cm altura) que representa un nido abstracto. Cada curva está moldeada a mano, creando una obra escultural contemporánea irrepetible.', 1, '2025-11-17 04:53:04', '2025-11-17 05:33:34'),
(25, 1, 'Taza Café Cortado', 'taza-cafe-cortado', 'Taza especial para cortado (180ml) con proporción perfecta entre café y leche. Su interior blanco realza el color del café, mientras que el exterior tiene esmalte mate coral.', 7200.00, NULL, NULL, 0, 0, NULL, '1763357275_4562.webp', 'Taza Café Cortado - Latido Cerámico', 'Taza especial para cortado (180ml) con proporción perfecta entre café y leche. Su interior blanco realza el color del café, mientras que el exterior tiene esmalte mate coral.', 1, '2025-11-17 04:53:04', '2025-11-17 05:27:55'),
(26, 2, 'Plato Postre Luna', 'plato-postre-luna', 'Plato para postre (19cm) con borde ondulado que imita fases lunares. Su esmalte perlado cambia sutilmente de tono según la luz, creando presentaciones mágicas.', 6800.00, NULL, NULL, 0, 7, NULL, '1763357172_6486.webp', 'Plato Postre Luna - Latido Cerámico', 'Plato para postre (19cm) con borde ondulado que imita fases lunares. Su esmalte perlado cambia sutilmente de tono según la luz, creando presentaciones mágicas.', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:46'),
(27, 3, 'Cuenco Ramen Tokio', 'cuenco-ramen-tokio', 'Cuenco profundo para ramen (800ml) con bordes altos y base ancha. Su esmalte negro mate con interior blanco facilita la presentación de caldos. Incluye descanso para palillos.', 12400.00, NULL, NULL, 0, 0, NULL, '1763357604_5813.webp', 'Cuenco Ramen Tokio - Latido Cerámico', 'Cuenco profundo para ramen (800ml) con bordes altos y base ancha. Su esmalte negro mate con interior blanco facilita la presentación de caldos. Incluye descanso para palillos.', 1, '2025-11-17 04:53:04', '2025-11-17 05:33:24'),
(28, 21, 'Jarrita Leche Granja', 'jarrita-leche-granja', 'Jarrita para leche (250ml) con pico vertedor antigoteo y asa cómoda. Su esmalte crema con motivos florales pintados a mano evoca la vida rural tradicional.', 8900.00, NULL, NULL, 0, 4, NULL, '1763357082_7841.webp', 'Jarrita Leche Granja - Latido Cerámico', 'Jarrita para leche (250ml) con pico vertedor antigoteo y asa cómoda. Su esmalte crema con motivos florales pintados a mano evoca la vida rural tradicional.', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:22'),
(29, 22, 'Mortero Especias Andes', 'mortero-especias-andes', 'Mortero con pilón (12cm diámetro) de cerámica extra resistente. Su superficie rugosa interior facilita la molienda de especias. Base antideslizante para mayor estabilidad.', 14500.00, NULL, NULL, 0, 5, NULL, '1763357115_3817.webp', 'Mortero Especias Andes - Latido Cerámico', 'Mortero con pilón (12cm diámetro) de cerámica extra resistente. Su superficie rugosa interior facilita la molienda de especias. Base antideslizante para mayor estabilidad.', 1, '2025-11-17 04:53:04', '2025-11-17 17:49:06'),
(30, 23, 'Incensario Zen', 'incensario-zen', 'Incensario ceremonial con cavidades para varitas e incienso en cono. Su diseño minimalista y esmalte verde bambú crean un ambiente de serenidad y concentración.', 9600.00, NULL, NULL, 0, 8, NULL, '1763357200_1940.webp', 'Incensario Zen - Latido Cerámico', 'Incensario ceremonial con cavidades para varitas e incienso en cono. Su diseño minimalista y esmalte verde bambú crean un ambiente de serenidad y concentración.', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:54'),
(31, 24, 'Fuente Oval Cosecha', 'fuente-oval-cosecha', 'Fuente ovalada (35cm) perfecta para asados y presentaciones familiares. Su esmalte miel con bordes rústicos la convierte en pieza central de cualquier mesa festiva.', 16800.00, NULL, NULL, 0, 0, NULL, '1763357635_1214.webp', 'Fuente Oval Cosecha - Latido Cerámico', 'Fuente ovalada (35cm) perfecta para asados y presentaciones familiares. Su esmalte miel con bordes rústicos la convierte en pieza central de cualquier mesa festiva.', 1, '2025-11-17 04:53:04', '2025-11-17 05:33:55'),
(32, 25, 'Salero Pimienta Gemelos', 'salero-pimienta-gemelos', 'Set de salero y pimentero con formas complementarias que encajan perfectamente. Sus orificios están diseñados específicamente para cada condimento. Base magnética.', 6200.00, NULL, NULL, 0, 0, NULL, '1763357575_9538.webp', 'Salero Pimienta Gemelos - Latido Cerámico', 'Set de salero y pimentero con formas complementarias que encajan perfectamente. Sus orificios están diseñados específicamente para cada condimento. Base magnética.', 1, '2025-11-17 04:53:04', '2025-11-17 05:32:55'),
(33, 26, 'Cafetera Filtro Artesana', 'cafetera-filtro-artesana', 'Cafetera de filtro (600ml) estilo pour-over con cuello de cisne para control perfecto del vertido. Su cuerpo cerámico mantiene la temperatura y no altera el sabor.', 24800.00, NULL, NULL, 0, 0, NULL, '1763357443_1860.webp', 'Cafetera Filtro Artesana - Latido Cerámico', 'Cafetera de filtro (600ml) estilo pour-over con cuello de cisne para control perfecto del vertido. Su cuerpo cerámico mantiene la temperatura y no altera el sabor.', 1, '2025-11-17 04:53:04', '2025-11-17 05:30:43'),
(34, 27, 'Huevera Nido Seis', 'huevera-nido-seis', 'Huevera para 6 huevos con diseño de nido natural. Cada cavidad está moldeada individualmente para sujetar huevos de diferentes tamaños. Ideal para desayunos especiales.', 8300.00, NULL, NULL, 0, 0, NULL, '1763357643_8480.webp', 'Huevera Nido Seis - Latido Cerámico', 'Huevera para 6 huevos con diseño de nido natural. Cada cavidad está moldeada individualmente para sujetar huevos de diferentes tamaños. Ideal para desayunos especiales.', 1, '2025-11-17 04:53:04', '2025-11-17 05:34:03'),
(35, 28, 'Panera Trigo Dorado', 'panera-trigo-dorado', 'Panera rectangular (30cm) con tapa ventilada que mantiene el pan fresco y crujiente. Su esmalte dorado trigo y diseño de espigas la convierten en pieza decorativa.', 19200.00, NULL, NULL, 0, 7, NULL, '1763357125_9273.webp', 'Panera Trigo Dorado - Latido Cerámico', 'Panera rectangular (30cm) con tapa ventilada que mantiene el pan fresco y crujiente. Su esmalte dorado trigo y diseño de espigas la convierten en pieza decorativa.', 1, '2025-11-17 04:53:04', '2025-11-17 17:48:58'),
(36, 29, 'Quemador Aceites Aromas', 'quemador-aceites-aromas', 'Quemador de aceites esenciales con vela tea light. Su diseño de pétalos permite que los aromas se difundan uniformemente. Incluye pequeño cuenco superior para aceites.', 7800.00, NULL, NULL, 0, 0, NULL, '1763357534_7777.webp', 'Quemador Aceites Aromas - Latido Cerámico', 'Quemador de aceites esenciales con vela tea light. Su diseño de pétalos permite que los aromas se difundan uniformemente. Incluye pequeño cuenco superior para aceites.', 1, '2025-11-17 04:53:04', '2025-11-17 05:32:14'),
(37, 30, 'Recipiente Miel Abeja', 'recipiente-miel-abeja', 'Recipiente especial para miel con tapa de rosca y cucharita de madera incluida. Su forma hexagonal y esmalte ámbar lo convierten en homenaje a las abejas.', 10200.00, NULL, NULL, 0, 5, NULL, '1763357223_4894.webp', 'Recipiente Miel Abeja - Latido Cerámico', 'Recipiente especial para miel con tapa de rosca y cucharita de madera incluida. Su forma hexagonal y esmalte ámbar lo convierten en homenaje a las abejas.', 1, '2025-11-17 04:53:04', '2025-11-17 17:49:16'),
(38, 31, 'Plato Parrilla Brasas', 'plato-parrilla-brasas', 'Plato especial para carnes a la parrilla (28cm) con canaletas para jugos y superficie estriada que imita las marcas de la parrilla. Resistente a altas temperaturas.', 13600.00, NULL, NULL, 0, 5, NULL, '1763357192_3495.webp', 'Plato Parrilla Brasas - Latido Cerámico', 'Plato especial para carnes a la parrilla (28cm) con canaletas para jugos y superficie estriada que imita las marcas de la parrilla. Resistente a altas temperaturas.', 1, '2025-11-17 04:53:04', '2025-11-17 17:49:10'),
(39, 32, 'Sopera Familiar Hogar', 'sopera-familiar-hogar', 'Sopera grande (2 litros) con tapa y cucharón incluido. Su diseño clásico con asas laterales facilita el servido en reuniones familiares. Mantiene la temperatura.', 22400.00, NULL, NULL, 0, 0, NULL, '1763357237_4163.webp', 'Sopera Familiar Hogar - Latido Cerámico', 'Sopera grande (2 litros) con tapa y cucharón incluido. Su diseño clásico con asas laterales facilita el servido en reuniones familiares. Mantiene la temperatura.', 1, '2025-11-17 04:53:04', '2025-11-17 05:27:17'),
(40, 33, 'Botijo Andaluz Fresco', 'botijo-andaluz-fresco', 'Botijo tradicional (1.5 litros) que enfría el agua naturalmente por evaporación. Su forma clásica y esmalte blanco lo convierten en pieza funcional y decorativa.', 18600.00, NULL, NULL, 0, 0, NULL, '1763357391_8275.webp', 'Botijo Andaluz Fresco - Latido Cerámico', 'Botijo tradicional (1.5 litros) que enfría el agua naturalmente por evaporación. Su forma clásica y esmalte blanco lo convierten en pieza funcional y decorativa.', 1, '2025-11-17 04:53:04', '2025-11-17 05:29:51'),
(101, 34, 'COMBO Desayuno Perfecto', 'combo-desayuno-perfecto', 'Set completo para desayunos especiales: Taza Aurora + Plato Postre Luna + Jarrita Leche Granja + Huevera Nido Seis. Todo lo necesario para comenzar el día con estilo artesanal.', 28500.00, 31300.00, 10.00, 1, 0, NULL, '1763357468_3472.webp', 'COMBO Desayuno Perfecto - Latido Cerámico', 'Set completo para desayunos especiales: Taza Aurora + Plato Postre Luna + Jarrita Leche Granja + Huevera Nido Seis. Todo lo necesario para comenzar el día con estilo artesanal.', 1, '2025-11-17 04:53:04', '2025-11-17 05:31:08'),
(102, 34, 'COMBO Mesa Familiar', 'combo-mesa-familiar', 'Set para 4 personas: 4 Platos Llanos Arena + 4 Platos Hondos Río + 4 Cuencos Marea + Fuente Oval Cosecha. Perfecto para cenas familiares con estilo rústico elegante.', 89600.00, 99600.00, 10.00, 1, 0, NULL, '1763357480_4343.webp', 'COMBO Mesa Familiar - Latido Cerámico', 'Set para 4 personas: 4 Platos Llanos Arena + 4 Platos Hondos Río + 4 Cuencos Marea + Fuente Oval Cosecha. Perfecto para cenas familiares con estilo rústico elegante.', 1, '2025-11-17 04:53:04', '2025-11-17 05:31:21'),
(103, 34, 'COMBO Café Gourmet', 'combo-cafe-gourmet', 'Para amantes del café: Cafetera Filtro Artesana + 2 Tazas Café Cortado + 1 Taza Cacao + Azucarera Nube. Experiencia de café artesanal completa.', 45200.00, 50700.00, 11.00, 1, 0, NULL, '1763357495_5126.webp', 'COMBO Café Gourmet - Latido Cerámico', 'Para amantes del café: Cafetera Filtro Artesana + 2 Tazas Café Cortado + 1 Taza Cacao + Azucarera Nube. Experiencia de café artesanal completa.', 1, '2025-11-17 04:53:04', '2025-11-17 05:31:35'),
(104, 34, 'COMBO Té Ceremonial', 'combo-te-ceremonial', 'Set de té completo: Tetera Bruma + 4 Tazas Amanecer + Azucarera Domo + bandeja cerámica especial. Ideal para reuniones íntimas y ceremonias de té.', 52800.00, 58400.00, 10.00, 1, 0, NULL, '1763357501_6290.webp', 'COMBO Té Ceremonial - Latido Cerámico', 'Set de té completo: Tetera Bruma + 4 Tazas Amanecer + Azucarera Domo + bandeja cerámica especial. Ideal para reuniones íntimas y ceremonias de té.', 1, '2025-11-17 04:53:04', '2025-11-17 05:31:41'),
(105, 34, 'COMBO Jardín Interior', 'combo-jardin-interior', 'Set de jardinería: 3 Macetas Lluvia diferentes tamaños + 2 Jarrones Senda + Incensario Zen. Perfecto para crear un rincón verde y aromático en casa.', 48500.00, 53800.00, 10.00, 1, 0, NULL, '1763357511_2948.webp', 'COMBO Jardín Interior - Latido Cerámico', 'Set de jardinería: 3 Macetas Lluvia diferentes tamaños + 2 Jarrones Senda + Incensario Zen. Perfecto para crear un rincón verde y aromático en casa.', 1, '2025-11-17 04:53:04', '2025-11-17 05:31:52'),
(106, 34, 'COMBO Baño Spa', 'combo-bano-spa', 'Set completo para baño: Jabonera Onda + Portacepillos Duo + 3 Portavelas Alba + Quemador Aceites Aromas. Transforma tu baño en un spa relajante.', 24600.00, 27600.00, 11.00, 1, 0, NULL, '1763357519_5394.webp', 'COMBO Baño Spa - Latido Cerámico', 'Set completo para baño: Jabonera Onda + Portacepillos Duo + 3 Portavelas Alba + Quemador Aceites Aromas. Transforma tu baño en un spa relajante.', 1, '2025-11-17 04:53:04', '2025-11-17 05:31:59'),
(107, 34, 'COMBO Cocina Artesana', 'combo-cocina-artesana', 'Set para cocinar: Cazuela Horno Marejada + Mortero Especias Andes + Sopera Familiar + Salero Pimienta Gemelos. Herramientas tradicionales para cocina moderna.', 53200.00, 59000.00, 10.00, 1, 0, NULL, '1763357338_5552.webp', 'COMBO Cocina Artesana - Latido Cerámico', 'Set para cocinar: Cazuela Horno Marejada + Mortero Especias Andes + Sopera Familiar + Salero Pimienta Gemelos. Herramientas tradicionales para cocina moderna.', 1, '2025-11-17 04:53:04', '2025-11-17 05:28:58'),
(108, 34, 'COMBO Decoración Zen', 'combo-decoracion-zen', 'Set decorativo: Escultura Nido + Lámpara Base Loto + Figura Colibrí + 5 Portavelas Alba + Incensario Zen. Crea un ambiente de paz y armonía.', 78600.00, 87200.00, 10.00, 1, 0, NULL, '1763357542_6838.webp', 'COMBO Decoración Zen - Latido Cerámico', 'Set decorativo: Escultura Nido + Lámpara Base Loto + Figura Colibrí + 5 Portavelas Alba + Incensario Zen. Crea un ambiente de paz y armonía.', 1, '2025-11-17 04:53:04', '2025-11-17 05:32:23'),
(109, 34, 'COMBO Almacenamiento', 'combo-almacenamiento', 'Set organizador: Panera Trigo Dorado + Recipiente Miel Abeja + Botijo Andaluz + 2 Azucareras diferentes + 3 recipientes pequeños variados.', 76800.00, 85200.00, 10.00, 1, 0, NULL, '1763357557_8647.webp', 'COMBO Almacenamiento - Latido Cerámico', 'Set organizador: Panera Trigo Dorado + Recipiente Miel Abeja + Botijo Andaluz + 2 Azucareras diferentes + 3 recipientes pequeños variados.', 1, '2025-11-17 04:53:04', '2025-11-17 05:32:37'),
(110, 34, 'COMBO Parrilla Premium', 'combo-parrilla-premium', 'Set para asados: 4 Platos Parrilla Brasas + Fuente Oval Cosecha + Mortero Especias + 4 Vasos Esmaltados. Todo para una experiencia de parrilla gourmet.', 89200.00, 98800.00, 10.00, 1, 0, NULL, '1763357568_2115.webp', 'COMBO Parrilla Premium - Latido Cerámico', 'Set para asados: 4 Platos Parrilla Brasas + Fuente Oval Cosecha + Mortero Especias + 4 Vasos Esmaltados. Todo para una experiencia de parrilla gourmet.', 1, '2025-11-17 04:53:04', '2025-11-17 05:32:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_imagenes`
--

CREATE TABLE `producto_imagenes` (
  `id` bigint UNSIGNED NOT NULL,
  `producto_id` bigint UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` text COLLATE utf8mb4_unicode_ci,
  `orden` int UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id` bigint UNSIGNED NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nav` tinyint(1) NOT NULL DEFAULT '0',
  `orden` decimal(5,2) NOT NULL DEFAULT '0.00',
  `meta_title` varchar(160) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `view_file` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id`, `slug`, `nombre`, `titulo`, `nav`, `orden`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `activo`, `view_file`) VALUES
(1, 'inicio', 'Inicio', 'Bienvenido a Latido Cerámico', 1, 1.00, 'Bienvenido a Latido Cerámico - Latido Cerámico', 'Bienvenido a Latido Cerámico', '2025-11-17 04:53:04', '2025-11-17 04:53:04', 1, NULL),
(2, 'clases', 'Clases', 'Clases para niñas y niños (4–13 años)', 1, 2.00, 'Clases para niñas y niños (4–13 años) - Latido Cerámico', 'Clases para niñas y niños (4–13 años)', '2025-11-17 04:53:04', '2025-11-17 04:53:04', 1, NULL),
(3, 'productos', 'Productos', 'Nuestros Productos', 1, 3.00, 'Nuestros Productos - Latido Cerámico', 'Nuestros Productos', '2025-11-17 04:53:04', '2025-11-17 04:53:04', 1, NULL),
(4, 'combos', 'Combos', 'Combos y Promociones', 0, 3.50, 'Combos y Promociones - Latido Cerámico', 'Combos y Promociones', '2025-11-17 04:53:04', '2025-11-17 04:53:04', 1, NULL),
(5, 'contacto', 'Contacto', 'Contacto', 1, 4.00, 'Contacto - Latido Cerámico', 'Contacto', '2025-11-17 04:53:04', '2025-11-17 04:53:04', 1, NULL),
(6, 'favoritos', 'Favoritos', 'Tus favoritos', 1, 5.00, 'Tus favoritos - Latido Cerámico', 'Tus favoritos', '2025-11-17 04:53:04', '2025-11-17 04:53:04', 1, NULL),
(7, 'detalle-producto', 'Producto', 'Detalle de producto', 0, 99.00, 'Detalle de producto - Latido Cerámico', 'Detalle de producto', '2025-11-17 04:53:04', '2025-11-17 04:53:04', 1, NULL),
(8, 'checkout', 'Finalizar compra', 'Finalizar compra', 0, 98.00, 'Finalizar compra - Latido Cerámico', 'Finalizar compra', '2025-11-17 04:53:04', '2025-11-17 04:53:04', 1, NULL),
(9, 'confirmacion', 'Confirmación', 'Confirmación de compra', 0, 97.00, 'Confirmación de compra - Latido Cerámico', 'Confirmación de compra', '2025-11-17 04:53:04', '2025-11-17 04:53:04', 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `es_admin` tinyint(1) NOT NULL DEFAULT '0',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `password_hash`, `telefono`, `es_admin`, `activo`, `created_at`, `updated_at`) VALUES
(13, 'querque', 'alvarez', 'querque@gmail.com', '$2y$10$aGB47NWr1mUitzKW66QF8OcE4jzPadkzvB5mWdHOCYl/zbe/dqb3e', '1139109022', 0, 1, '2025-11-17 04:27:46', '2025-11-17 04:28:12'),
(15, 'testeo', 'retestep', 'testeo@gmail.com', '$2y$10$RZCL/RmiQLDDhtNVKXfOie7aB9nF2gWZQPE3pT31gjCPt0ib5ZGVS', '1139109022', 0, 1, '2025-11-17 17:45:31', '2025-11-17 17:45:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_admin`
--

CREATE TABLE `usuarios_admin` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_login` datetime DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios_admin`
--

INSERT INTO `usuarios_admin` (`id`, `nombre`, `email`, `password_hash`, `created_at`, `ultimo_login`, `apellido`, `telefono`) VALUES
(1, 'Administrador', 'admin@latido.local', '$2y$10$8KezZqAaMgp2b5j3pUyAwOE1iBVQNqCRuS65d.URo9ffDhs3xBxLC', '2025-11-10 22:19:54', '2025-11-17 17:48:00', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_secciones`
--
ALTER TABLE `admin_secciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `activo` (`activo`),
  ADD KEY `en_menu` (`en_menu`);

--
-- Indices de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_carritos_session` (`session_id`),
  ADD KEY `fk_carritos_usuario` (`usuario_id`);

--
-- Indices de la tabla `carrito_items`
--
ALTER TABLE `carrito_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_carrito_producto` (`carrito_id`,`producto_id`),
  ADD KEY `fk_carrito_items_producto` (`producto_id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_categorias_slug` (`slug`),
  ADD UNIQUE KEY `uq_categorias_nombre` (`nombre`);

--
-- Indices de la tabla `combo_items`
--
ALTER TABLE `combo_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_combo_producto` (`combo_producto_id`,`producto_id`),
  ADD KEY `fk_combo_items_producto` (`producto_id`);

--
-- Indices de la tabla `galeria`
--
ALTER TABLE `galeria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `visible` (`visible`),
  ADD KEY `orden` (`orden`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_ordenes_codigo` (`codigo`),
  ADD KEY `idx_ordenes_usuario` (`usuario_id`);

--
-- Indices de la tabla `orden_items`
--
ALTER TABLE `orden_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orden_items_producto` (`producto_id`),
  ADD KEY `idx_orden_items_orden` (`orden_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_productos_slug` (`slug`),
  ADD KEY `idx_productos_categoria` (`categoria_id`),
  ADD KEY `idx_productos_precio` (`precio`),
  ADD KEY `idx_productos_es_combo` (`es_combo`);

--
-- Indices de la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_producto_imagenes_producto` (`producto_id`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_secciones_slug` (`slug`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuarios_email` (`email`);

--
-- Indices de la tabla `usuarios_admin`
--
ALTER TABLE `usuarios_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_secciones`
--
ALTER TABLE `admin_secciones`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19795;

--
-- AUTO_INCREMENT de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `carrito_items`
--
ALTER TABLE `carrito_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=568;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `combo_items`
--
ALTER TABLE `combo_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `galeria`
--
ALTER TABLE `galeria`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `orden_items`
--
ALTER TABLE `orden_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT de la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `usuarios_admin`
--
ALTER TABLE `usuarios_admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `fk_carritos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `carrito_items`
--
ALTER TABLE `carrito_items`
  ADD CONSTRAINT `fk_carrito_items_carrito` FOREIGN KEY (`carrito_id`) REFERENCES `carritos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_carrito_items_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `combo_items`
--
ALTER TABLE `combo_items`
  ADD CONSTRAINT `fk_combo_items_combo` FOREIGN KEY (`combo_producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_combo_items_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD CONSTRAINT `fk_ordenes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `orden_items`
--
ALTER TABLE `orden_items`
  ADD CONSTRAINT `fk_orden_items_orden` FOREIGN KEY (`orden_id`) REFERENCES `ordenes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orden_items_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_imagenes`
--
ALTER TABLE `producto_imagenes`
  ADD CONSTRAINT `fk_producto_imagenes_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
