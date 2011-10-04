<?php 

// THEME VERSION MOVIL de paises (desarrollo incompleto y no publicado)
// RECORDAR cambiar menus
// Se tiene que crear una pagina de menu

if ($link) { mysql_close($link); }
if (!$txt) { header('HTTP/1.1 301 Moved Permanently'); header('Location: http://www.virtualpol.com/'); exit; }
if ($_SERVER['HTTP_HOST'] == 'ninguno.virtualpol.com') { header('Location: http://www.virtualpol.com/'); exit; }
$kw = '';

if ($txt_title) { 
	$txt_title .= ' | '.PAIS.' | VirtualPol'; 
} else { 
	//home
	
	if (!$pol['user_ID']) {
		if (PAIS == 'Hispania') { $kw = 'Simulador Politico Espa&ntilde;ol '; }
	}
	$txt_title =  PAIS.' '.$kw.'| Comunidad VirtualPol';
}
if (!$txt_description) { $txt_description = $txt_title . ' - ' . $kw . PAIS.' | VirtualPol'; }

$menu_ID = 0;



$body_bg = COLOR_BG;


ob_start('ob_gzhandler');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$txt_title?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="language" content="es_ES" />
<meta name="description" content="<?=$txt_description?>" />
<link href="/img/style.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="/favicon.ico" />

<script type="text/javascript">
menu_ID = <?=$menu_ID?>;
defcon = <?=$pol['config']['defcon']?>;
window.google_analytics_uacct = "UA-59186-46";
</script>
<script type="text/javascript" src="/img/scripts.js"></script>
<style type="text/css">
body { background: <?=$body_bg?>; }
div#footer, div.column, div.content, div#header {
border: 1px solid <?=COLOR_BG2?>;
border-width: 0 2px 2px 0;
}
</style>


<?=$txt_header?>
</head>

<body class="fullwidth">
<div id="container">
<div id="header">
<div id="header-in">

<?php
unset($txt_header);
if ($pol['msg'] > 0) { $num_msg = '<b style="font-size:18px;">' . $pol['msg'] . '</b>'; } else { $num_msg = $pol['msg']; }
if (($pol['estado'] == 'ciudadano') OR ($pol['estado'] == 'desarrollador')) { // ciudadano
	$nick_lower = strtolower($pol['nick']);
	
	if ($pol['config']['elecciones_estado'] == 'normal') {  
		$elecciones_quedan = duracion(strtotime($pol['config']['elecciones_inicio']) - time());
		$elecciones = ' <a href="/elecciones/">Elecciones en <b style="font-size:18px;">' . $elecciones_quedan . '</b></a> |'; 
	} elseif ($pol['config']['elecciones_estado'] == 'elecciones') {  
		$elecciones_quedan = (strtotime($pol['config']['elecciones_inicio']) + $pol['config']['elecciones_duracion']) - time();
		switch ($pol['config']['elecciones']) {
			case 'pres1': $elecciones = ' <a href="/elecciones/" style="color:red;"><b>1&ordf; Vuelta en curso</b>, queda <b style="font-size:18px;">' .  duracion($elecciones_quedan - 86400) . '</b></a> |';  break;
			case 'pres2': $elecciones = ' <a href="/elecciones/" style="color:red;"><b>2&ordf; Vuelta en curso</b>, queda <b style="font-size:18px;">' .  duracion($elecciones_quedan) . '</b></a> |'; break;
			case 'parl': $elecciones = ' <a href="/elecciones/" style="color:blue;"><b>Elecciones en curso</b>, queda <b style="font-size:18px;">' .  duracion($elecciones_quedan) . '</b></a> |';  break;
		}
	}
	if ($pol['cargo']) { $cargo_icono = ' <img src="/img/cargos/' . $pol['cargo'] . '.gif" border="0" />'; } else { $cargo_icono = ''; }
	$txt_perfil = '<a href="/perfil/' . $pol['nick'] . '/">' . $pol['nick'] . ' ' . $cargo_icono . '</a> | <a href="/pols/"><b>' . pols($pol['pols']) . '</b> ' . MONEDA . '</a> | <a href="/msg/" title="Mensajes">(' . $num_msg . ') <img src="/img/varios/email.gif" alt="Mensajes" border="0" style="margin-bottom:-5px;" /></a> | <a href="/foro/mis-respuestas/" title="Respuestas a tus mensajes en el foro">Resp</a> |' . $elecciones . ' <a href="/accion.php?a=logout">Salir</a>';
} elseif ($pol['estado'] == 'extranjero') { // extranjero
	$txt_perfil = '<a href="http://'.strtolower($pol['pais']).'.virtualpol.com/perfil/'.$pol['nick'].'/">'.$pol['nick'].'</a> <img src="/img/cargos/99.gif" style="margin-bottom:-2px;" border="0" /> (<b class="extranjero">Extranjero</b>) |  <a href="http://'.strtolower($pol['pais']).'.virtualpol.com/msg/" title="Mensajes">(' . $num_msg . ') <img src="/img/varios/email.gif" alt="Mensajes" border="0" style="margin-bottom:-5px;" /></a> | <a href="/accion.php?a=logout">Salir</a>';
} elseif ($pol['estado'] == 'turista') { // TURISTA
	$txt_perfil = $pol['nick'] . ' (<b class="turista">Turista</b>) ' . $pol['tiempo_ciudadanizacion'] . ' | <a href="/accion.php?a=logout">Salir</a>';
} elseif ($pol['estado'] == 'kickeado') { // KICKEADO
	$txt_perfil = $pol['nick'] . ' (<b class="expulsado">Kickeado</b>) | <a href="/control/kick/"><b>Ver Kicks</b></a> | <a href="http://'.strtolower($pol['pais']).'.virtualpol.com/msg/" title="Mensajes">(' . $num_msg . ') <img src="/img/varios/email.gif" alt="Mensajes" border="0" style="margin-bottom:-5px;" /></a>';
} elseif ($pol['estado'] == 'expulsado') { // EXPULSADO
	$txt_perfil = $pol['nick'] . ' (<b class="expulsado">Expulsado</b>)';
} elseif ($pol['nick']) { // sin identificar, login OK
	$txt_perfil = '<b>' . $pol['nick'] . '</b> (<span class="infog"><b>Turista</b></span>) <span class="azul">' . boton('Solicitar Ciudadania', REGISTRAR) . '</span> | <a href="/accion.php?a=logout">Salir</a>';
} else { // sin identificar, sin login
	$txt_perfil = '
<script type="text/javascript">
function vlgn (objeto) { if ((objeto.value == "Usuario") || (objeto.value == "123")) { objeto.value = ""; } }
</script>
<span style="float:right;margin-top:-3px;">
<form action="'.REGISTRAR.'login.php?a=login" method="post">
<input name="url" value="' . base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) . '" type="hidden" />
<input name="user" value="Usuario" size="8" maxlength="20" onfocus="vlgn(this)" type="text" />
<input name="pass" value="123" size="10" maxlength="20" onfocus="vlgn(this)" type="password" />
<input type="submit" value="Entrar" /></form>
</span>
<span class="azul"><a href="'.REGISTRAR.'"><b>Crear Ciudadano!</b></a></span> &nbsp; <a href="/info/recuperar-login/"><acronym title="Recuperar contrase&ntilde;a">?</acronym></a> &nbsp;';



	// adsense
	$ref = explode("/", $_SERVER['HTTP_REFERER']);
	if ((false == true) && ($ref[2] != 'www.virtualpol.com') && ($ref[2] != 'meneame.net') && (!isset($adsense_exclude)) && ($_SERVER['REQUEST_URI'] != '/doc/empezar-en-pol/')) {
		$txt = '
<div style="float:right;"><a href="/doc/empezar-en-pol/">Ayuda? FAQ!</a></div>
<div><table border="0" cellspacing="0" cellpadding="0" style="margin:-5px 0 4px 20px;"><tr><td>
<script type="text/javascript"><!--
google_ad_client = "pub-6223447893261020";
google_ad_slot = "7807319072";
google_ad_width = 250;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

</td><td>

<script type="text/javascript"><!--
google_ad_client = "pub-6223447893261020";
google_ad_slot = "7807319072";
google_ad_width = 250;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</td></tr></table></div>' . $txt;
	}

}
?>
<div style="margin:10px 0 2px 0;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>

<td><span id="homelogo"><a href="/img/banderas/<?=PAIS?>_500.gif"><img src="/img/banderas/<?=PAIS?>_60.gif" alt="Bandera de <?=PAIS?>, Simulador Politico en Espa&ntilde;ol juego online" border="0" style="float:left;margin:-9px 8px 0 -8px;" /></a> <a href="http://<?=HOST?>/" class="gris"><b><?=PAIS?></b></a> | <a href="http://www.virtualpol.com/" style="font-size:14px;" title="Simulador Pol&iacute;tico">Comunidad VirtualPol</a></span></td>

<td align="right"><?=$txt_perfil?></td>

<td align="right" width="180"><form action="/buscar/" id="cse-search-box">
<input type="hidden" name="cx" value="000141954329957006250:h-_yuvq_rwk" />
<input type="hidden" name="cof" value="FORID:9" />
<input type="hidden" name="ie" value="UTF-8" />
<input type="text" name="q" size="4" />
<input type="submit" name="sa" value="Buscar" />
</form></td>

</tr>
</table>
</div>


</div>
</div>


<div class="content">
<div class="content-in">

<?=$txt?>

</div>
</div>
</div>
<div class="clear"></div>
<div id="footer">
<div id="footer-in">



<div>
<span style="float:right;">
<?php
unset($txt);
if ($pol['estado'] == 'desarrollador') {
	$mtime = explode(' ', microtime()); 
	$tiempofinal = $mtime[1] + $mtime[0]; 
	$tiempototal = number_format($tiempofinal - $tiempoinicial, 3); 
	echo ' | ' . $tiempototal . 's ' . floor(memory_get_usage() / 1024) . 'kb';
} elseif (!$pol['user_ID']) {
	$teoriza_b = array(
'gonzo.teoriza.com|GONZO', 
'mia.teoriza.com|Mia', 
'ocio.teoriza.com|Ocio',
'chat.teoriza.com|Chat',
'intimidades.teoriza.com|Intimidades',
'aziroet.com|Crear Blog',
);
	$teoriza_b = explode("|", $teoriza_b[array_rand($teoriza_b)]);
	echo '+<a href="http://' . $teoriza_b[0] . '/">' . $teoriza_b[1] . '</a>';
}
?></span>
<b><?=PAIS?></b> <span style="font-size:11px;">DEFCON <b><?=$pol['config']['defcon']?></b></span>

<span class="amarillo" id="pols_frase"><b><?=$pol['config']['pols_frase']?></b>
<?php if ($pol['config']['pols_fraseedit'] == $pol['user_ID']) { echo ' <a href="/subasta/editar/" class="gris">#</a>'; } ?></span>
</div>
</div>
</div>

<center style="margin:15px 0 0 0;"><span class="azul" style="padding:8px;color:grey;">
<a href="http://www.virtualpol.com/" title="Simulador Politico">Comunidad <b>VirtualPOL</b></a> | <a href="http://desarrollo.virtualpol.com/">Blog <b>Desarrollo</b></a> | Paises: <?php $n = 0; foreach ($vp['paises'] AS $pais) { if ($n++ != 0) { echo ' &amp; '; } echo '<a href="http://'.strtolower($pais).'.virtualpol.com/">'.$pais.'</a>'; } ?> | Creado por: <a href="http://gonzo.teoriza.com/">GONZO</a>, <a href="http://mia.teoriza.com/">Mia</a> y <a href="http://www.teoriza.com/">Blogs Teoriza</a> <?=$kw?>
</span></center>

</div>

<div id="pnick" class="azul" style="display:none;"></div>

<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-59186-46']);
_gaq.push(['_setDomainName', '.virtualpol.com']);
_gaq.push(['_trackPageview']);
(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>

</body>
</html>
<?php 
ob_end_flush();
?>