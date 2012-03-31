<?php
include('inc-login.php');

$centro = '40.38001,-3.6694'; // Madrid




// GEN
if ($pol['estado'] != 'ciudadano') {
	$txt .= '<p>El mapa de ciudadanos solo está disponible para ciudadanos.</p>';

} elseif ($_GET['a'] == 'geo_all') {
	header('Content-Type: application/javascript');
	echo 'var eventos = [';
	$result = mysql_query("SELECT pais, ID, nick, geo FROM users WHERE estado = 'ciudadano' AND geo != '' LIMIT 1000", $link); // .($_GET['b']?" AND pais = '".$_GET['b']."'":"")
	while ($r = mysql_fetch_array($result)) { 
		echo '{\'ID\':'.$r['ID'].',\'x\':'.explodear(':', $r['geo'], 1).',\'y\':'.explodear(':', $r['geo'], 0).',\'q\':\''.$r['nick'].'\'},';
	}
	echo '];';
	if ($link) { mysql_close($link); }
	exit;

} elseif ($_GET['a'] == 'fijar') {
	// load config full
	$result = mysql_query("SELECT geo FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
	while ($row = mysql_fetch_array($result)) { 
		$pol['geo'] = explode(':', $row['geo']); 
	}
	if (!$pol['geo'][0]) { $center['x'] = '-23'; $center['y'] = '21';  } else {  $center['x'] = $pol['geo'][0]; $center['y'] = $pol['geo'][1];  }

	$txt .= '
<script type="text/javascript" src="http://maps.google.com/maps?file=api&v=2&key='.$google_maps_api.'"></script>
<script type="text/javascript">

window.onload = function(){ initialize(); }
function initialize() {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));
		var center = new GLatLng('.$center['y'].', '.$center['x'].');
		map.setCenter(center, 5);

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
Latitud: <input name="y" size="3" type="text" id="geo_y" value="'.$pol['geo'][1].'" readonly="readonly" /><br />
Longitud: <input name="x" size="3" type="text" id="geo_x" value="'.$pol['geo'][0].'" readonly="readonly" />
</td>

<td>Marca tu lugar haciendo clic. La información será pública.<br />La precisión guardada en la base de datos es baja (1.112 metros a la redonda).</td>

</tr>
</table>

</form>

';




} else { // MAPA

	$result = mysql_query("SELECT COUNT(*) AS num FROM users WHERE estado = 'ciudadano' AND geo != ''", $link); //  AND pais = '".PAIS."'
	while ($r = mysql_fetch_array($result)) { $geo_num = $r['num']; }
	

	$txt .='
<script src="http://maps.googleapis.com/maps/api/js?v=3&sensor=false"></script>
<script type="text/javascript" src="/geolocalizacion/geo_all/'.PAIS.'"></script>
<script type="text/javascript" src="'.IMG.'lib/markerclusterer_packed.js"></script>
<script type="text/javascript">
function initialize() {
var center = new google.maps.LatLng('.$centro.');

var map = new google.maps.Map(document.getElementById("map"), {
  zoom: 6,
  center: center,
  mapTypeId: google.maps.MapTypeId.ROADMAP
});


var imageUrl = "'.IMG.'ico/marker.png";
var markerImage = new google.maps.MarkerImage(imageUrl, new google.maps.Size(20, 20));

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
		opt_anchor: [16, 0],
		opt_textColor: "#ffffff",
		opt_textSize: 14
	}, {
		url: "'.IMG.'ico/m2.png",
		height: 58,
		width: 58,
		opt_anchor: [24, 0],
		opt_textColor: "#ffffff",
		opt_textSize: 11
	}, {
		url: "'.IMG.'ico/m3.png",
		height: 66,
		width: 65,
		opt_anchor: [32, 0],
		opt_textSize: 12
	}]
});


}

function markerClick(nick) {
	return function() { window.location.href = "http://15m.virtualpol.com/perfil/" + nick; }
}

google.maps.event.addDomListener(window, "load", initialize);
</script>

<div id="map" style="width:100%;height:550px;margin-left:-20px;"></div>';

}



//THEME
$txt_title = 'Mapa de ciudadanos';
$txt_nav = array('/geolocalizacion'=>'Mapa de ciudadanos');
if ($_GET['a'] == 'fijar') { $txt_nav[] = 'Geolocalízate'; } else { $txt_nav[] = $geo_num.' ciudadanos'; }
$txt_tab = array('/geolocalizacion'=>'Mapa', '/geolocalizacion/fijar'=>'Geolocalízate');
$txt_menu = 'info';
include('theme.php');
?>