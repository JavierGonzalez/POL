<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 






if ($_GET[1] == 'solicitar-chat') { // Crear chat
	$txt_title = _('Solicitar chat');
	$txt_nav = array('/chats'=>'Chats', _('Solicitar chat'));
	$txt_tab = array('/chats/solicitar-chat'=>_('Solicitar chat'));

	if (($pol['pais']) AND ($pol['pais'] != PAIS)) { redirect('http://'.strtolower($pol['pais']).'.'.DOMAIN.'/chats/'.$_GET[1]); }

	$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
	while ($row = r($result)) { $pol['config'][$row['dato']] = $row['valor']; }

	echo '<form action="/accion/chat/solicitar" method="post">

<ol>
<li><b>'._('Nombre del chat').':</b><br />
<input type="text" name="nombre" size="20" maxlength="20" /> ('._('No modificable').')
<br /><br /></li>

<li>' . boton(_('Solicitar chat'), 'submit', false, '', $pol['config']['pols_crearchat']) . '</li>
</ol>
</form>';

} elseif ($_GET[2] == 'estadisticas') {

	$result = sql("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET[1]."' LIMIT 1");
	while ($r = r($result)) { 

		$txt_title = _('Chat').': '.$r['titulo'].' | log';
		$txt_nav = array('/chats'=>_('Chats'), '/chats/'.$r['url']=>$r['titulo'], _('Estadisticas'));
		$txt_tab = array('/chats/'.$r['url']=>'Chat', '/chats/'.$r['url'].'/log'=>_('Log'), '/chats/'.$r['url'].'/estadisticas'=>_('Estadisticas'), '/chats/'.$r['url'].'/opciones'=>_('Opciones'));
		
		if ((nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) AND (isset($pol['user_ID']))) {
			
			$msg_num = array(); $char_num = array();
			$result2 = sql("SELECT nick, msg FROM chats_msg WHERE chat_ID = '".$r['chat_ID']."' AND tipo = 'm'");
			while ($r2 = r($result2)) { 
				$msg_num[$r2['nick']]++;
				$char_num[$r2['nick']] += strlen($r2['msg']);
			}
			
			echo '<fieldset>Estadisticas de las últimas 24h de esta sala de chat.</fieldset>
<table>
<tr>
<th></th>
<th>Ciudadano</th>
<th>Caracteres</th>
<th>Lineas</th>
<th>C/L</th>
</tr>';
			arsort($char_num);
			foreach ($char_num AS $nick => $num) {
				echo '<tr>
<td align="right">'.++$n.'.</td>
<td class="rich"><b>'.crear_link($nick).'</b></td>
<td align="right"><b>'.num($num).'</b></td>
<td align="right">'.num($msg_num[$nick]).'</td>
<td align="right">'.num($num/$msg_num[$nick],1).'</td>
</tr>';
			}
			echo '</table>';
		} else {
			echo '<p style="color:red;">'._('No tienes acceso de lectura').'.</p>';
		}
	}

} elseif ($_GET[2] == 'log') { // Ultimo.

	$result = sql("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET[1]."' LIMIT 1");
	while ($r = r($result)) { 

		$txt_title = _('Chat').': '.$r['titulo'].' | log';
		$txt_nav = array('/chats'=>_('Chats'), '/chats/'.$r['url']=>$r['titulo'], _('Log'));
		$txt_tab = array('/chats/'.$r['url']=>'Chat', '/chats/'.$r['url'].'/log'=>_('Log'), '/chats/'.$r['url'].'/estadisticas'=>_('Estadisticas'), '/chats/'.$r['url'].'/opciones'=>_('Opciones'));
		
		if ((nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) AND (isset($pol['user_ID']))) {
			echo '<p>'._('Log de las últimas 24 horas').':</p><p class="rich" style="background:#FFFFFF;padding:15px;font-size:14px;text-align:left;">VirtualPol: '._('Plataforma').' '.PAIS.'. '._('Sala').': '.$r['titulo'].'. '._('A fecha de').' '.date('Y-m-d').'.<br />...<br />';
			$result2 = sql("SELECT * FROM chats_msg WHERE chat_ID = '".$r['chat_ID']."' AND tipo != 'p' ORDER BY msg_ID ASC");
			while ($r2 = r($result2)) { 
				echo '<span'.($r2['tipo']!='m'?' style="color:green;"':'').'>'.date('H:i', strtotime($r2['time'])).' <img src="'.IMG.'cargos/'.$r2['cargo'].'.gif" width="16" height="16" border="0"> <b>'.$r2['nick'].'</b>: '.$r2['msg']."</span><br />\n"; 
			}
			echo '<b>'._('FIN').'</b>: '.$date.'</p>';
		} else {
			echo '<p style="color:red;">'._('No tienes acceso de lectura').'.</p>';
		}
	}

} elseif ($_GET[2] == 'opciones') { // Configurar chat

	$result = sql("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET[1]."' LIMIT 1");
	while ($r = r($result)) { 

		$txt_title = _('Chat').': '.$r['titulo'].' | '._('Opciones');
		$txt_nav = array('/chats'=>_('Chats'), '/chats/'.$r['url']=>$r['titulo'], _('Opciones'));
		$txt_tab = array('/chats/'.$r['url']=>_('Chat'), '/chats/'.$r['url'].'/log'=>_('Log'), '/chats/'.$r['url'].'/opciones'=>_('Opciones'));

		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['leer'] .= '<input type="radio" name="acceso_leer" value="'.$at.'"'.($at==$r['acceso_leer']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_leer_var\').val(\''.$at_var.'\');" /> '._(ucfirst(str_replace("_", " ", $at))).'<br />';
		}
		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['escribir'] .= '<input type="radio" name="acceso_escribir" value="'.$at.'"'.($at==$r['acceso_escribir']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_escribir_var\').val(\''.$at_var.'\');"'.($at=='anonimos'?' disabled="disabled"':'').' /> '._(ucfirst(str_replace("_", " ", $at))).'<br />';
		}

		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['escribir_ex'] .= '<input type="radio" name="acceso_escribir_ex" value="'.$at.'"'.($at==$r['acceso_escribir_ex']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_escribir_ex_var\').val(\''.$at_var.'\');"'.($at=='anonimos'?' disabled="disabled"':'').' /> '._(ucfirst(str_replace("_", " ", $at))).'<br />';
		}

		echo '
<form action="/accion/chat/editar" method="post">
<input type="hidden" name="chat_ID" value="'.$r['chat_ID'].'" />
<input type="hidden" name="chat_nom" value="'.$_GET[1].'" />

<fieldset><legend>'._('Opciones de acceso').'</legend>
<table border="0" cellpadding="9">
<tr>
<td valign="top"><b>'._('Acceso leer').':</b><br />
'.$txt_li['leer'].' <input type="text" name="acceso_cfg_leer" size="25" maxlength="900" autocomplete="off" id="acceso_cfg_leer_var" value="'.$r['acceso_cfg_leer'].'" /></td>

<td valign="top"><b>'._('Acceso escribir').':</b><br />
'.$txt_li['escribir'].' <input type="text" name="acceso_cfg_escribir" size="25" maxlength="900" autocomplete="off" id="acceso_cfg_escribir_var" value="'.$r['acceso_cfg_escribir'].'" /></td>

<td valign="top"><b>'._('Acceso escribir').' '._('extranjeros').':</b><br />
'.$txt_li['escribir_ex'].' <input type="text" name="acceso_cfg_escribir_ex" size="25" maxlength="900" autocomplete="off" id="acceso_cfg_escribir_ex_var" value="'.$r['acceso_cfg_escribir_ex'].'" /></td>

</tr>
<tr><td colspan="3" align="center">

'.boton(_('Guardar'), (nucleo_acceso('privado', $r['admin'])||nucleo_acceso($vp['acceso']['control_gobierno'])?'submit':''), false, 'large blue').'

</td></tr>
</table>
</fieldset>

</form>';

		if ($r['user_ID'] != 0) {
			echo '<form action="/accion/chat/cambiarfundador" method="post">
<input type="hidden" name="chat_ID" value="'.$r['chat_ID'].'" />

<fieldset><legend>'._('Administradores').'</legend>
<p><input type="text" name="admin" size="40" maxlength="900" value="'.$r['admin'].'" /> <input type="submit" value="'._('Cambiar administradores').'"'.($r['user_ID']==$pol['user_ID'] || nucleo_acceso('privado', $r['admin']) || nucleo_acceso($vp['acceso']['control_gobierno'])?'':' disabled="disabled"').' /></p>
</fieldset>
</form>';
		}

		if (($r['estado'] == 'activo') AND ($r['user_ID'] != 0) AND (($r['user_ID'] == $pol['user_ID']) OR (nucleo_acceso($vp['acceso']['control_gobierno'])))) { 
			echo boton(_('Bloquear'), '/accion/chat/bloquear?chat_ID='.$r['chat_ID'], '¿Seguro que quieres BLOQUEAR este chat?');
		}

		echo '<!--<p>'._('Código HTML').': <input type="text" style="color:grey;font-weight:normal;" value="&lt;iframe width=&quot;730&quot; height=&quot;480&quot; scrolling=&quot;no&quot; frameborder=&quot;0&quot; transparency=&quot;transparency&quot; src=&quot;http://'.strtolower($r['pais']).'.'.DOMAIN.'/chats/'.$r['url'].'/e/&quot;&gt;&lt;p&gt;&lt;a href=&quot;http://'.strtolower($r['pais']).'.'.DOMAIN.'/chats/'.$r['url'].'/&quot;&gt;&lt;b&gt;Entra al chat&lt;/b&gt;&lt;/a&gt;&lt;/p&gt;&lt;/iframe&gt;" size="70" /></p>-->';
	}


} elseif ($_GET[1]) { // Chats
	include('chat/inc-chats.php');
} else { // Listado de chats
	$txt_title = _('Chats');
	$txt_nav = array('/chats'=>_('Chats'));
	$txt_tab = array('/chats/solicitar-chat'=>_('Solicitar chat'));

	$result = sql("SELECT COUNT(*) AS num FROM chats_msg WHERE time > '".date('Y-m-d H:i:s', time() - 600)."'");
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
	$result = sql("SELECT *,
(SELECT COUNT(DISTINCT nick) FROM chats_msg WHERE chat_ID = chats.chat_ID AND user_ID = 0 AND tipo != 'e' AND time > '".date('Y-m-d H:i:s', time() - 60*30)."') AS online
FROM chats WHERE pais = '".PAIS."' ORDER BY estado ASC, online DESC, fecha_creacion ASC");
	while ($r = r($result)) { 
		
		echo '<tr>
<td valign="top" align="right">'.($r['estado']=='activo'?'':'<b style="color:#888;">#</b>').'</td>
<td valign="top" align="right"><b>'.$r['online'].'</b></td>
<td valign="top" nowrap="nowrap" title="'.$r['pais'].'">'.($r['estado']=='activo'?'<a href="http://'.strtolower($r['pais']).'.'.DOMAIN.'/chats/'.$r['url'].'"><b>'.$r['titulo'].'</b></a>':'<b>'.$r['titulo'].'</b>').'</td>

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
}

// Limpiar logs de 24h
// sql("DELETE FROM chats_msg WHERE time < '".date('Y-m-d H:i:s', time() - (60*60*24))."'");

$txt_menu = 'comu';
//if (!$externo) {  }
