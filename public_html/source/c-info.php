<?php 
include('inc-login.php');

switch ($_GET['a']) {



case 'seguir':


function red_social($red, $ID) {

	if ($red == 'twitter') {
		return '<a href="https://twitter.com/'.$ID.'" class="twitter-follow-button" data-show-count="false" data-lang="es" data-size="large">Segui @'.$ID.'</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
	}

}

$txt_title = 'Sala de seguir';
$txt_nav = array('Seguir');

$txt .= '<h1 class="quitar">Sala de Seguir</h1>
<br />
<table border="0">';


$txt .= '<tr>
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
<th>Ciudadano</th>
<th>Confianza</th>
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

	$txt .= '<h1 class="quitar">Expiracion:</h1><br />

<table><tr><td valign="top"><h2>30 dias</h2>


<table border="0">
<tr>
<th>#</th>
<th>Dia</th>
<th>Ciudadanos</th>
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


</td><td>&nbsp;&nbsp;&nbsp;</td><td valign="top"><h2>Total</h2>


<table border="0">
<tr>
<th>#</th>
<th>Dia</th>
<th>Ciudadanos</th>
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
	$txt .= '<h1 class="quitar">Chat de voz:</h1>
<p>El chat de voz de VirtualPol funciona mediante un programa externo llamado Mumble. Es un programa de escritorio gratuito, f&aacute;cil de instalar, compatible con todos los sistemas, software libre y con encriptaci&oacute;n de las comunicaciones. Es la mejor formula disponible para proveer a VirtualPol de una opcion de comunicaci&oacute;n por voz.</p>

<p><b>&iquest;Como usar el chat de Voz?</b></p>

<ol>
<li><b>Instala Mumble</b> en tu ordenador:
	<ul>
		<li>Windows (<a href="http://download.mumble.com/en/mumble-1.2.3a.msi">Descargar</a>)</li>
		<li>OSX (<a href="http://sourceforge.net/projects/mumble/files%2FMumble%2F1.2.3%2FMumble-1.2.3.dmg/download">Descargar</a>)</li>
		<li>GNU/Linux (<a href="http://sourceforge.net/projects/mumble/files%2FMumble%2F1.2.3%2Fmurmur-static_x86-1.2.3.tar.bz2/download">Descargar</a>) <span style="color:grey;">Nota: puede haber problemas para que el navegador ejecute el programa, <a href="http://mumble.sourceforge.net/Mumble_URL#URL_Handler_Installation">info aqu&iacute;</a>.</span></li>
		<li><a href="http://mumble.sourceforge.net/">Ver todas las descargas</a></li>
	</ul><br />
	</li>

<li>Conecta unos <b>auriculares con micr&oacute;fono</b> (es lo m&aacute;s comodo, para que no se acople el sonido).<br /><br /></li>

<li><b>¡<a href="mumble://'.$pol['nick'].'@mumble.democraciarealya.es/Virtualpol/'.PAIS.'/?version=1.2.0">Entra aqu&iacute;</a>!</b> (o desde el men&uacute; "Voz") (<a href="mumble://'.$pol['nick'].'@democraciarealya.es/Virtualpol/?version=1.2.0">servidor alternativo</a>)</li>

</ol>

<p><br />El servidor de Mumble es "mumble.democraciarealya.es". De uso compartido con otras plataformas.</p>';


	$txt_title = 'Chat de Voz';
	$txt_nav = array('Chat de voz');
	break;


case 'foto':

	$txt .= '<h1>Instantanea de VirtualPol</h1><br />';
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

	paginacion('censo', $pagina_url, null, $pagina, $num_element_pag, 250);

	if ($_GET['b'] == 'nuevos') {
		$old = 'antiguedad';
	} else {
		$old = 'nuevos';
	}


	if ($_GET['b']) {
		$txt .= '<h1 class="quitar"><a href="/info/censo">Censo</a>: ' . ucfirst($_GET['b']) . '</h1>';
	} else {
		$txt .= '<h1 class="quitar">Censo:</h1>';
	}

	if ($_GET['b'] == 'busqueda') {
		$busqueda = $_GET['c'];
	} else {
		$busqueda = '';
	}

$txt .= '
<div style="float:right;">
<input name="qcmq" size="10" value="'.$busqueda.'" type="text" id="cmq">
<input value="Buscador de perfiles" type="submit" onclick="var cmq = $(\'#cmq\').attr(\'value\'); window.location.href=\'/info/censo/busqueda/\'+cmq+\'/\'; return false;">
</div>

<p>'.$p_paginas.'</p>

<p><abbr title="Numero de ciudadanos en la plataforma '.PAIS.'"><b>'.num($pol['config']['info_censo']).'</b> ciudadanos de '.PAIS.'</abbr> (<abbr title="Ciudadanos -no nuevos- que entraron en las últimas 24h, en la plataforma '.PAIS.'">activos <b>'.$censo_activos.'</b></abbr>,  <abbr title="Ciudadanos activos en todo VirtualPol">activos global <b>'.$censo_activos_vp.'</b></abbr>)

'.(ECONOMIA?' | <a href="/control/expulsiones" class="expulsado">Expulsados</a>: <b>'.$censo_expulsados.'</b> | <a href="/info/censo/riqueza" title="Los ciudadanos con m&aacute;s monedas.">Ricos</a>':'').' | <a href="/info/censo/SC" title="Todos los ciudadanos registrados en VirtualPol globalmente">Censo de VirtualPol</a> &nbsp; 
</p>

<table border="0" cellspacing="2" cellpadding="0" class="pol_table">
<tr>
<th></th>
'.(ASAMBLEA?'':'<th style="font-size:18px;"><a href="/info/censo/nivel">Nivel</a></th>').'
<th></th>
<th style="font-size:18px;"><a href="/info/censo/nombre">Nick</a></th>
<th style="font-size:18px;" colspan="2"><a href="/info/censo/confianza">Confianza</a></th>
'.(ASAMBLEA?'':'<th style="font-size:18px;"><a href="/info/censo/afiliacion">Afil</a></th>').'
<th style="font-size:18px;"><a href="/info/censo/online">Online</a></th>
<th style="font-size:18px;"><a href="/info/censo/'.$old.'">Antigüedad</a></th>
<!--<th style="font-size:18px;"><a href="/info/censo/elec"><abbr title="Elecciones en las que ha participado">Elec</abbr></a></th>-->
<th style="font-size:18px;"><a href="/info/censo">Último&nbsp;acceso&darr;</a></th>
<th style="font-size:18px;"><a href="/info/censo/perfiles">Perfiles</a></th>
</tr>';

	switch ($_GET['b']) {
		case 'busqueda': $order_by = 'WHERE (text LIKE \'%'.$_GET['c'].'%\' OR nick LIKE \'%'.$_GET['c'].'%\' OR datos LIKE \'%'.$_GET['c'].'%\') AND pais = \''.PAIS.'\' ORDER BY fecha_last DESC'; break;
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

	$result = mysql_query("SELECT ID, ID AS user_ID, nick, estado, pais, nivel, online, ref, ref_num, num_elec, voto_confianza, fecha_registro, nota, fecha_last, cargo, avatar, datos,
(SELECT siglas FROM ".SQL."partidos WHERE users.partido_afiliado != '0' AND ID = users.partido_afiliado LIMIT 1) AS siglas".$sql_extra."
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
<td nowrap="nowrap">'.(isset($sc[$r['ID']])?'<span style="float:right;color:red;margin-left:5px;" title="Supervisor del Censo">SC</span>':'').'<img src="'.IMG.'cargos/' . $r['cargo'] . '.gif" width="16" height="16" /> <b>' . crear_link($r['nick'], 'nick', $r['estado']) . '</b></td>
<td align="right" nowrap="nowrap"><span id="confianza'.$r['user_ID'].'">'.confianza($r['voto_confianza']).'</span></td>
<td nowrap="nowrap">'.($pol['user_ID']&&$r['user_ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['user_ID'].'" class="votar" type="confianza" name="'.$r['user_ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>
'.(ASAMBLEA?'':'<td>' . $partido . '</td>').'
<td align="right" nowrap="nowrap">' . $online . '</td>
<td>' . explodear(' ', $r['fecha_registro'], 0) . '</td>
<!--<td align="right">' . $r['num_elec'] . '</td>-->
<td align="right" nowrap="nowrap" class="timer" value="'.strtotime($r['fecha_last']).'"></td>

<td nowrap="nowrap">';

		$datos = explode('][', $r['datos']);
		foreach ($datos_perfil AS $id => $dato) {
			if ($datos[$id] != '') {
				$txt .= '<a href="'.$datos[$id].'" target="_blank"><img src="'.IMG.'ico/'.$id.'_32.png" width="32" width="32" alt="'.$datos.'" /></a>';
			}
		}

		$txt .= '</td>
</tr>' . "\n";

	
	}
	$txt .= '</table><p>' . $p_paginas . '</p>';
	
	$txt_title = 'Censo de Ciudadanos';
	$txt_nav = array('/info/censo'=>'Censo');
	$txt_tab = array('/info/censo/SC/'=>'Censo VirtualPol');
	break;


case 'estadisticas':
	$txt .= '<a href="/estadisticas/"><b>Nuevas estadisticas...</b></a>';
	break;

case 'economia':
	$txt .= '<h1 class="quitar">'.MONEDA.' Econom&iacute;a Global:</h1>';
	$txt_title = 'Economia Global';
	$txt_nav = array('/info/economia'=>'Economía global');
	$txt_menu = 'econ';



// #CUADRAR
// 11 AGOSTO 2010: 544.645 | 554.528 | 674.518
// 28 AGOSTO 2011: 883.003

$moneda_mundial = '883003';


$txt .= '<br /><table border="0" cellspacing="0" cellpadding="2">
<tr>
<th colspan="3" style="background:#B2FF99;" align="center">Informaci&oacute;n</th>
<th colspan="4" style="background:#FFB266;" align="center">Gobierno</th>
<th colspan="2" style="background:#99B2FF;" align="center">Promedios</th>
<th colspan="5" style="background:#FFFF99;" align="center">Contabilidad</th>
</tr>

<tr>
<th style="background:#B2FF99;">Pa&iacute;s</th>
<th style="background:#B2FF99;"><acronym title="Numero de ciudadanos.">Pob</acronym></th>
<th style="background:#B2FF99;"><acronym title="Total de deudas personales, dinero en negativo.">Negativo</acronym></th>

<th style="background:#FFB266;">Arancel</th>
<th style="background:#FFB266;" colspan="2">Impuestos</th>
<th style="background:#FFB266;"><acronym title="Pago por dia de actividad">Inem</acronym></th>

<th style="background:#99B2FF;"><acronym title="Salario medio">Salario</acronym></th>
<th style="background:#99B2FF;"><acronym title="Patrimonio medio por ciudadano.">Patrimonio</acronym></th>


<th style="background:#FFFF99;" colspan="2">Personal</th>
<th style="background:#FFFF99;" colspan="2">Gobierno</th>
<th style="background:#FFFF99;">Total '.MONEDA.'</th>

</tr>';

foreach ($vp['paises_economia'] AS $pais) {

$result = mysql_query("SELECT SUM(pols + IFNULL((SELECT SUM(pols) FROM ".strtolower($pais)."_cuentas WHERE user_ID = users.ID GROUP BY user_ID),0)) AS pols_ciudadanos,
(SELECT COUNT(ID) FROM users WHERE pais = '".$pais."' AND estado = 'ciudadano') AS num_ciudadanos,
(SELECT SUM(pols) FROM ".strtolower($pais)."_cuentas WHERE nivel > 0) AS pols_gobierno,
(SELECT SUM(pols) FROM users WHERE pais = '".$pais."' AND pols < 0) AS pols_negativo,
(SELECT valor FROM ".strtolower($pais)."_config WHERE dato = 'arancel_salida' LIMIT 1) AS arancel_salida,
(SELECT valor FROM ".strtolower($pais)."_config WHERE dato = 'impuestos' LIMIT 1) AS impuestos,
(SELECT valor FROM ".strtolower($pais)."_config WHERE dato = 'impuestos_minimo' LIMIT 1) AS impuestos_minimo,
(SELECT valor FROM ".strtolower($pais)."_config WHERE dato = 'pols_inem' LIMIT 1) AS inem,
(SELECT AVG(salario) FROM ".strtolower($pais)."_estudios) AS salario_medio
FROM users
WHERE pais = '".$pais."'");
	while($r = mysql_fetch_array($result)) {


		$result2 = mysql_query("SELECT nick, pais,
(pols + IFNULL((SELECT SUM(pols) FROM ".strtolower($pais)."_cuentas WHERE user_ID = users.ID GROUP BY user_ID),0)) AS pols_total
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
	$txt .= '<td colspan="2">Sin impuestos</td>';
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
		if ($gph_maxx[$n] > $gph_max) {
			$gph_max = $gph_maxx[$n];
		}
		$n++;
	}

}


	$result = mysql_query("SELECT SUM(pols) AS pols_total FROM users WHERE pais = 'ninguno'");
	while($r = mysql_fetch_array($result)) {
		$pols_turistas = $r['pols_total'];
	}

	$total_moneda = $total+$pols_turistas;


	if (($total_moneda) == $moneda_mundial) {
		$cuadrar = ' <acronym title="Las cuentas cuadran. No se ha creado ni destruido dinero. No hay bugs." style="color:blue;">OK</acronym>';
	} else {
		$cuadrar = ' <acronym title="Las cuentas no cuadran. Se ha creado o destruido dinero desde la ultima revision. Probablemente debido a un bug." style="color:red;">ERROR</acronym>: '.pols($total_moneda-$moneda_mundial).' '.MONEDA;
	}


$txt .= '
<tr>
<td colspan="12" align="right">Sin ciudadan&iacute;a: '.pols($pols_turistas).'</td>
<td>+</td>
<td style="font-size:18px;" align="right">'.pols($total_moneda).'</td>
<td>'.MONEDA.$cuadrar.'</td>
</tr>


<tr>
<td colspan="3" valign="top">

<h2>Los m&aacute;s ricos:</h2><ol>';

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

<h2>Deudores:</h2><ol>';

$result = mysql_query("SELECT pols, pais, nick FROM users WHERE pols < 0 ORDER BY pols ASC");
while($r = mysql_fetch_array($result)) {
	$txt .= '<li>'.pols($r['pols']).' '.MONEDA.' <b class="big">'.crear_link($r['nick'], 'nick', 'ciudadano', $r['pais']).'</b></li>';
}

$txt .= '</ol>
<span style="color:#888;">No contabiliza el dinero en cuentas bancarias.</span>

</td>


<td align="center" colspan="6" valign="top">
<h2>Reparto econ&oacute;mico:</h2><br />
<img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.round(($total_pais['RSSV']*100)/$total_moneda).','.round(($total_pais['Hispania']*100)/$total_moneda).'&chs=300x190&chl=RSSV|Hispania&chco='.substr($vp['bg']['RSSV'],1).','.substr($vp['bg']['Hispania'],1).'" alt="Reparto economico." />

<br /><br />

<h2>Evoluci&oacute;n de la econom&iacute;a:</h2><br />

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
<td align="center" colspan="15">(zona com&uacute;n entre paises)</td>
</tr>
</table>';


	break;





}



//THEME
if (!isset($txt_menu)) { $txt_menu = 'info'; }
include('theme.php');
?>