<?php 
/* ### Proyecto CHAT 2 ### */

include('inc-login.php');


if ($_GET['a'] == 'solicitar-chat') { // Crear chat
	$txt_title = 'Solicitar chat';
	if (($pol['pais']) AND ($pol['pais'] != PAIS)) { header('Location: http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/chats/'.$_GET['a'].'/'); exit; }

	$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
	while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

	$txt .= '<h1><a href="/chats/">Chats</a>: Solicitar chat</h1>

<form action="/accion.php?a=chat&b=solicitar" method="post">

<ol>
<li><b>Nombre del chat:</b><br />
<input type="text" name="nombre" size="20" maxlength="20" /> (No modificable)
<br /><br /></li>

<li>' . boton('Solicitar chat', false, false, '', $pol['config']['pols_crearchat']) . '</li>
</ol>
</form>';

} elseif (($_GET['a'] == 'turistas') AND ($pol['estado'] == 'ciudadano')) {

	$txt_title = 'Turistas';

	$txt .= '<h1><a href="/chats/">Chats</a>: Turistas</h1>

<br /><h2>Ultimos mensajes de Turistas:</h2><br />

<table border="0" cellspacing="0" cellpadding="0">
<tr>
<th align="right">IP</th>
<th></th>
<th align="right">Hace</th>
<th align="right">Nick</th>
<th>&nbsp; Mensaje</th>
</tr>';

	$result = mysql_query("SELECT *,
(SELECT pais FROM chats WHERE chat_ID = chats_msg.chat_ID LIMIT 1) AS pais,
(SELECT url FROM chats WHERE chat_ID = chats_msg.chat_ID LIMIT 1) AS url
FROM chats_msg WHERE IP != '' AND tipo = 'm' ORDER BY msg_ID DESC LIMIT 50", $link);
	while ($r = mysql_fetch_array($result)) { 
		
		$mip = explode('.', long2ip($r['IP']));
		$txt .= '<tr>
<td align="right" nowrap="nowrap" style="font-size:12px;">'.$mip[0].'.'.$mip[1].'.'.$mip[2].'.* &nbsp;</td>
<td nowrap="nowrap"><a href="/chats/'.$r['url'].'/">Ir</a>'.(((($pol['cargo'] == 12) OR ($pol['cargo'] == 13)) AND ($r['pais'] == $pol['pais']))?' <a href="/control/kick/ip-'.$r['IP'].'/'.$r['chat_ID'].'/" style="color:red;"><img src="'.IMG.'varios/kick.gif" border="0" alt="KICK" title="KICK" /></a> &nbsp;':'').'</td>
<td align="right" nowrap="nowrap" style="font-size:14px;">'.timer($r['time']).'</td>
<td align="right" nowrap="nowrap" style="color:#666;" style="font-size:15px;"><b>'.$r['nick'].'</b>:</td>
<td style="color:#AAA;font-size:15px;" width="100%">&nbsp; '.$r['msg'].'</td>
</tr>';
	}

	$txt .= '</table>';

} elseif ($_GET['b'] == 'log') { // Ultimo.

	$result = mysql_query("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET['a']."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 

		$txt_title = 'Chat: '.$r['titulo'].' | log';
		
		$txt .= '<h1><a href="/chats/">Chats</a>: <a href="/chats/'.$r['url'].'/">'.$r['titulo'].'</a> | log</h1>';

		if ((nucleo_acceso($r['acceso_leer'], $r['acceso_cfg_leer'])) AND (isset($pol['user_ID']))) {
			$txt .= '<p>Log de las ultimas 24 horas:</p><p class="rich" style="background:#FFFFFF;padding:15px;font-size:14px;text-align:left;">VirtualPol: Plataforma '.PAIS.'. Sala: '.$r['titulo'].'. A fecha de '.date('Y-m-d').'.<br />...<br />';
			$result2 = mysql_query("SELECT * FROM chats_msg WHERE chat_ID = '".$r['chat_ID']."' AND tipo != 'p' ORDER BY time ASC", $link);
			while ($r2 = mysql_fetch_array($result2)) { 
				$txt .= '<span'.($r2['tipo']!='m'?' style="color:green;"':'').'>'.date('H:i', strtotime($r2['time'])).' <img src="'.IMG.'cargos/'.$r2['cargo'].'.gif" width="16" height="16" border="0"> <b>'.$r2['nick'].'</b>: '.$r2['msg']."</span><br />\n"; 
			}
			$txt .= '<b>FIN</b>: '.$date.'</p>';
		} else {
			$txt .= '<p style="color:red;">No tienes acceso de lectura.</p>';
		}
	}

} elseif ($_GET['b'] == 'opciones') { // Configurar chat

	$result = mysql_query("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET['a']."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 

		$txt_title = 'Chat: '.$r['titulo'].' | Opciones';

		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['leer'] .= '<input type="radio" name="acceso_leer" value="'.$at.'"'.($at==$r['acceso_leer']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_leer_var\').val(\''.$at_var.'\');" /> '.ucfirst(str_replace("_", " ", $at)).'<br />';
		}
		foreach (nucleo_acceso('print') AS $at => $at_var) { 
			$txt_li['escribir'] .= '<input type="radio" name="acceso_escribir" value="'.$at.'"'.($at==$r['acceso_escribir']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_escribir_var\').val(\''.$at_var.'\');"'.($at=='anonimos'?' disabled="disabled"':'').' /> '.ucfirst(str_replace("_", " ", $at)).'<br />';
		}

		$txt .= '<h1><a href="/chats/">Chats</a>: <a href="/chats/'.$r['url'].'/">'.$r['titulo'].'</a> | Opciones</h1>

<form action="/accion.php?a=chat&b=editar" method="post">
<input type="hidden" name="chat_ID" value="'.$r['chat_ID'].'" />
<input type="hidden" name="chat_nom" value="'.$_GET['a'].'" />

<br /><table border="0" cellpadding="9">
<tr>
<td valign="top"><b>Acceso leer:</b><br />
'.$txt_li['leer'].' <input type="text" name="acceso_cfg_leer" size="40" maxlength="900" autocomplete="off" id="acceso_cfg_leer_var" value="'.$r['acceso_cfg_leer'].'" /></td>

<td valign="top"><b>Acceso escribir:</b><br />
'.$txt_li['escribir'].' <input type="text" name="acceso_cfg_escribir" size="40" maxlength="900" autocomplete="off" id="acceso_cfg_escribir_var" value="'.$r['acceso_cfg_escribir'].'" /></td>

</tr>
<tr><td colspan="2" align="center"><input type="submit" style="font-size:24px;width:150px;" value="Editar"'.((nucleo_acceso('privado', $r['admin'])) OR (($r['user_ID'] == 0) AND ($pol['nivel'] >= 98))?'':' disabled="disabled"').' /></td></tr>
</table>

</form>';

		if ($r['user_ID'] != 0) {
			$txt .= '<form action="/accion.php?a=chat&b=cambiarfundador" method="post">
<input type="hidden" name="chat_ID" value="'.$r['chat_ID'].'" />
<p>Administradores: <input type="text" name="admin" size="40" maxlength="900" value="'.$r['admin'].'" /> <input type="submit" value="Cambiar Administradores"'.(($r['user_ID'] == $pol['user_ID']) OR (nucleo_acceso('privado', $r['admin'])) OR (($r['user_ID'] == 0) AND (nucleo_acceso('nivel', 98)))?'':' disabled="disabled"').' /></p></form>';
		}

		if (($r['estado'] == 'activo') AND ($r['user_ID'] != 0) AND (($r['user_ID'] == $pol['user_ID']) OR (($pol['nivel'] >= 95) AND ($r['acceso_escribir'] == 'anonimos')))) { 
			$txt .= boton('Bloquear', 'http://'.strtolower($r['pais']).DEV.'.virtualpol.com/accion.php?a=chat&b=bloquear&chat_ID='.$r['chat_ID'], '&iquest;Seguro que quieres BLOQUEAR este chat?');
		}

		$txt .= '<p>Codigo HTML: <input type="text" style="color:grey;font-weight:normal;" value="&lt;iframe width=&quot;730&quot; height=&quot;480&quot; scrolling=&quot;no&quot; frameborder=&quot;0&quot; transparency=&quot;transparency&quot; src=&quot;http://'.strtolower($r['pais']).DEV.'.virtualpol.com/chats/'.$r['url'].'/e/&quot;&gt;&lt;p&gt;&lt;a href=&quot;http://'.strtolower($r['pais']).DEV.'.virtualpol.com/chats/'.$r['url'].'/&quot;&gt;&lt;b&gt;Entra al chat&lt;/b&gt;&lt;/a&gt;&lt;/p&gt;&lt;/iframe&gt;" size="70" /></p>';
	}


} elseif ($_GET['a']) { // Chats
	include('inc-chats.php');
} else { // Listado de chats
	$txt_title = 'Chats';

	$result = mysql_query("SELECT COUNT(*) AS num FROM chats_msg WHERE time > '".date('Y-m-d H:i:s', time() - 600)."'", $link);
	while ($r = mysql_fetch_array($result)) { 
		$msgnum_10min = $r['num'];
	}


	
	$txt .= '<span style="float:right;color:#888;font-size:18px;"><b>'.round(($msgnum_10min / 10), 1).'</b> msg/min</span><h1><a href="/chats/">Chats</a>:</h1>

<table border="0" width="100%" cellspacing="0" cellpadding="4">
<tr>
<th colspan="3"></th>
<th colspan="2" align="center">Acceso</th>
<th colspan="4"></th>
</tr>

<tr>
<th></th>
<th align="right"><acronym title="Online en los ultimos 30 minutos.">#</acronym></th>
<th>Chat</th>
<th style="background:#5CB3FF;">Leer</th>
<th style="background:#F97E7B;">Escribir</th>
<th>Visitas</th>
<th>Admin</th>
<th>Hace...</th>
<th></th>
<th></th>
</tr>';
	$result = mysql_query("SELECT *,
(SELECT COUNT(DISTINCT nick) FROM chats_msg WHERE chat_ID = chats.chat_ID AND user_ID = 0 AND tipo != 'e' AND time > '".date('Y-m-d H:i:s', time() - 1800)."') AS online
FROM chats WHERE pais = '".PAIS."' ORDER BY estado ASC, online DESC, fecha_creacion ASC", $link);
	while ($r = mysql_fetch_array($result)) { 
		
		$txt .= '<tr>
<td valign="top" align="right">'.($r['estado']=='activo'?'':'<b style="color:#888;">#</b>').'</td>
<td valign="top" align="right"><b>'.$r['online'].'</b></td>
<td valign="top" nowrap="nowrap" style="background:'.$vp['bg'][$r['pais']].';" title="'.$r['pais'].'">'.($r['estado']=='activo'?'<a href="http://'.strtolower($r['pais']).DEV.'.virtualpol.com/chats/'.$r['url'].'/"><b>'.$r['titulo'].'</b></a>':'<b>'.$r['titulo'].'</b>').'</td>

<td valign="top" style="background:#5CB3FF;">'.($r['acceso_cfg_leer']?'<acronym title="['.$r['acceso_cfg_leer'].']">':'').ucfirst($r['acceso_leer']).($r['acceso_cfg_leer']?'</acronym>':'').'</td>

<td valign="top" style="background:#F97E7B;">'.($r['acceso_cfg_escribir']?'<acronym title="['.$r['acceso_cfg_escribir'].']">':'').ucfirst($r['acceso_escribir']).($r['acceso_cfg_escribir']?'</acronym>':'').'</td>

<td valign="top" align="right">'.$r['stats_visitas'].'</td>

<td valign="top">'.($r['user_ID']==0?'<em>Sistema</em>':$r['admin']).'</td>

<td valign="top" align="right" nowrap="nowrap">'.timer($r['fecha_creacion']).'</td>
<td valign="top" align="right">'.($r['estado']=='activo'?'<a href="http://'.strtolower($r['pais']).DEV.'.virtualpol.com/chats/'.$r['url'].'/opciones/">Editar</a>':'').'</td>
<td>';

		$txt .= ((($r['estado'] != 'activo') AND ($pol['pais'] == $r['pais']) AND ($pol['nivel'] >= 95))?boton('Activar', 'http://'.strtolower($r['pais']).DEV.'.virtualpol.com/accion.php?a=chat&b=activar&chat_ID='.$r['chat_ID']):'').
((($r['estado'] == 'bloqueado') AND ($pol['user_ID'] == $r['user_ID']))?boton('Borrar', 'http://'.strtolower($r['pais']).DEV.'.virtualpol.com/accion.php?a=chat&b=eliminar&chat_ID='.$r['chat_ID']):'').'</td>
</tr>';
}

// '.($pol['estado'] == 'ciudadano'?'<p style="float:right;"><a href="/chats/turistas/"><b>Turistas</b></a></p>':'').'
	$txt .= '</table>
<p>'.boton('Solicitar chat', '/chats/solicitar-chat/').' <span style="font-size:12px;"></span></p>';


}

// Limpiar logs de 24h
mysql_query("DELETE FROM chats_msg WHERE time < '".date('Y-m-d H:i:s', time() - (60*60*24))."'", $link);

if (!$externo) { include('theme.php'); }
?>