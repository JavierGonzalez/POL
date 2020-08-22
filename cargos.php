<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



/* NUEVAS ELECCIONES


asigna = 0 = Elecciones automaticas (indestructibless por sistema)
asigna = -1 = Elecciones automaticas editables

# CAMPOS
elecciones (fecha proximas elecciones)
elecciones_electos (numero candidatos)
elecciones_cada (dias)
elecciones_durante (dias)
elecciones_votan (nucleo_acceso)

*/

if ($_GET[1] == 'organigrama') { // ORGANIGRAMA
	
	$txt_title = _('Organigrama');
	$txt_nav = array('/cargos'=>_('Cargos'), _('Organigrama'));
	$txt_tab = array('/cargos'=>_('Cargos'), '/cargos/organigrama'=>_('Organigrama'), '/examenes'=>_('Exámenes'));

	function cargo_bien($c){ return str_replace(' ', '_', $c); }

	$result = mysql_query_old("SELECT nombre, asigna, elecciones,
(SELECT nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = c.asigna LIMIT 1) AS asigna_nombre
FROM cargos `c`
WHERE pais = '".PAIS."' ORDER BY nivel DESC", $link);
	while($r = mysqli_fetch_array($result)) {
		if ($r['asigna'] <= 0) { $r['asigna_nombre'] = 'CIUDADANOS'; }

		$data_cargos[] = cargo_bien($r['asigna_nombre']).'->'.cargo_bien($r['nombre']);
	}

	echo '<a href="https://chart.googleapis.com/chart?cht=gv&chl=digraph{'.implode(';', $data_cargos).'}" target="_blank"><img style="max-width:1400px;margin-left:-20px;" src="http://chart.googleapis.com/chart?cht=gv&chl=digraph{'.implode(';', $data_cargos).'}" alt="grafico confianza" /></a><p>'._('Organigrama de la jerarquía de cargos. Grafico experimental, alpha').'.</p>';

} elseif (is_numeric($_GET[1])) { // VER CARGO

	$result = mysql_query_old("SELECT * FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET[1]."' LIMIT 1", $link);
	while($r = mysqli_fetch_array($result)) {
		$txt_nav = array('/cargos'=>'Cargos', '/cargos/'.$r['cargo_ID']=>$r['nombre']);

		if ($r['nombre_extra'] != '') { echo '<p>'.$r['nombre_extra'].'</p>'; }

		$a = 0;
		$activos = array();
		$candidatos = array();
		$activos_nick = array();
		$result2 = mysql_query_old("SELECT *, 
(SELECT nick FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS nick,
(SELECT estado FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS nick_estado,
(SELECT fecha_last FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS fecha_last,
(SELECT voto_confianza FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS voto_confianza,
(SELECT confianza_historico FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS confianza_historico
FROM cargos_users
WHERE pais = '".PAIS."' 
AND cargo_ID = '".$r['cargo_ID']."'
AND (aprobado = 'ok' OR cargo = 'true')
ORDER BY voto_confianza DESC, nota DESC, fecha_last DESC", $link);
		while($r2 = mysqli_fetch_array($result2)){

			if ($r['asigna'] > 0) { $asignador = nucleo_acceso('cargo', $r['asigna']); } else { $asignador = false; }
			if ($r['nombre'] == 'Socio') { $asignador = false; }

			if ($r2['nick_estado'] == 'ciudadano' || $r2['nick_estado'] == 'expulsado') {
				if ($r2['cargo'] == 'true') {
					$activos_nick[] = $r2['nick'];
					$activos_last[$r2['nick']] = $r2['fecha_last'];
					$activos[] = '<tr>
<td>'.($asignador?'<form action="/accion/cargo/del?ID='.$r['cargo_ID'].'" method="post">
<input type="hidden" name="user_ID" value="'.$r2['user_ID'].'"  />'.boton('X', 'submit', '¿Seguro que quieres QUITAR el cargo a '.strtoupper($r2['nick']).'?', 'small red').'</form>':'').'</td>
<td align="right">'.++$activos_num.'.</td>
<td><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" alt="icono '.$r['nombre'].'" width="16" height="16" border="0" style="margin-bottom:-3px;" /> <b>'.crear_link($r2['nick']).'</b></td>
<td align="right" class="gris">'.timer($r2['fecha_last']).'</td>
</tr>';
				}
				if ($r2['aprobado'] == 'ok') {
					$candidatos[] = '<tr>
<td>'.($asignador&&$r2['cargo']!='true'?'<form action="/accion/cargo/add?ID='.$r['cargo_ID'].'" method="POST">
<input type="hidden" name="user_ID" value="'.$r2['user_ID'].'"  />'.boton(_('Asignar'), 'submit', false, 'small blue').'</form>':'').'</td>
<td>'.($r2['cargo']=='true'?'<img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" alt="icono '.$r['nombre'].'" width="16" height="16" border="0" style="margin-bottom:-3px;" />':'<img src="'.IMG.'cargos/0.gif" alt="icono" width="16" height="16" border="0" style="margin-bottom:-3px;" />').' '.crear_link($r2['nick']).'</td>
<td align="right" class="gris">'.timer($r2['fecha_last']).'</td>
<td align="right">'.confianza($r2['voto_confianza']).'</td>
<td><img src="https://chart.googleapis.com/chart?cht=ls&chs=90x22&chd=t:'.implode(',', explode(' ', trim($r2['confianza_historico']))).'&chco=EA9800&chds=a&chbh=a" width="90" height="22" alt="Confianza" title="Confianza semanal" /></td>
<td align="right">'.num($r2['nota'],1).'</td>
</tr>';
				}
			}
		}

		echo '<table border="0"><tr><td valign="top">

<fieldset><legend><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" alt="icono '.$r['nombre'].'" width="16" height="16" border="0" style="margin-bottom:-3px;" /> '.$r['nombre'].' ('.count($activos).')</legend>

<table border="0">
<tr>
'.(isset($r['elecciones'])?'<th colspan="2" align="left">'._('Cadena de sucesión').'</th>':'<th colspan="3"></th>').'
<th style="font-weight:normal;">'._('Último acceso').'</th>
</tr>';

		if (isset($r['elecciones'])) {

			// CADENA DE SUCESION
			$result2 = mysql_query_old("SELECT * 
FROM votacion
WHERE tipo = 'elecciones' AND pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' AND estado = 'end'
ORDER BY time_expire DESC
LIMIT 1", $link);
			while($r2 = mysqli_fetch_array($result2)) {

				$cnum = 0;
				$elecciones_electos = explodear('|', $r2['ejecutar'], 2);
				foreach (explode(':', explodear('|', $r2['ejecutar'], 3)) AS $d) {
					$d = explode('.', $d);
					if ($d[2] != 'B') {
						$cnum++;
						if ($d[0]) {
							echo '<tr><td align="right">'.++$n.'.</td><td>'.(in_array($d[0], $activos_nick)?'<img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" alt="icono '.$r['nombre'].'" width="16" height="16" border="0" style="margin-bottom:-3px;" /> <b>'.crear_link($d[0]).'</b>':'<img src="'.IMG.'cargos/0.gif" alt="icono null" width="16" height="16" border="0" style="margin-bottom:-3px;" /> '.crear_link($d[0])).'</td><td align="right" class="gris">'.(isset($activos_last[$d[0]])?timer($activos_last[$d[0]]):'').'</td></tr>';
						}
						$ya_mostrado[$d[0]] = true;
					}
				}
			}
			foreach ($activos_nick AS $nick) {
				if (!isset($ya_mostrado[$nick])) {
					echo '<tr><td align="right"></td><td><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" alt="icono '.$r['nombre'].'" width="16" height="16" border="0" style="margin-bottom:-3px;" /> <b>'.crear_link($nick).'</b></td><td align="right" class="gris">'.(isset($activos_last[$nick])?timer($activos_last[$nick]):'').'</td></tr>';
				}
			}
		} else {
			echo implode('', $activos);
		}


		echo '
</table>
</fieldset>

	</td><td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td><td valign="top">



<fieldset><legend>'._('Candidatos a').' '.strtolower($r['nombre']).' ('.count($candidatos).')</legend>
<table border="0">
<tr>
<th></th>
<th></th>
<th style="font-weight:normal;">'._('Último acceso').'</th>
<th style="font-weight:normal;" colspan="2">'._('Confianza').'</th>
<th style="font-weight:normal;">'._('Nota').'</th>
</tr>
'.implode('', $candidatos).'
</table>
</fieldset>

</td></tr>


<tr>
<td colspan="3">


<fieldset><legend>Log de acciones</legend>

<table class="rich">
<tr>
<th colspan="2">'._('Fecha').'</th>
<th>'._('Acción').'</th>
</tr>';

$result = sql_old("SELECT time, accion
FROM log 
WHERE pais = '".PAIS."' AND accion_a = 'cargo' AND accion LIKE 'Cargo ".$r['nombre']." %'
ORDER BY time DESC LIMIT 25");
while($r = r($result)){
	echo '<tr>
<td align="right">'.timer($r['time']).'</td>
<td>'.substr($r['time'], 11, 5).'</td>
<td>'.$r['accion'].'</td>
</tr>'."\n";
}
echo '</table>

</fieldset>


</td>
</tr>


</table>';

	}

} else { // VER CARGOS
	$txt_nav = array('/cargos'=>_('Cargos'));
	$txt_tab = array('/cargos'=>'Cargos', '/cargos/organigrama'=>_('Organigrama'), '/elecciones'=>_('Elecciones'));
	if (nucleo_acceso($vp['acceso']['control_cargos'])) {
		$txt_tab['/cargos'] = _('Cargos');
		$txt_tab['/cargos/editar'] = _('Editar');
		$txt_tab['/cargos/editar/elecciones'] = _('Editar elecciones');
		$txt_tab['/control/gobierno/privilegios'] = _('Privilegios');
	}
	if (($pol['config']['socios_estado']=='true') AND (nucleo_acceso('ciudadanos'))) {
		$txt_tab['/socios'] = 'Socios';
	}

	if ($_GET[1] == 'editar') { 
		$editar = true; 
		if (nucleo_acceso($vp['acceso']['control_cargos'])) { $txt_nav[] = _('Editar cargos'); }
		echo '<form action="/accion/cargo/editar" method="POST">'.($_GET[2]=='elecciones'?'<input type="hidden" name="editar_elecciones" value="true" />':'');
	} else { $editar = false; }

	if ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) { $editar_examen = true; } else { $editar_examen = false; }


	// Obtiene cargos automaticos (para imponer el limite de 1 automatico por usuario, con el fin de evitar "picar en todos los cargos")
	$cargos_automaticos = array();
	if (isset($pol['user_ID'])) {
		$result = mysql_query_old("SELECT cargo_ID FROM cargos WHERE pais = '".PAIS."' AND autocargo = 'true'", $link);
		while($r = mysqli_fetch_array($result)){ $cargos_automaticos[] = $r['cargo_ID']; }
	}

	$cargo_ID_array = array();
	$result = mysql_query_old("SELECT *, 
(SELECT cargo FROM cargos_users WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' AND cargo_ID = cargos.cargo_ID LIMIT 1) AS cargo,
(SELECT aprobado FROM cargos_users WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' AND cargo_ID = cargos.cargo_ID LIMIT 1) AS aprobado,
(SELECT nota FROM cargos_users WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' AND cargo_ID = cargos.cargo_ID LIMIT 1) AS nota,
(SELECT ID FROM examenes WHERE pais = '".PAIS."' AND cargo_ID = cargos.cargo_ID LIMIT 1) AS examen_ID,
(SELECT COUNT(ID) FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = cargos.cargo_ID AND cargo = 'true') AS cargo_num,
(SELECT COUNT(ID) FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = cargos.cargo_ID AND aprobado = 'ok') AS candidatos_num
FROM cargos WHERE pais = '".PAIS."' ORDER BY nivel DESC", $link);
	while($r = mysqli_fetch_array($result)){

		$cargo_ID_array[] = $r['cargo_ID'];
		if (($editar) AND ($r['asigna'] > 0)) { $cargo_editar = true; } else { $cargo_editar = false; }

		$cargos_nick = array();
		if ($r['cargo_num'] > 0) {
			$result2 = mysql_query_old("SELECT (SELECT nick FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS nick, (SELECT voto_confianza FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS confianza FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = '".$r['cargo_ID']."' AND cargo = 'true' ORDER BY confianza DESC LIMIT 10", $link);
			while($r2 = mysqli_fetch_array($result2)){ $cargos_nick[] = crear_link($r2['nick']); }
		}

		$txt_el_td = '<span style="white-space:nowrap;">
<img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" alt="icono '.$r['nombre'].'" width="16" height="16" border="0" /> 
'.($cargo_editar?'<input type="text" name="nombre_'.$r['cargo_ID'].'" value="'.$r['nombre'].'" size="18" style="font-weight:bold;" /> <input type="text" name="nombre_extra_'.$r['cargo_ID'].'" value="'.$r['nombre_extra'].'" size="15" maxlength="160"  />':

'<a href="/cargos/'.$r['cargo_ID'].'"'.($r['nombre_extra']?' title="'.$r['nombre_extra'].'"':'').'><b style="font-size:20px;">'.$r['nombre'].'</b></a></span>'.($r['nombre_extra']!=''?'<br /><span style="font-size:12px;color:grey;margin-left:22px;">'.$r['nombre_extra'].'</span>':'').(count($cargos_nick)>0||$r['nombre_extra']?'<br /><span style="font-size:11px;margin-left:22px;">'.implode(', ', $cargos_nick).(count($cargos_nick)==10?'...':'.'):'')).'</span></td>';

		if ($cargo_editar) {
			$txt_el_td .= '<td align="right">'.($r['asigna']>0&&$r['cargo_num']==0?boton('X', '/accion/cargo/eliminar&cargo_ID='.$r['cargo_ID'], '¿Estás seguro de querer ELIMINAR este cargo?', 'small red').' ':'').'<select name="asigna_'.$r['cargo_ID'].'">';
			$result2 = mysql_query_old("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID != '".$r['cargo_ID']."' ORDER BY nivel DESC", $link);
			while($r2 = mysqli_fetch_array($result2)){
				$txt_el_td .= '<option value="'.$r2['cargo_ID'].'"'.($r['asigna']==$r2['cargo_ID']?' selected="selected"':'').'>'.$r2['nombre'].'</option>';
			}
			$txt_el_td .= '</select></td>';
		} elseif (($editar) AND ($r['asigna'] <= 0)) {
			$txt_el_td .= '<td><b>'._('Sistema').'</b></td>';
		} else {
			$txt_el_td .= '<td>';
			
			if ($pol['pais'] == PAIS) {
				if (($r['nombre'] == 'Socio') AND ($pol['config']['socios_estado']=='true') AND (nucleo_acceso('ciudadanos'))) {
					// Boton de socio
					$txt_el_td .= boton(_('Ser socio'), '/socios', false, 'orange');
				} else {
					if ($r['cargo'] == 'true') {
						$txt_el_td .= boton(_('Dimitir'), '/accion/cargo/dimitir?ID='.$r['cargo_ID'], '¿Estás seguro de querer DIMITIR?\n\n¡ES IRREVERSIBLE!', 'red');
					} else if ($r['aprobado'] == 'ok') {
						$txt_el_td .= boton(_('Repetir').' ('.$r['nota'].')', '/examenes/'.$r['examen_ID'], false, 'blue').' '.boton(_('Retirar'), '/accion/examenes/retirar_examen?ID='.$r['cargo_ID'], false, 'red');
					} else if ($r['aprobado'] == 'no') {

						if (($r['autocargo'] == 'true') AND (nucleo_acceso('cargo', implode(' ', $cargos_automaticos)))) { 
							// Tienes al menos un cargo automatico
							$txt_el_td .= '<span class="gris">'._('Solo puedes ejercer un cargo automático').'.</span>';
						} else {
							$txt_el_td .= boton(($r['autocargo']=='true'?_('Ser miembro'):_('Ser candidato')).' ('.$r['nota'].')', '/examenes/'.$r['examen_ID'], false, 'blue');
						}

					} else {

						if (($r['autocargo'] == 'true') AND (nucleo_acceso('cargo', implode(' ', $cargos_automaticos)))) { 
							// Tienes al menos un cargo automatico
							$txt_el_td .= '<span class="gris">'._('Solo puedes ejercer un cargo automático').'.</span>';
						} else {
							$txt_el_td .= boton(($r['autocargo']=='true'?_('Ser miembro'):_('Ser candidato')), '/examenes/'.$r['examen_ID'], false, 'blue');
						}
					}
				}
			}
			$txt_el_td .= '</td>';
		}

		$txt_el_td .= '
<td align="right" title="'._('Con cargo / Candidatos').'" style="font-size:16px;" nowrap><b style="font-size:18px;">'.$r['cargo_num'].'</b> / '.$r['candidatos_num'].'</td>
<td nowrap="nowrap" class="gris" align="center">'.($r['asigna']>0&&$cargo_editar?'<input type="checkbox" name="autocargo_'.$r['cargo_ID'].'" value="true" id="autocargo_'.$r['cargo_ID'].'"'.($r['autocargo']=='true'?' checked="checked"':'').' /> <label for="autocargo_'.$r['cargo_ID'].'" class="inline" title="Asignación de cargo automático al aprobar examen">'._('Cargo automático').'</label><br />':'').(!$editar&&$r['autocargo']=='true'?_('Cargo automático.'):'').($r['elecciones']!=''?' <a href="/elecciones">'._('Elecciones en').' <b>'.timer($r['elecciones']).'</b></a>':'').'</td>
<td align="right">'.($cargo_editar?'<input type="text" name="nivel_'.$r['cargo_ID'].'" value="'.$r['nivel'].'" size="3" maxlength="2" style="text-align:right;" />':$r['nivel']).'</td>
'.(ECONOMIA?'<td align="right">'.pols($r['salario']).'</td>':'').'
<td>'.($editar_examen?boton(_('Editar examen'), '/examenes/editar/'.$r['examen_ID']):'').'</td>
<td align="right" style="color:grey;">'.$r['cargo_ID'].'</td></tr>

'.($editar&&$_GET[2]=='elecciones'?'
<tr>
<td align="right" colspan="6" nowrap>'.($r['elecciones']?_('Próximas elecciones en').' '.timer($r['elecciones']).':':_('¿Próximas elecciones?')).' <input type="text" name="elecciones_'.$r['cargo_ID'].'" value="'.$r['elecciones'].'" size="16"'.($cargo_editar?'':'readonly').' /> 
&nbsp; '._('Electos').': <input type="text" name="elecciones_electos_'.$r['cargo_ID'].'" style="text-align:right;" value="'.$r['elecciones_electos'].'" size="1"'.($cargo_editar?'':'readonly').' /> 
&nbsp; '._('Cada').': <input type="text" name="elecciones_cada_'.$r['cargo_ID'].'" style="text-align:right;" value="'.$r['elecciones_cada'].'" size="2"'.($cargo_editar?'':'readonly').' /> 
días
&nbsp; '._('Votan').': <input type="text" name="elecciones_votan_'.$r['cargo_ID'].'" value="'.$r['elecciones_votan'].'" size="10"'.($cargo_editar?'':'readonly').' />
&nbsp; '._('Durante').': <input type="text" name="elecciones_durante_'.$r['cargo_ID'].'" style="text-align:right;" value="'.$r['elecciones_durante'].'" size="1"'.($cargo_editar?'':'readonly').' /> '._('días').'</td>
</tr>
<tr><td colspan="6">&nbsp;<br />&nbsp;</td></tr>
':'');
		
		//$txt_td2[$r['cargo_ID']] = array();
		if ($r['asigna']>0) { // Asignado...
			$txt_td2[$r['asigna']][$r['cargo_ID']] = $txt_el_td;
		} else { // Asignado por elecciones...
			$txt_td1[$r['cargo_ID']] = $txt_el_td;
		}
	}



		echo '
<table border="0" cellspacing="3" cellpadding="0">
<tr>
<th></th>
<th title="De quien depende el cargo">'.($editar?_('Supeditado a'):'').'</th>
<th></th>
<th title="Cómo/quien asigna el cargo">'._('Asignación').'</th>
<th>'._('Nivel').'</th>
'.(ECONOMIA?'<th title="Salario por dia trabajado">'._('Salario').'</th>':'').'
<th></th>
<th>ID</th>
</tr>';

		if ($txt_td1) { foreach ($txt_td1 AS $cargo_ID => $d1) {
			echo '<tr><td style="padding-left:0;">'.$d1;
			if ($txt_td2[$cargo_ID]) { foreach ($txt_td2[$cargo_ID] AS $cargo_ID2 => $d2) { 
				echo '<tr><td style="padding-left:25px;">'.$d2; 
				if ($txt_td2[$cargo_ID2]) { foreach ($txt_td2[$cargo_ID2] AS $cargo_ID3 => $d3) { 
					echo '<tr><td style="padding-left:50px;">'.$d3;
					if ($txt_td2[$cargo_ID3]) { foreach ($txt_td2[$cargo_ID3] AS $cargo_ID4 => $d4) { 
						echo '<tr><td style="padding-left:75px;">'.$d4; 
						if ($txt_td2[$cargo_ID4]) { foreach ($txt_td2[$cargo_ID4] AS $cargo_ID5 => $d5) { 
							echo '<tr><td style="padding-left:100px;">'.$d5; 
							if ($txt_td2[$cargo_ID5]) { foreach ($txt_td2[$cargo_ID5] AS $cargo_ID6 => $d6) { 
								echo '<tr><td style="padding-left:125px;">'.$d6; 
							} }
						} }
					} }
				} }
			} }
		} }

	if ($editar) {
		echo '<tr><td colspan="6" align="center">'.boton(_('Editar cargos'), 'submit', '¿Estás seguro que quieres EDITAR toda la configuracion de cargos?\n\nCUIDADO ESTA ACCION PUEDE TENER CONSECUENCIAS IMPORTANTES.', 'large orange').'</form></td></tr>';
	}
	echo '</table>';

	if ($editar) {
		echo '<form action="/accion/cargo/crear" method="POST">

<fieldset><legend>'._('Crear cargo').'</legend>

<p>'._('Nombre').': <input type="text" name="nombre" value="" /></p>

<p><table><tr><td valign="top">'._('Icono').':</td>';
		$directorio = opendir(RAIZ.'/img/cargos/');
		while ($archivo = readdir($directorio)) {
			$img_cargo_ID = explodear('.', $archivo, 0);
			if ((is_numeric($img_cargo_ID)) AND (!in_array($img_cargo_ID, array(0,98,99,7))) AND (!in_array($img_cargo_ID, $cargo_ID_array))) {
				if ($num >= 27) { echo '</tr><tr><td></td>'; $num = 0; } $num++;
				echo '<td align="center"><img src="'.IMG.'cargos/'.$archivo.'" width="16" height="16" title="cargo_ID: '.$img_cargo_ID.'" /><br /><input type="radio" name="cargo_ID" value="'.$img_cargo_ID.'"'.(!$txt_cargo_elegido?' checked="checked"':'').' /></td>';
				$txt_cargo_elegido = true;
			}
		}
		closedir($directorio); 

		echo '</tr></table></p><p>'._('Cargo supeditado a').': <select name="asigna">';
		
		$result2 = mysql_query_old("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' ORDER BY nivel DESC", $link);
		while($r2 = mysqli_fetch_array($result2)){
			echo '<option value="'.$r2['cargo_ID'].'">'.$r2['nombre'].'</option>';
		}
		echo '</select> '._('Nivel').': <input type="text" name="nivel" value="5" maxlength="2" size="2" style="text-align:right;" /></p>

<p>'.boton(_('Crear cargo'), 'submit', '¿Estás seguro de querer CREAR este nuevo cargo?', 'red').'</p>

</fieldset>

</form>';
	}
}


//THEME
$txt_title = _('Cargos');
$txt_menu = 'demo';

?>