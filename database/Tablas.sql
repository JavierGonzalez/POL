# Host: virtualpol.com:4321 (Version: 5.0.95-log)
# Date: 2013-05-05 14:30:03

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

#
# Source for table "api"
#

CREATE TABLE `api` (
  `api_ID` int(11) unsigned NOT NULL auto_increment,
  `item_ID` varchar(255) default NULL,
  `pais` varchar(30) default NULL,
  `tipo` enum('facebook','twitter') default 'facebook',
  `estado` enum('activo','inactivo') default 'activo',
  `nombre` varchar(255) default NULL,
  `linea_editorial` text,
  `url` varchar(255) default NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `acceso_escribir` text,
  `acceso_borrador` text,
  `clave` text,
  `num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY (`api_ID`),
  KEY `pais` (`pais`),
  KEY `estado` (`estado`),
  KEY `tipo` (`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Source for table "api_posts"
#

CREATE TABLE `api_posts` (
  `post_ID` int(11) unsigned NOT NULL auto_increment,
  `pais` varchar(255) default NULL,
  `api_ID` mediumint(9) unsigned default NULL,
  `estado` enum('publicado','cron','borrado','pendiente') NOT NULL default 'pendiente',
  `mensaje_ID` varchar(900) default NULL,
  `pendiente_user_ID` mediumint(8) unsigned default NULL,
  `publicado_user_ID` mediumint(9) unsigned default NULL,
  `borrado_user_ID` mediumint(8) unsigned default NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_cron` datetime default '0000-00-00 00:00:00',
  `message` text,
  `picture` varchar(255) default NULL,
  `link` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `caption` varchar(255) default NULL,
  `source` varchar(255) default NULL,
  PRIMARY KEY (`post_ID`),
  KEY `pais` (`pais`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=89 DEFAULT CHARSET=utf8;

#
# Source for table "cargos"
#

CREATE TABLE `cargos` (
  `ID` smallint(6) NOT NULL auto_increment,
  `pais` varchar(30) character set utf8 default NULL,
  `cargo_ID` smallint(6) unsigned NOT NULL default '0',
  `asigna` smallint(5) NOT NULL default '7',
  `nombre` varchar(32) NOT NULL default '',
  `nombre_extra` varchar(255) character set utf8 NOT NULL default '',
  `nivel` tinyint(3) NOT NULL default '1',
  `num` smallint(5) NOT NULL default '0',
  `salario` mediumint(9) unsigned NOT NULL default '0',
  `autocargo` enum('true','false') character set utf8 NOT NULL default 'false',
  `elecciones` datetime default NULL,
  `elecciones_electos` tinyint(3) unsigned default NULL,
  `elecciones_cada` smallint(5) unsigned default NULL,
  `elecciones_durante` tinyint(3) unsigned default NULL,
  `elecciones_votan` varchar(999) character set utf8 default NULL,
  PRIMARY KEY (`ID`),
  KEY `nivel` (`nivel`),
  KEY `nombre` (`nombre`),
  KEY `asigna` (`asigna`),
  KEY `cargo_ID` (`cargo_ID`),
  KEY `pais` (`pais`),
  KEY `elecciones` (`elecciones`)
) ENGINE=MyISAM AUTO_INCREMENT=622 DEFAULT CHARSET=latin1;

#
# Source for table "cargos_users"
#

CREATE TABLE `cargos_users` (
  `ID` bigint(20) NOT NULL auto_increment,
  `cargo_ID` smallint(5) NOT NULL default '0',
  `pais` varchar(30) default NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cargo` enum('true','false') NOT NULL default 'false',
  `aprobado` enum('ok','no') NOT NULL default 'ok',
  `nota` decimal(3,1) unsigned NOT NULL default '0.0',
  PRIMARY KEY (`ID`),
  KEY `cargo` (`cargo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo_ID` (`cargo_ID`),
  KEY `aprobado` (`aprobado`),
  KEY `pais` (`pais`),
  KEY `nota` (`nota`)
) ENGINE=MyISAM AUTO_INCREMENT=13355 DEFAULT CHARSET=utf8;

#
# Source for table "cat"
#

CREATE TABLE `cat` (
  `ID` smallint(6) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `url` varchar(80) NOT NULL default '',
  `nombre` varchar(80) NOT NULL default '',
  `num` smallint(6) unsigned NOT NULL default '0',
  `nivel` tinyint(3) unsigned NOT NULL default '0',
  `tipo` enum('empresas','docs','cargos') NOT NULL default 'docs',
  `orden` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `url` (`url`,`nivel`,`tipo`,`orden`,`nombre`,`num`),
  KEY `tipo` (`tipo`),
  KEY `orden` (`orden`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;

#
# Source for table "chats"
#

CREATE TABLE `chats` (
  `chat_ID` smallint(5) unsigned NOT NULL auto_increment,
  `estado` enum('activo','bloqueado','en_proceso','expirado','borrado') NOT NULL default 'en_proceso',
  `pais` varchar(30) default NULL,
  `url` varchar(90) NOT NULL,
  `titulo` varchar(90) NOT NULL,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `admin` varchar(900) NOT NULL default '',
  `acceso_leer` varchar(30) NOT NULL default 'anonimos',
  `acceso_escribir` varchar(30) default 'ciudadanos_global',
  `acceso_escribir_ex` varchar(30) NOT NULL default 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) default '',
  `acceso_cfg_escribir` varchar(900) default '',
  `acceso_cfg_escribir_ex` varchar(900) NOT NULL default '',
  `fecha_creacion` datetime NOT NULL,
  `fecha_last` datetime NOT NULL,
  `dias_expira` smallint(5) unsigned default NULL,
  `url_externa` varchar(500) default NULL,
  `stats_visitas` int(12) unsigned NOT NULL default '0',
  `stats_msgs` int(12) unsigned NOT NULL default '0',
  `GMT` tinyint(2) NOT NULL default '1',
  PRIMARY KEY (`chat_ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`),
  KEY `pais` (`pais`(1)),
  KEY `acceso_leer` (`acceso_leer`),
  KEY `acceso_escribir` (`acceso_escribir`),
  KEY `acceso_cfg_leer` (`acceso_cfg_leer`(333)),
  KEY `acceso_cfg_escribir` (`acceso_cfg_escribir`(333)),
  KEY `stats_msgs` (`stats_msgs`),
  KEY `fecha_last` (`fecha_last`),
  KEY `acceso_escribir_ex` (`acceso_escribir_ex`),
  KEY `acceso_cfg_escribir_ex` (`acceso_cfg_escribir_ex`)
) ENGINE=MyISAM AUTO_INCREMENT=724 DEFAULT CHARSET=latin1;

#
# Source for table "chats_msg"
#

CREATE TABLE `chats_msg` (
  `msg_ID` int(8) unsigned NOT NULL auto_increment,
  `chat_ID` smallint(5) unsigned NOT NULL,
  `nick` varchar(32) NOT NULL,
  `msg` varchar(900) NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(6) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  `IP` bigint(12) default NULL,
  PRIMARY KEY (`msg_ID`),
  KEY `chat_ID` (`chat_ID`),
  KEY `nick` (`nick`),
  KEY `time` (`time`),
  KEY `cargo` (`cargo`),
  KEY `user_ID` (`user_ID`),
  KEY `tipo` (`tipo`),
  KEY `msg` (`msg`(333)),
  KEY `IP` (`IP`)
) ENGINE=MyISAM AUTO_INCREMENT=4737396 DEFAULT CHARSET=latin1;

#
# Source for table "config"
#

CREATE TABLE `config` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `dato` varchar(100) NOT NULL default '',
  `valor` text NOT NULL,
  `autoload` enum('si','no') NOT NULL default 'si',
  PRIMARY KEY (`ID`),
  KEY `dato` (`dato`),
  KEY `autoload` (`autoload`),
  KEY `pais` (`pais`),
  KEY `valor` (`valor`(255))
) ENGINE=MyISAM AUTO_INCREMENT=728 DEFAULT CHARSET=utf8;

#
# Source for table "cuentas"
#

CREATE TABLE `cuentas` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `nombre` varchar(25) character set utf8 NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `exenta_impuestos` tinyint(1) NOT NULL default '0',
  `gobierno` enum('true','false') character set utf8 default 'false',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `user_ID` (`user_ID`),
  KEY `nivel` (`nivel`),
  KEY `pais` (`pais`)
) ENGINE=MyISAM AUTO_INCREMENT=591 DEFAULT CHARSET=latin1;

CREATE TABLE cuentas_apoderados (
  `ID` mediumint(8) NOT NULL auto_increment,
  `cuenta_ID` mediumint(8) NOT NULL,
  `user_ID` mediumint(8) NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=591 DEFAULT CHARSET=latin1;
	
#
# Source for table "docs"
#

CREATE TABLE `docs` (
  `ID` smallint(5) NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `url` varchar(255) character set utf8 NOT NULL default '',
  `title` varchar(255) character set utf8 NOT NULL default '',
  `text` longtext character set utf8 NOT NULL,
  `text_backup` longtext character set utf8 NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `estado` enum('ok','del','borrador') character set utf8 NOT NULL default 'ok',
  `cat_ID` tinyint(3) NOT NULL default '0',
  `acceso_leer` varchar(30) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(30) character set utf8 NOT NULL default 'privado',
  `acceso_cfg_leer` varchar(800) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(800) character set utf8 NOT NULL default '',
  `version` mediumint(9) unsigned NOT NULL default '0',
  `pad_ID` varchar(255) default NULL,
  PRIMARY KEY (`ID`),
  KEY `estado` (`estado`),
  KEY `cat_ID` (`cat_ID`),
  KEY `url` (`url`),
  KEY `pais` (`pais`),
  KEY `time_last` (`time_last`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=1679 DEFAULT CHARSET=latin1;

#
# Source for table "empresas"
#

CREATE TABLE `empresas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `url` varchar(40) character set utf8 NOT NULL default '',
  `nombre` varchar(40) character set utf8 NOT NULL default '',
  `user_ID` mediumint(8) NOT NULL default '0',
  `descripcion` text character set utf8 NOT NULL,
  `web` varchar(200) character set utf8 NOT NULL default '',
  `cat_ID` smallint(6) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pv` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`,`cat_ID`),
  KEY `cat_ID` (`cat_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=820 DEFAULT CHARSET=latin1;

#
# Source for table "empresas_acciones"
#

CREATE TABLE `empresas_acciones` (
  `ID` int(11) NOT NULL auto_increment,
  `ID_empresa` mediumint(9) unsigned NOT NULL default '0',
  `pais` varchar(30) default NULL,
  `nick` varchar(300) character set utf8 collate utf8_spanish_ci NOT NULL,
  `num_acciones` int(11) NOT NULL default '100',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Source for table "etsiit_foros"
#

CREATE TABLE `etsiit_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(6) unsigned default NULL,
  `url` varchar(50) character set utf8 NOT NULL default '',
  `title` varchar(50) character set utf8 NOT NULL default '',
  `descripcion` varchar(255) character set utf8 NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') character set utf8 NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Source for table "etsiit_foros_hilos"
#

CREATE TABLE `etsiit_foros_hilos` (
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
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Source for table "etsiit_foros_msg"
#

CREATE TABLE `etsiit_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Source for table "examenes"
#

CREATE TABLE `examenes` (
  `ID` mediumint(9) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `titulo` varchar(255) default NULL,
  `descripcion` text,
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cargo_ID` smallint(5) NOT NULL default '0',
  `nota` varchar(5) NOT NULL default '5',
  `num_preguntas` smallint(5) unsigned NOT NULL default '1',
  `ID_old` mediumint(8) unsigned default NULL,
  PRIMARY KEY (`ID`),
  KEY `titulo` (`titulo`),
  KEY `nota` (`nota`),
  KEY `pais` (`pais`),
  KEY `cargo_ID` (`cargo_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=516 DEFAULT CHARSET=utf8;


CREATE TABLE `examenes_profesores` (
  `ID` mediumint(9) unsigned NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `examen_ID` mediumint(9) unsigned NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=516 DEFAULT CHARSET=utf8;


#
# Source for table "examenes_preg"
#

CREATE TABLE `examenes_preg` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `examen_ID` smallint(5) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY (`ID`),
  KEY `pais` (`pais`),
  KEY `examen_ID` (`examen_ID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3517 DEFAULT CHARSET=utf8;

#
# Source for table "expulsiones"
#

CREATE TABLE `expulsiones` (
  `ID` smallint(5) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `autor` mediumint(8) NOT NULL default '0',
  `expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `razon` varchar(150) NOT NULL,
  `estado` enum('activo','inactivo','expulsado','cancelado','indultado') NOT NULL default 'activo',
  `tiempo` varchar(20) NOT NULL default '0',
  `IP` varchar(12) NOT NULL default '0',
  `cargo` tinyint(3) unsigned NOT NULL default '12',
  `motivo` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `user_ID` (`user_ID`),
  KEY `estado` (`estado`),
  KEY `IP` (`IP`),
  KEY `expire` (`expire`)
) ENGINE=MyISAM AUTO_INCREMENT=1512 DEFAULT CHARSET=latin1;

#
# Source for table "fcsm_foros"
#

CREATE TABLE `fcsm_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(6) unsigned default NULL,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) NOT NULL default 'ciudadanos_global',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

#
# Source for table "fcsm_foros_hilos"
#

CREATE TABLE `fcsm_foros_hilos` (
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
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=569 DEFAULT CHARSET=latin1;

#
# Source for table "fcsm_foros_msg"
#

CREATE TABLE `fcsm_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=5423 DEFAULT CHARSET=latin1;

#
# Source for table "foros"
#

CREATE TABLE `foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(6) unsigned default NULL,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) NOT NULL default 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

#
# Source for table "foros_items"
#

CREATE TABLE `foros_items` (
  `item_ID` int(9) unsigned NOT NULL auto_increment,
  `pais` varchar(255) default NULL,
  `estado` enum('ok','borrado','cerrado') NOT NULL default 'ok',
  `foro_ID` mediumint(9) unsigned default NULL,
  `hilo_ID` mediumint(8) default NULL,
  `parent_ID` mediumint(8) unsigned default NULL,
  `nivel` tinyint(3) unsigned default '1',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nick` varchar(20) default NULL,
  `cargo` tinyint(3) NOT NULL default '0',
  `title` varchar(80) NOT NULL default '',
  `text` text NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `num` smallint(5) NOT NULL default '0',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  `url_old` varchar(80) NOT NULL default '',
  PRIMARY KEY (`item_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

#
# Source for table "grupos"
#

CREATE TABLE `grupos` (
  `grupo_ID` int(11) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `nombre` varchar(255) NOT NULL default '',
  `num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`grupo_ID`),
  KEY `num` (`num`),
  KEY `pais` (`pais`)
) ENGINE=MyISAM AUTO_INCREMENT=351 DEFAULT CHARSET=utf8;

#
# Source for table "hechos"
#

CREATE TABLE `hechos` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `pais` varchar(30) character set utf8 default NULL,
  `time` date NOT NULL,
  `nick` varchar(14) character set utf8 NOT NULL default 'GONZO',
  `texto` varchar(2000) character set utf8 NOT NULL,
  `estado` enum('ok','del') character set utf8 NOT NULL default 'ok',
  `time2` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `time` (`time`,`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=346 DEFAULT CHARSET=latin1;

#
# Source for table "hispania_foros"
#

CREATE TABLE `hispania_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` mediumint(9) unsigned default NULL,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(255) NOT NULL default 'anonimos',
  `acceso_escribir` varchar(255) NOT NULL default 'ciudadanos_global',
  `acceso_escribir_msg` varchar(255) NOT NULL default 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;

#
# Source for table "hispania_foros_hilos"
#

CREATE TABLE `hispania_foros_hilos` (
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
  `votos` mediumint(9) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=5341 DEFAULT CHARSET=utf8;

#
# Source for table "hispania_foros_msg"
#

CREATE TABLE `hispania_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` mediumint(9) NOT NULL default '0',
  `votos_num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=43523 DEFAULT CHARSET=utf8;

#
# Source for table "kicks"
#

CREATE TABLE `kicks` (
  `ID` mediumint(9) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `user_ID` mediumint(9) unsigned NOT NULL default '0',
  `autor` mediumint(8) unsigned NOT NULL default '0',
  `expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `razon` varchar(160) NOT NULL default '',
  `estado` enum('activo','inactivo','expulsado','cancelado') NOT NULL default 'activo',
  `tiempo` varchar(20) NOT NULL default '0',
  `IP` varchar(12) NOT NULL default '0',
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `motivo` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `pais` (`pais`,`user_ID`,`estado`,`expire`),
  KEY `estado` (`estado`),
  KEY `user_ID` (`user_ID`),
  KEY `IP` (`IP`),
  KEY `expire` (`expire`)
) ENGINE=MyISAM AUTO_INCREMENT=763 DEFAULT CHARSET=utf8;

#
# Source for table "log"
#

CREATE TABLE `log` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `nick` varchar(20) NOT NULL default '',
  `accion` text NOT NULL,
  `accion_a` varchar(255) NOT NULL default '',
  PRIMARY KEY (`ID`),
  KEY `pais` (`pais`),
  KEY `user_ID` (`user_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=45865 DEFAULT CHARSET=utf8;

#
# Source for table "mapa"
#

CREATE TABLE `mapa` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `pais` varchar(30) character set utf8 default NULL,
  `pos_x` tinyint(2) NOT NULL default '1',
  `pos_y` tinyint(2) NOT NULL default '1',
  `size_x` tinyint(2) NOT NULL default '1',
  `size_y` tinyint(2) NOT NULL default '1',
  `user_ID` mediumint(8) NOT NULL default '1',
  `nick` varchar(255) default NULL,
  `link` varchar(90) NOT NULL default '',
  `text` varchar(90) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pols` mediumint(8) NOT NULL default '0',
  `color` char(7) NOT NULL default '',
  `estado` enum('p','v','e') NOT NULL default 'p',
  `superficie` smallint(4) NOT NULL default '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=797 DEFAULT CHARSET=latin1;

CREATE TABLE `mapa_barrios` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `pos_x` tinyint(2) NOT NULL default '1',
  `pos_y` tinyint(2) NOT NULL default '1',
  `size_x` tinyint(3) NOT NULL default '1',
  `size_y` tinyint(3) NOT NULL default '1',
  `nombre` text not null,
  `multiplicador_impuestos`  DECIMAL(5,2) NULL,
  `altura_maxima` smallint(5) NULL,
  `color` char(7) default '#FFFFFF',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `mapa_altura` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `parcela_ID` smallint(5) unsigned NOT NULL,
  `link` varchar(90) NOT NULL DEFAULT '',
  `text` varchar(90) NOT NULL DEFAULT '',
  `color` char(7) NOT NULL DEFAULT '',
  `altura` smallint(5) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

#
# Source for table "mensajes"
#

CREATE TABLE `mensajes` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `envia_ID` mediumint(8) unsigned NOT NULL default '0',
  `recibe_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `leido` enum('0','1') NOT NULL default '0',
  `cargo` smallint(5) NOT NULL default '0',
  `recibe_masivo` varchar(10) NOT NULL default '',
  PRIMARY KEY (`ID`),
  KEY `envia_ID` (`envia_ID`),
  KEY `recibe_ID` (`recibe_ID`),
  KEY `leido` (`leido`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=271745 DEFAULT CHARSET=utf8;

#
# Source for table "mic_foros"
#

CREATE TABLE `mic_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(5) unsigned default NULL,
  `url` varchar(50) character set utf8 NOT NULL default '',
  `title` varchar(50) character set utf8 NOT NULL default '',
  `descripcion` varchar(255) character set utf8 NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') character set utf8 NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`),
  KEY `time` (`time`),
  KEY `acceso_leer` (`acceso_leer`(333)),
  KEY `acceso_escribir` (`acceso_escribir`(333)),
  KEY `acceso_cfg_leer` (`acceso_cfg_leer`(333)),
  KEY `acceso_cfg_escribir` (`acceso_cfg_escribir`(333))
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=latin1;

#
# Source for table "mic_foros_hilos"
#

CREATE TABLE `mic_foros_hilos` (
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
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=481 DEFAULT CHARSET=latin1;

#
# Source for table "mic_foros_msg"
#

CREATE TABLE `mic_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`),
  KEY `hilo_ID` (`hilo_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2432 DEFAULT CHARSET=latin1;

#
# Source for table "notificaciones"
#

CREATE TABLE `notificaciones` (
  `noti_ID` int(11) unsigned NOT NULL auto_increment,
  `time` timestamp NULL default CURRENT_TIMESTAMP,
  `emisor` varchar(30) NOT NULL default 'sistema',
  `visto` enum('true','false') NOT NULL default 'false',
  `user_ID` mediumint(8) NOT NULL default '0',
  `texto` varchar(60) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  PRIMARY KEY (`noti_ID`),
  KEY `time` (`time`),
  KEY `visto` (`visto`),
  KEY `user_ID` (`user_ID`),
  KEY `url` (`url`),
  KEY `texto` (`texto`),
  KEY `emisor` (`emisor`)
) ENGINE=MyISAM AUTO_INCREMENT=344115 DEFAULT CHARSET=utf8;

#
# Source for table "occupy_foros"
#

CREATE TABLE `occupy_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(6) unsigned default NULL,
  `url` varchar(50) character set utf8 NOT NULL default '',
  `title` varchar(50) character set utf8 NOT NULL default '',
  `descripcion` varchar(255) character set utf8 NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') character set utf8 NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Source for table "occupy_foros_hilos"
#

CREATE TABLE `occupy_foros_hilos` (
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
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Source for table "occupy_foros_msg"
#

CREATE TABLE `occupy_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

#
# Source for table "partidos"
#

CREATE TABLE `partidos` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `ID_presidente` mediumint(8) unsigned NOT NULL default '0',
  `fecha_creacion` datetime NOT NULL default '0000-00-00 00:00:00',
  `siglas` varchar(20) default NULL,
  `nombre` varchar(50) default NULL,
  `descripcion` text,
  `estado` enum('ok') NOT NULL default 'ok',
  `ID_old` smallint(6) unsigned default NULL,
  PRIMARY KEY (`ID`),
  KEY `pais` (`pais`),
  KEY `ID_presidente` (`ID_presidente`),
  KEY `siglas` (`siglas`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=188 DEFAULT CHARSET=utf8;

#
# Source for table "partidos_listas"
#

CREATE TABLE `partidos_listas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `ID_partido` mediumint(8) default '0',
  `user_ID` mediumint(9) unsigned default '0',
  `orden` smallint(5) default '0',
  PRIMARY KEY (`ID`),
  KEY `ID_partido` (`ID_partido`),
  KEY `user_ID` (`user_ID`),
  KEY `orden` (`orden`),
  KEY `pais` (`pais`)
) ENGINE=MyISAM AUTO_INCREMENT=181 DEFAULT CHARSET=utf8;

#
# Source for table "pcp_foros"
#

CREATE TABLE `pcp_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(6) unsigned default NULL,
  `url` varchar(50) character set utf8 NOT NULL default '',
  `title` varchar(50) character set utf8 NOT NULL default '',
  `descripcion` varchar(255) character set utf8 NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') character set utf8 NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

#
# Source for table "pcp_foros_hilos"
#

CREATE TABLE `pcp_foros_hilos` (
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
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

#
# Source for table "pcp_foros_msg"
#

CREATE TABLE `pcp_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=169 DEFAULT CHARSET=latin1;

#
# Source for table "pdi_foros"
#

CREATE TABLE `pdi_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(6) unsigned default NULL,
  `url` varchar(50) character set utf8 NOT NULL default '',
  `title` varchar(50) character set utf8 NOT NULL default '',
  `descripcion` varchar(255) character set utf8 NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') character set utf8 NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

#
# Source for table "pdi_foros_hilos"
#

CREATE TABLE `pdi_foros_hilos` (
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
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

#
# Source for table "pdi_foros_msg"
#

CREATE TABLE `pdi_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

#
# Source for table "plataformas"
#

CREATE TABLE `plataformas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `estado` enum('pendiente','ok','no') default 'pendiente',
  `pais` varchar(255) default NULL,
  `asamblea` enum('true','false') default 'false',
  `economia` enum('true','false') default 'true',
  `user_ID` mediumint(8) unsigned default NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `descripcion` text,
  `participacion` mediumint(8) unsigned default NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

#
# Source for table "plebiscito_foros"
#

CREATE TABLE `plebiscito_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(6) unsigned default NULL,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) NOT NULL default 'ciudadanos_global',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

#
# Source for table "plebiscito_foros_hilos"
#

CREATE TABLE `plebiscito_foros_hilos` (
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
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

#
# Source for table "plebiscito_foros_msg"
#

CREATE TABLE `plebiscito_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  `votos_num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

#
# Source for table "pol_foros"
#

CREATE TABLE `pol_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` varchar(255) default NULL,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(255) NOT NULL default 'anonimos',
  `acceso_escribir` varchar(255) NOT NULL default 'ciudadanos_global',
  `acceso_escribir_msg` varchar(255) default 'ciudadanos_global',
  `acceso_cfg_leer` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(255) default '',
  `limite` smallint(6) default '10',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

#
# Source for table "pol_foros_hilos"
#

CREATE TABLE `pol_foros_hilos` (
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
  `votos` mediumint(9) NOT NULL default '0',
  `votos_num` mediumint(9) default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=6505 DEFAULT CHARSET=latin1;

#
# Source for table "pol_foros_msg"
#

CREATE TABLE `pol_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` mediumint(9) NOT NULL default '0',
  `votos_num` mediumint(9) default '0',
  PRIMARY KEY (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=59184 DEFAULT CHARSET=latin1;

#
# Source for table "pujas"
#

CREATE TABLE `pujas` (
  `ID` mediumint(9) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `mercado_ID` smallint(5) default NULL,
  `user_ID` mediumint(9) unsigned default NULL,
  `pols` mediumint(8) unsigned default NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `pais` (`pais`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `user_ID` (`user_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=861 DEFAULT CHARSET=utf8;

#
# Source for table "referencias"
#

CREATE TABLE `referencias` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `IP` varchar(10) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `referer` varchar(255) NOT NULL default '',
  `pagado` enum('0','1') NOT NULL default '0',
  `new_user_ID` mediumint(8) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `IP` (`IP`),
  KEY `user_ID` (`user_ID`),
  KEY `pagado` (`pagado`)
) ENGINE=MyISAM AUTO_INCREMENT=10061 DEFAULT CHARSET=latin1;

#
# Source for table "socios"
#

CREATE TABLE `socios` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `time` datetime default NULL,
  `time_last` datetime default NULL,
  `estado` varchar(255) default 'inscrito',
  `pais` varchar(255) default NULL,
  `socio_ID` int(11) unsigned default NULL,
  `user_ID` int(11) unsigned default NULL,
  `nombre` varchar(255) default NULL,
  `NIF` varchar(255) default NULL,
  `pais_politico` varchar(255) default NULL,
  `localidad` varchar(255) default NULL,
  `cp` varchar(255) default NULL,
  `direccion` varchar(255) default NULL,
  `contacto_email` varchar(255) default NULL,
  `contacto_telefono` varchar(255) default NULL,
  `validador_ID` int(11) unsigned default NULL,
  PRIMARY KEY (`ID`),
  KEY `time` (`time`),
  KEY `time_last` (`time_last`),
  KEY `pais` (`pais`),
  KEY `estado` (`estado`),
  KEY `socio_ID` (`socio_ID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

#
# Source for table "stats"
#

CREATE TABLE `stats` (
  `stats_ID` smallint(5) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
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
  `confianza` smallint(5) default '0',
  `autentificados` mediumint(9) default '0',
  PRIMARY KEY (`stats_ID`),
  KEY `time` (`time`),
  KEY `pais` (`pais`)
) ENGINE=MyISAM AUTO_INCREMENT=5881 DEFAULT CHARSET=latin1;

#
# Source for table "transacciones"
#

CREATE TABLE `transacciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `pols` int(10) NOT NULL default '0',
  `emisor_ID` mediumint(8) NOT NULL default '0',
  `receptor_ID` mediumint(8) NOT NULL default '0',
  `concepto` varchar(90) character set utf8 NOT NULL default '',
  `periodicidad` enum('D','S') default NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `emisor_ID` (`emisor_ID`),
  KEY `receptor_ID` (`receptor_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=91492 DEFAULT CHARSET=latin1;

#
# Source for table "users"
#

CREATE TABLE `users` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(18) NOT NULL default '',
  `lang` varchar(5) default NULL,
  `pais` varchar(30) default NULL,
  `estado` enum('turista','ciudadano','expulsado','validar') NOT NULL default 'validar',
  `nivel` tinyint(3) unsigned NOT NULL default '1',
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `cargos` varchar(400) NOT NULL default '',
  `grupos` varchar(400) NOT NULL default '',
  `examenes` varchar(400) NOT NULL default '',
  `voto_confianza` smallint(5) NOT NULL default '0',
  `confianza_historico` text NOT NULL,
  `partido_afiliado` mediumint(9) unsigned NOT NULL default '0',
  `online` int(10) unsigned NOT NULL default '0',
  `visitas` mediumint(8) unsigned NOT NULL default '0',
  `paginas` int(10) unsigned NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `email` varchar(255) NOT NULL default '',
  `fecha_registro` datetime NOT NULL default '0000-00-00 00:00:00',
  `fecha_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `fecha_init` datetime NOT NULL default '0000-00-00 00:00:00',
  `rechazo_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `fecha_legal` datetime NOT NULL default '0000-00-00 00:00:00',
  `reset_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `nickchange_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `pass` varchar(255) NOT NULL default '',
  `pass2` varchar(255) NOT NULL default '',
  `api_pass` varchar(16) NOT NULL default '0',
  `api_num` smallint(5) NOT NULL default '0',
  `num_elec` tinyint(3) unsigned NOT NULL default '0',
  `SC` enum('true','false') NOT NULL default 'false',
  `ser_SC` enum('true','false','block') NOT NULL default 'false',
  `nota` decimal(3,1) NOT NULL default '0.0',
  `donacion` mediumint(9) unsigned default NULL,
  `avatar` enum('true','false') NOT NULL default 'false',
  `IP` varchar(12) NOT NULL default '0',
  `host` varchar(150) NOT NULL,
  `hosts` text,
  `IP_proxy` varchar(150) NOT NULL,
  `text` varchar(2300) NOT NULL default '',
  `nav` varchar(500) NOT NULL,
  `avatar_localdir` varchar(100) NOT NULL,
  `x` decimal(10,2) default NULL,
  `y` decimal(10,2) default NULL,
  `socio` enum('true','false') NOT NULL default 'false',
  `dnie` enum('true','false') default 'false',
  `dnie_check` varchar(400) default NULL,
  `ref` varchar(25) NOT NULL default '',
  `ref_num` mediumint(8) unsigned NOT NULL default '0',
  `bando` varchar(255) default NULL,
  `nota_SC` varchar(500) NOT NULL default '',
  `traza` varchar(600) NOT NULL default '',
  `datos` varchar(9999) NOT NULL default '',
  `nombre` varchar(255) default NULL,
  `temp` int(11) default NULL,
  PRIMARY KEY (`ID`),
  KEY `nick` (`nick`),
  KEY `pais` (`pais`),
  KEY `fecha_last` (`fecha_last`),
  KEY `estado` (`estado`),
  KEY `voto_confianza` (`voto_confianza`),
  KEY `IP` (`IP`),
  KEY `pass` (`pass`),
  KEY `cargo` (`cargo`),
  KEY `grupos` (`grupos`(333)),
  KEY `cargos` (`cargos`(333)),
  KEY `examenes` (`examenes`(333)),
  KEY `x` (`x`),
  KEY `y` (`y`),
  KEY `lang` (`lang`),
  KEY `nivel` (`nivel`),
  KEY `fecha_registro` (`fecha_registro`),
  KEY `paginas` (`paginas`),
  KEY `dnie` (`dnie`),
  KEY `temp` (`temp`),
  KEY `socio` (`socio`),
  KEY `SC` (`SC`),
  KEY `nota_SC` (`nota_SC`(333))
) ENGINE=MyISAM AUTO_INCREMENT=221507 DEFAULT CHARSET=utf8;

#
# Source for table "users_con"
#

CREATE TABLE `users_con` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `time` datetime default NULL,
  `tipo` enum('session','login') default 'login',
  `user_ID` mediumint(8) unsigned default NULL,
  `IP` int(11) unsigned default NULL,
  `IP_rango` varchar(255) default NULL,
  `IP_rango3` varchar(20) default NULL,
  `IP_pais` varchar(2) default NULL,
  `host` varchar(255) default NULL,
  `ISP` varchar(255) default NULL,
  `proxy` varchar(255) default NULL,
  `login_seg` smallint(5) unsigned default NULL,
  `login_ms` smallint(5) unsigned default NULL,
  `dispositivo` bigint(20) unsigned default NULL,
  `nav_resolucion` varchar(255) default NULL,
  `nav` varchar(500) default NULL,
  `nav_so` varchar(255) default NULL,
  `referer` varchar(255) default NULL,
  PRIMARY KEY (`ID`),
  KEY `user_ID` (`user_ID`),
  KEY `time` (`time`),
  KEY `tipo` (`tipo`),
  KEY `IP` (`IP`),
  KEY `dispositivo` (`dispositivo`),
  KEY `ISP` (`ISP`),
  KEY `host` (`host`),
  KEY `nav_resolucion` (`nav_resolucion`),
  KEY `nav` (`nav`(333)),
  KEY `nav_so` (`nav_so`),
  KEY `IP_pais` (`IP_pais`),
  KEY `IP_rango` (`IP_rango`),
  KEY `proxy` (`proxy`),
  KEY `IP_rango3` (`IP_rango3`)
) ENGINE=MyISAM AUTO_INCREMENT=331118 DEFAULT CHARSET=utf8;

#
# Source for table "votacion"
#

CREATE TABLE `votacion` (
  `ID` smallint(5) NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `estado` enum('ok','end','borrador') NOT NULL default 'borrador',
  `pregunta` varchar(255) NOT NULL default '',
  `descripcion` text NOT NULL,
  `respuestas` text NOT NULL,
  `num` smallint(5) NOT NULL default '0',
  `num_censo` int(11) unsigned default NULL,
  `tipo` enum('sondeo','referendum','parlamento','destituir','otorgar','cargo','elecciones') NOT NULL default 'sondeo',
  `tipo_voto` enum('estandar','3puntos','5puntos','8puntos','multiple') NOT NULL default 'estandar',
  `privacidad` enum('true','false') NOT NULL default 'true',
  `aleatorio` enum('true','false') NOT NULL default 'false',
  `ejecutar` text NOT NULL,
  `duracion` mediumint(9) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `acceso_votar` varchar(30) NOT NULL default 'ciudadanos_global',
  `acceso_cfg_votar` varchar(900) NOT NULL default '',
  `acceso_ver` varchar(255) NOT NULL default 'anonimos',
  `acceso_cfg_ver` varchar(900) NOT NULL default '',
  `debate_url` varchar(255) NOT NULL default '',
  `user_ID` mediumint(8) NOT NULL default '0',
  `votos_expire` smallint(5) unsigned NOT NULL default '0',
  `respuestas_desc` text NOT NULL,
  `cargo_ID` smallint(6) unsigned default NULL,
  PRIMARY KEY (`ID`),
  KEY `pais` (`pais`),
  KEY `time_expire` (`time_expire`),
  KEY `estado` (`estado`),
  KEY `tipo` (`tipo`),
  KEY `num` (`num`),
  KEY `votos_expire` (`votos_expire`),
  KEY `time` (`time`),
  KEY `user_ID` (`user_ID`),
  KEY `pregunta` (`pregunta`),
  KEY `acceso_votar` (`acceso_votar`),
  KEY `acceso_cfg_votar` (`acceso_cfg_votar`(333)),
  KEY `acceso_ver` (`acceso_ver`),
  KEY `acceso_cfg_ver` (`acceso_cfg_ver`(333)),
  KEY `tipo_voto` (`tipo_voto`),
  KEY `privacidad` (`privacidad`),
  KEY `aleatorio` (`aleatorio`),
  KEY `num_censo` (`num_censo`),
  KEY `cargo_ID` (`cargo_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3690 DEFAULT CHARSET=utf8;

#
# Source for table "votacion_argumentos"
#

CREATE TABLE `votacion_argumentos` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `ref_ID` mediumint(8) unsigned default NULL,
  `user_ID` mediumint(8) unsigned default NULL,
  `time` datetime default '0000-00-00 00:00:00',
  `sentido` varchar(255) NOT NULL default '',
  `texto` varchar(900) NOT NULL default '',
  `votos` mediumint(8) default '0',
  `votos_num` mediumint(9) default '0',
  PRIMARY KEY (`ID`),
  KEY `ref_ID` (`ref_ID`),
  KEY `user_ID` (`user_ID`),
  KEY `votos` (`votos`),
  KEY `votos_num` (`votos_num`),
  KEY `time` (`time`),
  KEY `texto` (`texto`(333)),
  KEY `sentido` (`sentido`)
) ENGINE=MyISAM AUTO_INCREMENT=841 DEFAULT CHARSET=utf8;

#
# Source for table "votacion_votos"
#

CREATE TABLE `votacion_votos` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `ref_ID` smallint(5) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime default NULL,
  `voto` varchar(300) NOT NULL default '0',
  `validez` enum('true','false') NOT NULL default 'true',
  `autentificado` enum('true','false') default 'false',
  `mensaje` varchar(500) NOT NULL default '',
  `comprobante` varchar(600) default NULL,
  PRIMARY KEY (`ID`),
  KEY `ref_ID` (`ref_ID`),
  KEY `user_ID` (`user_ID`),
  KEY `voto` (`voto`),
  KEY `validez` (`validez`),
  KEY `time` (`time`),
  KEY `autentificado` (`autentificado`),
  KEY `mensaje` (`mensaje`)
) ENGINE=MyISAM AUTO_INCREMENT=198709 DEFAULT CHARSET=latin1;

#
# Source for table "votos"
#

CREATE TABLE `votos` (
  `voto_ID` int(11) unsigned NOT NULL auto_increment,
  `pais` varchar(30) default NULL,
  `item_ID` int(11) unsigned NOT NULL default '0',
  `emisor_ID` mediumint(8) unsigned NOT NULL default '0',
  `receptor_ID` mediumint(9) unsigned default NULL,
  `voto` tinyint(3) NOT NULL,
  `tipo` enum('confianza','hilos','msg','argumentos') NOT NULL default 'confianza',
  `time` datetime NOT NULL,
  PRIMARY KEY (`voto_ID`),
  KEY `tipo` (`tipo`),
  KEY `emisor_ID` (`emisor_ID`),
  KEY `item_ID` (`item_ID`),
  KEY `pais` (`pais`),
  KEY `voto` (`voto`)
) ENGINE=MyISAM AUTO_INCREMENT=180147 DEFAULT CHARSET=latin1;

#
# Source for table "vp_foros"
#

CREATE TABLE `vp_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `subforo_ID` smallint(5) unsigned default NULL,
  `url` varchar(50) character set utf8 NOT NULL default '',
  `title` varchar(50) character set utf8 NOT NULL default '',
  `descripcion` varchar(255) character set utf8 NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') character set utf8 NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(900) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(900) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_escribir_msg` varchar(900) NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`),
  KEY `time` (`time`),
  KEY `acceso_leer` (`acceso_leer`(333)),
  KEY `acceso_escribir` (`acceso_escribir`(333)),
  KEY `acceso_cfg_leer` (`acceso_cfg_leer`(333)),
  KEY `acceso_cfg_escribir` (`acceso_cfg_escribir`(333))
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;

#
# Source for table "vp_foros_hilos"
#

CREATE TABLE `vp_foros_hilos` (
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
  `votos` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=10213 DEFAULT CHARSET=latin1;

#
# Source for table "vp_foros_msg"
#

CREATE TABLE `vp_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`),
  KEY `hilo_ID` (`hilo_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=98144 DEFAULT CHARSET=latin1;

#
# Source for table "vulcan_foros"
#

CREATE TABLE `vulcan_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '20',
  `estado` enum('ok') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(255) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(255) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

#
# Source for table "vulcan_foros_hilos"
#

CREATE TABLE `vulcan_foros_hilos` (
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
  `votos` mediumint(9) default '0',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=829 DEFAULT CHARSET=latin1;

#
# Source for table "vulcan_foros_msg"
#

CREATE TABLE `vulcan_foros_msg` (
  `ID` int(10) NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` mediumint(9) NOT NULL default '0',
  PRIMARY KEY (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=4808 DEFAULT CHARSET=latin1;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
