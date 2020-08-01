<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




if ($pol['user_ID'] != 1) { exit; }
echo '<h1>TEST DE DESARROLLO</h1><hr />';



$anterior = microtime(true);

// METODO NUEVO
while($r=$db->sql("SELECT * FROM votacion_votos")){
	$guardar = $r['voto'];
}

echo 'Nuevo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO ANTIGUO
$result = mysql_query_old("SELECT * FROM votacion_votos", $link);
while($r = mysqli_fetch_array($result)) {
	$guardar = $r['voto'];
}


echo 'Antiguo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO NUEVO
while($r=$db->sql("SELECT * FROM votacion_votos")){
	$guardar = $r['voto'];
}

echo 'Nuevo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO ANTIGUO
$result = mysql_query_old("SELECT * FROM votacion_votos", $link);
while($r = mysqli_fetch_array($result)) {
	$guardar = $r['voto'];
}


echo 'Antiguo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO NUEVO
while($r=$db->sql("SELECT * FROM votacion_votos")){
	$guardar = $r['voto'];
}

echo 'Nuevo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);


// METODO ANTIGUO
$result = mysql_query_old("SELECT * FROM votacion_votos", $link);
while($r = mysqli_fetch_array($result)) {
	$guardar = $r['voto'];
}


echo 'Antiguo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

// METODO NUEVO
while($r=$db->sql("SELECT * FROM votacion_votos")){
	$guardar = $r['voto'];
}

echo 'Nuevo: '.num((microtime(true)-$anterior)*1000).'ms<hr />';
$anterior = microtime(true);

$txt_title = 'Test';
$txt_nav = array('Test');

?>