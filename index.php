<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



switch ($_GET[1]) {


case 'video':
	$txt_title = 'Vídeo';
	$txt_nav = array('Vídeo');
	echo '<br />';
	echo '2012:<br/><iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/fbSZf5hToQc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
	echo '<br /><br />';
	echo 'Mas antiguo:<br /><iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/WZhkO5E2nL0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
	break;


case 'presentacion':
	$result = mysql_query_old("SELECT title, text FROM docs WHERE ID = 577 LIMIT 1", $link); // doc_ID 577 = Test
	while($r = mysqli_fetch_array($result)) { presentacion($r['title'], $r['text']); }
	break;


case 'desarrollo':
	$txt_title = 'Desarrollo de VirtualPol | Codigo fuente, Software libre, descargar'; 
	$txt_nav = array('Desarrollo');

	$result = mysql_query_old("SELECT title, text FROM docs WHERE ID = 10 LIMIT 1", $link); // doc_ID 10 = Desarrollo
	while($r = mysqli_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }

	echo '
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

	$result = mysql_query_old("SELECT title, text FROM docs WHERE ID = 1188 LIMIT 1", $link); // doc_ID 1188 = Reglamento
	while($r = mysqli_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }

	echo '
<div>
<h1 style="text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>';

	break;


case 'documentacion':
	$txt_title = 'Documentación de VirtualPol | Manual, ayuda'; 
	$txt_nav = array('Documentación');

	$result = mysql_query_old("SELECT title, text FROM docs WHERE ID = 2 LIMIT 1", $link); // doc_ID 2 = Documentacion
	while($r = mysqli_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }

	echo '
<div>
<h1 style="text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>';
	break;


case 'TOS':
	$txt_nav = array('TOS');
	if (isset($pol['user_ID'])) {
		$result = mysql_query_old("SELECT fecha_legal FROM users WHERE ID = '".$pol['user_ID']."' AND fecha_legal != '0000-00-00 00:00:00'", $link);
		while($r = mysqli_fetch_array($result)) { $fecha_legal = $r['fecha_legal']; }

		if ($fecha_legal) {
			$txt_legal = '<p style="text-align:right;">Como usuario de VirtualPol aceptaste las siguientes condiciones en la fecha: '.$fecha_legal.'.</p>';
			$txt_legal_botones = '';
		} else {
			$txt_legal = 'Como usuario de VirtualPol debes aceptar las siguientes condiciones.<br /><br />';
			$txt_legal_botones = '<div style="margin:30px 0 0 0;">'.boton('HE LEIDO Y ACEPTO TODAS LAS CONDICIONES.', 'http://'.$pol['pais'].'.'.DOMAIN.'/accion/aceptar-condiciones').'</div>';
		}
	}


	$result = mysql_query_old("SELECT title, text FROM docs WHERE ID = '1' LIMIT 1", $link); // doc_ID 1 = TOS
	while($r = mysqli_fetch_array($result)) { $title = $r['title']; $text = $r['text']; }


	$txt_title = 'CONDICIONES DE USO DE VIRTUALPOL | Informacion legal, contacto';
	$txt_description = 'Condiciones de Uso de VirtualPol. Texto legal, contacto.'; 

	$txt_header .= '<meta name="robots" content="noindex,nofollow" />';

	echo '<em>'.$txt_legal.'</em>

<div>
<h1 style="text-align:center;font-size:28px;">'.$title.'</h1>

<div id="doc_pad">
'.$text.'
</div>

</div>
'.$txt_legal_botones;
	break;


default:
	if (!$_GET[1])
		$maxsim['redirect'] = '/chat/'.strtolower(PAIS);


}