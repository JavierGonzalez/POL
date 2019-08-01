<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
function crono($new='') {
	 global $crono;
	 $the_ms = num((microtime(true)-$crono)*1000);
	 $crono = microtime(true);
	 return '<h3>'.$the_ms.'ms '.$new.'</h3></hr>';
}
$txt .= '<h1>TEST</h1><hr />';


function control_acceso2($titulo=false, $name='', $acceso='', $cfg='', $quitar_array='') {
	$html = ($titulo==false?'':'<fieldset><legend>'.$titulo.'</legend>').'<select name="'.$name.'">';
	$quitar_array = explode(' ', $quitar_array);
	$array = nucleo_acceso('print');
	foreach ($array AS $a => $b) { if (in_array($a, $quitar_array)) { unset($array[$a]); } }
	foreach ($array AS $at => $at_var) {
		$html .= '<option value="'.$at.'"'.($at==$acceso?' selected="selected"':'').' />'.ucfirst(str_replace('_', ' ', $at)).'</option>';
	}
	$html .= '</select>';
	
	foreach ($array AS $a => $b) {
		$dis = ($a==$acceso?'':' style="display:none;"');
		switch ($a) {
			case 'cargo': $html .= '<br /><select multiple="multiple" class="fancy" name="'.$name.'_cfg"'.$dis.'>';
				$result = sql("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."'");
				while ($r = r($result)) { $html .= '<option value="'.$r['cargo_ID'].'">'.$r['nombre'].'</option>'; }
				$html .= '</select>'; 
				break;
			default: $html .= '<br /><input type="text" name="'.$name.'_cfg" size="18" maxlength="900" id="'.$name.'_cfg_var" value="'.$cfg.'"'.$dis.' />'; break;
		}
	}
	$html .= ($titulo==false?'':'</fieldset>');
	return $html;
}


$txt .= '
<script>
</script>

'.control_acceso2('Titulo de prueba', 'prueba', 'ciudadanos', '').'

<hr />



';



$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>