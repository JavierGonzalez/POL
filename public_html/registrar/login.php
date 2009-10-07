<?php
include('../inc-login.php');

function ischecked($num, $user_info) {
	$canales = explode("|", $user_info);
	if ($canales[$num] == 1) { $return = ' checked'; } else { $return = ''; }
	return $return;
}

switch ($_GET['a']) {


case 'recuperar-pass':
	// RECUPERAR PASS
	
	$txt .= '<h1>Recuperar contrase&ntilde;a:</h1><ul>';

	//changepass
	$txt .= '
<li class="azul">
<form action="'.REGISTRAR.'login.php?a=changemail" method="POST">
<input type="hidden" name="url" value="' . base64_encode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '" />
<table border="0" cellpadding="2" cellspacing="0">
<tr>
<td align="center" valign="top">Nick: <input type="text" size="10" name="nick" value="" maxlength="20" /></td>

<td align="center" valign="top">Email: <input type="text" size="20" name="email" value="" maxlength="100" /></td>

<td align="center" valign="top"><input type="submit" value="Recuperar contrase&ntilde;a" style="font-weight:bold;font-size:15px;color:green;" />
</td></tr></table></form></li>';

		$txt .= '</ul>';



	$txt_title = 'Solicitar cambio de contraseña :: Blogs Teoriza';
	include('../theme.php');
	break;






case 'panel':
	// CAMBIAR PASS...
	
	if ($pol['user_ID']) {

		$txt .= '<h1>Configuraci&oacute;n de usuario</h1><ul>';

		//changepass
		$txt .= '<li class="azul"><b>Cambio de contrase&ntilde;a:</b><br />
<form action="'.REGISTRAR.'login.php?a=changepass" method="POST">
<input type="hidden" name="url" value="' . base64_encode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '" />
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
<input type="hidden" name="url" value="' . base64_encode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . '" />
<table border="0" cellpadding="2" cellspacing="0" width="100%">
<tr>
<td align="center" valign="top">Email: <input type="text" size="30" name="email" value="'.$pol['email'].'" maxlength="100" /></td>
<td align="center" valign="top"><input type="submit" value="Cambiar email" style="font-weight:bold;font-size:15px;color:green;" />
</td></tr></table></form></li>';

		$txt .= '</ul>';


	} else { //Intruso
		$txt .= '<p><b style="color:blue;">Contrase&ntilde;a cambiada correctamente.</b> Debes hacer login de nuevo con tu nueva contrase&ntilde;a.</p>';

	}


	$txt_title = 'Panel de Usuarios :: Blogs Teoriza';
	include('../theme.php');
	break;

case 'changepass':
	$oldpass = md5(trim($_POST['oldpass']));
	$newpass = md5(trim($_POST['pass1']));
	$newpass2 = md5(trim($_POST['pass2']));
	$url = base64_decode($_POST['url']);

	$pre_login = true;
	
	if ($pol['user_ID']) {
		$result = mysql_query("SELECT ID FROM ".SQL_USERS." WHERE ID = '".$pol['user_ID']."' AND pass = '$oldpass' LIMIT 1", $link);
		while ($row = mysql_fetch_array($result)) { $userID = $row['ID']; }
		if (($pol['user_ID'] == $userID) AND ($newpass === $newpass2)) {
			mysql_query("UPDATE ".SQL_USERS." SET pass = '" . $newpass . "' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		}
	}

	header("Location: $url");
	break;

case 'changemail':
	$email = trim($_POST['email']);
	$url = base64_decode($_POST['url']);

	$pre_login = true;
	
	if ($pol['user_ID']) {
		mysql_query("UPDATE ".SQL_USERS." SET email = '".$email."' WHERE ID = '".$pol['user_ID']."' AND fecha_registro < '".date('Y-m-d 20:00:00', time() - 864000)."' LIMIT 1", $link);
	}

	header("Location: $url");
	break;



case 'login':
	$user = strtolower(trim($_POST['user']));
	$pass = md5(trim($_POST['pass']));
	$url = base64_decode($_POST['url']);

	$link = conectar();

	$result = mysql_query("SELECT ID FROM ".SQL_USERS." WHERE nick = '$user' AND pass = '$pass' LIMIT 1", $link);
	while ($row = mysql_fetch_array($result)) { $password_check = $row['ID']; }
	if ($password_check) {
		$expire = time() + 31536000;
		$md5_pass = md5(CLAVE.$pass);
		setcookie('teorizauser', $user, $expire, '/', USERCOOKIE);
		setcookie('teorizapass', $md5_pass, $expire, '/', USERCOOKIE);
	}
	header("Location: $url");

	break;

case 'logout':

	setcookie('teorizauser', '', time()-3600, '/', USERCOOKIE);
	setcookie('teorizapass', '', time()-3600, '/', USERCOOKIE);

	session_start();
	session_destroy();


	if ($_SERVER['HTTP_REFERER']) { $url = $_SERVER['HTTP_REFERER']; }
	else { $url = 'http://'.HOST.'/'; }
	header("Location: $url");
	break;

}
 



if ($link) { mysql_close($link); }
?>