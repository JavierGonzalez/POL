<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


if ($_POST['nick'] == $pol['nick']) { 
	$result3 = sql_old("SELECT IP, pols, nick, ID, ref, estado,
    ".(ECONOMIA?"(SELECT SUM(pols) FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$pol['user_ID']."')":"estado")." AS pols_cuentas 
    FROM users 
    WHERE ID = '".$pol['user_ID']."' AND estado = 'ciudadano' AND pais = '".PAIS."'
    LIMIT 1");
	while($r3 = r($result3)) {
		$user_ID = $r3['ID']; 
		$estado = $r3['estado']; 
		$pols = ($r3['pols'] + $r3['pols_cuentas']); 
		$nick = $r3['nick']; 
		$ref = $r3['ref']; 
		$IP = $r3['IP'];
	}
    evento_log('Eliminación de usuario ('.$nick.') permanente y voluntaria.');
    pols_transferir($pols, $user_ID, '-1', 'Eliminación de usuario ');
    
    sql_old("DELETE FROM empresas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
    sql_old("DELETE FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
    sql_old("DELETE FROM mapa WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
    sql_old("DELETE FROM pujas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");

    sql_old("DELETE FROM cargos_users WHERE user_ID = '".$user_ID."'");
    sql_old("DELETE FROM partidos_listas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
    sql_old("DELETE FROM partidos WHERE pais = '".PAIS."' AND ID_presidente = '".$user_ID."'");


    sql_old("UPDATE users SET estado = 'expulsado', pais = 'ninguno', nivel = '1', cargo = '0', cargos = '', examenes = '', nota = '0.0', pols = '0.0', rechazo_last = '".$date."' WHERE ID = '".$pol['user_ID']."'");
}
redirect('/');