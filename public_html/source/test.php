<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


exit;
foreach (array('Hispania') AS $pais) {

	$result = mysql_query("SELECT * FROM ".strtolower($pais)."_pujas", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("INSERT INTO pujas (pais, mercado_ID, user_ID, pols, time) 
VALUES ('".$pais."', '".$r['mercado_ID']."', '".$r['user_ID']."', '".$r['pols']."', '".$r['time']."')", $link);
	}
}









$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>