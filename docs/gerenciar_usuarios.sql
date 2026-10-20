-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 20/10/2026 às 18:30
-- Versão do servidor: 11.8.9-MariaDB-log
-- Versão do PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `u750204740_users`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissoes`
--

CREATE TABLE `permissoes` (
  `id` int(11) NOT NULL,
  `id_tipos_usuarios` int(11) NOT NULL,
  `id_sistemas` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `permissoes`
--

INSERT INTO `permissoes` (`id`, `id_tipos_usuarios`, `id_sistemas`, `id_usuarios`) VALUES
(31, 18, 4, 13),
(32, 19, 1, 13),
(33, 12, 10, 13),
(34, 16, 21, 13),
(35, 18, 4, 15),
(36, 19, 1, 15),
(37, 16, 21, 15),
(38, 12, 10, 15),
(39, 19, 1, 16),
(40, 12, 10, 51),
(41, 12, 10, 52),
(42, 12, 10, 49),
(43, 12, 10, 53),
(44, 18, 4, 55),
(45, 19, 1, 49),
(46, 19, 1, 50),
(47, 14, 10, 50),
(49, 13, 10, 57),
(50, 18, 4, 58),
(51, 19, 1, 58),
(52, 15, 10, 58),
(53, 17, 21, 58),
(54, 21, 2, 15),
(56, 21, 2, 58),
(57, 19, 1, 61),
(58, 19, 1, 60),
(59, 22, 17, 61),
(60, 21, 2, 63),
(61, 12, 10, 58);

-- --------------------------------------------------------

--
-- Estrutura para tabela `setores`
--

CREATE TABLE `setores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `setores`
--

INSERT INTO `setores` (`id`, `nome`) VALUES
(14, 'Aluno'),
(15, 'Professor'),
(16, 'Gestão'),
(17, 'Secretário(a)'),
(18, 'Diretor(a)'),
(19, 'Área Dev'),
(20, 'Suporte'),
(21, 'Coordenador(a)'),
(23, 'Recepção');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sistemas`
--

CREATE TABLE `sistemas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `sistemas`
--

INSERT INTO `sistemas` (`id`, `nome`) VALUES
(1, 'Entrada/saída'),
(2, 'Estágio'),
(3, 'Demandas'),
(4, 'Biblioteca'),
(5, 'SS'),
(9, 'Alimentação'),
(10, 'Estoque'),
(13, 'Espaços/Equipamentos'),
(14, 'Questões'),
(15, 'PCD'),
(16, 'Financeiro'),
(17, 'PDT'),
(18, 'Form_Parcial'),
(19, 'tombamento'),
(20, 'sesmated'),
(21, 'Gerenciador de Usuarios');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_usuarios`
--

CREATE TABLE `tipos_usuarios` (
  `id` int(11) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `id_sistema` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipos_usuarios`
--

INSERT INTO `tipos_usuarios` (`id`, `tipo`, `id_sistema`) VALUES
(12, 'Admin_estoque', 10),
(13, 'Comum_estoque', 10),
(14, 'liberador_estoque', 10),
(15, 'Dev_estoque', 10),
(16, 'Admin_usuario', 21),
(17, 'Dev_usuario', 21),
(18, 'Admin_biblioteca', 4),
(19, 'Admin_entrada_saida', 1),
(21, 'admin_estagio', 2),
(22, 'admin_pdt', 17);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) DEFAULT NULL,
  `email` char(200) DEFAULT NULL,
  `telefone` char(20) DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `senha` varchar(200) DEFAULT NULL,
  `id_setor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `telefone`, `foto_perfil`, `cpf`, `senha`, `id_setor`) VALUES
(13, 'Teste', 'Teste@teste.com', '(11) 11111-1111', NULL, '111.111.111-11', '$2y$10$1Pr9IM/Mgx9fhK1ievPLWOfPtbEZmf9fXyI1NMpN2DoRNX2U0hDyy', 15),
(14, 'melhor turma', '3b@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(15, 'otavio filho', 'otavio.ce@gmail.com', NULL, NULL, '646.115.823-53', '$2y$10$SFGu.nWIWZ7D4I9PxGMOGuDguW5rEiKTisRl9WI8gpX.nT2SUhi26', 15),
(16, 'vigilante', 'vigilante123@gmail.com', NULL, NULL, '111.111.111-11', '$2y$10$Aq6qMUY2a/tCiCyTKdMVFO0HxmjmPbj3ykeR0znjFEcXKJ1GJcYAu', 15),
(20, 'italo', 'italo@meioambiente.com', NULL, NULL, '111.111.111-11', NULL, 15),
(21, 'rennan', 'renan@administracao.com', NULL, NULL, '111.111.111-11', NULL, 15),
(25, 'JAMILYS GOSSON VIANA COLOMBO', 'jamilys.colombo@prof.ce.gov.br', NULL, NULL, '111.111.111-11', NULL, 15),
(26, 'Sigliany Freires Lemos', 'sigliany.lemos@prof.ce.gov.br', NULL, NULL, '111.111.111-11', NULL, 15),
(27, 'Brenda Kathellen Melo de Almeida', 'brendakathellen@yahoo.com.br', NULL, NULL, '111.111.111-11', NULL, 15),
(28, 'Kananda Beatriz Pinto de Sena', 'kananda.sena@prof.ce.gov.br', NULL, NULL, '111.111.111-11', NULL, 15),
(29, 'Fernando Cesar Madeira Bessa', 'bessafernando228@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(30, 'jose Rodrigo Lima Brasil', 'rodrigobrasil12453@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(31, 'Danytza façanha araripe', 'danytzaf@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(32, 'Carlla Danielle Da Silva Laurentino', 'carllalaurentino@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(33, 'isabel freitas dos santos nóbrega', 'belfreitas14@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(34, 'natalia queiroz lira', 'nataliaqlira@hotmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(35, 'DEYSIANE VALE DA SILVA LIMA', 'deysianedasilva34@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(36, 'Marcelo Leite Cavalcante', 'marcelo.00cavalcante@hotmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(37, 'Carlos Henrique Róseo de Paula Pessoa', 'carlosroseo@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(38, 'Ana Fabiane Carvalho', 'ana.carvalho1@prof.ce.gov.br', NULL, NULL, '111.111.111-11', NULL, 15),
(39, 'Adriana Paula da Silva Amorim', 'adriana.amorim1@prof.ce.gov.br', NULL, NULL, '111.111.111-11', NULL, 15),
(40, 'IGOR MARTINS PINHEIRO', 'igormpinheiro88@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(41, 'Solange Ferreira Alves', 'solangealves2012@hotmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(42, 'Solange Alves', 'solangealves20693@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(43, 'Francisco Jaene Alves De Andrade', 'jaene.andrade10@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(44, 'carlos emanuel silva de abreu', 'eusoujuventude@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(45, 'Jose Celio Cavalcante Filho', 'celiocavalcante13@gmail.com', NULL, NULL, '111.111.111-11', NULL, 15),
(46, 'matheus da silva falcão', 'matheus.falcao@prof.ce.gov.br', NULL, NULL, '111.111.111-11', NULL, 15),
(47, 'Edna', 'edna.silva@email.com', NULL, NULL, '111.111.111-11', NULL, 15),
(48, 'Marleuda', 'marleuda.lima@email.com', NULL, NULL, '111.111.111-11', NULL, 15),
(49, 'Rosana', 'rosana.oliveira@email.com', NULL, NULL, '142.395.781-09', '$2y$10$OK3fVID8xrgf7ZWxnmph5Ory8VOCgoi3hVUg.LcqXFC97zlWy64ha', 15),
(50, 'Adriana', 'adriana.costa@email.com', NULL, NULL, '905.347.620-33', NULL, 15),
(51, 'Cicero', 'cicero.sousa@email.com', NULL, NULL, '603.194.208-70', NULL, 18),
(52, 'Felipe', 'felipe@email.com', NULL, NULL, '111.111.111-11', '$2y$10$o1K0ytteCnzRBz7XpC6V1./bvlzt3wbqU6zM9YqbPr689uPAtrYuy', 15),
(53, 'Narcelio', 'narcelio.mendes@email.com', NULL, NULL, '111.111.111-11', '$2y$10$kkyW5rvMZhGcVXk7f.DsMeT4jKcpwZZWKjcclCdMTGynBeEUtnYTC', 15),
(54, 'Ricardo', 'ricardo.santos@email.com', NULL, NULL, '806.351.940-15', NULL, 15),
(55, 'Luana Caetano de Medeiros Lima', 'luana-cm@hotmail.com', '(85) 98883-2046', 'perfil_55_1756499705.jpg', '667.662.733-72', '$2y$10$dobBrOGbha1624ssTR854.VOoewnmFtyfSYRTShx5bS9uR7JNiXMu', 14),
(57, 'Marcelo Cabral', 'marcellcabral@gmail.com', NULL, NULL, '000.000.000-00', NULL, 15),
(58, 'Pedro Uchôa de Abreu', 'uchoadev16@gmail.com', NULL, NULL, '629.855.213-86', '$2y$10$HYScVCrL89SOKK5ftWIWfunXmSf7ROzfFXW4YiDfQGoTPLta7g3.6', 19),
(59, 'teste2', 'teste2@teste.com', NULL, NULL, '888.888.888-88', NULL, 14),
(60, 'Kauan Cristian Santos de Lima', 'cristian9br@gmail.com', NULL, NULL, '094.289.873-79', '$2y$10$JftKX6MZ8gpWfIPVWeK3lOxhptkCwAb/3B6HR1g3P.B.rVCNjGAbC', 19),
(61, 'Cássio Holanda Gomes Severiano', 'holandaseveriano@gmail.com', NULL, NULL, '051.787.343-51', '$2y$10$l.tUVkYd4bDdoefLyzc6we6JfH/QmINthDdm8NtQXt05Qfp.ap3lS', 19),
(62, 'Ester Andrade Almeida', 'ester101122@gmail.com', NULL, NULL, '101.122.343-05', NULL, 19),
(63, 'Ester', 'ester@gmail.com', NULL, NULL, '555.222.333-66', '$2y$10$VaoxXYzkiJU6rrpjSRBLx.61ZrG/gQUfxUFTsluNkFq56cAG.8w/a', 19);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipos_usuarios` (`id_tipos_usuarios`),
  ADD KEY `id_sistemas` (`id_sistemas`),
  ADD KEY `id_usuarios` (`id_usuarios`);

--
-- Índices de tabela `setores`
--
ALTER TABLE `setores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `sistemas`
--
ALTER TABLE `sistemas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sistema` (`id_sistema`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_setor` (`id_setor`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `permissoes`
--
ALTER TABLE `permissoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de tabela `setores`
--
ALTER TABLE `setores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `sistemas`
--
ALTER TABLE `sistemas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `permissoes`
--
ALTER TABLE `permissoes`
  ADD CONSTRAINT `permissoes_ibfk_1` FOREIGN KEY (`id_tipos_usuarios`) REFERENCES `tipos_usuarios` (`id`),
  ADD CONSTRAINT `permissoes_ibfk_2` FOREIGN KEY (`id_sistemas`) REFERENCES `sistemas` (`id`),
  ADD CONSTRAINT `permissoes_ibfk_3` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  ADD CONSTRAINT `tipos_usuarios_ibfk_1` FOREIGN KEY (`id_sistema`) REFERENCES `sistemas` (`id`);

--
-- Restrições para tabelas `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_setor`) REFERENCES `setores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
