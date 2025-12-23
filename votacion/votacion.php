<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$result = sql_old("SELECT *,
  (SELECT nick FROM users WHERE ID = votacion.user_ID LIMIT 1) AS nick, 
  (SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS ha_votado,
  (SELECT voto FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS que_ha_votado,
  (SELECT validez FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS que_ha_votado_validez,
  (SELECT mensaje FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS que_ha_mensaje,
  (SELECT comprobante FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS comprobante
FROM votacion
WHERE ID = '".$_GET[1]."'
LIMIT 1");

while($r = r($result)) {

	if ((!nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver'])) AND ($r['estado'] != 'borrador')) { 
		echo '<p style="color:red;">'._('No puedes ver esta votación. Solo pueden verla').' <b>'.verbalizar_acceso($r['acceso_ver'], $r['acceso_cfg_ver']).'</b></p>'; 
		break; 
	}

	$votos_total = $r['num'];

	$time_expire = strtotime($r['time_expire']);
	$time_creacion = strtotime($r['time']);
	$duracion = duracion($time_expire - $time_creacion);
	$respuestas = explode("|", $r['respuestas']);
	$respuestas_desc = explode("][", $r['respuestas_desc']);
	$respuestas_num = count($respuestas) - 1;
	
	$txt_title = _('Votación').': '.$r['pregunta'];
	$txt_nav = array('/votacion'=>_('Votaciones'), '/votacion/'.$r['ID']=>strtoupper($r['tipo']));

	if ($r['estado'] == 'ok') { 
		$txt_nav['/votacion/'.$r['ID']] = _('En curso').': '.num($votos_total).' '._('votos');
		$txt_tab = array('/votacion/'.$r['ID']=>_('Votación'), '/votacion/'.$r['ID'].'/info'=>_('Más información'));

		$tiempo_queda =  '<span style="color:blue;">'._('Quedan').' '.timer($time_expire, true).'.</span>'; 
	} elseif ($r['estado'] == 'borrador') {
		$txt_nav[] = _('Borrador');
		$txt_tab = array('/votacion/borradores'=>_('Ver borradores'), '/votacion/'.$r['ID']=>_('Previsualizar'), '/votacion/crear/'.$r['ID']=>_('Editar borrador'));

		$tiempo_queda =  '<span style="color:red;">'._('Borrador').' <span style="font-weight:normal;">('._('Previsualización de votación').')</span></span> ';
	} else { 
		$txt_nav['/votacion/'.$r['ID']] = _('Finalizado').': '.num($votos_total).' '._('votos');
		$txt_tab = array('/votacion/'.$r['ID']=>_('Votación'), '/votacion/'.$r['ID'].'/info'=>_('Más información'));
		if (isset($pol['user_ID'])) { $txt_tab['/votacion/'.$r['ID'].'/verificacion'] = _('Verificación'); }
		$tiempo_queda =  '<span style="color:grey;">'._('Finalizado').'</span>'; 
	}

	if ($_GET[2] == 'info') {
		$time_expire = strtotime($r['time_expire']);
		$time = strtotime($r['time']);
		
		echo '<fieldset><legend>'._('Información sobre esta votación').'</legend>

<table border="0">

<tr>
<td align="right">'._('Título/Pregunta').':</td>
<td><a href="/votacion/'.$r['ID'].'"><b>'.$r['pregunta'].'</b></a></td>
</tr>

<tr>
<td align="right">'._('Estado').':</td>
<td><b>'.($r['estado']=='ok'?_('En curso').'... '._('quedan').' '.timer($r['time_expire']):_('Finalizada').', '._('hace').' '.timer($r['time_expire'])).'</b> ('.num($r['num']).' '._('votos').')</td>
</tr>


<tr>
<td align="right">'._('Creada o aprobada por').':</td>
<td>'.($r['user_ID']==0?'<b>'._('Sistema VirtualPol').'</b> ('._('automático, sin intervención humana').')':'<b>'.crear_link($r['nick']).'</b>').'</td>
</tr>

<tr>
<td align="right">'._('Tipo de votación').':</td>
<td><b>'.ucfirst(_($r['tipo'])).'</b> '.($r['tipo']=='sondeo'?'('._('No vinculante, informativo').')':'('._('Vinculante').')').'</td>
</tr>

<tr>
<td align="right">'._('Tipo de voto').':</td>
<td><b>'.ucfirst((substr($r['tipo_voto'], 1, 6)=='puntos'?_('preferencial, repartir').' '.substr($r['tipo_voto'], 0, 1).' '._('puntos'):$r['tipo_voto'])).'</b> ('.($r['privacidad']=='true'?_('voto secreto'):_('voto público, no secreto')).($r['aleatorio']=='true'?', '._('opciones ordenadas aleatoriamente'):'').')</td>
</tr>

<tr>
<td align="right">'._('Pueden votar').':</td>
<td><b>'.ucfirst(verbalizar_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])).'</b></td>
</tr>

<tr>
<td align="right">'._('Pueden ver la votación').':</td>
<td><b>'.ucfirst(verbalizar_acceso($r['acceso_ver'], $r['acceso_cfg_ver'])).'</b></td>
</tr>

<tr>
<td align="right">'._('Fecha creación').':</td>
<td><b>'.$r['time'].'</b> ('.timer($r['time'], false, true).')</td>
</tr>

<tr>
<td align="right">'._('Fecha finalización').':</td>
<td><b>'.$r['time_expire'].'</b> ('.timer($r['time_expire'], false, true).')</td>
</tr>

<tr>
<td align="right">'._('Participación').':</td>
<td><b>'.num(($r['num_censo']>0?($r['num']*100)/$r['num_censo']:0),2).'%</b> ('.num($r['num']).' '._('votos').' '._('de').' '.num($r['num_censo']).' '._('votantes').')</td>
</tr>


<tr>
<td align="right" valign="top">'._('Duración').':</td>
<td><b>'.round($r['duracion']/24/60/60).' '._('días').'</b>'.($r['estado']=='ok'?gbarra(((time()-$time)*100)/($time_expire-$time)):'').'</td>
</tr>

</table>
</fieldset>

<fieldset><legend>'._('Propiedades de la votación').'</legend>


<table>

<tr>
<td align="right" valign="top"><b title="Accuracy: el computo de los votos es exacto">'._('Precisión').':</b></td>
<td>'._('SI, el computo de los votos es exacto').'.</td>
</tr>

<tr>
<td align="right" valign="top"><b title="Consistency: los resultados son coherentes y estables en el tiempo">'._('Consistencia').':</b></td>
<td>'._('SI, el resultado es coherente y estable en el tiempo. Una vez finalizadas no se puede eliminar o modificar las votaciones').'.</td>
</tr>

<tr>
<td align="right" valign="top"><b title="Democracy: solo pueden votar personas autorizadas y una sola vez">'._('Democracia').':</b></td>
<td>'._('Autentificación solida mediante').' <a href="'.SSL_URL.'dnie.php">DNIe</a> ('._('y otros certificados').') '._('opcional, avanzado sistema de vigilancia del censo de eficacia elevada (demostrada desde 2008), con supervisores del censo electos por democracia directa (cada 7 días, mediante el <a href="/info/censo/SC">voto de confianza</a>').').</td>
</tr>



'.($r['privacidad']=='true'?'


<tr>
<td align="right" valign="top"><b title="Privacy: el sentido del voto es secreto">'._('Privacidad').':</b></td>
<td>'._('SI, siempre que el servidor no se comprometa mientras la votación está activa. Al finalizar la votación se rompe la relación Usuario-Voto de forma definitiva e irreversible').'.</td>
</tr>

<tr>
<td align="right" valign="top"><b title="Veriability: capacidad publica de comprobar el recuento de votos">'._('Verificación').':</b></td>
<td>'._('Muy elevada, gracias a las siguientes medidas de transparencia').':
<ol>
<li>'._('Se permite verificar el sentido del propio voto mientras la votación está en curso').'.</li>
<li>'._('Comprobantes de voto que permiten verificar -más allá de toda duda- el sentido del propio voto situado en un <a href="/votacion/'.$r['ID'].'/verificacion">escrutinio público y completo</a>').'.</li>
<li>'._('Se hace público CUANDO vota QUIEN, de forma dinamica en chat y de forma <a href="/votacion/'.$r['ID'].'/verificacion">permanente</a>. Permitiendo que cualquiera se pueda poner en contacto con cualquier votante').'.</li>
</ol></td>
</tr>


':'

<tr>
<td align="right" valign="top"><b title="Privacy: el sentido del voto es secreto">'._('Privacidad').':</b></td>
<td>'._('NO, el voto es público. Cualquiera puede ver QUÉ vota QUIEN').'.</td>
</tr>

<tr>
<td align="right" valign="top"><b title="Veriability: capacidad pública de comprobar el recuento de votos">'._('Verificación').':</b></td>
<td>'._('SI. Esta votación tiene verificabilidad universal ya que el voto no es secreto').'.</td>
</tr>

').'

<tr>
<td align="right" valign="top"><b title="Posibilidad de modificar el sentido del voto propio en una votación en curso">'._('Rectificación').'</b>:</td>
<td>'._('SI, se permite modificar el voto').'.</td>
</tr>

<tr>
<td align="right" valign="top"><b title="Validez/nulidad de la votación">'._('Impugnación').':</b></td>
<td>'._('SI, se realiza una votación de nulidad/validez paralela e independiente del sentido de voto').'.</td>
</tr>

</table>
</fieldset>';


	} elseif ($_GET[2] == 'verificacion') {

		echo '<h2>'._('Verificación de votación').'</h2>

<p>'._('La información presentada a continuación es la tabla de comprobantes que muestra el escrutinio completo y la relación Voto-Comprobante de esta votación. Esto permite a cualquier votante comprobar el sentido de su voto ejercido más allá de toda duda. Todo ello sin romper el secreto de voto').'.</p>

'.($r['tipo_voto']!='estandar'?'<p><em>* '._('El tipo de voto de esta votación es múltiple o preferencial. Por razones tecnicas -provisionalmente- se muestra el campo "voto" en bruto').'.'.($r['tipo_voto']=='multiple'?' 0='._('En Blanco').', 1='._('SI').' y 2='._('NO').'.':'').'</em></p>':'');

if (($r['tipo_voto'] == 'multiple') OR (substr($r['tipo_voto'], 1, 6) == 'puntos')) {
echo '<p>'._('Opciones de voto').': ';
foreach ($respuestas AS $ID => $opcion) {
	if ($opcion) { echo $ID.'='.$opcion.', '; }
}
echo '</p>';
}

echo '
<style>
#tabla_comprobantes td { padding:0 4px; }
#tabla_comprobantes .tcb { color:blue; }
#tabla_comprobantes .tcr { color:red; }
</style>

<table border="0" style="font-family:\'Courier New\',Courier,monospace;font-size:12px;" id="tabla_comprobantes">
<tr>
<th title="Conteo de los diferentes sentidos de votos">'._('Contador').'</th>
'.($r['privacidad']=='false'?'<th>Votante</th>':'').'
<th title="Sentido del voto emitido">'._('Sentido de voto').'</th>
<th title="Voto de validez/nulidad, es una votación binaria paralela a la votación para determinar la validez de la misma.">'._('Validez').'</th>
<th title="Código aleatorio relacionado a cada voto">'._('Comprobante').'</th>
<th title="Comentario emitido junto al voto, anónimo y opcional">'._('Comentario').'</th>
</tr>';
		$txt_votantes = array();
		if ((!nucleo_acceso('ciudadanos_global')) AND ($r['estado'] == 'end')) {
			echo '<tr><td colspan="3" style="color:red;"><hr /><b>'._('Tienes que ser ciudadano para ver la tabla de comprobantes').'.</b></td></tr>';
		} else if (($r['estado'] == 'end') AND (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) {
			$contador_votos = 0;
			// SQLite: usar sql_old() y sin backticks
			$result2 = sql_old("SELECT user_ID, voto, validez, comprobante, mensaje,
  (SELECT nick FROM users WHERE ID = votacion_votos.user_ID LIMIT 1) AS nick
FROM votacion_votos
WHERE ref_ID = '".$r['ID']."' AND comprobante IS NOT NULL
ORDER BY mensaje DESC, voto ASC");
			while($r2 = r($result2)) { 
				$contador_votos++; 
				if ($r2['user_ID'] != 0) { $txt_votantes[] = ($r2['nick']?'@'.$r2['nick']:'&dagger;'); }
				echo '<tr id="'.$r2['comprobante'].'">
<td align="right">'.($r['tipo_voto']=='estandar'?++$contador[$r2['voto']]:++$contador).'.</td>
'.($r['privacidad']=='false'?'<td class="rich">'.($r2['nick']?'@'.$r2['nick']:'&dagger;').'</td>':'').'
<td nowrap>'.($r['tipo_voto']=='estandar'?'<b>'.substr($respuestas[$r2['voto']], 0, 25).(strlen($respuestas[$r2['voto']])>25?'...':'').'</b>':$r2['voto']).'</td>
<td'.($r2['validez']=='true'?' class="tcb">'._('Válida'):' class="tcr">'._('Nula')).'</td>
<td nowrap>'.$r['ID'].'-'.$r2['comprobante'].'</td>
'.($r2['mensaje']?'<td>'.$r2['mensaje'].'</td>':'').'
</tr>'."\n"; 
			}
			if ($contador_votos == 0) { echo '<tr><td colspan="3" style="color:red;"><hr /><b>'._('Esta votación es anterior al sistema de comprobantes, por lo tanto esta comprobación no es posible').'.</b></td></tr>'; }
		} else {
			echo '<tr><td colspan="3" style="color:red;"><hr /><b>'._('Esta votación aún no ha finalizado. Cuando finalice se mostrará aquí la tabla de votos-comprobantes').'.</b></td></tr>';
		}

		echo '</table>'.($r['privacidad']=='true'?'<fieldset class="rich" style="font-size:12px;"><legend>'._('Votantes').' ('._('excepto expirados').')</legend> '.implode(' ', $txt_votantes).'</fieldset>':'');

	} else {

		$txt_description = _('Votación').', '.ucfirst($r['tipo']).' '._('de').' '.PAIS.' - '.$r['pregunta'].' - VirtualPol, '._('la primera red social democrática');

		echo '
<fieldset class="rich"><legend style="font-size:22px;font-weight:bold;">'.$r['pregunta'].'</legend>
<p'.($r['estado']=='end'||isset($r['ha_votado'])?' class="votacion_desc_min"':'').'>
'.nl2br($r['descripcion']).'</p>
'.(substr($r['debate_url'], 0, 4)=='http'?'<p><b>'._('Debate').': <a href="'.$r['debate_url'].'">'._('aquí').'</a>.</b></p>':'').'
</fieldset>


';

		if ($r['estado'] == 'end') {  // VOTACION FINALIZADA: Mostrar escrutinio. 

			// Conteo/Proceso de votos (ESCRUTINIO)
			$escrutinio['votos'] = array(0,0,0,0,0,0,0,0,0,0,0,0);
			$escrutinio['votos_autentificados'] = 0;
			$escrutinio['votos_total'] = 0;
			$escrutinio['validez']['true'] = 0; $escrutinio['validez']['false'] = 0;
			$puntos_total = ($r['tipo_voto']=='estandar'?$votos_total:0);

			$result2 = sql_old("SELECT voto, validez, autentificado, mensaje FROM votacion_votos WHERE ref_ID = '".$r['ID']."'");
			while($r2 = r($result2)) {
				
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

			// Si el numero de votos nulos es mayor, la votación es nula
			if ($escrutinio['validez']['false']<=$escrutinio['validez']['true']) { 
				$validez = true; 
			} else { 
				$validez = false; 
			}

			// Opciones del escrutinio en orden descendente.
			arsort($escrutinio['votos']);

			// Imprime escrutinio en texto.
			echo '<fieldset><legend>'._('Resultado').'</legend><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td valign="top">';

			// Imprime escrutinio en grafico.
			if ($validez == true) { // Solo si el resultado es válido (menos de 50% de votos nulos).
				foreach ($escrutinio['votos'] AS $voto => $num) {
					if ($respuestas[$voto] != 'En Blanco') {
						$grafico_array_votos[] = $num;
						$grafico_array_respuestas[] = htmlspecialchars_decode((strlen($respuestas[$voto])>=13?trim(substr($respuestas[$voto], 0, 13)).'..':$respuestas[$voto]));
					}
				}

				if ($votos_total>0 AND count($respuestas)<=8 AND $r['tipo_voto']!='multiple') { 
					echo '<img src="//chart.googleapis.com/chart?cht=p&chds=a&chp=4.71&chd=t:'.implode(',', $grafico_array_votos).'&chs=350x175&chl='.implode('|', $grafico_array_respuestas).'&chf=bg,s,ffffff01|c,s,ffffff01&chco=FF9900|FFBE5E|FFD08A|FFDBA6" alt="Escrutinio" width="350" height="175" /><br />'; 
				}
			}

			if ($validez == true) {

				if ($r['tipo_voto'] == 'multiple') {
					echo '<table border="0" cellpadding="1" cellspacing="0"><tr><th></th><th>'._('SI').'</th><th></th><th>'._('NO').'</th></tr>';
					
					$puntos_total_sin_en_blanco = $puntos_total - $escrutinio['votos'][$en_blanco_ID];

					foreach ($escrutinio['votos'] AS $voto => $num) { 
						if ($respuestas[$voto]) {
							if ($respuestas[$voto] != 'En Blanco') {
								$voto_si = ($escrutinio['votos_full'][$voto][1]?$escrutinio['votos_full'][$voto][1]:0);
								$voto_no = ($escrutinio['votos_full'][$voto][2]?$escrutinio['votos_full'][$voto][2]:0);
								$voto_en_blanco = ($escrutinio['votos_full'][$voto][0]?$escrutinio['votos_full'][$voto][0]:0);

								$porcentaje_si = ($voto_si>0?($voto_si*100)/($voto_si + $voto_no):0);
								$porcentaje_no = ($voto_no>0?($voto_no*100)/($voto_si + $voto_no):0);

								echo '<tr title="'.num($voto_si).' '._('SI').' - '.num($voto_no).' '._('NO').' - '.num($voto_en_blanco).' '._('En Blanco').'">

<td class="rich"'.($respuestas_desc[$voto]?' title="'.$respuestas_desc[$voto].'" class="punteado"':'').'>'.$respuestas[$voto].'</td>

<td align="right"'.($porcentaje_si>50?' style="font-weight:bold;"':'').'>'.num($porcentaje_si,1).'%</td>

<td>'.gbarra($porcentaje_si, 40, false).'</td>

<td align="right"'.($porcentaje_no>50?' style="font-weight:bold;"':'').'>'.num($porcentaje_no,1).'%</td>

</tr>';

							} else { $votos_en_blanco = $num; }
						} else { unset($escrutinio['votos'][$voto]);  }
					}
					echo '<tr><th></th><th>'._('SI').'</th><th></th><th>'._('NO').'</th></tr></table>';

				} else {
					echo '<table border="0" cellpadding="1" cellspacing="0"><tr><th>'._('Escrutinio').'</th><th>'.($r['tipo_voto']=='estandar'?_('Votos'):_('Puntos')).'</th><th></th></tr>';
					
					// Obtener ID del voto "En Blanco"
					foreach ($escrutinio['votos'] AS $voto => $num) { if ($respuestas[$voto] == 'En Blanco') { $en_blanco_ID = $voto; } }
					
					$puntos_total_sin_en_blanco = $puntos_total - $escrutinio['votos'][$en_blanco_ID];

					foreach ($escrutinio['votos'] AS $voto => $num) { 
						if ($respuestas[$voto]) {
							if ($respuestas[$voto] != 'En Blanco') {
								echo '
<tr><td>'.($r['tipo']=='elecciones'?crear_link($respuestas[$voto]):$respuestas[$voto]).'</td>
<td align="right" title="'.num(($puntos_total>0?($num*100)/$puntos_total:0), 2).'%"><b>'.num($num).'</b></td>
<td align="right">'.num(($puntos_total_sin_en_blanco>0?($num*100)/$puntos_total_sin_en_blanco:0), 2).'%</td>
<td>'.gbarra(($puntos_total_sin_en_blanco>0?($num*100)/$puntos_total_sin_en_blanco:0), 60, false).'</td>
</tr>';
							} else { $votos_en_blanco = $num; }
						} else { unset($escrutinio['votos'][$voto]);  }
					}
					echo '<tr><td nowrap="nowrap" title="Voto no computable. Equivale a: No sabe/No contesta."><em>'._('En Blanco').'</em></td><td align="right" title="'.num(($puntos_total>0?($votos_en_blanco*100)/$puntos_total:0), 1).'%"><b>'.num($votos_en_blanco).'</b></td><td></td></tr></table>';
				}
			}
			

			// Imprime datos de legitimidad y validez
			echo '</td>
<td valign="top" align="right" style="color:#888;">
'._('Legitimidad').': <span style="color:#555;"><b>'.num($votos_total).'</b>&nbsp;'._('votos').'</span>, <b>'.$escrutinio['votos_autentificados'].'</b>&nbsp;'._('autentificados').'.<br />
'._('Validez').': 

'.($validez?'<span style="color:#2E64FE;"><b>OK</b>&nbsp;'.num(($votos_total>0?($escrutinio['validez']['true'] * 100) / $votos_total:100), 1).'%</span>':'<span style="color:#FF0000;"><b>'._('NULO').'</b>&nbsp;'.$porcentaje_validez.'%</span>').'<br />
<img width="200" height="120" title="Votos de validez: OK: '.num($escrutinio['validez']['true']).', NULO: '.$escrutinio['validez']['false'].'" src="//chart.googleapis.com/chart?cht=p&chp=4.71&chd=t:'.($escrutinio['validez']['true']==0&&$escrutinio['validez']['false']==0?'1,0':$escrutinio['validez']['true'].','.$escrutinio['validez']['false']).'&chs=200x120&chds=a&chl=OK|NULO&chf=bg,s,ffffff01|c,s,ffffff01&chco=2E64FE,FF0000,2E64FE,FF0000" alt="Validez" /></td>
</tr></table>

</fieldset>';


		} else { // VOTACION EN CURSO: VOTAR.

			$tiene_acceso_votar = nucleo_acceso($r['acceso_votar'],$r['acceso_cfg_votar']);

			echo '<fieldset><legend>'._('Votar').'</legend>
'.($pol['config']['info_consultas']>0&&$r['ha_votado']?'<p style="float:right;">'.boton(_('Siguiente votación'), '/votacion/next', false, 'orange').'</p>':'').'
<form action="/accion/votacion/votar" method="post">
<input type="hidden" name="ref_ID" value="'.$r['ID'].'"  />
<p>';


			if ($r['tipo_voto'] == 'estandar') {

				if ($r['privacidad'] == 'false') { echo '<p style="color:red;">'._('El voto es público en esta votación, por lo tanto NO será secreto').'.</p>'; }

				for ($i=0;$i<$respuestas_num;$i++) { if ($respuestas[$i]) { 
						$votos_array[] = '<option value="'.$i.'"'.($i==$r['que_ha_votado']?' selected="selected"':'').'>'.$respuestas[$i].'</option>'; 
				} }

				if ($r['aleatorio'] == 'true') { shuffle($votos_array); }

				echo '<select name="voto" style="font-size:20px;white-space:normal;max-width:400px;">'.implode('', $votos_array).'</select>';

			} elseif (substr($r['tipo_voto'], 1, 6) == 'puntos') {
				
				$tipo_puntos = substr($r['tipo_voto'], 0, 1);
				
				//if ($r['ha_votado']) { echo 'Tu voto preferencial ha sido recogido <b>correctamente</b>.<br /><br />'; }

				echo '<span style="color:grey;">'._('Debes repartir <b>los puntos más altos a tus opciones preferidas</b>. Puntos no acumulables').'.</span><table border="0"><tr><th colspan="'.substr($r['tipo_voto'], 0, 1).'" align="center">'._('Puntos').'</th><th></th></tr><tr>';

				for ($e=1;$e<=$tipo_puntos;$e++) { echo '<th align="center">'.$e.'</th>'; }
				echo '<th>'.($r['tipo']=='elecciones'?_('Candidatos'):_('Opciones')).'</th></tr>';		
				
				if ($r['ha_votado']) { $ha_votado_array = explode(' ', $r['que_ha_votado']); }
				else { $ha_votado_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0); }
				for ($i=0;$i<$respuestas_num;$i++) { if ($respuestas[$i]) { 
					$txt_print = '<tr>';
					$orden_id = false;
					for ($e=1;$e<=$tipo_puntos;$e++) {
						$txt_print .= '<td valign="top"><input type="radio" name="voto_'.$e.'" value="'.$i.'"'.($ha_votado_array[($e-1)]==$i?' checked="checked"':'').' /></td>';
						if ($ha_votado_array[($e-1)]==$i) { $orden_id = $e*-1; }
					}
					$txt_print .= '<td nowrap="nowrap"'.($respuestas_desc[$i]?' title="'.$respuestas_desc[$i].'" class="punteado"':'').'>'.($respuestas[$i]==='En Blanco'?'<em title="Equivale a No sabe/No contesta. No computable.">'._('En Blanco').'</em>':($r['tipo']=='elecciones'?'<b>'.crear_link($respuestas[$i]).'</b>':$respuestas[$i])).'</td></tr>';
					if ($respuestas[$i]==='En Blanco') { echo $txt_print; } else {
						if ($orden_id != false) {
							$votos_array[$orden_id] = $txt_print;
						} else { $votos_array[] = $txt_print; }
					}
				} }
				ksort($votos_array);
				if (($r['aleatorio'] == 'true') AND (!$r['ha_votado'])) { shuffle($votos_array); }

				echo implode('', $votos_array).'<tr>';

				for ($e=1;$e<=$tipo_puntos;$e++) { echo '<th align="center">'.$e.'</th>'; }
				
				echo '<th></th></tr></table>';

			} elseif ($r['tipo_voto'] == 'multiple') { // VOTAR MULTIPLE

				if ($r['ha_votado']) { echo _('Tus votos múltiples han sido recogidos <b>correctamente</b>').'. '; }

				echo '
<script type="text/javascript">

function radio_check(value) {
$("#votacion_radio input").removeAttr("checked");
$("#votacion_radio input[value=\'" + value + "\']").attr("checked", "checked");
}

</script>


<table border="0" id="votacion_radio">
<tr>
<th onclick="radio_check(1);" style="cursor:pointer;">'._('SI').'</th>
<th onclick="radio_check(2);" style="cursor:pointer;">'._('NO').'</th>
<th onclick="radio_check(0);" style="cursor:pointer;" nowrap="nowrap"><em>'._('En Blanco').'</em></th>
<th></th>
</tr>';				if ($r['ha_votado']) { $ha_votado_array = explode(' ', $r['que_ha_votado']); }
				else { $ha_votado_array = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0); }

				for ($i=0;$i<$respuestas_num;$i++) { if (($respuestas[$i]) AND ($respuestas[$i] != 'En Blanco')) { 
						$votos_array[] = '<tr>
<td valign="top" align="center"><input type="radio" name="voto_'.$i.'" value="1"'.($ha_votado_array[$i]==1?' checked="checked"':'').' /></td>
<td valign="top" align="center"><input type="radio" name="voto_'.$i.'" value="2"'.($ha_votado_array[$i]==2?' checked="checked"':'').' /></td>
<td valign="top" align="center"><input type="radio" name="voto_'.$i.'" value="0"'.($ha_votado_array[$i]==0||!$ha_votado_array[$i]?' checked="checked"':'').' /></td>
<td class="rich"'.($respuestas_desc[$i]?' title="'.$respuestas_desc[$i].'" class="punteado"':'').'>'.$respuestas[$i].'</td>
</tr>';
				} }
				if ($r['aleatorio'] == 'true') { shuffle($votos_array); }
				echo implode('', $votos_array).'<tr>
<th>'._('SI').'</th>
<th>'._('NO').'</th>
<th nowrap="nowrap"><em>'._('En Blanco').'</em></th>
<th></th>
</tr>
</table>';


			}


			// Imprime boton para votar, aviso de tiempo y votacion correcta/nula.
			echo ' '.boton(($r['ha_votado']?_('Modificar voto'):_('Votar')), ($r['estado']!='borrador'&&$tiene_acceso_votar?'submit':false), false, 'large '.($tiene_acceso_votar?'blue':'red')).' <span style="white-space:nowrap;">'.($tiene_acceso_votar?($r['ha_votado']?'<span style="color:#2E64FE;">'._('Puedes modificar tu voto durante').' <span class="timer" value="'.$time_expire.'"></span>.</span>':'<span style="color:#2E64FE;">'._('Tienes').' <span class="timer" value="'.$time_expire.'"></span> '._('para votar').'.</span>'):'<span style="color:red;white-space:nowrap;">'.(!$pol['user_ID']?'<b>'._('Para votar debes').' <a href="/registrar">'._('crear tu ciudadano').'</a>.</b>':_('No tienes acceso para votar, pueden votar').' '.verbalizar_acceso($r['acceso_votar'], $r['acceso_cfg_votar']).'.').'</span>').'</span></p>

<p>
<input id="validez_true" type="radio" name="validez" value="true" required'.($r['que_ha_votado_validez']=='true'?' checked="checked"':'').' /> <label for="validez_true">'._('Votación válida').'.</label><br />
<input id="validez_false" type="radio" name="validez" value="false" required'.($r['que_ha_votado_validez']=='false'?' checked="checked"':'').' /> <label for="validez_false">'._('Votación nula (inválida, inapropiada o tendenciosa)').'.</label>
</p>

<p><input type="text" name="mensaje" value="'.$r['que_ha_mensaje'].'" size="80" maxlength="160" placeholder="'._('Puedes escribir aquí un comentario').' ('.($r['privacidad']=='true'?_('opcional, secreto y público al finalizar la votación'):_('opcional y público al finalizar la votación')).')" /></p>

</form>

'.($r['ha_votado']?'<p style="margin-top:20px;text-align:right;">'._('Comprobante de voto').':<br /><input type="text" value="'.$r['ID'].'-'.$r['comprobante'].'" size="48" readonly="readonly" style="color:#AAA;" /> '.boton(_('Enviar a mi email'), '/accion/votacion/enviar_comprobante&comprobante='.$r['ID'].'-'.$r['comprobante'], false, 'pill small').'</p>':'').'</fieldset>';

		}

		// Añade tabla de escrutinio publico si es votacion tipo parlamento.
		if ($r['tipo'] == 'parlamento') {
			echo '<fieldset><legend>'._('Parlamento').'</legend><table border="0" cellpadding="0" cellspacing="3"><tr><th>'.(ASAMBLEA?_('Coordinador'):_('Diputado')).'</th><th></th><th colspan="2">'._('Voto').'</th><th>'._('Mensaje').'</th></tr>';			
			$result2 = sql_old("SELECT user_ID, voto AS ha_votado, mensaje AS ha_mensaje,
			  (SELECT nick FROM users WHERE ID = votacion_votos.user_ID LIMIT 1) AS nick,
			  (SELECT (SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND ID = users.partido_afiliado LIMIT 1) FROM users WHERE ID = votacion_votos.user_ID LIMIT 1) AS siglas
			FROM votacion_votos
			WHERE ref_ID = '".$r['ID']."'
			ORDER BY \"time\" ASC");
			while($r2 = r($result2)) {
				if ($r2['ha_votado'] != null) { $ha_votado = ' style="background:blue;"';
				} else { $ha_votado = ' style="background:red;"'; }
				echo '<tr><td><img src="'.IMG.'cargos/6.gif" /> <b>'.crear_link($r2['nick']) . '</b></td><td><b>'.(ASAMBLEA?'':crear_link($r2['siglas'], 'partido')).'</b></td><td'.$ha_votado.'></td><td><b>' . $respuestas[$r2['ha_votado']].'</b></td><td style="color:#555;font-size:12px;" class="rich">'.$r2['ha_mensaje'].'</td></tr>';
			}
			echo '</table></fieldset>';
		}

		echo '<fieldset id="argumentos"><legend>'._('Argumentos').'</legend><table>';

		$votos_mosotrar = 0;
		$argumentos_ocultos = 0;
		$argumentos_num = 0;
		$argumentos_mios = 0;
		$result2 = sql_old("SELECT va.ID, va.user_ID, va.ref_ID, va.sentido, va.texto, va.time, va.votos, va.votos_num, v.voto
FROM votacion_argumentos AS va
LEFT JOIN votos AS v ON (tipo = 'argumentos' AND item_ID = va.ID AND emisor_ID = '".$pol['user_ID']."')
WHERE va.ref_ID = '".$r['ID']."'
ORDER BY va.votos DESC, va.time DESC
LIMIT 2500");
		while($r2 = r($result2)) {
			$argumentos_num++;
			if ($r2['user_ID']==$pol['user_ID']) { $argumentos_mios++; }
			echo '
<tr'.($r2['votos']<$votos_mosotrar?' style="display:none;" class="negativizados"':'').'>

<td class="gris" nowrap>'.(nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])&&$r2['user_ID']!=$pol['user_ID']&&$r['estado']!='end'?'

<span id="data_argumentos'.$r2['ID'].'" type="argumentos" name="'.$r2['ID'].'" value="'.$r2['voto'].'">

<input type="radio" id="rac'.$r2['ID'].'" class="radio_argumentos'.$r2['ID'].'" name="radio_argumentos'.$r2['ID'].'" onclick="votar(1, \'argumentos\', \''.$r2['ID'].'\');"'.($r2['voto']==1?' checked="checked"':'').'> <label for="rac'.$r2['ID'].'">Acertado</label><br />
<input type="radio" id="rai'.$r2['ID'].'" class="radio_argumentos'.$r2['ID'].'" name="radio_argumentos'.$r2['ID'].'" onclick="votar(-1, \'argumentos\', \''.$r2['ID'].'\');"'.($r2['voto']==-1?' checked="checked"':'').'> <label for="rai'.$r2['ID'].'">Equivocado</label>

</span>

':'').'</td>

<td align="right" id="argumentos'.$r2['ID'].'">'.confianza($r2['votos'], $r2['votos_num']).'</td>

<td class="rich" style="'.($r2['votos']>=0?'color:#000;':'').'font-size:16px;">'.$r2['texto'].'</td>
<td class="gris" align="right" nowrap>'.$r2['sentido'].'<br />'.timer($r2['time']).($r2['user_ID']==$pol['user_ID']&&$r2['votos']<=6?boton('X', '/accion/votacion/argumento-eliminar?ID='.$r2['ID'].'&ref_ID='.$r2['ref_ID'], '¿Seguro que quieres ELIMINAR tu argumento?', 'red small'):'').'</td>
</tr>';
			if ($r2['votos'] < $votos_mosotrar) { $argumentos_ocultos++; }
		}
		echo '</table>'.($r['estado']!='end'&&$argumentos_ocultos>0&&nucleo_acceso('ciudadanos_global')?'<a href="#" onclick="$(\'.negativizados\').toggle();return false;">'._('Mostrar argumentos negativos ('.$argumentos_ocultos.')').'</a>':'').(false&&nucleo_acceso('ciudadanos_global')&&$r['estado']!='end'&&$argumentos_num>0?' <span style="color:red;"><em>Si un argumento <b>no te gusta pero es acertado</b> debes marcarlo como <u>acertado</u>.</em></span>':'');

	
		echo '
'.($r['estado']!='end'&&$argumentos_mios==0&&nucleo_acceso('confianza', 0)&&nucleo_acceso('antiguedad', 5)&&nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])?'

<p><a href="#" onclick="$(\'#add-argument\').toggle(\'slow\');return false;">'._('Añadir argumento').'</a></p>

<div id="add-argument" style="display:none;">

<form action="/accion/votacion/argumento" method="POST">
<input type="hidden" name="ref_ID" value="'.$r['ID'].'"  />

<p><select name="sentido" required>
<option value=""></option>
<optgroup label="Sobre el voto:">
<option value="Argumento">Argumento</option>
<option value="A favor">Argumento a favor</option>
<option value="En contra">Argumento en contra</option>
</optgroup>

<optgroup label="Sobre la votación:">
<option value="Matiz">Matiz</option>
<option value="Información">Información</option>
<option value="Corrección">Corrección</option>
<option value="Impugnación">Impugnación</option>
</optgroup>

</select> <input type="text" name="texto" value="" size="80" maxlength="160" placeholder="Escribe aquí tu argumento..." required /> (160 caracteres)</p>

<p>

&nbsp; <input type="checkbox" id="check1" name="check1" value="true" required /> <label for="check1">
El argumento es imparcial y objetivo.</label><br />

&nbsp; <input type="checkbox" id="check2" name="check2" value="true" required /> <label for="check2">
La escritura es correcta y respetuosa.</label><br />

&nbsp; <input type="checkbox" id="check3" name="check3" value="true" required /> <label for="check3">
No está repetido.</label><br />

</ul>

</p>

<table>
<tr>
<td>'.boton(_('Añadir argumento'), 'submit', false, 'blue').'</td>

<td style="color:grey;">* Se ocultará automáticamente si alcanza un balance de votos negativo.<br />
* Los argumentos son públicos y anónimos.</td>
</tr>
</table>

</form>


</div>
':'');
		echo '</fieldset>';

		if ($r['estado']=='end' AND nucleo_acceso('ciudadanos_global')) {
			$result2 = sql_old("SELECT COUNT(*) AS num FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND mensaje != ''");
			while($r2 = r($result2)) { $comentarios_num = $r2['num']; }

			echo '<fieldset style="font-size:13px;"><legend>'._('Comentarios adjuntos al voto').' ('.($r['estado']=='end'?$comentarios_num.' '._('comentarios').' &nbsp; '.($votos_total>0?num(($comentarios_num*100)/$votos_total, 1).'%':'?'):'0%').')</legend>';
			if (nucleo_acceso('ciudadanos_global')) {
				if ($r['estado'] == 'end') { 
					$result2 = sql_old("SELECT mensaje FROM votacion_votos WHERE ref_ID = '".$r['ID']."' AND mensaje != ''");
					while($r2 = r($result2)) { echo '<p>'.$r2['mensaje'].'</p>'; }
				} else { echo '<p style="color:grey;">'._('Los comentarios serán visibles al finalizar la votación').'.</p>'; }
			} else { echo '<p style="color:grey;">'._('Para ver los comentarios debes ser ciudadano').'.</p>'; }
			echo '</fieldset>';
		}

	}
}
