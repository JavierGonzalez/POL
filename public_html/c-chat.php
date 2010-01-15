<?php 
include('inc-login.php');
$adsense_exclude = true;

switch ($_GET['a']) {

case 'plaza-internacional':
	$pol['chat_id'] = 9;
	$pol['chat_nombre'] = 'Plaza Internacional';
	break;

	default: header('Location: http://'.HOST.'/'); break;
}


if (($_GET['a']) AND (isset($pol['chat_id']))) {

	include('source/inc-chat.php');
	
	$txt_title = 'CHAT: ' . $pol['chat_nombre'];


	// load config full
	$result = mysql_query("SELECT valor, dato FROM pol_config WHERE dato = 'pols_frase' OR dato = 'palabras' LIMIT 2", $link);
	while ($row = mysql_fetch_array($result)) { $media['POL'][$row['dato']] = $row['valor']; }


	$result = mysql_query("SELECT valor, dato FROM hispania_config WHERE dato = 'pols_frase' OR dato = 'palabras' LIMIT 2", $link);
	while ($row = mysql_fetch_array($result)) { $media['Hispania'][$row['dato']] = $row['valor']; }


	$txt .= '<br /><span class="amarillo" id="pols_frase"><b>'.$media['POL']['pols_frase'].'</b></span> (POL)<br /><br />';

	$txt .= '<span class="amarillo" id="pols_frase"><b>'.$media['Hispania']['pols_frase'].'</b></span> (Hispania)';


}


include('theme.php');
?>
