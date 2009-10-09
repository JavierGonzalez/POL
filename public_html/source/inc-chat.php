<?php
$pol_chat_version = '';


if ($pol['estado'] == 'ciudadano') { $rsegundos = '5'; } else { $rsegundos = '10'; }

// obtiene last_ID
$result = mysql_query("SELECT ID_msg FROM ".SQL."chat_" . $pol['chat_id'] . " ORDER BY ID_msg DESC LIMIT 1", $link);
while ($row = mysql_fetch_array($result)) { $last_ID = $row['ID_msg'] - 60; }
if (($last_ID < 0) OR (!isset($last_ID))) { $last_ID = 0; }



// es: 12 Policia, 13 Comisario
if ((($pol['cargo'] == 12) OR ($pol['cargo'] == 13) OR (($pol['cargo'] == 22) AND ($pol['chat_id'] == 1))) AND ($pol['pais'] == PAIS) AND (HOST != 'www.virtualpol.com')) {
	$js_kick = '<a href=\"/control/kick/" + elnick  + "/\" target=\"_blank\"><img src=\"/img/kick.gif\" title=\"Kickear\" alt=\"Kickear\" border=\"0\" /></a> ';
}


// genera array js, nombres cargos
$result = mysql_query("SELECT ID, nombre FROM ".SQL."estudios WHERE asigna != '-1'", $link);
while ($row = mysql_fetch_array($result)) {
	if ($array_ncargos) { $array_ncargos .= ', '; } 
	$array_ncargos .= $row['ID'] . ':"' . $row['nombre'] . '"';
}


$txt .= '<p class="chat_title">'.$pol['chat_nombre'].'</p>

<div id="chat_users">
<ul id="chat_list">
</ul>
</div>
<div id="chatmsg">
<ul id="chatmsgul">

<li style="margin-top:380px;color:#AAA;"><b>
VirtualPol<br />
Pais: '.PAIS.'<br />
Chat: '.$pol['chat_nombre'].'<br />
Fecha: '.$date.'<br />
Ciudadano: '.$pol['nick'].'<br />
<hr />
</b></li>

' . $li . '
</ul>
</div>';


// imprime input msg
if (
(!$pol['chat_accesos']) AND (
($pol['estado'] == 'ciudadano') OR 
($pol['estado'] == 'desarrollador') OR
($pol['cargo'] == 42) OR
((($pol['estado'] == 'turista') OR ($pol['estado'] == 'extranjero')) AND ($pol['config']['frontera'] == 'abierta'))
) OR (($pol['chat_accesos']) AND (in_array($pol['nick'], $pol['chat_accesos_list'])))


) {

	$txt .= '<div id="chatform">
<form action="" method="POST" onSubmit="return enviarmsg();">

<table border="0" width="100%">
<tr>

<td width="100%"><input type="text" name="msg" onKeyUp="msgkeyup(event,this);" onKeyDown="msgkeydown(event,this);" id="mensaje" tabindex="1" autocomplete="off" size="65" maxlength="250" style="width:95%;" /></td>

<td><input name="cfilter" value="1" type="checkbox" OnClick="chat_filtro_change(chat_filtro);" style="margin-top:8px;" title="Filtro de eventos" /></td>

<td><input type="submit" value="Enviar" id="botonenviar" style="width:150px;" /></td>

<td><sub>' . $pol_chat_version . '</sub></td>

</tr>
</table>







</form>


</div>';
} else {
	if ($pol['chat_accesos']) {
		$txt .= '<p class="azul"><b>La participaci&oacute;n en este chat est&aacute; restringida.</a></b></p>';
	} elseif (($pol['estado'] == 'extranjero') OR ($pol['estado'] == 'turista')) {
		$txt .= '<p class="azul"><b>Las fronteras estan cerradas, solo los Ciudadanos de '.PAIS.' pueden participar.</a></b></p>';
	} else {
		$txt .= '<p class="azul"><b>Para participar en el chat debes ser Ciudadano de '.PAIS.', <a href="'.REGISTRAR.'">reg&iacute;strate aqu&iacute;!</a></b></p>';
	}

}

if ($pol['chat_accesos']) {
	foreach ($pol['chat_accesos_list'] AS $participante) {
		if ($particip) { $particip .= ', '; }
		$particip .= crear_link($participante);
	}
	$txt .= '<p><b>Participantes:</b> ' . $particip . '</p>';
}


// css & js
$txt_header .= '

<script type="text/javascript"> 
// INIT
idmsg = parseInt("' . $last_ID . '");
elnick = "' . $pol['nick'] . '";
minick = "' . $pol['nick'] . '";
chat_id = "' . $pol['chat_id'] . '";
ajax_refresh = true;
chat_delay = ' . $rsegundos . '000;
chat_delay1 = "";
chat_delay2 = "";
chat_delay3 = setTimeout("change_delay(10000)", 60000);
chat_delay4 = setTimeout("change_delay(15000)", 120000);
chat_delay5 = setTimeout("change_delay(60000)", 300000);
chat_filtro = "normal";
chat_time = "";

al = new Array();
al_cargo = new Array();
array_ncargos = new Array();
array_ncargos = { 0:"", 99:"Extranjero", ' . $array_ncargos . ' };

window.onload = function(){
	document.getElementById("chatmsg").scrollTop = 900000;
	merge_list();
	$("#mensaje").focus();
	refresh = setTimeout(chat_query_ajax, 6000);
	chat_query_ajax();
}



// FUNCIONES

function chat_filtro_change() {
	if (chat_filtro == "normal") {
		$(".cf_c, .cf_e").fadeOut("slow");
		chat_filtro = "solochat";
	} else {
		chat_filtro = "normal";
		$(".cf_c, .cf_e").fadeIn("slow");
		document.getElementById("chatmsg").scrollTop = 900000;
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
	ajax_refresh = false;
	clearTimeout(refresh);
	$.post("/ajax.php", { id: chat_id, n: idmsg },
		function(data){
			ajax_refresh = true;
			if (data) { print_msg(data); }
			refresh = setTimeout(chat_query_ajax, chat_delay);
		}
	);
	if (ajax_refresh == true) { merge_list(); }
}

function auto_priv(nick) {
	$("#mensaje").attr("value","/msg " + nick + " ").css("background", "#FF7777").css("color", "#952500").focus();
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
				list += "<li id=\"" + mli[0] + "\" class=\"cf_" + mli[1] + "\">" + mli[2] + " <span class=\"chat_accion\">" + txt + "</span></li>\n";
			} else if (mli[1] == "p") {
				if ((mli[3] == minick) && (mli[4] == "<b>Nuevo")) { } else {
					
					var nick_solo = mli[3].split("&rarr;");

					list += "<li id=\"" + mli[0] + "\" class=\"cf_p chat_priv\">" + mli[2] + " <span class=\"chat_priv\" OnClick=\"auto_priv(\'" + nick_solo[0] + "\');\"><b>[PRIV] " + mli[3] + "</b>: " + txt + "</span></li>\n";
				}
			} else {
				if ("' . $pol['nick'] . '" != "") { var txt = txt.replace(/' . $pol['nick'] . '/gi, "<b style=\"color:orange;\">" + minick + "</b>"); }
				var yo = "";
				if (minick == mli[3]) { var yo = " class=\"yo\""; }
				list += "<li id=\"" + mli[0] + "\" class=\"cf_m\">" + mli[2] + " <img src=\"/img/cargos/" + mli[1] + ".gif\" width=\"16\" height=\"16\" title=\"" + array_ncargos[mli[1]] + "\" /> <b" + yo + " OnClick=\"auto_priv(\'" + mli[3] + "\');\">" + mli[3] + "</b>: " + txt + "</li>\n";
			}
			if (((msg_num - 1) == i) && (msg_num != "n")) { idmsg = mli[0]; }
			if ((mli[1] != "p") && (mli[1] != "e") && (mli[1] != "c")) { 
				al[mli[3]] = parseInt(new Date().getTime().toString().substring(0, 10));
				al_cargo[mli[3]] = mli[1];
			}
		}
		$("#chatmsgul").append(emoticono(list));
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
			list += "<li>' . $js_kick . ' <img src=\"/img/cargos/" + al_cargo[elnick] + ".gif\" title=\"" + array_ncargos[al_cargo[elnick]] + "\" /> <a href=\"http://'.strtolower(PAIS).'.virtualpol.com/perfil/" + elnick  + "/\" class=\"nick\">" + elnick + "</a></li>\n";
		}
	}
	$("#chat_list").html(list);
}

function print_delay() {
	$("#chatmsg li:last").hide();
	if (chat_filtro == "solochat") { $(".cf_c, .cf_e").css("display","none"); }
	$("#mensaje").focus();
	setTimeout(function(){
		$("#chatmsg li:last").fadeIn("slow");
		document.getElementById("chatmsg").scrollTop = 900000;
	}, 200);
}

function enviarmsg() {
	var elmsg = $("#mensaje").attr("value");
	if (elmsg) {
		$("#botonenviar").attr("disabled","disabled");
		$("#mensaje").attr("value","").css("background", "none").css("color", "black");

		ajax_refresh = false;
		clearTimeout(refresh);  
		$.post("/ajax.php", { a: "enviar", id: chat_id, n: idmsg, msg: elmsg }, 
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
return msg;
}

</script>';

?>
