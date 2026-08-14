-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-08-2026 a las 03:12:40
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
-- Base de datos: `agenda_escolar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivos_publicacion`
--

CREATE TABLE `archivos_publicacion` (
  `id` int(11) NOT NULL,
  `publicacion_id` int(11) NOT NULL,
  `nombre_original` varchar(255) NOT NULL,
  `nombre_guardado` varchar(255) NOT NULL,
  `ruta_archivo` varchar(500) NOT NULL,
  `extension` varchar(20) DEFAULT NULL,
  `tamano` bigint(20) DEFAULT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`id`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Matemáticas', NULL, 'ACTIVO'),
(2, 'Lengua Castellana', NULL, 'ACTIVO'),
(3, 'Inglés', NULL, 'ACTIVO'),
(4, 'Ciencias Naturales', NULL, 'ACTIVO'),
(5, 'Ciencias Sociales', NULL, 'ACTIVO'),
(6, 'Educación Física', NULL, 'ACTIVO'),
(7, 'Tecnología e Informática', NULL, 'ACTIVO'),
(8, 'Ética y Valores', NULL, 'ACTIVO'),
(9, 'Religión', NULL, 'ACTIVO'),
(10, 'Artística', NULL, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_sistema`
--

CREATE TABLE `configuracion_sistema` (
  `id` int(11) NOT NULL,
  `nombre_institucion` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `anio_lectivo` varchar(10) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion_sistema`
--

INSERT INTO `configuracion_sistema` (`id`, `nombre_institucion`, `logo`, `anio_lectivo`, `fecha_creacion`) VALUES
(1, 'Institución Educativa', 'logo.png', '2026', '2026-08-14 01:08:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `descripcion`, `estado`) VALUES
(1, '601', NULL, 'ACTIVO'),
(2, '602', NULL, 'ACTIVO'),
(3, '603', NULL, 'ACTIVO'),
(4, '604', NULL, 'ACTIVO'),
(5, '605', NULL, 'ACTIVO'),
(6, '606', NULL, 'ACTIVO'),
(7, '607', NULL, 'ACTIVO'),
(8, '608', NULL, 'ACTIVO'),
(9, '701', NULL, 'ACTIVO'),
(10, '702', NULL, 'ACTIVO'),
(11, '703', NULL, 'ACTIVO'),
(12, '704', NULL, 'ACTIVO'),
(13, '705', NULL, 'ACTIVO'),
(14, '706', NULL, 'ACTIVO'),
(15, '707', NULL, 'ACTIVO'),
(16, '708', NULL, 'ACTIVO'),
(17, '801', NULL, 'ACTIVO'),
(18, '802', NULL, 'ACTIVO'),
(19, '803', NULL, 'ACTIVO'),
(20, '804', NULL, 'ACTIVO'),
(21, '805', NULL, 'ACTIVO'),
(22, '806', NULL, 'ACTIVO'),
(23, '901', NULL, 'ACTIVO'),
(24, '902', NULL, 'ACTIVO'),
(25, '903', NULL, 'ACTIVO'),
(26, '904', NULL, 'ACTIVO'),
(27, '905', NULL, 'ACTIVO'),
(28, '1001', NULL, 'ACTIVO'),
(29, '1002', NULL, 'ACTIVO'),
(30, '1003', NULL, 'ACTIVO'),
(31, '1004', NULL, 'ACTIVO'),
(32, '1005', NULL, 'ACTIVO'),
(33, '1101', NULL, 'ACTIVO'),
(34, '1102', NULL, 'ACTIVO'),
(35, '1103', NULL, 'ACTIVO'),
(36, '1104', NULL, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docente_asignacion`
--

CREATE TABLE `docente_asignacion` (
  `id` int(11) NOT NULL,
  `docente_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `periodo_id` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entregas`
--

CREATE TABLE `entregas` (
  `id` int(11) NOT NULL,
  `publicacion_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_entrega` datetime NOT NULL DEFAULT current_timestamp(),
  `calificacion` decimal(4,2) DEFAULT NULL,
  `observacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_curso`
--

CREATE TABLE `estudiante_curso` (
  `id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `periodo_id` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_password`
--

CREATE TABLE `historial_password` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lectura_publicaciones`
--

CREATE TABLE `lectura_publicaciones` (
  `id` int(11) NOT NULL,
  `publicacion_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `fecha_lectura` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_accesos`
--

CREATE TABLE `log_accesos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_ingreso` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(50) DEFAULT NULL,
  `navegador` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_academicos`
--

CREATE TABLE `periodos_academicos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `periodos_academicos`
--

INSERT INTO `periodos_academicos` (`id`, `nombre`, `fecha_inicio`, `fecha_fin`, `estado`, `fecha_creacion`) VALUES
(1, '2026', '2026-01-15', '2026-11-30', 'ACTIVO', '2026-07-30 13:49:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `docente_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `asignatura_id` int(11) NOT NULL,
  `periodo_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('ANUNCIO','TAREA','EVALUACION','MATERIAL') NOT NULL,
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_entrega` date DEFAULT NULL,
  `visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones_individuales`
--

CREATE TABLE `publicaciones_individuales` (
  `id` int(11) NOT NULL,
  `publicacion_id` int(11) NOT NULL,
  `estudiante_id` int(11) NOT NULL,
  `fecha_asignacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'ADMIN'),
(2, 'DOCENTE'),
(3, 'ESTUDIANTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `documento` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `primer_login` tinyint(1) NOT NULL DEFAULT 0,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `requiere_cambio_password` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `archivos_publicacion`
--
ALTER TABLE `archivos_publicacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_archivo_publicacion` (`publicacion_id`);

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion_sistema`
--
ALTER TABLE `configuracion_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `docente_asignacion`
--
ALTER TABLE `docente_asignacion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_docente_curso_asignatura_periodo` (`docente_id`,`curso_id`,`asignatura_id`,`periodo_id`),
  ADD KEY `fk_da_curso` (`curso_id`),
  ADD KEY `fk_da_asignatura` (`asignatura_id`),
  ADD KEY `fk_docente_periodo` (`periodo_id`);

--
-- Indices de la tabla `entregas`
--
ALTER TABLE `entregas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_entrega_publicacion_estudiante` (`publicacion_id`,`estudiante_id`),
  ADD KEY `publicacion_id` (`publicacion_id`),
  ADD KEY `estudiante_id` (`estudiante_id`);

--
-- Indices de la tabla `estudiante_curso`
--
ALTER TABLE `estudiante_curso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_estudiante_periodo` (`estudiante_id`,`periodo_id`),
  ADD KEY `fk_ec_curso` (`curso_id`),
  ADD KEY `idx_ec_periodo` (`periodo_id`);

--
-- Indices de la tabla `historial_password`
--
ALTER TABLE `historial_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hp_usuario` (`usuario_id`);

--
-- Indices de la tabla `lectura_publicaciones`
--
ALTER TABLE `lectura_publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_lectura_publicacion_estudiante` (`publicacion_id`,`estudiante_id`),
  ADD KEY `publicacion_id` (`publicacion_id`),
  ADD KEY `estudiante_id` (`estudiante_id`);

--
-- Indices de la tabla `log_accesos`
--
ALTER TABLE `log_accesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_usuario` (`usuario_id`);

--
-- Indices de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_docente` (`docente_id`),
  ADD KEY `idx_curso` (`curso_id`),
  ADD KEY `idx_asignatura` (`asignatura_id`),
  ADD KEY `idx_publicacion_periodo` (`periodo_id`);

--
-- Indices de la tabla `publicaciones_individuales`
--
ALTER TABLE `publicaciones_individuales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_publicacion_estudiante` (`publicacion_id`,`estudiante_id`),
  ADD KEY `fk_pi_publicacion` (`publicacion_id`),
  ADD KEY `fk_pi_estudiante` (`estudiante_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`),
  ADD KEY `fk_usuario_rol` (`rol_id`),
  ADD KEY `idx_documento` (`documento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `archivos_publicacion`
--
ALTER TABLE `archivos_publicacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `configuracion_sistema`
--
ALTER TABLE `configuracion_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `docente_asignacion`
--
ALTER TABLE `docente_asignacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `entregas`
--
ALTER TABLE `entregas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiante_curso`
--
ALTER TABLE `estudiante_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_password`
--
ALTER TABLE `historial_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lectura_publicaciones`
--
ALTER TABLE `lectura_publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `log_accesos`
--
ALTER TABLE `log_accesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `periodos_academicos`
--
ALTER TABLE `periodos_academicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `publicaciones_individuales`
--
ALTER TABLE `publicaciones_individuales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archivos_publicacion`
--
ALTER TABLE `archivos_publicacion`
  ADD CONSTRAINT `fk_archivo_publicacion` FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `docente_asignacion`
--
ALTER TABLE `docente_asignacion`
  ADD CONSTRAINT `fk_da_asignatura` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  ADD CONSTRAINT `fk_da_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `fk_da_docente` FOREIGN KEY (`docente_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_docente_periodo` FOREIGN KEY (`periodo_id`) REFERENCES `periodos_academicos` (`id`);

--
-- Filtros para la tabla `entregas`
--
ALTER TABLE `entregas`
  ADD CONSTRAINT `entregas_ibfk_1` FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones` (`id`),
  ADD CONSTRAINT `entregas_ibfk_2` FOREIGN KEY (`estudiante_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `estudiante_curso`
--
ALTER TABLE `estudiante_curso`
  ADD CONSTRAINT `fk_ec_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `fk_ec_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_ec_periodo` FOREIGN KEY (`periodo_id`) REFERENCES `periodos_academicos` (`id`);

--
-- Filtros para la tabla `historial_password`
--
ALTER TABLE `historial_password`
  ADD CONSTRAINT `fk_hp_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `lectura_publicaciones`
--
ALTER TABLE `lectura_publicaciones`
  ADD CONSTRAINT `lectura_publicaciones_ibfk_1` FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones` (`id`),
  ADD CONSTRAINT `lectura_publicaciones_ibfk_2` FOREIGN KEY (`estudiante_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `log_accesos`
--
ALTER TABLE `log_accesos`
  ADD CONSTRAINT `fk_log_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `fk_pub_asignatura` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`),
  ADD CONSTRAINT `fk_pub_curso` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`),
  ADD CONSTRAINT `fk_pub_docente` FOREIGN KEY (`docente_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_pub_periodo` FOREIGN KEY (`periodo_id`) REFERENCES `periodos_academicos` (`id`);

--
-- Filtros para la tabla `publicaciones_individuales`
--
ALTER TABLE `publicaciones_individuales`
  ADD CONSTRAINT `fk_pi_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_pi_publicacion` FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
