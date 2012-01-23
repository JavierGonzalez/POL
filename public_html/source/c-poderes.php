<?php 
include('inc-login.php');

	
$p['jefegabinete'] = ' ';
$p['vice'] = ' ';


function get_cargo($cargo_ID, $salto=', ', $reicon=false) {
	global $link;

	$html = '';
	$limit = '';
	if ($num != false) { $limit = ' LIMIT ' . $num; }
	$result = mysql_query("SELECT 
(SELECT nick FROM users WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick
FROM ".SQL."estudios_users WHERE ID_estudio = '" . $cargo_ID . "' AND cargo = '1'" . $limit);
	while ($row = mysql_fetch_array($result)) {
		if ($html) { $html .= $salto; if ($reicon) { $html .= '<img src="'.IMG.'cargos/' . $cargo_ID . '.gif" /> '; } }
		$html .= crear_link($row['nick']); 
	}
	return '<img src="'.IMG.'cargos/' . $cargo_ID . '.gif" /> '.$html;
}

if ($pol['paises']) {
	foreach ($pol['paises'] AS $pais) {
		if ($pais != PAIS) {
			if ($mas_paises) { $mas_paises .= ', '; }
			$mas_paises .= '<a href="http://'.strtolower($pais).'.'.DOMAIN.'/">'.$pais.'</a>';
		}
	}
	if ($mas_paises) { $mas_paises = ' <span style="color:grey;font-size:12px;">(Ver otros pa&iacute;ses: '.$mas_paises.')</span>'; }
}

$txt .= '
<div id="poderes"><table border="0" width="100%" cellspacing="10" cellpadding="0">
<tr><td class="amarillo" valign="top" width="33%"><h1>Poder Ejecutivo</h1>
<p class="gris">El Gobierno</p>

<center>
<table border="0" width="100%" cellpadding="0" cellspacing="3">
<tr><td align="right"><b>Presidente</b></td><td valign="top"><b>' . get_cargo(7) . '</b></td></tr>
<tr><td align="right">Vicepesidente</td><td valign="top">' . get_cargo(19) . '</td></tr>
<tr><td align="right" valign="top">Ministro</td><td valign="top">' . get_cargo(16) . '</td></tr>
<tr><td align="right" valign="top">Comisario</td><td valign="top">' . get_cargo(13) . '</td></tr>
<tr><td align="right" valign="top">Polic&iacute;a</td><td valign="top">' . get_cargo(12) . '</td></tr>


<tr><td align="left" valign="top" colspan="2"><br /><p class="gris">Ej&eacute;rcito de '.PAIS.'</p></td></tr>

<tr><td align="right">General</td><td valign="top"><b>' . get_cargo(58) . '</b></td></tr>
<tr><td align="right">Capit&aacute;n</td><td valign="top">' . get_cargo(57) . '</td></tr>
<tr><td align="right" valign="top">Soldado</td><td valign="top">' . get_cargo(55) . '</td></tr>
</table>

</center>


</td><td class="amarillo" valign="top" width="33%"><h1>Poder Legislativo</h1>
<p class="gris">El Parlamento</p>

<center>
<table border="0" cellpadding="0" cellspacing="3" width="100%">
<tr><td align="right"><b>Presidente</b></td><td valign="top">' . get_cargo(22) . '</td></tr>
<tr><td align="right" valign="top">Diputados</td><td valign="top"><b>' . get_cargo(6, '<br />', true) . '</b></td></tr>
<!--<tr><td align="right" valign="top">Defensor Pueblo</td><td valign="top">' . get_cargo(20) . '</td></tr>-->
</table>
</center>

</td><td class="amarillo" valign="top" width="33%"><h1>Poder Judicial</h1>
<p class="gris">La Justicia</p>

<center>
<table border="0" width="100%" cellpadding="0" cellspacing="3">
<tr><td align="right" valign="top" width="50%"><b>Juez&nbsp;Supremo</b></td><td width="50%" valign="top"><b>' . get_cargo(9, '<br />', true) . '</b></td></tr>
<tr><td align="right" valign="top">Juez de Paz</td><td valign="top">' . get_cargo(8, '<br />', true) . '</td></tr>
<tr><td align="right" valign="top">Fiscal</td><td valign="top">' . get_cargo(11, '<br />', true) . '</td></tr>
</table>
</center>

</td></tr>';




if (ECONOMIA) {

$txt .= '
<tr><td colspan="4"></td></tr>
<tr><td class="amarillo" valign="top" colspan="2" width="50%"><h1>Econom&iacute;a</h1>
<p class="gris">Los m&aacute;s ricos</p>

<table border="0" cellpadding="0" cellspacing="0">
<tr><td><ol>';

$result = mysql_query("SELECT nick, cargo,
(pols + IFNULL((SELECT SUM(pols) FROM ".SQL."cuentas WHERE user_ID = users.ID GROUP BY user_ID),0)) AS pols_total
FROM users
WHERE pais = '".PAIS."'
ORDER BY pols_total DESC 
LIMIT 15");
$txt .= mysql_error($link);
while ($row = mysql_fetch_array($result)) {
	if (!$first) { 
		$first = true;
		$txt .= '<li><img src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> <b>' . crear_link($row['nick']) . '</b></li>';
	} else {
		$txt .= '<li><img src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> ' . crear_link($row['nick']) . '</li>';
	}
}

$txt .= '</ol></td><td valign="top"><ol>';


$first = '';
$result = mysql_query("SELECT nombre, ID
FROM ".SQL."cuentas WHERE ID != '1' AND ID != '2' AND ID != '154' AND ID != '182' ORDER BY pols DESC LIMIT 15");
while ($row = mysql_fetch_array($result)) {
	if (!$first) { 
		$first = true;
		$txt .= '<li><a href="/pols/cuentas/' . $row['ID'] . '/"><b>' . $row['nombre'] . '</b></a></li>';
	} else {
		$txt .= '<li><a href="/pols/cuentas/' . $row['ID'] . '/">' . $row['nombre'] . '</a></li>';
	}
}


$txt .= '</ol></td></tr></table>


</td><td class="amarillo" valign="top" width="50%"><h1>Terratenientes</h1>
<p class="gris">Con m&aacute;s propiedades</p><ol>';

$first = '';
$result = mysql_query("SELECT SUM(superficie) AS superficie,
(SELECT nick FROM users WHERE ID = ".SQL."mapa.user_ID LIMIT 1) AS nick,
(SELECT cargo FROM users WHERE ID = ".SQL."mapa.user_ID LIMIT 1) AS cargo
FROM ".SQL."mapa
WHERE estado != 'e'
GROUP BY user_ID
ORDER BY superficie DESC
LIMIT 15");
$txt .= mysql_error($link);
while ($row = mysql_fetch_array($result)) {
	if (!$first) { 
		$first = true;
		// (' . ($row['s'] - $row['num'])  . ')
		$txt .= '<li><img src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> <b>' . crear_link($row['nick']) . ' (' . $row['superficie'] . ')</b></li>';
	} else {
		$txt .= '<li><img src="'.IMG.'cargos/' . $row['cargo'] . '.gif" /> ' . crear_link($row['nick']) . ' (' . $row['superficie'] . ')</li>';
	}
}


$txt .= '</ol></td></tr>';

}

$txt .= '</table></div>';

$txt_header .= '
<style type="text/css">
#poderes p { margin:3px 0 0 0; padding:0; }
#poderes a { font-size:18px; }
</style>';


//THEME
$txt_title = 'Poderes de '.PAIS.': Ejecutivo, Legislativo, Judicial, Economico, Terratenientes';
include('theme.php');
?>
