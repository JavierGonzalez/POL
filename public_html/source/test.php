<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';



$txt .= mysql_real_escape_string(nl2br("Empieza.

Termina.")).'<hr />';

$_POST['test'] = "Mensaje. Test. 

Ñé \"ok\" 'vale'. Fin.";

$_POST['test'] = nl2br($_POST['test']);

foreach ($_POST AS $nom => $val) { $_POST[$nom] = escape($val); }

$txt .= 'PRINT = '.$_POST['test'];



$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>