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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `api_pass` (`api_pass`),
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_ban`
--

CREATE TABLE IF NOT EXISTS `vulcan_ban` (
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
-- Estructura de tabla para la tabla `vulcan_blog`
--

CREATE TABLE IF NOT EXISTS `vulcan_blog` (
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
-- Estructura de tabla para la tabla `vulcan_blog_com`
--

CREATE TABLE IF NOT EXISTS `vulcan_blog_com` (
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
-- Estructura de tabla para la tabla `vulcan_blog_post`
--

CREATE TABLE IF NOT EXISTS `vulcan_blog_post` (
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
-- Estructura de tabla para la tabla `vulcan_chat_0`
--

CREATE TABLE IF NOT EXISTS `vulcan_chat_0` (
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
-- Estructura de tabla para la tabla `vulcan_chat_1`
--

CREATE TABLE IF NOT EXISTS `vulcan_chat_1` (
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
-- Estructura de tabla para la tabla `vulcan_chat_2`
--

CREATE TABLE IF NOT EXISTS `vulcan_chat_2` (
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
-- Estructura de tabla para la tabla `vulcan_chat_3`
--

CREATE TABLE IF NOT EXISTS `vulcan_chat_3` (
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
-- Estructura de tabla para la tabla `vulcan_chat_4`
--

CREATE TABLE IF NOT EXISTS `vulcan_chat_4` (
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
-- Estructura de tabla para la tabla `vulcan_chat_5`
--

CREATE TABLE IF NOT EXISTS `vulcan_chat_5` (
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
-- Estructura de tabla para la tabla `vulcan_chat_6`
--

CREATE TABLE IF NOT EXISTS `vulcan_chat_6` (
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
-- Estructura de tabla para la tabla `vulcan_chat_7`
--

CREATE TABLE IF NOT EXISTS `vulcan_chat_7` (
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
-- Estructura de tabla para la tabla `vulcan_chat_8`
--

CREATE TABLE IF NOT EXISTS `vulcan_chat_8` (
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
-- Estructura de tabla para la tabla `vulcan_cuentas`
--

CREATE TABLE IF NOT EXISTS `vulcan_cuentas` (
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
-- Estructura de tabla para la tabla `vulcan_diputados`
--

CREATE TABLE IF NOT EXISTS `vulcan_diputados` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_docs`
--

CREATE TABLE IF NOT EXISTS `vulcan_docs` (
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
-- Estructura de tabla para la tabla `vulcan_elec`
--

CREATE TABLE IF NOT EXISTS `vulcan_elec` (
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
-- Estructura de tabla para la tabla `vulcan_elecciones`
--

CREATE TABLE IF NOT EXISTS `vulcan_elecciones` (
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
-- Estructura de tabla para la tabla `vulcan_empresas`
--

CREATE TABLE IF NOT EXISTS `vulcan_empresas` (
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
-- Estructura de tabla para la tabla `vulcan_estudios_users`
--

CREATE TABLE IF NOT EXISTS `vulcan_estudios_users` (
  `ID` bigint(20) NOT NULL auto_increment,
  `ID_estudio` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `estado` enum('ok','estudiando','examen') NOT NULL default 'ok',
  `cargo` enum('0','1') NOT NULL default '0',
  `nota` decimal(3,1) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `cargo` (`cargo`),
  KEY `estado` (`estado`),
  KEY `ID_estudio` (`ID_estudio`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_examenes_preg`
--

CREATE TABLE IF NOT EXISTS `vulcan_examenes_preg` (
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
-- Estructura de tabla para la tabla `vulcan_foros_hilos`
--

CREATE TABLE IF NOT EXISTS `vulcan_foros_hilos` (
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
-- Estructura de tabla para la tabla `vulcan_foros_msg`
--

CREATE TABLE IF NOT EXISTS `vulcan_foros_msg` (
  `ID` int(10) NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_log`
--

CREATE TABLE IF NOT EXISTS `vulcan_log` (
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
-- Estructura de tabla para la tabla `vulcan_mapa`
--

CREATE TABLE IF NOT EXISTS `vulcan_mapa` (
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
-- Estructura de tabla para la tabla `vulcan_mercado`
--

CREATE TABLE IF NOT EXISTS `vulcan_mercado` (
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
-- Estructura de tabla para la tabla `vulcan_partidos`
--

CREATE TABLE IF NOT EXISTS `vulcan_partidos` (
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
-- Estructura de tabla para la tabla `vulcan_partidos_listas`
--

CREATE TABLE IF NOT EXISTS `vulcan_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_pujas`
--

CREATE TABLE IF NOT EXISTS `vulcan_pujas` (
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
-- Estructura de tabla para la tabla `vulcan_ref`
--

CREATE TABLE IF NOT EXISTS `vulcan_ref` (
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
-- Estructura de tabla para la tabla `vulcan_ref_votos`
--

CREATE TABLE IF NOT EXISTS `vulcan_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_stats`
--

CREATE TABLE IF NOT EXISTS `vulcan_stats` (
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
-- Estructura de tabla para la tabla `vulcan_transacciones`
--

CREATE TABLE IF NOT EXISTS `vulcan_transacciones` (
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


-- phpMyAdmin SQL Dump
-- version 2.11.9.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 09-10-2009 a las 17:55:52
-- Versión del servidor: 5.0.85
-- Versión de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `teoriza_virtualpol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_cat`
--

CREATE TABLE IF NOT EXISTS `hispania_cat` (
  `ID` tinyint(3) NOT NULL auto_increment,
  `url` varchar(80) NOT NULL default '',
  `nombre` varchar(80) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `num` smallint(5) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `tipo` enum('empresas','docs','cargos') NOT NULL default 'empresas',
  PRIMARY KEY  (`ID`),
  KEY `url` (`url`,`tipo`),
  KEY `tipo` (`tipo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `hispania_cat`
--

INSERT INTO `hispania_cat` (`ID`, `url`, `nombre`, `time`, `num`, `nivel`, `tipo`) VALUES
(1, 'periodicos', 'Periodicos', '2008-10-07 00:00:00', 49, 0, 'empresas'),
(2, 'bufetes-de-abogados', 'Bufetes de abogados', '2008-10-07 00:00:00', 27, 0, 'empresas'),
(3, 'bancos', 'Bancos', '2008-10-07 00:00:00', 51, 0, 'empresas'),
(4, 'loterias-y-apuestas', 'Loterias y apuestas', '2008-10-07 00:00:00', 40, 0, 'empresas'),
(5, 'otros', 'Otros', '2008-10-08 00:00:00', 158, 0, 'empresas'),
(6, 'leyes-vigentes', 'Leyes vigentes', '0000-00-00 00:00:00', 0, 85, 'docs'),
(7, 'otros-documentos', 'Otros documentos', '0000-00-00 09:00:00', 0, 0, 'docs'),
(8, 'ayuda', 'Ayuda', '0000-00-00 00:06:00', 0, 20, 'docs'),
(9, 'leyes-derogadas', 'Leyes derogadas', '0000-00-00 00:02:00', 0, 85, 'docs'),
(10, 'leyes-propuestas', 'Leyes propuestas', '0000-00-00 00:01:00', 0, 0, 'docs'),
(11, 'sugerencias', 'Sugerencias', '0000-00-00 00:07:00', 0, 0, 'docs'),
(12, 'informacion', 'Informaci&oacute;n', '0000-00-00 00:05:00', 0, 0, 'docs'),
(13, 'historia', 'Historia', '0000-00-00 00:06:10', 0, 0, 'docs'),
(14, 'reglamento-vigente', 'Reglamento vigente', '0000-00-00 00:00:50', 0, 85, 'docs'),
(15, 'actas-del-parlamento', 'Actas del Parlamento', '0000-00-00 08:00:00', 0, 90, 'docs'),
(16, 'ssal', 'Sociedades Sin Animo de Lucro', '2008-10-07 00:00:00', 6, 0, 'empresas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_config`
--

CREATE TABLE IF NOT EXISTS `hispania_config` (
  `ID` tinyint(3) NOT NULL auto_increment,
  `dato` varchar(30) NOT NULL default '',
  `valor` text NOT NULL,
  `autoload` enum('si','no') NOT NULL default 'no',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `dato` (`dato`),
  KEY `autoload` (`autoload`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `hispania_config`
--

INSERT INTO `hispania_config` (`ID`, `dato`, `valor`, `autoload`) VALUES
(1, 'tiempo_turista', '1', 'si'),
(2, 'info_censo', '23', 'si'),
(3, 'info_partidos', '5', 'si'),
(4, 'info_documentos', '34', 'si'),
(5, 'elecciones_estado', 'normal', 'si'),
(7, 'num_escanos', '3', 'si'),
(8, 'elecciones_inicio', '2009-10-16 20:00:00', 'si'),
(9, 'elecciones_duracion', '172800', 'si'),
(10, 'elecciones_frecuencia', '1036800', 'si'),
(11, 'elecciones_antiguedad', '86400', 'si'),
(12, 'pols_frase', '<a href="http://hispania.virtualpol.com/empresas/bancos/fife--surrey-bank/">FIFE & SURREY Bank, la mejor opciÃ³n para hacer tu dinero rentable</a>', 'si'),
(23, 'palabras', '20025:hispania.virtualpol.com/partidos/cih/:Vota CIH', 'si'),
(35, 'examenes_exp', '7776000', 'no'),
(13, 'pols_afiliacion', '50', 'no'),
(14, 'pols_fraseedit', '20025', 'si'),
(15, 'info_consultas', '1', 'si'),
(16, 'pols_empresa', '0', 'si'),
(17, 'pols_cuentas', '30', 'si'),
(18, 'pols_partido', '20', 'si'),
(19, 'defcon', '5', 'si'),
(20, 'pols_inem', '50', 'no'),
(21, 'online_ref', '18000', 'no'),
(22, 'factor_propiedad', '0.3', 'no'),
(24, 'palabras_num', '5', 'no'),
(26, 'elecciones', '_pres', 'si'),
(27, 'examen_repe', '86400', 'no'),
(28, 'pols_solar', '1', 'no'),
(29, 'pols_mensajetodos', '1000', 'no'),
(30, 'pols_examen', '0', 'no'),
(31, 'pols_mensajeurgente', '0', 'no'),
(33, 'frontera', 'abierta', 'si'),
(34, 'palabra_gob', 'Poner aquÃ­ todo lo que querais en VirtualPol:code.google.com/p/virtualpol/issues/list', 'si'),
(36, 'impuestos_minimo', '1', 'no'),
(37, 'impuestos', '0', 'no'),
(40, 'bg', '41original.gif', 'si'),
(38, 'arancel_entrada', '', 'no'),
(39, 'arancel_salida', '1', 'no'),
(42, 'impuestos_empresa', '0', 'no'),
(41, 'pais_des', 'RepÃºblica DemocrÃ¡tica', 'si');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_estudios`
--

CREATE TABLE IF NOT EXISTS `hispania_estudios` (
  `ID` tinyint(3) unsigned NOT NULL auto_increment,
  `nombre` varchar(30) NOT NULL default '',
  `tiempo` mediumint(9) NOT NULL default '86400',
  `nivel` tinyint(3) NOT NULL default '1',
  `num_cargo` smallint(5) NOT NULL default '1',
  `asigna` smallint(5) NOT NULL default '7',
  `salario` smallint(5) NOT NULL default '0',
  `ico` enum('true','false','') NOT NULL default 'false',
  PRIMARY KEY  (`ID`),
  KEY `nivel` (`nivel`),
  KEY `nombre` (`nombre`),
  KEY `asigna` (`asigna`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `hispania_estudios`
--

INSERT INTO `hispania_estudios` (`ID`, `nombre`, `tiempo`, `nivel`, `num_cargo`, `asigna`, `salario`, `ico`) VALUES
(6, 'Diputado', 600, 90, 15, 22, 50, 'true'),
(7, 'Presidente', 70000, 100, 1, 0, 500, 'true'),
(8, 'Juez de Paz', 600000, 80, 3, 9, 300, 'true'),
(9, 'Juez Supremo', 300000, 85, 1, 7, 300, 'true'),
(11, 'Fiscal', 0, 60, 2, 7, 200, 'true'),
(12, 'Policia', 432000, 30, 6, 13, 200, 'true'),
(13, 'Comisario de Policia', 0, 50, 2, 7, 250, 'true'),
(16, 'Ministro', 60, 95, 6, 7, 200, 'true'),
(40, 'Arquitecto', 86400, 20, 1, 7, 200, 'true'),
(19, 'Vicepresidente', 600000, 98, 1, 7, 300, 'true'),
(20, 'Defensor del Pueblo', 60, 50, 1, 7, 200, 'true'),
(21, 'Supervisor del Censo', 60, 70, 2, 7, 200, 'true'),
(22, 'Presidente Parlamento', 60, 97, 1, 7, 50, 'true'),
(41, 'Consultor', 86400, 75, 1, 7, 200, 'true'),
(25, 'Notario', 181020, 20, 3, 7, 200, 'true'),
(26, 'Jefe de Prensa', 6000, 75, 2, 7, 200, 'true'),
(27, 'Secretario de Estado', 60, 50, 0, 7, 200, 'true'),
(28, 'Guia Turistico', 86400, 20, 0, 7, 200, 'true'),
(34, 'Profesor', 1, 10, 10, 35, 200, 'true'),
(35, 'Decano', 1, 15, 9, 7, 200, 'true'),
(36, 'Secretario', 61, 20, 0, 7, 200, 'true'),
(37, 'Funcionario', 61, 25, 10, 7, 200, 'true'),
(42, 'Embajador', 86400, 92, 1, 7, 200, 'true');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_examenes`
--

CREATE TABLE IF NOT EXISTS `hispania_examenes` (
  `ID` smallint(5) NOT NULL auto_increment,
  `titulo` varchar(100) NOT NULL default '',
  `descripcion` text NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cargo_ID` smallint(5) NOT NULL default '0',
  `nota` varchar(5) NOT NULL default '5.0',
  `num_preguntas` smallint(5) NOT NULL default '10',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `titulo` (`titulo`),
  KEY `nota` (`nota`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `hispania_examenes`
--

INSERT INTO `hispania_examenes` (`ID`, `titulo`, `descripcion`, `user_ID`, `time`, `cargo_ID`, `nota`, `num_preguntas`) VALUES
(10, 'Fiscal', 'Temario del examen a Fiscal:<br />\r\n- Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n- Ley del poder Judicial <br />\r<a href="\nhttp://hispania.virtualpol.com/doc/ley-del-poder-judicial/">\nhttp://hispania.virtualpol.com/doc/ley-del-poder-judicial/</a><br />\r\n<br />\r\n- C&oacute;digo Penal<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/">\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/</a><br />\r\n<br />\r\n- Conocimientos generales sobre Hispania', 1, '2008-11-21 13:10:17', 11, '8.5', 20),
(9, 'Juez Supremo', '- Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n- C&oacute;digo Penal de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/">\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/</a><br />\r\n', 1, '2008-11-21 13:10:17', 9, '9.0', 20),
(8, 'Juez de Paz', '- Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n- C&oacute;digo Penal de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/">\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/</a><br />\r\n<br />\r\n', 1, '2008-11-21 13:10:17', 8, '8.5', 20),
(6, 'Diputado', '-Conocimientos generales de Hispania.<br />\r\n<br />\r\n- Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n- Ley Electoral y de la Ciudadan&iacute;a<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/ley-electoral-y-de-la-ciudadania/">\nhttp://hispania.virtualpol.com/doc/ley-electoral-y-de-la-ciudadania/</a>', 1, '2008-11-21 13:10:17', 6, '7', 20),
(11, 'Policia', 'Para el examen de polic&iacute;a entrar&aacute;n las siguientes materias<br />\r\n<br />\r\n- Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n- C&oacute;digo Penal<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/">\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/</a><br />\r\n', 1, '2008-11-21 13:10:17', 12, '7.5', 15),
(12, 'Comisario de Policia', '- Conocimientos generales sobre Hispania y VirtualPOL<br />\r\n<br />\r\n- Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n- Ley Reguladora de la Autoridad Policial<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/ley-reguladora-de-la-autoridad-policial-en-proceso-de-redaccion/">\nhttp://hispania.virtualpol.com/doc/ley-reguladora-de-la-autoridad-policial-en-proceso-de-redaccion/</a>', 1, '2008-11-21 13:10:17', 13, '8.0', 20),
(14, 'Ministro', 'Temario:<br />\r\n-Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n-Conocimientos generales', 1, '2008-11-21 13:10:17', 16, '8.0', 15),
(16, 'Vicepresidente', 'Temario:<br />\r\n-Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a>', 1, '2008-11-21 13:10:17', 19, '6.0', 10),
(17, 'Defensor del Pueblo', 'Temario:<br />\r\n-Ley del Defensor del Pueblo<br />\r<a href="\nhttp://pol.virtualpol.com/doc/ley-del-defensor-del-pueblo/">\nhttp://pol.virtualpol.com/doc/ley-del-defensor-del-pueblo/</a><br />\r\n<br />\r\n-Ley del poder Judicial<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/ley-del-poder-judicial/">\nhttp://hispania.virtualpol.com/doc/ley-del-poder-judicial/</a><br />\r\n<br />\r\n-C&oacute;digo Penal<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/">\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/</a><br />\r\n', 1, '2008-11-21 13:10:17', 20, '8.5', 20),
(18, 'Supervisor del Censo', '- Ley del supervisor del censo (no est&aacute; en vigor todav&iacute;a):<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/ley-del-supervisor-del-censo/">\nhttp://hispania.virtualpol.com/doc/ley-del-supervisor-del-censo/</a>', 1, '2008-11-21 13:10:17', 21, '9.5', 15),
(19, 'Presidente Parlamento', 'Para el examen de Presidente del Parlamento entrar&aacute;n las siguientes materias:<br />\r\n<br />\r\n- Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n', 1, '2008-11-21 13:10:17', 22, '8.0', 20),
(59, 'Ciudadano', 'Honradez.', 200189, '2009-08-13 13:18:31', -59, '10', 15),
(45, 'Consultor', '- Conocimientos sobre el sistema de consultas.<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/propuesta-de-ley-sobre-las-consultas-populares/">\nhttp://hispania.virtualpol.com/doc/propuesta-de-ley-sobre-las-consultas-populares/</a><br />\r\n<br />\r\n- Capacidad para confeccionar consultas neutrales y correctas.<br />\r\n', 1, '2009-02-09 12:37:21', 41, '10', 10),
(21, 'Notario', 'Todas las leyes vigentes en POL', 1, '2008-11-21 13:10:17', 25, '9.0', 20),
(22, 'Jefe de Prensa', '-Reglamento del peri&oacute;dico nacional de POL<br />\r<a href="\nhttp://pol.virtualpol.com/doc/reglamento-del-periodico-nacional-de-pol/">\nhttp://pol.virtualpol.com/doc/reglamento-del-periodico-nacional-de-pol/</a><br />\r\n<br />\r\n-Conocimientos generales de Hispania', 1, '2008-11-21 13:10:17', 26, '7.0', 15),
(23, 'Secretario de Estado', '-Conocimientos Generales de Hispania y VirtualPol.', 1, '2008-11-21 13:10:17', 27, '7.5', 10),
(24, 'GuÃ­a Turistico', 'Conocimientos Generales de Hispania y VirtualPol.', 1, '2008-11-21 13:10:17', 28, '7.0', 15),
(25, 'Funcionario', '- Conocimientos de jerarqu&iacute;a de cargos<br />\r<a href="\nhttp://hispania.virtualpol.com/cargos/">\nhttp://hispania.virtualpol.com/cargos/</a><br />\r\n<br />\r\n- Conocimientos generales sobre documentos<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/">\nhttp://hispania.virtualpol.com/doc/</a><br />\r\n<br />\r\n- Conocimientos b&aacute;sicos sobre juicios<br />\r<a href="\nhttp://hispania.virtualpol.com/foro/justicia/">\nhttp://hispania.virtualpol.com/foro/justicia/</a><br />\r\n<br />\r\n- Conocimientos generales de VirtualPOL', 1, '2008-11-21 13:10:17', 37, '5.0', 15),
(27, 'Agente de Empleo', 'Examen desactualizado y sin utilidad vigente.<br />\r\n<br />\r\nSer&aacute; actualizado si as&iacute; es demandado.', 1, '2008-11-21 13:10:17', 31, '7.0', 15),
(30, 'Profesor', 'Temario:<br />\r\n-Ayuda para el funcionamiento de los ex&aacute;menes<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/">\nhttp://hispania.virtualpol.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/</a><br />\r\n<br />\r\n- Conocimientos Generales sobre Hispania<br />\r\n', 1, '2008-11-21 13:10:17', 34, '7', 10),
(31, 'Decano', 'Temario:<br />\r\n-Ayuda para el funcionamiento de los ex&aacute;menes<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/">\nhttp://hispania.virtualpol.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/</a><br />\r\n<br />\r\n- Conocimientos generales de Hispania.', 1, '2008-11-21 13:10:17', 35, '9.0', 20),
(0, 'General', 'Editar...', 1, '2008-11-26 10:49:38', 0, '5.0', 10),
(35, 'Derecho', 'Temario de derecho:<br />\r\n- Constituci&oacute;n de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n- C&oacute;digo Penal de Hispania<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/">\nhttp://hispania.virtualpol.com/doc/codigo-penal-de-hispania/</a>', 1, '2008-11-28 11:29:30', -35, '7', 20),
(39, 'Periodista', '- Conocimientos generales de VirtualPOL<br />\r\n<br />\r\n- Reglamento del Peri&oacute;dico Nacional de POL<br />\r<a href="\nhttp://pol.virtualpol.com/doc/reglamento-del-periodico-nacional-de-pol/">\nhttp://pol.virtualpol.com/doc/reglamento-del-periodico-nacional-de-pol/</a>', 1, '2008-12-01 01:43:06', -39, '5.0', 15),
(40, 'Inspector de trabajo', '- Constituci&oacute;n<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/">\nhttp://hispania.virtualpol.com/doc/constitucion-de-hispania/</a><br />\r\n<br />\r\n- Ley del Inspector de Trabajo<br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-del-inspector-de-trabajo/">\nhttp://pol.teoriza.com/doc/ley-del-inspector-de-trabajo/</a><br />\r\n<br />\r\n- Reglamento de Empleo P&uacute;blico<br />\r<a href="\nhttp://pol.teoriza.com/doc/reglamento-de-empleo-publico/">\nhttp://pol.teoriza.com/doc/reglamento-de-empleo-publico/</a>', 1, '2008-12-01 00:00:00', 39, '8.0', 10),
(42, 'Arquitecto', '- Conocimientos sobre el Mapa.<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/mapa-de-hispania/">\nhttp://hispania.virtualpol.com/doc/mapa-de-hispania/</a><br />\r\n<br />\r\n- Conocimientos generales de Hispania.', 1, '2009-01-22 19:24:50', 40, '10', 10),
(44, 'Secretario', '- Conocimientos de jerarqu&iacute;a de cargos<br />\r<a href="\nhttp://hispania.virtualpol.com/cargos/">\nhttp://hispania.virtualpol.com/cargos/</a><br />\r\n<br />\r\n- Conocimientos generales sobre documentos<br />\r<a href="\nhttp://hispania.virtualpol.com/doc/">\nhttp://hispania.virtualpol.com/doc/</a><br />\r\n<br />\r\n- Conocimientos generales de POL', 1, '2009-02-09 12:03:28', -44, '7', 20),
(53, 'GeografÃ­a', 'Conocimientos sobre Geograf&iacute;a.', 12923, '2009-03-21 14:22:24', -53, '7.0', 10),
(57, 'Embajador', '-Conocimientos generales sobre VirtualPol y las fronteras internacionales.<br />\r\n<br />\r\n-Ley de Relaciones Internacionales<br />\r<a href="\nhttp://pol.virtualpol.com/doc/propuesta-ley-de-relaciones-internacionales/">\nhttp://pol.virtualpol.com/doc/propuesta-ley-de-relaciones-internacionales/</a>', 1, '2009-04-26 00:00:00', 42, '7.5', 10),
(61, 'Borracho de Hispania', 'Preguntas a cerca de las actitudes del borracho y las generales de Hispania.', 200003, '2009-08-30 17:08:00', -61, '8.0', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hispania_foros`
--

CREATE TABLE IF NOT EXISTS `hispania_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `hispania_foros`
--

INSERT INTO `hispania_foros` (`ID`, `url`, `title`, `descripcion`, `acceso`, `time`, `estado`, `acceso_msg`) VALUES
(1, 'general', 'General', '', 1, 10, 'eliminado', 0),
(2, 'estado', 'Estado', 'InformaciÃ³n del Gobierno de Hispania', 25, 2, 'ok', 25),
(3, 'desarrollo', 'Desarrollo', 'Foro de desarrollo tecnico de Hispania', 100, 20, 'eliminado', 100),
(4, 'politica', 'Politica', 'Foro de desarrollo de Partidos Politicos', 1, 4, 'ok', 1),
(5, 'hoja-de-ruta', 'Hoja de Ruta', 'Foro para planear todo para la nueva etapa de Hispania', 100, 21, 'eliminado', 100),
(6, 'asamblea', 'Asamblea', 'Foro de desarrollo legislativo', 100, 22, 'eliminado', 100),
(7, 'internacional', 'Internacional', 'No usar, todo el mundo puede escribir en ', 0, 9, 'ok', 0),
(8, 'empleo', 'Empleo', '', 1, 10, 'eliminado', 0),
(9, 'ciudadania', 'CiudadanÃ­a', 'Foro general para ciudadanos', 1, 1, 'ok', 1),
(10, 'justicia', 'Justicia', 'Foro para la Justicia', 70, 7, 'ok', 0),
(11, 'y-despues', 'Y despuÃ©s...?', 'Foro para planear todo para la nueva etapa de Hispania', 1, 9, 'eliminado', 0),
(12, 'parlamento', 'Parlamento', 'Debates parlamentarios, Diputados y Gobierno', 90, 3, 'ok', 75),
(13, 'notaria', 'NotarÃ­a', 'Zona para dejar constancia de hechos, no editable', 1, 8, 'ok', 1),
(14, 'economia', 'EconomÃ­a', 'Foro para tratar asuntos econÃ³micos', 1, 6, 'eliminado', 1),
(15, 'ocio', 'Ocio', 'Of-Topic, juegos,...', 0, 10, 'ok', 0),
(16, 'camara', 'CÃ¡mara', 'Consultas de los Ciudadanos hacia el Gobierno', 1, 5, 'eliminado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_cat`
--

CREATE TABLE IF NOT EXISTS `pol_cat` (
  `ID` tinyint(3) NOT NULL auto_increment,
  `url` varchar(80) NOT NULL default '',
  `nombre` varchar(80) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `num` smallint(5) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `tipo` enum('empresas','docs','cargos') NOT NULL default 'empresas',
  PRIMARY KEY  (`ID`),
  KEY `url` (`url`,`tipo`),
  KEY `tipo` (`tipo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pol_cat`
--

INSERT INTO `pol_cat` (`ID`, `url`, `nombre`, `time`, `num`, `nivel`, `tipo`) VALUES
(1, 'periodicos', 'Periodicos', '2008-10-07 00:00:00', 43, 0, 'empresas'),
(2, 'bufetes-de-abogados', 'Bufetes de abogados', '2008-10-07 00:00:00', 18, 0, 'empresas'),
(3, 'bancos', 'Bancos', '2008-10-07 00:00:00', 39, 0, 'empresas'),
(4, 'loterias-y-apuestas', 'Loterias y apuestas', '2008-10-07 00:00:00', 33, 0, 'empresas'),
(5, 'otros', 'Otros', '2008-10-08 00:00:00', 154, 0, 'empresas'),
(6, 'leyes-vigentes', 'Leyes vigentes', '0000-00-00 00:00:00', 0, 85, 'docs'),
(7, 'otros-documentos', 'Otros documentos', '0000-00-00 09:00:00', 0, 0, 'docs'),
(8, 'ayuda', 'Ayuda', '0000-00-00 00:06:00', 0, 20, 'docs'),
(9, 'leyes-derogadas', 'Leyes derogadas', '0000-00-00 00:02:00', 0, 85, 'docs'),
(10, 'leyes-propuestas', 'Leyes propuestas', '0000-00-00 00:01:00', 0, 0, 'docs'),
(11, 'sugerencias', 'Sugerencias', '0000-00-00 00:07:00', 0, 0, 'docs'),
(12, 'informacion', 'Informaci&oacute;n', '0000-00-00 00:05:00', 0, 0, 'docs'),
(13, 'historia', 'Historia', '0000-00-00 00:06:10', 0, 0, 'docs'),
(14, 'reglamento-vigente', 'Reglamento vigente', '0000-00-00 00:00:50', 0, 85, 'docs'),
(15, 'actas-del-parlamento', 'Actas del Parlamento', '0000-00-00 08:00:00', 0, 90, 'docs'),
(16, 'ssal', 'Sociedades Sin Animo de Lucro', '2008-10-07 00:00:00', 4, 0, 'empresas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_config`
--

CREATE TABLE IF NOT EXISTS `pol_config` (
  `ID` tinyint(3) NOT NULL auto_increment,
  `dato` varchar(30) NOT NULL default '',
  `valor` text NOT NULL,
  `autoload` enum('si','no') NOT NULL default 'no',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `dato` (`dato`),
  KEY `autoload` (`autoload`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pol_config`
--

INSERT INTO `pol_config` (`ID`, `dato`, `valor`, `autoload`) VALUES
(1, 'tiempo_turista', '1', 'si'),
(2, 'info_censo', '73', 'si'),
(3, 'info_partidos', '8', 'si'),
(4, 'info_documentos', '394', 'si'),
(5, 'elecciones_estado', 'normal', 'si'),
(7, 'num_escanos', '5', 'si'),
(8, 'elecciones_inicio', '2009-10-16 20:00:00', 'si'),
(9, 'elecciones_duracion', '172800', 'si'),
(10, 'elecciones_frecuencia', '1036800', 'si'),
(11, 'elecciones_antiguedad', '86400', 'si'),
(12, 'pols_frase', '<a href="http://code.google.com/p/virtualpol/wiki/EmpezarDesarrollo">Como empezar a desarrollar</a>', 'si'),
(23, 'palabras', '200138:osvaldo-cangas-padilla.artelista.com/:Exposicion;11893:code.google.com/p/virtualpol/issues/list:Issues;11932:pol.virtualpol.com/partidos/pr:AFILIATE', 'si'),
(42, 'impuestos_empresa', '5', 'no'),
(41, 'pais_des', 'RepÃºblica DemocrÃ¡tica', 'si'),
(13, 'pols_afiliacion', '450', 'no'),
(14, 'pols_fraseedit', '1', 'si'),
(15, 'info_consultas', '3', 'si'),
(16, 'pols_empresa', '20', 'si'),
(17, 'pols_cuentas', '120', 'si'),
(18, 'pols_partido', '20', 'si'),
(19, 'defcon', '5', 'si'),
(20, 'pols_inem', '0', 'no'),
(21, 'online_ref', '18000', 'no'),
(22, 'factor_propiedad', '1', 'no'),
(24, 'palabras_num', '5', 'no'),
(26, 'elecciones', '_pres', 'si'),
(27, 'examen_repe', '86400', 'no'),
(28, 'pols_solar', '1', 'no'),
(29, 'pols_mensajetodos', '1000', 'no'),
(30, 'pols_examen', '10', 'no'),
(31, 'pols_mensajeurgente', '5', 'no'),
(33, 'frontera', 'abierta', 'si'),
(34, 'palabra_gob', 'Nueva revisiÃ³n de los sueldos, comprueba el tuyo!:pol.virtualpol.com/foro/gobierno/revision-de-sueldos-publicos/', 'si'),
(35, 'examenes_exp', '7776000', 'no'),
(40, 'bg', '08_basicas.gif', 'si'),
(38, 'arancel_entrada', '', 'no'),
(39, 'arancel_salida', '10', 'no'),
(36, 'impuestos_minimo', '500', 'no'),
(37, 'impuestos', '0.14', 'no');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_estudios`
--

CREATE TABLE IF NOT EXISTS `pol_estudios` (
  `ID` tinyint(3) unsigned NOT NULL auto_increment,
  `nombre` varchar(30) NOT NULL default '',
  `tiempo` mediumint(9) NOT NULL default '86400',
  `nivel` tinyint(3) NOT NULL default '1',
  `num_cargo` smallint(5) NOT NULL default '1',
  `asigna` smallint(5) NOT NULL default '7',
  `salario` smallint(5) NOT NULL default '0',
  `ico` enum('true','false','') NOT NULL default 'false',
  PRIMARY KEY  (`ID`),
  KEY `nivel` (`nivel`),
  KEY `nombre` (`nombre`),
  KEY `asigna` (`asigna`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pol_estudios`
--

INSERT INTO `pol_estudios` (`ID`, `nombre`, `tiempo`, `nivel`, `num_cargo`, `asigna`, `salario`, `ico`) VALUES
(6, 'Diputado', 600, 90, 15, 22, 0, 'true'),
(7, 'Presidente', 70000, 100, 1, 0, 50, 'true'),
(8, 'Juez de Paz', 600000, 80, 3, 9, 135, 'true'),
(9, 'Juez Supremo', 300000, 85, 1, 7, 135, 'true'),
(11, 'Fiscal', 0, 60, 2, 7, 65, 'true'),
(12, 'Policia', 432000, 30, 6, 13, 105, 'true'),
(13, 'Comisario de Policia', 0, 50, 2, 7, 110, 'true'),
(16, 'Ministro', 60, 95, 6, 7, 15, 'true'),
(40, 'Arquitecto', 86400, 20, 1, 7, 25, 'true'),
(19, 'Vicepresidente', 600000, 98, 1, 7, 50, 'true'),
(20, 'Defensor del Pueblo', 60, 50, 1, 7, 40, 'true'),
(21, 'Supervisor del Censo', 60, 70, 2, 7, 85, 'true'),
(22, 'Presidente Parlamento', 60, 97, 1, 7, 0, 'true'),
(41, 'Consultor', 86400, 75, 1, 7, 35, 'true'),
(25, 'Notario', 181020, 20, 3, 7, 35, 'true'),
(26, 'Jefe de Prensa', 6000, 75, 2, 7, 10, 'true'),
(27, 'Secretario de Estado', 60, 50, 0, 7, 90, 'true'),
(28, 'Guia Turistico', 86400, 20, 0, 7, 10, 'true'),
(34, 'Profesor', 1, 10, 10, 35, 55, 'true'),
(35, 'Decano', 1, 15, 9, 7, 70, 'true'),
(36, 'Secretario', 61, 20, 0, 7, 40, 'true'),
(37, 'Funcionario', 61, 25, 10, 7, 25, 'true'),
(42, 'Embajador', 86400, 92, 1, 7, 15, 'true');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_examenes`
--

CREATE TABLE IF NOT EXISTS `pol_examenes` (
  `ID` smallint(5) NOT NULL auto_increment,
  `titulo` varchar(100) NOT NULL default '',
  `descripcion` text NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cargo_ID` smallint(5) NOT NULL default '0',
  `nota` varchar(5) NOT NULL default '5.0',
  `num_preguntas` smallint(5) NOT NULL default '10',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `titulo` (`titulo`),
  KEY `nota` (`nota`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num' AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pol_examenes`
--

INSERT INTO `pol_examenes` (`ID`, `titulo`, `descripcion`, `user_ID`, `time`, `cargo_ID`, `nota`, `num_preguntas`) VALUES
(10, 'Fiscal', 'Temario del examen a Fiscal:<br />\r\n- Constituci&oacute;n de POL<br />\r<a href="\nhttp://pol.teoriza.com/doc/constitucion-de-pol/">\nhttp://pol.teoriza.com/doc/constitucion-de-pol/</a><br />\r\n<br />\r\n- Ley del poder Judicial <br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-poder-judicial/">\nhttp://pol.teoriza.com/doc/ley-poder-judicial/</a><br />\r\n<br />\r\n- C&oacute;digo Penal<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-codigo-penal/">\nhttp://pol.teoriza.com/doc/propuesta-codigo-penal/</a><br />\r\n<br />\r\n- Ley de Polic&iacute;a<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-ley-de-policia/">\nhttp://pol.teoriza.com/doc/propuesta-ley-de-policia/</a><br />\r\n<br />\r\n- Conocimientos generales sobre POL<br />\r\n<br />\r\nRevisado dia 13/8', 1, '2008-11-21 13:10:17', 11, '7.5', 20),
(9, 'Juez Supremo', '- Constituci&oacute;n de POL<br />\r<a href="\nhttp://pol.teoriza.com/doc/constitucion-de-pol/">\nhttp://pol.teoriza.com/doc/constitucion-de-pol/</a><br />\r\n<br />\r\n- Ley del Poder Judicial <br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-poder-judicial/">\nhttp://pol.teoriza.com/doc/ley-poder-judicial/</a><br />\r\n<br />\r\n- Ley de Polic&iacute;a<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-ley-de-policia/">\nhttp://pol.teoriza.com/doc/propuesta-ley-de-policia/</a><br />\r\n<br />\r\n- C&oacute;digo Penal<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-codigo-penal/">\nhttp://pol.teoriza.com/doc/propuesta-codigo-penal/</a><br />\r\n<br />\r\n- Ley del Defensor del Pueblo<br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-del-defensor-del-pueblo/">\nhttp://pol.teoriza.com/doc/ley-del-defensor-del-pueblo/</a><br />\r\n<br />\r\n- Ley Electoral y de la Ciudadan&iacute;a<br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-electoral-y-de-la-ciudadania/">\nhttp://pol.teoriza.com/doc/ley-electoral-y-de-la-ciudadania/</a><br />\r\n<br />\r\n- Nueva Ley del Parlamento<br />\r<a href="\nhttp://pol.teoriza.com/doc/nueva--ley-del-parlamento/">\nhttp://pol.teoriza.com/doc/nueva--ley-del-parlamento/</a><br />\r\n<br />\r\n- Ley de Pr&eacute;stamos y Ahorros<br />\r<a href="\nhttp://pol.virtualpol.com/doc/propuesta-ley-de-banca/">\nhttp://pol.virtualpol.com/doc/propuesta-ley-de-banca/</a><br />\r\n<br />\r\n- blog del desarrollador:<a href=" http://desarrollo.virtualpol.com/"> http://desarrollo.virtualpol.com/</a> Las preguntas estan adaptadas a los cambios aqu&iacute; publicados, por lo que en caso de duda lo dictado en el blog del desarrollador prevalece sobre las leyes.<br />\r\n', 1, '2008-11-21 13:10:17', 9, '9.0', 30),
(41, 'Derecho Administrativo', 'Todas las leyes y reglamentos de POL. Es un examen realmente dif&iacute;cil.', 14587, '2008-12-19 11:43:14', -41, '0.0', 10),
(8, 'Juez de Paz', '- Constituci&oacute;n de POL<br />\r<a href="\nhttp://pol.teoriza.com/doc/constitucion-de-pol/">\nhttp://pol.teoriza.com/doc/constitucion-de-pol/</a><br />\r\n<br />\r\n- C&oacute;digo Penal<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-codigo-penal/">\nhttp://pol.teoriza.com/doc/propuesta-codigo-penal/</a><br />\r\n<br />\r\n- Ley del poder Judicial<br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-poder-judicial/">\nhttp://pol.teoriza.com/doc/ley-poder-judicial/</a><br />\r\n<br />\r\n- Ley de Polic&iacute;a<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-ley-de-policia/">\nhttp://pol.teoriza.com/doc/propuesta-ley-de-policia/</a><br />\r\n<br />\r\n- Nueva Ley del Parlamento<br />\r<a href="\nhttp://pol.teoriza.com/doc/nueva--ley-del-parlamento/">\nhttp://pol.teoriza.com/doc/nueva--ley-del-parlamento/</a><br />\r\n<br />\r\n- Ley electoral y de la Ciudadan&iacute;a<br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-electoral-y-de-la-ciudadania/">\nhttp://pol.teoriza.com/doc/ley-electoral-y-de-la-ciudadania/</a><br />\r\n<br />\r\n- Ley del defensor del pueblo<br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-del-defensor-del-pueblo/">\nhttp://pol.teoriza.com/doc/ley-del-defensor-del-pueblo/</a><br />\r\n<br />\r\n- Decreto-ley de la Banca<br />\r<a href="\nhttp://pol.teoriza.com/doc/reglamento-de-banca/">\nhttp://pol.teoriza.com/doc/reglamento-de-banca/</a>', 1, '2008-11-21 13:10:17', 8, '7', 25),
(6, 'Diputado', 'Temario:<br />\r\n-Constituci&oacute;n<br />\r<a href="\nhttp://pol.virtualpol.com/doc/constitucion-de-pol/">\nhttp://pol.virtualpol.com/doc/constitucion-de-pol/</a><br />\r\n<br />\r\n-Ley de Compatibilidades:<br />\r<a href="\nhttp://pol.virtualpol.com/doc/ley-de-compatibilidades-propuesta-alternativa/">\nhttp://pol.virtualpol.com/doc/ley-de-compatibilidades-propuesta-alternativa/</a><br />\r\n<br />\r\n-Ley electoral y de la ciudadania:<br />\r<a href="\nhttp://pol.virtualpol.com/doc/ley-electoral-y-de-la-ciudadania/">\nhttp://pol.virtualpol.com/doc/ley-electoral-y-de-la-ciudadania/</a><br />\r\n<br />\r\n-Ley del parlamento<br />\r<a href="\nhttp://pol.virtualpol.com/doc/ley-del-parlamento-x23/">\nhttp://pol.virtualpol.com/doc/ley-del-parlamento-x23/</a><br />\r\n<br />\r\nRevisado dia 27/08', 1, '2008-11-21 13:10:17', 6, '7', 18),
(11, 'Policia', 'Para el examen de polic&iacute;a entrar&aacute;n las siguientes materias<br />\r\n<br />\r\n- Constituci&oacute;n de POL<br />\r\n<a href="http://pol.teoriza.com/doc/constitucion-de-pol/">http://pol.teoriza.com/doc/constitucion-de-pol/</a><br />\r\n<br />\r\n- Ley de Polic&iacute;a<br />\r\n<a href="http://pol.teoriza.com/doc/propuesta-ley-de-policia/">http://pol.teoriza.com/doc/propuesta-ley-de-policia/</a><br />\r\n<br />\r\n- Reglamento de Criterios Policiales<br />\r\n<a href="http://pol.teoriza.com/doc/reglamento-de-criterios-policiales/">http://pol.teoriza.com/doc/reglamento-de-criterios-policiales/</a><br />\r\n<br />\r\n- Reglamento Interno del Interior<br />\r\n<a href="http://pol.teoriza.com/doc/reglamento-interno-de-interior/">http://pol.teoriza.com/doc/reglamento-interno-de-interior/</a><br />\r\n<br />\r\n- C&oacute;digo Penal<br />\r\n<a href="http://pol.teoriza.com/doc/propuesta-codigo-penal/">http://pol.teoriza.com/doc/propuesta-codigo-penal/</a>', 1, '2008-11-21 13:10:17', 12, '7.5', 20),
(12, 'Comisario de Policia', '- Conocimientos generales sobre POL<br />\r\n<br />\r\n- Constituci&oacute;n de POL<br />\r\n<a href="http://pol.teoriza.com/doc/constitucion-de-pol/">http://pol.teoriza.com/doc/constitucion-de-pol/</a><br />\r\n<br />\r\n- Ley de Polic&iacute;a<br />\r\n<a href="http://pol.teoriza.com/doc/propuesta-ley-de-policia/">http://pol.teoriza.com/doc/propuesta-ley-de-policia/</a><br />\r\n<br />\r\n- C&oacute;digo Penal<br />\r\n<a href="http://pol.teoriza.com/doc/propuesta-codigo-penal/">http://pol.teoriza.com/doc/propuesta-codigo-penal/</a><br />\r\n<br />\r\n- Ley del Defensor del Pueblo<br />\r\n<a href="http://pol.teoriza.com/doc/ley-del-defensor-del-pueblo/">http://pol.teoriza.com/doc/ley-del-defensor-del-pueblo/</a><br />\r\n<br />\r\n- Ley Electoral y de la Ciudadan&iacute;a.<br />\r\n<a href="http://pol.teoriza.com/doc/ley-electoral-y-de-la-ciudadania/">http://pol.teoriza.com/doc/ley-electoral-y-de-la-ciudadania/</a><br />\r\n<br />\r\n- Reglamento Interno del Interior<br />\r\n<a href="http://pol.teoriza.com/doc/reglamento-interno-de-interior/">http://pol.teoriza.com/doc/reglamento-interno-de-interior/</a><br />\r\n<br />\r\n- Reglamento de Criterios Policiales<br />\r\n<a href="http://pol.teoriza.com/doc/reglamento-de-criterios-policiales/">http://pol.teoriza.com/doc/reglamento-de-criterios-policiales/</a>', 1, '2008-11-21 13:10:17', 13, '8.5', 30),
(14, 'Ministro', 'Temario:<br />\r\n-Constituci&oacute;n<br />\r<a href="\nhttp://pol.teoriza.com/doc/constitucion-de-pol/">\nhttp://pol.teoriza.com/doc/constitucion-de-pol/</a><br />\r\n<br />\r\n-Conocimientos generales<br />\r\n<br />\r\nRevisado el dia 21/08', 1, '2008-11-21 13:10:17', 16, '7.5', 15),
(16, 'Vicepresidente', 'Temario:<br />\r\n-Constituci&oacute;n<br />\r<a href="\nhttp://pol.teoriza.com/doc/constitucion-de-pol/">\nhttp://pol.teoriza.com/doc/constitucion-de-pol/</a><br />\r\n- Conocimientos generales sobre Pol<br />\r\n<br />\r\nRevisado el dia 21/08', 1, '2008-11-21 13:10:17', 19, '8.0', 15),
(17, 'Defensor del Pueblo', 'Temario:<br />\r\n-Ley del Defensor del Pueblo<br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-del-defensor-del-pueblo/">\nhttp://pol.teoriza.com/doc/ley-del-defensor-del-pueblo/</a><br />\r\n<br />\r\n-Ley del poder Judicial<br />\r<a href="\nhttp://pol.teoriza.com/doc/ley-poder-judicial/">\nhttp://pol.teoriza.com/doc/ley-poder-judicial/</a><br />\r\n<br />\r\n-C&oacute;digo Penal<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-codigo-penal/">\nhttp://pol.teoriza.com/doc/propuesta-codigo-penal/</a><br />\r\n<br />\r\nRevisada el dia 16/08<br />\r\n', 1, '2008-11-21 13:10:17', 20, '8.0', 20),
(18, 'Supervisor del Censo', '- Ley del supervisor del censo:<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-modificacion-ley-del-supervisor-del-censo/">\nhttp://pol.teoriza.com/doc/propuesta-modificacion-ley-del-supervisor-del-censo/</a><br />\r\n- Conocimientos generales de Pol<br />\r\n<br />\r\nRevisado dia 16/08', 1, '2008-11-21 13:10:17', 21, '9.0', 15),
(19, 'Presidente Parlamento', 'Para el examen de Presidente del Parlamento entrar&aacute;n las siguientes materias:<br />\r\n<br />\r\n- Constituci&oacute;n de POL<br />\r<a href="\nhttp://pol.virtualpol.com/doc/constitucion-de-pol/">\nhttp://pol.virtualpol.com/doc/constitucion-de-pol/</a><br />\r\n<br />\r\n- Ley del Parlamento<br />\r<a href="\nhttp://pol.virtualpol.com/doc/propuesta-ley-del-parlamento/">\nhttp://pol.virtualpol.com/doc/propuesta-ley-del-parlamento/</a><br />\r\n<br />\r\n- Ley Electoral y de la Ciudadan&iacute;a<br />\r<a href="\nhttp://pol.virtualpol.com/doc/ley-electoral-y-de-la-ciudadania/">\nhttp://pol.virtualpol.com/doc/ley-electoral-y-de-la-ciudadania/</a><br />\r\n<br />\r\n- Ley del Defensor del Pueblo<br />\r<a href="\nhttp://pol.virtualpol.com/doc/ley-del-defensor-del-pueblo/">\nhttp://pol.virtualpol.com/doc/ley-del-defensor-del-pueblo/</a><br />\r\n<br />\r\n- Conocimientos generales de POL', 1, '2008-11-21 13:10:17', 22, '8.5', 20),
(45, 'Consultor', '- Conocimientos sobre el sistema de consultas.<br />\r\n- Correcta escritura.<br />\r\n- Capacidad para confeccionar consultas neutrales y correctas.<br />\r\n<br />\r\n- Decreto-Ley sobre las Consultas<br />\r<a href="\nhttp://pol.teoriza.com/doc/decreto-ley-sobre-las-consultas/">\nhttp://pol.teoriza.com/doc/decreto-ley-sobre-las-consultas/</a><br />\r\n<br />\r\nRevisado dia 12/08', 1, '2009-02-09 12:37:21', 41, '6.0', 15),
(46, 'Oposiciones Decano [No util]', '- Ley de Educaci&oacute;n<a href=" http://pol.teoriza.com/doc/propuesta-ley-de-educacion/"> http://pol.teoriza.com/doc/propuesta-ley-de-educacion/</a><br />\r\n- Ayuda para el funcionamiento de los ex&aacute;menes<br />\r<a href="\nhttp://pol.teoriza.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/">\nhttp://pol.teoriza.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/</a><br />\r\n- Conocimientos generales de POL.<br />\r\n- Conocimientos del panel de configuraci&oacute;n de ex&aacute;menes.', 9279, '2009-02-12 12:53:27', -46, '0.0', 25),
(21, 'Notario', 'Todas las leyes vigentes en POL<br />\r\n<br />\r\nEn especial:<a href=" http://pol.virtualpol.com/doc/reglamento-de-la-notaria/"> http://pol.virtualpol.com/doc/reglamento-de-la-notaria/</a><br />\r\n<br />\r\nRevisado dia 13/8', 1, '2008-11-21 13:10:17', 25, '9.5', 15),
(22, 'Jefe de Prensa', '-Reglamento del peri&oacute;dico nacional<br />\r\n<a href="http://pol.teoriza.com/doc/reglamento-del-periodico-nacional-de-pol/">http://pol.teoriza.com/doc/reglamento-del-periodico-nacional-de-pol/</a><br />\r\n<br />\r\n-conocimientos generales de POL', 1, '2008-11-21 13:10:17', 26, '7.0', 15),
(23, 'Secretario de Estado', '-Conocimientos Generales de POL<br />\r\n<br />\r\nRevisado dia 11/08', 1, '2008-11-21 13:10:17', 27, '6.0', 10),
(24, 'GuÃ­a Turistico', 'Conocimientos Generales de POL<br />\r\n<br />\r\nRevisado dia 13/08', 1, '2008-11-21 13:10:17', 28, '6.0', 15),
(25, 'Funcionario', '- Conocimientos de jerarqu&iacute;a de cargos<br />\r\n<a href="http://pol.teoriza.com/cargos/">http://pol.teoriza.com/cargos/</a><br />\r\n<br />\r\n- Conocimientos generales sobre documentos<br />\r\n<a href="http://pol.teoriza.com/doc/">http://pol.teoriza.com/doc/</a><br />\r\n<br />\r\n- Conocimientos b&aacute;sicos sobre juicios<br />\r\n<a href="http://pol.teoriza.com/foro/justicia/">http://pol.teoriza.com/foro/justicia/</a><br />\r\n<br />\r\n- Conocimientos generales de POL', 1, '2008-11-21 13:10:17', 37, '7.0', 15),
(27, 'Agente de Empleo', 'Conocimientos generales sobre POL<br />\r\n<br />\r\n-Reglamento de Empleo P&uacute;blico<br />\r<a href="\nhttp://pol.teoriza.com/doc/reglamento-de-empleo-publico/">\nhttp://pol.teoriza.com/doc/reglamento-de-empleo-publico/</a>', 1, '2008-11-21 13:10:17', 31, '0.0', 15),
(30, 'Profesor', 'Temario:<br />\r\n-Ayuda para el funcionamiento de los ex&aacute;menes<br />\r<a href="\nhttp://pol.teoriza.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/">\nhttp://pol.teoriza.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/</a><br />\r\n<br />\r\n- Ley de Educaci&oacute;n<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-ley-de-educacion/">\nhttp://pol.teoriza.com/doc/propuesta-ley-de-educacion/</a><br />\r\n<br />\r\n- Conocimientos Generales sobre POL<br />\r\n<br />\r\nRevisado dia 12/08<br />\r\n', 1, '2008-11-21 13:10:17', 34, '7.5', 20),
(31, 'Decano', 'Temario:<br />\r\n-Ayuda para el funcionamiento de los ex&aacute;menes<br />\r<a href="\nhttp://pol.teoriza.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/">\nhttp://pol.teoriza.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/</a><br />\r\n<br />\r\n- Ley de Educaci&oacute;n<br />\r<a href="\nhttp://pol.teoriza.com/doc/propuesta-ley-de-educacion/">\nhttp://pol.teoriza.com/doc/propuesta-ley-de-educacion/</a><br />\r\n<br />\r\n- Conocimientos generales de POL.<br />\r\n- Conocimientos del panel de configuraci&oacute;n de ex&aacute;menes.<br />\r\n<br />\r\nRevisado dia 12/08', 1, '2008-11-21 13:10:17', 35, '8.0', 18),
(0, 'General', 'Editar...', 1, '2008-11-26 10:49:38', 0, '5.0', 10),
(34, 'Espia (broma)', 'Examen de broma :)', 1, '2008-11-27 19:08:34', -34, '1.0', 12),
(35, 'Derecho', 'Temario de derecho:<br />\r\nTodas las leyes y reglamentos de POL<br />\r<a href="\nhttp://pol.teoriza.com/doc/">\nhttp://pol.teoriza.com/doc/</a><br />\r\n<br />\r\nExamen revisado dia 6 de setiembre', 1, '2008-11-28 11:29:30', -35, '7.5', 20),
(36, 'Escobas (broma)', 'Examen de broma...', 11949, '2008-11-28 20:54:32', -36, '1.0', 10),
(37, 'Ventilador (broma)', '<a href="http://es.wikipedia.org/wiki/Ventilador">http://es.wikipedia.org/wiki/Ventilador</a>', 14587, '2008-11-28 20:55:42', -37, '1.0', 15),
(38, 'Desarrollador de POL [No util]', '- Conocimientos de programaci&oacute;n web.<br />\r\n- Conocimientos de la estructura del sistema de POL.', 1, '2008-11-29 12:58:08', -38, '0.0', 10),
(39, 'Periodista [No util]', '- Conocimientos generales de POL<br />\r\n<br />\r\n- Reglamento del Peri&oacute;dico Nacional<br />\r<a href="\nhttp://pol.teoriza.com/doc/reglamento-del-periodico-nacional-de-pol/">\nhttp://pol.teoriza.com/doc/reglamento-del-periodico-nacional-de-pol/</a>', 1, '2008-12-01 01:43:06', -39, '0.0', 15),
(40, 'Inspector de trabajo', 'Vacio', 1, '2008-12-01 00:00:00', 39, '0.0', 10),
(42, 'Arquitecto', 'Documentaci&oacute;n aun no disponible. Se est&aacute; redactando y en breve se enlazar&aacute;. De momento es dif&iacute;cil el examen dado que casi nadie conoce los entresijos del mapa. Disculpad las molestias.<br />\r\n<br />\r\n- Conocimientos extensos sobre el sistema del MAPA.<br />\r\n<br />\r\nRevisado dia 19/08', 1, '2009-01-22 19:24:50', 40, '7.5', 15),
(43, 'Historia de VirtualPol [No uti', 'Comocimientos generales sobre la historia de VirtualPol', 9279, '2009-01-31 03:59:10', -43, '0.0', 10),
(44, 'Secretario', '- Conocimientos de jerarqu&iacute;a de cargos<br />\r<a href="\nhttp://pol.teoriza.com/cargos/">\nhttp://pol.teoriza.com/cargos/</a><br />\r\n<br />\r\n- Conocimientos generales sobre documentos<br />\r<a href="\nhttp://pol.teoriza.com/doc/">\nhttp://pol.teoriza.com/doc/</a><br />\r\n<br />\r\n- Conocimientos generales de POL', 1, '2009-02-09 12:03:28', -44, '0.0', 20),
(47, 'Rey de POL (broma)', 'Examen de broma xD<br />\r\n-Conocimientos sobre c&oacute;mo ser un buen Rey de POL xD<br />\r\nEs un examen algo jodido xD', 12923, '2009-03-03 15:54:13', -47, '1.0', 10),
(48, 'InformÃ¡tica [No util]', ' - Conocimientos generales sobre POL<br />\r\n - Conocimientos generales sobre inform&aacute;tica', 12923, '2009-03-06 18:32:21', -48, '0.0', 7),
(49, 'Troll (broma)', 'Examen de broma xDDDD<br />\r\n- Conocimientos generales sobre chats y foros frecuentados por trolls ', 12923, '2009-03-10 22:38:21', -49, '1.0', 10),
(50, 'Emperador de POL (broma)', 'Examen de broma<br />\r\n- Conocimientos sobre c&oacute;mo ser un buen emperador xD', 12923, '2009-03-14 17:19:35', -50, '1.0', 10),
(51, 'Collejas (broma)', 'Examen de broma realizado por el recibecollejas oficial de POL, sEr gracias a la idea de bandida xDD', 12923, '2009-03-21 13:42:50', -51, '1.0', 15),
(52, 'Follonero (broma)', 'Saber discutir y ser m&aacute;s que un troll xD', 12923, '2009-03-21 14:06:47', -52, '1.0', 10),
(53, 'GeografÃ­a [No util]', 'Conocimientos sobre geograf&iacute;a', 12923, '2009-03-21 14:22:24', -53, '0.0', 15),
(54, 'Villano de POL (broma)', '- C&oacute;mics de Marvel<br />\r\n<br />\r\n- Austin Powers<br />\r\n<br />\r\n- El coche fant&aacute;stico', 12923, '2009-03-21 19:41:11', -54, '1.0', 10),
(55, 'Borrado', 'borrado', 12923, '2009-03-29 19:45:25', -55, '0.0', 10),
(56, 'Counter Strike [No util]', 'Conocimientos generales sobre Counter Strike', 12923, '2009-04-01 19:32:41', -56, '0.0', 20),
(57, 'Embajador', '-Conocimientos generales sobre VirtualPOL<br />\r\n-Ley de Relaciones Internacionales<br />\r<a href="\nhttp://pol.virtualpol.com/doc/propuesta-ley-de-relaciones-internacionales/">\nhttp://pol.virtualpol.com/doc/propuesta-ley-de-relaciones-internacionales/</a><br />\r\n<br />\r\nRevisado parcialmente en 11/08', 1, '2009-04-26 00:00:00', 42, '7.5', 15),
(58, 'FÃºtbol EspaÃ±ol [No util]', '- Conocimientos sobre el f&uacute;tbol espa&ntilde;ol', 12111, '2009-07-13 19:06:01', -58, '0.0', 20),
(59, 'FilosofÃ­a [No util]', '- Conocimientos generales sobre filosof&iacute;a.<br />\r\n- Conocimientos generales sobre Pol.', 19947, '2009-07-20 22:46:01', -59, '0.0', 15),
(60, 'Tutor de BOT', 'Temario:<br />\r\n-Ley de bots<br />\r<a href="\nhttp://pol.virtualpol.com/doc/modificacion-de-la-ley-del-sc/">\nhttp://pol.virtualpol.com/doc/modificacion-de-la-ley-del-sc/</a><br />\r\n<br />\r\n-Reglamento de bots<br />\r<a href="\nhttp://pol.virtualpol.com/doc/reglamento-de-bots/">\nhttp://pol.virtualpol.com/doc/reglamento-de-bots/</a>', 14587, '2009-08-20 13:30:14', -60, '9.5', 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pol_foros`
--

CREATE TABLE IF NOT EXISTS `pol_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pol_foros`
--

INSERT INTO `pol_foros` (`ID`, `url`, `title`, `descripcion`, `acceso`, `time`, `estado`, `acceso_msg`) VALUES
(1, 'general', 'General', 'Foro principal para Ciudadanos', 1, 1, 'ok', 0),
(2, 'parlamento', 'Parlamento', 'Debates parlamentarios, Diputados y Gobierno', 90, 5, 'ok', 50),
(3, 'gobierno', 'Gobierno', 'Zona de desarrollo del Gobierno de POL', 1, 6, 'ok', 1),
(4, 'justicia', 'Justicia', 'AdministraciÃ³n Judicial', 50, 8, 'ok', 0),
(5, 'notaria', 'Notar&iacute;a', 'Zona para dejar constancia de hechos, no editable', 1, 9, 'ok', 1),
(6, 'politica', 'Pol&iacute;tica', 'Zona para el Poder PolÃ­tico', 1, 2, 'ok', 1),
(7, 'economia', 'Econom&iacute;a', 'Zona para el Poder EconÃ³mico', 1, 3, 'ok', 0),
(8, 'mercado', 'Mercado', 'Zona de compra-venta, ofertas y demandas', 1, 4, 'ok', 0),
(9, 'camara-ciudadanos', 'Camara', 'Consultas de los Ciudadanos hacia el Gobierno', 1, 10, 'ok', 50),
(10, 'juegos', 'Juegos', 'Lugar  de reunion de los jugadores de POL', 0, 12, 'ok', 0),
(11, 'test', 'Test', '', 100, 10, 'eliminado', 100),
(12, 'organismos', 'Organismos', 'Zona de desarrollo para Organismos del Estado', 2, 7, 'ok', 1),
(13, 'registro-logs', 'Registro Logs', 'Zona para el registro de Logs ', 1, 11, 'ok', 50),
(14, 'guiris', 'Guiris', 'Foro de extranjeros', 0, 10, 'ok', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_cat`
--

CREATE TABLE IF NOT EXISTS `vulcan_cat` (
  `ID` tinyint(3) NOT NULL auto_increment,
  `url` varchar(80) NOT NULL default '',
  `nombre` varchar(80) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `num` smallint(5) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `tipo` enum('empresas','docs','cargos') NOT NULL default 'empresas',
  PRIMARY KEY  (`ID`),
  KEY `url` (`url`,`tipo`),
  KEY `tipo` (`tipo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `vulcan_cat`
--

INSERT INTO `vulcan_cat` (`ID`, `url`, `nombre`, `time`, `num`, `nivel`, `tipo`) VALUES
(1, 'periodicos', 'Periodicos', '2008-10-07 00:00:00', 42, 0, 'empresas'),
(2, 'bufetes-de-abogados', 'Bufetes de abogados', '2008-10-07 00:00:00', 22, 0, 'empresas'),
(3, 'bancos', 'Bancos', '2008-10-07 00:00:00', 43, 0, 'empresas'),
(4, 'loterias-y-apuestas', 'Loterias y apuestas', '2008-10-07 00:00:00', 33, 0, 'empresas'),
(5, 'otros', 'Otros', '2008-10-08 00:00:00', 149, 0, 'empresas'),
(6, 'leyes-vigentes', 'Leyes vigentes', '0000-00-00 00:00:00', 0, 85, 'docs'),
(7, 'otros-documentos', 'Otros documentos', '0000-00-00 09:00:00', 0, 0, 'docs'),
(8, 'ayuda', 'Ayuda', '0000-00-00 00:06:00', 0, 20, 'docs'),
(9, 'leyes-derogadas', 'Leyes derogadas', '0000-00-00 00:02:00', 0, 85, 'docs'),
(10, 'leyes-propuestas', 'Leyes propuestas', '0000-00-00 00:01:00', 0, 0, 'docs'),
(11, 'sugerencias', 'Sugerencias', '0000-00-00 00:07:00', 0, 0, 'docs'),
(12, 'informacion', 'Informaci&oacute;n', '0000-00-00 00:05:00', 0, 0, 'docs'),
(13, 'historia', 'Historia', '0000-00-00 00:06:10', 0, 0, 'docs'),
(14, 'reglamento-vigente', 'Reglamento vigente', '0000-00-00 00:00:50', 0, 85, 'docs');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_config`
--

CREATE TABLE IF NOT EXISTS `vulcan_config` (
  `ID` tinyint(3) NOT NULL auto_increment,
  `dato` varchar(30) NOT NULL default '',
  `valor` text NOT NULL,
  `autoload` enum('si','no') NOT NULL default 'no',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `dato` (`dato`),
  KEY `autoload` (`autoload`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `vulcan_config`
--

INSERT INTO `vulcan_config` (`ID`, `dato`, `valor`, `autoload`) VALUES
(1, 'tiempo_turista', '0', 'si'),
(2, 'info_censo', '16', 'si'),
(3, 'info_partidos', '6', 'si'),
(4, 'info_documentos', '66', 'si'),
(5, 'elecciones_estado', 'normal', 'si'),
(7, 'num_escanos', '3', 'si'),
(8, 'elecciones_inicio', '2009-10-16 20:00:00', 'si'),
(9, 'elecciones_duracion', '172800', 'si'),
(10, 'elecciones_frecuencia', '1036800', 'si'),
(11, 'elecciones_antiguedad', '86400', 'si'),
(12, 'pols_frase', 'Cucuta', 'si'),
(38, 'arancel_entrada', '', 'no'),
(23, 'palabras', '200007::Cucuta', 'si'),
(13, 'pols_afiliacion', '100', 'no'),
(14, 'pols_fraseedit', '200007', 'si'),
(15, 'info_consultas', '0', 'si'),
(16, 'pols_empresa', '50', 'si'),
(17, 'pols_cuentas', '50', 'si'),
(18, 'pols_partido', '50', 'si'),
(19, 'defcon', '5', 'si'),
(20, 'pols_inem', '6', 'no'),
(21, 'online_ref', '7200', 'no'),
(22, 'factor_propiedad', '0', 'no'),
(24, 'palabras_num', '5', 'no'),
(26, 'elecciones', '_pres', 'si'),
(27, 'examen_repe', '86400', 'no'),
(28, 'pols_solar', '1', 'no'),
(29, 'pols_mensajetodos', '1000', 'no'),
(30, 'pols_examen', '3', 'no'),
(31, 'pols_mensajeurgente', '5', 'no'),
(32, 'frontera', 'abierta', 'si'),
(34, 'palabra_gob', 'Leyes Importantes en Referendo.. VOTA !!:vulcan.virtualpol.com/referendum/', 'si'),
(35, 'examenes_exp', '7776000', 'no'),
(39, 'arancel_salida', '10', 'no'),
(42, 'impuestos_empresa', '5', 'no'),
(36, 'impuestos_minimo', '10', 'no'),
(37, 'impuestos', '0.10', 'no'),
(40, 'bg', '3t6.gif', 'si'),
(41, 'pais_des', 'RepÃºblica Latinoamericana DemocrÃ¡tica', 'si');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_estudios`
--

CREATE TABLE IF NOT EXISTS `vulcan_estudios` (
  `ID` tinyint(3) unsigned NOT NULL auto_increment,
  `nombre` varchar(30) NOT NULL default '',
  `tiempo` mediumint(9) NOT NULL default '86400',
  `nivel` tinyint(3) NOT NULL default '1',
  `num_cargo` smallint(5) NOT NULL default '1',
  `asigna` smallint(5) NOT NULL default '7',
  `salario` smallint(5) NOT NULL default '0',
  `ico` enum('true','false','') NOT NULL default 'false',
  PRIMARY KEY  (`ID`),
  KEY `nivel` (`nivel`),
  KEY `nombre` (`nombre`),
  KEY `asigna` (`asigna`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `vulcan_estudios`
--

INSERT INTO `vulcan_estudios` (`ID`, `nombre`, `tiempo`, `nivel`, `num_cargo`, `asigna`, `salario`, `ico`) VALUES
(6, 'Diputado', 600, 90, 15, 22, 0, 'true'),
(7, 'Presidente', 70000, 100, 1, 0, 70, 'true'),
(8, 'Juez de Paz', 600000, 80, 3, 9, 80, 'true'),
(9, 'Juez Supremo', 300000, 85, 1, 7, 90, 'true'),
(11, 'Fiscal', 0, 60, 2, 7, 0, 'true'),
(12, 'Policia', 432000, 30, 6, 13, 60, 'true'),
(13, 'Comisario de Policia', 0, 50, 2, 7, 70, 'true'),
(16, 'Ministro', 60, 95, 6, 7, 0, 'true'),
(40, 'Arquitecto', 86400, 20, 1, 7, 40, 'true'),
(19, 'Vicepresidente', 600000, 98, 1, 7, 0, 'true'),
(20, 'Defensor del Pueblo', 60, 50, 1, 7, 0, 'true'),
(21, 'Supervisor del Censo', 60, 70, 2, 7, 70, 'true'),
(22, 'Presidente Parlamento', 60, 97, 1, 7, 0, 'true'),
(41, 'Consultor', 86400, 75, 1, 7, 0, 'true'),
(25, 'Notario', 181020, 20, 3, 7, 0, 'true'),
(26, 'Jefe de Prensa', 6000, 75, 2, 7, 0, 'true'),
(27, 'Secretario de Estado', 60, 50, 0, 7, 0, 'true'),
(28, 'Guia Turistico', 86400, 20, 0, 7, 0, 'true'),
(34, 'Profesor', 1, 10, 10, 35, 0, 'true'),
(35, 'Decano', 1, 15, 9, 7, 0, 'true'),
(36, 'Secretario', 61, 20, 0, 7, 0, 'true'),
(37, 'Funcionario', 61, 25, 10, 7, 60, 'true'),
(42, 'Embajador', 86400, 92, 1, 7, 0, 'true');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_examenes`
--

CREATE TABLE IF NOT EXISTS `vulcan_examenes` (
  `ID` smallint(5) NOT NULL auto_increment,
  `titulo` varchar(100) NOT NULL default '',
  `descripcion` text NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cargo_ID` smallint(5) NOT NULL default '0',
  `nota` varchar(5) NOT NULL default '5.0',
  `num_preguntas` smallint(5) NOT NULL default '10',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `titulo` (`titulo`),
  KEY `nota` (`nota`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `vulcan_examenes`
--

INSERT INTO `vulcan_examenes` (`ID`, `titulo`, `descripcion`, `user_ID`, `time`, `cargo_ID`, `nota`, `num_preguntas`) VALUES
(10, 'Fiscal', '- Constituci&oacute;n de VULCAN<br />\r\n<br />\r\n- Conocimientos generales sobre VULCAN', 1, '2008-11-21 13:10:17', 11, '6.0', 7),
(9, 'Juez Supremo', '- Constituci&oacute;n de VULCAN<br />\r\n<br />\r\n<br />\r\n- Conocimientos generales sobre VULCAN', 1, '2008-11-21 13:10:17', 9, '8.0', 12),
(41, 'Derecho Administrativo', 'Todas las leyes y reglamentos de POL. Es un examen realmente dif&iacute;cil.', 14587, '2008-12-19 11:43:14', -41, '9.0', 30),
(8, 'Juez de Paz', '- Constituci&oacute;n de VULCAN<br />\r\n<br />\r\n<br />\r\n- Conocimientos generales de VULCAN', 1, '2008-11-21 13:10:17', 8, '8.0', 11),
(48, 'Derecho', '- Constituci&oacute;n de VULCAN:<br />\r\n<br />\r\n<br />\r\n- Conocimientos generales de VULCAN', 12557, '2009-05-13 19:49:51', -48, '5.0', 15),
(6, 'Diputado', '-Conocimientos generales de VULCAN<br />\r\n-Ley del parlamento:<a href=" http://vulcan.virtualpol.com/doc/ley-del-parlamento/"> http://vulcan.virtualpol.com/doc/ley-del-parlamento/</a>', 1, '2008-11-21 13:10:17', 6, '8.5', 13),
(11, 'Policia', '- Conocimientos sobre Polic&iacute;a: <br />\r<a href="\nhttp://vulcan.virtualpol.com/doc/ley-del-policia/">\nhttp://vulcan.virtualpol.com/doc/ley-del-policia/</a>', 1, '2008-11-21 13:10:17', 12, '6.5', 13),
(12, 'Comisario de Policia', '- Conocimientos sobre la Ley del Polic&iacute;a:<a href=" http://vulcan.virtualpol.com/doc/ley-del-policia/"> http://vulcan.virtualpol.com/doc/ley-del-policia/</a>', 1, '2008-11-21 13:10:17', 13, '7.5', 10),
(14, 'Ministro', '-Conocimientos generales de VULCAN<br />\r\n-Ley org&aacute;nica del Parlamento:<a href=" http://vulcan.virtualpol.com/doc/ley-organica-del-parlamento/"> http://vulcan.virtualpol.com/doc/ley-organica-del-parlamento/</a>', 1, '2008-11-21 13:10:17', 16, '7.0', 10),
(16, 'Vicepresidente', 'Conocimientos generales sobre VULCAN', 1, '2008-11-21 13:10:17', 19, '9.0', 10),
(17, 'Defensor del Pueblo', '- Ley del Defensor del Pueblo:<br />\r<a href="\nhttp://vulcan.virtualpol.com/doc/ley-del-defensor-del-pueblo/">\nhttp://vulcan.virtualpol.com/doc/ley-del-defensor-del-pueblo/</a><br />\r\n<br />\r\nConocimientos generales sobre VULCAN', 1, '2008-11-21 13:10:17', 20, '8', 10),
(49, 'Historia', '-La Historia de VULCAN', 13892, '2009-05-23 20:36:36', -49, '9.9', 30),
(18, 'Supervisor del Censo', 'Conocimientos del Supervisor del Censo', 1, '2008-11-21 13:10:17', 21, '9.0', 5),
(47, 'Embajador', 'Orden Ministerial de Embajadas: <a href="http://vulcan.virtualpol.com/doc/orden-ministerial-de-embajadas/">http://vulcan.virtualpol.com/doc/orden-ministerial-de-embajadas/</a><br />\r\nConocimientos generales de Vulcan', 1, '2009-04-26 00:00:00', 42, '7.0', 10),
(19, 'Presidente Parlamento', 'Preguntas generales', 1, '2008-11-21 13:10:17', 22, '7.5', 5),
(45, 'Consultor', '- Conocimientos sobre el sistema de consultas.<br />\r\n- Correcta escritura.<br />\r\n- Capacidad para confeccionar consultas neutrales y correctas.<br />\r\n<br />\r\n', 1, '2009-02-09 12:37:21', 41, '5.0', 15),
(46, 'Oposiciones Decano', '- Ley de Educaci&oacute;n <a href="http://pol.teoriza.com/doc/propuesta-ley-de-educacion/">http://pol.teoriza.com/doc/propuesta-ley-de-educacion/</a><br />\r\n- Ayuda para el funcionamiento de los ex&aacute;menes<br />\r\n<a href="http://pol.teoriza.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/">http://pol.teoriza.com/doc/ayuda-para-el-funcionamiento-de-los-examenes/</a><br />\r\n- Conocimientos generales de POL.<br />\r\n- Conocimientos del panel de configuraci&oacute;n de ex&aacute;menes.', 9279, '2009-02-12 12:53:27', -46, '9.5', 50),
(21, 'Notario', 'Conocimientos generales sobre VULCAN<br />\r\n<br />\r\nConocimientos sobre los niveles de todos los cargos', 1, '2008-11-21 13:10:17', 25, '9.0', 10),
(22, 'Jefe de Prensa', 'Temario:<br />\r\n-Conocimientos generales de Vulcan.<br />\r\n-Conocimiento general de medios de comunicaci&oacute;n y redacci&oacute;n.<br />\r\n-Conocimientos generales de POL<br />\r\n<br />\r\nPuede ayudarte el Reglamento del peri&oacute;dico nacional de POL, por ahora no tenemos en Vulcan:<br />\r<a href="\nhttp://pol.teoriza.com/doc/reglamento-del-periodico-nacional-de-pol/">\nhttp://pol.teoriza.com/doc/reglamento-del-periodico-nacional-de-pol/</a><br />\r\n<br />\r\n', 1, '2008-11-21 13:10:17', 26, '6.0', 15),
(23, 'Secretario de Estado', '-Conocimientos Generales de POL', 1, '2008-11-21 13:10:17', 27, '5.0', 10),
(24, 'GuÃ­a Turistico', 'Conocimientos Generales de VULCAN', 1, '2008-11-21 13:10:17', 28, '5.5', 10),
(25, 'Funcionario', '- Conocimientos de jerarqu&iacute;a de cargos<br />\r<a href="\nhttp://vulcan.virtualpol.com/cargos/">\nhttp://vulcan.virtualpol.com/cargos/</a><br />\r\n<br />\r\n- Conocimientos generales sobre documentos<br />\r<a href="\nhttp://vulcan.virtualpol.com/doc/">\nhttp://vulcan.virtualpol.com/doc/</a><br />\r\n<br />\r\n- Conocimientos b&aacute;sicos sobre juicios<br />\r<a href="\nhttp://vulcan.virtualpol.com/foro/justicia/">\nhttp://vulcan.virtualpol.com/foro/justicia/</a><br />\r\n<br />\r\n- Conocimientos generales de VULCAN', 1, '2008-11-21 13:10:17', 37, '9.0', 5),
(27, 'Agente de Empleo', 'Conocimientos generales sobre POL<br />\r\n<br />\r\n-Reglamento de Empleo P&uacute;blico<br />\r\n<a href="http://pol.teoriza.com/doc/reglamento-de-empleo-publico/">http://pol.teoriza.com/doc/reglamento-de-empleo-publico/</a>', 1, '2008-11-21 13:10:17', 31, '7.0', 15),
(30, 'Profesor', '-Conocimientos sobre el control de los examenes:<a href=" http://vulcan.virtualpol.com/doc/informacion-sobre-el-control-de-los-examenes/"> http://vulcan.virtualpol.com/doc/informacion-sobre-el-control-de-los-examenes/</a><br />\r\n-Conocimientos generales<br />\r\n', 1, '2008-11-21 13:10:17', 34, '8.5', 10),
(31, 'Decano', '-Conocimientos generales<br />\r\n-Conocimientos sobre el control de las preguntas:<a href=" http://vulcan.virtualpol.com/doc/informacion-sobre-el-control-de-los-examenes/"> http://vulcan.virtualpol.com/doc/informacion-sobre-el-control-de-los-examenes/</a>', 1, '2008-11-21 13:10:17', 35, '9.5', 15),
(0, 'General', 'Editar...', 1, '2008-11-26 10:49:38', 0, '5.0', 10),
(39, 'Periodista', 'Temario:<br />\r\n-Conocimientos generales de Vulcan.<br />\r\n-Conocimiento general de medios de comunicaci&oacute;n y redacci&oacute;n.<br />\r\n-Conocimientos generales de POL<br />\r\n<br />\r\nPuede ayudarte el Reglamento del peri&oacute;dico nacional de POL, por ahora no tenemos en Vulcan:<br />\r\n<a href="http://pol.teoriza.com/doc/reglamento-del-periodico-nacional-de-pol/">http://pol.teoriza.com/doc/reglamento-del-periodico-nacional-de-pol/</a><br />\r\n<br />\r\n', 1, '2008-12-01 01:43:06', -39, '5.0', 10),
(40, 'Inspector de trabajo', '- Constituci&oacute;n<br />\r\n<a href="http://pol.teoriza.com/doc/constitucion-de-pol/">http://pol.teoriza.com/doc/constitucion-de-pol/</a><br />\r\n<br />\r\n- Ley del Inspector de Trabajo<br />\r\n<a href="http://pol.teoriza.com/doc/ley-del-inspector-de-trabajo/">http://pol.teoriza.com/doc/ley-del-inspector-de-trabajo/</a><br />\r\n<br />\r\n- Reglamento de Empleo P&uacute;blico<br />\r\n<a href="http://pol.teoriza.com/doc/reglamento-de-empleo-publico/">http://pol.teoriza.com/doc/reglamento-de-empleo-publico/</a>', 1, '2008-12-01 00:00:00', 39, '8.0', 10),
(42, 'Arquitecto', 'Conocimientos generales del Mapa', 1, '2009-01-22 19:24:50', 40, '5.0', 10),
(44, 'Secretario', 'Editar...', 1, '2009-02-09 12:03:28', -44, '5.0', 10),
(50, 'LÃ³gica', 'L&oacute;gica.<br />\r\n<br />\r\n<br />\r\nA veces se produce contradicci&oacute;n independientemente de la respuesta que elijas, en ese caso hay que escoger Exit, s&oacute;lo en ese caso.', 200189, '2009-08-29 13:57:25', -50, '6.1', 5),
(51, 'Supervisor', 'Editar...', 200281, '2009-09-19 22:03:07', -51, '5.0', 10),
(52, 'Examen de prueba', 'Editar...', 200173, '2009-10-01 15:58:59', -52, '0.0', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulcan_foros`
--

CREATE TABLE IF NOT EXISTS `vulcan_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '20',
  `estado` enum('ok') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `vulcan_foros`
--

INSERT INTO `vulcan_foros` (`ID`, `url`, `title`, `descripcion`, `acceso`, `time`, `estado`, `acceso_msg`) VALUES
(1, 'general', 'General', 'Foro principal', 1, 1, 'ok', 1),
(2, 'parlamento', 'Parlamento', 'Debates parlamentarios, Diputados y Gobierno', 90, 9, 'ok', 50),
(3, 'gobierno', 'Gobierno', 'Zona de desarrollo del Gobierno de POL', 95, 8, 'ok', 1),
(4, 'justicia', 'Justicia', 'AdministraciÃ³n Judicial', 50, 7, 'ok', 1),
(5, 'notaria', 'Notar&iacute;a', 'Zona para dejar constancia de hechos, no editable', 1, 6, '', 1),
(6, 'politica', 'Pol&iacute;tica', 'Zona para el Poder Politico', 1, 5, 'ok', 1),
(7, 'economia', 'Econom&iacute;a', 'Zona para el Poder EconÃ³mico', 1, 4, 'ok', 1),
(8, 'mercado', 'Mercado', 'Zona de compra venta, ofertas y demandas', 0, 3, 'ok', 0),
(9, 'camara-ciudadanos', 'Camara', 'Refundacion de Vulcan', 1, 2, 'ok', 1),
(10, 'juegos', 'Juegos', 'Lugar  de reunion de los jugadores de POL', 0, 10, 'ok', 0),
(11, '', '', 'Notaria', 1, 11, '', 0),
(12, 'revulcannizacio', 'ReVulcannizacio', '', 1, 1, '', 0),
(13, 'noratia', 'Noratia', '', 1, 10, '', 0),
(14, 'lanotaria', 'LaNotaria', 'Donde se firman los contratos', 1, 11, '', 0),
(15, 'notariaaa', 'Notariaaa', '', 1, 10, '', 0),
(16, 'la-notaria', 'La Notaria', 'Lugar donde se firman los contratos', 1, 11, 'ok', 1),
(17, 'embajadas', 'Embajadas', 'Lugar para los embajadores', 0, 13, '', 0),
(18, 'internacional', 'Internacional', 'Para Ciudadanos Extranjeros', 0, 12, 'ok', 0),
(19, 'offtopic', 'OffTopic', 'Â¡biba el Jugo! Lugar para jugosear', 1, 10, 'ok', 0);
