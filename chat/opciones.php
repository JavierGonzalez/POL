<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$result = sql_old("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET[1]."' LIMIT 1");
while ($r = r($result)) { 

    $txt_title = _('Chat').': '.$r['titulo'].' | '._('Opciones');
    $txt_nav = array('/chat/list'=>_('Chats'), '/chat/'.$r['url']=>$r['titulo'], _('Opciones'));
    $txt_tab = array('/chat/'.$r['url']=>_('Chat'), '/chat/log/'.$r['url'] =>_('Log'), '/chat/opciones/'.$r['url']=>_('Opciones'));

    foreach (nucleo_acceso('print') AS $at => $at_var) { 
        $txt_li['leer'] .= '<input type="radio" name="acceso_leer" value="'.$at.'"'.($at==$r['acceso_leer']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_leer_var\').val(\''.$at_var.'\');" /> '._(ucfirst(str_replace("_", " ", $at))).'<br />';
    }
    foreach (nucleo_acceso('print') AS $at => $at_var) { 
        $txt_li['escribir'] .= '<input type="radio" name="acceso_escribir" value="'.$at.'"'.($at==$r['acceso_escribir']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_escribir_var\').val(\''.$at_var.'\');"'.($at=='anonimos'?' disabled="disabled"':'').' /> '._(ucfirst(str_replace("_", " ", $at))).'<br />';
    }

    foreach (nucleo_acceso('print') AS $at => $at_var) { 
        $txt_li['escribir_ex'] .= '<input type="radio" name="acceso_escribir_ex" value="'.$at.'"'.($at==$r['acceso_escribir_ex']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_escribir_ex_var\').val(\''.$at_var.'\');"'.($at=='anonimos'?' disabled="disabled"':'').' /> '._(ucfirst(str_replace("_", " ", $at))).'<br />';
    }

    echo '
<form action="/accion/chat/editar" method="post">
<input type="hidden" name="chat_ID" value="'.$r['chat_ID'].'" />
<input type="hidden" name="chat_nom" value="'.$_GET[1].'" />

<fieldset><legend>'._('Opciones de acceso').'</legend>
<table border="0" cellpadding="9">
<tr>
<td valign="top"><b>'._('Acceso leer').':</b><br />
'.$txt_li['leer'].' <input type="text" name="acceso_cfg_leer" size="25" maxlength="900" autocomplete="off" id="acceso_cfg_leer_var" value="'.$r['acceso_cfg_leer'].'" /></td>

<td valign="top"><b>'._('Acceso escribir').':</b><br />
'.$txt_li['escribir'].' <input type="text" name="acceso_cfg_escribir" size="25" maxlength="900" autocomplete="off" id="acceso_cfg_escribir_var" value="'.$r['acceso_cfg_escribir'].'" /></td>

<td valign="top"><b>'._('Acceso escribir').' '._('extranjeros').':</b><br />
'.$txt_li['escribir_ex'].' <input type="text" name="acceso_cfg_escribir_ex" size="25" maxlength="900" autocomplete="off" id="acceso_cfg_escribir_ex_var" value="'.$r['acceso_cfg_escribir_ex'].'" /></td>

</tr>
<tr><td colspan="3" align="center">

'.boton(_('Guardar'), (nucleo_acceso('privado', $r['admin'])||nucleo_acceso($vp['acceso']['control_gobierno'])?'submit':''), false, 'large blue').'

</td></tr>
</table>
</fieldset>

</form>';

    if ($r['user_ID'] != 0) {
        echo '<form action="/accion/chat/cambiarfundador" method="post">
<input type="hidden" name="chat_ID" value="'.$r['chat_ID'].'" />

<fieldset><legend>'._('Administradores').'</legend>
<p><input type="text" name="admin" size="40" maxlength="900" value="'.$r['admin'].'" /> <input type="submit" value="'._('Cambiar administradores').'"'.($r['user_ID']==$pol['user_ID'] || nucleo_acceso('privado', $r['admin']) || nucleo_acceso($vp['acceso']['control_gobierno'])?'':' disabled="disabled"').' /></p>
</fieldset>
</form>';
    }

    if (($r['estado'] == 'activo') AND ($r['user_ID'] != 0) AND (($r['user_ID'] == $pol['user_ID']) OR (nucleo_acceso($vp['acceso']['control_gobierno'])))) { 
        echo boton(_('Bloquear'), '/accion/chat/bloquear?chat_ID='.$r['chat_ID'], '¿Seguro que quieres BLOQUEAR este chat?');
    }

    echo '<!--<p>'._('Código HTML').': <input type="text" style="color:grey;font-weight:normal;" value="&lt;iframe width=&quot;730&quot; height=&quot;480&quot; scrolling=&quot;no&quot; frameborder=&quot;0&quot; transparency=&quot;transparency&quot; src=&quot;http://'.strtolower($r['pais']).'.'.DOMAIN.'/chats/'.$r['url'].'/e/&quot;&gt;&lt;p&gt;&lt;a href=&quot;http://'.strtolower($r['pais']).'.'.DOMAIN.'/chats/'.$r['url'].'/&quot;&gt;&lt;b&gt;Entra al chat&lt;/b&gt;&lt;/a&gt;&lt;/p&gt;&lt;/iframe&gt;" size="70" /></p>-->';
}
