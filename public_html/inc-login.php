<?php
define('TIME_START', microtime(true));

include('config.php'); // config raiz


$date = date('Y-m-d H:i:s'); // fija fecha actual $date en formato entendible por MySQL
$IP = direccion_IP('longip'); // obtiene la IP en formato numérico (longip)



// Prevención de inyecciones varias
foreach ($_POST AS $nom => $val) { $_POST[$nom] = escape($val); }
foreach ($_GET  AS $nom => $val) { $_GET[$nom] = escape($val); }
foreach ($_REQUEST AS $nom => $val) { $_REQUEST[$nom] = escape($val); }
foreach ($_COOKIE AS $nom => $val) { $_COOKIE[$nom] = escape($val); }


if (!isset($_SESSION)) { session_start(); } // inicia sesion PHP


// nucleo del sistema de usuarios, comienza la verificación
if (isset($_COOKIE['teorizauser'])) {
	$result = mysql_query("SELECT ID, pass, nick, estado, pols, pais, email, fecha_registro, rechazo_last, cargo, cargos, examenes, nivel, dnie FROM users WHERE nick = '".$_COOKIE['teorizauser']."' LIMIT 1", $link);
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

?>