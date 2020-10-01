<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 

if (nucleo_acceso($vp['acceso']['gestion_mapa'])) { 
	$txt_tab['/mapa/arquitecto'] = _('Comprar propiedades');
	$txt_tab['/mapa/arquitecto/propiedades'] = _('Propiedades del estado');
}else{
    return;
}

if ($_GET[1] == 'propiedades'){
    $txt_title = 'Mapa: Propiedades del estado';
	$txt_nav = array('/mapa'=>'Mapa', 'Propiedades del estado');

	echo '<h1 class="quitar"><a href="/mapa/arquitecto">Mapa</a>: Propiedades del estado (<a href="/doc/mapa-de-vp">Ayuda</a>)</h1>

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
	
	$result = mysql_query_old("SELECT *
FROM mapa
WHERE pais = '".PAIS."' AND estado = 'e'
ORDER BY estado ASC, time ASC", $link);
	while($r = mysqli_fetch_array($result)){

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
'.boton('Vender', '/mapa/vender/'.$r['ID']).'
'.boton('Editar', '/mapa/arquitecto/editar/' . $r['ID']).'
'.(($r['size_x']*$r['size_y'])>1?boton('Separar', '/accion/mapa/separar?ID='.$r['ID'], '&iquest;Seguro que quieres SEPARAR esta propiedad?').' ':'').' ' . boton('X', '/accion/mapa/eliminar?ID=' . $r['ID'], '&iquest;Seguro que quieres ELIMINAR esta propiedad?\n\nSe convertira en un solar.').'

<form action="/accion/arquitecto/ceder?ID='.$r['ID'].'" method="post">
<input type="submit" value="Ceder a:" /> <input type="text" name="nick" size="8" maxlength="20" value="" /></form> 
'; 
				break;

			case 'v': 
				$estado = 'En venta'; $botones = boton('Editar', '/mapa/vender/' . $r['ID']) . ' ' . boton('Cancelar venta', '/accion/mapa/cancelar-venta?ID=' . $r['ID']); 
				break;

			case 'e': 
				$prop[$r['ID']]['pos_x'] = $r['pos_x'];
				$prop[$r['ID']]['pos_y'] = $r['pos_y'];
				$prop[$r['ID']]['size_x'] = $r['size_x'];
				$prop[$r['ID']]['size_y'] = $r['size_y'];
				$prop[$r['ID']]['color'] = $r['color'];

				$botones = boton('Editar', '/mapa/arquitecto/editar/' . $r['ID'] . '/') . ' 
'.(($r['size_x']*$r['size_y'])>1?boton('Separar', '/accion/mapa/separar?ID='.$r['ID'], '&iquest;Seguro que quieres SEPARAR esta propiedad?').' ':'').'
' . boton('X', '/accion/mapa/eliminar?ID=' . $r['ID'], '&iquest;Seguro que quieres ELIMINAR esta propiedad?\n\nSe convertira en un solar.').'
<form action="/accion/arquitecto/ceder?ID='.$r['ID'].'" method="post">
<input type="submit" value="Ceder a:" /> <input type="text" name="nick" size="8" maxlength="20" value="" /></form> 
'; 
				break;
		}

		if ($r['estado'] == 'e') {

			echo '<tr>
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
			echo '<tr>
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
	
	echo '

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

			echo '<tr>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>+</b></td>
<td valign="top">' . $ex_x . '</td>
<td><div style="width:' . $size2_x . 'px;height:' . $size2_y . 'px; background:#' . $prop[$ex_x]['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>=</b></td>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . ($size_x + $size2_x) . 'px;height:' . $size_y . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top">' . boton('Fusionar', '/accion/mapa/fusionar?ID=' . $ID . '-' . $ex_x . '&f=x&superficie=' . ($d['size_x'] * $prop[$ex_x]['size_x'])) . '</td>
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

			echo '<tr>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . $size_x . 'px;height:' . $size_y . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>+</b></td>
<td valign="top">' . $ex_y . '</td>
<td><div style="width:' . $size2_x . 'px;height:' . $size2_y . 'px; background:#' . $prop[$ex_y]['color'] . ';border:1px solid grey;"></div></td>
<td valign="top"><b>=</b></td>
<td valign="top" align="right">' . $ID . '</td>
<td><div style="width:' . $size_x . 'px;height:' . ($size_y + $size2_y) . 'px; background:#' . $d['color'] . ';border:1px solid grey;"></div></td>
<td valign="top">' . boton('Fusionar', '/accion/mapa/fusionar?ID=' . $ID . '-' . $ex_y . '&f=y&superficie=' . ($d['size_y'] * $prop[$ex_y]['size_y'])) . '</td>
</tr>';
		}
	}

	} //exist $prop



	echo '</table><br /><p><a href="/mapa/"><b>Ver mapa</b></a> &nbsp; <a href="/doc/mapa-de-vp/"><b>Ayuda</b></a></p>';
}elseif (($_GET[1] == 'editar') AND ($_GET[2])) { // EDITAR
	$txt_title = _('Mapa').': '._('Editar propiedad');
	$txt_nav = array('/mapa'=>_('Mapa'), _('Editar propiedad'));

	$result = mysql_query_old("SELECT *
FROM mapa
WHERE pais = '".PAIS."' AND ID = '" . $_GET[2] . "' AND (user_ID = '" . $pol['user_ID'] . "' OR (estado = 'e' AND 'true' = '".(nucleo_acceso('cargo', 40)?'true':'false')."'))
LIMIT 1", $link);
	while($r = mysqli_fetch_array($result)){

		
		$tamaño = ($r['size_x'] * $r['size_y']);
		echo '<h1><a href="/mapa">'._('Mapa').'</a>: '._('Editar propiedad').': ' . $_GET[2] . '</h1>

<form action="/accion/arquitecto/editar?ID=' . $_GET[2] . '" method="post">

<ol>

<li>'._('Propiedad').': <b>' . $r['ID'] . '</b><br />
'._('Posición').': <b>' . $r['pos_x'] . '-' . $r['pos_y'] . '</b><br />
'._('Tamaño').': <b>' . $r['size_x'] . 'x' . $r['size_y'] . '=' . ($r['size_x'] * $r['size_y']) . '</b><br /><br /></li>
<input type="hidden" name="color" value="#CCC" /> 
<li><b>'._('frase').'</b>:<br />
<input type="text" name="link" size="50" maxlength="70" value="' . $r['link'] . '" /><br /><br /></li>
<li><input type="submit" value="'._('Guardar').'" /><br /><br /></li>

</ol>

</form>';
	}




}else{
    $cuadrado_size = 24;
    $mapa_full = true;

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
            case 'e': if ($r['link']) { $info = 'e|' . $r['link']; } else { $info = 'e|'; } break;
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
                    $("#" + i).html(pa1);
                    $("#" + i).css("white-space", "nowrap");
                    $("#" + i).css("overflow", "hidden");
                    if (prop_a[2] == "V"){
                        $("#" + i).css("writing-mode", "tb-rl");
                    }
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
            $("#msg").html(msg);
            $("#msg").css("display", "inline");

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
            } else { var ID = $(this).attr("id"); window.location = "/accion/arquitecto/comprar/" + ID + "/"; }
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

    $txt_mapa .='
    <div id="msg" class="amarillo"></div>';

    echo $txt_mapa;
} 

