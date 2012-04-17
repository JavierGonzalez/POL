<?php

function conectar($nodie=false) { 
// nodie=false -> La instalacion hace include 
// de este fichero de configuracion una vez 
// modificado para comprobar 100% que ha sido 
// satisfactoria la configuracion y la base
// de datos funciona. Por esto se hace
// necesario evitar que la ejecucion muera
// con un exit; si la conexion no se
// realiza. De este modo conectar(true)
// evitara que la ejecucion termine con exit;
// Si no se especifica nada como parametro
// seguir'a funcionando como hasta ahora.

	$mysql_db = '...';
	$mysql_user = '...';
	$mysql_pass = '...';
	$mysql_host = '...';

	$error_msg = '<h1>MySQL Error</h1><p>Lo siento, la base de datos no funciona temporalmente.</p>';
	if (!($l=@mysql_connect($mysql_host, $mysql_user, $mysql_pass))) { echo $error_msg; if(!$nodie){exit;} }
	if (!@mysql_select_db($mysql_db, $l)) { echo $error_msg; if(!$nodie){exit;} }
	mysql_query("SET NAMES 'utf8'");
	return $l;
}

define('CLAVE', '...'); // clave de coockie (cambiar en caso de robo de claves md5)
define('CLAVE_SHA', '...'); // elemento concatenado a la contraseña para generar una clave en SHA256, guardado en el campo 'pass2' en la tabla 'users' (si se cambia se tendrán que resetear las claves)
define('CLAVE_DNIE', '...');
define('CLAVE_API_TRACTIS', '...'); 
define('CLAVE_API_ETHERPAD', '...'); 


$twitter_key = array(
'consumer_key'    => '...',
'consumer_secret' => '...',
'user_token'      => '...',
'user_secret'     => '...',
);


define('DEV', '');
?>
