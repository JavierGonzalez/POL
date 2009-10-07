<?php
include('config.php');
include('source/inc-functions.php');

//INIT MICROTIME
$mtime = explode(' ', microtime()); 
$tiempoinicial = $mtime[1] + $mtime[0]; 

$date = date('Y-m-d H:i:s');
$IP = direccion_IP('longip');
$link = conectar();

if (!isset($_SESSION)) { session_start(); }

if (isset($_COOKIE['teorizauser'])) { //NO existe sesion
	$result = mysql_query("SELECT ID, pass, nick, estado, pols, pais, email, fecha_registro, rechazo_last FROM ".SQL_USERS." WHERE nick = '"  . trim($_COOKIE['teorizauser']) . "' LIMIT 1", $link);
	while ($row = mysql_fetch_array($result)) { 
		if (md5(CLAVE.$row['pass']) == $_COOKIE['teorizapass']) { //pass OK
			$session_new = true;
			$pol['nick'] = $row['nick'];
			$pol['user_ID'] = $row['ID'];
			$pol['pols'] = $row['pols'];

			$pol['email'] = $row['email'];
			
			$_SESSION['pol']['nick'] = $row['nick'];
			$_SESSION['pol']['user_ID'] = $row['ID'];

			$pol['estado'] = $row['estado'];
			$pol['pais'] = $row['pais'];
			$pol['rechazo_last'] = $row['rechazo_last'];
			$pol['fecha_registro'] = $row['fecha_registro'];
		}
	}  
}

?>