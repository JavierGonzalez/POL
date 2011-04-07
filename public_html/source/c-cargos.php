<?php 
include('inc-login.php');


$pol['cargos'] = cargos();


if ($_GET['a']) { // EDITAR CARGOS

	$result = mysql_query("SELECT ID, nombre, tiempo, nivel, num_cargo, salario
FROM ".SQL."estudios
WHERE ID = '" . $_GET['a'] . "'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {
		$txt .= '<h1><img src="'.IMG.'doc-edit.gif" alt="Editar" /> <a href="/cargos/">Cargo</a>: ' . $r['nombre'] . '</h1><ul>';

		$result2 = mysql_query("SELECT user_ID, nota,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick,
(SELECT cargo FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick_cargo,
(SELECT estado FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick_estado
FROM ".SQL."estudios_users 
WHERE estado = 'ok' 
AND ID_estudio = '" . $r['ID'] . "'
AND cargo = '0'
AND user_ID != '" . $pol['user_ID'] . "'
ORDER BY nick ASC", $link);
		while($r2 = mysql_fetch_array($result2)){
			if (($r2['nick_estado'] == 'ciudadano')) { // ($r2['nick_cargo'] == '0') AND 
				$ciudadanos .= '<option value="' . $r2['user_ID'] . '">' . $r2['nota'] . ' ' . $r2['nick'] . '</option>';
			}
		}

		$txt .= '<li><form action="/accion.php?a=cargo&b=add&ID=' . $r['ID'] . '" method="post"><select name="user_ID">' . $ciudadanos . '</select> <input type="submit" value="Asignar" /></form><br /></li></ul>';


		$a = 0;
		$result2 = mysql_query("SELECT user_ID, 
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick
FROM ".SQL."estudios_users 
WHERE ID_estudio = '" . $r['ID'] . "'
AND cargo = '1'
ORDER BY nick ASC", $link);
		while($r2 = mysql_fetch_array($result2)){
			$a++;
			$activos .= '<li><form action="/accion.php?a=cargo&b=del&ID=' . $r['ID'] . '" method="post"><input type="hidden" name="user_ID" value="' . $r2['user_ID'] . '"  /><input style="height:26px;" type="submit" value="X" /> <b>' . crear_link($r2['nick']) . '</b></form></li>';
		}

		$txt .= '<div class="azul"><p><b style="color:green;">' . $r['nombre'] . ': ' . $a . '</b></p><ol>' . $activos . '</ol></div>';
	}

} else { // VER CARGOS

	$txt .= '<h1>Cargos</h1><br />

<table border="0" cellspacing="3" cellpadding="0" class="pol_table">
<tr>
<th>Nivel&darr;</th>
<th>Cargo</th>
<th><acronym title="Salario por dia trabajado">Salario</acronym></th>
<th>Cargos</th>
<th colspan="2">Asigna</th>

</tr>';

	$cargos = cargos();

	$result = mysql_query("SELECT 
ID, nombre, nivel, num_cargo, asigna, salario, ico
FROM ".SQL."estudios 
WHERE asigna != '-1'
ORDER BY nivel DESC", $link);
	while($r = mysql_fetch_array($result)){


		$p_edit = '';
		if (($pol['nivel'] == 120) OR ($pol['cargos'][$r['asigna']]) OR (($r['ID'] != 19) AND ($r['asigna'] == 7) AND ($pol['cargos'][19]))) { 
			$p_edit = '<form><input style="margin-bottom:-16px;" type="button" value="Editar" onClick="window.location.href=\'/cargos/' . $r['ID'] . '/\';"></form>';
		}

		$c_nicks = '';
		$num = 0;
		$result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."estudios_users.user_ID LIMIT 1) AS nick
FROM ".SQL."estudios_users 
WHERE cargo = '1'
AND ID_estudio = '" . $r['ID'] . "'
ORDER BY nick ASC", $link);
		while($r2 = mysql_fetch_array($result2)){
			$num++;
			if ($c_nicks) { $c_nicks .= ', '; } 
			$c_nicks .= crear_link($r2['nick']);
		}


		switch ($r['asigna']) {
			case -3: case -2: $asignado_por = '<acronym title="Consulta Ejecutiva (proximamente)"><b>C. Ejecutiva</b></acronym>'; break;
			case 0: $asignado_por = '<acronym title="Elecciones Generales"><b>Elecciones</b></acronym>'; break;
			default: $asignado_por = '<img src="'.IMG.'cargos/'.$r['asigna'].'.gif" title="" />'; break;
		}


		if ($r['ico'] == true) { $ico = '<img src="'.IMG.'cargos/' . $r['ID'] . '.gif" alt="icono ' . $r['nombre'] . '" border="0" /> '; } else { $ico = ''; }

		$txt .= '<tr>
<td align="right" valign="top">' . $r['nivel'] . '</td>
<td valign="top" nowrap="nowrap">' . $ico . '<b>' . $r['nombre'] . '</b></td>
<td align="right" valign="top">' . pols($r['salario']) . '</td>
<td valign="top">' . $num . ' <b>' . $c_nicks . '</b></td><td valign="top" nowrap="nowrap">' . $asignado_por . '</td>
<td valign="top">' . $p_edit . '</td></tr>';
	}
	$txt .= '</table>';

}


//THEME
$txt_title = 'Cargos';
include('theme.php');
?>
