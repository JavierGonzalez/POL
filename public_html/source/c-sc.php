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

// Proteccion. Zona privada para SC.
if ((!nucleo_acceso('supervisores_censo')) OR (!isset($pol['user_ID']))) { redirect('http://www.'.DOMAIN); }


$txt_nav['/sc'] = 'Supervisión del Censo';
$txt_tab['/sc'] = 'Coincidencias';
$txt_tab['/sc/filtro/nuevos'] = 'Nuevos';
$txt_tab['/sc/filtro/actividad'] = 'Activos';
$txt_tab['/control/expulsiones'] = 'Expulsiones';
$txt_tab['/control/supervisor-censo'] = 'Principal (antiguo)';
$txt_tab['/control/supervisor-censo/factores-secundarios'] = 'Extra (antiguo)';





$IDS = array();
$IDS_bg = array('#87CEEB', '#FA8072', '#F0E68C', '#FF7F50', '#EE82EE', '#C0C0C0', '#7CFC00', '#F08080', '#66CDAA', '#FFEBCD', '#4169E1', '#D8BFD8', '#FFD700', '#FF4500');

$pass_simple = array(
'5f4dcc3b5aa765d61d8327deb882cf99', // password
'827ccb0eea8a706c4c34a16891f84e7b', // 12345
'e10adc3949ba59abbe56e057f20f883e', // 123456
'fcea920f7412b5da7be0cf42b8c93759', // 1234567
'25d55ad283aa400af464c76d713c07ad', // 12345678
'25f9e794323b453885f5181f1b624d0b', // 123456789
'e807f1fcf82d132f9bb018ca6738a19f', // 1234567890
'01cfcd4f6b8770febfb40cb906715822', // 54321
'c33367701511b4f6020ec61ded352059', // 654321
'f0898af949a373e72a4f6a34b4de9090', // 7654321
'5e8667a439c68f5145dd2fcbecf02209', // 87654321
'6ebe76c9fb411be97b3b0d48b791a7c9', // 987654321
'4297f44b13955235245b2497399d7a93', // 123123
'ed2b1f468c5f915f3f1cf75d7068baae', // 12341234
'b0baee9d279d34fa1dfd71aadb908c3f', // 11111
'96e79218965eb72c92a549dd5a330112', // 111111
'7fa8282ad93047a4d6fe6111c93b308a', // 1111111
'ab56b4d92b40713acc5af89985d4b786', // abcde
'e80b5017098950fc58aad83c8c14978e', // abcdef
'7ac66c0f148de9519b8bd264312c4d64', // abcdefg
'e8dc4081b13434b45189a720b77b6818', // abcdefgh
'a906449d5769fa7361d7ecc6aa3f6d28', // 123abc
'9c98df872d24244696c393a1d26ab749', // 123abcd
'ef73781effc5774100f87fe2f437a435', // 1234abcd
'64ad3fb166ddb41a2ca24f1803b8b722', // 1234abc
'e99a18c428cb38d5f260853678922e03', // abc123
'a141c47927929bc2d1fb6d336a256df4', // abc1234
'd6b0ab7f1c8ab8f514db9a6d85de160a', // abc12345
'd8578edf8458ce06fbc5bb76a58c5ca4', // qwerty
'f25a2fc72690b780b2a14e140ef6a9e0', // iloveyou
'784c48992e9fd4adc2744ffc8fbad900', // tequiero
'eb0a191797624dd3a48fa681d3061212', // master
'bed128365216c019988915ed3add75fb', // passw0rd
'76419c58730d9f35de7ac538c2fd6737', // qazwsx
'4a8ae7cc60c5e496edc3c71c3f0e4376', // virtualpol
'dd957749589af6eda64794777e9ebca0', // virtualpol.com
'726c8c2b65516842fd8722ecc50aad53', // democracia
);

if (false) {
	// Script para actualizar/generar los hash de contraseñas "simples"
	$pass_simple_gen = 'password
12345
123456
1234567
12345678
123456789
1234567890
54321
654321
7654321
87654321
987654321
123123
12341234
11111
111111
1111111
abcde
abcdef
abcdefg
abcdefgh
123abc
123abcd
1234abcd
1234abc
abc123
abc1234
abc12345
qwerty
iloveyou
tequiero
master
passw0rd
qazwsx
virtualpol
virtualpol.com
democracia';

	foreach (explode("\n", $pass_simple_gen) AS $tpass) {
		if ($tpass != '') {
			$tpass = trim($tpass);
			$txt .= '\''.md5($tpass).'\', // '.$tpass.'<br />';
		}
	}
}

switch ($_GET['a']) {

case 'filtro':

	// LIMPIEZA DATOS IRRELEVANTES
	$result = sql("SELECT * FROM users_con ORDER BY user_ID ASC, time DESC");
	while ($r = r($result)) {
		if (($r['user_ID'] == $rl['user_ID']) AND ($r['dispositivo'] == $rl['dispositivo']) AND ($r['IP'] == $rl['IP']) AND ($r['nav'] == $rl['nav'])) { sql("DELETE FROM users_con WHERE ID = '".$r['ID']."' LIMIT 1"); } $rl = $r;
	}

	$txt_nav['/sc/filtro'] = 'Filtro';
	$txt_nav[] = $_GET['b'];
	
	$sql_order = "time DESC";
	switch ($_GET['b']) {
		case 'IP': $sql_where = "IP = '".$_GET['c']."'"; break;
		case 'nick': $sql_where = "nick IN ('".implode("','", explode('-', $_GET['c']))."')"; break;
		case 'user_ID': $sql_where = "user_ID IN ('".implode("','", explode('-', $_GET['c']))."')"; break;
		case 'nuevos': $sql_where = "'true' = 'true' GROUP BY user_ID"; $sql_order = "u.fecha_registro DESC"; break;
		case 'actividad':
		default: $sql_select = ", MAX(time) AS time"; $sql_where = "'true' = 'true' GROUP BY user_ID";
	}

	$txt .= '<!--<fieldset><legend>Filtros</legend><a href="/sc/'.$_GET['a'].'/m'.$_GET['b'].'/login">Solo login</a></fieldset>-->
<fieldset>
<table>
<tr>
<th></th>
<th></th>
<th title="Negrita = login / No negrita = sesión">Hace</th>
<th>Dispositivo</th>
<th>ISP / IP</th>
<th>Email</th>
<th>Clave</th>
<th>Navegador</th>
<th>Pantalla</th>
<th></th>
</tr>';
	$result = sql("SELECT user_ID, dispositivo, ISP, uc.host, uc.nav, nav_so, uc.IP, nav_resolucion, nick, estado, pais, pass, nota_SC, email, tipo, time".$sql_select."
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID 
WHERE ".$sql_where."
ORDER BY ".$sql_order." LIMIT 100");
	while ($r = r($result)) { $txt .= print_td($r); }
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
<th title="Proveedor de email">Email</th>
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
		$result2 = sql("SELECT user_ID, MAX(dispositivo) AS dispositivo, MAX(nav_resolucion) AS nav_resolucion, MAX(time) AS time, ISP, nick, estado, pais, pass, nota_SC, email, tipo, uc.host, uc.nav, nav_so, uc.IP
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID
WHERE uc.IP = '".$r['IP']."'
GROUP BY user_ID
ORDER BY time DESC");
		while ($r2 = r($result2)) {
			$txt_tr .= print_td($r2, ++$clon_count);
			if ((!in_array($r2['user_ID'], $clones_array_full)) AND ($r2['estado'] != 'expulsado')) { $mostrar = true; $clones_array_full[] = $r2['user_ID']; }
			$clones_array[] = $r2['user_ID'];
			if ($r2['estado'] == 'expulsado') {
				$razon = false;
				$result3 = sql("SELECT razon FROM expulsiones WHERE user_ID = '".$r2['user_ID']."' AND estado = 'expulsado' LIMIT 1");
				while ($r3 = r($result3)) { $razon = $r3['razon']; }
				if (($razon == false) OR ($razon == 'Registro erroneo.')) { $clon_count--; }
			}
		}
		$IDS = array();
		if (($mostrar) AND ($clon_count > 1)) {
			$txt .= '<tr class="tdhead">
<td colspan="3" nowrap><a href="/sc/filtro/user_ID/'.implode('-', $clones_array).'" class="button blue small">&nbsp;</a> <a href="/control/expulsiones/expulsar/'.implode('-', $clones_array).'" class="button red small">Expulsar '.$clon_count.'</a></td>
<td colspan="10"></td>
</tr>'.$txt_tr;
		}
	}
	$txt .= '</table></fieldset>';



	$txt .= '<fieldset><legend>Coincidencia de Dispositivo</legend><table>';
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
		$result2 = sql("SELECT user_ID, MAX(dispositivo) AS dispositivo, MAX(nav_resolucion) AS nav_resolucion, MAX(time) AS time, ISP, nick, estado, pais, pass, nota_SC, email, tipo, uc.host, uc.nav, nav_so, uc.IP
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID
WHERE dispositivo = '".$r['dispositivo']."'
GROUP BY user_ID
ORDER BY MAX(time) DESC");
		while ($r2 = r($result2)) {
			$txt_tr .= print_td($r2, ++$clon_count);
			$clones_array[] = $r2['user_ID'];
			if ((!in_array($r2['user_ID'], $clones_array_full)) AND ($r2['estado'] != 'expulsado')) { $mostrar = true; $clones_array_full[] = $r2['user_ID']; }
			
			if ($r2['estado'] == 'expulsado') {
				$razon = false;
				$result3 = sql("SELECT razon FROM expulsiones WHERE user_ID = '".$r2['user_ID']."' AND estado = 'expulsado' LIMIT 1");
				while ($r3 = r($result3)) { $razon = $r3['razon']; }
				if (($razon == false) OR ($razon == 'Registro erroneo.')) { $clon_count--; }
			}
		}
		$IDS = array();
		if (($mostrar) AND ($clon_count > 1)) {
			$txt .= '<tr class="tdhead">
<td colspan="3" nowrap><a href="/sc/filtro/user_ID/'.implode('-', $clones_array).'" class="button blue small">&nbsp;</a> <a href="/control/expulsiones/expulsar/'.implode('-', $clones_array).'" class="button red small">Expulsar '.$clon_count.'</a></td>
<td colspan="10"></td>
</tr>'.$txt_tr;
		}
	}
	$txt .= '</table></fieldset>';




	$txt .= '<fieldset><legend>Ocultación de conexión</legend><table>';
	$clones_array = array();
	$txt_tr = '';
	$clon_count = 0;
	$result2 = sql("SELECT user_ID, MAX(dispositivo) AS dispositivo, MAX(nav_resolucion) AS nav_resolucion, MAX(time) AS time, ISP, nick, estado, pais, pass, nota_SC, email, tipo, uc.host, uc.nav, nav_so, uc.IP
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID
WHERE ISP LIKE 'Ocultado%' AND estado != 'expulsado'
GROUP BY user_ID
ORDER BY MAX(time) DESC, time DESC");
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
ORDER BY num DESC, fecha_last DESC");
	while ($r = r($result)) { 
	if (!in_array($r['pass'], $pass_simple)) {
		
		$clones_array = array();
		$txt_tr = '';
		$clon_count = 0;
		$mostrar = false;
		$result2 = sql("SELECT user_ID, MAX(dispositivo) AS dispositivo, MAX(nav_resolucion) AS nav_resolucion, MAX(time) AS time, ISP, nick, estado, pais, pass, nota_SC, email, tipo, uc.host, uc.nav, nav_so, uc.IP
FROM users_con `uc`
LEFT OUTER JOIN users `u`
ON uc.user_ID = u.ID
WHERE u.pass = '".$r['pass']."'
GROUP BY user_ID
ORDER BY dispositivo DESC, MAX(time) DESC");
		while ($r2 = r($result2)) {
			$txt_tr .= print_td($r2, ++$clon_count);
			if ((!in_array($r2['user_ID'], $clones_array_full)) AND ($r2['estado'] != 'expulsado')) { $mostrar = true; $clones_array_full[] = $r2['user_ID']; }
			$clones_array[] = $r2['user_ID'];
			if ($r2['estado'] == 'expulsado') {
				$razon = false;
				$result3 = sql("SELECT razon FROM expulsiones WHERE user_ID = '".$r2['user_ID']."' AND estado = 'expulsado' LIMIT 1");
				while ($r3 = r($result3)) { $razon = $r3['razon']; }
				if (($razon == false) OR ($razon == 'Registro erroneo.')) { $clon_count--; }
			}
		}
		$IDS = array();
		if (($mostrar) AND ($clon_count > 1)) {
			$txt .= '<tr class="tdhead">
<td colspan="3" nowrap><a href="/sc/filtro/user_ID/'.implode('-', $clones_array).'" class="button blue small">&nbsp;</a> <a href="/control/expulsiones/expulsar/'.implode('-', $clones_array).'" class="button red small">Expulsar '.$clon_count.'</a></td>
<td colspan="10"></td>
</tr>'.$txt_tr;
		}
	} }
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


$txt .= '
<style>
.tdhead td { padding-top:20px; }
.peque { font-size:10px; }
a.button { margin:-3px 0; }
.idu { font-size:12px; padding:5px 5px; margin:-9px -4px; white-space:nowrap; }
#content { background:#ffe7e1; margin-top:0; padding-top:4px; }
</style>';

function unico($ID, $es='', $print=false) {
	global $IDS, $IDS_bg;
	if ($ID != '') {
		if (!isset($IDS[$es][$ID])) { $IDS[$es][$ID] = count($IDS[$es]); }
		return '<span class="idu" style="background:'.$IDS_bg[$IDS[$es][$ID]].';">'.($print===false?$es:($print===true?$ID:$print)).($print!==true?' <b>'.($IDS[$es][$ID]+1).'</b>':'').'</span>';
	} else { return ''; }
}

function print_td($r, $count=false) {
	global $pass_simple;
	return '<tr>
<td nowrap><a href="/sc/filtro/user_ID/'.$r['user_ID'].'" class="button blue small">&nbsp;</a> <a href="/control/expulsiones/expulsar/'.$r['nick'].'" class="button red small">&nbsp;</a></td>
'.($count!==false?'<td align="right"><b>'.$count.'.</b></td>':'').'
<td width="130"><b style="font-size:16px;">'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</b></td>
<td width="70" align="right"'.($r['tipo']=='login'?' style="font-weight:bold;"':'').' nowrap>'.timer($r['time']).'</td>
<td nowrap width="80">'.unico($r['dispositivo'], 'Dispositivo').'</td>
<td nowrap align="right" title="'.$r['host'].'">'.unico($r['ISP'], 'ISP', true).' '.unico($r['IP'], 'IP').'</td>
<td nowrap title="'.$r['email'].'">'.unico('@'.explodear('@', $r['email'], 1), 'email', true).'</td>
<td nowrap>'.unico($r['pass'], 'Clave', (in_array($r['pass'], $pass_simple)?'Clave simple':'Clave')).'</td>
<td nowrap title="'.$r['nav'].'">'.unico($r['nav'], 'nav', $r['nav_so']).'</td>
<td nowrap align="right">'.($r['nav_resolucion']?unico($r['nav_resolucion'].'bit', 'res', true):'').'</td>
<td nowrap>'.print_nota_SC($r['nota_SC'], $r['user_ID']).'</td>
</tr>';
}

function print_nota_SC($nota_SC, $user_ID) {
	global $pol;
	return ($nota_SC!=''?'<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=SC&b=nota&ID='.$user_ID.'" method="post"><input type="text" name="nota_SC" size="15" maxlength="255" value="'.$nota_SC.'" style="margin:-4px 0;'.(substr($nota_SC, 0, 7)=='Cuidado'?'color:red;':'').(substr($nota_SC, 0, 12)=='Comparte con'?'color:green;':'').(substr($nota_SC, 0, 3)=='OK '?'color:blue;':'').'" /> '.boton('OK', 'submit', false, 'small pill').'</form>':'');
}

//THEME
$txt_title = 'Supervisión del Censo - CONFIDENCIAL';
$txt_menu = 'demo';
include('theme.php');
?>
