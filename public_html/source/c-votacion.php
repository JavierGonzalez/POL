<?php 
include('inc-login.php');

$votaciones_tipo = array('sondeo', 'referendum', 'parlamento', 'cargo', 'elecciones');


// FINALIZAR VOTACIONES
$result = mysql_query("SELECT ID, tipo, tipo_voto, num, pregunta, respuestas, ejecutar, privacidad, acceso_ver FROM votacion 
WHERE estado = 'ok' AND pais = '".PAIS."' AND (time_expire <= '".$date."' OR ((votos_expire != 0) AND (num >= votos_expire)))", $link);
while($r = mysql_fetch_array($result)){
	
	// Finaliza la votación
	mysql_query("UPDATE votacion SET estado = 'end', time_expire = '".$date."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);

	include_once('inc-functions-accion.php');

	if ($r['acceso_ver'] == 'anonimos') {
		evento_chat('<b>['.strtoupper($r['tipo']).']</b> Finalizado, resultados: <a href="/votacion/'.$r['ID'].'"><b>'.$r['pregunta'].'</b></a> <span style="color:grey;">(votos: <b>'.$r['num'].'</b>)</span>');
	}

	if ($r['ejecutar'] != '') { // EJECUTAR ACCIONES

		$validez_voto['true'] = 0; $validez_voto['false'] = 0; $voto[0] = 0; $voto[1] = 0; $voto[2] = 0; $voto_preferencial = array();
		$result2 = mysql_query("SELECT validez, voto FROM votacion_votos WHERE ref_ID = ".$r['ID']."", $link);
		while($r2 = mysql_fetch_array($result2)) {
			$validez_voto[$r2['validez']]++;
			if ($r['tipo_voto'] == 'estandar') {
				$voto[$r2['voto']]++;
			} elseif (substr($r['tipo_voto'], 1, 6) == 'puntos') {
				$voto_valor = 0;
				foreach (explode(' ', $r2['voto']) AS $opcion_ID) {
					$voto_valor++;
					$voto_preferencial[$opcion_ID] += $voto_valor;
				}
			}
		}

		// Determina validez: mayoria simple <= votacion nula
		if ($validez_voto['false'] < $validez_voto['true']) { 
			// OK: votación válida
			$cargo_ID = explodear('|', $r['ejecutar'], 1);
			
			switch (explodear('|', $r['ejecutar'], 0)) {

				case 'cargo': // $r['ejecutar'] = cargo|$cargo_ID|$user_ID
					if ($voto[1] > $voto[2]) {
						cargo_add($cargo_ID, explodear('|', $r['ejecutar'], 2), true, true);
					} else {
						cargo_del($cargo_ID, explodear('|', $r['ejecutar'], 2), true, true);
					}
					break;

				case 'elecciones': // $r['ejecutar'] = elecciones|$cargo_ID|$numero_a_asignar
					
					// Quita todos los cargos de las elecciones (reset)
					$result2 = mysql_query("SELECT user_ID FROM cargos_users WHERE cargo_ID = '".$cargo_ID."' AND pais = '".PAIS."' AND cargo = 'true'", $link);
					while($r2 = mysql_fetch_array($result2)) { cargo_del($cargo_ID, $r2['user_ID'], true, true); }

					// Reset campo temporal (más simple que crear tablas temporales)
					mysql_query("UPDATE users SET temp = NULL", $link);
					
					// Añade los resultados de puntos en el campo temporal
					$respuestas = explode('|', $r['respuestas']);
					foreach ($voto_preferencial AS $opcion_ID => $puntos) {
						if ($opcion_ID != 0) { // Ignora "En blanco" por ser no computable
							mysql_query("UPDATE users SET temp = '".$puntos."' WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND nick = '".$respuestas[$opcion_ID]."' LIMIT 1", $link);
							$votacion_preferencial_nick[$respuestas[$opcion_ID]] = $puntos;
						}
					}

					// Asigna ordenando con mysql teniendo en cuenta la antiguedad para desempatar
					$result2 = mysql_query("SELECT ID, nick FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND temp IS NOT NULL ORDER BY temp DESC, fecha_registro ASC LIMIT ".explodear('|', $r['ejecutar'], 2), $link);
					while($r2 = mysql_fetch_array($result2)) { 
						cargo_add($cargo_ID, $r2['ID'], true, true); 
						$guardar[] = $r2['nick'].'.'.$votacion_preferencial_nick[$r2['nick']];
					}
					
					// Guarda escrutinio
					mysql_query("UPDATE votacion SET ejecutar = '".$r['ejecutar']."|".implode(':', $guardar)."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
					
					break;
			}
		}
	}

	// _______ A continuación se rompe la relación Usuario-Voto irreversiblemente ________
	
	if ($r['privacidad'] == 'true') {
		// Rompe la relación Usuario-Voto. Solo en votaciones con secreto de voto.
		barajar_votos($r['ID']); // Esta funcion está documentada en /source/inc-functions-accion.php
	}

	// Actualiza contador de votaciones activas
	$result2 = mysql_query("SELECT COUNT(ID) AS num FROM votacion WHERE estado = 'ok' AND pais = '".PAIS."' AND acceso_ver = 'anonimos'", $link);
	while($r2 = mysql_fetch_array($result2)) {
		mysql_query("UPDATE config SET valor = '".$r2['num']."' WHERE pais = '".PAIS."' AND dato = 'info_consultas' LIMIT 1", $link);
	}
}
// FIN DE FINALIZAR VOTACIONES




// EMPIEZA PRESENTACION

if (($_GET['a'] == 'verificacion') AND ($_GET['b']) AND (isset($pol['user_ID']))) {
	$comprobante_full = $_GET['b'];
	$ref_ID = explodear('-', $comprobante_full, 0);
	$comprobante = explodear('-', $comprobante_full, 1);
	redirect('/votacion/'.$ref_ID.'/verificacion#'.$comprobante);

} elseif ($_GET['a'] == 'crear') {

	unset($votaciones_tipo[4]); 

	$txt_title = 'Borrador de votación';
	$txt_nav = array('/votacion'=>'Votaciones', '/votacion/borradores'=>'Borradores', 'Crear borrador');
	$txt_tab = array('/votacion/borradores'=>'Ver borradores', '/votacion/'.$_GET['b']=>'Previsualizar', '/votacion/crear/'.$_GET['b']=>'Editar borrador');

	// EDITAR
	if (is_numeric($_GET['b'])) {
		$result = mysql_query("SELECT * FROM votacion WHERE estado = 'borrador' AND ID = '".$_GET['b']."' LIMIT 1", $link);
		$edit = mysql_fetch_array($result);
	}


	// Pre-selectores
	if (!isset($edit['ID'])) { $edit['tipo'] = 'sondeo'; $edit['acceso_votar'] = 'ciudadanos'; $edit['acceso_ver'] = 'anonimos'; }
	
	$sel['tipo_voto'][$edit['tipo_voto']] = ' selected="selected"';
	$sel['privacidad'][$edit['privacidad']] = ' selected="selected"';
	
	$sel['tipo'][$edit['tipo']] = ' checked="checked"';
	
	$sel['acceso_votar'][$edit['acceso_votar']] = ' selected="selected"';
	$sel['acceso_ver'][$edit['acceso_ver']] = ' selected="selected"';

	$txt .= '<form action="http://'.strtolower(PAIS).'.'.DOMAIN.'/accion.php?a=votacion&b=crear" method="post">

'.(isset($edit['ID'])?'<input type="hidden" name="ref_ID" value="'.$_GET['b'].'" />':'').'

<table border="0"><tr><td valign="top">
<p class="azul" style="text-align:left;"><b>Tipo de votación</b>:<br />
<span id="tipo_select">';

	$tipo_extra = array(
'sondeo'=>'<span style="float:right;">(informativo, no vinculante)</span>', 
'referendum'=>'<span style="float:right;">(vinculante)</span>',
'parlamento'=>'<span style="float:right;">(vinculante)</span>',
'cargo'=>'<span style="float:right;" title="Se ejecuta una acción automática tras su finalización.">(ejecutiva)</span>',
);

	if (ASAMBLEA) { unset($votaciones_tipo[2]); } // Quitar tipo de votacion de parlamento.

	foreach ($votaciones_tipo AS $tipo) {
		$txt .= '<span style="font-size:18px;"><input type="radio" name="tipo" value="'.$tipo.'" onclick="cambiar_tipo_votacion(\''.$tipo.'\');"'.$sel['tipo'][$tipo].' />'.$tipo_extra[$tipo].ucfirst($tipo).'</span><br >';
	}

	$txt .= '</span><br />

<span id="time_expire">
<b>Duración</b>:

<input type="text" name="time_expire" value="'.(isset($edit['ID'])?round($edit['duracion']/3600):'24').'" style="text-align:right;width:50px;" />

<select name="time_expire_tipo">
<option value="3600" selected="selected">horas</option>
<option value="86400">días</option>
</select></span>


<span id="cargo_form" style="display:none;">
<b>Cargo</b>: 
<select name="cargo">';

	$sel['cargo'][explodear('|', $edit['ejecutar'], 0)] = ' selected="selected"';
	$result = mysql_query("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' ORDER BY nivel DESC", $link);
	while($r = mysql_fetch_array($result)) { $txt .= '<option value="'.$r['cargo_ID'].'"'.$sel['cargo'][$r['cargo_ID']].'>'.$r['nombre'].'</option>'; }

	$txt .= '
</select><br />
Ciudadano: <input type="text" name="nick" value="" size="10" /></span>


<br /><span id="votos_expire">
<b>Finalizar con</b>: <input type="text" name="votos_expire" value="'.($edit['votos_expire']?$edit['votos_expire']:'').'" size="1" maxlength="5" style="text-align:right;" /> votos</span><br />

<span id="tipo_voto">
<b>Tipo de voto</b>: 
<select name="tipo_voto">
<option value="estandar"'.$sel['tipo_voto']['estandar'].'>Una elección (estándar)</option>
<option value="multiple"'.$sel['tipo_voto']['multiple'].'>Múltiple</option>

<optgroup label="Preferencial">
<option value="3puntos"'.$sel['tipo_voto']['3puntos'].'>3 votos (6 puntos)</option>
<option value="5puntos"'.$sel['tipo_voto']['5puntos'].'>5 votos (15 puntos)</option>
<option value="8puntos"'.$sel['tipo_voto']['8puntos'].'>8 votos (36 puntos)</option>
</optgroup>


</select></span>
<br />
<span id="privacidad">
<b>Voto</b>: 
<select name="privacidad">
<option value="true"'.$sel['privacidad']['true'].'>Secreto (estándar)</option>
<option value="false"'.$sel['privacidad']['false'].'>Público</option>
</select>

<br />

<b>Orden de opciones:</b> <input type="checkbox" name="aleatorio" value="true"'.($edit['aleatorio']=='true'?' checked="checked"':'').' /> Aleatorio.
</span>
</p>


</td><td valign="top" align="right">
		
<p id="acceso_votar" class="azul"><b>Acceso para votar:</b><br />
<select name="acceso_votar">';


	$tipos_array = nucleo_acceso('print');
	unset($tipos_array['anonimos']);
	foreach ($tipos_array AS $at => $at_var) {
		$txt .= '<option value="'.$at.'"'.$sel['acceso_votar'][$at].' />'.ucfirst(str_replace("_", " ", $at)).'</option>';
	}

	$txt .= '</select><br />
<input type="text" name="acceso_cfg_votar" size="18" maxlength="500" id="acceso_cfg_votar_var" value="'.$edit['acceso_cfg_votar'].'" /><br />
'.ucfirst(verbalizar_acceso($edit['acceso_votar'], $edit['acceso_cfg_votar'])).'</p>
		
<p id="acceso_ver" class="azul"><b>Acceso ver votación:</b><br />
<select name="acceso_ver">';


	$tipos_array = nucleo_acceso('print');
	foreach ($tipos_array AS $at => $at_var) {
		$txt .= '<option value="'.$at.'"'.$sel['acceso_ver'][$at].' />'.ucfirst(str_replace("_", " ", $at)).'</opcion>';
	}

	$txt .= '</select><br />
<input type="text" name="acceso_cfg_ver" size="18" maxlength="500" id="acceso_cfg_ver_var" value="'.$edit['acceso_cfg_ver'].'" /><br />
'.ucfirst(verbalizar_acceso($edit['acceso_ver'], $edit['acceso_cfg_ver'])).'
</p>

</td></tr></table>

<div class="votar_form">
<p><b>Pregunta</b>: 
<input type="text" name="pregunta" size="57" maxlength="70" value="'.$edit['pregunta'].'" /></p>
</div>

<p><b>Descripción</b>:<br />
<textarea name="descripcion" style="width:600px;height:260px;">
'.strip_tags($edit['descripcion']).'
</textarea></p>

<p><b>URL de debate</b>: (opcional, debe empezar por http://...)<br />
<input type="text" name="debate_url" size="57" maxlength="300" value="'.$edit['debate_url'].'" /></p>

<div class="votar_form">
<p><b>Opciones de voto</b>:
<ul style="margin-bottom:-16px;">
<li><input type="text" name="respuesta0" size="22" value="En Blanco" readonly="readonly" style="color:grey;" /> &nbsp; <a href="#" id="a_opciones" onclick="opcion_nueva();return false;">Añadir opción</a></li>
</ul>
<ol id="li_opciones" style="margin-top:10px;">';

	if (!isset($edit['ID'])) {
		$edit['respuestas'] = 'SI|NO|';
		$edit['respuestas_desc'] = '][][';
	}

	$respuestas = explode("|", $edit['respuestas']);
	$respuestas_desc = explode("][", $edit['respuestas_desc']);
	if ($respuestas[0] == 'En Blanco') { unset($respuestas[0]); }

	foreach ($respuestas AS $ID => $respuesta) {
		if ($respuesta != '') {
			$respuestas_num++;
			// &nbsp; Descripción: <input type="text" name="respuesta_desc'.$respuestas_num.'" size="28" maxlength="500" value="'.$respuestas_desc[$ID].'" /> (opcional)
			$txt .= '<li><input type="text" name="respuesta'.$respuestas_num.'" size="80" maxlength="160" value="'.$respuesta.'" /></li>';
		}
	}

	$txt .= '
</ol>
</p>
</div>
<p><input type="submit" value="Guardar borrador"'.(nucleo_acceso($vp['acceso']['votacion_borrador'])?'':' disabled="disabled"').' style="font-size:18px;" /></p>';

	$txt_header .= '<script type="text/javascript">
campos_num = '.($respuestas_num+1).';
campos_max = 30;

function cambiar_tipo_votacion(tipo) {
	$("#acceso_ver, #acceso_votar, #time_expire, .votar_form, #votos_expire, #tipo_voto, #privacidad").show();
	$("#cargo_form").hide();
	switch (tipo) {
		case "parlamento": $("#acceso_votar, #votos_expire, #privacidad, #acceso_ver").hide(); break;
		case "cargo": $("'.(ASAMBLEA?'':'#acceso_ver, #acceso_votar, ').'#time_expire, .votar_form, #votos_expire, #tipo_voto, #privacidad").hide(); $("#cargo_form").show(); break;
	}
}

function opcion_nueva() {
	$("#li_opciones").append(\'<li><input type="text" name="respuesta\' + campos_num + \'" size="80" maxlength="160" /></li>\');
	if (campos_num >= campos_max) { $("#a_opciones").hide(); }
	campos_num++;
	return false;
}

</script>';

} elseif ($_GET['a'] == 'borradores') { // VER BORRADORES

	$txt_title = 'Borradores de votaciones';
	$txt_nav = array('/votacion'=>'Votaciones', '/votacion/borradores'=>'Borradores de votación');
	$txt_tab = array('/votacion/crear'=>'Crear votación');
	
	$txt .= '<table border="0" cellpadding="1" cellspacing="0" class="pol_table">';

	$result = mysql_query("SELECT ID, duracion, tipo_voto, pregunta, time, time, time_expire, user_ID, estado, num, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver,
(SELECT nick FROM users WHERE ID = votacion.user_ID LIMIT 1) AS nick
FROM votacion
WHERE estado = 'borrador' AND pais = '".PAIS."'
ORDER BY time DESC
LIMIT 500", $link);
	while($r = mysql_fetch_array($result)) {

		if (nucleo_acceso($vp['acceso'][$r['tipo']])) {
			$boton_borrar = boton('X', '/accion.php?a=votacion&b=eliminar&ID='.$r['ID'], '¿Estás seguro de querer ELIMINAR este borrador de votación?', 'small');
			$boton_iniciar = boton('Iniciar', '/accion.php?a=votacion&b=iniciar&ref_ID='.$r['ID'], '¿Estás seguro de querer INICIAR esta votación?', 'small');
		} else {
			$boton_borrar = boton('X', false, false, 'small');
			$boton_iniciar = boton('Iniciar', false, false, 'small');
		}
		
		$txt .= '<tr>
<td valign="top" align="right" nowrap="nowrap"><b>'.ucfirst($r['tipo']).'</b><br />'.$boton_borrar.' '.$boton_iniciar.'<br />'.boton('Previsualizar', '/votacion/'.$r['ID'], false, 'small').'</td>
<td><a href="/votacion/crear/'.$r['ID'].'"><b style="font-size:18px;">'.$r['pregunta'].'</b></a><br />
Creado hace <b><span class="timer" value="'.strtotime($r['time']).'"></span></b> por '.crear_link($r['nick']).', editado hace <span class="timer" value="'.strtotime($r['time_expire']).'"></span>
<br />
Ver: <em title="'.$r['acceso_cfg_ver'].'">'.$r['acceso_ver'].'</em>, votar: <em title="'.$r['acceso_cfg_votar'].'">'.$r['acceso_votar'].'</em>, tipo voto: <em>'.$r['tipo_voto'].'</em>, duración: <em>'.duracion($r['duracion']).'</em></td>
</tr>';
	}
	$txt .= '</table>';



} elseif ($_GET['a']) { // VER VOTACION

	$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = votacion.user_ID LIMIT 1) AS nick, 
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS ha_votado,
(SELECT voto FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS que_ha_votado,
(SELECT validez FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS que_ha_votado_validez,
(SELECT mensaje FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS que_ha_mensaje,
(SELECT comprobante FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS comprobante
FROM votacion
WHERE ID = '".$_GET['a']."' AND pais = '".PAIS."'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {

		if ((!nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver'])) AND ($r['estado'] != 'borrador')) { 
			$txt .= '<p style="color:red;">Esta votación es privada. No tienes acceso para ver su contenido o resultado.</p>'; 
			break; 
		}

		$votos_total = $r['num'];

		$time_expire = strtotime($r['time_expire']);
		$time_creacion = strtotime($r['time']);
		$duracion = duracion($time_expire - $time_creacion);
		$respuestas = explode("|", $r['respuestas']);
		$respuestas_desc = explode("][", $r['respuestas_desc']);
		$respuestas_num = count($respuestas) - 1;
		
		$txt_title = 'Votacion: ' . strtoupper($r['tipo']) . ' | ' . $r['pregunta'];
		$txt_nav = array('/votacion'=>'Votaciones', '/votacion/'.$r['ID']=>strtoupper($r['tipo']));

		if ($r['estado'] == 'ok') { 
			$txt_nav['/votacion/'.$r['ID']] = 'En curso: '.num($votos_total).' votos';
			$txt_tab = array('/votacion/'.$r['ID']=>'Votación', '/votacion/'.$r['ID'].'/info'=>'Más información');

			$tiempo_queda =  '<span style="color:blue;">Quedan '.timer($time_expire, true).'.</span>'; 
		} elseif ($r['estado'] == 'borrador') {
			$txt_nav[] = 'Borrador';
			$txt_tab = array('/votacion/borradores'=>'Ver borradores', '/votacion/'.$r['ID']=>'Previsualizar', '/votacion/crear/'.$r['ID']=>'Editar borrador');

			$tiempo_queda =  '<span style="color:red;">Borrador <span style="font-weight:normal;">(Previsualización de votación)</span></span> ';
		} else { 
			$txt_nav['/votacion/'.$r['ID']] = 'Finalizado: '.num($votos_total).' votos';
			$txt_tab = array('/votacion/'.$r['ID']=>'Votación', '/votacion/'.$r['ID'].'/info'=>'Más información');
			if (isset($pol['user_ID'])) { $txt_tab['/votacion/'.$r['ID'].'/verificacion'] = 'Verificación'; }
			$tiempo_queda =  '<span style="color:grey;">Finalizado</span>'; 
		}


		if ($_GET['b'] == 'info') {
			$time_expire = strtotime($r['time_expire']);
			$time = strtotime($r['time']);
			$txt .= '<h2>Información sobre esta votación:</h2>

<table border="0">

<tr>
<td align="right">Título/Pregunta:</td>
<td><a href="/votacion/'.$r['ID'].'"><b>'.$r['pregunta'].'</b></a></td>
</tr>

<tr>
<td align="right">Estado de la votación:</td>
<td><b>'.($r['estado']=='ok'?'En curso... quedan '.timer($r['time_expire']):'Finalizada, hace'.timer($r['time_expire'])).'</b> ('.num($r['num']).' votos)</td>
</tr>


<tr>
<td align="right">Creado o aprobado por:</td>
<td>'.($r['user_ID']==0?'<b>Sistema VirtualPol</b> (automático, sin intervención humana)':'<b>'.crear_link($r['nick']).'</b>').'</td>
</tr>

<tr>
<td align="right">Tipo de votación:</td>
<td><b>'.ucfirst($r['tipo']).'</b> '.($r['tipo']=='sondeo'?'(No vinculante, informativo)':'(Vinculante)').'</td>
</tr>

<tr>
<td align="right">Tipo de voto:</td>
<td><b>'.ucfirst((substr($r['tipo_voto'], 1, 6)=='puntos'?'preferencial, repartir '.substr($r['tipo_voto'], 0, 1).' puntos':$r['tipo_voto'])).'</b> ('.($r['privacidad']=='true'?'voto secreto':'voto público, no secreto').($r['aleatorio']=='true'?', orden de opciones aleatorio':'').')</td>
</tr>

<tr>
<td align="right">Pueden votar:</td>
<td><b>'.ucfirst(verbalizar_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])).'</b></td>
</tr>

<tr>
<td align="right">Pueden ver la votación:</td>
<td><b>'.ucfirst(verbalizar_acceso($r['acceso_ver'], $r['acceso_cfg_ver'])).'</b></td>
</tr>

<tr>
<td align="right">Fecha creación:</td>
<td><b>'.$r['time'].'</b> ('.timer($r['time'], false, true).')</td>
</tr>

<tr>
<td align="right">Fecha finalización:</td>
<td><b>'.$r['time_expire'].'</b> ('.timer($r['time_expire'], false, true).')</td>
</tr>

<tr>
<td align="right">Duración:</td>
<td><b>'.round($r['duracion']/24/60/60).' días</b>'.($r['estado']=='ok'?gbarra(((time()-$time)*100)/($time_expire-$time)):'').'</td>
</tr>

</table>


<br /><h3>Propiedades de la votación:</h3>
<ul>

<li><b title="Accuracy: el computo de los votos es exacto.">Precisión:</b> Si, el computo de los votos es exacto.</b></li>

<li><b title="Consistency: los resultados son coherentes y estables en el tiempo.">Consistencia:</b> Si, el resultado es coherente y estable en el tiempo. Una vez finalizadas no se puede eliminar o modificar las votaciones.</b></li>

<li><b title="Democracy: solo pueden votar personas autorizadas y una sola vez.">Democracia:</b> Autentificación solida mediante DNIe (y otros certificados) opcional, avanzado sistema de vigilancia del censo de eficacia elevada, con supervisores del censo electos por democracia directa (voto de confianza, cada 7 días).</li>

'.($r['privacidad']=='true'?'

<li><b title="Privacy: el sentido del voto es secreto.">Privacidad:</b> Si, siempre que el servidor no se comprometa mientras la votación está activa. Al finalizar la votación se rompe la relación Usuario-Voto de forma definitiva e irreversible.</li>

<li><b title="Veriability: capacidad publica de comprobar el recuento de votos.">Verificación:</b> Muy alta, con diferentes medidas de transparencia. 1. Se permite verificar el sentido del propio voto mientras la votación está activa. 2. Se hace publico CUANDO vota QUIEN. 3. Sistema de comprobantes que permite verificar -más allá de toda duda- el sentido del voto en el escrutinio público y completo.</li>

':'

<li><b title="Privacy: el sentido del voto es secreto.">Privacidad:</b> NO, el voto es público. Cualquiera puede ver QUÉ vota QUIEN.</li>

<li><b title="Veriability: capacidad pública de comprobar el recuento de votos.">Verificación:</b> Si. Esta votación tiene verificabilidad universal ya que el voto no es secreto.</li>

').'

<li><b title="Posibilidad de modificar el sentido del voto propio en una votación activa.">Rectificación</b> Si.</li>

</ul>';



			

			$result2 = mysql_query("SELECT COUNT(*) AS num FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND mensaje != ''", $link);
			while($r2 = mysql_fetch_array($result2)) { $comentarios_num = $r2['num']; }

			$txt .= '<br /><h3>Comentarios anónimos ('.($r['estado']=='end'?$comentarios_num.' comentarios, '.num(($comentarios_num*100)/$votos_total, 1).'%':'?').')</h3>';
			
			if (nucleo_acceso('ciudadanos_global')) {
				if ($r['estado'] == 'end') { 
					$result2 = mysql_query("SELECT mensaje FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND mensaje != ''", $link);
					while($r2 = mysql_fetch_array($result2)) { $txt .= '<p>'.$r2['mensaje'].'</p>'; }
				} else { $txt .= '<p>Los comentarios estarán visibles al finalizar la votación.</p>'; }
			} else { $txt .= '<p>Para ver los comentarios debes ser ciudadano.</p>'; }
	

		} elseif ($_GET['b'] == 'verificacion') {

			$txt .= '<h2>Verificación de votación</h2>

<p>La información presentada a continuación es la tabla de comprobantes que muestra el escrutinio completo y la relación Voto-Comprobante de esta votación. Esto permite a cualquier votante comprobar el sentido de su voto ejercido más allá de toda duda. Todo ello sin romper el secreto de voto.</p>

'.($r['tipo_voto']!='estandar'?'<p><em>* El tipo de voto de esta votación es múltiple o preferencial. Por razones tecnicas -provisionalmente- se muestra el campo "voto" en bruto.'.($r['tipo_voto']=='multiple'?' 0=En Blanco, 1=SI y 2=NO.':'').'</em></p>':'');

if (substr($r['tipo_voto'], 1, 6) == 'puntos') {
	$txt .= '<p>Opciones de voto: ';
	foreach ($respuestas AS $ID => $opcion) {
		if ($opcion) { $txt .= $ID.'='.$opcion.', '; }
	}
	$txt .= '</p>';
}

$txt .= '
<style>
#tabla_comprobantes td { padding:0 4px; }
#tabla_comprobantes .tcb { color:blue; }
#tabla_comprobantes .tcr { color:red; }
</style>

<table border="0" style="font-family:\'Courier New\',Courier,monospace;" id="tabla_comprobantes">
<tr>
<th title="Conteo de los diferentes sentidos de votos">Contador</th>
<th title="Sentido del voto emitido">Sentido de voto</th>
<th title="Voto de validez/nulidad, es una votación binaria paralela a la votación para determinar la validez de la misma.">Validez</th>
<th title="Código aleatorio relacionado a cada voto">Comprobante</th>
<th title="Comentario emitido junto al voto, anónimo y opcional">Comentario</th>
</tr>';
			if ((!nucleo_acceso('ciudadanos')) AND ($r['estado'] == 'end')) {
				$txt .= '<tr><td colspan="3" style="color:red;"><hr /><b>Tienes que ser ciudadano para ver la tabla de comprobantes.</b></td></tr>';
			} else if (($r['estado'] == 'end') AND (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) {
				$contador_votos = 0;
				$result2 = mysql_query("SELECT user_ID, voto, validez, comprobante, mensaje,
(SELECT nick FROM users WHERE ID = votacion_votos.user_ID LIMIT 1) AS nick
FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND comprobante IS NOT NULL".($r['tipo_voto']=='estandar'?" ORDER BY voto ASC":""), $link);
				while($r2 = mysql_fetch_array($result2)) { 
					$contador_votos++; 
					if ($r2['user_ID'] != 0) { $txt_votantes[] = ($r2['nick']?$r2['nick']:'&dagger;'); }
					$txt .= '<tr id="'.$r2['comprobante'].'">
<td align="right">'.($r['tipo_voto']=='estandar'?++$contador[$r2['voto']]:++$contador).'.</td>
<td nowrap>'.($r['tipo_voto']=='estandar'?'<b>'.$respuestas[$r2['voto']].'</b>':$r2['voto']).'</td>
<td'.($r2['validez']=='true'?' class="tcb">Válida':' class="tcr">Nula').'</td>
<td nowrap>'.$r['ID'].'-'.$r2['comprobante'].'</td>
'.($r2['mensaje']?'<td title="'.$r2['mensaje'].'">Comentario</td>':'').'
</tr>'."\n"; 
				}
				if ($contador_votos == 0) { $txt .= '<tr><td colspan="3" style="color:red;"><hr /><b>Esta votación es anterior al sistema de comprobantes, por lo tanto esta comprobación no es posible.</b></td></tr>'; }
			} else {
				$txt .= '<tr><td colspan="3" style="color:red;"><hr /><b>Esta votación aún no ha finalizado. Cuando finalice se mostrará aquí la tabla de votos-comprobantes.</b></td></tr>';
			}

			$txt .= '</table><p><b>Votantes:</b><br />'.implode(', ', $txt_votantes).'</p>';

		} else {


			$txt_description = 'VirtualPol, la primera red social democrática | '.ucfirst($r['tipo']).' de '.PAIS.': '.$r['pregunta'].'.';
			$txt .= '<div class="amarillo" style="margin-top:5px;">
<h1>'.$r['pregunta'].'</h1>
<div class="rich'.($r['estado']=='end'?' votacion_desc_min':'').'">
'.$r['descripcion'].'
'.(substr($r['debate_url'], 0, 4)=='http'?'<hr /><p><b>Debate sobre esta votación: <a href="'.$r['debate_url'].'">aquí</a>.</b></p>':'').'
</div>
</div>

'.($r['acceso_ver']=='anonimos'&&((!isset($pol['user_ID'])) || ($r['ha_votado']) || ($r['estado']=='end'))?'<center><table border="0" style="margin:5px 0 15px 0;">
<tr>
'.(!isset($pol['user_ID'])?'<td>'.boton('¡Crea tu ciudadano para votar!', REGISTRAR.'?p='.PAIS, false, 'large blue').'</td>':'').'
<td width="20"></td>
<td nowrap="nowrap"><b style="font-size:20px;color:#777;">¡Difúnde esta votación!</b> &nbsp;</td>

<td width="140" height="35">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://'.HOST.'/votacion/'.$r['ID'].'" data-text="'.($r['estado']=='ok'?'VOTACIÓN':'RESULTADO').': '.substr($r['pregunta'], 0, 83).'" data-lang="es" data-size="large" data-related="AsambleaVirtuaI" data-hashtags="15M">Twittear</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</td>


<td width="50"><g:plusone annotation="none" href="http://'.HOST.'/votacion/'.$r['ID'].'"></g:plusone></td>

<td>'.boton('Donar', 'https://virtualpol.com/donaciones', false, 'pill orange').'</td>

<td><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>
<div style="display:inline;" class="fb-like" data-href=http://'.HOST.'/votacion/'.$r['ID'].'" data-send="true" data-layout="button_count" data-width="300" data-show-faces="false" data-action="recommend" data-font="verdana"></div></td>
</tr></table></center>':'').'
';




			// Muestra información de votación (a la derecha)
/*			$txt .= '<span style="float:right;text-align:right;">
'.(isset($r['nick'])?'Creador '.crear_link($r['nick']).'. ':'').'Duración <b>'.$duracion.'</b>.<br />
Acceso de voto: <acronym title="'.$r['acceso_cfg_votar'].'">'.ucfirst(str_replace('_', ' ', $r['acceso_votar'])).'</acronym>'.($r['acceso_ver']!='anonimos'?' (privada)':'').'.<br /> 
Inicio: <em>' . $r['time'] . '</em><br /> 
Fin: <em>' . $r['time_expire'] . '</em><br />
'.($r['votos_expire']!=0?'Finaliza tras  <b>'.$r['votos_expire'].'</b> votos.<br />':'').'
'.($r['tipo_voto']!='estandar'?($r['tipo_voto']=='multiple'?'<b>Votación múltiple</b>':'<b>Votación preferencial</b> ('.$r['tipo_voto'].').').'<br />':'').'
</span>';
*/



			if ($r['estado'] == 'end') {  // VOTACION FINALIZADA: Mostrar escrutinio. 

				// Conteo/Proceso de votos (ESCRUTINIO)
				$escrutinio['votos'] = array(0,0,0,0,0,0,0,0,0,0,0,0);
				$escrutinio['votos_autentificados'] = 0;
				$escrutinio['votos_total'] = 0;
				$escrutinio['validez']['true'] = 0; $escrutinio['validez']['false'] = 0;
				$puntos_total = ($r['tipo_voto']=='estandar'?$votos_total:0);

				$result2 = mysql_query("SELECT voto, validez, autentificado, mensaje FROM votacion_votos WHERE ref_ID = '".$r['ID']."'", $link);
				while($r2 = mysql_fetch_array($result2)) {
					
					switch ($r['tipo_voto']) {

						case 'estandar': $escrutinio['votos'][$r2['voto']]++; break;

						case '3puntos': case '5puntos': case '8puntos': 
							$voto_array = explode(' ', $r2['voto']); $puntos = 1;
							foreach ($voto_array AS $elvoto) {
								if (isset($respuestas[$elvoto])) {
									$escrutinio['votos'][$elvoto] += $puntos;
									$puntos_total += $puntos;
									$puntos++;
								}
							}
							break;

						case 'multiple':
							$voto_array = explode(' ', $r2['voto']);
							foreach ($voto_array AS $voto_ID => $elvoto) {
								if (isset($respuestas[$voto_ID])) { 
									$escrutinio['votos'][$voto_ID] += ($elvoto==2?-1:$elvoto);
									$escrutinio['votos_full'][$voto_ID][$elvoto]++;
								}
							}
							break;
					}

					$escrutinio['validez'][$r2['validez']]++;
					if ($r2['autentificado'] == 'true') { $escrutinio['votos_autentificados']++; }
				}

				// Ordena escrutinio multiple por porcentaje de SI.
				if ($r['tipo_voto'] == 'multiple') { 
					foreach ($escrutinio['votos_full'] AS $voto_ID => $voto_array) {
						$escrutinio['votos'][$voto_ID] = round(($voto_array[1]>0?($voto_array[1]*100)/($voto_array[1] + $voto_array[2])*100:0));
					}
				}

				// Determina validez (por mayoria simple)
				$nulo_limite = ceil(($votos_total)/2);
				if ($escrutinio['validez']['false'] < $escrutinio['validez']['true']) { $validez = true; } else { $validez = false; }

				// Opciones del escrutinio en orden descendente.
				arsort($escrutinio['votos']);

				// Imprime escrutinio en texto.
				$txt .= '<table border="0" cellpadding="0" cellspacing="0"><tr><td valign="top">';

				// Imprime escrutinio en grafico.
				if ($validez == true) { // Solo si el resultado es válido (menos de 50% de votos nulos).
					foreach ($escrutinio['votos'] AS $voto => $num) {
						if ($respuestas[$voto] != 'En Blanco') {
							$grafico_array_votos[] = $num;
							$grafico_array_respuestas[] = (strlen($respuestas[$voto])>=13?trim(substr($respuestas[$voto], 0, 13)).'..':$respuestas[$voto]);
						}
					}

					if ((count($respuestas) <= 8) AND ($r['tipo_voto'] != 'multiple')) { 
						$txt .= '<img src="http://chart.apis.google.com/chart?cht=p&chds=a&chp=4.71&chd=t:'.implode(',', $grafico_array_votos).'&chs=350x175&chl='.implode('|', $grafico_array_respuestas).'&chf=bg,s,ffffff01|c,s,ffffff01&chco=FF9900|FFBE5E|FFD08A|FFDBA6" alt="Escrutinio" width="350" height="175" /><br />'; 
					}
				}

				if ($validez == true) {

					if ($r['tipo_voto'] == 'multiple') {
						$txt .= '<table border="0" cellpadding="1" cellspacing="0" class="pol_table"><tr><th>Escrutinio &nbsp; </th><th>SI</th><th>NO</th><th></th></tr>';
						
						$puntos_total_sin_en_blanco = $puntos_total - $escrutinio['votos'][$en_blanco_ID];

						foreach ($escrutinio['votos'] AS $voto => $num) { 
							if ($respuestas[$voto]) {
								if ($respuestas[$voto] != 'En Blanco') {
									$voto_si = ($escrutinio['votos_full'][$voto][1]?$escrutinio['votos_full'][$voto][1]:0);
									$voto_no = ($escrutinio['votos_full'][$voto][2]?$escrutinio['votos_full'][$voto][2]:0);
									$voto_en_blanco = ($escrutinio['votos_full'][$voto][0]?$escrutinio['votos_full'][$voto][0]:0);

									$txt .= '<tr>
<td'.($respuestas_desc[$voto]?' title="'.$respuestas_desc[$voto].'" class="punteado"':'').'>'.$respuestas[$voto].'</td>
<td align="right"><b>'.$voto_si.'</b></td>
<td align="right">'.$voto_no.'</td>
<td align="right"><b title="Votos computables: '.num($voto_si+$voto_no).', En Blanco: '.$voto_en_blanco.'">'.num(($voto_si>0?($voto_si*100)/($voto_si + $voto_no):0),1).'%</b></td>
</tr>';

								} else { $votos_en_blanco = $num; }
							} else { unset($escrutinio['votos'][$voto]);  }
						}
						$txt .= '</table>';

					} else {
						$txt .= '<table border="0" cellpadding="1" cellspacing="0" class="pol_table"><tr><th>Escrutinio</th><th>'.($r['tipo_voto']=='estandar'?'Votos':'Puntos').'</th><th></th></tr>';
						
						// Obtener ID del voto "En Blanco"
						foreach ($escrutinio['votos'] AS $voto => $num) { if ($respuestas[$voto] == 'En Blanco') { $en_blanco_ID = $voto; } }
						
						$puntos_total_sin_en_blanco = $puntos_total - $escrutinio['votos'][$en_blanco_ID];

						foreach ($escrutinio['votos'] AS $voto => $num) { 
							if ($respuestas[$voto]) {
								if ($respuestas[$voto] != 'En Blanco') {
									$txt .= '<tr><td nowrap="nowrap"'.($respuestas_desc[$voto]?' title="'.$respuestas_desc[$voto].'" class="punteado"':'').'>'.$respuestas[$voto].'</td><td align="right" title="'.num(($num*100)/$puntos_total, 1).'%"><b>'.num($num).'</b></td><td align="right">'.num(($num*100)/$puntos_total_sin_en_blanco, 1).'%</td></tr>';
								} else { $votos_en_blanco = $num; }
							} else { unset($escrutinio['votos'][$voto]);  }
						}
						$txt .= '<tr><td nowrap="nowrap" title="Voto no computable. Equivale a: No sabe/No contesta."><em>En Blanco</em></td><td align="right" title="'.num(($votos_en_blanco*100)/$puntos_total, 1).'%"><b>'.num($votos_en_blanco).'</b></td><td></td></tr></table>';
					}
				}
				

				// Imprime datos de legitimidad y validez
				$txt .= '</td>
<td valign="top" style="color:#888;"><br />
Legitimidad: <span style="color:#555;"><b>'.num($votos_total).'</b>&nbsp;votos</span>, <b>'.$escrutinio['votos_autentificados'].'</b>&nbsp;autentificados.<br />
Validez de esta votación: '.($validez?'<span style="color:#2E64FE;"><b>OK</b>&nbsp;'.num(($escrutinio['validez']['true'] * 100) / $votos_total, 1).'%</span>':'<span style="color:#FF0000;"><b>NULO</b>&nbsp;'.$porcentaje_validez.'%</span>').'<br />
<img width="230" height="130" title="Votos de validez: OK: '.num($escrutinio['validez']['true']).', NULO: '.$escrutinio['validez']['false'].'" src="http://chart.apis.google.com/chart?cht=p&chp=4.71&chd=t:'.$escrutinio['validez']['true'].','.$escrutinio['validez']['false'].'&chs=230x130&chds=a&chl=OK|NULO&chf=bg,s,ffffff01|c,s,ffffff01&chco=2E64FE,FF0000,2E64FE,FF0000" alt="Validez" /></td>
</tr></table>';


			} else { // VOTACION EN CURSO: VOTAR.

				$tiene_acceso_votar = nucleo_acceso($r['acceso_votar'],$r['acceso_cfg_votar']);


				$txt .= '<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=votacion&b=votar" method="post">
<input type="hidden" name="ref_ID" value="'.$r['ID'].'"  /><p>';


				if ($r['tipo_voto'] == 'estandar') {

					if (($r['privacidad'] == 'false') AND (!isset($r['ha_votado']))) { $txt .= '<p style="color:red;">El voto es público en esta votación, por lo tanto NO será secreto.</p>'; }

					for ($i=0;$i<$respuestas_num;$i++) { if ($respuestas[$i]) { 
							$votos_array[] = '<option value="'.$i.'"'.($i==$r['que_ha_votado']?' selected="selected"':'').'>'.$respuestas[$i].'</option>'; 
					} }

					if ($r['aleatorio'] == 'true') { shuffle($votos_array); }

					$txt .= '<select name="voto" style="font-size:20px;white-space:normal;max-width:400px;">'.implode('', $votos_array).'</select>';

				} elseif (($r['tipo_voto'] == '3puntos') OR ($r['tipo_voto'] == '5puntos') OR ($r['tipo_voto'] == '8puntos')) {

					//if ($r['ha_votado']) { $txt .= 'Tu voto preferencial ha sido recogido <b>correctamente</b>.<br /><br />'; }

					$txt .= '<span style="color:red;">Debes repartir <b>los puntos más altos a tus opciones preferidas</b>. Puntos no acumulables.</span>
<table border="0">
<tr>
<th colspan="'.substr($r['tipo_voto'], 0, 1).'" align="center">Puntos</th>
<th></th>
</tr>
<tr>
<th align="center">1</th>
<th align="center">2</th>
<th align="center">3</th>
'.($r['tipo_voto']=='5puntos'?'<th align="center">4</th><th align="center">5</th>':'').'
'.($r['tipo_voto']=='8puntos'?'<th align="center">4</th><th align="center">5</th><th align="center">6</th><th align="center">7</th><th align="center">8</th>':'').'
<th>Opciones'.($r['tipo']=='elecciones'?' / Candidatos':'').'</th>
</tr>';				if ($r['ha_votado']) { $ha_votado_array = explode(' ', $r['que_ha_votado']); }
					else { $ha_votado_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0); }
					for ($i=0;$i<$respuestas_num;$i++) { if ($respuestas[$i]) { 
							$votos_array[] = '<tr>
<td valign="top"><input type="radio" name="voto_1" value="'.$i.'"'.($ha_votado_array[0]==$i?' checked="checked"':'').' /></td>
<td valign="top"><input type="radio" name="voto_2" value="'.$i.'"'.($ha_votado_array[1]==$i?' checked="checked"':'').' /></td>
<td valign="top"><input type="radio" name="voto_3" value="'.$i.'"'.($ha_votado_array[2]==$i?' checked="checked"':'').' /></td>
'.($r['tipo_voto']=='5puntos'?'
<td valign="top"><input type="radio" name="voto_4" value="'.$i.'"'.($ha_votado_array[3]==$i?' checked="checked"':'').' /></td>
<td valign="top"><input type="radio" name="voto_5" value="'.$i.'"'.($ha_votado_array[4]==$i?' checked="checked"':'').' /></td>
':'').'
'.($r['tipo_voto']=='8puntos'?'
<td valign="top"><input type="radio" name="voto_4" value="'.$i.'"'.($ha_votado_array[3]==$i?' checked="checked"':'').' /></td>
<td valign="top"><input type="radio" name="voto_5" value="'.$i.'"'.($ha_votado_array[4]==$i?' checked="checked"':'').' /></td>
<td valign="top"><input type="radio" name="voto_6" value="'.$i.'"'.($ha_votado_array[5]==$i?' checked="checked"':'').' /></td>
<td valign="top"><input type="radio" name="voto_7" value="'.$i.'"'.($ha_votado_array[6]==$i?' checked="checked"':'').' /></td>
<td valign="top"><input type="radio" name="voto_8" value="'.$i.'"'.($ha_votado_array[7]==$i?' checked="checked"':'').' /></td>
':'').'
<td nowrap="nowrap"'.($respuestas_desc[$i]?' title="'.$respuestas_desc[$i].'" class="punteado"':'').'>'.($respuestas[$i]==='En Blanco'?'<em title="Equivale a No sabe/No contesta. No computable.">En Blanco</em>':($r['tipo']=='elecciones'?'<b>'.crear_link($respuestas[$i]).'</b>':$respuestas[$i])).'</td>
</tr>';
					} }
					if ($r['aleatorio'] == 'true') { shuffle($votos_array); }
					$txt .= implode('', $votos_array).'
<tr>
<th align="center">1</th>
<th align="center">2</th>
<th align="center">3</th>
'.($r['tipo_voto']=='5puntos'?'<th align="center">4</th><th align="center">5</th>':'').'
'.($r['tipo_voto']=='8puntos'?'<th align="center">4</th><th align="center">5</th><th align="center">6</th><th align="center">7</th><th align="center">8</th>':'').'
<th></th>
</tr>
</table>';
				} elseif ($r['tipo_voto'] == 'multiple') { // VOTAR MULTIPLE

					if ($r['ha_votado']) { $txt .= 'Tus votos múltiples han sido recogidos <b>correctamente</b>. '; }

					$txt .= '<table border="0">
<tr>
<th>SI</th>
<th>NO</th>
<th nowrap="nowrap"><em>En Blanco</em></th>
<th></th>
</tr>';				if ($r['ha_votado']) { $ha_votado_array = explode(' ', $r['que_ha_votado']); }
					else { $ha_votado_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0); }

					for ($i=0;$i<$respuestas_num;$i++) { if (($respuestas[$i]) AND ($respuestas[$i] != 'En Blanco')) { 
							$votos_array[] = '<tr>
<td valign="top" align="center"><input type="radio" name="voto_'.$i.'" value="1"'.($ha_votado_array[$i]==1?' checked="checked"':'').' /></td>
<td valign="top" align="center"><input type="radio" name="voto_'.$i.'" value="2"'.($ha_votado_array[$i]==2?' checked="checked"':'').' /></td>
<td valign="top" align="center"><input type="radio" name="voto_'.$i.'" value="0"'.($ha_votado_array[$i]==0||!$ha_votado_array[$i]?' checked="checked"':'').' /></td>
<td'.($respuestas_desc[$i]?' title="'.$respuestas_desc[$i].'" class="punteado"':'').'>'.$respuestas[$i].'</td>
</tr>';
					} }
					if ($r['aleatorio'] == 'true') { shuffle($votos_array); }
					$txt .= implode('', $votos_array).'<tr>
<th>SI</th>
<th>NO</th>
<th nowrap="nowrap"><em>En Blanco</em></th>
<th></th>
</tr>
</table>';


				}


				// Imprime boton para votar, aviso de tiempo y votacion correcta/nula.
				$txt .= ' '.boton(($r['ha_votado']?'Modificar voto':'Votar'), ($r['estado']!='borrador'&&$tiene_acceso_votar?'submit':false), false, 'large blue').' <span style="white-space:nowrap;">'.($tiene_acceso_votar?($r['ha_votado']?'<span style="color:#2E64FE;">Puedes modificar tu voto durante <span class="timer" value="'.$time_expire.'"></span>.</span>':'<span style="color:#2E64FE;">Tienes <span class="timer" value="'.$time_expire.'"></span> para votar.</span>'):'<span style="color:red;white-space:nowrap;">'.(!$pol['user_ID']?'<b>Para votar debes <a href="'.REGISTRAR.'?p='.PAIS.'">crear tu ciudadano</a>.</b>':'No tienes acceso para votar.').'</span>').'</span></p>

<p>
<input type="radio" name="validez" value="true"'.($r['que_ha_votado_validez']!='false'?' checked="checked"':'').' /> Votación válida.<br />
<input type="radio" name="validez" value="false"'.($r['que_ha_votado_validez']=='false'?' checked="checked"':'').' /> Votación nula (inválida, inapropiada o tendenciosa).
</p>

<p>Comentario (opcional, secreto y público al finalizar la votación).<br />
<input type="text" name="mensaje" value="'.$r['que_ha_mensaje'].'" size="60" maxlength="160" /></p>
</form>

'.($r['ha_votado']?'<p style="margin-top:30px;">Comprobante de voto:<br />
<input type="text" value="'.$r['ID'].'-'.$r['comprobante'].'" size="60" readonly="readonly" style="color:#AAA;" /> '.boton('Enviar al email', '/accion.php?a=votacion&b=enviar_comprobante&comprobante='.$r['ID'].'-'.$r['comprobante'], false, 'pill').'</p>':'');

			}

			// Añade tabla de escrutinio publico si es votacion tipo parlamento.
			if ($r['tipo'] == 'parlamento') {
				$txt .= '<table border="0" cellpadding="0" cellspacing="3" class="pol_table"><tr><th>Diputado</th><th></th><th colspan="2">Voto</th><th>Mensaje</th></tr>';
				$result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS nick,
(SELECT (SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND ID = users.partido_afiliado LIMIT 1) AS las_siglas FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS siglas,
(SELECT voto FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND user_ID = cargos_users.user_ID LIMIT 1) AS ha_votado,
(SELECT mensaje FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND user_ID = cargos_users.user_ID LIMIT 1) AS ha_mensaje
FROM cargos_users
WHERE pais = '".PAIS."' AND cargo = 'true' AND cargo_ID = '6'
ORDER BY siglas ASC", $link);
				while($r2 = mysql_fetch_array($result2)) {
					if ($r2['ha_votado'] != null) { $ha_votado = ' style="background:blue;"';
					} else { $ha_votado = ' style="background:red;"'; }
					$txt .= '<tr><td><img src="'.IMG.'cargos/6.gif" /> <b>' . crear_link($r2['nick']) . '</b></td><td><b>' . crear_link($r2['siglas'], 'partido') . '</b></td><td' . $ha_votado . '></td><td><b>' . $respuestas[$r2['ha_votado']]  . '</b></td><td style="color:#555;font-size:12px;" class="rich">'.$r2['ha_mensaje'].'</td></tr>';
				}
				$txt .= '</table>';
			}
		}
	}

} else {


	// Calcular votos por hora
	$result = mysql_query("SELECT COUNT(*) AS num FROM votacion_votos WHERE time >= '".date('Y-m-d H:i:s', time() - 60*60*2)."'", $link);
	while($r = mysql_fetch_array($result)) { $votos_por_hora = num($r['num']/2); }

	$result = mysql_query("SELECT COUNT(*) AS num FROM votacion WHERE estado = 'borrador' AND pais = '".PAIS."'", $link);
	while($r = mysql_fetch_array($result)) { $borradores_num = $r['num']; }

	$txt_title = 'Votaciones';
	$txt_nav = array('/votacion'=>'Votaciones');
	$txt_tab = array('/elecciones'=>'Elecciones', '/votacion/borradores'=>'Borradores ('.$borradores_num.')', '/votacion/crear'=>'Crear votación');
	
	$txt .= '
<span style="float:right;text-align:right;">
<b title="Promedio global de las ultimas 2 horas">'.$votos_por_hora.'</b> votos/hora</span>

<span style="color:#888;"><br /><b>Votaciones en curso</b>:</span>
<table border="0" cellpadding="1" cellspacing="0">
<tr>
<th></th>
<th>Votos</th>
<th></th>
<th colspan="3">Finaliza en...</th>
</tr>';
	$mostrar_separacion = true;
	
	$result = mysql_query("SELECT ID, pregunta, time, time_expire, user_ID, estado, num, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver,
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS ha_votado
FROM votacion
WHERE estado = 'ok' AND pais = '".PAIS."'
ORDER BY time_expire ASC
LIMIT 500", $link);
	while($r = mysql_fetch_array($result)) {
		$time_expire = strtotime($r['time_expire']);
		$time = strtotime($r['time']);

		if ((!isset($pol['user_ID'])) OR ((!$r['ha_votado']) AND ($r['estado'] == 'ok') AND (nucleo_acceso($r['acceso_votar'],$r['acceso_cfg_votar'])))) { 
			$votar = boton('Votar', (isset($pol['user_ID'])?'/votacion/'.$r['ID']:REGISTRAR.'?p='.PAIS), false, 'small blue').' ';
		} else { $votar = ''; }

		$boton = '';
		if ($r['user_ID'] == $pol['user_ID']) {
			if ($r['estado'] == 'ok') {
				if ($r['tipo'] != 'cargo') { $boton .= boton('Finalizar', '/accion.php?a=votacion&b=concluir&ID='.$r['ID'], '¿Seguro que quieres FINALIZAR esta votacion?', 'small orange'); }
				$boton .= boton('X', '/accion.php?a=votacion&b=eliminar&ID='.$r['ID'], '¿Seguro que quieres ELIMINAR esta votacion?', 'small red');
			}
		}
		
		if (($r['acceso_ver'] == 'anonimos') OR (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) {
			$txt .= '<tr>
<td width="100"'.($r['tipo']=='referendum'||$r['tipo']=='elecciones'?' style="font-weight:bold;"':'').'>'.ucfirst($r['tipo']).'</td>
<td align="right"><b>'.num($r['num']).'</b></td>
<td>'.$votar.'<a href="/votacion/'.$r['ID'].'" style="'.($r['tipo']=='referendum'||$r['tipo']=='elecciones'?'font-weight:bold;':'').($r['acceso_ver']!='anonimos'?'color:red;" title="Votación privada':'').'">'.$r['pregunta'].'</a></td>
<td nowrap="nowrap" class="gris" align="right">'.timer($time_expire, true).'</td>
<td nowrap="nowrap">'.$boton.'</td>
<td>'.gbarra(((time()-$time)*100)/($time_expire-$time)).'</td>
</tr>';
		}
	}
	$txt .= '</table>';



	$txt_header .= '<script type="text/javascript">

function ver_votacion(tipo) {
	var estado = $("#c_" + tipo).is(":checked");
	if (estado) {
		$(".v_" + tipo).show();
	} else {
		$(".v_" + tipo).hide();
	}
}

</script>';
	

$txt .= '<span style="color:#888;"><br /><b>Finalizadas</b>:</span> &nbsp; &nbsp; 

<span style="color:#666;padding:3px 4px;border:1px solid #999;border-bottom:none;" class="redondeado"><b>
<input type="checkbox" onclick="ver_votacion(\'referendum\');" id="c_referendum" checked="checked" /> Referendums &nbsp; 
'.(ASAMBLEA?'':'<input type="checkbox" onclick="ver_votacion(\'parlamento\');" id="c_parlamento" checked="checked" /> Parlamento &nbsp; ').' 
<input type="checkbox" onclick="ver_votacion(\'sondeo\');" id="c_sondeo" checked="checked" /> Sondeos</b> &nbsp; 
<input type="checkbox" onclick="ver_votacion(\'cargo\');" id="c_cargo" /> Cargos &nbsp; 
<input type="checkbox" onclick="ver_votacion(\'privadas\');" id="c_privadas" /> <span style="color:red;">Privadas</span> &nbsp; 
</span>

<hr />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">
';
	$mostrar_separacion = true;
	$result = mysql_query("SELECT ID, pregunta, time, time_expire, user_ID, estado, num, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver
FROM votacion
WHERE estado = 'end' AND pais = '".PAIS."'
ORDER BY time_expire DESC
LIMIT 500", $link);
	while($r = mysql_fetch_array($result)) {
		$time_expire = strtotime($r['time_expire']);
		
		if (($r['acceso_ver'] == 'anonimos') OR (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) {
			$txt .= '<tr class="v_'.$r['tipo'].($r['acceso_ver']!='anonimos'?' v_privadas':'').'"'.(in_array($r['tipo'], array('referendum', 'parlamento', 'sondeo', 'elecciones'))&&$r['acceso_ver']=='anonimos'?'':' style="display:none;"').'>
<td width="100"'.($r['tipo']=='referendum'||$r['tipo']=='elecciones'?' style="font-weight:bold;"':'').'>'.ucfirst($r['tipo']).'</td>
<td align="right"><b>'.num($r['num']).'</b></td>
<td><a href="/votacion/'.$r['ID'].'" style="'.($r['tipo']=='referendum'||$r['tipo']=='elecciones'?'font-weight:bold;':'').($r['acceso_ver']!='anonimos'?'color:red;" title="Votación privada':'').'">'.$r['pregunta'].'</a></td>
<td nowrap="nowrap" align="right" class="gris">'.timer($time_expire, true).'</td>
<td></td>
</tr>';
		}
	}
	$txt .= '</table>';



}



//THEME
$txt_menu = 'demo';
include('theme.php');
?>