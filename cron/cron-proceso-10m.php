<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



// PROCESO ejecutado cada 10 minutos.



$result = sql_old("SELECT post_ID FROM api_posts WHERE estado = 'cron' AND time_cron <= '".$date."' LIMIT 1");
while($r = r($result)) {
	api_facebook('publicar', $r['post_ID'], true);
}
