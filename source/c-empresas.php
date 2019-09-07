<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

use App\Controllers\EmpresaController;

include('inc-login.php');

$urlA = $_GET['a'];
$urlB = $_GET['b'];

$empresaController = new EmpresaController($pol);
$empresaController->setUrls($urlA, $urlB);

if (($_GET['a'] == 'editar') AND ($_GET['b'])) { //EDITAR EMPRESA

	$result = mysql_query("SELECT ID, url, nombre, user_ID, descripcion, web, cat_ID, time,
(SELECT nombre FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_nom,
(SELECT url FROM cat WHERE pais = '".PAIS."' AND ID = empresas.cat_ID LIMIT 1) AS cat_url,
(SELECT nick FROM users WHERE ID = empresas.user_ID LIMIT 1) AS nick
FROM empresas
WHERE pais = '".PAIS."' AND ID = '".$_GET['b']."' 
AND user_ID = '".$pol['user_ID']."'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) {
		
		$txt_title = _('Empresa').': ' . $r['nombre'] . ' - '._('Sector').': ' . $r['cat_nom'];
		$txt_nav = array('/empresas'=>_('Empresas'), '/empresas/'.$r['url']=>$r['cat_nom'], $r['nombre'], _('Editar'));

		include('inc-functions-accion.php');
		$txt .= '
<form action="/accion.php?a=empresa&b=editar&ID='.$r['ID'].'" method="post">
<input type="hidden" name="return" value="'.$r['cat_url'].'/'.$r['url'].'" />

<p class="amarillo">'._('Fundador').': <b>'.crear_link($r['nick']).'</b> '._('el').' <em>'.explodear(' ', $r['time'], 0).'</em>, '._('sector').': <a href="/empresas/'.$r['cat_url'].'">'.$r['cat_nom'].'</a></p>

<p class="amarillo">'.editor_enriquecido('txt', $r['descripcion']).'</p>

<p><input type="submit" value="'._('Guardar').'" /> &nbsp; <a href="/empresas"><b>'._('Ver empresas').'</b></a></form></p>
';
	}


} elseif ($_GET['a'] == 'crear-empresa') { //CREAR EMPRESA

	$txt = $empresaController->crearEmpresa();

} elseif (($_GET['a']) AND (!$_GET['b'])) { //VER SECTOR

	$txt_nav = array('/empresas'=>_('Empresas'), $empresaController->getCategory()->nombre);
	$txt .= $empresaController->verCategoria();

} elseif ($_GET['a']) { //VER EMPRESA

	$cat_nom = $empresaController->getCategory()->nombre;
	$emp_nom = $empresaController->getEmpresa()->nombre;

	$txt_title = _('Empresas').': '.$emp_nom.' - '._('Sector').': '.$cat_nom;
	$txt_nav = array('/empresas' => _('Empresas'), '/empresas/'.$urlA => $cat_nom, $emp_nom);
	$txt = $empresaController->verEmpresa();

} else { // #EMPRESAS
	
	$txt_nav = array('/empresas'=>_('Empresas'));
	$txt .= $empresaController->indexCategorias();
}


//THEME
if (!$txt_title) { $txt_title = _('Empresas'); }
$txt_menu = 'econ';
include('theme.php');
?>