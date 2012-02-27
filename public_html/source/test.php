<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


for ($n=1;$n<10;$n++) {
	$txt .= pow($n, 2).' = '.(pow($n-1, 2)+($n+($n-1))).'<br/>';
}


include('theme.php');
?>