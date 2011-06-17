<?php 
include('inc-login.php');
/*
pol_ref			(`ID` `pregunta` `descripcion` `respuestas` `time` `time_expire` `user_ID` `estado`)
pol_ref_votos	(`ID` `user_ID``ref_ID` `voto`)
*/

$result = mysql_query("SELECT ID, tipo, num, pregunta FROM votacion WHERE time_expire < '".$date."' AND estado = 'ok' AND pais = '".PAIS."'", $link);
while($r = mysql_fetch_array($result)){

	include_once('inc-functions-accion.php');

	evento_chat('<b>['.strtoupper($r['tipo']).']</b> Finalizado, resultados: <a href="/votacion/'.$r['ID'].'/"><b>'.$r['pregunta'].'</b></a> <span style="color:grey;">(votos: <b>'.$r['num'].'</b>)</span>');

	mysql_query("UPDATE votacion SET estado = 'end' WHERE ID = '".$r['ID']."' LIMIT 1", $link);

	// actualizar info en theme
	$result2 = mysql_query("SELECT COUNT(ID) AS num FROM votacion WHERE estado = 'ok' AND pais = '".PAIS."'", $link);
	while($r2 = mysql_fetch_array($result2)) {
		mysql_query("UPDATE ".SQL."config SET valor = '".$r2['num']."' WHERE dato = 'info_consultas' LIMIT 1", $link);
	}
}





// load user cargos
$pol['cargos'] = cargos();


if ($_GET['a'] == 'crear') {
	$txt_title = 'Crear votacion';



	$disabled['sondeo'] = ' disabled="disabled"';
	$disabled['referendum'] = ' disabled="disabled"';
	$disabled['parlamento'] = ' disabled="disabled"';


	$sc = get_supervisores_del_censo();
	if (isset($sc[$pol['user_ID']])) { 
		$disabled['sondeo'] = '';
	}
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

/* NIVEL MINIMO PARA PODER CREAR:
SONDEO >95 y 41
REFERENDUM SOLO >95
PARLAMENTO SOLO 22 y 6
*/


	$txt .= '<h1><a href="/votacion/">Votaci&oacute;n</a>: Crear</h1>
<form action="/accion.php?a=votacion&b=crear" method="post">


<table width="570"><tr><td valign="top">


<p class="azul"><b>Tipo</b>: 
<select name="tipo">
<option value="sondeo"'.$disabled['sondeo'].' onclick="$(\'#acceso_votar_div\').show();">SONDEO</option>
<option value="referendum"'.$disabled['referendum'].' onclick="$(\'#acceso_votar_div\').show();">REFERENDUM</option>
<option value="parlamento"'.$disabled['parlamento'].' onclick="$(\'#acceso_votar_div\').hide();">PARLAMENTO</option>
</select><br /><br />

<b>Duraci&oacute;n</b>: 
<select name="time_expire">
<option value="300">5 minutos</option>
<option value="600">10 minutos</option>
<option value="1800">30 minutos</option>
<option value="3600">1 hora</option>
<option value="86400" selected="selected">24 horas</option>
<option value="172800">2 d&iacute;as</option>
<option value="259200">3 d&iacute;as</option>
<option value="345600">4 d&iacute;as</option>
</select></p>
';


		$r['acceso_votar'] = 'ciudadanos_pais';
		$tipos_array = nucleo_acceso('print');
		unset($tipos_array['anonimos']);
		foreach ($tipos_array AS $at => $at_var) {
			$txt_li['votar'] .= '<input type="radio" name="acceso_votar" value="'.$at.'"'.($at==$r['acceso_votar']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_votar_var\').val(\''.$at_var.'\');" /> '.ucfirst(str_replace("_", " ", $at)).'<br />';
		}


		$txt .= '</td><td valign="top" align="right">
		
<p id="acceso_votar_div" class="azul"><b>Acceso para votar:</b><br />
'.$txt_li['votar'].' <input type="text" name="acceso_cfg_votar" size="18" maxlength="500" id="acceso_cfg_votar_var" value="'.$r['acceso_cfg_votar'].'" /></p>

</td></tr></table>

<p><b>Pregunta</b>: 
<input type="text" name="pregunta" size="57" maxlength="70" /></p>

<p><b>Descripci&oacute;n</b>: (siempre visible)<br />
<textarea name="descripcion" style="color: green; font-weight: bold; width: 570px; height: 250px;"></textarea></p>

<p><b>Respuestas</b>:
<ol>
<li><input type="text" name="respuesta0" size="22" maxlength="30" value="SI" /></li>
<li><input type="text" name="respuesta1" size="22" maxlength="30" value="NO" /></li>
<li><input type="text" name="respuesta2" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta3" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta4" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta5" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta6" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta7" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta8" size="22" maxlength="30" /></li>
<li><input type="text" name="respuesta9" size="22" maxlength="30" /></li>
</ol>
<ul style="margin-top:-16px;">
<li><input type="text" name="respuesta10" size="22" maxlength="30" value="En Blanco" readonly="readonly" style="color:grey;" /></li>
<li><input type="text" name="respuesta11" size="22" maxlength="30" value="Votacion Invalida" readonly="readonly" style="color:grey;" /></li>
</ul></p>

<p><input type="submit" value="Crear votaci&oacute;n" /> &nbsp; <a href="/votacion/"><b>Ver votaciones</b></a></p>';




} elseif ($_GET['a']) {


	$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = votacion.user_ID LIMIT 1) AS nick, 
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS ha_votado
FROM votacion
WHERE ID = '".$_GET['a']."' AND pais = '".PAIS."'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {

		if ($r['tipo'] == 'parlamento') {
			$result2 = mysql_unbuffered_query("SELECT ID FROM ".SQL."estudios_users WHERE user_ID = '" . $pol['user_ID'] . "' AND cargo = '1' AND ID_estudio = '6' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ $es_diputado = true; }
		}

		$time_expire = strtotime($r['time_expire']);
		$time_creacion = strtotime($r['time']);
		$duracion = duracion($time_expire - $time_creacion);
		$respuestas = explode("|", $r['respuestas']);
		$respuestas_num = count($respuestas) - 1;
		$txt_title = 'Consulta: ' . strtoupper($r['tipo']) . ' | ' . $r['pregunta'];

		if ($r['estado'] == 'ok') { 
			$tiempo_queda =  ' | <span style="color:blue;">Queda ' . duracion($time_expire - time()) . '</span>'; 
		} else { $tiempo_queda =  ' | <span style="color:grey;">Finalizado</span>'; }


		$txt .= '<h1><a href="/votacion/">Consultas</a>: ' . strtoupper($r['tipo']) . ' | ' . $r['pregunta'] . $tiempo_queda . '</h1>

<br /><div class="amarillo"><p>' . $r['descripcion'] . '</p></div>

<p style="text-align:right;">Acceso: <acronym title="'.$r['acceso_cfg_votar'].'"><b>'.ucfirst(str_replace('_', ' ', $r['acceso_votar'])).'</b></acronym>. Creador <b>' . $r['nick'] . '</b>, a fecha <em>' . $r['time'] . '</em>, duraci&oacute;n <b>'.$duracion.'</b>.</p>';

		if ($time_expire < time()) { // VOTACION TERMINADA, IMPRIMIR RESULTADOS 

			$txt_escrutinio = '';
			$conteo_votos = 0;
			$invalido = false;
			$result2 = mysql_query("SELECT COUNT(user_ID) as num, voto
FROM votacion_votos
WHERE ref_ID = '" . $r['ID'] . "'
GROUP BY voto", $link);
			while($r2 = mysql_fetch_array($result2)) {
				$txt_escrutinio .= '<tr><td>' . $respuestas[$r2['voto']] . '</td><td align="right"><b>' . $r2['num'] . '</b></td><td align="right">' . round(($r2['num'] * 100) / $r['num']) . '%</td></tr>';

				$escaños_total = $escaños_total + $r2['num'];
				if ($chart_dato) { $chart_dato .= ','; } $chart_dato .= $r2['num'];
				


				if ($chart_nom) { $chart_nom .= '|'; } $chart_nom .= $respuestas[$r2['voto']];

				
				if (($respuestas[$r2['voto']] == 'Votacion Invalida') AND ($r2['num'] >= $conteo_votos)) { 
					$invalido = true; 
					$votos_invalido = $r2['num']; 
				} 
				$conteo_votos += $r2['num'];
			}



			$txt .= '
<table border="0" cellpadding="0" cellspacing="0"><tr><td valign="top">
			
<h2>Escrutinio:</h2>
'.($invalido==false?'<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
<tr>
<th>Respuestas &nbsp;</th>
<th>Votos</th>
<th></th>
</tr>'.$txt_escrutinio.'</table>':'<b style="color:red;">Votacion Invalida.<br /><br />Invalidado por '.$votos_invalido.' votos, de un total de '.$conteo_votos.' votos.</b>').'</td><td valign="top">


'.($invalido==false?($r['tipo']=='parlamento'?'<img src="http://chart.apis.google.com/chart?cht=p&chd=t:' . $escaños_total . ',' . $chart_dato . '&chs=450x300&chl=|' . $chart_nom . '&chco=ffffff01,FF8000&chf=bg,s,ffffff01|c,s,ffffff01" alt="Escrutinio" />':'<img src="http://chart.apis.google.com/chart?cht=p&chd=t:' . $chart_dato . '&chs=440x200&chl=' . $chart_nom . '&chf=bg,s,ffffff01|c,s,ffffff01" alt="Escrutinio" />'):'').'

</td></tr></table>';



		} else {
			if ((!$r['ha_votado']) AND (nucleo_acceso($r['acceso_votar'],$r['acceso_cfg_votar']))) {
				for ($i=0;$i<$respuestas_num;$i++) { if ($respuestas[$i]) { $votos .= '<option value="' . $i . '">' . $respuestas[$i] . '</option>'; } }
				$txt .= '<form action="/accion.php?a=votacion&b=votar" method="post">
<input type="hidden" name="ref_ID" value="' . $r['ID'] . '"  />
<p><select name="voto">
<option value="">&darr;</option>
' . $votos . '
</select>
<input type="submit" value="Votar" /></p>';
			} elseif ($r['ha_votado']) {
				$txt .= 'Tu voto ha sido recogido correctamente.';
			} else {
				$txt .= '<b style="color:red;">No tienes acceso para votar.</b>';
			}
		}

		if ($r['tipo'] == 'parlamento') {
			$txt .= '
<table border="0" cellpadding="0" cellspacing="3" class="pol_table">
<tr>
<th>Diputado</th>
<th></th>
<th colspan="2">Voto</th>
</tr>';

			$result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM users WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID = users.partido_afiliado LIMIT 1) AS las_siglas FROM users WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS siglas,
(SELECT voto FROM votacion_votos WHERE ref_ID = '" . $r['ID'] . "' AND user_ID = ".SQL."estudios_users.user_ID LIMIT 1) AS ha_votado
FROM ".SQL."estudios_users
WHERE cargo = '1' AND ID_estudio = '6'
ORDER BY siglas ASC", $link);
			$txt .= mysql_error($link);
			while($r2 = mysql_fetch_array($result2)) {
				if ($r2['ha_votado'] != null) { $ha_votado = ' style="background:blue;"';
				} else { $ha_votado = ' style="background:red;"'; }
				$txt .= '<tr><td><img src="'.IMG.'cargos/6.gif" /> <b>' . crear_link($r2['nick']) . '</b></td><td><b>' . crear_link($r2['siglas'], 'partido') . '</b></td><td' . $ha_votado . '></td><td><b>' . $respuestas[$r2['ha_votado']]  . '</b></td></tr>';
			}
			$txt .= '</table>';

		}


	}

} else {

	$result = mysql_query("SELECT ID FROM ".SQL."estudios_users WHERE user_ID = '" . $pol['user_ID'] . "' AND cargo = '1' AND ID_estudio = '6' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){ $es_diputado = true; }

	$txt_title = 'Votaci&oacute;n: sondeos, referendums, votaciones del parlamento';
	$txt .= '<h1>Votaci&oacute;n: (Referendum, sondeos y parlamento)</h1>
<br />';

	$sc = get_supervisores_del_censo(); 
	if (($pol['nivel'] >= 95) OR ($pol['cargos']['41']) OR (isset($sc[$pol['user_ID']]))) { $txt .= '<p>' . boton('Crear consulta', '/votacion/crear/') . '</p>'; }
	
	$txt .= '<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
<tr>
<th>Tipo</th>
<th>Votos</th>
<th>Pregunta</th>
<th>Autor</th>
<th>Estado</th>
<th></th>
</tr>';
	$result = mysql_query("SELECT ID, pregunta, time, time_expire, user_ID, estado, num, tipo, acceso_votar, acceso_cfg_votar,
(SELECT nick FROM users WHERE ID = votacion.user_ID LIMIT 1) AS nick,
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS ha_votado
FROM votacion
WHERE pais = '".PAIS."' 
ORDER BY estado ASC, time_expire DESC", $link);
	while($r = mysql_fetch_array($result)) {
		if ($r['estado'] == 'ok') { 
			$time_expire = strtotime($r['time_expire']);
			$estado =  '<span style="color:blue;">' . duracion($time_expire - time()) . '</span>'; 
		} else { $estado = '<span style="color:grey;">Finalizado</span>'; }

		if ((!$r['ha_votado']) AND ($r['estado'] == 'ok') AND (nucleo_acceso($r['acceso_votar'],$r['acceso_cfg_votar']))) { 
			$votar = boton('Votar', '/votacion/' . $r['ID'] . '/');
		} else { $votar = ''; }

		if ($r['user_ID'] == $pol['user_ID']) {
			$boton = boton('X', '/accion.php?a=votacion&b=eliminar&ID=' . $r['ID'], '&iquest;Seguro que quieres CANCELAR y ELIMINAR esta votacion?');
		} else { $boton = ''; }

		$txt .= '<tr>
<td>' . ucfirst($r['tipo']) . '</td>
<td align="right"><b>' . $r['num'] . '</b></td>
<td><a href="/votacion/' . $r['ID'] . '/"><b>' . $r['pregunta'] . '</b></a></td>
<td>' . crear_link($r['nick']) . '</td>
<td><b>' . $estado . '</b></td>
<td>' . $votar . $boton . '</td>
<td></td>
</tr>';
	}
	$txt .= '</table>';


}



//THEME
include('theme.php');
?>