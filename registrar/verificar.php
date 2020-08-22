<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$result = sql_old("SELECT ID, nick, pass, pais FROM users WHERE estado = 'validar' AND nick = '".$_GET['nick']."' AND api_pass = '".$_GET['code']."' LIMIT 1");
while ($r = r($result)) { 

    notificacion($r['ID'], _('Bienvenido!'), '/doc/bienvenida');
    notificacion($r['ID'], _('Sitúate en mapa de ciudadanos!'), '/geolocalizacion');

    if ($r['pais'] == 'ninguno') {
        sql_old("UPDATE users SET estado = 'turista' WHERE ID = '".$r['ID']."' LIMIT 1");
        redirect('/registrar/login/login?user='.$r['nick'].'&pass_md5='.$r['pass'].'&url_http=/registrar');
    } else {
        sql_old("UPDATE users SET estado = 'ciudadano' WHERE ID = '".$r['ID']."' LIMIT 1");

        
        $result2 = sql_old("SELECT COUNT(*) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".$r['pais']."'");
        while ($r2 = r($result2)) { $ciudadanos_num = $r2['num']; }

        evento_chat('<b>[#] '._('Nuevo ciudadano').'</b> '._('de').' <b>'.$r['pais'].'</b> <span style="color:grey;">(<b>'.num($ciudadanos_num).'</b> '._('ciudadanos').', <b><a href="http://'.strtolower($r['pais']).'.'.DOMAIN.'/perfil/'.$r['nick'].'" class="nick">'.$r['nick'].'</a></b>)</span>', 0, 0, false, 'e', $r['pais'], $r['nick']);

        unset($_SESSION);
        session_unset(); session_destroy();
        
        redirect('/registrar/login/login?user='.$r['nick'].'&pass_md5='.$r['pass'].'&url_http=/');
    }
}