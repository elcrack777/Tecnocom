-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-11-2024 a las 03:43:22
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
-- Base de datos: `gestionpendientes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pendientes`
--

CREATE TABLE `pendientes` (
  `id` int(11) NOT NULL,
  `fecha_creacion` date DEFAULT curdate(),
  `nombre_tecnico` varchar(100) NOT NULL,
  `cliente` varchar(100) NOT NULL,
  `fecha_atencion` date DEFAULT NULL,
  `fecha_reasignacion` date DEFAULT NULL,
  `status` enum('Pendiente','En Progreso','Atendido') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pendientes`
--

INSERT INTO `pendientes` (`id`, `fecha_creacion`, `nombre_tecnico`, `cliente`, `fecha_atencion`, `fecha_reasignacion`, `status`) VALUES
(1, '2024-11-03', 'Mayra Patricia', 'LP', '2024-11-09', '0000-00-00', 'Pendiente'),
(2, '2024-11-03', 'Mayra Patricia', 'LP', '2024-11-28', '0000-00-00', 'Pendiente'),
(3, '2024-11-03', 'Mayra Patricia', 'LP', '2024-11-07', '0000-00-00', 'En Progreso'),
(5, '2024-11-03', 'Mayra Patricia', 'RS', '2024-11-26', '2024-11-30', 'Atendido');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
