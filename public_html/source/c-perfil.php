<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

if (($_GET['a'] == 'editar') AND (isset($pol['nick']))) { redirect('/perfil/'.$pol['nick'].'/editar'); }
if ((!$_GET['a']) AND (isset($pol['nick']))) { redirect('/perfil/'.$pol['nick']); }

$result = sql("SELECT *, 
(SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND ID = users.partido_afiliado LIMIT 1) AS partido,
(SELECT COUNT(ID) FROM ".SQL."foros_hilos WHERE user_ID = users.ID LIMIT 1) AS num_hilos,
(SELECT COUNT(ID) FROM ".SQL."foros_msg WHERE user_ID = users.ID LIMIT 1) AS num_msg
FROM users 
WHERE nick = '".$_GET['a']."'
LIMIT 1");
while($r = r($result)){

	$user_ID = $r['ID'];
	if ((PAIS != $r['pais']) AND ($r['estado'] == 'ciudadano') AND ($r['pais'] != 'ninguno')) {
		redirect('http://'.strtolower($r['pais']).'.'.DOMAIN.'/perfil/'.$r['nick']);
	} elseif ($user_ID) { //nick existe

		$nick = $r['nick'];
		if ($r['avatar'] == 'true') { $p_avatar = '<span width="120" height="120"><img src="'.IMG.'a/'.$r['ID'].'.jpg" alt="'.$nick.'" /></span>'; }

		$extras = '';
		if (nucleo_acceso('supervisores_censo')) {
	
			$extras = '
<tr>
<td colspan="2"><div style="float:right;">'.boton(_('Expulsar'), 'http://'.strtolower($pol['pais']).'.'.DOMAIN.'/control/expulsiones/expulsar/'.$r['nick'], false, 'red').'</div>('.$r['ID'].', <span title="'.$r['avatar_localdir'].'" style="font-size:12px;">'.$r['email'].'</span>, '.num($r['visitas']).' v, '.num($r['paginas']).' pv,  <a href="http://www.geoiptool.com/es/?IP='.($r['IP']+rand(-30,30)).'">'.ocultar_IP($r['host'], 'host').'</a>)<br /><span style="font-size:9px;color:#666;">'.$r['nav'].'</span></td></tr>
<tr><td colspan="3" align="right">

<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=SC&b=nota&ID='.$r['ID'].'" method="post">
'._('Anotación').': <input type="text" name="nota_SC" size="35" maxlength="255" value="'.$r['nota_SC'].'" />
'.boton(_('OK'), 'submit', false, 'pill small').'
</form>

</td>

<td valign="top" align="right"><div style="font-size:12px;width:180px;max-height:100px;overflow:auto;">';
			
			foreach (explode('|', $r['hosts']) AS $el_host) { if ($el_host != '') { $extras .= ocultar_IP($el_host, 'host').'<br />'; } }
			
			$extras .= '</div></td></tr>';
		} else { $extras = ''; }
		
		if (($r['socio'] == 'true') AND (nucleo_acceso('socios'))) {
			$result2 = sql("SELECT socio_ID, PAIS FROM socios WHERE pais = '".PAIS."' AND user_ID = '".$r['ID']."' LIMIT 1");
			while ($r2 = r($result2)) { $socio_ID = PAIS.$r2['socio_ID']; }
		}

		if ($r['estado'] == 'expulsado') {
			$razon = false;
			$result2 = sql("SELECT razon FROM expulsiones WHERE user_ID = '".$r['ID']."' ORDER BY expire DESC LIMIT 1");
			while ($r2 = r($result2)) { $razon = $r2['razon']; }
		}
		$txt .= '<table border="0" cellspacing="4"><tr><td rowspan="3" valign="top" align="center">'.($r['avatar']=='true'?'<img src="'.IMG.'a/'.$r['ID'].'.jpg" alt="'.$nick.'" />':'').($r['dnie']=='true'?'<br /><img src="'.IMG.'varios/autentificacion.png" border="0" style="margin-top:6px;" />':'').'</td><td nowrap="nowrap">
<div class="amarillo">		
<h1>'.$nick.' &nbsp; <span style="color:grey;"><span'.($r['estado']!='ciudadano'?' class="'.$r['estado'].'"':'').'>'.($r['estado']=='expulsado'&&$razon==false?'Auto-eliminado':ucfirst($r['estado'])).'</span> '.($r['estado']!='expulsado'?'de '.$r['pais']:'').'</span></h1>'.(isset($socio_ID)&&nucleo_acceso('socios')?'<span class="gris" style="float:right;font-size:16px;">'._('Socio').': <b>'.$socio_ID.'</b></span>':'').(isset($r['nombre'])&&nucleo_acceso('ciudadanos')?'<span class="gris" style="font-size:16px;">'.$r['nombre'].'</span>':'').'
</div>
</td><td nowrap="nowrap">';


		// CONFIANZA
		if ((($user_ID != $pol['user_ID']) AND ($pol['user_ID']) AND ($pol['estado'] != 'expulsado'))) {

			// numero de votos emitidos
			$result2 = sql("SELECT COUNT(*) AS num FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND voto != '0'");
			while ($r2 = r($result2)) { $num_votos = $r2['num']; }

			$result2 = sql("SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND item_ID = '".$user_ID."' LIMIT 1");
			while ($r2 = r($result2)) { $hay_v_c = $r2['voto']; }
			if (!$hay_v_c) { $hay_v_c = '0'; }

			$txt .= _('Confianza').': '.confianza($r['voto_confianza']).' 
<span id="data_confianza'.$user_ID.'" class="votar" type="confianza" name="'.$user_ID.'" value="'.$hay_v_c.'"></span><br />
Votos emitidos: <b'.($num_votos <= VOTO_CONFIANZA_MAX?'':' style="color:red;"').'>'.$num_votos.'</b> de '.VOTO_CONFIANZA_MAX.'.';
		} else { $txt .= _('Confianza').': ' . confianza($r['voto_confianza']); }


		$txt .= '</td></tr>'.$extras.'</table><div id="info">';

		$cargos_num = 0;
		$los_cargos_num = 0;
		$result2 = sql("SELECT cargo_ID, cargo, nota, aprobado, time,
(SELECT nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS nombre,
(SELECT titulo FROM examenes WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS examen_nombre
FROM cargos_users
WHERE pais = '".PAIS."' AND user_ID = '" . $user_ID . "'
ORDER BY cargo DESC, aprobado ASC, nota DESC");
		while($r2 = r($result2)) {
			if ($r2['cargo'] == 'true') { 
				$dimitir = ' <span class="gris"> ('._('Cargo Ejercido').')</span>';
			}
			$los_cargos_num++;
			if ($r2['aprobado'] == 'ok') { 
				$sello = '<img src="'.IMG.'varios/estudiado.gif" alt="Aprobado" title="Aprobado" border="0" /> '; 
			} else { $sello = ''; }

			if ($r2['cargo_ID'] > 0) { $cargo_img = '<img src="'.IMG.'cargos/'.$r2['cargo_ID'].'.gif" border="0" />'; } else { $cargo_img = ''; }
			$los_cargos .= '<tr>
<td>'.$sello.'</td>
<td align="right" class="gris">'.$r2['nota'].'</td>
<td>'.$cargo_img.'</td>
<td><b><a href="/cargos/'.$r2['cargo_ID'].'">'.($r2['nombre']?$r2['nombre']:$r2['examen_nombre']).'</a></b></td>
<td style="color:#999;" align="right"><acronym title="'.$r2['time'].'">'.duracion(time()-strtotime($r2['time'])).'</acronym></td>
<td nowrap="nowrap"><b>' . $dimitir . '</b></td>
</tr>';

			$dimitir = '';
		}

		$los_cargos = '<table border="0" cellpadding="0" cellspacing="4">'.$los_cargos.'</table>';

		if ($user_ID == $pol['user_ID']) { //es USER

			$result2 = sql("SELECT valor FROM config WHERE pais = '".PAIS."' AND dato = 'pols_afiliacion' LIMIT 1");
			while($r2 = r($result2)){ if ($r2['pols'] >= $pols) { $pols_afiliacion = $r2['valor']; } }

			$text_limit = 1600 - strlen(strip_tags($r['text']));
			
			
			$txt .= '<button onclick="$(\'#editarperfil\').slideToggle(\'slow\');" style="font-weight:bold;" class="pill">'._('Editar perfil').'</button> '.boton(_('Opciones de usuario'), REGISTRAR.'login.php?a=panel').' '.boton(_('Autentificación'), SSL_URL.'dnie.php').' '.($pol['pais']!='ninguno'?boton(_('Rechazar ciudadanía'), REGISTRAR, false, 'red').' ':'').'



<div id="editarperfil"'.($_GET['b']=='editar'?'':' style="display:none;"').'>

<fieldset><legend>'._('Editar perfil').'</legend>


<fieldset><legend>'._('Tu nombre').'</legend>
<form action="/accion.php?a=perfil&b=nombre" method="post">
<p>Introduce tu nombre y apellidos. No es obligatorio, pero debe ser veráz. Será visible para los ciudadanos de '.PAIS.'.<br />
<input type="text" name="nombre" value="" size="40" maxlength="90" placeholder="'.$r['nombre'].'" required  />
 '.boton(_('Guardar'), 'submit', false, 'blue').'
</form></p>
</fieldset>';

if (ECONOMIA) {
			
$result2 = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND dato = 'impuestos' OR dato = 'impuestos_minimo'");
while($r2 = r($result2)){ $pol['config'][$r2['dato']] = $r2['valor']; }

$patrimonio = $r['pols'];
$patrimonio_libre_impuestos = 0;
$txt .= '


<fieldset><legend>'._('Tu economía').'</legend>

<table border="0">

<tr>
<td align="right">'._('Personal').'</td>
<td align="right">'.pols($r['pols']).' '.MONEDA.'</td>
<td><a href="/pols">'._('Info').'</a></td>
</tr>';


$result2 = sql("SELECT ID, pols, nombre, exenta_impuestos FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$r['ID']."'");
while($r2 = r($result2)){
	if ($r2['exenta_impuestos'] == 1) {
		$patrimonio_libre_impuestos += $r2['pols'];
		$sin_impuestos = ' - <em style="#AAA">'._('Sin impuestos').'</em>';
	}
	else {
		$sin_impuestos = '';
	}
	$patrimonio += $r2['pols'];
	$txt .= '
<tr>
<td align="right">'._('Cuenta').'</td>
<td align="right">'.pols($r2['pols']).' '.MONEDA.'</td>
<td><a href="/pols/cuentas/'.$r2['ID'].'"><em>'.$r2['nombre'].'</em></a>'.$sin_impuestos.'</td>
</tr>';
}

$patrimonio_con_impuestos = $patrimonio - $patrimonio_libre_impuestos;
if ($patrimonio_con_impuestos >= $pol['config']['impuestos_minimo']) {
	if ($pol['config']['impuestos_minimo'] < 0) { $patrimonio_con_impuestos -= $pol['config']['impuestos_minimo']; }
	$impuesto = ceil( ( $patrimonio_con_impuestos * $pol['config']['impuestos']) / 100);
	$impuestos = '<em>'._('Impuestos al día').': '.pols(-$impuesto).' '.MONEDA.'</em>';
} else {
	$impuestos = '<em style="#AAA">'._('Sin impuestos').'.</em>';
}

$txt .= '
<tr><td></td><td><hr style="border:1px solid #AAA; margin:-3px; padding:0;" /></td><td></td></tr>

<tr>
<td align="right">'._('Patrimonio total').'</td>
<td align="right">' . pols($patrimonio) . ' '.MONEDA.'</td>
<td>&nbsp;&nbsp; '.$impuestos.'</td>
</tr>
</table>

</fieldset>

<fieldset><legend>'._('Referencia').'</legend>

<p><input style="background:#FFFFDD;border: 1px solid grey;" type="text" size="35" value="http://'.HOST.'/r/' . strtolower($nick) . '/" readonly="readonly" /><br />
(Ganar&aacute;s <b>' . pols($pols_afiliacion) . ' '.MONEDA.'</b> por cada nuevo Ciudadano autentico que se registre por este enlace y cumpla el minimo tiempo online en sus 30 primeros dias)</p>

</fieldset>';

} // fin ECONOMIA

// <p>Clave API: <input class="api_box" type="text" size="12" value="' . $r['api_pass'] . '" readonly="readonly" /> ' . boton('Generar clave', '/accion.php?a=api&b=gen_pass', '&iquest;Seguro que deseas CAMBIAR tu clave API?\n\nLa antigua no funcionar&aacute;.') . ' (Equivale a tu contrase&ntilde;a, mantenla en secreto. M&aacute;s info: <a href="'.SSL_URL.'api.php">API</a>)</p>


if (!ASAMBLEA) {
	$txt .= '<form action="/accion.php?a=afiliarse" method="post">

<fieldset><legend>'._('Afiliación').'</legend>

<p><select name="partido"><option value="0">'._('Ninguno').'</option>';


	$result2 = sql("SELECT ID, siglas FROM partidos WHERE pais = '".PAIS."' ORDER BY siglas ASC");
	while($r2 = r($result2)){
		$txt .= '<option value="'.$r2['ID'].'"'.($r2['ID']==$pol['partido']?' selected="selected"':'').'>' . $r2['siglas'] . '</option>';
	}

	$txt .= '
</select>

<input value="'._('Afiliarse').'" type="submit"'.($pol['config']['elecciones_estado']=='elecciones'?' disabled="disabled"':'').'>
</form>
</p></fieldset>';

}

$txt .= '




<fieldset><legend>'._('Biografía').'</legend>
<form action="/accion.php?a=avatar&b=desc" method="post">
<p><textarea name="desc" id="desc_area" style="width:500px;height:150px;" required>'.strip_tags($r['text'], '<b>').'</textarea><br />
'.boton(_('Guardar'), 'submit', false, 'blue').' (<span id="desc_limit" style="color:blue;">'.$text_limit.'</span> '._('caracteres').')
</form></p>
</fieldset>


'.(!isset($r['x'])?'
<fieldset><legend>'._('Geolocalización').'</legend>
<p>'.boton(_('Sitúate en el mapa de usuarios'), '/geolocalizacion/fijar', false, 'large blue').'</p>
</fieldset>
':'').'


<fieldset><legend>'._('Tus perfiles en otras redes sociales').'</legend>
<form action="/accion.php?a=perfil&b=datos" method="POST">
<table border="0">
<tr>
<td colspan="2"><b>'._('Perfiles').'</b></td>
<td>&nbsp; <b>'._('Solo direcciones web').'</b> (<span style="color:red;">'._('Debe empezar por').': <span style="font-weight:bold;">http://</span> o <span style="font-weight:bold;">https://</span></span>)</td>
</tr>';



// DATOS DE PERFIL
$datos = explode('][', $r['datos']);
foreach ($datos_perfil AS $id => $dato) {
	if ($dato != '') {
		$txt .= '<tr><td align="right">'.$dato.'</td><td><img src="'.IMG.'ico/'.$id.'_32.png" width="32" width="32" alt="'.$datos.'" /></td><td><input type="url" name="'.$dato.'" value="'.$datos[$id].'" size="60" placeholder="http://" /></td></tr>';
	}
}

$txt .= '
<tr><td colspan="2"></td><td>'.boton(_('Guardar'), 'submit', false, 'blue').'</td></tr>
</table>
</form>
</fieldset>


<fieldset><legend>Avatar ('._('tu foto').')</legend>
<form action="/accion.php?a=avatar&b=upload" method="post" enctype="multipart/form-data">
<p><input name="avatar" type="file" required /> '.boton(_('Guardar'), 'submit', false, 'blue').' | ' . boton(_('Borrar avatar'), '/accion.php?a=avatar&b=borrar', false, 'red') . ' (jpg, max 1mb)</p>
</form>
</fieldset>



';


// numero de votos emitidos
$result2 = sql("SELECT COUNT(*) AS num FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND voto != '0'");
while ($r2 = r($result2)) { $num_votos = $r2['num']; }

$txt .= '<fieldset><legend>'._('Votos de confianza emitidos').' ('.$num_votos.' '._('de').' '.VOTO_CONFIANZA_MAX.')</legend><p>';

$voto_anterior = '';
$result2 = sql("SELECT voto, time,
(SELECT nick FROM users WHERE ID = v.item_ID LIMIT 1) AS nick,
(SELECT pais FROM users WHERE ID = v.item_ID LIMIT 1) AS pais
FROM votos `v`
WHERE tipo = 'confianza' AND emisor_ID = '".$user_ID."' AND voto != 0
ORDER BY voto DESC, time ASC");
while($r2 = r($result2)) {
	if ($voto_anterior != $r2['voto']) { $txt .= '<br /> ' . confianza($r2['voto']) . ' &middot; '; }
	$voto_anterior = $r2['voto'];
	$txt .= crear_link($r2['nick'], 'nick', null, $r2['pais']) . ' ';
}

$txt .= '</p></fieldset>';


$txt .= '</fieldset></div>';


		} 

		if ($r['text']) { $txt .= '<fieldset><legend>'._('Biografía').'</legend><p>'.$r['text'].'</p></fieldset>'; }

		if ($r['ref_num'] != 0) {
			$result = sql("SELECT IP, nick, pais, online FROM users WHERE ref = '" . $r['ID'] . "' ORDER BY fecha_last DESC");
			while($r2 = r($result)) {
				$refs .= crear_link($r2['nick']) . ' </b>('.duracion($r2['online']).')<b><br />' . "\n";
			}
		}

		$nota = $r['nota'];

		if (ECONOMIA) { 
			// empresas y partidos
			$empresas_num = 0;
			$result = sql("SELECT nombre, url, cat_ID, (SELECT url FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_url FROM empresas WHERE pais = '".PAIS."' AND user_ID = '".$r['ID']."' ORDER BY time DESC");
			while($r2 = r($result)) {
				$empresas_num++;
				$empresas .= '<a href="/empresas/'.$r2['cat_url'].'/'.$r2['url'].'">'.$r2['nombre'].'</a><br />'."\n";
			}
		}


		$txt .= '<table border="0" cellspacing="8"><tr><td valign="top" width="220">
'.($r['donacion']&&$pol['user_ID']?'<p>'._('Donación').': <b>'.pols($r['donacion']).' euros</b></p>':'').'
'.(ASAMBLEA?'':'<p>'._('Nivel').': <b>' . $r['nivel'] . '</b></p>').'
<p>'._('Nota media').': <b><span class="gris">' . $nota . '</span></b></p>
<p>'._('Tiempo online').': <b><acronym title="' . $r['online'] . '">' . duracion($r['online']) . '</acronym></b></p>
<p>'._('Elecciones').': <b>' . $r['num_elec'] . '</b></p>

'.(ECONOMIA?'<p>'._('Empresas').': <b>' . $empresas_num . '</b><br /><b>' . $empresas . '</b></p>':'').'

<p>'._('Foro').': <a href="/foro/mis-respuestas/'.$r['nick'].'" title="hilos+mensajes" style="font-weight:bold;">'.$r['num_hilos'].'+'.$r['num_msg'].'</a></p>
'.(ASAMBLEA?'':'<p>'._('Afiliado a').': <b>' . crear_link($r['partido'], 'partido') . '</b></p>').'
<p>'._('Último acceso').': <acronym title="' . $r['fecha_last'] . '"><b>' . duracion(time() - strtotime($r['fecha_last'])) . '</b></acronym><br />';


$txt .= _('Registrado hace').': <b><acronym title="' . $r['fecha_registro'] . '">'.round((time() - strtotime($r['fecha_registro'])) / 60 / 60 / 24).' '._('días').'</acronym></b><br />
';


/* Tramos de expiraci?n
	< 30d	- 15 dias CANCELADO
30d < 90d	- 30 dias 
90d >		- 60 dias
*/
$date			= date('Y-m-d 20:00:00'); 					// ahora
$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); // 30 dias
$margen_90dias	= date('Y-m-d 20:00:00', time() - 7776000); // 90 dias
$time_registro = $r['fecha_registro'];
if ($time_registro <= $margen_90dias) {
	$tiempo_inactividad = 5184000; // tras 60 dias
} else {
	$tiempo_inactividad = 2592000; // tras 30 dias
}
$txt .= _('Expira').' '.($r['donacion']||$r['dnie']=='true'?'<b>'._('Nunca').'</b> ('.($r['donacion']?_('Donante'):_('Autentificado')).')':'<b>'._('tras').' '.round($tiempo_inactividad / 60 / 60 / 24).' '._('días').'</b> '._('inactivo')).'


</p></td><td valign="top">



'.(nucleo_acceso('ciudadanos_global')&&is_numeric($r['x'])?'<a href="http://maps.google.es/maps?q='.round($r['y'],2).','.round($r['x'],2).'&hl=es&t=m" target="_blank"><img width="300" height="160" class="static_map" style="border:1px solid #AAA;" src="http://maps.google.com/maps/api/staticmap?
center='.$r['y'].','.$r['x'].'&amp;zoom=13&amp;size=300x160&amp;maptype=roadmap&amp;sensor=false&amp;markers=icon:'.IMG.'ico/marker.png|'.$r['y'].','.$r['x'].'" alt="Geoposición" /></a>':'').'




<p>';
$datos = explode('][', $r['datos']);
foreach ($datos_perfil AS $id => $dato) {
	if ($datos[$id] != '') {
		$txt .= '<a href="'.$datos[$id].'" target="_blank"><img src="'.IMG.'ico/'.$id.'_32.png" width="32" width="32" alt="'.$datos.'" /></a>';
	}
}
$txt .= '

(<a href="/info/seguir">'._('Seguir desde redes sociales').'</a>)</p>


<b>'._('Últimas notas').':</b>

<table border="0" cellpadding="0" cellspacing="3">';


$result2 = sql("SELECT ID, user_ID, time, text
FROM ".SQL."foros_msg
WHERE hilo_ID = '-1' AND user_ID = '" . $r['ID'] . "'
ORDER BY time DESC
LIMIT 5");
while($r2 = r($result2)){
	$txt .= '<tr><td valign="top" class="amarillo">' . $avatar . $r2['text'] . '</td></tr>' . "\n";
}
$txt .= '</table>

<p style="margin-bottom:0px;">'._('Cargos y exámenes').': <b>' . $los_cargos_num . '</b> (<a href="/examenes">'._('Ver exámenes').'</a>)</p>
'.$los_cargos.'<br />';

if ($r['grupos'] != '') {
	$txt .= '<b>'._('Grupos').'</b>:<ul>';
	$result2 = sql("SELECT nombre FROM grupos WHERE grupo_ID IN (".str_replace(' ', ',', $r['grupos']).")");
	while ($r2 = r($result2)) {
	  $txt .= '<li><a href="/grupos">'.$r2['nombre'].'</a></li>';
	}
	$txt .= '</ul>';
}





$result2 = sql("SELECT pais, titulo, url, fecha_creacion, fecha_last
FROM chats WHERE user_ID = '".$r['ID']."' 
ORDER BY fecha_creacion ASC");
while ($r2 = r($result2)) { 
	$txt .= '<li>'.duracion(time() - strtotime($r2['fecha_creacion'])).' <a href="http://'.strtolower($r2['pais']).'.'.DOMAIN.'/chats/'.$r2['url'].'"><b>'.$r2['titulo'].'</b></a> ('._('hace').' '.duracion(time() - strtotime($r2['fecha_last'])).')</li>';
}


$txt .= '</ul>

</td></tr></table>


</div>';

		$txt_title = $nick.' - '.ucfirst($r['estado']) . ' '._('de').' '.$r['pais'];
		$txt_nav = array('/info/censo'=>_('Censo'), '/perfil/'.$nick=>$nick);

		if ($user_ID != $pol['user_ID']) {
			$txt_tab['http://'.strtolower($pol['pais']).'.'.DOMAIN.'/msg/'.$nick.'/'] = _('Enviar mensaje');
			if (ECONOMIA) { $txt_tab['http://'.strtolower($pol['pais']).'.'.DOMAIN.'/pols/transferir/'.strtolower($nick) . '/'] = _('Transferir'); }
		} else {
			
		}

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
$txt_menu = 'info';
include('theme.php');
?>
