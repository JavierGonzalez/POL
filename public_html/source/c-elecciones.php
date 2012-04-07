<?php
include('inc-login.php');


$txt .= '
<table border="0" cellpadding="1" cellspacing="0">
<tr>
<th></th>
<th>Estado</th>
<th>Cuando</th>
<th></th>
<th colspan="2" title="Votos / Participación">Votos</th>
<th></th>
</tr>
';


	$result = mysql_query("SELECT * 
FROM cargos
WHERE pais = '".PAIS."' AND elecciones IS NOT NULL
ORDER BY elecciones DESC
LIMIT 50", $link);
	while($r = mysql_fetch_array($result)) {
		$time_start = strtotime($r['elecciones']);
		$time_anterior = strtotime($r['elecciones'])-($r['elecciones_cada']*24*60*60);

		$txt .= '<tr>
<td align="right" width="320"><b style="font-size:16px;">Elecciones a '.$r['nombre'].'</b></td>

<td nowrap="nowrap">Próximamente...</td>

<td>En '.timer($time_start, true).'</td>

<td>'.gbarra(((time()-$time_anterior)*100)/($time_start-$time_anterior)).'</td>

<td></td>
<td></td>
<td></td>
</tr>';
}






	// ELECCIONES EXISTENTES
	$result = mysql_query("SELECT * 
FROM votacion
WHERE tipo = 'elecciones' AND pais = '".PAIS."'
ORDER BY time_expire DESC
LIMIT 1000", $link);
	while($r = mysql_fetch_array($result)) {
		$time_expire = strtotime($r['time_expire']);
		$time = strtotime($r['time']);

		$txt .= '<tr>
<td align="right" width="320"><b style="font-size:16px;">'.$r['pregunta'].'</b></td>

<td nowrap="nowrap">'.($r['estado']=='ok'?'<b>¡En curso!</b>':'Finalizada').'</td>

<td>'.($r['estado']=='ok'?'Quedan '.timer($time_expire, true):'Hace '.timer($time_expire, true)).'</td>

<td>'.($r['estado']=='ok'?gbarra(((time()-$time)*100)/($time_expire-$time)):'').'</td>


<td align="right" title="Votos / Participación"'.($r['estado']=='ok'?' style="font-style:italic;"':'').'><b>'.num($r['num']).'</b></td>
<td align="right">'.num(($r['num']*100)/$r['num_censo'], 2).'%</td>

<td>'.($r['estado']=='end'?'<button class="small blue" onclick="$(\'#escrutinio_'.$r['ID'].'\').toggle(\'slow\');">Ver resultados</button>':'').'</td>

</tr>
<tr id="escrutinio_'.$r['ID'].'" style="display:none;">
<td colspan="7">';

if ($r['estado'] == 'end') {
	$txt .= '<table border="0"><tr><td valign="top"><img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.round(($r['num']*100)/$r['num_censo'], 2).','.round(100-(($r['num']*100)/$r['num_censo']), 2).'&chs=300x160&chds=a&chl=Participación|Abstención&chf=bg,s,ffffff01|c,s,ffffff01&chp=3.14" alt="Participación" /></td>';
	
	$txt .= '<td valign="top"><table border="0"><tr><th></th><th>Candidato</th><th>Puntos</th></tr>';
	$cnum = 0;
	$escrutinio_d = array();
	$elecciones_electos = explodear('|', $r['ejecutar'], 2);
	foreach (explode(':', explodear('|', $r['ejecutar'], 3)) AS $d) {
		$d = explode('.', $d);
		if ($d[2] != 'B') {
			$cnum++;
			$txt .= '<tr'.($cnum<=$elecciones_electos?' style="font-weight:bold;"':'').'><td align="right">'.($d[2]&&$d[2]!='COORDINACION'?$d[2]:'').'</td><td>'.crear_link($d[0]).'</td><td align="right">'.$d[1].'</td></tr>';
			$escrutinio_d[] = $d[1];
		}
	}
	$txt .= '</table></td>
<td valign="top"><img style="margin-top:25px;" src="https://chart.googleapis.com/chart?cht=bhs&chs=200x'.($cnum*28).'&chd=t:'.implode(',', $escrutinio_d).'&chds=a&chf=bg,s,ffffff01|c,s,ffffff01" alt="Escrutinio" /></td>
</tr></table>';
}

$txt .= '
</td>
</tr>
';
	}

	$txt .= '
<!--<tr>
<td colspan="7" align="center">
<img src="http://chart.apis.google.com/chart?cht=lc&chs=740x250&chls=3,1,0|3,1,0&chxt=y,r,x&chxl=0:|Votos|' . round($historial_v_max / 2) . '|' . $historial_v_max . '|1:|Participacion|50%|100%|2:|' . $historial_tipo . '&chds=0,' . $historial_v_max . ',0,100&chd=t:' . $historial_v . '|' . $historial_p . '&chf=bg,s,ffffff01|c,s,ffffff01&chco=0066FF,FF0000&chm=B,FFFFFF,0,0,0&chxs=0,0066FF,14|1,FF0000,14" alt="Historial de participacion"  />
</td>
</tr>-->
</table>';






//THEME
$txt_title = 'Elecciones';
$txt_nav = array('/elecciones'=>'Elecciones');
$txt_menu = 'demo';
include('theme.php');
?>