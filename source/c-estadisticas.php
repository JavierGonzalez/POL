<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

function gen_grafico($datos, $fecha='', $cero=false) {
	$maxValue = max($datos);
	$dato_last = $datos[count($datos)-1];
	 

	if ($cero) { $datos = strtr(chart_data($datos), 'A', '_'); } else { $datos = chart_data($datos); }
	return '//chart.googleapis.com/chart?cht=lc&chs=800x120&chxt=y,r&chxl=0:|_____|'.$maxValue.'|1:|_____|'.$dato_last.'&chd=s:'.$datos.'&chf=bg,s,ffffff01|c,s,ffffff01&chco=0066FF&chm=B,FFFFFF,0,0,0';
}

function gen_datos($datos, $cero=false, $datos2=false) {
	$maxValue = max($datos);
	$dato_last = $datos[count($datos)-1];
	if ($cero) { $datos = strtr(chart_data($datos), 'A', '_'); } else { $datos = chart_data($datos); }

	if ($datos2) {
		$maxValue2 = max($datos2);
		if ($maxValue2 > $maxValue) { $maxValue = $maxValue2; }
		if ($cero) { $datos = $datos.','.strtr(chart_data($datos2), 'A', '_'); } else { $datos = $datos.','.chart_data($datos2); }
	}

	return '&chxt=y,r&chxl=0:|_____|'.$maxValue.'|1:|_____|'.$dato_last.'&chd=s:'.$datos;
}

$txt_title = _('Estadísticas');
$txt_nav = array(_('Estadísticas'));





if ($_GET['a'] == 'macro') {

$txt .= '

<p>Sala de situación de VirtualPol. Estadísticas globales (macro).</p>

<fieldset><legend>Plataformas</legend>

<table>
<tr>
<th colspan="2"></th>
<th colspan="5" style="border-left:1px dotted #999;">Ciudadanos</th>

<th colspan="3" style="border-left:1px dotted #999;">Actividad</th>

<th colspan="5" style="border-left:1px dotted #999;">Votaciones</th>



<th colspan="3" style="border-left:1px dotted #999;"></th>
</tr>

<tr>
<th>Tipo</th>
<th>Plataforma</th>

<th title="Ciudadanos en total">Total</th>
<th title="Balance de ciudadanos desde las 20:00 hasta ahora">Hoy</th>
<th title="Balance de ciudadanos en el último tramo de 24h">1d</th>
<th title="Balance de ciudadanos en el último tramo de 7 días">7d</th>
<th title="Balance de ciudadanos en el último tramo de 30 días">30d</th>

<th title="Ciudadanos -no nuevos- que entraron en las últimas 24h">1d</th>
<th title="Ciudadanos -no nuevos- que entraron en los últimos 7 días">7d</th>
<th title="Ciudadanos -no nuevos- que entraron en los últimos 30 días">30d</th>

<th title="Numero de votos de la mayor votación de los últimos 30 días">Votantes</th>
<th title="Numero de votos emitidos en los últimos 30 días">Votos</th>
<th title="Votaciones En Curso">Activas</th>
<th>Votaciones</th>
<th>Votos total</th>

<th title="Dias de existencia">Días</th>
<th title="Ciudadanos geolocalizados">Geo.</th>
<th title="Supervisores del Censo">SC</th>
<th title="Kicks (bloqueos por moderación) en los últimos 30 días">Kicks</th>

</tr>';

$result = sql("SELECT pais, valor AS num,
(SELECT COUNT(*) FROM votacion WHERE pais = config.pais AND estado = 'ok') AS v_encurso,
(SELECT MAX(num) FROM votacion WHERE pais = config.pais AND time_expire > '".tiempo(30)."') AS v_votantes,
(SELECT SUM(num) FROM votacion WHERE pais = config.pais AND time_expire > '".tiempo(30)."') AS v_votos,
(SELECT COUNT(*) FROM votacion WHERE pais = config.pais) AS v_votaciones,
(SELECT SUM(num) FROM votacion WHERE pais = config.pais) AS v_votos_total,
(SELECT COUNT(*) FROM users WHERE pais = config.pais AND estado = 'ciudadano' AND fecha_last > '".tiempo(1)."' AND fecha_registro < '".tiempo(1)."') AS a_1d,
(SELECT COUNT(*) FROM users WHERE pais = config.pais AND estado = 'ciudadano' AND fecha_last > '".tiempo(7)."' AND fecha_registro < '".tiempo(7)."') AS a_7d,
(SELECT COUNT(*) FROM users WHERE pais = config.pais AND estado = 'ciudadano' AND fecha_last > '".tiempo(30)."' AND fecha_registro < '".tiempo(30)."') AS a_30d,
(SELECT COUNT(*) FROM users WHERE pais = config.pais AND estado = 'ciudadano' AND x IS NOT NULL) AS geo,
(SELECT COUNT(*) FROM users WHERE pais = config.pais AND SC = 'true') AS SC_num,
(SELECT COUNT(*) FROM kicks WHERE pais = config.pais AND expire > '".tiempo(30)."') AS kicks_num,
(SELECT COUNT(*) FROM stats WHERE pais = config.pais) AS dias_num
FROM config WHERE dato = 'info_censo' ORDER BY ABS(valor) DESC LIMIT 25");
while($r = r($result)) {

	$pais = $r['pais'];
	
	$d = array();
	$result2 = sql("SELECT ciudadanos, 24h FROM stats WHERE pais = '".$pais."' ORDER BY time DESC LIMIT 31");
	while($r2 = r($result2)) { 
		$d['ciudadanos'][]	= $r2['ciudadanos'];
		$d['24h'][]			= $r2['24h'];
	}

	$result2 = sql("SELECT valor, dato FROM config WHERE pais = '".$pais."' AND dato IN ('pais_des', 'tipo', 'bg_color')");
	while($r2 = r($result2)) { $pais_config[$r2['dato']] = $r2['valor']; }

	$txt .= '<tr style="background:'.$pais_config['bg_color'].';">

<td><em style="color:#777;font-size:12px;">'.ucfirst($pais_config['tipo']).'</em></td>

<td title="'.$pais_config['pais_des'].'"><a href="//'.strtolower($pais).'.'.DOMAIN.'"><b style="font-size:18px;">'.$pais.'</b></a></td>

<td align="right"><b>'.num($r['num']).'</b></td>


<td align="right">'.confianza($r['num']-$d['ciudadanos'][0]).'</td>
<td align="right">'.confianza($d['ciudadanos'][0]-$d['ciudadanos'][1]).'</td>
<td align="right">'.confianza($d['ciudadanos'][0]-$d['ciudadanos'][7]).'</td>
<td align="right">'.confianza($d['ciudadanos'][0]-$d['ciudadanos'][30]).'</td>

<td align="right">'.num($r['a_1d']).'</td>
<td align="right"><b>'.num($r['a_7d']).'</b></td>
<td align="right">'.num($r['a_30d']).'</td>

<td align="right">'.num($r['v_votantes']).'</td>
<td align="right">'.num($r['v_votos']).'</td>
<td align="right"><b>'.num($r['v_encurso']).'</b></td>
<td align="right"><b>'.num($r['v_votaciones']).'</b></td>
<td align="right"><b>'.num($r['v_votos_total']).'</b></td>

<td align="right">'.num($r['dias_num']).'</td>
<td align="right">'.num($r['geo']).'</td>
<td align="right">'.num($r['SC_num']).'</td>
<td align="right">'.num($r['kicks_num']).'</td>

</tr>';

	// Totalizadores
	$total['num'] += $r['num'];

	$total['c_hoy'] += $r['num']-$d['ciudadanos'][0];
	$total['c_1d'] += $d['ciudadanos'][0]-$d['ciudadanos'][1];
	$total['c_7d'] += $d['ciudadanos'][0]-$d['ciudadanos'][7];
	$total['c_30d'] += $d['ciudadanos'][0]-$d['ciudadanos'][30];


	$total['a_1d'] += $r['a_1d'];
	$total['a_7d'] += $r['a_7d'];
	$total['a_30d'] += $r['a_30d'];


	$total['v_votantes'] += $r['v_votantes'];
	$total['v_votos'] += $r['v_votos'];
	$total['v_encurso'] += $r['v_encurso'];
	$total['v_votaciones'] += $r['v_votaciones'];
	$total['v_votos_total'] += $r['v_votos_total'];

	$total['geo'] += $r['geo'];
	$total['SC_num'] += $r['SC_num'];
	$total['kicks_num'] += $r['kicks_num'];

}

$txt .= '
<tr>
<td></td>
<td align="right"><b>VirtualPol</b></td>
<td align="right"><b>'.num($total['num']).'</b></td>

<td align="right">'.confianza($total['c_hoy']).'</td>
<td align="right">'.confianza($total['c_1d']).'</td>
<td align="right">'.confianza($total['c_7d']).'</td>
<td align="right">'.confianza($total['c_30d']).'</td>

<td align="right">'.num($total['a_1d']).'</td>
<td align="right"><b>'.num($total['a_7d']).'</b></td>
<td align="right">'.num($total['a_30d']).'</td>

<td align="right">'.num($total['v_votantes']).'</td>
<td align="right">'.num($total['v_votos']).'</td>
<td align="right"><b>'.num($total['v_encurso']).'</b></td>
<td align="right"><b>'.num($total['v_votaciones']).'</b></td>
<td align="right"><b>'.num($total['v_votos_total']).'</b></td>

<td align="right"></td>
<td align="right">'.num($total['geo']).'</td>
<td align="right">'.num($total['SC_num']).'</td>
<td align="right">'.num($total['kicks_num']).'</td>

<td align="right"></td>
</tr>

</table>

</fieldset>';

$txt_title = 'Macro';
$txt_nav[] = 'Macro';



} elseif ($_GET['a'] == 'full-tab') {

	header('Content-Type: text/plain');

	echo 'Dia (20:00)	Pais	Ciudadanos	Ciudadanos nuevos	Ciudadanos eliminados	Ciudadanos que entraron en 24h, sin ser nuevos	Hilos en el foro	Confianza	Partidos	Empresas	Pols	Pols Gobierno	Pols cuentas	Pols frase+palabras	Numero de transacciones	Porcentaje del mapa en venta	Pols de la propiedad mas barata en venta'."\n";

	$result = mysql_query("SELECT * FROM stats ORDER BY time DESC, pais ASC LIMIT 20000", $link);
	while($r = mysql_fetch_array($result)) {
		echo explodear(' ', $r['time'], 0).'	'.$r['pais'].'	'.$r['ciudadanos'].'	'.$r['nuevos'].'	'.$r['eliminados'].'	'.$r['24h'].'	'.$r['hilos_msg'].'	'.$r['confianza'].'	'.$r['partidos'].'	'.$r['empresas'].'	'.$r['pols'].'	'.$r['pols_gobierno'].'	'.$r['pols_cuentas'].'	'.$r['frase'].'	'.$r['transacciones'].'	'.$r['mapa'].'	'.$r['mapa_vende']."\n";
	}
	exit;

} else if ($_GET['a'] == 'full') {
	$txt_tab = array('/estadisticas'=>'VirtualPol');

$txt .= '
<table width="100%">
<tr>
<th>Dia (20:00)</th>
<th>Pais</th>
<th title="Ciudadanos">C</th>
<th title="Ciudadanos nuevos">CN</th>
<th title="Ciudadanos eliminados">CE</th>
<th title="Ciudadanos que entraron en 24h, sin ser nuevos">24h</th>
<th title="Hilos en el foro">Hilos</th>
<th title="Confianza">Conf</th>
<th title="Partidos">Par</th>
<th title="Empresas">Emp</th>
<th title="Pols">Pols</th>
<th title="Pols Gobierno">Pols_G</th>
<th title="Pols cuentas">Pols_C</th>
<th title="Pols frase+palabras">PF</th>
<th title="Numero de transacciones">T</th>
<th title="Porcentaje del mapa en venta">MV%</th>
<th title="Pols de la propiedad mas barata en venta">MV</th>
</tr>
';
$result = mysql_query("SELECT * FROM stats WHERE pais = '".PAIS."' ORDER BY time DESC, pais ASC LIMIT 20000", $link);
while($r = mysql_fetch_array($result)) {


$txt .= '<tr>
<td>'.explodear(' ', $r['time'], 0).'</td>
<td>'.$r['pais'].'</td>

<td align="right">'.$r['ciudadanos'].'</td>
<td align="right">'.$r['nuevos'].'</td>
<td align="right">'.$r['eliminados'].'</td>

<td align="right">'.$r['24h'].'</td>
<td align="right">'.$r['hilos_msg'].'</td>
<td align="right">'.$r['confianza'].'</td>
<td align="right">'.$r['partidos'].'</td>
<td align="right">'.$r['empresas'].'</td>

<td align="right">'.$r['pols'].'</td>
<td align="right">'.$r['pols_gobierno'].'</td>
<td align="right">'.$r['pols_cuentas'].'</td>
<td align="right">'.$r['frase'].'</td>
<td align="right">'.$r['transacciones'].'</td>
<td align="right">'.$r['mapa'].'</td>
<td align="right">'.$r['mapa_vende'].'</td>

</tr>'."\n";

}
$txt .= '</table>';


} else {
	$txt_tab = array('/estadisticas'=>'VirtualPol');

$i = 0;
$result = mysql_query("SELECT 
SUM(ciudadanos) AS ciudadanos, 
SUM(nuevos) AS nuevos,
SUM(hilos_msg) AS hilos_msg,
SUM(partidos) AS partidos,
SUM(empresas) AS empresas,
SUM(pols) AS pols,
SUM(pols_cuentas) AS pols_cuentas,
SUM(pols_gobierno) AS pols_gobierno,
SUM(confianza) AS confianza,
SUM(transacciones) AS transacciones,
SUM(frase) AS frase,
SUM(mapa) AS mapa,
SUM(mapa_vende) AS mapa_vende,
SUM(eliminados) AS eliminados,
COUNT(pais) AS paises,
SUM(24h) AS 24h,
SUM(autentificados) AS autentificados,
time
FROM stats
".(in_array($_GET['a'], $vp['paises'])?'WHERE pais = \''.$_GET['a'].'\' ':'')."
GROUP BY time
ORDER BY time ASC
LIMIT 2000", $link);
while($r = mysql_fetch_array($result)) {

	$d['paises'][$i] = $r['paises'];
	$d['ciudadanos'][$i] = $r['ciudadanos'];
	$d['nuevos'][$i] = $r['nuevos'];
	$d['hilos_msg'][$i] = $r['hilos_msg'];
	$d['partidos'][$i] = $r['partidos'];
	$d['empresas'][$i] = $r['empresas'];

	$pols_cuentas = $r['pols_cuentas']-$r['pols_gobierno'];
	$d['pols'][$i] = $r['pols']+$pols_cuentas;
	$d['pols_cuentas'][$i] = $pols_cuentas;

	
	if (substr($r['pols_gobierno'], 0, 1) == '-') { 
		$d['pols_gobierno'][$i] = 0; 
	} else { 
		$d['pols_gobierno'][$i] = $r['pols_gobierno'];
	} 

	if ($r['confianza'] != 0) { $d['confianza'][$i] = strval($r['confianza']) + 300; } else { $d['confianza'][$i] = 0; }
	$d['transacciones'][$i] = $r['transacciones'];
	$d['pols_total'][$i] = $r['pols'] + $r['pols_cuentas'];
	$d['frase'][$i] = $r['frase'];
	$d['mapa'][$i] = $r['mapa'];
	$d['mapa_vende'][$i] = $r['mapa_vende'];
	$d['eliminados'][$i] = $r['eliminados'];
	$d['24h'][$i] = $r['24h'];
	$d['autentificados'][$i] = $r['autentificados'];
	
	++$i;
}
$d['paises'][0] = 0;


foreach ($vp['paises'] AS $pais) { 
	$txt_tab['/estadisticas/'.$pais] = $pais;
}

$txt_tab['/estadisticas/full'] = _('Datos brutos');

$txt .= '<span style="float:right;font-size:12px;margin-top:-15px;">('.num($i).' '._('días').', '.num($i/365, 2).' '._('años').')</span>

<div id="stats">

<fieldset><legend>'._('Demografía').'</legend>

<b id="1.1">1.1 <span style="color:#0000FF;">'._('Ciudadanos').'</span>/<span style="color:#FF0000;">'._('países').'</span></b> (<a href="/info/censo">'._('Ver censo').'</a>)<br />
<img src="//chart.googleapis.com/chart?cht=lc&chs=800x120&chf=bg,s,ffffff01|c,s,ffffff01&chco=0000FF,FF0000&chm=B,FFFFFF,0,0,0'.($_GET['a']?gen_datos($d['ciudadanos'], false, $d['paises']):gen_datos($d['ciudadanos'], false)).'" alt="Ciudadanos/paises" border="0" />


<br /><b id="1.2">1.2 '._('Ciudadanos').' <span style="color:#0000FF;">'._('nuevos').'</span>/<span style="color:#FF0000;">'._('expirados').'</span></b>  (<a href="/info/censo/nuevos">'._('Ver nuevos').'</a>)<br />
<img src="//chart.googleapis.com/chart?cht=lc&chs=800x120&chf=bg,s,ffffff01|c,s,ffffff01&chco=0000FF,FF0000&chm=B,FFFFFF,0,0,0'.($_GET['a']?gen_datos($d['nuevos'], false, $d['eliminados']):gen_datos($d['nuevos'], false)).'" alt="Ciudadanos nuevos/expirados" border="0" />
</fieldset>

<fieldset><legend>'._('Actividad').'</legend>


<b id="2.1">2.1 '._('Ciudadanos').' '._('activos').'</b> ('._('entraron en').' 24h)<br />
<img src="'.gen_grafico($d['24h'], '', true).'" alt="Ciudadanos que entraron en 24h" border="0" />

<br /><b id="2.2">2.2 '._('Foro').', '._('mensajes nuevos').'</b> (<a href="/foro">'._('Ver foro').'</a>)<br />
<img src="'.gen_grafico($d['hilos_msg']).'" alt="Mensajes en el Foro" border="0" />

'.(!ASAMBLEA?'<br /><b id="2.3">2.3 '._('Partidos').'</b> (<a href="/partidos">'._('Ver partidos').'</a>)<br /><img src="'.gen_grafico($d['partidos']).'" alt="Partidos" border="0" />':'').'


<br /><b id="2.5">2.5 '._('Confianza general').'</b> (<a href="/info/confianza">'._('Ver confianza').'</a>)<br />
<img src="'.gen_grafico($d['confianza'], '', true).'" alt="Confianza" border="0" />

<br /><b id="2.6">2.6 '._('Autentificados').'</b> (<a href="'.SSL_URL.'dnie.php">'._('Autentificación').'</a>)<br />
<img src="'.gen_grafico($d['autentificados']).'" alt="autentificacion" border="0" />

</fieldset>';

if (ECONOMIA) {
	$txt .= '<fieldset><legend>'._('Economía').'</legend>

<b id="3.1">3.1 '.MONEDA.' '._('en total').'</b> </b><br />
<img src="'.gen_grafico($d['pols_total'], '', true).'" alt="monedas en total" border="0" />

<br /><b id="3.2">3.2 '.MONEDA.' '._('de ciudadanos').'</b> (<a href="/info/censo/riqueza">'._('Ver los más ricos').'</a>)<br />
<img src="'.gen_grafico($d['pols'], '', true).'" alt="monedas de ciudadanos" border="0" />

<br /><b id="3.3">3.3 '.MONEDA.' '._('en cuentas personales').'</b> (<a href="/pols/cuentas">'._('Ver cuentas').'</a>)<br />
<img src="'.gen_grafico($d['pols_cuentas'], '', true).'" alt="monedas en cuentas" border="0" />

<br /><b id="3.4">3.4 '.MONEDA.' '._('del gobierno').'</b> ('._('Ver cuenta del').' <a href="/pols/cuentas/1">'._('Gobierno').'</a>)<br />
<img src="'.gen_grafico($d['pols_gobierno']).'" alt="monedas Cuenta Gobierno" border="0" />

<br /><b id="3.5">3.5 '._('Subastas').'</b><br />
<img src="'.gen_grafico($d['frase'], '', true).'" alt="Subasta: la frase" border="0" />

<br /><b id="3.6">3.6 '._('Mapa').'</b> (<a href="/mapa">'._('Ver mapa').'</a>)<br />
<img src="'.gen_grafico($d['mapa']).'" alt="Porcentaje de ocupacion" border="0" />

<br /><b id="3.7">3.7 <span style="color:#0000FF;">'._('Empresas').'</span>/<span style="color:#FF0000;">'._('transacciones').'</span></b> (<a href="/empresas">'._('Ver empresas').'</a> , <a href="/pols">'._('Ver transferencias').'</a>)<br />
<img src="//chart.googleapis.com/chart?cht=lc&chs=800x120&chf=bg,s,ffffff01|c,s,ffffff01&chco=0000FF,FF0000&chm=B,FFFFFF,0,0,0'.($_GET['a']?gen_datos($d['empresas'], true, $d['transacciones']):gen_datos($d['empresas'], true)).'" alt="Empresas" border="0" />

</fieldset>';
}

if (!ASAMBLEA) {
	$txt .= '

<fieldset><legend>'._('Política').'</legend>

<b id="4.1">4.1 '._('Afiliaciones').'</b></p><div style="background:FFFFDD;"><table border="0"><tr>';

foreach ($vp['paises'] AS $PAIS) {
	// GRAFICO AFILIADOS
	$n = 0;
	$g_otros = 0;
	$g_datos = array();
	$g_siglas = array();
	$result = mysql_query("SELECT COUNT(ID) AS num, partido_afiliado,
(SELECT siglas FROM partidos WHERE pais = '".$PAIS."' AND ID = users.partido_afiliado) AS siglas
FROM users 
WHERE estado = 'ciudadano' AND pais = '".$PAIS."'
GROUP BY partido_afiliado
ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)){
		$n++;
		if ($n <= 10) {
			if ($r['partido_afiliado'] == 0) { $r['siglas'] = 'Ninguno'; }
			$g_datos[] = $r['num'];
			$g_siglas[] = $r['siglas'];
		} else {
			$g_otros += $r['num'];
		}
	}

	$txt .= '
<td><b>'.$PAIS.'</b><br />
<img src="//chart.googleapis.com/chart?cht=p&chs=200x100&chds=a
&chd=t:'.implode(',', $g_datos).','.$g_otros.'
&chl='.implode('|', $g_siglas).'|Otros
&chf=bg,s,ffffff01|c,s,ffffff01" alt="Afiliados por partido" title="Afiliados por partido" />
</td>';
	unset($g_siglas, $g_datos);
}

$txt .= '</tr></table></fieldset>';
}
	
	$txt .= '</div>';

}


//THEME
$txt_menu = 'info';
include('theme.php');
?>