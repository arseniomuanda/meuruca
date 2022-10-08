-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Tempo de geração: 08-Out-2022 às 10:10
-- Versão do servidor: 5.7.34
-- versão do PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `meuruca`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `acessos`
--

CREATE TABLE `acessos` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `acesso` longtext,
  `firma` int(11) NOT NULL,
  `criadopor` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `acesso_utilizador`
--

CREATE TABLE `acesso_utilizador` (
  `id` int(11) NOT NULL,
  `utilizador` int(11) UNSIGNED ZEROFILL NOT NULL,
  `acesso` int(11) NOT NULL,
  `firma` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `agendas`
--

CREATE TABLE `agendas` (
  `id` int(11) NOT NULL,
  `ticket` varchar(40) DEFAULT NULL,
  `inicio` datetime NOT NULL,
  `fim` datetime DEFAULT NULL,
  `descricao` text,
  `activo` int(1) DEFAULT '1',
  `estado` int(1) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `nome_item` varchar(100) DEFAULT NULL,
  `viatura` int(11) DEFAULT NULL,
  `categoria` varchar(20) DEFAULT NULL,
  `prestador` int(11) NOT NULL,
  `servico_entrega` int(1) DEFAULT NULL,
  `is_domicilio` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `agendas`
--

INSERT INTO `agendas` (`id`, `ticket`, `inicio`, `fim`, `descricao`, `activo`, `estado`, `created_at`, `updated_at`, `deleted_at`, `nome_item`, `viatura`, `categoria`, `prestador`, `servico_entrega`, `is_domicilio`) VALUES
(23, NULL, '2022-04-30 18:00:00', NULL, 'Eu quero coiso', 1, 0, NULL, NULL, NULL, NULL, 3, 'Manutenção', 0, 0, 1),
(25, NULL, '2022-04-12 12:30:00', NULL, 'Tenho de pedir para ele lavar o carro também.', 1, 1, NULL, NULL, NULL, NULL, 8, 'Manutenção', 0, 0, 0),
(26, NULL, '2022-04-20 18:32:00', NULL, 'Arsee', 1, 1, NULL, NULL, NULL, NULL, 7, 'Manutenção', 0, 0, 0),
(27, NULL, '2022-05-16 17:30:00', NULL, 'Arse', 1, 0, NULL, NULL, NULL, NULL, 7, 'Manutenção', 0, 0, 0),
(29, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção. ', 1, 1, 0),
(30, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção. ', 1, 1, 0),
(31, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(32, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(33, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(34, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(35, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(36, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(37, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(38, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(39, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(40, NULL, '0000-00-00 00:00:00', NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(41, NULL, '0000-00-00 00:00:00', NULL, '', 1, 0, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(42, NULL, '0000-00-00 00:00:00', NULL, '', 1, 0, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(43, NULL, '0000-00-00 00:00:00', NULL, '', 1, 0, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(44, NULL, '0000-00-00 00:00:00', NULL, '', 1, 0, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0),
(45, NULL, '0000-00-00 00:00:00', NULL, '', 1, 0, NULL, NULL, NULL, NULL, 3, 'Manutenção', 1, 1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ano_fabricos`
--

CREATE TABLE `ano_fabricos` (
  `id` int(11) NOT NULL,
  `nome` varchar(4) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `modelo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `ano_fabricos`
--

INSERT INTO `ano_fabricos` (`id`, `nome`, `created_at`, `updated_at`, `deleted_at`, `modelo`) VALUES
(1, '1996', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `auditoria`
--

CREATE TABLE `auditoria` (
  `idauditoria` int(10) NOT NULL,
  `accao` varchar(80) CHARACTER SET latin1 NOT NULL COMMENT 'ex: login, actualizar, inserir, procurar, pagar',
  `processo` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT 'resultado da acÃ§Ã£o, pode ser a permissÃ£o',
  `registo` text CHARACTER SET latin1 NOT NULL COMMENT 'operaÃ§Ã£o exeecutada ou registo afecto da operaperÃ£o',
  `utilizador` varchar(180) CHARACTER SET latin1 NOT NULL,
  `dataAcao` datetime NOT NULL,
  `dataExpiracao` datetime NOT NULL COMMENT 'a data de expiraÃ§Ã£o Ã© a data em que o sistema elimina o regidto da base de dados',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `criadopor` int(10) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Extraindo dados da tabela `auditoria`
--

INSERT INTO `auditoria` (`idauditoria`, `accao`, `processo`, `registo`, `utilizador`, `dataAcao`, `dataExpiracao`, `created_at`, `updated_at`, `criadopor`, `deleted_at`) VALUES
(1, 'Inserir', 'New proprietarios', '3', '1', '2022-03-29 01:57:01', '2024-03-29 01:57:01', '2022-03-29 07:57:01', NULL, NULL, NULL),
(2, 'Inserir', 'New proprietarios', '4', '1', '2022-03-29 01:57:10', '2024-03-29 01:57:10', '2022-03-29 07:57:10', NULL, NULL, NULL),
(3, 'Deletar', 'Deletar proprietarios', '3', '1', '2022-03-29 00:00:00', '2024-03-29 02:12:25', '2022-03-29 08:12:25', NULL, NULL, NULL),
(4, 'Deletar', 'Deletar proprietarios', '3', '1', '2022-03-29 00:00:00', '2024-03-29 02:12:53', '2022-03-29 08:12:53', NULL, NULL, NULL),
(5, 'Deletar', 'Deletar proprietarios', '3', '1', '2022-03-29 00:00:00', '2024-03-29 02:16:13', '2022-03-29 08:16:13', NULL, NULL, NULL),
(6, 'Deletar', 'Deletar proprietarios', '3', '1', '2022-03-29 00:00:00', '2024-03-29 02:19:50', '2022-03-29 08:19:50', NULL, NULL, NULL),
(7, 'Deletar', 'Deletar proprietarios', '3', '1', '2022-03-29 00:00:00', '2024-03-29 02:22:59', '2022-03-29 08:22:59', NULL, NULL, NULL),
(8, 'Inserir', 'New utilizadors', '0', '1', '2022-03-29 07:31:39', '2024-03-29 07:31:39', '2022-03-29 13:31:39', NULL, NULL, NULL),
(9, 'Inserir', 'New contas', '0', '1', '2022-03-30 07:52:10', '2024-03-30 07:52:10', '2022-03-30 13:52:10', NULL, NULL, NULL),
(10, 'Inserir', 'New contas', '0', '1', '2022-03-30 07:53:31', '2024-03-30 07:53:31', '2022-03-30 13:53:31', NULL, NULL, NULL),
(11, 'Inserir', 'New proprietarios', '0', '1', '2022-03-30 07:53:32', '2024-03-30 07:53:32', '2022-03-30 13:53:32', NULL, NULL, NULL),
(12, 'Inserir', 'New contas', '0', '1', '2022-03-30 07:57:10', '2024-03-30 07:57:10', '2022-03-30 13:57:10', NULL, NULL, NULL),
(13, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:14:37', '2024-03-30 08:14:37', '2022-03-30 14:14:37', NULL, NULL, NULL),
(14, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:14:55', '2024-03-30 08:14:55', '2022-03-30 14:14:55', NULL, NULL, NULL),
(15, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:16:14', '2024-03-30 08:16:14', '2022-03-30 14:16:14', NULL, NULL, NULL),
(16, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:16:52', '2024-03-30 08:16:52', '2022-03-30 14:16:52', NULL, NULL, NULL),
(17, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:17:04', '2024-03-30 08:17:04', '2022-03-30 14:17:04', NULL, NULL, NULL),
(18, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:18:22', '2024-03-30 08:18:22', '2022-03-30 14:18:22', NULL, NULL, NULL),
(19, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:19:40', '2024-03-30 08:19:40', '2022-03-30 14:19:40', NULL, NULL, NULL),
(20, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:21:58', '2024-03-30 08:21:58', '2022-03-30 14:21:58', NULL, NULL, NULL),
(21, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:22:59', '2024-03-30 08:22:59', '2022-03-30 14:22:59', NULL, NULL, NULL),
(22, 'Inserir', 'New contas', '0', '1', '2022-03-30 08:23:25', '2024-03-30 08:23:25', '2022-03-30 14:23:25', NULL, NULL, NULL),
(23, 'Inserir', 'New contas', '0', '1', '2022-03-30 09:10:01', '2024-03-30 09:10:01', '2022-03-30 15:10:01', NULL, NULL, NULL),
(24, 'Inserir', 'New utilizadors', '0', '1', '2022-03-30 09:10:56', '2024-03-30 09:10:56', '2022-03-30 15:10:56', NULL, NULL, NULL),
(25, 'Inserir', 'New utilizadors', '0', '1', '2022-03-30 09:11:00', '2024-03-30 09:11:00', '2022-03-30 15:11:00', NULL, NULL, NULL),
(26, 'Inserir', 'New proprietarios', '0', '1', '2022-03-30 09:16:00', '2024-03-30 09:16:00', '2022-03-30 15:16:00', NULL, NULL, NULL),
(27, 'Inserir', 'New proprietarios', '0', '1', '2022-03-30 09:16:03', '2024-03-30 09:16:03', '2022-03-30 15:16:03', NULL, NULL, NULL),
(28, 'Inserir', 'New proprietarios', '5', '1', '2022-03-30 09:16:32', '2024-03-30 09:16:32', '2022-03-30 15:16:32', NULL, NULL, NULL),
(29, 'Inserir', 'New contas', '2', '1', '2022-03-30 09:16:46', '2024-03-30 09:16:46', '2022-03-30 15:16:46', NULL, NULL, NULL),
(30, 'Inserir', 'New contas', '3', '1', '2022-03-30 09:16:49', '2024-03-30 09:16:49', '2022-03-30 15:16:49', NULL, NULL, NULL),
(31, 'Inserir', 'New contas', '4', '1', '2022-03-30 09:25:54', '2024-03-30 09:25:54', '2022-03-30 15:25:54', NULL, NULL, NULL),
(32, 'Inserir', 'New proprietarios', '6', '1', '2022-03-30 09:25:54', '2024-03-30 09:25:54', '2022-03-30 15:25:54', NULL, NULL, NULL),
(33, 'Inserir', 'New contas', '5', '1', '2022-03-30 09:26:29', '2024-03-30 09:26:29', '2022-03-30 15:26:29', NULL, NULL, NULL),
(34, 'Inserir', 'New proprietarios', '7', '1', '2022-03-30 09:26:29', '2024-03-30 09:26:29', '2022-03-30 15:26:29', NULL, NULL, NULL),
(35, 'Inserir', 'New contas', '6', '1', '2022-03-30 09:27:03', '2024-03-30 09:27:03', '2022-03-30 15:27:03', NULL, NULL, NULL),
(36, 'Inserir', 'New proprietarios', '8', '1', '2022-03-30 09:27:03', '2024-03-30 09:27:03', '2022-03-30 15:27:03', NULL, NULL, NULL),
(37, 'Inserir', 'New contas', '7', '1', '2022-03-30 09:27:33', '2024-03-30 09:27:33', '2022-03-30 15:27:33', NULL, NULL, NULL),
(38, 'Inserir', 'New proprietarios', '9', '1', '2022-03-30 09:27:33', '2024-03-30 09:27:33', '2022-03-30 15:27:33', NULL, NULL, NULL),
(39, 'Inserir', 'New utilizadors', '1', '1', '2022-03-30 09:27:33', '2024-03-30 09:27:33', '2022-03-30 15:27:33', NULL, NULL, NULL),
(40, 'Inserir', 'New contas', '8', '1', '2022-03-30 09:29:33', '2024-03-30 09:29:33', '2022-03-30 15:29:33', NULL, NULL, NULL),
(41, 'Inserir', 'New proprietarios', '10', '1', '2022-03-30 09:29:33', '2024-03-30 09:29:33', '2022-03-30 15:29:33', NULL, NULL, NULL),
(42, 'Inserir', 'New utilizadors', '2', '1', '2022-03-30 09:29:34', '2024-03-30 09:29:34', '2022-03-30 15:29:34', NULL, NULL, NULL),
(43, 'Inserir', 'New contas', '9', '1', '2022-03-30 09:31:32', '2024-03-30 09:31:32', '2022-03-30 15:31:32', NULL, NULL, NULL),
(44, 'Inserir', 'New proprietarios', '11', '1', '2022-03-30 09:31:32', '2024-03-30 09:31:32', '2022-03-30 15:31:32', NULL, NULL, NULL),
(45, 'Inserir', 'New contas', '10', '1', '2022-03-30 09:31:47', '2024-03-30 09:31:47', '2022-03-30 15:31:47', NULL, NULL, NULL),
(46, 'Inserir', 'New proprietarios', '12', '1', '2022-03-30 09:31:47', '2024-03-30 09:31:47', '2022-03-30 15:31:47', NULL, NULL, NULL),
(47, 'Inserir', 'New contas', '11', '1', '2022-03-30 09:31:55', '2024-03-30 09:31:55', '2022-03-30 15:31:55', NULL, NULL, NULL),
(48, 'Inserir', 'New proprietarios', '13', '1', '2022-03-30 09:31:56', '2024-03-30 09:31:56', '2022-03-30 15:31:56', NULL, NULL, NULL),
(49, 'Inserir', 'New utilizadors', '3', '1', '2022-03-30 09:31:56', '2024-03-30 09:31:56', '2022-03-30 15:31:56', NULL, NULL, NULL),
(50, 'Inserir', 'New contas', '12', '1', '2022-03-30 09:32:45', '2024-03-30 09:32:45', '2022-03-30 15:32:45', NULL, NULL, NULL),
(51, 'Inserir', 'New proprietarios', '14', '1', '2022-03-30 09:32:45', '2024-03-30 09:32:45', '2022-03-30 15:32:45', NULL, NULL, NULL),
(52, 'Inserir', 'New utilizadors', '4', '1', '2022-03-30 09:32:45', '2024-03-30 09:32:45', '2022-03-30 15:32:45', NULL, NULL, NULL),
(53, 'Inserir', 'New contas', '13', '1', '2022-03-30 09:33:39', '2024-03-30 09:33:39', '2022-03-30 15:33:39', NULL, NULL, NULL),
(54, 'Inserir', 'New proprietarios', '15', '1', '2022-03-30 09:33:39', '2024-03-30 09:33:39', '2022-03-30 15:33:39', NULL, NULL, NULL),
(55, 'Inserir', 'New contas', '14', '1', '2022-03-30 10:19:14', '2024-03-30 10:19:14', '2022-03-30 16:19:14', NULL, NULL, NULL),
(56, 'Inserir', 'New proprietarios', '16', '1', '2022-03-30 10:19:14', '2024-03-30 10:19:14', '2022-03-30 16:19:14', NULL, NULL, NULL),
(57, 'Inserir', 'New utilizadors', '6', '1', '2022-03-30 10:19:14', '2024-03-30 10:19:14', '2022-03-30 16:19:14', NULL, NULL, NULL),
(58, 'Inserir', 'New contas', '15', '1', '2022-03-31 06:22:58', '2024-03-31 06:22:58', '2022-03-31 12:22:58', NULL, NULL, NULL),
(59, 'Inserir', 'New contas', '0', '1', '2022-03-31 06:31:32', '2024-03-31 06:31:32', '2022-03-31 12:31:32', NULL, NULL, NULL),
(60, 'Inserir', 'New contas', '16', '1', '2022-03-31 06:31:47', '2024-03-31 06:31:47', '2022-03-31 12:31:47', NULL, NULL, NULL),
(61, 'Inserir', 'New contas', '17', '1', '2022-03-31 06:31:50', '2024-03-31 06:31:50', '2022-03-31 12:31:50', NULL, NULL, NULL),
(62, 'Inserir', 'New contas', '18', '1', '2022-03-31 06:33:47', '2024-03-31 06:33:47', '2022-03-31 12:33:47', NULL, NULL, NULL),
(63, 'Inserir', 'New contas', '19', '1', '2022-03-31 06:34:30', '2024-03-31 06:34:30', '2022-03-31 12:34:30', NULL, NULL, NULL),
(64, 'Inserir', 'New contas', '20', '1', '2022-03-31 06:35:32', '2024-03-31 06:35:32', '2022-03-31 12:35:32', NULL, NULL, NULL),
(65, 'Inserir', 'New proprietarios', '17', '1', '2022-03-31 06:35:32', '2024-03-31 06:35:32', '2022-03-31 12:35:32', NULL, NULL, NULL),
(66, 'Inserir', 'New contas', '21', '1', '2022-03-31 06:36:07', '2024-03-31 06:36:07', '2022-03-31 12:36:07', NULL, NULL, NULL),
(67, 'Inserir', 'New proprietarios', '18', '1', '2022-03-31 06:36:08', '2024-03-31 06:36:08', '2022-03-31 12:36:08', NULL, NULL, NULL),
(68, 'Inserir', 'New contas', '22', '1', '2022-03-31 06:36:32', '2024-03-31 06:36:32', '2022-03-31 12:36:32', NULL, NULL, NULL),
(69, 'Inserir', 'New proprietarios', '19', '1', '2022-03-31 06:36:32', '2024-03-31 06:36:32', '2022-03-31 12:36:32', NULL, NULL, NULL),
(70, 'Inserir', 'New utilizadors', '7', '1', '2022-03-31 06:36:33', '2024-03-31 06:36:33', '2022-03-31 12:36:33', NULL, NULL, NULL),
(71, 'Inserir', 'New marcas', '1', '1', '2022-03-31 08:13:47', '2024-03-31 08:13:47', '2022-03-31 14:13:47', NULL, NULL, NULL),
(72, 'Inserir', 'New marcas', '0', '1', '2022-03-31 08:14:38', '2024-03-31 08:14:38', '2022-03-31 14:14:38', NULL, NULL, NULL),
(73, 'Actualizar', 'Update marcas', '1', '1', '2022-03-31 00:00:00', '2024-03-31 08:14:55', '2022-03-31 14:14:55', NULL, NULL, NULL),
(74, 'Inserir', 'New modelos', '0', '1', '2022-03-31 08:15:50', '2024-03-31 08:15:50', '2022-03-31 14:15:50', NULL, NULL, NULL),
(75, 'Inserir', 'New modelos', '1', '1', '2022-03-31 08:15:57', '2024-03-31 08:15:57', '2022-03-31 14:15:57', NULL, NULL, NULL),
(76, 'Inserir', 'New ano_fabricos', '1', '1', '2022-03-31 08:17:37', '2024-03-31 08:17:37', '2022-03-31 14:17:37', NULL, NULL, NULL),
(77, 'Actualizar', 'Update ano_fabricos', '1', '1', '2022-03-31 00:00:00', '2024-03-31 08:18:27', '2022-03-31 14:18:27', NULL, NULL, NULL),
(78, 'Inserir', 'New viaturas', '0', '1', '2022-03-31 08:19:01', '2024-03-31 08:19:01', '2022-03-31 14:19:01', NULL, NULL, NULL),
(79, 'Inserir', 'New viaturas', '3', '1', '2022-03-31 08:19:09', '2024-03-31 08:19:09', '2022-03-31 14:19:09', NULL, NULL, NULL),
(80, 'Inserir', 'New contas', '23', '1', '2022-03-31 19:50:37', '2024-03-31 19:50:37', '2022-04-01 01:50:37', NULL, NULL, NULL),
(81, 'Inserir', 'New proprietarios', '20', '1', '2022-03-31 19:50:38', '2024-03-31 19:50:38', '2022-04-01 01:50:38', NULL, NULL, NULL),
(82, 'Inserir', 'New utilizadors', '8', '1', '2022-03-31 19:50:38', '2024-03-31 19:50:38', '2022-04-01 01:50:38', NULL, NULL, NULL),
(83, 'Inserir', 'New contas', '24', '0', '2022-04-01 06:31:57', '2024-04-01 06:31:57', '2022-04-01 12:31:57', NULL, NULL, NULL),
(84, 'Inserir', 'New proprietarios', '21', '0', '2022-04-01 06:32:00', '2024-04-01 06:32:00', '2022-04-01 12:32:00', NULL, NULL, NULL),
(85, 'Inserir', 'New utilizadors', '9', '0', '2022-04-01 06:32:00', '2024-04-01 06:32:00', '2022-04-01 12:32:00', NULL, NULL, NULL),
(86, 'Inserir', 'New contas', '25', '1', '2022-04-03 05:54:49', '2024-04-03 05:54:49', '2022-04-03 11:54:49', NULL, NULL, NULL),
(87, 'Inserir', 'New proprietarios', '22', '1', '2022-04-03 05:54:49', '2024-04-03 05:54:49', '2022-04-03 11:54:49', NULL, NULL, NULL),
(88, 'Inserir', 'New contas', '26', '1', '2022-04-03 09:23:20', '2024-04-03 09:23:20', '2022-04-03 15:23:20', NULL, NULL, NULL),
(89, 'Inserir', 'New proprietarios', '23', '1', '2022-04-03 09:23:20', '2024-04-03 09:23:20', '2022-04-03 15:23:20', NULL, NULL, NULL),
(90, 'Inserir', 'New contas', '27', '1', '2022-04-03 09:24:18', '2024-04-03 09:24:18', '2022-04-03 15:24:18', NULL, NULL, NULL),
(91, 'Inserir', 'New proprietarios', '24', '1', '2022-04-03 09:24:18', '2024-04-03 09:24:18', '2022-04-03 15:24:18', NULL, NULL, NULL),
(92, 'Inserir', 'New contas', '28', '1', '2022-04-03 09:25:42', '2024-04-03 09:25:42', '2022-04-03 15:25:42', NULL, NULL, NULL),
(93, 'Inserir', 'New proprietarios', '25', '1', '2022-04-03 09:25:43', '2024-04-03 09:25:43', '2022-04-03 15:25:43', NULL, NULL, NULL),
(94, 'Inserir', 'New contas', '29', '1', '2022-04-03 09:26:04', '2024-04-03 09:26:04', '2022-04-03 15:26:04', NULL, NULL, NULL),
(95, 'Inserir', 'New proprietarios', '26', '1', '2022-04-03 09:26:04', '2024-04-03 09:26:04', '2022-04-03 15:26:04', NULL, NULL, NULL),
(96, 'Inserir', 'New contas', '30', '1', '2022-04-03 09:26:23', '2024-04-03 09:26:23', '2022-04-03 15:26:23', NULL, NULL, NULL),
(97, 'Inserir', 'New proprietarios', '27', '1', '2022-04-03 09:26:23', '2024-04-03 09:26:23', '2022-04-03 15:26:23', NULL, NULL, NULL),
(98, 'Inserir', 'New contas', '31', '1', '2022-04-03 09:26:57', '2024-04-03 09:26:57', '2022-04-03 15:26:57', NULL, NULL, NULL),
(99, 'Inserir', 'New proprietarios', '28', '1', '2022-04-03 09:26:57', '2024-04-03 09:26:57', '2022-04-03 15:26:57', NULL, NULL, NULL),
(100, 'Inserir', 'New contas', '32', '1', '2022-04-03 09:28:23', '2024-04-03 09:28:23', '2022-04-03 15:28:23', NULL, NULL, NULL),
(101, 'Inserir', 'New proprietarios', '29', '1', '2022-04-03 09:28:23', '2024-04-03 09:28:23', '2022-04-03 15:28:23', NULL, NULL, NULL),
(102, 'Inserir', 'New contas', '33', '1', '2022-04-03 09:28:40', '2024-04-03 09:28:40', '2022-04-03 15:28:40', NULL, NULL, NULL),
(103, 'Inserir', 'New proprietarios', '30', '1', '2022-04-03 09:28:41', '2024-04-03 09:28:41', '2022-04-03 15:28:41', NULL, NULL, NULL),
(104, 'Inserir', 'New contas', '34', '1', '2022-04-03 09:29:12', '2024-04-03 09:29:12', '2022-04-03 15:29:12', NULL, NULL, NULL),
(105, 'Inserir', 'New proprietarios', '31', '1', '2022-04-03 09:29:12', '2024-04-03 09:29:12', '2022-04-03 15:29:12', NULL, NULL, NULL),
(106, 'Inserir', 'New contas', '35', '1', '2022-04-03 09:29:35', '2024-04-03 09:29:35', '2022-04-03 15:29:35', NULL, NULL, NULL),
(107, 'Inserir', 'New proprietarios', '32', '1', '2022-04-03 09:29:35', '2024-04-03 09:29:35', '2022-04-03 15:29:35', NULL, NULL, NULL),
(108, 'Inserir', 'New contas', '36', '1', '2022-04-03 09:30:17', '2024-04-03 09:30:17', '2022-04-03 15:30:17', NULL, NULL, NULL),
(109, 'Inserir', 'New proprietarios', '33', '1', '2022-04-03 09:30:17', '2024-04-03 09:30:17', '2022-04-03 15:30:17', NULL, NULL, NULL),
(110, 'Inserir', 'New contas', '37', '1', '2022-04-03 09:30:40', '2024-04-03 09:30:40', '2022-04-03 15:30:40', NULL, NULL, NULL),
(111, 'Inserir', 'New proprietarios', '34', '1', '2022-04-03 09:30:40', '2024-04-03 09:30:40', '2022-04-03 15:30:40', NULL, NULL, NULL),
(112, 'Inserir', 'New contas', '38', '1', '2022-04-03 09:31:05', '2024-04-03 09:31:05', '2022-04-03 15:31:05', NULL, NULL, NULL),
(113, 'Inserir', 'New proprietarios', '35', '1', '2022-04-03 09:31:05', '2024-04-03 09:31:05', '2022-04-03 15:31:05', NULL, NULL, NULL),
(114, 'Inserir', 'New contas', '39', '1', '2022-04-03 09:31:17', '2024-04-03 09:31:17', '2022-04-03 15:31:17', NULL, NULL, NULL),
(115, 'Inserir', 'New proprietarios', '36', '1', '2022-04-03 09:31:17', '2024-04-03 09:31:17', '2022-04-03 15:31:17', NULL, NULL, NULL),
(116, 'Inserir', 'New utilizadors', '10', '1', '2022-04-03 09:31:17', '2024-04-03 09:31:17', '2022-04-03 15:31:17', NULL, NULL, NULL),
(117, 'Inserir', 'New contas', '40', '1', '2022-04-03 09:50:44', '2024-04-03 09:50:44', '2022-04-03 15:50:44', NULL, NULL, NULL),
(118, 'Inserir', 'New proprietarios', '37', '1', '2022-04-03 09:50:44', '2024-04-03 09:50:44', '2022-04-03 15:50:44', NULL, NULL, NULL),
(119, 'Inserir', 'New utilizadors', '11', '1', '2022-04-03 09:50:45', '2024-04-03 09:50:45', '2022-04-03 15:50:45', NULL, NULL, NULL),
(120, 'Inserir', 'New contas', '41', '1', '2022-04-03 09:57:07', '2024-04-03 09:57:07', '2022-04-03 15:57:07', NULL, NULL, NULL),
(121, 'Inserir', 'New proprietarios', '38', '1', '2022-04-03 09:57:07', '2024-04-03 09:57:07', '2022-04-03 15:57:07', NULL, NULL, NULL),
(122, 'Inserir', 'New utilizadors', '12', '1', '2022-04-03 09:57:07', '2024-04-03 09:57:07', '2022-04-03 15:57:07', NULL, NULL, NULL),
(123, 'Actualizar', 'Update utilizadors', '12', '12', '2022-04-03 00:00:00', '2024-04-03 12:01:58', '2022-04-03 18:01:58', NULL, NULL, NULL),
(124, 'Actualizar', 'Update utilizadors', '12', '12', '2022-04-03 00:00:00', '2024-04-03 13:30:16', '2022-04-03 19:30:16', NULL, NULL, NULL),
(125, 'Actualizar', 'Update utilizadors', '12', '12', '2022-04-03 00:00:00', '2024-04-03 13:38:32', '2022-04-03 19:38:32', NULL, NULL, NULL),
(126, 'Actualizar', 'Update utilizadors', '12', '12', '2022-04-03 00:00:00', '2024-04-03 13:40:18', '2022-04-03 19:40:18', NULL, NULL, NULL),
(127, 'Actualizar', 'Update utilizadors', '12', '12', '2022-04-03 00:00:00', '2024-04-03 13:41:01', '2022-04-03 19:41:01', NULL, NULL, NULL),
(128, 'Actualizar', 'Update utilizadors', '12', '12', '2022-04-03 00:00:00', '2024-04-03 15:31:17', '2022-04-03 21:31:17', NULL, NULL, NULL),
(129, 'Inserir', 'New viaturas', '0', '1', '2022-04-03 18:34:52', '2024-04-03 18:34:52', '2022-04-04 00:34:52', NULL, NULL, NULL),
(130, 'Inserir', 'New viaturas', '4', '1', '2022-04-03 18:35:26', '2024-04-03 18:35:26', '2022-04-04 00:35:26', NULL, NULL, NULL),
(131, 'Inserir', 'New viaturas', '5', '1', '2022-04-03 18:36:18', '2024-04-03 18:36:18', '2022-04-04 00:36:18', NULL, NULL, NULL),
(132, 'Inserir', 'New viaturas', '7', '6', '2022-04-04 05:54:07', '2024-04-04 05:54:07', '2022-04-04 11:54:07', NULL, NULL, NULL),
(133, 'Inserir', 'New viaturas', '8', '6', '2022-04-04 05:55:31', '2024-04-04 05:55:31', '2022-04-04 11:55:31', NULL, NULL, NULL),
(134, 'Inserir', 'New agendas', '1', '1', '2022-04-04 07:50:28', '2024-04-04 07:50:28', '2022-04-04 13:50:28', NULL, NULL, NULL),
(135, 'Inserir', 'New agendas', '2', '1', '2022-04-04 07:50:44', '2024-04-04 07:50:44', '2022-04-04 13:50:44', NULL, NULL, NULL),
(136, 'Inserir', 'New agendas', '3', '6', '2022-04-04 08:33:20', '2024-04-04 08:33:20', '2022-04-04 14:33:20', NULL, NULL, NULL),
(137, 'Inserir', 'New agendas', '4', '6', '2022-04-04 08:33:57', '2024-04-04 08:33:57', '2022-04-04 14:33:57', NULL, NULL, NULL),
(138, 'Inserir', 'New agendas', '5', '6', '2022-04-04 08:38:08', '2024-04-04 08:38:08', '2022-04-04 14:38:08', NULL, NULL, NULL),
(139, 'Inserir', 'New agendas', '6', '6', '2022-04-04 08:38:53', '2024-04-04 08:38:53', '2022-04-04 14:38:53', NULL, NULL, NULL),
(140, 'Inserir', 'New agendas', '7', '6', '2022-04-04 08:44:22', '2024-04-04 08:44:22', '2022-04-04 14:44:22', NULL, NULL, NULL),
(141, 'Inserir', 'New agendas', '8', '6', '2022-04-04 08:44:59', '2024-04-04 08:44:59', '2022-04-04 14:44:59', NULL, NULL, NULL),
(142, 'Inserir', 'New agendas', '9', '6', '2022-04-04 08:49:59', '2024-04-04 08:49:59', '2022-04-04 14:49:59', NULL, NULL, NULL),
(143, 'Inserir', 'New agendas', '10', '6', '2022-04-04 08:50:08', '2024-04-04 08:50:08', '2022-04-04 14:50:08', NULL, NULL, NULL),
(144, 'Inserir', 'New agendas', '11', '6', '2022-04-04 08:50:18', '2024-04-04 08:50:18', '2022-04-04 14:50:18', NULL, NULL, NULL),
(145, 'Inserir', 'New agendas', '12', '6', '2022-04-04 08:52:27', '2024-04-04 08:52:27', '2022-04-04 14:52:27', NULL, NULL, NULL),
(146, 'Inserir', 'New agendas', '13', '6', '2022-04-04 08:54:52', '2024-04-04 08:54:52', '2022-04-04 14:54:52', NULL, NULL, NULL),
(147, 'Inserir', 'New agendas', '14', '6', '2022-04-04 08:55:17', '2024-04-04 08:55:17', '2022-04-04 14:55:17', NULL, NULL, NULL),
(148, 'Inserir', 'New agendas', '15', '6', '2022-04-04 08:58:58', '2024-04-04 08:58:58', '2022-04-04 14:58:58', NULL, NULL, NULL),
(149, 'Inserir', 'New agendas', '16', '6', '2022-04-04 08:59:54', '2024-04-04 08:59:54', '2022-04-04 14:59:54', NULL, NULL, NULL),
(150, 'Inserir', 'New agendas', '17', '6', '2022-04-04 09:00:26', '2024-04-04 09:00:26', '2022-04-04 15:00:26', NULL, NULL, NULL),
(151, 'Inserir', 'New agendas', '18', '6', '2022-04-04 09:00:37', '2024-04-04 09:00:37', '2022-04-04 15:00:37', NULL, NULL, NULL),
(152, 'Inserir', 'New agendas', '19', '6', '2022-04-05 01:52:57', '2024-04-05 01:52:57', '2022-04-05 07:52:57', NULL, NULL, NULL),
(153, 'Inserir', 'New agendas', '20', '6', '2022-04-05 02:41:17', '2024-04-05 02:41:17', '2022-04-05 08:41:17', NULL, NULL, NULL),
(154, 'Inserir', 'New agendas', '21', '6', '2022-04-05 02:55:09', '2024-04-05 02:55:09', '2022-04-05 08:55:10', NULL, NULL, NULL),
(155, 'Inserir', 'New agendas', '22', '6', '2022-04-06 06:35:12', '2024-04-06 06:35:12', '2022-04-06 12:35:12', NULL, NULL, NULL),
(156, 'Inserir', 'New viaturas', '19', '6', '2022-04-06 09:04:52', '2024-04-06 09:04:52', '2022-04-06 15:04:52', NULL, NULL, NULL),
(157, 'Actualizar', 'Update viaturas', '3', '6', '2022-04-06 00:00:00', '2024-04-06 09:37:40', '2022-04-06 15:37:40', NULL, NULL, NULL),
(158, 'Actualizar', 'Update viaturas', '3', '6', '2022-04-06 00:00:00', '2024-04-06 09:44:31', '2022-04-06 15:44:31', NULL, NULL, NULL),
(159, 'Actualizar', 'Update viaturas', '3', '6', '2022-04-06 00:00:00', '2024-04-06 09:45:29', '2022-04-06 15:45:29', NULL, NULL, NULL),
(160, 'Actualizar', 'Update viaturas', '3', '6', '2022-04-06 00:00:00', '2024-04-06 10:01:58', '2022-04-06 16:01:58', NULL, NULL, NULL),
(161, 'Actualizar', 'Update viaturas', '3', '6', '2022-04-06 00:00:00', '2024-04-06 10:02:06', '2022-04-06 16:02:06', NULL, NULL, NULL),
(162, 'Actualizar', 'Update viaturas', '3', '6', '2022-04-06 00:00:00', '2024-04-06 10:02:13', '2022-04-06 16:02:13', NULL, NULL, NULL),
(163, 'Inserir', 'New viaturas', '0', '6', '2022-04-07 06:10:33', '2024-04-07 06:10:33', '2022-04-07 12:10:33', NULL, NULL, NULL),
(164, 'Inserir', 'New gestao_viaturas', '1', '6', '2022-04-07 06:13:15', '2024-04-07 06:13:15', '2022-04-07 12:13:15', NULL, NULL, NULL),
(165, 'Inserir', 'New gestao_viaturas', '2', '6', '2022-04-07 06:26:14', '2024-04-07 06:26:14', '2022-04-07 12:26:14', NULL, NULL, NULL),
(166, 'Inserir', 'New agendas', '23', '6', '2022-04-09 06:52:56', '2024-04-09 06:52:56', '2022-04-09 12:52:57', NULL, NULL, NULL),
(167, 'Inserir', 'New agendas', '24', '6', '2022-04-09 09:53:19', '2024-04-09 09:53:19', '2022-04-09 15:53:19', NULL, NULL, NULL),
(168, 'Inserir', 'New agendas', '25', '6', '2022-04-10 04:16:02', '2024-04-10 04:16:02', '2022-04-10 10:16:03', NULL, NULL, NULL),
(169, 'Deletar', 'Deletar agendas', '24', '6', '2022-04-11 00:00:00', '2024-04-11 02:17:53', '2022-04-11 08:17:53', NULL, NULL, NULL),
(170, 'Actualizar', 'Update agendas', '23', '6', '2022-04-11 00:00:00', '2024-04-11 02:45:05', '2022-04-11 08:45:05', NULL, NULL, NULL),
(171, 'Actualizar', 'Update agendas', '23', '6', '2022-04-11 00:00:00', '2024-04-11 02:54:36', '2022-04-11 08:54:36', NULL, NULL, NULL),
(172, 'Actualizar', 'Update agendas', '23', '6', '2022-04-11 00:00:00', '2024-04-11 02:54:44', '2022-04-11 08:54:44', NULL, NULL, NULL),
(173, 'Actualizar', 'Update agendas', '23', '6', '2022-04-11 00:00:00', '2024-04-11 02:58:26', '2022-04-11 08:58:26', NULL, NULL, NULL),
(174, 'Actualizar', 'Update agendas', '25', '6', '2022-04-11 00:00:00', '2024-04-11 06:39:05', '2022-04-11 12:39:05', NULL, NULL, NULL),
(175, 'Actualizar', 'Update agendas', '23', '6', '2022-04-11 00:00:00', '2024-04-11 06:39:11', '2022-04-11 12:39:11', NULL, NULL, NULL),
(176, 'Actualizar', 'Update agendas', '23', '6', '2022-04-11 00:00:00', '2024-04-11 06:45:06', '2022-04-11 12:45:06', NULL, NULL, NULL),
(177, 'Inserir', 'New marcas', '2', '1', '2022-04-11 07:45:04', '2024-04-11 07:45:04', '2022-04-11 13:45:04', NULL, NULL, NULL),
(178, 'Inserir', 'New gestao_viaturas', '3', '6', '2022-04-12 02:13:37', '2024-04-12 02:13:37', '2022-04-12 08:13:38', NULL, NULL, NULL),
(179, 'Inserir', 'New gestao_viaturas', '4', '6', '2022-04-12 02:13:40', '2024-04-12 02:13:40', '2022-04-12 08:13:40', NULL, NULL, NULL),
(180, 'Inserir', 'New viaturas', '24', '6', '2022-04-13 01:44:50', '2024-04-13 01:44:50', '2022-04-13 07:44:50', NULL, NULL, NULL),
(181, 'Inserir', 'New viaturas', '33', '6', '2022-04-13 03:10:45', '2024-04-13 03:10:45', '2022-04-13 09:10:45', NULL, NULL, NULL),
(182, 'Actualizar', 'Update agendas', '23', '6', '2022-04-24 00:00:00', '2024-04-24 03:47:36', '2022-04-24 09:47:36', NULL, NULL, NULL),
(183, 'Actualizar', 'Update agendas', '23', '6', '2022-04-24 00:00:00', '2024-04-24 03:47:46', '2022-04-24 09:47:46', NULL, NULL, NULL),
(184, 'Inserir', 'New viaturas', '0', '0', '2022-05-11 10:13:46', '2024-05-11 10:13:46', '2022-05-11 16:13:47', NULL, NULL, NULL),
(185, 'Inserir', 'New viaturas', '0', '0', '2022-05-11 10:14:45', '2024-05-11 10:14:45', '2022-05-11 16:14:45', NULL, NULL, NULL),
(186, 'Inserir', 'New viaturas', '0', '0', '2022-05-11 10:17:38', '2024-05-11 10:17:38', '2022-05-11 16:17:38', NULL, NULL, NULL),
(187, 'Inserir', 'New viaturas', '0', '0', '2022-05-11 10:17:45', '2024-05-11 10:17:45', '2022-05-11 16:17:45', NULL, NULL, NULL),
(188, 'Inserir', 'New viaturas', '0', '0', '2022-05-11 10:18:17', '2024-05-11 10:18:17', '2022-05-11 16:18:17', NULL, NULL, NULL),
(189, 'Inserir', 'New viaturas', '35', '0', '2022-05-11 10:19:31', '2024-05-11 10:19:31', '2022-05-11 16:19:31', NULL, NULL, NULL),
(190, 'Inserir', 'New viaturas', '37', '0', '2022-05-11 10:20:34', '2024-05-11 10:20:34', '2022-05-11 16:20:34', NULL, NULL, NULL),
(191, 'Actualizar', 'Update agendas', '26', '6', '2022-05-16 00:00:00', '2024-05-16 11:37:00', '2022-05-16 17:37:00', NULL, NULL, NULL),
(192, 'Inserir', 'New contas', '44', '0', '2022-06-07 09:08:53', '2024-06-07 09:08:53', '2022-06-07 15:08:53', NULL, NULL, NULL),
(193, 'Inserir', 'New proprietarios', '41', '0', '2022-06-07 09:08:56', '2024-06-07 09:08:56', '2022-06-07 15:08:56', NULL, NULL, NULL),
(194, 'Inserir', 'New utilizadors', '15', '1', '2022-06-07 09:08:56', '2024-06-07 09:08:56', '2022-06-07 15:08:56', NULL, NULL, NULL),
(195, 'Actualizar', 'Update utilizadors', '6', '6', '2022-06-17 00:00:00', '2024-06-17 16:33:31', '2022-06-17 16:33:31', NULL, NULL, NULL),
(196, 'Actualizar', 'Update utilizadors', '6', '1', '2022-06-17 00:00:00', '2024-06-17 16:39:50', '2022-06-17 16:39:50', NULL, NULL, NULL),
(197, 'Actualizar', 'Actualizar Utilizador', '6', '1', '2022-06-17 00:00:00', '2024-06-17 16:39:50', '2022-06-17 16:39:50', NULL, NULL, NULL),
(198, 'Actualizar', 'Update proprietarios', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:39:50', '2022-06-17 16:39:50', NULL, NULL, NULL),
(199, 'Actualizar', 'Update contas', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:39:50', '2022-06-17 16:39:50', NULL, NULL, NULL),
(200, 'Actualizar', 'Update utilizadors', '6', '1', '2022-06-17 00:00:00', '2024-06-17 16:39:55', '2022-06-17 16:39:55', NULL, NULL, NULL),
(201, 'Actualizar', 'Actualizar Utilizador', '6', '1', '2022-06-17 00:00:00', '2024-06-17 16:39:55', '2022-06-17 16:39:55', NULL, NULL, NULL),
(202, 'Actualizar', 'Update proprietarios', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:39:55', '2022-06-17 16:39:55', NULL, NULL, NULL),
(203, 'Actualizar', 'Update contas', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:39:55', '2022-06-17 16:39:55', NULL, NULL, NULL),
(204, 'Actualizar', 'Actualizar Utilizador', '6', '1', '2022-06-17 00:00:00', '2024-06-17 16:40:03', '2022-06-17 16:40:03', NULL, NULL, NULL),
(205, 'Actualizar', 'Update proprietarios', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:40:03', '2022-06-17 16:40:03', NULL, NULL, NULL),
(206, 'Actualizar', 'Update contas', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:40:03', '2022-06-17 16:40:03', NULL, NULL, NULL),
(207, 'Actualizar', 'Actualizar Utilizador', '6', '1', '2022-06-17 00:00:00', '2024-06-17 16:41:23', '2022-06-17 16:41:23', NULL, NULL, NULL),
(208, 'Actualizar', 'Update proprietarios', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:41:23', '2022-06-17 16:41:23', NULL, NULL, NULL),
(209, 'Actualizar', 'Update contas', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:41:23', '2022-06-17 16:41:23', NULL, NULL, NULL),
(210, 'Actualizar', 'Actualizar Utilizador', '6', '1', '2022-06-17 00:00:00', '2024-06-17 16:42:41', '2022-06-17 16:42:41', NULL, NULL, NULL),
(211, 'Actualizar', 'Update proprietarios', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:42:41', '2022-06-17 16:42:41', NULL, NULL, NULL),
(212, 'Actualizar', 'Actualizar Utilizador', '6', '1', '2022-06-17 00:00:00', '2024-06-17 16:42:48', '2022-06-17 16:42:48', NULL, NULL, NULL),
(213, 'Actualizar', 'Update proprietarios', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:42:48', '2022-06-17 16:42:48', NULL, NULL, NULL),
(214, 'Actualizar', 'Actualizar Utilizador', '6', '1', '2022-06-17 00:00:00', '2024-06-17 16:43:21', '2022-06-17 16:43:21', NULL, NULL, NULL),
(215, 'Actualizar', 'Update proprietarios', '16', '1', '2022-06-17 00:00:00', '2024-06-17 16:43:21', '2022-06-17 16:43:21', NULL, NULL, NULL),
(216, 'Actualizar', 'Update contas', '1', '1', '2022-06-17 00:00:00', '2024-06-17 16:43:21', '2022-06-17 16:43:21', NULL, NULL, NULL),
(217, 'Inserir', 'New itemfacturas', '12', '1', '2022-06-24 13:21:58', '2024-06-24 13:21:58', '2022-06-24 13:21:59', NULL, NULL, NULL),
(218, 'Inserir', 'New agendas', '44', '1', '2022-06-24 13:22:25', '2024-06-24 13:22:25', '2022-06-24 13:22:25', NULL, NULL, NULL),
(219, 'Inserir', 'New facturas', '14', '1', '2022-06-24 13:22:25', '2024-06-24 13:22:25', '2022-06-24 13:22:25', NULL, NULL, NULL),
(220, 'Inserir', 'New itemfacturas', '13', '1', '2022-06-24 13:22:26', '2024-06-24 13:22:26', '2022-06-24 13:22:26', NULL, NULL, NULL),
(221, 'Inserir', 'New agendas', '45', '1', '2022-06-24 13:24:49', '2024-06-24 13:24:49', '2022-06-24 13:24:49', NULL, NULL, NULL),
(222, 'Inserir', 'New facturas', '15', '1', '2022-06-24 13:24:49', '2024-06-24 13:24:49', '2022-06-24 13:24:49', NULL, NULL, NULL),
(223, 'Inserir', 'New itemfacturas', '14', '1', '2022-06-24 13:24:50', '2024-06-24 13:24:50', '2022-06-24 13:24:50', NULL, NULL, NULL),
(224, 'Inserir', 'New contas', '48', '1', '2022-07-26 10:14:26', '2024-07-26 10:14:26', '2022-07-26 10:14:26', NULL, NULL, NULL),
(225, 'Inserir', 'New proprietarios', '42', '1', '2022-07-26 10:14:27', '2024-07-26 10:14:27', '2022-07-26 10:14:27', NULL, NULL, NULL),
(226, 'Inserir', 'New contas', '49', '1', '2022-07-26 10:34:34', '2024-07-26 10:34:34', '2022-07-26 10:34:34', NULL, NULL, NULL),
(227, 'Inserir', 'New proprietarios', '43', '1', '2022-07-26 10:34:34', '2024-07-26 10:34:34', '2022-07-26 10:34:34', NULL, NULL, NULL),
(228, 'Inserir', 'New contas', '50', '1', '2022-07-26 10:38:20', '2024-07-26 10:38:20', '2022-07-26 10:38:20', NULL, NULL, NULL),
(229, 'Inserir', 'New proprietarios', '44', '1', '2022-07-26 10:38:20', '2024-07-26 10:38:20', '2022-07-26 10:38:20', NULL, NULL, NULL),
(230, 'Inserir', 'New contas', '51', '1', '2022-07-26 10:40:03', '2024-07-26 10:40:03', '2022-07-26 10:40:03', NULL, NULL, NULL),
(231, 'Inserir', 'New proprietarios', '45', '1', '2022-07-26 10:40:03', '2024-07-26 10:40:03', '2022-07-26 10:40:03', NULL, NULL, NULL),
(232, 'Inserir', 'New contas', '52', '1', '2022-07-26 10:49:07', '2024-07-26 10:49:07', '2022-07-26 10:49:07', NULL, NULL, NULL),
(233, 'Inserir', 'New proprietarios', '46', '1', '2022-07-26 10:49:07', '2024-07-26 10:49:07', '2022-07-26 10:49:07', NULL, NULL, NULL),
(234, 'Inserir', 'New utilizadors', '16', '1', '2022-07-26 10:49:07', '2024-07-26 10:49:07', '2022-07-26 10:49:07', NULL, NULL, NULL),
(235, 'Inserir', 'New contas', '53', '1', '2022-07-26 10:55:42', '2024-07-26 10:55:42', '2022-07-26 10:55:42', NULL, NULL, NULL),
(236, 'Inserir', 'New proprietarios', '47', '1', '2022-07-26 10:55:42', '2024-07-26 10:55:42', '2022-07-26 10:55:42', NULL, NULL, NULL),
(237, 'Inserir', 'New utilizadors', '17', '1', '2022-07-26 10:55:42', '2024-07-26 10:55:42', '2022-07-26 10:55:42', NULL, NULL, NULL),
(238, 'Actualizar', 'Update utilizadors', '17', '17', '2022-07-27 00:00:00', '2024-07-27 09:44:27', '2022-07-27 09:44:27', NULL, NULL, NULL),
(239, 'Actualizar', 'Update utilizadors', '17', '17', '2022-07-27 00:00:00', '2024-07-27 10:13:26', '2022-07-27 10:13:26', NULL, NULL, NULL),
(240, 'Actualizar', 'Update utilizadors', '17', '17', '2022-07-27 00:00:00', '2024-07-27 10:15:32', '2022-07-27 10:15:32', NULL, NULL, NULL),
(241, 'Actualizar', 'Update utilizadors', '17', '17', '2022-07-27 00:00:00', '2024-07-27 23:52:47', '2022-07-27 23:52:47', NULL, NULL, NULL),
(242, 'Actualizar', 'Update utilizadors', '17', '17', '2022-07-27 00:00:00', '2024-07-27 23:59:46', '2022-07-27 23:59:46', NULL, NULL, NULL),
(243, 'Actualizar', 'Update utilizadors', '17', '17', '2022-07-28 00:00:00', '2024-07-28 00:00:18', '2022-07-28 00:00:18', NULL, NULL, NULL),
(244, 'Actualizar', 'Update utilizadors', '17', '17', '2022-07-28 00:00:00', '2024-07-28 00:20:08', '2022-07-28 00:20:08', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacao_servico`
--

CREATE TABLE `avaliacao_servico` (
  `id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `prestador_id` int(11) NOT NULL,
  `avaliador_id` int(11) NOT NULL,
  `pontuacao` float(2,2) NOT NULL,
  `dataservico` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contactos`
--

CREATE TABLE `contactos` (
  `id` int(11) NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `tipo` varchar(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `proprietario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contas`
--

CREATE TABLE `contas` (
  `id` int(11) NOT NULL,
  `nif` varchar(14) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `tipo_conta` int(11) DEFAULT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `contas`
--

INSERT INTO `contas` (`id`, `nif`, `created_at`, `updated_at`, `deleted_at`, `tipo_conta`, `nome`) VALUES
(14, NULL, '2022-03-30 16:19:14', NULL, NULL, NULL, 'Arsénio Muanda'),
(41, NULL, '2022-04-03 15:57:07', NULL, NULL, NULL, 'Paulo Zeferino'),
(42, NULL, '2022-05-10 12:45:25', NULL, NULL, NULL, 'Arsenio'),
(43, NULL, '2022-06-07 14:51:28', NULL, NULL, NULL, 'Teresa Iracelma'),
(44, NULL, '2022-06-07 15:08:53', NULL, NULL, NULL, 'Teste kSHD'),
(45, '9889753987434', '2022-06-17 16:42:41', NULL, NULL, NULL, 'Arsénio Muanda'),
(46, '9889753987434', '2022-06-17 16:42:48', NULL, NULL, NULL, 'Arsénio Muanda'),
(53, NULL, '2022-07-26 10:55:42', NULL, NULL, NULL, 'Arsénio 2');

-- --------------------------------------------------------

--
-- Estrutura da tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `id` int(11) NOT NULL,
  `pais` varchar(40) DEFAULT NULL,
  `provincia` varchar(40) DEFAULT NULL,
  `municipio` varchar(40) DEFAULT NULL,
  `ditrito` varchar(100) DEFAULT NULL,
  `comuna` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `n_casa` varchar(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `proprietario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `agenda` int(11) NOT NULL,
  `proprietario` int(11) NOT NULL,
  `criadopor` int(11) NOT NULL,
  `datafactura` date NOT NULL,
  `conta` int(11) NOT NULL,
  `estado` int(1) NOT NULL DEFAULT '0',
  `hash_factura` varchar(250) DEFAULT NULL,
  `hash_recibo` varchar(200) DEFAULT NULL,
  `final` int(1) NOT NULL DEFAULT '0',
  `pago` int(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `facturas`
--

INSERT INTO `facturas` (`id`, `agenda`, `proprietario`, `criadopor`, `datafactura`, `conta`, `estado`, `hash_factura`, `hash_recibo`, `final`, `pago`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 18:36:26', NULL, NULL),
(2, 0, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 18:45:25', NULL, NULL),
(3, 0, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 18:47:50', NULL, NULL),
(4, 0, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 18:47:54', NULL, NULL),
(5, 0, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 18:55:37', NULL, NULL),
(6, 0, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 18:57:10', NULL, NULL),
(7, 0, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 18:57:38', NULL, NULL),
(8, 0, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 18:58:12', NULL, NULL),
(9, 39, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 18:59:20', '2022-05-25 19:12:45', NULL),
(10, 40, 16, 1, '2022-05-25', 1, 0, NULL, NULL, 0, 0, '2022-05-25 19:36:06', NULL, NULL),
(11, 41, 16, 1, '2022-06-01', 1, 0, NULL, NULL, 1, 0, '2022-06-01 15:06:35', '2022-06-06 14:38:56', NULL),
(12, 42, 16, 1, '2022-06-02', 1, 0, NULL, NULL, 0, 0, '2022-06-02 13:30:04', NULL, NULL),
(13, 43, 16, 1, '2022-06-02', 1, 0, NULL, NULL, 0, 0, '2022-06-02 13:31:46', NULL, NULL),
(14, 44, 16, 1, '2022-06-24', 1, 0, NULL, NULL, 1, 0, '2022-06-24 13:22:25', '2022-08-02 18:50:04', NULL),
(15, 45, 16, 1, '2022-06-24', 1, 0, NULL, NULL, 0, 0, '2022-06-24 13:24:49', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `gestao_viaturas`
--

CREATE TABLE `gestao_viaturas` (
  `id` int(11) NOT NULL,
  `km_actual` double DEFAULT NULL,
  `periodo_de_revisao` double NOT NULL,
  `km_diaria_dias_semana` double DEFAULT NULL,
  `km_diaria_final_semana` double DEFAULT NULL,
  `data_ultima_revisao` date DEFAULT NULL,
  `km_na_ultima_revisao` double DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `viatura` int(11) DEFAULT NULL,
  `tipo_oleo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `gestao_viaturas`
--

INSERT INTO `gestao_viaturas` (`id`, `km_actual`, `periodo_de_revisao`, `km_diaria_dias_semana`, `km_diaria_final_semana`, `data_ultima_revisao`, `km_na_ultima_revisao`, `created_at`, `updated_at`, `deleted_at`, `viatura`, `tipo_oleo`) VALUES
(1, 0, 0, 0, 0, '0000-00-00', 0, NULL, NULL, NULL, 3, 0),
(2, 12, 0, 2, 1, '2022-04-17', 3, NULL, NULL, NULL, 3, 1),
(3, 120, 0, 80, 40, '2022-04-17', 3, NULL, NULL, NULL, 3, 0),
(4, 120, 0, 80, 40, '2022-04-17', 3, NULL, NULL, NULL, 3, 0),
(5, 0, 0, 60, 20, NULL, 0, NULL, NULL, NULL, 27, NULL),
(6, 0, 0, 60, 20, NULL, 0, NULL, NULL, NULL, 29, NULL),
(7, 0, 0, 60, 20, '2022-04-13', 0, NULL, NULL, NULL, 33, NULL),
(8, 0, 0, 60, 20, '2022-05-11', 0, NULL, NULL, NULL, 35, NULL),
(9, 0, 0, 60, 20, '2022-05-11', 0, NULL, NULL, NULL, 37, NULL),
(10, 7000, 5000, 80, 40, '2022-07-11', 5000, NULL, NULL, NULL, 3, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `itemfacturas`
--

CREATE TABLE `itemfacturas` (
  `id` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `factura` int(11) NOT NULL,
  `valor` double NOT NULL,
  `criadopor` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `qntidade` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `conta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `itemfacturas`
--

INSERT INTO `itemfacturas` (`id`, `itemId`, `factura`, `valor`, `criadopor`, `nome`, `qntidade`, `created_at`, `updated_at`, `deleted_at`, `conta`) VALUES
(1, 0, 9, 2000, 1, 'Serviço de Entrega', 1, '2022-05-25 18:59:20', NULL, NULL, 1),
(2, 0, 10, 2000, 1, 'Serviço de Entrega', 1, '2022-05-25 19:36:07', NULL, NULL, 1),
(3, 0, 11, 2000, 1, 'Serviço de Entrega', 1, '2022-06-01 15:06:35', NULL, NULL, 1),
(4, 0, 11, 2000, 1, 'Serviço de Entrega', 1, '2022-06-01 15:07:33', NULL, NULL, 1),
(5, 0, 11, 2000, 1, 'Serviço de Entrega', 1, '2022-06-01 15:07:52', NULL, NULL, 1),
(6, 0, 11, 2000, 1, 'Serviço de Entrega', 1, '2022-06-02 13:06:46', NULL, NULL, 1),
(7, 0, 11, 2000, 1, 'Serviço de Entrega', 1, '2022-06-02 13:06:49', NULL, NULL, 1),
(8, 0, 11, 2000, 1, 'Serviço de Entrega', 1, '2022-06-02 13:06:51', NULL, NULL, 1),
(9, 0, 12, 2000, 1, 'Serviço de Entrega', 1, '2022-06-02 13:30:04', NULL, NULL, 1),
(10, 0, 13, 2000, 1, 'Serviço de Entrega', 1, '2022-06-02 13:31:46', NULL, NULL, 1),
(11, 0, 11, 2000, 1, 'Serviço de Entrega', 1, '2022-06-03 12:32:11', NULL, NULL, 1),
(12, 0, 11, 40000, 1, 'Lavagem de Interior', 1, '2022-06-24 13:21:58', NULL, NULL, 1),
(13, 0, 14, 2000, 1, 'Seviço de Entrega Meu Ruca', 1, '2022-06-24 13:22:26', NULL, NULL, 1),
(14, 0, 15, 5000, 1, 'Seviço de Entrega Meu Ruca', 1, '2022-06-24 13:24:49', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `marcas`
--

INSERT INTO `marcas` (`id`, `nome`, `created_at`, `updated_at`, `deleted_at`, `descricao`) VALUES
(1, 'Toyota', NULL, NULL, NULL, NULL),
(2, 'Paulo Zeferino', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `modelos`
--

CREATE TABLE `modelos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `marca` int(11) NOT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `modelos`
--

INSERT INTO `modelos` (`id`, `nome`, `created_at`, `updated_at`, `deleted_at`, `marca`, `descricao`) VALUES
(1, 'Corola', NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `oleo_gestao_viaturas`
--

CREATE TABLE `oleo_gestao_viaturas` (
  `id` int(11) NOT NULL,
  `tipo_oleo` int(11) DEFAULT NULL,
  `descricao` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `gestao_viatura` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `perfil_agenda`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `perfil_agenda` (
`id` int(11)
,`ticket` varchar(40)
,`inicio` datetime
,`fim` datetime
,`descricao` text
,`activo` int(1)
,`estado` int(1)
,`created_at` datetime
,`updated_at` datetime
,`deleted_at` datetime
,`nome_item` varchar(100)
,`viatura` int(11)
,`categoria` varchar(20)
,`prestador` int(11)
,`servico_entrega` int(1)
,`is_domicilio` int(1)
,`matricula` varchar(13)
,`imagemViatura` varchar(255)
,`modeloViatura` varchar(100)
,`marcaViatura` varchar(100)
,`anoFabrico` varchar(4)
,`nomePrestador` varchar(40)
,`telefonePrestador` varchar(19)
,`emailPrestador` varchar(150)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `prestadors`
--

CREATE TABLE `prestadors` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `nif` varchar(19) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefone` varchar(19) NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `criadopor` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `site` varchar(200) DEFAULT NULL,
  `androidlink` varchar(200) DEFAULT NULL,
  `ioslink` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `gps_latitude` int(19) DEFAULT NULL,
  `gps_longitude` varchar(19) DEFAULT NULL,
  `w3w` varchar(100) DEFAULT NULL,
  `country` varchar(30) NOT NULL DEFAULT 'Angola',
  `provincia` varchar(30) NOT NULL DEFAULT 'Luanda',
  `municipio` varchar(30) DEFAULT NULL,
  `distrito` varchar(30) DEFAULT NULL,
  `comuna` varchar(30) DEFAULT NULL,
  `bairro` varchar(30) DEFAULT NULL,
  `n_casa` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `prestadors`
--

INSERT INTO `prestadors` (`id`, `nome`, `nif`, `email`, `telefone`, `endereco`, `criadopor`, `foto`, `site`, `androidlink`, `ioslink`, `created_at`, `updated_at`, `deleted_at`, `gps_latitude`, `gps_longitude`, `w3w`, `country`, `provincia`, `municipio`, `distrito`, `comuna`, `bairro`, `n_casa`) VALUES
(1, 'Arsénio Serviços', '00098463552342', 'ars@gmail.com', '990990998', 'Kilamba, R2, Edificio 12.', 1, '', 'www.arsenio.com', '', '', '2022-05-25 12:45:34', NULL, NULL, 0, '', '', 'Angola', 'Luanda', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `proprietarios`
--

CREATE TABLE `proprietarios` (
  `bi` varchar(14) DEFAULT NULL,
  `passport` varchar(14) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `nome` varchar(40) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `conta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `proprietarios`
--

INSERT INTO `proprietarios` (`bi`, `passport`, `id`, `nome`, `created_at`, `updated_at`, `deleted_at`, `conta`) VALUES
('9889753987434', NULL, 16, 'Arsénio Muanda', '2022-03-30 16:19:14', '2022-06-17 16:41:23', NULL, 14),
(NULL, NULL, 38, 'Paulo Zeferino', '2022-04-03 15:57:07', NULL, NULL, 41),
(NULL, NULL, 39, 'Arsenio', '2022-05-10 12:45:28', NULL, NULL, 42),
(NULL, NULL, 40, 'Teresa Iracelma', '2022-06-07 14:51:30', NULL, NULL, 43),
(NULL, NULL, 41, 'Teste kSHD', '2022-06-07 15:08:55', NULL, NULL, 44),
(NULL, NULL, 47, 'Arsénio 2', '2022-07-26 10:55:42', NULL, NULL, 53);

-- --------------------------------------------------------

--
-- Estrutura da tabela `recibo_pagamento`
--

CREATE TABLE `recibo_pagamento` (
  `id` int(11) NOT NULL,
  `pagamento` int(11) NOT NULL,
  `valor` double NOT NULL,
  `datapagamento` date NOT NULL,
  `comprovativo` varchar(255) DEFAULT NULL,
  `borderoux` varchar(250) NOT NULL,
  `hash_recibo` varchar(16) NOT NULL,
  `criador` int(11) NOT NULL,
  `firma` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `service_prestador`
--

CREATE TABLE `service_prestador` (
  `id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `prestador_id` int(11) NOT NULL,
  `descricao` varchar(300) NOT NULL,
  `firma` int(11) NOT NULL,
  `criadopor` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servicos`
--

CREATE TABLE `servicos` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `nome` varchar(60) NOT NULL,
  `categoria` int(11) NOT NULL,
  `descricao` varchar(400) DEFAULT NULL,
  `criadopor` int(11) NOT NULL,
  `is_aprovado` int(1) NOT NULL DEFAULT '0',
  `is_domicilio` int(1) NOT NULL DEFAULT '0',
  `valor` double NOT NULL,
  `prestador` int(11) NOT NULL,
  `conta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `servicos`
--

INSERT INTO `servicos` (`id`, `created_at`, `updated_at`, `deleted_at`, `nome`, `categoria`, `descricao`, `criadopor`, `is_aprovado`, `is_domicilio`, `valor`, `prestador`, `conta`) VALUES
(1, '2022-05-25 14:05:10', '2022-05-26 08:05:19', NULL, 'Lavagem de Interior', 1, 'Lavar dentro do carro tipo nada, vais ficar malaike', 0, 0, 0, 2000, 1, 0),
(2, '2022-05-25 18:47:23', '2022-05-25 18:55:57', NULL, 'Seviço de Entrega Meu Ruca', 3, 'Meu Ruca leva o teu carro...', 0, 0, 0, 2000, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `subscricoes`
--

CREATE TABLE `subscricoes` (
  `id` int(10) NOT NULL,
  `nome` varchar(250) COLLATE utf8_esperanto_ci NOT NULL,
  `plano` int(10) NOT NULL,
  `inicio` date NOT NULL,
  `fim` date NOT NULL,
  `utilizadores` int(5) NOT NULL,
  `documentos` int(5) NOT NULL,
  `pago` double NOT NULL,
  `firma` int(10) DEFAULT '1',
  `entidade` int(5) UNSIGNED ZEROFILL NOT NULL,
  `subentidade` int(3) UNSIGNED ZEROFILL NOT NULL,
  `referencia` varchar(15) COLLATE utf8_esperanto_ci NOT NULL,
  `valor` double NOT NULL,
  `estado_pag` varchar(25) COLLATE utf8_esperanto_ci NOT NULL,
  `datacriacao` datetime DEFAULT NULL,
  `dataedicao` datetime DEFAULT NULL,
  `criadopor` int(10) DEFAULT NULL,
  `descricao_plano` varchar(250) COLLATE utf8_esperanto_ci NOT NULL,
  `apartamento` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_esperanto_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_contas`
--

CREATE TABLE `tipo_contas` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_items`
--

CREATE TABLE `tipo_items` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) DEFAULT NULL,
  `decricao` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_oleos`
--

CREATE TABLE `tipo_oleos` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_servicos`
--

CREATE TABLE `tipo_servicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(40) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tipo_servicos`
--

INSERT INTO `tipo_servicos` (`id`, `nome`, `created_at`, `updated_at`, `deleted_at`, `descricao`) VALUES
(1, 'Lavagem', '0000-00-00 00:00:00', NULL, NULL, NULL),
(2, 'Manutenção', '0000-00-00 00:00:00', NULL, NULL, NULL),
(3, 'Meu Ruca', '0000-00-00 00:00:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizacaos`
--

CREATE TABLE `utilizacaos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `inicio` date DEFAULT NULL,
  `fim` date DEFAULT NULL,
  `descricao` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `gestao_viatura` int(11) DEFAULT NULL,
  `tipo_item` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadors`
--

CREATE TABLE `utilizadors` (
  `id` int(11) NOT NULL,
  `tipo` int(2) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `reset_token` varchar(300) NOT NULL,
  `api_token_date` datetime DEFAULT NULL,
  `api_token` text,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `autenticacao` varchar(255) DEFAULT NULL,
  `perfil` int(2) DEFAULT NULL,
  `estado` int(1) DEFAULT '1',
  `ultimoAcesso` datetime DEFAULT NULL,
  `criadopor` int(11) NOT NULL DEFAULT '1',
  `telefone` varchar(19) DEFAULT NULL,
  `acesso` int(11) DEFAULT NULL,
  `proprietario` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `utilizadors`
--

INSERT INTO `utilizadors` (`id`, `tipo`, `username`, `reset_token`, `api_token_date`, `api_token`, `email`, `password`, `autenticacao`, `perfil`, `estado`, `ultimoAcesso`, `criadopor`, `telefone`, `acesso`, `proprietario`, `foto`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6, 1, 'arsmuanda', '56b7c68d3f2d6634e740a5e71bf374b4', NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJUSEVfQ0xBSU4iLCJhdWQiOiJUSEVfQVVESUVOQ0UiLCJpYXQiOjE2NTc1NzcyMDQsIm5iZiI6MTY1NzU3NzIwNCwiZXhwIjoxNjU3NjY2MjA0LCJkYXRhIjp7ImFjZXNzbyI6MSwiZW1haWwiOiJhcnNlbXVhbmRhQGdtYWlsLmNvbSIsImlkIjoiNiIsImNvbnRhIjoiMTQifX0.lcGTJDtJQfPIQACHOn5JX6kfMVkKaMzTEOekJquHsBw', 'arsemuanda@gmail.com', '$2y$10$33M/yBWjIkWfnoBgbjwqgO./jd1Ft7ALKRQjjyOrrMkRMg2g5.NGq', NULL, 1, NULL, '2022-07-11 23:06:44', 1, '990990994', 1, 16, 'http://localhost:8080/file/utilizadors/6.png', '2022-03-30 16:19:14', '2022-07-11 23:06:44', NULL),
(12, 1, 'arsemuanda5', '2eea4b3f823c0fc3c4d6fbd7b6d5708c', NULL, NULL, 'arsemuanda5@gmail.com', '$2y$10$J9f.fwsdpxxb7lH/3Z5ju.F4bQsh24fzSCvV5agjLThjWLXDjU.vy', NULL, 1, NULL, '2022-04-06 05:47:13', 12, '990998990', 1, 38, NULL, '2022-04-03 15:57:07', '2022-04-06 11:47:13', NULL),
(13, 1, 'arsenio', '79b98881c89417bb62fd2bb54ecbd204', NULL, NULL, 'arsenio@gmail.com', '$2y$10$tqbVZEY7MKrHE3mlkyoQhuNMu2noHQM45aDTHVMAnrUPlgvK85WB2', NULL, 1, NULL, NULL, 0, '900032333', 1, 39, NULL, '2022-05-10 12:45:28', NULL, NULL),
(14, 1, 'teresa', '565bd907e9a901beee2e8e9cd5424232', NULL, NULL, 'teresa@gdj.com', '$2y$10$UI771lkBM5yXbGRO9zHJA.ewYlOURMOSJUkesfOSHEKrp2IwVT0dW', NULL, 1, 1, NULL, 1, '978567456', 1, 40, NULL, '2022-06-07 14:51:31', NULL, NULL),
(15, 1, 'Teste', 'efc5307b52f8f325db91edaf58942736', NULL, NULL, 'Teste@g.dom', '$2y$10$tZP3kQlb29XlAxRfXQFFa.her08DSkDMaaPnnf/2YqPoE/5AihtMi', NULL, 1, 1, '2022-06-07 09:08:58', 1, '965445325', 1, 41, NULL, '2022-06-07 15:08:56', '2022-06-07 15:08:58', NULL),
(17, 1, 'mano', '010ef9070cec4f02c920054c38eb9d89', '2022-07-28 00:02:10', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJUSEVfQ0xBSU4iLCJhdWQiOiJUSEVfQVVESUVOQ0UiLCJpYXQiOjE2NjAzNzgwNTksIm5iZiI6MTY2MDM3ODA1OSwiZXhwIjoxNjYwNDY3MDU5LCJkYXRhIjp7ImFjZXNzbyI6MSwiZW1haWwiOiJtYW5vQGdtYWlsLmNvbSIsImlkIjoiMTciLCJjb250YSI6IjUzIn19.meQpxEpa8fL73u-Ik7jT0YloG2uJJodXdlGvF0spkWg', 'mano@gmail.com', '$2y$10$gFbSn9e2Hn7FWt0FQVOa/uk9RhLCAClsqscj9qyWVvafuHmP0PNxS', NULL, 1, 1, '2022-08-13 09:07:39', 17, '992994003', 1, 47, NULL, '2022-07-26 10:55:42', '2022-08-13 09:07:39', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `viaturas`
--

CREATE TABLE `viaturas` (
  `id` int(11) NOT NULL,
  `matricula` varchar(13) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `proprietario` int(11) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `descricao` text,
  `imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `viaturas`
--

INSERT INTO `viaturas` (`id`, `matricula`, `created_at`, `updated_at`, `deleted_at`, `proprietario`, `ano`, `descricao`, `imagem`) VALUES
(3, 'AA-87-53-AA', NULL, NULL, NULL, 16, 1, '\r\n', ''),
(7, 'AD-32-43-GS', NULL, NULL, NULL, 16, 1, 'Arsénio', ''),
(8, 'AD-32-43-GL', NULL, NULL, NULL, 16, 1, 'jkkl', ''),
(19, 'AR-90-65-RD', NULL, NULL, NULL, 16, 1, '', ''),
(24, 'AS-73-34-AS', NULL, NULL, NULL, 16, 1, '', ''),
(27, 'AS-73-34-AC', NULL, NULL, NULL, 16, 1, '', ''),
(29, 'AS-73-34-AR', NULL, NULL, NULL, 16, 1, '', ''),
(33, 'AS-73-34-AH', NULL, NULL, NULL, 16, 1, '', ''),
(35, '54343454534', NULL, NULL, NULL, 16, 1, NULL, ''),
(37, '543434545', NULL, NULL, NULL, 16, 1, NULL, 'http://localhost:8080/file/viaturas/37.png');

--
-- Acionadores `viaturas`
--
DELIMITER $$
CREATE TRIGGER `add_gestao_0_km` AFTER INSERT ON `viaturas` FOR EACH ROW BEGIN
INSERT INTO `gestao_viaturas`(`km_actual`, `km_diaria_dias_semana`, `km_diaria_final_semana`, `data_ultima_revisao`, `km_na_ultima_revisao`, `viatura`) VALUES (0,60,20,curdate(),0,new.id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para vista `perfil_agenda`
--
DROP TABLE IF EXISTS `perfil_agenda`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `perfil_agenda`  AS SELECT `agendas`.`id` AS `id`, `agendas`.`ticket` AS `ticket`, `agendas`.`inicio` AS `inicio`, `agendas`.`fim` AS `fim`, `agendas`.`descricao` AS `descricao`, `agendas`.`activo` AS `activo`, `agendas`.`estado` AS `estado`, `agendas`.`created_at` AS `created_at`, `agendas`.`updated_at` AS `updated_at`, `agendas`.`deleted_at` AS `deleted_at`, `agendas`.`nome_item` AS `nome_item`, `agendas`.`viatura` AS `viatura`, `agendas`.`categoria` AS `categoria`, `agendas`.`prestador` AS `prestador`, `agendas`.`servico_entrega` AS `servico_entrega`, `agendas`.`is_domicilio` AS `is_domicilio`, `viaturas`.`matricula` AS `matricula`, `viaturas`.`imagem` AS `imagemViatura`, `modelos`.`nome` AS `modeloViatura`, `marcas`.`nome` AS `marcaViatura`, `ano_fabricos`.`nome` AS `anoFabrico`, `prestadors`.`nome` AS `nomePrestador`, `prestadors`.`telefone` AS `telefonePrestador`, `prestadors`.`email` AS `emailPrestador` FROM (((((`agendas` join `viaturas` on((`agendas`.`viatura` = `viaturas`.`id`))) join `ano_fabricos` on((`viaturas`.`ano` = `ano_fabricos`.`id`))) join `modelos` on((`ano_fabricos`.`modelo` = `modelos`.`id`))) join `marcas` on((`modelos`.`marca` = `marcas`.`id`))) left join `prestadors` on((`agendas`.`prestador` = `prestadors`.`id`))) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `acessos`
--
ALTER TABLE `acessos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `acesso_utilizador`
--
ALTER TABLE `acesso_utilizador`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `agendas`
--
ALTER TABLE `agendas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_agenda_2` (`viatura`);

--
-- Índices para tabela `ano_fabricos`
--
ALTER TABLE `ano_fabricos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_ano_fabrico_2` (`modelo`);

--
-- Índices para tabela `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`idauditoria`);

--
-- Índices para tabela `avaliacao_servico`
--
ALTER TABLE `avaliacao_servico`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_contacto_2` (`proprietario`);

--
-- Índices para tabela `contas`
--
ALTER TABLE `contas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_conta_2` (`tipo_conta`);

--
-- Índices para tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_endereco_2` (`proprietario`);

--
-- Índices para tabela `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `gestao_viaturas`
--
ALTER TABLE `gestao_viaturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_gestao_viatura_2` (`viatura`);

--
-- Índices para tabela `itemfacturas`
--
ALTER TABLE `itemfacturas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `modelos`
--
ALTER TABLE `modelos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_modelo_2` (`marca`);

--
-- Índices para tabela `oleo_gestao_viaturas`
--
ALTER TABLE `oleo_gestao_viaturas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_oleo_gestao_viaturas_1` (`tipo_oleo`),
  ADD KEY `FK_Relacionamento_2_2` (`gestao_viatura`);

--
-- Índices para tabela `prestadors`
--
ALTER TABLE `prestadors`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `proprietarios`
--
ALTER TABLE `proprietarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_pessoa_utilizador_proprietario_2` (`conta`);

--
-- Índices para tabela `servicos`
--
ALTER TABLE `servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tipo_contas`
--
ALTER TABLE `tipo_contas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tipo_items`
--
ALTER TABLE `tipo_items`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tipo_oleos`
--
ALTER TABLE `tipo_oleos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tipo_servicos`
--
ALTER TABLE `tipo_servicos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `utilizacaos`
--
ALTER TABLE `utilizacaos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_utilizacao_2` (`gestao_viatura`),
  ADD KEY `FK_utilizacao_3` (`tipo_item`);

--
-- Índices para tabela `utilizadors`
--
ALTER TABLE `utilizadors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `reset_token` (`reset_token`),
  ADD UNIQUE KEY `telefone` (`telefone`);

--
-- Índices para tabela `viaturas`
--
ALTER TABLE `viaturas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD KEY `FK_viatura_2` (`proprietario`),
  ADD KEY `FK_viatura_3` (`ano`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendas`
--
ALTER TABLE `agendas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `ano_fabricos`
--
ALTER TABLE `ano_fabricos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `idauditoria` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT de tabela `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contas`
--
ALTER TABLE `contas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `gestao_viaturas`
--
ALTER TABLE `gestao_viaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `itemfacturas`
--
ALTER TABLE `itemfacturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `modelos`
--
ALTER TABLE `modelos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `oleo_gestao_viaturas`
--
ALTER TABLE `oleo_gestao_viaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `prestadors`
--
ALTER TABLE `prestadors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `proprietarios`
--
ALTER TABLE `proprietarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `servicos`
--
ALTER TABLE `servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tipo_contas`
--
ALTER TABLE `tipo_contas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipo_items`
--
ALTER TABLE `tipo_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipo_oleos`
--
ALTER TABLE `tipo_oleos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipo_servicos`
--
ALTER TABLE `tipo_servicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `utilizacaos`
--
ALTER TABLE `utilizacaos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `utilizadors`
--
ALTER TABLE `utilizadors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `viaturas`
--
ALTER TABLE `viaturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `agendas`
--
ALTER TABLE `agendas`
  ADD CONSTRAINT `FK_agenda_2` FOREIGN KEY (`viatura`) REFERENCES `viaturas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `ano_fabricos`
--
ALTER TABLE `ano_fabricos`
  ADD CONSTRAINT `FK_ano_fabrico_2` FOREIGN KEY (`modelo`) REFERENCES `modelos` (`id`);

--
-- Limitadores para a tabela `contactos`
--
ALTER TABLE `contactos`
  ADD CONSTRAINT `FK_contacto_2` FOREIGN KEY (`proprietario`) REFERENCES `proprietarios` (`id`);

--
-- Limitadores para a tabela `contas`
--
ALTER TABLE `contas`
  ADD CONSTRAINT `FK_conta_2` FOREIGN KEY (`tipo_conta`) REFERENCES `tipo_contas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD CONSTRAINT `FK_endereco_2` FOREIGN KEY (`proprietario`) REFERENCES `proprietarios` (`id`);

--
-- Limitadores para a tabela `gestao_viaturas`
--
ALTER TABLE `gestao_viaturas`
  ADD CONSTRAINT `FK_gestao_viatura_2` FOREIGN KEY (`viatura`) REFERENCES `viaturas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `modelos`
--
ALTER TABLE `modelos`
  ADD CONSTRAINT `FK_modelo_2` FOREIGN KEY (`marca`) REFERENCES `marcas` (`id`);

--
-- Limitadores para a tabela `oleo_gestao_viaturas`
--
ALTER TABLE `oleo_gestao_viaturas`
  ADD CONSTRAINT `FK_Relacionamento_2_2` FOREIGN KEY (`gestao_viatura`) REFERENCES `gestao_viaturas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_oleo_gestao_viaturas_1` FOREIGN KEY (`tipo_oleo`) REFERENCES `tipo_oleos` (`id`);

--
-- Limitadores para a tabela `proprietarios`
--
ALTER TABLE `proprietarios`
  ADD CONSTRAINT `FK_pessoa_utilizador_proprietario_2` FOREIGN KEY (`conta`) REFERENCES `contas` (`id`);

--
-- Limitadores para a tabela `utilizacaos`
--
ALTER TABLE `utilizacaos`
  ADD CONSTRAINT `FK_utilizacao_2` FOREIGN KEY (`gestao_viatura`) REFERENCES `gestao_viaturas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_utilizacao_3` FOREIGN KEY (`tipo_item`) REFERENCES `tipo_items` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `viaturas`
--
ALTER TABLE `viaturas`
  ADD CONSTRAINT `FK_viatura_2` FOREIGN KEY (`proprietario`) REFERENCES `proprietarios` (`id`),
  ADD CONSTRAINT `FK_viatura_3` FOREIGN KEY (`ano`) REFERENCES `ano_fabricos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
