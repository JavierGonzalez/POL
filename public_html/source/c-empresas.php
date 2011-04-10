<?php 
include('inc-login.php');
/*
pol_cat			(ID, url, nombre, time, num, nivel, tipo[empresas|docs|cargos])
pol_empresas	(ID, url, nombre, user_ID, descripcion, web, cat_ID, time)
*/
if (($_GET['a'] == 'editar') AND ($_GET['b'])) { //EDITAR EMPRESA

	$result = mysql_query("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time,
(SELECT nombre FROM ".SQL."cat WHERE ID = ".SQL."empresas.cat_ID LIMIT 1) AS cat_nom,
(SELECT url FROM ".SQL."cat WHERE ID = ".SQL."empresas.cat_ID LIMIT 1) AS cat_url,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."empresas.user_ID LIMIT 1) AS nick
FROM ".SQL."empresas
WHERE ID = '" . $_GET['b'] . "' 
AND user_ID = '" . $pol['user_ID'] . "'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {
		$txt_title = 'Empresa: ' . $r['nombre'] . ' - Sector: ' . $r['cat_nom'];
		include('inc-functions-accion.php');
		$txt .= '<h1>Editar: ' . $r['nombre'] . '</h1>

<form action="/accion.php?a=empresa&b=editar&ID=' . $r['ID'] . '" method="post">
<input type="hidden" name="return" value="' . $r['cat_url'] . '/' . $r['url'] . '/" />

<p class="amarillo">Fundador: <b>' . crear_link($r['nick']) . '</b> el <em>' . explodear(" ", $r['time'], 0) . '</em>, sector: <a href="/empresas/' . $r['cat_url'] . '/">' . $r['cat_nom'] . '</a></p>

<p class="amarillo">' . editor_enriquecido('txt', $r['descripcion']) . '</p>

<p><input type="submit" value="Guardar" /> &nbsp; <a href="/empresas/"><b>Ver Empresas</b></a></form></p>
';

	}


} elseif ($_GET['a'] == 'crear-empresa') { //CREAR EMPRESA

	$result = mysql_query("SELECT ID, url, nombre, num
FROM ".SQL."cat
WHERE tipo = 'empresas'
ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$txt_li .= '<option value="' . $r['ID'] . '">' . $r['nombre'] . '</option>';
	}

	$txt .= '<h1><a href="/empresas/">Empresas</a>: Crear Empresa</h1>

<form action="/accion.php?a=empresa&b=crear" method="post">

<p>Sector: <select name="cat">' . $txt_li . '</select> (No modificable)</p>

<p>Nombre: <input type="text" name="nombre" size="20" maxlength="20" /> (No modificable)</p>

<p>' . boton('Crear Empresa', false, false, '', $pol['config']['pols_empresa']) . '</p>

<p><a href="/empresas/"><b>Ver Empresas</b></a></p>

</form>';

} elseif (($_GET['a']) AND (!$_GET['b'])) { //VER SECTOR

	$result = mysql_query("SELECT ID, url, nombre, num
FROM ".SQL."cat
WHERE tipo = 'empresas'
AND url = '" . $_GET['a'] . "'
ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$cat_ID = $r['ID'];
		$cat_nom = $r['nombre'];
		$cat_num = $r['num'];
	}
	$txt .= '<h1><a href="/empresas/">Empresas</a>: ' . $cat_nom . '</h1>
<br />
<table border="0" cellspacing="0" cellpadding="2" class="pol_table">';

	$txt .= '<tr class="amarillo"><td><a href="/empresas/' . $_GET['a'] . '/"><b>' . $cat_nom . '</b></a></td><td>' . $cat_num . '</td><td></td></tr>';


	$result = mysql_query("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."empresas.user_ID LIMIT 1) AS nick
FROM ".SQL."empresas
WHERE cat_ID = '" . $cat_ID . "'
ORDER BY time ASC", $link);
	while($r = mysql_fetch_array($result)) {
		$txt .= '<tr><td align="right">' . crear_link($r['nick']) . '</td><td><a href="/empresas/' . $_GET['a'] . '/' . $r['url'] . '/"><b>' . $r['nombre'] . '</b></a></td><td></td></tr>';
	}
	$txt .= '</table><p><a href="/empresas/"><b>Ver Empresas</b></a></p>';


} elseif ($_GET['a']) { //VER EMPRESA

	$result = mysql_query("SELECT ID FROM ".SQL."cat WHERE tipo = 'empresas' AND url = '".$_GET['a']."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) { $cat_ID = $r['ID']; }

	$result = mysql_query("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time, pv,
(SELECT nombre FROM ".SQL."cat WHERE ID = ".SQL."empresas.cat_ID LIMIT 1) AS cat_nom,
(SELECT url FROM ".SQL."cat WHERE ID = ".SQL."empresas.cat_ID LIMIT 1) AS cat_url,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."empresas.user_ID LIMIT 1) AS nick
FROM ".SQL."empresas
WHERE url = '" . $_GET['b'] . "' AND cat_ID = '".$cat_ID."'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {

		mysql_query("UPDATE ".SQL."empresas SET pv = pv+1 WHERE ID = '" . $r['ID'] . "'", $link);
		$r['pv']++;

		$txt_title = 'Empresa: ' . $r['nombre'] . ' - Sector: ' . $r['cat_nom'];
		if ($pol['user_ID'] == $r['user_ID']) { $editar .= boton('Editar', '/empresas/editar/' . $r['ID'] . '/'); }
		$txt .= '<h1><a href="/empresas/">Empresas</a>: <a href="/empresas/' . $r['cat_url'] . '/">' . $r['cat_nom'] . '</a> | ' . $r['nombre'] . ' ' . $editar . '</h1>
<br />

<div class="amarillo">' . $r['descripcion'] . '</div>

<p class="azul">Fundador: <b>' . crear_link($r['nick']) . '</b> | creaci&oacute;n: <em>' . explodear(" ", $r['time'], 0) . '</em> | sector: <a href="/empresas/' . $r['cat_url'] . '/">' . $r['cat_nom'] . '</a> | visitas: ' . $r['pv'] . '</p>

';
if ($r['user_ID'] == $pol['user_ID']) { $boton = '<form action="/accion.php?a=empresa&b=acciones&ID='.$r['ID'].'" method="post">
$txt .= 'Ceder acciones a:<input type="text" name="nick" size="8" maxlength="20" value="" /><br />
Cantidad de acciones:<input type="text" name="cantidad size="8" maxlength="3" value="" /><br />
<input type="submit" value="Ceder" />
</form>';
                                                                                                         }
		if ($r['user_ID'] == $pol['user_ID']) { $boton = '<form action="/accion.php?a=empresa&b=ceder&ID='.$r['ID'].'" method="post">
<input type="submit" value="Ceder a:" /> <input type="text" name="nick" size="8" maxlength="20" value="" /></form> '.boton('X', '/accion.php?a=empresa&b=eliminar&ID=' . $r['ID'], '&iquest;Estas seguro de querer ELIMINAR definitivamente esta empresa?'); }
		$txt .= '<span style="float:right;">' . $boton . $editar . '</span>';
	}

} else { // #EMPRESAS
	$txt .= '<h1>Empresas:</h1>

<p>' . boton('Crear Empresa', '/empresas/crear-empresa/', false, '', $pol['config']['pols_empresa']) . '</p>

<table border="0" cellspacing="0" cellpadding="2" class="pol_table">';

	$result = mysql_query("SELECT ID, url, nombre, num
FROM ".SQL."cat
WHERE tipo = 'empresas'
ORDER BY orden ASC", $link);
	while($r = mysql_fetch_array($result)) {
		$txt .= '<tr class="amarillo"><td><a href="/empresas/' . $r['url'] . '/"><b>' . $r['nombre'] . '</b></a></td><td>' . $r['num'] . '</td><td>Visitas</td></tr>';



		$result2 = mysql_query("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, pv, time,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."empresas.user_ID LIMIT 1) AS nick
FROM ".SQL."empresas
WHERE cat_ID = '" . $r['ID'] . "'
ORDER BY pv DESC", $link);
		while($r2 = mysql_fetch_array($result2)) {
			$txt .= '<tr><td align="right">' . crear_link($r2['nick']) . '</td><td><a href="/empresas/' . $r['url'] . '/' . $r2['url'] . '/"><b>' . $r2['nombre'] . '</b></a></td><td align="right"><b>' . $r2['pv'] . '</b></td></tr>';
		}
		$txt .= '<tr><td colspan="3"></td></tr>';
	}

	$txt .= '</table><p>' . boton('Crear Empresa', '/empresas/crear-empresa/', false, '', $pol['config']['pols_empresa']) . '</p>';
}




//THEME
if (!$txt_title) { $txt_title = 'Empresas'; }
include('theme.php');
?>
