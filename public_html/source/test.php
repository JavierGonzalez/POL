<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


/*** MIGRACION A NUEVO SISTEMA DE CARGO. ***/





$result = mysql_query("SELECT ID, pais FROM users WHERE estado = 'ciudadano' AND cargo = '13' AND pais = '15M'", $link);
while($r = mysql_fetch_array($result)){
	//mysql_query("INSERT INTO cargos_users (cargo_ID, pais, user_ID, time, aprobado, cargo, nota) VALUES ('13', '15M', '".$r['ID']."', '".$date."', 'ok', 'true', '0.0')", $link);
}


$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>