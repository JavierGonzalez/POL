<?php

function conectar() {
	$mysql_db = '...';
	$mysql_user = '...';
	$mysql_pass = '...';

	$error_msg = '<h1>MySQL Error</h1><p>Lo siento, la base de datos no funciona temporalmente.</p>';
	if (!($l=@mysql_connect('localhost', $mysql_user, $mysql_pass))) { echo $error_msg; exit; }
	if (!@mysql_select_db($mysql_db, $l)) { echo $error_msg; exit; }
	mysql_query("SET NAMES 'utf8'");
	return $l;
}

define('CLAVE', '...'); // clave de coockie (cambiar en caso de robo de claves md5)
define('CLAVE_DNIE', '...');
define('CLAVE_API_TRACTIS', '...'); 
define('CLAVE_API_ETHERPAD', '...'); 

define('DEV', '');
?>