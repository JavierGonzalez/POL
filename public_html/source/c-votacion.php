<?php 
include('inc-login.php');

$votaciones_tipo = array('sondeo', 'referendum', 'parlamento', 'cargo');


// ¿FINALIZAR VOTACIONES?
$result = mysql_query("SELECT ID, tipo, num, pregunta, ejecutar, privacidad, acceso_ver FROM votacion 
WHERE estado = 'ok' AND pais = '".PAIS."' AND (time_expire <= '".$date."' OR ((votos_expire != 0) AND (num >= votos_expire)))", $link);
while($r = mysql_fetch_array($result)){
	
	mysql_query("UPDATE votacion SET estado = 'end', time_expire = '".$date."' WHERE ID = '".$r['ID']."' LIMIT 1", $link);

	include_once('inc-functions-accion.php');

	if ($r['acceso_ver'] == 'anonimos') {
		evento_chat('<b>['.strtoupper($r['tipo']).']</b> Finalizado, resultados: <a href="/votacion/'.$r['ID'].'/"><b>'.$r['pregunta'].'</b></a> <span style="color:grey;">(votos: <b>'.$r['num'].'</b>)</span>');
	}

	if ($r['ejecutar'] != '') { // EJECUTAR ACCIONES

		$validez_voto['true'] = 0; $validez_voto['false'] = 0; $voto[0] = 0; $voto[1] = 0; $voto[2] = 0;
		$result2 = mysql_query("SELECT validez, voto FROM votacion_votos WHERE ref_ID = ".$r['ID']."", $link);
		while($r2 = mysql_fetch_array($result2)) {
			$validez_voto[$r2['validez']]++;
			$voto[$r2['voto']]++;
		}

		// Determinar validez: mayoria simple = votacion nula.
		if ($validez_voto['false'] < $validez_voto['true']) { // Validez: OK.
			if ($r['tipo'] == 'cargo') {
				if ($voto[1] > $voto[2]) {
					cargo_add(explodear('|', $r['ejecutar'], 0), explodear('|', $r['ejecutar'], 1), true, true);
				} else {
					cargo_del(explodear('|', $r['ejecutar'], 0), explodear('|', $r['ejecutar'], 1), true, true);
				}
			}
		}
	}
	
	if ($r['privacidad'] == 'true') {
		// Elimina la relacion entre USUARIO y VOTO una vez finaliza. Por privacidad.
		mysql_query("UPDATE votacion_votos SET user_ID = '0', time = NULL WHERE ref_ID = '".$r['ID']."'", $link); 
	}

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

	$algun_acceso_voto = false;
	foreach ($votaciones_tipo AS $tipo) {
		if (!nucleo_acceso($vp['acceso'][$tipo])) { $disabled[$tipo] = ' disabled="disabled"'; } else { $algun_acceso_voto = true; }
	}
	if (nucleo_acceso('supervisores_censo')) { $algun_acceso_voto = true; }

	// SI el usuario es SC puee hacer sondeos tambien.
	$sc = get_supervisores_del_censo();
	if (isset($sc[$pol['user_ID']])) { $disabled['sondeo'] = ''; }

	$txt_header .= '<script type="text/javascript">
campos_num = 3;
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
	$("#li_opciones").append("<li><input type=\"text\" name=\"respuesta" + campos_num + "\" size=\"22\" maxlength=\"34\" /> &nbsp; Descripci&oacute;n: <input type=\"text\" name=\"respuesta_desc" + campos_num + "\" size=\"28\" maxlength=\"500\" value=\"\" /> (opcional)</li>");
	if (campos_num >= campos_max) { $("#a_opciones").hide(); }
	campos_num++;
	return false;
}

</script>';


	$txt .= '<h1><a href="/votacion/">Votaciones</a>: Crear votaci&oacute;n</h1>

<form action="http://'.strtolower(PAIS).'.'.DOMAIN.'/accion.php?a=votacion&b=crear" method="post">

'.($algun_acceso_voto?'':'<p style="color:red;">No tienes acceso para crear votaciones, pero puedes ver las opciones.</p>').'

<table border="0"><tr><td valign="top">
<p class="azul" style="text-align:left;"><b>Tipo de votaci&oacute;n</b>:<br />
<span id="tipo_select">';

	$tipo_extra = array(
'sondeo'=>'<span style="float:right;">(informativo, no vinculante)</span>', 
'referendum'=>'<span style="float:right;">(vinculante)</span>',
'parlamento'=>'<span style="float:right;">(vinculante)</span>',
'cargo'=>'<span style="float:right;" title="Se ejecuta una acci&oacute;n autom&aacute;tica tras su finalizaci&oacute;n.">(ejecutiva)</span>',
);

	if (ASAMBLEA) { unset($votaciones_tipo[2]); }

	foreach ($votaciones_tipo AS $tipo) {
		$disabled['sondeo'] .= ' checked="checked"';	
		$txt .= '<span style="font-size:18px;"><input type="radio" name="tipo" value="'.$tipo.'"'.$disabled[$tipo].' onclick="cambiar_tipo_votacion(\''.$tipo.'\');" />'.$tipo_extra[$tipo].ucfirst($tipo).'</span><br >';
	}

$txt .= '</span><br />

<span id="time_expire">
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
'.(ASAMBLEA?'<option value="604800">7 d&iacute;as</option><option value="864000">10 d&iacute;as</option>':'').'
</select></span>


<span id="cargo_form" style="display:none;">
<b>Cargo</b>: 
<select name="cargo">';

$result = mysql_query("SELECT ID, nombre FROM ".SQL."estudios ORDER BY nivel DESC", $link);
while($r = mysql_fetch_array($result)) { $txt .= '<option value="'.$r['ID'].'">'.$r['nombre'].'</option>'; }

$txt .= '
</select> &nbsp; Ciudadano: <input type="text" name="nick" value="" size="10" /></span>


<br /><span id="votos_expire">
<b>Finalizar con</b>: <input type="text" name="votos_expire" value="" size="1" maxlength="5" style="text-align:right;" /> votos</span><br />

<span id="tipo_voto">
<b>Tipo de voto</b>: 
<select name="tipo_voto">

<option value="estandar" selected="selected">Una elecci&oacute;n (est&aacute;ndar)</option>
<option value="multiple">M&uacute;ltiple</option>

<optgroup label="Preferencial">
<option value="3puntos">3 votos (6 puntos)</option>
<option value="5puntos">5 votos (15 puntos)</option>
<option value="8puntos">8 votos (36 puntos)</option>
</optgroup>


</select></span>
<br />
<span id="privacidad">
<b>Voto</b>: 
<select name="privacidad">
<option value="true" selected="selected">Secreto (est&aacute;ndar)</option>
<option value="false">P&uacute;blico</option>
</select></span>


<br />

<b>Orden de opciones:</b> <input type="checkbox" name="aleatorio" value="true" /> Aleatorio.

</p>


</td><td valign="top" align="right">
		
<p id="acceso_votar" class="azul"><b>Acceso para votar:</b><br />
<select name="acceso_votar">';


	$tipos_array = nucleo_acceso('print');
	foreach ($tipos_array AS $at => $at_var) {
		$txt .= '<option value="'.$at.'"'.($at=='ciudadanos'?' selected="selected"':'').($at=='anonimos'?' disabled="disabled"':'').' />'.ucfirst(str_replace("_", " ", $at)).'</option>';
	}

	$txt .= '</select><br />
<input type="text" name="acceso_cfg_votar" size="18" maxlength="500" id="acceso_cfg_votar_var" value="" /></p>
		
<p id="acceso_ver" class="azul"><b>Acceso ver votaci&oacute;n:</b><br />
<select name="acceso_ver">';


	$tipos_array = nucleo_acceso('print');
	foreach ($tipos_array AS $at => $at_var) {
		$txt .= '<option value="'.$at.'"'.($at=='anonimos'?' selected="selected"':'').' />'.ucfirst(str_replace("_", " ", $at)).'</opcion>';
	}

	$txt .= '</select><br />
<input type="text" name="acceso_cfg_ver" size="18" maxlength="500" id="acceso_cfg_ver_var" value="" /></p>

</td></tr></table>

<div class="votar_form">
<p><b>Pregunta</b>: 
<input type="text" name="pregunta" size="57" maxlength="70" /></p>
</div>

<p><b>Descripci&oacute;n</b>:<br />
<textarea name="descripcion" style="color: green; font-weight: bold; width: 570px; height: 250px;"></textarea></p>

<p><b>URL de debate</b>: (opcional, debe empezar por http://...)<br />
<input type="text" name="debate_url" size="57" maxlength="300" /></p>

<div class="votar_form">
<p><b>Opciones de voto</b>:
<ul style="margin-bottom:-16px;">
<li><input type="text" name="respuesta0" size="22" value="En Blanco" readonly="readonly" style="color:grey;" /> &nbsp; <a href="#" id="a_opciones" onclick="opcion_nueva();return false;">A&ntilde;adir opci&oacute;n</a></li>
</ul>
<ol id="li_opciones">
<li><input type="text" name="respuesta1" size="22" maxlength="34" value="SI" /> &nbsp; Descripci&oacute;n: <input type="text" name="respuesta_desc1" size="28" maxlength="500" value="" /> (opcional)</li>
<li><input type="text" name="respuesta2" size="22" maxlength="34" value="NO" /> &nbsp; Descripci&oacute;n: <input type="text" name="respuesta_desc2" size="28" maxlength="500" value="" /> (opcional)</li>
</ol>
</p>
</div>
<p><input type="submit" value="Iniciar votaci&oacute;n" style="font-size:18px;"'.($algun_acceso_voto?'':' disabled="disabled"').' /> &nbsp; <a href="/votacion/"><b>Ver votaciones</b></a></p>';



} elseif ($_GET['a']) { // VER VOTACION

	$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = votacion.user_ID LIMIT 1) AS nick, 
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS ha_votado,
(SELECT voto FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS que_ha_votado,
(SELECT validez FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS que_ha_votado_validez,
(SELECT mensaje FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS que_ha_mensaje
FROM votacion
WHERE ID = '".$_GET['a']."' AND pais = '".PAIS."'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {

		if (!nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver'])) { 
			$txt .= '<p style="color:red;">Esta votaci&oacute;n es privada. No tienes acceso para ver su contenido o resultado.</p>'; 
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

		if ($r['estado'] == 'ok') { 
			$tiempo_queda =  '<span style="color:blue;">Quedan <span class="timer" value="'.$time_expire.'"></span>.</span>'; 
		} else { $tiempo_queda =  '<span style="color:grey;">Finalizado</span>'; }


		$txt .= '<h1><a href="/votacion/">Votaciones</a>: '.strtoupper($r['tipo']).' &nbsp; &nbsp; &nbsp; '.num($votos_total).' votos | '.$tiempo_queda.'</h1>

<div class="amarillo" style="margin:20px 0 5px 0;padding:20px 10px 0 10px;">
<h1>'.$r['pregunta'].'</h1>
<p style="text-align:left;'.($r['estado']=='end'?'max-height:300px;overflow-y:auto;':'').'">'.$r['descripcion'].'</p>
'.(substr($r['debate_url'], 0, 4)=='http'?'<hr /><p><b>Debate de esta votación: <a href="'.$r['debate_url'].'">aqu&iacute;</a>.</b></p>':'').'
</div>

'.($r['acceso_ver']=='anonimos'?'<table border="0" style="margin-bottom:15px;"><tr>
<td width="20"></td>
<td><b style="font-size:20px;color:#777;">¡Dif&uacute;nde esta votaci&oacute;n!</b> &nbsp;</td>

<td width="140" height="35">
<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://'.strtolower(PAIS).'.'.DOMAIN.'/votacion/'.$r['ID'].'/" data-text="'.($r['estado']=='ok'?'VOTACI&Oacute;N':'RESULTADO').': '.substr($r['pregunta'], 0, 83).'" data-lang="es" data-size="large" data-related="AsambleaVirtuaI" data-hashtags="AsambleaVirtual">Twittear</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</td>

<td><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>
<div style="display:inline;" class="fb-like" data-href="http://'.strtolower(PAIS).'.'.DOMAIN.'/votacion/'.$r['ID'].'/" data-send="true" data-layout="button_count" data-width="300" data-show-faces="false" data-action="recommend" data-font="verdana"></div></td>

</tr></table>':'').'
';


		if ($_GET['b'] == 'info') {
			
			$txt .= '<span id="ver_info"></span><span style="float:right;text-align:right;"><a href="/votacion/'.$r['ID'].'/"><b>Volver a la votaci&oacute;n</b></a></span><table border="0" width="100%"><tr><td valign="top">';
			
			
			$result2 = mysql_query("SELECT COUNT(*) AS num FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND mensaje != ''", $link);
			while($r2 = mysql_fetch_array($result2)) { $comentarios_num = $r2['num']; }

			$txt .= '<h2 style="margin-top:18px;">Comentarios an&oacute;nimos ('.($r['estado']=='end'?$comentarios_num:'*').')</h2>';
			if (nucleo_acceso('ciudadanos_global')) {
				if ($r['estado'] == 'end') { 
					$result2 = mysql_query("SELECT mensaje FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND mensaje != '' ORDER BY RAND()", $link);
					while($r2 = mysql_fetch_array($result2)) { $txt .= '<p>'.$r2['mensaje'].'</p>'; }
				} else { $txt .= '<p>Los comentarios estar&aacute;n visibles al finalizar la votaci&oacute;n.</p>'; }
			} else { $txt .= '<p>Para ver los comentarios debes ser ciudadano.</p>'; }


			if ((($r['privacidad'] == 'false') AND ($r['estado'] == 'end')) OR ($r['estado'] != 'end')) {
				$txt .= '<h2 style="margin-top:18px;">Registro de votos</h2>

<table border="0" cellpadding="3">
<tr>
<th>Quien</th>
<th>Voto</th>
<th>Autentificado</th>
</tr>';
				$orden = 0;
				$result2 = mysql_query("SELECT user_ID, voto, validez, autentificado, (SELECT nick FROM users WHERE ID = user_ID LIMIT 1) AS nick FROM votacion_votos WHERE ref_ID = '".$r['ID']."' ORDER BY RAND()", $link);
				while($r2 = mysql_fetch_array($result2)) {
					$orden++;

					$txt .= '<tr>
<td>'.($r2['user_ID']==0?'*':crear_link($r2['nick'])).'</td>
<td nowrap="nowrap"><b>'.($r['privacidad']=='false'&&$r['estado']=='end'?$respuestas[$r2['voto']]:'*').'</b></td>
<td>'.($r2['autentificado']=='true'?'<span style="color:blue;"><b>SI</b></span>':'<span style="color:grey;">NO</span>').'</td>
</tr>';
				}
				$txt .= '<tr><td colspan="4" nowrap="nowrap">Votos computados: <b>'.$orden.'</b> (Contador: '.$r['num'].')</td></tr></table>';
			}

	
			$txt .= '
</td>
<td valign="top" width="350">

<h2>Propiedades de la votaci&oacute;n:</h2>
<ul>';

			if ($r['privacidad'] == 'true') { // Privacidad SI, voto secreto.
			$txt .= '
<li><b title="Accuracy: el computo de los votos es exacto.">Precisi&oacute;n:</b> Si, el computo de los votos es exacto.</b>

<li><b title="Democracy: solo pueden votar personas autorizadas y una sola vez.">Democracia:</b> Autentificaci&oacute;n solida mediante DNIe (y otros certificados) opcional y avanzado sistema de vigilancia del censo de eficacia elevada.</li>

<li><b title="Privacy: el sentido del voto es secreto.">Privacidad:</b> Si, siempre que el servidor no se comprometa mientras la votaci&oacute;n est&aacute; activa.</li>

<li><b title="Veriability: capacidad publica de comprobar el recuento de votos.">Verificaci&oacute;n:</b> Se permite verificar el sentido del propio voto mientras la votaci&oacute;n est&aacute; activa. Y se hace publico CUANDO vota QUIEN.</li>

<li><b title="Posibilidad de modificar el sentido del voto propio en una votaci&oacute;n activa.">Rectificaci&oacute;n</b> Si.</li>';
} else { // Privacidad NO, voto publico, verificabilidad
	$txt .= '
<li><b title="Accuracy: el computo de los votos es exacto.">Precisi&oacute;n:</b> Si, el computo de los votos es exacto.</b>

<li><b title="Democracy: solo pueden votar personas autorizadas y una sola vez.">Democracia:</b> Autentificaci&oacute;n solida mediante DNIe (y otros certificados) opcional y avanzado sistema de vigilancia del censo de eficacia elevada.</li>

<li><b title="Privacy: el sentido del voto es secreto.">Privacidad:</b> No, el voto es p&uacute;blico.</li>

<li><b title="Veriability: capacidad publica de comprobar el recuento de votos.">Verificaci&oacute;n:</b> Si, verificabilidad universal.</li>

<li><b title="Posibilidad de modificar el sentido del voto propio en una votaci&oacute;n activa.">Rectificaci&oacute;n</b> Si.</li>';
}


$txt .= '</ul>

</td></tr></table>';


		} else {

			$txt .= '<span style="float:right;text-align:right;">
Creador <b>' . crear_link($r['nick']) . '</b>. Duraci&oacute;n <b>'.$duracion.'</b>.<br />
Acceso de voto: <acronym title="'.$r['acceso_cfg_votar'].'">'.ucfirst(str_replace('_', ' ', $r['acceso_votar'])).'</acronym>.<br /> 
Inicio: <em>' . $r['time'] . '</em><br /> 
Fin: <em>' . $r['time_expire'] . '</em><br />
'.($r['votos_expire']!=0?'Finaliza tras  <b>'.$r['votos_expire'].'</b> votos.<br />':'').'
'.($r['tipo_voto']!='estandar'?'<b>Votaci&oacute;n preferencial</b> ('.$r['tipo_voto'].').<br />':'').'
<a href="/votacion/'.$r['ID'].'/info/#ver_info">M&aacute;s informaci&oacute;n</a>.
</span>';

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

				// Determina validez (por mayoria simple)
				$nulo_limite = ceil(($votos_total)/2);
				if ($escrutinio['validez']['false'] < $escrutinio['validez']['true']) { $validez = true; } else { $validez = false; }

				arsort($escrutinio['votos']);


				// Imprime escrutinio en texto.
				$txt .= '<table border="0" cellpadding="0" cellspacing="0"><tr><td valign="top"><b>Resultados:</b><br />';

				// Imprime escrutinio en grafico.
				if ($validez == true) {
					
					foreach ($escrutinio['votos'] AS $voto => $num) {
						if ($respuestas[$voto] != 'En Blanco') {
							$grafico_array_votos[] = $num;
							$grafico_array_respuestas[] = $respuestas[$voto];
						}
					}

					if ((count($respuestas) <= 10) AND ($r['tipo_voto'] != 'multiple')) { $txt .= '<img src="http://chart.apis.google.com/chart?cht=p&chds=a&chp=4.71&chd=t:'.implode(',', $grafico_array_votos).'&chs=350x175&chl='.implode('|', $grafico_array_respuestas).'&chf=bg,s,ffffff01|c,s,ffffff01&chco=FF9900|FFBE5E|FFD08A|FFDBA6" alt="Escrutinio" width="350" height="175" />'; }
				}

				$txt .= '<br />';

				if ($validez==true) {

					if ($r['tipo_voto'] == 'multiple') {
						$txt .= '<table border="0" cellpadding="1" cellspacing="0" class="pol_table"><tr><th>Escrutinio &nbsp; </th><th>SI</th><th>NO</th><th></th></tr>';
						
						// Obtener ID del voto "En Blanco"
						
						$puntos_total_sin_en_blanco = $puntos_total - $escrutinio['votos'][$en_blanco_ID];

						foreach ($escrutinio['votos'] AS $voto => $num) { 
							if ($respuestas[$voto]) {
								if ($respuestas[$voto] != 'En Blanco') {
									$voto_si = ($escrutinio['votos_full'][$voto][1]?$escrutinio['votos_full'][$voto][1]:0);
									$voto_no = ($escrutinio['votos_full'][$voto][2]?$escrutinio['votos_full'][$voto][2]:0);
									$voto_en_blanco = ($escrutinio['votos_full'][$voto][0]?$escrutinio['votos_full'][$voto][0]:0);

									$txt .= '<tr>
<td nowrap="nowrap"'.($respuestas_desc[$voto]?' title="'.$respuestas_desc[$voto].'" class="punteado"':'').'>'.$respuestas[$voto].'</td>
<td align="right"><b>'.$voto_si.'</b></td>
<td align="right">'.$voto_no.'</td>
<td align="right"><b title="Votos computables: '.num($voto_si+$voto_no).', Balance: '.num($num).', En Blanco: '.$voto_en_blanco.'">'.num(($voto_si>0?($voto_si*100)/($voto_si + $voto_no):0),1).'%</b></td>
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
Validez de esta votaci&oacute;n: '.($validez?'<span style="color:#2E64FE;"><b>OK</b>&nbsp;'.num(($escrutinio['validez']['true'] * 100) / $votos_total, 1).'%</span>':'<span style="color:#FF0000;"><b>NULO</b>&nbsp;'.$porcentaje_validez.'%</span>').'<br />
<img width="230" height="130" title="Votos de validez: '.$escrutinio['validez']['true'].' OK, '.$escrutinio['validez']['false'].' NULO" src="http://chart.apis.google.com/chart?cht=p&chp=4.71&chd=t:'.$escrutinio['validez']['true'].','.$escrutinio['validez']['false'].'&chs=230x130&chds=a&chl=OK|NULO&chf=bg,s,ffffff01|c,s,ffffff01&chco=2E64FE,FF0000,2E64FE,FF0000" alt="Validez" /></td>
</tr></table>';


			} else { // VOTACION EN CURSO: Votar.

				$tiene_acceso_votar = nucleo_acceso($r['acceso_votar'],$r['acceso_cfg_votar']);


				$txt .= '<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=votacion&b=votar" method="post">
<input type="hidden" name="ref_ID" value="'.$r['ID'].'"  /><p>';


				if ($r['tipo_voto'] == 'estandar') {
					if ($r['ha_votado']) {
						for ($i=0;$i<$respuestas_num;$i++) { if ($respuestas[$i]) { 
								$votos_array[] = '<option value="'.$i.'"'.($i==$r['que_ha_votado']?' selected="selected"':'').'>'.$respuestas[$i].'</option>'; 
						} }
						$txt .= 'Tu voto ha sido computado <b>correctamente</b>.<br />';
					} else {
						if ($r['privacidad'] == 'false') { $txt .= '<p style="color:red;">El voto es p&uacute;blico en esta votaci&oacute;n, por lo tanto NO ser&aacute; secreto.</p>'; }
						for ($i=0;$i<$respuestas_num;$i++) { if ($respuestas[$i]) { 
								$votos_array[] = '<option value="'.$i.'"'.($respuestas[$i]=='En Blanco'?' selected="selected"':'').'>'.$respuestas[$i].'</option>'; 
						} }
					}
					if ($r['aleatorio'] == 'true') { shuffle($votos_array); }
					$txt .= '<select name="voto" style="font-size:22px;">'.implode('', $votos_array).'</select>';

				} elseif (($r['tipo_voto'] == '3puntos') OR ($r['tipo_voto'] == '5puntos') OR ($r['tipo_voto'] == '8puntos')) {

					if ($r['ha_votado']) { $txt .= 'Tu voto preferencial ha sido recogido <b>correctamente</b>.<br /><br />'; }

					$txt .= '<span style="color:red;">Debes repartir <b>los puntos m&aacute;s altos a tus opciones preferidas</b>. Puntos no acumulables.</span>
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
<th>Opciones</th>
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
<td'.($respuestas_desc[$i]?' title="'.$respuestas_desc[$i].'" class="punteado"':'').'>'.($respuestas[$i]==='En Blanco'?'<em title="Equivale a No sabe/No contesta. No computable.">En Blanco</em>':$respuestas[$i]).'</td>
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

					if ($r['ha_votado']) { $txt .= 'Tus votos han sido recogidos <b>correctamente</b>. '; }

					$txt .= 'Esta votaci&oacute;n es m&uacute;ltiple.
<table border="0">
<tr>
<th>SI</th>
<th>NO</th>
<th><em>En Blanco</em></th>
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
<th><em>En Blanco</em></th>
<th></th>
</tr>
</table>';


				}


				// Imprime boton para votar, aviso de tiempo y votacion correcta/nula.
				$txt .= '
<input type="submit" value="'.($r['ha_votado']?'Modificar voto':'Votar').'" style="font-size:22px;"'.($tiene_acceso_votar?'':' disabled="disabled"').' /> '.($tiene_acceso_votar?($r['ha_votado']?'<span style="color:#2E64FE;">Puedes modificar tu voto durante <span class="timer" value="'.$time_expire.'"></span>.</span>':'<span style="color:#2E64FE;">Tienes <span class="timer" value="'.$time_expire.'"></span> para votar.</span>'):'<span style="color:red;white-space:nowrap;">'.(!$pol['user_ID']?'<b>Para votar debes <a href="'.REGISTRAR.'?p='.PAIS.'">crear tu ciudadano</a>.</b>':'No tienes acceso para votar.').'</span>').'</p>

<p style="margin-top:-10px;">
<input type="radio" name="validez" value="true"'.($r['que_ha_votado_validez']!='false'?' checked="checked"':'').' /> Votaci&oacute;n correcta.<br />
<input type="radio" name="validez" value="false"'.($r['que_ha_votado_validez']=='false'?' checked="checked"':'').' /> Votaci&oacute;n nula (inv&aacute;lida, inapropiada o tendenciosa).
</p>

<p>Comentario (opcional, secreto y p&uacute;blico al terminar la votaci&oacute;n).<br />
<input type="text" name="mensaje" value="'.$r['que_ha_mensaje'].'" size="60" maxlength="160" /></p>

</form>';
			}

			// A&ntilde;ade tabla de escrutinio publico si es votacion tipo parlamento.
			if ($r['tipo'] == 'parlamento') {
				$txt .= '<table border="0" cellpadding="0" cellspacing="3" class="pol_table"><tr><th>Diputado</th><th></th><th colspan="2">Voto</th><th>Mensaje</th></tr>';
				$result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM users WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick,
(SELECT (SELECT siglas FROM ".SQL."partidos WHERE ID = users.partido_afiliado LIMIT 1) AS las_siglas FROM users WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS siglas,
(SELECT voto FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND user_ID = ".SQL."estudios_users.user_ID LIMIT 1) AS ha_votado,
(SELECT mensaje FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND user_ID = ".SQL."estudios_users.user_ID LIMIT 1) AS ha_mensaje
FROM ".SQL."estudios_users
WHERE cargo = '1' AND ID_estudio = '6'
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

	$txt_title = 'Votaciones';
	$txt .= '<h1>Votaciones: &nbsp; &nbsp; '.(isset($pol['user_ID'])?boton('Crear votaci&oacute;n', '/votacion/crear/'):boton('Crear ciudadano', REGISTRAR.'?p='.PAIS)).'</h1>

<span style="float:right;" title="Promedio global de las ultimas 2 horas"><b>'.$votos_por_hora.'</b> votos/hora</span>


<span style="color:#888;"><br /><b>En curso</b>:</span><hr />
<table border="0" cellpadding="1" cellspacing="0" class="pol_table">';
	$mostrar_separacion = true;
	// (SELECT nick FROM users WHERE ID = votacion.user_ID LIMIT 1) AS nick,
	$result = mysql_query("SELECT ID, pregunta, time, time_expire, user_ID, estado, num, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver,
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS ha_votado
FROM votacion
WHERE estado = 'ok' AND pais = '".PAIS."'
ORDER BY time_expire DESC
LIMIT 500", $link);
	while($r = mysql_fetch_array($result)) {
		$time_expire = strtotime($r['time_expire']);

		if ((!isset($pol['user_ID'])) OR ((!$r['ha_votado']) AND ($r['estado'] == 'ok') AND (nucleo_acceso($r['acceso_votar'],$r['acceso_cfg_votar'])))) { 
			$votar = boton('Votar', '/votacion/'.$r['ID'].'/');
		} else { $votar = ''; }

		$boton = '';
		if ($r['user_ID'] == $pol['user_ID']) {
			if ($r['estado'] == 'ok') {
				if ($r['tipo'] != 'cargo') { $boton .= boton('Finalizar', '/accion.php?a=votacion&b=concluir&ID='.$r['ID'], '&iquest;Seguro que quieres FINALIZAR esta votacion?'); }
				$boton .= boton('X', '/accion.php?a=votacion&b=eliminar&ID=' . $r['ID'], '&iquest;Seguro que quieres ELIMINAR esta votacion?');
			}
		}

		
		if (($r['acceso_ver'] == 'anonimos') OR (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) {
			$txt .= '<tr>
<td width="100"'.($r['tipo']=='referendum'?' style="font-weight:bold;"':'').'>'.ucfirst($r['tipo']).'</td>
<td align="right"><b>'.num($r['num']).'</b></td>
<td>'.$votar.'<a href="/votacion/'.$r['ID'].'/" style="'.($r['tipo']=='referendum'?'font-weight:bold;':'').($r['acceso_ver']!='anonimos'?'color:red;" title="Votación privada':'').'">'.$r['pregunta'].'</a></td>
<td nowrap="nowrap"><span style="color:blue;" title="Tiempo que falta para el resultado">Faltan <b><span class="timer" value="'.$time_expire.'"></span></b></span></td>
<td nowrap="nowrap">'.$boton.'</td>
<td></td>
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
<input type="checkbox" onclick="ver_votacion(\'referendum\');" id="c_referendum" checked="checked" />Referendums &nbsp; 
'.(ASAMBLEA?'':'<input type="checkbox" onclick="ver_votacion(\'parlamento\');" id="c_parlamento" checked="checked" />Parlamento &nbsp; ').' 
<input type="checkbox" onclick="ver_votacion(\'sondeo\');" id="c_sondeo" checked="checked" />Sondeos</b> &nbsp; 
<input type="checkbox" onclick="ver_votacion(\'cargo\');" id="c_cargo" />Cargos</b> &nbsp; 
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
			$txt .= '<tr class="v_'.$r['tipo'].'"'.(in_array($r['tipo'], array('referendum', 'parlamento', 'sondeo'))?'':' style="display:none;"').'>
<td width="100"'.($r['tipo']=='referendum'?' style="font-weight:bold;"':'').'>'.ucfirst($r['tipo']).'</td>
<td align="right"><b>'.num($r['num']).'</b></td>
<td><a href="/votacion/'.$r['ID'].'/" style="'.($r['tipo']=='referendum'?'font-weight:bold;':'').($r['acceso_ver']!='anonimos'?'color:red;" title="Votación privada':'').'">'.$r['pregunta'].'</a></td>
<td nowrap="nowrap"><span style="color:grey;">Hace <span class="timer" value="'.$time_expire.'"></span></span></td>
<td></td>
</tr>';
		}
	}
	$txt .= '</table>';



}



//THEME
include('theme.php');
?>