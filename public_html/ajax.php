<?php
include('config.php');
$link = @conectar();
header('connection: close');
header('Content-Type: text/plain');
ob_start('ob_gzhandler');
if (!isset($_SESSION)) { session_start(); }

/*
ID CARGO 00:00 NICK ...MENSAJE...

m0 - m normal
p - m privado
e - evento
c - print comando
*/


function chat_refresh($id, $n) {
	global $link, $_SESSION;
	$t = '';
	$res = mysql_unbuffered_query("SELECT * FROM ".SQL."chat_" . $id . " WHERE ID_msg > '" . $n . "' AND (user_ID = '0' OR user_ID = '" . $_SESSION['pol']['user_ID'] . "') ORDER BY ID_msg DESC LIMIT 100", $link);
	while ($r = mysql_fetch_array($res)) { 
		if ($r['tipo'] != 'm') { $r['cargo'] = $r['tipo']; }
		$t = $r['ID_msg'] . ' ' . $r['cargo'] . ' ' . substr($r['time'], 11, 5) . ' ' . $r['nick'] . ' ' . $r['msg'] . "\n" . $t; 
	}
	return $t;
}



$chat_id = mysql_real_escape_string($_REQUEST['id']);


if ((!isset($_REQUEST['a'])) AND (isset($_REQUEST['n']))) {
	echo chat_refresh($chat_id, $_REQUEST['n']);
} elseif ($_REQUEST['a'] == 'enviar') {

	$date = date('Y-m-d H:i:s');

	// carga ciudadano si existe
	$result = mysql_unbuffered_query("SELECT ID, nick, cargo, estado, pais FROM ".SQL_USERS." WHERE ID = '" . $_SESSION['pol']['user_ID'] . "' LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){
		$pol['user_ID'] = $row['ID']; 
		$pol['nick'] = $row['nick'];
		$pol['pais'] = $row['pais'];
		$pol['cargo'] = $row['cargo'];
		$pol['estado'] = $row['estado'];
		if ($pol['estado'] == 'desarrollador'){ $pol['pais'] = PAIS; $pol['cargo'] = 0; }
		//if (($pol['pais'] != PAIS) AND ($pol['estado'] == 'ciudadano')) { 
		//	if ($pol['cargo'] != 42) { $pol['cargo'] = 99; $pol['estado'] = 'extranjero'; }
		//}
	}
	
	//if ($pol['estado'] == 'extranjero') {
	//	$result = mysql_query("SELECT valor FROM ".SQL."config WHERE dato = 'frontera' LIMIT 1", $link);
	//	while($row = mysql_fetch_array($result)){ $pol['config']['frontera'] = $row['valor']; }
	//}

	// BANEADO? EXPULSADO!
	$result = mysql_unbuffered_query("SELECT expire FROM ".strtolower($pol['pais'])."_ban WHERE estado = 'activo' AND (user_ID = '" . $pol['user_ID'] . "' OR (IP != '0' AND IP != '' AND IP = '" . $_SERVER['REMOTE_ADDR'] . "')) LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){ 
		if ($row['expire'] < $date) { // DESBANEAR
			mysql_query("UPDATE ".SQL."ban SET estado = 'inactivo' WHERE estado = 'activo' AND expire < '" . $date . "'", $link); 
		} else { $pol['estado'] = 'expulsado'; }
	}


	// CHECK MSG
	$msg_len = strlen($_REQUEST['msg']);
	if (
($msg_len > 0) AND
($msg_len < 280) AND
($pol['nick'] == $_SESSION['pol']['nick']) AND
($pol['user_ID']) AND
(
($pol['estado'] == 'ciudadano') OR
($pol['estado'] == 'desarrollador')
)
) {


		// limpia MSG
		$msg = $_REQUEST['msg'];

		// limitacion caracteres
		
		//$msg = eregi_replace("[^a-z0-9 áéíóúñçüÁÉÍÓÚÑÇÜ \.\: ,; () {} ¿? ¡!\"-_·\$%&| ]", "", $msg);
		//$msg = utf8_encode($msg);

		$msg = str_replace("\r", "", str_replace("\n", "", trim(strip_tags($msg))));
		$msg = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/()]","<a target=\"_blank\" href=\"\\0\">\\0</a>", $msg);
		$target_ID = 0;
		$tipo = 'c';

		if (substr($msg, 0, 1) == '/') {
			// ES COMANDO
			$msg_array = explode(" ", $msg);
			$msg_key = substr($msg_array[0], 1);
			$msg_rest = substr($msg, (strlen($msg_key) + 2));
			$user_ID_priv = '0';

			switch ($msg_key) {
				case 'dado':
					$elmsg = '<b>[$]</b> <em>' . $pol['nick'] . '</em> tira el dado... <b>' . mt_rand(1,6) . '</b> &nbsp; <em>' . $msg_rest . '</em>';
					break;
				
				case 'd4':
					$elmsg = '<b>[$]</b> <em>' . $pol['nick'] . '</em> tira el d4... <b>' . mt_rand(1,4) . '</b> &nbsp; <em>' . $msg_rest . '</em>';	    
                    			break;

				case 'd8':
					$elmsg = '<b>[$]</b> <em>' . $pol['nick'] . '</em> tira el d8... <b>' . mt_rand(1,8) . '</b> &nbsp; <em>' . $msg_rest . '</em>';	    
					break;
				
				case 'd10':
					$elmsg = '<b>[$]</b> <em>' . $pol['nick'] . '</em> tira el d10... <b>' . mt_rand(1,10) . '</b> &nbsp; <em>' . $msg_rest . '</em>';	    
					break;
				
				case 'd12':
					$elmsg = '<b>[$]</b> <em>' . $pol['nick'] . '</em> tira el d12... <b>' . mt_rand(1,12) . '</b> &nbsp; <em>' . $msg_rest . '</em>';	    
					break;
				
				case 'd20':
					$elmsg = '<b>[$]</b> <em>' . $pol['nick'] . '</em> tira el d20... <b>' . mt_rand(1,20) . '</b> &nbsp; <em>' . $msg_rest . '</em>';	    
					break;

				case 'd%':
                                        $elmsg = '<b>[$]</b> <em>' . $pol['nick'] . '</em> tira el d%... <b>' . mt_rand(00,99) . '</b> &nbsp; <em>' . $msg_rest . '</em>';
					break;


				case 'calc': 
					if (ereg("^[0-9\+-\/\*\(\)\.]{1,30}$", strtolower($msg_rest))) { 
						@eval("\$result=" . $msg_rest . ";");
						if (substr($result, 0, 8) == 'Resource') { $result = 'calc error'; }
						$elmsg = '<b>[$] ' . $pol['nick'] . '</b> calc: <b style="color:blue">' . $msg_rest . '</b> <b style="color:grey;">=</b> <b style="color:red">' . $result . '</b>';
					}
					break;

				case 'aleatorio': $elmsg = '<b>[$] ' . $pol['nick'] . '</b> aleatorio: <b>' . mt_rand(00000,99999) . '</b>'; break;
				case 'servidor':  
					if ($msg_rest == 'cs') {
						$elmsg = '<b>[#] ' . $pol['nick'] . '</b> Servidor de Counter-Strike: <span class="gris">' . $_SERVER['REMOTE_ADDR'] . ':27015</span>';
					} elseif ($msg_rest == 'aoe') {
						$elmsg = '<b>[#] ' . $pol['nick'] . '</b> Servidor de AOE: ...';
					} 
					break;
				case 'me': $elmsg = '<b style="margin-left:20px;">' . $pol['nick'] . '</b> ' . $msg_rest; break;
				case 'ayuda':
				case 'novatos': $elmsg = '<b>[#] ' . $pol['nick'] . '</b> Ayuda: <a href="/doc/empezar-en-'.strtolower(PAIS).'/" target="_blank">C&oacute;mo empezar, FAQ</a>'; break;
				case 'policia':
					if (($pol['cargo'] == 13) OR ($pol['cargo'] == 12)) {
						$elmsg = '<span style="color:blue;">' . $msg_rest . ' <b>(Aviso Oficial)</b></span>';
						$tipo = 'm';
					}
					break;
				case 'msg':
					$nick_receptor = trim($msg_array[1]);
					$result = mysql_unbuffered_query("SELECT ID FROM ".SQL_USERS." WHERE nick = '" . $nick_receptor . "' LIMIT 1", $link);
					while($row = mysql_fetch_array($result)){ 
						$elmsg = substr($msg_rest, (strlen($nick_receptor)));
						$target_ID = $row['ID'];
						$tipo = 'p';
					}
					break;
					
					case 'parlamento':
					if(($pol['cargo'] == 22) AND ($chat_id == 1)){
						$elmsg = '<span style="color:blue;">' . $msg_rest . ' <b>(Aviso Oficial- Presidente del Parlamento)</b></span>';
						$tipo = 'm';
					}
					break;
			}
			$msg = null; if ($elmsg) { $msg = $elmsg; }
			
		} else { $tipo = 'm'; }

		// insert MSG
		if ($msg) {
			mysql_query("INSERT INTO ".SQL."chat_" . $chat_id . " (nick, time, msg, cargo, user_ID, tipo) VALUES ('" . $pol['nick'] . "', '" . $date . "', '" . $msg . "', '" . $pol['cargo'] . "', '" . $target_ID . "', '" . $tipo . "')", $link);
		}

		// refresca last
		mysql_query("UPDATE ".SQL_USERS." SET fecha_last = '" . $date . "' WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1");

		// limpia msg antiguos
		$time_margen = date('Y-m-d H:i:00', time() - 86400); //24h
		mysql_query("DELETE FROM ".SQL."chat_" . $chat_id . " WHERE time < '" . $time_margen . "'", $link);

		// print refresh
		echo chat_refresh($chat_id, $_REQUEST['n']);

	}

}

ob_end_flush();
mysql_close($link);
?>
