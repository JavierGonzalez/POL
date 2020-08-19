<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




if (($_GET[1]) AND (!$pol['user_ID'])) {

	$result = mysql_query_old("SELECT ID, pais FROM users WHERE nick = '".$_GET[1]."' LIMIT 1", $link);
	while($r = mysqli_fetch_array($result)){ 
		mysql_query_old("INSERT INTO referencias 
(user_ID, IP, time, referer, pagado, new_user_ID) 
VALUES ('" . $r['ID'] . "', '" . $IP . "', '" . $date . "', '" . $_SERVER['HTTP_REFERER'] . "', '0', '0')", $link);
		$user_plataforma = $r['pais'];
	}

}

redirect('/registrar');