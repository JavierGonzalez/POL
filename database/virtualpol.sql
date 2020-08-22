-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 23, 2020 at 12:08 AM
-- Server version: 10.3.17-MariaDB
-- PHP Version: 7.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `virtualpol`
--

-- --------------------------------------------------------

--
-- Table structure for table `15m_foros`
--

CREATE TABLE `15m_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(255) DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `15m_foros_hilos`
--

CREATE TABLE `15m_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `15m_foros_msg`
--

CREATE TABLE `15m_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `25s_foros`
--

CREATE TABLE `25s_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `25s_foros_hilos`
--

CREATE TABLE `25s_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `25s_foros_msg`
--

CREATE TABLE `25s_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `api`
--

CREATE TABLE `api` (
  `api_ID` int(11) UNSIGNED NOT NULL,
  `item_ID` varchar(255) DEFAULT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `tipo` enum('facebook','twitter') DEFAULT 'facebook',
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `nombre` varchar(255) DEFAULT NULL,
  `linea_editorial` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `acceso_escribir` text DEFAULT NULL,
  `acceso_borrador` text DEFAULT NULL,
  `clave` text DEFAULT NULL,
  `num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `api_posts`
--

CREATE TABLE `api_posts` (
  `post_ID` int(11) UNSIGNED NOT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `api_ID` mediumint(9) UNSIGNED DEFAULT NULL,
  `estado` enum('publicado','cron','borrado','pendiente') NOT NULL DEFAULT 'pendiente',
  `mensaje_ID` varchar(900) DEFAULT NULL,
  `pendiente_user_ID` mediumint(8) UNSIGNED DEFAULT NULL,
  `publicado_user_ID` mediumint(9) UNSIGNED DEFAULT NULL,
  `borrado_user_ID` mediumint(8) UNSIGNED DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_cron` datetime DEFAULT '0000-00-00 00:00:00',
  `message` text DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `asamblea_foros`
--

CREATE TABLE `asamblea_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `descripcion` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `asamblea_foros_hilos`
--

CREATE TABLE `asamblea_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `asamblea_foros_msg`
--

CREATE TABLE `asamblea_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `atlantis_foros`
--

CREATE TABLE `atlantis_foros` (
  `ID` smallint(5) NOT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(255) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(255) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `atlantis_foros_hilos`
--

CREATE TABLE `atlantis_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` mediumint(9) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `atlantis_foros_msg`
--

CREATE TABLE `atlantis_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` mediumint(9) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cargos`
--

CREATE TABLE `cargos` (
  `ID` smallint(6) NOT NULL,
  `pais` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `cargo_ID` smallint(6) UNSIGNED NOT NULL DEFAULT 0,
  `asigna` smallint(5) NOT NULL DEFAULT 7,
  `nombre` varchar(32) NOT NULL DEFAULT '',
  `nombre_extra` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `nivel` tinyint(3) NOT NULL DEFAULT 1,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `salario` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `autocargo` enum('true','false') CHARACTER SET utf8 NOT NULL DEFAULT 'false',
  `elecciones` datetime DEFAULT NULL,
  `elecciones_electos` tinyint(3) UNSIGNED DEFAULT NULL,
  `elecciones_cada` smallint(5) UNSIGNED DEFAULT NULL,
  `elecciones_durante` tinyint(3) UNSIGNED DEFAULT NULL,
  `elecciones_votan` varchar(999) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cargos_users`
--

CREATE TABLE `cargos_users` (
  `ID` bigint(20) NOT NULL,
  `cargo_ID` smallint(5) NOT NULL DEFAULT 0,
  `pais` varchar(30) DEFAULT NULL,
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cargo` enum('true','false') NOT NULL DEFAULT 'false',
  `aprobado` enum('ok','no') NOT NULL DEFAULT 'ok',
  `nota` decimal(3,1) UNSIGNED NOT NULL DEFAULT 0.0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cat`
--

CREATE TABLE `cat` (
  `ID` smallint(6) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `url` varchar(80) NOT NULL DEFAULT '',
  `nombre` varchar(80) NOT NULL DEFAULT '',
  `num` smallint(6) UNSIGNED NOT NULL DEFAULT 0,
  `nivel` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `tipo` enum('empresas','docs','cargos') NOT NULL DEFAULT 'docs',
  `orden` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `publicar` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indica si los cambios hechos en esta categoría se deben publicar en el chat.',
  `tipo_impositivo` decimal(2,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `chat_ID` smallint(5) UNSIGNED NOT NULL,
  `estado` enum('activo','bloqueado','en_proceso','expirado','borrado') NOT NULL DEFAULT 'en_proceso',
  `pais` varchar(30) DEFAULT NULL,
  `url` varchar(90) NOT NULL,
  `titulo` varchar(90) NOT NULL,
  `user_ID` mediumint(8) UNSIGNED NOT NULL,
  `admin` varchar(900) NOT NULL DEFAULT '',
  `acceso_leer` varchar(30) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(30) DEFAULT 'ciudadanos_global',
  `acceso_escribir_ex` varchar(30) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) DEFAULT '',
  `acceso_cfg_escribir` varchar(900) DEFAULT '',
  `acceso_cfg_escribir_ex` varchar(900) NOT NULL DEFAULT '',
  `fecha_creacion` datetime NOT NULL,
  `fecha_last` datetime NOT NULL,
  `dias_expira` smallint(5) UNSIGNED DEFAULT NULL,
  `url_externa` varchar(500) DEFAULT NULL,
  `stats_visitas` int(12) UNSIGNED NOT NULL DEFAULT 0,
  `stats_msgs` int(12) UNSIGNED NOT NULL DEFAULT 0,
  `GMT` tinyint(2) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chats_msg`
--

CREATE TABLE `chats_msg` (
  `msg_ID` int(8) UNSIGNED NOT NULL,
  `chat_ID` smallint(5) UNSIGNED NOT NULL,
  `nick` varchar(32) NOT NULL,
  `msg` varchar(900) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `user_ID` mediumint(6) UNSIGNED NOT NULL DEFAULT 0,
  `tipo` enum('m','p','e','c') NOT NULL DEFAULT 'm',
  `IP` bigint(12) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `ID` smallint(5) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `dato` varchar(100) NOT NULL DEFAULT '',
  `valor` text NOT NULL,
  `autoload` enum('si','no') NOT NULL DEFAULT 'si'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cuentas`
--

CREATE TABLE `cuentas` (
  `ID` mediumint(8) NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `nombre` varchar(25) CHARACTER SET utf8 NOT NULL,
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `pols` decimal(10,2) NOT NULL DEFAULT 0.00,
  `nivel` tinyint(3) NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `exenta_impuestos` tinyint(1) NOT NULL DEFAULT 0,
  `gobierno` enum('true','false') CHARACTER SET utf8 DEFAULT 'false'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `docs`
--

CREATE TABLE `docs` (
  `ID` smallint(5) NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `text` longtext CHARACTER SET utf8 NOT NULL,
  `text_backup` longtext CHARACTER SET utf8 NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `estado` enum('ok','del','borrador') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `cat_ID` smallint(5) NOT NULL DEFAULT 0,
  `acceso_leer` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(30) CHARACTER SET utf8 NOT NULL DEFAULT 'privado',
  `acceso_cfg_leer` varchar(800) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(800) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `version` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `pad_ID` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `empresas`
--

CREATE TABLE `empresas` (
  `ID` smallint(5) NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `url` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `nombre` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `descripcion` text CHARACTER SET utf8 NOT NULL,
  `web` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `cat_ID` smallint(6) NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pv` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `empresas_acciones`
--

CREATE TABLE `empresas_acciones` (
  `ID` int(11) NOT NULL,
  `ID_empresa` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `pais` varchar(30) DEFAULT NULL,
  `nick` varchar(300) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `num_acciones` int(11) NOT NULL DEFAULT 100
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `etsiit_foros`
--

CREATE TABLE `etsiit_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `descripcion` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos',
  `acceso_cfg_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `etsiit_foros_hilos`
--

CREATE TABLE `etsiit_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `etsiit_foros_msg`
--

CREATE TABLE `etsiit_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `examenes`
--

CREATE TABLE `examenes` (
  `ID` mediumint(9) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `cargo_ID` smallint(5) NOT NULL DEFAULT 0,
  `nota` varchar(5) NOT NULL DEFAULT '5',
  `num_preguntas` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `ID_old` mediumint(8) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `examenes_preg`
--

CREATE TABLE `examenes_preg` (
  `ID` int(11) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `examen_ID` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expulsiones`
--

CREATE TABLE `expulsiones` (
  `ID` smallint(5) NOT NULL,
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `autor` mediumint(8) NOT NULL DEFAULT 0,
  `expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `razon` varchar(150) NOT NULL,
  `estado` enum('activo','inactivo','expulsado','cancelado','indultado') NOT NULL DEFAULT 'activo',
  `tiempo` varchar(20) NOT NULL DEFAULT '0',
  `IP` varchar(12) NOT NULL DEFAULT '0',
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 12,
  `motivo` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fcsm_foros`
--

CREATE TABLE `fcsm_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fcsm_foros_hilos`
--

CREATE TABLE `fcsm_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fcsm_foros_msg`
--

CREATE TABLE `fcsm_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `foros`
--

CREATE TABLE `foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) NOT NULL DEFAULT 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `foros_items`
--

CREATE TABLE `foros_items` (
  `item_ID` int(9) UNSIGNED NOT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `estado` enum('ok','borrado','cerrado') NOT NULL DEFAULT 'ok',
  `foro_ID` mediumint(9) UNSIGNED DEFAULT NULL,
  `hilo_ID` mediumint(8) DEFAULT NULL,
  `parent_ID` mediumint(8) UNSIGNED DEFAULT NULL,
  `nivel` tinyint(3) UNSIGNED DEFAULT 1,
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `nick` varchar(20) DEFAULT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num` smallint(5) NOT NULL DEFAULT 0,
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `url_old` varchar(80) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `grupos`
--

CREATE TABLE `grupos` (
  `grupo_ID` int(11) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `nombre` varchar(255) NOT NULL DEFAULT '',
  `num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hechos`
--

CREATE TABLE `hechos` (
  `ID` mediumint(8) UNSIGNED NOT NULL,
  `pais` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `time` date NOT NULL,
  `nick` varchar(14) CHARACTER SET utf8 NOT NULL DEFAULT 'GONZO',
  `texto` varchar(2000) CHARACTER SET utf8 NOT NULL,
  `estado` enum('ok','del') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hispania_foros`
--

CREATE TABLE `hispania_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` mediumint(9) UNSIGNED DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(255) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(255) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(255) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hispania_foros_hilos`
--

CREATE TABLE `hispania_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` mediumint(9) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hispania_foros_msg`
--

CREATE TABLE `hispania_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` mediumint(9) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kicks`
--

CREATE TABLE `kicks` (
  `ID` mediumint(9) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `user_ID` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `autor` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `razon` varchar(160) NOT NULL DEFAULT '',
  `estado` enum('activo','inactivo','expulsado','cancelado') NOT NULL DEFAULT 'activo',
  `tiempo` varchar(20) NOT NULL DEFAULT '0',
  `IP` varchar(12) NOT NULL DEFAULT '0',
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `motivo` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `nick` varchar(20) NOT NULL DEFAULT '',
  `accion` text NOT NULL,
  `accion_a` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mapa`
--

CREATE TABLE `mapa` (
  `ID` smallint(5) UNSIGNED NOT NULL,
  `pais` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `pos_x` tinyint(2) NOT NULL DEFAULT 1,
  `pos_y` tinyint(2) NOT NULL DEFAULT 1,
  `size_x` tinyint(2) NOT NULL DEFAULT 1,
  `size_y` tinyint(2) NOT NULL DEFAULT 1,
  `user_ID` mediumint(8) NOT NULL DEFAULT 1,
  `nick` varchar(255) DEFAULT NULL,
  `link` varchar(90) NOT NULL DEFAULT '',
  `text` varchar(90) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pols` mediumint(8) NOT NULL DEFAULT 0,
  `color` char(3) NOT NULL DEFAULT '',
  `estado` enum('p','v','e') NOT NULL DEFAULT 'p',
  `superficie` smallint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mensajes`
--

CREATE TABLE `mensajes` (
  `ID` int(10) UNSIGNED NOT NULL,
  `envia_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `recibe_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `leido` enum('0','1') NOT NULL DEFAULT '0',
  `cargo` smallint(5) NOT NULL DEFAULT 0,
  `recibe_masivo` varchar(10) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mic_foros`
--

CREATE TABLE `mic_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(5) UNSIGNED DEFAULT NULL,
  `url` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `descripcion` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos',
  `acceso_cfg_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mic_foros_hilos`
--

CREATE TABLE `mic_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mic_foros_msg`
--

CREATE TABLE `mic_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notificaciones`
--

CREATE TABLE `notificaciones` (
  `noti_ID` int(11) UNSIGNED NOT NULL,
  `time` timestamp NULL DEFAULT current_timestamp(),
  `emisor` varchar(30) NOT NULL DEFAULT 'sistema',
  `visto` enum('true','false') NOT NULL DEFAULT 'false',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `texto` varchar(60) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `occupy_foros`
--

CREATE TABLE `occupy_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `descripcion` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos',
  `acceso_cfg_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `occupy_foros_hilos`
--

CREATE TABLE `occupy_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `occupy_foros_msg`
--

CREATE TABLE `occupy_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `partidos`
--

CREATE TABLE `partidos` (
  `ID` mediumint(8) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `ID_presidente` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `siglas` varchar(20) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('ok') NOT NULL DEFAULT 'ok',
  `ID_old` smallint(6) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partidos_listas`
--

CREATE TABLE `partidos_listas` (
  `ID` mediumint(8) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `ID_partido` mediumint(8) DEFAULT 0,
  `user_ID` mediumint(9) UNSIGNED DEFAULT 0,
  `orden` smallint(5) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pcp_foros`
--

CREATE TABLE `pcp_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `descripcion` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos',
  `acceso_cfg_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pcp_foros_hilos`
--

CREATE TABLE `pcp_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pcp_foros_msg`
--

CREATE TABLE `pcp_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pdi_foros`
--

CREATE TABLE `pdi_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `descripcion` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos',
  `acceso_cfg_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pdi_foros_hilos`
--

CREATE TABLE `pdi_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pdi_foros_msg`
--

CREATE TABLE `pdi_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plataformas`
--

CREATE TABLE `plataformas` (
  `ID` mediumint(8) UNSIGNED NOT NULL,
  `estado` enum('pendiente','ok','no') DEFAULT 'pendiente',
  `pais` varchar(255) DEFAULT NULL,
  `asamblea` enum('true','false') DEFAULT 'false',
  `economia` enum('true','false') DEFAULT 'true',
  `user_ID` mediumint(8) UNSIGNED DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `descripcion` text DEFAULT NULL,
  `participacion` mediumint(8) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `plebiscito_foros`
--

CREATE TABLE `plebiscito_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plebiscito_foros_hilos`
--

CREATE TABLE `plebiscito_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `plebiscito_foros_msg`
--

CREATE TABLE `plebiscito_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `podemos_foros`
--

CREATE TABLE `podemos_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `podemos_foros_hilos`
--

CREATE TABLE `podemos_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `podemos_foros_msg`
--

CREATE TABLE `podemos_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pol1_foros`
--

CREATE TABLE `pol1_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` varchar(255) DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(255) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(255) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(255) DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(255) DEFAULT '',
  `limite` smallint(6) DEFAULT 10
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pol1_foros_hilos`
--

CREATE TABLE `pol1_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` mediumint(9) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pol1_foros_msg`
--

CREATE TABLE `pol1_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` mediumint(9) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pol_foros`
--

CREATE TABLE `pol_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` varchar(255) DEFAULT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(255) NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(255) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(255) DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(255) DEFAULT '',
  `limite` smallint(6) DEFAULT 10
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pol_foros_hilos`
--

CREATE TABLE `pol_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` mediumint(9) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pol_foros_msg`
--

CREATE TABLE `pol_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` mediumint(9) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pujas`
--

CREATE TABLE `pujas` (
  `ID` mediumint(9) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `mercado_ID` smallint(5) DEFAULT NULL,
  `user_ID` mediumint(9) UNSIGNED DEFAULT NULL,
  `pols` decimal(10,2) UNSIGNED DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `referencias`
--

CREATE TABLE `referencias` (
  `ID` mediumint(8) NOT NULL,
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `IP` varchar(10) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `pagado` enum('0','1') NOT NULL DEFAULT '0',
  `new_user_ID` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `ID` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `type` enum('currency','item','material','') NOT NULL,
  `icon` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `simulador_foros`
--

CREATE TABLE `simulador_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(6) UNSIGNED DEFAULT NULL,
  `url` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `descripcion` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `simulador_foros_hilos`
--

CREATE TABLE `simulador_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(9) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `simulador_foros_msg`
--

CREATE TABLE `simulador_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0,
  `votos_num` mediumint(8) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `socios`
--

CREATE TABLE `socios` (
  `ID` int(11) UNSIGNED NOT NULL,
  `time` datetime DEFAULT NULL,
  `time_last` datetime DEFAULT NULL,
  `estado` varchar(255) DEFAULT 'inscrito',
  `pais` varchar(255) DEFAULT NULL,
  `socio_ID` int(11) UNSIGNED DEFAULT NULL,
  `user_ID` int(11) UNSIGNED DEFAULT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `NIF` varchar(255) DEFAULT NULL,
  `pais_politico` varchar(255) DEFAULT NULL,
  `localidad` varchar(255) DEFAULT NULL,
  `cp` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `contacto_email` varchar(255) DEFAULT NULL,
  `contacto_telefono` varchar(255) DEFAULT NULL,
  `validador_ID` int(11) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `stats_ID` smallint(5) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ciudadanos` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `nuevos` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `pols` int(10) NOT NULL DEFAULT 0,
  `pols_cuentas` int(10) NOT NULL DEFAULT 0,
  `transacciones` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `hilos_msg` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `pols_gobierno` int(10) NOT NULL DEFAULT 0,
  `partidos` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `frase` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `empresas` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `eliminados` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `mapa` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `mapa_vende` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `24h` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `confianza` smallint(5) DEFAULT 0,
  `autentificados` mediumint(9) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `msg_id` int(10) UNSIGNED NOT NULL,
  `canal` decimal(10,0) UNSIGNED DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `texto` varchar(900) DEFAULT NULL,
  `participante` varchar(900) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `participantes_num` decimal(10,0) UNSIGNED DEFAULT NULL,
  `puntos` mediumint(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transacciones`
--

CREATE TABLE `transacciones` (
  `ID` mediumint(8) NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `pols` decimal(10,2) NOT NULL DEFAULT 0.00,
  `emisor_ID` mediumint(8) NOT NULL DEFAULT 0,
  `receptor_ID` mediumint(8) NOT NULL DEFAULT 0,
  `concepto` varchar(90) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` mediumint(8) UNSIGNED NOT NULL,
  `nick` varchar(18) NOT NULL DEFAULT '',
  `lang` varchar(5) DEFAULT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `estado` enum('turista','ciudadano','expulsado','validar') NOT NULL DEFAULT 'validar',
  `nivel` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `cargos` varchar(400) NOT NULL DEFAULT '',
  `grupos` varchar(400) NOT NULL DEFAULT '',
  `examenes` varchar(400) NOT NULL DEFAULT '',
  `voto_confianza` smallint(5) NOT NULL DEFAULT 0,
  `confianza_historico` text NOT NULL,
  `partido_afiliado` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `online` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `visitas` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `paginas` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `pols` decimal(10,2) NOT NULL DEFAULT 0.00,
  `email` varchar(255) NOT NULL DEFAULT '',
  `fecha_registro` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_init` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rechazo_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `fecha_legal` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reset_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `nickchange_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pass` varchar(255) NOT NULL DEFAULT '',
  `pass2` varchar(255) NOT NULL DEFAULT '',
  `api_pass` varchar(16) NOT NULL DEFAULT '0',
  `api_num` smallint(5) NOT NULL DEFAULT 0,
  `num_elec` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `SC` enum('true','false') NOT NULL DEFAULT 'false',
  `ser_SC` enum('true','false','block') NOT NULL DEFAULT 'false',
  `nota` decimal(3,1) NOT NULL DEFAULT 0.0,
  `donacion` mediumint(9) UNSIGNED DEFAULT NULL,
  `avatar` enum('true','false') NOT NULL DEFAULT 'false',
  `IP` varchar(12) NOT NULL DEFAULT '0',
  `host` varchar(150) NOT NULL,
  `hosts` text DEFAULT NULL,
  `IP_proxy` varchar(150) NOT NULL,
  `text` varchar(2300) NOT NULL DEFAULT '',
  `nav` varchar(500) NOT NULL,
  `avatar_localdir` varchar(100) NOT NULL,
  `x` decimal(10,2) DEFAULT NULL,
  `y` decimal(10,2) DEFAULT NULL,
  `socio` enum('true','false') NOT NULL DEFAULT 'false',
  `dnie` enum('true','false') DEFAULT 'false',
  `dnie_check` varchar(400) DEFAULT NULL,
  `ref` varchar(25) NOT NULL DEFAULT '',
  `ref_num` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `bando` varchar(255) DEFAULT NULL,
  `nota_SC` varchar(500) NOT NULL DEFAULT '',
  `traza` varchar(600) NOT NULL DEFAULT '',
  `datos` varchar(9999) NOT NULL DEFAULT '',
  `nombre` varchar(255) DEFAULT NULL,
  `temp` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_con`
--

CREATE TABLE `users_con` (
  `ID` int(11) UNSIGNED NOT NULL,
  `time` datetime DEFAULT NULL,
  `tipo` enum('session','login') DEFAULT 'login',
  `user_ID` mediumint(8) UNSIGNED DEFAULT NULL,
  `IP` int(11) UNSIGNED DEFAULT NULL,
  `IP_rango` varchar(255) DEFAULT NULL,
  `IP_rango3` varchar(20) DEFAULT NULL,
  `IP_pais` varchar(2) DEFAULT NULL,
  `host` varchar(255) DEFAULT NULL,
  `ISP` varchar(255) DEFAULT NULL,
  `proxy` varchar(255) DEFAULT NULL,
  `login_seg` smallint(5) UNSIGNED DEFAULT NULL,
  `login_ms` smallint(5) UNSIGNED DEFAULT NULL,
  `dispositivo` bigint(20) UNSIGNED DEFAULT NULL,
  `nav_resolucion` varchar(255) DEFAULT NULL,
  `nav` varchar(500) DEFAULT NULL,
  `nav_so` varchar(255) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_resources`
--

CREATE TABLE `user_resources` (
  `ID` int(11) NOT NULL,
  `user_id` mediumint(8) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `votacion`
--

CREATE TABLE `votacion` (
  `ID` smallint(5) NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `estado` enum('ok','end','borrador') NOT NULL DEFAULT 'borrador',
  `pregunta` varchar(255) NOT NULL DEFAULT '',
  `descripcion` text NOT NULL,
  `respuestas` text NOT NULL,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `num_censo` int(11) UNSIGNED DEFAULT NULL,
  `tipo` enum('sondeo','referendum','parlamento','destituir','otorgar','cargo','elecciones') NOT NULL DEFAULT 'sondeo',
  `tipo_voto` enum('estandar','3puntos','5puntos','8puntos','multiple','aleatorio') NOT NULL DEFAULT 'estandar',
  `privacidad` enum('true','false') NOT NULL DEFAULT 'true',
  `aleatorio` enum('true','false') NOT NULL DEFAULT 'false',
  `ejecutar` text NOT NULL,
  `duracion` mediumint(9) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `acceso_votar` varchar(30) NOT NULL DEFAULT 'ciudadanos_global',
  `acceso_cfg_votar` varchar(900) NOT NULL DEFAULT '',
  `acceso_ver` varchar(255) NOT NULL DEFAULT 'anonimos',
  `acceso_cfg_ver` varchar(900) NOT NULL DEFAULT '',
  `debate_url` varchar(255) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `votos_expire` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `respuestas_desc` text NOT NULL,
  `cargo_ID` smallint(6) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `votacion_argumentos`
--

CREATE TABLE `votacion_argumentos` (
  `ID` int(11) UNSIGNED NOT NULL,
  `ref_ID` mediumint(8) UNSIGNED DEFAULT NULL,
  `user_ID` mediumint(8) UNSIGNED DEFAULT NULL,
  `time` datetime DEFAULT '0000-00-00 00:00:00',
  `sentido` varchar(255) NOT NULL DEFAULT '',
  `texto` varchar(900) NOT NULL DEFAULT '',
  `votos` mediumint(8) DEFAULT 0,
  `votos_num` mediumint(9) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `votacion_votos`
--

CREATE TABLE `votacion_votos` (
  `ID` int(11) UNSIGNED NOT NULL,
  `ref_ID` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime DEFAULT NULL,
  `voto` varchar(300) NOT NULL DEFAULT '0',
  `validez` enum('true','false') NOT NULL DEFAULT 'true',
  `autentificado` enum('true','false') DEFAULT 'false',
  `mensaje` varchar(500) NOT NULL DEFAULT '',
  `comprobante` varchar(600) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `votos`
--

CREATE TABLE `votos` (
  `voto_ID` int(11) UNSIGNED NOT NULL,
  `pais` varchar(30) DEFAULT NULL,
  `item_ID` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `emisor_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `receptor_ID` mediumint(9) UNSIGNED DEFAULT NULL,
  `voto` tinyint(3) NOT NULL,
  `tipo` enum('confianza','hilos','msg','argumentos') NOT NULL DEFAULT 'confianza',
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vp_foros`
--

CREATE TABLE `vp_foros` (
  `ID` smallint(5) NOT NULL,
  `subforo_ID` smallint(5) UNSIGNED DEFAULT NULL,
  `url` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `title` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `descripcion` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','eliminado') CHARACTER SET utf8 NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL DEFAULT 'ciudadanos',
  `acceso_cfg_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL DEFAULT '',
  `limite` tinyint(3) UNSIGNED NOT NULL DEFAULT 8
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vp_foros_hilos`
--

CREATE TABLE `vp_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` smallint(6) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vp_foros_msg`
--

CREATE TABLE `vp_foros_msg` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vulcan_foros`
--

CREATE TABLE `vulcan_foros` (
  `ID` smallint(5) NOT NULL,
  `url` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `descripcion` varchar(255) NOT NULL DEFAULT '',
  `acceso` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `time` smallint(3) NOT NULL DEFAULT 20,
  `estado` enum('ok') NOT NULL DEFAULT 'ok',
  `acceso_msg` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `acceso_leer` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'anonimos',
  `acceso_escribir` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'ciudadanos',
  `acceso_cfg_leer` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `acceso_cfg_escribir` varchar(900) CHARACTER SET utf8 NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vulcan_foros_hilos`
--

CREATE TABLE `vulcan_foros_hilos` (
  `ID` mediumint(8) NOT NULL,
  `sub_ID` smallint(5) NOT NULL DEFAULT 0,
  `url` varchar(80) NOT NULL DEFAULT '',
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `title` varchar(80) NOT NULL DEFAULT '',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 0,
  `num` smallint(5) NOT NULL DEFAULT 0,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `votos` mediumint(9) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vulcan_foros_msg`
--

CREATE TABLE `vulcan_foros_msg` (
  `ID` int(10) NOT NULL,
  `hilo_ID` mediumint(8) NOT NULL DEFAULT 0,
  `user_ID` mediumint(8) NOT NULL DEFAULT 0,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL DEFAULT 1,
  `estado` enum('ok','borrado') NOT NULL DEFAULT 'ok',
  `time2` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `votos` mediumint(9) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `15m_foros`
--
ALTER TABLE `15m_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `15m_foros_hilos`
--
ALTER TABLE `15m_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `15m_foros_msg`
--
ALTER TABLE `15m_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `25s_foros`
--
ALTER TABLE `25s_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `25s_foros_hilos`
--
ALTER TABLE `25s_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `25s_foros_msg`
--
ALTER TABLE `25s_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `api`
--
ALTER TABLE `api`
  ADD PRIMARY KEY (`api_ID`),
  ADD KEY `pais` (`pais`),
  ADD KEY `estado` (`estado`),
  ADD KEY `tipo` (`tipo`);

--
-- Indexes for table `api_posts`
--
ALTER TABLE `api_posts`
  ADD PRIMARY KEY (`post_ID`),
  ADD KEY `pais` (`pais`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `asamblea_foros`
--
ALTER TABLE `asamblea_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `asamblea_foros_hilos`
--
ALTER TABLE `asamblea_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `asamblea_foros_msg`
--
ALTER TABLE `asamblea_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `atlantis_foros`
--
ALTER TABLE `atlantis_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `atlantis_foros_hilos`
--
ALTER TABLE `atlantis_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `atlantis_foros_msg`
--
ALTER TABLE `atlantis_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `nivel` (`nivel`),
  ADD KEY `nombre` (`nombre`),
  ADD KEY `asigna` (`asigna`),
  ADD KEY `cargo_ID` (`cargo_ID`),
  ADD KEY `pais` (`pais`),
  ADD KEY `elecciones` (`elecciones`);

--
-- Indexes for table `cargos_users`
--
ALTER TABLE `cargos_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `cargo` (`cargo`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `cargo_ID` (`cargo_ID`),
  ADD KEY `aprobado` (`aprobado`),
  ADD KEY `pais` (`pais`),
  ADD KEY `nota` (`nota`);

--
-- Indexes for table `cat`
--
ALTER TABLE `cat`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `url` (`url`,`nivel`,`tipo`,`orden`,`nombre`,`num`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `orden` (`orden`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`chat_ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`),
  ADD KEY `pais` (`pais`(1)),
  ADD KEY `acceso_leer` (`acceso_leer`),
  ADD KEY `acceso_escribir` (`acceso_escribir`),
  ADD KEY `acceso_cfg_leer` (`acceso_cfg_leer`(333)),
  ADD KEY `acceso_cfg_escribir` (`acceso_cfg_escribir`(333)),
  ADD KEY `stats_msgs` (`stats_msgs`),
  ADD KEY `fecha_last` (`fecha_last`),
  ADD KEY `acceso_escribir_ex` (`acceso_escribir_ex`),
  ADD KEY `acceso_cfg_escribir_ex` (`acceso_cfg_escribir_ex`);

--
-- Indexes for table `chats_msg`
--
ALTER TABLE `chats_msg`
  ADD PRIMARY KEY (`msg_ID`),
  ADD KEY `chat_ID` (`chat_ID`),
  ADD KEY `nick` (`nick`),
  ADD KEY `time` (`time`),
  ADD KEY `cargo` (`cargo`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `msg` (`msg`(333)),
  ADD KEY `IP` (`IP`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `dato` (`dato`),
  ADD KEY `autoload` (`autoload`),
  ADD KEY `pais` (`pais`),
  ADD KEY `valor` (`valor`(255));

--
-- Indexes for table `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `nivel` (`nivel`),
  ADD KEY `pais` (`pais`);

--
-- Indexes for table `docs`
--
ALTER TABLE `docs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `estado` (`estado`),
  ADD KEY `cat_ID` (`cat_ID`),
  ADD KEY `url` (`url`),
  ADD KEY `pais` (`pais`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`,`cat_ID`),
  ADD KEY `cat_ID` (`cat_ID`);

--
-- Indexes for table `empresas_acciones`
--
ALTER TABLE `empresas_acciones`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `etsiit_foros`
--
ALTER TABLE `etsiit_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `etsiit_foros_hilos`
--
ALTER TABLE `etsiit_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `etsiit_foros_msg`
--
ALTER TABLE `etsiit_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `titulo` (`titulo`),
  ADD KEY `nota` (`nota`),
  ADD KEY `pais` (`pais`),
  ADD KEY `cargo_ID` (`cargo_ID`);

--
-- Indexes for table `examenes_preg`
--
ALTER TABLE `examenes_preg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pais` (`pais`),
  ADD KEY `examen_ID` (`examen_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `expulsiones`
--
ALTER TABLE `expulsiones`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `estado` (`estado`),
  ADD KEY `IP` (`IP`),
  ADD KEY `expire` (`expire`);

--
-- Indexes for table `fcsm_foros`
--
ALTER TABLE `fcsm_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `fcsm_foros_hilos`
--
ALTER TABLE `fcsm_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `fcsm_foros_msg`
--
ALTER TABLE `fcsm_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `foros`
--
ALTER TABLE `foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `foros_items`
--
ALTER TABLE `foros_items`
  ADD PRIMARY KEY (`item_ID`);

--
-- Indexes for table `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`grupo_ID`),
  ADD KEY `num` (`num`),
  ADD KEY `pais` (`pais`);

--
-- Indexes for table `hechos`
--
ALTER TABLE `hechos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `time` (`time`,`estado`);

--
-- Indexes for table `hispania_foros`
--
ALTER TABLE `hispania_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `hispania_foros_hilos`
--
ALTER TABLE `hispania_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `hispania_foros_msg`
--
ALTER TABLE `hispania_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `kicks`
--
ALTER TABLE `kicks`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pais` (`pais`,`user_ID`,`estado`,`expire`),
  ADD KEY `estado` (`estado`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `IP` (`IP`),
  ADD KEY `expire` (`expire`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pais` (`pais`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `mapa`
--
ALTER TABLE `mapa`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `envia_ID` (`envia_ID`),
  ADD KEY `recibe_ID` (`recibe_ID`),
  ADD KEY `leido` (`leido`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `mic_foros`
--
ALTER TABLE `mic_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`),
  ADD KEY `time` (`time`),
  ADD KEY `acceso_leer` (`acceso_leer`(333)),
  ADD KEY `acceso_escribir` (`acceso_escribir`(333)),
  ADD KEY `acceso_cfg_leer` (`acceso_cfg_leer`(333)),
  ADD KEY `acceso_cfg_escribir` (`acceso_cfg_escribir`(333));

--
-- Indexes for table `mic_foros_hilos`
--
ALTER TABLE `mic_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `mic_foros_msg`
--
ALTER TABLE `mic_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`),
  ADD KEY `hilo_ID` (`hilo_ID`);

--
-- Indexes for table `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`noti_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `visto` (`visto`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `url` (`url`),
  ADD KEY `texto` (`texto`),
  ADD KEY `emisor` (`emisor`);

--
-- Indexes for table `occupy_foros`
--
ALTER TABLE `occupy_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `occupy_foros_hilos`
--
ALTER TABLE `occupy_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `occupy_foros_msg`
--
ALTER TABLE `occupy_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `partidos`
--
ALTER TABLE `partidos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pais` (`pais`),
  ADD KEY `ID_presidente` (`ID_presidente`),
  ADD KEY `siglas` (`siglas`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `partidos_listas`
--
ALTER TABLE `partidos_listas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_partido` (`ID_partido`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `orden` (`orden`),
  ADD KEY `pais` (`pais`);

--
-- Indexes for table `pcp_foros`
--
ALTER TABLE `pcp_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pcp_foros_hilos`
--
ALTER TABLE `pcp_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pcp_foros_msg`
--
ALTER TABLE `pcp_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pdi_foros`
--
ALTER TABLE `pdi_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pdi_foros_hilos`
--
ALTER TABLE `pdi_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pdi_foros_msg`
--
ALTER TABLE `pdi_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `plataformas`
--
ALTER TABLE `plataformas`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `plebiscito_foros`
--
ALTER TABLE `plebiscito_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `plebiscito_foros_hilos`
--
ALTER TABLE `plebiscito_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `plebiscito_foros_msg`
--
ALTER TABLE `plebiscito_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `podemos_foros`
--
ALTER TABLE `podemos_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `podemos_foros_hilos`
--
ALTER TABLE `podemos_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `podemos_foros_msg`
--
ALTER TABLE `podemos_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pol1_foros`
--
ALTER TABLE `pol1_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pol1_foros_hilos`
--
ALTER TABLE `pol1_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pol1_foros_msg`
--
ALTER TABLE `pol1_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pol_foros`
--
ALTER TABLE `pol_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pol_foros_hilos`
--
ALTER TABLE `pol_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pol_foros_msg`
--
ALTER TABLE `pol_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `pujas`
--
ALTER TABLE `pujas`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pais` (`pais`),
  ADD KEY `mercado_ID` (`mercado_ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `referencias`
--
ALTER TABLE `referencias`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `IP` (`IP`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `pagado` (`pagado`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `simulador_foros`
--
ALTER TABLE `simulador_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `simulador_foros_hilos`
--
ALTER TABLE `simulador_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `simulador_foros_msg`
--
ALTER TABLE `simulador_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `socios`
--
ALTER TABLE `socios`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `time` (`time`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `pais` (`pais`),
  ADD KEY `estado` (`estado`),
  ADD KEY `socio_ID` (`socio_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`stats_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `pais` (`pais`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`msg_id`),
  ADD KEY `canal` (`canal`),
  ADD KEY `fecha_creacion` (`fecha_creacion`),
  ADD KEY `fecha_publicacion` (`fecha_publicacion`),
  ADD KEY `estado` (`estado`),
  ADD KEY `texto` (`texto`(255)),
  ADD KEY `participante` (`participante`(255)),
  ADD KEY `puntos` (`puntos`),
  ADD KEY `participantes_num` (`participantes_num`),
  ADD KEY `ip` (`ip`);

--
-- Indexes for table `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `emisor_ID` (`emisor_ID`),
  ADD KEY `receptor_ID` (`receptor_ID`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `nick` (`nick`),
  ADD KEY `pais` (`pais`),
  ADD KEY `fecha_last` (`fecha_last`),
  ADD KEY `estado` (`estado`),
  ADD KEY `voto_confianza` (`voto_confianza`),
  ADD KEY `IP` (`IP`),
  ADD KEY `pass` (`pass`),
  ADD KEY `cargo` (`cargo`),
  ADD KEY `grupos` (`grupos`(333)),
  ADD KEY `cargos` (`cargos`(333)),
  ADD KEY `examenes` (`examenes`(333)),
  ADD KEY `x` (`x`),
  ADD KEY `y` (`y`),
  ADD KEY `lang` (`lang`),
  ADD KEY `nivel` (`nivel`),
  ADD KEY `fecha_registro` (`fecha_registro`),
  ADD KEY `paginas` (`paginas`),
  ADD KEY `dnie` (`dnie`),
  ADD KEY `temp` (`temp`),
  ADD KEY `socio` (`socio`),
  ADD KEY `SC` (`SC`),
  ADD KEY `nota_SC` (`nota_SC`(333));

--
-- Indexes for table `users_con`
--
ALTER TABLE `users_con`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `IP` (`IP`),
  ADD KEY `dispositivo` (`dispositivo`),
  ADD KEY `ISP` (`ISP`),
  ADD KEY `host` (`host`),
  ADD KEY `nav_resolucion` (`nav_resolucion`),
  ADD KEY `nav` (`nav`(333)),
  ADD KEY `nav_so` (`nav_so`),
  ADD KEY `IP_pais` (`IP_pais`),
  ADD KEY `IP_rango` (`IP_rango`),
  ADD KEY `proxy` (`proxy`),
  ADD KEY `IP_rango3` (`IP_rango3`);

--
-- Indexes for table `user_resources`
--
ALTER TABLE `user_resources`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `votacion`
--
ALTER TABLE `votacion`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `pais` (`pais`),
  ADD KEY `time_expire` (`time_expire`),
  ADD KEY `estado` (`estado`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `num` (`num`),
  ADD KEY `votos_expire` (`votos_expire`),
  ADD KEY `time` (`time`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `pregunta` (`pregunta`),
  ADD KEY `acceso_votar` (`acceso_votar`),
  ADD KEY `acceso_cfg_votar` (`acceso_cfg_votar`(333)),
  ADD KEY `acceso_ver` (`acceso_ver`),
  ADD KEY `acceso_cfg_ver` (`acceso_cfg_ver`(333)),
  ADD KEY `tipo_voto` (`tipo_voto`),
  ADD KEY `privacidad` (`privacidad`),
  ADD KEY `aleatorio` (`aleatorio`),
  ADD KEY `num_censo` (`num_censo`),
  ADD KEY `cargo_ID` (`cargo_ID`);

--
-- Indexes for table `votacion_argumentos`
--
ALTER TABLE `votacion_argumentos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ref_ID` (`ref_ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `votos` (`votos`),
  ADD KEY `votos_num` (`votos_num`),
  ADD KEY `time` (`time`),
  ADD KEY `texto` (`texto`(333)),
  ADD KEY `sentido` (`sentido`);

--
-- Indexes for table `votacion_votos`
--
ALTER TABLE `votacion_votos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ref_ID` (`ref_ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `voto` (`voto`),
  ADD KEY `validez` (`validez`),
  ADD KEY `time` (`time`),
  ADD KEY `autentificado` (`autentificado`),
  ADD KEY `mensaje` (`mensaje`);

--
-- Indexes for table `votos`
--
ALTER TABLE `votos`
  ADD PRIMARY KEY (`voto_ID`),
  ADD KEY `tipo` (`tipo`),
  ADD KEY `emisor_ID` (`emisor_ID`),
  ADD KEY `item_ID` (`item_ID`),
  ADD KEY `pais` (`pais`),
  ADD KEY `voto` (`voto`);

--
-- Indexes for table `vp_foros`
--
ALTER TABLE `vp_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`),
  ADD KEY `time` (`time`),
  ADD KEY `acceso_leer` (`acceso_leer`(333)),
  ADD KEY `acceso_escribir` (`acceso_escribir`(333)),
  ADD KEY `acceso_cfg_leer` (`acceso_cfg_leer`(333)),
  ADD KEY `acceso_cfg_escribir` (`acceso_cfg_escribir`(333));

--
-- Indexes for table `vp_foros_hilos`
--
ALTER TABLE `vp_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `vp_foros_msg`
--
ALTER TABLE `vp_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`),
  ADD KEY `hilo_ID` (`hilo_ID`);

--
-- Indexes for table `vulcan_foros`
--
ALTER TABLE `vulcan_foros`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `vulcan_foros_hilos`
--
ALTER TABLE `vulcan_foros_hilos`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `url` (`url`),
  ADD KEY `sub_ID` (`sub_ID`),
  ADD KEY `time_last` (`time_last`),
  ADD KEY `estado` (`estado`);

--
-- Indexes for table `vulcan_foros_msg`
--
ALTER TABLE `vulcan_foros_msg`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `foro_ID` (`hilo_ID`),
  ADD KEY `time` (`time`),
  ADD KEY `estado` (`estado`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `15m_foros`
--
ALTER TABLE `15m_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `15m_foros_hilos`
--
ALTER TABLE `15m_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `15m_foros_msg`
--
ALTER TABLE `15m_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `25s_foros`
--
ALTER TABLE `25s_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `25s_foros_hilos`
--
ALTER TABLE `25s_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `25s_foros_msg`
--
ALTER TABLE `25s_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api`
--
ALTER TABLE `api`
  MODIFY `api_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_posts`
--
ALTER TABLE `api_posts`
  MODIFY `post_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asamblea_foros`
--
ALTER TABLE `asamblea_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asamblea_foros_hilos`
--
ALTER TABLE `asamblea_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asamblea_foros_msg`
--
ALTER TABLE `asamblea_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atlantis_foros`
--
ALTER TABLE `atlantis_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atlantis_foros_hilos`
--
ALTER TABLE `atlantis_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atlantis_foros_msg`
--
ALTER TABLE `atlantis_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cargos`
--
ALTER TABLE `cargos`
  MODIFY `ID` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cargos_users`
--
ALTER TABLE `cargos_users`
  MODIFY `ID` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cat`
--
ALTER TABLE `cat`
  MODIFY `ID` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `chat_ID` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats_msg`
--
ALTER TABLE `chats_msg`
  MODIFY `msg_ID` int(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `ID` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `docs`
--
ALTER TABLE `docs`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empresas`
--
ALTER TABLE `empresas`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empresas_acciones`
--
ALTER TABLE `empresas_acciones`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `etsiit_foros`
--
ALTER TABLE `etsiit_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `etsiit_foros_hilos`
--
ALTER TABLE `etsiit_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `etsiit_foros_msg`
--
ALTER TABLE `etsiit_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `examenes`
--
ALTER TABLE `examenes`
  MODIFY `ID` mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `examenes_preg`
--
ALTER TABLE `examenes_preg`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expulsiones`
--
ALTER TABLE `expulsiones`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fcsm_foros`
--
ALTER TABLE `fcsm_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fcsm_foros_hilos`
--
ALTER TABLE `fcsm_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fcsm_foros_msg`
--
ALTER TABLE `fcsm_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foros`
--
ALTER TABLE `foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foros_items`
--
ALTER TABLE `foros_items`
  MODIFY `item_ID` int(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grupos`
--
ALTER TABLE `grupos`
  MODIFY `grupo_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hechos`
--
ALTER TABLE `hechos`
  MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hispania_foros`
--
ALTER TABLE `hispania_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hispania_foros_hilos`
--
ALTER TABLE `hispania_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hispania_foros_msg`
--
ALTER TABLE `hispania_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kicks`
--
ALTER TABLE `kicks`
  MODIFY `ID` mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mapa`
--
ALTER TABLE `mapa`
  MODIFY `ID` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mic_foros`
--
ALTER TABLE `mic_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mic_foros_hilos`
--
ALTER TABLE `mic_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mic_foros_msg`
--
ALTER TABLE `mic_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `noti_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `occupy_foros`
--
ALTER TABLE `occupy_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `occupy_foros_hilos`
--
ALTER TABLE `occupy_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `occupy_foros_msg`
--
ALTER TABLE `occupy_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partidos`
--
ALTER TABLE `partidos`
  MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partidos_listas`
--
ALTER TABLE `partidos_listas`
  MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcp_foros`
--
ALTER TABLE `pcp_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcp_foros_hilos`
--
ALTER TABLE `pcp_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pcp_foros_msg`
--
ALTER TABLE `pcp_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pdi_foros`
--
ALTER TABLE `pdi_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pdi_foros_hilos`
--
ALTER TABLE `pdi_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pdi_foros_msg`
--
ALTER TABLE `pdi_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plataformas`
--
ALTER TABLE `plataformas`
  MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plebiscito_foros`
--
ALTER TABLE `plebiscito_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plebiscito_foros_hilos`
--
ALTER TABLE `plebiscito_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plebiscito_foros_msg`
--
ALTER TABLE `plebiscito_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `podemos_foros`
--
ALTER TABLE `podemos_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `podemos_foros_hilos`
--
ALTER TABLE `podemos_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `podemos_foros_msg`
--
ALTER TABLE `podemos_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pol1_foros`
--
ALTER TABLE `pol1_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pol1_foros_hilos`
--
ALTER TABLE `pol1_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pol1_foros_msg`
--
ALTER TABLE `pol1_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pol_foros`
--
ALTER TABLE `pol_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pol_foros_hilos`
--
ALTER TABLE `pol_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pol_foros_msg`
--
ALTER TABLE `pol_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pujas`
--
ALTER TABLE `pujas`
  MODIFY `ID` mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referencias`
--
ALTER TABLE `referencias`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `simulador_foros`
--
ALTER TABLE `simulador_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `simulador_foros_hilos`
--
ALTER TABLE `simulador_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `simulador_foros_msg`
--
ALTER TABLE `simulador_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `socios`
--
ALTER TABLE `socios`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stats`
--
ALTER TABLE `stats`
  MODIFY `stats_ID` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `msg_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_con`
--
ALTER TABLE `users_con`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_resources`
--
ALTER TABLE `user_resources`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votacion`
--
ALTER TABLE `votacion`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votacion_argumentos`
--
ALTER TABLE `votacion_argumentos`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votacion_votos`
--
ALTER TABLE `votacion_votos`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votos`
--
ALTER TABLE `votos`
  MODIFY `voto_ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vp_foros`
--
ALTER TABLE `vp_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vp_foros_hilos`
--
ALTER TABLE `vp_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vp_foros_msg`
--
ALTER TABLE `vp_foros_msg`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vulcan_foros`
--
ALTER TABLE `vulcan_foros`
  MODIFY `ID` smallint(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vulcan_foros_hilos`
--
ALTER TABLE `vulcan_foros_hilos`
  MODIFY `ID` mediumint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vulcan_foros_msg`
--
ALTER TABLE `vulcan_foros_msg`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;
