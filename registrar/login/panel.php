<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



if ($pol['user_ID']) {

    

    $result = sql_old("SELECT ser_SC FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1");
    while($r = r($result)) { $ser_SC = $r['ser_SC']; }

    echo '<h1>'._('Opciones de usuario').' ('.$pol['nick'].'):</h1>

<div style="max-width:640px;">


<!--
<fieldset><legend>'._('Acciones').'</legend>

<p style="text-align:center;">
'.($pol['pais']!='ninguno'?boton(_('Cambiar de plataforma'), '/registrar', false, 'red').' ':'').'
</p>

</fieldset>
-->



<fieldset><legend>'._('Cambiar idioma').'</legend>


<form action="/registrar/login/changelang" method="POST">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">'._('Idioma').': 
<select name="lang">
<option value="">'._('Idioma por defecto de plataformas').'</option>';
$result = sql_old("SELECT lang FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1");
while ($r = r($result)) { $the_lang = $r['lang']; }

foreach ($vp['langs'] AS $loc => $lang) {
    echo '<option value="'.$loc.'"'.($loc==$the_lang?' selected="selected"':'').'>'.$lang.'</option>';
}
echo '</select>
</td>
<td valign="middle" align="right" valign="top">
'.boton(_('Cambiar'), 'submit', false, 'large blue').'
</td></tr></table></form>



</fieldset>



<fieldset><legend>'._('Cambiar contraseña').'</legend>

<form action="/registrar/login/changepass" method="POST">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">'._('Contraseña actual').':<br /><input type="password" name="oldpass" value="" maxlength="30" required /></td>
<td valign="middle" align="center" valign="top">'._('Nueva contraseña').':<br /><input type="password" name="pass1" value="" maxlength="30" required /><br />
<input type="password" name="pass2" value="" maxlength="30" required /></td>
<td valign="middle" align="right" valign="top">
'.boton(_('Cambiar'), 'submit', false, 'large blue').'
</td></tr></table></form>

</fieldset>



<fieldset><legend>'._('Cambiar email').'</legend>

<form action="/registrar/login/changemail" method="POST">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">'._('Email').': <input type="email" size="30" name="email" value="" maxlength="100" placeholder="'.$pol['email'].'" required /></td>
<td valign="middle" align="right" valign="top">
'.boton(_('Cambiar'), 'submit', false, 'large blue').'
</td></tr></table></form>

</fieldset>



<fieldset><legend>'._('Candidato a Supervisor del Censo').'</legend>

<form action="/registrar/login/ser_SC" method="POST">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">
<input type="checkbox" name="ser_SC" value="true"'.($ser_SC=='true'?' checked="checked"':'').' /> '._('Quiero ser candidato a Supervisor del Censo').'.
</td>
<td valign="middle" align="right">'.boton(_('Guardar'), 'submit', false, 'large blue').'</td>
</tr></table></form>

</fieldset>


<fieldset><legend>'._('Cambiar nick').'</legend>

<form action="/registrar/login/changenick" method="POST">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">'._('Nuevo nombre de usuario').':<br /><input type="text" name="newnick" value="" maxlength="30" pattern="[A-Za-z0-9_]{3,14}" placeholder="'.$pol['nick'].'" required /></td>
<td valign="middle" align="right" valign="top">

'.boton(_('Cambiar'), 'submit', '¿Estás seguro de querer cambiar el nick?\n\n! ! !\nSOLO PODRAS CAMBIARLO UNA VEZ AL AÑO.\n! ! !', 'red large').'
</td></tr></table></form>

</fieldset>


<fieldset><legend>'._('Eliminar usuario').'</legend>

<form action="/registrar/login/borrar-usuario" method="POST">
<input type="hidden" name="nick" value="'.$pol['nick'].'" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">
'.boton(_('ELIMINAR usuario permanentemente'), 'submit', '¿Estas seguro de querer ELIMINAR PERMANENTEMENTE tu usuario y todos los datos de caracter privado de forma definitiva?\n\nEl proceso es automático y la eliminación se efectuará tras 10 días.', 'small red').'
</td></tr></table></form>

</fieldset>

</div>';

} else { //Intruso
    echo '<p><b style="color:blue;">'._('Cambio efectuado correctamente.</b> Debes entrar de nuevo con tu usuario y contraseña').'.</p>';
}


$txt_title = _('Panel de Usuario');
$txt_nav = array(_('Panel de usuario'));