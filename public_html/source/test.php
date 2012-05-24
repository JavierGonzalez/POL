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

// load config full
$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }


$result = sql("SELECT user_ID, (SELECT fecha_last FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS fecha_last FROM cargos_users WHERE pais = '".PAIS."' AND cargo = 'false' GROUP BY user_ID");
$txt .= mysql_error();
while ($r = r($result)) { 
	if ((!$r['fecha_last']) OR (strtotime($r['fecha_last']) < (time() - 60*60*24*$pol['config']['examenes_exp']))) {
		sql("DELETE FROM cargos_users WHERE user_ID = '".$r['user_ID']."' AND cargo = 'false'");
	}
}



$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>