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
		$result = mysql_query("SELECT ID, pass, nick, cargo, nivel, pais, fecha_registro, estado FROM ".SQL_USERS." WHERE nick = '" .mysql_real_escape_string($_COOKIE['teorizauser'])."' LIMIT 1", $link);
		while ($row = mysql_fetch_array($result)) {
			if (md5(CLAVE.$row['pass']) == $_COOKIE['teorizapass']) {
				$session_new = true;
				$_SESSION['pol']['nick'] = $row['nick'];
				$_SESSION['pol']['user_ID'] = $row['ID'];
				$_SESSION['pol']['cargo'] = $row['cargo'];
				$_SESSION['pol']['nivel'] = $row['nivel'];
				$_SESSION['pol']['fecha_registro'] = $row['fecha_registro'];
				$_SESSION['pol']['pais'] = $row['pais'];
				$_SESSION['pol']['estado'] = $row['estado'];
			}
		}  
	}

	$pol['nick'] = $_SESSION['pol']['nick'];
	$pol['user_ID'] = $_SESSION['pol']['user_ID'];
}


// LOAD CONFIG
$result = mysql_unbuffered_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'si'", $link);
while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

// USER OK
if (isset($pol['user_ID'])) {

	// LOAD: $pol
	$result = mysql_unbuffered_query("SELECT online, estado, pais, pols, partido_afiliado, fecha_last, fecha_registro, nivel, fecha_init, cargo,
(SELECT COUNT(*) FROM ".SQL_MENSAJES." WHERE recibe_ID = ".SQL_USERS.".ID AND leido = '0') AS msg
FROM ".SQL_USERS." WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
	while($row = mysql_fetch_array($result)) {
		$pol['pols'] = $row['pols'];
		$pol['pais'] = $row['pais'];
		$pol['estado'] = $row['estado'];
		$pol['partido'] = $row['partido_afiliado'];
		$pol['fecha_registro'] = $row['fecha_registro'];
		$pol['nivel'] = $row['nivel'];
		$pol['msg'] = $row['msg'];
		$pol['online'] = $row['online'];
		$pol['cargo'] = $row['cargo'];
		$fecha_init = $row['fecha_init'];
		$fecha_last = $row['fecha_last'];

		if ($pol['estado'] == 'desarrollador') { $pol['pais'] = PAIS; $pol['nivel'] = 120; }

		if (($row['pais'] != PAIS) AND ($pol['estado'] == 'ciudadano')) { 
			// es extranjero
			$pol['estado'] = 'extranjero';
			if (($pol['cargo'] != 42) AND ($pol['cargo'] != 21)) { $pol['cargo'] = 99; }
			$pol['nivel'] = 0;
			$pol['pols'] = 0;
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
		mysql_query("UPDATE ".SQL_USERS." SET paginas = paginas + 1, fecha_last = '" . $date . "'" . $update . " WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
	} else { $pol = null; session_unset(); session_destroy(); } // impide el acceso a expulsados


	// EXPULSADO?
	$result = mysql_query("SELECT expire FROM ".SQL."ban WHERE estado = 'activo' AND (user_ID = '" . $pol['user_ID'] . "' OR (IP != '0' AND IP = '" . $IP . "')) LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){ 
		if ($row['expire'] < $date) { // DESBANEAR!
			mysql_query("UPDATE ".SQL."ban SET estado = 'inactivo' WHERE estado = 'activo' AND expire < '" . $date . "'", $link); 
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