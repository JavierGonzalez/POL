<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');
$txt_title = _('Grupos');

if (is_numeric($_GET['a'])) {
	

	$result = sql("SELECT * FROM api WHERE pais = '".PAIS."' AND api_ID = '".$_GET['a']."' LIMIT 1");
	while($r = r($result)) {
		$txt_nav = array('/api'=>'Api', '/api/'.$r['api_ID']=>$r['nombre']);
		$txt .= '

'.(nucleo_acceso($vp['acceso']['api_borrador'])?'
<form action="/accion.php?a=api&b=crear" method="post">
<fieldset><legend>Crear borrador de publicación</legend>

<input type="hidden" name="api_ID" value="'.$r['api_ID'].'" />

<p><textarea name="texto" style="width:500px;height:60px;"></textarea>
'.boton('Crear borrador', 'submit', '¿Estás seguro de querer CREAR un borrador de contenido?', 'blue').'</p>

</fieldset>
</form>
':'').'

<table><tr><td valign="top">


<fieldset><legend>Contenido publicado</legend>
<table>
<tr>
<th></th>
<th>Texto</th>
<th>Cuando</th>
<th>Quien</th>
</tr>';
		$result2 = sql("SELECT *, (SELECT nick FROM users WHERE ID = api_posts.publicado_user_ID LIMIT 1) AS nick_publicado FROM api_posts WHERE pais = '".PAIS."' AND api_ID = '".$r['api_ID']."' AND estado = 'publicado' ORDER BY time DESC");
		while($r2 = r($result2)) { 
			$txt .= '<tr>
<td>'.($r2['estado']=='publicado'&&nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?boton('Despublicar', '/accion.php?a=api&b=borrar&ID='.$r2['post_ID'], '¿Estás seguro de querer ELIMINAR de forma irreversible esta publicación?', 'small red'):'').'</td>
<td width="200"><a href="https://www.facebook.com/permalink.php?story_fbid='.explodear('_', $r2['mensaje_ID'], 1).'&id='.$r['item_ID'].'" target="_blank">'.substr($r2['texto'], 0, 150).'</a></td>
<td align="right" nowrap>'.timer($r2['time']).'</td>
<td>'.crear_link($r2['nick_publicado']).'</td>
</tr>';
		}
		$txt .= '</table>
</fieldset>

</td><td valign="top">

<fieldset><legend>Contenido pendiente</legend>
<table>
<tr>
<th></th>
<th>Texto</th>
<th>Cuando</th>
<th>Quien</th>
</tr>';
		$result2 = sql("SELECT *, (SELECT nick FROM users WHERE ID = api_posts.pendiente_user_ID LIMIT 1) AS nick_pendiente, (SELECT nick FROM users WHERE ID = api_posts.borrado_user_ID LIMIT 1) AS nick_borrado FROM api_posts WHERE pais = '".PAIS."' AND api_ID = '".$r['api_ID']."' AND estado != 'publicado' ORDER BY time DESC");
		while($r2 = r($result2)) { 
			$txt .= '<tr>
<td nowrap>'.(nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?boton('Publicar', '/accion.php?a=api&b=publicar&ID='.$r2['post_ID'], '¿Estás seguro de querer PUBLICAR esta publicación?', 'small blue'):'').' '.(nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?boton('X', '/accion.php?a=api&b=borrar_borrador&ID='.$r2['post_ID'], '¿Estás seguro de querer BORRAR este borrador de publicación?', 'small red'):'').'</td>
<td width="200">'.$r2['texto'].'</td>
<td align="right" nowrap>'.timer($r2['time']).'</td>
<td>'.($r2['estado']=='pendiente'?crear_link($r2['nick_pendiente']):crear_link($r2['nick_borrado'])).'</td>
</tr>';
		}
		$txt .= '</table>
</fieldset>

</tr></table>
		
		






';
	}


	
} else {
	$txt_nav = array('/api'=>'Api');
	$txt .= '<table>
<tr>
<th>Estado</th>
<th>Medio</th>
<th>Nombre</th>
</tr>';
	$result = sql("SELECT * FROM api WHERE pais = '".PAIS."'");
	while($r = r($result)) { 
		$txt .= '<tr>
<td>'.ucfirst($r['estado']).'</td>
<td><b>'.ucfirst($r['tipo']).'</b></td>
<td><a href="/api/'.$r['api_ID'].'"><b>'.$r['nombre'].'</b></a></td>
<td><a href="'.$r['url'].'" target="_blank"><em>Ir</em></a></td>
</tr>';
	}
	$txt .= '</table>';

}






$txt_menu = 'demo';
include('theme.php');
?>