<?php 
include('inc-login.php');

if (($pol['user_ID']) AND ($pol['pais'] == PAIS)) {

if ($_GET['a'] == 'mensajes-enviados') {

	$txt_title = 'Tus mensajes enviados';
	$txt .= '<h1><img src="'.IMG.'varios/email.gif" alt="Msg" /> <a href="/msg/">Mensajes privados</a>: Mensajes enviados</h1>';
	
	$txt .= '<p>' . boton('Enviar mensaje', '/msg/enviar/') . ' &nbsp; <span class="gris">Mensajes enviados por ti.</span></p>';

	$txt .= '<table border="0" cellspacing="0" cellpadding="0" width="100%" class="pol_table" id="msg_table">
<tr>
<th></th>
<th>Receptor</th>
<th width="100%">Tu mensaje</th>
<th></th>
</tr>';

	$result = mysql_query("SELECT 
ID, envia_ID, recibe_ID, time, text,
(SELECT nick FROM users WHERE users.ID = recibe_ID LIMIT 1) AS nick_envia,
(SELECT nombre FROM ".SQL."estudios WHERE ".SQL."estudios.ID = cargo LIMIT 1) AS cargo
FROM ".SQL_MENSAJES."
WHERE envia_ID = '" . $pol['user_ID'] . "'
ORDER BY time DESC
LIMIT 50", $link);
	while($r = mysql_fetch_array($result)){


		$txt .= '<tr><td valign="top"></td><td valign="top" align="right"><b>' . crear_link($r['nick_envia']) . '</b><br /><b>' . str_replace(' ', '&nbsp;', $r['cargo']) . '</b><acronym title="' . $r['time'] . '" style="font-size:12px;"><span class="timer" value="'.strtotime($r['time']).'"></span></acronym></td><td valign="top">' . $r['text'] . '<hr /></td><td valign="top">' . boton('Responder', '/msg/' . strtolower($r['nick_envia']) . '/') . '</td><td valign="top">' . boton('X', '/accion.php?a=borrar-mensaje&ID=' . $r['ID']) . '</td></tr>' . "\n";
	}

	$txt .= '</table><p><b>(*)</b> <em>Esta p&aacute;gina est&aacute; en versi&oacute;n ALPHA, el motivo es que cabe la "extra&ntilde;a" posibilidad de que falten algunos mensajes. Esto suceder&aacute; cuando el RECEPTOR elimine tu mensaje enviado (ya que el mensaje se borra de la base de datos). Puede resultar incoherente la ausencia de algun mensaje enviado.</em></p>';





} elseif ($_GET['a']) { // ENVIAR MENSAJE

	// load config
	$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
	while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

	$txt_title = 'Enviar mensaje';
	if ($_GET['a'] == 'cargos') {
		$pre_cargo = $_GET['b'];
	} else if ($_GET['a'] != 'enviar') { 
		$pre_nick = strtolower($_GET['a']); 
	}

	$result = mysql_query("SELECT ID, nombre,
(SELECT COUNT(ID) FROM ".SQL."estudios_users WHERE cargo = '1' AND ID_estudio = ".SQL."estudios.ID LIMIT 1) AS cargos_num
FROM ".SQL."estudios
WHERE asigna != '-1'
ORDER BY nivel DESC", $link);
	while($r = mysql_fetch_array($result)){
		if ($r['cargos_num'] > 0) {
			$select_todoscargos .= '<option value="' . $r['ID'] . '"'.($pre_cargo==$r['ID']?' selected="selected"':'').'>' . $r['cargos_num'] . ' &nbsp; ' . $r['nombre'] . '</option>';
		}
	}
	$select_todoscargos .= '<option value="SC"'.($pre_cargo=='SC'?' selected="selected"':'').'>&nbsp; &nbsp; Supervisores del Censo</option>';

	//tus cargos
	$result = mysql_query("SELECT ID_estudio, 
(SELECT nombre FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nombre
FROM ".SQL."estudios_users 
WHERE cargo = '1'
AND user_ID = '" . $pol['user_ID'] . "'
ORDER BY nombre ASC", $link);
	while($r = mysql_fetch_array($result)){
		$select_cargos .= '<option value="' . $r['ID_estudio'] . '">' . $r['nombre'] . '</option>';
	}


	$txt_header .= '
<script type="text/javascript">

window.onload = function(){
	$("'.($_GET['a'] != 'enviar'?'#text':'#ciudadano').'").focus();
}

function click_form(tipo) {
	$("#ciudadano").attr("value","");

	switch (tipo) {
	case "ciudadano":
		$("#urgente").removeAttr("disabled","disabled");

		$("#radio_cargos").removeAttr("checked");
		$("#radio_todos").removeAttr("checked");
		$("#radio_ciudadano").attr("checked","checked");
		break;

	case "cargos":
		$("#urgente").attr("disabled","disabled");
		$("#urgente").removeAttr("checked");

		$("#radio_ciudadano").removeAttr("checked");
		$("#radio_todos").removeAttr("checked");
		$("#radio_cargos").attr("checked","checked");
		break;

	case "todos":
		$("#urgente").attr("disabled","disabled");
		$("#urgente").removeAttr("checked");

		$("#radio_ciudadano").removeAttr("checked");
		$("#radio_cargos").removeAttr("checked");
		$("#radio_todos").attr("checked","checked");
		break;
	}
}

</script>';

	$disabled_todos = '';
	if ($pol['config']['pols_mensajetodos'] > $pol['pols']) { $disabled_todos = ' disabled="disabled"'; }
	$txt .= '<h1><img src="'.IMG.'varios/email.gif" alt="Msg" /> <a href="/msg/">Mensajes privados</a>: Enviar mensaje</h1>

<form action="/accion.php?a=enviar-mensaje" method="post">

<p><b>Destino:</b><table border="0" style="margin-top:-15px;">
<tr onclick="click_form(\'ciudadano\');">
<td><input id="radio_ciudadano" type="radio" name="para" value="ciudadano"'.(!$pre_cargo?' checked="checked"':'').' />Ciudadano:</td>
<td><input id="ciudadano" tabindex="1" type="text" name="nick" value="' . $pre_nick . '" style="font-size:17px;" /> (Puedes indicar hasta 9 ciudadanos separados por espacios)</td>
</tr>
<tr onclick="click_form(\'cargos\');">
<td><input id="radio_cargos" type="radio" name="para" value="cargo"'.($pre_cargo?' checked="checked"':'').' />Cargos:</td>
<td><select name="cargo_ID" style="color:green;font-weight:bold;font-size:17px;"><option name="" value=""></option>' . $select_todoscargos . '</select> (env&iacute;o m&uacute;ltiple, cuidado)</td>
</tr>
<tr>
'.(ECONOMIA?'<td colspan="2"><input id="radio_todos" type="radio" name="para" value="todos"' . $disabled_todos . ' onclick="click_form(\'todos\');" />Mensaje Global a todos los Ciudadanos (' . $pol['config']['info_censo'] . '). ' . pols($pol['config']['pols_mensajetodos']) . ' '.MONEDA.'.</td>':'').'
</tr>
</table>
</p>

<p><b>Mensaje:</b><br />
<textarea tabindex="2" name="text" style="color:green;font-weight:bold;width:550px;height:200px;"></textarea></p>

<input type="hidden" name="calidad" value="0" />

<p><input type="submit" value="Enviar" style="font-size:24px;" /> &nbsp; <input type="checkbox" name="urgente" value="1" id="urgente" /> Env&iacute;o urgente. (el receptor recibir&aacute; un email)'.(ECONOMIA?' '.pols($pol['config']['pols_mensajeurgente']) . ' '.MONEDA:'').'</form></p>';



} else {
	$txt_title = $pol['msg'] . ' mensajes recibidos';
	$txt .= '<h1><img src="'.IMG.'varios/email.gif" alt="Msg" /> Mensajes privados: <a href="/msg/mensajes-enviados/">Mensajes enviados</a></h1>

<p>' . boton('Enviar mensaje', '/msg/enviar/') . ' &nbsp; <span class="gris">Tienes <b>' . $pol['msg'] . '</b> mensajes</span></p>

<table border="0" cellspacing="0" cellpadding="0" width="100%" class="pol_table" id="msg_table">
<tr>
<th colspan="2"><span style="float:right;">De &nbsp; &nbsp;</span>
<button onClick="window.location.href=\'/accion.php?a=mensaje-leido&ID=all\';" style="color:#666;" />Todo leido</button>
</th>
<th>Mensaje</th>
<th></th>
</tr>';

	$result = mysql_query("SELECT 
ID, envia_ID, recibe_ID, time, text, leido, cargo, recibe_masivo,
(SELECT nick FROM users WHERE users.ID = envia_ID LIMIT 1) AS nick_envia,
(SELECT nombre FROM ".SQL."estudios WHERE ".SQL."estudios.ID = cargo LIMIT 1) AS cargo_nom
FROM ".SQL_MENSAJES."
WHERE recibe_ID = '" . $pol['user_ID'] . "'
ORDER BY leido ASC, time DESC
LIMIT 100", $link);
	while($r = mysql_fetch_array($result)){
		if ($r['leido'] == 0) {
			$boton = '<input type="checkbox" name="option2" onClick="window.location.href=\'/accion.php?a=mensaje-leido&ID=' . $r['ID'] . '\';"  checked />';
			$fondo = ' style="background:#FFFFCC;"';
			
		} else {
			$boton = '<input type="checkbox" name="option2" />';
			$fondo = '';
		}


		if ($r['cargo'] != '0') { $cargo = ' <img src="'.IMG.'cargos/' . $r['cargo'] . '.gif" title="' . $r['cargo_nom'] . '" />'; } else { $cargo = ''; }

		$txt .= '<tr' . $fondo . '><td valign="top">' . $boton . '</td><td valign="top" align="right" nowrap="nowrap"><b>' . crear_link($r['nick_envia']) . '</b>' . $cargo . '<br /><acronym title="' . $r['time'] . '" style="font-size:12px;"><span class="timer" value="'.strtotime($r['time']).'"></span></acronym></td><td valign="top">' . $r['text'] . '<hr /></td><td valign="top">' . boton('Responder', ($r['recibe_masivo']==''?'/msg/' . strtolower($r['nick_envia']).'/':'/msg/cargos/'.$r['recibe_masivo'].'/')) . '</td><td valign="top">' . boton('X', '/accion.php?a=borrar-mensaje&ID=' . $r['ID']) . '</td></tr>' . "\n";
	}

	if (!$boton) { $txt .= '<tr><td colspan="5"><b>No tienes ning&uacute;n mensaje.</b></td></tr>'; }

	$txt .= '</table>';

}

} else if ($pol['user_ID']) {
	header('Location: http://'.strtolower($pol['pais']).'.virtualpol.com/msg/'.($_GET['a']?$_GET['a'].'/':''));
	exit;
}

//THEME
include('theme.php');
?>
