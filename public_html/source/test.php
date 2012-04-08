<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';



function control_acceso($name, $name_cfg, $value, $value_cfg=null, $titulo=null) {

	$html = '<fieldset><legend>'.$titulo.'</legend><select name="'.$name.'">';

	$tipos_array = nucleo_acceso('print');
	unset($tipos_array['anonimos']);

	foreach ($tipos_array AS $at => $at_var) {
		$html .= '<option value="'.$at.'"'.($value==$at?' selected="selected"':'').' />'.$at.'</option>';
	}

	$html .= '</select><br />
<input type="text" name="'.$name_cfg.'" size="18" maxlength="500" id="acceso_cfg_votar_var" value="'.$value_cfg.'" /><br />
'.ucfirst(verbalizar_acceso($value, $value_cfg)).'</fieldset>';

	return $html;
}

$txt .= control_acceso('acceso_votar', 'acceso_cfg_votar', 'cargo', '6', 'Acceso para votar').'<hr />';

$txt .= control_acceso('acceso_votar', 'acceso_cfg_votar', 'anonimos').'<hr />';

$txt .= control_acceso('acceso_votar', 'acceso_cfg_votar', 'privado', 'GONZO ddo').'<hr />';



$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>