<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$nick = strtolower(trim($_REQUEST['user']));
if ($_REQUEST['pass_md5']) { $pass = $_REQUEST['pass_md5']; } else { $pass = md5(trim($_REQUEST['pass'])); }

if ($_REQUEST['url_http']) { 
    $url = $_REQUEST['url_http'];
} elseif ($_REQUEST['url']) { 
    $url = $_REQUEST['url']; 
} else {
    $url = ''; 
}

$user_ID = false;

if (strlen($pass) != 32) { $pass = md5($pass); }
$result = sql_old("SELECT ID, nick, api_pass FROM users WHERE ".(strpos($nick, '@')?"email = '".$nick."'":"nick = '".$nick."'")." AND pass = '".$pass."' AND estado != 'expulsado' LIMIT 1");
while ($r = r($result)) { 
    $user_ID = $r['ID']; 
    $nick = $r['nick']; 
    $api_pass = $r['api_pass'];

    users_con($user_ID, $_REQUEST['extra'], 'login');
}

if (is_numeric($user_ID)) {
    
    $expire = ($_REQUEST['no_cerrar_sesion']=='true'?time()+(86400*30):0);
    setcookie('teorizauser', $nick, $expire, '/', USERCOOKIE);
    setcookie('teorizapass', md5(PASSWORDS['clave'].$pass), $expire, '/', USERCOOKIE);

    redirect($url);
} else { 
    $result = sql_old("SELECT estado FROM users WHERE ".(strpos($nick, '@')?"email = '".$nick."'":"nick = '".$nick."'")." LIMIT 1");
    while ($r = r($result)) { $nick_estado = $r['estado']; }

    switch ($nick_estado) {
        case 'turista': case 'ciudadano': $msg_error = _('Contraseña incorrecta'); break;
        case 'expulsado': 
            $result = sql_old("SELECT razon FROM expulsiones WHERE estado='expulsado' and tiempo = '".$nick."' ORDER BY expire DESC LIMIT 1");
            while ($r = r($result)) { $razon = $r['razon']; }
            $msg_error = ($razon?'Expulsado por incumplimiento del TOS. Infracción: <em>'.$razon.'</em>':'Auto-eliminado'); 
            break;
        case 'validar': $msg_error = _('Usuario no validado, revisa tu email'); break;
        default: $msg_error = _('Usuario inexistente, probablemente expirado por inactividad'); break;
    }

    redirect('/registrar/login?error='.base64_encode($msg_error));
} 