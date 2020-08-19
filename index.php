<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$_GET[1] = strtolower(PAIS);
include('chat/index.php');


/*
if ((false) AND ($pol['user_ID'] == 1) OR ($pol['user_ID'] == 208162) OR ($pol['user_ID'] == 211725)) {
	
	$txt_nav = array('/'=>'Bienvenido a '.$pol['config']['pais_des']);

	echo '
<style type="text/css">
.mtitulo { text-align:right; font-size:20px; color:#555; }
.widthflex { max-width:100px; overflow:hidden; }
.legend2 { color:#BBB; }
</style>


<div class="col_4">
<fieldset><legend><a href="/chats"><b>Chats</b></a> <span class="legend2">&mdash; Conversar</span></legend>

<table width="100%">';

$result = sql("SELECT url, titulo,
(SELECT COUNT(DISTINCT nick) FROM chats_msg WHERE chat_ID = chats.chat_ID AND user_ID = 0 AND time > '".date('Y-m-d H:i:s', time() - 60*30)."') AS online
FROM chats 
WHERE pais = '".PAIS."' AND estado = 'activo' ORDER BY online DESC, fecha_creacion ASC 
LIMIT 6");
while ($r = r($result)) { 
	echo '<tr>
<td align="right" class="gris" title="Participantes en el chat"><b>'.num($r['online']).'</b></td>
<td class="widthflex" nowrap>'.($r['url']==strtolower(PAIS)?'<span style="float:right;">'.boton(_('Entrar'), '/chats/'.$r['url'].'', false, 'blue small').'</span>':'').'<a href="/chats/'.$r['url'].'">'.($r['url']==strtolower(PAIS)?'<b style="font-size:17px;">'.$r['titulo'].'</b>':$r['titulo']).'</b></a></td>
</tr>';
}
echo '</table>




</fieldset>
</div>

<div class="col_4">
<fieldset><legend><a href="/foro"><b>Foro</b></a> <span class="legend2">&mdash; Debatir</span></legend>
<table>';

$result = sql("SELECT url, title, num, votos, votos_num,
(SELECT url FROM ".SQL."foros WHERE ID = ".SQL."foros_hilos.sub_ID LIMIT 1) AS sub_url
FROM ".SQL."foros_hilos
WHERE estado = 'ok' AND votos > 1
ORDER BY time_last DESC
LIMIT 6");
while($r = r($result)) {
	echo '<tr>
<td align="right" title="Votos">'.confianza($r['votos'], $r['votos_num']).'</td>
<td width="100%" class="widthflex" title="'.$r['title'].'" nowrap><a href="/foro/'.$r['sub_url'].'/'.$r['url'].'">'.$r['title'].'</a></td>
<td align="right" title="Mensajes" class="gris"><b>'.num($r['num']).'<b></td>
</tr>';
}


	echo '</table>

</fieldset>
</div>

<div class="col_4">
<fieldset><legend><a href="/votacion"><b>Votaciones</b></a> <span class="legend2">&mdash; Decidir</span></legend>
<table width="100%">';
$linea = 0;
$result = sql("SELECT ID, pregunta, time, time_expire, user_ID, estado, num, num_censo, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, cargo_ID,
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS ha_votado
FROM votacion
WHERE estado IN ('ok', 'end') AND pais = '".PAIS."'
ORDER BY estado ASC, time_expire DESC
LIMIT 8");
while($r = r($result)) {

	if ((nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar'])) AND (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) {
		echo '<tr'.($r['estado']=='end'&&++$linea==1?' style="border-top:1px solid #CCC;"':'').'>
<td align="right" class="gris" title="'._('Participación').': '.($r['num_censo']==0?0:num($r['num']*100/$r['num_censo'], 1)).'% ('.num($r['num_censo']).')"><b>'.num($r['num']).'</b></td>

<td width="100%" class="widthflex" nowrap>'.(($r['estado']=='ok'&&!$r['ha_votado'])||($r['estado']=='end')?'<span style="float:right;margin-right:-5px;"><a href="/votacion/'.$r['ID'].'" class="button small blue" style="margin-top:-2px;">'.($r['estado']=='ok'?_('Votar'):_('Resultado')).'</a></span>':'').($r['cargo_ID']?'<a href="/cargos/'.$r['cargo_ID'].'"><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" width="16" height="16" /></a> ':'').'<a href="/votacion/'.$r['ID'].'">'.$r['pregunta'].'</a></td>
</tr>';
	}
}

echo '</table>



</fieldset>
</div>



<hr />



<div class="col_6">
<fieldset><legend>Notificaciones</legend>
...
</fieldset>
</div>

<div class="col_3">
<fieldset><legend>Cargos</legend>
</fieldset>
</div>

<div class="col_3">
<fieldset><legend>Elecciones</legend>
</fieldset>
</div>


<div style="height:100px;"></div>';


} else {
	// CHAT PLAZA
	$_GET[1] = strtolower(PAIS);
	include('inc-chats.php');
}


echo mysqli_error($link);

$txt_description = $pol['config']['pais_des'].'. '.PAIS;
$txt_menu = 'comu';

*/