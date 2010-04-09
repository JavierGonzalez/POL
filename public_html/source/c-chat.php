<?php 
include('inc-login.php');
$adsense_exclude = true;
$pol['chat_accesos'] = false;

switch ($_GET['a']) {

case 'plaza':
	$pol['chat_id'] = 0;
	$pol['chat_nombre'] = 'Plaza de '.PAIS;


	//redireccion
	if ($_GET['b'] != 'm') { header('Location: http://'.strtolower(PAIS).'.virtualpol.com/'); exit; }

	break;

case 'parlamento':
	$pol['chat_id'] = 1;
	$pol['chat_nombre'] = 'Parlamento de '.PAIS;
	break;

case 'comisaria':
	$pol['chat_id'] = 2;
	$pol['chat_nombre'] = 'Comisaria de '.PAIS;
	break;

case 'tribunales':
	$pol['chat_id'] = 3;
	$pol['chat_nombre'] = 'Tribunales de '.PAIS;
	break;

case 'gobierno':
	$pol['chat_id'] = 4;
	$pol['chat_nombre'] = 'Gobierno de '.PAIS;
	break;

case 'hotel-arts':
	$pol['chat_id'] = 5;
	$pol['chat_nombre'] = 'Hotel Arts de '.PAIS;
	break;

case 'universidad':
	$pol['chat_id'] = 6;
	$pol['chat_nombre'] = 'Universidad de '.PAIS;
	break;

case 'antiguedad':
	if (strtotime($pol['fecha_registro']) < (time() - 2592000)) {
		$pol['chat_id'] = 7;
		$pol['chat_nombre'] = 'Antiguedad de '.PAIS;
	}
	break;

case 'anfiteatro':
	if (PAIS == 'POL') {
		$pol['chat_id'] = 8;
		$pol['chat_nombre'] = 'Anfiteatro (Club <a href="http://pol.virtualpol.com/foro/general/nacimiento-del-club-privado-de-debate-mmmmm-y-de-la-ongd-baobab/">mmmmm</a>)';
		$pol['chat_accesos'] = true;
		$pol['chat_accesos_list'] = array('Jazunzu', 'born', 'Sanchez', 'GONZO', 'dannnyql', 'selvatgi', 'fran');
	}
	break;
	
	
	default: header('Location: http://'.HOST.'/'); break;
}


if (($_GET['a']) AND (isset($pol['chat_id']))) {

	include('inc-chat.php');
	
	$txt_title = strip_tags('CHAT: ' . $pol['chat_nombre']);
}

if ($_GET['b'] == 'm') { include('theme-m.php'); } else { include('theme.php'); }

?>
