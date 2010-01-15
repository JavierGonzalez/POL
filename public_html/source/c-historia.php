<?php 
include('inc-login.php');

$txt .= '<h1>Hechos Hist&oacute;ricos de VirtualPol</h1>

<br />';

if ($pol['user_ID']) {
$txt .= '
<h2>A&ntilde;adir:</h2>
<p style="color:#AAA">Solo hechos importantes a nivel general y explicados de forma tan sencilla que cualquiera de fuera lo comprenda. De forma breve y concisa. <a href="http://pol.virtualpol.com/buscar/">buscador</a>. Etiquetas permitidas &lt;b&gt; y &lt;a href=""&gt;&lt;/a&gt;</p>
<p><form action="http://'.strtolower($pol['pais']).'.virtualpol.com/accion.php?a=historia&b=add" method="POST">


<select name="year">
<option value="2008">2008</option>
<option value="2009"">2009</option>
<option value="2010" selected="selected">2010</option>
<option value="2011">2011</option>
</select> -
<select name="mes">
<option value="01" selected="selected">01</option>
<option value="02">02</option>
<option value="03">03</option>
<option value="04">04</option>
<option value="05">05</option>
<option value="06">06</option>
<option value="07">07</option>
<option value="08">08</option>
<option value="09">09</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
</select> -
<input name="dia" size="2" maxlength="2" type="text" value="01" />


<input name="hecho" size="60" maxlength="200" type="text" value="" />


<select name="pais">
<option value="VirtualPol">VirtualPol</option>
<option value="POL">POL</option>
<option value="Hispania">Hispania</option>
</select>

<input value="Guardar" type="submit">
</form>
</p>
<br />';
}

$txt .= '<table border="0" cellspacing="0" cellpadding="1">
<tr>
<th>Fecha</th>
<th>Hecho hist&oacute;rico</th>
<th></th>
</tr>';


$result = mysql_query("SELECT *
FROM hechos
WHERE estado = 'ok'
ORDER BY time ASC", $link);
while($row = mysql_fetch_array($result)) {

	if (($row['nick'] == $pol['nick']) OR ($pol['estado'] == 'desarrollador') OR ($pol['nivel'] == 100)) {
		$boton = boton('x', '/accion.php?a=historia&b=del&ID='.$row['ID'], 'm');
	} else { $boton = ''; }

	$txt .= '<tr style="background:'.$vp['bg'][$row['pais']].';"><td valign="top" style="color:#999;">'.$row['time'].'</td><td valign="top">'.$row['texto'].'</td><td valign="top" style="font-size:14px;" align="right">'.$row['nick'].'</td><td valign="top">'.$boton.'</td></tr>';
}

$txt .= '</table>';



//THEME
$txt_title = 'Historia de VirtualPol';
include('theme.php');
?>
