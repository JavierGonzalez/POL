<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 





// HERRAMIENTA DE DESARROLLO PARA VERIFICAR EL IMPORTANTE NUCLEO DE ACCESO.



function test_acceso($tipo, $valor='') {
	return '<tr><td>'.(nucleo_acceso($tipo, $valor)?'<b style="color:blue;">true</b>':'<b style="color:red;">false</b>').'</td><td nowrap> = nucleo_acceso(\''.$tipo.'\', \''.$valor.'\');</td><td>'.ucfirst(verbalizar_acceso($tipo, $valor)).'</td><td>'.sql_acceso($tipo, $valor).'</td></tr>';
}

echo '<h1>Test nucleo_acceso()</h1><table border="0">
<tr>
<th>¿Acceso?</th>
<th>Peticion</th>
<th>Verbalizacion</th>
</tr>
';

echo test_acceso('', '');
echo test_acceso('', '');
echo test_acceso('asasf', 'sad');
echo test_acceso('privado', true);
echo test_acceso('privado', '0');
echo test_acceso('privado', 0);
echo test_acceso('privado', 1);
echo test_acceso('privado', -1);
echo test_acceso('privado', '');
echo test_acceso('privado|gonzo ok');
echo test_acceso('privado', null);
echo test_acceso('privado', false);
echo test_acceso('privado', 'gonzo');
echo test_acceso('privado', '     gon');
echo test_acceso('privado', 'otro gonzoto');
echo test_acceso('excluir', 'gonzo otro');
echo test_acceso('excluir', 'otro otra');
echo test_acceso('afiliado', '1');
echo test_acceso('confianza', '0');
echo test_acceso('confianza', '-1');
echo test_acceso('confianza', '20');
echo test_acceso('confianza', '2');
echo test_acceso('cargo', '1 2 3');
echo test_acceso('cargo', '6 7');
echo test_acceso('grupos', '1 2 3');
echo test_acceso('grupos', '1');
echo test_acceso('examenes', '47 48 49');
echo test_acceso('examenes', '999 998');
echo test_acceso('nivel', '0');
echo test_acceso('nivel', '1');
echo test_acceso('nivel', '100');
echo test_acceso('antiguedad', '1');
echo test_acceso('antiguedad', '360');
echo test_acceso('antiguedad', '100000');
echo test_acceso('autentificados', '');
echo test_acceso('supervisores_censo', '');
echo test_acceso('ciudadanos', '');
echo test_acceso('ciudadanos', 'VP 15M');
echo test_acceso('ciudadanos_global', '');
echo test_acceso('anonimos', '');
echo '</table>';


?>