<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


/*** MIGRACION A NUEVO SISTEMA DE CARGO. ***/





$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>