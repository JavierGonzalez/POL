<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

// Nuevo panel de Supervisión del Censo

include('inc-login.php');


// Solo SC.
if ((!nucleo_acceso('supervisores_censo')) OR (!isset($pol['user_ID']))) { redirect('http://www.'.DOMAIN); }


$txt_nav['/sc'] = 'Supervisión del Censo';
$txt_tab['/sc'] = 'Principal';
$txt_tab['/sc/all/last'] = 'Nuevos';
$txt_tab['/sc/all'] = 'Actividad';


$IDS = array();
$IDS_bg = array('#87CEEB', '#FA8072', '#F0E68C', '#FF7F50', '#EE82EE', '#C0C0C0', '#7CFC00', '#F08080', '#66CDAA', '#FFEBCD', '#4169E1', '#D8BFD8', '#FFD700', '#FF4500');

switch ($_GET['a']) {


case 'all':
case 'nick':
case 'user_ID':
case 'IP':

	// LIMPIEZA DATOS IRRELEVANTES
	$result = sql("SELECT * FROM users_con WHERE tipo = 'session' ORDER BY user_ID ASC, time DESC");
	while ($r = r($result)) {
		if (($r['user_ID']==$rl['user_ID']) AND ($r['IP']==$rl['IP']) AND ($r['nav_check'] == $rl['nav_check']) AND ($r['dispositivo'] == $rl['dispositivo']) AND ($r['nav_resolucion'] == $rl['nav_resolucion']) AND ($r['nav_bit'] == $rl['nav_bit'])) {
			sql("DELETE FROM users_con WHERE ID = '".$r['ID']."' LIMIT 1");
		}
		$rl = $r;
	}

	$txt_nav[] = 'Ver '.$_GET['a'];
	
	if ($_GET['a'] == 'user_ID') { $sql_where = "user_ID IN ('".implode("','", explode('-', $_GET['b']))."')"; }
	elseif ($_GET['a'] == 'nick') { $sql_where = "nick IN ('".implode("','", explode('-', $_GET['b']))."')"; }
	elseif ($_GET['a'] == 'IP') { $sql_where = "IP = '".$_GET['b']."'"; }
	elseif ($_GET['a'] == 'all') { $sql_where = "'true' = 'true'"; }

	if ($_GET['c'] == 'session') { $sql_where .= " AND tipo = 'session'"; }
	if ($_GET['c'] == 'login') { $sql_where .= " AND tipo = 'login'"; }
	
	if ($_GET['b'] == 'last') { $sql_order = "u.fecha_registro DESC"; }
	else { $sql_order = "time DESC"; }

	$txt .= '<fieldset><legend>Filtros</legend><a href="/sc/'.$_GET['a'].'/m'.$_GET['b'].'/login">Solo login</a></fieldset>

<fieldset>
<table>
<tr>
<th></th>
<th></th>
<th title="Negrita = login / No negrita = sesión">Hace</th>
<th>Dispositivo</th>
<th>ISP / IP</th>
<th>Clave</th>
<th>Navegador</th>
<th>Pantalla</th>
<th></th>
</tr>';
	$result = sql("SELECT user_ID, dispositivo, ISP, uc.host, uc.nav, uc.IP, nav_check, nav_resolucion, nav_bit, nick, estado, pais, pass, nota_SC, tipo, time
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID
WHERE ".$sql_where."
ORDER BY ".$sql_order." LIMIT 100");
	while ($r = r($result)) { 
		$txt .= print_td($r);
	}
	$txt .= '</table></fieldset>';

	break;
	

case 'filtro':
default:
	$txt .= '<fieldset><legend>Coincidencia de IP</legend><table>
<tr>
<th colspan="3"></th>
<th title="Negrita = login / No negrita = sesión">Hace</th>
<th title="Hardware">Dispositivo</th>
<th title="Proveedor de Internet / Dirección IP y host">ISP / IP</th>
<th title="Coincidencia de contraseña">Clave</th>
<th>Navegador</th>
<th>Pantalla</th>
<th></th>
</tr>';

	$clones_array_full = array();
	$result = sql("SELECT COUNT(DISTINCT user_ID) AS num, IP 
FROM users_con
GROUP BY IP
HAVING num > 1
ORDER BY num DESC, IP ASC");
	while ($r = r($result)) { 
		
		$clones_array = array();
		$txt_tr = '';
		$clon_count = 0;
		$mostrar = false;
		$result2 = sql("SELECT user_ID, dispositivo, ISP, uc.host, uc.nav, uc.IP, nav_check, nav_resolucion, nav_bit, nick, estado, pais, pass, nota_SC, tipo, MAX(time) AS time
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID
WHERE uc.IP = '".$r['IP']."'
GROUP BY user_ID
ORDER BY dispositivo DESC, nav_bit DESC, MAX(time) DESC");
		while ($r2 = r($result2)) {
			$txt_tr .= print_td($r2, ++$clon_count);
			if ((!in_array($r2['user_ID'], $clones_array_full)) AND ($r2['estado'] != 'expulsado')) { $mostrar = true; $clones_array_full[] = $r2['user_ID']; }
			$clones_array[] = $r2['user_ID'];
		}
		$IDS = array();

		if ($mostrar) {
			$txt .= '<tr class="tdhead">
<td colspan="3" nowrap><a href="/sc/user_ID/'.implode('-', $clones_array).'" class="button blue small">Ver</a> <a href="/control/expulsiones/expulsar/'.implode('-', $clones_array).'" class="button red small">Expulsar '.$clon_count.'</a></td>
<td colspan="9"></td>
</tr>'.$txt_tr;
		}
	}
	$txt .= '</table></fieldset>';



	$txt .= '<fieldset><legend>Coincidencia de Dispositivo (traza)</legend><table>';
	$clones_array_full = array();
	$result = sql("SELECT COUNT(DISTINCT user_ID) AS num, dispositivo
FROM users_con
WHERE dispositivo IS NOT NULL AND dispositivo != ''
GROUP BY dispositivo
HAVING num > 1
ORDER BY num DESC, time DESC");
	while ($r = r($result)) { 
		
		$clones_array = array();
		$txt_tr = '';
		$clon_count = 0;
		$mostrar = false;
		$result2 = sql("SELECT user_ID, dispositivo, ISP, uc.host, uc.nav, uc.IP, nav_check, nav_resolucion, nav_bit, nick, estado, pais, pass, nota_SC, tipo, MAX(time) AS time
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID
WHERE dispositivo = '".$r['dispositivo']."'
GROUP BY user_ID
ORDER BY MAX(time) DESC");
		while ($r2 = r($result2)) {
			$txt_tr .= print_td($r2, ++$clon_count);
			if ((!in_array($r2['user_ID'], $clones_array_full)) AND ($r2['estado'] != 'expulsado')) { $mostrar = true; $clones_array_full[] = $r2['user_ID']; }
			$clones_array[] = $r2['user_ID'];
		}
		$IDS = array();

		if ($mostrar) {
			$txt .= '<tr class="tdhead">
<td colspan="3" nowrap><a href="/sc/user_ID/'.implode('-', $clones_array).'" class="button blue small">Ver</a> <a href="/control/expulsiones/expulsar/'.implode('-', $clones_array).'" class="button red small">Expulsar '.$clon_count.'</a></td>
<td colspan="9"></td>
</tr>'.$txt_tr;
		}
	}
	$txt .= '</table></fieldset>';







	$txt .= '<fieldset><legend>Ocultación de conexión</legend><table>';

	$clones_array = array();
	$txt_tr = '';
	$clon_count = 0;
	$result2 = sql("SELECT user_ID, dispositivo, ISP, uc.host, uc.nav, nav_check, uc.IP, nav_resolucion, nav_bit, nick, estado, pais, pass, nota_SC, tipo, MAX(time) AS time
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID
WHERE ISP LIKE 'Ocultado%'
GROUP BY user_ID
ORDER BY MAX(time) DESC");
	while ($r2 = r($result2)) {
		$txt_tr .= print_td($r2, ++$clon_count);
		$clones_array[] = $r2['user_ID'];
	}
	$IDS = array();

	$txt .= $txt_tr;
	$txt .= '</table></fieldset>';






	$txt .= '<fieldset><legend>Coincidencia de clave</legend><table>';

	$clones_array_full = array();
	$result = sql("SELECT COUNT(*) AS num, pass 
FROM users
GROUP BY pass
HAVING num > 1
ORDER BY num DESC");
	while ($r = r($result)) { 
		
		$clones_array = array();
		$txt_tr = '';
		$clon_count = 0;
		$mostrar = false;
		$result2 = sql("SELECT user_ID, dispositivo, ISP, uc.host, uc.nav, uc.IP, nav_check, nav_resolucion, nav_bit, nick, estado, pais, pass, nota_SC, tipo, MAX(time) AS time
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID
WHERE u.pass = '".$r['pass']."'
GROUP BY user_ID
ORDER BY dispositivo DESC, nav_bit DESC, MAX(time) DESC");
		while ($r2 = r($result2)) {
			$txt_tr .= print_td($r2, ++$clon_count);
			if ((!in_array($r2['user_ID'], $clones_array_full)) AND ($r2['estado'] != 'expulsado')) { $mostrar = true; $clones_array_full[] = $r2['user_ID']; }
			$clones_array[] = $r2['user_ID'];
		}
		$IDS = array();

		if ($mostrar) {
			$txt .= '<tr class="tdhead">
<td colspan="3" nowrap><a href="/sc/user_ID/'.implode('-', $clones_array).'" class="button blue small">Ver</a> <a href="/control/expulsiones/expulsar/'.implode('-', $clones_array).'" class="button red small">Expulsar '.$clon_count.'</a></td>
<td colspan="9"></td>
</tr>'.$txt_tr;
		}
	}
	$txt .= '</table></fieldset>';




	break;
}

// LEYENDA
foreach ($vp['paises'] AS $pais) { $paises .= ' <span style="background:'.$vp['bg'][$pais].';" class="redondeado">'.$pais.'</span>'; }
$txt .= '<fieldset><legend>Leyenda</legend>
<p class="rich">Supervisores del Censo: @'.implode(' @', get_supervisores_del_censo()).'</p>
<p>Plataformas:'.$paises.'</p>
<p>Estados de usuario: <b class="ciudadano">'._('Ciudadano').'</b> <b class="turista">'._('Turista').'</b> <b class="validar">'._('Validar').'</b> <b class="expulsado">'._('Expulsado').'</b></p>
</fieldset>';


$txt .= '<style>
.tdhead td { padding-top:20px; }
.peque { font-size:10px; }
a.button { margin:-3px 0; }
.ids_bg { padding:5px 7px; margin:-8px -2px; }
#content { background:#ffe7e1; margin-top:0; padding-top:4px; }
</style>';

function unico($ID, $es='', $print=false) {
	global $IDS, $IDS_bg;
	if ($ID != '') {
		if (!isset($IDS[$es][$ID])) { $IDS[$es][$ID] = count($IDS[$es]); }
		return '<span style="background:'.$IDS_bg[$IDS[$es][$ID]].';" class="ids_bg">'.$es.' '.($print==true?$ID:'<b>'.($IDS[$es][$ID]+1).'</b>').'</span>';
	} else { return ''; }
}

function print_td($r2, $count=false) {
	return '<tr>
<td><a href="/control/expulsiones/expulsar/'.$r2['nick'].'" class="button red small">X</a></td>
'.($count!=false?'<td align="right"><b>'.$count.'.</b></td>':'').'
<td width="150"><b style="font-size:18px;">'.crear_link($r2['nick'], 'nick', $r2['estado'], $r2['pais']).'</b></td>
<td width="70" align="right"'.($r2['tipo']=='login'?' style="font-weight:bold;"':'').' nowrap>'.timer($r2['time']).'</td>
<td nowrap width="96">'.unico($r2['dispositivo'], 'Dispositivo').'</td>
<td nowrap align="right" title="'.$r2['host'].'">'.unico($r2['ISP'], '', true).' '.unico($r2['IP'], 'IP').'</td>
<td nowrap>'.unico($r2['pass'], 'Clave').'</td>
<td nowrap title="'.$r2['nav'].'">'.unico($r2['nav_check'], 'Navegador').'</td>
<td nowrap align="right">'.($r2['nav_bit']>0?unico($r2['nav_resolucion'].' '.$r2['nav_bit'].'bit', '', true):'').'</td>
<td nowrap>'.print_nota_SC($r2['nota_SC'], $r2['user_ID']).'</td>
</tr>';
}

function print_nota_SC($nota_SC, $user_ID) {
	global $pol;
	return ($nota_SC!=''?'<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=SC&b=nota&ID='.$user_ID.'" method="post"><input type="text" name="nota_SC" size="20" maxlength="255" value="'.$nota_SC.'"'.(substr($nota_SC, 0, 7)=='Cuidado'?' style="color:red;"':'').(substr($nota_SC, 0, 12)=='Comparte con'?' style="color:green;"':'').(substr($nota_SC, 0, 3)=='OK '?' style="color:blue;"':'').' /> '.boton('OK', 'submit', false, 'small pill').'</form>':'');
}

//THEME
$txt_title = 'Supervisión del Censo - CONFIDENCIAL';
$txt_menu = 'demo';
include('theme.php');
?>
