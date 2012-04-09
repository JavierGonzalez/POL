<?php
//Abstraemos la aplicacion del tipo de base de datos para dar soporte a multiples bases de datos en un futuro.

function sqpol_query($query, $link){
	return mysql_query($query, $link); 
}

function sqpol_fetch_array($result){
	return mysql_fetch_array($result);
}


?>
