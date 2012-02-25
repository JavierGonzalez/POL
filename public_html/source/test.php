<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';



mysql_query("UPDATE notificaciones SET texto = '¡Bienvenido!' WHERE texto = '&iexcl;bienvenido!'", $link);





include('theme.php');
?>