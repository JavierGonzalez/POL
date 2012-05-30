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



$result = sql("SELECT ID, nav FROM users_con WHERE nav_so IS NULL");
while ($r = r($result)) { 
	//$i = get_browser($r['nav'], true);
	//sql("UPDATE users_con SET nav_so = '".$i['platform']." ".$i['parent']."' WHERE ID = '".$r['ID']."' LIMIT 1");
}
$txt .= 'OK';


/*
$txt .= '<table>';
$result = sql("SELECT ID, nick, IP_proxy, host FROM users WHERE IP_proxy != '' ORDER BY IP_proxy ASC");
while ($r = r($result)) { 
	$txt .= '<tr>
<td>'.crear_link($r['nick']).'</td>
<td align="right">'.$r['host'].'</td>
<td align="right">'.(filter_var($r['IP_proxy'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)?'OK':'<b>ERROR</b>').'</td>
<td>'.$r['IP_proxy'].'</td>
<td>'.gethostbyaddr($r['IP_proxy']).'</td>
</tr>';

	//sql("UPDATE users SET IP_proxy = '".(filter_var($r['IP_proxy'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)&&substr($r['IP_proxy'], 0, 3)!='127'?$r['IP_proxy']:'')."' WHERE ID = '".$r['ID']."' LIMIT 1");
}
$txt .= '</table>';
*/

// 127.0.0.1

/*
if (filter_var('62.87.94.250', FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
	$txt .= 'OK';
} else { $txt .= 'ERROR'; }
*/



$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>