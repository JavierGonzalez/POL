<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

define('TIME_START', microtime(true));

include('../config.php');

//INIT
$date = date('Y-m-d H:i:s');
$IP = direccion_IP('longip');

// Prevención de inyección
foreach ($_GET  AS $nom => $val) { $_GET[$nom] = escape($val); }
foreach ($_POST AS $nom => $val) { $_POST[$nom] = escape($val, false); }
foreach ($_REQUEST AS $nom => $val) { $_REQUEST[$nom] = escape($val); }
foreach ($_COOKIE AS $nom => $val) { $_COOKIE[$nom] = escape($val); }
/*
foreach (array('GET', 'POST', 'REQUEST', 'COOKIE') AS $_) {
	foreach (${'_'.$_} AS $key=>$value) {
		if (get_magic_quotes_gpc()) { $value = stripslashes($value); }
		$value = str_replace(
			array("\r\n",   "\n",     '\'',    '"',     '\\'   ), 
			array('<br />', '<br />', '&#39;', '&#34;', '&#92;'),
		$value);
		${'_'.$_}[$key] = mysql_real_escape_string($value); 
	}
}
*/


// LOGIN
if (isset($_COOKIE['teorizauser'])) { 
	session_start();
	
	if (!isset($_SESSION['pol'])) { //NO existe sesion
		$result = sql("SELECT ID, pass, nick, cargo, nivel, pais, fecha_registro, estado, dnie, voto_confianza FROM users WHERE nick = '" .$_COOKIE['teorizauser']."' LIMIT 1");
		while ($r = r($result)) {
			if (md5(CLAVE.$r['pass']) == $_COOKIE['teorizapass']) {
				$session_new = true;
				$_SESSION['pol']['nick'] = $r['nick'];
				$_SESSION['pol']['user_ID'] = $r['ID'];
				$_SESSION['pol']['fecha_registro'] = $r['fecha_registro'];
				$_SESSION['pol']['confianza'] = $r['voto_confianza'];
			}
		}  
	}

	$pol['nick'] = $_SESSION['pol']['nick'];
	$pol['user_ID'] = $_SESSION['pol']['user_ID'];

	// Control del tiempo para responder un examen. Puesto aquí para que sirva también para eliminar $_SESION['examen'] si se empieza un examen y no se reciben las respuestas. 
	if (isset($_SESSION['examen'])) {
		if (($_SESSION['examen']['tiempo'] + 10) <= time()) { unset($_SESSION['examen']); } 
	}
}

// USER OK
if (isset($pol['user_ID'])) {

	// LOAD: $pol
	$result = sql("SELECT lang, online, estado, pais, pols, partido_afiliado, bando, fecha_last, fecha_registro, nivel, fecha_init, cargo, cargos, examenes, fecha_legal, dnie, SC, IP, grupos, socio
FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	while($r = r($result)) {
		$pol['pols'] = $r['pols'];
		$pol['pais'] = $r['pais'];
		$pol['estado'] = $r['estado'];
		$pol['partido'] = $r['partido_afiliado'];
		$pol['bando'] = $r['bando'];
		$pol['fecha_registro'] = $r['fecha_registro'];
		$pol['nivel'] = $r['nivel'];
		$pol['online'] = $r['online'];
		$pol['cargo'] = $r['cargo'];
		$pol['IP'] = $r['IP'];
		$pol['grupos'] = $r['grupos'];
		$fecha_init = $r['fecha_init'];
		$fecha_last = $r['fecha_last'];
		
		if ((isset($r['lang'])) AND ($_SERVER['REQUEST_URI'] != '/accion.php')) { $pol['config']['lang'] = $r['lang']; }

		$_SESSION['pol']['cargo'] = $r['cargo'];
		$_SESSION['pol']['cargos'] = $r['cargos'];
		$_SESSION['pol']['examenes'] = $r['examenes'];
		$_SESSION['pol']['nivel'] = $r['nivel'];
		$_SESSION['pol']['pais'] = $r['pais'];
		$_SESSION['pol']['estado'] = $r['estado'];
		$_SESSION['pol']['socio'] = $r['socio'];
		$_SESSION['pol']['dnie'] = $r['dnie'];
		$_SESSION['pol']['SC'] = $r['SC'];
		$_SESSION['pol']['partido_afiliado'] = $r['partido_afiliado'];
		$_SESSION['pol']['pols'] = $r['pols'];
		$_SESSION['pol']['grupos'] = $r['grupos'];

		if (($r['pais'] != PAIS) AND ($pol['estado'] == 'ciudadano')) { 
			// es extranjero
			$pol['estado'] = 'extranjero';
			if (($pol['cargo'] != 42) AND ($pol['cargo'] != 21)) { $pol['cargo'] = 99; }
			$pol['nivel'] = 0;
			$pol['pols'] = 0;
		}
	}

	// UPDATE
	if ($pol['estado'] != 'expulsado') { // No esta expulsado
		if (isset($session_new)) { // START SESSION
			$update = ", visitas = visitas + 1, nav = '".$_SERVER['HTTP_USER_AGENT']."', fecha_init = '".$date."'";
			if ($fecha_init != '0000-00-00 00:00:00') { $update .= ", online = online + ".(strtotime($fecha_last)-strtotime($fecha_init)); }
			include_once('inc-functions-accion.php');
			$txt .= users_con($pol['user_ID'], '', 'session', true);
		}
		sql("UPDATE LOW_PRIORITY users SET paginas = paginas + 1, fecha_last = '".$date."'".$update." WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	} else { unset($pol); session_unset(); session_destroy(); } // impide el acceso a expulsados


	// EXPULSADO?
	$result = sql("SELECT expire FROM kicks WHERE pais = '".PAIS."' AND estado = 'activo' AND (user_ID = '".$pol['user_ID']."' OR (IP != '0' AND IP = '".$IP."')) LIMIT 1");
	while($r = r($result)){ 
		if ($r['expire'] < $date) { // DESBANEAR!
			sql("UPDATE LOW_PRIORITY kicks SET estado = 'inactivo' WHERE pais = '".PAIS."' AND estado = 'activo' AND expire < '".$date."'"); 
		} else { // BANEADO 
			$pol['estado'] = 'kickeado';
			$_SESSION['pol']['estado'] = 'kickeado';
		}
	}

	if ($pol['estado'] == 'expulsado') {  session_unset(); session_destroy(); }
}


// Forzado SSL
if (false AND !$_SERVER['HTTPS'] AND isset($pol['nick'])) { redirect('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); }


$vp['lang'] = $pol['config']['lang'];
if ((isset($vp['lang'])) AND ($vp['lang'] != 'es_ES')) {
	// Carga internacionalización
	$locale = $vp['lang'];
	putenv("LC_ALL=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain('messages', '../locale');
	textdomain('messages');
	bind_textdomain_codeset('messages', 'UTF-8');
}

$txt_nav = array(); 
$txt_tab = array();
unset($fecha_init, $fecha_last);
?>