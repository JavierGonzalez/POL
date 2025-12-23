<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$result = mysql_query_old("SELECT url,
(SELECT url FROM ".SQL."foros WHERE ID = ".SQL."foros_hilos.sub_ID LIMIT 1) AS subforo
FROM ".SQL."foros_hilos
WHERE ID = '".$_GET[1]."'
LIMIT 1", $link);

while($r = mysqli_fetch_array($result)) {
    redirect('/foro/'.$r['subforo'].'/'.$r['url']);
}