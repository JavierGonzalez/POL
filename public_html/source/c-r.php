<?php 
include('inc-login.php');

if (($_GET['a']) AND (!$pol['user_ID'])) {

	$result = mysql_query("SELECT ID, pais FROM users WHERE nick = '".$_GET['a']."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){ 
		mysql_query("INSERT INTO referencias 
(user_ID, IP, time, referer, pagado, new_user_ID) 
VALUES ('" . $r['ID'] . "', '" . $IP . "', '" . $date . "', '" . $_SERVER['HTTP_REFERER'] . "', '0', '0')", $link);
		$user_plataforma = $r['pais'];
	}

}
redirect('http://www.'.DOMAIN.'/registrar/'.($user_plataforma?'?p='.$user_plataforma:''));
?>