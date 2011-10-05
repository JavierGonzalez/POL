/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

CREATE TABLE `chats` (
  `chat_ID` smallint(5) unsigned NOT NULL auto_increment,
  `estado` enum('activo','bloqueado','en_proceso','expirado','borrado') NOT NULL default 'en_proceso',
  `pais` enum('POL','Hispania','VP') character set utf8 NOT NULL default 'POL',
  `url` varchar(20) NOT NULL,
  `titulo` varchar(150) NOT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

INSERT INTO `chats` VALUES (1,'activo','POL','pol','Plaza de POL',0,'anonimos','anonimos','','','2010-04-17 18:50:57','2011-06-19 08:38:02',NULL,NULL,2125,352,1);
INSERT INTO `chats` VALUES (2,'activo','Hispania','hispania','Plaza de Hispania',0,'anonimos','ciudadanos',NULL,NULL,'2010-04-17 18:51:06','2011-06-19 11:30:18',NULL,NULL,352,6,1);
INSERT INTO `chats` VALUES (4,'activo','Hispania','chat-hispaniol','Chat Hispaniol',200414,'anonimos','ciudadanos',NULL,NULL,'2010-04-19 16:19:01','2011-06-16 12:20:59',15,NULL,24,0,1);
INSERT INTO `chats` VALUES (6,'activo','POL','prueba','Prueba',200417,'anonimos','ciudadanos',NULL,NULL,'2010-04-26 21:37:52','2011-05-31 09:35:47',15,NULL,34,1,1);
CREATE TABLE `chats_msg` (
  `msg_ID` int(12) unsigned NOT NULL auto_increment,
  `chat_ID` smallint(5) unsigned NOT NULL,
  `nick` varchar(14) NOT NULL,
  `msg` varchar(900) NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `cargo` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(6) unsigned NOT NULL default '0',
  `tipo` enum('m','p','e','c') NOT NULL default 'm',
  `IP` bigint(12) default NULL,
  PRIMARY KEY  (`msg_ID`),
  KEY `chat_ID` (`chat_ID`,`nick`,`time`,`cargo`,`user_ID`,`tipo`)
) ENGINE=MyISAM AUTO_INCREMENT=2434 DEFAULT CHARSET=latin1;

INSERT INTO `chats_msg` VALUES (1867,4,'oportunista','Â¿?','2011-06-21 20:32:05',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1868,4,'oportunista','la plaza secreta :D','2011-06-21 20:43:35',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1869,4,'oportunista','xD','2011-06-21 20:43:38',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1870,4,'oportunista',':roto2:','2011-06-21 20:43:41',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1871,4,'Honse',':roto2:','2011-06-21 20:49:59',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1872,4,'Honse','lugar perfecto para las conspiraciones :d','2011-06-21 20:50:15',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1873,4,'oportunista','aquÃ­ supuestamente habrÃ­a que acceder con los usuarios registrados en vp-dev','2011-06-21 20:50:38',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1874,4,'oportunista','sÃ­ :D','2011-06-21 20:50:56',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1875,4,'Brook','Ã±e, esto es dev?','2011-06-23 11:48:44',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1876,4,'Honse','si :roto2:','2011-06-23 11:52:33',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1877,4,'Honse','pero parece mas bien una plaza aparte, ya que entra con el mismo login que vp.virtualpol','2011-06-23 11:52:59',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1878,4,'Brook','hum, pero lo de las votaciones no vsa','2011-06-23 12:00:40',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1879,4,'jechenique',':O','2011-06-25 03:39:05',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1880,4,'oportunista',':roto2:','2011-06-25 22:09:23',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1881,4,'Gak','oportunista','2011-06-25 22:09:43',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1882,4,'Gak','espera que ahora vengo','2011-06-25 22:09:50',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1883,4,'Vara','walaaaaaaaaaaaa','2011-06-26 00:26:49',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1884,4,'Vara','estoy en el universo paralelo a vp','2011-06-26 00:27:00',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1885,4,'Vara','en una realidad alternativa donde yo soy todos y todos son yo!','2011-06-26 00:27:16',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1886,4,'Vara','donde yo no violo patos.... si no que ellos me violan a mi..','2011-06-26 00:27:28',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1887,4,'Vara','QUE MIEDO!','2011-06-26 00:27:31',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1888,4,'Vara','mejor me voy a la plaza normal.... esta me da repelus...','2011-06-26 00:28:00',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1889,4,'Vara','<span style=\"margin-left:20px;color:#66004C;\"><b>Vara</b> se marcha, hasta pronto!</span>','2011-06-26 00:28:03',6,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (1890,4,'jechenique','me han citado a una mega orgÃ­a acÃ¡ ... estoy en el lugar indicado ? :roto2:','2011-06-26 00:31:00',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1891,4,'jechenique','joder.. que impuntualidad, se han pasado ya 1 minuto','2011-06-26 00:31:18',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1892,4,'jechenique','xD','2011-06-26 00:31:20',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1893,4,'jechenique','<b style=\"margin-left:20px;\">jechenique</b> se marcha','2011-06-26 00:31:26',0,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (1894,4,'Honse',':roto2:','2011-06-26 00:31:31',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1895,4,'jechenique','viva AS Roma !!!','2011-06-26 00:33:05',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1896,4,'jechenique',':roto2:','2011-06-26 00:33:15',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1897,4,'Honse','ein? :roto2:','2011-06-26 00:33:52',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1898,4,'jechenique','mierda.. lo he puesto en otro lado xD','2011-06-26 00:36:20',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1899,4,'Gak','que es esto?','2011-06-26 00:40:14',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1900,4,'oportunista','la plaza de la versiÃ³n de desarrollo','2011-06-26 14:28:41',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1901,4,'LordNak','leches, funciona xD','2011-06-26 18:35:45',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1902,4,'oportunista','^---^','2011-06-26 20:49:49',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1903,4,'oportunista','(^_^)','2011-06-26 20:49:55',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1904,4,'oportunista','orejitas de gato :D','2011-06-26 20:50:57',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1905,4,'oportunista','aunque quedan muy separadas','2011-06-26 20:51:16',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1906,4,'Linus','Ã±e','2011-06-26 20:51:40',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1907,4,'Brook','Ã±eÃ±aÃ±u','2011-06-26 20:52:31',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1908,4,'Shrewd','huh','2011-06-26 20:53:05',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1909,4,'LordNak','okfjbojeobtj','2011-06-26 20:53:06',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1910,4,'LordNak','wtogbjotjgbt','2011-06-26 20:53:07',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1911,4,'oportunista',':Ãž','2011-06-26 20:53:24',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1912,4,'oportunista',':-Ãž','2011-06-26 20:53:29',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1913,4,'Shrewd','son chats distintos!','2011-06-26 20:53:33',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1914,4,'oportunista','sÃ­, un efecto extraÃ±o en un cambio que hizo GONZO','2011-06-26 20:53:59',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1915,4,'oportunista','este chat se conecta a la base de datos de desarrollo','2011-06-26 20:54:16',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1916,4,'oportunista','y por eso es distinto','2011-06-26 20:54:22',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1917,4,'Shrewd','mmmm es raro si','2011-06-26 20:54:48',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1918,4,'Shrewd','normalmente desarollo fallaba por todos lados','2011-06-26 20:54:54',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1919,4,'oportunista','salvo el chat, lo demÃ¡s accede a la base de datos real, la del funcionamiento normal','2011-06-26 20:55:51',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1920,4,'fe23','mmm...','2011-06-26 21:09:34',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1921,4,'fe23','ya no se requiere login diferente para la dev?','2011-06-26 21:09:47',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1922,4,'oportunista','mientras GONZO no lo cambie no','2011-06-26 21:16:06',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1923,4,'oportunista','esta conectado con la bd real','2011-06-26 21:16:29',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1924,4,'oportunista','en  cambio el chat conecta con la bd de desarrollo','2011-06-26 21:16:41',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1925,4,'fe23','ahora te lo muestro oportunista','2011-06-26 21:23:39',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1926,4,'fe23','oportunista <a target=\"_blank\" href=\"http://box.jisko.net/i/ada7163e.png\">http://box.jisko.net/i/ada7163e.png</a>','2011-06-26 21:25:06',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1927,4,'oportunista','ya estoy','2011-06-26 21:26:45',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1928,4,'fe23','eso, que mira como lo veo yo','2011-06-26 21:27:04',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1929,4,'oportunista','sÃ­, lo de arriba es coo estÃ¡ saliendo mientras no se actualice vp.cdn.teoriza.com/style.css','2011-06-26 21:27:48',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1930,4,'oportunista','y lo de abajo es una captura que puse de cÃ³mo se ve con los cambios que hice a style.css','2011-06-26 21:28:10',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1931,4,'oportunista','le pongo entonces border-radius: 12px;  y border: #000000 solid thin; ?','2011-06-26 21:30:37',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1932,4,'oportunista','asÃ­ se ve con los bordes redondeados','2011-06-26 21:31:47',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1933,4,'fe23','si','2011-06-26 21:37:09',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1934,4,'fe23','asi se ven redondeados','2011-06-26 21:37:15',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1935,4,'oportunista','tengo que mirar cÃ³mo poner un botÃ³n \"citar\" y que no quede tan cantoso como el de editar','2011-06-26 21:39:04',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1936,4,'oportunista','que quede pequÃ±ito en la esquina para que no moleste','2011-06-26 21:39:31',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1937,4,'Vara','<b style=\"margin-left:20px;\">Vara</b> entra en la plaza secreta','2011-06-27 19:22:31',6,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (1938,4,'Vara','muajajajaj estoy en el universo paralelo','2011-06-27 19:22:40',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1939,4,'Brook','fe23 como hago que solo sea para los polis?','2011-06-30 13:20:59',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1940,4,'orxona','Ã±e','2011-06-30 13:26:19',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1941,4,'oportunista','Ã±e','2011-06-30 21:53:23',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1942,4,'oportunista',':roto2:','2011-06-30 21:53:26',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1943,4,'Brook','eÃ±e','2011-06-30 22:54:54',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1944,4,'oportunista','nas','2011-07-08 20:14:09',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1945,4,'oportunista','input id=\"notas_boton\" value=\"Enviar\" disabled=\"disabled\" t','2011-07-10 21:04:04',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1946,4,'oportunista','estÃ¡ desactivado el botÃ³n el el cÃ³digo :O','2011-07-10 21:04:19',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1947,4,'oportunista','en*','2011-07-10 21:04:26',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1948,4,'oportunista','no sÃ© si GONZO lo desactivÃ³ por alguna razÃ³n','2011-07-10 21:04:41',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1949,4,'Fernando','Â¿?','2011-07-18 06:25:52',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1950,4,'Fernando','pero que es esto?','2011-07-18 06:26:45',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1951,4,'Fernando','fe23?=','2011-07-18 06:26:50',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1952,4,'Fernando','pero n o habÃ­a sido expulsado?','2011-07-18 06:26:59',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1953,4,'Fernando',':O','2011-07-18 06:27:01',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1954,4,'Fernando','y Vara? es diputado?','2011-07-18 06:27:07',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1955,4,'Fernando','en que realidad paralela estoy?','2011-07-18 06:27:14',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1956,4,'Fernando','hola','2011-07-21 00:39:38',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1957,4,'Fernando','hay que bien','2011-07-21 00:39:42',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1958,4,'Fernando','aquÃ­ no estoy kickeado','2011-07-21 00:39:47',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1959,4,'Fernando',':)','2011-07-21 00:39:49',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1960,4,'Fernando','QUE ALGUIEN LLAME A HONSE','2011-07-21 01:10:48',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1961,4,'Fernando','necesito hablar conÃ©l','2011-07-21 01:10:53',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1962,4,'Fernando','venga chicooos','2011-07-21 01:11:00',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1963,4,'Victor_Daniel','esto es la DEV, Fernando','2011-07-21 01:15:05',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1964,4,'Fernando','si','2011-07-21 01:15:13',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1965,4,'Fernando',':D es sÃ³lo para decirle una cosa a Honse','2011-07-21 01:15:22',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1966,4,'Fernando','y ya tÃ¡','2011-07-21 01:15:25',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1967,4,'Victor','Hola','2011-07-21 01:16:27',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1968,4,'Fernando','Hola','2011-07-21 01:16:52',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1969,4,'Fernando',':)','2011-07-21 01:16:54',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1970,4,'Victor','Que es esto?','2011-07-21 01:17:05',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1971,4,'Victor','Vara diputado?','2011-07-21 01:17:12',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1972,4,'Victor','y esta fe23?','2011-07-21 01:17:16',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1973,4,'Fernando','Hola','2011-07-21 01:18:06',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1974,4,'Fernando',':)','2011-07-21 01:18:09',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1975,4,'Fernando','Victor es malbadoooo ohh :troll:','2011-07-21 01:18:16',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1976,4,'Victor','Hola Fer','2011-07-21 01:18:30',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1977,4,'Victor','por?','2011-07-21 01:18:32',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1978,4,'Fernando','Victor dile al tonto de Honse que venga','2011-07-21 01:18:43',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1979,4,'Fernando','que aquÃ­ soyy libree!','2011-07-21 01:18:47',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1980,4,'Fernando',':D','2011-07-21 01:18:48',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1981,4,'Victor_Daniel','01:17 [Extranjero] Honse: si no puedo :roto2: 01:18 [Extranjero] Honse: estan las fronteras cerradas o algo asi :D','2011-07-21 01:19:02',9,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1982,4,'Fernando',':O','2011-07-21 01:19:09',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1983,4,'Fernando','GONZO es mal\'b\'ado tambiÃ©n','2011-07-21 01:19:20',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1984,4,'Fernando',':troll:','2011-07-21 01:19:22',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1985,4,'Fernando','pues no me dejÃ©is solo!!!','2011-07-21 01:19:28',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1986,4,'Fernando','venir a verme a mi cÃ¡rcel!','2011-07-21 01:19:34',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1987,4,'Victor','Pero que es esto??????','2011-07-21 01:19:35',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1988,4,'Fernando',':D','2011-07-21 01:19:36',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1989,4,'Victor','me lo podeis explicar?','2011-07-21 01:19:42',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1990,4,'Fernando','la plaza del desarrollo','2011-07-21 01:19:48',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1991,4,'Fernando',':troll:','2011-07-21 01:19:50',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1992,4,'MisterM','juas','2011-07-21 01:20:01',8,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1993,4,'Fernando','Y yo con mi ingenio y mi culito he logrado hacerla mi jaula de platÃ³n!','2011-07-21 01:20:06',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1994,4,'MisterM','la plaza \"secreta\"','2011-07-21 01:20:09',8,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1995,4,'Fernando','xD','2011-07-21 01:20:10',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1996,4,'Fernando','MisterM dimite!','2011-07-21 01:20:14',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1997,4,'Fernando','por malbado!! :troll:','2011-07-21 01:20:20',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1998,4,'Fernando','como VDDD','2011-07-21 01:20:22',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (1999,4,'Fernando','tots sois malbadoooos!! :troll:','2011-07-21 01:20:30',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2000,4,'MisterM','pero si yo no soy juez supremo! xD','2011-07-21 01:20:42',8,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2001,4,'Baldor','hola','2011-07-21 01:20:45',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2002,4,'Fernando','Que alguien le diga a Honse que es un presidente sexi.. :D','2011-07-21 01:20:52',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2003,4,'Victor','y una cosa','2011-07-21 01:20:56',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2004,4,'Fernando','Hola Baldor!!','2011-07-21 01:20:56',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2005,4,'Fernando','biiien!!','2011-07-21 01:21:00',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2006,4,'Victor','fe23 es diputadoÂ¿Â¿','2011-07-21 01:21:03',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2007,4,'Fernando','que venga gentee!','2011-07-21 01:21:03',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2008,4,'Fernando','FIESTA!','2011-07-21 01:21:06',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2009,4,'Fernando',':D','2011-07-21 01:21:07',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2010,4,'Fernando','nah victor','2011-07-21 01:21:21',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2011,4,'Fernando','eso es muy viejo','2011-07-21 01:21:24',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2012,4,'Fernando','txD','2011-07-21 01:21:26',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2013,4,'MisterM','va a haber mÃ¡s gente aquÃ­ que en la plaza normal xD','2011-07-21 01:21:27',8,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2014,4,'Fernando','Venga debemos conspirar todos contra VD y esmity','2011-07-21 01:21:46',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2015,4,'Fernando',':roto2:','2011-07-21 01:21:48',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2016,4,'Victor','Jajajaja','2011-07-21 01:21:57',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2017,4,'Fernando','conspiraciones, conspiraciones everywhere','2011-07-21 01:22:14',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2018,4,'Fernando','Â¿QuÃ© tal gente?','2011-07-21 01:22:17',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2019,4,'SMITH',':O','2011-07-21 01:22:20',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2020,4,'SMITH','que es esto :O','2011-07-21 01:22:23',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2021,4,'Fernando','estoy encerrado!! maÃ±ana salgo','2011-07-21 01:22:26',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2022,4,'Fernando','Hola ESMITY','2011-07-21 01:22:30',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2023,4,'SMITH','cospiraciones :O','2011-07-21 01:22:32',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2024,4,'Fernando','Bienvenido a mi diseÃ±o particular','2011-07-21 01:22:36',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2025,4,'Fernando',':roto2:','2011-07-21 01:22:38',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2026,4,'Fernando','AquÃ­ soy invencible!','2011-07-21 01:22:43',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2027,4,'MisterM','en todo caso coNspiraciones xD','2011-07-21 01:22:45',8,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2028,4,'Fernando',':troll:','2011-07-21 01:22:47',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2029,4,'Fernando','dile a Honse que es tonto','2011-07-21 01:23:00',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2030,4,'Fernando','y que si estÃ¡s kickeado dos dÃ­as','2011-07-21 01:23:05',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2031,4,'Victor','xD','2011-07-21 01:23:07',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2032,4,'Fernando','no puedes leer ni recibir MPS','2011-07-21 01:23:11',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2033,4,'Fernando',':facepalm:','2011-07-21 01:23:17',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2034,4,'Fernando',':troll:','2011-07-21 01:23:21',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2035,4,'Victor','Fernando te aburres aki tu solito?','2011-07-21 01:23:28',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2036,4,'Fernando','nah, ahora viene la chusma a molestarme','2011-07-21 01:23:41',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2037,4,'Fernando',':troll:','2011-07-21 01:23:43',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2038,4,'Victor',':troll:','2011-07-21 01:24:19',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2039,4,'Fernando','Que alguien le diga a Honse que debe venir aquÃ­ a darme salami :troll:','2011-07-21 01:24:41',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2040,4,'Victor','jajaa','2011-07-21 01:24:49',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2041,4,'Victor','yo te doy merengue merengue','2011-07-21 01:24:56',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2042,4,'Fernando','Gracias JONSO','2011-07-21 01:25:28',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2043,4,'Fernando','por hacer este chat','2011-07-21 01:25:32',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2044,4,'Fernando','para liberarme','2011-07-21 01:25:34',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2045,4,'Fernando',':troll:','2011-07-21 01:25:36',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2046,4,'Fernando','toma te doy un besito :*','2011-07-21 01:25:44',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2047,4,'Fernando',':D  Moltes Gracies','2011-07-21 01:25:50',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2048,4,'Victor','me estoy poniendo celoso xD','2011-07-21 01:26:05',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2049,4,'Fernando','que alguien se lo diga por la otra plaza!!','2011-07-21 01:26:16',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2050,4,'Fernando','hombree!','2011-07-21 01:26:18',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2051,4,'Fernando','que a lo mejor no lee esta','2011-07-21 01:26:23',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2052,4,'Fernando',':troll:','2011-07-21 01:26:25',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2053,4,'Fernando','Muahahaahahhahaahahaha SMITH voy a por ti...','2011-07-21 01:27:23',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2054,4,'Fernando','ponedlo en la plaza general','2011-07-21 01:27:31',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2055,4,'Fernando','que asÃ­ mÃ¡smola','2011-07-21 01:27:34',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2056,4,'Fernando',':troll:','2011-07-21 01:27:37',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2057,4,'Fernando','Pero que alguien pase mis mensajee!!','2011-07-21 01:28:52',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2058,4,'Fernando','o me chivo al presidente Brook','2011-07-21 01:28:59',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2059,4,'Fernando',':troll:','2011-07-21 01:29:02',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2060,4,'Victor','.......','2011-07-21 01:29:41',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2061,4,'Fernando','muy bien vÃ­ctor','2011-07-21 01:30:30',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2062,4,'Fernando','toma un caramelito','2011-07-21 01:30:33',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2063,4,'Fernando','<b style=\"margin-left:20px;\">Fernando</b> le da un caramelito de... Naranja!! :troll:','2011-07-21 01:30:47',19,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2064,4,'Victor','Jo...','2011-07-21 01:31:22',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2065,4,'Fernando','Pasa este tambiÃ©n y te llevas uno de fresa Victor..','2011-07-21 01:31:26',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2066,4,'Victor','lo queria de salami jaja','2011-07-21 01:31:29',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2067,4,'Fernando','Peny mÃ¡smola. Peny for president!! Desde la cÃ¡rcel decimos Peny gracias!! :)','2011-07-21 01:31:55',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2068,4,'SMITH','Fernando dimite! El pueblo no te admite!','2011-07-21 01:32:33',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2069,4,'SMITH',':troll:','2011-07-21 01:32:40',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2070,4,'Fernando','Esmity es un prevaricador, y lo demostrarÃ©.. :troll:','2011-07-21 01:33:37',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2071,4,'Victor','fernando','2011-07-21 01:34:04',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2072,4,'Victor','mi caramelo!','2011-07-21 01:34:08',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2073,4,'Fernando','<b style=\"margin-left:20px;\">Fernando</b> le da un caramelito de Fresa... :troll:','2011-07-21 01:34:27',19,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2074,4,'Victor','ahora de Salami xD','2011-07-21 01:34:45',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2075,4,'Fernando','otro mensaje...','2011-07-21 01:35:00',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2076,4,'Fernando','Ni el GarzÃ³n de VP (VD), ni el Rajoy de VP (Esmity) ni nadie pueden conmigoooo!! Soy invencible!! Muajajajajajajajaajjajaajajajaja!!','2011-07-21 01:35:38',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2077,4,'Victor','mi caramelo de salami Â¬Â¬','2011-07-21 01:36:35',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2078,4,'Fernando','<b style=\"margin-left:20px;\">Fernando</b> le da un caramelito de Salami!! y otro de Choped por la rapidez!! :troll:','2011-07-21 01:36:47',19,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2079,4,'Victor','Que rico!','2011-07-21 01:38:13',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2080,4,'Fernando','bueno','2011-07-21 01:41:25',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2081,4,'Fernando','por lo menos nos queda esto...','2011-07-21 01:41:32',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2082,4,'Fernando',':|','2011-07-21 01:41:35',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2083,4,'Victor',':O','2011-07-21 01:42:26',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2084,4,'Fernando','oye victor','2011-07-21 01:42:46',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2085,4,'Fernando','dile al gilipollas ese de la plaza','2011-07-21 01:42:55',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2086,4,'Fernando','que no es \'Por peticiÃ³n propia\'','2011-07-21 01:43:00',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2087,4,'Fernando','sino por una sentencia judicial..','2011-07-21 01:43:05',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2088,4,'Fernando',':facepalm:','2011-07-21 01:43:10',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2089,4,'Victor',':facepalm:','2011-07-21 01:44:22',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2090,4,'Fernando','Victor manda este mensaje..','2011-07-21 01:44:36',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2091,4,'Fernando','Â¡Que lo expulseenn!! QUEREMOS VER LA SANGRE DE WilliamV correr... :troll:','2011-07-21 01:44:59',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2092,4,'Victor','quiero mi caramelo de merengue','2011-07-21 01:46:12',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2093,4,'Fernando','<b style=\"margin-left:20px;\">Fernando</b> le da un caramelito de merengue..','2011-07-21 01:46:28',19,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2094,4,'Fernando',':troll:','2011-07-21 01:46:32',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2095,4,'Victor','^^','2011-07-21 01:46:39',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2096,4,'Fernando','Que lo expulsen!!! Bibah forever Jonse and the ACracia!','2011-07-21 01:47:08',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2097,4,'Fernando','maanda ese tambiÃ©n','2011-07-21 01:47:12',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2098,4,'Fernando',':troll:','2011-07-21 01:47:15',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2099,4,'Victor','mi caramelo de semen de Honse','2011-07-21 01:48:30',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2100,4,'Fernando','<b style=\"margin-left:20px;\">Fernando</b> le da un caramelo con su semen... que masmola :roto2:','2011-07-21 01:49:02',19,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2101,4,'Fernando','aunque estas cochinadas','2011-07-21 01:49:08',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2102,4,'Fernando','no se pueden decir','2011-07-21 01:49:11',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2103,4,'Fernando','shhh','2011-07-21 01:49:12',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2104,4,'Fernando',':troll:','2011-07-21 01:49:16',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2105,4,'Victor',':troll:','2011-07-21 01:49:31',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2106,4,'Victor','que asco :roto2:','2011-07-21 01:49:40',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2107,4,'Fernando','VD es presi... :O','2011-07-21 01:49:50',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2108,4,'Fernando','pasa este mensaje','2011-07-21 01:49:53',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2109,4,'Fernando','..:troll','2011-07-21 01:49:55',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2110,4,'Victor','cual?','2011-07-21 01:50:31',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2111,4,'Fernando','01:49  Fernando: VD es presi...','2011-07-21 01:50:43',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2112,4,'Fernando','con el :O','2011-07-21 01:50:49',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2113,4,'Fernando','espeeraa..','2011-07-21 01:51:34',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2114,4,'Fernando','antes dile','2011-07-21 01:51:36',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2115,4,'Fernando','que','2011-07-21 01:51:39',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2116,4,'Fernando','que..','2011-07-21 01:51:40',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2117,4,'Fernando','que si ha recibido mi MPP!!','2011-07-21 01:51:46',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2118,4,'Fernando',':O','2011-07-21 01:51:47',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2119,4,'Victor','dejalo','2011-07-21 01:52:22',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2120,4,'Victor','no kiero mas caramelos xD','2011-07-21 01:52:29',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2121,4,'Fernando',':(','2011-07-21 01:52:44',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2122,4,'Fernando','hablad','2011-07-21 01:58:48',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2123,4,'Fernando','nadie quiere hablar conmigo!','2011-07-21 01:58:56',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2124,4,'Fernando',':(','2011-07-21 01:58:58',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2125,4,'Victor','hablo yo','2011-07-21 01:59:51',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2126,4,'Victor','quieres salami? lo tengo rebajado!','2011-07-21 02:00:09',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2127,4,'Fernando','Pasa el mensaje','2011-07-21 02:00:36',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2128,4,'Fernando','Peny mÃ¡smola! Peny forever!! :D','2011-07-21 02:00:48',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2129,4,'Victor','me voy','2011-07-21 02:02:43',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2130,4,'Victor','chao','2011-07-21 02:02:44',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2131,4,'Fernando',':O','2011-07-21 02:03:01',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2132,4,'Fernando','adiÃ³s.. :(','2011-07-21 02:03:08',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2133,4,'Fernando','siempre te quise...','2011-07-21 02:03:13',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2134,4,'Fernando',':troll:','2011-07-21 02:03:17',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2135,4,'Fernando','Naaram!!','2011-07-21 02:37:51',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2136,4,'Fernando','debemos conspirar aquÃ­','2011-07-21 02:37:55',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2137,4,'Fernando',':troll:','2011-07-21 02:37:59',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2138,4,'Fernando','<b style=\"margin-left:20px;\">Fernando</b> HOLA, sean todos bienvenidos al despacho habituado para la Ã©poca de dos dÃ­as de kicks que se le han sentenciado al Excmo, guapo e inteligente Vicepresidente Fernando','2011-07-21 02:39:23',19,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2139,4,'Fernando','Bienvenido Naaram','2011-07-21 02:46:49',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2140,4,'Fernando','te esperÃ¡bamos','2011-07-21 02:46:52',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2141,4,'Fernando',':D','2011-07-21 02:46:53',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2142,4,'Shrewd','prueba','2011-07-21 02:47:35',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2143,4,'Fernando','de prueba nada','2011-07-21 02:47:47',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2144,4,'Fernando','fuera de aquÃ­','2011-07-21 02:47:50',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2145,4,'Fernando',':troll:','2011-07-21 02:47:54',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2146,4,'Fernando','aquÃ­ debÃ­a estar Naaram','2011-07-21 02:47:59',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2147,4,'Fernando','hombre ya!','2011-07-21 02:48:03',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2148,4,'Fernando','Para un lugar libre que tengo','2011-07-21 02:48:09',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2149,4,'Fernando',':|','2011-07-21 02:48:12',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2150,4,'Shrewd','anda, pero si esto es australia !','2011-07-21 02:48:13',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2151,4,'Shrewd','bueno perdon perdon','2011-07-21 02:48:28',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2152,4,'Fernando','No, esto es alcalameco','2011-07-21 02:48:33',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2153,4,'Shrewd','nueva australia','2011-07-21 02:48:34',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2154,4,'Shrewd','xD','2011-07-21 02:48:37',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2155,4,'Naaram','Buenas','2011-07-21 02:48:44',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2156,4,'Fernando','pero estÃ¡ dentro de la jurisdicciÃ³n de \'VitualPOL\'','2011-07-21 02:48:50',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2157,4,'Fernando',':troo2:','2011-07-21 02:48:52',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2158,4,'Fernando',':troll:','2011-07-21 02:48:55',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2159,4,'Fernando','Holaa Naaram','2011-07-21 02:48:58',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2160,4,'Fernando','que tal?','2011-07-21 02:49:01',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2161,4,'Fernando','has entrado en Mine?','2011-07-21 02:49:13',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2162,4,'Naaram','he entrado pero no he visto mucho nuevo','2011-07-21 02:50:08',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2163,4,'Naaram','aparte del barrio de chabolas horrible','2011-07-21 02:50:12',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2164,4,'Naaram','y las cloacas','2011-07-21 02:50:14',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2165,4,'Fernando','ni yo','2011-07-21 02:50:22',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2166,4,'Fernando','xD','2011-07-21 02:50:23',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2167,4,'Fernando','bueno sÃ­','2011-07-21 02:50:28',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2168,4,'Fernando','deberÃ­as haber visto','2011-07-21 02:50:32',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2169,4,'Fernando','el \'teleferic\'','2011-07-21 02:50:35',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2170,4,'Fernando',':roto2:','2011-07-21 02:50:39',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2171,4,'Fernando','quiero hacer una especie de tranvÃ­a tapado con tÃºnel','2011-07-21 02:50:49',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2172,4,'Fernando','que conecte mi barrio','2011-07-21 02:50:54',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2173,4,'Fernando','con invernalia','2011-07-21 02:50:58',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2174,4,'Fernando',':troll:','2011-07-21 02:51:03',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2175,4,'Fernando','estamos en fase de pruebas..','2011-07-21 02:51:10',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2176,4,'Naaram','un metro, vaya','2011-07-21 02:51:19',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2177,4,'Fernando','pichin, pichan...','2011-07-21 02:51:33',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2178,4,'Fernando','no llega a estar bajo tierra..','2011-07-21 02:51:44',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2179,4,'Fernando','mÃ¡s bien no sÃ© como orientarlo..','2011-07-21 02:51:51',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2180,4,'Fernando','pero bueno','2011-07-21 02:51:53',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2181,4,'Fernando','ya se verÃ¡','2011-07-21 02:51:57',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2182,4,'Fernando',':troll:','2011-07-21 02:52:00',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2183,4,'Naaram','seria interesante hacer un metro para la ciudad','2011-07-21 02:52:15',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2184,4,'Naaram','con una estacion central','2011-07-21 02:52:18',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2185,4,'Fernando','xD','2011-07-21 02:52:23',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2186,4,'Naaram','que te envÃ­e a uno y otro barrio segÃºn necesitemos','2011-07-21 02:52:23',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2187,4,'Fernando','pero serÃ­a muy difiÃ­cil','2011-07-21 02:52:28',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2188,4,'Naaram','que va','2011-07-21 02:52:36',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2189,4,'Fernando','porque con tanta caÃ±erÃ­a','2011-07-21 02:52:38',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2190,4,'Fernando','cloaca','2011-07-21 02:52:41',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2191,4,'Fernando','auditorio..','2011-07-21 02:52:43',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2192,4,'Fernando','centro comercial','2011-07-21 02:52:46',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2193,4,'Naaram','se pueden utilizar las propias caÃ±erias','2011-07-21 02:52:48',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2194,4,'Fernando','etcÃ©tera','2011-07-21 02:52:49',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2195,4,'Fernando',':roto2: yo tambiÃ©n lo he pensado','2011-07-21 02:53:01',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2196,4,'Naaram','no estÃ¡ toda la ciudad perforada y podemos guiarnos con las cloacas','2011-07-21 02:53:35',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2197,4,'Naaram','es mas, en realidad son pocos tuneles','2011-07-21 02:53:43',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2198,4,'Fernando',':troll: a excavaar!!','2011-07-21 02:54:07',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2199,4,'Fernando','y hay que hablar con Hooks','2011-07-21 02:54:18',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2200,4,'Fernando','para que delimite su barrio','2011-07-21 02:54:24',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2201,4,'Fernando','ponernos a hacer la muralla por mi zona','2011-07-21 02:54:34',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2202,4,'Fernando','para sabeer donde puedo construir... :troll:','2011-07-21 02:54:48',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2203,4,'Naaram','construye hasta la montaÃ±a','2011-07-21 02:55:07',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2204,4,'Naaram','de momento','2011-07-21 02:55:09',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2205,4,'Naaram','no creo que hoosk baje mucho','2011-07-21 02:55:14',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2206,4,'Fernando','mm ok :)','2011-07-21 02:55:27',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2207,4,'Naaram','o incluso','2011-07-21 02:58:08',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2208,4,'Naaram','podriamos construir los tuneles por fuera de la ciudad','2011-07-21 02:58:13',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2209,4,'Naaram','dejando un tunel gigante de salida','2011-07-21 02:58:17',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2210,4,'Naaram','todos los barrios tienen muralla externa','2011-07-21 02:58:23',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2211,4,'Naaram','asi que aunque de un rodeo, seria fÃ¡cil','2011-07-21 02:58:30',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2212,4,'Naaram','por ejemplo, quitar los tÃºneles que van hacia la puerta del mar y transformarlos en tuneles de metro','2011-07-21 02:58:47',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2213,4,'Fernando','mmmm','2011-07-21 02:59:05',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2214,4,'Naaram','que salgan las vias hacia el mar, y desde el mar van a los diferentes barrios','2011-07-21 02:59:09',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2215,4,'Naaram','aunque rodeen la ciudad, pero bueno, el metro es rapidismo, mas que ir andando','2011-07-21 02:59:18',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2216,4,'Naaram','y que las estaciones sean en las \"esquinas\" de cada barrio','2011-07-21 02:59:27',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2217,4,'Fernando','mmm pero por dentro de las murallas?','2011-07-21 02:59:28',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2218,4,'Naaram','es decir, en los puntos mas alejados de la avenida','2011-07-21 02:59:34',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2219,4,'Naaram','no, por fuera y por debajo','2011-07-21 02:59:37',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2220,4,'Fernando','ahh ok','2011-07-21 02:59:43',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2221,4,'Fernando','bueno, llevarÃ­as el moderlo de carreteras de madeira','2011-07-21 02:59:55',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2222,4,'Fernando',':roto2:','2011-07-21 02:59:58',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2223,4,'Fernando','en madeira una sÃ³la autovÃ­a','2011-07-21 03:00:05',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2224,4,'Fernando','circula en redondo','2011-07-21 03:00:11',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2225,4,'Fernando','toda la isla','2011-07-21 03:00:14',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2226,4,'Fernando','y para ir a cualquier parte','2011-07-21 03:00:20',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2227,4,'Fernando','coges y paras','2011-07-21 03:00:23',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2228,4,'Fernando','segÃºn te convenga','2011-07-21 03:00:28',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2229,4,'Fernando','serÃ­a molÃ³n!','2011-07-21 03:00:31',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2230,4,'Fernando',':troll:','2011-07-21 03:00:33',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2231,4,'Naaram','yo haria una estacion central, subterranea, bajo la fuente','2011-07-21 03:00:45',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2232,4,'Naaram','y desde ahi, un tunel con seis vias','2011-07-21 03:00:55',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2233,4,'Fernando','mmm deberÃ¡ ser bastante subterrÃ¡nea..','2011-07-21 03:01:03',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2234,4,'Fernando','ahora ahÃ­ estÃ¡ la central','2011-07-21 03:01:14',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2235,4,'Fernando','hidrÃ¡ulica','2011-07-21 03:01:19',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2236,4,'Fernando','de Rafa','2011-07-21 03:01:22',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2237,4,'Fernando','caÃ±erÃ­as paquÃ­','2011-07-21 03:01:26',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2238,4,'Fernando','caÃ±erÃ­as pallÃ¡','2011-07-21 03:01:30',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2239,4,'Naaram','una via al barrio de tsu, otra a mi barrio, otra al tuyo, otra al de hoosk, otra a donde estÃ¡ la presa y una mas hacia el exterior, que podria comunicar','2011-07-21 03:01:31',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2240,4,'Fernando',':roto2:','2011-07-21 03:01:32',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2241,4,'Naaram','con otra que distribuya nuevas vias','2011-07-21 03:01:40',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2242,4,'Fernando','mm lo del de hoosk','2011-07-21 03:02:04',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2243,4,'Fernando','lo verÃ­a difÃ­cil','2011-07-21 03:02:08',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2244,4,'Fernando','la diferencia de alturas serÃ­a mortal','2011-07-21 03:02:14',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2245,4,'Fernando','podrÃ­amos hacer como en barcelona','2011-07-21 03:02:22',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2246,4,'Fernando','con el paralel','2011-07-21 03:02:26',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2247,4,'Fernando',':roto2:','2011-07-21 03:02:27',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2248,4,'Fernando','que llegue el metro a la central de mi barrio','2011-07-21 03:02:37',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2249,4,'Fernando','y allÃ­ pueda coger el \'teleferic\'','2011-07-21 03:02:43',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2250,4,'Fernando','para subir a la montaÃ±a','2011-07-21 03:02:48',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2251,4,'Fernando',':troll:','2011-07-21 03:02:51',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2252,4,'Fernando','como si fuera montjuic','2011-07-21 03:02:57',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2253,4,'Naaram','no importan las diferencias de alturas, fernando','2011-07-21 03:02:59',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2254,4,'Naaram','hay impulsores','2011-07-21 03:03:02',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2255,4,'Fernando',':D','2011-07-21 03:03:02',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2256,4,'Naaram','antiguamente si que importaba y era un coÃ±azo, pero ahora no es problema','2011-07-21 03:03:26',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2257,4,'Fernando','ya, ya...','2011-07-21 03:03:27',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2258,4,'Fernando','bueno, ok','2011-07-21 03:03:36',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2259,4,'Fernando','estarÃ­a guay','2011-07-21 03:03:41',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2260,4,'Fernando','sin duda','2011-07-21 03:03:43',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2261,4,'Naaram','en breve empezarÃ© a construir o el Parlamento, o la Ciudadela','2011-07-21 03:04:50',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2262,4,'Fernando','el parlamento..','2011-07-21 03:05:34',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2263,4,'Fernando','mm','2011-07-21 03:05:34',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2264,4,'Fernando','y tienes pensado','2011-07-21 03:05:39',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2265,4,'Fernando','donde ponerlo?','2011-07-21 03:05:43',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2266,4,'Naaram','si','2011-07-21 03:05:57',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2267,4,'Naaram','detrÃ¡s de la presa','2011-07-21 03:05:59',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2268,4,'Naaram','hay una zona natural','2011-07-21 03:06:03',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2269,4,'Naaram','que parece redonda','2011-07-21 03:06:06',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2270,4,'Naaram','ahi harÃ© el Parlamento','2011-07-21 03:06:12',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2271,4,'Naaram','un poco mas bajo que la catedral y con una cupula dorada y de cristal','2011-07-21 03:06:23',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2272,4,'Fernando','mmm','2011-07-21 03:06:34',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2273,4,'Fernando','molarÃ¡','2011-07-21 03:06:36',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2274,4,'Fernando','pero para accederÂ¿','2011-07-21 03:06:42',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2275,4,'Fernando','con la presa delante..','2011-07-21 03:06:49',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2276,4,'Fernando',':troll:','2011-07-21 03:06:50',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2277,4,'Fernando','eso menosmola','2011-07-21 03:06:53',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2278,4,'Fernando',':troll:','2011-07-21 03:06:58',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2279,4,'Naaram','entra al minecraft anda...','2011-07-21 03:08:26',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2280,4,'Fernando',':D','2011-07-21 03:08:38',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2281,4,'Fernando','ok','2011-07-21 03:08:47',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2282,4,'Fernando','Ya queda menos chicos!','2011-07-21 03:32:00',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2283,4,'Fernando','En 16 horas vuelvo a dar por culo en VP','2011-07-21 03:32:09',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2284,4,'Fernando','!!','2011-07-21 03:32:11',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2285,4,'Fernando',':troll: !','2011-07-21 03:32:14',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2286,4,'Fernando','esperÃ¡dme','2011-07-21 03:32:21',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2287,4,'Fernando',':roto2:','2011-07-21 03:32:25',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2288,4,'Fernando','A ver..','2011-07-21 15:38:57',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2289,4,'Fernando','si alguien lee esto','2011-07-21 15:39:02',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2290,4,'Fernando','que sea capaz de decirle a Shrewd','2011-07-21 15:39:09',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2291,4,'Fernando','que se guarde su campaÃ±a por donde le quepa','2011-07-21 15:39:16',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2292,4,'Fernando','y se deje de tonterÃ­as','2011-07-21 15:39:20',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2293,4,'Fernando','el gobierno no tiene la culpa','2011-07-21 15:39:25',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2294,4,'Fernando','de que los consultores','2011-07-21 15:39:30',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2295,4,'Fernando','hagan tonterÃ­as con sus licencias','2011-07-21 15:39:36',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2296,4,'Fernando','si se hubiera regulado el cÃ³digo de comercio','2011-07-21 15:39:44',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2297,4,'Fernando','que como diputado presentÃ© una vez..','2011-07-21 15:39:53',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2298,4,'Fernando','en el que se dejaba todo esto claro','2011-07-21 15:39:58',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2299,4,'Fernando','o si el PARLAMENTO','2011-07-21 15:40:03',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2300,4,'Fernando','hubiera hecho algo..','2011-07-21 15:40:07',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2301,4,'Fernando','Este gobierno sÃ³lo se ha limitado a elaborar un decreto para EVITAR los sondeos estÃºpidos de este tipo','2011-07-21 15:40:31',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2302,4,'Fernando','pero claro en vuestra mano estÃ¡ poner el \'nulo\'','2011-07-21 15:40:42',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2303,4,'Fernando','nosotros no vamos a hacer mÃ¡s, ahora Shrewd sigue con tu campaÃ±a','2011-07-21 15:40:57',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2304,4,'Fernando','.','2011-07-28 14:35:50',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2305,4,'Fernando','.','2011-07-28 14:35:51',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2306,4,'Fernando','.','2011-07-28 14:35:52',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2307,4,'Fernando','.','2011-07-28 14:35:52',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2308,4,'Fernando','.','2011-07-28 14:35:53',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2309,4,'Fernando','.','2011-07-28 14:35:53',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2310,4,'Fernando','.','2011-07-28 14:35:54',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2311,4,'Fernando','.','2011-07-28 14:35:54',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2312,4,'Fernando','.','2011-07-28 14:35:54',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2313,4,'Fernando','.','2011-07-28 14:35:55',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2314,4,'Fernando','..','2011-07-28 14:35:55',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2315,4,'Fernando','.','2011-07-28 14:35:56',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2316,4,'Fernando','..','2011-07-28 14:35:56',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2317,4,'Fernando','.','2011-07-28 14:35:56',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2318,4,'Fernando','.','2011-07-28 14:35:57',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2319,4,'Fernando','.','2011-07-28 14:35:57',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2320,4,'Fernando','.','2011-07-28 14:35:57',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2321,4,'Fernando','.','2011-07-28 14:35:57',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2322,4,'Fernando','.','2011-07-28 14:35:57',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2323,4,'Fernando','.','2011-07-28 14:35:58',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2324,4,'Fernando','.','2011-07-28 14:35:58',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2325,4,'Fernando','.','2011-07-28 14:35:58',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2326,4,'Fernando','.','2011-07-28 14:35:58',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2327,4,'Fernando','.','2011-07-28 14:35:58',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2328,4,'Fernando','.','2011-07-28 14:35:59',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2329,4,'Fernando','.','2011-07-28 14:35:59',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2330,4,'Fernando','.','2011-07-28 14:35:59',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2331,4,'Fernando','.','2011-07-28 14:35:59',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2332,4,'Fernando','.','2011-07-28 14:35:59',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2333,4,'Fernando','.','2011-07-28 14:36:00',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2334,4,'Fernando','.','2011-07-28 14:36:00',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2335,4,'Fernando','.','2011-07-28 14:36:00',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2336,4,'Fernando','.','2011-07-28 14:36:00',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2337,4,'Fernando','.','2011-07-28 14:36:00',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2338,4,'Fernando','.','2011-07-28 14:36:00',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2339,4,'Fernando','.','2011-07-28 14:36:01',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2340,4,'Fernando','..','2011-07-28 14:36:01',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2341,4,'Fernando','.','2011-07-28 14:36:01',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2342,4,'Fernando','.','2011-07-28 14:36:01',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2343,4,'Fernando','.','2011-07-28 14:36:02',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2344,4,'Fernando','.','2011-07-28 14:36:02',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2345,4,'Fernando','.','2011-07-28 14:36:02',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2346,4,'Fernando','.','2011-07-28 14:36:02',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2347,4,'Fernando','.','2011-07-28 14:36:02',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2348,4,'Fernando','.','2011-07-28 14:36:02',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2349,4,'Fernando','..','2011-07-28 14:36:02',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2350,4,'Fernando','.','2011-07-28 14:36:03',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2351,4,'Fernando','.','2011-07-28 14:36:03',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2352,4,'Fernando','.','2011-07-28 14:36:04',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2353,4,'Fernando','..','2011-07-28 14:36:04',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2354,4,'Fernando','.','2011-07-28 14:36:04',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2355,4,'Fernando','.','2011-07-28 14:36:05',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2356,4,'Fernando','.','2011-07-28 14:36:05',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2357,4,'Fernando','.','2011-07-28 14:36:05',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2358,4,'Fernando','.','2011-07-28 14:36:05',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2359,4,'Fernando','aver... :D','2011-07-28 14:36:08',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2360,4,'Fernando','es que es una prueba','2011-07-28 14:36:11',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2361,4,'Fernando','xD','2011-07-28 14:36:13',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2362,4,'Fernando','ya estÃ¡','2011-07-28 14:36:22',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2363,4,'Fernando','la conversaciÃ³n de antes borrada','2011-07-28 14:36:28',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2364,4,'Fernando',':Troll:','2011-07-28 14:36:31',0,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2365,4,'Chaos92','Donde estan todos?','2011-08-05 17:54:12',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2366,4,'Chaos92',':|','2011-08-05 17:54:16',12,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2367,4,'Fernando','Han muerto','2011-08-11 17:10:48',41,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2368,4,'Fernando','somos los Ãºnicos supervivientes','2011-08-11 17:10:55',41,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2369,4,'Fernando',':troll:','2011-08-11 17:10:57',41,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2370,4,'Shrewd','alguien dijo albaceteeee','2011-08-17 23:02:27',36,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2371,4,'Brook','Ã±e','2011-10-04 08:20:37',36,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2372,4,'Brook','afs','2011-10-04 18:36:45',36,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2373,4,'Brook','Ã±e','2011-10-04 19:37:02',36,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2374,4,'Juanma','hola','2011-10-04 19:39:15',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2375,4,'Falcom','Ã±aca Ã±aca','2011-10-04 19:39:19',36,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2376,4,'Honse','que guapo soy :troll:','2011-10-04 19:39:30',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2377,4,'Juanma','quereis seso conmigo :*','2011-10-04 19:39:45',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2378,4,'Juanma','?','2011-10-04 19:40:05',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2379,4,'Fernando','quÃ© hacÃ©is aquÃ­ cacho de trolls?','2011-10-04 19:40:09',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2380,4,'Fernando',':Roto2:','2011-10-04 19:40:11',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2381,4,'Juanma','no se','2011-10-04 19:40:30',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2382,4,'Zami',':O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O','2011-10-04 19:40:32',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2383,4,'Fernando','19:39  Honse: que guapo soy  :TROLL: ---> y quÃ© mentiroso.. :TROLL:','2011-10-04 19:40:34',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2384,4,'Juanma','rascarme la nariz :troll:','2011-10-04 19:40:40',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2385,4,'Fernando','Zami la rasca la cosita a Juanma..','2011-10-04 19:41:03',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2386,4,'Fernando',':TROLL:','2011-10-04 19:41:05',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2387,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o:o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o:o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o:o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o:o :o :o :o :o','2011-10-04 19:41:13',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2388,4,'Zami',':O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O :O','2011-10-04 19:41:14',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2389,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:41:36',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2390,4,'Juanma','ataque troll','2011-10-04 19:41:45',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2391,4,'Juanma','huid!','2011-10-04 19:41:49',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2392,4,'Zami','http://vp.virtualpol.com/foro/general/ataque-troll-a-atlantis/','2011-10-04 19:41:52',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2393,4,'Honse','Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec faucibus arcu sit amet lorem pretium non faucibus enim tincidunt. Aliquam sed turpis ac erat tempor faucibus. Cras sodales nulla id felis aliquam a pellentesque tellus aliquam.','2011-10-04 19:42:11',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2394,4,'Honse','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras a urna in justo luctus facilisis at eget enim. Vestibulum at urna sit amet eros consectetur placerat sit amet sed diam. Praesent nec erat vel nisi malesuada rhoncus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.','2011-10-04 19:42:35',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2395,4,'Honse','. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec faucibus arcu sit amet lorem pretium non faucibus enim tincidunt.','2011-10-04 19:42:47',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2396,4,'Fernando','Honse marica!','2011-10-04 19:42:48',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2397,4,'Fernando',':TROLL:','2011-10-04 19:42:50',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2398,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:42:50',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2399,4,'Honse','Aliquam sed turpis ac erat tempor faucibus. Cras sodales nulla id felis aliquam a pellentesque tellus aliquam.','2011-10-04 19:42:53',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2400,4,'Honse','In accumsan quam nec metus convallis eu placerat nisi condimentum. Duis vitae ipsum mi, quis molestie leo. Proin suscipit ante sit amet dolor cursus sed tincidunt diam aliquam. Cras in diam ligula, eu cursus lectus. Phasellus hendrerit malesuada ante, nec hendrerit magna vestibulum sed.','2011-10-04 19:43:00',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2401,4,'Fernando','<b style=\"margin-left:20px;\">Fernando</b> corre huyendo entre las dos calles centrales al grito \'\'Honse marica\'\' XD','2011-10-04 19:43:09',19,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2402,4,'Honse','Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras viverra dictum magna et fringilla. Nullam sagittis sem ac dui malesuada ac sagittis lectus venenatis. Aliquam ut nibh elit. Nunc auctor pellentesque turpis vel commodo.','2011-10-04 19:43:14',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2403,4,'Honse','Ut pulvinar nulla vel eros feugiat dictum. Morbi varius posuere volutpat. Curabitur lobortis tincidunt ultricies. Quisque ac mauris ut lectus laoreet vestibulum. Donec elementum sem non nisi blandit posuere. In hac habitasse platea dictumst.','2011-10-04 19:43:19',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2404,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:43:21',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2405,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:43:23',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2406,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:43:27',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2407,4,'Honse','que friskis sois, solo sabies poner emoticonos y encima mal :roto2:','2011-10-04 19:43:50',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2408,4,'Honse',':troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll:','2011-10-04 19:44:39',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2409,4,'Honse',':troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll:','2011-10-04 19:44:42',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2410,4,'Honse',':troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll:','2011-10-04 19:44:44',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2411,4,'Honse',':troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll:','2011-10-04 19:44:46',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2412,4,'Honse',':troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll:','2011-10-04 19:44:48',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2413,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:44:49',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2414,4,'Honse',':troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll: :troll:','2011-10-04 19:44:50',22,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2415,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:44:51',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2416,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:44:53',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2417,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:44:55',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2418,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:44:57',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2419,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:44:59',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2420,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:45:01',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2421,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:45:03',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2422,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:45:05',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2423,4,'Zami','<b style=\"margin-left:20px;\">Zami</b> Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la','2011-10-04 19:45:16',6,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2424,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o:o','2011-10-04 19:45:33',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2425,4,'Zami','<b style=\"margin-left:20px;\">Zami</b> Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la','2011-10-04 19:45:33',6,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2426,4,'Zami','<b style=\"margin-left:20px;\">Zami</b> Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la','2011-10-04 19:45:38',6,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2427,4,'Juanma','r','2011-10-04 19:49:00',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2428,4,'Juanma',':o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o :o','2011-10-04 19:49:02',35,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2429,4,'Zami','<b style=\"margin-left:20px;\">Zami</b> Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la F1!Por favor recupera la','2011-10-04 19:50:42',6,0,'c',NULL);
INSERT INTO `chats_msg` VALUES (2430,4,'Brook','HOSTIA PUTA, TROLLAZOS!!!!','2011-10-04 19:54:25',36,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2431,4,'Zami',':troll:','2011-10-04 19:55:57',6,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2432,4,'Fernando','Trollazos rojos y cabrones.','2011-10-04 22:01:20',19,0,'m',NULL);
INSERT INTO `chats_msg` VALUES (2433,4,'Fernando',':TROLL:','2011-10-04 22:01:21',19,0,'m',NULL);
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
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=latin1;

CREATE TABLE `hechos` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `time` date NOT NULL,
  `nick` varchar(14) NOT NULL default 'GONZO',
  `texto` varchar(2000) NOT NULL,
  `estado` enum('ok','del') NOT NULL default 'ok',
  `time2` datetime NOT NULL,
  `pais` enum('VirtualPol','POL','VULCAN','Hispania','VP') character set utf8 NOT NULL default 'VirtualPol',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`,`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM AUTO_INCREMENT=40082 DEFAULT CHARSET=latin1;

INSERT INTO `mensajes` VALUES (40051,200455,200469,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40052,200455,200414,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40053,200455,200417,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40054,200455,200419,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40055,200455,200467,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40056,200455,200424,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40057,200455,200426,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40058,200455,200471,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40059,200455,200470,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40060,200455,200429,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40061,200455,200438,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40062,200455,200441,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40064,200455,200463,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40065,200455,200460,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','0',0);
INSERT INTO `mensajes` VALUES (40066,200455,200462,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','1',0);
INSERT INTO `mensajes` VALUES (40067,200455,200455,'2010-09-06 10:46:43','<b>Mensaje Global:</b> (<span class=\"pp\">1.000</span> <img src=\"/img/m.gif\" border=\"0\" />)<hr />Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis risus nunc, eu luctus nunc. Fusce sagittis massa in ipsum ornare fringilla. Phasellus molestie, lectus at congue facilisis, elit enim aliquam neque, at tristique justo sapien eu nulla. Etiam fermentum urna vel dolor dapibus sit amet facilisis nisl facilisis. Nunc posuere luctus aliquam. Donec interdum, eros sit amet lobortis viverra, augue magna suscipit risus, in tincidunt ante purus non sapien. Curabitur viverra lacus quis mauris accumsan vel bibendum sem accumsan. Mauris mattis, nunc vel gravida accumsan, neque lectus facilisis orci, at ornare nunc ligula non leo. In hac habitasse platea dictumst. Ut scelerisque scelerisque elementum. Nulla quam augue, porttitor id convallis vel, pulvinar sit amet turpis.<br />\r\n<br />\r\nAliquam porttitor venenatis mi, at volutpat nulla faucibus eget. Morbi nisl urna, tempus sed bibendum eget, dignissim ut libero. Praesent iaculis rhoncus neque, in ultrices arcu tincidunt ut. Mauris tincidunt ultricies eros eu egestas. Praesent at tellus eget mi posuere malesuada id ac nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Duis enim mauris, fermentum ac viverra vitae, dictum quis justo. Fusce libero turpis, volutpat ut ultrices eu, mollis sed libero. Donec mattis volutpat magna, ac tempor nisl viverra quis. Nulla in odio nulla, et pellentesque urna. Cras dictum urna in lorem porta faucibus vehicula erat rhoncus. Fusce in adipiscing neque. Aliquam at purus erat. Morbi quis odio enim. Nulla eros sapien, tempor in ultrices eget, consectetur et lectus. Sed eu nisl eget diam posuere consequat. Quisque sit amet augue nec augue cursus laoreet ac quis ligula.<br />\r\n<br />\r\nQuisque odio eros, ullamcorper quis imperdiet ut, egestas pellentesque augue. Donec eu urna dolor, quis aliquet mi. Ut at pharetra sem. Phasellus dictum sagittis leo a consectetur. Nam ultrices hendrerit augue quis suscipit. Aliquam fringilla pulvinar lorem nec congue. Maecenas facilisis tempor convallis. Curabitur in augue purus. In sed dolor ut tortor gravida molestie sed sed nunc. Nulla sit amet orci nisi, vel pellentesque sem. In ut libero augue. Nunc malesuada bibendum ligula, rutrum fringilla nulla condimentum vitae. Phasellus sit amet mi ut dolor malesuada ultricies. Phasellus erat odio, scelerisque a consequat vel, suscipit eget dui. Suspendisse potenti. Fusce rutrum ultricies felis, et viverra mauris rutrum ac. Praesent pellentesque, mi sed faucibus sagittis, turpis sem rutrum lorem, eu varius augue odio eu tellus. Suspendisse potenti. Phasellus eget purus nisi.<br />\r\n<br />\r\nDuis tempor ornare laoreet. Ut ut mi vel eros pretium euismod et eget sapien. Suspendisse in metus sapien, ac feugiat odio. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Ut pellentesque lacinia feugiat. Vivamus tempor est tortor. Donec tempor, orci condimentum varius adipiscing, felis quam suscipit ligula, vel auctor erat justo non dolor. Vivamus quis eros a orci facilisis malesuada. Phasellus eu lacus enim, id volutpat orci. Quisque suscipit dignissim cursus. Nullam tortor lacus, lobortis at mollis sit amet, consectetur ac massa. Duis sed nulla at neque ultrices porta vitae quis diam. Quisque luctus tellus id neque tempor dictum. Mauris cursus enim eget lacus adipiscing tempor. Nunc sodales suscipit risus id accumsan. Aliquam sem nibh, eleifend convallis vulputate et, malesuada et sapien. Fusce venenatis iaculis est, ut convallis neque ornare nec. Nulla auctor lacinia accumsan.<br />\r\n<br />\r\nNunc nec odio odio, in tincidunt urna. Praesent pellentesque consectetur lobortis. Curabitur diam metus, faucibus non congue sit amet, vestibulum sit amet turpis. Aliquam interdum tincidunt est vitae mollis. Cras fringilla convallis ultricies. Curabitur non lectus nisi. Duis id auctor lectus. Praesent ut purus non arcu fringilla ultricies. Aenean euismod dui nec justo iaculis ut tempus justo venenatis. Nullam et lectus ipsum. Proin non eros augue. Sed quis dui at lorem aliquam porta nec id mauris. Praesent bibendum, nunc non consequat viverra, est sem aliquam augue, ut porttitor turpis augue vitae urna. Nulla eu ultricies velit. Ut egestas feugiat metus, ac sollicitudin sem ullamcorper ac. Sed ante odio, commodo eget gravida quis, iaculis eu arcu. Pellentesque et tortor eu ipsum facilisis congue. Curabitur consectetur nunc at sem tempus elementum. ','1',0);
INSERT INTO `mensajes` VALUES (40068,200419,200419,'2010-09-24 17:55:59','prueba','0',0);
INSERT INTO `mensajes` VALUES (40071,200442,200419,'2010-09-24 18:01:36','reprueba','0',0);
INSERT INTO `mensajes` VALUES (40075,200439,200462,'2010-09-24 19:06:41','<b>Mensaje multiple: Profesor</b><br />prueba 1 2 3','1',0);
INSERT INTO `mensajes` VALUES (40076,200439,200419,'2010-09-24 19:06:41','<b>Mensaje multiple: Profesor</b><br />prueba 1 2 3','0',0);
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
) ENGINE=MyISAM AUTO_INCREMENT=6647 DEFAULT CHARSET=latin1;

CREATE TABLE `stats` (
  `stats_ID` smallint(5) unsigned NOT NULL auto_increment,
  `pais` enum('POL','VULCAN','Hispania','VP') character set utf8 NOT NULL default 'POL',
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
) ENGINE=MyISAM AUTO_INCREMENT=1274 DEFAULT CHARSET=latin1;

INSERT INTO `stats` VALUES (1,'POL','2008-09-01 20:00:00',25,0,0,0,0,0,0,2,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (2,'POL','2008-09-02 20:00:00',28,3,0,0,0,0,0,2,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (3,'POL','2008-09-03 20:00:00',28,0,0,0,0,0,0,2,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (4,'POL','2008-09-04 20:00:00',33,5,0,0,0,0,0,3,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (5,'POL','2008-09-05 20:00:00',35,2,0,0,0,0,0,3,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (6,'POL','2008-09-06 20:00:00',48,13,0,0,0,0,0,3,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (7,'POL','2008-09-07 20:00:00',54,6,0,0,0,0,0,4,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (8,'POL','2008-09-08 20:00:00',56,2,0,0,0,0,0,4,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (9,'POL','2008-09-09 20:00:00',56,0,0,0,0,0,0,4,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (10,'POL','2008-09-10 20:00:00',57,1,0,0,0,0,0,4,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (11,'POL','2008-09-11 20:00:00',59,2,0,0,0,2,0,4,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (12,'POL','2008-09-12 20:00:00',60,1,0,0,0,14,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (13,'POL','2008-09-13 20:00:00',61,1,0,0,0,9,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (14,'POL','2008-09-14 20:00:00',61,0,0,0,0,10,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (15,'POL','2008-09-15 20:00:00',73,12,0,0,0,34,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (16,'POL','2008-09-16 20:00:00',82,9,0,0,0,7,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (17,'POL','2008-09-17 20:00:00',85,3,0,0,0,22,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (18,'POL','2008-09-18 20:00:00',87,2,0,0,0,16,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (19,'POL','2008-09-19 20:00:00',88,1,0,0,0,4,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (20,'POL','2008-09-20 20:00:00',89,1,0,0,0,5,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (21,'POL','2008-09-21 20:00:00',92,3,0,0,0,3,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (22,'POL','2008-09-22 20:00:00',94,2,0,0,0,5,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (23,'POL','2008-09-23 20:00:00',94,0,0,0,0,6,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (24,'POL','2008-09-24 20:00:00',96,2,0,0,0,16,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (25,'POL','2008-09-25 20:00:00',96,0,0,0,0,8,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (26,'POL','2008-09-26 20:00:00',96,0,0,0,0,6,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (27,'POL','2008-09-27 20:00:00',97,1,0,0,0,8,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (28,'POL','2008-09-28 20:00:00',100,3,0,0,0,3,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (29,'POL','2008-09-29 20:00:00',100,0,0,0,0,4,0,5,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (30,'POL','2008-09-30 20:00:00',102,2,0,0,0,3,0,6,0,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (31,'POL','2008-10-01 20:00:00',118,3,5950,59050,26,4,58440,7,240,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (32,'POL','2008-10-02 20:00:00',108,2,8870,56130,52,10,49910,6,460,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (33,'POL','2008-10-03 20:00:00',110,4,10340,54660,36,9,53100,6,670,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (34,'POL','2008-10-04 20:00:00',111,3,13100,50900,28,7,49900,7,150,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (35,'POL','2008-10-05 20:00:00',99,2,15470,48530,19,8,47480,7,50,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (36,'POL','2008-10-06 20:00:00',95,5,19150,44850,34,8,43800,8,160,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (37,'POL','2008-10-07 20:00:00',94,7,22850,41150,25,22,39070,8,140,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (38,'POL','2008-10-08 20:00:00',94,2,24630,39370,20,7,36720,8,140,0,0,0,0,0,0);
INSERT INTO `stats` VALUES (39,'POL','2008-10-09 20:00:00',94,3,22550,41750,54,4,38950,8,170,16,0,0,0,0,0);
INSERT INTO `stats` VALUES (40,'POL','2008-10-10 20:00:00',96,3,20870,43430,69,21,38200,8,360,22,0,0,0,0,0);
INSERT INTO `stats` VALUES (41,'POL','2008-10-11 20:00:00',96,2,21120,43180,45,5,36710,8,200,24,0,0,0,0,0);
INSERT INTO `stats` VALUES (42,'POL','2008-10-12 20:00:00',96,1,20440,43860,37,9,34240,8,190,25,0,0,0,0,0);
INSERT INTO `stats` VALUES (43,'POL','2008-10-13 20:00:00',93,1,21130,43170,31,5,30430,8,410,26,0,0,0,0,0);
INSERT INTO `stats` VALUES (44,'POL','2008-10-14 20:00:00',95,2,22570,41730,23,8,28050,8,560,27,0,0,0,0,0);
INSERT INTO `stats` VALUES (45,'POL','2008-10-15 20:00:00',91,1,23810,40490,25,3,26090,8,770,28,0,0,0,0,0);
INSERT INTO `stats` VALUES (46,'POL','2008-10-16 20:00:00',433,144,43760,195540,117,251,177020,35,320,45,0,0,0,0,0);
INSERT INTO `stats` VALUES (47,'POL','2008-10-17 20:00:00',501,80,87690,151360,711,214,129090,35,710,57,0,0,0,0,0);
INSERT INTO `stats` VALUES (48,'POL','2008-10-18 20:00:00',515,30,97470,141580,598,93,112970,35,700,62,0,0,0,0,0);
INSERT INTO `stats` VALUES (49,'POL','2008-10-19 20:00:00',532,30,118760,120140,272,55,92110,35,600,64,0,0,0,0,0);
INSERT INTO `stats` VALUES (50,'POL','2008-10-20 20:00:00',568,51,136740,100160,497,137,62120,33,1020,73,0,0,0,0,0);
INSERT INTO `stats` VALUES (51,'POL','2008-10-21 20:00:00',580,17,140530,96370,244,115,49990,33,1600,77,0,0,0,0,0);
INSERT INTO `stats` VALUES (52,'POL','2008-10-22 20:00:00',589,13,146950,90250,259,96,38840,33,1010,81,0,0,0,0,0);
INSERT INTO `stats` VALUES (53,'POL','2008-10-23 20:00:00',601,13,153750,83450,315,115,30990,33,1100,86,0,0,0,0,0);
INSERT INTO `stats` VALUES (54,'POL','2008-10-24 20:00:00',611,11,174120,63080,368,70,21560,34,800,91,1,0,0,0,0);
INSERT INTO `stats` VALUES (55,'POL','2008-10-25 20:00:00',625,17,184070,53110,174,94,11570,34,930,91,3,0,0,0,0);
INSERT INTO `stats` VALUES (56,'POL','2008-10-26 20:00:00',640,15,195040,102140,181,79,58800,34,1340,93,1,0,0,0,0);
INSERT INTO `stats` VALUES (57,'POL','2008-10-27 20:00:00',648,10,203390,93790,232,133,48640,34,1400,96,2,0,0,0,0);
INSERT INTO `stats` VALUES (58,'POL','2008-10-28 20:00:00',652,6,209960,87220,216,197,39620,33,1500,99,2,0,0,0,0);
INSERT INTO `stats` VALUES (59,'POL','2008-10-29 20:00:00',662,10,215800,81380,393,117,29720,33,1610,101,0,0,0,0,0);
INSERT INTO `stats` VALUES (60,'POL','2008-10-30 20:00:00',669,10,228310,69080,235,72,19520,34,1320,103,3,53,0,0,0);
INSERT INTO `stats` VALUES (61,'POL','2008-10-31 20:00:00',555,16,211030,86390,1526,242,41730,32,1160,106,130,65,0,0,0);
INSERT INTO `stats` VALUES (62,'POL','2008-11-01 20:00:00',534,25,213670,83250,824,51,44420,32,950,100,40,70,0,0,0);
INSERT INTO `stats` VALUES (63,'POL','2008-11-02 20:00:00',510,16,210200,86720,476,67,43760,31,1670,99,35,73,0,0,0);
INSERT INTO `stats` VALUES (64,'POL','2008-11-03 20:00:00',507,16,216520,80400,962,102,40850,33,1120,101,18,75,0,0,0);
INSERT INTO `stats` VALUES (65,'POL','2008-11-04 20:00:00',461,3,216380,80540,392,187,39720,30,1200,102,11,76,0,0,0);
INSERT INTO `stats` VALUES (66,'POL','2008-11-05 20:00:00',455,13,216380,80540,353,170,37720,30,1020,102,3,76,0,0,0);
INSERT INTO `stats` VALUES (67,'POL','2008-11-06 20:00:00',462,3,216680,80140,378,187,37720,30,830,102,6,76,0,0,0);
INSERT INTO `stats` VALUES (68,'POL','2008-11-07 20:00:00',465,13,208410,88510,745,68,33450,30,1010,102,9,78,0,0,0);
INSERT INTO `stats` VALUES (69,'POL','2008-11-08 20:00:00',457,5,208410,88510,802,64,39430,30,3820,98,13,78,10,0,0);
INSERT INTO `stats` VALUES (70,'POL','2008-11-09 20:00:00',444,4,213650,82920,436,145,34030,29,950,97,9,80,12,0,0);
INSERT INTO `stats` VALUES (71,'POL','2008-11-10 20:00:00',456,17,208200,88640,740,228,37000,29,1260,97,4,72,14,0,0);
INSERT INTO `stats` VALUES (72,'POL','2008-11-11 20:00:00',453,3,209920,88060,505,167,33110,30,2470,99,6,70,14,0,0);
INSERT INTO `stats` VALUES (73,'POL','2008-11-12 20:00:00',454,6,212760,85220,689,89,32170,26,3260,101,5,71,15,0,0);
INSERT INTO `stats` VALUES (74,'POL','2008-11-13 20:00:00',448,2,215030,82950,593,85,29380,26,2480,100,8,71,14,0,0);
INSERT INTO `stats` VALUES (75,'POL','2008-11-14 20:00:00',446,4,214660,84820,679,77,29640,27,4710,100,6,72,14,0,0);
INSERT INTO `stats` VALUES (76,'POL','2008-11-15 20:00:00',419,9,220640,78880,745,61,28710,26,920,97,29,71,12,0,0);
INSERT INTO `stats` VALUES (77,'POL','2008-11-16 20:00:00',403,10,221240,78280,411,52,26380,26,660,102,26,70,9,0,0);
INSERT INTO `stats` VALUES (78,'POL','2008-11-17 20:00:00',394,8,224760,75100,703,123,23300,25,1390,103,17,72,8,0,0);
INSERT INTO `stats` VALUES (79,'POL','2008-11-18 20:00:00',372,4,228240,71620,754,161,19050,25,650,104,25,74,9,0,0);
INSERT INTO `stats` VALUES (80,'POL','2008-11-19 20:00:00',361,3,224480,75280,700,242,18800,24,1150,104,13,76,6,0,0);
INSERT INTO `stats` VALUES (81,'POL','2008-11-20 20:00:00',357,6,226570,73190,643,218,15720,26,1180,104,10,76,7,0,0);
INSERT INTO `stats` VALUES (82,'POL','2008-11-21 20:00:00',353,6,225600,73950,358,165,16230,26,1180,105,10,77,7,0,0);
INSERT INTO `stats` VALUES (83,'POL','2008-11-22 20:00:00',359,13,236170,63590,680,295,8050,28,600,108,6,74,11,0,0);
INSERT INTO `stats` VALUES (84,'POL','2008-11-23 20:00:00',355,12,241130,130140,651,123,77360,27,1850,104,4,67,8,0,0);
INSERT INTO `stats` VALUES (85,'POL','2008-11-24 20:00:00',359,11,250430,120840,686,142,73290,27,1670,104,5,66,6,0,0);
INSERT INTO `stats` VALUES (86,'POL','2008-11-25 20:00:00',359,8,252880,118390,645,242,68340,28,1350,106,8,68,8,0,0);
INSERT INTO `stats` VALUES (87,'POL','2008-11-26 20:00:00',360,7,253980,117290,608,229,62790,27,1750,108,4,68,8,0,0);
INSERT INTO `stats` VALUES (88,'POL','2008-11-27 20:00:00',365,9,255780,115490,610,162,60550,27,1840,108,3,68,7,0,0);
INSERT INTO `stats` VALUES (89,'POL','2008-11-28 20:00:00',369,6,256140,115130,537,111,57510,27,2340,109,2,70,8,0,0);
INSERT INTO `stats` VALUES (90,'POL','2008-11-29 20:00:00',374,9,264500,106770,629,66,48780,26,650,112,4,67,7,0,0);
INSERT INTO `stats` VALUES (91,'POL','2008-11-30 20:00:00',358,2,263060,108040,692,58,45350,26,1130,111,18,68,9,0,0);
INSERT INTO `stats` VALUES (92,'POL','2008-12-01 20:00:00',361,7,271900,99200,611,168,38100,26,1340,111,4,66,9,138,0);
INSERT INTO `stats` VALUES (93,'POL','2008-12-02 20:00:00',361,7,276450,94650,605,144,33430,27,1160,114,4,67,7,141,0);
INSERT INTO `stats` VALUES (94,'POL','2008-12-03 20:00:00',350,3,276620,93980,616,200,32590,26,1620,107,13,65,8,135,0);
INSERT INTO `stats` VALUES (95,'POL','2008-12-04 20:00:00',339,3,280590,91610,562,262,29720,26,1370,103,13,62,11,126,0);
INSERT INTO `stats` VALUES (96,'POL','2008-12-05 20:00:00',334,3,279350,92850,518,134,30330,26,1780,103,7,62,10,109,0);
INSERT INTO `stats` VALUES (97,'POL','2008-12-06 20:00:00',329,3,282310,89890,473,64,26490,25,860,96,6,62,10,101,0);
INSERT INTO `stats` VALUES (98,'POL','2008-12-07 20:00:00',329,10,285850,86350,464,61,22140,25,850,97,10,60,8,102,0);
INSERT INTO `stats` VALUES (99,'POL','2008-12-08 20:00:00',311,1,284070,88130,480,92,23270,25,1470,95,13,57,8,105,0);
INSERT INTO `stats` VALUES (100,'POL','2008-12-09 20:00:00',307,2,281710,90490,243,205,19780,25,1370,96,6,55,9,115,0);
INSERT INTO `stats` VALUES (101,'POL','2008-12-10 20:00:00',303,3,280390,91810,487,109,17690,25,1180,95,7,53,9,113,0);
INSERT INTO `stats` VALUES (102,'POL','2008-12-11 20:00:00',294,2,285520,86680,460,153,12750,25,1760,94,11,50,7,118,0);
INSERT INTO `stats` VALUES (103,'POL','2008-12-12 20:00:00',292,3,292720,79480,444,128,2520,25,2130,96,5,49,4,102,0);
INSERT INTO `stats` VALUES (104,'POL','2008-12-13 20:00:00',288,6,303070,69130,487,80,-3070,24,1990,91,10,49,5,139,0);
INSERT INTO `stats` VALUES (105,'POL','2008-12-14 20:00:00',271,2,302590,69610,444,67,-4720,24,1120,93,19,48,5,107,0);
INSERT INTO `stats` VALUES (106,'POL','2008-12-15 20:00:00',268,5,298010,74190,439,79,-7180,24,1220,93,8,47,5,109,0);
INSERT INTO `stats` VALUES (107,'POL','2008-12-16 20:00:00',261,8,291760,80440,479,82,-4660,24,1750,94,15,46,4,117,0);
INSERT INTO `stats` VALUES (108,'POL','2008-12-17 20:00:00',265,11,283560,88640,518,92,900,24,1180,91,7,52,5,114,0);
INSERT INTO `stats` VALUES (109,'POL','2008-12-18 20:00:00',261,4,282070,90130,451,154,5130,24,1380,88,8,52,7,107,0);
INSERT INTO `stats` VALUES (110,'POL','2008-12-19 20:00:00',257,2,273340,98860,432,97,11940,24,1430,89,5,53,7,97,0);
INSERT INTO `stats` VALUES (111,'POL','2008-12-20 20:00:00',252,0,276090,96110,389,45,9620,24,560,88,3,51,5,85,0);
INSERT INTO `stats` VALUES (112,'POL','2008-12-21 20:00:00',246,2,275660,96570,367,40,8840,24,460,87,4,50,5,80,0);
INSERT INTO `stats` VALUES (113,'POL','2008-12-22 20:00:00',238,1,278940,93290,379,37,7980,23,910,87,9,49,5,89,0);
INSERT INTO `stats` VALUES (114,'POL','2008-12-23 20:00:00',232,3,266740,108490,789,73,17850,24,5420,87,5,43,5,89,0);
INSERT INTO `stats` VALUES (115,'POL','2008-12-24 20:00:00',235,4,269440,105790,344,59,14760,24,1140,87,1,42,5,87,0);
INSERT INTO `stats` VALUES (116,'POL','2008-12-25 20:00:00',231,1,270270,104960,389,35,14200,24,930,87,5,41,4,70,0);
INSERT INTO `stats` VALUES (117,'POL','2008-12-26 20:00:00',227,2,270640,104590,313,53,12970,23,370,88,6,36,4,80,0);
INSERT INTO `stats` VALUES (118,'POL','2008-12-27 20:00:00',227,3,267920,107310,362,16,9090,23,270,89,3,35,4,116,0);
INSERT INTO `stats` VALUES (119,'POL','2008-12-28 20:00:00',215,2,264040,111190,371,34,12570,23,480,88,10,36,4,73,0);
INSERT INTO `stats` VALUES (120,'POL','2008-12-29 20:00:00',210,5,260930,114300,337,87,14630,24,690,86,9,36,3,89,0);
INSERT INTO `stats` VALUES (121,'POL','2008-12-30 20:00:00',206,1,253400,121830,348,90,19200,24,550,86,5,36,3,83,0);
INSERT INTO `stats` VALUES (122,'POL','2008-12-31 20:00:00',200,4,250400,124830,349,116,15960,24,720,88,9,35,3,82,0);
INSERT INTO `stats` VALUES (123,'POL','2009-01-01 20:00:00',190,1,250960,124270,289,24,15110,24,450,88,12,33,5,52,0);
INSERT INTO `stats` VALUES (124,'POL','2009-01-02 20:00:00',185,2,250700,124530,279,52,15040,24,940,84,7,32,4,72,0);
INSERT INTO `stats` VALUES (125,'POL','2009-01-03 20:00:00',182,2,251690,123540,313,67,13370,22,670,82,5,32,3,71,0);
INSERT INTO `stats` VALUES (126,'POL','2009-01-04 20:00:00',182,1,261560,113670,285,43,11560,22,1250,82,1,30,3,68,0);
INSERT INTO `stats` VALUES (127,'POL','2009-01-05 20:00:00',180,1,259200,116030,889,48,13540,21,450,81,3,64,3,77,0);
INSERT INTO `stats` VALUES (128,'POL','2009-01-06 20:00:00',181,6,270920,104310,425,43,16480,22,900,80,5,64,1,81,0);
INSERT INTO `stats` VALUES (129,'POL','2009-01-07 20:00:00',184,4,270590,104640,360,36,16350,22,340,80,1,64,2,85,0);
INSERT INTO `stats` VALUES (130,'POL','2009-01-08 20:00:00',188,5,274250,100980,377,128,12230,21,470,81,1,65,1,84,0);
INSERT INTO `stats` VALUES (131,'POL','2009-01-09 20:00:00',189,2,259330,115900,415,85,9280,22,420,82,1,66,1,84,0);
INSERT INTO `stats` VALUES (132,'POL','2009-01-10 20:00:00',188,0,263800,111430,350,61,4500,22,590,81,1,67,1,102,0);
INSERT INTO `stats` VALUES (133,'POL','2009-01-11 20:00:00',174,2,252060,123170,347,62,22450,21,820,79,14,65,1,79,0);
INSERT INTO `stats` VALUES (134,'POL','2009-01-12 20:00:00',172,1,248600,126630,372,168,20410,21,780,79,3,66,1,75,0);
INSERT INTO `stats` VALUES (135,'POL','2009-01-13 20:00:00',169,1,245390,129840,335,177,21330,19,590,77,5,66,1,75,0);
INSERT INTO `stats` VALUES (136,'POL','2009-01-14 20:00:00',167,1,247080,128150,311,85,22820,18,850,75,3,66,1,81,0);
INSERT INTO `stats` VALUES (137,'POL','2009-01-15 20:00:00',165,3,256510,118720,306,295,21460,18,1160,71,5,66,1,76,0);
INSERT INTO `stats` VALUES (138,'POL','2009-01-16 20:00:00',164,1,260930,114300,297,132,17580,18,1010,71,2,66,1,67,0);
INSERT INTO `stats` VALUES (139,'POL','2009-01-17 20:00:00',164,2,261910,113320,313,40,16260,18,1860,73,2,66,0,68,0);
INSERT INTO `stats` VALUES (140,'POL','2009-01-18 20:00:00',161,0,263770,111460,299,63,13550,18,1550,73,3,67,0,73,0);
INSERT INTO `stats` VALUES (141,'POL','2009-01-19 20:00:00',161,1,269420,105810,312,156,11010,19,1550,72,1,68,2,77,0);
INSERT INTO `stats` VALUES (142,'POL','2009-01-20 20:00:00',161,3,271630,103600,351,175,14930,19,2000,72,3,68,1,76,0);
INSERT INTO `stats` VALUES (143,'POL','2009-01-21 20:00:00',157,1,269860,105370,326,162,13570,18,1520,72,5,68,1,77,0);
INSERT INTO `stats` VALUES (144,'POL','2009-01-22 20:00:00',161,6,270010,105220,337,230,12640,18,3160,73,2,72,1,85,0);
INSERT INTO `stats` VALUES (145,'POL','2009-01-23 20:00:00',159,2,262980,112250,476,161,16890,18,2120,73,2,78,2,78,0);
INSERT INTO `stats` VALUES (146,'POL','2009-01-24 20:00:00',154,1,256570,118660,443,75,19760,17,2940,71,3,79,1,92,0);
INSERT INTO `stats` VALUES (147,'POL','2009-01-25 20:00:00',151,3,217070,158160,392,46,30370,17,1310,64,6,81,1,71,0);
INSERT INTO `stats` VALUES (148,'POL','2009-01-26 20:00:00',149,1,207880,167350,354,168,31240,17,1070,65,3,82,1,75,0);
INSERT INTO `stats` VALUES (149,'POL','2009-01-27 20:00:00',148,1,210230,165000,334,157,28110,17,590,65,1,70,2,72,0);
INSERT INTO `stats` VALUES (150,'POL','2009-01-28 20:00:00',146,1,211980,163250,323,122,24900,18,1130,66,3,70,2,79,0);
INSERT INTO `stats` VALUES (151,'POL','2009-01-29 20:00:00',146,4,196000,179230,375,143,26290,18,850,67,3,70,1,75,0);
INSERT INTO `stats` VALUES (152,'POL','2009-01-30 20:00:00',145,2,198780,176450,307,64,21040,18,610,67,4,68,0,74,0);
INSERT INTO `stats` VALUES (153,'POL','2009-01-31 20:00:00',147,3,200370,174860,315,34,18790,18,730,67,1,69,0,68,0);
INSERT INTO `stats` VALUES (154,'POL','2009-02-01 20:00:00',148,4,198170,177060,306,67,17210,17,550,59,3,69,1,67,0);
INSERT INTO `stats` VALUES (155,'POL','2009-02-02 20:00:00',149,1,199540,175690,272,121,15170,17,1130,59,0,69,1,68,0);
INSERT INTO `stats` VALUES (156,'POL','2009-02-03 20:00:00',149,1,200290,174940,260,87,12730,17,1100,53,1,68,1,68,0);
INSERT INTO `stats` VALUES (157,'POL','2009-02-04 20:00:00',149,1,240800,134430,309,66,10040,18,620,54,1,69,2,68,0);
INSERT INTO `stats` VALUES (158,'POL','2009-02-05 20:00:00',151,3,240060,135170,276,59,11740,18,3520,54,1,68,2,68,0);
INSERT INTO `stats` VALUES (159,'POL','2009-02-06 20:00:00',146,2,243370,131860,286,134,9420,18,560,54,7,67,1,78,0);
INSERT INTO `stats` VALUES (160,'POL','2009-02-07 20:00:00',146,4,246380,128850,318,34,6440,18,960,54,2,67,1,96,0);
INSERT INTO `stats` VALUES (161,'POL','2009-02-08 20:00:00',145,3,246870,128360,316,59,4720,18,480,54,4,67,1,92,0);
INSERT INTO `stats` VALUES (162,'POL','2009-02-09 20:00:00',146,5,246530,128700,354,358,-3030,18,590,54,4,67,1,87,0);
INSERT INTO `stats` VALUES (163,'POL','2009-02-10 20:00:00',146,1,249830,125400,325,176,-4260,18,500,54,1,68,0,74,0);
INSERT INTO `stats` VALUES (164,'POL','2009-02-11 20:00:00',149,4,249560,125670,313,80,-3690,18,780,55,1,68,0,74,0);
INSERT INTO `stats` VALUES (165,'POL','2009-02-12 20:00:00',151,4,249226,126004,357,133,-3471,19,800,54,2,69,1,79,0);
INSERT INTO `stats` VALUES (166,'POL','2009-02-13 20:00:00',151,2,255009,120221,480,90,14177,19,660,52,2,70,0,77,0);
INSERT INTO `stats` VALUES (167,'POL','2009-02-14 20:00:00',152,4,255288,119942,345,97,14560,19,624,52,3,68,0,72,0);
INSERT INTO `stats` VALUES (168,'POL','2009-02-15 20:00:00',153,1,254064,121166,303,74,15347,19,993,53,0,67,2,65,0);
INSERT INTO `stats` VALUES (169,'POL','2009-02-16 20:00:00',152,1,255632,119598,270,89,13572,20,570,53,2,67,2,65,0);
INSERT INTO `stats` VALUES (170,'POL','2009-02-17 20:00:00',151,1,260365,114865,332,278,12030,20,990,53,2,67,3,76,0);
INSERT INTO `stats` VALUES (171,'POL','2009-02-18 20:00:00',150,3,262047,113183,352,215,10811,20,1030,53,2,67,1,78,0);
INSERT INTO `stats` VALUES (172,'POL','2009-02-19 20:00:00',150,1,262890,112340,323,169,9711,20,1300,54,1,68,1,71,0);
INSERT INTO `stats` VALUES (173,'POL','2009-02-20 20:00:00',173,25,266562,108668,371,211,6253,20,1242,54,1,69,1,96,0);
INSERT INTO `stats` VALUES (174,'POL','2009-02-21 20:00:00',180,9,268819,106411,458,166,4325,20,424,56,2,72,1,117,0);
INSERT INTO `stats` VALUES (175,'POL','2009-02-22 20:00:00',181,5,269660,105570,348,121,3269,20,600,55,6,70,1,77,0);
INSERT INTO `stats` VALUES (176,'POL','2009-02-23 20:00:00',180,2,277023,98207,323,211,1390,20,1000,55,3,70,2,83,0);
INSERT INTO `stats` VALUES (177,'POL','2009-02-24 20:00:00',181,5,261256,113974,462,388,7205,21,920,54,4,74,2,89,0);
INSERT INTO `stats` VALUES (178,'POL','2009-02-25 20:00:00',180,1,267458,232542,416,391,130765,21,1024,54,2,75,1,87,0);
INSERT INTO `stats` VALUES (179,'POL','2009-02-26 20:00:00',180,1,271328,228672,350,236,123447,21,1010,55,1,76,1,83,0);
INSERT INTO `stats` VALUES (180,'POL','2009-02-27 20:00:00',185,7,273751,226249,394,190,121605,21,1450,55,2,77,1,94,0);
INSERT INTO `stats` VALUES (181,'POL','2009-02-28 20:00:00',189,5,263518,236482,382,59,110414,20,1631,57,1,77,2,80,0);
INSERT INTO `stats` VALUES (182,'POL','2009-03-01 20:00:00',184,2,256795,243205,406,21,110540,20,800,56,7,77,2,84,0);
INSERT INTO `stats` VALUES (183,'POL','2009-03-02 20:00:00',185,2,257118,242882,423,61,99414,20,7575,56,1,76,0,89,0);
INSERT INTO `stats` VALUES (184,'POL','2009-03-03 20:00:00',188,3,264473,235527,444,167,95607,19,2250,57,0,78,0,94,0);
INSERT INTO `stats` VALUES (185,'POL','2009-03-04 20:00:00',189,5,266729,233271,452,215,95659,19,6580,58,3,79,1,96,0);
INSERT INTO `stats` VALUES (186,'POL','2009-03-05 20:00:00',187,2,252042,247958,488,291,119683,19,7252,61,6,80,1,95,0);
INSERT INTO `stats` VALUES (187,'POL','2009-03-06 20:00:00',188,3,248027,251973,417,294,118335,20,2120,61,2,81,1,94,0);
INSERT INTO `stats` VALUES (188,'POL','2009-03-07 20:00:00',187,8,268052,231948,468,255,116796,20,1700,64,10,81,1,122,0);
INSERT INTO `stats` VALUES (189,'POL','2009-03-08 20:00:00',180,2,271251,228749,473,195,114739,20,900,64,9,82,1,128,0);
INSERT INTO `stats` VALUES (190,'POL','2009-03-09 20:00:00',176,0,274403,225597,487,234,113199,21,1354,65,4,78,2,104,0);
INSERT INTO `stats` VALUES (191,'POL','2009-03-10 20:00:00',178,4,273789,226211,423,424,110594,22,4160,65,0,79,0,98,0);
INSERT INTO `stats` VALUES (192,'POL','2009-03-11 20:00:00',178,5,272420,227580,482,440,113793,22,3880,67,4,79,1,102,0);
INSERT INTO `stats` VALUES (193,'POL','2009-03-12 20:00:00',189,4,269517,230483,495,407,107725,23,3460,68,0,80,0,101,0);
INSERT INTO `stats` VALUES (194,'POL','2009-03-13 20:00:00',192,2,282179,217821,465,407,104453,24,2260,68,0,79,2,99,0);
INSERT INTO `stats` VALUES (195,'POL','2009-03-14 20:00:00',188,4,283969,216031,416,289,102112,23,870,69,1,79,2,95,0);
INSERT INTO `stats` VALUES (196,'POL','2009-03-15 20:00:00',191,3,287110,212890,447,108,103100,24,744,69,3,79,3,93,0);
INSERT INTO `stats` VALUES (197,'POL','2009-03-16 20:00:00',190,1,285433,214567,411,230,101842,23,1210,69,1,79,3,100,0);
INSERT INTO `stats` VALUES (198,'POL','2009-03-17 20:00:00',187,2,286632,213368,662,219,96742,24,2600,69,1,79,3,97,0);
INSERT INTO `stats` VALUES (199,'POL','2009-03-18 20:00:00',187,2,284579,215421,435,331,94016,21,1950,70,2,79,3,98,0);
INSERT INTO `stats` VALUES (200,'POL','2009-03-19 20:00:00',188,3,288050,211950,380,288,93111,20,1040,70,1,77,2,85,0);
INSERT INTO `stats` VALUES (201,'POL','2009-03-20 20:00:00',188,0,291677,208323,352,225,88148,20,1260,70,0,77,2,91,0);
INSERT INTO `stats` VALUES (202,'POL','2009-03-21 20:00:00',189,1,294052,205948,396,99,85567,20,933,70,1,77,2,111,0);
INSERT INTO `stats` VALUES (203,'POL','2009-03-22 20:00:00',188,1,298913,201087,407,176,84229,20,1045,70,2,77,1,91,0);
INSERT INTO `stats` VALUES (204,'POL','2009-03-23 20:00:00',176,2,287453,212547,430,296,91836,20,620,73,12,76,1,101,1);
INSERT INTO `stats` VALUES (205,'POL','2009-03-24 20:00:00',173,1,284018,215982,411,271,89247,20,990,73,4,75,1,98,-243);
INSERT INTO `stats` VALUES (206,'POL','2009-03-25 20:00:00',173,4,277590,222410,385,294,102971,20,1577,71,3,74,2,90,-237);
INSERT INTO `stats` VALUES (207,'POL','2009-03-26 20:00:00',172,1,279797,220203,367,182,100578,20,1097,72,2,74,1,94,-235);
INSERT INTO `stats` VALUES (208,'POL','2009-03-27 20:00:00',171,1,284701,215299,347,156,97296,20,1105,72,2,73,2,88,-230);
INSERT INTO `stats` VALUES (209,'POL','2009-03-28 20:00:00',171,2,296099,203901,365,180,94484,20,695,73,2,69,1,90,-205);
INSERT INTO `stats` VALUES (210,'POL','2009-03-29 20:00:00',167,2,294484,205516,343,144,94693,20,401,73,6,69,1,79,-183);
INSERT INTO `stats` VALUES (211,'POL','2009-03-30 20:00:00',167,2,295963,204037,314,94,93098,20,1080,73,2,69,1,93,-172);
INSERT INTO `stats` VALUES (212,'POL','2009-03-31 20:00:00',166,0,290274,209726,354,164,88468,18,2650,74,1,68,0,86,-129);
INSERT INTO `stats` VALUES (213,'POL','2009-04-01 20:00:00',168,3,280916,219084,311,169,87599,16,1420,76,1,68,0,86,-143);
INSERT INTO `stats` VALUES (214,'POL','2009-04-02 20:00:00',166,1,282331,217669,326,269,92869,16,6490,76,3,68,2,77,-105);
INSERT INTO `stats` VALUES (215,'POL','2009-04-03 20:00:00',172,5,292033,207967,552,205,84345,16,1614,76,1,68,2,88,-80);
INSERT INTO `stats` VALUES (216,'POL','2009-04-04 20:00:00',171,2,285478,214522,340,46,86086,16,1260,77,2,68,2,83,-82);
INSERT INTO `stats` VALUES (217,'POL','2009-04-05 20:00:00',163,0,285371,214629,353,33,84826,16,131,75,8,69,2,91,-69);
INSERT INTO `stats` VALUES (218,'POL','2009-04-06 20:00:00',162,2,263984,236016,358,479,83815,16,1110,76,3,68,4,87,90);
INSERT INTO `stats` VALUES (219,'POL','2009-04-07 20:00:00',158,0,254230,245770,646,291,85632,16,1311,76,4,68,3,83,93);
INSERT INTO `stats` VALUES (220,'POL','2009-04-08 20:00:00',159,2,249534,250466,331,178,84820,15,1940,77,1,68,3,80,88);
INSERT INTO `stats` VALUES (221,'POL','2009-04-09 20:00:00',160,4,247641,252359,292,113,84980,15,470,78,3,66,2,68,89);
INSERT INTO `stats` VALUES (222,'POL','2009-04-10 20:00:00',160,3,249802,250198,282,82,84223,15,1006,76,3,66,2,72,83);
INSERT INTO `stats` VALUES (223,'POL','2009-04-11 20:00:00',156,1,249767,250233,273,78,83730,15,456,76,3,65,1,66,105);
INSERT INTO `stats` VALUES (224,'POL','2009-04-12 20:00:00',152,1,247859,252706,273,84,82448,16,370,76,3,65,1,73,87);
INSERT INTO `stats` VALUES (225,'POL','2009-04-13 20:00:00',154,4,252696,247304,310,123,80035,16,600,76,2,65,1,78,-8);
INSERT INTO `stats` VALUES (226,'POL','2009-04-14 20:00:00',153,2,252154,247846,320,157,80981,16,1310,77,3,66,1,79,-55);
INSERT INTO `stats` VALUES (227,'POL','2009-04-15 20:00:00',156,5,252748,247252,324,147,80686,17,1810,77,2,66,1,82,-43);
INSERT INTO `stats` VALUES (228,'POL','2009-04-16 20:00:00',155,3,250820,249180,360,148,84167,17,1110,76,4,66,1,83,-47);
INSERT INTO `stats` VALUES (229,'POL','2009-04-17 20:00:00',154,1,254373,245627,361,86,77488,18,821,76,2,65,0,82,-29);
INSERT INTO `stats` VALUES (230,'POL','2009-04-18 20:00:00',154,4,255633,244367,350,168,76534,18,1064,77,4,65,0,78,-20);
INSERT INTO `stats` VALUES (231,'POL','2009-04-19 20:00:00',150,0,267238,232762,319,98,75422,18,361,77,4,66,1,78,-1);
INSERT INTO `stats` VALUES (232,'POL','2009-04-20 20:00:00',144,2,264365,235635,332,163,78520,16,740,77,8,65,1,91,36);
INSERT INTO `stats` VALUES (233,'POL','2009-04-21 20:00:00',144,3,278183,221817,377,294,75598,16,703,78,3,65,1,78,44);
INSERT INTO `stats` VALUES (234,'POL','2009-04-22 20:00:00',144,4,286629,213371,308,142,75037,16,660,79,4,66,1,75,45);
INSERT INTO `stats` VALUES (235,'POL','2009-04-23 20:00:00',140,1,286727,213273,336,119,74502,16,1050,80,5,67,1,79,54);
INSERT INTO `stats` VALUES (236,'POL','2009-04-24 20:00:00',144,5,309480,190520,318,216,74194,16,1081,80,1,65,1,85,74);
INSERT INTO `stats` VALUES (237,'POL','2009-04-25 20:00:00',153,10,289216,210485,174,73,73593,16,705,67,0,64,1,80,64);
INSERT INTO `stats` VALUES (238,'POL','2009-04-26 20:00:00',157,6,287883,211798,283,88,72951,15,850,66,1,64,1,84,6);
INSERT INTO `stats` VALUES (239,'POL','2009-04-27 20:00:00',153,2,287870,212130,230,69,73329,15,190,66,3,63,1,75,3);
INSERT INTO `stats` VALUES (240,'POL','2009-04-28 20:00:00',152,1,228093,271908,197,107,134591,15,7535,64,2,62,1,72,36);
INSERT INTO `stats` VALUES (241,'POL','2009-04-29 20:00:00',154,3,225123,274877,120,164,134386,15,930,64,1,62,1,74,-64);
INSERT INTO `stats` VALUES (242,'POL','2009-04-30 20:00:00',155,4,196022,303978,151,107,134935,15,1685,64,3,61,0,76,192);
INSERT INTO `stats` VALUES (243,'POL','2009-05-01 20:00:00',156,8,200251,299489,210,94,135082,14,741,64,7,61,0,74,211);
INSERT INTO `stats` VALUES (244,'POL','2009-05-02 20:00:00',159,5,208733,291267,240,53,121220,14,300,64,2,61,0,73,202);
INSERT INTO `stats` VALUES (245,'POL','2009-05-03 20:00:00',149,8,209826,290174,258,36,119987,14,300,64,0,61,0,82,212);
INSERT INTO `stats` VALUES (246,'POL','2009-05-04 20:00:00',171,24,194372,305628,304,95,118341,14,800,63,2,62,0,97,234);
INSERT INTO `stats` VALUES (247,'POL','2009-05-05 20:00:00',175,7,198639,301361,281,95,116583,14,500,63,3,61,0,71,229);
INSERT INTO `stats` VALUES (248,'POL','2009-05-06 20:00:00',169,3,193512,306488,275,104,117388,14,580,60,5,61,0,69,249);
INSERT INTO `stats` VALUES (249,'POL','2009-05-07 20:00:00',168,2,191473,308527,237,69,115992,13,390,60,3,62,1,63,258);
INSERT INTO `stats` VALUES (250,'POL','2009-05-08 20:00:00',169,3,183861,316139,230,104,111829,13,420,59,1,61,1,55,258);
INSERT INTO `stats` VALUES (251,'POL','2009-05-09 20:00:00',168,5,187241,312759,215,86,110995,13,570,59,7,60,0,55,269);
INSERT INTO `stats` VALUES (252,'POL','2009-05-10 20:00:00',167,3,187307,312693,215,82,109705,13,571,59,4,60,0,58,299);
INSERT INTO `stats` VALUES (253,'POL','2009-05-11 20:00:00',164,1,187999,312001,186,27,108948,13,600,59,3,61,0,60,271);
INSERT INTO `stats` VALUES (254,'POL','2009-05-12 20:00:00',164,1,185836,314164,139,69,108515,13,947,59,3,61,0,59,246);
INSERT INTO `stats` VALUES (255,'POL','2009-05-13 20:00:00',165,3,185297,314703,128,28,109571,13,830,59,2,61,0,58,243);
INSERT INTO `stats` VALUES (256,'POL','2009-05-14 20:00:00',166,2,190214,309786,186,72,110762,13,1970,59,1,62,0,64,230);
INSERT INTO `stats` VALUES (257,'POL','2009-05-15 20:00:00',164,3,190508,309492,122,74,110033,13,589,59,3,62,0,61,243);
INSERT INTO `stats` VALUES (258,'POL','2009-05-16 20:00:00',162,1,191669,308331,108,35,108646,13,255,59,3,62,0,49,252);
INSERT INTO `stats` VALUES (259,'POL','2009-05-17 20:00:00',161,1,192199,307801,108,25,107070,13,320,58,2,62,0,53,257);
INSERT INTO `stats` VALUES (260,'POL','2009-05-18 20:00:00',149,1,190559,309441,117,23,105450,13,350,58,14,62,0,56,266);
INSERT INTO `stats` VALUES (261,'POL','2009-05-19 20:00:00',130,5,192719,307281,152,62,105511,14,281,58,23,62,0,60,248);
INSERT INTO `stats` VALUES (262,'POL','2009-05-20 20:00:00',123,1,192837,307163,147,29,105223,14,301,58,8,61,0,54,250);
INSERT INTO `stats` VALUES (263,'POL','2009-05-21 20:00:00',115,1,193642,306358,111,47,103908,14,530,58,9,61,0,54,235);
INSERT INTO `stats` VALUES (264,'POL','2009-05-22 20:00:00',112,0,192063,307937,111,41,103019,15,846,58,3,59,1,45,237);
INSERT INTO `stats` VALUES (265,'POL','2009-05-23 20:00:00',111,1,190767,309233,139,54,104692,15,320,58,3,60,1,49,239);
INSERT INTO `stats` VALUES (266,'POL','2009-05-24 20:00:00',106,0,191176,308824,100,25,104784,15,160,61,5,60,0,50,246);
INSERT INTO `stats` VALUES (267,'POL','2009-05-25 20:00:00',103,1,187429,312571,100,46,100516,15,276,61,4,60,0,50,241);
INSERT INTO `stats` VALUES (268,'POL','2009-05-26 20:00:00',104,1,186900,313100,107,118,101923,15,450,61,0,60,0,50,241);
INSERT INTO `stats` VALUES (269,'POL','2009-05-27 20:00:00',108,3,195755,304245,94,50,101532,15,230,62,2,60,0,52,236);
INSERT INTO `stats` VALUES (270,'POL','2009-05-28 20:00:00',108,1,195940,304060,95,138,101307,15,850,62,1,60,1,54,235);
INSERT INTO `stats` VALUES (271,'POL','2009-05-29 20:00:00',106,0,198242,301758,122,260,101900,15,455,63,2,60,1,54,195);
INSERT INTO `stats` VALUES (272,'POL','2009-05-30 20:00:00',108,6,198697,301303,104,151,101035,15,103,63,2,58,1,62,201);
INSERT INTO `stats` VALUES (273,'POL','2009-05-31 20:00:00',110,6,219974,280026,107,55,100068,15,94,63,2,58,1,61,184);
INSERT INTO `stats` VALUES (274,'POL','2009-06-01 20:00:00',107,0,332877,167123,141,146,36593,14,62,61,1,58,0,51,183);
INSERT INTO `stats` VALUES (275,'POL','2009-06-02 20:00:00',99,3,336305,163695,174,184,33137,14,151,61,43,58,0,51,183);
INSERT INTO `stats` VALUES (276,'POL','2009-06-03 20:00:00',101,0,336840,163160,175,155,32202,13,361,61,0,58,0,53,181);
INSERT INTO `stats` VALUES (277,'POL','2009-06-04 20:00:00',106,5,331776,167929,172,116,30823,13,210,61,1,58,0,66,179);
INSERT INTO `stats` VALUES (278,'POL','2009-06-05 20:00:00',108,5,332027,167432,220,194,29851,13,512,61,1,58,0,63,177);
INSERT INTO `stats` VALUES (279,'POL','2009-06-06 20:00:00',109,3,334475,164993,186,45,25559,13,300,62,2,58,0,55,174);
INSERT INTO `stats` VALUES (280,'POL','2009-06-07 20:00:00',112,2,332585,164358,201,49,23976,13,260,62,0,58,0,58,175);
INSERT INTO `stats` VALUES (281,'POL','2009-06-08 20:00:00',113,2,333563,163390,220,61,22903,14,121,62,1,58,0,61,184);
INSERT INTO `stats` VALUES (282,'POL','2009-06-09 20:00:00',112,1,335199,161764,204,215,21205,14,353,62,2,58,0,65,199);
INSERT INTO `stats` VALUES (283,'POL','2009-06-10 20:00:00',107,1,332957,164016,229,121,20994,14,1150,62,7,58,0,56,202);
INSERT INTO `stats` VALUES (284,'POL','2009-06-11 20:00:00',106,2,281407,215833,233,29,20331,13,450,62,7,58,0,58,205);
INSERT INTO `stats` VALUES (285,'POL','2009-06-12 20:00:00',106,1,286602,210648,176,69,19503,14,171,63,1,58,0,48,212);
INSERT INTO `stats` VALUES (286,'POL','2009-06-13 20:00:00',108,2,287509,209667,170,62,17972,14,70,61,2,58,0,62,210);
INSERT INTO `stats` VALUES (287,'POL','2009-06-14 20:00:00',103,1,287354,208165,185,33,16915,14,211,61,5,58,0,51,211);
INSERT INTO `stats` VALUES (288,'POL','2009-06-15 20:00:00',103,3,282039,213468,193,115,17425,15,110,61,2,59,0,57,216);
INSERT INTO `stats` VALUES (289,'POL','2009-06-16 20:00:00',101,1,285789,209728,197,84,12894,15,663,60,4,59,0,50,231);
INSERT INTO `stats` VALUES (290,'POL','2009-06-17 20:00:00',99,0,272483,223893,202,135,31964,15,2800,62,2,58,0,52,217);
INSERT INTO `stats` VALUES (291,'POL','2009-06-18 20:00:00',103,4,257327,239054,222,123,108785,15,541,62,1,58,0,59,218);
INSERT INTO `stats` VALUES (292,'POL','2009-06-19 20:00:00',102,1,255335,241206,234,105,108209,15,900,62,2,59,1,60,231);
INSERT INTO `stats` VALUES (293,'POL','2009-06-20 20:00:00',101,1,245618,251122,261,51,111158,15,751,62,2,59,1,58,236);
INSERT INTO `stats` VALUES (294,'POL','2009-06-21 20:00:00',102,2,246260,250538,430,164,117375,15,660,63,3,67,0,56,237);
INSERT INTO `stats` VALUES (295,'POL','2009-06-22 20:00:00',103,1,255683,241057,244,50,108442,15,680,63,0,69,1,51,231);
INSERT INTO `stats` VALUES (296,'POL','2009-06-23 20:00:00',103,1,256927,239813,232,77,108183,15,800,63,1,69,1,60,234);
INSERT INTO `stats` VALUES (297,'POL','2009-06-24 20:00:00',103,1,256366,240374,193,67,108262,15,1141,63,1,69,1,51,234);
INSERT INTO `stats` VALUES (298,'POL','2009-06-25 20:00:00',100,1,256718,240022,185,58,110553,15,650,62,3,70,1,53,237);
INSERT INTO `stats` VALUES (299,'POL','2009-06-26 20:00:00',100,1,254721,241453,219,96,108342,15,760,62,1,70,1,54,240);
INSERT INTO `stats` VALUES (300,'POL','2009-06-27 20:00:00',101,2,254055,240975,182,63,107862,15,600,62,2,69,1,50,246);
INSERT INTO `stats` VALUES (301,'POL','2009-06-28 20:00:00',98,0,246154,244239,226,89,111181,15,560,62,3,69,1,48,244);
INSERT INTO `stats` VALUES (302,'POL','2009-06-29 20:00:00',98,1,236808,253723,228,128,120106,16,500,62,1,70,1,60,231);
INSERT INTO `stats` VALUES (303,'POL','2009-06-30 20:00:00',100,5,234357,253336,201,166,120241,16,970,62,3,70,1,58,231);
INSERT INTO `stats` VALUES (304,'POL','2009-07-01 20:00:00',103,3,235097,253182,191,151,120051,15,541,62,1,70,1,49,236);
INSERT INTO `stats` VALUES (305,'POL','2009-07-02 20:00:00',104,1,230049,258236,203,122,119999,15,742,62,1,67,1,58,230);
INSERT INTO `stats` VALUES (306,'POL','2009-07-03 20:00:00',101,1,229552,258739,192,154,120796,15,861,63,3,59,1,49,223);
INSERT INTO `stats` VALUES (307,'POL','2009-07-04 20:00:00',101,1,228171,260126,180,109,118348,15,730,63,7,59,1,49,223);
INSERT INTO `stats` VALUES (308,'POL','2009-07-05 20:00:00',102,2,222552,267948,226,85,121868,15,710,64,3,59,1,50,224);
INSERT INTO `stats` VALUES (309,'POL','2009-07-06 20:00:00',103,1,223599,266907,163,123,118118,15,690,64,0,59,1,49,236);
INSERT INTO `stats` VALUES (310,'POL','2009-07-07 20:00:00',100,0,230937,259585,186,107,109699,15,690,64,2,59,1,48,243);
INSERT INTO `stats` VALUES (311,'POL','2009-07-08 20:00:00',101,2,248118,242240,195,163,26038,14,1020,62,1,59,1,44,242);
INSERT INTO `stats` VALUES (312,'POL','2009-07-09 20:00:00',101,0,243548,246806,172,52,26303,14,923,62,1,59,1,45,249);
INSERT INTO `stats` VALUES (313,'POL','2009-07-10 20:00:00',102,0,242154,248129,154,64,20348,14,1021,62,3,59,1,50,251);
INSERT INTO `stats` VALUES (314,'POL','2009-07-11 20:00:00',99,1,241380,248909,107,26,20402,14,2556,62,5,59,1,39,249);
INSERT INTO `stats` VALUES (315,'POL','2009-07-12 20:00:00',97,0,237033,253351,154,32,24834,14,709,62,2,58,1,39,242);
INSERT INTO `stats` VALUES (316,'POL','2009-07-13 20:00:00',100,4,233048,253265,114,137,24948,13,770,63,3,59,1,53,251);
INSERT INTO `stats` VALUES (317,'POL','2009-07-14 20:00:00',104,5,238782,246872,146,117,28776,12,690,63,1,58,1,55,250);
INSERT INTO `stats` VALUES (318,'POL','2009-07-15 20:00:00',107,4,250687,234978,108,123,28603,12,920,63,1,59,1,50,261);
INSERT INTO `stats` VALUES (319,'POL','2009-07-16 20:00:00',104,0,238425,247251,135,167,29420,11,832,63,2,59,1,52,267);
INSERT INTO `stats` VALUES (320,'POL','2009-07-17 20:00:00',110,5,242555,243132,113,161,31656,11,1821,63,0,59,1,53,268);
INSERT INTO `stats` VALUES (321,'POL','2009-07-18 20:00:00',110,0,236334,249364,102,28,31914,11,1050,63,0,59,1,42,263);
INSERT INTO `stats` VALUES (322,'POL','2009-07-19 20:00:00',110,2,232141,253770,138,17,36320,11,720,63,3,59,1,43,230);
INSERT INTO `stats` VALUES (323,'POL','2009-07-20 20:00:00',113,3,232037,254504,126,96,33747,11,850,63,0,58,0,50,217);
INSERT INTO `stats` VALUES (324,'POL','2009-07-21 20:00:00',112,1,231882,254747,98,57,33965,12,960,63,0,58,0,44,218);
INSERT INTO `stats` VALUES (325,'POL','2009-07-22 20:00:00',111,2,238557,255320,98,34,36748,12,1071,62,2,58,0,47,203);
INSERT INTO `stats` VALUES (326,'POL','2009-07-23 20:00:00',113,3,239645,254400,99,286,36495,12,600,62,1,58,0,48,198);
INSERT INTO `stats` VALUES (327,'POL','2009-07-24 20:00:00',117,9,239361,254692,68,51,36787,12,1350,62,5,58,0,49,190);
INSERT INTO `stats` VALUES (328,'POL','2009-07-25 20:00:00',116,1,239852,258930,122,74,41229,12,780,62,1,58,0,47,189);
INSERT INTO `stats` VALUES (329,'POL','2009-07-26 20:00:00',116,3,239922,258587,128,92,40436,12,710,62,2,58,0,47,187);
INSERT INTO `stats` VALUES (330,'POL','2009-07-27 20:00:00',115,3,240561,257954,94,109,40203,12,541,62,6,58,0,48,194);
INSERT INTO `stats` VALUES (331,'POL','2009-07-28 20:00:00',119,3,241517,257004,87,58,39375,12,370,62,0,58,0,47,196);
INSERT INTO `stats` VALUES (332,'POL','2009-07-29 20:00:00',117,0,241874,257008,79,70,39659,12,500,62,2,58,0,37,208);
INSERT INTO `stats` VALUES (333,'POL','2009-07-30 20:00:00',116,3,242632,256256,106,80,39329,12,680,62,2,58,0,37,214);
INSERT INTO `stats` VALUES (334,'POL','2009-07-31 20:00:00',116,0,274894,224000,81,73,39388,13,950,62,0,58,0,40,260);
INSERT INTO `stats` VALUES (335,'POL','2009-08-01 20:00:00',114,0,240959,256186,76,26,39104,13,451,62,2,58,0,33,245);
INSERT INTO `stats` VALUES (336,'POL','2009-08-02 20:00:00',111,1,238616,259105,125,17,43143,13,170,62,3,58,0,37,247);
INSERT INTO `stats` VALUES (337,'POL','2009-08-03 20:00:00',102,1,239744,257953,75,27,41991,13,180,62,10,58,0,37,245);
INSERT INTO `stats` VALUES (338,'POL','2009-08-04 20:00:00',105,2,237204,257447,75,21,41435,14,360,62,0,58,0,40,239);
INSERT INTO `stats` VALUES (339,'POL','2009-08-05 20:00:00',103,2,238382,256265,81,67,40895,14,440,62,3,58,0,32,524);
INSERT INTO `stats` VALUES (340,'POL','2009-08-06 20:00:00',101,1,240311,258199,109,39,41347,13,1011,61,6,58,0,38,608);
INSERT INTO `stats` VALUES (341,'POL','2009-08-07 20:00:00',97,0,238937,259419,85,38,42147,13,1701,61,4,58,0,37,656);
INSERT INTO `stats` VALUES (342,'POL','2009-08-08 20:00:00',97,1,253478,248384,133,55,41450,13,380,61,1,58,0,43,715);
INSERT INTO `stats` VALUES (343,'POL','2009-08-09 20:00:00',96,2,253310,248418,125,26,40642,13,710,60,3,58,0,43,712);
INSERT INTO `stats` VALUES (344,'POL','2009-08-10 20:00:00',97,1,249241,246824,127,40,41110,13,490,60,0,57,0,41,730);
INSERT INTO `stats` VALUES (345,'POL','2009-08-11 20:00:00',99,2,234171,265865,208,88,42587,13,870,61,0,57,0,39,736);
INSERT INTO `stats` VALUES (346,'POL','2009-08-12 20:00:00',100,1,226281,274058,200,83,48580,13,315,62,1,57,0,41,739);
INSERT INTO `stats` VALUES (347,'POL','2009-08-13 20:00:00',97,0,224781,275559,185,42,49285,13,720,62,5,57,0,32,732);
INSERT INTO `stats` VALUES (348,'POL','2009-08-14 20:00:00',97,2,222640,277700,203,47,48888,13,880,64,2,57,0,41,727);
INSERT INTO `stats` VALUES (349,'POL','2009-08-15 20:00:00',98,1,224040,276300,172,35,49823,13,820,65,0,57,0,35,718);
INSERT INTO `stats` VALUES (350,'POL','2009-08-16 20:00:00',96,0,209455,280085,167,15,51580,13,610,65,2,57,0,44,729);
INSERT INTO `stats` VALUES (351,'POL','2009-08-17 20:00:00',96,0,211438,278102,164,25,50697,13,360,65,0,57,0,38,741);
INSERT INTO `stats` VALUES (352,'POL','2009-08-18 20:00:00',96,0,212313,277227,167,106,50752,13,400,65,2,57,0,40,748);
INSERT INTO `stats` VALUES (353,'POL','2009-08-19 20:00:00',96,0,211000,278540,161,132,51615,13,1170,65,1,57,0,39,797);
INSERT INTO `stats` VALUES (354,'POL','2009-08-20 20:00:00',95,0,210650,278863,200,44,52338,13,930,65,1,59,0,33,793);
INSERT INTO `stats` VALUES (355,'POL','2009-08-21 20:00:00',93,0,208627,280850,191,137,55899,14,3750,65,2,60,0,32,828);
INSERT INTO `stats` VALUES (356,'POL','2009-08-22 20:00:00',93,1,209070,280397,207,39,56037,14,390,65,1,63,0,41,828);
INSERT INTO `stats` VALUES (357,'POL','2009-08-23 20:00:00',92,0,208661,280795,161,28,56335,14,610,65,1,63,0,39,836);
INSERT INTO `stats` VALUES (358,'POL','2009-08-24 20:00:00',90,2,208672,280785,170,94,56395,14,700,66,4,63,0,37,853);
INSERT INTO `stats` VALUES (359,'POL','2009-08-25 20:00:00',87,0,210343,274710,164,133,57397,13,450,65,1,63,0,37,908);
INSERT INTO `stats` VALUES (360,'POL','2009-08-26 20:00:00',85,0,209150,274171,284,47,57708,14,180,63,0,56,0,29,922);
INSERT INTO `stats` VALUES (361,'POL','2009-08-27 20:00:00',84,1,208665,274548,161,81,57785,14,350,63,0,56,0,34,938);
INSERT INTO `stats` VALUES (362,'POL','2009-08-28 20:00:00',83,0,207991,275142,174,49,58120,14,400,63,1,57,0,32,942);
INSERT INTO `stats` VALUES (363,'POL','2009-08-29 20:00:00',83,0,207030,275419,183,43,58273,14,650,60,1,58,0,31,950);
INSERT INTO `stats` VALUES (364,'POL','2009-08-30 20:00:00',83,0,205919,274092,192,25,58714,14,400,58,0,58,0,33,956);
INSERT INTO `stats` VALUES (365,'POL','2009-08-31 20:00:00',83,1,203983,276028,259,141,58561,14,651,51,2,58,0,36,926);
INSERT INTO `stats` VALUES (366,'POL','2009-09-01 20:00:00',88,7,200508,279136,251,202,61630,15,560,49,1,58,0,44,1036);
INSERT INTO `stats` VALUES (367,'POL','2009-09-02 20:00:00',91,2,201169,278639,229,157,57266,15,651,47,0,58,0,40,1118);
INSERT INTO `stats` VALUES (368,'POL','2009-09-03 20:00:00',91,2,200605,279017,233,142,57644,14,540,47,2,59,0,39,1125);
INSERT INTO `stats` VALUES (369,'POL','2009-09-04 20:00:00',90,1,200842,278783,235,126,63388,14,792,46,1,58,0,37,1118);
INSERT INTO `stats` VALUES (370,'POL','2009-09-05 20:00:00',91,1,200863,278732,214,99,64037,14,753,46,0,58,0,43,1121);
INSERT INTO `stats` VALUES (371,'POL','2009-09-06 20:00:00',93,1,199330,280256,223,41,64908,14,600,48,0,58,0,40,1122);
INSERT INTO `stats` VALUES (372,'POL','2009-09-07 20:00:00',91,1,199452,280134,212,257,64218,14,610,48,1,58,0,43,1124);
INSERT INTO `stats` VALUES (373,'POL','2009-09-08 20:00:00',91,0,198751,280613,236,183,64881,13,452,48,0,58,0,38,1135);
INSERT INTO `stats` VALUES (374,'POL','2009-09-09 20:00:00',89,0,196598,282325,239,165,66813,13,1600,49,0,58,0,34,1149);
INSERT INTO `stats` VALUES (375,'POL','2009-09-10 20:00:00',90,1,195564,283359,282,126,68135,12,880,49,1,59,0,34,1143);
INSERT INTO `stats` VALUES (376,'POL','2009-09-11 20:00:00',84,1,195531,283392,256,122,69226,13,850,50,6,57,0,39,1168);
INSERT INTO `stats` VALUES (377,'POL','2009-09-12 20:00:00',84,0,195382,284023,243,52,69857,12,590,50,0,57,0,33,1180);
INSERT INTO `stats` VALUES (378,'POL','2009-09-13 20:00:00',84,0,195070,284388,226,53,70222,12,472,50,0,57,0,35,1183);
INSERT INTO `stats` VALUES (379,'POL','2009-09-14 20:00:00',85,1,194846,284601,212,64,70435,13,452,50,1,57,0,41,1189);
INSERT INTO `stats` VALUES (380,'POL','2009-09-15 20:00:00',84,0,194863,284576,243,134,70066,13,821,50,1,57,0,33,1206);
INSERT INTO `stats` VALUES (381,'POL','2009-09-16 20:00:00',85,2,189272,290120,226,159,70576,12,560,50,1,57,0,34,1214);
INSERT INTO `stats` VALUES (382,'POL','2009-09-17 20:00:00',85,2,188354,291038,215,92,66495,8,1050,50,2,57,0,34,1229);
INSERT INTO `stats` VALUES (383,'POL','2009-09-18 20:00:00',85,0,184544,290848,223,65,67417,9,970,49,1,57,0,36,1227);
INSERT INTO `stats` VALUES (384,'POL','2009-09-19 20:00:00',84,2,-151120,301596,251,92,78690,9,1360,49,2,57,0,47,1230);
INSERT INTO `stats` VALUES (385,'POL','2009-09-20 20:00:00',85,2,-151255,301731,198,50,78225,9,33,47,1,57,0,45,1219);
INSERT INTO `stats` VALUES (386,'POL','2009-09-21 20:00:00',83,2,193123,330345,206,76,106409,7,230,47,4,57,0,38,1220);
INSERT INTO `stats` VALUES (387,'POL','2009-09-22 20:00:00',81,0,175544,333421,242,77,106330,8,33,46,0,58,0,39,1236);
INSERT INTO `stats` VALUES (388,'POL','2009-09-23 20:00:00',80,0,175696,333297,215,210,105706,8,461,46,1,58,0,37,1249);
INSERT INTO `stats` VALUES (389,'POL','2009-09-24 20:00:00',80,1,172768,336965,231,232,111336,8,203,42,3,57,0,37,1248);
INSERT INTO `stats` VALUES (390,'POL','2009-09-25 20:00:00',81,1,175267,334486,218,89,106251,8,240,43,0,58,0,38,1239);
INSERT INTO `stats` VALUES (391,'POL','2009-09-26 20:00:00',80,0,174908,334845,195,35,106110,8,123,43,1,58,0,32,1236);
INSERT INTO `stats` VALUES (392,'POL','2009-09-27 20:00:00',78,0,174852,334901,191,24,106166,8,261,43,2,58,0,35,1236);
INSERT INTO `stats` VALUES (393,'POL','2009-09-28 20:00:00',79,1,182814,327196,191,91,107547,8,270,40,2,58,0,37,1285);
INSERT INTO `stats` VALUES (394,'POL','2009-09-29 20:00:00',79,1,183293,326511,210,66,107875,8,301,39,1,58,1,33,1276);
INSERT INTO `stats` VALUES (395,'POL','2009-09-30 20:00:00',77,0,181820,327984,198,73,107947,8,310,39,2,58,1,29,1278);
INSERT INTO `stats` VALUES (396,'POL','2009-10-01 20:00:00',74,0,181185,328010,203,62,107973,8,351,38,2,58,1,30,1268);
INSERT INTO `stats` VALUES (397,'POL','2009-10-02 20:00:00',77,2,182756,326693,203,62,110625,8,260,36,1,58,1,37,1248);
INSERT INTO `stats` VALUES (398,'POL','2009-10-03 20:00:00',78,0,172841,326466,188,46,111898,8,380,36,0,58,1,36,1250);
INSERT INTO `stats` VALUES (399,'POL','2009-10-04 20:00:00',79,1,173254,326103,176,29,111535,8,1,36,1,58,1,40,1244);
INSERT INTO `stats` VALUES (400,'POL','2009-10-05 20:00:00',76,0,170798,328375,185,115,113807,8,270,36,1,58,1,35,1244);
INSERT INTO `stats` VALUES (401,'POL','2009-10-06 20:00:00',76,0,172993,326338,191,120,112300,8,281,36,4,58,1,37,1247);
INSERT INTO `stats` VALUES (402,'POL','2009-10-07 20:00:00',76,0,168166,331165,194,59,111846,8,260,36,0,58,1,40,1234);
INSERT INTO `stats` VALUES (403,'POL','2009-10-08 20:00:00',74,0,177403,332893,190,68,112037,8,271,34,1,58,1,35,1238);
INSERT INTO `stats` VALUES (404,'POL','2009-10-09 20:00:00',73,0,177896,332430,191,39,107089,8,300,34,0,58,1,37,1240);
INSERT INTO `stats` VALUES (405,'POL','2009-10-10 20:00:00',73,0,178540,331783,183,33,106442,8,200,34,0,58,1,33,1242);
INSERT INTO `stats` VALUES (406,'POL','2009-10-11 20:00:00',73,0,178520,331803,174,21,106462,8,160,34,0,58,1,29,1256);
INSERT INTO `stats` VALUES (407,'POL','2009-10-12 20:00:00',70,0,178592,331731,170,36,106410,8,110,34,3,58,1,33,1263);
INSERT INTO `stats` VALUES (408,'POL','2009-10-13 20:00:00',70,0,179359,330697,189,31,106191,8,368,34,0,58,1,33,1264);
INSERT INTO `stats` VALUES (409,'POL','2009-10-14 20:00:00',71,1,180829,327904,183,27,106707,8,921,32,0,58,1,33,1271);
INSERT INTO `stats` VALUES (410,'POL','2009-10-15 20:00:00',72,1,180751,327883,173,67,106686,8,483,32,0,58,1,33,1273);
INSERT INTO `stats` VALUES (411,'POL','2009-10-16 20:00:00',72,1,180306,328328,166,45,107994,8,1800,32,1,58,1,35,1273);
INSERT INTO `stats` VALUES (412,'POL','2009-10-17 20:00:00',74,1,182172,327764,167,51,108230,7,560,32,1,58,1,37,1272);
INSERT INTO `stats` VALUES (413,'POL','2009-10-18 20:00:00',76,2,169680,340256,161,29,107767,7,84,32,0,58,1,43,1255);
INSERT INTO `stats` VALUES (414,'POL','2009-10-19 20:00:00',74,0,172268,337273,167,75,107361,7,81,32,2,58,1,39,1250);
INSERT INTO `stats` VALUES (415,'POL','2009-10-20 20:00:00',74,0,173636,335905,168,58,107097,7,301,32,0,58,1,32,1256);
INSERT INTO `stats` VALUES (416,'POL','2009-10-21 20:00:00',74,1,172707,335624,163,142,106816,7,450,32,0,58,1,33,1258);
INSERT INTO `stats` VALUES (417,'POL','2009-10-22 20:00:00',74,0,172644,335687,163,48,106879,7,272,32,1,58,1,34,1260);
INSERT INTO `stats` VALUES (418,'POL','2009-10-23 20:00:00',75,1,173748,334583,154,24,106679,7,180,32,0,58,1,32,1264);
INSERT INTO `stats` VALUES (419,'POL','2009-10-24 20:00:00',74,0,167577,341762,156,3,113858,7,170,32,1,58,1,32,1281);
INSERT INTO `stats` VALUES (420,'POL','2009-10-25 20:00:00',76,3,167378,341622,162,79,113718,7,210,32,0,58,3,34,1283);
INSERT INTO `stats` VALUES (421,'POL','2009-10-26 20:00:00',80,5,167664,341336,178,42,113632,8,320,32,3,58,3,38,1290);
INSERT INTO `stats` VALUES (422,'POL','2009-10-27 20:00:00',80,1,168312,340571,183,74,113312,8,221,32,0,58,3,32,1289);
INSERT INTO `stats` VALUES (423,'POL','2009-10-28 20:00:00',84,4,167559,340744,168,142,113585,8,171,32,0,58,3,37,1291);
INSERT INTO `stats` VALUES (424,'POL','2009-10-29 20:00:00',85,2,171914,336385,160,153,109326,8,120,32,1,58,3,35,1297);
INSERT INTO `stats` VALUES (425,'POL','2009-10-30 20:00:00',85,0,173829,335501,175,101,104346,8,180,32,1,58,3,35,1303);
INSERT INTO `stats` VALUES (426,'POL','2009-10-31 20:00:00',83,1,172009,335170,172,41,104465,8,130,33,2,58,3,39,1303);
INSERT INTO `stats` VALUES (427,'POL','2009-11-01 20:00:00',84,1,171832,335347,167,23,104342,8,140,33,0,58,3,37,1304);
INSERT INTO `stats` VALUES (428,'POL','2009-11-02 20:00:00',84,1,172100,335079,175,52,104174,8,110,33,2,58,3,38,1309);
INSERT INTO `stats` VALUES (429,'POL','2009-11-03 20:00:00',85,1,173021,333712,189,69,104322,8,302,34,2,58,3,30,1311);
INSERT INTO `stats` VALUES (430,'POL','2009-11-04 20:00:00',81,0,172286,334447,173,53,105057,8,210,35,4,57,3,35,1308);
INSERT INTO `stats` VALUES (431,'POL','2009-11-05 20:00:00',80,2,170875,335858,193,90,104808,8,142,34,3,57,3,33,1304);
INSERT INTO `stats` VALUES (432,'POL','2009-11-06 20:00:00',80,1,170332,335769,207,117,104395,8,130,33,0,57,3,34,1312);
INSERT INTO `stats` VALUES (433,'POL','2009-11-07 20:00:00',76,1,167997,337094,174,40,105720,8,190,30,4,57,1,34,1314);
INSERT INTO `stats` VALUES (434,'POL','2009-11-08 20:00:00',73,0,167891,337173,165,12,105799,8,135,29,1,57,1,31,1309);
INSERT INTO `stats` VALUES (435,'POL','2009-11-09 20:00:00',75,3,185520,337044,174,80,105670,8,76,29,0,57,1,31,1310);
INSERT INTO `stats` VALUES (436,'POL','2009-11-10 20:00:00',79,4,185821,337411,192,53,105928,9,130,30,1,57,1,34,1311);
INSERT INTO `stats` VALUES (437,'POL','2009-11-11 20:00:00',82,2,187043,336797,183,31,105824,9,170,30,0,57,1,38,1319);
INSERT INTO `stats` VALUES (438,'POL','2009-11-12 20:00:00',82,0,187238,336602,189,36,105378,9,176,30,0,57,1,29,1322);
INSERT INTO `stats` VALUES (439,'POL','2009-11-13 20:00:00',82,1,187246,336011,207,40,101421,10,276,30,1,57,1,33,1333);
INSERT INTO `stats` VALUES (440,'POL','2009-11-14 20:00:00',84,2,187732,335525,167,10,101378,10,4,30,0,57,1,34,1338);
INSERT INTO `stats` VALUES (441,'POL','2009-11-15 20:00:00',84,1,186715,336542,178,45,101410,10,54,30,1,57,1,38,1338);
INSERT INTO `stats` VALUES (442,'POL','2009-11-16 20:00:00',85,2,187189,336068,196,111,101585,10,231,30,1,57,1,35,1358);
INSERT INTO `stats` VALUES (443,'POL','2009-11-17 20:00:00',84,0,188011,335246,199,56,101995,10,220,30,1,57,1,33,1358);
INSERT INTO `stats` VALUES (444,'POL','2009-11-18 20:00:00',83,0,184215,339042,185,34,102669,10,145,30,1,57,1,29,1333);
INSERT INTO `stats` VALUES (445,'POL','2009-11-19 20:00:00',80,0,183952,339305,175,37,102051,10,68,30,3,57,1,29,1333);
INSERT INTO `stats` VALUES (446,'POL','2009-11-20 20:00:00',76,0,185552,337705,173,96,101527,10,164,30,4,57,1,26,1338);
INSERT INTO `stats` VALUES (447,'POL','2009-11-21 20:00:00',74,0,187984,335273,173,67,101562,10,158,29,2,57,1,27,1336);
INSERT INTO `stats` VALUES (448,'POL','2009-11-22 20:00:00',74,0,186695,336562,168,81,101661,10,126,29,0,57,1,26,1336);
INSERT INTO `stats` VALUES (449,'POL','2009-11-23 20:00:00',75,1,186503,336754,168,22,101786,10,142,29,0,57,1,26,1350);
INSERT INTO `stats` VALUES (450,'POL','2009-11-24 20:00:00',77,2,182284,336771,169,77,101736,10,65,29,1,57,1,31,1346);
INSERT INTO `stats` VALUES (451,'POL','2009-11-25 20:00:00',72,1,181827,334393,249,41,102671,10,337,28,4,60,1,30,1331);
INSERT INTO `stats` VALUES (452,'POL','2009-11-26 20:00:00',71,1,184161,334855,202,34,103133,10,146,29,3,61,1,26,1335);
INSERT INTO `stats` VALUES (453,'POL','2009-11-27 20:00:00',72,1,184098,334918,189,18,103196,10,81,29,0,61,1,30,1339);
INSERT INTO `stats` VALUES (454,'POL','2009-11-28 20:00:00',72,0,180715,336956,198,15,103597,10,241,28,0,61,1,33,1324);
INSERT INTO `stats` VALUES (455,'POL','2009-11-29 20:00:00',75,3,180378,337293,192,16,103934,10,240,28,0,61,1,31,1326);
INSERT INTO `stats` VALUES (456,'POL','2009-11-30 20:00:00',75,0,180091,337580,200,57,104231,10,82,28,0,61,1,31,1331);
INSERT INTO `stats` VALUES (457,'POL','2009-12-01 20:00:00',74,1,181011,336631,214,68,99304,11,77,28,0,62,1,29,1344);
INSERT INTO `stats` VALUES (458,'POL','2009-12-02 20:00:00',73,1,192000,325642,225,78,102460,11,149,28,2,63,2,30,1332);
INSERT INTO `stats` VALUES (459,'POL','2009-12-03 20:00:00',71,2,188337,329304,220,51,105643,11,2530,28,1,63,2,37,1336);
INSERT INTO `stats` VALUES (460,'POL','2009-12-04 20:00:00',70,1,191387,326255,199,89,103526,11,180,28,3,64,1,32,1323);
INSERT INTO `stats` VALUES (461,'POL','2009-12-05 20:00:00',70,2,190069,327123,185,47,104394,11,80,28,2,64,1,29,1319);
INSERT INTO `stats` VALUES (462,'POL','2009-12-06 20:00:00',71,3,190199,326993,232,35,104976,11,83,28,1,65,1,31,1347);
INSERT INTO `stats` VALUES (463,'POL','2009-12-07 20:00:00',70,0,190653,326539,258,71,104422,11,172,27,1,69,1,30,1346);
INSERT INTO `stats` VALUES (464,'POL','2009-12-08 20:00:00',71,1,193273,323919,181,55,104772,11,190,28,0,69,1,29,1347);
INSERT INTO `stats` VALUES (465,'POL','2009-12-09 20:00:00',70,1,192957,324235,177,43,105143,11,250,28,2,69,1,33,1346);
INSERT INTO `stats` VALUES (466,'POL','2009-12-10 20:00:00',70,0,194175,323017,181,83,105277,11,161,28,0,69,1,31,1346);
INSERT INTO `stats` VALUES (467,'POL','2009-12-11 20:00:00',71,2,194582,322610,210,43,105870,11,97,29,1,71,1,31,1346);
INSERT INTO `stats` VALUES (468,'POL','2009-12-12 20:00:00',72,1,196535,321890,183,24,105897,11,110,28,2,71,1,33,1352);
INSERT INTO `stats` VALUES (469,'POL','2009-12-13 20:00:00',70,0,196673,321752,171,12,105759,11,0,28,2,71,1,38,1198);
INSERT INTO `stats` VALUES (470,'POL','2009-12-14 20:00:00',69,0,196342,322083,201,9,105940,11,145,27,1,72,1,30,1204);
INSERT INTO `stats` VALUES (471,'POL','2009-12-15 20:00:00',72,4,196866,321559,176,9,101338,11,75,26,1,72,1,36,1236);
INSERT INTO `stats` VALUES (472,'POL','2009-12-16 20:00:00',72,1,195881,322544,174,20,101596,11,90,26,1,72,1,30,1259);
INSERT INTO `stats` VALUES (473,'POL','2009-12-17 20:00:00',72,1,196982,321443,175,20,101417,11,22,26,1,72,1,30,1260);
INSERT INTO `stats` VALUES (474,'POL','2009-12-18 20:00:00',73,1,196718,321707,166,6,101716,11,0,26,1,72,1,17,1238);
INSERT INTO `stats` VALUES (475,'POL','2009-12-19 20:00:00',75,2,196343,324581,155,24,104190,11,51,26,1,58,0,25,1238);
INSERT INTO `stats` VALUES (476,'POL','2009-12-20 20:00:00',76,1,198202,323742,168,28,105195,11,60,26,1,58,0,23,1239);
INSERT INTO `stats` VALUES (477,'POL','2009-12-21 20:00:00',74,1,198166,324908,155,21,106361,11,31,26,3,58,0,28,1235);
INSERT INTO `stats` VALUES (478,'POL','2009-12-22 20:00:00',73,0,197861,326293,165,12,107246,11,120,26,1,58,0,26,1237);
INSERT INTO `stats` VALUES (479,'POL','2009-12-23 20:00:00',75,3,198511,326953,161,10,108408,12,3,26,1,58,0,31,1234);
INSERT INTO `stats` VALUES (480,'POL','2009-12-24 20:00:00',75,0,198413,328251,159,13,109706,12,260,26,0,58,0,25,1253);
INSERT INTO `stats` VALUES (481,'POL','2009-12-25 20:00:00',72,0,197739,329645,156,14,110760,12,11,26,3,58,0,21,1241);
INSERT INTO `stats` VALUES (482,'POL','2009-12-26 20:00:00',72,1,197319,331015,74,4,112130,12,0,26,2,58,0,32,1238);
INSERT INTO `stats` VALUES (483,'POL','2009-12-27 20:00:00',72,0,197222,332062,150,11,113177,12,11,26,0,58,0,24,1235);
INSERT INTO `stats` VALUES (484,'POL','2009-12-28 20:00:00',70,0,196722,333122,157,42,114272,12,5,26,1,58,0,28,1245);
INSERT INTO `stats` VALUES (485,'POL','2009-12-29 20:00:00',68,0,196097,334817,156,33,115967,12,2,26,2,58,0,24,1245);
INSERT INTO `stats` VALUES (486,'POL','2009-12-30 20:00:00',69,1,199522,332463,163,14,111141,12,16,26,1,58,0,27,1197);
INSERT INTO `stats` VALUES (487,'POL','2009-12-31 20:00:00',68,0,199484,333641,159,9,112354,12,62,26,1,58,0,26,1204);
INSERT INTO `stats` VALUES (488,'POL','2010-01-01 20:00:00',67,1,199119,334796,158,8,113619,12,15,26,2,58,0,23,1187);
INSERT INTO `stats` VALUES (489,'POL','2010-01-02 20:00:00',69,4,198905,336150,160,24,114773,12,109,25,2,58,0,30,1189);
INSERT INTO `stats` VALUES (490,'POL','2010-01-03 20:00:00',70,1,198877,337318,156,11,115941,12,12,25,0,58,0,26,1187);
INSERT INTO `stats` VALUES (491,'POL','2010-01-04 20:00:00',71,1,200670,336665,157,22,117052,12,60,25,0,58,0,27,1193);
INSERT INTO `stats` VALUES (492,'POL','2010-01-05 20:00:00',69,0,200264,338101,156,32,118498,12,35,25,2,58,0,26,1184);
INSERT INTO `stats` VALUES (493,'POL','2010-01-06 20:00:00',69,1,198262,340993,150,12,121390,12,2,25,1,58,0,23,1180);
INSERT INTO `stats` VALUES (494,'POL','2010-01-07 20:00:00',69,0,199532,340973,161,54,116734,12,62,25,0,58,0,26,1179);
INSERT INTO `stats` VALUES (495,'POL','2010-01-08 20:00:00',66,0,199980,341767,160,33,117239,12,35,25,1,58,0,23,1178);
INSERT INTO `stats` VALUES (496,'POL','2010-01-09 20:00:00',65,0,197808,345089,164,34,118526,12,258,26,1,58,0,32,1179);
INSERT INTO `stats` VALUES (497,'POL','2010-01-10 20:00:00',70,0,205117,346465,156,68,119937,12,13,26,0,58,0,36,1087);
INSERT INTO `stats` VALUES (498,'POL','2010-01-11 20:00:00',69,0,197013,353579,163,22,121796,12,61,26,1,58,0,30,1093);
INSERT INTO `stats` VALUES (499,'POL','2010-01-12 20:00:00',64,1,188392,353995,162,28,124396,10,12,26,2,58,0,28,1096);
INSERT INTO `stats` VALUES (500,'POL','2010-01-13 20:00:00',61,0,187257,355442,159,34,125843,10,300,26,1,59,0,26,1097);
INSERT INTO `stats` VALUES (501,'POL','2010-01-14 20:00:00',64,2,187322,356622,150,65,127023,10,130,26,0,59,0,27,1142);
INSERT INTO `stats` VALUES (502,'POL','2010-01-15 20:00:00',68,1,187816,360410,156,40,131735,10,6,26,0,54,0,30,1148);
INSERT INTO `stats` VALUES (503,'POL','2010-01-16 20:00:00',70,1,192927,361373,154,40,132798,10,2,26,1,54,0,27,1161);
INSERT INTO `stats` VALUES (504,'POL','2010-01-17 20:00:00',71,1,192956,362454,156,40,133779,10,24,26,0,54,0,33,1166);
INSERT INTO `stats` VALUES (505,'POL','2010-01-18 20:00:00',73,3,193060,363631,159,18,134956,10,130,26,1,54,0,33,1274);
INSERT INTO `stats` VALUES (506,'POL','2010-01-19 20:00:00',74,1,194272,363539,164,39,136208,10,40,26,0,54,0,25,1285);
INSERT INTO `stats` VALUES (507,'POL','2010-01-20 20:00:00',73,0,193426,365785,177,19,138624,11,825,27,1,54,0,30,1288);
INSERT INTO `stats` VALUES (508,'POL','2010-01-21 20:00:00',74,1,193658,366833,173,45,139772,11,86,27,0,54,0,27,1284);
INSERT INTO `stats` VALUES (509,'POL','2010-01-22 20:00:00',73,0,195696,366075,177,42,136654,11,679,26,2,54,0,29,1290);
INSERT INTO `stats` VALUES (510,'POL','2010-01-23 20:00:00',75,2,195702,367419,167,26,137998,11,161,26,1,54,0,37,1293);
INSERT INTO `stats` VALUES (511,'POL','2010-01-24 20:00:00',74,0,196117,368464,166,17,139243,11,63,26,1,54,0,33,1287);
INSERT INTO `stats` VALUES (512,'POL','2010-01-25 20:00:00',74,1,197340,368541,184,34,140955,12,93,28,2,55,0,32,1283);
INSERT INTO `stats` VALUES (513,'POL','2010-01-26 20:00:00',73,0,199282,368019,84,12,142097,12,43,26,1,55,0,30,1301);
INSERT INTO `stats` VALUES (514,'POL','2010-01-27 20:00:00',74,1,184639,384032,175,13,143302,12,12,25,4,55,0,29,1294);
INSERT INTO `stats` VALUES (515,'POL','2010-01-28 20:00:00',72,1,184818,385273,166,23,134343,12,44,25,3,55,0,32,1279);
INSERT INTO `stats` VALUES (516,'POL','2010-01-29 20:00:00',72,0,186113,385298,169,4,135712,12,34,25,0,55,0,27,1279);
INSERT INTO `stats` VALUES (517,'POL','2010-01-30 20:00:00',71,0,186164,386667,163,11,137081,11,150,24,1,55,0,30,1276);
INSERT INTO `stats` VALUES (518,'POL','2010-01-31 20:00:00',71,0,187640,386551,165,28,138308,11,15,24,0,55,0,29,1269);
INSERT INTO `stats` VALUES (519,'POL','2010-02-01 20:00:00',69,1,187827,386612,168,16,138012,11,1,20,2,55,0,25,1249);
INSERT INTO `stats` VALUES (520,'POL','2010-02-02 20:00:00',67,0,184786,388233,160,28,139633,11,32,20,2,55,0,23,1248);
INSERT INTO `stats` VALUES (521,'POL','2010-02-03 20:00:00',66,0,167924,406387,161,29,140919,11,2,20,0,55,0,28,1243);
INSERT INTO `stats` VALUES (522,'POL','2010-02-04 20:00:00',68,3,168028,407666,183,21,142198,11,26,20,3,56,0,30,1249);
INSERT INTO `stats` VALUES (523,'POL','2010-02-05 20:00:00',68,0,166071,408861,175,15,142379,11,1122,20,2,56,0,28,1249);
INSERT INTO `stats` VALUES (524,'POL','2010-02-06 20:00:00',70,2,166209,410288,162,12,143816,11,138,20,1,56,0,33,1251);
INSERT INTO `stats` VALUES (525,'POL','2010-02-07 20:00:00',67,0,166090,411767,154,17,144995,11,1,20,2,56,0,34,1256);
INSERT INTO `stats` VALUES (526,'POL','2010-02-08 20:00:00',66,0,161466,417611,151,20,150839,10,40,20,1,56,0,27,1262);
INSERT INTO `stats` VALUES (527,'POL','2010-02-09 20:00:00',67,2,161568,418729,153,36,151957,11,1,20,1,56,0,31,1273);
INSERT INTO `stats` VALUES (528,'POL','2010-02-10 20:00:00',67,0,162372,419315,153,7,152986,11,62,20,0,56,0,29,1262);
INSERT INTO `stats` VALUES (529,'POL','2010-02-11 20:00:00',68,1,161004,421653,156,14,155659,11,3,20,2,56,0,26,1244);
INSERT INTO `stats` VALUES (530,'POL','2010-02-12 20:00:00',68,0,162467,421400,152,27,156750,11,17,20,0,56,0,27,1264);
INSERT INTO `stats` VALUES (531,'POL','2010-02-13 20:00:00',68,0,162366,422711,151,8,158061,11,32,20,0,56,0,27,1260);
INSERT INTO `stats` VALUES (532,'POL','2010-02-14 20:00:00',68,1,162637,423650,154,4,159200,11,52,20,1,56,0,24,1263);
INSERT INTO `stats` VALUES (533,'POL','2010-02-15 20:00:00',71,4,164989,421024,168,41,156574,12,1370,19,1,57,0,34,1262);
INSERT INTO `stats` VALUES (534,'POL','2010-02-16 20:00:00',71,1,165497,421464,171,17,151718,12,151,20,1,57,0,30,1289);
INSERT INTO `stats` VALUES (535,'POL','2010-02-17 20:00:00',69,0,165144,422394,152,33,153078,11,210,19,1,57,0,23,1286);
INSERT INTO `stats` VALUES (536,'POL','2010-02-18 20:00:00',70,0,164743,423955,144,46,154639,11,272,19,0,57,0,29,1303);
INSERT INTO `stats` VALUES (537,'POL','2010-02-19 20:00:00',66,1,163943,425843,149,14,156327,10,170,19,4,57,0,29,1312);
INSERT INTO `stats` VALUES (538,'POL','2010-02-20 20:00:00',69,2,161710,427303,150,9,157761,10,130,18,3,57,0,35,1323);
INSERT INTO `stats` VALUES (539,'POL','2010-02-21 20:00:00',70,1,156859,433134,141,7,159101,10,140,18,0,57,0,29,1336);
INSERT INTO `stats` VALUES (540,'POL','2010-02-22 20:00:00',73,3,157043,434366,141,29,160433,10,150,18,0,57,0,29,1341);
INSERT INTO `stats` VALUES (541,'POL','2010-02-23 20:00:00',75,2,160622,434049,152,29,161971,11,260,18,1,57,0,33,1361);
INSERT INTO `stats` VALUES (542,'POL','2010-02-24 20:00:00',77,3,157715,438226,145,40,165748,10,131,18,2,57,0,37,1346);
INSERT INTO `stats` VALUES (543,'POL','2010-02-25 20:00:00',78,2,157607,439604,158,27,167126,10,140,18,1,57,0,30,1346);
INSERT INTO `stats` VALUES (544,'POL','2010-02-26 20:00:00',75,0,155004,443417,153,22,177623,10,43,17,4,57,0,29,1343);
INSERT INTO `stats` VALUES (545,'POL','2010-02-27 20:00:00',75,1,154979,444532,138,14,178512,10,11,16,1,57,0,26,1339);
INSERT INTO `stats` VALUES (546,'POL','2010-02-28 20:00:00',75,0,154436,446093,134,1,179973,10,260,16,1,57,0,26,1332);
INSERT INTO `stats` VALUES (547,'POL','2010-03-01 20:00:00',74,0,154218,446311,140,15,180081,10,210,16,2,57,0,28,1329);
INSERT INTO `stats` VALUES (548,'POL','2010-03-02 20:00:00',73,1,157647,442882,153,35,176316,10,363,16,2,57,0,33,1325);
INSERT INTO `stats` VALUES (549,'POL','2010-03-03 20:00:00',71,1,157016,443513,142,39,176947,10,505,16,5,57,0,31,1314);
INSERT INTO `stats` VALUES (550,'POL','2010-03-04 20:00:00',72,1,156379,444150,149,28,177379,10,650,16,0,57,0,29,1309);
INSERT INTO `stats` VALUES (551,'POL','2010-03-05 20:00:00',71,1,162691,437838,164,89,177411,10,2250,16,2,57,0,31,1313);
INSERT INTO `stats` VALUES (552,'POL','2010-03-06 20:00:00',71,2,160652,439877,163,27,180257,10,5060,16,2,57,0,38,1311);
INSERT INTO `stats` VALUES (553,'POL','2010-03-07 20:00:00',66,0,163754,436775,154,5,177155,10,124,15,4,57,0,36,1302);
INSERT INTO `stats` VALUES (554,'POL','2010-03-08 20:00:00',68,2,155054,445475,159,145,185955,10,33,15,0,57,0,34,1310);
INSERT INTO `stats` VALUES (555,'POL','2010-03-09 20:00:00',67,1,155011,445518,143,93,186008,10,62,16,2,57,0,35,1299);
INSERT INTO `stats` VALUES (556,'POL','2010-03-10 20:00:00',67,2,157050,443479,148,51,185833,11,111,16,2,57,0,37,1289);
INSERT INTO `stats` VALUES (557,'POL','2010-03-11 20:00:00',68,0,164369,439791,153,75,185855,11,83,15,0,57,0,35,1301);
INSERT INTO `stats` VALUES (558,'POL','2010-03-12 20:00:00',65,1,164437,437712,150,25,188483,10,179,13,1,57,0,35,1270);
INSERT INTO `stats` VALUES (559,'POL','2010-03-13 20:00:00',64,0,164556,437593,140,9,188483,10,21,13,1,57,0,26,1272);
INSERT INTO `stats` VALUES (560,'POL','2010-03-14 20:00:00',65,1,165375,466935,137,63,217825,10,84,13,0,57,0,28,1291);
INSERT INTO `stats` VALUES (561,'POL','2010-03-15 20:00:00',65,1,165583,466727,137,23,217617,10,135,13,1,57,0,30,1298);
INSERT INTO `stats` VALUES (562,'POL','2010-03-16 20:00:00',64,0,174172,458138,142,57,218045,11,130,13,2,57,0,28,1293);
INSERT INTO `stats` VALUES (563,'POL','2010-03-17 20:00:00',68,4,173943,458367,137,32,218284,11,141,13,0,57,0,32,1291);
INSERT INTO `stats` VALUES (564,'POL','2010-03-18 20:00:00',71,2,173948,458362,142,58,218579,11,165,13,0,57,0,33,1294);
INSERT INTO `stats` VALUES (565,'POL','2010-03-19 20:00:00',72,2,172733,459577,148,39,219514,11,765,13,1,57,0,32,1307);
INSERT INTO `stats` VALUES (566,'POL','2010-03-20 20:00:00',70,2,172529,459781,145,62,219718,11,700,13,3,57,0,35,1310);
INSERT INTO `stats` VALUES (567,'POL','2010-03-21 20:00:00',70,0,172579,459731,136,33,219668,11,80,13,0,57,0,28,1309);
INSERT INTO `stats` VALUES (568,'POL','2010-03-22 20:00:00',71,2,172909,459401,135,54,219304,11,0,13,1,57,0,34,1327);
INSERT INTO `stats` VALUES (569,'POL','2010-03-23 20:00:00',74,3,175091,457219,152,40,217368,11,33,13,0,57,0,36,1327);
INSERT INTO `stats` VALUES (570,'POL','2010-03-24 20:00:00',73,1,163759,469451,137,14,229600,10,103,13,7,57,0,27,1233);
INSERT INTO `stats` VALUES (571,'POL','2010-03-25 20:00:00',74,2,163693,469517,140,41,229666,10,370,12,2,57,0,30,1238);
INSERT INTO `stats` VALUES (572,'POL','2010-03-26 20:00:00',76,2,164602,468608,143,39,225563,9,104,12,0,57,0,34,1219);
INSERT INTO `stats` VALUES (573,'POL','2010-03-27 20:00:00',73,0,164856,468354,136,12,225309,10,41,12,3,57,0,28,1223);
INSERT INTO `stats` VALUES (574,'POL','2010-03-28 20:00:00',73,1,165021,468189,133,16,225114,10,0,12,1,57,0,26,1236);
INSERT INTO `stats` VALUES (575,'POL','2010-03-29 20:00:00',74,2,165462,467748,136,12,224773,10,80,12,2,57,0,30,1234);
INSERT INTO `stats` VALUES (576,'POL','2010-03-30 20:00:00',69,0,161795,468275,133,11,224900,10,0,12,2,57,0,27,1232);
INSERT INTO `stats` VALUES (577,'POL','2010-03-31 20:00:00',68,0,162880,467190,137,18,225052,11,12,12,1,57,0,29,1199);
INSERT INTO `stats` VALUES (578,'POL','2010-04-01 20:00:00',67,1,161540,468530,133,7,226692,10,12,12,3,57,0,29,1185);
INSERT INTO `stats` VALUES (579,'POL','2010-04-02 20:00:00',64,0,161743,468327,131,19,226489,10,109,12,3,57,0,21,1185);
INSERT INTO `stats` VALUES (580,'POL','2010-04-03 20:00:00',67,2,160783,469287,136,18,227649,10,530,12,0,57,0,34,1189);
INSERT INTO `stats` VALUES (581,'POL','2010-04-04 20:00:00',66,0,151910,478160,136,16,227522,10,181,12,2,57,0,29,1193);
INSERT INTO `stats` VALUES (582,'POL','2010-04-05 20:00:00',63,1,131774,498296,137,47,227658,10,12,12,4,57,0,29,1223);
INSERT INTO `stats` VALUES (583,'POL','2010-04-06 20:00:00',63,1,133181,496889,139,95,227673,11,111,12,0,57,0,31,1241);
INSERT INTO `stats` VALUES (584,'POL','2010-04-07 20:00:00',61,0,130583,499487,136,82,227545,10,92,12,2,57,0,30,1239);
INSERT INTO `stats` VALUES (585,'POL','2010-04-08 20:00:00',60,2,129405,499744,130,45,227402,10,12,12,2,57,0,26,1255);
INSERT INTO `stats` VALUES (586,'POL','2010-04-09 20:00:00',60,0,130521,498628,128,29,222860,9,36,11,3,57,0,23,1264);
INSERT INTO `stats` VALUES (587,'POL','2010-04-10 20:00:00',60,0,130542,498607,121,6,222839,9,11,11,0,57,0,25,1276);
INSERT INTO `stats` VALUES (588,'POL','2010-04-11 20:00:00',60,1,130630,498519,119,9,222751,9,83,11,1,57,0,28,1280);
INSERT INTO `stats` VALUES (589,'POL','2010-04-12 20:00:00',60,0,130522,498627,122,7,222859,9,280,11,1,57,0,27,1285);
INSERT INTO `stats` VALUES (590,'POL','2010-04-13 20:00:00',58,0,132417,496732,127,32,222728,9,33,11,3,57,0,27,1306);
INSERT INTO `stats` VALUES (591,'POL','2010-04-14 20:00:00',58,0,132573,496576,120,27,222572,9,11,11,0,57,0,25,1312);
INSERT INTO `stats` VALUES (592,'POL','2010-04-15 20:00:00',59,1,132619,497202,121,18,222548,9,83,11,1,57,0,25,1321);
INSERT INTO `stats` VALUES (593,'POL','2010-04-16 20:00:00',59,1,133861,495960,125,33,222608,9,103,11,1,57,0,28,1329);
INSERT INTO `stats` VALUES (594,'POL','2010-04-17 20:00:00',59,0,133618,496203,121,10,222851,9,84,11,1,57,0,31,1332);
INSERT INTO `stats` VALUES (595,'POL','2010-04-18 20:00:00',59,2,133584,496237,119,21,222885,9,122,11,2,57,0,27,1311);
INSERT INTO `stats` VALUES (596,'POL','2010-04-19 20:00:00',59,1,133345,496476,128,30,223124,9,300,11,1,57,0,28,1314);
INSERT INTO `stats` VALUES (597,'POL','2010-04-20 20:00:00',58,0,134196,495625,129,53,224037,9,12,11,0,57,0,28,1325);
INSERT INTO `stats` VALUES (598,'POL','2010-04-21 20:00:00',58,1,134202,495619,116,28,224031,9,82,11,1,57,0,27,1310);
INSERT INTO `stats` VALUES (599,'POL','2010-04-22 20:00:00',58,0,134189,495632,119,13,224044,9,105,11,0,57,0,23,1314);
INSERT INTO `stats` VALUES (600,'POL','2010-04-23 20:00:00',61,2,136038,493783,127,18,223959,9,70,11,0,57,0,30,1340);
INSERT INTO `stats` VALUES (601,'POL','2010-04-24 20:00:00',61,0,136017,493804,118,12,218980,9,11,11,0,57,0,21,1350);
INSERT INTO `stats` VALUES (602,'POL','2010-04-25 20:00:00',60,0,135582,494239,117,4,219415,9,1,11,1,57,0,23,1358);
INSERT INTO `stats` VALUES (603,'POL','2010-04-26 20:00:00',60,1,132464,494657,118,27,219833,9,11,11,1,57,0,25,1358);
INSERT INTO `stats` VALUES (604,'POL','2010-04-27 20:00:00',62,2,133229,493892,121,27,219955,9,21,11,0,57,0,25,1370);
INSERT INTO `stats` VALUES (605,'POL','2010-04-28 20:00:00',61,0,133530,493591,132,53,219654,10,36,11,3,57,0,28,1381);
INSERT INTO `stats` VALUES (606,'POL','2010-04-29 20:00:00',62,1,133646,493475,129,59,219638,11,112,11,0,57,0,28,1385);
INSERT INTO `stats` VALUES (607,'POL','2010-04-30 20:00:00',62,1,134486,492194,133,46,220121,11,560,11,1,57,0,30,1390);
INSERT INTO `stats` VALUES (608,'POL','2010-05-01 20:00:00',59,0,136710,489970,125,32,217897,11,30,11,2,57,0,30,1387);
INSERT INTO `stats` VALUES (609,'POL','2010-05-02 20:00:00',60,1,136240,490440,128,13,218367,11,601,11,0,57,0,26,1393);
INSERT INTO `stats` VALUES (610,'POL','2010-05-03 20:00:00',58,1,136276,490412,128,61,218339,11,43,11,4,57,0,29,1394);
INSERT INTO `stats` VALUES (611,'POL','2010-05-04 20:00:00',57,0,136756,489277,71,16,218968,10,571,11,0,57,0,21,1400);
INSERT INTO `stats` VALUES (612,'POL','2010-05-05 20:00:00',56,0,136645,489388,122,23,219079,10,160,11,1,57,0,22,1406);
INSERT INTO `stats` VALUES (613,'POL','2010-05-06 20:00:00',57,2,131613,494420,125,17,219111,10,5200,11,1,57,0,27,1397);
INSERT INTO `stats` VALUES (614,'POL','2010-05-07 20:00:00',58,2,133316,492717,131,3,219272,10,340,11,1,57,0,26,1364);
INSERT INTO `stats` VALUES (615,'POL','2010-05-08 20:00:00',58,0,133268,492765,128,6,219320,10,220,11,0,57,0,27,1365);
INSERT INTO `stats` VALUES (616,'POL','2010-05-09 20:00:00',59,1,133306,492727,121,86,219282,10,130,11,0,57,0,27,1361);
INSERT INTO `stats` VALUES (617,'POL','2010-05-10 20:00:00',60,1,133535,492498,123,39,219053,10,0,11,1,57,0,30,1365);
INSERT INTO `stats` VALUES (618,'POL','2010-05-11 20:00:00',60,0,135114,490919,126,30,218818,10,1,11,4,57,0,27,1379);
INSERT INTO `stats` VALUES (619,'POL','2010-05-12 20:00:00',61,3,129924,491342,125,56,219241,10,130,11,2,57,0,29,1409);
INSERT INTO `stats` VALUES (620,'POL','2010-05-13 20:00:00',61,2,128889,492377,130,58,220276,9,12,11,2,57,0,24,1422);
INSERT INTO `stats` VALUES (621,'POL','2010-05-14 20:00:00',60,0,125560,493043,128,67,221242,9,130,11,2,57,0,24,1414);
INSERT INTO `stats` VALUES (622,'POL','2010-05-15 20:00:00',62,2,123634,493307,148,30,222506,9,130,11,0,57,0,32,1440);
INSERT INTO `stats` VALUES (623,'POL','2010-05-16 20:00:00',61,0,123432,493509,113,14,222708,9,0,11,1,57,0,23,1452);
INSERT INTO `stats` VALUES (624,'POL','2010-05-17 20:00:00',60,0,123387,493451,112,45,222650,9,12,11,1,57,0,24,1457);
INSERT INTO `stats` VALUES (625,'POL','2010-05-18 20:00:00',73,13,124971,491867,125,22,217530,9,11,11,0,55,0,37,1493);
INSERT INTO `stats` VALUES (626,'POL','2010-05-19 20:00:00',77,5,125206,491632,119,130,217295,10,110,11,1,56,0,31,1510);
INSERT INTO `stats` VALUES (627,'POL','2010-05-20 20:00:00',77,1,127136,489712,123,31,216880,10,13,11,2,56,0,25,1507);
INSERT INTO `stats` VALUES (628,'POL','2010-05-21 20:00:00',79,2,127198,489650,112,47,216418,10,11,11,0,56,0,27,1516);
INSERT INTO `stats` VALUES (629,'POL','2010-05-22 20:00:00',77,4,144240,472608,112,72,216376,10,160,11,6,56,0,25,1513);
INSERT INTO `stats` VALUES (630,'VULCAN','2009-04-25 20:00:00',5,10,289215,500000,2,110,500000,7,1,21,0,0,0,80,64);
INSERT INTO `stats` VALUES (631,'VULCAN','2009-04-26 20:00:00',5,6,287882,500000,3,156,500000,7,1,22,0,0,0,84,6);
INSERT INTO `stats` VALUES (632,'VULCAN','2009-04-27 20:00:00',5,2,287870,500000,2,56,500000,7,0,22,0,0,0,75,3);
INSERT INTO `stats` VALUES (633,'VULCAN','2009-04-28 20:00:00',5,1,228092,500000,2,92,500000,7,1,16,0,0,0,72,36);
INSERT INTO `stats` VALUES (634,'VULCAN','2009-04-29 20:00:00',5,3,225123,500000,0,67,500000,7,0,17,0,0,0,74,-64);
INSERT INTO `stats` VALUES (635,'VULCAN','2009-04-30 20:00:00',5,4,196022,500000,0,126,500000,7,0,20,0,0,0,76,192);
INSERT INTO `stats` VALUES (636,'VULCAN','2009-05-01 20:00:00',10,8,200251,500000,0,37,500000,7,0,20,0,0,0,74,211);
INSERT INTO `stats` VALUES (637,'VULCAN','2009-05-02 20:00:00',15,5,208733,500000,0,16,500000,7,0,20,0,0,0,73,202);
INSERT INTO `stats` VALUES (638,'VULCAN','2009-05-03 20:00:00',20,2,207908,500482,10,52,500482,7,0,20,1,61,0,80,209);
INSERT INTO `stats` VALUES (639,'VULCAN','2009-05-05 20:00:00',27,1,1001,498999,38,117,498659,7,43,20,0,62,0,15,225);
INSERT INTO `stats` VALUES (640,'VULCAN','2009-05-06 20:00:00',28,0,1068,498932,51,51,498422,7,0,20,0,62,0,13,235);
INSERT INTO `stats` VALUES (641,'VULCAN','2009-05-07 20:00:00',28,0,2509,497491,63,95,496784,7,2,20,0,62,0,13,258);
INSERT INTO `stats` VALUES (642,'VULCAN','2009-05-08 20:00:00',29,0,2523,497477,40,68,496607,7,3,20,0,62,0,13,257);
INSERT INTO `stats` VALUES (643,'VULCAN','2009-05-09 20:00:00',29,0,2609,497391,32,20,496321,7,2,20,0,62,0,11,259);
INSERT INTO `stats` VALUES (644,'VULCAN','2009-05-10 20:00:00',29,0,2674,497326,37,43,496151,7,2,20,0,62,0,13,297);
INSERT INTO `stats` VALUES (645,'VULCAN','2009-05-11 20:00:00',28,0,2559,497441,54,23,496296,7,5,20,2,63,0,15,268);
INSERT INTO `stats` VALUES (646,'VULCAN','2009-05-12 20:00:00',27,1,2685,497315,59,30,496180,7,2,20,0,63,0,14,246);
INSERT INTO `stats` VALUES (647,'VULCAN','2009-05-13 20:00:00',27,0,2759,497241,43,12,496066,7,11,20,0,63,0,12,240);
INSERT INTO `stats` VALUES (648,'VULCAN','2009-05-14 20:00:00',27,0,2841,497159,43,38,495824,7,22,20,0,63,0,13,230);
INSERT INTO `stats` VALUES (649,'VULCAN','2009-05-15 20:00:00',28,0,2952,497048,35,21,495553,7,5,19,0,63,0,13,243);
INSERT INTO `stats` VALUES (650,'VULCAN','2009-05-16 20:00:00',27,0,2901,497099,41,7,495484,7,5,19,1,63,0,12,250);
INSERT INTO `stats` VALUES (651,'VULCAN','2009-05-17 20:00:00',30,3,2991,497009,31,16,495244,7,2,19,0,64,0,13,257);
INSERT INTO `stats` VALUES (652,'VULCAN','2009-05-18 20:00:00',26,0,2828,497172,41,26,495382,7,12,19,3,64,0,15,258);
INSERT INTO `stats` VALUES (653,'VULCAN','2009-05-19 20:00:00',25,2,2587,497413,46,32,495473,7,5,19,4,64,0,16,265);
INSERT INTO `stats` VALUES (654,'VULCAN','2009-05-20 20:00:00',22,0,2485,497515,45,45,495405,7,11,19,3,64,0,13,249);
INSERT INTO `stats` VALUES (655,'VULCAN','2009-05-21 20:00:00',22,0,2578,497422,44,30,495142,7,11,19,0,64,0,13,249);
INSERT INTO `stats` VALUES (656,'VULCAN','2009-05-22 20:00:00',22,0,2748,497252,30,11,494882,7,0,19,0,64,0,14,238);
INSERT INTO `stats` VALUES (657,'VULCAN','2009-05-23 20:00:00',23,3,2511,497489,41,18,494969,7,52,19,1,64,0,15,239);
INSERT INTO `stats` VALUES (658,'VULCAN','2009-05-24 20:00:00',25,2,2481,497519,42,17,495059,7,0,19,0,65,0,13,246);
INSERT INTO `stats` VALUES (659,'VULCAN','2009-05-25 20:00:00',25,0,2651,497349,29,10,494879,7,0,19,0,65,0,13,241);
INSERT INTO `stats` VALUES (660,'VULCAN','2009-05-26 20:00:00',25,0,2936,497064,20,4,494469,7,0,19,0,65,0,14,241);
INSERT INTO `stats` VALUES (661,'VULCAN','2009-05-27 20:00:00',23,0,2502,497498,65,17,493477,7,11,15,0,66,0,11,235);
INSERT INTO `stats` VALUES (662,'VULCAN','2009-05-28 20:00:00',23,0,2762,497238,22,8,493167,7,0,15,0,66,0,11,235);
INSERT INTO `stats` VALUES (663,'VULCAN','2009-05-29 20:00:00',23,0,2921,497079,23,31,492858,7,1,15,0,66,0,11,195);
INSERT INTO `stats` VALUES (664,'VULCAN','2009-05-30 20:00:00',23,0,3190,496810,22,25,492539,7,1,15,0,66,0,12,192);
INSERT INTO `stats` VALUES (665,'VULCAN','2009-05-31 20:00:00',25,1,3249,496751,21,26,492230,7,1,15,0,66,0,14,184);
INSERT INTO `stats` VALUES (666,'VULCAN','2009-06-01 20:00:00',25,0,3199,496801,25,5,492200,7,20,15,1,67,0,12,182);
INSERT INTO `stats` VALUES (667,'VULCAN','2009-06-02 20:00:00',23,0,3207,496793,28,22,492012,7,12,15,3,67,0,13,182);
INSERT INTO `stats` VALUES (668,'VULCAN','2009-06-03 20:00:00',23,2,3303,496697,28,26,492183,6,1,13,2,66,0,15,181);
INSERT INTO `stats` VALUES (669,'VULCAN','2009-06-04 20:00:00',23,1,5068,494932,22,3,491883,6,0,13,0,66,0,10,182);
INSERT INTO `stats` VALUES (670,'VULCAN','2009-06-05 20:00:00',25,2,5022,494978,28,9,491929,6,11,13,0,67,0,11,177);
INSERT INTO `stats` VALUES (671,'VULCAN','2009-06-06 20:00:00',22,1,4982,495018,29,34,491969,6,0,13,1,68,0,10,174);
INSERT INTO `stats` VALUES (672,'VULCAN','2009-06-07 20:00:00',22,0,5207,494793,15,43,491754,6,10,13,0,68,0,8,175);
INSERT INTO `stats` VALUES (673,'VULCAN','2009-06-08 20:00:00',23,1,5401,494599,11,4,491560,6,1,13,0,68,0,7,184);
INSERT INTO `stats` VALUES (674,'VULCAN','2009-06-09 20:00:00',23,1,5651,494349,11,26,491385,6,0,13,3,68,0,7,199);
INSERT INTO `stats` VALUES (675,'VULCAN','2009-06-10 20:00:00',23,1,5456,494544,50,5,491980,6,0,13,4,70,0,10,202);
INSERT INTO `stats` VALUES (676,'VULCAN','2009-06-11 20:00:00',23,0,5366,494634,21,28,491970,7,0,13,2,70,0,8,207);
INSERT INTO `stats` VALUES (677,'VULCAN','2009-06-12 20:00:00',23,1,5390,494610,26,2,491946,7,31,13,1,71,0,10,212);
INSERT INTO `stats` VALUES (678,'VULCAN','2009-06-13 20:00:00',22,1,5588,494412,19,23,491748,7,12,13,2,71,0,11,210);
INSERT INTO `stats` VALUES (679,'VULCAN','2009-06-14 20:00:00',23,0,5643,495224,24,8,492572,7,42,13,1,71,0,11,211);
INSERT INTO `stats` VALUES (680,'VULCAN','2009-06-15 20:00:00',25,1,5964,494885,19,13,492333,7,14,13,2,71,0,16,216);
INSERT INTO `stats` VALUES (681,'VULCAN','2009-06-16 20:00:00',26,2,5918,494931,27,18,492529,7,1,13,6,71,0,13,229);
INSERT INTO `stats` VALUES (682,'VULCAN','2009-06-17 20:00:00',26,1,6189,493811,24,31,491409,7,14,13,2,71,0,14,217);
INSERT INTO `stats` VALUES (683,'VULCAN','2009-06-18 20:00:00',26,1,5842,494158,35,3,491156,6,72,13,2,71,0,14,218);
INSERT INTO `stats` VALUES (684,'VULCAN','2009-06-19 20:00:00',24,0,6102,493898,33,31,490971,6,35,13,6,71,0,10,228);
INSERT INTO `stats` VALUES (685,'VULCAN','2009-06-20 20:00:00',24,1,6106,493894,33,8,490967,6,16,13,2,72,0,11,237);
INSERT INTO `stats` VALUES (686,'VULCAN','2009-06-21 20:00:00',25,1,6322,493678,22,2,490751,6,14,13,1,72,0,9,237);
INSERT INTO `stats` VALUES (687,'VULCAN','2009-06-22 20:00:00',25,1,6521,493479,34,32,480627,6,71,13,1,72,0,13,231);
INSERT INTO `stats` VALUES (688,'VULCAN','2009-06-23 20:00:00',26,1,6895,493105,20,7,480253,6,1,13,2,72,0,12,234);
INSERT INTO `stats` VALUES (689,'VULCAN','2009-06-24 20:00:00',25,0,7105,492895,26,12,480043,6,15,13,0,73,0,10,234);
INSERT INTO `stats` VALUES (690,'VULCAN','2009-06-25 20:00:00',26,2,7295,492705,22,11,479723,6,0,13,1,73,0,12,238);
INSERT INTO `stats` VALUES (691,'VULCAN','2009-06-26 20:00:00',24,0,7432,492568,32,11,479546,6,53,13,2,73,0,10,240);
INSERT INTO `stats` VALUES (692,'VULCAN','2009-06-27 20:00:00',25,1,7732,492268,22,15,479206,6,0,13,0,73,0,10,246);
INSERT INTO `stats` VALUES (693,'VULCAN','2009-06-28 20:00:00',26,2,7801,492199,23,10,479097,6,11,13,0,74,0,10,246);
INSERT INTO `stats` VALUES (694,'VULCAN','2009-06-29 20:00:00',28,2,8108,491892,23,8,478750,6,18,13,5,74,0,13,231);
INSERT INTO `stats` VALUES (695,'VULCAN','2009-06-30 20:00:00',27,0,7719,492288,25,7,478457,5,17,13,1,74,0,9,238);
INSERT INTO `stats` VALUES (696,'VULCAN','2009-07-01 20:00:00',26,1,6888,493119,24,11,478208,5,1,13,3,74,0,10,236);
INSERT INTO `stats` VALUES (697,'VULCAN','2009-07-02 20:00:00',26,1,7117,492890,34,20,477973,5,40,13,1,74,0,11,230);
INSERT INTO `stats` VALUES (698,'VULCAN','2009-07-03 20:00:00',25,0,7482,492518,28,18,477646,6,15,13,5,74,0,10,224);
INSERT INTO `stats` VALUES (699,'VULCAN','2009-07-04 20:00:00',25,0,7645,492355,24,38,477443,6,12,13,0,75,0,8,223);
INSERT INTO `stats` VALUES (700,'VULCAN','2009-07-05 20:00:00',25,1,7730,492270,19,10,477318,6,0,13,1,75,0,7,224);
INSERT INTO `stats` VALUES (701,'VULCAN','2009-07-06 20:00:00',23,0,9104,490896,26,34,476919,6,11,13,2,75,0,10,236);
INSERT INTO `stats` VALUES (702,'VULCAN','2009-07-07 20:00:00',24,1,9478,490522,35,30,476556,6,32,13,2,75,0,13,243);
INSERT INTO `stats` VALUES (703,'VULCAN','2009-07-08 20:00:00',24,2,6541,493459,41,65,476303,5,12,11,2,75,0,13,243);
INSERT INTO `stats` VALUES (704,'VULCAN','2009-07-09 20:00:00',22,0,5388,494612,30,40,478506,4,0,6,2,73,0,10,249);
INSERT INTO `stats` VALUES (705,'VULCAN','2009-07-10 20:00:00',21,0,5762,494238,25,21,478182,4,1,6,0,73,0,11,251);
INSERT INTO `stats` VALUES (706,'VULCAN','2009-07-11 20:00:00',20,0,5966,494034,31,10,477978,4,1,6,1,73,0,9,249);
INSERT INTO `stats` VALUES (707,'VULCAN','2009-07-12 20:00:00',21,1,6446,493554,32,1,478099,4,11,6,0,74,0,8,242);
INSERT INTO `stats` VALUES (708,'VULCAN','2009-07-13 20:00:00',17,0,5264,494666,21,37,479211,3,1,5,3,72,0,9,259);
INSERT INTO `stats` VALUES (709,'VULCAN','2009-07-14 20:00:00',18,0,5133,494797,28,31,479182,3,1,3,0,71,0,10,250);
INSERT INTO `stats` VALUES (710,'VULCAN','2009-07-15 20:00:00',16,0,5323,494607,25,30,378993,5,0,3,1,71,0,8,262);
INSERT INTO `stats` VALUES (711,'VULCAN','2009-07-16 20:00:00',12,0,1546,498384,29,52,381931,1,0,3,1,64,0,5,268);
INSERT INTO `stats` VALUES (712,'VULCAN','2009-07-17 20:00:00',10,0,1496,498434,1,10,381981,1,0,3,0,64,0,1,268);
INSERT INTO `stats` VALUES (713,'VULCAN','2009-07-18 20:00:00',9,0,1591,498339,2,15,381886,1,0,3,2,64,0,4,263);
INSERT INTO `stats` VALUES (714,'VULCAN','2009-07-19 20:00:00',8,0,1686,498244,4,8,381791,1,0,3,0,64,0,2,230);
INSERT INTO `stats` VALUES (715,'VULCAN','2009-07-20 20:00:00',8,0,1770,498160,6,1,381707,1,11,3,0,64,0,4,217);
INSERT INTO `stats` VALUES (716,'VULCAN','2009-07-21 20:00:00',8,0,1568,498362,6,2,381909,1,0,3,0,63,0,3,218);
INSERT INTO `stats` VALUES (717,'VULCAN','2009-07-22 20:00:00',7,0,1618,498312,2,1,381859,1,0,3,1,63,0,2,203);
INSERT INTO `stats` VALUES (718,'VULCAN','2009-07-23 20:00:00',7,0,1668,498262,2,0,381809,1,0,3,0,63,0,3,197);
INSERT INTO `stats` VALUES (719,'VULCAN','2009-07-24 20:00:00',6,0,1703,498227,5,0,381774,1,0,3,0,63,0,2,187);
INSERT INTO `stats` VALUES (720,'VULCAN','2009-07-25 20:00:00',7,1,1753,498177,2,0,381724,1,0,3,0,63,0,4,189);
INSERT INTO `stats` VALUES (721,'VULCAN','2009-07-26 20:00:00',6,0,1763,498167,4,1,381714,1,0,3,2,63,0,1,185);
INSERT INTO `stats` VALUES (722,'VULCAN','2009-07-27 20:00:00',6,0,1808,498122,2,0,381669,1,0,3,4,63,0,2,194);
INSERT INTO `stats` VALUES (723,'VULCAN','2009-07-28 20:00:00',7,0,1808,498122,1,5,381669,1,0,3,0,63,0,2,196);
INSERT INTO `stats` VALUES (724,'VULCAN','2009-07-29 20:00:00',7,0,0,499930,1,0,383477,0,0,0,0,61,0,2,205);
INSERT INTO `stats` VALUES (725,'VULCAN','2009-07-30 20:00:00',10,0,0,499930,0,45,383477,0,0,0,0,61,0,6,214);
INSERT INTO `stats` VALUES (726,'VULCAN','2009-07-31 20:00:00',12,1,0,499930,0,10,383477,0,0,0,0,61,0,7,260);
INSERT INTO `stats` VALUES (727,'VULCAN','2009-08-01 20:00:00',11,0,0,499930,0,1,383477,0,0,0,0,61,0,4,247);
INSERT INTO `stats` VALUES (728,'VULCAN','2009-08-02 20:00:00',13,0,-20,499950,2,4,383497,0,0,0,0,61,0,5,246);
INSERT INTO `stats` VALUES (729,'VULCAN','2009-08-03 20:00:00',13,0,110,499820,9,11,383367,3,0,0,3,61,0,4,246);
INSERT INTO `stats` VALUES (730,'VULCAN','2009-08-04 20:00:00',12,0,252,499678,14,38,383914,2,12,0,6,61,0,5,239);
INSERT INTO `stats` VALUES (731,'VULCAN','2009-08-05 20:00:00',13,0,454,499476,17,20,383712,3,1,0,0,61,0,6,524);
INSERT INTO `stats` VALUES (732,'VULCAN','2009-08-06 20:00:00',13,0,633,499297,13,11,383533,3,26,0,5,61,0,5,609);
INSERT INTO `stats` VALUES (733,'VULCAN','2009-08-07 20:00:00',13,0,841,499089,21,36,494131,4,2,0,3,61,0,7,661);
INSERT INTO `stats` VALUES (734,'VULCAN','2009-08-08 20:00:00',13,0,902,499028,17,12,494024,4,3,0,1,61,0,6,716);
INSERT INTO `stats` VALUES (735,'VULCAN','2009-08-09 20:00:00',13,0,1101,498829,14,5,493825,4,11,0,2,61,0,6,720);
INSERT INTO `stats` VALUES (736,'VULCAN','2009-08-10 20:00:00',11,0,1205,6518,20,10,6312,3,3,1,2,61,0,6,730);
INSERT INTO `stats` VALUES (737,'VULCAN','2009-08-11 20:00:00',10,0,1020,16369,26,7,9851,3,0,1,0,61,0,4,736);
INSERT INTO `stats` VALUES (738,'VULCAN','2009-08-12 20:00:00',9,0,1099,15987,10,2,-179,2,0,0,0,61,0,3,740);
INSERT INTO `stats` VALUES (739,'VULCAN','2009-08-13 20:00:00',10,0,1378,15707,8,10,-459,2,0,0,0,61,0,4,725);
INSERT INTO `stats` VALUES (740,'VULCAN','2009-08-14 20:00:00',10,0,1653,15432,7,9,-734,2,0,0,0,61,0,3,733);
INSERT INTO `stats` VALUES (741,'VULCAN','2009-08-15 20:00:00',8,0,1782,15234,9,0,-932,2,0,0,1,61,0,3,718);
INSERT INTO `stats` VALUES (742,'VULCAN','2009-08-16 20:00:00',8,0,1887,15129,6,6,-1037,2,0,0,0,61,0,2,729);
INSERT INTO `stats` VALUES (743,'VULCAN','2009-08-17 20:00:00',7,0,2085,14931,8,1,-1235,2,0,0,1,61,0,3,741);
INSERT INTO `stats` VALUES (744,'VULCAN','2009-08-18 20:00:00',7,0,2090,14926,5,0,-1240,2,0,0,0,61,0,1,750);
INSERT INTO `stats` VALUES (745,'VULCAN','2009-08-19 20:00:00',7,0,2295,14721,4,0,-1445,2,0,0,0,61,0,3,797);
INSERT INTO `stats` VALUES (746,'VULCAN','2009-08-20 20:00:00',8,1,2399,14617,8,7,-1549,2,0,0,1,61,0,3,794);
INSERT INTO `stats` VALUES (747,'VULCAN','2009-08-21 20:00:00',7,0,2595,14421,10,8,-1745,2,0,0,0,61,0,2,829);
INSERT INTO `stats` VALUES (748,'VULCAN','2009-08-22 20:00:00',9,1,2802,14214,9,1,-1952,2,0,0,0,61,0,4,828);
INSERT INTO `stats` VALUES (749,'VULCAN','2009-08-23 20:00:00',9,0,2901,14115,11,1,-2051,2,2,0,2,61,0,2,838);
INSERT INTO `stats` VALUES (750,'VULCAN','2009-08-24 20:00:00',9,0,3092,13924,11,4,-2242,2,11,0,0,61,0,3,856);
INSERT INTO `stats` VALUES (751,'VULCAN','2009-08-25 20:00:00',12,0,3756,13705,15,8,-2461,3,0,0,0,61,0,7,908);
INSERT INTO `stats` VALUES (752,'VULCAN','2009-08-26 20:00:00',11,0,18555,-104,67,39,-104,3,16,0,0,0,0,6,922);
INSERT INTO `stats` VALUES (753,'VULCAN','2009-08-27 20:00:00',14,1,3260,15299,29,24,15299,3,42,0,3,0,0,9,938);
INSERT INTO `stats` VALUES (754,'VULCAN','2009-08-28 20:00:00',16,0,7358,15156,26,103,15156,4,71,0,2,5,0,7,942);
INSERT INTO `stats` VALUES (755,'VULCAN','2009-08-29 20:00:00',16,0,7547,15067,27,33,15067,4,21,0,2,12,0,11,950);
INSERT INTO `stats` VALUES (756,'VULCAN','2009-08-30 20:00:00',16,0,7627,17425,37,32,15125,4,80,2,3,18,0,8,962);
INSERT INTO `stats` VALUES (757,'VULCAN','2009-08-31 20:00:00',18,1,8549,17272,51,25,14972,4,21,5,3,48,0,12,943);
INSERT INTO `stats` VALUES (758,'VULCAN','2009-09-01 20:00:00',17,0,8622,17199,54,23,14899,5,21,7,1,49,0,11,1037);
INSERT INTO `stats` VALUES (759,'VULCAN','2009-09-02 20:00:00',17,0,8816,17005,42,5,14705,4,21,7,0,50,0,9,1118);
INSERT INTO `stats` VALUES (760,'VULCAN','2009-09-03 20:00:00',20,3,9038,16783,38,17,14483,4,11,7,1,50,0,13,1132);
INSERT INTO `stats` VALUES (761,'VULCAN','2009-09-04 20:00:00',20,0,9044,16783,324,9,14483,5,50,7,2,50,0,10,1119);
INSERT INTO `stats` VALUES (762,'VULCAN','2009-09-05 20:00:00',20,0,9167,16690,40,10,14390,5,30,7,0,50,0,11,1121);
INSERT INTO `stats` VALUES (763,'VULCAN','2009-09-06 20:00:00',20,0,9213,16644,40,10,14344,5,32,8,1,50,0,9,1122);
INSERT INTO `stats` VALUES (764,'VULCAN','2009-09-07 20:00:00',22,1,9213,16644,50,8,14304,5,12,8,3,50,0,10,1126);
INSERT INTO `stats` VALUES (765,'VULCAN','2009-09-08 20:00:00',22,0,9212,16632,50,20,14192,5,3,8,1,50,0,13,1135);
INSERT INTO `stats` VALUES (766,'VULCAN','2009-09-09 20:00:00',22,0,9335,16509,50,71,14009,5,1,8,0,50,0,15,1149);
INSERT INTO `stats` VALUES (767,'VULCAN','2009-09-10 20:00:00',22,0,9301,16543,52,29,13938,5,30,10,5,50,0,12,1149);
INSERT INTO `stats` VALUES (768,'VULCAN','2009-09-11 20:00:00',21,0,9019,16825,59,46,13950,6,106,11,7,50,0,14,1145);
INSERT INTO `stats` VALUES (769,'VULCAN','2009-09-12 20:00:00',21,0,8588,16774,53,39,13829,6,2,9,1,47,0,15,1181);
INSERT INTO `stats` VALUES (770,'VULCAN','2009-09-13 20:00:00',20,0,8612,16697,48,23,13682,6,2,9,1,47,0,11,1183);
INSERT INTO `stats` VALUES (771,'VULCAN','2009-09-14 20:00:00',20,0,8348,16972,47,10,13627,6,2,10,0,47,0,10,1189);
INSERT INTO `stats` VALUES (772,'VULCAN','2009-09-15 20:00:00',18,0,8373,16955,53,28,13579,6,3,9,3,47,0,13,1206);
INSERT INTO `stats` VALUES (773,'VULCAN','2009-09-16 20:00:00',17,0,8432,16937,52,25,13411,6,1,9,1,47,0,12,1216);
INSERT INTO `stats` VALUES (774,'VULCAN','2009-09-17 20:00:00',17,0,8444,16925,46,11,13349,6,60,9,0,47,0,11,1231);
INSERT INTO `stats` VALUES (775,'VULCAN','2009-09-18 20:00:00',17,0,-41566,66935,56,6,63309,6,116,9,0,48,0,9,1228);
INSERT INTO `stats` VALUES (776,'VULCAN','2009-09-19 20:00:00',17,1,8504,151845,61,29,73229,6,16,9,0,48,0,13,1230);
INSERT INTO `stats` VALUES (777,'VULCAN','2009-09-20 20:00:00',18,1,8592,151757,43,20,73141,6,2,9,0,48,0,12,1236);
INSERT INTO `stats` VALUES (778,'VULCAN','2009-09-21 20:00:00',18,0,9005,68468,99,62,64801,6,1,9,0,48,0,12,1227);
INSERT INTO `stats` VALUES (779,'VULCAN','2009-09-22 20:00:00',24,5,9248,68290,75,42,64476,6,5,9,0,48,0,16,1236);
INSERT INTO `stats` VALUES (780,'VULCAN','2009-09-23 20:00:00',24,0,9405,68135,60,47,64121,6,24,9,1,49,0,16,1250);
INSERT INTO `stats` VALUES (781,'VULCAN','2009-09-24 20:00:00',23,1,9598,67256,54,20,63886,6,30,8,0,49,0,14,1253);
INSERT INTO `stats` VALUES (782,'VULCAN','2009-09-25 20:00:00',22,0,9789,67065,51,19,63695,6,12,9,0,50,0,9,1239);
INSERT INTO `stats` VALUES (783,'VULCAN','2009-09-26 20:00:00',22,0,10084,66770,32,4,63400,6,1,9,2,50,0,11,1236);
INSERT INTO `stats` VALUES (784,'VULCAN','2009-09-27 20:00:00',23,1,10432,66453,42,1,63083,6,10,9,0,50,0,11,1236);
INSERT INTO `stats` VALUES (785,'VULCAN','2009-09-28 20:00:00',19,0,10682,66203,53,3,62833,6,11,9,2,52,0,8,1285);
INSERT INTO `stats` VALUES (786,'VULCAN','2009-09-29 20:00:00',19,0,10947,66108,53,0,62738,6,1,9,4,53,0,9,1276);
INSERT INTO `stats` VALUES (787,'VULCAN','2009-09-30 20:00:00',20,1,11412,65643,32,3,62243,6,0,10,0,53,0,10,1278);
INSERT INTO `stats` VALUES (788,'VULCAN','2009-10-01 20:00:00',20,0,11868,65216,232,3,61846,6,1,10,4,66,1,10,1268);
INSERT INTO `stats` VALUES (789,'VULCAN','2009-10-02 20:00:00',18,0,11867,64761,55,4,61391,6,10,9,3,66,0,8,1256);
INSERT INTO `stats` VALUES (790,'VULCAN','2009-10-03 20:00:00',17,0,12305,64417,34,0,61047,6,11,9,1,66,0,9,1245);
INSERT INTO `stats` VALUES (791,'VULCAN','2009-10-04 20:00:00',16,0,12500,64222,32,0,60852,6,0,9,1,66,0,7,1244);
INSERT INTO `stats` VALUES (792,'VULCAN','2009-10-05 20:00:00',17,0,12751,64016,36,1,60646,6,2,9,0,66,0,9,1244);
INSERT INTO `stats` VALUES (793,'VULCAN','2009-10-06 20:00:00',17,0,13082,63685,37,16,60315,6,0,9,3,66,0,9,1247);
INSERT INTO `stats` VALUES (794,'VULCAN','2009-10-07 20:00:00',16,0,9406,63411,52,8,60036,6,21,10,1,66,0,9,1233);
INSERT INTO `stats` VALUES (795,'VULCAN','2009-10-08 20:00:00',17,1,10803,63176,47,2,59796,6,11,10,8,66,0,6,1237);
INSERT INTO `stats` VALUES (796,'VULCAN','2009-10-09 20:00:00',16,0,10104,63147,39,3,59767,6,0,9,0,66,0,6,1240);
INSERT INTO `stats` VALUES (797,'VULCAN','2009-10-10 20:00:00',16,0,9532,63724,37,7,59744,6,0,8,1,66,0,7,1242);
INSERT INTO `stats` VALUES (798,'VULCAN','2009-10-11 20:00:00',17,1,9649,63612,31,1,59632,6,0,8,0,66,0,6,1256);
INSERT INTO `stats` VALUES (799,'VULCAN','2009-10-12 20:00:00',16,0,9762,63500,31,2,59520,6,0,8,1,66,0,7,1264);
INSERT INTO `stats` VALUES (800,'VULCAN','2009-10-13 20:00:00',15,0,9863,63404,32,6,59424,6,0,8,1,66,0,6,1264);
INSERT INTO `stats` VALUES (801,'VULCAN','2009-10-14 20:00:00',16,0,9983,63338,34,3,59358,7,3,8,2,66,0,6,1271);
INSERT INTO `stats` VALUES (802,'VULCAN','2009-10-15 20:00:00',15,0,10248,63177,43,14,59197,6,0,8,1,66,0,6,1273);
INSERT INTO `stats` VALUES (803,'VULCAN','2009-10-16 20:00:00',15,0,10351,63079,33,13,59099,6,13,8,0,66,0,6,1273);
INSERT INTO `stats` VALUES (804,'VULCAN','2009-10-17 20:00:00',15,0,10494,62544,37,7,58964,6,2,8,1,66,0,8,1272);
INSERT INTO `stats` VALUES (805,'VULCAN','2009-10-18 20:00:00',16,2,9495,62401,34,3,58821,6,3,8,2,66,0,9,1255);
INSERT INTO `stats` VALUES (806,'VULCAN','2009-10-19 20:00:00',17,1,10654,61247,45,37,58267,7,14,15,0,66,0,8,1260);
INSERT INTO `stats` VALUES (807,'VULCAN','2009-10-20 20:00:00',17,0,11196,65780,52,10,62800,7,0,15,0,66,0,7,1256);
INSERT INTO `stats` VALUES (808,'VULCAN','2009-10-21 20:00:00',16,0,11777,65204,51,3,62224,7,1,14,1,66,0,8,1258);
INSERT INTO `stats` VALUES (809,'VULCAN','2009-10-22 20:00:00',16,0,12479,64507,56,4,61527,7,13,15,1,66,0,9,1260);
INSERT INTO `stats` VALUES (810,'VULCAN','2009-10-23 20:00:00',18,2,13076,63915,58,17,60935,7,3,17,0,66,0,8,1264);
INSERT INTO `stats` VALUES (811,'VULCAN','2009-10-24 20:00:00',18,0,13102,63894,60,19,60314,7,2,17,0,66,0,8,1281);
INSERT INTO `stats` VALUES (812,'VULCAN','2009-10-25 20:00:00',19,0,13489,63400,66,12,59720,8,17,18,0,66,0,9,1283);
INSERT INTO `stats` VALUES (813,'VULCAN','2009-10-26 20:00:00',20,1,14028,62866,63,3,59086,8,2,19,0,66,0,9,1290);
INSERT INTO `stats` VALUES (814,'VULCAN','2009-10-27 20:00:00',20,0,14544,62355,61,6,58475,8,0,19,0,66,0,8,1289);
INSERT INTO `stats` VALUES (815,'VULCAN','2009-10-28 20:00:00',19,1,15085,61819,63,5,57839,7,21,19,2,66,0,10,1291);
INSERT INTO `stats` VALUES (816,'VULCAN','2009-10-29 20:00:00',19,0,15800,61363,66,3,57283,7,12,19,0,66,0,7,1290);
INSERT INTO `stats` VALUES (817,'VULCAN','2009-10-30 20:00:00',18,0,14830,61307,59,19,57127,6,60,17,0,60,0,6,1303);
INSERT INTO `stats` VALUES (818,'VULCAN','2009-10-31 20:00:00',18,0,15121,61021,60,2,56641,6,85,17,0,60,0,9,1304);
INSERT INTO `stats` VALUES (819,'VULCAN','2009-11-01 20:00:00',17,0,15451,60696,58,3,56216,6,11,16,1,60,0,6,1304);
INSERT INTO `stats` VALUES (820,'VULCAN','2009-11-02 20:00:00',16,0,15891,60261,52,6,55781,6,11,15,1,60,0,5,1309);
INSERT INTO `stats` VALUES (821,'VULCAN','2009-11-03 20:00:00',17,0,17308,59786,54,44,55306,6,53,11,0,60,0,8,1310);
INSERT INTO `stats` VALUES (822,'VULCAN','2009-11-04 20:00:00',17,0,13666,63433,57,11,54853,6,1,11,0,60,0,6,1313);
INSERT INTO `stats` VALUES (823,'VULCAN','2009-11-05 20:00:00',16,0,14054,63050,51,4,54370,6,2,10,1,60,0,5,1304);
INSERT INTO `stats` VALUES (824,'VULCAN','2009-11-06 20:00:00',17,1,14613,62496,53,23,53716,6,31,11,0,60,0,8,1312);
INSERT INTO `stats` VALUES (825,'VULCAN','2009-11-07 20:00:00',17,0,15003,62288,55,7,53458,6,1,11,4,60,0,6,1316);
INSERT INTO `stats` VALUES (826,'VULCAN','2009-11-08 20:00:00',20,0,21432,55891,61,13,52878,8,13,12,0,60,0,10,1310);
INSERT INTO `stats` VALUES (827,'VULCAN','2009-11-09 20:00:00',21,0,4710,55118,66,33,52055,8,3,14,1,60,0,10,1310);
INSERT INTO `stats` VALUES (828,'VULCAN','2009-11-10 20:00:00',20,0,5282,54551,73,30,51453,9,163,14,1,60,0,11,1312);
INSERT INTO `stats` VALUES (829,'VULCAN','2009-11-11 20:00:00',21,1,6146,53684,80,88,50572,9,63,15,0,60,0,12,1319);
INSERT INTO `stats` VALUES (830,'VULCAN','2009-11-12 20:00:00',21,0,6922,52913,77,49,49811,10,212,16,0,60,0,13,1322);
INSERT INTO `stats` VALUES (831,'VULCAN','2009-11-13 20:00:00',20,0,3935,52389,84,14,49145,10,101,16,0,60,0,13,1333);
INSERT INTO `stats` VALUES (832,'VULCAN','2009-11-14 20:00:00',21,1,4593,51736,75,27,48492,10,76,16,0,60,0,12,1338);
INSERT INTO `stats` VALUES (833,'VULCAN','2009-11-15 20:00:00',21,0,5316,51018,73,2,47774,10,2,16,0,60,0,11,1338);
INSERT INTO `stats` VALUES (834,'VULCAN','2009-11-16 20:00:00',21,0,-14027,70366,78,26,67082,10,2,17,0,60,0,11,1358);
INSERT INTO `stats` VALUES (835,'VULCAN','2009-11-17 20:00:00',20,0,-13307,69651,75,5,66367,10,44,18,1,60,0,12,1356);
INSERT INTO `stats` VALUES (836,'VULCAN','2009-11-18 20:00:00',19,0,-12594,68943,78,8,65659,9,23,18,1,60,0,11,1332);
INSERT INTO `stats` VALUES (837,'VULCAN','2009-11-19 20:00:00',19,0,-21960,78314,75,31,75030,9,34,18,0,60,0,9,1334);
INSERT INTO `stats` VALUES (838,'VULCAN','2009-11-20 20:00:00',19,0,-21160,77519,72,17,74235,9,64,18,1,60,0,10,1343);
INSERT INTO `stats` VALUES (839,'VULCAN','2009-11-21 20:00:00',19,0,-20547,76911,72,32,73627,9,54,20,0,60,0,9,1338);
INSERT INTO `stats` VALUES (840,'VULCAN','2009-11-22 20:00:00',19,0,-19869,76238,70,11,72954,9,2,20,1,60,0,10,1336);
INSERT INTO `stats` VALUES (841,'VULCAN','2009-11-23 20:00:00',19,0,-19472,75846,72,23,72562,10,99,20,0,60,0,7,1350);
INSERT INTO `stats` VALUES (842,'VULCAN','2009-11-24 20:00:00',18,0,-18813,75192,74,43,71908,10,45,20,1,60,0,9,1347);
INSERT INTO `stats` VALUES (843,'VULCAN','2009-11-25 20:00:00',19,0,-18055,81476,79,38,71162,11,78,20,0,60,0,10,1331);
INSERT INTO `stats` VALUES (844,'VULCAN','2009-11-26 20:00:00',18,1,-23203,83833,80,47,73413,11,223,21,0,62,0,10,1337);
INSERT INTO `stats` VALUES (845,'VULCAN','2009-11-27 20:00:00',17,0,-22632,83268,79,11,72749,10,125,20,1,62,0,10,1339);
INSERT INTO `stats` VALUES (846,'VULCAN','2009-11-28 20:00:00',16,0,-25336,85968,88,24,72618,9,263,19,1,62,0,8,1324);
INSERT INTO `stats` VALUES (847,'VULCAN','2009-11-29 20:00:00',16,0,-24754,85391,68,3,71943,9,31,19,1,62,0,8,1326);
INSERT INTO `stats` VALUES (848,'VULCAN','2009-11-30 20:00:00',16,0,-24768,85410,68,69,71256,9,23,19,0,63,0,9,1331);
INSERT INTO `stats` VALUES (849,'VULCAN','2009-12-01 20:00:00',16,0,-1024308,1084999,76,57,1070712,9,181,21,1,63,0,9,1344);
INSERT INTO `stats` VALUES (850,'VULCAN','2009-12-02 20:00:00',16,0,14827,46180,152,110,26002,9,98,21,2,62,0,10,1346);
INSERT INTO `stats` VALUES (851,'VULCAN','2009-12-03 20:00:00',17,1,-134456,195463,78,25,175165,8,122,21,1,62,0,10,1336);
INSERT INTO `stats` VALUES (852,'VULCAN','2009-12-04 20:00:00',17,0,-272374,333381,73,21,312963,9,225,21,0,62,0,9,1343);
INSERT INTO `stats` VALUES (853,'VULCAN','2009-12-05 20:00:00',17,0,-273834,334841,72,33,318024,9,11,21,0,62,0,10,1325);
INSERT INTO `stats` VALUES (854,'VULCAN','2009-12-06 20:00:00',18,1,-277384,338391,73,11,321516,9,42,21,1,62,0,11,1355);
INSERT INTO `stats` VALUES (855,'VULCAN','2009-12-07 20:00:00',17,0,-279067,340074,69,3,323097,9,130,21,1,62,0,7,1346);
INSERT INTO `stats` VALUES (856,'VULCAN','2009-12-08 20:00:00',17,1,-280519,341526,69,6,324449,9,12,21,0,62,0,9,1347);
INSERT INTO `stats` VALUES (857,'VULCAN','2009-12-09 20:00:00',17,0,-281932,342939,67,12,325816,9,12,21,1,62,0,8,1351);
INSERT INTO `stats` VALUES (858,'VULCAN','2009-12-10 20:00:00',17,0,-283369,344376,69,32,327208,9,36,21,1,62,0,8,1346);
INSERT INTO `stats` VALUES (859,'VULCAN','2009-12-11 20:00:00',20,3,-284593,345600,339,41,328380,10,0,21,0,81,0,11,1349);
INSERT INTO `stats` VALUES (860,'VULCAN','2009-12-12 20:00:00',22,2,-285548,346555,129,33,329312,10,0,21,0,84,0,12,1251);
INSERT INTO `stats` VALUES (861,'VULCAN','2009-12-13 20:00:00',22,0,-977404,1038411,78,24,1020948,10,0,19,3,85,0,12,1198);
INSERT INTO `stats` VALUES (862,'VULCAN','2009-12-14 20:00:00',22,0,-981022,1042029,84,15,1024507,10,0,19,0,86,0,9,1204);
INSERT INTO `stats` VALUES (863,'VULCAN','2009-12-15 20:00:00',24,3,19592,41415,285,53,23893,11,1,19,2,100,0,12,1237);
INSERT INTO `stats` VALUES (864,'VULCAN','2009-12-16 20:00:00',25,1,20389,40618,72,5,23024,10,12,20,1,100,0,14,1255);
INSERT INTO `stats` VALUES (865,'VULCAN','2009-12-17 20:00:00',24,0,21052,39932,70,12,22270,10,11,20,0,100,0,9,1260);
INSERT INTO `stats` VALUES (866,'VULCAN','2009-12-18 20:00:00',23,0,21732,39252,61,12,21531,10,0,20,1,100,0,8,1236);
INSERT INTO `stats` VALUES (867,'VULCAN','2009-12-19 20:00:00',24,0,35463,26389,64,15,8668,10,0,20,0,100,0,10,1238);
INSERT INTO `stats` VALUES (868,'VULCAN','2009-12-20 20:00:00',23,0,35892,26653,59,2,8932,10,31,20,0,100,0,6,1239);
INSERT INTO `stats` VALUES (869,'VULCAN','2009-12-21 20:00:00',23,1,37501,25911,62,0,8570,10,23,19,2,100,0,10,1237);
INSERT INTO `stats` VALUES (870,'VULCAN','2009-12-22 20:00:00',22,0,38171,26189,62,0,8848,10,0,19,2,100,0,8,1239);
INSERT INTO `stats` VALUES (871,'VULCAN','2009-12-23 20:00:00',21,0,38658,26468,55,0,9127,10,0,19,1,100,0,7,1234);
INSERT INTO `stats` VALUES (872,'VULCAN','2009-12-24 20:00:00',21,0,39211,26713,54,1,9372,10,10,19,1,100,0,7,1253);
INSERT INTO `stats` VALUES (873,'VULCAN','2009-12-25 20:00:00',21,1,39504,26986,56,2,9645,10,14,19,1,100,0,6,1244);
INSERT INTO `stats` VALUES (874,'VULCAN','2009-12-26 20:00:00',20,1,28966,38264,31,0,20969,9,0,19,2,100,0,10,1238);
INSERT INTO `stats` VALUES (875,'VULCAN','2009-12-27 20:00:00',21,1,29621,38476,61,5,21181,9,0,20,0,100,0,10,1235);
INSERT INTO `stats` VALUES (876,'VULCAN','2009-12-28 20:00:00',22,1,30286,38701,63,2,21406,9,11,20,0,100,0,11,1245);
INSERT INTO `stats` VALUES (877,'VULCAN','2009-12-29 20:00:00',21,0,30794,39082,63,8,21787,9,0,20,3,100,0,9,1245);
INSERT INTO `stats` VALUES (878,'VULCAN','2009-12-30 20:00:00',21,0,31288,39312,59,0,22017,9,0,20,2,100,0,8,1197);
INSERT INTO `stats` VALUES (879,'VULCAN','2009-12-31 20:00:00',22,2,31955,39558,63,0,22263,9,0,20,1,100,0,12,1202);
INSERT INTO `stats` VALUES (880,'VULCAN','2010-01-01 20:00:00',22,0,32331,39778,59,1,22483,9,0,20,0,100,0,6,1187);
INSERT INTO `stats` VALUES (881,'VULCAN','2010-01-02 20:00:00',22,0,32706,39999,52,0,22704,9,0,20,0,100,0,6,1189);
INSERT INTO `stats` VALUES (882,'VULCAN','2010-01-03 20:00:00',22,0,33065,40236,54,0,22941,9,10,21,0,100,0,6,1187);
INSERT INTO `stats` VALUES (883,'VULCAN','2010-01-04 20:00:00',22,0,33408,40466,56,2,23171,9,3,21,0,100,0,5,1193);
INSERT INTO `stats` VALUES (884,'VULCAN','2010-01-05 20:00:00',20,0,30694,43626,56,0,26331,8,3849,18,2,100,0,5,1184);
INSERT INTO `stats` VALUES (885,'VULCAN','2010-01-06 20:00:00',19,0,30980,43868,52,0,26573,8,0,17,3,100,0,5,1181);
INSERT INTO `stats` VALUES (886,'VULCAN','2010-01-07 20:00:00',19,1,31306,44093,50,1,26798,8,2,17,1,100,0,6,1179);
INSERT INTO `stats` VALUES (887,'VULCAN','2010-01-08 20:00:00',21,0,26824,49355,59,5,32060,9,55,17,0,100,0,9,1180);
INSERT INTO `stats` VALUES (888,'VULCAN','2010-01-09 20:00:00',21,0,27349,49569,58,5,32274,9,4,17,0,100,0,8,1179);
INSERT INTO `stats` VALUES (889,'VULCAN','2010-01-10 20:00:00',14,0,12103,57599,52,12,40321,7,31,12,3,75,0,5,1083);
INSERT INTO `stats` VALUES (890,'VULCAN','2010-01-11 20:00:00',13,0,10041,51701,38,6,51081,6,0,12,0,75,0,5,1093);
INSERT INTO `stats` VALUES (891,'VULCAN','2010-01-12 20:00:00',11,0,5208,54163,27,3,53558,5,0,7,2,71,0,2,1096);
INSERT INTO `stats` VALUES (892,'VULCAN','2010-01-13 20:00:00',12,1,-251,646,28,3,56,4,11,6,0,48,0,5,1096);
INSERT INTO `stats` VALUES (893,'VULCAN','2010-01-14 20:00:00',11,0,4604,692,24,8,102,3,0,6,0,48,0,2,1142);
INSERT INTO `stats` VALUES (894,'Hispania','2009-07-16 20:00:00',7,2,0,10000,118,31,10000,4,11,0,0,14,0,7,265);
INSERT INTO `stats` VALUES (895,'Hispania','2009-07-17 20:00:00',9,2,0,10000,2,9,10000,4,0,0,0,14,0,9,268);
INSERT INTO `stats` VALUES (896,'Hispania','2009-07-18 20:00:00',10,2,0,10000,0,0,10000,3,0,0,0,14,0,5,263);
INSERT INTO `stats` VALUES (897,'Hispania','2009-07-19 20:00:00',12,1,0,10000,0,4,10000,3,0,0,2,14,0,6,230);
INSERT INTO `stats` VALUES (898,'Hispania','2009-07-20 20:00:00',13,0,0,10000,0,0,10000,3,0,0,2,14,0,6,217);
INSERT INTO `stats` VALUES (899,'Hispania','2009-07-21 20:00:00',15,1,0,10000,2,0,10000,4,0,0,1,14,0,7,218);
INSERT INTO `stats` VALUES (900,'Hispania','2009-07-22 20:00:00',18,2,0,500000,0,1,500000,6,0,0,1,14,0,9,223);
INSERT INTO `stats` VALUES (901,'Hispania','2009-07-23 20:00:00',21,3,0,500000,0,17,500000,8,0,0,0,14,0,12,197);
INSERT INTO `stats` VALUES (902,'Hispania','2009-07-24 20:00:00',24,2,0,500000,0,12,500000,9,0,0,0,14,0,16,187);
INSERT INTO `stats` VALUES (903,'Hispania','2009-07-25 20:00:00',25,1,0,500000,0,3,500000,9,0,0,1,14,0,15,189);
INSERT INTO `stats` VALUES (904,'Hispania','2009-07-26 20:00:00',27,1,0,500000,0,3,500000,9,0,0,1,14,0,17,185);
INSERT INTO `stats` VALUES (905,'Hispania','2009-07-27 20:00:00',24,1,6496,493504,15,8,493504,8,0,1,7,0,0,14,193);
INSERT INTO `stats` VALUES (906,'Hispania','2009-07-28 20:00:00',24,1,6945,493055,23,30,493055,10,21,8,1,1,0,15,196);
INSERT INTO `stats` VALUES (907,'Hispania','2009-07-29 20:00:00',23,0,6581,493419,11,12,493419,10,11,9,1,2,0,14,205);
INSERT INTO `stats` VALUES (908,'Hispania','2009-07-30 20:00:00',22,1,6208,493792,7,16,493642,11,12,12,1,2,0,18,214);
INSERT INTO `stats` VALUES (909,'Hispania','2009-07-31 20:00:00',23,1,6656,493344,12,34,493194,11,51,12,0,2,0,13,260);
INSERT INTO `stats` VALUES (910,'Hispania','2009-08-01 20:00:00',24,0,6800,493200,11,1,493050,11,1,12,0,2,0,10,247);
INSERT INTO `stats` VALUES (911,'Hispania','2009-08-02 20:00:00',23,0,7138,492862,9,8,492712,11,0,12,0,2,0,9,246);
INSERT INTO `stats` VALUES (912,'Hispania','2009-08-03 20:00:00',23,1,9563,490437,17,3,490287,11,100,12,1,2,0,10,244);
INSERT INTO `stats` VALUES (913,'Hispania','2009-08-04 20:00:00',24,2,10808,489192,28,11,488278,10,11,13,5,2,0,12,239);
INSERT INTO `stats` VALUES (914,'Hispania','2009-08-05 20:00:00',22,0,11336,488664,25,9,487000,10,22,13,3,2,0,6,527);
INSERT INTO `stats` VALUES (915,'Hispania','2009-08-06 20:00:00',24,0,14059,485941,22,5,484167,11,17,13,5,7,0,11,609);
INSERT INTO `stats` VALUES (916,'Hispania','2009-08-07 20:00:00',26,2,16794,483206,31,7,481358,10,41,13,3,13,0,11,661);
INSERT INTO `stats` VALUES (917,'Hispania','2009-08-08 20:00:00',26,1,19833,480167,37,52,478202,10,44,14,0,16,0,15,716);
INSERT INTO `stats` VALUES (918,'Hispania','2009-08-09 20:00:00',24,0,20377,479623,31,8,477572,9,19,14,2,16,0,7,715);
INSERT INTO `stats` VALUES (919,'Hispania','2009-08-10 20:00:00',24,1,23795,13299,26,91,11148,9,51,14,2,17,0,9,733);
INSERT INTO `stats` VALUES (920,'Hispania','2009-08-11 20:00:00',25,0,22050,15043,27,35,14903,9,12,14,0,17,0,11,736);
INSERT INTO `stats` VALUES (921,'Hispania','2009-08-12 20:00:00',26,1,10051,27042,13,41,26902,9,1,14,1,17,0,9,740);
INSERT INTO `stats` VALUES (922,'Hispania','2009-08-13 20:00:00',23,0,6778,30315,22,43,30312,8,0,12,2,16,0,7,725);
INSERT INTO `stats` VALUES (923,'Hispania','2009-08-14 20:00:00',21,0,11,37082,27,25,37079,8,0,12,2,16,0,6,733);
INSERT INTO `stats` VALUES (924,'Hispania','2009-08-15 20:00:00',21,0,-3,37096,19,62,37093,8,14,12,2,16,0,6,724);
INSERT INTO `stats` VALUES (925,'Hispania','2009-08-16 20:00:00',25,4,10797,37096,5,38,37093,9,2,13,0,16,0,10,729);
INSERT INTO `stats` VALUES (926,'Hispania','2009-08-17 20:00:00',24,0,10797,37096,1,37,37093,9,0,14,1,16,0,8,741);
INSERT INTO `stats` VALUES (927,'Hispania','2009-08-18 20:00:00',20,0,10800,37093,0,8,37093,6,0,10,4,15,0,8,748);
INSERT INTO `stats` VALUES (928,'Hispania','2009-08-19 20:00:00',22,2,10800,37093,0,3,37093,7,0,10,0,15,0,10,797);
INSERT INTO `stats` VALUES (929,'Hispania','2009-08-20 20:00:00',22,1,10826,37094,14,12,37094,8,900,12,2,15,0,11,806);
INSERT INTO `stats` VALUES (930,'Hispania','2009-08-21 20:00:00',21,0,10862,37094,9,26,37094,7,12700,15,0,15,0,8,829);
INSERT INTO `stats` VALUES (931,'Hispania','2009-08-22 20:00:00',21,1,10862,37104,5,19,37104,7,10,15,2,15,0,10,828);
INSERT INTO `stats` VALUES (932,'Hispania','2009-08-23 20:00:00',21,0,10863,37114,9,50,37114,7,910,15,2,15,0,8,838);
INSERT INTO `stats` VALUES (933,'Hispania','2009-08-24 20:00:00',21,0,10861,37115,5,183,37115,7,1,20,0,15,0,9,856);
INSERT INTO `stats` VALUES (934,'Hispania','2009-08-25 20:00:00',21,0,859,51076,6,160,37116,8,1,21,0,15,0,10,908);
INSERT INTO `stats` VALUES (935,'Hispania','2009-08-26 20:00:00',20,0,842,51093,3,43,37133,8,17,22,2,15,0,9,918);
INSERT INTO `stats` VALUES (936,'Hispania','2009-08-27 20:00:00',19,0,839,51096,3,66,37136,8,3,22,0,15,0,7,938);
INSERT INTO `stats` VALUES (937,'Hispania','2009-08-28 20:00:00',17,0,923,47137,4,3,37137,6,1,16,2,13,0,9,942);
INSERT INTO `stats` VALUES (938,'Hispania','2009-08-29 20:00:00',18,1,1206,47438,7,0,37138,6,1,18,2,13,0,6,950);
INSERT INTO `stats` VALUES (939,'Hispania','2009-08-30 20:00:00',16,0,1186,47458,3,5,37158,4,1,16,1,9,0,6,956);
INSERT INTO `stats` VALUES (940,'Hispania','2009-08-31 20:00:00',16,1,1158,47459,3,2,37159,4,1,16,3,9,0,8,941);
INSERT INTO `stats` VALUES (941,'Hispania','2009-09-01 20:00:00',22,7,1136,47848,6,1,37181,4,0,16,1,9,0,13,1037);
INSERT INTO `stats` VALUES (942,'Hispania','2009-09-02 20:00:00',21,1,944,47876,2,2,37209,5,0,15,0,9,0,8,1118);
INSERT INTO `stats` VALUES (943,'Hispania','2009-09-03 20:00:00',22,0,1097,47909,2,2,37209,5,0,16,2,9,0,8,1132);
INSERT INTO `stats` VALUES (944,'Hispania','2009-09-04 20:00:00',23,0,11076,37921,277,12,37221,4,11,16,2,9,0,11,1119);
INSERT INTO `stats` VALUES (945,'Hispania','2009-09-05 20:00:00',23,0,11075,37922,4,8,37222,4,1,16,1,9,0,7,1121);
INSERT INTO `stats` VALUES (946,'Hispania','2009-09-06 20:00:00',22,0,11083,37923,4,6,37223,3,1,16,0,9,0,7,1122);
INSERT INTO `stats` VALUES (947,'Hispania','2009-09-07 20:00:00',25,2,878,48128,8,35,37228,5,5,17,2,9,0,13,1126);
INSERT INTO `stats` VALUES (948,'Hispania','2009-09-08 20:00:00',24,0,800,48441,7,48,37241,5,1,15,0,9,0,11,1136);
INSERT INTO `stats` VALUES (949,'Hispania','2009-09-09 20:00:00',25,0,1295,48387,18,39,37187,5,3,15,0,4,0,9,1149);
INSERT INTO `stats` VALUES (950,'Hispania','2009-09-10 20:00:00',24,0,1359,48323,17,15,37123,5,4,15,0,4,0,10,1149);
INSERT INTO `stats` VALUES (951,'Hispania','2009-09-11 20:00:00',20,0,1424,48258,21,38,37058,5,2,25,5,4,0,10,1168);
INSERT INTO `stats` VALUES (952,'Hispania','2009-09-12 20:00:00',19,0,1491,48191,16,9,36991,5,3,25,1,4,0,9,1180);
INSERT INTO `stats` VALUES (953,'Hispania','2009-09-13 20:00:00',18,0,1551,48131,14,2,36931,5,0,24,2,4,0,8,1183);
INSERT INTO `stats` VALUES (954,'Hispania','2009-09-14 20:00:00',17,1,607,49075,19,7,36875,5,2,24,2,4,0,9,1189);
INSERT INTO `stats` VALUES (955,'Hispania','2009-09-15 20:00:00',17,0,663,49019,15,12,36819,5,3,24,1,4,0,9,1206);
INSERT INTO `stats` VALUES (956,'Hispania','2009-09-16 20:00:00',18,1,717,48967,24,17,36767,5,1,24,0,4,0,10,1216);
INSERT INTO `stats` VALUES (957,'Hispania','2009-09-17 20:00:00',18,1,-3232,52916,20,22,40716,5,4,24,2,4,0,10,1231);
INSERT INTO `stats` VALUES (958,'Hispania','2009-09-18 20:00:00',17,1,-159165,212849,48,8,200649,5,3,24,2,4,0,12,1228);
INSERT INTO `stats` VALUES (959,'Hispania','2009-09-19 20:00:00',19,3,-9320,76824,39,16,75624,4,0,18,0,4,0,15,1231);
INSERT INTO `stats` VALUES (960,'Hispania','2009-09-20 20:00:00',20,1,-9273,76777,14,8,75577,4,1,18,0,4,0,11,1236);
INSERT INTO `stats` VALUES (961,'Hispania','2009-09-21 20:00:00',19,0,762,76742,16,59,75542,4,0,18,0,4,0,9,1227);
INSERT INTO `stats` VALUES (962,'Hispania','2009-09-22 20:00:00',20,0,1074,90868,18,27,89668,4,0,18,1,4,0,8,1236);
INSERT INTO `stats` VALUES (963,'Hispania','2009-09-23 20:00:00',21,1,1279,90633,29,18,89433,4,76,18,0,4,0,9,1250);
INSERT INTO `stats` VALUES (964,'Hispania','2009-09-24 20:00:00',22,0,1508,90350,38,71,89150,4,52,18,0,4,0,9,1253);
INSERT INTO `stats` VALUES (965,'Hispania','2009-09-25 20:00:00',21,0,1593,90245,32,61,88945,5,12,18,2,4,0,7,1239);
INSERT INTO `stats` VALUES (966,'Hispania','2009-09-26 20:00:00',21,1,1837,90001,18,9,88701,5,1,18,1,4,0,7,1236);
INSERT INTO `stats` VALUES (967,'Hispania','2009-09-27 20:00:00',23,2,1831,89976,22,8,88376,5,15,18,0,4,0,10,1236);
INSERT INTO `stats` VALUES (968,'Hispania','2009-09-28 20:00:00',22,0,1441,90109,31,27,88309,5,160,19,0,4,0,10,1285);
INSERT INTO `stats` VALUES (969,'Hispania','2009-09-29 20:00:00',20,0,1654,89932,21,24,88132,5,2,19,2,4,0,7,1277);
INSERT INTO `stats` VALUES (970,'Hispania','2009-09-30 20:00:00',18,0,1893,89693,17,12,87893,4,2,19,2,4,0,7,1273);
INSERT INTO `stats` VALUES (971,'Hispania','2009-10-01 20:00:00',21,1,2650,89516,23,19,87716,4,53,19,5,4,0,10,1268);
INSERT INTO `stats` VALUES (972,'Hispania','2009-10-02 20:00:00',20,0,3515,88853,29,10,87053,4,13,19,4,4,0,9,1256);
INSERT INTO `stats` VALUES (973,'Hispania','2009-10-03 20:00:00',18,0,14223,88193,31,6,86393,3,89,20,2,4,0,7,1250);
INSERT INTO `stats` VALUES (974,'Hispania','2009-10-04 20:00:00',17,1,14455,83911,29,8,81811,3,141,20,3,4,0,7,1247);
INSERT INTO `stats` VALUES (975,'Hispania','2009-10-05 20:00:00',18,0,14980,83525,28,46,81125,4,32,20,1,4,0,8,1244);
INSERT INTO `stats` VALUES (976,'Hispania','2009-10-06 20:00:00',19,2,15004,83343,35,31,80493,4,41,20,5,4,0,9,1247);
INSERT INTO `stats` VALUES (977,'Hispania','2009-10-07 20:00:00',22,2,11574,90737,31,32,87187,5,51,22,1,4,0,12,1234);
INSERT INTO `stats` VALUES (978,'Hispania','2009-10-08 20:00:00',22,1,782,89407,42,61,85957,4,34,21,8,4,0,11,1237);
INSERT INTO `stats` VALUES (979,'Hispania','2009-10-09 20:00:00',22,0,1763,89129,36,17,84179,4,12,21,1,4,0,12,1240);
INSERT INTO `stats` VALUES (980,'Hispania','2009-10-10 20:00:00',23,0,3305,87590,31,15,82240,4,11,21,0,4,0,12,1242);
INSERT INTO `stats` VALUES (981,'Hispania','2009-10-11 20:00:00',23,1,4880,86015,30,5,80365,5,0,22,1,4,0,11,1256);
INSERT INTO `stats` VALUES (982,'Hispania','2009-10-12 20:00:00',23,0,5595,85300,34,3,78310,5,15,22,0,4,0,12,1264);
INSERT INTO `stats` VALUES (983,'Hispania','2009-10-13 20:00:00',23,0,6700,84462,38,8,76172,4,10,22,0,4,0,13,1265);
INSERT INTO `stats` VALUES (984,'Hispania','2009-10-14 20:00:00',22,0,5844,86661,49,29,75747,5,1330,24,1,4,0,11,1271);
INSERT INTO `stats` VALUES (985,'Hispania','2009-10-15 20:00:00',22,2,5689,86816,42,28,75477,5,70,31,1,4,0,11,1274);
INSERT INTO `stats` VALUES (986,'Hispania','2009-10-16 20:00:00',20,0,5987,86518,31,20,75115,5,38,34,1,4,0,10,1274);
INSERT INTO `stats` VALUES (987,'Hispania','2009-10-17 20:00:00',18,0,6003,85200,32,22,74787,5,22,34,1,4,0,9,1272);
INSERT INTO `stats` VALUES (988,'Hispania','2009-10-18 20:00:00',18,0,6245,86105,24,1,75592,5,18,35,2,4,0,8,1255);
INSERT INTO `stats` VALUES (989,'Hispania','2009-10-19 20:00:00',17,0,6560,86185,26,14,75472,5,55,35,4,4,0,8,1260);
INSERT INTO `stats` VALUES (990,'Hispania','2009-10-20 20:00:00',18,1,6585,81090,27,13,70277,5,310,35,0,4,0,10,1256);
INSERT INTO `stats` VALUES (991,'Hispania','2009-10-21 20:00:00',20,1,8018,80867,28,3,69844,5,22,35,0,4,0,12,1258);
INSERT INTO `stats` VALUES (992,'Hispania','2009-10-22 20:00:00',20,0,8382,80503,27,3,69370,5,1,35,0,4,0,10,1260);
INSERT INTO `stats` VALUES (993,'Hispania','2009-10-23 20:00:00',21,1,8602,80283,27,11,68850,5,12,35,0,4,0,12,1264);
INSERT INTO `stats` VALUES (994,'Hispania','2009-10-24 20:00:00',23,2,9005,78872,32,16,68469,5,51,35,0,4,0,10,1281);
INSERT INTO `stats` VALUES (995,'Hispania','2009-10-25 20:00:00',26,3,8767,79561,36,3,68008,5,11,35,1,4,0,11,1283);
INSERT INTO `stats` VALUES (996,'Hispania','2009-10-26 20:00:00',31,5,9168,79160,35,2,67567,5,11,35,0,4,0,14,1290);
INSERT INTO `stats` VALUES (997,'Hispania','2009-10-27 20:00:00',30,0,9499,78829,27,7,67236,5,11,31,1,4,0,8,1289);
INSERT INTO `stats` VALUES (998,'Hispania','2009-10-28 20:00:00',31,2,9853,79055,34,6,67031,5,260,30,1,4,0,11,1284);
INSERT INTO `stats` VALUES (999,'Hispania','2009-10-29 20:00:00',31,0,9903,78755,34,2,66641,4,32,21,0,4,0,10,1290);
INSERT INTO `stats` VALUES (1000,'Hispania','2009-10-30 20:00:00',32,1,10173,78485,27,13,66281,4,12,21,0,4,0,10,1303);
INSERT INTO `stats` VALUES (1001,'Hispania','2009-10-31 20:00:00',32,1,12155,78654,36,12,65916,4,32,21,2,4,0,12,1304);
INSERT INTO `stats` VALUES (1002,'Hispania','2009-11-01 20:00:00',34,2,11694,79115,36,5,65581,4,102,21,0,4,0,12,1304);
INSERT INTO `stats` VALUES (1003,'Hispania','2009-11-02 20:00:00',34,1,12171,78598,28,7,65064,3,0,21,0,4,0,13,1309);
INSERT INTO `stats` VALUES (1004,'Hispania','2009-11-03 20:00:00',35,2,12945,77373,29,6,64635,3,58,21,1,4,0,11,1310);
INSERT INTO `stats` VALUES (1005,'Hispania','2009-11-04 20:00:00',33,1,12160,78158,34,14,64170,3,2,21,3,4,0,11,1308);
INSERT INTO `stats` VALUES (1006,'Hispania','2009-11-05 20:00:00',29,1,12436,77882,32,3,63894,3,11,21,5,4,0,9,1307);
INSERT INTO `stats` VALUES (1007,'Hispania','2009-11-06 20:00:00',31,1,13581,77369,34,14,63381,3,14,21,0,4,0,13,1312);
INSERT INTO `stats` VALUES (1008,'Hispania','2009-11-07 20:00:00',31,0,14050,77850,38,5,62876,3,2,21,0,4,0,12,1314);
INSERT INTO `stats` VALUES (1009,'Hispania','2009-11-08 20:00:00',30,0,14023,77877,31,1,62453,3,64,21,2,4,0,12,1310);
INSERT INTO `stats` VALUES (1010,'Hispania','2009-11-09 20:00:00',30,1,14466,77434,32,1,62010,3,24,21,1,4,0,12,1310);
INSERT INTO `stats` VALUES (1011,'Hispania','2009-11-10 20:00:00',27,0,12713,78519,28,6,63095,3,0,21,2,4,0,9,1311);
INSERT INTO `stats` VALUES (1012,'Hispania','2009-11-11 20:00:00',27,2,12388,78244,19,2,62820,3,0,21,2,4,0,7,1319);
INSERT INTO `stats` VALUES (1013,'Hispania','2009-11-12 20:00:00',25,0,12462,78170,20,1,62746,3,1,21,2,4,0,8,1322);
INSERT INTO `stats` VALUES (1014,'Hispania','2009-11-13 20:00:00',25,0,16965,77766,24,21,62342,5,1,21,1,4,0,9,1333);
INSERT INTO `stats` VALUES (1015,'Hispania','2009-11-14 20:00:00',25,1,17369,77362,23,1,61938,5,1,21,1,4,0,10,1338);
INSERT INTO `stats` VALUES (1016,'Hispania','2009-11-15 20:00:00',24,0,17791,76940,22,14,61674,5,11,21,1,4,0,6,1338);
INSERT INTO `stats` VALUES (1017,'Hispania','2009-11-16 20:00:00',28,5,18136,76595,23,21,61329,6,0,21,1,4,0,12,1358);
INSERT INTO `stats` VALUES (1018,'Hispania','2009-11-17 20:00:00',28,0,17905,76826,25,10,61060,6,56,21,0,4,0,8,1358);
INSERT INTO `stats` VALUES (1019,'Hispania','2009-11-18 20:00:00',29,1,18197,76534,25,5,60768,6,33,22,0,4,0,10,1357);
INSERT INTO `stats` VALUES (1020,'Hispania','2009-11-19 20:00:00',28,0,18522,76209,22,12,60443,6,0,22,1,4,0,8,1333);
INSERT INTO `stats` VALUES (1021,'Hispania','2009-11-20 20:00:00',29,1,18845,75886,19,8,60120,6,12,22,0,4,0,8,1343);
INSERT INTO `stats` VALUES (1022,'Hispania','2009-11-21 20:00:00',27,0,20621,74110,26,92,59766,6,11,23,2,4,0,8,1338);
INSERT INTO `stats` VALUES (1023,'Hispania','2009-11-22 20:00:00',26,0,21125,73606,27,30,59262,7,100,22,1,4,0,9,1336);
INSERT INTO `stats` VALUES (1024,'Hispania','2009-11-23 20:00:00',27,1,19829,74902,28,19,58558,8,21,23,0,4,0,9,1350);
INSERT INTO `stats` VALUES (1025,'Hispania','2009-11-24 20:00:00',25,0,24458,74475,34,14,57901,7,75,25,2,4,0,10,1347);
INSERT INTO `stats` VALUES (1026,'Hispania','2009-11-25 20:00:00',26,0,20812,73919,43,10,57345,7,105,26,1,5,0,10,1339);
INSERT INTO `stats` VALUES (1027,'Hispania','2009-11-26 20:00:00',24,2,19992,74739,596,81,58663,8,66,28,4,40,0,11,1337);
INSERT INTO `stats` VALUES (1028,'Hispania','2009-11-27 20:00:00',25,1,18619,76111,154,29,58450,9,160,30,0,46,1,10,1339);
INSERT INTO `stats` VALUES (1029,'Hispania','2009-11-28 20:00:00',24,0,18588,77496,124,13,58434,9,95,30,1,49,2,12,1324);
INSERT INTO `stats` VALUES (1030,'Hispania','2009-11-29 20:00:00',24,0,18616,77468,68,3,58346,9,117,30,0,51,2,9,1326);
INSERT INTO `stats` VALUES (1031,'Hispania','2009-11-30 20:00:00',25,1,19339,76745,36,23,57548,9,40,30,0,51,2,11,1331);
INSERT INTO `stats` VALUES (1032,'Hispania','2009-12-01 20:00:00',29,2,20297,75766,45,20,56289,9,61,30,0,51,2,15,1344);
INSERT INTO `stats` VALUES (1033,'Hispania','2009-12-02 20:00:00',28,0,20844,75024,45,45,55547,8,66,30,1,51,2,13,1354);
INSERT INTO `stats` VALUES (1034,'Hispania','2009-12-03 20:00:00',29,2,21623,74245,40,4,54708,8,32,31,1,51,2,14,1338);
INSERT INTO `stats` VALUES (1035,'Hispania','2009-12-04 20:00:00',31,2,23176,72692,49,26,52795,8,103,31,0,51,2,14,1343);
INSERT INTO `stats` VALUES (1036,'Hispania','2009-12-05 20:00:00',31,0,23573,72745,69,8,52579,8,50,31,0,53,2,13,1325);
INSERT INTO `stats` VALUES (1037,'Hispania','2009-12-06 20:00:00',30,0,24117,72201,145,11,51585,8,96,31,1,57,3,11,1360);
INSERT INTO `stats` VALUES (1038,'Hispania','2009-12-07 20:00:00',31,1,20589,75729,78,4,51863,8,101,32,1,59,3,11,1354);
INSERT INTO `stats` VALUES (1039,'Hispania','2009-12-08 20:00:00',29,0,20515,75803,57,0,51937,8,71,32,0,59,3,8,1347);
INSERT INTO `stats` VALUES (1040,'Hispania','2009-12-09 20:00:00',29,0,20228,76090,134,2,52054,8,101,32,0,63,3,11,1351);
INSERT INTO `stats` VALUES (1041,'Hispania','2009-12-10 20:00:00',29,1,19126,77192,71,9,52376,8,141,32,1,79,2,12,1346);
INSERT INTO `stats` VALUES (1042,'Hispania','2009-12-11 20:00:00',28,1,17373,78945,77,32,55994,8,130,30,2,81,2,12,1349);
INSERT INTO `stats` VALUES (1043,'Hispania','2009-12-12 20:00:00',28,0,23862,71223,86,15,52242,8,111,30,3,86,2,16,1251);
INSERT INTO `stats` VALUES (1044,'Hispania','2009-12-13 20:00:00',28,1,23385,71700,102,18,52464,8,622,32,3,100,0,12,1199);
INSERT INTO `stats` VALUES (1045,'Hispania','2009-12-14 20:00:00',31,3,20988,74097,102,32,47966,8,156,33,1,100,0,14,1203);
INSERT INTO `stats` VALUES (1046,'Hispania','2009-12-15 20:00:00',36,4,22226,72859,113,45,45063,8,391,35,0,100,0,19,1237);
INSERT INTO `stats` VALUES (1047,'Hispania','2009-12-16 20:00:00',36,1,22244,72841,82,13,43345,8,110,35,1,100,0,17,1259);
INSERT INTO `stats` VALUES (1048,'Hispania','2009-12-17 20:00:00',41,5,19287,75821,98,11,45488,8,123,30,1,69,0,23,1251);
INSERT INTO `stats` VALUES (1049,'Hispania','2009-12-18 20:00:00',41,0,19557,75551,134,32,45646,8,90,31,0,48,0,10,1236);
INSERT INTO `stats` VALUES (1050,'Hispania','2009-12-19 20:00:00',40,0,9642,88248,97,103,58034,9,120,32,0,1,0,16,1239);
INSERT INTO `stats` VALUES (1051,'Hispania','2009-12-20 20:00:00',40,1,8354,90206,79,19,59378,9,230,32,1,1,0,15,1237);
INSERT INTO `stats` VALUES (1052,'Hispania','2009-12-21 20:00:00',42,2,8921,90434,65,8,59796,9,15,32,0,1,1,18,1241);
INSERT INTO `stats` VALUES (1053,'Hispania','2009-12-22 20:00:00',42,1,9265,90935,77,18,59740,9,70,31,2,1,1,19,1240);
INSERT INTO `stats` VALUES (1054,'Hispania','2009-12-23 20:00:00',41,0,9511,91409,71,14,59754,9,78,31,1,1,1,17,1238);
INSERT INTO `stats` VALUES (1055,'Hispania','2009-12-24 20:00:00',42,1,10028,91562,64,3,60107,8,42,31,0,1,1,15,1253);
INSERT INTO `stats` VALUES (1056,'Hispania','2009-12-25 20:00:00',39,0,9359,92586,52,1,60831,8,21,31,4,1,1,8,1244);
INSERT INTO `stats` VALUES (1057,'Hispania','2009-12-26 20:00:00',39,1,9409,93206,29,15,61221,8,31,31,1,1,1,15,1238);
INSERT INTO `stats` VALUES (1058,'Hispania','2009-12-27 20:00:00',37,2,9378,93862,62,6,61730,8,1,31,4,1,1,15,1235);
INSERT INTO `stats` VALUES (1059,'Hispania','2009-12-28 20:00:00',38,1,7260,97230,91,29,62650,7,146,34,1,1,1,16,1246);
INSERT INTO `stats` VALUES (1060,'Hispania','2009-12-29 20:00:00',38,1,7695,97625,69,14,60370,7,51,34,3,1,1,15,1247);
INSERT INTO `stats` VALUES (1061,'Hispania','2009-12-30 20:00:00',39,1,7297,98828,71,5,61388,7,60,36,0,2,1,14,1197);
INSERT INTO `stats` VALUES (1062,'Hispania','2009-12-31 20:00:00',37,0,7674,99181,69,4,62056,7,81,36,2,2,1,12,1202);
INSERT INTO `stats` VALUES (1063,'Hispania','2010-01-01 20:00:00',34,1,6918,100162,48,0,63102,7,11,36,4,1,0,6,1188);
INSERT INTO `stats` VALUES (1064,'Hispania','2010-01-02 20:00:00',34,0,7746,100064,47,1,63567,7,52,36,0,1,0,13,1189);
INSERT INTO `stats` VALUES (1065,'Hispania','2010-01-03 20:00:00',34,0,7879,100636,43,1,64031,7,51,36,0,1,0,12,1187);
INSERT INTO `stats` VALUES (1066,'Hispania','2010-01-04 20:00:00',34,0,8184,101056,55,8,64487,7,21,36,0,1,0,13,1193);
INSERT INTO `stats` VALUES (1067,'Hispania','2010-01-05 20:00:00',34,1,8804,101216,43,4,64584,7,0,36,2,1,0,14,1184);
INSERT INTO `stats` VALUES (1068,'Hispania','2010-01-06 20:00:00',33,1,8683,101877,30,0,65245,7,0,36,2,1,0,10,1184);
INSERT INTO `stats` VALUES (1069,'Hispania','2010-01-07 20:00:00',34,2,6822,104568,62,10,65483,7,52,36,1,1,0,16,1179);
INSERT INTO `stats` VALUES (1070,'Hispania','2010-01-08 20:00:00',33,0,7121,104824,36,23,65649,7,80,36,1,1,0,12,1180);
INSERT INTO `stats` VALUES (1071,'Hispania','2010-01-09 20:00:00',31,0,7698,104872,30,1,65827,7,0,35,2,1,0,14,1087);
INSERT INTO `stats` VALUES (1072,'Hispania','2010-01-10 20:00:00',31,1,7809,105336,30,3,66291,6,32,34,1,1,0,14,1087);
INSERT INTO `stats` VALUES (1073,'Hispania','2010-01-11 20:00:00',33,1,18870,105970,59,55,67956,6,81,32,0,1,0,17,1093);
INSERT INTO `stats` VALUES (1074,'Hispania','2010-01-12 20:00:00',37,0,20110,116983,86,48,68953,7,98,32,0,1,0,18,1096);
INSERT INTO `stats` VALUES (1075,'Hispania','2010-01-13 20:00:00',39,1,27442,170789,107,30,113326,8,33,33,0,48,0,20,1096);
INSERT INTO `stats` VALUES (1076,'Hispania','2010-01-14 20:00:00',43,4,28113,166385,114,77,109421,8,53,33,0,54,0,22,1142);
INSERT INTO `stats` VALUES (1077,'Hispania','2010-01-15 20:00:00',44,6,24512,168102,142,46,110937,9,202,34,4,54,0,22,1148);
INSERT INTO `stats` VALUES (1078,'Hispania','2010-01-16 20:00:00',47,3,31572,163780,145,18,111241,9,80,34,1,54,0,22,1162);
INSERT INTO `stats` VALUES (1079,'Hispania','2010-01-17 20:00:00',48,2,32110,164555,133,13,111860,9,79,34,1,54,0,21,1166);
INSERT INTO `stats` VALUES (1080,'Hispania','2010-01-18 20:00:00',77,29,32833,165233,176,47,110876,8,160,34,1,58,0,46,1274);
INSERT INTO `stats` VALUES (1081,'Hispania','2010-01-19 20:00:00',79,3,28283,171121,182,149,111531,10,176,34,1,58,1,26,1284);
INSERT INTO `stats` VALUES (1082,'Hispania','2010-01-20 20:00:00',79,1,28922,171670,143,37,111970,10,188,34,1,58,1,25,1289);
INSERT INTO `stats` VALUES (1083,'Hispania','2010-01-21 20:00:00',78,0,26880,172817,325,90,113231,10,220,33,4,69,1,24,1284);
INSERT INTO `stats` VALUES (1084,'Hispania','2010-01-22 20:00:00',80,2,26194,174736,239,20,115089,10,150,33,0,76,1,26,1290);
INSERT INTO `stats` VALUES (1085,'Hispania','2010-01-23 20:00:00',80,1,26197,175879,167,19,115884,10,50,35,2,78,1,25,1293);
INSERT INTO `stats` VALUES (1086,'Hispania','2010-01-24 20:00:00',79,1,25374,177765,145,19,116488,10,71,36,5,78,1,22,1287);
INSERT INTO `stats` VALUES (1087,'Hispania','2010-01-25 20:00:00',74,0,26461,177980,218,33,118347,10,32,36,7,83,1,21,1284);
INSERT INTO `stats` VALUES (1088,'Hispania','2010-01-26 20:00:00',74,1,26600,179084,73,51,119140,10,88,36,4,83,1,23,1303);
INSERT INTO `stats` VALUES (1089,'Hispania','2010-01-27 20:00:00',71,0,26807,180137,142,47,119684,10,62,36,2,83,1,22,1294);
INSERT INTO `stats` VALUES (1090,'Hispania','2010-01-28 20:00:00',53,1,27333,180837,153,38,120150,10,13,36,25,83,1,22,1284);
INSERT INTO `stats` VALUES (1091,'Hispania','2010-01-29 20:00:00',51,2,28028,181388,161,42,120631,10,37,36,6,84,1,23,1279);
INSERT INTO `stats` VALUES (1092,'Hispania','2010-01-30 20:00:00',46,0,27955,182457,145,31,121121,10,35,35,5,84,1,17,1276);
INSERT INTO `stats` VALUES (1093,'Hispania','2010-01-31 20:00:00',44,0,27589,183959,132,60,122002,10,32,35,2,84,1,19,1269);
INSERT INTO `stats` VALUES (1094,'Hispania','2010-02-01 20:00:00',44,0,28127,185819,143,65,122265,9,37,36,0,84,1,20,1270);
INSERT INTO `stats` VALUES (1095,'Hispania','2010-02-02 20:00:00',42,0,28134,189998,166,21,123134,9,61,36,2,86,1,20,1253);
INSERT INTO `stats` VALUES (1096,'Hispania','2010-02-03 20:00:00',40,1,25810,193744,134,84,124179,8,97,36,5,86,1,19,1243);
INSERT INTO `stats` VALUES (1097,'Hispania','2010-02-04 20:00:00',43,3,31884,189224,150,70,124773,8,85,36,1,87,1,22,1250);
INSERT INTO `stats` VALUES (1098,'Hispania','2010-02-05 20:00:00',42,0,30610,194144,143,114,125504,8,280,36,2,87,1,21,1249);
INSERT INTO `stats` VALUES (1099,'Hispania','2010-02-06 20:00:00',41,0,31251,194651,120,11,125958,8,35,36,2,87,1,18,1251);
INSERT INTO `stats` VALUES (1100,'Hispania','2010-02-07 20:00:00',42,1,32331,195169,116,14,126252,8,36,36,1,87,1,21,1256);
INSERT INTO `stats` VALUES (1101,'Hispania','2010-02-08 20:00:00',43,2,31726,197333,144,96,126559,8,37,36,2,87,1,23,1262);
INSERT INTO `stats` VALUES (1102,'Hispania','2010-02-09 20:00:00',46,3,25578,205234,145,43,134452,7,140,34,1,88,1,26,1266);
INSERT INTO `stats` VALUES (1103,'Hispania','2010-02-10 20:00:00',44,0,26598,205677,138,32,134446,7,23,29,2,88,1,18,1262);
INSERT INTO `stats` VALUES (1104,'Hispania','2010-02-11 20:00:00',44,0,28203,206662,121,39,135349,7,82,29,2,88,1,23,1256);
INSERT INTO `stats` VALUES (1105,'Hispania','2010-02-12 20:00:00',45,1,33371,203117,137,91,135850,7,100,30,0,89,1,21,1264);
INSERT INTO `stats` VALUES (1106,'Hispania','2010-02-13 20:00:00',45,2,33717,204479,154,27,121475,6,11,29,3,89,1,24,1260);
INSERT INTO `stats` VALUES (1107,'Hispania','2010-02-14 20:00:00',43,0,33930,205714,172,28,122552,6,560,29,2,82,1,20,1263);
INSERT INTO `stats` VALUES (1108,'Hispania','2010-02-15 20:00:00',45,3,35168,207698,262,59,124033,6,131,30,1,74,0,25,1261);
INSERT INTO `stats` VALUES (1109,'Hispania','2010-02-16 20:00:00',47,2,35400,209531,469,94,124813,7,179,30,0,91,1,26,1289);
INSERT INTO `stats` VALUES (1110,'Hispania','2010-02-17 20:00:00',48,1,35914,211318,161,117,125830,6,30,31,1,91,1,29,1286);
INSERT INTO `stats` VALUES (1111,'Hispania','2010-02-18 20:00:00',52,6,36889,212281,190,90,126247,6,110,32,2,93,1,33,1303);
INSERT INTO `stats` VALUES (1112,'Hispania','2010-02-19 20:00:00',53,3,38322,213356,196,56,127028,6,151,35,3,94,1,40,1312);
INSERT INTO `stats` VALUES (1113,'Hispania','2010-02-20 20:00:00',53,1,41348,213939,172,12,127441,6,55,35,3,94,1,27,1323);
INSERT INTO `stats` VALUES (1114,'Hispania','2010-02-21 20:00:00',55,2,42699,214384,162,30,127915,6,131,36,0,94,1,28,1336);
INSERT INTO `stats` VALUES (1115,'Hispania','2010-02-22 20:00:00',55,0,45229,213590,197,55,128130,6,31,37,0,95,1,31,1341);
INSERT INTO `stats` VALUES (1116,'Hispania','2010-02-23 20:00:00',56,2,41804,216750,202,73,129091,5,90,34,3,95,1,27,1383);
INSERT INTO `stats` VALUES (1117,'Hispania','2010-02-24 20:00:00',58,2,42749,217617,166,63,129748,5,105,35,0,96,1,27,1348);
INSERT INTO `stats` VALUES (1118,'Hispania','2010-02-25 20:00:00',56,1,42674,219264,164,31,131160,5,141,34,4,96,1,26,1348);
INSERT INTO `stats` VALUES (1119,'Hispania','2010-02-26 20:00:00',56,1,43768,219697,154,12,131618,5,93,35,2,96,1,26,1347);
INSERT INTO `stats` VALUES (1120,'Hispania','2010-02-27 20:00:00',56,0,44452,220760,161,18,132085,5,36,36,0,96,1,25,1345);
INSERT INTO `stats` VALUES (1121,'Hispania','2010-02-28 20:00:00',58,2,45030,220182,153,23,130984,5,88,37,0,96,0,23,1332);
INSERT INTO `stats` VALUES (1122,'Hispania','2010-03-01 20:00:00',56,0,45334,219878,172,45,129740,5,79,38,3,96,1,25,1329);
INSERT INTO `stats` VALUES (1123,'Hispania','2010-03-02 20:00:00',54,1,46368,218844,158,35,128561,5,88,38,3,95,1,24,1325);
INSERT INTO `stats` VALUES (1124,'Hispania','2010-03-03 20:00:00',53,2,47143,218069,163,99,127528,5,140,38,4,95,0,22,1314);
INSERT INTO `stats` VALUES (1125,'Hispania','2010-03-04 20:00:00',53,2,48617,216595,154,39,127755,5,321,36,2,95,0,24,1309);
INSERT INTO `stats` VALUES (1126,'Hispania','2010-03-05 20:00:00',53,1,47686,217526,170,56,127849,5,106,35,1,95,1,21,1312);
INSERT INTO `stats` VALUES (1127,'Hispania','2010-03-06 20:00:00',55,5,43599,221613,175,71,128005,5,760,37,4,94,1,32,1315);
INSERT INTO `stats` VALUES (1128,'Hispania','2010-03-07 20:00:00',56,1,48282,216930,173,15,126443,5,60,37,2,94,1,33,1314);
INSERT INTO `stats` VALUES (1129,'Hispania','2010-03-08 20:00:00',54,1,48369,216843,163,139,127140,5,84,37,1,95,2,25,1310);
INSERT INTO `stats` VALUES (1130,'Hispania','2010-03-09 20:00:00',53,2,48177,217035,162,128,127285,6,100,36,3,96,2,27,1306);
INSERT INTO `stats` VALUES (1131,'Hispania','2010-03-10 20:00:00',54,2,48839,216373,151,166,131508,6,131,35,2,97,2,23,1289);
INSERT INTO `stats` VALUES (1132,'Hispania','2010-03-11 20:00:00',56,2,52324,209257,192,221,120522,6,49,32,0,97,2,25,1301);
INSERT INTO `stats` VALUES (1133,'Hispania','2010-03-12 20:00:00',57,2,46275,217317,183,72,126275,9,168,32,2,97,2,28,1270);
INSERT INTO `stats` VALUES (1134,'Hispania','2010-03-13 20:00:00',57,1,44345,219247,178,51,125433,9,131,34,1,98,2,26,1272);
INSERT INTO `stats` VALUES (1135,'Hispania','2010-03-14 20:00:00',55,3,41756,191675,215,100,125571,9,120,33,1,99,2,26,1291);
INSERT INTO `stats` VALUES (1136,'Hispania','2010-03-15 20:00:00',54,0,41040,192391,179,47,124652,10,372,33,0,100,1,25,1300);
INSERT INTO `stats` VALUES (1137,'Hispania','2010-03-16 20:00:00',49,0,41315,192116,165,32,119933,10,262,34,5,100,1,21,1295);
INSERT INTO `stats` VALUES (1138,'Hispania','2010-03-17 20:00:00',49,1,41159,192272,150,13,119414,9,425,34,3,100,1,24,1291);
INSERT INTO `stats` VALUES (1139,'Hispania','2010-03-18 20:00:00',46,0,41788,191513,169,47,118765,9,460,33,2,100,2,21,1294);
INSERT INTO `stats` VALUES (1140,'Hispania','2010-03-19 20:00:00',45,1,43101,190200,131,15,117568,10,80,33,2,100,2,20,1304);
INSERT INTO `stats` VALUES (1141,'Hispania','2010-03-20 20:00:00',47,2,44192,189239,159,12,116497,10,75,35,1,100,2,25,1309);
INSERT INTO `stats` VALUES (1142,'Hispania','2010-03-21 20:00:00',46,1,44861,188570,164,49,115303,10,101,35,3,100,2,27,1309);
INSERT INTO `stats` VALUES (1143,'Hispania','2010-03-22 20:00:00',47,2,44161,189270,229,56,111815,8,195,35,1,100,8,25,1329);
INSERT INTO `stats` VALUES (1144,'Hispania','2010-03-23 20:00:00',46,0,43854,189577,177,111,111246,8,140,38,3,100,6,22,1327);
INSERT INTO `stats` VALUES (1145,'Hispania','2010-03-24 20:00:00',47,2,41227,191304,212,137,102972,8,232,40,5,100,7,28,1336);
INSERT INTO `stats` VALUES (1146,'Hispania','2010-03-25 20:00:00',49,2,46183,186348,237,48,100293,8,229,43,3,98,6,27,1207);
INSERT INTO `stats` VALUES (1147,'Hispania','2010-03-26 20:00:00',52,3,43872,188659,240,32,100606,8,128,46,0,100,6,30,1219);
INSERT INTO `stats` VALUES (1148,'Hispania','2010-03-27 20:00:00',53,2,45393,187138,169,61,98962,8,110,46,0,100,5,29,1223);
INSERT INTO `stats` VALUES (1149,'Hispania','2010-03-28 20:00:00',52,0,45116,187415,178,21,97971,8,80,48,1,100,5,28,1234);
INSERT INTO `stats` VALUES (1150,'Hispania','2010-03-29 20:00:00',52,1,46770,185761,187,29,96357,8,110,48,1,100,5,27,1237);
INSERT INTO `stats` VALUES (1151,'Hispania','2010-03-30 20:00:00',51,0,48424,187247,195,46,94481,8,143,49,1,100,5,24,1233);
INSERT INTO `stats` VALUES (1152,'Hispania','2010-03-31 20:00:00',50,0,48590,187081,171,17,93173,8,111,50,1,100,5,25,1230);
INSERT INTO `stats` VALUES (1153,'Hispania','2010-04-01 20:00:00',49,1,50368,185303,184,24,92316,8,60,47,1,100,4,23,1185);
INSERT INTO `stats` VALUES (1154,'Hispania','2010-04-02 20:00:00',51,2,51450,184221,154,6,91087,8,42,47,0,100,4,21,1185);
INSERT INTO `stats` VALUES (1155,'Hispania','2010-04-03 20:00:00',51,1,55348,180323,193,13,89800,8,42,47,1,100,9,29,1189);
INSERT INTO `stats` VALUES (1156,'Hispania','2010-04-04 20:00:00',52,2,56374,179297,190,12,92982,8,122,47,1,100,8,27,1192);
INSERT INTO `stats` VALUES (1157,'Hispania','2010-04-05 20:00:00',53,1,57818,177853,237,56,87041,8,82,48,0,100,5,27,1229);
INSERT INTO `stats` VALUES (1158,'Hispania','2010-04-06 20:00:00',51,0,59503,176168,215,58,85572,8,201,46,3,100,5,27,1234);
INSERT INTO `stats` VALUES (1159,'Hispania','2010-04-07 20:00:00',53,2,61924,173747,254,101,84512,9,43,48,1,99,6,30,1239);
INSERT INTO `stats` VALUES (1160,'Hispania','2010-04-08 20:00:00',56,3,58267,178325,285,49,84432,9,65,47,0,100,4,34,1255);
INSERT INTO `stats` VALUES (1161,'Hispania','2010-04-09 20:00:00',56,1,59275,177317,251,24,83344,9,105,48,2,100,4,30,1264);
INSERT INTO `stats` VALUES (1162,'Hispania','2010-04-10 20:00:00',55,1,59219,177373,222,23,83335,9,129,45,1,99,3,32,1266);
INSERT INTO `stats` VALUES (1163,'Hispania','2010-04-11 20:00:00',55,0,59461,177141,218,37,82513,9,137,46,1,100,4,25,1280);
INSERT INTO `stats` VALUES (1164,'Hispania','2010-04-12 20:00:00',56,2,60673,175929,202,17,77722,9,83,47,1,100,4,26,1285);
INSERT INTO `stats` VALUES (1165,'Hispania','2010-04-13 20:00:00',54,0,62794,173808,228,10,76761,9,440,46,2,100,2,29,1306);
INSERT INTO `stats` VALUES (1166,'Hispania','2010-04-14 20:00:00',53,1,62828,173774,214,15,78163,9,591,46,2,100,2,30,1312);
INSERT INTO `stats` VALUES (1167,'Hispania','2010-04-15 20:00:00',52,1,65404,170526,251,46,72879,8,999,44,0,100,2,27,1321);
INSERT INTO `stats` VALUES (1168,'Hispania','2010-04-16 20:00:00',53,1,66265,169665,224,57,71830,8,290,45,2,100,2,28,1329);
INSERT INTO `stats` VALUES (1169,'Hispania','2010-04-17 20:00:00',56,2,67492,168438,194,17,70415,8,131,46,2,100,2,32,1334);
INSERT INTO `stats` VALUES (1170,'Hispania','2010-04-18 20:00:00',53,0,67675,168455,189,6,70139,8,54,46,5,100,2,29,1314);
INSERT INTO `stats` VALUES (1171,'Hispania','2010-04-19 20:00:00',52,1,67699,168431,200,38,69475,8,160,45,2,100,2,28,1317);
INSERT INTO `stats` VALUES (1172,'Hispania','2010-04-20 20:00:00',55,4,69732,166413,264,159,65230,10,185,43,3,100,3,30,1325);
INSERT INTO `stats` VALUES (1173,'Hispania','2010-04-21 20:00:00',56,1,69358,166787,230,68,63790,11,131,43,0,100,3,32,1311);
INSERT INTO `stats` VALUES (1174,'Hispania','2010-04-22 20:00:00',57,1,68946,167199,203,52,63923,11,450,43,0,100,3,31,1314);
INSERT INTO `stats` VALUES (1175,'Hispania','2010-04-23 20:00:00',56,0,70245,165900,210,30,62340,11,140,41,1,100,4,32,1340);
INSERT INTO `stats` VALUES (1176,'Hispania','2010-04-24 20:00:00',57,1,70435,165710,194,58,61566,11,125,39,0,100,4,31,1350);
INSERT INTO `stats` VALUES (1177,'Hispania','2010-04-25 20:00:00',58,1,70430,165715,194,109,60942,11,203,38,1,100,3,31,1359);
INSERT INTO `stats` VALUES (1178,'Hispania','2010-04-26 20:00:00',56,0,73296,165549,199,148,60712,10,100,38,2,100,3,29,1358);
INSERT INTO `stats` VALUES (1179,'Hispania','2010-04-27 20:00:00',54,0,73814,165031,198,49,59995,10,100,38,1,100,3,31,1370);
INSERT INTO `stats` VALUES (1180,'Hispania','2010-04-28 20:00:00',54,0,73528,165317,186,70,59522,10,411,37,1,100,3,29,1382);
INSERT INTO `stats` VALUES (1181,'Hispania','2010-04-29 20:00:00',55,1,73881,164964,178,48,59070,10,480,37,3,100,3,31,1385);
INSERT INTO `stats` VALUES (1182,'Hispania','2010-04-30 20:00:00',54,0,74316,164970,193,37,58557,10,400,37,1,99,3,31,1389);
INSERT INTO `stats` VALUES (1183,'Hispania','2010-05-01 20:00:00',54,1,75404,163882,221,30,57935,10,81,37,2,100,4,36,1387);
INSERT INTO `stats` VALUES (1184,'Hispania','2010-05-02 20:00:00',54,1,72827,166459,219,28,57837,10,730,37,1,100,4,33,1392);
INSERT INTO `stats` VALUES (1185,'Hispania','2010-05-03 20:00:00',55,1,73299,165979,240,39,57149,9,240,36,0,97,3,33,1394);
INSERT INTO `stats` VALUES (1186,'Hispania','2010-05-04 20:00:00',56,2,74222,165509,99,44,60803,10,110,34,2,98,4,33,1400);
INSERT INTO `stats` VALUES (1187,'Hispania','2010-05-05 20:00:00',53,0,75990,163741,198,85,61900,10,360,33,3,98,4,34,1395);
INSERT INTO `stats` VALUES (1188,'Hispania','2010-05-06 20:00:00',53,0,85224,154507,213,36,61070,10,151,34,0,99,4,32,1398);
INSERT INTO `stats` VALUES (1189,'Hispania','2010-05-07 20:00:00',53,1,81752,157979,220,47,68867,8,162,35,5,100,7,31,1366);
INSERT INTO `stats` VALUES (1190,'Hispania','2010-05-08 20:00:00',53,1,81813,157918,184,44,68331,8,174,34,1,100,7,29,1356);
INSERT INTO `stats` VALUES (1191,'Hispania','2010-05-09 20:00:00',52,0,81691,158040,168,14,67869,8,95,35,1,100,7,24,1360);
INSERT INTO `stats` VALUES (1192,'Hispania','2010-05-10 20:00:00',52,1,82046,157685,170,50,67475,7,300,35,1,100,8,27,1368);
INSERT INTO `stats` VALUES (1193,'Hispania','2010-05-11 20:00:00',52,1,82704,157027,183,41,67118,7,212,35,3,100,7,31,1379);
INSERT INTO `stats` VALUES (1194,'Hispania','2010-05-12 20:00:00',57,4,77414,167084,191,57,67158,8,1050,35,0,100,7,37,1411);
INSERT INTO `stats` VALUES (1195,'Hispania','2010-05-13 20:00:00',61,4,89796,154702,206,29,66937,8,624,36,0,100,7,35,1422);
INSERT INTO `stats` VALUES (1196,'Hispania','2010-05-14 20:00:00',60,1,89220,158143,207,42,67249,9,570,36,5,100,7,35,1414);
INSERT INTO `stats` VALUES (1197,'Hispania','2010-05-15 20:00:00',69,8,91713,157312,211,26,66139,9,91,36,0,100,7,48,1440);
INSERT INTO `stats` VALUES (1198,'Hispania','2010-05-16 20:00:00',70,2,84841,164184,215,7,65841,9,320,37,1,100,7,34,1450);
INSERT INTO `stats` VALUES (1199,'Hispania','2010-05-17 20:00:00',67,3,79748,169380,210,209,70614,8,541,38,2,100,7,40,1457);
INSERT INTO `stats` VALUES (1200,'Hispania','2010-05-18 20:00:00',73,7,79332,169796,222,126,69965,8,320,37,1,100,7,43,1493);
INSERT INTO `stats` VALUES (1201,'Hispania','2010-05-19 20:00:00',75,3,76958,172170,220,363,69039,6,3110,36,1,100,7,35,1501);
INSERT INTO `stats` VALUES (1202,'Hispania','2010-05-20 20:00:00',77,3,77514,171604,227,113,68269,6,72,37,1,100,7,41,1507);
INSERT INTO `stats` VALUES (1203,'Hispania','2010-05-21 20:00:00',80,4,78147,170971,211,168,67410,6,61,35,3,100,7,40,1516);
INSERT INTO `stats` VALUES (1204,'Hispania','2010-05-22 20:00:00',77,0,76927,172191,215,90,67129,6,44,37,3,100,7,38,1507);
INSERT INTO `stats` VALUES (1205,'POL','2010-05-23 20:00:00',77,0,144599,472249,109,39,216017,10,13,11,0,56,0,25,1526);
INSERT INTO `stats` VALUES (1206,'Hispania','2010-05-23 20:00:00',79,4,83379,165739,228,176,66990,7,402,37,6,100,7,44,1526);
INSERT INTO `stats` VALUES (1207,'Hispania','2010-05-24 20:00:00',77,0,79436,169682,226,136,67338,9,169,38,1,99,6,39,1526);
INSERT INTO `stats` VALUES (1208,'POL','2010-05-24 20:00:00',78,0,146345,470503,112,45,215776,10,34,11,0,56,0,22,1526);
INSERT INTO `stats` VALUES (1209,'Hispania','2010-05-25 20:00:00',78,3,79190,169928,249,144,67310,10,259,43,6,100,6,44,1531);
INSERT INTO `stats` VALUES (1210,'POL','2010-05-25 20:00:00',78,2,146070,470778,113,52,210351,10,90,11,2,56,0,29,1531);
INSERT INTO `stats` VALUES (1211,'VULCAN','2009-05-04 20:00:00',20,0,207908,500482,10,52,500482,7,0,20,1,61,0,80,209);
INSERT INTO `stats` VALUES (1212,'Hispania','2010-05-26 20:00:00',81,5,79412,169705,246,248,67476,9,809,42,1,100,5,47,1550);
INSERT INTO `stats` VALUES (1213,'POL','2010-05-26 20:00:00',83,4,146155,470694,115,48,210267,10,34,12,1,56,0,26,1550);
INSERT INTO `stats` VALUES (1214,'Hispania','2010-05-27 20:00:00',84,4,78718,170390,251,192,67204,9,290,39,2,100,5,48,1611);
INSERT INTO `stats` VALUES (1215,'POL','2010-05-27 20:00:00',87,3,147022,469836,123,89,208909,10,2,14,2,56,0,30,1611);
INSERT INTO `stats` VALUES (1216,'POL','2010-05-28 20:00:00',78,3,147140,469723,123,15,208796,10,13,14,16,56,0,31,1600);
INSERT INTO `stats` VALUES (1217,'Hispania','2010-05-28 20:00:00',78,0,77471,171632,238,49,66969,9,102,41,8,100,6,42,1599);
INSERT INTO `stats` VALUES (1218,'Hispania','2010-05-29 20:00:00',80,3,78019,171084,216,28,66213,9,53,41,3,100,6,47,1603);
INSERT INTO `stats` VALUES (1219,'POL','2010-05-29 20:00:00',75,0,147109,469754,119,11,208827,10,410,14,4,57,0,34,1603);
INSERT INTO `stats` VALUES (1220,'POL','2010-05-30 20:00:00',76,3,146931,469932,120,12,209005,10,270,14,2,60,0,31,1591);
INSERT INTO `stats` VALUES (1221,'Hispania','2010-05-30 20:00:00',79,1,77872,171231,214,22,65633,9,47,41,3,100,6,45,1591);
INSERT INTO `stats` VALUES (1222,'POL','2010-05-31 20:00:00',73,1,149323,467540,127,21,208858,10,2,15,3,60,0,27,1605);
INSERT INTO `stats` VALUES (1223,'Hispania','2010-05-31 20:00:00',79,1,78516,170587,228,139,67927,9,140,41,2,100,6,45,1601);
INSERT INTO `stats` VALUES (1224,'Hispania','2010-06-01 20:00:00',78,0,72857,176246,232,179,63431,9,131,44,2,100,6,41,1565);
INSERT INTO `stats` VALUES (1225,'POL','2010-06-01 20:00:00',69,1,149272,467591,122,65,208909,9,60,16,12,60,0,26,1556);
INSERT INTO `stats` VALUES (1226,'Hispania','2010-06-02 20:00:00',80,2,77449,171654,228,87,60869,9,181,44,0,100,6,40,1559);
INSERT INTO `stats` VALUES (1227,'POL','2010-06-02 20:00:00',69,1,149749,467114,119,58,209001,9,23,15,1,62,0,25,1559);
INSERT INTO `stats` VALUES (1228,'POL','2010-06-03 20:00:00',68,1,151578,465285,115,76,208677,9,0,14,3,62,0,27,1572);
INSERT INTO `stats` VALUES (1229,'Hispania','2010-06-03 20:00:00',83,4,75990,173113,247,79,60466,9,80,47,1,100,6,42,1570);
INSERT INTO `stats` VALUES (1230,'Hispania','2010-06-04 20:00:00',84,2,77370,171738,229,38,60041,10,250,46,2,100,6,38,1581);
INSERT INTO `stats` VALUES (1231,'POL','2010-06-04 20:00:00',66,0,151738,465125,109,18,208517,9,111,12,2,62,0,24,1579);
INSERT INTO `stats` VALUES (1232,'POL','2010-06-05 20:00:00',63,0,145896,471124,109,79,214516,9,930,12,4,62,0,26,1568);
INSERT INTO `stats` VALUES (1233,'Hispania','2010-06-05 20:00:00',85,3,76899,172057,244,97,61010,10,232,46,3,100,6,44,1568);
INSERT INTO `stats` VALUES (1234,'POL','2010-06-06 20:00:00',63,4,146183,470837,104,65,214229,9,35,12,6,63,0,31,1612);
INSERT INTO `stats` VALUES (1235,'Hispania','2010-06-06 20:00:00',84,3,77766,171190,236,55,60294,10,99,45,4,100,6,45,1603);
INSERT INTO `stats` VALUES (1236,'POL','2010-06-07 20:00:00',64,3,146636,470389,105,29,214306,9,11,12,3,64,0,29,1675);
INSERT INTO `stats` VALUES (1237,'Hispania','2010-06-07 20:00:00',83,0,78618,170338,247,119,59715,10,37,47,0,100,6,41,1675);
INSERT INTO `stats` VALUES (1238,'POL','2010-06-08 20:00:00',66,3,147626,469399,106,19,213066,9,4,12,3,64,0,32,1701);
INSERT INTO `stats` VALUES (1239,'Hispania','2010-06-08 20:00:00',80,1,78552,170404,242,142,59685,10,195,48,4,100,6,42,1689);
INSERT INTO `stats` VALUES (1240,'POL','2010-06-09 20:00:00',69,3,147576,469495,108,33,212712,10,41,12,2,64,0,32,1700);
INSERT INTO `stats` VALUES (1241,'Hispania','2010-06-09 20:00:00',79,1,79303,169607,233,125,59628,9,312,48,1,100,5,43,1698);
INSERT INTO `stats` VALUES (1242,'POL','2010-06-10 20:00:00',69,0,147591,469480,107,79,212697,10,53,12,1,64,0,28,1710);
INSERT INTO `stats` VALUES (1243,'Hispania','2010-06-10 20:00:00',80,3,79775,169135,237,121,59068,10,229,48,3,100,5,43,1706);
INSERT INTO `stats` VALUES (1244,'POL','2010-06-11 20:00:00',68,0,147337,469734,113,74,212436,10,36,12,6,64,0,29,1707);
INSERT INTO `stats` VALUES (1245,'Hispania','2010-06-11 20:00:00',81,3,74597,174313,250,145,58776,10,141,50,2,100,5,46,1707);
INSERT INTO `stats` VALUES (1246,'Hispania','2010-06-12 20:00:00',82,4,74505,174405,249,28,58427,10,75,51,3,100,5,49,1722);
INSERT INTO `stats` VALUES (1247,'POL','2010-06-12 20:00:00',70,2,144991,472080,135,54,212185,10,33,12,2,64,0,36,1722);
INSERT INTO `stats` VALUES (1248,'Hispania','2010-06-13 20:00:00',82,0,75110,173800,224,77,57758,10,202,51,0,100,4,39,1731);
INSERT INTO `stats` VALUES (1249,'POL','2010-06-13 20:00:00',66,0,145250,471821,111,30,211841,10,1,12,2,64,0,23,1731);
INSERT INTO `stats` VALUES (1250,'Hispania','2010-06-14 20:00:00',78,0,81401,167509,216,102,51382,10,100,49,5,100,5,40,1707);
INSERT INTO `stats` VALUES (1251,'POL','2010-06-14 20:00:00',66,0,145821,471250,108,25,211496,10,1,11,0,64,0,26,1707);
INSERT INTO `stats` VALUES (1252,'Hispania','2010-06-15 20:00:00',77,1,81541,167369,93,87,51249,10,31,49,4,100,5,31,1702);
INSERT INTO `stats` VALUES (1253,'POL','2010-06-15 20:00:00',66,0,145887,471184,50,11,211430,10,0,11,0,64,0,18,1702);
INSERT INTO `stats` VALUES (1256,'POL','2010-06-16 20:00:00',68,2,146248,470823,105,59,211598,10,0,11,5,64,0,25,1712);
INSERT INTO `stats` VALUES (1257,'Hispania','2010-06-16 20:00:00',79,2,83080,165830,237,161,55022,10,430,49,5,100,4,41,1712);
INSERT INTO `stats` VALUES (1258,'POL','2010-06-17 20:00:00',66,1,146309,470762,110,25,211587,10,53,11,5,64,0,28,1722);
INSERT INTO `stats` VALUES (1259,'Hispania','2010-06-17 20:00:00',81,2,83392,165518,212,181,54690,10,210,49,0,100,4,40,1722);
INSERT INTO `stats` VALUES (1260,'POL','2010-06-18 20:00:00',67,3,148286,468785,115,35,199500,10,12,11,3,64,0,31,1730);
INSERT INTO `stats` VALUES (1261,'Hispania','2010-06-18 20:00:00',80,2,90005,158905,212,105,54310,10,43,48,2,100,4,40,1720);
INSERT INTO `stats` VALUES (1262,'POL','2010-06-19 20:00:00',66,1,148412,468659,112,39,199372,10,22,11,2,64,0,26,1729);
INSERT INTO `stats` VALUES (1263,'Hispania','2010-06-19 20:00:00',81,2,90239,158671,221,82,50916,10,172,48,1,100,6,41,1728);
INSERT INTO `stats` VALUES (1264,'Hispania','2010-06-20 20:00:00',81,2,78293,170617,236,130,50484,10,72,45,4,96,4,41,1745);
INSERT INTO `stats` VALUES (1265,'POL','2010-06-20 20:00:00',64,1,148762,468309,109,3,199022,10,81,11,2,64,0,26,1745);
INSERT INTO `stats` VALUES (1266,'POL','2010-06-21 20:00:00',71,5,149863,467208,123,73,197921,11,33,11,1,64,0,30,1793);
INSERT INTO `stats` VALUES (1267,'Hispania','2010-06-21 20:00:00',76,3,92256,156669,315,218,51207,10,226,47,3,100,8,38,1788);
INSERT INTO `stats` VALUES (1268,'Hispania','2010-06-22 20:00:00',72,1,94939,153986,239,142,51465,10,169,46,5,100,8,46,1782);
INSERT INTO `stats` VALUES (1269,'POL','2010-06-22 20:00:00',69,0,151238,465833,118,24,197624,11,35,11,5,64,0,29,1782);
INSERT INTO `stats` VALUES (1270,'Hispania','2010-06-23 20:00:00',75,4,95565,153360,228,100,50117,9,130,47,1,100,7,44,1774);
INSERT INTO `stats` VALUES (1271,'POL','2010-06-23 20:00:00',71,2,151469,465602,113,33,197728,9,12,11,1,66,0,32,1774);
INSERT INTO `stats` VALUES (1272,'Hispania','2010-06-24 20:00:00',71,1,91452,157473,273,75,50649,10,240,44,1,100,9,37,1778);
INSERT INTO `stats` VALUES (1273,'POL','2010-06-24 20:00:00',75,0,151659,465412,117,26,197538,9,125,11,0,67,0,35,1778);
CREATE TABLE `users` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `nick` varchar(14) NOT NULL default '',
  `pais` enum('ninguno','POL','VULCAN','Hispania','VP') character set utf8 NOT NULL default 'POL',
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
  `bando` varchar(255) default NULL,
  `nota_SC` varchar(255) default '',
  `fecha_legal` datetime default '0000-00-00 00:00:00',
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
) ENGINE=MyISAM AUTO_INCREMENT=200488 DEFAULT CHARSET=latin1;

INSERT INTO `users` VALUES (200414,'GONZO_DEV','POL',3049,'2009-10-09 18:08:29','2011-04-10 19:23:01',0,'ciudadano',100,'***@gmail.com','HASS_PASS_HERE',0,62347,'2011-04-10 19:23:01',0,0,'ff99becf26c2',6,'1597815562',10,'false','',12,70,810,'Mozilla/5.0 (Windows NT 6.1; rv:2.0) Gecko/20100101 Firefox/4.0',0,'0000-00-00 00:00:00','','*.ipcom.comunitel.net','','',NULL,NULL,'','0000-00-00 00:00:00');
INSERT INTO `users` VALUES (200419,'opor','POL',61,'2009-10-09 19:49:46','2011-05-04 18:39:55',0,'ciudadano',10,'***@gmail.com','HASS_PASS_HERE',1,115130,'2011-05-04 17:31:26',0,0,'cd4977cadbfa',0,'1429542581',0,'false','',34,146,2274,'Mozilla/5.0 (X11; Linux i686; rv:2.0.1) Gecko/20100101 Firefox/4.0.1',0,'2010-09-24 18:52:47','','*.dynamic.orange.es','','',NULL,NULL,'','2011-05-04 18:20:46');
INSERT INTO `users` VALUES (200424,'dev_sanchez','POL',0,'2009-10-13 15:56:56','2010-04-17 21:31:51',0,'ciudadano',1,'***@gmail.com','HASS_PASS_HERE',0,16041,'2010-04-17 21:31:28',0,0,'ea467f246196',0,'1361312159',0,'false','',0,20,148,'Mozilla/5.0 (Windows; U; Windows NT 5.1; es-ES; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (.NET CLR 3.5.30729)',0,'0000-00-00 00:00:00','','*.dynamicIP.rima-tde.net','','',NULL,NULL,'','0000-00-00 00:00:00');
CREATE TABLE `votacion` (
  `ID` smallint(5) NOT NULL auto_increment,
  `pais` enum('VP','15M','15MBCN','15MMAD','POL','Hispania','VULCAN','Atlantis') character set utf8 NOT NULL default 'VP',
  `pregunta` varchar(255) character set utf8 NOT NULL default '',
  `descripcion` text character set utf8 NOT NULL,
  `respuestas` text character set utf8 NOT NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `time_expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_ID` mediumint(8) NOT NULL default '0',
  `estado` enum('ok','end') character set utf8 NOT NULL default 'ok',
  `num` smallint(5) NOT NULL default '0',
  `tipo` enum('sondeo','referendum','parlamento','destituir','otorgar') character set utf8 NOT NULL default 'sondeo',
  `acceso_votar` varchar(30) character set utf8 NOT NULL default 'ciudadanos_pais',
  `acceso_cfg_votar` varchar(800) character set utf8 NOT NULL default '',
  `ejecutar` varchar(255) character set utf8 NOT NULL default '',
  `votos_expire` smallint(5) unsigned NOT NULL default '0',
  `tipo_voto` enum('estandar','3puntos','5puntos') NOT NULL default 'estandar',
  `privacidad` enum('true','false') NOT NULL default 'true',
  PRIMARY KEY  (`ID`),
  KEY `tipo` (`tipo`),
  KEY `pais` (`pais`),
  KEY `time_expire` (`time_expire`),
  KEY `estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=1034 DEFAULT CHARSET=latin1;

CREATE TABLE `votacion_votos` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `ref_ID` smallint(5) unsigned NOT NULL default '0',
  `voto` varchar(300) character set utf8 NOT NULL default '0',
  `validez` enum('true','false') character set utf8 NOT NULL default 'true',
  `autentificado` enum('true','false') default 'false',
  `mensaje` varchar(140) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `ref_ID` (`ref_ID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=19630 DEFAULT CHARSET=latin1;

CREATE TABLE `votos` (
  `voto_ID` int(11) unsigned NOT NULL auto_increment,
  `pais` enum('all','VP','15M') character set utf8 NOT NULL default 'all',
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
) ENGINE=MyISAM AUTO_INCREMENT=17228 DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `vp_cat` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `vp_config` (
  `ID` tinyint(3) NOT NULL auto_increment,
  `dato` varchar(30) NOT NULL default '',
  `valor` text NOT NULL,
  `autoload` enum('si','no') NOT NULL default 'no',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `dato` (`dato`),
  KEY `autoload` (`autoload`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;

INSERT INTO `vp_config` VALUES (1,'tiempo_turista','1','si');
INSERT INTO `vp_config` VALUES (2,'info_censo','5','si');
INSERT INTO `vp_config` VALUES (3,'info_partidos','0','si');
INSERT INTO `vp_config` VALUES (4,'info_documentos','0','si');
INSERT INTO `vp_config` VALUES (5,'elecciones_estado','elecciones','no');
INSERT INTO `vp_config` VALUES (7,'num_escanos','11','si');
INSERT INTO `vp_config` VALUES (8,'elecciones_inicio','2011-02-04 20:00:00','si');
INSERT INTO `vp_config` VALUES (9,'elecciones_duracion','172800','si');
INSERT INTO `vp_config` VALUES (10,'elecciones_frecuencia','1036800','si');
INSERT INTO `vp_config` VALUES (11,'elecciones_antiguedad','86400','si');
INSERT INTO `vp_config` VALUES (12,'pols_frase','','si');
INSERT INTO `vp_config` VALUES (13,'pols_afiliacion','1000','no');
INSERT INTO `vp_config` VALUES (14,'pols_fraseedit','201715','si');
INSERT INTO `vp_config` VALUES (15,'info_consultas','0','si');
INSERT INTO `vp_config` VALUES (16,'pols_empresa','0','si');
INSERT INTO `vp_config` VALUES (17,'pols_cuentas','20','si');
INSERT INTO `vp_config` VALUES (18,'pols_partido','250','si');
INSERT INTO `vp_config` VALUES (19,'defcon','3','si');
INSERT INTO `vp_config` VALUES (20,'pols_inem','10','no');
INSERT INTO `vp_config` VALUES (21,'online_ref','3600','no');
INSERT INTO `vp_config` VALUES (22,'factor_propiedad','1','no');
INSERT INTO `vp_config` VALUES (23,'palabras','','si');
INSERT INTO `vp_config` VALUES (24,'palabras_num','6','no');
INSERT INTO `vp_config` VALUES (26,'elecciones','pres1','si');
INSERT INTO `vp_config` VALUES (27,'examen_repe','43200','no');
INSERT INTO `vp_config` VALUES (28,'pols_solar','1','no');
INSERT INTO `vp_config` VALUES (29,'pols_mensajetodos','1000','no');
INSERT INTO `vp_config` VALUES (30,'pols_examen','0','no');
INSERT INTO `vp_config` VALUES (31,'pols_mensajeurgente','1','no');
INSERT INTO `vp_config` VALUES (33,'frontera','abierta','si');
INSERT INTO `vp_config` VALUES (34,'palabra_gob','Version provisional del nuevo VP:vp.virtualpol.com/','si');
INSERT INTO `vp_config` VALUES (35,'examenes_exp','7776000','no');
INSERT INTO `vp_config` VALUES (36,'impuestos_minimo','3000','no');
INSERT INTO `vp_config` VALUES (37,'impuestos','1.30','no');
INSERT INTO `vp_config` VALUES (38,'arancel_entrada','','no');
INSERT INTO `vp_config` VALUES (39,'arancel_salida','0','no');
INSERT INTO `vp_config` VALUES (40,'bg','','si');
INSERT INTO `vp_config` VALUES (41,'pais_des','...','si');
INSERT INTO `vp_config` VALUES (42,'impuestos_empresa','0','no');
INSERT INTO `vp_config` VALUES (43,'frontera_con_Atlantis','cerrada','si');
INSERT INTO `vp_config` VALUES (44,'frontera_con_Hispania','cerrada','si');
INSERT INTO `vp_config` VALUES (45,'pols_crearchat','200','no');
INSERT INTO `vp_config` VALUES (46,'chat_diasexpira','15','no');
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `vp_diputados` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `vp_elecciones` (
  `ID` mediumint(8) NOT NULL auto_increment,
  `ID_partido` varchar(800) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `nav` varchar(255) NOT NULL default '',
  `IP` varchar(30) NOT NULL default '',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num';

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)';

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `vp_log` (
  `ID` bigint(12) unsigned NOT NULL auto_increment,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `user_ID2` mediumint(8) unsigned NOT NULL default '0',
  `accion` tinyint(3) unsigned NOT NULL default '0',
  `dato` mediumint(8) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO `vp_log` VALUES (1,'2011-03-12 23:25:29',200482,200482,2,0);
INSERT INTO `vp_log` VALUES (2,'2011-03-12 23:25:36',200481,200481,2,0);
INSERT INTO `vp_log` VALUES (3,'2011-03-22 20:51:35',200484,200484,2,0);
INSERT INTO `vp_log` VALUES (4,'2011-05-24 23:34:41',200485,200485,2,0);
INSERT INTO `vp_log` VALUES (5,'2011-05-25 13:04:37',200486,200486,2,0);
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `vp_partidos_listas` (
  `ID` smallint(5) NOT NULL auto_increment,
  `ID_partido` smallint(5) NOT NULL default '0',
  `user_ID` mediumint(8) NOT NULL default '0',
  `orden` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `vp_pujas` (
  `ID` mediumint(8) unsigned NOT NULL auto_increment,
  `mercado_ID` tinyint(3) unsigned NOT NULL default '0',
  `user_ID` mediumint(8) unsigned NOT NULL default '0',
  `pols` mediumint(8) unsigned NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ID`),
  KEY `mercado_ID` (`mercado_ID`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
