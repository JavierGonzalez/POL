<?php

function editor_enriquecido($name, $txt='') {
	$GLOBALS['txt_header'] .= '
<script type="text/javascript" src="/img/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
mode : "textareas",
theme : "advanced",
language : "es",
plugins : "style,table",
 
// Theme options
theme_advanced_buttons1 : "bold,italic,underline,|,strikethrough,sub,sup,charmap,|,forecolor,fontselect,fontsizeselect,|,link,unlink,image,|,undo,redo,|,cleanup,removeformat",
theme_advanced_buttons2 : "justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,blockquote,hr,|,tablecontrols",
theme_advanced_buttons3 : "",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
theme_advanced_resizing : true,

});
</script>';

	return '<textarea name="' . $name . '" style="width:750px;height:350px;">' . $txt . '</textarea>';
}

function evento_chat($msg, $user_ID='0', $sql_chat=0, $secret=false, $tipo='e', $pais='') {
	global $pol, $link, $vp;
	if ($secret) { $nick = '_'; } else { $nick = $pol['nick']; }
	if (!$pais) { $pais = PAIS; }
	mysql_query("INSERT INTO ".strtolower($pais)."_chat_" . $sql_chat . " (nick, time, msg, cargo, user_ID, tipo) VALUES ('" . $nick . "', '" . date('Y-m-d H:i:s') . "', '" . $msg . "', '0', '" . $user_ID . "', '" . $tipo . "')", $link);

	if (($sql_chat == 0) AND ($tipo == 'e')) {
		$msg = str_replace("href=\"/", "href=\"http://".HOST."/", $msg);
		if (!$pais) { $pais = PAIS; }
		mysql_query("INSERT INTO pol_chat_9 (nick, time, msg, cargo, user_ID, tipo) VALUES ('" . $nick . "', '" . date('Y-m-d H:i:s') . "', '<span style=\"background:".$vp['bg'][$pais].";\" >" . $msg . "<span>', '0', '" . $user_ID . "', '" . $tipo . "')", $link);
	}
}

function evento_log($accion, $dato='', $user_ID2='', $user_ID='') {
	global $pol, $link; 
	$user_ID = $pol['user_ID'];
	mysql_query("INSERT INTO ".SQL."log (time, user_ID, user_ID2, accion, dato) VALUES ('" . date('Y-m-d H:i:s') . "', '" . $user_ID . "', '" . $user_ID2 . "', '" . $accion . "', '" . $dato . "')", $link);
}

function cargo_add($cargo_ID, $user_ID) {
	global $link; 
	$result = mysql_query("SELECT nivel FROM ".SQL."estudios WHERE ID = '" . $cargo_ID . "' LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){
		mysql_query("UPDATE ".SQL."estudios_users SET cargo = '1' WHERE ID_estudio = '" . $cargo_ID . "' AND user_ID = '" . $user_ID . "' AND estado = 'ok' LIMIT 1", $link);
		mysql_query("UPDATE ".SQL_USERS." SET nivel = '" . $row['nivel'] . "', cargo = '" . $cargo_ID . "' WHERE ID = '" . $user_ID . "' AND nivel < '" . $row['nivel'] . "' LIMIT 1", $link);
		evento_log(11, $cargo_ID, $user_ID);
	}
}

function cargo_del($cargo_ID, $user_ID) {
	global $link; 
	$result = mysql_query("SELECT nivel FROM ".SQL."estudios WHERE ID = '" . $cargo_ID . "' LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){
		mysql_query("UPDATE ".SQL."estudios_users SET cargo = '0' WHERE ID_estudio = '" . $cargo_ID . "' AND user_ID = '" . $user_ID . "' LIMIT 1", $link);
		evento_log(12, $cargo_ID, $user_ID);
		$result = mysql_query("SELECT ID_estudio, 
(SELECT nivel FROM ".SQL."estudios WHERE ID = ".SQL."estudios_users.ID_estudio LIMIT 1) AS nivel
FROM ".SQL."estudios_users 
WHERE user_ID = '" . $user_ID . "' AND cargo = '1' 
ORDER BY nivel DESC
LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ $user_nivel_max = $row['nivel']; $user_nivel_sql = ", cargo = '" . $row['ID_estudio'] . "'"; }
		if (!$user_nivel_max) { $user_nivel_max = 1; $user_nivel_sql = ", cargo = ''"; }
		mysql_query("UPDATE ".SQL_USERS." SET nivel = '" . $user_nivel_max . "'" . $user_nivel_sql . " WHERE ID = '" . $user_ID . "' LIMIT 1", $link);
	}
}


function enviar_email($user_ID, $asunto, $mensaje, $email='') {
	$cabeceras = "From: VirtualPol <pol@teoriza.com> \nReturn-Path: VirtualPol <pol@teoriza.com>\n X-Sender: VirtualPol <pol@teoriza.com>\n From: VirtualPol <pol@teoriza.com>\n MIME-Version: 1.0\nContent-type: text/html\n";

	if (($user_ID) AND ($email == '')) {
		global $link;
		$result = mysql_unbuffered_query("SELECT email FROM ".SQL_USERS." WHERE ID = '" . $user_ID . "' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ $email = $row['email']; }
	}
	mail($email, $asunto, $mensaje, $cabeceras);
}

function pols_transferir($pols, $emisor_ID, $receptor_ID, $concepto, $pais='') {
	global $link, $pol;

	if (!$pais) { $sql = SQL; $pais = PAIS; } else { $sql = strtolower($pais).'_'; }

	$return = false;
	$pols = strval($pols);
	if (($pols != 0) AND ($concepto)) {
		$concepto = ucfirst(mysql_real_escape_string($concepto));

		//quitar
		if ($emisor_ID > 0) {
			mysql_query("UPDATE ".SQL_USERS." SET pols = pols - " . $pols . " WHERE ID = '" . $emisor_ID . "' AND pais = '".$pais."' LIMIT 1", $link);
		} else {

			if ($pol['nick']) { $concepto = '<b>'.$pol['nick'].'&rsaquo;</b> '.$concepto; }

			mysql_query("UPDATE ".$pais."cuentas SET pols = pols - " . $pols . " WHERE ID = '" . substr($emisor_ID, 1) . "' LIMIT 1", $link);
		}

		//ingresar
		if ($receptor_ID > 0) {
			mysql_query("UPDATE ".SQL_USERS." SET pols = pols + " . $pols . " WHERE ID = '" . $receptor_ID . "' AND pais = '".$pais."' LIMIT 1", $link);
		} else {
			mysql_query("UPDATE ".$sql."cuentas SET pols = pols + " . $pols . " WHERE ID = '" . substr($receptor_ID, 1) . "' LIMIT 1", $link);
		}

		mysql_query("INSERT INTO ".$sql."transacciones (pols, emisor_ID, receptor_ID, concepto, time) VALUES (" . $pols . ", '" . $emisor_ID . "', '" . $receptor_ID . "', '" . $concepto . "', '" . date('Y-m-d H:i:s') . "')", $link);
		$return = true;
	}
	return $return;
}

function eliminar_ciudadano($ID) {
	global $link, $pol;
	$user_ID = false;
	$result3 = mysql_query("SELECT IP, pols, nick, ID, ref, estado,
(SELECT SUM(pols) FROM ".SQL."cuentas WHERE user_ID = '" . $ID . "') AS pols_cuentas 
FROM ".SQL_USERS." 
WHERE ID = '" . $ID . "' 
LIMIT 1", $link);
	while($row3 = mysql_fetch_array($result3)) {
		$user_ID = $row3['ID']; 
		$estado = $row3['estado']; 
		$pols = ($row3['pols'] + $row3['pols_cuentas']); 
		$nick = $row3['nick']; 
		$ref = $row3['ref']; 
		$IP = $row3['IP'];
	}

	if ($user_ID) { // ELIMINAR CIUDADANO
		pols_transferir($pols, $user_ID, '-1', '&dagger; Defuncion: <em>' . $nick . '</em>');

		if ($ref != '0') { 
			mysql_query("UPDATE ".SQL_USERS." SET ref_num = ref_num - 1 WHERE ID = '" . $ref . "' LIMIT 1", $link);
			mysql_query("DELETE FROM ".SQL_REFERENCIAS." WHERE IP = '" . $IP . "' OR user_ID = '" . $ref . "'", $link); 
		}
		mysql_query("DELETE FROM ".SQL_USERS." WHERE ID = '" . $user_ID . "' LIMIT 1", $link);
		mysql_query("DELETE FROM ".SQL_REFERENCIAS." WHERE user_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL."partidos_listas WHERE user_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL."partidos WHERE ID_presidente = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL."empresas WHERE user_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL."mercado WHERE user_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL_MENSAJES." WHERE recibe_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL."estudios_users WHERE user_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL."cuentas WHERE user_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL."ban WHERE user_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL."mapa WHERE user_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL_VOTOS." WHERE user_ID = '" . $user_ID . "' OR uservoto_ID = '" . $user_ID . "'", $link);
		mysql_query("DELETE FROM ".SQL."foros_msg WHERE user_ID = '" . $user_ID . "' AND hilo_ID = '-1'", $link);

		$img_root = RAIZ.'pol/img/a/' . $user_ID;
		if (file_exists($img_root . '.jpg')) {
			@unlink($img_root . '.jpg');
			@unlink($img_root . '_40.jpg');
		}

		// anula el posible voto en elecciones
		if ($pol['config']['elecciones_estado'] == 'elecciones') {
			mysql_query("UPDATE ".SQL."elecciones SET ID_partido = '-1' WHERE user_ID = '" . $user_ID . "' LIMIT 1", $link);
		}

		// eliminar
		if ($estado == 'expulsado') { 
			mysql_query("DELETE FROM ".SQL."foros_msg WHERE user_ID = '" . $user_ID . "'", $link);
			mysql_query("DELETE FROM ".SQL."foros_hilos WHERE user_ID = '" . $user_ID . "'", $link);
		}
	}
}

// accion
function gen_title($title) {
	$title = strip_tags($title);
	return $title;
}
function gen_url($url) {
	$url = trim($url);
	$url = utf8_decode($url);
	$url = strtr($url, " ·ÈÌÛ˙ÒÁ¡…Õ”⁄—«¸‹", "-aeiouncaeiouncuU");
	$url = ereg_replace("[^A-Za-z0-9-]", "", $url);
	$url = substr($url, 0, 90);
	$url = strip_tags($url);
	$url = strtolower($url);
	return $url;
}
function gen_text($text, $type='') {
	$text = utf8_decode($text);
	$text = preg_replace('#(<[^>]+[\s\r\n\"\'])(on|xmlns)[^>]*>#iU', "$1>", $text); //prevent XSS
	if ($type == 'plain') {
		$text = strip_tags($text, "<img>,<b>,<i>,<s>,<embed>,<object>,<param>");
		$text = nl2br($text);
		$text = ereg_replace("(^|\n| )[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", $text);
	} else {
		$text = strip_tags($text, "<img>,<b>,<i>,<s>,<embed>,<object>,<param>,<span>,<font>,<strong>,<p>,<b>,<em>,<ul>,<ol>,<li>,<blockquote>,<a>,<h2>,<h3>,<h4>,<br>,<hr>,<table>,<tr>,<td>,<th>");
		$text = str_replace("\n\n", "<br /><br />\n\n", $text); //LINUX
		$text = str_replace("\r\n\r\n", "<br /><br />\r\n\r\n", $text); //WINDOWS
	}
	//acentos
	$mal = array(chr(183), chr(231), chr(199), chr(128), '∫', '™', '©', 'Æ', '∞', '·', 'È', 'Ì', 'Û', '˙', '¡', '…', 'Õ', '”', '⁄', 'Ò', '—', '¸', '‹', chr(191), '°');
	$ok	= array('&#183;', '&#231;', '&#199;', '&#128;', '&ordm;', '&ordf;', '&copy;', '&reg;', '&deg;', '&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&ntilde;', '&Ntilde;', '&uuml;', '&Uuml;', '&iquest;', '&iexcl;');
	$text = str_replace($mal, $ok, $text);

	return $text;
}

function imageCompression($imgfile='',$thumbsize=0,$savePath=NULL,$format) {
    list($width,$height) = getimagesize($imgfile);

	$newwidth = $thumbsize;
	$newheight = $thumbsize;

    $thumb = imagecreatetruecolor($newwidth,$newheight);
	if ($format == 'gif') {
		$source = imagecreatefromgif($imgfile);
	} elseif ($format == 'png') {
		$source = imagecreatefrompng($imgfile);
	} else {
		$source = imagecreatefromjpeg($imgfile); 
	}
    imagecopyresampled($thumb,$source,0,0,0,0,$newwidth,$newheight,$width,$height);
    imagejpeg($thumb,$savePath,70);
}

function filtro_sql($a) {
	return mysql_real_escape_string($a);
}

?>
