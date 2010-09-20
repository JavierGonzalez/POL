<?php
include('inc-login.php');
/*
pol_examenes 		(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas)
pol_examenes_preg 	(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)
pol_estudios_users 	(ID, ID_estudio, user_ID, time, estado, cargo, nota)

/examenes/editar/ID/		- Config, Ver, editar, borrar y añadir preguntas
/examenes/crear/			- Crear nuevo
/examenes/examen/ID/		- Hacer Examen
/examenes/ID/				- Ver Examen
/examenes/					- Lista

34 Profesor - Añadir editar borrar preguntas
35 Profesor Decano - Añadir examenes, editar info examenes

$pol['config']['examen_repe']
*/

function boton_cargo($value, $url, $cargo) {
	//boton_cargo('Crear examen', '/examenes/crear/', '7', $pol['cargos'])
	$text = '';
	if ($url) { $val = ' type="button" onclick="window.location.href=\'' . $url . '\';"'; } 
	else { $val = ' type="submit"'; }
	if ($cargo) {
		global $pol;		
		if (($cargo == 35) AND ($pol['cargos'][35])) {
		} elseif (($cargo == 34) AND (($pol['cargos'][34]) OR ($pol['cargos'][35]))) {
		} else { $val .= ' disabled="disabled"'; }
		if ($cargo == 35) { $text = 'Decano'; }
		elseif ($cargo == 34) { $text = 'Profesor'; }
	}
	return '<span class="amarillo"><input' . $val . ' value="' . $value . '" /> Cargo requerido: <em>' . $text . '</em></span>';
}


function shuffle_assoc($input_array){
	foreach($input_array as $key => $value){
		$temp_array[$value][key] = $key;
	}
	shuffle($input_array);
	foreach($input_array as $key => $value){
		$return_array[$temp_array[$value][key]] = $value;
	}
	return $return_array;
}

function shuffle_assoc_OLD(&$array) {
	if (count($array)>1) {
		$keys = array_rand($array, count($array));

		foreach($keys as $key)
		$new[$key] = $array[$key];

		$array = $new;
	}
	return true;
} 

// carga config
$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

$pol['cargos'] = cargos();

// INIT
if (($_GET['a'] == 'editar') AND ((($pol['cargos'][35]) OR ($pol['cargos'][34])) AND ($pol['estado'] == 'ciudadano') OR ($pol['estado'] == 'desarrollador'))) { 		// EDITAR

	if (!$_GET['b']) { $_GET['b'] = 0; }

	$result = mysql_query("SELECT ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas
FROM ".SQL."examenes
WHERE ID = '" . $_GET['b'] . "'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){

		$txt .= '<h1>Editar examen: ' . $row['titulo'] . ' (<a href="/examenes/">Ver examenes</a>)</h1>


<hr />
<h2>A&ntilde;adir pregunta: (mejor pocas y bien hechas, gracias!)</h2>
<div id="edit">
<form action="/accion.php?a=examenes&b=nueva-pregunta&ID=' . $_GET['b'] . '" method="post">
<ol>
<li><b>Pregunta:</b> (max 200 caracteres)<b><br />&iquest;<input type="text" name="pregunta" autocomplete="off" size="50" maxlength="200" />?</b></li>

<li><b>Respuestas:</b> (entre 2 y 4, max 100 caracteres)<br />
<input type="text" name="respuesta0" size="25" maxlength="100" autocomplete="off" style="border:2px solid grey;" /> <b>(Correcta)</b><br />
<input type="text" name="respuesta1" size="25" maxlength="100" autocomplete="off" /><br />
<input type="text" name="respuesta2" size="25" maxlength="100" autocomplete="off" /><br />
<input type="text" name="respuesta3" size="25" maxlength="100" autocomplete="off" /></li>

<li><b>Tiempo preciso para responder:</b> <input type="text" name="tiempo" value="6" size="1" maxlength="3" style="text-align:right;" /> segundos</li>

<li>' . boton_cargo('A&ntilde;adir pregunta', false, 34) . '</li>
</ol>
</form>

<hr />

<h2>Preguntas:</h2>
<ol id="lista">';
		// ".SQL."examenes_preg 	(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)
		$result2 = mysql_query("SELECT ID, examen_ID, user_ID, pregunta, respuestas, tiempo,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."examenes_preg.user_ID LIMIT 1) AS nick
FROM ".SQL."examenes_preg
WHERE examen_ID = '" . $_GET['b'] . "'
ORDER BY time DESC", $link);
		while($row2 = mysql_fetch_array($result2)){
			$respuestas = '';
			$res = explode("|", $row2['respuestas']);
			foreach($res as $ID => $respuesta) {
				$respuestas .= '<option value="' . $ID . '">' . $respuesta . '</option>';
			}


			if (($pol['cargos'][35]) OR (($pol['cargos'][34]) AND ($row2['user_ID'] == $pol['user_ID']))) { $boton = boton('x', '/accion.php?a=examenes&b=eliminar-pregunta&ID=' . $row2['ID'] . '&re_ID=' . $row['ID'], '&iquest;Seguro que quieres ELIMINAR esta pregunta y sus respuestas?'); } else { $boton = ''; }

			$txt .= '<li>' . crear_link($row2['nick']) . ': <b>&iquest;' . $row2['pregunta'] . '?</b> &nbsp; (' . $row2['tiempo'] . ' seg) &nbsp; <select name="p">' . $respuestas . '</select> ' . $boton . '</li>';
		}
	
	
		$txt .= '</ol>';

		if ($_GET['b'] != 0) {
			if (substr($row['cargo_ID'], 0, 1) != '-') { $readonly = ' readonly="readonly"'; } else { $readonly = ''; }
			$txt .= '<hr />
<h2>Editar examen:</h2>
<form action="/accion.php?a=examenes&b=editar-examen&ID=' . $row['ID'] . '" method="post">
<ol>

<li><b>Titulo del examen:</b> <input type="text" name="titulo" size="15" maxlength="30" value="' . $row['titulo'] . '"' . $readonly . ' /></li>

<li><b>Temario:</b> descripci&oacute;n breve y precisa de los temas abarcados<br />
<textarea name="descripcion" style="color: green; font-weight: bold; width: 570px; height: 100px;">' . strip_tags($row['descripcion']) . '</textarea></li>

<li><b>Nota para aprobar:</b> <input type="text" name="nota" size="3" maxlength="4" value="' . $row['nota'] . '" style="text-align:right;" /</li>

<li><b>Extensi&oacute;n:</b> <input type="text" name="num_preguntas" size="3" maxlength="4" value="' . $row['num_preguntas'] . '" style="text-align:right;" /> preguntas</li>

<li>' . boton_cargo('Editar examen', false, 35) . '</li>

</ol>
</form>';
		}
		$txt .= '</div>';

		$txt_header .= '
<style type="text/css">
#edit li { margin:0 0 10px 0; }
#lista li { margin:0 0 0 0; }
</style>
';
	}

} elseif (($_GET['a'] == 'mis-examenes') AND (($pol['estado'] == 'ciudadano') OR ($pol['estado'] == 
'desarrollador'))) { 	// MIS EXAMENES

	// load config full
	$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
	while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

	$txt_title = 'Mis examenes';
	$txt .= '<h1><a href="/examenes/">Examenes</a>: Mis examenes</h1>

<br />

<table border="0" cellspacing="0" cellpadding="2" class="pol_table">
<tr>
<th></th>
<th>Nota</th>
<th>Examen</th>
<th></th>
<th>Hace</th>
</tr>';
// ".SQL."examenes 		(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas)
// ".SQL."examenes_preg 	(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo) 

	$result = mysql_query("SELECT ID, ID_estudio, user_ID, time, estado, cargo, nota, 
(SELECT titulo FROM ".SQL."examenes WHERE cargo_ID = ".SQL."estudios_users.ID_estudio LIMIT 1) AS nombre_examen,
(SELECT ID FROM ".SQL."examenes WHERE cargo_ID = ".SQL."estudios_users.ID_estudio LIMIT 1) AS examen_ID
FROM ".SQL."estudios_users
WHERE user_ID = '" . $pol['user_ID'] . "'
ORDER BY estado ASC, nota DESC", $link);
	while($row = mysql_fetch_array($result)){
		if ($row['estado'] == 'ok') { $sello = '<img src="/img/estudiado.gif" alt="Aprobado" title="Aprobado" border="0" />'; } else { $sello = ''; }
		if ($row['cargo'] == 1) { $cargo = '(Cargo ejercido)'; } else { $cargo = ''; }
		$txt .= '<tr><td>' . $sello . '</td><td align="right"><b style="color:grey;">' . $row['nota'] . '</b></td><td><a href="/examenes/' . $row['examen_ID'] . '/"><b>' . $row['nombre_examen'] . '</b></a></td><td>' . $cargo . '</td><td align="right"><acronym title="' . $row['time'] . '">' . duracion(time() - strtotime($row['time'])) .  '</acronym></td></tr>';
	}

	$txt .= '</table><p style="color:red;">Tiempo de expiraci&oacute;n: <b>'.duracion($pol['config']['examenes_exp']).'</b></p>';


} elseif (($_GET['a'] == 'crear') AND ($pol['estado'] == 'ciudadano')) { 	// CREAR NUEVA
	$txt .= '<h1><a href="/examenes/">Examenes</a>: Crear examen</h1>

<form action="/accion.php?a=examenes&b=crear" method="post">

<ol>

<li><b>Examen:</b> <input type="text" name="titulo" size="15" maxlength="30" /><br /><br /></li>

<li>' . boton_cargo('Crear examen', false, 35) . '</li>

</ol>

</form>';
} elseif (($_GET['a'] == 'examen') AND ($_GET['b']) AND ($_GET['b'] != 0) AND (($pol['estado'] == 'ciudadano') OR ($pol['estado'] == 'desarrollador'))) { 
														// HACER EXAMEN

	$result = mysql_query("SELECT ID, titulo, user_ID, time, cargo_ID, nota, num_preguntas,
(SELECT time FROM ".SQL."estudios_users WHERE ID_estudio = ".SQL."examenes.cargo_ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS fecha_ultimoexamen,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = ".SQL."examenes.ID LIMIT 1) AS num_preguntas_especificas,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = 0 LIMIT 1) AS num_preguntas_generales
FROM ".SQL."examenes
WHERE ID = '" . $_GET['b'] . "'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){


		$preguntas_disponibles = $row['num_preguntas_especificas'] + $row['num_preguntas_generales'];
		$margen_ultimoexamen = strtotime($row['fecha_ultimoexamen']) + $pol['config']['examen_repe'];
		if (((!$row['fecha_ultimoexamen']) OR ($margen_ultimoexamen < time())) AND ($row['num_preguntas_especificas'] >= 5) AND ($preguntas_disponibles >= $row['num_preguntas']) AND ($pol['pols'] >= $pol['config']['pols_examen'])) {

			// marca examen como hecho	
			if ($row['fecha_ultimoexamen']) {
				//update 
				mysql_query("UPDATE ".SQL."estudios_users SET time = '" . $date . "', nota = 0.0, estado = 'examen' WHERE ID_estudio = '" . $row['cargo_ID'] . "' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
			} else {
				//insert
				mysql_query("INSERT INTO ".SQL."estudios_users 
(ID_estudio, user_ID, time, estado, nota) 
VALUES ('" . $row['cargo_ID'] . "', '" . $pol['user_ID'] . "', '" . $date . "', 'examen', '0.0')", $link);
			}	

			// Cobrar examen
			if ($pol['config']['pols_examen'] != '0') {
				include('inc-functions-accion.php');
				pols_transferir($pol['config']['pols_examen'], $pol['user_ID'], '-1', 'Examen: ' . $row['titulo']);
			}


			// EMPIEZA EXAMEN
			$txt .= '<h1>Examen: ' . $row['titulo'] . '</h1>

<p>Tienes <b><span class="seg"></span></b> segundos.</p>


<table id="latabla" border="0">
<tr>

<td style="background:red;" id="t_mas" width="12" height="1"></td>

<td rowspan="2">


<div id="examen">
<form action="/accion.php?a=examenes&b=examinar&ID=' . $_GET['b'] . '" method="post" id="elexamen">
<ol>';


			// ".SQL."examenes_preg 	(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)
			$tiempo = 0;
			$respuestas_correctas = array();
			$result2 = mysql_query("SELECT ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo
FROM ".SQL."examenes_preg
WHERE examen_ID = '" . $_GET['b'] . "' OR examen_ID = 0
ORDER BY examen_ID DESC, RAND() LIMIT " . $row['num_preguntas'], $link);
			while($row2 = mysql_fetch_array($result2)){
				$respuestas = '';
				$res2 = '';
				$res = explode("|", $row2['respuestas']);
				
				$res2['a'] = $res[0];
				$respuestas_correctas[] = md5($res[0]);
				$res2['b'] = $res[1];
				if ($res[2]) { $res2['c'] = $res[2]; }
				if ($res[3]) { $res2['d'] = $res[3]; }
				
				$res2 = shuffle_assoc($res2);


				$tiempo += $row2['tiempo'];
				foreach($res2 as $ID => $respuesta) {
					$respuestas .= '<input type="radio" name="respuesta' . $row2['ID'] . '" value="' . md5($respuesta) . '" />' . $respuesta . '<br />';
				}
				if ($pregs) { $pregs .= '|'; } $pregs .= $row2['ID'];
				$txt .= '<li><b>&iquest;' . $row2['pregunta'] . '?</b><br />' . $respuestas . '</li>';
			}
			$tiempo += 10;
			$limite_tiempo = time() + $tiempo;
			$_SESSION['examen']['respuestas'] = $respuestas_correctas;
			$_SESSION['examen']['tiempo'] = $limite_tiempo;
			$_SESSION['examen']['ID'] = $_GET['b'];


			$txt .= '</ol>

<input type="hidden" name="pregs" value="' . $pregs . '" />
<input type="hidden" name="tlgs" value="' . $limite_tiempo . '" />

</div>

</td>
</tr>

<tr>
<td style="background:blue;" id="t_menos"></td>
</tr>
</table>

<p><input type="submit" value="Terminar examen" style="height:35px;" /> &nbsp; Tienes <b><span class="seg"></span></b> segundos.</p>
</form>
';

			$txt_header .= '
<style type="text/css">
.seg { font-size:22px; }
#examen li { margin:0 0 10px 0; }
</style>

<script type="text/javascript">
tiempo = parseInt("' . $tiempo . '");
tiempo_total = parseInt("' . $tiempo . '");

function time_refresh() {
	if (tiempo > 0) {
		$(".seg").html(tiempo);
		tiempo = tiempo - 1;
		refresh = setTimeout(time_refresh, 1000);
		var t = parseInt(tiempo_total) - parseInt(tiempo);	
		var porcentaje_mas = Math.floor((t * parseInt($("#latabla").height())) / parseInt(tiempo_total));
		$("#t_mas").attr("height", porcentaje_mas);
	} else { $("#elexamen").submit(); }
}

window.onload = function(){
	$(".seg").html(tiempo);
	refresh = setTimeout(time_refresh, 1000);
}
</script>';

		} else { header('Location: http://'.HOST.'/examenes/' . $row['ID'] . '/'); exit; }
	}





} elseif (($_GET['a']) AND ($_GET['a'] != 0)) {				// VER EXAMEN

	$result = mysql_query("SELECT ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = ".SQL."examenes.ID LIMIT 1) AS num_preguntas_especificas,
(SELECT time FROM ".SQL."estudios_users WHERE ID_estudio = ".SQL."examenes.cargo_ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS fecha_ultimoexamen
FROM ".SQL."examenes
WHERE ID = '" . $_GET['a'] . "'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){

		$txt_title = 'Examen de ' . $row['titulo'];
		$txt .= '<h1>' . $row['titulo'] . ' (<a href="/examenes/">Ver examenes</a>)</h1>
<table border="0" width="100%"><tr><td valign="top" width="60%">

<p class="amarillo"><b>Temario:</b><br />' . $row['descripcion'] . '</p>

<p>Nota minima para aprobar: <b class="gris">' . $row['nota'] . '</b>. Examen tipo test, tiempo limitado, <b>' . $row['num_preguntas'] . '</b> preguntas de entre <b>' . $row['num_preguntas_especificas'] . '</b> en total.</p>

<p>No podr&aacute;s repetir este examen hasta <b>' . duracion($pol['config']['examen_repe']) . '</b> despu&eacute;s.</p>';

		if ($row['cargo_ID'] == 0) {
			$txt .= '<p>Examen sin vinculaci&oacute;n con cargo.</p>';
		} else {
			$result2 = mysql_query("SELECT nombre FROM ".SQL."estudios WHERE ID = '" . $row['cargo_ID'] . "' LIMIT 1", $link);
			while($row2 = mysql_fetch_array($result2)){ $txt .= '<p>Examen vinculado al cargo: <a href="/cargos/">' . $row2['nombre'] . '</a>.</p>'; }
			
		}

		$margen_ultimoexamen = strtotime($row['fecha_ultimoexamen']) + $pol['config']['examen_repe'];
		if ((!$row['fecha_ultimoexamen']) OR ($margen_ultimoexamen < time())) {
			$txt .= '<p>' . boton('OK, HACER EXAMEN', '/examenes/examen/' . $row['ID'] . '/', '&iquest;Est&aacute;s preparado para EXAMINARTE?\n\nSolo podr&aacute;s intentarlo UNA VEZ cada ' . duracion($pol['config']['examen_repe']) . '.\n\nSi ejerces el cargo y suspendes lo perder&aacute;s!', false, $pol['config']['pols_examen']) . '</p>';
		} else {
			$txt .= '<p><b class="amarillo">No puedes repetir el examen hasta dentro de ' . duracion($margen_ultimoexamen - time()) . '</b></p>';
		}

		$txt .= '</td><td valign="top" width="40%"><ol>';

		$result2 = mysql_query("SELECT nota, user_ID, cargo, estado,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick,
(SELECT fecha_registro FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS fecha_registro
FROM ".SQL."estudios_users WHERE ID_estudio = '" . $row['cargo_ID'] . "' AND nota != '' ORDER BY nota DESC, cargo DESC, fecha_registro ASC LIMIT 100", $link);
		$txt .= mysql_error($link);
		while($row2 = mysql_fetch_array($result2)){ 
			if ($row2['cargo'] == 1) { $cargo = '<img src="/img/cargos/'.$row['cargo_ID'].'.gif" />'; } else { $cargo = ''; }
			if ($row2['estado'] == 'ok') { $sello = '<img src="/img/estudiado.gif" alt="Aprobado" title="Aprobado" border="0" />'; } else { $sello = '<span style="margin-left:21px;"></span>'; }
			$txt .= '<li><b class="gris">' . $sello . ' ' . $row2['nota'] . ' ' . $cargo . crear_link($row2['nick']) . '</b></li>';
		}

		$txt .= '</ol></td></tr></table>';

	}



} else {							// VER LISTA EXAMENES

	$txt_title = 'Mis examenes';
	$txt .= '<h1>Examenes: <a href="/examenes/mis-examenes/">Mis examenes</a></h1>';


	$result = mysql_query("SELECT examen_ID FROM ".SQL."examenes_preg", $link);
	while($row = mysql_fetch_array($result)){ 
		 if ($row['examen_ID'] == 0) { $num_generales++; } else { $num_especificas++; }
	}
	
	if (($pol['cargos'][35]) OR ($pol['cargos'][34])) { $boton = boton('Editar', '/examenes/editar/', 'm'); } 
	$txt .= '<p><b class="big">' . ($num_generales + $num_especificas) . '</b> preguntas: <b>' . $num_especificas . '</b> especificas + <b>' . $num_generales . '</b> generales ' . $boton . '</p>'; 
	$boton = '';

$txt .= '
<table border="0" cellspacing="0" cellpadding="2" class="pol_table">
<tr>
<th>Preguntas</th>
<th><acronym title="Nota para aprobar">Nota</acronym></th>
<th colspan="2"><acronym title="Porcentaje de aprobados">Aprob</acronym></th>
<th></th>
<th>Examen</th>
<th></th>
</tr>';
// ".SQL."examenes 		(ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas)
// ".SQL."examenes_preg 	(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo) 


	$result = mysql_query("SELECT ID, titulo, user_ID, time, cargo_ID, nota, num_preguntas,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = ".SQL."examenes.ID LIMIT 1) AS num_preguntas_especificas,
(SELECT COUNT(*) FROM ".SQL."estudios_users WHERE ID_estudio = ".SQL."examenes.cargo_ID AND nota != '') AS examinados,
(SELECT COUNT(*) FROM ".SQL."estudios_users WHERE ID_estudio = ".SQL."examenes.cargo_ID AND nota != '' AND estado = 'ok') AS aprobados
FROM ".SQL."examenes
WHERE ID != 0
ORDER BY nota DESC, num_preguntas_especificas DESC", $link);
	while($row = mysql_fetch_array($result)){

		if (substr($row['cargo_ID'], 0, 1) != '-') {
			$result2 = mysql_query("SELECT nombre FROM ".SQL."estudios WHERE ID = '" . $row['cargo_ID'] . "' LIMIT 1", $link);
			while($row2 = mysql_fetch_array($result2)){ $cargo = '<img src="/img/cargos/' . $row['cargo_ID'] . '.gif" title="' . $row2['nombre'] . '" />'; }
		} else { $cargo = ''; }

		if (($pol['cargos'][35]) OR ($pol['cargos'][34])) { $boton = boton('Editar', '/examenes/editar/' . $row['ID'] . '/', 'm'); } 
		else { $boton = ''; }

		if ($row['aprobados'] > 0) {
			$aprobados = round(($row['aprobados'] * 100) / $row['examinados']) . '%';	
		} else { $aprobados = '0%'; }

		$num_preguntas_disponibles = $num_generales + $row['num_preguntas_especificas'];
		if (($row['num_preguntas_especificas'] >= 5) AND ($num_preguntas_disponibles >= $row['num_preguntas'])) {
			$url = '<a href="/examenes/' . $row['ID'] . '/"><b>' . $row['titulo'] . '</b></a>';
		} else {
			$url = '<b>' . $row['titulo'] . '</b>';
			$aprobados = '';
		}


		$txt .= '<tr>
<td valign="top">' . $row['num_preguntas'] . ' de ' . $row['num_preguntas_especificas'] . '</td>
<td valign="top"><b style="color:grey;">' . $row['nota'] . '</b></td>
<td valign="top" align="right">' . $aprobados . '</td>
<td valign="top" align="right">' . $row['aprobados'] . '</td>
<td valign="top">' . $cargo . '</td>
<td valign="top">' . $url . '</td>
<td valign="top">' . $boton . '</td>
</tr>';
	}

	$txt .= '</table>';

	if ($pol['cargos'][35]) {
		$txt .= '<p>' . boton_cargo('Crear examen', '/examenes/crear/', 35) . '</p>';
	}

}

//THEME
if (!$txt_title) { $txt_title = 'Examenes'; }
include('theme.php');
?>
