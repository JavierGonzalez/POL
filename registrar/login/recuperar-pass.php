<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


if ($pol['user_ID']) { redirect('/registrar/login/panel'); exit; }

echo '<h2>'._('¿Has olvidado tu contraseña?').'</h2>';

if ($_GET[2]=='no-existe') {
    echo '<p style="color:red;"><b>'._('No existe ningún usuario con ese email. Probablemente ha sido eliminado por inactividad, puedes registrarte de nuevo').'.</b></p>';
} elseif ($_GET[2]=='no-24h') {
    echo '<p style="color:red;"><b>'._('Solo se puede hacer una recuperación de contraseña cada 24 horas. Debes esperar').'.</b></p>';
}

echo '<p>'._('No te preocupes, puedes solicitar una recuperación de contraseña. Siguiendo estos pasos').':</p>

<ol>
<li><form action="/registrar/login/start-reset-pass" method="POST">'._('Tu email').': <input type="text" name="email" value="" style="width:250px;" /> <input type="submit" value="'._('Iniciar recuperación de contraseña').'" style="font-weight:bold;" onclick="alert(\'Recibirás en segundos un email en tu correo.\n\nSi no lo recibes escribe a '.CONTACTO_EMAIL.'\');" /></form></li>
<li>'._('Recibirás inmediatamente un email con una dirección web que te permitirá cambiar la contraseña. (Quizá esté en la carpeta spam)').'.</li>
</ol>

<p>'._('Por seguridad, esta acción <b>solo se puede iniciar una vez cada 24h</b> y el cambio de contraseña ha de realizarse dentro de este periodo').'.</p>

<p>'._('Si esto no te ayuda a recuperar tu usuario, en ultima instancia, puedes escribirnos un email a').' <em>'.CONTACTO_EMAIL.'</em></p>
';
$txt_title = _('Recuperar contraseña');
$txt_nav = array(_('Recuperar contraseña'));