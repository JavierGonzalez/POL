<?php
include('inc-login.php');

if (PAIS != 'POL') {
header('HTTP/1.1 301 Moved Permanently'); header('Location: http://pol.virtualpol.com/geolocalizacion/'); exit;
}

$google_maps_api = 'ABQIAAAA3x7avNobQn9IKOcVfx9_jhSEhcFhcQv9ywlyLTKF25AAGEQo5xR0yFjye8cvG2S3VXQayUCrkHGOBQ';


if ($_GET['a'] == 'fijar') {

	// load config full
	$result = mysql_query("SELECT geo FROM ".SQL_USERS." WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
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
		map.setCenter(center, 3);

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

<h1><a href="/geolocalizacion/">Geolocalizaci&oacute;n</a>: fijar posicion</h1><br />

<div id="map" style="width:100%;height:550px"></div>

<form action="http://'.strtolower($pol['pais']).'.virtualpol.com/accion.php?a=geolocalizacion&b=add" method="POST">
Latitud: <input name="y" size="25" type="text" id="geo_y" value="'.$pol['geo'][1].'" readonly="readonly" /><br />
Longitud: <input name="x" size="25" type="text" id="geo_x" value="'.$pol['geo'][0].'" readonly="readonly" />
<br /><input value="Fijar mi localizaci&oacute;n" type="submit">
</form>

<p>Elige en el mapa el lugar habitual en el que te encuentras. Tu decides la precisi&oacute;n con la que lo quieres indicar.</p>


';



} else {





	$result = mysql_query("SELECT ID, nick, geo, pais FROM ".SQL_USERS." WHERE geo != '' LIMIT 200", $link);
	while ($row = mysql_fetch_array($result)) {
		if ($geo_array) { $geo_array .= ', '; } 
		$geo = explode(':', $row['geo']);
		$geo_num++;
		$geo_array .= $row['ID'] . ':"' . $row['nick'] . '|'.$geo[0].'|'.$geo[1].'|'.$row['pais'].'|'.$row['ID'].'"'."\n";
	}



	$txt .= '<h1>Geolocalizaci&oacute;n:';
	if ($pol['user_ID']) { $txt .= ' '.boton('Fija tu localizaci&oacute;n', '/geolocalizacion/fijar/'); }
	$txt .='</h1>

<script type="text/javascript" src="http://maps.google.com/maps?file=api&v=2&key='.$google_maps_api.'"></script>
<script type="text/javascript">
window.onload = function(){ initialize(); }

geo_array = new Array();
geo_array = { ' . $geo_array . ' };

map = "";
geocoder = "";

function createMarker(point, nombre, pais, user_ID) {
	var baseIcon = new GIcon(G_DEFAULT_ICON);
	var letteredIcon = new GIcon(baseIcon);
	letteredIcon.image = "http://'.strtolower(PAIS).'.virtualpol.com/img/geo/marker_" + pais + ".png";
	var marker = new GMarker(point, {title: nombre, icon:letteredIcon });

	GEvent.addListener(marker, "click", function() {
		marker.openInfoWindowHtml("<b>" + nombre + "</b><br />Ciudadano de " + pais + "<br /><img src=\"/img/a/" + user_ID + ".jpg\" alt=\"" + nombre + "\" /><br /><br /><br /><br />");
	});
	return marker;
} 



function initialize() {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));
		var center = new GLatLng(33, -31);
		map.setCenter(center, 3);
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());

		for (var geo in geo_array) {
			var dato = geo_array[geo].split("|");
			var point = new GLatLng(dato[2], dato[1]);
			marker = createMarker(point, dato[0], dato[3], dato[4]);
			map.addOverlay(marker);
		}

	}
}


</script>

<div id="map" style="width:100%;height:550px"></div>

<p><b>'.$geo_num.'</b> Ciudadanos geolocalizados.</p>';

}






//THEME
$txt_title = 'GeoLocalizacion';
include('theme.php');
?>