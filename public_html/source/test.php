<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';




print_r($vp['acceso']);

exit;


$vp_acceso['15M'] = array(
'votacion_borrador'=>	array('cargo', '6 25 67'),
'sondeo'=>				array('cargo', '6'),
'referendum'=>			array('cargo', '6'),
'parlamento'=>			array('cargo', '6'),
'kick'=>				array('cargo', '13'),
'kick_quitar'=>			array('cargo', '6 13'),
'foro_borrar'=>			array('cargo', '6 13'),
'control_gobierno'=>	array('cargo', '6'),
'control_sancion'=>		array('cargo', ''),
'control_grupos'=>		array('cargo', '6'),
'control_cargos'=>		array('cargo', '6'),
'examenes_decano'=>		array('cargo', '6'),
'examenes_profesor'=>	array('privado', ''),
'crear_partido'=>		array('cargo', '6'),
);


$vp_acceso['Hispania'] = array(
'votacion_borrador'=>	array('ciudadanos_global', ''),
'sondeo'=>				array('cargo', '41 6 16 22 19 7'),
'referendum'=>			array('nivel', '95'),
'parlamento'=>			array('cargo', '6 22'),
'kick'=>				array('cargo', '12 13 22 9'),
'kick_quitar'=>			array('cargo', '13 9 8'),
'foro_borrar'=>			array('cargo', '12 13'),
'control_gobierno'=>	array('cargo', '7 19'),
'control_sancion'=>		array('cargo', '9'),
'control_grupos'=>		array('cargo', '7 19'),
'control_cargos'=>		array('cargo', '7 19'),
'examenes_decano'=>		array('cargo', '35 60'),
'examenes_profesor'=>	array('cargo', '34'),
'crear_partido'=>		array('antiguedad','0'),
);

$vp_acceso['RSSV'] = array(
'votacion_borrador'=>	array('ciudadanos', ''),
'sondeo'=>				array('cargo', '6'),
'referendum'=>			array('cargo', '6'),
'parlamento'=>			array('cargo', '6'),
'kick'=>				array('cargo', '13 12'),
'kick_quitar'=>			array('cargo', '6 13 12'),
'foro_borrar'=>			array('cargo', '6 13 12'),
'control_gobierno'=>	array('cargo', '6'),
'control_sancion'=>		array('cargo', ''),
'control_grupos'=>		array('cargo', '6'),
'control_cargos'=>		array('cargo', '6'),
'examenes_decano'=>		array('cargo', '6'),
'examenes_profesor'=>	array('privado', ''),
'crear_partido'=>		array('cargo', '6'),
);



foreach (array('15M', 'Hispania', 'RSSV') AS $pais) {
	
	$la_config = array();
	foreach ($vp_acceso[$pais] AS $dato => $valor) { 
		$la_config[] = $dato.';'.$valor[0].':'.$valor[1];	
	}
	// dato->acceso = 'sondeo;123:123'
	mysql_query("INSERT INTO config (pais, dato, valor, autoload) VALUES ('".$pais."', 'acceso', '".implode('|', $la_config)."', 'si')", $link);
}







$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>