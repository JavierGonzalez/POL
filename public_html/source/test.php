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




$result = sql("SELECT ID, host FROM users_con");
while ($r = r($result)) { 
	$host = $r['host'];
	if (!is_numeric(substr($host, -1, 1))) {
		$hoste = explode('.', $host);
		$ISP = ucfirst($hoste[count($hoste)-(in_array($hoste[count($hoste)-2], array('com', 'net', 'org'))?3:2)]).(!in_array($hoste[count($hoste)-1], array('com', 'net'))?' '.strtoupper($hoste[count($hoste)-1]):'');
		if ((stristr($host, 'static')) OR (stristr($host, 'client'))) { $ISP .= ' (static)'; }
		elseif (stristr($host, 'dyn')) { $ISP .= ' (dynamic)'; }
		elseif ((stristr($host, 'proxy')) OR (stristr($host, 'cache'))) { $ISP .= ' (proxy)'; }
		if ((stristr($host, 'vpn')) OR (stristr($host, 'vps'))) { $ISP = 'Ocultado (VPN)'; } 
		if ((stristr($host, 'tor')) OR (stristr($host, 'anon'))) { $ISP = 'Ocultado (TOR)'; }
		$ISP = "'".$ISP."'";
	} else { $ISP = "NULL"; }
	sql("UPDATE users_con SET ISP = ".$ISP." WHERE ID = '".$r['ID']."' LIMIT 1");
}






$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>