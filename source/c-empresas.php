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

	$category = $empresaController->getCategory();
	$empresa = $empresaController->getEmpresa();
	
	$txt_title = _('Empresa').': ' . $empresa->nombre . ' - '._('Sector').': ' . $category->nombre;
	$txt_nav = array('/empresas'=>_('Empresas'), '/empresas/'.$category->url=>$category->nombre, $empresa->nombre, _('Editar'));

	include('inc-functions-accion.php');

	if($empresa->user_ID == $pol['user_ID']){
		$txt = $empresaController->editarEmpresa();
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