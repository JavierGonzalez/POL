<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 





//if (!$pol['user_ID']) { redirect('/'); }

$num_element_pag = $pol['config']['info_censo'];

// num ciudadanos activos (los que entraron en las ultimas 24h sin ser nuevos ciudadanos)
$margen_24h = date('Y-m-d H:i:s', time() - 86400);	// 24 h
$result = mysqli_fetch_row(mysql_query_old("SELECT COUNT(ID) FROM users WHERE estado != 'expulsado' AND estado != 'validar' AND fecha_last > '".$margen_24h."' AND fecha_registro < '".$margen_24h."'", $link));
$censo_activos_vp = $result[0];
$result = mysqli_fetch_row(mysql_query_old("SELECT COUNT(ID) FROM users WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND fecha_last > '".$margen_24h."' AND fecha_registro < '".$margen_24h."'", $link));
$censo_activos = $result[0];


// num expulsados
$result = mysqli_fetch_row(mysql_query_old("SELECT COUNT(ID) FROM users WHERE estado = 'expulsado'", $link));
$censo_expulsados = $result[0];

if ((!is_numeric($_GET[2])) AND ($_GET[1] == 'busqueda')) {
    $pagina = $_GET[3];
    $pagina_url = '/info/censo/busqueda/' . $_GET[2] . '/';
} elseif (($_GET[1]) AND (!is_numeric($_GET[1]))) { 
    $pagina = $_GET[2];
    $pagina_url = '/info/censo/' . $_GET[1] . '/';
} else { 
    $pagina = $_GET[1]; 
    $pagina_url = '/info/censo/';
}

if ($_GET[1] == 'turistas') {
    $num_element_pag = $censo_turistas;
}
elseif ($_GET[1] == 'expulsados') {
    $num_element_pag = $censo_expulsados;
}

paginacion('censo', $pagina_url, null, $pagina, $num_element_pag, 150);

if ($_GET[1] == 'nuevos') {
    $old = 'antiguedad';
} else {
    $old = 'nuevos';
}

if ($_GET[1] == 'busqueda') {
    $busqueda = $_GET[2];
} else {
    $busqueda = '';
}

echo '
<div style="float:right;">
<input name="qcmq" size="14" value="'.$busqueda.'" type="text" id="cmq" />
<button onclick="var cmq = $(\'#cmq\').attr(\'value\'); window.location.href=\'/info/censo/busqueda/\'+cmq+\'/\'; return false;" class="small">'._('Buscar ciudadano').'</button>
</div>

<p>'.$p_paginas.'</p>

<p><abbr title="Numero de ciudadanos en la plataforma '.PAIS.'"><b>'.num($pol['config']['info_censo']).'</b> '._('ciudadanos de').' '.PAIS.'</abbr> (<abbr title="Ciudadanos -no nuevos- que entraron en las últimas 24h, en la plataforma '.PAIS.'">'._('activos').' <b>'.$censo_activos.'</b></abbr>,  <abbr title="Ciudadanos activos en todo VirtualPol">'._('activos global').' <b>'.$censo_activos_vp.'</b></abbr>)

'.(ECONOMIA?' | <a href="/control/expulsiones" class="expulsado">'._('Expulsados').'</a>: <b>'.$censo_expulsados.'</b> | <a href="/info/censo/riqueza" title="Los ciudadanos con más monedas">'._('Ricos').'</a>':'').' | <a href="/info/censo/SC" title="Todos los ciudadanos registrados en VirtualPol globalmente">'._('Censo de').' VirtualPol</a> &nbsp; 
</p>

<table border="0" cellspacing="2" cellpadding="0">
<tr>
<th></th>
'.(ASAMBLEA?'':'<th style="font-size:18px;"><a href="/info/censo/nivel">'._('Nivel').'</a></th>').'
<th></th>
<th style="font-size:18px;"><a href="/info/censo/nombre">'._('Nick').'</a></th>
<th style="font-size:18px;" colspan="2"><a href="/info/censo/confianza">'._('Confianza').'</a></th>
'.(ASAMBLEA?'':'<th style="font-size:18px;"><a href="/info/censo/afiliacion">Afil</a></th>').'
<th style="font-size:18px;"><a href="/info/censo/online">Online</a></th>
<th style="font-size:18px;"><a href="/info/censo/'.$old.'">'._('Antigüedad').'</a></th>
<th style="font-size:18px;"><a href="/info/censo">'._('Último').'&nbsp;'._('acceso').'&darr;</a></th>
<th style="font-size:18px;"><a href="/info/censo/perfiles">'._('Perfiles').'</a></th>
</tr>';

switch ($_GET[1]) {
    case 'busqueda': 
        $where = 'WHERE (text LIKE \'%'.$_GET[2].'%\' OR nombre LIKE \'%'.$_GET[2].'%\' OR nick LIKE \'%'.$_GET[2].'%\' OR datos LIKE \'%'.$_GET[2].'%\') ';
        $order_by = 'ORDER BY fecha_last DESC'; 
    break;
    case 'nivel': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' AND ID != \'1\'';
        $order_by = ' ORDER BY nivel DESC'; 
    break;
    case 'nombre': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\'';
        $order_by = ' ORDER BY nick ASC'; 
    break;
    case 'nuevos': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\'';
        $order_by = ' ORDER BY fecha_registro DESC'; 
    break;
    case 'antiguedad': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\'';
        $order_by = ' ORDER BY fecha_registro ASC'; 
    break;
    case 'elec': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\'';
        $order_by = ' ORDER BY num_elec DESC, fecha_registro ASC'; 
    break;
    case 'online': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\'';
        $order_by = ' ORDER BY online DESC'; 
    break;
    case 'riqueza': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\'';
        $order_by = ' ORDER BY pols DESC, fecha_registro ASC'; 
    break;
    case 'afiliacion': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\'';
        $order_by = ' ORDER BY partido_afiliado DESC, fecha_registro ASC'; 
    break;
    case 'confianza': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\'';
        $order_by = ' ORDER BY voto_confianza DESC, fecha_registro ASC'; 
    break;
    case 'expulsados': 
        $where = 'WHERE estado = \'expulsado\'';
        $order_by = ' ORDER BY fecha_last DESC'; $num_element_pag = $censo_expulsados; 
    break;
    case 'turistas': 
        $where = 'WHERE estado = \'turista\'';
        $order_by = ' ORDER BY fecha_registro DESC'; $num_element_pag = $censo_turistas; 
    break;
    case 'perfiles': 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\' AND datos != \'\' AND datos != \'][][][][][\'';
        $order_by = ' ORDER BY fecha_registro ASC'; 
    break;
    case 'SC': 
        $where = 'WHERE estado != \'expulsado\' AND estado != \'validar\' ';
        $order_by = " ORDER BY voto_confianza DESC, fecha_registro ASC"; 
    break;

    default: 
        $where = 'WHERE estado = \'ciudadano\' AND pais = \''.PAIS.'\'';
        $order_by = ' ORDER BY fecha_last DESC';
}

if ($p_init) { $orden = $p_init + 1; } else { $orden = 1; }

if ($pol['estado']) { $sql_extra = ", (SELECT voto FROM votos WHERE tipo = 'confianza' AND emisor_ID = '" . $pol['user_ID'] . "' AND item_ID = users.ID LIMIT 1) AS has_votado"; }


$sc = get_supervisores_del_censo();

$result = mysql_query_old("(SELECT ID, ID AS user_ID, nick, nombre, estado, pais, nivel, online, ref, ref_num, num_elec, voto_confianza, fecha_registro, nota, fecha_last, cargo, avatar, datos,
(SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND users.partido_afiliado != '0' AND ID = users.partido_afiliado LIMIT 1) AS siglas".$sql_extra."
FROM users ".$where." AND modo_invisible='false' ".$order_by." LIMIT ".mysqli_real_escape_string($link,$p_limit). " ) union (SELECT ID, ID AS user_ID, nick, nombre, estado, pais, nivel, online, ref, ref_num, num_elec, voto_confianza, fecha_registro, nota, '' as fecha_last, cargo, avatar, datos,
(SELECT siglas FROM partidos WHERE pais = '".PAIS."' AND users.partido_afiliado != '0' AND ID = users.partido_afiliado LIMIT 1) AS siglas".$sql_extra."
FROM users ".$where." AND modo_invisible='true' AND estado != 'validar'  ".$order_by." LIMIT ".mysqli_real_escape_string($link,$p_limit).")"
, $link);
while($r = mysqli_fetch_array($result)){
    if ($r['online'] != 0) { $online = duracion($r['online']); } else { $online = ''; }
    if ($r['avatar'] == 'true') { $avatar = avatar($r['ID'], 40) . ' '; } else { $avatar = ''; }
    if ($r['siglas']) { $partido = '<a href="/partidos/' . strtolower($r['siglas']) . '/">' . $r['siglas'] . '</a>'; } else { $partido = ''; }
    if ($r['ref_num'] == 0) { $r['ref_num'] = ''; }
    if ($r['num_elec'] == 0) { $r['num_elec'] = ''; }
    if (!$r['has_votado']) { $r['has_votado'] = 0; }

    echo '<tr>
<td align="right" class="gris">' . $orden++ . '</td>
'.(ASAMBLEA?'':'<td align="right">' . $r['nivel'] . '</td>').'
<td height="38">' . $avatar . '</td>
<td nowrap="nowrap">'.(isset($sc[$r['ID']])?'<span style="float:right;color:red;margin-left:5px;" title="'._('Supervisor del Censo').'">'._('SC').'</span>':'').'<img src="'.IMG.'cargos/' . $r['cargo'] . '.gif" width="16" height="16" /> <b>' . crear_link($r['nick'], 'nick', $r['estado']) . '</b>'.(isset($r['nombre'])&&nucleo_acceso('ciudadanos')?'<br /><span style="color:grey;font-size:12px;">'.$r['nombre'].'</span>':'').'</td>
<td align="right" nowrap="nowrap"><span id="confianza'.$r['user_ID'].'">'.confianza($r['voto_confianza']).'</span></td>
<td nowrap="nowrap">'.($pol['user_ID']&&$r['user_ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['user_ID'].'" class="votar" type="confianza" name="'.$r['user_ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>
'.(ASAMBLEA?'':'<td>' . $partido . '</td>').'
<td align="right" nowrap="nowrap">' . $online . '</td>
<td>' . explodear(' ', $r['fecha_registro'], 0) . '</td>
<td align="right" nowrap="nowrap" class="timer" value="'.strtotime($r['fecha_last']).'"></td>

<td nowrap="nowrap">';

    $datos = explode('][', $r['datos']);
    foreach ($datos_perfil AS $id => $dato) {
        if ($datos[$id] != '') {
            echo '<a href="'.$datos[$id].'" target="_blank"><img src="'.IMG.'ico/'.$id.'_32.png" width="32" width="32" alt="'.$datos.'" /></a>';
        }
    }

    echo '</td></tr>' . "\n";


}
echo '</table><p>' . $p_paginas . '</p>';

$txt_title = _('Censo de ciudadanos');
$txt_nav = array('/info/censo'=>_('Censo'));
$txt_tab = array('/geolocalizacion'=>_('Mapa de ciudadanos'), '/info/censo/SC'=>_('Censo VirtualPol'));