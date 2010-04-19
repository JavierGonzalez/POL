<?php 
/* ### Proyecto CHAT 2 ###

2010-04-16 <GONZO> La idea consiste en desarrollar un nuevo chat que cuando esté listo suplantará al antiguo sistema, con el mismo nucleo pero con una evolución sustancial.


MEJORAS:
1. Crear una tabla en BD de chats activos. Esto nos permitirá: crear y eliminar chats flexiblemente, configurar parámetros por cada chat (por ejemplo habilitar/deshabilitar extranjeros o anonimos), acceso privado.
3. Centralizar las tablas de chat en una unica a ser posible.
2. Crear una versión html del chat puro para que sirva como recurso a webs externas (a modo de widget, con una linea de js). Esto expandirá la comunidad y aportará un sentido util y práctico de existir (moderar, vigilar y controlar los chats basandonos en nuestro sistema democrático).
3. Permitir acceso opcional a anonimos sin registrar y sus correspondientes medidas de control y mitigación (por ejemplo un comando para avisar a un policia).
4. ¿Ideas?


TAREAS:		(- por hacer, x hecho)
x Crear nueva tabla "chats".
x Crear nueva tabla "chats_msg".
x Copiar el nucleo del chat que servirá como comienzo.
x Aglutinar el HTML, CSS y JS del chat, aislandolo del resto del codigo.
x Sortear los conflictos derivados de que hay multiples paises.
x Panel de creación de nuevos chats. Opciones:
x Panel de configuracion de un chat existente. Opciones:
	x Acceso minimo para lectura: N nivel, N antiguedad, ciudadanos de X pais, cualquier ciudadano, abierto.
	x Acceso minimo para escritura: N nivel, N antiguedad, ciudadanos de X pais, cualquier ciudadano, anonimos.
x Añadir en Despacho Oval control para quien puede crear un chat,
x Página mostrando los chats activos, su URL externa donde se visualiza (si la hay) y estadisticas de visitas de los chats.
x Evolucionar chat
x Evolucionar nuevo ajax.php
- Resolver conflictos derivados de los usuarios anonimos.
- Bans a usuarios anonimos

*/

include('inc-login.php');


if ($_GET['a'] == 'solicitar-chat') { // Crear chat

	if (($pol['pais']) AND ($pol['pais'] != PAIS)) { header('Location: http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/chats/'.$_GET['a'].'/'); exit; }

	$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
	while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

	foreach ($vp['paises'] AS $pais) { $txt_li .= '<option value="'.$pais.'"'.($pais==PAIS?' selected="selected"':'').'>'.$pais.'</option>';}

	$txt .= '<h1><a href="/chats/">Chats</a>: Solicitar chat</h1>

<form action="/accion.php?a=chat&b=solicitar" method="post">

<ol>
<li><b>Pais:</b><br />
<select name="pais">' . $txt_li . '</select> (No modificable)
<br /><br /></li>

<li><b>Nombre del chat:</b><br />
<input type="text" name="nombre" size="20" maxlength="20" /> (No modificable)
<br /><br /></li>

<li>' . boton('Solicitar chat', false, false, '', $pol['config']['pols_crearchat']) . '</li>
</ol>
</form>';


} elseif ($_GET['b'] == 'opciones') { // Configurar chat


	if (($pol['pais']) AND ($pol['pais'] != PAIS)) { header('Location: http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/chats/'.$_GET['a'].'/'.$_GET['b'].'/'); exit; }

	$result = mysql_query("SELECT * FROM chats WHERE estado = 'activo' AND url = '".$_GET['a']."' AND pais = '".PAIS."' LIMIT 1", $link);
	while ($r = mysql_fetch_array($result)) { 


		$acceso_tipos = array('privado'=>'Ciudadano1 C2 C3 ...', 'nivel'=>'1', 'antiguedad'=>'365', 'ciudadanos_pais'=>'', 'ciudadanos'=>'', 'anonimos'=>'');
		foreach ($acceso_tipos AS $at => $at_var) { 
			$txt_li['leer'] .= '<input type="radio" name="acceso_leer" value="'.$at.'"'.($at==$r['acceso_leer']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_leer_var\').val(\''.$at_var.'\');" /> '.ucfirst(str_replace("_", " ", $at)).'<br />';
		}
		foreach ($acceso_tipos AS $at => $at_var) { 
			$txt_li['escribir'] .= '<input type="radio" name="acceso_escribir" value="'.$at.'"'.($at==$r['acceso_escribir']?' checked="checked"':'').' onclick="$(\'#acceso_cfg_escribir_var\').val(\''.$at_var.'\');" /> '.ucfirst(str_replace("_", " ", $at)).'<br />';
		}

		$txt .= '<h1><a href="/chats/">Chats</a>: <a href="/chats/'.$r['url'].'/">'.$r['titulo'].'</a> | Opciones</h1>

<form action="/accion.php?a=chat&b=editar" method="post">
<input type="hidden" name="chat_ID" value="'.$r['chat_ID'].'" />

<br /><table border="0" cellpadding="9">
<tr>
<td><b>Acceso leer:</b><br />
'.$txt_li['leer'].' <input type="text" name="acceso_cfg_leer" size="18" maxlength="500" id="acceso_cfg_leer_var" value="'.$r['acceso_cfg_leer'].'" /></td>

<td><b>Acceso escribir:</b><br />
'.$txt_li['escribir'].' <input type="text" name="acceso_cfg_escribir" size="18" maxlength="500" id="acceso_cfg_escribir_var" value="'.$r['acceso_cfg_escribir'].'" /></td>

</tr>
</table>

<p><input type="submit" value="Editar"'.(($r['user_ID'] == $pol['user_ID']) OR (($r['user_ID'] == 0) AND ($pol['nivel'] >= 98))?'':' disabled="disabled"').' /> [Solo el Fundador puede editar estos par&aacute;metros.]</p>

</form>
';
	}
	$txt .= '<br /><br /><p style="text-align:center;color:red;font-size:12px;">Acceso de escritura para anonimos EN DESARROLLO.</p>';


} elseif ($_GET['a']) { // Chats
	include('inc-chats.php');
} else { // Listado de chats

	if (($pol['pais']) AND ($pol['pais'] != PAIS)) { header('Location: http://'.strtolower($pol['pais']).DEV.'.virtualpol.com/chats/'); exit; }

	
	$txt .= '<h1><a href="/chats/">Chats</a>:</h1>

<table border="0" width="0" cellspacing="0" cellpadding="4">
<tr>
<th colspan="4"></th>
<th colspan="2" align="center">Acceso</th>
<th colspan="4"></th>
</tr>

<tr>
<th></th>
<th align="right"><acronym title="Online en los ultimos 30 minutos.">#</acronym></th>
<th>Chat</th>
<th>Pais</th>
<th style="background:#5CB3FF;">Leer</th>
<th style="background:#F97E7B;">Escribir</th>
<th>Fundador</th>
<th>Hace...</th>
<th></th>
<th></th>
</tr>';
	$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = chats.user_ID LIMIT 1) AS fundador,
(SELECT COUNT(DISTINCT user_ID) FROM chats_msg WHERE chat_ID = chats.chat_ID AND time > ".(time() - 108000).") AS online
FROM chats ORDER BY estado ASC, online DESC, fecha_creacion ASC", $link);
	while ($r = mysql_fetch_array($result)) { 
		
		$txt .= '<tr>
<td valign="top" align="right"><b style="color:#888;">'.ucfirst($r['estado']).'</b></td>
<td valign="top" align="right"><b>'.$r['online'].'</b></td>
<td valign="top" nowrap="nowrap">'.($r['estado']=='activo'?'<a href="http://'.strtolower($r['pais']).DEV.'.virtualpol.com/chats/'.$r['url'].'/"><b>'.$r['titulo'].'</b></a>':'<b>'.$r['titulo'].'</b>').'</td>
<td valign="top">'.$r['pais'].'</td>
<td valign="top" style="background:#5CB3FF;">'.ucfirst($r['acceso_leer']).($r['acceso_cfg_leer']?' <span style="font-size:11px;">['.$r['acceso_cfg_leer'].']</span>':'').'</td>
<td valign="top" style="background:#F97E7B;">'.ucfirst($r['acceso_escribir']).($r['acceso_cfg_escribir']?' <span style="font-size:11px;">['.$r['acceso_cfg_escribir'].']</span>':'').'</td>
<td valign="top">'.($r['user_ID']==0?'<em>Sistema</em>':crear_link($r['fundador'])).'</td>
<td valign="top" align="right" nowrap="nowrap">'.duracion(time() - strtotime($r['fecha_creacion'])).'</td>
<td valign="top" align="right">'.($r['estado']=='activo'?'<a href="http://'.strtolower($r['pais']).DEV.'.virtualpol.com/chats/'.$r['url'].'/opciones/">Editar</a>':'').'</td>
<td>';

		if (($r['estado'] == 'activo') AND ($r['user_ID'] != 0) AND (($r['user_ID'] == $pol['user_ID']) OR (($pol['nivel'] >= 95) AND ($r['acceso_escribir'] == 'anonimos')))) { 
			$txt .= boton('Bloquear', 'http://'.strtolower($r['pais']).DEV.'.virtualpol.com/accion.php?a=chat&b=bloquear&chat_ID='.$r['chat_ID'], '&iquest;Seguro que quieres BLOQUEAR este chat?');
		}							
		$txt .= ((($r['estado'] != 'activo') AND ($pol['pais'] == $r['pais']))?boton('Activar', 'http://'.strtolower($r['pais']).DEV.'.virtualpol.com/accion.php?a=chat&b=activar&chat_ID='.$r['chat_ID']):'').'</td>
</tr>';
}

	$txt .= '</table><p>'.boton('Solicitar chat', '/chats/solicitar-chat/').' <span style="font-size:12px;">[El Presidente o Vicepresidente activar&aacute; el chat.]</span></p>';



}


include('theme.php');

?>