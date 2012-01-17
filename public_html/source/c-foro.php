<?php 
include('inc-login.php');

function reemplazos($t) { return '<span class="rich">'.strip_tags($t, '<br>').'</span>'; }

function print_lateral($nick, $cargo_ID=false, $time, $siglas='', $user_ID='', $avatar='', $votos=0, $voto=false, $tipo='msg', $item_ID=0) {
	global $pol;
	if ($cargo_ID == 99) { $cargo = 'Extranjero'; }
	return '<table border="0" width="100%"><tr>
<td width="40" valign="top">'.($avatar=='true'?'<span>'.avatar($user_ID, 40).'</span>':'').'</td>
<td align="right" valign="top" nowrap="nowrap">
<b>'.($cargo_ID?'<img src="'.IMG.'cargos/'.$cargo_ID.'.gif" /> ':'').crear_link($nick).'</b><br />
<span class="min">'.timer($time).' '.$siglas.'</span> 
<span id="'.$tipo.$item_ID.'">'.confianza($votos).'</span>'.($pol['pais']==PAIS&&$item_ID!=0&&$user_ID!=$pol['user_ID']?'<br />
<span id="data_'.$tipo.$item_ID.'" class="votar" type="'.$tipo.'" name="'.$item_ID.'" value="'.$voto.'"></span>':'').'
</td></tr></table>';
}

function foro_enviar($subforo, $hilo=null, $edit=null, $citar=null) {
	global $pol, $link, $return_url, $vp;

	$referer = explode('/', $_SERVER['HTTP_REFERER'], 4); 
	$referer = '/'.$referer[3];

	if (($pol['estado'] == 'ciudadano') OR ($pol['estado'] == 'extranjero')) {
		if ($edit) { //editar
			$return_url = 'foro/';
			if ($hilo) { //msg
				$result = mysql_query("SELECT text, cargo FROM ".SQL."foros_msg WHERE ID = '" . $hilo . "' AND estado = 'ok' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
				while($r = mysql_fetch_array($result)){ $edit_text = $r['text']; $edit_cargo = $r['cargo']; }
			} else { //hilo
				$result = mysql_query("SELECT sub_ID, text, cargo, title, user_ID, ID FROM ".SQL."foros_hilos WHERE ID = '" . $subforo . "' AND estado = 'ok' AND (user_ID = '".$pol['user_ID']."' OR 'true' = '".(nucleo_acceso($vp['acceso']['foro_borrar'])?'true':'false')."') LIMIT 1", $link);
				while($r = mysql_fetch_array($result)){ $sub_ID = $r['sub_ID']; $edit_ID = $r['ID']; $edit_user_ID = $r['user_ID']; $edit_title = $r['title']; $edit_text = $r['text']; $edit_cargo = $r['cargo']; }
			}
			$edit_text = strip_tags($edit_text);
		}
		if ($citar != null) { //citar
			if ($citar>0) { //msg
				$result = mysql_query("SELECT text, user_ID FROM ".SQL."foros_msg WHERE ID = '" . $citar . "' AND estado = 'ok'  LIMIT 1", $link);
				while($r = mysql_fetch_array($result)){ 
					$edit_text = $r['text']; 
					$user_ID = $r['user_ID'];
				}
			} 
			else {
				$result = mysql_query("SELECT text, user_ID FROM ".SQL."foros_hilos WHERE ID = '" . abs($citar) . "' AND estado = 'ok' LIMIT 1", $link);
				while($r = mysql_fetch_array($result)){ 
					$edit_text = $r['text']; 
					$user_ID = $r['user_ID'];
				}
			}
			$result = mysql_query("SELECT nick FROM users WHERE ID = '" . $user_ID . "' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ 
				$edit_text = '[quote='.$r['nick'].'] '.$edit_text.' [/quote]'; 
			}
			
			$edit_text = strip_tags($edit_text);
			
		}

		if ($pol['nivel'] > 1) {
			$result = mysql_query("SELECT ID_estudio, 
(SELECT nombre FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nombre,
(SELECT nivel FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nivel
FROM ".SQL."estudios_users  WHERE cargo = '1' AND user_ID = '" . $pol['user_ID'] . "'
ORDER BY nivel DESC", $link);
			while($r = mysql_fetch_array($result)){
				if ($edit_cargo == $r['ID_estudio']) { $selected = ' selected="selected"'; } else { $selected = ''; }
				$select_cargos .= '<option value="' . $r['ID_estudio'] . '"' . $selected . '>' . $r['nombre'] . '</option>' . "\n";
			}
		}
		if ($pol['estado'] == 'extranjero') { $select_cargos = '<option value="99">Extranjero</option>'; } else { $select_cargos = '<option value="0">Ciudadano</option>' . $select_cargos; }

		if (!$hilo) { 
			if ($edit) { $get = 'editar'; } else { $get = 'hilo'; } 

// <input type="hidden" name="return_url" value="' . $return_url . '"  />

			$html .= '<div id="enviar" class="redondeado">
<h2>Nuevo hilo</h2>


<form action="/accion.php?a=foro&b=' . $get . '" method="post">
<input type="hidden" name="subforo" value="' . $subforo . '"  />
<input type="hidden" name="return_url" value="' . $return_url . '"  />';

			if ($edit) {
				$html .= '<p>Foro: <select name="sub_ID">';
				$result = mysql_query("SELECT ID, url, title, acceso_escribir, acceso_cfg_escribir FROM ".SQL."foros WHERE estado = 'ok' ORDER BY time ASC", $link);
				while($r = mysql_fetch_array($result)){ 
					$html .= '<option value="'.$r['ID'].'"'.($r['ID']==$sub_ID?' selected="selected"':'').(nucleo_acceso($r['acceso_escribir'],$r['acceso_cfg_escribir'])?'':' disabled="disabled"').'>'.$r['title'].'</option>';
				}
				$html .= '</select></p>';
			}
			$html .= '
<p>T&iacute;tulo:<br />
<input name="title" size="60" maxlength="80" type="text" value="'.str_replace('"', '&#34;', $edit_title).'" /></p>

<p'.($edit&&$edit_user_ID!=$pol['user_ID']?' style="display:none;"':'').'>Mensaje:<br />
<textarea name="text" style="color: green; font-weight: bold; width: 570px; height: 250px;">' . $edit_text . '</textarea><br />
<span style="color:grey;font-size:12px;">Etiquetas: [b]...[/b] [em]...[/em] [quote]...[/quote] [img]url[/img] [youtube]url-youtube[/youtube], auto-enlaces.</span></p>

<p><input value="Enviar" type="submit" style="font-size:22px;" /> En calidad de: <select name="encalidad" style="color:green;font-weight:bold;font-size:17px;">' . $select_cargos . '
</select></p>
</form>

'.($edit?'<hr /><p>'.boton('Eliminar hilo', '/accion.php?a=foro&b=eliminarhilo&ID='.$edit_ID, '&iquest;Est&aacute;s seguro de querer ELIMINAR este HILO DE FORMA IRREVOCABLE?').'</p>':'').'

</div>';
		} else {
			if ($edit) { $get = 'editar'; } else { $get = 'reply'; } 
			$html .= '<div id="enviar" class="redondeado">
<form action="/accion.php?a=foro&b=' . $get . '" method="post">
<input type="hidden" name="subforo" value="' . $subforo . '"  />
<input type="hidden" name="hilo" value="' . $hilo . '"  />
<input type="hidden" name="return_url" value="' . $return_url . '"  />

<h2>Respuesta</h2>

<p>Mensaje:<br />
<textarea name="text" style="color: green; font-weight: bold; width: 570px; height: 250px;">' . $edit_text . '</textarea><br />
<span style="color:grey;font-size:12px;">Etiquetas: [b]...[/b] [em]...[/em] [quote]...[/quote] [img]url[/img] [youtube]url-youtube[/youtube], auto-enlaces.</span></p>

<p><input value="Enviar" type="submit" style="font-size:22px;" /> En calidad de: <select name="encalidad" style="color:green;font-weight:bold;font-size:17px;">' . $select_cargos . '
</select></p>

</form>
</div>';
		}
		return $html;
	} else {
		return '<p class="azul"><b>Debes ser Ciudadano para participar, <a href="'.REGISTRAR.'?p='.PAIS.'">reg&iacute;strate aqu&iacute;!</a></b></p>';
	}
}








/*
pol_foros			(`ID` `url` `title` `descripcion` `acceso` `time` `estado`)
pol_foros_hilos		(`ID` `sub_ID``url` `user_ID` `title` `time` `time_last` `text` `cargo` `num`)
pol_foros_msg		(`ID``hilo_ID` `user_ID` `time` `text` `cargo`)
*/



if ($_GET['a'] == 'r') { // redirigir

	$result = mysql_query("SELECT url,
(SELECT url FROM ".SQL."foros WHERE ID = ".SQL."foros_hilos.sub_ID LIMIT 1) AS subforo
FROM ".SQL."foros_hilos
WHERE ID = '".$_GET['b']."'
LIMIT 1", $link);
$txt .= mysql_error($link);
	while($r = mysql_fetch_array($result)) {
		r301('/foro/'.$r['subforo'].'/'.$r['url'].'/');
	}


} elseif ($_GET['a'] == 'editar') {
	$txt .= '<h1><a href="/foro/">Foro</a>: editar</h1>';

	$txt .= foro_enviar($_GET['b'], $_GET['c'], true);

} elseif ($_GET['a'] == 'mis-respuestas') {


	if ($_GET['b']) {
		$result = mysql_query("SELECT ID, nick FROM users WHERE nick = '".$_GET['b']."' LIMIT 1", $link);
		while($r = mysql_fetch_array($result)) {
			$el_nick = $r['nick'];
			$el_user_ID = $r['ID'];
		}
	} elseif ($pol['user_ID']) { 
		$el_user_ID = $pol['user_ID']; 
	}
	if (!isset($el_user_ID)) { exit; }

	$txt_title = 'Foro - Actividad';
	$txt .= '<h1><a href="/foro/">Foro</a>: <b>'.($_GET['b']?'Actividad de '.$el_nick:'Tu actividad').'</b></h1>
<br />';

	$txt .= '<table border="0" cellpadding="1" cellspacing="0" class="pol_table">

<tr class="amarillo"><td colspan="4"><h2 style="font-size:18px;padding:8px;">&Uacute;ltimos hilos:</h2></tr>';

	$result = mysql_query("SELECT ID, url FROM ".SQL."foros", $link);
	while($r = mysql_fetch_array($result)) { $sub[$r['ID']] = $r['url']; }



	$result = mysql_query("SELECT *, u.nick
FROM ".SQL."foros_hilos `h`
LEFT JOIN users `u` ON (u.ID = h.user_ID)
WHERE user_ID = '".$el_user_ID."'
ORDER BY h.time DESC
LIMIT 10", $link);
	while($r = mysql_fetch_array($result)) {
		$txt .= '<tr><td align="right" valign="top" colspan="2">' . print_lateral($r['nick'], $r['cargo'], $r['time'], '', $pol['user_ID'], '', $r['votos'], false, 'hilos', $r['ID']) . '</td><td align="right" valign="top"><b style="font-size:20px;">'.$r['num'].'</b></td><td valign="top" colspan="2" nowrap="nowrap" style="color:grey;"><a href="/foro/' . $sub[$r['sub_ID']] . '/' . $r['url'] . '/"><b>' . $r['title'] . '</b></a><br />' . substr(strip_tags($r['text']), 0, 90) . '..</td></tr>';
	}


	$txt .= '</table>';


	$txt .= '<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">

<tr class="amarillo"><td colspan="4"><h2 style="font-size:18px;padding:8px;">&Uacute;ltimos mensajes:</h2></tr>';

	$result = mysql_query("SELECT ID, url FROM ".SQL."foros", $link);
	while($r = mysql_fetch_array($result)) { $sub[$r['ID']] = $r['url']; }

	$result = mysql_query("SELECT ID, hilo_ID, user_ID, time, text, cargo, votos,
(SELECT nick FROM users WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad,
(SELECT url FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_url,
(SELECT title FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_titulo,
(SELECT sub_ID FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS sub_ID
FROM ".SQL."foros_msg
WHERE hilo_ID != '-1' AND user_ID = '".$el_user_ID."'
ORDER BY time DESC
LIMIT 50", $link);
	while($r = mysql_fetch_array($result)) {

		$result2 = mysql_query("SELECT COUNT(*) AS resp_num FROM ".SQL."foros_msg WHERE hilo_ID = '".$r['hilo_ID']."' AND time > '".$r['time']."'", $link);
		while($r2 = mysql_fetch_array($result2)) {
			$resp_num = $r2['resp_num'];
		}

		if (!$repes[$r['hilo_ID']]) {
			$repes[$r['hilo_ID']] = true;

			$txt .= '<tr><td align="right" valign="top" colspan="2">' . print_lateral($r['nick'], $r['cargo'], $r['time'], '', $pol['user_ID'], '', $r['votos'], false, 'msg', $r['ID']) . '</td><td align="right" valign="top"><acronym title="Nuevos mensajes"><b style="font-size:20px;">'.$resp_num.'</b></acronym></td><td valign="top" colspan="2" nowrap="nowrap" style="color:grey;"><a href="/foro/'.$sub[$r['sub_ID']].'/'.$r['hilo_url'].'"><b>'.$r['hilo_titulo'].'</b></a><br /><span title="Mensajes despu&eacute;s del tuyo">(<b style="font-size:18px;">'.$resp_num.'</b> nuevos)</span> '.substr(strip_tags($r['text']), 0, 90).'..</td></tr>';
		}
	}


	$txt .= '</table>';


} elseif ($_GET['a'] == 'ultima-actividad') {


	$txt_title = 'Foro - Ultima actividad';
	$txt .= '<h1><a href="/foro/">Foro</a>: <b>Ultima actividad</b></h1>
<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">

<tr class="amarillo"><td colspan="4"><h2 style="font-size:18px;padding:8px;">Ultimos 50 mensajes:</h2></tr>';

	$result = mysql_query("SELECT ID, url FROM ".SQL."foros", $link);
	while($r = mysql_fetch_array($result)) { $sub[$r['ID']] = $r['url']; }

	$result = mysql_query("SELECT ID, hilo_ID, user_ID, time, text, cargo, votos,
(SELECT nick FROM users WHERE ID = m.user_ID LIMIT 1) AS nick,
(SELECT nombre FROM ".SQL."estudios WHERE ID = m.cargo LIMIT 1) AS encalidad,
(SELECT url FROM ".SQL."foros_hilos WHERE ID = m.hilo_ID LIMIT 1) AS hilo_url,
(SELECT title FROM ".SQL."foros_hilos WHERE ID = m.hilo_ID LIMIT 1) AS hilo_titulo,
(SELECT sub_ID FROM ".SQL."foros_hilos WHERE ID = m.hilo_ID LIMIT 1) AS sub_ID,
(SELECT voto FROM votos WHERE tipo = 'msg' AND pais = '".PAIS."' AND item_ID = m.ID AND emisor_ID = '".$pol['user_ID']."') AS voto
FROM ".SQL."foros_msg `m`
WHERE hilo_ID != '-1' AND estado = 'ok'
ORDER BY time DESC
LIMIT 50", $link);
	while($r = mysql_fetch_array($result)) {

		$result2 = mysql_query("SELECT acceso_leer, acceso_cfg_leer FROM ".SQL."foros WHERE ID = '".$r['sub_ID']."' LIMIT 1", $link);
		while($r2 = mysql_fetch_array($result2)) {
			if (nucleo_acceso($r2['acceso_leer'], $r2['acceso_cfg_leer'])) {
				$txt .= '<tr>
<td align="right" valign="top" colspan="2" nowrap="nowrap">'.print_lateral($r['nick'], $r['cargo'], $r['time'], '', $r['user_ID'], '', $r['votos'], $r['voto'], 'msg', $r['ID']).'</td>
<td valign="top" colspan="2">
<span style="font-size:17px;"><a href="/foro/'.$sub[$r['sub_ID']].'/'.$r['hilo_url'].'"><b>'.$r['hilo_titulo'].'</b></a></span><br />

<span style="text-align:justify;font-size:15px;" class="rich">'.$r['text'].'</span>
<br /><br />
</td>
</tr>';
			}
		}
	}

	$txt .= '</table>';




} elseif ($_GET['b']) {			//foro/subforo/hilo-prueba/


	$result = mysql_query("SELECT h.ID, sub_ID, user_ID, h.url, h.title, h.time, time_last, h.text, h.cargo, num, u.nick, u.estado, u.avatar, acceso_leer, acceso_escribir, acceso_escribir_msg, acceso_cfg_leer, acceso_cfg_escribir, acceso_cfg_escribir_msg, votos, v.voto, f.title AS foro_title, f.url AS foro_url, f.descripcion
FROM ".SQL."foros_hilos `h`
LEFT JOIN ".SQL."foros `f` ON (f.ID = sub_ID)
LEFT JOIN users `u` ON (u.ID = user_ID)
LEFT JOIN votos `v` ON (tipo = 'hilos' AND v.pais = '".PAIS."' AND item_ID = h.ID AND emisor_ID = '".$pol['user_ID']."')
WHERE h.url = '".$_GET['b']."' AND h.estado = 'ok'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {

		// Foro incorrecto? redireccion.
		if ($_GET['a'] != $r['foro_url']) { 
			header('HTTP/1.1 301 Moved Permanently'); 
			header('Location: http://'.strtolower(PAIS).'.virtualpol.com/foro/'.$r['foro_url'].'/'.$r['url'].'/'); 
			exit; 
		}
		

		$acceso['leer'] = nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer']);
		$acceso['escribir_msg'] = nucleo_acceso($r['acceso_escribir_msg'], $r['acceso_cfg_escribir_msg']);

		if ($acceso['leer']) {

			if ($r['estado'] != 'expulsado') {

				$subforo = $_GET['a'];
				$return_url = 'foro/' . $subforo . '/' . $r['url'] . '/';
				paginacion('hilo', '/'.$return_url, $r['ID'], $_GET['c'], $r['num']);
				
				if ($_GET['c']) { $pag_title = ' - P&aacute;gina: '.$_GET['c']; }
				$txt_title = $r['title'].' - Foro: '.$r['foro_title'].$pag_title;
				$txt_description = $r['title'].' - Foro: '.$r['foro_title'].$pag_title;


				// acceso
				if ($acceso['escribir_msg']) { $crear_hilo = '#enviar'; $citar = '<div class="citar">'.boton('Citar', '/'.$return_url.'1/-'.$r['ID'].'#enviar').'</div>'; } else { $crear_hilo = ''; }


				$txt .= '<h1><a href="/foro/">Foro</a>: <a href="/foro/'.$r['foro_url'].'/">'.$r['foro_title'].'</a> (<span style="font-size:18px;">'.$r['descripcion'].'</span>)</h1>

<p style="margin-bottom:4px;">'.$p_paginas.' &nbsp; ' . boton('Responder', $crear_hilo) . ' &nbsp; 
<span style="float:right;">Orden: <a href="/'.$return_url.'/"'.($_GET['c']=='mejores'?'':' style="color:#444;"').'>Fecha</a> | <a href="/'.$return_url.'mejores/"'.($_GET['c']=='mejores'?' style="color:#444;"':'').'>Votos</a></span>
<b>'.$r['num'].'</b> mensajes en este hilo creado hace <acronym title="'.$r['time'].'"><span class="timer" value="'.strtotime($r['time']).'"></span></acronym>.</p>

<table border="0" cellpadding="2" cellspacing="0" class="pol_table">';

				if (($pol['user_ID'] == $r['user_ID']) AND ($subforo != 'notaria')) { 
					// es tu post
					$editar = '<span style="float:right;">'.boton('Editar', '/foro/editar/'.$r['ID'].'/').'</span>'; 
				} elseif (nucleo_acceso($vp['acceso']['foro_borrar'])) { 
					$editar = '<span style="float:right;">'.boton('Mover', '/foro/editar/'.$r['ID'].'/').' '.boton('Papelera', '/accion.php?a=foro&b=borrar&c=hilo&ID='.$r['ID'].'/', '&iquest;Quieres enviar a la PAPELERA este HILO y sus MENSAJES?').'</span>'; 
				} else { $editar = ''; }

				$txt .= '<tr><td align="right" valign="top">' . print_lateral($r['nick'], $r['cargo'], $r['time'], $r['siglas'], $r['user_ID'], $r['avatar'], $r['votos'], $r['voto'], 'hilos', $r['ID']) . '</td><td class="amarillo redondeado" valign="top" width="80%"><p style="text-align:justify;">'.$citar.$editar.'<h1 style="margin:-6px 0 10px 0;"><a href="/'.$return_url.'" class="rich" style="font-size:20px;">'.$r['title'].'</a></h1>'.reemplazos($r['text']).'</p></td></tr>';

				$result2 = mysql_query("SELECT m.ID, hilo_ID, user_ID, m.time, m.text, m.cargo, nick, m.estado AS nick_estado, avatar, votos, v.voto
FROM ".SQL."foros_msg `m`
LEFT JOIN users `u` on (u.ID = user_ID)
LEFT JOIN votos `v` ON (tipo = 'msg' AND v.pais = '".PAIS."' AND item_ID = m.ID AND emisor_ID = '".$pol['user_ID']."')
WHERE hilo_ID = '".$r['ID']."' AND m.estado = 'ok'
ORDER BY ".($_GET['c']=='mejores'?'votos DESC LIMIT 100':'time ASC LIMIT '.$p_limit), $link);
				while($r2 = mysql_fetch_array($result2)) {

					if (($pol['user_ID'] == $r2['user_ID']) AND ($subforo != 'notaria') AND (strtotime($r2['time']) > (time() - 3600))) { 
						$editar = boton('Editar', '/foro/editar/' . $r2['hilo_ID'] . '/' . $r2['ID'] . '/') . boton('X', '/accion.php?a=foro&b=eliminarreply&ID=' . $r2['ID'] . '&hilo_ID=' . $r2['hilo_ID'], '&iquest;Est&aacute;s seguro de querer ELIMINAR tu MENSAJE?') . ' '; 
					} elseif (nucleo_acceso($vp['acceso']['foro_borrar'])) { 
						// policia borra
						$editar = boton('Papelera', '/accion.php?a=foro&b=borrar&c=mensaje&ID=' . $r2['ID'] . '/', '&iquest;Quieres enviar a la PAPELERA este MENSAJE?') . ' '; 
					} else { $editar = ''; }
					if ($citar) {
						 $citar = '<div class="citar">'.boton('Citar', '/'.$return_url.'1/'.$r2['ID'].'#enviar').'</div>'; 
					}

					$txt .= '<tr id="m-' . $r2['ID'] . '"><td align="right" valign="top">' . print_lateral($r2['nick'], $r2['cargo'], $r2['time'], $r2['siglas'], $r2['user_ID'], $r2['avatar'], $r2['votos'], $r2['voto'], 'msg', $r2['ID']) . '</td><td valign="top"><p class="pforo"><span style="float:right;">' . $editar . '<a href="#m-' . $r2['ID'] . '">#</a></span>'.($r2['nick_estado']=='expulsado'?'<span style="color:red;">Expulsado.</span>':$citar.reemplazos($r2['text'])).'</p></td></tr>';
				}
				$txt .= '</table> <p>' . $p_paginas . '</p>';

				if ($acceso['escribir_msg']) { $txt .= foro_enviar($r['sub_ID'], $r['ID'], null, $_GET['d']); }

				if (!$pol['user_ID']) { $txt .= '<p class="azul"><b>Para poder participar en esta conversacion has de <a href="'.REGISTRAR.'?p='.PAIS.'">registrar tu ciudadano</a></b></p>'; }
				
				$txt .= '<br /><hr /><p>'.$r['title'].'. M&aacute;s hilos: ';
				$result2 = mysql_query("SELECT url, title, (SELECT url FROM ".SQL."foros WHERE ID = ".SQL."foros_hilos.sub_ID LIMIT 1) AS subforo FROM ".SQL."foros_hilos WHERE estado = 'ok' ORDER BY RAND() LIMIT 8", $link);
				while($r2 = mysql_fetch_array($result2)) {
					$txt .= '<a href="/foro/' . $r2['subforo'] . '/' . $r2['url'] . '/">' . $r2['title'] . '</a>, ';
				}
				$txt .= '<p>';
				
				$txt_header = '<style type="text/css">.content-in hr { border: 1px solid grey; } .flateral { margin:0 0 0 5px; float:right; } .pforo { text-align:justify; margin:2px; }</style>';
			}
		} else { $txt .= '<p><b style="color:red;">No tienes acceso de lectura a este subforo.</b></p>'; }
	}

} elseif ($_GET['a'] == 'papelera') { //foro/papelera/

	$txt_title = 'Papelera';
	$txt .= '<h1><a href="/foro/">Foro</a>: Papelera</h1>
<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
<tr class="azul"><td colspan="4"><h2 style="color:red;font-size:22px;padding:8px;">Hilos</h2></tr>';



	$result = mysql_query("SELECT ID, sub_ID, user_ID, url, title, time, time_last, text, cargo, num, votos,
(SELECT nick FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID = users.partido_afiliado LIMIT 1) FROM users WHERE ID = ".SQL."foros_hilos.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_hilos.cargo LIMIT 1) AS encalidad
FROM ".SQL."foros_hilos
WHERE estado = 'borrado'
ORDER BY time_last DESC", $link);
	while($r = mysql_fetch_array($result)) {
		if (nucleo_acceso($vp['acceso']['foro_borrar'])) { $boton = boton('Restaurar', '/accion.php?a=foro&b=restaurar&c=hilo&ID=' . $r['ID'], '&iquest;Quieres RESTAURAR este HILO y sus MENSAJES?'); } else { $boton = boton('Restaurar'); }

		$txt .= '<tr><td align="right" valign="top">' . print_lateral($r['nick'], $r['cargo'], $r['time'], $r['siglas'], $r['user_ID'], $r['avatar'], $r['votos'], false, 'hilos') . '</td><td valign="top"><p class="pforo"><b style="color:blue;">' . $r['title'] . '</b><br />' . $r['text'] . '</p></td><td valign="top" nowrap="nowrap"><acronym title="' . $r['time_last'] . '"><span class="timer" value="'.strtotime($r['time_last']).'"></span></acronym></td><td valign="top">' . $boton . '</td></tr>';
	}

$txt .= '<tr><td><br /></td></tr><tr class="azul"><td colspan="4"><h2 style="color:red;font-size:22px;padding:8px;">Mensajes</h2></tr>';



	$result = mysql_query("SELECT ID, hilo_ID, user_ID, time, time2, text, cargo, votos,
(SELECT nick FROM users WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM users WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID = users.partido_afiliado LIMIT 1) FROM users WHERE ID = ".SQL."foros_msg.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad
FROM ".SQL."foros_msg
WHERE estado = 'borrado'
ORDER BY time2 DESC", $link);
	while($r = mysql_fetch_array($result)) {
		if (nucleo_acceso($vp['acceso']['foro_borrar'])) { $boton = boton('Restaurar', '/accion.php?a=foro&b=restaurar&c=mensaje&ID=' . $r['ID'], '&iquest;Quieres RESTAURAR este MENSAJE?'); } else { $boton = boton('Restaurar'); }

		$txt .= '<tr><td align="right" valign="top">' . print_lateral($r['nick'], $r['cargo'], $r['time'], $r['siglas'], $r['user_ID'], $r['avatar'], $r['votos'], false) . '</td><td valign="top"><p class="pforo">' . $r['text'] . '</p></td><td valign="top" nowrap="nowrap"><acronym title="' . $r['time2'] . '"><span class="timer" value="'.strtotime($r['time2']).'"></span></acronym></td><td valign="top">' . $boton . '</td></tr>';
	}


	$txt .= '</table><br /><p class="gris">Los mensajes se eliminar&aacute;n tras 10 d&iacute;as.</p>';

	$txt_header = '<style type="text/css">.content-in hr { border: 1px solid grey; } .flateral { margin:0 0 0 5px; float:right; } .pforo { text-align:justify; font-size:11px; margin:2px; }</style>';



} elseif ($_GET['a']) {	//foro/subforo/

	$result = mysql_query("SELECT * FROM ".SQL."foros WHERE url = '" . $_GET['a'] . "' AND estado = 'ok' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {
		if (nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) {
			$return_url = 'foro/'.$r['url'].'/';
			
			$txt_title = 'Foro: '.$r['title'].' - '.$r['descripcion'];

			if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) { $crear_hilo = '#enviar'; } else { $crear_hilo = ''; }

			$txt .= '<h1><a href="/foro/">Foro</a>: <a href="/foro/'.$r['url'].'/">'.$r['title'].'</a> (<span style="font-size:18px;">'.$r['descripcion'].'</span>)</h1>

<p style="margin-bottom:0;">'.boton('Crear Hilo', $crear_hilo).'</p>

<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
<tr>
<th>Autor</th>
<th colspan="2">Mensajes</th>
<th>Hilo</th>
<th>Creado</th>
<th></th>
</tr>';
			$result2 = mysql_query("SELECT ID, url, user_ID, title, time, time_last, cargo, num, sub_ID, votos,
(SELECT nick FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT estado FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS estado
FROM ".SQL."foros_hilos
WHERE sub_ID = '" . $r['ID'] . "' AND estado = 'ok'
ORDER BY time_last DESC
LIMIT 200", $link);
			while($r2 = mysql_fetch_array($result2)) {

				if ($r2['estado'] != 'expulsado') {
					if (strtotime($r2['time']) < (time() - 432000)) { 
						$titulo = '<a href="/foro/' . $r['url'] . '/' . $r2['url'] . '/">' . $r2['title'] . '</a>'; 
					} else { 
						$titulo = '<a href="/foro/' . $r['url'] . '/' . $r2['url'] . '/"><b>' . $r2['title'] . '</b></a>'; 
					}
					if (strtotime($r2['time']) > (time() - 86400)) { $titulo = $titulo . ' <sup style="font-size:9px;color:red;">Nuevo!</sup>'; }

					if (($pol['user_ID'] == $r2['user_ID']) AND (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir']))) { 
						$editar = ' ' . boton('X', '/accion.php?a=foro&b=eliminarhilo&ID=' . $r2['ID'], '&iquest;Est&aacute;s seguro de querer ELIMINAR este HILO?'); 
					} else { $editar = ''; }

					$txt .= '<tr>
<td align="right">'.crear_link($r2['nick']).'</td>
<td align="right"><b>'.$r2['num'].'</b></td>
<td align="right" style="padding-right:4px;">'.confianza($r2['votos']).'</td>
<td>'.$titulo.'</td>
<td align="right"><span class="timer" value="'.strtotime($r2['time']).'"></span></td>
<td>'.$editar.'</td>
</tr>';
				}
			}
			$txt .= '</table><br />';
			if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) { $txt .= foro_enviar($r['ID']); }
		} else { $txt .= '<p><b style="color:red;">No tienes acceso de lectura a este subforo.</b></p>'; }
	}


} else {						//foro/

	$adsense_exclude = true;

	$txt_title = 'Foro';
	$txt .= '<div style="float:right;color:green;"><a href="/foro/ultima-actividad/">&Uacute;ltima actividad</a> | <a href="/foro/mis-respuestas/">Tu actividad</a> &nbsp; [<a href="/control/gobierno/foro/">Configuraci&oacute;n foro</a>]</div>

<h1><b>Foro</b>:</h1>
<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">';

	$result = mysql_query("SELECT *,
(SELECT COUNT(*) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID LIMIT 1) AS num
FROM ".SQL."foros
WHERE estado = 'ok'
ORDER BY time ASC", $link);
	while($r = mysql_fetch_array($result)) {
		if (nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) {

			if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) { $crear_hilo = boton('Crear Hilo', '/foro/' . $r['url'] . '/#enviar'); } else { $crear_hilo = boton('Crear Hilo'); }

			$txt .= '<tr class="amarillo">
<td nowrap="nowrap" style="padding-right:4px;"><h2><a href="/foro/'.$r['url'].'/" style="font-size:22px;margin-left:8px;"><b>'.$r['title'].'</b></a></h2></td>
<td align="right"><b style="font-size:19px;">'.$r['num'].'</b></td>
<td style="color:green;" colspan="2"><span style="float:right;color:grey;">'.$el_acceso.'</span><span style="font-size:18px;">'.$r['descripcion'].'</span></td>
<td align="right" width="10%">'.$crear_hilo.'</td>
</tr>';

			if (!$r['limite']) { $r['limite'] = 8; }

			$result2 = mysql_query("SELECT ID, url, user_ID, title, time, time_last, cargo, num, votos,
(SELECT nick FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT estado FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS estado
FROM ".SQL."foros_hilos
WHERE sub_ID = '".$r['ID']."' AND estado = 'ok'
ORDER BY time_last DESC
LIMIT ".$r['limite'], $link);
			while($r2 = mysql_fetch_array($result2)) {
				if ($r2['estado'] != 'expulsado') {
					$hilo_url[$r2['ID']] = '<a href="/foro/' . $r['url'] . '/' . $r2['url'] . '/">' . $r2['title'] . '</a>';
					if (strtotime($r2['time']) < (time() - 432000)) { $titulo = $hilo_url[$r2['ID']]; } else { $titulo = '<b>' . $hilo_url[$r2['ID']] . '</b>'; }
					if (strtotime($r2['time']) > (time() - 86400)) { $titulo = $titulo . ' <sup style="font-size:9px;color:red;">Nuevo!</sup>'; }
					$txt .= '<tr>
<td align="right" style="padding-right:4px;" valign="top">'.crear_link($r2['nick']).'</td>
<td valign="top" align="right"><b>'.$r2['num'].'</b></td>
<td align="right" style="padding-right:4px;">'.confianza($r2['votos']).'</td>
<td class="rich">'.$titulo.'</td>
<td align="right" valign="top" nowrap="nowrap"><span class="timer" value="'.strtotime($r2['time']).'"></span></td>
</tr>';
				}
			}
			$txt .= '<tr><td colspan="4">&nbsp;</td></tr>';
		}
	}


		$txt .= '<tr class="amarillo">
<td width="120"><h2><a href="/foro/papelera/" style="font-size:22px;margin-left:8px;">Papelera</a></h2></td>
<td align="right"><b style="font-size:19px;"></b></td>
<td style="color:green;" colspan="2">Cuarentena de mensajes, eliminados tras 10 d&iacute;as.</td>
<td align="right" width="10%"></td>
</tr>
</table>';

}


//THEME
include('theme.php');
?>
