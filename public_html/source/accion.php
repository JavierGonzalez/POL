<?php 
include('inc-login.php');
include('inc-functions-accion.php'); // functions extra

// load config full
$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

// load user cargos
$pol['cargos'] = cargos();

// prevent SSX
if ($_GET['ID']) { $_GET['ID'] = mysql_real_escape_string($_GET['ID']); }

// Solo ciudadanos
if (
((PAIS == $pol['pais']) AND ($pol['estado'] == 'ciudadano'))
OR ($pol['estado'] == 'desarrollador')
OR (($pol['estado'] == 'kickeado') AND ($_GET['a'] == 'rechazar-ciudadania'))
OR (($pol['estado'] == 'kickeado') AND ($_GET['a'] == 'elecciones-generales'))
OR (($pol['estado'] == 'extranjero') AND ($_GET['a'] == 'foro'))
OR (($pol['estado'] == 'extranjero') AND ($_GET['a'] == 'mercado'))
) {


switch ($_GET['a']) { // #####################################################





case 'chat':

	if (($_GET['b'] == 'solicitar') AND ($pol['pols'] >= $pol['config']['pols_crearchat']) AND ($_POST['nombre']) AND ($_POST['pais'])) {

		$nombre = $_POST['nombre'];
		$url = gen_url($nombre);


		mysql_query("INSERT INTO chats (pais, url, titulo, user_ID, fecha_creacion, fecha_last, dias_expira) 
VALUES ('".$_POST['pais']."', '".$url."', '".ucfirst($nombre)."', '".$pol['user_ID']."', '".$date."', '".$date."', '".$pol['config']['chat_diasexpira']."')", $link);

		$result = mysql_query("SELECT chat_ID FROM chats WHERE url = '".$url."' AND user_ID = '".$pol['user_ID']."' AND pais = '".$_POST['pais']."' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)) {
			pols_transferir($pol['config']['pols_crearchat'], $pol['user_ID'], '-1', 'Solicitud chat: '.$nombre);
		}

	} elseif (($_GET['b'] == 'editar') AND ($_POST['chat_ID'])) {

		//if ($_POST['acceso_escribir'] == 'anonimos') { $_POST['acceso_escribir'] = 'ciudadanos'; }

		mysql_query("UPDATE chats 
SET acceso_leer = '".$_POST['acceso_leer']."', 
acceso_escribir = '".$_POST['acceso_escribir']."', 
acceso_cfg_leer = '".strtolower($_POST['acceso_cfg_leer'])."', 
acceso_cfg_escribir = '".strtolower($_POST['acceso_cfg_escribir'])."'
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

	if (($pol['nivel'] >= 98) AND ($_POST['pais'] == PAIS)) {
		$elecciones_dias_quedan = ceil((strtotime($pol['config']['elecciones_inicio']) - time()) / 86400);
		$elecciones_frecuencia_dias = ceil($pol['config']['elecciones_frecuencia'] / 86400);
		if (($elecciones_dias_quedan > 5) AND ($elecciones_dias_quedan < $elecciones_frecuencia_dias)) {
			mysql_query("DELETE FROM ".SQL."partidos_listas", $link);
			evento_chat('<b>[GOBIERNO]</b> Se han vaciado las listas electorales ('.crear_link($pol['nick']).', <a href="/control/despacho-oval/">Despacho Oval</a>)');
		}
	}

	$refer_url = 'partidos/';

	break;

case 'historia':
	$_POST['hecho'] = trim($_POST['hecho']);
	if (($_GET['b'] == 'add') AND ($_POST['hecho'] != '')) {
		mysql_query("INSERT INTO hechos (time, nick, texto, estado, time2, pais) VALUES ('".$_POST['year']."-".$_POST['mes']."-".$_POST['dia']."', '".$pol['nick']."', '".strip_tags($_POST['hecho'],'<b>,<a>')."', 'ok', '".$date."', '".$_POST['pais']."')", $link);
	} elseif ($_GET['b'] == 'del') {
		mysql_query("UPDATE hechos SET estado = 'del' WHERE ID = '".$_GET['ID']."' AND (nick = '".$pol['nick']."' OR '".$pol['nivel']."' = '100') LIMIT 1", $link);
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

	if ((($pol['estado'] == 'desarrollador') OR ($pol['cargo'] == 9)) AND ($_POST['pols'] <= 5000) AND ($_POST['pols'] > 0)) {

		$result = mysql_query("SELECT ID, nick FROM users 
WHERE nick = '".$_POST['nick']."' AND estado = 'ciudadano' AND pais = '".PAIS."'
LIMIT 1", $link);
		while($row = mysql_fetch_array($result)) {
		
			pols_transferir($_POST['pols'], $row['ID'], '-1', '<b>SANCION ('.$pol['nick'].')&rsaquo;</b> '.strip_tags($_POST['concepto']));

			evento_chat('<b>[SANCION] '.crear_link($row['nick']).' ha sido sancionado con '.pols($_POST['pols']).' '.MONEDA.' (<a href="/control/judicial/">Ver sanciones</a>)</b>');
		}

	}
	$refer_url = 'control/judicial/';

	break;

case 'pass':
	if (($pol['estado'] == 'desarrollador') AND ($_GET['nick'])) {


		$result = mysql_query("SELECT ID, nick, email FROM users WHERE nick = '".$_GET['nick']."' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)) {
			$email = $row['email'];
			$user_ID = $row['ID'];
			$nick = $row['nick'];
		
		}

		if ($email) {
			$new_pass = $nick.rand(1000,9999);
			$asunto = '[VirtualPol] Contrase&ntilde;a reseteada del usuario: '.$nick;
			$mensaje = 'Hola Ciudadano,<br /><br />Se ha procedido a resetear tu contrase&ntilde;a de seguridad. Por lo tanto tu contrase&ntilde;a ha cambiado.<br /><br /><hr />Usuario: <b>'.$_GET['nick'].'</b><br />Contrase&ntilde;a nueva: <b>'.$new_pass.'</b><br />Login en: <a href="http://www.virtualpol.com/">http://www.virtualpol.com</a><hr /><br />Gracias, nos vemos en VirtualPol ;)<br /><br /><br />VirtualPol<br />http://'.HOST;
			enviar_email($user_ID, $asunto, $mensaje); 

			mysql_query("UPDATE users SET pass = '".md5($new_pass)."' WHERE ID = '".$user_ID."' LIMIT 1", $link);
		}	
	}
	break;

case 'rechazar-ciudadania':

	$user_ID = false;
	$result3 = mysql_query("SELECT IP, pols, nick, ID, ref, estado,
(SELECT SUM(pols) FROM ".SQL."cuentas WHERE user_ID = '".$pol['user_ID']."') AS pols_cuentas 
FROM users 
WHERE ID = '".$pol['user_ID']."' AND estado = 'ciudadano' AND pais = '".PAIS."'
LIMIT 1", $link);
	while($row3 = mysql_fetch_array($result3)) {
		$user_ID = $row3['ID']; 
		$estado = $row3['estado']; 
		$pols = ($row3['pols'] + $row3['pols_cuentas']); 
		$nick = $row3['nick']; 
		$ref = $row3['ref']; 
		$IP = $row3['IP'];
	}
	if (($user_ID) AND ($_POST['pais'] == PAIS) AND ($pols >=0)) { // RECHAZAR CIUDADANIA

		// moneda
		if ($pols >= 0) {
			$pols_arancel = round(($pols*$pol['config']['arancel_salida'])/100);
		} else { $pols_arancel = 0; }
		$pols = $pols - $pols_arancel;

		evento_log(13); // rechazo de ciudadania
		evento_chat('<b>[#] '.crear_link($nick).' rechaza la Ciudadania</b> de '.PAIS.' (llevandose consigo: '.pols($pols).' '.MONEDA.')');
		
		

		pols_transferir($pols_arancel, $user_ID, '-1', 'Arancel de salida (rechazo de ciudadania) '.$pol['config']['arancel_salida'].'%');

		mysql_query("UPDATE users SET estado = 'turista', pais = 'ninguno', nivel = '1', cargo = '0', nota = '0.0', pols = '".$pols."', rechazo_last = '".$date."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		
		if ($pol['config']['elecciones_estado'] == 'elecciones') { 
			mysql_query("UPDATE ".SQL."elecciones SET ID_partido = '-1' WHERE user_ID = '".$user_ID."' LIMIT 1", $link);
		}
		
		mysql_query("DELETE FROM ".SQL."partidos_listas WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM ".SQL."partidos WHERE ID_presidente = '".$user_ID."'", $link);
		mysql_query("DELETE FROM ".SQL."empresas WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM ".SQL."mercado WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM ".SQL."estudios_users WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM ".SQL."cuentas WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM ".SQL."mapa WHERE user_ID = '".$user_ID."'", $link);
		mysql_query("DELETE FROM ".SQL."pujas WHERE user_ID = '".$user_ID."'", $link);
	}
	header('Location: '.REGISTRAR);
	exit;
	break;


case 'expulsar':
	if (
(($pol['estado'] == 'desarrollador') OR ($pol['cargo'] == 21) OR ($pol['cargo'] == 9) OR ($pol['cargo'] == 7))
 AND ($_GET['b'] == 'desexpulsar') 
 AND ($_GET['ID'])
) {
	
		$result = mysql_query("SELECT ID, user_ID, tiempo  FROM ".SQL_EXPULSIONES." WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
		while ($row = mysql_fetch_array($result)) {
			mysql_query("UPDATE users SET estado = 'ciudadano' WHERE ID = '".$row['user_ID']."' LIMIT 1", $link);
			mysql_query("UPDATE ".SQL_EXPULSIONES." SET estado = 'cancelado' WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);

			evento_chat('<span class="expulsado"><img src="/img/expulsar.gif" title="Expulsion" border="0" /> <b>[EXPULSION] '.$row['tiempo'].'</b> ha sido <b>DESexpulsado</b> de VirtualPol por <img src="/img/cargos/'.$pol['cargo'].'.gif" border="0" /> <b>'.$pol['nick'].'</b> (<a href="/control/expulsiones/">Ver expulsiones</a>)</span>');
		}

	} elseif (($pol['estado'] == 'desarrollador') OR ($pol['cargo'] == 21) AND ($_GET['razon'])) {

		$result = mysql_query("SELECT nick, ID FROM users 
WHERE ID = '".$_GET['ID']."'
AND estado != 'expulsado'
AND (cargo = '0' OR cargo = '21')
LIMIT 1", $link);
		while ($row = mysql_fetch_array($result)) {
			mysql_query("UPDATE users SET estado = 'expulsado' WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
			
			mysql_query("INSERT INTO ".SQL_EXPULSIONES." (user_ID, autor, expire, razon, estado, tiempo, IP, cargo) VALUES ('".$row['ID']."', '".$pol['user_ID']."', '".$date."', '".ucfirst(strip_tags($_GET['razon']))."', 'expulsado', '".$row['nick']."', '0', '".$pol['cargo']."')", $link);

			evento_chat('<span class="expulsado"><img src="/img/expulsar.gif" title="Expulsion" border="0" /> <b>[EXPULSION] '.$row['nick'].'</b> ha sido expulsado de VirtualPol. Razon: <b>'.$_GET['razon'].'</b> (<a href="/control/expulsiones/">Ver expulsiones</a>)</span>');
		}
	}
	$refer_url = 'control/expulsiones/';
	break;

case 'voto':
	if (($_GET['b'] == 'confianza') AND ($_GET['ID'] != $pol['user_ID']) AND (($_REQUEST['voto_confianza'] == '-1') OR ($_REQUEST['voto_confianza'] == '0') OR ($_REQUEST['voto_confianza'] == '1'))) {

		// has votado ya a este usuario?
		$hay_voto = false;
		$result = mysql_query("SELECT voto FROM ".SQL_VOTOS." WHERE estado = 'confianza' AND uservoto_ID = '".$pol['user_ID']."' AND user_ID = '".$_GET['ID']."' LIMIT 1", $link);
		while ($row = mysql_fetch_array($result)) { $voto = $row['voto']; $hay_voto = true; }

		// nick existe
		$result = mysql_query("SELECT ID FROM users WHERE ID = '".$_GET['ID']."'", $link);
		while ($row = mysql_fetch_array($result)) { $nick_existe = true; }

		if ($nick_existe == true) {
			if ($hay_voto == true) {
				// update
				mysql_query("UPDATE ".SQL_VOTOS." SET voto = '".$_REQUEST['voto_confianza']."' WHERE estado = 'confianza' AND uservoto_ID = '".$pol['user_ID']."' AND user_ID = '".$_GET['ID']."' LIMIT 1", $link);
			} else {
				// insert
				mysql_query("INSERT INTO ".SQL_VOTOS." (user_ID, uservoto_ID, voto, time, estado) VALUES ('".$_GET['ID']."', '".$pol['user_ID']."', '".$_REQUEST['voto_confianza']."', '".$date."', 'confianza')", $link);
			}

			$result = mysql_query("SELECT SUM(voto) AS voto_confianza FROM ".SQL_VOTOS." WHERE estado = 'confianza' AND user_ID = '".$_GET['ID']."'", $link);
			while ($row = mysql_fetch_array($result)) { 
				mysql_query("UPDATE users SET voto_confianza = '".$row['voto_confianza']."' WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
			}

			$refer_url = 'perfil/'.strtolower($_GET['nick']).'/';
		}

	}
	break;


case 'avatar':
	$img_root = RAIZ.'source/img/a/';
	if ($_GET['b'] == 'upload') {
		$nom_file = $pol['user_ID'].'.jpg';
		$img_name = $_FILES['avatar']['name'];
		$img_type = $_FILES['avatar']['type'];
		$img_size = $_FILES['avatar']['size'];
		if ((strpos($img_type, 'gif') || (strpos($img_type, 'jpeg')) || (strpos($img_type, 'png'))) && ($img_size < 1000000)) {
			move_uploaded_file($_FILES['avatar']['tmp_name'], $img_root . $nom_file);
		} 
		if (file_exists($img_root . $nom_file)) {
			imageCompression($img_root . $nom_file, 120, $img_root . $nom_file, 'jpg');
			imageCompression($img_root . $nom_file, 80, $img_root . $pol['user_ID'].'_80.jpg', 'jpg');
			imageCompression($img_root . $nom_file, 40, $img_root . $pol['user_ID'].'_40.jpg', 'jpg');

			mysql_query("UPDATE users SET avatar_localdir = '".$_FILES['avatar']['name']."', avatar = 'true' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		}
	} elseif ($_GET['b'] == 'borrar') {
		unlink($img_root . $pol['user_ID'].'.jpg');
		unlink($img_root . $pol['user_ID'].'_40.jpg');
		unlink($img_root . $pol['user_ID'].'_80.jpg');
		mysql_query("UPDATE users SET avatar = 'false' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'perfil/'.strtolower($pol['nick']).'/';
	} elseif (($_GET['b'] == 'desc') AND (strlen($_POST['desc']) <= 1300)) {
		$_POST['desc'] = gen_text($_POST['desc'], 'plain');
		mysql_query("UPDATE users SET text = '".$_POST['desc']."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
	}
	$refer_url = 'perfil/'.strtolower($pol['nick']).'/';
	break;


case 'examenes':

	if (($_GET['b'] == 'crear') AND ($_POST['titulo']) AND ($pol['cargos'][35])) {
		$_POST['titulo'] = gen_title($_POST['titulo']);
		mysql_query("INSERT INTO ".SQL."examenes (titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas) VALUES ('" . $_POST['titulo'] . "', 'Editar...', '" . $pol['user_ID'] . "', '" . $date . "', '" . $_POST['cargo_ID'] . "', '5.0', 10)", $link);
		$new_ID = mysql_insert_id($link);
		mysql_query("UPDATE ".SQL."examenes SET cargo_ID = '-" . $new_ID . "' WHERE ID = '" . $new_ID . "' LIMIT 1", $link);
		$refer_url = 'examenes/';

	} elseif (($_GET['b'] == 'nueva-pregunta') AND ($_GET['ID'] != null) AND (($pol['cargos'][35]) OR ($pol['cargos'][34])) AND ($_POST['pregunta']) AND ($_POST['respuesta0'] != null) AND ($_POST['respuesta1'] != null) AND ($_POST['tiempo'])) {
		for ($i=0;$i<10;$i++) { 
			if ($_POST['respuesta' . $i]) { 
				if ($respuestas) { $respuestas .= '|'; }
				$respuestas .= ucfirst(trim(str_replace("|", "", $_POST['respuesta' . $i]))); 
			} 
		}
		$pregunta = ucfirst($_POST['pregunta']);
		mysql_query("INSERT INTO ".SQL."examenes_preg (examen_ID, user_ID, time, pregunta, respuestas, tiempo) VALUES ('" . $_GET['ID'] . "', '" . $pol['user_ID'] . "', '" . $date . "', '" . $pregunta . "', '" . $respuestas . "', " . $_POST['tiempo'] . ")", $link);
		
		//evento_chat('<b>[EXAMEN]</b> Nueva pregunta. <a href="/examenes/editar/' . $_GET['ID'] . '/">Editar examen</a>. (' . crear_link($pol['nick']) . ')', 0, 6);
		$refer_url = 'examenes/editar/' . $_GET['ID'] . '/';

	} elseif (($_GET['b'] == 'eliminar-pregunta') AND ($_GET['ID'] != null) AND (($pol['cargos'][35]) OR ($pol['cargos'][34]))) {
		mysql_query("DELETE FROM ".SQL."examenes_preg WHERE ID = '" . $_GET['ID'] . "' LIMIT 1", $link);
		$refer_url = 'examenes/editar/' . $_GET['re_ID'] . '/';

	} elseif (($_GET['b'] == 'editar-examen') AND ($_GET['ID'] != null) AND ($pol['cargos'][35]) AND ($_POST['titulo']) AND ($_POST['descripcion']) AND ($_POST['nota']) AND ($_POST['num_preguntas'])) {
		$_POST['descripcion'] = gen_text($_POST['descripcion'], 'plain');
		mysql_query("UPDATE ".SQL."examenes SET titulo = '".$_POST['titulo']."', descripcion = '".$_POST['descripcion'] . "', nota = '".$_POST['nota']."', num_preguntas = '".$_POST['num_preguntas']."' WHERE ID = '" . $_GET['ID'] . "' LIMIT 1", $link);
		$refer_url = 'examenes/editar/' . $_GET['ID'] . '/';
	} elseif (($_GET['b'] == 'examinar') AND ($_GET['ID'] != null) AND ($_POST['pregs']) AND (($_POST['tlgs'] + 10) > time())) {

		$result = mysql_query("SELECT cargo_ID, titulo, ID, nota, num_preguntas,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = ".SQL."examenes.ID LIMIT 1) AS num_depreguntas
FROM ".SQL."examenes WHERE ID = '" . $_GET['ID'] . "' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ 
			$cargo_ID = $row['cargo_ID'];
			$nota_aprobado = $row['nota'];
			$examen_titulo = $row['titulo'];
			$examen_ID = $row['ID'];
			$num_depreguntas = $row['num_depreguntas'];
		}

		if (($num_depreguntas >= 5) AND ($examen_ID)) {

			$nota['ok'] = 0;
			$nota['fail'] = 0;
			$nota['total'] = 0;
			$pregs = explode("|", $_POST['pregs']);
			foreach($pregs as $ID) { $nota['total']++; if ($_POST['respuesta' . $ID] == 'a') { $nota['ok']++; } else { $nota['fail']++; } }
		
			$nota['nota'] = number_format(round(($nota['ok'] / $nota['total']) * 10, 1), 1, '.', '');
			if ($nota['nota'] >= $nota_aprobado) { $estado = ", estado = 'ok'"; } else { $estado = ", estado = 'examen'"; }

			$evento_examen = '<b>[EXAMEN]</b> &nbsp; <b style="color:grey;">' . $nota['nota'] . '</b> ' . crear_link($pol['nick']) . ' en el examen <a href="/examenes/' . $examen_ID . '/">' . $examen_titulo . '</a>';

			if ($nota['nota'] >= $nota_aprobado) { evento_chat($evento_examen); }
			//evento_chat($evento_examen, 0, 6);

			mysql_query("UPDATE ".SQL."estudios_users SET time = '" . $date . "', nota = '" . $nota['nota'] . "'" . $estado . " WHERE user_ID = '" . $pol['user_ID'] . "' AND ID_estudio = '" . $cargo_ID . "' LIMIT 1", $link);

			$refer_url = 'examenes/mis-examenes/';
		}
	}

	break;


case 'mapa':
	//pol_mapa (ID, pos_x, pos_y, size_x, size_y, user_ID, link, text, time, pols, color, estado)
	$columnas = 38;
	$filas = 40;

	// pasa a ESTADO
	if ($pol['cargos'][40]) { mysql_query("UPDATE ".SQL."mapa SET estado = 'e', user_ID = '' WHERE link = 'ESTADO'", $link); }

	if (($_GET['b'] == 'compraventa') AND ($_GET['ID'])) {


		$result = mysql_query("SELECT ID, user_ID, pols FROM ".SQL."mapa WHERE ID = '".$_GET['ID']."' AND estado = 'v' AND '".$pol['pols']."' >= pols LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ 
			if ($pol['user_ID'] != $row['user_ID']) {
				pols_transferir($row['pols'], $pol['user_ID'], $row['user_ID'], 'Compra-venta propiedad: '.$row['ID']);
				mysql_query("UPDATE ".SQL."mapa SET estado = 'p', user_ID = '".$pol['user_ID']."' WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
			}
		}

	} elseif (($_GET['b'] == 'cancelar-venta') AND ($_GET['ID'])) {

		mysql_query("UPDATE ".SQL."mapa SET estado = 'p' WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'mapa/propiedades/';

	} elseif (($_GET['b'] == 'vender') AND ($_GET['ID']) AND ($_POST['pols'] > 0)) {

		mysql_query("UPDATE ".SQL."mapa SET pols = '".$_POST['pols']."', estado = 'v' WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'mapa/propiedades/';


	} elseif (($_GET['b'] == 'eliminar') AND ($_GET['ID'])) {

		mysql_query("DELETE FROM ".SQL."mapa WHERE ID = '".$_GET['ID']."' AND (user_ID = '".$pol['user_ID']."' OR (estado = 'e' AND '1' = '".$pol['cargos'][40]."')) LIMIT 1", $link);
		$refer_url = 'mapa/propiedades/';


	} elseif (($_GET['b'] == 'fusionar') AND ($_GET['ID']) AND ($_GET['f'])) {

		$ID = explode("-", $_GET['ID']);

		$result = mysql_query("SELECT *
FROM ".SQL."mapa 
WHERE (user_ID = '".$pol['user_ID']."' OR (estado = 'e' AND '1' = '".$pol['cargos'][40]."')) AND (ID = '".$ID[0]."' OR ID = '".$ID[1]."') LIMIT 2", $link);
		while($row = mysql_fetch_array($result)){ 
			$prop[$row['ID']]['size_x'] = $row['size_x'];
			$prop[$row['ID']]['size_y'] = $row['size_y'];
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
		while($row = mysql_fetch_array($result)){ 
			$superficie = $row['size_x'] * $row['size_y'];
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
			while($row = mysql_fetch_array($result)){
				for ($y=1;$y<=$row['size_y'];$y++) {
					for ($x=1;$x<=$row['size_x'];$x++) {
						$cc[($row['pos_x'] + ($x - 1))][($row['pos_y'] + ($y - 1))] = true;
					}
				}

			}
			

			if (($cc[$pos[0]][$pos[1]] != true) AND ($pol['pols'] >= $pol['config']['pols_solar'])) { // verifica solar libre

				mysql_query("INSERT INTO ".SQL."mapa (pos_x, pos_y, size_x, size_y, user_ID, link, text, time, pols, color, estado, superficie) VALUES ('".$pos[0]."', '".$pos[1]."', '1', '1', '".$pol['user_ID']."', '".$_POST['link']."', '', '".$date."', '".$pol['config']['pols_solar']."', '".$_POST['color']."', 'p', '1')", $link);
				pols_transferir($pol['config']['pols_solar'], $pol['user_ID'], '-1', 'Compra propiedad: '.$_GET['ID']);
			}
		}

		$refer_url = 'mapa/';

	}
	break;


case 'despacho-oval':
	if (
($_GET['b'] == 'config') AND 
($pol['nivel'] >= 98) AND  
($_POST['online_ref'] >= 60) AND
($_POST['pols_inem'] >= 0) AND ($_POST['pols_inem'] <= 500) AND
($_POST['pols_afiliacion'] >= 0) AND ($_POST['pols_afiliacion'] <= 2000) AND
($_POST['pols_empresa'] >= 0) AND
($_POST['pols_cuentas'] >= 0) AND
($_POST['pols_partido'] >= 0) AND
($_POST['pols_solar'] >= 0) AND
($_POST['pols_crearchat'] >= 0) AND
($_POST['factor_propiedad'] <= 10) AND ($_POST['factor_propiedad'] >= 0) AND 
($_POST['pols_mensajetodos'] >= 1000) AND 
($_POST['pols_examen'] >= 0) AND 
($pol['config']['pols_mensajeurgente'] >= 0) AND
($_POST['num_escanos'] <= 30) AND ($_POST['num_escanos'] >= 1) AND 
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
'num_escanos'=>'Numero de esca&ntilde;os y Diputados',
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
		} else {
			mysql_query("UPDATE ".SQL."config SET valor = '".strip_tags($valor)."' WHERE dato = '".$dato."' LIMIT 1", $link);
		}

		if ($pol['config'][$dato] != $valor) { 
			if ($valor == '') { $valor = '<em>null</em>'; }
			if ($dato == 'online_ref') {
				$valor = intval($valor)/60; 
				$pol['config'][$dato] = $pol['config'][$dato]/60;
			}
			evento_chat('<b>[GOBIERNO]</b> Configuraci&oacute;n ('.crear_link($pol['nick']).'): <em>'.$dato_array[$dato].'</em> de <b>'.$pol['config'][$dato].'</b> a <b>'.$valor.'</b> (<a href="/control/despacho-oval/">Despacho Oval</a>)'); 
		}
	
	}
}


	// Salarios
	$result = mysql_query("SELECT ID, salario, nombre FROM ".SQL."estudios", $link);
	while($row = mysql_fetch_array($result)){
		$salario = $_POST['salario_'.$row['ID']];
		if (($salario >= 0) AND ($salario <= 1000)) {
			if ($salario != $row['salario']) { evento_chat('<b>[GOBIERNO]</b> El salario de <img src="/img/cargos/'.$row['ID'].'.gif" /><b>'.$row['nombre'].'</b> se ha cambiado de '.pols($row['salario']).' '.MONEDA.' a '.pols($salario).' '.MONEDA.' ('.crear_link($pol['nick']).', <a href="/control/despacho-oval/">Despacho Oval</a>)');  }
			mysql_query("UPDATE ".SQL."estudios SET salario = '".$salario."' WHERE ID = '".$row['ID']."' LIMIT 1", $link);
		}
	}

	$refer_url = 'control/despacho-oval/';

	// FORO
	} elseif (($_GET['b'] == 'subforo') AND ($pol['nivel'] >= 98)) {

		$subforos = explode('.', $_POST['subforos']);

		foreach ($subforos AS $subforo_ID) {
			
			mysql_query("UPDATE ".SQL."foros SET descripcion = '".$_POST[$subforo_ID.'_descripcion']."', acceso = '".$_POST[$subforo_ID.'_acceso']."', acceso_msg = '".$_POST[$subforo_ID.'_acceso_msg']."', time = '".$_POST[$subforo_ID.'_time']."' WHERE ID = '".$subforo_ID."' LIMIT 1", $link);
		}

		$refer_url = 'control/despacho-oval/foro/';
	} elseif (($_GET['b'] == 'crearsubforo') AND ($pol['nivel'] >= 98)) {

		mysql_query("INSERT INTO ".SQL."foros (url, title, descripcion, acceso, time, estado, acceso_msg) 
VALUES ('".gen_url($_POST['nombre'])."', '".$_POST['nombre']."', '', '1', '10', 'ok', '0')", $link);

		$refer_url = 'control/despacho-oval/foro/';

	} elseif (($_GET['b'] == 'eliminarsubforo') AND ($pol['nivel'] >= 98) AND ($_GET['ID'])) {

		mysql_query("UPDATE ".SQL."foros SET estado = 'eliminado' WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);

		$refer_url = 'control/despacho-oval/foro/';
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
		while($row = mysql_fetch_array($result)){ $cat_url = $row['url']; $cat_ID = $row['ID']; }

		mysql_query("INSERT INTO ".SQL."empresas (url, nombre, user_ID, descripcion, web, cat_ID, time) 
VALUES ('".$url."', '".$nombre."', '".$pol['user_ID']."', 'Editar...', '', '".$cat_ID."', '".$date."')", $link);

		mysql_query("UPDATE ".SQL."cat SET num = num + 1 WHERE ID = '".$cat_ID."' LIMIT 1", $link);

		pols_transferir($pol['config']['pols_empresa'], $pol['user_ID'], '-1', 'Creacion nueva empresa: '.$nombre);

		$return = $cat_url.'/'.$url.'/';

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
		while($row = mysql_fetch_array($result)){ if ($row['pols'] >= $pols) { $pols_max = false; } }

		if (($pols_max) AND ($pols <= $pol['pols'])) {
			mysql_query("INSERT INTO ".SQL."pujas (mercado_ID, user_ID, pols, time) VALUES ('".$ID."', '".$pol['user_ID']."', '".$pols."', '".$date."')", $link);
			evento_chat('<b>[#]</b> puja '.pols($pols).' '.MONEDA.' de <em>'.$pol['nick'].'</em> (<a href="/subasta/">Subasta</a>)'); 
		}

		$refer_url = 'subasta/';
	
	} elseif (($_GET['b'] == 'editarfrase') AND ($pol['config']['pols_fraseedit'] == $pol['user_ID'])) {

		$url = '<a href="http://'.strip_tags($_POST['url']).'">'.ucfirst(strip_tags($_POST['frase'])).'</a>';
		mysql_query("UPDATE ".SQL."config SET valor = '".$url."' WHERE dato = 'pols_frase' LIMIT 1", $link);
		
		$refer_url = 'subasta/editar/';

	} elseif (($_GET['b'] == 'cederfrase') AND ($pol['config']['pols_fraseedit'] == $pol['user_ID']) AND ($pol['nick'] != $_POST['nick'])) {


		$result = mysql_query("SELECT ID, nick, pais FROM users WHERE nick = '".$_POST['nick']."' AND (estado = 'ciudadano' OR estado = 'desarrollador') LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ 
			mysql_query("UPDATE ".SQL."config SET valor = '".$row['ID']."' WHERE dato = 'pols_fraseedit' LIMIT 1", $link);	
			evento_chat('<b>[#] '.crear_link($pol['nick']).' cede</b> "la frase" a <b>'.crear_link($row['nick']).'</b>'); 
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
		
		$result = mysql_query("SELECT ID, nick, pais FROM users WHERE nick = '".$_POST['nick']."'AND (estado = 'ciudadano' OR estado = 'desarrollador') LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ 

			$dato = '';
			foreach(explode(";", $pol['config']['palabras']) as $num => $t) {
				$t = explode(":", $t);
				
				if ($dato) { $dato .= ';'; }

				if (($t[0] == $pol['user_ID']) AND ($_GET['ID'] == $num)) {
					$dato .= $row['ID'].'::'.$row['nick'];
				} else { $dato .= $t[0].':'.$t[1].':'.$t[2]; }
			}
			mysql_query("UPDATE ".SQL."config SET valor = '".$dato."' WHERE dato = 'palabras' LIMIT 1", $link);
			evento_chat('<b>[#] '.crear_link($pol['nick']).' cede</b> la "palabra '.($_GET['ID'] + 1).'" a <b>'.crear_link($row['nick']).'</b>');
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
			$result = mysql_query("SELECT ID, pais FROM users WHERE pais = '".PAIS."' AND ID = '".$pol['user_ID']."' AND pols >= '".$pols."' AND (estado = 'ciudadano' OR estado = 'desarrollador') LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $pais_origen = $row['pais']; $origen = 'ciudadano'; }

		} elseif (ctype_digit($_POST['origen'])) { 
			//Cuenta

			$result = mysql_query("SELECT ID FROM ".SQL."cuentas WHERE ID = '".$_POST['origen']."' AND pols >= '".$pols."' AND (user_ID = '".$pol['user_ID']."' OR (nivel != 0 AND nivel <= '".$pol['nivel']."')) LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $origen = 'cuenta'; }

		}

		//DESTINO
		if (($_POST['destino'] == 'ciudadano') AND ($_POST['ciudadano'])) {
			//Ciudadano

			//nick existe
			$result = mysql_query("SELECT ID, pais FROM users WHERE nick = '".$_POST['ciudadano']."' AND (estado = 'ciudadano' OR estado = 'desarrollador') LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){  $pais_destino = $row['pais']; $destino = 'ciudadano'; $destino_user_ID = $row['ID']; }

		} elseif (($_POST['destino'] == 'cuenta') AND ($_POST['cuenta'])) {
			//cuenta
			
			//cuenta existe
			$result = mysql_query("SELECT ID FROM ".SQL."cuentas WHERE ID = '".$_POST['cuenta']."' LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $destino = 'cuenta'; $destino_cuenta_ID = $row['ID']; }
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
			if ($pols > 0) {
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


case 'referendum':

	if ($_GET['b'] == 'crear') {
		if (($pol['nivel'] >= 95) OR ($pol['cargos']['41'])) { 


			for ($i=0;$i<10;$i++) {
				if ($_POST['respuesta'.$i]) { $respuestas .= trim($_POST['respuesta'.$i]).'|'; }
			}

			$time_expire = date('Y-m-d H:i:s', time() + $_POST['time_expire']);
			$_POST['pregunta'] = strip_tags($_POST['pregunta']);
			$_POST['descripcion'] = gen_text($_POST['descripcion'], 'plain');
			mysql_query("INSERT INTO ".SQL."ref (pregunta, descripcion, respuestas, time, time_expire, user_ID, estado, tipo) VALUES ('".$_POST['pregunta']."', '".$_POST['descripcion']."', '".$respuestas."', '".$date."', '".$time_expire."', '".$pol['user_ID']."', 'ok', '".$_POST['tipo']."')", $link);

			$result = mysql_unbuffered_query("SELECT ID FROM ".SQL."ref WHERE user_ID = '".$pol['user_ID']."' ORDER BY ID DESC LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $ref_ID = $row['ID']; }
			evento_chat('<b>['.strtoupper($_POST['tipo']).']</b> Creado por '.$pol['nick'].': <a href="/referendum/'.$ref_ID.'/"><b>'.$_POST['pregunta'].'</b></a> <span style="color:grey;">('.duracion($_POST['time_expire']).')</span>');
		}
	} elseif (($_GET['b'] == 'votar') AND ($_POST['voto'] != null) AND ($_POST['ref_ID'])) { 

			//2008-11-21 22:00:00
			$result = mysql_unbuffered_query("SELECT fecha_registro FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $fecha_registro = $row['fecha_registro']; }

			$result = mysql_unbuffered_query("SELECT tipo, pregunta, estado FROM ".SQL."ref WHERE ID = '".$_POST['ref_ID']."' LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $tipo = $row['tipo']; $pregunta = $row['pregunta']; $estado = $row['estado']; }

			$result = mysql_unbuffered_query("SELECT ID FROM ".SQL."estudios_users WHERE user_ID = '".$pol['user_ID']."' AND cargo = '1' AND ID_estudio = '6' LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $es_diputado = true; }

			if (($estado == 'ok') AND (($tipo == 'sondeo') OR ($tipo == 'referendum') OR (($tipo == 'parlamento') AND ($es_diputado)))) {
				$result = mysql_unbuffered_query("SELECT ID FROM ".SQL."ref_votos WHERE user_ID = '".$pol['user_ID']."' AND ref_ID = '".$_POST['ref_ID']."' LIMIT 1", $link);
				while($row = mysql_fetch_array($result)){ $ha_votado = true; }
				if ((!$ha_votado) AND (strtotime($fecha_registro) < (time() - 86400))) {
					mysql_query("INSERT INTO ".SQL."ref_votos (user_ID, ref_ID, voto) VALUES ('".$pol['user_ID']."', '".$_POST['ref_ID']."', '".$_POST['voto']."')", $link);
					mysql_query("UPDATE ".SQL."ref SET num = num + 1 WHERE ID = '".$_POST['ref_ID']."' LIMIT 1", $link);

					evento_chat('<b>['.strtoupper($tipo).']</b> Voto de  '.$pol['nick'].' en: <a href="/referendum/'.$_POST['ref_ID'].'/">'.$pregunta.'</a>');
				}
			}

	} elseif (($_GET['b'] == 'eliminar') AND ($_GET['ID'])) { 
		mysql_query("DELETE FROM ".SQL."ref WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		mysql_query("DELETE FROM ".SQL."ref_votos WHERE ref_ID = '".$_GET['ID']."'", $link);
	}

	// actualizar info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."ref WHERE estado = 'ok'", $link);
	while($row = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$row['num']."' WHERE dato = 'info_consultas' LIMIT 1", $link);
	}

	$refer_url = 'referendum/';
	break;


case 'foro':
	// añadir, editar
	if ((strlen($_POST['text']) > 1) AND ($_POST['subforo'])) {
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
				while($row = mysql_fetch_array($result)) { $title = $row['title']; }
				evento_chat('<b>[FORO]</b> Nuevo mensaje de '.$pol['nick'].': <a href="/'.$_POST['return_url'].'">'.$title.'</a>');
			} else {
				//$text = strip_tags($text);
			}
			
			mysql_query("INSERT INTO ".SQL."foros_msg (hilo_ID, user_ID, time, text, cargo) VALUES ('".$_POST['hilo']."', '".$pol['user_ID']."', '".$time."', '".$text."', '".$_POST['encalidad']."')", $link);
		}

		if ($_POST['hilo']) {
			$msg_num = 0;
			$result = mysql_query("SELECT COUNT(*) AS num FROM ".SQL."foros_msg WHERE hilo_ID = '".$_POST['hilo']."' AND estado = 'ok'", $link);
			while($row = mysql_fetch_array($result)) { $msg_num = $row['num']; }
			mysql_query("UPDATE ".SQL."foros_hilos SET num = '".$msg_num."' WHERE ID = '".$_POST['hilo']."' LIMIT 1", $link);
		}


		$refer_url = $_POST['return_url'];
	}


	if (($_GET['b'] == 'borrar') AND ($_GET['ID']) AND ($_GET['c']) AND (($pol['cargo'] == 12) OR ($pol['cargo'] == 13))) {

		if ($_GET['c'] == 'hilo') {
			mysql_query("UPDATE ".SQL."foros_hilos SET estado = 'borrado', time_last = '".$date."' WHERE ID = '".$_GET['ID']."' AND estado = 'ok' LIMIT 1", $link);
		} elseif ($_GET['c'] == 'mensaje') {
			mysql_query("UPDATE ".SQL."foros_msg SET estado = 'borrado', time2 = '".$date."' WHERE ID = '".$_GET['ID']."' AND estado = 'ok' LIMIT 1", $link);
		}
		$refer_url = 'foro/papelera/';

	} elseif (($_GET['b'] == 'restaurar') AND ($_GET['ID']) AND ($_GET['c']) AND (($pol['cargo'] == 12) OR ($pol['cargo'] == 13))) {

		if ($_GET['c'] == 'hilo') {
			mysql_query("UPDATE ".SQL."foros_hilos SET estado = 'ok' WHERE ID = '".$_GET['ID']."' AND estado = 'borrado' LIMIT 1", $link);
		} elseif ($_GET['c'] == 'mensaje') {
			mysql_query("UPDATE ".SQL."foros_msg SET estado = 'ok', time2 = '0000-00-00 00:00:00' WHERE ID = '".$_GET['ID']."' AND estado = 'borrado' LIMIT 1", $link);
		}
		$refer_url = 'foro/papelera/';


	} elseif (($_GET['b'] == 'eliminarhilo') AND ($_GET['ID'])) {
		$result = mysql_unbuffered_query("SELECT ID FROM ".SQL."foros_hilos WHERE ID = '".$_GET['ID']."' AND ('1' = '".$pol['user_ID']."' OR user_ID = '".$pol['user_ID']."') LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ $es_ok = true; }
		if ($es_ok) {
			mysql_query("DELETE FROM ".SQL."foros_hilos WHERE ID = '".$_GET['ID']."' AND ('1' = '".$pol['user_ID']."' OR user_ID = '".$pol['user_ID']."') LIMIT 1", $link);
			mysql_query("DELETE FROM ".SQL."foros_msg WHERE hilo_ID = '".$_GET['ID']."'", $link);
		}
		$refer_url = 'foro/';

	} elseif (($_GET['b'] == 'eliminarreply') AND ($_GET['hilo_ID']) AND ($_GET['ID'])) {

		$result = mysql_unbuffered_query("SELECT ID FROM ".SQL."foros_msg WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' AND time > '".date('Y-m-d H:i:s', time() - 3600)."' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ $es_ok = true; }

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
		while($row = mysql_fetch_array($result)){ 
			if ($pol['user_ID'] == $row['autor']) {
				$es_policiaexpulsador = true;
			}
			$kickeado_id = $row['user_ID'];
			$kick_id = $row['ID']; 
		}
	
		if (($es_policiaexpulsador) OR ($pol['cargos'][13]) OR ($pol['cargos'][9])) {
			mysql_query("UPDATE ".SQL."ban SET estado = 'cancelado' WHERE estado = 'activo' AND ID = '".$_GET['ID']."' LIMIT 1", $link); 
			if (mysql_affected_rows()==1) {
				$result = mysql_query("SELECT nick FROM users WHERE ID = '".$kickeado_id."' LIMIT 1", $link);
				while($row = mysql_fetch_array($result)){ $kickeado_nick = $row['nick'];}
				evento_log(14, $kick_id, $kickeado_id); // Kick cancelado
				evento_chat('<span style="color:red;"><img src="/img/kick.gif" alt="Kick" border="0" /> <b>[KICK]</b> El kick a <b>'.$kickeado_nick.'</b> ha sido cancelado por <img src="/img/cargos/'.$pol['cargo'].'.gif" border="0" /> <b>'.$pol['nick'].'</b>.</span>');
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
			while($row = mysql_fetch_array($result)){ $user_kicked = true; }
			$el_userid = -1;
		} else {
			$result = mysql_query("SELECT ID, nick, IP, cargo, pais FROM users WHERE nick = '".$_POST['nick']."' LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $kick_cargo = $row['cargo']; $kick_user_ID = $row['ID']; $kick_nick = $row['nick']; $kick_IP = '\''.$row['IP'].'\''; $kick_pais = $row['pais']; }
			$result = mysql_query("SELECT ID FROM ".SQL."ban WHERE user_ID = '".$kick_user_ID."' AND estado = 'activo' LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $user_kicked = true; }
			$el_userid = $pol['user_ID'];
		}


		$_POST['razon'] = ereg_replace("(^|\n| )[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", strip_tags($_POST['razon']));
		$_POST['motivo'] = ereg_replace("(^|\n| )[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", strip_tags($_POST['motivo']));

		if (
(($pol['cargos'][12]) OR ($pol['cargos'][13]) OR ($pol['cargos'][22])) AND 
($kick_user_ID >= 0) AND 
($user_kicked != true) AND 
((($kick_cargo != 7) AND ($kick_cargo != 13)) OR ($kick_pais != PAIS)) AND
($_POST['razon']) AND
($_POST['expire'] <= 777600)
) {
			$_POST['razon'] = ucfirst(strip_tags($_POST['razon']));
			$expire = date('Y-m-d H:i:s', time() + $_POST['expire']);
			mysql_query("INSERT INTO ".SQL."ban (user_ID, autor, expire, razon, estado, tiempo, IP, cargo, motivo) VALUES ('".$el_userid."', ".$pol['user_ID'].", '".$expire."', '".$_POST['razon']."', 'activo', '".$_POST['expire']."', ".$kick_IP.", '".$pol['cargo']."', '".$_POST['motivo']."')", $link);

			$kick_msg = '<span style="color:red;"><img src="/img/kick.gif" alt="Kick" border="0" /> <b>[KICK] '.$kick_nick.'</b> ha sido kickeado por <img src="/img/cargos/'.$pol['cargo'].'.gif" border="0" /> <b>'.$pol['nick'].'</b>, durante <b>'.duracion($_POST['expire']).'</b>. Razon: <em>'.$_POST['razon'].'</em> (<a href="/control/kick/">Ver kicks</a>)</span>';
			evento_chat($kick_msg);
			if ((isset($_POST['chat_ID'])) AND ($_POST['chat_ID'] != 1) AND ($_POST['chat_ID'] != 2)) { evento_chat($kick_msg, 0, $_POST['chat_ID']); }
		}
		$refer_url = 'control/kick/';
	}
	break;



case 'mensaje-leido':
	if (($_GET['ID'])) {
		mysql_query("UPDATE ".SQL_MENSAJES." SET leido = '1' WHERE ID = '".$_GET['ID']."' AND recibe_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'msg/';
	}
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
			$result = mysql_query("SELECT ID, pais FROM users WHERE nick = '".$_POST['nick']."' LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ 
				mysql_query("INSERT INTO ".SQL_MENSAJES." (envia_ID, recibe_ID, time, text, leido, cargo) VALUES ('".$pol['user_ID']."', '".$row['ID']."', '".$date."', '".$text."', '0', '".$_POST['calidad']."')", $link);
				
				// MENSAJE URGENTE
				if (($_POST['urgente'] == '1') AND ($pol['pols'] >= $pol['config']['pols_mensajeurgente'])) { 
					$asunto = '[VirtualPol] Tienes un mensaje urgente de '.$pol['nick'];
					$mensaje = 'Hola Ciudadano,<br /><br />Has recibido un mensaje urgente enviado por el Ciudadano: '.$pol['nick'].'.<br /><br />Para leerlo has de entrar aquí: <a href="http://'.HOST.'/msg/">http://'.HOST.'/msg/</a><br /><br />Mensaje de '.$pol['nick'].':<hr />'.$text.'<hr /><br /><br />Nota: Si este aviso te ha resultado molesto puedes defender tu derecho apoyandote en la Justicia de '.PAIS.'.<br /><br /><br />VirtualPol<br />http://'.HOST;
					pols_transferir($pol['config']['pols_mensajeurgente'], $pol['user_ID'], '-1', 'Envio mensaje urgente');
					enviar_email($row['ID'], $asunto, $mensaje); 
				}

				evento_chat('<b>Nuevo mensaje privado</b> (<a href="http://'.strtolower($row['pais']).'.virtualpol.com/msg/"><b>Leer!</b></a>)', $row['ID'], -1, false, 'p'); 
			}
		} elseif (($_POST['para'] == 'cargo') AND ($_POST['cargo_ID'])) {



			$result = mysql_query("SELECT nombre FROM ".SQL."estudios WHERE ID = '".$_POST['cargo_ID']."' LIMIT 1", $link);
			while($row = mysql_fetch_array($result)){ $cargo_nombre = $row['nombre']; }



			if ($_POST['cargo_ID'] == '21') {
				$result = mysql_query("SELECT ID AS user_ID FROM users WHERE cargo = '21' OR estado = 'desarrollador'", $link);
			} else {
				$result = mysql_query("SELECT user_ID FROM ".SQL."estudios_users WHERE cargo = '1'  AND estado = 'ok' AND ID_estudio = '".$_POST['cargo_ID']."' LIMIT 50", $link);
			}
			while($row = mysql_fetch_array($result)){ 
				if ($row['user_ID'] != $pol['user_ID']) {
					mysql_query("INSERT INTO ".SQL_MENSAJES." (envia_ID, recibe_ID, time, text, leido, cargo) VALUES ('".$pol['user_ID']."', '".$row['user_ID']."', '".$date."', '<b>Mensaje multiple: ".$cargo_nombre."</b><br />".$text."', '0', '".$_POST['calidad']."')", $link);
				}
			}
		} elseif (($_POST['para'] == 'todos') AND ($pol['pols'] >= $pol['config']['pols_mensajetodos'])) {
			// MENSAJE GLOBAL
			$text = '<b>Mensaje Global:</b> ('.pols($pol['config']['pols_mensajetodos']).' '.MONEDA.')<hr />'.$text;
			pols_transferir($pol['config']['pols_mensajetodos'], $pol['user_ID'], '-1', 'Mensaje Global');
			$result = mysql_query("SELECT ID FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."'", $link);
			while($row = mysql_fetch_array($result)){ 
				mysql_query("INSERT INTO ".SQL_MENSAJES." (envia_ID, recibe_ID, time, text, leido, cargo) VALUES ('".$pol['user_ID']."', '".$row['ID']."', '".$date."', '".$text."', '0', '".$_POST['calidad']."')", $link);
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
		while($row = mysql_fetch_array($result)){ $fecha_registro = $row['fecha_registro']; }

		//ha votado?
		$result = mysql_query("SELECT ID FROM ".SQL."elecciones WHERE user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ $ha_votado = $row['ID']; }
		
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
						while($row = mysql_fetch_array($result)){ 
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
					while($row = mysql_fetch_array($result)){
						mysql_query("INSERT INTO ".SQL."elecciones (ID_partido, user_ID, nav, IP, time) VALUES ('".$ID_partido."', '".$pol['user_ID']."', '".$nav."', '".$IP."', '".$time."')", $link);
						mysql_query("UPDATE users SET num_elec = num_elec + 1 WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
						mysql_query("UPDATE ".SQL."elec SET num_votos = num_votos + 1 ORDER BY time DESC LIMIT 1", $link);
					}
				}
			}

			$result = mysql_query("SELECT num_votantes FROM ".SQL."elec ORDER BY time DESC LIMIT 1", $link);
			while($row = mysql_fetch_array($result)) { $num_votantes = $row['num_votantes']; }

			$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."elecciones", $link);
			while($row = mysql_fetch_array($result)) { $num_votos = $row['num']; }

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
		while($row = mysql_fetch_array($result)){
			$siglas = $row['siglas'];
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

		cargo_del($_GET['ID'], $pol['user_ID']);

		$result2 = mysql_query("SELECT nombre FROM ".SQL."estudios WHERE ID = '".$_GET['ID']."' LIMIT 1", $link);
		while($row2 = mysql_fetch_array($result2)){ 
			evento_chat('<b>[CARGO] '.crear_link($pol['nick']).' dimite</b> del cargo <img src="/img/cargos/'.$_GET['ID'].'.gif" />'.$row2['nombre']);
		}
		$refer_url = 'perfil/'.strtolower($pol['nick']).'/';



	} elseif (($_GET['b'] == 'cederOLD') AND ($_POST['user_ID'])) {

		$result = mysql_query("SELECT user_ID FROM ".SQL."estudios_users WHERE user_ID = '".$_POST['user_ID']."' AND estado = 'ok' AND cargo = '0' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){
			$diputado_aprobado = true;
		}

		if (($pol['cargos'][6]) AND ($diputado_aprobado)) { //es diputado de momento
			cargo_add('6', $_POST['user_ID']); //hace diputado
			cargo_del('6', $pol['user_ID']); //quita diputado
		}
		
		$refer_url = 'perfil/'.strtolower($pol['nick']).'/';

	} elseif (($b) AND ($cargo_ID)) {
		$result = mysql_query("SELECT ID, asigna, nombre FROM ".SQL."estudios WHERE ID = '".$cargo_ID."' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){

			if (($pol['nivel'] == 120) OR 
(($pol['cargos'][$row['asigna']]) AND ($row['ID'] != 7)) OR 
(($row['ID'] != 19) AND ($row['asigna'] == 7) AND ($pol['cargos'][19]) AND ($row['ID'] != 7))) { 


				$result2 = mysql_query("SELECT nick, online, fecha_registro FROM users WHERE ID = '".$_POST['user_ID']."' AND pais = '".PAIS."' LIMIT 1", $link);
				while($row2 = mysql_fetch_array($result2)){ $nick_asignado = $row2['nick']; $asignado['fecha_registro'] = $row2['fecha_registro']; $asignado['online'] = $row2['online']; }

				if ($nick_asignado) {
					if ($b == 'add') {
						if (($cargo_ID != 21) OR (($cargo_ID == 21) AND (strtotime($asignado['fecha_registro']) <= (time()-8640000)) AND ($asignado['online'] >= 864000))) {
							cargo_add($cargo_ID, $_POST['user_ID']);
							evento_chat('<b>[CARGO]</b> El cargo de '.'<img src="/img/cargos/'.$cargo_ID.'.gif" />'.$row['nombre'].' ha sido asignado a '.crear_link($nick_asignado).' por '.crear_link($pol['nick']));
						}
					}
					elseif ($b == 'del') { 
						cargo_del($cargo_ID, $_POST['user_ID']); 
						evento_chat('<b>[CARGO] '.crear_link($pol['nick']).' quita</b> el cargo <img src="/img/cargos/'.$cargo_ID.'.gif" />'.$row['nombre'].' a '. crear_link($nick_asignado));
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
		while($row = mysql_fetch_array($result)){
			mysql_query("DELETE FROM ".SQL."partidos WHERE ID = '".$row['ID']."' LIMIT 1", $link);
			mysql_query("DELETE FROM ".SQL."partidos_listas WHERE ID_partido = '".$row['ID']."' LIMIT 1", $link);
			evento_log(5, $row['ID']);
		}
	}

	// actualizar info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."partidos WHERE estado = 'ok'", $link);
	while($row = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$row['num']."' WHERE dato = 'info_partidos' LIMIT 1", $link);
	}

	$refer_url = 'partidos/';
	break;

case 'eliminar-documento':
	
	$result = mysql_query("SELECT ID FROM ".SQL."docs WHERE url = '".$_GET['url']."' AND nivel <= '".$pol['nivel']."' LIMIT 1", $link);
	echo mysql_error($link);
	while($row = mysql_fetch_array($result)){ 
		mysql_query("UPDATE ".SQL."docs SET estado = 'del' WHERE ID = '".$row['ID']."' LIMIT 1", $link);
		evento_log(8, $row['ID']);
		$refer_url = 'doc/';
	}

	// actualiza info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."docs WHERE estado = 'ok'", $link);
	while($row = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$row['num']."' WHERE dato = 'info_documentos' LIMIT 1", $link);
	}
	break;


case 'editar-documento':
	if (($_POST['titulo']) AND ($_POST['text']) AND ($_POST['nivel'] <= 120) AND ($_POST['cat'])) {
		$text = gen_text($_POST['text']);
		$text = str_replace("../../", "/doc/", $text);
		$_POST['titulo'] = strip_tags($_POST['titulo']);


		mysql_query("UPDATE ".SQL."docs SET user_ID = '".$pol['user_ID']."', cat_ID = '".$_POST['cat']."', nivel = '".$_POST['nivel']."', text = '".$text."', title = '".$_POST['titulo']."', time_last = '".$date."' WHERE url = '".$_POST['url']."' AND nivel <= '".$pol['nivel']."' LIMIT 1", $link);
		evento_log(7, $_GET['ID']);
	}
	$refer_url = 'doc/'.$url.'/';
	break;

case 'crear-documento':
	if ((strlen($_POST['title']) > 3) AND (strlen($_POST['title']) < 200) AND ($_POST['nivel'] <= 120) AND ($_POST['cat'])) {


		$url = gen_url($_POST['title']);
		$text = gen_text($_POST['text']);

		mysql_query("INSERT INTO ".SQL."docs 
(user_ID, url, title, text, time, time_last, nivel, estado, cat_ID) 
VALUES ('".$pol['user_ID']."', '".$url."', '".$_POST['title']."', '".$text."', '".$date."', '".$date."', ".$_POST['nivel'].", 'ok', '".$_POST['cat']."')", $link);
		evento_log(6, $url);
	}

	// actualizacion de info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."docs WHERE estado = 'ok'", $link);
	while($row = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$row['num']."' WHERE dato = 'info_documentos' LIMIT 1", $link);
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
	while($row = mysql_fetch_array($result)){ $ya_es_presidente = true; }

	if (($pol['config']['elecciones_estado'] != 'elecciones') AND (strlen($_POST['siglas']) <= 12) AND (strlen($_POST['siglas']) >= 2) AND ($pol['pols'] >= $pol['config']['pols_partido']) AND ($_POST['nombre']) AND ($ya_es_presidente == false)) {

		$_POST['descripcion'] = gen_text($_POST['descripcion']);

		pols_transferir($pol['config']['pols_partido'], $pol['user_ID'], '-1', 'Creacion nuevo partido: '.$_POST['siglas']);

		mysql_query("INSERT INTO ".SQL."partidos 
(ID_presidente, fecha_creacion, siglas, nombre, descripcion, estado) 
VALUES ('".$pol['user_ID']."', '".$date."', '".strtoupper($_POST['siglas'])."', '".$_POST['nombre']."', '".$_POST['descripcion']."', 'ok')
", $link);


		$result = mysql_query("SELECT ID FROM ".SQL."partidos WHERE siglas = '".$_POST['siglas']."' LIMIT 1", $link);
		while($row = mysql_fetch_array($result)){ $partido_ID = $row['ID']; }
		evento_log(3, $partido_ID);
	}

	// actualizar info en theme
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."partidos WHERE estado = 'ok'", $link);
	while($row = mysql_fetch_array($result)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$row['num']."' WHERE dato = 'info_partidos' LIMIT 1", $link);
	}

	$refer_url = 'partidos/';
	break;
}
}


if ($_GET['a'] == 'logout') {
	if (($_SESSION) AND (substr($_SESSION['pol']['nick'], 0, 1) != '-')) { 
		unset($_SESSION);
		session_unset(); session_destroy();
	}

	header('Location: '.REGISTRAR.'login.php?a=logout');
	exit;
}

if ($link) { mysql_close($link); }
header('Location: http://'.HOST.'/'.$refer_url);
?>
