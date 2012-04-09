<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';

exit;

/*
foreach ($vp['paises'] AS $pais) {

	$result = mysql_query("SELECT * FROM ".strtolower($pais)."_partidos", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("INSERT INTO partidos (pais, ID_presidente, fecha_creacion, siglas, nombre, descripcion, estado, ID_old) 
VALUES ('".$pais."', '".$r['ID_presidente']."', '".$r['fecha_creacion']."', '".$r['siglas']."', '".$r['nombre']."', '".$r['descripcion']."', '".$r['estado']."', '".$r['ID']."')", $link);
	}
}


foreach ($vp['paises'] AS $pais) {

	$result = mysql_query("SELECT *,
(SELECT ID FROM partidos WHERE ID_old = ".strtolower($pais)."_partidos_listas.ID_partido LIMIT 1) AS nuevo_partido_ID
FROM ".strtolower($pais)."_partidos_listas", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("INSERT INTO partidos_listas (pais, ID_partido, user_ID, orden) 
VALUES ('".$pais."', '".$r['nuevo_partido_ID']."', '".$r['user_ID']."', '".$r['orden']."')", $link);
	}
}
*/


$result = mysql_query("SELECT ID, partido_afiliado,
(SELECT ID FROM partidos WHERE ID_old = users.partido_afiliado  LIMIT 1) AS nuevo_partido_ID
FROM users
WHERE estado = 'ciudadano' AND partido_afiliado > 0", $link);
while($r = mysql_fetch_array($result)) {
	mysql_query("UPDATE users SET partido_afiliado = '".$r['nuevo_partido_ID']."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
}









$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>