<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$txt_tab['/pols'] = _('Pols');
$txt_tab['/pols/transaut'] = _('Transacciones automáticas');
/*
pol_transacciones		(ID, pols, emisor_ID, receptor_ID, concepto, time)
pol_cuentas			(ID, nombre, user_ID, pols, nivel, time)
*/

if (($_GET[1] == 'cuentas') AND ($_GET[2] == 'crear')) {
	$txt_title = 'Crear nueva cuenta bancaria';
	$txt_nav = array('/pols'=>'Economía', 'Crear cuenta bancaria');

	echo '<form action="/accion/pols/crear-cuenta" method="post">

<p>Nombre: <input type="text" name="nombre" size="20" maxlength="20" /> ' . boton('Crear Cuenta', false, false, false, $pol['config']['pols_cuentas']) . '</p>

<p><a href="/pols/cuentas"><b>Ver Cuentas Bancarias</b></a> &nbsp; <a href="/pols/"><b>Ver tus '.MONEDA.'</b></a></p>

</form>
';
}elseif (($_GET[1] == 'cuentas') AND ($_GET[3] == 'apoderados')) {
	$txt_title = 'Gestionar apodeadors';
	$txt_nav = array('/pols'=>'Economía', 'Cuentas');

	$result = mysql_query_old("SELECT ID, nombre, user_ID, pols, nivel, time,
	(SELECT nick FROM users WHERE ID = cuentas.user_ID LIMIT 1) AS nick
	FROM cuentas
	WHERE pais = '".PAIS."' AND ID = '" . $_GET[2] . "'
	LIMIT 1", $link);
		while($row = mysqli_fetch_array($result)) {
			$user_ID = $row['user_ID'];
	
			if ($user_ID == $pol['user_ID']){
				echo '<form action="/accion/pols/anadir-apoderado" method="post">

				<p>Apoderado: <input type="text" name="apoderado" size="20" maxlength="20" /> ' . boton('Añadir apoderado', false, false, false, '0') . '</p>'
				
				

				echo '</form>
				';
			}else{
				echo 'No tiene acceso para gestionar esta cuenta';
			}
	
		}

} elseif (($_GET[1] == 'cuentas') AND ($_GET[2])) {
	
	$result = mysql_query_old("SELECT ID, nombre, user_ID, pols, nivel, time,
(SELECT nick FROM users WHERE ID = cuentas.user_ID LIMIT 1) AS nick
FROM cuentas
WHERE pais = '".PAIS."' AND ID = '" . $_GET[2] . "'
LIMIT 1", $link);
	while($row = mysqli_fetch_array($result)) {
		$ID = $row['ID'];
		$pols = $row['pols'];
		$nombre = $row['nombre'];
		$user_ID = $row['user_ID'];

		if ($user_ID == $pol['user_ID']){
			$txt_tab['/pols/cuentas/'.$_GET[2].'/apoderados'] = _('Gestionar apoderados');
		}

		$nivel = $row['nivel'];
	}

	if ($ID) { //existe cuenta
		$txt_title = 'Cuenta bancaria: ' . $nombre;
		$txt_nav = array('/pols'=>'Economía', '/pols/cuentas'=>'Cuenta bancaria', $nombre);

		$result = mysql_query_old("SELECT COUNT(*) AS num FROM transacciones WHERE pais = '".PAIS."' AND (emisor_ID = '-" . $ID . "' OR receptor_ID = '-" . $ID . "') AND periodicidad is null", $link);
		while($row = mysqli_fetch_array($result)){ $total = $row['num']; }

		if (is_numeric($_GET[3])) { $ahora = $_GET[3]; } 
		else { $ahora = ''; }

		paginacion('censo', '/pols/cuentas/' . $ID . '/', null, $ahora, $total, 200);


		echo '<h1><span class="amarillo">'.pols($pols).' '.MONEDA.'</span> &nbsp; CUENTA: '.$nombre.' <span style="color:grey;">(ID: '.$ID.')</span> '.boton('&rarr;', '/pols/transferir/-'.$ID, false, 'small') . '</h1>
<br />
<p>' . $p_paginas . '</p>
<table border="0" cellspacing="3" cellpadding="0" class="pol_table">
<tr>
<th align="right">'.MONEDA.'</th>
<th>Ciudadano</th>
<th colspan="2">Concepto</th>
</tr>';

// concepto != 'CP' AND concepto != 'INEMPOL' AND concepto != 'Salario' AND 
	$result = mysql_query_old("SELECT ID, pols, concepto, time, receptor_ID, emisor_ID,
(SELECT nick FROM users WHERE ID = transacciones.emisor_ID LIMIT 1) AS nick_emisor,
(SELECT nick FROM users WHERE ID = transacciones.receptor_ID LIMIT 1) AS nick_receptor
FROM transacciones
WHERE pais = '".PAIS."' AND (emisor_ID = '-" . $ID . "' OR receptor_ID = '-" . $ID . "')  AND periodicidad is null
ORDER BY time DESC
LIMIT ".$p_limit, $link);
	while($row = mysqli_fetch_array($result)) {

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

			$result2 = mysql_query_old("SELECT nombre FROM cuentas WHERE pais = '".PAIS."' AND ID = '".$cuenta_ID."' LIMIT 1", $link);
			while($row2 = mysqli_fetch_array($result2)){ $cuenta_nombre = $row2['nombre']; }
			
			$transferir_nick = $transf_ID;
			$transf = $transf_pre . ' #<a href="/pols/cuentas/' . $cuenta_ID . '/"><b>' . $cuenta_nombre . '</b></a>';
		} 



		echo '<tr><td align="right" valign="top"><b>' . pols($pols) . '</b></td><td valign="top">' . $transf . '</td><td>' . $row['concepto'] . '</td><td valign="top" align="right"><acronym title="' . $row['time'] . '">' . str_replace(" ", "&nbsp;", duracion(time() - strtotime($row['time']))) . '</acronym></td><td valign="top" align="right">'.boton('&rarr;', '/pols/transferir/'.strtolower($transferir_nick), false, 'small').'</td></tr>';
	}

	echo '</table><p>'.$p_paginas.'</p><p><a href="/pols/cuentas/"><b>Ver Cuentas</b></a> &nbsp; <a href="/pols/"><b>Ver tus '.MONEDA.'</b></a></p>';




	}

} elseif ($_GET[1] == 'transaut') {
	$pre_desde = date("d/m/Y");
	if (PAIS != $pol['pais']) { redirect('http://'.strtolower($pol['pais']).'.'.DOMAIN.'/pols'); }

		if (($_GET[1]) AND ($_GET[2])) { 
			$pre_nick = $_GET[2]; 
			$select1_ok = '';
			if ($_GET[3]) { $pre_pols = $_GET[3]; } else { $pre_pols = ''; }
		}
		if ($_GET[4]) { $pre_concepto = str_replace("-", " ", $_GET[4]) . ': '; } else { $pre_concepto = ''; }
	
	
		if ($_GET[2] < 0) { 
			$select_pre = substr($_GET[2], 1); 
			$pre_nick = '';
			$select_ok = ' checked="checked"';  
		} else {
			$select1_ok = ' checked="checked"';
		}
		$result = mysql_query_old("SELECT ID, nombre, pols, user_ID, nivel
	FROM cuentas WHERE pais = '".PAIS."'  
	ORDER BY nivel DESC, nombre ASC", $link);
		while($row = mysqli_fetch_array($result)){
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
	
	if ($_GET[3]) { $focus = 'concepto'; } elseif ($_GET[2]) { $focus = 'pols'; } else { $focus = 'ciudadano'; }
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
		$result = mysql_query_old("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND dato = 'arancel_salida'", $link);
		while ($row = mysqli_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }
	
		if ($pol['pols'] < 1) { $extra_personal = ' disabled="disabled"'; } else { $extra_personal = ''; }
	
		echo '	<h1><span class="amarillo">' . pols($pol['pols']) . ' '.MONEDA.'</span> &nbsp; (<a href="/info/economia/">Economia Global</a>)</h1>
	
	
	<div class="amarillo" style="margin:20px 8px 8px 8px;">
	<form action="/accion/pols/transaut" method="post">
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
	<li><b>Periodicidad:</b> &rarr; 
	<table border="0">
	<tr>
	<td><input id="radio_diaria" type="radio" name="periodicidad" value="D" checked="checked" />Diaria&nbsp;
	<input id="radio_semanal" type="radio" name="periodicidad" value="S">Semanal
	<input id="radio_mensual" type="radio" name="periodicidad" value="M">Mensual</td>
	</tr>
	</table>
	</li>

	<li><input value="' . $pre_pols . '" id="pols" type="text" tabindex="4" name="pols" size="3" maxlength="5" style="text-align:right;" class="pols" /> '.MONEDA.' <b>Concepto:</b> <input id="concepto" type="text" value="' . $pre_concepto . '" tabindex="5" name="concepto" size="30" maxlength="90"  /></li>
	
	<li><input type="submit" value="Crear" onClick="if (!confirm(\'&iquest;Estas seguro de querer crear esta transferencia automática?\')) { return false; }" /></li>
	
	</ol>
	</form>
	</div>
	
	
	<table border="0" cellspacing="3" cellpadding="0" class="pol_table">
	<tr>
	<th align="right">'.MONEDA.'</th>
	<th>Origen</th>
	<th>Destino</th>
	<th>Periodicidad</th>
	<th>Concepto</th>
	<th>Eliminar</th>
	</tr>';
	
		$result = mysql_query_old("SELECT COUNT(*) AS num FROM transacciones WHERE pais = '".PAIS."' AND ((emisor_ID = '" . $pol['user_ID'] . "') OR (emisor_ID in (select CONCAT('-', ID) from cuentas where user_ID ='" . $pol['user_ID'] . "'))) AND periodicidad is not null", $link);
		while($row = mysqli_fetch_array($result)){ $total = $row['num']; }
	
		if (is_numeric($_GET[1])) { $ahora = $_GET[1]; } 
		else { $ahora = ''; }
	
		paginacion('censo', '/pols/', null, $ahora, $total, 15);
	
		$result = mysql_query_old("SELECT ID, pols, concepto, time, receptor_ID, emisor_ID, periodicidad,
	coalesce((SELECT nick FROM users WHERE ID = transacciones.emisor_ID LIMIT 1),
	(SELECT concat(u.nick,'(', c.nombre,')') from users u, cuentas c where u.ID=(select user_ID from cuentas where ID=SUBSTRING(transacciones.emisor_ID, 2)) and c.id=SUBSTRING(transacciones.emisor_ID, 2) ))
	AS nick_emisor,
	coalesce((SELECT nick FROM users WHERE ID = transacciones.receptor_ID LIMIT 1),
	(SELECT concat(u.nick,'(', c.nombre,')') from users u, cuentas c where u.ID=(select user_ID from cuentas where ID=SUBSTRING(transacciones.receptor_ID, 2)) and c.id=SUBSTRING(transacciones.receptor_ID, 2) )) AS nick_receptor
	FROM transacciones
	WHERE pais = '".PAIS."' AND ((emisor_ID = '" . $pol['user_ID'] . "') OR (emisor_ID in (select CONCAT('-', ID) from cuentas where user_ID ='" . $pol['user_ID'] . "'))) AND periodicidad is not null
	ORDER BY time DESC
	LIMIT ".$p_limit, $link);

		while($row = mysqli_fetch_array($result)) {
			$transaccion_ID =  $row['ID'];
			$periodicidad = $row['periodicidad'];
			$receptor_nick = $row['nick_receptor'];
			$emisor_nick = $row['nick_emisor'];
			$pols = $row['pols'];
			
			if ($periodicidad == "D"){
				$periodicidad = "Diaria";
			}elseif ($periodicidad == "S"){
				$periodicidad = "Semanal";
			}elseif ($periodicidad == "M"){
				$periodicidad = "Mensual";
			}
				
			echo '<tr><td align="right" valign="top"><b>'.pols($pols).'</b></td><td valign="top">'.$emisor_nick.'</td><td valign="top">'.$receptor_nick.'</td><td valign="top">'.$periodicidad.'</td><td>'.$row['concepto'].'</td><td valign="top" align="right">'.boton(_('Eliminar'), '/accion/pols/transaut/eliminar/'.strtolower($transaccion_ID), false, 'red').'</td></tr>';
		}
		echo '</table><p>'.$p_paginas.'</p>';

} elseif ($_GET[1] == 'cuentas') {
	if ($pol['nivel'] < 98) {
		$disabled = ' disabled="disabled"';
	}
	else {
		$disabled = '';
	}
	$txt_title = 'Cuentas Bancarias';
	$txt_nav = array('/pols'=>'Economía', 'Cuentas bancarias');
	echo '<h1>Cuentas:</h1>

<p>' . boton('Crear Cuenta', '/pols/cuentas/crear/', false, false, $pol['config']['pols_cuentas']) . '</p>

<form action="/accion/exencion_impuestos" method="post">

<table border="0" cellspacing="2" cellpadding="0" class="pol_table">
<tr>
<th align="right">'.MONEDA.'</th>
<th>Cuenta</th>
<th>Propietario</th>
<th>Sin impuestos</th>
<th></th>
</tr>';

	$result = mysql_query_old("SELECT ID, nombre, user_ID, pols, nivel, time, exenta_impuestos,
(SELECT nick FROM users WHERE ID = cuentas.user_ID LIMIT 1) AS nick
FROM cuentas
WHERE pais = '".PAIS."' 
ORDER BY nivel DESC, pols DESC", $link);
	while($row = mysqli_fetch_array($result)) {
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
			$boton = boton('X', '/accion/pols/eliminar-cuenta?ID=' . $row['ID'], '&iquest;Seguro que quieres ELIMINAR tu cuenta Bancaria?');
		} else { $boton = ''; }
		echo '<tr><td align="right">' . pols($row['pols']) . '</td><td><a href="/pols/cuentas/' . $row['ID'] . '/"><b>' . $row['nombre'] . '</b></a></td><td>' . $propietario . '</td><td align="center">'.$checkbox.'</td><td>'.boton('&rarr;', '/pols/transferir/-'.$row['ID'], false, 'small').$boton.'</td></tr>';
	}
        echo '</table>';
	if ($pol['nivel'] >= 98) {
		echo '<input type="submit" value="Cambiar exención de impuestos" /></form>';
	}
	echo '<p>' . boton('Crear Cuenta', '/pols/cuentas/crear/', false, false, $pol['config']['pols_cuentas']) . ' &nbsp; <a href="/pols/"><b>Ver tus '.MONEDA.'</b></a></p>';



} elseif (nucleo_acceso('ciudadanos')) {

	if (PAIS != $pol['pais']) { redirect('http://'.strtolower($pol['pais']).'.'.DOMAIN.'/pols'); }

	if (($_GET[1]) AND ($_GET[2])) { 
		$pre_nick = $_GET[2]; 
		$select1_ok = '';
		if ($_GET[3]) { $pre_pols = $_GET[3]; } else { $pre_pols = ''; }
	}
	if ($_GET[4]) { $pre_concepto = str_replace("-", " ", $_GET[4]) . ': '; } else { $pre_concepto = ''; }


	if ($_GET[2] < 0) { 
		$select_pre = substr($_GET[2], 1); 
		$pre_nick = '';
		$select_ok = ' checked="checked"';  
	} else {
		$select1_ok = ' checked="checked"';
	}
	$result = mysql_query_old("SELECT ID, nombre, pols, user_ID, nivel
FROM cuentas WHERE pais = '".PAIS."'  
ORDER BY nivel DESC, nombre ASC", $link);
	while($row = mysqli_fetch_array($result)){
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

if ($_GET[3]) { $focus = 'concepto'; } elseif ($_GET[2]) { $focus = 'pols'; } else { $focus = 'ciudadano'; }
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
	$result = mysql_query_old("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND dato = 'arancel_salida'", $link);
	while ($row = mysqli_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

	if ($pol['pols'] < 1) { $extra_personal = ' disabled="disabled"'; } else { $extra_personal = ''; }

	echo '	<h1><span class="amarillo">' . pols($pol['pols']) . ' '.MONEDA.'</span> &nbsp; (<a href="/info/economia/">Economia Global</a>)</h1>


<div class="amarillo" style="margin:20px 8px 8px 8px;">
<form action="/accion/pols/transferir" method="post">
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

	$result = mysql_query_old("SELECT COUNT(*) AS num FROM transacciones WHERE pais = '".PAIS."' AND (emisor_ID = '" . $pol['user_ID'] . "' OR receptor_ID = '" . $pol['user_ID'] . "') AND periodicidad is null", $link);
	while($row = mysqli_fetch_array($result)){ $total = $row['num']; }

	if (is_numeric($_GET[1])) { $ahora = $_GET[1]; } 
	else { $ahora = ''; }

	paginacion('censo', '/pols/', null, $ahora, $total, 15);

	$result = mysql_query_old("SELECT ID, pols, concepto, time, receptor_ID, emisor_ID,
(SELECT nick FROM users WHERE ID = transacciones.emisor_ID LIMIT 1) AS nick_emisor,
(SELECT nick FROM users WHERE ID = transacciones.receptor_ID LIMIT 1) AS nick_receptor
FROM transacciones
WHERE pais = '".PAIS."' AND (emisor_ID = '" . $pol['user_ID'] . "' OR receptor_ID = '" . $pol['user_ID'] . "') AND periodicidad is null
ORDER BY time DESC
LIMIT ".$p_limit, $link);
	while($row = mysqli_fetch_array($result)) {

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

			$result2 = mysql_query_old("SELECT nombre FROM cuentas WHERE pais = '".PAIS."' AND ID = '" . $cuenta_ID . "' LIMIT 1", $link);
			while($row2 = mysqli_fetch_array($result2)){ $cuenta_nombre = $row2['nombre']; }
			
			$transferir_nick = $transf_ID;
			$transf = $transf_pre . ' #<a href="/pols/cuentas/' . $cuenta_ID . '/"><b>' . $cuenta_nombre . '</b></a>';
		} 


		echo '<tr><td align="right" valign="top"><b>'.pols($pols).'</b></td><td valign="top">'.$transf.'</td><td>'.$row['concepto'].'</td><td valign="top" align="right"><acronym title="'.$row['time'].'">'.str_replace(" ", "&nbsp;", duracion(time() - strtotime($row['time']))).'</acronym></td><td valign="top" align="right">'.boton('&rarr;', '/pols/transferir/'.strtolower($transferir_nick), false, 'small').'</td></tr>';
	}
	echo '</table><p>'.$p_paginas.'</p>';

}



//THEME
$txt_menu = 'econ';

?>
