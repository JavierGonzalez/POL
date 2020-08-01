<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 





if (($pol['user_ID']) AND ($pol['pais'] == PAIS)) {

if ($_GET[1] == 'mensajes-enviados') {

	$txt_title = _('Tus mensajes enviados');
	$txt_nav = array('/msg'=>_('Mensajes Privados'), _('Enviados'));
	$txt_tab = array('/msg'=>_('Recibidos'), '/msg/mensajes-enviados'=>_('Enviados'));

	echo '<p>'.boton(_('Enviar mensaje'), '/msg/enviar').' &nbsp; <span class="gris">'._('Mensajes enviados por tí').'.</span></p>';

	echo '<table border="0" cellspacing="0" cellpadding="0" width="100%" id="msg_table">
<tr>
<th></th>
<th>'._('Receptor').'</th>
<th width="100%">'._('Tu mensaje').'</th>
<th></th>
</tr>';

	$result = mysql_query_old("SELECT 
ID, envia_ID, recibe_ID, time, text,
(SELECT nick FROM users WHERE users.ID = recibe_ID LIMIT 1) AS nick_envia,
(SELECT nombre FROM cargos WHERE cargos.cargo_ID = cargo LIMIT 1) AS cargo
FROM mensajes
WHERE envia_ID = '" . $pol['user_ID'] . "'
ORDER BY time DESC
LIMIT 50", $link);
	while($r = mysqli_fetch_array($result)){


		echo '<tr><td valign="top"></td><td valign="top" align="right"><b>'.crear_link($r['nick_envia']).'</b><br /><b>'.str_replace(' ', '&nbsp;', $r['cargo']).'</b><acronym title="'.$r['time'].'" style="font-size:12px;"><span class="timer" value="'.strtotime($r['time']).'"></span></acronym></td><td valign="top" class="rich">'.$r['text'].'</td><td valign="top">'.boton(_('Responder'), '/msg/'.$r['nick_envia']).'</td><td valign="top"></td></tr>'."\n";
	}

	echo '</table><p><b>(*)</b> <em>Esta página está en versión ALPHA, el motivo es que cabe la "extraña" posibilidad de que falten algunos mensajes. Esto suceder&aacute; cuando el RECEPTOR elimine tu mensaje enviado (ya que el mensaje se borra de la base de datos). Puede resultar incoherente la ausencia de algun mensaje enviado.</em></p>';

} else {
	$txt_title = $pol['msg'].' '._('mensajes recibidos');
	$txt_nav = array('/msg'=>_('Mensajes Privados'), _('Recibidos'));
	$txt_tab = array('/msg'=>_('Recibidos'), '/msg/mensajes-enviados'=>_('Enviados'));

	echo '

<br />

<div><button onclick="$(\'#box_msg\').toggle(\'slow\');">'._('Escribir mensaje').'</button> &nbsp; <span class="gris">'._('Tienes').' <b>'.$pol['msg'].'</b> '._('mensajes sin leer').'</span> '.boton(_('Marcar todo como leído'), '/accion/mensaje-leido?ID=all', false, 'small pill').'</div>';



	// load config
	$result = mysql_query_old("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'", $link);
	while ($r = mysqli_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

	if ($_GET[1] == 'cargos') {
		$pre_cargo = $_GET[2];
	} else if ($_GET[1] != 'enviar') { 
		$pre_nick = $_GET[1]; 
		if ($pre_nick=='') {
			$ocultar_formulario = 'style="display:none;"';
		}
	}
	if (isset($_POST['ciudadanos'])) { $pre_nick = $_POST['ciudadanos']; }

	$result = mysql_query_old("SELECT cargo_ID, nombre,
(SELECT COUNT(*) FROM cargos_users WHERE pais = '".PAIS."' AND cargo = 'true' AND cargo_ID = cargos.cargo_ID LIMIT 1) AS cargos_num
FROM cargos
WHERE pais = '".PAIS."' ORDER BY nivel DESC", $link);
	while($r = mysqli_fetch_array($result)){
		if ($r['cargos_num'] > 0) {
			$select_todoscargos .= '<option value="' . $r['cargo_ID'] . '"'.($pre_cargo==$r['cargo_ID']?' selected="selected"':'').'>'.$r['nombre'].' ('.$r['cargos_num'].')</option>';
		}
	}
	$select_todoscargos .= '<option value="SC"'.($pre_cargo=='SC'?' selected="selected"':'').'>&nbsp; &nbsp; '._('Supervisores del Censo').'</option>';

	//tus cargos
	$result = mysql_query_old("SELECT cargo_ID, 
(SELECT nombre FROM cargos WHERE cargos.ID = cargo_ID LIMIT 1) AS nombre
FROM cargos_users 
WHERE cargo = 'true'
AND user_ID = '" . $pol['user_ID'] . "'
ORDER BY nombre ASC", $link);
	while($r = mysqli_fetch_array($result)){
		$select_cargos .= '<option value="' . $r['cargo_ID'] . '">' . $r['nombre'] . '</option>';
	}

	$result = mysql_query_old("SELECT * FROM grupos WHERE pais = '".PAIS."' ORDER BY num DESC", $link);
	while($r = mysqli_fetch_array($result)){
		if (in_array($r['grupo_ID'], explode(' ', $pol['grupos']))) {
			$select_grupos .= '<option value="'.$r['grupo_ID'].'">'.$r['nombre'].' ('.$r['num'].')</option>';
		}
	}


	$txt_header .= '
<script type="text/javascript">

window.onload = function(){
	$("'.($_GET[1] != 'enviar'?'#text':'#ciudadano').'").focus();
}

function click_form(tipo) {	
	$("#radio_ciudadano").removeAttr("checked");
	$("#radio_cargos").removeAttr("checked");
	$("#radio_todos").removeAttr("checked");
	$("#urgente").removeAttr("disabled","disabled").removeAttr("checked");

	$("#radio_" + tipo).attr("checked","checked");

	switch (tipo) {
		case "todos":
		case "cargos": 
		case "grupos":
			$("#urgente").attr("disabled","disabled"); 
			break;
	}
}

</script>';

	$disabled_todos = '';
	if ($pol['config']['pols_mensajetodos'] > $pol['pols']) { $disabled_todos = ' disabled="disabled"'; }
	echo '
<div id="box_msg"'.$ocultar_formulario.'>

<form action="/accion/enviar-mensaje" method="post">

<p><b>'._('Destino').':</b><table border="0" style="margin-top:-15px;">
<tr onclick="click_form(\'ciudadano\');">
<td nowrap="nowrap"><input id="radio_ciudadano" type="radio" name="para" value="ciudadano"'.(!$pre_cargo?' checked="checked"':'').' />'._('Ciudadano').':</td>
<td nowrap="nowrap"><input id="ciudadano" tabindex="1" type="text" name="nick" value="'.str_replace('-', ' ', $pre_nick).'" style="font-size:17px;width:300px;" /> '.(nucleo_acceso($vp['acceso']['control_gobierno'])?'':'('._('Hasta').' '.MP_MAX.', '._('nicks separados por espacios').')').'</td>
</tr>
<tr onclick="click_form(\'cargos\');">
<td nowrap="nowrap"><input id="radio_cargos" type="radio" name="para" value="cargo"'.($pre_cargo?' checked="checked"':'').' />'._('Cargos').':</td>
<td nowrap="nowrap"><select name="cargo_ID" style="font-weight:bold;font-size:16px;"><option name="" value=""></option>'.$select_todoscargos.'</select> ('._('envío múltiple').')</td>
</tr>

'.(isset($select_grupos)?'

<tr onclick="click_form(\'grupos\');">
<td><input id="radio_grupos" type="radio" name="para" value="grupos"'.($pre_grupos?' checked="checked"':'').' />'._('Grupos').':</td>
<td><select name="grupo_ID" style="font-weight:bold;font-size:16px;">
<option name="" value=""></option>
'.$select_grupos.'
</select> ('._('envío múltiple').')</td>
</tr>

':'').'


<tr>
'.(ECONOMIA?'<td colspan="2" nowrap="nowrap"><input id="radio_todos" type="radio" name="para" value="todos"' . $disabled_todos . ' onclick="click_form(\'todos\');" />'._('Mensaje global').' (' . $pol['config']['info_censo'] . '). ' . pols($pol['config']['pols_mensajetodos']) . ' '.MONEDA.'.</td>':'').'
</tr>
</table>
</p>

<p><b>'._('Mensaje').':</b><br />
<textarea tabindex="2" name="text" style="width:550px;height:200px;" required></textarea></p>

<input type="hidden" name="calidad" value="0" />

<p>'.boton(_('Enviar'), 'submit', false, 'large blue').' &nbsp; <input type="checkbox" name="urgente" value="1" id="urgente" /> '._('Envío urgente').'. ('._('el receptor recibirá un email').')'.(ECONOMIA?' '.pols($pol['config']['pols_mensajeurgente']) . ' '.MONEDA:'').'</form></p>
<hr />
<br /><br />
</div>



<table border="0" cellspacing="0" cellpadding="4">
<tr>
<th colspan="2"><span style="float:right;">'._('Emisor').' &nbsp; &nbsp;</span>
</th>
<th>'._('Mensaje').'</th>
<th></th>
</tr>';

	$result = mysql_query_old("SELECT 
ID, envia_ID, recibe_ID, time, text, leido, cargo, recibe_masivo,
(SELECT nick FROM users WHERE users.ID = envia_ID LIMIT 1) AS nick_envia,
(SELECT nombre FROM cargos WHERE cargos.cargo_ID = cargo LIMIT 1) AS cargo_nom
FROM mensajes
WHERE recibe_ID = '" . $pol['user_ID'] . "'
ORDER BY leido ASC, time DESC
LIMIT 100", $link);
	while($r = mysqli_fetch_array($result)){
		if ($r['leido'] == 0) {
			$boton = '<input type="checkbox" name="option2" onClick="window.location.href=\'/accion/mensaje-leido?ID=' . $r['ID'] . '\';"  checked />';
			$fondo = ' style="background:#FFFFCC;"';
			
		} else {
			$boton = '<input type="checkbox" name="option2" />';
			$fondo = '';
		}


		if ($r['cargo'] != '0') { $cargo = ' <img src="'.IMG.'cargos/'.$r['cargo'].'.gif" title="'.$r['cargo_nom'].'" />'; } else { $cargo = ''; }

		echo '<tr'.$fondo.'>
<td valign="top">'.$boton.'</td>
<td valign="top" align="right" nowrap="nowrap"><b>'.crear_link($r['nick_envia']).'</b>'.$cargo.'<br /><acronym title="'.$r['time'].'" style="font-size:12px;"><span class="timer" value="'.strtotime($r['time']).'"></span></acronym></td>
<td valign="top" class="rich">'.$r['text'].'</td>
<td valign="top"><button onclick="$(\'#ciudadano\').val(\''.$r['nick_envia'].'\');$(\'#box_msg\').toggle(\'slow\');">'._('Responder').'</button></td>
<td valign="top">'.boton('X', '/accion/borrar-mensaje?ID='.$r['ID'], false, 'small red').'</td>
</tr>'."\n";
	}

	if (!$boton) { echo '<tr><td colspan="5"><b>'._('No tienes ningún mensaje').'.</b></td></tr>'; }

	echo '</table>';

}

} else if ($pol['user_ID']) {
	redirect('http://'.strtolower($pol['pais']).'.'.DOMAIN.'/msg/'.($_GET[1]?$_GET[1]:''));
}

//THEME
$txt_menu = 'comu';

?>
