-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2025 a las 19:25:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rdm2023`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`, `visible`) VALUES
(4, 'PARLANTES', 'Parlantes portátiles y de outlet ', '2025-09-02 17:36:27', '2025-09-02 17:36:27', 1),
(5, 'TELEVISORES', 'Pantallas LED, OLED, QLED y Smart TV de distintas marcas y tamaños.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(6, 'AUDIO', 'Parlantes, auriculares, barras de sonido, home theaters y equipos de audio.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(7, 'COMPUTADORAS', 'PC de escritorio, notebooks, all-in-one, monitores y accesorios.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(8, 'CELULARES', 'Teléfonos móviles, smartphones, accesorios y cargadores.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(9, 'TABLETS', 'Tablets, convertibles y accesorios compatibles.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(10, 'CONSOLAS Y VIDEOJUEGOS', 'Consolas, videojuegos físicos y digitales, mandos y accesorios.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(11, 'COMPONENTES DE PC', 'Placas madre, procesadores, tarjetas gráficas, memorias RAM, discos SSD y HDD.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(12, 'IMPRESORAS Y MULTIFUNCIONALES', 'Impresoras a tinta, láser, multifunción, cartuchos y toners.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(13, 'ACCESORIOS', 'mausepad, Fundas, cables, teclados, mouses, cargadores, soportes y gadgets.', '2025-09-02 17:42:15', '2025-09-04 23:16:06', 1),
(14, 'SMART HOME', 'Domótica, cámaras de seguridad, asistentes virtuales y dispositivos IoT.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(15, 'ELECTROPEQUEÑOS', 'Pequeños electrodomésticos relacionados al hogar y oficina.', '2025-09-02 17:42:15', '2025-09-02 17:42:15', 1),
(16, 'INMUEBLES', 'Terrenos disponibles para la compra', '2025-09-04 23:07:28', '2025-09-04 23:16:35', 1),
(17, 'PAPELERIA', 'Papeles, Fotocopias, Caratulas', '2025-09-04 23:17:08', '2025-09-04 23:17:08', 1),
(19, 'TINTA DE IMPRESORA', 'MANEJO DE MULTIPLES MARCAS DE IMPRESORA', '2025-09-11 21:59:31', '2025-09-11 21:59:31', 1),
(20, 'PAPELERIA', 'PAPALES, CATULINAS, HOJAS', '2025-09-12 00:16:38', '2025-09-12 00:16:38', 1),
(21, 'MOUSEPAD', 'alfombrilla de escritorio', '2025-09-12 00:24:42', '2025-09-11 22:15:11', 1),
(22, 'MOUSE', 'mauses gamer y comunes', '2025-09-12 00:27:32', '2025-09-11 22:15:00', 1),
(23, 'HUB USB', 'hubs usbs', '2025-09-12 00:30:27', '2025-09-12 00:30:27', 1),
(24, 'EXTENSOR DE RED', 'EXTENSORES DE RED WIRELESS', '2025-09-12 00:34:20', '2025-09-12 00:34:20', 1),
(25, 'FUENTE DE ALIMENTACION', 'fuentes de poder', '2025-09-12 00:41:55', '2025-09-12 00:41:55', 1),
(26, 'BATERIAS', 'pilas, baterias', '2025-09-12 00:44:49', '2025-09-12 00:44:49', 1),
(27, 'FUNDAS PARA NOTEBOOK', NULL, '2025-09-12 01:30:42', '2025-09-12 01:30:42', 1),
(28, 'AURICULARES', NULL, '2025-09-12 01:36:11', '2025-09-12 01:36:11', 1),
(29, 'JOYSTICK', NULL, '2025-09-12 01:55:45', '2025-09-12 01:55:45', 1),
(30, 'TECLADOS', NULL, '2025-09-12 05:48:23', '2025-09-12 05:48:23', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `RazonSocial` varchar(100) DEFAULT NULL,
  `NombreCompleto` varchar(100) DEFAULT NULL,
  `cuit_dni` varchar(100) NOT NULL,
  `Domicilio` varchar(100) DEFAULT NULL,
  `Localidad` varchar(100) DEFAULT NULL,
  `Detalle` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `Telefono` varchar(50) DEFAULT NULL,
  `TipoCliente` enum('Persona','Empresa') NOT NULL DEFAULT 'Persona',
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `RazonSocial`, `NombreCompleto`, `cuit_dni`, `Domicilio`, `Localidad`, `Detalle`, `Email`, `Telefono`, `TipoCliente`, `visible`, `created_at`, `updated_at`) VALUES
(1, 'DL GARBE COMPUTACION', 'Daniel Garbe', '20-32830774-6', 'Avda. Cordoba 2011', 'CORDOBA CAPITAL', 'I.V.A RESPONSABLE INSCRIPTO', 'ventas@glgarbe.com.ar', '3496 501175', 'Empresa', 1, '1992-01-01 03:00:00', NULL),
(2, 'TecnoSolutions S.A.', 'Tiago Raminelli', '30-71234567-8', 'Avenida de Mayo 1234', 'Buenos Aires', 'I.V.A RESPONSABLE INSCRIPTO', 'contacto@tecnosolutions.com.ar', '+5493408402912', 'Empresa', 1, '2022-03-15 13:00:00', '2025-09-15 02:42:57'),
(3, 'Construcciones del Sur SRL', 'Ana Rodriguez', '30-78901234-5', 'Calle 50 #750', 'La Plata', 'I.V.A RESPONSABLE INSCRIPTO', 'admin@consudelsur.com', '+54 221 444-5555', 'Empresa', 1, '2021-08-20 12:30:00', '2025-09-08 22:11:33'),
(4, 'Logística Rápida SRL', 'Carlos Gomez', '30-75612398-7', 'Ruta Nacional 9 km 25', 'Zárate', 'I.V.A RESPONSABLE INSCRIPTO', 'carlos.gomez@logistica.com', '+54 3487 123456', 'Empresa', 1, '2023-01-10 17:15:00', NULL),
(5, 'Tienda de Libros S.A.', 'Laura Fernandez', '30-65432198-7', 'Avenida Corrientes 1500', 'Buenos Aires', 'I.V.A RESPONSABLE INSCRIPTO', 'laura@tiendadelibros.com.ar', '+54 11 4987-6543', 'Empresa', 1, '2020-05-01 14:00:00', NULL),
(6, NULL, 'Martín Salgado', '20-33445566-7', 'General Paz 850', 'Córdoba', 'MONOTRIBUTISTA', 'martin.salgado@gmail.com', '+54 351 987-6543', 'Persona', 1, '2024-01-25 19:20:00', NULL),
(7, NULL, 'Sofía Castro', '27-40556677-8', 'San Martín 123', 'Rosario', 'Consumidor Final', 'sofia.castro@hotmail.com', '+54 341 1122334', 'Persona', 1, '2023-11-05 11:45:00', NULL),
(8, NULL, 'Diego Perez', '20-25678912-3', 'Rivadavia 789', 'San Miguel de Tucumán', 'MONOTRIBUTISTA', 'diego.perez@consultor.com', '+54 381 5566778', 'Persona', 1, '2024-02-29 16:00:00', '2025-09-08 21:59:55'),
(9, NULL, 'Valeria Mendez', '27-38901234-5', 'Esmeralda 456', 'CABA', 'Consumidor Final', 'valeria.mendez@outlook.com', '+54 11 7890-1234', 'Persona', 1, '2023-07-18 13:20:00', NULL),
(10, NULL, 'Jorge Navarro', '20-30123456-7', 'Avenida San Juan 100', 'Mendoza', 'MONOTRIBUTISTA', 'jorge.navarro@empresa.com', '+54 261 456-7890', 'Persona', 1, '2024-04-12 18:50:00', '2025-09-08 22:00:00'),
(11, 'Edenor S.A', 'Empresa Distribuidora y Comercializadora Norte Sociedad Anónima', 'US29244A1025', 'Avenida del Libertador 6363', 'Buenos Aires, Ciudad Autónoma de Buenos Aires', 'I.V.A RESPONSABLE INSCRIPTO', 'consultora@edenor.com', '+54 11 3444-5555', 'Empresa', 1, '2025-09-08 22:21:03', '2025-09-08 22:23:23'),
(12, 'Rigo Horacio', 'Pascual Rigo', '13921212', 'Cochabamba 1230', 'San Cristobal', 'MONOTRIBUTISTA', 'rigohoracio@gmail.com', '3408-402912', 'Persona', 1, '2025-09-15 01:50:43', '2025-09-15 01:50:43'),
(13, 'Bazar Estrellita', 'Quiroz Gustavo', '1242-232242-2322', 'Caseros 1030', 'San Cristobal', 'I.V.A RESPONSABLE INSCRIPTO', 'BazarEstrellita@gmail.com', '4308-434566', 'Empresa', 1, '2025-09-15 02:01:24', '2025-09-15 02:01:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` int(11) NOT NULL,
  `servicio_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(8,2) NOT NULL,
  `descuento` decimal(8,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `servicio_id`, `cantidad`, `precio_unitario`, `descuento`, `subtotal`, `visible`, `created_at`, `updated_at`) VALUES
(60, 1, 44, NULL, 3, 27580.00, 0.00, 82740.00, 1, '2025-09-13 04:19:22', '2025-09-13 04:56:21'),
(61, 1, 59, NULL, 3, 10000.00, 0.00, 30000.00, 1, '2025-09-13 04:19:22', '2025-09-13 04:56:21'),
(62, 1, 44, NULL, 3, 27580.00, 0.00, 82740.00, 1, '2025-09-13 04:19:22', '2025-09-13 04:56:21'),
(63, 1, 41, NULL, 3, 6000.00, 0.00, 18000.00, 1, '2025-09-13 04:19:22', '2025-09-13 04:56:21'),
(68, 1, 41, NULL, 3, 6000.00, 0.00, 18000.00, 1, '2025-09-13 04:22:18', '2025-09-13 04:56:21'),
(69, 1, 67, NULL, 3, 6200.00, 0.00, 18600.00, 1, '2025-09-13 04:22:18', '2025-09-13 04:56:21'),
(71, 1, 41, NULL, 3, 6000.00, 0.00, 18000.00, 1, '2025-09-13 04:23:10', '2025-09-13 04:56:21'),
(74, 1, 58, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-13 04:23:10', '2025-09-13 04:56:21'),
(113, 8, 99, NULL, 1, 31000.00, 0.00, 31000.00, 1, '2025-09-13 04:33:44', '2025-09-15 02:32:37'),
(114, 8, 54, NULL, 2, 28800.00, 0.00, 57600.00, 1, '2025-09-13 04:37:35', '2025-09-15 02:32:37'),
(115, 8, 58, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-13 04:37:51', '2025-09-15 02:32:37'),
(116, 8, 65, NULL, 1, 4600.00, 0.00, 4600.00, 1, '2025-09-13 04:38:11', '2025-09-15 02:32:37'),
(117, 8, 45, NULL, 3, 10000.00, 0.00, 30000.00, 1, '2025-09-13 04:38:32', '2025-09-15 02:32:37'),
(120, 8, 50, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-13 04:38:32', '2025-09-15 02:32:37'),
(121, 8, 72, NULL, 1, 15000.00, 0.00, 15000.00, 1, '2025-09-13 04:38:32', '2025-09-15 02:32:37'),
(122, 7, 41, NULL, 3, 6000.00, 0.00, 18000.00, 1, NULL, '2025-09-13 05:20:34'),
(123, 8, 108, NULL, 1, 33600.00, 0.00, 33600.00, 1, '2025-09-13 05:24:50', '2025-09-15 02:32:37'),
(124, 9, 47, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-15 01:56:23', '2025-09-15 02:32:23'),
(125, 9, 49, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-15 01:56:23', '2025-09-15 02:32:23'),
(126, 9, 97, NULL, 1, 36500.00, 0.00, 36500.00, 1, '2025-09-15 01:57:49', '2025-09-15 02:32:23'),
(127, 10, 108, NULL, 1, 35000.00, 0.00, 35000.00, 1, '2025-09-15 02:06:49', '2025-09-15 02:32:16'),
(128, 10, 73, NULL, 1, 25000.00, 0.00, 25000.00, 1, '2025-09-15 02:06:49', '2025-09-15 02:32:16'),
(129, 10, 90, NULL, 1, 12000.00, 0.00, 12000.00, 1, '2025-09-15 02:06:49', '2025-09-15 02:32:16'),
(130, 11, 43, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-15 02:24:25', '2025-09-15 02:32:07'),
(131, 11, 64, NULL, 1, 27580.00, 0.00, 27580.00, 1, '2025-09-15 02:24:25', '2025-09-15 02:32:07'),
(132, 12, 95, NULL, 1, 19900.00, 0.00, 19900.00, 1, '2025-09-15 02:26:52', '2025-09-15 02:27:46'),
(133, 14, 63, NULL, 1, 34000.00, 3400.00, 30600.00, 1, '2025-09-15 02:36:28', '2025-09-15 02:36:28'),
(134, 3, 42, NULL, 1, 28800.00, 0.00, 28800.00, 1, '2025-09-16 05:48:36', '2025-09-16 05:48:36'),
(135, 3, 50, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-16 05:48:36', '2025-09-16 05:48:36'),
(136, 15, 56, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-17 03:06:24', '2025-09-19 19:20:03'),
(137, 15, 44, NULL, 1, 27580.00, 0.00, 27580.00, 1, '2025-09-17 03:06:24', '2025-09-19 19:20:03'),
(138, 2, 43, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-18 20:27:03', '2025-09-18 20:27:03'),
(139, 15, 63, NULL, 1, 27580.00, 0.00, 27580.00, 1, '2025-09-19 18:46:20', '2025-09-19 19:20:03'),
(140, 15, 48, NULL, 1, 10000.00, 0.00, 10000.00, 1, '2025-09-19 18:46:40', '2025-09-19 19:20:03'),
(141, 15, 55, NULL, 1, 28800.00, 0.00, 28800.00, 1, '2025-09-19 18:47:52', '2025-09-19 19:20:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `tipo_movimiento` enum('entrada','salida') NOT NULL,
  `observacion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`, `visible`) VALUES
(29, 'STAR INK', 'Marca de Pinturas de Impresora', '2025-09-11 21:52:41', '2025-09-11 21:52:41', 1),
(30, 'GNESIS', 'Marca de Pinturas de Impresora', '2025-09-11 21:53:53', '2025-09-11 21:53:53', 1),
(31, 'ART-JET  INKS', 'Marca de Pinturas de Impresora', '2025-09-11 21:54:20', '2025-09-11 21:54:20', 1),
(32, 'ECOTANK', 'Marca de Pinturas de Impresora', '2025-09-11 21:57:44', '2025-09-11 21:57:44', 1),
(33, 'MGN tintas', 'Marca de Pinturas de Impresora', '2025-09-11 21:58:16', '2025-09-11 21:58:16', 1),
(34, 'GLOBAL ELECTRONIC', 'Fabricante e importador de #tecnologia\r\nVenta mayorista con envíos a todo el país 🇦🇷', '2025-09-11 23:28:51', '2025-09-11 23:28:51', 1),
(36, 'NISUTA', 'Empresa de estampados', '2025-09-12 00:24:29', '2025-09-12 00:24:29', 1),
(37, 'GENIUS', NULL, '2025-09-12 00:27:43', '2025-09-12 00:27:43', 1),
(38, 'TP-LINK', 'MARCA DE PRODUCTOS DE RED', '2025-09-12 00:33:51', '2025-09-12 00:33:51', 1),
(39, 'NETMAK', 'Marca de productos de oficina', '2025-09-12 00:37:39', '2025-09-12 00:37:39', 1),
(40, 'MAXWELL', NULL, '2025-09-12 00:47:39', '2025-09-12 00:47:39', 1),
(41, 'PHILIPS', 'ELETRONICA DE CONSUMO', '2025-09-12 00:55:20', '2025-09-12 00:55:20', 1),
(42, 'Trust', 'Marca de Mause', '2025-09-12 01:13:52', '2025-09-12 01:13:52', 1),
(43, 'LOGITECH', 'MARCA DE ELETRONICA DE CONSUMO', '2025-09-12 01:17:28', '2025-09-12 01:17:28', 1),
(44, 'CDTCK', 'marca de fundas para notebooks', '2025-09-12 01:31:11', '2025-09-12 01:31:11', 1),
(45, 'DAEWOO', 'ELECTRONICA DE CONSUMO', '2025-09-12 01:36:33', '2025-09-12 01:36:33', 1),
(46, 'KANJI', NULL, '2025-09-12 01:38:01', '2025-09-12 01:38:01', 1),
(47, 'RED DRAGON', NULL, '2025-09-12 05:48:53', '2025-09-12 05:48:53', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_08_175057_add_codigo_to_products_table', 2),
(5, '2025_09_08_181411_create_clientes_table', 3),
(6, '2025_09_08_183331_add_visible_to_clientes_table', 4),
(7, '2025_09_09_162147_create_ventas_table', 5),
(8, '2025_09_13_022639_create_servicios_table', 6),
(9, '2025_09_17_002729_create_reparacions_table', 7),
(10, '2025_09_17_020059_create_reparacion_servicio_table', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(255) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_proveedor` decimal(10,2) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `url_imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `sub_categoria` varchar(255) DEFAULT NULL,
  `marca_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `descripcion`, `precio_proveedor`, `precio`, `stock`, `url_imagen`, `categoria_id`, `sub_categoria`, `marca_id`, `created_at`, `updated_at`, `visible`) VALUES
(41, 'COD41', 'MGN-664 BK.TD', 'L: 100,110,120,130,132,200,210,', 4000.00, 6000.00, 2, 'https://www.instagram.com/p/DNoPatJOTgc/?img_index=2', 19, 'UNIVERSAL DYE', 33, '2025-09-11 19:05:56', '2025-09-12 01:55:23', 1),
(42, 'COD42', 'EPSON 544', 'L:1110,1210,1250,3110,3150,3160,3210,3250,3251,3260,3560,5190,5290,5590', 24338.00, 28800.00, 2, 'https://www.instagram.com/p/DMksd4TOTeZ/', 19, 'YELLOW', 32, '2025-09-11 19:09:14', '2025-09-12 01:55:23', 1),
(43, 'COD43', 'EPSON 504', 'L: 4150.L4160,4260,6161,6171,6191,6270,141150', 5000.00, 10000.00, 3, 'https://www.instagram.com/p/DMksd4TOTeZ/', 19, 'YELLOW', 32, '2025-09-11 19:13:07', '2025-09-16 01:38:44', 1),
(44, 'COD44', 'EPSON 664', 'L: 110,120,121,200,210,220,300,310,350,355,365,375,380,395,396,455,475,495,555,565,575,606,655,656,1300,1455', 22064.00, 27580.00, 1, 'https://www.soscomputacion.com.ar/tintas-originales-epson/6730-tinta-epson-original-664-magenta-70ml.html', 19, 'MAGENTA', 32, '2025-09-11 19:17:32', '2025-09-17 00:06:24', 1),
(45, 'COD45', 'E6644', 'L: 110,200,210,300,350,355,550,555 70ML', 4000.00, 10000.00, 1, 'https://www.mercadolibre.com.ar/tinta-compatible-star-ink-para-gt51-gt53-gt52-gt5820/up/MLAU3193473476', 19, 'YELLOW', 29, '2025-09-11 19:24:08', '2025-09-12 01:55:23', 1),
(46, 'COD46', 'E6643', 'L: 110,200,210,300,350,355,550,555', 4000.00, 10000.00, 2, 'https://articulo.mercadolibre.com.ar/MLA-1369991689-tinta-alternativa-t644-para-epson-l110-l200l355l1300-70ml-_JM', 19, 'MAGENTA', 29, '2025-09-11 19:29:51', '2025-09-11 19:29:51', 1),
(47, 'COD47', 'E6642', 'L: 110,200,210,300,350,355,550,555', 4000.00, 10000.00, 1, 'https://listado.mercadolibre.com.ar/tinta-alternativa-e6642#D[A:TINTA%20ALTERNATIVA%20E6642]', 19, 'CYAN', 29, '2025-09-11 19:32:12', '2025-09-11 19:32:12', 1),
(48, 'COD48', 'ART-JET-INKS-CIAN', '1OOmle a', 4000.00, 10000.00, 1, 'https://www.mercadolibre.com.ar/mas-vendidos/MLA3561?attribute_id=BRAND&attribute_value_id=7356576#origin=pdp', 19, 'LIGHT CYAN', 31, '2025-09-11 19:38:25', '2025-09-13 02:34:44', 1),
(49, 'COD49', 'ART-JET-INKS-MAG-CLA', '100ml', 4000.00, 10000.00, 1, 'https://www.mercadolibre.com.ar/mas-vendidos/MLA3561?attribute_id=BRAND&attribute_value_id=7356576#origin=pdp', 19, 'LIGHT MAGENTA', 31, '2025-09-11 19:39:09', '2025-09-12 01:55:24', 1),
(50, 'COD50', 'MGN-664 C.TD', '70ML, usar antes del 2027', 4000.00, 10000.00, 1, 'https://listado.mercadolibre.com.ar/mgn-tintas#D[A:MGN%20TINTAS]', 19, 'CYAN', 33, '2025-09-11 19:41:44', '2025-09-11 20:09:46', 1),
(51, 'COD51', 'MGN-544 BK.TD', 'L: 1210, 1250,3110,3150,3250,3251,3260,5290', 4000.00, 10000.00, 2, 'https://www.instagram.com/p/DNoPatJOTgc/?img_index=2', 19, 'BLACK', 33, '2025-09-11 19:46:52', '2025-09-12 01:55:24', 1),
(52, 'COD52', 'MGN-544 M.TD', 'L: 100,110,120,130,132,200,210,', 4000.00, 10000.00, 2, 'https://www.instagram.com/p/DNoPatJOTgc/?img_index=2', 19, 'MAGENTA', 33, '2025-09-11 19:48:33', '2025-09-11 19:48:33', 1),
(53, 'COD53', 'MGN-544 C.TD', 'L: 100,110,120,130,132,200,210,', 4000.00, 10000.00, 1, 'https://www.instagram.com/p/DNoPatJOTgc/?img_index=2', 19, 'CYAN', 33, '2025-09-11 19:49:49', '2025-09-11 20:09:46', 1),
(54, 'COD54', 'EPSON 544', 'L: 3110,3150,3210,3250,5190', 24338.00, 28800.00, 3, 'https://www.mercadolibre.com.ar/tinta-compatible-star-ink-p-l3110-l3210-l3250-l3150-e544-tinta-amarillo/p/MLA45981848', 19, 'YELLOW', 29, '2025-09-11 19:56:20', '2025-09-12 01:55:24', 1),
(55, 'COD55', 'EPSON 544', 'L: 3110,3150,3210,3250,5190', 24338.00, 28800.00, 2, 'https://www.mercadolibre.com.ar/tinta-compatible-star-ink-p-l3110-l3210-l3250-l3150-e544-tinta-amarillo/p/MLA45981848', 19, 'MAGENTA', 29, '2025-09-11 19:57:05', '2025-09-11 21:06:04', 1),
(56, 'COD56', 'EPSON 544', 'L: 3110,3150,3210,3250,5190', 4000.00, 10000.00, 1, 'https://www.mercadolibre.com.ar/tinta-compatible-star-ink-p-l3110-l3210-l3250-l3150-e544-tinta-amarillo/p/MLA45981848', 19, 'CYAN', 29, '2025-09-11 19:58:47', '2025-09-17 00:06:24', 1),
(57, 'COD57', 'EPSON 544', 'L: 3110,3150,3210,3250,5190', 24338.00, 28800.00, 3, 'https://www.instagram.com/p/DNoPatJOTgc/?img_index=2', 19, 'BLACK', 29, '2025-09-11 20:00:25', '2025-09-12 01:55:24', 1),
(58, 'COD58', 'E6643', 'L: 110,200,210,300,350,355,550,555', 4000.00, 10000.00, 2, 'https://listado.mercadolibre.com.mx/refill-ink-e6643', 19, 'YELLOW', 29, '2025-09-11 20:03:09', '2025-09-12 01:55:24', 1),
(59, 'COD59', 'EPSON 673', 'L: 4150.L4160,4260,6161,6171,6191,6270,141150 ALTERNATIVO', 4000.00, 10000.00, 1, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'CYAN', 32, '2025-09-11 20:12:10', '2025-09-11 20:12:10', 1),
(60, 'COD60', 'EPSON 504-N-PRETO', 'L: 4150.L4160,4260,6161,6171,6191,6270,14150', 4000.00, 10000.00, 1, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'BLACK', 32, '2025-09-11 20:15:16', '2025-09-12 01:55:24', 1),
(61, 'COD61', 'EPSON 544', 'L:1110,1210,1250,3110,3150,3160,3210,3250,3251,3260,3560,5190,5290,5590', 24338.00, 28800.00, 2, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'MAGENTA', 32, '2025-09-11 20:18:16', '2025-09-11 21:00:41', 1),
(62, 'COD62', 'EPSON 504', 'L:1110,1210,1250,3110,3150,3160,3210,3250,3251,3260,3560,5190,5290,5590', 4000.00, 10000.00, 1, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'CYAN', 32, '2025-09-11 20:21:51', '2025-09-11 20:21:51', 1),
(63, 'COD63', 'EPSON 664', 'L:1110,1210,1250,3110,3150,3160,3210,3250,3251,3260,3560,5190,5290,5590', 22064.00, 27580.00, 0, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'BLACK', 32, '2025-09-11 20:23:30', '2025-09-14 23:36:28', 1),
(64, 'COD64', 'EPSON 664', 'L:1110,1210,1250,3110,3150,3160,3210,3250,3251,3260,3560,5190,5290,5590', 22064.00, 27580.00, 3, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'YELLOW', 32, '2025-09-11 20:24:11', '2025-09-16 01:38:54', 1),
(65, 'COD65', 'TINTA 195-196-197', 'L: 101,201,211,401,204,104,214,411', 1215.00, 4600.00, 5, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'COLOR', 29, '2025-09-11 20:40:05', '2025-09-11 20:48:29', 1),
(66, 'COD66', 'MGN-206', 'XP-2101', 4000.00, 6500.00, 4, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'COLOR', 33, '2025-09-11 20:41:42', '2025-09-11 20:41:42', 1),
(67, 'COD67', 'TINTA 296-297', 'L: 231,241,431,441', 3000.00, 6200.00, 7, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'COLOR', 33, '2025-09-11 20:44:55', '2025-09-11 20:44:55', 1),
(68, 'COD68', 'EPSON 664', 'L:1110,1210,1250,3110,3150,3160,3210,3250,3251,3260,3560,5190,5290,5590', 22064.00, 27580.00, 1, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'CYAN', 32, '2025-09-11 20:54:04', '2025-09-11 21:08:09', 1),
(69, 'COD69', 'EPSON 544', 'L:1110,1210,1250,3110,3150,3160,3210,3250,3251,3260,3560,5190,5290,5590', 24338.00, 35000.00, 1, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 19, 'BLACK', 32, '2025-09-11 20:58:24', '2025-09-12 03:02:33', 1),
(70, 'COD70', 'PAPEL FOTOCOPIA A4', 'BRILLANTE', 3572.00, 5000.00, 3, 'https://listado.mercadolibre.com.mx/papel-fotocopia-brillante-115g#D[A:papel%20fotocopia%20brillante%20115g]', 20, '115G 20 HOJAS', 31, '2025-09-11 21:19:43', '2025-09-11 21:19:43', 1),
(71, 'COD71', 'PAPEL FOTOCOPIA A4', 'BRILLANTE', 17277.00, 23000.00, 2, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 20, '115G 100 HOJAS', 31, '2025-09-11 21:20:36', '2025-09-11 21:20:36', 1),
(72, 'COD72', 'PAPEL FOTOCOPIA A4', 'BRILLANTE', 10661.00, 15000.00, 2, 'https://listado.mercadolibre.com.mx/ecotank-pintura?sb=all_mercadolibre#D[A:ecotank%20pintura]', 20, '200G 100 HOJAS', 31, '2025-09-11 21:23:13', '2025-09-11 21:23:13', 1),
(73, 'COD73', 'MAUSEPAD 78x28x3', 'Estampada', 15262.00, 25000.00, 2, 'https://listado.mercadolibre.com.ar/mausepad-78x28x3#D[A:mausepad%2078x28x3]', 21, 'NSPAD78D', 36, '2025-09-11 21:27:11', '2025-09-11 21:27:11', 1),
(74, 'COD74', 'Mause NX-7000', 'Wireless', 9785.00, 13000.00, 1, 'https://listado.mercadolibre.com.ar/genius-nx-7000#D[A:genius%20nx%207000]', 22, 'Black', 37, '2025-09-11 21:29:03', '2025-09-12 02:59:21', 1),
(75, 'COD75', 'HUB USB 2.04', 'PUERTOS', 10564.00, 14000.00, 1, 'https://listado.mercadolibre.com.ar/nisuhub-usb-nisuta#D[A:nisuHUB%20USB%20NISUTA]', 23, 'NSUH439', 36, '2025-09-11 21:31:56', '2025-09-11 21:31:56', 1),
(76, 'COD76', 'HUB USB 2.04', 'PUERTOS', 7300.00, 12000.00, 1, 'https://listado.mercadolibre.com.ar/nisuhub-usb-nisuta#D[A:nisuHUB%20USB%20NISUTA]', 23, 'NS-UH0420', 36, '2025-09-11 21:33:20', '2025-09-11 21:33:20', 1),
(77, 'COD77', 'TL-WA850RE', 'Extensor de red', 30719.00, 40000.00, 1, 'https://listado.mercadolibre.com.ar/tl-wa850re#D[A:TL-WA850RE]', 24, '300Mps', 38, '2025-09-11 21:36:15', '2025-09-11 21:36:15', 1),
(78, 'COD78', 'NM-PAD2 23X20CM', 'Mausepad de escritorio chico', 2739.00, 5000.00, 3, 'https://listado.mercadolibre.com.ar/nm-pad2-23x20cm?sb=all_mercadolibre#D[A:NM-PAD2%2023X20CM]', 21, 'COLOR', 39, '2025-09-11 21:39:09', '2025-09-11 21:39:50', 1),
(79, 'COD79', 'MAUSEPAD', 'NEGRO/AZUL', 4197.00, 7000.00, 3, 'https://listado.mercadolibre.com.ar/mausepad-nisuta?sb=all_mercadolibre#D[A:mausepad%20nisuta]', 21, 'NSPAD24', 36, '2025-09-11 21:41:09', '2025-09-11 21:41:09', 1),
(80, 'COD80', 'Shure SH-550', 'Fuente', 20071.00, 32000.00, 1, 'https://listado.mercadolibre.com.ar/shure-sh-550-550w#D[A:shure%20sh%20550%20550w]', 25, '550W', 39, '2025-09-11 21:43:28', '2025-09-11 21:43:28', 1),
(81, 'COD81', 'PILAS AA', 'POR UNIDAD', 834.00, 2000.00, 10, 'https://listado.mercadolibre.com.ar', 26, 'ALCALINA', 40, '2025-09-11 21:50:57', '2025-09-11 21:50:57', 1),
(82, 'COD82', 'Botella Tinte Universal', '250cc Negro Magna', 3865.00, 7000.00, 1, 'https://listado.mercadolibre.com.ar', 19, 'BLACK', 29, '2025-09-11 21:52:12', '2025-09-12 03:03:05', 1),
(83, 'COD83', 'Empleado', 'no le pago bien', 11111.00, 1111111.00, 1, 'https://listado.mercadolibre.com.ar', 24, 'yo', 30, '2025-09-11 21:54:44', '2025-09-11 21:54:50', 0),
(84, 'COD84', 'M234', 'WIRED MOUSE', 4500.00, 10000.00, 2, 'https://listado.mercadolibre.com.ar/m234', 22, 'NEGRO', 41, '2025-09-11 22:00:03', '2025-09-11 22:00:03', 1),
(85, 'COD85', 'Micro Mause', 'GENIUS', 4000.00, 10000.00, 3, 'https://listado.mercadolibre.com.ar/micro-mause#D[A:MICRO%20MAUSE]', 22, 'COLOR', 37, '2025-09-11 22:03:08', '2025-09-11 22:03:08', 1),
(86, 'COD86', 'ECO-8015', 'Wireless Mouse', 10000.00, 20000.00, 1, 'https://listado.mercadolibre.com.ar/eco-8015-mause#D[A:eco%208015%20mause]', 22, 'GRAY', 37, '2025-09-11 22:06:14', '2025-09-12 02:58:33', 1),
(87, 'COD87', 'OPTICAL MAUSE', 'Optical Mause', 3000.00, 6200.00, 2, 'https://listado.mercadolibre.com.ar/mauseoptico', 22, 'DX-SERIES', 37, '2025-09-11 22:09:44', '2025-09-11 22:09:44', 1),
(88, 'COD88', 'DX-7000X', '2,4 Ghz', 25000.00, 54500.00, 2, 'https://listado.mercadolibre.com.ar/mouse-genius-dx-7000-black-opticos-inalambricos', 22, 'DX-SERIES', 37, '2025-09-11 22:13:11', '2025-09-11 22:13:11', 1),
(89, 'COD89', 'MOUSETRUST', 'WIRELESS MOUSE', 4000.00, 10000.00, 4, 'https://listado.mercadolibre.com.ar/trust-wireless?sb=all_mercadolibre#D[A:trust%20wireless]', 22, 'TRUST', 42, '2025-09-11 22:14:45', '2025-09-11 22:14:45', 1),
(90, 'COD90', 'MOUSE M170', 'WIRELESS', 6000.00, 12000.00, 4, 'https://listado.mercadolibre.com.ar/mouse-m170#D[A:MOUSE%20M170]', 22, 'COLOR', 43, '2025-09-11 22:18:45', '2025-09-11 22:22:15', 1),
(91, 'COD91', 'MOUSE M170 GRANDE', 'WIRELESS', 10000.00, 18000.00, 2, 'https://listado.mercadolibre.com.ar/mouse-m170#D[A:MOUSE%20M170]', 22, 'COLOR', 43, '2025-09-11 22:23:24', '2025-09-11 22:23:24', 1),
(92, 'COD92', 'MOUSE M90-110', 'WIRELESS', 5000.00, 12000.00, 2, 'https://listado.mercadolibre.com.ar/mouse-m170#D[A:MOUSE%20M110]', 22, 'COLOR', 43, '2025-09-11 22:24:29', '2025-09-11 22:24:29', 1),
(93, 'COD93', 'MOUSE M87', 'MINI MOUSE', 10000.00, 35000.00, 1, 'https://listado.mercadolibre.com.ar/mouse-m170#D[A:MOUSE%20M170]', 22, 'COLOR', 43, '2025-09-11 22:25:46', '2025-09-11 22:25:46', 1),
(94, 'COD94', 'MOUSE M625', 'OPTICAL MOUSE', 2000.00, 5100.00, 2, 'https://listado.mercadolibre.com.ar/mouse-netmak-m625#D[A:mouse%20netmak%20m625]', 22, 'NEGRO', 39, '2025-09-11 22:27:16', '2025-09-11 22:27:16', 1),
(95, 'COD95', 'FUNDA PARA NOTEBOOK', 'SEGUN EL COLOR', 12.00, 19900.00, 2, 'https://listado.mercadolibre.com.ar/fundas-para-notebook-13%2C3#D[A:FUNDAS%20PARA%20NOTEBOOK%2013,3]', 27, '13,3', 44, '2025-09-11 22:33:03', '2025-09-11 22:33:03', 1),
(96, 'COD96', 'FUNDAS PARA NOTEBOOK', 'CAMBIA EL COLOR', 14000.00, 20500.00, 5, 'https://listado.mercadolibre.com.ar/fundas-para-notebook-13%2C3#D[A:FUNDAS%20PARA%20NOTEBOOK%2013,3]', 27, '15,6', 44, '2025-09-11 22:33:57', '2025-09-11 22:33:57', 1),
(97, 'COD97', 'AURICULAR DAEWOO', 'WIRELESS', 20000.00, 36500.00, 3, 'https://listado.mercadolibre.com.ar/dawoo-prix?sb=all_mercadolibre#D[A:DAWOO%20PRIX]', 28, 'COLOR', 45, '2025-09-11 22:39:46', '2025-09-11 22:39:46', 1),
(98, 'COD98', 'AURICULAR NETMAK BIZA', 'WIRELESS', 10000.00, 21000.00, 5, 'https://listado.mercadolibre.com.ar/auriculares-biza?sb=all_mercadolibre#D[A:auriculares%20biza%20]', 28, 'COLOR', 39, '2025-09-11 22:41:09', '2025-09-11 22:41:09', 1),
(99, 'COD99', 'DW-PL431Z1', 'WIRELESS', 15000.00, 31000.00, 1, 'https://listado.mercadolibre.com.ar/daewoo-polar#D[A:daewoo%20polar]', 28, 'POLAR', 45, '2025-09-11 22:42:28', '2025-09-11 22:42:28', 1),
(100, 'COD100', 'NM-UR70', 'POR CABLE', 1000.00, 4000.00, 4, 'https://listado.mercadolibre.com.ar/netmark-nm-ur70?sb=all_mercadolibre#D[A:netmark%20nm%20ur70]', 28, 'COLOR', 39, '2025-09-11 22:44:14', '2025-09-11 22:44:14', 1),
(101, 'COD101', 'EARBUDS', 'WIRELESS', 14000.00, 30000.00, 1, 'https://listado.mercadolibre.com.ar/earbuds-netmark#D[A:EARBUDS%20NETMARK]', 28, 'AURICULAR BT', 39, '2025-09-11 22:46:39', '2025-09-11 22:46:39', 1),
(102, 'COD102', 'DAEWOO JAY', 'WIRELESS', 14000.00, 29000.00, 2, 'https://listado.mercadolibre.com.ar/daewoo-jay#D[A:DAEWOO%20JAY]', 28, 'COLOR', 45, '2025-09-11 22:47:58', '2025-09-11 22:47:58', 1),
(103, 'COD103', 'HS-300N', 'WIRELESS', 7000.00, 12000.00, 1, 'https://listado.mercadolibre.com.ar/hs-300n-auricualr#D[A:HS-300N%20auricualr', 28, 'OFFICE', 37, '2025-09-11 22:49:39', '2025-09-11 22:49:39', 1),
(104, 'COD104', 'AURICULAR PC', 'Ideal para oficina', 6000.00, 14000.00, 1, 'https://listado.mercadolibre.com.ar/auricular-pc-netmark_PriceRange_0-20000_NoIndex_True#applied_filter_id%3Dprice%26applied_filter_name%', 28, 'OFFICE', 39, '2025-09-11 22:52:17', '2025-09-11 22:52:17', 1),
(105, 'COD105', 'DANGER', 'compatible con múltiples dispositivos', 10000.00, 33000.00, 1, 'https://listado.mercadolibre.com.ar/danger-joystick#D[A:danger%20joystick]', 29, 'PC/P4/P3', 39, '2025-09-11 22:56:50', '2025-09-11 22:56:50', 1),
(106, 'COD106', 'DELUXE', 'BOTONES PROGRAMABLES', 10000.00, 23000.00, 1, 'https://listado.mercadolibre.com.ar/deluxe-gamepad#D[A:deluxe%20GAMEPAD]', 29, 'GAMEPAD', 39, '2025-09-11 22:59:54', '2025-09-11 22:59:54', 1),
(107, 'COD107', 'NUEVO CAMPO DE PRECIO', 'Descripcion generica', 1.00, 2.00, 1, 'img/hdmi_cable.jpg', 13, 'PROCENTAJEs', 47, '2025-09-12 02:36:02', '2025-09-12 03:07:23', 0),
(108, 'COD108', 'Teclado KUMARA', '60% de tamaño', 21000.00, 33600.00, 2, 'img/hdmi_cable.jpg', 30, 'COLOR', 47, '2025-09-12 02:50:03', '2025-09-12 02:50:39', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `contacto`, `telefono`, `email`, `direccion`, `created_at`, `updated_at`, `visible`) VALUES
(1, 'Distribuidora Norte', 'Juan Pérez', '1122334455', 'norte@distribuidora.com', 'Av. Siempre Viva 123', '2025-09-02 14:35:44', '2025-09-02 14:35:44', 1),
(2, 'Distribuidora Sur', 'María López', '2233445566', 'sur@distribuidora.com', 'Calle Falsa 456', '2025-09-02 14:35:44', '2025-09-02 14:35:44', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparaciones`
--

CREATE TABLE `reparaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo_unico` varchar(50) NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `equipo_descripcion` varchar(255) NOT NULL,
  `equipo_marca` varchar(100) DEFAULT NULL,
  `equipo_modelo` varchar(100) DEFAULT NULL,
  `descripcion_danio` text NOT NULL,
  `solucion_aplicada` text DEFAULT NULL,
  `reparable` tinyint(1) NOT NULL DEFAULT 1,
  `estado_reparacion` enum('Pendiente','En proceso','Reparado','No reparable','Entregado') NOT NULL DEFAULT 'Pendiente',
  `fecha_ingreso` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reparaciones`
--

INSERT INTO `reparaciones` (`id`, `codigo_unico`, `cliente_id`, `equipo_descripcion`, `equipo_marca`, `equipo_modelo`, `descripcion_danio`, `solucion_aplicada`, `reparable`, `estado_reparacion`, `fecha_ingreso`, `created_at`, `updated_at`) VALUES
(1, 'REPARE1', 13, 'Impresora Epshon Mk1-2334', 'EPSHON', 'MK-1232', 'SE ROMPIO EL TONEL, LA FUENTE DE ENERGIA TAMBIEN.', 'CAMBIO DE TONEL Y TORNO.\r\nCAMBIO DE FUENTE', 1, 'Pendiente', '2025-09-16 22:07:00', '2025-09-17 01:07:50', '2025-09-17 01:07:50'),
(3, 'REPARE3', 13, 'Smartphone Samsung', 'Samsung', 'Galaxy S22', 'Batería no carga', 'Reemplazo de batería', 1, 'En proceso', '2025-09-14 14:15:00', NULL, NULL),
(5, 'EXOSMRT1', 2, 'Netbook Exo', 'Exo', 'L403', 'Se le rompio la pantalla y el sonido', 'Cambio de Display y parlante interno', 1, 'Pendiente', '2025-09-16 00:00:00', NULL, NULL),
(6, 'REP-20250917013648', 2, 'Celular TCL 505', 'TLC', '505 SERIE', 'Daño en el modulo\r\nLimpieza y restauración de fabrica', 'Cambio de Modulo', 1, 'Pendiente', '2025-09-16 00:00:00', NULL, NULL),
(7, 'REP-223128', 4, 'Impresora Epshon Mk1-2334', 'EPSHON', 'L403', 'nose hay que ver', 'a determinar', 1, 'Pendiente', '2025-09-19 00:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparacion_producto`
--

CREATE TABLE `reparacion_producto` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reparacion_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` int(11) NOT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reparacion_producto`
--

INSERT INTO `reparacion_producto` (`id`, `reparacion_id`, `producto_id`, `precio`, `cantidad`, `created_at`, `updated_at`) VALUES
(1, 5, 43, 30000.00, 1, '2025-09-18 15:58:48', '2025-09-18 19:12:14'),
(2, 5, 54, 30000.00, 1, '2025-09-18 19:02:38', '2025-09-18 19:12:14'),
(4, 6, 95, 40000.00, 1, '2025-09-18 19:12:53', '2025-09-18 19:12:53'),
(5, 6, 92, 20000.00, 1, '2025-09-18 19:12:53', '2025-09-18 19:12:53'),
(6, 3, 45, 40000.00, 1, '2025-09-18 19:20:15', '2025-09-18 19:20:15'),
(7, 3, 45, 55000.00, 1, '2025-09-18 19:20:15', '2025-09-18 19:20:15'),
(8, 1, 42, 28800.00, 1, '2025-09-19 19:47:16', '2025-09-19 19:47:38'),
(9, 1, 45, 10000.00, 1, '2025-09-19 19:47:16', '2025-09-19 19:47:38'),
(10, 1, 46, 10000.00, 1, '2025-09-19 19:47:16', '2025-09-19 19:47:38'),
(11, 1, 49, 10000.00, 1, '2025-09-19 19:47:16', '2025-09-19 19:47:38'),
(12, 1, 44, 10000.00, 1, '2025-09-19 19:47:38', '2025-09-19 19:47:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reparacion_servicio`
--

CREATE TABLE `reparacion_servicio` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reparacion_id` bigint(20) UNSIGNED NOT NULL,
  `servicio_id` bigint(20) UNSIGNED NOT NULL,
  `precio` float NOT NULL,
  `cantidad` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reparacion_servicio`
--

INSERT INTO `reparacion_servicio` (`id`, `reparacion_id`, `servicio_id`, `precio`, `cantidad`, `created_at`, `updated_at`) VALUES
(2, 5, 5, 0, 1, NULL, '2025-09-18 18:23:26'),
(3, 5, 7, 0, 1, '2025-09-17 05:29:23', '2025-09-18 18:23:26'),
(5, 3, 2, 45000, 1, '2025-09-17 06:22:30', '2025-09-17 06:27:34'),
(6, 3, 4, 54000, 1, '2025-09-17 06:22:30', '2025-09-17 06:27:34'),
(7, 1, 5, 30000, 1, '2025-09-17 06:22:43', '2025-09-18 18:23:17'),
(8, 1, 4, 30000, 1, '2025-09-17 06:22:43', '2025-09-18 18:23:17'),
(9, 6, 2, 2500, 1, '2025-09-17 06:27:49', '2025-09-19 19:57:23'),
(11, 1, 6, 30000, 1, '2025-09-18 18:23:17', '2025-09-18 18:23:17'),
(12, 7, 3, 40000, 1, '2025-09-19 19:49:34', '2025-09-19 19:49:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `iva_aplicable` decimal(10,2) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `iva_aplicable`, `precio`, `activo`, `visible`, `created_at`, `updated_at`) VALUES
(1, 'Instalación de S.O', 'Instalación de Windows, Linux o MacOS en computadoras de escritorio o portátiles', 1.00, 5000.00, 1, 1, '2025-09-13 02:36:20', '2025-09-13 06:06:21'),
(2, 'Mantenimiento Preventivo', 'Limpieza interna de hardware y optimización del sistema operativo para mejorar el rendimiento.', 21.00, 2500.00, 1, 1, '2025-09-13 02:36:20', '2025-09-13 02:36:20'),
(3, 'Reparación de Hardware', 'Diagnóstico y reemplazo de componentes defectuosos (disco, memoria, placa madre, fuente, etc.).', 21.00, 4000.00, 1, 1, '2025-09-13 02:36:20', '2025-09-13 02:36:20'),
(4, 'Recuperación de Datos', 'Recuperación de archivos perdidos o eliminados accidentalmente de discos duros o unidades externas.', 21.00, 3500.00, 1, 1, '2025-09-13 02:36:20', '2025-09-13 02:36:20'),
(5, 'Instalación de Software', 'Instalación y configuración de programas de productividad, antivirus o utilidades.', 21.00, 1500.00, 1, 1, '2025-09-13 02:36:20', '2025-09-13 02:36:20'),
(6, 'Soporte Remoto', 'Asistencia técnica a distancia vía software de escritorio remoto.', 21.00, 2000.00, 1, 1, '2025-09-13 02:36:20', '2025-09-13 02:36:20'),
(7, 'Configuración de Red', 'Configuración de redes domésticas o de oficina, incluyendo routers, switches y Wi-Fi.', 21.00, 3000.00, 1, 1, '2025-09-13 02:36:20', '2025-09-13 02:36:20'),
(8, 'Backup y Respaldo de Datos', 'Creación de copias de seguridad automáticas o manuales de archivos y sistemas completos.', 21.00, 3000.00, 1, 1, '2025-09-13 06:06:54', '2025-09-13 06:06:54'),
(9, 'Optimización de Software', 'Actualización y ajuste de programas para mejorar el rendimiento y la compatibilidad.', 21.00, 2200.00, 1, 1, '2025-09-13 06:07:28', '2025-09-13 06:07:28'),
(10, 'Reparación de Impresora', 'Reparar las partes dañadas de la impresora', 21.00, 30000.00, 1, 1, '2025-09-15 02:39:31', '2025-09-15 02:39:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_venta`
--

CREATE TABLE `servicio_venta` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `servicio_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio_venta`
--

INSERT INTO `servicio_venta` (`id`, `venta_id`, `servicio_id`, `cantidad`, `precio`, `created_at`, `updated_at`) VALUES
(5, 1, 2, 2, 20000.00, '2025-09-13 23:07:22', '2025-09-13 23:07:22'),
(6, 1, 6, 1, 10000.00, '2025-09-13 23:07:22', '2025-09-13 23:07:22'),
(7, 1, 8, 2, 3000.00, '2025-09-13 23:09:00', '2025-09-13 23:09:00'),
(8, 2, 2, 1, 2500.00, '2025-09-13 23:23:55', '2025-09-13 23:23:55'),
(9, 2, 3, 1, 4000.00, '2025-09-13 23:23:55', '2025-09-13 23:23:55'),
(10, 2, 5, 1, 1500.00, '2025-09-13 23:23:55', '2025-09-13 23:23:55'),
(11, 2, 8, 1, 19999.94, NULL, NULL),
(12, 3, 1, 1, 5000.00, NULL, NULL),
(13, 7, 1, 1, 0.00, NULL, NULL),
(14, 9, 3, 1, 20000.00, NULL, NULL),
(15, 9, 8, 1, 10000.00, NULL, NULL),
(16, 14, 10, 1, 30000.00, NULL, NULL),
(17, 15, 2, 1, 2500.00, NULL, NULL),
(18, 15, 3, 1, 4000.00, NULL, NULL),
(19, 10, 3, 1, 4000.00, NULL, NULL),
(20, 2, 3, 1, 30000.00, NULL, NULL),
(21, 2, 8, 1, 40000.00, NULL, NULL),
(22, 15, 2, 1, 60000.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('TVXsnjZ581Uw7XW5p86xAsMle8N7u8dpT7cQJBFZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidTFYdVA3dUIyczZOR1NydlhlTkVrWFRHcWVPaFlhTURaRHdudUxvUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Nzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zZXJ2aWNpb3N2ZW50YXM/bnVtZXJvX2NvbXByb2JhbnRlPSZzZWFyY2g9JnZlbnRhPSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758302709);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_comprobante` varchar(255) NOT NULL DEFAULT 'Factura',
  `numero_comprobante` varchar(255) DEFAULT NULL,
  `condicion_pago` varchar(255) NOT NULL DEFAULT 'Contado',
  `importe_neto` decimal(10,2) NOT NULL,
  `importe_iva` decimal(10,2) NOT NULL,
  `importe_total` decimal(10,2) NOT NULL,
  `estado_venta` varchar(255) NOT NULL DEFAULT 'Pagada',
  `observaciones` text DEFAULT NULL,
  `fecha_venta` date NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_id`, `tipo_comprobante`, `numero_comprobante`, `condicion_pago`, `importe_neto`, `importe_iva`, `importe_total`, `estado_venta`, `observaciones`, `fecha_venta`, `visible`, `created_at`, `updated_at`) VALUES
(1, 1, 'Factura', 'N1ASDAS234', 'Contado', 278080.00, 0.00, 278080.00, 'Pagada', 'VINO FALLADO EL AURICULAR', '2025-09-15', 1, '2025-09-16 02:08:49', '2025-09-13 04:56:21'),
(2, 11, 'Factura', 'N2321ASDV', 'Contado Efectivo', 10000.00, 0.00, 10000.00, 'Pagada', NULL, '2025-09-15', 1, '2025-09-16 02:08:49', '2025-09-18 20:27:03'),
(3, 5, 'Presupuesto', 'NDASSD122', 'Contado Efectivo', 38800.00, 0.00, 38800.00, 'Pendiente', NULL, '2025-09-15', 0, '2025-09-16 02:08:49', '2025-09-19 20:25:02'),
(4, 6, 'Factura', 'ANSRFI3', 'Contado', 405000.00, 85050.00, 490050.00, 'Pagada', NULL, '2025-09-10', 0, '2025-09-16 02:08:49', '2025-09-19 20:09:24'),
(5, 2, 'Presupuesto', 'KASDOOOM139', 'Contado', 740700.00, 155547.00, 896247.00, 'Pagada', NULL, '2025-09-10', 0, '2025-09-16 02:08:49', '2025-09-18 20:28:04'),
(6, 5, 'Factura', 'AREUSET2', 'Contado', 95000.00, 19950.00, 114950.00, 'Pagada', NULL, '2025-09-10', 0, '2025-09-10 07:14:52', '2025-09-19 20:11:24'),
(7, 10, 'Factura', 'KAPU10', 'Contado', 18000.00, 0.00, 18000.00, 'Pagada', NULL, '2025-09-10', 0, '2025-09-10 20:07:58', '2025-09-19 20:11:10'),
(8, 8, 'Presupuesto', 'NMRCROOT43', 'Contado Efectivo', 191800.00, 0.00, 191800.00, 'Pagada', 'Muy corto de plata', '2025-09-13', 0, '2025-09-13 03:22:28', '2025-09-19 20:11:27'),
(9, 12, 'Presupuesto', 'NMRRIGO1', 'Contado Transferencia', 56500.00, 0.00, 56500.00, 'Pagada', NULL, '2025-09-14', 0, '2025-09-15 01:56:23', '2025-09-19 20:11:31'),
(10, 13, 'Presupuesto', 'NRMASQUIROZ1', 'Cuenta Corriente', 72000.00, 0.00, 72000.00, 'Pendiente', NULL, '2025-09-14', 1, '2025-09-15 02:06:49', '2025-09-15 02:32:16'),
(11, 13, 'Factura', 'NMRCRQUERIO1', 'Contado Transferencia', 37580.00, 0.00, 37580.00, 'Pagada', 'Un tipazo', '2025-09-14', 1, '2025-09-15 02:24:25', '2025-09-15 02:32:07'),
(12, 13, 'Factura', 'NMRCREQS1', 'Contado Efectivo', 19900.00, 0.00, 19900.00, 'Pagada', 'Llego con la plata', '2025-09-08', 1, '2025-09-15 02:26:52', '2025-09-15 02:27:46'),
(14, 12, 'Factura', 'NMRCRORGOPA1', 'Cuenta Corriente', 30600.00, 0.00, 30600.00, 'Pendiente', 'Muy capo se llevo todas las tintas', '2025-09-14', 0, '2025-09-15 02:36:28', '2025-09-19 20:10:40'),
(15, 12, 'Factura', 'NMRCROHORAST111', 'Contado Efectivo', 103960.00, 0.00, 103960.00, 'Pagada', 'Nada', '2025-09-17', 0, '2025-09-17 03:06:24', '2025-09-19 20:08:56');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientes_cuit/dni_unique` (`cuit_dni`),
  ADD UNIQUE KEY `clientes_email_unique` (`Email`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_ventas_venta_id_foreign` (`venta_id`),
  ADD KEY `fk_detalle_ventas_producto` (`producto_id`),
  ADD KEY `fk_detalle_ventas_servicio` (`servicio_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `marca_id` (`marca_id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reparaciones_codigo_unico_unique` (`codigo_unico`),
  ADD KEY `reparaciones_cliente_id_foreign` (`cliente_id`);

--
-- Indices de la tabla `reparacion_producto`
--
ALTER TABLE `reparacion_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reparacion_producto_reparacion_id_foreign` (`reparacion_id`),
  ADD KEY `fk_producto` (`producto_id`);

--
-- Indices de la tabla `reparacion_servicio`
--
ALTER TABLE `reparacion_servicio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reparacion_servicio_reparacion_id_servicio_id_unique` (`reparacion_id`,`servicio_id`),
  ADD KEY `reparacion_servicio_servicio_id_foreign` (`servicio_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicio_venta`
--
ALTER TABLE `servicio_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_servicio_venta_venta` (`venta_id`),
  ADD KEY `fk_servicio_venta_servicio` (`servicio_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ventas_numero_comprobante_unique` (`numero_comprobante`),
  ADD KEY `ventas_cliente_id_foreign` (`cliente_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `reparacion_producto`
--
ALTER TABLE `reparacion_producto`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `reparacion_servicio`
--
ALTER TABLE `reparacion_servicio`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `servicio_venta`
--
ALTER TABLE `servicio_venta`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detalle_ventas_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_ventas_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`);

--
-- Filtros para la tabla `reparaciones`
--
ALTER TABLE `reparaciones`
  ADD CONSTRAINT `reparaciones_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reparacion_producto`
--
ALTER TABLE `reparacion_producto`
  ADD CONSTRAINT `fk_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reparacion_producto_reparacion_id_foreign` FOREIGN KEY (`reparacion_id`) REFERENCES `reparaciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reparacion_servicio`
--
ALTER TABLE `reparacion_servicio`
  ADD CONSTRAINT `reparacion_servicio_reparacion_id_foreign` FOREIGN KEY (`reparacion_id`) REFERENCES `reparaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reparacion_servicio_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `servicio_venta`
--
ALTER TABLE `servicio_venta`
  ADD CONSTRAINT `fk_servicio_venta_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_servicio_venta_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
