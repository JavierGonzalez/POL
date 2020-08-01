<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 





include('config.php'); // config raiz


$date = date('Y-m-d H:i:s');
$IP = direccion_IP('longip'); // obtiene la IP en formato num�rico (longip)



// Prevenci�n de inyecciones varias
foreach (array('GET', 'POST', 'REQUEST', 'COOKIE') AS $_) {
	foreach ((array)${'_'.$_} AS $key=>$value) {
		$value = str_replace(
			array("\r\n",   "\n",     '\'',    '"',     '\\'   ), 
			array('<br />', '<br />', '&#39;', '&#34;', '&#92;'),
		$value);
		${'_'.$_}[$key] = mysqli_real_escape_string($link,$value); 
	}
}


if (!isset($_SESSION)) { session_start(); } // inicia sesion PHP


// nucleo del sistema de usuarios, comienza la verificaci�n
if (isset($_COOKIE['teorizauser'])) {
	$result = mysql_query_old("SELECT ID, pass, lang, nick, estado, pols, pais, email, fecha_registro, rechazo_last, cargo, cargos, examenes, nivel, dnie FROM users WHERE nick = '".$_COOKIE['teorizauser']."' LIMIT 1", $link);
	while ($r = mysqli_fetch_array($result)) { 
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
	// Carga internacionalizaci�n
	$locale = $vp['lang'];
	putenv("LC_ALL=$locale");
	setlocale(LC_ALL, $locale);
	bindtextdomain('messages', '/locale');
	textdomain('messages');
	bind_textdomain_codeset('messages', 'UTF-8');
}


?>