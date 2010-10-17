<?php 
include('inc-login.php');

if ((!$_GET['a']) AND ($pol['estado'] == 'ciudadanoNOOO')) {

	$txt .= '<h1>Estudios</h1>

<p>Los estudios son como <em>t&iacute;tulos</em> u <em>oposiciones</em> que <b>permiten desempe&ntilde;ar un cargo</b> o realizar una labor en '.PAIS.'.</p>

<p><b>Estudia solo lo que te gustar&iacute;a desempe&ntilde;ar</b> en un futuro. Cada estudio requiere un proceso de tiempo, pero con el suficiente podr&aacute;s estudiarlos todos si lo deseas.</p>';

	$txt .= '<table border="0" cellspacing="0" cellpadding="0" class="pol_table">
<tr>
<th></th>
<th>Estudio</th>
<th>Nivel</th>
<th>Tiempo</th>
<th></th>
</tr>';
	
	$result = mysql_query("SELECT 
ID_estudio, time
FROM ".SQL."estudios_users 
WHERE estado = 'estudiando' AND user_ID = '" . $pol['user_ID'] . "' 
LIMIT 1", $link);
	$estudiando = mysql_fetch_array($result);

	$result = mysql_query("SELECT 
ID, nombre, tiempo, nivel,
(SELECT estado FROM ".SQL."estudios_users WHERE user_ID = '" . $pol['user_ID'] . "' AND ID_estudio = ".SQL."estudios.ID LIMIT 1) AS estado
FROM ".SQL."estudios 
ORDER BY nivel DESC", $link);
	while($row = mysql_fetch_array($result)){

		$estudiar = '';
		if ($row['estado'] == 'ok') { //ya estudiado
			$estudiar = '<img src="'.IMG.'estudiado.gif" alt="Estudiado" border="0" />';
		} elseif ($row['estado'] == 'estudiando') { //estudiando

			$t_inicio = strtotime($estudiando['time']);
			$t_objetivo = $t_inicio + $row['tiempo']; //IF now > objetivo = OK
			$t_estudiado = time() - $t_inicio;
			$p_estudiado = round(($t_estudiado * 100) / $row['tiempo']);


			$estudiar = '<b>' . $p_estudiado . '% <img src="'.IMG.'estudiando.gif" alt="Estudiando" title="Estudiando..." border="0" /></b> ';
		} else { //no estudiado
			if (!$estudiando['ID_estudio']) {
				$estudiar = '<a href="/accion.php?a=estudiar&id=' . $row['ID'] . '" title="Estudiar"><img src="'.IMG.'play.gif" alt="Estudiar" border="0" /></a> ';
			}
		}

		$txt .= '<tr><td style="padding:0;" align="right">' . $estudiar . '</td><td><b>' . $row['nombre'] . '</b></td><td align="right"><b>' . $row['nivel'] . '</b></td><td align="right">' . duracion($row['tiempo']) . '</td><td><a href="/doc/ayuda-cargos/#' . str_replace(' ', '_', strtolower($row['nombre'])) . '"><b>Info</b></a></td></tr>' . "\n";
	}
	$txt .= '</table>
<p>' . boton('Detener estudios', '/accion.php?a=estudiar&b=borrar') . '</p>
<p>El <em>nivel</em> es el acceso que proporciona si tienes ese Cargo en '.PAIS.'. Por ejemplo siendo Presidente (nivel 100) podr&aacute;s crear y editar Documentos de nivel 100 e inferior.</p>';
	$txt .= '<p><a href="/cargos/"><b>Ver Cargos</b></a> &nbsp; <a href="/doc/ayuda-cargos/"><b>Ver ayuda</b></a></p>';
} else {
	$txt .= '<p><b>Los estudios se acabaron!</b> Estamos en proceso de suplantar el antiguo sistema de estudios (clic y esperar) por un sistema de examenes tipo test automatizados que puedes ver aqu&iacute; y muy pronto estar&aacute; en marcha para conseguir los "estudios o titulos".</p><p><b><a href="/examenes/">Ver examenes</a></b></p>';

}


//THEME
$txt_title = 'Estudios';
include('theme.php');
?>
