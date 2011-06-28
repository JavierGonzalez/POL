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
<input type="hidden" name="url" value="' . base64_encode(REGISTRAR.'login.php?a=panel') . '" />
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
	if (substr($_POST['url'], 0, 4) == 'http') { $url = $_POST['url']; } else { $url = base64_decode($_POST['url']); }

	$pre_login = true;
	
	if ($pol['user_ID']) {
		$result = mysql_query("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND pass = '$oldpass' LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) { $userID = $r['ID']; }
		if (($pol['user_ID'] == $userID) AND ($newpass === $newpass2)) {
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

	$result = mysql_query("SELECT ID AS user_ID, nick FROM users WHERE nick = '".$nick."' AND pass = '".$pass."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { $user_ID = $r['user_ID']; }

	if ($user_ID) {
		
		$expire = time() + 31536000;
		setcookie('teorizauser', $nick, $expire, '/', USERCOOKIE);
		setcookie('teorizapass', md5(CLAVE.$pass), $expire, '/', USERCOOKIE);

		if (true) {
			$traza_name = 'vpid1';
			echo '<html>
<header>
<title></title>
<meta http-equiv="refresh" content="6;url=http://www.virtualpol.com/">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://www.virtualpol.com/img/evercookie/swfobject-2.2.min.js"></script>
<script type="text/javascript" src="http://www.virtualpol.com/img/evercookie/evercookie.js"></script>
<script type="text/javascript">
var ec = new evercookie();
ec_url = "'.$url.'";
ec.get("'.$traza_name.'", function(value) { 
	if (value === undefined) {
		ec.set("'.$traza_name.'", "'.$user_ID.'");
	} else if (value == '.$user_ID.') {
	} else {
		ec_url = "http://www.virtualpol.com/registrar/login.php?a=traza&traza=" + value + "&user_ID='.$user_ID.'&pass='.$pass.'&url=" + ec_url; 
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
	} else { header('Location: '.$url); } 
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