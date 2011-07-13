<?php 
include('inc-login.php');

$txt .= '<h1>Historia de VirtualPol</h1>
';

$sc = get_supervisores_del_censo();

if (isset($sc[$pol['user_ID']])) {

$txt .= '
<!--<p style="color:#AAA">Solo hechos importantes a nivel general y explicados de forma tan sencilla que cualquiera de fuera lo comprenda. De forma breve y concisa. <a href="http://pol.virtualpol.com/buscar/">buscador</a>. Etiquetas permitidas &lt;b&gt; y &lt;a href=""&gt;&lt;/a&gt;</p>-->

<p><form action="http://'.strtolower($pol['pais']).'.virtualpol.com/accion.php?a=historia&b=add" method="POST">


<select name="year">
<option value="2004">2004</option>
<option value="2005">2005</option>
<option value="2006">2006</option>
<option value="2007">2007</option>
<option value="2008">2008</option>
<option value="2009">2009</option>
<option value="2010">2010</option>
<option value="2011" selected="selected">2011</option>
<option value="2012">2012</option>
</select>/
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
</select>/
<input name="dia" size="1" maxlength="2" type="text" value="01" />


<input name="hecho" size="60" maxlength="250" type="text" value="" />


<select name="pais">
<option value="VirtualPol">VirtualPol</option>
<option value="Desarrollo">Desarrollo</option>
<option value="15M">15M</option>
<option value="VP">VP</option>
<option value="POL">POL</option>
<option value="Hispania">Hispania</option>
<option value="Atlantis">Atlantis</option>
</select>

<input value="A&ntilde;adir" type="submit">
</form>
</p>';
}

$txt .= '<table border="0" cellspacing="0" cellpadding="1">
<tr>
<th>Fecha</th>
<th>Hecho hist&oacute;rico</th>
<th></th>
</tr>

<tr><td valign="top" style="color:#999;">'.explodear(' ', $date, 0).'</td><td valign="top"><em><b>Hoy</b>...</em></td><td valign="top"></td></tr>
';

$sc = get_supervisores_del_censo();

$result = mysql_query("SELECT *
FROM hechos
WHERE estado = 'ok'
ORDER BY time DESC", $link);
while($r = mysql_fetch_array($result)) {

	if (($r['nick'] == $pol['nick']) OR (isset($sc[$pol['user_ID']])) OR ($pol['nivel'] >= 97)) {
		$boton = boton('x', '/accion.php?a=historia&b=del&ID='.$r['ID'], 'm');
	} else { $boton = ''; }
	// <td valign="top" style="font-size:14px;" align="right">'.$r['nick'].'</td>
	$txt .= '<tr style="background:'.$vp['bg'][$r['pais']].';"><td valign="top" style="color:#999;" nowrap="nowrap">'.$r['time'].'</td><td valign="top">'.$r['texto'].'</td><td valign="top">'.$boton.'</td></tr>';
}

$txt .= '</table>';



//THEME
$txt_title = 'Historia de VirtualPol';
include('theme.php');
?>
