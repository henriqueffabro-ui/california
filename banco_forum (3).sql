-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09/04/2026 às 13:46
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
-- Banco de dados: `banco_forum`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `upvotes` int(11) DEFAULT NULL,
  `downvotes` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `post_id`, `usuario_id`, `comentario`, `data`, `upvotes`, `downvotes`) VALUES
(19, 23, 1, 'caraca essa imagem é realmente linda', '2026-04-05 19:05:41', 1, 0),
(20, 23, 1, 'mds isso é arte', '2026-04-05 19:10:30', 0, NULL),
(21, 23, 1, 'ww', '2026-04-05 19:10:40', NULL, NULL),
(22, 23, 1, 'minecraft', '2026-04-05 19:10:53', NULL, NULL),
(23, 23, 1, 'rea', '2026-04-08 13:13:41', NULL, NULL),
(24, 23, 1, 'ww', '2026-04-08 13:17:04', NULL, NULL),
(25, 23, 1, 'rererer', '2026-04-08 13:18:06', NULL, NULL),
(26, 23, 1, 'tttt', '2026-04-08 13:18:58', NULL, NULL),
(27, 23, 1, 'tttt', '2026-04-08 13:18:59', NULL, NULL),
(28, 24, 1, 'rere', '2026-04-08 13:22:35', 0, 0),
(29, 24, 1, 'www', '2026-04-08 13:25:02', 0, 1),
(30, 24, 1, 'ttt', '2026-04-08 13:26:43', 0, 0),
(31, 24, 1, 'rerer', '2026-04-08 13:29:20', 0, 1),
(32, 24, 1, 'ww', '2026-04-08 13:42:33', 0, 1),
(33, 24, 1, 'sss', '2026-04-08 13:42:38', 0, 1),
(34, 24, 1, 'www', '2026-04-08 13:44:01', 0, 1),
(35, 25, 1, 'q', '2026-04-08 14:00:09', 0, 1),
(36, 25, 1, 'w', '2026-04-08 14:00:11', 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `imagens`
--

CREATE TABLE `imagens` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `id_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `imagens`
--

INSERT INTO `imagens` (`id`, `nome`, `id_post`) VALUES
(1, '69c7d73c70087.', 5),
(3, '69c7d8c548ef3.', 7),
(4, '69c7d8f8bbf20.', 8),
(12, '69c7db01b815f.jpg', 14),
(13, '69c9bbd01b773.jpg', 15),
(14, '69c9bbe40658b.jpg', 16),
(15, '69c9bbe4094c1.jpg', 16),
(16, '69c9bc15042e6.jpg', 17),
(17, '69c9bc150526a.jpg', 17),
(18, '69ca7a90d0904.jpg', 18),
(19, '69ca7ae22ae0a.jpg', 19),
(20, '69ca7cbb29d86.jpg', 20),
(21, '69ca7cbb2b084.jpg', 20),
(22, '69cb09e6dc016.jpg', 21),
(23, '69cb0a7c31a7d.jpg', 22),
(24, '69d19fa707d5d.png', 23),
(25, '69d655d4f21a4.png', 24),
(26, '69d65f624c3aa.', 25);

-- --------------------------------------------------------

--
-- Estrutura para tabela `postagens`
--

CREATE TABLE `postagens` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `editado` int(100) DEFAULT NULL,
  `upvotes` int(100) DEFAULT NULL,
  `downvotes` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `postagens`
--

INSERT INTO `postagens` (`id`, `titulo`, `descricao`, `id_usuario`, `data`, `editado`, `upvotes`, `downvotes`) VALUES
(5, 'qq', 'qqq', 1, '2026-03-28 10:27:24', NULL, NULL, NULL),
(7, 'qqqqsss', 'qqqq', 1, '2026-03-28 10:33:57', 1, NULL, NULL),
(8, 'wwwddd', 'sss', 1, '2026-03-28 10:34:48', 1, NULL, NULL),
(14, 'imagens', 'brabo', 1, '2026-03-28 10:43:29', NULL, NULL, NULL),
(15, 'ee', 'eee', 1, '2026-03-29 20:54:56', NULL, NULL, NULL),
(16, 'qqq', 'qqq', 1, '2026-03-29 20:55:16', NULL, NULL, NULL),
(17, 'ss', 'ss', 1, '2026-03-29 20:56:05', NULL, NULL, NULL),
(18, '', '', 1, '2026-03-30 10:28:48', NULL, NULL, NULL),
(19, '', '', 1, '2026-03-30 10:30:10', NULL, 0, 0),
(20, '', 'aaa', 1, '2026-03-30 10:38:03', 1, 0, 0),
(21, 'www', 'www', 1, '2026-03-30 20:40:22', NULL, 1, 0),
(22, 'oaoaoa', 'ssssssss', 1, '2026-03-30 20:42:52', NULL, 0, 1),
(23, 'olha que linda', 'essa imagem ---- edit: muito obrigado pelo apoio galera', 1, '2026-04-04 20:32:55', 1, 2, 0),
(24, 'teste', 'teste', 1, '2026-04-08 10:19:16', NULL, NULL, NULL),
(25, 'wwww', 'wwww', 1, '2026-04-08 11:00:02', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(200) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `data_criacao`) VALUES
(1, 'chiquinho_gaviao', 'gaviaochico@gmail.com', 'california', '2026-03-28 10:06:43'),
(2, 'Henrique', 'henriqueffabro@gmail.com', 'california', '2026-03-28 10:42:04');

-- --------------------------------------------------------

--
-- Estrutura para tabela `votes_comentarios`
--

CREATE TABLE `votes_comentarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario_id` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `votes_comentarios`
--

INSERT INTO `votes_comentarios` (`id`, `usuario_id`, `comentario_id`, `tipo`, `data`) VALUES
(3, 1, 19, 1, '2026-04-08 10:13:37'),
(5, 1, 29, -1, '2026-04-08 10:25:05'),
(10, 1, 32, -1, '2026-04-08 10:49:45'),
(11, 1, 31, -1, '2026-04-08 10:49:45'),
(13, 1, 33, -1, '2026-04-08 10:49:47'),
(14, 1, 34, -1, '2026-04-08 10:49:48'),
(35, 1, 35, -1, '2026-04-08 11:14:51');

-- --------------------------------------------------------

--
-- Estrutura para tabela `votos`
--

CREATE TABLE `votos` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `votos`
--

INSERT INTO `votos` (`id`, `post_id`, `usuario_id`, `tipo`) VALUES
(47, 21, 1, 1),
(50, 23, 2, 1),
(51, 23, 1, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `imagens`
--
ALTER TABLE `imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_post` (`id_post`);

--
-- Índices de tabela `postagens`
--
ALTER TABLE `postagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `votes_comentarios`
--
ALTER TABLE `votes_comentarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`comentario_id`),
  ADD KEY `comentario_id` (`comentario_id`);

--
-- Índices de tabela `votos`
--
ALTER TABLE `votos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `post_id` (`post_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `postagens`
--
ALTER TABLE `postagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `votes_comentarios`
--
ALTER TABLE `votes_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `votos`
--
ALTER TABLE `votos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `postagens` (`id`),
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `imagens`
--
ALTER TABLE `imagens`
  ADD CONSTRAINT `imagens_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `postagens` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `postagens`
--
ALTER TABLE `postagens`
  ADD CONSTRAINT `postagens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `votes_comentarios`
--
ALTER TABLE `votes_comentarios`
  ADD CONSTRAINT `votes_comentarios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_comentarios_ibfk_2` FOREIGN KEY (`comentario_id`) REFERENCES `comentarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `votos`
--
ALTER TABLE `votos`
  ADD CONSTRAINT `votos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `votos_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `postagens` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
