<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';




function escape2($a, $escape=true) {


	// Previene inyección SQL
	$a = nl2br($a);
	$a = str_replace('\'', '&#39;', $a);
	$a = str_replace('"', '&quot;', $a);
	if ($escape == true) { $a = mysql_real_escape_string($a); }

	// Previene XSS (inyección js)
	

	return $a;
}


$txt .= escape2("Linea uno.

Linea dos.");


$txt .= 'Inyección: '.$_GET['inyeccion'];



$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>