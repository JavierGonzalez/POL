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
		$res = mysql_query_old("SELECT HIGH_PRIORITY * FROM chats_msg 
WHERE chat_ID = ".$chat_ID." AND 
msg_ID > ".$msg_ID."".(isset($_SESSION['pol']['user_ID'])?" AND (user_ID = '0' OR user_ID = ".$_SESSION['pol']['user_ID']." OR (tipo = 'p' AND nick LIKE '".$_SESSION['pol']['nick']."&rarr;%'))":" AND tipo != 'p'")." 
ORDER BY msg_ID DESC LIMIT 1000");
		while ($r = r($res)) { 
			$t = $r['msg_ID'].' '.($r['tipo']!='m'?$r['tipo']:$r['cargo']).' '.substr($r['time'], 11, 5).' '.$r['nick'].' '.$r['msg']."\n".$t; 
		}
		return $t;
	}
}
