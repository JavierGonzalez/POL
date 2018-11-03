<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

// PROCESO ejecutado cada 10 minutos.

$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';
$_SERVER['HTTP_HOST'] = '15m.virtualpol.com';
include($root_dir.'config.php');
include($root_dir.'source/inc-functions-accion.php');


foreach ($vp['paises'] AS $pais) {
	
}


$result = sql("SELECT post_ID FROM api_posts WHERE estado = 'cron' AND time_cron <= '".$date."' LIMIT 1");
while($r = r($result)) {
	api_facebook('publicar', $r['post_ID'], true);
}



mysql_close($link);
?>