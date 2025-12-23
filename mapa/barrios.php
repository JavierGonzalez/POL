<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 

$txt_tab['/mapa/barrios'] = _('Barrios de POL');
$txt_tab['/mapa'] = _('Mapa');

if (nucleo_acceso($vp['acceso']['gestion_mapa'])) { 
	$txt_tab['/mapa/barrios/gestion'] = _('Gestión de los barrios');
}

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
$result = mysql_query_old("SELECT ID, pos_x, pos_y, size_x, size_y, nombre, color
FROM mapa_barrios
ORDER BY pos_x ASC, pos_y ASC", $link);
while($r = mysqli_fetch_array($result)) {

    $sup_total += $r['superficie'];

    // genera tabla array
    $m[$r['pos_x']][$r['pos_y']] = $r['ID'] . '|' . $r['size_x'] . '|' . $r['size_y'] . '|' . $r['nombre'] . '|' . $r['color'];

    $orientacion = 'H';
    if ($r['size_y'] > $r['size_x']){
        $orientacion = 'V';
    }

    $info = $r['nombre'] . '|' . $r['color']. "|" .$orientacion;
    

    if ($prop) { $prop .= ',' . "\n"; }
    $prop .= $r['ID'] . ':"' . $info . '"';
}

$result = mysql_query_old("SELECT ID, pos_x, pos_y, size_x, size_y, link as nombre, color
FROM mapa WHERE estado = 'e'
ORDER BY pos_x ASC, pos_y ASC", $link);
while($r = mysqli_fetch_array($result)) {

    $sup_total += $r['superficie'];

    // genera tabla array
    $m[$r['pos_x']][$r['pos_y']] = $r['ID'] . '|' . $r['size_x'] . '|' . $r['size_y'] . '|' . $r['nombre'] . '|' . $r['color'] ;

    $orientacion = 'H';
    if ($r['size_y'] > $r['size_x']){
        $orientacion = 'V';
    }

    $info = $r['nombre'] . '|' . $r['color']. "|" .$orientacion. '|e';
    

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

function colorear() {
    for (i in prop) {
        var prop_a = prop[i].split("|");
        if (prop_a[3] == "e"){
            var pa1 = prop_a[0];
            var elcolor = "#808080";
            $("#" + i).html(pa1);
            $("#" + i).css("white-space", "nowrap");
            $("#" + i).css("overflow", "hidden");
            if (prop_a[2] == "V"){
                $("#" + i).html("<span style=\"writing-mode: vertical-rl\">"+pa1+"</span>");
                $("#" + i).css("writing-mode", "tb-rl");
            }
            $("#" + i).css("background", elcolor);
        }else{
            var pa1 = prop_a[0];
            var elcolor =  prop_a[1];
            $("#" + i).attr("nombre" ,pa1);
            $("#" + i).attr("color" ,elcolor);
            $("#" + i).html(pa1);
            $("#" + i).css("white-space", "nowrap");
            $("#" + i).css("overflow", "hidden");
            if (prop_a[3] == "V"){
                $("#" + i).css("writing-mode", "vertical-rl");
            }

            $("#" + i).css("background", elcolor);
        }
    }
}

$(document).ready(function(){
    $("#msg").css("display","none");
    colorear();
    $("#polmap td").click(function () { 
        var id = $(this).attr("id");
        var nombre = $(this).attr("nombre");
        var color = $(this).attr("color");
        if (nombre != undefined){
            
            $("#editarID").val(id);
            $("#borrarID").val(id);
            $("#nombre").val(nombre);
            $("#color").val(color);
            $("#color").css("background-color", color);        
            $("#formularioEditar").css("visibility", "visible");
        }else{
            $("#formularioEditar").css("visibility", "hidden");
        }
    });
});

$(document).mousemove(function(e){
    $("#msg").css({top: e.pageY + "px", left: e.pageX + 15 + "px"});
});

</script>';
unset($prop);
$txt_mapa .= '

<div id="polmap" style="float: left">
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

if ($_GET[1] == 'gestion'){
    $txt_title = 'Mapa: Propiedades del estado';
    $txt_nav = array('/mapa'=>'Mapa', 'Propiedades del estado');
    
    echo '
        <script>
            var pos_x;
            var pos_y;

            var size_x;
            var size_y;
            function cambiarMapaGestion(){
                $("#polmap").html("");
            }
            function crearBarrio(){
                pos_x=0;
                pos_y=0;
    
                size_x=0;
                size_y=0;
                   
                $("#barrio").html("Seleccione una esquina del barrio.");
                limpiarMapa();
                $("#polmap td").click(function () { 
                    var id = $(this).attr("id");
                    pos_x = Number(id.split("-")[0]);
                    pos_y = Number(id.split("-")[1]);
                    $("#polmap td").off("click");
                    finalizarBarrio();
                });
            }

            function finalizarBarrio(){
                $("#barrio").html("Seleccione la segunda esquina del barrio.");
                $("#polmap td").mouseover(function(){
                    limpiarMapa();
                    var id = $(this).attr("id");
                    var last_pos_x = Number(id.split("-")[0]);
                    var last_pos_y = Number(id.split("-")[1]);
                    colorearMapa(last_pos_x, last_pos_y);                  
                })
                .click(function () { 
                    var id = $(this).attr("id");
                    var last_pos_x = id.split("-")[0];
                    var last_pos_y = id.split("-")[1];

                    colorearMapa(last_pos_x, last_pos_y);

                    $("#pos_x").val(pos_x);
                    $("#pos_y").val(pos_y);
                    $("#size_x").val(size_x);
                    $("#size_y").val(size_y);

                    $("#barrio").html("Introduzca el nombre del barrio y color del barrio y pulse crear");
                    $("#formulario").css("visibility", "visible");

                    $("#polmap td").off("click");
                    $("#polmap td").off("mouseover");
                });
            }

            function colorearMapa(last_pos_x, last_pos_y, color = "grey"){
                var ini_x = pos_x;
                var fin_x = last_pos_x;

                var ini_y = pos_y;
                var fin_y = last_pos_y;


                if (last_pos_x < pos_x){
                   ini_x = last_pos_x;
                   fin_x = pos_x;
                }
                if (last_pos_y < pos_y){
                    ini_y = last_pos_y;
                    fin_y = pos_y;
                 }

                size_x = (Number(fin_x) +1) - ini_x;
                size_y= (Number(fin_y) +1)  - ini_y;

                for (x = ini_x; x<=fin_x; x++){
                    for (y = ini_y; y<=fin_y; y++){
                        var id = "#"+x+"-"+y;
                        $(id).css("background", color);
                    }
                }
            }

            function limpiarMapa(){
                for (x = 1; x<' . $mapa_height . '; x++){
                    for (y = 1; y<' . $mapa_width . '; y++){
                        var id = "#"+x+"-"+y;
                        $(id).css("background", "white");
                    }
                }
            }
        </script>
        <div id="formulario" style="visibility: hidden">
            <form id="form" action="/accion/arquitecto/crear-barrio" method="POST">
                <input type="hidden" id="pos_x" name="pos_x">
                <input type="hidden" id="pos_y" name="pos_y">
                <input type="hidden" id="size_x" name="size_x">
                <input type="hidden" id="size_y" name="size_y">
                <span style="display: block">Nombre: <input type="text" value="" name="nombre"></span>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.3.3/jscolor.min.js" integrity="sha512-KVabwlnqwMHqLIONPKHQTGzW4C0dg3HEPwtTCVjzGOW508cm5Vl6qFvewK/DUbgqLPGRrMeKL3Ga3kput855HQ==" crossorigin="anonymous"></script>
                <span style="display: block">Color: <input value="" data-jscolor="" name="color"></span>
                <span style="display: block"><input type="submit" value="Crear"></span>
            </form>
        </div>
        <div id="formularioEditar" style="visibility: hidden; float: left; margin-left: 20px">
            <form id="form" action="/accion/arquitecto/editar-barrio" method="POST">
                <input type="hidden" value="" name="ID" id="editarID" />
                <span style="display: block">Nombre: <input type="text" value="" name="nombre" id="nombre"></span>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.3.3/jscolor.min.js" integrity="sha512-KVabwlnqwMHqLIONPKHQTGzW4C0dg3HEPwtTCVjzGOW508cm5Vl6qFvewK/DUbgqLPGRrMeKL3Ga3kput855HQ==" crossorigin="anonymous"></script>
                <span style="display: block">Color: <input value="" data-jscolor="" name="color" id="color"></span>
                <span style="display: block"><input type="submit" value="Editar"></span>
            </form>
            <form action="/accion/arquitecto/borrar-barrio" method="POST">
                <input type="hidden" value="" name="ID"  id="borrarID" />
                <input type="submit" value="Borrar" style="background: #f44336; color: white" />
            </form>
        </div>
        <div style="float: none; clear: both;">
            <span style="display: block"><input type="button" value="Crear nuevo barrio" onclick="crearBarrio();" id="crearBarrio"></span>
        </div>
        <div id="barrio" ></div>

    ';
}

