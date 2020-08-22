<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



function onlynumbers($string) {
	$eregi = preg_replace("([a-zA-Z0-9_]+)","",$string);
	return (empty($eregi)?true:false);
}


function comprobar_email($email){
	global $pol;

    $mail_correcto = false;
	$emails_falsos = $pol['config']['backlist_emails'];
	$emails_falsos = explode("\n", $emails_falsos);
	$domain = explode("@", $email); $domain = strtolower($domain[1]);	
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
          if (substr_count($email,".")>= 1){
             $term_dom = substr(strrchr($email, '.'),1);
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                if ($caracter_ult != "@" && $caracter_ult != "."){
					if ((!in_array($domain, $emails_falsos)) AND (preg_match("/\+/", $email) == 0) AND (preg_match("/spam/", $email) == 0)) {
              			$mail_correcto = true;
					}
                }
             }
          }
       }
    }
    return $mail_correcto;
} 



$nick = trim($_POST['nick']);
$email = trim($_POST['email']);
$url = trim($_POST['url']);
$pass1 = trim($_POST['pass1']);
$pass2 = trim($_POST['pass2']);

$nicks_prohibidos = $pol['config']['backlist_nicks'];
$nicks_prohibidos = explode("\n", $nicks_prohibidos);
$crono = $_POST['crono'];


//CONTROL: captcha

if ($_POST['condiciones'] == 'ok') {


    $bloquear_registro = false;

    // Bloquea registro si la IP coincide con otro expulsado
    /*
    $margen = date('Y-m-d H:i:s', time() - (60*60)); // 1 h
    $result = sql("SELECT ID FROM users WHERE (estado = 'expulsadoNO' OR fecha_registro > '".$margen."') AND (IP = '".direccion_IP('longip')."' OR hosts LIKE '%".direccion_IP()."%') LIMIT 1");
    while ($r = r($result)) { $bloquear_registro = true; }

    foreach (explode("\n", $pol['config']['backlist_IP']) AS $la_IP) {
        $la_IP = trim($la_IP);
        if (stristr(' '.direccion_IP(), ' '.explodear(' ', $la_IP, 0))) { $bloquear_registro = true; }
    }
    */

    
    if ($bloquear_registro === false) {


        if (true) {

            //CONTROL: solo letras y numeros en nick
            if ((onlynumbers($nick) == true) AND (!in_array($nick, $nicks_prohibidos))) { 

                //CONTROL: contraseñas
                $rn = $_POST['repid'];
                if (($pass1) && ($pass1 === $pass2)) {
                    if (comprobar_email($email) == true) {

                        $result = sql("SELECT ID FROM users WHERE email = '$email' LIMIT 1");
                        while ($r = r($result)) { $email_existe = $r['ID'];}

                        if (!$email_existe) { //el email esta libre
                            if ((strlen($nick) >= 3) AND (strlen($nick) <= 14)) {

                                $result = sql("SELECT ID FROM users WHERE nick = '".$nick."' LIMIT 1");
                                while ($r = r($result)) { $nick_existe = $r['ID'];}

                                $result = sql("SELECT tiempo FROM expulsiones WHERE tiempo = '".$nick."' AND estado = 'expulsado' LIMIT 1");
                                while ($r = r($result)) { $nick_expulsado_existe = $r['tiempo']; }

                                if ((!$nick_existe) AND (!$nick_expulsado_existe)) { //si el nick esta libre
                                    $longip = ip2long($_SERVER['REMOTE_ADDR']);


                                    //Si existe referencia IP
                                    $afiliacion = 0;
                                    $result = sql("SELECT ID, user_ID, (SELECT nick FROM users WHERE ID = referencias.user_ID LIMIT 1) AS nick FROM referencias WHERE IP = '".$longip."' LIMIT 1");
                                    while($r = r($result)){ 
                                        $afiliacion = $r['user_ID'];
                                        $ref = ' (ref: ' . crear_link($r['nick']) . ')';
                                    }
                                    
                                    // gen API pass
                                    $api_pass = substr(md5(mt_rand(1000000000,9999999999)), 0, 12);

                                    //crea el ciudadano
                                    if (strlen($pass1) != 32) { 
                                        $pass_md5 = pass_key($pass1, 'md5');
                                        $pass_sha = pass_key($pass1);
                                    }
                                    
                                    sql("INSERT INTO users 
(nick, pols, fecha_registro, fecha_last, partido_afiliado, estado, nivel, email, num_elec, online, fecha_init, ref, ref_num, api_pass, api_num, IP, nota, avatar, text, cargo, visitas, paginas, nav, voto_confianza, confianza_historico, pais, pass, pass2, host, IP_proxy, dnie_check, bando, nota_SC, fecha_legal) 
VALUES ('".$nick."', '0', '".$date."', '".$date."', '', 'validar', '1', '" . strtolower($email) . "', '0', '0', '" . $date . "', '".$afiliacion."', '0', '".$api_pass."', '0', '" . $IP . "', '0.0', 'false', '', '', '0', '0', '" . $_SERVER['HTTP_USER_AGENT'] . "', '0', '0', 'POL', '".$pass_md5."', '".$pass_sha."', '".@gethostbyaddr($_SERVER['REMOTE_ADDR'])."', '".ip2long($_SERVER['HTTP_X_FORWARDED_FOR'])."', null, null, '".((($_POST['nick_clon']=='')||(strtolower($_POST['nick_clon'])=='no'))?'':'Comparte con: '.$_POST['nick_clon'])."', '".$date."')");
                                    $result = sql("SELECT ID FROM users WHERE nick = '".$nick."' LIMIT 1");
                                    while($r = r($result)){ $new_ID = $r['ID']; }
                                    
                                    if (!$_COOKIE['trz']) {
                                        $_COOKIE['trz'] = round(microtime(true)*10000);
                                        setcookie('trz', $_COOKIE['trz'], (time()+(86400*365)), '/', USERCOOKIE);
                                    }

                                    users_con($new_ID, $_REQUEST['extra'], 'login');

                                    if ($ref) {
                                        sql("UPDATE referencias SET new_user_ID = '" . $new_ID . "' WHERE IP = '" . $longip . "' LIMIT 1");
                                    }







                                    $url_validacion = 'https://'.$_SERVER['HTTP_HOST'].'/registrar/verificar?code='.$api_pass.'&nick='.$nick;

                                    $mensaje = '<p>'._('Hola').' '.$nick.':</p>';
                                    $mensaje .= '<p>'._('Para terminar el registro debes validar tu usuario. Simplemente tienes que entrar en la siguiente dirección web').':</p>';
                                    $mensaje .= '<p><a href="'.$url_validacion.'">'.$url_validacion.'</a></p>';
                                    $mensaje .= '<p>'._('¡Esto es todo, bienvenido!').'</p>';

                                    enviar_email(null, _('Verificar nuevo usuario').': '.$nick, $mensaje, $email);

                                    $registro_txt .= '<p><span style="color:green;"><b>'._('¡Correcto!').'</b></span>. '._('Tu usuario ha sido creado exitosamente').'.</p><p>'._('Ya puedes hacer login con tu usuario.').'</em>.</p>';

                                } else {$nick = ''; $error .= '<p class="vmal"><b>'._('Error').'</b>: '._('El nick está ocupado, por favor elige otro').'</p>';}
                            } else {$nick = ''; $error .= '<p class="vmal"><b>'._('Error').'</b>: '._('El nick debe tener entre 3 y 14 caracteres').'</p>';}
                        } else {$email = ''; $error .= '<p class="vmal"><b>'._('Error').'</b>: '._('Dirección de email en uso').', <a href="/registrar/login/recuperar-pass"><b>'._('recupera tu usuario').'</b></a></p>';}
                    } else {$email = ''; $error .= '<p class="vmal"><b>'._('Error').'</b>: '._('El email no es valido').'</p>';}
                } else { $pass1 = ''; $pass2 = '';  $error .= '<p class="vmal"><b>'._('Error').'</b>: '._('Debes escribir la misma contraseña dos veces').'</p>';}
            } else { $pass1 = ''; $pass2 = '';  $error .= '<p class="vmal"><b>'._('Error').'</b>: '._('El nick solo puede tener letras, numeros y el caracter: "_". La inicial nunca debe ser un numero').'.</p>';}
        } else { $error .= '<p class="vmal"><b>'._('Error').'</b>: '._('No has acertado captcha').'</p>'; }

    } else { $error .= '<p class="vmal"><b>'._('Error').'</b>: '._('Se ha activado un bloqueo a tu conexión. Si crees que puede ser un error contacta').': '.CONTACTO_EMAIL.'</p>'; }

} else { $error .= '<p class="vmal"><b>'._('Error').'</b>: '._('Debes de aceptar las condiciones').'</p>'; }


if ($error)
    redirect('/registrar?error='.base64_encode($error));
else
    redirect('/registrar?msg='.base64_encode('Usuario registrado! Revisa tu email para validarlo (tal vez esté en spam)!'));