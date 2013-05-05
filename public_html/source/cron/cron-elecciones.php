<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';

// Busca cargos con elecciones activas y en fecha de activar
$result = sql("SELECT * FROM cargos WHERE pais = '".PAIS."' AND elecciones <= '".$date."' LIMIT 20");
while($r = r($result)) {
	
	// Fija fecha de proximas elecciones (las siguientes)
	sql("UPDATE cargos SET elecciones = '".date('Y-m-d 20:00:00', time()+($r['elecciones_cada']*24*60*60))."' WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' LIMIT 1");

	// Obtiene numero de elecciones de este cargo (para numerarlas en orden)
	$result2 = sql("SELECT COUNT(*) AS votaciones_num FROM votacion WHERE pais = '".PAIS."' AND estado = 'end' AND tipo = 'elecciones' AND cargo_ID = '".$r['cargo_ID']."'");
	while($r2 = r($result2)) { $elecciones_num = $r2['votaciones_num']; }
	$elecciones_num++;

	// Obtener candidatos
	$candidatos_nick = array(); $candidatos_ID = array();
	$result2 = sql("SELECT user_ID, (SELECT nick FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS nick FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' AND aprobado = 'ok' LIMIT 100");
	while($r2 = r($result2)) { $candidatos_nick[] = $r2['nick']; $candidatos_ID[] = $r2['user_ID']; }

	// Obtener numero máximo de votantes (num_censo)
	$result2 = sql("SELECT COUNT(*) AS num FROM users WHERE ".sql_acceso(explodear('|', $r['elecciones_votan'], 0), explodear('|', $r['elecciones_votan'], 1), PAIS));
	while($r2 = r($result2)) { $votos_num = $r2['num']; }

	$candidatos_num = count($candidatos_nick);

	if ($candidatos_num > 0) {
	
		// Crear votacion, ya activada
		sql("INSERT INTO votacion 
(pais, pregunta, descripcion, respuestas, respuestas_desc, time, time_expire, user_ID, estado, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, ejecutar, votos_expire, tipo_voto, privacidad, debate_url, aleatorio, duracion, num_censo, cargo_ID) 
VALUES (
'".PAIS."', 
'".$elecciones_num."&ordf; Elecciones a ".$r['nombre']."', 
'Elecciones periódicas y automáticas para el cargo <a href=\"/cargos/".$r['cargo_ID']."\"><img src=\"".IMG."cargos/".$r['cargo_ID'].".gif\" alt=\"".$r['nombre']."\" /> <b>".$r['nombre']."</b></a>.<br /><br />
Realizadas cada <b>".$r['elecciones_cada']." días</b>, durante <b>".$r['elecciones_durante']." días</b>. ".($r['elecciones_electos']==1?"Será electo el candidato más votado":"Serán electos los <b>".$r['elecciones_electos']." candidatos más votados</b>").", de entre <b>".count($candidatos_nick)." candidatos</b>.', 
'En Blanco|".implode('|',$candidatos_nick)."|', 
'',
'".$date."', 
'".(false&&$candidatos_num<=$r['elecciones_electos']?$date:date('Y-m-d H:i:s', time()+($r['elecciones_durante']*24*60*60)))."', 
'0', 
'ok', 
'elecciones',  
'".explodear('|', $r['elecciones_votan'], 0)."', 
'".explodear('|', $r['elecciones_votan'], 1)."', 
'anonimos', 
'', 
'elecciones|".$r['cargo_ID']."|".$r['elecciones_electos']."', 
'0', 
'".($r['cargo_ID']==6&&PAIS=='15M'?'8puntos':'5puntos')."', 
'true', 
'', 
'true', 
'".($r['elecciones_durante']*24*60*60)."',
".(is_numeric($votos_num)?$votos_num:'NULL').",
".$r['cargo_ID'].")");

		// Imprime evento en el chat
		$result2 = sql("SELECT ID FROM votacion WHERE pais = '".PAIS."' AND estado = 'ok' ORDER BY ID DESC LIMIT 1");
		while($r2 = r($result2)) { 
			$votacion_ID = $r2['ID'];
			evento_chat('<b>[ELECCIONES]</b> <a href="/votacion/'.$r2['ID'].'"><b>Comienzan las elecciones a '.$r['nombre'].'</b></a>'); 
		}

		// Enviar emails.
		if ($candidatos_num>$r['elecciones_electos'] AND $r['asigna']==0 AND in_array(explodear('|', $r['elecciones_votan'], 0), array('ciudadanos', 'ciudadanos_global'))) {
			$result2 = sql("SELECT nick, email FROM users WHERE pais = '".PAIS."' AND estado != 'expulsado'");
			while($r2 = r($result2)){ 
				$mensaje = '<p>Hola '.$r2['nick'].':</p>

<p>Han comenzado las elecciones de '.PAIS.'. Tu participación es importante para el funcionamiento democrático.</p>

<p><a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/votacion/'.$votacion_ID.'"><b style="font-size:18px">Entrar a votar</b></a></p>

<p>VirtualPol | La primera red social democrática</p>';
				enviar_email(null, 'Comienzan las elecciones a '.$r['nombre'], $mensaje, $r2['email']); 
			}
		}
	}
}
?>