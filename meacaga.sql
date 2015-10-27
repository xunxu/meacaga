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

CREATE TABLE `Places` (
  `PlaceId` int(11) NOT NULL AUTO_INCREMENT,
  `Address` varchar(200) NOT NULL DEFAULT ' ',
  `Lat` float NOT NULL,
  `Lng` float NOT NULL,
  `PlaceName` varchar(200) NOT NULL DEFAULT ' ',
  `Description` varchar(1000) NOT NULL DEFAULT ' ',
  `Paper` int(11) NOT NULL DEFAULT '1',
  `Size` int(11) NOT NULL DEFAULT '2',
  `WaitTime` int(11) NOT NULL DEFAULT '2',
  `Cleanliness` int(11) NOT NULL DEFAULT '2',
  `Smell` int(11) NOT NULL DEFAULT '2',
  `Author` varchar(200) NOT NULL DEFAULT ' ',
  `Email` varchar(200) NOT NULL DEFAULT ' ',
  `Date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PlaceId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Comments`
--

CREATE TABLE `Comments` (
  `CommentId` int(11) NOT NULL AUTO_INCREMENT,
  `PlaceId` int(11) NOT NULL,
  `Comment` varchar(1000) NOT NULL DEFAULT ' ',
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`CommentId`),
  INDEX (`PlaceId`),
  CONSTRAINT FOREIGN KEY (`PlaceId`) REFERENCES `Places` (`PlaceId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Photos`
--

CREATE TABLE `Photos` (
  `PhotoId` int(11) NOT NULL AUTO_INCREMENT,
  `PlaceId` int(11) NOT NULL,
  `Path` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`PhotoId`),
  INDEX (`PlaceId`),
  CONSTRAINT FOREIGN KEY (`PlaceId`) REFERENCES `Places` (`PlaceId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Score`
--

CREATE TABLE `Score` (
  `ScoreId` int(11) NOT NULL AUTO_INCREMENT,
  `PlaceId` int(11) NOT NULL,
  `Paper` int(11) NOT NULL,
  `Size` int(11) NOT NULL,
  `WaitTime` int(11) NOT NULL,
  `Cleanliness` int(11) NOT NULL,
  `Smell` int(11) NOT NULL,
  PRIMARY KEY (`ScoreId`),
  INDEX (`PlaceId`),
  CONSTRAINT FOREIGN KEY (`PlaceId`) REFERENCES `Places` (`PlaceId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Metadatos
--
USE `phpmyadmin`;


--
-- Volcado de datos para las tablas
--

INSERT INTO `meacaga`.`Places` (`PlaceId`, `Address`, `Lat`, `Lng`, `PlaceName`, `Description`, `Paper`, `Size`, `WaitTime`, `Cleanliness`, `Smell`, `Author`, `Email`, `Date`) VALUES
(1, 'Calle Rodillo, 16, 37001 Salamanca, Salamanca, EspaÃ±a', 40.9624, -5.65992, 'El gabri', 'baÃ±o', 1, 2, 2, 2, 2, 'jesus', 'jeuss@jeusus.es', '2015-06-25 20:51:43'),
(2, 'Calle Padilleros, 17, 37002 Salamanca, Salamanca, EspaÃ±a', 40.9684, -5.66221, '', '', 1, 2, 2, 2, 2, ' ', ' ', '2015-06-26 01:36:56');


INSERT INTO `meacaga`.`Comments` (`CommentId`, `PlaceId`, `Comment`, `Date`) VALUES
(1, 1, 'sdfsdf', '2015-06-26 09:24:39'),
(2, 1, 'alksjd', '2015-06-26 09:24:39'),
(3, 1, 'lkasd', '2015-06-26 10:01:20'),
(4, 2, 'aaaaaaa', '2015-06-26 10:06:05'),
(5, 2, 'bbbbbbbb', '2015-06-26 10:06:57');


INSERT INTO `meacaga`.`Score` (`ScoreId`, `PlaceId`, `Paper`, `Size`, `WaitTime`, `Cleanliness`, `Smell`) VALUES
(1, 1, 3, 5, 2, 3, 5),
(5, 2, 3, 5, 1, 1, 1),
(6, 2, 3, 5, 1, 1, 1),
(7, 2, 3, 5, 1, 1, 0),
(8, 2, 3, 5, 1, 1, 0),
(9, 2, 3, 5, 1, 1, 0),
(10, 2, 3, 5, 1, 1, 0),
(11, 2, 3, 5, 1, 1, 0);