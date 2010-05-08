<?php 

$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';


$_SERVER['HTTP_HOST'] = 'pol.virtualpol.com';


include($root_dir.'config.php');
include($root_dir.'source/inc-functions.php');
include($root_dir.'source/inc-functions-accion.php');
$link = conectar();


foreach ($vp['paises'] AS $pais) {
	evento_chat('<b>[PROCESO] Quedan <span style="color:#666;">5 minutos</span>...</b>', '0', 0, false, 'e', $pais);
}

mysql_close($link);

?>