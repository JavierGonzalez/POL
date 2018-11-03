<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

$centro = '40.42,-3.70'; // Madrid (Plaza del Sol)


// GEN
if (!isset($pol['user_ID'])) {
	$txt .= '<p>'._('El mapa de ciudadanos solo está disponible para ciudadanos').'.</p>';


} elseif ($_GET['a'] == 'vecinos') {
	include_once('inc-functions-accion.php');

	$user_y = explodear(',', $centro, 0); $user_x = explodear(',', $centro, 1);
	$result = mysql_query("SELECT x, y FROM users WHERE ID = '".$pol['user_ID']."' AND x IS NOT NULL LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { $user_x = $r['x']; $user_y = $r['y']; $geo = true; }

	if ($geo) {
		$txt .= '<p>'._('Los ciudadanos más cercanos a ti').'.</p>';
	} else {
		$txt .= '<p>'._('No estás geolocalizado').' '.boton(_('Geolocalízate'), '/geolocalizacion/fijar', false, 'large red').'</p>';
	}
	
	
	$txt .= '<table border="0">
<tr>
<th colspan="2">'._('Ciudadano').'</th>
<th>'._('Distancia').'</th>
<th>'._('Último acceso').'</th>
</tr>';
	$result = mysql_query("SELECT ID, nick, pais, avatar, fecha_last, x, y, POW(x-".$user_x.",2)+POW(y-".$user_y.",2) AS dist FROM users WHERE estado = 'ciudadano' AND x IS NOT NULL ORDER BY dist ASC LIMIT 100", $link);
	while ($r = mysql_fetch_array($result)) { 
		$txt .= '<tr>
<td>'.($r['avatar']=='true'?avatar($r['ID'], 40):'').'</td>
<td><b style="font-size:16px;">'.crear_link($r['nick']).'</b></td>
<td align="right" style="font-size:16px;">'.distancia($user_x, $user_y, $r['x'], $r['y'], 0).' km</td>
<td align="right" class="gris">'.timer($r['fecha_last']).'</td>
<td>'.($r['ID']!=$pol['user_ID']?boton(_('Enviar mensaje'), '/msg/'.$r['nick'], false, 'blue'):'').'</td>
</tr>';
	}
	$txt .= '</table>';


} elseif ($_GET['a'] == 'fijar') {
	
	$center['y'] = explodear(',', $centro, 0); $center['x'] = explodear(',', $centro, 1);
	$result = mysql_query("SELECT x, y FROM users WHERE ID = '".$pol['user_ID']."' AND x IS NOT NULL LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 
		$center['x'] = $r['x']; 
		$center['y'] = $r['y'];
	}

	$txt .= '
<script type="text/javascript" src="//maps.google.com/maps?file=api&v=2&key="></script>
<script type="text/javascript">

$(document).ready(function(){ initialize(); });

function initialize() {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));
		var center = new GLatLng('.$center['y'].', '.$center['x'].');
		map.setCenter(center, 10);

		var marker = new GMarker(center, {draggable: false});
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());

		GEvent.addListener(marker, "dragstart", function() {
			map.closeInfoWindow();
		});

		GEvent.addListener(map, "click", function (overlay,point){
			if (point){
				marker.setPoint(point);
				$("#geo_x").attr("value", roundNumber(point.lng(), 2));
				$("#geo_y").attr("value", roundNumber(point.lat(), 2));
			}
		});

		map.addOverlay(marker);
	}
}


function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}
</script>

<div id="map" style="width:100%;height:400px"></div>

<form action="'.accion_url().'a=geolocalizacion&b=add" method="POST">

<table border="0" width="100%">
<tr>

<td>'.boton(_('Fijar mi localización'), 'submit', false, 'blue').'</td>

<td align="right" nowrap="nowrap">
'._('Latitud').': <input name="y" size="3" type="text" id="geo_y" value="'.$center['y'].'" style="text-align:right;" readonly="readonly" /><br />
'._('Longitud').': <input name="x" size="3" type="text" id="geo_x" value="'.$center['x'].'" style="text-align:right;" readonly="readonly" />
</td>

<td>'._('Marca tu lugar haciendo clic. La información será pública').'.<br />
'._('Por privacidad la precisión guardada es de solo 1.112 metros a la redonda').'.</td>

<td align="right">'.boton(_('Eliminar tu geolocalización'), accion_url().'a=geolocalizacion&b=del', _('¿Estás seguro de querer borrar tu geolocalización de forma permanente?\n\nDebes saber que -para proteger la privacidad- la precisión guardada es de 1112 metros a la redonda, aleatoriamente.'), 'red small').'</td>

</tr>
</table>

</form>';


} else { // MAPA

	if (nucleo_acceso('ciudadanos')) {
		$result = mysql_query("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND x IS NOT NULL LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) { $geo = true; }
		if ($geo != true) { $txt .= '<p>'._('No estás geolocalizado').' '.boton(_('Geolocalízate'), '/geolocalizacion/fijar', false, 'red').'</p>'; }
	}

	$txt .='
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3&sensor=false"></script>
<script type="text/javascript" src="'.IMG.'lib/markerclusterer_packed.js"></script>
<script type="text/javascript">
nicks = new Array();
eventos = new Array();

$(document).ready(function(){ 
	'.($_GET['a']=='filtro'?'print_eventos("'.$_GET['b'].'", "'.$_GET['c'].'");':'print_eventos("ciudadanos", "");').'
});

function initialize() {
	$("#header-breadcrumbs a:last").html(eventos.length + " '._('ciudadanos').'");
	$("#total-num").html(eventos.length);

	var center = new google.maps.LatLng('.$centro.');

	var map = new google.maps.Map(document.getElementById("map"), {
		zoom: 6,
		center: center,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});


	var markerImage = new google.maps.MarkerImage("'.IMG.'ico/marker.png", new google.maps.Size(20, 20));
	
	var markers = [];
	for (var i = 0; i < eventos.length; i++) {
		var ev = eventos[i].split(" ");
		var latlng = new google.maps.LatLng(ev[1] + "" + aleatorio(10,99), ev[2] + "" + aleatorio(10,99));
		var marker = new google.maps.Marker({"position": latlng, icon: markerImage, title: ev[0]});
		var fn = markerClick(ev[0]);
		google.maps.event.addListener(marker, "click", fn);
		markers.push(marker);
	}

	var markerCluster = new MarkerClusterer(map, markers, {
		gridSize: 40,
		styles: [{
			url: "'.IMG.'ico/m1.png",
			height: 53,
			width: 52,
			textColor: "#ffffff",
			textSize: 16
		}, {
			url: "'.IMG.'ico/m2.png",
			height: 58,
			width: 58,
			textColor: "#ffffff",
			textSize: 16
		}, {
			url: "'.IMG.'ico/m3.png",
			height: 66,
			width: 65,
			textColor: "#ffffff",
			textSize: 16
		}, {
			url: "'.IMG.'ico/m4.png",
			height: 78,
			width: 77,
			textColor: "#ffffff",
			textSize: 16
		}]
	});

	google.maps.event.addListener(markerCluster, "clusterclick", function(cluster) { 
		var clickedMakrers = cluster.getMarkers(); 
		var lnicks = new Array();
		var el_cluster = cluster.getMarkers();
		
		if (('.(nucleo_acceso($vp['acceso']['control_gobierno'])?'"false"':'"true"').' == "true") && (nicks.length >= '.MP_MAX.')) {
			alert("Puedes enviar máximo '.MP_MAX.' a la vez.");
		} else if (el_cluster.length <= 250) {
			for (var i = 0; i < el_cluster.length; i++) {
				lnicks.push(el_cluster[i].getTitle());
			}
			print_nick_list("add", lnicks);
			markerCluster.setZoomOnClick(false);
		} else {
			markerCluster.setZoomOnClick(true);
		}
	}); 
}

function aleatorio(inferior, superior) { 
	numPosibilidades = superior - inferior;
	aleat = Math.random() * numPosibilidades;
	aleat = Math.round(aleat);
	return parseInt(inferior) + aleat; 
} 

function filtro_change(n) {
	var la_config = $(n).val();
	var ab = la_config.split("|");
	print_eventos(ab[0], ab[1]);
}

function print_eventos(acceso, acceso_cfg) {
	eventos = new Array();
	$.post("/ajax.php", { a: "geo", acceso: acceso, acceso_cfg: acceso_cfg }, function(data){
		eventos = data.split(",");
		initialize();
	});
}

function print_nick_list(accion, lnicks) {
	var html = "";
	var nicks_num = 0;
	if (accion == "add") {
		for (var i = 0; i < lnicks.length; i++) { 
			if ((lnicks[i]) && (!in_array(lnicks[i], nicks)) && (('.(nucleo_acceso($vp['acceso']['control_gobierno'])?'"true"':'"false"').' == "true") || (nicks.length < '.MP_MAX.'))) { 
				nicks.push(lnicks[i]); 
			}
		}
	} else if (accion == "reset") {
		nicks = new Array();
	}

	for (var i = 0; i < nicks.length; i++) {
		html = "<a href=\"/perfil/" + nicks[i] + "\" target=\"_blank\">" + nicks[i] + "</a><br />" + html;
		nicks_num++;
	}
	$("#nicks-num").text(nicks_num);
	$("#user-list-c").html(html);
}


function in_array(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

function markerClick(nick) { return function() { print_nick_list("add", [nick]); } }


function redirect_POST(la_url) {
	$("<form />")
      .hide()
      .attr({ method : "post"})
      .attr({ action : la_url})
      .append($("<input />")
        .attr("type","hidden")
        .attr({ "name" : "ciudadanos" })
        .val(nicks.join(" "))
      )
      .append("<input type=\"submit\" />")
      .appendTo($("body"))
      .submit();
}


//google.maps.event.addDomListener(window, "load", initialize);
</script>

<div id="user-list" style="position:absolute;right:10px;width:150px;height:500px;">

<p>'._('Total ciudadanos').' <b id="total-num"></b><br />
<select onchange="filtro_change(this)" style="width:150px;">
<option value="ciudadanos_global|" selected="selected">'._('Todo').' VirtualPol</option>
<option value="ciudadanos|" selected="selected">'._('Ciudadanos').' '.PAIS.'</option>

<optgroup label="'._('Cargos').'">';

$result = mysql_query("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' ORDER BY nivel DESC", $link);
while ($r = mysql_fetch_array($result)) { 
	$txt .= '<option value="cargo|'.$r['cargo_ID'].'">'.$r['nombre'].'</option>';
}


$txt .= '</optgroup>

<optgroup label="'._('Grupos').'">';

$result = mysql_query("SELECT grupo_ID, nombre FROM grupos WHERE pais = '".PAIS."' ORDER BY num DESC", $link);
while ($r = mysql_fetch_array($result)) { 
	$txt .= '<option value="grupos|'.$r['grupo_ID'].'">'.$r['nombre'].'</option>';
}


$txt .= '</optgroup>

<optgroup label="'._('Confianza').'">
<option value="confianza|5">+5</option>
<option value="confianza|10">+10</option>
<option value="confianza|20">+20</option>
<option value="confianza|50">+50</option>
</optgroup>

<optgroup label="'._('Antigüedad').'">
<option value="antiguedad|90">+3 '._('meses').'</option>
<option value="antiguedad|365">+1 '._('año').'</option>
<option value="antiguedad|'.(365*2).'">+2 '._('año').'</option>
<option value="antiguedad|'.(365*3).'">+3 '._('año').'</option>
<!--<option value="antiguedad|'.(365*4).'">+4 '._('año').'</option>-->
<!--<option value="antiguedad|'.(365*5).'">+5 '._('año').'</option>-->
</optgroup>

<optgroup label="'._('Otros filtros').'">
<option value="autentificados|">'._('Autentificados').'</option>
<option value="supervisores_censo|">'._('Superv. censo').'</option>
<option value="socio|">'._('Socios').'</option>
</optgroup>

</select>
</p>

<p><button onclick="print_nick_list(\'reset\', \'\');" class="small" style="float:right;margin-top:-4px;">X</button>
<b id="nicks-num">0</b> '._('ciudadanos').':</p>

<div id="user-list-c" style="overflow-y:auto;height:345px;">
<p class="gris" style="margin-top:20px;font-size:12px;">'._('Puedes crear una lista de ciudadanos haciendo clic en los puntos azules y enviarles un mensaje privado').'.</p>

</div>

<p><button onclick="redirect_POST(\'//'.strtolower($pol['pais']).'.'.DOMAIN.'/msg/enviar\');" class="small">'._('Enviar mensaje privado').'</button>
'.(nucleo_acceso($vp['acceso']['control_gobierno'])?'<br /><button onclick="redirect_POST(\'/control/gobierno/notificaciones\');" class="small">'._('Crear notificación').'</button>':'').'</p>
</div>

<div style="margin:0 160px -5px -20px;">
<div id="map" style="width:100%;height:500px;"></div>
</div>

';
}


//THEME
$txt_title = _('Mapa de ciudadanos');
$txt_nav = array('/geolocalizacion'=>_('Mapa'));
if ($_GET['a'] == 'fijar') { $txt_nav[] = _('Geolocalízate'); } 
elseif ($_GET['a'] == 'vecinos') { $txt_nav[] = _('Ciudadanos cercanos'); } 
else { $txt_nav[] = '&nbsp;'; }


$txt_tab = array('/geolocalizacion'=>_('Mapa'), '/geolocalizacion/vecinos'=>_('Ciudadanos cercanos'), '/geolocalizacion/fijar'=>_('Geolocalízate'));
$txt_menu = 'info';
include('theme.php');
?>