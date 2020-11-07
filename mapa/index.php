<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




function generar_color() {
	$colores_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
	$color .= $colores_a[rand(0,15)] . $colores_a[rand(0,15)] . $colores_a[rand(0,15)];
	return $color;
}

$result = mysql_query_old("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'", $link);
while($r = mysqli_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }



/* estado
P - propiedad	LIBRE			(propiedad, no venta)						link|nick|color
V - venta		Amarillo		(propiedad, en venta, link a compra)	v|id|nick|pols
E - estado		Gris			(propiedad, no venta, estatal)			e|link-interno|text

S - solar			Blanco		(solar, en venta, link a compra)			null
*/

// mapa (ID, pais, pos_x, pos_y, size_x, size_y, user_ID, link, text, time, pols, color, estado)
if (nucleo_acceso($vp['acceso']['gestion_mapa'])) { 
	$txt_tab['/mapa/arquitecto'] = _('Gestion arquitecto');
}

$txt_tab['/mapa/barrios'] = _('Barrios de POL');

if (($_GET[1] == 'arquitecto') AND nucleo_acceso($vp['acceso']['gestion_mapa'])) {

	include('arquitecto.php');
	
}if (($_GET[1] == 'compraventa') AND ($_GET[2])) { //Comprar

		$result = mysql_query_old("SELECT *
FROM mapa
WHERE pais = '".PAIS."' AND ID = '".$_GET[2]."' AND estado = 'v'
LIMIT 1", $link);
	while($r = mysqli_fetch_array($result)){

		$txt_title = _('Mapa').': '._('Comprar propiedad');
		$txt_nav = array('/mapa'=>_('Mapa'), _('Comprar propiedad').' '.$_GET[2]);

		echo '<ol>

<li>'._('Propiedad').': <b>' . $r['ID'] . '</b><br />
'._('Posición').': <b>' . $r['pos_x'] . '-' . $r['pos_y'] . '</b><br />
'._('Tamaño').': <b>' . $r['size_x'] . 'x' . $r['size_y'] . '=' . ($r['size_x'] * $r['size_y']) . '</b><br />
'._('Coste').': <b>'.pols(round($r['pols'] / ($r['size_x'] * $r['size_y']))).'</b> <img src="'.IMG.'varios/m.gif" alt="monedas" /> '._('por cuadrado').'<br /><br /></li>

<li>' . boton(_('Comprar'), '/accion/mapa/compraventa?ID=' . $r['ID'], false, false, $r['pols']) . '<br /><br /></li>

</ol>

<p><a href="/mapa/propiedades"><b>'._('Ver tus propiedades').'</b></a></p>';
	}

} elseif (($_GET[1] == 'vender') AND ($_GET[2])) { // VENDER

	$result = mysql_query_old("SELECT *
FROM mapa
WHERE pais = '".PAIS."' AND ID = '" . $_GET[2] . "' 
LIMIT 1", $link);
	while($r = mysqli_fetch_array($result)){

		$txt_title = _('Mapa').': '._('Vender propiedad');
		$txt_nav = array('/mapa'=>_('Mapa'), _('Vender propiedad').' '.$_GET[2]);
		echo '

<form action="/accion/mapa/vender?ID=' . $_GET[2] . '" method="post">

<ol>

<li>'._('Propiedad').': <b>' . $r['ID'] . '</b><br />
'._('Posición').': <b>' . $r['pos_x'] . '-' . $r['pos_y'] . '</b><br />
'._('Tamaño').': <b>' . $r['size_x'] . 'x' . $r['size_y'] . '=' . ($r['size_x'] * $r['size_y']) . '</b><br /><br /></li>

<li>'._('Precio').': <b><input type="text" style="text-align:right;" name="pols" size="5" maxlength="4" value="' . $r['pols'] . '" /> '.MONEDA.'</b><br /><br /></li>

<li><input type="submit" value="'._('Poner en venta').'" /><br /><br /></li>

</ol>

</form>

<p><a href="/mapa/propiedades"><b>'._('Ver tus propiedades').'</b></a></p>';

	}

} elseif (($_GET[1] == 'editar') AND ($_GET[2])) { // EDITAR
	$txt_title = _('Mapa').': '._('Editar propiedad');
	$txt_nav = array('/mapa'=>_('Mapa'), _('Editar propiedad'));



	$result_max_altura = mysql_query_old("SELECT altura_maxima 
		FROM mapa_barrios b, mapa m 
		WHERE m.ID = '" . $_GET[2] . "' 
		AND ( m.pos_x >= b.pos_x 
			AND m.pos_x < (b.pos_x+b.size_x) )
		AND ( m.pos_y >= b.pos_y 
			AND m.pos_y < (b.pos_y+b.size_y) )");

	$max_altura = 1;
	if ($r = mysqli_fetch_array($result_max_altura)){
		$max_altura = $r['altura_maxima'];
	}


	$result = mysql_query_old("SELECT *
FROM mapa
WHERE pais = '".PAIS."' AND ID = '" . $_GET[2] . "' AND (user_ID = '" . $pol['user_ID'] . "' OR (estado = 'e' AND 'true' = '".(nucleo_acceso($vp['acceso']['gestion_mapa'])?'true':'false')."'))
LIMIT 1", $link);
	while($r = mysqli_fetch_array($result)){


		for ($n=1;$n <=15;$n++) {
			$color = generar_color();
			$colores .= '<option value="' . $color . '" style="background:#' . $color . ';width:60px;">' . $color . '</option>';
		}
		
		$tamaño = ($r['size_x'] * $r['size_y']);
		echo '<h1><a href="/mapa">'._('Mapa').'</a>: '._('Editar propiedad').': ' . $_GET[2] . '</h1>

<form action="/accion/mapa/editar?ID=' . $_GET[2] . '" method="post">
<input type="hidden" name="max_altura" value="' . $max_altura . '" />
<ul style="list-style-type:none;">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.3.3/jscolor.min.js" integrity="sha512-KVabwlnqwMHqLIONPKHQTGzW4C0dg3HEPwtTCVjzGOW508cm5Vl6qFvewK/DUbgqLPGRrMeKL3Ga3kput855HQ==" crossorigin="anonymous"></script>

<li>'._('Propiedad').': <b>' . $r['ID'] . '</b><br />
'._('Posición').': <b>' . $r['pos_x'] . '-' . $r['pos_y'] . '</b><br />
'._('Tamaño').': <b>' . $r['size_x'] . 'x' . $r['size_y'] . '=' . ($r['size_x'] * $r['size_y']) . '</b><br /><br /></li>
';
for ($i=1;$i<=$max_altura;$i++){
	error_log("SELECT link, color
	FROM mapa_altura
	WHERE parcela_ID = '" . $_GET[2] . "' AND altura = ".$i." 
	LIMIT 1");


	$result_altura = mysql_query_old("SELECT link, color
	FROM mapa_altura
	WHERE parcela_ID = '" . $_GET[2] . "' AND altura = '".$i."'
	LIMIT 1", $link);
	if ($r_altura = mysqli_fetch_array($result_altura)){
		$color = $r_altura['color'];
		$text = $r_altura['link'];
	}else{
		$color = $r['color'];
		$text = $r['link'];
	}

	echo ' <fieldset>
	<legend>Piso '.$i.'</legend>
	<li><b>'._('Dirección web').'</b> o <b>'._('frase').'</b>:<br />
	<input type="text" name="link_'.$i.'" size="50" maxlength="70" value="' . $text . '" /><br /><br /></li>

	<li><b>'._('Color').':</b> 
	Color: <input value="'.$color.'" data-jscolor="" name="color_'.$i.'">
	</fieldset>
	';

}


echo '<li><input type="submit" value="'._('Guardar').'" /><br /><br /></li>

</ul>

</form>

<p><a href="/mapa"><b>Ver mapa</b></a> &nbsp; <a href="/mapa/propiedades"><b>Ver tus propiedades</b></a></p>';
	}




} elseif ($_GET[1] == 'propiedades') { //Propiedades

	$txt_title = 'Mapa: Tus propiedades';
	$txt_nav = array('/mapa'=>'Mapa', 'Tus propiedades');

	echo '<h1 class="quitar"><a href="/mapa">Mapa</a>: Tus propiedades (<a href="/doc/mapa-de-vp">Ayuda</a>)</h1>

<br />

<table border="0" cellpadding="0" class="pol_table">
<tr>
<th colspan="2">Propiedad</th>
<th>Pos</th>
<th>Tama&ntilde;o</th>
<th>Color</th>
<th>Precio</th>
<th>Coste</th>
<th>Estado</th>
</tr>';
	$multip = 10;

	$result = mysql_query_old("SELECT *
FROM mapa
WHERE pais = '".PAIS."' AND user_ID = '" . $pol['user_ID'] . "'
ORDER BY estado ASC, time ASC", $link);
	while($r = mysqli_fetch_array($result)){

		if ($r['estado'] == 'v') { $r['color'] = 'FF0'; }

		$size_x = $r['size_x'] * $multip;
		$size_y = $r['size_y'] * $multip;

		$botones = '';
		switch ($r['estado']) {

			case 'p': 
				$prop[$r['ID']]['pos_x'] = $r['pos_x'];
				$prop[$r['ID']]['pos_y'] = $r['pos_y'];
				$prop[$r['ID']]['size_x'] = $r['size_x'];
				$prop[$r['ID']]['size_y'] = $r['size_y'];
				$prop[$r['ID']]['color'] = $r['color'];

				$estado = 'Propiedad'; 
				$botones = ' 
'.boton('Vender', '/mapa/vender/'.$r['ID']).'
'.boton('Editar', '/mapa/editar/' . $r['ID']).'
'.(($r['size_x']*$r['size_y'])>1?boton('Separar', '/accion/mapa/separar?ID='.$r['ID'], '&iquest;Seguro que quieres SEPARAR tu propiedad?').' ':'').' ' . boton('X', '/accion/mapa/eliminar?ID=' . $r['ID'], '&iquest;Seguro que quieres ELIMINAR tu propiedad?\n\nSe convertira en un solar.').'

<form action="/accion/mapa/ceder?ID='.$r['ID'].'" method="post">
<input type="submit" value="Ceder a:" /> <input type="text" name="nick" size="8" maxlength="20" value="" /></form> 
'; 
				break;

			case 'v': 
				$estado = 'En venta'; $botones = boton('Editar', '/mapa/vender/' . $r['ID']) . ' ' . boton('Cancelar venta', '/accion/mapa/cancelar-venta?ID=' . $r['ID']); 
				break;

			case 'e': 
				$prop[$r['ID']]['pos_x'] = $r['pos_x'];
				$prop[$r['ID']]['pos_y'] = $r['pos_y'];
				$prop[$r['ID']]['size_x'] = $r['size_x'];
				$prop[$r['ID']]['size_y'] = $r['size_y'];
				$prop[$r['ID']]['color'] = $r['color'];

				$botones = boton('Editar', '/mapa/editar/' . $r['ID'] . '/') . ' 
'.(($r['size_x']*$r['size_y'])>1?boton('Separar', '/accion/mapa/separar?ID='.$r['ID'], '&iquest;Seguro que quieres SEPARAR tu propiedad?').' ':'').'
' . boton('X', '/accion/mapa/eliminar?ID=' . $r['ID'], '&iquest;Seguro que quieres ELIMINAR tu propiedad?\n\nSe convertira en un solar.').'
<form action="/accion/mapa/ceder?ID='.$r['ID'].'" method="post">
<input type="submit" value="Ceder a:" /> <input type="text" name="nick" size="8" maxlength="20" value="" /></form> 
'; 
				break;
		}

		if ($r['estado'] == 'e') {

			echo '<tr>
<td align="right" valign="top">' . $r['ID'] . '</td>
<td valign="top"><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#888;border:1px solid grey;"></div></td>
<td valign="top">' . $r['pos_x'] . '-' . $r['pos_y'] . '</td>
<td valign="top">' . $r['size_x'] . 'x' . $r['size_y'] . '=' . ($r['superficie']) . '</td>
<td valign="top" colspan="3">' . $r['link'] . '</td>
<td valign="top">Estatal</td>
<td nowrap="nowrap" valign="top" align="right">' . $botones . '</td>
</tr>';

		} else {
			$prop_num++;
			$coste = ceil(($r['size_x'] * $r['size_y']) * $pol['config']['factor_propiedad']);
			$coste_total += $coste;
			$superficie += $r['size_x'] * $r['size_y'];
			echo '<tr>
<td align="right" valign="top">' . $r['ID'] . '</td>
<td valign="top"><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:' . $r['color'] . ';border:1px solid grey;"></div></td>
<td valign="top">' . $r['pos_x'] . '-' . $r['pos_y'] . '</td>
<td valign="top">' . $r['size_x'] . 'x' . $r['size_y'] . '=' . ($r['superficie']) . '</td>
<td valign="top">' . $r['color'] . '</td>
<td nowrap="nowrap" align="right" valign="top">' . pols($r['pols']) . ' '.MONEDA.'</td>
<td nowrap="nowrap" align="right" valign="top">' . pols($coste) . ' '.MONEDA.'/dia</td>
<td valign="top">' . $estado . '</td>
<td nowrap="nowrap" valign="top" align="right">' . $botones . '</td>
</tr>';
		}
	}
	if (($coste_total-$pol['config']['pols_inem']) != 0) { 
		$dias_dinero = floor($pol['pols']/($coste_total-$pol['config']['pols_inem']));
	} else { $dias_dinero = 0; }
	
	echo '<tr><td colspan="5"></td><td align="right"><b style="font-size:20px;">Total:</td><td>'.pols($coste_total).'</b> '.MONEDA.'/dia</td><td colspan="2"><em>Tienes para '.($dias_dinero<=5?'<b style="color:red;">':'<b>').$dias_dinero.'</b> d&iacute;as</em></td></tr></table>

<p>Tienes <b>' . $prop_num . '</b> propiedades, <b>' . $superficie . '</b> de superficie. Factor de propiedad: <b>' . $pol['config']['factor_propiedad'] . '</b>, Inempol: ' . pols($pol['config']['pols_inem']) . ' '.MONEDA.' (por cada dia que entres).</p>


<br />

<h2>Fusiones posibles</h2>

<table border="0" class="pol_table">';

	if ($prop) {

	// FUSION X
	foreach($prop as $ID => $d) {

		//encaje
		$next_x = $d['pos_x'] + $d['size_x'];
		
		//busqueda x
		$ex_x = '';
		foreach($prop as $ID2 => $d2) {
			if (($d2['pos_x'] == $next_x) 
				AND ($d['pos_y'] == $d2['pos_y']) 
				AND (($d['pos_y'] + $d['size_y']) == ($d2['pos_y'] + $d2['size_y']))
				AND ($ID2 != $ID)) { $ex_x = $ID2; }
		}
		
		if ($ex_x) {
			$size_x = $d['size_x'] * $multip;
			$size_y = $d['size_y'] * $multip;
			$size2_x = $prop[$ex_x]['size_x'] * $multip;
			$size2_y = $prop[$ex_x]['size_y'] * $multip;

			echo '<tr>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>+</b></td>
<td valign="top">' . $ex_x . '</td>
<td><div style="width:' . $size2_x . 'px;height:' . $size2_y . 'px; background:#' . $prop[$ex_x]['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>=</b></td>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . ($size_x + $size2_x) . 'px;height:' . $size_y . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top">' . boton('Fusionar', '/accion/mapa/fusionar?ID=' . $ID . '-' . $ex_x . '&f=x&superficie=' . ($d['size_x'] * $prop[$ex_x]['size_x'])) . '</td>
</tr>';
		}
	}


	// FUSION Y
	foreach($prop as $ID => $d) {

		//encaje
		$next_y = $d['pos_y'] + $d['size_y'];
		
		//busqueda y
		$ex_y = '';
		foreach($prop as $ID2 => $d2) {
			if (($d2['pos_y'] == $next_y) 
				AND ($d['pos_x'] == $d2['pos_x']) 
				AND (($d['pos_x'] + $d['size_x']) == ($d2['pos_x'] + $d2['size_x']))
				AND ($ID2 != $ID)) { 
				$ex_y = $ID2;  
			}
		}
		
		if ($ex_y) {
			$size_y = $d['size_y'] * $multip;
			$size_x = $d['size_x'] * $multip;
			$size2_y = $prop[$ex_y]['size_y'] * $multip;
			$size2_x = $prop[$ex_y]['size_x'] * $multip;

			echo '<tr>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>+</b></td>
<td valign="top">' . $ex_y . '</td>
<td><div style="width:' . $size2_x . 'px;height:' . $size2_y . 'px; background:#' . $prop[$ex_y]['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>=</b></td>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . $size_x . 'px;height:' . ($size_y + $size2_y) . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top">' . boton('Fusionar', '/accion/mapa/fusionar?ID=' . $ID . '-' . $ex_y . '&f=y&superficie=' . ($d['size_y'] * $prop[$ex_y]['size_y'])) . '</td>
</tr>';
		}
	}

	} //exist $prop



	echo '</table><br /><p><a href="/mapa/"><b>Ver mapa</b></a> &nbsp; <a href="/doc/mapa-de-vp/"><b>Ayuda</b></a></p>';

} elseif (($_GET[1] == 'comprar') AND ($_GET[2])) { //Comprar

	$txt_title = 'Mapa: Comprar propiedad';
	$txt_nav = array('/mapa'=>'Mapa', 'Comprar propiedad');

	for ($n=1;$n <=15;$n++) {
		$color = generar_color();
		$colores .= '<option value="' . $color . '" style="background:#' . $color . ';width:60px;">' . $color . '</option>';
	}



	echo '<h1><a href="/mapa/">Mapa</a>: Comprar propiedad:  ' . $_GET[2] . '</h1>

<form action="/accion/mapa/comprar?ID=' . $_GET[2] . '" method="post">

<ol>

<li><b>Direcci&oacute;n web</b> o <b>frase</b>:<br />
<input type="text" name="link" size="50" maxlength="70" /><br /><br /></li>

<li><b>'._('Color').':</b> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.3.3/jscolor.min.js" integrity="sha512-KVabwlnqwMHqLIONPKHQTGzW4C0dg3HEPwtTCVjzGOW508cm5Vl6qFvewK/DUbgqLPGRrMeKL3Ga3kput855HQ==" crossorigin="anonymous"></script>
Color: <input value="'.$r['color'].'" data-jscolor="" name="color">

(<a href="/mapa/comprar/' . $_GET[2] . '/">Generar m&aacute;s</a>)<br /><br /></li>

<li>' . boton('Comprar', false, false, false, $pol['config']['pols_solar']) . ' (M&aacute;s coste de 1 '.PAIS.' al dia)<br /><br /></li>

</ol>

</form>

<p><a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>';

} else {

	$cuadrado_size = 24;
	$mapa_full = true;
	include('mapa.php');

	echo '<p><button onclick="window.location=\'/mapa\';">'._('Actualizar').'</button> <button onclick="colorear(\'toggle\');" class="orange">'._('Modo').'</button> &nbsp; (<a href="/doc/mapa-de-vp">'._('Ayuda').'</a>)</p>

<table><tr><td rowspan="2" valign="top">
'.$txt_mapa.'
</td><td valign="top" colspan="2">';




	// datos graficos
	$result = mysql_query_old("SELECT mapa_vende
FROM stats WHERE pais = '".PAIS."' AND mapa_vende != 0 
ORDER BY time ASC LIMIT 500", $link);
	while($row = mysqli_fetch_array($result)){
		$dgrafico[] = $row['mapa_vende'];
	}
	if ($dgrafico) { $dgrafico_max = max($dgrafico); } else { $dgrafico_max = 0; }




echo '
<h1 style="display:inline-block;">'._('Info').'</h1>
<span><acronym title="Superficie ocupada" style="color:blue;"><b>' . round(($sup_total * 100) / $superficie_total, 1) . '%</b> '._('ocupado').'</acronym> 
<acronym title="Superficie en venta" style="color:red;"><b>' . round(($venta_total * 100) / $superficie_total, 1) . '%</b> '._('en venta').' </acronym>	
</span><br />
<img style="margin:0 0 4px 0;" src="http://chart.apis.google.com/chart?cht=lc&chs=450x110&chxt=y&chxl=0:|0|' . round($dgrafico_max / 2) . '|' . $dgrafico_max . '&chd=s:' . chart_data($dgrafico) . '&chco=0066FF&chm=B,FFFFDD,0,0,0&chf=bg,s,ffffff01|c,s,ffffff01" width="450" height="110" />
</td></tr>
<tr><td valign="top">
<h1>'._('Terratenientes').'</h1>
<p class="gris">'._('Con más propiedades').'</p><ol>';

$n = 0;
$result = mysql_query_old("SELECT SUM(superficie) AS superficie, COUNT(*) AS num,
(SELECT nick FROM users WHERE ID = mapa.user_ID LIMIT 1) AS nick,
(SELECT cargo FROM users WHERE ID = mapa.user_ID LIMIT 1) AS cargo
FROM mapa
WHERE pais = '".PAIS."' AND estado != 'e'
GROUP BY user_ID
ORDER BY superficie DESC, num ASC
LIMIT 15");
while ($row = mysqli_fetch_array($result)) {
	$n++;
	if ($n <= 3) { 
		$first = true;
		echo '<li><img width="16" height="16" src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> <b>' . crear_link($row['nick']) . ' (' . $row['superficie'] . ')</b></li>';
	} else {
		echo '<li><img width="16" height="16" src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> ' . crear_link($row['nick']) . ' (' . $row['superficie'] . ')</li>';
	}
}


	echo '</ol></td><td valign="top">
<h1>'._('Grandes propiedades').'</h1>
<p class="gris">'._('Las propiedades más extensas').'</p><ol>';

$n = 0;
$result = mysql_query_old("SELECT size_x, size_y, superficie,
(SELECT nick FROM users WHERE ID = mapa.user_ID LIMIT 1) AS nick,
(SELECT cargo FROM users WHERE ID = mapa.user_ID LIMIT 1) AS cargo
FROM mapa
WHERE pais = '".PAIS."' AND estado != 'e'
ORDER BY superficie DESC
LIMIT 15");
while ($row = mysqli_fetch_array($result)) {
	$n++;
	if ($n <= 3) { 
		$first = true;
		echo '<li><img width="16" height="16" src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> <b>' . crear_link($row['nick']) . ' ('.$row['size_x'].'x'.$row['size_y'].'=' . $row['superficie'] . ')</b></li>';
	} else {
		echo '<li><img width="16" height="16" src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> ' . crear_link($row['nick']) . ' ('.$row['size_x'].'x'.$row['size_y'].'=' . $row['superficie'] . ')</li>';
	}
}

	echo '</ol></tr></table>';
}


//THEME
if (!$txt_title) { $txt_title = _('Mapa'); $txt_nav = array('/mapa'=>_('Mapa')); }
$txt_menu = 'econ';
