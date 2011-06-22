<?php
session_start(); 
if ((isset($_COOKIE['teorizauser'])) AND (!isset($_SESSION['pol']['user_ID']))) { include('inc-login.php'); } // Si no hay login, hace login basico
else { include('../config-pwd.php'); $link = @conectar(); } // Conecta MySQL solo
header('connection: close');
header('Content-Type: text/plain');

$host = explode('.', $_SERVER['HTTP_HOST']);
define('pais', str_replace('-dev', '', $host[0], $dev));
define('PAIS', strtoupper(pais)); // ARREGLAR: si se crea un pais con minusculas en el nombre esto corrompera el nucleo de acceso

define('SQL', strtolower(pais).'_');

/*
ID CARGO 00:00 NICK MSG
m0 - m normal
p - m privado
e - evento
c - print comando
*/


if ($_REQUEST['a'] != 'whois') {
// ### IMPORTANTE: MANTENER FUNCION IGUAL QUE SU COPIA IDENTICA EN inc-functions.php (duplicación por optimizacion (para evitar cargar archivo de funciones))
// ### NUCLEO ACCESO 3.0
function nucleo_acceso($tipo, $valor='') {
	global $_SESSION;
	$rt = false;
	switch ($tipo) {
		case 'excluir': if (!in_array(strtolower($_SESSION['pol']['nick']), explode(' ', strtolower($valor)))) { $rt = true; } break;
		case 'privado': if (in_array(strtolower($_SESSION['pol']['nick']), explode(' ', strtolower($valor)))) { $rt = true; } break;
		case 'confianza': if ($_SESSION['pol']['confianza'] >= $valor) { $rt = true; } break;
		case 'nivel': if (($_SESSION['pol']['pais'] == PAIS) AND ($_SESSION['pol']['nivel'] >= $valor)) { $rt = true; } break;
		case 'cargo': if (($_SESSION['pol']['pais'] == PAIS) AND (in_array($_SESSION['pol']['cargo'], explode(' ', $valor)))) { $rt = true; } break;
		case 'autentificados': if ($_SESSION['pol']['dnie'] == 'true') { $rt = true; } break;
		case 'antiguedad': if (($_SESSION['pol']['fecha_registro']) AND (strtotime($_SESSION['pol']['fecha_registro']) < (time() - ($valor*86400)))) { $rt = true; } break;
		case 'ciudadanos_pais': if (($_SESSION['pol']['pais'] == PAIS) AND ($_SESSION['pol']['estado'] == 'ciudadano')) { $rt = true; } break;
		case 'ciudadanos': if ((isset($_SESSION['pol']['user_ID'])) AND ($_SESSION['pol']['estado'] == 'ciudadano')) { $rt = true; } break;
		case 'anonimos': if ($_SESSION['pol']['estado'] != 'expulsado') { $rt = true; } break;
		
		case 'print': return array('privado'=>'Ciudadano1 C2 C3 ...', 'excluir'=>'Ciudadano1 C2 C3 ...', 'confianza'=>'0', 'cargo'=>'cargo_ID1 cID2 cID3 ...', 'nivel'=>'1', 'antiguedad'=>'365', 'autentificados'=>'', 'ciudadanos_pais'=>'', 'ciudadanos'=>'', 'anonimos'=>''); exit;
	}
	return $rt;
}
// ###
}


function acceso_check($chat_ID, $ac=null) {
	global $link, $_SESSION;
	if (isset($ac)) { $check = array($ac); } else { $check = array('leer','escribir'); }
	$result = mysql_query("SELECT HIGH_PRIORITY acceso_leer, acceso_escribir, acceso_cfg_leer, acceso_cfg_escribir, pais FROM chats WHERE chat_ID = '".$chat_ID."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 
		foreach ($check AS $a) { $acceso[$a] = nucleo_acceso($r['acceso_'.$a], $r['acceso_cfg_'.$a]); }
	}
	if (isset($ac)) { return $acceso[$ac]; } else { return $acceso; }
}


function chat_refresh($chat_ID, $msg_ID=0) {
	global $link, $_SESSION;
	$t = '';

	if (acceso_check($chat_ID, 'leer') === true) { // Permite leer  
		$res = mysql_unbuffered_query("SELECT HIGH_PRIORITY * FROM chats_msg 
WHERE chat_ID = '".$chat_ID."' AND 
msg_ID > '".$msg_ID."' AND 
(user_ID = '0' OR user_ID = '".$_SESSION['pol']['user_ID']."' OR (tipo = 'p' AND nick LIKE '".$_SESSION['pol']['nick']."&rarr;%')) 
ORDER BY msg_ID DESC LIMIT 50", $link);
		while ($r = @mysql_fetch_array($res)) { 
			if ($r['tipo'] != 'm') { $r['cargo'] = $r['tipo']; }
			if ($r['cargo'] == 98) { $r['cargo'] .= '_'.$r['IP']; }
			$t = $r['msg_ID'].' '.$r['cargo'].' '.date('H:i', strtotime($r['time'])).' '.$r['nick'].' '.$r['msg']."\n".$t; 
		}
		return $t;
	}
}




if ((!isset($_REQUEST['a'])) AND (is_numeric($_REQUEST['chat_ID']))) {

	echo chat_refresh($_REQUEST['chat_ID'], $_REQUEST['n']);

} elseif (($_REQUEST['a'] == 'enviar') AND (is_numeric($_REQUEST['chat_ID']))) {

	$date = date('Y-m-d H:i:s');
	$chat_ID = $_REQUEST['chat_ID'];

	// EXPULSADO?
	$result = mysql_unbuffered_query("SELECT HIGH_PRIORITY ID FROM expulsiones WHERE estado = 'expulsado' AND user_ID = '".$_SESSION['pol']['user_ID']."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){ 
		$expulsado = true;
		session_destroy();
	}

	// KICKEADO?
	$result = mysql_unbuffered_query("SELECT HIGH_PRIORITY expire FROM ".SQL."ban 
WHERE estado = 'activo' AND (user_ID = '".$_SESSION['pol']['user_ID']."' OR (IP != '0' AND IP != '' AND IP = inet_aton('".$_SERVER['REMOTE_ADDR']."'))) 
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){ 
		if ($r['expire'] < $date) { // QUITAR KICK
			mysql_query("UPDATE HIGH_PRIORITY ".SQL."ban SET estado = 'inactivo' WHERE estado = 'activo' AND expire < '".$date."'", $link); 
		} else { $expulsado = true; }
	}


	// CHECK MSG
	$msg_len = strlen($_REQUEST['msg']);
	if (($msg_len > 0) AND ($msg_len < 280) AND (!$expulsado) AND (acceso_check($chat_ID, 'escribir') === true)) {
		
		if ((!$_SESSION['pol']['nick']) AND (substr($_POST['anonimo'], 0, 1) == '-') AND (strlen($_POST['anonimo']) >= 3) AND (strlen($_POST['anonimo']) <= 15) AND (!stristr($_POST['anonimo'], '__'))) { 
			$result = mysql_query("SELECT nick FROM users WHERE nick='".substr($_POST['anonimo'], 1)."'", $link);
			if (mysql_fetch_array($result)) { 
				$borrar_msg = true;
				echo 'n 0 ---- - <b style="color:#FF0000;">Nick inv&aacute;lido por estar registrado.</b>'. "\n"; 
			}
			else {
				$_SESSION['pol']['nick'] = $_POST['anonimo'];
				$_SESSION['pol']['estado'] = 'anonimo';
			}
		}

		// limpia MSG
		$msg = $_REQUEST['msg'];
		if ($borrar_msg) {
			$msg='';
		}

		$msg = str_replace("\r", "", str_replace("\n", "", trim(strip_tags($msg))));
		if ($_SESSION['pol']['estado'] != 'anonimo') { $msg = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/()]","<a target=\"_blank\" href=\"\\0\">\\0</a>", $msg); }
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
					$param = $msg_array[1]; // parametro despues de /dado
					if ((is_numeric($param)) AND ($param > 1)) {
						$result_rand = mt_rand(1, $param);
						$result_type = ' de '.$param.' n&uacute;meros';
					} elseif ($param == '%') {
						$result_rand = mt_rand(00, 99).'%';
						$result_type = ' de porcentaje';
					} else { // dado normal
						$result_rand = mt_rand(1, 6);
						$result_type = '';
					}
					$elmsg = '<b>[$]</b> <em>' . $_SESSION['pol']['nick'] . '</em> tira el <b>dado'.$result_type.': <span style="font-size:16px;">'.$result_rand.'</span></b>';
					break;


				case 'calc': 
					if (ereg("^[0-9\+-\/\*\(\)\.]{1,100}$", strtolower($msg_rest))) { 
						@eval("\$result=" . $msg_rest . ";");
						if (substr($result, 0, 8) == 'Resource') { $result = 'calc error'; }
						$elmsg = '<b>[$] ' . $_SESSION['pol']['nick'] . '</b> calc: <b style="color:blue">' . $msg_rest . '</b> <b style="color:grey;">=</b> <b style="color:red">' . $result . '</b>';
					}
					break;

				case 'aleatorio': $elmsg = '<b>[$] ' . $_SESSION['pol']['nick'] . '</b> aleatorio: <b>' . mt_rand(00000,99999) . '</b>'; break;
				
				case 'ciudadano': 
					if ($_SESSION['pol']['user_ID']) {
						$elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . '</b> te anima a unirte a la comunidad: <a href="http://'.pais.'.virtualpol.com/r/'.strtolower($_SESSION['pol']['nick']).'/" target="_blank"><b>Crear Usuario</b></a>'; 
					}
					break;

				case 'autentificado': 
					if ($_SESSION['pol']['dnie'] == 'true') {
						$elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . '</b> est&aacute; correctamente <a href="http://www.virtualpol.com/dnie.php">autentificado</a>.'; 
					}
					break;

				case 'servidor':  
					if ($msg_rest == 'cs') {
						$elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . '</b> Servidor de Counter-Strike: <span class="gris">' . $_SERVER['REMOTE_ADDR'] . ':27015</span>';
					} elseif ($msg_rest == 'aoe') {
						$elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . '</b> Servidor de AOE: ...';
					} elseif ($msg_rest == 'BFV') {
						$elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . '</b> Servidor de Battlefield Vietnam: <span class="gris">' . $_SERVER['REMOTE_ADDR'] . ':15567</span>';
					}
					break;
				case 'me': $elmsg = '<b style="margin-left:20px;">' . $_SESSION['pol']['nick'] . '</b> ' . $msg_rest; break;
				case 'exit': $elmsg = '<span style="margin-left:20px;color:#66004C;"><b>' . $_SESSION['pol']['nick'] . '</b> se marcha, hasta pronto!</span>'; break;
				case 'sombras': $elmsg = '<span style="margin-left:20px;color:#585858;"><b>' . $_SESSION['pol']['nick'] . '</b> se retira a las sombras...</span>'; break;
				case 'ayuda': $elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . '</b> ofrece ayuda: <a href="http://docs.google.com/present/view?id=ddfcnxdb_15fqwwcpct" target="_blank"><b>Gu&iacute;a Inicial</b></a> - <a href="http://www.virtualpol.com/manual" target="_blank">Manual de ayuda</a>.</a>'; break;

				case 'policia':
				if ((($_SESSION['pol']['cargo'] == 13) OR ($_SESSION['pol']['cargo'] == 12)) AND (strtolower($_SESSION['pol']['pais']) == pais))  {
						$elmsg = '<span style="color:blue;">' . $msg_rest . ' <b>(Aviso Oficial)</b></span>';
						$tipo = 'm';
					}
					break;
				case 'msg':
					if ($_SESSION['pol']['user_ID']) {
						$nick_receptor = trim($msg_array[1]);
						$result = mysql_unbuffered_query("SELECT HIGH_PRIORITY ID, nick FROM users WHERE nick = '" . $nick_receptor . "' LIMIT 1", $link);
						while($row = mysql_fetch_array($result)){ 
							$elmsg = substr($msg_rest, (strlen($row['nick'])));
							$target_ID = $row['ID'];
							$tipo = 'p';
							$elnick = $_SESSION['pol']['nick'].'&rarr;'.$row['nick'];
						}
					}
					break;
					
				case 'parlamento':
					if (($_SESSION['pol']['cargo'] == 22) AND (strtolower($_SESSION['pol']['pais']) == pais)) {
						$elmsg = '<span style="color:blue;">'.$msg_rest.' <b>(Aviso Oficial- Presidente del Parlamento)</b></span>';
						$tipo = 'm';
					}
					break;
			}
			$msg = null; if ($elmsg) { $msg = $elmsg; }
			
		} else { $tipo = 'm'; }

		// insert MSG
		if ($msg) {
			if (!$elnick) { $elnick = $_SESSION['pol']['nick']; }
			if ($_SESSION['pol']['estado'] == 'anonimo') { $sql_ip = 'inet_aton("'.$_SERVER['REMOTE_ADDR'].'")'; } else { $sql_ip = 'NULL'; }

			$elcargo = $_SESSION['pol']['cargo'];
			if ((strtolower($_SESSION['pol']['pais']) != pais) AND ($_SESSION['pol']['estado'] == 'ciudadano')) { 
				if ($_SESSION['pol']['cargo'] != 42) { $elcargo = 99; }
			} elseif (substr($elnick, 0, 1) == '-') {
				$elcargo = 98;
				$elnick = substr($elnick, 1);
			}

			mysql_query("INSERT DELAYED INTO chats_msg (chat_ID, nick, msg, cargo, user_ID, tipo, IP) VALUES ('".$chat_ID."', '".$elnick."', '".$msg."', '".$elcargo."', '".$target_ID."', '".$tipo."', ".$sql_ip.")", $link);

			mysql_query("
UPDATE users SET fecha_last = '".$date."' WHERE ID = '".$_SESSION['pol']['user_ID']."' LIMIT 1;
UPDATE chats SET stats_msgs = stats_msgs + 1 WHERE chat_ID = '".$chat_ID."' LIMIT 1;
", $link);

		}


		// print refresh
		if ($_REQUEST['n']) { echo chat_refresh($chat_ID, $_REQUEST['n']); }

	} else { echo 'n 0 &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; <b style="color:#FF0000;">Chat bloqueado.</b>'."\n"; }


} elseif (($_REQUEST['a'] == 'whois') AND (isset($_REQUEST['nick']))) {

	$res = mysql_query("SELECT ID, fecha_registro, partido_afiliado, fecha_last, nivel, online, nota, avatar, estado, pais, cargo,
(SELECT siglas FROM ".SQL."partidos WHERE ID = users.partido_afiliado LIMIT 1) AS partido,
(SELECT COUNT(ID) FROM ".SQL."foros_hilos WHERE user_ID = users.ID LIMIT 1) AS num_hilos,
(SELECT COUNT(ID) FROM ".SQL."foros_msg WHERE user_ID = users.ID LIMIT 1) AS num_msg
FROM users WHERE nick = '".mysql_real_escape_string($_REQUEST['nick'])."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($res)) { 
		include('inc-functions.php');
		if ($r['avatar'] == 'true') { $r['avatar'] = 1; } else { $r['avatar'] = 0; }
		if (!$r['partido']) { $r['partido'] = '-'; }

		if ($r['estado'] == 'expulsado') {
			$res2 = mysql_query("SELECT razon FROM expulsiones WHERE user_ID = '".$r['ID']."' AND estado = 'expulsado' ORDER BY expire DESC LIMIT 1", $link);
			while ($r2 = mysql_fetch_array($res2)) { $expulsion = str_replace(':', '', $r2['razon']); }
		}

		echo $r['ID'] . ':' . round((time() - strtotime($r['fecha_registro'])) / 60 / 60 / 24) . ' dias:' . duracion(time() - strtotime($r['fecha_last'])) . ':' . $r['nivel'] . ':' . $r['nota'] . ':' . duracion($r['online']) . ':' . $r['avatar'] . ':' . $r['partido'] . ':' . $r['num_hilos'] . '+' . $r['num_msg'] . ':' . $r['estado'] . ':' . $r['pais'] . ':' . $r['cargo'] . ':'.$expulsion.':';
	}

}


mysql_close($link);
?>
