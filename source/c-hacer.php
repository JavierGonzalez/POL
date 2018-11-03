<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


// LOAD CONFIG
$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }

$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); // 30 dias

function print_hacer($hecho=false, $titulo_si='', $titulo_no='', $link='', $descripcion='') {
	return '<tr>
<td valign="top">'.($hecho?'<img src="'.IMG.'ico/ok.png" width="32" height="32" style="margin-top:4px;" />':'<img src="'.IMG.'ico/no.png" width="32" height="32" style="margin-top:4px;" />').'</td>
<td><b style="'.($hecho?'color:blue;':'color:red;').'">'.($hecho?$titulo_si:$titulo_no).'</b> &nbsp; '.$link.'<br />
<span class="gris">'.$descripcion.'</span></td>
</tr>';
}



$txt .= '<h2>¿Qué hacer en '.$pol['config']['pais_des'].'?</h2>


<fieldset class="rich">'.(nucleo_acceso($vp['accesos']['control_gobierno'])?'<span style="float:right;"><a href="/control/gobierno">Editar</a></span>':'').$pol['config']['palabra_gob'].'</fieldset>

<table>';

$txt .= print_hacer(nucleo_acceso('autentificados'), 
'Estás autentificado correctamente', 
'No has autentificado tu usuario.', 
'<a href="'.SSL_URL.'dnie.php" target="_blank" style="font-size:19px;">Ver autentificación</a>', 
'Puedes identificarte de forma segura con <abbr title="DNI electronico y otros 30 certificados">DNIe</abbr>, contribuirás a reforzar la legitimidad de las votaciones y tu usuario nunca se eliminará. Es opcional.');


$fecha_24_antes = date('Y-m-d H:i:00', strtotime($pol['config']['elecciones_inicio']) - $pol['config']['elecciones_antiguedad']);

$result = sql("SELECT fecha_registro FROM users WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1");
while($r = r($result)){ $fecha_registro = $r['fecha_registro']; }

$hay_votaciones = 0;
if ($pol['config']['info_consultas'] > 0) {
	$result = sql("SELECT ID, pregunta, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, (SELECT user_ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS ha_votado FROM votacion WHERE estado = 'ok' AND pais = '".PAIS."'");
	while($r = r($result)) { 
		if ((!$r['ha_votado']) AND (nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])) AND (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) { 
			$hay_votaciones++;
			$votaciones_array[] = '<li><a href="/votacion/'.$r['ID'].'/"><b>'.$r['pregunta'].'</b></a></li>';
		}
	}
}

$txt .= print_hacer(($hay_votaciones==0?true:false), 
'Has votado en todas las votaciones en curso', 
'Hay votaciones en las que aún no has votado', 
'<a href="/votacion/next" target="_blank" style="font-size:19px;">Ver votaciones</a>', 
'Las votaciones (informativas o vinculantes) son el mecanismo democrático más habitual. Duran un tiempo determinado, configuración específica y puede haber varias simultáneas.');





$txt .= print_hacer(($_SESSION['pol']['cargos']!=''?true:false), 
'Estás ejerciendo '.count(explode(' ', $_SESSION['pol']['cargos'])).' cargos', 
'No estás ejerciendo ningún cargo', 
'<a href="/cargos" target="_blank" style="font-size:19px;">Ver cargos</a>', 
'Los cargos sirven para asignar taréas (con sus responsabilidades y privilegios). Un ejemplo de cargo es por ejemplo los Moderadores o Coordinadores. La asignación de cargos puede ser de 3 tipos: asignado por elecciones periódicas y automáticas, asignado manualmente y asignado al solicitar.');





$votos_confianza = 0;
$result = sql("SELECT COUNT(*) AS num FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."'");
while($r = r($result)) { $votos_confianza = $r['num']; }


$txt .= print_hacer(($votos_confianza>=VOTO_CONFIANZA_MAX?true:false), 
'Has repartido todos tus votos de confianza', 
'Te quedan '.(VOTO_CONFIANZA_MAX-$votos_confianza).' votos de confianza por repartir', 
'<a href="/info/censo/confianza" target="_blank" style="font-size:19px;">Votar confianza</a>', 
'El voto de confianza es una valoración (+1 -1) que los usuarios se reparten entre sí. No implica que te guste ese usuario, si no que confías en él. Tienes '.VOTO_CONFIANZA_MAX.' votos de confianza para repartir.');



$geo = false;
$result = sql("SELECT x, y FROM users WHERE ID = '".$pol['user_ID']."' AND x IS NOT NULL LIMIT 1");
while ($r = r($result)) { $geo = true; }

$txt .= print_hacer($geo, 
'Estás geolocalizado correctamente', 
'Aún no te has situado en el mapa de ciudadanos', 
'<a href="'.($geo?'/geolocalizacion':'/geolocalizacion/fijar').'" target="_blank" style="font-size:19px;">Ver mapa de ciudadanos</a>', 
'El mapa de ciudadanos permite comunicar y conocer a gente cercana geográficamente. De un fácil vistazo puedes encontrar participantes de un lugar concreto y enviarles un mensaje privado. La precisión es de solo 1.111 metros a la redonda, por seguridad.');



$perfil = false;
$result = sql("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND (datos = '' OR text = '') LIMIT 1");
while($r = r($result)) { $perfil = true; }

$txt .= print_hacer((!$perfil?true:false), 
'Tu perfil está bastante completo', 
'Tienes cosas por rellenar en tu perfil', 
'<a href="/perfil/editar" target="_blank" style="font-size:19px;">Ir a tu perfil</a>', 
'Cada usuario tiene su perfil personal, en él se muestra información de actividad, etc. Se recomienda indicar tus redes sociales y escribir una breve nota.');



$votos_foro = 0;
$result = sql("SELECT COUNT(*) AS num FROM votos WHERE tipo IN ('msg', 'hilos') AND emisor_ID = '".$pol['user_ID']."' AND time > '".$margen_30dias."'");
while($r = r($result)) { $votos_foro = $r['num']; }

$txt .= print_hacer(($votos_foro>=$votos_foro_minimo?true:false), 
'Has votado '.$votos_foro.' veces en el foro el último mes', 
'Participa votando más en el foro', 
'<a href="/foro/" target="_blank" style="font-size:19px;">Ir al foro</a>', 
'Puedes votar (+1 -1) cualquier hilo y mensaje del foro, así contribuirás al debate con tu opinión. Se recomienda hacer al menos '.$votos_foro_minimo.' votos al mes (vas '.$votos_foro.').');


$txt .= print_hacer(($pol['grupos']!=''?true:false), 
'Estás afiliado a '.count(explode(' ', $pol['grupos'])).' grupos', 
'No estás afiliado a ningún grupo', 
'<a href="/grupos" target="_blank" style="font-size:19px;">Ver grupos</a>', 
'Afiliandote a grupos podrás acceder a sus foros, documentos, chats y votaciones. Puedes afiliarte a múltiples grupos y en cualquier momento.');



$txt .= '</table>';




if (!$pol['user_ID']) { redirect(REGISTRAR.'?p='.PAIS); } 
elseif ($pol['estado'] != 'ciudadano') { redirect(REGISTRAR.'?p='.PAIS); }


//THEME
$txt_title = '¿Qué hacer?';
$txt_nav = array('/hacer'=>'¿Qué hacer?');
$txt_menu = 'info';
include('theme.php');
?>