<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


if (is_numeric($_GET[1])) { 
	paginacion('eventos', '/log/', null, $_GET[1], null, '500');
} else { 
	paginacion('eventos', '/log/'.$_GET[1].'/'.$_GET[2].'/', null, $_GET[3], null, '500');
}

echo '<br />'.$p_paginas.'

<table class="rich">
<tr>
<th colspan="2">'._('Fecha').'</th>
<th>'._('Quien').'</th>
<th>'._('Acción').'</th>
</tr>';

$result = mysql_query_old("SELECT *
FROM log 
WHERE pais = '".PAIS."'".($_GET[1]=='nick'?" AND nick = '".$_GET[2]."'":"")."
ORDER BY time DESC LIMIT ".mysqli_real_escape_string($link,$p_limit), $link);
while($r = mysqli_fetch_array($result)){
	echo '<tr>
<td align="right">'.timer($r['time']).'</td><td class="gris">'.substr($r['time'], 11, 5).'</td>
<td>'.crear_link($r['nick']).'</td><td>'.$r['accion'].'</td>
</tr>'."\n";
}
echo '</table>';


//THEME
$txt_title = _('Log de eventos');
$txt_nav = array(_('Log'));
$txt_menu = 'info';

?>