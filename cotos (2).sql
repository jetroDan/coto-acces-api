-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-01-2020 a las 08:23:41
-- Versión del servidor: 5.7.19
-- Versión de PHP: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cotos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotos`
--

DROP TABLE IF EXISTS `cotos`;
CREATE TABLE IF NOT EXISTS `cotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `houseCount` int(11) NOT NULL DEFAULT '0',
  `address` varchar(250) NOT NULL,
  `userCount` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cotos`
--

INSERT INTO `cotos` (`id`, `name`, `houseCount`, `address`, `userCount`, `created_at`, `updated_at`) VALUES
(1, 'Nuevo coto modificado', 4, 'nueva direccion de coto', 0, '2020-01-07 00:51:41', '2020-01-13 22:52:27'),
(2, 'nuevo coto 2', 5, 'nueva direccion', 0, '2020-01-07 01:36:17', '2020-01-07 01:36:17'),
(3, 'Nuevo coto 3', 4, 'nueva direccion', 0, '2020-01-07 02:31:22', '2020-01-07 02:31:22'),
(4, 'nuevo coto 4', 14, 'direccion', 0, '2020-01-07 02:36:41', '2020-01-07 02:36:41'),
(45, 'nuevo coto 4', 14, 'direccion', 0, '2020-01-07 04:10:05', '2020-01-07 04:10:05'),
(46, 'nuevo coto de prueba final', 10, 'avenida de la casa', 0, '2020-01-31 08:17:08', '2020-01-31 08:17:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invitations`
--

DROP TABLE IF EXISTS `invitations`;
CREATE TABLE IF NOT EXISTS `invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `visitor_phone` varchar(20) DEFAULT NULL,
  `token` text NOT NULL,
  `name` varchar(150) NOT NULL,
  `invitation_type` int(11) NOT NULL DEFAULT '0',
  `visitor_type` int(11) NOT NULL DEFAULT '0',
  `visit_day` date DEFAULT NULL,
  `arrival_time` time NOT NULL,
  `departure_time` time NOT NULL,
  `visit_duration` time DEFAULT NULL,
  `recurring_visitor` int(1) NOT NULL DEFAULT '0',
  `last_visit_day` date DEFAULT NULL,
  `daily` int(1) NOT NULL DEFAULT '0',
  `indefinite_stay` int(1) NOT NULL DEFAULT '0',
  `motive` varchar(150) DEFAULT NULL,
  `monday` int(1) NOT NULL DEFAULT '0',
  `tuesday` int(1) NOT NULL DEFAULT '0',
  `wednesday` int(1) NOT NULL DEFAULT '0',
  `thursday` int(1) NOT NULL DEFAULT '0',
  `friday` int(1) NOT NULL DEFAULT '0',
  `saturday` int(1) NOT NULL DEFAULT '0',
  `sunday` int(1) NOT NULL DEFAULT '0',
  `specific_days` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `invitations`
--

INSERT INTO `invitations` (`id`, `user_id`, `visitor_phone`, `token`, `name`, `invitation_type`, `visitor_type`, `visit_day`, `arrival_time`, `departure_time`, `visit_duration`, `recurring_visitor`, `last_visit_day`, `daily`, `indefinite_stay`, `motive`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `specific_days`, `created_at`, `updated_at`) VALUES
(15, 11, '32159321552', '$2y$10$TkcJZFKNQbk5hcSydqROusZa8Kn06U5VoXZLgk1b4ILT1dt0kxC', 'Nuevo visitante', 0, 0, '2020-01-17', '14:34:00', '18:34:00', '14:34:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-18 02:39:36', '2020-01-18 02:39:36'),
(16, 11, '32159321552', '$2y$10$TkcJZFKNQbk5hcSydqROusZa8Kn06U5VoXZLgk1b4ILT1dt0kxC', 'Nuevo visitante', 0, 0, '2020-01-17', '14:34:00', '18:34:00', '14:34:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-18 02:39:36', '2020-01-18 02:39:36'),
(17, 1, NULL, '$2y$10$TWe01ER08c4j93F4ktypDOPxjkhAt8NBTF5iAtBHzqH6JBmHXsdtK', 'cable', 1, 3, '2020-01-31', '23:03:00', '23:03:00', '18:03:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-30 21:13:44', '2020-01-30 21:13:44'),
(18, 1, NULL, '$2y$10$h7NqBTri7MWRc3m8dTe2sY8SyF9LQ7ChsSwTEdO7fmYZe7XZO', 'limpieza', 0, 2, '2020-08-30', '14:15:00', '23:15:00', '03:15:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-30 21:17:31', '2020-01-30 21:17:31'),
(26, 19, NULL, '$2y$10$Ln7o9D1aImZfMr8aPKNluLyVudMdeoI3FFMnFdAwc4Al7l1KyUuq', 'cable', 1, 3, '2020-05-31', '13:06:00', '23:06:00', '22:06:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 06:06:59', '2020-01-31 06:06:59'),
(25, 1, NULL, '$2y$10$BbxYzBuDs7SWYaC0nm6HhVS7WHA5mZ40DgCAE6bolpjSHCEn', 'cable', 1, 3, '2020-01-30', '23:50:00', '23:50:00', '23:50:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 05:58:09', '2020-01-31 05:58:09'),
(24, 1, NULL, '$2y$10$lQr6L3lhF14sG7z4y0BMEeFBJJBPbbhg1R5JFhv7c1jPW4qShlhu', 'cable', 1, 3, '2020-01-30', '23:50:00', '23:50:00', '23:50:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 05:57:42', '2020-01-31 05:57:42'),
(23, 1, NULL, '$2y$10$4aPbaV1Wm8Dl0nOFTdlaNedrct8fttTZwXuMz6DASHI2G4Xu5YgHa', 'cable', 1, 3, '2020-01-30', '23:50:00', '23:50:00', '23:50:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 05:57:24', '2020-01-31 05:57:24'),
(27, 19, NULL, '$2y$10$XpbRjTUNG3Q9ppppWuG9COl4I3G0Lhj0J4suGuqz5JrWJymozP1u', 'cable', 1, 3, '2020-05-31', '13:06:00', '23:06:00', '22:06:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 06:07:20', '2020-01-31 06:07:20'),
(28, 19, NULL, '$2y$10$TDHtEBaDVkZBJ2iNugseo6dNoiCQXzu4x60wG7fJk7r6jKfiGFq', 'cable', 1, 3, '2020-05-31', '13:06:00', '23:06:00', '22:06:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 06:07:55', '2020-01-31 06:07:55'),
(29, 19, NULL, '$2y$10$XDjWbbVUzxZVqYHW5qJoOjAMMajZa261coUBsEDIDElHI6Yo9W', 'cable', 1, 3, '2020-05-31', '13:06:00', '23:06:00', '22:06:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 06:08:08', '2020-01-31 06:08:08'),
(30, 19, NULL, '$2y$10$QF0BzF47iqfiZQ2g6vNUFobRTmp23YjEPQQbrTSsprLj2oKT0wi', 'cable', 1, 3, '2020-05-31', '13:06:00', '23:06:00', '22:06:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 06:08:23', '2020-01-31 06:08:23'),
(31, 19, NULL, '$2y$10$Uoi7ynjHaVwTHnK4MRWuGwqaNc7cRZwfHoHoT9lSBKCsImSkx5K', 'cable', 1, 3, '2020-05-31', '13:06:00', '23:06:00', '22:06:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 06:09:27', '2020-01-31 06:09:27'),
(32, 19, NULL, '$2y$10$4GWzC7u2o9K31UbSuh9xpu9vBP7pZ8KdjpQAEMiJoAIVNnWxD6nK', 'cable', 1, 3, '2020-05-31', '13:06:00', '23:06:00', '22:06:00', 0, NULL, 0, 0, NULL, 0, 0, 0, 0, 0, 0, 0, 0, '2020-01-31 06:09:52', '2020-01-31 06:09:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `data` text,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `data`, `status`, `created_at`, `updated_at`) VALUES
(1, 12, 'Cambie la BD', 'Bienvenido a la app de cotos', '', 0, '2020-01-24 08:03:17', '2020-01-24 22:44:34'),
(2, 12, 'Nueva notificacion', 'Bienvenido a la app de cotos', '', 1, '2020-01-24 19:03:24', '2020-01-23 21:03:24'),
(3, 12, 'Nuevo registro', 'Hay un nuevo registro desde tu invitacion, por favor revisalo para aprobarlo o rechazarlo', '4', 0, '2020-01-24 19:12:24', '2020-01-23 21:03:24'),
(4, 21, 'Nueva visita', 'Hay una nueva visita agendada, revísala para que estés entrerado', '1', 0, '2020-01-31 06:09:52', '2020-01-31 06:09:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrations`
--

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE IF NOT EXISTS `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invitation_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `INE_url` text,
  `qr` text,
  `token` text,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `registrations`
--

INSERT INTO `registrations` (`id`, `invitation_id`, `name`, `phone`, `INE_url`, `qr`, `token`, `status`, `created_at`, `updated_at`) VALUES
(1, 15, 'nombre del visitante', '', '', NULL, NULL, 1, '2020-01-18 03:46:08', '2020-01-27 16:04:15'),
(2, 15, 'nombre completo del nuevo', '3343434', '', NULL, NULL, 1, '2020-01-22 02:08:37', '2020-01-27 16:04:23'),
(3, 15, 'nuevo visitante con ife', '3423434', '/uploads/images/20201212009641579637854Hh1ztfI.jpg', NULL, NULL, 0, '2020-01-22 02:17:34', '2020-01-28 22:10:50'),
(4, 15, 'nuev visitante INE', '3234234', '/uploads/images/20201226402261579713639161_coca-cola-355-ml-lata-aluminio.jpg', NULL, NULL, 0, '2020-01-22 23:20:39', '2020-01-24 21:01:30'),
(5, 15, 'Nuevo visitante', '564654654654', NULL, NULL, NULL, 2, '2020-01-24 21:00:23', '2020-01-27 16:04:28'),
(6, 15, 'nuevo visitante notificacion', '654645654654', NULL, NULL, NULL, 0, '2020-01-24 21:01:09', '2020-01-24 21:01:09'),
(7, 15, 'nuevo visitante', '34234324', NULL, NULL, NULL, 0, '2020-01-24 22:26:26', '2020-01-24 20:52:51'),
(8, 15, 'nuevo visitante', '33434', NULL, NULL, NULL, 0, '2020-01-24 22:44:58', '2020-01-24 20:52:35'),
(9, 15, 'nuevo visitante', '324234234', NULL, '/uploads/qrcodes/1d3c7a832b0fd7c0e227a5011f5ac2a4140f6969d5213fd0ece03148e62e461e.png', 'eyJpdiI6ImpMUXZkS0xuWElZSHNITFwvaFdqcVwvdz09IiwidmFsdWUiOiJXdWZyWkpMbGlBU2RBZjBGRDNEXC96Zz09IiwibWFjIjoiZTI3MDM1ZjA2MDA5ZDc0ZGRkMDZiNTA4MjQ0MTViODA5ZmM0NjNlMDhlNjM0N2Q5ZThmMGRkZDgzZmFiNmM5NCJ9', 1, '2020-01-24 22:47:37', '2020-01-30 19:37:54'),
(10, 15, 'nuevo visitante', '324234234', '/uploads/images/20201243041971579884521abid_orig.jpg', NULL, NULL, 0, '2020-01-24 22:48:41', '2020-01-24 22:48:41'),
(11, 15, 'nuevo visitante', '324234234', '/uploads/images/20201245011981579884538abid_orig.jpg', NULL, NULL, 1, '2020-01-24 22:48:58', '2020-01-27 15:59:32'),
(12, 15, 'Juanito', '1233456123', '/uploads/images/20201248818891579891656abid_orig.jpg', '/uploads/qrcodes/1d3c7a832b0fd7c0e227a5011f5ac2a4140f6969d5213fd0ece03148e62e461e.png', 'eyJpdiI6IlRVRE9TblFVTVQ0a29OZUIxUDhoZkE9PSIsInZhbHVlIjoiNzFLK0JBZ1VFdWVVMG5iOFN3RjNVdz09IiwibWFjIjoiYzRjZjM1NGE5N2Q1YzM3NmY3OTRlMjI2MjFhZWQxMjdkNDdjYmViZmE1YmViOThiNDAzYzkwYzAwODUxZDk5YSJ9', 1, '2020-01-25 00:47:36', '2020-01-28 22:21:38'),
(13, 15, 'juanito', '654654654654', NULL, NULL, NULL, 2, '2020-01-25 00:59:01', '2020-01-27 16:04:51'),
(14, 15, 'juanito', '654654654654', NULL, NULL, NULL, 0, '2020-01-25 00:59:06', '2020-01-24 20:37:27'),
(15, 15, 'nuevo visitante', '523335970269', '/uploads/images/20201243889801579898199abid_orig.jpg', '/uploads/qrcodes/1d3c7a832b0fd7c0e227a5011f5ac2a4140f6969d5213fd0ece03148e62e461e.png', 'eyJpdiI6ImtOd0hGOEkwYUFBU2d4YXBxUERiM1E9PSIsInZhbHVlIjoiRGtOOFdSeUdvYk5LQ05zUWVaYXJ4Zz09IiwibWFjIjoiYmM1MGY4MjdhYjhmNTZmZDhmNjhkZjk0MTc2YTAwNzg1Y2Q0NWYwNjU1ZDc4YzY5MGU3MTgzYjNiMDg3YmJlNyJ9', 1, '2020-01-24 20:36:39', '2020-01-30 20:19:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Root', '2020-01-06 06:00:00', '2020-01-06 06:00:00'),
(2, 'Admin', '2020-01-06 06:00:00', '2020-01-06 06:00:00'),
(3, 'Guardia', '2020-01-06 06:00:00', '2020-01-13 06:00:00'),
(4, 'Colono', '2020-01-06 06:00:00', '2020-01-13 06:00:00'),
(5, 'Visitante', '2020-01-27 06:00:00', '2020-01-27 06:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `nss` varchar(20) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `coto_id` int(11) DEFAULT NULL,
  `fcm` text,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `address`, `nss`, `username`, `password`, `role_id`, `coto_id`, `fcm`, `created_at`, `updated_at`) VALUES
(11, 'sdsdddddddddd', '321312', 'casita blanca', NULL, 'secret', '$2y$10$opmQnRM/sFE9IVQdzA7CxeXiEat1y62sgNidmHua7WDcN1h0cU3YG', 1, 2, '', '2020-01-13 21:24:25', '2020-01-31 08:15:58'),
(12, 'prueba', '12344444', 'prueba', 'prueba', 'prueba', '$2y$10$wiuMapk4FPhJDlCtCaTEhesAq2lRlcDwwlh8LRhoHXeBGvuDD23Py', 2, 2, 'eISKamHiv50:APA91bHqufoaSCs2B43GN9Tb9YymzhsPRDHWFMuzsG_yjFEJctrUJtB8T7atR8HpIfZgGeU4HcGoQkqsD3tbFgXyGnNyZDAbmGRmASXAHbhwWx4T9827rwcOrKT656mUUyloOztarUuv', '2020-01-22 11:01:53', '2020-01-22 11:36:18'),
(21, 'Guardia', 'sdhasdhalh', 'sdhaklsdah', 'asdhaksdh', 'guardia', '$2y$10$opmQnRM/sFE9IVQdzA7CxeXiEat1y62sgNidmHua7WDcN1h0cU3YG', 3, 4, 'dc0ix-4wO_8:APA91bFa79i0VaK3Yj-7kQutmNafY5jZ1Wq1vBb5qZ8u0TJxBofJyRp_NJ6y4_6k41Qud1R9ykV_KTOWd5RFeSCqDq-3eepR4k9Jx-dn_QL6CkCek610vrCfReQkCKVT00IJMinfneyf', '2020-01-27 17:34:30', '2020-01-31 05:45:07'),
(19, 'nuevo colono', '3131132131321', 'cotito chiquito 14', NULL, 'colono', '$2y$10$opmQnRM/sFE9IVQdzA7CxeXiEat1y62sgNidmHua7WDcN1h0cU3YG', 4, 4, '', '2020-01-27 17:18:04', '2020-01-31 06:37:44'),
(25, 'nuevo admin prueba final', '1234545666', NULL, NULL, 'prueba final', '$2y$10$6.OBM8JBryi995LP/l400.jDeVxIBQE5gAMeG6yKvCXlSepRRbdqm', 2, 45, NULL, '2020-01-31 08:19:08', '2020-01-31 08:19:08'),
(24, 'nuevo visitante', '523335970269', NULL, NULL, NULL, NULL, 5, NULL, '', '2020-01-30 20:19:00', '2020-01-31 06:59:57'),
(29, 'nuevo usuario final', '23424324', 'asdasd', NULL, '324324324', '$2y$10$INL4x8muJdhhcbbtAM7VJOw6xG/PPfxiQ1qNCXOqKDb7UfaRL2Gim', 4, 46, NULL, '2020-01-31 08:21:10', '2020-01-31 08:21:10'),
(27, 'nuevo guardia final', '324234324', 'sdasdasdsad', '234234', 'prueba final1', '$2y$10$vz1nHlp/Jhj0Ubuv7h54nOg.wEkN51RO6/CDiVmESpip21PkYhuL6', 3, 46, NULL, '2020-01-31 08:20:22', '2020-01-31 08:20:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_coto`
--

DROP TABLE IF EXISTS `user_coto`;
CREATE TABLE IF NOT EXISTS `user_coto` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `coto_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
