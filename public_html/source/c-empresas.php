<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');
/*
pol_cat			(ID, url, nombre, time, num, nivel, tipo[empresas|docs|cargos])
pol_empresas	(ID, url, nombre, user_ID, descripcion, web, cat_ID, time)
*/
if (($_GET['a'] == 'editar') AND ($_GET['b'])) { //EDITAR EMPRESA

	$result = mysql_query("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time,
(SELECT nombre FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_nom,
(SELECT url FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_url,
(SELECT nick FROM users WHERE ID = empresas.user_ID LIMIT 1) AS nick
FROM empresas
WHERE pais = '".PAIS."' AND ID = '" . $_GET['b'] . "' 
AND user_ID = '" . $pol['user_ID'] . "'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {
		
		$txt_title = 'Empresa: ' . $r['nombre'] . ' - Sector: ' . $r['cat_nom'];
		$txt_nav = array('/empresas'=>'Empresas', '/empresas/'.$r['url']=>$r['cat_nom'], $r['nombre'], 'Editar');

		include('inc-functions-accion.php');
		$txt .= '<h1 class="quitar">Editar: ' . $r['nombre'] . '</h1>

<form action="/accion.php?a=empresa&b=editar&ID=' . $r['ID'] . '" method="post">
<input type="hidden" name="return" value="' . $r['cat_url'] . '/' . $r['url'] . '/" />

<p class="amarillo">Fundador: <b>' . crear_link($r['nick']) . '</b> el <em>' . explodear(" ", $r['time'], 0) . '</em>, sector: <a href="/empresas/' . $r['cat_url'] . '/">' . $r['cat_nom'] . '</a></p>

<p class="amarillo">' . editor_enriquecido('txt', $r['descripcion']) . '</p>

<p><input type="submit" value="Guardar" /> &nbsp; <a href="/empresas/"><b>Ver Empresas</b></a></form></p>
';

	}


} elseif ($_GET['a'] == 'crear-empresa') { //CREAR EMPRESA

	$result = mysql_query("SELECT ID, url, nombre, num
FROM cat
WHERE pais = '".PAIS."' AND tipo = 'empresas'
ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$txt_li .= '<option value="' . $r['ID'] . '">' . $r['nombre'] . '</option>';
	}

	$txt .= '<h1 class="quitar"><a href="/empresas/">Empresas</a>: Crear Empresa</h1>

<form action="/accion.php?a=empresa&b=crear" method="post">

<p>Sector: <select name="cat">' . $txt_li . '</select> (No modificable)</p>

<p>Nombre: <input type="text" name="nombre" size="20" maxlength="20" /> (No modificable)</p>

<p>' . boton('Crear Empresa', false, false, '', $pol['config']['pols_empresa']) . '</p>

<p><a href="/empresas/"><b>Ver Empresas</b></a></p>

</form>';

} elseif (($_GET['a']) AND (!$_GET['b'])) { //VER SECTOR

	$result = mysql_query("SELECT ID, url, nombre, num
FROM cat
WHERE pais = '".PAIS."' AND tipo = 'empresas'
AND url = '" . $_GET['a'] . "'
ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)) {
		$cat_ID = $r['ID'];
		$cat_nom = $r['nombre'];
		$cat_num = $r['num'];
	}
	
	$txt_nav = array('/empresas'=>'Empresas', $cat_nom);

	$txt .= '<h1 class="quitar"><a href="/empresas/">Empresas</a>: ' . $cat_nom . '</h1>
<br />
<table border="0" cellspacing="0" cellpadding="2" class="pol_table">';

	$txt .= '<tr class="amarillo"><td><a href="/empresas/' . $_GET['a'] . '/"><b>' . $cat_nom . '</b></a></td><td>' . $cat_num . '</td><td></td></tr>';


	$result = mysql_query("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time, pv,
(SELECT nick FROM users WHERE ID = empresas.user_ID LIMIT 1) AS nick
FROM empresas
WHERE pais = '".PAIS."' AND cat_ID = '" . $cat_ID . "'
ORDER BY time ASC", $link);
	while($r = mysql_fetch_array($result)) {
		$txt .= '<tr><td align="right">' . crear_link($r['nick']) . '</td><td><a href="/empresas/' . $_GET['a'] . '/' . $r['url'] . '/"><b>' . $r['nombre'] . '</b></a></td><td align="right"><b>'.$r['pv'].'</b> visitas</td></tr>';
	}
	$txt .= '</table><p><a href="/empresas/"><b>Ver Empresas</b></a></p>';


} elseif ($_GET['a']) { //VER EMPRESA

	$result = mysql_query("SELECT ID FROM cat WHERE pais = '".PAIS."' AND tipo = 'empresas' AND url = '".$_GET['a']."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) { $cat_ID = $r['ID']; }

	$result = mysql_query("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time, pv,
(SELECT nombre FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_nom,
(SELECT url FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_url,
(SELECT nick FROM users WHERE ID = empresas.user_ID LIMIT 1) AS nick
FROM empresas
WHERE pais = '".PAIS."' AND url = '" . $_GET['b'] . "' AND cat_ID = '".$cat_ID."'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {

		mysql_query("UPDATE empresas SET pv = pv+1 WHERE pais = '".PAIS."' AND ID = '" . $r['ID'] . "'", $link);
		$r['pv']++;

		$txt_title = 'Empresa: '.$r['nombre'].' - Sector: '.$r['cat_nom'];
		$txt_nav = array('/empresas'=>'Empresas', '/empresas/'.$_GET['a']=>$r['cat_nom'], $r['nombre']);

		if ($pol['user_ID'] == $r['user_ID']) { $editar .= boton('Editar', '/empresas/editar/'.$r['ID']); }
		
		$txt .= '<br /><div class="amarillo">'.$r['descripcion'].'</div>

<p class="azul">Fundador: <b>'.crear_link($r['nick']).'</b> | creación: <em>'.explodear(" ", $r['time'], 0).'</em> | sector: <a href="/empresas/'.$r['cat_url'].'">'.$r['cat_nom'].'</a> | visitas: '.$r['pv'].'</p>

<table width="100%"><tr>';
		if ($r['user_ID'] == $pol['user_ID']) {  
			$txt .= '<td><form action="/accion.php?a=empresa&b=acciones&ID='.$r['ID'].'" method="post">
Ceder acciones a: <input type="text" name="nick" size="8" maxlength="20" value="" /><br />
Cantidad de acciones: <input type="text" name="cantidad" size="8" maxlength="3" value="" /><br />
'.boton('Ceder', 'submit', false, 'small').' [En desarrollo]
</form></td>';
			$boton = '<form action="/accion.php?a=empresa&b=ceder&ID='.$r['ID'].'" method="post">
'.boton('Ceder a:', 'submit', false, 'small').' <input type="text" name="nick" size="8" maxlength="20" value="" /></form> '.boton('X', '/accion.php?a=empresa&b=eliminar&ID='.$r['ID'], '¿Estas seguro de querer ELIMINAR definitivamente esta empresa?', 'red'); 
		}
		$txt .= '<td align="right">'.$boton.$editar.'</td></tr></table>';
	}

} else { // #EMPRESAS
	
	$txt_nav = array('/empresas'=>'Empresas');

	$txt .= '<p>'.boton('Crear Empresa', '/empresas/crear-empresa/', false, '', $pol['config']['pols_empresa']).'</p>

<table border="0" cellspacing="0" cellpadding="2" class="pol_table">';

	$result = mysql_query("SELECT ID, url, nombre, num
FROM cat
WHERE pais = '".PAIS."' AND tipo = 'empresas'
ORDER BY orden ASC", $link);
	while($r = mysql_fetch_array($result)) {
		$pv_num = 0;
		$result2 = mysql_query("SELECT pv FROM empresas WHERE pais = '".PAIS."' AND cat_ID = '" . $r['ID'] . "'", $link);
		while($r2 = mysql_fetch_array($result2)) { $pv_num += $r2['pv']; }

		$txt .= '<tr class="amarillo"><td><a href="/empresas/'.$r['url'].'" style="font-size:19px;"><b>'.$r['nombre'].'</b></a></td><td align="right"><b>'.$r['num'].'</b> empresas</td><td align="right"><b>'.$pv_num.'</b> visitas</td></tr>';

		$txt .= '<tr><td colspan="3" height="6"></td></tr>';
	}

	$txt .= '</table><p>'.boton('Crear Empresa', '/empresas/crear-empresa', false, '', $pol['config']['pols_empresa']).'</p>';
}




//THEME
if (!$txt_title) { $txt_title = 'Empresas'; }
$txt_menu = 'econ';
include('theme.php');
?>