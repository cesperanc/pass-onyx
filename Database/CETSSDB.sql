-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 16-Jun-2013 às 03:57
-- Versão do servidor: 5.5.31-0ubuntu0.13.04.1
-- versão do PHP: 5.4.9-4ubuntu2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `CETSSDB`
--
DROP DATABASE `CETSSDB`;
CREATE DATABASE `CETSSDB` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `CETSSDB`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `Alunos`
--

DROP TABLE IF EXISTS `Alunos`;
CREATE TABLE IF NOT EXISTS `Alunos` (
  `codAluno` bigint(20) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `email` varchar(45) NOT NULL,
  `emailExterno` varchar(45) NOT NULL,
  PRIMARY KEY (`codAluno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `DiretorCursoEdicao`
--

DROP TABLE IF EXISTS `DiretorCursoEdicao`;
CREATE TABLE IF NOT EXISTS `DiretorCursoEdicao` (
  `idDiretorCursoEdicao` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) DEFAULT NULL,
  `telefone` varchar(9) DEFAULT NULL,
  `telefone2` varchar(9) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idDiretorCursoEdicao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `DiretorCursoEdicao`
--

INSERT INTO `DiretorCursoEdicao` (`idDiretorCursoEdicao`, `nome`, `telefone`, `telefone2`, `email`) VALUES
(1, 'Diretor 1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `Docentes`
--

DROP TABLE IF EXISTS `Docentes`;
CREATE TABLE IF NOT EXISTS `Docentes` (
  `idDocente` int(11) NOT NULL AUTO_INCREMENT,
  `nif` varchar(9) DEFAULT NULL,
  `nome` varchar(45) NOT NULL,
  `telefone` varchar(9) DEFAULT NULL,
  `telefone2` varchar(9) DEFAULT NULL,
  `morada` varchar(45) DEFAULT NULL,
  `localidade` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `facebook` varchar(45) DEFAULT NULL,
  `estadoFormacao1` varchar(45) DEFAULT NULL,
  `grauFormacao1` varchar(45) DEFAULT NULL,
  `areaFormacao1` varchar(45) DEFAULT NULL,
  `estadoFormacao2` varchar(45) DEFAULT NULL,
  `grauFormacao2` varchar(45) DEFAULT NULL,
  `areaFormacao2` varchar(45) DEFAULT NULL,
  `estadoFormacao3` varchar(45) DEFAULT NULL,
  `grauFormacao3` varchar(45) DEFAULT NULL,
  `areaFormacao3` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idDocente`),
  UNIQUE KEY `NIF_UNIQUE` (`nif`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `Docentes`
--

INSERT INTO `Docentes` (`idDocente`, `nif`, `nome`, `telefone`, `telefone2`, `morada`, `localidade`, `email`, `facebook`, `estadoFormacao1`, `grauFormacao1`, `areaFormacao1`, `estadoFormacao2`, `grauFormacao2`, `areaFormacao2`, `estadoFormacao3`, `grauFormacao3`, `areaFormacao3`) VALUES
(1, '183254481', 'Cláudio Esperança', '916022516', '', '', '', 'cesperanc@gmail.com', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '218230141', 'Diogó', '', '', '', '', 'diogo.serra.ipleiria@gmail.com', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `EdicaoCursoSemestreLetivo`
--

DROP TABLE IF EXISTS `EdicaoCursoSemestreLetivo`;
CREATE TABLE IF NOT EXISTS `EdicaoCursoSemestreLetivo` (
  `anoLetivo` bigint(20) NOT NULL,
  `semestre` int(11) NOT NULL,
  `idDiretorCursoEdicaoFK` int(11) NOT NULL,
  `codPlanoCursoFK` varchar(16) NOT NULL,
  `dataInicio` date NOT NULL,
  `dataFim` date NOT NULL,
  PRIMARY KEY (`anoLetivo`,`semestre`,`idDiretorCursoEdicaoFK`,`codPlanoCursoFK`),
  KEY `fk_EdicaoSemestreLetivo_DiretorCursoEdicao1_idx` (`idDiretorCursoEdicaoFK`),
  KEY `fk_EdicaoCursoSemestreLetivo_PlanoCurso1_idx` (`codPlanoCursoFK`),
  KEY `index4` (`anoLetivo`),
  KEY `index5` (`semestre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `EdicaoCursoSemestreLetivo`
--

INSERT INTO `EdicaoCursoSemestreLetivo` (`anoLetivo`, `semestre`, `idDiretorCursoEdicaoFK`, `codPlanoCursoFK`, `dataInicio`, `dataFim`) VALUES
(2012, 1, 1, '481241', '2013-09-01', '2014-01-30'),
(2013, 1, 1, '1', '2013-06-29', '2013-06-30'),
(2013, 2, 1, '481241', '2013-06-12', '2013-06-12');

-- --------------------------------------------------------

--
-- Estrutura da tabela `PlanoCurso`
--

DROP TABLE IF EXISTS `PlanoCurso`;
CREATE TABLE IF NOT EXISTS `PlanoCurso` (
  `codPlanoCurso` varchar(16) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `anoPlano` int(11) NOT NULL,
  `regime` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`codPlanoCurso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `PlanoCurso`
--

INSERT INTO `PlanoCurso` (`codPlanoCurso`, `nome`, `anoPlano`, `regime`) VALUES
('1', 'teste', 0, NULL),
('481241', 'Técnico/a Especialista em Tecnologias e Programação de Sistemas de Informação', 2008, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `PlanoUnidCurr`
--

DROP TABLE IF EXISTS `PlanoUnidCurr`;
CREATE TABLE IF NOT EXISTS `PlanoUnidCurr` (
  `codPlanoCursoFK` varchar(16) NOT NULL,
  `codPlanoUnidCurr` varchar(16) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `horasTotal` int(11) NOT NULL,
  `horasSemana` float NOT NULL,
  PRIMARY KEY (`codPlanoCursoFK`,`codPlanoUnidCurr`),
  KEY `fk_PlanoUnidCurr_PlanoCurso_idx` (`codPlanoCursoFK`),
  KEY `index3` (`codPlanoUnidCurr`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `PlanoUnidCurr`
--

INSERT INTO `PlanoUnidCurr` (`codPlanoCursoFK`, `codPlanoUnidCurr`, `nome`, `horasTotal`, `horasSemana`) VALUES
('1', '1', 'Teste', 50, 0),
('1', '5062', 'Língua portuguesa', 50, 0),
('481241', '5062', 'Língua portuguesa', 50, 0),
('481241', '5063', 'Língua inglesa', 50, 0),
('481241', '5064', 'Matemática', 50, 0),
('481241', '5065', 'Empresa - estrutura e funções', 25, 0),
('481241', '5085', 'Criação de estrutura de base de dados em SQL', 25, 0),
('481241', '5086', 'Programação em SQL', 25, 0),
('481241', '5089', 'Programação - Algoritmos', 25, 0),
('481241', '5098', 'Arquitectura de hardware', 25, 0),
('481241', '5114', 'Sistema operativo servidor (plataforma proprietária)', 25, 0),
('481241', '5116', 'Sistemas operativos open source', 25, 0),
('481241', '5407', 'Sistemas de informação - fundamentos', 25, 0),
('481241', '5408', 'Sistemas de informação - concepção', 25, 0),
('481241', '5409', 'Engenharia de software', 25, 0),
('481241', '5410', 'Bases de dados - conceitos', 25, 0),
('481241', '5411', 'Bases de dados - sistemas de gestão', 25, 0),
('481241', '5412', 'Programação de computadores - estruturada', 50, 0),
('481241', '5413', 'Programação de computadores - orientada a objectos', 50, 0),
('481241', '5414', 'Programação para a WEB - cliente (client-side)', 50, 0),
('481241', '5415', 'WEB - hipermédia e acessibilidades', 25, 0),
('481241', '5416', 'WEB - ferramentas multimédia', 25, 0),
('481241', '5417', 'Programação para a WEB - servidor (server-side)', 50, 0),
('481241', '5418', 'Redes de comunicação de dados', 25, 0),
('481241', '5419', 'Segurança em sistemas informáticos', 25, 0),
('481241', '5420', 'Integração de sistemas de informação - conceitos', 25, 0),
('481241', '5421', 'Integração de sistemas de informação - tecnologias e níveis de Integração', 50, 0),
('481241', '5422', 'Integração de sistemas de informação - ferramentas', 25, 0),
('481241', '5423', 'Acesso móvel a sistemas de informação', 50, 0),
('481241', '5424', 'Planeamento e gestão de projectos de sistemas de informação', 25, 0),
('481241', '5425', 'Projecto de tecnologias e programação de sistemas de informação', 50, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `TipoContrato`
--

DROP TABLE IF EXISTS `TipoContrato`;
CREATE TABLE IF NOT EXISTS `TipoContrato` (
  `percentagemServDoc` int(11) NOT NULL COMMENT '\n\n',
  `horasSemestre` int(11) NOT NULL,
  `vencimento` float NOT NULL,
  `vencimentoExtenso` varchar(100) NOT NULL,
  PRIMARY KEY (`percentagemServDoc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `TiposEpocasAvaliacao`
--

DROP TABLE IF EXISTS `TiposEpocasAvaliacao`;
CREATE TABLE IF NOT EXISTS `TiposEpocasAvaliacao` (
  `idTiposEpocasAvaliacao` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`idTiposEpocasAvaliacao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacoes`
--

DROP TABLE IF EXISTS `avaliacoes`;
CREATE TABLE IF NOT EXISTS `avaliacoes` (
  `codPlanoCursoFK2` varchar(16) NOT NULL,
  `codPlanoUnidCurrFK` varchar(16) NOT NULL,
  `anoLetivoFK` bigint(20) NOT NULL,
  `semestreFK` int(11) NOT NULL,
  `codAlunoFK` bigint(20) NOT NULL,
  `idEpocasAvaliacaoFK` int(11) NOT NULL,
  `data` date DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `semana` int(11) DEFAULT NULL,
  PRIMARY KEY (`codPlanoCursoFK2`,`codPlanoUnidCurrFK`,`anoLetivoFK`,`semestreFK`,`codAlunoFK`,`idEpocasAvaliacaoFK`),
  KEY `fk_avaliacoes_2_idx` (`codPlanoUnidCurrFK`),
  KEY `fk_avaliacoes_3_idx` (`anoLetivoFK`),
  KEY `fk_avaliacoes_5_idx` (`codAlunoFK`),
  KEY `fk_avaliacoes_6_idx` (`idEpocasAvaliacaoFK`),
  KEY `fk_avaliacoes_4_idx` (`semestreFK`),
  KEY `fk_avaliacoes_1_idx` (`codPlanoCursoFK2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `edicaoUnidCurrDocentes`
--

DROP TABLE IF EXISTS `edicaoUnidCurrDocentes`;
CREATE TABLE IF NOT EXISTS `edicaoUnidCurrDocentes` (
  `codPlanoCursoFK2` varchar(16) NOT NULL,
  `codPlanoUnidCurrFK` varchar(16) NOT NULL,
  `anoLetivoFK` bigint(20) NOT NULL,
  `semestreFK` int(11) NOT NULL,
  `idDocenteFK` int(11) NOT NULL,
  `percentagemServDocFK` int(11) NOT NULL,
  `horasSemestre` int(11) NOT NULL,
  `vencimento` float NOT NULL,
  `vencimentoExtenso` varchar(100) NOT NULL,
  PRIMARY KEY (`codPlanoCursoFK2`,`codPlanoUnidCurrFK`,`anoLetivoFK`,`semestreFK`,`idDocenteFK`,`percentagemServDocFK`),
  KEY `fk_edicaoUnidCurrDocentes_1_idx` (`codPlanoCursoFK2`),
  KEY `fk_edicaoUnidCurrDocentes_2_idx` (`codPlanoUnidCurrFK`),
  KEY `fk_edicaoUnidCurrDocentes_3_idx` (`anoLetivoFK`),
  KEY `fk_edicaoUnidCurrDocentes_4_idx` (`semestreFK`),
  KEY `fk_edicaoUnidCurrDocentes_5_idx` (`idDocenteFK`),
  KEY `fk_edicaoUnidCurrDocentes_6_idx` (`percentagemServDocFK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `epocasAvaliacao`
--

DROP TABLE IF EXISTS `epocasAvaliacao`;
CREATE TABLE IF NOT EXISTS `epocasAvaliacao` (
  `idEpocasAvaliacao` int(11) NOT NULL AUTO_INCREMENT,
  `idTiposEpocasAvaliacaoFK` int(11) NOT NULL,
  `dataInicio` date NOT NULL,
  `dataFim` date NOT NULL,
  PRIMARY KEY (`idEpocasAvaliacao`),
  KEY `fk_epocasAvaliacao_TiposEpocasAvaliacao1_idx` (`idTiposEpocasAvaliacaoFK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `horarioEdicaoUnidCurr`
--

DROP TABLE IF EXISTS `horarioEdicaoUnidCurr`;
CREATE TABLE IF NOT EXISTS `horarioEdicaoUnidCurr` (
  `idhorarioEdicaoUnidCurr` int(11) NOT NULL AUTO_INCREMENT,
  `codPlanoCursoFK2` varchar(16) NOT NULL,
  `codPlanoUnidCurrFK` varchar(16) NOT NULL,
  `anoLetivoFK` bigint(20) NOT NULL,
  `semestreFK` int(11) NOT NULL,
  PRIMARY KEY (`idhorarioEdicaoUnidCurr`),
  KEY `fk_horarioEdicaoUnidCurr_1_idx` (`codPlanoCursoFK2`),
  KEY `fk_horarioEdicaoUnidCurr_2_idx` (`codPlanoUnidCurrFK`),
  KEY `fk_horarioEdicaoUnidCurr_3_idx` (`anoLetivoFK`),
  KEY `fk_horarioEdicaoUnidCurr_4_idx` (`semestreFK`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `EdicaoCursoSemestreLetivo`
--
ALTER TABLE `EdicaoCursoSemestreLetivo`
  ADD CONSTRAINT `fk_EdicaoSemestreLetivo_DiretorCursoEdicao1` FOREIGN KEY (`idDiretorCursoEdicaoFK`) REFERENCES `DiretorCursoEdicao` (`idDiretorCursoEdicao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_EdicaoCursoSemestreLetivo_PlanoCurso1` FOREIGN KEY (`codPlanoCursoFK`) REFERENCES `PlanoCurso` (`codPlanoCurso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `PlanoUnidCurr`
--
ALTER TABLE `PlanoUnidCurr`
  ADD CONSTRAINT `fk_PlanoUnidCurr_PlanoCurso` FOREIGN KEY (`codPlanoCursoFK`) REFERENCES `PlanoCurso` (`codPlanoCurso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `fk_avaliacoes_1` FOREIGN KEY (`codPlanoCursoFK2`) REFERENCES `PlanoUnidCurr` (`codPlanoCursoFK`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_avaliacoes_2` FOREIGN KEY (`codPlanoUnidCurrFK`) REFERENCES `PlanoUnidCurr` (`codPlanoUnidCurr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_avaliacoes_3` FOREIGN KEY (`anoLetivoFK`) REFERENCES `EdicaoCursoSemestreLetivo` (`anoLetivo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_avaliacoes_5` FOREIGN KEY (`codAlunoFK`) REFERENCES `Alunos` (`codAluno`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_avaliacoes_6` FOREIGN KEY (`idEpocasAvaliacaoFK`) REFERENCES `epocasAvaliacao` (`idEpocasAvaliacao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_avaliacoes_4` FOREIGN KEY (`semestreFK`) REFERENCES `EdicaoCursoSemestreLetivo` (`semestre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `edicaoUnidCurrDocentes`
--
ALTER TABLE `edicaoUnidCurrDocentes`
  ADD CONSTRAINT `fk_edicaoUnidCurrDocentes_1` FOREIGN KEY (`codPlanoCursoFK2`) REFERENCES `PlanoUnidCurr` (`codPlanoCursoFK`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_edicaoUnidCurrDocentes_2` FOREIGN KEY (`codPlanoUnidCurrFK`) REFERENCES `PlanoUnidCurr` (`codPlanoUnidCurr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_edicaoUnidCurrDocentes_3` FOREIGN KEY (`anoLetivoFK`) REFERENCES `EdicaoCursoSemestreLetivo` (`anoLetivo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_edicaoUnidCurrDocentes_4` FOREIGN KEY (`semestreFK`) REFERENCES `EdicaoCursoSemestreLetivo` (`semestre`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_edicaoUnidCurrDocentes_5` FOREIGN KEY (`idDocenteFK`) REFERENCES `Docentes` (`idDocente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_edicaoUnidCurrDocentes_6` FOREIGN KEY (`percentagemServDocFK`) REFERENCES `TipoContrato` (`percentagemServDoc`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `epocasAvaliacao`
--
ALTER TABLE `epocasAvaliacao`
  ADD CONSTRAINT `fk_epocasAvaliacao_TiposEpocasAvaliacao1` FOREIGN KEY (`idTiposEpocasAvaliacaoFK`) REFERENCES `TiposEpocasAvaliacao` (`idTiposEpocasAvaliacao`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `horarioEdicaoUnidCurr`
--
ALTER TABLE `horarioEdicaoUnidCurr`
  ADD CONSTRAINT `fk_horarioEdicaoUnidCurr_1` FOREIGN KEY (`codPlanoCursoFK2`) REFERENCES `PlanoUnidCurr` (`codPlanoCursoFK`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_horarioEdicaoUnidCurr_2` FOREIGN KEY (`codPlanoUnidCurrFK`) REFERENCES `PlanoUnidCurr` (`codPlanoUnidCurr`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_horarioEdicaoUnidCurr_3` FOREIGN KEY (`anoLetivoFK`) REFERENCES `EdicaoCursoSemestreLetivo` (`anoLetivo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_horarioEdicaoUnidCurr_4` FOREIGN KEY (`semestreFK`) REFERENCES `EdicaoCursoSemestreLetivo` (`semestre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
