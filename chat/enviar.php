<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$maxsim['output'] = 'text';




$date = date('Y-m-d H:i:s');
$chat_ID = $_POST['chat_ID'];

// EXPULSADO?
$result = sql_old("SELECT HIGH_PRIORITY ID FROM expulsiones WHERE estado = 'expulsado' AND user_ID = '".$_SESSION['pol']['user_ID']."' LIMIT 1");
while($r = r($result)){ 
    $expulsado = true;
    session_destroy();
}

// KICKEADO?
$result = sql_old("SELECT HIGH_PRIORITY expire FROM kicks 
WHERE pais = '".PAIS."' AND 
estado = 'activo' AND 
(user_ID = '".$_SESSION['pol']['user_ID']."' OR 
(IP NOT IN ('0', '') AND IP = inet_aton('".e($_SERVER['REMOTE_ADDR'])."'))) 
LIMIT 1");
while($r = r($result)){ 
    if ($r['expire'] < $date) { // QUITAR KICK
        sql_old("UPDATE HIGH_PRIORITY kicks SET estado = 'inactivo' WHERE pais = '".PAIS."' AND estado = 'activo' AND expire < '".$date."'"); 
    } else { $expulsado = true; }
}

// CHECK MSG
$msg_len = strlen($_POST['msg']);
if (($msg_len > 0) AND ($msg_len < 400) AND (!isset($expulsado)) AND ((acceso_check($chat_ID, 'escribir')) OR (($_SESSION['pol']['pais'] != PAIS) AND (acceso_check($chat_ID, 'escribir_ex'))))) {
    
    if ((!isset($_SESSION['pol']['nick'])) AND (substr($_POST['anonimo'], 0, 1) == '-') AND (strlen($_POST['anonimo']) >= 3) AND (strlen($_POST['anonimo']) <= 15) AND (!stristr($_POST['anonimo'], '__'))) { 
        $result = sql_old("SELECT nick FROM users WHERE nick='".substr($_POST['anonimo'], 1)."'");
        if (r($result)) { 
            $borrar_msg = true;
            echo 'n 0 ---- - <b style="color:#FF0000;">Nick inv&aacute;lido por estar registrado.</b>'. "\n"; 
        }
        else {
            $_SESSION['pol']['nick'] = $_POST['anonimo'];
            $_SESSION['pol']['estado'] = 'anonimo';
        }
    }

    // limpia MSG
    $msg = $_POST['msg'];
    if (isset($borrar_msg)) { $msg = ''; }

    $msg = str_replace(array("\n", "\r", "ส็็็็็็็็", "ส็็็็็็็็็็็็็็็็็็็็็็็็็"), "", str_replace("'", "''", trim($msg)));
    $msg = strip_tags($msg);

    $target_ID = 0;
    $tipo = 'c';

    if (substr($msg, 0, 1) == '/') {
        // ES COMANDO
        $msg_array = explode(" ", $msg);
        $msg_key = substr($msg_array[0], 1);
        $msg_rest = substr($msg, (strlen($msg_key) + 2));
        $user_ID_priv = '0';

        switch ($msg_key) {

            case 'dado':
                $param = $msg_array[1]; // parametro despues de /dado
                if ((is_numeric($param)) AND ($param > 1)) {
                    $result_rand = mt_rand(1, $param);
                    $result_type = ' de '.$param.' n&uacute;meros';
                } elseif ($param == '%') {
                    $result_rand = mt_rand(00, 99).'%';
                    $result_type = ' de porcentaje';
                } else { // dado normal
                    $result_rand = mt_rand(1, 6);
                    $result_type = '';
                }
                $elmsg = '<b>[$]</b> <em>' . $_SESSION['pol']['nick'] . '</em> tira el <b>dado'.$result_type.': <span style="font-size:16px;">'.$result_rand.'</span></b>';
                break;

            case 'calc': 
                if (preg_match("/[0-9\+-\/\*\(\)\.]{1,100}/", strtolower($msg_rest))) { 
                    @eval("\$result=" . $msg_rest . ";");
                    if (substr($result, 0, 8) == 'Resource') { $result = 'calc error'; }
                    $elmsg = '<b>[$] ' . $_SESSION['pol']['nick'] . '</b> calc: <b style="color:blue">' . $msg_rest . '</b> <b style="color:grey;">=</b> <b style="color:red">' . $result . '</b>';
                }
                break;

            case 'aleatorio': $elmsg = '<b>[$] ' . $_SESSION['pol']['nick'] . '</b> aleatorio: <b>' . mt_rand(00000,99999) . '</b>'; break;
            
            case 'ciudadano': 
                if (isset($_SESSION['pol']['user_ID'])) {
                    $elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . '</b> te anima a unirte a la comunidad: <a href="/r/'.strtolower($_SESSION['pol']['nick']).'/" target="_blank"><b>Crear Usuario</b></a>'; 
                }
                break;
            
            case 'trabaja': 
                $elmsg = 'la econom&iacute;a te necesita! <button class="small pill">Trabaja</button> :troll:'; 
                $tipo = 'm';
                break;

            case 'dnie':
            case 'autentificado': 
                if (nucleo_acceso('autentificados')) {
                    $elmsg = '<b>[#] ' . $_SESSION['pol']['nick'] . ' es autentico.</b> (<a href="'.SSL_URL.'dnie.php">Autentificado</a>)'; 
                }
                break;

            case 'me': $elmsg = '<b style="margin-left:20px;">' . $_SESSION['pol']['nick'] . '</b> ' . $msg_rest; break;
            case 'exit': $elmsg = '<span style="margin-left:20px;color:#66004C;"><b>' . $_SESSION['pol']['nick'] . '</b> se marcha, ¡hasta pronto!</span>'; break;
            case 'sombras': $elmsg = '<span style="margin-left:20px;color:#585858;"><b>' . $_SESSION['pol']['nick'] . '</b> se retira a las sombras...</span>'; break;
            case 'ayuda': 
                $tipo = 'm';
                $elmsg = 'ofrece ayuda'.($msg_rest?' a '.$msg_rest:'').': <a href="/hacer" target="_blank">¿<b>Qué hacer</b>?</a> - <a href/video" target="_blank"><b>Bienvenida (video)</b></a> - <a href="http://www.'.DOMAIN.'/manual" target="_blank">Documentación</a>.</a>';
                break;

            case 'moderador': 
            case 'policia': if (nucleo_acceso('cargo', '13 12'))  { $elmsg = '<span style="color:blue;">' . $msg_rest . ' <b>(Aviso Oficial)</b></span>'; $tipo = 'm'; } break;

            case 'msg':
                if (isset($_SESSION['pol']['user_ID'])) {
                    $nick_receptor = trim($msg_array[1]);
                    $result = sql_old("SELECT HIGH_PRIORITY ID, nick FROM users WHERE nick = '" . $nick_receptor . "' LIMIT 1");
                    while($r = r($result)){ 
                        $elmsg = substr($msg_rest, (strlen($r['nick'])));
                        $target_ID = $r['ID'];
                        $tipo = 'p';
                        $elnick = $_SESSION['pol']['nick'].'&rarr;'.$r['nick'];
                    }
                }
                break;
        }
        unset($msg); if (isset($elmsg)) { $msg = $elmsg; }
        
    } else { $tipo = 'm'; }

    // insert MSG
    if (isset($msg)) {
        if (!isset($elnick)) { $elnick = $_SESSION['pol']['nick']; }
        if ($_SESSION['pol']['estado'] == 'anonimo') { $sql_ip = 'inet_aton("'.e($_SERVER['REMOTE_ADDR']).'")'; } else { $sql_ip = 'NULL'; }

        $elcargo = $_SESSION['pol']['cargo'];
        if (($_SESSION['pol']['pais'] != PAIS) AND ($_SESSION['pol']['estado'] == 'ciudadano')) { $elcargo = 99; } // Extrangero

        sql_old("INSERT DELAYED INTO chats_msg (chat_ID, nick, msg, cargo, user_ID, tipo, IP) VALUES ('".$chat_ID."', '".$elnick."', '".$msg."', '".$elcargo."', '".$target_ID."', '".$tipo."', ".$sql_ip.")");

        sql_old("
UPDATE users SET fecha_last = '".$date."' WHERE ID = '".$_SESSION['pol']['user_ID']."' LIMIT 1;
UPDATE chats SET stats_msgs = stats_msgs + 1 WHERE chat_ID = '".$chat_ID."' LIMIT 1;
");
    }

    
    if (isset($_POST['n'])) 
        echo chat_refresh($chat_ID, $_POST['n']); 

} else { 
    echo 'n 0 &nbsp; &nbsp; <b style="color:#FF0000;">No tienes permiso de escritura.</b>'."\n"; 
}