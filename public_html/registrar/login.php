<?php
include('../inc-login.php');

function ischecked($num, $user_info) {
	$canales = explode("|", $user_info);
	if ($canales[$num] == 1) { $return = ' checked'; } else { $return = ''; }
	return $return;
}

switch ($_GET['a']) {


case 'panel':
	
	if ($pol['user_ID']) {

		$txt .= '<h1>Configuraci&oacute;n de usuario</h1><ul>';

		//changepass
		$txt .= '<li class="azul"><b>Cambio de contrase&ntilde;a:</b><br />
<form action="'.REGISTRAR.'login.php?a=changepass" method="POST">
<input type="hidden" name="url" value="' . base64_encode(REGISTRAR.'login.php?a=panel') . '" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td align="center" valign="top">Contrase&ntilde;a actual:<br /><input type="password" name="oldpass" value="" maxlength="30" /></td>
<td align="center" valign="top">Nueva contrase&ntilde;a:<br /><input type="password" name="pass1" value="" maxlength="30" /><br />
<input type="password" name="pass2" value="" maxlength="30" /></td>
<td align="center" valign="top"><input type="submit" value="Cambiar contrase&ntilde;a" style="font-weight:bold;font-size:15px;color:green;" />
</td></tr></table></form></li>

<br />


<li class="azul"><b>Cambio de email:</b><br />
<form action="'.REGISTRAR.'login.php?a=changemail" method="POST">
<input type="hidden" name="url" value="' . base64_encode(REGISTRAR.'login.php?a=panel') . '" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td align="center" valign="top">Email: <input type="text" size="30" name="email" value="'.$pol['email'].'" maxlength="100" /></td>
<td align="center" valign="top"><input type="submit" value="Cambiar email" style="font-weight:bold;font-size:15px;color:green;" />
</td></tr></table></form></li>

<br />

<li class="azul"><b>Borrar usuario y datos personales:</b><br />
<form action="'.REGISTRAR.'login.php?a=borrar-usuario" method="POST">
<input type="hidden" name="nick" value="'.$pol['nick'].'" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td align="center" valign="top"><input type="submit" value="Eliminar usuario definitivamente" style="font-weight:bold;font-size:16px;color:red;" onclick="if ((confirm(\'&iquest;Estas seguro de querer ELIMINAR PERMANENTEMENTE tu usuario y todos los datos asociados?\')) && (confirm(\'&iquest;De verdad estas seguro? Esta accion es irreversible.\')) && (confirm(\'Si aceptas, tu usuario sera eliminado junto con todos sus datos personales. &iquest;Estas de acuerdo?\'))) {} else { return false; }" />
</td></tr></table></form></li>';

		$txt .= '</ul>';


	} else { //Intruso
		$txt .= '<p><b style="color:blue;">Contrase&ntilde;a cambiada correctamente.</b> Debes hacer login de nuevo con tu nueva contrase&ntilde;a.</p>';

	}


	$txt_title = 'Panel de Usuarios';
	include('../theme.php');
	break;







case 'recuperar-pass':
	if ($pol['user_ID']) { header('Location: '.REGISTRAR.'login.php?a=panel'); exit; }

	$txt .= '<h2>&iquest;Has olvidado tu contrase&ntilde;a?</h2>';

	if ($_GET['b']=='no-existe') {
		$txt .= '<p style="color:red;"><b>No existe ningun usuario con ese email. Probablemente ha sido eliminado por inactividad, puedes registrarlo de nuevo.</b></p>';
	} elseif ($_GET['b']=='no-24h') {
		$txt .= '<p style="color:red;"><b>Solo se puede hacer un reseteo de contrase&ntilde;a cada 24 horas. Debes esperar.</b></p>';
	}

	$txt .= '
<p>No te preocupes, puedes solicitar un reset de la contrase&ntilde;a. Siguiendo estos pasos:</p>

<ol>
<li><form action="'.REGISTRAR.'login.php?a=start-reset-pass" method="POST">Tu email: <input type="text" name="email" value="" style="width:250px;" /> <input type="submit" value="Iniciar reset" style="font-weight:bold;" onclick="alert(\'Recibir&aacute;s en segundos un email en tu correo.\n\nSi no lo recibes escribe a desarrollo@virtualpol.com\');" /></form></li>
<li>Recibir&aacute;s inmediatamente un email con una direcci&oacute;n web que te permitir&aacute; cambiar la contrase&ntilde;a. (Quiz&aacute; est&eacute; en la carpeta spam).</li>
</ol>

<p>Por seguridad, esta acci&oacute;n <b>solo se puede iniciar una vez cada 24h</b> y el cambio de contrase&ntilde;a ha de realizarse dentro de este periodo.</p>

<p>Si esto no te ayuda a recuperar tu usuario, en ultima instancia, puedes escribirnos un email a <em>desarrollo@virtualpol.com</em></p>
';
	$txt_title = 'Recuperar contrase&ntilde;a';
	include('../theme.php');
	break;




case 'reset-pass':

	$result = mysql_query("SELECT ID, nick FROM users WHERE ID = '".$_GET['user_ID']."' AND api_pass = '".$_GET['check']."' AND reset_last >= '".$date."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 
		$check = true;
		
		$txt .= '<h2>Cambio de contrase&ntilde;a:</h2>

<p>Escribe tu nueva contrase&ntilde;a para efectuar el cambio:</p>

<form action="'.REGISTRAR.'login.php?a=reset-pass-change" method="POST">
<input type="hidden" name="user_ID" value="'.$_GET['user_ID'].'" />
<input type="hidden" name="check" value="'.$_GET['check'].'" />
<input type="password" name="pass_new" value="" /><br />
<input type="password" name="pass_new2" value="" /> (introducir otra vez)<br />
<br />
<input type="submit" value="Cambiar contrase&ntilde;a" style="font-weight:bold;"/>
</form>';
		
	}
	if ($check != true) { $txt .= 'Error.'; }

	$txt_title = 'Recuperar contrase&ntilde;a';
	include('../theme.php');
	break;



// ACCIONES /login.php?a=...


case 'reset-pass-change':	
	if ($_POST['pass_new'] === $_POST['pass_new2']) {
		mysql_query("UPDATE users SET pass = '".md5($_POST['pass_new'])."', api_pass = '".rand(1000000,9999999)."', reset_last = '".$date."' WHERE ID = '".$_POST['user_ID']."' AND api_pass = '".$_POST['check']."' AND reset_last >= '".$date."' LIMIT 1", $link);
	}
	header('Location: http://www.virtualpol.com/');
	break;

case 'start-reset-pass':
	$enviado = false;
	$result = mysql_query("SELECT ID, nick, api_pass, email FROM users WHERE email = '".$_POST['email']."' AND reset_last < '".$date."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 
		$enviado = true;
		$reset_pass = rand(1000000000, 9999999999);
		mysql_query("UPDATE users SET api_pass = '".$reset_pass."', reset_last = '".date('Y-m-d H:00:00', time() + (86400*1))."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);

		$texto_email = "<p>Hola ".$r['nick']."!</p>
<p>Ha solicitado un reset de la contraseña, con la intención de efectuar una recuperación y posterior cambio de contraseña.</p>

<p>Si has solicitado esta acción, continúa entrando en el siguiente enlace. <b>De lo contrario ignora este email</b>.</p>

<blockquote>
Reset de contraseña:<br />
<a href=\"".REGISTRAR."login.php?a=reset-pass&user_ID=".$r['ID']."&check=".$reset_pass."\"><b>".REGISTRAR."login.php?a=reset-pass&user_ID=".$r['ID']."&check=".$reset_pass."</b></a>
</blockquote>

<p>_________<br />
VirtualPol</p>";

		mail($r['email'], "[VirtualPol] Cambio de contraseña del usuario: ".$r['nick'], $texto_email, "FROM: VirtualPol <desarrollo@virtualpol.com>\nMIME-Version: 1.0\nContent-type: text/html; charset=UTF-8\n"); 
	}

	if ($enviado == false) {
		$nick_existe = false;
		$result = mysql_query("SELECT ID FROM users WHERE email = '".$_POST['email']."' LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) { $nick_existe = true; }
		
		if ($nick_existe) {
			header('Location: '.REGISTRAR.'login.php?a=recuperar-pass&b=no-24h');
		} else {
			header('Location: '.REGISTRAR.'login.php?a=recuperar-pass&b=no-existe');
		}


	} else {
		header('Location: http://www.virtualpol.com/');
	}
	break;



case 'changepass':
	$oldpass = md5(trim($_POST['oldpass']));
	$newpass = md5(trim($_POST['pass1']));
	$newpass2 = md5(trim($_POST['pass2']));
	if (substr($_POST['url'], 0, 4) == 'http') { $url = $_POST['url']; } else { $url = base64_decode($_POST['url']); }

	$pre_login = true;
	
	if ($pol['user_ID']) {
		$result = mysql_query("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND pass = '".$oldpass."' LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) { $userID = $r['ID']; }
		if (($pol['user_ID'] == $userID) AND ($newpass === $newpass2)) {
			if (strlen($newpass) != 32) { $newpass = md5($newpass); }
			mysql_query("UPDATE users SET pass = '" . $newpass . "' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		}
	}

	header("Location: $url");
	break;

case 'changemail':
	$email = trim($_POST['email']);
	$url = base64_decode($_POST['url']);

	$pre_login = true;
	
	if ($pol['user_ID']) {
		mysql_query("UPDATE users SET email = '".$email."' WHERE ID = '".$pol['user_ID']."' AND fecha_registro < '".date('Y-m-d 20:00:00', time() - 864000)."' LIMIT 1", $link);
	}

	header("Location: $url");
	break;


case 'borrar-usuario':
	if ($_POST['nick'] == $pol['nick']) { mysql_query("UPDATE users SET estado = 'expulsado' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link); }
	header("Location: http://www.virtualpol.com");
	break;

case 'traza':
	if (($_GET['user_ID']) AND ($_GET['traza']) AND ($_GET['pass'])) {
		$result = mysql_query("SELECT ID AS user_ID, traza FROM users WHERE ID = '".$_GET['user_ID']."' AND pass = '".$_GET['pass']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)) {
			if ($r['traza'] == '') { $r['traza'] = ' '; }
			$traza_m = explode(' ', $r['traza']);
			if (!in_array($_GET['traza'], $traza_m)) {
				mysql_query("UPDATE users SET traza = '".$r['traza']." ".$_GET['traza']."' WHERE ID = '".$r['user_ID']."' LIMIT 1", $link);
			}
		}
	}
	header("Location: ".$_GET['url']);
	break;


case 'login':
	$nick = strtolower(trim($_REQUEST['user']));
	if ($_REQUEST['pass_md5']) { $pass = $_REQUEST['pass_md5']; } else { $pass = md5(trim($_REQUEST['pass'])); }
	
	if ($_REQUEST['url_http']) { 
		$url = $_REQUEST['url_http'];
	} elseif ($_REQUEST['url']) { 
		$url = base64_decode($_REQUEST['url']); 
	} else {
		$url = 'http://vp.virtualpol.com/'; 
	}

	$link = conectar();
	
	if (strlen($pass) != 32) { $pass = md5($pass); }
	$result = mysql_query("SELECT ID AS user_ID, nick FROM users WHERE nick = '".$nick."' AND pass = '".$pass."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { $user_ID = $r['user_ID']; }

	if ($user_ID) {
		
		$expire = time() + (86400*60);
		setcookie('teorizauser', $nick, $expire, '/', USERCOOKIE);
		setcookie('teorizapass', md5(CLAVE.$pass), $expire, '/', USERCOOKIE);

		if (true) {
			$traza_name = 'vpid1';
			echo '<html>
<header>
<title></title>
<meta http-equiv="refresh" content="6;url=http://www.virtualpol.com/">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="'.IMG.'evercookie/swfobject-2.2.min.js"></script>
<script type="text/javascript" src="'.IMG.'evercookie/evercookie.js"></script>
<script type="text/javascript">
var ec = new evercookie();
ec_url = "'.$url.'";
ec.get("'.$traza_name.'", function(value) { 
	if (value === undefined) {
		ec.set("'.$traza_name.'", "'.$user_ID.'");
	} else if (value == '.$user_ID.') {
	} else {
		ec_url = "'.REGISTRAR.'login.php?a=traza&traza=" + value + "&user_ID='.$user_ID.'&pass='.$pass.'&url=" + ec_url; 
		ec.set("'.$traza_name.'", "'.$user_ID.'");
	}
	window.location.href = ec_url;
});
</script>
<style type="text/css">
body, a { color:#FFFFFF; }
*, body { display:none; }
</style>
</header>
<body>
&nbsp;
</body>
</html>';
		} else { header('Location: '.$url); } 
	} else { 
		$result = mysql_query("SELECT estado FROM users WHERE nick = '".$nick."' LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) { $nick_estado = $r['estado']; }

		switch ($nick_estado) {
			case 'turista': case 'ciudadano': $msg_error = 'Contrase&ntilde;a incorrecta'; break;
			case 'expulsado': $msg_error = 'Est&aacute;s expulsado de VirtualPol por infracci&oacute;n del <a href="/legal">TOS</a>'; break;
			case 'validar': $msg_error = 'Usuario no validado, revisa tu email'; break;
			default: $msg_error = 'Usuario inexistente, probablemente expirado por inactividad'; break;
		}

		header('Location: '.REGISTRAR.'login.php?error='.base64_encode($msg_error));
	} 
	break;


case 'logout':
	unset($_SESSION); 
	session_destroy();

	setcookie('teorizauser', '', time()-3600, '/', USERCOOKIE);
	setcookie('teorizapass', '', time()-3600, '/', USERCOOKIE);

	if ($_SERVER['HTTP_REFERER']) { $url = $_SERVER['HTTP_REFERER']; }
	else { $url = 'http://'.HOST.'/'; }
	header("Location: $url");
	break;



default:

	$txt_header .= '<style type="text/css">.content { width:400px; margin: 0 auto; padding: 2px 12px 0 12px; }</style>';


	$txt .= '<center><h1>Entrar con ciudadano:</h1></center>';

	if (isset($pol['user_ID'])) {
		$txt .= '<p>Ya est&aacute;s logueado correctamente como <b>'.$pol['nick'].'</b>.</p>';
	} else {
		$txt .= '
<script type="text/javascript" src="'.IMG.'lib/md5.js"></script>
<script type="text/javascript">
function vlgn (objeto) { if ((objeto.value == "Usuario") || (objeto.value == "123")) { objeto.value = ""; } }
</script>


<form action="'.REGISTRAR.'login.php?a=login" method="post">
<input name="url" value="'.($_GET['r']?$_GET['r']:base64_encode('http://www.virtualpol.com/')).'" type="hidden" />

<table border="0" style="margin:20px auto;">

<tr>
<td align="right">Usuario:</td>
<td><input name="user" value="" size="10" maxlength="20" onfocus="vlgn(this)" type="text" style="font-size:20px;font-weight:bold;" /></td>
</tr>

<tr>
<td align="right">Contrase&ntilde;a:</td>
<td><input id="login_pass" name="pass" type="password" value="" size="10" maxlength="200" onfocus="vlgn(this)" style="font-size:20px;font-weight:bold;" /></td>
</tr>

<tr>
<td colspan="2" align="center">
'.($_GET['error']?'<em style="color:red;">'.base64_decode($_GET['error']).'.</em><br /><br />':'').'
<input type="submit" value="Entrar" onclick="$(\'#login_pass\').val(hex_md5($(\'#login_pass\').val()));$(\'#login_pass\').attr(\'name\', \'pass_md5\');" style="font-size:25px;color:#777;" /><br /><br />
<a href="'.REGISTRAR.'login.php?a=recuperar-pass">&iquest;Has olvidado tu contrase&ntilde;a?</a><br /><br />
<a href="'.REGISTRAR.'">&iquest;A&uacute;n no tienes usuario registrado?</a><br /><br /><br />
<span style="color:#888;">Contacto: desarrollo@virtualpol.com</span>
</td>
</tr>

</table>

</form>';
	}

	$txt_title = 'Login';
	include('../theme.php');
	break;

}
 



if ($link) { @mysql_close($link); }
?>