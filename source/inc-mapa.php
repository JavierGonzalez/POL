<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

// Prevención de inyección
foreach ($_POST AS $nom => $val) { $_POST[$nom] = escape($val); }
foreach ($_GET  AS $nom => $val) { $_GET[$nom] = escape($val); }
foreach ($_REQUEST AS $nom => $val) { $_REQUEST[$nom] = escape($val); }


$mapa_width = $cuadrado_size * $columnas;
$mapa_height = $cuadrado_size * $filas;
$superficie_total = $columnas * $filas;

/* estado
P - propiedad	LIBRE			(propiedad, no venta)						link|nick|color|letras
V - venta		Amarillo		(propiedad, en venta, link a compra)	v|nick|pols
E - estado		Gris			(propiedad, no venta, estatal)			e|link-interno|text

S - solar			Blanco		(solar, en venta, link a compra)			null
*/

$count = 1;
$prop = '';
$m = null;
$result = mysql_query("SELECT ID, pos_x, pos_y, size_x, size_y, link, pols, color, estado, superficie, nick
FROM mapa
WHERE pais = '".PAIS."' 
ORDER BY pos_y ASC, pos_x ASC", $link);
while($r = mysql_fetch_array($result)) {

	$sup_total += $r['superficie'];

	// genera tabla array
	$m[$r['pos_x']][$r['pos_y']] = $r['ID'] . '|' . $r['size_x'] . '|' . $r['size_y'];

	//super-array javascript
	switch ($r['estado']) {
		case 'p': $info = $r['link'] . '|' .  $r['nick'] . '|' . $r['color']; break;
		case 'v': $info = 'v|' . $r['nick'] . '|' . $r['pols']; $venta_total += $r['superficie']; break;
		case 'e': if ($r['link']) { $info = 'e|' . $r['link']; } else { $info = 'e'; } break;
	}

	if ($prop) { $prop .= ',' . "\n"; }
	$prop .= $r['ID'] . ':"' . $info . '"';
}

$txt_mapa .= '
<style type="text/css">
#polmap table {
table-layout: fixed;
width:' . $mapa_width . 'px;
height:' . $mapa_height . 'px;
}

#polmap td {
background: #FFF;
height:' . $cuadrado_size . 'px;
padding:0;
margin:0;
border:1px solid #999;
font-size:15px;
color:blue;
font-weight:bold;
text-align:center;
}
#msg {position:absolute;display:none;z-index:10;}
</style>

<script type="text/javascript">
vision = "normal";
prop = new Array();
prop = {
'.$prop.'
};

function colorear(modo) {
	for (i in prop) {
		var prop_a = prop[i].split("|");
		var pa1 = prop_a[1];
		switch (prop_a[0]) {
			case "v":
				if ((vision != "normal") && (pa1 == "'.$pol['nick'].'")) { var elcolor = "#FF0000"; $("#" + i).html(prop_a[2]); } 
				else {
					if (vision != "normal") { $("#" + i).html(prop_a[2]); } else { $("#" + i).html(""); }
					var elcolor = "#FFFF00";
				} 
				break;

			case "e":
				var elcolor = "#808080";
				$("#" + i).text(pa1).css("color", "#CCC");
				break;

			default:
				if (vision == "normal") { var elcolor = "#" + prop_a[2]; } 
				else { if (pa1 == "'.$pol['nick'].'") { var elcolor = "#FF0000"; } else { var elcolor = "#AACC99"; } }
		}
		$("#" + i).css("background", elcolor);
	}
	if (vision == "normal") { vision = "comprar"; } 
	else { vision = "normal"; }
}

$(document).ready(function(){
	$("#msg").css("display","none");
	colorear("normal");
	$("#polmap td").mouseover(function(){
		var ID = $(this).attr("id");
		var amsg = prop[ID];
		if (amsg) {
			var amsg = amsg.split("|");
			switch (amsg[0]) {
				case "v": var msg = "<span style=\"color:green;\"><b>En venta</b></span><br />" + amsg[1] + " (" + ID + ")<br /><span style=\"color:blue;\"><b>" + amsg[2] + "</span> monedas</b>"; break;
				
				case "e": if (amsg[1]) { var msg = "<span style=\"color:grey;font-size:22fpx;\"><b>" + amsg[1] + "</b></span>"; } break;
				
				default: var msg = "<span style=\"color:green;\"><b>" + amsg[0] + "</b></span><br />" + amsg[1] + " (" + ID + ")"; $(this).css("cursor", "pointer");
			}
		} else { var msg = "<span style=\"color:green;\">Comprar</span><br />Solar: " + ID + "<br /> <span style=\"color:blue;\"><b>' . $pol['config']['pols_solar'] . '</span> monedas</b>"; }
		$(this).css("border", "1px solid white");
		$("#msg").html(msg).css("display", "inline");

	}).mouseout(function(){
		$("#msg").css("display","none");
		$(this).css("border", "1px solid #999");
	}).click(function () { 
		var amsg = prop[$(this).attr("id")];
		if (amsg) {
			var amsg = amsg.split("|");
			switch (amsg[0]) {
			case "v": window.location = "/mapa/compraventa/" + $(this).attr("id") + "/"; break;
			default:
				if (amsg[0]) {
					if (amsg[0].substring(0, 1) == "/") { window.location = amsg[0]; } 
					else { window.location = "http://" + amsg[0]; }
				}
			}
		} else { var ID = $(this).attr("id"); window.location = "/mapa/comprar/" + ID + "/"; }
    });
});

$(document).mousemove(function(e){
	$("#msg").css({top: e.pageY + "px", left: e.pageX + 15 + "px"});
});

</script>';
unset($prop);
$txt_mapa .= '

<div id="polmap">
<table border="0" cellpadding="0" cellspacing="0" height="' . $mapa_height . '" width="' . $mapa_width . '">';
for ($y=1;$y<=$filas;$y++) {
	$txt_mapa .= '<tr>';
	for ($x=1;$x<=$columnas;$x++) {
		while ($mapa_extra2[$x][$y]) { $x += $mapa_extra2[$x][$y]; }
		if ($m[$x][$y]) {
			$d = explode("|", $m[$x][$y]); $span = '';
			$extra = 0;
			if ($d[1] > 1) { $span .= ' colspan="' . $d[1] . '"';  $extra += $d[1] - 1; }
			if ($d[2] > 1) { $span .= ' rowspan="' . $d[2] . '"'; }
			$txt_mapa .= '<td id="' . $d[0] . '"' . $span . '></td>';
			for ($xn=1;$xn<$d[2];$xn++) {
				$mapa_extra2[$x][$y + $xn] = $d[1];
			}
			$x += $extra;
		} else {
			if ($x <= $columnas) { $txt_mapa .= '<td id="' . $x . '-' . $y . '"></td>'; }
		}
	}
	$txt_mapa .= '</tr>' . "\n";
}


$txt_mapa .= '</table></div>';

if ($mapa_full) { $txt_mapa .= '<p><a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>'; }

$txt_mapa .='
<div id="msg" class="amarillo"></div>';
?>



