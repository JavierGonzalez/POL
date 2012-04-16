<?php
$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';

// Busca cargos con elecciones activas y en fecha de activar
$result = mysql_query("SELECT * FROM cargos WHERE pais = '".PAIS."' AND elecciones <= '".$date."' LIMIT 20", $link);
while($r = mysql_fetch_array($result)) {
	
	// Fija fecha de proximas elecciones (las siguientes)
	mysql_query("UPDATE cargos SET elecciones = '".date('Y-m-d 20:00:00', time()+($r['elecciones_cada']*24*60*60))."' WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' LIMIT 1", $link);

	// Obtiene numero de elecciones de este cargo (para numerarlas en orden)
	$result2 = mysql_query("SELECT COUNT(*) AS votaciones_num FROM votacion WHERE pais = '".PAIS."' AND tipo = 'elecciones' AND ejecutar LIKE 'elecciones|".$r['cargo_ID']."|%'", $link);
	while($r2 = mysql_fetch_array($result2)) { $elecciones_num = $r2['votaciones_num']; }
	$elecciones_num++;

	// Obtener candidatos
	$candidatos_nick = array(); $candidatos_ID = array();
	$result2 = mysql_query("SELECT user_ID, (SELECT nick FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS nick FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' AND aprobado = 'ok' LIMIT 100", $link);
	while($r2 = mysql_fetch_array($result2)) { $candidatos_nick[] = $r2['nick']; $candidatos_ID[] = $r2['user_ID']; }

	// Obtener numero maximo de votantes (num_censo)
	$result2 = mysql_query("SELECT COUNT(*) AS num FROM users WHERE pais = '".PAIS."' AND estado = 'ciudadano'", $link);
	while($r2 = mysql_fetch_array($result2)) { $votos_num = $r2['num']; }

	// Crear votacion, ya activada
	mysql_query("INSERT INTO votacion 
(pais, pregunta, descripcion, respuestas, respuestas_desc, time, time_expire, user_ID, estado, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, ejecutar, votos_expire, tipo_voto, privacidad, debate_url, aleatorio, duracion, num_censo, cargo_ID) 
VALUES (
'".PAIS."', 
'".$elecciones_num."&ordf; Elecciones a ".$r['nombre']."', 
'Elecciones periódicas y automáticas para el cargo <b>".$r['nombre']."</b>.<br /><br />
Realizadas cada <b>".$r['elecciones_cada']." días</b>, durante <b>".$r['elecciones_durante']." días</b>. ".($r['elecciones_electos']==1?"Será electo el candidato más votado":"Serán electos los <b>".$r['elecciones_electos']." candidatos más votados</b>").", de entre <b>".count($candidatos_nick)." candidatos</b>.', 
'En Blanco|".implode('|',$candidatos_nick)."|', 
'',
'".$date."', 
'".date('Y-m-d H:i:s', time()+($r['elecciones_durante']*24*60*60))."', 
'0', 
'ok', 
'elecciones',  
'".explodear('|', $r['elecciones_votan'], 0)."', 
'".explodear('|', $r['elecciones_votan'], 1)."', 
'anonimos', 
'', 
'elecciones|".$r['cargo_ID']."|".$r['elecciones_electos']."', 
'0', 
'5puntos', 
'true', 
'', 
'true', 
'".($r['elecciones_durante']*24*60*60)."',
".(is_numeric($votos_num)?$votos_num:'NULL').",
".$r['cargo_ID'].")", $link);

	// Imprime evento en el chat
	$result2 = mysql_query("SELECT ID FROM votacion WHERE pais = '".PAIS."' AND estado = 'ok' ORDER BY ID DESC LIMIT 1", $link);
	while($r2 = mysql_fetch_array($result2)) { 
		$votacion_ID = $r2['ID'];
		evento_chat('<b>[ELECCIONES]</b> <a href="/votacion/'.$r2['ID'].'"><b>Comienzan las elecciones a '.$r['nombre'].'</b></a>'); 
	}

	// Enviar emails.
	if (($r['asigna'] == 0) AND (explodear('|', $r['elecciones_votan'], 0) == 'ciudadanos')) {
		$result2 = mysql_query("SELECT nick, email FROM users WHERE pais = '".PAIS."' AND estado != 'expulsado'", $link);
		while($r2 = mysql_fetch_array($result2)){ 
			$mensaje = '<p>Hola '.$r2['nick'].':</p>

<p>Han comenzado las elecciones de '.PAIS.'. Tu participación es importante para el funcionamiento democrático.</p>

<p><a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/votacion/'.$votacion_ID.'"><b style="font-size:18px">Entrar a votar</b></a></p>

<p>VirtualPol | La primera red social democrática</p>';
			enviar_email(null, 'Comienzan las elecciones a '.$r['nombre'], $mensaje, $r2['email']); 
		}
	}
}
?>