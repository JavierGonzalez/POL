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


function ischecked($num, $user_info) {
	$canales = explode("|", $user_info);
	if ($canales[$num] == 1) { $return = ' checked'; } else { $return = ''; }
	return $return;
}

switch ($_GET['a']) {


case 'panel':
	
	if ($pol['user_ID']) {

		

		$result = sql("SELECT ser_SC FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		while($r = r($result)) { $ser_SC = $r['ser_SC']; }

		$txt .= '<h1>'._('Opciones de usuario').' ('.$pol['nick'].'):</h1>

<div style="max-width:640px;">



<fieldset><legend>'._('Acciones').'</legend>

<p style="text-align:center;">
'.(nucleo_acceso('autentificados')?'':boton(_('Autentificación'), SSL_URL.'dnie.php')).' 
'.($pol['pais']!='ninguno'?boton(_('Cambiar de plataforma'), REGISTRAR, false, 'red').' ':'').'
</p>

</fieldset>



<fieldset><legend>'._('Cambiar idioma').'</legend>


<form action="'.REGISTRAR.'login.php?a=changelang" method="POST">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">'._('Idioma').': 
<select name="lang">
<option value="">'._('Idioma por defecto de plataformas').'</option>';
	$result = sql("SELECT lang FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	while ($r = r($result)) { $the_lang = $r['lang']; }

	foreach ($vp['langs'] AS $loc => $lang) {
		$txt .= '<option value="'.$loc.'"'.($loc==$the_lang?' selected="selected"':'').'>'.$lang.'</option>';
	}
	$txt .= '</select>
</td>
<td valign="middle" align="right" valign="top">
'.boton(_('Cambiar'), 'submit', false, 'large blue').'
</td></tr></table></form>



</fieldset>



<fieldset><legend>'._('Cambiar contraseña').'</legend>

<form action="'.REGISTRAR.'login.php?a=changepass" method="POST">
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

<form action="'.REGISTRAR.'login.php?a=changemail" method="POST">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">'._('Email').': <input type="email" size="30" name="email" value="" maxlength="100" placeholder="'.$pol['email'].'" required /></td>
<td valign="middle" align="right" valign="top">
'.boton(_('Cambiar'), 'submit', false, 'large blue').'
</td></tr></table></form>

</fieldset>



<fieldset><legend>'._('Candidato a Supervisor del Censo').'</legend>

<form action="'.REGISTRAR.'login.php?a=ser_SC" method="POST">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">
<input type="checkbox" name="ser_SC" value="true"'.($ser_SC=='true'?' checked="checked"':'').' /> '._('Quiero ser candidato a Supervisor del Censo').'.
</td>
<td valign="middle" align="right">'.boton(_('Guardar'), 'submit', false, 'large blue').'</td>
</tr></table></form>

</fieldset>


<fieldset><legend>'._('Cambiar nick').'</legend>

<form action="'.REGISTRAR.'login.php?a=changenick" method="POST">
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">'._('Nuevo nombre de usuario').':<br /><input type="text" name="newnick" value="" maxlength="30" pattern="[A-Za-z0-9_]{3,14}" placeholder="'.$pol['nick'].'" required /></td>
<td valign="middle" align="right" valign="top">

'.boton(_('Cambiar'), 'submit', '¿Estás seguro de querer cambiar el nick?\n\n! ! !\nSOLO PODRAS CAMBIARLO UNA VEZ AL AÑO.\n! ! !', 'red large').'
</td></tr></table></form>

</fieldset>


<fieldset><legend>'._('Eliminar usuario').'</legend>

<form action="'.REGISTRAR.'login.php?a=borrar-usuario" method="POST">
<input type="hidden" name="nick" value="'.$pol['nick'].'" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td valign="middle" align="center" valign="top">
'.boton(_('ELIMINAR usuario permanentemente'), 'submit', '¿Estas seguro de querer ELIMINAR PERMANENTEMENTE tu usuario y todos los datos de caracter privado de forma definitiva?\n\nEl proceso es automático y la eliminación se efectuará tras 10 días.', 'small red').'
</td></tr></table></form>

</fieldset>

</div>';

	} else { //Intruso
		$txt .= '<p><b style="color:blue;">'._('Cambio efectuado correctamente.</b> Debes entrar de nuevo con tu usuario y contraseña').'.</p>';
	}


	$txt_title = _('Panel de Usuario');
	$txt_nav = array(_('Panel de usuario'));
	include('../theme.php');
	break;







case 'recuperar-pass':
	if ($pol['user_ID']) { redirect(REGISTRAR.'login.php?a=panel'); exit; }

	$txt .= '<h2>'._('¿Has olvidado tu contraseña?').'</h2>';

	if ($_GET['b']=='no-existe') {
		$txt .= '<p style="color:red;"><b>'._('No existe ningún usuario con ese email. Probablemente ha sido eliminado por inactividad, puedes registrarte de nuevo').'.</b></p>';
	} elseif ($_GET['b']=='no-24h') {
		$txt .= '<p style="color:red;"><b>'._('Solo se puede hacer una recuperación de contraseña cada 24 horas. Debes esperar').'.</b></p>';
	}

	$txt .= '<p>'._('No te preocupes, puedes solicitar una recuperación de contraseña. Siguiendo estos pasos').':</p>

<ol>
<li><form action="'.REGISTRAR.'login.php?a=start-reset-pass" method="POST">'._('Tu email').': <input type="text" name="email" value="" style="width:250px;" /> <input type="submit" value="'._('Iniciar recuperación de contraseña').'" style="font-weight:bold;" onclick="alert(\'Recibirás en segundos un email en tu correo.\n\nSi no lo recibes escribe a '.CONTACTO_EMAIL.'\');" /></form></li>
<li>'._('Recibirás inmediatamente un email con una dirección web que te permitirá cambiar la contraseña. (Quizá esté en la carpeta spam)').'.</li>
</ol>

<p>'._('Por seguridad, esta acción <b>solo se puede iniciar una vez cada 24h</b> y el cambio de contraseña ha de realizarse dentro de este periodo').'.</p>

<p>'._('Si esto no te ayuda a recuperar tu usuario, en ultima instancia, puedes escribirnos un email a').' <em>'.CONTACTO_EMAIL.'</em></p>
';
	$txt_title = _('Recuperar contraseña');
	$txt_nav = array(_('Recuperar contraseña'));
	include('../theme.php');
	break;


case 'reset-pass':

	$result = sql("SELECT ID, nick FROM users WHERE ID = '".$_GET['user_ID']."' AND api_pass = '".$_GET['check']."' AND reset_last >= '".$date."' LIMIT 1");
	while ($r = r($result)) { 
		$check = true;
		
		$txt .= '<h2>'._('Cambio de contraseña').':</h2>

<p>'._('Escribe tu nueva contraseña para efectuar el cambio').':</p>

<form action="'.REGISTRAR.'login.php?a=reset-pass-change" method="POST">
<input type="hidden" name="user_ID" value="'.$_GET['user_ID'].'" />
<input type="hidden" name="check" value="'.$_GET['check'].'" />
<input type="password" name="pass_new" value="" /><br />
<input type="password" name="pass_new2" value="" /> ('._('introducir otra vez').')<br />
<br />
<input type="submit" value="'._('Cambiar contraseña').'" style="font-weight:bold;"/>
</form>';
		
	}
	if ($check != true) { $txt .= _('Error').'.'; }

	$txt_title = _('Recuperar contraseña');
	$txt_nav = array(_('Recuperar contraseña'));
	include('../theme.php');
	break;



// ACCIONES /login.php?a=...


case 'reset-pass-change':	
	if ($_POST['pass_new'] === $_POST['pass_new2']) {
		sql("UPDATE users SET pass = '".pass_key($_POST['pass_new'], 'md5')."', pass2 = '".pass_key($_POST['pass_new'])."', api_pass = '".rand(1000000,9999999)."', reset_last = '".$date."' WHERE ID = '".$_POST['user_ID']."' AND api_pass = '".$_POST['check']."' AND reset_last >= '".$date."' LIMIT 1");
	}
	redirect('http://www.'.DOMAIN);
	break;

case 'start-reset-pass':
	$enviado = false;
	$result = sql("SELECT ID, nick, api_pass, email FROM users WHERE email = '".$_POST['email']."' AND reset_last < '".$date."' LIMIT 1");
	while ($r = r($result)) { 
		$enviado = true;
		$reset_pass = rand(1000000000, 9999999999);
		sql("UPDATE users SET api_pass = '".$reset_pass."', reset_last = '".date('Y-m-d H:00:00', time() + (86400*1))."' WHERE ID = '".$r['ID']."' LIMIT 1");

		$texto_email = "<p>"._("Hola")." ".$r['nick']."!</p>
<p>"._("Has solicitado un reset de la contraseña, con la intención de efectuar una recuperación y posterior cambio de contraseña").".</p>

<p>"._("Si has solicitado esta acción, continúa entrando en el siguiente enlace. <b>De lo contrario ignora este email</b>").".</p>

<blockquote>
"._("Reset de contraseña").":<br />
<a href=\"".REGISTRAR."login.php?a=reset-pass&user_ID=".$r['ID']."&check=".$reset_pass."\"><b>".REGISTRAR."login.php?a=reset-pass&user_ID=".$r['ID']."&check=".$reset_pass."</b></a>
</blockquote>

<p>_________<br />
VirtualPol</p>";

		mail($r['email'], "[VirtualPol] "._("Cambio de contraseña de usuario").": ".$r['nick'], $texto_email, "FROM: VirtualPol <".CONTACTO_EMAIL.">\nMIME-Version: 1.0\nContent-type: text/html; charset=UTF-8\n"); 
	}

	if ($enviado == false) {
		$nick_existe = false;
		$result = sql("SELECT ID FROM users WHERE email = '".$_POST['email']."' LIMIT 1");
		while ($r = r($result)) { $nick_existe = true; }
		
		if ($nick_existe) {
			redirect(REGISTRAR.'login.php?a=recuperar-pass&b=no-24h');
		} else {
			redirect(REGISTRAR.'login.php?a=recuperar-pass&b=no-existe');
		}
	} else {
		redirect('http://www.'.DOMAIN);
	}
	break;



case 'changepass':
	$oldpass = md5(trim($_POST['oldpass']));
	$newpass = md5(trim($_POST['pass1']));
	$newpass2 = md5(trim($_POST['pass2']));
	$pre_login = true;
	
	if ($pol['user_ID']) {
		$result = sql("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND pass = '".$oldpass."' LIMIT 1");
		while ($r = r($result)) { $userID = $r['ID']; }
		if (($pol['user_ID'] == $userID) AND ($newpass === $newpass2)) {
			if (strlen($newpass) != 32) { $newpass = pass_key($newpass, 'md5'); }
			sql("UPDATE users SET pass = '".$newpass."', pass2 = '".pass_key($_POST['pass1'])."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		}
	}

	redirect(REGISTRAR.'login.php?a=panel');
	break;
	
case 'changenick':
		$nick_new = trim($_POST['newnick']);
	
		$pre_login = true;
	
		if (isset($pol['user_ID'])) {

			function nick_check($string) {
				$eregi = eregi_replace("([A-Z0-9_]+)","", $string);
				if (empty($eregi)) { return true; } else { return false; }
			}

			$dentro_del_margen = false;
			$result = sql("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND nickchange_last < '".date('Y-m-d 20:00:00', time() - (86400*365))."' LIMIT 1");
			while ($r = r($result)) { $dentro_del_margen = true; }
			
			$nick_existe = false;
			$result = sql("SELECT ID FROM users WHERE nick = '".$nick_new."' LIMIT 1");
			while ($r = r($result)) { $nick_existe = true; }


			if ((nick_check($nick_new)) AND (strlen($nick_new) >= 3) AND (strlen($nick_new) <= 12) AND ($dentro_del_margen) AND (!$nick_existe)) {

				// EJECUTAR CAMBIO DE NICK
				sql("UPDATE users SET nick = '".$nick_new."', nickchange_last = now() WHERE ID = '".$pol['user_ID']."' LIMIT 1");
				
				evento_chat('<b>[#] El ciudadano '.$pol['nick'].'</b> se ha cambiado de nombre a <b>'.crear_link($nick_new).'</b>.', 0, 0, true, 'e', $pol['pais']);
				
				
				unset($_SESSION); 
				session_destroy();

				setcookie('teorizauser', '', time()-3600, '/', USERCOOKIE);
				setcookie('teorizapass', '', time()-3600, '/', USERCOOKIE);
			}
		}

		redirect(REGISTRAR.'login.php?a=panel');
		break;
	
	
case 'changemail':
	$email = trim($_POST['email']);
	$pre_login = true;
	if ($pol['user_ID']) {
		sql("UPDATE users SET email = '".$email."' WHERE ID = '".$pol['user_ID']."' AND fecha_registro < '".date('Y-m-d 20:00:00', time() - 864000)."' LIMIT 1");
	}
	redirect(REGISTRAR.'login.php?a=panel');
	break;

case 'changelang':
	$pre_login = true;
	if ($pol['user_ID']) {
		sql("UPDATE users SET lang = ".($_POST['lang']?"'".$_POST['lang']."'":"NULL")." WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	}
	redirect(REGISTRAR.'login.php?a=panel');
	break;

case 'borrar-usuario':
	if ($_POST['nick'] == $pol['nick']) { 
		evento_log('Eliminación de usuario permanente y voluntaria.');
		sql("UPDATE users SET estado = 'expulsado' WHERE ID = '".$pol['user_ID']."' LIMIT 1"); 
	}
	redirect('http://www.'.DOMAIN.'/');
	break;


case 'ser_SC':
	sql("UPDATE users SET ser_SC = '".($_POST['ser_SC']=='true'?'true':'false')."' WHERE ser_SC IN ('true', 'false') AND ID = '".$pol['user_ID']."' LIMIT 1");
	redirect(REGISTRAR."login.php?a=panel");
	break;



case 'trz':
	if (($_GET['x']) AND ($_GET['y']) AND ($_GET['z'])) {
		$result = sql("SELECT ID FROM users WHERE ID = '".$_GET['y']."' AND api_pass = '".$_GET['z']."' LIMIT 1");
		while($r = r($result)) {
			setcookie('trz', $_GET['x'], (time()+(86400*365)), '/', USERCOOKIE);
			sql("UPDATE users_con SET dispositivo = '".$_GET['x']."' WHERE tipo = 'login' AND user_ID = '".$r['ID']."' ORDER BY time DESC LIMIT 1");
		}
	}
	redirect(base64_decode($_GET['u']));
	break;

case 'login':
	$nick = strtolower(trim($_REQUEST['user']));
	if ($_REQUEST['pass_md5']) { $pass = $_REQUEST['pass_md5']; } else { $pass = md5(trim($_REQUEST['pass'])); }
	
	if ($_REQUEST['url_http']) { 
		$url = $_REQUEST['url_http'];
	} elseif ($_REQUEST['url']) { 
		$url = escape(base64_decode($_REQUEST['url'])); 
	} else {
		$url = 'http://15m.'.DOMAIN; 
	}

	$user_ID = false;

	if (strlen($pass) != 32) { $pass = md5($pass); }
	$result = sql("SELECT ID, nick, api_pass FROM users WHERE ".(strpos($nick, '@')?"email = '".$nick."'":"nick = '".$nick."'")." AND pass = '".$pass."' AND estado != 'expulsado' LIMIT 1");
	while ($r = r($result)) { 
		$user_ID = $r['ID']; 
		$nick = $r['nick']; 
		$api_pass = $r['api_pass'];

		users_con($user_ID, $_REQUEST['extra'], 'login');
	}

	if (is_numeric($user_ID)) {
		
		$expire = ($_REQUEST['no_cerrar_sesion']=='true'?time()+(86400*30):0);
		setcookie('teorizauser', $nick, $expire, '/', USERCOOKIE);
		setcookie('teorizapass', md5(CLAVE.$pass), $expire, '/', USERCOOKIE);

		if (true) {
			$traza_nom = '86731242'; // OLD: vpid1
			echo '<html>
<header>
<title></title>
<meta http-equiv="refresh" id="redirect" content="9;url='.$url.'">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="'.IMG.'lib/evercookie/swfobject-2.2.min.js"></script>
<script type="text/javascript" src="'.IMG.'lib/evercookie/evercookie.js"></script>
<script type="text/javascript">
var ec = new evercookie();
ec.get("'.$traza_nom.'", function(value) { 
	if (value === undefined) {
		var get_ms = new Date().getTime();
		everc_value = '.(isset($_COOKIE['trz'])?'"'.$_COOKIE['trz'].'"':'get_ms + Math.floor(Math.random()*1001)').';
		$("#redirect").attr("content", "9;url='.REGISTRAR.'login.php?a=trz&x=" + everc_value + "&y='.$user_ID.'&z='.$api_pass.'&u='.base64_encode($url).'");
		ec.set("'.$traza_nom.'", everc_value);
	} else { everc_value = value; }
	window.location.href = "'.REGISTRAR.'login.php?a=trz&x=" + everc_value + "&y='.$user_ID.'&z='.$api_pass.'&u='.base64_encode($url).'";
});
</script>
<style type="text/css">
body, a { color:#FFFFFF; cursor:progress; }
*, body { display:none; cursor:progress; }
</style>
</header>
<body style="cursor:progress;">
&nbsp;
</body>
</html>';
		} else { redirect($url); } 
	} else { 
		$result = sql("SELECT estado FROM users WHERE ".(strpos($nick, '@')?"email = '".$nick."'":"nick = '".$nick."'")." LIMIT 1");
		while ($r = r($result)) { $nick_estado = $r['estado']; }

		switch ($nick_estado) {
			case 'turista': case 'ciudadano': $msg_error = _('Contraseña incorrecta'); break;
			case 'expulsado': 
				$result = sql("SELECT razon FROM expulsiones WHERE estado='expulsado' and tiempo = '".$nick."' ORDER BY expire DESC LIMIT 1");
				while ($r = r($result)) { $razon = $r['razon']; }
				$msg_error = ($razon?'Expulsado por incumplimiento del TOS. Infracción: <em>'.$razon.'</em>':'Auto-eliminado'); 
				break;
			case 'validar': $msg_error = _('Usuario no validado, revisa tu email'); break;
			default: $msg_error = _('Usuario inexistente, probablemente expirado por inactividad'); break;
		}

		redirect(REGISTRAR.'login.php?error='.base64_encode($msg_error));
	} 
	break;


case 'logout':
	unset($_SESSION); 
	session_destroy();

	setcookie('teorizauser', '', time()-36000, '/', USERCOOKIE);
	setcookie('teorizapass', '', time()-36000, '/', USERCOOKIE);

	if ($_SERVER['HTTP_REFERER']) { $url = $_SERVER['HTTP_REFERER']; }
	else { $url = 'http://'.HOST.'/'; }
	redirect($url);
	break;



default:

	$txt .= '<div style="width:380px;margin:0 auto;">';

	if (isset($pol['user_ID'])) {
		$txt .= '<p>'._('Ya estás logueado correctamente como').' <b>'.$pol['nick'].'</b>.</p>';
	} else {
		$txt .= '
<script type="text/javascript" src="'.IMG.'lib/md5.js"></script>
<script type="text/javascript">
timestamp_start = Math.round(+new Date()/1000);
everc_value = "";
function login_start() {
	$("#boton_iniciar_sesion").html("'._('Iniciando sesión...').'");
	timestamp_end = Math.round(+new Date()/1000);
	$("#input_extra").val(screen.width + "x" + screen.height + "|" + screen.availWidth + "x" + screen.availHeight + "|" + Math.round(timestamp_end - timestamp_start) + "|" + screen.colorDepth + "|");
	//$("#login_pass").val(hex_md5($("#login_pass").val()));
	//$("#login_pass").attr("name", "pass_md5");
}
</script>
<style>
#content-right { background:url('.IMG.'bg/verde-cesped.gif); }
</style>



<form action="'.REGISTRAR.'login.php?a=login" method="post">
<input name="url" value="'.($_GET['r']?$_GET['r']:base64_encode('http://www.'.DOMAIN.'/')).'" type="hidden" />
<input type="hidden" name="extra" value="" id="input_extra" />

<fieldset><legend>'._('Iniciar sesión').'</legend>

<table border="0" style="margin:10px auto;">

<tr>
<td align="right">'._('Usuario o email').':</td>
<td><input name="user" value="" size="16" maxlength="200" type="text" style="font-size:20px;font-weight:bold;" autocomplete="off" autofocus required /></td>
</tr>

<tr>
<td align="right">'._('Contraseña').':</td>
<td><input id="login_pass" name="pass" type="password" value="" size="16" maxlength="200" style="font-size:20px;font-weight:bold;" required /></td>
</tr>

<tr>
<td align="center" colspan="2"><input type="checkbox" name="no_cerrar_sesion" value="true" id="no_cerrar_sesion" /> <label for="no_cerrar_sesion" class="inline">'._('No cerrar sesión en 30 días').'.</label></td>
</tr>

<tr>
<td colspan="2" align="center">

'.($_GET['error']?'<p style="color:red;"><b>'.escape(base64_decode($_GET['error'])).'</b></p>':'').'

<button onclick="login_start();" class="large blue" id="boton_iniciar_sesion">'._('Iniciar sesión').'</button><br />
<br />
<a href="'.REGISTRAR.'login.php?a=recuperar-pass">'._('¿Has olvidado tu contraseña?').'</a>
</table>

<p style="color:#888;text-align:center;">'._('Contacto').': <a href="mailto:'.CONTACTO_EMAIL.'" style="color:#888;" target="_blank">'.CONTACTO_EMAIL.'</a></p>
</fieldset>

</form>';
	}

	$txt .= '</div>';

	$txt_title = _('Iniciar sesión');
	$txt_nav = array(_('Iniciar sesión'));
	include('../theme.php');
	exit;
}
 

if ($link) { @mysql_close($link); }
?>