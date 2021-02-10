<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$result = mysql_query_old("SELECT * FROM ".SQL."foros WHERE url = '" . $_GET[1] . "' AND estado = 'ok' LIMIT 1", $link);
while($r = mysqli_fetch_array($result)) {
    if (nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) {
        $return_url = 'foro/'.$r['url'].'/';
        
        $txt_title = 'Foro: '.$r['title'].' - '.$r['descripcion'];
        $txt_nav = array('/foro'=>'Foro', $r['title']);
        $txt_tab = array('/foro/'=>'Foro', '/foro/ultima-actividad/'=>'Última actividad', '/control/gobierno/foro/'=>'Configuración foro', );

        if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) { $txt_tab = array('#enviar'=>'Crear hilo'); }

        if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) { $crear_hilo = '#enviar'; } else { $crear_hilo = ''; }

        echo '

<table border="0" cellpadding="1" cellspacing="0">
<tr>
<th>Autor</th>
<th colspan="2">Mensajes</th>
<th>Hilo</th>
<th>Creado</th>
<th></th>
</tr>';
        $result2 = mysql_query_old("SELECT ID, url, user_ID, title, time, time_last, cargo, num, sub_ID, votos, votos_num,fecha_programado,
(SELECT nick FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS nick,
(SELECT estado FROM users WHERE ID = ".SQL."foros_hilos.user_ID LIMIT 1) AS estado
FROM ".SQL."foros_hilos
WHERE sub_ID = '" . $r['ID'] . "' AND estado = 'ok'
ORDER BY time_last DESC
LIMIT 200", $link);
        while($r2 = mysqli_fetch_array($result2)) {

            if ($r2['fecha_programado'] == '' OR ($r2['fecha_programado'] != '' AND $r2['user_ID'] == $pol['user_ID'])) {
                if (strtotime($r2['time']) < (time() - 432000)) { 
                    $titulo = '<a href="/foro/' . $r['url'] . '/' . $r2['url'] . '">' . $r2['title'] . '</a>'; 
                } else { 
                    $titulo = '<a href="/foro/' . $r['url'] . '/' . $r2['url'] . '"><b>' . $r2['title'] . '</b></a>'; 
                }
                if (strtotime($r2['time']) > (time() - 86400)) { $titulo = $titulo . ' <sup style="font-size:9px;color:red;">¡Nuevo!</sup>'; }

                if (($pol['user_ID'] == $r2['user_ID']) AND (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir']))) { 
                    $editar = ' '.boton('X', '/accion/foro/eliminarhilo?ID='.$r2['ID'], '¿Estás seguro de querer ELIMINAR este HILO?', 'small red'); 
                } else { $editar = ''; }

                echo '<tr>
<td align="right">'.crear_link($r2['nick']).'</td>
<td align="right"><b>'.$r2['num'].'</b></td>
<td align="right" style="padding-right:4px;">'.confianza($r2['votos'], $r2['votos_num']).'</td>
<td>'.$titulo.' '.($r2['fecha_programado'] != 0 ? '<i class="far fa-clock"></i>' : '') .'</td>
<td align="right"><span class="timer" value="'.strtotime($r2['time']).'"></span></td>
<td>'.$editar.'</td>
</tr>';
            }
        }
        echo '</table><br />';
        if (nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])) { echo foro_enviar($r['ID']); }
    } else { echo '<p><b style="color:red;">No tienes acceso de lectura a este subforo.</b></p>'; }
}