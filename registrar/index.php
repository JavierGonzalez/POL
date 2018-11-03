<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('../inc-login.php');
include('../source/inc-functions-accion.php');


// Configuración de registro
define('CAPTCHA_REGISTRO', false);


$result = sql("SELECT valor, dato FROM config WHERE PAIS IS NULL");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }

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

function onlynumbers($string) {
	$eregi = eregi_replace("([A-Z0-9_]+)","",$string);
	if (empty($eregi)) { return true; } else { return false; }
}


foreach ($vp['paises'] AS $pais) {
	$result = sql("SELECT COUNT(ID) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".$pais."'");
	while($r = r($result)) {
		sql("UPDATE config SET valor = '" . $r['num'] . "' WHERE pais = '".strtolower($pais)."' AND dato = 'info_censo' LIMIT 1");
	}
}


switch ($_GET['a']) {

case 'registrar': //CHECK
	$nick = trim($_POST['nick']);
	$email = trim($_POST['email']);
	$url = trim($_POST['url']);
	$pass1 = trim($_POST['pass1']);
	$pass2 = trim($_POST['pass2']);

	$nicks_prohibidos = $pol['config']['backlist_nicks'];
	$nicks_prohibidos = explode("\n", $nicks_prohibidos);
	$crono = $_POST['crono'];


	//CONTROL: captcha
	include('animal-captcha-check.php');
	if ($_POST['condiciones'] == 'ok') {


		$bloquear_registro = false;

		// Bloquea registro si la IP coincide con otro expulsado
		$margen = date('Y-m-d H:i:s', time() - (60*60)); // 1 h
		$result = sql("SELECT ID FROM users WHERE (estado = 'expulsadoNO' OR fecha_registro > '".$margen."') AND (IP = '".direccion_IP('longip')."' OR hosts LIKE '%".direccion_IP()."%') LIMIT 1");
		while ($r = r($result)) { $bloquear_registro = true; }

		foreach (explode("\n", $pol['config']['backlist_IP']) AS $la_IP) {
			$la_IP = trim($la_IP);
			if (stristr(' '.direccion_IP(), ' '.explodear(' ', $la_IP, 0))) { $bloquear_registro = true; }
		}

		
		if ($bloquear_registro === false) {


		if ((CAPTCHA_REGISTRO == false) OR (animal_captcha_check($_POST['animal']) == true)) {

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
VALUES ('".$nick."', '0', '".$date."', '".$date."', '', 'validar', '1', '" . strtolower($email) . "', '0', '0', '" . $date . "', '".$afiliacion."', '0', '".$api_pass."', '0', '" . $IP . "', '0.0', 'false', '', '', '0', '0', '" . $_SERVER['HTTP_USER_AGENT'] . "', '0', '0', '".(in_array($_GET['p'], $vp['paises'])?$_GET['p']:'ninguno')."', '".$pass_md5."', '".$pass_sha."', '".@gethostbyaddr($_SERVER['REMOTE_ADDR'])."', '".ip2long($_SERVER['HTTP_X_FORWARDED_FOR'])."', null, null, '".((($_POST['nick_clon']=='')||(strtolower($_POST['nick_clon'])=='no'))?'':'Comparte con: '.$_POST['nick_clon'])."', '".$date."')");
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

									$mensaje = '<p>'._('Hola').' '.$nick.':</p>

<p>'._('Para terminar el registro debes validar tu usuario. Simplemente tienes que entrar en la siguiente dirección web').':</p>

<p><a href="'.REGISTRAR.'?a=verificar&code='.$api_pass.'&nick='.$nick.'">'.REGISTRAR.'?a=verificar&code='.$api_pass.'&nick='.$nick.'</a></p>

<p>'._('¡Esto es todo!').'</p>';

									enviar_email(null, _('Verificar nuevo usuario').': '.$nick, $mensaje, $email);

									$registro_txt .= '<p><span style="color:green;"><b>'._('¡Correcto!').'</b></span>. '._('Tu usuario ha sido creado exitosamente').'.</p><p>'._('Último paso. Ahora <b>debes revisar tu email</b>, te hemos enviado un email para validar tu usuario').'.</p><p><em>* '._('Por favor, rescata el email si lo encuentras en la carpeta de spam. Esta medida es por seguridad').'</em>.</p>';

								} else {$nick = ''; $verror .= '<p class="vmal"><b>'._('Error').'</b>: '._('El nick está ocupado, por favor elige otro').'</p>';}
							} else {$nick = ''; $verror .= '<p class="vmal"><b>'._('Error').'</b>: '._('El nick debe tener entre 3 y 14 caracteres').'</p>';}
						} else {$email = ''; $verror .= '<p class="vmal"><b>'._('Error').'</b>: '._('Dirección de email en uso').', <a href="'.REGISTRAR.'login.php?a=recuperar-pass"><b>'._('recupera tu usuario').'</b></a></p>';}
					} else {$email = ''; $verror .= '<p class="vmal"><b>'._('Error').'</b>: '._('El email no es valido').'</p>';}
				} else { $pass1 = ''; $pass2 = '';  $verror .= '<p class="vmal"><b>'._('Error').'</b>: '._('Debes escribir la misma contraseña dos veces').'</p>';}
			} else { $pass1 = ''; $pass2 = '';  $verror .= '<p class="vmal"><b>'._('Error').'</b>: '._('El nick solo puede tener letras, numeros y el caracter: "_". La inicial nunca debe ser un numero').'.</p>';}
		} else { $verror .= '<p class="vmal"><b>'._('Error').'</b>: '._('No has acertado captcha').'</p>'; }

		} else { $verror .= '<p class="vmal"><b>'._('Error').'</b>: '._('Se ha activado un bloqueo a tu conexión. Si crees que puede ser un error contacta').': '.CONTACTO_EMAIL.'</p>'; }

	} else { $verror .= '<p class="vmal"><b>'._('Error').'</b>: '._('Debes de aceptar las condiciones').'</p>'; }
	break;



case 'verificar': //URL EMAIL
	$result = sql("SELECT ID, nick, pass, pais FROM users WHERE estado = 'validar' AND nick = '".$_GET['nick']."' AND api_pass = '".$_GET['code']."' LIMIT 1");
	while ($r = r($result)) { 

		notificacion($r['ID'], _('Bienvenido!'), '/doc/bienvenida');
		notificacion($r['ID'], _('Sitúate en mapa de ciudadanos!'), '/geolocalizacion');

		if ($r['pais'] == 'ninguno') {
			sql("UPDATE users SET estado = 'turista' WHERE ID = '".$r['ID']."' LIMIT 1");
			redirect(REGISTRAR.'login.php?a=login&user='.$r['nick'].'&pass_md5='.$r['pass'].'&url_http='.REGISTRAR);
		} else {
			sql("UPDATE users SET estado = 'ciudadano' WHERE ID = '".$r['ID']."' LIMIT 1");

			
			$result2 = sql("SELECT COUNT(*) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".$r['pais']."'");
			while ($r2 = r($result2)) { $ciudadanos_num = $r2['num']; }

			evento_chat('<b>[#] '._('Nuevo ciudadano').'</b> '._('de').' <b>'.$r['pais'].'</b> <span style="color:grey;">(<b>'.num($ciudadanos_num).'</b> '._('ciudadanos').', <b><a href="http://'.strtolower($r['pais']).'.'.DOMAIN.'/perfil/'.$r['nick'].'" class="nick">'.$r['nick'].'</a></b>)</span>', 0, 0, false, 'e', $r['pais'], $r['nick']);

			unset($_SESSION);
			session_unset(); session_destroy();
			
			redirect(REGISTRAR.'login.php?a=login&user='.$r['nick'].'&pass_md5='.$r['pass'].'&url_http=http://'.strtolower($r['pais']).'.'.DOMAIN);
		}
	}

	break;


case 'solicitar-ciudadania':
	

	// tiene kick?
	$result = sql("SELECT ID FROM ".strtolower($_POST['pais'])."_ban WHERE estado = 'activo' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1");
	while ($r = r($result)) { $tiene_kick = true; }

	$result = sql("SELECT pais FROM users WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1");
	while ($r = r($result)) { $user_pais = $r['pais']; }

	$pais_existe = false;
	$result = sql("SELECT pais FROM config WHERE pais = '".$_POST['pais']."' AND dato = 'PAIS' LIMIT 1");
	while ($r = r($result)) { $pais_existe = $r['pais']; }

	if (($pol['user_ID']) AND ($tiene_kick != true) AND ($user_pais == 'ninguno') AND ($pol['estado'] == 'turista') AND ($pais_existe != false)) {
		sql("UPDATE users SET estado = 'ciudadano', pais = '".$pais_existe."' WHERE estado = 'turista' AND pais = 'ninguno' AND ID = '".$pol['user_ID']."' LIMIT 1");
	
		$result2 = sql("SELECT COUNT(*) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".$_POST['pais']."'");
		while ($r2 = r($result2)) { $ciudadanos_num = $r2['num']; }

		evento_chat('<b>[#] '._('Nuevo ciudadano').'</b> '._('de').' <b>'.$_POST['pais'].'</b> <span style="color:grey;">(<b>'.num($ciudadanos_num).'</b> '._('ciudadanos').', <b><a href="http://'.strtolower($_POST['pais']).'.'.DOMAIN.'/perfil/'.$pol['nick'].'" class="nick">'.$pol['nick'].'</a></b>)</span>', 0, 0, false, 'e', $_POST['pais'], $r['nick']);

		unset($_SESSION);
		session_unset(); session_destroy();

		redirect('http://'.strtolower($_POST['pais']).'.'.DOMAIN);
	
	} else { redirect(REGISTRAR); }
	
	break;


	default: $verror = ' '; break;
}




if ($pol['estado'] == 'ciudadano') {


	// load config full
	$result = sql("SELECT valor, dato FROM config WHERE pais = '".strtolower($pol['pais'])."' AND autoload = 'no'");
	while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }


	$txt_title = _('Cambiar ciudadanía');
	$txt_nav = array(_('Ciudadanía'));

	$txt .= '<p><b>'._('Actualmente eres ciudadano en la plataforma').' '.$pol['pais'].'</b>.</p>

<blockquote>
<p style="color:red;"><b>'._('Cambiar ciudadanía').' '._('de').' '.$pol['pais'].'</b>:</p>

<ul>
<li>Siempre podrás elegir tu ciudadanía libremente pero recuerda que no puedes crear más de un usuario.</li>
<li>No es necesario tener el estatus de ciudadano para participar (parcialmente) en otras plataformas.</li>
'.($pol['pais']=='Hispania'?'
<li style="color:red;"><b>PERDERAS:</b> tus cuentas bancarias (pero tus monedas), <b>cargos</b>, examenes, <b>votos</b> en elecciones activas en este momento, tus empresas, tu partido, subastas de hoy y todos los derechos de ciudadano.</li>
<li>CONSERVARAS: tus monedas (restando un arancel del <b style="color:red;">'.$pol['config']['arancel_salida'].'%</b>), tu antiguedad, online, mensajes privados, confianza, mensajes en foro... y todo lo dem&aacute;s.</li>
':'').'
</ul>';


if (strtotime($pol['rechazo_last']) < (time() - 21600)) { // 6 horas
	$txt .= '
<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=rechazar-ciudadania" method="POST">
<input type="hidden" name="pais" value="'.$pol['pais'].'" />
<p>'.boton(_('Cambiar ciudadanía de la plataforma').' '.$pol['pais'], 'submit', '¿Estás seguro de querer CAMBIAR ciudadanía?', 'pill red').'</p>
</form>';

} else { $txt .= '<p style="color:red;"><b>Solo puedes cambiar tu ciudadanía una vez cada 6 horas...</b></p>'; }

$txt .= '</blockquote>';

} elseif (($pol['estado'] == 'turista') AND ($pol['pais'] != 'ninguno')) {
	$txt_title = 'Registrar: PASO 2 (Solicitar Ciudadania)';
	$txt_nav = array('Crear ciudadano');
	$txt .= '<h1><span class="gris">1. Crear usuario |</span> 2. Solicitar Ciudadan&iacute;a <span class="gris">| 3. Ser Ciudadano</span></h1><hr /><p>Tu solicitud de ciudadanía en '.$pol['pais'].' está en proceso.</p>';

} elseif (($pol['estado'] == 'turista') AND ($pol['pais'] == 'ninguno')) {
	$txt_title = _('Solicitar ciudadanía');
	$txt_nav = array(_('Solicitar ciudadanía'));
	$atrack = '"/atrack/registro/solicitar.html"'; 

	if (!$_GET['pais']) { $_GET['pais'] = $vp['paises'][0]; }

	$txt .= '
<p>'._('Dentro de VirtualPol hay diversas plataformas democraticas que son 100% independientes. Elige en la que quieres participar').'.</p>

<form action="?a=solicitar-ciudadania" method="post">

<fieldset><legend>'._('Elige tu plataforma').'</legend>

<table border="0" cellspacing="4">';
	$n = 0;
	
	$result = sql("SELECT pais, valor AS num FROM config WHERE dato = 'info_censo' ORDER BY ABS(valor) DESC LIMIT 25");
	while($r = r($result)) {

		$pais = $r['pais'];
		$ciudadanos_num = $r['num'];

		// pais_des
		$result2 = sql("SELECT dato, valor FROM config WHERE pais = '".$pais."' AND dato IN ('pais_des', 'tipo')");
		while($r2 = r($result2)) { $pais_array[$r2['dato']] = $r2['valor']; }
		$n++;
		$txt .= '
<tr style="font-size:19px;">
<td valign="middle"><img src="'.IMG.'banderas/'.$pais.'.png" width="80" height="50" border="0" /></td>
<td><input type="radio" name="pais" id="pr_'.$pais.'" value="'.$pais.'"'.($n==1?' checked="checked"':'').' /></td>
<td valign="middle" nowrap="nowrap"><label for="pr_'.$pais.'" style="cursor:pointer;"><b>'.$pais_array['pais_des'].'</b><br /><span class="gris"><b>'.num($ciudadanos_num).'</b> '._('ciudadanos').', '.ucfirst($pais_array['tipo']).'.</span></label></td>
</tr>';
	}

	$txt .= '
<tr>
<td colspan="2"></td>
<td>'.boton(_('Solicitar ciudadanía'), 'submit', false, 'large blue').'</td>
</tr>

</table>

</fieldset>

</form>';

} elseif ($registro_txt) {
	$txt_title = _('Registrar usuario');
	$txt_nav = array(_('Registro'));
	$txt .= $registro_txt;
} else {


	$txt_header .= '
<script type="text/javascript">
$(document).ready(function() {
	$(".password").valid();
	$("#form_crear_ciudadano").validate();
});
</script>
<script type="text/javascript" src="'.IMG.'lib/jquery-validate.password/lib/jquery.validate.js"></script>
<script type="text/javascript" src="'.IMG.'lib/jquery-validate.password/jquery.validate.password.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="'.IMG.'lib/jquery-validate.password/jquery.validate.password.css" />
';

	$atrack = '"/atrack/registro/formulario.html"';
	$txt_title = _('Crear ciudadano');
	$txt_nav = array(_('Crear ciudadano'));

	$txt .= '<form action="?a=registrar'.($_GET['p']?'&p='.$_GET['p']:'').($_GET['r']?'&r='.$_GET['r']:'').'" method="POST" id="form_crear_ciudadano">

<input type="hidden" name="extra" value="" id="input_extra" />
<input type="hidden" name="repid" value="' . $rn . '" />
<input type="hidden" name="crono" value="' . time() . '" />
'.($_GET['p']?'<input type="hidden" name="p" value="'.$_GET['p'].'" />':'').'
'.($_GET['r']?'<input type="hidden" name="r" value="'.$_GET['r'].'" />':'').'



<fieldset><legend>'._('Crear ciudadano').'</legend>

<fieldset><legend>Atencion:</legend><h3 style="color:red">Se ha detectado un problema con los e-mails de validación cuando se usa hotmail/outlook, se recomienda usar gmail.<br/>Estamos trabajando en arreglar el fallo, tened paciencia.</h3>
</fieldset>

<div style="color:red;font-weight:bold;">'.$verror.'</div>

<table>

<tr>
<td align="right"><b>'._('Nick').'</b>:</td>
<td><input type="text" name="nick" value="'.$nick.'" size="10" maxlength="14" pattern="[A-Za-z0-9_]{3,14}" placeholder="'._('nick').'" required /> '._('Será tu identidad. Sin espacios. Solo letras, numeros y').' "_".</td>
</tr>

<tr>
<td align="right"><b>'._('Email').'</b>:</td>
<td><input type="email" name="email" value="'.$email.'" size="30" maxlength="50" placeholder="'._('tu_direccion@email.com').'" required /> '._('Recibirás un email de verificación. No se enviará spam').'.</td>
</tr>

<tr>
<td align="right" valign="top"><b>'._('Contraseña').'</b>:</td>
<td>

<div class="password-meter" style="white-space:nowrap;margin-bottom:4px">
	<div class="password-meter-message">&nbsp;</div>
	<div class="password-meter-bg">
		<div class="password-meter-bar"></div>
	</div>
</div>

<input id="pass1" class="password" type="password" autocomplete="off" name="pass1" value="" maxlength="40" required /><br />
<input id="pass2" type="password" autocomplete="off" name="pass2" value="" maxlength="40" style="margin-top:1px;" required /> '._('Introduce otra vez').'.</td>
</tr>

'.(CAPTCHA_REGISTRO?'<tr>
<td align="right" valign="top"><b>'._('¿Qué animal es?').'</b>:</td>
<td><img src="animal-captcha.php" alt="Animal" id="animalcaptchaimg"  onclick="document.getElementById(\'animalcaptchaimg\').src=\'animal-captcha.php?\'+Math.random();" title="'._('Visualizar otro animal').'" /><br />
<input type="text" name="animal" value="" autocomplete="off" size="14" maxlength="20" placeholder="'._('Ejemplo: león').'" pattern="[A-Za-záéíóúÁÉÍÓÚñÑüÜ]{2,20}" required /> '._('Un nombre, sin espacios, nivel primaria').' (<a href="http://www.teoriza.com/captcha/example.php" target="_blank">Animal Captcha</a>)</td>
</tr>':'').'

<tr>
<td></td>
<td><b>'._('¿Compartes conexión a Internet con otro usuario de VirtualPol?').'</b><br />
'._('En caso afirmativo indica el nick').': <input type="text" name="nick_clon" value="" size="10" maxlength="14" pattern="[A-Za-z0-9_]{0,14}" /> '._('En caso negativo dejar vacío').'.</td>
</tr>


<tr>
<td></td>
<td><br /><span style="font-size:18px;">Cosas que debes saber:</span>
<ul style="margin:0;">
	<li><b>VirtualPol es tuyo.</b> Este proyecto lo construimos entre todos. Es software libre, gratuito, sin publicidad y está <u>al servicio del procomún</u>.</li>
	<li><b>Es la primera red social democrática.</b> Una herramienta <u>pionera</u> que desde 2008 da soporte a diversas plataformas independientes entre sí.</li>
	<li><b>Todos los usuarios son iguales.</b> Es la primera comunidad de internet <u>sin administradores</u> privilegiados. Cualquier usuario se puede involucrar en la gestión en absoluta igualdad de condiciones.</li>
	<li><b>Democrático.</b> Todo se determina mediante mecanismos <u>genuinamente democráticos</u> (votaciones, elecciones, etc). El sistema es automático y esto garantiza que nadie puede acaparar el control.</li>
	<li>Hay 4 lineas de participación:
	<ol style="margin:0;">
		<li><b>Chat</b>: para conocerse.</li>
		<li><b>Foro</b>: para debatir en profundidad.</li>
		<li><b>Votaciones</b>: para tomar decisiones.</li>
		<li><b>Grupos de Trabajo</b>: para actuar y llevar las decisiones a la realidad.</li>
	</ol>
	</li>
</ul>
</td>
</tr>



<tr>
<td></td>
<td><input type="checkbox" name="condiciones" value="ok" id="checkcondiciones" required /> <label for="checkcondiciones"><b>'._('Aceptas las').' <a href="http://www'.'.'.DOMAIN.'/TOS" target="_blank">'._('Condiciones de Uso de VirtualPol').' (TOS)</a>.</b></label></td>
</tr>

<tr>
<td></td>
<td><button onclick="login_start();" class="large blue">'._('Crear ciudadano').'</button></td>
</tr>

</table>

</fieldset>

</form>

<script type="text/javascript" src="'.IMG.'lib/md5.js"></script>
<script type="text/javascript">
timestamp_start = Math.round(+new Date()/1000);
function login_start() {
	timestamp_end = Math.round(+new Date()/1000);
	$("#input_extra").val(screen.width + "x" + screen.height + "|" + screen.availWidth + "x" + screen.availHeight + "|" + Math.round(timestamp_end - timestamp_start) + "|" + screen.colorDepth + "|");
}
</script>';

}

include('../theme.php');
?>
