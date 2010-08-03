<?php

// INICIALIZACION
$host = explode('.', $_SERVER['HTTP_HOST']); // obtiene $host[0] que es el subdominio
$host[0] = str_replace('-dev', '', $host[0], $dev); // convierte subdominios "pais-dev" en "pais" para que funcione la version dev
if ($host[1] != 'virtualpol') { header('HTTP/1.1 301 Moved Permanently'); header('Location: http://www.virtualpol.com/'); exit; }


// Configuracion Paises y colores
$vp['paises'] = array('POL', 'Hispania');
$vp['bg'] = array('POL'=>'#E1EDFF', 'Hispania'=>'#FFFF4F', 'ninguno'=>'#FFFFFF');
$vp['bg2'] = array('POL'=>'#BFD9FF', 'Hispania'=>'#D9D900', 'ninguno'=>'#FFFFFF');

// Configuracion por pais
switch ($host[0]) {

case 'pol':
	define('PAIS', 'POL');
	define('SQL', 'pol_');
	define('COLOR_BG', $vp['bg'][PAIS]);
	define('COLOR_BG2', $vp['bg2'][PAIS]);
	break;

case 'hispania':
	define('PAIS', 'Hispania');
	define('SQL', 'hispania_');
	define('COLOR_BG', $vp['bg'][PAIS]);
	define('COLOR_BG2', $vp['bg2'][PAIS]);
	break;

default:
	define('PAIS', 'POL');
	define('SQL', 'pol_');
	define('COLOR_BG', '#eee');
	define('COLOR_BG2', 'grey');
	break;
}

// variables del sistema
define('MONEDA', '<img src="/img/m.gif" border="0" />');
define('MONEDA_NOMBRE', 'POLs');
if ($dev) {
	// Version DEV
	define('RAIZ', '/var/www/vhosts/virtualpol.com/httpdocs/devel/');
} else {
	// version REAL (www.virtualpol.com)
	define('RAIZ', '/var/www/vhosts/virtualpol.com/httpdocs/real/');
}

define('HOST', $_SERVER['HTTP_HOST']);
define('VERSION', '1.0 Beta');

// variables de tablas SQL
define('SQL_USERS', 'users');
define('SQL_REFERENCIAS', 'referencias');
define('SQL_MENSAJES', 'mensajes');
define('SQL_VOTOS', 'votos');
define('SQL_EXPULSIONES', 'expulsiones');

// variables del sistema de usuarios
define('USERCOOKIE', '.virtualpol.com');

if ($dev) {
	define('REGISTRAR', 'http://www-dev.virtualpol.com/registrar/');
	define('DEV', '-dev');
} else {
	define('REGISTRAR', 'http://www.virtualpol.com/registrar/');
	define('DEV', '');
}



// funciones con passwords importantes
include('config-pwd.php');
?>
