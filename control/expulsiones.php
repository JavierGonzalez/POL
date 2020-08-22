<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




if ($_GET[1] == 'expulsar') { // /control/expulsiones/expulsar

$txt_title = 'Expulsar: '.$_GET[2];
$txt_nav = array('/control'=>_('Control'), '/control/expulsiones'=>_('Expulsiones'), _('Expulsar'));


if (isset($sc[$pol['user_ID']])) { $disabled = ''; } else { $disabled = ' disabled="disabled"'; }


if (is_numeric(str_replace('-', '', $_GET[2]))) {
    $nicks = array();
    $result = sql_old("SELECT nick FROM users WHERE ID IN ('".implode("','", explode('-', $_GET[2]))."') AND estado != 'expulsado'");
    while ($r = r($result)) { $nicks[] = $r['nick']; }
    $_GET[2] = implode('-', $nicks);
}


echo '

<p>'._('Las expulsiones son efectuadas por los Supervisores del Censo (SC), consiste en un bloqueo definitivo a un usuario y su puesta en proceso de eliminación forzada tras 5 dias, durante este periodo es reversible. Las expulsiones se aplican por incumplimiento las <a href/TOS">Condiciones de Uso</a>').'.</p>

<form action="/accion/expulsar" method="post">

<ol>
<li><b>'._('Nick').':</b> '._('usuarios a expulsar').'.<br />
<input type="text" value="'.str_replace('-', ' ', $_GET[2]).'" name="nick" size="50" maxlength="900" style="font-weight:bold;" required />
<br /><br /></li>

<li>'._('<b>Motivo de expulsión:</b> si son varios elegir el mas claro').'.<br />
<select name="razon">

<optgroup label="Clones">
<option value="Clones: 1.a" selected="selected">1.a Clones:</option>
<option value="Clones: 1.b">1.b Uso de una dirección de email temporal o de uso no habitual.</option>
<option value="Clones: 1.c">1.c Uso de cualquier método cuyo fin sea ocultar la conexión a Internet.</option>
</optgroup>

<optgroup label="Mantenimiento">
<option value="Registro erroneo.">Registro erroneo.</option>
<option value="Test de desarrollo.">Test de desarrollo.</option>
</optgroup>

<optgroup label="Ataque al sistema">
<option value="Ataque al sistema: 2.a">2.a Uso o descubrimiento de bugs del sistema, sea cual fuere su finalidad, sin reportarlo inmediatamente u obrando de mala fe.</option>
<option value="Ataque al sistema: 2.b">2.b Ejecutar cualquier tipo de acción que busque causar un perjuicio al mismo.</option>
<option value="Ataque al sistema: 2.c">2.c La utilización malintencionada del privilegio de Supervisor del Censo.</option>
</optgroup>


<optgroup label="Ataque a la comunidad">
<option value="Ataque a la comunidad: 3.a">3.a Publicación de contenido altamente violento, obsceno o, en todo caso, no apto para menores de edad.</option>
<!--<option value="Ataque a la comunidad: 3.b">3.b Hacer apología del terrorismo o ideologías que defiendan el uso de la violencia.</option>-->
<option value="Ataque a la comunidad: 3.c">3.c Amenazar a otros usuarios con repercusiones fuera de la comunidad.</option>
<option value="Ataque a la comunidad: 3.d">3.d El uso reiterado o sistemático de “kicks” superiores a 15 minutos sin cobertura legal dentro de la comunidad.</option>
</optgroup>


</select><br /><br /></li>


<li><b>Caso <input type="text" name="caso" size="8" maxlength="20" /></b> '._('Solo en caso de clones').'.<br /><br /></li>

<li><b>Pruebas:</b> anotaciones o pruebas sobre la expulsion. Confidencial, solo visible por los SC.<br />
<textarea name="motivo" style="color:green;font-weight:bold;width:500px;height:120px;"></textarea>
<br /><br /></li>


<li>'.boton(_('Expulsar'), ($disabled?false:'submit'), false, 'large red').'</li></ol></form>	
';


} elseif (($_GET[1] == 'info') AND ($_GET[2]) AND (isset($sc[$pol['user_ID']]))) {

    $result = sql_old("SELECT *,
(SELECT nick FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado,
(SELECT estado FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = expulsiones.autor LIMIT 1) AS nick_autor
FROM expulsiones
WHERE ID = '".$_GET[2]."' LIMIT 1");
    while($r = r($result)){
        echo '
<p><b>'.crear_link($r['expulsado'], 'nick', $r['expulsado_estado']).'</b> '._('fue expulsado por').' <b>'.crear_link($r['nick_autor']).'</b>.</p>

<p>'._('Razón').': <b>'.$r['razon'].'</b></p>

<p>'._('Fecha').': '.$r['expire'].'</p>

<p>'._('Pruebas').':</p><p class="azul">'.str_replace("\n","<br />", $r['motivo']).'</p>';
    }
} else {


$txt_title = 'Control: '._('Expulsiones');
$txt_nav = array('/control'=>_('Control'), '/control/expulsiones'=>_('Expulsiones'));

echo '
<p>'._('Las expulsiones son efectuadas por los Supervisores del Censo (SC). Consiste en un bloqueo definitivo a un usuario y su puesta en proceso de eliminación forzada tras 5 dias, durante este periodo es reversible. Las expulsiones se aplican por incumplimiento las <a href/TOS">Condiciones de Uso</a> (con la excepción de Registro erroneo y Test de desarrollo). Los Supervisores del Censo son ciudadanos con más de 1 año de antiguedad y elegidos por democracia directa, mediante el "voto de confianza", actualizado cada Domingo a las 20:00').'.</p>

<table border="0" cellspacing="1" cellpadding="">
<tr>
<th>'._('Expulsado').'</th>
<th>'._('Cuando').'</th>
<th>'._('Por').'</th>
<th>'._('Motivo').'</th>
<th></th>
</tr>';


$result = sql_old("SELECT ID, razon, expire, estado, autor, tiempo, cargo, motivo,
(SELECT nick FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado,
(SELECT pais FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_pais,
(SELECT estado FROM users WHERE ID = expulsiones.user_ID LIMIT 1) AS expulsado_estado,
(SELECT nick FROM users WHERE ID = expulsiones.autor LIMIT 1) AS nick_autor
FROM expulsiones
WHERE estado != 'indultado'
ORDER BY expire DESC");
while($r = r($result)){
    
    if ((isset($sc[$pol['user_ID']])) AND ($r['expulsado_pais']) AND ($r['estado'] == 'expulsado')) { 
        $expulsar = boton(_('Cancelar'), '/accion/expulsar/desexpulsar?ID='.$r['ID'], '&iquest;Seguro que quieres CANCELAR la EXPULSION del usuario: '.$r['tiempo'].'?', 'small red'); 
    } elseif ($r['estado'] == 'cancelado') { $expulsar = '<b style="font-weight:bold;">'._('Cancelado').'</b>'; } else { $expulsar = ''; }

    if (!$r['expulsado_estado']) { $r['expulsado_estado'] = 'expulsado'; }

    echo '
<tr><td valign="top" nowrap="nowrap">'.($r['estado'] == 'expulsado'?'<img src="'.IMG.'varios/expulsar.gif" alt="Expulsado" border="0" /> ':'<img src="'.IMG.'cargos/0.gif" border="0" /> ').'<b>'.crear_link($r['tiempo'], 'nick', $r['expulsado_estado'], $r['expulsado_pais']) . '</b></td>
<td valign="top" align="right" valign="top" nowrap="nowrap"><acronym title="' . $r['expire'] . '">'.timer($r['expire']).'</acronym></td>
<td valign="top">'.crear_link($r['nick_autor']).'</td>
<td valign="top"><b style="font-size:13px;">'.$r['razon'].'</b></td>
<td valign="top" align="center">'.$expulsar.'</td>
<td>'.(isset($sc[$pol['user_ID']])&&$r['motivo']!=''?'<a href="/control/expulsiones/info/'.$r['ID'].'/">#</a>':'').'</td>
</tr>' . "\n";

    }
    echo '</table><p>Indultados de forma excepcional todos las expulsiones anteriores al 1 de Enero del 2012.</p>';
}