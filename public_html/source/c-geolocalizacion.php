<?php
include('inc-login.php');

$centro = '40.180,-3.669'; // Madrid




// GEN
if (!isset($pol['user_ID'])) {
	$txt .= '<p>El mapa de ciudadanos solo está disponible para ciudadanos.</p>';


} elseif ($_GET['a'] == 'vecinos') {
	include_once('inc-functions-accion.php');

	$user_y = '40.416'; $user_x = '-3.700'; // Madrid
	$result = mysql_query("SELECT x, y FROM users WHERE ID = '".$pol['user_ID']."' AND x IS NOT NULL LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { $user_x = $r['x']; $user_y = $r['y']; $geo = true; }

	if ($geo) {
		$txt .= '<p>Los ciudadanos más cercanos a ti.</p>';
	} else {
		$txt .= '<p>No estás geolocalizado '.boton('Geolocalízate', '/geolocalizacion/fijar', false, 'large red').'</p>';
	}
	
	
	$txt .= '<table border="0">';
	$result = mysql_query("SELECT ID, nick, pais, avatar, x, y, POW(x-".$user_x.",2)+POW(y-".$user_y.",2) AS dist FROM users WHERE estado = 'ciudadano' AND ID != '".$pol['user_ID']."' AND x IS NOT NULL ORDER BY dist ASC LIMIT 25", $link);
	while ($r = mysql_fetch_array($result)) { 
		$txt .= '<tr><td height="40">'.($r['avatar']=='true'?avatar($r['ID'], 40):'').'</td><td><b style="font-size:16px;">'.crear_link($r['nick']).'</b></td><td align="right">'.distancia($user_x, $user_y, $r['x'], $r['y'], 0).' km</td><td>'.boton('Enviar mensaje', 'http://'.strtolower($pol['pais']).'.'.DOMAIN.'/msg/'.$r['nick']).'</td></tr>';
	}
	$txt .= '</table>';


} elseif ($_GET['a'] == 'fijar') {
	
	$center['y'] = '40.3'; $center['x'] = '-3.6';
	$result = mysql_query("SELECT x, y FROM users WHERE ID = '".$pol['user_ID']."' AND x IS NOT NULL LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 
		$center['x'] = $r['x']; 
		$center['y'] = $r['y'];
	}

	$txt .= '
<script type="text/javascript" src="http://maps.google.com/maps?file=api&v=2&key='.$google_maps_api.'"></script>
<script type="text/javascript">

window.onload = function(){ initialize(); }
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
				$("#geo_x").attr("value", point.x);
				$("#geo_y").attr("value", point.y);
			}
		});

		map.addOverlay(marker);
	}
}
</script>

<div id="map" style="width:100%;height:400px"></div>

<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=geolocalizacion&b=add" method="POST">

<table border="0">
<tr>

<td>'.boton('Fijar mi localización', 'submit', false, 'blue').'</td>

<td align="right" nowrap="nowrap">
Latitud: <input name="y" size="3" type="text" id="geo_y" value="'.$center['y'].'" readonly="readonly" /><br />
Longitud: <input name="x" size="3" type="text" id="geo_x" value="'.$center['x'].'" readonly="readonly" />
</td>

<td>Marca tu lugar haciendo clic. La información será pública.<br />
Por privacidad la precisión guardada es de solo 560 metros.</td>

</tr>
</table>

</form>';


} else { // MAPA

	$result = mysql_query("SELECT COUNT(*) AS num FROM users WHERE estado = 'ciudadano' AND x IS NOT NULL", $link); //  AND pais = '".PAIS."'
	while ($r = mysql_fetch_array($result)) { $geo_num = $r['num']; }


	$result = mysql_query("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND x IS NOT NULL LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { $geo = true; }

	if ($geo != true) { $txt .= '<p>No estás geolocalizado '.boton('Geolocalízate', '/geolocalizacion/fijar', false, 'large red').'</p>'; }


	$txt .='
<script src="http://maps.googleapis.com/maps/api/js?v=3&sensor=false"></script>
<script type="text/javascript" src="/ajax.php?a=geo"></script>
<script type="text/javascript" src="'.IMG.'lib/markerclusterer_packed.js"></script>
<script type="text/javascript">
function initialize() {
var center = new google.maps.LatLng('.$centro.');

var map = new google.maps.Map(document.getElementById("map"), {
  zoom: 6,
  center: center,
  mapTypeId: google.maps.MapTypeId.ROADMAP
});


var markerImage = new google.maps.MarkerImage("'.IMG.'ico/marker.png", new google.maps.Size(20, 20));

var markers = [];
for (var i = 0; i < eventos.length; i++) {
	var latlng = new google.maps.LatLng(eventos[i].x, eventos[i].y);
	var marker = new google.maps.Marker({"position": latlng, icon: markerImage, title: eventos[i].q});
	var fn = markerClick(eventos[i].q);
	google.maps.event.addListener(marker, "click", fn);
	markers.push(marker);
}

var markerCluster = new MarkerClusterer(map, markers, {
	styles: [{
		url: "'.IMG.'ico/m1.png",
		height: 53,
		width: 52,
		//anchor: [16, 0],
		textColor: "#ffffff",
		textSize: 16
	}, {
		url: "'.IMG.'ico/m2.png",
		height: 58,
		width: 58,
		//anchor: [24, 0],
		textColor: "#ffffff",
		textSize: 16
	}, {
		url: "'.IMG.'ico/m3.png",
		height: 66,
		width: 65,
		//anchor: [32, 0],
		textColor: "#ffffff",
		textSize: 16
	}, {
		url: "'.IMG.'ico/m4.png",
		height: 78,
		width: 77,
		//anchor: [32, 0],
		textColor: "#ffffff",
		textSize: 16
	}]
});
}


    




function markerClick(nick) { return function() { window.open("http://15m.virtualpol.com/perfil/" + nick); } }

google.maps.event.addDomListener(window, "load", initialize);
</script>

<div id="map" style="width:100%;height:500px;margin-left:-20px;"></div>';

}



//THEME
$txt_title = 'Mapa de ciudadanos';
$txt_nav = array('/geolocalizacion'=>'Mapa');
if ($_GET['a'] == 'fijar') { $txt_nav[] = 'Geolocalízate'; } 
elseif ($_GET['a'] == 'vecinos') { $txt_nav[] = 'Ciudadanos cercanos'; } 
else { $txt_nav[] = $geo_num.' ciudadanos'; }


$txt_tab = array('/geolocalizacion'=>'Mapa', '/geolocalizacion/vecinos'=>'Ciudadanos cercanos', '/geolocalizacion/fijar'=>'Geolocalízate');
$txt_menu = 'info';
include('theme.php');
?>