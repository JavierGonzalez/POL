<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';



mysql_query("DELETE FROM notificaciones WHERE time < '".date('Y-m-d 20:00:00', time() - 864000)."'", $link); // 




include('theme.php');
?>