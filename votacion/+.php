<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$votaciones_tipo = array('sondeo', 'referendum', 'parlamento', 'cargo', 'elecciones');


//THEME
$txt_menu = 'demo';



// FINALIZAR VOTACIONES
$result = sql_old("SELECT ID, tipo, tipo_voto, num, pregunta, respuestas, ejecutar, privacidad, acceso_ver FROM votacion 
WHERE estado = 'ok' AND pais = '".PAIS."' AND (tipo_voto = 'aleatorio' OR time_expire <= '".$date."' OR ((votos_expire != 0) AND (num >= votos_expire)))");
while($r = r($result)){
	
	// Finaliza la votación
	sql_old("UPDATE votacion SET estado = 'end', time_expire = '".$date."' WHERE ID = '".$r['ID']."' LIMIT 1");

	if ($r['acceso_ver'] == 'anonimos') {
		evento_chat('<b>['.strtoupper($r['tipo']).']</b> Finalizado, resultados: <a href="/votacion/'.$r['ID'].'"><b>'.$r['pregunta'].'</b></a> <span style="color:grey;">(votos: <b>'.$r['num'].'</b>)</span>');
	}

	if ($r['ejecutar'] != '') { // EJECUTAR ACCIONES

		$validez_voto['true'] = 0; $validez_voto['false'] = 0; $voto[0] = 0; $voto[1] = 0; $voto[2] = 0; $voto_preferencial = array();
		$result2 = sql_old("SELECT validez, voto FROM votacion_votos WHERE ref_ID = ".$r['ID']."");
		while($r2 = r($result2)) {
			$validez_voto[$r2['validez']]++;
			if ($r['tipo_voto'] == 'estandar') {
				$voto[$r2['voto']]++;
			} elseif (substr($r['tipo_voto'], 1, 6) == 'puntos') {
				$voto_valor = 0;
				foreach (explode(' ', $r2['voto']) AS $opcion_ID) {
					$voto_valor++;
					$voto_preferencial[$opcion_ID] += $voto_valor;
				}
			}
		}

		// Determina validez
		if ($validez_voto['false']<=$validez_voto['true']) { 
			// OK: votación válida
			$cargo_ID = explodear('|',$r['ejecutar'],1);
			
			switch (explodear('|',$r['ejecutar'],0)) {

				case 'cargo': // $r['ejecutar'] = cargo|$cargo_ID|$user_ID
					if ($voto[1] > $voto[2]) {
						cargo_add($cargo_ID, explodear('|',$r['ejecutar'],2), true, true);
					} else {
						cargo_del($cargo_ID, explodear('|',$r['ejecutar'],2), true, true);
					}
					break;


				case 'elecciones': // $r['ejecutar'] = elecciones|$cargo_ID|$numero_a_asignar
					
					$cargo_del = array();
					$cargo_add = array();

					// Quita todos los cargos de las elecciones (reset)
					$result2 = sql_old("SELECT user_ID FROM cargos_users WHERE cargo_ID = '".$cargo_ID."' AND pais = '".PAIS."' AND cargo = 'true'");
					while($r2 = r($result2)) { 
						$cargo_del[] = $r2['user_ID'];
					}

					// Reset campo temporal (más simple que crear tablas temporales)
					sql_old("UPDATE users SET temp = NULL WHERE temp IS NOT NULL");
					
					// Añade los resultados de puntos en el campo temporal
					$respuestas = explode('|', $r['respuestas']);
					$votacion_preferencial_nick = array();
					foreach ($voto_preferencial AS $opcion_ID => $puntos) {
						if ($opcion_ID != 0) { // Ignora "En blanco" por ser no computable
							sql_old("UPDATE users SET temp = '".$puntos."' WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND nick = '".$respuestas[$opcion_ID]."' LIMIT 1");
							$votacion_preferencial_nick[$respuestas[$opcion_ID]] = $puntos;
						}
					}

					// Asigna ordenando con SQL teniendo en cuenta la antiguedad para desempatar
					$n = 0;
					$guardar = array();
					$result2 = sql_old("SELECT ID, nick FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND temp IS NOT NULL ORDER BY temp DESC, fecha_registro ASC, voto_confianza DESC LIMIT 50");
					while($r2 = r($result2)) {
						$n++;
						if ($n <= explodear('|', $r['ejecutar'], 2)) { 
							$cargo_add[] = $r2['ID'];
						}
						$guardar[] = $r2['nick'].'.'.$votacion_preferencial_nick[$r2['nick']];
					}

					// Asignación de cargos
					foreach ($cargo_del AS $user_ID) {
						if (!in_array($user_ID, $cargo_add)) { cargo_del($cargo_ID, $user_ID, true, true); }
					}
					foreach ($cargo_add AS $user_ID) {
						if (!in_array($user_ID, $cargo_del)) { cargo_add($cargo_ID, $user_ID, true, true); }
					}
					
					// Guarda escrutinio
					sql_old("UPDATE votacion SET ejecutar = '".$r['ejecutar']."|".implode(':', $guardar)."' WHERE ID = '".$r['ID']."' LIMIT 1");
					
					break;
			}
		}
	}

	// _______ A continuación se rompe la relación Usuario-Voto irreversiblemente ________
	
	if ($r['privacidad'] == 'true') {
		// Rompe la relación Usuario-Voto. Solo en votaciones con secreto de voto.
		barajar_votos($r['ID']);
	}

	// Actualiza contador de votaciones activas
	actualizar('votaciones');
}
// FIN DE FINALIZAR VOTACIONES
