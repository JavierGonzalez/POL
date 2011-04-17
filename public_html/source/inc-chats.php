<?php
if (!isset($_COOKIE['teorizauser'])) { session_start(); }

if ($_GET['b'] == 'e') { $externo = true; } else { $externo = false; }

if ((!$pol['nick']) AND ($_SESSION['pol']['nick'])) { $pol['nick'] = $_SESSION['pol']['nick']; }

$result = mysql_query("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET['a']."' LIMIT 1", $link);
while ($r = mysql_fetch_array($result)) { 
	
	if ($r['pais'] != PAIS) { header('Location: http://'.strtolower($r['pais']).DEV.'.virtualpol.com/chats/'.$_GET['a'].'/'.($_GET['b']?$_GET['b'].'/':'')); exit; }


	mysql_query("UPDATE chats SET stats_visitas = stats_visitas + 1 WHERE chat_ID = ".$r['chat_ID']." LIMIT 1", $link);

	$chat_ID = $r['chat_ID'];
	$titulo = $r['titulo'];

	foreach (array('leer','escribir') AS $a) {


// ### NUCLEO ACCESOS 2.0
switch ($r['acceso_'.$a]) {
	case 'excluir': if (!in_array(strtolower($_SESSION['pol']['nick']), explode(' ', $r['acceso_cfg_'.$a]))) { $acceso[$a] = true; } break;
	case 'privado': if (in_array(strtolower($_SESSION['pol']['nick']), explode(' ', $r['acceso_cfg_'.$a]))) { $acceso[$a] = true; } break;
	case 'nivel': if (($_SESSION['pol']['nivel'] >= $r['acceso_cfg_'.$a]) AND ($_SESSION['pol']['pais'] == $r['pais'])) { $acceso[$a] = true; } break;
	case 'antiguedad': if (($_SESSION['pol']['fecha_registro']) AND (strtotime($_SESSION['pol']['fecha_registro']) < (time() - ($r['acceso_cfg_'.$a]*86400)))) { $acceso[$a] = true; } break;
	case 'ciudadanos_pais': if ($_SESSION['pol']['pais'] == $r['pais']) { $acceso[$a] = true; } break;
	case 'ciudadanos': if (isset($_SESSION['pol']['user_ID'])) { $acceso[$a] = true; } break;
	case 'anonimos': if ($_SESSION['pol']['estado'] != 'expulsado') { $acceso[$a] = true; } break;
	default: $acceso[$a] = false;
}
// ###


	}

	$acceso_leer = $r['acceso_leer'];
	$acceso_escribir = $r['acceso_escribir'];
	$acceso_cfg_leer = $r['acceso_cfg_leer'];
	$acceso_cfg_escribir = $r['acceso_cfg_escribir'];
}

// genera array js, nombres cargos
$result = mysql_query("SELECT ID, nombre FROM ".SQL."estudios WHERE asigna != '-1'", $link);
while ($row = mysql_fetch_array($result)) {
	if ($array_ncargos) { $array_ncargos .= ', '; } 
	$array_ncargos .= $row['ID'] . ':"' . $row['nombre'] . '"';
}

// SI es Policia o Comisario del pais, muestra control de kicks.
if ((($pol['cargo'] == 12) OR ($pol['cargo'] == 13)) AND ($pol['pais'] == PAIS)) {
	$js_kick = '<a href=\"/control/kick/" + kick_nick  + "/" + chat_ID  + "/\" target=\"_blank\"><img src=\"'.IMG.'kick.gif\" title=\"Kickear\" alt=\"Kickear\" border=\"0\" /></a> ';
} else { $js_kick = ''; }



$txt .= '
<div id="vp_c">

<h1 style="margin:0 0 18px 0;">';

if ($externo) {
	if ($_SESSION['pol']['user_ID']) {
		$txt .= '<span style="float:right;"><a href="http://www'.DEV.'.virtualpol.com/">Volver a VirtualPOL</a></span>'.$titulo;
	} else {
		$txt .= '<span style="float:right;"><a href="http://www'.DEV.'.virtualpol.com/registrar/">Crear ciudadano</a></span>'.$titulo;
	}
} else {
	$txt .= '<span style="float:right;">[<a href="/chats/'.$_GET['a'].'/opciones/">Opciones</a>]</span><a href="/chats/">Chat</a>: '.$titulo;
}


$txt .= '</h1>

<div id="vpc_u">
<ul id="chat_list">
</ul>
</div>

<div id="vpc_fondo">
<div id="vpc">
<ul id="vpc_ul">
<li style="margin-top:380px;color:#AAA;"><b>
'.($acceso['leer']?'
<img src="'.IMG.'logo-virtualpol-40original.gif" alt="VirtualPOL" border="0" height="40" /><br />
'.$titulo.', Gobierno de '.PAIS.'<br />
Acceso leer: '.$acceso_leer.($acceso_cfg_leer?' [<em>'.$acceso_cfg_leer.'</em>]':'').'<br />
Acceso escribir: '.$acceso_escribir.($acceso_cfg_escribir?' [<em>'.$acceso_cfg_escribir.'</em>]':'').'<br />
<span style="float:right;">'.date('Y-m-d H:i:s').' &nbsp;</span>Nick: '.($pol['nick']?$pol['nick']:'Anonimo').'<br />
<hr />
':'<span style="color:red;">No tienes acceso de lectura, lo siento.</span>').'
</b></li></ul>
</div>
</div>';

if ($acceso['escribir']) {

	$txt .= '</div><div id="chatform">
<form action="" method="POST" onSubmit="return enviarmsg();">

<table border="0" width="100%">
<tr>

<td width="100%"><input type="text" name="msg" onKeyUp="msgkeyup(event,this);" onKeyDown="msgkeydown(event,this);" id="vpc_msg" tabindex="1" autocomplete="off" size="65" maxlength="250" style="width:95%;" /></td>

<td><input name="cfilter" value="1" type="checkbox" OnClick="chat_filtro_change(chat_filtro);" style="margin-top:8px;" title="Filtro de eventos" /></td>

<td><input type="submit" value="Enviar" id="botonenviar" style="width:150px;" /></td>

<td></td>

</tr>
</table>

</form>

</div>';

} else { $txt .= '<p class="azul"><span style="color:red;"><b>No tienes permiso para escribir.</b></span></p>'; }



// css & js
$txt_header .= '

<style type="text/css">
input, area, div.content-in select { color:green; font-size:16px; font-weight:bold; }
#vp_c { font-family: "Arial", "Helvetica", sans-serif; font-size:17px; }
#vp_c h1 { font-size:19px; color:green; margin:0; padding:0; line-height:12px; }
#vp_c a { color:#06f;text-decoration:none; }
#vp_c a:hover { text-decoration:underline; }
#vp_c h1 a { color:#4BB000; } 
#vpc { vertical-align: bottom; height:400px; overflow:auto; overflow-x:hidden; }
#vpc ul { padding:0; margin:0; position:static; }
#vpc ul li { padding:0; margin:0; color:#666666; background:none; font-size:15px; list-style:none;}
#vpc .oldc { color:#A3A3A3; }
#vpc_u { float:right; width:180px; height:400px; overflow:auto; overflow-x:hidden; margin-left:20px; }
#vpc_u ul { padding:0; margin:0; position:static; }
#vpc_u ul li { padding:0; margin:0; color:#666666; background:none; font-size:18px; font-weight:bold; list-style:none;}
#vpc_u li { font-weight:bold; }
#vpc_u a { color:#808080; text-decoration:none; }
#vpc_u a:hover { text-decoration:underline; }
#vpc_msg { color:black; border: 1px solid #808080; padding:3px; }
.vpc_accion { color:green; font-size:16px; }
.vpc_priv { color:#9F009F; font-size:16px; }
.vpc_yo { color:#2D2D2D; }
</style>


<script type="text/javascript"> 
msg_ID = -1;
elnick = "'.$_SESSION['pol']['nick'].'";
minick = elnick;
chat_ID = "'.$chat_ID.'";
ajax_refresh = true;
refresh = "";
anonimo = false;
chat_delay = 4500;
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
delays();

chat_filtro = "normal";
chat_time = "";
acceso_leer = '.($acceso['leer']?'true':'false').';
acceso_escribir = '.($acceso['escribir']?'true':'false').';

al = new Array();
al_cargo = new Array();
array_ncargos = new Array();
array_ncargos = { 0:"", 98:"Turista", 99:"Extranjero", '.$array_ncargos.' };
array_ignorados = new Array();

window.onload = function(){
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
}



// FUNCIONES

function scroll_abajo() {
	if (chat_scroll <= document.getElementById("vpc").scrollTop) {
		document.getElementById("vpc").scrollTop = 900000;
		chat_scroll = document.getElementById("vpc").scrollTop;
	}
}

function siControlPulsado(event, nick){
	if (event.ctrlKey==1){
		toggle_ignorados(nick);
		return false;
	}
}

function toggle_ignorados(nick) {
	var idx = array_ignorados.indexOf(nick);
	if(idx != -1) {
		array_ignorados.splice(idx, 1);
		$("."+nick).show();
		scroll_abajo();
	}
	else {
		array_ignorados.push(nick); 
		$("."+nick).hide();
	}
	merge_list();
}

function cf_cambiarnick() {
	nick_anonimo = $("#cf_nick").val();
	nick_anonimo = nick_anonimo.replace(/[^A-Za-z0-9_-]/g, "");
	if ((nick_anonimo) && (nick_anonimo.length >= 3) && (nick_anonimo.length <= 14)) {
		elnick = "-" + nick_anonimo.replace(" ", "_"); 
		anonimo = elnick;
		$("#cf").hide();
		$("#chatform").show();
	} else { $("#cf_nick").val(""); }
}

function chat_filtro_change() {
	if (chat_filtro == "normal") {
		$(".cf_c, .cf_e").fadeOut("slow");
		chat_filtro = "solochat";
	} else {
		chat_filtro = "normal";
		$(".cf_c, .cf_e").fadeIn("slow");
		scroll_abajo();
	}
}

function msgkeyup(evt, elem) {
	if ($(elem).attr("value").substr(0,1) == "/") {
		$(elem).css("background", "#FF7777").css("color", "#952500");
	} else {
		$(elem).css("background", "none").css("color", "black");
	}
}

function msgkeydown(evt, elem) {
	obj = elem;
	var keyCode;
	if ("which" in evt) { keyCode=evt.which; }
	else if ("keyCode" in evt) { keyCode=evt.keyCode; }
	else if ("keyCode" in window.event) { keyCode=window.event.keyCode; }
	else if ("which" in window.event) { keyCode=evt.which; }
	if (keyCode == 9) {
		var elmsg = $(elem).attr("value");
		var array_elmsg = elmsg.split(" ");
		var elmsg_num = array_elmsg.length;
		var palabras = "";
		for (i=0;i<elmsg_num;i++) {
			if (i == (elmsg_num - 1)) { var ultima_palabra = array_elmsg[i].toLowerCase(); } else { palabras += array_elmsg[i] + " "; }
		}
		if (ultima_palabra) {
			var len_ultima_palabra = ultima_palabra.length;
			for (elnick in al) {
				var elmnick = elnick.toLowerCase();
				if (ultima_palabra == elmnick.substr(0, len_ultima_palabra)) {
					if (palabras) { obj.value = palabras + elnick + " "; } else { obj.value = elnick + " "; }
				}
			}
		}
		setTimeout("obj.focus()", 10);
	}
}

function chat_query_ajax() {
	if (ajax_refresh) {
		ajax_refresh = false;
		clearTimeout(refresh);
		$.post("/ajax.php", { chat_ID: chat_ID, n: msg_ID },
			function(data){
				ajax_refresh = true;
				if (data) { print_msg(data); }
				refresh = setTimeout(chat_query_ajax, chat_delay);
			}
		);
		if (ajax_refresh == true) { merge_list(); }
	}
}

function refresh_sin_leer() {
	document.title = chat_sin_leer_yo + chat_sin_leer + " - " + titulo_html;
}


function print_msg(data) {
	if (ajax_refresh) {
		var chat_sin_leer_antes = chat_sin_leer;
		var escondidos = new Array();
		var arraydata = data.split("\n");
		var msg_num = arraydata.length - 1;
		var list = "";
		for (i=0;i<msg_num;i++) {
			var mli = arraydata[i].split(" ");
			var txt = ""; var ml = mli.length; for (var e=4; e<ml; e++) { txt += mli[e] + " "; }
			if (chat_time == mli[2]) {
				mli[2] = "<span style=\"color:white;\">" + mli[2] + "</span>";
			} else {
				chat_time = mli[2];
			}
			if ((mli[1] == "c") || (mli[1] == "e")) {
				list += "<li id=\"" + mli[0] + "\" class=\"cf_" + mli[1] + "\">" + mli[2] + " <span class=\"vpc_accion\">" + txt + "</span></li>\n";
			} else if (mli[1] == "p") {
				if ((mli[3] == minick) && (mli[4] == "<b>Nuevo")) { } else {
					var nick_solo = mli[3].split("&rarr;");
					if (minick == nick_solo[0]) {
						list += "<li id=\"" + mli[0] + "\" class=\"" + nick_solo[0] + "\">" + mli[2] + " <span class=\"vpc_priv\" style=\"color:#004FC6\" ;OnClick=\"auto_priv(\'" + nick_solo[0] + "\');\"><b>[PRIV] " + mli[3] + "</b>: " + txt + "</span></li>\n";
					} else {
						list += "<li id=\"" + mli[0] + "\" class=\"" + nick_solo[0] + "\">" + mli[2] + " <span class=\"vpc_priv\" OnClick=\"auto_priv(\'" + nick_solo[0] + "\');\"><b>[PRIV] " + mli[3] + "</b>: " + txt + "</span></li>\n";
						chat_sin_leer_yo = chat_sin_leer_yo + "+";
					}
				}
			} else {'.($pol['nick']?'if ("'.$pol['nick'].'" != "") { var txt_antes = txt; var txt = txt.replace(/'.$pol['nick'].'/gi, "<b style=\"color:orange;\">" + minick + "</b>"); if (txt_antes != txt) { chat_sin_leer_yo = chat_sin_leer_yo + "+"; } }':'').'
				var vpc_yo = "";
				if (minick == mli[3]) { var vpc_yo = " class=\"vpc_yo\""; }
				if (mli[1].substr(0,3) == "98_") { var cargo_ID = 98; } else { var cargo_ID = mli[1]; }
				list += "<li id=\"" + mli[0] + "\" class=\"" + mli[3] + "\">" + mli[2] + " <img src=\"'.IMG.'cargos/" + cargo_ID + ".gif\" width=\"16\" height=\"16\" title=\"" + array_ncargos[cargo_ID] + "\" /> <b" + vpc_yo + " OnClick=\"auto_priv(\'" + mli[3] + "\');\">" + mli[3] + "</b>: " + txt + "</li>\n";
			}
			if (((msg_num - 1) == i) && (msg_num != "n")) { msg_ID = mli[0]; }
			if ((mli[1] != "e") && (mli[1] != "c")) { 
				if (mli[1] == "p") { var nick_p = mli[3].split("&rarr"); mli[3] = nick_p[0]; mli[1] = "0"; }
				al[mli[3]] = parseInt(new Date().getTime().toString().substring(0, 10));
				al_cargo[mli[3]] = mli[1];
			}
			var idx = array_ignorados.indexOf(mli[3]);
			if(idx != -1) {
				escondidos.push(mli[0]);
			}
			else {
				chat_sin_leer++;
			}
		}
		$("#vpc_ul").append(emoticono(list));
		merge_list();
		if ((chat_sin_leer > 0) || (chat_sin_leer_antes == -1)) {
			refresh_sin_leer();
			print_delay();
		}
		for(var i=0;i<escondidos.length;i++)
		{
    			$("#"+escondidos[i]).hide();
		}
	}
}

function merge_list() {
	var unix_timestamp = parseInt(new Date().getTime().toString().substring(0, 10));
	var times_exp = parseInt(unix_timestamp - 900); //15min
	var list = "";
	for (elnick in al) {
		if (al[elnick] < times_exp) {
			al[elnick] = null;
			al_cargo[elnick] = null;
		} else {
			if (al_cargo[elnick].substr(0,3) == "98_") {
				var kick_nick  = "ip-" + al_cargo[elnick].substr(3);
				list += "<li>'.$js_kick.' <img src=\"'.IMG.'cargos/98.gif\" title=\"" + array_ncargos[98] + "\" /> " + elnick + "</li>\n";
			} else {
				var kick_nick  = elnick;
				var idx = array_ignorados.indexOf(elnick);
				if(idx!=-1) {
					nick_tachado = "<strike>" + elnick + "</strike>";
				}
				else
				{
					nick_tachado = elnick;
				}
				list += "<li>'.$js_kick.' <img src=\"'.IMG.'cargos/" + al_cargo[elnick] + ".gif\" title=\"" + array_ncargos[al_cargo[elnick]] + "\" /> <a href=\"http://'.strtolower(PAIS).DEV.'.' .URL. '/perfil/" + elnick  + "/\" class=\"nick\" onClick=\"return siControlPulsado(event,\'"+ elnick +"\');\">" + nick_tachado + "</a></li>\n";
			}
		}
	}
	$("#chat_list").html(list);
}

function print_delay() {
	$("#vpc li:last").hide();
	if (chat_filtro == "solochat") { $(".cf_c, .cf_e").css("display","none"); }
	$("#vpc_msg").focus();
	setTimeout(function(){
		$("#vpc li:last").fadeIn("slow");
		scroll_abajo();
	}, 200);
}

function enviarmsg() {
 	var elmsg = $("#vpc_msg").attr("value");
	if ((elmsg) && (acceso_escribir)) {
		ajax_refresh = false;
		clearTimeout(refresh);
		$("#botonenviar").attr("disabled","disabled");
		$("#vpc_msg").attr("value","").css("background", "none").css("color", "black");
		$.post("/ajax.php", { a: "enviar", chat_ID: chat_ID, n: msg_ID, msg: elmsg, anonimo: anonimo }, 
		function(data){ 
			ajax_refresh = true;
			if (data) { chat_sin_leer = -1; print_msg(data); }
			setTimeout(function(){ $("#botonenviar").removeAttr("disabled"); }, 1600);
			chat_delay = 4500;
			refresh = setTimeout(chat_query_ajax, chat_delay);
			delays();
		} );
	}
	return false;
}

function change_delay(delay) {
	chat_delay = parseInt(delay) * parseInt(1000); 
}

function delays() {
	if (chat_delay1) { clearTimeout(chat_delay1); } chat_delay1 = setTimeout("change_delay(6)", 25000);
	if (chat_delay2) { clearTimeout(chat_delay2); } chat_delay2 = setTimeout("change_delay(10)", 60000);
	if (chat_delay3) { clearTimeout(chat_delay3); } chat_delay3 = setTimeout("change_delay(15)", 120000);
	if (chat_delay4) { clearTimeout(chat_delay4); } chat_delay4 = setTimeout("change_delay(60)", 300000);
	if (chat_delay_close) { clearTimeout(chat_delay_close); } chat_delay_close = setTimeout("chat_close()", 1800000); // 30min
}

function chat_close() {
	clearTimeout(refresh);
	$("body").before("<div id=\"chat_alert\" style=\"position:absolute;top:40%;left:40%;\"><button onclick=\"chat_enabled();\" style=\"font-weight:bold;font-size:28px;color:#888;\">Volver al chat...</button></div>");
}

function chat_enabled() {
	$("#chat_alert").remove();
	chat_query_ajax();
	chat_delay = 4500;
	refresh = setTimeout(chat_query_ajax, chat_delay);
	delays();
}

function auto_priv(nick) {
	$("#vpc_msg").attr("value","/msg " + nick + " ").css("background", "#FF7777").css("color", "#952500").focus();
}

function emoticono(m) {
m = m.replace(/(\s|^):\)/gi, " <img src=\"'.IMG.'smiley/sonrie.gif\" border=\"0\" alt=\":)\" title=\":)\" />");
m = m.replace(/(\s|^):\(/gi, " <img src=\"'.IMG.'smiley/disgustado.gif\" border=\"0\" alt=\":(\" title=\":(\" />");
m = m.replace(/(\s|^):\|/gi, " <img src=\"'.IMG.'smiley/desconcertado.gif\" border=\"0\" alt=\":|\" title=\":|\" />");
m = m.replace(/(\s|^):D/gi, " <img src=\"'.IMG.'smiley/xd.gif\" alt=\":D\" border=\"0\" title=\":D\" />");
m = m.replace(/(\s|^):\*/gi, " <img src=\"'.IMG.'smiley/muacks.gif\" alt=\":*\" border=\"0\" title=\":*\" />");
m = m.replace(/(\s|^);\)/gi, " <img src=\"'.IMG.'smiley/guino.gif\" alt=\";)\" border=\"0\" title=\";)\" />");
m = m.replace(/(\s|^):O/gi, " <img src=\"'.IMG.'smiley/bocaabierta.gif\" alt=\":O\" border=\"0\" title=\":O\" />");
m = m.replace(/(\s|^):tarta:/gi, " <img src=\"'.IMG.'smiley/tarta.gif\" alt=\":tarta:\" border=\"0\" title=\":tarta:\" />");
m = m.replace(/(\s|^):roto2:/gi, " <img src=\"'.IMG.'smiley/roto2.gif\" alt=\":roto2:\" border=\"0\" title=\":roto2:\" />");
m = m.replace(/(\s|^):facepalm:/gi, " <img src=\"'.IMG.'smiley/palm.gif\" alt=\":facepalm:\" border=\"0\" title=\":facepalm:\" />");
m = m.replace(/(\s|^):moneda:/gi, " <img src=\"'.IMG.'m.gif\" alt=\":moneda:\" border=\"0\" title=\":moneda:\" />");
return m;
}

</script>';

if ($externo) {
	// ES chat externo (incrustado en una web fuera de virtualpol.com)
	echo '
<html>
<body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
'.$txt_header.$txt.'
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-59186-46");
pageTracker._setDomainName("virtualpol.com");
pageTracker._trackPageview();
} catch(err) {}
</script>
</body></html>';
	if ($link) { mysql_error($link); }
}


?>




