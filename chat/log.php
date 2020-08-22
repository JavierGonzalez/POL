<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$result = sql("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET[1]."' LIMIT 1");
while ($r = r($result)) { 

    $txt_title = _('Chat').': '.$r['titulo'].' | log';
    $txt_nav = array('/chat/list'=>_('Chats'), '/chats/'.$r['url']=>$r['titulo'], _('Log'));
    $txt_tab = array('/chat/'.$r['url']=>'Chat', '/chat/log/'.$r['url']=>_('Log'), '/chat/estadisticas/'.$r['url']=>_('Estadisticas'), '/chat/opciones/'.$r['url']=>_('Opciones'));
    
    if ((nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) AND (isset($pol['user_ID']))) {
        echo '<p>'._('Log de las últimas 3000 lineas').':</p><p class="rich" style="background:#FFFFFF;padding:15px;font-size:14px;text-align:left;">VirtualPol: '._('Plataforma').' '.PAIS.'. '._('Sala').': '.$r['titulo'].'. '._('A fecha de').' '.date('Y-m-d').'.<br />...<br />';
        
        $result2 = sql("SELECT tipo, time, cargo, nick, msg FROM chats_msg WHERE chat_ID = '".$r['chat_ID']."' AND tipo != 'p' ORDER BY msg_ID DESC LIMIT 3000");
        while ($r2 = r($result2)) { 
            $chat_log = '<span'.($r2['tipo']!='m'?' style="color:green;"':'').'>'.date('H:i', strtotime($r2['time'])).' <img src="'.IMG.'cargos/'.$r2['cargo'].'.gif" width="16" height="16" border="0"> <b>'.$r2['nick'].'</b>: '.$r2['msg']."</span><br />\n".$chat_log; 
        }

        echo $chat_log;
        
        echo '<b>'._('FIN').'</b>: '.$date.'</p>';
    
    } else {
        echo '<p style="color:red;">'._('No tienes acceso de lectura').'.</p>';
    }
}