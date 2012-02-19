<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';



$result = mysql_query("SELECT ID, descripcion FROM votacion", $link);
while($r = mysql_fetch_array($result)) {
	$txt .= 'votacion: '.$r['ID'].'<br />';
	
	$r['descripcion'] = str_replace('<b>', '[b]', $r['descripcion']);
	$r['descripcion'] = str_replace('</b>', '[/b]', $r['descripcion']);
	
	$r['descripcion'] = str_replace('<em>', '[em]', $r['descripcion']);
	$r['descripcion'] = str_replace('</em>', '[/em]', $r['descripcion']);

	$r['descripcion'] = str_replace('<i>', '[em]', $r['descripcion']);
	$r['descripcion'] = str_replace('</i>', '[/em]', $r['descripcion']);

	$r['descripcion'] = str_replace('<s>', '[s]', $r['descripcion']);
	$r['descripcion'] = str_replace('</s>', '[/s]', $r['descripcion']);

	$r['descripcion'] = strip_tags($r['descripcion'], '<br>');

	//mysql_query("UPDATE votacion SET descripcion = '".$r['descripcion']."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
}




include('theme.php');
?>