<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

if (is_numeric($_GET['a'])) { 
	paginacion('eventos', '/log/', null, $_GET['a'], null, '500');
} else { 
	paginacion('eventos', '/log/'.$_GET['a'].'/'.$_GET['b'].'/', null, $_GET['c'], null, '500');
}

$txt .= '<br />'.$p_paginas.'

<table class="rich">
<tr>
<th colspan="2">'._('Fecha').'</th>
<th>'._('Quien').'</th>
<th>'._('Acción').'</th>
</tr>';

$result = mysql_query("SELECT *
FROM log 
WHERE pais = '".PAIS."'".($_GET['a']=='nick'?" AND nick = '".$_GET['b']."'":"")."
ORDER BY time DESC LIMIT ".mysql_real_escape_string($p_limit), $link);
while($r = mysql_fetch_array($result)){
	$txt .= '<tr>
<td align="right">'.timer($r['time']).'</td><td class="gris">'.substr($r['time'], 11, 5).'</td>
<td>'.crear_link($r['nick']).'</td><td>'.$r['accion'].'</td>
</tr>'."\n";
}
$txt .= '</table>';


//THEME
$txt_title = _('Log de eventos');
$txt_nav = array(_('Log'));
$txt_menu = 'info';
include('theme.php');
?>