<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


$txt .= '
<table border="0" cellpadding="1" cellspacing="0">
<tr>
<th></th>
<th>'._('Estado').'</th>
<th>'._('Cuando').'</th>
<th></th>
<th colspan="2"></th>
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
<td align="right" width="320"><b style="font-size:16px;">'._('Elecciones a').' '.$r['nombre'].'</b></td>

<td nowrap="nowrap"><em>'._('Próximamente').'...</em></td>

<td>'._('En').' '.timer($time_start, true).'</td>

<td colspan="4">'.gbarra(((time()-$time_anterior)*100)/($time_start-$time_anterior)).'</td>

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
	if ($r['estado'] == 'end') { $n++; }
	$txt .= '<tr>
<td align="right" width="320"><b style="font-size:16px;">'.($time>=strtotime('2012-04-05')?'<a href="/votacion/'.$r['ID'].'">'.$r['pregunta'].'</a>':$r['pregunta']).'</b></td>

<td nowrap="nowrap">'.($r['estado']=='ok'?'<b>¡'._('En curso').'!</b>':_('Finalizada')).'</td>

<td>'.($r['estado']=='ok'?_('Quedan').' '.timer($time_expire, true):_('Hace').' '.timer($time_expire, true)).'</td>

<td>'.($r['estado']=='ok'?gbarra(((time()-$time)*100)/($time_expire-$time)):'').'</td>


<td align="right" title="Votos / Participación"'.($r['estado']=='ok'?' style="font-style:italic;"':'').'><b>'.num($r['num']).'</b> votos</td>
<td align="right">'.num(($r['num']*100)/$r['num_censo'], 2).'%</td>

<td>'.($r['estado']=='end'?'<button class="small blue" onclick="$(\'#escrutinio_'.$r['ID'].'\').toggle(\'slow\');">'._('Ver resultado').'</button>':'').'</td>

</tr>
<tr id="escrutinio_'.$r['ID'].'"'.($n==1?'':' style="display:none;"').'>
<td colspan="7">';

	if ($r['estado'] == 'end') {
		$txt .= '<table border="0"><tr><td valign="top"><img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.round(($r['num']*100)/$r['num_censo'], 2).','.round(100-(($r['num']*100)/$r['num_censo']), 2).'&chs=300x160&chds=a&chl='._('Participación').'|'._('Abstención').'&chf=bg,s,ffffff01|c,s,ffffff01&chp=3.14" alt="'._('Participación').'" /></td>';
		
		$txt .= '<td valign="top"><table border="0"><tr><th></th><th>'._('Candidato').'</th><th>'._('Puntos').'</th></tr>';
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
<td valign="top"><img style="margin-top:25px;" src="https://chart.googleapis.com/chart?cht=bhs&chs=200x'.($cnum*28).'&chd=t:'.implode(',', $escrutinio_d).'&chds=a&chf=bg,s,ffffff01|c,s,ffffff01" alt="'._('Escrutinio').'" /></td>
</tr></table>';
	}

	$txt .= '</td></tr>';
}

$txt .= '</table>';


//THEME
$txt_title = _('Elecciones');
$txt_nav = array('/elecciones'=>_('Elecciones'));
$txt_menu = 'demo';
include('theme.php');
?>