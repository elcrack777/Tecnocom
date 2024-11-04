-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-11-2024 a las 06:27:46
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
  `status` enum('Pendiente','En Progreso','Atendido') DEFAULT 'Pendiente',
  `motivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pendientes`
--

INSERT INTO `pendientes` (`id`, `fecha_creacion`, `nombre_tecnico`, `cliente`, `fecha_atencion`, `fecha_reasignacion`, `status`, `motivo`) VALUES
(1, '2024-11-03', 'Mayra Patricia', 'LP', '2024-11-09', '0000-00-00', 'Pendiente', NULL),
(2, '2024-11-03', 'Mayra Patricia', 'LP', '2024-11-28', '0000-00-00', 'Atendido', 'Deee'),
(3, '2024-11-03', 'Mayra Patricia', 'LP', '2024-11-07', '0000-00-00', 'En Progreso', NULL),
(5, '2024-11-03', 'Mayra Patricia', 'RS', '2024-11-26', '2024-11-30', 'Pendiente', 'Si a mi y a mis amigos'),
(6, '2024-11-03', 'Mayra Patricia', 'Kreston', '2024-11-15', '0000-00-00', 'Pendiente', 'Siuuu'),
(7, '2024-11-03', 'Mayra Patricia', 'Rocava', '2024-11-28', '0000-00-00', 'En Progreso', 'Falta 3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Admin'),
(2, 'Tecnico'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `rol_id` int(11) NOT NULL,
  `fotografia` varchar(255) DEFAULT NULL,
  `contraseña` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `telefono`, `rol_id`, `fotografia`, `contraseña`, `fecha_creacion`) VALUES
(1, 'Gabriel Carrillo', 'helpdesk@tecnocom.com.mx', '34568678761', 1, 'Img/Users/rocket.png', '$2y$10$8JTss5iC9cTgBI4bWuFgPeDxwDXEGVuyZJTXbzwVgJ29W5q14nwo.', '2024-11-03 19:36:53'),
(2, 'Mayra Patricia', 'gabrielr833@gmail.com', '8115656261877', 2, 'Img/Users/rocket.png', '$2y$10$Sbv5KhWGw9pcddLfo3fFQ.Q92I6fZn6bwnvdrLAubwj9lk.FjGEMS', '2024-11-03 19:49:36');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pendientes`
--
ALTER TABLE `pendientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
