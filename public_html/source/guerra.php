<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Modulo de Guerra - VirtualPOL</title>
<link rel="stylesheet" type="text/css" href="guerra.css" />
<script type="text/javascript"> 
var mostrar_nom = true;
var mostrar_cua = true;
function mostrar_n() { 
	if (mostrar_nom == true) {
		document.getElementById("nombres").style.visibility="visible";
		mostrar_nom = false;
	}
	else {
		document.getElementById("nombres").style.visibility="hidden";
		mostrar_nom = true;
	} 
} 
function mostrar_c() { 
	if (mostrar_cua == true) {
		document.getElementById("cuadricula").style.visibility="visible";
		mostrar_cua = false;
	}
	else {
		document.getElementById("cuadricula").style.visibility="hidden";
		mostrar_cua = true;
	} 
} 
</script>
</head>

<body>
<div id="header"><span class="h_item" id="h_left"><a href="http://virtualpol.com">VirtualPOL</a></span><span class="h_item" id="h_right">Usuario</span></div>
<div id="controles"> <span onclick="mostrar_c()">Mostrar cuadricula</span>&nbsp;&frasl;&nbsp;<span onclick="mostrar_n()">Mostrar nombres</span></div>
<div id="container">
	<div id="cuadricula" style="visibility:hidden"><img src="cuadricula.png" /></div>
	<div id="nombres" style="visibility:hidden"><img src="nombres.png" /></div>
	
</div>
</body>
</html>
