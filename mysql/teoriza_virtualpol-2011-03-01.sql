
CREATE TABLE `chats` (
  `chat_ID` smallint(5) unsigned NOT NULL auto_increment,
  `estado` enum('activo','bloqueado','en_proceso','expirado','borrado') NOT NULL default 'en_proceso',
  `pais` enum('POL','Hispania','Atlantis') NOT NULL,
  `url` varchar(90) NOT NULL,
  `titulo` varchar(90) NOT NULL,
  `user_ID` mediumint(8) unsigned NOT NULL,
  `acceso_leer` enum('privado','nivel','antiguedad','ciudadanos_pais','ciudadanos','anonimos') NOT NULL default 'anonimos',
  `acceso_escribir` enum('privado','nivel','antiguedad','ciudadanos_pais','ciudadanos','anonimos') NOT NULL default 'ciudadanos',
  `acceso_cfg_leer` varchar(500) default NULL,
  `acceso_cfg_escribir` varchar(500) default NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_last` datetime NOT NULL,
  `dias_expira` smallint(5) unsigned default NULL,
  `url_externa` varchar(500) default NULL,
  `stats_visitas` int(12) unsigned NOT NULL default '0',
  `stats_msgs` int(12) unsigned NOT NULL default '0',
  `GMT` tinyint(2) NOT NULL default '1',
  PRIMARY KEY  (`chat_ID`),
  UNIQUE KEY `url` (`url`),
  KEY `estado` (`estado`),
  KEY `pais` (`pais`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=latin1;
CREATE TABLE `chats_msg` (
  `msg_ID` int(12) unsigned NOT NULL auto_increment,
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
  KEY `tipo` (`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=699340 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=507 DEFAULT CHARSET=latin1;
CREATE TABLE `g_provincias` (
  `Id` int(11) NOT NULL auto_increment,
  `nombre` text NOT NULL,
  `propietario` text NOT NULL,
  `flags` int(11) default NULL,
  `puntos` int(11) NOT NULL default '1',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
CREATE TABLE `g_usuarios` (
  `Id` int(11) NOT NULL auto_increment,
  `id_usuario` int(11) NOT NULL default '0',
  `pais` text NOT NULL,
  `provincia` text,
  `ataque` int(11) NOT NULL default '10',
  `defensa` int(11) NOT NULL default '5',
  `salud` int(11) NOT NULL default '50',
  `ha_atacado` int(11) NOT NULL default '0',
  `flags` int(11) NOT NULL default '0',
  `experiencia` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE `hechos` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `time` date NOT NULL,
  `nick` varchar(14) character set utf8 NOT NULL default 'GONZO',
  `texto` varchar(2000) character set utf8 NOT NULL,
  `estado` enum('ok','del') character set utf8 NOT NULL default 'ok',
  `time2` datetime NOT NULL,
  `pais` enum('VirtualPol','POL','VULCAN','Hispania','Atlantis') character set utf8 NOT NULL default 'VirtualPol',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=282 DEFAULT CHARSET=latin1;
CREATE TABLE `mails` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `email` varchar(140) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=690 DEFAULT CHARSET=latin1;
CREATE TABLE `mensajes` (
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
) ENGINE=MyISAM AUTO_INCREMENT=85591 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_ban` (
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
) ENGINE=MyISAM AUTO_INCREMENT=674 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_blog` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `vp_blog_com` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `vp_blog_post` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `vp_cat` (
  `ID` tinyint(3) NOT NULL auto_increment,
  `url` varchar(80) NOT NULL default '',
  `nombre` varchar(80) NOT NULL default '',
  `num` smallint(5) NOT NULL default '0',
  `nivel` tinyint(3) NOT NULL default '0',
  `tipo` enum('empresas','docs','cargos') NOT NULL default 'empresas',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `url` (`url`,`tipo`),
  KEY `tipo` (`tipo`),
  KEY `orden` (`orden`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_config` (
  `ID` tinyint(3) NOT NULL auto_increment,
  `dato` varchar(30) NOT NULL default '',
  `valor` text NOT NULL,
  `autoload` enum('si','no') NOT NULL default 'no',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `dato` (`dato`),
  KEY `autoload` (`autoload`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=373 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_diputados` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_docs` (
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
) ENGINE=MyISAM AUTO_INCREMENT=728 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1506 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=449 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_estudios` (
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
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_estudios_users` (
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
) ENGINE=MyISAM AUTO_INCREMENT=16702 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=1588 DEFAULT CHARSET=latin1 COMMENT='(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)';
CREATE TABLE `vp_foros` (
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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
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
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `url` (`url`),
  KEY `sub_ID` (`sub_ID`),
  KEY `time_last` (`time_last`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=5965 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_foros_msg` (
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
) ENGINE=MyISAM AUTO_INCREMENT=55927 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_log` (
  `ID` bigint(12) unsigned NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `user_ID2` mediumint(8) unsigned NOT NULL default '0',
  `accion` tinyint(3) unsigned NOT NULL default '0',
  `dato` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=24184 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_mapa` (
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
) ENGINE=MyISAM AUTO_INCREMENT=9591 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_mercado` (
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=219 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=214 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_pujas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `mercado_ID` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `pols` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=10429 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=1002 DEFAULT CHARSET=latin1;
CREATE TABLE `vp_ref_votos` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `user_ID` mediumint(8) NOT NULL default '0',
  `ref_ID` smallint(5) NOT NULL default '0',
  `voto` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=15945 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=318115 DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM AUTO_INCREMENT=8479 DEFAULT CHARSET=latin1;
CREATE TABLE `stats` (
  `stats_ID` smallint(5) unsigned NOT NULL auto_increment,
  `pais` enum('POL','VULCAN','Hispania','Atlantis') NOT NULL,
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
  PRIMARY KEY  (`stats_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=1867 DEFAULT CHARSET=latin1;
CREATE TABLE `trolls` (
  `Id` int(11) NOT NULL auto_increment,
  `nick` varchar(18) default '',
  `puntos` int(11) default NULL,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=159 DEFAULT CHARSET=utf8;
CREATE TABLE `users` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(14) NOT NULL default '',
  `pais` enum('ninguno','POL','VULCAN','Hispania','Atlantis') NOT NULL default 'POL',
  `pols` int(10) NOT NULL default '0',
  `fecha_registro` datetime NOT NULL default '0000-00-00 00:00:00',
  `fecha_last` datetime NOT NULL default '0000-00-00 00:00:00',
  `partido_afiliado` smallint(6) NOT NULL default '0',
  `estado` enum('turista','ciudadano','expulsado','validar') NOT NULL default 'validar',
  `nivel` tinyint(3) unsigned NOT NULL default '1',
  `email` varchar(255) NOT NULL default '',
  `pass` varchar(64) NOT NULL,
  `num_elec` tinyint(3) unsigned NOT NULL default '0',
  `online` int(10) unsigned NOT NULL default '0',
  `fecha_init` datetime NOT NULL default '0000-00-00 00:00:00',
  `ref` mediumint(8) unsigned NOT NULL default '0',
  `ref_num` mediumint(8) unsigned NOT NULL default '0',
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
  `bando` varchar(255) default NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=202169 DEFAULT CHARSET=latin1;
CREATE TABLE `v_bandos` (
  `Id` int(11) NOT NULL auto_increment,
  `nombre` varchar(255) default NULL,
  `ID_presidente` varchar(255) default NULL,
  `descripcion` text,
  PRIMARY KEY  (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE `votos` (
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
) ENGINE=MyISAM AUTO_INCREMENT=13387 DEFAULT CHARSET=latin1;