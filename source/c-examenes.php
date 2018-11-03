<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');

function boton_cargo($value, $url, $cargo) {
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
	return '<span class="amarillo"><input' . $val . ' value="' . $value . '" /> '._('Cargo requerido').': <em>' . $text . '</em></span>';
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

// carga config
$result = mysql_query("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'", $link);
while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

// INIT
if (($_GET['a'] == 'editar') AND (((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) AND ($pol['estado'] == 'ciudadano'))) { 		// EDITAR

	if (!$_GET['b']) { $_GET['b'] = 0; }

	$result = mysql_query("SELECT ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas
FROM examenes
WHERE pais = '".PAIS."' AND ID = '".$_GET['b']."'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){
		
		$txt_title = _('Editar examen');
		$txt_nav = array('/examenes'=>_('Exámenes'), _('Editar examen').': '.$r['titulo']);
		$txt_tab = array('/cargos'=>_('Cargos'));

		$txt .= '
<h2>'._('Añadir pregunta').':</h2>
<div id="edit">
<form action="'.accion_url().'a=examenes&b=nueva-pregunta&ID='.$_GET['b'].'" method="post">
<ol>
<li><b>'._('Pregunta').':</b> ('._('máximo').' 200 '._('caracteres').')<b><br />
&iquest;<input type="text" name="pregunta" autocomplete="off" size="50" maxlength="200" />?</b></li>

<li><b>'._('Respuestas').':</b> ('._('entre').' 2 '._('y').' 4 '._('respuestas').', '._('máximo').' 100 '._('caracteres').')<br />
<input type="text" name="respuesta0" size="35" maxlength="100" autocomplete="off" style="border:2px solid grey;" /> <b>(Correcta)</b><br />
<input type="text" name="respuesta1" size="35" maxlength="100" autocomplete="off" /><br />
<input type="text" name="respuesta2" size="35" maxlength="100" autocomplete="off" /><br />
<input type="text" name="respuesta3" size="35" maxlength="100" autocomplete="off" /></li>

<li><b>'._('Tiempo para responder').':</b> <input type="text" name="tiempo" value="10" size="1" maxlength="3" style="text-align:right;" /> '._('segundos').'</li>

<li>'.boton_cargo(_('Añadir pregunta'), false, 34).'</li>
</ol>
</form>

<hr />

<h2>'._('Preguntas').':</h2>
<ol id="lista">';

		$result2 = mysql_query("SELECT ID, examen_ID, user_ID, pregunta, respuestas, tiempo,
(SELECT nick FROM users WHERE ID = examenes_preg.user_ID LIMIT 1) AS nick
FROM examenes_preg
WHERE pais = '".PAIS."' AND examen_ID = '".$_GET['b']."'
ORDER BY time DESC", $link);
		while($r2 = mysql_fetch_array($result2)){
			$respuestas = '';
			$res = explode("|", $r2['respuestas']);
			foreach($res as $ID => $respuesta) {
				$respuestas .= '<option value="' . $ID . '">' . $respuesta . '</option>';
			}

			if ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR ((nucleo_acceso($vp['acceso']['examenes_profesor'])) AND ($r2['user_ID'] == $pol['user_ID']))) { $boton = boton('x', accion_url().'a=examenes&b=eliminar-pregunta&ID=' . $r2['ID'] . '&re_ID=' . $r['ID'], '&iquest;Seguro que quieres ELIMINAR esta pregunta y sus respuestas?', 'small red'); } else { $boton = ''; }

			$txt .= '<li>¿'.$r2['pregunta'].'? &nbsp; ('.$r2['tiempo'].' seg)<br /><select name="p" style="width:60px;"><option value=""></option>'.$respuestas.'</select> '.$boton.' '.crear_link($r2['nick']).'</li>';
		}
	
	
		$txt .= '</ol>';

		if ($_GET['b'] != 0) {
			if (substr($r['cargo_ID'], 0, 1) != '-') { $readonly = ' readonly="readonly"'; } else { $readonly = ''; }
			$txt .= '<hr />
<h2>'._('Editar examen').':</h2>
<form action="'.accion_url().'a=examenes&b=editar-examen&ID=' . $r['ID'] . '" method="post">
<ol>

<li><b>'._('Titulo del examen').':</b> <input type="text" name="titulo" size="15" maxlength="30" value="' . $r['titulo'] . '"' . $readonly . ' /></li>

<li><b>'._('Temario').':</b> '._('descripción breve y precisa').' <span style="color: red">Campo requerido</span><br />
<textarea name="descripcion" style="color: green; font-weight: bold; width: 570px; height: 100px;">' . strip_tags($r['descripcion']) . '</textarea></li>

<li><b>'._('Nota para aprobar').':</b> <input type="text" name="nota" size="3" maxlength="4" value="' . $r['nota'] . '" style="text-align:right;" /></li>

<li><b>'._('Extensión').':</b> <input type="text" name="num_preguntas" size="3" maxlength="4" value="' . $r['num_preguntas'] . '" style="text-align:right;" /> '._('preguntas').'</li>

<li>'.boton_cargo(_('Editar examen'), false, 35).'</li>

</ol>
</form>';
			if ((nucleo_acceso($vp['acceso']['examenes_decano'])) AND ($r['cargo_ID'] < 0))  {
				$result3 = mysql_query("SELECT (SELECT COUNT(*) FROM examenes_preg WHERE pais = '".PAIS."' AND examen_ID = examenes.ID LIMIT 1) AS num_depreguntas
FROM examenes WHERE pais = '".PAIS."' AND ID = '" . $_GET['b'] . "' LIMIT 1", $link);
				while($r3 = mysql_fetch_array($result3)){ 
					if ($r3['num_depreguntas'] == 0) {
						$txt .='<hr />
<form action="'.accion_url().'a=examenes&b=eliminar-examen" method="post">
<input type="hidden" name="ID" value="' . $r['ID'] . '" /> 
<input type="submit" value="'._('Eliminar examen').'"/>
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
	$result = mysql_query("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'", $link);
	while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

	$txt_title = _('Mis exámenes');
	$txt_nav = array('/examenes'=>_('Exámenes'), _('Mis exámenes'));
	$txt_tab = array('/cargos'=>_('Cargos'));

	$txt .= '<table border="0" cellspacing="0" cellpadding="2">
<tr>
<th></th>
<th>'._('Nota').'</th>
<th>'._('Examen').'</th>
<th></th>
<th>'._('Hace').'</th>
</tr>';

	$result = mysql_query("SELECT cargo_ID, user_ID, time, aprobado, cargo, nota, 
(SELECT titulo FROM examenes WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS nombre_examen,
(SELECT ID FROM examenes WHERE pais = '".PAIS."' AND cargo_ID = cargos_users.cargo_ID LIMIT 1) AS examen_ID
FROM cargos_users
WHERE user_ID = '" . $pol['user_ID'] . "'
ORDER BY aprobado ASC, nota DESC", $link);
	while($r = mysql_fetch_array($result)){
		if ($r['aprobado'] == 'ok') { $sello = '<img src="'.IMG.'varios/estudiado.gif" alt="Aprobado" title="Aprobado" border="0" />'; } else { $sello = ''; }
		if ($r['cargo'] == 'true') { $cargo = '('._('Cargo ejercido').')'; } else { $cargo = ''; }
		if (($r['cargo_ID'] <= 0) AND (time()-strtotime($r['time']) > $pol['config']['examen_repe']*6)) {
			$caducar_examen = ' <form action="'.accion_url().'a=examenes&b=caducar_examen&ID='.$r['cargo_ID'].'" method="POST"><input type="hidden" name="pais" value="'.$pol['pais'].'" /><input type="submit" value="X"  onclick="if (!confirm(\'&iquest;Seguro que quieres que CADUQUE el examen de ' . $r['nombre_examen'] . '?\')) { return false; }"/></form>';
		} else { $caducar_examen = ''; }
		$txt .= '<tr><td>' . $sello . '</td><td align="right"><b style="color:grey;">' . $r['nota'] . '</b></td><td><a href="/examenes/' . $r['examen_ID'] . '"><b>' . $r['nombre_examen'] . '</b></a></td><td>' . $cargo . '</td><td align="right"><acronym title="' . $r['time'] . '">' . duracion(time() - strtotime($r['time'])) .  '</acronym></td><td><b>'. $caducar_examen .'</b></td></tr>';
	}

	$txt .= '</table>';


} elseif (($_GET['a'] == 'crear') AND ($pol['estado'] == 'ciudadano')) { 	// CREAR NUEVA
	$txt .= '<h1><a href="/examenes/">Examenes</a>: '._('Crear examen').'</h1>

<form action="'.accion_url().'a=examenes&b=crear" method="post">

<ol>

<li><b>'._('Examen').':</b> <input type="text" name="titulo" size="15" maxlength="30" /><br /><br /></li>

<li>' . boton_cargo(_('Crear examen'), false, 35) . '</li>

</ol>

</form>';
} elseif (($_GET['a'] == 'examen') AND ($_GET['b']) AND ($_GET['b'] != 0) AND ($pol['estado'] == 'ciudadano')) { 
														// HACER EXAMEN

	$result = mysql_query("SELECT ID, titulo, user_ID, time, cargo_ID, nota, num_preguntas,
(SELECT time FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = examenes.cargo_ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS fecha_ultimoexamen,
(SELECT COUNT(*) FROM examenes_preg WHERE pais = '".PAIS."' AND examen_ID = examenes.ID LIMIT 1) AS num_preguntas_especificas,
(SELECT COUNT(*) FROM examenes_preg WHERE pais = '".PAIS."' AND examen_ID = 0 LIMIT 1) AS num_preguntas_generales
FROM examenes
WHERE pais = '".PAIS."' AND ID = '".$_GET['b']."'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		$preguntas_disponibles = $r['num_preguntas_especificas'] + $r['num_preguntas_generales'];
		$margen_ultimoexamen = strtotime($r['fecha_ultimoexamen']) + $pol['config']['examen_repe'];
		if (((!$r['fecha_ultimoexamen']) OR ($margen_ultimoexamen < time())) AND ($pol['pols'] >= $pol['config']['pols_examen'])) {

			// marca examen como hecho	
			if ($r['fecha_ultimoexamen']) {
				//update 
				mysql_query("UPDATE cargos_users SET time = '" . $date . "', nota = 0.0, aprobado = 'no' WHERE cargo_ID = '" . $r['cargo_ID'] . "' AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
			} else {
				//insert
				mysql_query("INSERT INTO cargos_users 
(cargo_ID, pais, user_ID, time, aprobado, nota) 
VALUES ('" . $r['cargo_ID'] . "', '".PAIS."', '" . $pol['user_ID'] . "', '" . $date . "', 'no', '0.0')", $link);
			}	

			// Cobrar examen
			if ($pol['config']['pols_examen'] != '0') {
				include('inc-functions-accion.php');
				pols_transferir($pol['config']['pols_examen'], $pol['user_ID'], '-1', _('Examen').': ' . $r['titulo']);
			}


			// EMPIEZA EXAMEN

			$txt_title = _('Examen');
			$txt_nav = array('/examenes'=>_('Exámenes'), $r['titulo']);

			$txt .= '<p>'._('Tienes').' <b><span class="seg"></span></b> '._('segundos').'.</p>


<table id="latabla" border="0">
<tr>

<td style="background:red;" id="t_mas" width="12" height="1"></td>

<td rowspan="2">


<div id="examen">
<form action="/accion.php?a=examenes&b=examinar&ID='.$_GET['b'].'" method="post" id="elexamen">
<ol>';


			$examen_tiempo = 0;
			$respuestas_correctas = array();
			$result2 = mysql_query("SELECT ID, examen_ID, user_ID, time, pregunta, respuestas, tiempo
FROM examenes_preg
WHERE pais = '".PAIS."' AND (examen_ID = '" . $_GET['b'] . "' OR examen_ID = 0)
ORDER BY examen_ID DESC, RAND() LIMIT ".mysql_real_escape_string($r['num_preguntas']), $link);
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
					$respuestas .= '<input type="radio" name="respuesta'.$r2['ID'].'" value="'.md5($respuesta).'" id="respuesta'.$r2['ID'].'_'.md5($respuesta).'" /><label for="respuesta'.$r2['ID'].'_'.md5($respuesta).'">'.$respuesta.'</label><br />';
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

<p>'.boton(_('Terminar examen'), 'submit', false, 'large blue').' &nbsp; '._('Tienes').' <b><span class="seg"></span></b> '._('segundos').'.</p>
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

		} else { redirect('/examenes/'.$r['ID']); }
	}





} elseif (($_GET['a']) AND ($_GET['a'] != 0)) {				// VER EXAMEN

	$result = mysql_query("SELECT ID, titulo, descripcion, user_ID, time, cargo_ID, nota, num_preguntas,
(SELECT COUNT(*) FROM examenes_preg WHERE pais = '".PAIS."' AND examen_ID = examenes.ID LIMIT 1) AS num_preguntas_especificas,
(SELECT time FROM cargos_users WHERE cargo_ID = examenes.cargo_ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS fecha_ultimoexamen
FROM examenes
WHERE pais = '".PAIS."' AND ID = '" . $_GET['a'] . "'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){

		$txt_title = _('Examen').' '._('de').' ' . $r['titulo'];
		$txt_nav = array('/examenes'=>_('Exámenes'), $r['titulo']);
		$txt_tab = array('/cargos'=>_('Cargos'));

		$txt .= '<table border="0" width="100%"><tr><td valign="top" width="60%">

<fieldset><legend>'._('Temario').'</legend>
<p class="rich">'.$r['descripcion'].'</p>
</fieldset>

<p>'._('Nota mínima para aprobar').': <b class="gris">' . $r['nota'] . '</b>. '._('Examen tipo test, tiempo limitado').', <b>' . $r['num_preguntas'] . '</b> '._('preguntas').' '._('de').' '._('entre').' <b>' . $r['num_preguntas_especificas'] . '</b> '._('en').' '._('total').'.</p>

<p>'._('No podrás repetir examen hasta').' <b>' . duracion($pol['config']['examen_repe']) . '</b> '._('después').'. ';

		if ($r['cargo_ID'] == 0) {
			$txt .= _('Examen sin vinculación con cargo').'.';
		} else {
			$result2 = mysql_query("SELECT nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ $txt .= _('Examen vinculado al cargo').': <a href="/cargos/">' . $r2['nombre'] . '</a>.'; $cargo_nom = $r2['nombre']; }	
		}

		$txt .= '</p>';

		$margen_ultimoexamen = strtotime($r['fecha_ultimoexamen']) + $pol['config']['examen_repe'];
		if ((!$r['fecha_ultimoexamen']) OR ($margen_ultimoexamen < time())) {
			$txt .= '<p>'.boton(_('HACER EXAMEN'), '/examenes/examen/'.$r['ID'], '¿Estás preparado para EXAMINARTE?\n\nSolo podrás intentarlo UNA VEZ cada '.duracion($pol['config']['examen_repe']).'.\n\nSi ejerces el cargo y suspendes lo perderás!', 'large blue', $pol['config']['pols_examen']).' '.($cargo_nom?' [para postularse como candidato a <img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" /> <b>'.$cargo_nom.'</b>]':'').'</p>';
		} else {
			$txt .= '<p><b class="amarillo">'._('No puedes repetir el examen hasta dentro de').' '.duracion($margen_ultimoexamen - time()).'</b></p>';
		}

		$txt .= '</td><td valign="top" width="40%"></td></tr></table>';

	}



} else {	// VER LISTA EXAMENES

	$txt_title = _('Exámenes');
	$txt_nav = array('/examenes'=>_('Exámenes'));
	$txt_tab = array('/cargos'=>_('Cargos'));

	$result = mysql_query("SELECT examen_ID FROM examenes_preg WHERE pais = '".PAIS."'", $link);
	while($r = mysql_fetch_array($result)){ 
		 if ($r['examen_ID'] == 0) { $num_generales++; } else { $num_especificas++; }
	}
	
	if ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) { $boton = boton(_('Editar'), '/examenes/editar', false, 'small'); } 
	$boton = '';

	$txt .= '
<table border="0" cellspacing="0" cellpadding="2" class="pol_table">
<tr>
<th>'._('Preguntas').'</th>
<th>'._('Notas').'</th>
<th colspan="2">'._('Aprobados').'</th>
<th></th>
<th>'._('Examen').'</th>
<th class="gris">ID</th>
<th></th>
</tr>';


	$result = mysql_query("SELECT ID, titulo, user_ID, time, cargo_ID, nota, num_preguntas,
(SELECT COUNT(*) FROM examenes_preg WHERE pais = '".PAIS."' AND examen_ID = examenes.ID LIMIT 1) AS num_preguntas_especificas,
(SELECT COUNT(*) FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = examenes.cargo_ID) AS examinados,
(SELECT COUNT(*) FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = examenes.cargo_ID AND aprobado = 'ok') AS aprobados
FROM examenes
WHERE pais = '".PAIS."' AND ID != 0
ORDER BY nota DESC, num_preguntas_especificas DESC", $link);
	while($r = mysql_fetch_array($result)){

		if (substr($r['cargo_ID'], 0, 1) != '-') {
			$result2 = mysql_query("SELECT nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '" . $r['cargo_ID'] . "' LIMIT 1", $link);
			while($r2 = mysql_fetch_array($result2)){ $cargo = '<img src="'.IMG.'cargos/' . $r['cargo_ID'] . '.gif" title="' . $r2['nombre'] . '" />'; }
		} else { $cargo = ''; }

		if ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) { $boton = boton(_('Editar'), '/examenes/editar/'.$r['ID'], false, 'small'); } 
		else { $boton = ''; }

		if ($r['aprobados'] > 0) {
			$aprobados = round(($r['aprobados'] * 100) / $r['examinados']) . '%';	
		} else { $aprobados = '0%'; }


		if ((($r['num_preguntas']+$num_generales) > 0) AND (substr($r['cargo_ID'], 0, 1) == '-')) {
			$url = '<a href="/examenes/'.$r['ID'].'"><b>'.$r['titulo'].'</b></a>';
		} else {
			$url = '<b>'.$r['titulo'].'</b>';
		}



		$txt .= '<tr>
<td valign="top">' . $r['num_preguntas'] . ' de ' . $r['num_preguntas_especificas'] . '</td>
<td valign="top"><b style="color:grey;">' . $r['nota'] . '</b></td>
<td valign="top" align="right">' . $aprobados . '</td>
<td valign="top" align="right">' . $r['aprobados'] . '</td>
<td valign="top">' . $cargo . '</td>
<td valign="top">' . $url . '</td>
<td valign="top" align="right"class="gris">'.$r['ID'].'</td>
<td valign="top">' . $boton . '</td>
</tr>';
	}

	$txt .= '</table>';

	$txt .= '<p><b class="big">' . ($num_generales + $num_especificas) . '</b> '._('preguntas').': <b>' . $num_especificas . '</b> '._('especificas').' + <b>' . $num_generales . '</b> '._('generales').'</p>'; 

	if (nucleo_acceso($vp['acceso']['examenes_decano'])) { $txt_tab = array('/examenes/crear'=>_('Crear exámen')); }

}

//THEME
if (!$txt_title) { $txt_title = _('Exámenes'); }
$txt_menu = 'demo';
include('theme.php');
?>
