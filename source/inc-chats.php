<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/


// Prevención de inyección
foreach ($_POST AS $nom => $val) { $_POST[$nom] = escape($val); }
foreach ($_GET  AS $nom => $val) { $_GET[$nom] = escape($val); }
foreach ($_REQUEST AS $nom => $val) { $_REQUEST[$nom] = escape($val); }


if (!isset($_COOKIE['teorizauser'])) { session_start(); }

if ($_GET['b'] == 'e') { $externo = true; } else { $externo = false; }

if ((!$pol['nick']) AND ($_SESSION['pol']['nick'])) { $pol['nick'] = $_SESSION['pol']['nick']; }

$result = mysql_query("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET['a']."' LIMIT 1", $link);
while ($r = mysql_fetch_array($result)) { 
	
	$txt_nav = array('/chats'=>_('Chats'), $r['titulo']);
	$txt_tab = array('/chats/'.$r['url'].'/log'=>_('Log'), '/chats/'.$r['url'].'/opciones'=>_('Opciones'));

	if ($r['pais'] != PAIS) { redirect('http://'.strtolower($r['pais']).'.'.DOMAIN.'/chats/'.$_GET['a'].'/'.$_GET['b']); }


	if ($pol['user_ID']) { mysql_query("UPDATE chats SET stats_visitas = stats_visitas + 1, fecha_last = '".$date."' WHERE chat_ID = ".$r['chat_ID']." LIMIT 1", $link); }

	$chat_ID = $r['chat_ID'];
	$titulo = $r['titulo'];

	foreach (array('leer','escribir') AS $a) {
		$acceso[$a] = nucleo_acceso($r['acceso_'.$a], $r['acceso_cfg_'.$a]);
	}

	$acceso_leer = $r['acceso_leer'];
	$acceso_cfg_leer = $r['acceso_cfg_leer'];

	$acceso_escribir = $r['acceso_escribir'];
	$acceso_cfg_escribir = $r['acceso_cfg_escribir'];

	$acceso_escribir_ex = $r['acceso_escribir_ex'];
	$acceso_cfg_escribir_ex = $r['acceso_cfg_escribir_ex'];
}

// genera array js, nombres cargos
$result = mysql_query("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' ORDER BY nivel DESC", $link);
while ($r = mysql_fetch_array($result)) {
	if ($array_cargos) { $array_cargos .= ', '; } 
	$array_cargos .= $r['cargo_ID'].':"'.$r['nombre'].'"';
}

// Muestra control de kicks.
if ((nucleo_acceso($vp['acceso']['kick'])) AND ($pol['pais'] == PAIS)) {
	$js_kick = '<a href=\"/control/kick/" + kick_nick  + "/" + chat_ID  + "/\" target=\"_blank\"><img src=\"'.IMG.'varios/kick.gif\" title=\"Kickear\" alt=\"Kickear\" /></a> ';
} else { $js_kick = ''; }



$txt .= '
<div id="vp_c">

<h1 style="margin:0 0 18px 0;">';

if ($externo) {
	if ($_SESSION['pol']['user_ID']) {
		$txt .= '<span style="float:right;"><a href="http://www.'.DOMAIN.'">'._('Volver a VirtualPol').'</a></span>'.$titulo;
	} else {
		$txt .= '<span style="float:right;"><a href="'.REGISTRAR.'?='.PAIS.'">'._('Crear ciudadano').'</a></span>'.$titulo;
	}
} else {
	$txt .= '<span class="quitar"><span style="float:right;">[<a href="/chats/'.$_GET['a'].'/opciones">'._('Opciones').'</a>] [<a href="/chats/'.$_GET['a'].'/log">'._('Log').'</a>]</span><a href="/chats/">'._('Chat').'</a>: '.$titulo.'</span>';
}

$a_leer = nucleo_acceso($acceso_leer, $acceso_cfg_leer);
$a_escribir = nucleo_acceso($acceso_escribir, $acceso_cfg_escribir);
$a_escribir_ex = nucleo_acceso($acceso_escribir_ex, $acceso_cfg_escribir_ex);

$txt .= '</h1>

<div id="vpc_u">
<ul id="chat_list">
</ul>
</div>

<div id="vpc_fondo">
<div id="vpc">
<ul id="vpc_ul">
<li style="margin-top:600px;color:#666;">
<img src="'.IMG.'logo/vp2.png" alt="VirtualPol" width="200" height="60" /><br />
<span style="float:right;">'.date('Y-m-d H:i').' &nbsp;</span>
Chat de '.PAIS.': <b>'.$titulo.'</b><br />

<table>
<tr>
<td align="right">'._('Acceso leer').':</td>
<td><b style="color:'.($a_leer?'blue;">'._('SI'):'red;">'._('NO')).'</b>. '.ucfirst(verbalizar_acceso($acceso_leer, $acceso_cfg_leer)).'</td>
</tr>

<tr>
<td align="right">'._('Acceso escribir').':</td>
'.($pol['estado']=='extranjero'?'<td><b style="color:'.($a_escribir_ex?'blue;">'._('SI'):'red;">'._('NO')).'</b>. '.ucfirst(verbalizar_acceso($acceso_escribir_ex, $acceso_cfg_escribir_ex)).'</td>':'<td><b style="color:'.($a_escribir?'blue;">'._('SI'):'red;">'._('NO')).'</b>. '.ucfirst(verbalizar_acceso($acceso_escribir, $acceso_cfg_escribir)).'</td>').'
</tr>

</table>

<hr />

</li>
</ul>
</div>
</div>


</div>

<div id="chatform">
<form method="POST" onSubmit="return enviarmsg();">

<div class="envio_mensaje_container">
	<div class="refrescar_evento"><img id="vpc_actividad" onclick="actualizar_ahora();" src="'.IMG.'ico/punto_gris.png" width="16" height="16" title="Actualizar chat" style="margin-top:4px;" /></div>

	<div class="cuadro_mensaje">
	'.(isset($pol['user_ID'])?'
	<input type="text" data-emojiable="true" id="vpc_msg" name="msg" onKeyUp="msgkeyup(event,this);" onKeyDown="msgkeydown(event,this);" tabindex="1" autocomplete="off" size="65" maxlength="250" style="margin-left:0;width:98%; height: 32px; " autofocus="autofocus" value="" required />':boton(_('¡Regístrate para participar!'), REGISTRAR.'?p='.PAIS, false, 'green')).'
	</div>

	<div class="ocultar_evento">&nbsp;&nbsp; <input id="cfilter" name="cfilter" value="1" type="checkbox" OnClick="chat_filtro_change(chat_filtro);" /> <label for="cfilter" class="inline">'._('Ocultar eventos').'</label></div>

	<div class="enviar_mensaje">'.boton(_('Enviar'), 'submit', false, '', '', ' id="botonenviar"').'</div>
</div>

</form>

</div>';




// css & js
$txt_header .= '
<script type="text/javascript"> 
msg_ID = -1;
elnick = "'.$_SESSION['pol']['nick'].'";
minick = elnick;
chat_ID = "'.$chat_ID.'";

ajax_refresh = true;
refresh = "";
anonimo = false;

hace_kick = '.($js_kick==''?'false':'true').';
kick_nick = "___";
js_kick = "'.$js_kick.'";

chat_delay = 4000;
chat_delay1 = "";
chat_delay2 = "";
chat_delay3 = "";
chat_delay4 = "";
chat_sin_leer = 0;
chat_sin_leer_yo = "";
mouse_position = "";
titulo_html = document.title;
chat_delay_close = "";
chat_scroll = 0;

chat_filtro = "normal";
chat_time = "";
acceso_leer = '.($acceso['leer']?'true':'false').';
acceso_escribir = '.($acceso['escribir']?'true':'false').';

al = new Array();
al_cargo = new Array();
array_cargos = new Array();
array_cargos = { '.$array_cargos.', 0:"", 98:"Turista", 99:"Extranjero" };

array_ignorados = new Array();

window.onload = function(){
	scroll_abajo();
	merge_list();
	$("#vpc_msg").focus();
	if ((!elnick) && ("'.$acceso_escribir.'" == "anonimos")) {
		$("#chatform").hide().after("<div id=\"cf\"><b>Nick:</b> <input type=\"input\" id=\"cf_nick\" size=\"10\" maxlength=\"14\" /> <button onclick=\"cf_cambiarnick();\" style=\"font-weight:bold;color:green;font-size:16px;\">Entrar al chat</button></div>");
	}
	'.($acceso['leer']?'refresh = setTimeout(chat_query_ajax, 6000); chat_query_ajax();':'').'

	$("body").click(function() {
	  chat_sin_leer = 0; 
	  chat_sin_leer_yo = "";
	  refresh_sin_leer();
	});
	delays();
	$("#vp_c a").attr("target", "_blank");
}


</script>';

$GLOBALS['txt_footer'].='
      <script>$(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: \'[data-emojiable=true]\',
          assetsPath: \''.IMG.'emoji/img/\',
          popupButtonClasses: \'fa fa-smile-o\',
		  norealTime: true
        });
        // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
        // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
        // It can be called as many times as necessary; previously converted input fields will not be converted again
        //window.emojiPicker.discover();
      });
	  
	  function fix_onChange_editable_elements()
		{
		  var tags = document.querySelectorAll(\'[contenteditable=true][onChange]\');//(requires FF 3.1+, Safari 3.1+, IE8+)
		  for (var i=tags.length-1; i>=0; i--) if (typeof(tags[i].onblur)!=\'function\')
		  {
			tags[i].onfocus = function()
			{
			  this.data_orig=this.innerHTML;
			};
			tags[i].onblur = function()
			{
			  if (this.innerHTML != this.data_orig)
				this.onchange();
			  delete this.data_orig;
			};
		  }
		}
		fix_onChange_editable_elements();
	  </script>';
    



if ($externo) {
	// ES chat externo (incrustado en una web externa)
	echo '
<html>
<body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="'.IMG.'scripts.js"></script>
<script type="text/javascript">IMG = "'.IMG.'";</script>
'.$txt_header.$txt.'
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-59186-46");
pageTracker._setDomainName("'.DOMAIN.'");
pageTracker._trackPageview();
} catch(err) {}
</script>
</body></html>';
	if ($link) { mysql_error($link); } exit;
}

?>
