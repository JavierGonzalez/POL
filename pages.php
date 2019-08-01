<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');


switch ($_GET['a']) {

case 'video':
	$txt_title = 'Vídeo';
	$txt_nav = array('Vídeo');
	$txt .= '<br /><iframe width="640" height="360" src="http://www.youtube-nocookie.com/embed/fbSZf5hToQc" frameborder="0" allowfullscreen></iframe>';
	break;


case 'donaciones':
	$txt_title = 'Donaciones a VirtualPol'; 
	$txt_nav = array('Donaciones');

	$result = mysql_query("SELECT title, text FROM docs WHERE ID = 752 LIMIT 1", $link); // doc_ID 752 = Donaciones
	while($r = mysql_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }


	$text = str_replace(':botones_donar:', '
<table border="0" width="100%" style="margin-bottom:-25px;">
<tr>
	<td align="center" valign="top">
		<span class="gris">PayPal / Tarjeta de crédito</span>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="A6JJDTXA44V9Q">
			<input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
			<img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
		</form>
	</td>
	<td align="center" valign="top">
		<span class="gris">Transferencia bancaria:</span><br />
		3174 5899 90 2025993516<br /> 
		<em>Caixa Vinaros, Javier González</em>
	</td>
</tr>
</table>
', $text);


	$txt .= '
<div>
<h1 style="text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>';
	break;


case 'presentacion':
	include_once('source/inc-functions-accion.php');
	$result = mysql_query("SELECT title, text FROM docs WHERE ID = 577 LIMIT 1", $link); // doc_ID 577 = Test
	while($r = mysql_fetch_array($result)) { presentacion($r['title'], $r['text']); }
	break;


case 'desarrollo':
	$txt_title = 'Desarrollo de VirtualPol2 | Codigo fuente, Software libre, descargar'; 
	$txt_nav = array('Desarrollo');

	$result = mysql_query("SELECT title, text FROM docs WHERE ID = 10 LIMIT 1", $link); // doc_ID 10 = Desarrollo
	while($r = mysql_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }

	$txt .= '
<div>
<h1 style="text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>';
	break;

case 'reglamento-sc':
	$txt_title = 'Reglamento de Supervisión del Censo'; 
	$txt_nav = array('Reglamento de Supervisión del Censo');

	$result = mysql_query("SELECT title, text FROM docs WHERE ID = 1188 LIMIT 1", $link); // doc_ID 1188 = Reglamento
	while($r = mysql_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }

	$txt .= '
<div>
<h1 style="text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>';

	break;


case 'manual': redirect('http://www.virtualpol.com/documentacion'); break;
case 'documentacion':
	$txt_title = 'Documentación de VirtualPol | Manual, ayuda'; 
	$txt_nav = array('Documentación');

	$result = mysql_query("SELECT title, text FROM docs WHERE ID = 2 LIMIT 1", $link); // doc_ID 2 = Documentacion
	while($r = mysql_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }

	$txt .= '
<div>
<h1 style="text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>';
	break;



case 'legal': redirect('http://www.virtualpol.com/TOS'); break;
case 'TOS':
	$txt_nav = array('TOS');
	if (isset($pol['user_ID'])) {
		$result = mysql_query("SELECT fecha_legal FROM users WHERE ID = '".$pol['user_ID']."' AND fecha_legal != '0000-00-00 00:00:00'", $link);
		while($r = mysql_fetch_array($result)) { $fecha_legal = $r['fecha_legal']; }

		if ($fecha_legal) {
			$txt_legal = '<p style="text-align:right;">Como usuario de VirtualPol aceptaste las siguientes condiciones en la fecha: '.$fecha_legal.'.</p>';
			$txt_legal_botones = '';
		} else {
			$txt_legal = 'Como usuario de VirtualPol debes aceptar las siguientes condiciones.<br /><br />';
			$txt_legal_botones = '<div style="margin:30px 0 0 0;">'.boton('HE LEIDO Y ACEPTO TODAS LAS CONDICIONES.', 'http://'.$pol['pais'].'.'.DOMAIN.'/accion.php?a=aceptar-condiciones').'</div>';
		}
	}


	$result = mysql_query("SELECT title, text FROM docs WHERE ID = '1' LIMIT 1", $link); // doc_ID 1 = TOS
	while($r = mysql_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }


	$txt_title = 'CONDICIONES DE USO DE VIRTUALPOL | Informacion legal, contacto';
	$txt_description = 'Condiciones de Uso de VirtualPol. Texto legal, contacto.'; 

	$txt_header .= '<meta name="robots" content="noindex,nofollow" />';

	$txt .= '<em>'.$txt_legal.'</em>

<div>
<h1 style="text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>
'.$txt_legal_botones;
	break;


default: redirect('http://www.'.DOMAIN);
}

include('theme.php');
?>
