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
		global $pol, $vp;
		
		if (($cargo == 35) AND (nucleo_acceso($vp['acceso']['examenes_decano']))) { } 
		elseif (($cargo == 34) AND ((nucleo_acceso($vp['acceso']['examenes_profesor'])) OR (nucleo_acceso($vp['acceso']['examenes_decano'])))) { } 
		else { $val .= ' disabled="disabled"'; }

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
while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

$pol['cargos'] = cargos();

// INIT
if (($_GET['a'] == 'editar') AND (((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) AND ($pol['estado'] == 'ciudadano'))) { 		// EDITAR

	if (!$_GET['b']) { $_GET['b'] = 0; }

	$result = mysql_query("SELECT ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas
FROM ".SQL."examenes
WHERE ID = '" . $_GET['b'] . "'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		$txt .= '<h1>Editar examen: ' . $r['titulo'] . ' (<a href="/examenes/">Ver examenes</a>)</h1>


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
		while($r2 = mysql_fetch_array($result2)){
			$respuestas = '';
			$res = explode("|", $r2['respuestas']);
			foreach($res as $ID => $respuesta) {
				$respuestas .= '<option value="' . $ID . '">' . $respuesta . '</option>';
			}


			if ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR ((nucleo_acceso($vp['acceso']['examenes_profesor'])) AND ($r2['user_ID'] == $pol['user_ID']))) { $boton = boton('x', '/accion.php?a=examenes&b=eliminar-pregunta&ID=' . $r2['ID'] . '&re_ID=' . $r['ID'], '&iquest;Seguro que quieres ELIMINAR esta pregunta y sus respuestas?'); } else { $boton = ''; }

			$txt .= '<li>' . crear_link($r2['nick']) . ': <b>&iquest;' . $r2['pregunta'] . '?</b> &nbsp; (' . $r2['tiempo'] . ' seg) &nbsp; <select name="p"><option value=""></option>' . $respuestas . '</select> ' . $boton . '</li>';
		}
	
	
		$txt .= '</ol>';

		if ($_GET['b'] != 0) {
			if (substr($r['cargo_ID'], 0, 1) != '-') { $readonly = ' readonly="readonly"'; } else { $readonly = ''; }
			$txt .= '<hr />
<h2>Editar examen:</h2>
<form action="/accion.php?a=examenes&b=editar-examen&ID=' . $r['ID'] . '" method="post">
<ol>

<li><b>Titulo del examen:</b> <input type="text" name="titulo" size="15" maxlength="30" value="' . $r['titulo'] . '"' . $readonly . ' /></li>

<li><b>Temario:</b> descripci&oacute;n breve y precisa de los temas abarcados<br />
<textarea name="descripcion" style="color: green; font-weight: bold; width: 570px; height: 100px;">' . strip_tags($r['descripcion']) . '</textarea></li>

<li><b>Nota para aprobar:</b> <input type="text" name="nota" size="3" maxlength="4" value="' . $r['nota'] . '" style="text-align:right;" /></li>

<li><b>Extensi&oacute;n:</b> <input type="text" name="num_preguntas" size="3" maxlength="4" value="' . $r['num_preguntas'] . '" style="text-align:right;" /> preguntas</li>

<li>' . boton_cargo('Editar examen', false, 35) . '</li>

</ol>
</form>';
			if ((nucleo_acceso($vp['acceso']['examenes_decano'])) AND ($r['cargo_ID'] < 0))  {
				$result3 = mysql_query("SELECT (SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = ".SQL."examenes.ID LIMIT 1) AS num_depreguntas
FROM ".SQL."examenes WHERE ID = '" . $_GET['b'] . "' LIMIT 1", $link);
				while($r3 = mysql_fetch_array($result3)){ 
					if ($r3['num_depreguntas'] == 0) {
						$txt .='<hr />
<form action="/accion.php?a=examenes&b=eliminar-examen" method="post">
<input type="hidden" name="ID" value="' . $r['ID'] . '" /> 
<input type="submit" value="Eliminar examen"/>
</form>';
					}
				}
			}
		}
		$txt .= '</div>';

		$txt_header .= '
<style type="text/css">
#edit li { margin:0 0 10px 0; }
#lista li { margin:0 0 0 0; }
</style>
';
	}

} elseif (($_GET['a'] == 'mis-examenes') AND ($pol['estado'] == 'ciudadano')) { 	// MIS EXAMENES

	// load config full
	$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
	while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

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
	while($r = mysql_fetch_array($result)){
		if ($r['estado'] == 'ok') { $sello = '<img src="'.IMG.'estudiado.gif" alt="Aprobado" title="Aprobado" border="0" />'; } else { $sello = ''; }
		if ($r['cargo'] == 1) { $cargo = '(Cargo ejercido)'; } else { $cargo = ''; }
		if (($r['ID_estudio'] <= 0) AND (time()-strtotime($r['time']) > $pol['config']['examen_repe']*6)) {
			$caducar_examen = ' <form action="/accion.php?a=examenes&b=caducar_examen&ID='.$r['ID'].'" method="POST"><input type="hidden" name="pais" value="'.$pol['pais'].'" /><input type="submit" value="X"  onclick="if (!confirm(\'&iquest;Seguro que quieres que CADUQUE el examen de ' . $r['nombre_examen'] . '?\')) { return false; }"/></form>';
		}
		else {
			$caducar_examen = '';
		}
		$txt .= '<tr><td>' . $sello . '</td><td align="right"><b style="color:grey;">' . $r['nota'] . '</b></td><td><a href="/examenes/' . $r['examen_ID'] . '/"><b>' . $r['nombre_examen'] . '</b></a></td><td>' . $cargo . '</td><td align="right"><acronym title="' . $r['time'] . '">' . duracion(time() - strtotime($r['time'])) .  '</acronym></td><td><b>'. $caducar_examen .'</b></td></tr>';
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
} elseif (($_GET['a'] == 'examen') AND ($_GET['b']) AND ($_GET['b'] != 0) AND ($pol['estado'] == 'ciudadano')) { 
														// HACER EXAMEN

	$result = mysql_query("SELECT ID, titulo, user_ID, time, cargo_ID, nota, num_preguntas,
(SELECT time FROM ".SQL."estudios_users WHERE ID_estudio = ".SQL."examenes.cargo_ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS fecha_ultimoexamen,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = ".SQL."examenes.ID LIMIT 1) AS num_preguntas_especificas,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = 0 LIMIT 1) AS num_preguntas_generales
FROM ".SQL."examenes
WHERE ID = '" . $_GET['b'] . "'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		$preguntas_disponibles = $r['num_preguntas_especificas'] + $r['num_preguntas_generales'];
		$margen_ultimoexamen = strtotime($r['fecha_ultimoexamen']) + $pol['config']['examen_repe'];
		if (((!$r['fecha_ultimoexamen']) OR ($margen_ultimoexamen < time())) AND ($pol['pols'] >= $pol['config']['pols_examen'])) {

			// marca examen como hecho	
			if ($r['fecha_ultimoexamen']) {
				//update 
				mysql_query("UPDATE ".SQL."estudios_users SET time = '" . $date . "', nota = 0.0, estado = 'examen' WHERE ID_estudio = '" . $r['cargo_ID'] . "' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
			} else {
				//insert
				mysql_query("INSERT INTO ".SQL."estudios_users 
(ID_estudio, user_ID, time, estado, nota) 
VALUES ('" . $r['cargo_ID'] . "', '" . $pol['user_ID'] . "', '" . $date . "', 'examen', '0.0')", $link);
			}	

			// Cobrar examen
			if ($pol['config']['pols_examen'] != '0') {
				include('inc-functions-accion.php');
				pols_transferir($pol['config']['pols_examen'], $pol['user_ID'], '-1', 'Examen: ' . $r['titulo']);
			}


			// EMPIEZA EXAMEN
			$txt .= '<h1>Examen: ' . $r['titulo'] . '</h1>

<p>Tienes <b><span class="seg"></span></b> segundos.</p>


<table id="latabla" border="0">
<tr>

<td style="background:red;" id="t_mas" width="12" height="1"></td>

<td rowspan="2">


<div id="examen">
<form action="/accion.php?a=examenes&b=examinar&ID=' . $_GET['b'] . '" method="post" id="elexamen">
<ol>';


			// ".SQL."examenes_preg 	(ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo)
			$examen_tiempo = 0;
			$respuestas_correctas = array();
			$result2 = mysql_query("SELECT ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo
FROM ".SQL."examenes_preg
WHERE examen_ID = '" . $_GET['b'] . "' OR examen_ID = 0
ORDER BY examen_ID DESC, RAND() LIMIT " . $r['num_preguntas'], $link);
			echo mysql_error($link);
			while($r2 = mysql_fetch_array($result2)){
				$respuestas = '';
				$res2 = '';
				$res = explode("|", $r2['respuestas']);
				
				$res2['a'] = $res[0];
				$respuestas_correctas[] = md5($res[0]);
				$res2['b'] = $res[1];
				if ($res[2]) { $res2['c'] = $res[2]; }
				if ($res[3]) { $res2['d'] = $res[3]; }
				
				$res2 = shuffle_assoc($res2);


				$examen_tiempo += $r2['tiempo'];
				foreach($res2 as $ID => $respuesta) {
					$respuestas .= '<input type="radio" name="respuesta' . $r2['ID'] . '" value="' . md5($respuesta) . '" />' . $respuesta . '<br />';
				}
				if ($pregs) { $pregs .= '|'; } $pregs .= $r2['ID'];
				$txt .= '<li><b>&iquest;' . $r2['pregunta'] . '?</b><br />' . $respuestas . '</li>';
			}
			$examen_tiempo += 10;
			$limite_tiempo = time() + $examen_tiempo;
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
examen_tiempo = parseInt("' . $examen_tiempo . '");
examen_tiempo_total = parseInt("' . $examen_tiempo . '");

function time_refresh() {
	if (examen_tiempo > 0) {
		$(".seg").html(examen_tiempo);
		examen_tiempo = examen_tiempo - 1;
		examen_refresh = setTimeout(time_refresh, 1000);
		var t = parseInt(examen_tiempo_total) - parseInt(examen_tiempo);	
		var porcentaje_mas = Math.floor((t * parseInt($("#latabla").height())) / parseInt(examen_tiempo_total));
		$("#t_mas").attr("height", porcentaje_mas);
	} else { $("#elexamen").submit(); }
}

window.onload = function(){
	$(".seg").html(examen_tiempo);
	examen_refresh = setTimeout(time_refresh, 1000);
}
</script>';

		} else { header('Location: http://'.HOST.'/examenes/' . $r['ID'] . '/'); exit; }
	}





} elseif (($_GET['a']) AND ($_GET['a'] != 0)) {				// VER EXAMEN

	$result = mysql_query("SELECT ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas,
(SELECT COUNT(*) FROM ".SQL."examenes_preg WHERE examen_ID = ".SQL."examenes.ID LIMIT 1) AS num_preguntas_especificas,
(SELECT time FROM ".SQL."estudios_users WHERE ID_estudio = ".SQL."examenes.cargo_ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS fecha_ultimoexamen
FROM ".SQL."examenes
WHERE ID = '" . $_GET['a'] . "'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		$txt_title = 'Examen de ' . $r['titulo'];
		$txt .= '<h1>' . $r['titulo'] . ' (<a href="/examenes/">Ver examenes</a>)</h1>
<table border="0" width="100%"><tr><td valign="top" width="60%">

<p class="amarillo"><b>Temario:</b><br />' . $r['descripcion'] . '</p>

<p>Nota minima para aprobar: <b class="gris">' . $r['nota'] . '</b>. Examen tipo test, tiempo limitado, <b>' . $r['num_preguntas'] . '</b> preguntas de entre <b>' . $r['num_preguntas_especificas'] . '</b> en total.</p>

<p>No podr&aacute;s repetir este examen hasta <b>' . duracion($pol['config']['examen_repe']) . '</b> despu&eacute;s.</p>';

		if ($r['cargo_ID'] == 0) {
			$txt .= '<p>Examen sin vinculaci&oacute;n con cargo.</p>';
		} else {
			$result2 = mysql_query("SELECT nombre FROM ".SQL."estudios WHERE ID = '" . $r['cargo_ID'] . "' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ $txt .= '<p>Examen vinculado al cargo: <a href="/cargos/">' . $r2['nombre'] . '</a>.</p>'; }
			
		}

		$margen_ultimoexamen = strtotime($r['fecha_ultimoexamen']) + $pol['config']['examen_repe'];
		if ((!$r['fecha_ultimoexamen']) OR ($margen_ultimoexamen < time())) {
			$txt .= '<p>' . boton('HACER EXAMEN', '/examenes/examen/' . $r['ID'] . '/', '&iquest;Est&aacute;s preparado para EXAMINARTE?\n\nSolo podr&aacute;s intentarlo UNA VEZ cada ' . duracion($pol['config']['examen_repe']) . '.\n\nSi ejerces el cargo y suspendes lo perder&aacute;s!', false, $pol['config']['pols_examen']) . '</p>';
		} else {
			$txt .= '<p><b class="amarillo">No puedes repetir el examen hasta dentro de ' . duracion($margen_ultimoexamen - time()) . '</b></p>';
		}

		$txt .= '</td><td valign="top" width="40%"><ol>';

		$result2 = mysql_query("SELECT nota, user_ID, cargo, estado,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick,
(SELECT fecha_registro FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS fecha_registro
FROM ".SQL."estudios_users WHERE ID_estudio = '" . $r['cargo_ID'] . "' AND nota != '' ORDER BY nota DESC, cargo DESC, fecha_registro ASC LIMIT 100", $link);
		while($r2 = mysql_fetch_array($result2)){ 
			if ($r2['cargo'] == 1) { $cargo = '<img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" />'; } else { $cargo = ''; }
			if ($r2['estado'] == 'ok') { $sello = '<img src="'.IMG.'estudiado.gif" alt="Aprobado" title="Aprobado" border="0" />'; } else { $sello = '<span style="margin-left:21px;"></span>'; }
			$txt .= '<li><b class="gris">' . $sello . ' ' . $r2['nota'] . ' ' . $cargo . crear_link($r2['nick']) . '</b></li>';
		}

		$txt .= '</ol></td></tr></table>';

	}



} else {							// VER LISTA EXAMENES

	$txt_title = 'Mis examenes';
	$txt .= '<h1>Examenes: <a href="/examenes/mis-examenes/">Mis examenes</a></h1>';


	$result = mysql_query("SELECT examen_ID FROM ".SQL."examenes_preg", $link);
	while($r = mysql_fetch_array($result)){ 
		 if ($r['examen_ID'] == 0) { $num_generales++; } else { $num_especificas++; }
	}
	
	if ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) { $boton = boton('Editar', '/examenes/editar/', 'm'); } 
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
	while($r = mysql_fetch_array($result)){

		if (substr($r['cargo_ID'], 0, 1) != '-') {
			$result2 = mysql_query("SELECT nombre FROM ".SQL."estudios WHERE ID = '" . $r['cargo_ID'] . "' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ $cargo = '<img src="'.IMG.'cargos/' . $r['cargo_ID'] . '.gif" title="' . $r2['nombre'] . '" />'; }
		} else { $cargo = ''; }

		if ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) { $boton = boton('Editar', '/examenes/editar/' . $r['ID'] . '/', 'm'); } 
		else { $boton = ''; }

		if ($r['aprobados'] > 0) {
			$aprobados = round(($r['aprobados'] * 100) / $r['examinados']) . '%';	
		} else { $aprobados = '0%'; }


		if (($r['num_preguntas']+$num_generales) > 0) {
			$url = '<a href="/examenes/' . $r['ID'] . '/"><b>' . $r['titulo'] . '</b></a>';
		} else {
			$url = '<b>' . $r['titulo'] . '</b>';
		}



		$txt .= '<tr>
<td valign="top">' . $r['num_preguntas'] . ' de ' . $r['num_preguntas_especificas'] . '</td>
<td valign="top"><b style="color:grey;">' . $r['nota'] . '</b></td>
<td valign="top" align="right">' . $aprobados . '</td>
<td valign="top" align="right">' . $r['aprobados'] . '</td>
<td valign="top">' . $cargo . '</td>
<td valign="top">' . $url . '</td>
<td valign="top">' . $boton . '</td>
</tr>';
	}

	$txt .= '</table>';

	if (nucleo_acceso($vp['acceso']['examenes_decano'])) {
		$txt .= '<p>' . boton_cargo('Crear examen', '/examenes/crear/', 35) . '</p>';
	}

}

//THEME
if (!$txt_title) { $txt_title = 'Examenes'; }
include('theme.php');
?>
