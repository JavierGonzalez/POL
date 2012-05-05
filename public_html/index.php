<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


$txt_description = 'La primera Red Social Democrática. Simulador Politico y social Español, democracia participativa, simulador, politica'; 



/* Datos estadisticos
Se contabilizan los siguientes datos antiguos conservadas en tablas antiguas.
Plataforma votaciones votos
Atlantis 402 4540
POL 773 13752
Hispania 1079 15546
Vulcan 161 954
VP 369 11341
*/
$result = mysql_query("SELECT COUNT(*) AS num FROM votacion", $link);
while($r = mysql_fetch_array($result)) { $num_votaciones = $r['num']+2784; }

$result = mysql_query("SELECT COUNT(*) AS num FROM votacion_votos", $link);
while($r = mysql_fetch_array($result)) { $num_votaciones_votos = $r['num']+46133; }

$result = mysql_query("SELECT COUNT(*) AS num FROM votos", $link);
while($r = mysql_fetch_array($result)) { $num_votos = $r['num']; }

$txt_nav = array(_('Bienvenido a VirtualPol'));

$txt .= '


<table>

<tr><td valign="top">


<p>VirtualPol es la primera <b>red social democrática</b>.</p>

<p><b>En VirtualPol no hay administrador.</b> Se ha automatizado la democracia. Todo se decide con pilares democr&aacute;ticos (1 ciudadano 1 voto). En VirtualPol hay diferentes plataformas independientes entre s&iacute; que comparten este sistema como base.</p>

<p>Los principales gestores se eligen mediante elecciones peri&oacute;dicas y autom&aacute;ticas, de forma que nadie puede detener el ciclo. De esta forma no existe ning&uacute;n usuario privilegiado, todos parten de la absoluta igualdad de condiciones.</p>

<ul><em>VirtualPol ofrece:</em>

<li><b>Herramientas democráticas</b>: elecciones, votaciones avanzadas, sistema de cargos, <abbr title="El voto de confianza es un voto +1 -1 secreto, que cada usuario otorga a otros usuarios">voto de confianza</abbr>, grupos/partidos, control de <abbr title="Los kicks sirven para moderar, son bloqueos temporales de usuarios">kicks</abbr>, <abbr title="Exámenes tipo test automaticos">exámenes</abbr>...</li>

<li><b>Herramientas de comunicaci&oacute;n</b>: salas de chat, foros, voz (mumble), mensajes privados, notas...</li>

<li>Custodiado por un <b>avanzado sistema de Supervisi&oacute;n del Censo</b> (<a href="'.SSL_URL.'dnie.php" title="Autentificaci&oacute;n mediante DNIe y otros certificados">DNIe</a>, <abbr title="Avanzado sistema de deteccion mediante factores tecnicos">sistema de detecci&oacute;n</abbr>, supervisores elegidos democr&aacute;ticamente, <a href="http://www.'.DOMAIN.'/TOS" title="Condiciones de Uso de VirtualPol">TOS</a>...). M&aacute;s de 3 a&ntilde;os de experiencia funcionando con solidez.</li>

<li>Algunos datos: '.num($num_votaciones_votos).' votos procesados en '.num($num_votaciones).' votaciones y '.num($num_votos).' votos de otros tipos.</li>

<li>Es <a href="/desarrollo">Software Libre</a>, gratuito y sin publicidad.</li>
</ul>


<p>VirtualPol es la primera comunidad de Internet sin administrador. Un paso firme hacia la Democracia Participativa.</p>


</td><td valign="top">

<br />

<table border="0" cellpadding="2" cellspacing="0">
<tr>
<th colspan="2" align="left">Plataformas</th>
<th colspan="2" align="left">Población</th>
</tr>';

$result = mysql_query("SELECT COUNT(ID) AS num FROM users WHERE dnie = 'true'", $link);
while($r = mysql_fetch_array($result)) { $autentificados = $r['num']; }

foreach ($vp['paises'] AS $pais) {
	$pais_low = strtolower($pais);
	
	// ciudadanos
	$result = mysql_query("SELECT COUNT(ID) AS num FROM users WHERE pais = '".$pais."' AND estado = 'ciudadano'", $link);
	while($r = mysql_fetch_array($result)) { $pais_pob = $r['num']; $pais_pob_num[$pais] = $r['num']; }

	// dias de existencia
	$result = mysql_query("SELECT COUNT(stats_ID) AS num FROM stats WHERE pais = '".$pais."'", $link);
	while($r = mysql_fetch_array($result)) { $pais_dias = $r['num']; }


	// Presidente
	$pais_presidente = '';
	$result = mysql_query("SELECT nick FROM users WHERE pais = '".$pais."' AND cargo = '7'", $link);
	while($r = mysql_fetch_array($result)) { $pais_presidente = '<a href="http://'.$pais_low.'.'.DOMAIN.'/perfil/'.strtolower($r['nick']).'" class="nick"><b style="font-size:18px;">'.$r['nick'].'</b></a>'; }

	$pais_vice = '';
	$result = mysql_query("SELECT nick FROM users WHERE pais = '".$pais."' AND cargo = '19'", $link);
	while($r = mysql_fetch_array($result)) { $pais_vice = '<a href="http://'.$pais_low.'.'.DOMAIN.'/perfil/'.strtolower($r['nick']).'" class="nick" style="font-size:18px;">' . $r['nick'] . '</a>'; }

	// DEFCON
	$result = mysql_query("SELECT valor, dato FROM config WHERE pais = '".$pais_low."' AND dato = 'pais_des'", $link);
	while($r = mysql_fetch_array($result)) { $pais_config[$r['dato']] = $r['valor']; }


	// GEN GRAFICO CIRCULAR
	if ($gf['censo_num']) { $gf['censo_num'] .= ','; }
	$gf['censo_num'] .= $pais_pob;

	$poblacion_num += $pais_pob;

	if ($gf['bg_color']) { $gf['bg_color'] .= ','; }
	$gf['bg_color'] .= substr($vp['bg'][$pais],1);

	if ($gf['paises']) { $gf['paises'] .= '|'; }
	$gf['paises'] .= $pais;

	$moneda = 'Monedas';


	$txt .= '<tr style="background:'.$vp['bg'][$pais].';">
<td><a href="http://'.$pais_low.'.'.DOMAIN.'"><img src="'.IMG.'banderas/'.$pais.'_60.gif" border="0" alt="'.$pais.'" width="60" height="40" /></a></td>

<td nowrap="nowrap"><a href="http://'.$pais_low.'.'.DOMAIN.'"><b style="font-size:24px;">'.$pais.'</b></a><br /><em style="color:#777;">'.$pais_config['pais_des'].'</em></td>

<td align="right"><b style="font-size:22px;">'.num($pais_pob).'</b></td>
<td nowrap="nowrap" align="right"><b>'.num($pais_dias).'</b> días</td>

</tr>';

}

$result = mysql_query("SELECT COUNT(ID) AS num FROM users WHERE pais = 'ninguno' AND estado != 'expulsado'", $link);
while($r = mysql_fetch_array($result)){ 
	$poblacion_num += $r['num'];

	if ($gf['censo_num']) { $gf['censo_num'] .= ','; }
	$gf['censo_num'] .= $r['num'];
	$pob_ninguno = $r['num'];

	if ($gf['paises']) { $gf['paises'] .= '|'; }
	$gf['paises'] .= 'Turistas';	
}


$txt .= '<tr><td style="border-bottom:1px solid grey;" colspan="4"></td></tr>

<tr>
<td colspan="2" rowspan="2" align="center" valign="top"><img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.$gf['censo_num'].'&chds=a&chs=190x90&chl='.$gf['paises'].'&chco='.$gf['bg_color'].',BBBBBB&chf=bg,s,ffffff01|c,s,ffffff01&chco=FF9900|FFBE5E|FFD08A|FFDBA6" alt="Reparto del censo - Simulador Politico" title="Reparto de la poblaci&oacute;n entre plataformas." width="190" height="90" /></td>
<td align="right" valign="top"><b style="font-size:20px;">'.num($poblacion_num).'</b></td>
<td colspan="2" valign="middle"><b>Ciudadanos</b></td>
</tr>

<tr>
<td align="right" valign="top" colspan="2"><b>'.num($autentificados).'</b> Autentificados</td>
</tr>


<tr>
<td colspan="4" align="right">'.(nucleo_acceso('antiguedad', 2)?boton('Solicitar nueva plataforma', '/crear-plataforma.php', false, 'small pill'):'').'</td>
</tr>

</table>



</td></tr></table>

'.(isset($pol['nick'])?'':'<p style="text-align:center;">'.boton('Crear ciuadano', REGISTRAR, false, 'large blue').'</p>');



$result = sql("SELECT COUNT(*) AS num FROM plataformas WHERE estado = 'pendiente'");
while($r = r($result)) { $plat_num = $r['num']; }

if ($pol['user_ID'] == 1) {
	$txt_tab['/crear-plataforma.php?a=admin'] = 'Plataformas pendientes ('.$plat_num.')';
}

$txt_header .= '<style type="text/css">td b { font-size:15px; }</style>';

include('theme.php');
?>