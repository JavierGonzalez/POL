<?php
header('connection: close');
header('Content-Type: text/plain');
session_start(); 

// Inicialización.
if ((!isset($_SESSION['pol']['user_ID'])) AND (isset($_COOKIE['teorizauser']))) { 
	include('inc-login.php'); // Si no hay login, hace login basico
} else { include('../config.php'); }



// ARREGLAR: reemplazar esta zona por un include a un config.php ligero
$host = explode('.', $_SERVER['HTTP_HOST']);
if ($host[0] == 'hispania') { $host[0] = 'Hispania'; } else { $host[0] = strtoupper($host[0]); }
define('PAIS', $host[0]); 
define('SQL', strtolower($host[0]).'_');
define('DOMAIN', 'virtualpol.com');


function acceso_check($chat_ID, $ac=null) {
	global $link;
	if (isset($ac)) { $check = array($ac); } else { $check = array('leer','escribir','escribir_ex'); }
	$result = mysql_unbuffered_query("SELECT HIGH_PRIORITY acceso_leer, acceso_escribir, acceso_escribir_ex, acceso_cfg_leer, acceso_cfg_escribir, acceso_cfg_escribir_ex, pais FROM chats WHERE chat_ID = ".$chat_ID." LIMIT 1");
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
		$res = mysql_unbuffered_query("SELECT HIGH_PRIORITY * FROM chats_msg 
WHERE chat_ID = ".$chat_ID." AND 
msg_ID > ".$msg_ID."".(isset($_SESSION['pol']['user_ID'])?" AND (user_ID = '0' OR user_ID = ".$_SESSION['pol']['user_ID']." OR (tipo = 'p' AND nick LIKE '".$_SESSION['pol']['nick']."&rarr;%'))":" AND tipo != 'p'")." 
ORDER BY msg_ID DESC LIMIT 50");
		while ($r = r($res)) { 
			$t = $r['msg_ID'].' '.($r['tipo']!='m'?$r['tipo']:$r['cargo']).' '.substr($r['time'], 11, 5).' '.$r['nick'].' '.$r['msg']."\n".$t; 
		}
		return $t;
	}
}


// Prevención de inyección
foreach ($_POST AS $nom => $val) { $_POST[$nom] = escape($val, true, false); }


if ((!isset($_POST['a'])) AND (is_numeric($_POST['chat_ID'])) AND (is_numeric($_POST['n']))) {

	echo chat_refresh($_POST['chat_ID'], $_POST['n']);

} elseif (($_POST['a'] == 'enviar') AND (is_numeric($_POST['chat_ID']))) {

	$date = date('Y-m-d H:i:s');
	$chat_ID = $_POST['chat_ID'];

	// EXPULSADO?
	$result = sql("SELECT HIGH_PRIORITY ID FROM expulsiones WHERE estado = 'expulsado' AND user_ID = '".$_SESSION['pol']['user_ID']."' LIMIT 1");
	while($r = r($result)){ 
		$expulsado = true;
		session_destroy();
	}

	// KICKEADO?
	$result = sql("SELECT HIGH_PRIORITY expire FROM kicks 
WHERE pais = '".PAIS."' AND estado = 'activo' AND (user_ID = '".$_SESSION['pol']['user_ID']."' OR (IP != '0' AND IP != '' AND IP = inet_aton('".$_SERVER['REMOTE_ADDR']."'))) 
LIMIT 1");
	while($r = r($result)){ 
		if ($r['expire'] < $date) { // QUITAR KICK
			sql("UPDATE HIGH_PRIORITY kicks SET estado = 'inactivo' WHERE pais = '".PAIS."' AND estado = 'activo' AND expire < '".$date."'"); 
		} else { $expulsado = true; }
	}

	// CHECK MSG
	$msg_len = strlen($_POST['msg']);
	if (($msg_len > 0) AND ($msg_len < 400) AND (!isset($expulsado)) AND ((acceso_check($chat_ID, 'escribir')) OR (($_SESSION['pol']['pais'] != PAIS) AND (acceso_check($chat_ID, 'escribir_ex'))))) {
		
		if ((!isset($_SESSION['pol']['nick'])) AND (substr($_POST['anonimo'], 0, 1) == '-') AND (strlen($_POST['anonimo']) >= 3) AND (strlen($_POST['anonimo']) <= 15) AND (!stristr($_POST['anonimo'], '__'))) { 
			$result = sql("SELECT nick FROM users WHERE nick='".substr($_POST['anonimo'], 1)."'");
			if (r($result)) { 
				$borrar_msg = true;
				echo 'n 0 ---- - <b style="color:#FF0000;">Nick inv&aacute;lido por estar registrado.</b>'. "\n"; 
			}
			else {
				$_SESSION['pol']['nick'] = $_POST['anonimo'];
				$_SESSION['pol']['estado'] = 'anonimo';
			}
		}

		// limpia MSG
		$msg = $_POST['msg'];
		if (isset($borrar_msg)) { $msg = ''; }

		$msg = str_replace("ส็็็็็็็็", "", str_replace("ส็็็็็็็็็็็็็็็็็็็็็็็็็", "", str_replace("'", "''", str_replace("\r", "", str_replace("\n", "", trim($msg))))));
		
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

				case 'acceso':
					$result = sql("SELECT admin, acceso_cfg_escribir, acceso_escribir FROM chats WHERE chat_ID = '".$chat_ID."' LIMIT 1");
					while($r = r($result)){
						$admins = explode(' ', trim(strtolower($r['admin'])));
						if ((in_array(strtolower($_SESSION['pol']['nick']), $admins)) AND (in_array($r['acceso_escribir'], array('privado', 'excluir')))) {
							$escribir = explode(' ', $r['acceso_cfg_escribir']);
							if ($msg_array[1] == 'add') { $escribir[] = $msg_array[2]; } 
							elseif ($msg_array[1] == 'del') { $escribir = array_diff($escribir, array(strtolower($msg_array[2]))); }
							$escribir = trim(strtolower(implode(' ', $escribir)));
							sql("UPDATE chats SET acceso_cfg_escribir = '".$escribir."' WHERE chat_ID = '".$chat_ID."' LIMIT 1");
							$elmsg = 'Acceso cambiado a: <b>'.$escribir.'</b>';
							$target_ID = $_SESSION['pol']['user_ID'];
							$tipo = 'p';
							$elnick = $_SESSION['pol']['nick'];
						}
					}
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
					if (isset($_SESSION['pol']['user_ID'])) {
						$elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . '</b> te anima a unirte a la comunidad: <a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/r/'.strtolower($_SESSION['pol']['nick']).'/" target="_blank"><b>Crear Usuario</b></a>'; 
					}
					break;

				case 'trabaja': 
					if (PAIS != '15M') {
						$elmsg = 'la econom&iacute;a te necesita! <button class="small pill">Trabaja</button> :troll:'; 
						$tipo = 'm';
					}
					break;
				
				case 'dnie':
				case 'autentificado': 
					if (nucleo_acceso('autentificados')) {
						$elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . ' es autentico.</b> (<a href="'.SSL_URL.'dnie.php">Autentificado</a>)'; 
					}
					break;

				case 'me': $elmsg = '<b style="margin-left:20px;">' . $_SESSION['pol']['nick'] . '</b> ' . $msg_rest; break;
				case 'exit': $elmsg = '<span style="margin-left:20px;color:#66004C;"><b>' . $_SESSION['pol']['nick'] . '</b> se marcha, hasta pronto!</span>'; break;
				case 'sombras': $elmsg = '<span style="margin-left:20px;color:#585858;"><b>' . $_SESSION['pol']['nick'] . '</b> se retira a las sombras...</span>'; break;
				case 'ayuda': 
					$tipo = 'm';
					if (PAIS == '15M') {
						$elmsg = 'ofrece ayuda'.($msg_rest?' a '.$msg_rest:'').': <a href="http://www.virtualpol.com/video" target="_blank"><b>Bienvenida</b> (video)</a> - <a href="http://15m.'.DOMAIN.'/doc/faq---consultas-a-la-ciudadania/" target="_blank"><b>Ayuda y FAQ</b> (Preguntas frecuentes)</a> - <a href="http://15m.'.DOMAIN.'/doc/la-declaracion-2-0">La Declaraci&oacute;n</a>.</a>';
					} else {
						$elmsg = 'ofrece ayuda'.($msg_rest?' a '.$msg_rest:'').': <a href="http://www.virtualpol.com/video" target="_blank"><b>Bienvenida (video)</b></a> - <a href="http://www.'.DOMAIN.'/manual" target="_blank">Documentaci&oacute;n</a>.</a>';
					}
					break;

				case 'moderador': 
				case 'policia': if (nucleo_acceso('cargo', '13 12'))  { $elmsg = '<span style="color:blue;">' . $msg_rest . ' <b>(Aviso Oficial)</b></span>'; $tipo = 'm'; } break;

				case 'msg':
					if (isset($_SESSION['pol']['user_ID'])) {
						$nick_receptor = trim($msg_array[1]);
						$result = sql("SELECT HIGH_PRIORITY ID, nick FROM users WHERE nick = '" . $nick_receptor . "' LIMIT 1");
						while($r = r($result)){ 
							$elmsg = substr($msg_rest, (strlen($r['nick'])));
							$target_ID = $r['ID'];
							$tipo = 'p';
							$elnick = $_SESSION['pol']['nick'].'&rarr;'.$r['nick'];
						}
					}
					break;
			}
			unset($msg); if (isset($elmsg)) { $msg = $elmsg; }
			
		} else { $tipo = 'm'; }

		// insert MSG
		if (isset($msg)) {
			if (!isset($elnick)) { $elnick = $_SESSION['pol']['nick']; }
			if ($_SESSION['pol']['estado'] == 'anonimo') { $sql_ip = 'inet_aton("'.$_SERVER['REMOTE_ADDR'].'")'; } else { $sql_ip = 'NULL'; }

			$elcargo = $_SESSION['pol']['cargo'];
			if (($_SESSION['pol']['pais'] != PAIS) AND ($_SESSION['pol']['estado'] == 'ciudadano')) { $elcargo = 99; } // Extrangero

			sql("INSERT DELAYED INTO chats_msg (chat_ID, nick, msg, cargo, user_ID, tipo, IP) VALUES ('".$chat_ID."', '".$elnick."', '".$msg."', '".$elcargo."', '".$target_ID."', '".$tipo."', ".$sql_ip.")");

			sql("
UPDATE users SET fecha_last = '".$date."' WHERE ID = '".$_SESSION['pol']['user_ID']."' LIMIT 1;
UPDATE chats SET stats_msgs = stats_msgs + 1 WHERE chat_ID = '".$chat_ID."' LIMIT 1;
");

		}


		// print refresh
		if (isset($_POST['n'])) { echo chat_refresh($chat_ID, $_POST['n']); }

	} else { echo 'n 0 &nbsp; &nbsp; <b style="color:#FF0000;">No tienes permiso de escritura.</b>'."\n"; }

} else if ($_GET['a'] == 'noti') {
	
	define('REGISTRAR', 'https://virtualpol.com/registrar/');
	include_once('inc-login.php');
?>
<script type="text/javascript">
$('ul.menu').each(function(){
	$(this).find('li').has('ul').addClass('has-menu').append('<span class="arrow">&nbsp;</span>');
});
$('ul.menu li').hover(function(){
	$(this).find('ul:first').stop(true, true).show();
	$(this).addClass('hover');
}, function(){
	$(this).find('ul').stop(true, true).hide();
	$(this).removeClass('hover');
});
</script>
<?php
	echo notificacion('print');


} else if (($_GET['a'] == 'geo') AND (nucleo_acceso('ciudadanos_global'))) {
	header('Content-Type: application/javascript');
	echo 'var eventos = [';
	$result = sql("SELECT pais, nick, x, y FROM users WHERE estado = 'ciudadano' AND x IS NOT NULL LIMIT 5000"); 
	// .($_GET['b']?" AND pais = '".$_GET['b']."'":"")
	while ($r = r($result)) { echo '{"q":"'.$r['nick'].'","x":'.$r['y'].',"y":'.$r['x'].'},'; }
	echo '];';


} else if (($_POST['a'] == 'whois') AND (isset($_POST['nick']))) {

	$res = sql("SELECT ID, fecha_registro, partido_afiliado, fecha_last, nivel, online, nota, avatar, voto_confianza, estado, pais, cargo,
(SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND ID = users.partido_afiliado LIMIT 1) AS partido,
(SELECT COUNT(ID) FROM ".SQL."foros_hilos WHERE user_ID = users.ID LIMIT 1) AS num_hilos,
(SELECT COUNT(ID) FROM ".SQL."foros_msg WHERE user_ID = users.ID LIMIT 1) AS num_msg
FROM users WHERE nick = '".str_replace('@', '', $_POST['nick'])."' LIMIT 1");
	while ($r = r($res)) { 
		include('inc-functions.php');
		if ($r['avatar'] == 'true') { $r['avatar'] = 1; } else { $r['avatar'] = 0; }
		if (!isset($r['partido'])) { $r['partido'] = '-'; }

		if ($r['estado'] == 'expulsado') {
			$res2 = sql("SELECT razon FROM expulsiones WHERE user_ID = '".$r['ID']."' AND estado = 'expulsado' ORDER BY expire DESC LIMIT 1");
			while ($r2 = r($res2)) { $expulsion = str_replace(':', '', $r2['razon']); }
		}

		echo $r['ID'] . ':' . round((time() - strtotime($r['fecha_registro'])) / 60 / 60 / 24) . ' dias:' . duracion(time() - strtotime($r['fecha_last'])) . ':' . $r['nivel'] . ':' . $r['nota'] . ':' . duracion($r['online']) . ':' . $r['avatar'] . ':' . $r['partido'] . ':' . $r['num_hilos'] . '+' . $r['num_msg'] . ':' . $r['estado'] . ':' . $r['pais'] . ':' . $r['cargo'] . ':'.$expulsion.':'.$r['voto_confianza'].':';
	}

}


mysql_close($link);
?>