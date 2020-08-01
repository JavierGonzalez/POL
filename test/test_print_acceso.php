<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




if ($pol['user_ID'] != 1) { exit; }
function crono($new='') {
	 global $crono;
	 $the_ms = num((microtime(true)-$crono)*1000);
	 $crono = microtime(true);
	 return '<h3>'.$the_ms.'ms '.$new.'</h3></hr>';
}
echo '<h1>TEST</h1><hr />';


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


echo '
<script>
</script>

'.control_acceso2('Titulo de prueba', 'prueba', 'ciudadanos', '').'

<hr />



';



$txt_title = 'Test';
$txt_nav = array('Test');

?>