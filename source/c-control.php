<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


// load config full
$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }

$sc = get_supervisores_del_censo();

switch ($_GET['a']) {


case 'supervisor-censo':

if (nucleo_acceso('supervisores_censo')) {

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
		return ($nota_SC!=''?'<form action="'.accion_url($pol['pais']).'a=SC&b=nota&ID='.$user_ID.'" method="post"><input type="text" name="nota_SC" size="25" maxlength="255" value="'.$nota_SC.'"'.(substr($nota_SC, 0, 7)=='Cuidado'?' style="color:red;"':'').(substr($nota_SC, 0, 12)=='Comparte con'?' style="color:green;"':'').(substr($nota_SC, 0, 3)=='OK '?' style="color:blue;"':'').' /> '.boton('OK', 'submit', false, 'small pill').'</form>':'');
	}

	// nomenclatura
	foreach ($vp['paises'] AS $pais) { $paises .= ' <span class="redondeado">'.$pais.'</span>'; }
	$nomenclatura = '<fieldset><legend>'._('Plataformas').'</legend>'.$paises.' | '._('Estados').': <b class="ciudadano">'._('Ciudadano').'</b> <b class="turista">'._('Turista').'</b> <b class="validar">'._('Validar').'</b> <b class="expulsado">'._('Expulsado').'</b></fieldset>';

	// siglas partidos
	$result = sql("SELECT ID, siglas FROM partidos WHERE pais = '".PAIS."'");
	while($r = r($result)) { $siglas[$r['ID']] = $r['siglas']; }

	$txt_tab = array('/control/supervisor-censo'=>_('Principal'), '/control/supervisor-censo/factores-secundarios'=>_('Extra'), '/control/supervisor-censo/nuevos-ciudadanos'=>_('Nuevos'), '/control/supervisor-censo/bloqueos'=>_('Bloqueos'), '/control/expulsiones'=>_('Expulsiones'));

	if ($_GET['b'] == 'nuevos-ciudadanos') {

			$txt_title = _('Control').': SC | '._('Nuevos ciudadanos');
			$txt_nav = array('/control'=>_('Control'), '/control/supervisor-censo'=>'SC', _('Nuevos'));

			$txt .= '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp;  '._('Supervisores del censo').': <b>'.$supervisores.'</b></p>'.$nomenclatura;

			$txt .= '<h1>1. '._('Actividad de nuevos ciudadanos (últimos 60)').'</h1><hr />
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
	$result = sql("SELECT *
FROM users 
ORDER BY fecha_registro DESC
LIMIT 60");
	while($r = r($result)) {
		$dia_registro = date('j', strtotime($r['fecha_registro']));
		
		$razon = '';
		if ($r['estado'] == 'expulsado') {
			$result2 = sql("SELECT razon FROM expulsiones WHERE user_ID = '".$r['ID']."' LIMIT 1");
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
		
		$txt .= '<tr'.$td_bg.'>
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
	$txt .= '</table>';





} else if ($_GET['b'] == 'bloqueos') {

	$txt_title = 'SC | '._('bloqueos');
	$txt_nav['/sc/bloqueos'] = _('Bloqueos');

	$result = sql("SELECT valor, dato FROM config WHERE PAIS IS NULL");
	while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }

	$backlists = array('backlist_IP'=>400, 'backlist_emails'=>180, 'backlist_nicks'=>120);

	$txt .= '<form action="'.accion_url().'a=bloqueos" method="post">

<p>'._('Listas negras para bloquear masivamente con filtros. Un elemento por linea. Elementos de al menos 5 caracteres (para minimizar el riesgo de filtros masivos). Precaución, hay riesgo de producir bloqueos masivos').'.</p>
<table>
<tr>';

	foreach ($backlists AS $tipo => $width) {
		$txt .= '<td><fieldset><legend>'.ucfirst(str_replace('_', ' ', $tipo)).'</legend>
	<textarea style="width:'.$width.'px;height:400px;white-space:nowrap;" name="'.$tipo.'">'.$pol['config'][$tipo]."\n".'</textarea></fieldset></td>';
	}
	
	$txt .= '
</tr>

<tr>
<td colspan="'.count($backlists).'" align="center">'.boton(_('Guardar'), 'submit', '¿Estás seguro de activar estos BLOQUEOS?\n\nPRECAUCION: RIESGO DE BLOQUEOS MASIVOS INVOLUNTARIOS.', 'large red').'</td>
</tr>
</table>

</form>
';



} else if ($_GET['b'] == 'confianza-mutua') {



	$txt_title = _('Control').': SC | '._('Confianza mutua');
	$txt_nav = array('/control'=>_('Control'), '/control/supervisor-censo'=>'SC', _('Confianza mutua'));

	$txt .= '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp; '._('Supervisores del censo').': <b>' . $supervisores . '</b></p>'.$nomenclatura;


$data_amigos = array();
$data_enemigos = array();
$confianzas_amigos = array();

$result = sql("SELECT *,
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


$result = sql("SELECT *,
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

$txt .= '<h1>'._('Grafico confianza').'</h1>

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

} else if ($_GET['b'] == 'factores-secundarios') {

	$txt_title = _('Control').': SC | '._('Extras');
	$txt_nav = array('/control'=>_('Control'), '/control/supervisor-censo'=>'SC', _('Extras'));

	$txt .= '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp;  '._('Supervisores del censo').': <b>'.$supervisores.'</b></p>'.$nomenclatura;


	$txt .= '<br /><h1>5. '._('Referencias').'</h1><hr /><table border="0" cellspacing="4">';
	$result = sql("SELECT ID, nick, ref, pais, ref_num, estado, partido_afiliado
FROM users 
WHERE ref_num != '0' 
ORDER BY ref_num DESC, fecha_registro DESC");
	while($r = r($result)) {
		$clones = '';
		$result2 = sql("SELECT ID, nick, ref, pais, estado, partido_afiliado
FROM users 
WHERE ref = '" . $r['ID'] . "'");
		while($r2 = r($result2)) { 
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


	$txt .= '<br /><h1>6. '._('Emails atípicos').'</h1><hr /><table border="0" cellspacing="4">';
	$result = sql("SELECT email, nick, ref, ref_num, estado FROM users ORDER BY fecha_registro DESC");
	while($r = r($result)) {

		$r['email'] = strtolower($r['email']);
		$email = explode("@", $r['email']);

		if (!in_array($email[1], $emails_whitelist)) {
			$clones = '';
			$r['email'] = explodear("@", $r['email'], 1); 
			$txt .= '<tr><td>' . crear_link($r['nick'], 'nick', $r['estado']) . '</td><td>*@<b>'.$r['email'].'</b></td></tr>';
		}
	}
	$txt .= '</table>';


	$txt .= '<br /><h1>7. '._('URLs de referencia').'</h1><hr /><table border="0" cellspacing="4">
<tr>
<th></th>
<th>Ref</th>
<th>'._('Nuevos').'</th>
<th>URL</th>
</tr>';
	$result = sql("SELECT user_ID, COUNT(*) AS num, referer,
(SELECT nick FROM users WHERE ID = referencias.user_ID LIMIT 1) AS nick,
(SELECT COUNT(*) FROM referencias WHERE referer = referencias.referer AND new_user_ID != '0') AS num_registrados
FROM referencias 
GROUP BY referer HAVING COUNT(*) > 1
ORDER BY num DESC");
	while($r = r($result)) {

		$result2 = sql("SELECT COUNT(*) AS num_registrados FROM referencias WHERE referer = '" . $r['referer'] . "' AND new_user_ID != '0'");
		while($r2 = r($result2)) {
			if ($r2['num_registrados'] != 0) { $num_registrados = '+' . $r2['num_registrados']; } else { $num_registrados = ''; }
		}
		if ($r['referer'] == '') { $r['referer'] = '#referencia-directa'; $r['nick'] = '&nbsp;'; }

		$txt .= '<tr><td><b>' . crear_link($r['nick']) . '</b></td><td align="right"><b>' . $r['num'] . '</b></td><td align="right">' . $num_registrados . '</td><td><a href="' . $r['referer'] . '">' . $r['referer'] . '</a></td></tr>';
	}
	$txt .= '</table>';



	$txt .= '<br /><h1>8. '._('Más votos y menos actividad').'</h1><hr /><table border="0" cellspacing="4">
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
	$result = sql("SELECT nick, IP, num_elec, estado, online, visitas, pais, paginas, ((num_elec * 100) / online) AS factor, partido_afiliado 
FROM users WHERE num_elec > 2 AND fecha_last > '".date('Y-m-d 20:00:00', time() - 2592000)."' ORDER BY factor DESC LIMIT 20");
	while($r = r($result)) {
		if ($r['factor'] > 0.0099) {
			$txt .= '<tr><td>'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</td><td align="right"><b>'.$r['num_elec'].'</b></td><td>/</td><td align="right"><b>'.duracion($r['online']).'</b></td><td><b>=</b></td><td>'.$r['factor'].'</td><td align="right">'.$r['visitas'].'</td><td align="right">'.$r['paginas'].'</td><td>('.ocultar_IP($r['IP']).')</td></tr>';
		}
	}
	$txt .= '</table>';


	$txt .= '<br /><h1>9. '._('Navegadores').'</h1><hr />
<table border="0" cellspacing="4">';
	$result = sql("SELECT COUNT(*) AS num, nav
FROM users 
GROUP BY nav HAVING COUNT(*) > 1
ORDER BY num ASC");
	while($r = r($result)) {

		$clones = '';
		if ($r['num'] <= 30) {
			$result2 = sql("SELECT ID, nick, estado, pais FROM users WHERE nav = '" . $r['nav'] . "' ORDER BY fecha_registro DESC");
			while($r2 = r($result2)) {
				if ($clones) { $clones .= ' & '; }
				$clones .= crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']);
			}
		} else { $clones = '</b>('._('navegador muy común').')<b>'; }


		$txt .= '<tr><td align="right"><b>'.$r['num'].'</b></td><td>'.$clones.'</td><td style="font-size:9px;">'.$r['nav'].'</td></tr>';
	}
	$txt .= '</table>';


	$txt .= '<br /><h1>10. '._('Más antigüedad y menos actividad').' (ALPHA)</h1><hr /><table border="0" cellspacing="4">
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
	$result = sql("SELECT nick, IP, num_elec, estado, online, visitas, pais, paginas, fecha_registro,  ((CURRENT_TIMESTAMP() - TIMESTAMP(fecha_registro)) / online) AS factor 
FROM users  
WHERE online > 600
ORDER BY factor DESC LIMIT 30");
	while($r = r($result)) {
		$txt .= '<tr><td>'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</td><td align="right"><b>'.duracion(time()-strtotime($r['fecha_registro'])).'</b></td><td>/</td><td align="right"><b>'.duracion($r['online']).'</b></td><td><b>=</b></td><td>'.round($r['factor']).'</td><td align="right">'.$r['visitas'].'</td><td align="right">'.$r['paginas'].'</td><td>('.ocultar_IP($r['IP']).')</td></tr>';
	}
	$txt .= '</table>';


	} else { // principal

	$txt_title = _('Control').': SC';
	$txt_nav = array('/control'=>_('Control'), '/control/supervisor-censo'=>'SC');

	$txt .= '<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b> &nbsp; '._('Supervisores del censo').': <b>'.$supervisores.'</b></p>'.$nomenclatura;


	$txt .= '<fieldset><legend>1. '._('Coincidencias de IP').' ('.round((microtime(true)-TIME_START)*1000).'ms)</legend><table border="0" cellspacing="4">';
	$result = sql("SELECT nick, IP, COUNT(*) AS num, host
FROM users 
GROUP BY IP HAVING COUNT(*) > 1
ORDER BY num DESC, IP ASC");
	while($r = r($result)) {
		$clones = array();
		$nota_SC = '';
		$clones_expulsados = true;
		$confianza_total = 0;
		$result2 = sql("SELECT ID, nick, estado, pais, partido_afiliado, nota_SC, 
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
			$txt .= '<tr><td>' . $r['num'] . '</td><td><span style="float:right;">'.ocultar_IP($r['host'], 'host').'</span>'.implode(' & ', $clones).'</td><td>'.ocultar_IP($r['IP']).'</td><td nowrap="nowrap">'.$nota_SC.'</td></tr>';
		}
	}
	$txt .= '</table></fieldset>';




	$txt .= '<fieldset><legend>2. '._('Coincidencias de clave').' ('.round((microtime(true)-TIME_START)*1000).'ms)</legend><table border="0" cellspacing="4">';
	$result = sql("SELECT ID, IP, COUNT(*) AS num, pass
FROM users 
GROUP BY pass HAVING COUNT(*) > 1
ORDER BY num DESC, fecha_registro DESC");
	while($r = r($result)) {
		if (($r['pass'] != 'mmm') OR ($r['pass'] != 'e10adc3949ba59abbe56e057f20f883e')) {

			$clones = array();
			$nota_SC = '';
			$confianza_total = 0;
			$result2 = sql("SELECT ID, nick, pais, partido_afiliado, estado, nota_SC
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
				$txt .= '<tr><td>' . $r['num'] . '</td><td><b>'.implode(' & ', $clones).'</b></td><td nowrap="nowrap">'.$nota_SC.'</td></tr>';
			}
		}
	}
	$txt .= '</table></fieldset>';




	$trazas_rep = array();
	$txt .= '<fieldset><legend>3. '._('Coincidencia de traza').' ('.round((microtime(true)-TIME_START)*1000).'ms)</legend><table border="0" cellspacing="4">';
	$result = sql("SELECT ID AS user_ID, ID, nick, estado, pais, traza, nota_SC FROM users WHERE traza != '' ORDER BY fecha_registro DESC");
	while($r = r($result)) {
		$nota_SC .= print_nota_SC($r['nota_SC'], $r['ID']);
		$tn = 1;
		$trazas = explode(' ', $r['traza']);
		$trazas_clones = array();
		if ($r['estado'] == 'expulsado') { $mostrar = false; } else { $mostrar = true; }
		foreach ($trazas AS $unatraza) {
			$trazado = false;
			$result2 = sql("SELECT ID, nick, estado, pais, nota_SC FROM users WHERE ID = '".$unatraza."' LIMIT 1");
			while($r2 = r($result2)) {
				$nota_SC .= print_nota_SC($r2['nota_SC'], $r2['ID']);
				$tn++; $trazas_clones[] = crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']);
				$trazado = true;
				if ($r2['estado'] != 'expulsado') { $mostrar = true; }
			}
			if ($trazado == false) {
				$result2 = sql("SELECT tiempo AS nick FROM expulsiones WHERE user_ID = '".$unatraza."' LIMIT 1");
				while($r2 = r($result2)) {
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
	$txt .= '</table></fieldset>';


	$txt .= '<fieldset><legend>4. '._('Ocultación (proxys, TOR...)').' ('.round((microtime(true)-TIME_START)*1000).'ms)</legend><table border="0" cellspacing="4">';
	$array_searchtor = array('%anon%', '%tor%', '%vps%', '%vpn%', '%proxy%');
	$sql_anon = array();
	foreach ($array_searchtor AS $filtro) { $sql_anon[] = "hosts LIKE '".$filtro."' OR host LIKE '".$filtro."'"; }
	$result = sql("SELECT nick, estado, host, IP, nav, nota_SC FROM users WHERE ".implode(" OR ", $sql_anon)." ORDER BY fecha_registro DESC");
	while($r = r($result)) {
		$txt .= '<tr><td><b>'.crear_link($r['nick'], 'nick', $r['estado']).'</b></td><td>'.ocultar_IP($r['IP']).'</td><td nowrap="nowrap"><b>'.ocultar_IP($r['host'], 'host').'</b></td><td style="font-size:10px;">'.$r['nav'].'</td><td nowrap="nowrap">'.print_nota_SC($r['nota_SC'], $r['ID']).'</td></tr>';
	}
	$txt .= '</table><table border="0" cellspacing="4">';
	$result = sql("SELECT ID, IP, nick, estado, pais, IP_proxy, host
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
				$result2 = sql("SELECT nick, estado, pais FROM users WHERE ID != '".$r['ID']."' AND (IP = '".ip2long($IP)."' OR IP_proxy LIKE '%".$IP."%') ORDER BY fecha_registro DESC");
				while($r2 = r($result2)) {
					$clones_num++;
					$clones .= crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']).' ';
				}
				$clones .= '<br />';
			}
		}

		if ($clones_num > 0) {
			if ($proxy_first != true) { $txt .= '<tr><th></th><th colspan="3"><span style="float:right;">Hosts</span>Proxys</th></tr>'; $proxy_first = true; }
			$txt .= '<tr>
<td valign="top"><b>' . crear_link($r['nick'], 'nick', $r['estado'], $r['pais']) . '</b></td>
<td valign="top">' . $proxys_num . '<hr /></td>
<td valign="top">' . $proxys . '<hr /></td>
<td valign="top" nowrap="nowrap" align="right">' . $proxys_dns . '<hr /></td>
<td valign="top">' . $clones . '<hr /></td>
</tr>';
		}

	}
	$txt .= '</table></fieldset>';

	}

	} else { $txt .= '<p class="amarillo" style="color:red;"><b>'._('Acceso restringido').'.</b></p>'; }
	break;




case 'gobierno':
	$txt_title = _('Control').': '._('Gobierno');
	$txt_nav = array('/control'=>_('Control'), '/control/gobierno'=>_('Gobierno'));

	$txt_tab['/control/gobierno'] = _('Gobierno');
	$txt_tab['/control/gobierno/privilegios'] = _('Privilegios');
	if (ECONOMIA) { $txt_tab['/control/gobierno/economia'] = _('Economía'); }
	$txt_tab['/control/gobierno/notificaciones'] = _('Notificaciones');
	$txt_tab['/control/gobierno/foro'] = _('Configuración foro');
	$txt_tab['/control/gobierno/categorias'] = _('Categorías');
	
	if (nucleo_acceso($vp['acceso']['control_gobierno'])) { $dis = null; } else { $dis = ' disabled="disabled"'; }

	$defcon_bg = array('1' => 'white','2' => 'red','3' => 'yellow','4' => 'green','5' => 'blue');

	if ($_GET['b'] == 'categorias') {
		$txt_nav[] = _('Categorías');

		if (nucleo_acceso($vp['acceso']['control_gobierno'])) { $dis = ''; } else { $dis = ' disabled="disabled"'; }

		$txt .= '<form action="'.accion_url().'a=gobierno&b=categorias&c=editar" method="post">

<table border="0" cellspacing="0" cellpadding="4">

<tr>
<th>'._('Orden').'</th>
<th>'._('Nombre').'</th>
<th>'._('Tipo').'</th>
<th>'._('Nivel').'</th>
<th>Actual</th>
<th>Publicable</th>
</tr>';
	$subforos = '';
	$result = sql("SELECT * FROM cat WHERE pais = '".PAIS."' ORDER BY tipo DESC, orden ASC");
	while($r = r($result)){
		
		$num = 0;
		if ($r['tipo'] == 'docs') {
			$result2 = sql("SELECT COUNT(*) AS el_num FROM docs WHERE pais = '".PAIS."' AND cat_ID = '".$r['ID']."'");
			while($r2 = r($result2)){ $num = $r2['el_num']; }
		} elseif ($r['tipo'] == 'empresas') {
			$result2 = sql("SELECT COUNT(*) AS el_num FROM empresas WHERE pais = '".PAIS."' AND cat_ID = '".$r['ID']."'");
			while($r2 = r($result2)){ $num = $r2['el_num']; }
		}
		$checkbox = '<input class="checkbox_impuestos" type="checkbox" name="'.$r['ID'].'_publicable" value="1"'.$disabled;
		
		if ($r['publicar'] == '1') {
			$checkbox .= ' checked="checked"';
		}
		$checkbox .= ' />';
			
		$txt .= '<tr>
<td><input type="text" style="text-align:right;" name="'.$r['ID'].'_orden" size="1" maxlength="3" value="'.$r['orden'].'" /></td>

<td><input type="text" name="'.$r['ID'].'_nombre" size="30" maxlength="50" value="'.$r['nombre'].'" style="font-weight:bold;" /></td>

<td>'.ucfirst($r['tipo']).'</td>

<td><input type="text" style="text-align:right;" name="'.$r['ID'].'_nivel" size="1" maxlength="3" value="'.$r['nivel'].'" /></td>

<td align="right" style="color:#999;" nowrap="nowrap"><b>'.$num.'</b></td>
<td align="center">'.$checkbox.'
</td>


<td>'.($num==0?boton('Eliminar', accion_url().'a=gobierno&b=categorias&c=eliminar&ID='.$r['ID'], false, 'small red'):'').'</td>
</tr>'."\n";
	}

		$txt .= '
<tr>
<td align="center" colspan="8"><input value="'._('Guardar cambios').'" style="font-size:22px;" type="submit"'.$dis.' /></td>
</tr>
</table>
</form>


<fieldset><legend>'._('Crear categoría').'</legend>
<form action="'.accion_url().'a=gobierno&b=categorias&c=crear" method="post">
<table border="0" cellspacing="3" cellpadding="0">
<tr>
<td>'._('Nombre').':</td>
<td><input type="text" name="nombre" size="10" maxlength="30" value="" /></td>
'.(ECONOMIA?'<td><select name="tipo"><option value="docs">'._('Documentos').'</option><option value="empresas">'._('Empresas').'</option></select></td>':'').'
<td><strong>Publicable &nbsp;</strong><input class="checkbox_impuestos" type="checkbox" name="publicable" value="1" /></td>
<td><input value="'._('Crear categoría').'" style="font-size:18px;" type="submit"'.$dis.' /></td>
</tr>
</table>
</form>
</fieldset>';



	} else if ($_GET['b'] == 'privilegios') {
		$txt_nav[] = _('Privilegios');
		
		if (!ECONOMIA) { unset($vp['acceso']['control_sancion']); }
		if (ASAMBLEA) { unset($vp['acceso']['parlamento']); }

		$privilegios_array = array(
'control_gobierno'=>_('Configuración principal'),
'control_cargos'=>_('Configurar cargos'),
'control_grupos'=>_('Configurar grupos'),
'control_sancion'=>_('Imponer sanciones'),
'crear_partido'=>_('Crear partido'),
'examenes_decano'=>_('Gestionar exámenes'),
'examenes_profesor'=>_('Crear preguntas de examen'),
'foro_borrar'=>_('Moderar foro'),
'kick'=>_('Kickear (bloqueos temporales)'),
'kick_quitar'=>_('Quitar kicks'),
'parlamento'=>_('Aprobar votación de parlamento'),
'referendum'=>_('Aprobar referéndums'),
'sondeo'=>_('Aprobar sondeos'),
'votacion_borrador'=>_('Crear borradores de votación'),
'control_socios'=>_('Gestión de socios'),
'api_borrador'=>_('Crear borradores en API'),
'cargo'=>_('Control cargos'),
'control_docs'=>_('Control de los documentos'),
);


	$txt .= '<fieldset>'._('Los privilegios sirven para gestionar permisos especiales del sistema. Este panel muestra los privilegios y quien los ostenta actualmente').'.</fieldset>
<fieldset><legend>'._('Privilegios').'</legend><form action="'.accion_url().'a=gobierno&b=privilegios" method="POST"><table>
<tr>
<th></th>
<th>'._('Configuración').'</th>
<th>¿'._('Quien tiene acceso').'?</th>
</tr>';
		foreach ($vp['acceso'] AS $acceso => $cfg) {
			$txt .= '<tr>
<td align="right" nowrap="nowrap"><b>'.$privilegios_array[$acceso].'</b></td>
<td>'.($acceso=='control_gobierno'?'':control_acceso(false, $acceso, $cfg[0], $cfg[1], 'anonimos ciudadanos_global', true)).'</td>
<td>'.ucfirst(verbalizar_acceso($cfg)).'</td>
</tr>';
		}
		$txt .= '<tr><td colspan="3" align="center">'.boton(_('Guardar'), (nucleo_acceso($vp['acceso']['control_gobierno'])?'submit':false), '¿Estás seguro de querer MODIFICAR los privilegios?', 'large red').'</td></tr></table></form></fieldset>';


	} elseif ($_GET['b'] == 'notificaciones') {
		
		$txt_nav[] = _('Notificaciones');
		
		$txt .= '<fieldset>'._('Las notificaciones son mensajes eventuales enviados a cada usuario que aparecen de forma resaltada en el menú de notificaciones. Este panel permite crear notificaciones personalizadas.').'</fieldset>
		
<form action="'.accion_url().'a=gobierno&b=notificaciones&c=add" method="post">

<fieldset><legend>'._('Crear notificación (para todos los ciudadanos)').'</legend>

<table border="0">
<tr>
<td>'._('Texto').': </td>
<td><input type="text" name="texto" value="" size="52" maxlength="50" required /></td>
</tr>

<tr>
<td>URL: </td>
<td><input type="url" name="url" value="" size="64" maxlength="80" required placeholder="http://" /> ('._('si no cabe usa un acortador').')</td>
</tr>

<tr>
<td>Destino: </td>
<td>'.control_acceso(false, 'acceso', ($_POST['ciudadanos']?'privado':'ciudadanos'), $_POST['ciudadanos'], 'anonimos ciudadanos_global excluir', true).'</td>
</tr>

<tr>
<td></td>
<td>
'.boton(_('Crear notificación'), (nucleo_acceso($vp['acceso']['control_gobierno'])?'submit':false), '¿Estás seguro de crear esta notificación?\n\n¡Cuidado! compruébalo inmediatamente, en caso de error puedes borrarlo.', 'red').'</td>
</tr>
</table>
</fieldset>

</form>

<fieldset><legend>'._('Notificaciones').'</legend>
<table border="0" cellspacing="0" cellpadding="4">


<tr>
<th>'._('Cuando').'</th>
<th>'._('Mensaje').'</th>
<th>'._('Emitidas').'</th>
<th colspan="2">Clicks</th>
<th></th>
</tr>';
		$result = sql("SELECT *, COUNT(*) AS num FROM notificaciones WHERE emisor = '".PAIS."' GROUP BY emisor, texto ORDER BY time DESC");
		while($r = r($result)){

			$leido = 0;
			$result2 = sql("SELECT COUNT(*) AS num FROM notificaciones WHERE texto = '".$r['texto']."' AND visto = 'true'");
			while($r2 = r($result2)){ $leido = $r2['num']; }

			$txt .= '<tr>
<td align="right">'.timer($r['time']).'</td>
<td><a href="'.$r['url'].'">'.$r['texto'].'</a></td>
<td align="right"><b>'.num($r['num']).'</b></td>
<td align="right">'.num($leido).'</td>
<td align="right">'.num($leido*100/$r['num'], 2).'%</td>
<td>'.(nucleo_acceso($vp['acceso']['control_gobierno'])?boton('X', accion_url().'a=gobierno&b=notificaciones&c=borrar&noti_ID='.$r['noti_ID'], false, 'small'):boton('X', false, false, 'small')).'</td>
</tr>';
		}

		$txt .= '</table></fieldset>';

	} elseif ($_GET['b'] == 'foro') {
		
		$txt_nav[] = _('Configuración foro');

		$txt .= '<form action="'.accion_url().'a=gobierno&b=subforo" method="post">

<table border="0" cellspacing="0" cellpadding="4">

<tr>
<th colspan="2"></th>
<th colspan="3" align="center" style="background:#CCC;">'._('Acceso').'</th>
<th colspan="2"></th>
</tr>

<tr>
<th>'._('Orden').'</th>
<th>'._('Foro/descripción').'</th>
<th style="background:#5CB3FF;">'._('Leer').'</th>
<th style="background:#F97E7B;">'._('Crear hilos').'</th>
<th style="background:#F97E7B;">'._('Responder mensajes').'</th>
<th title="Numero de hilos mostrados en la home del foro">'._('Mostrar').'</th>
<th></th>
<th></th>
</tr>';
	$subforos = '';
	$result = sql("SELECT *,
(SELECT COUNT(*) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_hilos,
(SELECT SUM(num) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_msg
FROM ".SQL."foros WHERE estado = 'ok'
ORDER BY time ASC");
	while($r = r($result)){

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

<td align="right" style="color:#999;" nowrap="nowrap">'.number_format($r['num_hilos'], 0, ',', '.').' '._('hilos').'<br />
'.number_format($r['num_msg'], 0, ',', '.').' '._('mensajes').'</td>
<td>'.($r['num_hilos']==0?boton('Eliminar', accion_url().'a=gobierno&b=eliminarsubforo&ID='.$r['ID'], false, 'small red'):'').'</td>
</tr>'."\n";

		if ($subforos) { $subforos .= '.'; }
		$subforos .= $r['ID'];
	}

		$txt .= '
<input name="subforos" value="'.$subforos.'" type="hidden" />
<tr>
<td align="center" colspan="8"><input value="'._('Guardar cambios').'" style="font-size:22px;" type="submit"'.$dis.' /></td>
</tr>
</table>
</form>

<fieldset><legend>'._('Crear nuevo foro').'</legend>
<form action="'.accion_url().'a=gobierno&b=crearsubforo" method="post">
<table border="0" cellspacing="3" cellpadding="0">
<tr>
<td>'._('Nombre').':</td>
<td><input type="text" name="nombre" size="10" maxlength="15" value="" /></td>
<td><input value="'._('Crear subforo').'" style="font-size:18px;" type="submit"'.$dis.' /></td>
</tr>
</table>
</form>
</fieldset>';


	} elseif ($_GET['b'] == 'economia') {


	$txt .= '
<form action="'.accion_url().'a=gobierno&b=economia" method="post">

<table border="0" cellspacing="3" cellpadding="0"><tr><td valign="top">


<fieldset><legend>'._('Economía principal').'</legend>

<table border="0"'.(ECONOMIA?'':' style="display:none;"').'>

<tr><td align="right">'._('Subsidio por desempleo').':</td><td><input style="text-align:right;" class="pols" type="text" name="pols_inem" size="3" maxlength="6" value="' . $pol['config']['pols_inem'] . '"'.$dis.' /> '.MONEDA.' '._('por día activo').'</td></tr>
<tr><td align="right">'._('Referencia').':</td><td><input style="text-align:right;" class="pols" type="text" name="pols_afiliacion" size="3" maxlength="6" value="' . $pol['config']['pols_afiliacion'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Crear empresa').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_empresa" size="3" maxlength="6" value="' . $pol['config']['pols_empresa'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Crear cuenta bancaria').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_cuentas" size="3" maxlength="6" value="' . $pol['config']['pols_cuentas'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Crear partido').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_partido" size="3" maxlength="6" value="' . $pol['config']['pols_partido'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Hacer examen').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_examen" size="3" maxlength="6" value="' . $pol['config']['pols_examen'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right"><acronym title="Mensaje privado a todos los Ciudadanos.">'._('mensaje global').'</acronym>:</td><td><input style="text-align:right;" type="text" name="pols_mensajetodos" size="3" maxlength="6" class="pols" value="' . $pol['config']['pols_mensajetodos'] . '"'.$dis.' /> '.MONEDA.' ('._('mínimo').' '.pols(300).')</td></tr>
<tr><td align="right">'._('Mensaje urgente').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_mensajeurgente" size="3" maxlength="6" value="' . $pol['config']['pols_mensajeurgente'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Crear chat').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_crearchat" size="3" maxlength="6" value="' . $pol['config']['pols_crearchat'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
</table>
</fieldset>

<fieldset><legend>'._('Economía Internacional').'</legend>
<table>
<tr><td align="right">'._('Arancel de salida').':</td><td><input style="text-align:right;" type="text" name="arancel_salida" size="3" maxlength="6" value="' . $pol['config']['arancel_salida'] . '"'.$dis.' /><b>%</b></td></tr>
</table>
</fieldset>

<fieldset><legend>'._('Impuestos').'</legend>
<table>
<tr><td align="right"><acronym title="Porcentaje que se impondrá al patrimonio de cada ciudadano que supere el limite. Se redondea. Incluye cuentas y personal.">'._('Impuesto de patrimonio').'</acronym>:</td><td><input style="text-align:right;" type="text" name="impuestos" size="3" maxlength="6" value="' . $pol['config']['impuestos'] . '"'.$dis.' /><b>%</b></td></tr>
<tr><td align="right"><acronym title="Limite minimo de patrimonio para recibir impuestos.">'._('Mínimo patrimonio').'</acronym>:</td><td><input class="pols" style="text-align:right;" type="text" name="impuestos_minimo" size="3" maxlength="6" value="' . $pol['config']['impuestos_minimo'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right"><acronym title="Impuesto fijo diario por cada empresa.">'._('Impuesto de empresa').'</acronym>:</td><td><input class="pols" style="text-align:right;" type="text" name="impuestos_empresa" size="3" maxlength="6" value="' . $pol['config']['impuestos_empresa'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
</table>
</fieldset>

<fieldset><legend>'._('Mapa').'</legend>
<table>
<tr><td align="right">'._('Precio solar').':</td><td><input style="text-align:right;" class="pols" type="text" name="pols_solar" size="3" maxlength="6" value="' . $pol['config']['pols_solar'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Factor de propiedad').':</td><td><input style="text-align:right;" type="text" name="factor_propiedad" size="3" maxlength="6" value="' . $pol['config']['factor_propiedad'] . '"'.$dis.' /> * '._('superficie = coste').'</td></tr>
';

$sel = '';

	$txt .= '<tr><td colspan="2"></td></tr></table>
</fieldset>

</td><td valign="top">


<fieldset><legend>'._('Salarios').'</legend>
<table border="0" cellspacing="3" cellpadding="0"'.(ECONOMIA?'':' style="display:none;"').'>';

	$result = sql("SELECT nombre, cargo_ID, salario
FROM cargos
WHERE pais = '".PAIS."'
ORDER BY salario DESC");
	while($r = r($result)){
		$txt .= '<tr><td align="right">' . $r['nombre'] . ' <img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" title="'.$r['nombre'].'" /></td><td><input style="text-align:right;" type="text" name="salario_' . $r['cargo_ID'] . '" size="3" maxlength="6" class="pols" value="' . $r['salario'] . '"'.$dis.' /> '.MONEDA.'</td></tr>';
	}

	$txt .= '
</table>
</fieldset>

</td></tr></table>

<p style="text-align:center;">'.boton(_('Guardar'), ($dis?false:'submit'), false, 'large red').'</p>

</form>';




	} else {


		function print_td_url($titulo, $name, $desc='') {
			return '<tr>
<td align="right" title="'.$desc.'">'.$titulo.':</td>
<td><input type="url" name="url_'.$name.'" value="'.$pol['config']['url'][$name].'" placeholder="http://" size="30" /></td>
</tr>';
		}

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

	$defcon = '<select name="defcon"'.$dis.' style="font-size:25px;color:grey;">';
	for ($i=5;$i>=1;$i--) {
		if ($i == $pol['config']['defcon']) { $sel = ' selected="selected"'; } else { $sel = ''; }
		$defcon .= '<option value="' . $i . '" style="background:' . $defcon_bg[$i] . ';"' . $sel . '>' . $i . '</option>';
	}
	$defcon .= '</select>';

	$txt .= '
<form action="'.accion_url().'a=gobierno&b=config" method="post" enctype="multipart/form-data">

<table border="0" cellspacing="3" cellpadding="0"><tr><td valign="top">


<fieldset><legend>'._('Configuración principal').'</legend>

<table border="0" cellspacing="3" cellpadding="0">


<tr><td align="right">URL:</td><td>http://<b>'.PAIS.'</b>.virtualpol.com</td></tr>

<tr><td align="right">'._('Nombre').':</td><td><input type="text" name="pais_des" size="24" maxlength="40" value="'.$pol['config']['pais_des'].'" /></td></tr>



<tr><td align="right">'._('Tipo de plataforma').':</td><td>
<select name="tipo">';
foreach (array('plataforma', 'asamblea', 'simulador') AS $tipo) {
	$txt .= '<option value="'.$tipo.'"'.($tipo==$pol['config']['tipo']?' selected="selected"':'').'>'.ucfirst($tipo).'</option>';
}
$txt .= '
</select></td></tr>


<tr><td align="right">'._('Zona horaria').':</td><td>
<select name="timezone">';
foreach (array('Europe/Madrid', 'America/New_York', 'Chile/Continental') AS $tipo) {
	$txt .= '<option value="'.$tipo.'"'.($tipo==$pol['config']['timezone']?' selected="selected"':'').'>'.ucfirst($tipo).'</option>';
}
$txt .= '
</select></td></tr>


<tr><td align="right">'._('Idioma').':</td><td><select name="lang">';
	$result = sql("SELECT valor FROM config WHERE pais = '".PAIS."' AND dato = 'lang'");
	while ($r = r($result)) { $plataforma_lang = $r['valor']; }

	foreach ($vp['langs'] AS $loc => $lang) {
		$txt .= '<option value="'.$loc.'"'.($loc==$plataforma_lang?' selected="selected"':'').'>'.$lang.'</option>';
	}
	$txt .= '</select></td></tr>

'.(!ECONOMIA?'<input type="hidden" name="defcon" value="5" /><input type="hidden" name="online_ref" value="0" />':'<tr><td align="right">DEFCON:</td>
<td>'.$defcon.'</td></tr>

<tr><td align="right">'._('Referencia').':</td>
<td><input type="number" name="online_ref" size="3" maxlength="10" value="' . round($pol['config']['online_ref']/60) . '" min="5" max="90" required /> min online (' . duracion($pol['config']['online_ref'] + 1) . ')</td>

</tr>');

$palabra_gob = explode(':', $pol['config']['palabra_gob']);


$txt .= '

<tr><td align="right">'._('Expiración de candidatura tras').':</td>
<td><input type="number" name="examenes_exp" value="'.$pol['config']['examenes_exp'].'" min="5" max="90" required /> '._('días').' '._('inactivo').'<td></tr>

<tr><td align="right">'._('Expiración chats').':</td>
<td><input type="number" name="chat_diasexpira" value="'.$pol['config']['chat_diasexpira'].'" min="10" max="90" required /> <acronym title="Dia inactivos">'._('días').'</acronym></td></tr>



<tr><td valign="top" colspan="2">'._('Mensaje del Gobierno').':<br />
<textarea name="palabra_gob" style="width:400px;height:100px;">'.strip_tags($pol['config']['palabra_gob']).'</textarea>
</td></tr>

</table>
</fieldset>


<fieldset><legend>URLs (EN DESARROLLO...)</legend>
<table>
'.print_td_url('Carta Magna', 'cartamagna', 'Documento, constitucion, declaración, ley, reglas o normas principales.').'
'.print_td_url('Ayuda', 'ayuda', 'Documento de ayuda').'
'.print_td_url('Bienvenida', 'bienvenida', 'Documento de bienvenida').'
'.print_td_url('Vídeo', 'video', 'Video de introducción a la plataforma').'
'.print_td_url('Facebook', 'fbfanpage', 'Fanpage de Facebook').'
'.print_td_url('Twitter', 'twitter', 'Cuenta de twitter').'
'.print_td_url('Google+', 'googleplus', 'Cuenta de Google+').'
</table>
</fieldset>


</td><td valign="top">



<fieldset><legend>'._('Diseño').'</legend>
<table>
<tr>
<td align="right">'._('Tapiz').':</td>
<td>
<select id="fondos" name="bg">
<option value="">'._('Por defecto').'</option>';

$sel2[$pol['config']['bg']] = ' selected="selected"';

$directorio = opendir(RAIZ.'/img/bg/'); 
while ($archivo = readdir($directorio)) {
	if (preg_match("/.(gif|jpg|png)$/i", $archivo)) {
		$txt .= '<option value="'.$archivo.'"'.$sel2[$archivo].' onclick="change_bg(\''.$archivo.'\')"  onmouseover="change_bg(\''.$archivo.'\')">'.$archivo.'</option>';
	}
}
closedir($directorio); 

$txt .= '</select>
</td>
</tr>

<tr>
<td align="right">'._('Añadir tapiz').':</td>
<td nowrap><input type="file" name="nuevo_tapiz" accept="image/jpg" /> (jpg, 1440x100)</td>
</tr>

<tr>
<td align="right" nowrap>'._('Bandera').' (80x50):</td>
<td nowrap><img src="'.IMG.'banderas/'.PAIS.'.png?'.rand(10000,99999).'" width="80" height="50" style="border:1px solid #CCC;background:#FFF;" />  (png, 80x50, max 50kb)<br /><input type="file" name="nuevo_bandera" accept="image/png" /></td>
</tr>


<tr>
<td align="right" nowrap>'._('Logo').' (200x60):</td>
<td nowrap><img src="'.IMG.'banderas/'.PAIS.'_logo.png?'.rand(10000,99999).'" width="200" height="60" style="border:1px solid #CCC;background:#FFF;" />  (png, 200x60)<br /><input type="file" name="nuevo_logo" accept="image/png" /></td>
</tr>




<tr>
<td align="right">'._('Color de fondo').':</td>
<td><input type="color" name="bg_color" value="'.strtolower($pol['config']['bg_color']).'" style="background:'.$pol['config']['bg_color'].';width:150px;" /></td>
</tr>

</table>
</fieldset>

<p>'.boton(_('Guardar'), ($dis?false:'submit'), false, 'large red').'</p>

</td></tr></table>

</form>';

}
	break;






case 'expulsiones':

if ($_GET['b'] == 'expulsar') { // /control/expulsiones/expulsar

	$txt_title = 'Expulsar: '.$_GET['c'];
	$txt_nav = array('/control'=>_('Control'), '/control/expulsiones'=>_('Expulsiones'), _('Expulsar'));


	if (isset($sc[$pol['user_ID']])) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }


	if (is_numeric(str_replace('-', '', $_GET['c']))) {
		$nicks = array();
		$result = sql("SELECT nick FROM users WHERE ID IN ('".implode("','", explode('-', $_GET['c']))."') AND estado != 'expulsado'");
		while ($r = r($result)) { $nicks[] = $r['nick']; }
		$_GET['c'] = implode('-', $nicks);
	}


	$txt .= '

<p>'._('Las expulsiones son efectuadas por los Supervisores del Censo (SC), consiste en un bloqueo definitivo a un usuario y su puesta en proceso de eliminación forzada tras 5 dias, durante este periodo es reversible. Las expulsiones se aplican por incumplimiento las <a href="http://www.virtualpol.com/TOS">Condiciones de Uso</a>').'.</p>

<form action="'.accion_url().'a=expulsar" method="post">

<ol>
<li><b>'._('Nick').':</b> '._('usuarios a expulsar').'.<br />
<input type="text" value="'.str_replace('-', ' ', $_GET['c']).'" name="nick" size="50" maxlength="900" style="font-weight:bold;" required />
<br /><br /></li>

<li>'._('<b>Motivo de expulsión:</b> si son varios elegir el mas claro').'.<br />
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


<li><b>Caso <input type="text" name="caso" size="8" maxlength="20" /></b> '._('Solo en caso de clones').'.<br /><br /></li>

<li><b>Pruebas:</b> anotaciones o pruebas sobre la expulsion. Confidencial, solo visible por los SC.<br />
<textarea name="motivo" style="color:green;font-weight:bold;width:500px;height:120px;"></textarea>
<br /><br /></li>


<li>'.boton(_('Expulsar'), ($disabled?false:'submit'), false, 'large red').'</li></ol></form>	
';


} elseif (($_GET['b'] == 'info') AND ($_GET['c']) AND (isset($sc[$pol['user_ID']]))) {

		$result = sql("SELECT *,
(SELECT nick FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = expulsiones.autor LIMIT 1) AS nick_autor
FROM expulsiones
WHERE ID = '".$_GET['c']."' LIMIT 1");
		while($r = r($result)){
			$txt .= '
<p><b>'.crear_link($r['expulsado'], 'nick', $r['expulsado_estado']).'</b> '._('fue expulsado por').' <b>'.crear_link($r['nick_autor']).'</b>.</p>

<p>'._('Razón').': <b>'.$r['razon'].'</b></p>

<p>'._('Fecha').': '.$r['expire'].'</p>

<p>'._('Pruebas').':</p><p class="azul">'.str_replace("\n","<br />", $r['motivo']).'</p>';
		}
} else {


	$txt_title = 'Control: '._('Expulsiones');
	$txt_nav = array('/control'=>_('Control'), '/control/expulsiones'=>_('Expulsiones'));

	$txt .= '
<p>'._('Las expulsiones son efectuadas por los Supervisores del Censo (SC). Consiste en un bloqueo definitivo a un usuario y su puesta en proceso de eliminación forzada tras 5 dias, durante este periodo es reversible. Las expulsiones se aplican por incumplimiento las <a href="http://www.virtualpol.com/TOS">Condiciones de Uso</a> (con la excepción de Registro erroneo y Test de desarrollo). Los Supervisores del Censo son ciudadanos con más de 1 año de antiguedad y elegidos por democracia directa, mediante el "voto de confianza", actualizado cada Domingo a las 20:00').'.</p>

<table border="0" cellspacing="1" cellpadding="">
<tr>
<th>'._('Expulsado').'</th>
<th>'._('Cuando').'</th>
<th>'._('Por').'</th>
<th>'._('Motivo').'</th>
<th></th>
</tr>';


	$result = sql("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado,
(SELECT pais FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_pais,
(SELECT estado FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = expulsiones.autor LIMIT 1) AS nick_autor
FROM expulsiones
WHERE estado != 'indultado'
ORDER BY expire DESC");
	while($r = r($result)){
		
		if ((isset($sc[$pol['user_ID']])) AND ($r['expulsado_pais']) AND ($r['estado'] == 'expulsado')) { 
			$expulsar = boton(_('Cancelar'), accion_url().'a=expulsar&b=desexpulsar&ID='.$r['ID'], '&iquest;Seguro que quieres CANCELAR la EXPULSION del usuario: '.$r['tiempo'].'?', 'small red'); 
		} elseif ($r['estado'] == 'cancelado') { $expulsar = '<b style="font-weight:bold;">'._('Cancelado').'</b>'; } else { $expulsar = ''; }

		if (!$r['expulsado_estado']) { $r['expulsado_estado'] = 'expulsado'; }

		$txt .= '
<tr><td valign="top" nowrap="nowrap">'.($r['estado'] == 'expulsado'?'<img src="'.IMG.'varios/expulsar.gif" alt="Expulsado" border="0" /> ':'<img src="'.IMG.'cargos/0.gif" border="0" /> ').'<b>'.crear_link($r['tiempo'], 'nick', $r['expulsado_estado'], $r['expulsado_pais']) . '</b></td>
<td valign="top" align="right" valign="top" nowrap="nowrap"><acronym title="' . $r['expire'] . '">'.timer($r['expire']).'</acronym></td>
<td valign="top">'.crear_link($r['nick_autor']).'</td>
<td valign="top"><b style="font-size:13px;">'.$r['razon'].'</b></td>
<td valign="top" align="center">'.$expulsar.'</td>
<td>'.(isset($sc[$pol['user_ID']])&&$r['motivo']!=''?'<a href="/control/expulsiones/info/'.$r['ID'].'/">#</a>':'').'</td>
</tr>' . "\n";

		}
		$txt .= '</table><p>Indultados de forma excepcional todos las expulsiones anteriores al 1 de Enero del 2012.</p>';
	}
	break;






case 'kick':
	$txt_title = _('Control').': '._('Kicks');
	$txt_nav = array('/control'=>_('Control'), _('Kicks'));
	$txt_tab = array('/control/kick/expulsar/'=>_('Kickear'));
	
	if (($_GET['b'] == 'info') AND ($_GET['c'])) {

		$result = sql("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = kicks.autor LIMIT 1) AS nick_autor
FROM kicks
WHERE pais = '".PAIS."' AND ID = '".$_GET['c']."' LIMIT 1");
		while($r = r($result)){
			$txt .= '
<p>'._('Motivo').': <b>'.$r['razon'].'</b></p>

<p>'._('Pruebas').':</p>

<p class="azul">'.str_replace("\n","<br />", $r['motivo']).'</p>';
		}

	} elseif ($_GET['b']) {
		
		$txt_nav = array('/control'=>_('Control'), '/control/kicks'=>_('Kicks'), _('Kickear'));

		if ($_GET['b'] == 'expulsar') { $_GET['b'] = ''; }
		if (nucleo_acceso($vp['acceso']['kick'])) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }
		$txt .= '
<p>'._('Esta acción privilegiada bloquea totalmente las acciones de un Ciudadano y los que comparten su IP').'.</p>

<form action="'.accion_url().'a=kick" method="post">
'.($_GET['c']?'<input type="hidden" name="chat_ID" value="'.$_GET['c'].'" />':'').'
<ol>
<li><b>'._('Nick').':</b> '._('el ciudadano').'.<br /><input type="text" value="' . $_GET['b'] . '" name="nick" size="20" maxlength="20" required /></li>

<li><b>'._('Duración').':</b> '._('duración temporal de este kick').'.<br />
<select name="expire">
<option value="120">2 min</option>
<option value="300">5 min</option>
<option value="600">10 min</option>
<option value="900">15 min</option>
<option value="1200">20 min</option>
<option value="1800" selected="selected">30 min</option>
<option value="2700">45 min</option>
<option value="4500">75 min</option>
<option value="3600">1 horas</option>
<option value="5400">1.5 horas</option>
<option value="7200">2 horas</option>
<option value="18000">5 horas</option>
<option value="86400">1 día</option>
<option value="172800">2 días</option>
<option value="259200">3 días</option>
<option value="518400">6 días</option>
<option value="777600">9 días</option>
</select></li>

<li><b>'._('Motivo breve').':</b> '._('frase con el motivo de este kick, se preciso').'.<br /><input type="text" name="razon" size="60" maxlength="255" required /></li>

<li><b>'._('Pruebas').':</b> '._('puedes pegar aquí las pruebas sobre el kick').'.<br /><textarea name="motivo" cols="70" rows="6" style="color: green; font-weight: bold;" required></textarea></p></li>


<li>'.boton(_('Kickear'), ($disabled==''?'submit':false), false, 'red').'</li></ol></form>
			
';
	} else {
		$txt .= '
<table border="0" cellspacing="1" cellpadding="">
<tr>
<th colspan="2">'._('Estado').'</th>
<th>'._('Afectado').'</th>
<th>'._('Autor').'</th>
<th>'._('Cuando').'</th>
<th>'._('Tiempo').'</th>
<th>'._('Razón').'</th>
<th></th>
</tr>';

	sql("UPDATE kicks SET estado = 'inactivo' WHERE pais = '".PAIS."' AND estado = 'activo' AND expire < '" . $date . "'"); 
	$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); //30dias
	$result = sql("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo, user_ID,
(SELECT nick FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = kicks.autor LIMIT 1) AS nick_autor
FROM kicks
WHERE pais = '".PAIS."' AND expire > '" . $margen_30dias . "' AND estado != 'expulsado'
ORDER BY expire DESC");
	while($r = r($result)){
		if ((($r['autor'] == $pol['user_ID']) OR (nucleo_acceso($vp['acceso']['kick_quitar']))) AND ($r['estado'] == 'activo')) { $expulsar = boton('X', accion_url().'a=kick&b=quitar&ID='.$r['ID'], '&iquest;Seguro que quieres hacer INACTIVO este kick?'); } else { $expulsar = ''; }

		$duracion = '<acronym title="'.$r['expire'].'">' . duracion((time() + $r['tiempo']) - strtotime($r['expire'])).'</acronym>';

		if ($r['estado'] == 'activo') {
			$estado = '<span style="color:red;">'._('Activo').'</span>';
		} elseif ($r['estado'] == 'cancelado') {
			$estado = '<span style="color:grey;">'._('Cancelado').'</span>';
		} else {
			$estado = '<span style="color:grey;">'._('Inactivo').'</span>';
		}
		if (!$r['expulsado_estado']) { $r['expulsado_estado'] = 'expulsado'; }

		$txt .= '<tr><td valign="top"><img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /></td><td valign="top"><b>'.$estado.'</b></td><td valign="top"><b>'.($r['user_ID'] == 0?'Anonimo':crear_link($r['expulsado'], 'nick', $r['expulsado_estado'])).'</b></td><td valign="top" nowrap="nowrap"><img src="'.IMG.'cargos/'.$r['cargo'].'.gif" border="0" /> ' . crear_link($r['nick_autor']) . '</td><td align="right" valign="top" nowrap="nowrap"><acronym title="' . $r['expire'] . '">'.timer($r['expire']).'</acronym></td><td align="right" valign="top" nowrap="nowrap">' . duracion($r['tiempo']+1) . '</td><td><b style="font-size:13px;">'.($r['motivo']?'<a href="/control/kick/info/'.$r['ID'].'/">'.$r['razon'].'</a>':$r['razon']).'</b></td><td>'.$expulsar.'</td></tr>' . "\n";
	}
	$txt .= '</table>';


	}

	break;


case 'judicial':
	$txt_title = _('Control').': '._('Judicial');
	$txt_nav = array('/control'=>_('Control'), _('Judicial'));

	
	$txt .= '
<h2>1. '._('Sanciones').'</h2><hr />

<table border="0" cellspacing="1" cellpadding="">
<tr>
<th></th>
<th>'._('Ciudadano').'</th>
<th>'._('Hace').'</th>
<th></th>
</tr>';



	$result = sql("SELECT *,
(SELECT nick FROM users WHERE ID = transacciones.emisor_ID LIMIT 1) AS nick
FROM transacciones
WHERE pais = '".PAIS."' AND concepto LIKE '<b>SANCION %' AND receptor_ID = '-1'
ORDER BY time DESC");
	while($r = r($result)){
		$txt .= '<tr><td>'.pols('-'.$r['pols']).' '.MONEDA.'</td><td><b>'.crear_link($r['nick']).'</b></td><td><acronym title="'.$r['time'].'">'.timer($r['time']).'</acronym></td><td>'.$r['concepto'].'</td></tr>' . "\n";
	}




if (!nucleo_acceso($vp['acceso']['control_sancion'])) { $disabled = ' disabled="disabled"'; }

$txt .= '</table><br />

<form action="'.accion_url().'a=sancion" method="post">

<ol>
<li><b>'._('Nick').':</b>.<br /><input type="text" value="" name="nick" size="20" maxlength="20" /><br /><br /></li>

<li><b>'.MONEDA.' de multa:</b> el importe de la sanción, maximo 5000 '.MONEDA.' (en caso de no tener la cantidad requerida, se quedará en negativo).<br /><input style="color:blue;text-align:right;" type="text" name="pols" size="4" value="1" maxlength="4" /> '.MONEDA.'<br /><br /></li>

<li><b>Concepto:</b> breve frase con la razón de la sanción.<br /><input type="text" name="concepto" size="50" maxlength="100" /><br /><br /></li>

<li><input type="submit" style="color:red;" value="'._('Efectuar sanción').'"' . $disabled . ' /> &nbsp; <span style="color:red;"><b>[acción irreversible]</b></span></li></ol></form>
			
';
	break;




	default:
		$txt_title = _('Control');
		$txt_nav = array('/control'=>_('Control'));

		$txt .= '
<p class="amarillo" style="color:red;">'._('Zonas de control cuyo acceso está reservado a los ciudadanos que ejercen estos cargos').'.</p>

<table border="0" cellspacing="6">

<tr><td nowrap="nowrap"><a class="abig" href="/control/gobierno"><b>'._('Gobierno').'</b></a></td>
<td align="right" nowrap="nowrap"></td>
<td></td></tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /> <a class="abig" href="/control/kick/"><b>Kicks</b></a></td>
<td align="right" nowrap="nowrap"></td>
<td>Control de bloqueo temporal del acceso.</td>
</tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'varios/expulsar.gif" alt="Expulsado" border="0" /> <a class="abig" href="/control/expulsiones/"><b>'._('Expulsiones').'</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/21.gif" title="Supervisor del Censo" /></td>
<td>Expulsiones permanentes por incumplimiento del <a href="http://www.'.DOMAIN.'/">TOS</a>.</td>
</tr>

<tr>';


if (isset($sc[$pol['user_ID']])) {
	$txt .= '<td nowrap="nowrap"><a class="abig" href="/control/supervisor-censo/"><b>'._('Supervisión del censo').'</b></a></td>';
} else {
	$txt .= '<td nowrap="nowrap"><b class="abig gris">'._('Supervisión del censo').'</b></td>';
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

//THEME
include('theme.php');
?>
