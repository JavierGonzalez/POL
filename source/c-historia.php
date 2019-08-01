<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

$txt_nav = array('/historia'=>'Historia de VirtualPol');
$txt .= '<h1 class="quitar">Historia de VirtualPol</h1>';

$sc = get_supervisores_del_censo();

if (isset($sc[$pol['user_ID']])) {

$txt .= '
<!--<p style="color:#AAA">Solo hechos importantes a nivel general y explicados de forma tan sencilla que cualquiera de fuera lo comprenda. De forma breve y concisa. <a href="/buscar/">buscador</a>. Etiquetas permitidas &lt;b&gt; y &lt;a href=""&gt;&lt;/a&gt;</p>-->

<p><form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=historia&b=add" method="POST">


<select name="year">
<option value="2004">2004</option>
<option value="2005">2005</option>
<option value="2006">2006</option>
<option value="2007">2007</option>
<option value="2008">2008</option>
<option value="2009">2009</option>
<option value="2010">2010</option>
<option value="2011">2011</option>
<option value="2012" selected="selected">2012</option>
<option value="2013">2013</option>
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
<option value="Desarrollo">Desarrollo</option>';

foreach ($vp['paises'] AS $pais) {
	$txt .= '<option value="'.$pais.'">'.$pais.'</option>';
}

$txt .= '

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

$result = mysql_query("SELECT *
FROM hechos
WHERE estado = 'ok'
ORDER BY time DESC", $link);
while($r = mysql_fetch_array($result)) {

	if (($r['nick'] == $pol['nick']) OR (nucleo_acceso('supervisores_censo')) OR ($pol['nivel'] >= 97)) {
		$boton = boton('x', '/accion.php?a=historia&b=del&ID='.$r['ID'], false, 'small');
	} else { $boton = ''; }
	// <td valign="top" style="font-size:14px;" align="right">'.$r['nick'].'</td>
	$txt .= '<tr><td valign="top" style="color:#999;" nowrap="nowrap">'.$r['time'].'</td><td valign="top">'.$r['texto'].'</td><td valign="top">'.$boton.'</td></tr>';
}

$txt .= '</table>';



//THEME
$txt_title = 'Historia de VirtualPol';
$txt_menu = 'info';
include('theme.php');
?>
