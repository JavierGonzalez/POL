<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

define('TIME_START', microtime(true));

include('config.php'); // config raiz


$date = date('Y-m-d H:i:s');
$IP = direccion_IP('longip'); // obtiene la IP en formato numérico (longip)



// Prevención de inyecciones varias
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


if (!isset($_SESSION)) { session_start(); } // inicia sesion PHP


// nucleo del sistema de usuarios, comienza la verificación
if (isset($_COOKIE['teorizauser'])) {
	$result = mysql_query("SELECT ID, pass, lang, nick, estado, pols, pais, email, fecha_registro, rechazo_last, cargo, cargos, examenes, nivel, dnie FROM users WHERE nick = '".$_COOKIE['teorizauser']."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 
		if (md5(CLAVE.$r['pass']) == $_COOKIE['teorizapass']) { // cookie pass OK
			$session_new = true;
			$pol['nick'] = $r['nick'];
			$pol['user_ID'] = $r['ID'];
			$pol['pols'] = $r['pols'];
			$pol['email'] = $r['email'];
			$pol['estado'] = $r['estado'];
			$pol['pais'] = $r['pais'];
			$pol['rechazo_last'] = $r['rechazo_last'];
			$pol['fecha_registro'] = $r['fecha_registro'];

			if (isset($r['lang'])) { $pol['config']['lang'] = $r['lang']; }

			// variables perdurables en la sesion, solo se guarda el nick e ID de usuario
			$_SESSION['pol']['nick'] = $r['nick'];
			$_SESSION['pol']['user_ID'] = $r['ID'];
			$_SESSION['pol']['cargo'] = $r['cargo'];
			$_SESSION['pol']['cargos'] = $r['cargos'];
			$_SESSION['pol']['examenes'] = $r['examenes'];
			$_SESSION['pol']['nivel'] = $r['nivel'];
			$_SESSION['pol']['fecha_registro'] = $r['fecha_registro'];
			$_SESSION['pol']['pais'] = $r['pais'];
			$_SESSION['pol']['estado'] = $r['estado'];
			$_SESSION['pol']['dnie'] = $r['dnie'];
		}
	}  
}



// Forzado SSL
if (false AND !$_SERVER['HTTPS'] AND isset($pol['nick'])) { redirect('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); }


$vp['lang'] = $pol['config']['lang'];
if ((isset($vp['lang'])) AND ($vp['lang'] != 'es_ES')) {
	// Carga internacionalización
	$locale = $vp['lang'];
	putenv("LC_ALL=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain('messages', '/locale');
	textdomain('messages');
	bind_textdomain_codeset('messages', 'UTF-8');
}


?>