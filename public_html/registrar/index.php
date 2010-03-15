<?php
include('../inc-login.php');
$adsense_exclude = true;

function comprobar_email($email){
    $mail_correcto = false;
	$emails_falsos = '
binkmail.com
chogmail.com
devnullmail.com
frapmail.com
guerrillamailblock.com
mailcatch.com
mailinator.com
meltmail.com
obobbo.com
putthisinyourspamdatabase.com
sendspamhere.com
shinedyoureyes.com
spamavert.com
spamcorptastic.com
spamgourmet.com
spamherelots.com
spamhereplease.com
tempinbox.com
temporaryinbox.com
thisisnotmyrealemail.com
trash-mail.com
trashmail.net
filzmail.com
brefmail.com
tempemail.net
mytrashmail.com
tempemail.co.za
emaxpro.com
zzn.com
tyldd.com
alone.la
anal.la
bang.la
bisex.la
bitch.la
bizarre.la
buff.la
cumshot.la
devote.la
dick.la
dolly.la
ecstasy.la
erotic.la
extreme.la
fetish.la
freesex.la
fuckme.la
fuckyou.la
gangbang.la
heat.la
honey.la
horny.la
inlove.la
kiss.la
lonely.la
lovely.la
lulu.la
nancy.la
oral.la
randy.la
slave.la
stripper.la
sweet.la
sweetheart.la
sweetly.la
trash2009.com
slopsbox.com
dgraficos.com
navarro.at
acelerados.com
espalpsp.com
espalnds.com
espalwii.com
uggsrock.com
yopmail.com
owlpic.com
666.joliekemulder.nl
';

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
	if(empty($eregi)){
	return true;
	}
	return false;
}



foreach ($vp['paises'] AS $pais) {
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL_USERS." WHERE estado = 'ciudadano' AND pais = '".$pais."'", $link);
	while($row = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".strtolower($pais)."_config SET valor = '" . $row['num'] . "' WHERE dato = 'info_censo' LIMIT 1", $link);
	}
}

switch ($_GET['a']) {
case 'registrar': //CHECK
	$nick = trim($_POST['nick']);
	$email = trim($_POST['email']);
	$url = trim($_POST['url']);
	$pass1 = trim($_POST['pass1']);
	$pass2 = trim($_POST['pass2']);

	$crono = $_POST['crono'];

	//CONTROL: captcha
	include('animal-captcha-check.php');
	if ($_POST['condiciones'] == 'ok') {
		if (animal_captcha_check($_POST['animal']) == true) {

			//CONTROL: solo letras y numeros en nick
			if ((onlynumbers($nick) == true) AND ($nick != 'pol') AND ($nick != 'hispania') AND ($nick != 'virtualpol') AND ($nick != 'teoriza') AND ($nick != 'presidente') AND ($nick != 'god') AND ($nick != 'admin')) { 

				//CONTROL: contraseñas
				$rn = $_POST['repid'];
				if (($pass1) && ($pass1 === $pass2)) {
					if (comprobar_email($email) == true) {

						$result = mysql_query("SELECT ID FROM ".SQL_USERS." WHERE email = '$email' LIMIT 1", $link);
						while ($row = mysql_fetch_array($result)) { $email_existe = $row['ID'];}

						if (!$email_existe) { //el email esta libre
							if ((strlen($nick) >= 3) AND (strlen($nick) <= 14)) {

								$result = mysql_query("SELECT ID FROM ".SQL_USERS." WHERE nick = '$nick' LIMIT 1", $link);
								while ($row = mysql_fetch_array($result)) { $nick_existe = $row['ID'];}

								$result = mysql_query("SELECT tiempo FROM ".SQL_EXPULSIONES." WHERE tiempo = '".$nick."' AND estado = 'expulsado' LIMIT 1", $link);
								while ($row = mysql_fetch_array($result)) { $nick_expulsado_existe = $row['tiempo'];}

								if ((!$nick_existe) AND (!$nick_expulsado_existe)) { //si el nick esta libre
									$longip = ip2long($_SERVER['REMOTE_ADDR']);


									//Si existe referencia IP
									$afiliacion = 0;
									$result = mysql_query("SELECT ID, user_ID,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL_REFERENCIAS.".user_ID LIMIT 1) AS nick
FROM ".SQL_REFERENCIAS." WHERE IP = '" . $longip . "' LIMIT 1", $link);
									while($row = mysql_fetch_array($result)){ 
										$afiliacion = $row['user_ID'];
										$ref = ' (ref: ' . crear_link($row['nick']) . ')';
									}
									
									// gen API pass
									$api_pass = substr(md5(mt_rand(1000000000,9999999999)), 0, 12);

									//crea el ciudadano

									mysql_query("INSERT INTO ".SQL_USERS." 
(nick, pols, fecha_registro, fecha_last, partido_afiliado, estado, nivel, email, num_elec, online, fecha_init, ref, ref_num, api_pass, api_num, IP, nota, avatar, text, cargo, visitas, paginas, nav, voto_confianza, pais, pass, IP_proxy, geo) 
VALUES ('" . $nick . "', '0', '" . $date . "', '" . $date . "', '', 'validar', '1', '" . strtolower($email) . "', '0', '0', '" . $date . "', '" . $afiliacion . "', '0', '" . $api_pass . "', '0', '" . $IP . "', '0.0', 'false', '', '', '0', '0', '" . $_SERVER['HTTP_USER_AGENT'] . "', '0', 'ninguno', '" . md5($pass1) . "', '".ip2long($_SERVER['HTTP_X_FORWARDED_FOR'])."', '')", $link);


									if ($ref) {
										$result = mysql_query("SELECT ID FROM ".SQL_USERS." WHERE nick = '" . $nick . "' LIMIT 1", $link);
										while($row = mysql_fetch_array($result)){ $new_ID = $row['ID']; }
										
										mysql_query("UPDATE ".SQL_REFERENCIAS." SET new_user_ID = '" . $new_ID . "' WHERE IP = '" . $longip . "' LIMIT 1", $link);
									}



									$texto_email = "Hola $nick\n\n\nEste email es para crear tu usuario en VirtualPol. Tan solo debes acceder a la siguiente direccion web, para activar tu usuario.\n\nUsuario: $nick\nContraseña: $pass1\n\n ".REGISTRAR."?a=verificar&nick=" . $nick . "&code=" . $api_pass . "\n\nEsperamos que te diviertas!\n\n\n Atentamente,\nVirtualPol\npol@teoriza.com";


									mail($email, "Verificacion de " . $nick, $texto_email, "FROM: VirtualPol <pol@teoriza.com> \nReturn-Path: pol@teoriza.com \nX-Sender: pol@teoriza.com \nX-Mailer:PHP 4.4 \nMIME-Version: 1.0\n"); 

									$registro_txt .= '<p><span style="color:blue;"><b>OK</b></span>. El usuario se ha creado correctamente. Su estado actual es: <em>En espera de validaci&oacute;n</em>.</p>';
									$registro_txt .= '<p><b>Te hemos enviado un email de verificaci&oacute;n</b>, rev&iacute;salo ahora. En el email te hemos indicado una direccion web que debes visitar para as&iacute; verificar tu usuario.</p><p class="gris">(<b>Rescata el email si est&aacute; como no deseado o spam!</b>)</p>';

								} else {$nick = ''; $verror .= '<p class="vmal"><b>Error 1.</b> Ese nick ya est&aacute; registrado, lo siento.</p>';}
							} else {$nick = ''; $verror .= '<p class="vmal"><b>Error 1.</b> Tu apodo debe tener entre 3 y 14 caracteres.</p>';}
						} else {$email = ''; $verror .= '<p class="vmal"><b>Error 3.</b> La direcci&oacute;n de email ya esta usandose.</p>';}
					} else {$email = ''; $verror .= '<p class="vmal"><b>Error 3.</b> El email no es valido.</p>';}
				} else { $pass1 = ''; $pass2 = '';  $verror .= '<p class="vmal"><b>Error 4.</b> Debes escribir la misma contrase&ntilde;a dos veces.</p>';}
			} else { $pass1 = ''; $pass2 = '';  $verror .= '<p class="vmal"><b>Error 1.</b> El nick solo puede tener letras, numeros y el caracter: "_". La inicial nunca debe ser un numero.</p>';}
		} else { $verror .= '<p class="vmal"><b>Error 5.</b> No has acertado la pregunta captcha.</p>'; }
	} else { $verror .= '<p class="vmal"><b>Error 2.</b> Has de aceptar las condiciones.</p>'; }

	break;
	
case 'verificar': //URL EMAIL
	$nick = $_GET['nick'];
	$result = mysql_query("SELECT ID, nick, pass FROM ".SQL_USERS." WHERE estado = 'validar' AND nick = '" . $nick . "' AND api_pass = '" . $_GET['code'] . "' LIMIT 1", $link);
	while ($row = mysql_fetch_array($result)) { 
		$pol['nick'] = $row['nick'];
		$pol['user_ID'] = $row['ID'];
		$pass = $row['pass'];
	}

	if ($pol['nick']) {


		$expire = time()+31536000;
		setcookie('teorizauser', $pol['nick'], $expire, '/', USERCOOKIE);
		setcookie('teorizapass', md5($pass), $expire, '/', USERCOOKIE);

		mysql_query("UPDATE ".SQL_USERS." SET estado = 'turista' WHERE estado = 'validar' AND ID = '" . $pol['user_ID'] . "' AND api_pass = '" . $_GET['code'] . "' LIMIT 1", $link);

		$atrack = '"/atrack/registro/validado.html"'; 

		$registro_txt .= '<p><span style="color:blue;"><b>OK</b></span>. El usuario se ha verificado correctamente.</p>';
		$registro_txt .= '<p>Ya eres un <span class="turista">Turista</span>, ahora el ultimo paso: '.boton('Solicitar Ciudadania','/registrar/').'</p>';

	} else { 
		$registro_txt .= '<p><b style="color:red;">ERROR</b>, el usuario no ha podido ser verificado.</p>';
	}
	break;


case 'solicitar-ciudadania':
	

	// tiene kick?
	$result = mysql_query("SELECT ID FROM ".strtolower($_POST['pais'])."_ban WHERE estado = 'activo' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
	while ($row = mysql_fetch_array($result)) { $tiene_kick = true; }

	$result = mysql_query("SELECT pais FROM ".SQL_USERS." WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
	while ($row = mysql_fetch_array($result)) { $user_pais = $row['pais']; }

	if (($pol['user_ID']) AND ($tiene_kick != true) AND ($user_pais == 'ninguno') AND ($pol['estado'] == 'turista') AND (($_POST['pais'] == 'POL') OR ($_POST['pais'] == 'Hispania'))) {
		mysql_query("UPDATE ".SQL_USERS." SET estado = 'ciudadano', pais = '" . $_POST['pais'] . "' WHERE estado = 'turista' AND pais = 'ninguno' AND ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
	
		include('../source/inc-functions-accion.php');

		if ($pol['pols'] > 0) {
			$trae = ' (Trayendo consigo: '.pols($pol['pols']).' '.MONEDA.')';
		} else { $trae = ''; }

		evento_chat('<b>[#] <a href="http://'.strtolower($_POST['pais']).DEV.'.virtualpol.com/perfil/'.$pol['nick'].'/" class="nick">' . $pol['nick'] . '</a> acepta la Ciudadania</b> de ' . $_POST['pais'] . $trae, 0, 0, false, 'e', $_POST['pais']);

		mysql_query("INSERT INTO " . strtolower($_POST['pais']) . "_log 
(time, user_ID, user_ID2, accion, dato) 
VALUES ('" . date('Y-m-d H:i:s') . "', '" . $pol['user_ID'] . "', '" . $pol['user_ID'] . "', '2', '')", $link);
		header('Location: http://'.strtolower($_POST['pais']).DEV.'.virtualpol.com/');
	
	} else { header('Location: '.REGISTRAR); }
	
	break;


	default: $verror = ' '; break;
}





if (($pol['estado'] == 'ciudadano') OR ($pol['estado'] == 'desarrollador')) {


	// load config full
	$result = mysql_query("SELECT valor, dato FROM ".strtolower($pol['pais'])."_config WHERE autoload = 'no'", $link);
	while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }


	$txt_title = 'Registrar: PASO 3 (Ya eres Ciudadano!)';
	$txt .= '<h1><span class="gris">1. Crear usuario | 2. Solicitar Ciudadan&iacute;a</span> | 3. Ser Ciudadano</h1><hr />
<p>Felicidades! <b>ya eres Ciudadano de ' . $pol['pais'] . '</b>.</p>

<p>Puedes entrar en tu Pais <a href="http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/"><b>'.$pol['pais'].'</b></a> y saluda a tus compa&ntilde;eros Ciudadanos!</p>

<br /><br /><hr />

<div class="azul">
<p style="color:red;"><b>Rechazar Ciudadania de ' . $pol['pais'] . '</b>:</p>

<ul>
<li>Esta acci&oacute;n es irreversible.</li>
<li style="color:red;"><b>PERDERAS:</b> tus cuentas bancarias (pero no su dinero), <b>cargos</b>, examenes, <b>votos</b> en elecciones activas en este momento, tus empresas, tu partido, subastas de hoy y todos los derechos de Ciudadano.</li>
<li>CONSERVARAS: tu dinero (restando un arancel del <b style="color:red;">'.$pol['config']['arancel_salida'].'%</b>), tu antiguedad, online, mensajes privados, confianza, mensajes en foro... y todo lo dem&aacute;s.</li>
<li>No es necesario rechazar la Ciudadania para experimentar y participar (limitadamente) en otros Paises.</li>
<li>Siempre podr&aacute;s solicitar ciudadan&iacute;a de cualquier Pa&iacute;s.</li>
</ul>
<blockquote>';


if (strtotime($pol['rechazo_last']) < (time() - 21600)) { // 6 horas
	$txt .= '
<form action="http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/accion.php?a=rechazar-ciudadania" method="POST">
<input type="hidden" name="pais" value="'.$pol['pais'].'" />
<p><b style="color:red;">[<input type="submit" value="Rechazar Ciudadania de '.$pol['pais'].'" />]</b></p>
</form>';

} else { $txt .= '<p style="color:red;"><b>Solo puedes rechazar tu Ciudadan&iacute;a una vez cada 6 horas...</b></p>'; }

$txt .= '</blockquote></div>';


} elseif (($pol['estado'] == 'turista') AND ($pol['pais'] != 'ninguno')) {
	$txt_title = 'Registrar: PASO 2 (Solicitar Ciudadania)';
	$txt .= '<h1><span class="gris">1. Crear usuario |</span> 2. Solicitar Ciudadan&iacute;a <span class="gris">| 3. Ser Ciudadano</span></h1><hr /><p>Tu solicitud de ciudadan&iacute;a en ' . $pol['pais'] . ' est&aacute; en proceso.</p>';

} elseif (($pol['estado'] == 'turista') AND ($pol['pais'] == 'ninguno')) {
	$txt_title = 'Registrar: PASO 2 (Solicitar Ciudadania)';
	$atrack = '"/atrack/registro/solicitar.html"'; 

	if (!$_GET['pais']) { $_GET['pais'] = 'POL'; }

	$txt .= '<h1><span class="gris">1. Crear usuario |</span> 2. Solicitar Ciudadan&iacute;a <span class="gris">| 3. Ser Ciudadano</span></h1>
	
<hr /><br />

<div class="azul">

<form name="rp" action="">
Solicitar Ciudadania en el Pais: <select name="r_p" onchange="window.location=(\''.REGISTRAR.'?pais=\' + document.forms.rp.r_p[document.forms.rp.r_p.selectedIndex].value);">
';


	foreach ($vp['paises'] as $pais) {

		// ciudadanos
		$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL_USERS." WHERE pais = '".$pais."'", $link);
		while($row = mysql_fetch_array($result)) { $ciudadanos_num = $row['num']; }

		// pais_des
		$result = mysql_query("SELECT valor FROM ".strtolower($pais)."_config WHERE dato = 'pais_des' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)) { $pais_des = $row['valor']; }

		if ($_GET['pais'] == $pais) { $select = ' selected="selected"'; } else { $select = ''; } 
		$txt .= '<option value="' . $pais . '"' . $select . '>' . $pais . ' - '.$ciudadanos_num.' ciudadanos - '.$pais_des.'</option>';
	}


	$txt .= '</select></form>


<ul>
<li><b>Aceptas ser Ciudadano de <a href="http://'.strtolower($_GET['pais']).DEV.'.virtualpol.com/">'.$_GET['pais'].'</a></b>, con tus derechos y obligaciones.</li>
<li><b>Aceptas <a href="http://'.strtolower($_GET['pais']).DEV.'.virtualpol.com/doc/">La Constituci&oacute;n</a> y las <a href="http://'.strtolower($_GET['pais']).DEV.'.virtualpol.com/doc/">Leyes</a> de '.$_GET['pais'].'</b>.</li></ul>

<div class="pol_form">
<form action="?a=solicitar-ciudadania" method="post">
<input name="pais" value="' . $_GET['pais'] . '" type="hidden" />
<blockquote>
<input value="Aceptar Ciudadania de '.$_GET['pais'].'" style="color: blue; font-size: 20px;" type="submit" onClick="javascript:pageTracker._trackPageview(\'/atrack/registro/ciudadano.html\');" /> 
</blockquote>
</form>

</div>

</div>';

} elseif ($registro_txt) {
	$txt_title = 'Registrar: PASO 2 (Solicitar Ciudadania)';
	$txt .= '<h1>1. Crear usuario <span class="gris">| 2. Solicitar Ciudadan&iacute;a | 3. Ser Ciudadano</span></h1><hr />' . $registro_txt;
} else {
	$atrack = '"/atrack/registro/formulario.html"';
	$txt_title = 'Registrar: PASO 1 (Crear usuario Turista)';
	if ($_POST['condiciones'] == 'ok') { $condiciones = ' checked="checked"'; } else { $condiciones = ''; }
	$txt .= '<h1>1. Crear usuario <span class="gris">| 2. Solicitar Ciudadan&iacute;a | 3. Ser Ciudadano</span></h1><hr />

<p class="gris">VirtualPol es la &Uacute;NICA Comunidad Auto-gestionada Democr&aacute;ticamente de toda Internet. 100% sin admins, sin GODs.</p>

<form action="?a=registrar" method="POST">
<input type="hidden" name="repid" value="' . $rn . '" />
<input type="hidden" name="crono" value="' . time() . '" />

<div style="color:red;font-weight:bold;">' . $verror . '</div>

<ol>
<li><b>Nick</b>: ser&aacute; tu identidad.<br />
<input type="text" name="nick" value="' . $nick . '" size="10" maxlength="14" /><br /><br /></li>

<li><b>Email</b>: debe funcionar bien, te enviar&eacute;mos un email para verificarlo.<br />
<input type="text" name="email" value="' . $email . '" size="30" maxlength="50" /><br /><br /></li>

<li><b>Contrase&ntilde;a</b>: elije una buena contrase&ntilde;a, nunca te la pediremos v&iacute;a email.<br />
<input type="password" autocomplete="off" name="pass1" value="' . $pass1 . '" maxlength="40" /><br />
<input type="password" autocomplete="off" name="pass2" value="' . $pass2 . '" maxlength="40" style="margin-top:1px;" /><br /><br /></li>

<img src="animal-captcha.php" alt="Animal" style="float:right;" />

<li><b>&iquest;Qu&eacute; animal es el de la derecha?</b>: si eres humano lo sabr&aacute;s. &rarr;<br />
<input type="text" name="animal" value="" autocomplete="off" maxlength="20" /><br /><br /><br /><br /></li>


<li><input name="condiciones" value="ok" type="checkbox"' . $condiciones . ' /> <b>Acepta estas condiciones</b>:</li>
</ol>

<div class="azul" style="margin-top:-10px;">
<ul>
<li>Comprendes que <b>esta comunidad es para todos los publicos</b>, y por tanto, no proceden contenidos violentos, pornogr&aacute;ficos o inadecuados.</li>
<li>Aceptas que tu usuario es <b>&Uacute;NICO, PERSONAL e intransferible</b>. Solo se permite uno por persona en VirtualPol. Infringir esta norma b&aacute;sica conlleva una expulsi&oacute;n perpetua de todos los usuarios implicados, sin necesidad de juicio previo. Se encargan de esta tar&eacute;a los Supervisores del Censo.</li>
<li><b>Si compartes conexi&oacute;n</b> a Internet con otros usuarios de VirtualPol, <b>debes notificarlo</b> a los Supervisores del Censo.</li>
<li>Podr&aacute;s recibir algunas notificaciones v&iacute;a email (nunca spam).</li>
<li>Tu usuario expirar&aacute; completamente si no tienes actividad de 10 dias a 90 dias, dependiendo de tu antiguedad.</li>
<li>Si te conectas desde un ordenador p&uacute;blico, usa la acci&oacute;n de <b>Salir</b>, por seguridad.</li>
</ul>
</div>




<ol>

<li value="6"><input type="submit" value="Crear usuario" style="height:40px;font-size:22px;" /></li>
</form>
</ol>';
}

include('../theme.php');
?>
