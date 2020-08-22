<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


if ($_POST['nick'] == $pol['nick']) { 
    evento_log('Eliminación de usuario permanente y voluntaria.');
    sql_old("UPDATE users SET estado = 'expulsado' WHERE ID = '".$pol['user_ID']."' LIMIT 1"); 
}
redirect('/');