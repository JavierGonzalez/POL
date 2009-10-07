<?php

// INICIALIZACION

// obtiene $host[0] que es el subdominio
$host = explode('.', $_SERVER['HTTP_HOST']);
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

// carga variables comunes del sistema
define('MONEDA', '<img src="/img/m.gif" border="0" />');
define('MONEDA_NOMBRE', 'POLs');

define('HOST', $_SERVER['HTTP_HOST']);
define('SQL_USERS', 'users');
define('SQL_REFERENCIAS', 'referencias');
define('SQL_MENSAJES', 'mensajes');
define('SQL_VOTOS', 'votos');
define('SQL_EXPULSIONES', 'expulsiones');

// variables del sistema de usuarios
define('USERCOOKIE', '.virtualpol.com');
define('CLAVE', ''); // clave de coockie (cambiar en caso de robo de claves md5)
define('VERSION', 'BETA 0.3');
define('RAIZ', '/home/teoriza/public_html/virtualpol.com/');
define('REGISTRAR', 'http://www.virtualpol.com/registrar/');

// arrancar la base de datos MySQL, configurar clave
function conectar() {
	$error_msg = '<h1>MySQL Error</h1><p>Lo siento, la base de datos no funciona temporalmente.</p>';
	if (!($l=@mysql_connect('localhost', 'teoriza_virtualp', 'SET_PASS'))) { echo $error_msg; exit; }
	if (!@mysql_select_db('teoriza_virtualpol', $l)) { echo $error_msg; exit; } 
	return $l;
}

?>