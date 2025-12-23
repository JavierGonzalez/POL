<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




$txt_title = _('Borradores de votaciones');
$txt_nav = array('/votacion'=>_('Votaciones'), '/votacion/borradores'=>_('Borradores de votación'));
$txt_tab = array('/votacion/crear'=>_('Crear votación'));

echo '<table border="0" cellpadding="1" cellspacing="0">';

$result = sql_old("SELECT ID, duracion, tipo_voto, pregunta, time, time, time_expire, user_ID, estado, num, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver,
(SELECT nick FROM users WHERE ID = votacion.user_ID LIMIT 1) AS nick
FROM votacion
WHERE estado = 'borrador' AND pais = '".PAIS."'
ORDER BY time DESC
LIMIT 5000");
while($r = r($result)) {

	if (nucleo_acceso($vp['acceso'][$r['tipo']])) {
		$boton_borrar = boton('X', '/accion/votacion/eliminar?ID='.$r['ID'], '¿Estás seguro de querer ELIMINAR este borrador de votación?', 'small');
		$boton_iniciar = boton(_('Iniciar'), '/accion/votacion/iniciar?ref_ID='.$r['ID'], '¿Estás seguro de querer INICIAR esta votación?', 'small');
	} else {
		$boton_borrar = boton('X', false, false, 'small');
		$boton_iniciar = boton(_('Iniciar'), false, false, 'small');
	}
	
	echo '<tr>
<td valign="top" align="right" nowrap="nowrap"><b>'.ucfirst($r['tipo']).'</b><br />'.$boton_borrar.' '.$boton_iniciar.'<br />'.boton(_('Previsualizar'), '/votacion/'.$r['ID'], false, 'small').'</td>
<td><a href="/votacion/crear/'.$r['ID'].'"><b style="font-size:18px;">'.$r['pregunta'].'</b></a><br />
'._('Creado hace').' <b><span class="timer" value="'.strtotime($r['time']).'"></span></b> '._('por').' '.crear_link($r['nick']).', '._('editado hace').' <span class="timer" value="'.strtotime($r['time_expire']).'"></span>
<br />
'._('Ver').': <em title="'.$r['acceso_cfg_ver'].'">'.$r['acceso_ver'].'</em>, '._('votar').': <em title="'.$r['acceso_cfg_votar'].'">'.$r['acceso_votar'].'</em>, '._('tipo voto').': <em>'.$r['tipo_voto'].'</em>, '._('duración').': <em>'.duracion($r['duracion']).'</em></td>
</tr>';
}
echo '</table>';