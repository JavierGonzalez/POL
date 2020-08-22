<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 

exit;

if (($_GET['x']) AND ($_GET['y']) AND ($_GET['z'])) {
    $result = sql("SELECT ID FROM users WHERE ID = '".$_GET['y']."' AND api_pass = '".$_GET['z']."' LIMIT 1");
    while($r = r($result)) {
        setcookie('trz', $_GET['x'], (time()+(86400*365)), '/', USERCOOKIE);
        sql("UPDATE users_con SET dispositivo = '".$_GET['x']."' WHERE tipo = 'login' AND user_ID = '".$r['ID']."' ORDER BY time DESC LIMIT 1");
    }
}
redirect(base64_decode($_GET['u']));