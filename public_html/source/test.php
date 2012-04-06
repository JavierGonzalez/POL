<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


exit;



foreach (array('15M', 'VP', 'Hispania', 'RSSV', 'POL', 'VULCAN', 'Atlantis') AS $pais) {


	$result = mysql_query("SELECT * 
FROM ".strtolower($pais)."_elec
ORDER BY time ASC", $link);
	while($r = mysql_fetch_array($result)) {


		if ($pais == '15M') {
			$r['nombre'] = 'Coordinador';
			$r['cargo_ID'] = 6;
			$r['elecciones_electos'] = 9;
		} elseif ($r['tipo'] == 'parl') {
			$r['nombre'] = 'Diputado';
			$r['cargo_ID'] = 6;
			$r['elecciones_electos'] = 7;
		} else {
			$r['nombre'] = 'Presidente';
			$r['cargo_ID'] = 7;
			$r['elecciones_electos'] = 1;
		}

		$r['elecciones_durante'] = 2;
		$r['elecciones_cada'] = 14;
	
	$candidatos_nick = array();
	$escrutinio = array();
	foreach (explode('|', $r['escrutinio']) AS $d) {
		if ((explodear(':', $d, 1) != 'I') AND (explodear(':', $d, 1) != '')) {
			$candidatos_nick[] = explodear(':', $d, 2);
			$escrutinio[] = explodear(':', $d, 2).'.'.explodear(':', $d, 0).'.'.explodear(':', $d, 1);
		}
	}
	$escrutinio = implode(':', $escrutinio);




			// Crear votacion, ya activada
		mysql_query("INSERT INTO votacion 
(pais, pregunta, descripcion, respuestas, respuestas_desc, time, time_expire, user_ID, estado, num, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, ejecutar, votos_expire, tipo_voto, privacidad, debate_url, aleatorio, duracion, num_censo, cargo_ID) 
VALUES (
'".$pais."', 
'".++$count[$pais]."&ordf; Elecciones a ".$r['nombre']."', 
'Elecciones periódicas y automáticas para el cargo <b>".$r['nombre']."</b>.<br /><br />
Realizadas cada <b>".$r['elecciones_cada']." días</b>, durante <b>".$r['elecciones_durante']." días</b>. ".($r['elecciones_electos']==1?"Será electo el candidato más votado":"Serán electos los <b>".$r['elecciones_electos']." candidatos más votados</b>").", de entre <b>".count($candidatos_nick)." candidatos</b>.<br /><br />
Estas elecciones fueron realizadas con el sistema antiguo de elecciones (vigente desde 2008 hasta 5 de Abril del 2012).', 
'En Blanco|".implode('|',$candidatos_nick)."|', 
'',
'".$r['time']."', 
'".date('Y-m-d H:i:s', strtotime($r['time'])+($r['elecciones_durante']*24*60*60))."', 
'0', 
'end', 
'".$r['num_votos']."',
'elecciones',  
'ciudadanos', 
'', 
'anonimos', 
'', 
'elecciones|".$r['cargo_ID']."|".$r['elecciones_electos']."|".$escrutinio."', 
'0', 
'estandar', 
'true', 
'', 
'true', 
'".($r['elecciones_durante']*24*60*60)."',
".$r['num_votantes'].",
".$r['cargo_ID'].")", $link);
	}


}







$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>