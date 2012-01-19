<?php 
include('inc-login.php');


// HERRAMIENTA DE DESARROLLO PARA VERIFICAR EL IMPORTANTE NUCLEO DE ACCESO.



function test_acceso($tipo, $valor) {
	return (nucleo_acceso($tipo, $valor)?'<b style="color:blue;">true</b>':'<b style="color:red;">false</b>').' = nucleo_acceso(\''.$tipo.'\', \''.$valor.'\');<br />';
}

$txt .= '<h1>Test nucleo_acceso()</h1><hr />';
$txt .= test_acceso('privado', 'gonzo');
$txt .= test_acceso('privado', 'otro gonzoto');
$txt .= test_acceso('excluir', 'gonzo otro');
$txt .= test_acceso('excluir', 'otro otra');
$txt .= test_acceso('afiliado', '1');
$txt .= test_acceso('confianza', '0');
$txt .= test_acceso('confianza', '-1');
$txt .= test_acceso('confianza', '20');
$txt .= test_acceso('confianza', '2');
$txt .= test_acceso('cargo', '1 2 3');
$txt .= test_acceso('grupos', '1 2 3');
$txt .= test_acceso('grupos', '1');
$txt .= test_acceso('nivel', '0');
$txt .= test_acceso('nivel', '1');
$txt .= test_acceso('nivel', '100');
$txt .= test_acceso('antiguedad', '1');
$txt .= test_acceso('antiguedad', '360');
$txt .= test_acceso('antiguedad', '100000');
$txt .= test_acceso('antentificados', '');
$txt .= test_acceso('supervisores_censo', '');
$txt .= test_acceso('ciudadanos', '');
$txt .= test_acceso('ciudadanos', 'VP 15M');
$txt .= test_acceso('ciudadanos_global', '');
$txt .= test_acceso('anonimos', '');

include('theme.php');
?>