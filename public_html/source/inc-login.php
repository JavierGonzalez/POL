<?php
include('../config.php');
include('inc-functions.php');

//INIT
$mtime = explode(' ', microtime()); 
$tiempoinicial = $mtime[1] + $mtime[0]; 
$date = date('Y-m-d H:i:s');
$IP = direccion_IP('longip');
$link = conectar();


// LOGIN
if (isset($_COOKIE['teorizauser'])) { 
	session_start();
	
	if (!isset($_SESSION['pol'])) { //NO existe sesion
		$result = mysql_query("SELECT ID, pass, nick, cargo, nivel, pais, fecha_registro, estado FROM users WHERE nick = '" .mysql_real_escape_string($_COOKIE['teorizauser'])."' LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) {
			if (md5(CLAVE.$r['pass']) == $_COOKIE['teorizapass']) {
				$session_new = true;
				$_SESSION['pol']['nick'] = $r['nick'];
				$_SESSION['pol']['user_ID'] = $r['ID'];
				$_SESSION['pol']['cargo'] = $r['cargo'];
				$_SESSION['pol']['nivel'] = $r['nivel'];
				$_SESSION['pol']['fecha_registro'] = $r['fecha_registro'];
				$_SESSION['pol']['pais'] = $r['pais'];
				$_SESSION['pol']['estado'] = $r['estado'];

			}
		}  
	}

	$pol['nick'] = $_SESSION['pol']['nick'];
	$pol['user_ID'] = $_SESSION['pol']['user_ID'];

	// Control del tiempo para responder un examen. Puesto aquí para que sirva también para eliminar $_SESION['examen'] si se empieza un examen y no se reciben las respuestas. 
	if (isset($_SESSION['examen'])) {
		if (($_SESSION['examen']['tiempo'] + 10) <= time()) {
			unset($_SESSION['examen']);
		} 
	}
}


// LOAD CONFIG
$result = mysql_unbuffered_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'si'", $link);
while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

// USER OK
if (isset($pol['user_ID'])) {

	// LOAD: $pol
	$result = mysql_unbuffered_query("SELECT online, estado, pais, pols, partido_afiliado, bando, fecha_last, fecha_registro, nivel, fecha_init, cargo, fecha_legal,
(SELECT COUNT(*) FROM ".SQL_MENSAJES." WHERE recibe_ID = users.ID AND leido = '0') AS msg
FROM users WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {
		$pol['pols'] = $r['pols'];
		$pol['pais'] = $r['pais'];
		$pol['estado'] = $r['estado'];
		$pol['partido'] = $r['partido_afiliado'];
		$pol['bando'] = $r['bando'];
		$pol['fecha_registro'] = $r['fecha_registro'];
		$pol['nivel'] = $r['nivel'];
		$pol['msg'] = $r['msg'];
		$pol['online'] = $r['online'];
		$pol['cargo'] = $r['cargo'];
		$fecha_init = $r['fecha_init'];
		$fecha_last = $r['fecha_last'];

		$_SESSION['pol']['cargo'] = $r['cargo'];
		$_SESSION['pol']['nivel'] = $r['nivel'];

		if (($r['pais'] != PAIS) AND ($pol['estado'] == 'ciudadano')) { 
			// es extranjero
			$pol['estado'] = 'extranjero';
			if (($pol['cargo'] != 42) AND ($pol['cargo'] != 21)) { $pol['cargo'] = 99; }
			$pol['nivel'] = 0;
			$pol['pols'] = 0;
		}


		// Si no se han aceptado las nuevas condiciones obliga a aceptarlas.
		if (($r['fecha_legal'] == '0000-00-00 00:00:00') AND ($_GET['a'] != 'aceptar-condiciones')) {
			if ($link) { mysql_close($link); }
			header('Location: http://www.virtualpol.com/legal');
			exit;
		}


	}

	// UPDATE
	if ($pol['estado'] != 'expulsado') { // No esta expulsado
		if ($session_new) {
			// START SESSION
			$update = ", visitas = visitas + 1, nav = '" . $_SERVER['HTTP_USER_AGENT'] . "', fecha_init = '" . $date . "', IP = '" . $IP . "', host = '".gethostbyaddr(long2ip($IP))."'";
			if ($_SERVER['HTTP_X_FORWARDED_FOR']) { $update .= ", IP_proxy = '".$_SERVER['HTTP_X_FORWARDED_FOR']."'"; }
			if ($fecha_init != '0000-00-00 00:00:00') { 
				$update .= ", online = online + " . (strtotime($fecha_last) - strtotime($fecha_init)); 
			}
		}
		mysql_query("UPDATE LOW_PRIORITY users SET paginas = paginas + 1, fecha_last = '" . $date . "'" . $update . " WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
	} else { $pol = null; session_unset(); session_destroy(); } // impide el acceso a expulsados


	// EXPULSADO?
	$result = mysql_query("SELECT expire FROM ".SQL."ban WHERE estado = 'activo' AND (user_ID = '" . $pol['user_ID'] . "' OR (IP != '0' AND IP = '" . $IP . "')) LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){ 
		if ($r['expire'] < $date) { // DESBANEAR!
			mysql_query("UPDATE LOW_PRIORITY ".SQL."ban SET estado = 'inactivo' WHERE estado = 'activo' AND expire < '" . $date . "'", $link); 
		} else { // BANEADO 
			$pol['estado'] = 'kickeado';
		}
	}

	switch ($pol['estado']) {
		case 'expulsado': unset($_SESSION['pol']['nick']); break;
	}
}
unset($fecha_init, $fecha_last);

?>
