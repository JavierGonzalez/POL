<?php 

$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';


// MICROTIME ON
$mtime = explode(' ', microtime()); 
$tiempoinicial = $mtime[1] + $mtime[0]; 


include($root_dir.'config.php');
include($root_dir.'source/inc-functions.php');
include($root_dir.'source/inc-functions-accion.php');
$link = conectar();





// PROTECCION DE DOBLE EJECUCION. Evita que se ejcute el proceso mas de una vez en un mismo dia.
$result = mysql_query("SELECT pais FROM stats WHERE pais = '".PAIS."' AND time = '".date('Y-m-d 20:00:00')."' LIMIT 1", $link);
while($r = mysql_fetch_array($result)) { echo 'Ya se ha ejecutado hoy'; exit; }




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


// LOAD CONFIG $pol['config'][]
$result = mysql_query("SELECT valor, dato FROM ".SQL."config", $link);
while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }


// EXPIRACION DE EXAMENES
$examenes_exp_num = 0;
$result = mysql_query("SELECT ID, time, cargo 
FROM ".SQL."estudios_users 
WHERE cargo = '0' AND time < '".date('Y-m-d 20:00:00', time() - $pol['config']['examenes_exp'])."'
ORDER BY time DESC", $link);
while($r = mysql_fetch_array($result)){
	$examenes_exp_num++;
	mysql_query("DELETE FROM ".SQL."estudios_users WHERE ID = '".$r['ID']."'", $link);
}
//evento_chat('<b>[PROCESO]</b> Expirados <b>'.$examenes_exp_num.'</b> examenes.');


if (ECONOMIA) {

// REFERENCIAS 
$result = mysql_query("SELECT ID, user_ID, new_user_ID,
(SELECT nick FROM users WHERE ID = ".SQL_REFERENCIAS.".user_ID LIMIT 1) AS nick,
(SELECT pais FROM users WHERE ID = ".SQL_REFERENCIAS.".user_ID LIMIT 1) AS nick_pais,
(SELECT nick FROM users WHERE ID = ".SQL_REFERENCIAS.".new_user_ID LIMIT 1) AS new_nick,
(SELECT online FROM users WHERE ID = ".SQL_REFERENCIAS.".new_user_ID LIMIT 1) AS online
FROM ".SQL_REFERENCIAS." 
WHERE new_user_ID != '0' AND pagado = '0'", $link);
while($r = mysql_fetch_array($result)){ 
	$txt .= $r['nick'].' - '.$r['new_nick'].' - '.$pol['config']['pols_afiliacion'].'<br />';
	if (($r['online'] >= $pol['config']['online_ref']) AND ($r['nick_pais'] == PAIS)) {
		evento_chat('<b>[PROCESO] Referencia exitosa</b>, nuevo Ciudadano '.crear_link($r['new_nick']).', '.crear_link($r['nick']).' gana <em>'.pols($pol['config']['pols_afiliacion']).' '.MONEDA.'</em>');
		pols_transferir($pol['config']['pols_afiliacion'], '-1', $r['user_ID'], 'Referencia: '.$r['new_nick']);
		mysql_query("UPDATE ".SQL_REFERENCIAS." SET pagado = '1' WHERE ID = '".$r['ID']."' LIMIT 1", $link);
		mysql_query("UPDATE users SET ref_num = ref_num + 1 WHERE ID = '".$r['user_ID']."' LIMIT 1", $link);
	}
}
mysql_query("DELETE FROM ".SQL_REFERENCIAS." WHERE time < '".$margen_30dias."'", $link);


// SALARIOS
$result = mysql_query("SELECT user_ID,
(SELECT salario FROM ".SQL."estudios WHERE  ID = ".SQL."estudios_users.ID_estudio AND asigna != '-1' LIMIT 1) AS salario
FROM ".SQL."estudios_users
WHERE cargo = '1'
ORDER BY user_ID ASC", $link);
while($r = mysql_fetch_array($result)){ if ($salarios[$r['user_ID']] < $r['salario']) { $salarios[$r['user_ID']] = $r['salario']; } }
$result = mysql_query("SELECT pols FROM ".SQL."cuentas WHERE ID = 1 LIMIT 1", $link);
while($r = mysql_fetch_array($result)) { $pols_gobierno = $r['pols']; }
$gasto_total = 0;
foreach($salarios as $user_ID => $salario) {
	$result = mysql_query("SELECT ID
FROM users
WHERE ID = '".$user_ID."' AND fecha_last > '".$margen_24h."' AND pais = '".PAIS."'
LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){
		$txt .= $user_ID. ' - '.$salario."<br />\n";
		$gasto_total += $salario;
		$tiene_sueldo[$user_ID] = 'ok';
		pols_transferir($salario, '-1', $user_ID, 'Salario');
	}
}
evento_chat('<b>[PROCESO] Sueldos efectuados.</b> Gasto: <em>'.pols($gasto_total).' '.MONEDA.'</em>');

$result = mysql_query("SELECT pols FROM ".SQL."cuentas WHERE ID = '1' LIMIT 1", $link);
while($r = mysql_fetch_array($result)) {
	$pols_gobierno2 = $r['pols'];
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
	$result = mysql_query("SELECT ID FROM users WHERE fecha_last > '".$margen_24h."' AND pais = '".PAIS."'", $link);
	while($r = mysql_fetch_array($result)){ 
		if ($tiene_sueldo[$r['ID']] != 'ok') {
			$gasto_total += $salario_inempol;
			pols_transferir($salario_inempol, '-1', $r['ID'], 'INEMPOL');
		}
	}
}
evento_chat('<b>[PROCESO] INEMPOL efectuado.</b> Gasto: <em>'.pols($gasto_total).' '.MONEDA.'</em>');

$result = mysql_query("SELECT pols FROM ".SQL."cuentas WHERE ID = '1' LIMIT 1", $link);
while($r = mysql_fetch_array($result)) {
	$pols_gobierno2 = $r['pols'];
}

if ($pols_gobierno - $gasto_total != $pols_gobierno2) {
	mysql_query("UPDATE ".SQL."cuentas SET pols = pols - ".$gasto_total." WHERE ID = '1' LIMIT 1", $link);
	evento_chat('<b>[PROCESO] Correcci&oacute;n efectuada.</b> Descontado el gasto en INEMPOL. El error era de <em>'. pols($pols_gobierno2 - $pols_gobierno + $gasto_total).' '.MONEDA.'</em>');
}



// SUBASTA: LA FRASE
$result = mysql_query("SELECT pols, user_ID,
(SELECT nick FROM users WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick,
(SELECT pols FROM users WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick_pols
FROM ".SQL."pujas 
WHERE mercado_ID = '1'
ORDER BY pols DESC LIMIT 1", $link);
while($r = mysql_fetch_array($result)){
	mysql_query("DELETE FROM ".SQL."pujas WHERE mercado_ID = '1'", $link); //resetea pujas
	evento_chat('<b>[PROCESO]</b> Subasta: <b>La frase</b>, de <em>'.crear_link($r['nick']).'</em> por '.pols($r['pols']).' '.MONEDA.'');
	$pujas_total = $r['pols'];
	pols_transferir($r['pols'], $r['user_ID'], '-1', 'Subasta: <em>La frase</em>');
	mysql_query("UPDATE ".SQL."config SET valor = '".$r['user_ID']."' WHERE dato = 'pols_fraseedit' LIMIT 1", $link);
	mysql_query("UPDATE ".SQL."config SET valor = '".$r['nick']."' WHERE dato = 'pols_frase' LIMIT 1", $link);
}


// SUBASTA: LAS PALABRAS
$gan = $pol['config']['palabras_num'];
$g = 1;
$las_palabras = '';
$result = mysql_query("SELECT user_ID, MAX(pols) AS los_pols,
(SELECT nick FROM users WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick,
(SELECT pols FROM users WHERE ID = ".SQL."pujas.user_ID LIMIT 1) AS nick_pols
FROM ".SQL."pujas
WHERE mercado_ID = 2
GROUP BY user_ID
ORDER BY los_pols DESC", $link);
while($r = mysql_fetch_array($result)) {
	if ($g <= $gan) {
		if ($las_palabras) { $las_palabras .= ';'; }
		$las_palabras .= $r['user_ID'].'::'.$r['nick'];
		evento_chat('<b>[PROCESO]</b> Subasta: <b>Palabra'.$g.'</b>, de <em>'.crear_link($r['nick']).'</em> por '.pols($r['los_pols']).' '.MONEDA.'');
		pols_transferir($r['los_pols'], $r['user_ID'], '-1', 'Subasta: Palabra'.$g);
		$pujas_total += $r['los_pols'];
		$g++;
	}
}
mysql_query("DELETE FROM ".SQL."pujas WHERE mercado_ID = '2'", $link); //resetea pujas
mysql_query("UPDATE ".SQL."config SET valor = '".$las_palabras."' WHERE dato = 'palabras' LIMIT 1", $link);


// COSTE PROPIEDADES
$p['user_ID'] = 1;
$recaudado_propiedades = 0;
$result = mysql_query("SELECT ID, size_x, size_y, user_ID, estado, superficie,
(SELECT pols FROM users WHERE ID = ".SQL."mapa.user_ID LIMIT 1) AS pols_total
FROM ".SQL."mapa 
WHERE user_ID != '0' AND estado != 'e'
ORDER BY user_ID ASC, size_x DESC, size_y DESC", $link);
while($r = mysql_fetch_array($result)){ 
	if ($p['user_ID'] != $r['user_ID']) { 
		if ($p['pols_total'] >= $p['pols']) {
			pols_transferir($p['pols'], $p['user_ID'], '-1', 'CP');
			$recaudado_propiedades += $p['pols']; 
		} else {
			foreach($p['prop'] as $unID => $uncoste) {
				mysql_query("DELETE FROM ".SQL."mapa WHERE ID = '".$unID."' AND user_ID = '".$p['user_ID']."' LIMIT 1", $link);
			}
		}
		$p = '';
		$p['user_ID'] = $r['user_ID'];
	}
	$coste = ceil(($r['size_x'] * $r['size_y']) * $pol['config']['factor_propiedad']);
	$p['pols'] += $coste;
	$p['pols_total'] = $r['pols_total'];
	$p['prop'][$r['ID']] = $coste;
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







// IMPUESTO PATRIMONIO
if ($pol['config']['impuestos'] > 0) {	
	$minimo = $pol['config']['impuestos_minimo'];
	$porcentaje = $pol['config']['impuestos'];

	$result = mysql_query("SELECT ID, nick, pols, estado,
(SELECT SUM(pols) FROM ".SQL."cuentas WHERE user_ID = users.ID AND nivel = '0' AND exenta_impuestos = '0' GROUP BY user_ID) AS pols_cuentas
FROM users WHERE pais = '".PAIS."' AND fecha_registro < '".$margen_24h."'
ORDER BY fecha_registro ASC", $link);
	while($r = mysql_fetch_array($result)) { 
		$pols_total = ($r['pols'] + $r['pols_cuentas']);

		if ($pols_total >= $minimo) { // REGLAS
			$base_imponible = $pols_total;
			if ($minimo < 0) { $base_imponible -= $minimo; }
			$impuesto = ceil( ( $base_imponible * $porcentaje) / 100);
			$redaudacion += $impuesto;
		} else { $impuesto = 0; $num_porcentaje_0++; }

		// TRANSFERIR
		if ($impuesto > 0) {
			$resto_impuestos = $impuesto;

			if ($r['pols'] < 0) {
				$pols_total = $r['pols_cuentas'];
			}

			$result2 = mysql_query("SELECT ID, pols FROM ".SQL."cuentas WHERE user_ID = '".$r['ID']."' AND nivel = '0' AND exenta_impuestos = '0'", $link);
			while($r2 = mysql_fetch_array($result2)) {
				$impuesto_cuenta = ceil(($r2['pols']/$pols_total) * $impuesto);
				pols_transferir($impuesto_cuenta, '-'.$r2['ID'], '-1', 'IMPUESTO '.date('Y-m-d').': '.$pol['config']['impuestos'].'%');
				$resto_impuestos -= $impuesto_cuenta;
			}

			if ($r['pols'] >= $resto_impuestos) {
				pols_transferir($resto_impuestos, $r['ID'], '-1', 'IMPUESTO '.date('Y-m-d').': '.$pol['config']['impuestos'].'%');	
				$resto_impuestos = 0;
			}

			if ($resto_impuestos > 0) {
				$result2 = mysql_query("SELECT ID FROM ".SQL."cuentas WHERE user_ID = '".$r['ID']."' AND nivel = '0' AND exenta_impuestos = '0' ORDER BY pols DESC LIMIT 1", $link);
				while($r2 = mysql_fetch_array($result2)) { 
					pols_transferir($resto_impuestos, '-'.$r2['ID'], '-1', 'IMPUESTO '.date('Y-m-d').': '.$pol['config']['impuestos'].'%. Ajuste por redondeos.');
				}
			}
		}
	}
	evento_chat('<b>[PROCESO] IMPUESTO PATRIMONIO '.date('Y-m-d').'</b>, recaudado: '.pols($redaudacion).' '.MONEDA);
}


// IMPUESTO EMPRESA
if ($pol['config']['impuestos_empresa'] > 0) {	
	$result = mysql_query("SELECT COUNT(ID) AS num, user_ID FROM ".SQL."empresas GROUP BY user_ID ORDER BY num DESC", $link);
	while($r = mysql_fetch_array($result)) { 
		// comprueba si existe el propietario de la empresa antes de ejecutar el impuesto
		$result2 = mysql_query("SELECT ID, pols FROM users WHERE ID = '".$r['user_ID']."' AND pais = '".PAIS."' LIMIT 1", $link);
		while($r2 = mysql_fetch_array($result2)) { 
			$impuesto = round($pol['config']['impuestos_empresa'] * $r['num']);
			if ($r2['pols'] >= $impuesto) {
				$recaudacion_empresas += $impuesto;
				pols_transferir($impuesto, $r['user_ID'], '-1', 'IMPUESTO EMPRESAS '.date('Y-m-d').': '.$r['num'].' empresas');	
			} 
			else {
				$result3 = mysql_query("SELECT ID, pols FROM ".SQL."cuentas WHERE user_ID = '".$r['user_ID']."' AND nivel = '0' ORDER BY pols DESC LIMIT 1", $link);
				while($r3 = mysql_fetch_array($result3)) {
					 if ($r3['pols'] >= $impuesto) {
						$recaudacion_empresas += $impuesto;
						pols_transferir($impuesto, '-'.$r3['ID'], '-1', 'IMPUESTO EMPRESAS '.date('Y-m-d').': '.$r['num'].' empresas');	
					} 
				}
			}
		}
	}
	evento_chat('<b>[PROCESO] IMPUESTO EMPRESAS '.date('Y-m-d').'</b>, recaudado: '.pols($recaudacion_empresas).' '.MONEDA);
}



} // FIN if (ECONOMIA) {




// NOTAS MEDIA
$result = mysql_query("SELECT user_ID, AVG(nota) AS media FROM ".SQL."estudios_users GROUP BY user_ID", $link);
while($r = mysql_fetch_array($result)){ 
	if ($r['media']) { mysql_query("UPDATE users SET nota = '".$r['media']."' WHERE ID = '".round($r['user_ID'], 1)."' LIMIT 1", $link); }
}
//evento_chat('<b>[PROCESO] Calculadas las notas media.</b>');


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

// ELIMINAR bans antiguos
mysql_query("DELETE FROM ".SQL."ban WHERE (estado = 'inactivo' OR estado = 'cancelado') AND expire < '".$margen_60dias."'", $link);

// ELIMINAR hilos BASURA
mysql_query("DELETE FROM ".SQL."foros_hilos WHERE estado = 'borrado' AND time_last < '".$margen_10dias."'", $link);

// ELIMINAR mensajes BASURA
mysql_query("DELETE FROM ".SQL."foros_msg WHERE estado = 'borrado' AND time2 < '".$margen_10dias."'", $link);

// ELIMINAR examenes antiguos
//mysql_query("DELETE FROM ".SQL."estudios_users WHERE cargo = '0' AND time < '".$margen_60dias."'", $link);


// ELIMINAR USUARIOS
/* periodos:
	< 30d	- 10 dias
30d < 90d	- 30 dias 
90d >		- 60 dias
Autentificados NO expiran.
*/
$st['eliminados'] = 0;
$result = mysql_query("SELECT ID, nick, fecha_registro, fecha_last FROM users
WHERE dnie = 'false' AND 
((pais = 'ninguno' OR pais = '".PAIS."') AND fecha_registro <= '".$margen_90dias."' AND fecha_last <= '".$margen_60dias."') OR
((pais = 'ninguno' OR pais = '".PAIS."') AND fecha_registro > '".$margen_90dias."' AND fecha_registro <= '".$margen_30dias."' AND fecha_last <= '".$margen_30dias."') OR
((pais = 'ninguno' OR pais = '".PAIS."') AND fecha_registro > '".$margen_30dias."' AND fecha_last <= '".$margen_10dias."') OR
((pais = 'ninguno' OR pais = '".PAIS."') AND estado = 'expulsado' AND fecha_last <= '".$margen_10dias."') OR
(estado = 'validar' AND fecha_last <= '".$margen_10dias."')
", $link);
while($r = mysql_fetch_array($result)) {
	$st['eliminados']++;
	eliminar_ciudadano($r['ID']);
}


// Avisos por email 48h antes de la eliminación
function retrasar_t($t) { return date('Y-m-d 20:00:00', (strtotime($t)+(86400*2))); }
$result = mysql_query("SELECT ID, nick, email, fecha_registro, fecha_last FROM users
WHERE dnie = 'false' AND estado != 'expulsado' AND 
((pais = 'ninguno' OR pais = '".PAIS."') AND fecha_registro <= '".retrasar_t($margen_90dias)."' AND fecha_last <= '".retrasar_t($margen_60dias)."') OR
((pais = 'ninguno' OR pais = '".PAIS."') AND fecha_registro > '".retrasar_t($margen_90dias)."' AND fecha_registro <= '".retrasar_t($margen_30dias)."' AND fecha_last <= '".retrasar_t($margen_30dias)."') OR
((pais = 'ninguno' OR pais = '".PAIS."') AND fecha_registro > '".retrasar_t($margen_30dias)."' AND fecha_last <= '".retrasar_t($margen_10dias)."') OR
((pais = 'ninguno' OR pais = '".PAIS."') AND estado = 'expulsado' AND fecha_last <= '".retrasar_t($margen_10dias)."') OR
(estado = 'validar' AND fecha_last <= '".retrasar_t($margen_10dias)."')
", $link);
while($r = mysql_fetch_array($result)) {
	mail($r['email'], '[VirtualPol] Tu usuario '.$r['nick'].' está a punto de expirar por inactividad', "Hola ciudadano ".$r['nick'].",\n\nEl sistema VirtualPol se esmera en tener un censo limpio y fiel a la realidad, en lugar de tener decenas de miles de usuarios sin actividad. Por ello se eliminan usuarios en tramos entre 10 dias y 60 dias de inactividad.\n\nDebes entrar lo antes posible en VirtualPol o tu usuario expirará.\n\n\nVirtualPol\nhttp://www.virtualpol.com", "FROM: VirtualPol <desarrollo@virtualpol.com> \nReturn-Path: desarrollo@virtualpol.com \nX-Sender: desarrollo@virtualpol.com \nMIME-Version: 1.0\n"); 
}






// ACTUALIZACION DEL VOTO CONFIANZA
if (date('N') == 7) { // SOLO EL DOMINGO
	mysql_query("DELETE FROM votos WHERE tipo = 'confianza' AND (voto = '0' OR time < '".$margen_60dias."')", $link);
	mysql_query("UPDATE users SET voto_confianza = '0'", $link);
	$result = mysql_query("SELECT item_ID, SUM(voto) AS num_confianza FROM votos WHERE tipo = 'confianza' GROUP BY item_ID", $link);
	while ($r = mysql_fetch_array($result)) { 
		mysql_query("UPDATE users SET voto_confianza = '".$r['num_confianza']."' WHERE ID = '".$r['item_ID']."' LIMIT 1", $link);
	} 
	evento_chat('<b>[PROCESO] Supervisores del Censo:</b> '.implode(' ', get_supervisores_del_censo()));
}



// STATS (1º obtener variables estadísticas, 2º insertar los datos en la tabla stats)

// ciudadanos
$result = mysql_query("SELECT COUNT(ID) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."'", $link);
while($r = mysql_fetch_array($result)) { $st['ciudadanos'] = $r['num']; }

// nuevos
$result = mysql_query("SELECT COUNT(ID) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_registro > '".$margen_24h."'", $link);
while($r = mysql_fetch_array($result)) { $st['nuevos'] = $r['num']; }
evento_chat('<b>[PROCESO]</b> Ciudadanos nuevos: <b>'.$st['nuevos'].'</b>, Ciudadanos expirados: <b>'.$st['eliminados'].'</b>. Balance: <b>'.round($st['nuevos'] - $st['eliminados']).'</b>');

// pols
$result = mysql_query("SELECT SUM(pols) AS num FROM users WHERE pais = '".PAIS."'", $link);
while($r = mysql_fetch_array($result)) { $st['pols'] = $r['num']; }

// pols_cuentas
if (ECONOMIA) {
	$result = mysql_query("SELECT SUM(pols) AS num FROM ".SQL."cuentas", $link);
	while($r = mysql_fetch_array($result)) { $st['pols_cuentas'] = $r['num']; }

	// transacciones

	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."transacciones WHERE time > '".$margen_24h."'", $link);
	while($r = mysql_fetch_array($result)) { $st['transacciones'] = $r['num']; }
} else { $st['transacciones'] = 0; $st['pols_cuentas'] = 0; }

// hilos+msg
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."foros_hilos WHERE time > '".$margen_24h."'", $link);
while($r = mysql_fetch_array($result)) { $st['hilos_msg'] = $r['num']; }
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."foros_msg WHERE time > '".$margen_24h."'", $link);
while($r = mysql_fetch_array($result)) { $st['hilos_msg'] = $st['hilos_msg'] + $r['num']; }

// pols_gobierno
if (ECONOMIA) {
	$result = mysql_query("SELECT SUM(pols) AS num FROM ".SQL."cuentas WHERE ID = '1' OR ID = '2'", $link);
	while($r = mysql_fetch_array($result)) { $st['pols_gobierno'] = $r['num']; }
} else { $st['pols_gobierno'] = 0; }

// partidos
$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."partidos WHERE estado = 'ok'", $link);
while($r = mysql_fetch_array($result)) { $st['partidos'] = $r['num']; }

// empresas
if (ECONOMIA) {
	$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL."empresas", $link);
	while($r = mysql_fetch_array($result)) { $st['empresas'] = $r['num']; }


	// mapa (desde el 2011/04/07 guarda el porcentaje en venta.
	$superficie_total = $columnas * $filas;
	$result = mysql_query("SELECT superficie, estado FROM ".SQL."mapa", $link);
	while($r = mysql_fetch_array($result)) { 
		$sup_total += $r['superficie']; 
		if ($r['estado'] == 'v') { $sup_vende += $r['superficie']; }
	}
	$st['mapa'] = round(($sup_vende * 100) / $superficie_total);

	// mapa_vende: el precio de venta más bajo de una propiedad
	$result = mysql_query("SELECT pols FROM ".SQL."mapa WHERE estado = 'v' ORDER BY pols ASC LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) { $st['mapa_vende'] = $r['pols']; }
} else { $st['empresas'] = 0; $st['mapa'] = 0; $st['mapa_vende'] = 0; }


// 24h: ciudadanos que entraron en 24h (CONDICION NUEVA: y que no sean ciudadanos nuevos).
$result = mysql_query("SELECT COUNT(ID) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_last > '".$margen_24h."' AND fecha_registro < '".$margen_24h."'", $link);
while($r = mysql_fetch_array($result)) { $st['24h'] = $r['num']; }

// confianza
$result = mysql_query("SELECT SUM(voto) AS num FROM votos WHERE tipo = 'confianza'", $link);
while($r = mysql_fetch_array($result)) { $st['confianza'] = $r['num']; }

// autentificados
$result = mysql_query("SELECT COUNT(*) AS num FROM users WHERE dnie = 'true' AND pais = '".PAIS."'", $link);
while($r = mysql_fetch_array($result)) { $st['autentificados'] = $r['num']; }


// STATS GUARDADO DIARIO
mysql_query("INSERT INTO stats 
(pais, time, ciudadanos, nuevos, pols, pols_cuentas, transacciones, hilos_msg, pols_gobierno, partidos, frase, empresas, eliminados, mapa, mapa_vende, 24h, confianza, autentificados) 
VALUES ('".PAIS."', '".date('Y-m-d 20:00:00')."', '".$st['ciudadanos']."', '".$st['nuevos']."', '".$st['pols']."', '".$st['pols_cuentas']."', '".$st['transacciones']."', '".$st['hilos_msg']."', '".$st['pols_gobierno']."', '".$st['partidos']."', '".$pujas_total."', '".$st['empresas']."', '".$st['eliminados']."', '".$st['mapa']."', '".$st['mapa_vende']."', '".$st['24h']."', '".$st['confianza']."', '".$st['autentificados']."')", $link);


// ¿ELECCIONES?
include($root_dir.'source/cron/cron-elecciones.php');


// Calcula el tiempo de proceso
$mtime = explode(' ', microtime()); 
$tiempofinal = $mtime[1] + $mtime[0]; 
$tiempototal = number_format($tiempofinal - $tiempoinicial, 3); 

evento_chat('<b>[PROCESO] FIN del proceso</b>, todo <span style="color:blue;"><b>OK</b></span>, '.$tiempototal.'s (<a href="/estadisticas/">estadisticas actualizadas</a>)');

mysql_close($link);
?>
