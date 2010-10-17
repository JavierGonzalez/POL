<?php 
include('inc-login.php');



// load config full
$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

// load user cargos
$pol['cargos'] = cargos();



switch ($_GET['a']) {


case 'supervisor-censo':

	if ($pol['estado'] == 'desarrollador') {


		$result = mysql_query("SELECT nick FROM ".SQL_USERS." WHERE cargo = '21'" . $limit);
		while ($row = mysql_fetch_array($result)) {
			if ($supervisores) { $supervisores .= ', '; }
			$supervisores .= crear_link($row['nick']); 
		}

		// nomenclatura
		foreach ($vp['paises'] AS $pais) { $paises .= ' <span style="background:'.$vp['bg'][$pais].';">'.$pais.'</span>'; }
		$nomenclatura = '<span style="float:right;">Paises:'.$paises.' | Estados: <b class="ciudadano">Ciudadano</b> <b class="turista">Turista</b> <b class="validar">Validar</b> <b class="expulsado">Expulsado</b></span>';

		// siglas partidos
		$result = mysql_query("SELECT ID, siglas FROM ".SQL."partidos", $link);
		while($row = mysql_fetch_array($result)) { $siglas[$row['ID']] = $row['siglas']; }

		if ($_GET['b'] == 'nuevos-ciudadanos') {

				$txt_title = 'Control: Supervision del Censo - Nuevos ciudadanos';
				$txt .= '<h1><a href="/control/">Control</a>: <a href="/control/supervisor-censo/">Supervisi&oacute;n del Censo</a> | Nuevos ciudadanos | <a href="/control/expulsiones/">Expulsiones</a></h1>

<p class="amarillo" style="color:red;">La informaci&oacute;n y los mecanismos de esta p&aacute;gina son <b>confidenciales</b>. <img src="'.IMG.'cargos/21.gif" /> Supervisores del Censo: <b>' . $supervisores . '</b></p>'.$nomenclatura;

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
<th align="right"><acronym title="Transferencias de moneda saliente">T</acronym></th>
<th align="right">Email</th>
<th></th>
<th>IP</th>
</tr>';
		$result = mysql_query("SELECT *,
(SELECT COUNT(*) FROM ".SQL_MENSAJES." WHERE envia_ID = ".SQL_USERS.".ID) AS num_priv,
(SELECT COUNT(*) FROM ".SQL."foros_msg WHERE user_ID = ".SQL_USERS.".ID) AS num_foro,
(SELECT COUNT(*) FROM ".SQL."transacciones WHERE emisor_ID = ".SQL_USERS.".ID) AS num_transf, 
(SELECT voto FROM ".SQL_VOTOS." WHERE estado = 'confianza' AND uservoto_ID = '" . $pol['user_ID'] . "' AND user_ID = ".SQL_USERS.".ID LIMIT 1) AS has_votado
FROM ".SQL_USERS." 
ORDER BY fecha_registro DESC
LIMIT 60", $link);
		while($row = mysql_fetch_array($result)) {
			$dia_registro = date('j', strtotime($row['fecha_registro']));




			$IPs = explode(".", long2ip($row['IP']));
			$hosts = explode(".", $row['host']);
			$host = '';
			if (strlen($hosts[count($hosts)-3]) > 3) { $host = $hosts[count($hosts)-3] . '.' . $hosts[count($hosts)-2] . '.' . $hosts[count($hosts)-1]; }
			if ($row['online'] > 60) { $online = duracion($row['online']); } else { $online = ''; }
			if ($row['num_elec'] == '0') { $row['num_elec'] = ''; }

			if ($row['estado'] == 'expulsado') {
				$td_bg = ' style="background:#FFA6A6;"';
			} elseif ($row['visitas'] <= 1) { 
				$td_bg = ' style="background:#C0C0C0;"';
			} elseif ($row['visitas'] <= 2) {
				$td_bg = ' style="background:#D3D3D3;"';
			} elseif ($row['visitas'] <= 4) {
				$td_bg = ' style="background:#DCDCDC;"';
			} elseif ($row['visitas'] <= 6) {
				$td_bg = ' style="background:#EFEFEF;"';
			} else { $td_bg = ''; }

			if ($row['has_votado']) { $has_votado = ' (' . confianza($row['has_votado']) . ')'; } else { $has_votado = ''; }
			//$row['email'] = explodear("@", $row['email'], 1);
			
			$txt .= '<tr' . $td_bg . '>
<td style="background:'.$vp['bg'][$row['pais']].';"><b>' . crear_link($row['nick'], 'nick', $row['estado']) . '</b></td>
<td align="right">' . $dia_registro . '</td>
<td align="right" nowrap="nowrap">' . duracion(time() - strtotime($row['fecha_registro'])) . '</td>
<td align="right" nowrap="nowrap">' . $online . '</td>
<td align="right" nowrap="nowrap">' . duracion(time() - strtotime($row['fecha_last'])) . '</td>
<td>' . $siglas[$row['partido_afiliado']] . '</td>
<td align="right"><b>' . $row['num_elec'] . '</b></td>
<td nowrap="nowrap"><b>' . confianza($row['voto_confianza']) . '</b>' . $has_votado . '</td>
<td align="right" nowrap="nowrap"><acronym title="' . $row['fecha_init'] . '">' . $row['visitas'] . '</acronym></td>
<td align="right">' . $row['paginas'] . '</td>
<td align="right">' . $row['num_foro'] . '</td>
<td align="right">' . $row['num_priv'] . '</td>
<td align="right">' . $row['num_transf'] . '</td>
<td align="right">' . $row['email'] . '</td>
<td align="right" nowrap="nowrap">*.' . $host . '</td><td>' . $IPs[0] . '.' . $IPs[1] . '.*</td>
</tr>';
			$dia_registro_last = $dia_registro;
		}
		$txt .= '</table>';

		} else {
			// principal

		$txt_title = 'Control: Supervision del Censo';
		$txt .= '<h1><a href="/control/">Control</a>: Supervisi&oacute;n del Censo | <a href="/control/supervisor-censo/nuevos-ciudadanos/">Nuevos ciudadanos</a> | <a href="/control/expulsiones/">Expulsiones</a></h1>

<p class="amarillo" style="color:red;">La informaci&oacute;n y los mecanismos de esta p&aacute;gina son <b>confidenciales</b>. <img src="'.IMG.'cargos/21.gif" /> Supervisores del Censo: <b>' . $supervisores . '</b></p>'.$nomenclatura;
		

		$txt .= '<h1>1. Coincidencias de IP</h1><hr /><table border="0" cellspacing="4">';
		$result = mysql_query("SELECT nick, IP, COUNT(*) AS num, host
FROM ".SQL_USERS." 
WHERE estado != 'desarrollador'
GROUP BY IP HAVING COUNT(*) > 1
ORDER BY num DESC, fecha_registro DESC", $link);
		while($row = mysql_fetch_array($result)) {
			$clones = '';
			$desarrollador = false;
			$clones_expulsados = true;
			$result2 = mysql_query("SELECT ID, nick, estado, pais, partido_afiliado FROM ".SQL_USERS." WHERE IP = '" . $row['IP'] . "' ORDER BY fecha_registro DESC", $link);
			while($row2 = mysql_fetch_array($result2)) {
				if ($clones) { $clones .= ' & '; }
				if ($row2['estado'] != 'expulsado') { $clones_expulsados = false; } 
				$clones .= '<b>'.crear_link($row2['nick'], 'nick', $row2['estado'], $row2['pais']) . '</b> ' . $siglas[$row2['partido_afiliado']];
				if ($row2['estado'] == 'desarrollador') { $desarrollador = true; }
			}
			if ((!$desarrollador) AND (!$clones_expulsados)) {
				$IPs = explode(".", long2ip($row['IP']));
				$hosts = explode(".", $row['host']);
				$host = '';
				if (strlen($hosts[count($hosts)-3]) > 3) { $host = $hosts[count($hosts)-3] . '.' . $hosts[count($hosts)-2] . '.' . $hosts[count($hosts)-1]; }
				$txt .= '<tr><td>' . $row['num'] . '</td><td>' . $clones . '</td><td align="right" nowrap="nowrap">*.' . $host . '</td><td>' . $IPs[0] . '.' . $IPs[1] . '.*.*</td></tr>';
			}
		}
		$txt .= '</table>';



		$txt .= '<br /><h1>2. Coincidencia de clave</h1><hr /><table border="0" cellspacing="4">';
		$result = mysql_query("SELECT ID, IP, COUNT(*) AS num, pass
FROM ".SQL_USERS." 
GROUP BY pass HAVING COUNT(*) > 1
ORDER BY num DESC, fecha_registro DESC", $link);
		while($row = mysql_fetch_array($result)) {
			if (($row['pass'] != 'mmm') OR ($row['pass'] != 'e10adc3949ba59abbe56e057f20f883e')) {

				$clones = '';
				$result2 = mysql_query("SELECT ID, nick, pais, partido_afiliado, estado
FROM ".SQL_USERS." 
WHERE pass = '" . $row['pass'] . "'", $link);
				$clones_expulsados = true;
				while($row2 = mysql_fetch_array($result2)) { 
					if ($row2['nick']) {
						if ($row2['estado'] != 'expulsado') { $clones_expulsados = false; } 
						if ($clones) { $clones .= ' & '; }
						$clones .= crear_link($row2['nick'], 'nick', $row2['estado'], $row2['pais']) . '</b> ' . $siglas[$row2['partido_afiliado']] . '<b>';
					} 
				}
				if (!$clones_expulsados) {
					$txt .= '<tr><td>' . $row['num'] . '</td><td><b>' . $clones . '</b></td></tr>';
				}
			}
		}
		$txt .= '</table>';






		$txt .= '<br /><h1>3. Proxys</h1><hr />
<table border="0" cellspacing="4"><tr><th></th><th colspan="2">Proxys</th><th>Hosts</th><th>Clones</th></tr>';
		$result = mysql_query("SELECT ID, IP, nick, estado, pais, IP_proxy
FROM ".SQL_USERS." 
WHERE IP_proxy != ''
ORDER BY fecha_registro DESC", $link);
		while($row = mysql_fetch_array($result)) {

			$proxys = '';
			$proxys_dns = '';
			$IP_anterior = '';
			$clones = '';
			$clones_num = 0;
			$proxys_num = '';
			$num = 1;
			$proxys_array = explode(', ', long2ip($row['IP']).', '.$row['IP_proxy']);

			foreach ($proxys_array AS $IP) {
				if (($IP_anterior != $IP) AND ($IP != '127.0.0.1') AND ($IP != '-1')) {
					$IP_anterior = $IP;
					
					$host = gethostbyaddr($IP);
					if ($host == $IP) { $host = '*'; }
					
					$proxys_num .= '<b>'.$num++.'.</b><br />';
					$proxys .= $IP.'<br />';
					$proxys_dns .= $host.'<br />';

					// clones	
					$result2 = mysql_query("SELECT nick, estado, pais FROM ".SQL_USERS." WHERE ID != '".$row['ID']."' AND (IP = '".ip2long($IP)."' OR IP_proxy LIKE '%".$IP."%') ORDER BY fecha_registro DESC", $link);
					while($row2 = mysql_fetch_array($result2)) {
						$clones_num++;
						$clones .= crear_link($row2['nick'], 'nick', $row2['estado'], $row2['pais']).' ';
					}
					$clones .= '<br />';
				}
			}

			if ($clones_num > 0) {
				$txt .= '<tr>
<td valign="top"><b>' . crear_link($row['nick'], 'nick', $row['estado'], $row['pais']) . '</b></td>
<td valign="top">' . $proxys_num . '<hr /></td>
<td valign="top">' . $proxys . '<hr /></td>
<td valign="top" align="right">' . $proxys_dns . '<hr /></td>
<td valign="top">' . $clones . '<hr /></td>
</tr>';
			}

		}
		$txt .= '</table>';




		$txt .= '<br /><h1>4. Referencias</h1><hr /><table border="0" cellspacing="4">';
		$result = mysql_query("SELECT ID, nick, ref, pais, ref_num, estado, partido_afiliado
FROM ".SQL_USERS." 
WHERE ref_num != '0' 
ORDER BY ref_num DESC, fecha_registro DESC", $link);
		while($row = mysql_fetch_array($result)) {
			$clones = '';
			$result2 = mysql_query("SELECT ID, nick, ref, pais, estado, partido_afiliado
FROM ".SQL_USERS." 
WHERE ref = '" . $row['ID'] . "'", $link);
			while($row2 = mysql_fetch_array($result2)) { 
				if ($row2['nick']) { 
					if ($clones) { $clones .= ' & '; }
					$clones .= crear_link($row2['nick'], 'nick', $row2['estado'], $row2['pais']) . '</b> ' . $siglas[$row2['partido_afiliado']] . '<b>';
				} 
			}
			$txt .= '<tr><td><b>' . crear_link($row['nick'], 'nick', $row['estado'], $row['pais']) . '</b> ' . $siglas[$row['partido_afiliado']] . '</td><td align="right"></td><td><b>' . $row['ref_num'] . '</b></td><td>(<b>' . $clones . '</b>)</td></tr>';
		}
		$txt .= '</table>';







		$txt .= '<br /><h1>5. Emails at&iacute;picos</h1><hr /><table border="0" cellspacing="4">';
		$result = mysql_query("SELECT email, nick, ref, ref_num, estado FROM ".SQL_USERS." ORDER BY fecha_registro DESC", $link);
		while($row = mysql_fetch_array($result)) {
			$row['email'] = strtolower($row['email']);
			$email = explode("@", $row['email']);

			$emails_atipicos = array(
'gmail.com', 'hotmail.com', 'terra.es',
'hotmail.es', 'telefonica.net', 'yahoo.com.ar', 'ono.com', 
'msn.es', 'msn.com', 'live.com', 'yahoo.es', 'vodafone.es', 'yahoo.com.ve',);


			if (!in_array($email[1], $emails_atipicos)) {
				$clones = '';
				$row['email'] = explodear("@", $row['email'], 1); 
				$txt .= '<tr><td>' . crear_link($row['nick'], 'nick', $row['estado']) . '</td><td>*@<b>' . $row['email'] . '</b></td></tr>';
			}
		}
		$txt .= '</table>';





		$txt .= '<br /><h1>6. Referencias desde URLs</h1><hr /><table border="0" cellspacing="4">
<tr>
<th></th>
<th>Ref</th>
<th>Nuevos</th>
<th>URL de referencia</th>
</tr>';
		$result = mysql_query("SELECT user_ID, COUNT(*) AS num, referer,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL_REFERENCIAS.".user_ID LIMIT 1) AS nick,
(SELECT COUNT(*) FROM ".SQL_REFERENCIAS." WHERE referer = ".SQL_REFERENCIAS.".referer AND new_user_ID != '0') AS num_registrados
FROM ".SQL_REFERENCIAS." 
GROUP BY referer HAVING COUNT(*) > 1
ORDER BY num DESC", $link);
		while($row = mysql_fetch_array($result)) {

			$result2 = mysql_query("SELECT COUNT(*) AS num_registrados FROM ".SQL_REFERENCIAS." WHERE referer = '" . $row['referer'] . "' AND new_user_ID != '0'", $link);
			while($row2 = mysql_fetch_array($result2)) {
				if ($row2['num_registrados'] != 0) { $num_registrados = '+' . $row2['num_registrados']; } else { $num_registrados = ''; }
			}
			if ($row['referer'] == '') { $row['referer'] = '#referencia-directa'; $row['nick'] = '&nbsp;'; }

			$txt .= '<tr><td><b>' . crear_link($row['nick']) . '</b></td><td align="right"><b>' . $row['num'] . '</b></td><td align="right">' . $num_registrados . '</td><td><a href="' . $row['referer'] . '">' . $row['referer'] . '</a></td></tr>';
		}
		$txt .= '</table>';



		$txt .= '<br /><h1>7. M&aacute;s votos y menos actividad</h1><hr /><table border="0" cellspacing="4">
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
FROM ".SQL_USERS." WHERE num_elec > 2 AND fecha_last > '".date('Y-m-d 20:00:00', time() - 2592000)."' ORDER BY factor DESC LIMIT 20", $link);
		while($row = mysql_fetch_array($result)) {
			if ($row['factor'] > 0.0099) {
				$IPs = explode(".", long2ip($row['IP']));
				$txt .= '<tr><td>' . crear_link($row['nick'], 'nick', $row['estado'], $row['pais']) . ' ' .			$siglas[$row['partido_afiliado']] . '</td><td align="right"><b>' . $row['num_elec'] . '</b></td><td>/</td><td align="right"><b>' . duracion($row['online']) . '</b></td><td><b>=</b></td><td>' . $row['factor'] . '</td><td align="right">'.$row['visitas'].'</td><td align="right">'.$row['paginas'].'</td><td>(' . $IPs[0] . '.' . $IPs[1] . '.*.*)</td></tr>';
			}
		}
		$txt .= '</table>';


		$txt .= '<br /><h1>8. Navegadores</h1><hr />
<table border="0" cellspacing="4">';
		$result = mysql_query("SELECT COUNT(*) AS num, nav
FROM ".SQL_USERS." 
GROUP BY nav HAVING COUNT(*) > 1
ORDER BY num ASC", $link);
		while($row = mysql_fetch_array($result)) {

			$clones = '';
			if ($row['num'] <= 8) {
				$result2 = mysql_query("SELECT ID, nick, estado, pais FROM ".SQL_USERS." WHERE nav = '" . $row['nav'] . "' ORDER BY fecha_registro DESC", $link);
				while($row2 = mysql_fetch_array($result2)) {
					if ($clones) { $clones .= ' & '; }
					$clones .= crear_link($row2['nick'], 'nick', $row2['estado'], $row2['pais']);
				}
			} else { $clones = '</b>(navegador muy comun)<b>'; }



			$txt .= '<tr><td align="right"><b>' . $row['num'] . '</b></td><td><b>' . $clones . '</b></td><td style="font-size:9px;">' . $row['nav'] . '</td></tr>';
		}
		$txt .= '</table>';




		}


	} else { $txt .= '<p class="amarillo" style="color:red;"><b>Acceso restringido a los Supervisores del Censo.</b></p>'; }
	break;






case 'despacho-oval':
	$txt_title = 'Control: Despacho Oval';
	if ($pol['nivel'] >= 98) { $dis = ''; } else { $dis = ' disabled="disabled"'; }

	$result = mysql_query("SELECT (SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS elnick
	 FROM ".SQL."estudios_users WHERE ID_estudio = '7' AND cargo = '1' LIMIT 1", $link);
	while($row = mysql_fetch_array($result)) { $presidente = $row['elnick']; }

	$result = mysql_query("SELECT (SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS elnick
	 FROM ".SQL."estudios_users WHERE ID_estudio = '19' AND cargo = '1' LIMIT 1", $link);
	while($row = mysql_fetch_array($result)) { $vicepresidente = $row['elnick']; }

	$defcon_bg = array('1' => 'white','2' => 'red','3' => 'yellow','4' => 'green','5' => 'blue');



	if ($_GET['b'] == 'foro') {

		$txt .= '<h1><a href="/control/">Control</a>: <a href="/control/despacho-oval/">Despacho Oval</a> | Control Foro</h1>
		
<br />
<form action="/accion.php?a=despacho-oval&b=subforo" method="post">

<table border="0" cellspacing="3" cellpadding="0" class="pol_table">
<tr>
<td class="amarillo"colspan="8"><b class="big">Control de foros</b></td>
</tr>

<tr>
<th colspan="3"></th>
<th colspan="2" align="center">Nivel acceso *</th>
<th colspan="2" align="center">Info</th>
</tr>

<tr>
<th>Orden</th>
<th>Subforo</th>
<th>Descripcion</th>
<th align="center"><acronym title="Nivel minimo para crear hilos">Hilos</acronym></th>
<th align="center"><acronym title="Nivel minimo para crear mensajes">Mensajes</acronym></th>
<th align="center">Hilos</th>
<th align="center">Mensajes</th>
<th></th>
</tr>';
	$subforos = '';
	$result = mysql_query("SELECT *,
(SELECT COUNT(*) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_hilos,
(SELECT SUM(num) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_msg
FROM ".SQL."foros WHERE estado = 'ok'
ORDER BY time ASC", $link);
	while($row = mysql_fetch_array($result)){

		if ($row['num_hilos'] == 0) { $del = '<input style="margin-bottom:-16px;" type="button" value="Eliminar" onClick="window.location.href=\'/accion.php?a=despacho-oval&b=eliminarsubforo&ID=' . $row['ID'] . '/\';">';
		} else { $del = ''; }

		$txt .= '<tr>
<td align="right"><input type="text" style="text-align:right;" name="'.$row['ID'].'_time" size="1" maxlength="3" value="'.$row['time'].'" /></td>
<td><a href="/foro/'.$row['url'].'/"><b>'.$row['title'].'</b></a></td>
<td><input type="text" name="'.$row['ID'].'_descripcion" size="30" maxlength="100" value="'.$row['descripcion'].'" /></td>
<td align="right"><input type="text" name="'.$row['ID'].'_acceso" style="text-align:right;" size="2" maxlength="3" value="'.$row['acceso'].'" /></td>
<td align="right"><input type="text" style="text-align:right;" name="'.$row['ID'].'_acceso_msg" size="2" maxlength="3" value="'.$row['acceso_msg'].'" /></td>
<td align="right" style="color:#999;">'.number_format($row['num_hilos'], 0, ',', '.').'</td>
<td align="right" style="color:#999;">'.number_format($row['num_msg'], 0, ',', '.').'</td>
<td>'.$del.'</td>
</tr>'."\n";

		if ($subforos) { $subforos .= '.'; }
		$subforos .= $row['ID'];
	}

		$txt .= '
<input name="subforos" value="'.$subforos.'" type="hidden" />
<tr>
<td align="center" colspan="8"><input value="Guardar cambios" style="font-size:18px;" type="submit"'.$dis.' /></td>
</tr>
<tr>
<td colspan="8">* Los Extranjeros tienen nivel <b>0</b>, los Ciudadanos sin cargo nivel <b>1</b> y el nivel asciende en adelante segun los cargos ejercidos hasta el nivel <b>100</b> que es el Presidente.</td>
</tr>
</table>
</form>

<br />

<form action="/accion.php?a=despacho-oval&b=crearsubforo" method="post">
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





	$txt .= '<h1><a href="/control/">Control</a>: Despacho Oval | <a href="/control/despacho-oval/foro/">Control Foro</a></h1>

<br />
<form action="/accion.php?a=despacho-oval&b=config" method="post">

<table border="0" cellspacing="3" cellpadding="0" class="pol_table"><tr><td valign="top">

<table border="0" cellspacing="3" cellpadding="0" class="pol_table">

<tr><td colspan="2" class="amarillo"><b class="big">Control Ejecutivo</b></td></tr>


<tr><td align="right">Descripcion Pais:</td><td><input type="text" name="pais_des" size="24" maxlength="40" value="'.$pol['config']['pais_des'].'"'.$dis.' /></td></tr>
<tr><td align="right">DEFCON:</td><td>' . $defcon . '</td></tr>
<tr><td align="right">Referencia tras:</td><td><input style="text-align:right;" type="text" name="online_ref" size="3" maxlength="10" value="' . round($pol['config']['online_ref']/60) . '"'.$dis.' /> min online (' . duracion($pol['config']['online_ref'] + 1) . ')</td></tr>
<tr><td align="right">Esca&ntilde;os:</td><td><input style="text-align:right;" type="text" name="num_escanos" size="3" maxlength="10" value="' . $pol['config']['num_escanos'] . '"'.$dis.' /> Diputados</td></tr>';

$palabra_gob = explode(':', $pol['config']['palabra_gob']);

$sel_exp = '';
$sel_exp[$pol['config']['examenes_exp']] = ' selected="selected"';

$txt .= '
<tr><td align="right" valign="top"><acronym title="Mensaje del Gobierno">Mensaje Gobierno</acronym>:</td><td align="right">
<input type="text" name="palabra_gob0" size="24" maxlength="200" value="' . $palabra_gob[0] . '"'.$dis.' /><br />
http://<input type="text" name="palabra_gob1" size="19" maxlength="200" value="' . $palabra_gob[1] . '"'.$dis.' /></td></tr>

<tr><td align="right"><acronym title="Tiempo de vigencia maxima de un examen">Caducidad Examenes</acronym>:</td><td>
<select name="examenes_exp"'.$dis.'>
<option value="7776000"' . $sel_exp['7776000'] . '>3 meses</option>
<option value="5184000"' . $sel_exp['5184000'] . '>2 meses</option>
<option value="2592000"' . $sel_exp['2592000'] . '>1 mes</option>
<option value="1296000"' . $sel_exp['1296000'] . '>15 dias</option>
</select>';


foreach ($vp['paises'] AS $pais) {
$sel = '';
$sel[$pol['config']['frontera_con_' . $pais]] = ' selected="selected"';
if (PAIS != $pais) {
$txt .= '
<tr><td align="right">Frontera con ' . $pais. ':</td>
<td>
<select name="frontera_con_' . $pais . '"'.$dis.'>
<option value="abierta"' . $sel['abierta'] . '>Abierta</option>
<option value="cerrada"' . $sel['cerrada'] . '>Cerrada</option>
</select>
</tr>';
}
}

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

</td></tr>



<tr><td colspan="2"></td></tr>

<tr><td colspan="2" class="amarillo"><b class="big">Econom&iacute;a</b> '.MONEDA.'</td></tr>



<tr><td align="right">Inem'.PAIS.':</td><td><input style="text-align:right;" class="pols" type="text" name="pols_inem" size="3" maxlength="6" value="' . $pol['config']['pols_inem'] . '"'.$dis.' /> '.MONEDA.' por d&iacute;a activo</td></tr>
<tr><td align="right">Referencia:</td><td><input style="text-align:right;" class="pols" type="text" name="pols_afiliacion" size="3" maxlength="6" value="' . $pol['config']['pols_afiliacion'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Crear empresa:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_empresa" size="3" maxlength="6" value="' . $pol['config']['pols_empresa'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Crear cuenta bancaria:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_cuentas" size="3" maxlength="6" value="' . $pol['config']['pols_cuentas'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Crear partido:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_partido" size="3" maxlength="6" value="' . $pol['config']['pols_partido'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">Hacer examen:</td><td><input class="pols" style="text-align:right;" type="text" name="pols_examen" size="3" maxlength="6" value="' . $pol['config']['pols_examen'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right"><acronym title="Mensaje privado a todos los Ciudadanos.">Mensaje Global</acronym>:</td><td><input style="text-align:right;" type="text" name="pols_mensajetodos" size="3" maxlength="6" class="pols" value="' . $pol['config']['pols_mensajetodos'] . '"'.$dis.' /> '.MONEDA.' (minimo '.pols(1000).')</td></tr>
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


<table border="0" cellspacing="3" cellpadding="0" class="pol_table">

<tr><td colspan="2" class="amarillo"><b class="big">Salarios</b></td></tr>';


	$result = mysql_query("SELECT nombre, ID, salario
FROM ".SQL."estudios
ORDER BY salario DESC", $link);
	while($row = mysql_fetch_array($result)){
		$txt .= '<tr><td align="right">' . $row['nombre'] . ':</td><td><input style="text-align:right;" type="text" name="salario_' . $row['ID'] . '" size="3" maxlength="6" class="pols" value="' . $row['salario'] . '"'.$dis.' /> '.MONEDA.'</td></tr>';
	}




	$txt .= '
</table>

</td></tr></table>

<table border="0" cellspacing="3" cellpadding="0" class="pol_table">

<tr><td colspan="2" class="amarillo"><b class="big">Emoticonos</b></td></tr>
<tr><td><a href="'.IMG.'smiley/roto2.gif"><p>roto2</p></a></td><td><input type="checkbox" value="roto2" /></td>
</table>



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

$txt .= '<p class="azul" style="color:grey;">Este control pertenece al Presidente <img src="'.IMG.'cargos/7.gif" /><b>' . crear_link($presidente) . '</b> y Vicepresidente <img src="'.IMG.'cargos/19.gif" /><b>' . crear_link($vicepresidente) . '</b>.</p>';
	break;






case 'expulsiones':
	$txt_title = 'Control:  Expulsiones';
	$txt .= '<h1><a href="/control/">Control</a>: <img src="'.IMG.'expulsar.gif" alt="Expulsado" border="0" /> Expulsiones</h1>

<p>Una expulsi&oacute;n bloquea de forma perpetua a un usuario de <a href="http://www.virtualpol.com/">VirtualPol</a>. Debe usarse tan solo en casos de <b>clones</b> o <b>ataques al sistema</b>.</p>

<table border="0" cellspacing="1" cellpadding="" class="pol_table">
<tr>
<th>Expulsado</th>
<th>Pa&iacute;s</th>
<th>Cuando</th>
<th>Motivo</th>
<th></th>
</tr>';


$result = mysql_query("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL_EXPULSIONES.".user_ID LIMIT 1) AS expulsado,
(SELECT pais FROM ".SQL_USERS." WHERE ID = ".SQL_EXPULSIONES.".user_ID LIMIT 1) AS expulsado_pais,
(SELECT estado FROM ".SQL_USERS." WHERE ID = ".SQL_EXPULSIONES.".user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL_EXPULSIONES.".autor LIMIT 1) AS nick_autor
FROM ".SQL_EXPULSIONES."
ORDER BY expire DESC", $link);
while($row = mysql_fetch_array($result)){
	
	if ((($pol['estado'] == 'desarrollador') OR  ($pol['cargo'] == 9) OR ($pol['cargo'] == 7)) AND ($row['expulsado_pais']) AND ($row['estado'] == 'expulsado')) { 
		$expulsar = boton('Cancelar', '/accion.php?a=expulsar&b=desexpulsar&ID=' . $row['ID'], '&iquest;Seguro que quieres CANCELAR la EXPULSION del usuario: '.$row['tiempo'].'?'); 
	} elseif ($row['estado'] == 'cancelado') { $expulsar = '<b style="font-weight:bold;">Cancelado</b>'; } else { $expulsar = ''; }

	$duracion = '<acronym title="' . $row['expire'] . '">' . duracion((time() + $row['tiempo']) - strtotime($row['expire'])) . '</acronym>';

	if (!$row['expulsado_estado']) { $row['expulsado_estado'] = 'expulsado'; }

	$txt .= '<tr><td valign="top" nowrap="nowrap">';
	
	if ($row['estado'] == 'expulsado') {
		$txt .= '<img src="'.IMG.'expulsar.gif" alt="Expulsado" border="0" /> ';
	} else { $txt .= '<img src="'.IMG.'cargos/0.gif" border="0" /> '; }

	$txt .= '<b>' . crear_link($row['tiempo'], 'nick', $row['expulsado_estado'], $row['expulsado_pais']) . '</b></td>
<td valign="top">'.$row['expulsado_pais'].'</td>
<td valign="top" align="right" valign="top" nowrap="nowrap"><acronym title="' . $row['expire'] . '">' . $duracion . '</acronym></td><td valign="top"><b style="font-size:13px;">' . $row['razon'] . '</b></td><td valign="top" align="center">' . $expulsar . '</td></tr>' . "\n";

}
$txt .= '</table><hr /><p>Las expulsiones son ejecutadas por los desarrolladores a cualquier usuario que no ejerzan ningun cargo en su pais.</p>
<p>Las expulsiones pueden ser canceladas por el <b><img src="'.IMG.'cargos/7.gif" />Presidente</b> y <b><img src="'.IMG.'cargos/9.gif" />Juez Supremo</b>, antes de que el expulsado sea eliminado (ocurre tras 10 dias inactivo).</p>';
	break;



case 'kick':
	$txt_title = 'Control: Kicks';
	
	if (($_GET['b'] == 'info') AND ($_GET['c'])) {

		$result = mysql_query("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."ban.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM ".SQL_USERS." WHERE ID = ".SQL."ban.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."ban.autor LIMIT 1) AS nick_autor
FROM ".SQL."ban
WHERE ID = '" . $_GET['c'] . "' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){
			$txt .= '<h1><a href="/control/">Control</a>: <a href="/control/kick/">Kicks</a> | info '.$_GET['c'].'</h1>
<p>Motivo: <b>'.$row['razon'].'</b></p>

<p>Pruebas:</p><p class="azul">'.str_replace("\n","<br />", $row['motivo']).'</p>';
		}



	} elseif ($_GET['b']) {
		if ($_GET['b'] == 'expulsar') { $_GET['b'] = ''; }
		if (($pol['cargos'][12]) OR ($pol['cargos'][13]) OR ($pol['cargos'][22])) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }
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
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."ban.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM ".SQL_USERS." WHERE ID = ".SQL."ban.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."ban.autor LIMIT 1) AS nick_autor
FROM ".SQL."ban
WHERE expire > '" . $margen_30dias . "' AND estado != 'expulsado'
ORDER BY expire DESC", $link);
	while($row = mysql_fetch_array($result)){
		if ((($row['autor'] == $pol['user_ID']) OR ($pol['cargos'][13]) OR ($pol['cargos'][9])) AND ($row['estado'] == 'activo')) { $expulsar = boton('X', '/accion.php?a=kick&b=quitar&ID=' . $row['ID'], '&iquest;Seguro que quieres hacer INACTIVO este kick?'); } else { $expulsar = ''; }

		$duracion = '<acronym title="' . $row['expire'] . '">' . duracion((time() + $row['tiempo']) - strtotime($row['expire'])) . '</acronym>';
		if ($row['estado'] == 'activo') {
			$estado = '<span style="color:red;">Activo</span>';
		} elseif ($row['estado'] == 'cancelado') {
			$estado = '<span style="color:grey;">Cancelado</span>';
		} else {
			$estado = '<span style="color:grey;">Inactivo</span>';
		}
		if (!$row['expulsado_estado']) { $row['expulsado_estado'] = 'expulsado'; }

		if ($row['motivo']) { $motivo = '<a href="/control/kick/info/'.$row['ID'].'/">#</a>'; } else { $motivo = ''; }
		$txt .= '<tr><td valign="top"><img src="'.IMG.'kick.gif" alt="Kick" border="0" /></td><td valign="top"><b>' . $estado . '</b></td><td valign="top"><b>'.($row['user_ID'] == 0?'Anonimo':crear_link($row['expulsado'], 'nick', $row['expulsado_estado'])).'</b></td><td valign="top" nowrap="nowrap"><img src="'.IMG.'cargos/' . $row['cargo'] . '.gif" border="0" /> ' . crear_link($row['nick_autor']) . '</td><td align="right" valign="top" nowrap="nowrap"><acronym title="' . $row['expire'] . '">' . $duracion . '</acronym></td><td align="right" valign="top" nowrap="nowrap">' . duracion($row['tiempo']+1) . '</td><td><b style="font-size:13px;">' . $row['razon'] . '</b></td><td>' . $expulsar . '</td><td>'.$motivo.'</td></tr>' . "\n";
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
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."transacciones.emisor_ID LIMIT 1) AS nick
FROM ".SQL."transacciones
WHERE concepto LIKE '<b>SANCION %' AND receptor_ID = '-1'
ORDER BY time DESC", $link);
	while($row = mysql_fetch_array($result)){
		$txt .= '<tr><td>'.pols('-'.$row['pols']).' '.MONEDA.'</td><td><b>'.crear_link($row['nick']).'</b></td><td><acronym title="'.$row['time'].'">' . duracion(time() - strtotime($row['time'])) . '</acronym></td><td>'.$row['concepto'].'</td></tr>' . "\n";
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

<tr><td nowrap="nowrap"><a class="abig" href="/control/despacho-oval/"><b>Despacho Oval</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/7.gif" title="Presidente" /> <img src="'.IMG.'cargos/19.gif" title="Vicepresidente" /></td>
<td>El m&aacute;ximo poder ejecutivo.</td></tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'kick.gif" alt="Kick" border="0" /> <a class="abig" href="/control/kick/"><b>Kicks</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/13.gif" title="Comisario de Policia" /> <img src="'.IMG.'cargos/12.gif" title="Policia" /></td>
<td>F&eacute;rreo control de control de acceso temporal.</td>
</tr>

<tr>
<td nowrap="nowrap"><img src="'.IMG.'expulsar.gif" alt="Expulsado" border="0" /> <a class="abig" href="/control/expulsiones/"><b>Expulsiones</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/21.gif" title="Supervisor del Censo" /></td>
<td>Expulsiones permanentes de VirtualPol. Zona com&uacute;n entre Paises.</td>
</tr>

<tr>';


if ($pol['estado'] == 'desarrollador') {
	$txt .= '<td nowrap="nowrap"><a class="abig" href="/control/supervisor-censo/"><b>Supervisi&oacute;n del Censo</b></a></td>';
} else {
	$txt .= '<td nowrap="nowrap"><b class="abig gris">Supervisi&oacute;n del Censo</b></td>';
}


$txt .= '
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/21.gif" title="Supervisor del Censo" /></td>
<td>Informaci&oacute;n procesada y analizada sobre el censo. Reservado.</td></tr>



<tr><td nowrap="nowrap"><a class="abig" href="/control/judicial/"><b>Judicial</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/9.gif" title="Judicial" /></td>
<td>El panel judicial que permite efectuar sanciones.</td></tr>


<tr><td nowrap="nowrap"><a class="abig" href="/mapa/propiedades/"><b>Propiedades del Estado</b></a></td>
<td align="right" nowrap="nowrap"><img src="'.IMG.'cargos/40.gif" title="Arquitecto" /></td>
<td>El Arquitecto tiene el control de las propiedades del Estado.</td></tr>

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
