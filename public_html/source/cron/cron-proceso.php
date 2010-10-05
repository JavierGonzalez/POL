<?php 

$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';


// MICROTIME ON
$mtime = explode(' ', microtime()); 
$tiempoinicial = $mtime[1] + $mtime[0]; 


include($root_dir.'config.php');
include($root_dir.'source/inc-functions.php');
include($root_dir.'source/inc-functions-accion.php');
$link = conectar();

// INICIO PROCESO
evento_chat('<b>[PROCESO] Inicio del proceso diario...</b>');

// TIME MARGEN
$date			= date('Y-m-d 20:00:00'); 					// ahora
$margen_24h		= date('Y-m-d 20:00:00', time() - 86400);	// 24 h
$margen_2dias	= date('Y-m-d 20:00:00', time() - 172800);	// 2 dias
$margen_5dias	= date('Y-m-d 20:00:00', time() - 432000);	// 5 dias
$margen_10dias	= date('Y-m-d 20:00:00', time() - 864000);	// 10 dias
$margen_15dias	= date('Y-m-d 20:00:00', time() - 1296000); // 15 dias
$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); // 30 dias
$margen_60dias	= date('Y-m-d 20:00:00', time() - 5184000); // 60 dias
$margen_90dias	= date('Y-m-d 20:00:00', time() - 7776000); // 90 dias


// LOAD CONFIG
$result = mysql_query("SELECT valor, dato FROM ".SQL."config", $link);
while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }




// examenes_exp
$examenes_exp_num = 0;
$result = mysql_query("SELECT ID, time, cargo 
FROM ".SQL."estudios_users 
WHERE cargo = '0' AND time < '".date('Y-m-d 20:00:00', time() - $pol['config']['examenes_exp'])."'
ORDER BY time DESC", $link);
while($row = mysql_fetch_array($result)){
	$examenes_exp_num++;
	mysql_query("DELETE FROM ".SQL."estudios_users WHERE ID = '".$row['ID']."'", $link);
}
evento_chat('<b>[PROCESO]</b> Expirados <b>'.$examenes_exp_num.'</b> examenes.');

// REFERENCIAS 
$result = mysql_query("SELECT ID, user_ID, new_user_ID,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL_REFERENCIAS.".user_ID LIMIT 1) AS nick,
(SELECT pais FROM ".SQL_USERS." WHERE ID = ".SQL_REFERENCIAS.".user_ID LIMIT 1) AS nick_pais,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL_REFERENCIAS.".new_user_ID LIMIT 1) AS new_nick,
(SELECT online FROM ".SQL_USERS." WHERE ID = ".SQL_REFERENCIAS.".new_user_ID LIMIT 1) AS online
FROM ".SQL_REFERENCIAS." 
WHERE new_user_ID != '0' AND pagado = '0'", $link);
while($row = mysql_fetch_array($result)){ 
	$txt .= $row['nick'].' - '.$row['new_nick'].' - '.$pol['config']['pols_afiliacion'].'<br />';
	if (($row['online'] >= $pol['config']['online_ref']) AND ($row['nick_pais'] == PAIS)) {
		evento_chat('<b>[PROCESO] Referencia exitosa</b>, nuevo Ciudadano '.crear_link($row['new_nick']).', '.crear_link($row['nick']).' gana <em>'.pols($pol['config']['pols_afiliacion']).' '.MONEDA.'</em>');
		pols_transferir($pol['config']['pols_afiliacion'], '-1', $row['user_ID'], 'Referencia: '.$row['new_nick']);
		mysql_query("UPDATE ".SQL_REFERENCIAS." SET pagado = '1' WHERE ID = '".$row['ID']."' LIMIT 1", $link);
		mysql_query("UPDATE ".SQL_USERS." SET ref_num = ref_num + 1 WHERE ID = '".$row['user_ID']."' LIMIT 1", $link);
	}
}
mysql_query("DELETE FROM ".SQL_REFERENCIAS." WHERE time < '".$margen_30dias."'", $link);


// SALARIOS
$result = mysql_query("SELECT user_ID,
(SELECT salario FROM ".SQL."estudios WHERE  ID = ".SQL."estudios_users.ID_estudio AND asigna != '-1' LIMIT 1) AS salario
FROM ".SQL."estudios_users
WHERE cargo = '1'
ORDER BY user_ID ASC", $link);
while($row = mysql_fetch_array($result)){ if ($salarios[$row['user_ID']] < $row['salario']) { $salarios[$row['user_ID']] = $row['salario']; } }

$result = mysql_query("SELECT pols FROM ".SQL."cuentas WHERE ID = 1 LIMIT 1", $link);
while($row = mysql_fetch_array($result)) {
	$pols_gobierno = $row['pols'];
}

$gasto_total = 0;
foreach($salarios as $user_ID => $salario) {
	$result = mysql_query("SELECT ID
FROM ".SQL_USERS."
WHERE ID = '".$user_ID."' AND estado = 'ciudadano' AND fecha_last > '".$margen_24h."' AND pais = '".PAIS."'
LIMIT 1", $link);
	while($row = mysql_fetch_array($result)){
		$txt .= $user_ID. ' - '.$salario."<br />\n";
		$gasto_total += $salario;
		$tiene_sueldo[$user_ID] = 'ok';
		pols_transferir($salario, '-1', $user_ID, 'Salario');
	}
}
evento_chat('<b>[PROCESO] Sueldos efectuados.</b> Gasto: <em>'.pols($gasto_total).' '.MONEDA.'</em>');

$result = mysql_query("SELECT pols FROM ".SQL."cuentas WHERE ID = '1' LIMIT 1", $link);
while($row = mysql_fetch_array($result)) {
	$pols_gobierno2 = $row['pols'];
}

if ($pols_gobierno - $gasto_total != $pols_gobierno2) {
	mysql_query("UPDATE ".SQL."cuentas SET pols = pols - ".$gasto_total." WHERE ID = '1' LIMIT 1", $link);
	$pols_gobierno -= $gasto_total;
	evento_chat('<b>[PROCESO] Correcci&oacute;n efectuada.</b> Descontado el gasto en salarios. El error era de <em>'. pols($pols_gobierno2 - $pols_gobierno).' '.MONEDA.'</em>');
}
else {
	$pols_gobierno = $pols_gobierno2;
}

// INEMPOL
$salario_inempol = $pol['config']['pols_inem'];
$gasto_total = 0;
if ($salario_inempol > 0) {
	$result = mysql_query("SELECT ID FROM ".SQL_USERS." WHERE fecha_last > '".$margen_24h."' AND estado = 'ciudadano' AND pais = '".PAIS."'", $link);
	while($row = mysql_fetch_array($result)){ 
		if ($tiene_sueldo[$row['ID']] != 'ok') {
			$gasto_total += $salario_inempol;
			pols_transferir($salario_inempol, '-1', $row['ID'], 'INEMPOL');
		}
	}
}
evento_chat('<b>[PROCESO] INEMPOL efectuado.</b> Gasto: <em>'.pols($gasto_total).' '.MONEDA.'</em>');

$result = mysql_query("SELECT pols FROM ".SQL."cuentas WHERE ID = '1' LIMIT 1", $link);
while($row = mysql_fetch_array($result)) {
	$pols_gobierno2 = $row['pols'];
}

if ($pols_gobierno - $gasto_total != $pols_gobierno2) {
	mysql_query("UPDATE ".SQL."cuentas SET pols = pols - ".$gasto_total." WHERE ID = '1' LIMIT 1", $link);
	evento_chat('<b>[PROCESO] Correcci&oacute;n efectuada.</b> Descontado el gasto en INEMPOL. El error era de <em>'. pols($pols_gobierno2 - $pols_gobierno + $gasto_total).' '.MONEDA.'</em>');
}

//enviar_email(null, "[POL] CRON 24h - Sueldos ejecutados", "Sueldos<br /><br />".$txt, "gonzomail@gmail.com");

sleep(1);

// SUBASTA: LA FRASE
$result = mysql_query("SELECT pols, user_ID,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick,
(SELECT pols FROM ".SQL_USERS." WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick_pols
FROM ".SQL."pujas 
WHERE mercado_ID = '1'
ORDER BY pols DESC LIMIT 1", $link);
while($row = mysql_fetch_array($result)){
	mysql_query("DELETE FROM ".SQL."pujas WHERE mercado_ID = '1'", $link); //resetea pujas
	evento_chat('<b>[PROCESO]</b> Subasta: <b>La frase</b>, de <em>'.crear_link($row['nick']).'</em> por '.pols($row['pols']).' '.MONEDA.'');
	$pujas_total = $row['pols'];
	pols_transferir($row['pols'], $row['user_ID'], '-1', 'Subasta: <em>La frase</em>');
	mysql_query("UPDATE ".SQL."config SET valor = '".$row['user_ID']."' WHERE dato = 'pols_fraseedit' LIMIT 1", $link);
	mysql_query("UPDATE ".SQL."config SET valor = '".$row['nick']."' WHERE dato = 'pols_frase' LIMIT 1", $link);
}

// SUBASTA: LAS PALABRAS
$gan = $pol['config']['palabras_num'];
$g = 1;
$las_palabras = '';
$result = mysql_query("SELECT user_ID, MAX(pols) AS los_pols,
(SELECT nick FROM ".SQL_USERS." WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick,
(SELECT pols FROM ".SQL_USERS." WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick_pols
FROM ".SQL."pujas
WHERE mercado_ID = 2
GROUP BY user_ID
ORDER BY los_pols DESC", $link);
while($row = mysql_fetch_array($result)) {
	if ($g <= $gan) {
		if ($las_palabras) { $las_palabras .= ';'; }
		$las_palabras .= $row['user_ID'].'::'.$row['nick'];
		evento_chat('<b>[PROCESO]</b> Subasta: <b>Palabra'.$g.'</b>, de <em>'.crear_link($row['nick']).'</em> por '.pols($row['los_pols']).' '.MONEDA.'');
		pols_transferir($row['los_pols'], $row['user_ID'], '-1', 'Subasta: Palabra'.$g);
		$pujas_total += $row['los_pols'];
		$g++;
	}
}
mysql_query("DELETE FROM ".SQL."pujas WHERE mercado_ID = '2'", $link); //resetea pujas
mysql_query("UPDATE ".SQL."config SET valor = '".$las_palabras."' WHERE dato = 'palabras' LIMIT 1", $link);



// COSTE PROPIEDADES
$p['user_ID'] = 1;
$recaudado_propiedades = 0;
$result = mysql_query("SELECT ID, size_x, size_y, user_ID, estado, superficie,
(SELECT pols FROM ".SQL_USERS." WHERE ID = ".SQL."mapa.user_ID LIMIT 1) AS pols_total
FROM ".SQL."mapa 
WHERE user_ID != '0' AND estado != 'e'
ORDER BY user_ID ASC, size_x DESC, size_y DESC", $link);
while($row = mysql_fetch_array($result)){ 
	if ($p['user_ID'] != $row['user_ID']) { 
		//ejecuta ciudadano
		if ($p['pols_total'] >= $p['pols']) {
			pols_transferir($p['pols'], $p['user_ID'], '-1', 'CP');
			$recaudado_propiedades += $p['pols']; 
		} else {
			foreach($p['prop'] as $unID => $uncoste) {
				mysql_query("DELETE FROM ".SQL."mapa WHERE ID = '".$unID."' AND user_ID = '".$p['user_ID']."' LIMIT 1", $link);
			}
		}
		$p = '';
		$p['user_ID'] = $row['user_ID'];
	}
	$coste = ceil(($row['size_x'] * $row['size_y']) * $pol['config']['factor_propiedad']);
	$p['pols'] += $coste;
	$p['pols_total'] = $row['pols_total'];
	$p['prop'][$row['ID']] = $coste;
}
//ejecuta ultimo ciudadano
if ($p['pols_total'] >= $p['pols']) {
	pols_transferir($p['pols'], $p['user_ID'], '-1', 'CP');
	$recaudado_propiedades += $p['pols']; 
} else {
	foreach($p['prop'] as $unID => $uncoste) {
		mysql_query("DELETE FROM ".SQL."mapa WHERE ID = '".$unID."' AND user_ID = '".$p['user_ID']."' LIMIT 1", $link);
	}
}

evento_chat('<b>[PROCESO] Coste de propiedades efectuado,</b> recaudado: '.pols($recaudado_propiedades).' '.MONEDA);


// NOTAS MEDIA
$result = mysql_query("SELECT user_ID, AVG(nota) AS media FROM ".SQL."estudios_users GROUP BY user_ID", $link);
while($row = mysql_fetch_array($result)){ 
	if ($row['media']) { mysql_query("UPDATE ".SQL_USERS." SET nota = '".$row['media']."' WHERE ID = '".round($row['user_ID'], 1)."' LIMIT 1", $link); }
}
evento_chat('<b>[PROCESO] Calculadas las notas media.</b>');


// ELIMINAR CHAT INACTIVOS TRAS N DIAS
/*$margen_chatexpira = date('Y-m-d 20:00:00', time() - (86400 * $pol['config']['chat_diasexpira']));
mysql_query("DELETE FROM chats WHERE pais = '".PAIS."' AND fecha_last < '".$margen_chatexpira."'", $link);
*/
// ELIMINAR MENSAJES PRIVADOS
mysql_query("DELETE FROM ".SQL_MENSAJES." WHERE time < '".$margen_15dias."'", $link);

// ELIMINAR TRANSACCIONES ANTIGUAS
mysql_query("DELETE FROM ".SQL."transacciones WHERE time < '".$margen_60dias."'", $link);

// ELIMINAR LOG EVENTOS
mysql_query("DELETE FROM ".SQL."log WHERE time < '".$margen_90dias."'", $link);

// ELIMINAR votos resuduales
mysql_query("DELETE FROM ".SQL_VOTOS." WHERE estado = 'confianza' AND voto = '0'", $link);

// ELIMINAR bans antiguos
mysql_query("DELETE FROM ".SQL."ban WHERE (estado = 'inactivo' OR estado = 'cancelado') AND expire < '".$margen_60dias."'", $link);

// ELIMINAR hilos BASURA
mysql_query("DELETE FROM ".SQL."foros_hilos WHERE estado = 'borrado' AND time_last < '".$margen_10dias."'", $link);

// ELIMINAR mensajes BASURA
mysql_query("DELETE FROM ".SQL."foros_msg WHERE estado = 'borrado' AND time2 < '".$margen_10dias."'", $link);

// ELIMINAR examenes antiguos
//mysql_query("DELETE FROM ".SQL."estudios_users WHERE cargo = '0' AND time < '".$margen_60dias."'", $link);

// ELIMINAR privilegios de desarrollador (nadie deberia tenerlo de forma continua, así queda para uso ocasional de mantenimiento)
//mysql_query("UPDATE ".SQL_USERS." SET estado = 'ciudadano' WHERE estado = 'desarrollador'", $link);



// ELIMINAR USUARIOS  (15 dias)
/*
	< 30d	- 10 dias
30d < 90d	- 30 dias 
90d >		- 60 dias
*/

$st['eliminados'] = 0;
$result = mysql_query("SELECT ID, nick, fecha_registro, fecha_last FROM ".SQL_USERS."
WHERE 
((pais = 'ninguno' OR pais = '".PAIS."') AND fecha_registro <= '".$margen_90dias."' AND fecha_last <= '".$margen_60dias."') OR
((pais = 'ninguno' OR pais = '".PAIS."') AND fecha_registro > '".$margen_90dias."' AND fecha_registro <= '".$margen_30dias."' AND fecha_last <= '".$margen_30dias."') OR
((pais = 'ninguno' OR pais = '".PAIS."') AND fecha_registro > '".$margen_30dias."' AND fecha_last <= '".$margen_10dias."') OR
((pais = 'ninguno' OR pais = '".PAIS."') AND estado = 'expulsado' AND fecha_last <= '".$margen_10dias."') OR
(estado = 'validar' AND fecha_last <= '".$margen_10dias."')
", $link);
while($row = mysql_fetch_array($result)) {
	$st['eliminados']++;
	eliminar_ciudadano($row['ID']);
}




// IMPUESTO PATRIMONIO
if ($pol['config']['impuestos'] > 0) {	
	$minimo = $pol['config']['impuestos_minimo'];
	$porcentaje = $pol['config']['impuestos'];

	$result = mysql_query("SELECT ID, nick, pols, estado,
(SELECT SUM(pols) FROM ".SQL."cuentas WHERE user_ID = ".SQL_USERS.".ID AND nivel = '0' AND exenta_impuestos = '0' GROUP BY user_ID) AS pols_cuentas
FROM ".SQL_USERS." WHERE pais = '".PAIS."' AND estado != 'desarrollador'
ORDER BY fecha_registro ASC", $link);
	while($row = mysql_fetch_array($result)) { 
		$pols_total = ($row['pols'] + $row['pols_cuentas']);

		if ($pols_total >= $minimo) { // REGLAS
			$impuesto = floor( ( $pols_total * $porcentaje) / 100);
			$redaudacion += $impuesto;
		} else { $impuesto = 0; $num_porcentaje_0++; }


		// TRANSFERIR
		if ($impuesto > 0) {
			$resto_impuestos = $impuesto;

			if ($row['pols'] < 0) {
				$pols_total = $row['pols_cuentas'];
			}

			$result2 = mysql_query("SELECT ID, pols FROM ".SQL."cuentas WHERE user_ID = '".$row['ID']."' AND nivel = '0' AND exenta_impuestos = '0'", $link);
			while($row2 = mysql_fetch_array($result2)) {
				$proporcion_cuenta = $row2['pols']/$pols_total;
				$impuesto_cuenta = floor($proporcion_cuenta * $impuesto);
				pols_transferir($impuesto_cuenta, '-'.$row2['ID'], '-1', 'IMPUESTO '.date('Y-m-d').': '.$pol['config']['impuestos'].'%');
				$resto_impuestos -= $impuesto_cuenta;
			}

			if ($row['pols'] >= $resto_impuestos) {
				pols_transferir($resto_impuestos, $row['ID'], '-1', 'IMPUESTO '.date('Y-m-d').': '.$pol['config']['impuestos'].'%');	
				$resto_impuestos = 0;
			}

			if ($resto_impuestos > 0) {
				$result2 = mysql_query("SELECT ID FROM ".SQL."cuentas WHERE user_ID = '".$row['ID']."' AND nivel = '0' AND exenta_impuestos = '0' ORDER BY pols DESC LIMIT 1", $link);
				while($row2 = mysql_fetch_array($result2)) { 
					pols_transferir($resto_impuestos, '-'.$row2['ID'], '-1', 'IMPUESTO '.date('Y-m-d').': '.$pol['config']['impuestos'].'%. Ajuste por redondeos.');
				}
			}
		}
	}
	evento_chat('<b>[PROCESO] IMPUESTO PATRIMONIO '.date('Y-m-d').'</b>, recaudado: '.pols($redaudacion).' '.MONEDA);
}




// IMPUESTO EMPRESA
if ($pol['config']['impuestos_empresa'] > 0) {	
	$result = mysql_query("SELECT COUNT(ID) AS num, user_ID FROM ".SQL."empresas GROUP BY user_ID ORDER BY num DESC", $link);
	while($row = mysql_fetch_array($result)) { 

		// comprueba si existe el propietario de la empresa antes de ejecutar el impuesto
		$result2 = mysql_query("SELECT ID, pols FROM ".SQL_USERS." WHERE ID = '".$row['user_ID']."' AND pais = '".PAIS."' LIMIT 1", $link);
		while($row2 = mysql_fetch_array($result2)) { 
			$impuesto = round($pol['config']['impuestos_empresa'] * $row['num']);
			if ($row2['pols'] >= $impuesto) {
				$recaudacion_empresas += $impuesto;

				pols_transferir($impuesto, $row['user_ID'], '-1', 'IMPUESTO EMPRESAS '.date('Y-m-d').': '.$row['num'].' empresas');	
			} 
			else {
				$result3 = mysql_query("SELECT ID, pols FROM ".SQL."cuentas WHERE user_ID = '".$row['user_ID']."' AND nivel = '0' ORDER BY pols DESC LIMIT 1", $link);
				while($row3 = mysql_fetch_array($result3)) {
					 if ($row3['pols'] >= $impuesto) {
						$recaudacion_empresas += $impuesto;

						pols_transferir($impuesto, '-'.$row3['ID'], '-1', 'IMPUESTO EMPRESAS '.date('Y-m-d').': '.$row['num'].' empresas');	
					} 
				}
			}
		}
	}
	evento_chat('<b>[PROCESO] IMPUESTO EMPRESAS '.date('Y-m-d').'</b>, recaudado: '.pols($recaudacion_empresas).' '.MONEDA);
}








// STATS INIT

// ciudadanos
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL_USERS." WHERE estado = 'ciudadano' AND pais = '".PAIS."'", $link);
while($row = mysql_fetch_array($result)) { $st['ciudadanos'] = $row['num']; }

// nuevos
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL_USERS." WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_registro > '".$margen_24h."'", $link);
while($row = mysql_fetch_array($result)) { $st['nuevos'] = $row['num']; }
evento_chat('<b>[PROCESO]</b> Ciudadanos nuevos: <b>'.$st['nuevos'].'</b>, Ciudadanos expirados: <b>'.$st['eliminados'].'</b>. Balance: <b>'.round($st['nuevos'] - $st['eliminados']).'</b>');

// pols
$result = mysql_query("SELECT SUM(pols) AS num FROM ".SQL_USERS." WHERE pais = '".PAIS."'", $link);
while($row = mysql_fetch_array($result)) { $st['pols'] = $row['num']; }

// pols_cuentas
$result = mysql_query("SELECT SUM(pols) AS num FROM ".SQL."cuentas", $link);
while($row = mysql_fetch_array($result)) { $st['pols_cuentas'] = $row['num']; }

// transacciones
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."transacciones WHERE time > '".$margen_24h."'", $link);
while($row = mysql_fetch_array($result)) { $st['transacciones'] = $row['num']; }

// hilos+msg
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."foros_hilos WHERE time > '".$margen_24h."'", $link);
while($row = mysql_fetch_array($result)) { $st['hilos_msg'] = $row['num']; }
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."foros_msg WHERE time > '".$margen_24h."'", $link);
while($row = mysql_fetch_array($result)) { $st['hilos_msg'] = $st['hilos_msg'] + $row['num']; }

// pols_gobierno
$result = mysql_query("SELECT SUM(pols) AS num FROM ".SQL."cuentas WHERE ID = '1' OR ID = '2'", $link);
while($row = mysql_fetch_array($result)) { $st['pols_gobierno'] = $row['num']; }

// partidos
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."partidos WHERE estado = 'ok'", $link);
while($row = mysql_fetch_array($result)) { $st['partidos'] = $row['num']; }

// empresas
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."empresas", $link);
while($row = mysql_fetch_array($result)) { $st['empresas'] = $row['num']; }

// mapa
$superficie_total = 38 * 40;
$result = mysql_query("SELECT superficie, estado FROM ".SQL."mapa", $link);
while($row = mysql_fetch_array($result)) { 
	$sup_total += $row['superficie']; 
	if ($row['estado'] == 'v') { $sup_vende += $row['superficie']; }
}
$st['mapa'] = round(($sup_total * 100) / $superficie_total);
$st['mapa_vende'] = round(($sup_vende * 100) / $superficie_total);

// 24h
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL_USERS." WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_last > '".$margen_24h."'", $link);
while($row = mysql_fetch_array($result)) { $st['24h'] = $row['num']; }

// confianza
$result = mysql_query("SELECT SUM(voto) AS num FROM ".SQL_VOTOS." WHERE estado = 'confianza'", $link);
while($row = mysql_fetch_array($result)) { $st['confianza'] = $row['num']; }


// STATS NUEVO
mysql_query("INSERT INTO stats 
(pais, time, ciudadanos, nuevos, pols, pols_cuentas, transacciones, hilos_msg, pols_gobierno, partidos, frase, empresas, eliminados, mapa, mapa_vende, 24h, confianza) 
VALUES ('".PAIS."', '".date('Y-m-d 20:00:00')."', '".$st['ciudadanos']."', '".$st['nuevos']."', '".$st['pols']."', '".$st['pols_cuentas']."', '".$st['transacciones']."', '".$st['hilos_msg']."', '".$st['pols_gobierno']."', '".$st['partidos']."', '".$pujas_total."', '".$st['empresas']."', '".$st['eliminados']."', '".$st['mapa']."', '".$st['mapa_vende']."', '".$st['24h']."', '".$st['confianza']."')", $link);



// ¿ELECCIONES?
include($root_dir.'source/cron/cron-elecciones.php');


// MICROTIME OFF
$mtime = explode(' ', microtime()); 
$tiempofinal = $mtime[1] + $mtime[0]; 
$tiempototal = number_format($tiempofinal - $tiempoinicial, 3); 



evento_chat('<b>[PROCESO] FIN del proceso</b>, todo <span style="color:blue;"><b>OK</b></span>, '.$tiempototal.'s (<a href="/info/estadisticas/">estadisticas actualizadas</a>)');

mysql_close($link);

?>
