<?php 
include('inc-login.php');

function gen_grafico($datos, $fecha='', $cero=false) {
	$maxValue = max($datos);
	$dato_last = $datos[count($datos)-1];
	 

	if ($cero) { $datos = strtr(chart_data($datos), 'A', '_'); } else { $datos = chart_data($datos); }
	return 'http://chart.apis.google.com/chart?cht=lc&chs=800x120&chxt=y,r&chxl=0:|_____|'.$maxValue.'|1:|___|'.$dato_last.'&chd=s:'.$datos.'&chf=bg,s,ffffff01|c,s,ffffff01&chco=0066FF&chm=B,FFFFFF,0,0,0';
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

	return '&chxt=y,r&chxl=0:|_____|'.$maxValue.'|1:|__|'.$dato_last.'&chd=s:'.$datos;
}

$txt_title = 'Estadísticas';
$txt_nav = array('Estadísticas');
$txt_tab = array('/estadisticas'=>'VirtualPol');


if ($_GET['a'] == 'full-tab') {

	header('Content-Type: text/plain');

	echo 'Dia (20:00)	Pais	Ciudadanos	Ciudadanos nuevos	Ciudadanos eliminados	Ciudadanos que entraron en 24h, sin ser nuevos	Hilos en el foro	Confianza	Partidos	Empresas	Pols	Pols Gobierno	Pols cuentas	Pols frase+palabras	Numero de transacciones	Porcentaje del mapa en venta	Pols de la propiedad mas barata en venta'."\n";

	$result = mysql_query("SELECT * FROM stats ORDER BY time DESC, pais ASC LIMIT 20000", $link);
	while($r = mysql_fetch_array($result)) {
		echo explodear(' ', $r['time'], 0).'	'.$r['pais'].'	'.$r['ciudadanos'].'	'.$r['nuevos'].'	'.$r['eliminados'].'	'.$r['24h'].'	'.$r['hilos_msg'].'	'.$r['confianza'].'	'.$r['partidos'].'	'.$r['empresas'].'	'.$r['pols'].'	'.$r['pols_gobierno'].'	'.$r['pols_cuentas'].'	'.$r['frase'].'	'.$r['transacciones'].'	'.$r['mapa'].'	'.$r['mapa_vende']."\n";
	}
	exit;

} else if ($_GET['a'] == 'full') {


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

$txt_tab['/estadisticas/full'] = 'Datos brutos';

$txt .= '<span style="float:right;font-size:12px;margin-top:-15px;">('.num($i).' días, '.num($i/365, 2).' años)</span>

<div id="stats">

<fieldset><legend>1. Demografía</legend>

<b id="1.1">1.1 <span style="color:#0000FF;">Ciudadanos</span>/<span style="color:#FF0000;">paises</span></b> (<a href="/info/censo/">Ver censo</a>)<br />
<img src="http://chart.apis.google.com/chart?cht=lc&chs=800x120&chf=bg,s,ffffff01|c,s,ffffff01&chco=0000FF,FF0000&chm=B,FFFFFF,0,0,0'.($_GET['a']?gen_datos($d['ciudadanos'], false, $d['paises']):gen_datos($d['ciudadanos'], false)).'" alt="Ciudadanos/paises" border="0" />


<br /><b id="1.2">1.2 Ciudadanos <span style="color:#0000FF;">nuevos</span>/<span style="color:#FF0000;">expirados</span></b>  (<a href="/info/censo/nuevos/">Ver nuevos</a>)<br />
<img src="http://chart.apis.google.com/chart?cht=lc&chs=800x120&chf=bg,s,ffffff01|c,s,ffffff01&chco=0000FF,FF0000&chm=B,FFFFFF,0,0,0'.($_GET['a']?gen_datos($d['nuevos'], false, $d['eliminados']):gen_datos($d['nuevos'], false)).'" alt="Ciudadanos nuevos/expirados" border="0" />
</fieldset>

<fieldset><legend>2. Actividad</legend>


<b id="2.1">2.1 Ciudadanos activos</b> (entraron en 24h)<br />
<img src="'.gen_grafico($d['24h'], '', true).'" alt="Ciudadanos que entraron en 24h" border="0" />

<br /><b id="2.2">2.2 Foro, nuevos mensajes</b> (<a href="/foro/">Ver foro</a>)<br />
<img src="'.gen_grafico($d['hilos_msg']).'" alt="Mensajes en el Foro" border="0" />

'.(!ASAMBLEA?'<br /><b id="2.3">2.3 Partidos pol&iacute;ticos</b> (<a href="/partidos/">Ver partidos</a>)<br /><img src="'.gen_grafico($d['partidos']).'" alt="Partidos" border="0" />':'').'


<br /><b id="2.5">2.5 Confianza general</b> (<a href="/info/confianza/">Ver confianza</a>)<br />
<img src="'.gen_grafico($d['confianza'], '', true).'" alt="Confianza" border="0" />

<br /><b id="2.6">2.6 Autentificados</b> (<a href="'.SSL_URL.'dnie.php">Autentificaci&oacute;n</a>)<br />
<img src="'.gen_grafico($d['autentificados']).'" alt="autentificacion" border="0" />

</fieldset>';

if (ECONOMIA) {
	$txt .= '<fieldset><legend>3. Economía</legend>

<b id="3.1">3.1 '.MONEDA.' en total</b> </b><br />
<img src="'.gen_grafico($d['pols_total'], '', true).'" alt="monedas en total" border="0" />

<br /><b id="3.2">3.2 '.MONEDA.' de Ciudadanos</b> (<a href="/info/censo/riqueza/">Ver los m&aacute;s ricos</a>)<br />
<img src="'.gen_grafico($d['pols'], '', true).'" alt="monedas de ciudadanos" border="0" />

<br /><b id="3.3">3.3 '.MONEDA.' en Cuentas personales</b> (<a href="/pols/cuentas/">Ver cuentas</a>)<br />
<img src="'.gen_grafico($d['pols_cuentas'], '', true).'" alt="monedas en cuentas" border="0" />

<br /><b id="3.4">3.4 '.MONEDA.' del Gobierno</b> (Ver cuenta del <a href="/pols/cuentas/1/">Gobierno</a>)<br />
<img src="'.gen_grafico($d['pols_gobierno']).'" alt="monedas Cuenta Gobierno" border="0" />

<br /><b id="3.5">3.5 Subastas</b> (Referencia econ&oacute;mica)<br />
<img src="'.gen_grafico($d['frase'], '', true).'" alt="Subasta: la frase" border="0" />

<br /><b id="3.6">3.6 Mapa: porcentaje de ocupaci&oacute;n</b> (<a href="/mapa/">Ver Mapa</a>)<br />
<img src="'.gen_grafico($d['mapa']).'" alt="Porcentaje de ocupacion" border="0" />

<br /><b id="3.7">3.7 <span style="color:#0000FF;">Empresas</span>/<span style="color:#FF0000;">transacciones</span></b> (<a href="/empresas/">Ver empresas</a> , <a href="/pols/">Ver transferencias</a>)<br />
<img src="http://chart.apis.google.com/chart?cht=lc&chs=800x120&chf=bg,s,ffffff01|c,s,ffffff01&chco=0000FF,FF0000&chm=B,FFFFFF,0,0,0'.($_GET['a']?gen_datos($d['empresas'], true, $d['transacciones']):gen_datos($d['empresas'], true)).'" alt="Empresas" border="0" />

</fieldset>';
}

if (!ASAMBLEA) {
	$txt .= '

<fieldset><legend>4. Política</legend>

<b id="4.1">4.1 Afiliaciones</b></p><div style="background:FFFFDD;"><table border="0"><tr>';

foreach ($vp['paises'] AS $PAIS) {
	// GRAFICO AFILIADOS
	$n = 0;
	$g_otros = 0;
	$g_datos = array();
	$g_siglas = array();
	$result = mysql_query("SELECT COUNT(ID) AS num, partido_afiliado,
(SELECT siglas FROM ".strtolower($PAIS)."_partidos WHERE ID = users.partido_afiliado) AS siglas
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
<img src="http://chart.apis.google.com/chart?cht=p&chs=200x100&chds=a
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