/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

CREATE TABLE `15m_elec` (
  `ID` smallint(5) NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo` enum('pres','parl') NOT NULL default 'pres',
  `num_votantes` mediumint(8) NOT NULL default '0',
  `escrutinio` text NOT NULL,
  `num_votos` smallint(5) NOT NULL default '0',
  `pols_init` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
CREATE TABLE `15m_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2213 DEFAULT CHARSET=latin1;
CREATE TABLE `15m_examenes` (
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
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=latin1 COMMENT='(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num';
CREATE TABLE `15m_examenes_preg` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `examen_ID` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `examen_ID` (`examen_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=latin1 COMMENT='(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)';
CREATE TABLE `15m_foros` (
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
CREATE TABLE `15m_foros_hilos` (
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=1099 DEFAULT CHARSET=latin1;
CREATE TABLE `15m_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=8615 DEFAULT CHARSET=latin1;
CREATE TABLE `15m_partidos` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_presidente` mediumint(7) NOT NULL default '0',
  `fecha_creacion` datetime NOT NULL default '0000-00-00 00:00:00',
  `siglas` varchar(12) NOT NULL default '',
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` enum('ok') NOT NULL default 'ok',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `siglas` (`siglas`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
CREATE TABLE `15m_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_cuentas` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(25) NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `exenta_impuestos` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `user_ID` (`user_ID`),
  KEY `nivel` (`nivel`)
) ENGINE=MyISAM AUTO_INCREMENT=289 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_docs` (
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
) ENGINE=MyISAM AUTO_INCREMENT=105 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_elec` (
  `ID` smallint(5) NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo` enum('pres','parl') NOT NULL default 'pres',
  `num_votantes` mediumint(8) NOT NULL default '0',
  `escrutinio` text NOT NULL,
  `num_votos` smallint(5) NOT NULL default '0',
  `pols_init` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=295 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_empresas` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1268 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_examenes` (
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
) ENGINE=MyISAM AUTO_INCREMENT=74 DEFAULT CHARSET=latin1 COMMENT='(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num';
CREATE TABLE `atlantis_examenes_preg` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `examen_ID` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `examen_ID` (`examen_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1787 DEFAULT CHARSET=latin1 COMMENT='(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)';
CREATE TABLE `atlantis_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(255) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(255) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_foros_hilos` (
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=1025 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` mediumint(9) default '0',
  PRIMARY KEY  (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=8723 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_mapa` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
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
  `color` char(3) NOT NULL default '',
  `estado` enum('p','v','e') NOT NULL default 'p',
  `superficie` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `pos_x` (`pos_x`),
  KEY `pos_y` (`pos_y`),
  KEY `estado` (`estado`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_partidos` (
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
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_pujas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `mercado_ID` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `pols` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=658 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_ref` (
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
) ENGINE=MyISAM AUTO_INCREMENT=508 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4889 DEFAULT CHARSET=latin1;
CREATE TABLE `atlantis_transacciones` (
  `ID` int(12) unsigned NOT NULL auto_increment,
  `pols` int(10) NOT NULL default '0',
  `emisor_ID` mediumint(8) NOT NULL default '0',
  `receptor_ID` mediumint(8) NOT NULL default '0',
  `concepto` varchar(90) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `emisor_ID` (`emisor_ID`),
  KEY `receptor_ID` (`receptor_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=5862 DEFAULT CHARSET=latin1;
CREATE TABLE `cargos` (
  `ID` smallint(6) NOT NULL auto_increment,
  `cargo_ID` smallint(6) unsigned NOT NULL default '0',
  `asigna` smallint(5) NOT NULL default '7',
  `nombre` varchar(30) character set utf8 NOT NULL default '',
  `pais` enum('Hispania','RSSV','15M') character set utf8 NOT NULL default '15M',
  `nombre_extra` varchar(255) character set utf8 NOT NULL default '',
  `nivel` tinyint(3) NOT NULL default '1',
  `num` smallint(5) NOT NULL default '0',
  `salario` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `nivel` (`nivel`),
  KEY `nombre` (`nombre`),
  KEY `asigna` (`asigna`),
  KEY `cargo_ID` (`cargo_ID`),
  KEY `pais` (`pais`)
) ENGINE=MyISAM AUTO_INCREMENT=315 DEFAULT CHARSET=latin1;
CREATE TABLE `cargos_users` (
  `ID` bigint(20) NOT NULL auto_increment,
  `cargo_ID` smallint(5) NOT NULL default '0',
  `pais` enum('15M','Hispania','RSSV') NOT NULL default '15M',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cargo` enum('true','false') NOT NULL default 'false',
  `aprobado` enum('ok','no') NOT NULL default 'ok',
  `nota` decimal(3,1) unsigned NOT NULL default '0.0',
  PRIMARY KEY  (`ID`),
  KEY `cargo` (`cargo`),
  KEY `user_ID` (`user_ID`),
  KEY `cargo_ID` (`cargo_ID`),
  KEY `aprobado` (`aprobado`),
  KEY `pais` (`pais`),
  KEY `nota` (`nota`)
) ENGINE=MyISAM AUTO_INCREMENT=8174 DEFAULT CHARSET=utf8;
CREATE TABLE `cat` (
  `ID` smallint(6) unsigned NOT NULL auto_increment,
  `pais` enum('15M','Hispania','RSSV') default NULL,
  `url` varchar(80) NOT NULL default '',
  `nombre` varchar(80) NOT NULL default '',
  `num` smallint(6) unsigned NOT NULL default '0',
  `nivel` tinyint(3) unsigned NOT NULL default '0',
  `tipo` enum('empresas','docs','cargos') NOT NULL default 'docs',
  `orden` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `url` (`url`,`nivel`,`tipo`,`orden`,`nombre`,`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `chats` (
  `chat_ID` smallint(5) unsigned NOT NULL auto_increment,
  `estado` enum('activo','bloqueado','en_proceso','expirado','borrado') character set utf8 NOT NULL default 'en_proceso',
  `pais` enum('VP','15M','Hispania','RSSV') NOT NULL default 'VP',
  `url` varchar(90) character set utf8 NOT NULL,
  `titulo` varchar(90) character set utf8 NOT NULL,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `admin` varchar(900) NOT NULL default '',
  `acceso_leer` varchar(30) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(30) character set utf8 default 'ciudadanos',
  `acceso_escribir_ex` varchar(30) NOT NULL default '',
  `acceso_cfg_leer` varchar(900) character set utf8 default NULL,
  `acceso_cfg_escribir` varchar(900) character set utf8 default NULL,
  `acceso_cfg_escribir_ex` varchar(900) NOT NULL default '',
  `fecha_creacion` datetime NOT NULL,
  `fecha_last` datetime NOT NULL,
  `dias_expira` smallint(5) unsigned default NULL,
  `url_externa` varchar(500) character set utf8 default NULL,
  `stats_visitas` int(12) unsigned NOT NULL default '0',
  `stats_msgs` int(12) unsigned NOT NULL default '0',
  `GMT` tinyint(2) NOT NULL default '1',
  PRIMARY KEY  (`chat_ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`),
  KEY `pais` (`pais`),
  KEY `acceso_leer` (`acceso_leer`),
  KEY `acceso_escribir` (`acceso_escribir`),
  KEY `acceso_cfg_leer` (`acceso_cfg_leer`(333)),
  KEY `acceso_cfg_escribir` (`acceso_cfg_escribir`(333)),
  KEY `stats_msgs` (`stats_msgs`),
  KEY `fecha_last` (`fecha_last`),
  KEY `acceso_escribir_ex` (`acceso_escribir_ex`),
  KEY `acceso_cfg_escribir_ex` (`acceso_cfg_escribir_ex`)
) ENGINE=MyISAM AUTO_INCREMENT=523 DEFAULT CHARSET=latin1;
CREATE TABLE `chats_msg` (
  `msg_ID` mediumint(8) unsigned NOT NULL auto_increment,
  `chat_ID` smallint(5) unsigned NOT NULL,
  `nick` varchar(32) character set utf8 NOT NULL,
  `msg` varchar(900) character set utf8 NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(6) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') character set utf8 NOT NULL default 'm',
  `IP` bigint(12) default NULL,
  PRIMARY KEY  (`msg_ID`),
  KEY `chat_ID` (`chat_ID`),
  KEY `nick` (`nick`),
  KEY `time` (`time`),
  KEY `cargo` (`cargo`),
  KEY `user_ID` (`user_ID`),
  KEY `tipo` (`tipo`),
  KEY `msg` (`msg`(333)),
  KEY `IP` (`IP`)
) ENGINE=MyISAM AUTO_INCREMENT=2974784 DEFAULT CHARSET=latin1;
CREATE TABLE `config` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `pais` enum('15M','Hispania','RSSV','VP') NOT NULL default '15M',
  `dato` varchar(100) NOT NULL default '',
  `valor` text NOT NULL,
  `autoload` enum('si','no') NOT NULL default 'no',
  PRIMARY KEY  (`ID`),
  KEY `dato` (`dato`),
  KEY `valor` (`valor`(10)),
  KEY `autoload` (`autoload`),
  KEY `pais` (`pais`)
) ENGINE=MyISAM AUTO_INCREMENT=263 DEFAULT CHARSET=utf8;
CREATE TABLE `docs` (
  `ID` smallint(5) NOT NULL auto_increment,
  `pais` enum('sistema','VP','15M','Hispania','RSSV') character set utf8 NOT NULL default 'VP',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  `text_backup` longtext NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `estado` enum('ok','del','borrador') NOT NULL default 'ok',
  `cat_ID` tinyint(3) NOT NULL default '0',
  `acceso_leer` varchar(30) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(30) character set utf8 NOT NULL default 'privado',
  `acceso_cfg_leer` varchar(800) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(800) character set utf8 NOT NULL default '',
  `version` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `estado` (`estado`),
  KEY `cat_ID` (`cat_ID`),
  KEY `url` (`url`)
) ENGINE=MyISAM AUTO_INCREMENT=828 DEFAULT CHARSET=latin1;
CREATE TABLE `empresa_acciones` (
  `id` int(11) NOT NULL auto_increment,
  `ID_empresa` mediumint(9) unsigned NOT NULL default '0',
  `nick` varchar(300) character set utf8 collate utf8_spanish_ci NOT NULL,
  `pais` varchar(300) character set utf8 collate utf8_spanish_ci NOT NULL,
  `num_acciones` int(11) NOT NULL default '100',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `expulsiones` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1247 DEFAULT CHARSET=latin1;
CREATE TABLE `grupos` (
  `grupo_ID` int(11) unsigned NOT NULL auto_increment,
  `pais` enum('VP','15M','Hispania','RSSV') NOT NULL default 'VP',
  `nombre` varchar(255) NOT NULL default '',
  `num` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`grupo_ID`),
  KEY `num` (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;
CREATE TABLE `hechos` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `time` date NOT NULL,
  `nick` varchar(14) character set utf8 NOT NULL default 'GONZO',
  `texto` varchar(2000) character set utf8 NOT NULL,
  `estado` enum('ok','del') character set utf8 NOT NULL default 'ok',
  `time2` datetime NOT NULL,
  `pais` enum('VirtualPol','POL','15M','VULCAN','Hispania','Atlantis','VP','Desarrollo','RSSV') character set utf8 NOT NULL default 'VirtualPol',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=344 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_cuentas` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(25) NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `exenta_impuestos` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `user_ID` (`user_ID`),
  KEY `nivel` (`nivel`)
) ENGINE=MyISAM AUTO_INCREMENT=497 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_docs` (
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
) ENGINE=MyISAM AUTO_INCREMENT=376 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_elec` (
  `ID` smallint(5) NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo` enum('pres','parl') NOT NULL default 'pres',
  `num_votantes` mediumint(8) NOT NULL default '0',
  `escrutinio` text NOT NULL,
  `num_votos` smallint(5) NOT NULL default '0',
  `pols_init` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1558 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_empresas` (
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
) ENGINE=MyISAM AUTO_INCREMENT=654 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_examenes` (
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
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_examenes_preg` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `examen_ID` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `examen_ID` (`examen_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3142 DEFAULT CHARSET=latin1;
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
  `acceso_leer` varchar(255) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(255) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_escribir_msg` varchar(255) NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir_msg` varchar(900) NOT NULL default '',
  `limite` tinyint(3) unsigned NOT NULL default '8',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=4526 DEFAULT CHARSET=utf8;
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
  PRIMARY KEY  (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=39235 DEFAULT CHARSET=utf8;
CREATE TABLE `hispania_mapa` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
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
  `color` char(3) NOT NULL default '',
  `estado` enum('p','v','e') NOT NULL default 'p',
  `superficie` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `pos_x` (`pos_x`),
  KEY `pos_y` (`pos_y`),
  KEY `estado` (`estado`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_partidos` (
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
) ENGINE=MyISAM AUTO_INCREMENT=250 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=504 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_pujas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `mercado_ID` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `pols` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=5844 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_ref` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1363 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=16573 DEFAULT CHARSET=latin1;
CREATE TABLE `hispania_transacciones` (
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
) ENGINE=MyISAM AUTO_INCREMENT=56419 DEFAULT CHARSET=latin1;
CREATE TABLE `kicks` (
  `ID` mediumint(9) unsigned NOT NULL auto_increment,
  `pais` enum('15M','Hispania','RSSV') default NULL,
  `user_ID` mediumint(9) unsigned NOT NULL default '0',
  `autor` mediumint(8) unsigned NOT NULL default '0',
  `expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `razon` varchar(160) NOT NULL default '',
  `estado` enum('activo','inactivo','expulsado','cancelado') NOT NULL default 'activo',
  `tiempo` varchar(20) NOT NULL default '0',
  `IP` varchar(12) NOT NULL default '0',
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `motivo` text NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `pais` (`pais`,`user_ID`,`estado`,`expire`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `log` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pais` enum('15M','RSSV','Hispania') default NULL,
  `nick` varchar(20) NOT NULL default '',
  `accion` varchar(900) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `pais` (`pais`),
  KEY `user_ID` (`user_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=408 DEFAULT CHARSET=utf8;
CREATE TABLE `mensajes` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `envia_ID` mediumint(8) unsigned NOT NULL default '0',
  `recibe_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `leido` enum('0','1') NOT NULL default '0',
  `cargo` smallint(5) NOT NULL default '0',
  `recibe_masivo` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `envia_ID` (`envia_ID`),
  KEY `recibe_ID` (`recibe_ID`),
  KEY `leido` (`leido`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=181089 DEFAULT CHARSET=utf8;
CREATE TABLE `notificaciones` (
  `noti_ID` int(11) unsigned NOT NULL auto_increment,
  `time` timestamp NULL default CURRENT_TIMESTAMP,
  `emisor` enum('sistema','15M','Hispania','RSSV') NOT NULL default 'sistema',
  `visto` enum('true','false') NOT NULL default 'false',
  `user_ID` mediumint(8) NOT NULL default '0',
  `texto` varchar(60) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`noti_ID`),
  KEY `time` (`time`),
  KEY `visto` (`visto`),
  KEY `user_ID` (`user_ID`),
  KEY `url` (`url`),
  KEY `texto` (`texto`)
) ENGINE=MyISAM AUTO_INCREMENT=44216 DEFAULT CHARSET=utf8;
CREATE TABLE `pol_cuentas` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(25) NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `exenta_impuestos` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `user_ID` (`user_ID`),
  KEY `nivel` (`nivel`)
) ENGINE=MyISAM AUTO_INCREMENT=377 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_docs` (
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
) ENGINE=MyISAM AUTO_INCREMENT=729 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_elec` (
  `ID` smallint(5) NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo` enum('pres','parl') NOT NULL default 'pres',
  `num_votantes` mediumint(8) NOT NULL default '0',
  `escrutinio` text NOT NULL,
  `num_votos` smallint(5) NOT NULL default '0',
  `pols_init` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1560 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_empresas` (
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
) ENGINE=MyISAM AUTO_INCREMENT=459 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_examenes` (
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
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=latin1 COMMENT='(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num';
CREATE TABLE `pol_examenes_preg` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `examen_ID` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `examen_ID` (`examen_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1595 DEFAULT CHARSET=latin1 COMMENT='(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)';
CREATE TABLE `pol_foros` (
  `ID` smallint(5) NOT NULL auto_increment,
  `url` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `descripcion` varchar(255) NOT NULL default '',
  `acceso` tinyint(3) unsigned NOT NULL default '1',
  `time` smallint(3) NOT NULL default '1',
  `estado` enum('ok','eliminado') NOT NULL default 'ok',
  `acceso_msg` tinyint(3) unsigned NOT NULL default '1',
  `acceso_leer` varchar(255) character set utf8 NOT NULL default 'anonimos',
  `acceso_escribir` varchar(255) character set utf8 NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(900) character set utf8 NOT NULL default '',
  `acceso_cfg_escribir` varchar(900) character set utf8 NOT NULL default '',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=5995 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY  (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=56233 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_mapa` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `pos_x` tinyint(2) NOT NULL default '1',
  `pos_y` tinyint(2) NOT NULL default '1',
  `size_x` tinyint(2) NOT NULL default '1',
  `size_y` tinyint(2) NOT NULL default '1',
  `user_ID` mediumint(8) NOT NULL default '1',
  `nick` varchar(255) default '',
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `pol_partidos` (
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
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=214 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_pujas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `mercado_ID` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `pols` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=10452 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_ref` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1005 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=15996 DEFAULT CHARSET=latin1;
CREATE TABLE `pol_transacciones` (
  `ID` int(12) unsigned NOT NULL auto_increment,
  `pols` int(10) NOT NULL default '0',
  `emisor_ID` mediumint(8) NOT NULL default '0',
  `receptor_ID` mediumint(8) NOT NULL default '0',
  `concepto` varchar(90) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `emisor_ID` (`emisor_ID`),
  KEY `receptor_ID` (`receptor_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=318815 DEFAULT CHARSET=latin1;
CREATE TABLE `referencias` (
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
) ENGINE=MyISAM AUTO_INCREMENT=9454 DEFAULT CHARSET=latin1;
CREATE TABLE `rssv_elec` (
  `ID` smallint(5) NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo` enum('pres','parl') NOT NULL default 'pres',
  `num_votantes` mediumint(8) NOT NULL default '0',
  `escrutinio` text NOT NULL,
  `num_votos` smallint(5) NOT NULL default '0',
  `pols_init` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
CREATE TABLE `rssv_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;
CREATE TABLE `rssv_examenes` (
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
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=latin1 COMMENT='(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num';
CREATE TABLE `rssv_examenes_preg` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `examen_ID` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `examen_ID` (`examen_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1396 DEFAULT CHARSET=latin1 COMMENT='(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)';
CREATE TABLE `rssv_foros` (
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`),
  KEY `time` (`time`),
  KEY `acceso_leer` (`acceso_leer`(333)),
  KEY `acceso_escribir` (`acceso_escribir`(333)),
  KEY `acceso_cfg_leer` (`acceso_cfg_leer`(333)),
  KEY `acceso_cfg_escribir` (`acceso_cfg_escribir`(333))
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
CREATE TABLE `rssv_foros_hilos` (
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=305 DEFAULT CHARSET=latin1;
CREATE TABLE `rssv_foros_msg` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `hilo_ID` mediumint(8) NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `cargo` tinyint(3) unsigned NOT NULL default '1',
  `estado` enum('ok','borrado') NOT NULL default 'ok',
  `time2` datetime NOT NULL default '0000-00-00 00:00:00',
  `votos` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`),
  KEY `hilo_ID` (`hilo_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1830 DEFAULT CHARSET=latin1;
CREATE TABLE `rssv_partidos` (
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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
CREATE TABLE `rssv_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
CREATE TABLE `rssv_ref` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `rssv_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `stats` (
  `stats_ID` smallint(5) unsigned NOT NULL auto_increment,
  `pais` enum('POL','15M','15MBCN','15MMAD','VULCAN','Hispania','Atlantis','VP','RSSV') character set utf8 NOT NULL default 'POL',
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
  `autentificados` mediumint(9) default NULL,
  PRIMARY KEY  (`stats_ID`),
  KEY `time` (`time`),
  KEY `pais` (`pais`)
) ENGINE=MyISAM AUTO_INCREMENT=2778 DEFAULT CHARSET=latin1;
CREATE TABLE `users` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(18) NOT NULL default '',
  `pais` enum('ninguno','VP','15M','Hispania','RSSV') NOT NULL default 'ninguno',
  `estado` enum('turista','ciudadano','expulsado','validar') NOT NULL default 'validar',
  `nivel` tinyint(3) unsigned NOT NULL default '1',
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `cargos` varchar(400) NOT NULL default '',
  `grupos` varchar(400) NOT NULL default '',
  `examenes` varchar(400) NOT NULL default '',
  `voto_confianza` smallint(5) NOT NULL default '0',
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
  `ser_SC` enum('true','false') NOT NULL default 'false',
  `nota` decimal(3,1) NOT NULL default '0.0',
  `ref` varchar(25) NOT NULL default '',
  `ref_num` mediumint(8) unsigned NOT NULL default '0',
  `IP` varchar(12) NOT NULL default '0',
  `avatar` enum('true','false') NOT NULL default 'false',
  `text` varchar(2300) NOT NULL default '',
  `nav` varchar(500) NOT NULL,
  `avatar_localdir` varchar(100) NOT NULL,
  `host` varchar(150) NOT NULL,
  `hosts` varchar(2000) default '',
  `IP_proxy` varchar(150) NOT NULL,
  `geo` varchar(200) NOT NULL,
  `dnie` enum('true','false') default 'false',
  `dnie_check` varchar(400) default NULL,
  `bando` varchar(255) default NULL,
  `nota_SC` varchar(500) NOT NULL default '',
  `traza` varchar(600) NOT NULL default '',
  `datos` varchar(9999) NOT NULL default '',
  PRIMARY KEY  (`ID`),
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
  KEY `examenes` (`examenes`(333))
) ENGINE=MyISAM AUTO_INCREMENT=211806 DEFAULT CHARSET=utf8;
CREATE TABLE `votacion` (
  `ID` smallint(5) NOT NULL auto_increment,
  `pais` enum('VP','15M','POL','Hispania','VULCAN','Atlantis','RSSV') NOT NULL default 'VP',
  `pregunta` varchar(255) NOT NULL default '',
  `descripcion` text NOT NULL,
  `respuestas` text NOT NULL,
  `respuestas_desc` text NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_ID` mediumint(8) NOT NULL default '0',
  `estado` enum('ok','end','borrador') NOT NULL default 'borrador',
  `num` smallint(5) NOT NULL default '0',
  `tipo` enum('sondeo','referendum','parlamento','destituir','otorgar','cargo') NOT NULL default 'sondeo',
  `debate_url` varchar(255) NOT NULL default '',
  `acceso_votar` varchar(30) NOT NULL default 'ciudadanos_pais',
  `acceso_cfg_votar` varchar(900) NOT NULL default '',
  `acceso_ver` varchar(255) NOT NULL default 'anonimos',
  `acceso_cfg_ver` varchar(900) NOT NULL default '',
  `ejecutar` varchar(255) NOT NULL default '',
  `votos_expire` smallint(5) unsigned NOT NULL default '0',
  `tipo_voto` enum('estandar','3puntos','5puntos','8puntos','multiple') NOT NULL default 'estandar',
  `privacidad` enum('true','false') NOT NULL default 'true',
  `aleatorio` enum('true','false') NOT NULL default 'false',
  `duracion` mediumint(9) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `pais` (`pais`),
  KEY `time_expire` (`time_expire`),
  KEY `estado` (`estado`),
  KEY `tipo` (`tipo`),
  KEY `num` (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=1994 DEFAULT CHARSET=utf8;
CREATE TABLE `votacion_votos` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `ref_ID` smallint(5) unsigned NOT NULL default '0',
  `time` datetime default NULL,
  `voto` varchar(300) character set utf8 NOT NULL default '0',
  `validez` enum('true','false') character set utf8 NOT NULL default 'true',
  `autentificado` enum('true','false') default 'false',
  `mensaje` varchar(500) NOT NULL default '',
  `comprobante` varchar(600) character set utf8 default NULL,
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`),
  KEY `user_ID` (`user_ID`),
  KEY `voto` (`voto`),
  KEY `validez` (`validez`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=87323 DEFAULT CHARSET=latin1;
CREATE TABLE `votos` (
  `voto_ID` int(11) unsigned NOT NULL auto_increment,
  `pais` enum('all','VP','15M','Hispania','RSSV') character set utf8 NOT NULL default 'all',
  `item_ID` int(11) unsigned NOT NULL default '0',
  `emisor_ID` mediumint(8) unsigned NOT NULL default '0',
  `voto` tinyint(3) NOT NULL,
  `tipo` enum('confianza','hilos','msg') character set utf8 NOT NULL default 'confianza',
  `time` datetime NOT NULL,
  PRIMARY KEY  (`voto_ID`),
  KEY `tipo` (`tipo`),
  KEY `emisor_ID` (`emisor_ID`),
  KEY `item_ID` (`item_ID`),
  KEY `pais` (`pais`),
  KEY `voto` (`voto`)
) ENGINE=MyISAM AUTO_INCREMENT=79969 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_cuentas` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `nombre` varchar(25) NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `pols` int(10) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `exenta_impuestos` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `user_ID` (`user_ID`),
  KEY `nivel` (`nivel`)
) ENGINE=MyISAM AUTO_INCREMENT=1002 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_elec` (
  `ID` smallint(5) NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo` enum('pres','parl') NOT NULL default 'pres',
  `num_votantes` mediumint(8) NOT NULL default '0',
  `escrutinio` text NOT NULL,
  `num_votos` smallint(5) NOT NULL default '0',
  `pols_init` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=386 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_empresas` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1665 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_examenes` (
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
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=latin1 COMMENT='(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num';
CREATE TABLE `vp_examenes_preg` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `examen_ID` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `examen_ID` (`examen_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1336 DEFAULT CHARSET=latin1 COMMENT='(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)';
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`),
  KEY `time` (`time`),
  KEY `acceso_leer` (`acceso_leer`(333)),
  KEY `acceso_escribir` (`acceso_escribir`(333)),
  KEY `acceso_cfg_leer` (`acceso_cfg_leer`(333)),
  KEY `acceso_cfg_escribir` (`acceso_cfg_escribir`(333))
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=10213 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`),
  KEY `hilo_ID` (`hilo_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=98144 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_mapa` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `pos_x` tinyint(2) NOT NULL default '1',
  `pos_y` tinyint(2) NOT NULL default '1',
  `size_x` tinyint(2) NOT NULL default '1',
  `size_y` tinyint(2) NOT NULL default '1',
  `user_ID` mediumint(8) NOT NULL default '1',
  `nick` varchar(20) NOT NULL default '',
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
) ENGINE=MyISAM AUTO_INCREMENT=1014 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_partidos` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1372 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=655 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_pujas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `mercado_ID` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `pols` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=5851 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_ref` (
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
) ENGINE=MyISAM AUTO_INCREMENT=460 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11773 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_transacciones` (
  `ID` int(12) unsigned NOT NULL auto_increment,
  `pols` int(10) NOT NULL default '0',
  `emisor_ID` mediumint(8) NOT NULL default '0',
  `receptor_ID` mediumint(8) NOT NULL default '0',
  `concepto` varchar(90) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `emisor_ID` (`emisor_ID`),
  KEY `receptor_ID` (`receptor_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=75605 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_cuentas` (
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
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_docs` (
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
) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_elec` (
  `ID` smallint(5) NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `tipo` enum('pres','parl') NOT NULL default 'pres',
  `num_votantes` mediumint(8) NOT NULL default '0',
  `escrutinio` text NOT NULL,
  `num_votos` smallint(5) NOT NULL default '0',
  `pols_init` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=194 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_empresas` (
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
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_examenes` (
  `ID` smallint(5) NOT NULL auto_increment,
  `titulo` varchar(100) character set utf8 NOT NULL default '',
  `descripcion` text character set utf8 NOT NULL,
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cargo_ID` smallint(5) NOT NULL default '0',
  `nota` varchar(5) character set utf8 NOT NULL default '5.0',
  `num_preguntas` smallint(5) NOT NULL default '10',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `titulo` (`titulo`),
  KEY `nota` (`nota`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_examenes_preg` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `examen_ID` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `pregunta` text NOT NULL,
  `respuestas` text NOT NULL,
  `tiempo` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `examen_ID` (`examen_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=669 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=829 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY  (`ID`),
  KEY `foro_ID` (`hilo_ID`),
  KEY `time` (`time`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=4808 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_mapa` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
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
  `color` char(3) NOT NULL default '',
  `estado` enum('p','v','e') NOT NULL default 'p',
  `superficie` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `pos_x` (`pos_x`),
  KEY `pos_y` (`pos_y`),
  KEY `estado` (`estado`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_partidos` (
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
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_pujas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `mercado_ID` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `pols` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=700 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_ref` (
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
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1043 DEFAULT CHARSET=latin1;
CREATE TABLE `vulcan_transacciones` (
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
) ENGINE=MyISAM AUTO_INCREMENT=12630 DEFAULT CHARSET=latin1;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
