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
	$nick = $r['nick'];
	$user_ID = $r['ID'];

	if ((PAIS != $r['pais']) AND ($r['estado'] == 'ciudadano') AND ($r['pais'] != 'ninguno')) {
		redirect(vp_url('/perfil/'.$nick, $r['pais']));
	} elseif ($user_ID) { // usuario ok

		

	if (($r['socio'] == 'true') AND (nucleo_acceso('socios'))) {
		$result2 = sql("SELECT socio_ID, PAIS FROM socios WHERE pais = '".PAIS."' AND user_ID = '".$r['ID']."' LIMIT 1");
		while ($r2 = r($result2)) { $socio_ID = PAIS.$r2['socio_ID']; }
	}

	if ($r['estado'] == 'expulsado') {
		$razon = false;
		$result2 = sql("SELECT razon FROM expulsiones WHERE estado='expulsado' and user_ID = '".$r['ID']."' ORDER BY expire DESC LIMIT 1");
		while ($r2 = r($result2)) { $razon = $r2['razon']; }
	}



		$txt .= '<div style="width:750px;"><table width="100%">';

		if (nucleo_acceso('supervisores_censo')) { // INFO PARA SC
			$txt .= '<tr><td>
<div style="float:right;">
<form action="'.accion_url($pol['pais']).'a=SC&b=nota&ID='.$r['ID'].'" method="post">
<input type="text" name="nota_SC" size="25" maxlength="255" value="'.$r['nota_SC'].'" />
'.boton(_('OK'), 'submit', false, 'pill small').'
'.boton('&nbsp;', '/sc/filtro/user_ID/'.$r['ID'], false, 'blue small').' 
'.boton('&nbsp;', '//'.strtolower($pol['pais']).'.'.DOMAIN.'/control/expulsiones/expulsar/'.$r['nick'], false, 'red small').'
</form>
</div>
'.$r['ID'].' <span title="'.$r['avatar_localdir'].'" style="font-size:11px;">'.$r['email'].'</span> <span style="font-size:12px;" title="'.$r['nav'].'">'.num($r['visitas']).'v '.num($r['paginas']).'pv<br /><a href="http://www.geoiptool.com/es/?IP='.($r['IP']+rand(-30,30)).'">'.ocultar_IP($r['host'], 'host').'</a></span>
</td></tr>';
		}





// ***************
// START ZONA EDIT
if ($user_ID == $pol['user_ID']) { //es USER

	$result2 = sql("SELECT valor FROM config WHERE pais = '".PAIS."' AND dato = 'pols_afiliacion' LIMIT 1");
	while($r2 = r($result2)){ if ($r2['pols'] >= $pols) { $pols_afiliacion = $r2['valor']; } }

	$text_limit = 1600 - strlen(strip_tags($r['text']));
	
	
	$txt .= '<tr><td><p style="text-align:center;"><button onclick="$(\'#editarperfil\').slideToggle(\'slow\');" style="font-weight:bold;" class="pill">'._('Editar perfil').'</button> '.boton(_('Opciones de usuario'), REGISTRAR.'login.php?a=panel').' '.boton(_('Autentificación'), SSL_URL.'dnie.php').' '.($pol['pais']!='ninguno'?boton(_('Cambiar ciudadanía'), REGISTRAR, false, 'red').' ':'').'<p>



<div id="editarperfil"'.($_GET['b']=='editar'?' ':' style="display:none;"').'>

<fieldset><legend>'._('Editar perfil').'</legend>


<fieldset><legend>'._('Tu nombre').'</legend>
<form action="'.accion_url().'a=perfil&b=nombre" method="post">
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

	$txt .= '
<fieldset><legend>'._('Biografía').'</legend>
<form action="'.accion_url().'a=avatar&b=desc" method="post">
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
<form action="'.accion_url().'a=perfil&b=datos" method="POST">
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
<form action="'.accion_url().'a=avatar&b=upload" method="post" enctype="multipart/form-data">
<p><input name="avatar" type="file" required /> '.boton(_('Guardar'), 'submit', false, 'blue').' | ' . boton(_('Borrar avatar'), accion_url().'a=avatar&b=borrar', false, 'red') . ' (jpg, max 1mb)</p>
</form>
</fieldset>';


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


	$txt .= '<fieldset><legend>Clave API</legend><input class="api_box" type="text" size="12" value="'.$r['api_pass'].'" readonly="readonly" /> '.boton('Generar clave', accion_url().'a=api&b=gen_pass', '¿Seguro que deseas CAMBIAR tu clave API?\n\nLa antigua dejará de funcionar.', 'blue').' (Equivale a tu contraseña, mantenla en secreto. Más info: <a href="'.SSL_URL.'api.php">API</a>)</fieldset>';

	if (!ASAMBLEA) {
		$txt .= '<form action="'.accion_url().'a=afiliarse" method="post">

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
	$txt .= '</fieldset></div>';
	$txt .= '</td></tr>';
}
// FIN ZONA EDIT
// *************








$txt .= '
<tr>
<td align="center"><h1>'.$nick.''.($r['dnie']=='true'?' <span class="icon medium" style="color:#339900;" data-icon="l" title="Autentificado"></span>':'').(isset($r['nombre'])&&nucleo_acceso('ciudadanos')?' <span style="color:#BBB;font-size:18px;">'.$r['nombre'].'</span>':'').'</h1>

<span style="color:grey;font-size:22px;"><b><span'.($r['estado']!='ciudadano'?' class="'.$r['estado'].'"':'').'>'.($r['estado']=='expulsado'&&$razon==false?_('Auto-eliminado'):ucfirst($r['estado'])).'</span> '.($r['estado']!='expulsado'?''._('de').' '.$pol['config']['pais_des']:'').'</b></span>
</td>
</tr>


<tr>
<td align="center" nowrap>

'.($r['avatar']=='true'?'<img src="'.IMG.'a/'.$r['ID'].'.jpg" alt="'.$nick.'" title="Avatar" width="120" height="120" style="border:1px solid #AAA;" />':'').'

'.(nucleo_acceso('ciudadanos_global')&&is_numeric($r['x'])?'<a href="http://maps.google.es/maps?q='.$r['y'].','.$r['x'].'&hl=es&t=m" target="_blank"><img width="250" height="120" class="static_map" style="border:1px solid #AAA;" src="//maps.google.com/maps/api/staticmap?
center='.$r['y'].','.$r['x'].'&amp;zoom=11&amp;size=250x120&amp;maptype=roadmap&amp;sensor=false&amp;markers=icon:'.IMG.'ico/marker.png|'.$r['y'].','.$r['x'].'" alt="Geo" title="Geolocalización" /></a>':'').'

'.(str_replace(array('0', ' '), '', $r['confianza_historico'])!=''?'
<img src="https://chart.googleapis.com/chart
?chs=250x120
&chf=bg,s,F4F4F4
&chm=B,FFFFFF,0,0,0
&cht=lc
&chco=EA9800
&chls=5|1
&chma=10,10
&chds=a
&chd=t:'.implode(',', explode(' ', trim($r['confianza_historico']))).'
&chxt=x
&chxl=0:|'.implode('|', explode(' ', trim($r['confianza_historico']))).'
" width="250" height="120" alt="Confianza historico" title="Confianza semanal" style="border:1px solid #AAA;" />':'').'
</td>
</tr>

</table>


<table width="100%" class="gris">

<tr>
<td align="right">'._('Confianza').':</td>
<td>';


// CONFIANZA
if ((($user_ID != $pol['user_ID']) AND ($pol['user_ID']) AND ($pol['estado'] != 'expulsado'))) {
	// numero de votos emitidos
	$result2 = sql("SELECT COUNT(*) AS num FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND voto != '0'");
	while ($r2 = r($result2)) { $num_votos = $r2['num']; }

	$result2 = sql("SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND item_ID = '".$user_ID."' LIMIT 1");
	while ($r2 = r($result2)) { $hay_v_c = $r2['voto']; }
	if (!$hay_v_c) { $hay_v_c = '0'; }

	$txt .= confianza($r['voto_confianza']).' 
<span id="data_confianza'.$user_ID.'" class="votar" type="confianza" name="'.$user_ID.'" value="'.$hay_v_c.'"></span><br />
<span style="font-size:12px;">'._('Emitidos').' <b'.($num_votos <= VOTO_CONFIANZA_MAX?'':' style="color:red;"').'>'.$num_votos.'</b> '._('de').' '.VOTO_CONFIANZA_MAX.'</span>';
} else { $txt .= confianza($r['voto_confianza']); }

$txt .= '</td>

<td align="right" nowrap>'._('Redes sociales').':</td>
<td>';
$datos_n = 0;
$datos = explode('][', $r['datos']);
foreach ($datos_perfil AS $id => $dato) {
	if ($datos[$id] != '') { $datos_n++; $txt .= '<a href="'.$datos[$id].'" target="_blank"><img src="'.IMG.'ico/'.$id.'_32.png" width="32" height="32" alt="'.$datos.'" /></a> '; }
}
if ($datos_n == 0) { $txt .= '<b>'._('Ninguna').'</b>'; }
$txt .= '</td>
</tr>



<tr>
<td align="right">'._('Último acceso').':</td>
<td><b>'.timer($r['fecha_last']).'</b></td>

<td align="right" title="Elecciones en las que ha participado: '.$r['num_elec'].'">'._('Cargos').':</td>
<td><b>';
$result2 = sql("SELECT cargo_ID, cargo, nota,
(SELECT nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS nombre,
(SELECT nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS nivel
FROM cargos_users
WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."' AND (cargo = 'true' OR aprobado = 'ok')
ORDER BY nivel DESC");
while($r2 = r($result2)) {
	if ($r2['cargo'] == 'true') {
		$txt_cargos[] = '<a href="/cargos/'.$r2['cargo_ID'].'"><img src="'.IMG.'cargos/'.$r2['cargo_ID'].'.gif" width="16" height="16" alt="'.$r2['nombre'].'" title="'.$r2['nombre'].' ('.$r2['nota'].')" /></a>';
	} else {
		$txt_cand[] = '<a href="/cargos/'.$r2['cargo_ID'].'"><img src="'.IMG.'cargos/'.$r2['cargo_ID'].'.gif" width="16" height="16" alt="'.$r2['nombre'].'" title="'.$r2['nombre'].' ('.$r2['nota'].')" /></a>';
	}
}
$txt .= (count($txt_cargos)>0?implode(' ', $txt_cargos):_('Ninguno')).'</b></td>
</tr>


<tr>
<td align="right">'._('Tiempo online').':</td>
<td><b>'.timer(time()-$r['online'], true).'</b> <span title="'.num($r['paginas']).' páginas vistas">('.num($r['visitas']).' '._('visitas').')</span></td>

<td align="right" title="Nota media: '.$r['nota'].'">'._('Candidaturas').':</td>
<td><b>'.(count($txt_cand)>0?implode(' ', $txt_cand):_('Ninguna')).'</b></td>
</tr>



<tr>
<td align="right">'._('Registrado').' '._('hace').':</td>
<td><b>'.timer($r['fecha_registro']).'</b></td>

<td align="right" title="Expiración por inactividad">'._('Expiración').':</td>
<td><b>';



/* Expiraciones:
Tras 90 dias inactivo

Excepciones:
* Autentificados
* Socios
* Donantes
* Veteranos (más de 2 años de antiguedad)
*/

if ($r['fecha_registro'] < tiempo(365*2)) {  $txt .= _('Vitalicio').' ('._('veterano').')'; } 
elseif ($r['dnie'] == 'true') { $txt .= _('Vitalicio').' ('._('autentificado').')'; }
elseif ($r['socio'] == 'true') { $txt .= _('Vitalicio').' ('._('asociado').')'; }
elseif ($r['donacion'] > 0) { $txt .= _('Vitalicio').' ('._('donante').')'; }
else { $txt .= _('En').' '.num(90-((time()-strtotime($r['fecha_last']))/60/60/24)).' '._('días'); }




$txt .= '</b></td>
</tr>



<tr>
<td align="right">'._('Foro').':</td>
<td><a href="/foro/mis-respuestas/'.$r['nick'].'"><b>'.num($r['num_hilos']).' '._('hilos').' / '.num($r['num_msg']).' '._('mensajes').'</b></a></td>

<td align="right">'._('Grupos').':</td>
<td><b>';
$txt_grupos = array();
if ($r['grupos']) {
	$result2 = sql("SELECT nombre FROM grupos WHERE grupo_ID IN (".str_replace(' ', ',', $r['grupos']).")");
	while ($r2 = r($result2)) { $txt_grupos[] = '<a href="/grupos">'.$r2['nombre'].'</a>'; }
}
$txt .= (count($txt_grupos)>0?implode(' ', $txt_grupos):_('Ninguno')).'</b></td>
</tr>

<tr>
<td colspan="2" width="50%"></td>
<td colspan="2" width="50%"></td>
</tr>


</table>

'.($r['text']!=''&&nucleo_acceso('ciudadanos_global')?'<fieldset><legend>'._('Biografía').'</legend><p>'.$r['text'].'</p></fieldset>':'').'



</div>';





/*

'.(isset($socio_ID)&&nucleo_acceso('socios')?'<span class="gris" style="float:right;font-size:16px;">'._('Socio').': <b>'.$socio_ID.'</b></span>':'').(isset($r['nombre'])&&nucleo_acceso('ciudadanos')?'<span class="gris" style="font-size:16px;">'.$r['nombre'].'</span>':'').'


		if ($r['ref_num'] != 0) {
			$result = sql("SELECT IP, nick, pais, online FROM users WHERE ref = '" . $r['ID'] . "' ORDER BY fecha_last DESC");
			while($r2 = r($result)) {
				$refs .= crear_link($r2['nick']) . ' </b>('.duracion($r2['online']).')<b><br />' . "\n";
			}
		}
		if (ECONOMIA) { 
			// empresas y partidos
			$empresas_num = 0;
			$result = sql("SELECT nombre, url, cat_ID, (SELECT url FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_url FROM empresas WHERE pais = '".PAIS."' AND user_ID = '".$r['ID']."' ORDER BY time DESC");
			while($r2 = r($result)) {
				$empresas_num++;
				$empresas .= '<a href="/empresas/'.$r2['cat_url'].'/'.$r2['url'].'">'.$r2['nombre'].'</a><br />'."\n";
			}
		}
*/


		$txt_title = $nick.' - '.ucfirst($r['estado']) . ' '._('de').' '.$r['pais'];
		$txt_nav = array('/info/censo'=>_('Censo'), '/perfil/'.$nick=>$nick);

		if ($user_ID != $pol['user_ID']) {
			$txt_tab['http://'.strtolower($pol['pais']).'.'.DOMAIN.'/msg/'.$nick.'/'] = _('Enviar mensaje');
			if (ECONOMIA) { $txt_tab['http://'.strtolower($pol['pais']).'.'.DOMAIN.'/pols/transferir/'.strtolower($nick) . '/'] = _('Transferir'); }
		}


	} else { header("HTTP/1.0 404 Not Found"); mysql_close($link); exit; }
}


$txt_header .= '<style type="text/css">
#content { color:#333; }
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