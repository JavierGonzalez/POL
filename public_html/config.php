<?php

// INICIALIZACION


$host = explode('.', $_SERVER['HTTP_HOST']); // obtiene $host[0] que es el subdominio
$host[0] = str_replace('-dev', '', $host[0]); // convierte subdominios "pais-dev" en "pais" para que funcione la version dev

if ($host[1] != 'virtualpol') { header('HTTP/1.1 301 Moved Permanently'); header('Location: http://www.virtualpol.com/'); exit; }

// paises existentes y colores
$vp['paises'] = array('POL', 'Hispania', 'VULCAN');
$vp['bg'] = array('POL'=>'#E1EDFF', 'VULCAN'=>'#FFD7B3', 'Hispania'=>'#FFFF4F', 'ninguno'=>'#FFFFFF'); //#FFFF00
$vp['bg2'] = array('POL'=>'#BFD9FF', 'VULCAN'=>'#FFB3B3', 'Hispania'=>'#D9D900', 'ninguno'=>'#FFFFFF');

// carga las variables de los paises
switch ($host[0]) {

case 'pol':
	define('PAIS', 'POL');
	define('SQL', 'pol_');
	define('COLOR_BG', $vp['bg'][PAIS]);
	define('COLOR_BG2', $vp['bg2'][PAIS]);
	break;

case 'vulcan':
	define('PAIS', 'VULCAN');
	define('SQL', 'vulcan_');
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
define('RAIZ', '/home/teoriza/public_html/virtualpol_dev/');
define('HOST', $_SERVER['HTTP_HOST']);
define('VERSION', 'BETA 1.0');

// variables de tablas SQL
define('SQL_USERS', 'users');
define('SQL_REFERENCIAS', 'referencias');
define('SQL_MENSAJES', 'mensajes');
define('SQL_VOTOS', 'votos');
define('SQL_EXPULSIONES', 'expulsiones');

// variables del sistema de usuarios
define('USERCOOKIE', '.virtualpol.com');
define('CLAVE', ''); // clave de coockie (cambiar en caso de robo de claves md5)
define('REGISTRAR', 'http://www-dev.virtualpol.com/registrar/');


// funciones con passwords importantes
include('config-pwd.php');
?>