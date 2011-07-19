<?php 
include('inc-login.php');


// load config full
$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

// load user cargos
$pol['cargos'] = cargos();

$sc = get_supervisores_del_censo();


switch ($_GET['a']) {


case 'supervisor-censo':

if (isset($sc[$pol['user_ID']])) {

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

	// nomenclatura
	foreach ($vp['paises'] AS $pais) { $paises .= ' <span style="background:'.$vp['bg'][$pais].';">'.$pais.'</span>'; }
	$nomenclatura = '<span style="float:right;">Plataformas:'.$paises.' | Estados: <b class="ciudadano">Ciudadano</b> <b class="turista">Turista</b> <b class="validar">Validar</b> <b class="expulsado">Expulsado</b></span>';

	// siglas partidos
	$result = mysql_query("SELECT ID, siglas FROM ".SQL."partidos", $link);
	while($r = mysql_fetch_array($result)) { $siglas[$r['ID']] = $r['siglas']; }

	if ($_GET['b'] == 'nuevos-ciudadanos') {

			$txt_title = 'Control: Supervision del Censo - Nuevos ciudadanos';
			$txt .= '<h1><a href="/control/">Control</a>: <a href="/control/supervisor-censo/">Supervisi&oacute;n del Censo</a> | <a href="/control/supervisor-censo/factores-secundarios/">Extras</a> | Nuevos ciudadanos | <a href="/control/expulsiones/">Expulsiones</a> | <a href="/control/expulsiones/expulsar">Expulsar</a></h1>

<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b>. Supervisores del Censo: <b>' . $supervisores . '</b></p>'.$nomenclatura;

			$txt .= '<h1>1. Actividad de nuevos Ciudadanos (ultimos 60)</h1><hr />
<table border="0" cellspacing="0" cellpadding="2">
<tr>
<th></th>
<th align="right" colspan="2"><acronym title="Tiempo desde que se registr&oacute;">Registro</acronym></th>
<th align="right">Online</th>
<th align="right"><acronym title="Tiempo desde el ultimo acceso">Ultimo</acronym></th>
<th align="right"><acronym title="Pais">P</acronym></th>
<th align="right"><acronym title="Votos ejercidos en Elecciones">E</acronym></th>
<th><acronym title="Confianza">C</acronym></th>
<th align="right"><acronym title="Visitas">V</acronym></th>
<th align="right"><acronym title="Paginas vistas">PV</acronym></th>
<th align="right"><acronym title="Mensajes en foro">F</acronym></th>
<th align="right"><acronym title="Mensajes privados enviados">P</acronym></th>
<th align="right">Email</th>
<th></th>
<th>IP</th>
</tr>';
	$result = mysql_query("SELECT *,
(SELECT COUNT(*) FROM ".SQL_MENSAJES." WHERE envia_ID = users.ID) AS num_priv,
(SELECT COUNT(*) FROM ".SQL."foros_msg WHERE user_ID = users.ID) AS num_foro,
(SELECT voto FROM ".SQL_VOTOS." WHERE estado = 'confianza' AND uservoto_ID = '" . $pol['user_ID'] . "' AND user_ID = users.ID LIMIT 1) AS has_votado
FROM users 
ORDER BY fecha_registro DESC
LIMIT 60", $link);
	while($r = mysql_fetch_array($result)) {
		$dia_registro = date('j', strtotime($r['fecha_registro']));
		
		$razon = '';
		if ($r['estado'] == 'expulsado') {
			$result2 = mysql_query("SELECT razon FROM ".SQL_EXPULSIONES." WHERE user_ID = '".$r['ID']."' LIMIT 1", $link);
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

		if ($r['has_votado']) { $has_votado = ' (' . confianza($r['has_votado']) . ')'; } else { $has_votado = ''; }

		
		$txt .= '<tr' . $td_bg . '>
<td align="right"><b>' . $dia_registro . '</b></td>
<td style="background:'.$vp['bg'][$r['pais']].';"><b>' . crear_link($r['nick'], 'nick', $r['estado']) . '</b></td>
<td align="right" nowrap="nowrap">'.timer($r['fecha_registro']).'</td>
<td align="right" nowrap="nowrap">' . $online . '</td>
<td align="right" nowrap="nowrap">'.timer($r['fecha_last']) . '</td>
<td>' . $siglas[$r['partido_afiliado']] . '</td>
<td align="right"><b>' . $r['num_elec'] . '</b></td>
<td nowrap="nowrap"><b>' . confianza($r['voto_confianza']) . '</b>' . $has_votado . '</td>
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




} else if ($_GET['b'] == 'factores-secundarios') {

	$txt_title = 'Control: Supervision del Censo | Extras';
	$txt .= '<h1><a href="/control/">Control</a>: <a href="/control/supervisor-censo/">Supervisi&oacute;n del Censo</a> | Extras | <a href="/control/supervisor-censo/nuevos-ciudadanos/">Nuevos ciudadanos</a> | <a href="/control/expulsiones/">Expulsiones</a> | <a href="/control/expulsiones/expulsar">Expulsar</a></h1>

<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b>. Supervisores del Censo: <b>' . $supervisores . '</b></p>'.$nomenclatura;


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


	$txt .= '<br /><h1>6. Emails at&iacute;picos</h1><hr /><table border="0" cellspacing="4">';
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
(SELECT nick FROM users WHERE ID = ".SQL_REFERENCIAS.".user_ID LIMIT 1) AS nick,
(SELECT COUNT(*) FROM ".SQL_REFERENCIAS." WHERE referer = ".SQL_REFERENCIAS.".referer AND new_user_ID != '0') AS num_registrados
FROM ".SQL_REFERENCIAS." 
GROUP BY referer HAVING COUNT(*) > 1
ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)) {

		$result2 = mysql_query("SELECT COUNT(*) AS num_registrados FROM ".SQL_REFERENCIAS." WHERE referer = '" . $r['referer'] . "' AND new_user_ID != '0'", $link);
		while($r2 = mysql_fetch_array($result2)) {
			if ($r2['num_registrados'] != 0) { $num_registrados = '+' . $r2['num_registrados']; } else { $num_registrados = ''; }
		}
		if ($r['referer'] == '') { $r['referer'] = '#referencia-directa'; $r['nick'] = '&nbsp;'; }

		$txt .= '<tr><td><b>' . crear_link($r['nick']) . '</b></td><td align="right"><b>' . $r['num'] . '</b></td><td align="right">' . $num_registrados . '</td><td><a href="' . $r['referer'] . '">' . $r['referer'] . '</a></td></tr>';
	}
	$txt .= '</table>';



	$txt .= '<br /><h1>8. M&aacute;s votos y menos actividad</h1><hr /><table border="0" cellspacing="4">
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
			$txt .= '<tr><td>' . crear_link($r['nick'], 'nick', $r['estado'], $r['pais']) . ' ' .			$siglas[$r['partido_afiliado']] . '</td><td align="right"><b>' . $r['num_elec'] . '</b></td><td>/</td><td align="right"><b>' . duracion($r['online']) . '</b></td><td><b>=</b></td><td>' . $r['factor'] . '</td><td align="right">'.$r['visitas'].'</td><td align="right">'.$r['paginas'].'</td><td>('.ocultar_IP($r['IP']).')</td></tr>';
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
		if ($r['num'] <= 10) {
			$result2 = mysql_query("SELECT ID, nick, estado, pais FROM users WHERE nav = '" . $r['nav'] . "' ORDER BY fecha_registro DESC", $link);
			while($r2 = mysql_fetch_array($result2)) {
				if ($clones) { $clones .= ' & '; }
				$clones .= crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']);
			}
		} else { $clones = '</b>(navegador muy comun)<b>'; }


		$txt .= '<tr><td align="right"><b>' . $r['num'] . '</b></td><td><b>' . $clones . '</b></td><td style="font-size:9px;">' . $r['nav'] . '</td></tr>';
	}
	$txt .= '</table>';


	} else { // principal

	$txt_title = 'Control: Supervision del Censo';
	$txt .= '<h1><a href="/control/">Control</a>: Supervisi&oacute;n del Censo | <a href="/control/supervisor-censo/factores-secundarios/">Extras</a> | <a href="/control/supervisor-censo/nuevos-ciudadanos/">Nuevos ciudadanos</a> | <a href="/control/expulsiones/">Expulsiones</a> | <a href="/control/expulsiones/expulsar">Expulsar</a></h1>

<p class="amarillo" style="color:red;"><b>C O N F I D E N C I A L</b>. Supervisores del Censo: <b>' . $supervisores . '</b></p>'.$nomenclatura;
	

	$txt .= '<h1>1. Coincidencias de IP</h1><hr /><table border="0" cellspacing="4">';
	$result = mysql_query("SELECT nick, IP, COUNT(*) AS num, host
FROM users 
GROUP BY IP HAVING COUNT(*) > 1
ORDER BY num DESC, fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$clones = array();
		$nota_SC = '';
		$desarrollador = false;
		$clones_expulsados = true;
		$result2 = mysql_query("SELECT ID, nick, estado, pais, partido_afiliado, nota_SC FROM users WHERE IP = '" . $r['IP'] . "' ORDER BY fecha_registro DESC", $link);
		while($r2 = mysql_fetch_array($result2)) {
			$nota_SC .= $r2['nota_SC'].' ';
			if ($r2['estado'] != 'expulsado') { $clones_expulsados = false; } 
			$clones[] = '<b>'.crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']) . '</b> ' . $siglas[$r2['partido_afiliado']];
		}
		if ((!$desarrollador) AND (!$clones_expulsados)) {
			$txt .= '<tr><td>' . $r['num'] . '</td><td>'.implode(' & ', $clones).'</td><td align="right" nowrap="nowrap">'.ocultar_IP($r['host'], 'host').'</td><td>'.ocultar_IP($r['IP']).'</td><td><em>'.$nota_SC.'</em></td></tr>';
		}
	}
	$txt .= '</table>';




	$txt .= '<br /><h1>2. Coincidencia de clave</h1><hr /><table border="0" cellspacing="4">';
	$result = mysql_query("SELECT ID, IP, COUNT(*) AS num, pass
FROM users 
GROUP BY pass HAVING COUNT(*) > 1
ORDER BY num DESC, fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
		if (($r['pass'] != 'mmm') OR ($r['pass'] != 'e10adc3949ba59abbe56e057f20f883e')) {

			$clones = array();
			$nota_SC = '';
			$result2 = mysql_query("SELECT ID, nick, pais, partido_afiliado, estado, nota_SC
FROM users 
WHERE pass = '" . $r['pass'] . "'", $link);
			$clones_expulsados = true;
			while($r2 = mysql_fetch_array($result2)) { 
				if ($r2['nick']) {
					$nota_SC .= $r2['nota_SC'].' ';
					if ($r2['estado'] != 'expulsado') { $clones_expulsados = false; } 
					$clones[] = crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']) . '</b> ' . $siglas[$r2['partido_afiliado']] . '<b>';
				} 
			}
			if (!$clones_expulsados) {
				$txt .= '<tr><td>' . $r['num'] . '</td><td><b>'.implode(' & ', $clones).'</b></td><td><em>'.$nota_SC.'</em></td></tr>';
			}
		}
	}
	$txt .= '</table>';




	$trazas_rep = array();
	$txt .= '<br /><h1>3. Traza (coincidencia de dispositivo)</h1><hr /><table border="0" cellspacing="4">';
	$result = mysql_query("SELECT ID AS user_ID, nick, estado, traza FROM users WHERE traza != '' ORDER BY fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$tn = 1;
		$trazas = explode(' ', $r['traza']);
		$trazas_clones = array();
		if ($r['estado'] == 'expulsado') { $mostrar = false; } else { $mostrar = true; }
		foreach ($trazas AS $unatraza) {
			$trazado = false;
			$result2 = mysql_query("SELECT nick, estado FROM users WHERE ID = '".$unatraza."' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)) {
				$tn++; $trazas_clones[] = crear_link($r2['nick'], 'nick', $r2['estado']);
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
		if ($mostrar == true) {
			$txt .= '<tr><td>'.$tn.'</td><td><b>'.crear_link($r['nick'], 'nick', $r['estado']).'</b>: <b>'.implode(' & ', $trazas_clones).'</b></td></tr>';
		}
	}
	$txt .= '</table>';





	$txt .= '<br /><h1>4. Ocultaci&oacute;n de conexi&oacute;n (proxys, TOR...)</h1><hr /><table border="0" cellspacing="4">';
	$array_searchtor = array('%anon%', '%tor%', '%vps%', '%proxy%');
	$sql_anon = '';
	foreach ($array_searchtor AS $filtro) { if ($sql_anon != '') { $sql_anon .= ' OR ';  } $sql_anon .= "hosts LIKE '".$filtro."'"; }
	$result = mysql_query("SELECT nick, estado, host, IP, nav FROM users WHERE ".$sql_anon." ORDER BY fecha_registro DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$txt .= '<tr><td><b>'.crear_link($r['nick'], 'nick', $r['estado']).'</b></td><td>'.ocultar_IP($r['IP']).'</td><td><b>'.ocultar_IP($r['host'], 'host').'</b></td><td style="font-size:10px;">'.$r['nav'].'</td></tr>';
	}
	$txt .= '</table>';

	$txt .= '<table border="0" cellspacing="4">';
	$result = mysql_query("SELECT ID, IP, nick, estado, pais, IP_proxy
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
				
				$host = IP2host($IP);
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
	$txt .= '</table>';









	}


	} else { $txt .= '<p class="amarillo" style="color:red;"><b>Acceso restringido a los Supervisores del Censo.</b></p>'; }
	break;






case 'gobierno':
	$txt_title = 'Control: Gobierno';
	if (nucleo_acceso($vp['acceso']['control_gobierno'])) { $dis = ''; } else { $dis = ' disabled="disabled"'; }

	$result = mysql_query("SELECT (SELECT nick FROM users WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS elnick
	 FROM ".SQL."estudios_users WHERE ID_estudio = '7' AND cargo = '1' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) { $presidente = $r['elnick']; }

	$result = mysql_query("SELECT (SELECT nick FROM users WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS elnick
	 FROM ".SQL."estudios_users WHERE ID_estudio = '19' AND cargo = '1' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) { $vicepresidente = $r['elnick']; }

	$defcon_bg = array('1' => 'white','2' => 'red','3' => 'yellow','4' => 'green','5' => 'blue');



	if ($_GET['b'] == 'foro') {

		$txt .= '<h1><a href="/control/">Control</a>: <a href="/control/gobierno/">Gobierno</a> | Control Foro</h1>
		
<br />
<form action="/accion.php?a=gobierno&b=subforo" method="post">

<table border="0" cellspacing="0" cellpadding="4" class="pol_table">

<tr>
<th colspan="2"></th>
<th colspan="2" align="center">Acceso</th>
<th colspan="2"></th>
</tr>

<tr>
<th>Orden</th>
<th>Foro/Descripcion</th>
<th style="background:#5CB3FF;">Leer</th>
<th style="background:#F97E7B;">Escribir</th>
<th></th>
<th></th>
</tr>';
	$subforos = '';
	$result = mysql_query("SELECT *,
(SELECT COUNT(*) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_hilos,
(SELECT SUM(num) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_msg
FROM ".SQL."foros WHERE estado = 'ok'
ORDER BY time ASC", $link);
	while($r = mysql_fetch_array($result)){

		if ($r['num_hilos'] == 0) { $del = '<input style="margin-bottom:-16px;" type="button" value="Eliminar" onClick="window.location.href=\'/accion.php?a=gobierno&b=eliminarsubforo&ID=' . $r['ID'] . '/\';">';
		} else { $del = ''; }



		$txt_li['leer'] = ''; $txt_li['escribir'] = '';
		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['leer'] .= '<option value="'.$at.'"'.($at==$r['acceso_leer']?' selected="selected"':'').'>'.ucfirst(str_replace("_", " ", $at)).'</option>';
		}
		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['escribir'] .= '<option value="'.$at.'"'.($at==$r['acceso_escribir']?' selected="selected"':'').($at=='anonimos'?' disabled="disabled"':'').'>'.ucfirst(str_replace("_", " ", $at)).'</option>';
		}


		$txt .= '<tr>
<td align="right"><input type="text" style="text-align:right;" name="'.$r['ID'].'_time" size="1" maxlength="3" value="'.$r['time'].'" /></td>
<td><a href="/foro/'.$r['url'].'/"><b>'.$r['title'].'</b></a><br />
<input type="text" name="'.$r['ID'].'_descripcion" size="25" maxlength="100" value="'.$r['descripcion'].'" /></td>


<td style="background:#5CB3FF;"><b><select name="'.$r['ID'].'_acceso_leer">'.$txt_li['leer'].'</select><br />
<input type="text" name="'.$r['ID'].'_acceso_cfg_leer" size="16" maxlength="900" value="'.$r['acceso_cfg_leer'].'" /></td>

<td style="background:#F97E7B;"><b><select name="'.$r['ID'].'_acceso_escribir">'.$txt_li['escribir'].'</select><br />
<input type="text" name="'.$r['ID'].'_acceso_cfg_escribir" size="16" maxlength="900" value="'.$r['acceso_cfg_escribir'].'" /></td>


<td align="right" style="color:#999;">'.number_format($r['num_hilos'], 0, ',', '.').' hilos<br />
'.number_format($r['num_msg'], 0, ',', '.').' mensajes</td>
<td>'.$del.'</td>
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
	$("body").css("background","#FFFFFF url(\''.IMG.'bg/"+img+"\') repeat top left");
}
</script>';





	$txt .= '<h1><a href="/control/">Control</a>: Gobieno | <a href="/control/gobierno/foro/">Control Foro</a></h1>

<br />
<form action="/accion.php?a=gobierno&b=config" method="post">

<table border="0" cellspacing="3" cellpadding="0" class="pol_table"><tr><td valign="top">

<table border="0" cellspacing="3" cellpadding="0" class="pol_table">


<tr><td align="right">Descripcion:</td><td><input type="text" name="pais_des" size="24" maxlength="40" value="'.$pol['config']['pais_des'].'"'.$dis.' /></td></tr>
<tr><td align="right">DEFCON:</td><td>' . $defcon . '</td></tr>
<tr><td align="right">Referencia tras:</td><td><input style="text-align:right;" type="text" name="online_ref" size="3" maxlength="10" value="' . round($pol['config']['online_ref']/60) . '"'.$dis.' /> min online (' . duracion($pol['config']['online_ref'] + 1) . ')</td></tr>
<tr><td align="right">Esca&ntilde;os:</td><td><input style="text-align:right;" type="text" name="num_escanos" size="3" maxlength="10" value="' . $pol['config']['num_escanos'] . '"'.$dis.' /> Diputados</td></tr>';

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

<tr><td align="right">Expiraci&oacute;n chats:</td><td><input type="text" name="chat_diasexpira" size="2" maxlength="6" value="'.$pol['config']['chat_diasexpira'].'"'.$dis.' /> <acronym title="Dia inactivos">Dias</acronym></td></tr



<tr><td colspan="2"><br /><b>Dise&ntilde;o:</b></td></tr>
<tr><td align="right">Imagen tapiz:</td>
<td>
<select id="fondos" name="bg">
<option value="">Por defecto</option>';

$sel2[$pol['config']['bg']] = ' selected="selected"';

$directorio = opendir(RAIZ.'/img/bg/'); 
while ($archivo = readdir($directorio)) {
	if (($archivo != '.') AND ($archivo != '..') AND (substr($archivo,0,1) != '.') AND ($archivo != 'index.php')) {
		$txt .= '<option value="'.$archivo.'"'.$sel2[$archivo].' onclick="change_bg(\''.$archivo.'\')"  onmouseover="change_bg(\''.$archivo.'\')">'.$archivo.'</option>';
	}
}
closedir($directorio); 



$txt .= '</select>
</tr>

</td></tr></table>


<table border="0"'.(ECONOMIA?'':' style="display:none;"').'>

<tr><td colspan="2"></td></tr>

<tr><td colspan="2" class="amarillo"><b class="big">Econom&iacute;a</b> '.MONEDA.'</td></tr>



<tr><td align="right">Inem'.PAIS.':</td><td><input style="text-align:right;" class="pols" type="text" name="pols_inem" size="3" maxlength="6" value="' . $pol['config']['pols_inem'] . '"'.$dis.' /> '.MONEDA.' por d&iacute;a activo</td></tr>
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
<tr><td align="right"><acronym title="Porcentaje que se impondr&aacute; al patrimonio de cada ciudadano que supere el limite. Se redondea. Incluye cuentas y personal.">Impuesto de patrimonio</acronym>:</td><td><input style="text-align:right;" type="text" name="impuestos" size="3" maxlength="6" value="' . $pol['config']['impuestos'] . '"'.$dis.' /><b>%</b></td></tr>
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


	$result = mysql_query("SELECT nombre, ID, salario
FROM ".SQL."estudios
ORDER BY salario DESC", $link);
	while($r = mysql_fetch_array($result)){
		$txt .= '<tr><td align="right">' . $r['nombre'] . ':</td><td><input style="text-align:right;" type="text" name="salario_' . $r['ID'] . '" size="3" maxlength="6" class="pols" value="' . $r['salario'] . '"'.$dis.' /> '.MONEDA.'</td></tr>';
	}




	$txt .= '
</table>

</td></tr></table>

<!--<table border="0" cellspacing="3" cellpadding="0" class="pol_table">

<tr><td colspan="2" class="amarillo"><b class="big">Emoticonos</b></td></tr>
<tr><td><a href="'.IMG.'smiley/roto2.gif"><p>roto2</p></a></td><td><input type="checkbox" value="roto2" /></td>
</table>-->



<p style="text-align:center;"><input value="EJECUTAR" style="font-size:20px;" type="submit"'.$dis.' /></p>

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


	if (isset($sc[$pol['user_ID']])) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }
	$txt .= '<h1><a href="/control/">Control</a>: <img src="'.IMG.'expulsar.gif" alt="Expulsion" border="0" /> <a href="/control/expulsiones/">Expulsiones</a> | Expulsar</h1>

<p>Esta acci&oacute;n es efectuada por los Supervisores del Censo (SC), consiste en un bloqueo definitivo a un usuario y su puesta en proceso de eliminaci&oacute;n forzada tras 10 dias, pasado ese periodo de tiempo es irreversible. Seg&uacute;n el <a href="http://www.virtualpol.com/legal">TOS</a> es motivo de expulsi&oacute;n <em>2.c La utilizaci&oacute;n malintencionada del privilegio de expulsi&oacute;n.</em></p>

<form action="/accion.php?a=expulsar" method="post">

<ol>
<li><b>Nick:</b> el usuario a expulsar.<br />
<input type="text" value="'.$_GET['c'].'" name="nick" size="20" maxlength="20" />
<br /><br /></li>

<li><b>Motivo de expulsi&oacute;n:</b> si son varios elegir el mas claro.<br />
<select name="razon">

<optgroup label="Clones">
	<option value="Clones: 1.a" selected="selected">1.a Clones:</option>
	<option value="Clones: 1.b">1.b Uso de una direcci&oacute;n de email temporal o de uso no habitual.</option>
	<option value="Clones: 1.c">1.c Uso de cualquier m&eacute;todo cuyo fin sea ocultar la conexi&oacute;n a Internet.</option>
</optgroup>

<optgroup label="Mantenimiento">
	<option value="Registro erroneo.">Registro erroneo.</option>
	<option value="Peticion propia.">Peticion propia.</option>
	<option value="Test de desarrollo.">Test de desarrollo.</option>
</optgroup>

<optgroup label="Ataque al sistema">
	<option value="Ataque al sistema: 2.a">2.a Uso o descubrimiento de bugs del sistema, sea cual fuere su finalidad, sin reportarlo inmediatamente u obrando de mala fe.</option>
	<option value="Ataque al sistema: 2.b">2.b Ejecutar cualquier tipo de acci&oacute;n que busque causar un perjuicio al mismo.</option>
	<option value="Ataque al sistema: 2.c">2.c La utilizaci&oacute;n malintencionada del privilegio de expulsi&oacute;n.</option>
	<option value="Ataque al sistema: 2.d">2.d Faltar gravemente al respeto por lo personal a un Administrador.</option>
</optgroup>


<optgroup label="Ataque a la comunidad">
	<option value="Ataque a la comunidad: 3.a">3.a Publicaci&oacute;n de contenido altamente violento, obsceno o, en todo caso, no apto para menores de edad.</option>
	<option value="Ataque a la comunidad: 3.b">3.b Hacer apolog&iacute;a del terrorismo o ideolog&iacute;as que defiendan el uso de la violencia.</option>
	<option value="Ataque a la comunidad: 3.c">3.c Amenazar a otros usuarios con repercusiones fuera de la comunidad.</option>
	<option value="Ataque a la comunidad: 3.d">3.d El uso reiterado o sistem&aacute;tico de “kicks” superiores a 15 minutos sin cobertura legal dentro de la comunidad.</option>
</optgroup>


</select><br /><br /></li>


<li><b>Caso <input type="text" name="caso" size="8" maxlength="20" /></b> Solo en caso de clones.<br /><br /></li>

<li><b>Pruebas:</b> anotaciones o pruebas sobre la expulsion. Confidencial, solo visible por los SC.<br />
<textarea name="motivo" cols="70" rows="6" style="color: green; font-weight: bold;"></textarea>
<br /><br /></li>


<li><input type="submit" value="Ejecutar EXPULSION" onclick="if (!confirm(\'&iquest;Seguro que quieres EXPULSAR a este usuario?\')) { return false; }"'.$disabled.' /></li></ol></form>	
';


} elseif (($_GET['b'] == 'info') AND ($_GET['c']) AND (isset($sc[$pol['user_ID']]))) {

		$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = expulsiones.autor LIMIT 1) AS nick_autor
FROM expulsiones
WHERE ID = '".$_GET['c']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){
			$txt .= '<h1><a href="/control/">Control</a>: <a href="/control/expulsiones/">Expulsiones</a> | #'.$_GET['c'].'</h1>

<p><b>'.crear_link($r['expulsado'], 'nick', $r['expulsado_estado']).'</b> fue expulsado por <b>'.crear_link($r['nick_autor']).'</b>.</p>

<p>Raz&oacute;n: <b>'.$r['razon'].'</b></p>

<p>Fecha: '.$r['expire'].'</p>

<p>Pruebas:</p><p class="azul">'.str_replace("\n","<br />", $r['motivo']).'</p>';
		}
} else {


	$txt_title = 'Control:  Expulsiones';
	$txt .= '<h1><a href="/control/">Control</a>: <img src="'.IMG.'expulsar.gif" alt="Expulsado" border="0" /> Expulsiones | <a href="/control/expulsiones/expulsar">Expulsar</a></h1>

<p>Una expulsi&oacute;n bloquea de forma perpetua a un usuario de <a href="http://www.virtualpol.com/">VirtualPol</a>. Esta responsabilidad est&aacute; a cargo de los Supervisores del Censo (son los 7 m&aacute;s votados de confianza, con m&aacute;s de 365 dias de veteran&iacute;a). Son elegidos mediante democracia directa. Las expulsiones siguen las reglas del <a href="http://www.virtualpol.com/legal">TOS</a>.</p>

<table border="0" cellspacing="1" cellpadding="" class="pol_table">
<tr>
<th>Expulsado</th>
<th>Pa&iacute;s</th>
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
			$expulsar = boton('Cancelar', '/accion.php?a=expulsar&b=desexpulsar&ID=' . $r['ID'], '&iquest;Seguro que quieres CANCELAR la EXPULSION del usuario: '.$r['tiempo'].'?'); 
		} elseif ($r['estado'] == 'cancelado') { $expulsar = '<b style="font-weight:bold;">Cancelado</b>'; } else { $expulsar = ''; }

		if (!$r['expulsado_estado']) { $r['expulsado_estado'] = 'expulsado'; }

		$txt .= '
<tr><td valign="top" nowrap="nowrap">'.($r['estado'] == 'expulsado'?'<img src="'.IMG.'expulsar.gif" alt="Expulsado" border="0" /> ':'<img src="'.IMG.'cargos/0.gif" border="0" /> ').'<b>' . crear_link($r['tiempo'], 'nick', $r['expulsado_estado'], $r['expulsado_pais']) . '</b></td>
<td valign="top">'.$r['expulsado_pais'].'</td>
<td valign="top" align="right" valign="top" nowrap="nowrap"><acronym title="' . $r['expire'] . '">'.timer($r['expire']).'</acronym></td>
<td valign="top">'.crear_link($r['nick_autor']).'</td>
<td valign="top"><b style="font-size:13px;">' . $r['razon'] . '</b></td>
<td valign="top" align="center">' . $expulsar . '</td>
<td>'.(isset($sc[$pol['user_ID']])&&$r['motivo']!=''?'<a href="/control/expulsiones/info/'.$r['ID'].'/">#</a>':'').'</td>
</tr>' . "\n";

		}
		$txt .= '</table><hr /><p>Las expulsiones son ejecutadas por los desarrolladores a cualquier usuario que no ejerzan ningun cargo en su pais.</p>
<p>Las expulsiones pueden ser canceladas por el <b><img src="'.IMG.'cargos/7.gif" />Presidente</b> y <b><img src="'.IMG.'cargos/9.gif" />Juez Supremo</b>, antes de que el expulsado sea eliminado (ocurre tras 10 dias inactivo).</p>';
	}
	break;






case 'kick':
	$txt_title = 'Control: Kicks';
	
	if (($_GET['b'] == 'info') AND ($_GET['c'])) {

		$result = mysql_query("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM users WHERE ID = ".SQL."ban.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = ".SQL."ban.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = ".SQL."ban.autor LIMIT 1) AS nick_autor
FROM ".SQL."ban
WHERE ID = '" . $_GET['c'] . "' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){
			$txt .= '<h1><a href="/control/">Control</a>: <a href="/control/kick/">Kicks</a> | info '.$_GET['c'].'</h1>
<p>Motivo: <b>'.$r['razon'].'</b></p>

<p>Pruebas:</p><p class="azul">'.str_replace("\n","<br />", $r['motivo']).'</p>';
		}

	} elseif ($_GET['b']) {
		if ($_GET['b'] == 'expulsar') { $_GET['b'] = ''; }
		if (nucleo_acceso($vp['acceso']['kick'])) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }
		$txt .= '<h1><a href="/control/">Control</a>: <a href="/control/kick/">Kicks</a> | <img src="'.IMG.'kick.gif" alt="Kick" border="0" /> Kickear</h1><p>Esta acci&oacute;n privilegiada bloquea totalmente las acciones de un Ciudadano y los que comparten su IP.</p>

<form action="/accion.php?a=kick" method="post">
'.($_GET['c']?'<input type="hidden" name="chat_ID" value="'.$_GET['c'].'" />':'').'
<ol>
<li><b>Nick:</b> el Ciudadano.<br /><input type="text" value="' . $_GET['b'] . '" name="nick" size="20" maxlength="20" /><br /><br /></li>

<li><b>Duraci&oacute;n:</b> duraci&oacute;n temporal de este kick.<br />
<select name="expire">
<option value="120">2 minutos</option>
<option value="300">5 minutos</option>
<option value="600">10 minutos</option>
<option value="900">15 minutos</option>
<option value="1200">20 minutos</option>
<option value="1800" selected="selected">30 minutos</option>
<option value="3600">1 hora</option>
<option value="18000">5 horas</option>
<option value="86400">1 d&iacute;a</option>
<option value="172800">2 d&iacute;as</option>
<option value="259200">3 d&iacute;as</option>
<option value="518400">6 d&iacute;as</option>
<option value="777600">9 d&iacute;as</option>
</select><br /><br /></li>

<li><b>Motivo breve:</b> frase con el motivo de este kick. Se preciso.<br /><input type="text" name="razon" size="60" maxlength="255" /><br /><br /></li>

<li><b>Pruebas:</b> opcionalmente puedes pegar aqui las anotaciones o pruebas sobre el kick.<br /><textarea name="motivo" cols="70" rows="6" style="color: green; font-weight: bold;"></textarea></p>

<br /><br /></li>


<li><input type="submit" value="Ejecutar KICK"' . $disabled . ' /></li></ol></form>
			
';
	} else {
		$txt .= '<h1><a href="/control/">Control</a>: <img src="'.IMG.'kick.gif" alt="Kick" border="0" /> Kicks</h1><p>' . boton('KICK', '/control/kick/expulsar/') . ' Un kick bloquea temporalmente a un Ciudadano y su IP de todas las acciones en '.PAIS.'.</p>

<table border="0" cellspacing="1" cellpadding="" class="pol_table">
<tr>
<th colspan="2">Estado</th>
<th>Afectado</th>
<th>Autor</th>
<th>Cuando</th>
<th>Tiempo</th>
<th>Raz&oacute;n</th>
<th></th>
</tr>';

	mysql_query("UPDATE ".SQL."ban SET estado = 'inactivo' WHERE estado = 'activo' AND expire < '" . $date . "'", $link); 
	$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); //30dias
	$result = mysql_query("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo, user_ID,
(SELECT nick FROM users WHERE ID = ".SQL."ban.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = ".SQL."ban.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = ".SQL."ban.autor LIMIT 1) AS nick_autor
FROM ".SQL."ban
WHERE expire > '" . $margen_30dias . "' AND estado != 'expulsado'
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

		if ($r['motivo']) { $motivo = '<a href="/control/kick/info/'.$r['ID'].'/">#</a>'; } else { $motivo = ''; }
		$txt .= '<tr><td valign="top"><img src="'.IMG.'kick.gif" alt="Kick" border="0" /></td><td valign="top"><b>' . $estado . '</b></td><td valign="top"><b>'.($r['user_ID'] == 0?'Anonimo':crear_link($r['expulsado'], 'nick', $r['expulsado_estado'])).'</b></td><td valign="top" nowrap="nowrap"><img src="'.IMG.'cargos/' . $r['cargo'] . '.gif" border="0" /> ' . crear_link($r['nick_autor']) . '</td><td align="right" valign="top" nowrap="nowrap"><acronym title="' . $r['expire'] . '">'.timer($r['expire']).'</acronym></td><td align="right" valign="top" nowrap="nowrap">' . duracion($r['tiempo']+1) . '</td><td><b style="font-size:13px;">' . $r['razon'] . '</b></td><td>' . $expulsar . '</td><td>'.$motivo.'</td></tr>' . "\n";
	}
	$txt .= '</table><p>Los kicks solo pueden ser revocadas por un Comisario de Policia, un Juez Supremo o el Polic&iacute;a autor de la expulsi&oacute;n.</p>';


	}

	break;


case 'judicial':
	$txt_title = 'Control: Judicial';

	
	$txt .= '<h1><a href="/control/">Control</a>: Judicial</h1><p>Panel Judicial para Jueces.</p>

<h2>1. Sanciones</h2><hr />

<table border="0" cellspacing="1" cellpadding="" class="pol_table">
<tr>
<th></th>
<th>Sancionado</th>
<th>Hace</th>
<th>Concepto</th>
</tr>';



	$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = ".SQL."transacciones.emisor_ID LIMIT 1) AS nick
FROM ".SQL."transacciones
WHERE concepto LIKE '<b>SANCION %' AND receptor_ID = '-1'
ORDER BY time DESC", $link);
	while($r = mysql_fetch_array($result)){
		$txt .= '<tr><td>'.pols('-'.$r['pols']).' '.MONEDA.'</td><td><b>'.crear_link($r['nick']).'</b></td><td><acronym title="'.$r['time'].'">'.timer($r['time']).'</acronym></td><td>'.$r['concepto'].'</td></tr>' . "\n";
	}




if ($pol['cargo'] != 9) { $disabled = ' disabled="disabled"'; }

$txt .= '</table><br />

<form action="/accion.php?a=sancion" method="post">

<ol>
<li><b>Nick:</b> el Ciudadano de '.PAIS.' que recibir&aacute; la sanci&oacute;n.<br /><input type="text" value="" name="nick" size="20" maxlength="20" /><br /><br /></li>

<li><b>'.MONEDA.' de multa:</b> el importe de la sanci&oacute;n, maximo 5000 '.MONEDA.' (en caso de no tener la cantidad requerida, se quedar&aacute; en negativo).<br /><input style="color:blue;text-align:right;" type="text" name="pols" size="4" value="1" maxlength="4" /> '.MONEDA.'<br /><br /></li>

<li><b>Concepto:</b> breve frase con la raz&oacute;n de la sanci&oacute;n.<br /><input type="text" name="concepto" size="50" maxlength="100" /><br /><br /></li>

<li><input type="submit" style="color:red;" value="Efectuar sanci&oacute;n"' . $disabled . ' /> &nbsp; <span style="color:red;"><b>[acci&oacute;n irreversible]</b></span></li></ol></form>
			
';
	break;




	default:
		$txt_title = 'Control';
		$txt .= '<h1>Control:</h1>
<p class="amarillo" style="color:red;">Zonas de control cuyo acceso est&aacute; reservado a los ciudadanos que ejercen estos cargos.</p>

<table border="0" cellspacing="6">

<tr><td nowrap="nowrap"><a class="abig" href="/control/gobierno/"><b>Control</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/7.gif" title="Presidente" /> <img src="'.IMG.'cargos/19.gif" title="Vicepresidente" /></td>
<td>Panel de configuraci&oacute;n principal.</td></tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'kick.gif" alt="Kick" border="0" /> <a class="abig" href="/control/kick/"><b>Kicks</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/13.gif" title="Comisario de Policia" /> <img src="'.IMG.'cargos/12.gif" title="Policia" /></td>
<td>Control de bloqueo temporal del acceso.</td>
</tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'expulsar.gif" alt="Expulsado" border="0" /> <a class="abig" href="/control/expulsiones/"><b>Expulsiones</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/21.gif" title="Supervisor del Censo" /></td>
<td>Expulsiones permanentes por incumplimiento del <a href="http://www.virtualpol.com/">TOS</a>.</td>
</tr>

<tr>';


if (isset($sc[$pol['user_ID']])) {
	$txt .= '<td nowrap="nowrap"><a class="abig" href="/control/supervisor-censo/"><b>Supervisi&oacute;n del Censo</b></a></td>';
} else {
	$txt .= '<td nowrap="nowrap"><b class="abig gris">Supervisi&oacute;n del Censo</b></td>';
}

foreach ($sc AS $user_ID => $nick) { $supervisores .= crear_link($nick).' '; }

$txt .= '
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/21.gif" title="Supervisor del Censo" /></td>
<td>Informaci&oacute;n sobre el censo y control de clones.<br />
Supervisores del Censo: <b>'.$supervisores.'</b> (los 7 ciudadanos con m&aacute;s votos de confianza)</td></tr>';

if (ECONOMIA) {

$txt .= '
<tr><td nowrap="nowrap"><a class="abig" href="/control/judicial/"><b>Judicial</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/9.gif" title="Judicial" /></td>
<td>El panel judicial que permite efectuar sanciones.</td></tr>


<tr><td nowrap="nowrap"><a class="abig" href="/mapa/propiedades/"><b>Propiedades del Estado</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/40.gif" title="Arquitecto" /></td>
<td>El Arquitecto tiene el control de las propiedades del Estado.</td></tr>';

}

$txt .= '
<tr><td nowrap="nowrap"><a class="abig" href="/referendum/crear/"><b>Sondeos</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/41.gif" title="Consultor" /></td>
<td>El Consultor puede hacer sondeos de petici&oacute;n popular.</td></tr>

</table>';

		break;



}
$txt_header .= '<style type="text/css">h1 a { color:#4BB000; } .abig { font-size:20px; }</style>';


//THEME
include('theme.php');
?>
