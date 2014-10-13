/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

// VARIABLES
pnick = '';
whois_cache = new Array();
chat_msg_ID = new Array();
p_st = '';

// ON LOAD
$(document).ready(function(){
	
	// Reemplazo de emoticonos, etc.
	$(".rich").each(function (i) { $(this).html(enriquecer($(this).html(), true)); });

	// Botones HTML de votos +1 -1
	$(".votar").each(function (i) {
		var tipo = $(this).attr("type");
		var item_ID = $(this).attr("name");
		var voto = parseInt($(this).attr("value"));
		if (voto == 1) { var c_mas = " checked=\"checked\""; }
		if (voto == -1) { var c_menos = " checked=\"checked\""; }
		var radio_ID = tipo + item_ID;
		$(this).html("+<input type=\"radio\" class=\"radio_" + radio_ID + "\" name=\"radio_" + radio_ID + "\" onclick=\"votar(1, '" + tipo + "', '" + item_ID + "');\"" + c_mas + " /><input type=\"radio\" class=\"radio_" + radio_ID + "\" name=\"radio_" + radio_ID + "\" onclick=\"votar(-1, '" + tipo + "', '" + item_ID + "');\"" + c_menos + " />&#8211;"); 
	});

	search_timers();
	setInterval("search_timers()", 60000); // Actualiza temporizadores, cada 1 minuto.

	// Efecto scroll horizontal de Notificaciones.
	if (p_scroll == true) { 
		p_r = false;
		pl = 0;
		if (Math.floor(Math.random()*2) == 1) { p_r = true; } // Deslizado izquierda/derecha aleatorio.
		var p_st = setInterval("pscr()", 95); // Inicia deslizado cada 95ms (10 veces por segundo)
		var p_st_close = setTimeout("pscr_close()", 180000); // Detiene deslizado tras 3 min.
	}

	// Popup de info de ciudadanos.
	$(".nick").mouseover(function(){
		var wnick = $(this).text();
		if (!whois_cache[wnick]) { pnick = setTimeout(function(){ $.post("/ajax.php", { a: "whois", nick: wnick }, function(data){ $("#pnick").css("display","none"); whois_cache[wnick] = data; print_whois(data, wnick); }); }, 500);
		} else { print_whois(whois_cache[wnick], wnick); }
	}).mouseout(function(){ clearTimeout(pnick); pnick = ""; $("#pnick").css("display","none"); });
	$(document).mousemove(function(e){ $("#pnick").css({top: e.pageY + "px", left: e.pageX + 15 + "px"}); });

	// Mensajes emergentes de ayuda.
	$(".ayuda").hover(
		function () {
			var txt = $(this).attr("value");
			$(this).append('<span class="ayudap">' + txt + '</span>');
		}, 
		function () { $(".ayudap").remove(); }
	);

	setInterval("actualizar_noti()", 180000); // Actualiza notificaciones cada 3 min.
});



/*** FUNCIONES ***/


function pscr() {
	// Esta funcion es critica, debe optimizarse al máximo. Se ejecuta 10 veces por segundo.
	if (p_scroll == true) {
		if (p_r == true) { pl++; } else { pl--; }
		document.getElementById('menu-noti').style.backgroundPosition = pl + 'px 0';
	} else { if (p_st) { clearInterval(p_st); } }
}
function pscr_close() { p_scroll = false; }

function actualizar_noti() { 
	$('#notif').load('/ajax.php?a=noti');
	if (p_scroll == false) { clearInterval(p_st); }
	search_timers();
}

function votar(voto, tipo, item_ID) {
	var radio_ID = tipo + item_ID;
	$(".radio_" + radio_ID).blur();
	var voto_pre = parseInt($("#data_" + radio_ID).attr("value"));
	if (voto_pre == voto) { voto = 0; $(".radio_" + radio_ID).removeAttr("checked"); }
	$.get("/accion.php", { a: "voto", tipo: tipo, item_ID: item_ID, voto: voto }, function(data){
		if (data) {
			if (data == "false") {
				$(".radio_" + radio_ID).removeAttr("checked");
				alert("El voto no se ha podido realizar.");
			} else if (data == "limite") {
				$(".radio_" + radio_ID).removeAttr("checked");
				alert("Has llegado al limite de votos emitibles.");
			} else {
				if (tipo != "confianza") { $("#" + radio_ID).html(print_votonum(data)); }
				$("#data_" + radio_ID).attr("value", voto);
			}
		}
	});
}
function print_votonum(num) {
	var num = parseInt(num);
	if (num >= 10) { return "<span class=\"vcc\">+" + num + "</span>"; }
	else if (num >= 0) { return "<span class=\"vc\">+" + num + "</span>"; } 
	else if (num > -10) { return "<span class=\"vcn\">" + num + "</span>"; }
	else { return "<span class=\"vcnn\">" + num + "</span>"; }
}



function print_whois(whois, wnick) {
	var w = whois.split(":"); print_votonum(w[13])
	if (!whois) { $("#pnick").html("&dagger;"); } else {
	if (w[6] == 1) { var wa = "<img src=\"" + IMG + "a/" + w[0] + ".jpg\" style=\"float:right;margin:0 -6px 0 0;\" />"; } else { var wa = ""; }
	if (w[11] != 0) { var wc = "<img src=\"" + IMG + "cargos/" + w[11] + ".gif\" width=\"16\" /> "; } else { var wc = ""; }
	if (w[9] == "expulsado") { var exp = "<br /><b style=\"color:red;\">" + w[12] + "</b>"; } else { var exp = ""; }
		
		$("#pnick").html("<legend>" + wc + "<b style=\"color:grey;\"><span style=\"color:#555;\">" + wnick + "</span> (<span class=\"" + w[9] + "\">" + w[9].substr(0,1).toUpperCase() + w[9].substr(1,w[9].length) + "</span> de " + w[10] + ")</b>" + exp + "</legend>" + wa + "Confianza: " + print_votonum(w[13]) + "<br /><!--Afil: <b>" + w[7] + "</b><br />-->Foro: <b>" + w[8] + "</b><br /><br />Online: <b>" + w[5] + "</b><br />Ultimo acceso: <b>" + w[2] + "</b><br />Registrado: <b>" + w[1] + "</b>").css("display","inline");
	}
}


function search_timers() {
	var ts = Math.round((new Date()).getTime() / 1000);
	$(".timer").each(function (i) {
		var cuando = $(this).attr("value");
		$(this).text(hace(cuando, ts, 1, false));
	});
}

function hace(cuando, ts, num, pre) {
	tiempo = (cuando - ts);
	if (pre) { if (tiempo >= 0) { pre = _["En"]; } else { pre = _["Hace"]; } }
	tiempo = Math.abs(tiempo);
	
	var periods_sec = new Array(2419200, 86400, 3600, 60, 1);
	var periods_txt = new Array(_["meses"], _["días"], _["horas"], _["min"], _["seg"]);

	if (pre) { var duracion = pre + " "; } else { var duracion = ""; }

	tiempo_cont = tiempo;
	nm = 0;
	for (n in periods_sec) {
		sec = periods_sec[n];
		if ((nm < num) && ((tiempo_cont >= (sec*2)) || (n == 4))) {
			period = Math.floor(tiempo_cont / sec);
			if (n == 4) { 
				duracion += _["Segundos"];
			} else {
				duracion += period + " " + periods_txt[n];
			}
			if ((num != 1) && (n != 4)) { if (n != 3) { duracion += ", "; } else { duracion += " y "; } }
			tiempo_cont = tiempo_cont - (period * sec);
			nm++;
		}
	}
	return duracion;
}






// FUNCIONES CHAT START

function actualizar_ahora() {
	chat_delay = 4000;
	refresh = setTimeout(chat_query_ajax, chat_delay);
	delays();
	chat_query_ajax();
	scroll_abajo();
	$("#vpc_msg").focus();
}

function scroll_abajo() {
	if (chat_scroll <= document.getElementById("vpc").scrollTop) {
		document.getElementById("vpc").scrollTop = 90000000;
		chat_scroll = document.getElementById("vpc").scrollTop;
	}
}

function siControlPulsado(event, nick){
	if (event.ctrlKey==1) { toggle_ignorados(nick); return false; }
}

function toggle_ignorados(nick) {
	var idx = $.inArray(nick, array_ignorados);
	if (idx != -1) {
		array_ignorados.splice(idx, 1);
		$("."+nick).show();
		scroll_abajo();
	} else {
		array_ignorados.push(nick); 
		$("."+nick).hide();
	}
	merge_list();
	chat_scroll = 0;
	scroll_abajo();
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
		chat_filtro = "solochat";
		$(".cf_c, .cf_e").hide();
	} else {
		chat_filtro = "normal";
		$(".cf_c, .cf_e").show();	
	}
	chat_scroll = 0;
	scroll_abajo();
	$("#vpc_msg").focus();
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
		var start = new Date().getTime();
		$("#vpc_actividad").attr("src", IMG + "ico/punto_azul.png");
		$.post("/ajax.php", { chat_ID: chat_ID, n: msg_ID },
			function(data){
				ajax_refresh = true;
				if (data) { print_msg(data); }
				refresh = setTimeout(chat_query_ajax, chat_delay);
				var elapsed = new Date().getTime() - start;
				$("#vpc_actividad").attr("src", IMG + "ico/punto_gris.png").attr("title", "Chat actualizado (" + (chat_delay/1000) + " segundos, " + elapsed + "ms)");
			}
		);
		if (ajax_refresh == true) { merge_list(); }
	}
}

function refresh_sin_leer() { document.title = chat_sin_leer_yo + chat_sin_leer + " - " + titulo_html; }

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

			var m_ID = mli[0];
			var m_tipo = mli[1];
			var m_time = mli[2];
			var m_nick = mli[3];

			if (!chat_msg_ID[m_ID]) {
				
				chat_msg_ID[m_ID] = true;

				if (chat_time == m_time) { m_time = "<span style=\"color:#eee;\">" + m_time + "</span>"; } else { chat_time = m_time; }

				switch(m_tipo) {
					case "c":
					case "e":
						list += "<li id=\"" + m_ID + "\" class=\"cf_" + m_tipo + "\">" + m_time + " <span class=\"vpc_accion\">" + txt + "</span></li>\n";
						m_tipo = "0";
						break;

					case "p":
						if ((m_nick == minick) && (mli[4] == "<b>Nuevo")) { } else {
							var nick_solo = m_nick.split("&rarr;");
							var nick_s = nick_solo[0];
							if (minick == nick_s) {
								list += "<li id=\"" + m_ID + "\" class=\"" + nick_s + "\">" + m_time + " <span class=\"vpc_priv\" style=\"color:#004FC6\" ;OnClick=\"auto_priv(\'" + nick_s + "\');\"><b>[PRIV] " + m_nick + "</b>: " + txt + "</span></li>\n";
							} else {
								list += "<li id=\"" + m_ID + "\" class=\"" + nick_s + "\">" + m_time + " <span class=\"vpc_priv\" OnClick=\"auto_priv(\'" + nick_s + "\');\"><b>[PRIV] " + m_nick + "</b>: " + txt + "</span></li>\n";
								chat_sin_leer_yo = chat_sin_leer_yo + "+";
							}
						}
						var nick_p = m_nick.split("&rarr"); m_nick = nick_p[0]; m_tipo = "0";
						break;

					default:
						if (minick != "") { 
							var txt = " " + txt;
							var regexp = eval("/ "+minick+"/gi");
							var txt = txt.replace(regexp, " <b style=\"color:orange;\">" + minick + "</b>"); 
							if (txt.search(regexp) != -1) { chat_sin_leer_yo = chat_sin_leer_yo + "+"; } 
						}

						var vpc_yo = "";
						if (minick == m_nick) { var vpc_yo = " class=\"vpc_yo\""; }
						if (m_tipo.substr(0,3) == "98_") { var cargo_ID = 98; } else { var cargo_ID = m_tipo; }
						list += "<li id=\"" + m_ID + "\" class=\"" + m_nick + "\">" + m_time + " <img src=\""+IMG+"cargos/" + cargo_ID + ".gif\" width=\"16\" height=\"16\" title=\"" + array_cargos[cargo_ID] + "\" /> <b" + vpc_yo + " OnClick=\"auto_priv(\'" + m_nick + "\');\">" + m_nick + "</b>: " + txt + "</li>\n";
				}

				if (((msg_num - 1) == i) && (msg_num != "n") && (m_nick != "&nbsp;")) { msg_ID = m_ID; }
				if ((m_tipo != "c") && (m_nick != "_") && (m_nick != "")) { 
					al[m_nick] = parseInt(new Date().getTime().toString().substring(0, 10));
					if ((al_cargo[m_nick] == "0") || (!al_cargo[m_nick])) { al_cargo[m_nick] = m_tipo; }
				}

				var idx = $.inArray(m_nick, array_ignorados);
				if (idx != -1) { escondidos.push(m_ID); } else { chat_sin_leer++; }
			}
		}

		$("#vpc_ul").append(enriquecer(list, false));
		merge_list();
		if ((chat_sin_leer > 0) || (chat_sin_leer_antes == -1)) {
			refresh_sin_leer();
			print_delay();
		}
		for (var i=0;i<escondidos.length;i++) { $("#"+escondidos[i]).hide(); }
	}
}

function merge_list() {
	var unix_timestamp = parseInt(new Date().getTime().toString().substring(0, 10));
	var times_exp = parseInt(unix_timestamp - 1500); //25min
	
	array_list = new Array();
	for (elnick in al) {
		if (al[elnick] < times_exp) {
			al[elnick] = null;
			al_cargo[elnick] = null;
		} else {
			var cargo_ID = al_cargo[elnick];

			if (cargo_ID.substr(0,3) == "98_") { var kick_nick  = "ip-" + cargo_ID.substr(3); } 
			else { var kick_nick  = elnick; }

			var idx = $.inArray(elnick, array_ignorados);
			if (idx != -1) { nick_tachado = "<strike>" + elnick + "</strike>"; } else { nick_tachado = elnick; }
			
			if (array_list[cargo_ID] === undefined) { array_list[cargo_ID] = ""; }

			if (hace_kick) {
				js_kick = "<a href=\"/control/kick/" + kick_nick  + "/" + chat_ID  + "\" target=\"_blank\"><img src=\"" + IMG + "varios/kick.gif\" title=\"Kickear\" alt=\"Kickear\" border=\"0\" /></a>";
			} else {
				js_kick = "";
			}

			array_list[cargo_ID] += "<li>" + js_kick + " <img src=\""+IMG+"cargos/" + cargo_ID + ".gif\" title=\"" + array_cargos[cargo_ID] + "\" /> <a href=\"/perfil/" + elnick  + "\" class=\"nick\" onClick=\"siControlPulsado(event,\'"+ elnick +"\');\" target=\"_blank\">" + nick_tachado + "</a></li>\n";
		}
	}

	var list = "";

	for (cargo_ID in array_cargos) {
		if ((array_list[cargo_ID] !== undefined) && (cargo_ID > 0)) { list += array_list[cargo_ID]; }
	}
	for (cargo_ID in array_cargos) {
		if ((array_list[cargo_ID] !== undefined) && (cargo_ID == 0)) { list += array_list[cargo_ID]; }
	}

	$("#chat_list").html(list);
}



function print_delay() {
	if (chat_filtro == "solochat") { $(".cf_c, .cf_e").hide(); }
	$("#vpc_msg").focus();
	scroll_abajo();
}



function enviarmsg() {
 	var elmsg = $("#vpc_msg").attr("value");
	var boton_envia_estado = $("#botonenviar").attr("disabled");
	$("#vpc_actividad").attr("src", IMG + "ico/punto_rojo.png");
	if ((elmsg) && (boton_envia_estado != "disabled")) {
		ajax_refresh = false;
		clearTimeout(refresh);
		$("#botonenviar").attr("disabled","disabled");
		$("#vpc_msg").attr("value","").css("background", "none").css("color", "black");
		$.post("/ajax.php", { a: "enviar", chat_ID: chat_ID, n: msg_ID, msg: elmsg, anonimo: anonimo }, 
		function(data){ 
			ajax_refresh = true;
			if (data) { chat_sin_leer = -1; print_msg(data); }
			setTimeout(function(){ $("#botonenviar").removeAttr("disabled"); }, 1600);
			chat_delay = 4000;
			refresh = setTimeout(chat_query_ajax, chat_delay);
			delays();
			$("#vpc_actividad").attr("src", IMG + "ico/punto_gris.png");
		} );
	}
	return false;
}

function change_delay(delay) { chat_delay = parseInt(delay) * parseInt(1000); }

function delays() {
	if (chat_delay1) { clearTimeout(chat_delay1); } chat_delay1 = setTimeout("change_delay(6)", 25000);
	if (chat_delay2) { clearTimeout(chat_delay2); } chat_delay2 = setTimeout("change_delay(10)", 60000);
	if (chat_delay3) { clearTimeout(chat_delay3); } chat_delay3 = setTimeout("change_delay(15)", 120000);
	if (chat_delay4) { clearTimeout(chat_delay4); } chat_delay4 = setTimeout("change_delay(60)", 300000);
	if (chat_delay_close) { clearTimeout(chat_delay_close); } chat_delay_close = setTimeout("chat_close()", 7200000); // 2h
}

function chat_close() {
	clearTimeout(refresh);
	$("body").before("<div id=\"chat_alert\" style=\"position:absolute;top:40%;left:40%;\"><button onclick=\"chat_enabled();\" style=\"font-weight:bold;font-size:28px;color:#888;z-index:20px;\">Volver al chat...</button></div>");
}

function chat_enabled() {
	$("#chat_alert").remove();
	chat_query_ajax();
	chat_delay = 4500;
	refresh = setTimeout(chat_query_ajax, chat_delay);
	delays();
	$("#vpc_msg").focus();
	scroll_abajo();
}

function auto_priv(nick) { $("#vpc_msg").attr("value","/msg " + nick + " ").css("background", "#FF7777").css("color", "#952500").focus(); }

// ### FUNCIONES CHAT END


function enriquecer(m, bbcode) {

	// Emoticonos
	m = m.replace(/(\s|^):\)/gi,			' <img src="'+IMG+'smiley/sonrie.gif" border="0" alt=":)" title=":)" width="15" height="15" />');
	m = m.replace(/(\s|^):\(/gi,			' <img src="'+IMG+'smiley/disgustado.gif" border="0" alt=":(" title=":(" width="15" height="15" />');
	m = m.replace(/(\s|^):\|/gi,			' <img src="'+IMG+'smiley/desconcertado.gif" border="0" alt=":|" title=":|" width="15" height="15" />');
	m = m.replace(/(\s|^):D/gi,				' <img src="'+IMG+'smiley/xd.gif" alt=":D" border="0" title=":D" width="15" height="15" />');
	m = m.replace(/(\s|^):\*/gi,			' <img src="'+IMG+'smiley/muacks.gif" alt=":*" border="0" title=":*" width="15" height="15" />');
	m = m.replace(/(\s|^);\)/gi,			' <img src="'+IMG+'smiley/guino.gif" alt=";)" border="0" title=";)" width="15" height="15" />');
	m = m.replace(/(\s|^):O/gi,				' <img src="'+IMG+'smiley/bocaabierta.gif" alt=":O" border="0" title=":O" width="15" height="15" />');
	m = m.replace(/:(tarta|roto2|palm|moneda):/gi,	' <img src="'+IMG+'smiley/$1.gif" alt=":$1:" border="0" title=":$1:" width="16" height="16" />');
	m = m.replace(/(\s|^)(:troll:)/gi,			' <img src="'+IMG+'smiley/troll.gif" alt=":troll:" border="0" title=":troll:" width="15" height="15" />');
	m = m.replace(/(\s|^)(:falso:)/gi,			' <img src="'+IMG+'smiley/sonrie.gif" border="0" alt=":falso:" title=":falso:" width="15" height="15" onMouseOver="$(this).attr(\'src\', \''+IMG+'smiley/troll.gif\');" />');


	// URLs
	m = m.replace(/(\s|^|>)(\/[-A-Z0-9\/_]{3,})/ig, ' <a href="$2" target="_blank">$2</a>'); // /url
	m = m.replace(/(\s|^|>)@([-A-Z0-9_]{2,20})/ig, ' <a href="/perfil/$2" class="nick">@<b>$2</b></a>'); // @nick
	m = m.replace(/(\s|^|>)(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, ' <a href="$2" target="_blank">$2</a>');

	// BBCODE
	if (bbcode) {
		m = m.replace(/\[(b|i|em|s)\](.*?)\[\/\1\]/gi, '<$1>$2</$1>'); 
		m = m.replace(/\[img\](.*?)\[\/img\]/gi, '<img src="$1" alt="img" style="max-width:800px;" />');
		m = m.replace(/\[youtube\]http\:\/\/www\.youtube\.com\/watch\?v=(.*?)\[\/youtube\]/gi, '<iframe width="520" height="390" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>');
		m = m.replace(/\[quote\]/gi, '<blockquote><div class="quote">');
		m = m.replace(/\[quote=(.*?)\]/gi, '<blockquote><div class="quote"><cite>$1 escribió:</cite>');
		m = m.replace(/\[\/quote\]/gi, '</div></blockquote>');
	}

	// Botones Instant
	if (bbcode) { var boton_width = 50; } else { var boton_width = 16; }
	m = m.replace(/:(aplauso|noo|rickroll|relax|alarmanuclear|porquenotecallas|zas|aleluya):/gi, html_instant('$1', boton_width));

	return m;
}

function html_instant(nom, width) {
	return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" title=":' + nom + ':" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="' + width + '" width="' + width + '"><param name="quality" value="high" /><param name="wmode" value="transparent" /><param name="movie" value="' + IMG + 'instant/' + nom + '.swf" /><embed style="margin:0 0 -3px 0;" height="' + width + '" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="' + IMG + 'instant/' + nom + '.swf" type="application/x-shockwave-flash" width="' + width + '" wmode="transparent"></embed></object>';
}