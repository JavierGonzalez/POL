<?php 
include('inc-login.php');

$result = mysql_query("SELECT *, 
(SELECT siglas FROM ".SQL."partidos WHERE ID = ".SQL_USERS.".partido_afiliado LIMIT 1) AS partido,
(SELECT COUNT(ID) FROM ".SQL."foros_hilos WHERE user_ID = ".SQL_USERS.".ID LIMIT 1) AS num_hilos,
(SELECT COUNT(ID) FROM ".SQL."foros_msg WHERE user_ID = ".SQL_USERS.".ID LIMIT 1) AS num_msg
FROM ".SQL_USERS." 
WHERE nick = '" . $_GET['a'] . "'
LIMIT 1", $link);
while($row = mysql_fetch_array($result)){

	$user_ID = $row['ID'];
	if ((PAIS != $row['pais']) AND ($row['estado'] == 'ciudadano') AND ($row['pais'] != 'ninguno')) {
		header('HTTP/1.1 301 Moved Permanently'); header('Location: http://'.strtolower($row['pais']).'.virtualpol.com/perfil/'.$row['nick'].'/'); exit;
	} elseif ($user_ID) { //nick existe

		$nick = $row['nick'];
		if ($row['avatar'] == 'true') { $p_avatar = '<img src="'.IMG.'a/' . $row['ID'] . '.jpg" alt="'.$nick.'" />'; }

		$extras = '';
		if ($row['estado'] == 'desarrollador') { $row['pais'] = 'VirtualPol'; }
		if ($pol['cargo'] == 21) {
			$hosts = explode(".", $row['host']);
			$host = '';
			if (strlen($hosts[count($hosts)-3]) > 3) { $host = $hosts[count($hosts)-3] . '.' . $hosts[count($hosts)-2] . '.' . $hosts[count($hosts)-1]; }

			//if ($row['cargo'] != 0) { $exp_disabled = ' disabled="disabled"'; } else { $exp_disabled = ''; }
			$extras = '<tr><td colspan="2"><input style="float:right;" value="Expulsar" onclick="if (!confirm(\'&iquest;Seguro que quieres EXPULSAR a este usuario?\')) { return false; } else { var razon = prompt(\'&iquest;Razon de expulsion?\',\'\').replace(\'&\',\'%26\'); if (razon) { window.location.href=\'http://'.strtolower($pol['pais']).'.virtualpol.com/accion.php?a=expulsar&ID=' . $row['ID'] . '&nick=' . $row['nick'] . '&razon=\' + razon; } }" type="button"'.$exp_disabled.' />('.$row['email'].', *.' . $host . ')<br /><span style="font-size:9px;color:#666;">'.$row['nav'].'</span></td></tr>'; 
		} elseif ($pol['estado'] == 'desarrollador') {

			//if ($row['cargo'] != 0) { $exp_disabled = ' disabled="disabled"'; } else { $exp_disabled = ''; }
			$extras = '<tr><td colspan="2"><input style="float:right;" value="Expulsar" onclick="if (!confirm(\'&iquest;Seguro que quieres EXPULSAR a este usuario?\')) { return false; } else { var razon = prompt(\'&iquest;Razon de expulsion?\',\'\').replace(\'&\',\'%26\'); if (razon) { window.location.href=\'http://'.strtolower($pol['pais']).'.virtualpol.com/accion.php?a=expulsar&ID=' . $row['ID'] . '&nick=' . $row['nick'] . '&razon=\' + razon; } }" type="button"'.$exp_disabled.' />(' . $row['ID'] . ', '.$row['email'].', *.' . $row['host'] . ', <a href="http://www.geoiptool.com/es/?IP='.long2ip($row['IP']).'">GeoIP</a>)<br /><span style="font-size:9px;color:#666;">'.$row['nav'].'</span></td></tr>';
		
		} else { $extras = ''; }





		$txt .= '<table border="0" cellspacing="4"><tr><td rowspan="2">'.$p_avatar.'</td><td nowrap="nowrap"><h1><span class="amarillo"><img src="'.IMG.'cargos/'.$row['cargo'].'.gif" alt="Cargo" style="margin-bottom:0;" border="0" /> ' . $nick . ' &nbsp; <span style="color:grey;"><span class="' . $row['estado'] . '">' . ucfirst($row['estado']) . '</span> de ' . $row['pais'] . '</span></span></h1></td><td nowrap="nowrap">';



		// CONFIANZA
		if ((($user_ID != $pol['user_ID']) AND ($pol['user_ID']) AND ($pol['estado'] != 'expulsado')) OR ($pol['estado'] == 'desarrollador')) {

			$result2 = mysql_query("SELECT voto FROM ".SQL_VOTOS." WHERE estado = 'confianza' AND uservoto_ID = '" . $pol['user_ID'] . "' AND user_ID = '" . $user_ID . "' LIMIT 1", $link);
			while ($row2 = mysql_fetch_array($result2)) { $hay_v_c = $row2['voto']; }

			$c_select = ' disabled="disabled"';
			if ($hay_v_c == '1') { $confianza_1 = $c_select;
			} elseif ($hay_v_c == '0') { $confianza_0 = $c_select;
			} elseif ($hay_v_c == '-1') { $confianza_m1 = $c_select;
			} else { $confianza_0 = $c_select; }

$txt .= 'Confianza: ' . confianza($row['voto_confianza']) . '

<input type="button" value="+" onClick="window.location.href=\'http://' . $pol['pais'] . '.virtualpol.com/accion.php?a=voto&b=confianza&ID=' . $user_ID . '&nick=' . $nick . '&voto_confianza=1\';"' . $confianza_1 . ' /><input type="button" value="0" onClick="window.location.href=\'http://' . $pol['pais'] . '.virtualpol.com/accion.php?a=voto&b=confianza&ID=' . $user_ID . '&nick=' . $nick . '&voto_confianza=0\';"' . $confianza_0 . ' /><input type="button" value="&#8211;" onClick="window.location.href=\'http://' . $pol['pais'] . '.virtualpol.com/accion.php?a=voto&b=confianza&ID=' . $user_ID . '&nick=' . $nick . '&voto_confianza=-1\';"' . $confianza_m1 . ' />
';
		} else {
			$txt .= 'Confianza: ' . confianza($row['voto_confianza']);
		}






$txt .= '</td></tr>'.$extras.'</table><div id="info">';

		$cargos_num = 0;
		$estudios_num = 0;
		$result2 = mysql_query("SELECT ID_estudio, cargo, nota, estado, time,
(SELECT titulo FROM ".SQL."examenes WHERE cargo_ID = ".SQL."estudios_users.ID_estudio LIMIT 1) AS nombre
FROM ".SQL."estudios_users
WHERE user_ID = '" . $user_ID . "'
ORDER BY cargo DESC, estado ASC, nota DESC", $link);
		while($row2 = mysql_fetch_array($result2)) {
			if ($row2['cargo'] == 1) { 
				$dimitir = ' <span class="gris"> (Cargo Ejercido)</span>';
				if ($row['ID'] == $pol['user_ID']) {
					
					if ($row2['ID_estudio'] == '666666') {

						$result3 = mysql_query("SELECT user_ID, nota, ID_estudio,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick,
(SELECT fecha_last FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS fecha_last
FROM ".SQL."estudios_users
WHERE ID_estudio = '6' AND estado = 'ok' AND cargo = '0'
ORDER BY nick ASC", $link);
						while($row3 = mysql_fetch_array($result3)) {
							if (strtotime($row3['fecha_last']) > (time() - 259200)) {
								$diputados .= '<option value="' . $row3['user_ID'] . '">' . $row3['nota'] . ' ' . $row3['nick'] . '</option>';
							}
						}

						$estudios .= '<form action="/accion.php?a=cargo&b=ceder&ID=' . $row2['ID_estudio'] . '" method="post">';
						$dimitir .= '<select name="user_ID"><option value=""></option>' . $diputados . '</select><input type="submit" value="Ceder" onclick="if (!confirm(\'&iquest;Seguro que quieres CEDER de este cargo?\')) { return false; }"></form>'; 
					} else {
						$dimitir .= ' <form action="/accion.php?a=cargo&b=dimitir&ID='.$row2['ID_estudio'].'" method="POST"><input type="hidden" name="pais" value="'.$pol['pais'].'" /><input type="submit" value="Dimitir"  onclick="if (!confirm(\'&iquest;Seguro que quieres DIMITIR del cargo de ' . $row2['nombre'] . '?\')) { return false; }"/></form>';
					}
					
				}
			}
			$estudios_num++;
			if ($row2['estado'] == 'ok') { 
				$sello = '<img src="'.IMG.'estudiado.gif" alt="Aprobado" title="Aprobado" border="0" /> '; 
			} else { $sello = ''; }

			if ($row2['ID_estudio'] > 0) { $cargo_img = '<img src="'.IMG.'cargos/' . $row2['ID_estudio'] . '.gif" border="0" />'; } else { $cargo_img = ''; }
			$estudios .= '<tr>
<td>' . $sello . '</td>
<td align="right" class="gris">' . $row2['nota'] . '</td>
<td>' . $cargo_img . '</td>
<td><b>' . $row2['nombre'] . '</b></td>
<td style="color:#999;" align="right"><acronym title="'.$row2['time'].'">'.duracion(time()-strtotime($row2['time'])).'</acronym></td>
<td><b>' . $dimitir . '</b></td>
</tr>';

			$dimitir = '';
		}

		$estudios = '<table border="0" cellpadding="0" cellspacing="4">' . $estudios . '</table>';

		if ($user_ID == $pol['user_ID']) { //es USER

			$result2 = mysql_query("SELECT valor FROM ".SQL."config WHERE dato = 'pols_afiliacion' LIMIT 1", $link);
			while($row2 = mysql_fetch_array($result2)){ if ($row2['pols'] >= $pols) { $pols_afiliacion = $row2['valor']; } }

			$text_limit = 1200 - strlen(strip_tags($row['text']));
			$txt .= '<div class="azul">';



$result2 = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE dato = 'impuestos' OR dato = 'impuestos_minimo'", $link);
while($row2 = mysql_fetch_array($result2)){ $pol['config'][$row2['dato']] = $row2['valor']; }



$patrimonio = $row['pols'];
$patrimonio_libre_impuestos = 0;
$txt .= '

<b>Tu Economia (<a href="/info/economia/">Economia Global</a>)</b>
<table border="0">

<tr>
<td align="right">Personal</td>
<td align="right">' . pols($row['pols']) . ' '.MONEDA.'</td>
<td><a href="/pols/">Info</a></td>
</tr>';


$result2 = mysql_query("SELECT ID, pols, nombre, exenta_impuestos FROM ".SQL."cuentas WHERE user_ID = '".$row['ID']."'", $link);
while($row2 = mysql_fetch_array($result2)){
	if ($row2['exenta_impuestos'] == 1) {
		$patrimonio_libre_impuestos += $row2['pols'];
		$sin_impuestos = ' - <em style="#AAA">Sin impuestos</em>';
	}
	else {
		$sin_impuestos = '';
	}
	$patrimonio += $row2['pols'];
	$txt .= '
<tr>
<td align="right">Cuenta</td>
<td align="right">' . pols($row2['pols']) . ' '.MONEDA.'</td>
<td><a href="/pols/cuentas/'.$row2['ID'].'/"><em>'.$row2['nombre'].'</em></a>'.$sin_impuestos.'</td>
</tr>';
}

$patrimonio_con_impuestos = $patrimonio - $patrimonio_libre_impuestos;
if ($patrimonio_con_impuestos >= $pol['config']['impuestos_minimo']) {
	$impuesto = floor( ( $patrimonio_con_impuestos * $pol['config']['impuestos']) / 100);
	$impuestos = '<em>Impuestos al dia: '.pols(-$impuesto).' '.MONEDA.'</em>';
} else {
	$impuestos = '<em style="#AAA">Sin impuestos.</em>';
}


$txt .= '
<tr><td></td><td><hr style="border:1px solid #AAA; margin:-3px; padding:0;" /></td><td></td></tr>

<tr>
<td align="right">Patrimonio Total</td>
<td align="right">' . pols($patrimonio) . ' '.MONEDA.'</td>
<td>&nbsp;&nbsp; '.$impuestos.'</td>
</tr>
</table>

<br />

<p>Referencia: <input style="background:#FFFFDD;border: 1px solid grey;" type="text" size="35" value="http://'.HOST.'/r/' . strtolower($nick) . '/" readonly="readonly" /><br />
(Ganar&aacute;s <b>' . pols($pols_afiliacion) . ' '.MONEDA.'</b> por cada nuevo Ciudadano autentico que se registre por este enlace y cumpla el minimo tiempo online en sus 30 primeros dias)</p>

<p>Clave API: <input class="api_box" type="text" size="12" value="' . $row['api_pass'] . '" readonly="readonly" /> ' . boton('Generar clave', '/accion.php?a=api&b=gen_pass', '&iquest;Seguro que deseas CAMBIAR tu clave API?\n\nLa antigua no funcionar&aacute;.') . '<br />(Esta clave equivale a tu contrase&ntilde;a, mantenla en secreto. M&aacute;s info: <a href="http://www.virtualpol.com/api.php">API</a>)</p>

<p>' . boton('Cambiar contrase&ntilde;a', REGISTRAR.'login.php?a=panel') . '</p>';

			if ($pol['pais'] != 'ninguno') {
				$txt .= '<p>' . boton('Ir a Rechazar Ciudadania', REGISTRAR) . '</p>';
			}

			$txt .= '
<p><form action="/accion.php?a=avatar&b=upload" method="post" enctype="multipart/form-data">Avatar: <input name="avatar" type="file" /><input type="submit" value="Cambiar Avatar" /> | ' . boton('Borrar Avatar', '/accion.php?a=avatar&b=borrar') . ' (jpg, max 1mb)</form></p>

<p>
<form action="/accion.php?a=afiliarse" method="post">

Partido afiliado: <select name="partido"><option value="0">Ninguno</option>';


$result2 = mysql_query("SELECT ID, siglas FROM ".SQL."partidos ORDER BY siglas ASC", $link);
while($row2 = mysql_fetch_array($result2)){
	$txt .= '<option value="' . $row2['ID'] . '">' . $row2['siglas'] . '</option>';
}
if ($pol['config']['elecciones_estado'] == 'elecciones') { $disable_afiliar = ' disabled="disabled"'; } else { $disable_afiliar = ''; }


$txt .= '
</select>

<input value="Afiliarse" type="submit"' . $disable_afiliar . '></form>
</p>

<p><form action="/accion.php?a=avatar&b=desc" method="post">Espacio para lo que quieras: (<span id="desc_limit" style="color:blue;">' . $text_limit . '</span> caracteres)<br />
<textarea name="desc" id="desc_area" style="background:#FFFFDD;border: 1px solid grey; padding:4px; color: green; font-weight: bold; width: 500px; height: 80px;">' . strip_tags($row['text'], '<b>') . '</textarea> <input value="Guardar" type="submit" />
</form></p>


<p><b>Votos de confianza recibida:</b> ';


$result2 = mysql_query("SELECT voto, time,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL_VOTOS.".uservoto_ID LIMIT 1) AS nick,
(SELECT pais FROM ".SQL_USERS." WHERE ID = ".SQL_VOTOS.".uservoto_ID LIMIT 1) AS pais
FROM ".SQL_VOTOS."
WHERE estado = 'confianza' AND user_ID = '" . $user_ID . "' AND voto = 1
ORDER BY voto DESC, time ASC", $link);
while($row2 = mysql_fetch_array($result2)) {
	
	if ($voto_anterior != $row2['voto']) { $txt .= '<br /> ' . confianza($row2['voto']) . ' &middot; '; }
	$voto_anterior = $row2['voto'];
	$txt .= crear_link($row2['nick'], 'nick', null, $row2['pais']) . ', ';
}

$txt .= '</p>

<p><b>Tus votos de confianza:</b> ';

$voto_anterior = '';
$result2 = mysql_query("SELECT voto, time,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL_VOTOS.".user_ID LIMIT 1) AS nick,
(SELECT pais FROM ".SQL_USERS." WHERE ID = ".SQL_VOTOS.".user_ID LIMIT 1) AS pais
FROM ".SQL_VOTOS."
WHERE estado = 'confianza' AND uservoto_ID = '" . $user_ID . "' AND voto != 0
ORDER BY voto DESC, time ASC", $link);
while($row2 = mysql_fetch_array($result2)) {
	if ($voto_anterior != $row2['voto']) { $txt .= '<br /> ' . confianza($row2['voto']) . ' &middot; '; }
	$voto_anterior = $row2['voto'];
	$txt .= crear_link($row2['nick'], 'nick', null, $row2['pais']) . ', ';
}


$txt .= '</p>

</div>

<br />';


		} 

		if ($row['text']) { $txt .= '<div class="amarillo">' . $row['text'] . '</div>'; }

		if ($row['ref_num'] != 0) {
			$result = mysql_query("SELECT IP, nick, pais, online FROM ".SQL_USERS." WHERE ref = '" . $row['ID'] . "' ORDER BY fecha_last DESC", $link);
			while($row2 = mysql_fetch_array($result)) {
				$refs .= crear_link($row2['nick']) . ' </b>('.duracion($row2['online']).')<b><br />' . "\n";
			}
		}

		$nota = $row['nota'];

		// empresas y partidos
		$empresas_num = 0;
		$result = mysql_query("SELECT nombre, url, cat_ID, (SELECT url FROM ".SQL."cat WHERE ID = ".SQL."empresas.cat_ID LIMIT 1) AS cat_url FROM ".SQL."empresas WHERE user_ID = '" . $row['ID'] . "' ORDER BY time DESC", $link);
		while($row2 = mysql_fetch_array($result)) {
			$empresas_num++;
			$empresas .= '<a href="/empresas/'.$row2['cat_url'].'/'.$row2['url'].'/">' . $row2['nombre'] . '</a><br />' . "\n";
		}

		$txt .= '<table border="0" cellspacing="8"><tr><td valign="top" width="220">
<p>Nivel: <b>' . $row['nivel'] . '</b></p>
<p>Nota media: <b><span class="gris">' . $nota . '</span></b></p>
<p>Tiempo online: <b><acronym title="' . $row['online'] . '">' . duracion($row['online']) . '</acronym></b></p>
<p>Elecciones: <b>' . $row['num_elec'] . '</b></p>

<p>Empresas: <b>' . $empresas_num . '</b><br /><b>' . $empresas . '</b></p>

<p>Foro: <b><acronym title="hilos+mensajes">' . $row['num_hilos'] . '+' . $row['num_msg'] . '</acronym></b></p>
<p>Referencias: <b>' . $row['ref_num'] . '</b><br /><b>' . $refs . '</b></p>
<p>Afiliado a: <b>' . crear_link($row['partido'], 'partido') . '</b></p>
<p>Bando: <b>' . $row['bando'] . '</b></p>';

if ($row['estado'] != 'desarrollador') {
	$txt .= '<p>Ultimo acceso: <acronym title="' . $row['fecha_last'] . '"><b>' . duracion(time() - strtotime($row['fecha_last'])) . '</b></acronym><br />';
}

$txt .= 'Nacido hace: <b><acronym title="' . $row['fecha_registro'] . '">'.round((time() - strtotime($row['fecha_registro'])) / 60 / 60 / 24).' dias</acronym></b><br />
';






if ($row['estado'] != 'desarrollador') {
	/*
			< 30d	- 10 dias
		30d < 90d	- 30 dias 
		90d >		- 60 dias
	*/

	$date			= date('Y-m-d 20:00:00'); 					// ahora
	$margen_10dias	= date('Y-m-d 20:00:00', time() - 864000);	// 10 dias
	$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); // 30 dias
	$margen_90dias	= date('Y-m-d 20:00:00', time() - 7776000); // 90 dias

	$time_registro = $row['fecha_registro'];



	if ($time_registro <= $margen_90dias) {
		$tiempo_inactividad = 5184000; // tras 60 dias
	} elseif (($time_registro > $margen_90dias) AND ($time_registro <= $margen_30dias)) {
		$tiempo_inactividad = 2592000; // tras 30 dias
	} else  {
		$tiempo_inactividad = 864000; // tras 10 dias
	}




	$txt .= 'Expira tras <b>'.round($tiempo_inactividad / 60 / 60 / 24).' dias</b> inactivo.';


}


/*


segundos por dia... segundos en total, segundos online. 20661318 - 4267251
//$txt .= 'Promedio: <b title="' . (time() - strtotime($row['fecha_registro'])) . ' - ' . $row['online'] . '">' . ((time() - strtotime($row['fecha_registro'])) / $row['online']) . ' por dia</b>
*/

$txt .= '</p></td><td valign="top">';


$txt .= '
<b>Ultimas 5 notas:</b>

<table border="0" cellpadding="0" cellspacing="3" class="pol_table">';


$result2 = mysql_query("SELECT ID, user_ID, time, text
FROM ".SQL."foros_msg
WHERE hilo_ID = '-1' AND user_ID = '" . $row['ID'] . "'
ORDER BY time DESC
LIMIT 5", $link);
while($row2 = mysql_fetch_array($result2)){
	$txt .= '<tr><td valign="top" class="amarillo">' . $avatar . $row2['text'] . '</td></tr>' . "\n";
}
$txt .= '</table>

<p style="margin-bottom:0px;">Cargos y Examenes: <b>' . $estudios_num . '</b> (<a href="/examenes/">Ver examenes</a>)</p>
' . $estudios . '

</td></tr></table>';


		if ($user_ID != $pol['user_ID']) {
			$txt .= '<p>' . boton('Enviar mensaje', 'http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/msg/' . strtolower($nick) . '/') . ' &nbsp; ' . boton('Transferir '.MONEDA_NOMBRE.'', 'http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/pols/transferir/' . strtolower($nick) . '/') . '</p>';
		}
		$txt .= '</div>';

		$txt_title = $nick.' - '.ucfirst($row['estado']) . ' de '.$row['pais'];
		$txt_description = $txt_title . ' ' . str_replace("\"", "", strip_tags($row['text']));

	} else { header("HTTP/1.0 404 Not Found"); exit; }
}


$txt_header .= '<style type="text/css">
#info b { color:green; }
.api_box { border: 1px solid grey; text-align:center; background:#FFFFDD; color:#FFFFDD; }
.api_box:hover { color:green; }
</style>


<script language="javascript">
function limitChars(textid, limit, infodiv) {
	var text = $("#"+textid).val(); 
	var textlength = text.length;
	if(textlength >= limit) {
		$("#" + infodiv).html("<span style=\"color:red;\">0</span>");
		$("#" + textid).val(text.substr(0,limit));
		return false;
	} else {
		$("#" + infodiv).html("<span style=\"color:blue;\">"+ (limit - textlength) +"</span>");
		return true;
	}
}

window.onload = function(){
	$("#desc_area").keyup(function(){
		limitChars("desc_area", 900, "desc_limit");
	})
}
</script>
';

//THEME
include('theme.php');
?>
