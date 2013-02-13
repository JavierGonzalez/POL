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
if ($pol['user_ID'] != 1) { redirect('http://www.virtualpol.com'); }
function crono($new='') { global $crono; $the_ms = num((microtime(true)-$crono)*1000); $crono = microtime(true); return '<h3>'.$the_ms.'ms '.$new.'</h3></hr>'; }
$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }
$txt .= ' ';
/***************************************************************************/


function prevent_XSS_and_SQL_inyection() {
	global $_GET, $_POST, $_REQUEST, $_COOKIE;
	
	$var = '_POST';
	${$var}['test'] = 'FUNCIONA!';
	print_r($_POST);
	
	/*
	foreach (array('GET', 'POST', 'REQUEST', 'COOKIE') AS $_) {
		foreach (${'_'.$_} AS $key=>$value) {
			if (get_magic_quotes_gpc()) { $value = stripslashes($value); }
			$value = str_replace(
				array("\r\n",   "\n",     '\'',    '"',     '\\'   ), 
				array('<br />', '<br />', '&#39;', '&#34;', '&#92;'),
				$value);
			${'_'.$_}[$key] = mysql_real_escape_string($value); 
		}
	}
	*/
}


$_POST['test'] = "'hola'\n\n\n";

$txt .= $_POST['test'].' (antes)<br />';

prevent_XSS_and_SQL_inyection();

$txt .= $_POST['test'].' (despues)<br />';





//**************************************************************************/
$txt .= mysql_error();
$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>