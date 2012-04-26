<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

function indexar_i18n() {
	// Funcion inutil, cuyo unico fin es indexar textos del sistema en el sistema gettext de i18n
	
	// tipos votacion
	$null = _('sondeo')._('referendum')._('parlamento')._('cargo')._('elecciones');
	
	// Tipos nucleo acceso
	$null = _('privado') . _('excluir')._('afiliado')._('confianza')._('cargo')._('grupos') . _('nivel')._('antiguedad')._('autentificacion')._('supervisores_censo')._('ciudadanos') . _('ciudadanos_global')._('anonimos');
	
	// Estados votacion
	$null = _('ok')._('end')._('del')._('borrador');
	
	// Tiempos
	$null = _('años')._('meses')._('semanas')._('días')._('horas')._('minutos')._('segundos')._('Pocos segundos');

	// Otros
	$null = _('puntos')._('estandar')._('multiple')._('ninguno') . _('turista')._('extranjero')._('ciudadano')._('expulsado')._('validar')._('borrado')._('activo') . _('inactivo')._('cancelado')._('cancelar')._('En')._('Hace')._('min')._('seg');
}

function actualizar($accion, $user_ID=false) {
	global $pol, $link;
	if ($user_ID == false) { $user_ID = $pol['user_ID']; }
	switch ($accion) {
		
		case 'examenes':
			$data_array = array();
			$result = mysql_query("SELECT cargo_ID, (SELECT ID FROM examenes WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS examen_ID FROM cargos_users WHERE user_ID = '".$user_ID."' AND aprobado = 'ok'", $link);
			while($r = mysql_fetch_array($result)){ $data_array[] = $r['examen_ID']; }
			mysql_query("UPDATE users SET examenes = '".implode(' ', $data_array)."' WHERE ID = '".$user_ID."' LIMIT 1", $link);
			break;

		case 'cargos':
			$data_array = array();
			$result = mysql_query("SELECT cargo_ID FROM cargos_users WHERE user_ID = '".$user_ID."' AND cargo = 'true'", $link);
			while($r = mysql_fetch_array($result)){ $data_array[] = $r['cargo_ID']; }
			mysql_query("UPDATE users SET cargos = '".implode(' ', $data_array)."' WHERE ID = '".$user_ID."' LIMIT 1", $link);
			break;

		case 'contador_docs':
			$result = mysql_query("SELECT COUNT(ID) AS num FROM docs WHERE estado = 'ok' AND pais = '".PAIS."'", $link);
			while($r = mysql_fetch_array($result)) {
				mysql_query("UPDATE config SET valor = '".$r['num']."' WHERE pais = '".PAIS."' AND dato = 'info_documentos' LIMIT 1", $link);
			}
			break;
	}
}


function evento_log($accion, $es_sistema=false) {
	global $pol, $link, $_REQUEST;
	if (!isset($pol['user_ID'])) { $es_sistema = true; }
	if (PAIS == 'Ninguno') { $pais = $pol['pais']; } else { $pais = PAIS; }
	mysql_query("INSERT INTO log (pais, user_ID, nick, time, accion, accion_a) VALUES ('".$pais."', '".($es_sistema==false?$pol['user_ID']:0)."', '".($es_sistema==false?$pol['nick']:'Sistema')."', '".date('Y-m-d H:i:s')."', '".$accion."', '".$_REQUEST['a']."')", $link);
}

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

		case 'create': try { $e->createPad($ID, html_entity_decode(strip_tags(str_replace("<br />", "\n", $txt)), null, 'UTF-8')); return true; } catch (Exception $error) { return false; } break;
		case 'get': try { return $e->getHTML($ID)->html; } catch (Exception $error) { return false; } break;
		case 'delete': try { $e->deletePad($ID); return true; } catch (Exception $error) { return false; } break;
	}
}



// ELIMINACION DE TINYMCE EN CURSO
function editor_enriquecido($name, $txt='') {
        $GLOBALS['txt_header'] .= '
<script type="text/javascript" src="'.IMG.'tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
document.domain = "'.DOMAIN.'";
tinyMCE.init({
mode : "textareas",
theme : "advanced",
language : "es",
plugins : "style,table",
elements : "abshosturls",
relative_urls : false,
remove_script_host : false,
theme_advanced_buttons1 : "bold,italic,underline,|,strikethrough,sub,sup,charmap,|,forecolor,fontselect,fontsizeselect,|,link,unlink,image,|,undo,redo,|,cleanup,removeformat,code",
theme_advanced_buttons2 : "justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,blockquote,hr,|,tablecontrols",
theme_advanced_buttons3 : "",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
theme_advanced_statusbar_location : "bottom",
theme_advanced_resizing : true,
});
</script>';
        return '<textarea name="'.$name.'" style="width:750px;height:350px;">'.$txt.'</textarea>';
}

function evento_chat($msg, $user_ID='0', $chat_ID='', $secret=false, $tipo='e', $pais='', $nick=false) {
	global $pol, $link, $vp;
	if (!$nick) { $nick = $pol['nick']; }
	if (!$pais) { $pais = PAIS; }
	$chat_ID = $vp['paises_chat'][$pais];
	mysql_query("INSERT INTO chats_msg (chat_ID, nick, msg, cargo, user_ID, tipo) VALUES ('".$chat_ID."', '".$nick."', '".$msg."', '0', '".$user_ID."', '".$tipo."')", $link);
}


function cargo_add($cargo_ID, $user_ID, $evento_chat=true, $sistema=false) {
	global $link, $pol, $date; 
	$result = mysql_query("SELECT nombre, nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){
		
		$result2 = mysql_query("SELECT cargo_ID FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' AND user_ID = '".$user_ID."' LIMIT 1", $link);
		while($r2 = mysql_fetch_array($result2)){ $tiene_examen = true; }

		if ($tiene_examen) {
			mysql_query("UPDATE cargos_users SET cargo = 'true', aprobado = 'ok' WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' AND user_ID = '".$user_ID."' LIMIT 1", $link);
		} else {
			mysql_query("INSERT INTO cargos_users (cargo_ID, pais, user_ID, time, aprobado, cargo, nota) VALUES ('".$cargo_ID."', '".PAIS."', '".$user_ID."', '".$date."', 'ok', 'true', '0.0')", $link);
		}

		mysql_query("UPDATE users SET nivel = '".$r['nivel']."', cargo = '".$cargo_ID."' WHERE ID = '".$user_ID."' AND nivel < '".$r['nivel']."' LIMIT 1", $link);
		actualizar('cargos', $user_ID);

		if ($evento_chat) { 
			$result2 = mysql_query("SELECT nick FROM users WHERE ID = '".$user_ID."' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ $nick_asignado = $r2['nick']; }
			evento_chat('<b>[CARGO]</b> El cargo de <img src="'.IMG.'cargos/'.$cargo_ID.'.gif" /> '.$r['nombre'].' ha sido asignado a '.crear_link($nick_asignado).' por '.crear_link(($sistema==true?'VirtualPol':$pol['nick'])));
			notificacion($user_ID, 'Te ha sido asignado el cargo '.$r['nombre'], '/cargos');
		}
		evento_log('Cargo '.$r['nombre'].' asignado a '.$nick_asignado.' por '.($sistema==true?'VirtualPol':$pol['nick']));
	}
}

function cargo_del($cargo_ID, $user_ID, $evento_chat=true, $sistema=false) {
	global $link, $pol; 
	$result = mysql_query("SELECT nombre, nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){
		mysql_query("UPDATE cargos_users SET cargo = 'false' WHERE pais = '".PAIS."' AND cargo_ID = '" . $cargo_ID . "' AND user_ID = '".$user_ID."' LIMIT 1", $link);
		$result = mysql_query("SELECT cargo_ID, 
(SELECT nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS nivel
FROM cargos_users 
WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."' AND cargo = 'true' 
ORDER BY nivel DESC
LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ $user_nivel_max = $r['nivel']; $user_nivel_sql = ", cargo = '" . $r['cargo_ID'] . "'"; }
		if (!$user_nivel_max) { $user_nivel_max = 1; $user_nivel_sql = ", cargo = ''"; }
		mysql_query("UPDATE users SET nivel = '" . $user_nivel_max . "'" . $user_nivel_sql . " WHERE ID = '".$user_ID."' LIMIT 1", $link);
		actualizar('cargos', $user_ID);

		if ($evento_chat) { 
			$result2 = mysql_query("SELECT nick FROM users WHERE ID = '".$user_ID."' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ $nick_asignado = $r2['nick']; }
			evento_chat('<b>[CARGO] '.crear_link(($sistema==true?'VirtualPol':$pol['nick'])).' quita</b> el cargo <img src="'.IMG.'cargos/'.$cargo_ID.'.gif" />'.$r['nombre'].' a '. crear_link($nick_asignado));
		}
		evento_log('Cargo '.$r['nombre'].' quitado a '.$nick_asignado.' por '.($sistema==true?'VirtualPol':$pol['nick']));
	}
}




// NUEVA FUNCION DE CARGOS EN DESARROLLO
function cargo($accion, $cargo_ID, $user_ID, $evento_chat=true, $sistema=false) {
	global $link, $pol;
	

	switch ($accion) {
		case 'add':
			break;

		case 'del':
			break;

		case 'dimitir':
			break;

	}


	// OLD
	$result = mysql_query("SELECT nombre, nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){
		
		mysql_query("UPDATE cargos_users SET cargo = 'false' WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' AND user_ID = '".$user_ID."' LIMIT 1", $link);
		$result = mysql_query("SELECT cargo_ID, 
(SELECT nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS nivel
FROM cargos_users 
WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."' AND cargo = 'true' 
ORDER BY nivel DESC
LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ $user_nivel_max = $r['nivel']; $user_nivel_sql = ", cargo = '" . $r['cargo_ID'] . "'"; }


		if (!$user_nivel_max) { $user_nivel_max = 1; $user_nivel_sql = ", cargo = ''"; }
		mysql_query("UPDATE users SET nivel = '" . $user_nivel_max . "'" . $user_nivel_sql . " WHERE ID = '".$user_ID."' LIMIT 1", $link);
		actualizar('cargos', $user_ID);

		if ($evento_chat) { 
			$result2 = mysql_query("SELECT nick FROM users WHERE ID = '".$user_ID."' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ $nick_asignado = $r2['nick']; }
			evento_chat('<b>[CARGO] '.crear_link(($sistema==true?'VirtualPol':$pol['nick'])).' quita</b> el cargo <img src="'.IMG.'cargos/'.$cargo_ID.'.gif" />'.$r['nombre'].' a '. crear_link($nick_asignado));
		}
		evento_log('Cargo '.$r['nombre'].' quitado a '.$nick_asignado.' por '.($sistema==true?'VirtualPol':$pol['nick']));
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

		mysql_query("INSERT INTO transacciones (pais, pols, emisor_ID, receptor_ID, concepto, time) VALUES ('".$pais."', " . $pols . ", '" . $emisor_ID . "', '" . $receptor_ID . "', '" . $concepto . "', '" . date('Y-m-d H:i:s') . "')", $link);
		notificacion($receptor_ID, 'Te han transferido '.$pols.' monedas', '/pols');
		$return = true;
	}
	return $return;
}

function eliminar_ciudadano($ID) {
	global $link, $pol;
	$user_ID = false;
	$result3 = mysql_query("SELECT IP, pols, nick, ID, ref, estado".(ECONOMIA?",
(SELECT SUM(pols) FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$ID."') AS pols_cuentas":"")." 
FROM users 
WHERE ID = '".$ID."' 
LIMIT 1", $link);
	while($r3 = mysql_fetch_array($result3)) {
		$user_ID = $r3['ID']; 
		$estado = $r3['estado']; 
		$pols = ($r3['pols'] + $r3['pols_cuentas']); 
		$nick = $r3['nick']; 
		$ref = $r3['ref']; 
		$IP = $r3['IP'];
	}

	if (is_numeric($user_ID)) { 
		// ELIMINAR CIUDADANO

		if (ECONOMIA) { pols_transferir($pols, $user_ID, '-1', '&dagger; Defuncion: <em>'.$nick.'</em>'); }

		if ((ECONOMIA) AND ($ref != '0')) { 
			mysql_query("UPDATE users SET ref_num = ref_num - 1 WHERE ID = '".$ref."' LIMIT 1", $link);
		}
		mysql_query("DELETE FROM users WHERE ID = '".$user_ID."' LIMIT 1", $link);
		mysql_query("DELETE FROM partidos_listas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM partidos WHERE pais = '".PAIS."' AND ID_presidente = '".$user_ID."'", $link);
		mysql_query("DELETE FROM cargos_users WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM kicks WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM chats WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM votos WHERE emisor_ID = '".$user_ID."' OR (tipo = 'confianza' AND item_ID = '".$user_ID."')", $link);
		mysql_query("DELETE FROM ".SQL."foros_msg WHERE user_ID = '".$user_ID."' AND hilo_ID = '-1'", $link);

		mysql_query("DELETE FROM referencias WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM empresas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM mapa WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM referencias WHERE IP = '".$IP."' OR user_ID = '".$ref."'", $link); 

		$img_root = RAIZ.'/img/a/'.$user_ID;
		if (file_exists($img_root.'.jpg')) {
			@unlink($img_root.'.jpg');
			@unlink($img_root.'_40.jpg');
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
		//$text = nl2br($text);
		$text = str_replace("\n\n", "<br /><br />\n\n", $text); //LINUX
		$text = str_replace("\r\n\r\n", "<br /><br />\r\n\r\n", $text); //WINDOWS
	} else {
		$text = strip_tags($text, "<img>,<b>,<i>,<s>,<embed>,<object>,<param>,<span>,<font>,<strong>,<p>,<b>,<em>,<ul>,<ol>,<li>,<blockquote>,<a>,<h2>,<h3>,<h4>,<br>,<hr>,<table>,<tr>,<td>,<th>");
		$text = str_replace("\n\n", "<br /><br />\n\n", $text); //LINUX
		$text = str_replace("\r\n\r\n", "<br /><br />\r\n\r\n", $text); //WINDOWS
	} 

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


function barajar_votos($votacion_ID) { // FUNCION CRITICA. Especialmente comentada.
	global $link;

	// El objetivo de esta funcion es barajar los votos de forma que quede rota la relación Usuario-Voto.

	// Comprueba que la votacion está terminada y los votos no son publicos (para evitar corrupciones)
	$result = mysql_query("SELECT privacidad FROM votacion WHERE ID = '".$votacion_ID."' AND estado = 'end' AND privacidad = 'true' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){ $ok = $r['privacidad']; }
	if ($ok != 'true') { return false; }

	
	// Extrae los IDs de votos y los guarda en array.
	$votos = array();
	$n = 0;
	$result = mysql_query("SELECT * FROM votacion_votos WHERE ref_ID = '".$votacion_ID."'", $link);
	while($r = mysql_fetch_array($result)){ 
		$n++;
		$votos[$n]['ID'] = $r['ID'];
	}

	// Extrae los datos a barajar de la tabla de votos, ya ordenados aleatoriamente.
	$n = 0;
	$result = mysql_query("SELECT * FROM votacion_votos WHERE ref_ID = '".$votacion_ID."' ORDER BY RAND()", $link);
	while($r = mysql_fetch_array($result)){ 
		$n++;
		$votos[$n]['voto'] = $r['voto'];
		$votos[$n]['validez'] = $r['validez'];
		$votos[$n]['autentificado'] = $r['autentificado'];
		$votos[$n]['mensaje'] = $r['mensaje'];
		$votos[$n]['comprobante'] = $r['comprobante'];
	}

	// Recorre el array para volver a guardar los mismos datos, pero barajados.
	foreach ($votos AS $null => $voto) {
		$sql_set = array();
		foreach ($voto AS $dato => $valor) {
			if ($dato == 'ID') { $voto_ID = $valor; } 
			else { $sql_set[] = "".$dato." = '".str_replace("'", "", $valor)."'"; }
		}
		mysql_query("UPDATE votacion_votos SET ".implode(', ', $sql_set)." WHERE ID = '".$voto_ID."' LIMIT 1", $link);
	}
	return true;
}

function distancia($lat1, $lng1, $lat2, $lng2, $dec=0) {
	$pi80 = M_PI / 180;
	$lat1 *= $pi80;
	$lng1 *= $pi80;
	$lat2 *= $pi80;
	$lng2 *= $pi80;
	$r = 6372.797; // mean radius of Earth in km
	$dlat = $lat2 - $lat1;
	$dlng = $lng2 - $lng1;
	$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	$km = $r * $c;
	return round($km, $dec);
}


function form_select_cat($tipo='docs', $cat_now='') {
	global $pol, $link;
	$f .= '<select name="cat">';
	$result = sql("
SELECT ID, nombre, nivel
FROM cat
WHERE pais = '".PAIS."' AND tipo = '" . $tipo . "'
ORDER BY orden ASC", $link);
	while($row = r($result)){
		if ($cat_now == $row['ID']) { 
			$selected = ' selected="selected"'; 
		} elseif ($pol['nivel'] < $row['nivel']) {
			$selected = ' disabled="disabled"'; 
			$row['nombre'] = $row['nombre'] . ' (Nivel: ' . $row['nivel'] . ')';
		} else { 
			$selected = ''; 
		}
		$f .= '<option value="' . $row['ID'] . '"' . $selected . '>' . $row['nombre'] . '</option>' . "\n";
	}
	$f .= '</select>';
	return $f;
}

?>