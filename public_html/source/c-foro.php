<?php 
include('inc-login.php');
/*
pol_foros			(`ID` `url` `title` `descripcion` `acceso` `time` `estado`)
pol_foros_hilos		(`ID` `sub_ID``url` `user_ID` `title` `time` `time_last` `text` `cargo` `num`)
pol_foros_msg		(`ID``hilo_ID` `user_ID` `time` `text` `cargo`)
*/

function foro_enviar($subforo, $hilo=null, $edit=null) {
	global $pol, $link, $return_url;

	$referer = explode('/', $_SERVER['HTTP_REFERER'], 4); 
	$referer = '/'.$referer[3];

	if (($pol['estado'] == 'ciudadano') OR ($pol['estado'] == 'desarrollador') OR (($pol['config']['frontera_con_'.$pol['pais']] == 'abierta') AND ($pol['estado'] == 'extranjero'))) {
		if ($edit) { //editar
			$return_url = 'foro/';
			if ($hilo) { //msg
				$result = mysql_query("SELECT text, cargo FROM ".SQL."foros_msg WHERE ID = '" . $hilo . "' AND estado = 'ok' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
				while($row = mysql_fetch_array($result)){ $edit_text = $row['text']; $edit_cargo = $row['cargo']; }
			} else { //hilo
				$result = mysql_query("SELECT sub_ID, text, cargo, title FROM ".SQL."foros_hilos WHERE ID = '" . $subforo . "' AND estado = 'ok' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
				while($row = mysql_fetch_array($result)){ $edit_title = $row['title']; $edit_text = $row['text']; $edit_cargo = $row['cargo']; }
			}
			$edit_text = strip_tags($edit_text, "<img>,<b>,<i>,<s>,<embed>,<object>,<param>");
		}

		if ($pol['nivel'] > 1) {
			$result = mysql_query("SELECT ID_estudio, 
(SELECT nombre FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nombre,
(SELECT nivel FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nivel
FROM ".SQL."estudios_users  WHERE cargo = '1' AND user_ID = '" . $pol['user_ID'] . "'
ORDER BY nivel DESC", $link);
			while($row = mysql_fetch_array($result)){
				if ($edit_cargo == $row['ID_estudio']) { $selected = ' selected="selected"'; } else { $selected = ''; }
				$select_cargos .= '<option value="' . $row['ID_estudio'] . '"' . $selected . '>' . $row['nombre'] . '</option>' . "\n";
			}
		}
		if ($pol['estado'] == 'extranjero') { $select_cargos = '<option value="99">Extranjero</option>'; } else { $select_cargos = '<option value="0">Ciudadano</option>' . $select_cargos; }

		if ((!$hilo)) { 
			if ($edit) { $get = 'editar'; } else { $get = 'hilo'; } 

// <input type="hidden" name="return_url" value="' . $return_url . '"  />

			$html .= '<div id="enviar">
<form action="/accion.php?a=foro&b=' . $get . '" method="post">
<input type="hidden" name="subforo" value="' . $subforo . '"  />
<input type="hidden" name="return_url" value="' . $return_url . '"  />

<h2>Nuevo hilo</h2>

<p>T&iacute;tulo:<br />
<input name="title" size="60" maxlength="200" type="text" value="' . $edit_title . '" /></p>

<p>Mensaje:<br />
<textarea name="text" style="color: green; font-weight: bold; width: 570px; height: 250px;">' . $edit_text . '</textarea><br />
<span style="color:grey;font-size:12px;">Etiquetas HTML permitidas: &lt;img&gt;, &lt;b&gt;, &lt;i&gt;, &lt;s&gt;, videos incrustados, enlaces auto-linkeados.</span></p>

<p><input value="Enviar" type="submit"> En calidad de: <select name="encalidad" style="color:green;font-weight:bold;font-size:17px;">' . $select_cargos . '
</select></p>

</div>';
		} else {
			if ($edit) { $get = 'editar'; } else { $get = 'reply'; } 
			$html .= '<div id="enviar">
<form action="/accion.php?a=foro&b=' . $get . '" method="post">
<input type="hidden" name="subforo" value="' . $subforo . '"  />
<input type="hidden" name="hilo" value="' . $hilo . '"  />
<input type="hidden" name="return_url" value="' . $return_url . '"  />

<h2>Respuesta</h2>

<p>Mensaje:<br />
<textarea name="text" style="color: green; font-weight: bold; width: 570px; height: 250px;">' . $edit_text . '</textarea><br />
<span style="color:grey;font-size:12px;">Etiquetas HTML permitidas: &lt;img&gt;, &lt;b&gt;, &lt;i&gt;, &lt;s&gt;, videos incrustados, enlaces auto-linkeados.</span></p>

<p><input value="Enviar" type="submit"> En calidad de: <select name="encalidad" style="color:green;font-weight:bold;font-size:17px;">' . $select_cargos . '
</select></p>

</form>
</div>';
		}
		return $html;
	} else {
		
		if ($pol['estado'] == 'extranjero') {
			return '<p class="azul"><b>Las fronteras con  '.$pol['pais'].' están cerradas, no puedes participar.</a></b></p>'; 
		} else {
			return '<p class="azul"><b>Debes ser Ciudadano para participar, <a href="'.REGISTRAR.'">reg&iacute;strate aqu&iacute;!</a></b></p>'; 
		}

	}
}

function print_lateral($nick, $cargo, $time, $siglas='', $user_ID='', $avatar='', $cargo_ID='', $confianza='') {
	$extra = '';
	if ($cargo_ID == 99) { $cargo = 'Extranjero'; }
	if ($avatar == 'true') { $avatar = '<span class="flateral">' . avatar($user_ID, 40) . '</span>'; } else { $avatar = ''; }
	if ($cargo_ID) { $extra .= ' <img src="/img/cargos/' . $cargo_ID . '.gif" title="' . $cargo . '" />'; }
	if ($confianza != '') { $extra .= ' ' . confianza($confianza) . ' '; }
	
	return $avatar . '<b>' . crear_link($nick) . $extra . '</b><br /><span class="min"><acronym title="' . $time . '">' . duracion(time() - strtotime($time)) .  '</acronym> ' . $siglas . '</span><br /><br />';
}







/*
pol_foros			(`ID` `url` `title` `descripcion` `acceso` `time` `estado`)
pol_foros_hilos		(`ID` `sub_ID``url` `user_ID` `title` `time` `time_last` `text` `cargo` `num`)
pol_foros_msg		(`ID``hilo_ID` `user_ID` `time` `text` `cargo`)
*/
if ($_GET['a'] == 'editar') {
	$txt .= '<h1><a href="/foro/">Foro</a>: editar</h1>';

	$txt .= foro_enviar($_GET['b'], $_GET['c'], true);

} elseif (($_GET['a'] == 'mis-respuestas') AND ($pol['user_ID'])) {


	$txt_title = 'Foro - Mis respuestas';
	$txt .= '<h1><a href="/foro/">Foro</a>: <a href="/foro/ultima-actividad/"><b>Ultima actividad</b></a> |  <b>Mis respuestas</b></h1>
<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">

<tr class="amarillo"><td colspan="4"><h2 style="font-size:18px;padding:8px;">Respuestas en tus ultimos mensajes:</h2></tr>';

	$result = mysql_query("SELECT ID, url FROM ".SQL."foros", $link);
	while($row = mysql_fetch_array($result)) { $sub[$row['ID']] = $row['url']; }

	$result = mysql_query("SELECT ID, hilo_ID, user_ID, time, text, cargo,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad,
(SELECT url FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_url,
(SELECT title FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_titulo,
(SELECT sub_ID FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS sub_ID
FROM ".SQL."foros_msg
WHERE hilo_ID != '-1' AND user_ID = '".$pol['user_ID']."'
ORDER BY time DESC
LIMIT 50", $link);
	while($row = mysql_fetch_array($result)) {

		$result2 = mysql_query("SELECT COUNT(*) AS resp_num FROM ".SQL."foros_msg WHERE hilo_ID = '".$row['hilo_ID']."' AND time > '".$row['time']."'", $link);
		while($row2 = mysql_fetch_array($result2)) {
			$resp_num = $row2['resp_num'];
		}

		if (!$repes[$row['hilo_ID']]) {
			$repes[$row['hilo_ID']] = true;
			$txt .= '<tr><td align="right" valign="top" colspan="2">' . print_lateral($row['nick'], $row['encalidad'], $row['time'], '', '', '', $row['cargo']) . '</td><td align="right" valign="top"><acronym title="Nuevos mensajes"><b style="font-size:18px;">'.$resp_num.'</b></acronym></td><td valign="top" colspan="2" nowrap="nowrap" style="color:grey;"><a href="/foro/' . $sub[$row['sub_ID']] . '/' . $row['hilo_url'] . '"><b>' . $row['hilo_titulo'] . '</b></a> &nbsp; (<b style="font-size:18px;">'.$resp_num.'</b></span> mensajes nuevos)<br />' . substr(strip_tags($row['text']), 0, 90) . '..</td></tr>';
		}
	}


	$txt .= '</table>';


} elseif ($_GET['a'] == 'ultima-actividad') {


	$txt_title = 'Foro - Ultima actividad';
	$txt .= '<h1><a href="/foro/">Foro</a>: <b>Ultima actividad</b> | <a href="/foro/mis-respuestas/">Mis respuestas</a></h1>
<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">

<tr class="amarillo"><td colspan="4"><h2 style="font-size:18px;padding:8px;">Ultimos 25 mensajes:</h2></tr>';

	$result = mysql_query("SELECT ID, url FROM ".SQL."foros", $link);
	while($row = mysql_fetch_array($result)) {
		$sub[$row['ID']] = $row['url'];
	}

	$result = mysql_query("SELECT ID, hilo_ID, user_ID, time, text, cargo,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad,
(SELECT url FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_url,
(SELECT title FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_titulo,
(SELECT sub_ID FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS sub_ID
FROM ".SQL."foros_msg
WHERE hilo_ID != '-1' AND estado = 'ok'
ORDER BY time DESC
LIMIT 25", $link);
	while($row = mysql_fetch_array($result)) {
		$txt .= '<tr><td align="right" valign="top" colspan="2">' . print_lateral($row['nick'], $row['encalidad'], $row['time'], '', '', '', $row['cargo']) . '</td><td valign="top" colspan="2"><p style="text-align:justify;margin:1px;"><a href="/foro/' . $sub[$row['sub_ID']] . '/' . $row['hilo_url'] . '"><b>' . $row['hilo_titulo'] . '</b></a><br />' . $row['text'] . '</p></td></tr>';
	}


	$txt .= '</table>';




} elseif ($_GET['b']) {			//foro/subforo/hilo-prueba/

	$result = mysql_query("SELECT ID, sub_ID, user_ID, url, title, time, time_last, text, cargo, num, 
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT estado FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS estado,
(SELECT avatar FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID = ".SQL_USERS.".partido_afiliado LIMIT 1) FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_hilos.cargo LIMIT 1) AS encalidad,
(SELECT acceso_msg FROM ".SQL."foros WHERE ID = ".SQL."foros_hilos.sub_ID LIMIT 1) AS acceso_msg,
(SELECT voto_confianza FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS confianza
FROM ".SQL."foros_hilos
WHERE url = '" . $_GET['b'] . "' AND estado = 'ok'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)) {

		if ($row['estado'] != 'expulsado') {

			$subforo = $_GET['a'];
			$return_url = 'foro/' . $subforo . '/' . $row['url'] . '/';
			paginacion('hilo', '/'.$return_url, $row['ID'], $_GET['c'], $row['num']);
			
			if ($_GET['c']) { $pag_title = ' - P&aacute;gina: ' . $_GET['c']; }
			$txt_title = $row['title'] . ' - Foro: ' . ucfirst($_GET['a']) . $pag_title;
			$txt_description = $row['title'] . ' - Foro: ' . ucfirst($_GET['a']) . $pag_title;


			// acceso
			if ($pol['nivel'] >= $row['acceso_msg']) { $crear_hilo = '#enviar'; } else { $crear_hilo = ''; }




			$txt .= '<h1><a href="/foro/">Foro</a>: <a href="/foro/' . $_GET['a'] . '/">' . ucfirst($_GET['a']) . '</a> | <a href="/' . $return_url . '">' . $row['title'] . '</a></h1>

<p style="margin-bottom:4px;">' .  $p_paginas . ' &nbsp; ' . boton('Responder', $crear_hilo) . ' &nbsp; <b>' . $row['num'] . '</b> mensajes en este hilo creado hace <acronym title="' . $row['time'] . '">' . duracion(time() - strtotime($row['time'])) . '</acronym>.</p>



<table border="0" cellpadding="2" cellspacing="0" class="pol_table">';

			if (($pol['user_ID'] == $row['user_ID']) AND ($subforo != 'notaria')) { 
				// es tu post
				$editar = '<span style="float:right;">' . boton('Editar', '/foro/editar/' . $row['ID'] . '/') . '</span>'; 
			} elseif (($pol['cargo'] == 12) OR ($pol['cargo'] == 13)) { 
				// policia borra
				$editar = '<span style="float:right;">' . boton('Papelera', '/accion.php?a=foro&b=borrar&c=hilo&ID=' . $row['ID'] . '/', '&iquest;Quieres enviar a la PAPELERA este HILO y sus MENSAJES?') . '</span>'; 
			} else { $editar = ''; }


			$txt .= '<tr class="amarillo"><td align="right" valign="top">' . print_lateral($row['nick'], $row['encalidad'], $row['time'], $row['siglas'], $row['user_ID'], $row['avatar'], $row['cargo'], $row['confianza']) . '</td><td valign="top" width="80%"><p style="text-align:justify;">' . $editar . $row['text'] . '</p></td></tr>';

			$result2 = mysql_query("SELECT ID, hilo_ID, user_ID, time, text, cargo,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID = ".SQL_USERS.".partido_afiliado LIMIT 1) FROM ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad,
(SELECT voto_confianza FROM ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS confianza
FROM ".SQL."foros_msg
WHERE hilo_ID = '" . $row['ID'] . "' AND estado = 'ok'
ORDER BY time ASC
LIMIT " . $p_limit, $link);
			while($row2 = mysql_fetch_array($result2)) {

				if (($pol['user_ID'] == $row2['user_ID']) AND ($subforo != 'notaria') AND (strtotime($row2['time']) > (time() - 3600))) { 
					$editar = boton('Editar', '/foro/editar/' . $row2['hilo_ID'] . '/' . $row2['ID'] . '/') . boton('X', '/accion.php?a=foro&b=eliminarreply&ID=' . $row2['ID'] . '&hilo_ID=' . $row2['hilo_ID'], '&iquest;Est&aacute;s seguro de querer ELIMINAR tu MENSAJE?') . ' '; 
				} elseif (($pol['cargo'] == 12) OR ($pol['cargo'] == 13)) { 
					// policia borra
					$editar = boton('Papelera', '/accion.php?a=foro&b=borrar&c=mensaje&ID=' . $row2['ID'] . '/', '&iquest;Quieres enviar a la PAPELERA este MENSAJE?') . ' '; 
				} else { $editar = ''; }

				$txt .= '<tr id="m-' . $row2['ID'] . '"><td align="right" valign="top">' . print_lateral($row2['nick'], $row2['encalidad'], $row2['time'], $row2['siglas'], $row2['user_ID'], $row2['avatar'], $row2['cargo'], $row2['confianza']) . '</td><td valign="top"><p class="pforo"><span style="float:right;">' . $editar . '<a href="#m-' . $row2['ID'] . '">#</a></span>' . $row2['text'] . '</p></td></tr>';
			}
			$txt .= '</table> <p>' . $p_paginas . '</p>';

			if ($pol['nivel'] >= $row['acceso_msg']) { $txt .= foro_enviar($row['sub_ID'], $row['ID']); }

			if (!$pol['user_ID']) { $txt .= '<p class="azul"><b>Para poder participar en esta conversacion has de <a href="'.REGISTRAR.'">solicitar la Ciudadania en '.PAIS.'</a></b></p>'; }
			
			$txt .= '<br /><hr /><p>'.$row['title'].'. M&aacute;s hilos: ';
			$result2 = mysql_query("SELECT url, title, (SELECT url FROM ".SQL."foros WHERE ID = ".SQL."foros_hilos.sub_ID LIMIT 1) AS subforo FROM ".SQL."foros_hilos WHERE estado = 'ok' ORDER BY RAND() LIMIT 8", $link);
			while($row2 = mysql_fetch_array($result2)) {
				$txt .= '<a href="/foro/' . $row2['subforo'] . '/' . $row2['url'] . '/">' . $row2['title'] . '</a>, ';
			}
			$txt .= '<p>';
			
			$txt_header = '<style type="text/css">.content-in hr { border: 1px solid grey; } .flateral { margin:0 0 0 5px; float:right; } .pforo { text-align:justify; margin:2px; }</style>';
		}
	}

} elseif ($_GET['a'] == 'papelera') { //foro/papelera/

	$txt_title = 'Papelera';
	$txt .= '<h1><a href="/foro/">Foro</a>: Papelera</h1>
<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
<tr class="azul"><td colspan="4"><h2 style="color:red;font-size:22px;padding:8px;">Hilos</h2></tr>';



	$result = mysql_query("SELECT ID, sub_ID, user_ID, url, title, time, time_last, text, cargo, num, 
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID = ".SQL_USERS.".partido_afiliado LIMIT 1) FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_hilos.cargo LIMIT 1) AS encalidad
FROM ".SQL."foros_hilos
WHERE estado = 'borrado'
ORDER BY time_last DESC", $link);
	while($row = mysql_fetch_array($result)) {
		if (($pol['cargo'] == 12) OR ($pol['cargo'] == 13)) { $boton = boton('Restaurar', '/accion.php?a=foro&b=restaurar&c=hilo&ID=' . $row['ID'], '&iquest;Quieres RESTAURAR este HILO y sus MENSAJES?'); } else { $boton = boton('Restaurar'); }

		$txt .= '<tr><td align="right" valign="top">' . print_lateral($row['nick'], $row['encalidad'], $row['time'], $row['siglas'], $row['user_ID'], $row['avatar'], $row['cargo']) . '</td><td valign="top"><p class="pforo"><b style="color:blue;">' . $row['title'] . '</b><br />' . $row['text'] . '</p></td><td valign="top" nowrap="nowrap"><acronym title="' . $row['time_last'] . '">' . duracion(time() - strtotime($row['time_last'])) . '</acronym></td><td valign="top">' . $boton . '</td></tr>';
	}

$txt .= '<tr><td><br /></td></tr><tr class="azul"><td colspan="4"><h2 style="color:red;font-size:22px;padding:8px;">Mensajes</h2></tr>';



	$result = mysql_query("SELECT ID, hilo_ID, user_ID, time, time2, text, cargo, 
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID = ".SQL_USERS.".partido_afiliado LIMIT 1) FROM ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad
FROM ".SQL."foros_msg
WHERE estado = 'borrado'
ORDER BY time2 DESC", $link);
	while($row = mysql_fetch_array($result)) {
		if (($pol['cargo'] == 12) OR ($pol['cargo'] == 13)) { $boton = boton('Restaurar', '/accion.php?a=foro&b=restaurar&c=mensaje&ID=' . $row['ID'], '&iquest;Quieres RESTAURAR este MENSAJE?'); } else { $boton = boton('Restaurar'); }

		$txt .= '<tr><td align="right" valign="top">' . print_lateral($row['nick'], $row['encalidad'], $row['time'], $row['siglas'], $row['user_ID'], $row['avatar'], $row['cargo']) . '</td><td valign="top"><p class="pforo">' . $row['text'] . '</p></td><td valign="top" nowrap="nowrap"><acronym title="' . $row['time2'] . '">' . duracion(time() - strtotime($row['time2'])) . '</acronym></td><td valign="top">' . $boton . '</td></tr>';
	}


	$txt .= '</table><br /><p class="gris">Los mensajes se eliminar&aacute;n tras 10 d&iacute;as.</p>';

	$txt_header = '<style type="text/css">.content-in hr { border: 1px solid grey; } .flateral { margin:0 0 0 5px; float:right; } .pforo { text-align:justify; font-size:11px; margin:2px; }</style>';



} elseif ($_GET['a']) {	//foro/subforo/

	$result = mysql_query("SELECT ID, url, title, descripcion, acceso, time
FROM ".SQL."foros
WHERE url = '" . $_GET['a'] . "' AND estado = 'ok'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)) {
		$return_url = 'foro/' . $_GET['a'] . '/';
		
		$txt_title = 'Foro: ' . ucfirst($_GET['a']) . ' - ' . $row['descripcion'];

		if ($pol['nivel'] >= $row['acceso']) { $crear_hilo = '#enviar'; } else { $crear_hilo = ''; }

		$txt .= '<h1><a href="/foro/">Foro</a>: <a href="/foro/' . $_GET['a'] . '/">' . ucfirst($_GET['a']) . '</a></h1>

<p style="margin-bottom:0;">' . boton('Crear Hilo', $crear_hilo) . ' (' . $row['descripcion'] . ')</p>

<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
<tr>
<th>Autor</th>
<th></th>
<th>Hilo</th>
<th>Creado</th>
<th></th>
</tr>';
		$result2 = mysql_query("SELECT ID, url, user_ID, title, time, time_last, cargo, num, sub_ID,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT estado FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS estado
FROM ".SQL."foros_hilos
WHERE sub_ID = '" . $row['ID'] . "' AND estado = 'ok'
ORDER BY time_last DESC
LIMIT 200", $link);
		while($row2 = mysql_fetch_array($result2)) {

			if ($row2['estado'] != 'expulsado') {
				if (strtotime($row2['time']) < (time() - 432000)) { 
					$titulo = '<a href="/foro/' . $row['url'] . '/' . $row2['url'] . '/">' . $row2['title'] . '</a>'; 
				} else { 
					$titulo = '<a href="/foro/' . $row['url'] . '/' . $row2['url'] . '/"><b>' . $row2['title'] . '</b></a>'; 
				}
				if (strtotime($row2['time']) > (time() - 86400)) { $titulo = $titulo . ' <sup style="font-size:9px;color:red;">Nuevo!</sup>'; }

				if (($pol['user_ID'] == $row2['user_ID']) AND ($pol['nivel'] >= $row['acceso'])) { $editar = ' ' . boton('X', '/accion.php?a=foro&b=eliminarhilo&ID=' . $row2['ID'], '&iquest;Est&aacute;s seguro de querer ELIMINAR este HILO?'); } else { $editar = ''; }
				$txt .= '<tr><td align="right">' . crear_link($row2['nick']) . '</td><td align="right"><b>' . $row2['num'] . '</b></td><td>' . $titulo . '</td><td align="right">' . duracion(time() - strtotime($row2['time'])) . '</td><td>' . $editar . '</td></tr>';
			}
		}
		$txt .= '</table><br />';
		if ($pol['nivel'] >= $row['acceso']) { $txt .= foro_enviar($row['ID']); }
	}


} else {						//foro/

	$adsense_exclude = true;

	$txt_title = 'Foro';
	$txt .= '<h1><b>Foro</b>: <a href="/foro/ultima-actividad/">Ultima actividad</a> | <a href="/foro/mis-respuestas/">Mis respuestas</a></h1>
<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">';

	$result = mysql_query("SELECT ID, url, title, descripcion, acceso,
(SELECT COUNT(*) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID LIMIT 1) AS num
FROM ".SQL."foros
WHERE estado = 'ok'
ORDER BY time ASC", $link);
	while($row = mysql_fetch_array($result)) {
		if ($pol['nivel'] >= $row['acceso']) { $crear_hilo = boton('Crear Hilo', '/foro/' . $row['url'] . '/#enviar'); } else { $crear_hilo = boton('Crear Hilo'); }
		if ($row['acceso'] > 1) { $el_acceso = '(Nivel: ' . $row['acceso'] . ')'; } else { $el_acceso = ''; }
		$txt .= '<tr class="amarillo">
<td nowrap="nowrap"><h2><a href="/foro/' . $row['url'] . '/" style="font-size:22px;margin-left:8px;"><b>' . $row['title'] . '</b></a></h2></td>
<td align="right"><b style="font-size:19px;">' . $row['num'] . '</b></td>
<td style="color:green;">' . $row['descripcion'] . '</td>
<td align="right" style="color:grey;">' . $el_acceso . '</td>
<td align="right" width="10%">' . $crear_hilo . '</td>
</tr>';

		if ($row['num'] > 100) { $num_limit = 12; } 
		elseif ($row['num'] > 50) { $num_limit = 8; }
		else { $num_limit = 4; }

		$result2 = mysql_query("SELECT ID, url, user_ID, title, time, time_last, cargo, num,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT estado FROM ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS estado
FROM ".SQL."foros_hilos
WHERE sub_ID = '" . $row['ID'] . "' AND estado = 'ok'
ORDER BY time_last DESC
LIMIT " . $num_limit, $link);
		while($row2 = mysql_fetch_array($result2)) {
			if ($row2['estado'] != 'expulsado') {
				$hilo_url[$row2['ID']] = '<a href="/foro/' . $row['url'] . '/' . $row2['url'] . '/">' . $row2['title'] . '</a>';
				if (strtotime($row2['time']) < (time() - 432000)) { $titulo = $hilo_url[$row2['ID']]; } else { $titulo = '<b>' . $hilo_url[$row2['ID']] . '</b>'; }
				if (strtotime($row2['time']) > (time() - 86400)) { $titulo = $titulo . ' <sup style="font-size:9px;color:red;">Nuevo!</sup>'; }
				$txt .= '<tr><td align="right" valign="top">' . crear_link($row2['nick']) . '</td><td valign="top" align="right"><b>' . $row2['num'] . '</b></td><td colspan="2">' . $titulo . '</td><td align="right" valign="top">' . duracion(time() - strtotime($row2['time'])) . '</td></tr>';
			}
		}
		$txt .= '<tr><td colspan="4">&nbsp;</td></tr>';
	}


		$txt .= '<tr class="amarillo">
<td width="120"><h2><a href="/foro/papelera/" style="font-size:22px;margin-left:8px;">Papelera</a></h2></td>
<td align="right"><b style="font-size:19px;"></b></td>
<td style="color:green;">Cuarentena de mensajes eliminados, 10 dias.</td>
<td align="right" style="color:grey;"></td>
<td align="right" width="10%"></td>
</tr>
</table>';

}


//THEME
include('theme.php');
?>
