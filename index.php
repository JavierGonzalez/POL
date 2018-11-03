<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


$txt_description = _('La primera Red Social Democrática').'. Simulador Politico y social Español, democracia participativa, simulador, politica'; 

/* Datos estadisticos
Se contabilizan los siguientes datos antiguos conservadas en tablas antiguas.
Plataforma votaciones votos
Atlantis 402 4540
POL 773 13752
Hispania 1079 15546
Vulcan 161 954
VP 369 11341
*/
$result = sql("SELECT COUNT(*) AS num FROM votacion");
while($r = r($result)) { $num_votaciones = $r['num']+2784; }

$result = sql("SELECT COUNT(*) AS num FROM votacion_votos");
while($r = r($result)) { $num_votaciones_votos = $r['num']+46133; }

$result = sql("SELECT COUNT(*) AS num FROM votos");
while($r = r($result)) { $num_votos = $r['num']; }

$txt_nav = array(_('Bienvenido a VirtualPol'));

$txt .= '<table>

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

<table border="0" cellpadding="2" cellspacing="0" width="430">
<tr>
<th align="left">'._('Plataformas').'</th>
<th align="right">'._('Usuarios').'</th>
</tr>';


$result = sql("SELECT pais, valor AS num FROM config WHERE dato = 'info_censo' ORDER BY ABS(valor) DESC LIMIT 100");
while($r = r($result)) {

	$pais = $r['pais'];
	$pais_low = strtolower($pais);

	$result2 = sql("SELECT valor, dato FROM config WHERE pais = '".$pais."' AND dato IN ('pais_des', 'tipo', 'bg_color')");
	while($r2 = r($result2)) { $pais_config[$r2['dato']] = $r2['valor']; }

	$txt .= '<tr style="background:'.$pais_config['bg_color'].';'.($r['num']<0?'display:none;" class="p-inactiva"':'"').'>
<td><a href="http://'.$pais_low.'.'.DOMAIN.'"><img src="'.IMG.'banderas/'.$pais.'.png" width="80" height="50" border="0" alt="'.$pais.'" /></a></td>

<td><span style="float:right;font-size:22px;"><b>'.num($r['num']).'</b></span><a href="http://'.$pais_low.'.'.DOMAIN.'"><b style="font-size:'.($r['num']>1000?18:16).'px;">'.$pais_config['pais_des'].'</b></a><br />
<em style="color:#777;">'.ucfirst($pais_config['tipo']).'</em></td>

</tr>';
	
	$poblacion_num += $r['num'];
}


$txt .= '<tr><td style="border-bottom:1px solid grey;" colspan="2"><!--<a href="#" onclick="$(\'tr .p-inactiva\').toggle();return false;">'._('Ver todas las plataformas').'</a>--></td></tr>

<tr>
<td colspan="2"><span style="float:right;font-size:20px;"><b>'.num($poblacion_num).'</b></span><!--'.(nucleo_acceso('antiguedad', 2)?boton(_('Solicitar nueva plataforma'), '/crear-plataforma.php', false, 'small pill'):'').'--></td>
</tr>

</table>

</td></tr></table>';

$result = sql("SELECT COUNT(*) AS num FROM plataformas WHERE estado = 'pendiente'");
while($r = r($result)) { $plat_num = $r['num']; }

if ($pol['user_ID'] == 1) { $txt_tab['/crear-plataforma.php?a=admin'] = 'Plataformas pendientes ('.$plat_num.')'; }

include('theme.php');
?>