<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$maxsim['output'] = 'text';

$tipo_mapa = $_POST['tipo_mapa'];
if (isset($_POST['nivel'])){
	$nivel = $_POST['nivel'];
}else{
	$nivel = 1;
}

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
$prop = '{';
$m = null;
$color = 'white';
$consulta_barrios = "SELECT ID, pos_x, pos_y, size_x, size_y, nombre, color, 'b' as estado
FROM mapa_barrios
UNION ALL 
SELECT ID, pos_x, pos_y, size_x, size_y, link as nombre, color, estado
FROM mapa WHERE estado = 'e'";

if ($nivel == 1){
	$consulta_parcelas = "SELECT ID, pos_x, pos_y, size_x, size_y, link, pols, color, estado, superficie, nick
	FROM mapa
	WHERE pais = '".PAIS."'
	ORDER BY pos_y ASC, pos_x ASC";
}else{
	$consulta_parcelas = "SELECT m.ID as ID, pos_x, pos_y, size_x, size_y, a.link as link, pols, a.color as color, estado, superficie, nick
	FROM mapa m, mapa_altura a
	WHERE pais = '".PAIS."' AND ( (m.ID = a.parcela_ID AND a.altura = '".$nivel."'))
	UNION ALL 
	SELECT ID, pos_x, pos_y, size_x, size_y, link as link, '' , color, estado, '1', ''
	FROM mapa WHERE estado = 'e'	";
}

if ($tipo_mapa == 1){
	$result = mysql_query_old($consulta_parcelas, $link);
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
		
		if ($prop) { $prop .= ','; }
		$prop .= '"'. $r['ID'] . '":"' . $info . '"';
	}
}else{
	$result = mysql_query_old($consulta_barrios, $link);
	while($r = mysqli_fetch_array($result)) {

		$sup_total += $r['superficie'];
	
		// genera tabla array
		$m[$r['pos_x']][$r['pos_y']] = $r['ID'] . '|' . $r['size_x'] . '|' . $r['size_y'] . '|' . $r['nombre'] . '|' . $r['color'];
	
		$orientacion = 'H';
		if ($r['size_y'] > $r['size_x']){
			$orientacion = 'V';
		}
	
		$info = $r['estado'].'|'. $r['nombre'] . '|' . $r['color']. "|" .$orientacion. "|";
		
	
		if ($prop) { $prop .= ','; }
		$prop .='"'. $r['ID'] . '":"' . $info . '"';
	}
}

$prop .='}';


$txt_mapa = '<table border="0" cellpadding="0" cellspacing="0" height="' . $mapa_height . '" width="' . $mapa_width . '">';
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


$txt_mapa .= '</table>';

$response['prop'] = $prop;
$response['mapa'] = $txt_mapa;


echo json_encode($response);
