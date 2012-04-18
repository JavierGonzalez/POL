<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

paginacion('eventos', '/log', null, $_GET['a'], null, '500');

$txt .= '<br />'.$p_paginas.'

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<th colspan="2">Fecha</th>
<th>Quien</th>
<th>Acción</th>
</tr>';

$result = mysql_query("SELECT *
FROM log 
WHERE pais = '".PAIS."'
ORDER BY time DESC LIMIT ".mysql_real_escape_string($p_limit), $link);
while($r = mysql_fetch_array($result)){
	$txt .= '<tr>
<td align="right">'.timer($r['time']).'</td><td>'.substr($r['time'], 11, 5).'</td>
<td>'.crear_link($r['nick']).'</td><td>'.$r['accion'].'</td>
</tr>'."\n";
}
$txt .= '</table>';


//THEME
$txt_title = 'Log de eventos';
$txt_nav = array('Log');
$txt_menu = 'info';
include('theme.php');
?>