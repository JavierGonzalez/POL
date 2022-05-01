<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




$txt_menu = 'comu';


function acceso_check($chat_ID, $ac=null) {
	global $link;
	if (isset($ac)) { $check = array($ac); } else { $check = array('leer','escribir','escribir_ex'); }
	$result = mysql_query_old("SELECT HIGH_PRIORITY acceso_leer, acceso_escribir, acceso_escribir_ex, acceso_cfg_leer, acceso_cfg_escribir, acceso_cfg_escribir_ex, pais FROM chats WHERE chat_ID = ".$chat_ID." LIMIT 1");
	while ($r = r($result)) { 
		foreach ($check AS $a) { $acceso[$a] = nucleo_acceso($r['acceso_'.$a], $r['acceso_cfg_'.$a]); }
	}
	if (isset($ac)) { return $acceso[$ac]; } else { return $acceso; }
}

/* ID CARGO 00:00 NICK MSG
m0 - m normal
p - m privado
e - evento
c - print comando
*/
function chat_refresh($chat_ID, $msg_ID=0) {
	global $link, $_SESSION;
	$t = '';

	if (acceso_check($chat_ID, 'leer') === true) { // Permite leer  
		$res = mysql_query_old("SELECT * FROM chats_msg 
            WHERE chat_ID = ".$chat_ID." 
            AND msg_ID > ".$msg_ID."
            AND time > '".date('Y-m-d H:i:s', time() - (60*60*24*5))."'
            ".(isset($_SESSION['pol']['user_ID'])?" 
            AND (user_ID = '0' OR user_ID = ".$_SESSION['pol']['user_ID']." OR (tipo = 'p' AND nick_sender = '".$_SESSION['pol']['nick']."'))":" AND tipo != 'p'")." 
            ORDER BY msg_ID DESC LIMIT 1000");
		while ($r = r($res)) { 
			$t = $r['msg_ID'].' '.($r['tipo']!='m'?$r['tipo']:$r['cargo']).' '.substr($r['time'], 11, 5).' '.$r['nick'].' '.$r['msg']."\n".$t; 
		}
		return $t;
	}
}

function comprobar_mensajes_foro_programados(){
	global $link, $_SESSION;
	//COMPROBAR MENSAJES DEL FORO PROGRAMADOS

	error_log("Comprobando mensajes del foro programados...");
	$result = mysql_query_old("SELECT chat_ID FROM chats WHERE pais = '".PAIS."' AND user_ID = '0' ORDER BY fecha_creacion ASC LIMIT 1");
	while($r = r($result)){ $chat_ID = $r['chat_ID']; }

	$result = mysql_query_old(" SELECT h.ID ID, CONCAT('/foro/', f.url, '/', h.url) url, h.title title, nick nick, fecha_programado 
	FROM ".strtolower(PAIS)."_foros_hilos h, ".strtolower(PAIS)."_foros f, users u
	WHERE
	h.sub_ID  = f.ID 
	AND 
	u.ID = h.user_ID
	AND fecha_programado is not null
	AND 
	fecha_programado < now()");
	while($r = r($result)){
		$msg = '<b>[FORO]</b> <a href="'.$r['url'].'/"><b>'.$r['title'].'</b></a> <span style="color:grey;">('.$r['nick'].')</span>';

		mysql_query_old("UPDATE ".strtolower(PAIS)."_foros_hilos SET fecha_programado = null WHERE ID = '".$r['ID']."'");
		mysql_query_old("INSERT INTO chats_msg (chat_ID, nick, msg, cargo, user_ID, tipo) VALUES ('".$chat_ID."', '".$r['nick']."', '".$msg."', '0', '0', 'e')");
	}
	//FIN COMPROBAR MENSAJES DEL FORO PROGRAMADOS
}
