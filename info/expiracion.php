<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 





$margen_15dias	= date('Y-m-d 20:00:00', time() - 1296000); // 15 dias
$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); // 30 dias
$margen_90dias	= date('Y-m-d 20:00:00', time() - 7776000); // 90 dias

echo '
<table><tr><td valign="top"><h2>30 '._('días').'</h2>


<table border="0">
<tr>
<th>#</th>
<th>'._('Días').'</th>
<th>'._('Ciudadanos').'</th>
</tr>';
$dias = 1;
$result = mysql_query_old("SELECT fecha_last, COUNT(*) AS num, DAY(fecha_last) AS day 
FROM users
WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_registro > '".$margen_30dias."'
GROUP BY day
ORDER BY fecha_last DESC", $link);
while($r = mysqli_fetch_array($result)) { 
    echo '<tr><td align="right">'.$dias++.'</td><td align="right">'.$r['day'].'</td><td align="right"><b>'.$r['num'].'</b></td></tr>'; 
}
echo '</table>


</td><td>&nbsp;&nbsp;&nbsp;</td><td valign="top"><h2>'._('Total').'</h2>


<table border="0">
<tr>
<th>#</th>
<th>'._('Día').'</th>
<th>'._('Ciudadanos').'</th>
</tr>';

$dias = 1;
$result = mysql_query_old("SELECT fecha_last, COUNT(*) AS num, DAY(fecha_last) AS day 
FROM users
WHERE estado = 'ciudadano' AND pais = '".PAIS."'
GROUP BY day
ORDER BY fecha_last DESC", $link);
while($r = mysqli_fetch_array($result)) { 
    echo '<tr><td align="right">'.$dias++.'</td><td align="right">'.$r['day'].'</td><td align="right"><b>'.$r['num'].'</b></td></tr>'; 
}
echo '</table>
</td></tr></table>';