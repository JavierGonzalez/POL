<?php
$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';


// Busca cargos con elecciones activas y en fecha de activar
$result = mysql_query("SELECT * FROM cargos WHERE pais = '".PAIS."' AND elecciones <= '".$date."' LIMIT 20", $link);
while($r = mysql_fetch_array($result)) {
	
	// Fija fecha de proximas elecciones (las siguientes)
	mysql_query("UPDATE cargos SET elecciones = '".date('Y-m-d H:i:s', time()+($r['elecciones_cada']*24*60*60))."' WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' LIMIT 1", $link);

	// Obtiene candidatos.
	$candidatos_nick = array();
	$result2 = mysql_query("SELECT user_ID, (SELECT nick FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS nick FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' AND aprobado = 'ok' LIMIT 100", $link);
	while($r2 = mysql_fetch_array($result2)) { $candidatos_nick[] = $r2['nick']; }

	// Crear votacion, ya activada
	mysql_query("INSERT INTO votacion 
(pais, pregunta, descripcion, respuestas, respuestas_desc, time, time_expire, user_ID, estado, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, ejecutar, votos_expire, tipo_voto, privacidad, debate_url, aleatorio, duracion) 
VALUES (
'".PAIS."', 
'Elecciones a ".$r['nombre']."', 
'".$_POST['descripcion']."', 
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
'".($r['elecciones_durante']*24*60*60)."')", $link);

	// Imprime evento en el chat
	$result2 = mysql_query("SELECT ID FROM votacion WHERE pais = '".PAIS."' AND estado = 'ok' ORDER BY ID DESC LIMIT 1", $link);
	while($r2 = mysql_fetch_array($result2)) { 
		evento_chat('<b>[ELECCIONES]</b> <a href="/votacion/'.$r2['ID'].'"><b>Comienzan las elecciones a '.$r['nombre'].'</b></a>'); 
	}
}

?>