<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$result = sql_old("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET[1]."' LIMIT 1");
while ($r = r($result)) { 

    $txt_title = _('Chat').': '.$r['titulo'].' | log';
    $txt_nav = array('/chat/list'=>_('Chats'), '/chats/'.$r['url']=>$r['titulo'], _('Estadisticas'));
    $txt_tab = array('/chat/'.$r['url']=>'Chat', '/chat/log/'.$r['url']=>_('Log'), '/chat/estadisticas/'.$r['url']=>_('Estadisticas'), '/chat/opciones/'.$r['url']=>_('Opciones'));
    
    if ((nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) AND (isset($pol['user_ID']))) {
        
        $msg_num = array(); $char_num = array();
        $result2 = sql_old("SELECT nick, msg FROM chats_msg WHERE chat_ID = '".$r['chat_ID']."' AND tipo = 'm'");
        while ($r2 = r($result2)) { 
            $msg_num[$r2['nick']]++;
            $char_num[$r2['nick']] += strlen($r2['msg']);
        }
        
        echo '<fieldset>Estadisticas de las últimas 24h de esta sala de chat.</fieldset>
<table>
<tr>
<th></th>
<th>Ciudadano</th>
<th>Caracteres</th>
<th>Lineas</th>
<th>C/L</th>
</tr>';
        arsort($char_num);
        foreach ($char_num AS $nick => $num) {
            echo '<tr>
<td align="right">'.++$n.'.</td>
<td class="rich"><b>'.crear_link($nick).'</b></td>
<td align="right"><b>'.num($num).'</b></td>
<td align="right">'.num($msg_num[$nick]).'</td>
<td align="right">'.num($num/$msg_num[$nick],1).'</td>
</tr>';
        }
        echo '</table>';
    } else {
        echo '<p style="color:red;">'._('No tienes acceso de lectura').'.</p>';
    }
}
