<?php
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
FROM ".SQL."mapa
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

if ($mapa_full) {
	$txt_mapa .= '<h1 style="margin: 6px 0 6px 0;">Mapa: &nbsp; <input type="button" value="Actualizar" onclick="window.location=\'/mapa/\';" style="margin:-8px 0 -6px 0;padding:0;" /> <input type="button" value="Modo" onclick="colorear(\'toggle\');" style="margin:-8px 0 -6px 0;padding:0;" /> &nbsp; <acronym title="Superficie ocupada" style="color:blue;">' . round(($sup_total * 100) / $superficie_total) . '% ocupado</acronym> <acronym title="Superficie en venta" style="color:red;">' . round(($venta_total * 100) / $superficie_total) . '% en venta </acronym> &nbsp; (<a href="/doc/mapa-de-vp/">Ayuda</a>)</h1>';
}

$txt_mapa .= '
<style type="text/css">
#polm table {
table-layout: fixed;
width:' . $mapa_width . 'px;
height:' . $mapa_height . 'px;
}

#polm td {
height:' . $cuadrado_size . 'px;
padding:0;
margin:0;
border:1px solid #999;
font-size:15px;
color:blue;
font-weight:bold;
text-align:center;
}
#msg {position:absolute;display:none;}
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
		$(this).css("border", "1px solid #999");
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
$txt_mapa .= '

<div id="polm">
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




