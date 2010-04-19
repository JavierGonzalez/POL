<?php
if ($_GET['b'] == 'e') { $externo = true; } else { $externo = false; }



$result = mysql_query("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET['a']."' LIMIT 1", $link);
while ($r = mysql_fetch_array($result)) { 
		 
	if ($r['pais'] != PAIS) { header('Location: http://'.strtolower($r['pais']).DEV.'.virtualpol.com/chats/'.$_GET['a'].'/'.($_GET['b']?$_GET['b'].'/':'')); exit; }

	$chat_ID = $r['chat_ID'];
	$titulo = $r['titulo'];

	// NUCLEO ACCESOS
	foreach (array('leer','escribir') AS $a) {
		$acceso[$a] = false;
		if (($r['acceso_'.$a] == 'privado') AND (in_array(strtolower($_SESSION['pol']['nick']), explode(" ", $r['acceso_cfg_'.$a])))) { $acceso[$a] = true; } 
		elseif (($r['acceso_'.$a] == 'nivel') AND ($_SESSION['pol']['nivel'] >= $r['acceso_cfg_'.$a])) { $acceso[$a] = true; }
		elseif (($r['acceso_'.$a] == 'antiguedad') AND (strtotime($_SESSION['pol']['fecha_registro']) >= strtotime($r['acceso_cfg_'.$a]))) { $acceso[$a] = true; }
		elseif (($r['acceso_'.$a] == 'ciudadanos_pais') AND ($_SESSION['pol']['pais'] == $r['pais'])) { $acceso[$a] = true; }
		elseif (($r['acceso_'.$a] == 'ciudadanos') AND (isset($_SESSION['pol']['user_ID']))) { $acceso[$a] = true; }
		elseif (($r['acceso_'.$a] == 'anonimos') AND ($_SESSION['pol']['estado'] != 'expulsado')) { $acceso[$a] = true; }
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
	$js_kick = '<a href=\"/control/kick/" + elnick  + "/\" target=\"_blank\"><img src=\"/img/kick.gif\" title=\"Kickear\" alt=\"Kickear\" border=\"0\" /></a> ';
} else { $js_kick = ''; }



$txt .= '
<h1 style="margin-bottom:4px;"><span style="float:right;">[<a href="/chats/'.$_GET['a'].'/opciones/">Opciones</a>]</span><a href="/chats/">Chat</a>: '.$titulo.'</h1>

<div id="vp_chat_users">
<ul id="chat_list">
</ul>
</div>

<div id="vp_chat">
<ul id="vp_chat_ul">
<li style="margin-top:380px;color:#AAA;"><b>
'.($acceso['leer']?'
VirtualPOL<br />
Gobierno de '.PAIS.'<br />
Chat: '.$titulo.'<br />
'.date('Y-m-d H:i:s').'<br />
Acceso leer: '.$acceso_leer.($acceso_cfg_leer?' [<em>'.$acceso_cfg_leer.'</em>]':'').'<br />
Acceso escribir: '.$acceso_escribir.($acceso_cfg_escribir?' [<em>'.$acceso_cfg_escribir.'</em>]':'').'<br />
Nick: '.($pol['nick']?$pol['nick']:'Anonimo').'<br />
<hr />
':'<span style="color:red;">No tienes acceso de lectura, lo siento.</span>').'
</b></li></ul>
</div>';

if ($acceso['escribir']) {

	$txt .= '<div id="chatform">
<form action="" method="POST" onSubmit="return enviarmsg();">

<table border="0" width="100%">
<tr>

<td width="100%"><input type="text" name="msg" onKeyUp="msgkeyup(event,this);" onKeyDown="msgkeydown(event,this);" id="vp_chat_msg" tabindex="1" autocomplete="off" size="65" maxlength="250" style="width:95%;" /></td>

<td><input name="cfilter" value="1" type="checkbox" OnClick="chat_filtro_change(chat_filtro);" style="margin-top:8px;" title="Filtro de eventos" /></td>

<td><input type="submit" value="Enviar" id="botonenviar" style="width:150px;" /></td>

<td></td>

</tr>
</table>

</form>

</div>';

} else { $txt .= '<p class="azul"><b>No tienes permiso de escritura.</b></p>'; }



// css & js
$txt_header .= '

<style type="text/css">
#vp_chat { vertical-align: bottom; height:400px; overflow:auto; background:white; }
#vp_chat ul { padding:0; margin:0; position:static; }
#vp_chat ul li { padding:0; margin:0; color:#666666; background:none; font-size:15px; list-style:none;}
#vp_chat .oldc { color:#A3A3A3; }
#vp_chat_users { float:right; width:180px; height:400px; overflow:auto; margin-left:20px; background:white; }
#vp_chat_users ul { padding:0; margin:0; position:static; }
#vp_chat_users ul li { padding:0; margin:0; color:#666666; background:none; font-size:18px; font-weight:bold; list-style:none;}
#vp_chat_users li { font-weight:bold; }
#vp_chat_users a { color:#808080; text-decoration:none; }
#vp_chat_users a:hover { text-decoration:underline; }
#vp_chat_msg { color:black; border: 1px solid #808080; padding:3px; }
.vp_chat_accion { color:green; font-size:16px; }
.vp_chat_priv { color:#9F009F; font-size:16px; }
.vp_chat_yo { color:#2D2D2D; }
</style>


<script type="text/javascript"> 
// INIT
msg_ID = -1;
elnick = "'.$pol['nick'].'";
if (!elnick) { 
	elnick = "#Anonimo"; // prompt("nick?") 
}
minick = elnick;
chat_ID = "'.$chat_ID.'";
ajax_refresh = true;
chat_delay = 5000;
chat_delay1 = "";
chat_delay2 = "";
chat_delay3 = setTimeout("change_delay(10000)", 60000);
chat_delay4 = setTimeout("change_delay(15000)", 120000);
chat_delay5 = setTimeout("change_delay(60000)", 300000);
chat_delay_close = setTimeout(prompt("Volver a activar chat?"), 108000000);
chat_filtro = "normal";
chat_time = "";
acceso_leer = '.($acceso['leer']?'true':'false').';
acceso_escribir = '.($acceso['escribir']?'true':'false').';

al = new Array();
al_cargo = new Array();
array_ncargos = new Array();
array_ncargos = { 0:"", 99:"Extranjero", '.$array_ncargos.' };

window.onload = function(){
	document.getElementById("vp_chat").scrollTop = 900000;
	merge_list();
	$("#vp_chat_msg").focus();
	'.($acceso['leer']?'refresh = setTimeout(chat_query_ajax, 6000); chat_query_ajax();':'').'
}



// FUNCIONES

function chat_filtro_change() {
	if (chat_filtro == "normal") {
		$(".cf_c, .cf_e").fadeOut("slow");
		chat_filtro = "solochat";
	} else {
		chat_filtro = "normal";
		$(".cf_c, .cf_e").fadeIn("slow");
		document.getElementById("vp_chat").scrollTop = 900000;
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
	// TAB
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
	if (acceso_leer) {
		ajax_refresh = false;
		clearTimeout(refresh);
		$.post("/ajax2.php", { chat_ID: chat_ID, n: msg_ID },
			function(data){
				ajax_refresh = true;
				if (data) { print_msg(data); }
				refresh = setTimeout(chat_query_ajax, chat_delay);
			}
		);
		if (ajax_refresh == true) { merge_list(); }
	}
}

function auto_priv(nick) {
	$("#vp_chat_msg").attr("value","/msg " + nick + " ").css("background", "#FF7777").css("color", "#952500").focus();
}

function print_msg(data) {
	if (ajax_refresh) {
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
				list += "<li id=\"" + mli[0] + "\" class=\"cf_" + mli[1] + "\">" + mli[2] + " <span class=\"vp_chat_accion\">" + txt + "</span></li>\n";
			} else if (mli[1] == "p") {
				if ((mli[3] == minick) && (mli[4] == "<b>Nuevo")) { } else {
					
					var nick_solo = mli[3].split("&rarr;");
					
					if (minick == nick_solo[0]) {
						list += "<li id=\"" + mli[0] + "\" class=\"cf_p vp_chat_priv\">" + mli[2] + " <span class=\"vp_chat_priv\" style=\"color:#004FC6\" ;OnClick=\"auto_priv(\'" + nick_solo[0] + "\');\"><b>[PRIV] " + mli[3] + "</b>: " + txt + "</span></li>\n";
					} else {
						list += "<li id=\"" + mli[0] + "\" class=\"cf_p vp_chat_priv\">" + mli[2] + " <span class=\"vp_chat_priv\" OnClick=\"auto_priv(\'" + nick_solo[0] + "\');\"><b>[PRIV] " + mli[3] + "</b>: " + txt + "</span></li>\n";
					}
				}
			} else {'.($pol['nick']?'if ("'.$pol['nick'].'" != "") { var txt = txt.replace(/'.$pol['nick'].'/gi, "<b style=\"color:orange;\">" + minick + "</b>"); }':'').'
				var vp_chat_yo = "";
				if (minick == mli[3]) { var vp_chat_yo = " class=\"vp_chat_yo\""; }
				list += "<li id=\"" + mli[0] + "\" class=\"cf_m\">" + mli[2] + " <img src=\"/img/cargos/" + mli[1] + ".gif\" width=\"16\" height=\"16\" title=\"" + array_ncargos[mli[1]] + "\" /> <b" + vp_chat_yo + " OnClick=\"auto_priv(\'" + mli[3] + "\');\">" + mli[3] + "</b>: " + txt + "</li>\n";
			}
			if (((msg_num - 1) == i) && (msg_num != "n")) { msg_ID = mli[0]; }
			if ((mli[1] != "p") && (mli[1] != "e") && (mli[1] != "c")) { 
				al[mli[3]] = parseInt(new Date().getTime().toString().substring(0, 10));
				al_cargo[mli[3]] = mli[1];
			}
		}
		$("#vp_chat_ul").append(emoticono(list));
		merge_list();
		print_delay();
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
			list += "<li>' . $js_kick . ' <img src=\"/img/cargos/" + al_cargo[elnick] + ".gif\" title=\"" + array_ncargos[al_cargo[elnick]] + "\" /> <a href=\"http://'.strtolower(PAIS).DEV.'.virtualpol.com/perfil/" + elnick  + "/\" class=\"nick\">" + elnick + "</a></li>\n";
		}
	}
	$("#chat_list").html(list);
}

function print_delay() {
	$("#vp_chat li:last").hide();
	if (chat_filtro == "solochat") { $(".cf_c, .cf_e").css("display","none"); }
	$("#vp_chat_msg").focus();
	setTimeout(function(){
		$("#vp_chat li:last").fadeIn("slow");
		document.getElementById("vp_chat").scrollTop = 900000;
	}, 200);
}

function enviarmsg() {
	var elmsg = $("#vp_chat_msg").attr("value");
	if ((elmsg) && (acceso_escribir)) {
		$("#botonenviar").attr("disabled","disabled");
		$("#vp_chat_msg").attr("value","").css("background", "none").css("color", "black");

		ajax_refresh = false;
		clearTimeout(refresh);  
		$.post("/ajax2.php", { a: "enviar", chat_ID: chat_ID, n: msg_ID, msg: elmsg }, 
		function(data){ 
			ajax_refresh = true;
			if (data) {
				print_msg(data);
				chat_delay = 3000;
			}
			refresh = setTimeout(chat_query_ajax, chat_delay);

			setTimeout(function(){ $("#botonenviar").removeAttr("disabled"); }, 1500);
			clearTimeout(chat_delay1); chat_delay1 = setTimeout("change_delay(4000)", 10000);
			clearTimeout(chat_delay2); chat_delay2 = setTimeout("change_delay(6000)", 20000);
			clearTimeout(chat_delay3); chat_delay3 = setTimeout("change_delay(10000)", 60000);
			clearTimeout(chat_delay4); chat_delay4 = setTimeout("change_delay(15000)", 120000);
			clearTimeout(chat_delay5); chat_delay5 = setTimeout("change_delay(60000)", 300000);
			clearTimeout(chat_delay_close); chat_delay_close = setTimeout(prompt("Volver a activar chat?"), 108000000);
		} );
	}
	return false;
}

function change_delay(delay) {
	chat_delay = delay; 
}

function emoticono(msg) {
msg = msg.replace(/(\s|^):\)/gi, " <img src=\"/img/smiley/sonrie.gif\" border=\"0\" alt=\":)\" title=\":)\" />");			// :)
msg = msg.replace(/(\s|^):\(/gi, " <img src=\"/img/smiley/disgustado.gif\" border=\"0\" alt=\":(\" title=\":(\" />");		// :(
msg = msg.replace(/(\s|^):\|/gi, " <img src=\"/img/smiley/desconcertado.gif\" border=\"0\" alt=\":|\" title=\":|\" />");	// :|
msg = msg.replace(/(\s|^):D/gi, " <img src=\"/img/smiley/xd.gif\" alt=\":D\" border=\"0\" title=\":D\" />");				// :D
msg = msg.replace(/(\s|^):\*/gi, " <img src=\"/img/smiley/muacks.gif\" alt=\":*\" border=\"0\" title=\":*\" />");			// :*
msg = msg.replace(/(\s|^);\)/gi, " <img src=\"/img/smiley/guino.gif\" alt=\";)\" border=\"0\" title=\";)\" />");			// ;)
msg = msg.replace(/(\s|^):O/gi, " <img src=\"/img/smiley/bocaabierta.gif\" alt=\":O\" border=\"0\" title=\":O\" />");		// :O
msg = msg.replace(/(\s|^):tarta:/gi, " <img src=\"/img/smiley/tarta.gif\" alt=\":tarta:\" border=\"0\" title=\":tarta:\" />");
msg = msg.replace(/(\s|^):roto2:/gi, " <img src=\"/img/smiley/roto2.gif\" alt=\":roto2:\" border=\"0\" title=\":roto2:\" />");
msg = msg.replace(/(\s|^):moneda:/gi, " <img src=\"/img/m.gif\" alt=\":moneda:\" border=\"0\" title=\":moneda:\" />");
return msg;
}

</script>';

if ($externo) {
	// ES chat externo (incrustado en una web fuera de virtualpol.com)
	echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>'.$txt_header.$txt;
	if ($link) { mysql_error($link); }
}


?>




