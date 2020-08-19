<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$result = sql("SELECT ID, nick FROM users WHERE ID = '".$_GET['user_ID']."' AND api_pass = '".$_GET['check']."' AND reset_last >= '".$date."' LIMIT 1");
while ($r = r($result)) { 
    $check = true;
    
    echo '<h2>'._('Cambio de contraseña').':</h2>

<p>'._('Escribe tu nueva contraseña para efectuar el cambio').':</p>

<form action="/registrar/login/reset-pass-change" method="POST">
<input type="hidden" name="user_ID" value="'.$_GET['user_ID'].'" />
<input type="hidden" name="check" value="'.$_GET['check'].'" />
<input type="password" name="pass_new" value="" /><br />
<input type="password" name="pass_new2" value="" /> ('._('introducir otra vez').')<br />
<br />
<input type="submit" value="'._('Cambiar contraseña').'" style="font-weight:bold;"/>
</form>';
    
}
if ($check != true) { echo _('Error').'.'; }

$txt_title = _('Recuperar contraseña');
$txt_nav = array(_('Recuperar contraseña'));