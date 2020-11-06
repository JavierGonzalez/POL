<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 

$txt_title = _('Control').': '._('Gobierno');
$txt_nav = array('/control'=>_('Control'), '/control/gobierno'=>_('Gobierno'));

$txt_tab['/control/gobierno'] = _('Gobierno');
$txt_tab['/control/gobierno/privilegios'] = _('Privilegios');
if (ECONOMIA) { $txt_tab['/control/gobierno/economia'] = _('Economía'); }
$txt_tab['/control/gobierno/notificaciones'] = _('Notificaciones');
$txt_tab['/control/gobierno/foro'] = _('Configuración foro');
$txt_tab['/control/gobierno/categorias'] = _('Categorías');

if (nucleo_acceso($vp['acceso']['control_gobierno'])) { $dis = null; } else { $dis = ' disabled="disabled"'; }

$defcon_bg = array('1' => 'white','2' => 'red','3' => 'yellow','4' => 'green','5' => 'blue');

if ($_GET[1] == 'categorias') {
    $txt_nav[] = _('Categorías');

    if (nucleo_acceso($vp['acceso']['control_gobierno'])) { $dis = ''; } else { $dis = ' disabled="disabled"'; }

    echo '<form action="/accion/gobierno/categorias/editar" method="post">

<table border="0" cellspacing="0" cellpadding="4">

<tr>'.$_GET[3].'
<th>'._('Orden').'</th>
<th>'._('Nombre').'</th>
<th>'._('Tipo').'</th>
<th>'._('Nivel').'</th>
<th>Actual</th>
<th>Publicable</th>
</tr>';
$subforos = '';
$result = sql_old("SELECT * FROM cat WHERE pais = '".PAIS."' ORDER BY tipo DESC, orden ASC");
while($r = r($result)){
    
    $num = 0;
    if ($r['tipo'] == 'docs') {
        $result2 = sql_old("SELECT COUNT(*) AS el_num FROM docs WHERE pais = '".PAIS."' AND cat_ID = '".$r['ID']."'");
        while($r2 = r($result2)){ $num = $r2['el_num']; }
    } elseif ($r['tipo'] == 'empresas') {
        $result2 = sql_old("SELECT COUNT(*) AS el_num FROM empresas WHERE pais = '".PAIS."' AND cat_ID = '".$r['ID']."'");
        while($r2 = r($result2)){ $num = $r2['el_num']; }
    }
    $checkbox = '<input class="checkbox_impuestos" type="checkbox" name="'.$r['ID'].'_publicable" value="1"'.$disabled;
    
    if ($r['publicar'] == '1') {
        $checkbox .= ' checked="checked"';
    }
    $checkbox .= ' />';
        
    echo '<tr>
<td><input type="text" style="text-align:right;" name="'.$r['ID'].'_orden" size="1" maxlength="3" value="'.$r['orden'].'" /></td>

<td><input type="text" name="'.$r['ID'].'_nombre" size="30" maxlength="50" value="'.$r['nombre'].'" style="font-weight:bold;" /></td>

<td>'.ucfirst($r['tipo']).'</td>

<td><input type="text" style="text-align:right;" name="'.$r['ID'].'_nivel" size="1" maxlength="3" value="'.$r['nivel'].'" /></td>

<td align="right" style="color:#999;" nowrap="nowrap"><b>'.$num.'</b></td>
<td align="center">'.$checkbox.'
</td>


<td>'.($num==0?boton('Eliminar', '/accion/gobierno/categorias/eliminar?ID='.$r['ID'], false, 'small red'):'').'</td>
</tr>'."\n";
}

    echo '
<tr>
<td align="center" colspan="8"><input value="'._('Guardar cambios').'" style="font-size:22px;" type="submit"'.$dis.' /></td>
</tr>
</table>
</form>


<fieldset><legend>'._('Crear categoría').'</legend>
<form action="/accion/gobierno/categorias/crear" method="post">
<table border="0" cellspacing="3" cellpadding="0">
<tr>
<td>'._('Nombre').':</td>
<td><input type="text" name="nombre" size="10" maxlength="30" value="" /></td>
'.(ECONOMIA?'<td><select name="tipo"><option value="docs">'._('Documentos').'</option><option value="empresas">'._('Empresas').'</option></select></td>':'').'
<td><strong>Publicable &nbsp;</strong><input class="checkbox_impuestos" type="checkbox" name="publicable" value="1" /></td>
<td><input value="'._('Crear categoría').'" style="font-size:18px;" type="submit"'.$dis.' /></td>
</tr>
</table>
</form>
</fieldset>';



} else if ($_GET[1] == 'privilegios') {
    $txt_nav[] = _('Privilegios');
    
    if (!ECONOMIA) { unset($vp['acceso']['control_sancion']); }
    if (ASAMBLEA) { unset($vp['acceso']['parlamento']); }

    $privilegios_array = array(
'control_gobierno'=>_('Configuración principal'),
'control_cargos'=>_('Configurar cargos'),
'control_grupos'=>_('Configurar grupos'),
'control_sancion'=>_('Imponer sanciones'),
'crear_partido'=>_('Crear partido'),
'examenes_decano'=>_('Gestionar exámenes'),
'examenes_profesor'=>_('Crear preguntas de examen'),
'foro_borrar'=>_('Moderar foro'),
'kick'=>_('Kickear (bloqueos temporales)'),
'kick_quitar'=>_('Quitar kicks'),
'parlamento'=>_('Aprobar votación de parlamento'),
'referendum'=>_('Aprobar referéndums'),
'sondeo'=>_('Aprobar sondeos'),
'votacion_borrador'=>_('Crear borradores de votación'),
'control_socios'=>_('Gestión de socios'),
'api_borrador'=>_('Crear borradores en API'),
'cargo'=>_('Control cargos'),
'control_docs'=>_('Control de los documentos'),
'crear_cuenta'=>_('Crear cuentas'),
'gestion_mapa'=>_('Gestión del mapa')
);


echo '<fieldset>'._('Los privilegios sirven para gestionar permisos especiales del sistema. Este panel muestra los privilegios y quien los ostenta actualmente').'.</fieldset>
<fieldset><legend>'._('Privilegios').'</legend><form action="/accion/gobierno/privilegios" method="POST"><table>
<tr>
<th></th>
<th>'._('Configuración').'</th>
<th>¿'._('Quien tiene acceso').'?</th>
</tr>';
    foreach ($vp['acceso'] AS $acceso => $cfg) {
        echo '<tr>
<td align="right" nowrap="nowrap"><b>'.$privilegios_array[$acceso].'</b></td>
<td>'.($acceso=='control_gobierno'?'':control_acceso(false, $acceso, $cfg[0], $cfg[1], 'anonimos ciudadanos_global', true)).'</td>
<td>'.ucfirst(verbalizar_acceso($cfg)).'</td>
</tr>';
    }
    echo '<tr><td colspan="3" align="center">'.boton(_('Guardar'), (nucleo_acceso($vp['acceso']['control_gobierno'])?'submit':false), '¿Estás seguro de querer MODIFICAR los privilegios?', 'large red').'</td></tr></table></form></fieldset>';


} elseif ($_GET[1] == 'notificaciones') {
    
    $txt_nav[] = _('Notificaciones');
    
    echo '<fieldset>'._('Las notificaciones son mensajes eventuales enviados a cada usuario que aparecen de forma resaltada en el menú de notificaciones. Este panel permite crear notificaciones personalizadas.').'</fieldset>
    
<form action="/accion/gobierno/notificaciones/add" method="post">

<fieldset><legend>'._('Crear notificación (para todos los ciudadanos)').'</legend>

<table border="0">
<tr>
<td>'._('Texto').': </td>
<td><input type="text" name="texto" value="" size="52" maxlength="50" required /></td>
</tr>

<tr>
<td>URL: </td>
<td><input type="url" name="url" value="" size="64" maxlength="80" required placeholder="http://" /> ('._('si no cabe usa un acortador').')</td>
</tr>

<tr>
<td>Destino: </td>
<td>'.control_acceso(false, 'acceso', ($_POST['ciudadanos']?'privado':'ciudadanos'), $_POST['ciudadanos'], 'anonimos ciudadanos_global excluir', true).'</td>
</tr>

<tr>
<td></td>
<td>
'.boton(_('Crear notificación'), (nucleo_acceso($vp['acceso']['control_gobierno'])?'submit':false), '¿Estás seguro de crear esta notificación?\n\n¡Cuidado! compruébalo inmediatamente, en caso de error puedes borrarlo.', 'red').'</td>
</tr>
</table>
</fieldset>

</form>

<fieldset><legend>'._('Notificaciones').'</legend>
<table border="0" cellspacing="0" cellpadding="4">


<tr>
<th>'._('Cuando').'</th>
<th>'._('Mensaje').'</th>
<th>'._('Emitidas').'</th>
<th colspan="2">Clicks</th>
<th></th>
</tr>';
    $result = sql_old("SELECT *, COUNT(*) AS num FROM notificaciones WHERE emisor = '".PAIS."' GROUP BY emisor, texto ORDER BY time DESC");
    while($r = r($result)){

        $leido = 0;
        $result2 = sql_old("SELECT COUNT(*) AS num FROM notificaciones WHERE texto = '".$r['texto']."' AND visto = 'true'");
        while($r2 = r($result2)){ $leido = $r2['num']; }

        echo '<tr>
<td align="right">'.timer($r['time']).'</td>
<td><a href="'.$r['url'].'">'.$r['texto'].'</a></td>
<td align="right"><b>'.num($r['num']).'</b></td>
<td align="right">'.num($leido).'</td>
<td align="right">'.num($leido*100/$r['num'], 2).'%</td>
<td>'.(nucleo_acceso($vp['acceso']['control_gobierno'])?boton('X', '/accion/gobierno/notificaciones/borrar&noti_ID='.$r['noti_ID'], false, 'small'):boton('X', false, false, 'small')).'</td>
</tr>';
    }

    echo '</table></fieldset>';

} elseif ($_GET[1] == 'foro') {
    
    $txt_nav[] = _('Configuración foro');

    echo '<form action="/accion/gobierno/subforo" method="post">

<table border="0" cellspacing="0" cellpadding="4">

<tr>
<th colspan="2"></th>
<th colspan="3" align="center" style="background:#CCC;">'._('Acceso').'</th>
<th colspan="2"></th>
</tr>

<tr>
<th>'._('Orden').'</th>
<th>'._('Foro/descripción').'</th>
<th style="background:#5CB3FF;">'._('Leer').'</th>
<th style="background:#F97E7B;">'._('Crear hilos').'</th>
<th style="background:#F97E7B;">'._('Responder mensajes').'</th>
<th title="Numero de hilos mostrados en la home del foro">'._('Mostrar').'</th>
<th></th>
<th></th>
</tr>';
$subforos = '';
$result = sql_old("SELECT *,
(SELECT COUNT(*) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_hilos,
(SELECT SUM(num) FROM ".SQL."foros_hilos WHERE sub_ID = ".SQL."foros.ID AND estado = 'ok') AS num_msg
FROM ".SQL."foros WHERE estado = 'ok'
ORDER BY time ASC");
while($r = r($result)){

    $txt_li['leer'] = ''; $txt_li['escribir'] = ''; $txt_li['escribir_msg'] = '';
    foreach (nucleo_acceso('print') AS $at => $at_var) { 
        $txt_li['leer'] .= '<option value="'.$at.'"'.($at==$r['acceso_leer']?' selected="selected"':'').'>'.ucfirst(str_replace("_", " ", $at)).'</option>';
    }
    foreach (nucleo_acceso('print') AS $at => $at_var) { 
        $txt_li['escribir'] .= '<option value="'.$at.'"'.($at==$r['acceso_escribir']?' selected="selected"':'').($at=='anonimos'?' disabled="disabled"':'').'>'.ucfirst(str_replace("_", " ", $at)).'</option>';
    }

    foreach (nucleo_acceso('print') AS $at => $at_var) { 
        $txt_li['escribir_msg'] .= '<option value="'.$at.'"'.($at==$r['acceso_escribir_msg']?' selected="selected"':'').($at=='anonimos'?' disabled="disabled"':'').'>'.ucfirst(str_replace("_", " ", $at)).'</option>';
    }


    echo '<tr>
<td align="right"><input type="text" style="text-align:right;" name="'.$r['ID'].'_time" size="1" maxlength="3" value="'.$r['time'].'" /></td>
<td><a href="/foro/'.$r['url'].'/"><b>'.$r['title'].'</b></a><br />
<input type="text" name="'.$r['ID'].'_descripcion" size="25" maxlength="100" value="'.$r['descripcion'].'" /></td>


<td style="background:#5CB3FF;"><b><select name="'.$r['ID'].'_acceso_leer">'.$txt_li['leer'].'</select><br />
<input type="text" name="'.$r['ID'].'_acceso_cfg_leer" size="16" maxlength="900" value="'.$r['acceso_cfg_leer'].'" /></td>

<td style="background:#F97E7B;"><b><select name="'.$r['ID'].'_acceso_escribir">'.$txt_li['escribir'].'</select><br />
<input type="text" name="'.$r['ID'].'_acceso_cfg_escribir" size="16" maxlength="900" value="'.$r['acceso_cfg_escribir'].'" /></td>

<td style="background:#F97E7B;"><b><select name="'.$r['ID'].'_acceso_escribir_msg">'.$txt_li['escribir_msg'].'</select><br />
<input type="text" name="'.$r['ID'].'_acceso_cfg_escribir_msg" size="16" maxlength="900" value="'.$r['acceso_cfg_escribir_msg'].'" /></td>


<td align="right"><input type="text" style="text-align:right;" name="'.$r['ID'].'_limite" size="1" maxlength="2" value="'.$r['limite'].'" /></td>

<td align="right" style="color:#999;" nowrap="nowrap">'.number_format($r['num_hilos'], 0, ',', '.').' '._('hilos').'<br />
'.number_format($r['num_msg'], 0, ',', '.').' '._('mensajes').'</td>
<td>'.($r['num_hilos']==0?boton('Eliminar', '/accion/gobierno/eliminarsubforo?ID='.$r['ID'], false, 'small red'):'').'</td>
</tr>'."\n";

    if ($subforos) { $subforos .= '.'; }
    $subforos .= $r['ID'];
}

    echo '
<input name="subforos" value="'.$subforos.'" type="hidden" />
<tr>
<td align="center" colspan="8"><input value="'._('Guardar cambios').'" style="font-size:22px;" type="submit"'.$dis.' /></td>
</tr>
</table>
</form>

<fieldset><legend>'._('Crear nuevo foro').'</legend>
<form action="/accion/gobierno/crearsubforo" method="post">
<table border="0" cellspacing="3" cellpadding="0">
<tr>
<td>'._('Nombre').':</td>
<td><input type="text" name="nombre" size="10" maxlength="15" value="" /></td>
<td><input value="'._('Crear subforo').'" style="font-size:18px;" type="submit"'.$dis.' /></td>
</tr>
</table>
</form>
</fieldset>';


} elseif ($_GET[1] == 'economia') {


echo '
<form action="/accion/gobierno/economia" method="post">

<table border="0" cellspacing="3" cellpadding="0"><tr><td valign="top">


<fieldset><legend>'._('Economía principal').'</legend>

<table border="0"'.(ECONOMIA?'':' style="display:none;"').'>

<tr><td align="right">'._('Subsidio por desempleo').':</td><td><input style="text-align:right;" class="pols" type="text" name="pols_inem" size="3" maxlength="6" value="' . $pol['config']['pols_inem'] . '"'.$dis.' /> '.MONEDA.' '._('por día activo').'</td></tr>
<tr><td align="right">'._('Referencia').':</td><td><input style="text-align:right;" class="pols" type="text" name="pols_afiliacion" size="3" maxlength="6" value="' . $pol['config']['pols_afiliacion'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Crear empresa').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_empresa" size="3" maxlength="6" value="' . $pol['config']['pols_empresa'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Crear cuenta bancaria').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_cuentas" size="3" maxlength="6" value="' . $pol['config']['pols_cuentas'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Crear partido').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_partido" size="3" maxlength="6" value="' . $pol['config']['pols_partido'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Hacer examen').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_examen" size="3" maxlength="6" value="' . $pol['config']['pols_examen'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right"><acronym title="Mensaje privado a todos los Ciudadanos.">'._('mensaje global').'</acronym>:</td><td><input style="text-align:right;" type="text" name="pols_mensajetodos" size="3" maxlength="6" class="pols" value="' . $pol['config']['pols_mensajetodos'] . '"'.$dis.' /> '.MONEDA.' ('._('mínimo').' '.pols(300).')</td></tr>
<tr><td align="right">'._('Mensaje urgente').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_mensajeurgente" size="3" maxlength="6" value="' . $pol['config']['pols_mensajeurgente'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Crear chat').':</td><td><input class="pols" style="text-align:right;" type="text" name="pols_crearchat" size="3" maxlength="6" value="' . $pol['config']['pols_crearchat'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right"><acronym title="Porcentaje de los demás salarios que cobrará el ciudadano.">'._('Porcentaje de salario extra').':</td><td><input class="pols" style="text-align:right;" type="text" name="porcentaje_multiple_sueldo" size="3" maxlength="6" value="' . $pol['config']['porcentaje_multiple_sueldo'] . '"'.$dis.' /> % (Un valor entre 0 y 100)</td></tr>
</table>
</fieldset>

<fieldset><legend>'._('Impuestos').'</legend>
<table>
<tr><td align="right"><acronym title="Porcentaje que se impondrá al patrimonio de cada ciudadano que supere el limite. Se redondea. Incluye cuentas y personal.">'._('Impuesto de patrimonio').'</acronym>:</td><td><input style="text-align:right;" type="text" name="impuestos" size="3" maxlength="6" value="' . $pol['config']['impuestos'] . '"'.$dis.' /><b>%</b></td></tr>
<tr><td align="right"><acronym title="Periodicidad del cobro de impuesto de patrimonio">'._('Periodicidad impuesto de patrimonio').'</acronym>:</td>
    <td>
        <select name="impuestos_periodicidad" id="impuestos_periodicidad" '.$dis.'>
            <option value="D" '. ($pol['config']['impuestos_periodicidad'] == "D" ? "selected" : "").'>Diaria</option>
            <option value="S" '. ($pol['config']['impuestos_periodicidad'] == "S" ? "selected" : "").'>Semanal</option>
            <option value="P" '. ($pol['config']['impuestos_periodicidad'] == "P" ? "selected" : "").'>Días pares</option>
            <option value="B" '. ($pol['config']['impuestos_periodicidad'] == "B" ? "selected" : "").'>Bisemanal (Miércoles y Domingo)</option>
        </select>
    </td></tr>
    <tr><td align="right"><acronym title="Porcentaje de impuestos que se impondrá a las transacciones de tipo Salario">'._('Impuesto de renta').'</acronym>:</td><td><input style="text-align:right;" type="text" name="impuestos_renta" size="3" maxlength="6" value="' . $pol['config']['impuestos_renta'] . '"'.$dis.' /><b>%</b></td></tr>
    <tr><td align="right"><acronym title="Porcentaje de impuestos que se impondrá a las transacciones que no sean de tipo Salario">'._('IVA').'</acronym>:</td><td><input style="text-align:right;" type="text" name="impuestos_iva" size="3" maxlength="6" value="' . $pol['config']['impuestos_iva'] . '"'.$dis.' /><b>%</b></td></tr>
<tr><td align="right"><acronym title="Limite minimo de patrimonio para recibir impuestos.">'._('Mínimo patrimonio').'</acronym>:</td><td><input class="pols" style="text-align:right;" type="text" name="impuestos_minimo" size="3" maxlength="6" value="' . $pol['config']['impuestos_minimo'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
</table>
</fieldset>

';


$sel = '';

echo '
</td><td valign="top">
<fieldset><legend>'._('Mapa').'</legend>
<table>
<tr><td align="right">'._('Precio solar').':</td><td><input style="text-align:right;" class="pols" type="text" name="pols_solar" size="3" maxlength="6" value="' . $pol['config']['pols_solar'] . '"'.$dis.' /> '.MONEDA.'</td></tr>
<tr><td align="right">'._('Factor de propiedad').':</td><td><input style="text-align:right;" type="text" name="factor_propiedad" size="3" maxlength="6" value="' . $pol['config']['factor_propiedad'] . '"'.$dis.' /> * '._('superficie = coste').'</td></tr>
<tr><td colspan="2"><h2>Configuración barrios</h2></td></tr>
';
$result = sql_old("SELECT ID, nombre, multiplicador_impuestos, altura_maxima FROM mapa_barrios", $link);
while($r = r($result)){
    echo '<tr><td colspan="2"><h3>'.$r['nombre'].'</h3></td></tr>';

    echo '<tr><td align="right">'._('Multiplicador impuestos').':</td><td><input style="text-align:right;" type="text" name="barrio_'.$r['ID'].'_impuestos" size="3" maxlength="6" value="' . $r['multiplicador_impuestos'] . '"'.$dis.' /> * '._('Valor por el que se multiplicará el coste de propiedad.').'</td></tr>';
    echo '<tr><td align="right">'._('Altura máxima').':</td><td><input style="text-align:right;" type="text" name="barrio_'.$r['ID'].'_altura" size="3" maxlength="6" value="' . $r['altura_maxima'] . '"'.$dis.' /> * '._('Número máximo de alturas habilitadas en el barrio.').'</td></tr>';
}

echo '</table>
</fieldset>
</td>
';

echo '

</td><td valign="top">


<fieldset><legend>'._('Salarios').'</legend>
<table border="0" cellspacing="3" cellpadding="0"'.(ECONOMIA?'':' style="display:none;"').'>';

$result = sql_old("SELECT nombre, cargo_ID, salario
FROM cargos
WHERE pais = '".PAIS."'
ORDER BY salario DESC");
while($r = r($result)){
    echo '<tr><td align="right">' . $r['nombre'] . ' <img src="'.IMG.'cargos/'.$r['cargo_ID'].'.gif" title="'.$r['nombre'].'" /></td><td><input style="text-align:right;" type="text" name="salario_' . $r['cargo_ID'] . '" size="3" maxlength="6" class="pols" value="' . $r['salario'] . '"'.$dis.' /> '.MONEDA.'</td></tr>';
}

echo '
</table>
</fieldset>

</td></tr></table>

<p style="text-align:center;">'.boton(_('Guardar'), ($dis?false:'submit'), false, 'large red').'</p>

</form>';




} else {


    function print_td_url($titulo, $name, $desc='') {
        return '<tr>
<td align="right" title="'.$desc.'">'.$titulo.':</td>
<td><input type="url" name="url_'.$name.'" value="'.$pol['config']['url'][$name].'" placeholder="http://" size="30" /></td>
</tr>';
    }

    $txt_header .= '
<script type="text/javascript">
function change_bg(img) {
$("#header").css("background","#FFFFFF url(\''.IMG.'bg/"+img+"\') repeat top left");
}
$(function() {
$("#fondos").hover(
    function(e){
        change_bg($(this).val()); },
    function(e){
        change_bg($(this).val());
    }
);
});
</script>';

$defcon = '<select name="defcon"'.$dis.' style="font-size:25px;color:grey;">';
for ($i=5;$i>=1;$i--) {
    if ($i == $pol['config']['defcon']) { $sel = ' selected="selected"'; } else { $sel = ''; }
    $defcon .= '<option value="' . $i . '" style="background:' . $defcon_bg[$i] . ';"' . $sel . '>' . $i . '</option>';
}
$defcon .= '</select>';

echo '
<form action="/accion/gobierno/config" method="post" enctype="multipart/form-data">

<table border="0" cellspacing="3" cellpadding="0"><tr><td valign="top">


<fieldset><legend>'._('Configuración principal').'</legend>

<table border="0" cellspacing="3" cellpadding="0">


<tr><td align="right">URL:</td><td>http://<b>'.PAIS.'</b>.virtualpol.com</td></tr>

<tr><td align="right">'._('Nombre').':</td><td><input type="text" name="pais_des" size="24" maxlength="40" value="'.$pol['config']['pais_des'].'" /></td></tr>



<tr><td align="right">'._('Tipo de plataforma').':</td><td>
<select name="tipo">';
foreach (array('plataforma', 'asamblea', 'simulador') AS $tipo) {
echo '<option value="'.$tipo.'"'.($tipo==$pol['config']['tipo']?' selected="selected"':'').'>'.ucfirst($tipo).'</option>';
}
echo '
</select></td></tr>


<tr><td align="right">'._('Zona horaria').':</td><td>
<select name="timezone">';
foreach (array('Europe/Madrid', 'America/New_York', 'Chile/Continental') AS $tipo) {
echo '<option value="'.$tipo.'"'.($tipo==$pol['config']['timezone']?' selected="selected"':'').'>'.ucfirst($tipo).'</option>';
}
echo '
</select></td></tr>


<tr><td align="right">'._('Idioma').':</td><td><select name="lang">';
$result = sql_old("SELECT valor FROM config WHERE pais = '".PAIS."' AND dato = 'lang'");
while ($r = r($result)) { $plataforma_lang = $r['valor']; }

foreach ($vp['langs'] AS $loc => $lang) {
    echo '<option value="'.$loc.'"'.($loc==$plataforma_lang?' selected="selected"':'').'>'.$lang.'</option>';
}
echo '</select></td></tr>

'.(!ECONOMIA?'<input type="hidden" name="defcon" value="5" /><input type="hidden" name="online_ref" value="0" />':'<tr><td align="right">DEFCON:</td>
<td>'.$defcon.'</td></tr>

<tr><td align="right">'._('Referencia').':</td>
<td><input type="number" name="online_ref" size="3" maxlength="10" value="' . round($pol['config']['online_ref']/60) . '" min="5" max="90" required /> min online (' . duracion($pol['config']['online_ref'] + 1) . ')</td>

</tr>');

$palabra_gob = explode(':', $pol['config']['palabra_gob']);


echo '

<tr><td align="right">'._('Expiración de candidatura tras').':</td>
<td><input type="number" name="examenes_exp" value="'.$pol['config']['examenes_exp'].'" min="5" max="90" required /> '._('días').' '._('inactivo').'<td></tr>

<tr><td align="right">'._('Expiración chats').':</td>
<td><input type="number" name="chat_diasexpira" value="'.$pol['config']['chat_diasexpira'].'" min="10" max="90" required /> <acronym title="Dia inactivos">'._('días').'</acronym></td></tr>

<tr><td align="right">'._('Repetición de examenes').':</td>
<td><input type="number" name="examen_repe" value="'.$pol['config']['examen_repe'].'" required /> <acronym title="Tiempo requerido antes de poder repetir un examen">'._('segundos').'</acronym></td></tr>


<tr><td valign="top" colspan="2">'._('Mensaje del Gobierno').':<br />
<textarea name="palabra_gob" style="width:400px;height:100px;">'.strip_tags($pol['config']['palabra_gob']).'</textarea>
</td></tr>

</table>
</fieldset>


<fieldset><legend>URLs (EN DESARROLLO...)</legend>
<table>
'.print_td_url('Carta Magna', 'cartamagna', 'Documento, constitucion, declaración, ley, reglas o normas principales.').'
'.print_td_url('Ayuda', 'ayuda', 'Documento de ayuda').'
'.print_td_url('Bienvenida', 'bienvenida', 'Documento de bienvenida').'
'.print_td_url('Vídeo', 'video', 'Video de introducción a la plataforma').'
'.print_td_url('Facebook', 'fbfanpage', 'Fanpage de Facebook').'
'.print_td_url('Twitter', 'twitter', 'Cuenta de twitter').'
'.print_td_url('Google+', 'googleplus', 'Cuenta de Google+').'
</table>
</fieldset>


</td><td valign="top">



<fieldset><legend>'._('Diseño').'</legend>
<table>
<tr>
<td align="right">'._('Tapiz').':</td>
<td>
<select id="fondos" name="bg">
<option value="">'._('Por defecto').'</option>';

$sel2[$pol['config']['bg']] = ' selected="selected"';

foreach (glob('img/bg/*') AS $file) {
    $archivo = basename($file);
    if (preg_match("/.(gif|jpg|png)$/i", $archivo))
        echo '<option value="'.$archivo.'"'.$sel2[$archivo].' onclick="change_bg(\''.$archivo.'\')"  onmouseover="change_bg(\''.$archivo.'\')">'.$archivo.'</option>';
}

echo '</select>
</td>
</tr>

<tr>
<td align="right">'._('Añadir tapiz').':</td>
<td nowrap><input type="file" name="nuevo_tapiz" accept="image/jpg" /> (jpg, 1440x100)</td>
</tr>

<tr>
<td align="right" nowrap>'._('Bandera').' (80x50):</td>
<td nowrap><img src="'.IMG.'banderas/'.PAIS.'.png?'.rand(10000,99999).'" width="80" height="50" style="border:1px solid #CCC;background:#FFF;" />  (png, 80x50, max 50kb)<br /><input type="file" name="nuevo_bandera" accept="image/png" /></td>
</tr>


<tr>
<td align="right" nowrap>'._('Logo').' (200x60):</td>
<td nowrap><img src="'.IMG.'banderas/'.PAIS.'_logo.png?'.rand(10000,99999).'" width="200" height="60" style="border:1px solid #CCC;background:#FFF;" />  (png, 200x60)<br /><input type="file" name="nuevo_logo" accept="image/png" /></td>
</tr>




<tr>
<td align="right">'._('Color de fondo').':</td>
<td><input type="color" name="bg_color" value="'.strtolower($pol['config']['bg_color']).'" style="background:'.$pol['config']['bg_color'].';width:150px;" /></td>
</tr>

</table>
</fieldset>

<p>'.boton(_('Guardar'), ($dis?false:'submit'), false, 'large red').'</p>

</td></tr></table>

</form>';

}