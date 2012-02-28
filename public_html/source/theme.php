<?php 
if ((isset($_GET['v'])) OR ($pol['user_ID'] == 1)) { include('theme2.php'); exit; }

if ($_SERVER['HTTP_HOST'] == 'ninguno.'.DOMAIN) { redirect('http://www.'.DOMAIN.'/'); }

if (isset($_GET['noti'])) {
	notificacion('visto', $_GET['noti']);
}

if (!isset($txt)) { 
	header('HTTP/1.1 404 Not Found');
	$txt = '<p style="font-size:24px;">ERROR 404: <b>P&aacute;gina inexistente</b>.</p>';
}

if (isset($_GET['error'])) { 
	header('HTTP/1.1 401 Unauthorized'); 
	$txt = '<p style="font-size:24px;color:red;">ERROR: <b>'.base64_decode($_GET['error']).'</b>.</p>';
}

$kw = '';
if (isset($txt_title)) { 
	$txt_title .= ' | '.PAIS.' | VirtualPol'; 
} else { 	//home
	$txt_title = (isset($pol['config']['pais_des'])?$pol['config']['pais_des'].' de '.PAIS.' '.$kw.'| VirtualPol':PAIS.' '.$kw.'| VirtualPol');
}
if (!isset($txt_description)) { $txt_description = $txt_title.' - '.$kw.PAIS.' | VirtualPol'; }


if (isset($_GET['bg'])) { 
	$body_bg = COLOR_BG.' url(\'http://'.$_GET['bg'].'\') repeat fixed top left';
} else if (isset($pol['config']['bg'])) { 
	$body_bg = COLOR_BG.' url(\''.IMG.'bg/'.$pol['config']['bg'].'\') repeat fixed top left'; 
} else { $body_bg = COLOR_BG; }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$txt_title?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="language" content="es_ES" />
<meta name="description" content="<?=$txt_description?>" />

<link rel="stylesheet" type="text/css" href="<?=IMG?>style.css?v=22" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="<?=IMG?>superfish.js"></script> 

<script type="text/javascript">
var _sf_startpt=(new Date()).getTime()
defcon = <?=$pol['config']['defcon']?>;
IMG = "<?=IMG?>";
window.google_analytics_uacct = "UA-59186-46";
</script>

<style type="text/css">
body { background: <?=$body_bg?>; }
.sf-menu li, .sf-menu a, .sf-menu a:visited  { color:#555; text-shadow:1px 1px 4px #FFF; }
.md { color:#888; float:right; font-size:14px; }
</style>


<?=$txt_header?>
<link rel="shortcut icon" href="/favicon.ico" /> 


</head>

<body class="fullwidth">
<div id="container">


<div id="header_vp">
<a href="http://www.<?=DOMAIN?>/" title="VirtualPol"><img src="<?=IMG?>logo-virtualpol-2.png" border="0" alt="VirtualPol" width="162" height="46" /></a>
</div>

<div id="header">
<div id="header-in">

<?php

// ARREGLAR: este pedazo de trozo de codigo es un lamentable pero histórico trozo primigenio. De las primeras lineas en construirse allá por el 2008. Hay que hacerlo de nuevo.

unset($txt_header);
if ($pol['estado'] == 'ciudadano') { // ciudadano
	$nick_lower = strtolower($pol['nick']);
	
	$elecciones = '';
	if ($pol['config']['elecciones_estado'] == 'normal') {  
		//$elecciones_quedan = duracion(strtotime($pol['config']['elecciones_inicio']) - time());
		$elecciones = ' <a href="/elecciones/">Elecciones en <b style="font-size:18px;"><span class="timer" value="'.strtotime($pol['config']['elecciones_inicio']).'"></span></b></a> |'; 
	} elseif ($pol['config']['elecciones_estado'] == 'elecciones') {  
		$elecciones_quedan = (strtotime($pol['config']['elecciones_inicio']) + $pol['config']['elecciones_duracion']);
		switch ($pol['config']['elecciones']) {
			case 'pres1': $elecciones = ' <a href="/elecciones/" style="color:red;"><b>1&ordf; Vuelta en curso</b>, queda <b style="font-size:18px;">'.timer(($elecciones_quedan - 86400), true).'</b></a> |';  break;
			case 'pres2': $elecciones = ' <a href="/elecciones/" style="color:red;"><b>2&ordf; Vuelta en curso</b>, queda <b style="font-size:18px;">'.timer($elecciones_quedan, true).'</b></a> |'; break;
			case 'parl': $elecciones = ' <a href="/elecciones/" style="color:blue;"><b>Elecciones en curso</b>, queda <b style="font-size:18px;">'.timer($elecciones_quedan, true).'</b></a> |';  break;
		}
	}
	if (isset($pol['cargo'])) { $cargo_icono = ' <img src="'.IMG.'cargos/' . $pol['cargo'] . '.gif" border="0" width="16" height="16" />'; } else { $cargo_icono = ''; }
	$txt_perfil = '<a href="/perfil/' . $pol['nick'] . '/">' . $pol['nick'] . ' ' . $cargo_icono . '</a>'.(ECONOMIA?' | <a href="/pols/"><b>' . pols($pol['pols']) . '</b> ' . MONEDA . '</a>':'').' | <a href="/msg/" title="Mensajes Privados (MP)"><img src="'.IMG.'varios/email.gif" alt="Mensajes" border="0" width="25" height="20" style="margin-bottom:-5px;" /></a> |' . $elecciones . ' <a href="/accion.php?a=logout">Salir</a>';} elseif ($pol['estado'] == 'extranjero') { // extranjero
	$txt_perfil = '<a href="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/perfil/'.$pol['nick'].'/">'.$pol['nick'].'</a> <img src="'.IMG.'cargos/99.gif" style="margin-bottom:-2px;" border="0" width="16" height="16" /> (<b class="extranjero">Extranjero</b>) | <a href="/accion.php?a=logout">Salir</a>';
} elseif ($pol['estado'] == 'turista') { // TURISTA
	$txt_perfil = $pol['nick'] . ' (<b class="turista">Turista</b>) ' . $pol['tiempo_ciudadanizacion'] . ' | ' . boton('Solicitar Ciudadania', REGISTRAR) . ' | <a href="/accion.php?a=logout">Salir</a>';
} elseif ($pol['estado'] == 'kickeado') { // KICKEADO
	$txt_perfil = $pol['nick'] . ' (<b class="expulsado">Kickeado</b>) | <a href="/control/kick/"><b>Ver Kicks</b></a>';
} elseif ($pol['estado'] == 'expulsado') { // EXPULSADO
	$txt_perfil = $pol['nick'] . ' (<b class="expulsado">Expulsado</b>)';
} elseif ((isset($pol['nick'])) AND ($pol['estado'] != '')) { // sin identificar, login OK
	$txt_perfil = '<b>'.$pol['nick'].'</b> (<span class="infog"><b>Turista</b></span>) <span class="azul">' . boton('Solicitar Ciudadania', REGISTRAR) . '</span> | <a href="/accion.php?a=logout">Salir</a>';
} else { // sin identificar, sin login
	$txt_perfil = boton('Crear ciudadano', REGISTRAR.'?p='.PAIS).' | '.boton('Entrar', REGISTRAR.'login.php?r='.base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
}
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>

<td width="50" height="40"><?=notificacion('print')?></td>
<td nowrap="nowrap" width="80" height="40">
<a href="/" title="<?=$pol['config']['pais_des'].' de '.PAIS?>"><img src="<?=IMG?>banderas/<?=PAIS?>_60.gif" width="60" height="40" border="0" /></a>
</td>

<td nowrap="nowrap"><span style="color:#888;font-size:18px;"><?=$pol['config']['pais_des'].' de '.PAIS?></span></td>

<td align="right" valign="middle" nowrap="nowrap"><?=$txt_perfil?></td>

</tr>
</table>



</div>
</div>
<div id="content-wrap" class="clear lcol">
<div class="column">
<div class="column-in">




<?php if (ECONOMIA) { // menu VP ?>


<dl id="menu">
<ul class="sf-menu sf-vertical">
	<li id="menu-1">
		<a href="/">Comunicaci&oacute;n</a>
		<ul style="margin-top:-38px;">
			<li><a href="/chats/">Chats</a></li>
			<li><a href="/foro/"><span style="float:right;">&#9658;</span>Foros</a>
				<ul>
					<li><a href="/foro/ultima-actividad/">&Uacute;ltima actividad</a>
					<?=(isset($pol['user_ID'])?'<li><a href="/foro/mis-respuestas/">Tu actividad</a></li>':'')?>
				</ul>
			</li>
			<?=(isset($pol['user_ID'])?'<li><a href="mumble://'.$pol['nick'].'@mumble.democraciarealya.es/Virtualpol/'.PAIS.'/?version=1.2.0"><span style="float:right;">&#9658;</span>Voz</a><ul><li><a href="/info/voz/">Config. Mumble</a></li></ul></li>':'')?>
			<li><a href="/notas/">Notas</a></li>
			<li><a href="/msg/">Mensajes Privados</a></li>
		</ul>
	</li>

	<li id="menu-2">
		<a href="#">Informaci&oacute;n</a>
			<ul>
				<li><a href="/info/censo/">Censo <span class="md"><?=num($pol['config']['info_censo'])?></span></a></li>

				<li><a href="/doc/">Documentos <span class="md"><?=$pol['config']['info_documentos']?></span></a></li>

				<li><a href="#" style="cursor:default;"><span style="float:right;">&#9658;</span>Registros</a>
					<ul>
						<li><a href="/estadisticas/">Estad&iacute;sticas</a></li>
						<li><a href="http://chartbeat.com/dashboard2/?url=virtualpol.com&k=ecc15496e00f415838f6912422024d06" target="_blank" title="Estadisticas de ChartBeat">Estad&iacute;sticas extra</a></li>
						<!--<li><a href="/geolocalizacion/">GeoLocalizaci&oacute;n</a></li>-->
						<li><a href="/log-eventos/" title="Registro de eventos">Log de eventos</a></li>
					</ul>
				</li>


				<li><a href="/buscar/">Buscador</a></li>

				<li><a href="#" style="cursor:default;"><span style="float:right;">&#9658;</span><b>Sobre VirtualPol</b></a>
					<ul>
						<li><a href="http://www.virtualpol.com/video" target="_blank">Bienvenido (video)</a></li>
						<li><a href="http://www.virtualpol.com/manual" target="_blank">Documentaci&oacute;n</a></li>
						<li><a href="/historia/">Hechos hist&oacute;ricos</a></li>
						<li><a href="http://desarrollo.virtualpol.com/" target="_blank">Blog Desarrollo</a></li>
						<li><a href="/desarrollo" target="_blank">C&oacute;digo fuente</a></li>
						<li><a href="https://www.ohloh.net/p/virtualpol/contributors" target="_blank">Info desarrollo</a></li>
						<li><a href="http://www.virtualpol.com/TOS" target="_blank" title="Condiciones de Uso de VirtualPol">TOS</a></li>
					</ul>
				</li>

				
			</ul>
	</li>

	<li id="menu-3">
		<a href="#">Democracia</a>
		<ul style="margin-top:-74px;">
			<li><a href="/elecciones/"><b>Elecciones</b></a></li>
			<li><a href="/votacion/">Votaciones <span class="md"><?=$pol['config']['info_consultas']?></span></a></li>
			<li><a href="/control/"><span style="float:right;">&#9658;</span><b>Gesti&oacute;n</b></a>
				<ul>
					<li><a href="/control/gobierno/">Gobierno</a></li>
					<li><a href="/doc/boletin-oficial-del-estado/">BOE</a></li>
					<li><a href="/control/kick/">Kicks</a></li>
					<li><a href="/control/expulsiones/">Expulsiones</a></li>
					<li><a href="/examenes/">Ex&aacute;menes</a></li>
					<li><a href="<?=SSL_URL?>dnie.php">Autentificaci&oacute;n</a></li>
					<li><a href="/poderes/">Poderes</a></li><li><a href="/control/judicial/">Sanciones</a></li>
				</ul>
			</li>
			<li><a href="/partidos/">Partidos <span class="md"><?=$pol['config']['info_partidos']?></span></a></li>
			<li><a href="/grupos/">Grupos</a></li>		
			<li><a href="/cargos/">Cargos</a></li>
			<li><a href="/hacer/">&iquest;Qu&eacute; hacer?</a></li>
		</ul>
	</li>

	<li id="menu-4">
		<a href="#">Econom&iacute;a</a>
		<ul>
			<?=($pol['pais']==PAIS?'<li><a href="/pols/"><b>Tus monedas</b></a></li>':'')?>
			<li><a href="/empresas/"><b>Empresas</b></a></li>
			<li><a href="/pols/cuentas/">Cuentas</a></li>
			<li><a href="/subasta/">Subastas</a></li>
			<li><a href="/mapa/">Mapa</a></li>
			<li><a href="/info/economia/">Econom&iacute;a Global</a></li>
		</ul>
	</li>
	
	<li id="menu-5" class="menu-5" style="margin:10px 0 0 1px;"><a href="/votacion/">Votaciones</a></li>

</ul></dd></dl>

<hr style="margin:5px 20px -5px -5px;color:#FF6;" />

<div id="palabras">
<?php 
foreach(explode(";", $pol['config']['palabras']) as $t) {
	$t = explode(":", $t);
	if ($t[0] == $pol['user_ID']) { $edit = ' <a href="/subasta/editar/" class="gris">#</a>'; } else { $edit = ''; }
	if (isset($t[1])) { echo '<a href="http://'.$t[1].'"><b>'.$t[2].'</b></a>'.$edit."<br />\n"; } 
	else { echo $t[2].$edit."<br />\n"; }
}


echo '<a href="/mapa/" class="gris" style="float:right;margin:0 11px 0 0;">Mapa</a><a href="/subasta/" class="gris" style="margin:0 0 0 -3px;">Subasta</a>';

if (!isset($cuadrado_size)) {
	$cuadrado_size = 10;
	include('inc-mapa.php');
	echo '<div style="margin:0 0 0 -4px;">'.$txt_mapa.'</div>';
}


echo '</div>';
?>




<?php } else { // MENU ASAMBLEARIO v3 ?>

<dl id="menu">
<ul class="sf-menu sf-vertical">
	<li id="menu-1">
		<a href="/">Comunicaci&oacute;n</a>
		<ul style="margin-top:-38px;">
			<li><a href="/chats/">Chats</a></li>
			<li><a href="/foro/"><span style="float:right;">&#9658;</span>Foros</a>
				<ul>
					<li><a href="/foro/ultima-actividad/">&Uacute;ltima actividad</a>
					<?=(isset($pol['user_ID'])?'<li><a href="/foro/mis-respuestas/">Tu actividad</a></li>':'')?>
				</ul>
			</li>
			<?=(isset($pol['user_ID'])?'<li><a href="mumble://'.$pol['nick'].'@mumble.democraciarealya.es/Virtualpol/'.PAIS.'/?version=1.2.0"><span style="float:right;">&#9658;</span>Voz</a><ul><li><a href="/info/voz/">Config. Mumble</a></li></ul></li>':'')?>
			<li><a href="/msg/">Mensajes Privados</a></li>
		</ul>
	</li>

	<li id="menu-2">
		<a href="#">Informaci&oacute;n</a>
			<ul>
				<li><a href="/info/censo/">Censo <span class="md"><?=num($pol['config']['info_censo'])?></span></a></li>

				<li><a href="/doc/">Documentos <span class="md"><?=$pol['config']['info_documentos']?></span></a></li>

				<li><a href="#" style="cursor:default;"><span style="float:right;">&#9658;</span>Registros</a>
					<ul>
						<li><a href="/estadisticas/">Estad&iacute;sticas</a></li>
						<li><a href="http://chartbeat.com/dashboard2/?url=virtualpol.com&k=ecc15496e00f415838f6912422024d06" target="_blank" title="Estadisticas de ChartBeat">Estad&iacute;sticas extra</a></li>
						<li><a href="/log-eventos/">Log de eventos</a></li>
					</ul>
				</li>


				<li><a href="/buscar/">Buscar</a></li>

				<li><a href="#" style="cursor:default;"><span style="float:right;">&#9658;</span><b>Sobre VirtualPol</b></a>
					<ul>
						<li><a href="http://www.virtualpol.com/video" target="_blank">Bienvenido (video)</a></li>
						<li><a href="http://www.virtualpol.com/manual" target="_blank">Documentaci&oacute;n</a></li>
						<li><a href="http://www.virtualpol.com/desarrollo" target="_blank">C&oacute;digo fuente</a></li>
						<li title="Estad&iacute;sticas de desarrollo del c&oacute;digo fuente"><a href="https://www.ohloh.net/p/virtualpol/contributors" target="_blank">Info desarrollo</a></li>
						<li title="Condiciones de Uso de VirtualPol"><a href="http://www.virtualpol.com/TOS" target="_blank">TOS</a></li>
					</ul>
				</li>
			</ul>
	</li>

	<li id="menu-3">
		<a href="#">Democracia</a>
		<ul style="margin-top:-74px;">
			<li><a href="/elecciones/"><b>Elecciones</b></a></li>
			<li><a href="/votacion/">Votaciones <span class="md"><?=$pol['config']['info_consultas']?></span></a></li>
			<li><a href="/control/"><span style="float:right;">&#9658;</span><b>Gesti&oacute;n</b></a>
				<ul>
					<li title="Control de Gobierno"><a href="/control/gobierno/">Control</a></li>
					<li title="Bloqueos temporales de usuarios por moderaci&oacute;n"><a href="/control/kick/">Kicks</a></li>
					<li><a href="/examenes/">Ex&aacute;menes</a></li>
					<li><a href="<?=SSL_URL?>dnie.php">Autentificaci&oacute;n</a></li>
				</ul>
			</li>
			<li><a href="/grupos/">Grupos</a></li>
			<li><a href="/cargos/">Cargos</a></li>
			<li><a href="/hacer/">&iquest;Qu&eacute; hacer?</a></li>
		</ul>
	</li>
	
	<!--<li id="menu-5" class="menu-5" style="margin-top:12px;"><a href="/foro/comunicados/">Comunicados</a></li>-->
	<li id="menu-5" class="menu-5" style="margin-top:12px;"><a href="/foro/">Foro</a></li>
	<li id="menu-5" class="menu-5"><a href="/votacion/">Votaciones</a></li>
</ul></dd></dl>

<?php

echo '<div id="palabras">';
foreach(explode(';', $pol['config']['palabras']) as $t) {
	$t = explode(':', $t);
	echo ($t[1]!=''?'<a href="http://'.$t[1].'"><b>'.$t[2].'</b></a>':$t[2]).($pol['user_ID']==$t[0]?' <a href="/subasta/editar/" class="gris">#</a>':'')."<br />\n";
}
echo '</div>

<div style="margin:12px 0 0 0;">
<a href="https://www.facebook.com/pages/Asamblea-Virtual/216054178475524"><img src="'.IMG.'ico/2_32.png" alt="Facebook" width="32" height="32" /></a> 

<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://15m.virtualpol.com/" data-text="Participa en la Asamblea Virtual del 15M! http://www.virtualpol.com/video" data-lang="es" data-size="large" data-related="AsambleaVirtuaI" data-count="none" data-hashtags="AsambleaVirtual">Twittear</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

</div>';
}
?>
</div>
</div>
<div class="content">
<div class="content-in">


<?=$txt.$kw?>

</div>
</div>
</div>
<div class="clear"></div>
<div id="footer">
<div id="footer-in">



<div>
<span style="float:right;font-size:14px;">
<?php
unset($txt);
echo ($pol['user_ID']==1?round((microtime(true)-TIME_START)*1000).'ms | ':'');
?>
<a href="http://www.virtualpol.com/TOS" target="_blank"><abbr title="Condiciones de Uso">TOS</abbr></a> | 
<a href="http://www.virtualpol.com/desarrollo" title="Código fuente, software libre">Desarrollo</a> | 
<a href="http://www.virtualpol.com/documentacion" target="_blank">Documentación</a> &nbsp; &nbsp; 
2008-2012 <b><a href="http://www.virtualpol.com/" style="font-size:16px;">VirtualPol</a></b> <sub>Beta</sub></span>
<?php
echo '<b>'.PAIS.'</b>';
if (!ASAMBLEA) {
	echo ' <span style="font-size:11px;"><abbr title="CONdicion de DEFensa">DEFCON <b>'.$pol['config']['defcon'].'</b></abbr></span> <span class="amarillo" id="pols_frase"><b>'.$pol['config']['pols_frase'].'</b>';
	if ($pol['config']['pols_fraseedit'] == $pol['user_ID']) { echo ' <a href="/subasta/editar/" class="gris">#</a>'; }
}
?>
</span>

</div>
</div>
</div>
</div>

<div id="pnick" class="azul" style="display:none;opacity:0.9;"></div>


<script type="text/javascript" src="<?=IMG?>scripts.js?v=23"></script>
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

<script type="text/javascript">
var _sf_async_config={uid:26055,domain:"virtualpol.com"};
(function(){
  function loadChartbeat() {
    window._sf_endpt=(new Date()).getTime();
    var e = document.createElement('script');
    e.setAttribute('language', 'javascript');
    e.setAttribute('type', 'text/javascript');
    e.setAttribute('src',
       (("https:" == document.location.protocol) ? "https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" : "http://static.chartbeat.com/") +
       "js/chartbeat.js");
    document.body.appendChild(e);
  }
  var oldonload = window.onload;
  window.onload = (typeof window.onload != 'function') ?
     loadChartbeat : function() { oldonload(); loadChartbeat(); };
})();

</script>

</body>
</html>
<?php if ($link) { mysql_close($link); } ?>