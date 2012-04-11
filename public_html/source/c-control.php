<?php 
include('inc-login.php');


// load config full
$result = mysql_query("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'", $link);
while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

$sc = get_supervisores_del_censo();

switch ($_GET['a']) {


case 'supervisor-censo':

if (isset($sc[$pol['user_ID']])) {

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
		return ($nota_SC!=''?'<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=SC&b=nota&ID='.$user_ID.'" method="post"><input type="text" name="nota_SC" size="25" maxlength="255" value="'.$nota_SC.'" /> '.boton('OK', 'submit', false, 'small pill').'</form>':'');
	}

	// nomenclatura
	foreach ($vp['paises'] AS $pais) { $paises .= ' <span style="background:'.$vp['bg'][$pais].';" class="redondeado">'.$pais.'</span>'; }
	$nomenclatura = '<span style="float:right;">Plataformas:'.$paises.' | Estados: <b class="ciudadano">Ciudadano</b> <b class="turista">Turista</b> <b class="validar">Validar</b> <b class="expulsado">Expulsado</b></span>';

	// siglas partidos
	$result = mysql_query("SELECT ID, siglas FROM partidos WHERE pais = '".PAIS."'", $link);
	while($r = mysql_fetch_array($result)) { $siglas[$r['ID']] = $r['siglas']; }

	$txt_tab = array('/control/supervisor-censo'=>'Principal', '/control/supervisor-censo/factores-secundarios'=>'Extra', '/control/supervisor-censo/nuevos-ciudadanos'=>'Nuevos ciudadanos', '/control/expulsiones'=>'Expulsiones');

	if ($_GET['b'] == 'nuevos-ciudadanos') {

			$txt_title = 'Control: SC | Nuevos ciudadanos';
			$txt_nav = array('/control'=>'Control', '/control/supervisor-censo'=>'SC', 'Nuevos ciudadanos');

			$txt .= '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp;  Supervisores del Censo: <b>' . $supervisores . '</b></p>'.$nomenclatura;

			$txt .= '<h1>1. Actividad de nuevos Ciudadanos (ultimos 60)</h1><hr />
<table border="0" cellspacing="0" cellpadding="2">
<tr>
<th></th>
<th align="right" colspan="2"><acronym title="Tiempo desde que se registró">Registro</acronym></th>
<th align="right">Online</th>
<th align="right"><acronym title="Tiempo desde el ultimo acceso">Ultimo</acronym></th>
<th align="right"><acronym title="Plataforma">P</acronym></th>
<th align="right"><acronym title="Votos ejercidos en Elecciones">E</acronym></th>
<th align="center" colspan="2"><acronym title="Confianza de SC, actualizada en tiempo real">C_SC</acronym></th>
<th align="right"><acronym title="Visitas">V</acronym></th>
<th align="right"><acronym title="Paginas vistas">PV</acronym></th>
<th align="right"><acronym title="Mensajes en foro">F</acronym></th>
<th align="right"><acronym title="Mensajes privados enviados">P</acronym></th>
<th align="right">Email</th>
<th></th>
<th>IP</th>
</tr>';
	$result = mysql_query("SELECT *,
(SELECT COUNT(*) FROM mensajes WHERE envia_ID = users.ID) AS num_priv,
(SELECT COUNT(*) FROM ".SQL."foros_msg WHERE user_ID = users.ID) AS num_foro,
(SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '" . $pol['user_ID'] . "' AND item_ID = users.ID LIMIT 1) AS has_votado,
(SELECT SUM(voto) AS voto_total FROM votos WHERE tipo = 'confianza' AND item_ID = users.ID AND emisor_ID IN (".implode(',', $sc_user_ID).") LIMIT 1) AS voto_confianza_SC
FROM users 
ORDER BY fecha_registro DESC
LIMIT 60", $link);
	while($r = mysql_fetch_array($result)) {
		$dia_registro = date('j', strtotime($r['fecha_registro']));
		
		$razon = '';
		if ($r['estado'] == 'expulsado') {
			$result2 = mysql_query("SELECT razon FROM expulsiones WHERE user_ID = '".$r['ID']."' LIMIT 1", $link);
			while ($r2 = mysql_fetch_array($result2)) { $razon = '<b style="color:red;">'.$r2['razon'].'</b> '; }
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
		
		$txt .= '<tr' . $td_bg . '>
<td align="right"><b>' . $dia_registro . '</b></td>
<td style="background:'.$vp['bg'][$r['pais']].';"><b>' . crear_link($r['nick'], 'nick', $r['estado']) . '</b></td>
<td align="right" nowrap="nowrap">'.timer($r['fecha_registro']).'</td>
<td align="right" nowrap="nowrap">' . $online . '</td>
<td align="right" nowrap="nowrap">'.timer($r['fecha_last']) . '</td>
<td nowrap="nowrap">' . $siglas[$r['partido_afiliado']] . '</td>
<td align="right"><b>' . $r['num_elec'] . '</b></td>
<td align="right"><span id="confianza'.$r['ID'].'">'.confianza($r['voto_confianza_SC']).'</span></td>
<td align="right" nowrap="nowrap">'.($r['ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['ID'].'" class="votar" type="confianza" name="'.$r['ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>
<td align="right" nowrap="nowrap"><acronym title="' . $r['fecha_init'] . '">' . $r['visitas'] . '</acronym></td>
<td align="right">' . $r['paginas'] . '</td>
<td align="right">' . $r['num_foro'] . '</td>
<td align="right">' . $r['num_priv'] . '</td>
<td align="right" style="font-size:10px;">' . $r['email'] . '</td>
<td align="right" nowrap="nowrap" style="font-size:10px;">'.ocultar_IP($r['host'], 'host').'</td>
<td style="font-size:10px;">'.ocultar_IP($r['IP']).'</td>
<td nowrap="nowrap" style="font-size:10px;">'.$razon.$r['nota_SC'].'</td>
</tr>';
		$dia_registro_last = $dia_registro;
	}
	$txt .= '</table>';



} else if ($_GET['b'] == 'confianza-mutua') {



	$txt_title = 'Control: SC | Confianza mutua';
	$txt_nav = array('/control'=>'Control', '/control/supervisor-censo'=>'SC', 'Confianza mutua');

	$txt .= '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp;  Supervisores del Censo: <b>' . $supervisores . '</b></p>'.$nomenclatura;


$data_amigos = array();
$data_enemigos = array();
$confianzas_amigos = array();

$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = votos.emisor_ID LIMIT 1) AS emisor_nick,
(SELECT nick FROM users WHERE ID = votos.item_ID LIMIT 1) AS item_nick
FROM votos
WHERE tipo = 'confianza' AND voto = '1'
ORDER BY RAND()", $link);
while($r = mysql_fetch_array($result)) {
	$r['emisor_nick'] = substr($r['emisor_nick'], 0, 8);
	$r['item_nick'] = substr($r['item_nick'], 0, 8);

	if ($r['emisor_ID'] < $r['item_ID']) {
		$confianzas_amigos[$r['emisor_nick'].'--'.$r['item_nick']]++;
	} else {
		$confianzas_amigos[$r['item_nick'].'--'.$r['emisor_nick']]++;
	}

}

foreach ($confianzas_amigos AS $emisor_item => $num) {
	if ($num >= 2) { 
		$data_amigos[] = $emisor_item;
		//$txt .= $emisor_item.'<br />'; 
	}
}


$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = votos.emisor_ID LIMIT 1) AS emisor_nick,
(SELECT nick FROM users WHERE ID = votos.item_ID LIMIT 1) AS item_nick
FROM votos
WHERE tipo = 'confianza' AND voto = '-1'", $link);
while($r = mysql_fetch_array($result)) {
	$r['emisor_nick'] = substr($r['emisor_nick'], 0, 8);
	$r['item_nick'] = substr($r['item_nick'], 0, 8);

	if ($r['emisor_ID'] < $r['item_ID']) {
		$confianzas_enemigos[$r['emisor_nick'].'--'.$r['item_nick']]++;
	} else {
		$confianzas_enemigos[$r['item_nick'].'--'.$r['emisor_nick']]++;
	}

}

foreach ($confianzas_enemigos AS $emisor_item => $num) {
	if ($num >= 2) { 
		$data_enemigos[] = $emisor_item;
		//$txt .= $emisor_item.'<br />'; 
	}
}




$gwidth = 500;
$gheight = 600;

$txt .= '<h1>Grafico confianza</h1>

<hr />

<table>
<tr>
<td>
<b>Confianza mutua '.count($data_amigos).'</b><br />
<!--<img src="http://chart.googleapis.com/chart?cht=gv:neato&chs='.$gwidth.'x'.$gheight.'&chl=graph{'.implode(';', $data_amigos).'}" width="'.$gwidth.'" height="'.$gheight.'" alt="grafico confianza" /><br />-->
<img src="http://chart.googleapis.com/chart?cht=gv:twopi&chs='.$gwidth.'x'.$gheight.'&chl=graph{'.implode(';', $data_amigos).'}" width="'.$gwidth.'" height="'.$gheight.'" alt="grafico confianza" />
</td>

<td>
<b>Desconfianza mutua '.count($data_enemigos).'</b><br />
<!--<img src="http://chart.googleapis.com/chart?cht=gv:neato&chs='.$gwidth.'x'.$gheight.'&chl=graph{'.implode(';', $data_enemigos).'}" width="'.$gwidth.'" height="'.$gheight.'" alt="grafico confianza" /><br />-->
<img src="http://chart.googleapis.com/chart?cht=gv:twopi&chs='.$gwidth.'x'.$gheight.'&chl=graph{'.implode(';', $data_enemigos).'}" width="'.$gwidth.'" height="'.$gheight.'" alt="grafico confianza" />
</td>
</tr>
</table>
';

} else if ($_GET['b'] == 'factores-secundarios') {

	$txt_title = 'Control: SC | Extras';
	$txt_nav = array('/control'=>'Control', '/control/supervisor-censo'=>'SC', 'Extras');

	$txt .= '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp;  Supervisores del Censo: <b>' . $supervisores . '</b></p>'.$nomenclatura;


	$txt .= '<br /><h1>5. Referencias</h1><hr /><table border="0" cellspacing="4">';
	$result = mysql_query("SELECT ID, nick, ref, pais, ref_num, estado, partido_afiliado
FROM users 
WHERE ref_num != '0' 
ORDER BY ref_num DESC, fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$clones = '';
		$result2 = mysql_query("SELECT ID, nick, ref, pais, estado, partido_afiliado
FROM users 
WHERE ref = '" . $r['ID'] . "'", $link);
		while($r2 = mysql_fetch_array($result2)) { 
			if ($r2['nick']) { 
				if ($clones) { $clones .= ' & '; }
				$clones .= crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']) . '</b> ' . $siglas[$r2['partido_afiliado']] . '<b>';
			} 
		}
		if ($clones != '') { $txt .= '<tr><td><b>' . crear_link($r['nick'], 'nick', $r['estado'], $r['pais']) . '</b> ' . $siglas[$r['partido_afiliado']] . '</td><td align="right"></td><td><b>' . $r['ref_num'] . '</b></td><td>(<b>' . $clones . '</b>)</td></tr>'; }
	}
	$txt .= '</table>';


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


	$txt .= '<br /><h1>6. Emails atípicos</h1><hr /><table border="0" cellspacing="4">';
	$result = mysql_query("SELECT email, nick, ref, ref_num, estado FROM users ORDER BY fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {

		$r['email'] = strtolower($r['email']);
		$email = explode("@", $r['email']);

		if (!in_array($email[1], $emails_whitelist)) {
			$clones = '';
			$r['email'] = explodear("@", $r['email'], 1); 
			$txt .= '<tr><td>' . crear_link($r['nick'], 'nick', $r['estado']) . '</td><td>*@<b>'.$r['email'].'</b></td></tr>';
		}
	}
	$txt .= '</table>';


	$txt .= '<br /><h1>7. Referencias desde URLs</h1><hr /><table border="0" cellspacing="4">
<tr>
<th></th>
<th>Ref</th>
<th>Nuevos</th>
<th>URL de referencia</th>
</tr>';
	$result = mysql_query("SELECT user_ID, COUNT(*) AS num, referer,
(SELECT nick FROM users WHERE ID = referencias.user_ID LIMIT 1) AS nick,
(SELECT COUNT(*) FROM referencias WHERE referer = referencias.referer AND new_user_ID != '0') AS num_registrados
FROM referencias 
GROUP BY referer HAVING COUNT(*) > 1
ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)) {

		$result2 = mysql_query("SELECT COUNT(*) AS num_registrados FROM referencias WHERE referer = '" . $r['referer'] . "' AND new_user_ID != '0'", $link);
		while($r2 = mysql_fetch_array($result2)) {
			if ($r2['num_registrados'] != 0) { $num_registrados = '+' . $r2['num_registrados']; } else { $num_registrados = ''; }
		}
		if ($r['referer'] == '') { $r['referer'] = '#referencia-directa'; $r['nick'] = '&nbsp;'; }

		$txt .= '<tr><td><b>' . crear_link($r['nick']) . '</b></td><td align="right"><b>' . $r['num'] . '</b></td><td align="right">' . $num_registrados . '</td><td><a href="' . $r['referer'] . '">' . $r['referer'] . '</a></td></tr>';
	}
	$txt .= '</table>';



	$txt .= '<br /><h1>8. Más votos y menos actividad</h1><hr /><table border="0" cellspacing="4">
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
	$result = mysql_query("SELECT nick, IP, num_elec, estado, online, visitas, pais, paginas, ((num_elec * 100) / online) AS factor, partido_afiliado 
FROM users WHERE num_elec > 2 AND fecha_last > '".date('Y-m-d 20:00:00', time() - 2592000)."' ORDER BY factor DESC LIMIT 20", $link);
	while($r = mysql_fetch_array($result)) {
		if ($r['factor'] > 0.0099) {
			$txt .= '<tr><td>'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</td><td align="right"><b>'.$r['num_elec'].'</b></td><td>/</td><td align="right"><b>'.duracion($r['online']).'</b></td><td><b>=</b></td><td>'.$r['factor'].'</td><td align="right">'.$r['visitas'].'</td><td align="right">'.$r['paginas'].'</td><td>('.ocultar_IP($r['IP']).')</td></tr>';
		}
	}
	$txt .= '</table>';


	$txt .= '<br /><h1>9. Navegadores</h1><hr />
<table border="0" cellspacing="4">';
	$result = mysql_query("SELECT COUNT(*) AS num, nav
FROM users 
GROUP BY nav HAVING COUNT(*) > 1
ORDER BY num ASC", $link);
	while($r = mysql_fetch_array($result)) {

		$clones = '';
		if ($r['num'] <= 25) {
			$result2 = mysql_query("SELECT ID, nick, estado, pais FROM users WHERE nav = '" . $r['nav'] . "' ORDER BY fecha_registro DESC", $link);
			while($r2 = mysql_fetch_array($result2)) {
				if ($clones) { $clones .= ' & '; }
				$clones .= crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']);
			}
		} else { $clones = '</b>(navegador muy comun)<b>'; }


		$txt .= '<tr><td align="right"><b>'.$r['num'].'</b></td><td>'.$clones.'</td><td style="font-size:9px;">'.$r['nav'].'</td></tr>';
	}
	$txt .= '</table>';


	$txt .= '<br /><h1>10. Más antiguedad y menos online (ALPHA)</h1><hr /><table border="0" cellspacing="4">
<tr>
<th></th>
<th>Antiguedad</th>
<th></th>
<th>Online</th>
<th colspan="2"></th>
<th>V</th>
<th>PV</th>
<th></th>
</tr>';
	$result = mysql_query("SELECT nick, IP, num_elec, estado, online, visitas, pais, paginas, fecha_registro,  ((CURRENT_TIMESTAMP() - TIMESTAMP(fecha_registro)) / online) AS factor 
FROM users  
WHERE online > 600
ORDER BY factor DESC LIMIT 30", $link);
	while($r = mysql_fetch_array($result)) {
		$txt .= '<tr><td>'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</td><td align="right"><b>'.duracion(time()-strtotime($r['fecha_registro'])).'</b></td><td>/</td><td align="right"><b>'.duracion($r['online']).'</b></td><td><b>=</b></td><td>'.round($r['factor']).'</td><td align="right">'.$r['visitas'].'</td><td align="right">'.$r['paginas'].'</td><td>('.ocultar_IP($r['IP']).')</td></tr>';
	}
	$txt .= '</table>';


	} else { // principal

	$txt_title = 'Control: SC';
	$txt_nav = array('/control'=>'Control', '/control/supervisor-censo'=>'SC');

	$txt .= '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp;  Supervisores del Censo: <b>'.$supervisores.'</b></p>'.$nomenclatura;


	$txt .= '<h1>1. Coincidencias de IP<span style="float:right;">('.round((microtime(true)-TIME_START)*1000).'ms)</span></h1><hr /><table border="0" cellspacing="4">';
	$result = mysql_query("SELECT nick, IP, COUNT(*) AS num, host
FROM users 
GROUP BY IP HAVING COUNT(*) > 1
ORDER BY num DESC, fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$clones = array();
		$nota_SC = '';
		$desarrollador = false;
		$clones_expulsados = true;
		$confianza_total = 0;
		$result2 = mysql_query("SELECT ID, nick, estado, pais, partido_afiliado, nota_SC, 
(SELECT SUM(voto) AS voto_total FROM votos WHERE tipo = 'confianza' AND item_ID = users.ID AND emisor_ID IN (".implode(',', $sc_user_ID).") LIMIT 1) AS voto_confianza_SC, 
(SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND item_ID = users.ID LIMIT 1) AS has_votado
FROM users 
WHERE IP = '" . $r['IP'] . "' 
ORDER BY fecha_registro DESC", $link);
		while($r2 = mysql_fetch_array($result2)) {
			$nota_SC .= print_nota_SC($r2['nota_SC'], $r2['ID']);
			$confianza_total += $r2['voto_confianza_SC'];
			if ($r2['estado'] != 'expulsado') { $clones_expulsados = false; } 
			$clones[] = '<b>'.crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']).'</b>';
		}
		if ((!$desarrollador) AND (!$clones_expulsados)) {
			$txt .= '<tr><td>' . $r['num'] . '</td><td>'.confianza($confianza_total).'</td><td><span style="float:right;">'.ocultar_IP($r['host'], 'host').'</span>'.implode(' & ', $clones).'</td><td>'.ocultar_IP($r['IP']).'</td><td nowrap="nowrap">'.$nota_SC.'</td></tr>';
		}
	}
	$txt .= '</table>';




	$txt .= '<br /><h1>2. Coincidencia de clave<span style="float:right;">('.round((microtime(true)-TIME_START)*1000).'ms)</span></h1><hr /><table border="0" cellspacing="4">';
	$result = mysql_query("SELECT ID, IP, COUNT(*) AS num, pass
FROM users 
GROUP BY pass HAVING COUNT(*) > 1
ORDER BY num DESC, fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
		if (($r['pass'] != 'mmm') OR ($r['pass'] != 'e10adc3949ba59abbe56e057f20f883e')) {

			$clones = array();
			$nota_SC = '';
			$confianza_total = 0;
			$result2 = mysql_query("SELECT ID, nick, pais, partido_afiliado, estado, nota_SC, (SELECT SUM(voto) AS voto_total FROM votos WHERE tipo = 'confianza' AND item_ID = users.ID AND emisor_ID IN (".implode(',', $sc_user_ID).") LIMIT 1) AS voto_confianza_SC
FROM users 
WHERE pass = '" . $r['pass'] . "'", $link);
			$clones_expulsados = true;
			while($r2 = mysql_fetch_array($result2)) { 
				if ($r2['nick']) {
					$nota_SC .= print_nota_SC($r2['nota_SC'], $r2['ID']);
					$confianza_total += $r2['voto_confianza_SC'];
					if ($r2['estado'] != 'expulsado') { $clones_expulsados = false; } 
					$clones[] = crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']);
				} 
			}
			if (!$clones_expulsados) {
				$txt .= '<tr><td>' . $r['num'] . '</td><td>'.confianza($confianza_total).'</td><td><b>'.implode(' & ', $clones).'</b></td><td nowrap="nowrap">'.$nota_SC.'</td></tr>';
			}
		}
	}
	$txt .= '</table>';




	$trazas_rep = array();
	$txt .= '<br /><h1>3. Coincidencia de dispositivo (Traza)<span style="float:right;">('.round((microtime(true)-TIME_START)*1000).'ms)</span></h1><hr /><table border="0" cellspacing="4">';
	$result = mysql_query("SELECT ID AS user_ID, ID, nick, estado, pais, traza, nota_SC FROM users WHERE traza != '' ORDER BY fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$nota_SC .= print_nota_SC($r['nota_SC'], $r['ID']);
		$tn = 1;
		$trazas = explode(' ', $r['traza']);
		$trazas_clones = array();
		if ($r['estado'] == 'expulsado') { $mostrar = false; } else { $mostrar = true; }
		foreach ($trazas AS $unatraza) {
			$trazado = false;
			$result2 = mysql_query("SELECT ID, nick, estado, pais, nota_SC FROM users WHERE ID = '".$unatraza."' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)) {
				$nota_SC .= print_nota_SC($r2['nota_SC'], $r2['ID']);
				$tn++; $trazas_clones[] = crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']);
				$trazado = true;
				if ($r2['estado'] != 'expulsado') { $mostrar = true; }
			}
			if ($trazado == false) {
				$result2 = mysql_query("SELECT tiempo AS nick FROM expulsiones WHERE user_ID = '".$unatraza."' LIMIT 1", $link);
				while($r2 = mysql_fetch_array($result2)) {
					$r2['estado'] = 'expulsado';
					$tn++; $trazas_clones[] = crear_link($r2['nick'], 'nick', $r2['estado']);
				}
			}
		}
		if (($mostrar == true) AND (count($trazas_clones) > 0)) {
			$txt .= '<tr><td>'.$tn.'</td><td><b>'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</b>: <b>'.implode(' & ', $trazas_clones).'</b></td><td>'.$nota_SC.'</td></tr>';
			$nota_SC = '';
		}
	}
	$txt .= '</table>';


	$txt .= '<br /><h1>4. Ocultación de conexión (proxys, TOR...)<span style="float:right;">('.round((microtime(true)-TIME_START)*1000).'ms)</span></h1><hr /><table border="0" cellspacing="4">';
	$array_searchtor = array('%anon%', '%tor%', '%vps%', '%proxy%');
	$sql_anon = '';
	foreach ($array_searchtor AS $filtro) { if ($sql_anon != '') { $sql_anon .= ' OR ';  } $sql_anon .= "hosts LIKE '".$filtro."'"; }
	$result = mysql_query("SELECT nick, estado, host, IP, nav, nota_SC FROM users WHERE ".$sql_anon." ORDER BY fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$txt .= '<tr><td><b>'.crear_link($r['nick'], 'nick', $r['estado']).'</b></td><td>'.ocultar_IP($r['IP']).'</td><td><b>'.ocultar_IP($r['host'], 'host').'</b></td><td style="font-size:10px;">'.$r['nav'].'</td><td nowrap="nowrap">'.print_nota_SC($r['nota_SC'], $r['ID']).'</td></tr>';
	}
	$txt .= '</table>';

	$txt .= '<table border="0" cellspacing="4">';
	$result = mysql_query("SELECT ID, IP, nick, estado, pais, IP_proxy, host
FROM users 
WHERE IP_proxy != ''
ORDER BY fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
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
				$result2 = mysql_query("SELECT nick, estado, pais FROM users WHERE ID != '".$r['ID']."' AND (IP = '".ip2long($IP)."' OR IP_proxy LIKE '%".$IP."%') ORDER BY fecha_registro DESC", $link);
				while($r2 = mysql_fetch_array($result2)) {
					$clones_num++;
					$clones .= crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']).' ';
				}
				$clones .= '<br />';
			}
		}

		if ($clones_num > 0) {
			if ($proxy_first != true) { $txt .= '<tr><th></th><th colspan="3"><span style="float:right;">Hosts</span>Saltos de proxys</th></tr>'; $proxy_first = true; }
			$txt .= '<tr>
<td valign="top"><b>' . crear_link($r['nick'], 'nick', $r['estado'], $r['pais']) . '</b></td>
<td valign="top">' . $proxys_num . '<hr /></td>
<td valign="top">' . $proxys . '<hr /></td>
<td valign="top" align="right">' . $proxys_dns . '<hr /></td>
<td valign="top">' . $clones . '<hr /></td>
</tr>';
		}

	}
	$txt .= '</table><span style="float:right;">('.round((microtime(true)-TIME_START)*1000).'ms)</span>';

	}

	} else { $txt .= '<p class="amarillo" style="color:red;"><b>Acceso restringido a los Supervisores del Censo.</b></p>'; }
	break;




case 'gobierno':
	$txt_title = 'Control: Gobierno';
	$txt_nav = array('/control'=>'Control', '/control/gobierno'=>'Gobierno');
	$txt_tab = array('/control/gobierno'=>'Gobierno', '/control/gobierno/notificaciones'=>'Notificaciones', '/control/gobierno/foro'=>'Configuración foro');

	if (nucleo_acceso($vp['acceso']['control_gobierno'])) { $dis = null; } else { $dis = ' disabled="disabled"'; }

	$result = mysql_query("SELECT (SELECT nick FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS elnick
	 FROM cargos_users WHERE cargo_ID = '7' AND cargo = 'true' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) { $presidente = $r['elnick']; }

	$result = mysql_query("SELECT (SELECT nick FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS elnick
	 FROM cargos_users WHERE cargo_ID = '19' AND cargo = 'true' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) { $vicepresidente = $r['elnick']; }

	$defcon_bg = array('1' => 'white','2' => 'red','3' => 'yellow','4' => 'green','5' => 'blue');



	if ($_GET['b'] == 'notificaciones') {
		
		$txt_nav = array('/control'=>'Control', '/control/gobierno'=>'Gobierno', 'Notificaciones');
		
		$txt .= '<h1 class="quitar"><a href="/control">Control</a>: <a href="/control/gobierno">Gobierno</a> | Notificaciones</h1>
		
<br />

<form action="/accion.php?a=gobierno&b=notificaciones&c=add" method="post">

<table border="0">
<tr>
<td>Texto: </td>
<td><input type="text" name="texto" value="" size="54" maxlength="50" /></td>
</tr>
<tr>
<td>URL: </td>
<td><input type="text" name="url" value="" size="64" maxlength="60" /> (si no cabe usa un acortador)</td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="Crear notificación"'.(nucleo_acceso($vp['acceso']['control_gobierno'])?'':' disabled="disabled"').' /> <span style="color:red;"><b>Cuidado.</b> Lo recibirán todos los ciudadanos de '.PAIS.'.</span></td>
</tr>
</table>


<hr />

</form>

<table border="0" cellspacing="0" cellpadding="4" class="pol_table">


<tr>
<th>Cuando</th>
<th>Mensaje</th>
<th>Emitidas</th>
<th colspan="2">Leídas/clics</th>
<th></th>
</tr>';
		$result = mysql_query("SELECT *, COUNT(*) AS num FROM notificaciones WHERE emisor = '".PAIS."' GROUP BY emisor, texto ORDER BY time DESC", $link);
		while($r = mysql_fetch_array($result)){

			$leido = 0;
			$result2 = mysql_query("SELECT COUNT(*) AS num FROM notificaciones WHERE texto = '".$r['texto']."' AND visto = 'true'", $link);
			while($r2 = mysql_fetch_array($result2)){ $leido = $r2['num']; }

			$txt .= '<tr>
<td align="right">'.timer($r['time']).'</td>
<td><a href="'.$r['url'].'">'.$r['texto'].'</a></td>
<td align="right"><b>'.$r['num'].'</b></td>
<td align="right">'.$leido.'</td>
<td align="right">'.num($leido*100/$r['num'], 2).'%</td>
<td>'.(nucleo_acceso($vp['acceso']['control_gobierno'])?boton('X', '/accion.php?a=gobierno&b=notificaciones&c=borrar&noti_ID='.$r['noti_ID'], false, 'small'):boton('X', false, false, 'small')).'</td>
</tr>';
		}

		$txt .= '</table>';

	} elseif ($_GET['b'] == 'foro') {
		
		$txt_nav = array('/control'=>'Control', '/control/gobierno'=>'Gobierno', 'Configuración foro');

		$txt .= '<h1 class="quitar"><a href="/control/">Control</a>: <a href="/control/gobierno/">Gobierno</a> | Control Foro</h1>
		
<br />
<form action="/accion.php?a=gobierno&b=subforo" method="post">

<table border="0" cellspacing="0" cellpadding="4" class="pol_table">

<tr>
<th colspan="2"></th>
<th colspan="3" align="center" style="background:#CCC;">Acceso</th>
<th colspan="2"></th>
</tr>

<tr>
<th>Orden</th>
<th>Foro/Descripcion</th>
<th style="background:#5CB3FF;">Leer</th>
<th style="background:#F97E7B;">Crear Hilos</th>
<th style="background:#F97E7B;">Responder Mensajes</th>
<th title="Numero de hilos mostrados en la home del foro">Mostrar</th>
<th></th>
</tr>';
	$subforos = '';
	$result = mysql_query("SELECT *,
(SELECT COUNT(*) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_hilos,
(SELECT SUM(num) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_msg
FROM ".SQL."foros WHERE estado = 'ok'
ORDER BY time ASC", $link);
	while($r = mysql_fetch_array($result)){

		if ($r['num_hilos'] == 0) { $del = '<br /><input style="margin-bottom:-16px;" type="button" value="Eliminar" onClick="window.location.href=\'/accion.php?a=gobierno&b=eliminarsubforo&ID=' . $r['ID'] . '/\';">';
		} else { $del = ''; }



		$txt_li['leer'] = ''; $txt_li['escribir'] = ''; $txt_li['escribir_msg'] = '';
		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['leer'] .= '<option value="'.$at.'"'.($at==$r['acceso_leer']?' selected="selected"':'').'>'.ucfirst(str_replace("_", " ", $at)).'</option>';
		}
		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['escribir'] .= '<option value="'.$at.'"'.($at==$r['acceso_escribir']?' selected="selected"':'').($at=='anonimos'?' disabled="disabled"':'').'>'.ucfirst(str_replace("_", " ", $at)).'</option>';
		}

		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['escribir_msg'] .= '<option value="'.$at.'"'.($at==$r['acceso_escribir_msg']?' selected="selected"':'').($at=='anonimos'?' disabled="disabled"':'').'>'.ucfirst(str_replace("_", " ", $at)).'</option>';
		}


		$txt .= '<tr>
<td align="right"><input type="text" style="text-align:right;" name="'.$r['ID'].'_time" size="1" maxlength="3" value="'.$r['time'].'" /></td>
<td><a href="/foro/'.$r['url'].'/"><b>'.$r['title'].'</b></a><br />
<input type="text" name="'.$r['ID'].'_descripcion" size="25" maxlength="100" value="'.$r['descripcion'].'" /></td>


<td style="background:#5CB3FF;"><b><select name="'.$r['ID'].'_acceso_leer">'.$txt_li['leer'].'</select><br />
<input type="text" name="'.$r['ID'].'_acceso_cfg_leer" size="16" maxlength="900" value="'.$r['acceso_cfg_leer'].'" /></td>

<td style="background:#F97E7B;"><b><select name="'.$r['ID'].'_acceso_escribir">'.$txt_li['escribir'].'</select><br />
<input type="text" name="'.$r['ID'].'_acceso_cfg_escribir" size="16" maxlength="900" value="'.$r['acceso_cfg_escribir'].'" /></td>

<td style="background:#F97E7B;"><b><select name="'.$r['ID'].'_acceso_escribir_msg">'.$txt_li['escribir_msg'].'</select><br />
<input type="text" name="'.$r['ID'].'_acceso_cfg_escribir_msg" size="16" maxlength="900" value="'.$r['acceso_cfg_escribir_msg'].'" /></td>


<td align="right"><input type="text" style="text-align:right;" name="'.$r['ID'].'_limite" size="1" maxlength="2" value="'.$r['limite'].'" /></td>

<td align="right" style="color:#999;" nowrap="nowrap">'.number_format($r['num_hilos'], 0, ',', '.').' hilos<br />
'.number_format($r['num_msg'], 0, ',', '.').' mensajes'.$del.'</td>
</tr>'."\n";

		if ($subforos) { $subforos .= '.'; }
		$subforos .= $r['ID'];
	}

		$txt .= '
<input name="subforos" value="'.$subforos.'" type="hidden" />
<tr>
<td align="center" colspan="8"><input value="Guardar cambios" style="font-size:22px;" type="submit"'.$dis.' /></td>
</tr>
</table>
</form>

<br />

<form action="/accion.php?a=gobierno&b=crearsubforo" method="post">
<table border="0" cellspacing="3" cellpadding="0" class="pol_table">
<tr>
<td class="amarillo"colspan="7"><b class="big">Crear nuevo foro</b></td>
</tr>

<tr>
<td>Nombre:</td>
<td><input type="text" name="nombre" size="10" maxlength="15" value="" /></td>
<td><input value="Crear subforo" style="font-size:18px;" type="submit"'.$dis.' /></td>
</tr>

</table>
</form>';
	} else {



	$defcon = '<select name="defcon"'.$dis.' style="font-size:25px;color:grey;">';
	for ($i=5;$i>=1;$i--) {
		if ($i == $pol['config']['defcon']) { $sel = ' selected="selected"'; } else { $sel = ''; }
		$defcon .= '<option value="' . $i . '" style="background:' . $defcon_bg[$i] . ';"' . $sel . '>' . $i . '</option>';
	}
	$defcon .= '</select>';


$txt_header .= '
<script type="text/javascript">
function change_bg(img) {
	$("#header").css("background","#FFFFFF url(\''.IMG.'bg/"+img+"\') repeat top left");
}

$(function() {
	$("#fondos").hover(
		function(e){
			change_bg($(this).val()); },
		function(e){
			change_bg($(this).val());
		}
	);
});

</script>';





	$txt .= '<h1 class="quitar"><a href="/control/">Control</a>: Gobieno | <a href="/control/gobierno/foro/">Control Foro</a>  <a href="/control/gobierno/notificaciones">Notificaciones</a></h1>

<br />
<form action="/accion.php?a=gobierno&b=config" method="post">

<table border="0" cellspacing="3" cellpadding="0" class="pol_table"><tr><td valign="top">

<table border="0" cellspacing="3" cellpadding="0" class="pol_table">


<tr><td align="right">Descripcion:</td><td><input type="text" name="pais_des" size="24" maxlength="40" value="'.$pol['config']['pais_des'].'"'.$dis.' /></td></tr>
<tr><td align="right">DEFCON:</td><td>' . $defcon . '</td></tr>
<tr><td align="right">Referencia tras:</td><td><input style="text-align:right;" type="text" name="online_ref" size="3" maxlength="10" value="' . round($pol['config']['online_ref']/60) . '"'.$dis.' /> min online (' . duracion($pol['config']['online_ref'] + 1) . ')</td></tr>
<tr><td align="right">Escaños:</td><td><input style="text-align:right;" type="text" name="num_escanos" size="3" maxlength="10" value="' . $pol['config']['num_escanos'] . '"'.$dis.' /> Diputados</td></tr>';

$palabra_gob = explode(':', $pol['config']['palabra_gob']);

$sel_exp = '';
$sel_exp[$pol['config']['examenes_exp']] = ' selected="selected"';

$txt .= '
<tr><td align="right" valign="top"><acronym title="Mensaje Global">Mensaje Global</acronym>:</td><td align="right">
<input type="text" name="palabra_gob0" size="24" maxlength="200" value="' . $palabra_gob[0] . '"'.$dis.' /><br />
http://<input type="text" name="palabra_gob1" size="19" maxlength="200" value="' . $palabra_gob[1] . '"'.$dis.' /></td></tr>

<tr><td align="right"><acronym title="Tiempo de vigencia maxima de un examen">Caducidad Examenes</acronym>:</td><td>
<select name="examenes_exp"'.$dis.'>
<option value="7776000"' . $sel_exp['7776000'] . '>3 meses</option>
<option value="5184000"' . $sel_exp['5184000'] . '>2 meses</option>
<option value="2592000"' . $sel_exp['2592000'] . '>1 mes</option>
<option value="1296000"' . $sel_exp['1296000'] . '>15 dias</option>
</select>';



$txt .= '

<tr><td align="right">Expiración chats:</td><td><input type="text" name="chat_diasexpira" size="2" maxlength="6" value="'.$pol['config']['chat_diasexpira'].'"'.$dis.' /> <acronym title="Dia inactivos">Dias</acronym></td></tr



<tr><td colspan="2"><br /><b>Diseño:</b></td></tr>
<tr><td align="right">Imagen tapiz:</td>
<td>
<select id="fondos" name="bg">
<option value="">Por defecto</option>';

$sel2[$pol['config']['bg']] = ' selected="selected"';

$directorio = opendir(RAIZ.'/img/bg/'); 
while ($archivo = readdir($directorio)) {
	if (($archivo != 'borrados') AND ($archivo != '.') AND ($archivo != '..') AND (substr($archivo,0,1) != '.') AND ($archivo != 'index.php')) {
		$txt .= '<option value="'.$archivo.'"'.$sel2[$archivo].' onclick="change_bg(\''.$archivo.'\')"  onmouseover="change_bg(\''.$archivo.'\')">'.$archivo.'</option>';
	}
}
closedir($directorio); 



$txt .= '</select>
</tr>

</td></tr></table>


<table border="0"'.(ECONOMIA?'':' style="display:none;"').'>

<tr><td colspan="2"></td></tr>

<tr><td colspan="2" class="amarillo"><b class="big">Economía</b> '.MONEDA.'</td></tr>



<tr><td align="right">Inem'.PAIS.':</td><td><input style="text-align:right;" class="pols" type="text" name="pols_inem" size="3" maxlength="6" value="' . $pol['config']['pols_inem'] . '"'.$dis.' /> '.MONEDA.' por día activo</td></tr>
<tr><td align="right">Referencia:</td><td><input style="text-align:right;" class="pols" type="text" name="pols_afiliacion" size="3" maxlength="6" value="' . $pol['config']['pols_afiliacion'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Crear empresa:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_empresa" size="3" maxlength="6" value="' . $pol['config']['pols_empresa'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Crear cuenta bancaria:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_cuentas" size="3" maxlength="6" value="' . $pol['config']['pols_cuentas'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Crear partido:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_partido" size="3" maxlength="6" value="' . $pol['config']['pols_partido'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Hacer examen:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_examen" size="3" maxlength="6" value="' . $pol['config']['pols_examen'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right"><acronym title="Mensaje privado a todos los Ciudadanos.">Mensaje Global</acronym>:</td><td><input style="text-align:right;" type="text" name="pols_mensajetodos" size="3" maxlength="6" class="pols" value="' . $pol['config']['pols_mensajetodos'] . '"'.$dis.' /> '.MONEDA.' (minimo '.pols(300).')</td></tr>
<tr><td align="right">Mensaje urgente:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_mensajeurgente" size="3" maxlength="6" value="' . $pol['config']['pols_mensajeurgente'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Crear chat:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_crearchat" size="3" maxlength="6" value="' . $pol['config']['pols_crearchat'] . '"'.$dis.' /> '.MONEDA.'</td></tr>

<tr><td colspan="2"><br /><b>Internacional:</b></td></tr>
<tr><td align="right">Arancel de salida:</td><td><input style="text-align:right;" type="text" name="arancel_salida" size="3" maxlength="6" value="' . $pol['config']['arancel_salida'] . '"'.$dis.' /><b>%</b></td></tr>


<tr><td colspan="2"><br /><b>Impuestos diarios:</b></td></tr>
<tr><td align="right"><acronym title="Porcentaje que se impondrá al patrimonio de cada ciudadano que supere el limite. Se redondea. Incluye cuentas y personal.">Impuesto de patrimonio</acronym>:</td><td><input style="text-align:right;" type="text" name="impuestos" size="3" maxlength="6" value="' . $pol['config']['impuestos'] . '"'.$dis.' /><b>%</b></td></tr>
<tr><td align="right"><acronym title="Limite minimo de patrimonio para recibir impuestos.">Minimo patrimonio</acronym>:</td><td><input class="pols" style="text-align:right;" type="text" name="impuestos_minimo" size="3" maxlength="6" value="' . $pol['config']['impuestos_minimo'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right"><acronym title="Impuesto fijo diario por cada empresa.">Impuesto de empresa</acronym>:</td><td><input class="pols" style="text-align:right;" type="text" name="impuestos_empresa" size="3" maxlength="6" value="' . $pol['config']['impuestos_empresa'] . '"'.$dis.' /> '.MONEDA.'</td></tr>



<tr><td colspan="2"><br /><b>Mapa:</b></td></tr>
<tr><td align="right">Precio de un solar:</td><td><input style="text-align:right;" class="pols" type="text" name="pols_solar" size="3" maxlength="6" value="' . $pol['config']['pols_solar'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Factor de propiedad:</td><td><input style="text-align:right;" type="text" name="factor_propiedad" size="3" maxlength="6" value="' . $pol['config']['factor_propiedad'] . '"'.$dis.' /> * superficie = coste</td></tr>
';

$sel = '';
$sel[$pol['config']['frontera']] = ' selected="selected"';

	$txt .= '<tr><td colspan="2"></td></tr></table>


</td><td valign="top">


<table border="0" cellspacing="3" cellpadding="0" class="pol_table"'.(ECONOMIA?'':' style="display:none;"').'>

<tr><td colspan="2" class="amarillo"><b class="big">Salarios</b></td></tr>';


	$result = mysql_query("SELECT nombre, cargo_ID, salario
FROM cargos
WHERE pais = '".PAIS."'
ORDER BY salario DESC", $link);
	while($r = mysql_fetch_array($result)){
		$txt .= '<tr><td align="right">' . $r['nombre'] . ':</td><td><input style="text-align:right;" type="text" name="salario_' . $r['cargo_ID'] . '" size="3" maxlength="6" class="pols" value="' . $r['salario'] . '"'.$dis.' /> '.MONEDA.'</td></tr>';
	}




	$txt .= '
</table>

</td></tr></table>

<!--<table border="0" cellspacing="3" cellpadding="0" class="pol_table">

<tr><td colspan="2" class="amarillo"><b class="big">Emoticonos</b></td></tr>
<tr><td><a href="'.IMG.'smiley/roto2.gif"><p>roto2</p></a></td><td><input type="checkbox" value="roto2" /></td>
</table>-->



<p style="text-align:center;">'.boton('EJECUTAR', ($dis?false:'submit'), false, 'large red').'</p>

</form>
<br/>
<form action="/accion.php?a=vaciar_listas" method="POST">
<table border="0" cellspacing="3" cellpadding="0" class="pol_table">
<tr>
<td class="amarillo"colspan="7"><b class="big">Listas electorales</b></td>
</tr>

<tr>';

$elecciones_dias_quedan = ceil((strtotime($pol['config']['elecciones_inicio']) - time()) / 86400);
$elecciones_frecuencia_dias = ceil($pol['config']['elecciones_frecuencia'] / 86400);
if (($elecciones_dias_quedan <= 5) OR ($elecciones_dias_quedan == $elecciones_frecuencia_dias)) {
	 $dis = ' disabled="disabled"'; 
}

$txt .= '
<td><input type="hidden" name="pais" value="'.$pol['pais'].'" /><p><input type="submit" value="Vaciar listas electorales" onclick="if (!confirm(\'&iquest;Seguro que quieres VACIAR LAS LISTAS ELECTORALES?\')) { return false; }"'.$dis.' /></td>
</tr>

</table>
</form>
';

}
	break;






case 'expulsiones':

if ($_GET['b'] == 'expulsar') { // /control/expulsiones/expulsar

	$txt_title = 'Control:  Expulsiones | Expulsar';
	$txt_nav = array('/control'=>'Control', '/control/expulsiones'=>'Expulsiones', 'Expulsar');


	if (isset($sc[$pol['user_ID']])) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }
	$txt .= '<h1 class="quitar"><a href="/control/">Control</a>: <img src="'.IMG.'varios/expulsar.gif" alt="Expulsion" border="0" /> <a href="/control/expulsiones/">Expulsiones</a> | Expulsar</h1>

<p>Las expulsiones son efectuadas por los Supervisores del Censo (SC), consiste en un bloqueo definitivo a un usuario y su puesta en proceso de eliminación forzada tras 5 dias, durante este periodo es reversible. Las expulsiones se aplican por incumplimiento las <a href="http://www.'.DOMAIN.'/TOS">Condiciones de Uso</a>.</p>

<form action="/accion.php?a=expulsar" method="post">

<ol>
<li><b>Nick:</b> el usuario a expulsar.<br />
<input type="text" value="'.$_GET['c'].'" name="nick" size="20" maxlength="20" />
<br /><br /></li>

<li><b>Motivo de expulsión:</b> si son varios elegir el mas claro.<br />
<select name="razon">

<optgroup label="Clones">
	<option value="Clones: 1.a" selected="selected">1.a Clones:</option>
	<option value="Clones: 1.b">1.b Uso de una dirección de email temporal o de uso no habitual.</option>
	<option value="Clones: 1.c">1.c Uso de cualquier método cuyo fin sea ocultar la conexión a Internet.</option>
</optgroup>

<optgroup label="Mantenimiento">
	<option value="Registro erroneo.">Registro erroneo.</option>
	<option value="Test de desarrollo.">Test de desarrollo.</option>
</optgroup>

<optgroup label="Ataque al sistema">
	<option value="Ataque al sistema: 2.a">2.a Uso o descubrimiento de bugs del sistema, sea cual fuere su finalidad, sin reportarlo inmediatamente u obrando de mala fe.</option>
	<option value="Ataque al sistema: 2.b">2.b Ejecutar cualquier tipo de acción que busque causar un perjuicio al mismo.</option>
	<option value="Ataque al sistema: 2.c">2.c La utilización malintencionada del privilegio de Supervisor del Censo.</option>
</optgroup>


<optgroup label="Ataque a la comunidad">
	<option value="Ataque a la comunidad: 3.a">3.a Publicación de contenido altamente violento, obsceno o, en todo caso, no apto para menores de edad.</option>
	<!--<option value="Ataque a la comunidad: 3.b">3.b Hacer apología del terrorismo o ideologías que defiendan el uso de la violencia.</option>-->
	<option value="Ataque a la comunidad: 3.c">3.c Amenazar a otros usuarios con repercusiones fuera de la comunidad.</option>
	<option value="Ataque a la comunidad: 3.d">3.d El uso reiterado o sistemático de “kicks” superiores a 15 minutos sin cobertura legal dentro de la comunidad.</option>
</optgroup>


</select><br /><br /></li>


<li><b>Caso <input type="text" name="caso" size="8" maxlength="20" /></b> Solo en caso de clones.<br /><br /></li>

<li><b>Pruebas:</b> anotaciones o pruebas sobre la expulsion. Confidencial, solo visible por los SC.<br />
<textarea name="motivo" cols="70" rows="6" style="color: green; font-weight: bold;"></textarea>
<br /><br /></li>


<li>'.boton('EXPULSAR', ($disabled?false:'submit'), '¿Seguro que debes EXPULSAR a este usuario?', 'large red').'</li></ol></form>	
';


} elseif (($_GET['b'] == 'info') AND ($_GET['c']) AND (isset($sc[$pol['user_ID']]))) {

		$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = expulsiones.autor LIMIT 1) AS nick_autor
FROM expulsiones
WHERE ID = '".$_GET['c']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){
			$txt .= '<h1 class="quitar"><a href="/control/">Control</a>: <a href="/control/expulsiones/">Expulsiones</a> | #'.$_GET['c'].'</h1>

<p><b>'.crear_link($r['expulsado'], 'nick', $r['expulsado_estado']).'</b> fue expulsado por <b>'.crear_link($r['nick_autor']).'</b>.</p>

<p>Razón: <b>'.$r['razon'].'</b></p>

<p>Fecha: '.$r['expire'].'</p>

<p>Pruebas:</p><p class="azul">'.str_replace("\n","<br />", $r['motivo']).'</p>';
		}
} else {


	$txt_title = 'Control:  Expulsiones';
	$txt_nav = array('/control'=>'Control', '/control/expulsiones'=>'Expulsiones');

	$txt .= '<h1 class="quitar"><a href="/control/">Control</a>: <img src="'.IMG.'varios/expulsar.gif" alt="Expulsado" border="0" /> Expulsiones | <a href="/control/expulsiones/expulsar">Expulsar</a></h1>

<p>Las expulsiones son efectuadas por los Supervisores del Censo (SC). Consiste en un bloqueo definitivo a un usuario y su puesta en proceso de eliminación forzada tras 5 dias, durante este periodo es reversible. Las expulsiones se aplican por incumplimiento las <a href="http://www.'.DOMAIN.'/TOS">Condiciones de Uso</a> (con la excepción de Registro erroneo y Test de desarrollo). Los Supervisores del Censo son ciudadanos con más de 1 año de antiguedad y elegidos por democracia directa, mediante el "voto de confianza", actualizado cada Domingo a las 20:00.</p>

<table border="0" cellspacing="1" cellpadding="" class="pol_table">
<tr>
<th>Expulsado</th>
<th>Cuando</th>
<th>Por</th>
<th>Motivo</th>
<th></th>
</tr>';


	$result = mysql_query("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado,
(SELECT pais FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_pais,
(SELECT estado FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = expulsiones.autor LIMIT 1) AS nick_autor
FROM expulsiones
ORDER BY expire DESC", $link);
	while($r = mysql_fetch_array($result)){
		
		if ((isset($sc[$pol['user_ID']])) AND ($r['expulsado_pais']) AND ($r['estado'] == 'expulsado')) { 
			$expulsar = boton('Cancelar', '/accion.php?a=expulsar&b=desexpulsar&ID=' . $r['ID'], '&iquest;Seguro que quieres CANCELAR la EXPULSION del usuario: '.$r['tiempo'].'?', 'small red'); 
		} elseif ($r['estado'] == 'cancelado') { $expulsar = '<b style="font-weight:bold;">Cancelado</b>'; } else { $expulsar = ''; }

		if (!$r['expulsado_estado']) { $r['expulsado_estado'] = 'expulsado'; }

		$txt .= '
<tr><td valign="top" nowrap="nowrap">'.($r['estado'] == 'expulsado'?'<img src="'.IMG.'varios/expulsar.gif" alt="Expulsado" border="0" /> ':'<img src="'.IMG.'cargos/0.gif" border="0" /> ').'<b>' . crear_link($r['tiempo'], 'nick', $r['expulsado_estado'], $r['expulsado_pais']) . '</b></td>
<td valign="top" align="right" valign="top" nowrap="nowrap"><acronym title="' . $r['expire'] . '">'.timer($r['expire']).'</acronym></td>
<td valign="top">'.crear_link($r['nick_autor']).'</td>
<td valign="top"><b style="font-size:13px;">' . $r['razon'] . '</b></td>
<td valign="top" align="center">' . $expulsar . '</td>
<td>'.(isset($sc[$pol['user_ID']])&&$r['motivo']!=''?'<a href="/control/expulsiones/info/'.$r['ID'].'/">#</a>':'').'</td>
</tr>' . "\n";

		}
		$txt .= '</table>';
	}
	break;






case 'kick':
	$txt_title = 'Control: Kicks';
	$txt_nav = array('/control'=>'Control', 'Kicks');
	$txt_tab = array('/control/kick/expulsar/'=>'Kickear');
	
	if (($_GET['b'] == 'info') AND ($_GET['c'])) {

		$result = mysql_query("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = kicks.autor LIMIT 1) AS nick_autor
FROM kicks
WHERE pais = '".PAIS."' AND ID = '".$_GET['c']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){
			$txt .= '<h1 class="quitar"><a href="/control/">Control</a>: <a href="/control/kick/">Kicks</a> | info '.$_GET['c'].'</h1>
<p>Motivo: <b>'.$r['razon'].'</b></p>

<p>Pruebas:</p><p class="azul">'.str_replace("\n","<br />", $r['motivo']).'</p>';
		}

	} elseif ($_GET['b']) {
		
		$txt_nav = array('/control'=>'Control', '/control/kicks'=>'Kicks', 'Kickear');

		if ($_GET['b'] == 'expulsar') { $_GET['b'] = ''; }
		if (nucleo_acceso($vp['acceso']['kick'])) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }
		$txt .= '<h1 class="quitar"><a href="/control/">Control</a>: <a href="/control/kick/">Kicks</a> | <img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /> Kickear</h1><p>Esta acción privilegiada bloquea totalmente las acciones de un Ciudadano y los que comparten su IP.</p>

<form action="/accion.php?a=kick" method="post">
'.($_GET['c']?'<input type="hidden" name="chat_ID" value="'.$_GET['c'].'" />':'').'
<ol>
<li><b>Nick:</b> el Ciudadano.<br /><input type="text" value="' . $_GET['b'] . '" name="nick" size="20" maxlength="20" /><br /><br /></li>

<li><b>Duración:</b> duración temporal de este kick.<br />
<select name="expire">
<option value="120">2 minutos</option>
<option value="300">5 minutos</option>
<option value="600">10 minutos</option>
<option value="900">15 minutos</option>
<option value="1200">20 minutos</option>
<option value="1800" selected="selected">30 minutos</option>
<option value="2700">45 minutos</option>
<option value="4500">75 minutos</option>
<option value="3600">1 hora</option>
<option value="5400">1.5 horas</option>
<option value="7200">2 horas</option>
<option value="18000">5 horas</option>
<option value="86400">1 día</option>
<option value="172800">2 días</option>
<option value="259200">3 días</option>
<option value="518400">6 días</option>
<option value="777600">9 días</option>
</select><br /><br /></li>

<li><b>Motivo breve:</b> frase con el motivo de este kick. Se preciso.<br /><input type="text" name="razon" size="60" maxlength="255" /><br /><br /></li>

<li><b>Pruebas:</b> opcionalmente puedes pegar aqui las anotaciones o pruebas sobre el kick.<br /><textarea name="motivo" cols="70" rows="6" style="color: green; font-weight: bold;"></textarea></p>

<br /><br /></li>


<li><input type="submit" value="Ejecutar KICK"' . $disabled . ' /></li></ol></form>
			
';
	} else {
		$txt .= '<h1 class="quitar"><a href="/control/">Control</a>: <img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /> Kicks</h1>

<p><span class="quitar">'.boton('KICK', '/control/kick/expulsar/').' </span>Un kick bloquea temporalmente a un Ciudadano y su IP de todas las acciones en '.PAIS.'.</p>

<table border="0" cellspacing="1" cellpadding="" class="pol_table">
<tr>
<th colspan="2">Estado</th>
<th>Afectado</th>
<th>Autor</th>
<th>Cuando</th>
<th>Tiempo</th>
<th>Razón</th>
<th></th>
</tr>';

	mysql_query("UPDATE kicks SET estado = 'inactivo' WHERE pais = '".PAIS."' AND estado = 'activo' AND expire < '" . $date . "'", $link); 
	$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); //30dias
	$result = mysql_query("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo, user_ID,
(SELECT nick FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = kicks.autor LIMIT 1) AS nick_autor
FROM kicks
WHERE pais = '".PAIS."' AND expire > '" . $margen_30dias . "' AND estado != 'expulsado'
ORDER BY expire DESC", $link);
	while($r = mysql_fetch_array($result)){
		if ((($r['autor'] == $pol['user_ID']) OR (nucleo_acceso($vp['acceso']['kick_quitar']))) AND ($r['estado'] == 'activo')) { $expulsar = boton('X', '/accion.php?a=kick&b=quitar&ID=' . $r['ID'], '&iquest;Seguro que quieres hacer INACTIVO este kick?'); } else { $expulsar = ''; }

		$duracion = '<acronym title="' . $r['expire'] . '">' . duracion((time() + $r['tiempo']) - strtotime($r['expire'])) . '</acronym>';

		if ($r['estado'] == 'activo') {
			$estado = '<span style="color:red;">Activo</span>';
		} elseif ($r['estado'] == 'cancelado') {
			$estado = '<span style="color:grey;">Cancelado</span>';
		} else {
			$estado = '<span style="color:grey;">Inactivo</span>';
		}
		if (!$r['expulsado_estado']) { $r['expulsado_estado'] = 'expulsado'; }

		$txt .= '<tr><td valign="top"><img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /></td><td valign="top"><b>' . $estado . '</b></td><td valign="top"><b>'.($r['user_ID'] == 0?'Anonimo':crear_link($r['expulsado'], 'nick', $r['expulsado_estado'])).'</b></td><td valign="top" nowrap="nowrap"><img src="'.IMG.'cargos/' . $r['cargo'] . '.gif" border="0" /> ' . crear_link($r['nick_autor']) . '</td><td align="right" valign="top" nowrap="nowrap"><acronym title="' . $r['expire'] . '">'.timer($r['expire']).'</acronym></td><td align="right" valign="top" nowrap="nowrap">' . duracion($r['tiempo']+1) . '</td><td><b style="font-size:13px;">'.($r['motivo']?'<a href="/control/kick/info/'.$r['ID'].'/">'.$r['razon'].'</a>':$r['razon']).'</b></td><td>'.$expulsar.'</td></tr>' . "\n";
	}
	$txt .= '</table>'.(ASAMBLEA?'':'<p>Los kicks solo pueden ser revocadas por un Comisario de Policia, un Juez Supremo o el Policía autor de la expulsión.</p>');


	}

	break;


case 'judicial':
	$txt_title = 'Control: Judicial';
	$txt_nav = array('/control'=>'Control', 'Judicial');

	
	$txt .= '<h1 class="quitar"><a href="/control/">Control</a>: Judicial</h1><p>Panel Judicial para Jueces.</p>

<h2>1. Sanciones</h2><hr />

<table border="0" cellspacing="1" cellpadding="" class="pol_table">
<tr>
<th></th>
<th>Sancionado</th>
<th>Hace</th>
<th>Concepto</th>
</tr>';



	$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = transacciones.emisor_ID LIMIT 1) AS nick
FROM transacciones
WHERE pais = '".PAIS."' AND concepto LIKE '<b>SANCION %' AND receptor_ID = '-1'
ORDER BY time DESC", $link);
	while($r = mysql_fetch_array($result)){
		$txt .= '<tr><td>'.pols('-'.$r['pols']).' '.MONEDA.'</td><td><b>'.crear_link($r['nick']).'</b></td><td><acronym title="'.$r['time'].'">'.timer($r['time']).'</acronym></td><td>'.$r['concepto'].'</td></tr>' . "\n";
	}




if ($pol['cargo'] != 9) { $disabled = ' disabled="disabled"'; }

$txt .= '</table><br />

<form action="/accion.php?a=sancion" method="post">

<ol>
<li><b>Nick:</b> el Ciudadano de '.PAIS.' que recibirá la sanción.<br /><input type="text" value="" name="nick" size="20" maxlength="20" /><br /><br /></li>

<li><b>'.MONEDA.' de multa:</b> el importe de la sanción, maximo 5000 '.MONEDA.' (en caso de no tener la cantidad requerida, se quedará en negativo).<br /><input style="color:blue;text-align:right;" type="text" name="pols" size="4" value="1" maxlength="4" /> '.MONEDA.'<br /><br /></li>

<li><b>Concepto:</b> breve frase con la razón de la sanción.<br /><input type="text" name="concepto" size="50" maxlength="100" /><br /><br /></li>

<li><input type="submit" style="color:red;" value="Efectuar sanción"' . $disabled . ' /> &nbsp; <span style="color:red;"><b>[acción irreversible]</b></span></li></ol></form>
			
';
	break;




	default:
		$txt_title = 'Control';
		$txt_nav = array('/control'=>'Control');

		$txt .= '<h1 class="quitar">Control:</h1>
<p class="amarillo" style="color:red;">Zonas de control cuyo acceso está reservado a los ciudadanos que ejercen estos cargos.</p>

<table border="0" cellspacing="6">

<tr><td nowrap="nowrap"><a class="abig" href="/control/gobierno/"><b>Gobierno</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/7.gif" title="Presidente" /> <img src="'.IMG.'cargos/19.gif" title="Vicepresidente" /></td>
<td>Opciones de configuración de gobierno.</td></tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /> <a class="abig" href="/control/kick/"><b>Kicks</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/13.gif" title="Comisario de Policia" /> <img src="'.IMG.'cargos/12.gif" title="Policia" /></td>
<td>Control de bloqueo temporal del acceso.</td>
</tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'varios/expulsar.gif" alt="Expulsado" border="0" /> <a class="abig" href="/control/expulsiones/"><b>Expulsiones</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/21.gif" title="Supervisor del Censo" /></td>
<td>Expulsiones permanentes por incumplimiento del <a href="http://www.'.DOMAIN.'/">TOS</a>.</td>
</tr>

<tr>';


if (isset($sc[$pol['user_ID']])) {
	$txt .= '<td nowrap="nowrap"><a class="abig" href="/control/supervisor-censo/"><b>Supervisión del Censo</b></a></td>';
} else {
	$txt .= '<td nowrap="nowrap"><b class="abig gris">Supervisión del Censo</b></td>';
}

foreach ($sc AS $user_ID => $nick) { $supervisores .= crear_link($nick).' '; }

$txt .= '
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/21.gif" title="Supervisor del Censo" /></td>
<td>Información sobre el censo y control de clones.<br />
Supervisores del Censo: <b>'.$supervisores.'</b><br />(los '.count($sc).' ciudadanos con más votos de confianza)</td></tr>';

if (ECONOMIA) {

	$txt .= '
<tr><td nowrap="nowrap"><a class="abig" href="/control/judicial/"><b>Judicial</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/9.gif" title="Judicial" /></td>
<td>El panel judicial que permite efectuar sanciones.</td></tr>


<tr><td nowrap="nowrap"><a class="abig" href="/mapa/propiedades/"><b>Propiedades del Estado</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/40.gif" title="Arquitecto" /></td>
<td>El Arquitecto tiene el control de las propiedades del Estado.</td></tr>';

}

$txt .= '</table>';

		break;
}

$txt_header .= '<style type="text/css">h1 a { color:#4BB000; } .abig { font-size:20px; }</style>';


//THEME
include('theme.php');
?>
