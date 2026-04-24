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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.asientos: ~0 rows (aproximadamente)
DELETE FROM `asientos`;

-- Volcando estructura para tabla bd_cinema.clasificaciones_edad
CREATE TABLE IF NOT EXISTS `clasificaciones_edad` (
  `id_clasificacion` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_clasificacion`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.clasificaciones_edad: ~0 rows (aproximadamente)
DELETE FROM `clasificaciones_edad`;

-- Volcando estructura para tabla bd_cinema.generos
CREATE TABLE IF NOT EXISTS `generos` (
  `id_genero` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_genero`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.generos: ~0 rows (aproximadamente)
DELETE FROM `generos`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.peliculas: ~0 rows (aproximadamente)
DELETE FROM `peliculas`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.pelicula_genero: ~0 rows (aproximadamente)
DELETE FROM `pelicula_genero`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.salas: ~0 rows (aproximadamente)
DELETE FROM `salas`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.sesiones: ~0 rows (aproximadamente)
DELETE FROM `sesiones`;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bd_cinema.sesion_asientos: ~0 rows (aproximadamente)
DELETE FROM `sesion_asientos`;

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
