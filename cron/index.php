<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


if ($_SERVER['SERVER_ADDR'] !== $_SERVER['REMOTE_ADDR'])
	exit('Solo el propio servidor puede ejecutar "el proceso".');


unset($maxsim['output']);

// PROTECCION DE DOBLE EJECUCION. Evita que se ejcute el proceso mas de una vez en un mismo dia.

$result = sql_old("SELECT pais FROM stats WHERE pais = '".PAIS."' AND time = '".date('Y-m-d 20:00:00')."' LIMIT 1");
while($r = r($result)) { echo 'Ya se ha ejecutado hoy'; exit; }


// INICIO PROCESO
evento_chat('<b>[PROCESO] Inicio del proceso diario...</b>');

// TIME MARGEN
$date			= date('Y-m-d 20:00:00'); 					// ahora


// LOAD CONFIG $pol['config'][]
$result = sql_old("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }


// EXPIRACION DE CANDIDATOS INACTIVOS
$result = sql_old("SELECT user_ID, (SELECT fecha_last FROM users WHERE ID = cargos_users.user_ID LIMIT 1) AS fecha_last FROM cargos_users WHERE pais = '".PAIS."' AND aprobado = 'ok' GROUP BY user_ID");
while ($r = r($result)) { 
	if (($r['fecha_last']) AND (strtotime($r['fecha_last']) < (time() - 60*60*24*$pol['config']['examenes_exp']))) {
		sql_old("UPDATE cargos_users SET aprobado = 'no' WHERE user_ID = '".$r['user_ID']."'");
	}
}


if (ECONOMIA) {

// REFERENCIAS 
$result = sql_old("SELECT ID, user_ID, new_user_ID,
(SELECT nick FROM users WHERE ID = referencias.user_ID LIMIT 1) AS nick,
(SELECT pais FROM users WHERE ID = referencias.user_ID LIMIT 1) AS nick_pais,
(SELECT nick FROM users WHERE ID = referencias.new_user_ID LIMIT 1) AS new_nick,
(SELECT online FROM users WHERE ID = referencias.new_user_ID LIMIT 1) AS online
FROM referencias 
WHERE new_user_ID != '0' AND pagado = '0'");
while($r = r($result)){ 
	echo $r['nick'].' - '.$r['new_nick'].' - '.$pol['config']['pols_afiliacion'].'<br />';
	if (($r['online'] >= $pol['config']['online_ref']) AND ($r['nick_pais'] == PAIS)) {
		evento_chat('<b>[PROCESO] Referencia exitosa</b>, nuevo Ciudadano '.crear_link($r['new_nick']).', '.crear_link($r['nick']).' gana <em>'.pols($pol['config']['pols_afiliacion']).' '.MONEDA.'</em>');
		pols_transferir($pol['config']['pols_afiliacion'], '-1', $r['user_ID'], 'Referencia: '.$r['new_nick']);
		sql_old("UPDATE referencias SET pagado = '1' WHERE ID = '".$r['ID']."' LIMIT 1");
		sql_old("UPDATE users SET ref_num = ref_num + 1 WHERE ID = '".$r['user_ID']."' LIMIT 1");
	}
}
sql_old("DELETE FROM referencias WHERE time < '".tiempo(30)."'");


// SALARIOS
$result = sql_old("SELECT cu.user_ID,
(SELECT MAX(salario) FROM cargos WHERE pais = 'POL' AND cargo_ID IN (select cargo_ID from cargos_users where user_ID = cu.user_ID AND cargo = 'true') AND asigna != '-1') AS max_salario,
(SELECT SUM(salario) FROM cargos WHERE pais = 'POL' AND cargo_ID IN (select cargo_ID from cargos_users where user_ID = cu.user_ID AND cargo = 'true') AND asigna != '-1') AS other_salario
FROM cargos_users cu
WHERE pais = 'POL' AND cargo = 'true'
GROUP BY user_ID 
");
while($r = r($result)){
	$salarios[$r['user_ID']] = $r['max_salario']; 
	$salarios_extra[$r['user_ID']] = $r['other_salario'] - $r['max_salario']; 
}

$result = sql_old("SELECT pols FROM cuentas WHERE pais = '".PAIS."' AND gobierno = 'true' LIMIT 1");
while($r = r($result)) { $pols_gobierno = $r['pols']; }
$gasto_total = 0;
foreach($salarios as $user_ID => $salario) {
	$result = sql_old("SELECT ID
FROM users
WHERE ID = '".$user_ID."' AND fecha_last > '".tiempo(1)."' AND pais = '".PAIS."'
LIMIT 1");
	while($r = r($result)){
		echo $user_ID. ' - '.$salario."<br />\n";
		$gasto_total += $salario;
		$tiene_sueldo[$user_ID] = 'ok';
		pols_transferir($salario, '-1', $user_ID, 'Salario');
	}
}
evento_chat('<b>[PROCESO] Sueldos efectuados.</b> Gasto: <em>'.pols($gasto_total).' '.MONEDA.'</em>');
if ($pol['config']['porcentaje_multiple_sueldo'] > 0){
	$gasto_extra = 0;
	foreach($salarios_extra as $user_ID => $salario_extra) {
		$salario_extra = ($salario_extra * $pol['config']['porcentaje_multiple_sueldo']) / 100;
		$result = sql_old("SELECT ID
	FROM users
	WHERE ID = '".$user_ID."' AND fecha_last > '".tiempo(1)."' AND pais = '".PAIS."'
	LIMIT 1");
		while($r = r($result)){
			echo $user_ID. ' - '.$salario_extra."<br />\n";
			$gasto_extra += $salario_extra;
			pols_transferir($salario_extra, '-1', $user_ID, 'Salario extra');
		}
	}
	evento_chat('<b>[PROCESO] Sueldos extras efectuados.</b> Gasto: <em>'.pols($gasto_extra).' '.MONEDA.'</em>');
	
}

$result = sql_old("SELECT pols FROM cuentas WHERE pais = '".PAIS."' AND gobierno = 'true' LIMIT 1");
while($r = r($result)) {
	$pols_gobierno2 = $r['pols'];
}

if ($pols_gobierno - $gasto_total != $pols_gobierno2) {
	sql_old("UPDATE cuentas SET pols = pols - ".$gasto_total." WHERE pais = '".PAIS."' AND gobierno = 'true' LIMIT 1");
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
	$result = sql_old("SELECT ID FROM users WHERE fecha_last > '".tiempo(1)."' AND pais = '".PAIS."'");
	while($r = r($result)){ 
		if ($tiene_sueldo[$r['ID']] != 'ok') {
			$gasto_total += $salario_inempol;
			pols_transferir($salario_inempol, '-1', $r['ID'], 'INEMPOL');
		}
	}
}
evento_chat('<b>[PROCESO] INEMPOL efectuado.</b> Gasto: <em>'.pols($gasto_total).' '.MONEDA.'</em>');

$result = sql_old("SELECT pols FROM cuentas WHERE pais = '".PAIS."' AND gobierno = 'true' LIMIT 1");
while($r = r($result)) {
	$pols_gobierno2 = $r['pols'];
}

if ($pols_gobierno - $gasto_total != $pols_gobierno2) {
	sql_old("UPDATE cuentas SET pols = pols - ".$gasto_total." WHERE pais = '".PAIS."' AND gobierno = 'true' LIMIT 1");
	evento_chat('<b>[PROCESO] Correcci&oacute;n efectuada.</b> Descontado el gasto en INEMPOL. El error era de <em>'. pols($pols_gobierno2 - $pols_gobierno + $gasto_total).' '.MONEDA.'</em>');
}



// SUBASTA: LA FRASE
$result = sql_old("SELECT pols, user_ID,
(SELECT nick FROM users WHERE ID = pujas.user_ID LIMIT 1) AS nick,
(SELECT pols FROM users WHERE ID = pujas.user_ID LIMIT 1) AS nick_pols
FROM pujas 
WHERE pais = '".PAIS."' AND mercado_ID = '1'
ORDER BY pols DESC LIMIT 1");
while($r = r($result)){
	sql_old("DELETE FROM pujas WHERE pais = '".PAIS."' AND mercado_ID = '1'"); //resetea pujas
	evento_chat('<b>[PROCESO]</b> Subasta: <b>La frase</b>, de <em>'.crear_link($r['nick']).'</em> por '.pols($r['pols']).' '.MONEDA.'');
	$pujas_total = $r['pols'];
	pols_transferir($r['pols'], $r['user_ID'], '-1', 'Subasta: <em>La frase</em>');
	sql_old("UPDATE config SET valor = '".$r['user_ID']."' WHERE pais = '".PAIS."' AND dato = 'pols_fraseedit' LIMIT 1");
	sql_old("UPDATE config SET valor = '".$r['nick']."' WHERE pais = '".PAIS."' AND dato = 'pols_frase' LIMIT 1");
}


// SUBASTA: LAS PALABRAS
$gan = $pol['config']['palabras_num'];
$g = 1;
$las_palabras = '';
$result = sql_old("SELECT user_ID, MAX(pols) AS los_pols,
(SELECT nick FROM users WHERE ID = pujas.user_ID LIMIT 1) AS nick,
(SELECT pols FROM users WHERE ID = pujas.user_ID LIMIT 1) AS nick_pols
FROM pujas
WHERE pais = '".PAIS."' AND mercado_ID = 2
GROUP BY user_ID
ORDER BY los_pols DESC");
while($r = r($result)) {
	if ($g <= $gan) {
		if ($las_palabras) { $las_palabras .= ';'; }
		$las_palabras .= $r['user_ID'].'::'.$r['nick'];
		evento_chat('<b>[PROCESO]</b> Subasta: <b>Palabra'.$g.'</b>, de <em>'.crear_link($r['nick']).'</em> por '.pols($r['los_pols']).' '.MONEDA.'');
		pols_transferir($r['los_pols'], $r['user_ID'], '-1', 'Subasta: Palabra'.$g);
		$pujas_total += $r['los_pols'];
		$g++;
	}
}
sql_old("DELETE FROM pujas WHERE pais = '".PAIS."' AND mercado_ID = '2'"); //resetea pujas
sql_old("UPDATE config SET valor = '".$las_palabras."' WHERE pais = '".PAIS."' AND dato = 'palabras' LIMIT 1");


// COSTE PROPIEDADES
$p['user_ID'] = 1;
$recaudado_propiedades = 0;
$result = sql_old("SELECT ID, size_x, size_y, user_ID, estado, superficie,
(SELECT pols FROM users WHERE ID = mapa.user_ID LIMIT 1) AS pols_total
FROM mapa 
WHERE pais = '".PAIS."' AND user_ID != '0' AND estado != 'e'
ORDER BY user_ID ASC, size_x DESC, size_y DESC");
while($r = r($result)){ 
	if ($p['user_ID'] != $r['user_ID']) { 
		if ($p['pols_total'] >= $p['pols']) {
			pols_transferir($p['pols'], $p['user_ID'], '-1', 'CP');
			$recaudado_propiedades += $p['pols']; 
		} else {
			foreach($p['prop'] as $unID => $uncoste) {
				sql_old("DELETE FROM mapa WHERE pais = '".PAIS."' AND ID = '".$unID."' AND user_ID = '".$p['user_ID']."' LIMIT 1");
			}
		}
		$p = [];
		$p['user_ID'] = $r['user_ID'];
	}
	$coste = round(($r['size_x'] * $r['size_y']) * $pol['config']['factor_propiedad'], 2);
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
		sql_old("DELETE FROM mapa WHERE pais = '".PAIS."' AND ID = '".$unID."' AND user_ID = '".$p['user_ID']."' LIMIT 1");
	}
}
evento_chat('<b>[PROCESO] Coste de propiedades efectuado,</b> recaudado: '.pols($recaudado_propiedades).' '.MONEDA);







// IMPUESTO PATRIMONIO
if ($pol['config']['impuestos'] > 0) {	
	$minimo = $pol['config']['impuestos_minimo'];
	$porcentaje = $pol['config']['impuestos'];

	$result = sql_old("SELECT ID, nick, pols, estado,
(SELECT SUM(pols) FROM cuentas WHERE pais = '".PAIS."' AND user_ID = users.ID AND nivel = '0' AND exenta_impuestos = '0' GROUP BY user_ID) AS pols_cuentas
FROM users WHERE pais = '".PAIS."' AND fecha_registro < '".tiempo(1)."'
ORDER BY fecha_registro ASC");
	while($r = r($result)) { 
		$pols_total = ($r['pols'] + $r['pols_cuentas']);

		if ($pols_total >= $minimo) { // REGLAS
			$base_imponible = $pols_total;
			if ($minimo < 0) { $base_imponible -= $minimo; }
			$impuesto = round( ( $base_imponible * $porcentaje) / 100, 2);
			$redaudacion += $impuesto;
		} else { $impuesto = 0; $num_porcentaje_0++; }

		// TRANSFERIR
		if ($impuesto > 0) {
			$resto_impuestos = $impuesto;

			if ($r['pols'] < 0) {
				$pols_total = $r['pols_cuentas'];
			}

			$result2 = sql_old("SELECT ID, pols FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$r['ID']."' AND nivel = '0' AND exenta_impuestos = '0'");
			while($r2 = r($result2)) {
				$impuesto_cuenta = round(($r2['pols']/$pols_total) * $impuesto, 2);
				pols_transferir($impuesto_cuenta, '-'.$r2['ID'], '-1', 'IMPUESTO '.date('Y-m-d').': '.$pol['config']['impuestos'].'%');
				$resto_impuestos -= $impuesto_cuenta;
			}

			if ($r['pols'] >= $resto_impuestos) {
				pols_transferir($resto_impuestos, $r['ID'], '-1', 'IMPUESTO '.date('Y-m-d').': '.$pol['config']['impuestos'].'%');	
				$resto_impuestos = 0;
			}

			if ($resto_impuestos > 0) {
				$result2 = sql_old("SELECT ID FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$r['ID']."' AND nivel = '0' AND exenta_impuestos = '0' ORDER BY pols DESC LIMIT 1");
				while($r2 = r($result2)) { 
					pols_transferir($resto_impuestos, '-'.$r2['ID'], '-1', 'IMPUESTO '.date('Y-m-d').': '.$pol['config']['impuestos'].'%. Ajuste por redondeos.');
				}
			}
		}
	}
	evento_chat('<b>[PROCESO] Impuesto patrimonio '.date('Y-m-d').'</b>, recaudado: '.pols($redaudacion).' '.MONEDA);
}


// IMPUESTO EMPRESA
if ($pol['config']['impuestos_empresa'] > 0) {	
	$result = sql_old("SELECT COUNT(ID) AS num, user_ID FROM empresas WHERE pais = '".PAIS."' GROUP BY user_ID ORDER BY num DESC");
	while($r = r($result)) { 
		// comprueba si existe el propietario de la empresa antes de ejecutar el impuesto
		$result2 = sql_old("SELECT ID, pols FROM users WHERE ID = '".$r['user_ID']."' AND pais = '".PAIS."' LIMIT 1");
		while($r2 = r($result2)) { 
			$impuesto = round($pol['config']['impuestos_empresa'] * $r['num'], 2);
			if ($r2['pols'] >= $impuesto) {
				$recaudacion_empresas += $impuesto;
				pols_transferir($impuesto, $r['user_ID'], '-1', 'IMPUESTO EMPRESAS '.date('Y-m-d').': '.$r['num'].' empresas');	
			} 
			else {
				$result3 = sql_old("SELECT ID, pols FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$r['user_ID']."' AND nivel = '0' ORDER BY pols DESC LIMIT 1");
				while($r3 = r($result3)) {
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

//Gestion transacciones automaticas
gestionar_transacciones_automaticas("D");

if (date('N') == 7) {
	gestionar_transacciones_automaticas("S");
}

if (date('j') == 1) {
	gestionar_transacciones_automaticas("M");
}

} // FIN if (ECONOMIA) {

function gestionar_transacciones_automaticas($periodicidad){
	$consulta_transacciones = mysql_query_old("SELECT pols, emisor_ID, receptor_ID, concepto FROM transacciones WHERE pais = '".PAIS."' AND periodicidad = '".$periodicidad."'", $link);
	while($row = mysqli_fetch_array($consulta_transacciones)){ 

		$pols = $row['pols']; 
		$emisor_ID = $row['emisor_ID']; 
		$receptor_ID = $row['receptor_ID']; 
		$concepto = $row['concepto'];
		$receptor_mensaje_ID = $emisor_ID;

		if ($emisor_ID >0){
			$result = mysql_query_old("SELECT pols FROM users WHERE pais = '".PAIS."' AND ID = '".$emisor_ID."'", $link);
		}else{
			$result = mysql_query_old("SELECT pols, user_ID FROM cuentas WHERE pais = '".PAIS."' AND ID = SUBSTRING('".$emisor_ID."',2)", $link);
		}

		if ($row = mysqli_fetch_array($result)){
			$disponible = $row['pols'];
			if ($emisor_ID < 0){
				$receptor_mensaje_ID = $row['user_ID'];
			}
			if ($disponible > $pols){
				pols_transferir($pols, $emisor_ID, $receptor_ID, $concepto);	
			}else{
				$date = date('Y-m-d 20:00:00'); 
				sql_old("INSERT INTO mensajes (envia_ID, recibe_ID, time, text, leido, cargo) VALUES ('0', '".$receptor_mensaje_ID."', '".$date."', 'Ocurrió un error al realizar la transferencia automática con concepto (".$concepto."), por favor revise que tenga fondos para las operaciones configuradas.', '0', '0')");
				notificacion($receptor_mensaje_ID, 'Problema con una transaccion automática', '/msg');
				evento_chat('<b>[MP]</b> <a href="/msg">Nuevo mensaje privado</a>', $receptor_mensaje_ID, -1, false, 'p', $r['pais']);
			}
		}

		
	}
	
}


// NOTAS MEDIA
$result = sql_old("SELECT user_ID, AVG(nota) AS media FROM cargos_users WHERE pais = '".PAIS."' GROUP BY user_ID");
while($r = r($result)){ 
	if ($r['media']) { sql_old("UPDATE users SET nota = '".$r['media']."' WHERE ID = '".round($r['user_ID'], 1)."' LIMIT 1"); }
}
//evento_chat('<b>[PROCESO] Calculadas las notas media.</b>');


// ELIMINAR CHAT INACTIVOS TRAS N DIAS
$margen_chatexpira = date('Y-m-d 20:00:00', time() - (86400 * $pol['config']['chat_diasexpira']));
sql_old("DELETE FROM chats WHERE pais = '".PAIS."' AND fecha_last < '".$margen_chatexpira."'");


// ELIMINAR MENSAJES PRIVADOS
sql_old("DELETE FROM mensajes WHERE time < '".tiempo(15)."'");

// ELIMINAR TRANSACCIONES ANTIGUAS
sql_old("DELETE FROM transacciones WHERE pais = '".PAIS."' AND time < '".tiempo(365)."'");

// ELIMINAR LOG EVENTOS
sql_old("DELETE FROM ".SQL."log WHERE time < '".tiempo(365)."'");

// ELIMINAR bans antiguos
sql_old("DELETE FROM kicks WHERE pais = '".PAIS."' AND (estado = 'inactivo' OR estado = 'cancelado') AND expire < '".tiempo(60)."'");

// ELIMINAR hilos BASURA
sql_old("DELETE FROM ".SQL."foros_hilos WHERE estado = 'borrado' AND time_last < '".tiempo(10)."'");

// ELIMINAR mensajes BASURA
sql_old("DELETE FROM ".SQL."foros_msg WHERE estado = 'borrado' AND time2 < '".tiempo(10)."'");

// ELIMINAR examenes antiguos
//sql_old("DELETE FROM cargos_users WHERE pais = '".PAIS."' AND cargo = 'false' AND time < '".tiempo(60)."'");

// ELIMINAR notificaciones
sql_old("DELETE FROM notificaciones WHERE time < '".tiempo(10)."'");

// ELIMINAR users_con
sql_old("DELETE FROM users_con WHERE time < '".tiempo(30)."'");

//Issue 140 - Eliminar posesiones de usuarios expulsados



$result3 = sql_old("SELECT IP, pols, nick, ID, ref, estado,
".(ECONOMIA?"(SELECT SUM(pols) FROM cuentas WHERE pais = '".PAIS."' AND user_ID = ID)":"estado")." AS pols_cuentas 
FROM users 
WHERE estado = 'expulsado' AND pais <> 'ninguno'");
while($r3 = r($result3)) {
	$user_ID = $r3['ID']; 
	$estado = $r3['estado']; 
	$pols = ($r3['pols'] + $r3['pols_cuentas']); 
	$nick = $r3['nick']; 
	$ref = $r3['ref']; 
	$IP = $r3['IP'];

	evento_log('Eliminando propiedades de usuario ('.$nick.') previamente expulsado.');
	pols_transferir($pols, $user_ID, '-1', 'Eliminación de usuario ');

	sql_old("DELETE FROM empresas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
	sql_old("DELETE FROM cuentas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
	sql_old("DELETE FROM mapa WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
	sql_old("DELETE FROM pujas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");

	sql_old("DELETE FROM cargos_users WHERE user_ID = '".$user_ID."'");
	sql_old("DELETE FROM partidos_listas WHERE pais = '".PAIS."' AND user_ID = '".$user_ID."'");
	sql_old("DELETE FROM partidos WHERE pais = '".PAIS."' AND ID_presidente = '".$user_ID."'");


	sql_old("UPDATE users SET estado = 'expulsado', pais = 'ninguno', nivel = '1', cargo = '0', cargos = '', examenes = '', nota = '0.0', pols = '0.0' WHERE ID = '".$user_ID."' LIMIT 1");
}

//Fin eliminación posesiones.

/* Expiraciones:
NUNCA

Excepciones:
* Autentificados
* Socios
* Donantes
* Veteranos (1 año de antiguedad)

Emails de aviso de expiración:
1. Tras 30 días inactivo
2. Tras 55 días inactivo
*/

/*
$st['eliminados'] = 0;
$result = sql_old("SELECT ID, estado, nick FROM users
WHERE (dnie = 'false' AND socio = 'false' AND donacion IS NULL AND fecha_registro > '".tiempo(365)."' AND 
(pais IN ('ninguno', '".PAIS."') AND fecha_last <= '".tiempo(120)."')) OR 
(estado IN ('validar', 'expulsado') AND fecha_last <= '".tiempo(10)."') 
LIMIT 80");
while($r = r($result)) {
	if ($r['estado'] == 'ciudadano') { $st['eliminados']++; }
	eliminar_ciudadano($r['ID']);
}
*/


// Emails de aviso de expiración
$result = sql_old("SELECT ID, pais, nick, email FROM users
WHERE estado IN ('ciudadano', 'turista') AND dnie = 'false' AND socio = 'false' AND donacion IS NULL AND fecha_registro > '".tiempo(365*2)."' AND 
(pais = '".PAIS."' AND 
((fecha_last >= '".tiempo(30, '00:00:00')."' AND fecha_last <= '".tiempo(30, '23:59:59')."') OR 
(fecha_last >= '".tiempo(55, '00:00:00')."' AND fecha_last <= '".tiempo(55, '23:59:59')."')))
OR 
(fecha_last >= '".tiempo(85, '00:00:00')."' AND fecha_last <= '".tiempo(85, '23:59:59')."')))
LIMIT 1000");
while($r = r($result)) {
	$mensaje = '<p>Hola ciudadano '.$r['nick'].':</p>

<p>En VirtualPol nos esmeramos en tener un censo seguro y fiel a la realidad, en lugar de tener cientos de miles de usuarios sin actividad. Por ello los usuarios que no entran en 90 días son borrados por inactividad.</p>

<p>Tu usuario "'.$r['nick'].'" está a punto de expirar por inactividad. Debes entrar lo antes posible en VirtualPol. Solo con entrar basta para que tu usuario "'.$r['nick'].'" no expire.</p>

<p><a href="http://'.strtolower($r['pais']).'.'.DOMAIN.'"><b style="font-size:20px;">Regresa a '.$r['pais'].' y participa!</b></a></p>

<p><b>VirtualPol</b> - La primera Red Social Democrática.<br />
http://www.'.DOMAIN.'</p>';
	//enviar_email(null, 'Tu usuario '.$r['nick'].' está a punto de expirar por inactividad', $mensaje, $r['email']);
	if ($r['estado'] == 'ciudadano') { 
		//$st['eliminados']++; 
		//convertir_turista($r['ID']);
	}
}




// ACTUALIZACION DEL VOTO CONFIANZA
sql_old("UPDATE users SET voto_confianza = '0'");
$result = sql_old("SELECT item_ID, SUM(voto) AS num_confianza FROM votos, users WHERE tipo = 'confianza' AND users.id = emisor_ID AND (users.estado != 'expulsado' and users.estado != 'validar') GROUP BY item_ID");
while ($r = r($result)) { 
	sql_old("UPDATE users SET voto_confianza = '".$r['num_confianza']."' WHERE ID = '".$r['item_ID']."' LIMIT 1");
}
sql_old("DELETE FROM votos WHERE tipo = 'confianza' AND (voto = '0' OR time < '".tiempo(180)."')");




if (date('N') == 7) { // SOLO DOMINGO

	// Guardar historico de confianza (un dato por semana)
	$result = sql_old("SELECT ID, voto_confianza FROM users WHERE pais = '".PAIS."'");
	while ($r = r($result)) {
		sql_old("UPDATE users SET confianza_historico = CONCAT(confianza_historico,' ".$r['voto_confianza']."') WHERE ID = '".$r['ID']."' LIMIT 1");
	}
	
	// Actualizar nuevos SC
	sql_old("UPDATE users SET SC = 'false'");
	$result = sql_old("SELECT ID FROM users WHERE estado = 'ciudadano' AND fecha_registro < '".tiempo(365)."' AND ser_SC = 'true' ORDER BY voto_confianza DESC, fecha_registro ASC LIMIT ".SC_NUM);
	while($r = r($result)){ 
		sql_old("UPDATE users SET SC = 'true' WHERE ID = '".$r['ID']."' LIMIT 1");
	}


	evento_chat('<b>[PROCESO] Supervisores del Censo electos:</b> '.implode(' ', get_supervisores_del_censo()));
	
	// Quitar candidaturas de SC que estén más de 30 dias inactivos.
	sql_old("UPDATE users SET ser_SC = 'false' WHERE ser_SC = 'true' AND fecha_last < '".tiempo(30)."'");
}



// STATS (1º obtener variables estadísticas, 2º insertar los datos en la tabla stats)

// ciudadanos
$result = sql_old("SELECT COUNT(ID) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."'");
while($r = r($result)) { $st['ciudadanos'] = $r['num']; }

// nuevos
$result = sql_old("SELECT COUNT(ID) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_registro > '".tiempo(1)."'");
while($r = r($result)) { $st['nuevos'] = $r['num']; }
evento_chat('<b>[PROCESO]</b> Ciudadanos nuevos: <b>'.$st['nuevos'].'</b>, Ciudadanos expirados: <b>'.$st['eliminados'].'</b>. Balance: <b>'.round($st['nuevos'] - $st['eliminados']).'</b>');

// pols
$result = sql_old("SELECT SUM(pols) AS num FROM users WHERE pais = '".PAIS."'");
while($r = r($result)) { $st['pols'] = $r['num']; }

// pols_cuentas
if (ECONOMIA) {
	$result = sql_old("SELECT SUM(pols) AS num FROM cuentas WHERE pais = '".PAIS."'");
	while($r = r($result)) { $st['pols_cuentas'] = $r['num']; }

	// transacciones

	$result = sql_old("SELECT COUNT(ID) AS num FROM transacciones WHERE pais = '".PAIS."' AND time > '".tiempo(1)."'");
	while($r = r($result)) { $st['transacciones'] = $r['num']; }
} else { $st['transacciones'] = 0; $st['pols_cuentas'] = 0; }

// hilos+msg
$result = sql_old("SELECT COUNT(ID) AS num FROM ".SQL."foros_hilos WHERE time > '".tiempo(1)."'");
while($r = r($result)) { $st['hilos_msg'] = $r['num']; }
$result = sql_old("SELECT COUNT(ID) AS num FROM ".SQL."foros_msg WHERE time > '".tiempo(1)."'");
while($r = r($result)) { $st['hilos_msg'] = $st['hilos_msg'] + $r['num']; }

// pols_gobierno
if (ECONOMIA) {
	$result = sql_old("SELECT SUM(pols) AS num FROM cuentas WHERE pais = '".PAIS."' AND gobierno = 'true'");
	while($r = r($result)) { $st['pols_gobierno'] = $r['num']; }
} else { $st['pols_gobierno'] = 0; }

// partidos
$result = sql_old("SELECT COUNT(ID) AS num FROM partidos WHERE pais = '".PAIS."' AND estado = 'ok'");
while($r = r($result)) { $st['partidos'] = $r['num']; }

// empresas
if (ECONOMIA) {
	$result = sql_old("SELECT COUNT(ID) AS num FROM empresas WHERE pais = '".PAIS."'");
	while($r = r($result)) { $st['empresas'] = $r['num']; }


	// mapa (desde el 2011/04/07 guarda el porcentaje en venta.
	$superficie_total = $columnas * $filas;
	$result = sql_old("SELECT superficie, estado FROM mapa WHERE pais = '".PAIS."'");
	while($r = r($result)) { 
		$sup_total += $r['superficie']; 
		if ($r['estado'] == 'v') { $sup_vende += $r['superficie']; }
	}
	$st['mapa'] = round(($sup_vende * 100) / $superficie_total);

	// mapa_vende: el precio de venta más bajo de una propiedad
	$result = sql_old("SELECT pols FROM mapa WHERE pais = '".PAIS."' AND estado = 'v' ORDER BY pols ASC LIMIT 1");
	while($r = r($result)) { $st['mapa_vende'] = $r['pols']; }
} else { $st['empresas'] = 0; $st['mapa'] = 0; $st['mapa_vende'] = 0; }


// 24h: ciudadanos que entraron en 24h (CONDICION NUEVA: y que no sean ciudadanos nuevos).
$result = sql_old("SELECT COUNT(ID) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_last > '".tiempo(1)."' AND fecha_registro < '".tiempo(1)."'");
while($r = r($result)) { $st['24h'] = $r['num']; }

// confianza
$result = sql_old("SELECT SUM(voto) AS num FROM votos WHERE tipo = 'confianza'");
while($r = r($result)) { $st['confianza'] = $r['num']; }

// autentificados
$result = sql_old("SELECT COUNT(*) AS num FROM users WHERE dnie = 'true' AND pais = '".PAIS."'");
while($r = r($result)) { $st['autentificados'] = $r['num']; }


// STATS GUARDADO DIARIO
sql_old("INSERT INTO stats 
(pais, time, ciudadanos, nuevos, pols, pols_cuentas, transacciones, hilos_msg, pols_gobierno, partidos, frase, empresas, eliminados, mapa, mapa_vende, 24h, confianza, autentificados) 
VALUES ('".PAIS."', '".date('Y-m-d 20:00:00')."', '".$st['ciudadanos']."', '".$st['nuevos']."', '".$st['pols']."', '".$st['pols_cuentas']."', '".$st['transacciones']."', '".$st['hilos_msg']."', '".$st['pols_gobierno']."', '".$st['partidos']."', '".$pujas_total."', '".$st['empresas']."', '".$st['eliminados']."', '".$st['mapa']."', '".$st['mapa_vende']."', '".$st['24h']."', '".$st['confianza']."', '".$st['autentificados']."')");


// ¿ELECCIONES?
include('cron-elecciones.php');

// Unifica y comprime archivos CSS y JS
include('cron-compress-all.php');



evento_chat('<b>[PROCESO] FIN del proceso</b>, todo <span style="color:blue;"><b>OK</b></span>, '.num((microtime(true)-TIME_START)/1000000000).'s (<a href="/estadisticas/'.PAIS.'">estadisticas actualizadas</a>)');





if (date('N') == 1) { // Solo Lunes 

	evento_chat('<b>[#] Comienzo de envio de emails</b> semanales de aviso de votaciones.');
	
	$emails_enviados = 0;
	$result = sql_old("SELECT ID, nick, email, pais FROM users WHERE pais = '".PAIS."' AND estado = 'ciudadano' AND email != '' ORDER BY fecha_registro ASC LIMIT 10000");
	while($r = r($result)) {

		// Lista de votaciones por votar del usuario
		$txt_votaciones = '';
		$votar_num = 0;
		$result2 = sql_old("SELECT ID, pais, pregunta, tipo,
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$r['ID']."' LIMIT 1) AS ha_votado
FROM votacion
WHERE estado = 'ok' AND pais = '".PAIS."' AND acceso_votar IN ('ciudadanos_global', 'ciudadanos') AND acceso_ver IN ('ciudadanos_global', 'ciudadanos', 'anonimos')
ORDER BY num DESC");
		while($r2 = r($result2)) {
			if (!$r2['ha_votado']) { // Si NO ha votado...
				$votar_num++;
				$txt_votaciones .= '<li><a href="http://'.strtolower($r2['pais']).'.'.DOMAIN.'/votacion/'.$r2['ID'].'"><b>'.$r2['pregunta'].'</b></a> ('.ucfirst($r2['tipo']).')</li>';
			}
		}

		if ($votar_num > 0) { // Enviar email solo si tiene votaciones por votar

			// Lista de ultimas votaciones finalizadas
			$txt_votaciones_result = '';
			$result2 = sql_old("SELECT ID, pais, pregunta, tipo, num
FROM votacion
WHERE estado = 'end' AND pais = '".PAIS."' AND acceso_votar IN ('ciudadanos_global', 'ciudadanos') AND acceso_ver IN ('ciudadanos_global', 'ciudadanos', 'anonimos')
ORDER BY time_expire DESC LIMIT 5");
			while($r2 = r($result2)) {
				$txt_votaciones_result .= '<li><a href="http://'.strtolower($r2['pais']).'.'.DOMAIN.'/votacion/'.$r2['ID'].'">'.$r2['pregunta'].'</a> <span style="">(<b>'.num($r2['num']).'</b> votos)</span></li>';
			}

			$txt_email = '<p>¡Hola '.$r['nick'].'!</p>
		
<p>Aún puedes votar en las siguientes votaciones:</p>
<ol>'.$txt_votaciones.'</ol>

<p><br />Resultados de las últimas votaciones:</p>
<ul>
'.$txt_votaciones_result.'
<li>(<a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/votacion">Ver todas</a>)</li>
</ul>

<p><br />Más formas de participar: <a href="http://'.strtolower(PAIS).'.'.DOMAIN.'"><b>Chat</b></a>, <a href="http://'.strtolower(PAIS).'.'.DOMAIN.'/hacer">¿<b>Qué hacer</b>?</a></p>

<p>________<br />
<b>'.$pol['config']['pais_des'].'</b><br />
<a href="http://www.'.DOMAIN.'">Virtual<b>Pol</b></a> - La primera red social democrática
</p>';
			$txt_titulo = $r['nick'].', '.($votar_num>1?'¡Tienes '.$votar_num.' votaciones pendientes!':'¡Tienes una votación pendiente!');

			enviar_email($r['ID'], $txt_titulo, $txt_email); 
			$emails_enviados++;

			//echo $votar_num.' '.$r['nick'].'<br />'.$txt_email;
		}
	}
	evento_chat('<b>[#] Terminado el envio de emails</b> de aviso <span style="color:grey;">('.num($emails_enviados).' emails enviados)</span>.');

}