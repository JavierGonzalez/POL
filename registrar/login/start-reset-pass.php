<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$enviado = false;
$result = sql("SELECT ID, nick, api_pass, email FROM users WHERE email = '".$_POST['email']."' AND reset_last < '".$date."' LIMIT 1");
while ($r = r($result)) { 
    $enviado = true;
    $reset_pass = rand(1000000000, 9999999999);


    $url_validacion = 'https://'.$_SERVER['HTTP_HOST'].'/registrar/login/reset-pass?user_ID='.$r['ID'].'&check='.$reset_pass;


    sql("UPDATE users SET api_pass = '".$reset_pass."', reset_last = '".date('Y-m-d H:00:00', time() + (86400*1))."' WHERE ID = '".$r['ID']."' LIMIT 1");

    $texto_email = "<p>"._("Hola")." ".$r['nick']."!</p>
<p>"._("Has solicitado un reset de la contraseña, con la intención de efectuar una recuperación y posterior cambio de contraseña").".</p>

<p>"._("Si has solicitado esta acción, continúa entrando en el siguiente enlace. <b>De lo contrario ignora este email</b>").".</p>

<blockquote>
"._("Reset de contraseña").":<br />
<a href=\"".$url_validacion."\"><b>".$url_validacion."</b></a>
</blockquote>

<p>_________<br />
VirtualPol</p>";

    enviar_email(null, "[VirtualPol] "._("Cambio de contraseña de usuario").": ".$r['nick'], $texto_email, $r['email']);
}

if ($enviado == false) {
    $nick_existe = false;
    $result = sql("SELECT ID FROM users WHERE email = '".$_POST['email']."' LIMIT 1");
    while ($r = r($result)) { $nick_existe = true; }
    
    if ($nick_existe) {
        redirect('/registrar/login/recuperar-pass/no-24h');
    } else {
        redirect('/registrar/login/recuperar-pass/no-existe');
    }
} else {
    redirect('/');
}