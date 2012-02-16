<?php 
include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
$txt .= '<h1>TEST DE DESARROLLO</h1><hr />';


function gen_url2($url) {
	if (mb_detect_encoding($url) != 'UTF-8') { $url = utf8_decode($url); }
	
	$url = trim($url);
	$url = strtr(utf8_decode($url), utf8_decode(' àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'),
utf8_decode('-aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'));
	$url = ereg_replace("[^A-Za-z0-9-]", "", $url);
	$url = substr($url, 0, 90);
	$url = strip_tags($url);
	$url = strtolower($url);
	return $url;
}

$test = 'Holá mundo Ñé';

$txt .= $test.' ('.mb_detect_encoding($test).')<br /> '.gen_url2($test).' ('.mb_detect_encoding(gen_url2($test)).')';




include('theme.php');
?>