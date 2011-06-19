<?php
include('inc-login.php');
$adsense_exclude = true;

$txt_title = 'VirtualPol | Simulador Pol&iacute;tico Espa&ntilde;ol | Juego online de democracia politica | simuladores Espa&ntilde;a, Latinoamerica';

$txt_description = 'La Primera y Unica Plataforma de Auto-gestion y Democracia. Simulador Politico Espa&ntilde;ol, Juego online, experimento social, el mejor simulador de politica de Espa&ntilde;a. Simuladores geo-politica POL Hispania Atlantis. El mejor simulador politico.'; 


if (isset($pol['user_ID'])) {
	$txt .= '<h1>Bienvenido a VirtualPol</h1>

<p>VirtualPol es una <b>plataforma democr&aacute;tica de Internet</b> donde los usuarios se auto-gestionan a s&iacute; mismos. Hasta ahora el &uacute;nico m&eacute;todo de administrar una comunidad en Internet era mediante una estructura rigida y autoritaria.</p>

     <p><b>VirtualPol es una revoluci&oacute;n</b>, con la <b>Democracia</b> como pilar fundamental. Las Elecciones estan automatizadas, por lo tanto no existe ning&uacute;n usuario privilegiado. No hay intervenci&oacute;n del admin, a no ser que una situación determinada dada en un momento determinado no pueda ser arreglada por la ciudadanía. Esto es un avance hist&oacute;rico en las comunidades de Internet. Todos los ciudadanos est&aacute;n en absoluta igualdad. Con las mismas oportunidades para el liderazgo o el fracaso en la b&uacute;squeda del Poder y la auto-gesti&oacute;n. <a href="http://vp.virtualpol.com/historia/">Historia de VirtualPol</a>.</p>';
} else {

	$txt .= '<h1>Bienvenido a VirtualPol - Simulador Politico</h1>

<p><span style="float:right;margin-left:10px;"><iframe src="http://docs.google.com/present/embed?id=ddfcnxdb_15fqwwcpct&interval=30" frameborder="0" width="410" height="342"></iframe></span>VirtualPol es una <b>plataforma democr&aacute;tica de Internet</b> donde los usuarios se auto-gestionan a s&iacute; mismos. Hasta ahora el &uacute;nico m&eacute;todo de administrar una comunidad en Internet era mediante una estructura rigida y autoritaria.</p>

     <p><b>VirtualPol es una revoluci&oacute;n</b>, con la <b>Democracia</b> como pilar fundamental. Las Elecciones estan automatizadas, por lo tanto no existe ning&uacute;n usuario privilegiado. No hay intervenci&oacute;n del admin, a no ser que una situación determinada dada en un momento determinado no pueda ser arreglada por la ciudadanía. Esto es un avance hist&oacute;rico en las comunidades de Internet. Todos los ciudadanos est&aacute;n en absoluta igualdad. Con las mismas oportunidades para el liderazgo o el fracaso en la b&uacute;squeda del Poder y la auto-gesti&oacute;n. <a href="http://vp.virtualpol.com/historia/">Historia de VirtualPol</a>.</p>';

}
if (!$pol['nick']) {
        $txt .= '<br /><center><span class="amarillo" style="padding:17px 9px 13px 9px;"><input value="REGISTRAR CIUDADANO" onclick="window.location.href=\'/registrar/\';" type="button" style="font-size:18px;height:40px;color:red;" /></span></center><br />';
} elseif ($pol['pais'] == 'ninguno'){ 
        $txt .= '<p>' . boton('Solicita ciudadania!', 'http://www.virtualpol.com/registrar/') . '</p>';
}


$txt .= '<center>
<table border="0" cellpadding="5" cellspacing="0">
<tr style="color:grey;">
<th colspan="2" align="left">Plataforma</th>
<th>Poblaci&oacute;n</th>
<th>Antig&uuml;edad</th>
<th>Gobierno</th>
<th colspan="3" align="center">Informaci&oacute;n</th>
</tr>';

foreach ($vp['paises'] AS $pais) {
	$pais_low = strtolower($pais);
	
	// ciudadanos
	$result = mysql_query("SELECT COUNT(ID) AS num FROM users WHERE pais = '".$pais."' AND estado != 'expulsado'", $link);
	while($r = mysql_fetch_array($result)) { $pais_pob = $r['num']; $pais_pob_num[$pais] = $r['num']; }

	// dias de existencia
	$result = mysql_query("SELECT COUNT(stats_ID) AS num FROM stats WHERE pais = '".$pais."'", $link);
	while($r = mysql_fetch_array($result)) { $pais_dias = $r['num']; }


	// Presidente
	$pais_presidente = '';
	$result = mysql_query("SELECT nick FROM users WHERE pais = '".$pais."' AND cargo = '7'", $link);
	while($r = mysql_fetch_array($result)) { $pais_presidente = '<a href="http://'.$pais_low.'.virtualpol.com/perfil/'.strtolower($r['nick']).'/" class="nick"><b style="font-size:18px;">' . $r['nick'] . '</b></a>'; }

	$pais_vice = '';
	$result = mysql_query("SELECT nick FROM users WHERE pais = '".$pais."' AND cargo = '19'", $link);
	while($r = mysql_fetch_array($result)) { $pais_vice = '<a href="http://'.$pais_low.'.virtualpol.com/perfil/'.strtolower($r['nick']).'/" class="nick" style="font-size:18px;">' . $r['nick'] . '</a>'; }

	// DEFCON
	$result = mysql_query("SELECT valor, dato FROM ".$pais_low."_config WHERE dato = 'defcon' OR dato LIKE 'frontera%' OR dato = 'arancel_salida' OR dato = 'pais_des'", $link);
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
<td><a href="http://'.$pais_low.'.virtualpol.com/"><img src="'.IMG.'banderas/'.$pais.'_60.gif" border="0" alt="'.$pais.'" /></a></td>

<td><a href="http://'.$pais_low.'.virtualpol.com/"><b style="font-size:24px;">'.$pais.'</b></a><br /><em style="color:#777;">'.$pais_config['pais_des'].'</em></td>

<td align="right"><b style="font-size:20px;">' . $pais_pob . '</b></td>
<td nowrap="nowrap" align="right"><b>' . $pais_dias . '</b> d&iacute;as</td>
<td nowrap="nowrap"><img src="'.IMG.'cargos/7.gif" alt="Presidente de '.$pais.'" title="Presidente de '.$pais.'" /> '.$pais_presidente.'<br /><img src="'.IMG.'cargos/19.gif" alt="Vicepresidente de '.$pais.'" title="Vicepresidente de '.$pais.'" /> '.$pais_vice.'</td>

<td style="font-size:13px;">
<a href="http://'.$pais_low.'.virtualpol.com/elecciones/">Elecciones</a><br />
<a href="http://'.$pais_low.'.virtualpol.com/foro/">Foro</a><br />
<a href="http://'.$pais_low.'.virtualpol.com/chats/">Chats</a>
</td>

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


$txt .= '
<tr>
<td style="border-bottom:1px solid grey;" colspan="10"></td>
</tr>
<tr>
<td colspan="2"><img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.$gf['censo_num'].'&chs=210x90&chl='.$gf['paises'].'&chco='.$gf['bg_color'].',BBBBBB" alt="Reparto del censo - Simulador Politico" title="Reparto del censo entre Paises" /></td>

<td align="right" valign="top"><b style="font-size:20px;">' . $poblacion_num . '</b></td>
<td colspan="2" valign="top"><b style="font-size:20px;">Ciudadanos</b></td>

<td colspan="3" align="right">

</td>
</tr>
</table>
</center>';


if (!$pol['nick']) {
	$txt .= '<h1>Simulador Pol&iacute;tico Espa&ntilde;ol | Ciudadanos online:</h1>';
}


$time_pre = date('Y-m-d H:i:00', time() - 3600); // 1 hora
$result = mysql_query("SELECT nick, pais, estado
FROM users 
WHERE fecha_last > '" . $time_pre . "' AND estado != 'expulsado'
ORDER BY fecha_last DESC", $link);
while($r = mysql_fetch_array($result)){ 
	$li_online_num++; 
	$gf['censo_online'][$r['pais']]++;

	$pais_url = strtolower($r['pais']);
	if ($pais_url == 'ninguno') { $pais_url = 'vp'; }
	$li_online .= ' <a href="http://'.$pais_url.'.virtualpol.com/perfil/'.$r['nick'].'/" class="nick '.$r['estado'].'" style="padding:2px;line-height:25px;background:' . $vp['bg'][$r['pais']] . ';">'.$r['nick'].'</a>'; 
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
