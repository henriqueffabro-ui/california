-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/04/2026 às 15:50
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
  `downvotes` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `post_id`, `usuario_id`, `comentario`, `data`, `upvotes`, `downvotes`, `parent_id`) VALUES
(19, 23, 1, 'caraca essa imagem é realmente linda', '2026-04-05 19:05:41', 1, 0, NULL),
(20, 23, 1, 'mds isso é arte', '2026-04-05 19:10:30', 0, NULL, NULL),
(21, 23, 1, 'ww', '2026-04-05 19:10:40', NULL, NULL, NULL),
(22, 23, 1, 'minecraft', '2026-04-05 19:10:53', NULL, NULL, NULL),
(50, 24, 1, 'pppp', '2026-04-10 23:53:53', NULL, NULL, NULL),
(51, 24, 1, 'qqqq', '2026-04-10 23:53:56', NULL, NULL, 50),
(52, 24, 1, 'qqqqq', '2026-04-10 23:57:20', NULL, NULL, 50),
(53, 24, 1, 'qqqq', '2026-04-10 23:58:09', NULL, NULL, 50),
(54, 24, 1, 'qqqqq', '2026-04-10 23:58:25', NULL, NULL, 50),
(55, 24, 1, 'qqqq', '2026-04-10 23:58:47', NULL, NULL, 50),
(56, 24, 1, 'qqqqw', '2026-04-11 00:00:14', NULL, NULL, 50),
(57, 24, 1, 'qqqq', '2026-04-11 00:01:06', NULL, NULL, 50),
(58, 24, 1, 'qqqqqw', '2026-04-11 00:01:10', NULL, NULL, 50),
(59, 24, 1, 'qqqqqw', '2026-04-11 00:01:11', NULL, NULL, 50),
(60, 24, 1, 'wwww', '2026-04-11 00:01:49', NULL, NULL, 50),
(61, 24, 1, 'çççç', '2026-04-11 00:04:22', NULL, NULL, 50),
(62, 24, 1, 'eee', '2026-04-11 00:06:39', NULL, NULL, 50),
(63, 24, 1, 'ppp', '2026-04-11 00:06:56', NULL, NULL, 52),
(64, 24, 1, 'qqq', '2026-04-11 00:08:34', NULL, NULL, 50),
(65, 24, 1, 'qq', '2026-04-11 00:12:01', NULL, NULL, 50);

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
(24, '69d19fa707d5d.png', 23),
(25, '69d655d4f21a4.png', 24);

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
(23, 'olha que linda', 'essa imagem ---- edit: muito obrigado pelo apoio galera', 1, '2026-04-04 20:32:55', 1, 2, 0),
(24, 'teste', 'teste', 1, '2026-04-08 10:19:16', NULL, 1, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(200) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `foto_perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `data_criacao`, `foto_perfil`) VALUES
(1, 'chiquinho_gaviao', 'gaviaochico@gmail.com', 'california', '2026-03-28 10:06:43', NULL),
(2, 'Henrique', 'henriqueffabro@gmail.com', 'california', '2026-03-28 10:42:04', NULL);

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
(3, 1, 19, 1, '2026-04-08 10:13:37');

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
(50, 23, 2, 1),
(51, 23, 1, 1),
(52, 24, 1, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_parent` (`parent_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de tabela `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `postagens`
--
ALTER TABLE `postagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `votes_comentarios`
--
ALTER TABLE `votes_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `votos`
--
ALTER TABLE `votos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `postagens` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_parent` FOREIGN KEY (`parent_id`) REFERENCES `comentarios` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `votos_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `postagens` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
