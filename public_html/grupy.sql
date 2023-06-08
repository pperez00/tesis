-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2023 a las 22:29:59
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `grupy`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos_grupo`
--

CREATE TABLE `archivos_grupo` (
  `id` int(11) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `grupo` varchar(60) NOT NULL,
  `usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `archivos_grupo`
--

INSERT INTO `archivos_grupo` (`id`, `archivo`, `grupo`, `usuario`) VALUES
(30, 'pablo perez.pdf', '646a7fdd2d77e', 18),
(31, 'pablo perez_646a7fdd2d77e.pdf', '646a7fdd2d77e', 18),
(32, 'pablo perez_646e79a5276ae.pdf', '646e79a5276ae', 18),
(33, 'pablo perez_646bb99deabad.pdf', '646bb99deabad', 20),
(34, 'pablo_perez_647667238cb39.pdf', '647667238cb39', 18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprados`
--

CREATE TABLE `comprados` (
  `id` int(11) NOT NULL,
  `id_mercado` varchar(255) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comprados`
--

INSERT INTO `comprados` (`id`, `id_mercado`, `id_usuario`, `estado`) VALUES
(2, '1313124256', 18, 'pending'),
(3, '1313280754', 1, 'pending');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_tareas`
--

CREATE TABLE `estado_tareas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `todos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_tareas`
--

INSERT INTO `estado_tareas` (`id`, `nombre`, `todos`) VALUES
(1, 'Incompleta', 0),
(2, 'Comenzada', 1),
(3, 'Terminada', 0),
(4, 'No comenzada', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `miembro` int(11) NOT NULL,
  `id_chat_grupo` varchar(60) NOT NULL,
  `nombre` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `usuario`, `miembro`, `id_chat_grupo`, `nombre`) VALUES
(38, 18, 20, '6476bdad61fac', 'Grupo 1'),
(39, 18, 19, '6476bdc7a1a10', 'Grupo 2'),
(41, 25, 19, '647e2e2cae3fe', 'grupo nuevo'),
(44, 1, 25, '647e3014c9810', 'grupo admin'),
(46, 1, 19, '647e303f952f6', 'grupo admin 2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `id_grupo` varchar(60) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `Estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id`, `usuario`, `id_grupo`, `nombre`, `Estado`) VALUES
(10000003, 19, '647667238cb39', 'tarea 2', 3),
(10000004, 18, '647667238cb39', 'tarea 1', 1),
(10000008, 19, '6476bdc7a1a10', 'otra tarea', 3),
(10000013, 20, '6476bdad61fac', 'us1 tarea 1', 4),
(10000014, 20, '6476bdad61fac', 'us 1 tarea 2', 4),
(10000015, 18, '6476bdad61fac', 'pablo tarea 1', 4),
(10000016, 18, '6476bdad61fac', 'pablo tarea 2', 4),
(10000017, 19, '6476bdc7a1a10', 'gr2 prueba tarea 1', 4),
(10000019, 25, '647e2e2cae3fe', 'tarea 2', 3),
(10000020, 19, '647e2e2cae3fe', 'tarea 1', 4),
(10000021, 25, '647e3014c9810', 'tarea 1', 4),
(10000022, 25, '647e3014c9810', 'tarea 2', 4),
(10000023, 19, '647e303f952f6', 'tarea 2', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `usuario` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `pass` varchar(60) NOT NULL,
  `permiso` int(11) NOT NULL DEFAULT 2,
  `foto` varchar(60) NOT NULL,
  `premium` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `email`, `pass`, `permiso`, `foto`, `premium`) VALUES
(1, 'elizabeth', 'admin', 'elizabeth@gmail.com', '$2y$10$tV7fSPOCBlaQ.9DlG6.fquMawLoT/ZNpGsIFf1ZtPor.cuI7pwjrW', 1, 'meme06.jpg', 1),
(18, 'Pablo', 'pperez', 'fdsaf@dsa.com', '$2y$10$lOKtggH4PjV6MzGJBVuNhuZfUu8Cr22SLA2m6xmDfc54qpO2CA/hC', 2, '20211230_180035 (3).jpg', 1),
(19, 'prueba', 'prueba', 'prueba@hotmail.com', '$2y$10$.HVuS2bIuuzgIUumCT3qvuI8.S5UKecP2dYqlNZf6KiaVWkXUH0VC', 2, '20211230_180040.jpg', 0),
(20, 'usuario 1', 'usuario1', 'usuario1@hotmail.ccom', '$2y$10$jiloFjpBRbKFWqAXlMUwlOTc9DRYEU5Jk2PDi3bJS4od11FNh5nC2', 2, 'pablo.jpg', 0),
(22, 'fgds', 'user3', 'gfds@ds.com', '$2y$10$Jwua5ApUoEwKqCYy9PwsZOzl4I3yEHkYR4SgXs5Yk3KO9YAREZemC', 2, '20211230_180042.jpg', 0),
(23, 'us4', 'user4', 'jlhk@h.com', '$2y$10$MSPe9eHNIsjSmv4fE5GFouMJaEZJZRe54fp8UrYI2A/EGYNk6M8g2', 2, '20211117_0001.png', 0),
(24, 'us5', 'user5', 'jlhk@hj.com', '$2y$10$iJLgufv5q/Z3kGzlwBURCOOfPfcjep0ZSOpnm/do3tCI83WbLegAO', 2, '20211117_0001.png', 0),
(25, 'ultimo', 'ultimo', 'fasd@fads.com', '$2y$10$ptKUBRrzWgz8YoklMrXzj.xanD9h8Rj4Gr7RnbhE8gTulCJjmY2Kq', 2, 'WallpaperStudio10-179150-1920x1080.jpg', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivos_grupo`
--
ALTER TABLE `archivos_grupo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `comprados`
--
ALTER TABLE `comprados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_fk` (`id_usuario`);

--
-- Indices de la tabla `estado_tareas`
--
ALTER TABLE `estado_tareas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `archivos_grupo`
--
ALTER TABLE `archivos_grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `comprados`
--
ALTER TABLE `comprados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_tareas`
--
ALTER TABLE `estado_tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000025;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comprados`
--
ALTER TABLE `comprados`
  ADD CONSTRAINT `id_usuario_fk` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
