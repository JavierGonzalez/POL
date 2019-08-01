<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

switch ($_GET['a']) {



case 'supervision-del-censo':

	$txt_nav[] = 'Supervisión del Censo';
	$txt_title = 'Supervisión del censo';
	$txt .= '


<div class="col_5">
<fieldset><legend>Supervisores del censo</legend><table width="100%">
<tr>
<th colspan="2" style="font-weight:normal;">Confianza</th>
<th></th>
<th align="right" style="font-weight:normal;" nowrap>Antigüedad</th>
</tr>';


$result = sql("SELECT ID AS user_ID, nick, pais, voto_confianza, fecha_last, fecha_registro, (SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND item_ID = users.ID LIMIT 1) AS has_votado FROM users WHERE SC = 'true' ORDER BY voto_confianza DESC");
while($r = r($result)) {
	$txt .= '<tr>
<td align="right" nowrap="nowrap"><span id="confianza'.$r['user_ID'].'">'.confianza($r['voto_confianza']).'</span></td>
<td nowrap="nowrap">'.($pol['user_ID']&&$r['user_ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['user_ID'].'" class="votar" type="confianza" name="'.$r['user_ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>
<td nowrap><span class="gris" style="float:right">'.timer($r['fecha_last']).'</span><b>'.crear_link($r['nick']).'</b></td>
<td align="right" nowrap>'.timer($r['fecha_registro']).'</td>
</tr>';
}

$txt .= '</table>
<p class="gris">* Asignados automáticamente cada Domingo a las 20:00.<br />
* El balance de votos de confianza se actualiza cada 24h.</p>
</fieldset>


<fieldset><legend>Candidatos a supervisor del censo</legend><table width="100%">';


$result = sql("SELECT ID AS user_ID, nick, pais, voto_confianza, fecha_last, fecha_registro, (SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND item_ID = users.ID LIMIT 1) AS has_votado FROM users WHERE SC = 'false' AND ser_SC = 'true' AND fecha_registro < '".tiempo(365)."' AND voto_confianza > 0 ORDER BY voto_confianza DESC LIMIT 10");
while($r = r($result)) {
	$txt .= '<tr>
<td align="right" nowrap="nowrap"><span id="confianza'.$r['user_ID'].'">'.confianza($r['voto_confianza']).'</span></td>
<td nowrap="nowrap">'.($pol['user_ID']&&$r['user_ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['user_ID'].'" class="votar" type="confianza" name="'.$r['user_ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>
<td nowrap><span class="gris" style="float:right">'.timer($r['fecha_last']).'</span>'.crear_link($r['nick']).'</td>
<td align="right" nowrap>'.timer($r['fecha_registro']).'</td>
</tr>';
}

$txt .= '</table>
<p class="gris"><em>Requisitos para ser candidato:</em><br />
1. Antiguedad de al menos un año.<br />
2. Postularse como candidato voluntario (<a href="'.REGISTRAR.'login.php?a=panel">aquí</a>).
</p>
</fieldset>
</div>

<div class="col_7">

<fieldset><legend>Información</legend>
<p>VirtualPol tiene -por necesidad- un avanzado sistema de supervisión del censo. Las <a href="http://www.virtualpol.com/TOS">Condiciones de Uso</a> (TOS) regulan lo estrictamente esencial, por ejemplo la creación de más de un usuario por persona.</p>
<p>Los encargados de aplicar el TOS -con ayuda de un avanzado sistema de detección- son los supervisores del censo. Son los '.SC_NUM.' ciudadanos de VirtualPol con más votos de confianza y al menos un año de antiguedad, elegidos semanalmente por democracia directa de forma automática.</p>
<p>La función de esta página es aportar la máxima transparencia posible sobre esta importante labor.</p>
<p class="gris">* <a href="http://www.virtualpol.com/reglamento-sc">Reglamento de Supervisión del Censo</a></p>
</fieldset>


<fieldset><legend>Últimas expulsiones</legend>

<table width="100%">
<tr>
<th></th>
<th style="font-weight:normal;">'._('Motivo').'</th>
<th style="font-weight:normal;">'._('Hace').'</th>
</tr>';


	$result = sql("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado,
(SELECT pais FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_pais,
(SELECT estado FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_estado
FROM expulsiones
WHERE estado != 'indultado'
ORDER BY expire DESC LIMIT 15");
	while($r = r($result)){
		
		if ((isset($sc[$pol['user_ID']])) AND ($r['expulsado_pais']) AND ($r['estado'] == 'expulsado')) { 
			$expulsar = boton(_('Cancelar'), accion_url().'a=expulsar&b=desexpulsar&ID='.$r['ID'], '&iquest;Seguro que quieres CANCELAR la EXPULSION del usuario: '.$r['tiempo'].'?', 'small red'); 
		} elseif ($r['estado'] == 'cancelado') { $expulsar = '<b style="font-weight:bold;">'._('Cancelado').'</b>'; } else { $expulsar = ''; }

		if (!$r['expulsado_estado']) { $r['expulsado_estado'] = 'expulsado'; }

		$txt .= '
<tr><td valign="top" nowrap>'.crear_link($r['tiempo'], 'nick', $r['expulsado_estado'], $r['expulsado_pais']).'</td>
<td valign="top">'.$r['razon'].'</td>
<td valign="top" align="right" valign="top" nowrap="nowrap" class="gris" title="'.$r['expire'].'">'.timer($r['expire']).'</td>
</tr>'."\n";

		}
		$txt .= '</table>
<p class="gris">* Puedes comprobar en qué consiste cada infracción en las <a href="http://www.virtualpol.com/TOS">Condiciones de Uso</a>.</p>
<p><a href="/control/expulsiones">Ver lista completa</a></p>
</fieldset>
</div>


<div style="height:920px;"></div>';

	break;



case 'seguir':


function red_social($red, $ID) {

	if ($red == 'twitter') {
		return '<a href="https://twitter.com/'.$ID.'" class="twitter-follow-button" data-show-count="false" data-lang="es" data-size="large">Seguir @'.$ID.'</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
	}

}

$txt_title = _('Sala de seguir');
$txt_nav = array(_('Seguir'));

$txt .= '<table border="0">

<tr>
<td align="right" colspan="2"><em>VirtualPol</em></td>
<td>'.red_social('twitter', 'VirtualPol').'</td>
<td></td>
</tr>';


if (ASAMBLEA) {
	$txt .= '<tr>
<td align="right" colspan="2"><em>Asamblea Virtual</em></td>
<td>'.red_social('twitter', 'AsambleaVirtuaI').'</td>
<td></td>
</tr>';
}


$txt .= '<tr>
<th>'._('Ciudadano').'</th>
<th>'._('Confianza').'</th>
<th>Twitter</th>
</tr>';

	$dias = 1;
	$result = mysql_query("SELECT ID, nick, datos, voto_confianza
FROM users
WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND datos != ''
ORDER BY voto_confianza DESC
LIMIT 10000", $link);
	while($r = mysql_fetch_array($result)) { 

		$datos_array = explode('][', $r['datos']);

		// TWITTER
		$twitter_ID = false;
		$twitter = false;
		if ($datos_array[1] != '') {
			foreach (explode('/', '/'.$datos_array[1]) AS $elemento) { $twitter_ID = $elemento; }
			$twitter_ID = str_replace('#', '', str_replace('@', '', $twitter_ID));
			if (strlen($twitter_ID) >= 3) { 
				$twitter = red_social('twitter', $twitter_ID); 
			}
		}

		if ($twitter) {
			$txt .= '<tr>
<td align="right">'.crear_link($r['nick']).'</td>
<td align="right">'.confianza($r['voto_confianza']).'</td>
<td>'.($r['ID']==$pol['user_ID']?'':$twitter).'</td>
<td></td>
</tr>';
		}

	}
	$txt .= '</table>';

	break;

case 'expiracion':


	$margen_15dias	= date('Y-m-d 20:00:00', time() - 1296000); // 15 dias
	$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); // 30 dias
	$margen_90dias	= date('Y-m-d 20:00:00', time() - 7776000); // 90 dias

	$txt .= '
<table><tr><td valign="top"><h2>30 '._('días').'</h2>


<table border="0">
<tr>
<th>#</th>
<th>'._('Días').'</th>
<th>'._('Ciudadanos').'</th>
</tr>';
	$dias = 1;
	$result = mysql_query("SELECT fecha_last, COUNT(*) AS num, DAY(fecha_last) AS day 
FROM users
WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_registro > '".$margen_30dias."'
GROUP BY day
ORDER BY fecha_last DESC", $link);
	while($r = mysql_fetch_array($result)) { 
		$txt .= '<tr><td align="right">'.$dias++.'</td><td align="right">'.$r['day'].'</td><td align="right"><b>'.$r['num'].'</b></td></tr>'; 
	}
	$txt .= '</table>


</td><td>&nbsp;&nbsp;&nbsp;</td><td valign="top"><h2>'._('Total').'</h2>


<table border="0">
<tr>
<th>#</th>
<th>'._('Día').'</th>
<th>'._('Ciudadanos').'</th>
</tr>';

	$dias = 1;
	$result = mysql_query("SELECT fecha_last, COUNT(*) AS num, DAY(fecha_last) AS day 
FROM users
WHERE estado = 'ciudadano' AND pais = '".PAIS."'
GROUP BY day
ORDER BY fecha_last DESC", $link);
	while($r = mysql_fetch_array($result)) { 
		$txt .= '<tr><td align="right">'.$dias++.'</td><td align="right">'.$r['day'].'</td><td align="right"><b>'.$r['num'].'</b></td></tr>'; 
	}
	$txt .= '</table>
</td></tr></table>';

	break;


case 'voz':
	$txt .= '
<p>'._('El chat de voz de VirtualPol funciona mediante un programa externo llamado Mumble. Es un programa de escritorio gratuito, fácil de instalar, compatible con todos los sistemas, software libre y con encriptación de las comunicaciones. Es la mejor formula disponible para proveer a VirtualPol de una opcion de comunicación por voz').'.</p>

<div style="float:left;margin:20px 40px 150px 0;">
<script type="text/javascript" src="http://view.light-speed.com/mumble.php?url=https%3A//api.mumble.com/mumble/cvp.php%3Ftoken%3DLSG-8D-383B3DEB&c=055b75&r=6&h=292&w=202&css=https%3A//view.light-speed.com/styles/mumble-minimal.css"></script>
</div>

<p><b>'._('¿Como usar el chat de voz?').'</b></p>

<ol>
<li>'._('<b>Instala Mumble</b> en tu ordenador').':
	<ul>
		<li>Windows (<a href="http://download.mumble.com/en/mumble-1.2.3a.msi">'._('Descargar').'</a>)</li>
		<li>OSX (<a href="http://sourceforge.net/projects/mumble/files%2FMumble%2F1.2.3%2FMumble-1.2.3.dmg/download">'._('Descargar').'</a>)</li>
		<li>GNU/Linux (<a href="http://sourceforge.net/projects/mumble/files%2FMumble%2F1.2.3%2Fmurmur-static_x86-1.2.3.tar.bz2/download">'._('Descargar').'</a>) <span style="color:grey;">'._('Nota: puede haber problemas para que el navegador ejecute el programa').', <a href="http://mumble.sourceforge.net/Mumble_URL#URL_Handler_Installation">'._('info aquí').'</a>.</span></li>
		<li><a href="http://mumble.sourceforge.net/">'._('Ver todas las descargas').'</a></li>
	</ul>
	</li>

<li>'._('Conecta unos <b>auriculares con micrófono</b> (es lo más comodo, para que no se acople el sonido)').'.<br /><br /></li>

<li><b>¡<a href="'.mumble_url().'" style="font-size:18px;">'._('Entra aquí').'</a>!</b></li>

</ol>

<p>Servidor: <b>virtualpol.mumble.com</b><br />
Puerto: <b>3704</b><br />
Contraseña: <b>'.(nucleo_acceso('ciudadanos_global')?'vp':'***').'</b></p>
';


	$txt_title = _('Chat de voz');
	$txt_nav = array(_('Chat de voz'));
	break;


case 'foto':

	$txt .= '<h1>'._('Instantánea de').' VirtualPol</h1><br />';
	$result = mysql_query("SELECT ID, nick, pais
FROM users
WHERE estado = 'ciudadano' AND avatar = 'true'
ORDER BY online DESC
LIMIT 300", $link);
	while($r = mysql_fetch_array($result)) { 
		$txt .= '<img src="'.IMG.'a/'.$r['ID'].'.jpg" alt="'.$r['nick'].'" title="'.$r['nick'].'" />'; 
	}

	break;

case 'censo':

	if (!$pol['user_ID']) { redirect('/'); }

	$num_element_pag = $pol['config']['info_censo'];

	// num ciudadanos activos (los que entraron en las ultimas 24h sin ser nuevos ciudadanos)
	$margen_24h = date('Y-m-d H:i:s', time() - 86400);	// 24 h
	$result = mysql_fetch_row(mysql_query("SELECT COUNT(ID) FROM users WHERE estado != 'expulsado' AND estado != 'validar' AND fecha_last > '".$margen_24h."' AND fecha_registro < '".$margen_24h."'", $link));
	$censo_activos_vp = $result[0];
	$result = mysql_fetch_row(mysql_query("SELECT COUNT(ID) FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_last > '".$margen_24h."' AND fecha_registro < '".$margen_24h."'", $link));
	$censo_activos = $result[0];


	// num expulsados
	$result = mysql_fetch_row(mysql_query("SELECT COUNT(ID) FROM users WHERE estado = 'expulsado'", $link));
	$censo_expulsados = $result[0];

	if ((!is_numeric($_GET['c'])) AND ($_GET['b'] == 'busqueda')) {
		$pagina = $_GET['d'];
		$pagina_url = '/info/censo/busqueda/' . $_GET['c'] . '/';
	} elseif (($_GET['b']) AND (!is_numeric($_GET['b']))) { 
		$pagina = $_GET['c'];
		$pagina_url = '/info/censo/' . $_GET['b'] . '/';
	} else { 
		$pagina = $_GET['b']; 
		$pagina_url = '/info/censo/';
	}
	
	if ($_GET['b'] == 'turistas') {
		$num_element_pag = $censo_turistas;
	}
	elseif ($_GET['b'] == 'expulsados') {
		$num_element_pag = $censo_expulsados;
	}

	paginacion('censo', $pagina_url, null, $pagina, $num_element_pag, 150);

	if ($_GET['b'] == 'nuevos') {
		$old = 'antiguedad';
	} else {
		$old = 'nuevos';
	}

	if ($_GET['b'] == 'busqueda') {
		$busqueda = $_GET['c'];
	} else {
		$busqueda = '';
	}

$txt .= '
<div style="float:right;">
<input name="qcmq" size="14" value="'.$busqueda.'" type="text" id="cmq" />
<button onclick="var cmq = $(\'#cmq\').attr(\'value\'); window.location.href=\'/info/censo/busqueda/\'+cmq+\'/\'; return false;" class="small">'._('Buscar ciudadano').'</button>
</div>

<p>'.$p_paginas.'</p>

<p><abbr title="Numero de ciudadanos en la plataforma '.PAIS.'"><b>'.num($pol['config']['info_censo']).'</b> '._('ciudadanos de').' '.PAIS.'</abbr> (<abbr title="Ciudadanos -no nuevos- que entraron en las últimas 24h, en la plataforma '.PAIS.'">'._('activos').' <b>'.$censo_activos.'</b></abbr>,  <abbr title="Ciudadanos activos en todo VirtualPol">'._('activos global').' <b>'.$censo_activos_vp.'</b></abbr>)

'.(ECONOMIA?' | <a href="/control/expulsiones" class="expulsado">'._('Expulsados').'</a>: <b>'.$censo_expulsados.'</b> | <a href="/info/censo/riqueza" title="Los ciudadanos con más monedas">'._('Ricos').'</a>':'').' | <a href="/info/censo/SC" title="Todos los ciudadanos registrados en VirtualPol globalmente">'._('Censo de').' VirtualPol</a> &nbsp; 
</p>

<table border="0" cellspacing="2" cellpadding="0">
<tr>
<th></th>
'.(ASAMBLEA?'':'<th style="font-size:18px;"><a href="/info/censo/nivel">'._('Nivel').'</a></th>').'
<th></th>
<th style="font-size:18px;"><a href="/info/censo/nombre">'._('Nick').'</a></th>
<th style="font-size:18px;" colspan="2"><a href="/info/censo/confianza">'._('Confianza').'</a></th>
'.(ASAMBLEA?'':'<th style="font-size:18px;"><a href="/info/censo/afiliacion">Afil</a></th>').'
<th style="font-size:18px;"><a href="/info/censo/online">Online</a></th>
<th style="font-size:18px;"><a href="/info/censo/'.$old.'">'._('Antigüedad').'</a></th>
<th style="font-size:18px;"><a href="/info/censo">'._('Último').'&nbsp;'._('acceso').'&darr;</a></th>
<th style="font-size:18px;"><a href="/info/censo/perfiles">'._('Perfiles').'</a></th>
</tr>';

	switch ($_GET['b']) {
		case 'busqueda': $order_by = 'WHERE (text LIKE \'%'.$_GET['c'].'%\' OR nombre LIKE \'%'.$_GET['c'].'%\' OR nick LIKE \'%'.$_GET['c'].'%\' OR datos LIKE \'%'.$_GET['c'].'%\') ORDER BY fecha_last DESC'; break;
		case 'nivel': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' AND ID != \'1\' ORDER BY nivel DESC'; break;
		case 'nombre': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY nick ASC'; break;
		case 'nuevos': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY fecha_registro DESC'; break;
		case 'antiguedad': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY fecha_registro ASC'; break;
		case 'elec': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY num_elec DESC, fecha_registro ASC'; break;
		case 'online': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY online DESC'; break;
		case 'riqueza': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY pols DESC, fecha_registro ASC'; break;
		case 'afiliacion': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY partido_afiliado DESC, fecha_registro ASC'; break;
		case 'confianza': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY voto_confianza DESC, fecha_registro ASC'; break;
		case 'expulsados': $order_by = 'WHERE estado = \'expulsado\' ORDER BY fecha_last DESC'; $num_element_pag = $censo_expulsados; break;
		case 'turistas': $order_by = 'WHERE estado = \'turista\' ORDER BY fecha_registro DESC'; $num_element_pag = $censo_turistas; break;
		case 'perfiles': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' AND datos != \'\' AND datos != \'][][][][][\' ORDER BY fecha_registro ASC'; break;
		case 'SC': $order_by = "WHERE estado != 'expulsado' ORDER BY voto_confianza DESC, fecha_registro ASC"; break;

		default: $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY fecha_last DESC';
	}

	if ($p_init) { $orden = $p_init + 1; } else { $orden = 1; }

	if ($pol['estado']) { $sql_extra = ", (SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '" . $pol['user_ID'] . "' AND item_ID = users.ID LIMIT 1) AS has_votado"; }


	$sc = get_supervisores_del_censo();

	$result = mysql_query("SELECT ID, ID AS user_ID, nick, nombre, estado, pais, nivel, online, ref, ref_num, num_elec, voto_confianza, fecha_registro, nota, fecha_last, cargo, avatar, datos,
(SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND users.partido_afiliado != '0' AND ID = users.partido_afiliado LIMIT 1) AS siglas".$sql_extra."
FROM users ".$order_by." LIMIT ".mysql_real_escape_string($p_limit), $link);
	while($r = mysql_fetch_array($result)){
		if ($r['online'] != 0) { $online = duracion($r['online']); } else { $online = ''; }
		if ($r['avatar'] == 'true') { $avatar = avatar($r['ID'], 40) . ' '; } else { $avatar = ''; }
		if ($r['siglas']) { $partido = '<a href="/partidos/' . strtolower($r['siglas']) . '/">' . $r['siglas'] . '</a>'; } else { $partido = ''; }
		if ($r['ref_num'] == 0) { $r['ref_num'] = ''; }
		if ($r['num_elec'] == 0) { $r['num_elec'] = ''; }
		if (!$r['has_votado']) { $r['has_votado'] = 0; }

		$txt .= '<tr>
<td align="right" class="gris">' . $orden++ . '</td>
'.(ASAMBLEA?'':'<td align="right">' . $r['nivel'] . '</td>').'
<td height="38">' . $avatar . '</td>
<td nowrap="nowrap">'.(isset($sc[$r['ID']])?'<span style="float:right;color:red;margin-left:5px;" title="'._('Supervisor del Censo').'">'._('SC').'</span>':'').'<img src="'.IMG.'cargos/' . $r['cargo'] . '.gif" width="16" height="16" /> <b>' . crear_link($r['nick'], 'nick', $r['estado']) . '</b>'.(isset($r['nombre'])&&nucleo_acceso('ciudadanos')?'<br /><span style="color:grey;font-size:12px;">'.$r['nombre'].'</span>':'').'</td>
<td align="right" nowrap="nowrap"><span id="confianza'.$r['user_ID'].'">'.confianza($r['voto_confianza']).'</span></td>
<td nowrap="nowrap">'.($pol['user_ID']&&$r['user_ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['user_ID'].'" class="votar" type="confianza" name="'.$r['user_ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>
'.(ASAMBLEA?'':'<td>' . $partido . '</td>').'
<td align="right" nowrap="nowrap">' . $online . '</td>
<td>' . explodear(' ', $r['fecha_registro'], 0) . '</td>
<td align="right" nowrap="nowrap" class="timer" value="'.strtotime($r['fecha_last']).'"></td>

<td nowrap="nowrap">';

		$datos = explode('][', $r['datos']);
		foreach ($datos_perfil AS $id => $dato) {
			if ($datos[$id] != '') {
				$txt .= '<a href="'.$datos[$id].'" target="_blank"><img src="'.IMG.'ico/'.$id.'_32.png" width="32" width="32" alt="'.$datos.'" /></a>';
			}
		}

		$txt .= '</td></tr>' . "\n";

	
	}
	$txt .= '</table><p>' . $p_paginas . '</p>';
	
	$txt_title = _('Censo de ciudadanos');
	$txt_nav = array('/info/censo'=>_('Censo'));
	$txt_tab = array('/geolocalizacion'=>_('Mapa de ciudadanos'), '/info/censo/SC/'=>_('Censo VirtualPol'));
	break;


case 'estadisticas':
	$txt .= '<a href="/estadisticas/"><b>'._('Nuevas estadísticas').'...</b></a>';
	break;

case 'economia':
	$txt .= '<h1 class="quitar">'.MONEDA.' '._('Economía Global').':</h1>';
	$txt_title = _('Economia Global');
	$txt_nav = array('/info/economia'=>_('Economía global'));
	$txt_menu = 'econ';

	// Obtiene colores de background de paises
	$result = sql("SELECT valor, pais FROM config WHERE dato = 'bg_color'");
	while ($r = r($result)) { $vp['bg'][$r['pais']] = $r['valor']; }


// #CUADRAR
// 11 AGOSTO 2010: 544.645 | 554.528 | 674.518
// 28 AGOSTO 2011: 883.003

//$moneda_mundial = '883003';
$moneda_mundial = '100000';


$txt .= '<br /><table border="0" cellspacing="0" cellpadding="2">
<tr>
<th colspan="3" style="background:#B2FF99;" align="center">'._('Información').'</th>
<th colspan="4" style="background:#FFB266;" align="center">'._('Gobierno').'</th>
<th colspan="2" style="background:#99B2FF;" align="center">'._('Promedios').'</th>
<th colspan="5" style="background:#FFFF99;" align="center">'._('Contabilidad').'</th>
</tr>

<tr>
<th style="background:#B2FF99;">'._('País').'</th>
<th style="background:#B2FF99;"><acronym title="Numero de ciudadanos.">'._('Población').'</acronym></th>
<th style="background:#B2FF99;"><acronym title="Total de deudas personales, dinero en negativo.">'._('Deuda').'</acronym></th>

<th style="background:#FFB266;">'._('Arancel').'</th>
<th style="background:#FFB266;" colspan="2">'._('Impuestos').'</th>
<th style="background:#FFB266;"><acronym title="Pago por dia de actividad">'._('Subsidio').'</acronym></th>

<th style="background:#99B2FF;"><acronym title="Salario medio">'._('Salario').'</acronym></th>
<th style="background:#99B2FF;"><acronym title="Patrimonio medio por ciudadano.">'._('Patrimonio').'</acronym></th>


<th style="background:#FFFF99;" colspan="2">'._('Personal').'</th>
<th style="background:#FFFF99;" colspan="2">'._('Gobierno').'</th>
<th style="background:#FFFF99;">'._('Total').' '.MONEDA.'</th>

</tr>';

$result0 = mysql_query("SELECT pais FROM config WHERE dato = 'ECONOMIA' AND valor = 'true'");
while($r0 = mysql_fetch_array($result0)) {
		$pais = $r0['pais'];

$result = mysql_query("SELECT SUM(pols + IFNULL((SELECT SUM(pols) FROM cuentas WHERE pais = '".$pais."' AND user_ID = users.ID GROUP BY user_ID),0)) AS pols_ciudadanos,
(SELECT COUNT(ID) FROM users WHERE pais = '".$pais."' AND estado = 'ciudadano') AS num_ciudadanos,
(SELECT SUM(pols) FROM cuentas WHERE pais = '".$pais."' AND nivel > 0) AS pols_gobierno,
(SELECT SUM(pols) FROM users WHERE pais = '".$pais."' AND pols < 0) AS pols_negativo,
(SELECT valor FROM config WHERE pais = '".$pais."' AND dato = 'arancel_salida' LIMIT 1) AS arancel_salida,
(SELECT valor FROM config WHERE pais = '".$pais."' AND dato = 'impuestos' LIMIT 1) AS impuestos,
(SELECT valor FROM config WHERE pais = '".$pais."' AND dato = 'impuestos_minimo' LIMIT 1) AS impuestos_minimo,
(SELECT valor FROM config WHERE pais = '".$pais."' AND dato = 'pols_inem' LIMIT 1) AS inem,
(SELECT AVG(salario) FROM cargos WHERE pais = '".$pais."') AS salario_medio
FROM users
WHERE pais = '".$pais."'");
	while($r = mysql_fetch_array($result)) {


		$result2 = mysql_query("SELECT nick, pais,
(pols + IFNULL((SELECT SUM(pols) FROM cuentas WHERE pais = '".$pais."' AND user_ID = users.ID GROUP BY user_ID),0)) AS pols_total
FROM users
WHERE pais = '".$pais."'
ORDER BY pols_total DESC 
LIMIT 25", $link);
		while ($r2 = mysql_fetch_array($result2)) {
			$ricos[$r2['nick'].':'.$r2['pais']] = $r2['pols_total'];
		}



		$total += $r['pols_ciudadanos'] + $r['pols_gobierno'];

		$total_pais[$pais] = $r['pols_ciudadanos']+$r['pols_gobierno'];

		$txt .= '<tr>
<td style="background:'.$vp['bg'][$pais].';"><a href="http://'.strtolower($pais).'.'.DOMAIN.'/"><b>'.$pais.'</b></a></td>
<td align="right"><b>'.$r['num_ciudadanos'].'</b></td>
<td align="right">'.pols($r['pols_negativo']).'</td>

<td align="right" style="color:red;"><b>'.$r['arancel_salida'].'%</b></td>';


if ($r['impuestos'] > 0) {
	$txt .= '<td><b>'.$r['impuestos'].'%</b></td><td align="right">'.pols($r['impuestos_minimo']).'</td>';
} else {
	$txt .= '<td colspan="2">'._('Sin impuestos').'</td>';
}


$txt .= '<td align="right">'.pols($r['inem']).'</td>

<td align="right">'.pols($r['salario_medio']).'</td>
<td align="right">'.($r['num_ciudadanos']>0?pols(round($r['pols_ciudadanos']/$r['num_ciudadanos'])):0).'</td>

<td align="right">'.pols($r['pols_ciudadanos']).'</td>
<td>+</td>
<td align="right">'.pols($r['pols_gobierno']).'</td>
<td>=</td>
<td align="right">'.pols($r['pols_ciudadanos']+$r['pols_gobierno']).'</td>
</tr>';

	}

	// GEN GRAFICO VISITAS
	$n = 0;
	$result = mysql_query("SELECT pols, pols_cuentas FROM stats WHERE pais = '".$pais."' ORDER BY time DESC LIMIT 9", $link);
	while($r = mysql_fetch_array($result)){
		if ($gph[$pais]) { $gph[$pais] = ',' . $gph[$pais]; }
		$gph_maxx[$n] += $r['pols'] + $r['pols_cuentas'];
		$gph[$pais] = $r['pols'] + $r['pols_cuentas'] . $gph[$pais];
		if ($gph_maxx[$n] > $gph_max) { $gph_max = $gph_maxx[$n]; }
		$n++;
	}
}

	$result = mysql_query("SELECT SUM(pols) AS pols_total FROM users WHERE pais = 'ninguno'");
	while($r = mysql_fetch_array($result)) { $pols_turistas = $r['pols_total']; }

	$total_moneda = $total+$pols_turistas;

	if (($total_moneda) == $moneda_mundial) {
		$cuadrar = ' <acronym title="Las cuentas cuadran. No se ha creado ni destruido dinero. No hay bugs." style="color:blue;">'._('OK').'</acronym>';
	} else {
		$cuadrar = ' <acronym title="Las cuentas no cuadran. Se ha creado o destruido dinero desde la ultima revision. Probablemente debido a un bug." style="color:red;">'._('ERROR').'</acronym>: '.pols($total_moneda-$moneda_mundial).' '.MONEDA;
	}


$txt .= '
<tr>
<td colspan="12" align="right">'._('Sin ciudadanía').': '.pols($pols_turistas).'</td>
<td>+</td>
<td style="font-size:18px;" align="right">'.pols($total_moneda).'</td>
<td>'.MONEDA.$cuadrar.'</td>
</tr>


<tr>
<td colspan="3" valign="top">

<h2>'._('Los más ricos').':</h2><ol>';

arsort($ricos);
$extra = '';
foreach ($ricos AS $info => $pols_total) {
	$num++;
	if (($pols_total > 0) AND ($num <= 25)) {
		$nick = explodear(':', $info, 0);
		$pais = explodear(':', $info, 1);
		// $extra = pols($pols_total).' ';
		$txt .= '<li>'.MONEDA.' <b class="big">'.$extra.''.crear_link($nick, 'nick', 'ciudadano', $pais).'</b></li>';
	}
}

$txt .= '</ol>


</td>

<td colspan="6" valign="top">

<h2>'._('Deudores').':</h2><ol>';

$result = mysql_query("SELECT pols, pais, nick FROM users WHERE pols < 0 ORDER BY pols ASC");
while($r = mysql_fetch_array($result)) {
	$txt .= '<li>'.pols($r['pols']).' '.MONEDA.' <b class="big">'.crear_link($r['nick'], 'nick', 'ciudadano', $r['pais']).'</b></li>';
}

$txt .= '</ol>
<span style="color:#888;">'._('No contabiliza el dinero en cuentas bancarias').'.</span>

</td>


<td align="center" colspan="6" valign="top">
<h2>'._('Reparto económico').':</h2><br />
<img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.round(($total_pais['RSSV']*100)/$total_moneda).','.round(($total_pais['Hispania']*100)/$total_moneda).'&chs=300x190&chl=RSSV|Hispania&chco='.substr($vp['bg']['RSSV'],1).','.substr($vp['bg']['Hispania'],1).'" alt="Reparto economico." />

<br /><br />

<h2>'._('Evolución de la economía').':</h2><br />

<img src="http://chart.apis.google.com/chart?cht=lc
&chs=330x350
&cht=bvs
&chco='.substr($vp['bg']['RSSV'],1).','.substr($vp['bg']['Hispania'],1).'
&chd=t:'.$gph['RSSV'].','.$total_pais['RSSV'].'|'.$gph['Hispania'].','.$total_pais['Hispania'].'
&chds=0,'.$moneda_mundial.'
&chxt=r
&chxl=0:||'.round($moneda_mundial / 2).'|'.$moneda_mundial.'
" alt="Monedas" />

</td>

</tr>

<tr>
<td align="center" colspan="15">('._('zona común entre países').')</td>
</tr>
</table>';


	break;

}



//THEME
if (!isset($txt_menu)) { $txt_menu = 'info'; }
include('theme.php');
?>