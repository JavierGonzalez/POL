<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



$txt_nav[] = 'Supervisión del Censo';
$txt_title = 'Supervisión del censo';



echo '
<div class="col_5">
<fieldset><legend>Supervisores del censo</legend><table width="100%">
<tr>
<th colspan="2" style="font-weight:normal;">Confianza</th>
<th></th>
<th align="right" style="font-weight:normal;" nowrap>Antigüedad</th>
</tr>';


$result = sql_old("SELECT ID AS user_ID, nick, pais, voto_confianza, fecha_last, fecha_registro, (SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND item_ID = users.ID LIMIT 1) AS has_votado FROM users WHERE SC = 'true' ORDER BY voto_confianza DESC");
while($r = r($result)) {
echo '<tr>
<td align="right" nowrap="nowrap"><span id="confianza'.$r['user_ID'].'">'.confianza($r['voto_confianza']).'</span></td>
<td nowrap="nowrap">'.($pol['user_ID']&&$r['user_ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['user_ID'].'" class="votar" type="confianza" name="'.$r['user_ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>
<td nowrap><span class="gris" style="float:right">'.timer($r['fecha_last']).'</span><b>'.crear_link($r['nick']).'</b></td>
<td align="right" nowrap>'.timer($r['fecha_registro']).'</td>
</tr>';
}

echo '</table>
<p class="gris">* Asignados automáticamente cada Domingo a las 20:00.<br />
* El balance de votos de confianza se actualiza cada 24h.</p>
</fieldset>


<fieldset><legend>Candidatos a supervisor del censo</legend><table width="100%">';


$result = sql_old("SELECT ID AS user_ID, nick, pais, voto_confianza, fecha_last, fecha_registro, (SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."' AND item_ID = users.ID LIMIT 1) AS has_votado FROM users WHERE SC = 'false' AND ser_SC = 'true' AND fecha_registro < '".tiempo(365)."' AND voto_confianza > 0 ORDER BY voto_confianza DESC LIMIT 10");
while($r = r($result)) {
echo '<tr>
<td align="right" nowrap="nowrap"><span id="confianza'.$r['user_ID'].'">'.confianza($r['voto_confianza']).'</span></td>
<td nowrap="nowrap">'.($pol['user_ID']&&$r['user_ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['user_ID'].'" class="votar" type="confianza" name="'.$r['user_ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>
<td nowrap><span class="gris" style="float:right">'.timer($r['fecha_last']).'</span>'.crear_link($r['nick']).'</td>
<td align="right" nowrap>'.timer($r['fecha_registro']).'</td>
</tr>';
}

echo '</table>
<p class="gris"><em>Requisitos para ser candidato:</em><br />
1. Antiguedad de al menos un año.<br />
2. Postularse como candidato voluntario (<a href="/registrar/login/panel">aquí</a>).
</p>
</fieldset>
</div>

<div class="col_7">

<fieldset><legend>Información</legend>
<p>VirtualPol tiene -por necesidad- un avanzado sistema de supervisión del censo. Las <a href/TOS">Condiciones de Uso</a> (TOS) regulan lo estrictamente esencial, por ejemplo la creación de más de un usuario por persona.</p>
<p>Los encargados de aplicar el TOS -con ayuda de un avanzado sistema de detección- son los supervisores del censo. Son los '.SC_NUM.' ciudadanos de VirtualPol con más votos de confianza y al menos un año de antiguedad, elegidos semanalmente por democracia directa de forma automática.</p>
<p>La función de esta página es aportar la máxima transparencia posible sobre esta importante labor.</p>
<p class="gris">* <a href/reglamento-sc">Reglamento de Supervisión del Censo</a></p>
</fieldset>


<fieldset><legend>Últimas expulsiones</legend>

<table width="100%">
<tr>
<th></th>
<th style="font-weight:normal;">'._('Motivo').'</th>
<th style="font-weight:normal;">'._('Hace').'</th>
</tr>';


$result = sql_old("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado,
(SELECT pais FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_pais,
(SELECT estado FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_estado
FROM expulsiones
WHERE estado != 'indultado'
ORDER BY expire DESC LIMIT 15");
while($r = r($result)){
    
    if ((isset($sc[$pol['user_ID']])) AND ($r['expulsado_pais']) AND ($r['estado'] == 'expulsado')) { 
        $expulsar = boton(_('Cancelar'), '/accion/expulsar/desexpulsar?ID='.$r['ID'], '&iquest;Seguro que quieres CANCELAR la EXPULSION del usuario: '.$r['tiempo'].'?', 'small red'); 
    } elseif ($r['estado'] == 'cancelado') { $expulsar = '<b style="font-weight:bold;">'._('Cancelado').'</b>'; } else { $expulsar = ''; }

    if (!$r['expulsado_estado']) { $r['expulsado_estado'] = 'expulsado'; }

    echo '
<tr><td valign="top" nowrap>'.crear_link($r['tiempo'], 'nick', $r['expulsado_estado'], $r['expulsado_pais']).'</td>
<td valign="top">'.$r['razon'].'</td>
<td valign="top" align="right" valign="top" nowrap="nowrap" class="gris" title="'.$r['expire'].'">'.timer($r['expire']).'</td>
</tr>'."\n";

    }
    echo '</table>
<p class="gris">* Puedes comprobar en qué consiste cada infracción en las <a href/TOS">Condiciones de Uso</a>.</p>
<p><a href="/control/expulsiones">Ver lista completa</a></p>
</fieldset>
</div>


<div style="height:920px;"></div>';

