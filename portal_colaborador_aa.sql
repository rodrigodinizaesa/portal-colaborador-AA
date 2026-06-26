-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26-Jun-2026 às 19:16
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `portal_colaborador_aa`
--
CREATE DATABASE IF NOT EXISTS `portal_colaborador_aa` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `portal_colaborador_aa`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `areas`
--

CREATE TABLE `areas` (
  `id` int(3) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `idDepartamento` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `areas`
--

INSERT INTO `areas` (`id`, `nome`, `idDepartamento`) VALUES
(1, 'Divisao de Apoio ao Estaleiro', 1),
(2, 'Divisao de Construçao Naval', 1),
(3, 'Divisao de Mecanica', 1),
(4, 'Divisao de Planeamento e Gestao de Projetos', 1),
(5, 'Gabinete de Projeto e Fabrico Ativo', 1),
(6, 'Divisao de Laboratorios e de Controlo da Qualidade', 1),
(7, 'Balneario Central', 1),
(8, 'Divisao de Armas e Sensores', 1),
(9, 'Divisao de Contratacao e Aprovisionamento', 2),
(10, 'Divisao de Gestao Financeira', 2),
(11, 'Divisao de Gestao de Tecnologias de Informacao', 3),
(12, 'Servico de Seguranca no Trabalho e Ambiente', 3),
(13, 'Divisao de Recursos Humanos', 3),
(14, 'Unidade de Apoio Geral', 3),
(15, 'Unidade Tecnologica Qualidade', 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cargos`
--

CREATE TABLE `cargos` (
  `id` int(3) NOT NULL,
  `nome` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `cargos`
--

INSERT INTO `cargos` (`id`, `nome`) VALUES
(1, 'Diretor do Estaleiro'),
(2, 'Diretor de Financas'),
(3, 'Diretor de Recursos'),
(4, 'Diretor de Gabinete de Assessoria'),
(5, 'Chefe de Apoio ao Estaleiro'),
(6, 'Chefe de Construcao Naval'),
(7, 'Chefe de Mecanica'),
(8, 'Chefe de Planeamento e Gestao de Projetos'),
(9, 'Chefe de Projeto e Fabrico Aditivo'),
(10, 'Chefe de Laboratorio e Controlo da Qualidade'),
(11, 'Chefe de Balneario Central'),
(12, 'Chefe de Divisao de Armas e Sensores'),
(13, 'Chefe de Contratacao e Aprovisionamento'),
(14, 'Chefe de Gestao Financeira'),
(15, 'Chefe de Gestao de Tecnologias e Informacao'),
(16, 'Chefe de Serviço de Segurança no Trabalho e Ambiente'),
(17, 'Chefe de Recursos Humanos'),
(18, 'Chefe de Unidade de Apoio Geral'),
(19, 'Tecnico Superior de Recursos Humanos'),
(20, 'Tecnico Especialista de Recursos Humanos');

-- --------------------------------------------------------

--
-- Estrutura da tabela `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(3) NOT NULL,
  `nome` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `departamentos`
--

INSERT INTO `departamentos` (`id`, `nome`) VALUES
(1, 'Direcao de Estaleiro'),
(2, 'Direcao de Financas'),
(3, 'Direcao de Recursos'),
(4, 'Gabinete de Assesoria');

-- --------------------------------------------------------

--
-- Estrutura da tabela `faltas`
--

CREATE TABLE `faltas` (
  `idFaltas` int(6) NOT NULL,
  `idFuncionario` int(3) NOT NULL,
  `dataInicio` datetime NOT NULL,
  `dataFim` datetime NOT NULL,
  `tipoFalta` enum('parcial','diaria') NOT NULL,
  `motivoFalta` varchar(200) NOT NULL,
  `motivo` varchar(150) DEFAULT NULL,
  `comentarioRH` text DEFAULT NULL,
  `comprovativo` varchar(255) DEFAULT NULL,
  `estado` enum('pendente','aprovado','rejeitado') DEFAULT 'pendente',
  `idResponsavelRH` int(3) DEFAULT NULL,
  `dataPedido` datetime DEFAULT current_timestamp(),
  `dataDecisao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `faltas`
--

INSERT INTO `faltas` (`idFaltas`, `idFuncionario`, `dataInicio`, `dataFim`, `tipoFalta`, `motivoFalta`, `motivo`, `comentarioRH`, `comprovativo`, `estado`, `idResponsavelRH`, `dataPedido`, `dataDecisao`) VALUES
(1, 1, '2026-06-05 09:00:00', '2026-06-05 18:00:00', 'parcial', 'Ausencia de Picagem', '', '', '', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `id` int(3) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `nif` int(9) NOT NULL,
  `dataNascimento` date NOT NULL,
  `sexo` varchar(20) NOT NULL,
  `iban` varchar(25) NOT NULL,
  `niss` varchar(11) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `nacionalidade` varchar(50) NOT NULL,
  `morada` varchar(200) NOT NULL,
  `distrito` varchar(50) NOT NULL,
  `concelho` varchar(50) NOT NULL,
  `freguesia` varchar(50) NOT NULL,
  `codigoPostal` varchar(10) NOT NULL,
  `contactoEmergenciaNome` varchar(150) DEFAULT NULL,
  `contactoEmergenciaTelefone` varchar(30) DEFAULT NULL,
  `contactoEmergenciaParentesco` varchar(50) DEFAULT NULL,
  `titulares` int(1) NOT NULL,
  `dependentes` int(2) NOT NULL,
  `idCargo` int(3) NOT NULL,
  `idArea` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `funcionarios`
--

INSERT INTO `funcionarios` (`id`, `nome`, `nif`, `dataNascimento`, `sexo`, `iban`, `niss`, `telefone`, `nacionalidade`, `morada`, `distrito`, `concelho`, `freguesia`, `codigoPostal`, `contactoEmergenciaNome`, `contactoEmergenciaTelefone`, `contactoEmergenciaParentesco`, `titulares`, `dependentes`, `idCargo`, `idArea`) VALUES
(1, 'Rodrigo Diniz', 123456789, '2001-09-15', 'Masculino', 'PT00000000000000000000000', '12345678901', '+351987654321', 'Marroquino', 'Rua XPTO 5esq', 'Setubal', 'Barreiro', 'Barreiro e Lavradio', '2830123', 'Nelsinho', '+27821234567', 'Primo', 1, 1, 17, 13),
(2, 'Fernando Mendes', 123456789, '2001-01-01', 'Masculino', 'PT00000000000000000000000', '12345678901', '+351927654321', 'Marroquino', 'Rua XPTO 4esq', 'Setúbal', 'Moita', 'Alhos Vedros', '2860421', 'Rodrigo', '+27821234567', 'Primaco', 1, 1, 3, 13);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidosdadosfiscais`
--

CREATE TABLE `pedidosdadosfiscais` (
  `id` int(5) NOT NULL,
  `idFuncionario` int(3) NOT NULL,
  `novoNome` varchar(150) DEFAULT NULL,
  `novoNif` int(9) DEFAULT NULL,
  `novaDataNascimento` date DEFAULT NULL,
  `novoSexo` varchar(20) DEFAULT NULL,
  `novoIban` varchar(25) DEFAULT NULL,
  `novoNiss` varchar(11) DEFAULT NULL,
  `novoTelefone` varchar(20) DEFAULT NULL,
  `novaNacionalidade` varchar(50) DEFAULT NULL,
  `novaMorada` varchar(200) DEFAULT NULL,
  `novoDistrito` varchar(50) DEFAULT NULL,
  `novoConcelho` varchar(50) DEFAULT NULL,
  `novaFreguesia` varchar(50) DEFAULT NULL,
  `novoCodigoPostal` varchar(10) DEFAULT NULL,
  `contactoEmergenciaNome` varchar(150) DEFAULT NULL,
  `contactoEmergenciaTelefone` varchar(30) DEFAULT NULL,
  `contactoEmergenciaParentesco` varchar(50) DEFAULT NULL,
  `novoTitulares` int(1) DEFAULT NULL,
  `novoDependentes` int(2) DEFAULT NULL,
  `comprovativo` varchar(255) NOT NULL,
  `nota` text DEFAULT NULL,
  `estado` enum('pendente','aprovado','rejeitado') NOT NULL DEFAULT 'pendente',
  `idResponsavelRH` int(3) DEFAULT NULL,
  `dataPedido` datetime NOT NULL DEFAULT current_timestamp(),
  `dataDecisao` datetime DEFAULT NULL,
  `comentarioRH` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `pedidosdadosfiscais`
--

INSERT INTO `pedidosdadosfiscais` (`id`, `idFuncionario`, `novoNome`, `novoNif`, `novaDataNascimento`, `novoSexo`, `novoIban`, `novoNiss`, `novoTelefone`, `novaNacionalidade`, `novaMorada`, `novoDistrito`, `novoConcelho`, `novaFreguesia`, `novoCodigoPostal`, `contactoEmergenciaNome`, `contactoEmergenciaTelefone`, `contactoEmergenciaParentesco`, `novoTitulares`, `novoDependentes`, `comprovativo`, `nota`, `estado`, `idResponsavelRH`, `dataPedido`, `dataDecisao`, `comentarioRH`) VALUES
(1, 1, 'Antonio Joao', 123456789, '2001-01-01', 'Masculino', 'PT00000000000000000000000', '12345678901', '+351987654321', 'Marroquino', 'Rua Maio 4esq', 'Setubal', 'Moita', 'Alhos Vedros', '2860123', 'Sergio', '+29782464527', 'Tio', 1, 1, 'C:/xampp/uploads/20260624_RD_1.pdf', NULL, 'pendente', NULL, '2026-06-08 00:00:00', NULL, NULL),
(2, 2, 'Fernando Mendes', 123456789, '2001-01-01', 'Masculino', 'PT00000000000000000000000', '12345678901', '+351927654321', 'Marroquino', 'Rua XPTO 4esq', 'Setúbal', 'Moita', 'Alhos Vedros', '2860421', 'Rodrigo', '+27821234567', 'Primaco', 2, 1, 'Array', NULL, 'pendente', 1, '2026-06-11 17:42:20', NULL, ''),
(4, 2, '20260624_FM_2.pdf', 123456789, '2001-01-01', 'Masculino', 'PT00000000000000000000000', '12345678901', '+351927654321', 'Marroquino', 'Rua XPTO 4esq', 'Setúbal', 'Moita', 'Alhos Vedros', '2860421', 'Rodrigo', '+27821234567', 'Primaco', 1, 1, 'C:/xampp/uploads/20260624_FM_2.pdf', NULL, 'pendente', NULL, '2026-06-24 16:37:11', NULL, NULL),
(5, 2, 'Jony Peps', 123456789, '2001-01-01', 'Masculino', 'PT00000000000000000000000', '12345678901', '+351927654321', 'Marroquino', 'Rua XPTO 4esq', 'Setúbal', 'Moita', 'Alhos Vedros', '2860421', 'Rodrigo', '+27821234567', 'Primaco', 1, 1, 'C:/xampp/uploads/20260624_FM_2.pdf', NULL, 'pendente', 1, '2026-06-24 16:49:21', NULL, '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedidosferias`
--

CREATE TABLE `pedidosferias` (
  `id` int(6) NOT NULL,
  `idFuncionario` int(3) NOT NULL,
  `dataInicio` date NOT NULL,
  `dataFim` date NOT NULL,
  `nota` text DEFAULT NULL,
  `estado` enum('pendente','aprovado','rejeitado') NOT NULL DEFAULT 'pendente',
  `idResponsavel` int(3) DEFAULT NULL,
  `dataPedido` datetime NOT NULL DEFAULT current_timestamp(),
  `dataDecisao` datetime DEFAULT NULL,
  `comentarioResponsavel` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `pedidosferias`
--

INSERT INTO `pedidosferias` (`id`, `idFuncionario`, `dataInicio`, `dataFim`, `nota`, `estado`, `idResponsavel`, `dataPedido`, `dataDecisao`, `comentarioResponsavel`) VALUES
(1, 1, '2026-07-01', '2026-07-15', NULL, 'pendente', NULL, '2026-06-12 17:27:21', NULL, NULL),
(3, 2, '2026-06-16', '2026-06-23', '', 'pendente', NULL, '2026-06-15 11:14:11', NULL, NULL),
(4, 1, '2026-06-24', '2026-06-30', '', 'rejeitado', 2, '2026-06-23 21:22:29', '2026-06-25 14:15:25', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `registos`
--

CREATE TABLE `registos` (
  `id` int(11) NOT NULL,
  `idFuncionario` int(3) NOT NULL,
  `dataEntrada` datetime NOT NULL,
  `dataSaida` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipos`
--

CREATE TABLE `tipos` (
  `id` int(1) NOT NULL,
  `nome` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `tipos`
--

INSERT INTO `tipos` (`id`, `nome`) VALUES
(1, 'Admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id` int(3) NOT NULL,
  `nColaborador` varchar(4) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `idTipo` int(1) NOT NULL,
  `idFuncionario` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id`, `nColaborador`, `password`, `email`, `idTipo`, `idFuncionario`) VALUES
(1, 'f001', '$2y$10$AVAmsLLzLTff4vdy52Yu9uQY2Jm4mKgfmLQ/3I811jB5iCizxq6Ca', 'r@r.r', 1, 1),
(2, 'f002', '$2y$10$XrmoDhiT2ytc0iT2S28l4OuOP6V0oz7tzY/JeQO9Djz68ioHrUtgm', 'f@f.f', 1, 2);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idDepartamento` (`idDepartamento`);

--
-- Índices para tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `faltas`
--
ALTER TABLE `faltas`
  ADD PRIMARY KEY (`idFaltas`),
  ADD KEY `idFuncionario` (`idFuncionario`),
  ADD KEY `idResponsavelRH` (`idResponsavelRH`);

--
-- Índices para tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idCargo` (`idCargo`),
  ADD KEY `idArea` (`idArea`);

--
-- Índices para tabela `pedidosdadosfiscais`
--
ALTER TABLE `pedidosdadosfiscais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idFuncionario` (`idFuncionario`),
  ADD KEY `idResponsavelRH` (`idResponsavelRH`);

--
-- Índices para tabela `pedidosferias`
--
ALTER TABLE `pedidosferias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idFuncionario` (`idFuncionario`),
  ADD KEY `idResponsavel` (`idResponsavel`);

--
-- Índices para tabela `registos`
--
ALTER TABLE `registos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idFuncionario` (`idFuncionario`);

--
-- Índices para tabela `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idTipo` (`idTipo`),
  ADD KEY `idFuncionario` (`idFuncionario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `faltas`
--
ALTER TABLE `faltas`
  MODIFY `idFaltas` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pedidosdadosfiscais`
--
ALTER TABLE `pedidosdadosfiscais`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `pedidosferias`
--
ALTER TABLE `pedidosferias`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `registos`
--
ALTER TABLE `registos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `areas`
--
ALTER TABLE `areas`
  ADD CONSTRAINT `areas_ibfk_1` FOREIGN KEY (`idDepartamento`) REFERENCES `departamentos` (`id`);

--
-- Limitadores para a tabela `faltas`
--
ALTER TABLE `faltas`
  ADD CONSTRAINT `faltas_ibfk_1` FOREIGN KEY (`idFuncionario`) REFERENCES `funcionarios` (`id`),
  ADD CONSTRAINT `faltas_ibfk_2` FOREIGN KEY (`idResponsavelRH`) REFERENCES `funcionarios` (`id`);

--
-- Limitadores para a tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD CONSTRAINT `funcionarios_ibfk_1` FOREIGN KEY (`idCargo`) REFERENCES `cargos` (`id`),
  ADD CONSTRAINT `funcionarios_ibfk_2` FOREIGN KEY (`idArea`) REFERENCES `areas` (`id`);

--
-- Limitadores para a tabela `pedidosdadosfiscais`
--
ALTER TABLE `pedidosdadosfiscais`
  ADD CONSTRAINT `pedidosdadosfiscais_ibfk_1` FOREIGN KEY (`idFuncionario`) REFERENCES `funcionarios` (`id`),
  ADD CONSTRAINT `pedidosdadosfiscais_ibfk_2` FOREIGN KEY (`idResponsavelRH`) REFERENCES `funcionarios` (`id`);

--
-- Limitadores para a tabela `pedidosferias`
--
ALTER TABLE `pedidosferias`
  ADD CONSTRAINT `pedidosferias_ibfk_1` FOREIGN KEY (`idFuncionario`) REFERENCES `funcionarios` (`id`),
  ADD CONSTRAINT `pedidosferias_ibfk_2` FOREIGN KEY (`idResponsavel`) REFERENCES `funcionarios` (`id`);

--
-- Limitadores para a tabela `registos`
--
ALTER TABLE `registos`
  ADD CONSTRAINT `registos_ibfk_1` FOREIGN KEY (`idFuncionario`) REFERENCES `funcionarios` (`id`);

--
-- Limitadores para a tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD CONSTRAINT `utilizadores_ibfk_1` FOREIGN KEY (`idTipo`) REFERENCES `tipos` (`id`),
  ADD CONSTRAINT `utilizadores_ibfk_2` FOREIGN KEY (`idFuncionario`) REFERENCES `funcionarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
