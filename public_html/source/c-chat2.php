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
- Sortear los conflictos derivados de que hay multiples paises.
- Panel de creación de nuevos chats. Opciones:
	- Tiempo de expiración: tras N dias de inactividad, perpetuo. 
	- Qué pais lo Gobierna en ultima instancia (con sus policias o incluso pudiendolo clausurar).
- Panel de configuracion de un chat existente. Opciones:
	- Acceso minimo para lectura: N nivel, N antiguedad, ciudadanos de X pais, cualquier ciudadano, abierto.
	- Acceso minimo para escritura: N nivel, N antiguedad, ciudadanos de X pais, cualquier ciudadano, anonimos.
	- ...
- Añadir en Despacho Oval control para quien puede crear un chat,
- Página mostrando los chats activos, su URL externa donde se visualiza (si la hay) y estadisticas de visitas de los chats.
- ...

*/





if ($_GET['a'] == 'solicitar-chat') { // Crear chat
	include('inc-login.php');

	$result = mysql_query("SELECT valor, dato FROM ".SQL."config WHERE autoload = 'no'", $link);
	while ($row = mysql_fetch_array($result)) { $pol['config'][$row['dato']] = $row['valor']; }

	foreach ($vp['paises'] AS $pais) { $txt_li .= '<option value="'.$pais.'"'.($pais==PAIS?' selected="selected"':'').'>'.$pais.'</option>';}

	$txt .= '<h1><a href="/chat2/">Chats</a>: Solicitar chat</h1>

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

<p><a href="/chat2/"><b>Ver Chats</b></a></p>

</form>';



	include('theme.php');

} elseif ($_GET['a']) { // Chats

	include('inc-chat2.php');

} else { // Listado de chats
	include('inc-login.php');
	
	$txt .= '<table width="0" border="0">
<tr>
<th>Estado</th>
<th>Pais</th>
<th>Chat</th>
<th>Acceso Leer</th>
<th>Acceso Escribir</th>
<th>Fundador</th>
<th>Hace...</th>
</tr>';
	$result = mysql_query("SELECT *,
(SELECT nick FROM users WHERE ID = chats.user_ID LIMIT 1) AS fundador
FROM chats ORDER BY estado DESC", $link);
	while ($r = mysql_fetch_array($result)) { 
		
		$txt .= '<tr>
<td align="right"><b style="color:#888;">'.ucfirst($r['estado']).'</b></td>
<td><b>'.$r['pais'].'</b></td>
<td><a href="http://'.strtolower($r['pais']).'-dev.virtualpol.com/chat2/'.$r['url'].'/"><b>'.$r['titulo'].'</b></a></td>
<td>'.$r['acceso_leer'].($r['acceso_cfg_leer']?' ('.$r['acceso_cfg_leer'].')':'').'</td>
<td>'.$r['acceso_escribir'].($r['acceso_cfg_escribir']?' ('.$r['acceso_cfg_escribir'].')':'').'</td>
<td>'.($r['user_ID']==0?'<em>Sistema</em>':crear_link($r['fundador'])).'</td>
<td align="right">'.duracion(time() - strtotime($r['fecha_creacion'])).'</td>
</tr>';
	}

	$txt .= '</table><p>'.boton('Solicitar chat', '/chat2/solicitar-chat/').'</p>';

	include('theme.php');
}



?>
