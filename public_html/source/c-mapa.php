<?php 
include('inc-login.php');


function generar_color() {
	$colores_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
	$color .= $colores_a[rand(0,15)] . $colores_a[rand(0,15)] . $colores_a[rand(0,15)];
	return $color;
}

$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }


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
	while($row = mysql_fetch_array($result)){

		$txt_title = 'Comprar propiedad';
		$txt .= '<h1>Comprar propiedad ' . $_GET['b'] . '</h1>

<ol>

<li>Propiedad: <b>' . $row['ID'] . '</b><br />
Posici&oacute;n: <b>' . $row['pos_x'] . '-' . $row['pos_y'] . '</b><br />
Tama&ntilde;o: <b>' . $row['size_x'] . 'x' . $row['size_y'] . '=' . ($row['size_x'] * $row['size_y']) . '</b><br /><br /></li>

<li>' . boton('COMPRAR', '/accion.php?a=mapa&b=compraventa&ID=' . $row['ID'], false, false, $row['pols']) . '<br /><br /></li>

</ol>

<p><a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>';
	}

} elseif (($_GET['a'] == 'vender') AND ($_GET['b'])) { // VENDER

	$result = mysql_query("SELECT *
FROM ".SQL."mapa
WHERE ID = '" . $_GET['b'] . "' 
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){

		$txt_title = 'Vender propiedad';
		$txt .= '<h1>Mapa: vender propiedad ' . $_GET['b'] . '</h1>


<form action="/accion.php?a=mapa&b=vender&ID=' . $_GET['b'] . '" method="post">

<ol>

<li>Propiedad: <b>' . $row['ID'] . '</b><br />
Posici&oacute;n: <b>' . $row['pos_x'] . '-' . $row['pos_y'] . '</b><br />
Tama&ntilde;o: <b>' . $row['size_x'] . 'x' . $row['size_y'] . '=' . ($row['size_x'] * $row['size_y']) . '</b><br /><br /></li>

<li>Precio de venta: <b><input type="text" style="text-align:right;" name="pols" size="5" maxlength="4" value="' . $row['pols'] . '" /> '.MONEDA.'</b><br /><br /></li>

<li><input type="submit" value="Poner en venta" /><br /><br /></li>

</ol>

</form>

<p><a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>';

	}

} elseif (($_GET['a'] == 'editar') AND ($_GET['b'])) { // EDITAR
	$txt_title = 'Editar propiedad';

	$result = mysql_query("SELECT *
FROM ".SQL."mapa
WHERE ID = '" . $_GET['b'] . "' AND (user_ID = '" . $pol['user_ID'] . "' OR (estado = 'e' AND '1' = '" . $pol['cargos'][40] . "'))
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){


		for ($n=1;$n <=15;$n++) {
			$color = generar_color();
			$colores .= '<option value="' . $color . '" style="background:#' . $color . ';width:60px;">' . $color . '</option>';
		}
		
		$tamaño = ($row['size_x'] * $row['size_y']);
		$txt .= '<h1>Editar propiedad: ' . $_GET['b'] . '</h1>

<form action="/accion.php?a=mapa&b=editar&ID=' . $_GET['b'] . '" method="post">

<ol>

<li>Propiedad: <b>' . $row['ID'] . '</b><br />
Posici&oacute;n: <b>' . $row['pos_x'] . '-' . $row['pos_y'] . '</b><br />
Tama&ntilde;o: <b>' . $row['size_x'] . 'x' . $row['size_y'] . '=' . ($row['size_x'] * $row['size_y']) . '</b><br /><br /></li>

<li><b>Direcci&oacute;n web</b> o <b>frase</b>:<br />
<input type="text" name="link" size="50" maxlength="70" value="' . $row['link'] . '" /><br /><br /></li>

<li><b>Color:</b> 
<select name="color">
<option value="' . $row['color'] . '" style="background:#' . $row['color'] . ';">' . $row['color'] . '</option>
' . $colores . '
</select> <span style="background:#' . $row['color'] . ';height:15px;width:40px;"></span> (<a href="/mapa/editar/' . $_GET['b'] . '/">Generar m&aacute;s</a> o especificar: <input type="text" size="2" maxlength="3" name="color2" value="" />)<br /><br /></li>

<!--<li><b>Letras:</b> <input type="text" name="text" size="8" maxlength="' . $tamaño . '" value="' . $row['text'] . '" /> (opcional, letras: <b>' . $tamaño . '</b>)<br /><br /></li>-->

<li><input type="submit" value="Guardar" /><br /><br /></li>

</ol>

</form>

<p><a href="/mapa/"><b>Ver mapa</b></a> &nbsp; <a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>';
	}




} elseif ($_GET['a'] == 'propiedades') { //Propiedades

	$txt_title = 'Propiedades';
	$txt .= '<h1>Propiedades (<a href="/doc/mapa-de-pol/">Info</a>)</h1>

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
	while($row = mysql_fetch_array($result)){

		if ($row['estado'] == 'v') { $row['color'] = 'FF0'; }

		$size_x = $row['size_x'] * $multip;
		$size_y = $row['size_y'] * $multip;

		$botones = '';
		switch ($row['estado']) {

			case 'p': 
				$prop[$row['ID']]['pos_x'] = $row['pos_x'];
				$prop[$row['ID']]['pos_y'] = $row['pos_y'];
				$prop[$row['ID']]['size_x'] = $row['size_x'];
				$prop[$row['ID']]['size_y'] = $row['size_y'];
				$prop[$row['ID']]['color'] = $row['color'];

				$estado = 'Propiedad'; 
				$botones = boton('Editar', '/mapa/editar/' . $row['ID'] . '/') . ' ' . boton('Vender', '/mapa/vender/' . $row['ID'] . '/') . ' ' . boton('X', '/accion.php?a=mapa&b=eliminar&ID=' . $row['ID'], '&iquest;Seguro que quieres ELIMINAR tu propiedad?\n\nSe convertira en un solar.'); 
				break;

			case 'v': 
				$estado = 'En venta'; $botones = boton('Editar', '/mapa/vender/' . $row['ID'] . '/') . ' ' . boton('Cancelar venta', '/accion.php?a=mapa&b=cancelar-venta&ID=' . $row['ID']); 
				break;

			case 'e': 
				$prop[$row['ID']]['pos_x'] = $row['pos_x'];
				$prop[$row['ID']]['pos_y'] = $row['pos_y'];
				$prop[$row['ID']]['size_x'] = $row['size_x'];
				$prop[$row['ID']]['size_y'] = $row['size_y'];
				$prop[$row['ID']]['color'] = $row['color'];

				$botones = boton('Editar', '/mapa/editar/' . $row['ID'] . '/') . ' ' . boton('X', '/accion.php?a=mapa&b=eliminar&ID=' . $row['ID'], '&iquest;Seguro que quieres ELIMINAR tu propiedad?\n\nSe convertira en un solar.'); 
				break;
		}

		if ($row['estado'] == 'e') {

			$txt .= '<tr>
<td align="right" valign="top">' . $row['ID'] . '</td>
<td valign="top"><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#888;border:1px solid grey;"></div></td>
<td valign="top">' . $row['pos_x'] . '-' . $row['pos_y'] . '</td>
<td valign="top">' . $row['size_x'] . 'x' . $row['size_y'] . '=' . ($row['superficie']) . '</td>
<td valign="top" colspan="3">' . $row['link'] . '</td>
<td valign="top">Estatal</td>
<td nowrap="nowrap" valign="top">' . $botones . '</td>
</tr>';

		} else {
			$prop_num++;
			$coste = ceil(($row['size_x'] * $row['size_y']) * $pol['config']['factor_propiedad']);
			$coste_total += $coste;
			$superficie += $row['size_x'] * $row['size_y'];
			$txt .= '<tr>
<td align="right" valign="top">' . $row['ID'] . '</td>
<td valign="top"><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#' . $row['color'] . ';border:1px solid grey;"></div></td>
<td valign="top">' . $row['pos_x'] . '-' . $row['pos_y'] . '</td>
<td valign="top">' . $row['size_x'] . 'x' . $row['size_y'] . '=' . ($row['superficie']) . '</td>
<td valign="top">' . $row['color'] . '</td>
<td nowrap="nowrap" align="right" valign="top">' . pols($row['pols']) . ' '.MONEDA.'</td>
<td nowrap="nowrap" align="right" valign="top">' . pols($coste) . ' '.MONEDA.'/dia</td>
<td valign="top">' . $estado . '</td>
<td nowrap="nowrap" valign="top">' . $botones . '</td>
</tr>';
		}
	}


	$txt .= '<tr><td colspan="5"></td><td colspan="2" align="right"><b style="font-size:20px;">Total: ' . pols($coste_total) . '</b> '.MONEDA.'/dia</td><td colspan="2"></td></tr></table>

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
<td valign="top">' . boton('Fusionar', '/accion.php?a=mapa&b=fusionar&ID=' . $ID . '-' . $ex_x . '&f=x&superficie=' . ($d['size_x'] * $prop[$ex_x]['size_x']), '&iquest;Seguro que quieres FUSIONAR estas propiedades?\n\nNo se puede revertir.') . '</td>
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
<td valign="top">' . boton('Fusionar', '/accion.php?a=mapa&b=fusionar&ID=' . $ID . '-' . $ex_y . '&f=y&superficie=' . ($d['size_y'] * $prop[$ex_y]['size_y']), '&iquest;Seguro que quieres FUSIONAR estas propiedades?\n\nNo se puede revertir.') . '</td>
</tr>';
		}
	}

	} //exist $prop



	$txt .= '</table><br /><p><a href="/mapa/"><b>Ver mapa</b></a> &nbsp; <a href="/doc/mapa-de-pol/"><b>Ver documentaci&oacute;n</b></a></p>';

} elseif (($_GET['a'] == 'comprar') AND ($_GET['b'])) { //Comprar

	$txt_title = 'Comprar propiedad';
	for ($n=1;$n <=15;$n++) {
		$color = generar_color();
		$colores .= '<option value="' . $color . '" style="background:#' . $color . ';width:60px;">' . $color . '</option>';
	}



	$txt .= '<h1>Comprar propiedad:  ' . $_GET['b'] . '</h1>

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

<p><a href="/mapa/"><b>Ver mapa</b></a> &nbsp; <a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>';

} else {



//__________________________________________


$cuadrado_size = 20;
$columnas = 38;
$filas = 40;
$width = $cuadrado_size * $columnas;
$height = $cuadrado_size * $filas;
$superficie_total = $columnas * $filas;

/* estado
P - propiedad	LIBRE			(propiedad, no venta)						link|nick|color|letras
V - venta		Amarillo		(propiedad, en venta, link a compra)	v|nick|pols
E - estado		Gris			(propiedad, no venta, estatal)			e|link-interno|text

S - solar			Blanco		(solar, en venta, link a compra)			null
*/
// ".SQL."mapa (ID, pos_x, pos_y, size_x, size_y, user_ID, link, text, time, pols, color, estado)

$count = 1;
$result = mysql_query("SELECT ID, pos_x, pos_y, size_x, size_y, link, pols, color, estado, superficie,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."mapa.user_ID LIMIT 1) AS nick
FROM ".SQL."mapa
ORDER BY pos_y ASC, pos_x ASC", $link);
while($row = mysql_fetch_array($result)) {

	$sup_total += $row['superficie'];

	// genera tabla array
	$m[$row['pos_x']][$row['pos_y']] = $row['ID'] . '|' . $row['size_x'] . '|' . $row['size_y'];

	//super-array javascript
	switch ($row['estado']) {
		case 'p': $info = $row['link'] . '|' .  $row['nick'] . '|' . $row['color']; break;
		case 'v': $info = 'v|' . $row['nick'] . '|' . $row['pols']; $venta_total += $row['superficie']; break;
		case 'e': if ($row['link']) { $info = 'e|' . $row['link']; } else { $info = 'e'; } break;
	}

	if ($prop) { $prop .= ',' . "\n"; }
	$prop .= $row['ID'] . ':"' . $info . '"';
}

$txt .= '<h2 style="margin: 6px 0 6px 0;">Mapa de '.PAIS.' (<a href="/mapa/propiedades/">Ver tus propiedades</a>) <acronym title="Superficie ocupada" style="color:blue;">' . round(($sup_total * 100) / $superficie_total) . '%</acronym> <input type="button" value="Modo" onclick="colorear(\'toggle\');" style="margin:-8px 0 -6px 0;height:26px;padding:0;" /> <acronym title="Superficie en venta" style="color:#F0F000;">' . round(($venta_total * 100) / $superficie_total) . '%</acronym></h2>';

$txt_header .= '
<style type="text/css">
#polm table {
table-layout: fixed;
width:' . $width . 'px;
height:' . $height . 'px;
}

#polm td {
height:' . $cuadrado_size . 'px;
padding:0;
margin:0;
border:1px solid grey;
font-size:15px;
color:blue;
font-weight:bold;
text-align:center;
}
#msg {position:absolute;}
</style>

<script type="text/javascript">
vision = "normal";
prop = new Array();
prop = {
' . $prop . '
};

function colorear(modo) {
	for (i in prop) {
		var prop_a = prop[i].split("|");
		if (prop_a[0] == "v") {
			if ((vision != "normal") && (prop_a[1] == "' . $pol['nick'] . '")) {
				var elcolor = "#FF0000"; $("#" + i).html(prop_a[2]);
			} else {
				if (vision != "normal") { $("#" + i).html(prop_a[2]); } else { $("#" + i).html(""); }
				var elcolor = "#FFFF00";
			} 
		} else if (prop_a[0] == "e") {
			var elcolor = "#808080";
			$("#" + i).text(prop_a[1]);
			$("#" + i).css("color", "#CCC");
		} else {
			if (vision == "normal") {
				var elcolor = "#" + prop_a[2].substring(0, 1) + prop_a[2].substring(0, 1) + prop_a[2].substring(1, 2) + prop_a[2].substring(1, 2) + prop_a[2].substring(2, 3) + prop_a[2].substring(2, 3);
			} else { 
				if (prop_a[1] == "' . $pol['nick'] . '") { var elcolor = "#FF0000"; } else { var elcolor = "#AACC99"; } 
			}
		}
		$("#" + i).css("background", elcolor);
	}
	if (vision == "normal") { vision = "comprar"; } 
	else { vision = "normal"; }

}

$(document).ready(function(){
	$("#msg").css("display","none");
	colorear("normal");
	$("#polm td").mouseover(function(){
		var ID = $(this).attr("id");
		var amsg = prop[ID];
		if (amsg) {
			var amsg = amsg.split("|");
			switch (amsg[0]) {
				case "v": var msg = "<span style=\"color:green;\"><b>En venta</b></span><br />" + amsg[1] + " (" + ID + ")<br /><span style=\"color:blue;\"><b>" + amsg[2] + "</span> '.MONEDA_NOMBRE.'</b>"; break;
				case "e": if (amsg[1]) { var msg = "<span style=\"color:grey;font-size:22fpx;\"><b>" + amsg[1] + "</b></span>"; } break;
				default: var msg = "<span style=\"color:green;\"><b>" + amsg[0] + "</b></span><br />" + amsg[1] + " (" + ID + ")";
			}
		} else { var msg = "<span style=\"color:green;\">Comprar</span><br />Solar: " + ID + "<br /> <span style=\"color:blue;\"><b>' . $pol['config']['pols_solar'] . '</span> '.MONEDA_NOMBRE.'</b>"; }
		$(this).css("border", "1px solid white");
		$("#msg").html(msg).css("display", "inline");

	}).mouseout(function(){
		$("#msg").css("display","none");
		$(this).css("border", "1px solid grey");
	}).click(function () { 
		var amsg = prop[$(this).attr("id")];
		if (amsg) {
			var amsg = amsg.split("|");
			switch (amsg[0]) {
			case "v": window.location = "http://'.HOST.'/mapa/compraventa/" + $(this).attr("id") + "/"; break;
			default:
				if (amsg[0]) {
					if (amsg[0].substring(0, 1) == "/") { window.location = "http://'.HOST.'" + amsg[0]; } 
					else { window.location = "http://" + amsg[0]; }
				}
			}
		} else { var ID = $(this).attr("id"); window.location = "http://'.HOST.'/mapa/comprar/" + ID + "/"; }
    });
});

$(document).mousemove(function(e){
	$("#msg").css({top: e.pageY + "px", left: e.pageX + 15 + "px"});
});

</script>';
unset($prop);
//
$txt .= '

<div id="polm">
<table border="0" cellpadding="0" cellspacing="0" height="' . $height . '" width="' . $width . '">';
for ($y=1;$y<=$filas;$y++) {
	$txt .= '<tr>';
	for ($x=1;$x<=$columnas;$x++) {
		while ($extra2[$x][$y]) { $x += $extra2[$x][$y]; }
		if ($m[$x][$y]) {
			$d = explode("|", $m[$x][$y]); $span = '';
			$extra = 0;
			if ($d[1] > 1) { $span .= ' colspan="' . $d[1] . '"';  $extra += $d[1] - 1; }
			if ($d[2] > 1) { $span .= ' rowspan="' . $d[2] . '"'; }
			$txt .= '<td id="' . $d[0] . '"' . $span . '></td>';
			for ($xn=1;$xn<$d[2];$xn++) {
				$extra2[$x][$y + $xn] = $d[1];
			}
			$x += $extra;
		} else {
			if ($x <= $columnas) { $txt .= '<td id="' . $x . '-' . $y . '"></td>'; }
		}
	}
	$txt .= '</tr>' . "\n";
}


$txt .= '</table></div>

<div id="msg" class="amarillo"></div>';





}


//THEME
if (!$txt_title) { $txt_title = 'Mapa'; }
include('theme.php');
?>
