<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');
/*
pol_transacciones		(ID, pols, emisor_ID, receptor_ID, concepto, time)
pol_cuentas			(ID, nombre, user_ID, pols, nivel, time)
*/

if (($_GET['a'] == 'cuentas') AND ($_GET['b'] == 'crear')) {
	$txt_title = 'Crear nueva cuenta bancaria';
	$txt_nav = array('/pols'=>'Economía', 'Crear cuenta bancaria');

	$txt .= '<form action="/accion.php?a=pols&b=crear-cuenta" method="post">

<p>Nombre: <input type="text" name="nombre" size="20" maxlength="20" /> ' . boton('Crear Cuenta', false, false, false, $pol['config']['pols_cuentas']) . '</p>

<p><a href="/pols/cuentas"><b>Ver Cuentas Bancarias</b></a> &nbsp; <a href="/pols/"><b>Ver tus '.MONEDA.'</b></a></p>

</form>
';

} elseif (($_GET['a'] == 'cuentas') AND ($_GET['b'])) {

	$result = mysql_query("SELECT ID, nombre, user_ID, pols, nivel, time,
(SELECT nick FROM users WHERE ID = cuentas.user_ID LIMIT 1) AS nick
FROM cuentas
WHERE pais = '".PAIS."' AND ID = '" . $_GET['b'] . "'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)) {
		$ID = $row['ID'];
		$pols = $row['pols'];
		$nombre = $row['nombre'];
		$user_ID = $row['user_ID'];
		$nivel = $row['nivel'];
	}

	if ($ID) { //existe cuenta
		$txt_title = 'Cuenta bancaria: ' . $nombre;
		$txt_nav = array('/pols'=>'Economía', '/pols/cuentas'=>'Cuenta bancaria', $nombre);

		$result = mysql_unbuffered_query("SELECT COUNT(*) AS num FROM transacciones WHERE pais = '".PAIS."' AND (emisor_ID = '-" . $ID . "' OR receptor_ID = '-" . $ID . "')", $link);
		while($row = mysql_fetch_array($result)){ $total = $row['num']; }

		if (is_numeric($_GET['c'])) { $ahora = $_GET['c']; } 
		else { $ahora = ''; }

		paginacion('censo', '/pols/cuentas/' . $ID . '/', null, $ahora, $total, 200);


		$txt .= '<h1><span class="amarillo">'.pols($pols).' '.MONEDA.'</span> &nbsp; CUENTA: '.$nombre.' <span style="color:grey;">(ID: '.$ID.')</span> '.boton('&rarr;', '/pols/transferir/-'.$ID, false, 'small') . '</h1>
<br />
<p>' . $p_paginas . '</p>
<table border="0" cellspacing="3" cellpadding="0" class="pol_table">
<tr>
<th align="right">'.MONEDA.'</th>
<th>Ciudadano</th>
<th colspan="2">Concepto</th>
</tr>';

// concepto != 'CP' AND concepto != 'INEMPOL' AND concepto != 'Salario' AND 
	$result = mysql_query("SELECT ID, pols, concepto, time, receptor_ID, emisor_ID,
(SELECT nick FROM users WHERE ID = transacciones.emisor_ID LIMIT 1) AS nick_emisor,
(SELECT nick FROM users WHERE ID = transacciones.receptor_ID LIMIT 1) AS nick_receptor
FROM transacciones
WHERE pais = '".PAIS."' AND (emisor_ID = '-" . $ID . "' OR receptor_ID = '-" . $ID . "')
ORDER BY time DESC
LIMIT ".mysql_real_escape_string($p_limit), $link);
	while($row = mysql_fetch_array($result)) {

		if ($row['emisor_ID'] == '-' . $ID) { //doy
			$transferir_nick = $row['nick_receptor'];
			$transf = '&rarr; ' . crear_link($row['nick_receptor']);
			$transf_pre = '&rarr;';
			$transf_ID = $row['receptor_ID'];
			$pols = '-' . $row['pols'];
		} else { //recibo
			$transferir_nick = $row['nick_emisor'];
			$transf = '&larr; ' . crear_link($row['nick_emisor']);
			$transf_pre = '&larr;';
			$transf_ID = $row['emisor_ID'];
			$pols = $row['pols'];
		}

		if ($transf_ID < 0) {
			
			$cuenta_ID = substr($transf_ID, 1);

			$result2 = mysql_query("SELECT nombre FROM cuentas WHERE pais = '".PAIS."' AND ID = '".$cuenta_ID."' LIMIT 1", $link);
			while($row2 = mysql_fetch_array($result2)){ $cuenta_nombre = $row2['nombre']; }
			
			$transferir_nick = $transf_ID;
			$transf = $transf_pre . ' #<a href="/pols/cuentas/' . $cuenta_ID . '/"><b>' . $cuenta_nombre . '</b></a>';
		} 



		$txt .= '<tr><td align="right" valign="top"><b>' . pols($pols) . '</b></td><td valign="top">' . $transf . '</td><td>' . $row['concepto'] . '</td><td valign="top" align="right"><acronym title="' . $row['time'] . '">' . str_replace(" ", "&nbsp;", duracion(time() - strtotime($row['time']))) . '</acronym></td><td valign="top" align="right">'.boton('&rarr;', '/pols/transferir/'.strtolower($transferir_nick), false, 'small').'</td></tr>';
	}

	$txt .= '</table><p>'.$p_paginas.'</p><p><a href="/pols/cuentas/"><b>Ver Cuentas</b></a> &nbsp; <a href="/pols/"><b>Ver tus '.MONEDA.'</b></a></p>';




	}


} elseif ($_GET['a'] == 'cuentas') {
	if ($pol['nivel'] < 98) {
		$disabled = ' disabled="disabled"';
	}
	else {
		$disabled = '';
	}
	$txt_title = 'Cuentas Bancarias';
	$txt_nav = array('/pols'=>'Economía', 'Cuentas bancarias');
	$txt .= '<h1>Cuentas:</h1>

<p>' . boton('Crear Cuenta', '/pols/cuentas/crear/', false, false, $pol['config']['pols_cuentas']) . '</p>

<form action="/accion.php?a=exencion_impuestos" method="post">

<table border="0" cellspacing="2" cellpadding="0" class="pol_table">
<tr>
<th align="right">'.MONEDA.'</th>
<th>Cuenta</th>
<th>Propietario</th>
<th>Sin impuestos</th>
<th></th>
</tr>';

	$result = mysql_query("SELECT ID, nombre, user_ID, pols, nivel, time, exenta_impuestos,
(SELECT nick FROM users WHERE ID = cuentas.user_ID LIMIT 1) AS nick
FROM cuentas
WHERE pais = '".PAIS."' 
ORDER BY nivel DESC, pols DESC", $link);
	while($row = mysql_fetch_array($result)) {
		if ($row['nivel'] == 0) {
			$propietario = crear_link($row['nick']);
			$checkbox = '<input class="checkbox_impuestos" type="checkbox" name="exenta_impuestos'.$row['ID'].'" value="1"'.$disabled;
			if ($row['exenta_impuestos'] == '1') {
				$checkbox .= ' checked="checked"';
			}
			$checkbox .= ' />';
		} elseif ($row['nivel']) {
			$propietario = '<b>Gobierno</b> (nivel: ' . $row['nivel'] . ')';
			$checkbox = '';
		} else {
			$propietario = '??';
		}
		if (($row['pols'] == '0') AND ($row['user_ID'] == $pol['user_ID']) AND ($row['nivel'] == '0')) {
			$boton = boton('X', '/accion.php?a=pols&b=eliminar-cuenta&ID=' . $row['ID'], '&iquest;Seguro que quieres ELIMINAR tu cuenta Bancaria?');
		} else { $boton = ''; }
		$txt .= '<tr><td align="right">' . pols($row['pols']) . '</td><td><a href="/pols/cuentas/' . $row['ID'] . '/"><b>' . $row['nombre'] . '</b></a></td><td>' . $propietario . '</td><td align="center">'.$checkbox.'</td><td>'.boton('&rarr;', '/pols/transferir/-'.$row['ID'], false, 'small').$boton.'</td></tr>';
	}
        $txt .= '</table>';
	if ($pol['nivel'] >= 98) {
		$txt .= '<input type="submit" value="Cambiar exención de impuestos" /></form>';
	}
	$txt .= '<p>' . boton('Crear Cuenta', '/pols/cuentas/crear/', false, false, $pol['config']['pols_cuentas']) . ' &nbsp; <a href="/pols/"><b>Ver tus '.MONEDA.'</b></a></p>';



} elseif (nucleo_acceso('ciudadanos')) {

	if (PAIS != $pol['pais']) { redirect('http://'.strtolower($pol['pais']).'.'.DOMAIN.'/pols'); }

	if (($_GET['a']) AND ($_GET['b'])) { 
		$pre_nick = $_GET['b']; 
		$select1_ok = '';
		if ($_GET['c']) { $pre_pols = $_GET['c']; } else { $pre_pols = ''; }
	}
	if ($_GET['d']) { $pre_concepto = str_replace("-", " ", $_GET['d']) . ': '; } else { $pre_concepto = ''; }


	if ($_GET['b'] < 0) { 
		$select_pre = substr($_GET['b'], 1); 
		$pre_nick = '';
		$select_ok = ' checked="checked"';  
	} else {
		$select1_ok = ' checked="checked"';
	}
	$result = mysql_query("SELECT ID, nombre, pols, user_ID, nivel
FROM cuentas WHERE pais = '".PAIS."'  
ORDER BY nivel DESC, nombre ASC", $link);
	while($row = mysql_fetch_array($result)){
		if (($row['user_ID'] == $pol['user_ID']) OR (($pol['nivel'] >= $row['nivel']) AND ($row['nivel'] != 0) AND ($pol['nivel'] != 120))) {
			if ($row['pols'] < 1) { $extra = ' disabled="disabled"'; } else { $extra = ''; }
			$select_origen .= '<option value="' . $row['ID'] . '"' . $extra . '>' . pols($row['pols']) . ' - ' . $row['nombre'] . '</option>' . "\n";
		}
		if ($select_pre == $row['ID']) { $extra2 = ' selected="selected"'; } else { $extra2 = ''; }
		$select_cuentas .= '<option value="' . $row['ID'] . '"' . $extra2 . '>' . $row['nombre'] . '</option>' . "\n";
	}
	if ($select_origen) { $select_origen = '<optgroup label="Cuentas">' . $select_origen . '</optgroup>'; }

	$txt_title = 'Moneda';
	$txt_nav = array('/pols'=>'Economía');

if ($_GET['c']) { $focus = 'concepto'; } elseif ($_GET['b']) { $focus = 'pols'; } else { $focus = 'ciudadano'; }
$txt_header .= '
<style type="text/css">
.transf li { margin-bottom:10px; }
</style>
<script type="text/javascript">

window.onload = function(){
	$("#' . $focus . '").focus();
}

function click_cuenta() {
	$("#ciudadano").attr("value","");
	$("#radio_ciudadano").removeAttr("checked");
	$("#radio_cuenta").attr("checked","checked");
}

function click_ciudadano() {
	$("#radio_cuenta").removeAttr("checked");
	$("#radio_ciudadano").attr("checked","checked");
}

</script>';

	// load config full
	$result = mysql_query("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND dato = 'arancel_salida'", $link);
	while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

	if ($pol['pols'] < 1) { $extra_personal = ' disabled="disabled"'; } else { $extra_personal = ''; }

	$txt .= '	<h1><span class="amarillo">' . pols($pol['pols']) . ' '.MONEDA.'</span> &nbsp; (<a href="/info/economia/">Economia Global</a>)</h1>


<div class="amarillo" style="margin:20px 8px 8px 8px;">
<form action="/accion.php?a=pols&b=transferir" method="post">
<ol class="transf">

<li><b>Origen:</b> &nbsp;&larr; 
<select name="origen">
<option value="0"' . $extra_personal . '>' . pols($pol['pols']) . ' '.MONEDA.'</option>
' . $select_origen . '
</select></li>

<li><b>Destino:</b> &rarr; 
<table border="0">
<tr onclick="click_ciudadano();">
<td><input id="radio_ciudadano" type="radio" name="destino" value="ciudadano"' . $select1_ok . ' />Ciudadano</td>
<td><input tabindex="1" type="text" id="ciudadano" name="ciudadano" size="14" maxlength="14" value="' . $pre_nick . '" /> &nbsp; <acronym title="Si la transferencia es internacional, tu pais se quedar&aacute; este porcentaje en concepto de arancel.">Arancel: <b style="color:red;">'.$pol['config']['arancel_salida'].'%</b></acronym></td>
<td>&nbsp; <a href="/info/censo/">Ver censo</a></td>
</tr>
<tr onclick="click_cuenta();">
<td><input id="radio_cuenta" type="radio" name="destino" value="cuenta"' . $select_ok . ' />Cuenta</td>
<td><select id="cuenta" name="cuenta"><option value=""></option>' . $select_cuentas . '</select></td>
<td>&nbsp; <a href="/pols/cuentas/">Ver cuentas</a></td></tr>
</table>
</li>

<li><input value="' . $pre_pols . '" id="pols" type="text" tabindex="2" name="pols" size="3" maxlength="5" style="text-align:right;" class="pols" /> '.MONEDA.' <b>Concepto:</b> <input id="concepto" type="text" value="' . $pre_concepto . '" tabindex="3" name="concepto" size="30" maxlength="90"  /></li>

<li><input type="submit" value="Transferir" onClick="if (!confirm(\'&iquest;Estas seguro de querer transferir monedas?\')) { return false; }" /></li>

</ol>
</form>
</div>


<table border="0" cellspacing="3" cellpadding="0" class="pol_table">
<tr>
<th align="right">'.MONEDA.'</th>
<th>Ciudadano/Cuenta</th>
<th colspan="3">Concepto</th>
</tr>';

	$result = mysql_unbuffered_query("SELECT COUNT(*) AS num FROM transacciones WHERE pais = '".PAIS."' AND (emisor_ID = '" . $pol['user_ID'] . "' OR receptor_ID = '" . $pol['user_ID'] . "')", $link);
	while($row = mysql_fetch_array($result)){ $total = $row['num']; }

	if (is_numeric($_GET['a'])) { $ahora = $_GET['a']; } 
	else { $ahora = ''; }

	paginacion('censo', '/pols/', null, $ahora, $total, 15);

	$result = mysql_query("SELECT ID, pols, concepto, time, receptor_ID, emisor_ID,
(SELECT nick FROM users WHERE ID = transacciones.emisor_ID LIMIT 1) AS nick_emisor,
(SELECT nick FROM users WHERE ID = transacciones.receptor_ID LIMIT 1) AS nick_receptor
FROM transacciones
WHERE pais = '".PAIS."' AND (emisor_ID = '" . $pol['user_ID'] . "' OR receptor_ID = '" . $pol['user_ID'] . "')
ORDER BY time DESC
LIMIT " . mysql_real_escape_string($p_limit), $link);
	while($row = mysql_fetch_array($result)) {

		if ($row['nick_emisor'] == $pol['nick']) { //doy
			$transferir_nick = $row['nick_receptor'];
			$transf = '&rarr; ' . crear_link($row['nick_receptor']);
			$transf_pre = '&rarr;';
			$transf_ID = $row['receptor_ID'];
			$pols = '-' . $row['pols'];
		} else { //recibo
			$transferir_nick = $row['nick_emisor'];
			$transf = '&larr; ' . crear_link($row['nick_emisor']);
			$transf_pre = '&larr;';
			$transf_ID = $row['emisor_ID'];
			$pols = $row['pols'];
		}

		if ($transf_ID < 0) {
			
			$cuenta_ID = substr($transf_ID, 1);

			$result2 = mysql_query("SELECT nombre FROM cuentas WHERE pais = '".PAIS."' AND ID = '" . $cuenta_ID . "' LIMIT 1", $link);
			while($row2 = mysql_fetch_array($result2)){ $cuenta_nombre = $row2['nombre']; }
			
			$transferir_nick = $transf_ID;
			$transf = $transf_pre . ' #<a href="/pols/cuentas/' . $cuenta_ID . '/"><b>' . $cuenta_nombre . '</b></a>';
		} 


		$txt .= '<tr><td align="right" valign="top"><b>'.pols($pols).'</b></td><td valign="top">'.$transf.'</td><td>'.$row['concepto'].'</td><td valign="top" align="right"><acronym title="'.$row['time'].'">'.str_replace(" ", "&nbsp;", duracion(time() - strtotime($row['time']))).'</acronym></td><td valign="top" align="right">'.boton('&rarr;', '/pols/transferir/'.strtolower($transferir_nick), false, 'small').'</td></tr>';
	}
	$txt .= '</table><p>'.$p_paginas.'</p>';

}



//THEME
$txt_menu = 'econ';
include('theme.php');
?>
