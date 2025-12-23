<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$txt_title = 'Papelera';
$txt_nav = array('/foro'=>'Foro', '/foro/papelera'=>'Papelera');
$txt_tab = array('/foro'=>'Foro', '/foro/ultima-actividad'=>'Última actividad', '/control/gobierno/foro'=>'Configuración foro');

echo '<fieldset><legend>Hilos borrados</legend>

<table border="0" cellpadding="1" cellspacing="0">';

$result = mysql_query_old("SELECT ID, sub_ID, user_ID, url, title, time, time_last, text, cargo, num, votos, votos_num,
(SELECT nick FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND ID = users.partido_afiliado LIMIT 1) FROM users WHERE ID = ".SQL."foros_hilos.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM cargos WHERE cargo_ID = ".SQL."foros_hilos.cargo LIMIT 1) AS encalidad
FROM ".SQL."foros_hilos
WHERE estado = 'borrado'
ORDER BY time_last DESC", $link);
while($r = mysqli_fetch_array($result)) {
    if (nucleo_acceso($vp['acceso']['foro_borrar'])) { $boton = boton('Restaurar', '/accion/foro/restaurar/hilo?ID=' . $r['ID'], '¿Quieres RESTAURAR este HILO y sus MENSAJES?'); } else { $boton = boton('Restaurar'); }

    echo '<tr><td align="right" valign="top">' . print_lateral($r['nick'], $r['cargo'], $r['time'], $r['siglas'], $r['user_ID'], $r['avatar'], $r['votos'], $r['votos_num'], false, 'hilos') . '</td><td valign="top"><p class="pforo"><b style="color:blue;">' . $r['title'] . '</b><br />' . $r['text'] . '</p></td><td valign="top" nowrap="nowrap"><acronym title="' . $r['time_last'] . '"><span class="timer" value="'.strtotime($r['time_last']).'"></span></acronym></td><td valign="top">' . $boton . '</td></tr>';
}

echo '</table></fieldset>


<fieldset><legend>Mensajes borrados</legend>

<table>';



$result = mysql_query_old("SELECT ID, hilo_ID, user_ID, time, time2, text, cargo, votos, votos_num,
(SELECT nick FROM users WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS nick,
(SELECT avatar FROM users WHERE ID = ".SQL."foros_msg.user_ID LIMIT 1) AS avatar,
(SELECT (SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND ID = users.partido_afiliado LIMIT 1) FROM users WHERE ID = ".SQL."foros_msg.user_ID AND partido_afiliado != '0' LIMIT 1) AS siglas,
(SELECT nombre FROM cargos WHERE cargo_ID = ".SQL."foros_msg.cargo LIMIT 1) AS encalidad
FROM ".SQL."foros_msg
WHERE estado = 'borrado'
ORDER BY time2 DESC", $link);
while($r = mysqli_fetch_array($result)) {
    if (nucleo_acceso($vp['acceso']['foro_borrar'])) { $boton = boton('Restaurar', '/accion/foro/restaurar/mensaje?ID=' . $r['ID'], '¿Quieres RESTAURAR este MENSAJE?'); } else { $boton = boton('Restaurar'); }

    echo '<tr><td align="right" valign="top">' . print_lateral($r['nick'], $r['cargo'], $r['time'], $r['siglas'], $r['user_ID'], $r['avatar'], $r['votos'], $r['votos_num'], false) . '</td><td valign="top"><p class="pforo">' . $r['text'] . '</p></td><td valign="top" nowrap="nowrap"><acronym title="' . $r['time2'] . '"><span class="timer" value="'.strtotime($r['time2']).'"></span></acronym></td><td valign="top">' . $boton . '</td></tr>';
}


echo '</table></fieldset><p class="gris">Los mensajes se eliminarán tras 10 días.</p>';

$txt_header = '<style type="text/css">.content-in hr { border: 1px solid grey; } .flateral { margin:0 0 0 5px; float:right; } .pforo { text-align:justify; font-size:11px; margin:2px; }</style>';

