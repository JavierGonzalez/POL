<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


echo '<h1>'._('Instantánea de').' VirtualPol</h1><br />';
$result = mysql_query_old("SELECT ID, nick, pais
FROM users
WHERE estado = 'ciudadano' AND avatar = 'true'
ORDER BY online DESC
LIMIT 300", $link);
while($r = mysqli_fetch_array($result)) { 
    echo '<img src="'.IMG.'a/'.$r['ID'].'.jpg" alt="'.$r['nick'].'" title="'.$r['nick'].'" />'; 
}