<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$txt_title = _('Solicitar chat');
$txt_nav = array('/chat/list'=>'Chats', _('Solicitar chat'));
$txt_tab = array('/chat/solicitar'=>_('Solicitar chat'));

if (($pol['pais']) AND ($pol['pais'] != PAIS)) { 
    redirect('/chat/'.$_GET[1]); 
}

$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($row = r($result)) { 
    $pol['config'][$row['dato']] = $row['valor']; 
}

echo '<form action="/accion/chat/solicitar" method="post">

<ol>
<li><b>'._('Nombre del chat').':</b><br />
<input type="text" name="nombre" size="20" maxlength="20" /> ('._('No modificable').')
<br /><br /></li>

<li>'.boton(_('Solicitar chat'), 'submit', false, '', $pol['config']['pols_crearchat']).'</li>
</ol>
</form>';