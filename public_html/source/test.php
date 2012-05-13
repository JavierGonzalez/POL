<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
function crono($new='') {
	 global $crono;
	 $the_ms = num((microtime(true)-$crono)*1000);
	 $crono = microtime(true);
	 return '<h3>'.$the_ms.'ms '.$new.'</h3></hr>';
}

	$txt .= 'asd';


		if ($_FILES['nuevo_tapiz']['name']) {
			$nom_file = RAIZ.'/img/bg/tapiz-extra-'.strtolower(str_replace('_','-', str_replace(' ','-', substr($_FILES['nuevo_tapiz']['name'], 0, 8)))).'_'.PAIS.'.jpg';
			if (str_replace('image/', '', $_FILES['nuevo_tapiz']['type']) == 'jpeg') {
				move_uploaded_file($_FILES['nuevo_tapiz']['tmp_name'], $nom_file);
			}
			if (file_exists($nom_file)) {
				imageCompression($nom_file, null, $nom_file, 'jpeg', 1440, 100);
			}
		}


$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>