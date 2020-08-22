<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



exit;

// ENVIO DE EMAILS DE AVISO

// EMAIL ANTIGUOOOOOOOOOOOOOOOOOOOOOOOOO 

// ------ NO ENVIAR -------




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
<li>(<a href="/votacion">Ver todas</a>)</li>
</ul>

<p><br />Más formas de participar: <a href=""><b>Chat</b></a>, <a href="/hacer">¿<b>Qué hacer</b>?</a></p>

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












?>