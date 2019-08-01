<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


// HERRAMIENTA DE DESARROLLO PARA VERIFICAR EL IMPORTANTE NUCLEO DE ACCESO.



function test_acceso($tipo, $valor='') {
	return '<tr><td>'.(nucleo_acceso($tipo, $valor)?'<b style="color:blue;">true</b>':'<b style="color:red;">false</b>').'</td><td nowrap> = nucleo_acceso(\''.$tipo.'\', \''.$valor.'\');</td><td>'.ucfirst(verbalizar_acceso($tipo, $valor)).'</td><td>'.sql_acceso($tipo, $valor).'</td></tr>';
}

$txt .= '<h1>Test nucleo_acceso()</h1><table border="0">
<tr>
<th>¿Acceso?</th>
<th>Peticion</th>
<th>Verbalizacion</th>
</tr>
';

$txt .= test_acceso('', '');
$txt .= test_acceso('', '');
$txt .= test_acceso('asasf', 'sad');
$txt .= test_acceso('privado', true);
$txt .= test_acceso('privado', '0');
$txt .= test_acceso('privado', 0);
$txt .= test_acceso('privado', 1);
$txt .= test_acceso('privado', -1);
$txt .= test_acceso('privado', '');
$txt .= test_acceso('privado|gonzo ok');
$txt .= test_acceso('privado', null);
$txt .= test_acceso('privado', false);
$txt .= test_acceso('privado', 'gonzo');
$txt .= test_acceso('privado', '     gon');
$txt .= test_acceso('privado', 'otro gonzoto');
$txt .= test_acceso('excluir', 'gonzo otro');
$txt .= test_acceso('excluir', 'otro otra');
$txt .= test_acceso('afiliado', '1');
$txt .= test_acceso('confianza', '0');
$txt .= test_acceso('confianza', '-1');
$txt .= test_acceso('confianza', '20');
$txt .= test_acceso('confianza', '2');
$txt .= test_acceso('cargo', '1 2 3');
$txt .= test_acceso('cargo', '6 7');
$txt .= test_acceso('grupos', '1 2 3');
$txt .= test_acceso('grupos', '1');
$txt .= test_acceso('examenes', '47 48 49');
$txt .= test_acceso('examenes', '999 998');
$txt .= test_acceso('nivel', '0');
$txt .= test_acceso('nivel', '1');
$txt .= test_acceso('nivel', '100');
$txt .= test_acceso('antiguedad', '1');
$txt .= test_acceso('antiguedad', '360');
$txt .= test_acceso('antiguedad', '100000');
$txt .= test_acceso('autentificados', '');
$txt .= test_acceso('supervisores_censo', '');
$txt .= test_acceso('ciudadanos', '');
$txt .= test_acceso('ciudadanos', 'VP 15M');
$txt .= test_acceso('ciudadanos_global', '');
$txt .= test_acceso('anonimos', '');
$txt .= '</table>';

include('theme.php');
?>