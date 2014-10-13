<?php 

$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';


$_SERVER['HTTP_HOST'] = 'pol.virtualpol.com';


include($root_dir.'config.php');
include($root_dir.'source/inc-functions.php');
include($root_dir.'source/inc-functions-accion.php');
$link = conectar();


foreach ($vp['paises'] AS $pais) {
	evento_chat('<b>[#]</b> Quedan <b>5 minutos</b> para el proceso diario.', '0', 0, false, 'e', $pais);
}

mysql_close($link);

?>