<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



if (is_numeric($_GET[1])) { // VER VOTACION
	include_once('votacion.php');
	return;
}


// Calcular votos por hora
$result = sql_old("SELECT COUNT(*) AS num FROM votacion_votos WHERE time >= '".date('Y-m-d H:i:s', time() - 60*60*2)."'");
while($r = r($result)) { $votos_por_hora = num($r['num']/2); }

$result = sql_old("SELECT COUNT(*) AS num FROM votacion WHERE estado = 'borrador' AND pais = '".PAIS."'");
while($r = r($result)) { $borradores_num = $r['num']; }

$txt_title = _('Votaciones');
$txt_nav = array('/votacion'=>_('Votaciones'));
$txt_tab = array('/elecciones'=>_('Elecciones'), '/votacion/borradores'=>_('Borradores').' ('.$borradores_num.')', '/votacion/crear'=>_('Crear votación'));

echo '

<fieldset><legend>'._('En curso').' &nbsp; ('.$votos_por_hora.' '._('votos/hora').')</legend>
<table border="0" cellpadding="1" cellspacing="0">
<tr>
<th></th>
<th>'._('Votos').'</th>
<th></th>
<th colspan="4" align="left"><span style="float:right;">Argumentos</span>'._('Finaliza').'... &nbsp;</th>
</tr>';
$mostrar_separacion = true;

$result = sql_old("SELECT ID, pais, pregunta, time, time_expire, user_ID, estado, num, num_censo, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, cargo_ID,
(SELECT ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '" . $pol['user_ID'] . "' LIMIT 1) AS ha_votado,
(SELECT COUNT(*) FROM votacion_argumentos WHERE ref_ID = votacion.ID AND votos >= 0) AS argumentos_num
FROM votacion
WHERE estado = 'ok'".($_GET[1]=='todas'?"":" AND pais = '".PAIS."'")."
ORDER BY time_expire ASC");
while($r = r($result)) {
	$time_expire = strtotime($r['time_expire']);
	$time = strtotime($r['time']);

	if ((!isset($pol['user_ID'])) OR ((!$r['ha_votado']) AND ($r['estado'] == 'ok') AND (nucleo_acceso($r['acceso_votar'],$r['acceso_cfg_votar'])))) { 
		$votar = '<a href="'.(isset($pol['user_ID'])?'/votacion/'.$r['ID']:'/registrar').'" class="button small blue">'._('Votar').'</a> ';
	} else { $votar = ''; }

	if (($r['acceso_ver'] == 'anonimos') OR (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) {
		echo '<tr>
<td width="100"'.($r['tipo']=='referendum'||$r['tipo']=='elecciones'?' style="font-weight:bold;"':'').'>'.ucfirst(_($r['tipo'])).'</td>
<td align="right" title="'._('Participación').': '.($r['num_censo']==0?0:num($r['num']*100/$r['num_censo'], 2)).'% ('.num($r['num_censo']).')"><b>'.num($r['num']).'</b></td>
<td>'.$votar.($r['cargo_ID']?'<a href="/cargos/'.$r['cargo_ID'].'"><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" width="16" height="16" /></a> ':'').'<a href="/votacion/'.$r['ID'].'" style="'.($r['tipo']=='referendum'||$r['tipo']=='elecciones'?'font-weight:bold;':'').(!in_array($r['acceso_ver'], array('anonimos', 'ciudadanos', 'ciudadanos_global'))?'color:red;" title="Votación privada':'').'">'.$r['pregunta'].'</a></td>
<td nowrap="nowrap" class="gris" align="right">'.timer($r['time_expire']).'</td>
<td nowrap="nowrap">'.($r['user_ID']==$pol['user_ID']&&$r['estado']=='ok'?boton('Cancelar', '/accion/votacion/finalizar?ID='.$r['ID'], '¿Seguro que quieres CANCELAR esta votacion y convertirla en un BORRADOR?', 'small red'):'').'</td>
<td>'.gbarra(round((time()-$time)*100)/($time_expire-$time), 60, false).'</td>
<td align="right">'.($r['argumentos_num']>0?'<b>'.num($r['argumentos_num']).'</b>':'').'</td>
</tr>';
	}
}
echo '</table></fieldset>';



$txt_header .= '<script type="text/javascript">

function ver_votacion(tipo) {
var estado = $("#c_" + tipo).is(":checked");
if (estado) {
	$(".v_" + tipo).show();
} else {
	$(".v_" + tipo).hide();
}
}

</script>';


echo '<fieldset><legend>'._('Finalizadas').'</legend>

<span style="color:#666;padding:3px 4px;border:1px solid #999;border-bottom:none;margin-left:100px;" class="redondeado">
<b>
<input type="checkbox" onclick="ver_votacion(\'elecciones\');" id="c_elecciones" checked="checked" /> '._('Elecciones').' &nbsp; 
<input type="checkbox" onclick="ver_votacion(\'referendum\');" id="c_referendum" checked="checked" /> '._('Referéndums').' &nbsp; 
</b>
<input type="checkbox" onclick="ver_votacion(\'sondeo\');" id="c_sondeo" checked="checked" /> '._('Sondeos').' &nbsp; 
<input type="checkbox" onclick="ver_votacion(\'parlamento\');" id="c_parlamento" checked="checked" /> '._('Parlamento').' &nbsp;  
<input type="checkbox" onclick="ver_votacion(\'cargo\');" id="c_cargo" checked="checked" /> '._('Cargo').' &nbsp;  
<input type="checkbox" onclick="ver_votacion(\'privadas\');" id="c_privadas" /> <span style="color:red;">'._('Privadas').'</span> &nbsp; 
<input type="checkbox" onclick="window.location.href = \'/votacion/todas\';" /> '._('Todas').' &nbsp; 
</span>

<hr />

<table border="0" cellpadding="1" cellspacing="0">
';
$mostrar_separacion = true;
$result = sql_old("SELECT ID, pais, pregunta, time, time_expire, user_ID, estado, num, num_censo, tipo, acceso_votar, acceso_cfg_votar, acceso_ver, acceso_cfg_ver, cargo_ID
FROM votacion
WHERE estado = 'end'".($_GET[1]=='todas'?"":" AND pais = '".PAIS."'")."
ORDER BY ".($_GET['order_by']=='num'?$_GET['order_by']:"time_expire")." DESC");
while($r = r($result)) {
	if (($r['acceso_ver'] == 'anonimos') OR (nucleo_acceso($r['acceso_ver'], $r['acceso_cfg_ver']))) {
		echo '<tr class="v_'.$r['tipo'].($r['acceso_ver']!='anonimos'?' v_privadas':'').'"'.(in_array($r['tipo'], array('referendum', 'parlamento', 'sondeo', 'elecciones', 'cargo'))&&in_array($r['acceso_ver'], array('anonimos', 'ciudadanos', 'ciudadanos_global'))?'':' style="display:none;"').'>
'.($_GET[1]=='todas'?'<td>'.$r['pais'].'</td>':'').'
<td width="100"'.($r['tipo']=='referendum'||$r['tipo']=='elecciones'?' style="font-weight:bold;"':'').'>'.ucfirst(_($r['tipo'])).'</td>
<td align="right" title="'._('Participación').': '.($r['num_censo']==0?0:num($r['num']*100/$r['num_censo'], 2)).'% ('.num($r['num_censo']).')"><b>'.num($r['num']).'</b></td>
<td>'.($r['cargo_ID']?'<a href="/cargos/'.$r['cargo_ID'].'"><img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" width="16" height="16" /></a> ':'').'<a href="/votacion/'.$r['ID'].'" style="'.($r['tipo']=='referendum'||$r['tipo']=='elecciones'?'font-weight:bold;':'').(!in_array($r['acceso_ver'], array('anonimos', 'ciudadanos', 'ciudadanos_global'))?'color:red;" title="Votación privada':'').'">'.$r['pregunta'].'</a></td>
<td nowrap="nowrap" align="right" class="gris">'.timer($r['time_expire']).'</td>
</tr>';
	}
}
echo '</table></fieldset>';
