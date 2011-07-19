<?php 
include('inc-login.php');
include('inc-functions-accion.php'); // functions extra

// load config full
$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

// load user cargos
$pol['cargos'] = cargos();

// prevent SSX
if ($_GET['ID']) { $_GET['ID'] = mysql_real_escape_string($_GET['ID']); }

foreach ($_POST AS $nom => $val) { $_POST[$nom] = str_replace("'", "&#39;", $val); }


// Solo ciudadanos
if (
((PAIS == $pol['pais']) AND ($pol['estado'] == 'ciudadano'))
OR (($pol['estado'] == 'kickeado') AND ($_GET['a'] == 'rechazar-ciudadania'))
OR (($pol['estado'] == 'kickeado') AND ($_GET['a'] == 'elecciones-generales'))
OR (($pol['estado'] == 'extranjero') AND ($_GET['a'] == 'foro'))
OR (($pol['estado'] == 'extranjero') AND ($_GET['a'] == 'mercado'))
) {


switch ($_GET['a']) { // #####################################################


case 'aceptar-condiciones':
	$result = mysql_query("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND fecha_legal = '0000-00-00 00:00:00' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("UPDATE users SET fecha_legal = '".$date."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		evento_chat('<b>[#] '.crear_link($pol['nick']).'</b> ha aceptado las <a href="http://www'.DEV.'.'.URL.'/legal">Condiciones de Uso de VirtualPol</a>.');
	}
	$refer_url = '';
	break;


case 'SC':
	$sc = get_supervisores_del_censo();
	if (($_GET['b'] == 'nota') AND (isset($sc[$pol['user_ID']])) AND ($_GET['ID'])) {
		mysql_query("UPDATE users SET nota_SC = '".strip_tags($_POST['nota_SC'])."' WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
		$refer_url = 'control/supervisor-censo/';
	}
	break;


case 'exencion_impuestos':
	if ($pol['nivel'] >= 98) {
		$result = mysql_query("SELECT ID, exenta_impuestos FROM ".SQL."cuentas where nivel = '0'", $link);
		while($r = mysql_fetch_array($result)) {
			if (($_POST['exenta_impuestos'.$r['ID']] == '1') AND ($r['exenta_impuestos'] == '0')) {
				mysql_query("UPDATE ".SQL."cuentas SET exenta_impuestos = 1 where ID = '".$r['ID']."'", $link);
			}
			elseif  (!isset($_POST['exenta_impuestos'.$r['ID']]) AND ($r['exenta_impuestos'] == '1')) {
				mysql_query("UPDATE ".SQL."cuentas SET exenta_impuestos = 0 where ID = '".$r['ID']."'", $link);
			}
		}
		$refer_url = 'pols/cuentas/';
	} 
	break;

case 'chat':

	if (($_GET['b'] == 'solicitar') AND ($pol['pols'] >= $pol['config']['pols_crearchat']) AND ($_POST['nombre'])) {

		$nombre = $_POST['nombre'];
		$url = gen_url($nombre);


		mysql_query("INSERT INTO chats (pais, url, titulo, user_ID, fecha_creacion, fecha_last, dias_expira) 
VALUES ('".PAIS."', '".$url."', '".ucfirst($nombre)."', '".$pol['user_ID']."', '".$date."', '".$date."', '".$pol['config']['chat_diasexpira']."')", $link);

		$result = mysql_query("SELECT chat_ID FROM chats WHERE url = '".$url."' AND user_ID = '".$pol['user_ID']."' AND pais = '".$_POST['pais']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)) {
			pols_transferir($pol['config']['pols_crearchat'], $pol['user_ID'], '-1', 'Solicitud chat: '.$nombre);
		}
	} elseif (($_GET['b'] == 'cambiarfundador') AND ($_POST['fundador']) AND ($_POST['chat_ID'])) {

		$result = mysql_query("SELECT ID FROM users WHERE nick = '".$_POST['fundador']."' AND estado = 'ciudadano' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)) {
			mysql_query("UPDATE chats SET user_ID = ".$r['ID']." WHERE chat_ID = '".$_POST['chat_ID']."' AND estado = 'activo' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		}

	} elseif (($_GET['b'] == 'editar') AND ($_POST['chat_ID'])) {

		if ($_POST['acceso_cfg_leer']) { 
			$_POST['acceso_cfg_leer'] = trim(ereg_replace(' +', ' ', strtolower($_POST['acceso_cfg_leer']))); 
		}
		if ($_POST['acceso_cfg_escribir']) { 
			$_POST['acceso_cfg_escribir'] = trim(ereg_replace(' +', ' ', strtolower($_POST['acceso_cfg_escribir'])));
		}

		mysql_query("UPDATE chats 
SET acceso_leer = '".$_POST['acceso_leer']."', 
acceso_escribir = '".$_POST['acceso_escribir']."', 
acceso_cfg_leer = '".$_POST['acceso_cfg_leer']."', 
acceso_cfg_escribir = '".$_POST['acceso_cfg_escribir']."'
WHERE chat_ID = '".$_POST['chat_ID']."' AND estado = 'activo' AND pais = '".PAIS."' AND ((user_ID = '".$pol['user_ID']."') OR ((user_ID = 0) AND (".$pol['nivel']." >= 98))) 
LIMIT 1", $link);

	} elseif (($_GET['b'] == 'activar') AND ($_GET['chat_ID']) AND ($pol['nivel'] >= 98)) {
		mysql_query("UPDATE chats SET estado = 'activo' WHERE chat_ID = '".$_GET['chat_ID']."' AND estado != 'activo' AND pais = '".PAIS."' LIMIT 1", $link);
	} elseif (($_GET['b'] == 'eliminar') AND ($_GET['chat_ID'])) {
		mysql_query("DELETE FROM chats WHERE chat_ID = '".$_GET['chat_ID']."' AND estado = 'bloqueado' AND pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
	} elseif (($_GET['b'] == 'bloquear') AND ($_GET['chat_ID'])) {
		mysql_query("UPDATE chats SET estado = 'bloqueado' WHERE chat_ID = '".$_GET['chat_ID']."' AND estado = 'activo' AND pais = '".PAIS."' AND (user_ID = '".$pol['user_ID']."' OR ((acceso_escribir = 'anonimos') AND ('".$pol['nivel']."' >= 95))) LIMIT 1", $link);
	}

	$refer_url = 'chats/';
	break;








case 'vaciar_listas':

	if (nucleo_acceso($vp['acceso']['control_gobierno'])) {
		$elecciones_dias_quedan = ceil((strtotime($pol['config']['elecciones_inicio']) - time()) / 86400);
		$elecciones_frecuencia_dias = ceil($pol['config']['elecciones_frecuencia'] / 86400);
		if (($elecciones_dias_quedan > 5) AND ($elecciones_dias_quedan < $elecciones_frecuencia_dias)) {
			mysql_query("DELETE FROM ".SQL."partidos_listas", $link);
			evento_chat('<b>[GOBIERNO]</b> Se han vaciado las listas electorales ('.crear_link($pol['nick']).', <a href="/control/gobierno/">Gobierno</a>)');
		}
	}

	$refer_url = 'partidos/';

	break;

case 'historia':
	$sc = get_supervisores_del_censo();

	$_POST['hecho'] = trim($_POST['hecho']);
	if (($_GET['b'] == 'add') AND ($_POST['hecho'] != '')) {
		mysql_query("INSERT INTO hechos (time, nick, texto, estado, time2, pais) VALUES ('".$_POST['year']."-".$_POST['mes']."-".$_POST['dia']."', '".$pol['nick']."', '".strip_tags($_POST['hecho'],'<b>,<a>')."', 'ok', '".$date."', '".$_POST['pais']."')", $link);
	} elseif ($_GET['b'] == 'del') {
		mysql_query("UPDATE hechos SET estado = 'del' WHERE ID = '".$_GET['ID']."' AND (nick = '".$pol['nick']."' OR '".$pol['nivel']."' = '100' OR '".$sc[$pol['user_ID']]."' != '') LIMIT 1", $link);
	}


	$refer_url = 'historia/';

	break;


case 'geolocalizacion':

	if (($_GET['b'] == 'add') AND ($_POST['x']) AND ($_POST['y'])) {
		mysql_query("UPDATE users SET geo = '".$_POST['x'].":".$_POST['y']."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
	}
	$refer_url = 'geolocalizacion/';

	break;




case 'sancion':

	if ((nucleo_acceso($vp['acceso']['control_sancion'])) AND ($_POST['pols'] <= 5000) AND ($_POST['pols'] > 0)) {

		$result = mysql_query("SELECT ID, nick FROM users 
WHERE nick = '".$_POST['nick']."' AND estado = 'ciudadano' AND pais = '".PAIS."'
LIMIT 1", $link);
		while($r = mysql_fetch_array($result)) {
		
			pols_transferir($_POST['pols'], $r['ID'], '-1', '<b>SANCION ('.$pol['nick'].')&rsaquo;</b> '.strip_tags($_POST['concepto']));

			evento_chat('<b>[SANCION] '.crear_link($r['nick']).'</b> ha sido sancionado con '.pols($_POST['pols']).' '.MONEDA.' (<a href="/control/judicial/">Ver sanciones</a>)');
		}

	}
	$refer_url = 'control/judicial/';

	break;

case 'pass':
	if (($pol['user_ID'] == 1) AND ($_GET['nick'])) {


		$result = mysql_query("SELECT ID, nick, email FROM users WHERE nick = '".$_GET['nick']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)) {
			$email = $r['email'];
			$user_ID = $r['ID'];
			$nick = $r['nick'];
		}

		if ($user_ID) {
			$new_pass = $nick.rand(10000,99999);

			$asunto = '[VirtualPol] Contraseña reseteada del usuario: '.$nick;

			$mensaje = "Hola Ciudadano,\n\nSe ha procedido a resetear tu contraseña de seguridad. Por lo tanto tu contraseña ha cambiado.\n\n\nUsuario: ".$nick."\nNueva contraseña: ".$new_pass."\n\nLogin en: http://www.virtualpol.com/\n\nGracias, nos vemos en VirtualPol ;)\n\n\nVirtualPol\nhttp://www.virtualpol.com";

			mail($email, $asunto, $mensaje, "FROM: VirtualPOL <desarrollo@virtualpol.com> \nReturn-Path: desarrollo@virtualpol.com \nX-Sender: desarrollo@virtualpol.com \nMIME-Version: 1.0\n"); 

			mysql_query("UPDATE users SET pass = '".md5($new_pass)."' WHERE ID = '".$user_ID."' LIMIT 1", $link);
			echo 'OK.';
		} else { echo 'Error.'; }
		exit;
	}
	break;




case 'rechazar-ciudadania':
	
	$user_ID = false;
	$result3 = mysql_query("SELECT IP, pols, nick, ID, ref, estado,
".(ECONOMIA?"(SELECT SUM(pols) FROM ".SQL."cuentas WHERE user_ID = '".$pol['user_ID']."')":"estado")." AS pols_cuentas 
FROM users 
WHERE ID = '".$pol['user_ID']."' AND estado = 'ciudadano' AND pais = '".PAIS."'
LIMIT 1", $link);
	while($r3 = mysql_fetch_array($result3)) {
		$user_ID = $r3['ID']; 
		$estado = $r3['estado']; 
		$pols = ($r3['pols'] + $r3['pols_cuentas']); 
		$nick = $r3['nick']; 
		$ref = $r3['ref']; 
		$IP = $r3['IP'];
	}
	if (($user_ID) AND ($_POST['pais'] == PAIS) AND ($pols >=0)) { // RECHAZAR CIUDADANIA

		// moneda
		if ($pols >= 0) {
			$pols_arancel = round(($pols*$pol['config']['arancel_salida'])/100);
		} else { $pols_arancel = 0; }
		$pols = $pols - $pols_arancel;

		evento_log(13); // rechazo de ciudadania
		if (($pol['pols'] > 0) AND (PAIS == 'VP')) {
			$consigo = ' (llevandose consigo: '.pols($pols).' '.MONEDA.')';
		}
		evento_chat('<b>[#] '.crear_link($nick).' rechaza la Ciudadania</b> de '.PAIS.$consigo);
		
		
		if (ECONOMIA) {
			pols_transferir($pols_arancel, $user_ID, '-1', 'Arancel de salida (rechazo de ciudadania) '.$pol['config']['arancel_salida'].'%');
			mysql_query("DELETE FROM ".SQL."empresas WHERE user_ID = '".$user_ID."'", $link);
			mysql_query("DELETE FROM ".SQL."estudios_users WHERE user_ID = '".$user_ID."'", $link);
			mysql_query("DELETE FROM ".SQL."mercado WHERE user_ID = '".$user_ID."'", $link);
			mysql_query("DELETE FROM ".SQL."cuentas WHERE user_ID = '".$user_ID."'", $link);
			mysql_query("DELETE FROM ".SQL."mapa WHERE user_ID = '".$user_ID."'", $link);
			mysql_query("DELETE FROM ".SQL."pujas WHERE user_ID = '".$user_ID."'", $link);
		}
		mysql_query("UPDATE users SET estado = 'turista', pais = 'ninguno', nivel = '1', cargo = '0', nota = '0.0', pols = '".$pols."', rechazo_last = '".$date."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		
		if ($pol['config']['elecciones_estado'] == 'elecciones') { 
			mysql_query("UPDATE ".SQL."elecciones SET ID_partido = '-1' WHERE user_ID = '".$user_ID."' LIMIT 1", $link);
		}
		
		mysql_query("DELETE FROM ".SQL."partidos_listas WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM ".SQL."partidos WHERE ID_presidente = '".$user_ID."'", $link);

		unset($_SESSION);
		session_unset(); session_destroy();
	}
	header('Location: '.REGISTRAR);
	exit;
	break;


case 'expulsar':
	$sc = get_supervisores_del_censo();
	if ((isset($sc[$pol['user_ID']])) AND ($_GET['b'] == 'desexpulsar') AND ($_GET['ID'])) {
		$result = mysql_query("SELECT ID, user_ID, tiempo  FROM expulsiones WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) {
			mysql_query("UPDATE users SET estado = 'ciudadano' WHERE ID = '".$r['user_ID']."' LIMIT 1", $link);
			mysql_query("UPDATE expulsiones SET estado = 'cancelado' WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
			evento_chat('<span class="expulsado"><img src="'.IMG.'expulsar.gif" title="Expulsion" border="0" /> <b>[EXPULSION] '.$r['tiempo'].'</b> ha sido <b>DESexpulsado</b> de VirtualPol por <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" /> <b>'.$pol['nick'].'</b> (<a href="/control/expulsiones/">Ver expulsiones</a>)</span>', '0', '', false, 'e', 'VP');
		}

	} elseif ((isset($sc[$pol['user_ID']])) AND ($_POST['razon']) AND ($_POST['nick']) AND (!in_array($_POST['nick'], $sc))) { 

		if ($_POST['caso']) { $_POST['razon'] .= ' caso '.ucfirst($_POST['caso']); }

		$_POST['motivo'] = ereg_replace("(^|\n| )[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", strip_tags($_POST['motivo']));

		$result = mysql_query("SELECT nick, ID FROM users WHERE nick = '".$_POST['nick']."' AND estado != 'expulsado' LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) {
			mysql_query("UPDATE users SET estado = 'expulsado' WHERE ID = '".$r['ID']."' LIMIT 1", $link);

			mysql_query("DELETE FROM votos WHERE estado = 'confianza' AND uservoto_ID = '".$user_ID."'", $link);
			
			mysql_query("INSERT INTO expulsiones (user_ID, autor, expire, razon, estado, tiempo, IP, cargo, motivo) VALUES ('".$r['ID']."', '".$pol['user_ID']."', '".$date."', '".ucfirst(strip_tags($_POST['razon']))."', 'expulsado', '".$r['nick']."', '0', '".$pol['cargo']."', '".$_POST['motivo']."')", $link);

			evento_chat('<span class="expulsado"><img src="'.IMG.'expulsar.gif" title="Expulsion" border="0" /> <b>[EXPULSION] '.$r['nick'].'</b> ha sido expulsado de VirtualPol. Razon: <b>'.$_POST['razon'].'</b> (<a href="/control/expulsiones/">Ver expulsiones</a>)</span>', '0', '', false, 'e', 'VP');
		}
	}
	$refer_url = 'control/expulsiones/';
	break;


case 'voto':
	if (($_GET['b'] == 'confianza') AND ($_GET['ID'] != $pol['user_ID']) AND (($_REQUEST['voto_confianza'] == '-1') OR ($_REQUEST['voto_confianza'] == '0') OR ($_REQUEST['voto_confianza'] == '1'))) {


		// numero de votos emitidos
		$result = mysql_query("SELECT COUNT(*) AS num FROM ".SQL_VOTOS." WHERE uservoto_ID = '".$pol['user_ID']."' AND voto != '0'", $link);
		while ($r = mysql_fetch_array($result)) { $num_votos = $r['num']; }

		// ha votado a este usuario?
		$hay_voto = false;
		$result = mysql_query("SELECT voto FROM ".SQL_VOTOS." WHERE estado = 'confianza' AND uservoto_ID = '".$pol['user_ID']."' AND user_ID = '".$_GET['ID']."' LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) { $voto = $r['voto']; $hay_voto = true; }

		// nick existe
		$result = mysql_query("SELECT ID FROM users WHERE ID = '".$_GET['ID']."'", $link);
		while ($r = mysql_fetch_array($result)) { $nick_existe = true; }

		if (($nick_existe == true) AND (($_REQUEST['voto_confianza'] == '0') OR ($num_votos < VOTO_CONFIANZA_MAX))) {
			if ($hay_voto == true) {
				// update
				mysql_query("UPDATE ".SQL_VOTOS." SET voto = '".$_REQUEST['voto_confianza']."', time = '".$date."' WHERE estado = 'confianza' AND uservoto_ID = '".$pol['user_ID']."' AND user_ID = '".$_GET['ID']."' LIMIT 1", $link);
			} else {
				// insert
				mysql_query("INSERT INTO ".SQL_VOTOS." (user_ID, uservoto_ID, voto, time, estado) VALUES ('".$_GET['ID']."', '".$pol['user_ID']."', '".$_REQUEST['voto_confianza']."', '".$date."', 'confianza')", $link);
			}
		}
		$refer_url = 'perfil/'.strtolower($_GET['nick']).'/';

	}
	break;


case 'avatar':
	$img_root = RAIZ.'/img/a/';
	if ($_GET['b'] == 'upload') {
		$nom_file = $pol['user_ID'].'.jpg';
		$img_name = $_FILES['avatar']['name'];
	        $img_type = str_replace('image/', '', $_FILES['avatar']['type']);
		$img_size = $_FILES['avatar']['size'];
	        if ((($img_type == 'gif') || ($img_type == 'jpeg') || ($img_type == 'png')) && ($img_size < 1000000)) {
			move_uploaded_file($_FILES['avatar']['tmp_name'], $img_root . $nom_file);
		} 
		if (file_exists($img_root . $nom_file)) {
			imageCompression($img_root . $nom_file, 120, $img_root . $nom_file, $img_type);
			imageCompression($img_root . $nom_file, 80, $img_root . $pol['user_ID'].'_80.jpg', $img_type);
			imageCompression($img_root . $nom_file, 40, $img_root . $pol['user_ID'].'_40.jpg', $img_type);

			mysql_query("UPDATE users SET avatar_localdir = '".$_FILES['avatar']['name']."', avatar = 'true' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		}
	} elseif ($_GET['b'] == 'borrar') {
		unlink($img_root . $pol['user_ID'].'.jpg');
		unlink($img_root . $pol['user_ID'].'_40.jpg');
		unlink($img_root . $pol['user_ID'].'_80.jpg');
		mysql_query("UPDATE users SET avatar = 'false' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'perfil/'.strtolower($pol['nick']).'/';
	} elseif (($_GET['b'] == 'desc') AND (strlen($_POST['desc']) <= 2000)) {
		$_POST['desc'] = gen_text($_POST['desc'], 'plain');
		mysql_query("UPDATE users SET text = '".$_POST['desc']."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
	}
	$refer_url = 'perfil/'.strtolower($pol['nick']).'/';
	break;


case 'examenes':
	if (($_GET['b'] == 'crear') AND ($_POST['titulo']) AND (nucleo_acceso($vp['acceso']['examenes_decano']))) {
		$_POST['titulo'] = gen_title($_POST['titulo']);
		mysql_query("INSERT INTO ".SQL."examenes (titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas) VALUES ('" . $_POST['titulo'] . "', 'Editar...', '" . $pol['user_ID'] . "', '" . $date . "', '" . $_POST['cargo_ID'] . "', '5.0', 10)", $link);
		$new_ID = mysql_insert_id($link);
		mysql_query("UPDATE ".SQL."examenes SET cargo_ID = '-" . $new_ID . "' WHERE ID = '" . $new_ID . "' LIMIT 1", $link);
		$refer_url = 'examenes/';

	} elseif (($_GET['b'] == 'nueva-pregunta') AND ($_GET['ID'] != null) AND ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) AND ($_POST['pregunta']) AND ($_POST['respuesta0'] != null) AND ($_POST['respuesta1'] != null) AND ($_POST['tiempo'])) {
		for ($i=0;$i<10;$i++) { 
			if ($_POST['respuesta' . $i]) { 
				if ($respuestas) { $respuestas .= '|'; }
				$respuestas .= ucfirst(trim(str_replace("|", "", $_POST['respuesta' . $i]))); 
			} 
		}
		$pregunta = ucfirst($_POST['pregunta']);
		mysql_query("INSERT INTO ".SQL."examenes_preg (examen_ID, user_ID, time, pregunta, respuestas, tiempo) VALUES ('" . $_GET['ID'] . "', '" . $pol['user_ID'] . "', '" . $date . "', '" . $pregunta . "', '" . $respuestas . "', " . $_POST['tiempo'] . ")", $link);
		
		$refer_url = 'examenes/editar/' . $_GET['ID'] . '/';

	} elseif (($_GET['b'] == 'eliminar-pregunta') AND ($_GET['ID'] != null) AND ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor'])))) {
		mysql_query("DELETE FROM ".SQL."examenes_preg WHERE ID = '" . $_GET['ID'] . "' LIMIT 1", $link);
		$refer_url = 'examenes/editar/' . $_GET['re_ID'] . '/';

	} elseif (($_GET['b'] == 'editar-examen') AND ($_GET['ID'] != null) AND (nucleo_acceso($vp['acceso']['examenes_decano'])) AND ($_POST['titulo']) AND ($_POST['descripcion']) AND ($_POST['nota'] >= 0) AND ($_POST['num_preguntas'] >= 0)) {
		$_POST['descripcion'] = gen_text($_POST['descripcion'], 'plain');
		mysql_query("UPDATE ".SQL."examenes SET titulo = '".$_POST['titulo']."', descripcion = '".$_POST['descripcion'] . "', nota = '".$_POST['nota']."', num_preguntas = '".$_POST['num_preguntas']."' WHERE ID = '" . $_GET['ID'] . "' LIMIT 1", $link);
		$refer_url = 'examenes/editar/' . $_GET['ID'] . '/';
	} elseif (($_GET['b'] == 'examinar') AND ($_GET['ID'] != null) AND ($_POST['pregs']) AND (($_POST['tlgs'] + 10) > time())) {

		$result = mysql_query("SELECT cargo_ID, titulo, ID, nota, num_preguntas,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = ".SQL."examenes.ID LIMIT 1) AS num_depreguntas
FROM ".SQL."examenes WHERE ID = '" . $_GET['ID'] . "' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			$cargo_ID = $r['cargo_ID'];
			$nota_aprobado = $r['nota'];
			$examen_titulo = $r['titulo'];
			$examen_ID = $r['ID'];
			$num_depreguntas = $r['num_depreguntas'];
			$num_preguntas = $r['num_preguntas'];
		}

		if (($examen_ID) AND isset($_SESSION['examen'])) {
			if ($examen_ID == $_SESSION['examen']['ID']) {
				$respuestas_correctas = $_SESSION['examen']['respuestas'];
				$nota['ok'] = 0;
				$indice = 0;
				$pregs = explode("|", $_POST['pregs']);
				foreach($pregs as $ID) { 
					if ($_POST['respuesta' . $ID] == $respuestas_correctas[$indice]) { 
						$nota['ok']++; 
					} 
					$indice++; 
				}
				if ($indice == $num_preguntas) {
					$nota['nota'] = number_format(round(($nota['ok'] / $num_preguntas) * 10, 1), 1, '.', '');
				} else {
					$nota['nota'] = 0;
				}
				if ($nota['nota'] >= $nota_aprobado) { $estado = ", estado = 'ok'"; } else { $estado = ", estado = 'examen'"; }

				$evento_examen = '<b>[EXAMEN]</b> &nbsp; <b style="color:grey;">' . $nota['nota'] . '</b> ' . crear_link($pol['nick']) . ' en el examen <a href="/examenes/' . $examen_ID . '/">' . $examen_titulo . '</a>';

				if ($nota['nota'] >= $nota_aprobado) { evento_chat($evento_examen); }
				//evento_chat($evento_examen, 0, 6);

				mysql_query("UPDATE ".SQL."estudios_users SET time = '" . $date . "', nota = '" . $nota['nota'] . "'" . $estado . " WHERE user_ID = '" . $pol['user_ID'] . "' AND ID_estudio = '" . $cargo_ID . "' LIMIT 1", $link);

				$refer_url = 'examenes/mis-examenes/';
			}
			unset($_SESSION['examen']);
		}
	} elseif (($_GET['b'] == 'eliminar-examen') AND ($_POST['ID'] != null) AND (nucleo_acceso($vp['acceso']['examenes_decano']))) { 
		$result = mysql_query("SELECT cargo_ID,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = ".SQL."examenes.ID LIMIT 1) AS num_depreguntas
FROM ".SQL."examenes WHERE ID = '" . $_POST['ID'] . "' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			if (($r['cargo_ID'] < 0) AND ($r['num_depreguntas'] == 0)) {
				mysql_query("DELETE FROM ".SQL."examenes WHERE ID = '".$_POST['ID']."'", $link);
				mysql_query("DELETE FROM ".SQL."estudios_users WHERE ID_estudio = '".$r['cargo_ID']."'", $link);
				$refer_url = 'examenes/';
			}
		}
	} elseif (($_GET['b'] == 'caducar_examen') AND ($_GET['ID'] != null)) {
	
		if ($_POST['pais'] == PAIS) {
			mysql_query("DELETE FROM ".SQL."estudios_users WHERE ID = '".$_GET['ID']."' AND user_ID = '". $pol['user_ID']."' AND time < '".date('Y-m-d 20:00:00', time() - $pol['config']['examen_repe']*6)."' AND ID_estudio <= 0", $link);
			$refer_url = 'examenes/mis-examenes/';
		}
	}

	break;


case 'mapa':
	//pol_mapa (ID, pos_x, pos_y, size_x, size_y, user_ID, link, text, time, pols, color, estado)

	// pasa a ESTADO
	if ($pol['cargos'][40]) { mysql_query("UPDATE ".SQL."mapa SET estado = 'e', user_ID = '' WHERE link = 'ESTADO'", $link); }

	if (($_GET['b'] == 'compraventa') AND ($_GET['ID'])) {


		$result = mysql_query("SELECT ID, user_ID, pols FROM ".SQL."mapa WHERE ID = '".$_GET['ID']."' AND estado = 'v' AND '".$pol['pols']."' >= pols LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			if ($pol['user_ID'] != $r['user_ID']) {
				pols_transferir($r['pols'], $pol['user_ID'], $r['user_ID'], 'Compra-venta propiedad: '.$r['ID']);
				mysql_query("UPDATE ".SQL."mapa SET estado = 'p', user_ID = '".$pol['user_ID']."', nick = '".$pol['nick']."' WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
			}
		}
		$refer_url = 'mapa/';

	} elseif (($_GET['b'] == 'cancelar-venta') AND ($_GET['ID'])) {

		mysql_query("UPDATE ".SQL."mapa SET estado = 'p' WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'mapa/propiedades/';

	} elseif (($_GET['b'] == 'vender') AND ($_GET['ID']) AND ($_POST['pols'] > 0)) {

		mysql_query("UPDATE ".SQL."mapa SET pols = '".$_POST['pols']."', estado = 'v' WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'mapa/propiedades/';


	} elseif (($_GET['b'] == 'eliminar') AND ($_GET['ID'])) {

		mysql_query("DELETE FROM ".SQL."mapa WHERE ID = '".$_GET['ID']."' AND (user_ID = '".$pol['user_ID']."' OR (estado = 'e' AND '1' = '".$pol['cargos'][40]."')) LIMIT 1", $link);
		$refer_url = 'mapa/propiedades/';



	} elseif (($_GET['b'] == 'ceder') AND ($_GET['ID']) AND ($_POST['nick'])) {

		$result = mysql_query("SELECT ID, user_ID, pols, 
(SELECT ID FROM users WHERE nick = '".$_POST['nick']."' AND pais = '".PAIS."' AND estado = 'ciudadano' LIMIT 1) AS ceder_user_ID 
FROM ".SQL."mapa 
WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' AND (estado = 'p' OR estado = 'e') LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			if ($r['ceder_user_ID']) {
				mysql_query("UPDATE ".SQL."mapa SET user_ID = '".$r['ceder_user_ID']."', nick = '".$_POST['nick']."',  time = '".$date."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
				evento_log(16, $r['ID'], $r['ceder_user_ID']); // Ceder propiedad
			}
		}

		$refer_url = 'mapa/propiedades/';

	} elseif (($_GET['b'] == 'separar') AND ($_GET['ID'])) {

		$result = mysql_query("SELECT * FROM ".SQL."mapa WHERE ID = '".$_GET['ID']."' AND (estado = 'p' OR estado = 'e') AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			
			for ($y=1;$y<=$r['size_y'];$y++) {
				for ($x=1;$x<=$r['size_x'];$x++) {
					if (($x==1) AND ($y==1)) {
						mysql_query("UPDATE ".SQL."mapa SET size_x = 1, size_y = 1, superficie = 1, time = '".$date."', estado = 'p' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
						$puntero_x = $r['pos_x'];
						$puntero['pos_x'] = $r['pos_x'];
						$puntero['pos_y'] = $r['pos_y'];
					} else {
						mysql_query("INSERT INTO ".SQL."mapa (pos_x, pos_y, size_x, size_y, user_ID, nick, link, text, time, pols, color, estado, superficie) VALUES ('".$puntero['pos_x']."', '".$puntero['pos_y']."', '1', '1', '".$pol['user_ID']."', '".$pol['nick']."', '".$r['link']."', '', '".$date."', '".$r['pols']."', '".$r['color']."', 'p', '1')", $link);
					}
					$puntero['pos_x']++;
				}
				$puntero['pos_x'] = $puntero_x;
				$puntero['pos_y']++;
			}

		}
		
		
		$refer_url = 'mapa/propiedades/';

	} elseif (($_GET['b'] == 'fusionar') AND ($_GET['ID']) AND ($_GET['f'])) {

		$ID = explode("-", $_GET['ID']);

		$result = mysql_query("SELECT *
FROM ".SQL."mapa 
WHERE (user_ID = '".$pol['user_ID']."' OR (estado = 'e' AND '1' = '".$pol['cargos'][40]."')) AND (ID = '".$ID[0]."' OR ID = '".$ID[1]."') LIMIT 2", $link);
		while($r = mysql_fetch_array($result)){ 
			$prop[$r['ID']]['size_x'] = $r['size_x'];
			$prop[$r['ID']]['size_y'] = $r['size_y'];
		}

		//propiedades ok
		if (($prop[$ID[0]]['size_x']) AND ($prop[$ID[1]]['size_x'])) {
			if ($_GET['f'] == 'x') {

				//ampliar 0
				$size_x = ($prop[$ID[0]]['size_x'] + $prop[$ID[1]]['size_x']);
				$size_y = $prop[$ID[0]]['size_y'];
				mysql_query("UPDATE ".SQL."mapa SET size_x = '".$size_x."', superficie = '".($size_x * $size_y)."' WHERE ID = '".$ID[0]."' LIMIT 1", $link);
				//eliminar 1
				mysql_query("DELETE FROM ".SQL."mapa WHERE ID = '".$ID[1]."' LIMIT 1", $link);

			} elseif ($_GET['f'] == 'y') {

				//ampliar 0
				$size_x = $prop[$ID[0]]['size_x'];
				$size_y = ($prop[$ID[0]]['size_y'] + $prop[$ID[1]]['size_y']);
				mysql_query("UPDATE ".SQL."mapa SET size_y = '".$size_y."', superficie = '".($size_x * $size_y)."' WHERE ID = '".$ID[0]."' LIMIT 1", $link);
				//eliminar 1
				mysql_query("DELETE FROM ".SQL."mapa WHERE ID = '".$ID[1]."' LIMIT 1", $link);

			}
		}
		$refer_url = 'mapa/propiedades/';


	} elseif (($_GET['b'] == 'editar') AND ($_GET['ID']) AND ($_POST['color']) AND ($_POST['link'] != 'e') AND ($_POST['link'] != 'v')) {

		$_POST['color2'] = ereg_replace("[^A-Fa-f0-9]", "", $_POST['color2']);
		if (strlen($_POST['color2']) == 3) { 
			$_POST['color2'] = strtoupper($_POST['color2']);
			if (($_POST['color2'] == 'FFF') OR ($_POST['color2'] == '000') OR ($_POST['color2'] == 'FF0') OR ($_POST['color2'] == '333')) {
				$_POST['color'] = ''; 
			} else {
				$_POST['color'] = strtoupper(trim($_POST['color2'])); 
			}
		}
		$_POST['color'] = ereg_replace("[^A-Fa-f0-9]", "", $_POST['color']);

		$result = mysql_query("SELECT * FROM ".SQL."mapa WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			$superficie = $r['size_x'] * $r['size_y'];
		}


		if ((strlen($_POST['text']) <= $superficie)) {
			$_POST['text'] = ereg_replace("[^A-Za-z0-9-]", "", $_POST['text']);
		}
			
		$_POST['link'] = strip_tags($_POST['link']);
		$_POST['link'] = str_replace("http://", "", $_POST['link']);
		$_POST['link'] = str_replace("|", "", $_POST['link']);
		$_POST['link'] = str_replace("\"", "", $_POST['link']);
		$_POST['link'] = str_replace(HOST, "", $_POST['link']);
		if (strlen($_POST['color']) == 3) {
			mysql_query("UPDATE ".SQL."mapa SET color = '".$_POST['color']."', text = '".$_POST['text']."', link = '".$_POST['link']."' WHERE ID = '" .	$_GET['ID']."' AND (user_ID = '".$pol['user_ID']."' OR (estado = 'e' AND '1' = '".$pol['cargos'][40]."')) LIMIT 1", $link);
			$refer_url = 'mapa/propiedades/';
		}


	} elseif (($_GET['b'] == 'comprar') AND ($_GET['ID']) AND ($_POST['color'])) {
		
		$_POST['link'] = strip_tags($_POST['link']);
		$_POST['link'] = str_replace("http://", "", $_POST['link']);
		$_POST['link'] = str_replace("|", "", $_POST['link']);
		$_POST['link'] = str_replace("\"", "", $_POST['link']);
		$_POST['link'] = str_replace(HOST, "", $_POST['link']);

		$pos = explode("-", $_GET['ID']);


		if (($pos[0] > 0) AND ($pos[1] > 0) AND ($pos[0] <= $columnas) AND ($pos[1] <= $filas) AND ($_POST['link'] != 'e') AND ($_POST['link'] != 'v')) {
			
			//verifica solar libre
			$cc = false;
			$result = mysql_query("SELECT pos_x, pos_y, size_x, size_y FROM ".SQL."mapa", $link);
			while($r = mysql_fetch_array($result)){
				for ($y=1;$y<=$r['size_y'];$y++) {
					for ($x=1;$x<=$r['size_x'];$x++) {
						$cc[($r['pos_x'] + ($x - 1))][($r['pos_y'] + ($y - 1))] = true;
					}
				}

			}
			

			if (($cc[$pos[0]][$pos[1]] != true) AND ($pol['pols'] >= $pol['config']['pols_solar'])) { // verifica solar libre

				mysql_query("INSERT INTO ".SQL."mapa (pos_x, pos_y, size_x, size_y, user_ID, nick, link, text, time, pols, color, estado, superficie) VALUES ('".$pos[0]."', '".$pos[1]."', '1', '1', '".$pol['user_ID']."', '".$pol['nick']."', '".$_POST['link']."', '', '".$date."', '".$pol['config']['pols_solar']."', '".$_POST['color']."', 'p', '1')", $link);
				pols_transferir($pol['config']['pols_solar'], $pol['user_ID'], '-1', 'Compra propiedad: '.$_GET['ID']);
			}
		}

		$refer_url = 'mapa/';

	}
	break;


case 'gobierno':
	if (
($_GET['b'] == 'config') AND 
(nucleo_acceso($vp['acceso']['control_gobierno'])) AND  
($_POST['online_ref'] >= 60) AND
($_POST['pols_inem'] >= 0) AND ($_POST['pols_inem'] <= 500) AND
($_POST['pols_afiliacion'] >= 0) AND ($_POST['pols_afiliacion'] <= 2000) AND
($_POST['pols_empresa'] >= 0) AND
($_POST['pols_cuentas'] >= 0) AND
($_POST['pols_partido'] >= 0) AND
($_POST['pols_solar'] >= 0) AND
($_POST['pols_crearchat'] >= 0) AND
($_POST['factor_propiedad'] <= 10) AND ($_POST['factor_propiedad'] >= 0) AND 
($_POST['pols_mensajetodos'] >= 300) AND 
($_POST['pols_examen'] >= 0) AND 
($pol['config']['pols_mensajeurgente'] >= 0) AND
($_POST['num_escanos'] <= 31) AND ($_POST['num_escanos'] >= 3) AND 
(strlen($_POST['palabra_gob0']) <= 200) AND
($_POST['impuestos'] <= 5) AND ($_POST['impuestos'] >= 0) AND
($_POST['impuestos_minimo'] >= 0) AND
($_POST['impuestos_empresa'] <= 1000) AND ($_POST['impuestos_empresa'] >= 0) AND
($_POST['arancel_salida'] <= 100) AND ($_POST['arancel_salida'] >= 0) AND
($_POST['chat_diasexpira'] >= 10)
) {

$dato_array = array(
'online_ref'=>'Tiempo online en minutos para referencia',
'pols_mensajetodos'=>'Coste mensaje Global',
'pols_solar'=>'Coste solar del mapa',
'num_escanos'=>'Numero de esca&ntilde;os',
'defcon'=>'DEFCON',
'pols_inem'=>'INEM',
'pols_afiliacion'=>'Pago por afiliado',
'pols_empresa'=>'Coste creacion empresa',
'pols_cuentas'=>'Coste creacion cuenta bancaria',
'pols_partido'=>'Coste creacion partido politico',
'factor_propiedad'=>'Factor propiedad',
'pols_examen'=>'Coste hacer un examen',
'pols_mensajeurgente'=>'Coste mensaje urgente',
'examenes_exp'=>'Expiracion de examen',
'impuestos'=>'Impuesto de patrimonio',
'impuestos_minimo'=>'Minimo patrimonio imponible',
'impuestos_empresa'=>'Impuesto de empresa',
'arancel_salida'=>'Arancel de salida',
'bg'=>'Imagen de fondo',
'pais_des'=>'Descripcion del Pais',
'palabra_gob'=>'Mensaje Del Gobierno',
'pols_crearchat'=>'Coste creacion chat',
'chat_diasexpira'=>'Dias expiracion chat',
);

foreach ($vp['paises'] AS $pais) {
	if (PAIS != $pais) {
			$dato_array['frontera_con_' . $pais] = 'Frontera con ' . $pais;
	}
}

foreach ($_POST AS $dato => $valor) {
	if ((substr($dato, 0, 8) != 'salario_') AND ($dato != 'palabra_gob1')) {


		if ($dato == 'online_ref') {
			$valor = round($_POST['online_ref']*60);
			mysql_query("UPDATE ".SQL."config SET valor = '".strip_tags($valor)."' WHERE dato = '".$dato."' LIMIT 1", $link);
		} elseif ($dato == 'palabra_gob0') {
			$dato = 'palabra_gob';
			$valor = strip_tags($_POST['palabra_gob0']).":".strip_tags($_POST['palabra_gob1']);
			mysql_query("UPDATE ".SQL."config SET valor = '".strip_tags($valor)."' WHERE dato = '".$dato."' LIMIT 1", $link);
		} elseif ($dato == 'num_escanos') {
			if ($pol['config']['elecciones_estado'] != 'elecciones') {
				mysql_query("UPDATE ".SQL."config SET valor = '".strip_tags($valor)."' WHERE dato = '".$dato."' LIMIT 1", $link);
			}
		} else {
			mysql_query("UPDATE ".SQL."config SET valor = '".strip_tags($valor)."' WHERE dato = '".$dato."' LIMIT 1", $link);
		}

		if ($pol['config'][$dato] != $valor) { 
			if ($valor == '') { $valor = '<em>null</em>'; }
			if ($dato == 'online_ref') {
				$valor = intval($valor)/60; 
				$pol['config'][$dato] = $pol['config'][$dato]/60;
			}
			evento_chat('<b>[GOBIERNO]</b> Configuraci&oacute;n ('.crear_link($pol['nick']).'): <em>'.$dato_array[$dato].'</em> de <b>'.$pol['config'][$dato].'</b> a <b>'.$valor.'</b> (<a href="/control/gobierno/">Gobierno</a>)'); 
		}
	
	}
}


	// Salarios
	$result = mysql_query("SELECT ID, salario, nombre FROM ".SQL."estudios", $link);
	while($r = mysql_fetch_array($result)){
		$salario = $_POST['salario_'.$r['ID']];
		if (($salario >= 0) AND ($salario <= 1000)) {
			if ($salario != $r['salario']) { evento_chat('<b>[GOBIERNO]</b> El salario de <img src="'.IMG.'cargos/'.$r['ID'].'.gif" /><b>'.$r['nombre'].'</b> se ha cambiado de '.pols($r['salario']).' '.MONEDA.' a '.pols($salario).' '.MONEDA.' ('.crear_link($pol['nick']).', <a href="/control/gobierno/">Gobierno</a>)');  }
			mysql_query("UPDATE ".SQL."estudios SET salario = '".$salario."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
		}
	}

	$refer_url = 'control/gobierno/';

	// FORO
	} elseif (($_GET['b'] == 'subforo') AND (nucleo_acceso($vp['acceso']['control_gobierno']))) {

		$subforos = explode('.', $_POST['subforos']);

		foreach ($subforos AS $subforo_ID) {
			mysql_query("UPDATE ".SQL."foros SET descripcion = '".$_POST[$subforo_ID.'_descripcion']."', time = '".$_POST[$subforo_ID.'_time']."', acceso_leer = '".$_POST[$subforo_ID.'_acceso_leer']."', acceso_escribir = '".$_POST[$subforo_ID.'_acceso_escribir']."', acceso_cfg_leer = '".$_POST[$subforo_ID.'_acceso_cfg_leer']."', acceso_cfg_escribir = '".$_POST[$subforo_ID.'_acceso_cfg_escribir']."' WHERE ID = '".$subforo_ID."' LIMIT 1", $link);
		}

		$refer_url = 'control/gobierno/foro/';
	} elseif (($_GET['b'] == 'crearsubforo') AND (nucleo_acceso($vp['acceso']['control_gobierno']))) {

		mysql_query("INSERT INTO ".SQL."foros (url, title, descripcion, acceso, time, estado, acceso_msg) 
VALUES ('".gen_url($_POST['nombre'])."', '".$_POST['nombre']."', '', '1', '10', 'ok', '0')", $link);

		$refer_url = 'control/gobierno/foro/';

	} elseif (($_GET['b'] == 'eliminarsubforo') AND (nucleo_acceso($vp['acceso']['control_gobierno'])) AND ($_GET['ID'])) {

		mysql_query("UPDATE ".SQL."foros SET estado = 'eliminado' WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);

		$refer_url = 'control/gobierno/foro/';
	}

	break;



case 'api':
	if (($pol['user_ID']) AND ($_GET['b'] == 'gen_pass')) {
		mysql_query("UPDATE users SET api_pass = '".substr(md5(mt_rand(1000000000,9999999999)), 0, 12)."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'perfil/'.strtolower($pol['nick']).'/';
	}
	break;

case 'empresa':

	if (($_GET['b'] == 'crear') AND ($pol['pols'] >= $pol['config']['pols_empresa']) AND (ctype_digit($_POST['cat'])) AND ($_POST['nombre'])) {

		$nombre = $_POST['nombre'];
		$url = gen_url($nombre);

		
		$result = mysql_query("SELECT ID, url FROM ".SQL."cat WHERE ID = '".$_POST['cat']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ $cat_url = $r['url']; $cat_ID = $r['ID']; }

		mysql_query("INSERT INTO ".SQL."empresas (url, nombre, user_ID, descripcion, web, cat_ID, time) 
VALUES ('".$url."', '".$nombre."', '".$pol['user_ID']."', 'Editar...', '', '".$cat_ID."', '".$date."')", $link);

	  mysql_query("SELECT ID FROM ".SQL."vp_empresas WHERE nombre='$nombre'",$link);
	  $nick = $_SESSION['pol']['nick'];
	  $acciones = 100;

	  $acciones=mysql_query("INSERT INTO ".SQL."acciones (nick, nombre_empresa, acciones, pais, ID_empresa) 
VALUES ('".$nick."', '".$nombre."', '".$acciones."', '".$PAIS."')", $link);


		mysql_query("UPDATE ".SQL."cat SET num = num + 1 WHERE ID = '".$cat_ID."' LIMIT 1", $link);

		pols_transferir($pol['config']['pols_empresa'], $pol['user_ID'], '-1', 'Creacion nueva empresa: '.$nombre);

		$return = $cat_url.'/'.$url.'/';
      
		    } elseif (($_GET['b'] == 'acciones') AND ($_GET['ID']) AND ($_POST['nick'] AND ($_POST['cantidad']))) {

	  $id = $_GET['ID'];

	  $result = mysql_query("SELECT nombre, ID, user_ID FROM vp_empresas WHERE ID='$id', $link");

	  if ($r=mysql_fetch_array($result)) {

	  $id = $r['ID'];
	  $nick = $_POST['nick'];
	  $cantidad = $_POST['cantidad'];
	  $id_user = $r['user_ID'];

	  $acciones = mysql_query("INSERT INTO acciones (ID_empresa, num_acciones, nick, pais) 
VALUES ('".$id."', '".$cantidad."', '".$nick."', '".$PAIS."')", $link);



	  $usuario = mysql_query("SELECT nick FROM vp_users WHERE ID = '$id_user', $link");

	  if ($r=mysql_fetch_array($usuario)) {

	  $nick = $r['nick'];

	  $cantidadacciones = mysql_query("SELECT acciones, nick, nombre_empresa FROM acciones WHERE nick = '$nick' and ID_empresa = '$id', $link");

	  if ($r=mysql_fetch_array($cantidadacciones)) {

	  $susacciones = $r['acciones'];
	  $totalacciones = $susacciones - $cantidad;

	  $accionesresultantes = mysql_query("update acciones set acciones='$totalacciones' where nick='$nick' and ID_empresa='$id'",$link);

}
}
}



	} elseif (($_GET['b'] == 'ceder') AND ($_GET['ID']) AND ($_POST['nick'])) {

		$result = mysql_query("SELECT ID, user_ID, 
(SELECT ID FROM users WHERE nick = '".$_POST['nick']."' AND pais = '".PAIS."' AND estado = 'ciudadano' LIMIT 1) AS ceder_user_ID 
FROM ".SQL."empresas 
WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			if ($r['ceder_user_ID']) {
				mysql_query("UPDATE ".SQL."empresas SET user_ID = '".$r['ceder_user_ID']."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
				evento_log(15, $r['ID'], $r['ceder_user_ID']); // Ceder empresa
			}
		}
		$refer_url = 'empresas/';

	} elseif (($_GET['b'] == 'editar') AND ($_POST['txt'])) {

		$txt = gen_text($_POST['txt']);

		mysql_query("UPDATE ".SQL."empresas SET descripcion = '".$txt."' WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);

		$return =  $_POST['return'];
	} elseif (($_GET['b'] == 'eliminar') AND ($_GET['ID'])) {
		mysql_query("DELETE FROM ".SQL."empresas WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
	}
	$refer_url = 'empresas/'.$return;
	break;



case 'mercado':
	if (($_GET['b'] == 'puja') AND ($pol['estado'] != 'extranjero') AND ($_GET['ID']) AND ($_POST['puja'] > 0) AND (ctype_digit($_POST['puja'])) AND (date('H:i') != '20:00')) {
		$ID = $_GET['ID'];
		$pols = $_POST['puja'];
		
		//puja valida
		$pols_max = true;
		$result = mysql_query("SELECT pols FROM ".SQL."pujas 
WHERE mercado_ID = '".$ID."' 
ORDER BY pols DESC LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ if ($r['pols'] >= $pols) { $pols_max = false; } }

		if (($pols_max) AND ($pols <= $pol['pols'])) {
			mysql_query("INSERT INTO ".SQL."pujas (mercado_ID, user_ID, pols, time) VALUES ('".$ID."', '".$pol['user_ID']."', '".$pols."', '".$date."')", $link);
			evento_chat('<b>[#]</b> puja '.pols($pols).' '.MONEDA.' de <em>'.$pol['nick'].'</em> (<a href="/subasta/">Subasta</a>)'); 
		}

		$refer_url = 'subasta/';
	
	} elseif (($_GET['b'] == 'editarfrase') AND ($pol['config']['pols_fraseedit'] == $pol['user_ID'])) {

		$_POST['url'] = str_replace("http://", "", $_POST['url']);
		$url = '<a href="http://'.strip_tags($_POST['url']).'">'.ucfirst(strip_tags($_POST['frase'])).'</a>';
		mysql_query("UPDATE ".SQL."config SET valor = '".$url."' WHERE dato = 'pols_frase' LIMIT 1", $link);
		
		$refer_url = 'subasta/editar/';

	} elseif (($_GET['b'] == 'cederfrase') AND ($pol['config']['pols_fraseedit'] == $pol['user_ID']) AND ($pol['nick'] != $_POST['nick'])) {


		$result = mysql_query("SELECT ID, nick, pais FROM users WHERE nick = '".$_POST['nick']."' AND estado = 'ciudadano' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			mysql_query("UPDATE ".SQL."config SET valor = '".$r['ID']."' WHERE dato = 'pols_fraseedit' LIMIT 1", $link);	
			evento_chat('<b>[#] '.crear_link($pol['nick']).' cede</b> "la frase" a <b>'.crear_link($r['nick']).'</b>'); 
		}
		
		$refer_url = 'subasta/editar/';

	} elseif (($_GET['b'] == 'editarpalabra') AND ($_GET['ID'] != null) AND (strlen($_POST['text']) <= 10)) {
		
		$_POST['text'] = ereg_replace("[^ A-Za-z0-9-]", "", $_POST['text']);
		$_POST['text'] = str_replace(";", "", $_POST['text']);
		$_POST['text'] = str_replace(":", "", $_POST['text']);
		$_POST['url'] = str_replace("http://", "", $_POST['url']);
		$_POST['url'] = str_replace(";", "", $_POST['url']);
		$_POST['url'] = str_replace(":", "", $_POST['url']);

		$dato = '';
		foreach(explode(";", $pol['config']['palabras']) as $num => $t) {
			$t = explode(":", $t);
			
			if ($dato) { $dato .= ';'; }

			if (($t[0] == $pol['user_ID']) AND ($_GET['ID'] == $num)) {
				$dato .= $pol['user_ID'].':'.$_POST['url'].':'.$_POST['text'];
			} else {
				$dato .= $t[0].':'.$t[1].':'.$t[2];
			}
		}
		mysql_query("UPDATE ".SQL."config SET valor = '".$dato."' WHERE dato = 'palabras' LIMIT 1", $link);
		
		$refer_url = 'subasta/editar/';

	} elseif (($_GET['b'] == 'cederpalabra') AND ($_GET['ID'] >= 0) AND ($pol['nick'] != $_POST['nick'])) {
		
		$result = mysql_query("SELECT ID, nick, pais FROM users WHERE nick = '".$_POST['nick']."'AND estado = 'ciudadano' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 

			$dato = '';
			foreach(explode(";", $pol['config']['palabras']) as $num => $t) {
				$t = explode(":", $t);
				
				if ($dato) { $dato .= ';'; }

				if (($t[0] == $pol['user_ID']) AND ($_GET['ID'] == $num)) {
					$dato .= $r['ID'].'::'.$r['nick'];
				} else { $dato .= $t[0].':'.$t[1].':'.$t[2]; }
			}
			mysql_query("UPDATE ".SQL."config SET valor = '".$dato."' WHERE dato = 'palabras' LIMIT 1", $link);
			evento_chat('<b>[#] '.crear_link($pol['nick']).' cede</b> la "palabra '.($_GET['ID'] + 1).'" a <b>'.crear_link($r['nick']).'</b>');
		}
		
		$refer_url = 'subasta/editar/';
	}


	if (!$refer_url) { $refer_url = 'subasta/'; }
	break;






case 'pols':

	$_POST['pols'] = strval($_POST['pols']);

	$refer_url = 'pols/#error';

	if (($_GET['b'] == 'transferir') AND (ctype_digit($_POST['pols'])) AND ($_POST['pols'] > 0) AND ($_POST['concepto'])) {



		$concepto = ucfirst(strip_tags($_POST['concepto']));
		$pols = $_POST['pols'];

		$origen = false;
		$destino = false;
		$transf_int = false;
		

		//ORIGEN
		if ($_POST['origen'] == '0') { 
			//Personal

			//tienes dinero suficiente y nick existe
			$result = mysql_query("SELECT ID, pais FROM users WHERE pais = '".PAIS."' AND ID = '".$pol['user_ID']."' AND pols >= '".$pols."' AND estado = 'ciudadano' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $pais_origen = $r['pais']; $origen = 'ciudadano'; }

		} elseif (ctype_digit($_POST['origen'])) { 
			//Cuenta

			$result = mysql_query("SELECT ID FROM ".SQL."cuentas WHERE ID = '".$_POST['origen']."' AND pols >= '".$pols."' AND (user_ID = '".$pol['user_ID']."' OR (nivel != 0 AND nivel <= '".$pol['nivel']."')) LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $origen = 'cuenta'; }

		}

		//DESTINO
		if (($_POST['destino'] == 'ciudadano') AND ($_POST['ciudadano'])) {
			//Ciudadano

			//nick existe
			$result = mysql_query("SELECT ID, pais FROM users WHERE nick = '".$_POST['ciudadano']."' AND estado = 'ciudadano' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){  $pais_destino = $r['pais']; $destino = 'ciudadano'; $destino_user_ID = $r['ID']; }

		} elseif (($_POST['destino'] == 'cuenta') AND ($_POST['cuenta'])) {
			//cuenta
			
			//cuenta existe
			$result = mysql_query("SELECT ID FROM ".SQL."cuentas WHERE ID = '".$_POST['cuenta']."' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $destino = 'cuenta'; $destino_cuenta_ID = $r['ID']; }
		}


		if (($origen) AND ($destino)) { //todo OK

			//es transferencia internacional?
			if (($origen == 'ciudadano') AND ($destino == 'ciudadano') AND ($pais_origen != $pais_destino)) { 
				$transf_int = true; 
				if ($pol['config']['arancel_salida'] == 100) { $pols = 0; }
			} elseif (($origen == 'cuenta') AND ($destino == 'ciudadano') AND (PAIS != $pais_destino)) {
				$pols = 0;
			}

			if (($transf_int) AND ($pols > 0) AND ($pol['config']['arancel_salida'] > 0)) {
				// arancel salida
				$pols_arancel = round(($pols*$pol['config']['arancel_salida'])/100);
				pols_transferir($pols_arancel, $pol['user_ID'], '-1', 'Arancel de salida: '.$pol['config']['arancel_salida'].'%');
				$pols = $pols - $pols_arancel;
			}

			//quitar
			if ($origen == 'ciudadano') {
				mysql_query("UPDATE users SET pols = pols - ".$pols." WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
				$emisor_ID = $pol['user_ID'];
			} elseif ($origen == 'cuenta') {

				$concepto = '<b>'.$pol['nick'].'&rsaquo;</b> '.$concepto;
				if (!$pol['nick']) { $concepto = 'S&Upsilon;STEM'.$concepto; }
				
				mysql_query("UPDATE ".SQL."cuentas SET pols = pols - ".$pols." WHERE ID = '".$_POST['origen']."' LIMIT 1", $link);
				$emisor_ID = '-'.$_POST['origen'];
			}

			//ingresar
			if ($destino == 'ciudadano') {



				mysql_query("UPDATE users SET pols = pols + ".$pols." WHERE ID = '".$destino_user_ID."' LIMIT 1", $link);
				$receptor_ID = $destino_user_ID;
			} elseif ($destino == 'cuenta') {
				mysql_query("UPDATE ".SQL."cuentas SET pols = pols + ".$pols." WHERE ID = '".$destino_cuenta_ID."' LIMIT 1", $link);
				$receptor_ID = '-'.$destino_cuenta_ID;
			}

			// insert historial
			if (($pols > 0) AND ($emisor_ID != $receptor_ID)) {
				mysql_query("INSERT INTO ".SQL."transacciones (pols, emisor_ID, receptor_ID, concepto, time) VALUES ('".$pols."', '".$emisor_ID."', '".$receptor_ID."', '".$concepto."', '".$date."')", $link);

				if ($transf_int) {
					mysql_query("INSERT INTO ".strtolower($pais_destino)."_transacciones (pols, emisor_ID, receptor_ID, concepto, time) VALUES ('".$pols."', '".$emisor_ID."', '".$receptor_ID."', '".$concepto."', '".$date."')", $link);
				}

				$refer_url = 'pols/#ok';
			}


		}
		


	} elseif (($_GET['b'] == 'crear-cuenta') AND ($_POST['nombre']) AND ($pol['pols'] >= $pol['config']['pols_cuentas'])) {
		$_POST['nombre'] = ucfirst(strip_tags($_POST['nombre']));

		pols_transferir($pol['config']['pols_cuentas'], $pol['user_ID'], '-1', 'Creacion nueva cuenta bancaria: '.$_POST['nombre']);
		mysql_query("INSERT INTO ".SQL."cuentas (nombre, user_ID, pols, nivel, time) VALUES ('".$_POST['nombre']."', '".$pol['user_ID']."', 0, 0, '".$date."')", $link);

		$refer_url = 'pols/cuentas/';

	} elseif (($_GET['b'] == 'eliminar-cuenta') AND ($_GET['ID'])) {
		mysql_query("DELETE FROM ".SQL."cuentas WHERE ID = '".$_GET['ID']."' AND pols = '0' AND nivel = '0' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'pols/cuentas/';
	}

	break;


case 'votacion':
	$votaciones_tipo = array('referendum', 'parlamento', 'sondeo', 'destituir', 'otorgar');
	if (($_GET['b'] == 'crear') AND (in_array($_POST['tipo'], $votaciones_tipo))) {
		
		$sc = get_supervisores_del_censo();

		if ((nucleo_acceso($vp['acceso'][$_POST['tipo']])) OR (($_POST['tipo'] == 'sondeo') AND (isset($sc[$pol['user_ID']])))) { 

			if ($_POST['votos_expire'] > 0) { } else { $_POST['votos_expire'] = 0; }

			for ($i=0;$i<12;$i++) { if (trim($_POST['respuesta'.$i]) != '') { $respuestas .= trim($_POST['respuesta'.$i]).'|'; } }

			$_POST['pregunta'] = strip_tags($_POST['pregunta']);
			$_POST['descripcion'] = gen_text($_POST['descripcion'], 'plain');

			
			switch ($_POST['tipo']) {
				case 'parlamento':
					$_POST['acceso_votar'] = 'cargo'; 
					$_POST['acceso_cfg_votar'] = '6 22';
					$_POST['votos_expire'] = $pol['config']['num_escanos'];
					break;

				case 'destituir': case 'otorgar':
					
					$result = mysql_query("SELECT nombre FROM ".SQL."estudios WHERE ID = '".$_POST['cargo']."' LIMIT 1", $link);
					while($r = mysql_fetch_array($result)){ $cargo_nombre = $r['nombre']; }

					$result = mysql_query("SELECT ID, nick FROM users WHERE nick = '".$_POST['nick']."' AND pais = '".PAIS."' LIMIT 1", $link);
					while($r = mysql_fetch_array($result)){ $cargo_user_ID = $r['ID']; $_POST['nick'] = $r['nick']; }

					if (($cargo_nombre) AND ($cargo_user_ID)) { // fuerza configuracion
						$_POST['time_expire'] = 86400;
						if ($_POST['cargo'] == 7) { $_POST['time_expire'] = (86400*2); }
						$_POST['acceso_votar'] = 'ciudadanos'; $_POST['acceso_cfg_votar'] = '';
						$ejecutar = $_POST['cargo'].'|'.$cargo_user_ID;
						$_POST['pregunta'] = '&iquest;Apruebas '.strtoupper($_POST['tipo']).' el cargo '.$cargo_nombre.' al ciudadano '.$_POST['nick'].'?';
						$_POST['descripcion'] = '&iquest;Estas a favor de <b>'.ucfirst($_POST['tipo']).'</b> el cargo <b>'.$cargo_nombre.'</b> al ciudadano <b>'.crear_link($_POST['nick']).'</b>?<br /><br />Al finalizar esta votaci&oacute;n, si el resultado mayor es a favor, se ejecutar&aacute; la acci&oacute;n autom&aacute;ticamente.';
						$respuestas = 'SI|NO|En Blanco|';
						$_POST['votos_expire'] = 0;
						if ($_POST['cargo'] == 22) { $_POST['acceso_votar'] = 'cargo'; $_POST['acceso_cfg_votar'] = '6 22'; $_POST['votos_expire'] = $pol['config']['num_escanos']; }	
					} else { exit; }
					break;
			}

			mysql_query("INSERT INTO votacion (pais, pregunta, descripcion, respuestas, time, time_expire, user_ID, estado, tipo, acceso_votar, acceso_cfg_votar, ejecutar, votos_expire) VALUES ('".PAIS."', '".$_POST['pregunta']."', '".$_POST['descripcion']."', '".$respuestas."', '".$date."', '".date('Y-m-d H:i:s', time() + $_POST['time_expire'])."', '".$pol['user_ID']."', 'ok', '".$_POST['tipo']."', '".$_POST['acceso_votar']."', '".$_POST['acceso_cfg_votar']."', '".$ejecutar."', '".$_POST['votos_expire']."')", $link);

			$result = mysql_query("SELECT ID FROM votacion WHERE user_ID = '".$pol['user_ID']."' AND pais = '".PAIS."' ORDER BY ID DESC LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $ref_ID = $r['ID']; }

			evento_chat('<b>['.strtoupper($_POST['tipo']).']</b> Creado por '.$pol['nick'].': <a href="/votacion/'.$ref_ID.'/"><b>'.$_POST['pregunta'].'</b></a> <span style="color:grey;">('.duracion($_POST['time_expire']).')</span>');
		}
	} elseif (($_GET['b'] == 'votar') AND ($_POST['voto'] != null) AND ($_POST['ref_ID'])) { 


			$result = mysql_query("SELECT pais, tipo, pregunta, estado, acceso_votar, acceso_cfg_votar, num, votos_expire FROM votacion WHERE ID = '".$_POST['ref_ID']."' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $tipo = $r['tipo']; $pregunta = $r['pregunta']; $estado = $r['estado']; $pais = $r['pais']; $acceso_votar = $r['acceso_votar']; $acceso_cfg_votar = $r['acceso_cfg_votar']; $num = $r['num']; $votos_expire = $r['votos_expire']; $num++; }

			if (($estado == 'ok') AND (in_array($tipo, $votaciones_tipo)) AND (nucleo_acceso($acceso_votar,$acceso_cfg_votar))) {
				$ha_votado = false;
				$result = mysql_query("SELECT ID FROM votacion_votos WHERE ref_ID = '".$_POST['ref_ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
				while($r = mysql_fetch_array($result)){ $ha_votado = true; }

				if ($ha_votado) { // MODIFICAR VOTO
					mysql_query("UPDATE votacion_votos SET voto = '".$_POST['voto']."', validez = '".($_POST['validez']=='true'?'true':'false')."' WHERE ref_ID = '".$_POST['ref_ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
				} else { // INSERTAR VOTO
					mysql_query("INSERT INTO votacion_votos (user_ID, ref_ID, voto, validez, autentificado) VALUES ('".$pol['user_ID']."', '".$_POST['ref_ID']."', '".$_POST['voto']."', '".($_POST['validez']=='true'?'true':'false')."', '".($_SESSION['pol']['dnie']=='true'?'true':'false')."')", $link);
					mysql_query("UPDATE votacion SET num = num + 1 WHERE ID = '".$_POST['ref_ID']."' LIMIT 1", $link);

					evento_chat('<b>['.strtoupper($tipo).']</b> Voto'.($_SESSION['pol']['dnie']=='true'?' <b>autentificado</b>':'').' de  '.$pol['nick'].' en: <a href="/votacion/'.$_POST['ref_ID'].'/">'.$pregunta.'</a> <span style="color:grey;">(votos <b>'.$num.'</b>'.($votos_expire>0?' de '.$votos_expire:'').')</span>', '0', '', false, 'e', $pais);
				}
			}

			header('Location: http://'.strtolower($pais).'.virtualpol.com/votacion/'.$_POST['ref_ID'].'/'); mysql_close($link); exit;

	} elseif (($_GET['b'] == 'eliminar') AND ($_GET['ID'])) { 
		$result = mysql_query("SELECT ID FROM votacion WHERE estado = 'ok' AND ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)) {
			mysql_query("DELETE FROM votacion WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
			mysql_query("DELETE FROM votacion_votos WHERE ref_ID = '".$_GET['ID']."'", $link);
		}
	} elseif (($_GET['b'] == 'concluir') AND ($_GET['ID'])) { 
		mysql_query("UPDATE votacion SET time_expire = '".$date."' WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' AND pais = '".PAIS."' AND tipo != 'destituir' AND tipo != 'otorgar' LIMIT 1", $link);
	}

	// actualizar info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM votacion WHERE estado = 'ok' AND pais = '".PAIS."'", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$r['num']."' WHERE dato = 'info_consultas' LIMIT 1", $link);
	}

	$refer_url = 'votacion/';
	break;







case 'foro':
	// añadir, editar
	if ((strlen($_POST['text']) > 1) AND ($_POST['subforo'])) {

		$acceso = false;
		$result = mysql_query("SELECT acceso_escribir, acceso_cfg_escribir FROM ".SQL."foros WHERE ID = '".$_POST['subforo']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)) { $acceso = nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir']); }

		if ($acceso) {

			$text = gen_text(trim($_POST['text']), 'plain');
			$time = $date;
			if (($_GET['b'] == 'hilo') AND ($_POST['title'])) {
				$title = strip_tags($_POST['title']);
				$url = gen_url($title);
				$exito = mysql_query("INSERT INTO ".SQL."foros_hilos (sub_ID, url, user_ID, title, time, time_last, text, cargo) VALUES ('".$_POST['subforo']."', '".$url."', '".$pol['user_ID']."', '".$title."', '".$time."', '".$time."', '".$text."', '".$_POST['encalidad']."')", $link);
				if (!$exito) {
					if (strlen($url) > 69) {
						 $url = substr($url, 0, 69);	
					}
					$url = $url.'-'.date('dmyHi');
					mysql_query("INSERT INTO ".SQL."foros_hilos (sub_ID, url, user_ID, title, time, time_last, text, cargo) VALUES ('".$_POST['subforo']."', '".$url."', '".$pol['user_ID']."', '".$title."', '".$time."', '".$time."', '".$text."', '".$_POST['encalidad']."')", $link);
				}

				evento_chat('<b>[FORO]</b> Nuevo hilo de '.$pol['nick'].': <a href="/'.$_POST['return_url'] . $url.'/"><b>'.$title.'</b></a>');

			} elseif ($_GET['b'] == 'reply') {
				
				if ($_POST['hilo'] != -1) {
					mysql_query("UPDATE ".SQL."foros_hilos SET time_last = '".$time."' WHERE ID = '".$_POST['hilo']."' LIMIT 1", $link);
					
					$result = mysql_unbuffered_query("SELECT title, num FROM ".SQL."foros_hilos WHERE ID = '".$_POST['hilo']."' LIMIT 1", $link);
					while($r = mysql_fetch_array($result)) { $title = $r['title']; }
					evento_chat('<b>[FORO]</b> Nuevo mensaje de '.$pol['nick'].': <a href="/'.$_POST['return_url'].'">'.$title.'</a>');
				} else {
					//$text = strip_tags($text);
				}
				
				mysql_query("INSERT INTO ".SQL."foros_msg (hilo_ID, user_ID, time, text, cargo) VALUES ('".$_POST['hilo']."', '".$pol['user_ID']."', '".$time."', '".$text."', '".$_POST['encalidad']."')", $link);
			}

			if ($_POST['hilo']) {
				$msg_num = 0;
				$result = mysql_query("SELECT COUNT(*) AS num FROM ".SQL."foros_msg WHERE hilo_ID = '".$_POST['hilo']."' AND estado = 'ok'", $link);
				while($r = mysql_fetch_array($result)) { $msg_num = $r['num']; }
				mysql_query("UPDATE ".SQL."foros_hilos SET num = '".$msg_num."' WHERE ID = '".$_POST['hilo']."' LIMIT 1", $link);
			}
		}

		$refer_url = $_POST['return_url'];
	}


	if (($_GET['b'] == 'borrar') AND ($_GET['ID']) AND ($_GET['c']) AND (nucleo_acceso($vp['acceso']['foro_borrar']))) {

		if ($_GET['c'] == 'hilo') {
			mysql_query("UPDATE ".SQL."foros_hilos SET estado = 'borrado', time_last = '".$date."' WHERE ID = '".$_GET['ID']."' AND estado = 'ok' LIMIT 1", $link);
		} elseif ($_GET['c'] == 'mensaje') {
			mysql_query("UPDATE ".SQL."foros_msg SET estado = 'borrado', time2 = '".$date."' WHERE ID = '".$_GET['ID']."' AND estado = 'ok' LIMIT 1", $link);
		}
		$refer_url = 'foro/papelera/';

	} elseif (($_GET['b'] == 'restaurar') AND ($_GET['ID']) AND ($_GET['c']) AND (nucleo_acceso($vp['acceso']['foro_borrar']))) {

		if ($_GET['c'] == 'hilo') {
			mysql_query("UPDATE ".SQL."foros_hilos SET estado = 'ok' WHERE ID = '".$_GET['ID']."' AND estado = 'borrado' LIMIT 1", $link);
		} elseif ($_GET['c'] == 'mensaje') {
			mysql_query("UPDATE ".SQL."foros_msg SET estado = 'ok', time2 = '0000-00-00 00:00:00' WHERE ID = '".$_GET['ID']."' AND estado = 'borrado' LIMIT 1", $link);
		}
		$refer_url = 'foro/papelera/';


	} elseif (($_GET['b'] == 'eliminarhilo') AND ($_GET['ID'])) {
		$result = mysql_unbuffered_query("SELECT ID FROM ".SQL."foros_hilos WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ $es_ok = true; }
		if ($es_ok) {
			mysql_query("DELETE FROM ".SQL."foros_hilos WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
			mysql_query("DELETE FROM ".SQL."foros_msg WHERE hilo_ID = '".$_GET['ID']."'", $link);
		}
		$refer_url = 'foro/';

	} elseif (($_GET['b'] == 'eliminarreply') AND ($_GET['hilo_ID']) AND ($_GET['ID'])) {

		$result = mysql_unbuffered_query("SELECT ID FROM ".SQL."foros_msg WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' AND time > '".date('Y-m-d H:i:s', time() - 3600)."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ $es_ok = true; }

		if ($es_ok) {
			mysql_query("DELETE FROM ".SQL."foros_msg WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
			mysql_query("UPDATE ".SQL."foros_hilos SET num = num-1 WHERE ID = '".$_GET['hilo_ID']."' LIMIT 1", $link);
		}
		if ($_GET['hilo_ID'] == '-1') {
			$refer_url = 'notas/';
		} else {
			$refer_url = 'foro/';
		}

	} elseif (($_GET['b'] == 'editar') AND ($_POST['text']) AND ($_POST['subforo'])) {
		$text = gen_text($_POST['text'], 'plain');

		if ($_POST['hilo']) { //msg
			mysql_query("UPDATE ".SQL."foros_msg SET text = '".$text."', cargo = '".$_POST['encalidad']."' WHERE ID = '".$_POST['hilo']."' AND estado = 'ok' AND user_ID = '".$pol['user_ID']."' AND time > '".date('Y-m-d H:i:s', time() - 3600)."' LIMIT 1", $link);
		} else { //hilo
			if (strlen($_POST['title']) >= 4) {
				$title = strip_tags($_POST['title']);
				mysql_query("UPDATE ".SQL."foros_hilos SET text = '".$text."', title = '".$title."', cargo = '".$_POST['encalidad']."' WHERE ID = '".$_POST['subforo']."' AND estado = 'ok' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
			}
		}
	}

	break;


case 'kick':

	if (($_GET['b'] == 'quitar') AND ($_GET['ID'])) {


		$es_policiaexpulsador = false;
		$result = mysql_unbuffered_query("SELECT ID, user_ID, autor FROM ".SQL."ban WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			if ($pol['user_ID'] == $r['autor']) {
				$es_policiaexpulsador = true;
			}
			$kickeado_id = $r['user_ID'];
			$kick_id = $r['ID']; 
		}
	
		if (($es_policiaexpulsador) OR (nucleo_acceso($vp['acceso']['kick_quitar']))) {
			mysql_query("UPDATE ".SQL."ban SET estado = 'cancelado' WHERE estado = 'activo' AND ID = '".$_GET['ID']."' LIMIT 1", $link); 
			if (mysql_affected_rows()==1) {
				$result = mysql_query("SELECT nick FROM users WHERE ID = '".$kickeado_id."' LIMIT 1", $link);
				while($r = mysql_fetch_array($result)){ $kickeado_nick = $r['nick'];}
				evento_log(14, $kick_id, $kickeado_id); // Kick cancelado
				evento_chat('<span style="color:red;"><img src="'.IMG.'kick.gif" alt="Kick" border="0" /> <b>[KICK]</b> El kick a <b>'.$kickeado_nick.'</b> ha sido cancelado por <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" /> <b>'.$pol['nick'].'</b>.</span>');
			}
		}
		$refer_url = 'control/kick/';

	} elseif ($_POST['nick']) {
		if ((substr($_POST['nick'], 0, 3) == 'ip-') AND (is_numeric(substr($_POST['nick'], 3)))) {
			// kick a anonimo
			$kick_cargo = 98; 
			$kick_user_ID = 0; 
			$kick_IP = substr($_POST['nick'], 3); 
			$result = mysql_query("SELECT nick FROM chats_msg WHERE IP = ".$kick_IP." ORDER BY msg_ID DESC LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $kick_nick = $r['nick']; }
			$_POST['razon'] = '['.$kick_nick.'] '.$_POST['razon'];
			$kick_pais = PAIS;
			$result = mysql_query("SELECT ID FROM ".SQL."ban WHERE IP = ".$kick_IP." AND estado = 'activo' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $user_kicked = true; }
			$el_userid = -1;
		} else {
			$result = mysql_query("SELECT ID, nick, IP, cargo, pais FROM users WHERE nick = '".$_POST['nick']."' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $kick_cargo = $r['cargo']; $kick_user_ID = $r['ID']; $kick_nick = $r['nick']; $kick_IP = '\''.$r['IP'].'\''; $kick_pais = $r['pais']; }
			$result = mysql_query("SELECT ID FROM ".SQL."ban WHERE user_ID = '".$kick_user_ID."' AND estado = 'activo' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $user_kicked = true; }
			$el_userid = $kick_user_ID;
		}


		$_POST['razon'] = ereg_replace("(^|\n| )[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", strip_tags($_POST['razon']));
		$_POST['motivo'] = ereg_replace("(^|\n| )[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", strip_tags($_POST['motivo']));

		if (
(nucleo_acceso($vp['acceso']['kick'])) AND 
($kick_user_ID >= 0) AND 
($user_kicked != true) AND 
((($kick_cargo != 7) AND ($kick_cargo != 13)) OR ($kick_pais != PAIS)) AND
($_POST['razon']) AND
($_POST['expire'] <= 777600)
) {
			$_POST['razon'] = ucfirst(strip_tags($_POST['razon']));
			$expire = date('Y-m-d H:i:s', time() + $_POST['expire']);
			mysql_query("INSERT INTO ".SQL."ban (user_ID, autor, expire, razon, estado, tiempo, IP, cargo, motivo) VALUES ('".$el_userid."', ".$pol['user_ID'].", '".$expire."', '".$_POST['razon']."', 'activo', '".$_POST['expire']."', ".$kick_IP.", '".$pol['cargo']."', '".$_POST['motivo']."')", $link);

			$kick_msg = '<span style="color:red;"><img src="'.IMG.'kick.gif" alt="Kick" border="0" /> <b>[KICK] '.$kick_nick.'</b> ha sido kickeado por <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" /> <b>'.$pol['nick'].'</b>, durante <b>'.duracion($_POST['expire']).'</b>. Razon: <em>'.$_POST['razon'].'</em> (<a href="/control/kick/">Ver kicks</a>)</span>';
			evento_chat($kick_msg);
			if ((isset($_POST['chat_ID'])) AND ($_POST['chat_ID'] != 1) AND ($_POST['chat_ID'] != 2) AND ($_POST['chat_ID'] != 3) AND ($_POST['chat_ID'] != 4)) { evento_chat($kick_msg, 0, $_POST['chat_ID']); }
		}
		$refer_url = 'control/kick/';
	}
	break;



case 'mensaje-leido':
	if ($_GET['ID'] == 'all') {
		mysql_query("UPDATE ".SQL_MENSAJES." SET leido = '1' WHERE recibe_ID = '".$pol['user_ID']."'", $link);
	} elseif ($_GET['ID']) {
		mysql_query("UPDATE ".SQL_MENSAJES." SET leido = '1' WHERE ID = '".$_GET['ID']."' AND recibe_ID = '".$pol['user_ID']."' LIMIT 1", $link);
	}
	$refer_url = 'msg/';
	break;

case 'borrar-mensaje':
	if (($_GET['ID'])) {
		mysql_query("DELETE FROM ".SQL_MENSAJES." WHERE ID = '".$_GET['ID']."' AND recibe_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'msg/';
	}
	break;


case 'enviar-mensaje':

	if ((!$_GET['b']) AND ($_POST['text']) AND ($_POST['para'])) {
		$text = gen_text($_POST['text'], 'plain');
		if (($_POST['para'] == 'ciudadano') AND ($_POST['nick'])) {
			$envio_urgente = 0;

			$mp_num = 1;
			$enviar_nicks = '';
			$nicks_array = explode(' ', $_POST['nick'].' ');
			foreach ($nicks_array AS $el_nick) {
				if (($mp_num <= 9) AND ($el_nick)) { 
					// Maximo 9 ciudadanos. Para no suplantar el "mensaje global".
					if ($enviar_nicks != '') { $enviar_nicks .= ','; }
					$enviar_nicks .= "'".$el_nick."'";
					$mp_num++;
				}
			}
			
			$result = mysql_query("SELECT ID, pais FROM users WHERE nick IN (".$enviar_nicks.") AND estado != 'expulsado'", $link);
			while($r = mysql_fetch_array($result)){ 
				mysql_query("INSERT INTO ".SQL_MENSAJES." (envia_ID, recibe_ID, time, text, leido, cargo) VALUES ('".$pol['user_ID']."', '".$r['ID']."', '".$date."', '".$text."', '0', '".$_POST['calidad']."')", $link);
				
				// MENSAJE URGENTE
				if (($_POST['urgente'] == '1') AND ($pol['pols'] >= $pol['config']['pols_mensajeurgente'])) { 
					$asunto = '[VirtualPol] Tienes un mensaje urgente de '.$pol['nick'];
					$mensaje = 'Hola Ciudadano,<br /><br />Has recibido un mensaje urgente enviado por el Ciudadano: '.$pol['nick'].'.<br /><br />Para leerlo has de entrar aquí: <a href="http://'.HOST.'/msg/">http://'.HOST.'/msg/</a><br /><br />Mensaje de '.$pol['nick'].':<hr />'.$text.'<hr /><br /><br />Nota: Si este aviso te ha resultado molesto puedes defender tu derecho apoyandote en la Justicia de '.PAIS.'.<br /><br /><br />VirtualPol<br />http://'.HOST;
					enviar_email($r['ID'], $asunto, $mensaje); 
					$envio_urgente++;
				}

				evento_chat('<b>Nuevo mensaje privado</b> (<a href="http://'.strtolower($r['pais']).DEV.'.virtualpol.com/msg/"><b>Leer!</b></a>)', $r['ID'], -1, false, 'p'); 
			}

			if ($envio_urgente > 0) {
				pols_transferir(round($pol['config']['pols_mensajeurgente']*$envio_urgente), $pol['user_ID'], '-1', 'Envio mensaje urgente'.($envio_urgente>1?' x'.$envio_urgente:''));
			}


		} elseif (($_POST['para'] == 'cargo') AND ($_POST['cargo_ID'] == 'SC')) {

			$sc = get_supervisores_del_censo();

			foreach ($sc AS $user_ID => $nick) {
				if ($user_ID != $pol['user_ID']) {
					mysql_query("INSERT INTO ".SQL_MENSAJES." (envia_ID, recibe_ID, time, text, leido, cargo, recibe_masivo) VALUES ('".$pol['user_ID']."', '".$user_ID."', '".$date."', '<b>Mensaje multiple: Supervisor del Censo</b><br />".$text."', '0', '".$_POST['calidad']."', 'SC')", $link);
					evento_chat('<b>Nuevo mensaje privado</b> (<a href="http://'.strtolower(PAIS).DEV.'.'.URL.'/msg/"><b>Leer!</b></a>)', $user_ID, -1, false, 'p');
				}
			}
		} elseif (($_POST['para'] == 'cargo') AND ($_POST['cargo_ID'])) {

			$result = mysql_query("SELECT nombre FROM ".SQL."estudios WHERE ID = '".$_POST['cargo_ID']."' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $cargo_nombre = $r['nombre']; }

			if ($_POST['cargo_ID'] == '55') {
				$result = mysql_query("SELECT user_ID FROM ".SQL."estudios_users WHERE cargo = '1'  AND estado = 'ok' AND ID_estudio IN (55, 56, 57) LIMIT 80", $link);
			} else {
				$result = mysql_query("SELECT user_ID FROM ".SQL."estudios_users WHERE cargo = '1'  AND estado = 'ok' AND ID_estudio = '".$_POST['cargo_ID']."' LIMIT 80", $link);
			}
			while($r = mysql_fetch_array($result)){ 
				if ($r['user_ID'] != $pol['user_ID']) {
					mysql_query("INSERT INTO ".SQL_MENSAJES." (envia_ID, recibe_ID, time, text, leido, cargo, recibe_masivo) VALUES ('".$pol['user_ID']."', '".$r['user_ID']."', '".$date."', '<b>Mensaje multiple: ".$cargo_nombre."</b><br />".$text."', '0', '".$_POST['calidad']."', '".$_POST['cargo_ID']."')", $link);
					evento_chat('<b>Nuevo mensaje privado</b> (<a href="http://'.strtolower(PAIS).DEV.'.'.URL.'/msg/"><b>Leer!</b></a>)', $r['user_ID'], -1, false, 'p');
				}
			}
		} elseif (($_POST['para'] == 'todos') AND ($pol['pols'] >= $pol['config']['pols_mensajetodos'])) {
			// MENSAJE GLOBAL
			$text = '<b>Mensaje Global:</b> ('.pols($pol['config']['pols_mensajetodos']).' '.MONEDA.')<hr />'.$text;
			pols_transferir($pol['config']['pols_mensajetodos'], $pol['user_ID'], '-1', 'Mensaje Global');
			$result = mysql_query("SELECT ID FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."'", $link);
			while($r = mysql_fetch_array($result)){ 
				mysql_query("INSERT INTO ".SQL_MENSAJES." (envia_ID, recibe_ID, time, text, leido, cargo) VALUES ('".$pol['user_ID']."', '".$r['ID']."', '".$date."', '".$text."', '0', '".$_POST['calidad']."')", $link);
			}
		}

	}
	$refer_url = 'msg/';

	break;


case 'elecciones-generales':

	$ID_partido = $_POST['ID_partido'];
	if ((!$_GET['b']) AND ($pol['config']['elecciones_estado'] == 'elecciones') AND ($pol['estado'] == 'ciudadano')) {

		$fecha_24_antes = date('Y-m-d H:i:00', strtotime($pol['config']['elecciones_inicio']) - $pol['config']['elecciones_antiguedad']);

		//fecha registro?
		$result = mysql_query("SELECT fecha_registro FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ $fecha_registro = $r['fecha_registro']; }

		//ha votado?
		$result = mysql_query("SELECT ID FROM ".SQL."elecciones WHERE user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ $ha_votado = $r['ID']; }
		
		if ((!$ha_votado) AND ($fecha_registro < $fecha_24_antes)) {

			$nav = $_SERVER['HTTP_USER_AGENT'];
			$IP = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
			$time = $date;


			if ($pol['config']['elecciones'] == 'parl') {
				$diputados = '';
				$votos_count = 0;
				foreach ($_POST as $diputado_ID => $valor) {
					if (($valor == '1') AND ($votos_count <= $pol['config']['num_escanos'])) {
						// existe diputado
						$result = mysql_query("SELECT user_ID FROM ".SQL."partidos_listas WHERE user_ID = '".$diputado_ID."' LIMIT 1", $link);
						while($r = mysql_fetch_array($result)){ 
							if ($diputados) { $diputados .= '.'; }
							$diputados .= $diputado_ID;
							$votos_count++;
						}
					}
				} 

				mysql_query("INSERT INTO ".SQL."elecciones (ID_partido, user_ID, nav, IP, time) VALUES ('".$diputados."', '".$pol['user_ID']."', '".$nav."', '".$IP."', '".$time."')", $link);
				mysql_query("UPDATE users SET num_elec = num_elec + 1 WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
				mysql_query("UPDATE ".SQL."elec SET num_votos = num_votos + 1 ORDER BY time DESC LIMIT 1", $link);


			} else {
				// PRES
				if ($ID_partido == '0') { //BLANCO
					mysql_query("INSERT INTO ".SQL."elecciones (ID_partido, user_ID, nav, IP, time) VALUES ('0', '".$pol['user_ID']."', '".$nav."', '".$IP."', '".$time."')", $link);
					mysql_query("UPDATE users SET num_elec = num_elec + 1 WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
					mysql_query("UPDATE ".SQL."elec SET num_votos = num_votos + 1 ORDER BY time DESC LIMIT 1", $link);
					
				} else {
					$result = mysql_query("SELECT ID, 
(SELECT COUNT(ID) FROM ".SQL."partidos_listas WHERE ID_partido = ".SQL."partidos.ID LIMIT 1) AS num_lista
FROM ".SQL."partidos 
WHERE estado = 'ok' 
AND ID = '".$ID_partido."'
AND fecha_creacion < '".$fecha_24_antes."'
LIMIT 1", $link);
					while($r = mysql_fetch_array($result)){
						mysql_query("INSERT INTO ".SQL."elecciones (ID_partido, user_ID, nav, IP, time) VALUES ('".$ID_partido."', '".$pol['user_ID']."', '".$nav."', '".$IP."', '".$time."')", $link);
						mysql_query("UPDATE users SET num_elec = num_elec + 1 WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
						mysql_query("UPDATE ".SQL."elec SET num_votos = num_votos + 1 ORDER BY time DESC LIMIT 1", $link);
					}
				}
			}

			$result = mysql_query("SELECT num_votantes FROM ".SQL."elec ORDER BY time DESC LIMIT 1", $link);
			while($r = mysql_fetch_array($result)) { $num_votantes = $r['num_votantes']; }

			$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."elecciones", $link);
			while($r = mysql_fetch_array($result)) { $num_votos = $r['num']; }

			evento_chat('<b>[ELECCIONES]</b> Nuevo voto (<a href="/elecciones/"><b>info</b></a>, <b style="color:grey;">'.$num_votos.' votos, '.number_format((($num_votos * 100) / $num_votantes), 1, '.', '').'%</b>)', '0', '0', true); 

		}

	}
	$refer_url = 'elecciones/';
	
	break;

case 'partido-lista':
	$b = $_GET['b'];
	$ID_partido = $_GET['ID'];

	if (($b) AND ($ID_partido) AND ($pol['config']['elecciones_estado'] != 'elecciones')) {

		$result = mysql_query("SELECT ID_presidente, siglas FROM ".SQL."partidos WHERE ID = '".$ID_partido."' AND ID_presidente = '".$pol['user_ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){
			$siglas = $r['siglas'];
			if ($b == 'edit') {
				mysql_query("UPDATE ".SQL."partidos SET descripcion = '".gen_text($_POST['text'])."' WHERE ID = '".$ID_partido."' LIMIT 1", $link);
			} elseif (($b == 'add') AND ($_POST['user_ID'])) {
				mysql_query("INSERT INTO ".SQL."partidos_listas (ID_partido, user_ID) VALUES ('".$ID_partido."', '".$_POST['user_ID']."')", $link);
			} elseif (($b == 'del') AND ($_POST['user_ID'])) {
				mysql_query("DELETE FROM ".SQL."partidos_listas WHERE user_ID = '".$_POST['user_ID']."' AND ID_partido = '".$ID_partido."' LIMIT 1", $link);
			} elseif (($b == 'ceder-presidencia') AND ($_POST['user_ID'])) {
				mysql_query("UPDATE ".SQL."partidos SET ID_presidente = '".$_POST['user_ID']."' WHERE ID = '".$ID_partido."' LIMIT 1", $link);
			} elseif (($b == 'del-afiliado') AND ($_POST['user_ID'])) {
				mysql_query("UPDATE users SET partido_afiliado = '0' WHERE partido_afiliado = '".$ID_partido."' AND ID = '".$_POST['user_ID']."' LIMIT 1", $link);
			}

			$refer_url = 'partidos/'.strtolower($siglas).'/editar/';
		}
	}
	break;





case 'cargo':
	$b = $_GET['b'];
	$cargo_ID = $_GET['ID'];

	if (($_GET['b'] == 'dimitir') AND ($_GET['ID']) AND ($_POST['pais'] == PAIS)) {

		cargo_del($_GET['ID'], $pol['user_ID'], false);

		$result2 = mysql_query("SELECT nombre FROM ".SQL."estudios WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
		while($r2 = mysql_fetch_array($result2)){ 
			evento_chat('<b>[CARGO] '.crear_link($pol['nick']).' dimite</b> del cargo <img src="'.IMG.'cargos/'.$_GET['ID'].'.gif" />'.$r2['nombre']);
		}
		$refer_url = 'perfil/'.strtolower($pol['nick']).'/';



	} elseif (($_GET['b'] == 'cederOLD') AND ($_POST['user_ID'])) {

		$result = mysql_query("SELECT user_ID FROM ".SQL."estudios_users WHERE user_ID = '".$_POST['user_ID']."' AND estado = 'ok' AND cargo = '0' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){
			$diputado_aprobado = true;
		}

		if (($pol['cargos'][6]) AND ($diputado_aprobado)) { //es diputado de momento
			cargo_add('6', $_POST['user_ID']); //hace diputado
			cargo_del('6', $pol['user_ID']); //quita diputado
		}
		
		$refer_url = 'perfil/'.strtolower($pol['nick']).'/';

	} elseif (($b) AND ($cargo_ID)) {
		$result = mysql_query("SELECT ID, asigna, nombre FROM ".SQL."estudios WHERE ID = '".$cargo_ID."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){

			if ((($pol['cargos'][$r['asigna']]) AND ($r['ID'] != 7)) OR 
(($r['ID'] != 19) AND ($r['asigna'] == 7) AND ($pol['cargos'][19]) AND ($r['ID'] != 7))) { 


				$result2 = mysql_query("SELECT nick, online, fecha_registro FROM users WHERE ID = '".$_POST['user_ID']."' AND pais = '".PAIS."' LIMIT 1", $link);
				while($r2 = mysql_fetch_array($result2)){ $nick_asignado = $r2['nick']; $asignado['fecha_registro'] = $r2['fecha_registro']; $asignado['online'] = $r2['online']; }

				if ($nick_asignado) {
					if ($b == 'add') {
						if (($cargo_ID != 21) OR (($cargo_ID == 21) AND (strtotime($asignado['fecha_registro']) <= (time()-8640000)) AND ($asignado['online'] >= 864000))) {
							cargo_add($cargo_ID, $_POST['user_ID']);
						}
					}
					elseif ($b == 'del') { 
						cargo_del($cargo_ID, $_POST['user_ID']); 
					}
				}
				$refer_url = 'cargos/'.$cargo_ID.'/';
			}
		}
	}

	break;




case 'eliminar-partido':
	if (($pol['config']['elecciones_estado'] != 'elecciones')) {
		$result = mysql_query("SELECT ID FROM ".SQL."partidos WHERE ID_presidente = '".$pol['user_ID']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){
			mysql_query("DELETE FROM ".SQL."partidos WHERE ID = '".$r['ID']."' LIMIT 1", $link);
			mysql_query("DELETE FROM ".SQL."partidos_listas WHERE ID_partido = '".$r['ID']."' LIMIT 1", $link);
			evento_log(5, $r['ID']);
		}
	}

	// actualizar info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."partidos WHERE estado = 'ok'", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$r['num']."' WHERE dato = 'info_partidos' LIMIT 1", $link);
	}

	$refer_url = 'partidos/';
	break;






case 'eliminar-documento':
	
	$result = mysql_query("SELECT ID, acceso_escribir, acceso_cfg_escribir FROM docs WHERE url = '".$_GET['url']."' AND pais = '".PAIS."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){ 
		if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) {
			mysql_query("UPDATE docs SET estado = 'del' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
			evento_log(8, $r['ID']);
			
		}
		$refer_url = 'doc/';
	}

	// actualiza info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM docs WHERE estado = 'ok' AND pais = '".PAIS."'", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$r['num']."' WHERE dato = 'info_documentos' LIMIT 1", $link);
	}
	break;


case 'editar-documento':
	if (($_POST['titulo']) AND ($_POST['text']) AND ($_POST['cat'])) {
		$text = gen_text($_POST['text']);
		$text = str_replace("../../", "/doc/", $text);
		$_POST['titulo'] = strip_tags($_POST['titulo']);

		$result = mysql_query("SELECT ID, acceso_escribir, acceso_cfg_escribir FROM docs WHERE url = '".$_POST['url']."' AND pais = '".PAIS."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ 
			if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) {

				if (nucleo_acceso($_POST['acceso_escribir'], $_POST['acceso_cfg_escribir']) == false) { 
					// Si no tienes el acceso de escribir... se queda como esta.
					$_POST['acceso_escribir'] = $r['acceso_escribir']; 
					$_POST['acceso_cfg_escribir'] = $r['acceso_cfg_escribir']; 
				}

				mysql_query("UPDATE docs SET cat_ID = '".$_POST['cat']."', text = '".$text."', title = '".$_POST['titulo']."', time_last = '".$date."', acceso_leer = '".$_POST['acceso_leer']."', acceso_escribir = '".$_POST['acceso_escribir']."', acceso_cfg_leer = '".$_POST['acceso_cfg_leer']."', acceso_cfg_escribir = '".$_POST['acceso_cfg_escribir']."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
				evento_log(7, $r['ID']);
			}
		}
	}
	$refer_url = 'doc/'.$_POST['url'].'/';
	break;

case 'crear-documento':
	if ((strlen($_POST['title']) > 3) AND (strlen($_POST['title']) < 200) AND ($_POST['cat'])) {

		$url = gen_url($_POST['title']);
		$text = gen_text($_POST['text']);

		mysql_query("INSERT INTO docs 
(pais, url, title, text, time, time_last, estado, cat_ID, acceso_leer, acceso_escribir, acceso_cfg_leer, acceso_cfg_escribir) 
VALUES ('".PAIS."', '".$url."', '".$_POST['title']."', '".$text."', '".$date."', '".$date."', 'ok', '".$_POST['cat']."', '".$_POST['acceso_leer']."', '".$_POST['acceso_escribir']."', '".$_POST['acceso__cfg_leer']."', '".$_POST['acceso_cfg_escribir']."')", $link);
		evento_log(6, $url);
	}

	// actualizacion de info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM docs WHERE estado = 'ok' AND pais = '".PAIS."'", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$r['num']."' WHERE dato = 'info_documentos' LIMIT 1", $link);
	}

	$refer_url = 'doc/'.$url.'/';
	break;



case 'afiliarse':
	if (($pol['config']['elecciones_estado'] != 'elecciones')) {
		mysql_query("UPDATE users SET partido_afiliado = '".$_POST['partido']."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		mysql_query("DELETE FROM ".SQL."partidos_listas WHERE user_ID = '".$pol['user_ID']."'", $link);
		evento_log(9, $_POST['partido']);
	}
	$refer_url = 'perfil/'.strtolower($pol['nick']).'/';
	break;

case 'crear-partido':
	$_POST['siglas'] = preg_replace("/[^[a-z-]/i", "", $_POST['siglas']);

	$ya_es_presidente = false;
	$result = mysql_query("SELECT ID FROM ".SQL."partidos WHERE ID_presidente = '".$pol['user_ID']."'", $link);
	while($r = mysql_fetch_array($result)){ $ya_es_presidente = true; }

	if (($pol['config']['elecciones_estado'] != 'elecciones') AND (strlen($_POST['siglas']) <= 12) AND (strlen($_POST['siglas']) >= 2) AND ($pol['pols'] >= $pol['config']['pols_partido']) AND ($_POST['nombre']) AND ($ya_es_presidente == false)) {

		$_POST['descripcion'] = gen_text($_POST['descripcion']);

		pols_transferir($pol['config']['pols_partido'], $pol['user_ID'], '-1', 'Creacion nuevo partido: '.$_POST['siglas']);

		mysql_query("INSERT INTO ".SQL."partidos 
(ID_presidente, fecha_creacion, siglas, nombre, descripcion, estado) 
VALUES ('".$pol['user_ID']."', '".$date."', '".strtoupper($_POST['siglas'])."', '".$_POST['nombre']."', '".$_POST['descripcion']."', 'ok')
", $link);


		$result = mysql_query("SELECT ID FROM ".SQL."partidos WHERE siglas = '".$_POST['siglas']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){ $partido_ID = $r['ID']; }
		evento_log(3, $partido_ID);
	}

	// actualizar info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."partidos WHERE estado = 'ok'", $link);
	while($r = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$r['num']."' WHERE dato = 'info_partidos' LIMIT 1", $link);
	}

	$refer_url = 'partidos/';
	break;
}

}


if ($_GET['a'] == 'logout') {
	if ($_SESSION) { unset($_SESSION); session_unset(); session_destroy(); }
	header('Location: '.REGISTRAR.'login.php?a=logout');
	mysql_close($link); exit;
}

if ($link) { mysql_close($link); }
header('Location: http://'.HOST.'/'.$refer_url);
?>
