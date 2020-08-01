<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 

if (($_GET[1] == 'editar') AND ($_GET[2])) { //EDITAR EMPRESA

	$result = mysql_query_old("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time,
(SELECT nombre FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_nom,
(SELECT url FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_url,
(SELECT nick FROM users WHERE ID = empresas.user_ID LIMIT 1) AS nick
FROM empresas
WHERE pais = '".PAIS."' AND ID = '".$_GET[2]."' 
AND user_ID = '".$pol['user_ID']."'
LIMIT 1", $link);
	while($r = mysqli_fetch_array($result)) {
		
		$txt_title = _('Empresa').': ' . $r['nombre'] . ' - '._('Sector').': ' . $r['cat_nom'];
		$txt_nav = array('/empresas'=>_('Empresas'), '/empresas/'.$r['url']=>$r['cat_nom'], $r['nombre'], _('Editar'));

		
		echo '
<form action="/accion/empresa/editar?ID='.$r['ID'].'" method="post">
<input type="hidden" name="return" value="'.$r['cat_url'].'/'.$r['url'].'" />

<p class="amarillo">'._('Fundador').': <b>'.crear_link($r['nick']).'</b> '._('el').' <em>'.explodear(' ', $r['time'], 0).'</em>, '._('sector').': <a href="/empresas/'.$r['cat_url'].'">'.$r['cat_nom'].'</a></p>

<p class="amarillo">'.editor_enriquecido('txt', $r['descripcion']).'</p>

<p><input type="submit" value="'._('Guardar').'" /> &nbsp; <a href="/empresas"><b>'._('Ver empresas').'</b></a></form></p>
';
	}


} elseif ($_GET[1] == 'crear-empresa') { //CREAR EMPRESA

	$result = mysql_query_old("SELECT ID, url, nombre, num FROM cat WHERE pais = '".PAIS."' AND tipo = 'empresas' ORDER BY num DESC", $link);
	while($r = mysqli_fetch_array($result)) {
		$txt_li .= '<option value="'.$r['ID'].'">'.$r['nombre'].'</option>';
	}

	echo '
<form action="/accion/empresa/crear" method="post">

<p>'._('Sector').': <select name="cat">' . $txt_li . '</select> ('._('No modificable').')</p>

<p>'._('Nombre').': <input type="text" name="nombre" size="20" maxlength="20" /> ('._('No modificable').')</p>

<p>'.boton('Crear Empresa', false, false, '', $pol['config']['pols_empresa']).'</p>

<p><a href="/empresas"><b>'._('Ver empresas').'</b></a></p>

</form>';

} elseif (($_GET[1]) AND (!$_GET[2])) { //VER SECTOR

	$result = mysql_query_old("SELECT ID, url, nombre, num
FROM cat
WHERE pais = '".PAIS."' AND tipo = 'empresas'
AND url = '".$_GET[1]."'
ORDER BY num DESC", $link);
	while($r = mysqli_fetch_array($result)) {
		$cat_ID = $r['ID'];
		$cat_nom = $r['nombre'];
		$cat_num = $r['num'];
	}
	
	$txt_nav = array('/empresas'=>_('Empresas'), $cat_nom);

	echo '<table border="0" cellspacing="0" cellpadding="2">

<tr class="amarillo"><td>
<a href="/empresas/'.$_GET[1].'"><b>'.$cat_nom.'</b></a></td><td>'.$cat_num.'</td><td></td>
</tr>';

	$result = mysql_query_old("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time, pv,
(SELECT nick FROM users WHERE ID = empresas.user_ID LIMIT 1) AS nick
FROM empresas
WHERE pais = '".PAIS."' AND cat_ID = '".$cat_ID."'
ORDER BY time ASC", $link);
	while($r = mysqli_fetch_array($result)) {
		echo '<tr><td align="right">'.crear_link($r['nick']).'</td><td><a href="/empresas/'.$_GET[1].'/'.$r['url'].'"><b>'.$r['nombre'].'</b></a></td><td align="right"><b>'.$r['pv'].'</b> '._('visitas').'</td></tr>';
	}
	echo '</table><p><a href="/empresas"><b>'._('Ver empresas').'</b></a></p>';


} elseif ($_GET[1]) { //VER EMPRESA

	$result = mysql_query_old("SELECT ID FROM cat WHERE pais = '".PAIS."' AND tipo = 'empresas' AND url = '".$_GET[1]."' LIMIT 1", $link);
	while($r = mysqli_fetch_array($result)) { $cat_ID = $r['ID']; }

	$result = mysql_query_old("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time, pv,
(SELECT nombre FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_nom,
(SELECT url FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_url,
(SELECT nick FROM users WHERE ID = empresas.user_ID LIMIT 1) AS nick
FROM empresas
WHERE pais = '".PAIS."' AND url = '".$_GET[2]."' AND cat_ID = '".$cat_ID."'
LIMIT 1", $link);
	while($r = mysqli_fetch_array($result)) {

		mysql_query_old("UPDATE empresas SET pv = pv+1 WHERE pais = '".PAIS."' AND ID = '".$r['ID']."' LIMIT 1", $link);
		$r['pv']++;

		$txt_title = _('Empresas').': '.$r['nombre'].' - '._('Sector').': '.$r['cat_nom'];
		$txt_nav = array('/empresas'=>_('Empresas'), '/empresas/'.$_GET[1]=>$r['cat_nom'], $r['nombre']);

		if ($pol['user_ID'] == $r['user_ID']) { $editar .= boton(_('Editar'), '/empresas/editar/'.$r['ID']); }
		
		echo '<br /><div class="amarillo">'.html_entity_decode($r['descripcion'],ENT_COMPAT , 'UTF-8').'</div>

<p class="azul">'._('Fundador').': <b>'.crear_link($r['nick']).'</b> | '._('creación').': <em>'.explodear(" ", $r['time'], 0).'</em> | '._('sector').': <a href="/empresas/'.$r['cat_url'].'">'.$r['cat_nom'].'</a> | '._('visitas').': '.$r['pv'].'</p>

<table width="100%"><tr>';
		if ($r['user_ID'] == $pol['user_ID']) {  
			echo '<td><form action="/accion/empresa/acciones?ID='.$r['ID'].'" method="post">
'._('Ceder acciones a').': <input type="text" name="nick" size="8" maxlength="20" value="" /><br />
'._('Cantidad de acciones a').': <input type="text" name="cantidad" size="8" maxlength="3" value="" /><br />
'.boton(_('Ceder'), 'submit', false, 'small').' ['._('En desarrollo').']
</form></td>';
			$boton = '<form action="/accion/empresa/ceder?ID='.$r['ID'].'" method="post">
'.boton(_('Ceder a').':', 'submit', false, 'small').' <input type="text" name="nick" size="8" maxlength="20" value="" /></form> '.boton('X', '/accion/empresa/eliminar?ID='.$r['ID'], '¿Estas seguro de querer ELIMINAR definitivamente esta empresa?', 'red'); 
		}
		echo '<td align="right">'.$boton.$editar.'</td></tr></table>';
	}

} else { // #EMPRESAS
	
	$txt_nav = array('/empresas'=>_('Empresas'));

	echo '<p>'.boton(_('Crear Empresa'), '/empresas/crear-empresa', false, '', $pol['config']['pols_empresa']).'</p>

<table border="0" cellspacing="0" cellpadding="2">';

	$result = mysql_query_old("SELECT ID, url, nombre, num
FROM cat
WHERE pais = '".PAIS."' AND tipo = 'empresas'
ORDER BY orden ASC", $link);
	while($r = mysqli_fetch_array($result)) {
		$pv_num = 0;
		$result2 = mysql_query_old("SELECT pv FROM empresas WHERE pais = '".PAIS."' AND cat_ID = '".$r['ID']."'", $link);
		while($r2 = mysqli_fetch_array($result2)) { $pv_num += $r2['pv']; }

		echo '<tr class="amarillo"><td><a href="/empresas/'.$r['url'].'" style="font-size:19px;"><b>'.$r['nombre'].'</b></a></td><td align="right"><b>'.$r['num'].'</b> '._('empresas').'</td><td align="right"><b>'.$pv_num.'</b> '._('visitas').'</td></tr>';

		echo '<tr><td colspan="3" height="6"></td></tr>';
	}

	echo '</table><p>'.boton(_('Crear Empresa'), '/empresas/crear-empresa', false, '', $pol['config']['pols_empresa']).'</p>';
}


//THEME
if (!$txt_title) { $txt_title = _('Empresas'); }
$txt_menu = 'econ';

?>