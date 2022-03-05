<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 

ini_set('memory_limit', '10G');

$result = sql_old("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET[1]."' LIMIT 1");
while ($r = r($result)) { 
    
    if ((nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) AND (isset($pol['user_ID']))) {
        
        $result2 = sql_old("SELECT tipo, time, cargo, nick, msg FROM chats_msg WHERE chat_ID = '".$r['chat_ID']."' AND tipo != 'p' ORDER BY msg_ID DESC LIMIT 1000000");
        while ($r2 = r($result2)) {
            if (!$r2['nick'])
                $r2['nick'] = 'VirtualPol'; 
            
            $json = json_encode(['text' => $r2['nick'].': '.strip_tags($r2['msg'])]);

            if ($json)
                echo $json."\n";
        }
    
    } else {
        echo '<p style="color:red;">'._('No tienes acceso de lectura').'.</p>';
    }
}

exit;