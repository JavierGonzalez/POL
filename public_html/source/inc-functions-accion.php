<?php


function presentacion($titulo, $html, $url='http://www.virtualpol.com') {
	global $link;
	echo '
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8" />
<title>'.ucfirst($titulo).'</title>

<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:regular,semibold,italic,italicsemibold|PT+Sans:400,700,400italic,700italic|PT+Serif:400,700,400italic,700italic" />

<link rel="stylesheet" href="'.IMG.'lib/impress/css/impress-demo.css" />
</head>
<body>

<div id="impress" class="impress-not-supported">

<div class="fallback-message">
<p>Tu navegador <b>no soporta las caracteristicas requeridas</b> de impress.js, por lo tanto esta es una versión simplificada de esta presentación.</p>
<p>Para una mejor experiencia por favor usa la ultima versión del navegador <b>Chrome</b> o <b>Safari</b>. Firefox 10 (proximamente) tambien será soportado.</p>
</div>

'.str_replace('&quot;', '"', str_replace('&gt;', '>', str_replace('&lt;', '<', strip_tags($html)))).'


<div class="hint">
<p>Usa las teclas de <em>espacio</em> o <em>flechas</em> para navegar</p>
</div>

</div>



<div style="position: fixed; bottom: 10px; left: 10px;">
<a href="https://twitter.com/share" class="twitter-share-button" data-text="Presentación '.$url.'/presentacion VirtualPol" data-lang="es" data-size="large" data-related="VirtualPol">Twittear</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>

<a href="'.$url.'"><img style="position: absolute; top: -3px; left: -3px; border: 0; border-bottom-right-radius:12px; -moz-border-radius-bottomright:12px; -webkit-border-bottom-right-radius:12px; opacity:0.5;filter:alpha(opacity=50)" src="'.IMG.'logo-virtualpol-1.gif" alt="VirtualPol"></a>


<script src="'.IMG.'lib/impress/js/impress.js"></script>
</body>
</html>';
	mysql_close($link);
	exit;
}


function pad($control, $ID=false, $txt='') {
	if ($control != 'print') {
		include('../img/lib/etherpad-lite/etherpad-lite-client.php');
		$e = new EtherpadLiteClient(CLAVE_API_ETHERPAD, 'http://www.'.DOMAIN.':9001/api');
	}
	switch ($control) {

		case 'print':
			global $pol;
			return '<iframe src="http://www.virtualpol.com:9001/p/'.$ID.'?userName='.$pol['nick'].'" width="100%" height="500" frameborder="0" style="background:#FFF;margin:0 0 -9px -20px;"></iframe>';
			break;

		case 'create':
			try {
				$e->createPad($ID, html_entity_decode(strip_tags(str_replace("<br />", "\n", $txt)), null, 'UTF-8'));
				return true;
			} catch (Exception $error) { return false; }
			break;

		case 'get':
			try {
				return $e->getHTML($ID)->html;
			} catch (Exception $error) { return false; }
			break;

		case 'delete':
			try {
				$e->deletePad($ID);
				return true;
			} catch (Exception $error) { return false; }
			break;
	}
}



// ELIMINACION DE TINYMCE EN CURSO
function editor_enriquecido($name, $txt='') {
        $GLOBALS['txt_header'] .= '
<script type="text/javascript">
document.domain = "'.DOMAIN.'";
</script>
<script type="text/javascript" src="'.IMG.'tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">

tinyMCE.init({
mode : "textareas",
theme : "advanced",
language : "es",
plugins : "style,table",

elements : "abshosturls",
relative_urls : false,
remove_script_host : false,

// Theme options
theme_advanced_buttons1 : "bold,italic,underline,|,strikethrough,sub,sup,charmap,|,forecolor,fontselect,fontsizeselect,|,link,unlink,image,|,undo,redo,|,cleanup,removeformat,code",
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

function evento_chat($msg, $user_ID='0', $chat_ID='', $secret=false, $tipo='e', $pais='', $nick=false) {
	global $pol, $link, $vp;
	if (!$nick) { $nick = $pol['nick']; }
	if (!$pais) { $pais = PAIS; }
	$chat_ID = $vp['paises_chat'][$pais];
	mysql_query("INSERT INTO chats_msg (chat_ID, nick, msg, cargo, user_ID, tipo) VALUES ('".$chat_ID."', '".$nick."', '".$msg."', '0', '".$user_ID."', '".$tipo."')", $link);
}

function evento_log($accion, $dato='', $user_ID2='', $user_ID='') {
	global $pol, $link; 
	$user_ID = $pol['user_ID'];
	mysql_query("INSERT INTO ".SQL."log (time, user_ID, user_ID2, accion, dato) VALUES ('" . date('Y-m-d H:i:s') . "', '".$user_ID."', '" . $user_ID2 . "', '" . $accion . "', '" . $dato . "')", $link);
}

function cargo_add($cargo_ID, $user_ID, $evento_chat=true, $sistema=false) {
	global $link, $pol, $date; 
	$result = mysql_query("SELECT nombre, nivel FROM ".SQL."estudios WHERE ID = '" . $cargo_ID . "' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){
			
			$result2 = mysql_query("SELECT ID FROM ".SQL."estudios_users WHERE ID_estudio = '" . $cargo_ID . "' AND user_ID = '".$user_ID."' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ $tiene_examen = true; }

			if ($tiene_examen) {
					mysql_query("UPDATE ".SQL."estudios_users SET cargo = '1', estado = 'ok' WHERE ID_estudio = '" . $cargo_ID . "' AND user_ID = '".$user_ID."' AND estado = 'ok' LIMIT 1", $link);
			} else {
					mysql_query("INSERT INTO ".SQL."estudios_users (ID_estudio, user_ID, time, estado, cargo, nota) VALUES ('" . $cargo_ID . "', '".$user_ID."', '" . $date . "', 'ok', '1', '0.0')", $link);
			}

			mysql_query("UPDATE users SET nivel = '" . $r['nivel'] . "', cargo = '" . $cargo_ID . "' WHERE ID = '".$user_ID."' AND nivel < '" . $r['nivel'] . "' LIMIT 1", $link);
			evento_log(11, $cargo_ID, $user_ID);

			if ($evento_chat) { 
					$result2 = mysql_query("SELECT nick FROM users WHERE ID = '".$user_ID."' LIMIT 1", $link);
					while($r2 = mysql_fetch_array($result2)){ $nick_asignado = $r2['nick']; }
					evento_chat('<b>[CARGO]</b> El cargo de <img src="'.IMG.'cargos/'.$cargo_ID.'.gif" />'.$r['nombre'].' ha sido asignado a '.crear_link($nick_asignado).' por '.crear_link(($sistema==true?'VirtualPol':$pol['nick'])));
			}
	}
}

function cargo_del($cargo_ID, $user_ID, $evento_chat=true, $sistema=false) {
        global $link, $pol; 
        $result = mysql_query("SELECT nombre, nivel FROM ".SQL."estudios WHERE ID = '" . $cargo_ID . "' LIMIT 1", $link);
        while($r = mysql_fetch_array($result)){
                mysql_query("UPDATE ".SQL."estudios_users SET cargo = '0' WHERE ID_estudio = '" . $cargo_ID . "' AND user_ID = '".$user_ID."' LIMIT 1", $link);
                evento_log(12, $cargo_ID, $user_ID);
                $result = mysql_query("SELECT ID_estudio, 
(SELECT nivel FROM ".SQL."estudios WHERE ID = ".SQL."estudios_users.ID_estudio LIMIT 1) AS nivel
FROM ".SQL."estudios_users 
WHERE user_ID = '".$user_ID."' AND cargo = '1' 
ORDER BY nivel DESC
LIMIT 1", $link);
                while($r = mysql_fetch_array($result)){ $user_nivel_max = $r['nivel']; $user_nivel_sql = ", cargo = '" . $r['ID_estudio'] . "'"; }
                if (!$user_nivel_max) { $user_nivel_max = 1; $user_nivel_sql = ", cargo = ''"; }
                mysql_query("UPDATE users SET nivel = '" . $user_nivel_max . "'" . $user_nivel_sql . " WHERE ID = '".$user_ID."' LIMIT 1", $link);

                if ($evento_chat) { 
                        $result2 = mysql_query("SELECT nick FROM users WHERE ID = '".$user_ID."' LIMIT 1", $link);
                        while($r2 = mysql_fetch_array($result2)){ $nick_asignado = $r2['nick']; }
                        evento_chat('<b>[CARGO] '.crear_link(($sistema==true?'VirtualPol':$pol['nick'])).' quita</b> el cargo <img src="'.IMG.'cargos/'.$cargo_ID.'.gif" />'.$r['nombre'].' a '. crear_link($nick_asignado));
                }
        }
}


function enviar_email($user_ID, $asunto, $mensaje, $email='') {
	$cabeceras = "From: VirtualPol <".CONTACTO_EMAIL.">;\nReturn-Path: VirtualPol <".CONTACTO_EMAIL.">;\nX-Sender: VirtualPol <".CONTACTO_EMAIL.">;\n MIME-Version: 1.0;\nContent-type: text/html; charset=UTF-8\n";
	if (($user_ID) AND ($email == '')) {
		global $link;
		$result = mysql_query("SELECT email FROM users WHERE ID = '".$user_ID."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ $email = $r['email']; }
	}
	mail($email, $asunto, $mensaje, $cabeceras);
}

function pols_transferir($pols, $emisor_ID, $receptor_ID, $concepto, $pais='') {
        global $link, $pol;

        if (!$pais) { $sql = SQL; $pais = PAIS; } else { $sql = strtolower($pais).'_'; }

        $return = false;
        $pols = strval($pols);
        if (($pols != 0) AND ($concepto)) {
                $concepto = ucfirst($concepto);

                //quitar
                if ($emisor_ID > 0) {
                        mysql_query("UPDATE users SET pols = pols - " . $pols . " WHERE ID = '" . $emisor_ID . "' AND pais = '".$pais."' LIMIT 1", $link);
                } else {

                        if ($pol['nick']) { $concepto = '<b>'.$pol['nick'].'&rsaquo;</b> '.$concepto; }

                        mysql_query("UPDATE ".$sql."cuentas SET pols = pols - " . $pols . " WHERE ID = '" . substr($emisor_ID, 1) . "' LIMIT 1", $link);
                }

                //ingresar
                if ($receptor_ID > 0) {
                        mysql_query("UPDATE users SET pols = pols + " . $pols . " WHERE ID = '" . $receptor_ID . "' AND pais = '".$pais."' LIMIT 1", $link);
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
	$result3 = mysql_query("SELECT IP, pols, nick, ID, ref, estado".(ECONOMIA?",
	(SELECT SUM(pols) FROM ".SQL."cuentas WHERE user_ID = '" . $ID . "') AS pols_cuentas":"")." 
	FROM users 
	WHERE ID = '" . $ID . "' 
	LIMIT 1", $link);
	while($r3 = mysql_fetch_array($result3)) {
			$user_ID = $r3['ID']; 
			$estado = $r3['estado']; 
			$pols = ($r3['pols'] + $r3['pols_cuentas']); 
			$nick = $r3['nick']; 
			$ref = $r3['ref']; 
			$IP = $r3['IP'];
	}

	if ($user_ID) { // ELIMINAR CIUDADANO
			if (ECONOMIA) { pols_transferir($pols, $user_ID, '-1', '&dagger; Defuncion: <em>' . $nick . '</em>'); }

			if ((ECONOMIA) AND ($ref != '0')) { 
					mysql_query("UPDATE users SET ref_num = ref_num - 1 WHERE ID = '" . $ref . "' LIMIT 1", $link);
					mysql_query("DELETE FROM ".SQL_REFERENCIAS." WHERE IP = '" . $IP . "' OR user_ID = '" . $ref . "'", $link); 
			}
			mysql_query("DELETE FROM users WHERE ID = '".$user_ID."' LIMIT 1", $link);
			mysql_query("DELETE FROM ".SQL."partidos_listas WHERE user_ID = '".$user_ID."'", $link);
			mysql_query("DELETE FROM ".SQL."partidos WHERE ID_presidente = '".$user_ID."'", $link);
			mysql_query("DELETE FROM ".SQL."estudios_users WHERE user_ID = '".$user_ID."'", $link);
			mysql_query("DELETE FROM ".SQL."ban WHERE user_ID = '".$user_ID."'", $link);
			mysql_query("DELETE FROM chats WHERE user_ID = '".$user_ID."'", $link);
			mysql_query("DELETE FROM votos WHERE emisor_ID = '".$user_ID."' OR (tipo = 'confianza' AND item_ID = '".$user_ID."')", $link);
			mysql_query("DELETE FROM ".SQL."foros_msg WHERE user_ID = '".$user_ID."' AND hilo_ID = '-1'", $link);


			if (ECONOMIA) {
					mysql_query("DELETE FROM ".SQL_REFERENCIAS." WHERE user_ID = '".$user_ID."'", $link);
					mysql_query("DELETE FROM ".SQL."empresas WHERE user_ID = '".$user_ID."'", $link);
					mysql_query("DELETE FROM ".SQL."mercado WHERE user_ID = '".$user_ID."'", $link);
					mysql_query("DELETE FROM ".SQL."mapa WHERE user_ID = '".$user_ID."'", $link);
					mysql_query("DELETE FROM ".SQL."cuentas WHERE user_ID = '".$user_ID."'", $link);
			}

			$img_root = RAIZ.'/img/a/' . $user_ID;
			if (file_exists($img_root . '.jpg')) {
					@unlink($img_root . '.jpg');
					@unlink($img_root . '_40.jpg');
			}

			// anula el posible voto en elecciones
			if ($pol['config']['elecciones_estado'] == 'elecciones') {
					mysql_query("UPDATE ".SQL."elecciones SET ID_partido = '-1' WHERE user_ID = '".$user_ID."' LIMIT 1", $link);
			}

			// eliminar
			/* PENDIENTE DE ARREGLAR. CODIGO CORRECTO, EXCEPTO QUE NO DEBE BORRAR MENSAJES DE EXPULSADOS POR PETICION PROPIA.
			if ($estado == 'expulsado') { 
					mysql_query("DELETE FROM ".SQL."foros_msg WHERE user_ID = '".$user_ID."'", $link);
					mysql_query("DELETE FROM ".SQL."foros_hilos WHERE user_ID = '".$user_ID."'", $link);
			}
			*/
	}
}

// accion
function gen_title($title) {
	$title = strip_tags($title);
	return $title;
}
function gen_url($url) {
	if (mb_detect_encoding($url) != 'UTF-8') { $url = utf8_decode($url); }
	$url = trim($url);
	$url = strtr(utf8_decode($url), utf8_decode(' àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), utf8_decode('-aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'));
	$url = str_replace(array('&quot;', '&#39;'), '', $url);
	$url = ereg_replace("[^A-Za-z0-9-]", "", $url);
	$url = substr($url, 0, 90);
	$url = strip_tags($url);
	$url = strtolower($url);
	return $url;
}
function gen_text($text, $type='') {
	if (mb_detect_encoding($text) != 'UTF-8') { $text = utf8_encode($text); }
	$text = preg_replace('#(<[^>]+[\s\r\n\"\'])(on|xmlns)[^>]*>#iU', "$1>", $text); //prevent XSS
	if ($type == 'plain') {
		$text = strip_tags($text);
		$text = nl2br($text);
	} else {
		$text = strip_tags($text, "<img>,<b>,<i>,<s>,<embed>,<object>,<param>,<span>,<font>,<strong>,<p>,<b>,<em>,<ul>,<ol>,<li>,<blockquote>,<a>,<h2>,<h3>,<h4>,<br>,<hr>,<table>,<tr>,<td>,<th>");
		$text = str_replace("\n\n", "<br /><br />\n\n", $text); //LINUX
		$text = str_replace("\r\n\r\n", "<br /><br />\r\n\r\n", $text); //WINDOWS
	} 
	//acentos
	/*
	$mal = array(chr(183), chr(231), chr(199), chr(128), 'º', 'ª', '©', '®', '°', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ü', 'Ü', chr(191), '¡', 'à', 'è', 'ì', 'ò', 'ù', 'À', 'È', 'Ì', 'Ò', 'Ù');
	$ok = array('&#183;', '&#231;', '&#199;', '&#128;', '&ordm;', '&ordf;', '&copy;', '&reg;', '&deg;', '&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', '&ntilde;', '&Ntilde;', '&uuml;', '&Uuml;', '&iquest;', '&iexcl;', '&agrave;', '&egrave;', '&igrave;', '&ograve;', '&ugrave;', '&Agrave;', '&Egrave;', '&Igrave;', '&Ograve;', '&Ugrave;');
	$text = str_replace($mal, $ok, $text);
	*/

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
		imagealphablending($thumb, false);
		imagesavealpha($thumb, true);
		$source = imagecreatefrompng($imgfile);
	} else {
		$source = imagecreatefromjpeg($imgfile); 
	}
	imagecopyresampled($thumb,$source,0,0,0,0,$newwidth,$newheight,$width,$height);
	if ($format == 'png') {
		imagepng($thumb,$savePath,80);
	} else {
		imagejpeg($thumb,$savePath,80);
	}
}

function filtro_sql($a) {
	$a = str_replace('\'', '&#39;', $a);
	$a = str_replace('"', '&quot;', $a);
	return mysql_real_escape_string($a);
}

?>