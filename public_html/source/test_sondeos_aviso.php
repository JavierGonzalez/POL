<?php 
include('inc-login.php');
include('inc-functions-accion.php');

// ENVIO DE EMAILS DE AVISO

if (false) {

	evento_chat('<b>[#] Comienzo de envio de emails</b> de aviso de votaciones <span style="color:grey;">('.count($votaciones).' votaciones)</span>.');
	
	$emails_enviados = 0;
	$result = mysql_query("SELECT ID, nick, email FROM users WHERE estado = 'ciudadano' AND email != '' ORDER BY fecha_registro ASC LIMIT 10000000", $link);
	while($r = mysql_fetch_array($result)) {

		$txt_votaciones = '';
		$votar_num = 0;
		$result2 = mysql_query("SELECT ID, pais, pregunta, time, time_expire, user_ID, estado, num, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver,
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$r['ID']."' LIMIT 1) AS ha_votado
FROM votacion
WHERE estado = 'ok' AND pais = '15M' AND (acceso_votar = 'ciudadanos_global' OR acceso_votar = 'ciudadanos')
ORDER BY num DESC", $link);
		while($r2 = mysql_fetch_array($result2)) {
			if (!$r2['ha_votado']) {
				$votar_num++;
				$txt_votaciones .= '<li><a href="http://'.strtolower($r2['pais']).'.'.DOMAIN.'/votacion/'.$r2['ID'].'"><b>'.$r2['pregunta'].'</b></a> ('.ucfirst($r2['tipo']).')</li>';
			}
		}

		if ($votar_num > 0) {

			$txt_email = '<p>Hola '.$r['nick'].'!</p>
		
<p>Aún no has votado en las siguientes votaciones:</p>

<ol>
'.$txt_votaciones.'
</ol>

<p>Tu voto cuenta. Participamos en nuestra <a href="http://15m.virtualpol.com">sala de chat</a>.</p>

<p>¡Unidos somos fuertes!</p>

<p>_____<br />
<a href="http://15m.virtualpol.com">Asamblea Virtual 15M</a><br />
</p>';
			$txt_titulo = $r['nick'].', '.($votar_num>1?'¡Tienes '.$votar_num.' votaciones pendientes!':'¡Tienes una votación pendiente!');

			enviar_email($r['ID'], $txt_titulo, $txt_email); 
			$emails_enviados++;

			$txt .= $votar_num.' '.$r['nick'].'<br />';
		}
	}
	evento_chat('<b>[#] Terminado el envio de emails</b> de aviso <span style="color:grey;">('.num($emails_enviados).' emails enviados, '.round(microtime(true)-TIME_START).' seg de proceso)</span>.');
}

$txt .= '<hr />'.$contador;
include('theme.php');
?>