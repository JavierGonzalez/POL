<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$result = mysql_query_old("SELECT h.ID, sub_ID, user_ID, h.url, h.title, h.time, time_last, h.text, h.cargo, num, u.nick, u.estado, u.avatar, acceso_leer, acceso_escribir, acceso_escribir_msg, acceso_cfg_leer, acceso_cfg_escribir, acceso_cfg_escribir_msg, votos, votos_num, v.voto, f.title AS foro_title, f.url AS foro_url, f.descripcion
FROM ".SQL."foros_hilos `h`
LEFT JOIN ".SQL."foros `f` ON (f.ID = sub_ID)
LEFT JOIN users `u` ON (u.ID = user_ID)
LEFT JOIN votos `v` ON (tipo = 'hilos' AND v.pais = '".PAIS."' AND item_ID = h.ID AND emisor_ID = '".$pol['user_ID']."')
WHERE h.url = '".$_GET[1]."' AND h.estado = 'ok'
LIMIT 1", $link);
while($r = mysqli_fetch_array($result)) {

    // Foro incorrecto? redireccion.
    if ($_GET[0] != $r['foro_url']) { 
        redirect('/foro/'.$r['foro_url'].'/'.$r['url'].'/');
    }
    

    $acceso['leer'] = nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer']);
    $acceso['escribir_msg'] = nucleo_acceso($r['acceso_escribir_msg'], $r['acceso_cfg_escribir_msg']);

    if ($acceso['leer']) {

        $subforo = $_GET[0];
        $return_url = 'foro/' . $subforo . '/' . $r['url'] . '/';
        paginacion('hilo', '/'.$return_url, $r['ID'], $_GET[2], $r['num']);
        
        if ($_GET[2]) { $pag_title = ' - Página: '.$_GET[2]; }
        
        $txt_title = $r['title'].' - Foro: '.$r['foro_title'].$pag_title;
        $txt_nav = array('/foro'=>'Foro', '/foro/'.$r['foro_url']=>$r['foro_title'], $r['title']);

        $txt_description = $r['title'].' - Foro: '.$r['foro_title'].$pag_title;


        // acceso
        if ($acceso['escribir_msg']) { $crear_hilo = '#enviar'; $citar = '<div class="citar">'.boton('Citar', '/'.$return_url.'1/-'.$r['ID'].'#enviar', false, 'small pill').'</div>'; } else { $crear_hilo = ''; }


        echo '
<table border="0" cellpadding="2" cellspacing="0" style="margin-top:5px;">';

        if (($pol['user_ID'] == $r['user_ID']) AND ($subforo != 'notaria')) { 
            // es tu post
            $editar = '<span style="float:right;">'.boton('Editar', '/foro/editar/'.$r['ID'], false, 'small').'</span>'; 
        } elseif (nucleo_acceso($vp['acceso']['foro_borrar'])) { 
            $editar = '<span style="float:right;">'.boton('Mover', '/foro/editar/'.$r['ID'], false, 'small').' '.boton('Papelera', '/accion/foro/borrar/hilo?ID='.$r['ID'], '¿Quieres enviar a la PAPELERA este HILO y TODOS sus MENSAJES?', 'small red').'</span>'; 
        } else { $editar = ''; }

        echo '<tr>
<td align="right" valign="top" style="border-bottom:none;">'.print_lateral($r['nick'], $r['cargo'], $r['time'], $r['siglas'], $r['user_ID'], $r['avatar'], $r['votos'], $r['votos_num'], $r['voto'], 'hilos', $r['ID']).'</td>
<td class="amarillo redondeado" valign="top" width="80%"><p style="text-align:justify;">'.$citar.$editar.'<h1 style="margin:-6px 0 10px 0;"><a href="/'.$return_url.'" class="rich" style="font-size:20px;">'.$r['title'].'</a></h1>'.reemplazos($r['text']).'</p></td>
</tr>


<tr>
<td colspan="2" valign="middle" class="gris">
'.$p_paginas.' &nbsp; ' . boton('Responder', $crear_hilo, false, 'large blue') . ' &nbsp; 
<span style="float:right;margin-top:20px;">Orden: <a href="/'.$return_url.'/"'.($_GET[2]=='mejores'?'':' style="color:#444;"').'>Fecha</a> | <a href="/'.$return_url.'mejores/"'.($_GET[2]=='mejores'?' style="color:#444;"':'').'>Votos</a></span>
<b>'.$r['num'].'</b> mensajes en este hilo creado hace <acronym title="'.$r['time'].'"><span class="timer" value="'.strtotime($r['time']).'"></span></acronym>.
</td>
</td>';

        $result2 = mysql_query_old("SELECT m.ID, hilo_ID, user_ID, m.time, m.text, m.cargo, nick, m.estado AS nick_estado, avatar, votos, votos_num, v.voto
FROM ".SQL."foros_msg `m`
LEFT JOIN users `u` on (u.ID = user_ID)
LEFT JOIN votos `v` ON (tipo = 'msg' AND v.pais = '".PAIS."' AND item_ID = m.ID AND emisor_ID = '".$pol['user_ID']."')
WHERE hilo_ID = '".$r['ID']."' AND m.estado = 'ok'
ORDER BY ".($_GET[2]=='mejores'?'votos DESC LIMIT 100':'time ASC LIMIT '.mysqli_real_escape_string($link,$p_limit)), $link);
        while($r2 = mysqli_fetch_array($result2)) {

            if (($pol['user_ID'] == $r2['user_ID']) AND ($subforo != 'notaria') AND (strtotime($r2['time']) > (time() - 3600))) { 
                $editar = boton('Editar', '/foro/editar/'.$r2['hilo_ID'].'/'.$r2['ID'], false, 'small').boton('X', '/accion/foro/eliminarreply?ID='.$r2['ID'].'&hilo_ID='.$r2['hilo_ID'], '¿Estás seguro de querer ELIMINAR tu MENSAJE?', 'small red').' '; 
            } elseif (nucleo_acceso($vp['acceso']['foro_borrar'])) { 
                // policia borra
                $editar = boton('Papelera', '/accion/foro/borrar/mensaje?ID=' . $r2['ID'] . '/', '¿Quieres enviar a la PAPELERA este MENSAJE?', 'small') . ' '; 
            } else { $editar = ''; }

            if (($citar) AND ($pol['user_ID'] != $r2['user_ID'])) {
                    $citar = '<div class="citar">'.boton('Citar', '/'.$return_url.'1/'.$r2['ID'].'#enviar', false, 'small pill').'</div>'; 
            }

            echo '<tr id="m-' . $r2['ID'] . '"><td align="right" valign="top">' . print_lateral($r2['nick'], $r2['cargo'], $r2['time'], $r2['siglas'], $r2['user_ID'], $r2['avatar'], $r2['votos'], $r2['votos_num'], $r2['voto'], 'msg', $r2['ID']) . '</td><td valign="top"><p class="pforo"><span style="float:right;">' . $editar . '<a href="#m-' . $r2['ID'] . '">#</a></span>'.($r2['nick_estado']=='expulsado'?'<span style="color:red;">Expulsado.</span>':$citar.reemplazos($r2['text'])).'</p></td></tr>';
        }
        echo '</table> <p>' . $p_paginas . '</p>';

        if ($acceso['escribir_msg']) { echo foro_enviar($r['sub_ID'], $r['ID'], null, $_GET[3]); }

        if (!$pol['user_ID']) { echo '<p class="azul"><b>Para poder participar en esta conversacion has de <a href="/registrar">registrar tu ciudadano</a></b></p>'; }
        
        echo '<fieldset><legend>Más hilos</legend><p>';
        $result2 = mysql_query_old("SELECT url, title, (SELECT url FROM ".SQL."foros WHERE ID = ".SQL."foros_hilos.sub_ID LIMIT 1) AS subforo FROM ".SQL."foros_hilos WHERE estado = 'ok' ORDER BY RAND() LIMIT 10", $link);
        while($r2 = mysqli_fetch_array($result2)) {
            echo '<a href="/foro/'.$r2['subforo'].'/'.$r2['url'].'/">'.$r2['title'].'</a>, ';
        }
        echo '<p></fieldset>';
        
    } else { echo '<p><b style="color:red;">No tienes acceso de lectura a este subforo.</b></p>'; }
}
