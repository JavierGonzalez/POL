<?php
include('inc-login.php');
$adsense_exclude = true;

$txt_title = 'VirtualPol | Simulador Pol&iacute;tico Espa&ntilde;ol | Juego online de democracia politica | simuladores Espa&ntilde;a, Latinoamerica';

$txt_description = 'La Primera y Unica Plataforma de Auto-gestion y Democracia. Simulador Politico Espa&ntilde;ol, Juego online, experimento social, el mejor simulador de politica de Espa&ntilde;a. Simuladores geo-politica POL Hispania Atlantis. El mejor simulador politico.'; 


if (isset($pol['user_ID'])) {
	$txt .= '<h1>Bienvenido a VirtualPol</h1>

<p>VirtualPol es una <b>plataforma democr&aacute;tica de Internet</b> donde los usuarios se auto-gestionan a s&iacute; mismos. Hasta ahora el &uacute;nico m&eacute;todo de administrar una comunidad en Internet era mediante una estructura rigida y autoritaria.</p>

<p><b>VirtualPol es una revoluci&oacute;n</b>, con la <b>Democracia</b> como pilar fundamental. Las Elecciones estan automatizadas, por lo tanto no existe ning&uacute;n usuario privilegiado. No hay intervenci&oacute;n del admin, a no ser que una situación determinada dada en un momento determinado no pueda ser arreglada por la ciudadanía. Esto es un avance hist&oacute;rico en las comunidades de Internet. Todos los ciudadanos est&aacute;n en absoluta igualdad. Con las mismas oportunidades para el liderazgo o el fracaso en la b&uacute;squeda del Poder y la auto-gesti&oacute;n. Ahora mismo solo existe un país llamado VP, pero hay mas paises como Hispania, Pol, o Atlantis, los cuales se encuentran cerrados, según vaya subiendo el número de ciudadanos se irán abriendo. <a href="http://vp.virtualpol.com/historia/" target="Historia de VirtualPol"><b>Historia de VirtualPol</b></a></p>';
} else {

	$txt .= '<h1>Bienvenido a VirtualPol - Simulador Politico</h1>

<p><span style="float:right;margin-left:10px;"><iframe src="http://docs.google.com/present/embed?id=ddfcnxdb_15fqwwcpct&interval=30" frameborder="0" width="410" height="342"></iframe></span>VirtualPol es una <b>plataforma democr&aacute;tica de Internet</b> donde los usuarios se auto-gestionan a s&iacute; mismos. Hasta ahora el &uacute;nico m&eacute;todo de administrar una comunidad en Internet era mediante una estructura rigida y autoritaria.</p>

<p><b>VirtualPol es una revoluci&oacute;n</b>, con la <b>Democracia</b> como pilar fundamental. Las Elecciones estan automatizadas, por lo tanto no existe ning&uacute;n usuario privilegiado. No hay intervenci&oacute;n del admin, a no ser que una situación determinada dada en un momento determinado no pueda ser arreglada por la ciudadanía. Esto es un avance hist&oacute;rico en las comunidades de Internet. Todos los ciudadanos est&aacute;n en absoluta igualdad. Con las mismas oportunidades para el liderazgo o el fracaso en la b&uacute;squeda del Poder y la auto-gesti&oacute;n. Ahora mismo solo existe un país llamado VP, pero hay mas paises como Hispania, Pol, o Atlantis, los cuales se encuentran cerrados, según vaya subiendo el número de ciudadanos se irán abriendo. <a href="http://vp.virtualpol.com/historia/" target="Historia de VirtualPol"><b>Historia de VirtualPol</b></a></p><p><b>VirtualPol es una revoluci&oacute;n</b>, con la <b>Democracia</b> como pilar fundamental. Las Elecciones estan automatizadas, por lo tanto no existe ning&uacute;n usuario privilegiado. No hay intervenci&oacute;n de admin. Esto es un avance hist&oacute;rico en las comunidades de Internet. Todos los ciudadanos est&aacute;n en absoluta igualdad. Con las mismas oportunidades para el liderazgo o el fracaso en la b&uacute;squeda del Poder y la auto-gesti&oacute;n.</p>';

}


$txt .= '
<table border="0" cellpadding="5" cellspacing="0">
<tr>
<th align="center" colspan="2">Pa&iacute;ses</th>
<th colspan="2">Poblaci&oacute;n</th>
<th>Antig&uuml;edad</th>
<th>Gobierno</th>
<th colspan="3" align="center">Informaci&oacute;n</th>
</tr>';
$vp['paises2'] = array('VP'); // cambiar cuando se cambie en el config
foreach ($vp['paises2'] AS $pais) {
	$pais_low = strtolower($pais);
	
	// ciudadanos
	$result = mysql_query("SELECT COUNT(ID) AS num, AVG(voto_confianza) AS confianza FROM ".SQL_USERS." WHERE pais = '".$pais."' AND estado != 'expulsado'", $link);
	while($row = mysql_fetch_array($result)) { $pais_pob = $row['num']; $pais_pob_num[$pais] = $row['num']; $pais_conf = $row['confianza']; }

	// dias de existencia
	$result = mysql_query("SELECT COUNT(stats_ID) AS num FROM stats WHERE pais = '".$pais."'", $link);
	while($row = mysql_fetch_array($result)) { $pais_dias = $row['num']; }

	// dinero en personal
	$result = mysql_query("SELECT SUM(pols) AS num FROM ".SQL_USERS." WHERE pais = '".$pais."'", $link);
	while($row = mysql_fetch_array($result)) { $pais_monedas_p = $row['num']; }
	// dinero en cuentas
	$result = mysql_query("SELECT SUM(pols) AS num FROM ".$pais_low."_cuentas", $link);
	while($row = mysql_fetch_array($result)) { $pais_monedas_c = $row['num']; }


	// Presidente
	$pais_presidente = '';
	$result = mysql_query("SELECT nick FROM ".SQL_USERS." WHERE pais = '".$pais."' AND cargo = '7'", $link);
	while($row = mysql_fetch_array($result)) { $pais_presidente = '<a href="http://'.$pais_low.'.virtualpol.com/perfil/'.strtolower($row['nick']).'/" class="nick"><b style="font-size:18px;">' . $row['nick'] . '</b></a>'; }

	$pais_vice = '';
	$result = mysql_query("SELECT nick FROM ".SQL_USERS." WHERE pais = '".$pais."' AND cargo = '19'", $link);
	while($row = mysql_fetch_array($result)) { $pais_vice = '<a href="http://'.$pais_low.'.virtualpol.com/perfil/'.strtolower($row['nick']).'/" class="nick" style="font-size:18px;">' . $row['nick'] . '</a>'; }

	// DEFCON
	$result = mysql_query("SELECT valor, dato FROM ".$pais_low."_config WHERE dato = 'defcon' OR dato LIKE 'frontera%' OR dato = 'arancel_salida' OR dato = 'pais_des'", $link);
	while($row = mysql_fetch_array($result)) { $pais_config[$row['dato']] = $row['valor']; }


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
<td><a href="http://' . $pais_low . '.virtualpol.com/"><img src="'.IMG.'banderas/'.$pais.'_60.gif" border="0" alt="'.$pais.' - Simulador Politico" /></a></td>

<td><a href="http://' . $pais_low . '.virtualpol.com/"><b style="font-size:24px;">' . $pais . '</b></a><br /><em style="color:#999;">'.$pais_config['pais_des'].'</em></td>

<td align="right"><b style="font-size:20px;">' . $pais_pob . '</b></td>
<td><acronym title="Nivel de Confianza media">'.confianza(round($pais_conf, 1)).'</acronym></td>
<td nowrap="nowrap" align="right"><b>' . $pais_dias . '</b> d&iacute;as</td>
<td nowrap="nowrap"><img src="'.IMG.'cargos/7.gif" alt="Presidente de '.$pais.'" title="Presidente de '.$pais.'" /> '.$pais_presidente.'<br /><img src="'.IMG.'cargos/19.gif" alt="Vicepresidente de '.$pais.'" title="Vicepresidente de '.$pais.'" /> '.$pais_vice.'</td>

<td align="right" nowrap="nowrap" style="font-size:13px;"><acronym title="CONdici&oacute;n de DEFensa">DEFCON</acronym> <b>' . $pais_config['defcon'] . '</b><br />';

foreach ($vp['paises'] as $pais2) {
	if ($pais != $pais2) {
		$txt .= 'Frontera con '.$pais2.' <b>' . ucfirst($pais_config['frontera_con_'.$pais2]) . '</b><br />';
	}
}	
$txt .=
pols($pais_monedas_p + $pais_monedas_c) . ' '.MONEDA.' <acronym style="color:red;" title="Arancel de salida de moneda.">'.$pais_config['arancel_salida'].'%</acronym>
</td>

<td style="font-size:13px;"><a href="http://'.$pais_low.'.virtualpol.com/poderes/">Poderes</a><br />
<a href="http://'.$pais_low.'.virtualpol.com/foro/">Foro</a><br />
<a href="http://'.$pais_low.'.virtualpol.com/chats/">Chats</a></td>

</tr>';





// GEN GRAFICO VISITAS
$n = 0;
$result = mysql_query("SELECT ciudadanos, time FROM stats WHERE pais = '".$pais."' ORDER BY time DESC LIMIT 9", $link);
while($row = mysql_fetch_array($result)){
		
	if ($gph[$pais]) { $gph[$pais] = ',' . $gph[$pais]; }

	

	$gph_maxx[$n] += $row['ciudadanos'];



	$gph[$pais] = $row['ciudadanos'] . $gph[$pais];

	if ($gph_maxx[$n] > $gph_max) {
		$gph_max = $gph_maxx[$n];
	}
	$n++;
}


}

$result = mysql_query("SELECT COUNT(ID) AS num FROM ".SQL_USERS." WHERE pais = 'ninguno' AND estado != 'expulsado'", $link);
while($row = mysql_fetch_array($result)){ 
	$poblacion_num += $row['num'];

	if ($gf['censo_num']) { $gf['censo_num'] .= ','; }
	$gf['censo_num'] .= $row['num'];
	$pob_ninguno = $row['num'];

	if ($gf['paises']) { $gf['paises'] .= '|'; }
	$gf['paises'] .= 'Ninguno';	
}


if (max($pais_pob_num) > $gph_max) { $gph_max = max($pais_pob_num); } 

if (($poblacion_num - $pob_ninguno) > $gph_max) { $gph_max = $poblacion_num - $pob_ninguno; } 




$txt .= '
<tr>
<td style="border-bottom:1px solid grey;" colspan="10"></td>
</tr>
<tr>
<td colspan="2"><img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.$gf['censo_num'].'&chs=200x90&chl='.$gf['paises'].'&chco='.$gf['bg_color'].',BBBBBB" alt="Reparto del censo - Simulador Politico" title="Reparto del censo entre Paises" /></td>

<td align="right" valign="top"><b style="font-size:20px;">' . $poblacion_num . '</b></td>
<td colspan="2" valign="top"><b style="font-size:20px;">Ciudadanos</b></td>

<td colspan="3" align="right">

<img src="http://chart.apis.google.com/chart?cht=lc
&chs=320x90
&cht=bvs
&chco='.substr($vp['bg']['POL'],1).','.substr($vp['bg']['Hispania'],1).','.substr($vp['bg']['Atlantis'],1).'
&chd=t:'.$gph['POL'].','.$pais_pob_num['POL'].'|'.$gph['Hispania'].','.$pais_pob_num['Hispania'].'|'.$gph['Atlantis'].','.$pais_pob_num['Atlantis'].'
&chds=0,'.$gph_max.'
&chxt=r
&chxl=0:||'.round($poblacion_num / 2).'|'.$poblacion_num.'
" alt="Censo - Simulador Politico" />

</td>
</tr>
</table>';


if (!$pol['nick']) {
	$txt .= '<br /><center><span class="amarillo" style="background:blue;padding:17px 9px 13px 9px;"><input value="REGISTRAR CIUDADANO" onclick="window.location.href=\'/registrar/\';" type="button" style="font-size:18px;height:40px;color:red;" /></span></center><br /><h1>Simulador Pol&iacute;tico Espa&ntilde;ol | Ciudadanos online:</h1>';
} elseif ($pol['pais'] == 'ninguno'){ 
	$txt .= '<p>' . boton('Solicita ciudadania!', 'http://www.virtualpol.com/registrar/') . '</p>';
}


$time_pre = date('Y-m-d H:i:00', time() - 3600); // 1 hora
$result = mysql_query("SELECT nick, pais, estado
FROM ".SQL_USERS." 
WHERE fecha_last > '" . $time_pre . "' AND estado != 'desarrollador' AND estado != 'expulsado'
ORDER BY fecha_last DESC", $link);
while($row = mysql_fetch_array($result)){ 
	$li_online_num++; 
	$gf['censo_online'][$row['pais']]++;

	$pais_url = strtolower($row['pais']);
	if ($pais_url == 'ninguno') { $pais_url = 'pol'; }
	$li_online .= ' <a href="http://'.$pais_url.'.virtualpol.com/perfil/'.$row['nick'].'/" class="nick '.$row['estado'].'" style="padding:2px;line-height:25px;background:' . $vp['bg'][$row['pais']] . ';">'.$row['nick'].'</a>'; 
}

$txt .= '<br /><div class="amarillo">
<table border="0">
<tr>
<td><b style="font-size:34px;">' . $li_online_num . '</b></td>
<td>Ciudadanos online: ' . $li_online . '</td>
</tr>
</table></div>'; 


$txt_header .= '<style type="text/css">td b { font-size:15px; }</style>';

include('theme.php');
?>
