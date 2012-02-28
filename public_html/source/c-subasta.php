<?php 
include('inc-login.php');

// carga config
$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

/*
pol_mercado	(ID, user_ID, title, descripcion, pols ,tipo, time, estado) 
pol_pujas		(ID, mercado_ID, user_ID, pols, time)
*/

 if ($_GET['a'] == 'editar') {

	$txt .= '<h1><a href="/subasta/">Subastas</a>: Editar</h1><br />';

	if ($pol['user_ID'] == $pol['config']['pols_fraseedit']) {

		$url = str_replace("http://", "", explodear("\"", $pol['config']['pols_frase'], 1));
		$frase = explodear("<", explodear(">", $pol['config']['pols_frase'], 1), 0);

		$txt .= '<div class="azul"><h2>"la frase"</h2>
<form action="/accion.php?a=mercado&b=editarfrase" method="post">
<table border="0"><tr>
<td align="right">Texto: <input value="' . $frase . '" type="text" name="frase" size="50" maxlength="70" /><br />
http://<input type="text" name="url" size="50" maxlength="150" value="' . $url . '" /></td><td>
<input type="submit" value="Editar" style="height:40px;" /></form></b></td></tr>
<tr><td align="right">
<form action="/accion.php?a=mercado&b=cederfrase" method="post">
Ceder al Ciudadano: <input type="text" name="nick" size="14" maxlength="20" value="" />
</td><td>
<input type="submit" value="Ceder" style="height:30px;" />
</form>

</td></tr></table>
</div><br />';

	}

	// ;ID:URL:TEXT;
	foreach(explode(";", $pol['config']['palabras']) as $num => $t) {
		$t = explode(":", $t);
		if ($t[0] == $pol['user_ID']) {
			$txt .= '<div class="azul"><h2>"Palabra '.($num + 1).'"</h2>
<form action="/accion.php?a=mercado&b=editarpalabra&ID=' . $num . '" method="post">

<table border="0"><tr>
<td>Texto: <input value="' . $t[2] . '" type="text" name="text" size="12" maxlength="10" /><br />
http://<input type="text" name="url" size="50" maxlength="150" value="' . $t[1] . '" /></td><td>
<input type="submit" value="Editar" style="height:40px;" /></form></b></td></tr>

<tr><td align="right">
<form action="/accion.php?a=mercado&b=cederpalabra&ID='.$num.'" method="post">
Ceder al Ciudadano: <input type="text" name="nick" size="14" maxlength="20" value="" />
</td><td>
<input type="submit" value="Ceder" style="height:30px;" />
</form></td>

</tr></table>
</div><br />';
		}
	}


} else { // SUBASTAS

	$txt_header .= '
<script type="text/javascript">
window.onload = function(){ 
	setTimeout(function(){ $(".pujar").removeAttr("disabled"); }, 2000); 
}
</script>';

	// datos graficos
	$i = 0;
	$result = mysql_query("SELECT mercado_ID, pols
FROM ".SQL."pujas WHERE mercado_ID = '1' OR mercado_ID = '2' 
ORDER BY time ASC LIMIT 500", $link);
	while($row = mysql_fetch_array($result)){
		$dgrafico[$row['mercado_ID']][] = $row['pols'];
		$i++;
	}
	if ($dgrafico[1]) { $dgrafico_max_1 = max($dgrafico[1]); } else { $dgrafico_max_1 = 0; }
	if ($dgrafico[2]) { $dgrafico_max_2 = max($dgrafico[2]); } else { $dgrafico_max_2 = 0; }


	$queda = duracion(strtotime(date('Y-m-d 20:00:00')) - time());
	if (substr($queda, 0, 1) == '-') { $queda = '1 dia'; }

	$result = mysql_query("SELECT valor,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."config.valor LIMIT 1) AS nick
FROM ".SQL."config WHERE dato = 'pols_fraseedit' LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){ $nick = $row['nick']; }








	$txt .= '<h1>Subastas (<a href="/subasta/">Actualizar</a>) <span class="gris">Proceso en <span class="timer" value="'.strtotime(date('Y-m-d 20:00:00')).'"></span></span></h1>

<br />

<table border="0"><tr><td width="50%" valign="top">


<table border="0" cellspacing="2" cellpadding="0" class="pol_table">

<tr>
<td colspan="4" align="center"><img src="http://chart.apis.google.com/chart?cht=lc&chs=360x100&chxt=y&chxl=0:|0|' . round($dgrafico_max_1 / 2) . '|' . $dgrafico_max_1 . '&chd=s:' . chart_data($dgrafico[1]) . '&chco=0066FF&chm=B,FFFFDD,0,0,0&chf=bg,s,ffffff01|c,s,ffffff01" width="360" height="100" /></td>
</tr>';

	$ganador = 'ok';
	$init = false;
	$result = mysql_query("SELECT user_ID, pols, time,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick
FROM ".SQL."pujas
WHERE mercado_ID = 1
ORDER BY pols DESC
LIMIT 10", $link);
	while($row = mysql_fetch_array($result)) {
		$init = true;
		if ($ganador == 'ok') { 
			$txt .= '
<tr>
<td colspan="4" class="amarillo"><form action="/accion.php?a=mercado&b=puja&ID=1" method="post">
<b><input type="text" name="puja" value="' . ($row['pols'] + 10) . '" size="4" maxlength="7" style="text-align:right;" class="pols" /> '.MONEDA.' <input class="pujar" disabled="disabled" type="submit" value="Pujar" /> &nbsp; "La frase"</b></form></td>
</tr>';
			$bold = ' style="font-weight:bold;"'; 
			$ganador = '<b>(Ganador)</b>'; 
		} else { $ganador = false; $bold = ''; }
		$txt .= '<tr><td align="right"><b style="font-size:20px;">' . pols($row['pols']) . '</b></td><td' . $bold . '>' . crear_link($row['nick']) . '</td><td' . $bold . '><span class="timer" value="'.strtotime($row['time']).'"></span></td><td>' . $ganador . '</td></tr>';
	}


	if ($init == false) {
		$txt .= '
<tr>
<td colspan="4" class="amarillo"><form action="/accion.php?a=mercado&b=puja&ID=1" method="post">
<b><input type="text" name="puja" value="1" size="4" maxlength="7" style="text-align:right;" class="pols" /> '.MONEDA.' <input class="pujar" disabled="disabled" type="submit" value="Pujar" /> &nbsp; "La frase"</b></form></td>
</tr>';
	}

	$txt .= '</table>
<p>Propietario actual: <b style="font-size:22px;">' . crear_link($nick) . '</b></p>

</td><td width="50%" valign="top">




<table border="0" cellspacing="2" cellpadding="0" class="pol_table">

<tr>
<td colspan="4" align="center"><img src="http://chart.apis.google.com/chart?cht=lc&chs=360x100&chxt=y&chxl=0:|0|' . round($dgrafico_max_2 / 2) . '|' . $dgrafico_max_2 . '&chd=s:' . chart_data($dgrafico[2]) . '&chco=0066FF&chm=B,FFFFDD,0,0,0&chf=bg,s,ffffff01|c,s,ffffff01" width="360" height="100" /></td>
</tr>';

	$gan = 0;
	$ganador = '';
	$init = false;
	$result = mysql_query("SELECT user_ID, MAX(pols) AS los_pols, time,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick
FROM ".SQL."pujas
WHERE mercado_ID = 2
GROUP BY user_ID
ORDER BY los_pols DESC
LIMIT 10", $link);
	while($row = mysql_fetch_array($result)) {
		$init = true;
		$gan++;
		if ($gan == 1) {
		$txt .= '
<tr>
<td colspan="4" class="amarillo"><form action="/accion.php?a=mercado&b=puja&ID=2" method="post">
<b><input type="text" name="puja" value="' . ($row['los_pols'] + 10) . '" size="4" maxlength="7" readonly="readonly" style="text-align:right;" class="pols" /> '.MONEDA.' <input type="submit" value="Pujar" class="pujar" disabled="disabled" /> &nbsp; "las palabras"</b></form></td>
</tr>';
		}
		if ($gan <= $pol['config']['palabras_num']) { $bold = ' style="font-weight:bold;"'; $ganador = '<b>(Ganador)</b>'; } else { $bold = ''; $ganador = false; }
		$txt .= '<tr><td align="right"><b style="font-size:20px;">' . pols($row['los_pols']) . '</b></td><td' . $bold . '>' . crear_link($row['nick']) . '</td><td' . $bold . '><span class="timer" value="'.strtotime($row['time']).'"></span></td><td>' . $ganador . '</td></tr>';
	}


	if ($init == false) {
		$txt .= '
<tr>
<td colspan="4" class="amarillo"><form action="/accion.php?a=mercado&b=puja&ID=2" method="post">
<b><input type="text" name="puja" value="' . ($row['los_pols'] + 10) . '" size="4" class="pols" readonly="readonly" maxlength="7" style="text-align:right;" /> '.MONEDA.' <input type="submit" value="Pujar" class="pujar" disabled="disabled" /> &nbsp; "las palabras"</b></form></td>
</tr>';
	}

	$txt .= '</table>

<p>Propietarios actuales:<br /><b>';

	foreach(explode(";", $pol['config']['palabras']) as $t) {
		$t = explode(":", $t);
		$result = mysql_query("SELECT nick FROM ".SQL_USERS." WHERE ID = '" . $t[0] . "' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ 
			$txt .= crear_link($row['nick']) . ' ';
		}
		
	}

	$txt .= '</b></p></td></tr></table>';

}

//THEME
$txt_title = 'Subastas';
$txt_nav = array('Subastas');
$txt_menu = 'econ';
include('theme.php');
?>
