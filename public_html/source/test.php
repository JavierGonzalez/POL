<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


// Prevención de inyecciones varias
function escape_sql($a) {
	//$a = str_replace('\'', '&#39;', $a);
	//$a = str_replace('"', '&quot;', $a);
	return mysql_real_escape_string($a);
}
foreach ($_POST AS $nom => $val) { $_POST[$nom] = escape_sql($val); }
foreach ($_GET  AS $nom => $val) { $_GET[$nom] = escape_sql($val); }
foreach ($_REQUEST AS $nom => $val) { $_REQUEST[$nom] = escape_sql($val); }
foreach ($_COOKIE AS $nom => $val) { $_COOKIE[$nom] = escape_sql($val); }





$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>