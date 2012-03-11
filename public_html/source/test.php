<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


mysql_query("UPDATE votacion_votos SET comprobante = NULL", $link);

$result = mysql_query("SELECT ID FROM votacion WHERE estado = 'ok'", $link);
while ($r = mysql_fetch_array($result)) {




}  



$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>