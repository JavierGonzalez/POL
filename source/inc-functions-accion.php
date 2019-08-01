<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

/*
function api_facebook($accion, $item_ID, $sistema=false) {
	// DOCUMENTACION FB
//GRAPH API - https://developers.facebook.com/docs/reference/api/message/
//OBTENER TOKENS - http://www.damnsemicolon.com/php/auto-post-facebook-with-facebook-sdk

	require_once('../img/lib/facebook-php/facebook.php');
	global $date, $pol;

	$facebook = new Facebook(array(
		'appId'  => FB_APIKEY,
		'secret' => FB_SECRET,
		'cookie' => true,
	));
	$pub = false;
	$result = sql("SELECT *, 
(SELECT item_ID FROM api WHERE api_ID = api_posts.api_ID AND estado = 'activo' LIMIT 1) AS item_ID, 
(SELECT clave FROM api WHERE api_ID = api_posts.api_ID AND estado = 'activo' LIMIT 1) AS clave, 
(SELECT acceso_escribir FROM api WHERE api_ID = api_posts.api_ID AND estado = 'activo' LIMIT 1) AS acceso_escribir,
(SELECT pais FROM api WHERE api_ID = api_posts.api_ID AND estado = 'activo' LIMIT 1) AS api_pais,
(SELECT nombre FROM api WHERE api_ID = api_posts.api_ID AND estado = 'activo' LIMIT 1) AS nombre
FROM api_posts WHERE post_ID = '".$item_ID."' LIMIT 1");
	while ($r = r($result)) {
		$user_ID = ($sistema?$r['publicado_user_ID']:$pol['user_ID']);
		if ((isset($r['clave'])) AND ((nucleo_acceso($r['acceso_escribir'])) OR ($sistema))) {
			if (($accion == 'publicar') AND ($r['estado'] != 'publicado')) {
				if (strtotime($date) >= strtotime($r['time_cron'])) {
					$content_array['clave'] = $r['clave'];
					$content_array['message'] = trim(strip_tags($r['message']));

					
					if ($r['link'] != '') { $content_array['type'] = 'link'; $content_array['link'] = $r['link']; } // $content_array['name'] = $r['name'];
					if ($r['picture'] != '') { $content_array['type'] = 'photo'; $content_array['picture'] = $r['picture']; }
					
					$pub = $facebook->api('/'.$r['item_ID'].'/'.($r['link']==''?'feed':'links'), 'POST', $content_array);
					if (!stristr($pub['id'], '_')) { $pub['id'] = $r['item_ID'].'_'.$pub['id']; }
				} else {
					sql("UPDATE api_posts SET estado = 'cron', time = '".$date."', publicado_user_ID = '".$user_ID."' WHERE post_ID = '".$r['post_ID']."' LIMIT 1");
					return true;
				}
				if ($pub != false) {
					sql("UPDATE api_posts SET estado = 'publicado', time = '".$date."', mensaje_ID = '".$pub['id']."', publicado_user_ID = '".$user_ID."' WHERE post_ID = '".$r['post_ID']."' LIMIT 1");
					if ($r['api_pais'] == PAIS) {
						evento_chat('<b>[API]</b> Publicación de contenido en <a href="/api/'.$r['item_ID'].'">'.$r['nombre'].'</a> <span class="gris">('.$pol['nick'].', <a href="https://www.facebook.com/permalink.php?story_fbid='.explodear('_', $pub['id'], 1).'&id='.$r['item_ID'].'">ver contenido</a>, Facebook)</span>');
					}
					return true;
				} else { return false; }

			} elseif ($accion == 'borrar') {
				sql("UPDATE api_posts SET estado = 'pendiente', time = '".$date."', borrado_user_ID = '".$user_ID."' WHERE post_ID = '".$r['post_ID']."' LIMIT 1");
				$pub = $facebook->api('/'.$r['mensaje_ID'], 'DELETE', array('access_token'=>$r['clave']));
				return true; 
			}
		}
	}
}
*/


function publicar_documento($id, $txt){
	error_log('Publicar documento SQL: SELECT c.publicar FROM docs d, cat c WHERE d.cat_id=c.id AND d.id='.$id.' LIMIT 1');
	$result = sql("SELECT c.publicar FROM docs d, cat c WHERE d.cat_id=c.id AND d.id='".$id."' LIMIT 1");
	while ($r = r($result)){
	error_log('resultado: '.$r['c.publicar']);
		if ($r['publicar']){
			evento_chat($txt);
		}
	}
}

function actualizar($accion, $user_ID=false) {
	global $pol, $link;
	if ($user_ID == false) { $user_ID = $pol['user_ID']; }
	switch ($accion) {
		
		case 'votaciones':
			$result = sql("SELECT COUNT(ID) AS num FROM votacion WHERE estado = 'ok' AND pais = '".PAIS."' AND acceso_ver = 'anonimos'");
			while($r = r($result)) {
				sql("UPDATE config SET valor = '".$r['num']."' WHERE pais = '".PAIS."' AND dato = 'info_consultas' LIMIT 1");
			}
			break;

		case 'examenes':
			$data_array = array();
			$result = sql("SELECT cargo_ID, (SELECT ID FROM examenes WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS examen_ID FROM cargos_users WHERE user_ID = '".$user_ID."' AND aprobado = 'ok'");
			while($r = r($result)){ $data_array[] = $r['examen_ID']; }
			sql("UPDATE users SET examenes = '".implode(' ', $data_array)."' WHERE ID = '".$user_ID."' LIMIT 1");
			break;

		case 'cargos':
			$data_array = array();
			$result = sql("SELECT cargo_ID FROM cargos_users WHERE user_ID = '".$user_ID."' AND cargo = 'true'");
			while($r = r($result)){ $data_array[] = $r['cargo_ID']; }
			sql("UPDATE users SET cargos = '".implode(' ', $data_array)."' WHERE ID = '".$user_ID."' LIMIT 1");
			break;

		case 'contador_docs':
			$result = sql("SELECT COUNT(ID) AS num FROM docs WHERE estado = 'ok' AND pais = '".PAIS."'");
			while($r = r($result)) {
				sql("UPDATE config SET valor = '".$r['num']."' WHERE pais = '".PAIS."' AND dato = 'info_documentos' LIMIT 1");
			}
			break;
	}
}


function evento_log($accion, $es_sistema=false) {
	global $pol, $link, $_REQUEST;
	if (!isset($pol['user_ID'])) { $es_sistema = true; }
	if (PAIS == 'Ninguno') { $pais = $pol['pais']; } else { $pais = PAIS; }
	sql("INSERT INTO log (pais, user_ID, nick, time, accion, accion_a) VALUES ('".$pais."', '".($es_sistema==false?$pol['user_ID']:0)."', '".($es_sistema==false?$pol['nick']:'Sistema')."', '".date('Y-m-d H:i:s')."', '".$accion."', '".(substr($accion, 0, 6)=='Cargo '?'cargo':$_REQUEST['a'])."')");
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

'.str_replace('&#x2F;', '/', str_replace('&quot;', '"', str_replace('&gt;', '>', str_replace('&lt;', '<', strip_tags($html))))).'


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


/*
Sustitución de etherpad-lite por editor de markdown
function pad($control, $ID=false, $txt='') {
	if ($control != 'print') {
		include('../img/lib/etherpad-lite/etherpad-lite-client.php');
		$e = new EtherpadLiteClient(CLAVE_API_ETHERPAD, 'http://www.'.DOMAIN.':9001/api');
	}
	switch ($control) {
		case 'print':
			global $pol;
			return '<iframe src="http://www.virtualpol.com:9001/p/'.$ID.'?userName='.$pol['nick'].'" width="100%" height="500" frameborder="0" style="background:#FFF;margin:0 -20px -9px -20px;"></iframe>';
			break;

		case 'create': try { $e->createPad($ID, html_entity_decode(strip_tags(str_replace("<br />", "\n", $txt)), null, 'UTF-8')); return true; } catch (Exception $error) { return false; } break;
		case 'get': try { return $e->getHTML($ID)->html; } catch (Exception $error) { return false; } break;
		case 'delete': try { $e->deletePad($ID); return true; } catch (Exception $error) { return false; } break;
	}
}
*/

function pad($control, $ID=false, $txt='') {
	switch ($control) {
		case 'print':
			return '<script>var converter = new showdown.Converter();'
			.'html      = converter.makeHtml('.$txt.');'
			.'</script>';
			break;

		case 'create': 
			$GLOBALS['txt_footer'] .= '<script>'
			.'var simplemde = new EasyMDE({ element: document.getElementById("document_frame").contentWindow.document.getElementById("document_body") ,
				renderingConfig: {
					singleLineBreaks: false,
					codeSyntaxHighlighting: true,
				},
				spellChecker: false
			});'
			/*.'$(\'#editar_documento\').submit(function(event) {'
			.'event.preventDefault();'
			.'$(\'input.html_doc\').val(simplemde.value());'
			.'$(this).unbind(\'submit\').submit();'
			.'return true'
			.'});'*/
			.'</script>';
			return '<textarea id="document_body" style="display: none;">'.$txt.'</textarea>';
			break;
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
	
	$result = sql("SELECT chat_ID FROM chats WHERE pais = '".$pais."' AND user_ID = '0' ORDER BY fecha_creacion ASC LIMIT 1");
	while($r = r($result)){ $chat_ID = $r['chat_ID']; }

	sql("INSERT INTO chats_msg (chat_ID, nick, msg, cargo, user_ID, tipo) VALUES ('".$chat_ID."', '".($secret==false?$nick:'')."', '".$msg."', '0', '".$user_ID."', '".$tipo."')");
}


function cargo_add($cargo_ID, $user_ID, $evento_chat=true, $sistema=false) {
	global $link, $pol, $date; 
	$result = sql("SELECT nombre, nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' LIMIT 1");
	while($r = r($result)){
		
		$result2 = sql("SELECT cargo_ID FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' AND user_ID = '".$user_ID."' LIMIT 1");
		while($r2 = r($result2)){ $tiene_examen = true; }

		if ($tiene_examen) {
			sql("UPDATE cargos_users SET cargo = 'true', aprobado = 'ok' WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' AND user_ID = '".$user_ID."' LIMIT 1");
		} else {
			sql("INSERT INTO cargos_users (cargo_ID, pais, user_ID, time, aprobado, cargo, nota) VALUES ('".$cargo_ID."', '".PAIS."', '".$user_ID."', '".$date."', 'ok', 'true', '0.0')");
		}

		sql("UPDATE users SET nivel = '".$r['nivel']."', cargo = '".$cargo_ID."' WHERE ID = '".$user_ID."' AND nivel < '".$r['nivel']."' LIMIT 1");
		actualizar('cargos', $user_ID);

		if ($evento_chat) { 
			$result2 = sql("SELECT nick FROM users WHERE ID = '".$user_ID."' LIMIT 1");
			while($r2 = r($result2)){ $nick_asignado = $r2['nick']; }
			evento_chat('<b>[CARGO]</b> El cargo de <img src="'.IMG.'cargos/'.$cargo_ID.'.gif" /> '.$r['nombre'].' ha sido asignado a '.crear_link($nick_asignado).' por '.crear_link(($sistema==true?'VirtualPol':$pol['nick'])));
			notificacion($user_ID, 'Te ha sido asignado el cargo '.$r['nombre'], '/cargos');
		}
		evento_log('Cargo '.$r['nombre'].' asignado a @'.$nick_asignado.' por '.($sistema==true?'VirtualPol':'@'.$pol['nick']));
	}
}

function cargo_del($cargo_ID, $user_ID, $evento_chat=true, $sistema=false) {
	global $link, $pol; 
	$result = sql("SELECT nombre, nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' LIMIT 1");
	while($r = r($result)){
		sql("UPDATE cargos_users SET cargo = 'false' WHERE pais = '".PAIS."' AND cargo_ID = '" . $cargo_ID . "' AND user_ID = '".$user_ID."' LIMIT 1");
		$result = sql("SELECT cargo_ID, 
(SELECT nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS nivel
FROM cargos_users 
WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."' AND cargo = 'true' 
ORDER BY nivel DESC
LIMIT 1");
		while($r = r($result)){ $user_nivel_max = $r['nivel']; $user_nivel_sql = ", cargo = '" . $r['cargo_ID'] . "'"; }
		if (!$user_nivel_max) { $user_nivel_max = 1; $user_nivel_sql = ", cargo = ''"; }
		sql("UPDATE users SET nivel = '" . $user_nivel_max . "'" . $user_nivel_sql . " WHERE ID = '".$user_ID."' LIMIT 1");
		actualizar('cargos', $user_ID);

		$result2 = sql("SELECT nick FROM users WHERE ID = '".$user_ID."' LIMIT 1");
		while($r2 = r($result2)){ $nick_asignado = $r2['nick']; }

		if ($evento_chat) { 	
			evento_chat('<b>[CARGO] '.crear_link(($sistema==true?'VirtualPol':$pol['nick'])).' quita</b> el cargo <img src="'.IMG.'cargos/'.$cargo_ID.'.gif" />'.$r['nombre'].' a '. crear_link($nick_asignado));
		}
		evento_log('Cargo '.$r['nombre'].' quitado a @'.$nick_asignado.' por '.($sistema==true?'VirtualPol':'@'.$pol['nick']));
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
	$result = sql("SELECT nombre, nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' LIMIT 1");
	while($r = r($result)){
		
		sql("UPDATE cargos_users SET cargo = 'false' WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' AND user_ID = '".$user_ID."' LIMIT 1");
		$result = sql("SELECT cargo_ID, 
(SELECT nivel FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS nivel
FROM cargos_users 
WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."' AND cargo = 'true' 
ORDER BY nivel DESC
LIMIT 1");
		while($r = r($result)){ $user_nivel_max = $r['nivel']; $user_nivel_sql = ", cargo = '" . $r['cargo_ID'] . "'"; }


		if (!$user_nivel_max) { $user_nivel_max = 1; $user_nivel_sql = ", cargo = ''"; }
		sql("UPDATE users SET nivel = '" . $user_nivel_max . "'" . $user_nivel_sql . " WHERE ID = '".$user_ID."' LIMIT 1");
		actualizar('cargos', $user_ID);

		if ($evento_chat) { 
			$result2 = sql("SELECT nick FROM users WHERE ID = '".$user_ID."' LIMIT 1");
			while($r2 = r($result2)){ $nick_asignado = $r2['nick']; }
			evento_chat('<b>[CARGO] '.crear_link(($sistema==true?'VirtualPol':$pol['nick'])).' quita</b> el cargo <img src="'.IMG.'cargos/'.$cargo_ID.'.gif" />'.$r['nombre'].' a '. crear_link($nick_asignado));
		}
		evento_log('Cargo '.$r['nombre'].' quitado a @'.$nick_asignado.' por '.($sistema==true?'VirtualPol':'@'.$pol['nick']));
	}
}




function enviar_email($user_ID, $asunto, $mensaje, $email='') {
	$cabeceras = "From: VirtualPol <".CONTACTO_EMAIL.">;\nReturn-Path: VirtualPol <".CONTACTO_EMAIL.">;\nX-Sender: VirtualPol <".CONTACTO_EMAIL.">;\n MIME-Version: 1.0;\nContent-type: text/html; charset=UTF-8\n";
	if (($user_ID) AND ($email == '')) {
		global $link;
		$result = sql("SELECT email FROM users WHERE ID = '".$user_ID."' LIMIT 1");
		while($r = r($result)){ $email = $r['email']; }
	}
	mail($email, $asunto, $mensaje, $cabeceras);
}

function pols_transferir($pols, $emisor_ID, $receptor_ID, $concepto, $pais=false) {
	global $link, $pol;

	if ($pais == false) { $pais = PAIS; }

	$return = false;
	$pols = strval($pols);
	if ((is_numeric($pols)) AND ($pols != 0) AND ($concepto)) {
		$concepto = ucfirst(strip_tags($concepto));

		//quitar
		if ($emisor_ID > 0) {
			sql("UPDATE users SET pols = pols - ".$pols." WHERE ID = '".$emisor_ID."' AND pais = '".$pais."' LIMIT 1");
		} else {
			if (isset($pol['nick'])) { $concepto = '<b>'.$pol['nick'].'&rsaquo;</b> '.$concepto; }
			sql("UPDATE cuentas SET pols = pols - ".$pols." WHERE ".($emisor_ID==-1?"gobierno = 'true'":"ID = '".substr($emisor_ID, 1)."'")." AND pais = '".$pais."' LIMIT 1");
		}

		//ingresar
		if ($receptor_ID > 0) {
			sql("UPDATE users SET pols = pols + ".$pols." WHERE ID = '".$receptor_ID."' AND pais = '".$pais."' LIMIT 1");
		} else {
			sql("UPDATE cuentas SET pols = pols + ".$pols." WHERE ".($receptor_ID==-1?"gobierno = 'true'":"ID = '".substr($receptor_ID, 1)."'")." AND pais = '".$pais."' LIMIT 1");
		}

		sql("INSERT INTO transacciones (pais, pols, emisor_ID, receptor_ID, concepto, time) VALUES ('".$pais."', ".$pols.", '".$emisor_ID."', '".$receptor_ID."', '".$concepto."', '".date('Y-m-d H:i:s')."')");
		if ($receptor_ID > 0) { notificacion($receptor_ID, 'Te han transferido '.$pols.' monedas', '/pols'); }
		$return = true;
	}
	return $return;
}

function eliminar_ciudadano($ID) {
	global $link, $pol;
	$user_ID = false;
	$result3 = sql("SELECT IP, pols, nick, ID, ref, estado".(ECONOMIA?",
(SELECT SUM(pols) FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$ID."') AS pols_cuentas":"")." 
FROM users 
WHERE ID = '".$ID."' 
LIMIT 1");
	while($r3 = r($result3)) {
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
			sql("UPDATE users SET ref_num = ref_num - 1 WHERE ID = '".$ref."' LIMIT 1");
		}
		sql("DELETE FROM users WHERE ID = '".$user_ID."' LIMIT 1");
		sql("DELETE FROM users_con WHERE user_ID = '".$user_ID."'");
		sql("DELETE FROM partidos_listas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
		sql("DELETE FROM partidos WHERE pais = '".PAIS."' AND ID_presidente = '".$user_ID."'");
		sql("DELETE FROM cargos_users WHERE user_ID = '".$user_ID."'");
		sql("DELETE FROM kicks WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
		sql("DELETE FROM chats WHERE user_ID = '".$user_ID."'");
		sql("DELETE FROM votos WHERE emisor_ID = '".$user_ID."' OR (tipo = 'confianza' AND item_ID = '".$user_ID."')");
		sql("DELETE FROM ".SQL."foros_msg WHERE user_ID = '".$user_ID."' AND hilo_ID = '-1'");
		sql("DELETE FROM users_con WHERE user_ID = '".$user_ID."'");

		sql("DELETE FROM referencias WHERE user_ID = '".$user_ID."'");
		sql("DELETE FROM empresas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
		sql("DELETE FROM mapa WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
		sql("DELETE FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
		sql("DELETE FROM referencias WHERE IP = '".$IP."' OR user_ID = '".$ref."'"); 

		$img_root = RAIZ.'/img/a/'.$user_ID;
		if (file_exists($img_root.'.jpg')) {
			@unlink($img_root.'.jpg');
			@unlink($img_root.'_40.jpg');
		}

		// eliminar
		/* PENDIENTE DE ARREGLAR. CODIGO CORRECTO, EXCEPTO QUE NO DEBE BORRAR MENSAJES DE EXPULSADOS POR PETICION PROPIA.
		if ($estado == 'expulsado') { 
				sql("DELETE FROM ".SQL."foros_msg WHERE user_ID = '".$user_ID."'");
				sql("DELETE FROM ".SQL."foros_hilos WHERE user_ID = '".$user_ID."'");
		}
		*/
	}
}

function convertir_turista($ID) {
	global $link, $pol;
	$user_ID = false;
	$result3 = sql("SELECT IP, pols, nick, ID, ref, estado".(ECONOMIA?",
(SELECT SUM(pols) FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$ID."') AS pols_cuentas":"")." 
FROM users 
WHERE ID = '".$ID."' 
LIMIT 1");
	while($r3 = r($result3)) {
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

		sql("UPDATE users set pais='ninguno', estado='turista' WHERE ID = '".$user_ID."' LIMIT 1");
		sql("DELETE FROM partidos WHERE pais = '".PAIS."' AND ID_presidente = '".$user_ID."'");
		sql("DELETE FROM cargos_users WHERE user_ID = '".$user_ID."'");
		sql("DELETE FROM chats WHERE user_ID = '".$user_ID."'");

		sql("DELETE FROM empresas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
		sql("DELETE FROM mapa WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
		sql("DELETE FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
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
		// $text = strip_tags($text, '<br>');
		$text = strip_tags($text);
	} else {
		$text = strip_tags($text, "<br>,<img>,<b>,<i>,<s>,<embed>,<object>,<param>,<span>,<font>,<strong>,<p>,<b>,<em>,<ul>,<ol>,<li>,<blockquote>,<a>,<h2>,<h3>,<h4>,<br>,<hr>,<table>,<tr>,<td>,<th>");
	}
	$text = nl2br($text);
	return $text;
}

function imageCompression($imgfile='', $thumbsize=0, $savePath=NULL, $format='jpeg', $o_width=false, $o_height=false) {
	list($width,$height) = getimagesize($imgfile);
	$newwidth = $thumbsize;
	$newheight = $thumbsize;

	if ($o_width != false) {
		$newwidth = $o_width;
		$newheight = $o_height;
	}

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
		imagepng($thumb,$savePath,85);
	} else {
		imagejpeg($thumb,$savePath,85);
	}
}


function barajar_votos($votacion_ID) { // FUNCION CRITICA. Especialmente comentada.
	global $link;

	// El objetivo de esta funcion es barajar los votos de forma que quede rota la relación Usuario-Voto.

	// Comprueba que la votacion está terminada y los votos no son publicos (para evitar corrupciones)
	$result = sql("SELECT privacidad FROM votacion WHERE ID = '".$votacion_ID."' AND estado = 'end' AND privacidad = 'true' LIMIT 1");
	while($r = r($result)){ $ok = $r['privacidad']; }
	if ($ok != 'true') { return false; }

	
	// Extrae los IDs de votos y los guarda en array.
	$votos = array();
	$n = 0;
	$result = sql("SELECT * FROM votacion_votos WHERE ref_ID = '".$votacion_ID."'");
	while($r = r($result)){ 
		$n++;
		$votos[$n]['ID'] = $r['ID'];
	}

	// Extrae los datos a barajar de la tabla de votos, ya ordenados aleatoriamente.
	$n = 0;
	$result = sql("SELECT * FROM votacion_votos WHERE ref_ID = '".$votacion_ID."' ORDER BY RAND()");
	while($r = r($result)){ 
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
		sql("UPDATE votacion_votos SET ".implode(', ', $sql_set)." WHERE ID = '".$voto_ID."' LIMIT 1");
	}
	
	// Elimina relación usuario-voto también en los argumentos de votacion
	sql("UPDATE votacion_argumentos SET user_ID = 0 WHERE ref_ID = '".$votacion_ID."'");
	
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
ORDER BY orden ASC");
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

function users_con($user_ID, $extra='', $tipo='session', $rejs=false) {
	$IP = direccion_IP('longip');
	$host = strtolower(gethostbyaddr(long2ip($IP))); if ($host == '') { $host = long2ip($IP); }
	$extra_array = explode('|', $extra); // res1|res2|login_seg|bitdepth|dispositivo

	if (!is_numeric(substr($host, -1, 1))) {
		$hoste = explode('.', $host);
		$ISP = ucfirst($hoste[count($hoste)-(in_array($hoste[count($hoste)-2], array('com', 'net', 'org'))?3:2)]).(!in_array($hoste[count($hoste)-1], array('com', 'net'))?' '.strtoupper($hoste[count($hoste)-1]):'');
		if (substr(long2ip($IP), 0, 10) == '80.58.205.') { $ISP = 'CanguroNet (proxy)'; }
		elseif ((stristr($host, 'proxy')) OR (stristr($host, 'cache')) OR (stristr($host, 'server'))) { $ISP .= ' (proxy)'; }
		elseif ((stristr($host, 'dyn')) OR stristr($host, 'pool')) { $ISP .= ' (dynamic)'; }
		elseif ((stristr($host, 'static')) OR (stristr($host, 'client'))) { $ISP .= ' (static)'; }
		elseif (stristr($host, 'cable')) { $ISP .= ' (cable)'; }
		elseif (stristr($host, 'dsl')) { $ISP .= ' (adsl)'; }
		elseif (stristr($host, 'wimax')) { $ISP .= ' (wimax)'; }
		if ((stristr($host, 'vpn')) OR (stristr($host, 'vps')) OR (stristr($host, 'www'))) { $ISP = 'Ocultado (VPN)'; } 
		if ((stristr($host, 'tor')) OR (stristr($host, 'anon')) OR (stristr($host, 'exit')) OR (stristr($host, 'onion'))) { $ISP = 'Ocultado (TOR)'; }
		$ISP = "'".$ISP."'";
	} else { $ISP = "NULL"; }


	$la_IP = explode('.', long2ip($IP));
	$result = sql("SELECT IP_pais FROM users_con WHERE IP_rango = '".$la_IP[0].".".$la_IP[1]."' LIMIT 1");
	while($r = r($result)){ $el_pais = "'".$r['IP_pais']."'"; }
	if (strlen($hoste[count($hoste)-1]) == 2) { $el_pais = "'".strtoupper($hoste[count($hoste)-1])."'"; }
	if ((!$el_pais) AND (CLAVE_API_ipinfodb != '...')) { 
		$res = file_get_contents('http://api.ipinfodb.com/v3/ip-city/?key='.CLAVE_API_ipinfodb.'&ip='.$la_IP[0].'.'.$la_IP[1].'.1.1');
		$res = strtoupper(explodear(';', $res, 3));
		if (strlen($res) != 2) { $res = '??'; }
		$el_pais = "'".$res."'";
	}

	$_SERVER['HTTP_X_FORWARDED_FOR'] = (filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)&&substr($_SERVER['HTTP_X_FORWARDED_FOR'], 0, 3)!='127'?$_SERVER['HTTP_X_FORWARDED_FOR']:'');
	if (($_SERVER['HTTP_X_FORWARDED_FOR'] != '') AND (substr(long2ip($IP), 0, 10) == '80.58.205.')) { $IP = ip2long($_SERVER['HTTP_X_FORWARDED_FOR']); }

	$i = get_browser(null, true);

	sql("INSERT INTO users_con (user_ID, time, IP, host, proxy, nav, login_ms, login_seg, nav_resolucion, ISP, tipo, nav_so, IP_pais, IP_rango, IP_rango3, dispositivo) 
VALUES ('".$user_ID."', '".date('Y-m-d H:i:s')."', '".$IP."', '".$host."', '".$_SERVER['HTTP_X_FORWARDED_FOR']."', '".$_SERVER['HTTP_USER_AGENT']." | ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."".($extra_array[0]?" | ".$extra_array[0]." ".$extra_array[3]:"")."', '".round((microtime(true)-TIME_START)*1000)."', '".$extra_array[2]."', ".($extra_array[0]?"'".$extra_array[0]." ".$extra_array[3]."'":"NULL").", ".$ISP.", '".$tipo."', '".str_replace('Android Android', 'Android', $i['platform']." ".$i['parent'])."', ".$el_pais.", '".$la_IP[0].".".$la_IP[1]."', '".$la_IP[0].".".$la_IP[1].".".$la_IP[2]."', ".($_COOKIE['trz']?"'".$_COOKIE['trz']."'":"NULL").")");

	sql("UPDATE users SET host = '".$host."' WHERE ID = '".$user_ID."' LIMIT 1");

	return ($rejs==true?'<script type="text/javascript"> $(document).ready(function(){ $.post("'.vp_url('/accion.php?a=users_con', $_SESSION['pol']['pais']).'", { extra: screen.width + "x" + screen.height + "|" + screen.availWidth + "x" + screen.availHeight + "||" + screen.colorDepth + "|"}); }); </script>':true);
}

?>
