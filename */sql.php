<?php

function conectar() {
    $error_msg = '<h1>MySQL Error</h1><p>Lo siento, la base de datos no funciona temporalmente.</p>';
	if (!($l=@mysqli_connect('localhost', MYSQL_USER, MYSQL_PASS, MYSQL_DB))) { echo $error_msg; exit; }
	//if (!@mysql_select_db($mysql_db, $l)) { echo $error_msg; exit; }
	//mysql_query_old("SET NAMES 'utf8'"); // ACTIVAR AL MIGRAR BD A UTF8 
	return $l;
}



function mysql_query_old($query, $link2=false) {
	global $link;
	return mysqli_query(($link2?$link2:$link), $query);
}