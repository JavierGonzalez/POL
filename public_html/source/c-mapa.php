<?php 
include('inc-login.php');


function generar_color() {
	$colores_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
	$color .= $colores_a[rand(0,15)] . $colores_a[rand(0,15)] . $colores_a[rand(0,15)];
	return $color;
}

$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }


// load user cargos
$pol['cargos'] = cargos(); // 40 arquitecto

/* estado
P - propiedad	LIBRE			(propiedad, no venta)						link|nick|color
V - venta		Amarillo		(propiedad, en venta, link a compra)	v|id|nick|pols
E - estado		Gris			(propiedad, no venta, estatal)			e|link-interno|text

S - solar			Blanco		(solar, en venta, link a compra)			null
*/

// ".SQL."mapa (ID, pos_x, pos_y, size_x, size_y, user_ID, link, text, time, pols, color, estado)


if (($_GET['a'] == 'compraventa') AND ($_GET['b'])) { //Comprar

		$result = mysql_query("SELECT *
FROM ".SQL."mapa
WHERE ID = '" . $_GET['b'] . "' AND estado = 'v'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		$txt_title = 'Mapa: Comprar propiedad';
		$txt_nav = array('/mapa'=>'Mapa', 'Comprar propiedad '.$_GET['b']);

		$txt .= '<h1 class="quitar"><a href="/mapa/">Mapa</a>: Comprar propiedad ' . $_GET['b'] . '</h1>

<ol>

<li>Propiedad: <b>' . $r['ID'] . '</b><br />
Posici&oacute;n: <b>' . $r['pos_x'] . '-' . $r['pos_y'] . '</b><br />
Tama&ntilde;o: <b>' . $r['size_x'] . 'x' . $r['size_y'] . '=' . ($r['size_x'] * $r['size_y']) . '</b><br />
Coste: <b>'.pols(round($r['pols'] / ($r['size_x'] * $r['size_y']))).'</b> <img src="'.IMG.'varios/m.gif" alt="monedas" /> por cuadrado<br /><br /></li>

<li>' . boton('COMPRAR', '/accion.php?a=mapa&b=compraventa&ID=' . $r['ID'], false, false, $r['pols']) . '<br /><br /></li>

</ol>

<p><a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>';
	}

} elseif (($_GET['a'] == 'vender') AND ($_GET['b'])) { // VENDER

	$result = mysql_query("SELECT *
FROM ".SQL."mapa
WHERE ID = '" . $_GET['b'] . "' 
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		$txt_title = 'Mapa: Vender propiedad';
		$txt_nav = array('/mapa'=>'Mapa', 'Vender propiedad '.$_GET['b']);
		$txt .= '<h1 class="quitar"><a href="/mapa/">Mapa</a>: Vender propiedad ' . $_GET['b'] . '</h1>


<form action="/accion.php?a=mapa&b=vender&ID=' . $_GET['b'] . '" method="post">

<ol>

<li>Propiedad: <b>' . $r['ID'] . '</b><br />
Posici&oacute;n: <b>' . $r['pos_x'] . '-' . $r['pos_y'] . '</b><br />
Tama&ntilde;o: <b>' . $r['size_x'] . 'x' . $r['size_y'] . '=' . ($r['size_x'] * $r['size_y']) . '</b><br /><br /></li>

<li>Precio de venta: <b><input type="text" style="text-align:right;" name="pols" size="5" maxlength="4" value="' . $r['pols'] . '" /> '.MONEDA.'</b><br /><br /></li>

<li><input type="submit" value="Poner en venta" /><br /><br /></li>

</ol>

</form>

<p><a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>';

	}

} elseif (($_GET['a'] == 'editar') AND ($_GET['b'])) { // EDITAR
	$txt_title = 'Mapa: Editar propiedad';
	$txt_nav = array('/mapa'=>'Mapa', 'Editar propiedad');

	$result = mysql_query("SELECT *
FROM ".SQL."mapa
WHERE ID = '" . $_GET['b'] . "' AND (user_ID = '" . $pol['user_ID'] . "' OR (estado = 'e' AND '1' = '" . $pol['cargos'][40] . "'))
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){


		for ($n=1;$n <=15;$n++) {
			$color = generar_color();
			$colores .= '<option value="' . $color . '" style="background:#' . $color . ';width:60px;">' . $color . '</option>';
		}
		
		$tamaño = ($r['size_x'] * $r['size_y']);
		$txt .= '<h1><a href="/mapa/">Mapa</a>: Editar propiedad: ' . $_GET['b'] . '</h1>

<form action="/accion.php?a=mapa&b=editar&ID=' . $_GET['b'] . '" method="post">

<ol>

<li>Propiedad: <b>' . $r['ID'] . '</b><br />
Posici&oacute;n: <b>' . $r['pos_x'] . '-' . $r['pos_y'] . '</b><br />
Tama&ntilde;o: <b>' . $r['size_x'] . 'x' . $r['size_y'] . '=' . ($r['size_x'] * $r['size_y']) . '</b><br /><br /></li>

<li><b>Direcci&oacute;n web</b> o <b>frase</b>:<br />
<input type="text" name="link" size="50" maxlength="70" value="' . $r['link'] . '" /><br /><br /></li>

<li><b>Color:</b> 
<select name="color">
<option value="' . $r['color'] . '" style="background:#' . $r['color'] . ';">' . $r['color'] . '</option>
' . $colores . '
</select> <span style="background:#' . $r['color'] . ';height:15px;width:40px;"></span> (<a href="/mapa/editar/' . $_GET['b'] . '/">Generar m&aacute;s</a> o especificar: <input type="text" size="2" maxlength="3" name="color2" value="" />)<br /><br /></li>

<!--<li><b>Letras:</b> <input type="text" name="text" size="8" maxlength="' . $tamaño . '" value="' . $r['text'] . '" /> (opcional, letras: <b>' . $tamaño . '</b>)<br /><br /></li>-->

<li><input type="submit" value="Guardar" /><br /><br /></li>

</ol>

</form>

<p><a href="/mapa/"><b>Ver mapa</b></a> &nbsp; <a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>';
	}




} elseif ($_GET['a'] == 'propiedades') { //Propiedades

	$txt_title = 'Mapa: Tus propiedades';
	$txt_nav = array('/mapa'=>'Mapa', 'Tus propiedades');

	$txt .= '<h1 class="quitar"><a href="/mapa/">Mapa</a>: Tus propiedades (<a href="/doc/mapa-de-vp/">Ayuda</a>)</h1>

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
	
	$result = mysql_query("SELECT *
FROM ".SQL."mapa
WHERE user_ID = '" . $pol['user_ID'] . "' OR (estado = 'e' AND '1' = '" . $pol['cargos'][40] . "')
ORDER BY estado ASC, time ASC", $link);
	while($r = mysql_fetch_array($result)){

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
'.boton('Vender', '/mapa/vender/'.$r['ID'].'/').'
'.boton('Editar', '/mapa/editar/' . $r['ID'] . '/').'
'.(($r['size_x']*$r['size_y'])>1?boton('Separar', '/accion.php?a=mapa&b=separar&ID='.$r['ID'], '&iquest;Seguro que quieres SEPARAR tu propiedad?').' ':'').' ' . boton('X', '/accion.php?a=mapa&b=eliminar&ID=' . $r['ID'], '&iquest;Seguro que quieres ELIMINAR tu propiedad?\n\nSe convertira en un solar.').'

<form action="/accion.php?a=mapa&b=ceder&ID='.$r['ID'].'" method="post">
<input type="submit" value="Ceder a:" /> <input type="text" name="nick" size="8" maxlength="20" value="" /></form> 
'; 
				break;

			case 'v': 
				$estado = 'En venta'; $botones = boton('Editar', '/mapa/vender/' . $r['ID'] . '/') . ' ' . boton('Cancelar venta', '/accion.php?a=mapa&b=cancelar-venta&ID=' . $r['ID']); 
				break;

			case 'e': 
				$prop[$r['ID']]['pos_x'] = $r['pos_x'];
				$prop[$r['ID']]['pos_y'] = $r['pos_y'];
				$prop[$r['ID']]['size_x'] = $r['size_x'];
				$prop[$r['ID']]['size_y'] = $r['size_y'];
				$prop[$r['ID']]['color'] = $r['color'];

				$botones = boton('Editar', '/mapa/editar/' . $r['ID'] . '/') . ' 
'.(($r['size_x']*$r['size_y'])>1?boton('Separar', '/accion.php?a=mapa&b=separar&ID='.$r['ID'], '&iquest;Seguro que quieres SEPARAR tu propiedad?').' ':'').'
' . boton('X', '/accion.php?a=mapa&b=eliminar&ID=' . $r['ID'], '&iquest;Seguro que quieres ELIMINAR tu propiedad?\n\nSe convertira en un solar.').'
<form action="/accion.php?a=mapa&b=ceder&ID='.$r['ID'].'" method="post">
<input type="submit" value="Ceder a:" /> <input type="text" name="nick" size="8" maxlength="20" value="" /></form> 
'; 
				break;
		}

		if ($r['estado'] == 'e') {

			$txt .= '<tr>
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
			$txt .= '<tr>
<td align="right" valign="top">' . $r['ID'] . '</td>
<td valign="top"><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#' . $r['color'] . ';border:1px solid grey;"></div></td>
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
	
	$txt .= '<tr><td colspan="5"></td><td align="right"><b style="font-size:20px;">Total:</td><td>'.pols($coste_total).'</b> '.MONEDA.'/dia</td><td colspan="2"><em>Tienes para '.($dias_dinero<=5?'<b style="color:red;">':'<b>').$dias_dinero.'</b> d&iacute;as</em></td></tr></table>

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

			$txt .= '<tr>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>+</b></td>
<td valign="top">' . $ex_x . '</td>
<td><div style="width:' . $size2_x . 'px;height:' . $size2_y . 'px; background:#' . $prop[$ex_x]['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>=</b></td>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . ($size_x + $size2_x) . 'px;height:' . $size_y . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top">' . boton('Fusionar', '/accion.php?a=mapa&b=fusionar&ID=' . $ID . '-' . $ex_x . '&f=x&superficie=' . ($d['size_x'] * $prop[$ex_x]['size_x'])) . '</td>
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

			$txt .= '<tr>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>+</b></td>
<td valign="top">' . $ex_y . '</td>
<td><div style="width:' . $size2_x . 'px;height:' . $size2_y . 'px; background:#' . $prop[$ex_y]['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>=</b></td>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . $size_x . 'px;height:' . ($size_y + $size2_y) . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top">' . boton('Fusionar', '/accion.php?a=mapa&b=fusionar&ID=' . $ID . '-' . $ex_y . '&f=y&superficie=' . ($d['size_y'] * $prop[$ex_y]['size_y'])) . '</td>
</tr>';
		}
	}

	} //exist $prop



	$txt .= '</table><br /><p><a href="/mapa/"><b>Ver mapa</b></a> &nbsp; <a href="/doc/mapa-de-vp/"><b>Ayuda</b></a></p>';

} elseif (($_GET['a'] == 'comprar') AND ($_GET['b'])) { //Comprar

	$txt_title = 'Mapa: Comprar propiedad';
	$txt_nav = array('/mapa'=>'Mapa', 'Comprar propiedad');

	for ($n=1;$n <=15;$n++) {
		$color = generar_color();
		$colores .= '<option value="' . $color . '" style="background:#' . $color . ';width:60px;">' . $color . '</option>';
	}



	$txt .= '<h1><a href="/mapa/">Mapa</a>: Comprar propiedad:  ' . $_GET['b'] . '</h1>

<form action="/accion.php?a=mapa&b=comprar&ID=' . $_GET['b'] . '" method="post">

<ol>

<li><b>Direcci&oacute;n web</b> o <b>frase</b>:<br />
<input type="text" name="link" size="50" maxlength="70" /><br /><br /></li>

<li><b>Color:</b> 
<select name="color">
' . $colores . '
</select> (<a href="/mapa/comprar/' . $_GET['b'] . '/">Generar m&aacute;s</a>)<br /><br /></li>

<li>' . boton('Comprar', false, false, false, $pol['config']['pols_solar']) . ' (M&aacute;s coste de 1 '.PAIS.' al dia)<br /><br /></li>

</ol>

</form>

<p><a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>';

} else {

	$cuadrado_size = 22;
	$mapa_full = true;
	include('inc-mapa.php');

	$txt .= '<h1 style="margin: 6px 0 6px 0;">Mapa: &nbsp; <input type="button" value="Actualizar" onclick="window.location=\'/mapa/\';" style="margin:-8px 0 -6px 0;padding:0;" /> <input type="button" value="Modo" onclick="colorear(\'toggle\');" style="margin:-8px 0 -6px 0;padding:0;" /> &nbsp; (<a href="/doc/mapa-de-vp/">Ayuda</a>) &nbsp;</h1>
<table><tr><td rowspan="2">
'.$txt_mapa.'
</td><td valign="top" colspan="2">';




	// datos graficos
	$result = mysql_query("SELECT mapa_vende
FROM stats WHERE pais = '".PAIS."' AND mapa_vende != 0 
ORDER BY time ASC LIMIT 500", $link);
	while($row = mysql_fetch_array($result)){
		$dgrafico[] = $row['mapa_vende'];
	}
	if ($dgrafico) { $dgrafico_max = max($dgrafico); } else { $dgrafico_max = 0; }




$txt .= '
<h1 style="display:inline-block;">Info</h1>
<span><acronym title="Superficie ocupada" style="color:blue;"><b>' . round(($sup_total * 100) / $superficie_total, 1) . '%</b> ocupado</acronym> 
<acronym title="Superficie en venta" style="color:red;"><b>' . round(($venta_total * 100) / $superficie_total, 1) . '%</b> en venta </acronym>	
</span><br />
<img style="margin:0 0 4px 0;" src="http://chart.apis.google.com/chart?cht=lc&chs=450x110&chxt=y&chxl=0:|0|' . round($dgrafico_max / 2) . '|' . $dgrafico_max . '&chd=s:' . chart_data($dgrafico) . '&chco=0066FF&chm=B,FFFFDD,0,0,0&chf=bg,s,ffffff01|c,s,ffffff01" width="450" height="110" />
</td></tr>
<tr><td valign="top">
<h1>Terratenientes</h1>
<p class="gris">Con m&aacute;s propiedades</p><ol>';

$n = 0;
$result = mysql_query("SELECT SUM(superficie) AS superficie, COUNT(*) AS num,
(SELECT nick FROM users WHERE ID = ".SQL."mapa.user_ID LIMIT 1) AS nick,
(SELECT cargo FROM users WHERE ID = ".SQL."mapa.user_ID LIMIT 1) AS cargo
FROM ".SQL."mapa
WHERE estado != 'e'
GROUP BY user_ID
ORDER BY superficie DESC, num ASC
LIMIT 15");
while ($row = mysql_fetch_array($result)) {
	$n++;
	if ($n <= 3) { 
		$first = true;
		$txt .= '<li><img width="16" height="16" src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> <b>' . crear_link($row['nick']) . ' (' . $row['superficie'] . ')</b></li>';
	} else {
		$txt .= '<li><img width="16" height="16" src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> ' . crear_link($row['nick']) . ' (' . $row['superficie'] . ')</li>';
	}
}


	$txt .= '</ol></td><td valign="top">
<h1>Grandes propiedades</h1>
<p class="gris">Las propiedades m&aacute;s extensas</p><ol>';

$n = 0;
$result = mysql_query("SELECT size_x, size_y, superficie,
(SELECT nick FROM users WHERE ID = ".SQL."mapa.user_ID LIMIT 1) AS nick,
(SELECT cargo FROM users WHERE ID = ".SQL."mapa.user_ID LIMIT 1) AS cargo
FROM ".SQL."mapa
WHERE estado != 'e'
ORDER BY superficie DESC
LIMIT 15");
while ($row = mysql_fetch_array($result)) {
	$n++;
	if ($n <= 3) { 
		$first = true;
		$txt .= '<li><img width="16" height="16" src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> <b>' . crear_link($row['nick']) . ' ('.$row['size_x'].'x'.$row['size_y'].'=' . $row['superficie'] . ')</b></li>';
	} else {
		$txt .= '<li><img width="16" height="16" src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> ' . crear_link($row['nick']) . ' ('.$row['size_x'].'x'.$row['size_y'].'=' . $row['superficie'] . ')</li>';
	}
}

	$txt .= '</ol></tr></table>';
}


//THEME
if (!$txt_title) { $txt_title = 'Mapa'; $txt_nav = array('/mapa'=>'Mapa'); }
$txt_menu = 'econ';
include('theme.php');
?>
