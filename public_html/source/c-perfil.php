<?php 
include('inc-login.php');

$sc = get_supervisores_del_censo();

$result = mysql_query("SELECT *, 
(SELECT siglas FROM ".SQL."partidos WHERE ID = users.partido_afiliado LIMIT 1) AS partido,
(SELECT COUNT(ID) FROM ".SQL."foros_hilos WHERE user_ID = users.ID LIMIT 1) AS num_hilos,
(SELECT COUNT(ID) FROM ".SQL."foros_msg WHERE user_ID = users.ID LIMIT 1) AS num_msg
FROM users 
WHERE nick = '" . $_GET['a'] . "'
LIMIT 1", $link);
while($r = mysql_fetch_array($result)){

	$user_ID = $r['ID'];
	if ((PAIS != $r['pais']) AND ($r['estado'] == 'ciudadano') AND ($r['pais'] != 'ninguno')) {
		header('HTTP/1.1 301 Moved Permanently'); header('Location: http://'.strtolower($r['pais']).'.virtualpol.com/perfil/'.$r['nick'].'/'); exit;
	} elseif ($user_ID) { //nick existe

		$nick = $r['nick'];
		if ($r['avatar'] == 'true') { $p_avatar = '<span width="120" height="120"><img src="'.IMG.'a/' . $r['ID'] . '.jpg" alt="'.$nick.'" /></span>'; }

		$extras = '';
		if (isset($sc[$pol['user_ID']])) {
			$hosts = explode(".", $r['host']);
			$host = '';
			if (strlen($hosts[count($hosts)-3]) > 3) { 
				$host = '*.'.$hosts[count($hosts)-3] . '.' . $hosts[count($hosts)-2] . '.' . $hosts[count($hosts)-1]; 
			}
	
			$extras = '
<tr>
<td colspan="2"><input style="float:right;" value="Expulsar" onclick="window.location.href=\'http://'.strtolower($pol['pais']).'.virtualpol.com/control/expulsiones/expulsar/'.$r['nick'].'\';" type="button"'.$exp_disabled.' />(' . $r['ID'] . ', <span title="'.$r['avatar_localdir'].'">'.$r['email'].'</span>, '.ocultar_IP($host, 'host').', <a href="http://www.geoiptool.com/es/?IP='.($r['IP']+rand(-20,20)).'">GeoIP</a>)<br /><span style="font-size:9px;color:#666;">'.$r['nav'].'</span></td></tr>
<tr><td colspan="3" align="right">

<form action="http://'.strtolower($pol['pais']).'.virtualpol.com/accion.php?a=SC&b=nota&ID='.$r['ID'].'" method="post">
Anotaci&oacute;n de SC: <input type="text" name="nota_SC" size="35" maxlength="40" value="'.$r['nota_SC'].'" />
<input type="submit" value="Guardar" />
</form>

</td></tr>';
		} else { $extras = ''; }
		
		$txt .= '<table border="0" cellspacing="4"><tr><td rowspan="3" valign="top" align="center">'.($r['avatar']=='true'?'<img src="'.IMG.'a/' . $r['ID'] . '.jpg" alt="'.$nick.'" />':'').($r['dnie']=='true'?'<br /><img src="'.IMG.'autentificacion.png" border="0" style="margin-top:6px;" />':'').'</td><td nowrap="nowrap"><h1><span class="amarillo"><img src="'.IMG.'cargos/'.$r['cargo'].'.gif" alt="Cargo" style="margin-bottom:0;" border="0" /> ' . $nick . ' &nbsp; <span style="color:grey;"><span class="' . $r['estado'] . '">' . ucfirst($r['estado']) . '</span> de ' . $r['pais'] . '</span></span></h1></td><td nowrap="nowrap">';


		// CONFIANZA
		if ((($user_ID != $pol['user_ID']) AND ($pol['user_ID']) AND ($pol['estado'] != 'expulsado'))) {

			// numero de votos emitidos
			$result2 = mysql_query("SELECT COUNT(*) AS num FROM ".SQL_VOTOS." WHERE uservoto_ID = '".$pol['user_ID']."' AND voto != '0'", $link);
			while ($r2 = mysql_fetch_array($result2)) { $num_votos = $r2['num']; }

			$result2 = mysql_query("SELECT voto FROM ".SQL_VOTOS." WHERE estado = 'confianza' AND uservoto_ID = '".$pol['user_ID']."' AND user_ID = '".$user_ID."' LIMIT 1", $link);
			while ($r2 = mysql_fetch_array($result2)) { $hay_v_c = $r2['voto']; }

			$c_select = ' disabled="disabled"';
			if ($hay_v_c == '1') { $confianza_1 = $c_select; } 
			elseif ($hay_v_c == '0') { $confianza_0 = $c_select; } 
			elseif ($hay_v_c == '-1') { $confianza_m1 = $c_select; } 
			else { $confianza_0 = $c_select; }
			if ($num_votos >= VOTO_CONFIANZA_MAX) { $confianza_1 = $c_select; $confianza_m1 = $c_select; }

$txt .= 'Confianza: '.confianza($r['voto_confianza']).'

<input type="button" value="+" onClick="window.location.href=\'http://' . $pol['pais'] . '.virtualpol.com/accion.php?a=voto&b=confianza&ID=' . $user_ID . '&nick=' . $nick . '&voto_confianza=1\';"' . $confianza_1 . ' /><input type="button" value="0" onClick="window.location.href=\'http://' . $pol['pais'] . '.virtualpol.com/accion.php?a=voto&b=confianza&ID=' . $user_ID . '&nick=' . $nick . '&voto_confianza=0\';"' . $confianza_0 . ' /><input type="button" value="&#8211;" onClick="window.location.href=\'http://' . $pol['pais'] . '.virtualpol.com/accion.php?a=voto&b=confianza&ID=' . $user_ID . '&nick=' . $nick . '&voto_confianza=-1\';"' . $confianza_m1 . ' /><br />Votos emitidos: <b'.($num_votos <= VOTO_CONFIANZA_MAX?'':' style="color:red;"').'>'.$num_votos.'</b> de '.VOTO_CONFIANZA_MAX.'.
';
		} else { $txt .= 'Confianza: ' . confianza($r['voto_confianza']); }






		$txt .= '</td></tr>'.$extras.'</table><div id="info">';

		$cargos_num = 0;
		$estudios_num = 0;
		$result2 = mysql_query("SELECT ID_estudio, cargo, nota, estado, time,
(SELECT titulo FROM ".SQL."examenes WHERE cargo_ID = ".SQL."estudios_users.ID_estudio LIMIT 1) AS nombre
FROM ".SQL."estudios_users
WHERE user_ID = '" . $user_ID . "'
ORDER BY cargo DESC, estado ASC, nota DESC", $link);
		while($r2 = mysql_fetch_array($result2)) {
			if ($r2['cargo'] == 1) { 
				$dimitir = ' <span class="gris"> (Cargo Ejercido)</span>';
				if ($r['ID'] == $pol['user_ID']) {
					$dimitir .= '</td><td><form action="/accion.php?a=cargo&b=dimitir&ID='.$r2['ID_estudio'].'" method="POST"><input type="hidden" name="pais" value="'.$pol['pais'].'" /><input type="submit" value="Dimitir"  onclick="if (!confirm(\'&iquest;Seguro que quieres DIMITIR del cargo de ' . $r2['nombre'] . '?\')) { return false; }"/></form>';
				}
			}
			$estudios_num++;
			if ($r2['estado'] == 'ok') { 
				$sello = '<img src="'.IMG.'estudiado.gif" alt="Aprobado" title="Aprobado" border="0" /> '; 
			} else { $sello = ''; }

			if ($r2['ID_estudio'] > 0) { $cargo_img = '<img src="'.IMG.'cargos/' . $r2['ID_estudio'] . '.gif" border="0" />'; } else { $cargo_img = ''; }
			$estudios .= '<tr>
<td>' . $sello . '</td>
<td align="right" class="gris">' . $r2['nota'] . '</td>
<td>' . $cargo_img . '</td>
<td><b>' . $r2['nombre'] . '</b></td>
<td style="color:#999;" align="right"><acronym title="'.$r2['time'].'">'.duracion(time()-strtotime($r2['time'])).'</acronym></td>
<td nowrap="nowrap"><b>' . $dimitir . '</b></td>
</tr>';

			$dimitir = '';
		}

		$estudios = '<table border="0" cellpadding="0" cellspacing="4">' . $estudios . '</table>';

		if ($user_ID == $pol['user_ID']) { //es USER

			$result2 = mysql_query("SELECT valor FROM ".SQL."config WHERE dato = 'pols_afiliacion' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ if ($r2['pols'] >= $pols) { $pols_afiliacion = $r2['valor']; } }

			$text_limit = 1600 - strlen(strip_tags($r['text']));
			$txt .= '<div class="azul">';

if (ECONOMIA) {
			
$result2 = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE dato = 'impuestos' OR dato = 'impuestos_minimo'", $link);
while($r2 = mysql_fetch_array($result2)){ $pol['config'][$r2['dato']] = $r2['valor']; }

$patrimonio = $r['pols'];
$patrimonio_libre_impuestos = 0;
$txt .= '<b>Tu Economia (<a href="/info/economia/">Economia Global</a>)</b>
<table border="0">

<tr>
<td align="right">Personal</td>
<td align="right">' . pols($r['pols']) . ' '.MONEDA.'</td>
<td><a href="/pols/">Info</a></td>
</tr>';


$result2 = mysql_query("SELECT ID, pols, nombre, exenta_impuestos FROM ".SQL."cuentas WHERE user_ID = '".$r['ID']."'", $link);
while($r2 = mysql_fetch_array($result2)){
	if ($r2['exenta_impuestos'] == 1) {
		$patrimonio_libre_impuestos += $r2['pols'];
		$sin_impuestos = ' - <em style="#AAA">Sin impuestos</em>';
	}
	else {
		$sin_impuestos = '';
	}
	$patrimonio += $r2['pols'];
	$txt .= '
<tr>
<td align="right">Cuenta</td>
<td align="right">' . pols($r2['pols']) . ' '.MONEDA.'</td>
<td><a href="/pols/cuentas/'.$r2['ID'].'/"><em>'.$r2['nombre'].'</em></a>'.$sin_impuestos.'</td>
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
(Ganar&aacute;s <b>' . pols($pols_afiliacion) . ' '.MONEDA.'</b> por cada nuevo Ciudadano autentico que se registre por este enlace y cumpla el minimo tiempo online en sus 30 primeros dias)</p>';

} // fin ECONOMIA

// <p>Clave API: <input class="api_box" type="text" size="12" value="' . $r['api_pass'] . '" readonly="readonly" /> ' . boton('Generar clave', '/accion.php?a=api&b=gen_pass', '&iquest;Seguro que deseas CAMBIAR tu clave API?\n\nLa antigua no funcionar&aacute;.') . ' (Equivale a tu contrase&ntilde;a, mantenla en secreto. M&aacute;s info: <a href="http://www.virtualpol.com/api.php">API</a>)</p>
$txt .= '<p>'.boton('Cambiar contrase&ntilde;a', REGISTRAR.'login.php?a=panel').' 
'.boton('Autentificar con DNIe', SSL_URL.'dnie.php').' 
'.($pol['pais']!='ninguno'?boton('Rechazar Ciudadania', REGISTRAR).' ':'').'</p>

<p><form action="/accion.php?a=avatar&b=upload" method="post" enctype="multipart/form-data">Avatar: <input name="avatar" type="file" /><input type="submit" value="Cambiar Avatar" /> | ' . boton('Borrar Avatar', '/accion.php?a=avatar&b=borrar') . ' (jpg, max 1mb)</form></p>

<p>
<form action="/accion.php?a=afiliarse" method="post">

Afiliaci&oacute;n: <select name="partido"><option value="0">Ninguno</option>';


$result2 = mysql_query("SELECT ID, siglas FROM ".SQL."partidos ORDER BY siglas ASC", $link);
while($r2 = mysql_fetch_array($result2)){
	$txt .= '<option value="' . $r2['ID'] . '">' . $r2['siglas'] . '</option>';
}
if ($pol['config']['elecciones_estado'] == 'elecciones') { $disable_afiliar = ' disabled="disabled"'; } else { $disable_afiliar = ''; }


$txt .= '
</select>

<input value="Afiliarse" type="submit"' . $disable_afiliar . '></form>
</p>

<p><form action="/accion.php?a=avatar&b=desc" method="post">Espacio para lo que quieras: (<span id="desc_limit" style="color:blue;">' . $text_limit . '</span> caracteres)<br />
<textarea name="desc" id="desc_area" style="background:#FFFFDD;border: 1px solid grey; padding:4px; color: green; font-weight: bold; width: 500px; height: 80px;">' . strip_tags($r['text'], '<b>') . '</textarea> <input value="Guardar" type="submit" />
</form></p>';

/*
$txt .= '<p><b>Votos de confianza recibida:</b> ';
$result2 = mysql_query("SELECT voto, time,
(SELECT nick FROM users WHERE ID = ".SQL_VOTOS.".uservoto_ID LIMIT 1) AS nick,
(SELECT pais FROM users WHERE ID = ".SQL_VOTOS.".uservoto_ID LIMIT 1) AS pais
FROM ".SQL_VOTOS."
WHERE estado = 'confianza' AND user_ID = '" . $user_ID . "' AND voto != 0
ORDER BY voto DESC, time ASC", $link);
while($r2 = mysql_fetch_array($result2)) {
	
	if ($voto_anterior != $r2['voto']) { $txt .= '<br /> ' . confianza($r2['voto']) . ' &middot; '; }
	$voto_anterior = $r2['voto'];
	$txt .= crear_link($r2['nick'], 'nick', null, $r2['pais']) . ', ';
}

$txt .= '</p>';
*/

// numero de votos emitidos
$result2 = mysql_query("SELECT COUNT(*) AS num FROM ".SQL_VOTOS." WHERE uservoto_ID = '".$pol['user_ID']."' AND voto != '0'", $link);
while ($r2 = mysql_fetch_array($result2)) { $num_votos = $r2['num']; }

$txt .= '<p><b>Votos de confianza emitidos:</b> (<span style="font-weight:bold;">'.$num_votos.'</span> de '.VOTO_CONFIANZA_MAX.')';

$voto_anterior = '';
$result2 = mysql_query("SELECT voto, time,
(SELECT nick FROM users WHERE ID = ".SQL_VOTOS.".user_ID LIMIT 1) AS nick,
(SELECT pais FROM users WHERE ID = ".SQL_VOTOS.".user_ID LIMIT 1) AS pais
FROM ".SQL_VOTOS."
WHERE estado = 'confianza' AND uservoto_ID = '" . $user_ID . "' AND voto != 0
ORDER BY voto DESC, time ASC", $link);
while($r2 = mysql_fetch_array($result2)) {
	if ($voto_anterior != $r2['voto']) { $txt .= '<br /> ' . confianza($r2['voto']) . ' &middot; '; }
	$voto_anterior = $r2['voto'];
	$txt .= crear_link($r2['nick'], 'nick', null, $r2['pais']) . ', ';
}


$txt .= '</p>';


$txt .= '</div>

<br />';


		} 

		if ($r['text']) { $txt .= '<div class="amarillo">' . $r['text'] . '</div>'; }

		if ($r['ref_num'] != 0) {
			$result = mysql_query("SELECT IP, nick, pais, online FROM users WHERE ref = '" . $r['ID'] . "' ORDER BY fecha_last DESC", $link);
			while($r2 = mysql_fetch_array($result)) {
				$refs .= crear_link($r2['nick']) . ' </b>('.duracion($r2['online']).')<b><br />' . "\n";
			}
		}

		$nota = $r['nota'];

		if (ECONOMIA) { 
			// empresas y partidos
			$empresas_num = 0;
			$result = mysql_query("SELECT nombre, url, cat_ID, (SELECT url FROM ".SQL."cat WHERE ID = ".SQL."empresas.cat_ID LIMIT 1) AS cat_url FROM ".SQL."empresas WHERE user_ID = '" . $r['ID'] . "' ORDER BY time DESC", $link);
			while($r2 = mysql_fetch_array($result)) {
				$empresas_num++;
				$empresas .= '<a href="/empresas/'.$r2['cat_url'].'/'.$r2['url'].'/">' . $r2['nombre'] . '</a><br />' . "\n";
			}
		}

		$txt .= '<table border="0" cellspacing="8"><tr><td valign="top" width="220">
<p>Nivel: <b>' . $r['nivel'] . '</b></p>
<p>Nota media: <b><span class="gris">' . $nota . '</span></b></p>
<p>Tiempo online: <b><acronym title="' . $r['online'] . '">' . duracion($r['online']) . '</acronym></b></p>
<p>Elecciones: <b>' . $r['num_elec'] . '</b></p>

'.(ECONOMIA?'<p>Empresas: <b>' . $empresas_num . '</b><br /><b>' . $empresas . '</b></p>':'').'

<p>Foro: <b><acronym title="hilos+mensajes">' . $r['num_hilos'] . '+' . $r['num_msg'] . '</acronym></b></p>
<!--<p>Referencias: <b>' . $r['ref_num'] . '</b><br /><b>' . $refs . '</b></p>-->
<p>Afiliado a: <b>' . crear_link($r['partido'], 'partido') . '</b></p>
<p>Ultimo acceso: <acronym title="' . $r['fecha_last'] . '"><b>' . duracion(time() - strtotime($r['fecha_last'])) . '</b></acronym><br />';


$txt .= 'Registrado hace: <b><acronym title="' . $r['fecha_registro'] . '">'.round((time() - strtotime($r['fecha_registro'])) / 60 / 60 / 24).' dias</acronym></b><br />
';


/* Tramos de expiración
	< 30d	- 10 dias
30d < 90d	- 30 dias 
90d >		- 60 dias
*/
$date			= date('Y-m-d 20:00:00'); 					// ahora
$margen_10dias	= date('Y-m-d 20:00:00', time() - 864000);	// 10 dias
$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); // 30 dias
$margen_90dias	= date('Y-m-d 20:00:00', time() - 7776000); // 90 dias
$time_registro = $r['fecha_registro'];
if ($time_registro <= $margen_90dias) {
	$tiempo_inactividad = 5184000; // tras 60 dias
} elseif (($time_registro > $margen_90dias) AND ($time_registro <= $margen_30dias)) {
	$tiempo_inactividad = 2592000; // tras 30 dias
} else  {
	$tiempo_inactividad = 864000; // tras 10 dias
}
$txt .= 'Expira '.($r['dnie']=='true'?'<b>Nunca</b> (Autentificado)':'<b>tras '.round($tiempo_inactividad / 60 / 60 / 24).' dias</b> inactivo').'


</p></td><td valign="top">


<b>Ultimas 5 notas:</b>

<table border="0" cellpadding="0" cellspacing="3" class="pol_table">';


$result2 = mysql_query("SELECT ID, user_ID, time, text
FROM ".SQL."foros_msg
WHERE hilo_ID = '-1' AND user_ID = '" . $r['ID'] . "'
ORDER BY time DESC
LIMIT 5", $link);
while($r2 = mysql_fetch_array($result2)){
	$txt .= '<tr><td valign="top" class="amarillo">' . $avatar . $r2['text'] . '</td></tr>' . "\n";
}
$txt .= '</table>

<p style="margin-bottom:0px;">Cargos y Examenes: <b>' . $estudios_num . '</b> (<a href="/examenes/">Ver examenes</a>)</p>
' . $estudios . '

<ul>
';



$result2 = mysql_query("SELECT pais, titulo, url, fecha_creacion, fecha_last
FROM chats WHERE user_ID = '".$r['ID']."' 
ORDER BY fecha_creacion ASC", $link);
while ($r2 = mysql_fetch_array($result2)) { 
	$txt .= '<li>'.duracion(time() - strtotime($r2['fecha_creacion'])).' <a href="http://'.strtolower($r2['pais']).DEV.'.virtualpol.com/chats/'.$r2['url'].'/"><b>'.$r2['titulo'].'</b></a> (hace '.duracion(time() - strtotime($r2['fecha_last'])).')</li>';
}


$txt .= '</ul>

</td></tr></table>';


		if ($user_ID != $pol['user_ID']) {
			$txt .= '<p>' . boton('Enviar mensaje', 'http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/msg/' . strtolower($nick) . '/') . ' &nbsp; ' . boton('Transferir '.MONEDA_NOMBRE.'', 'http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/pols/transferir/' . strtolower($nick) . '/') . '</p>';
		}
		$txt .= '</div>';

		$txt_title = $nick.' - '.ucfirst($r['estado']) . ' de '.$r['pais'];
		$txt_description = $txt_title . ' ' . str_replace("\"", "", strip_tags($r['text']));

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
		limitChars("desc_area", 1300, "desc_limit");
	})
}
</script>
';

//THEME
include('theme.php');
?>
