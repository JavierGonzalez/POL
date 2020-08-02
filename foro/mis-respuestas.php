<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


if ($_GET[1]) {
    $result = mysql_query_old("SELECT ID, nick FROM users WHERE nick = '".$_GET[1]."' LIMIT 1", $link);
    while($r = mysqli_fetch_array($result)) {
        $el_nick = $r['nick'];
        $el_user_ID = $r['ID'];
    }
} elseif ($pol['user_ID']) { 
    $el_user_ID = $pol['user_ID']; 
}
if (!isset($el_user_ID)) { exit; }

$txt_title = 'Foro - Actividad';
$txt_nav = array('/foro'=>'Foro', 'Tu actividad');
$txt_tab = array('/foro/'=>'Foro', '/foro/ultima-actividad/'=>'Última actividad', '/control/gobierno/foro/'=>'Configuración foro', );

echo '<fieldset><legend>Últimos hilos</legend>

<table border="0" cellpadding="1" cellspacing="0">';

$result = mysql_query_old("SELECT ID, url FROM ".SQL."foros", $link);
while($r = mysqli_fetch_array($result)) { $sub[$r['ID']] = $r['url']; }

$result = mysql_query_old("SELECT h.ID, h.cargo, h.time, h.votos, h.votos_num, h.num, h.sub_ID, h.url, h.title, h.text, u.nick
FROM ".SQL."foros_hilos `h`
LEFT JOIN users `u` ON (u.ID = h.user_ID)
WHERE user_ID = '".$el_user_ID."'
ORDER BY h.time DESC
LIMIT 10", $link);
while($r = mysqli_fetch_array($result)) {
    echo '<tr><td align="right" valign="top" colspan="2">' . print_lateral($r['nick'], $r['cargo'], $r['time'], '', $pol['user_ID'], '', $r['votos'], $r['votos_num'], false, 'hilos', $r['ID']) . '</td><td align="right" valign="top"><b style="font-size:20px;">'.$r['num'].'</b></td><td valign="top" colspan="2" nowrap="nowrap" style="color:grey;"><a href="/foro/' . $sub[$r['sub_ID']] . '/' . $r['url'] . '/"><b>' . $r['title'] . '</b></a><br />' . substr(strip_tags($r['text']), 0, 90) . '..</td></tr>';
}


echo '</table></fieldset>


<fieldset><legend>Últimos mensajes</legend>

<table border="0" cellpadding="1" cellspacing="0">';

$result = mysql_query_old("SELECT ID, url FROM ".SQL."foros", $link);
while($r = mysqli_fetch_array($result)) { $sub[$r['ID']] = $r['url']; }

$result = mysql_query_old("SELECT ID, hilo_ID, user_ID, time, text, cargo, votos, votos_num,
(SELECT nick FROM users WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT nombre FROM cargos WHERE cargo_ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad,
(SELECT url FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_url,
(SELECT title FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS hilo_titulo,
(SELECT sub_ID FROM ".SQL."foros_hilos WHERE ID = ".SQL."foros_msg.hilo_ID LIMIT 1) AS sub_ID
FROM ".SQL."foros_msg
WHERE hilo_ID != '-1' AND user_ID = '".$el_user_ID."'
ORDER BY time DESC
LIMIT 50", $link);
while($r = mysqli_fetch_array($result)) {

    $result2 = mysql_query_old("SELECT COUNT(*) AS resp_num FROM ".SQL."foros_msg WHERE hilo_ID = '".$r['hilo_ID']."' AND time > '".$r['time']."'", $link);
    while($r2 = mysqli_fetch_array($result2)) {
        $resp_num = $r2['resp_num'];
    }

    if (!$repes[$r['hilo_ID']]) {
        $repes[$r['hilo_ID']] = true;
        echo '<tr><td align="right" valign="top" colspan="2">' . print_lateral($r['nick'], $r['cargo'], $r['time'], '', $pol['user_ID'], '', $r['votos'], $r['votos_num'], false, 'msg', $r['ID']) . '</td><td align="right" valign="top"><acronym title="Nuevos mensajes"><b style="font-size:20px;">'.$resp_num.'</b></acronym></td><td valign="top" colspan="2" nowrap="nowrap" style="color:grey;"><a href="/foro/'.$sub[$r['sub_ID']].'/'.$r['hilo_url'].'"><b>'.$r['hilo_titulo'].'</b></a><br /><span title="Mensajes después del tuyo">(<b style="font-size:18px;">'.$resp_num.'</b> nuevos)</span> '.substr(strip_tags($r['text']), 0, 90).'..</td></tr>';
    }
}

echo '</table></fieldset>';