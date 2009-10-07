<?php 
include('inc-login.php');
/*
http://pol.teoriza.com/blog/
http://pol.teoriza.com/blog/nombre/
http://pol.teoriza.com/blog/nombre/admin/
http://pol.teoriza.com/blog/nombre/123/nombre-post/
http://pol.teoriza.com/blog/nombre/rss/

pol_blog		(blog_ID, url, user_ID, acceso, nombre, titulo, descripcion, time, time_last, tipo, estado)
pol_blog_post	(post_ID, blog_ID, user_ID, url, titulo, texto, tags, num_com, estado)
pol_blog_com	(com_ID, post_ID, blog_ID, user_ID, nick, time, texto, estado)
*/


if ($_GET['b'] == 'admin') {		// BLOG ADMIN
	$txt .= 'blog admin';



} elseif ($_GET['b'] == 'rss') {	// BLOG RSS
	$txt .= 'blog rss';



} elseif ($_GET['c']) {				// BLOG POST
	$txt .= 'blog post';



} elseif ($_GET['a']) {				// BLOG HOME
	
	$re = mysql_query("SELECT ID_estudio, 
(SELECT nombre FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nombre,
(SELECT nivel FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nivel
FROM ".SQL."estudios_users  WHERE estado = 'ok' AND cargo = '1' AND user_ID = '" . $pol['user_ID'] . "'
ORDER BY nivel DESC", $link);
	while($r = mysql_fetch_array($re)){
		$txt .= 'blog home';
	}

} else {							// HOME
	$txt .= 'home';



}












/*

// BORRAR BORRAR BORRAR BORRAR BORRAR BORRAR BORRAR BORRAR BORRAR BORRAR

function foro_enviar($subforo, $hilo=null, $edit=null) {
	global $pol, $link, $return_url;

	if ($pol['estado'] == 'ciudadano') {
		if ($edit) { //editar
			$return_url = '/foro/';
			if ($hilo) { //msg
				$result = mysql_query("SELECT text, cargo FROM ".SQL."foros_msg WHERE ID = '" . $hilo . "' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
				while($row = mysql_fetch_array($result)){ $edit_text = $row['text']; $edit_cargo = $row['cargo']; }
			} else { //hilo
				$result = mysql_query("SELECT sub_ID, text, cargo, title FROM ".SQL."foros_hilos WHERE ID = '" . $subforo . "' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
				while($row = mysql_fetch_array($result)){ $edit_title = $row['title']; $edit_text = $row['text']; $edit_cargo = $row['cargo']; }
			}
			$edit_text = strip_tags($edit_text, "<img>,<b>,<i>");
		}

		if ($pol['nivel'] > 1) {
			$result = mysql_query("SELECT ID_estudio, 
(SELECT nombre FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nombre,
(SELECT nivel FROM ".SQL."estudios WHERE ".SQL."estudios.ID = ID_estudio LIMIT 1) AS nivel
FROM ".SQL."estudios_users  WHERE estado = 'ok' AND cargo = '1' AND user_ID = '" . $pol['user_ID'] . "'
ORDER BY nivel DESC", $link);
			while($row = mysql_fetch_array($result)){
				if ($edit_cargo == $row['ID_estudio']) { $selected = ' selected="selected"'; } else { $selected = ''; }
				$select_cargos .= '<option value="' . $row['ID_estudio'] . '"' . $selected . '>' . $row['nombre'] . '</option>' . "\n";
			}
		}

		if ((!$hilo)) { 
			if ($edit) { $get = 'editar'; } else { $get = 'hilo'; } 
			$html .= '<div id="enviar">
<form action="/accion.php?a=foro&b=' . $get . '" method="post">
<input type="hidden" name="subforo" value="' . $subforo . '"  />
<input type="hidden" name="return_url" value="' . $return_url . '"  />

<h2>Nuevo hilo</h2>

<p>T&iacute;tulo:<br />
<input name="title" size="60" maxlength="200" type="text" value="' . $edit_title . '" /></p>

<p>Mensaje:<br />
<textarea name="text" style="color: green; font-weight: bold; width: 570px; height: 250px;">' . $edit_text . '</textarea></p>

<p><input value="Enviar" type="submit"> En calidad de: <select name="encalidad" style="color:green;font-weight:bold;font-size:17px;"><option value="0">Ciudadano</option>' . $select_cargos . '
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
<textarea name="text" style="color: green; font-weight: bold; width: 570px; height: 250px;">' . $edit_text . '</textarea></p>

<p><input value="Enviar" type="submit"> En calidad de: <select name="encalidad" style="color:green;font-weight:bold;font-size:17px;"><option value="0">Ciudadano</option>' . $select_cargos . '
</select></p>

</form>
</div>';
		}
		return $html;
	} else { return '<p><b>Debes ser Ciudadano para participar, <a href="http://www.teoriza.com/registrar/">reg&iacute;strate aqu&iacute;!</a></b></p>'; }
}

function print_lateral($nick, $cargo, $time, $siglas='', $user_ID='',$avatar='') {
	if ($cargo) { $cargo = '<br />' . $cargo; }
	if ($avatar == 'true') { $html .= '<span class="flateral">' . avatar($user_ID, 40) . '</span>'; }
	$html .= '<b>' . crear_link($nick) . $cargo . '</b><br /><span class="min"><acronym title="' . $time . '">' . duracion(time() - strtotime($time)) .  '</acronym> ' . $siglas . '</span><br /><br />';
	return $html;
}







if ($_GET['a'] == 'editar') {
	$txt .= '<h1><a href="/foro/">Foro</a>: editar</h1>';

	$txt .= foro_enviar($_GET['b'], $_GET['c'], true);
} elseif ($_GET['a'] == 'ultima-actividad') {


	$txt_title = 'Foro - Ultima actividad';
	$txt .= '<h1><a href="/foro/">Foro</a>: <b>Ultima Actividad</b></h1>
<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">

<tr class="amarillo"><td colspan="4"><h2 style="font-size:18px;padding:8px;">Ultimas 25 respuestas:</h2></tr>';

	$result = mysql_query("SELECT ID, url FROM ".SQL."foros", $link);
	while($row = mysql_fetch_array($result)) {
		$sub[$row['ID']] = $row['url'];
	}

	$result = mysql_query("SELECT ID, hilo_ID, user_ID, time, text,
(SELECT nick FROM  ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad,
(SELECT url FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_url,
(SELECT title FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_titulo,
(SELECT sub_ID FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS sub_ID
FROM ".SQL."foros_msg
WHERE hilo_ID != '-1'
ORDER BY time DESC
LIMIT 25", $link);
	while($row = mysql_fetch_array($result)) {
		$txt .= '<tr><td align="right" valign="top" colspan="2">' . print_lateral($row['nick'], $row['encalidad'], $row['time']) . '</td><td valign="top" colspan="2"><p style="text-align:justify;margin:1px;"><a href="/foro/' . $sub[$row['sub_ID']] . '/' . $row['hilo_url'] . '">' . $row['hilo_titulo'] . '</a><br />' . $row['text'] . '</p></td></tr>';
	}


	$txt .= '</table>';




} elseif ($_GET['b']) {			//foro/subforo/hilo-prueba/

	$result = mysql_query("SELECT ID, sub_ID, user_ID, url, title, time, time_last, text, cargo, num, 
(SELECT nick FROM  ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM  ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID =  ".SQL_USERS.".partido_afiliado LIMIT 1) FROM  ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_hilos.cargo LIMIT 1) AS encalidad
FROM ".SQL."foros_hilos
WHERE url = '" . $_GET['b'] . "'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)) {
		$subforo = $_GET['a'];
		$return_url = '/foro/' . $subforo . '/' . $row['url'] . '/';
		paginacion('hilo', $return_url, $row['ID'], $_GET['c'], $row['num']);
		
		if ($_GET['c']) { $pag_title = ' - P&aacute;gina: ' . $_GET['c']; }
		$txt_title = $row['title'] . ' - Foro: ' . ucfirst($_GET['a']) . $pag_title;
		$txt .= '<h1><a href="/foro/">Foro</a>: <a href="/foro/' . $_GET['a'] . '/">' . ucfirst($_GET['a']) . '</a> | <a href="' . $return_url . '">' . $row['title'] . '</a></h1>

<p style="margin-bottom:0;">' .  $p_paginas . ' &nbsp; ' . boton('Responder', '#enviar') . ' &nbsp; <b>' . $row['num'] . '</b> respuestas, creado el <em>' . explodear(' ', $row['time'], 0) . '</em>.</p>



<table border="0" cellpadding="2" cellspacing="0" class="pol_table">';
		if (($pol['user_ID'] == $row['user_ID']) AND ($row['sub_ID'] != 5)) { $editar = '<span style="float:right;">' . boton('Editar', '/foro/editar/' . $row['ID'] . '/') . '</span>'; } else { $editar = ''; }
		$txt .= '<tr class="amarillo"><td align="right" valign="top">' . print_lateral($row['nick'], $row['encalidad'], $row['time'], $row['siglas'], $row['user_ID'], $row['avatar']) . '</td><td valign="top" width="80%"><p style="text-align:justify;">' . $editar . $row['text'] . '</p></td></tr>';

		$result2 = mysql_query("SELECT ID, hilo_ID, user_ID, time, text, cargo,
(SELECT nick FROM  ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM  ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID =  ".SQL_USERS.".partido_afiliado LIMIT 1) FROM  ".SQL_USERS." WHERE ID = ".SQL."foros_msg.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM ".SQL."estudios WHERE ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad
FROM ".SQL."foros_msg
WHERE hilo_ID = '" . $row['ID'] . "'
ORDER BY time ASC
LIMIT " . $p_limit, $link);
		while($row2 = mysql_fetch_array($result2)) {
			if (($pol['user_ID'] == $row2['user_ID']) AND ($row['sub_ID'] != 5)) { $editar = boton('Editar', '/foro/editar/' . $row2['hilo_ID'] . '/' . $row2['ID'] . '/') . boton('X', '/accion.php?a=foro&b=eliminarreply&ID=' . $row2['ID'] . '&hilo_ID=' . $row2['hilo_ID'], '&iquest;Est&aacute;s seguro de querer ELIMINAR este MENSAJE?'); } else { $editar = ''; }
			$txt .= '<tr id="m-' . $row2['ID'] . '"><td align="right" valign="top">' . print_lateral($row2['nick'], $row2['encalidad'], $row2['time'], $row2['siglas'], $row2['user_ID'], $row2['avatar']) . '</td><td valign="top"><p class="pforo"><span style="float:right;">' . $editar . '<a href="#m-' . $row2['ID'] . '">#</a></span>' . $row2['text'] . '</p></td></tr>';
		}
		$txt .= '</table> <p>' . $p_paginas . '</p>' . foro_enviar($row['sub_ID'], $row['ID']);


		
		$txt .= '<p>Otros hilos: ';
		$result2 = mysql_query("SELECT url, title, 
(SELECT url FROM ".SQL."foros WHERE ID = ".SQL."foros_hilos.sub_ID LIMIT 1) AS subforo
FROM ".SQL."foros_hilos
ORDER BY RAND()
LIMIT 8", $link);
		while($row2 = mysql_fetch_array($result2)) {
			$txt .= '<a href="/foro/' . $row2['subforo'] . '/' . $row2['url'] . '/">' . $row2['title'] . '</a>, ';
		}
		$txt .= '<p>';
		
		$txt_header = '<style type="text/css">.flateral { margin:0 0 0 5px; float:right; } .pforo { text-align:justify; margin:2px; }</style>';
	}






} elseif ($_GET['a']) {	//foro/subforo/

	$result = mysql_query("SELECT ID, url, title, descripcion, acceso, time
FROM ".SQL."foros
WHERE url = '" . $_GET['a'] . "' AND estado = 'ok'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)) {
		$return_url = '/foro/' . $_GET['a'] . '/';
		
		$txt_title = 'Foro: ' . ucfirst($_GET['a']) . ' - ' . $row['descripcion'];
		$txt .= '<h1><a href="/foro/">Foro</a>: <a href="/foro/' . $_GET['a'] . '/">' . ucfirst($_GET['a']) . '</a></h1>

<p style="margin-bottom:0;">' . boton('Crear Hilo', '#enviar') . ' (' . $row['descripcion'] . ')</p>

<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
<tr>
<th>Autor</th>
<th></th>
<th>Hilo</th>
<th>Creado</th>
<th></th>
</tr>';
		$result2 = mysql_query("SELECT ID, url, user_ID, title, time, time_last, cargo, num, sub_ID,
(SELECT nick FROM  ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick
FROM ".SQL."foros_hilos
WHERE sub_ID = '" . $row['ID'] . "'
ORDER BY time_last DESC
LIMIT 200", $link);
		while($row2 = mysql_fetch_array($result2)) {

			if (strtotime($row2['time']) < (time() - 432000)) { 
				$titulo = '<a href="/foro/' . $row['url'] . '/' . $row2['url'] . '/">' . $row2['title'] . '</a>'; 
			} else { 
				$titulo = '<a href="/foro/' . $row['url'] . '/' . $row2['url'] . '/"><b>' . $row2['title'] . '</b></a>'; 
			}
			if (strtotime($row2['time']) > (time() - 86400)) { $titulo = $titulo . ' <sup style="font-size:9px;color:red;">Nuevo!</sup>'; }

			if (($pol['user_ID'] == $row2['user_ID']) OR ($pol['user_ID'] == 1)) { $editar = ' ' . boton('X', '/accion.php?a=foro&b=eliminarhilo&ID=' . $row2['ID'], '&iquest;Est&aacute;s seguro de querer ELIMINAR este HILO?'); } else { $editar = ''; }
			$txt .= '<tr><td align="right">' . crear_link($row2['nick']) . '</td><td align="right"><b>' . $row2['num'] . '</b></td><td>' . $titulo . '</td><td align="right">' . duracion(time() - strtotime($row2['time'])) . '</td><td>' . $editar . '</td></tr>';
		}
		$txt .= '</table><br />';
		$txt .= foro_enviar($row['ID']);
	}





} else {						//foro/
	$txt_title = 'Foro';
	$txt .= '<h1><b>Foro</b>: <a href="/foro/ultima-actividad/">Ultima actividad</a></h1>
<br />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">';

	$result = mysql_query("SELECT ID, url, title, descripcion,
(SELECT COUNT(*) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID LIMIT 1) AS num
FROM ".SQL."foros
WHERE estado = 'ok'
ORDER BY time ASC", $link);
	while($row = mysql_fetch_array($result)) {
		$txt .= '<tr class="amarillo"><td width="120"><h2><a href="/foro/' . $row['url'] . '/" style="font-size:22px;margin-left:8px;"><b>' . $row['title'] . '</b></a></h2></td><td align="right"><b style="font-size:19px;">' . $row['num'] . '</b></td><td style="color:green;">' . $row['descripcion'] . '</td><td align="right" width="10%">' . boton('Crear Hilo', '/foro/' . $row['url'] . '/#enviar') . '</td></tr>';

		if ($row['num'] > 100) { $num_limit = 12; } 
		elseif ($row['num'] > 50) { $num_limit = 8; }
		else { $num_limit = 4; }

		$result2 = mysql_query("SELECT ID, url, user_ID, title, time, time_last, cargo, num,
(SELECT nick FROM  ".SQL_USERS." WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick
FROM ".SQL."foros_hilos
WHERE sub_ID = '" . $row['ID'] . "'
ORDER BY time_last DESC
LIMIT " . $num_limit, $link);
		while($row2 = mysql_fetch_array($result2)) {
			$hilo_url[$row2['ID']] = '<a href="/foro/' . $row['url'] . '/' . $row2['url'] . '/">' . $row2['title'] . '</a>';
			if (strtotime($row2['time']) < (time() - 432000)) { $titulo = $hilo_url[$row2['ID']]; } else { $titulo = '<b>' . $hilo_url[$row2['ID']] . '</b>'; }
			if (strtotime($row2['time']) > (time() - 86400)) { $titulo = $titulo . ' <sup style="font-size:9px;color:red;">Nuevo!</sup>'; }
			$txt .= '<tr><td align="right" valign="top">' . crear_link($row2['nick']) . '</td><td valign="top" align="right"><b>' . $row2['num'] . '</b></td><td>' . $titulo . '</td><td align="right" valign="top">' . duracion(time() - strtotime($row2['time'])) . '</td></tr>';

		}
		$txt .= '<tr><td colspan="4">&nbsp;</td></tr>';
	}

	$txt .= '</table>';

}

$txt_header .= '<style type="text/css">h1 a { color:#4BB000; } #enviar {background:#FFFFB7;padding:20px 0 20px 50px;}</style>';
*/

//THEME
include('theme.php');
?>
