<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
function crono($new='') {
	 global $crono;
	 $the_ms = num((microtime(true)-$crono)*1000);
	 $crono = microtime(true);
	 return '<h3>'.$the_ms.'ms '.$new.'</h3></hr>';
}
$txt .= '<h1>TEST</h1><hr />';







$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>