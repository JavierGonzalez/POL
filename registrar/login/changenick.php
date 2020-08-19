<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$nick_new = trim($_POST['newnick']);

$pre_login = true;

if (isset($pol['user_ID'])) {

    function nick_check($string) {
        $eregi = eregi_replace("([A-Z0-9_]+)","", $string);
        if (empty($eregi)) { return true; } else { return false; }
    }

    $dentro_del_margen = false;
    $result = sql("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND nickchange_last < '".date('Y-m-d 20:00:00', time() - (86400*365))."' LIMIT 1");
    while ($r = r($result)) { $dentro_del_margen = true; }
    
    $nick_existe = false;
    $result = sql("SELECT ID FROM users WHERE nick = '".$nick_new."' LIMIT 1");
    while ($r = r($result)) { $nick_existe = true; }


    if ((nick_check($nick_new)) AND (strlen($nick_new) >= 3) AND (strlen($nick_new) <= 12) AND ($dentro_del_margen) AND (!$nick_existe)) {

        // EJECUTAR CAMBIO DE NICK
        sql("UPDATE users SET nick = '".$nick_new."', nickchange_last = now() WHERE ID = '".$pol['user_ID']."' LIMIT 1");
        
        evento_chat('<b>[#] El ciudadano '.$pol['nick'].'</b> se ha cambiado de nombre a <b>'.crear_link($nick_new).'</b>.', 0, 0, true, 'e', $pol['pais']);
        
        
        unset($_SESSION); 
        session_destroy();

        setcookie('teorizauser', '', time()-3600, '/', USERCOOKIE);
        setcookie('teorizapass', '', time()-3600, '/', USERCOOKIE);
    }
}

redirect('/registrar/login/panel');