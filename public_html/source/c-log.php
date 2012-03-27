<?php 
include('inc-login.php');

paginacion('eventos', '/log', null, $_GET['a'], null, '500');

$txt .= '<br />'.$p_paginas.'

<table border="0" cellspacing="0" cellpadding="0" class="pol_table">
<tr>
<th>Fecha</th>
<th>Quien</th>
<th>Acción</th>
</tr>';

$result = mysql_query("SELECT *
FROM log 
WHERE pais = '".PAIS."'
ORDER BY time DESC LIMIT ".mysql_real_escape_string($p_limit), $link);
while($r = mysql_fetch_array($result)){
	$fecha = explodear(' ', $r['time'], 1);
	$fecha = explodear(':', $fecha, 0).':'.explodear(':', $fecha, 1);
	$txt .= '<tr><td title="'.$r['time'].'">'.$fecha.'</td><td>'.crear_link($r['nick']).'</td><td>'.$r['accion'].'.</td></tr>'."\n";
}
$txt .= '</table>';


//THEME
$txt_title = 'Log de eventos';
$txt_nav = array('Log');
$txt_menu = 'info';
include('theme.php');
?>