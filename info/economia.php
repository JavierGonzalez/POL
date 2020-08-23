<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



echo '<h1 class="quitar">'.MONEDA.' '._('Economía Global').':</h1>';
$txt_title = _('Economia Global');
$txt_nav = array('/info/economia'=>_('Economía global'));
$txt_menu = 'econ';

// Obtiene colores de background de paises
$result = sql_old("SELECT valor, pais FROM config WHERE dato = 'bg_color'");
while ($r = r($result)) { $vp['bg'][$r['pais']] = $r['valor']; }


// #CUADRAR
// 11 AGOSTO 2010: 544.645 | 554.528 | 674.518
// 28 AGOSTO 2011: 883.003
// jajajjaja inflación non-stop, los usuarios pidieron insistentemente ampliar la masa monetaria sucesivamente, dificil no ceder. Fail.
// 31 AGOSTO 2019: 100.000
// 23 AGOSTO 2020:  98.660 (bug)

$moneda_mundial = '98660';


echo '<br /><table border="0" cellspacing="0" cellpadding="2">
<tr>
<th colspan="3" style="background:#B2FF99;" align="center">'._('Información').'</th>
<th colspan="4" style="background:#FFB266;" align="center">'._('Gobierno').'</th>
<th colspan="2" style="background:#99B2FF;" align="center">'._('Promedios').'</th>
<th colspan="5" style="background:#FFFF99;" align="center">'._('Contabilidad').'</th>
</tr>

<tr>
<th style="background:#B2FF99;">'._('País').'</th>
<th style="background:#B2FF99;"><acronym title="Numero de ciudadanos.">'._('Población').'</acronym></th>
<th style="background:#B2FF99;"><acronym title="Total de deudas personales, dinero en negativo.">'._('Deuda').'</acronym></th>

<th style="background:#FFB266;">'._('Arancel').'</th>
<th style="background:#FFB266;" colspan="2">'._('Impuestos').'</th>
<th style="background:#FFB266;"><acronym title="Pago por dia de actividad">'._('Subsidio').'</acronym></th>

<th style="background:#99B2FF;"><acronym title="Salario medio">'._('Salario').'</acronym></th>
<th style="background:#99B2FF;"><acronym title="Patrimonio medio por ciudadano.">'._('Patrimonio').'</acronym></th>


<th style="background:#FFFF99;" colspan="2">'._('Personal').'</th>
<th style="background:#FFFF99;" colspan="2">'._('Gobierno').'</th>
<th style="background:#FFFF99;">'._('Total').' '.MONEDA.'</th>

</tr>';

$result0 = mysql_query_old("SELECT pais FROM config WHERE dato = 'ECONOMIA' AND valor = 'true'");
while($r0 = mysqli_fetch_array($result0)) {
    $pais = $r0['pais'];

$result = mysql_query_old("SELECT SUM(pols + IFNULL((SELECT SUM(pols) FROM cuentas WHERE pais = '".$pais."' AND user_ID = users.ID GROUP BY user_ID),0)) AS pols_ciudadanos,
(SELECT COUNT(ID) FROM users WHERE pais = '".$pais."' AND estado = 'ciudadano') AS num_ciudadanos,
(SELECT SUM(pols) FROM cuentas WHERE pais = '".$pais."' AND nivel > 0) AS pols_gobierno,
(SELECT SUM(pols) FROM users WHERE pais = '".$pais."' AND pols < 0) AS pols_negativo,
(SELECT valor FROM config WHERE pais = '".$pais."' AND dato = 'arancel_salida' LIMIT 1) AS arancel_salida,
(SELECT valor FROM config WHERE pais = '".$pais."' AND dato = 'impuestos' LIMIT 1) AS impuestos,
(SELECT valor FROM config WHERE pais = '".$pais."' AND dato = 'impuestos_minimo' LIMIT 1) AS impuestos_minimo,
(SELECT valor FROM config WHERE pais = '".$pais."' AND dato = 'pols_inem' LIMIT 1) AS inem,
(SELECT AVG(salario) FROM cargos WHERE pais = '".$pais."') AS salario_medio
FROM users
WHERE pais = '".$pais."'");
while($r = mysqli_fetch_array($result)) {


    $result2 = mysql_query_old("SELECT nick, pais,
(pols + IFNULL((SELECT SUM(pols) FROM cuentas WHERE pais = '".$pais."' AND user_ID = users.ID GROUP BY user_ID),0)) AS pols_total
FROM users
WHERE pais = '".$pais."'
ORDER BY pols_total DESC 
LIMIT 25", $link);
    while ($r2 = mysqli_fetch_array($result2)) {
        $ricos[$r2['nick'].':'.$r2['pais']] = $r2['pols_total'];
    }



    $total += $r['pols_ciudadanos'] + $r['pols_gobierno'];

    $total_pais[$pais] = $r['pols_ciudadanos']+$r['pols_gobierno'];

    echo '<tr>
<td style="background:'.$vp['bg'][$pais].';"><a href="http://'.strtolower($pais).'.'.DOMAIN.'/"><b>'.$pais.'</b></a></td>
<td align="right"><b>'.$r['num_ciudadanos'].'</b></td>
<td align="right">'.pols($r['pols_negativo']).'</td>

<td align="right" style="color:red;"><b>'.$r['arancel_salida'].'%</b></td>';


if ($r['impuestos'] > 0) {
echo '<td><b>'.$r['impuestos'].'%</b></td><td align="right">'.pols($r['impuestos_minimo']).'</td>';
} else {
echo '<td colspan="2">'._('Sin impuestos').'</td>';
}


echo '<td align="right">'.pols($r['inem']).'</td>

<td align="right">'.pols($r['salario_medio']).'</td>
<td align="right">'.($r['num_ciudadanos']>0?pols(round($r['pols_ciudadanos']/$r['num_ciudadanos'])):0).'</td>

<td align="right">'.pols($r['pols_ciudadanos']).'</td>
<td>+</td>
<td align="right">'.pols($r['pols_gobierno']).'</td>
<td>=</td>
<td align="right">'.pols($r['pols_ciudadanos']+$r['pols_gobierno']).'</td>
</tr>';

}

// GEN GRAFICO VISITAS
$n = 0;
$result = mysql_query_old("SELECT pols, pols_cuentas FROM stats WHERE pais = '".$pais."' ORDER BY time DESC LIMIT 9", $link);
while($r = mysqli_fetch_array($result)){
    if ($gph[$pais]) { $gph[$pais] = ',' . $gph[$pais]; }
    $gph_maxx[$n] += $r['pols'] + $r['pols_cuentas'];
    $gph[$pais] = $r['pols'] + $r['pols_cuentas'] . $gph[$pais];
    if ($gph_maxx[$n] > $gph_max) { $gph_max = $gph_maxx[$n]; }
    $n++;
}
}

$result = mysql_query_old("SELECT SUM(pols) AS pols_total FROM users WHERE pais = 'ninguno'");
while($r = mysqli_fetch_array($result)) { $pols_turistas = $r['pols_total']; }

$total_moneda = $total+$pols_turistas;

if (($total_moneda) == $moneda_mundial) {
    $cuadrar = ' <acronym title="Las cuentas cuadran. No se ha creado ni destruido dinero." style="color:blue;">'._('OK').'</acronym>';
} else {
    $cuadrar = ' <acronym title="Las cuentas no cuadran. Se ha creado o destruido dinero desde la ultima revision." style="color:red;">'._('ERROR').'</acronym>: '.pols($total_moneda-$moneda_mundial).' '.MONEDA;
}


echo '
<tr>
<td colspan="12" align="right">'._('Sin ciudadanía').': '.pols($pols_turistas).'</td>
<td>+</td>
<td style="font-size:18px;" align="right">'.pols($total_moneda).'</td>
<td>'.MONEDA.$cuadrar.'</td>
</tr>


<tr>
<td colspan="3" valign="top">

<h2>'._('Los más ricos').':</h2><ol>';

arsort($ricos);
$extra = '';
foreach ($ricos AS $info => $pols_total) {
$num++;
if (($pols_total > 0) AND ($num <= 25)) {
    $nick = explodear(':', $info, 0);
    $pais = explodear(':', $info, 1);
    // $extra = pols($pols_total).' ';
    echo '<li>'.MONEDA.' <b class="big">'.$extra.''.crear_link($nick, 'nick', 'ciudadano', $pais).'</b></li>';
}
}

echo '</ol>


</td>

<td colspan="6" valign="top">

<h2>'._('Deudores').':</h2><ol>';

$result = mysql_query_old("SELECT pols, pais, nick FROM users WHERE pols < 0 ORDER BY pols ASC");
while($r = mysqli_fetch_array($result)) {
echo '<li>'.pols($r['pols']).' '.MONEDA.' <b class="big">'.crear_link($r['nick'], 'nick', 'ciudadano', $r['pais']).'</b></li>';
}

echo '</ol>
<span style="color:#888;">'._('No contabiliza el dinero en cuentas bancarias').'.</span>

</td>


<td align="center" colspan="6" valign="top">
<h2>'._('Reparto económico').':</h2><br />
<img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.round(($total_pais['RSSV']*100)/$total_moneda).','.round(($total_pais['Hispania']*100)/$total_moneda).'&chs=300x190&chl=RSSV|Hispania&chco='.substr($vp['bg']['RSSV'],1).','.substr($vp['bg']['Hispania'],1).'" alt="Reparto economico." />

<br /><br />

<h2>'._('Evolución de la economía').':</h2><br />

<img src="http://chart.apis.google.com/chart?cht=lc
&chs=330x350
&cht=bvs
&chco='.substr($vp['bg']['RSSV'],1).','.substr($vp['bg']['Hispania'],1).'
&chd=t:'.$gph['RSSV'].','.$total_pais['RSSV'].'|'.$gph['Hispania'].','.$total_pais['Hispania'].'
&chds=0,'.$moneda_mundial.'
&chxt=r
&chxl=0:||'.round($moneda_mundial / 2).'|'.$moneda_mundial.'
" alt="Monedas" />

</td>

</tr>

<tr>
<td align="center" colspan="15">('._('zona común entre países').')</td>
</tr>
</table>';
