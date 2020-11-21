<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


unset($votaciones_tipo[4]); 

$txt_title = _('Borrador de votación');
$txt_nav = array('/votacion'=>_('Votaciones'), '/votacion/borradores'=>_('Borradores'), _('Crear votación'));
$txt_tab = array('/votacion/borradores'=>_('Ver borradores'), '/votacion/'.$_GET[2]=>_('Previsualizar'), '/votacion/crear/'.$_GET[2]=>_('Editar borrador'));

// EDITAR
if (is_numeric($_GET[2])) {
	$result = sql_old("SELECT * FROM votacion WHERE estado = 'borrador' AND ID = '".$_GET[2]."' LIMIT 1");
	$edit = r($result);
}


// Pre-selectores
if (!isset($edit['ID'])) { $edit['tipo'] = 'sondeo'; $edit['acceso_votar'] = 'ciudadanos_global'; $edit['acceso_ver'] = 'anonimos'; }

$sel['tipo_voto'][$edit['tipo_voto']] = ' selected="selected"';
$sel['privacidad'][$edit['privacidad']] = ' selected="selected"';

$sel['tipo'][$edit['tipo']] = ' checked="checked"';

$sel['acceso_votar'][$edit['acceso_votar']] = ' selected="selected"';
$sel['acceso_ver'][$edit['acceso_ver']] = ' selected="selected"';

echo '<form action="/accion/votacion/crear" method="post">

'.(isset($edit['ID'])?'<input type="hidden" name="ref_ID" value="'.$_GET[2].'" />':'').'

'.(nucleo_acceso($vp['acceso']['votacion_borrador'])?'':'<p style="color:red;">No tienes acceso. Solo pueden: '.verbalizar_acceso($vp['acceso']['votacion_borrador']).'</p>').'

<table border="0"><tr><td valign="top">


<fieldset><legend>'._('Tipo de votación').'</legend>
<p>
<span id="tipo_select">';

$tipo_extra = array(
'sondeo'=>'<span style="float:right;margin-left:6px;" class="gris">('._('informativo, no vinculante').')</span>', 
'referendum'=>'<span style="float:right;" class="gris">('._('vinculante').')</span>',
'parlamento'=>'<span style="float:right;" class="gris">('._('vinculante').')</span>',
'cargo'=>'<span style="float:right;" title="Se ejecuta una acción automática tras su finalización." class="gris">('._('ejecutiva').')</span>',
);

//if (ASAMBLEA) { unset($votaciones_tipo[2]); } // Quitar tipo de votacion de parlamento.

foreach ($votaciones_tipo AS $tipo) {
	echo '<span style="font-size:18px;"><input type="radio" name="tipo" value="'.$tipo.'" onclick="cambiar_tipo_votacion(\''.$tipo.'\');"'.$sel['tipo'][$tipo].' />'.$tipo_extra[$tipo]._(ucfirst($tipo)).'</span><br >';
}

echo '</p>


</fieldset>


<fieldset><legend>'._('Opciones de votación').'</legend>

<span id="time_expire">
<b>'._('Duración').'</b>:

<input type="text" name="time_expire" value="'.(isset($edit['ID'])?round($edit['duracion']/3600):'24').'" style="text-align:right;width:50px;" />

<select name="time_expire_tipo">
<option value="3600" selected="selected">'._('horas').'</option>
<option value="86400">'._('días').'</option>
</select></span>


<span id="cargo_form" style="display:none;">
<b>'._('Cargo').'</b>: 
<select name="cargo">';

$sel['cargo'][explodear('|', $edit['ejecutar'], 0)] = ' selected="selected"';
$result = sql_old("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' ORDER BY nivel DESC");
while($r = r($result)) { echo '<option value="'.$r['cargo_ID'].'"'.$sel['cargo'][$r['cargo_ID']].'>'.$r['nombre'].'</option>'; }

echo '
</select><br />
'._('Ciudadano').': <input type="text" name="nick" value="" size="10" /></span>


<br /><span id="votos_expire">
<b>'._('Finalizar con').'</b>: <input type="text" name="votos_expire" value="'.($edit['votos_expire']?$edit['votos_expire']:'').'" size="1" maxlength="5" style="text-align:right;" /> '._('votos').'</span>

</fieldset>


<fieldset><legend>'._('Opciones de voto').'</legend>

<span id="tipo_voto">
<b>'._('Tipo de voto').'</b>: 
<select name="tipo_voto">

<optgroup label="'._('Voto único').'">
<option value="estandar"'.$sel['tipo_voto']['estandar'].'>'._('Una elección').'</option>
</optgroup>

<optgroup label="'._('Voto aprobatorio').'">
<option value="multiple"'.$sel['tipo_voto']['multiple'].'>'._('Múltiple').'</option>
</optgroup>

<optgroup label="'._('Voto preferencial').'">
<option value="3puntos"'.$sel['tipo_voto']['3puntos'].'>3 '._('votos').' (6 '._('puntos').')</option>
<option value="5puntos"'.$sel['tipo_voto']['5puntos'].'>5 '._('votos').' (15 '._('puntos').')</option>
<option value="8puntos"'.$sel['tipo_voto']['8puntos'].'>8 '._('votos').' (36 '._('puntos').')</option>
</optgroup>

<optgroup label="'._('Sorteo').'">
<option value="aleatorio"'.$sel['tipo_voto']['aleatorio'].' disabled>'._('Aleatorio').'</option>
</optgroup>

</select></span>

<br />
<span id="privacidad">
<b>'._('Voto').'</b>: 
<select name="privacidad">
<option value="true"'.$sel['privacidad']['true'].'>'._('Secreto (estándar)').'</option>
<option value="false"'.$sel['privacidad']['false'].'>'._('Público').'</option>
</select>

<br />

<b>'._('Orden de opciones').'</b>: <input type="checkbox" name="aleatorio" value="true"'.($edit['aleatorio']=='true'?' checked="checked"':'').' /> '._('Aleatorio').'.
</span>


</fieldset>


</td><td valign="top" align="right">

<fieldset><legend>'._('Accesos').'</legend>

<fieldset><legend>'._('Votar').'</legend>
<p>
<select name="acceso_votar">';


$tipos_array = nucleo_acceso('print');
unset($tipos_array['anonimos']);
foreach ($tipos_array AS $at => $at_var) {
	echo '<option value="'.$at.'"'.$sel['acceso_votar'][$at].' />'.ucfirst(str_replace("_", " ", $at)).'</option>';
}

echo '</select><br />
<input type="text" name="acceso_cfg_votar" size="18" maxlength="500" id="acceso_cfg_votar_var" value="'.$edit['acceso_cfg_votar'].'" /><br />
'.ucfirst(verbalizar_acceso($edit['acceso_votar'], $edit['acceso_cfg_votar'])).'</p>

</fieldset>


<fieldset><legend>'._('Ver votación').'</legend>
<p>
<select name="acceso_ver">';


$tipos_array = nucleo_acceso('print');
foreach ($tipos_array AS $at => $at_var) {
	echo '<option value="'.$at.'"'.$sel['acceso_ver'][$at].' />'.ucfirst(str_replace("_", " ", $at)).'</opcion>';
}

echo '</select><br />
<input type="text" name="acceso_cfg_ver" size="18" maxlength="500" id="acceso_cfg_ver_var" value="'.$edit['acceso_cfg_ver'].'" /><br />
'.ucfirst(verbalizar_acceso($edit['acceso_ver'], $edit['acceso_cfg_ver'])).'
</p>
</fieldset>

</fieldset>

</td></tr></table>

<fieldset><legend>'._('Redacción').'</legend>

<div class="votar_form">
<p><b>'._('Pregunta').'</b>: 
<input type="text" name="pregunta" size="57" maxlength="140" value="'.$edit['pregunta'].'" required /></p>
</div>

<p><b>'._('Descripción').'</b>:<br />
<textarea name="descripcion" style="width:600px;height:260px;" required>'.strip_tags($edit['descripcion']).'</textarea></p>

<p><b>'._('URL de debate').'</b>: ('._('opcional, debe empezar por').' http://...)<br />
<input type="text" name="debate_url" size="57" maxlength="300" value="'.$edit['debate_url'].'" placeholder="http://" /></p>

</fieldset>


<div class="votar_form">

<fieldset><legend>'._('Opciones de voto').'</legend>
<p>
<ul style="margin-bottom:-16px;">
<li><input type="text" name="respuesta0" size="22" value="En Blanco" readonly="readonly" style="color:grey;" /> &nbsp; <a href="#" id="a_opciones" onclick="opcion_nueva();return false;">'._('Añadir opción').'</a></li>
</ul>
<ol id="li_opciones" style="margin-top:10px;">';

if (!isset($edit['ID'])) {
	$edit['respuestas'] = 'SI|NO|';
	$edit['respuestas_desc'] = '][][';
}

$respuestas = explode("|", $edit['respuestas']);
$respuestas_desc = explode("][", $edit['respuestas_desc']);
if ($respuestas[0] == 'En Blanco') { unset($respuestas[0]); }

foreach ($respuestas AS $ID => $respuesta) {
	if ($respuesta != '') {
		$respuestas_num++;
		echo '<li><input type="text" name="respuesta'.$respuestas_num.'" size="80" maxlength="250" value="'.$respuesta.'" /></li>';
	}
}

echo '
</ol>
</p>
</fieldset>

</div>
<p>'.(nucleo_acceso($vp['acceso']['votacion_borrador'])?boton(_('Guardar borrador'), 'submit', false, 'large blue'):boton(_('Guardar borrador'), false, false, 'large red').' No tienes acceso. Solo pueden: '.verbalizar_acceso($vp['acceso']['votacion_borrador'])).'</p>';

$txt_header .= '<script type="text/javascript">
campos_num = '.($respuestas_num+1).';
campos_max = 100;

function cambiar_tipo_votacion(tipo) {
$("#acceso_ver, #acceso_votar, #time_expire, .votar_form, #votos_expire, #tipo_voto, #privacidad").show();
$("#cargo_form").hide();
switch (tipo) {
	case "parlamento": 
		$(".votar_form input").val(""); 
		$("#acceso_votar, #votos_expire, #privacidad, #acceso_ver").hide(); 
		break;
	case "cargo": 
		$("'.(ASAMBLEA?'':'#acceso_ver, #acceso_votar, ').'#time_expire, .votar_form, #votos_expire, #tipo_voto, #privacidad").hide(); 
		$(".votar_form input").val("Automatico"); 
		$("#cargo_form").show(); 
		break;
	default:
		$(".votar_form input").val("");
		$("input[name=\'respuesta0\']").val("En Blanco");
}
}

function opcion_nueva() {
$("#li_opciones").append(\'<li><input type="text" name="respuesta\' + campos_num + \'" size="80" maxlength="250" /></li>\');
if (campos_num >= campos_max) { $("#a_opciones").hide(); }
campos_num++;
return false;
}

</script>';
