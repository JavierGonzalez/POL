<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$txt_title = _('Control').': '._('Kicks');
$txt_nav = array('/control'=>_('Control'), _('Kicks'));
$txt_tab = array('/control/kick/expulsar'=>_('Kickear'));

if (($_GET[1] == 'info') AND ($_GET[2])) {

    $result = sql_old("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = kicks.autor LIMIT 1) AS nick_autor
FROM kicks
WHERE pais = '".PAIS."' AND ID = '".$_GET[2]."' LIMIT 1");
    while($r = r($result)){
        echo '
<p>'._('Motivo').': <b>'.$r['razon'].'</b></p>

<p>'._('Pruebas').':</p>

<p class="azul">'.str_replace("\n","<br />", $r['motivo']).'</p>';
    }

} elseif ($_GET[1]) {
    
    $txt_nav = array('/control'=>_('Control'), '/control/kicks'=>_('Kicks'), _('Kickear'));

    if ($_GET[1] == 'expulsar') { $_GET[1] = ''; }
    if (nucleo_acceso($vp['acceso']['kick'])) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }
    echo '
<p>'._('Esta acción privilegiada bloquea totalmente las acciones de un Ciudadano y los que comparten su IP').'.</p>

<form action="/accion/kick" method="post">
'.($_GET[2]?'<input type="hidden" name="chat_ID" value="'.$_GET[2].'" />':'').'
<ol>
<li><b>'._('Nick').':</b> '._('el ciudadano').'.<br /><input type="text" value="' . $_GET[1] . '" name="nick" size="20" maxlength="20" required /></li>

<li><b>'._('Duración').':</b> '._('duración temporal de este kick').'.<br />
<select name="expire">
<option value="120">2 min</option>
<option value="300">5 min</option>
<option value="600">10 min</option>
<option value="900">15 min</option>
<option value="1200">20 min</option>
<option value="1800" selected="selected">30 min</option>
<option value="2700">45 min</option>
<option value="4500">75 min</option>
<option value="3600">1 horas</option>
<option value="5400">1.5 horas</option>
<option value="7200">2 horas</option>
<option value="18000">5 horas</option>
<option value="86400">1 día</option>
<option value="172800">2 días</option>
<option value="259200">3 días</option>
<option value="518400">6 días</option>
<option value="777600">9 días</option>
</select></li>

<li><b>'._('Motivo breve').':</b> '._('frase con el motivo de este kick, se preciso').'.<br /><input type="text" name="razon" size="60" maxlength="255" required /></li>

<li><b>'._('Pruebas').':</b> '._('puedes pegar aquí las pruebas sobre el kick').'.<br /><textarea name="motivo" cols="70" rows="6" style="color: green; font-weight: bold;" required></textarea></p></li>


<li>'.boton(_('Kickear'), ($disabled==''?'submit':false), false, 'red').'</li></ol></form>
        
';
} else {
    echo '
<table border="0" cellspacing="1" cellpadding="">
<tr>
<th colspan="2">'._('Estado').'</th>
<th>'._('Afectado').'</th>
<th>'._('Autor').'</th>
<th>'._('Cuando').'</th>
<th>'._('Tiempo').'</th>
<th>'._('Razón').'</th>
<th></th>
</tr>';

sql_old("UPDATE kicks SET estado = 'inactivo' WHERE pais = '".PAIS."' AND estado = 'activo' AND expire < '" . $date . "'"); 
$margen_30dias	= date('Y-m-d 20:00:00', time() - 2592000); //30dias
$result = sql_old("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo, user_ID,
(SELECT nick FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = kicks.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = kicks.autor LIMIT 1) AS nick_autor
FROM kicks
WHERE pais = '".PAIS."' AND expire > '" . $margen_30dias . "' AND estado != 'expulsado'
ORDER BY expire DESC");
while($r = r($result)){
    if ((($r['autor'] == $pol['user_ID']) OR (nucleo_acceso($vp['acceso']['kick_quitar']))) AND ($r['estado'] == 'activo')) { $expulsar = boton('X', '/accion/kick/quitar?ID='.$r['ID'], '&iquest;Seguro que quieres hacer INACTIVO este kick?'); } else { $expulsar = ''; }

    $duracion = '<acronym title="'.$r['expire'].'">' . duracion((time() + $r['tiempo']) - strtotime($r['expire'])).'</acronym>';

    if ($r['estado'] == 'activo') {
        $estado = '<span style="color:red;">'._('Activo').'</span>';
    } elseif ($r['estado'] == 'cancelado') {
        $estado = '<span style="color:grey;">'._('Cancelado').'</span>';
    } else {
        $estado = '<span style="color:grey;">'._('Inactivo').'</span>';
    }
    if (!$r['expulsado_estado']) { $r['expulsado_estado'] = 'expulsado'; }

    echo '<tr><td valign="top"><img src="'.IMG.'varios/kick.gif" alt="Kick" border="0" /></td><td valign="top"><b>'.$estado.'</b></td><td valign="top"><b>'.($r['user_ID'] == 0?'Anonimo':crear_link($r['expulsado'], 'nick', $r['expulsado_estado'])).'</b></td><td valign="top" nowrap="nowrap"><img src="'.IMG.'cargos/'.$r['cargo'].'.gif" border="0" /> ' . crear_link($r['nick_autor']) . '</td><td align="right" valign="top" nowrap="nowrap"><acronym title="' . $r['expire'] . '">'.timer($r['expire']).'</acronym></td><td align="right" valign="top" nowrap="nowrap">' . duracion($r['tiempo']+1) . '</td><td><b style="font-size:13px;">'.($r['motivo']?'<a href="/control/kick/info/'.$r['ID'].'/">'.$r['razon'].'</a>':$r['razon']).'</b></td><td>'.$expulsar.'</td></tr>' . "\n";
}
echo '</table>';


}
