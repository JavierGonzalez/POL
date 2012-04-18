<?php
include('../inc-login.php');
include('../source/inc-functions-accion.php');



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
	$result = mysql_query("SELECT COUNT(ID) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".$pais."'", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("UPDATE config SET valor = '" . $r['num'] . "' WHERE pais = '".strtolower($pais)."' AND dato = 'info_censo' LIMIT 1", $link);
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

		// Bloquea registro si la IP coincide con otro expulsado
		$margen_3h = date('Y-m-d H:i:s', time() - (60*60*3));
		$bloquear_registro = false;
		$result = sql("SELECT ID FROM users WHERE (estado = 'expulsado' OR fecha_registro > '".$margen_3h."') AND (IP = '".direccion_IP('longip')."' OR hosts LIKE '%".direccion_IP()."%') LIMIT 1");
		while ($r = r($result)) { $bloquear_registro = true; }

		foreach (explode("\n", $pol['config']['backlist_IP']) AS $la_IP) {
			if ((strlen($la_IP) >= 4) AND (stristr(' '.direccion_IP(), ' '.explodear(' ', $la_IP, 0)))) { $bloquear_registro = true; }
		}
		
		if ($bloquear_registro === false) {


		if (animal_captcha_check($_POST['animal']) == true) {

			//CONTROL: solo letras y numeros en nick
			if ((onlynumbers($nick) == true) AND (!in_array($nick, $nicks_prohibidos))) { 

				//CONTROL: contraseñas
				$rn = $_POST['repid'];
				if (($pass1) && ($pass1 === $pass2)) {
					if (comprobar_email($email) == true) {

						$result = mysql_query("SELECT ID FROM users WHERE email = '$email' LIMIT 1", $link);
						while ($r = mysql_fetch_array($result)) { $email_existe = $r['ID'];}

						if (!$email_existe) { //el email esta libre
							if ((strlen($nick) >= 3) AND (strlen($nick) <= 14)) {

								$result = mysql_query("SELECT ID FROM users WHERE nick = '".$nick."' LIMIT 1", $link);
								while ($r = mysql_fetch_array($result)) { $nick_existe = $r['ID'];}

								$result = mysql_query("SELECT tiempo FROM expulsiones WHERE tiempo = '".$nick."' AND estado = 'expulsado' LIMIT 1", $link);
								while ($r = mysql_fetch_array($result)) { $nick_expulsado_existe = $r['tiempo']; }

								if ((!$nick_existe) AND (!$nick_expulsado_existe)) { //si el nick esta libre
									$longip = ip2long($_SERVER['REMOTE_ADDR']);


									//Si existe referencia IP
									$afiliacion = 0;
									$result = mysql_query("SELECT ID, user_ID,
(SELECT nick FROM users WHERE ID = referencias.user_ID LIMIT 1) AS nick
FROM referencias WHERE IP = '".$longip."' LIMIT 1", $link);
									while($r = mysql_fetch_array($result)){ 
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
									
									mysql_query("INSERT INTO users 
(nick, pols, fecha_registro, fecha_last, partido_afiliado, estado, nivel, email, num_elec, online, fecha_init, ref, ref_num, api_pass, api_num, IP, nota, avatar, text, cargo, visitas, paginas, nav, voto_confianza, pais, pass, pass2, host, IP_proxy, geo, dnie_check, bando, nota_SC, fecha_legal) 
VALUES ('".$nick."', '0', '".$date."', '".$date."', '', 'validar', '1', '" . strtolower($email) . "', '0', '0', '" . $date . "', '".$afiliacion."', '0', '".$api_pass."', '0', '" . $IP . "', '0.0', 'false', '', '', '0', '0', '" . $_SERVER['HTTP_USER_AGENT'] . "', '0', '".(in_array($_GET['p'], $vp['paises'])?$_GET['p']:'ninguno')."', '".$pass_md5."', '".$pass_sha."', '".@gethostbyaddr($_SERVER['REMOTE_ADDR'])."', '".ip2long($_SERVER['HTTP_X_FORWARDED_FOR'])."', '', null, null, '".((($_POST['nick_clon']=='')||(strtolower($_POST['nick_clon'])=='no'))?'':'Comparte con: '.$_POST['nick_clon'])."', '".$date."')", $link);

									if ($ref) {
										$result = mysql_query("SELECT ID FROM users WHERE nick = '" . $nick . "' LIMIT 1", $link);
										while($r = mysql_fetch_array($result)){ $new_ID = $r['ID']; }
										
										mysql_query("UPDATE referencias SET new_user_ID = '" . $new_ID . "' WHERE IP = '" . $longip . "' LIMIT 1", $link);
									}



									$texto_email = "Hola $nick\n\n\nAccede a la siguiente direccion, para activar tu usuario y entrar a VirtualPol.\n\n".REGISTRAR."?a=verificar&nick=" . $nick . "&code=" . $api_pass . "\n\nContamos contigo!\n\n\nVirtualPol - http://www.".DOMAIN."/";


									mail($email, "[VirtualPol] Verificar " . $nick, $texto_email, "FROM: VirtualPol <".CONTACTO_EMAIL."> \nReturn-Path: ".CONTACTO_EMAIL." \nX-Sender: ".CONTACTO_EMAIL." \nMIME-Version: 1.0\n"); 

									$registro_txt .= '<p><span style="color:green;"><b>¡Bien!</b></span>. Tu usuario se ha creado correctamente.</p>';
									$registro_txt .= '<p>Ahora <b>debes revisar tu email, te hemos enviado un email para validar tu usuario</b>. Es por seguridad. Rescata el email si lo encuentras en la carpeta de spam.</p>';

								} else {$nick = ''; $verror .= '<p class="vmal"><b>Error</b>: Ese nick ya está registrado ¡elige otro!</p>';}
							} else {$nick = ''; $verror .= '<p class="vmal"><b>Error</b>: ¡Tu nick debe tener entre 3 y 14 caracteres!</p>';}
						} else {$email = ''; $verror .= '<p class="vmal"><b>Error</b>: ¡La dirección de email ya esta usandose, <a href="'.REGISTRAR.'login.php?a=recuperar-pass"><b>debes recuperar tu usuario</b></a>!</p>';}
					} else {$email = ''; $verror .= '<p class="vmal"><b>Error</b>: ¡El email no es valido!</p>';}
				} else { $pass1 = ''; $pass2 = '';  $verror .= '<p class="vmal"><b>Error</b>: ¡Debes escribir la misma contraseña dos veces!</p>';}
			} else { $pass1 = ''; $pass2 = '';  $verror .= '<p class="vmal"><b>Error</b>: El nick solo puede tener letras, numeros y el caracter: "_". La inicial nunca debe ser un numero.</p>';}
		} else { $verror .= '<p class="vmal"><b>Error</b>: ¡No has acertado captcha!</p>'; }

		} else { $verror .= '<p class="vmal"><b>Error</b>: ¿No has acertado captcha?</p>'; }

	} else { $verror .= '<p class="vmal"><b>Error</b>: ¡Has de aceptar las condiciones!</p>'; }
	break;



case 'verificar': //URL EMAIL
	$result = mysql_query("SELECT ID, nick, pass, pais FROM users WHERE estado = 'validar' AND nick = '".$_GET['nick']."' AND api_pass = '".$_GET['code']."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 

		notificacion($r['ID'], 'Bienvenido!', '/doc/bienvenida');

		if ($r['pais'] == 'ninguno') {
			mysql_query("UPDATE users SET estado = 'turista' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
			redirect(REGISTRAR."login.php?a=login&user=".$r['nick']."&pass_md5=".$r['pass']."&url_http=".REGISTRAR);
		} else {
			mysql_query("UPDATE users SET estado = 'ciudadano' WHERE ID = '".$r['ID']."' LIMIT 1", $link);

			
			$result2 = mysql_query("SELECT COUNT(*) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".$r['pais']."'", $link);
			while ($r2 = mysql_fetch_array($result2)) { $ciudadanos_num = $r2['num']; }

			evento_chat('<b>[#] Nuevo ciudadano</b> de <b>'.$r['pais'].'</b> <span style="color:grey;">(<b>'.num($ciudadanos_num).'</b> ciudadanos, <b><a href="http://'.strtolower($r['pais']).'.'.DOMAIN.'/perfil/'.$r['nick'].'/" class="nick">'.$r['nick'].'</a></b>)</span>', 0, 0, true, 'e', $r['pais'], $r['nick']);

			mysql_query("INSERT INTO ".strtolower($r['pais'])."_log 
(time, user_ID, user_ID2, accion, dato) 
VALUES ('".date('Y-m-d H:i:s')."', '".$r['ID']."', '".$r['ID']."', '2', '')", $link);

			unset($_SESSION);
			session_unset(); session_destroy();
			
			redirect(REGISTRAR."login.php?a=login&user=".$r['nick']."&pass_md5=".$r['pass']."&url_http=http://".strtolower($r['pais']).".".DOMAIN."/");
		}
	}

	break;


case 'solicitar-ciudadania':
	

	// tiene kick?
	$result = mysql_query("SELECT ID FROM ".strtolower($_POST['pais'])."_ban WHERE estado = 'activo' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { $tiene_kick = true; }

	$result = mysql_query("SELECT pais FROM users WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { $user_pais = $r['pais']; }

	$pais_existe = false;
	$result = mysql_query("SELECT pais FROM config WHERE pais = '".$_POST['pais']."' AND dato = 'PAIS' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { $pais_existe = $r['pais']; }

	if (($pol['user_ID']) AND ($tiene_kick != true) AND ($user_pais == 'ninguno') AND ($pol['estado'] == 'turista') AND ($pais_existe != false)) {
		mysql_query("UPDATE users SET estado = 'ciudadano', pais = '".$pais_existe."' WHERE estado = 'turista' AND pais = 'ninguno' AND ID = '".$pol['user_ID']."' LIMIT 1", $link);
	
		$result2 = mysql_query("SELECT COUNT(*) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".$_POST['pais']."'", $link);
		while ($r2 = mysql_fetch_array($result2)) { $ciudadanos_num = $r2['num']; }

		evento_chat('<b>[#] Nuevo ciudadano</b> de <b>'.$_POST['pais'].'</b> <span style="color:grey;">(<b>'.num($ciudadanos_num).'</b> ciudadanos, <b><a href="http://'.strtolower($_POST['pais']).'.'.DOMAIN.'/perfil/'.$pol['nick'].'/" class="nick">'.$pol['nick'].'</a></b>)</span>', 0, 0, false, 'e', $_POST['pais'], $r['nick']);

		mysql_query("INSERT INTO ".strtolower($_POST['pais'])."_log 
(time, user_ID, user_ID2, accion, dato) 
VALUES ('".date('Y-m-d H:i:s')."', '".$pol['user_ID']."', '".$pol['user_ID']."', '2', '')", $link);

		unset($_SESSION);
		session_unset(); session_destroy();

		redirect('http://'.strtolower($_POST['pais']).'.'.DOMAIN.'/');
	
	} else { redirect(REGISTRAR); }
	
	break;


	default: $verror = ' '; break;
}




if ($pol['estado'] == 'ciudadano') {


	// load config full
	$result = mysql_query("SELECT valor, dato FROM config WHERE pais = '".strtolower($pol['pais'])."' AND autoload = 'no'", $link);
	while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }


	$txt_title = 'Rechazar ciudadania';
	$txt_nav = array('Ciudadanía');

	$txt .= '<p><b>Actualmente eres ciudadano en la plataforma '.$pol['pais'].'</b>.</p>

<blockquote>
<p style="color:red;"><b>Rechazar ciudadania de '.$pol['pais'].'</b>:</p>

<ul>
<li>Esta acción es irreversible.</li>
<li>No es necesario tener el estatus de ciudadano para participar (parcialmente) en otras plataformas.</li>
<li>Podrás solicitar ciudadanía en otra plataforma o regresar.</li>
'.($pol['pais']=='Hispania'?'
<li style="color:red;"><b>PERDERAS:</b> tus cuentas bancarias (pero tus monedas), <b>cargos</b>, examenes, <b>votos</b> en elecciones activas en este momento, tus empresas, tu partido, subastas de hoy y todos los derechos de ciudadano.</li>
<li>CONSERVARAS: tus monedas (restando un arancel del <b style="color:red;">'.$pol['config']['arancel_salida'].'%</b>), tu antiguedad, online, mensajes privados, confianza, mensajes en foro... y todo lo dem&aacute;s.</li>
':'').'
</ul>';


if (strtotime($pol['rechazo_last']) < (time() - 21600)) { // 6 horas
	$txt .= '
<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=rechazar-ciudadania" method="POST">
<input type="hidden" name="pais" value="'.$pol['pais'].'" />
<p>'.boton('Rechazar ciudadania de la plataforma '.$pol['pais'], 'submit', '¿Estás seguro de querer RECHAZAR ciudadanía?', 'pill red').'</p>
</form>';

} else { $txt .= '<p style="color:red;"><b>Solo puedes rechazar tu ciudadan&iacute;a una vez cada 6 horas...</b></p>'; }

$txt .= '</blockquote>';

} elseif (($pol['estado'] == 'turista') AND ($pol['pais'] != 'ninguno')) {
	$txt_title = 'Registrar: PASO 2 (Solicitar Ciudadania)';
	$txt_nav = array('Crear ciudadano');
	$txt .= '<h1><span class="gris">1. Crear usuario |</span> 2. Solicitar Ciudadan&iacute;a <span class="gris">| 3. Ser Ciudadano</span></h1><hr /><p>Tu solicitud de ciudadan&iacute;a en ' . $pol['pais'] . ' est&aacute; en proceso.</p>';

} elseif (($pol['estado'] == 'turista') AND ($pol['pais'] == 'ninguno')) {
	$txt_title = 'Registrar: PASO 2 (Solicitar Ciudadania)';
	$txt_nav = array('Crear ciudadano');
	$atrack = '"/atrack/registro/solicitar.html"'; 

	if (!$_GET['pais']) { $_GET['pais'] = $vp['paises'][0]; }

	$txt .= '<h1><span class="gris">1. Crear usuario |</span> 2. Solicitar Ciudadan&iacute;a <span class="gris">| 3. Ser Ciudadano</span></h1>
	
<hr /><br />';


	$txt .= '
<form action="?a=solicitar-ciudadania" method="post">
<h1>Solicitar ciudadania:</h1>
<p style="text-align:left;">Dentro de VirtualPol hay diversas plataformas democraticas que son 100% independientes entre s&iacute;. Elige en la que quieres participar.</p>
<b>Plataformas:</b><br />
<table border="0" cellspacing="4">';

	foreach ($vp['paises'] as $pais) {
		// ciudadanos
		$result = mysql_query("SELECT COUNT(ID) AS num FROM users WHERE pais = '".$pais."'", $link);
		while($r = mysql_fetch_array($result)) { $ciudadanos_num = $r['num']; }

		// pais_des
		$result = mysql_query("SELECT valor FROM config WHERE pais = '".strtolower($pais)."' AND dato = 'pais_des' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)) { $pais_des = $r['valor']; }

		$txt .= ($pais=='VP'?'<tr><td>&nbsp;</td></tr>':'').'
<tr style="font-size:19px;">
<td><input type="radio" name="pais" id="pr_'.$pais.'" value="'.$pais.'"'.($pais=='15M'?' checked="checked"':'').' /></td>
<td nowrap="nowrap">'.$pais_des.' de <b>'.$pais.'</b></td>
<td></td>
<td align="right">'.$ciudadanos_num.' ciudadanos</td>
</tr>';
	}


	$txt .= '</table>
<input value="Solicitar Ciudadania" style="font-size:20px;margin:30px 0 0 0;" type="submit" onClick="javascript:pageTracker._trackPageview(\'/atrack/registro/ciudadano.html\');" /> 

</form>';

} elseif ($registro_txt) {
	$txt_title = 'Registrar: PASO 2 (Solicitar Ciudadania)';
	$txt_nav = array('Crear ciudadano');
	$txt .= '<h1>1. Crear usuario <span class="gris">| 2. Solicitar Ciudadan&iacute;a | 3. Ser Ciudadano</span></h1><hr />' . $registro_txt;
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
	$txt_title = 'Registrar: PASO 1 (Crear ciudadano)';
	$txt_nav = array('Crear ciudadano');

	$txt .= '

<form action="?a=registrar'.($_GET['p']?'&p='.$_GET['p']:'').($_GET['r']?'&r='.$_GET['r']:'').'" method="POST" id="form_crear_ciudadano">
<input type="hidden" name="repid" value="' . $rn . '" />
<input type="hidden" name="crono" value="' . time() . '" />
'.($_GET['p']?'<input type="hidden" name="p" value="'.$_GET['p'].'" />':'').'
'.($_GET['r']?'<input type="hidden" name="r" value="'.$_GET['r'].'" />':'').'





<div style="color:red;font-weight:bold;">' . $verror . '</div>

<ol>
<li><b>Nick</b>: ser&aacute; tu identidad.<br />
<input type="text" name="nick" value="' . $nick . '" size="10" maxlength="14" /><br /><br /></li>

<li><b>Email</b>: recibir&aacute;s un email de verificaci&oacute;n. No se enviar&aacute; spam.<br />
<input type="text" name="email" value="' . $email . '" size="30" maxlength="50" /><br /><br /></li>

<li><b>Contrase&ntilde;a</b>: introduce dos veces.<br />
<input id="pass1" class="password" type="password" autocomplete="off" name="pass1" value="" maxlength="40" />
<div class="password-meter" style="float:right;margin-right:400px;color:#666;">
	<div class="password-meter-message">&nbsp;</div>
	<div class="password-meter-bg">
		<div class="password-meter-bar"></div>
	</div>
</div>



<br />
<input id="pass2" type="password" autocomplete="off" name="pass2" value="" maxlength="40" style="margin-top:1px;" /><br /><br /></li>


<li><b>&iquest;Qu&eacute; animal es?</b> Un nombre, sin espacios, nivel primaria. <a href="http://www.teoriza.com/captcha/example.php" target="_blank">Animal Captcha</a>.<br />
<img src="animal-captcha.php" alt="Animal" id="animalcaptchaimg"  onclick="document.getElementById(\'animalcaptchaimg\').src=\'animal-captcha.php?\'+Math.random();" title="Visualizar otro animal" /><br />
<input type="text" name="animal" value="" autocomplete="off" size="14" maxlength="20" /><br /><br /></li>


<li><b>&iquest;Compartes conexi&oacute;n a Internet con otro usuario de VirtualPol?</b><br /> 
En caso afirmativo indica el nick: <input type="text" name="nick_clon" value="" size="10" maxlength="14" /> (en caso negativo deja vac&iacute;o)<br /><br />
</li>


<li><input name="condiciones" value="ok" type="checkbox" /> <b>Aceptas las <a href="http://www'.'.'.DOMAIN.'/TOS" target="_blank">Condiciones de Uso de VirtualPol</a>.</b><br /><br /></li>

<li>'.boton('Crear ciudadano', 'submit', false, 'large blue').'</li>
</form>
</ol>
<script type="text/javascript" src="'.IMG.'lib/md5.js"></script>
<br />';
//  onclick="$(\'#pass1\').val(hex_md5($(\'#pass1\').val()));$(\'#pass2\').val(hex_md5($(\'#pass2\').val()));"

}

include('../theme.php');
?>
