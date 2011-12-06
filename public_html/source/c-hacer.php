<?php 
include('inc-login.php');

/*

Cosas por hacer:
- Autentificar
- Votar en Elecciones
- Votar en Votaciones
- Repartir votos de confianza.
- Rellenar perfil.
- Votar hilos del foro.
- 

*/


$txt .= '<h1>&iquest;Qu&eacute; puedes hacer en VirtualPol?</h1>

<p>Por orden de importancia.</p>
<ol>


<li>'.($_SESSION['pol']['dnie']=='true'?'<b style="color:blue;">Est&aacute;s autentificado correctamente.</b>':'<b style="color:red;">No est&aacute;s autentificado, puedes hacerlo <a href="https://virtualpol.com/dnie.php" target="_blank">aqu&iacute;</a>.</b>').'<br />
Consiste en identificarte solidamente con <abbr title="DNI electronico y otros 30 certificados">DNIe</abbr>, contribuir&aacute;s a que el sistema sea m&aacute;s legitimo y tu usuario nunca se eliminar&aacute;. Es opcional.<br /><br /></li>';



$has_votado_elecciones = false;
if ($pol['config']['elecciones_estado'] == 'elecciones') {
	$result = mysql_query("SELECT user_ID FROM ".SQL."elecciones WHERE user_ID = '".$pol['user_ID']."' LIMIT 1", $link);
	while($r = mysql_fetch_array($result)) { $has_votado_elecciones = true; }
}
$txt .= '<li>'.($pol['config']['elecciones_estado']=='normal'?'<b style="color:blue;">No hay Elecciones en curso.</b>':($has_votado_elecciones==true?'<b style="color:blue;">Has votado correctamente en las elecciones.</b>':'<b style="color:red;">No has votado en las elecciones Elecciones.</b>')).' <a href="/elecciones/" target="_blank">M&aacute;s informaci&oacute;n</a>.<br />
Las Elecciones son un proceso democratico, periodico y autom&aacute;tico. De su resultado dependen los cargos m&aacute;s relevantes.<br /><br /></li>';




$hay_votaciones = false;
if ($pol['config']['info_consultas'] > 0) {
	$result = mysql_query("SELECT ID, acceso_votar, acceso_cfg_votar, (SELECT user_ID FROM votacion_votos WHERE ref_ID = votacion.ID AND user_ID = '".$pol['user_ID']."' LIMIT 1) AS ha_votado FROM votacion WHERE estado = 'ok'", $link);
	while($r = mysql_fetch_array($result)) { 
		if ((!$r['ha_votado']) AND (nucleo_acceso($r['acceso_votar'], $r['acceso_cfg_votar']))) { $hay_votaciones = true; }
	}
}

$txt .= '<li>'.($hay_votaciones?'<b style="color:red;">Hay votaciones en las que puedes votar.</b>':'<b style="color:blue;">No hay votaciones en curso.</b>').' <a href="/votacion/" target="_blank">Ver votaciones</a>.<br />
Las votaciones pueden ser informativas o vinculantes y sirven para tomar decisiones. Tienen una configuracion espec&iacute;fica, pueden haber varias simultaneas y puedes modificar tu voto mientras esten activas.<br /><br /></li>';




$votos_confianza = 0;
$result = mysql_query("SELECT COUNT(*) AS num FROM votos WHERE tipo = 'confianza' AND emisor_ID = '".$pol['user_ID']."'", $link);
while($r = mysql_fetch_array($result)) { $votos_confianza = $r['num']; }

$txt .= '<li>'.($votos_confianza<VOTO_CONFIANZA_MAX?'<b style="color:red;">Te quedan '.(VOTO_CONFIANZA_MAX-$votos_confianza).' votos de confianza por repartir.</b>':'<b style="color:blue;">Has repartido todos tus votos de confianza.</b>').' <a href="/info/censo/confianza/" target="_blank">Votar confianza</a>.<br />
El voto de confianza es una valoraci&oacute;n (+1 -1) que los usuarios se reparten entre si. No significa que te gusta ese usuario, si no que conf&iacute;as en el. Tienes '.VOTO_CONFIANZA_MAX.' votos de confianza para repartir.<br /><br /></li>';



$perfil = false;
$result = mysql_query("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND (datos = '' OR text = '') LIMIT 1", $link);
while($r = mysql_fetch_array($result)) { $perfil = true; }

$txt .= '<li>'.($perfil?'<b style="color:red;">Tienes cosas por rellenar en tu perfil.</b>':'<b style="color:blue;">Tu perfil est&aacute; bastante completo.</b>').' <a href="/perfil/'.$pol['nick'].'/" target="_blank">Ir a tu perfil</a>.<br />
Cada usuario tiene su perfil personal, en el se muestra informacion de actividad. Se recomienda indicar tus redes sociales y escribir una nota breve.<br /><br /></li>';



$txt .= '<li><b style="color:red;">Manifiesta tu opini&oacute;n votando en el foro.</b> <a href="/foro/" target="_blank">Ir al foro</a>.<br />
Puedes votar (+1 -1) cualquier hilo y mensaje del foro, as&iacute; contribuyes al debate con tu opini&oacute;n.<br /><br /></li>

';







/*
$result = mysql_query("SELECT FROM votacion WHERE ID = '".$_GET['a']."' AND pais = '".PAIS."' LIMIT 1", $link);
while($r = mysql_fetch_array($result)) {
}
*/


$txt .= '</ol>';




//THEME
$txt_title = 'Hacer';
include('theme.php');
?>