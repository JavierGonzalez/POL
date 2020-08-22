<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$txt_title = _('Chats');
$txt_nav = array('/chat/list'=>_('Chats'));
$txt_tab = array('/chat/solicitar'=>_('Solicitar chat'));

$result = sql_old("SELECT COUNT(*) AS num FROM chats_msg WHERE time > '".date('Y-m-d H:i:s', time() - 600)."'");
while ($r = r($result)) { 
	$msgnum_10min = $r['num'];
}



echo '<span style="float:right;color:#888;font-size:18px;"><b>'.round(($msgnum_10min / 10), 1).'</b> msg/min</span>

<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>
<th colspan="3"></th>
<th colspan="2" align="center">'._('Acceso').'</th>
<th colspan="4"></th>
</tr>

<tr>
<th></th>
<th align="right"></th>
<th align="left">'._('Chat').'</th>
<th>'._('Visitas').'</th>
<th style="background:#5CB3FF;">'._('Leer').'</th>
<th style="background:#F97E7B;">'._('Escribir').'</th>
<th>'._('Admin').'</th>
<th>'._('Hace').'...</th>
<th></th>
<th></th>
</tr>';
$result = sql_old("SELECT *,
(SELECT COUNT(DISTINCT nick) FROM chats_msg WHERE chat_ID = chats.chat_ID AND user_ID = 0 AND tipo != 'e' AND time > '".date('Y-m-d H:i:s', time() - 60*30)."') AS online
FROM chats WHERE pais = '".PAIS."' ORDER BY estado ASC, online DESC, fecha_creacion ASC");
while ($r = r($result)) { 
	
	echo '<tr>
<td valign="top" align="right">'.($r['estado']=='activo'?'':'<b style="color:#888;">#</b>').'</td>
<td valign="top" align="right"><b>'.$r['online'].'</b></td>
<td valign="top" nowrap="nowrap" title="'.$r['pais'].'">'.($r['estado']=='activo'?'<a href="/chat/'.$r['url'].'"><b>'.$r['titulo'].'</b></a>':'<b>'.$r['titulo'].'</b>').'</td>

<td valign="top" align="right">'.num($r['stats_visitas']).'</td>

<td valign="top" style="background:#5CB3FF;">'.($r['acceso_cfg_leer']?'<acronym title="['.$r['acceso_cfg_leer'].']">':'').ucfirst($r['acceso_leer']).($r['acceso_cfg_leer']?'</acronym>':'').'</td>

<td valign="top" style="background:#F97E7B;">'.($r['acceso_cfg_escribir']?'<acronym title="['.$r['acceso_cfg_escribir'].']">':'').ucfirst($r['acceso_escribir']).($r['acceso_cfg_escribir']?'</acronym>':'').'</td>

<td valign="top">'.($r['user_ID']==0?'<em>'._('Sistema').'</em>':$r['admin']).'</td>

<td valign="top" align="right" nowrap="nowrap">'.timer($r['fecha_creacion']).'</td>
<td valign="top" align="right"></td>
<td align="right" nowrap>';

	echo ((($r['estado'] != 'activo') AND ($pol['pais'] == $r['pais']) AND (nucleo_acceso($vp['acceso']['control_gobierno'])))?boton(_('Activar'), '/accion/chat/activar?chat_ID='.$r['chat_ID'], false, 'small orange'):'').' '.
($pol['user_ID'] == $r['user_ID'] || nucleo_acceso('privado', $r['admin']) || nucleo_acceso($vp['acceso']['control_gobierno'])?boton(_('Borrar'), '/accion/chat/eliminar?chat_ID='.$r['chat_ID'], '¿Estás seguro de querer ELIMINAR este chat?', 'small red pill'):'').' '.
($pol['user_ID'] == $r['user_ID'] ?boton(_('Limpiar'), '/accion/chat/limpiar?chat_ID='.$r['chat_ID'], '¿Estás seguro de querer LIMPIAR este chat?', 'small green pill'):'').
'</td>
</tr>';
}
echo '</table>';

// Limpiar logs de 24h
// sql_old("DELETE FROM chats_msg WHERE time < '".date('Y-m-d H:i:s', time() - (60*60*24))."'");

$txt_menu = 'comu';
//if (!$externo) {  }
