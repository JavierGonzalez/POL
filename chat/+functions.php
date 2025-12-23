<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



date_default_timezone_set('Europe/Madrid');

$txt_menu = 'comu';


function acceso_check($chat_ID, $ac=null) {
	global $link;
	if (isset($ac)) { $check = array($ac); } else { $check = array('leer','escribir','escribir_ex'); }
	$result = mysql_query_old("SELECT acceso_leer, acceso_escribir, acceso_escribir_ex, acceso_cfg_leer, acceso_cfg_escribir, acceso_cfg_escribir_ex, pais FROM chats WHERE chat_ID = ".$chat_ID." LIMIT 1");
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
	fecha_programado < '".date('Y-m-d H:i:s')."'");
	while($r = r($result)){
		$msg = '<b>[FORO]</b> <a href="'.$r['url'].'/"><b>'.$r['title'].'</b></a> <span style="color:grey;">('.$r['nick'].')</span>';

		mysql_query_old("UPDATE ".strtolower(PAIS)."_foros_hilos SET fecha_programado = null WHERE ID = '".$r['ID']."'");
		mysql_query_old("INSERT INTO chats_msg (chat_ID, nick, msg, cargo, user_ID, tipo) VALUES ('".$chat_ID."', '".$r['nick']."', '".$msg."', '0', '0', 'e')");
	}
	//FIN COMPROBAR MENSAJES DEL FORO PROGRAMADOS
}



function ai_context(array $config) {

                // Obtener nombres de cargos para el país actual
                $cargo_map = [];
                $cargo_res = sql_old("SELECT cargo_id, nombre FROM cargos WHERE pais = '".PAIS."'");
                while ($c = r($cargo_res)) {
                    $cargo_map[$c['cargo_id']] = $c['nombre'];
                }

                $lines = [];
                $res_ctx = sql_old("SELECT time, nick, msg, cargo FROM chats_msg WHERE chat_ID = '".$config['chat_ID']."' AND tipo != 'p' ORDER BY msg_ID DESC LIMIT 500");
                while ($r2 = r($res_ctx)) {
                    $time_part = substr($r2['time'], 11, 5);
                    $cargo_name = $cargo_map[$r2['cargo']] ?? '';
                    $nick_display = $r2['nick'];
                    if ($cargo_name !== '') {
                        $nick_display = '[' . $cargo_name . '] '.$nick_display;
                    }
                    $lines[] = $nick_display . ': ' . strip_tags($r2['msg']);
                }
                $lines = array_reverse($lines);
                $last_chat = implode("\n", $lines);


                // ---- NEW: Add all documents from table "doc" to the context ----
                $docs = sql_old("SELECT title, text FROM docs WHERE pais = '".PAIS."' AND url IN ('constitucion-de-pol_1734036783', 'codigo-civil', 'codigo-penal_1740516134', 'ley-de-policia', 'ley-del-parlamento_1738403641') ORDER BY time DESC LIMIT 100");
                $docs_text = '';
                while ($doc = r($docs)) {
                    $title = $doc['title'] ?? '';
                    $text  = $doc['text'] ?? '';
                    $docs_text .= "<pol_doc title=\"".$title . "\">\n" . $text . "\n</pol_doc>\n\n\n";
                }
                // ---------------------------------------------------------

                $prompt_system = "

<pol_ai !important>
Eres AI. Eres el agente de AI de POL (una red social democrática experimental). 
Tu objetivo es servir a los ciudadanos de POL. 
</pol_ai>

<pol_main !important>
**POL es un pais (virtual) soberano e independiente.**

Con sus propias elecciones, leyes, instituciones y una comunidad activa.
Quien manda es la Presidenta de POL.
</pol_main>


<pol_instructions !important>
**Responde con UNA ÚNICA frase para hablar en un chat (estilo IRC), sin prefijos, sin comillas, ni enlaces, ni HTML. **
Puedes extenderte cuando sea necesario.
No te excuses (por ejemplo, no digas 'pero no tengo datos privados mas allá del chat'). 

Usa lenguaje informal, natural.
Responde a todo lo que se te pida con total libertad.
</pol_instructions>


<pol_docs>
# Documentos del sistema:

".$docs_text."
</pol_docs>

**Responde a los ultimos mensajes del chat.**

";

    return [
        'prompt_system' => $prompt_system,
        'prompt_user'   => $last_chat,
    ];
}