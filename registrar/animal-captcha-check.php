<?php 
/*
### ANIMAL CAPTCHA 1.5
Author: GONZO (Javier Gonzalez Gonzalez) gonzomail@gmail.com
url: http://gonzo.teoriza.com/animal-captcha
Blogs Teoriza (www.Teoriza.com)
2010/03/03
###
*/


function animal_captcha_quitar_acentos($text) {
	$text = htmlentities($text, ENT_QUOTES, 'UTF-8');
	$text = strtolower($text);
	$patron = array (
		'/[\.,]+/' => '',

		// Vocales
		'/&agrave;/' => 'a',
		'/&egrave;/' => 'e',
		'/&igrave;/' => 'i',
		'/&ograve;/' => 'o',
		'/&ugrave;/' => 'u',

		'/&aacute;/' => 'a',
		'/&eacute;/' => 'e',
		'/&iacute;/' => 'i',
		'/&oacute;/' => 'o',
		'/&uacute;/' => 'u',

		'/&acirc;/' => 'a',
		'/&ecirc;/' => 'e',
		'/&icirc;/' => 'i',
		'/&ocirc;/' => 'o',
		'/&ucirc;/' => 'u',

		'/&atilde;/' => 'a',
		'/&etilde;/' => 'e',
		'/&itilde;/' => 'i',
		'/&otilde;/' => 'o',
		'/&utilde;/' => 'u',

		'/&auml;/' => 'a',
		'/&euml;/' => 'e',
		'/&iuml;/' => 'i',
		'/&ouml;/' => 'o',
		'/&uuml;/' => 'u',

		'/&auml;/' => 'a',
		'/&euml;/' => 'e',
		'/&iuml;/' => 'i',
		'/&ouml;/' => 'o',
		'/&uuml;/' => 'u',

		// Otras letras y caracteres especiales
		'/&aring;/' => 'a',
		'/&ntilde;/' => 'n',

		// Agregar aqui mas caracteres si es necesario

	);

	$text = preg_replace(array_keys($patron),array_values($patron),$text);
	return $text;
}


function animal_captcha_check($try) {
	if (!isset($_SESSION)) { session_start(); }
	if (isset($_SESSION['animalcaptcha'])) {
		$try = animal_captcha_quitar_acentos($try);
		$try = trim(strip_tags($try));
		$try = ereg_replace("[áàâãÁÀÂÃ]", "a", $try);
		$try = ereg_replace("[éèêÉÈÊ]", "e", $try);
		$try = ereg_replace("[íìîÍÌÎ]", "i", $try);
		$try = ereg_replace("[ÓÒÔÕóòôõ]", "o", $try);
		$try = ereg_replace("[ÚÙÛÜúùûü]", "u", $try);
		$try = ereg_replace("[çÇ]", "c", $try);
		$try = ereg_replace("[ñÑ]", "n", $try);
		$delete = array('²', '¡', 'º', 'ª', '“', '”', '„', '"', '\'', '.', ',', '_', ':',';','.', '´','!','¿','?','[',']','{','}','(',')','/','%','&','$','@');
		$try = str_replace($delete, "", $try);
		$try = utf8_encode(strtolower($try));
		$trys = explode(" ", $try);
		$animals = explode('|', $_SESSION['animalcaptcha']);
		unset($_SESSION['animalcaptcha']);
		$result = true;
		$e = 0;
		foreach($animals AS $one_animal) {	
			$animals_resp = explode('-', $one_animal);
			if (!in_array($trys[$e], $animals_resp)) { $result = false; }
			$e++;
		}
		return $result;
	}
}
?>