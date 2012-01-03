<?php

/* ALERTA CODIGO ANTIGUO. Lleva funcionando sin fallar 3 años 
(pros: funciona, contra: es codigo malo y antiguo)
*/


$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';


// arranque manual
if (!$pol['config']['elecciones']) {
	exit;

	include($root_dir.'config.php');
	include($root_dir.'source/inc-functions.php');
	include($root_dir.'source/inc-functions-accion.php');
	$link = conectar();

	// TIME MARGEN
	$date			= date('Y-m-d 20:00:00'); 					// ahora
	$margen_24h		= date('Y-m-d 20:00:00', time() - 86400);	// 24 h
	$margen_2dias	= date('Y-m-d 20:00:00', time() - 172800);	// 2 dias
	$margen_5dias	= date('Y-m-d 20:00:00', time() - 432000);	// 5 dias
	$margen_10dias	= date('Y-m-d 20:00:00', time() - 864000);	// 10 dias
	$margen_15dias	= date('Y-m-d 20:00:00', time() - 1296000); // 15 dias
	$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); // 30 dias
	$margen_60dias	= date('Y-m-d 20:00:00', time() - 5184000); // 60 dias
	$margen_90dias	= date('Y-m-d 20:00:00', time() - 7776000); // 90 dias

	// LOAD CONFIG $pol['config'][]
	$result = mysql_query("SELECT valor, dato FROM ".SQL."config", $link);
	while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }
}

/*
elecciones_estado  		elecciones
num_escanos 			3
elecciones_inicio 		2008-08-27 20:00:00
elecciones_duracion 	172800 2 dias
elecciones_frecuencia 	1036800 7+5 dias
elecciones_antiguedad 	86400 1 dia
elecciones				_pres pres1 pres2 _parl parl
*/


$elecciones_inicio_t = strtotime($pol['config']['elecciones_inicio']);
$elecciones_fin = $elecciones_inicio_t + $pol['config']['elecciones_duracion'];


// INICIA 2º VUELTA (mejorar)
if ($pol['config']['elecciones'] == 'pres1') {
	// cambia estado NEW
	mysql_query("UPDATE ".SQL."config SET valor = 'pres2' WHERE dato = 'elecciones' LIMIT 1", $link);
	// genera escrutinio primera vuelta
	$escrutinio = false;
	$nulo = false;
	$num_candidatos = 0;
	$candidatos = '';
	$result = mysql_query("SELECT ID_partido, ID, COUNT(ID_partido) AS num, 
(SELECT siglas FROM ".SQL."partidos WHERE ID = ".SQL."elecciones.ID_partido LIMIT 1) AS siglas
FROM ".SQL."elecciones
GROUP BY ID_partido
ORDER BY num DESC", $link);
	while($row = mysql_fetch_array($result)) {
		// elige los 2 candidatos mas votados en la primera vuelta
		if (($num_candidatos < 2) AND ($row['ID_partido'] > 0)) {
			$num_candidatos++;
			if ($candidatos) { $candidatos .= '|'; }
			$candidatos .= $row['ID_partido']; 
		}
		$nick = '';
		$result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS nick
FROM ".SQL."partidos_listas 
WHERE ID_partido = '" . $row['ID_partido'] . "' 
ORDER BY ID ASC LIMIT 1", $link);
		while($row2 = mysql_fetch_array($result2)){ $nick = $row2['nick']; $nick_ID = $row2['user_ID']; }
		if ($escrutinio) { $escrutinio .= '|'; }
		if ($row['ID_partido'] == 0) { $row['siglas'] = 'B'; }
		if ($row['ID_partido'] == -1) { $row['siglas'] = 'I'; $nulo = true; }
		$escrutinio .= $row['num'] . ':' . $row['siglas'] . ':' . $nick;
	}
	if ($nulo == false) { $escrutinio .= '|0:I:'; }
	$escrutinio = $candidatos . '#' . $escrutinio;

	// resetea numero de votos
	mysql_query("UPDATE ".SQL."elec SET escrutinio = '" . $escrutinio . "', num_votos = '0' ORDER BY time DESC LIMIT 1", $link);

	// evento chat
	evento_chat('<b>[ELECCIONES]</b> Fin de la 1&ordf; vuelta de las Elecciones Presidenciales.');
	evento_chat('<b>[ELECCIONES]</b> <a href="/elecciones/"><b>Comienza la 2&ordf; vuelta de las Elecciones Presidenciales, ya puedes votar de nuevo!</b></a>');

	// resetea votos
	mysql_query("DELETE FROM ".SQL."elecciones", $link);

	// envia emails
	$asunto = '['.PAIS.'] Comienza la Segunda Vuelta de las Elecciones Presidenciales de '.PAIS.'';
	$mensaje = 'Estimados ciudadanos de VirtualPol,<br /><br />Acaba de comenzar la Segunda Vuelta de las Elecciones Presidenciales, en las que tienes el derecho y deber de participar. Tu participacion es vital para que '.PAIS.' avance.<br /><br /><a href="http://'.strtolower(PAIS).'.virtualpol.com/"><b>http://'.PAIS.'.virtualpol.com/</b></a><br /><br />VirtualPol, plataforma democratica auto-gestionada<br />';
	$result = mysql_query("SELECT email FROM ".SQL_USERS." WHERE pais = '".PAIS."' AND estado != 'expulsado'", $link);
	while($row = mysql_fetch_array($result)){ enviar_email(null, $asunto, $mensaje, $row['email']); }
}





if (($pol['config']['elecciones_estado'] == 'normal') AND (time() >= ($elecciones_inicio_t - 1000))) { 
	// INICIO ELECCIONES


	// tipo elecciones
	$elec_now = substr($pol['config']['elecciones'], 1);
	if (ECONOMIA == false) {
		$empiezan_elecciones = 'Elecciones';
	} else if ($elec_now == 'pres') {
		$empiezan_elecciones = 'Elecciones Presidenciales (primera vuelta)';
	} elseif ($elec_now == 'parl') {
		$empiezan_elecciones = 'Elecciones Legislativas';
	}

	// limpia votos
	mysql_query("DELETE FROM ".SQL."elecciones", $link);

	// cambia estado OLD
	mysql_query("UPDATE ".SQL."config SET valor = 'elecciones' WHERE dato = 'elecciones_estado' LIMIT 1", $link);
	evento_chat('<b>[PROCESO]</b> <a href="/elecciones/"><b>Comienzan las ' . $empiezan_elecciones . '</b></a>');

	// calcula votantes
	$fecha_24_antes = date('Y-m-d H:i:s', strtotime($pol['config']['elecciones_inicio']) - $pol['config']['elecciones_antiguedad']);
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL_USERS." WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_registro < '" . $fecha_24_antes . "'", $link);
	while($row = mysql_fetch_array($result)) { $num_votantes = $row['num']; }

	// cambia estado NEW
	if ($elec_now == 'pres') { $elecciones_new = 'pres1'; } else { $elecciones_new = $elec_now; }
	mysql_query("UPDATE ".SQL."config SET valor = '" . $elecciones_new . "' WHERE dato = 'elecciones' LIMIT 1", $link);

	// crea inicio de elecciones
	mysql_query("INSERT INTO ".SQL."elec (time, tipo, num_votantes, escrutinio, num_votos, pols_init) VALUES ('" . date('Y-m-d 20:00:00') . "', '" . $elec_now . "', '" . $num_votantes . "', '', '0', '" . $st['pol_gobierno'] . "')", $link);

	// envia emails
	$asunto = '['.PAIS.'] Comienzan las ' . $empiezan_elecciones . ' de '.PAIS.'';
	$mensaje = 'Estimados ciudadanos de '.PAIS.',<br /><br />Acaban de comenzar las ' . $empiezan_elecciones . ', en las que tienes el derecho de participar. Tu voto es crucial para la democracia de '.PAIS.'.<br /><br /><a href="http://'.strtolower(PAIS).'.virtualpol.com/elecciones/"><b>http://'.PAIS.'.virtualpol.com/elecciones</b></a><br /><br />VirtualPol<br />http://'.strtolower(PAIS).'.virtualpol.com/';

	$result = mysql_query("SELECT email FROM ".SQL_USERS." WHERE pais = '".PAIS."' AND estado != 'expulsado'", $link);
	while($row = mysql_fetch_array($result)){ enviar_email(null, $asunto, $mensaje, $row['email']); }


} elseif (($pol['config']['elecciones_estado'] == 'elecciones') AND (time() > ($elecciones_fin - 1000))) { 
	// FIN ELECCIONES


	// ESCRUTINIO 80:GP:Pablo1|33:B:|26:MENEA:al00|26:FCL:NachE|14:CGA:Diver|11:UPR:Naaram|2:I:
	$escrutinio = false;
	$nulo = false;
	$presidente_electo_ID = false;
	$result = mysql_query("SELECT ID_partido, ID, COUNT(ID_partido) AS num, 
(SELECT siglas FROM ".SQL."partidos WHERE ID = ".SQL."elecciones.ID_partido LIMIT 1) AS siglas
FROM ".SQL."elecciones
GROUP BY ID_partido
ORDER BY num DESC", $link);
	while($row = mysql_fetch_array($result)) {
		$nick = '';
		$result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS nick
FROM ".SQL."partidos_listas 
WHERE ID_partido = '" . $row['ID_partido'] . "' 
ORDER BY ID ASC LIMIT 1", $link);
		while($row2 = mysql_fetch_array($result2)){ $nick = $row2['nick']; $nick_ID = $row2['user_ID']; }
		if ($escrutinio) { $escrutinio .= '|'; }
		if ($row['ID_partido'] == 0) { $row['siglas'] = 'B'; }
		if ($row['ID_partido'] == -1) { $row['siglas'] = 'I'; $nulo = true; }
		if ($pol['config']['elecciones'] == 'parl') {
			// falta de procesar escaños.
			$escrutinio .= $row['num'] . ':' . $row['siglas'] . ':0';
		} else {
			$escrutinio .= $row['num'] . ':' . $row['siglas'] . ':' . $nick;
			if (!$presidente_electo_ID) { $presidente_electo_ID = $nick_ID; $presidente_electo = $nick; }
		}

	}
	if ($nulo == false) { $escrutinio .= '|0:I:'; }
	mysql_query("UPDATE ".SQL."elec SET escrutinio = '" . $escrutinio . "' ORDER BY time DESC LIMIT 1", $link);


	if ($pol['config']['elecciones'] == 'pres2') { 
		// Presidenciales
		$elec_next = '_parl';

		// quita cargos de todo el Poder Ejecutivo
		$result2 = mysql_query("SELECT ID_estudio, user_ID FROM ".SQL."estudios_users WHERE cargo = '1' AND (ID_estudio = '7' OR ID_estudio = '16' OR ID_estudio = '19' OR ID_estudio = '23' OR ID_estudio = '27' OR ID_estudio = '26')", $link);
		while($row2 = mysql_fetch_array($result2)){
			cargo_del($row2['ID_estudio'], $row2['user_ID'], true, true);
		}

		// añade NUEVO presidente
		mysql_query("INSERT INTO ".SQL."estudios_users (ID_estudio, user_ID, time, estado, cargo, nota) VALUES ('7', '" . $presidente_electo_ID . "', '" . date('Y-m-d 20:00:00') . "', 'ok', '1', '')", $link);
		cargo_add(7, $presidente_electo_ID, true, true);

		evento_chat('<b>[ELECCIONES]</b> <a href="/elecciones/"><b>Elecciones Presidenciales FINALIZADAS</b> VIVA EL PRESIDENTE <b>' . crear_link($presidente_electo) . '</b>!!</a>');

	} elseif ($pol['config']['elecciones'] == 'parl') {
		// Parlamento
		if (ASAMBLEA) { $elec_next = '_parl'; } else { $elec_next = '_pres'; }


		// QUITA DIPUTADOS
		$r2 = mysql_query("SELECT user_ID FROM ".SQL."estudios_users WHERE ID_estudio = '6' AND cargo = '1'", $link);
		while($row2 = mysql_fetch_array($r2)){
			cargo_del(6, $row2['user_ID'], true, true);
		}




		// AÑADE DIPUTADOS
		$result = mysql_query("SELECT ID_partido FROM ".SQL."elecciones", $link);
		while($row = mysql_fetch_array($result)) {
			$votos_hechos = explode(".", $row['ID_partido']);
			foreach ($votos_hechos as $diputado_ID) { $votos[$diputado_ID]++; }
		}
		arsort($votos);
		reset($votos);
		
		$count = 0;
		$escrutinio = '';
		foreach ($votos as $diputado_ID => $votos_num) {
			$count++;
			
			$nick = '';
			$s_partido = '';
			$result = mysql_query("SELECT nick, voto_confianza, partido_afiliado,
(SELECT siglas FROM ".SQL."partidos WHERE ID = ".SQL_USERS.".partido_afiliado LIMIT 1) AS partido
FROM ".SQL_USERS." WHERE ID = '" . $diputado_ID . "' LIMIT 1", $link);
			while($row = mysql_fetch_array($result)) { 
				$nick = $row['nick'];
				$s_partido = $row['partido'];
			}

			if ($count <= $pol['config']['num_escanos']) { 
				cargo_add(6, $diputado_ID, true, true);
			}

			if ($escrutinio) { $escrutinio .= '|'; }
			$escrutinio .= $votos_num . ':' . $s_partido . ':' . $nick;
		}

		// 27:PD:nick|19:GP:3|13:B:0|10:PSD:1|8:SP:1|5:ANP:1|3:CS:1|3:NYS:0|2:PLHT:0|1:I:0|1:FA:0

		mysql_query("UPDATE ".SQL."elec SET escrutinio = '" . $escrutinio . "|0:I:0' ORDER BY time DESC LIMIT 1", $link);

		evento_chat('<b>[ELECCIONES] <a href="/elecciones/">Elecciones FINALIZADAS</a></b>');
	}

	// tiempo next elecciones
	$elecciones_next = date('Y-m-d 20:00:00', time() + $pol['config']['elecciones_frecuencia']);
	mysql_query("UPDATE ".SQL."config SET valor = '" . $elecciones_next . "' WHERE dato = 'elecciones_inicio' LIMIT 1", $link);

	// FIN ELEC
	mysql_query("UPDATE ".SQL."config SET valor = 'normal' WHERE dato = 'elecciones_estado' LIMIT 1", $link);
	mysql_query("UPDATE ".SQL."config SET valor = '" . $elec_next . "' WHERE dato = 'elecciones' LIMIT 1", $link);
}
?>
