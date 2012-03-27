<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


/*** MIGRACION A NUEVO SISTEMA DE CARGO. ***/



foreach ($vp['paises'] AS $pais) {
	$txt .= $pais.'<br />';
	$result = mysql_query("SELECT * FROM ".strtolower($pais)."_config", $link);
	while($r = mysql_fetch_array($result)){
		//mysql_query("INSERT INTO config (pais, dato, valor, autoload) VALUES ('".$pais."', '".$r['dato']."', '".$r['valor']."', '".$r['autoload']."')", $link);
	}
}




$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>