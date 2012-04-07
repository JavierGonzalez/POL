<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';




while($r=$db->sql("SELECT * FROM users")){
	$guardar = $r['nick'];
}

$txt .= num((microtime(true)-TIME_START)*1000).'ms';





$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>