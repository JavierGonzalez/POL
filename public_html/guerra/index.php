<?php
include ("war-lib.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Modulo de Guerra - VirtualPOL</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript"> 
function mostrar_n() { 
document.getElementById("nombres").style.visibility="visible"; 
} 
function mostrar_c() { 
document.getElementById("cuadricula").style.visibility="visible"; 
} 
</script>
</head>

<body>
<div id="header"><span class="h_item" id="h_left"><a href="http://virtualpol.com">VirtualPOL</a></span><span class="h_item" id="h_right">Usuario</span></div>
<div id="controles"> <span onclick="mostrar_c()">Mostrar cuadricula</span>&nbsp;&frasl;&nbsp;<span onclick="mostrar_n()">Mostrar nombres</span></div>
<div id="container">
	<div id="cuadricula" style="visibility:hidden"><img src="mapa8.png" /></div>
	<div id="nombres" style="visibility:hidden"><img src="mapa9.png" /></div>
	
</div>
</body>
</html>
