<?php
define('URL', 'virtualpol.com');
define('RAIZ', '/var/www/vhosts/virtualpol.com/httpdocs/'.(DEV=='-dev'?'devel':'real').'/');

// INICIALIZACION
$host = explode('.', $_SERVER['HTTP_HOST']); // obtiene $host[0] que es el subdominio
$host[0] = str_replace('-dev', '', $host[0]); // convierte subdominios "pais-dev" en "pais" para que funcione la version dev
if ($host[1] != 'virtualpol') { header('HTTP/1.1 301 Moved Permanently'); header('Location: http://www.virtualpol.com/'); exit; }

// Passwords y claves
include(RAIZ.'config-pwd.php');

// PAISES
$vp['paises'] = array('15M', 'VP'); // ACTIVOS
$vp['paises_congelados'] = array('POL', 'VULCAN', 'Hispania', 'Atlantis'); // INACTIVOS

// COLORES
$vp['bg'] = array('POL'=>'#E1EDFF', 'Hispania'=>'#FFFF4F', 'Atlantis'=>'#B9B9B9', 'vp'=>'#ACFA58', 'www'=>'#eeeeee');
$vp['bg2'] = array('POL'=>'#BFD9FF', 'Hispania'=>'#D9D900', 'Atlantis'=>'#999999', 'vp'=>'#9AFE2E', 'www'=>'grey');

// Configuracion por pais
switch ($host[0]) { 
	case 'vp': define('PAIS', 'VP'); break;
	case '15m': define('PAIS', '15M'); break;
	case 'pol': define('PAIS', 'POL'); break;
	case 'vulcan': define('PAIS', 'Vulcan'); break;
	case 'hispania': define('PAIS', 'Hispania'); break;
	case 'atlantis': define('PAIS', 'Atlantis'); break;
	default: define('PAIS', 'VP'); break;
}

if (PAIS == '15M') { 
	define('ECONOMIA', false);
	define('NOM_PARTIDOS','Comisiones');
} else { 
	define('ECONOMIA', true); 
	define('NOM_PARTIDOS','Partidos');
}

define('SQL', strtolower(PAIS).'_');

define('COLOR_BG', $vp['bg'][PAIS]);
define('COLOR_BG2', $vp['bg2'][PAIS]);

define('HOST', $_SERVER['HTTP_HOST']);
define('VERSION', '1.0 Beta');
define('IMG', 'http://vp.cdn.teoriza.com/'); // http://www.virtualpol.com/img/ - http://vp.cdn.teoriza.com/

define('MONEDA', '<img src="'.IMG.'m.gif" border="0" />');
define('MONEDA_NOMBRE', 'monedas');
// variables de tablas SQL
define('SQL_USERS', 'users');
define('SQL_REFERENCIAS', 'referencias');
define('SQL_MENSAJES', 'mensajes');
define('SQL_VOTOS', 'votos');
define('SQL_EXPULSIONES', 'expulsiones');
define('VOTO_CONFIANZA_MAX', 40); // numero maximo de votos de confianza emitidos

// Variables del sistema de usuarios
define('USERCOOKIE', '.virtualpol.com');
define('REGISTRAR', 'http://www'.DEV.'.virtualpol.com/registrar/');

// MAPA
$columnas = 14; $filas = 18;
?>
