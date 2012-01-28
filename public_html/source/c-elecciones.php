<?php
include('inc-login.php');
/*
#OLD
elecciones_estado  	elecciones
presidente 	Pablo1
num_escanos 	3
elecciones_inicio 	2008-08-27 13:00:00
elecciones_duracion 	172800 2 dias
elecciones_frecuencia 	1036800 7+5 dias
elecciones_antiguedad 	86400 1 dia

#NEW 
elecciones (_pres|pres1|pres2|_parl|parl)

pol_elec (ID, time, tipo, num_votantes, escrutinio)

80:GP:Pablo1|33:B:|26:MENEA:al00|26:FCL:NachE|14:CGA:Diver|11:UPR:Naaram|2:I:

7+5=12 42%
2 - 8%
7+5=12 42%
2 - 8%
*/

// LOAD CONFIG
$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
while ($r = mysql_fetch_array($result)) { $pol['config'][$r['dato']] = $r['valor']; }

// TEST


if ($_GET['a'] == 'votar') {
	//elecciones/votar/



	//$pol['config']['elecciones'] = 'parl';
	//$pol['config']['elecciones_estado'] = 'elecciones';



	if ($pol['estado'] == 'ciudadano') { 
		// ciudadano


		switch ($pol['config']['elecciones']) {
			case 'pres1': $elec_nombre = 'Elecciones Presidenciales (1&ordf; vuelta)'; $elec_boton = ' (Recuerda votar en la 2&ordf; vuelta! del Sabado 20:00 al Domingo 20:00)'; break;
			case 'pres2': $elec_nombre = 'Elecciones Presidenciales (2&ordf; vuelta)'; break;
			case 'parl': $elec_nombre = 'Elecciones al Parlamento'; break;
		}

		$txt_title = $elec_nombre;
		$txt .= '<h1>' . (ASAMBLEA?'Elecciones de Coordinadores':$elec_nombre) . '</h1>';
		


		if ((($pol['config']['elecciones_estado'] == 'elecciones') AND (($pol['config']['elecciones'] == 'pres1') OR ($pol['config']['elecciones'] == 'pres2')))) {
			// ELECCIONES PRESIDENCIALES

			$fecha_24_antes = date('Y-m-d H:i:00', strtotime($pol['config']['elecciones_inicio']) - $pol['config']['elecciones_antiguedad']);

			//fecha registro?
			$result = mysql_query("SELECT fecha_registro FROM users WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $fecha_registro = $r['fecha_registro']; }

			//ha votado?
			$result = mysql_query("SELECT ID FROM ".SQL."elecciones WHERE user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $ha_votado = $r['ID']; }

			if ($fecha_registro >= $fecha_24_antes) {
				$txt .= '<p class="amarillo" style="color:red;">No puedes ejercer el voto por falta de antiguedad, podr&aacute;s en las pr&oacute;ximas elecciones!</p>';
			} elseif ($ha_votado) { //ya ha votado
				$txt .= '<p class="amarillo"><b>Voto correcto!</b> Felicidades.</p>';
			} else {
				$txt .= '
<ul>
<li>Debes ejercer tu derecho a voto, como Ciudadano de '.PAIS.'.</li>
<li>El voto es siempre libre, anonimo y unipersonal.</li>
'.(ECONOMIA?'<li>Puedes ver las <a href="/partidos/"><b>listas</b> y <b>candidatos</b> aqu&iacute;</a>.</li>':'').'
</ul>';


				if ($pol['config']['elecciones'] == 'pres2') {
					$result3 = mysql_query("SELECT escrutinio FROM ".SQL."elec ORDER BY time DESC LIMIT 1", $link);
					while($r3 = mysql_fetch_array($result3)){ 
						// formato: candidato1|candidato1#escrutinio
						$candidatos_1v = explode("#", $r3['escrutinio']);
						$candidatos_1v = explode("|", $candidatos_1v[0]); 
					}
					foreach($candidatos_1v as $ID => $ID_partido) { $candidatos_1vuelta[$ID_partido] = true; }
				}

				$result = mysql_query("SELECT ID, siglas, 
(SELECT COUNT(ID) FROM ".SQL."partidos_listas WHERE ID_partido = ".SQL."partidos.ID LIMIT 1) AS num_lista
FROM ".SQL."partidos 
WHERE estado = 'ok' 
AND fecha_creacion < '".$fecha_24_antes."'
ORDER BY RAND()", $link);
				while($r = mysql_fetch_array($result)){
					if ($r['num_lista'] >= 1) {

						$result2 = mysql_query("SELECT user_ID,
(SELECT nick FROM users WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS nick
FROM ".SQL."partidos_listas 
WHERE ID_partido = '" . $r['ID'] . "' 
ORDER BY ID ASC
LIMIT 1", $link);
						while($r2 = mysql_fetch_array($result2)){ $nick = $r2['nick']; }
						if ($pol['config']['elecciones'] == 'pres2') {
							if ($candidatos_1vuelta[$r['ID']] == true) { 
								$partidos .= '<option value="' . $r['ID'] . '">' . $nick . ' (' . $r['siglas'] . ')</option>'; 
							}
						} else {
							$partidos .= '<option value="' . $r['ID'] . '">' . $nick . ' (' . $r['siglas'] . ')</option>';
						}

					}
				}
				$txt .= '<ol>
<li><form action="/accion.php?a=elecciones-generales" method="post"><select name="ID_partido" style="font-size:24px;color:green;">
<option value="0">En BLANCO</option>' . $partidos . '</select><br /><br /></li>
<li><input type="submit" style="font-size:24px;color:green;" value="VOTAR" />' . $elec_boton . '</form></li>
</ol>';
			}








		} elseif (($pol['config']['elecciones_estado'] == 'elecciones') AND ($pol['config']['elecciones'] == 'parl')) {
			// ELECCIONES AL PARLAMENTO

			$fecha_24_antes = date('Y-m-d H:i:00', strtotime($pol['config']['elecciones_inicio']) - $pol['config']['elecciones_antiguedad']);

			//fecha registro?
			$result = mysql_query("SELECT fecha_registro FROM users WHERE ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $fecha_registro = $r['fecha_registro']; }

			//ha votado?
			$result = mysql_query("SELECT ID FROM ".SQL."elecciones WHERE user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ $ha_votado = $r['ID']; }

			if ($fecha_registro >= $fecha_24_antes) {
				$txt .= '<p class="amarillo" style="color:red;">No puedes ejercer el voto por falta de antiguedad, podr&aacute;s en las pr&oacute;ximas elecciones!</p>';
			} elseif ($ha_votado) { //ya ha votado
				$txt .= '<p class="amarillo"><b>Voto correcto!</b> Felicidades.</p>';
			} else {
				$votos_num = $pol['config']['num_escanos'];
				$txt .= '
<ul>
<li>Debes ejercer tu derecho a voto, como ciudadano de '.PAIS.' cada 2 semanas.</li>
<li>El voto es siempre libre, anonimo y unipersonal.</li>
'.(ECONOMIA?'<li>Puedes ver las <a href="/partidos/"><b>listas</b> y <b>candidatos</b> aqu&iacute;</a>.</li>':'').'
<li>Puedes conceder hasta <b>'.$pol['config']['num_escanos'].' votos</b> a tus candidatos favoritos. Puedes dejar los votos que quieras en Blanco.</li>
</ul>


<div class="azul" id="votos_parl">

<form action="/accion.php?a=elecciones-generales" method="post">


<blockquote>
<table border="0">
<tr><td colspan="6" align="center"><b style="font-size:18px;"><span style="font-size:22px;" id="votos_num">' . $votos_num . '</span> votos en Blanco</b></td></tr>';

				$result = mysql_query("SELECT ID, siglas FROM ".SQL."partidos", $link);
				while($r = mysql_fetch_array($result)){
					$partidos[$r['ID']] = $r['siglas'];
				}

				$result = mysql_query("SELECT user_ID,
(SELECT nick FROM users WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS nick,
(SELECT estado FROM users WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS estado,
(SELECT partido_afiliado FROM users WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS partido_afiliado,
(SELECT voto_confianza FROM users WHERE ID = ".SQL."partidos_listas.user_ID LIMIT 1) AS confianza
FROM ".SQL."partidos_listas  
ORDER BY RAND()", $link);
				while($r = mysql_fetch_array($result)){ 
					if (($r['estado'] == 'ciudadano') AND ($partidos[$r['partido_afiliado']])) {
						if ($lat != true) { $txt .= '<tr>'; }

						$txt .= '<td><input class="diputado" name="' . $r['user_ID'] . '" value="1" type="checkbox" /> <b style="font-size:18px;">' . crear_link($r['nick']) . '</b></td><td>'.(ASAMBLEA?'':crear_link($partidos[$r['partido_afiliado']], 'partido')).'</td><td align="right">'.(ECONOMIA?confianza($r['confianza']):'').'</td>';

						if ($lat != true) { $txt .= '<td width="90"></td>'; $lat = true; } 
						else { $txt .= '</tr>'; $lat = false; }
					}
				} 

				$txt .= '</table><p style="color:#777;">* Pasa el ratón por encima o <em>haz clic</em> en los candidatos para ver m&aacute;s informaci&oacute;n.</p></blockquote></div>
				
<blockquote><input type="submit" style="font-size:24px;color:green;" value="VOTAR" /> (Una vez ejerzas el voto no podr&aacute;s modificarlo, tomate el tiempo que necesites)</form></blockquote>';



				$txt_header .= '
<script type="text/javascript">
votos_num = parseInt("' . $votos_num . '");

window.onload = function(){

	$(".diputado").click(function () {
		var checked_num = $("#votos_parl :input:checked").size();
		var votos = votos_num - checked_num;
		if (votos >= 0) {
			$("#votos_num").html(votos);
		} else {
			return false;
		}
	});
}


</script>';


			}




		} else	if ($pol['config']['elecciones_estado'] == 'normal') { 
			$txt .= '<p class="amarillo">Lo siento, a&uacute;n no puedes ejercer el derecho a voto.</p>';
		}
	} else { $txt .= '<p class="amarillo">Solo los Ciudadanos de '.PAIS.' tienen derecho a voto en estas Elecciones.</p>'; }





} else { // elecciones/
	$txt .= '
<div id="elec">

<h1>Elecciones</h1>

<table border="0" width="100%" cellspacing="8">
<tr><td colspan="2" class="amarillo" valign="top">

<h2>Calendario</h2>
<p>Pr&oacute;ximas elecciones: <b>'.explodear(' ', $pol['config']['elecciones_inicio'], 0).'</b> (siempre a las 20:00h). Duraci&oacute;n: <b>48h</b>. Periodicidad: <b>cada 2 semanas</b>.'.(ASAMBLEA?' Coordinadores a elegir: <b>'.$pol['config']['num_escanos'].'</b>.':'').'</p>
<table border="0" width="100%" height="50" cellpadding="2" cellspacing="0">
<tr>';

	$elec_ini = strtotime($pol['config']['elecciones_inicio']);
	if (($pol['config']['elecciones'] == 'pres1') OR ($pol['config']['elecciones'] == 'pres2') OR ($pol['config']['elecciones'] == '_pres')) {
		$mul = 12;
		$queda['pres']	= $elec_ini - time() . ' seg';
		$queda['parl']	= ($elec_ini + (86400 * 14)) - time() . ' seg';
	} else {
		$mul = 26;
		$queda['pres']	= ($elec_ini + (86400 * 14)) - time() . ' seg';
		$queda['parl']	= $elec_ini - time() . ' seg';
	}

	if (substr($queda['pres'], 0, 1) == '-') {
		if ($pol['config']['elecciones'] == 'pres1') {
			$queda['pres'] = '<b>1&ordf;</b> Vuelta en curso...';
		} elseif ($pol['config']['elecciones'] == 'pres2') {
			$queda['pres'] = '<b>2&ordf;</b> Vuelta en curso...';
		}
	} else { $queda['pres'] = duracion($queda['pres']); }

	if (substr($queda['parl'], 0, 1) == '-') { $queda['parl'] = 'En curso...'; } else { $queda['parl'] = duracion($queda['parl']); }

	$next_inicio = $elec_ini - (86400 * $mul);
	$hoy = date('j');
	for ($i=1;$i<=28;$i++) {
		$dia = date('j', $next_inicio + (86400 * $i));
		if ($dia == $hoy) { $dia = '<b style="font-size:20px;color:#333;">&rarr;<br />' . $dia . '</b>';  }
		if (($i == 13) OR ($i == 14)) { $dia = '<span style="color:red;">' . $dia . '</span>'; }
		if (($i == 27) OR ($i == 28)) { $dia = '<span style="color:blue;">' . $dia . '</span>'; }
		$txt .= '<td align="center" class="dia" valign="bottom" style="font-size:16px;color:#999;">' . $dia . '</td>';
	}

	$txt .= '
</tr>

<tr>
<td colspan="12" style="background:#D2D2D2;font-size:26px;" width="41%" height="30" title="Periodo normal" align="center">&rarr; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &rarr;</td>
'.(ASAMBLEA?'<td colspan="2" style="background:blue;color:white;" align="center" width="9%" title="Elecciones al Parlamento"><b>Parl</b></td>':'<td colspan="2" style="background:red;color:white;" align="center" width="9%" title="Elecciones Presidenciales"><b>Pres</b></td>').'

<td colspan="12" style="background:#D2D2D2;font-size:26px;" width="41%" title="Periodo normal" align="center">&rarr; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &rarr;</td>
<td colspan="2" style="background:blue;color:white;" align="center" width="9%" title="Elecciones al Parlamento"><b>Parl</b></td>
</tr>

</table>

</td></tr>';



if ($_GET['a'] == 'votar') {

	$txt .= '<tr><td colspan="2" class="amarillo">ok</td></tr>';

} else {

	$txt .= '<tr>';

	if (!ASAMBLEA) {

		$txt .= '
<td class="amarillo" width="50%" valign="top">
<h1 style="color:red;">Presidenciales en '.$queda['pres'].'</h1>';

		$tabla = '';
		$chart_dato = '';
		$chart_nom = '';
		$tabla_final = '';
		$result = mysql_query("SELECT time, tipo, num_votantes, escrutinio, num_votos
FROM ".SQL."elec 
WHERE tipo = 'pres'
ORDER BY time DESC LIMIT 1", $link);
		while($r = mysql_fetch_array($result)){
			$votos_total = $r['num_votos'];

			$txt .= '<p style="text-align:center;margin-top:8px;"><b>'.num($votos_total).'</b> votos de <em>'.num($r['num_votantes']).'</em>, participaci&oacute;n: <b>'.num(($votos_total*100)/$r['num_votantes'], 2).'%</b></p>';


			// formato: candidato1|candidato1#escrutinio
			if ($r['escrutinio']) {
				if ($pol['config']['elecciones'] == 'pres2') { 
					$r['escrutinio'] = explodear("#", $r['escrutinio'], 1); 
					$tabla .= '<tr><td colspan="4" align="center"><b>Escrutinio de la Primera Vuelta:</b></td></tr>';
				}

				// genera escrutinio en tabla
				$m = explode("|", $r['escrutinio']);
				$votos_total = 0;
				foreach($m as $t) {
					$t = explode(":", $t);
					if (($t[1] != 'I') AND ($t[1] != 'B')) { $votos_total += $t[0]; }
				}
				foreach($m as $t) {
					$t = explode(":", $t);
					if ($t[1] == 'B') {
						// if ($chart_dato) { $chart_dato .= ','; } $chart_dato .= $t[0];
						// if ($chart_nom) { $chart_nom .= '|'; } $chart_nom .= 'En Blanco';
						$tabla_final .= '<tr><td align="right" colspan="2"><b>En Blanco</b></td><td align="right">' . $t[0] . '</td><td align="right"></td></tr>';
					} elseif ($t[1] == 'I') {
						$tabla_final .= '<tr><td align="right" colspan="2"><b>Nulo</b></td><td align="right">' . $t[0] . '</td><td></td></tr>';
					} else {
						if ($chart_dato) { $chart_dato .= ','; } $chart_dato .= $t[0];
						if ($chart_nom) { $chart_nom .= '|'; } $chart_nom .= $t[2];
						$tabla .= '<tr><td align="right"><b>' . crear_link($t[2]) . '</b></td><td>' . crear_link($t[1], 'partido') . '</td><td align="right"><b>' . $t[0] . '</b></td><td align="right">' . number_format(($t[0] * 100) / $votos_total, 2, ',', '') . '%</td></tr>';
					}
				}
				$tabla .= $tabla_final;
			}

			// PRESIDENCIALES EN CURSO
			if (substr($pol['config']['elecciones'], 0, 4) == 'pres') {

				// presenta grafico participacion
				$p = round(($r['num_votos'] * 100) / $r['num_votantes']);
				$chart_dato = $p . ',' . (100 - $p);
				$chart_nom = 'Participacion|Abstencion';

				// boton votar
				$result = mysql_query("SELECT ID FROM ".SQL."elecciones WHERE user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
				while($r = mysql_fetch_array($result)){ $havotado = $r['ID']; }
				if ((!$havotado) AND ($pol['user_ID'])) { $boton = '<br />' . boton('VOTAR', '/elecciones/votar/'); }
				
				$tabla = '<tr><td colspan="4" style="color:grey;" align="center">Elecciones en curso...' . $boton . '</td></tr>' . $tabla;
			}
		}


		$txt .= '<center>
<img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.$chart_dato.'&chs=362x220&chds=a&chl='.$chart_nom.'&chf=bg,s,ffffff01|c,s,ffffff01&chp=3.14" alt="Escrutinio Presidenciales" />

<table border="0" class="pol_table">
<tr>
<th align="left">Candidato</th>
<th align="left">'.(ASAMBLEA?'':NOM_PARTIDOS).'</th>
<th colspan="2" align="left">Votos</th>
</tr>
' . $tabla . '
</table>
</center>

</td>';

}

$txt .= '
<td class="amarillo" width="50%" valign="top"'.(ASAMBLEA?' colspan="2"':'').'>
<h1 style="color:blue;">'.$queda['parl'].'</h1>';



	$tabla = '';
	$chart_dato = '';
	$chart_nom = '';
	$tabla_final = '';
	$result = mysql_query("SELECT time, tipo, num_votantes, escrutinio, num_votos
FROM ".SQL."elec 
WHERE tipo = 'parl'
ORDER BY time DESC LIMIT 1", $link);
	while($r = mysql_fetch_array($result)){
		$votos_total = $r['num_votos'];

		$txt .= '<p style="text-align:center;margin-top:8px;"><b>'.num($votos_total).'</b> votos de <em>'.num($r['num_votantes']).'</em>, participaci&oacute;n: <b>'.num(($votos_total * 100) / $r['num_votantes'], 2).'%</b></p>';
		
		if ($pol['config']['elecciones'] != 'parl') {
			$m = explode("|", $r['escrutinio']);

			$votos_total = 0;
			foreach($m as $t) {
				$t = explode(":", $t);
				if ($t[1] != 'I') { $votos_total += $t[0]; }
			}
			$count = 0;
			foreach($m as $t) {
				$t = explode(":", $t);
				if ($t[1] == 'B') {
					$tabla_final .= '<tr><td align="right" colspan="2"><b>En Blanco</b></td><td align="right">' . $t[0] . '</td><td align="right">' . number_format(($t[0] * 100) / $votos_total, 2, ',', '') . '%</td></tr>';
				} elseif ($t[1] == 'I') {
					$tabla_final .= '<tr><td align="right" colspan="2"><b>Nulo</b></td><td align="right">' . $t[0] . '</td><td></td></tr>';
				} else {
					$count++;

					// ejerce diputado?
					$cargo = '';
					$nestado = '';
					$result = mysql_query("SELECT ID, estado, (SELECT cargo FROM ".SQL."estudios_users WHERE user_ID = users.ID AND ID_estudio = '6' LIMIT 1) AS cargo FROM users WHERE nick = '" . $t[2] . "' AND pais = '".PAIS."' LIMIT 1", $link);
					while($r = mysql_fetch_array($result)){ 
						if ($r['estado'] == 'ciudadano') { $cargo = $r['cargo']; } 
						$nestado = $r['estado']; 
					}

					if ($t[1]) {
						if ($cargo == '1') {
							$tabla .= '<tr><td align="right">'.(ASAMBLEA?'':crear_link($t[1], 'partido')).'</td><td><img src="'.IMG.'cargos/6.gif" alt="Diputado" title="Diputado" border="0" /> <b>' . crear_link($t[2], 'nick', $nestado) . '</b></td><td align="right"><b>' . $t[0] . '</b></td><td align="right"></td></tr>';
						} else {
							$tabla .= '<tr><td align="right">'.(ASAMBLEA?'':crear_link($t[1], 'partido')).'</td><td>' . crear_link($t[2], 'nick', $nestado) . '</td><td align="right"><b>' . $t[0] . '</b></td><td align="right"></td></tr>';
						}
					}

				}
			}
			$tabla .= $tabla_final;
		} else { 

			// EN CURSO

			// EN CURSO
			$p = round(($votos_total * 100) / $r['num_votantes']);
			$chart_dato = $p . ',' . (100 - $p);
			$chart_nom = 'Participacion|Abstencion';

			$result = mysql_query("SELECT ID FROM ".SQL."elecciones WHERE user_ID = '" . $pol['user_ID'] . "' LIMIT 1", $link);
			while($r = mysql_fetch_array($result)){ 
				$havotado = $r['ID'];
			}
			if ((!$havotado) AND ($pol['user_ID'])) {
				 $boton = '<br />' . boton('VOTAR', '/elecciones/votar/');
			}
			$tabla = '<tr><td colspan="4" style="color:grey;" align="center">Elecciones en curso...' . $boton . '</td></tr>';


		}
	}



	$historial_p = '';
	$result = mysql_query("SELECT time, num_votantes, num_votos, tipo, escrutinio FROM ".SQL."elec ORDER BY time ASC", $link);
	while($r = mysql_fetch_array($result)){
		if ($historial_p) { $historial_p .= ','; }
		if ($historial_v) { $historial_v .= ','; }
		if ($historial_tipo) { $historial_tipo .= '|'; }
		$historial_tipo .= $r['tipo'];
		$historial_p .= round(($r['num_votos'] * 100) / $r['num_votantes']);
		$historial_v .= $r['num_votos'];
		if ($r['num_votos'] > $historial_v_max) { $historial_v_max = $r['num_votos']; }

		// genera datos grafico
		if (($r['escrutinio']) AND ($r['tipo'] == 'pres')) {
			if ($historial_tipo_pres) { $historial_tipo_pres .= '|'; }
			$historial_tipo_pres .= $r['tipo'];
			$fecha = strtotime($r['time']);
			$e = explode("|", $r['escrutinio']);
			foreach($e as $p) {
				$pp = explode(":", $p);
				if (($pp[1] != 'I') AND ($pp[1] != 'B') AND ($pp[1])) {
					$elec[$fecha][$pp[1]] = $pp[0];
					$partidos[$pp[1]]++;
					if ($votos_max_pres < $pp[0]) { $votos_max_pres = $pp[0]; }
				}
			}
		} elseif (($r['escrutinio']) AND ($r['tipo'] == 'parl')) {
			if ($historial_tipo_parl) { $historial_tipo_parl .= '|'; }
			$historial_tipo_parl .= $r['tipo'];
					
			$fecha = strtotime($r['time']);
			$e = explode("|", $r['escrutinio']);
			foreach($e as $p) {
				$pp = explode(":", $p);
				if (($pp[1] != 'I') AND ($pp[1] != 'B') AND ($pp[1])) {
					$elec[$fecha][$pp[1]] = $pp[0];
					$partidos_parl[$pp[1]]++;
					if ($votos_max_parl < $pp[0]) { $votos_max_parl = $pp[0]; }
				}
			}

		}
	}



	$i = 0;
	if ($partidos) { 
		arsort($partidos);
		foreach($partidos as $partido => $num) {
			if (($i++ < 15) AND ($num > 1)) { // AND ($num > 1)
				if ($g_eh_datos) { $g_eh_datos .= '|'; }
				$g_eh_datos .= $partido;
				$g_eh_x = '';
				foreach($elec as $fecha => $partido2) {
					if (!$partido2[$partido]) { $partido2[$partido] = -1; }
					$g_eh_x[] = $partido2[$partido];
				}
				if ($g_eh) { $g_eh .= ','; }
				$g_eh .= chart_data($g_eh_x, $votos_max_pres);
			} else { break; }
		}
	}

	$i = 0;
	if ($partidos_parl) { 
		arsort($partidos_parl);
		foreach($partidos_parl as $partido => $num) {
			if (($i++ < 15) AND ($num > 1)) { // AND ($num > 1)
				if ($g_eh_datos) { $g_parl_datos .= '|'; }
				$g_parl_datos .= $partido;
				$g_parl_x = '';
				foreach($elec as $fecha => $partido2) {
					if (!$partido2[$partido]) { $partido2[$partido] = -1; }
					$g_parl_x[] = $partido2[$partido];
				}
				if ($g_parl) { $g_parl .= ','; }
				$g_parl .= chart_data($g_parl_x, $votos_max_pres);
			} else { break; }
		}
	}

	if ($pol['config']['elecciones'] == 'parl') {
		$txt .= '<center><img src="http://chart.apis.google.com/chart?cht=p&chds=a&chd=t:' . $chart_dato . '&chs=362x220&chl=' . $chart_nom . '&chf=bg,s,ffffff01|c,s,ffffff01&chp=3.14" alt="Escrutinio Parlamentarias" />';
	} else {
		$txt .= '<center>';
		//<img src="http://chart.apis.google.com/chart?cht=p&chd=t:' . $chart_dato_escaños . ',' . $chart_dato . '&chs=362x220&chl=|' . $chart_nom . '&chco=FFFFDD,FF8000&chf=bg,s,FFFFDD,0" alt="Esca&ntilde;os Parlamentarias" />
	}
	$txt .= '<table border="0" class="pol_table">
<tr>
<th align="left" colspan="2">'.(ASAMBLEA?'':NOM_PARTIDOS).'</th>
<th colspan="2" align="left">Votos</th>
</tr>
' . $tabla . '
</table>
</center>


</td></tr><tr><td colspan="2" class="amarillo" valign="top">

<!--
<h1 style="margin-bottom:5px;">Elecciones Presidenciales (segundas vueltas)</h1>

<center>

<img src="http://chart.apis.google.com/chart?cht=lc
&chxt=y,x
&chs=740x250
&chd=s:' . $g_eh . '
&chls=3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0
&chdl=' . $g_eh_datos . '
&chma=42
&chco=000080,ff0000,1e90ff,688e23,d87093,800080,b22222,696969,ff1493,ff8c00,006400,b8860b,008b8b,8a2be2,ff00ff
&chf=bg,s,ffffff01|c,s,ffffff01
&chxl=0:|0|' . ($votos_max_pres / 2) . '|' . $votos_max_pres . '|1:|' . $historial_tipo_pres . '" alt="Historico de Elecciones Presidenciales"  />


</center>





<h1 style="margin-bottom:5px;">Elecciones Parlamentarias [BETA]</h1>

<center>

<img src="http://chart.apis.google.com/chart?cht=lc
&chxt=y,x
&chs=740x300
&chd=s:' . $g_parl . '
&chls=3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0|3,1,0
&chdl=' . $g_parl_datos . '
&chma=42
&chco=000080,ff0000,1e90ff,688e23,d87093,800080,b22222,696969,ff1493,ff8c00,006400,b8860b,008b8b,8a2be2,ff00ff
&chf=bg,s,ffffff01|c,s,ffffff01
&chxl=0:|0|' . ($votos_max_parl / 2) . '|' . $votos_max_parl . '|1:|' . $historial_tipo_parl . '" alt="Historico de Elecciones Parlamentarias"  />


</center>
-->





<h1 style="margin-bottom:5px;">Participaci&oacute;n</h1>
<center>

<img src="http://chart.apis.google.com/chart?cht=lc&chs=740x250&chls=3,1,0|3,1,0&chxt=y,r,x&chxl=0:|Votos|' . round($historial_v_max / 2) . '|' . $historial_v_max . '|1:|Participacion|50%|100%|2:|' . $historial_tipo . '&chds=0,' . $historial_v_max . ',0,100&chd=t:' . $historial_v . '|' . $historial_p . '&chf=bg,s,ffffff01|c,s,ffffff01&chco=0066FF,FF0000&chm=B,FFFFFF,0,0,0&chxs=0,0066FF,14|1,FF0000,14" alt="Historial de participacion"  /></center>

</td></tr>';
	
}

$txt .= '</table></div>';

$txt_header .= '<style type="text/css">#elec table { table-layout:fixed; } #elec td { color:grey; } #elec .dia { color:#D2D2D2; margin:0; padding:0; font-size:12px; } #elec { margin:-10px 0 0 -10px; } #elec p { margin:5px 0 2px 0; }</style>';

}

//THEME
$txt_title = 'Elecciones';
include('theme.php');
?>