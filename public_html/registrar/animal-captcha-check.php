<?php 
/*
### ANIMAL CAPTCHA 1.3
Author: GONZO (Javier Gonzalez Gonzalez) gonzomail@gmail.com
url: http://gonzo.teoriza.com/animal-captcha
Blogs Teoriza (www.Teoriza.com)
2009/10/03
###
*/

function animal_captcha_check($try) {
	if (!isset($_SESSION)) { session_start(); }
	$try = trim(strip_tags($try));
	$try = ereg_replace("[באגדְֱֲֳ]", "a", $try);
	$try = ereg_replace("[יטךָֹÊ]", "e", $try);
	$try = ereg_replace("[םלמּֽ־]", "i", $try);
	$try = ereg_replace("[׃ׂװױףעפץ]", "o", $try);
	$try = ereg_replace("[ÚÙÛתשû]", "u", $try);
	$try = ereg_replace("[חַ]", "c", $try);
	$try = ereg_replace("[סׁ]", "n", $try);
	$delete = array('²', '¡', '÷', '×', '“', '”', '„', '"', '\'', '.', ',', '_', ':',';','.', '´','!','¿','?','[',']','{','}','(',')','/','%','&','$','@');
	$try = str_replace(" ", "-", str_replace($delete, "", $try));
	$try = utf8_encode(strtolower($try));
	$animals = explode('-', $_SESSION['animalcaptcha']);
	if (in_array($try, $animals)) { return true; }  //captcha is OK (true)
	else { return false; } //captcha ERROR (false)
}
?>