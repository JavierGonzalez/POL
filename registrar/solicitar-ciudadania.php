<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




// tiene kick?
$result = sql_old("SELECT ID FROM expulsiones WHERE estado = 'activo' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1");
while ($r = r($result)) { $tiene_kick = true; }

$result = sql_old("SELECT pais FROM users WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1");
while ($r = r($result)) { $user_pais = $r['pais']; }

$pais_existe = false;
$result = sql_old("SELECT pais FROM config WHERE pais = '".$_POST['pais']."' AND dato = 'PAIS' LIMIT 1");
while ($r = r($result)) { $pais_existe = $r['pais']; }

if (($pol['user_ID']) AND ($tiene_kick != true) AND ($user_pais == 'ninguno') AND ($pol['estado'] == 'turista') AND ($pais_existe != false)) {
    sql_old("UPDATE users SET estado = 'ciudadano', rechazo_last = now(), pais = '".$pais_existe."' WHERE estado = 'turista' AND pais = 'ninguno' AND ID = '".$pol['user_ID']."' LIMIT 1");

    $result2 = sql_old("SELECT COUNT(*) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".$_POST['pais']."'");
    while ($r2 = r($result2)) { $ciudadanos_num = $r2['num']; }

    evento_chat('<b>[#] '._('Nuevo ciudadano').'</b> '._('de').' <b>'.$_POST['pais'].'</b> <span style="color:grey;">(<b>'.num($ciudadanos_num).'</b> '._('ciudadanos').', <b><a href="/perfil/'.$pol['nick'].'" class="nick">'.$pol['nick'].'</a></b>)</span>', 0, 0, false, 'e', $_POST['pais'], $r['nick']);

    unset($_SESSION);
    session_unset(); session_destroy();

    redirect('/');

} else { 
    redirect('/registrar'); 
}