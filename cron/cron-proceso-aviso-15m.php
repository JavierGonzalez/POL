<?php 
$_SERVER['HTTP_HOST'] = 'pol.virtualpol.com';

include('../../config.php');
include('../inc-functions.php');
include('../inc-functions-accion.php');
$link = conectar();


foreach ($vp['paises'] AS $pais) {
	evento_chat('<b>[PROCESO] Quedan <span style="color:#666;">15 minutos</span>...</b>', '0', 0, false, 'e', $pais);
}

mysql_close($link);

?>