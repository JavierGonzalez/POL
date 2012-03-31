<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';




foreach (array('15M', 'Hispania', 'RSSV') AS $PAIS) {
/*
	$result = mysql_query("SELECT item_ID, COUNT(*) AS votos_num FROM votos WHERE pais = '".$PAIS."' AND tipo = 'msg' GROUP BY item_ID", $link);
	while($r = mysql_fetch_array($result)){ 
		mysql_query("UPDATE ".strtolower($PAIS)."_foros_msg SET votos_num = '".$r['votos_num']."' WHERE ID = '".$r['item_ID']."' LIMIT 1", $link);
	}

	$result = mysql_query("SELECT item_ID, COUNT(*) AS votos_num FROM votos WHERE pais = '".$PAIS."' AND tipo = 'hilos' GROUP BY item_ID", $link);
	while($r = mysql_fetch_array($result)){ 
		mysql_query("UPDATE ".strtolower($PAIS)."_foros_hilos SET votos_num = '".$r['votos_num']."' WHERE ID = '".$r['item_ID']."' LIMIT 1", $link);
	}
*/
}


$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>