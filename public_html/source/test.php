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



$txt_header .= '<script type="text/javascript" src="http://15m.virtualpol.com/ajax.php?a=data_extra"></script>';

$txt .= '


<script>



$(document).ready(function(){
	
	$(".nucleo_acceso").each(function (i) { $(this).html(control_acceso($(this).attr("data-title"), $(this).attr("data-name"), $(this).attr("data-acceso"), $(this).attr("data-cfg"), $(this).attr("data-excluir"))); });

});


function control_acceso(title, name, acceso, cfg, excluir) {
	var html = "<div id=\"control_" + name + "\">";
	if (title != "") { html += "<fieldset><legend>" + title + "</legend>"; }

	html += "<select name=\"" + name + "\" onchange=\"control_acceso_cambiar(name, this.value);\">";

	for(var i in data_acceso) {
		if (data_acceso[i] == acceso) { var selected = " selected=\"selected\""; } else { var selected = ""; }
		html += "<option value=\"" + data_acceso[i] + "\"" + selected + ">" + data_acceso[i] + "</option>";
	}

	html += "</select><br />";

	var cfg_array = cfg.split(" ");

	for(var i in data_acceso) {
		switch (data_acceso[i]) {
			case "cargo":
				html += "<select class=\"cfg cfg_" + data_acceso[i] + "\" name=\"" + name + "_cfg\" multiple=\"multiple\" class=\"fancy\" style=\"display:none;\">";
				for(var e in data_cargo) {
					var selected = "";
					for(var d in cfg_array) { if (cfg_array[d] == e) { var selected = " selected=\"selected\""; } }
					html += "<option value=\"" + e + "\"" + selected + ">" + data_cargo[e] + "</option>";	
				}
				html += "</select>";
				break;

			default: html += "<input class=\"cfg cfg_" + data_acceso[i] + "\" type=\"text\" name=\"" + name + "_cfg\" value=\"" + cfg + "\" style=\"display:none;\" />";
		}
	}

	if (title != "") { html += "</fieldset>"; }
	html += "</div>";
	return html;

}

function control_acceso_cambiar(name, value) {
	$("#control_" + name + " .cfg").hide();
	$("#control_" + name + " .cfg_" + value).show();
}


</script>

<form>
<span class="nucleo_acceso" data-title="tal pascual" data-name="test" data-acceso="cargo" data-cfg="6 59" data-excluir="anonimos"></span>
<input type="submit" value="ok">
</form>


<select multiple="multiple" class="fancy" name="adsd">
<option value="0">-- Choose --</option>
<option value="1">Option 1</option>
<option value="2">Option 2</option>
<option value="3">Option 3</option>
</select>



';











$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>