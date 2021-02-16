<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


if (nucleo_acceso('supervisores_censo') OR $pol['nick']=='GONZO') {

	// extrae user_ID de SC
	foreach ($sc AS $user_ID => $nick) { $sc_user_ID[] = $user_ID; }

	$hosts_array = array(0=>'null');
	function IP2host($IP) {
		global $hosts_array;
		$longIP = ip2long($IP);
		if (!$hosts_array[$longIP]) {
			$hosts_array[$longIP] = @gethostbyaddr($IP);
		}
		return $hosts_array[$longIP];
	}

	foreach ($sc AS $user_ID => $nick) {
		$supervisores .= ' '.crear_link($nick); 
	}

	function print_nota_SC($nota_SC, $user_ID) {
		global $pol;
		return ($nota_SC!=''?'<form action="/acccion/SC/nota?ID='.$user_ID.'" method="post"><input type="text" name="nota_SC" size="25" maxlength="255" value="'.$nota_SC.'"'.(substr($nota_SC, 0, 7)=='Cuidado'?' style="color:red;"':'').(substr($nota_SC, 0, 12)=='Comparte con'?' style="color:green;"':'').(substr($nota_SC, 0, 3)=='OK '?' style="color:blue;"':'').' /> '.boton('OK', 'submit', false, 'small pill').'</form>':'');
	}

	// nomenclatura
	foreach ($vp['paises'] AS $pais) { $paises .= ' <span class="redondeado">'.$pais.'</span>'; }
	$nomenclatura = '<fieldset><legend>'._('Plataformas').'</legend>'.$paises.' | '._('Estados').': <b class="ciudadano">'._('Ciudadano').'</b> <b class="turista">'._('Turista').'</b> <b class="validar">'._('Validar').'</b> <b class="expulsado">'._('Expulsado').'</b></fieldset>';

	// siglas partidos
	$result = sql_old("SELECT ID, siglas FROM partidos WHERE pais = '".PAIS."'");
	while($r = r($result)) { $siglas[$r['ID']] = $r['siglas']; }

	$txt_tab = array('/control/supervisor-censo'=>_('Principal'), '/control/supervisor-censo/factores-secundarios'=>_('Extra'), '/control/supervisor-censo/nuevos-ciudadanos'=>_('Nuevos'), '/control/supervisor-censo/bloqueos'=>_('Bloqueos'), '/control/expulsiones'=>_('Expulsiones'));

	if ($_GET[1] == 'nuevos-ciudadanos') {

			$txt_title = _('Control').': SC | '._('Nuevos ciudadanos');
			$txt_nav = array('/control'=>_('Control'), '/control/supervisor-censo'=>'SC', _('Nuevos'));

			echo '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp;  '._('Supervisores del censo').': <b>'.$supervisores.'</b></p>'.$nomenclatura;

			echo '<h1>1. '._('Actividad de nuevos ciudadanos (últimos 60)').'</h1><hr />
<table border="0" cellspacing="0" cellpadding="2">
<tr>
<th></th>
<th></th>
<th align="right" colspan="2"><acronym title="Tiempo desde que se registró">'._('Registro').'</acronym></th>
<th align="right">Online</th>
<th align="right"><acronym title="Tiempo desde el ultimo acceso">'._('Último').'</acronym></th>
<th align="right"><acronym title="Plataforma">P</acronym></th>
<th align="center" colspan="2"><acronym title="Confianza de SC, actualizada en tiempo real">C_SC</acronym></th>
<th align="right"><acronym title="Visitas">V</acronym></th>
<th align="right"><acronym title="Paginas vistas">PV</acronym></th>
<th>IP</th>
<th></th>
<th align="right">'._('Email').'</th>
</tr>';
	$result = sql_old("SELECT *
FROM users 
ORDER BY fecha_registro DESC
LIMIT 60");
	while($r = r($result)) {
		$dia_registro = date('j', strtotime($r['fecha_registro']));
		
		$razon = '';
		if ($r['estado'] == 'expulsado') {
			$result2 = sql_old("SELECT razon FROM expulsiones WHERE user_ID = '".$r['ID']."' LIMIT 1");
			while ($r2 = r($result2)) { $razon = '<b style="color:red;">'.$r2['razon'].'</b> '; }
		}


		if ($r['online'] > 60) { $online = duracion($r['online']); } else { $online = ''; }
		if ($r['num_elec'] == '0') { $r['num_elec'] = ''; }

		if ($r['estado'] == 'expulsado') {
			$td_bg = ' style="background:#FFA6A6;"';
		} elseif ($r['visitas'] <= 1) { 
			$td_bg = ' style="background:#C0C0C0;"';
		} elseif ($r['visitas'] <= 2) {
			$td_bg = ' style="background:#D3D3D3;"';
		} elseif ($r['visitas'] <= 4) {
			$td_bg = ' style="background:#DCDCDC;"';
		} elseif ($r['visitas'] <= 6) {
			$td_bg = ' style="background:#EFEFEF;"';
		} else { $td_bg = ''; }
		
		if (!$r['voto_confianza_SC']) { $r['voto_confianza_SC'] = 0; }
		
		echo '<tr'.$td_bg.'>
<td><a href="/control/expulsiones/expulsar/'.$r['nick'].'" target="_blank" style="color:yellow;"><b>'._('Expulsar').'</b></a></td>
<td align="right"><b>'.$dia_registro.'</b></td>
<td><b>' . crear_link($r['nick'], 'nick', $r['estado']) . '</b></td>
<td align="right" nowrap="nowrap">'.timer($r['fecha_registro']).'</td>
<td align="right" nowrap="nowrap">'.$online.'</td>
<td align="right" nowrap="nowrap">'.timer($r['fecha_last']) . '</td>
<td nowrap="nowrap">' . $siglas[$r['partido_afiliado']] . '</td>
<td align="right"><span id="confianza'.$r['ID'].'">'.confianza($r['voto_confianza']).'</span></td>
<td align="right" nowrap="nowrap">'.($r['ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['ID'].'" class="votar" type="confianza" name="'.$r['ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>
<td align="right" nowrap="nowrap"><acronym title="'.$r['fecha_init'].'">'.$r['visitas'].'</acronym></td>
<td align="right">'.$r['paginas'].'</td>
<td>'.ocultar_IP($r['IP']).'</td>
<td nowrap="nowrap" align="right" style="font-size:10px;">'.ocultar_IP($r['host'], 'host').'</td>
<td align="right" style="font-size:10px;">'.$r['email'].'</td>
<td nowrap="nowrap" style="font-size:10px;">'.$razon.$r['nota_SC'].'</td>
</tr>';
		$dia_registro_last = $dia_registro;
	}
	echo '</table>';





} else if ($_GET[1] == 'bloqueos') {

	$txt_title = 'SC | '._('bloqueos');
	$txt_nav['/sc/bloqueos'] = _('Bloqueos');

	$result = sql_old("SELECT valor, dato FROM config WHERE PAIS IS NULL");
	while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }

	$backlists = array('backlist_IP'=>400, 'backlist_emails'=>180, 'backlist_nicks'=>120);

	echo '<form action="/accion/bloqueos" method="post">

<p>'._('Listas negras para bloquear masivamente con filtros. Un elemento por linea. Elementos de al menos 5 caracteres (para minimizar el riesgo de filtros masivos). Precaución, hay riesgo de producir bloqueos masivos').'.</p>
<table>
<tr>';

	foreach ($backlists AS $tipo => $width) {
		echo '<td><fieldset><legend>'.ucfirst(str_replace('_', ' ', $tipo)).'</legend>
	<textarea style="width:'.$width.'px;height:400px;white-space:nowrap;" name="'.$tipo.'">'.$pol['config'][$tipo]."\n".'</textarea></fieldset></td>';
	}
	
	echo '
</tr>

<tr>
<td colspan="'.count($backlists).'" align="center">'.boton(_('Guardar'), 'submit', '¿Estás seguro de activar estos BLOQUEOS?\n\nPRECAUCION: RIESGO DE BLOQUEOS MASIVOS INVOLUNTARIOS.', 'large red').'</td>
</tr>
</table>

</form>
';



} else if ($_GET[1] == 'confianza-mutua') {



	$txt_title = _('Control').': SC | '._('Confianza mutua');
	$txt_nav = array('/control'=>_('Control'), '/control/supervisor-censo'=>'SC', _('Confianza mutua'));

	echo '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp; '._('Supervisores del censo').': <b>' . $supervisores . '</b></p>'.$nomenclatura;


$data_amigos = array();
$data_enemigos = array();
$confianzas_amigos = array();

$result = sql_old("SELECT *,
(SELECT nick FROM users WHERE ID = votos.emisor_ID LIMIT 1) AS emisor_nick,
(SELECT nick FROM users WHERE ID = votos.item_ID LIMIT 1) AS item_nick
FROM votos
WHERE tipo = 'confianza' AND voto = '1'
ORDER BY RAND()");
while($r = r($result)) {
	$r['emisor_nick'] = substr($r['emisor_nick'], 0, 8);
	$r['item_nick'] = substr($r['item_nick'], 0, 8);

	if ($r['emisor_ID'] < $r['item_ID']) {
		$confianzas_amigos[$r['emisor_nick'].'--'.$r['item_nick']]++;
	} else {
		$confianzas_amigos[$r['item_nick'].'--'.$r['emisor_nick']]++;
	}
}

foreach ($confianzas_amigos AS $emisor_item => $num) { if ($num >= 2) { $data_amigos[] = $emisor_item; } }


$result = sql_old("SELECT *,
(SELECT nick FROM users WHERE ID = votos.emisor_ID LIMIT 1) AS emisor_nick,
(SELECT nick FROM users WHERE ID = votos.item_ID LIMIT 1) AS item_nick
FROM votos
WHERE tipo = 'confianza' AND voto = '-1'");
while($r = r($result)) {
	$r['emisor_nick'] = substr($r['emisor_nick'], 0, 8);
	$r['item_nick'] = substr($r['item_nick'], 0, 8);

	if ($r['emisor_ID'] < $r['item_ID']) {
		$confianzas_enemigos[$r['emisor_nick'].'--'.$r['item_nick']]++;
	} else {
		$confianzas_enemigos[$r['item_nick'].'--'.$r['emisor_nick']]++;
	}

}

foreach ($confianzas_enemigos AS $emisor_item => $num) { if ($num >= 2) { $data_enemigos[] = $emisor_item; } }



$gwidth = 500;
$gheight = 600;

echo '<h1>'._('Grafico confianza').'</h1>

<hr />

<table>
<tr>
<td>
<b>'._('Confianza mutua').' '.count($data_amigos).'</b><br />
<!--<img src="http://chart.googleapis.com/chart?cht=gv:neato&chs='.$gwidth.'x'.$gheight.'&chl=graph{'.implode(';', $data_amigos).'}" width="'.$gwidth.'" height="'.$gheight.'" alt="grafico confianza" /><br />-->
<img src="http://chart.googleapis.com/chart?cht=gv:twopi&chs='.$gwidth.'x'.$gheight.'&chl=graph{'.implode(';', $data_amigos).'}" width="'.$gwidth.'" height="'.$gheight.'" alt="grafico confianza" />
</td>

<td>
<b>'._('Desconfianza mutua').' '.count($data_enemigos).'</b><br />
<!--<img src="http://chart.googleapis.com/chart?cht=gv:neato&chs='.$gwidth.'x'.$gheight.'&chl=graph{'.implode(';', $data_enemigos).'}" width="'.$gwidth.'" height="'.$gheight.'" alt="grafico confianza" /><br />-->
<img src="http://chart.googleapis.com/chart?cht=gv:twopi&chs='.$gwidth.'x'.$gheight.'&chl=graph{'.implode(';', $data_enemigos).'}" width="'.$gwidth.'" height="'.$gheight.'" alt="grafico confianza" />
</td>
</tr>
</table>
';

} else if ($_GET[1] == 'factores-secundarios') {

	$txt_title = _('Control').': SC | '._('Extras');
	$txt_nav = array('/control'=>_('Control'), '/control/supervisor-censo'=>'SC', _('Extras'));

	echo '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp;  '._('Supervisores del censo').': <b>'.$supervisores.'</b></p>'.$nomenclatura;


	echo '<br /><h1>5. '._('Referencias').'</h1><hr /><table border="0" cellspacing="4">';
	$result = sql_old("SELECT ID, nick, ref, pais, ref_num, estado, partido_afiliado
FROM users 
WHERE ref_num != '0' 
ORDER BY ref_num DESC, fecha_registro DESC");
	while($r = r($result)) {
		$clones = '';
		$result2 = sql_old("SELECT ID, nick, ref, pais, estado, partido_afiliado
FROM users 
WHERE ref = '" . $r['ID'] . "'");
		while($r2 = r($result2)) { 
			if ($r2['nick']) { 
				if ($clones) { $clones .= ' & '; }
				$clones .= crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']) . '</b> ' . $siglas[$r2['partido_afiliado']] . '<b>';
			} 
		}
		if ($clones != '') { echo '<tr><td><b>' . crear_link($r['nick'], 'nick', $r['estado'], $r['pais']) . '</b> ' . $siglas[$r['partido_afiliado']] . '</td><td align="right"></td><td><b>' . $r['ref_num'] . '</b></td><td>(<b>' . $clones . '</b>)</td></tr>'; }
	}
	echo '</table>';


	$emails_whitelist = array(
'correo.ugr.es',
'gmail.com',
'googlemail.com',
'hotmail.com',
'hotmail.es',
'live.com',
'live.com.ar',
'live.com.mx',
'live.cl',
'movistar.es',
'msn.com',
'msn.es',
'ono.com',
'ozu.es',
'rocketmail.com',
'telefonica.es',
'telefonica.net',
'terra.es',
'vodafone.es',
'yahoo.com',
'yahoo.com.ar',
'yahoo.com.mx',
'yahoo.com.ve',
'yahoo.es',
'ymail.com',
'eresmas.com',
);


	echo '<br /><h1>6. '._('Emails atípicos').'</h1><hr /><table border="0" cellspacing="4">';
	$result = sql_old("SELECT email, nick, ref, ref_num, estado FROM users ORDER BY fecha_registro DESC");
	while($r = r($result)) {

		$r['email'] = strtolower($r['email']);
		$email = explode("@", $r['email']);

		if (!in_array($email[1], $emails_whitelist)) {
			$clones = '';
			$r['email'] = explodear("@", $r['email'], 1); 
			echo '<tr><td>' . crear_link($r['nick'], 'nick', $r['estado']) . '</td><td>*@<b>'.$r['email'].'</b></td></tr>';
		}
	}
	echo '</table>';


	echo '<br /><h1>7. '._('URLs de referencia').'</h1><hr /><table border="0" cellspacing="4">
<tr>
<th></th>
<th>Ref</th>
<th>'._('Nuevos').'</th>
<th>URL</th>
</tr>';
	$result = sql_old("SELECT user_ID, COUNT(*) AS num, referer,
(SELECT nick FROM users WHERE ID = referencias.user_ID LIMIT 1) AS nick,
(SELECT COUNT(*) FROM referencias WHERE referer = referencias.referer AND new_user_ID != '0') AS num_registrados
FROM referencias 
GROUP BY referer HAVING COUNT(*) > 1
ORDER BY num DESC");
	while($r = r($result)) {

		$result2 = sql_old("SELECT COUNT(*) AS num_registrados FROM referencias WHERE referer = '" . $r['referer'] . "' AND new_user_ID != '0'");
		while($r2 = r($result2)) {
			if ($r2['num_registrados'] != 0) { $num_registrados = '+' . $r2['num_registrados']; } else { $num_registrados = ''; }
		}
		if ($r['referer'] == '') { $r['referer'] = '#referencia-directa'; $r['nick'] = '&nbsp;'; }

		echo '<tr><td><b>' . crear_link($r['nick']) . '</b></td><td align="right"><b>' . $r['num'] . '</b></td><td align="right">' . $num_registrados . '</td><td><a href="' . $r['referer'] . '">' . $r['referer'] . '</a></td></tr>';
	}
	echo '</table>';



	echo '<br /><h1>8. '._('Más votos y menos actividad').'</h1><hr /><table border="0" cellspacing="4">
<tr>
<th></th>
<th><acronym title="Numero de elecciones">N</acronym></th>
<th></th>
<th>Online</th>
<th colspan="2"></th>
<th>V</th>
<th>PV</th>
<th></th>
</tr>';
	$result = sql_old("SELECT nick, IP, num_elec, estado, online, visitas, pais, paginas, ((num_elec * 100) / online) AS factor, partido_afiliado 
FROM users WHERE num_elec > 2 AND fecha_last > '".date('Y-m-d 20:00:00', time() - 2592000)."' ORDER BY factor DESC LIMIT 20");
	while($r = r($result)) {
		if ($r['factor'] > 0.0099) {
			echo '<tr><td>'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</td><td align="right"><b>'.$r['num_elec'].'</b></td><td>/</td><td align="right"><b>'.duracion($r['online']).'</b></td><td><b>=</b></td><td>'.$r['factor'].'</td><td align="right">'.$r['visitas'].'</td><td align="right">'.$r['paginas'].'</td><td>('.ocultar_IP($r['IP']).')</td></tr>';
		}
	}
	echo '</table>';


	echo '<br /><h1>9. '._('Navegadores').'</h1><hr />
<table border="0" cellspacing="4">';
	$result = sql_old("SELECT COUNT(*) AS num, nav
FROM users 
GROUP BY nav HAVING COUNT(*) > 1
ORDER BY num ASC");
	while($r = r($result)) {

		$clones = '';
		if ($r['num'] <= 30) {
			$result2 = sql_old("SELECT ID, nick, estado, pais FROM users WHERE nav = '" . $r['nav'] . "' ORDER BY fecha_registro DESC");
			while($r2 = r($result2)) {
				if ($clones) { $clones .= ' & '; }
				$clones .= crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']);
			}
		} else { $clones = '</b>('._('navegador muy común').')<b>'; }


		echo '<tr><td align="right"><b>'.$r['num'].'</b></td><td>'.$clones.'</td><td style="font-size:9px;">'.$r['nav'].'</td></tr>';
	}
	echo '</table>';


	echo '<br /><h1>10. '._('Más antigüedad y menos actividad').' (ALPHA)</h1><hr /><table border="0" cellspacing="4">
<tr>
<th></th>
<th>'._('Antigüedad').'</th>
<th></th>
<th>Online</th>
<th colspan="2"></th>
<th>V</th>
<th>PV</th>
<th></th>
</tr>';
	$result = sql_old("SELECT nick, IP, num_elec, estado, online, visitas, pais, paginas, fecha_registro,  ((CURRENT_TIMESTAMP() - TIMESTAMP(fecha_registro)) / online) AS factor 
FROM users  
WHERE online > 600
ORDER BY factor DESC LIMIT 30");
	while($r = r($result)) {
		echo '<tr><td>'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</td><td align="right"><b>'.duracion(time()-strtotime($r['fecha_registro'])).'</b></td><td>/</td><td align="right"><b>'.duracion($r['online']).'</b></td><td><b>=</b></td><td>'.round($r['factor']).'</td><td align="right">'.$r['visitas'].'</td><td align="right">'.$r['paginas'].'</td><td>('.ocultar_IP($r['IP']).')</td></tr>';
	}
	echo '</table>';


	} else { // principal

	$txt_title = _('Control').': SC';
	$txt_nav = array('/control'=>_('Control'), '/control/supervisor-censo'=>'SC');

	echo '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp; '._('Supervisores del censo').': <b>'.$supervisores.'</b></p>'.$nomenclatura;


	echo '<fieldset><legend>1. '._('Coincidencias de IP').' </legend><table border="0" cellspacing="4">';
	
	$result = sql_old("SELECT nick, IP, COUNT(*) AS num, host
FROM users 
GROUP BY IP HAVING COUNT(*) > 1
ORDER BY num DESC, IP ASC");
	while($r = r($result)) {
		$clones = array();
		$nota_SC = '';
		$clones_expulsados = true;
		$confianza_total = 0;
		$result2 = sql_old("SELECT ID, nick, estado, pais, partido_afiliado, nota_SC, 
(SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND item_ID = users.ID LIMIT 1) AS has_votado
FROM users 
WHERE IP = '" . $r['IP'] . "' 
ORDER BY fecha_registro DESC");
		while($r2 = r($result2)) {
			$nota_SC .= print_nota_SC($r2['nota_SC'], $r2['ID']);
			if ($r2['estado'] != 'expulsado') { $clones_expulsados = false; } 
			$clones[] = '<b>'.crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']).'</b>';
		}
		if (!$clones_expulsados) {
			echo '<tr><td>' . $r['num'] . '</td><td><span style="float:right;">'.ocultar_IP($r['host'], 'host').'</span>'.implode(' & ', $clones).'</td><td>'.ocultar_IP($r['IP']).'</td><td nowrap="nowrap">'.$nota_SC.'</td></tr>';
		}
	}
	echo '</table></fieldset>';




	echo '<fieldset><legend>2. '._('Coincidencias de clave').' </legend><table border="0" cellspacing="4">';
	$result = sql_old("SELECT ID, IP, COUNT(*) AS num, pass
FROM users 
GROUP BY pass HAVING COUNT(*) > 1
ORDER BY num DESC, fecha_registro DESC");
	while($r = r($result)) {
		if (($r['pass'] != 'mmm') OR ($r['pass'] != 'e10adc3949ba59abbe56e057f20f883e')) {

			$clones = array();
			$nota_SC = '';
			$confianza_total = 0;
			$result2 = sql_old("SELECT ID, nick, pais, partido_afiliado, estado, nota_SC
FROM users 
WHERE pass = '" . $r['pass'] . "'");
			$clones_expulsados = true;
			while($r2 = r($result2)) { 
				if ($r2['nick']) {
					$nota_SC .= print_nota_SC($r2['nota_SC'], $r2['ID']);
					if ($r2['estado'] != 'expulsado') { $clones_expulsados = false; } 
					$clones[] = crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']);
				} 
			}
			if (!$clones_expulsados) {
				echo '<tr><td>' . $r['num'] . '</td><td><b>'.implode(' & ', $clones).'</b></td><td nowrap="nowrap">'.$nota_SC.'</td></tr>';
			}
		}
	}
	echo '</table></fieldset>';




	$trazas_rep = array();
	echo '<fieldset><legend>3. '._('Coincidencia de traza').' </legend><table border="0" cellspacing="4">';
	$result = sql_old("SELECT ID AS user_ID, ID, nick, estado, pais, traza, nota_SC FROM users WHERE traza != '' ORDER BY fecha_registro DESC");
	while($r = r($result)) {
		$nota_SC .= print_nota_SC($r['nota_SC'], $r['ID']);
		$tn = 1;
		$trazas = explode(' ', $r['traza']);
		$trazas_clones = array();
		if ($r['estado'] == 'expulsado') { $mostrar = false; } else { $mostrar = true; }
		foreach ($trazas AS $unatraza) {
			$trazado = false;
			$result2 = sql_old("SELECT ID, nick, estado, pais, nota_SC FROM users WHERE ID = '".$unatraza."' LIMIT 1");
			while($r2 = r($result2)) {
				$nota_SC .= print_nota_SC($r2['nota_SC'], $r2['ID']);
				$tn++; $trazas_clones[] = crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']);
				$trazado = true;
				if ($r2['estado'] != 'expulsado') { $mostrar = true; }
			}
			if ($trazado == false) {
				$result2 = sql_old("SELECT tiempo AS nick FROM expulsiones WHERE user_ID = '".$unatraza."' LIMIT 1");
				while($r2 = r($result2)) {
					$r2['estado'] = 'expulsado';
					$tn++; $trazas_clones[] = crear_link($r2['nick'], 'nick', $r2['estado']);
				}
			}
		}
		if (($mostrar == true) AND (count($trazas_clones) > 0)) {
			echo '<tr><td>'.$tn.'</td><td><b>'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</b>: <b>'.implode(' & ', $trazas_clones).'</b></td><td>'.$nota_SC.'</td></tr>';
			$nota_SC = '';
		}
	}
	echo '</table></fieldset>';


	echo '<fieldset><legend>4. '._('Ocultación (proxys, TOR...)').'</legend><table border="0" cellspacing="4">';
	$array_searchtor = array('%anon%', '%tor%', '%vps%', '%vpn%', '%proxy%');
	$sql_anon = array();
	foreach ($array_searchtor AS $filtro) { $sql_anon[] = "hosts LIKE '".$filtro."' OR host LIKE '".$filtro."'"; }
	$result = sql_old("SELECT nick, estado, host, IP, nav, nota_SC FROM users WHERE ".implode(" OR ", $sql_anon)." ORDER BY fecha_registro DESC");
	while($r = r($result)) {
		echo '<tr><td><b>'.crear_link($r['nick'], 'nick', $r['estado']).'</b></td><td>'.ocultar_IP($r['IP']).'</td><td nowrap="nowrap"><b>'.ocultar_IP($r['host'], 'host').'</b></td><td style="font-size:10px;">'.$r['nav'].'</td><td nowrap="nowrap">'.print_nota_SC($r['nota_SC'], $r['ID']).'</td></tr>';
	}
	echo '</table><table border="0" cellspacing="4">';
	$result = sql_old("SELECT ID, IP, nick, estado, pais, IP_proxy, host
FROM users 
WHERE IP_proxy != ''
ORDER BY fecha_registro DESC");
	while($r = r($result)) {
		$proxys = '';
		$proxys_dns = '';
		$IP_anterior = '';
		$clones = '';
		$clones_num = 0;
		$proxys_num = '';
		$num = 1;
		$proxys_array = explode(', ', long2ip($r['IP']).', '.$r['IP_proxy']);

		foreach ($proxys_array AS $IP) {
			
			if (($IP > 0) || ($IP < 4294967295)) { $IP = long2ip($IP); }

			if (($IP_anterior != $IP) AND ($IP != '127.0.0.1') AND ($IP != '0.0.0.80') AND ($IP != '0.0.0.0') AND ($IP != '-1')) {
				$IP_anterior = $IP;
				
				//$host = IP2host($IP);
				$host = $r['host'];
				if ($host == $IP) { $host = '*'; }
				
				$proxys_num .= '<b>'.$num++.'.</b><br />';
				$proxys .= ocultar_IP(ip2long($IP)).'<br />';
				$proxys_dns .= ocultar_IP($host, 'host').'<br />';

				// clones	
				$result2 = sql_old("SELECT nick, estado, pais FROM users WHERE ID != '".$r['ID']."' AND (IP = '".ip2long($IP)."' OR IP_proxy LIKE '%".$IP."%') ORDER BY fecha_registro DESC");
				while($r2 = r($result2)) {
					$clones_num++;
					$clones .= crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']).' ';
				}
				$clones .= '<br />';
			}
		}

		if ($clones_num > 0) {
			if ($proxy_first != true) { echo '<tr><th></th><th colspan="3"><span style="float:right;">Hosts</span>Proxys</th></tr>'; $proxy_first = true; }
			echo '<tr>
<td valign="top"><b>' . crear_link($r['nick'], 'nick', $r['estado'], $r['pais']) . '</b></td>
<td valign="top">' . $proxys_num . '<hr /></td>
<td valign="top">' . $proxys . '<hr /></td>
<td valign="top" nowrap="nowrap" align="right">' . $proxys_dns . '<hr /></td>
<td valign="top">' . $clones . '<hr /></td>
</tr>';
		}

	}
	echo '</table></fieldset>';

	}

	} else { echo '<p class="amarillo" style="color:red;"><b>'._('Acceso restringido').'.</b></p>'; }