<?php 
include('inc-login.php');

switch ($_GET['a']) {

case 'recuperar-login':
	$adsense_exclude = true;
	$txt .= '<h1>Recuperar Contrase&ntilde;a:</h1><p>Si has perdido tu contrase&ntilde;a debes escribirnos un email a <em>pol@teoriza.com</em>, desde tu email de registro y gestionaremos tu recuperaci&oacute;n.</p><p>Proximamente esta funcionalidad ser&aacute; autom&aacute;tica. Gracias.</p>';
	$txt_title = 'Recuperar contrase&ntilde;a';
	break;

case 'foto':

	$txt .= '<h1>Instantanea de VirtualPol</h1><br />';
	$result = mysql_query("SELECT ID, nick, pais
FROM ".SQL_USERS."
WHERE estado = 'ciudadano' AND avatar = 'true'
ORDER BY online DESC
LIMIT 300", $link);
	while($row = mysql_fetch_array($result)) { 
		$txt .= '<img src="/img/a/'.$row['ID'].'.jpg" alt="'.$row['nick'].'" title="'.$row['nick'].'" />'; 
	}

	break;

case 'censo':
	$num_element_pag = $pol['config']['info_censo'];


	// num turistas
	$result = mysql_fetch_row(mysql_query("SELECT COUNT(ID) FROM ".SQL_USERS." WHERE estado = 'turista'", $link));
	$censo_turistas = $result[0];

	// num expulsados
	$result = mysql_fetch_row(mysql_query("SELECT COUNT(ID) FROM ".SQL_USERS." WHERE estado = 'expulsado'", $link));
	$censo_expulsados = $result[0];

	if (!is_numeric($_GET['c'])) {
		$pagina = $_GET['d'];
		$pagina_url = '/info/censo/busqueda/' . $_GET['c'] . '/';
	} elseif (($_GET['b']) AND (!is_numeric($_GET['b']))) { 
		$pagina = $_GET['c'];
		$pagina_url = '/info/censo/' . $_GET['b'] . '/';
	} else { 
		$pagina = $_GET['b']; 
		$pagina_url = '/info/censo/';
	}

	paginacion('censo', $pagina_url, null, $pagina, $num_element_pag, 50);

	if ($_GET['b'] == 'nuevos') {
		$old = 'antiguedad';
	} else {
		$old = 'nuevos';
	}


	if ($_GET['b']) {
		$txt .= '<h1><a href="/info/censo/">Censo</a>: ' . ucfirst($_GET['b']) . '</h1>';
	} else {
		$txt .= '<h1>Censo:</h1>';
	}

	if ($_GET['b'] == 'busqueda') {
		$busqueda = $_GET['c'];
	} else {
		$busqueda = '';
	}

$txt .= '<p>' . $p_paginas . ' &nbsp; <a href="/info/censo/">Ciudadanos</a>: <b>' . $pol['config']['info_censo'] . '</b> | <a href="/info/censo/turistas/" class="turista">Turistas</a>: <b>' . $censo_turistas . '</b> | <a href="/info/censo/expulsados/" class="expulsado">Expulsados</a>: <b>' . $censo_expulsados . '</b> | <a href="/info/censo/riqueza/">Ricos</a> | 

<input name="qcmq" size="10" value="'.$busqueda.'" type="text" id="cmq">
<input value="Buscar en perfil" type="submit" onclick="var cmq = $(\'#cmq\').attr(\'value\'); window.location.href=\'/info/censo/busqueda/\'+cmq+\'/\'; return false;">

</p>

<table border="0" cellspacing="2" cellpadding="0" class="pol_table">
<tr>
<th></th>
<th style="padding:8px;" class="azul"><a href="/info/censo/nivel/">Nivel</a></th>
<th></th>
<th style="padding:8px;" class="azul"><a href="/info/censo/nombre/">Nick</a></th>
<th style="padding:8px;" class="azul"><a href="/info/censo/afiliacion/">Afil</a></th>
<th style="padding:8px;" class="azul"><a href="/info/censo/online/">Online</a></th>
<th style="padding:8px;" class="azul"><a href="/info/censo/nota/">Nota</a></th>
<th style="padding:8px;" class="azul"><a href="/info/censo/' . $old . '/">Antiguedad</a></th>
<th style="padding:8px;" class="azul"><a href="/info/censo/elec/"><acronym title="Elecciones en las que ha participado">Elec</acronym></a></th>
<th style="padding:8px;" class="azul"><a href="/info/censo/refs/"><acronym title="Numero de referencias">Ref</acronym></a></th>
<th style="padding:8px;" class="azul"><a href="/info/censo/confianza/"><acronym title="Confianza">Conf</acronym></a></th>
<th style="padding:8px;" class="azul" colspan="2"><a href="/info/censo/">&Uacute;ltimo&nbsp;acceso&darr;</a></th>
</tr>';

	switch ($_GET['b']) {
		case 'busqueda': $order_by = 'WHERE text LIKE \'%'.$_GET['c'].'%\' ORDER BY fecha_last DESC'; break;
		case 'nivel': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' AND ID != \'1\' ORDER BY nivel DESC'; break;
		case 'nombre': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY nick ASC'; break;
		case 'nuevos': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY fecha_registro DESC'; break;
		case 'antiguedad': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY fecha_registro ASC'; break;
		case 'elec': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY num_elec DESC, fecha_registro ASC'; break;
		case 'online': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY online DESC'; break;
		case 'nota': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY nota DESC'; break;
		case 'riqueza': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY pols DESC, fecha_registro ASC'; break;
		case 'refs': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY ref_num DESC, fecha_registro ASC'; break;
		case 'afiliacion': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY partido_afiliado DESC, fecha_registro ASC'; break;
		case 'confianza': $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY voto_confianza DESC, fecha_registro ASC'; break;
		
		case 'expulsados': $order_by = 'WHERE estado = \'expulsado\' ORDER BY fecha_last DESC'; $num_element_pag = $censo_expulsados; break;
		case 'turistas': $order_by = 'WHERE estado = \'turista\' ORDER BY fecha_registro DESC'; $num_element_pag = $censo_turistas; break;

		default: $order_by = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' ORDER BY fecha_last DESC';
	}

	if ($p_init) { $orden = $p_init + 1; } else { $orden = 1; }

	if ($pol['estado']) { $sql_extra = ", (SELECT voto FROM ".SQL_VOTOS." WHERE estado = 'confianza' AND uservoto_ID = '" . $pol['user_ID'] . "' AND user_ID = ".SQL_USERS.".ID LIMIT 1) AS has_votado"; }

	$result = mysql_query("SELECT *,
(SELECT siglas FROM ".SQL."partidos WHERE ".SQL_USERS.".partido_afiliado != '0' AND ID = ".SQL_USERS.".partido_afiliado LIMIT 1) AS siglas" . $sql_extra . "
FROM ".SQL_USERS." " . $order_by . " LIMIT " . $p_limit, $link);
	while($row = mysql_fetch_array($result)){
		$txt .= mysql_error($link);
		$veterano = '';		
		if ($row['nivel'] == 120) { $row['nivel'] = 1; }
		if ($row['online'] != 0) { $online = duracion($row['online']); } else { $online = ''; }
		if ($row['ref'] != 0) {
			$result2 = mysql_query("SELECT nick FROM ".SQL_USERS." WHERE ID = '" . $row['ref'] . "' LIMIT 1", $link);
			while($row2 = mysql_fetch_array($result2)){ $veterano = '(Ref: ' . crear_link($row2['nick']) . ')'; }
		}
		if ($row['avatar'] == 'true') { $avatar = avatar($row['ID'], 40) . ' '; } else { $avatar = ''; }
		if ($row['siglas']) { $partido = '<a href="/partidos/' . strtolower($row['siglas']) . '/">' . $row['siglas'] . '</a>'; } else { $partido = ''; }
		if ($row['ref_num'] == 0) { $row['ref_num'] = ''; }
		if ($row['num_elec'] == 0) { $row['num_elec'] = ''; }

		if ($row['has_votado']) { $has_votado = ' (' . confianza($row['has_votado']) . ')'; } else { $has_votado = ''; }
		$txt .= '
<tr>
<td align="right" class="gris">' . $orden++ . '</td>
<td align="right">' . $row['nivel'] . '</td>
<td>' . $avatar . '</td>
<td><img src="/img/cargos/' . $row['cargo'] . '.gif" /> <b>' . crear_link($row['nick'], 'nick', $row['estado']) . '</b></td>
<td>' . $partido . '</td>
<td align="right" nowrap="nowrap">' . $online . '</td>
<td class="gris" align="right">' . $row['nota'] . '</td>
<td>' . explodear(' ', $row['fecha_registro'], 0) . '</td>
<td align="right">' . $row['num_elec'] . '</td>
<td align="right">' . $row['ref_num'] . '</td>
<td>' . confianza($row['voto_confianza']) . $has_votado .'</td>
<td align="right" nowrap="nowrap">' . duracion(time() - strtotime($row['fecha_last'])) . '</td>
<td nowrap="nowrap">' . $veterano . '</td>
</tr>' . "\n";
	}
	$txt .= '</table><p>' . $p_paginas . '</p>';
	
	$txt_title = 'Censo de Ciudadanos';
	break;





case 'estadisticas':


	function gen_grafico($datos, $fecha, $cero=false) {
		if ($datos) { $maxValue = max($datos); }
		if ($minValue >= 0) { $minValue = 0;  }
		if ($cero) { $datos = strtr(chart_data($datos), 'A', '_'); } else { $datos = chart_data($datos); }
		return 'http://chart.apis.google.com/chart?cht=lc&chs=840x120&chxt=y&chxl=0:|_____|' . $maxValue . '&chd=s:' . $datos . '&chf=bg,s,FFFFDD,0&chco=0066FF&chm=B,FFFFFF,0,0,0';
	}


	$txt_title = 'Estad&iacute;sticas, gr&aacute;ficos hist&oacute;ricos ';

	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."stats", $link);
	while($row = mysql_fetch_array($result)) { $num_total = $row['num']; }

	//paginacion('hilo', '/info/estadisticas/', null, $_GET['b'], $num_total, 100);

	$i = 0;
	$result = mysql_query("SELECT * FROM ".SQL."stats ORDER BY time ASC LIMIT 400", $link);
	while($row = mysql_fetch_array($result)) {

		$datos['ciudadanos'][$i] = $row['ciudadanos'];
		$datos['nuevos'][$i] = $row['nuevos'];
		$datos['hilos_msg'][$i] = $row['hilos_msg'];
		$datos['partidos'][$i] = $row['partidos'];
		$datos['empresas'][$i] = $row['empresas'];

		$datos['pols'][$i] = $row['pols'];
		$datos['pols_cuentas'][$i] = $row['pols_cuentas'];

		
		if (substr($row['pols_gobierno'], 0, 1) == '-') { 
			$datos['pols_gobierno'][$i] = 0; 
		} else { 
			$datos['pols_gobierno'][$i] = $row['pols_gobierno'];
		} 

		if ($row['confianza'] != 0) { $datos['confianza'][$i] = strval($row['confianza']) + 300; } else { $datos['confianza'][$i] = 0; }
		$datos['transacciones'][$i] = $row['transacciones'];
		$datos['pols_total'][$i] = $row['pols'] + $row['pols_cuentas'];
		$datos['frase'][$i] = $row['frase'];
		$datos['mapa'][$i] = $row['mapa'];
		$datos['mapa_vende'][$i] = $row['mapa_vende'];
		$datos['eliminados'][$i] = $row['eliminados'];
		$datos['24h'][$i] = $row['24h'];

		$fecha = date('j', strtotime($row['time']));
		$datos['fecha'] .= '|' . $fecha;

		
		++$i;
	}

/*
pol_stats (ID, time, ciudadanos, nuevos, pols, pols_cuentas, transacciones, hilos_msg, pols_gobierno, partidos)
*/

	$txt .= '<h1>' . $p_paginas . ' Estad&iacute;sticas: <span style="font-size:12px;">(&Uacute;ltimos ' . $i . ' d&iacute;as)</span></h1>

<div id="stats">

<h2 style="margin-top:35px;">1. DEMOGRAF&Iacute;A</h2>
<p class="amarillo"><b>1.1 Ciudadanos</b> (<a href="/info/censo/">Ver censo</a>)<br />
<img src="' . gen_grafico($datos['ciudadanos'], $datos['fecha']) . '" alt="Ciudadanos censo" border="0" />

<br /><b>1.2 Ciudadanos nuevos</b>  (<a href="/info/censo/nuevos/">Ver nuevos</a>)<br />
<img src="' . gen_grafico($datos['nuevos'], $datos['fecha']) . '" alt="Ciudadanos nuevos" border="0" />

<br /><b>1.3 Ciudadanos expirados</b><br />
<img src="' . gen_grafico($datos['eliminados'], $datos['fecha']) . '" alt="Ciudadanos expirados" border="0" />
</p>


<h2 style="margin-top:35px;">2. ACTIVIDAD</h2>

<p class="amarillo"><b>2.1 Ciudadanos activos</b> (entraron en 24h)<br />
<img src="' . gen_grafico($datos['24h'], $datos['fecha'], true) . '" alt="Ciudadanos que entraron en 24h" border="0" />

<br /><b>2.2 Foro, nuevos mensajes</b> (<a href="/foro/">Ver foro</a>)<br />
<img src="' . gen_grafico($datos['hilos_msg'], $datos['fecha']) . '" alt="Mensajes en el Foro" border="0" />

<br /><b>2.3 Partidos pol&iacute;ticos</b> (<a href="/partidos/">Ver partidos</a>)<br />
<img src="' . gen_grafico($datos['partidos'], $datos['fecha']) . '" alt="Partidos" border="0" />

<br /><b>2.4 Empresas</b> (<a href="/empresas/">Ver empresas</a>)<br />
<img src="' . gen_grafico($datos['empresas'], $datos['fecha'], true) . '" alt="Empresas" border="0" />

<br /><b>2.5 Transacciones</b> (<a href="/pols/">Ver transferencias</a>)<br />
<img src="' . gen_grafico($datos['transacciones'], $datos['fecha'], true) . '" alt="Transacciones" border="0" />

<br /><b>2.6 Confianza general</b> (<a href="/info/confianza/">Ver confianza</a>)<br />
<img src="' . gen_grafico($datos['confianza'], $datos['fecha'], true) . '" alt="Confianza" border="0" />
</p>

<h2 style="margin-top:35px;">3. ECONOM&Iacute;A</h2>
<p class="amarillo"><b>3.1 '.MONEDA.' en total</b> (<a href="/doc/economia/">Ver circulo del dinero</a>)</b><br />
<img src="' . gen_grafico($datos['pols_total'], $datos['fecha'], true) . '" alt="'.MONEDA_NOMBRE.' en total" border="0" />

<br /><b>3.2 '.MONEDA.' de Ciudadanos</b> (<a href="/info/censo/riqueza/">Ver los m&aacute;s ricos</a>)<br />
<img src="' . gen_grafico($datos['pols'], $datos['fecha'], true) . '" alt="'.MONEDA_NOMBRE.' de ciudadanos" border="0" />

<br /><b>3.3 '.MONEDA.' en Cuentas</b> (<a href="/pols/cuentas/">Ver cuentas</a>)<br />
<img src="' . gen_grafico($datos['pols_cuentas'], $datos['fecha'], true) . '" alt="'.MONEDA_NOMBRE.' en cuentas" border="0" />

<br /><b>3.4 '.MONEDA.' del Gobierno</b> (Ver cuentas: <a href="/pols/cuentas/1/">Gobierno</a> y <a href="/pols/cuentas/2/">Tesoro Publico</a>)<br />
<img src="' . gen_grafico($datos['pols_gobierno'], $datos['fecha']) . '" alt="'.MONEDA_NOMBRE.' Cuenta Gobierno" border="0" />

<br /><b>3.5 Subastas</b> (Referencia econ&oacute;mica)<br />
<img src="' . gen_grafico($datos['frase'], $datos['fecha'], true) . '" alt="Subasta: la frase" border="0" />

<br /><b>3.6 Mapa: porcentaje de ocupaci&oacute;n</b> (<a href="/">Ver Mapa</a>)<br />
<img src="' . gen_grafico($datos['mapa'], $datos['fecha']) . '" alt="Porcentaje de ocupacion" border="0" />
</p>

</div>

<p>' . $p_paginas . '</p>';

	$txt_header .= '<style type="text/css">#stats p { margin:4px; }</style>';



	break;

case 'economia':
	$txt .= '<h1>'.MONEDA.' Econom&iacute;a Global:</h1>';
	$txt_title = 'Economia Global';



// #CUADRAR
// TOTAL 11 AGOSTO: 544.645 | 554.528 | 674.518

$moneda_mundial = '674518';


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

foreach ($vp['paises'] AS $pais) {

$result = mysql_query("SELECT SUM(pols + IFNULL((SELECT SUM(pols) FROM ".strtolower($pais)."_cuentas WHERE user_ID = ".SQL_USERS.".ID GROUP BY user_ID),0)) AS pols_ciudadanos,
(SELECT COUNT(ID) FROM ".SQL_USERS." WHERE pais = '".$pais."' AND estado = 'ciudadano') AS num_ciudadanos,
(SELECT SUM(pols) FROM ".strtolower($pais)."_cuentas WHERE nivel > 0) AS pols_gobierno,
(SELECT SUM(pols) FROM ".SQL_USERS." WHERE pais = '".$pais."' AND pols < 0) AS pols_negativo,
(SELECT valor FROM ".strtolower($pais)."_config WHERE dato = 'arancel_salida' LIMIT 1) AS arancel_salida,
(SELECT valor FROM ".strtolower($pais)."_config WHERE dato = 'impuestos' LIMIT 1) AS impuestos,
(SELECT valor FROM ".strtolower($pais)."_config WHERE dato = 'impuestos_minimo' LIMIT 1) AS impuestos_minimo,
(SELECT valor FROM ".strtolower($pais)."_config WHERE dato = 'pols_inem' LIMIT 1) AS inem,
(SELECT AVG(salario) FROM ".strtolower($pais)."_estudios) AS salario_medio
FROM ".SQL_USERS."
WHERE pais = '".$pais."'");
	while($row = mysql_fetch_array($result)) {


		$result2 = mysql_query("SELECT nick, pais,
(pols + IFNULL((SELECT SUM(pols) FROM ".strtolower($pais)."_cuentas WHERE user_ID = ".SQL_USERS.".ID GROUP BY user_ID),0)) AS pols_total
FROM ".SQL_USERS."
WHERE pais = '".$pais."'
ORDER BY pols_total DESC 
LIMIT 25", $link);
		while ($row2 = mysql_fetch_array($result2)) {
			$ricos[$row2['nick'].':'.$row2['pais']] = $row2['pols_total'];
		}



		$total += $row['pols_ciudadanos'] + $row['pols_gobierno'];

		$total_pais[$pais] = $row['pols_ciudadanos']+$row['pols_gobierno'];

		$txt .= '<tr>
<td style="background:'.$vp['bg'][$pais].';"><a href="http://'.strtolower($pais).'.virtualpol.com/"><b>'.$pais.'</b></a></td>
<td align="right"><b>'.$row['num_ciudadanos'].'</b></td>
<td align="right">'.pols($row['pols_negativo']).'</td>

<td align="right" style="color:red;"><b>'.$row['arancel_salida'].'%</b></td>';


if ($row['impuestos'] > 0) {
	$txt .= '<td><b>'.$row['impuestos'].'%</b></td><td align="right">'.pols($row['impuestos_minimo']).'</td>';
} else {
	$txt .= '<td colspan="2">Sin impuestos</td>';
}


$txt .= '<td align="right">'.pols($row['inem']).'</td>

<td align="right">'.pols($row['salario_medio']).'</td>
<td align="right">'.pols(round($row['pols_ciudadanos']/$row['num_ciudadanos'])).'</td>

<td align="right">'.pols($row['pols_ciudadanos']).'</td>
<td>+</td>
<td align="right">'.pols($row['pols_gobierno']).'</td>
<td>=</td>
<td align="right">'.pols($row['pols_ciudadanos']+$row['pols_gobierno']).'</td>
</tr>';

	}


	// GEN GRAFICO VISITAS
	$n = 0;
	$result = mysql_query("SELECT pols, pols_cuentas FROM ".strtolower($pais)."_stats ORDER BY time DESC LIMIT 9", $link);
	while($row = mysql_fetch_array($result)){
		if ($gph[$pais]) { $gph[$pais] = ',' . $gph[$pais]; }
		$gph_maxx[$n] += $row['pols'] + $row['pols_cuentas'];
		$gph[$pais] = $row['pols'] + $row['pols_cuentas'] . $gph[$pais];
		if ($gph_maxx[$n] > $gph_max) {
			$gph_max = $gph_maxx[$n];
		}
		$n++;
	}

}


	$result = mysql_query("SELECT SUM(pols) AS pols_total FROM ".SQL_USERS." WHERE pais = 'ninguno'");
	while($row = mysql_fetch_array($result)) {
		$pols_turistas = $row['pols_total'];
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
		if ($pol['estado'] == 'desarrollador') { $extra = pols($pols_total).' '; }
		$txt .= '<li>'.MONEDA.' <b class="big">'.$extra.''.crear_link($nick, 'nick', 'ciudadano', $pais).'</b></li>';
	}
}

$txt .= '</ol>


</td>

<td align="center" colspan="6" valign="top">
<h2>Evoluci&oacute;n de la econom&iacute;a:</h2><br />

<img src="http://chart.apis.google.com/chart?cht=lc
&chs=330x350
&cht=bvs
&chco='.substr($vp['bg']['POL'],1).','.substr($vp['bg']['VULCAN'],1).','.substr($vp['bg']['Hispania'],1).'
&chd=t:'.$gph['POL'].','.$total_pais['POL'].'|'.$gph['VULCAN'].','.$total_pais['VULCAN'].'|'.$gph['Hispania'].','.$total_pais['Hispania'].'
&chds=0,'.$moneda_mundial.'
&chxt=r
&chxl=0:||'.round($moneda_mundial / 2).'|'.$moneda_mundial.'
" alt="Monedas" />
</td>

<td align="center" colspan="6" valign="top">
<h2>Reparto econ&oacute;mico:</h2><br />
<img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.round(($total_pais['POL']*100)/$total_moneda).','.round(($total_pais['VULCAN']*100)/$total_moneda).','.round(($total_pais['Hispania']*100)/$total_moneda).'&chs=300x190&chl=POL|VULCAN|Hispania&chco='.substr($vp['bg']['POL'],1).','.substr($vp['bg']['VULCAN'],1).','.substr($vp['bg']['Hispania'],1).'" alt="Reparto economico." /></td>

</tr>

<tr>
<td align="center" colspan="15">(zona com&uacute;n entre paises)</td>
</tr>
</table>';


	break;





}



//THEME
include('theme.php');
?>
