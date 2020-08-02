<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$txt_title = 'Foro: Última actividad';
$txt_nav = array('/foro'=>'Foro', 'Última actividad');
$txt_tab = array('/foro'=>'Foro', '/foro/ultima-actividad'=>'Última actividad', '/control/gobierno/foro'=>'Configuración foro');

echo '<fieldset><legend>Últimos 50 mensajes</legend>

<table border="0" cellpadding="1" cellspacing="0">';

$result = mysql_query_old("SELECT ID, url FROM ".SQL."foros", $link);
while($r = mysqli_fetch_array($result)) { $sub[$r['ID']] = $r['url']; }

$result = mysql_query_old("SELECT ID, hilo_ID, user_ID, time, text, cargo, votos, votos_num,
(SELECT nick FROM users WHERE ID = m.user_ID LIMIT 1) AS nick,
(SELECT nombre FROM cargos WHERE cargo_ID = m.cargo LIMIT 1) AS encalidad,
(SELECT url FROM ".SQL."foros_hilos WHERE ID = m.hilo_ID LIMIT 1) AS hilo_url,
(SELECT title FROM ".SQL."foros_hilos WHERE ID = m.hilo_ID LIMIT 1) AS hilo_titulo,
(SELECT sub_ID FROM ".SQL."foros_hilos WHERE ID = m.hilo_ID LIMIT 1) AS sub_ID,
(SELECT voto FROM votos WHERE tipo = 'msg' AND pais = '".PAIS."' AND item_ID = m.ID AND emisor_ID = '".$pol['user_ID']."') AS voto
FROM ".SQL."foros_msg `m`
WHERE hilo_ID != '-1' AND estado = 'ok'
ORDER BY time DESC
LIMIT 50", $link);
while($r = mysqli_fetch_array($result)) {
    $result2 = mysql_query_old("SELECT acceso_leer, acceso_cfg_leer FROM ".SQL."foros WHERE ID = '".$r['sub_ID']."' LIMIT 1", $link);
    while($r2 = mysqli_fetch_array($result2)) {
        if (nucleo_acceso($r2['acceso_leer'], $r2['acceso_cfg_leer'])) {
            echo '<tr>
<td align="right" valign="top" colspan="2" nowrap="nowrap">'.print_lateral($r['nick'], $r['cargo'], $r['time'], '', $r['user_ID'], '', $r['votos'], $r['votos_num'], $r['voto'], 'msg', $r['ID']).'</td>
<td valign="top" colspan="2">
<span style="font-size:17px;"><a href="/foro/'.$sub[$r['sub_ID']].'/'.$r['hilo_url'].'"><b>'.$r['hilo_titulo'].'</b></a></span><br />

<span style="text-align:justify;font-size:15px;" class="rich">'.$r['text'].'</span>
<br /><br />
</td>
</tr>';
        }
    }
}

echo '</table></fieldset>';
