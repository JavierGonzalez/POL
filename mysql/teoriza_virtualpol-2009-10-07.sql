-- phpMyAdmin SQL Dump
-- version 2.11.9.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-10-2009 a las 17:54:43
-- Versión del servidor: 5.0.85
-- Versión de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `teoriza_virtualpol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expulsiones`
--

CREATE TABLE IF NOT EXISTS `expulsiones` (
  `ID` smallint(5) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `autor` mediumint(8) NOT NULL default '0',
  `expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `razon` varchar(150) NOT NULL,
  `estado` enum('activo','inactivo','expulsado','cancelado') NOT NULL default 'activo',
  `tiempo` varchar(20) NOT NULL default '0',
  `IP` varchar(12) NOT NULL default '0',
  `cargo` tinyint(3) unsigned NOT NULL default '12',
  `motivo` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `user_ID` (`user_ID`),
  KEY `estado` (`estado`),
  KEY `IP` (`IP`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hechos`
--

CREATE TABLE IF NOT EXISTS `hechos` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `time` date NOT NULL,
  `nick` varchar(14) NOT NULL default 'GONZO',
  `texto` varchar(2000) NOT NULL,
  `estado` enum('ok','del') NOT NULL default 'ok',
  `time2` datetime NOT NULL,
  `pais` enum('VirtualPol','POL','VULCAN','Hispania') NOT NULL default 'VirtualPol',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_ban`
--

CREATE TABLE IF NOT EXISTS `hispania_ban` (
  `ID` smallint(5) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `autor` mediumint(8) NOT NULL default '0',
  `expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `razon` varchar(150) NOT NULL,
  `estado` enum('activo','inactivo','expulsado','cancelado') NOT NULL default 'activo',
  `tiempo` varchar(20) NOT NULL default '0',
  `IP` varchar(12) NOT NULL default '0',
  `cargo` tinyint(3) unsigned NOT NULL default '12',
  `motivo` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `user_ID` (`user_ID`),
  KEY `estado` (`estado`),
  KEY `IP` (`IP`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_blog`
--

CREATE TABLE IF NOT EXISTS `hispania_blog` (
  `blog_ID` smallint(5) unsigned NOT NULL auto_increment,
  `url` varchar(20) NOT NULL,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `acceso` varchar(30) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `titulo` varchar(80) NOT NULL,
  `descripcion` varchar(900) NOT NULL,
  `time` datetime NOT NULL,
  `time_last` datetime NOT NULL,
  `tipo` enum('blog','periodico') NOT NULL,
  `estado` enum('ok','delete') NOT NULL,
  PRIMARY KEY  (`blog_ID`),
  UNIQUE KEY `url` (`url`),
  KEY `user_ID` (`user_ID`,`time_last`,`tipo`,`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_blog_com`
--

CREATE TABLE IF NOT EXISTS `hispania_blog_com` (
  `com_ID` mediumint(8) unsigned NOT NULL auto_increment,
  `post_ID` smallint(5) unsigned NOT NULL,
  `blog_ID` smallint(5) unsigned NOT NULL,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `nick` varchar(14) NOT NULL,
  `time` datetime NOT NULL,
  `texto` varchar(3000) NOT NULL,
  `estado` enum('ok','delete','spam') NOT NULL,
  PRIMARY KEY  (`com_ID`),
  KEY `post_ID` (`post_ID`,`blog_ID`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_blog_post`
--

CREATE TABLE IF NOT EXISTS `hispania_blog_post` (
  `post_ID` smallint(5) unsigned NOT NULL auto_increment,
  `blog_ID` smallint(5) unsigned NOT NULL,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `url` varchar(90) NOT NULL,
  `titulo` varchar(90) NOT NULL,
  `texto` text NOT NULL,
  `tags` varchar(90) NOT NULL,
  `num_com` smallint(5) unsigned NOT NULL,
  `estado` enum('ok','borrador') NOT NULL,
  PRIMARY KEY  (`post_ID`),
  KEY `blog_ID` (`blog_ID`,`user_ID`,`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_0`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_0` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_1`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_1` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_2`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_2` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_3`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_3` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_4`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_4` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_5`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_5` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_6`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_6` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_7`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_7` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_8`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_8` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_chat_9`
--

CREATE TABLE IF NOT EXISTS `hispania_chat_9` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_cuentas`
--

CREATE TABLE IF NOT EXISTS `hispania_cuentas` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(25) NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `user_ID` (`user_ID`),
  KEY `nivel` (`nivel`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_diputados`
--

CREATE TABLE IF NOT EXISTS `hispania_diputados` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_docs`
--

CREATE TABLE IF NOT EXISTS `hispania_docs` (
  `ID` smallint(5) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `nivel` tinyint(3) NOT NULL default '0',
  `estado` enum('ok','del','borrador') NOT NULL default 'ok',
  `cat_ID` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`),
  KEY `cat_ID` (`cat_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_elec`
--

CREATE TABLE IF NOT EXISTS `hispania_elec` (
  `ID` smallint(5) NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo` enum('pres','parl') NOT NULL default 'pres',
  `num_votantes` mediumint(8) NOT NULL default '0',
  `escrutinio` text NOT NULL,
  `num_votos` smallint(5) NOT NULL default '0',
  `pols_init` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`tipo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_elecciones`
--

CREATE TABLE IF NOT EXISTS `hispania_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_empresas`
--

CREATE TABLE IF NOT EXISTS `hispania_empresas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `url` varchar(40) NOT NULL default '',
  `nombre` varchar(40) NOT NULL default '',
  `user_ID` mediumint(8) NOT NULL default '0',
  `descripcion` text NOT NULL,
  `web` varchar(200) NOT NULL default '',
  `cat_ID` tinyint(3) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pv` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`,`cat_ID`),
  KEY `cat_ID` (`cat_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_estudios_users`
--

CREATE TABLE IF NOT EXISTS `hispania_estudios_users` (
  `ID` bigint(20) NOT NULL auto_increment,
  `ID_estudio` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `estado` enum('ok','estudiando','examen') NOT NULL default 'ok',
  `cargo` enum('0','1') NOT NULL default '0',
  `nota` decimal(3,1) unsigned default NULL,
  PRIMARY KEY  (`ID`),
  KEY `cargo` (`cargo`),
  KEY `estado` (`estado`),
  KEY `ID_estudio` (`ID_estudio`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_examenes_preg`
--

CREATE TABLE IF NOT EXISTS `hispania_examenes_preg` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `examen_ID` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `examen_ID` (`examen_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_foros_hilos`
--

CREATE TABLE IF NOT EXISTS `hispania_foros_hilos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `sub_ID` smallint(5) NOT NULL default '0',
  `url` varchar(80) NOT NULL default '',
  `user_ID` mediumint(8) NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL default '0',
  `num` smallint(5) NOT NULL default '0',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_foros_msg`
--

CREATE TABLE IF NOT EXISTS `hispania_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_log`
--

CREATE TABLE IF NOT EXISTS `hispania_log` (
  `ID` bigint(12) unsigned NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `user_ID2` mediumint(8) unsigned NOT NULL default '0',
  `accion` tinyint(3) unsigned NOT NULL default '0',
  `dato` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_mapa`
--

CREATE TABLE IF NOT EXISTS `hispania_mapa` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `pos_x` tinyint(2) NOT NULL default '1',
  `pos_y` tinyint(2) NOT NULL default '1',
  `size_x` tinyint(2) NOT NULL default '1',
  `size_y` tinyint(2) NOT NULL default '1',
  `user_ID` mediumint(8) NOT NULL default '1',
  `link` varchar(90) NOT NULL default '',
  `text` varchar(90) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pols` mediumint(8) NOT NULL default '0',
  `color` char(3) NOT NULL default '',
  `estado` enum('p','v','e') NOT NULL default 'p',
  `superficie` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `pos_x` (`pos_x`),
  KEY `pos_y` (`pos_y`),
  KEY `estado` (`estado`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_mercado`
--

CREATE TABLE IF NOT EXISTS `hispania_mercado` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `title` varchar(90) NOT NULL default '',
  `descripcion` text NOT NULL,
  `pols` mediumint(8) NOT NULL default '0',
  `tipo` enum('subasta','venta','compra') NOT NULL default 'subasta',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `estado` enum('ok','old') NOT NULL default 'ok',
  PRIMARY KEY  (`ID`),
  KEY `tipo` (`tipo`,`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_partidos`
--

CREATE TABLE IF NOT EXISTS `hispania_partidos` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_presidente` mediumint(7) NOT NULL default '0',
  `fecha_creacion` datetime NOT NULL default '0000-00-00 00:00:00',
  `siglas` varchar(12) NOT NULL default '',
  `nombre` varchar(40) NOT NULL default '',
  `descripcion` text NOT NULL,
  `estado` enum('ok') NOT NULL default 'ok',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `siglas` (`siglas`),
  UNIQUE KEY `ID_presidente` (`ID_presidente`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_partidos_listas`
--

CREATE TABLE IF NOT EXISTS `hispania_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_pujas`
--

CREATE TABLE IF NOT EXISTS `hispania_pujas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `mercado_ID` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `pols` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_ref`
--

CREATE TABLE IF NOT EXISTS `hispania_ref` (
  `ID` smallint(5) NOT NULL auto_increment,
  `pregunta` varchar(255) NOT NULL default '',
  `descripcion` text NOT NULL,
  `respuestas` text NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_ID` mediumint(8) NOT NULL default '0',
  `estado` enum('ok','end') NOT NULL default 'ok',
  `num` smallint(5) NOT NULL default '0',
  `tipo` enum('sondeo','referendum','parlamento') NOT NULL default 'sondeo',
  PRIMARY KEY  (`ID`),
  KEY `tipo` (`tipo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_ref_votos`
--

CREATE TABLE IF NOT EXISTS `hispania_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_stats`
--

CREATE TABLE IF NOT EXISTS `hispania_stats` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ciudadanos` smallint(5) unsigned NOT NULL default '0',
  `nuevos` smallint(5) unsigned NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `pols_cuentas` int(10) NOT NULL default '0',
  `transacciones` smallint(5) unsigned NOT NULL default '0',
  `hilos_msg` smallint(5) unsigned NOT NULL default '0',
  `pols_gobierno` int(10) NOT NULL default '0',
  `partidos` tinyint(3) unsigned NOT NULL default '0',
  `frase` smallint(5) unsigned NOT NULL default '0',
  `empresas` smallint(5) unsigned NOT NULL default '0',
  `eliminados` smallint(5) unsigned NOT NULL default '0',
  `mapa` tinyint(3) unsigned NOT NULL default '0',
  `mapa_vende` tinyint(3) unsigned NOT NULL default '0',
  `24h` smallint(5) unsigned NOT NULL default '0',
  `confianza` smallint(5) NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_transacciones`
--

CREATE TABLE IF NOT EXISTS `hispania_transacciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `pols` int(10) NOT NULL default '0',
  `emisor_ID` mediumint(8) NOT NULL default '0',
  `receptor_ID` mediumint(8) NOT NULL default '0',
  `concepto` varchar(90) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `emisor_ID` (`emisor_ID`),
  KEY `receptor_ID` (`receptor_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mails`
--

CREATE TABLE IF NOT EXISTS `mails` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `email` varchar(140) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE IF NOT EXISTS `mensajes` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `envia_ID` mediumint(8) unsigned NOT NULL default '0',
  `recibe_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `leido` enum('0','1') NOT NULL default '0',
  `cargo` smallint(5) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `envia_ID` (`envia_ID`),
  KEY `recibe_ID` (`recibe_ID`),
  KEY `leido` (`leido`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_ban`
--

CREATE TABLE IF NOT EXISTS `pol_ban` (
  `ID` smallint(5) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `autor` mediumint(8) NOT NULL default '0',
  `expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `razon` varchar(150) NOT NULL,
  `estado` enum('activo','inactivo','expulsado','cancelado') NOT NULL default 'activo',
  `tiempo` varchar(20) NOT NULL default '0',
  `IP` varchar(12) NOT NULL default '0',
  `cargo` tinyint(3) unsigned NOT NULL default '12',
  `motivo` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `user_ID` (`user_ID`),
  KEY `estado` (`estado`),
  KEY `IP` (`IP`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_blog`
--

CREATE TABLE IF NOT EXISTS `pol_blog` (
  `blog_ID` smallint(5) unsigned NOT NULL auto_increment,
  `url` varchar(20) NOT NULL,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `acceso` varchar(30) NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `titulo` varchar(80) NOT NULL,
  `descripcion` varchar(900) NOT NULL,
  `time` datetime NOT NULL,
  `time_last` datetime NOT NULL,
  `tipo` enum('blog','periodico') NOT NULL,
  `estado` enum('ok','delete') NOT NULL,
  PRIMARY KEY  (`blog_ID`),
  UNIQUE KEY `url` (`url`),
  KEY `user_ID` (`user_ID`,`time_last`,`tipo`,`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_blog_com`
--

CREATE TABLE IF NOT EXISTS `pol_blog_com` (
  `com_ID` mediumint(8) unsigned NOT NULL auto_increment,
  `post_ID` smallint(5) unsigned NOT NULL,
  `blog_ID` smallint(5) unsigned NOT NULL,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `nick` varchar(14) NOT NULL,
  `time` datetime NOT NULL,
  `texto` varchar(3000) NOT NULL,
  `estado` enum('ok','delete','spam') NOT NULL,
  PRIMARY KEY  (`com_ID`),
  KEY `post_ID` (`post_ID`,`blog_ID`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_blog_post`
--

CREATE TABLE IF NOT EXISTS `pol_blog_post` (
  `post_ID` smallint(5) unsigned NOT NULL auto_increment,
  `blog_ID` smallint(5) unsigned NOT NULL,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `url` varchar(90) NOT NULL,
  `titulo` varchar(90) NOT NULL,
  `texto` text NOT NULL,
  `tags` varchar(90) NOT NULL,
  `num_com` smallint(5) unsigned NOT NULL,
  `estado` enum('ok','borrador') NOT NULL,
  PRIMARY KEY  (`post_ID`),
  KEY `blog_ID` (`blog_ID`,`user_ID`,`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_0`
--

CREATE TABLE IF NOT EXISTS `pol_chat_0` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_1`
--

CREATE TABLE IF NOT EXISTS `pol_chat_1` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_2`
--

CREATE TABLE IF NOT EXISTS `pol_chat_2` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_3`
--

CREATE TABLE IF NOT EXISTS `pol_chat_3` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_4`
--

CREATE TABLE IF NOT EXISTS `pol_chat_4` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_5`
--

CREATE TABLE IF NOT EXISTS `pol_chat_5` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_6`
--

CREATE TABLE IF NOT EXISTS `pol_chat_6` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_7`
--

CREATE TABLE IF NOT EXISTS `pol_chat_7` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_8`
--

CREATE TABLE IF NOT EXISTS `pol_chat_8` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_chat_9`
--

CREATE TABLE IF NOT EXISTS `pol_chat_9` (
  `ID_msg` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(40) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `msg` varchar(900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  PRIMARY KEY  (`ID_msg`),
  KEY `tipo` (`tipo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo` (`cargo`),
  KEY `time` (`time`),
  KEY `nick` (`nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_cuentas`
--

CREATE TABLE IF NOT EXISTS `pol_cuentas` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(25) NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `user_ID` (`user_ID`),
  KEY `nivel` (`nivel`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_diputados`
--

CREATE TABLE IF NOT EXISTS `pol_diputados` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_docs`
--

CREATE TABLE IF NOT EXISTS `pol_docs` (
  `ID` smallint(5) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `nivel` tinyint(3) NOT NULL default '0',
  `estado` enum('ok','del','borrador') NOT NULL default 'ok',
  `cat_ID` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`),
  KEY `cat_ID` (`cat_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_elec`
--

CREATE TABLE IF NOT EXISTS `pol_elec` (
  `ID` smallint(5) NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo` enum('pres','parl') NOT NULL default 'pres',
  `num_votantes` mediumint(8) NOT NULL default '0',
  `escrutinio` text NOT NULL,
  `num_votos` smallint(5) NOT NULL default '0',
  `pols_init` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`tipo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_elecciones`
--

CREATE TABLE IF NOT EXISTS `pol_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_empresas`
--

CREATE TABLE IF NOT EXISTS `pol_empresas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `url` varchar(40) NOT NULL default '',
  `nombre` varchar(40) NOT NULL default '',
  `user_ID` mediumint(8) NOT NULL default '0',
  `descripcion` text NOT NULL,
  `web` varchar(200) NOT NULL default '',
  `cat_ID` tinyint(3) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pv` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`,`cat_ID`),
  KEY `cat_ID` (`cat_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_estudios_users`
--

CREATE TABLE IF NOT EXISTS `pol_estudios_users` (
  `ID` bigint(20) NOT NULL auto_increment,
  `ID_estudio` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `estado` enum('ok','estudiando','examen') NOT NULL default 'ok',
  `cargo` enum('0','1') NOT NULL default '0',
  `nota` decimal(3,1) unsigned default NULL,
  PRIMARY KEY  (`ID`),
  KEY `cargo` (`cargo`),
  KEY `estado` (`estado`),
  KEY `ID_estudio` (`ID_estudio`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_examenes_preg`
--

CREATE TABLE IF NOT EXISTS `pol_examenes_preg` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `examen_ID` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `examen_ID` (`examen_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_foros_hilos`
--

CREATE TABLE IF NOT EXISTS `pol_foros_hilos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `sub_ID` smallint(5) NOT NULL default '0',
  `url` varchar(80) NOT NULL default '',
  `user_ID` mediumint(8) NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL default '0',
  `num` smallint(5) NOT NULL default '0',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_foros_msg`
--

CREATE TABLE IF NOT EXISTS `pol_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_log`
--

CREATE TABLE IF NOT EXISTS `pol_log` (
  `ID` bigint(12) unsigned NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `user_ID2` mediumint(8) unsigned NOT NULL default '0',
  `accion` tinyint(3) unsigned NOT NULL default '0',
  `dato` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_mapa`
--

CREATE TABLE IF NOT EXISTS `pol_mapa` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `pos_x` tinyint(2) NOT NULL default '1',
  `pos_y` tinyint(2) NOT NULL default '1',
  `size_x` tinyint(2) NOT NULL default '1',
  `size_y` tinyint(2) NOT NULL default '1',
  `user_ID` mediumint(8) NOT NULL default '1',
  `link` varchar(90) NOT NULL default '',
  `text` varchar(90) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pols` mediumint(8) NOT NULL default '0',
  `color` char(3) NOT NULL default '',
  `estado` enum('p','v','e') NOT NULL default 'p',
  `superficie` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `pos_x` (`pos_x`),
  KEY `pos_y` (`pos_y`),
  KEY `estado` (`estado`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_mercado`
--

CREATE TABLE IF NOT EXISTS `pol_mercado` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `title` varchar(90) NOT NULL default '',
  `descripcion` text NOT NULL,
  `pols` mediumint(8) NOT NULL default '0',
  `tipo` enum('subasta','venta','compra') NOT NULL default 'subasta',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `estado` enum('ok','old') NOT NULL default 'ok',
  PRIMARY KEY  (`ID`),
  KEY `tipo` (`tipo`,`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_partidos`
--

CREATE TABLE IF NOT EXISTS `pol_partidos` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_presidente` mediumint(7) NOT NULL default '0',
  `fecha_creacion` datetime NOT NULL default '0000-00-00 00:00:00',
  `siglas` varchar(12) NOT NULL default '',
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('ok') NOT NULL default 'ok',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `siglas` (`siglas`),
  UNIQUE KEY `ID_presidente` (`ID_presidente`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_partidos_listas`
--

CREATE TABLE IF NOT EXISTS `pol_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_pujas`
--

CREATE TABLE IF NOT EXISTS `pol_pujas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `mercado_ID` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `pols` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_ref`
--

CREATE TABLE IF NOT EXISTS `pol_ref` (
  `ID` smallint(5) NOT NULL auto_increment,
  `pregunta` varchar(255) NOT NULL default '',
  `descripcion` text NOT NULL,
  `respuestas` text NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_ID` mediumint(8) NOT NULL default '0',
  `estado` enum('ok','end') NOT NULL default 'ok',
  `num` smallint(5) NOT NULL default '0',
  `tipo` enum('sondeo','referendum','parlamento') NOT NULL default 'sondeo',
  PRIMARY KEY  (`ID`),
  KEY `tipo` (`tipo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_ref_votos`
--

CREATE TABLE IF NOT EXISTS `pol_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_stats`
--

CREATE TABLE IF NOT EXISTS `pol_stats` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ciudadanos` smallint(5) unsigned NOT NULL default '0',
  `nuevos` smallint(5) unsigned NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `pols_cuentas` int(10) NOT NULL default '0',
  `transacciones` smallint(5) unsigned NOT NULL default '0',
  `hilos_msg` smallint(5) unsigned NOT NULL default '0',
  `pols_gobierno` int(10) NOT NULL default '0',
  `partidos` tinyint(3) unsigned NOT NULL default '0',
  `frase` smallint(5) unsigned NOT NULL default '0',
  `empresas` smallint(5) unsigned NOT NULL default '0',
  `eliminados` smallint(5) unsigned NOT NULL default '0',
  `mapa` tinyint(3) unsigned NOT NULL default '0',
  `mapa_vende` tinyint(3) unsigned NOT NULL default '0',
  `24h` smallint(5) unsigned NOT NULL default '0',
  `confianza` smallint(5) NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_transacciones`
--

CREATE TABLE IF NOT EXISTS `pol_transacciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `pols` int(10) NOT NULL default '0',
  `emisor_ID` mediumint(8) NOT NULL default '0',
  `receptor_ID` mediumint(8) NOT NULL default '0',
  `concepto` varchar(90) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `emisor_ID` (`emisor_ID`),
  KEY `receptor_ID` (`receptor_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencias`
--

CREATE TABLE IF NOT EXISTS `referencias` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `IP` varchar(10) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `referer` varchar(255) NOT NULL default '',
  `pagado` enum('0','1') NOT NULL default '0',
  `new_user_ID` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `IP` (`IP`),
  KEY `user_ID` (`user_ID`),
  KEY `pagado` (`pagado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(14) NOT NULL default '',
  `pais` enum('ninguno','POL','VULCAN','Hispania') NOT NULL default 'POL',
  `pols` int(10) NOT NULL default '0',
  `fecha_registro` datetime NOT NULL default '0000-00-00 00:00:00',
  `fecha_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `partido_afiliado` smallint(6) NOT NULL default '0',
  `estado` enum('turista','ciudadano','expulsado','desarrollador','validar') NOT NULL default 'validar',
  `nivel` tinyint(3) unsigned NOT NULL default '1',
  `email` varchar(255) NOT NULL default '',
  `pass` varchar(64) NOT NULL,
  `num_elec` tinyint(3) unsigned NOT NULL default '0',
  `online` int(10) unsigned NOT NULL default '0',
  `fecha_init` datetime NOT NULL default '0000-00-00 00:00:00',
  `ref` mediumint(8) unsigned NOT NULL default '0',
  `ref_num` tinyint(3) unsigned NOT NULL default '0',
  `api_pass` varchar(16) NOT NULL default '0',
  `api_num` smallint(5) NOT NULL default '0',
  `IP` varchar(12) NOT NULL default '0',
  `nota` decimal(3,1) NOT NULL default '0.0',
  `avatar` enum('true','false') NOT NULL default 'false',
  `text` varchar(1900) NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `visitas` mediumint(8) unsigned NOT NULL default '0',
  `paginas` int(10) unsigned NOT NULL default '0',
  `nav` varchar(500) NOT NULL,
  `voto_confianza` smallint(5) NOT NULL default '0',
  `rechazo_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `avatar_localdir` varchar(100) NOT NULL,
  `host` varchar(150) NOT NULL,
  `IP_proxy` varchar(150) NOT NULL,
  `geo` varchar(200) NOT NULL,
  `dnie_check` varchar(400) default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `api_pass` (`api_pass`),
  UNIQUE KEY `dnie_check` (`dnie_check`),
  KEY `estado` (`estado`),
  KEY `partido_afiliado` (`partido_afiliado`),
  KEY `nivel` (`nivel`),
  KEY `pols` (`pols`),
  KEY `cargo` (`cargo`),
  KEY `voto_confianza` (`voto_confianza`),
  KEY `pais` (`pais`),
  KEY `ref_num` (`ref_num`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votos`
--

CREATE TABLE IF NOT EXISTS `votos` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `uservoto_ID` mediumint(8) unsigned NOT NULL,
  `voto` tinyint(3) NOT NULL,
  `time` datetime NOT NULL,
  `estado` enum('confianza') NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `estado` (`estado`),
  KEY `uservoto_ID` (`uservoto_ID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;