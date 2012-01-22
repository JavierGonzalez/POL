<?php
include('inc-login.php');

$txt_title = 'VirtualPol | Ecosistema de plataformas democr&aacute;ticas autogestionadas | VP - 15M asamblea | democracia online - simulador politico';

$txt_description = 'Ecosistema de plataformas democraticas autogestioandas. Simulador Politico Espa&ntilde;ol, Juego online, experimento social o simulador de politica. VP POL 15M atlantis vulcan hispania'; 



// '.(isset($pol['user_ID'])?'':'<span style="float:right;margin-left:10px;"><iframe src="http://docs.google.com/present/embed?id=ddfcnxdb_15fqwwcpct&interval=30" frameborder="0" width="410" height="342"></iframe></span>').'



// Datos estadisticos

/*
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

$txt .= '<h1>Bienvenido a VirtualPol</h1>

<p>VirtualPol es un ecosistema de <b>plataformas democr&aacute;ticas autogestionadas</b>.</p>

<p><b>En VirtualPol no hay administrador.</b> Se ha automatizado la democracia. Todo se decide con pilares democr&aacute;ticos (1 ciudadano 1 voto). En VirtualPol hay diferentes plataformas independientes entre s&iacute; que comparten este sistema como base.</p>

<p>Los principales gestores se eligen mediante elecciones peri&oacute;dicas y autom&aacute;ticas, de forma que nadie puede detener el ciclo. De esta forma no existe ning&uacute;n usuario privilegiado, todos parten de la absoluta igualdad de condiciones.</p>

<ul><em>VirtualPol ofrece:</em>

<li><b>Herramientas democr&aacute;ticas</b>: elecciones, votaciones avanzadas, sistema de cargos, <abbr title="El voto de confianza es un voto +1 -1 secreto, que cada usuario otorga a otros usuarios">voto de confianza</abbr>, grupos/partidos, control de <abbr title="Los kicks sirven para moderar, son bloqueos temporales de usuarios">kicks</abbr>, <abbr title="Ex&aacute;menes tipo test automaticos">ex&aacute;menes</abbr>...</li>

<li><b>Herramientas de comunicaci&oacute;n</b>: salas de chat, foros, voz (mumble), mensajes privados, notas...</li>

<li>Custodiado por un <b>avanzado sistema de Supervisi&oacute;n del Censo</b> (<a href="https://virtualpol.com/dnie.php" title="Autentificaci&oacute;n mediante DNIe y otros certificados">DNIe</a>, <abbr title="Avanzado sistema de deteccion mediante factores tecnicos">sistema de detecci&oacute;n</abbr>, supervisores elegidos democr&aacute;ticamente, <a href="http://www.virtualpol.com/legal" title="Condiciones de Uso de VirtualPol">TOS</a>...). M&aacute;s de 3 a&ntilde;os de experiencia funcionando con solidez.</li>

<li>Algunos datos: '.num($num_votaciones_votos).' votos procesados en '.num($num_votaciones).' votaciones y '.num($num_votos).' votos de otros tipos.</li>

<li>Es <a href="/codigo">Software Libre</a>.</li>
</ul>


<p>VirtualPol es la primera comunidad de Internet sin administrador. Un paso firme hacia la Democracia Directa.</p>';


$txt .= '<center>
<table border="0" cellpadding="5" cellspacing="0" width="60%">
<tr style="color:grey;">
<th colspan="2" align="left">Plataformas</th>
<th>Poblaci&oacute;n</th>
<th>Antig&uuml;edad</th>
<th></th>
<th></th>
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
	while($r = mysql_fetch_array($result)) { $pais_presidente = '<a href="http://'.$pais_low.'.virtualpol.com/perfil/'.strtolower($r['nick']).'/" class="nick"><b style="font-size:18px;">' . $r['nick'] . '</b></a>'; }

	$pais_vice = '';
	$result = mysql_query("SELECT nick FROM users WHERE pais = '".$pais."' AND cargo = '19'", $link);
	while($r = mysql_fetch_array($result)) { $pais_vice = '<a href="http://'.$pais_low.'.virtualpol.com/perfil/'.strtolower($r['nick']).'/" class="nick" style="font-size:18px;">' . $r['nick'] . '</a>'; }

	// DEFCON
	$result = mysql_query("SELECT valor, dato FROM ".$pais_low."_config WHERE dato = 'pais_des'", $link);
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
<td><a href="http://'.$pais_low.'.virtualpol.com/"><img src="'.IMG.'banderas/'.$pais.'_60.gif" border="0" alt="'.$pais.'" width="60" height="40" /></a></td>

<td nowrap="nowrap"><a href="http://'.$pais_low.'.virtualpol.com/"><b style="font-size:24px;">'.$pais.'</b></a><br /><em style="color:#777;">'.$pais_config['pais_des'].'</em></td>

<td align="right"><b style="font-size:22px;">'.num($pais_pob).'</b></td>
<td nowrap="nowrap" align="right"><b>'.num($pais_dias).'</b> d&iacute;as</td>

<td style="font-size:13px;">
<a href="http://'.$pais_low.'.virtualpol.com/elecciones/">Elecciones</a><br />
<a href="http://'.$pais_low.'.virtualpol.com/votacion/">Votaciones</a><br />
<a href="http://'.$pais_low.'.virtualpol.com/chats/">Chats</a> <a href="http://'.$pais_low.'.virtualpol.com/foro/">Foro</a>
</td>

<td nowrap="nowrap">'.($pais!='VP'?'':'<img src="'.IMG.'cargos/7.gif" alt="Presidente de '.$pais.'" title="Presidente de '.$pais.'" width="16" height="16" /> '.$pais_presidente.'<br /><img src="'.IMG.'cargos/19.gif" alt="Vicepresidente de '.$pais.'" title="Vicepresidente de '.$pais.'" width="16" height="16" /> '.$pais_vice.'').'</td>
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


$txt .= '<tr><td style="border-bottom:1px solid grey;" colspan="10"></td></tr>

<tr>
<td colspan="2" rowspan="2" align="right"><img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.$gf['censo_num'].'&chds=a&chs=225x110&chl='.$gf['paises'].'&chco='.$gf['bg_color'].',BBBBBB" alt="Reparto del censo - Simulador Politico" title="Reparto de la poblaci&oacute;n entre plataformas." width="225" height="110" /></td>
<td align="right" valign="top"><b style="font-size:22px;">'.num($poblacion_num).'</b></td>
<td colspan="2" valign="top"><b style="font-size:20px;">Ciudadanos</b></td>
<td colspan="3" align="right"></td>
</tr>

<tr>
<td align="right" valign="top"><b style="font-size:18px;">'.num($autentificados).'</b></td>
<td colspan="2" valign="top">Autentificados</td>
<td colspan="3" align="right"></td>
</tr>

</table>
</center>';


if (!$pol['nick']) {
	$txt .= '<p style="text-align:center;"><span class="amarillo" style="padding:17px 9px 13px 9px;"><input value="REGISTRAR CIUDADANO" onclick="window.location.href=\''.REGISTRAR.'\';" type="button" style="font-size:18px;height:40px;" /></span></p>';
} elseif ($pol['pais'] == 'ninguno'){ 
	$txt .= '<p>'.boton('Solicitar ciudadania', REGISTRAR).'</p>';
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
	$li_online .= ' <a href="http://'.$pais_url.'.virtualpol.com/perfil/'.$r['nick'].'/" class="nick redondeado '.$r['estado'].'" style="padding:2px;line-height:25px;background:' . $vp['bg'][$r['pais']] . ';">'.$r['nick'].'</a>'; 
}

$txt .= '<br />

<div class="amarillo" style="width:90%;margin:0 auto;">
<table border="0">
<tr>
<td><b style="font-size:34px;">'.num($li_online_num).'</b></td>
<td>Ciudadanos online: '.$li_online.'</td>
</tr>
</table></div>'; 


$txt_header .= '<style type="text/css">td b { font-size:15px; }</style>';

include('theme.php');
?>