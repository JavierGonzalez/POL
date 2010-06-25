<?php 
include('inc-login.php');

function gen_grafico($datos, $fecha='', $cero=false) {
	$maxValue = max($datos);
	if ($cero) { $datos = strtr(chart_data($datos), 'A', '_'); } else { $datos = chart_data($datos); }
	return 'http://chart.apis.google.com/chart?cht=lc&chs=800x120&chxt=y&chxl=0:|_____|'.$maxValue.'&chd=s:'.$datos.'&chf=bg,s,FFFFDD,0&chco=0066FF&chm=B,FFFFFF,0,0,0';
}

function gen_datos($datos, $cero=false, $datos2) {
	$maxValue = max($datos);
	if ($cero) { $datos = strtr(chart_data($datos), 'A', '_'); } else { $datos = chart_data($datos); }

	if ($datos2) {
		$maxValue2 = max($datos2);
		if ($maxValue2 > $maxValue) { $maxValue = $maxValue2; }
		if ($cero) { $datos = $datos.','.strtr(chart_data($datos2), 'A', '_'); } else { $datos = $datos.','.chart_data($datos2); }
	}

	return '&chxl=0:|_____|'.$maxValue.'&chd=s:'.$datos;
}

$txt_title = 'Estad&iacute;sticas';

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

	$d['pols'][$i] = $r['pols'];
	$d['pols_cuentas'][$i] = $r['pols_cuentas'];

	
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
	
	++$i;
}




$txt .= '<h1>';

if ($_GET['a'] == 'POL') {
	$txt .= '<a href="/estadisticas/">Estad&iacute;sticas</a>:  <b>POL</b> | <a href="/estadisticas/Hispania/">Hispania</a>';
} elseif ($_GET['a'] == 'Hispania') {
	$txt .= '<a href="/estadisticas/">Estad&iacute;sticas</a>:  <a href="/estadisticas/POL/">POL</a> | <b>Hispania</b>';
} else {
	$txt .= '<b>Estad&iacute;sticas</b>:  <a href="/estadisticas/POL/">POL</a> | <a href="/estadisticas/Hispania/">Hispania</a>';
}


$txt .= ' <span style="font-size:12px;">('.$i.' d&iacute;as)</span></h1>

<div id="stats">

<h2 style="margin-top:35px;">1. DEMOGRAF&Iacute;A</h2>
<p class="amarillo">

<b>1.1 <span style="color:#0000FF;">Ciudadanos</span>/<span style="color:#FF0000;">paises</span></b> (<a href="/info/censo/">Ver censo</a>)<br />
<img src="http://chart.apis.google.com/chart?cht=lc&chs=800x120&chxt=y&chf=bg,s,FFFFDD,0&chco=0000FF,FF0000&chm=B,FFFFFF,0,0,0'.gen_datos($d['ciudadanos'], false, $d['paises']).'" alt="Ciudadanos/paises" border="0" />


<br /><b>1.2 Ciudadanos <span style="color:#0000FF;">nuevos</span>/<span style="color:#FF0000;">expirados</span></b>  (<a href="/info/censo/nuevos/">Ver nuevos</a>)<br />
<img src="http://chart.apis.google.com/chart?cht=lc&chs=800x120&chxt=y&chf=bg,s,FFFFDD,0&chco=0000FF,FF0000&chm=B,FFFFFF,0,0,0'.gen_datos($d['nuevos'], false, $d['eliminados']).'" alt="Ciudadanos nuevos/expirados" border="0" />

</p>


<h2 style="margin-top:35px;">2. ACTIVIDAD</h2>

<p class="amarillo"><b>2.1 Ciudadanos activos</b> (entraron en 24h)<br />
<img src="'.gen_grafico($d['24h'], '', true).'" alt="Ciudadanos que entraron en 24h" border="0" />

<br /><b>2.2 Foro, nuevos mensajes</b> (<a href="/foro/">Ver foro</a>)<br />
<img src="'.gen_grafico($d['hilos_msg']).'" alt="Mensajes en el Foro" border="0" />

<br /><b>2.3 Partidos pol&iacute;ticos</b> (<a href="/partidos/">Ver partidos</a>)<br />
<img src="'.gen_grafico($d['partidos']).'" alt="Partidos" border="0" />

<br /><b>2.4 <span style="color:#0000FF;">Empresas</span>/<span style="color:#FF0000;">transacciones</span></b> (<a href="/empresas/">Ver empresas</a> , <a href="/pols/">Ver transferencias</a>)<br />
<img src="http://chart.apis.google.com/chart?cht=lc&chs=800x120&chxt=y&chf=bg,s,FFFFDD,0&chco=0000FF,FF0000&chm=B,FFFFFF,0,0,0'.gen_datos($d['empresas'], true, $d['transacciones']).'" alt="Empresas" border="0" />


<br /><b>2.5 Confianza general</b> (<a href="/info/confianza/">Ver confianza</a>)<br />
<img src="'.gen_grafico($d['confianza'], '', true).'" alt="Confianza" border="0" />
</p>

<h2 style="margin-top:35px;">3. ECONOM&Iacute;A</h2>
<p class="amarillo"><b>3.1 '.MONEDA.' en total</b> (<a href="/doc/economia/">Ver circulo del dinero</a>)</b><br />
<img src="'.gen_grafico($d['pols_total'], '', true).'" alt="'.MONEDA_NOMBRE.' en total" border="0" />

<br /><b>3.2 '.MONEDA.' de Ciudadanos</b> (<a href="/info/censo/riqueza/">Ver los m&aacute;s ricos</a>)<br />
<img src="'.gen_grafico($d['pols'], '', true).'" alt="'.MONEDA_NOMBRE.' de ciudadanos" border="0" />

<br /><b>3.3 '.MONEDA.' en Cuentas</b> (<a href="/pols/cuentas/">Ver cuentas</a>)<br />
<img src="'.gen_grafico($d['pols_cuentas'], '', true).'" alt="'.MONEDA_NOMBRE.' en cuentas" border="0" />

<br /><b>3.4 '.MONEDA.' del Gobierno</b> (Ver cuentas: <a href="/pols/cuentas/1/">Gobierno</a> y <a href="/pols/cuentas/2/">Tesoro Publico</a>)<br />
<img src="'.gen_grafico($d['pols_gobierno']).'" alt="'.MONEDA_NOMBRE.' Cuenta Gobierno" border="0" />

<br /><b>3.5 Subastas</b> (Referencia econ&oacute;mica)<br />
<img src="'.gen_grafico($d['frase'], '', true).'" alt="Subasta: la frase" border="0" />

<br /><b>3.6 Mapa: porcentaje de ocupaci&oacute;n</b> (<a href="/">Ver Mapa</a>)<br />
<img src="'.gen_grafico($d['mapa']).'" alt="Porcentaje de ocupacion" border="0" />
</p>


<h2 style="margin-top:35px;">4. POL&Iacute;TICA</h2>
<p class="amarillo"><b>4.1 Afiliados</b><br />';

foreach ($vp['paises'] AS $PAIS) {
	// GRAFICO AFILIADOS
	$result = mysql_query("SELECT COUNT(ID) AS num, partido_afiliado,
(SELECT siglas FROM ".strtolower($PAIS)."_partidos WHERE ID = users.partido_afiliado) AS siglas
FROM users 
WHERE estado = 'ciudadano' AND pais = '".$PAIS."'
GROUP BY partido_afiliado
ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)){

		if ($r['partido_afiliado'] == 0) { $r['siglas'] = 'Ninguno'; }

		if (isset($g_datos)) { $g_datos .= ','; }
		$g_datos .= $r['num'];

		if (isset($g_siglas)) { $g_siglas .= '|'; }
		$g_siglas .= $r['siglas'];

		//if ($g_max > $r['num']) { $g_max = $r['num']; }

	}

	$txt .= '
<b>'.$PAIS.'</b><br />
<img src="http://chart.apis.google.com/chart?cht=p&chs=350x200
&chd=t:'.$g_datos.'
&chl='.$g_siglas.'
" alt="Afiliados por partido" title="Afiliados por partido" /><br />
';
	unset($g_siglas, $g_datos);
}

$txt .= '

</p>


</div>

<p>'.$p_paginas.'</p>';

$txt_header .= '<style type="text/css">#stats p { margin:4px; }</style>';





//THEME
include('theme.php');
?>
