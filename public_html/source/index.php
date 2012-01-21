<?php 
include('inc-login.php');

$txt_description = $pol['config']['pais_des'].'. '.PAIS.' es una comunidad de VirtualPol. Una comunidad de Internet auto-gestionada democr&aacute;ticamente. Puedes registrarte y ser ciudadano, hablar de politica en nuestros foros, conversar en nuestros chats o una infinidad de actividades.';

if  (($pol['estado'] == 'turista'))  { // turista

	$time_pre = date('Y-m-d H:i:00', time() - 3600); //15m 
	$result = mysql_query("SELECT nick 
FROM ".SQL_USERS." 
WHERE estado = 'ciudadano' AND fecha_last > '" . $time_pre . "' AND pais = '".PAIS."'
ORDER BY fecha_last DESC", $link);
	while($row = mysql_fetch_array($result)){ $li_online_num++; if ($li_online) { $li_online .= ', '; } $li_online .= crear_link($row['nick']); }

	$result = mysql_query("SELECT siglas FROM ".SQL."partidos 
ORDER BY fecha_creacion ASC", $link);
	while($row = mysql_fetch_array($result)){ 
		$li_partidos_num++; 
		if ($li_partidos_num <= 10) {
			if ($li_partidos) { $li_partidos .= ', '; } 
			$li_partidos .= crear_link($row['siglas'], 'partido');
		}
	}

	if ($pol['config']['elecciones_estado'] == 'normal') {  
		$li_elecciones_num = 'en ' . duracion(strtotime($pol['config']['elecciones_inicio']) - time());
		$li_elecciones = '<a href="/elecciones/">Escrutinio, esca&ntilde;os y nuevo Gobierno</a>';
	} elseif ($pol['config']['elecciones_estado'] == 'elecciones') {  

		$fecha_24_antes = date('Y-m-d H:i:00', strtotime($pol['config']['elecciones_inicio']) - $pol['config']['elecciones_antiguedad']);
		$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL_USERS." WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_registro < '" . $fecha_24_antes . "'", $link);
		while($row = mysql_fetch_array($result)) { $num_votantes = $row['num']; }
		$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."elecciones", $link);
		while($row = mysql_fetch_array($result)) { $num_votos = $row['num']; }

		$elecciones_quedan = duracion((strtotime($pol['config']['elecciones_inicio']) + $pol['config']['elecciones_duracion']) - time());
		$li_elecciones_num = 'En curso';
	}

	$txt .= '<h1>Bienvenido a '.PAIS.'</h1>
<br />
<table border="0"><tr><td width="50%" valign="top">

<h2>&iquest;Qu&eacute; es '.PAIS.'?</h2>
<p style="text-align:justify;">'.PAIS.' es un Pais auto-gestionado Democr&aacute;ticamente de la comunidad <a href="http://www.virtualpol.com">VirtualPol</a>.</p>

<p style="text-align:justify;">El Gobierno Democr&aacute;tico se compone de un Presidente, Diputados, Jueces, Polic&iacute;as, Leyes, Juicios, Periodistas, Abogados, etc... sin la existencia de usuarios privilegiados (god).</p>

<p style="text-align:justify;">En '.PAIS.' todos los ciudadanos est&aacute;n en absoluta igualdad, con las mismas oportunidades para el liderazgo o el fracaso en la b&uacute;squeda del Poder.</p>

<p>'.boton('Reg&iacute;strate!', 'http://www.virtualpol.com/registrar/').'</p>

</td><td valign="top">
	
<h2>Informaci&oacute;n Global</h2>
<ul id="info">
<li>Ciudadanos online: <b>' . $li_online_num . '<br />' . $li_online . '</b></li>
<li>Partidos Pol&iacute;ticos: <b>' . $li_partidos_num . '<br />' . $li_partidos . '</b></li>
<li><a href="/elecciones/">Elecciones Generales</a> <b>' . $li_elecciones_num . '</b>.</li>
</ul>


</td></tr></table>';

	$txt_header .= '<style type="text/css">#info li { margin-top:10px; } </style>';

}


// CHAT PLAZA
$_GET['a'] = strtolower(PAIS);
include('inc-chats.php');



if ((isset($pol['user_ID'])) AND ($pol['config']['palabra_gob'] != ':') AND ($pol['config']['palabra_gob'] != '')) {
	$palabra_gob = explode(':', $pol['config']['palabra_gob']);
	$txt .= '<div style="margin:10px 0 0 0;"><div class="azul"><b><a href="http://'.$palabra_gob[1].'">'.$palabra_gob[0].'</a></b></div></div>';
}

//THEME
include('theme.php');
?>