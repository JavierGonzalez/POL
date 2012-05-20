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
	
	// https://developers.facebook.com/docs/reference/api/post/
	$result = sql("SELECT * FROM api WHERE api_ID = '".$_GET['a']."' LIMIT 1");
	while($r = r($result)) {
		$txt_nav = array('/api'=>'API', '/api/'.$r['api_ID']=>$r['nombre']);
		$txt .= '<h1><img src="'.IMG.'ico/'.($r['tipo']=='facebook'?2:1).'_32.png" width="32" width="32" alt="'.ucfirst($r['tipo']).'" /> <a href="'.$r['url'].'" target="_blank">'.$r['nombre'].'</a> <button onclick="$(\'#crear_borrador\').slideToggle(\'slow\');">Escribir borrador</button></h1>

<div id="crear_borrador" style="display:none;">

<form action="http://'.strtolower(PAIS).'.'.DOMAIN.'/accion.php?a=api&b=crear" method="post">

<fieldset><legend>Crear borrador de publicación</legend>


<input type="hidden" name="api_ID" value="'.$r['api_ID'].'" />

<table>

<tr>
<td align="right">Texto:</td>
<td><textarea name="message" style="width:450px;height:60px;"></textarea></td>
</tr>

<tr>
<td align="right">Publicación programada:</td>
<td><input type="text" name="time_cron" value="'.$date.'" /> (Se publicará después de esta fecha)</td>
</tr>

<tr>
<td align="right">Enlace enriquecido:</td>
<td><input type="text" name="link" value="" size="50" /> (Opcional, debe empezar por: http://)</td>
</tr>

<!--
<tr>
<td align="right">Imagen incrustada:</td>
<td><input type="text" name="picture" value="" size="50" /> (Opcional, debe empezar por: http://)</td>
</tr>
-->

<!--
<tr>
<td align="right">Vídeo incrustado:</td>
<td><input type="text" name="source" value="" size="50" /> (Opcional, debe empezar por: http://)</td>
</tr>
-->

<tr>
<td></td>
<td>'.boton('Crear borrador', (nucleo_acceso($vp['acceso']['api_borrador'])?'submit':false), '¿Estás seguro de querer CREAR un borrador de contenido?', 'blue large').(nucleo_acceso($vp['acceso']['api_borrador'])?'':'Solo acceso: '.verbalizar_acceso($vp['acceso']['api_borrador'])).'</td>
</tr>

</table>

</fieldset>
</form>
</div>


<table><tr><td valign="top">


<fieldset><legend>Contenido publicado</legend>
<table>
<tr>
<th></th>
<th>Texto</th>
<th>Cuando</th>
<th>Quien</th>
</tr>';
		$result2 = sql("SELECT *, (SELECT nick FROM users WHERE ID = api_posts.publicado_user_ID LIMIT 1) AS nick_publicado FROM api_posts WHERE api_ID = '".$r['api_ID']."' AND estado IN ('publicado', 'cron') ORDER BY time DESC");
		while($r2 = r($result2)) { 
			$txt .= '<tr>
<td align="right">'.(nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?boton(($r2['estado']=='cron'?'Cancelar':'Despublicar'), '/accion.php?a=api&b=borrar&ID='.$r2['post_ID'], '¿Estás seguro de querer ELIMINAR de forma irreversible esta publicación?', 'small red'):'').'</td>
<td width="200">'.($r2['estado']=='cron'?substr($r2['message'], 0, 150):'<a href="https://www.facebook.com/permalink.php?story_fbid='.explodear('_', $r2['mensaje_ID'], 1).'&id='.$r['item_ID'].'" target="_blank">'.substr($r2['message'], 0, 150).'</a>').'</td>
<td align="right" nowrap>'.timer(($r2['estado']=='cron'?$r2['time_cron']:$r2['time']), false, true).'</td>
<td>'.crear_link($r2['nick_publicado']).'</td>
</tr>';
		}
		$txt .= '</table>
</fieldset>

</td><td valign="top">

<fieldset><legend>Cola pendiente</legend>
<table>
<tr>
<th></th>
<th>Texto</th>
<th>Cuando</th>
<th>Quien</th>
</tr>';
		$result2 = sql("SELECT *, (SELECT nick FROM users WHERE ID = api_posts.pendiente_user_ID LIMIT 1) AS nick_pendiente, (SELECT nick FROM users WHERE ID = api_posts.borrado_user_ID LIMIT 1) AS nick_borrado FROM api_posts WHERE api_ID = '".$r['api_ID']."' AND estado = 'pendiente' ORDER BY time DESC");
		while($r2 = r($result2)) { 
			$txt .= '<tr>
<td nowrap>'.(nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?boton('Publicar', '/accion.php?a=api&b=publicar&ID='.$r2['post_ID'], '¿Estás seguro de querer PUBLICAR esta publicación?', 'small blue'):'').' '.(nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?boton('X', '/accion.php?a=api&b=borrar_borrador&ID='.$r2['post_ID'], '¿Estás seguro de querer BORRAR este borrador de publicación?', 'small red'):'').'</td>
<td width="200">'.$r2['message'].'</td>
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
	$txt_nav = array('/api'=>'API');
	$txt .= '<table>
<tr>
<th>Plataforma</th>
<th></th>
<th>Nombre</th>
<th></th>
<th>Quien publica</th>
</tr>';
	$result = sql("SELECT * FROM api WHERE estado = 'activo'");
	while($r = r($result)) { 
		$txt .= '<tr>
<td>'.$r['pais'].'</td>
<td><a href="'.$r['url'].'"><img src="'.IMG.'ico/'.($r['tipo']=='facebook'?2:1).'_32.png" width="32" width="32" alt="'.ucfirst($r['tipo']).'" /></a></td>
<td style="font-size:18px;"><a href="/api/'.$r['api_ID'].'"><b>'.$r['nombre'].'</b></a></td>
<td>'.(nucleo_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])?boton('Publicar', '/api/'.$r['api_ID'], false, 'blue').' ':'').'</td>
<td>'.ucfirst(verbalizar_acceso($r['acceso_escribir'], $r['acceso_cfg_escribir'])).'</td>
</tr>';
	}
	$txt .= '</table>';

}






$txt_menu = 'demo';
include('theme.php');
?>