<?php 
include('inc-login.php');
/*
pol_ref			(`ID` `pregunta` `descripcion` `respuestas` `time` `time_expire` `user_ID` `estado`)
pol_ref_votos	(`ID` `user_ID``ref_ID` `voto`)
*/

$result = mysql_query("SELECT ID, tipo, num, pregunta FROM ".SQL."ref WHERE time_expire < '" . $date . "' AND estado = 'ok'", $link);
while($row = mysql_fetch_array($result)){

	include_once('inc-functions-accion.php');

	evento_chat('<b>[' . strtoupper($row['tipo']) . ']</b> Finalizado, resultados: <a href="/referendum/' . $row['ID'] . '/"><b>' . $row['pregunta'] . '</b></a> <span style="color:grey;">(votos: ' . $row['num'] . ')</span>');
	mysql_query("UPDATE ".SQL."ref SET estado = 'end' WHERE ID = '" . $row['ID'] . "' LIMIT 1", $link);

	// actualizar info en theme
	$result2 = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."ref WHERE estado = 'ok'", $link);
	while($row2 = mysql_fetch_array($result2)) {
		mysql_query("UPDATE ".SQL."config SET valor = '" . $row2['num'] . "' WHERE dato = 'info_consultas' LIMIT 1", $link);
	}
}





// load user cargos
$pol['cargos'] = cargos();


if ($_GET['a'] == 'crear') {
	$txt_title = 'Crear referendum o sondeos';



	$disabled['sondeo'] = ' disabled="disabled"';
	$disabled['referendum'] = ' disabled="disabled"';
	$disabled['parlamento'] = ' disabled="disabled"';

	if (($pol['nivel'] >= 95)) {
		$disabled['referendum'] = '';
		$disabled['sondeo'] = '';
	}
	if ($pol['cargos'][41]) {
		$disabled['sondeo'] = '';
	}
	if (($pol['cargos'][22]) OR ($pol['cargos'][6])) {
		$disabled['parlamento'] = '';
	}

/*
SONDEO >95 y 41
REFERENDUM SOLO >95
PARLAMENTO SOLO 22 y 6
*/


	$txt .= '<h1><a href="/referendum/">Consultas</a>: Crear</h1>
<form action="/accion.php?a=referendum&b=crear" method="post">

<p><b>Tipo</b>: 
<select name="tipo">
<option value="sondeo"' . $disabled['sondeo'] . '>SONDEO</option>
<option value="referendum"' . $disabled['referendum'] . '>REFERENDUM</option>
<option value="parlamento"' . $disabled['parlamento'] . '>PARLAMENTO</option>
</select></p>

<p><b>Pregunta</b>: 
<input type="text" name="pregunta" size="60" maxlength="70" /></p>

<p><b>Duraci&oacute;n</b>: 
<select name="time_expire">
<option value="300">5 min</option>
<option value="600">10 min</option>
<option value="1800">30 min</option>
<option value="3600">1 hora</option>
<option value="86400">24 horas (1 dia)</option>
<option value="172800">48 horas (2 dias)</option>
<option value="259200">72 horas (3 dias)</option>
</select></p>

<p><b>Descripci&oacute;n</b>:<br />
<textarea name="descripcion" style="color: green; font-weight: bold; width: 570px; height: 250px;"></textarea></p>

<p><b>Respuestas</b>:
<ol>
<li><input type="text" name="respuesta0" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta1" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta2" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta3" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta4" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta5" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta6" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta7" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta8" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta9" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta10" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta11" size="22" maxlength="30" /></li>
</ol></p>

<p><input type="submit" value="Crear Referendum/sondeo" /> &nbsp; <a href="/referendum/"><b>Ver votaciones</b></a></p>';




} elseif ($_GET['a']) {


	$result = mysql_query("SELECT ID, pregunta, descripcion, respuestas, time, time_expire, user_ID, estado, num, tipo,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."ref.user_ID LIMIT 1) AS nick, 
(SELECT ID FROM ".SQL."ref_votos WHERE ref_ID = ".SQL."ref.ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS ha_votado
FROM ".SQL."ref
WHERE ID = '" . $_GET['a'] . "'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)) {

		if ($row['tipo'] == 'parlamento') {
			$result2 = mysql_unbuffered_query("SELECT ID FROM ".SQL."estudios_users WHERE user_ID = '" . $pol['user_ID'] . "' AND cargo = '1' AND ID_estudio = '6' LIMIT 1", $link);
			while($row2 = mysql_fetch_array($result2)){ $es_diputado = true; }
		}

		$time_expire = strtotime($row['time_expire']);
		$time_creacion = strtotime($row['time']);
		$duracion = duracion($time_expire - $time_creacion);
		$respuestas = explode("|", $row['respuestas']);
		$respuestas_num = count($respuestas) - 1;
		$txt_title = 'Consulta: ' . strtoupper($row['tipo']) . ' | ' . $row['pregunta'];

		if ($row['estado'] == 'ok') { 
			$tiempo_queda =  ' | <span style="color:blue;">Queda ' . duracion($time_expire - time()) . '</span>'; 
		} else { $tiempo_queda =  ' | <span style="color:grey;">Finalizado</span>'; }


		$txt .= '<h1><a href="/referendum/">Consultas</a>: ' . strtoupper($row['tipo']) . ' | ' . $row['pregunta'] . $tiempo_queda . '</h1>

<br /><div class="amarillo"><p>' . $row['descripcion'] . '</p></div>

<p style="text-align:right;">Creado por <b>' . $row['nick'] . '</b> a fecha de <em>' . $row['time'] . '</em>, con una duraci&oacute;n de ' . $duracion . '.</p>';

		if ($time_expire < time()) { //ha terminado 
			if (($row['estado'] == 'ok') AND ($time_expire < time())) { 
				include_once('inc-functions-accion.php');
				evento_chat('<b>[' . strtoupper($row['tipo']) . ']</b> Finalizado, resultados: <a href="/referendum/' . $row['ID'] . '/"><b>' . $row['pregunta'] . '</b></a> <span style="color:grey;">(votos: ' . $row['num'] . ')</span>');
			}

			$txt .= '

<table border="0" cellpadding="0" cellspacing="0"><tr><td valign="top">
			
<h2>Escrutinio:</h2>
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
<tr>
<th>Respuestas &nbsp;</th>
<th>Votos</th>
<th></th>
</tr>';


			$result2 = mysql_query("SELECT COUNT(user_ID) as num, voto
FROM ".SQL."ref_votos
WHERE ref_ID = '" . $row['ID'] . "'
GROUP BY voto
ORDER BY num DESC", $link);
			while($row2 = mysql_fetch_array($result2)) {
				$txt .= '<tr><td>' . $respuestas[$row2['voto']] . '</td><td align="right"><b>' . $row2['num'] . '</b></td><td align="right">' . round(($row2['num'] * 100) / $row['num']) . '%</td></tr>';

				$escaños_total = $escaños_total + $row2['num'];
				if ($chart_dato) { $chart_dato .= ','; } $chart_dato .= $row2['num'];
				if ($chart_nom) { $chart_nom .= '|'; } $chart_nom .= $respuestas[$row2['voto']];

			}
			$txt .= '</table></td><td valign="top">';

			if ($row['tipo'] == 'parlamento') {
				$txt .= '<img src="http://chart.apis.google.com/chart?cht=p&chd=t:' . $escaños_total . ',' . $chart_dato . '&chs=450x300&chl=|' . $chart_nom . '&chco=FFFFFF,FF8000" alt="Escrutinio" />';
			} else {
				$txt .= '<img src="http://chart.apis.google.com/chart?cht=p&chd=t:' . $chart_dato . '&chs=440x200&chl=' . $chart_nom . '" alt="Escrutinio" />';
			}	
			$txt .= '</td></tr></table>';



		} else {
			if ((($row['tipo'] == 'parlamento') AND ($es_diputado)) OR ($row['tipo'] != 'parlamento')) {
				if (!$row['ha_votado']) {
					for ($i=0;$i<$respuestas_num;$i++) { if ($respuestas[$i]) { $votos .= '<option value="' . $i . '">' . $respuestas[$i] . '</option>'; } }
					$txt .= '<form action="/accion.php?a=referendum&b=votar" method="post">
<input type="hidden" name="ref_ID" value="' . $row['ID'] . '"  />
<p><select name="voto">
<option value="">&darr;</option>
' . $votos . '
</select>
<input type="submit" value="Votar" /></p>';
				} else {
					$txt .= 'Tu voto ha sido recogido correctamente.';
				}
			}
		}

		if ($row['tipo'] == 'parlamento') {
			$txt .= '
<table border="0" cellpadding="0" cellspacing="3" class="pol_table">
<tr>
<th>Diputado</th>
<th></th>
<th colspan="2">Voto</th>
</tr>';

			$result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID = ".SQL_USERS.".partido_afiliado LIMIT 1) AS las_siglas FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS siglas,
(SELECT voto FROM ".SQL."ref_votos WHERE ref_ID = '" . $row['ID'] . "' AND user_ID = ".SQL."estudios_users.user_ID LIMIT 1) AS ha_votado
FROM ".SQL."estudios_users
WHERE cargo = '1' AND ID_estudio = '6'
ORDER BY siglas ASC", $link);
			$txt .= mysql_error($link);
			while($row2 = mysql_fetch_array($result2)) {
				if ($row2['ha_votado'] != null) { $ha_votado = ' style="background:blue;"';
				} else { $ha_votado = ' style="background:red;"'; }
				$txt .= '<tr><td><img src="'.IMG.'cargos/6.gif" /> <b>' . crear_link($row2['nick']) . '</b></td><td><b>' . crear_link($row2['siglas'], 'partido') . '</b></td><td' . $ha_votado . '></td><td><b>' . $respuestas[$row2['ha_votado']]  . '</b></td></tr>';
			}
			$txt .= '</table>';

		}


	}

} else {

	$result = mysql_unbuffered_query("SELECT ID FROM ".SQL."estudios_users WHERE user_ID = '" . $pol['user_ID'] . "' AND cargo = '1' AND ID_estudio = '6' LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){ $es_diputado = true; }

	$txt_title = 'Consultas: sondeos, referendum y parlamento - sistema de votacion';
	$txt .= '<h1>Consultas: (Referendum, sondeos y parlamento)</h1>
<br />';


	if (($pol['nivel'] >= 95) OR ($pol['cargos']['41'])) { $txt .= '<p>' . boton('Crear consulta', '/referendum/crear/') . '</p>'; }
	
	$txt .= '<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
<tr>
<th></th>
<th>Tipo</th>
<th>Pregunta</th>
<th>Autor</th>
<th>Estado</th>
<th></th>
</tr>';
	$result = mysql_query("SELECT ID, pregunta, time, time_expire, user_ID, estado, num, tipo,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."ref.user_ID LIMIT 1) AS nick,
(SELECT ID FROM ".SQL."ref_votos WHERE ref_ID = ".SQL."ref.ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS ha_votado
FROM ".SQL."ref
ORDER BY estado ASC, time_expire DESC", $link);
	while($row = mysql_fetch_array($result)) {
		if ($row['estado'] == 'ok') { 
			$time_expire = strtotime($row['time_expire']);

			$estado =  '<span style="color:blue;">' . duracion($time_expire - time()) . '</span>'; 
		} else { $estado = '<span style="color:grey;">Finalizado</span>'; }
		if ((!$row['ha_votado']) AND ($row['estado'] == 'ok') AND ($pol['estado'] == 'ciudadano')) { 
			if (($row['tipo'] == 'parlamento') AND (!$es_diputado)) {
				$votar = '';
			} else {
				$votar = boton('Votar', '/referendum/' . $row['ID'] . '/');
			}
		} else { 
			$votar = '';
		}

		if ($row['user_ID'] == $pol['user_ID']) {
			$boton = boton('X', '/accion.php?a=referendum&b=eliminar&ID=' . $row['ID'], '&iquest;Seguro que quieres CANCELAR y ELIMINAR este referendum/sondeo?');
		} else { $boton = ''; }

		$txt .= '<tr><td align="right"><b>' . $row['num'] . '</b></td><td>' . ucfirst($row['tipo']) . '</td><td><a href="/referendum/' . $row['ID'] . '/"><b>' . $row['pregunta'] . '</b></a></td><td>' . crear_link($row['nick']) . '</td><td><b>' . $estado . '</b></td><td>' . $votar . $boton . '</td><td></td></tr>';
	}
	$txt .= '</table>';


}



//THEME
include('theme.php');
?>
