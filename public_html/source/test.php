<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


$txt .= '<br /><br />'.boton('Desactivado', $url=false, $confirm=false, $size='small', $pols=false);

$txt .= '<br /><br />'.boton('Google', $url='http://www.google.es/#ok', $confirm=false, $size='medium', $pols=false);

$txt .= '<br /><br />'.boton('Google confirm', $url='http://www.google.es/#ok', $confirm='¿Hola esto es una pruéba?', $size='large', $pols=false);

$txt .= '<br /><br />'.boton('Tamaño', $url='/', $confirm=false, $size='large', $pols=false);


$txt .= '<br /><br />'.boton('Pols', $url='/', $confirm=false, $size=false, $pols=1000);

$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>