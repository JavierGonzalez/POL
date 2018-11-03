<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';



$anterior = microtime(true);

// METODO NUEVO
while($r=$db->sql("SELECT * FROM votacion_votos")){
	$guardar = $r['voto'];
}

$txt .= 'Nuevo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO ANTIGUO
$result = mysql_query("SELECT * FROM votacion_votos", $link);
while($r = mysql_fetch_array($result)) {
	$guardar = $r['voto'];
}


$txt .= 'Antiguo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO NUEVO
while($r=$db->sql("SELECT * FROM votacion_votos")){
	$guardar = $r['voto'];
}

$txt .= 'Nuevo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO ANTIGUO
$result = mysql_query("SELECT * FROM votacion_votos", $link);
while($r = mysql_fetch_array($result)) {
	$guardar = $r['voto'];
}


$txt .= 'Antiguo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO NUEVO
while($r=$db->sql("SELECT * FROM votacion_votos")){
	$guardar = $r['voto'];
}

$txt .= 'Nuevo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);


// METODO ANTIGUO
$result = mysql_query("SELECT * FROM votacion_votos", $link);
while($r = mysql_fetch_array($result)) {
	$guardar = $r['voto'];
}


$txt .= 'Antiguo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO NUEVO
while($r=$db->sql("SELECT * FROM votacion_votos")){
	$guardar = $r['voto'];
}

$txt .= 'Nuevo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>