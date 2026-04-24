-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para bd_cinema
CREATE DATABASE IF NOT EXISTS `bd_cinema` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `bd_cinema`;

-- Volcando estructura para tabla bd_cinema.asientos
CREATE TABLE IF NOT EXISTS `asientos` (
  `id_asiento` int(11) NOT NULL AUTO_INCREMENT,
  `fila` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `tipo` enum('normal','PMR') NOT NULL DEFAULT 'normal',
  `id_sala` int(11) NOT NULL,
  PRIMARY KEY (`id_asiento`),
  UNIQUE KEY `id_sala` (`id_sala`,`fila`,`numero`),
  CONSTRAINT `fk_asientos_sala` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.asientos: ~48 rows (aproximadamente)
DELETE FROM `asientos`;
INSERT INTO `asientos` (`id_asiento`, `fila`, `numero`, `tipo`, `id_sala`) VALUES
	(1, 1, 1, 'PMR', 1),
	(2, 1, 2, 'PMR', 1),
	(3, 1, 3, 'normal', 1),
	(4, 1, 4, 'normal', 1),
	(5, 1, 5, 'normal', 1),
	(6, 1, 6, 'normal', 1),
	(7, 2, 1, 'normal', 1),
	(8, 2, 2, 'normal', 1),
	(9, 2, 3, 'normal', 1),
	(10, 2, 4, 'normal', 1),
	(11, 2, 5, 'normal', 1),
	(12, 2, 6, 'normal', 1),
	(13, 3, 1, 'normal', 1),
	(14, 3, 2, 'normal', 1),
	(15, 3, 3, 'normal', 1),
	(16, 3, 4, 'normal', 1),
	(17, 3, 5, 'normal', 1),
	(18, 3, 6, 'normal', 1),
	(19, 4, 1, 'normal', 1),
	(20, 4, 2, 'normal', 1),
	(21, 4, 3, 'normal', 1),
	(22, 4, 4, 'normal', 1),
	(23, 4, 5, 'normal', 1),
	(24, 4, 6, 'normal', 1),
	(25, 1, 1, 'PMR', 2),
	(26, 1, 2, 'PMR', 2),
	(27, 1, 3, 'normal', 2),
	(28, 1, 4, 'normal', 2),
	(29, 1, 5, 'normal', 2),
	(30, 1, 6, 'normal', 2),
	(31, 2, 1, 'normal', 2),
	(32, 2, 2, 'normal', 2),
	(33, 2, 3, 'normal', 2),
	(34, 2, 4, 'normal', 2),
	(35, 2, 5, 'normal', 2),
	(36, 2, 6, 'normal', 2),
	(37, 3, 1, 'normal', 2),
	(38, 3, 2, 'normal', 2),
	(39, 3, 3, 'normal', 2),
	(40, 3, 4, 'normal', 2),
	(41, 3, 5, 'normal', 2),
	(42, 3, 6, 'normal', 2),
	(43, 4, 1, 'normal', 2),
	(44, 4, 2, 'normal', 2),
	(45, 4, 3, 'normal', 2),
	(46, 4, 4, 'normal', 2),
	(47, 4, 5, 'normal', 2),
	(48, 4, 6, 'normal', 2);

-- Volcando estructura para tabla bd_cinema.clasificaciones_edad
CREATE TABLE IF NOT EXISTS `clasificaciones_edad` (
  `id_clasificacion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_clasificacion`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.clasificaciones_edad: ~7 rows (aproximadamente)
DELETE FROM `clasificaciones_edad`;
INSERT INTO `clasificaciones_edad` (`id_clasificacion`, `nombre`, `descripcion`) VALUES
	(1, 'TP', 'Apta para todos los públicos'),
	(2, 'TP - Especial infancia', 'Especialmente recomendada para la infancia'),
	(3, '+7', 'No recomendada para menores de siete años'),
	(4, '+12', 'No recomendada para menores de doce años'),
	(5, '+16', 'No recomendada para menores de dieciséis años'),
	(6, '+18', 'No recomendada para menores de dieciocho años'),
	(7, 'Pendiente', 'Pendiente de clasificación');

-- Volcando estructura para tabla bd_cinema.generos
CREATE TABLE IF NOT EXISTS `generos` (
  `id_genero` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_genero`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.generos: ~8 rows (aproximadamente)
DELETE FROM `generos`;
INSERT INTO `generos` (`id_genero`, `nombre`) VALUES
	(1, 'Acción'),
	(2, 'Animación'),
	(3, 'Biopic'),
	(4, 'Ciencia ficción'),
	(5, 'Comedia'),
	(6, 'Comedia dramática'),
	(7, 'Drama'),
	(8, 'Drama romántico');

-- Volcando estructura para tabla bd_cinema.pagos
CREATE TABLE IF NOT EXISTS `pagos` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT,
  `id_reserva` int(11) NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp(),
  `stripe_payment_intent_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id_pago`),
  UNIQUE KEY `stripe_payment_intent_id` (`stripe_payment_intent_id`),
  KEY `fk_pagos_reserva` (`id_reserva`),
  CONSTRAINT `fk_pagos_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.pagos: ~0 rows (aproximadamente)
DELETE FROM `pagos`;

-- Volcando estructura para tabla bd_cinema.peliculas
CREATE TABLE IF NOT EXISTS `peliculas` (
  `id_pelicula` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `sinopsis` varchar(500) NOT NULL,
  `duracion_minutos` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `fecha_estreno` date DEFAULT NULL,
  `id_clasificacion` int(11) NOT NULL,
  PRIMARY KEY (`id_pelicula`),
  KEY `fk_peliculas_clasificacion` (`id_clasificacion`),
  CONSTRAINT `fk_peliculas_clasificacion` FOREIGN KEY (`id_clasificacion`) REFERENCES `clasificaciones_edad` (`id_clasificacion`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.peliculas: ~12 rows (aproximadamente)
DELETE FROM `peliculas`;
INSERT INTO `peliculas` (`id_pelicula`, `titulo`, `sinopsis`, `duracion_minutos`, `imagen`, `fecha_estreno`, `id_clasificacion`) VALUES
	(1, 'Altas capacidades', 'Una comedia dramática sobre jóvenes con talentos excepcionales y las tensiones que eso provoca en su entorno.', 104, 'ALTAS_CAPACIDADES.png', '2026-03-27', 5),
	(2, 'Amarga Navidad', 'Drama familiar ambientado en unas fiestas marcadas por conflictos personales y secretos del pasado.', 111, 'AMARGA_NAVIDAD.png', '2026-03-20', 4),
	(3, 'Hoppers', 'Película de animación centrada en una aventura divertida y accesible para público infantil y familiar.', 104, 'HOPPERS.png', '2026-03-06', 2),
	(4, 'Laponia', 'Comedia sobre un reencuentro familiar en un entorno frío donde salen a la luz diferencias culturales y personales.', 89, 'LAPONIA.png', '2026-04-01', 4),
	(5, 'Noche de bodas 2', 'Secuela de ritmo acelerado con acción, caos y un evento nupcial que termina fuera de control.', 107, 'NOCHE_dE_BODAS_2.png', '2026-04-01', 6),
	(6, 'Super Mario Galaxy, la película', 'Aventura animada inspirada en el universo galáctico de Mario, con humor y exploración espacial.', 98, 'SUPER_MARIO.png', '2026-04-01', 3),
	(7, 'Torrente Presidente', 'Comedia irreverente protagonizada por un candidato improbable que desata situaciones absurdas.', 102, 'TORRENTE_PRESIDENTE.png', '2026-03-13', 5),
	(8, 'Boulevard', 'Drama centrado en relaciones rotas y decisiones difíciles en una ciudad marcada por el desencanto.', 114, 'PROXIMOS EXTRENOS/BOULEVARD_CARTEL.png', '2026-04-10', 5),
	(9, 'No te olvidaré', 'Drama romántico sobre memoria, pérdida y la persistencia del amor a través del tiempo.', 114, 'NO_TE_OLVIDARE.png', '2026-04-10', 4),
	(10, 'El diablo viste de Prada 2', 'Comedia sobre moda, poder y relaciones profesionales en un entorno competitivo y elegante.', 120, 'PROXIMOS EXTRENOS/EL_DIABLO_VISTE_DE_PRADA_2_CARTEL.png', '2026-04-30', 7),
	(11, 'Michael', 'Biopic basado en la trayectoria vital y artística de una figura musical de enorme impacto internacional.', 127, 'PROXIMOS EXTRENOS/MICHAEL_CARTEL.png', '2026-04-22', 7),
	(12, 'Proyecto Salvación', 'Ciencia ficción sobre una misión desesperada que busca preservar a la humanidad frente a una amenaza global.', 156, 'PROXIMOS EXTRENOS/PROYECTO_SALVACION_CARTEL.png', '2026-04-09', 4);

-- Volcando estructura para tabla bd_cinema.pelicula_genero
CREATE TABLE IF NOT EXISTS `pelicula_genero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pelicula` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_pelicula` (`id_pelicula`,`id_genero`),
  KEY `fk_pelicula_genero_genero` (`id_genero`),
  CONSTRAINT `fk_pelicula_genero_genero` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id_genero`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pelicula_genero_pelicula` FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas` (`id_pelicula`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.pelicula_genero: ~12 rows (aproximadamente)
DELETE FROM `pelicula_genero`;
INSERT INTO `pelicula_genero` (`id`, `id_pelicula`, `id_genero`) VALUES
	(1, 1, 6),
	(2, 2, 7),
	(3, 3, 2),
	(4, 4, 5),
	(5, 5, 1),
	(6, 6, 2),
	(7, 7, 5),
	(8, 8, 7),
	(9, 9, 8),
	(10, 10, 5),
	(11, 11, 3),
	(12, 12, 4);

-- Volcando estructura para tabla bd_cinema.reservas
CREATE TABLE IF NOT EXISTS `reservas` (
  `id_reserva` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_sesion` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`id_reserva`),
  KEY `fk_reservas_usuario` (`id_usuario`),
  KEY `fk_reservas_sesion` (`id_sesion`),
  CONSTRAINT `fk_reservas_sesion` FOREIGN KEY (`id_sesion`) REFERENCES `sesiones` (`id_sesion`) ON UPDATE CASCADE,
  CONSTRAINT `fk_reservas_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.reservas: ~0 rows (aproximadamente)
DELETE FROM `reservas`;

-- Volcando estructura para tabla bd_cinema.reserva_asientos
CREATE TABLE IF NOT EXISTS `reserva_asientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_reserva` int(11) NOT NULL,
  `id_asiento` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_reserva` (`id_reserva`,`id_asiento`),
  KEY `fk_reserva_asientos_asiento` (`id_asiento`),
  CONSTRAINT `fk_reserva_asientos_asiento` FOREIGN KEY (`id_asiento`) REFERENCES `asientos` (`id_asiento`) ON UPDATE CASCADE,
  CONSTRAINT `fk_reserva_asientos_reserva` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.reserva_asientos: ~0 rows (aproximadamente)
DELETE FROM `reserva_asientos`;

-- Volcando estructura para tabla bd_cinema.salas
CREATE TABLE IF NOT EXISTS `salas` (
  `id_sala` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  PRIMARY KEY (`id_sala`),
  UNIQUE KEY `numero` (`numero`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.salas: ~2 rows (aproximadamente)
DELETE FROM `salas`;
INSERT INTO `salas` (`id_sala`, `numero`, `capacidad`) VALUES
	(1, 1, 24),
	(2, 2, 24);

-- Volcando estructura para tabla bd_cinema.sesiones
CREATE TABLE IF NOT EXISTS `sesiones` (
  `id_sesion` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_hora` datetime NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `id_pelicula` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  PRIMARY KEY (`id_sesion`),
  KEY `fk_sesiones_pelicula` (`id_pelicula`),
  KEY `fk_sesiones_sala` (`id_sala`),
  CONSTRAINT `fk_sesiones_pelicula` FOREIGN KEY (`id_pelicula`) REFERENCES `peliculas` (`id_pelicula`) ON UPDATE CASCADE,
  CONSTRAINT `fk_sesiones_sala` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.sesiones: ~9 rows (aproximadamente)
DELETE FROM `sesiones`;
INSERT INTO `sesiones` (`id_sesion`, `fecha_hora`, `precio`, `id_pelicula`, `id_sala`) VALUES
	(1, '2026-04-25 17:00:00', 7.50, 4, 1),
	(2, '2026-04-25 20:00:00', 8.50, 12, 2),
	(3, '2026-04-25 22:30:00', 8.00, 5, 1),
	(4, '2026-04-26 16:30:00', 6.50, 3, 2),
	(5, '2026-04-26 19:00:00', 7.50, 1, 1),
	(6, '2026-04-26 21:30:00', 7.50, 2, 2),
	(7, '2026-04-27 18:00:00', 7.00, 6, 1),
	(8, '2026-04-27 20:30:00', 7.50, 7, 2),
	(9, '2026-04-27 22:45:00', 8.00, 8, 1);

-- Volcando estructura para tabla bd_cinema.sesion_asientos
CREATE TABLE IF NOT EXISTS `sesion_asientos` (
  `id_sesion_asiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_sesion` int(11) NOT NULL,
  `id_asiento` int(11) NOT NULL,
  `estado` enum('libre','en_proceso','ocupado') NOT NULL DEFAULT 'libre',
  `bloqueado_hasta` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_sesion_asiento`),
  UNIQUE KEY `uk_sesion_asiento` (`id_sesion`,`id_asiento`),
  KEY `idx_sesion_estado` (`id_sesion`,`estado`),
  KEY `fk_sesion_asientos_asiento` (`id_asiento`),
  CONSTRAINT `fk_sesion_asientos_asiento` FOREIGN KEY (`id_asiento`) REFERENCES `asientos` (`id_asiento`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sesion_asientos_sesion` FOREIGN KEY (`id_sesion`) REFERENCES `sesiones` (`id_sesion`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=273 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.sesion_asientos: ~216 rows (aproximadamente)
DELETE FROM `sesion_asientos`;
INSERT INTO `sesion_asientos` (`id_sesion_asiento`, `id_sesion`, `id_asiento`, `estado`, `bloqueado_hasta`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(2, 1, 2, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(3, 1, 3, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(4, 1, 4, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(5, 1, 5, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(6, 1, 6, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(7, 1, 7, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(8, 1, 8, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(9, 1, 9, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(10, 1, 10, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(11, 1, 11, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(12, 1, 12, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(13, 1, 13, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(14, 1, 14, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(15, 1, 15, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(16, 1, 16, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(17, 1, 17, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(18, 1, 18, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(19, 1, 19, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(20, 1, 20, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(21, 1, 21, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(22, 1, 22, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(23, 1, 23, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(24, 1, 24, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(32, 2, 25, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(33, 2, 26, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(34, 2, 27, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(35, 2, 28, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(36, 2, 29, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(37, 2, 30, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(38, 2, 31, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(39, 2, 32, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(40, 2, 33, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(41, 2, 34, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(42, 2, 35, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(43, 2, 36, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(44, 2, 37, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(45, 2, 38, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(46, 2, 39, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(47, 2, 40, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(48, 2, 41, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(49, 2, 42, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(50, 2, 43, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(51, 2, 44, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(52, 2, 45, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(53, 2, 46, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(54, 2, 47, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(55, 2, 48, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(63, 3, 1, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(64, 3, 2, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(65, 3, 3, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(66, 3, 4, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(67, 3, 5, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(68, 3, 6, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(69, 3, 7, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(70, 3, 8, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(71, 3, 9, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(72, 3, 10, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(73, 3, 11, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(74, 3, 12, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(75, 3, 13, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(76, 3, 14, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(77, 3, 15, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(78, 3, 16, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(79, 3, 17, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(80, 3, 18, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(81, 3, 19, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(82, 3, 20, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(83, 3, 21, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(84, 3, 22, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(85, 3, 23, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(86, 3, 24, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(94, 4, 25, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(95, 4, 26, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(96, 4, 27, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(97, 4, 28, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(98, 4, 29, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(99, 4, 30, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(100, 4, 31, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(101, 4, 32, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(102, 4, 33, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(103, 4, 34, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(104, 4, 35, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(105, 4, 36, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(106, 4, 37, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(107, 4, 38, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(108, 4, 39, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(109, 4, 40, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(110, 4, 41, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(111, 4, 42, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(112, 4, 43, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(113, 4, 44, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(114, 4, 45, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(115, 4, 46, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(116, 4, 47, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(117, 4, 48, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(125, 5, 1, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(126, 5, 2, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(127, 5, 3, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(128, 5, 4, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(129, 5, 5, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(130, 5, 6, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(131, 5, 7, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(132, 5, 8, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(133, 5, 9, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(134, 5, 10, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(135, 5, 11, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(136, 5, 12, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(137, 5, 13, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(138, 5, 14, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(139, 5, 15, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(140, 5, 16, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(141, 5, 17, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(142, 5, 18, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(143, 5, 19, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(144, 5, 20, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(145, 5, 21, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(146, 5, 22, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(147, 5, 23, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(148, 5, 24, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(156, 6, 25, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(157, 6, 26, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(158, 6, 27, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(159, 6, 28, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(160, 6, 29, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(161, 6, 30, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(162, 6, 31, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(163, 6, 32, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(164, 6, 33, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(165, 6, 34, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(166, 6, 35, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(167, 6, 36, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(168, 6, 37, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(169, 6, 38, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(170, 6, 39, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(171, 6, 40, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(172, 6, 41, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(173, 6, 42, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(174, 6, 43, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(175, 6, 44, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(176, 6, 45, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(177, 6, 46, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(178, 6, 47, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(179, 6, 48, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(187, 7, 1, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(188, 7, 2, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(189, 7, 3, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(190, 7, 4, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(191, 7, 5, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(192, 7, 6, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(193, 7, 7, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(194, 7, 8, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(195, 7, 9, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(196, 7, 10, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(197, 7, 11, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(198, 7, 12, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(199, 7, 13, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(200, 7, 14, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(201, 7, 15, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(202, 7, 16, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(203, 7, 17, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(204, 7, 18, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(205, 7, 19, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(206, 7, 20, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(207, 7, 21, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(208, 7, 22, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(209, 7, 23, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(210, 7, 24, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(218, 8, 25, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(219, 8, 26, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(220, 8, 27, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(221, 8, 28, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(222, 8, 29, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(223, 8, 30, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(224, 8, 31, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(225, 8, 32, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(226, 8, 33, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(227, 8, 34, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(228, 8, 35, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(229, 8, 36, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(230, 8, 37, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(231, 8, 38, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(232, 8, 39, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(233, 8, 40, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(234, 8, 41, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(235, 8, 42, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(236, 8, 43, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(237, 8, 44, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(238, 8, 45, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(239, 8, 46, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(240, 8, 47, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(241, 8, 48, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(249, 9, 1, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(250, 9, 2, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(251, 9, 3, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(252, 9, 4, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(253, 9, 5, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(254, 9, 6, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(255, 9, 7, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(256, 9, 8, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(257, 9, 9, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(258, 9, 10, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(259, 9, 11, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(260, 9, 12, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(261, 9, 13, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(262, 9, 14, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(263, 9, 15, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(264, 9, 16, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(265, 9, 17, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(266, 9, 18, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(267, 9, 19, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(268, 9, 20, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(269, 9, 21, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(270, 9, 22, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(271, 9, 23, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17'),
	(272, 9, 24, 'libre', NULL, '2026-04-24 04:03:17', '2026-04-24 04:03:17');

-- Volcando estructura para tabla bd_cinema.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `firebase_uid` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'cliente',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `firebase_uid` (`firebase_uid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.usuarios: ~0 rows (aproximadamente)
DELETE FROM `usuarios`;

-- Volcando estructura para disparador bd_cinema.trg_asientos_crear_estado_en_sesiones
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER trg_asientos_crear_estado_en_sesiones
AFTER INSERT ON asientos
FOR EACH ROW
BEGIN
    INSERT INTO sesion_asientos (id_sesion, id_asiento, estado)
    SELECT s.id_sesion, NEW.id_asiento, 'libre'
    FROM sesiones s
    WHERE s.id_sala = NEW.id_sala;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Volcando estructura para disparador bd_cinema.trg_sesiones_crear_estado_asientos
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER trg_sesiones_crear_estado_asientos
AFTER INSERT ON sesiones
FOR EACH ROW
BEGIN
    INSERT INTO sesion_asientos (id_sesion, id_asiento, estado)
    SELECT NEW.id_sesion, a.id_asiento, 'libre'
    FROM asientos a
    WHERE a.id_sala = NEW.id_sala;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
