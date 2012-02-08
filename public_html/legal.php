<?php
include('inc-login.php');

if ($pol['user_ID']) {
	$result = mysql_query("SELECT fecha_legal FROM users WHERE ID = '".$pol['user_ID']."' AND fecha_legal != '0000-00-00 00:00:00'", $link);
	while($r = mysql_fetch_array($result)) { $fecha_legal = $r['fecha_legal']; }

	if ($fecha_legal) {
		$txt_legal = 'Como usuario de VirtualPol aceptaste las siguientes condiciones. ('.$fecha_legal.')<br /><br />';
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

$txt_header .= '
<meta name="robots" content="noindex,nofollow" />
<style type="text/css">
.content { width:800px; margin: 0 auto; }
</style>';

$txt .= '<em>'.$txt_legal.'</em>

<div style="color:#555;">
<h1 style="color:#444;text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>


'.$txt_legal_botones;


include('theme.php');
?>
