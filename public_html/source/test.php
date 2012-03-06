<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


$txt .= substr($_SERVER['REQUEST_URI'], 0, -1);


$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>