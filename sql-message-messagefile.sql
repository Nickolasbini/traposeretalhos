-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Out-2021 às 02:26
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
-- Estrutura da tabela `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL,
  `ownerOfMessage` bigint(20) NOT NULL,
  `dateOfMessage` datetime NOT NULL,
  `targetPerson` bigint(20) NOT NULL,
  `firstMessage` tinyint(4) DEFAULT NULL,
  `messageText` varchar(250) DEFAULT NULL,
  `fatherMessage` bigint(20) DEFAULT NULL,
  `messageFile` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ownerOfMessage` (`ownerOfMessage`),
  ADD KEY `targetPerson` (`targetPerson`),
  ADD KEY `fatherMessage` (`fatherMessage`),
  ADD KEY `document` (`messageFile`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`ownerOfMessage`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`targetPerson`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`fatherMessage`) REFERENCES `messages` (`id`),
  ADD CONSTRAINT `messages_ibfk_4` FOREIGN KEY (`messageFile`) REFERENCES `documents` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
