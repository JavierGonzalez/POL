<?php 
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

if (isset($_GET['http_host'])) { $_SERVER['HTTP_HOST'] = $_GET['http_host']; }

include('inc-login.php');
include('inc-functions-accion.php');

// load config full
$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }


if (
(nucleo_acceso('ciudadanos'))
OR (($pol['estado'] == 'kickeado') AND (in_array($_GET['a'], array('rechazar-ciudadania', 'votacion'))))
OR (($pol['estado'] == 'extranjero') AND (in_array($_GET['a'], array('voto', 'mercado', 'foro', 'votacion', 'api'))))
) {


//###################################################################
switch ($_GET['a']) { //############## BIG ACTION SWITCH ############
//###################################################################





	case 'experimento-1':
		

		$result = sql("SELECT item_ID, emisor_ID FROM votos WHERE tipo = 'confianza'");
		while($r = r($result)) {
			$users[$r['item_ID']] = array('name'=>$r['item_ID'], 'group'=>1);
			$users[$r['emisor_ID']] = array('name'=>$r['emisor_ID'], 'group'=>1);
			
			$links[] = array('source'=>$r['emisor_ID'], 'target'=>$r['receptor_ID'], 'value'=>1);
		}

		$pp['nodes'] = $users;
		$pp['links'] = $links;
		echo json_encode($pp);



		break;


/*
 * Vieja API para conectar con Facebook se comenta por si en el futuro fuese necesario recuperarla
 */
/*case 'api':
	$refer_url = 'api/';
	if (($_GET['b'] == 'crear') AND (is_numeric($_POST['api_ID']))) {
		$result = sql("SELECT * FROM api WHERE api_ID = '".$_POST['api_ID']."' LIMIT 1");
		while($r = r($result)) {
			if (nucleo_acceso($r['acceso_borrador'])) {
				if (is_numeric($_POST['post_ID'])) {
					sql("UPDATE api_posts SET time = '".$date."', time_cron = '".$_POST['time_cron']."', message = '".$_POST['message']."', picture = '".$_POST['picture']."', link = '".$_POST['link']."', source = '".$_POST['source']."' WHERE post_ID = '".$_POST['post_ID']."' AND estado != 'publicado' LIMIT 1");
				} else {
					sql("INSERT INTO api_posts (pais, api_ID, estado, pendiente_user_ID, time, time_cron, message, picture, link, source) 
VALUES ('".PAIS."', '".$r['api_ID']."', 'pendiente', '".$pol['user_ID']."', '".$date."', '".trim($_POST['time_cron'])."', '".strip_tags(trim($_POST['message']))."', '".strip_tags(trim($_POST['picture']))."', '".strip_tags(trim($_POST['link']))."', '".strip_tags(trim($_POST['source']))."')");
				}
				$refer_url = 'api/'.$r['api_ID'];
			}
		}
	} elseif (($_GET['b'] == 'publicar') AND (is_numeric($_GET['ID']))) {
		api_facebook('publicar', $_GET['ID']);
	} elseif (($_GET['b'] == 'borrar') AND (is_numeric($_GET['ID']))) {
		api_facebook('borrar', $_GET['ID']);
	} elseif (($_GET['b'] == 'borrar_borrador') AND (is_numeric($_GET['ID']))) {
		$result = sql("SELECT api_ID, (SELECT acceso_escribir FROM api WHERE api_ID = api_posts.api_ID LIMIT 1) AS acceso_escribir FROM api_posts WHERE post_ID = '".$_GET['ID']."' LIMIT 1");
		while($r = r($result)) {
			if (nucleo_acceso($r['acceso_escribir'])) {
				sql("DELETE FROM api_posts WHERE post_ID = '".$_GET['ID']."' AND estado != 'publicado' LIMIT 1");
			}
			$refer_url = 'api/'.$r['api_ID'];
		}
	}

	if ($_GET['b'] != 'crear') {
		$result = sql("SELECT api_ID FROM api_posts WHERE post_ID = '".$_GET['ID']."' LIMIT 1");
		while($r = r($result)) { $refer_url = 'api/'.$r['api_ID']; }
	}

	break;
*/ 


case 'test': // Test de desarrollo - eliminar pronto
	echo PAIS.' - '.$pol['pais'].'<hr />';
	echo $pol['nick'].' - '.$pol['user_ID'].' - '.$pol['estado'].' - '.$pol['nivel'].' - '.$pol['fecha_registro'].'<hr />';
	echo $_SESSION['pol']['nick'].' - '.$_SESSION['pol']['user_ID'].' - '.$_SESSION['pol']['estado'].' - '.$_SESSION['pol']['nivel'].'<hr />';
	echo (nucleo_acceso('ciudadanos')?'true':'false').'<hr />';
	echo $_SERVER['HTTP_HOST'].' - '.HOST.'<hr />';
	echo IMG.'<hr />';
	exit;
	break;
	
case 'api':
	if (($pol['user_ID']) AND ($_GET['b'] == 'gen_pass')) {
		mysql_query("UPDATE users SET api_pass = '".substr(md5(mt_rand(1000000000,9999999999)), 0, 12)."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);
		$refer_url = 'perfil/editar/';
	}
	break;

case 'users_con':
	sql("DELETE FROM users_con WHERE user_ID = '".$pol['user_ID']."' ORDER BY time DESC LIMIT 1");
	users_con($pol['user_ID'], $_REQUEST['extra'], 'session'); mysql_close(); exit;
	break;

case 'socios':
	$refer_url = 'socios';
	$es_socio = false;
	$result = sql("SELECT ID, estado, socio_ID FROM socios WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
	while($r = r($result)) { $es_socio = true; $socio_estado = $r['estado']; $socio_numero = PAIS.$r['socio_ID']; }

	if (($_GET['b'] == 'inscribirse') AND (nucleo_acceso('ciudadanos')) AND ($es_socio == false) AND ($pol['config']['socios_estado'] == 'true') AND ($_POST['nombre']) AND ($_POST['NIF']) AND ($_POST['localidad']) AND ($_POST['cp']) AND ($_POST['contacto_email'])) {
		$last_socio_ID = 0;
		
		$last_socio_ID = sql("SELECT socio_ID FROM socios WHERE pais = '".PAIS."' ORDER BY socio_ID DESC LIMIT 1", true);
		
		sql("INSERT INTO socios (time, time_last, pais, socio_ID, user_ID, nombre, NIF, pais_politico, localidad, cp, direccion, contacto_email, contacto_telefono) 
VALUES ('".$date."', '".$date."', '".PAIS."', '".($last_socio_ID==0?10000:$last_socio_ID+1)."', '".$pol['user_ID']."', '".ucfirst(trim($_POST['nombre']))."', '".str_replace(' ', '', str_replace('-', '', strtoupper(trim($_POST['NIF']))))."', '".$_POST['pais_politico']."', '".ucfirst($_POST['localidad'])."', '".trim($_POST['cp'])."', '".ucfirst(trim($_POST['direccion']))."', '".strtolower(trim($_POST['contacto_email']))."', '".str_replace(' ', '', trim($_POST['contacto_telefono']))."')");

	} elseif (($_GET['b'] == 'cancelar') AND ($es_socio)) {
		sql("DELETE FROM socios WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		sql("UPDATE users SET socio = 'false' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		if ($socio_estado == 'socio') {
			cargo_del($pol['config']['socios_ID'], $pol['user_ID']);
			sql("UPDATE users SET socio = 'false' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		}

	} elseif (($_GET['b'] == 'configurar') AND (nucleo_acceso($vp['acceso']['control_socios']))) {
		foreach (array('socios_estado', 'socios_ID', 'socios_descripcion', 'socios_responsable') AS $dato) {
			sql("UPDATE config SET valor = '".nl2br(strip_tags(trim($_POST[$dato])))."' WHERE pais = '".PAIS."' AND dato = '".$dato."' LIMIT 1");
		}
		$refer_url = 'socios/configurar';

	} elseif (($_GET['b'] == 'aprobar') AND (nucleo_acceso($vp['acceso']['control_socios'])) AND (is_numeric($_GET['ID']))) {
		$result = sql("SELECT ID, user_ID FROM socios WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND estado != 'socio' LIMIT 1");
		while($r = r($result)) {
			sql("UPDATE socios SET estado = 'socio', validador_ID = '".$pol['user_ID']."' WHERE ID = '".$_GET['ID']."' LIMIT 1");
			sql("UPDATE users SET socio = 'true' WHERE ID = '".$r['user_ID']."' LIMIT 1");
			cargo_add($pol['config']['socios_ID'], $r['user_ID']);
		}
		$refer_url = 'socios/inscritos';
	
	} elseif (($_GET['b'] == 'rescindir') AND (nucleo_acceso($vp['acceso']['control_socios'])) AND (is_numeric($_GET['ID']))) {
		$refer_url = 'socios/asociados';
		$result = sql("SELECT ID, user_ID, estado FROM socios WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' LIMIT 1");
		while($r = r($result)) {
			sql("UPDATE users SET socio = 'false' WHERE ID = '".$r['user_ID']."' LIMIT 1");
			if ($r['estado'] == 'socio') {
				sql("UPDATE socios SET estado = 'rescindido', validador_ID = '".$pol['user_ID']."' WHERE ID = '".$_GET['ID']."' LIMIT 1");
				cargo_del($pol['config']['socios_ID'], $r['user_ID']);
			} else { 
				sql("DELETE FROM socios WHERE ID = '".$_GET['ID']."' LIMIT 1");
				$refer_url = 'socios/inscritos'; 
			}
		}
	}
	break;


case 'grupos':
	if (($_GET['b'] == 'crear') AND (nucleo_acceso($vp['acceso']['control_grupos']))) {
		sql("INSERT INTO grupos (pais, nombre) VALUES ('".PAIS."', '".ucfirst($_POST['nombre'])."')");
	} elseif (($_GET['b'] == 'eliminar') AND (nucleo_acceso($vp['acceso']['control_grupos'])) AND ($_GET['grupo_ID'])) {
		sql("DELETE FROM grupos WHERE grupo_ID = '".$_GET['grupo_ID']."' AND pais = '".PAIS."' LIMIT 1");
	} elseif ($_GET['b'] == 'afiliarse') {
		$grupos_array = array();
		$result = sql("SELECT * FROM grupos WHERE pais = '".PAIS."'");
		while($r = r($result)) {

			if ($_POST['grupo_'.$r['grupo_ID']] == 'true') {
				$grupos_array[] = $r['grupo_ID'];
			
				if (!nucleo_acceso('grupos', $r['grupo_ID'])) {
					sql("UPDATE grupos SET num = num + 1 WHERE grupo_ID = '".$r['grupo_ID']."' LIMIT 1");
				}
			} else {
				if (nucleo_acceso('grupos', $r['grupo_ID'])) {
					sql("UPDATE grupos SET num = num - 1 WHERE grupo_ID = '".$r['grupo_ID']."' LIMIT 1");
				}
			}
		}
		sql("UPDATE users SET grupos = '".implode(' ', $grupos_array)."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	}
	$refer_url = 'grupos';
	break;


case 'perfil':
	if ($_GET['b'] == 'datos') {		
		foreach ($datos_perfil AS $id => $dato) { $datos_array[] = $_POST[$dato]; }
		sql("UPDATE users SET datos = '".implode('][', $datos_array)."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	} elseif ($_GET['b'] == 'nombre') {
		sql("UPDATE users SET nombre = '".strip_tags($_POST['nombre'])."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	}
	$refer_url = 'perfil/editar';
	break;

case 'aceptar-condiciones':
	$result = sql("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND fecha_legal = '0000-00-00 00:00:00' LIMIT 1");
	while($r = r($result)) {
		sql("UPDATE users SET fecha_legal = '".$date."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		evento_chat('<b>[#] '.crear_link($pol['nick']).'</b> ha aceptado las <a href="'.SSL_URL.'/TOS">Condiciones de Uso de VirtualPol</a>.');
	}
	$refer_url = '';
	break;


case 'donacion':
	sql("UPDATE users SET donacion = ".($_POST['donacion']>=5?"'".$_POST['donacion']."'":"NULL")." WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	$refer_url = 'perfil/'.$pol['nick'];
	break;


case 'SC':
	if (($_GET['b'] == 'nota') AND (nucleo_acceso('supervisores_censo')) AND ($_GET['ID'])) {
		sql("UPDATE users SET nota_SC = '".strip_tags($_POST['nota_SC'])."' WHERE ID = '".$_GET['ID']."' LIMIT 1");
		$refer_url = 'sc/filtro/user_ID/'.$_GET['ID'];
	}
	break;


case 'bloqueos':
	if (nucleo_acceso('supervisores_censo')) {
		foreach (array('backlist_emails', 'backlist_IP', 'backlist_nicks') AS $tipo) {
			$data = array();
			foreach (explode("\n", $_POST[$tipo]) AS $linea) {
				$linea = strtolower(trim(strip_tags($linea)));
				if (strlen(explodear(' ', $linea, 0)) >= 5) { $data[] = $linea; }
			}
			if (count($data) > 0) { sort($data); sql("UPDATE config SET valor = '".implode("\n", $data)."' WHERE dato = '".$tipo."' LIMIT 1"); }
		}
		$refer_url = 'control/supervisor-censo/bloqueos';
	}
	break;


case 'exencion_impuestos':
	if ($pol['nivel'] >= 98) {
		$result = sql("SELECT ID, exenta_impuestos FROM cuentas WHERE pais = '".PAIS."' AND nivel = '0'");
		while($r = r($result)) {
			if (($_POST['exenta_impuestos'.$r['ID']] == '1') AND ($r['exenta_impuestos'] == '0')) {
				sql("UPDATE cuentas SET exenta_impuestos = 1 WHERE pais = '".PAIS."' AND ID = '".$r['ID']."'");
			}
			elseif  (!isset($_POST['exenta_impuestos'.$r['ID']]) AND ($r['exenta_impuestos'] == '1')) {
				sql("UPDATE cuentas SET exenta_impuestos = 0 WHERE pais = '".PAIS."' AND ID = '".$r['ID']."'");
			}
		}
		$refer_url = 'pols/cuentas';
	} 
	break;



case 'chat':

	if (($_GET['b'] == 'solicitar') AND ($_POST['nombre'])) {
		$nombre = $_POST['nombre'];
		$url = gen_url($nombre);

		sql("INSERT INTO chats (pais, url, titulo, user_ID, admin, fecha_creacion, fecha_last, dias_expira) 
VALUES ('".PAIS."', '".$url."', '".ucfirst($nombre)."', '".$pol['user_ID']."', '".$pol['nick']."', '".$date."', '".$date."', '".$pol['config']['chat_diasexpira']."')");
		if (ECONOMIA) {
			$result = sql("SELECT chat_ID FROM chats WHERE url = '".$url."' AND user_ID = '".$pol['user_ID']."' AND pais = '".$_POST['pais']."' LIMIT 1");
			while($r = r($result)) {
				pols_transferir($pol['config']['pols_crearchat'], $pol['user_ID'], '-1', 'Solicitud chat: '.$nombre);
			}
		}
		$refer_url = 'chats';
	} elseif (($_GET['b'] == 'cambiarfundador') AND ($_POST['admin']) AND ($_POST['chat_ID'])) {
		
		$result = sql("SELECT admin, user_ID, url FROM chats WHERE chat_ID = '".$_POST['chat_ID']."' AND estado = 'activo' LIMIT 1");
		while($r = r($result)) {
			if ((nucleo_acceso('privado', $r['admin'])) OR ($r['user_ID'] == $pol['user_ID']) OR (nucleo_acceso($vp['acceso']['control_gobierno']))) {
				sql("UPDATE chats SET admin = '".strtolower(strip_tags($_POST['admin']))."' WHERE chat_ID = '".$_POST['chat_ID']."' LIMIT 1");
			}
			$refer_url = 'chats/'.$r['url'].'/opciones';
		}
	} elseif (($_GET['b'] == 'editar') AND ($_POST['chat_ID'])) {

		$result = sql("SELECT admin, user_ID, url FROM chats WHERE chat_ID = '".$_POST['chat_ID']."' AND estado = 'activo' LIMIT 1");
		while($r = r($result)) {
			
			if ((nucleo_acceso('privado', $r['admin'])) OR (nucleo_acceso($vp['acceso']['control_gobierno']))) {
				if ($_POST['acceso_cfg_leer']) { 
					$_POST['acceso_cfg_leer'] = trim(ereg_replace(' +', ' ', strtolower($_POST['acceso_cfg_leer']))); 
				}
				if ($_POST['acceso_cfg_escribir']) { 
					$_POST['acceso_cfg_escribir'] = trim(ereg_replace(' +', ' ', strtolower($_POST['acceso_cfg_escribir'])));
				}

				if ($_POST['acceso_cfg_escribir_ex']) { 
					$_POST['acceso_cfg_escribir_ex'] = trim(ereg_replace(' +', ' ', strtolower($_POST['acceso_cfg_escribir_ex'])));
				}
				sql("UPDATE chats 
SET acceso_leer = '".$_POST['acceso_leer']."', 
acceso_escribir = '".$_POST['acceso_escribir']."', 
acceso_escribir_ex = '".$_POST['acceso_escribir_ex']."', 
acceso_cfg_leer = '".$_POST['acceso_cfg_leer']."', 
acceso_cfg_escribir = '".$_POST['acceso_cfg_escribir']."',
acceso_cfg_escribir_ex = '".$_POST['acceso_cfg_escribir_ex']."'
WHERE chat_ID = '".$_POST['chat_ID']."' AND estado = 'activo' AND pais = '".PAIS."' LIMIT 1");
			}
		}
		$refer_url = 'chats/'.$_POST['chat_nom'].'/opciones';

	} elseif (($_GET['b'] == 'activar') AND ($_GET['chat_ID']) AND (nucleo_acceso($vp['acceso']['control_gobierno']))) {
		sql("UPDATE chats SET estado = 'activo' WHERE chat_ID = '".$_GET['chat_ID']."' AND estado != 'activo' AND pais = '".PAIS."' LIMIT 1");
		$refer_url = 'chats';
	} elseif (($_GET['b'] == 'eliminar') AND ($_GET['chat_ID'])) {
		sql("DELETE FROM chats WHERE chat_ID = '".$_GET['chat_ID']."' AND pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' OR 'true' = '".(nucleo_acceso($vp['acceso']['control_gobierno'])?'true':'false')."') LIMIT 1");
		$refer_url = 'chats';
	} elseif (($_GET['b'] == 'limpiar') AND ($_GET['chat_ID'])) {
		error_log("Cleaning chat... "."DELETE FROM chats_msg WHERE chat_ID = '".$_GET['chat_ID']."' AND '".$pol['user_ID']."' = (select user_ID from chats where chat_ID = '".$_GET['chat_ID']."' LIMIT 1)");
		sql("DELETE FROM chats_msg WHERE chat_ID = '".$_GET['chat_ID']."' AND '".$pol['user_ID']."' = (select user_ID from chats where chat_ID = '".$_GET['chat_ID']."' LIMIT 1)");
		$refer_url = 'chats';
	} elseif (($_GET['b'] == 'bloquear') AND ($_GET['chat_ID'])) {
		sql("UPDATE chats SET estado = 'bloqueado' WHERE chat_ID = '".$_GET['chat_ID']."' AND pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' OR 'true' = '".(nucleo_acceso($vp['acceso']['control_gobierno'])?'true':'false')."') LIMIT 1");
		$refer_url = 'chats';
	}
	break;



case 'historia':
	$sc = get_supervisores_del_censo();

	$_POST['hecho'] = trim($_POST['hecho']);
	if (($_GET['b'] == 'add') AND ($_POST['hecho'] != '')) {
		sql("INSERT INTO hechos (time, nick, texto, estado, time2, pais) VALUES ('".$_POST['year']."-".$_POST['mes']."-".$_POST['dia']."', '".$pol['nick']."', '".strip_tags($_POST['hecho'],'<b>,<a>')."', 'ok', '".$date."', '".$_POST['pais']."')");
	} elseif ($_GET['b'] == 'del') {
		sql("UPDATE hechos SET estado = 'del' WHERE ID = '".$_GET['ID']."' AND (nick = '".$pol['nick']."' OR '".$pol['nivel']."' = '100' OR '".$sc[$pol['user_ID']]."' != '') LIMIT 1");
	}


	$refer_url = 'historia';

	break;


case 'geolocalizacion':
	if (($_GET['b'] == 'add') AND (is_numeric($_POST['x'])) AND (is_numeric($_POST['y']))) {

		// Por privacidad solo se guardan 2 digitos reales de latitud y longitud (esto supone una precisión de 1.112km a la redonda a nivel del mar).
		$_POST['x'] = round($_POST['x'], 2);
		$_POST['y'] = round($_POST['y'], 2);

		$result = sql("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND x IS NULL LIMIT 1");
		while($r = r($result)) {
			evento_chat('<b>[#]</b> '.crear_link($pol['nick']).' se ha geolocalizado en el <a href="/geolocalizacion"><b>mapa</b> de ciudadanos</a>');
		}
		sql("UPDATE users SET x = '".$_POST['x']."', y = '".$_POST['y']."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		
	} elseif ($_GET['b'] == 'del') {
		sql("UPDATE users SET x = NULL, y = NULL WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	}
	$refer_url = 'geolocalizacion';
	break;



case 'sancion':
	if ((nucleo_acceso($vp['acceso']['control_sancion'])) AND ($_POST['pols'] <= 5000) AND ($_POST['pols'] > 0)) {
		$result = sql("SELECT ID, nick FROM users 
WHERE nick = '".$_POST['nick']."' AND estado = 'ciudadano' AND pais = '".PAIS."' LIMIT 1");
		while($r = r($result)) {
		
			pols_transferir($_POST['pols'], $r['ID'], '-1', '<b>SANCION ('.$pol['nick'].')&rsaquo;</b> '.strip_tags($_POST['concepto']));

			evento_chat('<b>[SANCION] '.crear_link($r['nick']).'</b> ha sido sancionado con '.pols($_POST['pols']).' '.MONEDA.' (<a href="/control/judicial">Ver sanciones</a>)');
		}

	}
	$refer_url = 'control/judicial';

	break;

case 'pass':
	if (($pol['user_ID'] == 1) AND ($_GET['nick'])) {


		$result = sql("SELECT ID, nick, email FROM users WHERE nick = '".$_GET['nick']."' LIMIT 1");
		while($r = r($result)) {
			$email = $r['email'];
			$user_ID = $r['ID'];
			$nick = $r['nick'];
		}

		if ($_GET['nick'] == $nick) {
			$new_pass = rand(1000000,9999999);
			sql("UPDATE users SET pass = '".pass_key($new_pass, 'md5')."', pass2 = '".pass_key($new_pass)."', reset_last = fecha_registro WHERE ID = '".$user_ID."' LIMIT 1");

			$mensaje = "<p>Ciudadano ".$nick.".</p><p>Se ha procedido a resetear tu contraseña por razones de seguridad. Por lo tanto tu contraseña ha cambiado.</p><p>Usuario: ".$nick."<br />Nueva contraseña: ".$new_pass."</p><p>Para entrar: ".REGISTRAR."login.php</p><p>Es recomendado que cambies tu contraseña. También puedes iniciar un proceso de recuperación con tu email.</p><p>Gracias, nos vemos en VirtualPol ;)</p><p>VirtualPol<br />".SSL_URL."</p>";
			enviar_email($user_ID, 'Nueva contraseña para el usuario: '.$nick, $mensaje);
			
			unset($new_pass);
			echo 'OK: '.$_GET['nick'];
		} else { echo 'Error.'; }
		exit;
	}
	break;




case 'rechazar-ciudadania':
	
	$user_ID = false;
	$result3 = sql("SELECT IP, pols, nick, ID, ref, estado,
".(ECONOMIA?"(SELECT SUM(pols) FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."')":"estado")." AS pols_cuentas 
FROM users 
WHERE ID = '".$pol['user_ID']."' AND estado = 'ciudadano' AND pais = '".PAIS."'
LIMIT 1");
	while($r3 = r($result3)) {
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

		if (ECONOMIA) {
			pols_transferir($pols_arancel, $user_ID, '-1', 'Arancel de salida (rechazo de ciudadania) '.$pol['config']['arancel_salida'].'%');
			
			sql("DELETE FROM empresas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
			sql("DELETE FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
			sql("DELETE FROM mapa WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
			sql("DELETE FROM pujas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
		}

		sql("DELETE FROM cargos_users WHERE user_ID = '".$user_ID."'");
		sql("UPDATE users SET estado = 'turista', pais = 'ninguno', nivel = '1', cargo = '0', cargos = '', examenes = '', nota = '0.0', pols = '".$pols."', rechazo_last = '".$date."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		sql("DELETE FROM partidos_listas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
		sql("DELETE FROM partidos WHERE pais = '".PAIS."' AND ID_presidente = '".$user_ID."'");

		evento_log('Rechaza ciudadanía');
		//evento_chat('<b>[#]</b> '.crear_link($nick).' rechaza la Ciudadania de '.PAIS);

		unset($_SESSION);
		session_unset(); session_destroy();
	}
	redirect(REGISTRAR);
	break;


case 'expulsar':
	$sc = get_supervisores_del_censo();
	if (($_GET['b'] == 'desexpulsar') AND ($_GET['ID'])) {
		$result = sql("SELECT ID, user_ID, tiempo  FROM expulsiones WHERE ID = '".$_GET['ID']."' LIMIT 1");
		while ($r = r($result)) {
			sql("UPDATE users SET estado = 'ciudadano', fecha_last = '".$date."' WHERE ID = '".$r['user_ID']."' LIMIT 1");
			sql("UPDATE expulsiones SET estado = 'cancelado' WHERE ID = '".$_GET['ID']."' LIMIT 1");
		}

	} elseif ((nucleo_acceso('supervisores_censo')) AND ($_POST['razon']) AND ($_POST['nick'])) { 

		if ($_POST['caso']) { $_POST['razon'] .= ' caso '.ucfirst($_POST['caso']); }

		$_POST['motivo'] = strip_tags($_POST['motivo']);

		$result = sql("SELECT nick, ID FROM users WHERE nick IN ('".implode("','", explode(' ', $_POST['nick']))."') AND estado != 'expulsado' LIMIT 8");
		while ($r = r($result)) {
			sql("UPDATE users SET estado = 'expulsado' WHERE ID = '".$r['ID']."' LIMIT 1");
			//sql("DELETE FROM votos WHERE emisor_ID = '".$r['ID']."'");

			// Cambia a "En Blanco" los votos. Es equivalente a anular el voto.
			$result2 = sql("SELECT ID, tipo_voto, respuestas FROM votacion WHERE estado = 'ok'");
			while ($r2 = r($result2)) { 
				$voto_en_blanco = '';
				if ($r2['tipo_voto'] == 'multiple') { 
					foreach (implode('|', $r2['respuestas']) AS $id => $pregunta) { if ($pregunta != '') { $voto_en_blanco .= '0 '; } }
					$voto_en_blanco = trim($voto_en_blanco);
				}
				else if ($r2['tipo_voto'] == '3puntos') { $voto_en_blanco = '0 0 0'; }
				else if ($r2['tipo_voto'] == '5puntos') { $voto_en_blanco = '0 0 0 0 0'; }
				else if ($r2['tipo_voto'] == '8puntos') { $voto_en_blanco = '0 0 0 0 0 0 0 0'; }
				else { $voto_en_blanco = '0'; }
				sql("UPDATE votacion_votos SET voto = '".$voto_en_blanco."', validez = 'true', mensaje = '' WHERE ref_ID = ".$r2['ID']." AND user_ID = ".$r['ID']." LIMIT 1");
			}
			
			sql("INSERT INTO expulsiones (user_ID, autor, expire, razon, estado, tiempo, IP, cargo, motivo) VALUES ('".$r['ID']."', '".$pol['user_ID']."', '".$date."', '".ucfirst(strip_tags($_POST['razon']))."', 'expulsado', '".$r['nick']."', '0', '".$pol['cargo']."', '".$_POST['motivo']."')");
		}
	}
	$refer_url = '/control/expulsiones';
	break;


case 'voto':
	$tipo = $_GET['tipo'];	
	$item_ID = $_GET['item_ID'];
	$voto = $_GET['voto'];
	$tipos_posibles = array('confianza', 'hilos', 'msg', 'argumentos');
	$votos_posibles = array('1', '0'); // Eliminado el voto de confianza '-1'.
	$voto_result = "false";
	if ((in_array($tipo, $tipos_posibles)) AND (in_array($voto, $votos_posibles))) {

		// Comprobaciones
		$check = false;
		if ($tipo == 'confianza') {
			$pais = 'all';

			// numero de votos emitidos
			$result = sql("SELECT COUNT(*) AS num FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND voto != '0'");
			while ($r = r($result)) { $num_votos = $r['num']; }

			$sc = get_supervisores_del_censo();
			if (isset($sc[$pol['user_ID']])) { $num_votos = 0; }

			// existe usuario
			$result = sql("SELECT ID FROM users WHERE ID = '".$item_ID."'");
			while ($r = r($result)) { $nick_existe = true; }

			if (($item_ID != $pol['user_ID']) AND ($nick_existe == true) AND (($voto == '0') OR ($num_votos < VOTO_CONFIANZA_MAX))) { 
				$check = true; 
				$voto_result = "true";
			} else {
				$voto_result = "limite";
			}

		} elseif ($tipo == 'argumentos') {
			$pais = PAIS;
			$result = sql("SELECT ID FROM votacion_argumentos WHERE ID = '".$item_ID."' LIMIT 1");
			while ($r = r($result)) { $check = true; }
		} else {
			$pais = PAIS;
			$result = sql("SELECT ID FROM ".SQL."foros_".$tipo." WHERE ID = '".$item_ID."' AND user_ID != '".$pol['user_ID']."' LIMIT 1");
			while ($r = r($result)) { $check = true; }
		}

		if ($check) {

			// has votado a este item?
			$hay_voto = false;
			$result = sql("SELECT voto_ID FROM votos WHERE tipo = '".$tipo."' AND pais = '".$pais."' AND emisor_ID = '".$pol['user_ID']."' AND item_ID = '".$item_ID."' LIMIT 1");
			while ($r = r($result)) { $hay_voto = $r['voto_ID']; }

			if ($hay_voto != false) {
				sql("UPDATE votos SET voto = '".$voto."', time = '".$date."' WHERE voto_ID = '".$hay_voto."' LIMIT 1");
			} else {
				sql("INSERT INTO votos (item_ID, pais, emisor_ID, voto, time, tipo) VALUES ('".$item_ID."', '".$pais."', '".$pol['user_ID']."', '".$voto."', '".$date."', '".$tipo."')");
			}

			// Contadores
			if (($tipo == 'hilos') OR ($tipo == 'msg')) {
				$result = sql("SELECT SUM(voto) AS num, COUNT(*) AS votos_num FROM votos WHERE tipo = '".$tipo."' AND pais = '".$pais."' AND item_ID = '".$item_ID."' AND (voto = 1 OR voto = -1)");
				while ($r = r($result)) { 
					$voto_result = $r['num'];
					sql("UPDATE ".SQL."foros_".$tipo." SET votos = '".$r['num']."', votos_num = '".$r['votos_num']."' WHERE ID = '".$item_ID."' LIMIT 1");
				}
			} elseif ($tipo == 'argumentos') {
				$result = sql("SELECT SUM(voto) AS num, COUNT(*) AS votos_num FROM votos WHERE tipo = '".$tipo."' AND item_ID = '".$item_ID."' AND (voto = 1 OR voto = -1)");
				while ($r = r($result)) { 
					$voto_result = $r['num'];
					sql("UPDATE votacion_argumentos SET votos = '".$r['num']."', votos_num = '".$r['votos_num']."' WHERE ID = '".$item_ID."' LIMIT 1");
				}
			}
		}
	}
	echo $voto_result;
	mysql_close($link); exit;
	break;


case 'avatar':
	$img_root = RAIZ.'/img/a/';
	if ($_GET['b'] == 'upload') {
		unlink($img_root.$pol['user_ID'].'.jpg');
		unlink($img_root.$pol['user_ID'].'_40.jpg');
		unlink($img_root.$pol['user_ID'].'_80.jpg');
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

			sql("UPDATE users SET avatar_localdir = '".escape($_FILES['avatar']['name'])."', avatar = 'true' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		}
	} elseif ($_GET['b'] == 'borrar') {
		unlink($img_root.$pol['user_ID'].'.jpg');
		unlink($img_root.$pol['user_ID'].'_40.jpg');
		unlink($img_root.$pol['user_ID'].'_80.jpg');
		sql("UPDATE users SET avatar = 'false' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		$refer_url = 'perfil/editar';
	} elseif (($_GET['b'] == 'desc') AND (strlen($_POST['desc']) <= 2000)) {
		$_POST['desc'] = gen_text($_POST['desc'], 'plain');
		sql("UPDATE users SET text = '".$_POST['desc']."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	}
	$refer_url = 'perfil/editar';
	break;


case 'examenes':
	if (($_GET['b'] == 'crear') AND ($_POST['titulo']) AND (nucleo_acceso($vp['acceso']['examenes_decano']))) {
		$_POST['titulo'] = gen_title($_POST['titulo']);
		sql("INSERT INTO examenes (pais, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas) VALUES ('".PAIS."', '" . $_POST['titulo'] . "', 'Editar...', '" . $pol['user_ID'] . "', '" . $date . "', '" . $_POST['cargo_ID'] . "', '5.0', 10)");
		$new_ID = mysql_insert_id($link);
		sql("UPDATE examenes SET cargo_ID = '-" . $new_ID . "' WHERE pais = '".PAIS."' AND ID = '" . $new_ID . "' LIMIT 1");
		evento_log('Examen nuevo '.$_POST['titulo']);
		$refer_url = 'examenes';


	} elseif (($_GET['b'] == 'nueva-pregunta') AND ($_GET['ID'] != null) AND ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) AND ($_POST['pregunta']) AND ($_POST['respuesta0'] != null) AND ($_POST['respuesta1'] != null) AND ($_POST['tiempo'])) {
		for ($i=0;$i<10;$i++) { 
			if ($_POST['respuesta' . $i]) { 
				if ($respuestas) { $respuestas .= '|'; }
				$respuestas .= ucfirst(trim(str_replace("|", "", $_POST['respuesta' . $i]))); 
			} 
		}
		$pregunta = ucfirst($_POST['pregunta']);
		sql("INSERT INTO examenes_preg (pais, examen_ID, user_ID, time, pregunta, respuestas, tiempo) VALUES ('".PAIS."', '" . $_GET['ID'] . "', '" . $pol['user_ID'] . "', '" . $date . "', '" . $pregunta . "', '" . $respuestas . "', " . $_POST['tiempo'] . ")");
		$refer_url = 'examenes/editar/' . $_GET['ID'];

	} elseif (($_GET['b'] == 'eliminar-pregunta') AND ($_GET['ID'] != null) AND ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor'])))) {
		sql("DELETE FROM examenes_preg WHERE pais = '".PAIS."' AND ID = '" . $_GET['ID'] . "' LIMIT 1");
		$refer_url = 'examenes/editar/' . $_GET['re_ID'];

	} elseif (($_GET['b'] == 'editar-examen') AND ($_GET['ID'] != null) AND (nucleo_acceso($vp['acceso']['examenes_decano'])) AND ($_POST['titulo']) AND ($_POST['descripcion']) AND ($_POST['nota'] >= 0) AND ($_POST['num_preguntas'] >= 0)) {
		$_POST['descripcion'] = gen_text($_POST['descripcion'], 'plain');
		sql("UPDATE examenes SET titulo = '".$_POST['titulo']."', descripcion = '".$_POST['descripcion'] . "', nota = '".$_POST['nota']."', num_preguntas = '".$_POST['num_preguntas']."' WHERE pais = '".PAIS."' AND ID = '" . $_GET['ID'] . "' LIMIT 1");
		evento_log('Examen editado #'.$_GET['ID']);
		$refer_url = 'examenes/editar/'.$_GET['ID'];
		
	} elseif (($_GET['b'] == 'examinar') AND ($_GET['ID'] != null) AND ($_POST['pregs']) AND (($_POST['tlgs'] + 10) > time())) {

		$result = sql("SELECT cargo_ID, titulo, ID, nota, num_preguntas,
(SELECT COUNT(*) FROM examenes_preg WHERE pais = '".PAIS."' AND examen_ID = examenes.ID LIMIT 1) AS num_depreguntas
FROM examenes WHERE pais = '".PAIS."' AND ID = '" . $_GET['ID'] . "' LIMIT 1");
		while($r = r($result)){ 
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
				if ($nota['nota'] >= $nota_aprobado) { $estado = ", aprobado = 'ok'"; } else { $estado = ", aprobado = 'no'"; }

				sql("UPDATE cargos_users SET time = '".$date."', nota = '".$nota['nota']."'".$estado." WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' AND cargo_ID = '".$cargo_ID."' LIMIT 1");

				if ($nota['nota'] >= $nota_aprobado) { // APROBADO
					
					$result2 = sql("SELECT cargo_ID FROM examenes WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND cargo_ID > 0 LIMIT 1");
					while($r2 = r($result2)){
						$result3 = sql("SELECT cargo_ID FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$r2['cargo_ID']."' AND autocargo = 'true' LIMIT 1");
						while($r3 = r($result3)){ $auto_cargo = $r3['cargo_ID']; }	
					}
					if ($auto_cargo > 0) {
						cargo_add($auto_cargo, $pol['user_ID'], true, true);
					} else {
						evento_chat('<b>[CARGO]</b> '.crear_link($pol['nick']).' se postula como candidato a <a href="/cargos">'.$examen_titulo.'</a> <span class="gris">('.$nota['nota'].')</span>'); 
						actualizar('examenes');
					}
				}

				$refer_url = 'cargos';
			}
			unset($_SESSION['examen']);
		}
	} elseif (($_GET['b'] == 'eliminar-examen') AND ($_POST['ID'] != null) AND (nucleo_acceso($vp['acceso']['examenes_decano']))) { 
		$result = sql("SELECT cargo_ID,
(SELECT COUNT(*) FROM examenes_preg WHERE pais = '".PAIS."' AND examen_ID = examenes.ID LIMIT 1) AS num_depreguntas
FROM examenes WHERE pais = '".PAIS."' AND ID = '".$_POST['ID']."' LIMIT 1");
		while($r = r($result)){ 
			if (($r['cargo_ID'] < 0) AND ($r['num_depreguntas'] == 0)) {
				sql("DELETE FROM examenes WHERE pais = '".PAIS."' AND ID = '".$_POST['ID']."'");
				evento_log('Examen eliminado #'.$_POST['ID']);
				$refer_url = 'cargos';
			}
		}
	} elseif (($_GET['b'] == 'caducar_examen') AND ($_GET['ID'] != null)) {
	
		if ($_POST['pais'] == PAIS) {
			sql("DELETE FROM cargos_users WHERE cargo_ID = '".$_GET['ID']."' AND user_ID = '". $pol['user_ID']."' AND time < '".date('Y-m-d 20:00:00', time() - $pol['config']['examen_repe']*6)."' AND cargo_ID <= 0");
			actualizar('examenes');
			$refer_url = 'cargos';
		}
	} elseif (($_GET['b'] == 'retirar_examen') AND (is_numeric($_GET['ID']))) {
		sql("DELETE FROM cargos_users WHERE cargo_ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		actualizar('examenes');
		evento_log('Candidatura retirada al cargo #'.$_GET['ID']);
		$refer_url = 'cargos';
	}

	break;


case 'mapa':

	// pasa a ESTADO
	if (nucleo_acceso('cargo', 40)) { sql("UPDATE mapa SET estado = 'e', user_ID = '' WHERE pais = '".PAIS."' AND link = 'ESTADO'"); }

	if (($_GET['b'] == 'compraventa') AND ($_GET['ID'])) {


		$result = sql("SELECT ID, user_ID, pols FROM mapa WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND estado = 'v' AND '".$pol['pols']."' >= pols LIMIT 1");
		while($r = r($result)){ 
			if ($pol['user_ID'] != $r['user_ID']) {
				pols_transferir($r['pols'], $pol['user_ID'], $r['user_ID'], 'Compra-venta propiedad: '.$r['ID']);
				sql("UPDATE mapa SET estado = 'p', user_ID = '".$pol['user_ID']."', nick = '".$pol['nick']."' WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' LIMIT 1");
			}
		}
		$refer_url = 'mapa/';

	} elseif (($_GET['b'] == 'cancelar-venta') AND ($_GET['ID'])) {

		sql("UPDATE mapa SET estado = 'p' WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		$refer_url = 'mapa/propiedades';

	} elseif (($_GET['b'] == 'vender') AND ($_GET['ID']) AND ($_POST['pols'] > 0)) {

		sql("UPDATE mapa SET pols = '".$_POST['pols']."', estado = 'v' WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		$refer_url = 'mapa/propiedades';


	} elseif (($_GET['b'] == 'eliminar') AND ($_GET['ID'])) {

		sql("DELETE FROM mapa WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND (user_ID = '".$pol['user_ID']."' OR (estado = 'e' AND 'true' = '".(nucleo_acceso('cargo', 40)?'true':'false')."')) LIMIT 1");
		$refer_url = 'mapa/propiedades';



	} elseif (($_GET['b'] == 'ceder') AND ($_GET['ID']) AND ($_POST['nick'])) {

		$result = sql("SELECT ID, user_ID, pols, 
(SELECT ID FROM users WHERE nick = '".$_POST['nick']."' AND pais = '".PAIS."' AND estado = 'ciudadano' LIMIT 1) AS ceder_user_ID 
FROM mapa 
WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' AND (estado = 'p' OR estado = 'e') LIMIT 1");
		while($r = r($result)){ 
			if ($r['ceder_user_ID']) {
				sql("UPDATE mapa SET user_ID = '".$r['ceder_user_ID']."', nick = '".$_POST['nick']."',  time = '".$date."' WHERE pais = '".PAIS."' AND ID = '".$r['ID']."' LIMIT 1");
				evento_log('Cede propiedad #'.$r['ID']);
			}
		}

		$refer_url = 'mapa/propiedades';

	} elseif (($_GET['b'] == 'separar') AND ($_GET['ID'])) {

		$result = sql("SELECT * FROM mapa WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND (estado = 'p' OR estado = 'e') AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		while($r = r($result)){ 
			
			for ($y=1;$y<=$r['size_y'];$y++) {
				for ($x=1;$x<=$r['size_x'];$x++) {
					if (($x==1) AND ($y==1)) {
						sql("UPDATE mapa SET size_x = 1, size_y = 1, superficie = 1, time = '".$date."', estado = 'p' WHERE pais = '".PAIS."' AND ID = '".$r['ID']."' LIMIT 1");
						$puntero_x = $r['pos_x'];
						$puntero['pos_x'] = $r['pos_x'];
						$puntero['pos_y'] = $r['pos_y'];
					} else {
						sql("INSERT INTO mapa (pais, pos_x, pos_y, size_x, size_y, user_ID, nick, link, text, time, pols, color, estado, superficie) VALUES ('".PAIS."', '".$puntero['pos_x']."', '".$puntero['pos_y']."', '1', '1', '".$pol['user_ID']."', '".$pol['nick']."', '".$r['link']."', '', '".$date."', '".$r['pols']."', '".$r['color']."', 'p', '1')");
					}
					$puntero['pos_x']++;
				}
				$puntero['pos_x'] = $puntero_x;
				$puntero['pos_y']++;
			}

		}
		
		
		$refer_url = 'mapa/propiedades';

	} elseif (($_GET['b'] == 'fusionar') AND ($_GET['ID']) AND ($_GET['f'])) {

		$ID = explode("-", $_GET['ID']);

		$result = sql("SELECT *
FROM mapa 
WHERE pais = '".PAIS."' AND (user_ID = '".$pol['user_ID']."' OR (estado = 'e' AND 'true' = '".(nucleo_acceso('cargo', 40)?'true':'false')."')) AND (ID = '".$ID[0]."' OR ID = '".$ID[1]."') LIMIT 2");
		while($r = r($result)){ 
			$prop[$r['ID']]['size_x'] = $r['size_x'];
			$prop[$r['ID']]['size_y'] = $r['size_y'];
		}

		//propiedades ok
		if (($prop[$ID[0]]['size_x']) AND ($prop[$ID[1]]['size_x'])) {
			if ($_GET['f'] == 'x') {

				//ampliar 0
				$size_x = ($prop[$ID[0]]['size_x'] + $prop[$ID[1]]['size_x']);
				$size_y = $prop[$ID[0]]['size_y'];
				sql("UPDATE mapa SET size_x = '".$size_x."', superficie = '".($size_x * $size_y)."' WHERE pais = '".PAIS."' AND ID = '".$ID[0]."' LIMIT 1");
				//eliminar 1
				sql("DELETE FROM mapa WHERE pais = '".PAIS."' AND ID = '".$ID[1]."' LIMIT 1");

			} elseif ($_GET['f'] == 'y') {

				//ampliar 0
				$size_x = $prop[$ID[0]]['size_x'];
				$size_y = ($prop[$ID[0]]['size_y'] + $prop[$ID[1]]['size_y']);
				sql("UPDATE mapa SET size_y = '".$size_y."', superficie = '".($size_x * $size_y)."' WHERE pais = '".PAIS."' AND ID = '".$ID[0]."' LIMIT 1");
				//eliminar 1
				sql("DELETE FROM mapa WHERE pais = '".PAIS."' AND ID = '".$ID[1]."' LIMIT 1");

			}
		}
		$refer_url = 'mapa/propiedades';


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

		$result = sql("SELECT * FROM mapa WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' LIMIT 1");
		while($r = r($result)){ 
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
			sql("UPDATE mapa SET color = '".$_POST['color']."', text = '".$_POST['text']."', link = '".$_POST['link']."' WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND (user_ID = '".$pol['user_ID']."' OR (estado = 'e' AND 'true' = '".(nucleo_acceso('cargo', 40)?'true':'false')."')) LIMIT 1");
			$refer_url = 'mapa/propiedades';
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
			$result = sql("SELECT pos_x, pos_y, size_x, size_y FROM mapa WHERE pais = '".PAIS."'");
			while($r = r($result)){
				for ($y=1;$y<=$r['size_y'];$y++) {
					for ($x=1;$x<=$r['size_x'];$x++) {
						$cc[($r['pos_x'] + ($x - 1))][($r['pos_y'] + ($y - 1))] = true;
					}
				}

			}
			

			if (($cc[$pos[0]][$pos[1]] != true) AND ($pol['pols'] >= $pol['config']['pols_solar'])) { // verifica solar libre

				sql("INSERT INTO mapa (pais, pos_x, pos_y, size_x, size_y, user_ID, nick, link, text, time, pols, color, estado, superficie) VALUES ('".PAIS."', '".$pos[0]."', '".$pos[1]."', '1', '1', '".$pol['user_ID']."', '".$pol['nick']."', '".$_POST['link']."', '', '".$date."', '".$pol['config']['pols_solar']."', '".$_POST['color']."', 'p', '1')");
				pols_transferir($pol['config']['pols_solar'], $pol['user_ID'], '-1', 'Compra propiedad: '.$_GET['ID']);
			}
		}

		$refer_url = 'mapa';

	}
	break;


case 'gobierno':


	$dato_array = array(
'online_ref'=>'Tiempo online en minutos para referencia',
'pols_mensajetodos'=>'Coste mensaje Global',
'pols_solar'=>'Coste solar del mapa',
'defcon'=>'DEFCON',
'pols_inem'=>'INEM',
'pols_afiliacion'=>'Pago por afiliado',
'pols_empresa'=>'Coste creacion empresa',
'pols_cuentas'=>'Coste creacion cuenta bancaria',
'pols_partido'=>'Coste creacion partido politico',
'factor_propiedad'=>'Factor propiedad',
'pols_examen'=>'Coste hacer un examen',
'pols_mensajeurgente'=>'Coste mensaje urgente',
'examenes_exp'=>'Expiración de candidaturas',
'impuestos'=>'Impuesto de patrimonio',
'impuestos_minimo'=>'Minimo patrimonio imponible',
'impuestos_empresa'=>'Impuesto de empresa',
'arancel_salida'=>'Arancel de salida',
'bg'=>'Imagen de fondo',
'pais_des'=>'Nombre de plataforma',
'pols_crearchat'=>'Coste creacion chat',
'chat_diasexpira'=>'Días expiracion chat',
'lang'=>'Idioma',
'tipo'=>'Tipo de plataforma',
'timezone'=>'Zona horaria',
);

	if (
($_GET['b'] == 'config') AND 
(nucleo_acceso($vp['acceso']['control_gobierno'])) AND  
(entre($_POST['online_ref'], 0, 900000)) AND
($_POST['chat_diasexpira'] >= 10)
) {

		foreach ($_POST AS $dato => $valor) {
			if (substr($dato, 0, 8) != 'salario_') {
				
				$valor = strip_tags($valor);
				
				switch ($dato) {
					case 'online_ref': $valor = round($_POST['online_ref']*60); break;
					case 'palabra_gob': $valor = nl2br($valor); break;
				}

				sql("UPDATE config SET valor = '".$valor."' WHERE pais = '".PAIS."' AND dato = '".$dato."' LIMIT 1");

				if (($pol['config'][$dato] != $valor) AND ($dato_array[$dato])) { 
					if ($valor == '') { $valor = '<em>null</em>'; }
					if ($dato == 'online_ref') {
						$valor = intval($valor)/60; 
						$pol['config'][$dato] = $pol['config'][$dato]/60;
					}
					evento_chat('<b>[GOBIERNO]</b> Configuración ('.crear_link($pol['nick']).'): <em>'.$dato_array[$dato].'</em> de <b>'.$pol['config'][$dato].'</b> a <b>'.$valor.'</b> (<a href="/control/gobierno/">Gobierno</a>)');
				}
			}
		}

		if ($_FILES['nuevo_tapiz']['name']) {
			$nom_file = RAIZ.'/img/bg/tapiz-extra-'.strtolower(str_replace('_','-', gen_url(substr(explodear('.', $_FILES['nuevo_tapiz']['name'], 0), 0, 8)))).'_'.PAIS.'.jpg';
			if (str_replace('image/', '', $_FILES['nuevo_tapiz']['type']) == 'jpeg') {
				move_uploaded_file($_FILES['nuevo_tapiz']['tmp_name'], $nom_file);
			}
			if (file_exists($nom_file)) {
				imageCompression($nom_file, null, $nom_file, 'jpeg', 1440, 100);
			}
		}
		
		if ($_FILES['nuevo_bandera']['name']) {
			$nom_file = RAIZ.'/img/banderas/'.PAIS.'.png';
			copy($nom_file, RAIZ.'/img/banderas/'.PAIS.'_'.time().'.png');
			if ((str_replace('image/', '', $_FILES['nuevo_bandera']['type']) == 'png') AND ($_FILES['nuevo_bandera']['size'] <= 50000)) {
				move_uploaded_file($_FILES['nuevo_bandera']['tmp_name'], $nom_file);
			}
			if (file_exists($nom_file)) {
				evento_chat('<b>[GOBIERNO]</b> Configuración ('.crear_link($pol['nick']).'): nueva bandera <img src="'.IMG.'banderas/'.PAIS.'.png?'.rand(1000,9999).'" width="80" height="50" /> (<a href="/control/gobierno">Gobierno</a>)');
			}
		}

		
		if ($_FILES['nuevo_logo']['name']) {
			$nom_file = RAIZ.'/img/banderas/'.PAIS.'_logo.png';
			copy($nom_file, RAIZ.'/img/banderas/'.PAIS.'_logo_'.time().'.png');
			if ((str_replace('image/', '', $_FILES['nuevo_logo']['type']) == 'png') AND ($_FILES['nuevo_logo']['size'] <= 80000)) {
				move_uploaded_file($_FILES['nuevo_logo']['tmp_name'], $nom_file);
			}
			if (file_exists($nom_file)) {
				evento_chat('<b>[GOBIERNO]</b> Configuración ('.crear_link($pol['nick']).'): nuevo logo <img src="'.IMG.'banderas/'.PAIS.'_logo.png?'.rand(1000,9999).'" width="200" height="60" /> (<a href="/control/gobierno">Gobierno</a>)');
			}
		}
		evento_log('Gobierno configuración: principal');
		$refer_url = 'control/gobierno';


	} elseif (
($_GET['b'] == 'economia') AND 
(nucleo_acceso($vp['acceso']['control_gobierno'])) AND  
($_POST['pols_inem'] >= 0) AND ($_POST['pols_inem'] <= 500) AND
(entre($_POST['pols_afiliacion'], 0, 2000)) AND
($_POST['pols_empresa'] >= 0) AND
($_POST['pols_cuentas'] >= 0) AND
($_POST['pols_partido'] >= 0) AND
($_POST['pols_solar'] >= 0) AND
($_POST['pols_crearchat'] >= 0) AND
(entre($_POST['factor_propiedad'], 0, 10)) AND 
($_POST['pols_mensajetodos'] >= 300) AND 
($_POST['pols_examen'] >= 0) AND 
($pol['config']['pols_mensajeurgente'] >= 0) AND
($_POST['impuestos'] <= 25) AND ($_POST['impuestos'] >= 0) AND
($_POST['impuestos_minimo'] >= -1000) AND
($_POST['impuestos_empresa'] <= 1000) AND ($_POST['impuestos_empresa'] >= 0) AND
($_POST['arancel_salida'] <= 100) AND ($_POST['arancel_salida'] >= 0)
) {

		foreach ($_POST AS $dato => $valor) {
			if ((substr($dato, 0, 8) != 'salario_') AND ($dato != 'palabra_gob1')) {

				sql("UPDATE config SET valor = '".strip_tags($valor)."' WHERE pais = '".PAIS."' AND dato = '".$dato."' LIMIT 1");
			
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

		if (ECONOMIA) {
			// Salarios
			$result = sql("SELECT cargo_ID, salario, nombre FROM cargos WHERE pais = '".PAIS."'");
			while($r = r($result)){
				$salario = $_POST['salario_'.$r['cargo_ID']];
				if (($salario >= 0) AND ($salario <= 1000)) {
					if ($salario != $r['salario']) { evento_chat('<b>[GOBIERNO]</b> El salario de <img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" /><b>'.$r['nombre'].'</b> se ha cambiado de '.pols($r['salario']).' '.MONEDA.' a '.pols($salario).' '.MONEDA.' ('.crear_link($pol['nick']).', <a href="/control/gobierno/">Gobierno</a>)');  }
					sql("UPDATE cargos SET salario = '".$salario."' WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' LIMIT 1");
				}
			}
		}
		evento_log('Gobierno configuración: economía');
		$refer_url = 'control/gobierno/economia';

	} elseif (($_GET['b'] == 'categorias') AND (nucleo_acceso($vp['acceso']['control_gobierno']))) {
		
		if ($_GET['c'] == 'editar') {
			$result = sql("SELECT ID FROM cat WHERE pais = '".PAIS."'");
			while ($r = r($result)) { 
				error_log("Updating category: UPDATE cat SET url = '". gen_url($_POST[$r['ID'].'_nombre'])."', nombre = '".$_POST[$r['ID'].'_nombre']."', nivel = '".$_POST[$r['ID'].'_nivel']."', orden = '".$_POST[$r['ID'].'_orden']."', publicar='".$_POST[$r['ID'].'_publicable']."' WHERE ID = '".$r['ID']."' LIMIT 1");
				sql("UPDATE cat SET url = '". gen_url($_POST[$r['ID'].'_nombre'])."', nombre = '".$_POST[$r['ID'].'_nombre']."', nivel = '".$_POST[$r['ID'].'_nivel']."', orden = '".$_POST[$r['ID'].'_orden']."', publicar='".$_POST[$r['ID'].'_publicable']."' WHERE ID = '".$r['ID']."' LIMIT 1");
			}
		} elseif ($_GET['c'] == 'crear') {
			error_log("Inserting category: INSERT INTO cat (pais, url, nombre, nivel, orden, tipo, publicar) VALUES ('".PAIS."', '".gen_url($_POST['nombre'])."', '".substr($_POST['nombre'], 0, 40)."', '0', '10', '".($_POST['tipo']?$_POST['tipo']:'docs')."', '".$_POST['publicable']."')");
			sql("INSERT INTO cat (pais, url, nombre, nivel, orden, tipo, publicar) VALUES ('".PAIS."', '".gen_url($_POST['nombre'])."', '".substr($_POST['nombre'], 0, 40)."', '0', '10', '".($_POST['tipo']?$_POST['tipo']:'docs')."', '".$_POST['publicable']."')");

		} elseif ($_GET['c'] == 'eliminar') {
			sql("DELETE FROM cat WHERE ID = '".$_GET['ID']."' LIMIT 1");
		}
		
		evento_log('Gobierno configuración: categorías');
		$refer_url = 'control/gobierno/categorias';

	} elseif (($_GET['b'] == 'privilegios') AND (nucleo_acceso($vp['acceso']['control_gobierno']))) {
		$_POST['control_socios'] = 'cargo';
		$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND dato = 'acceso'");
		while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }
		$accesos = array();
		foreach (explode('|', $pol['config']['acceso']) AS $el_acceso) {
			$acceso = explodear(';', $el_acceso, 0);
			if ($acceso == 'control_gobierno') { $accesos[] = $el_acceso; } else { $accesos[] = $acceso.';'.$_POST[$acceso].':'.str_replace(':', '', str_replace(';', '', trim($_POST[$acceso.'_cfg']))); }
		}
		sql("UPDATE config SET valor = '".implode('|', $accesos)."' WHERE pais = '".PAIS."' AND dato = 'acceso' LIMIT 1");
		evento_log('Gobierno configuración: privilegios');
		$refer_url = 'control/gobierno/privilegios';

	} elseif (($_GET['b'] == 'subforo') AND (nucleo_acceso($vp['acceso']['control_gobierno']))) {
		$subforos = explode('.', $_POST['subforos']);
		foreach ($subforos AS $subforo_ID) {
			sql("UPDATE ".SQL."foros SET descripcion = '".$_POST[$subforo_ID.'_descripcion']."', time = '".$_POST[$subforo_ID.'_time']."', acceso_leer = '".$_POST[$subforo_ID.'_acceso_leer']."', acceso_escribir = '".$_POST[$subforo_ID.'_acceso_escribir']."', acceso_escribir_msg = '".$_POST[$subforo_ID.'_acceso_escribir_msg']."', acceso_cfg_leer = '".$_POST[$subforo_ID.'_acceso_cfg_leer']."', acceso_cfg_escribir = '".$_POST[$subforo_ID.'_acceso_cfg_escribir']."', acceso_cfg_escribir_msg = '".$_POST[$subforo_ID.'_acceso_cfg_escribir_msg']."', limite = '".$_POST[$subforo_ID.'_limite']."' WHERE ID = '".$subforo_ID."' LIMIT 1");
		}
		evento_log('Gobierno configuración: foro');
		$refer_url = 'control/gobierno/foro';

	} elseif (($_GET['b'] == 'crearsubforo') AND (nucleo_acceso($vp['acceso']['control_gobierno']))) {
		sql("INSERT INTO ".SQL."foros (url, title, descripcion, acceso, time, estado, acceso_msg) VALUES ('".gen_url($_POST['nombre'])."', '".$_POST['nombre']."', '', '1', '10', 'ok', '0')");
		evento_log('Gobierno configuración: foro '.$_POST['nombre']);
		$refer_url = 'control/gobierno/foro';

	} elseif (($_GET['b'] == 'eliminarsubforo') AND (nucleo_acceso($vp['acceso']['control_gobierno'])) AND ($_GET['ID'])) {
		sql("DELETE FROM ".SQL."foros WHERE ID = '".$_GET['ID']."' LIMIT 1");
		evento_log('Gobierno configuración: foro eliminado #'.$_GET['ID']);
		$refer_url = 'control/gobierno/foro';
	
	} elseif (($_GET['b'] == 'notificaciones') AND (nucleo_acceso($vp['acceso']['control_gobierno']))) {
		if (($_GET['c'] == 'add') AND ($_POST['texto']) AND ($_POST['url'])) {
			$_POST['texto'] = ucfirst(substr(strip_tags($_POST['texto']), 0, 60));
			$_POST['url'] = str_replace('http://'.strtolower(PAIS).'.'.DOMAIN, '', substr(strip_tags($_POST['url']), 0, 90));
			$result = sql("SELECT ID FROM users WHERE pais = '".PAIS."' AND ".sql_acceso($_POST['acceso'], $_POST['acceso_cfg'])." ORDER BY voto_confianza DESC LIMIT 100000");
			while($r = r($result)){ notificacion($r['ID'], $_POST['texto'], $_POST['url'], PAIS); }
			evento_log('Gobierno configuración: notificación creada ('.$_POST['texto'].')');
		} elseif (($_GET['c'] == 'borrar') AND (is_numeric($_GET['noti_ID']))) {
			$result = sql("SELECT texto FROM notificaciones WHERE noti_ID = '".$_GET['noti_ID']."' LIMIT 1");
			while($r = r($result)){
				sql("DELETE FROM notificaciones WHERE texto = '".$r['texto']."' AND emisor = '".PAIS."'");
			}
			evento_log('Gobierno configuración: notificación eliminada #'.$_GET['noti_ID']);
		}
		$refer_url = 'control/gobierno/notificaciones';
	}
	break;

case 'empresa':
	if (($_GET['b'] == 'crear') AND ($pol['pols'] >= $pol['config']['pols_empresa']) AND (ctype_digit($_POST['cat'])) AND ($_POST['nombre'])) {
		$nombre = $_POST['nombre'];
		$url = gen_url($nombre);
		$result = sql("SELECT ID, url FROM cat WHERE pais = '".PAIS."' AND ID = '".$_POST['cat']."' LIMIT 1");
		while($r = r($result)){ $cat_url = $r['url']; $cat_ID = $r['ID']; }

		sql("INSERT INTO empresas (pais, url, nombre, user_ID, descripcion, web, cat_ID, time) VALUES ('".PAIS."', '".$url."', '".$nombre."', '".$pol['user_ID']."', 'Editar...', '', '".$cat_ID."', '".$date."')");

		sql("SELECT ID FROM ".SQL."vp_empresas WHERE nombre='$nombre'");
		$nick = $_SESSION['pol']['nick'];
		$acciones = 100;
		$acciones=sql("INSERT INTO ".SQL."acciones (nick, nombre_empresa, acciones, pais, ID_empresa) 
		VALUES ('".$nick."', '".$nombre."', '".$acciones."', '".$PAIS."')");

		sql("UPDATE cat SET num = num + 1 WHERE pais = '".PAIS."' AND ID = '".$cat_ID."' LIMIT 1");

		pols_transferir($pol['config']['pols_empresa'], $pol['user_ID'], '-1', 'Creacion nueva empresa: '.$nombre);
		evento_log('Empresa creada ('.$nombre.')');
		$return = $cat_url.'/'.$url;

	} elseif (($_GET['b'] == 'acciones') AND ($_GET['ID']) AND ($_POST['nick'] AND ($_POST['cantidad']))) {
		$id = $_GET['ID'];
		$result = sql("SELECT nombre, ID, user_ID FROM vp_empresas WHERE ID='$id', $link");
		if ($r=r($result)) {
			$id = $r['ID'];
			$nick = $_POST['nick'];
			$cantidad = $_POST['cantidad'];
			$id_user = $r['user_ID'];
			$acciones = sql("INSERT INTO acciones (ID_empresa, num_acciones, nick, pais) 
			VALUES ('".$id."', '".$cantidad."', '".$nick."', '".$PAIS."')");
			$usuario = sql("SELECT nick FROM vp_users WHERE ID = '$id_user', $link");
			if ($r=r($usuario)) {
				$nick = $r['nick'];
				$cantidadacciones = sql("SELECT acciones, nick, nombre_empresa FROM acciones WHERE nick = '$nick' and ID_empresa = '$id', $link");
				if ($r=r($cantidadacciones)) {
					$susacciones = $r['acciones'];
					$totalacciones = $susacciones - $cantidad;
					$accionesresultantes = sql("update acciones set acciones='$totalacciones' where nick='$nick' and ID_empresa='$id'");
				}
			}
		}
	} elseif (($_GET['b'] == 'ceder') AND ($_GET['ID']) AND ($_POST['nick'])) {

		$result = sql("SELECT ID, user_ID, 
(SELECT ID FROM users WHERE nick = '".$_POST['nick']."' AND pais = '".PAIS."' AND estado = 'ciudadano' LIMIT 1) AS ceder_user_ID 
FROM empresas 
WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		while($r = r($result)){ 
			if ($r['ceder_user_ID']) {
				sql("UPDATE empresas SET user_ID = '".$r['ceder_user_ID']."' WHERE pais = '".PAIS."' AND ID = '".$r['ID']."' LIMIT 1");
				evento_log('Cede empresa #'.$r['ID'].' a @'.$_POST['nick']);
			}
		}
		$refer_url = 'empresas';

	} elseif (($_GET['b'] == 'editar') AND ($_POST['txt'])) {
		$txt = gen_text($_POST['txt']);
		sql("UPDATE empresas SET descripcion = '".$txt."' WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		$return =  $_POST['return'];
		evento_log('Empresa editada #'.$_GET['ID']);
		
	} elseif (($_GET['b'] == 'eliminar') AND ($_GET['ID'])) {
		sql("DELETE FROM empresas WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		evento_log('Empresa eliminada #'.$_GET['ID']);
	}
	$refer_url = 'empresas/'.$return;
	break;


case 'mercado':
	if (($_GET['b'] == 'puja') AND ($pol['estado'] != 'extranjero') AND ($_GET['ID']) AND ($_POST['puja'] > 0) AND (is_numeric($_POST['puja'])) AND (date('H:i') != '20:00')) {
		$ID = $_GET['ID'];
		$pols = $_POST['puja'];

		if ($pols <= $pol['pols']) {
			sql("INSERT INTO pujas (pais, mercado_ID, user_ID, pols, time) VALUES ('".PAIS."', '".$ID."', '".$pol['user_ID']."', '".$pols."', '".$date."')");
			evento_chat('<b>[#]</b> <em>'.$pol['nick'].'</em> Ha realizado una puja en la subasta (<a href="/subasta/">Subasta</a>)'); 
		}
		//evento_log('Puja ('.$pols.' monedas)');
		$refer_url = 'subasta';
	
	} elseif (($_GET['b'] == 'editarfrase') AND (($pol['config']['pols_fraseedit'] == $pol['user_ID']) OR (nucleo_acceso($vp['acceso']['control_gobierno'])))) {
		$_POST['url'] = str_replace(array('http://', 'https://', ':', ',', ' '), '', $_POST['url']);
		$url = '<a href="http://'.strip_tags($_POST['url']).'">'.ucfirst(strip_tags($_POST['frase'])).'</a>';
		sql("UPDATE config SET valor = '".$url."' WHERE pais = '".PAIS."' AND dato = 'pols_frase' LIMIT 1");
		evento_log('Frase editada');
		$refer_url = 'subasta/editar';

	} elseif (($_GET['b'] == 'cederfrase') AND ($pol['config']['pols_fraseedit'] == $pol['user_ID']) AND ($pol['nick'] != $_POST['nick'])) {
		$result = sql("SELECT ID, nick, pais FROM users WHERE nick = '".$_POST['nick']."' AND estado = 'ciudadano' LIMIT 1");
		while($r = r($result)){ 
			sql("UPDATE config SET valor = '".$r['ID']."' WHERE pais = '".PAIS."' AND dato = 'pols_fraseedit' LIMIT 1");	
			evento_chat('<b>[#] '.crear_link($pol['nick']).' cede</b> "la frase" a <b>'.crear_link($r['nick']).'</b>'); 
		}
		$refer_url = 'subasta/editar';
		evento_log('Frase cedida a @'.$r['nick']);

	} elseif (($_GET['b'] == 'editarpalabra') AND (is_numeric($_GET['ID'])) AND (strlen($_POST['text']) <= 25)) {
		$_POST['text'] = ereg_replace("[^ A-Za-z0-9-]", "", $_POST['text']);
		$_POST['text'] = str_replace(array('http://', 'https://', ':', ',', '|'), '', $_POST['text']);
		$_POST['url'] = str_replace(array('http://', 'https://', ':', ',', '|', ' '), '', $_POST['url']);
		$dato = '';
		foreach(explode(";", $pol['config']['palabras']) as $num => $t) {
			$t = explode(":", $t);
			if ($dato) { $dato .= ';'; }
			if ((($t[0] == $pol['user_ID']) OR (nucleo_acceso($vp['acceso']['control_gobierno']))) AND ($_GET['ID'] == $num)) {
				$dato .= $t[0].':'.$_POST['url'].':'.$_POST['text'];
			} else {
				$dato .= $t[0].':'.$t[1].':'.$t[2];
			}
		}
		sql("UPDATE config SET valor = '".$dato."' WHERE pais = '".PAIS."' AND dato = 'palabras' LIMIT 1");
		evento_log('Enlace editado #'.$_GET['ID']);
		$refer_url = 'subasta/editar';

	} elseif (($_GET['b'] == 'cederpalabra') AND ($_GET['ID'] >= 0) AND ($pol['nick'] != $_POST['nick'])) {
		$result = sql("SELECT ID, nick, pais FROM users WHERE nick = '".$_POST['nick']."'AND estado = 'ciudadano' LIMIT 1");
		while($r = r($result)){ 
			$dato = '';
			foreach(explode(";", $pol['config']['palabras']) as $num => $t) {
				$t = explode(":", $t);
				if ($dato) { $dato .= ';'; }
				if (($t[0] == $pol['user_ID']) AND ($_GET['ID'] == $num)) {
					$dato .= $r['ID'].'::'.$r['nick'];
				} else { $dato .= $t[0].':'.$t[1].':'.$t[2]; }
			}
			sql("UPDATE config SET valor = '".$dato."' WHERE pais = '".PAIS."' AND dato = 'palabras' LIMIT 1");
			evento_chat('<b>[#] '.crear_link($pol['nick']).' cede</b> la "palabra '.($_GET['ID'] + 1).'" a <b>'.crear_link($r['nick']).'</b>');
			evento_log('Palabra #'.($_GET['ID'] + 1).' cedida a @'.$r['nick']);
		}
		$refer_url = 'subasta/editar';
	}
	if (!$refer_url) { $refer_url = 'subasta'; }
	break;



case 'pols':

	$_POST['pols'] = strval($_POST['pols']);

	$refer_url = 'pols#error';

	if (($_GET['b'] == 'transferir') AND (is_numeric($_POST['pols'])) AND ($_POST['pols'] > 0) AND ($_POST['concepto'])) {



		$concepto = ucfirst(strip_tags($_POST['concepto']));
		$pols = $_POST['pols'];

		$origen = false;
		$destino = false;
		$transf_int = false;
		

		//ORIGEN
		if ($_POST['origen'] == '0') { 
			//Personal

			//tienes dinero suficiente y nick existe
			$result = sql("SELECT ID, pais FROM users WHERE pais = '".PAIS."' AND ID = '".$pol['user_ID']."' AND pols >= '".$pols."' AND estado = 'ciudadano' LIMIT 1");
			while($r = r($result)){ $pais_origen = $r['pais']; $origen = 'ciudadano'; }

		} elseif (is_numeric($_POST['origen'])) { 
			//Cuenta

			$result = sql("SELECT ID FROM cuentas WHERE pais = '".PAIS."' AND ID = '".$_POST['origen']."' AND pols >= '".$pols."' AND (user_ID = '".$pol['user_ID']."' OR (nivel != 0 AND nivel <= '".$pol['nivel']."')) LIMIT 1");
			while($r = r($result)){ $origen = 'cuenta'; }

		}

		//DESTINO
		if (($_POST['destino'] == 'ciudadano') AND ($_POST['ciudadano'])) {
			//Ciudadano

			//nick existe
			$result = sql("SELECT ID, pais FROM users WHERE nick = '".$_POST['ciudadano']."' AND estado = 'ciudadano' LIMIT 1");
			while($r = r($result)){  $pais_destino = $r['pais']; $destino = 'ciudadano'; $destino_user_ID = $r['ID']; }

		} elseif (($_POST['destino'] == 'cuenta') AND ($_POST['cuenta'])) {
			//cuenta
			
			//cuenta existe
			$result = sql("SELECT ID FROM cuentas WHERE pais = '".PAIS."' AND ID = '".$_POST['cuenta']."' LIMIT 1");
			while($r = r($result)){ $destino = 'cuenta'; $destino_cuenta_ID = $r['ID']; }
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
				sql("UPDATE users SET pols = pols - ".$pols." WHERE ID = '".$pol['user_ID']."' LIMIT 1");
				$emisor_ID = $pol['user_ID'];
			} elseif ($origen == 'cuenta') {

				$concepto = '<b>'.$pol['nick'].'&rsaquo;</b> '.$concepto;
				if (!$pol['nick']) { $concepto = 'S&Upsilon;STEM'.$concepto; }
				
				sql("UPDATE cuentas SET pols = pols - ".$pols." WHERE pais = '".PAIS."' AND ID = '".$_POST['origen']."' LIMIT 1");
				$emisor_ID = '-'.$_POST['origen'];
			}

			//ingresar
			if ($destino == 'ciudadano') {



				sql("UPDATE users SET pols = pols + ".$pols." WHERE ID = '".$destino_user_ID."' LIMIT 1");
				$receptor_ID = $destino_user_ID;
			} elseif ($destino == 'cuenta') {
				sql("UPDATE cuentas SET pols = pols + ".$pols." WHERE pais = '".PAIS."' AND ID = '".$destino_cuenta_ID."' LIMIT 1");
				$receptor_ID = '-'.$destino_cuenta_ID;
			}

			// insert historial
			if (($pols > 0) AND ($emisor_ID != $receptor_ID)) {
				sql("INSERT INTO transacciones (pais, pols, emisor_ID, receptor_ID, concepto, time) VALUES ('".PAIS."', '".$pols."', '".$emisor_ID."', '".$receptor_ID."', '".$concepto."', '".$date."')");

				if ($transf_int) {
					sql("INSERT INTO transacciones (pais, pols, emisor_ID, receptor_ID, concepto, time) VALUES ('".$pais_destino."', '".$pols."', '".$emisor_ID."', '".$receptor_ID."', '".$concepto."', '".$date."')");
				}
				$refer_url = 'pols#ok';
			}
		}
	} elseif (($_GET['b'] == 'crear-cuenta') AND ($_POST['nombre']) AND ($pol['pols'] >= $pol['config']['pols_cuentas'])) {
		$_POST['nombre'] = ucfirst(strip_tags($_POST['nombre']));

		pols_transferir($pol['config']['pols_cuentas'], $pol['user_ID'], '-1', 'Creacion nueva cuenta bancaria: '.$_POST['nombre']);
		sql("INSERT INTO cuentas (pais, nombre, user_ID, pols, nivel, time) VALUES ('".PAIS."', '".$_POST['nombre']."', '".$pol['user_ID']."', 0, 0, '".$date."')");

		$refer_url = 'pols/cuentas';

	} elseif (($_GET['b'] == 'eliminar-cuenta') AND ($_GET['ID'])) {
		sql("DELETE FROM cuentas WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' AND pols = '0' AND nivel = '0' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		$refer_url = 'pols/cuentas';
	}

	break;


case 'votacion':
	$votaciones_tipo = array('referendum', 'parlamento', 'sondeo', 'cargo', 'elecciones');
	if (($_GET['b'] == 'crear') AND ($_POST['tipo'] != 'elecciones') AND (in_array($_POST['tipo'], $votaciones_tipo)) AND (nucleo_acceso($vp['acceso']['votacion_borrador']))) {
		
		if ($_POST['votos_expire'] > 0) { } else { $_POST['votos_expire'] = 0; }
		if ($_POST['tipo_voto'] == 'multiple') { unset($_POST['respuesta0']); }

		for ($i=0;$i<100;$i++) {
			if (trim($_POST['respuesta'.$i]) != '') {
				$respuestas .= trim(str_replace('|', '-', $_POST['respuesta'.$i])).'|';
				$respuestas_desc .= trim($_POST['respuesta_desc'.$i]).'][';
			}
		}
		
		$_POST['time_expire'] = round($_POST['time_expire']*$_POST['time_expire_tipo']);
		$_POST['debate_url'] = trim(strip_tags($_POST['debate_url']));
		$_POST['pregunta'] = trim(strip_tags($_POST['pregunta']));
		$_POST['descripcion'] = nl2br(strip_tags(trim($_POST['descripcion'])));
		if ($_POST['aleatorio'] != 'true') { $_POST['aleatorio'] = 'false'; }

		// Protección contra inyección de configuraciones prohibidas de votaciones especiales
		switch ($_POST['tipo']) {
			case 'parlamento':
				$_POST['privacidad'] = 'false';
				$_POST['acceso_votar'] = 'cargo'; $_POST['acceso_cfg_votar'] = '6';
				$_POST['acceso_ver'] = 'anonimos'; $_POST['acceso_cfg_ver'] = '';
				break;

			case 'cargo':
				$result = sql("SELECT nombre FROM cargos WHERE cargo_ID = '".$_POST['cargo']."' AND pais = '".PAIS."'  LIMIT 1");
				while($r = r($result)){ $cargo_nombre = $r['nombre']; }

				$result = sql("SELECT ID, nick FROM users WHERE nick = '".$_POST['nick']."' AND pais = '".PAIS."' LIMIT 1");
				while($r = r($result)){ $cargo_user_ID = $r['ID']; $_POST['nick'] = $r['nick']; }

				if (($cargo_nombre) AND ($cargo_user_ID)) { // fuerza configuracion
					$_POST['tipo_voto'] = 'estandar';
					$_POST['time_expire'] = 86400;
					if ($_POST['cargo'] == 7) { $_POST['time_expire'] = (86400*2); }
					if ((!ASAMBLEA) OR ($_POST['cargo'] == 6)) {
						$_POST['acceso_votar'] = 'ciudadanos'; $_POST['acceso_cfg_votar'] = '';
						$_POST['acceso_ver'] = 'anonimos'; $_POST['acceso_cfg_ver'] = '';
					}
					$ejecutar = 'cargo|'.$_POST['cargo'].'|'.$cargo_user_ID;
					$_POST['pregunta'] = '&iquest;Apruebas que el ciudadano '.$_POST['nick'].' ostente el cargo '.$cargo_nombre.'?';
					$_POST['descripcion'] .= '<hr />&iquest;Estas a favor que <b>'.crear_link($_POST['nick']).'</b> tenga el cargo <b>'.$cargo_nombre.'</b>?<br /><br />Al finalizar esta votaci&oacute;n, si el resultado por mayor&iacute;a es a favor, se otorgar&aacute; el cargo autom&aacute;ticamente, si por el contrario el resultado es en contra se le destituir&aacute; del cargo.';
					$respuestas = 'En Blanco|SI|NO|';
					$_POST['votos_expire'] = 0;	
				} else { exit; }
				break;
		}

		if (is_numeric($_POST['ref_ID'])) {
			sql("UPDATE votacion SET 
pregunta = '".$_POST['pregunta']."', 
descripcion = '".$_POST['descripcion']."', 
respuestas = '".$respuestas."', 
respuestas_desc = '".$respuestas_desc."', 
time_expire = '".$date."', 
tipo = '".$_POST['tipo']."', 
acceso_votar = '".$_POST['acceso_votar']."', 
acceso_cfg_votar = '".$_POST['acceso_cfg_votar']."', 
acceso_ver = '".$_POST['acceso_ver']."', 
acceso_cfg_ver = '".$_POST['acceso_cfg_ver']."', 
ejecutar = '".$ejecutar."', 
votos_expire = '".$_POST['votos_expire']."', 
tipo_voto = '".$_POST['tipo_voto']."', 
privacidad = '".$_POST['privacidad']."', 
debate_url = '".$_POST['debate_url']."', 
aleatorio = '".$_POST['aleatorio']."', 
duracion = '".$_POST['time_expire']."'
WHERE estado = 'borrador' AND ID = '".$_POST['ref_ID']."' AND pais = '".PAIS."' LIMIT 1");
			$ref_ID = $_POST['ref_ID'];
			evento_log('Votación: borrador editado <a href="/votacion/'.$_POST['ref_ID'].'">#'.$_POST['ref_ID'].'</a> ('.$_POST['tipo'].')');

		} else {
			sql("INSERT INTO votacion (pais, pregunta, descripcion, respuestas, respuestas_desc, time, time_expire, user_ID, estado, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, ejecutar, votos_expire, tipo_voto, privacidad, debate_url, aleatorio, duracion) VALUES ('".PAIS."', '".$_POST['pregunta']."', '".$_POST['descripcion']."', '".$respuestas."', '".$respuestas_desc."', '".$date."', '".$date."', '".$pol['user_ID']."', 'borrador', '".$_POST['tipo']."', '".$_POST['acceso_votar']."', '".$_POST['acceso_cfg_votar']."', '".$_POST['acceso_ver']."', '".$_POST['acceso_cfg_ver']."', '".$ejecutar."', '".$_POST['votos_expire']."', '".$_POST['tipo_voto']."', '".$_POST['privacidad']."', '".$_POST['debate_url']."', '".$_POST['aleatorio']."', '".$_POST['time_expire']."')");
			$result = sql("SELECT ID FROM votacion WHERE user_ID = '".$pol['user_ID']."' AND pais = '".PAIS."' ORDER BY ID DESC LIMIT 1");
			while($r = r($result)){ $ref_ID = $r['ID']; }
			evento_log('Votación: borrador creado <a href="/votacion/'.$ref_ID.'">#'.$ref_ID.'</a>');
		}
		redirect(vp_url('/votacion/borradores'));

	} elseif (($_GET['b'] == 'iniciar') AND (is_numeric($_GET['ref_ID']))) {
		
		$result = sql("SELECT * FROM votacion WHERE ID = '".$_GET['ref_ID']."' AND estado = 'borrador' LIMIT 1");
		while($r = r($result)){
			if (nucleo_acceso($vp['acceso'][$r['tipo']])) {
				$r['time_expire'] = date('Y-m-d H:i:s', time() + $r['duracion']); 

				$result2 = sql("SELECT COUNT(*) AS num FROM users WHERE ".sql_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])." LIMIT 1");
				while($r2 = r($result2)){ $censo_num = $r2['num']; }

				sql("UPDATE votacion SET estado = 'ok', user_ID = '".$pol['user_ID']."', time = '".$date."', time_expire = '".$r['time_expire']."', num_censo = '".$censo_num."' WHERE ID = '".$r['ID']."' LIMIT 1");
				if (in_array($r['acceso_ver'], array('anonimos', 'ciudadanos_global', 'ciudadanos'))) {
					evento_chat('<b>[VOTACIÓN] <a href="/votacion/'.$r['ID'].'">'.$r['pregunta'].'</a></b> <span style="color:grey;">('.duracion($r['time_expire']).')</span>');
				}
			}
		}
		evento_log('Votación: iniciada <a href="/votacion/'.$_GET['ref_ID'].'">#'.$_GET['ref_ID'].'</a>');

	} elseif (($_GET['b'] == 'votar') AND (is_numeric($_POST['ref_ID']))) { 

			// Extrae configuracion de la votación
			$result = sql("SELECT * FROM votacion WHERE ID = '".$_POST['ref_ID']."' LIMIT 1");
			while($r = r($result)){ $tipo = $r['tipo']; $pregunta = $r['pregunta']; $estado = $r['estado']; $pais = $r['pais']; $acceso_votar = $r['acceso_votar']; $acceso_cfg_votar = $r['acceso_cfg_votar']; $acceso_ver = $r['acceso_ver']; $acceso_cfg_ver = $r['acceso_cfg_ver']; $num = $r['num']; $votos_expire = $r['votos_expire']; $tipo_voto = $r['tipo_voto']; $num_censo = $r['num_censo']; $num++; }

			// Verifica acceso y estado de votacion
			if (($estado == 'ok') AND (in_array($tipo, $votaciones_tipo)) AND (nucleo_acceso($acceso_votar,$acceso_cfg_votar)) AND (nucleo_acceso($acceso_ver, $acceso_cfg_ver))) {
				
				// Extracción y verificación contra inyección de votos malformados
				switch ($tipo_voto) {
					case '3puntos': case '5puntos': case '8puntos': 
						for ($i=substr($tipo_voto, 0, 1);$i>0;--$i) {
							$el_voto = $_POST['voto_'.$i];
							$votos_array[] = (is_numeric($el_voto)&&!$votos_votados[$el_voto]?$el_voto:0);
							$votos_votados[$el_voto] = true; 
						}
						$_POST['voto'] = implode(' ', array_reverse($votos_array));
						break;

					case 'multiple': 
						for ($i=0;$i<100;$i++) { if (is_numeric($_POST['voto_'.$i])) { $votos_array[] = $_POST['voto_'.$i]; } }
						$_POST['voto'] = implode(' ', $votos_array);
						break;
				}
	
				$_POST['mensaje'] = str_replace('"', "&quot;", ucfirst(trim(strip_tags($_POST['mensaje']))));
				$_POST['validez'] = ($_POST['validez']=='true'?'true':'false');

				// Comprueba si ya ha votado o no
				$ha_votado = false;
				$result = sql("SELECT ID FROM votacion_votos WHERE ref_ID = '".$_POST['ref_ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
				while($r = r($result)){ $ha_votado = true; }

				if ($ha_votado) {	// MODIFICAR VOTO
					sql("UPDATE votacion_votos SET voto = '".$_POST['voto']."', validez = '".$_POST['validez']."', mensaje = '".$_POST['mensaje']."' WHERE ref_ID = '".$_POST['ref_ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
				
				} else {			// INSERTAR VOTO
					
					sql("UPDATE votacion SET num = num + 1 WHERE ID = '".$_POST['ref_ID']."' LIMIT 1");

					$comprobante = sha1(DOMAIN.'-'.$_POST['ref_ID'].'-'.time().'-'.microtime().'-'.$_POST['voto'].'-'.mt_rand(1000,99999999999999999999));
					sql("INSERT INTO votacion_votos (user_ID, ref_ID, time, voto, validez, autentificado, mensaje, comprobante) VALUES ('".$pol['user_ID']."', '".$_POST['ref_ID']."', '".$date."', '".$_POST['voto']."', '".$_POST['validez']."', '".($_SESSION['pol']['dnie']=='true'?'true':'false')."', '".$_POST['mensaje']."', '".$comprobante."')");
					unset($comprobante);
					
					if (in_array($acceso_ver, array('anonimos', 'ciudadanos_global', 'ciudadanos'))) {
						evento_chat('<b>['.strtoupper($tipo).']</b> <a href="/votacion/'.$_POST['ref_ID'].'">'.$pregunta.'</a> <span style="color:grey;">(<b>'.num($num).'</b> votos'.($votos_expire>0?' de '.$votos_expire:'').($tipo=='elecciones'&&is_numeric($num_censo)?' '.num(($num*100)/$num_censo, 2).'%':'').', '.$pol['nick'].($_SESSION['pol']['dnie']=='true'?', <b>autentificado</b>':'').')</span>', '0', '', false, 'e', $pais);
					}
				}
				unset($_POST['voto'], $_POST['mensaje'], $_POST['validez']);
			}
			redirect(vp_url('/votacion/'.$_POST['ref_ID'], $pais));

	} elseif (($_GET['b'] == 'eliminar') AND (is_numeric($_GET['ID']))) { 
		$result = sql("SELECT ID, user_ID, estado, tipo FROM votacion WHERE estado = 'borrador' AND ID = '".$_GET['ID']."' AND pais = '".PAIS."' LIMIT 1");
		while($r = r($result)) {
			if (($r['user_ID'] == $pol['user_ID']) OR (nucleo_acceso($vp['acceso'][$r['tipo']]))) {
				sql("DELETE FROM votacion WHERE ID = '".$r['ID']."' LIMIT 1");
				sql("DELETE FROM votacion_votos WHERE ref_ID = '".$r['ID']."'");
			}
		}

	} elseif (($_GET['b'] == 'finalizar') AND (is_numeric($_GET['ID']))) { 
		$result = sql("SELECT ID, user_ID, estado, tipo FROM votacion WHERE estado != 'end' AND ID = '".$_GET['ID']."' AND pais = '".PAIS."' LIMIT 1");
		while($r = r($result)) {
			if (($r['user_ID'] == $pol['user_ID']) OR (($r['estado'] == 'borrador') AND (nucleo_acceso($vp['acceso'][$r['tipo']])))) {
				sql("UPDATE votacion SET estado = 'borrador', num = '0' WHERE ID = '".$r['ID']."' LIMIT 1");
				sql("DELETE FROM votacion_votos WHERE ref_ID = '".$r['ID']."'");
			}
		}
		evento_log('Votación: cancelada <a href="/votacion/'.$_GET['ID'].'">#'.$_GET['ID'].'</a>');

	} elseif (($_GET['b'] == 'enviar_comprobante') AND ($_GET['comprobante'])) {
		$votacion_ID = explodear('-', $_GET['comprobante'], 0);
		$asunto = 'Comprobante de voto: votación '.$votacion_ID;
		$mensaje = 'Hola Ciudadano,<br /><br />Este email es para guardar tu comprobante de voto. Te permitirá comprobar el sentido de tu voto cuando la votacion finaliza y así aportar verificabilidad a la votación.<br /><br /><blockquote>Comprobante: <b>'.$_GET['comprobante'].'</b><br />Comprobar: http://'.HOST.'/votacion/'.$votacion_ID.'/verificacion#'.$_GET['comprobante'].'<br />Votación: http://'.HOST.'/votacion/'.$votacion_ID.'</blockquote><br /><br />No debes entregar a nadie esta información, de lo contrario podrían saber qué has votado.<br /><br />Atentamente.<br /><br /><br />VirtualPol - http://'.HOST;
		enviar_email($pol['user_ID'], $asunto, $mensaje); 
		redirect(vp_url('/votacion/'.$votacion_ID));

	} elseif (($_GET['b'] == 'argumento') AND (is_numeric($_POST['ref_ID'])) AND (strlen($_POST['texto']) > 1)) {
		$result = sql("SELECT * FROM votacion WHERE estado != 'end' AND ID = '".$_POST['ref_ID']."' LIMIT 1");
		while($r = r($result)) {
			$_POST['sentido'] = substr(strip_tags($_POST['sentido']), 0, 40);
			if (nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])) {
				sql("INSERT INTO votacion_argumentos (ref_ID, user_ID, time, sentido, texto) VALUES ('".$r['ID']."', '".$pol['user_ID']."', '".tiempo()."', '".$_POST['sentido']."', '".ucfirst(trim(substr(strip_tags($_POST['texto']), 0, 180)))."')");
				if (in_array($r['acceso_ver'], array('anonimos', 'ciudadanos_global', 'ciudadanos'))) {
					evento_chat('<b>[#]</b> <a href="/votacion/'.$_POST['ref_ID'].'#argumentos">Argumento añadido en votación</a>'.($_POST['sentido']?' <span class="gris">('.$_POST['sentido'].')</span>':''), '0', '', true, 'e'); 
				}
			}
			redirect(vp_url('/votacion/'.$r['ID']));
		}
	} elseif (($_GET['b'] == 'argumento-eliminar') AND (is_numeric($_GET['ID']))) {
		sql("DELETE FROM votacion_argumentos WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		redirect(vp_url('/votacion/'.$_GET['ref_ID']));
	}

	// actualizar info en theme
	actualizar('votaciones');

	$refer_url = 'votacion';
	break;



case 'foro':
	// añadir, editar
	if ((($_GET['b'] == 'reply') OR ($_GET['b'] == 'hilo')) AND (strlen($_POST['text']) > 1) AND ($_POST['subforo'])) {
		if ($_POST['subforo'] == -1) { $acceso['escribir_msg'] = true; } else { 
			$acceso = false;
			$result = sql("SELECT acceso_leer, acceso_escribir, acceso_cfg_escribir, acceso_escribir_msg, acceso_cfg_escribir_msg FROM ".SQL."foros WHERE ID = '".$_POST['subforo']."' LIMIT 1");
			while($r = r($result)) { 
				$acceso_leer = $r['acceso_leer']; 
				$acceso['escribir'] = nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir']); 
				$acceso['escribir_msg'] = nucleo_acceso($r['acceso_escribir_msg'], $r['acceso_cfg_escribir_msg']);
			}
		}
		$text = gen_text(trim($_POST['text']), 'plain');

		if (($_GET['b'] == 'hilo') AND ($_POST['title']) AND ($acceso['escribir'])) {
			$title = strip_tags($_POST['title']);
			$url = gen_url($title);
			
			$exito = sql("INSERT INTO ".SQL."foros_hilos (sub_ID, url, user_ID, title, time, time_last, text, cargo) VALUES ('".$_POST['subforo']."', '".$url."', '".$pol['user_ID']."', '".$title."', '".$date."', '".$date."', '".$text."', '".$_POST['encalidad']."')");
			if (!$exito) {
				if (strlen($url) > 69) { $url = substr($url, 0, 69); }
				$url = $url.'-'.date('dmyHi');
				sql("INSERT INTO ".SQL."foros_hilos (sub_ID, url, user_ID, title, time, time_last, text, cargo) VALUES ('".$_POST['subforo']."', '".$url."', '".$pol['user_ID']."', '".$title."', '".$date."', '".$date."', '".$text."', '".$_POST['encalidad']."')");
			}

			if (in_array($acceso_leer, array('anonimos', 'ciudadanos', 'ciudadanos_global'))) {
				evento_chat('<b>[FORO]</b> <a href="/'.$_POST['return_url'] . $url.'/"><b>'.$title.'</b></a> <span style="color:grey;">('.$pol['nick'].')</span>');
			}

		} elseif (($_GET['b'] == 'reply') AND ($acceso['escribir_msg'])) {
			if ($_POST['hilo'] != -1) {
				sql("UPDATE ".SQL."foros_hilos SET time_last = '".$date."' WHERE ID = '".$_POST['hilo']."' LIMIT 1");
				$result = sql("SELECT title, num FROM ".SQL."foros_hilos WHERE ID = '".$_POST['hilo']."' LIMIT 1");
				while($r = r($result)) { $title = $r['title']; }
				if (in_array($acceso_leer, array('anonimos', 'ciudadanos', 'ciudadanos_global'))) {
					evento_chat('<b>[FORO]</b> <a href="/'.$_POST['return_url'].'">'.$title.'</a> <span style="color:grey;">('.$pol['nick'].')</span>');
				}
			}
			sql("INSERT INTO ".SQL."foros_msg (hilo_ID, user_ID, time, text, cargo) VALUES ('".$_POST['hilo']."', '".$pol['user_ID']."', '".$date."', '".$text."', '".$_POST['encalidad']."')");
		}
		if ($_POST['hilo']) {
			$msg_num = 0;
			$result = sql("SELECT COUNT(*) AS num FROM ".SQL."foros_msg WHERE hilo_ID = '".$_POST['hilo']."' AND estado = 'ok'");
			while($r = r($result)) { $msg_num = $r['num']; }
			sql("UPDATE ".SQL."foros_hilos SET num = '".$msg_num."' WHERE ID = '".$_POST['hilo']."' LIMIT 1");
		}
		
		// Busca tags @nick
		preg_match_all("/(>|\s|^)@([a-z0-9_]{2,20})/i", $text, $nicks, PREG_SET_ORDER);
		$nombrados = array();
		foreach ($nicks AS $el_nick) { $nombrados[] = $el_nick[2]; }
		// Envia notificacion a los nicks que existen, sin duplicar, maximo 25
		$result = sql("SELECT ID FROM users WHERE pais = '".PAIS."' AND estado = 'ciudadano' AND nick IN ('".implode("','", $nombrados)."') LIMIT 25");
		while($r = r($result)) { notificacion($r['ID'], $pol['nick'].' te ha mencionado en un '.($_POST['hilo']?'hilo':'mensaje').' del foro', $_POST['return_url']); }
		
		$refer_url = $_POST['return_url'];
	
	
	} elseif (($_GET['b'] == 'borrar') AND ($_GET['ID']) AND ($_GET['c']) AND (nucleo_acceso($vp['acceso']['foro_borrar']))) {
		$result = sql("SELECT user_ID FROM ".SQL."foros_".($_GET['c']=='hilo'?'hilo':'msg')." WHERE ID = '".$_GET['ID']."' LIMIT 1");
		while($r = r($result)){ $el_user_ID = $r['user_ID']; }
		if ($_GET['c'] == 'hilo') {
			sql("UPDATE ".SQL."foros_hilos SET estado = 'borrado', time_last = '".$date."' WHERE ID = '".$_GET['ID']."' AND estado = 'ok' LIMIT 1");
		} elseif ($_GET['c'] == 'mensaje') {
			sql("UPDATE ".SQL."foros_msg SET estado = 'borrado', time2 = '".$date."' WHERE ID = '".$_GET['ID']."' AND estado = 'ok' LIMIT 1");
		}
		evento_log('Foro <em>'.strtolower($_GET['c']).'</em> enviado a la papelera por moderación #'.$_GET['ID']);
		$refer_url = 'foro/papelera';

	} elseif (($_GET['b'] == 'restaurar') AND ($_GET['ID']) AND ($_GET['c']) AND (nucleo_acceso($vp['acceso']['foro_borrar']))) {
		if ($_GET['c'] == 'hilo') {
			sql("UPDATE ".SQL."foros_hilos SET estado = 'ok' WHERE ID = '".$_GET['ID']."' AND estado = 'borrado' LIMIT 1");
		} elseif ($_GET['c'] == 'mensaje') {
			sql("UPDATE ".SQL."foros_msg SET estado = 'ok', time2 = '0000-00-00 00:00:00' WHERE ID = '".$_GET['ID']."' AND estado = 'borrado' LIMIT 1");
		}
		evento_log('Foro '.$_GET['c'].' restaurado desde la papelera por moderación #'.$_GET['ID']);
		$refer_url = 'foro/papelera';


	} elseif (($_GET['b'] == 'eliminarhilo') AND ($_GET['ID'])) {
		$result = sql("SELECT ID FROM ".SQL."foros_hilos WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
		while($r = r($result)){
			sql("DELETE FROM ".SQL."foros_hilos WHERE ID = '".$r['ID']."' LIMIT 1");
			sql("DELETE FROM ".SQL."foros_msg WHERE hilo_ID = '".$r['ID']."'");
		}
		evento_log('Foro hilo eliminado #'.$_GET['ID']);
		$refer_url = 'foro';

	} elseif (($_GET['b'] == 'eliminarreply') AND ($_GET['hilo_ID']) AND ($_GET['ID'])) {
		$result = mysql_unbuffered_query("SELECT ID FROM ".SQL."foros_msg WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' AND time > '".date('Y-m-d H:i:s', time() - 3600)."' LIMIT 1");
		while($r = r($result)){ $es_ok = true; }
		if ($es_ok) {
			sql("DELETE FROM ".SQL."foros_msg WHERE ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");
			sql("UPDATE ".SQL."foros_hilos SET num = num-1 WHERE ID = '".$_GET['hilo_ID']."' LIMIT 1");
			evento_log('Foro mensaje eliminado #'.$_GET['ID']);
		}
		if ($_GET['hilo_ID'] == '-1') { $refer_url = 'notas'; } else { $refer_url = 'foro'; }

	} elseif (($_GET['b'] == 'editar') AND ($_POST['text']) AND ($_POST['subforo'])) {
		$text = gen_text($_POST['text'], 'plain');

		if ($_POST['hilo']) { //msg
			$result = sql("SELECT f.url foro, h.url hilo, m.ID mensaje FROM ".SQL."foros f, ".SQL."foros_hilos h, ".SQL."foros_msg m where f.ID = h.sub_ID and m.hilo_ID = h.ID and m.ID='".$_POST['hilo']."'");
			$r =r($result);

			sql("UPDATE ".SQL."foros_msg SET text = '".$text."' WHERE ID = '".$_POST['hilo']."' AND estado = 'ok' AND user_ID = '".$pol['user_ID']."' AND time > '".date('Y-m-d H:i:s', time() - 3600)."' LIMIT 1");
			evento_log('Foro mensaje editado <a href="/foro/'.$r['foro'].'/'.$r['hilo'].'#m-'.$r['mensaje'].'">#'.$_POST['hilo'].'</a>');
		} else { //hilo
			if (strlen($_POST['title']) >= 4) {
				$result = sql("SELECT f.url foro, h.url hilo FROM ".SQL."foros f, ".SQL."foros_hilos h where f.ID = h.sub_ID and  h.ID='".$_POST['subforo']."'");
				$r =r($result);
	
				$title = strip_tags($_POST['title']);
				sql("UPDATE ".SQL."foros_hilos SET text = '".$text."', title = '".$title."'".($_POST['sub_ID'] > 0?", sub_ID = '".$_POST['sub_ID']."'":'')." WHERE ID = '".$_POST['subforo']."' AND estado = 'ok' AND (user_ID = '".$pol['user_ID']."' OR 'true' = '".(nucleo_acceso($vp['acceso']['foro_borrar'])?'true':'false')."') LIMIT 1");
				evento_log('Foro hilo editado <a href="/foro/'.$r['foro'].'/'.$r['hilo'].'">#'.$_POST['hilo'].'</a>');
			}
		}


		
		$refer_url = '/foro/r/'.$_POST['subforo'];
	}
	break;


case 'kick':

	if (($_GET['b'] == 'quitar') AND ($_GET['ID'])) {

		$es_policiaexpulsador = false;
		$result = mysql_unbuffered_query("SELECT ID, user_ID, autor FROM kicks WHERE pais = '".PAIS."' AND ID = '".$_GET['ID']."' LIMIT 1");
		while($r = r($result)){ 
			if ($pol['user_ID'] == $r['autor']) {
				$es_policiaexpulsador = true;
			}
			$kickeado_id = $r['user_ID'];
			$kick_id = $r['ID']; 
		}
	
		if (($es_policiaexpulsador) OR (nucleo_acceso($vp['acceso']['kick_quitar']))) {
			sql("UPDATE kicks SET estado = 'cancelado' WHERE pais = '".PAIS."' AND estado = 'activo' AND ID = '".$_GET['ID']."' LIMIT 1"); 
			if (mysql_affected_rows()==1) {
				$result = sql("SELECT nick FROM users WHERE ID = '".$kickeado_id."' LIMIT 1");
				while($r = r($result)){ $kickeado_nick = $r['nick'];}
				
				evento_log('Kick a @'.$kickeado_nick.' cancelado');
				
				evento_chat('<span style="color:red;"><img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /> <b>[KICK]</b> El kick a <b>'.$kickeado_nick.'</b> ha sido cancelado por <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" /> <b>'.$pol['nick'].'</b>.</span>');
			}
		}
		$refer_url = 'control/kick';

	} elseif ($_POST['nick']) {
		if ((substr($_POST['nick'], 0, 3) == 'ip-') AND (is_numeric(substr($_POST['nick'], 3)))) {
			// kick a anonimo
			$kick_cargo = 98; 
			$kick_user_ID = 0; 
			$kick_IP = substr($_POST['nick'], 3); 
			$result = sql("SELECT nick FROM chats_msg WHERE IP = ".$kick_IP." ORDER BY msg_ID DESC LIMIT 1");
			while($r = r($result)){ $kick_nick = $r['nick']; }
			$_POST['razon'] = '['.$kick_nick.'] '.$_POST['razon'];
			$kick_pais = PAIS;
			$result = sql("SELECT ID FROM kicks WHERE pais = '".PAIS."' AND IP = ".$kick_IP." AND estado = 'activo' LIMIT 1");
			while($r = r($result)){ $user_kicked = true; }
			$el_userid = -1;
		} else {
			$result = sql("SELECT ID, nick, IP, cargo, pais FROM users WHERE nick = '".$_POST['nick']."' LIMIT 1");
			while($r = r($result)){ $kick_cargo = $r['cargo']; $kick_user_ID = $r['ID']; $kick_nick = $r['nick']; $kick_IP = '\''.$r['IP'].'\''; $kick_pais = $r['pais']; }
			$result = sql("SELECT ID FROM kicks WHERE pais = '".PAIS."' AND user_ID = '".$kick_user_ID."' AND estado = 'activo' LIMIT 1");
			while($r = r($result)){ $user_kicked = true; }
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
			sql("INSERT INTO kicks (pais, user_ID, autor, expire, razon, estado, tiempo, IP, cargo, motivo) VALUES ('".PAIS."', '".$el_userid."', ".$pol['user_ID'].", '".$expire."', '".$_POST['razon']."', 'activo', '".$_POST['expire']."', ".$kick_IP.", '".$pol['cargo']."', '".$_POST['motivo']."')");

			evento_chat('<span style="color:red;"><img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /> <b>[KICK] '.$kick_nick.'</b> ha sido kickeado por <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" /> <b>'.$pol['nick'].'</b>, durante <b>'.duracion($_POST['expire']).'</b>. Razon: <em>'.$_POST['razon'].'</em> (<a href="/control/kick/">Ver kicks</a>)</span>');
			evento_log('Kick a @'.$kick_nick.', tiempo('.$_POST['expire'].'), razon: '.$_POST['razon']);
		}
		$refer_url = 'control/kick';
	}
	break;



case 'mensaje-leido':
	if ($_GET['ID'] == 'all') {
		sql("UPDATE mensajes SET leido = '1' WHERE recibe_ID = '".$pol['user_ID']."'");
	} elseif ($_GET['ID']) {
		sql("UPDATE mensajes SET leido = '1' WHERE ID = '".$_GET['ID']."' AND recibe_ID = '".$pol['user_ID']."' LIMIT 1");
	}
	sql("UPDATE notificaciones SET visto = 'true' WHERE user_ID = '".$pol['user_ID']."' AND visto = 'false' AND texto LIKE 'Mensaje %'");
	$refer_url = 'msg';
	break;

case 'borrar-mensaje':
	if (is_numeric($_GET['ID'])) {
		sql("DELETE FROM mensajes WHERE ID = '".$_GET['ID']."' AND recibe_ID = '".$pol['user_ID']."' LIMIT 1");
		sql("UPDATE notificaciones SET visto = 'true' WHERE user_ID = '".$pol['user_ID']."' AND visto = 'false' AND texto LIKE 'Mensaje %'");
		$refer_url = 'msg';
	}
	break;


case 'enviar-mensaje':

	if ((!$_GET['b']) AND ($_POST['text']) AND ($_POST['para'])) {
		$text = gen_text($_POST['text'], 'plain');
		if (($_POST['para'] == 'ciudadano') AND ($_POST['nick'])) {
			$envio_urgente = 0;

			$mp_num = 1;
			$enviar_nicks = array();
			$nicks_array = explode(' ', str_replace(',', '', $_POST['nick']));
			foreach ($nicks_array AS $el_nick) {
				if (($el_nick) AND (($mp_num <= MP_MAX) OR (nucleo_acceso($vp['acceso']['control_gobierno'])))) {
					$enviar_nicks[] = $el_nick;
					$mp_num++;
				}
			}
			
			$result = sql("SELECT ID, pais FROM users WHERE nick IN ('".implode("','", $enviar_nicks)."') AND estado != 'expulsado'");
			while($r = r($result)){ 
				sql("INSERT INTO mensajes (envia_ID, recibe_ID, time, text, leido, cargo) VALUES ('".$pol['user_ID']."', '".$r['ID']."', '".$date."', '".$text."', '0', '".$_POST['calidad']."')");
				
				// MENSAJE URGENTE
				if (($_POST['urgente'] == '1') AND ($pol['pols'] >= $pol['config']['pols_mensajeurgente'])) { 
					$asunto = '[VirtualPol] Tienes un mensaje urgente de '.$pol['nick'];
					$mensaje = 'Hola Ciudadano,<br /><br />Has recibido un mensaje urgente enviado por el Ciudadano: '.$pol['nick'].'.<br /><br />Mensaje de '.$pol['nick'].':<hr />'.$text.'<hr /><br /><br />Este mensaje es automatico. Para responder a '.$pol['nick'].' entra aqui:<br /><br />http://'.HOST.'/msg/'.$pol['nick'].'<br /><br /><br />VirtualPol<br />http://'.HOST;
					enviar_email($r['ID'], $asunto, $mensaje); 
					$envio_urgente++;
				}
				evento_chat('<b>[MP]</b> <a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/msg">Nuevo mensaje privado</a> <span style="color:grey;">('.$pol['nick'].')</span>', $r['ID'], -1, false, 'p', $r['pais']);
				notificacion($r['ID'], 'Mensaje privado de '.$pol['nick'], '/msg');
				$refer_url = 'msg';
			}

			if ($envio_urgente > 0) {
				pols_transferir(round($pol['config']['pols_mensajeurgente']*$envio_urgente), $pol['user_ID'], '-1', 'Envio mensaje urgente'.($envio_urgente>1?' x'.$envio_urgente:''));
			}


		} elseif (($_POST['para'] == 'cargo') AND ($_POST['cargo_ID'] == 'SC')) {

			$sc = get_supervisores_del_censo();

			foreach ($sc AS $user_ID => $nick) {
				if ($user_ID != $pol['user_ID']) {
					sql("INSERT INTO mensajes (envia_ID, recibe_ID, time, text, leido, cargo, recibe_masivo) VALUES ('".$pol['user_ID']."', '".$user_ID."', '".$date."', '<b>Mensaje multiple: Supervisor del Censo</b><br />".$text."', '0', '".$_POST['calidad']."', 'SC')");
					evento_chat('<b>[MP]</b> <a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/msg">Nuevo mensaje privado</a> <span style="color:grey;">(multiple)</span>', $user_ID, -1, false, 'p');
					notificacion($user_ID, 'Mensaje de SC de '.$pol['nick'], '/msg');
					$refer_url = 'msg';
				}
			}
		} elseif (($_POST['para'] == 'cargo') AND ($_POST['cargo_ID'])) {

			$result = sql("SELECT nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$_POST['cargo_ID']."' LIMIT 1");
			while($r = r($result)){ $cargo_nombre = $r['nombre']; }

			if ($_POST['cargo_ID'] == '55') {
				$result = sql("SELECT user_ID FROM cargos_users WHERE pais = '".PAIS."' AND cargo = 'true' AND cargo_ID IN (55, 56, 57) LIMIT 1000");
			} else {
				$result = sql("SELECT user_ID FROM cargos_users WHERE pais = '".PAIS."' AND cargo = 'true' AND cargo_ID = '".$_POST['cargo_ID']."' LIMIT 1000");
			}
			while($r = r($result)){ 
				if (($r['user_ID'] != $pol['user_ID']) AND ($r['user_ID'] != 0)) {
					sql("INSERT INTO mensajes (envia_ID, recibe_ID, time, text, leido, cargo, recibe_masivo) VALUES ('".$pol['user_ID']."', '".$r['user_ID']."', '".$date."', '<b>Mensaje multiple: ".$cargo_nombre."</b><br />".$text."', '0', '".$_POST['calidad']."', '".$_POST['cargo_ID']."')");
					evento_chat('<b>Nuevo mensaje privado</b> (<a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/msg"><b>Leer!</b></a>)', $r['user_ID'], -1, false, 'p');
					notificacion($r['user_ID'], 'Mensaje privado de '.$pol['nick'], '/msg');
					$refer_url = 'msg';
				}
			}
		} elseif (($_POST['para'] == 'grupos') AND ($_POST['grupo_ID'])) {

			$result = sql("SELECT nombre FROM grupos WHERE grupo_ID = '".$_POST['grupo_ID']."' LIMIT 1");
			while($r = r($result)){ $grupo_nombre = $r['nombre']; }

			$result = sql("SELECT ID AS user_ID, grupos FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND grupos != '' AND grupos LIKE '%".$_POST['grupo_ID']."%' LIMIT 1000");
			while($r = r($result)){ 
				if (($r['user_ID'] != $pol['user_ID']) AND (in_array($_POST['grupo_ID'], explode(' ', $r['grupos'])))) {
					
					sql("INSERT INTO mensajes (envia_ID, recibe_ID, time, text, leido, cargo, recibe_masivo) VALUES ('".$pol['user_ID']."', '".$r['user_ID']."', '".$date."', '<b>Mensaje multiple: grupo ".$grupo_nombre."</b><br />".$text."', '0', '".$_POST['calidad']."', '".$_POST['cargo_ID']."')");
					
					evento_chat('<b>Nuevo mensaje privado</b> (<a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/msg"><b>Leer!</b></a>)', $r['user_ID'], -1, false, 'p');
					
					notificacion($r['user_ID'], 'Mensaje privado del grupo '.$grupo_nombre, '/msg');
				}
			}
			$refer_url = 'msg';
		} elseif (($_POST['para'] == 'todos') AND ($pol['pols'] >= $pol['config']['pols_mensajetodos'])) {
			// MENSAJE GLOBAL
			$text = '<b>Mensaje Global:</b> ('.pols($pol['config']['pols_mensajetodos']).' '.MONEDA.')<hr />'.$text;
			pols_transferir($pol['config']['pols_mensajetodos'], $pol['user_ID'], '-1', 'Mensaje Global');
			$result = sql("SELECT ID FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."'");
			while($r = r($result)){ 
				sql("INSERT INTO mensajes (envia_ID, recibe_ID, time, text, leido, cargo) VALUES ('".$pol['user_ID']."', '".$r['ID']."', '".$date."', '".$text."', '0', '".$_POST['calidad']."')");
				notificacion($r['ID'], 'Mensaje privado global', '/msg');
				$refer_url = 'msg';
			}
		}
	}
	break;


case 'partido-lista':
	$b = $_GET['b'];
	$ID_partido = $_GET['ID'];

	if (($b) AND ($ID_partido) AND ($pol['config']['elecciones_estado'] != 'elecciones')) {

		$result = sql("SELECT ID_presidente, siglas FROM partidos WHERE pais = '".PAIS."' AND ID = '".$ID_partido."' AND ID_presidente = '".$pol['user_ID']."' LIMIT 1");
		while($r = r($result)){
			$siglas = $r['siglas'];
			if ($b == 'edit') {
				sql("UPDATE partidos SET descripcion = '".gen_text($_POST['text'])."' WHERE pais = '".PAIS."' AND ID = '".$ID_partido."' LIMIT 1");
			} elseif (($b == 'add') AND ($_POST['user_ID'])) {
				sql("INSERT INTO partidos_listas (pais, ID_partido, user_ID) VALUES ('".PAIS."', '".$ID_partido."', '".$_POST['user_ID']."')");
			} elseif (($b == 'del') AND ($_POST['user_ID'])) {
				sql("DELETE FROM partidos_listas WHERE pais = '".PAIS."' AND user_ID = '".$_POST['user_ID']."' AND ID_partido = '".$ID_partido."' LIMIT 1");
			} elseif (($b == 'ceder-presidencia') AND ($_POST['user_ID'])) {
				sql("UPDATE partidos SET ID_presidente = '".$_POST['user_ID']."' WHERE pais = '".PAIS."' AND ID = '".$ID_partido."' LIMIT 1");
			} elseif (($b == 'del-afiliado') AND ($_POST['user_ID'])) {
				sql("UPDATE users SET partido_afiliado = '0' WHERE partido_afiliado = '".$ID_partido."' AND ID = '".$_POST['user_ID']."' LIMIT 1");
			}

			$refer_url = 'partidos/'.strtolower($siglas).'/editar';
		}
	}
	break;





case 'cargo':
	$b = $_GET['b'];
	$cargo_ID = $_GET['ID'];

	if (($_GET['b'] == 'dimitir') AND (is_numeric($_GET['ID'])) AND (nucleo_acceso('cargo', $_GET['ID']))) {

		cargo_del($_GET['ID'], $pol['user_ID'], false);

		$result = sql("SELECT nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['ID']."' LIMIT 1");
		while($r = r($result)){ $cargo_nom = $r['nombre']; }
		
		evento_chat('<b>[CARGO] '.crear_link($pol['nick']).' dimite</b> del cargo <img src="'.IMG.'cargos/'.$_GET['ID'].'.gif" />'.$cargo_nom);

		sql("UPDATE cargos_users SET cargo = 'false', aprobado = 'no' WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['ID']."' AND user_ID = '".$pol['user_ID']."' LIMIT 1");

		// Asigna al siguiente en la cadena de sucesión si el cargo es electo
		$result = sql("SELECT cargo_ID FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['ID']."' AND elecciones IS NOT NULL LIMIT 1");
		while($r = r($result)){
			// El cargo es electo.
			$result2 = sql("SELECT ejecutar FROM votacion WHERE pais = '".PAIS."' AND tipo = 'elecciones' AND estado = 'end' AND cargo_ID = '".$_GET['ID']."' ORDER BY time DESC LIMIT 1");
			while($r2 = r($result2)){
				// Ultimas elecciones legitimas.
				sql("UPDATE users SET temp = NULL");
				foreach (explode(':', explodear('|', $r2['ejecutar'], 3)) AS $data) {
					sql("UPDATE users SET temp = ".explodear('.', $data, 1)." WHERE pais = '".PAIS."' AND nick = '".explodear('.', $data, 0)."' LIMIT 1");
				}
				
				$asignado = false;
				$result3 = sql("SELECT ID,
(SELECT cargo FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['ID']."' AND user_ID = users.ID LIMIT 1) AS el_cargo,
(SELECT aprobado FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['ID']."' AND user_ID = users.ID LIMIT 1) AS el_aprobado
FROM users WHERE pais = '".PAIS."' AND estado = 'ciudadano' AND temp IS NOT NULL ORDER BY temp DESC, fecha_registro ASC");
				while($r3 = r($result3)){
					// Recorre la cadena de sucesión por orden de votos, para asignar el cargo al primero sin cargo ejercido y con examen aprobado (candidato)
					if (($asignado == false) AND ($r3['el_cargo'] == 'false') AND ($r3['el_aprobado'] == 'ok')) {
						cargo_add($_GET['ID'], $r3['ID'], true, true);
						$asignado = true;
					}
				}

			}
		}
		$refer_url = 'cargos';

		
	} elseif (($_GET['b'] == 'editar') AND (nucleo_acceso($vp['acceso']['control_cargos']))) {
		$result = sql("SELECT * FROM cargos WHERE pais = '".PAIS."' AND asigna > 0");
		while($r = r($result)){
			$_POST['nombre_'.$r['cargo_ID']] = strip_tags(trim(substr($_POST['nombre_'.$r['cargo_ID']], 0, 30)));
			
			if ((strlen($_POST['nombre_'.$r['cargo_ID']]) >= 3) AND (is_numeric($_POST['asigna_'.$r['cargo_ID']])) AND (is_numeric($_POST['nivel_'.$r['cargo_ID']])) AND (entre($_POST['nivel_'.$r['cargo_ID']], 1, 99))) {
				
				if ($_POST['editar_elecciones'] == 'true') {
					if (($_POST['autocargo_'.$r['cargo_ID']] != 'true') AND (isset($_POST['elecciones_'.$r['cargo_ID']])) AND ($_POST['elecciones_'.$r['cargo_ID']] != '') AND (entre($_POST['elecciones_cada_'.$r['cargo_ID']], 7, 90)) AND (entre($_POST['elecciones_durante_'.$r['cargo_ID']],1,30)) AND (entre($_POST['elecciones_electos_'.$r['cargo_ID']],1,100))) {
						$sql_set = ", elecciones = '".$_POST['elecciones_'.$r['cargo_ID']]."', elecciones_electos = '".$_POST['elecciones_electos_'.$r['cargo_ID']]."', elecciones_cada = '".$_POST['elecciones_cada_'.$r['cargo_ID']]."', elecciones_durante = '".$_POST['elecciones_durante_'.$r['cargo_ID']]."', elecciones_votan = '".$_POST['elecciones_votan_'.$r['cargo_ID']]."'";
					} else { 
						$sql_set = ", elecciones = NULL, elecciones_electos = NULL, elecciones_cada = NULL, elecciones_durante = NULL, elecciones_votan = NULL";
					}
				}
				
				sql("UPDATE cargos SET nombre = '".$_POST['nombre_'.$r['cargo_ID']]."', nombre_extra = '".strip_tags($_POST['nombre_extra_'.$r['cargo_ID']])."', asigna = '".$_POST['asigna_'.$r['cargo_ID']]."', nivel = '".$_POST['nivel_'.$r['cargo_ID']]."', autocargo = '".($_POST['autocargo_'.$r['cargo_ID']]?'true':'false')."'".$sql_set." WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' LIMIT 1");
				sql("UPDATE examenes SET titulo = '".$_POST['nombre_'.$r['cargo_ID']]."' WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' LIMIT 1");
			}
		}
		if ($_POST['editar_elecciones'] == 'true') { $refer_url = 'cargos/editar/elecciones'; } else { $refer_url = 'cargos/editar'; }


	} elseif (($_GET['b'] == 'eliminar') AND (nucleo_acceso($vp['acceso']['control_cargos'])) AND (is_numeric($_GET['cargo_ID']))) {
		
		$result = sql("SELECT *,
(SELECT COUNT(ID) FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = cargos.cargo_ID AND cargo = 'true') AS cargo_num
FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['cargo_ID']."' AND asigna > 0 LIMIT 1");
		while($r = r($result)){
			if ($r['cargo_num'] == 0) {
				sql("DELETE FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['cargo_ID']."' LIMIT 1");
				sql("DELETE FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['cargo_ID']."'");
				sql("DELETE FROM examenes WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['cargo_ID']."' LIMIT 1");
			}
		}
		evento_log('Cargo '.$_GET['cargo_ID'].' eliminado por '.$pol['user_ID']);
		$refer_url = 'cargos/editar';


	} elseif (($_GET['b'] == 'crear') AND (nucleo_acceso($vp['acceso']['control_cargos'])) AND (strlen($_POST['nombre']) >= 3) AND (strlen($_POST['nombre']) <= 30) AND (entre($_POST['nivel'], 1, 98)) AND (is_numeric($_POST['cargo_ID']))) {
		$_POST['nombre'] = strip_tags(trim(substr($_POST['nombre'], 0, 30)));
		sql("INSERT INTO cargos (cargo_ID, asigna, nombre, pais, nivel) VALUES ('".$_POST['cargo_ID']."', '".$_POST['asigna']."', '".$_POST['nombre']."', '".PAIS."', '".$_POST['nivel']."')");
		sql("INSERT INTO examenes (pais, titulo, time, cargo_ID, nota) VALUES ('".PAIS."', '".$_POST['nombre']."', '".$date."', '".$_POST['cargo_ID']."', '0')");
		$refer_url = 'cargos/editar';


	} elseif ((in_array($b, array('add', 'del'))) AND (is_numeric($cargo_ID))) {
		$result = sql("SELECT cargo_ID, asigna, nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$cargo_ID."' LIMIT 1");
		while($r = r($result)){
			if (nucleo_acceso('cargo', $r['asigna'])) { 
				$result2 = sql("SELECT nick, online, fecha_registro FROM users WHERE ID = '".$_POST['user_ID']."' AND pais = '".PAIS."' LIMIT 1");
				while($r2 = r($result2)){ $nick_asignado = $r2['nick']; $asignado['fecha_registro'] = $r2['fecha_registro']; $asignado['online'] = $r2['online']; }

				if ($nick_asignado) {
					if ($b == 'add') { cargo_add($cargo_ID, $_POST['user_ID']); } 
					elseif ($b == 'del') { cargo_del($cargo_ID, $_POST['user_ID']); }
				}
				$refer_url = 'cargos/'.$cargo_ID;
			}
		}
	}
	break;


case 'eliminar-partido':
	if (($pol['config']['elecciones_estado'] != 'elecciones')) {
		$result = sql("SELECT ID, siglas FROM partidos WHERE pais = '".PAIS."' AND ID_presidente = '".$pol['user_ID']."' LIMIT 1");
		while($r = r($result)){
			sql("DELETE FROM partidos WHERE pais = '".PAIS."' AND ID = '".$r['ID']."' LIMIT 1");
			sql("DELETE FROM partidos_listas WHERE pais = '".PAIS."' AND ID_partido = '".$r['ID']."' LIMIT 1");
			evento_log('Partido eliminado '.$r['siglas']);
		}
	}
	actualizar('contador_docs');
	$refer_url = 'partidos';
	break;




case 'restaurar-documento':
	$result = sql("SELECT ID, pad_ID, url, acceso_escribir, acceso_cfg_escribir FROM docs WHERE ID = '".$_GET['ID']."' LIMIT 1");
	while($r = r($result)){ 
		if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) {
			pad('delete', $r['pad_ID']);
		}
		$refer_url = 'doc/'.$r['url'].'/editar';
	}
	break;

case 'eliminar-documento':
	
	$result = sql("SELECT ID, pad_ID, acceso_escribir, acceso_cfg_escribir, url FROM docs WHERE url = '".$_GET['url']."' AND pais = '".PAIS."' LIMIT 1");
	while($r = r($result)){ 
		if ((nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) OR (nucleo_acceso($vp['acceso']['control_docs']))) {
			sql("UPDATE docs SET estado = 'del' WHERE ID = '".$r['ID']."' LIMIT 1");
			evento_log('Documento eliminado <a href="/doc/'.$r['url'].'">#'.$r['ID'].'</a>');
			pad('delete', $r['pad_ID']);
		}
		$refer_url = 'doc';
	}
	actualizar('contador_docs');
	break;


case 'editar-documento':
	if (($_POST['titulo']) AND ($_POST['cat'])) {
		$_POST['titulo'] = strip_tags($_POST['titulo']);

		$result = sql("SELECT ID, pad_ID, pais, url, title, acceso_leer, acceso_escribir, acceso_cfg_escribir FROM docs WHERE ID = '".$_POST['doc_ID']."' LIMIT 1");
		while($r = r($result)){ 

			$text = $_POST['html_doc'];
			
			// Prevent SSX basic
			$text = str_replace("<script", "nojs", $text);
			$text = str_replace("&lt;script", "nojs", $text);
			$text = str_replace("&lt;br /&gt;", "", $text);
			$text = str_replace("<br />", "", $text);
		

			if ((nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) OR (nucleo_acceso($vp['acceso']['control_docs']))) {
				sql("UPDATE docs SET cat_ID = '".$_POST['cat']."', text = '".$text."', title = '".$_POST['titulo']."', time_last = '".$date."', acceso_leer = '".$_POST['acceso_leer']."', acceso_escribir = '".$_POST['acceso_escribir']."', acceso_cfg_leer = '".$_POST['acceso_cfg_leer']."', acceso_cfg_escribir = '".$_POST['acceso_cfg_escribir']."', version = version + 1 WHERE ID = '".$r['ID']."' LIMIT 1");
			}
			if (in_array($r['acceso_leer'], array('anonimos', 'ciudadanos', 'ciudadanos_global'))) { evento_log('Documento editado: <a href="/doc/'.$r['url'].'">'.$r['title'].'</a>'); }

			publicar_documento($r['ID'], 'Documento editado: <a href="/doc/'.$r['url'].'">'.$r['title'].'</a>');

			
			redirect(vp_url('/doc/'.$r['url'].'/editar', $r['pais']));
		}
	}
	break;

case 'crear-documento':
	if ((entre(strlen($_POST['title']), 1, 80)) AND (isset($_POST['cat']))) {
		
		$url = gen_url($_POST['title']);

		$result = sql("SELECT ID FROM docs WHERE pais = '".PAIS."' AND url = '".$url."' LIMIT 1");
		while($r = r($result)) { $url .= '_'.time(); }

		sql("INSERT INTO docs 
(pais, url, title, text, time, time_last, estado, cat_ID, acceso_leer, acceso_escribir, acceso_cfg_leer, acceso_cfg_escribir) 
VALUES ('".PAIS."', '".$url."', '".$_POST['title']."', '', '".$date."', '".$date."', 'ok', '".$_POST['cat']."', 'privado', 'privado', '".strtolower($pol['nick'])."', '".strtolower($pol['nick'])."')");
		
/*		$result = sql("SELECT ID FROM docs WHERE pais = '".PAIS."' AND url = '".$url."' LIMIT 1");
		while($r = r($result)){ sql("UPDATE docs SET pad_ID = '".$r['ID'].".".rand(100000,999999)."' WHERE ID = '".$r['ID']."' LIMIT 1"); }
*/
		actualizar('contador_docs');
		evento_log('Documento creado: <a href="/doc/'.$url.'">'.$_POST['title'].'</a>');
		publicar_documento($r['ID'], 'Documento creado: <a href="/doc/'.$url.'">'.$_POST['title'].'</a>');
	}
	$refer_url = 'doc/'.$url.'/editar';
	break;


case 'afiliarse':
	if (($pol['config']['elecciones_estado'] != 'elecciones')) {
		sql("UPDATE users SET partido_afiliado = '".$_POST['partido']."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
		sql("DELETE FROM partidos_listas WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."'");
		evento_log('Afiliado a #'.$_POST['partido']);
	}
	$refer_url = 'perfil/editar';
	break;

case 'crear-partido':
	$_POST['siglas'] = strtoupper(preg_replace("/[^[a-z-]/i", "", $_POST['siglas']));

	$ya_es_presidente = false;
	$result = sql("SELECT ID FROM partidos WHERE pais = '".PAIS."' AND ID_presidente = '".$pol['user_ID']."'");
	while($r = r($result)){ $ya_es_presidente = true; }

	if (($pol['config']['elecciones_estado'] != 'elecciones') AND (strlen($_POST['siglas']) <= 12) AND (strlen($_POST['siglas']) >= 2) AND (nucleo_acceso($vp['acceso']['crear_partido'])) AND ($_POST['nombre']) AND ($ya_es_presidente == false)) {

		$_POST['descripcion'] = gen_text($_POST['descripcion']);

		sql("INSERT INTO partidos 
(pais, ID_presidente, fecha_creacion, siglas, nombre, descripcion, estado) 
VALUES ('".PAIS."', '".$pol['user_ID']."', '".$date."', '".$_POST['siglas']."', '".$_POST['nombre']."', '".$_POST['descripcion']."', 'ok')
");

		$result = sql("SELECT ID FROM partidos WHERE pais = '".PAIS."' AND siglas = '".$_POST['siglas']."' LIMIT 1");
		while($r = r($result)){ $partido_ID = $r['ID']; }
		evento_log('Partido creado '.$_POST['siglas']);
	}

	// actualizar info en theme
	$result = sql("SELECT COUNT(ID) AS num FROM partidos WHERE pais = '".PAIS."' AND estado = 'ok'");
	while($r = r($result)) {
		sql("UPDATE config SET valor = '".$r['num']."' WHERE pais = '".PAIS."' AND dato = 'info_partidos' LIMIT 1");
	}

	$refer_url = 'partidos';
	break;
}
}


if ($_GET['a'] == 'logout') {
	setcookie('teorizauser', '', time()-3600, '/', USERCOOKIE);
	setcookie('teorizapass', '', time()-3600, '/', USERCOOKIE);
	unset($_SESSION); session_destroy();
	redirect(REGISTRAR.'login.php?a=logout');
}


if (!isset($refer_url)) { $refer_url = '?error='.base64_encode(_('Acción no permitida o erronea').' ('.$_GET['a'].')'); }
redirect('//'.HOST.'/'.$refer_url);
?>
