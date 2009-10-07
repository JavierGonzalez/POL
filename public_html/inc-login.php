<?php

// LOGIN de www.virtualpol.com

include('config.php'); // config raiz
include('source/inc-functions.php'); // libreria de funciones basicas

// inicia el cronometro para medir el tiempo de carga
$mtime = explode(' ', microtime()); 
$tiempoinicial = $mtime[1] + $mtime[0]; 

$date = date('Y-m-d H:i:s'); // fija fecha actual $date en formato entendible por MySQL


$IP = direccion_IP('longip'); // obtiene la IP en formato numérico (longip)
$link = conectar(); // conecta MySQL

if (!isset($_SESSION)) { session_start(); } // inicia sesion PHP


// nucleo del sistema de usuarios, comienza la verificación
if (isset($_COOKIE['teorizauser'])) {
	$result = mysql_query("SELECT ID, pass, nick, estado, pols, pais, email, fecha_registro, rechazo_last FROM ".SQL_USERS." WHERE nick = '"  . trim($_COOKIE['teorizauser']) . "' LIMIT 1", $link);
	while ($row = mysql_fetch_array($result)) { 
		if (md5(CLAVE.$row['pass']) == $_COOKIE['teorizapass']) { // cookie pass OK
			$session_new = true;
			$pol['nick'] = $row['nick'];
			$pol['user_ID'] = $row['ID'];
			$pol['pols'] = $row['pols'];
			$pol['email'] = $row['email'];
			$pol['estado'] = $row['estado'];
			$pol['pais'] = $row['pais'];
			$pol['rechazo_last'] = $row['rechazo_last'];
			$pol['fecha_registro'] = $row['fecha_registro'];

			// variables perdurables en la sesion, solo se guarda el nick e ID de usuario
			$_SESSION['pol']['nick'] = $row['nick'];
			$_SESSION['pol']['user_ID'] = $row['ID'];
		}
	}  
}

?>