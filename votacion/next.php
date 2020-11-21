<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$url = '/votacion';
if (isset($pol['user_ID'])) {
	$result = sql_old("SELECT ID, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, (SELECT user_ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS ha_votado FROM votacion WHERE estado = 'ok' AND pais = '".PAIS."' ORDER BY num DESC");
	while($r = r($result)) { 
		if ((!$r['ha_votado']) AND (nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])) AND (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) { $url = '/votacion/'.$r['ID']; break; }
	}
}
redirect($url);