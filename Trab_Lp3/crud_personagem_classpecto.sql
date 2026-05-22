-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/05/2026 às 05:21
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
  `usuario_id` int(11) NOT NULL,
  `imagem` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `personagem`
--

INSERT INTO `personagem` (`id`, `nome`, `classe`, `aspecto`, `usuario_id`, `imagem`) VALUES
(1, 'Mituna Captor', 'Herdeiro(a)', 'Ruína', 1, 'uploads/6a0fca812f353.png'),
(2, 'Jade Harley', 'Bardo(a)', 'Espaço', 1, 'uploads/6a0fca67bab55.png'),
(4, 'Jane Crocker', 'Herdeiro(a)', 'Respiração', 2, 'uploads/6a0fcb03a85b3.png'),
(5, 'Jake English', 'Escudeiro(a)', 'Esperança', 2, 'uploads/6a0fca0d53d4b.png'),
(15, 'feferi', 'Príncipe / Princesa', 'Mente', 1, 'uploads/6a0fca4b8e0fd.png'),
(17, 'Aradia Megido', 'Bruxo(a)', 'Tempo', 1, 'uploads/6a0fcade54211.png'),
(18, 'Homem aranha', 'Cavaleiro(a)', 'Espaço', 3, 'uploads/6a0fcb393a107.png'),
(19, 'Sonic', 'Cavaleiro(a)', 'Esperança', 1, 'uploads/6a0fcab2bc0f3.png'),
(20, 'Tung tung Sahur', 'Herdeiro(a)', 'Esperança', 1, 'uploads/6a0fcabed81a9.png'),
(21, 'Bowser', 'Ladrão(a)', 'Esperança', 2, 'uploads/6a0fc9c2e9a78.png'),
(22, 'Feferi Peixes', 'Bruxo(a)', 'Vida', 2, 'uploads/6a0fc99c406a4.png');

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
(1, 'Ash Ketchum', 'admin@email.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2026-05-18 16:11:26'),
(2, 'N', 'n@email.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2026-05-18 16:12:18'),
(3, 'Pedro Sá de Sousa', 'pedropipoca@email.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', '2026-05-21 17:58:02'),
(4, 'Beno Sá de Sousa', 'sollux@email.com', '8bb0cf6eb9b17d0f7d22b456f121257dc1254e1f01665370476383ea776df414', '2026-05-21 18:15:11');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
