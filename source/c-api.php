<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');
$txt_title = 'API';
$txt_nav = array('/api'=>'API');



/* PASOS PARA CONSEGUIR PRIVILEGIO

1. https://www.facebook.com/dialog/oauth?client_id=__APP_ID__&redirect_uri=http://www.virtualpol.com/&scope=manage_pages,offline_access,publish_stream

2. https://graph.facebook.com/oauth/access_token?client_id=__APP_ID__&redirect_uri=http://www.virtualpol.com/&client_secret=__APP_KEY__&code=CODIGO_ANTERIOR

3. https://graph.facebook.com/me/accounts?access_token=CODIGO_ANTERIOR
*/



$result = sql("SELECT post_ID FROM api_posts WHERE estado = 'cron' AND time_cron <= '".$date."' LIMIT 1");
while($r = r($result)) {
	include_once('inc-functions-accion.php');
	//api_facebook('publicar', $r['post_ID'], true);
}


if (is_numeric($_GET['a'])) {
	
	// https://developers.facebook.com/docs/reference/api/post/
	$result = sql("SELECT * FROM api WHERE api_ID = '".$_GET['a']."' LIMIT 1");
	while($r = r($result)) {
		$txt_nav = array('/api'=>'API', '/api/'.$r['api_ID']=>$r['nombre']);

		if (is_numeric($_GET['c'])) {
			$result2 = sql("SELECT * FROM api_posts WHERE post_ID = '".$_GET['c']."' AND estado = 'pendiente' LIMIT 1");
			$edit = r($result2);
		}

		$txt .= '
<h1><img src="'.IMG.'ico/'.($r['tipo']=='facebook'?2:1).'_32.png" width="32" width="32" alt="'.ucfirst($r['tipo']).'" /> <a href="'.$r['url'].'" target="_blank">'.$r['nombre'].'</a> <button onclick="$(\'#crear_borrador\').slideToggle(\'slow\');">Escribir borrador</button></h1>

<fieldset><legend>Linea editorial</legend>
<p>'.$r['linea_editorial'].'</p>
</fieldset>

<fieldset id="crear_borrador"'.($_GET['b']=='escribir'?'':' style="display:none;"').'><legend>Crear borrador de publicación</legend>

<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=api&b=crear" method="post">

<input type="hidden" name="api_ID" value="'.$r['api_ID'].'" />
'.(is_array($edit)?'<input type="hidden" name="post_ID" value="'.$edit['post_ID'].'" />':'').'

<table>

<tr>
<td align="right">Texto:</td>
<td><textarea name="message" style="width:500px;height:150px;" required>'.$edit['message'].'</textarea></td>
</tr>

<tr>
<td align="right">Enlace enriquecido:</td>
<td><input type="url" name="link" value="'.$edit['link'].'" size="50" placeholder="http://" /> (Opcional)</td>
</tr>

<tr>
<td align="right">Publicación programada:</td>
<td><input type="text" name="time_cron" value="'.($edit['time_cron']?$edit['time_cron']:$date).'" /> (Se publicará después de esta fecha)</td>
</tr>


<tr>
<td align="right">Imagen incrustada:</td>
<td><input type="url" name="picture" value="'.$edit['picture'].'" size="50" placeholder="http://" /> (Opcional)</td>
</tr>


<!--
<tr>
<td align="right">Vídeo incrustado:</td>
<td><input type="url" name="source" value="'.$edit['source'].'" size="50" placeholder="http://" /> (Opcional)</td>
</tr>
-->

<tr>
<td></td>
<td>'.boton((is_array($edit)?_('Modificar borrador'):_('Crear borrador')), (nucleo_acceso($r['acceso_borrador'])?'submit':false), false, 'blue large').(nucleo_acceso($r['acceso_borrador'])?'':_('Solo acceso').': '.verbalizar_acceso($r['acceso_borrador'])).'</td>
</tr>

</table>
</form>
</fieldset>



<style type="text/css">
.apic { 
	width:200px;
	max-height:70px;
	overflow-y:auto;
	font-size:13px;
}
</style>


<table><tr><td valign="top">


<fieldset><legend>Contenido publicado</legend>
<table>
<tr>
<th></th>
<th>Texto</th>
<th>Cuando</th>
<th>Publicado</th>
</tr>';
		$result2 = sql("SELECT *, (SELECT nick FROM users WHERE ID = api_posts.publicado_user_ID LIMIT 1) AS nick_publicado FROM api_posts WHERE api_ID = '".$r['api_ID']."' AND estado IN ('publicado', 'cron') ORDER BY time DESC");
		while($r2 = r($result2)) { 
			$txt .= '<tr>
<td align="right">'.(nucleo_acceso($r['acceso_escribir'])?boton(($r2['estado']=='cron'?'Cancelar':'Despublicar'), '/accion.php?a=api&b=borrar&ID='.$r2['post_ID'], '¿Estás seguro de querer ELIMINAR de forma irreversible esta publicación?', 'small red'):'').'</td>
<td width="200"><div class="apic">'.($r2['estado']=='cron'?$r2['message']:'<a href="https://www.facebook.com/permalink.php?story_fbid='.explodear('_', $r2['mensaje_ID'], 1).'&id='.$r['item_ID'].'" target="_blank" style="font-size:10px;">'.$r2['message'].'</a>').'</div></td>
<td align="right" nowrap>'.timer(($r2['estado']=='cron'?$r2['time_cron']:$r2['time']), false, true).'</td>
<td>'.crear_link($r2['nick_publicado']).'</td>
</tr>';
		}
		$txt .= '</table>
</fieldset>

</td><td valign="top">

<fieldset><legend>Cola pendiente (borradores)</legend>
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
<td nowrap>'.(nucleo_acceso($r['acceso_escribir'])?boton('Publicar', '/accion.php?a=api&b=publicar&ID='.$r2['post_ID'], '¿Estás seguro de querer PUBLICAR esta publicación?', 'small blue').' '.boton('Editar', '/api/'.$r['api_ID'].'/escribir/'.$r2['post_ID'], false, 'small orange'):'').' '.(nucleo_acceso($r['acceso_escribir'])?boton('X', '/accion.php?a=api&b=borrar_borrador&ID='.$r2['post_ID'], '¿Estás seguro de querer BORRAR este borrador de publicación?', 'small red'):'').'</td>
<td width="200"><div class="apic">'.$r2['message'].'</div></td>
<td align="right" nowrap>'.timer($r2['time']).'</td>
<td>'.($r2['estado']=='pendiente'?crear_link($r2['nick_pendiente']):crear_link($r2['nick_borrado'])).'</td>
</tr>';
		}
		$txt .= '</table>
</fieldset>

</tr></table>';
	}
	
} else {
	
	$txt .= '
<fieldset>La API sirve para conectar VirtualPol con servicios externos (como Facebook, Twitter, Wordpress..). Actualmente permite controlar la publicación de fanpages de Facebook desde VirtualPol. Esta solución permite democratizar fanpages, gestionarlas de forma abierta y facilitar la custodia.</fieldset>

<table>
<tr>
<th></th>
<th></th>
<th title="Publicado/Pendiente">Publicado</th>
<th></th>
<th>Quien publica</th>
</tr>';
	$result = sql("SELECT *,
(SELECT COUNT(*) FROM api_posts WHERE api_ID = api.api_ID AND estado = 'publicado' LIMIT 1) AS num_publicado,
(SELECT COUNT(*) FROM api_posts WHERE api_ID = api.api_ID AND estado = 'pendiente' LIMIT 1) AS num_pendiente
FROM api WHERE estado = 'activo' AND (pais = '".PAIS."' OR pais IS NULL)");
	while($r = r($result)) {
		$txt .= '<tr>
<td><a href="'.$r['url'].'"><img src="'.IMG.'ico/'.($r['tipo']=='facebook'?2:1).'_32.png" width="32" width="32" alt="'.ucfirst($r['tipo']).'" /></a></td>
<td style="font-size:18px;"><a href="/api/'.$r['api_ID'].'"><b>'.$r['nombre'].'</b></a></td>
<td style="font-size:20px;" align="right"><b>'.$r['num_publicado'].'</b>/'.$r['num_pendiente'].'</td>
<td>'.(nucleo_acceso($r['acceso_borrador'])?boton('Escribir borrador', '/api/'.$r['api_ID'].'/escribir', false, 'blue'):'').'</td>
<td>'.ucfirst(verbalizar_acceso($r['acceso_escribir'])).'</td>
</tr>';
	}
	$txt .= '</table>';
}


$txt_menu = 'com';
include('theme.php');
?>