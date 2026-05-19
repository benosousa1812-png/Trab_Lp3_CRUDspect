-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18/05/2026 às 23:47
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `crud_personagem_classpecto`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `personagem`
--

CREATE TABLE `personagem` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `classe` varchar(50) NOT NULL,
  `aspecto` varchar(50) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `personagem`
--

INSERT INTO `personagem` (`id`, `nome`, `classe`, `aspecto`, `usuario_id`) VALUES
(1, 'John Egbert', 'Herdeiro(a)', 'Respiração', 1),
(2, 'Jade Harley', 'Bruxo(a)', 'Espaço', 1),
(3, 'Dave Strider', 'Cavaleiro(a)', 'Tempo', 1),
(4, 'Jane Crocker', 'Herdeiro(a)', 'Respiração', 2),
(5, 'Jake English', 'Bruxo(a)', 'Espaço', 2),
(6, 'Dirk Strider', 'Cavaleiro(a)', 'Tempo', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` char(64) NOT NULL COMMENT 'Hash SHA256 da senha',
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `criado_em`) VALUES
(1, 'Ash Ketchum', 'admin@email.com', 'beno', '2026-05-18 16:11:26'),
(2, 'N', 'n@email.com', 'n', '2026-05-18 16:12:18');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `personagem`
--
ALTER TABLE `personagem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_personagem_usuario` (`usuario_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `personagem`
--
ALTER TABLE `personagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `personagem`
--
ALTER TABLE `personagem`
  ADD CONSTRAINT `fk_personagem_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
