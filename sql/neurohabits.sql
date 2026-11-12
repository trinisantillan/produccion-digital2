-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 12, 2026 at 06:21 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `neurohabits`
--

-- --------------------------------------------------------

--
-- Table structure for table `habitos_diarios`
--

CREATE TABLE `habitos_diarios` (
  `id` int(11) NOT NULL,
  `usuario_email` varchar(150) NOT NULL,
  `habito` varchar(120) NOT NULL,
  `fecha` date NOT NULL,
  `completado` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `habitos_diarios`
--

INSERT INTO `habitos_diarios` (`id`, `usuario_email`, `habito`, `fecha`, `completado`, `fecha_creacion`) VALUES
(1, 'trinisantillann@gmail.com', 'Fui a pilates', '2026-11-05', 0, '2026-11-05 17:42:42'),
(2, 'trinisantillann@gmail.com', 'Estudiar 30 minutos', '2026-11-05', 0, '2026-11-05 17:43:25');

-- --------------------------------------------------------

--
-- Table structure for table `objetivos_cumplidos`
--

CREATE TABLE `objetivos_cumplidos` (
  `id` int(11) NOT NULL,
  `usuario_email` varchar(150) NOT NULL,
  `objetivo_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `completado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `planes_estudio`
--

CREATE TABLE `planes_estudio` (
  `id` int(11) NOT NULL,
  `usuario_email` varchar(150) NOT NULL,
  `materia` varchar(150) NOT NULL,
  `fecha_examen` date NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `planes_estudio`
--

INSERT INTO `planes_estudio` (`id`, `usuario_email`, `materia`, `fecha_examen`, `fecha_creacion`) VALUES
(3, 'trinisantillann@gmail.com', 'Producción Digital II', '2026-11-13', '2026-11-05 19:02:01'),
(4, 'trinisantillann@gmail.com', 'Comunicación Digital II', '2026-11-17', '2026-11-05 19:42:08');

-- --------------------------------------------------------

--
-- Table structure for table `preguntas_contacto`
--

CREATE TABLE `preguntas_contacto` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `preguntas_contacto`
--

INSERT INTO `preguntas_contacto` (`id`, `nombre`, `email`, `mensaje`, `fecha`) VALUES
(4, 'Trinidad', 'trinisantillann@gmail.com', 'holaaa', '2026-10-01 17:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `resultados_quiz`
--

CREATE TABLE `resultados_quiz` (
  `id` int(11) NOT NULL,
  `usuario_email` varchar(100) NOT NULL,
  `puntos` int(11) NOT NULL,
  `total` int(11) DEFAULT 8,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resultados_quiz`
--

INSERT INTO `resultados_quiz` (`id`, `usuario_email`, `puntos`, `total`, `fecha`) VALUES
(12, 'trinisantillann@gmail.com', 8, 8, '2026-11-05 19:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `tareas_estudio`
--

CREATE TABLE `tareas_estudio` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `numero` tinyint(4) NOT NULL,
  `tarea` varchar(255) NOT NULL,
  `completada` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tareas_estudio`
--

INSERT INTO `tareas_estudio` (`id`, `plan_id`, `fecha`, `numero`, `tarea`, `completada`, `fecha_creacion`) VALUES
(7, 3, '2026-11-06', 1, 'Estudiar 30 minutos seguidos', 1, '2026-11-05 19:02:11'),
(8, 3, '2026-11-06', 2, 'leer 3 capitulos', 1, '2026-11-05 19:02:11');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `fecha_creacion`) VALUES
(19, 'Trinidad', 'trinisantillann@gmail.com', '$2y$10$UKsdGCDLy9dnW0v6ZZhO3esUT97.9EJOGQ/g0dNQw9yL8XgfLlB96', '2026-09-24 21:43:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `habitos_diarios`
--
ALTER TABLE `habitos_diarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `habito_unico_por_dia` (`usuario_email`,`habito`,`fecha`),
  ADD KEY `idx_usuario_fecha` (`usuario_email`,`fecha`);

--
-- Indexes for table `objetivos_cumplidos`
--
ALTER TABLE `objetivos_cumplidos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `objetivo_usuario_fecha` (`usuario_email`,`objetivo_id`,`fecha`),
  ADD KEY `idx_usuario_fecha` (`usuario_email`,`fecha`);

--
-- Indexes for table `planes_estudio`
--
ALTER TABLE `planes_estudio`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usuario` (`usuario_email`);

--
-- Indexes for table `preguntas_contacto`
--
ALTER TABLE `preguntas_contacto`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resultados_quiz`
--
ALTER TABLE `resultados_quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tareas_estudio`
--
ALTER TABLE `tareas_estudio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tarea_por_dia` (`plan_id`,`fecha`,`numero`),
  ADD KEY `idx_plan_fecha` (`plan_id`,`fecha`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `habitos_diarios`
--
ALTER TABLE `habitos_diarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `objetivos_cumplidos`
--
ALTER TABLE `objetivos_cumplidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `planes_estudio`
--
ALTER TABLE `planes_estudio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `preguntas_contacto`
--
ALTER TABLE `preguntas_contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `resultados_quiz`
--
ALTER TABLE `resultados_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tareas_estudio`
--
ALTER TABLE `tareas_estudio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tareas_estudio`
--
ALTER TABLE `tareas_estudio`
  ADD CONSTRAINT `tareas_estudio_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `planes_estudio` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
