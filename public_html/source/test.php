<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


/*** MIGRACION A NUEVO SISTEMA DE CARGO. ***/

mysql_query("DELETE FROM notificaciones WHERE time < '".date('Y-m-d 20:00:00', time() - 864000)."'", $link);



$result = mysql_query("SELECT ID FROM users", $link);
while($r = mysql_fetch_array($result)){
	//actualizar('cargos', $r['ID']);
	//actualizar('examenes', $r['ID']);
}


$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>