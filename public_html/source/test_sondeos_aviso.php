<?php 
include('inc-login.php');
include('inc-functions-accion.php');

// ENVIO DE EMAILS DE AVISO

$votaciones = array(1856, 1829, 1832, 1824, 1855);

if (false) {

	evento_chat('<b>[#] Comienzo de envio de emails</b> de aviso de votaciones <span style="color:grey;">('.count($votaciones).' votaciones)</span>.');

	$result = mysql_query("SELECT ID AS user_ID, nick, email FROM users WHERE estado = 'ciudadano' AND email != '' ORDER BY fecha_registro ASC LIMIT 100000", $link);
	while($r = mysql_fetch_array($result)) {

		$ha_votado_en = array();
		$falta_votar_en = array();
		$result2 = mysql_query("SELECT ref_ID FROM votacion_votos WHERE user_ID = '".$r['user_ID']."' AND ref_ID IN (".implode(',', $votaciones).")", $link);
		while($r2 = mysql_fetch_array($result2)) { $ha_votado_en[] = $r2['ref_ID']; }


		$falta_votar_en = array_diff($votaciones, $ha_votado_en);
		
		if (count($falta_votar_en) > 0) {

			// print
			$txt .= count($falta_votar_en).' '.$r['nick'].' ('.implode(', ', $falta_votar_en).')<br />';
			$contador++;

			$votaciones_li = '';
			$numm = 1;
			foreach ($falta_votar_en AS $id => $dato) {
				$votaciones_li .= "     ".$numm++.". http://15m.".DOMAIN."/votacion/".$dato."/\n";
			}

			$texto_email = "Hola ".$r['nick']."!\n\nAún no has votado en los siguientes sondeos de Asamblea Virtual 15M:\n\n".$votaciones_li."\nCuantos más votos más legitimidad. Tu opinión cuenta. Puedes votar \"En Blanco\" si no lo tienes claro y así participar. Recuerda que puedes modificar mientras la votación está activa.\n\n¿Como participar? http://15m.virtualpol.com/hacer/\n\nVer resultados: http://15m.virtualpol.com/votacion/\n\n\nDifundimos entre todos! Una asamblea para todos\n\n_________\nAsamblea Virtual 15M\nhttp://15m.virtualpol.com/";
			
			echo $r['nick'].'<br />';

			// \n\nSigue los sondeos desde redes sociales:\nhttps://www.facebook.com/pages/Asamblea-Virtual/216054178475524\nhttps://twitter.com/#!/AsambleaVirtuaI

			if (true) {
				mail($r['email'], (count($falta_votar_en)>1?"[15M] Hay ".count($falta_votar_en)." votaciones en las que aún no has votado!":"[15M] Hay una votación en la que aún no has votado!"), $texto_email, "FROM: VirtualPol <".CONTACTO_EMAIL."> \nReturn-Path: VirtualPol <".CONTACTO_EMAIL."> \nX-Sender: VirtualPol <".CONTACTO_EMAIL."> \nMIME-Version: 1.0\n"); 
			}
		}
	}
	evento_chat('<b>[#] Terminado el envio de emails</b> de aviso de votaciones <span style="color:grey;">('.num($contador).' emails enviados, '.round(microtime(true)-TIME_START).' seg de proceso)</span>.');
}

$txt .= '<hr />'.$contador;
include('theme.php');
?>