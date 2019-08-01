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

<tr><td colspan="8" align="center">

<table><tr>';

	$result = sql("SELECT cargo_ID, nombre FROM cargos WHERE pais = '".PAIS."' AND elecciones IS NOT NULL ORDER BY nivel DESC");
	while($r = r($result)) {
		$txt .= '<td align="center" title="'._('Elecciones a').' '.$r['nombre'].'"><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" width="16" height="16" /><br /><input type="checkbox" onclick="$(\'.cargo_'.$r['cargo_ID'].'\').toggle();$(\'.cargo_'.$r['cargo_ID'].'_info\').hide();" checked="checked" /></td>';
	}

// <input type="checkbox" onclick="$(\'.futuro\').toggle();" />
$txt .= '</tr></table>


</td></tr>

<tr>
<th></th>
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
ORDER BY elecciones ASC
LIMIT 50", $link);
while($r = mysql_fetch_array($result)) {
	$time_start = strtotime($r['elecciones']);
	$time_anterior = strtotime($r['elecciones'])-($r['elecciones_cada']*24*60*60);

	$txt .= '<tr class="futuro cargo_'.$r['cargo_ID'].'">
<td align="right" width="320" style="font-size:16px;'.($r['asigna']==0?'font-weight:bold;':'').'" class="gris">'._('Elecciones a').' '.$r['nombre'].'</td>

<td><a href="/cargos/'.$r['cargo_ID'].'"><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" width="16" height="16" /></a></td>

<td nowrap="nowrap"><em>'._('Próximamente').'...</em></td>

<td>'._('En').' '.timer($r['elecciones']).'</td>

<td colspan="4">'.gbarra(((time()-$time_anterior)*100)/($time_start-$time_anterior), 80).'</td>

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
	$txt .= '<tr class="cargo_'.$r['cargo_ID'].'">
<td align="right" nowrap="nowrap"><b style="font-size:16px;">'.($time>=strtotime('2012-04-05')?'<a href="/votacion/'.$r['ID'].'">'.$r['pregunta'].'</a>':$r['pregunta']).'</b></td>

<td><a href="/cargos/'.$r['cargo_ID'].'"><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" width="16" height="16" /></a></td>

<td nowrap="nowrap">'.($r['estado']=='ok'?'<b>¡'._('En curso').'!</b>':_('Finalizada')).'</td>

<td>'.($r['estado']=='ok'?_('Quedan').' '.timer($time_expire, true):_('Hace').' '.timer($time_expire, true)).'</td>

<td>'.($r['estado']=='ok'?gbarra(((time()-$time)*100)/($time_expire-$time), 80):'').'</td>


<td align="right" title="Votos / Participación"'.($r['estado']=='ok'?' style="font-style:italic;"':'').'><b>'.num($r['num']).'</b> '._('votos').'</td>
<td align="right">'.num(($r['num_censo']>0?($r['num']*100)/$r['num_censo']:0), 2).'%</td>

<td>'.($r['estado']=='end'?'<button class="small blue" onclick="$(\'#escrutinio_'.$r['ID'].'\').toggle(\'slow\');">'._('Ver resultado').'</button>':'').'</td>

</tr>
<tr id="escrutinio_'.$r['ID'].'" class="cargo_'.$r['cargo_ID'].'_info"'.($n==1?'':' style="display:none;"').'>
<td colspan="7">';

	if ($r['estado'] == 'end') {
		$txt .= '<table border="0"><tr><td valign="top"><img src="//chart.googleapis.com/chart?cht=p&chd=t:'.($r['num_censo']>0?round(($r['num']*100)/$r['num_censo'], 2).','.round(100-(($r['num']*100)/$r['num_censo']), 2):'1,1').'&chs=300x160&chds=a&chl='._('Participación').'|'._('Abstención').'&chf=bg,s,ffffff01|c,s,ffffff01&chp=3.14" alt="'._('Participación').'" /></td>';
		
		$txt .= '<td valign="top"><table border="0"><tr><th></th><th>'._('Candidato').'</th><th>'._('Puntos').'</th></tr>';
		$cnum = 0;
		$escrutinio_d = array();
		$elecciones_electos = explodear('|', $r['ejecutar'], 2);
		foreach (explode(':', explodear('|', $r['ejecutar'], 3)) AS $d) {
			$d = explode('.', $d);
			if ($d[2] != 'B') {
				$cnum++;
				$txt .= '<tr><td align="right" nowrap>'.($d[2]&&$d[2]!='COORDINACION'?$d[2]:'').'</td><td nowrap'.($cnum<=$elecciones_electos?' style="font-weight:bold;"':'').'>'.($cnum<=$elecciones_electos?'<img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" width="16" height="16" style="margin-top:-5px;" /> ':'').''.crear_link($d[0]).'</td><td align="right"><b>'.$d[1].'</b></td></tr>';
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
$txt_tab = array('/cargos'=>'Cargos', '/cargos/organigrama'=>_('Organigrama'), '/elecciones'=>_('Elecciones'));
$txt_menu = 'demo';
include('theme.php');
?>