<?php 
include('inc-login.php');


// HERRAMIENTA DE DESARROLLO PARA VERIFICAR EL IMPORTANTE NUCLEO DE ACCESO.

$votaciones = array(1616, 1617, 1618, 1648, 1650);

$result = mysql_query("SELECT ID AS user_ID, nick, email FROM users WHERE estado = 'ciudadano' AND email != '' ORDER BY fecha_registro ASC LIMIT 1", $link);
while($r = mysql_fetch_array($result)) {

	$ha_votado_en = array();
	$falta_votar_en = array();
	$result2 = mysql_query("SELECT ref_ID FROM votacion_votos WHERE user_ID = '".$r['user_ID']."' AND ref_ID IN (".implode(',', $votaciones).")", $link);
	while($r2 = mysql_fetch_array($result2)) { $ha_votado_en[] = $r2['ref_ID']; }


	$falta_votar_en = array_diff($votaciones, $ha_votado_en);
	
	if (count($falta_votar_en) > 0) {

		// print
		$txt .= $r['nick'].': '.count($falta_votar_en).' ('.implode(', ', $falta_votar_en).')<br />';
		$contador++;

		$votaciones_li = '';
		$numm = 1;
		foreach ($falta_votar_en AS $id => $dato) {
			$votaciones_li .= $numm++.". http://15m.".DOMAIN."/votacion/".$dato."/\n";
		}

		$texto_email = "Hola ciudadano ".$r['nick']."!\n\nEste email es para avisarte de que aún no has votado en los siguientes sondeos de la Asamblea Virtual 15M:\n\n".$votaciones_li."\nVotar es importante, cuantos más votos más legitimidad. Tu opinión cuenta. Puedes votar \"En Blanco\" si no lo tienes claro, así también participarás. Recuerda que puedes modificar tu voto las veces que quieras mientras la votación esté activa.\n\n¿Como participar más a fondo? http://15m.".DOMAIN."/hacer/\n\nAyúdanos a difundir, unidos somos fuertes!\n\n_________\nAsamblea Virtual 15M\nhttp://15m.".DOMAIN."/";


		// \n\nSigue los sondeos desde redes sociales:\nhttps://www.facebook.com/pages/Asamblea-Virtual/216054178475524\nhttps://twitter.com/#!/AsambleaVirtuaI

		if (true) {
			$r['email'] = "gonzomail@gmail.com"; // PROTECCION
			mail($r['email'], (count($falta_votar_en)>1?"[15M] Hay ".count($falta_votar_en)." votaciones en las que aún no has votado!":"[15M] Hay una votación en la que aún no has votado!"), $texto_email, "FROM: VirtualPol <".CONTACTO_EMAIL."> \nReturn-Path: VirtualPol <".CONTACTO_EMAIL."> \nX-Sender: VirtualPol <".CONTACTO_EMAIL."> \nMIME-Version: 1.0\n"); 
		}
		//$txt .= $texto_email;
	}
}

$txt .= '<hr />'.$contador;
include('theme.php');
?>