<?php 
include('inc-login.php');

function dato_nombre($dato, $tipo) {
	global $link;
	switch ($tipo) {
		case 'documento': $table = 'docs'; $result = 'url'; break;
		case 'partido': $table = 'partidos'; $result = 'siglas'; break;
		case 'cargo': $table = 'estudios'; $result = 'nombre'; break;
		case 'kick': $table = 'ban'; $result = 'expire'; break;
		case 'empresa': $table = 'empresas'; $result = 'nombre'; break;
	}
	$result2 = mysql_query("SELECT " . $result . " FROM ".SQL."" . $table . " WHERE ID = '" . $dato . "' LIMIT 1", $link);
	while($r2 = mysql_fetch_array($result2)){ return $r2[$result]; }
}


//if (!$_GET['a']) {

if ($_GET['b']) { 
	$result2 = mysql_query("SELECT ID FROM ".SQL_USERS." WHERE nick = '" . $_GET['b'] . "' LIMIT 1", $link);
	while($r2 = mysql_fetch_array($result2)){ $filtro_ID = 'WHERE user_ID = \'' . $r['ID'] . '\''; }
}



	paginacion('eventos', '/log-eventos/', null, $_GET['a'], null, '100');

	$txt .= '<h1>Log de Eventos:</h1>

<br />' . $p_paginas . '

<table border="0" cellspacing="0" cellpadding="0" class="pol_table">
<tr>
<th>Fecha</th>
<th>Quien</th>
<th>Acci&oacute;n</th>
</tr>';

	$result = mysql_query("SELECT 
time, accion, user_ID, user_ID2, dato,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."log.user_ID LIMIT 1) AS nick,
(SELECT nick FROM ".SQL_USERS." WHERE ".SQL."log.user_ID2 != '0' AND ID = ".SQL."log.user_ID2 LIMIT 1) AS nick2
FROM ".SQL."log " . $filtro_ID . "
ORDER BY time DESC
LIMIT " . $p_limit, $link);
	while($r = mysql_fetch_array($result)){
		$fecha = explodear(' ', $r['time'], 1);
		$fecha = explodear(':', $fecha, 0) . ':' . explodear(':', $fecha, 1);
		$fecha = '<acronym title="' . $r['time'] . '">' . $fecha . '</acronym>';

/*
#accion
1 x Turista creado
2 x Ciudadano creado (user_ID2)
3 x Partido creado (dato=siglas)
4 - Partido editado (dato=siglas)
5 x Partido eliminado (dato=siglas)
6 x Documento creado (dato=ID_documento)
7 x Documento editado (dato=ID_documento)
8 x Documento eliminado (dato=ID_documento)
9 x Afiliacion (dato=siglas)
10 x Estudio iniciado (dato=cargo_ID)
11 x Cargo asignado (user_ID2=user_ID, dato=cargo_ID)
12 x Cargo quitado (user_ID2=user_ID, dato=cargo_ID)
13 - Rechazar ciudadania
14 - Kick cancelado
15 - Ceder empresa (user_ID2=user_ID, dato=empresa_ID)
16 - Ceder propiedad (user_ID2=user_ID, dato=propiedad_ID)
*/
switch ($r['accion']) {
	case 1: $accion = 'Nuevo Turista'; break;
	case 2: $accion = 'Nuevo Ciudadano'; break;
	case 3: $accion = 'Partido creado ' . crear_link(dato_nombre($r['dato'] , 'partido'), 'partido'); break;
	case 4: $accion = 'Partido editado ' . crear_link(dato_nombre($r['dato'] , 'partido'), 'partido'); break;
	case 5: $accion = 'Partido eliminado ' . crear_link(dato_nombre($r['dato'] , 'partido'), 'partido'); break;
	case 6: $accion = 'Documento creado ' . crear_link(dato_nombre($r['dato'] , 'documento'), 'documento'); break;
	case 7: $accion = 'Documento editado ' . crear_link(dato_nombre($r['dato'] , 'documento'), 'documento'); break;
	case 8: $accion = 'Documento eliminado <em>/doc/' . dato_nombre($r['dato'] , 'documento') . '/</em>'; break;
	case 9:
		if ($r['dato'] == 0) {
			$accion = 'Afiliado a ning&uacute;n partido';
		} else {
			$accion = 'Afiliado al partido ' . crear_link(dato_nombre($r['dato'] , 'partido'), 'partido');
		}
		break;
	case 10: $accion = 'Estudiando <em>' . dato_nombre($r['dato'], 'cargo') . '</em>'; break;
	case 11: $accion = 'Cargo <em>' . dato_nombre($r['dato'], 'cargo') . '</em> asignado a ' . crear_link($r['nick2']); break;
	case 12: $accion = 'Cargo <em>' . dato_nombre($r['dato'], 'cargo') . '</em> quitado a ' . crear_link($r['nick2']); break;

	case 13: $accion = 'Rechaza la Ciudadania'; break;
	case 14:
		$fecha_hora_fin_kick = dato_nombre($r['dato'], 'kick');
		$accion = 'Cancelado el kick a ' . crear_link($r['nick2']) . '. Terminaba: ' . $fecha_hora_fin_kick; 
		break;
	case 15: $accion = 'Empresa <em>'.dato_nombre($r['dato'], 'empresa').'</em> cedida a '.crear_link($r['nick2']); case 16: $accion = 'Propiedad <em>#'.$r['dato'].'</em> cedida a '.crear_link($r['nick2']); break;
	default: $accion = $r['accion'];
}

		//Obtención de DATO

		$txt .= '<tr><td>' . $fecha . '</td><td>' . crear_link($r['nick']) . '</td><td>' . $accion . '.</td></tr>' . "\n";
	}
	$txt .= '</table>';

//}


//THEME
$txt_title = 'Log de Eventos';
include('theme.php');
?>
