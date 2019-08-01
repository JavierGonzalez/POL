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
if ((!nucleo_acceso('supervisores_censo') OR !isset($pol['user_ID'])) AND $pol['user_ID']!=1) { redirect('http://www.'.DOMAIN); }

// Obtiene colores de background de paises
$result = sql("SELECT valor, pais FROM config WHERE dato = 'bg_color'");
while ($r = r($result)) { $vp['bg'][$r['pais']] = $r['valor']; }

$txt_nav['/sc'] = 'Supervisión del Censo';

$txt_tab['/sc'] = 'Principal';
$txt_tab['/sc/bloqueos'] = 'Backlist';
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
'3d2172418ce305c7d16d4b05597c6a59', // 22222
'e3ceb5881a0a1fdaad01296d7554868d', // 222222
'79d886010186eb60e3611cd4a5d0bcae', // 2222222
'b7bc2a2f5bb6d521e64c8974c143e9a0', // 33333
'1a100d2c0dab19c4430e7d73762b3423', // 333333
'074fd28eff0f5adea071694061739e55', // 3333333
'79b7cdcd14db14e9cb498f1793817d69', // 44444
'73882ab1fa529d7273da0db6b49cc4f3', // 444444
'dcb64c94e1b81cd1cd3eb4a73ad27d99', // 4444444
'c5fe25896e49ddfe996db7508cf00534', // 55555
'5b1b68a9abf4d2cd155c81a9225fd158', // 555555
'992e63080ee1e47b99f42b8d64ede953', // 5555555
'ae8b5aa26a3ae31612eec1d1f6ffbce9', // 66666
'f379eaf3c831b04de153469d1bec345e', // 666666
'd5ee2eedfcf7adc285db4967bd86910d', // 6666666
'22a4d9b04fe95c9893b41e2fde83a427', // 77777
'f63f4fbc9f8c85d409f2f59f2b9e12d5', // 777777
'dc0fa7df3d07904a09288bd2d2bb5f40', // 7777777
'1c395a8dce135849bd73c6dba3b54809', // 88888
'21218cca77804d2ba1922c33e0151105', // 888888
'388ec3e3fa4983032b4f3e7d8fcb65ad', // 8888888
'd3eb9a9233e52948740d7eb8c3062d14', // 99999
'52c69e3a57331081823331c4e69d3f2e', // 999999
'283f42764da6dba2522412916b031080', // 9999999
'dcddb75469b4b4875094e14561e573d8', // 00000
'670b14728ad9902aecba32e22fa4f6bd', // 000000
'29c3eea3f305d6b823f562ac4be35217', // 0000000
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
22222
222222
2222222
33333
333333
3333333
44444
444444
4444444
55555
555555
5555555
66666
666666
6666666
77777
777777
7777777
88888
888888
8888888
99999
999999
9999999
00000
000000
0000000
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


$filtro_sel[$_SERVER["REQUEST_URI"]] = ' selected="selected"';

if ($_GET['a'] != 'bloqueos') {
	$txt .= '<fieldset><legend>Filtro</legend>

<select onchange="filtro_change(this)" >
<option'.$filtro_sel['/sc'].' value="/sc">Coincidencias (principal)</option>
<optgroup label="Filtros">
<option'.$filtro_sel['/sc/filtro/nuevos'].' value="/sc/filtro/nuevos">Nuevos ciudadanos</option>
<option'.$filtro_sel['/sc/filtro/actividad'].' value="/sc/filtro/actividad">Última actividad</option>
</optgroup>
<optgroup label="Filtros extra">
<option'.$filtro_sel['/sc/filtro/expulsados'].' value="/sc/filtro/expulsados">Expulsados</option>
<option'.$filtro_sel['/sc/filtro/SC'].' value="/sc/filtro/SC">Supervisores del Censo</option>
<option'.$filtro_sel['/sc/filtro/confianza'].' value="/sc/filtro/confianza">Top confianza</option>
<option'.$filtro_sel['/sc/filtro/desconfianza'].' value="/sc/filtro/desconfianza">Top desconfianza</option>
<option'.$filtro_sel['/sc/filtro/paises-raros'].' value="/sc/filtro/paises-raros">Paises raros</option>
<option'.$filtro_sel['/sc/filtro/mas-de-un-pais'].' value="/sc/filtro/mas-de-un-pais">Más de un país diferente</option>
<option'.$filtro_sel['/sc/filtro/mas-de-un-ISP'].' value="/sc/filtro/mas-de-un-ISP">Más de un ISPs diferente</option>
<option'.$filtro_sel['/sc/filtro/mas-de-un-nav'].' value="/sc/filtro/mas-de-un-nav">Más de un SO diferente</option>
<option'.$filtro_sel['/sc/filtro/conexion-oculta'].' value="/sc/filtro/conexion-oculta">Ocultación de conexión</option>
</optgroup>
</select> &nbsp; 

'.(is_numeric($_GET['a'])||!$_GET['a']?'
Profundidad: <select onchange="filtro_change(this)">
<option value="/sc/2"'.($_GET['a']==2?' selected="selected"':'').'>2 días</option>
<option value="/sc/5"'.(!$_GET['a']||$_GET['a']==5?' selected="selected"':'').'>5 días</option>
<option value="/sc/15"'.($_GET['a']==15?' selected="selected"':'').'>15 días</option>
<option value="/sc/30"'.($_GET['a']==30?' selected="selected"':'').'>30 días</option>
<option value="/sc/60"'.($_GET['a']==60?' selected="selected"':'').'>60 días</option>
<option value="/sc/10000"'.($_GET['a']==10000?' selected="selected"':'').'>Máximo</option>
</select> &nbsp; 
':'').'

Plataforma: <select onchange="filtro_change(this)" disabled>
<option value="all">Todo VirtualPol</option>';
foreach ($vp['paises'] AS $pais) { $txt .= '<option value="'.$pais.'">'.$pais.'</option>'; }
$txt .= '</select> &nbsp; 

Buscar: <input type="text" value="" disabled="disabled" />


</fieldset>';
}

switch ($_GET['a']) {



case 'bloqueos':


	$txt_title = _('Control').': SC | '._('bloqueos');
	$txt_nav['/sc/bloqueos'] = _('Bloqueos');

	$result = sql("SELECT valor, dato FROM config WHERE PAIS IS NULL");
	while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }

	$backlists = array('backlist_IP'=>400, 'backlist_emails'=>180, 'backlist_nicks'=>120);

	$txt .= '<form action="'.accion_url().'a=bloqueos" method="post">

<p>'._('Listas negras para bloquear masivamente con filtros. Un elemento por linea. Elementos de al menos 5 caracteres (para minimizar el riesgo de filtros masivos). Precaución, hay riesgo de producir bloqueos masivos').'.</p>
<table>
<tr>';

	foreach ($backlists AS $tipo => $width) {
		$txt .= '<td><fieldset><legend>'.ucfirst(str_replace('_', ' ', $tipo)).'</legend>
	<textarea style="width:'.$width.'px;height:400px;white-space:nowrap;" name="'.$tipo.'">'.$pol['config'][$tipo]."\n".'</textarea></fieldset></td>';
	}
	
	$txt .= '
</tr>

<tr>
<td colspan="'.count($backlists).'" align="center">'.boton(_('Guardar'), 'submit', '¿Estás seguro de activar estos BLOQUEOS?\n\nPRECAUCION: RIESGO DE BLOQUEOS MASIVOS INVOLUNTARIOS.', 'large red').'</td>
</tr>
</table>

</form>
';

	break;










case 'filtro':
	
	// LIMPIEZA DATOS IRRELEVANTES
	$result = sql("SELECT * FROM users_con ORDER BY user_ID ASC, time DESC");
	while ($r = r($result)) {
		//if (($r['user_ID'] == $rl['user_ID']) AND ($r['dispositivo'] == $rl['dispositivo']) AND ($r['IP'] == $rl['IP']) AND ($r['nav'] == $rl['nav'])) { sql("DELETE FROM users_con WHERE ID = '".$r['ID']."' LIMIT 1"); } $rl = $r;
	}

	$txt_nav['/sc/filtro'] = 'Filtro';
	$txt_nav[] = $_GET['b'];
	
	$sql_order = "uc.time DESC";
	$sql_select = ", MAX(dispositivo) AS dispositivo, MAX(nav_resolucion) AS nav_resolucion";
	switch ($_GET['b']) {
		case 'IP': 
			$sql_select = ", dispositivo, nav_resolucion"; 
			$sql_where = "IP = '".$_GET['c']."'";
			$sql_limit = 1000;
			break;

		case 'nick': 
			$sql_select = ", dispositivo, nav_resolucion"; 
			$sql_where = "nick IN ('".implode("','", explode('-', $_GET['c']))."')";
			$sql_limit = 1000;
			break;

		case 'user_ID': 
			$sql_select = ", dispositivo, nav_resolucion"; 
			$sql_where = "user_ID IN ('".implode("','", explode('-', $_GET['c']))."')";
			$sql_limit = 1000;
			break;

		case 'nuevos':
			$sql_where = "'true' = 'true' GROUP BY user_ID"; 
			$sql_order = "u.fecha_registro DESC"; 
			break;

		case 'expulsados': 
			$sql_select .= ", MAX(uc.time) AS time"; 
			$sql_where = "u.estado = 'expulsado' GROUP BY user_ID"; 
			$sql_order = "fecha_last DESC"; 
			$sql_uctime = true;
			break;

		case 'SC':  
			$sql_where = "SC = 'true' GROUP BY user_ID"; 
			$sql_order = "voto_confianza DESC";
			$sql_uctime = true;
			break;

		case 'confianza':  
			$sql_where = "'true' = 'true' GROUP BY user_ID";
			$sql_order = "voto_confianza DESC"; 
			break;
		
		case 'desconfianza':  
			$sql_where = "'true' = 'true' GROUP BY user_ID";
			$sql_order = "voto_confianza ASC"; 
			break;

		case 'paises-raros':
			$paises_habituales = '?? ES AR CO MX US UK FR PE DE EC DO';
			$sql_where = "IP_pais NOT IN ('".implode("','", explode(' ', $paises_habituales))."') GROUP BY user_ID";
			$sql_order = "IP_pais ASC"; 
			break;

		case 'mas-de-un-pais':
			$sql_select = ", COUNT(DISTINCT IP_pais) AS num";
			$sql_where = "IP_pais != '??' GROUP BY user_ID HAVING num > 1";
			$sql_order = "num DESC"; 
			break;

		case 'mas-de-un-ISP':
			$sql_select = ", COUNT(DISTINCT ISP) AS num";
			$sql_where = "ISP != '' GROUP BY user_ID HAVING num > 1";
			$sql_order = "num DESC"; 
			break;

			
		case 'mas-de-un-nav':
			$sql_select = ", COUNT(DISTINCT nav_so) AS num";
			$sql_where = "'true' = 'true' GROUP BY user_ID HAVING num > 1";
			$sql_order = "num DESC"; 
			break;

		case 'conexion-oculta':
			$sql_select .= ", MAX(uc.time) AS time"; 
			$sql_where = "ISP LIKE 'Ocultado%' GROUP BY user_ID";
			$sql_uctime = true;
			break;

		case 'actividad':
		default: 
			$sql_select .= ", MAX(uc.time) AS time"; 
			$sql_where = "'true' = 'true' GROUP BY user_ID";
	}


	$clones_array_full = array();
	$result = sql("SELECT user_ID, ISP, uc.host, uc.nav, nav_so, uc.IP, IP_pais, IP_rango, nick, estado, u.pais, pass, nota_SC, email, uc.tipo, ".($sql_uctime==true?'MAX(uc.time) AS time':'uc.time').", v.voto AS has_votado, u.voto_confianza".$sql_select."
FROM users_con `uc`
LEFT OUTER JOIN users `u` ON uc.user_ID = u.ID
LEFT OUTER JOIN votos `v` ON v.tipo = 'confianza' AND uc.user_ID = v.item_ID AND v.emisor_ID = '".$pol['user_ID']."'
WHERE ".$sql_where."
ORDER BY ".$sql_order." LIMIT ".mysql_real_escape_string((is_numeric($sql_limit)?$sql_limit:25)));
	while ($r = r($result)) { $clones_array_full[] = $r['user_ID']; $txt_td .= print_td($r); }
	
	$txt .= '
<fieldset>
<table>
<tr>
<th colspan="3" align="left"><a href="/sc/filtro/user_ID/'.implode('-', $clones_array_full).'" class="button blue small">&nbsp;</a></th>
<th nowrap colspan="2">ISP / Rango / País / IP</th>
<th>Traza</th>
<th nowrap>SO / Navegador</th>
<th>Clave</th>
<th>Email</th>
<th></th>
</tr>
'.$txt_td.'
</table>
</fieldset>';
	break;
	




default:

	if (!$_GET['a']) { $_GET['a'] = 5; }

	$sql_con = "uc.time > '".tiempo($_GET['a'])."' AND ";



	$txt .= '<fieldset><legend>Dispositivo</legend><table>
<tr>
<th colspan="4"></th>
<th nowrap colspan="2">ISP / Rango / País / IP</th>
<th>Traza</th>
<th nowrap>SO / Navegador</th>
<th>Clave</th>
<th>Email</th>
<th></th>
</tr>';
	$clones_array_full = array();
	$result = sql("SELECT COUNT(DISTINCT user_ID) AS num, dispositivo
FROM users_con `uc`
WHERE ".$sql_con."dispositivo IS NOT NULL AND dispositivo != ''
GROUP BY dispositivo
HAVING num > 1
ORDER BY num DESC, time DESC");
	while ($r = r($result)) { 
		$clones_array = array();
		$clones_nick_array = array();
		$txt_tr = '';
		$clon_count = 0;
		$clon_confianza = 0;
		$mostrar = false;
		$result2 = sql("SELECT user_ID, MAX(dispositivo) AS dispositivo, MAX(nav_resolucion) AS nav_resolucion, MAX(uc.time) AS time, ISP, nick, u.estado, u.pais, pass, nota_SC, email, uc.tipo, uc.host, uc.nav, nav_so, uc.IP, IP_pais, IP_rango, v.voto AS has_votado, u.voto_confianza
FROM users_con `uc`
LEFT OUTER JOIN users `u` ON uc.user_ID = u.ID
LEFT OUTER JOIN votos `v` ON v.tipo = 'confianza' AND uc.user_ID = v.item_ID AND v.emisor_ID = '".$pol['user_ID']."'
WHERE dispositivo = '".$r['dispositivo']."' AND dispositivo IS NOT NULL
GROUP BY user_ID
ORDER BY MAX(uc.time) DESC");
		while ($r2 = r($result2)) {
			$txt_tr .= print_td($r2, ++$clon_count);
			$clones_array[] = $r2['user_ID'];
			$clones_nick_array[] = $r2['nick'];
			if ((!in_array($r2['user_ID'], $clones_array_full)) AND ($r2['estado'] != 'expulsado')) { $mostrar = true; $clones_array_full[] = $r2['user_ID']; }
			
			if ($r2['estado'] == 'expulsado') {
				$razon = false;
				$result3 = sql("SELECT razon FROM expulsiones WHERE user_ID = '".$r2['user_ID']."' AND estado = 'expulsado' LIMIT 1");
				while ($r3 = r($result3)) { $razon = $r3['razon']; }
				if (($razon == false) OR ($razon == 'Registro erroneo.')) { $clon_count--; }
			}
			if (($r2['has_votado'] == 1) OR ($r2['user_ID'] == $pol['user_ID'])) { $clon_confianza++; }
		}
		$IDS = array();
		if (($mostrar) AND ($clon_count > 1) AND ($clon_count > $clon_confianza)) {
			$clones_list = implode('-', $clones_array);
			$txt .= '<tr class="tdhead">
<td colspan="8" nowrap><a href="/sc/filtro/user_ID/'.$clones_list.'" class="button blue small">&nbsp;</a> <a href="/control/expulsiones/expulsar/'.implode('-', $clones_nick_array).'" class="button red small">Expulsar '.$clon_count.'</a> <a href="/msg/'.implode('-', $clones_nick_array).'" class="button blue small">MP</a></td>
<td colspan="10"></td>
</tr>'.$txt_tr;
		}
	}
	$txt .= '</table></fieldset>';




	$txt .= '<fieldset><legend>IP</legend><table>';

// IPs publicas de algunos ISPs que sacan a sus clientes compartiendo la misma. Esto es util para identificar estas coincidencias y evitar falsos positivos.
$IP_publicas = array(
'85.62.234',	// Orange movil
'85.62.233',	// Orange movil
'81.45.7',		// Movistar movil
'81.45.52',		// Movistar movil
'195.235.76',	// Movistar movil
'80.58.205',	// Movistar CanguroNet
'93.186.23',	// Blackberry
'93.186.31',	// Blackberry
'77.209.224',	// Airtel movil
'77.209.225',	// Airtel movil
'77.209.226',	// Airtel movil
);

foreach ($IP_publicas AS $IPs) { $longIP_publicas[] = ip2long($IPs); }
	$clones_array_full = array();
	$result = sql("SELECT COUNT(DISTINCT user_ID) AS num, IP 
FROM users_con `uc`
WHERE ".$sql_con."IP NOT IN ('".implode("','", $longIP_publicas)."') AND IP_rango NOT IN ('".implode("','", $IP_publicas)."') AND IP_rango3 NOT IN ('".implode("','", $IP_publicas)."')
GROUP BY IP HAVING num > 1
ORDER BY num DESC, IP ASC");
	while ($r = r($result)) { 
		$clones_array = array();
		$clones_nick_array = array();
		$txt_tr = '';
		$clon_count = 0;
		$clon_confianza = 0;
		$mostrar = false;
		$result2 = sql("SELECT user_ID, MAX(dispositivo) AS dispositivo, MAX(nav_resolucion) AS nav_resolucion, MAX(uc.time) AS time, ISP, nick, u.estado, u.pais, pass, nota_SC, email, uc.tipo, uc.host, uc.nav, nav_so, uc.IP, IP_pais, IP_rango, v.voto AS has_votado, u.voto_confianza
FROM users_con `uc`
LEFT OUTER JOIN users `u` ON uc.user_ID = u.ID
LEFT OUTER JOIN votos `v` ON v.tipo = 'confianza' AND uc.user_ID = v.item_ID AND v.emisor_ID = '".$pol['user_ID']."'
WHERE uc.IP = '".$r['IP']."'
GROUP BY user_ID
ORDER BY uc.time DESC");
		while ($r2 = r($result2)) {
			$txt_tr .= print_td($r2, ++$clon_count);
			if ((!in_array($r2['user_ID'], $clones_array_full)) AND ($r2['estado'] != 'expulsado')) { $mostrar = true; $clones_array_full[] = $r2['user_ID']; }
			$clones_array[] = $r2['user_ID'];
			$clones_nick_array[] = $r2['nick'];
			if ($r2['estado'] == 'expulsado') {
				$razon = false;
				$result3 = sql("SELECT razon FROM expulsiones WHERE user_ID = '".$r2['user_ID']."' AND estado = 'expulsado' LIMIT 1");
				while ($r3 = r($result3)) { $razon = $r3['razon']; }
				if (($razon == false) OR ($razon == 'Registro erroneo.')) { $clon_count--; }
			}
			if (($r2['has_votado'] == 1) OR ($r2['user_ID'] == $pol['user_ID'])) { $clon_confianza++; }
		}
		$IDS = array();
		if (($mostrar) AND ($clon_count > 1) AND ($clon_count > $clon_confianza)) {
			$clones_list = implode('-', $clones_array);
			$txt .= '<tr class="tdhead">
<td colspan="8" nowrap><a href="/sc/filtro/user_ID/'.$clones_list.'" class="button blue small">&nbsp;</a> <a href="/control/expulsiones/expulsar/'.implode('-', $clones_nick_array).'" class="button red small">Expulsar '.$clon_count.'</a> <a href="/msg/'.implode('-', $clones_nick_array).'" class="button blue small">MP</a></td>
<td colspan="10"></td>
</tr>'.$txt_tr;
		}
	}
	$txt .= '</table></fieldset>';





	$txt .= '<fieldset><legend>Clave</legend><table>';
	$clones_array_full = array();
	$result = sql("SELECT COUNT(*) AS num, pass 
FROM users
GROUP BY pass
HAVING num > 1
ORDER BY num DESC, fecha_last DESC");
	while ($r = r($result)) { 
	if (!in_array($r['pass'], $pass_simple)) {
		
		$clones_array = array();
		$clones_nick_array = array();
		$txt_tr = '';
		$clon_count = 0;
		$clon_confianza = 0;
		$mostrar = false;
		$result2 = sql("SELECT user_ID, MAX(dispositivo) AS dispositivo, MAX(nav_resolucion) AS nav_resolucion, MAX(uc.time) AS time, ISP, nick, u.estado, u.pais, pass, nota_SC, email, uc.tipo, uc.host, uc.nav, nav_so, uc.IP, IP_pais, IP_rango, v.voto AS has_votado, u.voto_confianza
FROM users_con `uc`
LEFT OUTER JOIN users `u` ON uc.user_ID = u.ID
LEFT OUTER JOIN votos `v` ON v.tipo = 'confianza' AND uc.user_ID = v.item_ID AND v.emisor_ID = '".$pol['user_ID']."'
WHERE ".$sql_con."u.pass = '".$r['pass']."'
GROUP BY user_ID
ORDER BY dispositivo DESC, MAX(uc.time) DESC");
		while ($r2 = r($result2)) {
			$txt_tr .= print_td($r2, ++$clon_count);
			if ((!in_array($r2['user_ID'], $clones_array_full)) AND ($r2['estado'] != 'expulsado')) { $mostrar = true; $clones_array_full[] = $r2['user_ID']; }
			$clones_array[] = $r2['user_ID'];
			$clones_nick_array[] = $r2['nick'];
			if ($r2['estado'] == 'expulsado') {
				$razon = false;
				$result3 = sql("SELECT razon FROM expulsiones WHERE user_ID = '".$r2['user_ID']."' AND estado = 'expulsado' LIMIT 1");
				while ($r3 = r($result3)) { $razon = $r3['razon']; }
				if (($razon == false) OR ($razon == 'Registro erroneo.')) { $clon_count--; }
			}
			if (($r2['has_votado'] == 1) OR ($r2['user_ID'] == $pol['user_ID'])) { $clon_confianza++; }
		}
		$IDS = array();
		if (($mostrar) AND ($clon_count > 1) AND ($clon_count > $clon_confianza)) {
			$clones_list = implode('-', $clones_array);
			$txt .= '<tr class="tdhead">
<td colspan="8" nowrap><a href="/sc/filtro/user_ID/'.$clones_list.'" class="button blue small">&nbsp;</a> <a href="/control/expulsiones/expulsar/'.implode('-', $clones_nick_array).'" class="button red small">Expulsar '.$clon_count.'</a> <a href="/msg/'.implode('-', $clones_nick_array).'" class="button blue small">MP</a></td>
<td colspan="10"></td>
</tr>'.$txt_tr;
		}
	} }
	$txt .= '</table></fieldset>';




	$txt .= '<fieldset><legend>Ocultación de conexión</legend><table>';
	$clones_array = array();
	$txt_tr = '';
	$clon_count = 0;
	$clon_confianza = 0;
	$result2 = sql("SELECT user_ID, MAX(dispositivo) AS dispositivo, MAX(nav_resolucion) AS nav_resolucion, MAX(uc.time) AS time, ISP, nick, u.estado, u.pais, pass, nota_SC, email, uc.tipo, uc.host, uc.nav, nav_so, uc.IP, IP_pais, IP_rango, v.voto AS has_votado, u.voto_confianza
FROM users_con `uc`
LEFT OUTER JOIN users `u` ON uc.user_ID = u.ID
LEFT OUTER JOIN votos `v` ON v.tipo = 'confianza' AND uc.user_ID = v.item_ID AND v.emisor_ID = '".$pol['user_ID']."'
WHERE ".$sql_con."ISP LIKE 'Ocultado%' AND estado != 'expulsado'
GROUP BY user_ID
ORDER BY MAX(uc.time) DESC, uc.time DESC");
	while ($r2 = r($result2)) {
		$txt_tr .= print_td($r2, ++$clon_count);
		$clones_array[] = $r2['user_ID'];
	}
	$IDS = array();
	$txt .= $txt_tr;
	$txt .= '</table></fieldset>';





	break;
}

// LEYENDA
foreach ($vp['paises'] AS $pais) { $paises .= ' <span style="background:'.$vp['bg'][$pais].';" class="redondeado">'.$pais.'</span>'; }
$txt .= '<fieldset><legend>Leyenda</legend>
<p class="rich">Supervisores del Censo: @'.implode(' @', get_supervisores_del_censo()).'</p>
<p>Plataformas:'.$paises.' &nbsp; Estados de usuario: <b class="ciudadano">'._('Ciudadano').'</b> <b class="turista">'._('Turista').'</b> <b class="validar">'._('Validar').'</b> <b class="expulsado">'._('Expulsado').'</b></p>
</fieldset>';


$txt .= '
<style>
.tdhead td { padding-top:20px; }
.peque { font-size:10px; }
a.button { margin:-3px 0; }
.idu { font-size:12px; padding:7px 5px; margin:-9px 0px; white-space:nowrap; text-shadow:1px 1px 5px 
white; }
#content { background:#ffe7e1; margin-top:0; padding-top:4px; }
</style>

<script type="text/javascript">

function filtro_change(n) { window.location.href = $(n).val(); }

</script>
';

function unico($ID, $es='', $print=false, $ajustar=false) {
	global $IDS, $IDS_bg;
	if ($ID != '') {
		if (!isset($IDS[$es][$ID])) { $IDS[$es][$ID] = count($IDS[$es]); }
		return '<span class="idu" style="background:'.$IDS_bg[$IDS[$es][$ID]].';">'.($print===false?$es:($print===true?$ID:$print)).($print!==true?' <b>'.($ajustar==true&&strlen($IDS[$es][$ID]+1)==1?'0':'').($IDS[$es][$ID]+1).'</b>':'').'</span>';
	} else { return ''; }
}

function print_td($r, $count=false) {
	global $pass_simple, $vp, $pol;
	return '<tr>
<td nowrap><a href="/sc/filtro/user_ID/'.$r['user_ID'].'" class="button blue small">&nbsp;</a> <a href="/control/expulsiones/expulsar/'.$r['nick'].'" class="button red small">&nbsp;</a></td>

'.($count!==false?'<td align="right"><b>'.$count.'.</b></td>':'').'

<td nowrap style="background:'.$vp['bg'][$r['pais']].';"><span class="gris" style="float:right;"><span'.($r['tipo']=='login'?' style="font-weight:bold;"':'').'>'.timer($r['time']).'</span></span>
<b style="font-size:16px;">'.crear_link($r['nick'], 'nick', $r['estado'], $r['pais']).'</b></td>

<td nowrap align="right" style="background:'.$vp['bg'][$r['pais']].';"><span id="confianza'.$r['user_ID'].'">'.confianza($r['voto_confianza']).'</span> '.($pol['user_ID']&&$r['user_ID']!=$pol['user_ID']?'<span id="data_confianza'.$r['user_ID'].'" class="votar" type="confianza" name="'.$r['user_ID'].'" value="'.$r['has_votado'].'"></span>':'').'</td>

<td nowrap colspan="2" title="...'.substr($r['host'],5).'" align="right">'.unico($r['ISP'], 'ISP', true).unico($r['IP_rango'].'.*', 'IP_rango', true).unico($r['IP_pais'], 'IP_pais', true).unico($r['IP'], 'IP', false, true).'</td>
<td nowrap>'.unico($r['dispositivo'], '<b>Traza</b>').'</td>
<td nowrap title="'.$r['nav'].'">'.unico($r['nav'], 'nav', $r['nav_so'].' <span class="peque">'.explodear(' ', $r['nav_resolucion'], 0).'</span>').'</td>
<td nowrap>'.unico($r['pass'], 'Clave', (in_array($r['pass'], $pass_simple)?'Clave simple':'Clave')).'</td>
<td nowrap title="'.$r['email'].'">'.unico(explodear('@', $r['email'], 1), 'email', true).'</td>
<td nowrap>'.print_nota_SC($r['nota_SC'], $r['user_ID']).'</td>
</tr>';
}

function print_nota_SC($nota_SC, $user_ID) {
	global $pol;
	return ($nota_SC!=''?'<form action="'.accion_url($pol['pais']).'a=SC&b=nota&ID='.$user_ID.'" method="post"><input type="text" name="nota_SC" size="15" maxlength="255" value="'.$nota_SC.'" style="margin:-4px 0;'.(substr($nota_SC, 0, 7)=='Cuidado'?'color:red;':'').(substr($nota_SC, 0, 12)=='Comparte con'?'color:green;':'').(substr($nota_SC, 0, 3)=='OK '?'color:blue;':'').'" /> '.boton('OK', 'submit', false, 'small pill').'</form>':'');
}

//THEME
$txt_title = 'Supervisión del Censo - CONFIDENCIAL';
$txt_menu = 'demo';
include('theme.php');
?>
