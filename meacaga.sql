-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-10-2015 a las 13:20:40
-- Versión del servidor: 10.0.17-MariaDB
-- Versión de PHP: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `meacaga`
--
DROP DATABASE IF EXISTS `meacaga`;
CREATE DATABASE IF NOT EXISTS `meacaga` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `meacaga`;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Places`
--

CREATE TABLE `places` (
  `place_id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(200) NOT NULL DEFAULT ' ',
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT ' ',
  `description` varchar(1000) NOT NULL DEFAULT ' ',
  `author` varchar(200) NOT NULL DEFAULT ' ',
  `email` varchar(200) NOT NULL DEFAULT ' ',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`place_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `comment` varchar(1000) NOT NULL DEFAULT ' ',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`comment_id`),
  INDEX (`place_id`),
  CONSTRAINT FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Score`
--

CREATE TABLE `scores` (
  `score_id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `paper` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `wait_time` int(11) NOT NULL,
  `cleanliness` int(11) NOT NULL,
  `smell` int(11) NOT NULL,
  PRIMARY KEY (`score_id`),
  INDEX (`place_id`),
  CONSTRAINT FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Photos`
--

CREATE TABLE `photos` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `path` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`photo_id`),
  INDEX (`place_id`),
  CONSTRAINT FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



--
-- Metadatos
--
USE `phpmyadmin`;


--
-- Volcado de datos para las tablas
--

INSERT INTO `meacaga`.`places` (`place_id`, `address`, `lat`, `lng`, `name`, `description`, `author`, `email`, `date`) VALUES
(1, 'Calle Rodillo, 16, 37001 Salamanca, Salamanca, EspaÃ±a', 40.9624, -5.65992, 'El gabri', 'baÃ±o', 'jesus', 'jeuss@jeusus.es', '2015-06-25 20:51:43'),
(2, 'Calle Padilleros, 17, 37002 Salamanca, Salamanca, EspaÃ±a', 40.9684, -5.66221, '', '', ' ', ' ', '2015-06-26 01:36:56');


INSERT INTO `meacaga`.`comments` (`comment_id`, `place_id`, `comment`, `date`) VALUES
(1, 1, 'sdfsdf', '2015-06-26 09:24:39'),
(2, 1, 'alksjd', '2015-06-26 09:24:39'),
(3, 1, 'lkasd', '2015-06-26 10:01:20'),
(4, 2, 'aaaaaaa', '2015-06-26 10:06:05'),
(5, 2, 'bbbbbbbb', '2015-06-26 10:06:57');


INSERT INTO `meacaga`.`scores` (`score_id`, `place_id`, `paper`, `size`, `wait_time`, `cleanliness`, `smell`) VALUES
(1, 1, 3, 5, 2, 3, 5),
(5, 2, 3, 5, 1, 1, 1),
(6, 2, 3, 5, 1, 1, 1),
(7, 2, 3, 5, 1, 1, 0),
(8, 2, 3, 5, 1, 1, 0),
(9, 2, 3, 5, 1, 1, 0),
(10, 2, 3, 5, 1, 1, 0),
(11, 2, 3, 5, 1, 1, 0);