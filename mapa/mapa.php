<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




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
$color = 'white';
$max_altura;
$nivel = 1;

$result = mysql_query_old("SELECT MAX(COALESCE(altura_maxima,1)) as altura_maxima
FROM mapa_barrios", $link);
while($r = mysqli_fetch_array($result)) {
	$max_altura = $r['altura_maxima'];
}

$result = mysql_query_old("SELECT ID, pos_x, pos_y, size_x, size_y, link, pols, color, estado, superficie, nick
FROM mapa
WHERE pais = '".PAIS."' 
ORDER BY pos_y ASC, pos_x ASC", $link);
while($r = mysqli_fetch_array($result)) {

	$sup_total += $r['superficie'];

	// genera tabla array
	$m[$r['pos_x']][$r['pos_y']] = $r['ID'] . '|' . $r['size_x'] . '|' . $r['size_y'];
	$orientacion = 'H';
	if ($r['size_y'] > $r['size_x']){
		$orientacion = 'V';
	}
	//super-array javascript
	switch ($r['estado']) {
		case 'p': $info = $r['link'] . '|' .  $r['nick'] . '|' . $r['color']; break;
		case 'v': $info = 'v|' . $r['nick'] . '|' . $r['pols']; $venta_total += $r['superficie']; break;
		case 'e': 
			if ($r['link']) { 
				$info = 'e|' . $r['link']; 
			} else { 
				$info = 'e|'; 
			} 
			if ($r['color']){
				$color = $r['color'];
			}
		break;
	}

	$info .= "|" .$orientacion;
	
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
font-size:12px;
color:'.$color.';
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

var columnas = '.$columnas.' ;
var filas = '.$filas.';

function colorear(modo) {
	if (altura_actual > 1){
		$("#polmap td").html("X");
	}

	for (i in prop) {
		var prop_a = prop[i].split("|");
		var pa1 = prop_a[1];
		$("#" + i).html("");
		$("#" + i).css("color: black");
		$("#" + i).css("background: white");

		switch (prop_a[0]) {
			case "v":
				if ((vision != "normal") && (pa1 == "'.$pol['nick'].'")) { var elcolor = "#FF0000"; $("#" + i).html(prop_a[2]); } 
				else {
					if (vision != "normal") { $("#" + i).html(prop_a[2]); } else { $("#" + i).html(""); }
					var elcolor = "#FFFF00";
				} 
				break;

                case "b":
                    var elcolor = prop_a[2];
                    $("#" + i).html(pa1);
                    $("#" + i).css("overflow", "hidden");
                    if (prop_a[3] == "V"){
						$("#" + i).html("<span style=\"writing-mode: vertical-rl\">"+pa1+"</span>");
                        $("#" + i).css("writing-mode", "tb-rl");
                    }
                    break;

                case "e":
                    var elcolor = "#808080";
                    $("#" + i).html(pa1);
                    $("#" + i).css("white-space", "nowrap");
                    $("#" + i).css("overflow", "hidden");
                    if (prop_a[2] == "V"){
						$("#" + i).html("<span style=\"writing-mode: vertical-rl\">"+pa1+"</span>");
                        $("#" + i).css("writing-mode", "tb-rl");
                    }
                    break;

			default:
				if (vision == "normal") { 
					var elcolor = prop_a[2]; 
				} 
				else { if (pa1 == "'.$pol['nick'].'") { var elcolor = "#FF0000"; } else { var elcolor = "#AACC99"; } }
		}
		$("#" + i).css("background", elcolor);
	}
	if (vision == "normal") { vision = "comprar"; } 
	else { vision = "normal"; }
}


var maxima_altura = '.$max_altura.'
var altura_actual = 1;

$(document).ready(function(){
	if (altura_actual >= maxima_altura){
		$("#siguienteNivel").hide();
	}
	
	$("#anteriorNivel").hide();



	$("#msg").css("display","none");
	colorear("normal");
	inicializarTabla();
	bloqueoPorNivel();
});

function bloqueoPorNivel(){
	if (altura_actual == 1){
		return;
	}
	for (x=1;x<=filas;x++){
		for (y=1;y<=columnas;y++){
			$("#"+x+"-"+y).css("background","white");
			$("#"+x+"-"+y).css("color","red");
			$("#"+x+"-"+y).css("font-weight","bolder");
			$("#"+x+"-"+y).html("X");
		}
	}
}

function inicializarTabla(){

	$("#polmap td").mouseover(function(){
		var ID = $(this).attr("id");
		var amsg = prop[ID];
		if (amsg) {
			var amsg = amsg.split("|");
			switch (amsg[0]) {
				case "v": var msg = "<span style=\"color:green;\"><b>En venta</b></span><br />" + amsg[1] + " (" + ID + ")<br /><span style=\"color:blue;\"><b>" + amsg[2] + "</span> monedas</b>"; break;
				
				case "b":
				case "e": 
					if (amsg[1]) { 
						var msg = "<span style=\"color:grey;font-size:22fpx;\"><b>" + amsg[1] + "</b></span>"; 
					}else{
						var msg = "<span style=\"color:grey;font-size:22fpx;\"><b>" + amsg[0] + "</b></span>"; 
					}
					break;
				
				default: var msg = "<span style=\"color:green;\"><b>" + amsg[0] + "</b></span><br />" + amsg[1] + " (" + ID + ")"; $(this).css("cursor", "pointer");
			}
		} else if (altura_actual == 1){
			var msg = "<span style=\"color:green;\">Comprar</span><br />Solar: " + ID + "<br /> <span style=\"color:blue;\"><b>' . $pol['config']['pols_solar'] . '</span> monedas</b>"; 
		} 
		$(this).css("border", "1px solid white");
		if (msg != undefined){
			$("#msg").html(msg);
			$("#msg").css("display", "inline");
		}

	}).mouseout(function(){
		$("#msg").css("display","none");
		$(this).css("border", "1px solid #999");
	}).click(function () { 
		var amsg = prop[$(this).attr("id")];
		if (amsg) {
			var amsg = amsg.split("|");
			switch (amsg[0]) {
			case "v": window.location = "/mapa/compraventa/" + $(this).attr("id") + "/"; break;
			case "e": break;
			default:
				if (amsg[0]) {
					if (amsg[0].substring(0, 1) == "/") { window.location = amsg[0]; } 
					else { window.location = "http://" + amsg[0]; }
				}
			}
		} else { var ID = $(this).attr("id"); window.location = "/mapa/comprar/" + ID + "/"; }
    });
}

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


$txt_mapa .= '</table>

</div>';

if ($mapa_full) { $txt_mapa .= '<p><a href="/mapa/propiedades/"><b>Ver tus propiedades</b></a></p>'; }

$txt_mapa .= '
<div>
	<script>
		//Con valor 1 ver parcelas con valor 2 se ven barrios
		var barriosParcelasMode = 1;
		function verBarriosParcelas(){
			if (barriosParcelasMode == 1){
				barriosParcelasMode=2;
				$("#verBarriosParcelas").html("Parcelas");
			}else{
				barriosParcelasMode=1;
				$("#verBarriosParcelas").html("Barrios");
			}
			peticionAjax(barriosParcelasMode);
		}

		function siguienteNivel(){
			altura_actual++;

			barriosParcelasMode=1;
			$("#verBarriosParcelas").html("Barrios");

			peticionAjax(1, altura_actual);

			actualizaIndicadoresNivel(altura_actual);
		}

		function anteriorNivel(){
			altura_actual--;
			peticionAjax(1, altura_actual);

			barriosParcelasMode=1;
			$("#verBarriosParcelas").html("Barrios");

			actualizaIndicadoresNivel(altura_actual);

		}

		function actualizaIndicadoresNivel(altura_actual){
			if (altura_actual < maxima_altura){
				$("#siguienteNivel").show();
				$("#siguienteNivel").html("Nivel "+(altura_actual+1));
			}else{
				$("#siguienteNivel").hide();
			}
			if (altura_actual > 2){
				$("#anteriorNivel").show();
				$("#anteriorNivel").html("Nivel "+(altura_actual-1));
			}else if (altura_actual == 2){
				$("#anteriorNivel").show();
				$("#anteriorNivel").html("Suelo");
			}else{
				$("#anteriorNivel").hide();
			}

		}

		function peticionAjax(tipoMapa, nivel=altura_actual){
			$.post("/mapa/ajax", { tipo_mapa: tipoMapa, nivel: nivel }, 
			function(data) { 
				vision = "normal";
				$("#polmap").html(data.mapa);
				valprop = JSON.parse(data.prop.replace("{,","{"));
				prop = valprop;

				colorear("normal");
				inicializarTabla();
				bloqueoPorNivel();
			}, "json");
		}

	</script>
	<span style="float: left">
		<a href="javascript:anteriorNivel();" id="anteriorNivel">Suelo</a>
		<a href="javascript:siguienteNivel();" id="siguienteNivel">Nivel 2</a>
	</span>

	<span style="float: right">
		<a href="javascript:verBarriosParcelas();" id="verBarriosParcelas">Barrios</a>
	</span>
</div>';

$txt_mapa .='
<div id="msg" class="amarillo"></div>';



