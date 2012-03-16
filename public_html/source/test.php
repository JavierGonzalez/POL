<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';



$result = mysql_query("SELECT ID FROM votacion WHERE estado = 'end' AND privacidad = 'true'", $link);
while($r = mysql_fetch_array($result)){ 
	$txt .= '|';
	//barajar_votos($r['ID']);
}


$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>