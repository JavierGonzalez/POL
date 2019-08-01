<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/


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
define('CLAVE_SHA', '...'); // elemento concatenado a la contraseña para generar una clave en SHA256, guardado en el campo 'pass2' en la tabla 'users' (si se cambia se tendrán que resetear las claves)
define('CLAVE_DNIE', '...');
define('CLAVE_API_TRACTIS', '...'); 
define('CLAVE_API_ETHERPAD', '...'); 

define('FB_APIKEY', '...');
define('FB_SECRET', '...');
?>