<?php
if (!isset($_COOKIE['teorizauser'])) { session_start(); }

if ($_GET['b'] == 'e') { $externo = true; } else { $externo = false; }

if ((!$pol['nick']) AND ($_SESSION['pol']['nick'])) { $pol['nick'] = $_SESSION['pol']['nick']; }

$result = mysql_query("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET['a']."' LIMIT 1", $link);
while ($r = mysql_fetch_array($result)) { 
	
	$txt_nav = array('/chats'=>'Chats', '/chats/'.$r['url']=>$r['titulo']);
	$txt_tab = array('/chats/'.$r['url'].'/log'=>'Log', '/chats/'.$r['url'].'/opciones'=>'Opciones');

	if ($r['pais'] != PAIS) { header('Location: http://'.strtolower($r['pais']).'.'.DOMAIN.'/chats/'.$_GET['a'].'/'.($_GET['b']?$_GET['b'].'/':'')); exit; }


	if ($pol['user_ID']) { mysql_query("UPDATE chats SET stats_visitas = stats_visitas + 1, fecha_last = '".$date."' WHERE chat_ID = ".$r['chat_ID']." LIMIT 1", $link); }

	$chat_ID = $r['chat_ID'];
	$titulo = $r['titulo'];

	foreach (array('leer','escribir') AS $a) {
		$acceso[$a] = nucleo_acceso($r['acceso_'.$a], $r['acceso_cfg_'.$a]);
	}

	$acceso_leer = $r['acceso_leer'];
	$acceso_escribir = $r['acceso_escribir'];
	$acceso_cfg_leer = $r['acceso_cfg_leer'];
	$acceso_cfg_escribir = $r['acceso_cfg_escribir'];
}

// genera array js, nombres cargos
$result = mysql_query("SELECT ID, nombre FROM ".SQL."estudios WHERE asigna != '-1' ORDER BY nivel DESC", $link);
while ($r = mysql_fetch_array($result)) {
	if ($array_cargos) { $array_cargos .= ', '; } 
	$array_cargos .= $r['ID'].':"'.$r['nombre'].'"';
}

// Muestra control de kicks.
if ((nucleo_acceso($vp['acceso']['kick'])) AND ($pol['pais'] == PAIS)) {
	$js_kick = '<a href=\"/control/kick/" + kick_nick  + "/" + chat_ID  + "/\" target=\"_blank\"><img src=\"'.IMG.'varios/kick.gif\" title=\"Kickear\" alt=\"Kickear\" border=\"0\" /></a> ';
} else { $js_kick = ''; }



$txt .= '
<div id="vp_c">

<h1 style="margin:0 0 18px 0;">';

if ($externo) {
	if ($_SESSION['pol']['user_ID']) {
		$txt .= '<span style="float:right;"><a href="http://www.'.DOMAIN.'/">Volver a VirtualPOL</a></span>'.$titulo;
	} else {
		$txt .= '<span style="float:right;"><a href="http://www.'.DOMAIN.'/registrar/">Crear ciudadano</a></span>'.$titulo;
	}
} else {
	$txt .= '<span class="quitar"><span style="float:right;">[<a href="/chats/'.$_GET['a'].'/opciones/">Opciones</a>] [<a href="/chats/'.$_GET['a'].'/log/">Log</a>]</span><a href="/chats/">Chat</a>: '.$titulo.'</span>';
}


$txt .= '</h1>

<div id="vpc_u">
<ul id="chat_list">
</ul>
</div>

<div id="vpc_fondo">
<div id="vpc">
<ul id="vpc_ul">
<li style="margin-top:280px;color:#AAA;"><b>
'.($acceso['leer']?'
<img src="'.IMG.'logo-virtualpol-1.gif" alt="VirtualPol" border="0" /><br />
'.$titulo.'. Plataforma '.PAIS.'<br />
Acceso leer: '.$acceso_leer.($acceso_cfg_leer?' [<em>'.$acceso_cfg_leer.'</em>]':'').'<br />
Acceso escribir: '.$acceso_escribir.($acceso_cfg_escribir?' [<em>'.$acceso_cfg_escribir.'</em>]':'').'<br />
<span style="float:right;">'.date('Y-m-d H:i:s').' &nbsp;</span>Nick: '.($pol['nick']?$pol['nick']:'Anonimo').'<br />
<hr />
':'<span style="color:red;">No tienes acceso de lectura, lo siento.</span>').'
</b></li></ul>
</div>
</div>


</div><div id="chatform">
<form action="" method="POST" onSubmit="return enviarmsg();">

<table border="0" width="100%">
<tr>

<td width="100%" border="0">'.(isset($pol['user_ID'])?'<input type="text" id="vpc_msg" name="msg" onKeyUp="msgkeyup(event,this);" onKeyDown="msgkeydown(event,this);" tabindex="1" autocomplete="off" size="65" maxlength="250" style="width:98%;" />':'<b style="color:red;">Para participar debes <a href="'.REGISTRAR.'?p='.PAIS.'">crear un ciudadano</a>.</b>').'</td>

<td nowrap="nowrap" title="Marcar para ocultar eventos del chat">&nbsp;&nbsp; <input id="cfilter" name="cfilter" value="1" type="checkbox" OnClick="chat_filtro_change(chat_filtro);" /> <label for="cfilter" class="inline">Ocultar eventos</label></td>

<td><input type="submit" value="Enviar" id="botonenviar" style="width:110px;height:32px;" /></td>

<td></td>

</tr>
</table>

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






if ($externo) {
	// ES chat externo (incrustado en una web externa)
	echo '
<html>
<body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
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