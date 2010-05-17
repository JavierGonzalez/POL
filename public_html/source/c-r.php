<?php 
include('inc-login.php');

/*
pol_referencias (ID, user_ID, IP, time, referer)
*/

if (($_GET['a']) AND (!$pol['user_ID'])) {

	$result = mysql_query("SELECT ID FROM ".SQL_USERS." WHERE nick = '".$_GET['a']."' LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){ 
		mysql_query("INSERT INTO ".SQL_REFERENCIAS." 
(user_ID, IP, time, referer, pagado, new_user_ID) 
VALUES ('" . $row['ID'] . "', '" . $IP . "', '" . $date . "', '" . $_SERVER['HTTP_REFERER'] . "', '0', '0')", $link);
	}

}
if ($link) { mysql_close($link); }
header('HTTP/1.1 301 Moved Permanently');
header('Location: http://www.virtualpol.com/registrar/');
?>