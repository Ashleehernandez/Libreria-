-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 01:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `libreria`
--

-- --------------------------------------------------------

--
-- Table structure for table `autores`
--

CREATE TABLE `autores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `nacionalidad` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `autores`
--

INSERT INTO `autores` (`id`, `nombre`, `apellido`, `nacionalidad`, `fecha_nacimiento`, `biografia`, `created_at`) VALUES
(1, 'Gabriel', 'García Márquez', 'Colombiano', '1927-03-06', 'Escritor colombiano, Premio Nobel de Literatura en 1982, conocido por sus obras de realismo mágico.', '2025-07-30 23:15:55'),
(2, 'Isabel', 'Allende', 'Chilena', '1942-08-02', 'Escritora chilena de novelas, cuentos y memorias. Una de las escritoras más vendidas del mundo.', '2025-07-30 23:15:55'),
(3, 'Mario', 'Vargas Llosa', 'Peruano', '1936-03-28', 'Escritor peruano, Premio Nobel de Literatura en 2010, una de las figuras más importantes de la literatura latinoamericana.', '2025-07-30 23:15:55'),
(4, 'Julio', 'Cortázar', 'Argentino', '1914-08-26', 'Escritor argentino, uno de los autores más innovadores y originales de su tiempo.', '2025-07-30 23:15:55'),
(5, 'Jorge Luis', 'Borges', 'Argentino', '1899-08-24', 'Escritor argentino, considerado uno de los autores más destacados de la literatura del siglo XX.', '2025-07-30 23:15:55'),
(6, 'Paulo', 'Coelho', 'Brasileño', '1947-08-24', 'Escritor brasileño, uno de los autores más leídos del mundo contemporáneo.', '2025-07-30 23:15:55'),
(7, 'Octavio', 'Paz', 'Mexicano', '1914-03-31', 'Poeta y ensayista mexicano, Premio Nobel de Literatura en 1990.', '2025-07-30 23:15:55'),
(8, 'Laura', 'Esquivel', 'Mexicana', '1950-09-30', 'Escritora mexicana conocida por su novela \"Como agua para chocolate\".', '2025-07-30 23:15:55');

-- --------------------------------------------------------

--
-- Table structure for table `contacto`
--

CREATE TABLE `contacto` (
  `id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `correo` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `asunto` varchar(200) NOT NULL,
  `comentario` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `libros`
--

CREATE TABLE `libros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `autor_id` int(11) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `descripcion` text DEFAULT NULL,
  `fecha_publicacion` date DEFAULT NULL,
  `genero` varchar(50) DEFAULT NULL,
  `editorial` varchar(100) DEFAULT NULL,
  `paginas` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `autor_id`, `isbn`, `precio`, `stock`, `descripcion`, `fecha_publicacion`, `genero`, `editorial`, `paginas`, `imagen`, `created_at`) VALUES
(1, 'Cien años de soledad', 1, '978-0307474728', 25.99, 15, 'Una de las obras más importantes de la literatura universal, que narra la historia de la familia Buendía.', '1967-06-05', 'Realismo Mágico', 'Editorial Sudamericana', 432, 'cien_anos_soledad.jpg', '2025-07-30 23:15:55'),
(2, 'El amor en los tiempos del cólera', 1, '978-0307389732', 23.50, 10, 'Una hermosa historia de amor que trasciende el tiempo y las circunstancias.', '1985-09-05', 'Romance', 'Editorial Sudamericana', 464, 'amor_colera.jpg', '2025-07-30 23:15:55'),
(3, 'La casa de los espíritus', 2, '978-0525433446', 22.99, 12, 'Primera novela de Isabel Allende que la catapultó a la fama internacional.', '1982-10-01', 'Realismo Mágico', 'Plaza & Janés', 448, 'casa_espiritus.jpg', '2025-07-30 23:15:55'),
(4, 'Paula', 2, '978-0060927202', 20.99, 8, 'Memoir emotivo de Isabel Allende dedicado a su hija.', '1994-01-01', 'Biografía', 'Plaza & Janés', 352, 'paula.jpg', '2025-07-30 23:15:55'),
(5, 'La ciudad y los perros', 3, '978-8420674261', 21.50, 14, 'Primera novela de Vargas Llosa que lo estableció como una figura literaria importante.', '1963-10-01', 'Drama', 'Seix Barral', 416, 'ciudad_perros.jpg', '2025-07-30 23:15:55'),
(6, 'Conversación en La Catedral', 3, '978-8420674278', 28.99, 6, 'Una de las obras maestras de Vargas Llosa sobre la dictadura en Perú.', '1969-01-01', 'Drama Político', 'Seix Barral', 720, 'conversacion_catedral.jpg', '2025-07-30 23:15:55'),
(7, 'Rayuela', 4, '978-8437604572', 24.99, 9, 'Novela experimental que revolucionó la narrativa latinoamericana.', '1963-06-28', 'Experimental', 'Editorial Sudamericana', 600, 'rayuela.jpg', '2025-07-30 23:15:55'),
(8, 'El Aleph', 5, '978-8420674285', 19.99, 20, 'Colección de cuentos que incluye algunas de las obras más famosas de Borges.', '1949-06-01', 'Fantasía', 'Editorial Sudamericana', 192, 'aleph.jpg', '2025-07-30 23:15:55'),
(9, 'Ficciones', 5, '978-0802130303', 18.99, 16, 'Colección de relatos que estableció a Borges como maestro del cuento.', '1944-01-01', 'Fantasía', 'Editorial Sur', 176, 'ficciones.jpg', '2025-07-30 23:15:55'),
(10, 'El Alquimista', 6, '978-0061122415', 16.99, 25, 'Fábula sobre seguir los sueños y encontrar nuestro destino.', '1988-01-01', 'Filosofía', 'Editorial Planeta', 192, 'alquimista.jpg', '2025-07-30 23:15:55'),
(11, 'El laberinto de la soledad', 7, '978-9681668259', 22.50, 11, 'Ensayo fundamental sobre la identidad mexicana.', '1950-01-01', 'Ensayo', 'Fondo de Cultura Económica', 352, 'laberinto_soledad.jpg', '2025-07-30 23:15:55'),
(12, 'Como agua para chocolate', 8, '978-0385721240', 19.50, 18, 'Novela que combina cocina, amor y tradiciones mexicanas.', '1989-01-01', 'Romance', 'Editorial Planeta', 256, 'agua_chocolate.jpg', '2025-07-30 23:15:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_contacto_fecha` (`fecha`);

--
-- Indexes for table `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `idx_libros_autor` (`autor_id`),
  ADD KEY `idx_libros_genero` (`genero`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `autores`
--
ALTER TABLE `autores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `autores` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
