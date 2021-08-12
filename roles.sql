-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12-Ago-2021 às 05:05
-- Versão do servidor: 10.4.11-MariaDB
-- versão do PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `traposeretalhos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `roles`
--

CREATE TABLE `roles` (
  `id` int(10) NOT NULL,
  `roleName` varchar(100) NOT NULL,
  `dateOfCreation` date NOT NULL,
  `description` varchar(150) NOT NULL,
  `iconUrl` varchar(20) DEFAULT NULL,
  `colorOnMap` varchar(50) NOT NULL,
  `isUsedOnMap` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `roles`
--

INSERT INTO `roles` (`id`, `roleName`, `dateOfCreation`, `description`, `iconUrl`, `colorOnMap`, `isUsedOnMap`) VALUES
(1, 'seamstress', '2021-04-06', 'i seek the best professionals of the repairs area and works with fabric and clothes pieces', 'seamstress.svg', 'red', 1),
(2, 'tailor', '2021-04-08', 'i seek the best at the tailoring area, costumes made clothing specialist', 'tailor.svg', 'blue', 1),
(3, 'dressmaker', '2021-04-08', 'i seek the best professional in the area, looking for an unique and fashionable cloth piece', 'dressmaker.svg', 'green', 1),
(4, 'course', '2021-05-05', 'learning with the best professional and the best courses this incredible art which is the sweewing and like', 'online-course.svg', 'purple', 0),
(5, 'product', '2021-05-05', 'finding product used by trully professionals with great quality and unmissable prices', 'silk.svg', 'yellow', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
