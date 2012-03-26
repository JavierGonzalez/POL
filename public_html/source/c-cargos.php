<?php 
include('inc-login.php');


$pol['cargos'] = cargos();


if (is_numeric($_GET['a'])) { // CARGOS

	$result = mysql_query("SELECT * FROM cargos WHERE pais = '".PAIS."' AND cargo_ID = '".$_GET['a']."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {
		$txt_nav = array('/cargos'=>'Cargos', '/cargos/'.$r['cargo_ID']=>$r['nombre']);

		$a = 0;
		$activos = array();
		$candidatos = array();
		$result2 = mysql_query("SELECT *, 
(SELECT nick FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS nick,
(SELECT estado FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS nick_estado,
(SELECT fecha_last FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS fecha_last,
(SELECT voto_confianza FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS voto_confianza
FROM cargos_users
WHERE pais = '".PAIS."' 
AND cargo_ID = '".$r['cargo_ID']."'
AND aprobado = 'ok'
ORDER BY voto_confianza DESC, nota DESC", $link);
		while($r2 = mysql_fetch_array($result2)){

			if ($r['asigna'] > 0) { $asignador = nucleo_acceso('cargo', $r['asigna']); } else { $asignador = false; }

			if ($r2['nick_estado'] == 'ciudadano') {
				if ($r2['cargo'] == 'true') {
					$activos[] = '<tr>
<td>'.($asignador?'<form action="/accion.php?a=cargo&b=del&ID='.$r['cargo_ID'].'" method="post">
<input type="hidden" name="user_ID" value="'.$r2['user_ID'].'"  />'.boton('X', 'submit', '¿Seguro que quieres QUITAR el cargo a '.strtoupper($r2['nick']).'?', 'small red').'</form>':'').'</td>
<td align="right">'.++$activos_num.'.</td>
<td><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" alt="icono '.$r['nombre'].'" width="16" height="16" border="0" style="margin-bottom:-3px;" /> <b>'.crear_link($r2['nick']).'</b></td>
<td align="right" class="gris">'.timer($r2['fecha_last']).'</td>
</tr>';
				} else {
					$candidatos[] = '<tr>
<td>'.($asignador?'<form action="/accion.php?a=cargo&b=add&ID='.$r['cargo_ID'].'" method="POST">
<input type="hidden" name="user_ID" value="'.$r2['user_ID'].'"  />'.boton('Asignar', 'submit', false, 'small blue').'</form>':'').'</td>
<td><b>'.crear_link($r2['nick']).'</b></td>
<td align="right" class="gris">'.timer($r2['fecha_last']).'</td>
<td align="right">'.confianza($r2['voto_confianza']).'</td>
<td align="right"><b>'.num($r2['nota'],1).'</b></td>
</tr>';
				}
			}
		}

		$txt .= '<table border="0"><tr><td valign="top">

<table border="0">
<tr>
<th></th>
<th colspan="2" align="left">'.$r['nombre'].' <span style="font-weight:normal;">('.count($activos).')</span></th>
<th style="font-weight:normal;">Último acceso</th>
</tr>
'.implode('', $activos).'
</table>


	</td><td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td><td valign="top">


<table border="0">
<tr>
<th></th>
<th>Candidatos <span style="font-weight:normal;">('.count($candidatos).')</span></th>
<th style="font-weight:normal;">Último acceso</th>
<th style="font-weight:normal;">Confianza</th>
<th style="font-weight:normal;">Nota</th>
</tr>
'.implode('', $candidatos).'
</table>



</td></tr></table>';

	}

} else { // VER CARGOS
	$txt_nav = array('/cargos'=>'Cargos');
	$txt_tab = array('/examenes'=>'Exámenes');
	if (nucleo_acceso($vp['acceso']['control_cargos'])) {
		$txt_tab['/cargos'] = 'Ver cargos';
		$txt_tab['/cargos/editar'] = 'Editar';
	}


	if (($_GET['a'] == 'editar') AND (nucleo_acceso($vp['acceso']['control_cargos']))) { 
		$editar = true; 
		$txt_nav[] = 'Editar cargos';
		$txt .= '<form action="/accion.php?a=cargo&b=editar" method="POST">';
	} else { $editar = false; }

	if ((nucleo_acceso($vp['acceso']['examenes_decano'])) OR (nucleo_acceso($vp['acceso']['examenes_profesor']))) { $editar_examen = true; } else { $editar_examen = false; }
	
	$cargo_ID_array = array();
	$result = mysql_query("SELECT *, 
(SELECT cargo FROM cargos_users WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' AND cargo_ID = cargos.cargo_ID LIMIT 1) AS cargo,
(SELECT aprobado FROM cargos_users WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' AND cargo_ID = cargos.cargo_ID LIMIT 1) AS aprobado,
(SELECT nota FROM cargos_users WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."' AND cargo_ID = cargos.cargo_ID LIMIT 1) AS nota,
(SELECT ID FROM ".SQL."examenes WHERE cargo_ID = cargos.cargo_ID LIMIT 1) AS examen_ID,
(SELECT COUNT(ID) FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = cargos.cargo_ID AND cargo = 'true') AS cargo_num,
(SELECT COUNT(ID) FROM cargos_users WHERE pais = '".PAIS."' AND cargo_ID = cargos.cargo_ID AND cargo = 'false' AND aprobado = 'ok') AS candidatos_num
FROM cargos WHERE pais = '".PAIS."' ORDER BY nivel DESC", $link);
	while($r = mysql_fetch_array($result)){
		$cargo_ID_array[] = $r['cargo_ID'];
		if (($editar) AND ($r['asigna'] > 0)) { $cargo_editar = true; } else { $cargo_editar = false; }

		switch ($r['asigna']) {
			case -2: $asigna = '<b title="Votacion Ejecutiva">Votacion Ejecutiva</b>'; break;
			case 0:  $asigna = '<a href="/elecciones"><b>Elecciones</b></a>'; break;
			default: $asigna = ''; break;
		}
		
		$txt_el_td = '
<img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" alt="icono '.$r['nombre'].'" width="16" height="16" border="0" /> '.($cargo_editar?'<input type="text" name="nombre_'.$r['cargo_ID'].'" value="'.$r['nombre'].'" />':'<a href="/cargos/'.$r['cargo_ID'].'"><b style="font-size:20px;">'.$r['nombre'].'</b></a>').'</td>';

		if ($cargo_editar) {
			$txt_el_td .= '<td align="right">'.($r['asigna']>0&&$r['cargo_num']==0?boton('X', '/accion.php?a=cargo&b=eliminar&cargo_ID='.$r['cargo_ID'], '¿Estás seguro de querer ELIMINAR este cargo?', 'small red').' ':'').'<select name="asigna_'.$r['cargo_ID'].'">';
			$result2 = mysql_query("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' AND cargo_ID != '".$r['cargo_ID']."' ORDER BY nivel DESC", $link);
			while($r2 = mysql_fetch_array($result2)){
				$txt_el_td .= '<option value="'.$r2['cargo_ID'].'"'.($r['asigna']==$r2['cargo_ID']?' selected="selected"':'').'>'.$r2['nombre'].'</option>';
			}
			$txt_el_td .= '</select></td>';
		} elseif (($editar) AND ($r['asigna'] <= 0)) {
			$txt_el_td .= '<td><b>Sistema</b></td>';
		} else {
			$txt_el_td .= '<td>';
			if ($pol['pais'] == PAIS) {
				if ($r['cargo'] == 'true') {
					$txt_el_td .= boton('Dimitir', '/accion.php?a=cargo&b=dimitir&ID='.$r['cargo_ID'], '¿Estás seguro de querer DIMITIR?\n\n¡NUNCA LO HAGAS EN CALIENTE!', 'red');
				} else if ($r['aprobado'] == 'ok') {
					$txt_el_td .= boton('Repetir examen ('.$r['nota'].')', '/examenes/'.$r['examen_ID'], false, 'blue').' '.boton('Retirar candidatura', '/accion.php?a=examenes&b=retirar_examen&ID='.$r['cargo_ID'], false, 'red');
				} else if ($r['aprobado'] == 'no') {
					$txt_el_td .= boton('Ser candidato (examen, '.$r['nota'].')', '/examenes/'.$r['examen_ID'], false, 'blue');
				} else {
					$txt_el_td .= boton('Ser candidato (examen)', '/examenes/'.$r['examen_ID'], false, 'blue');
				}
			}
			$txt_el_td .= '</td>';
		}

		$txt_el_td .= '
<td align="right"><b style="font-size:16px;">'.$r['cargo_num'].'</b> / '.$r['candidatos_num'].'</td>
<td nowrap="nowrap">'.$asigna.'</td>
<td align="right">'.($cargo_editar?'<input type="text" name="nivel_'.$r['cargo_ID'].'" value="'.$r['nivel'].'" size="3" style="text-align:right;" />':$r['nivel']).'</td>
'.(ECONOMIA?'<td align="right">'.pols($r['salario']).'</td>':'').'
<td>'.($editar_examen?boton('Editar examen', '/examenes/editar/'.$r['examen_ID']):'').'</td>
<td align="right" style="color:grey;">'.$r['cargo_ID'].'</td>
';
		
		$txt_td2[$r['cargo_ID']] = array();
		if ($r['asigna']>0) { // Asignado...
			$txt_td2[$r['asigna']][$r['cargo_ID']] = $txt_el_td;
		} else { // Asignado por elecciones...
			$txt_td1[$r['cargo_ID']] = $txt_el_td;
		}
	}


		$txt .= '
<table border="0" cellspacing="3" cellpadding="0">
<tr>
<th></th>
<th>'.($editar?'Supeditado a':'').'</th>
<th title="Con cargo / Candidatos">Con cargo</th>
<th title="¿Quien asigna?">Asignación</th>
<th>Nivel</th>
'.(ECONOMIA?'<th title="Salario por dia trabajado">Salario</th>':'').'
<th></th>
<th>ID</th>
</tr>';

		foreach ($txt_td1 AS $cargo_ID => $d1) {
			$txt .= '<tr><td nowrap="nowrap">'.$d1.'</tr>';
			foreach ($txt_td2[$cargo_ID] AS $cargo_ID2 => $d2) { 
				$txt .= '<tr><td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp; '.$d2.'</tr>'; 
				foreach ($txt_td2[$cargo_ID2] AS $cargo_ID3 => $d3) { 
					$txt .= '<tr><td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; '.$d3.'</tr>';
					foreach ($txt_td2[$cargo_ID3] AS $cargo_ID4 => $d4) { 
						$txt .= '<tr><td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; '.$d4.'</tr>'; 
						foreach ($txt_td2[$cargo_ID4] AS $cargo_ID5 => $d5) { 
							$txt .= '<tr><td nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; '.$d5.'</tr>'; 
						}
					}
				}
			}
		}

	if ($editar) {
		$txt .= '<tr><td colspan="6" align="center">'.boton('Editar cargos', 'submit', '¿Estás seguro que quieres EDITAR toda la configuracion de cargos?\n\nCUIDADO ESTA ACCION PUEDE TENER CONSECUENCIAS IMPORTANTES.', 'large red').'</form></td></tr>';
	}
	$txt .= '</table>';

	if ($editar) {
		$txt .= '<form action="/accion.php?a=cargo&b=crear" method="POST">

<p>Nombre: <input type="text" name="nombre" value="" /></p>

<p>Icono (único): ';
		$directorio = opendir(RAIZ.'/img/cargos/');
		while ($archivo = readdir($directorio)) {
			$img_cargo_ID = explodear('.', $archivo, 0);
			if ((is_numeric($img_cargo_ID)) AND (!in_array($img_cargo_ID, array(0,98,99,7,6))) AND (!in_array($img_cargo_ID, $cargo_ID_array))) {
				$txt .= '<input type="radio" name="cargo_ID" value="'.$img_cargo_ID.'"'.(!$txt_cargo_elegido?' checked="checked"':'').' /><img src="'.IMG.'cargos/'.$archivo.'" width="16" height="16" title="cargo_ID: '.$img_cargo_ID.'" /> &nbsp; &nbsp; ';
				$txt_cargo_elegido = true;
			}
		}
		closedir($directorio); 

		$txt .= '</p><p>Cargo supeditado a: <select name="asigna">';
		
		$result2 = mysql_query("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' ORDER BY nivel DESC", $link);
		while($r2 = mysql_fetch_array($result2)){
			$txt .= '<option value="'.$r2['cargo_ID'].'">'.$r2['nombre'].'</option>';
		}
		$txt .= '</select> Nivel: <input type="text" name="nivel" value="5" maxlength="2" size="2" style="text-align:right;" /></p>

<p>'.boton('Crear cargo', 'submit', '¿Estás seguro de querer CREAR este nuevo cargo?', 'red').'</p>

</form>';
	}
}


//THEME
$txt_title = 'Cargos';
$txt_menu = 'demo';
include('theme.php');
?>