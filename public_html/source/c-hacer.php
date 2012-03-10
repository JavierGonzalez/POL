<?php 
include('inc-login.php');

/*

Cosas por hacer:
- Autentificar
- Votar en Elecciones
- Votar en Votaciones
- Repartir votos de confianza.
- Rellenar perfil.
- Votar hilos del foro.
- 

*/


// LOAD CONFIG
$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }


$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); // 30 dias

$txt .= '<h1>&iquest;Qu&eacute; puedes hacer en VirtualPol?</h1>

<ol>

<li>'.($_SESSION['pol']['dnie']=='true'?'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">Est&aacute;s autentificado correctamente.</b>':'<img src="'.IMG.'ico/no.png" width="32" height="32" /> <b style="color:red;">No has autentificado tu usuario.</b>').' <a href="'.SSL_URL.'dnie.php" target="_blank" style="font-size:19px;">Ver autentificaci&oacute;n</a>.<br />
Puedes identificarte solidamente con <abbr title="DNI electronico y otros 30 certificados">DNIe</abbr>, contribuir&aacute;s a reforzar la legitimidad de las votaciones y tu usuario nunca se eliminar&aacute;. Es opcional. <!--Autentificarse con un DNIe es gratis, solo necesitas tener un DNIe vigente, conocer su contrase&ntilde;a y disponer de un lector USB DNIe (8-12 euros).--><br /><br /></li>';




$fecha_24_antes = date('Y-m-d H:i:00', strtotime($pol['config']['elecciones_inicio']) - $pol['config']['elecciones_antiguedad']);

//fecha registro?
$result = mysql_query("SELECT fecha_registro FROM users WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
while($r = mysql_fetch_array($result)){ $fecha_registro = $r['fecha_registro']; }



$has_votado_elecciones = false;
if ($pol['config']['elecciones_estado'] == 'elecciones') {
	$result = mysql_query("SELECT user_ID FROM ".SQL."elecciones WHERE user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) { $has_votado_elecciones = true; }
}

$txt .= '<li>'.($pol['config']['elecciones_estado']=='normal'?'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">Quedan <span class="timer" value="'.strtotime($pol['config']['elecciones_inicio']).'"></span> para las pr&oacute;ximas Elecciones.</b>':($has_votado_elecciones==true?'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">Has votado correctamente en las elecciones.</b>':($fecha_registro>=$fecha_24_antes?'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">Podr&aacute;s votar en las pr&oacute;ximas elecciones.</b>':'<img src="'.IMG.'ico/no.png" width="32" height="32" /> <b style="color:red;">No has votado en las elecciones Elecciones.</b>'))).' <a href="/elecciones/" target="_blank" style="font-size:19px;">Ver Elecciones</a>.<br />
Las Elecciones son un proceso democr&aacute;tico, peri&oacute;dico y autom&aacute;tico. De su resultado dependen los cargos principales de moderaci&oacute;n y gesti&oacute;n. Todos los participantes pueden votar y cualquiera puede postularse como candidato.<br /><br /></li>';




$hay_votaciones = 0;
if ($pol['config']['info_consultas'] > 0) {
	$result = mysql_query("SELECT ID, pregunta, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, (SELECT user_ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS ha_votado FROM votacion WHERE estado = 'ok' AND pais = '".PAIS."'", $link);
	while($r = mysql_fetch_array($result)) { 
		if ((!$r['ha_votado']) AND (nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])) AND (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) { 
			$hay_votaciones++;
			$votaciones_array[] = '<li><a href="/votacion/'.$r['ID'].'/"><b>'.$r['pregunta'].'</b></a></li>';
		}
	}
}

$txt .= '<li>'.($pol['config']['info_consultas']>0?($hay_votaciones>0?'<img src="'.IMG.'ico/no.png" width="32" height="32" /> <b style="color:red;">Hay votaciones en las que a&uacute;n no has votado.</b>':'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">Has votado en todas las votaciones en curso.</b>'):'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">No hay votaciones en curso.</b>').' <a href="/votacion/" target="_blank" style="font-size:19px;">Ver votaciones</a>.<br />
'.($hay_votaciones>0?'<ul>'.implode('', $votaciones_array).'</ul>':'').'
Las votaciones (informativas o vinculantes) son el mecanismo democr&aacute;tico m&aacute;s habitual. Duran un tiempo determinado, configuraci&oacute;n espec&iacute;fica y puede haber varias simult&aacute;neas.<br /><br /></li>';




$votos_confianza = 0;
$result = mysql_query("SELECT COUNT(*) AS num FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."'", $link);
while($r = mysql_fetch_array($result)) { $votos_confianza = $r['num']; }

$txt .= '<li>'.($votos_confianza<VOTO_CONFIANZA_MAX?'<img src="'.IMG.'ico/no.png" width="32" height="32" /> <b style="color:red;">Te quedan '.(VOTO_CONFIANZA_MAX-$votos_confianza).' votos de confianza por repartir.</b>':'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">Has repartido todos tus votos de confianza.</b>').' <a href="/info/censo/confianza/" target="_blank" style="font-size:19px;">Votar confianza</a>.<br />
El voto de confianza es una valoraci&oacute;n (+1 -1) que los usuarios se reparten entre s&iacute;. No implica que te guste ese usuario, si no que conf&iacute;as en &eacute;l. Tienes '.VOTO_CONFIANZA_MAX.' votos de confianza para repartir.<br /><br /></li>';




$txt .= '<li>'.($pol['grupos']==''?'<img src="'.IMG.'ico/no.png" width="32" height="32" /> <b style="color:red;">No est&aacute;s afiliado a ning&uacute;n grupo</b>':'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">Est&aacute;s afiliado a '.count(explode(' ', $pol['grupos'])).' grupos.</b>').' <a href="/grupos/" target="_blank" style="font-size:19px;">Ver grupos</a>.<br />
Afiliandote a grupos podr&aacute;s acceder a sus foros, documentos, chats y votaciones. Puedes afiliarte a m&uacute;ltiples grupos y en cualquier momento.<br /><br /></li>';



$perfil = false;
$result = mysql_query("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND (datos = '' OR text = '') LIMIT 1", $link);
while($r = mysql_fetch_array($result)) { $perfil = true; }

$txt .= '<li>'.($perfil?'<img src="'.IMG.'ico/no.png" width="32" height="32" /> <b style="color:red;">Tienes cosas por rellenar en tu perfil.</b>':'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">Tu perfil est&aacute; bastante completo.</b>').' <a href="/perfil/'.$pol['nick'].'/" target="_blank" style="font-size:19px;">Ir a tu perfil</a>.<br />
Cada usuario tiene su perfil personal, en &eacute;l se muestra informaci&oacute;n de actividad, etc. Se recomienda indicar tus redes sociales y escribir una breve nota.<br /><br /></li>';




$votos_foro = 0;
$result = mysql_query("SELECT COUNT(*) AS num FROM votos WHERE tipo IN ('msg', 'hilos') AND emisor_ID = '".$pol['user_ID']."' AND time > '".$margen_30dias."'", $link);
while($r = mysql_fetch_array($result)) { $votos_foro = $r['num']; }

$votos_foro_minimo = 30;
$txt .= '<li>'.($votos_foro>=$votos_foro_minimo?'<img src="'.IMG.'ico/ok.png" width="32" height="32" /> <b style="color:blue;">Has votado '.$votos_foro.' veces en el foro el &uacute;ltimo mes.</b>':'<img src="'.IMG.'ico/no.png" width="32" height="32" /> <b style="color:red;">Participa votando m&aacute;s en el foro.</b>').' <a href="/foro/" target="_blank" style="font-size:19px;">Ir al foro</a>.<br />
Puedes votar (+1 -1) cualquier hilo y mensaje del foro, as&iacute; contribuir&aacute;s al debate con tu opini&oacute;n. Se recomienda hacer al menos '.$votos_foro_minimo.' votos al mes (vas '.$votos_foro.').</li>';





$txt .= '</ol>

<p>Por orden de importancia.</p>';


if (!$pol['user_ID']) { redirect(REGISTRAR.'?p='.PAIS); } 
elseif ($pol['estado'] != 'ciudadano') { redirect(REGISTRAR.'?p='.PAIS); }


//THEME
$txt_title = '¿Qué puedes hacer?';
$txt_nav = array('/hacer'=>'¿Qué hacer?');
$txt_menu = 'info';
include('theme.php');
?>