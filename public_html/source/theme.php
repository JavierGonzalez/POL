<?php /******* THEME *******/

// Errores y redirecciones.
if ($_SERVER['HTTP_HOST'] == 'ninguno.'.DOMAIN) { redirect('http://www.'.DOMAIN); }
if (isset($_GET['noti'])) { notificacion('visto', $_GET['noti']); }
if (!isset($txt)) { header('HTTP/1.1 404 Not Found'); $txt = '<h1 style="font-weight:normal;">ERROR 404: <b>Página inexistente</b></h1>'; }
if (isset($_GET['error'])) { header('HTTP/1.1 401 Unauthorized'); $txt = '<h1 style="font-weight:normal;color:red;">ERROR: <b>'.base64_decode($_GET['error']).'</b></h1>'; }

if (isset($txt_title)) { $txt_title .= ' | '.PAIS.' | VirtualPol'; }
else { $txt_title = (isset($pol['config']['pais_des'])?$pol['config']['pais_des'].' de '.PAIS.' '.$kw.'| VirtualPol':PAIS.' '.$kw.'| VirtualPol'); }

// Genera info de elecciones.
if ($pol['config']['elecciones_estado'] == 'normal') {
	$txt_elec_time = timer(strtotime($pol['config']['elecciones_inicio']), true); 
	$txt_elec = ''; 
} elseif ($pol['config']['elecciones_estado'] == 'elecciones') {  
	$elec_quedan = (strtotime($pol['config']['elecciones_inicio']) + $pol['config']['elecciones_duracion']);
	switch ($pol['config']['elecciones']) {
		case 'pres1': $txt_elec_time = timer(($elec_quedan - 86400), true); $txt_elec = ' <a href="/elecciones" style="color:red;font-weight:normal;">1&ordf; Vuelta en curso, queda <b>'.$txt_elec_time.'</b></a> |';  break;
		case 'pres2': $txt_elec_time = timer($elec_quedan, true); $txt_elec = ' <a href="/elecciones" style="color:red;font-weight:normal;">2&ordf; Vuelta en curso, queda <b>'.$txt_elec_time.'</b></a> |'; break;
		case 'parl': $txt_elec_time = timer($elec_quedan, true); $txt_elec = ' <a href="/elecciones" style="font-weight:normal;">Elecciones'.(ASAMBLEA?' a Coordinador':'').', queda <b>'.$txt_elec_time.'</b></a> |';  break;
	}
}

// Tapiz de fondo (1400x100)
if (isset($_GET['bg'])) { 
	$body_bg = 'url(\'http://'.$_GET['bg'].'\')';
} else if (isset($pol['config']['bg'])) { 
	$body_bg = 'url(\''.IMG.'bg/'.$pol['config']['bg'].'\')'; 
} else { $body_bg = 'none'; }

?>
<!DOCTYPE html>
<html>
<head>
<title><?=$txt_title?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="language" content="es_ES" />
<meta name="description" content="<?=(isset($txt_description)?$txt_description:$txt_title.' - '.$kw.PAIS.' | VirtualPol')?>" />
<link rel="shortcut icon" href="/favicon.ico" />

<link rel="stylesheet" type="text/css" href="<?=IMG?>style_all.css" media="all" />
<style type="text/css">
#header { background:#FFF <?=$body_bg?> repeat scroll top left; }
</style>

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="<?=IMG?>scripts_all.js"></script>
<script type="text/javascript">
var _sf_startpt=(new Date()).getTime();
IMG = '<?=IMG?>';
p_scroll = true;
</script>

<?=$txt_header?>
</head>
<body>

<div id="content-left">
	
	<a href="http://www.virtualpol.com"><img src="<?=IMG?>media/logo-virtualpol-1_200.gif" width="200" height="60" alt="VirtualPol" /></a>
	
	<ul class="menu vertical">

	<li id="menu-comu"<?=($txt_menu=='comu'?' class="menu-sel"':'')?>><a href="/">Comunicación</a>
		<ul>
			<li><a href="/chats">Chats</a></li>
			<li><a href="/foro"><b>Foros</b></a>
				<ul>
					<li><a href="/foro/ultima-actividad">Última actividad</a>
					<?=(isset($pol['user_ID'])?'<li><a href="/foro/mis-respuestas">Tu actividad</a></li>':'')?>
				</ul>
			</li>
			<?=(isset($pol['user_ID'])?'<li><a href="mumble://'.$pol['nick'].'@mumble.democraciarealya.es/Virtualpol/'.PAIS.'/?version=1.2.0">Voz</a><ul><li><a href="/info/voz">Configurar <em>Mumble</em></a></li></ul></li>':'')?>
			<li><a href="/msg">Mensajes Privados</a></li>
			<li><a href="#" style="cursor:default;">Redes sociales</a>
				<ul>
					<li><a href="<?=(ASAMBLEA?'https://twitter.com/#!/AsambleaVirtuaI':'https://twitter.com/#!/VirtualPol')?>">Twitter</a>
					<?=(ASAMBLEA?'<li><a href="https://www.facebook.com/AsambleaVirtual">Facebook</a>':'')?>
					<li><a href="/info/seguir">Seguir...</a>
				</ul>
			</li>
		</ul>
	</li>

	<li id="menu-info"<?=($txt_menu=='info'?' class="menu-sel"':'')?>><a href="/buscar">Información</a>
		<ul>
			<li><a href="/info/censo">Censo<span class="md"><?=num($pol['config']['info_censo'])?></span></a></li>
			<li><a href="/doc"><b>Documentos</b><span class="md"><?=$pol['config']['info_documentos']?></span></a></li>
			<li><a href="#" style="cursor:default;">Estadísticas</a>
				<ul>
					<li><a href="/estadisticas">Estadísticas</a></li>
					<!--<li><a href="http://chartbeat.com/dashboard2/?url=virtualpol.com&k=ecc15496e00f415838f6912422024d06" target="_blank" title="Estadísticas de ChartBeat">Estadísticas web</a></li>-->
					<li><a href="/log-eventos">Log de acciones</a></li>
				</ul>
			</li>
			<li><a href="/buscar">Buscar</a></li>
			<li><a href="#" style="cursor:default;">Sobre VirtualPol...</a>
				<ul>
					<li><a href="http://www.virtualpol.com/video" target="_blank">Vídeo de bienvenida</a></li>
					<li><a href="http://www.virtualpol.com/manual" target="_blank">Documentación</a></li>
					<li><a href="http://www.virtualpol.com/TOS" target="_blank">Condiciones de Uso</a></li>
					<li><a href="http://www.virtualpol.com/desarrollo" target="_blank">Desarrollo</a></li>
					<li><a href="https://virtualpol.com/donaciones" target="_blank">Donaciones</a></li>
				</ul>
			</li>
		</ul>
	</li>

	<li id="menu-demo"<?=($txt_menu=='demo'?' class="menu-sel"':'')?>><a href="/votacion">Democracia</a>
		<ul>
			<li><a href="/elecciones">Elecciones<span class="md"><?=$txt_elec_time?></span></a></li>
			<li><a href="/votacion"><b>Votaciones</b><span class="md"><?=$pol['config']['info_consultas']?></span></a></li>
			<li><a href="/control">Gestión</a>
				<ul>
					<li><a href="/control/gobierno">Gobierno</a></li>
					<li title="Bloqueos de moderación"><a href="/control/kick">Kicks</a></li>
					<li><a href="/examenes">Exámenes</a></li>
					<li><a href="<?=SSL_URL?>dnie.php">Autentificación</a></li>
				</ul>
			</li>
			<?=(ASAMBLEA?'':'<li><a href="/partidos">Partidos <span class="md">'.$pol['config']['info_partidos'].'</span></a></li>')?>
			<li><a href="/grupos">Grupos</a></li>
			<li><a href="/cargos">Cargos</a></li>
		</ul>
	</li>

<?php if (ECONOMIA) { ?>
	<li id="menu-econ"<?=($txt_menu=='econ'?' class="menu-sel"':'')?>><a href="/pols">Economía</a>
		<ul>
			<?=($pol['pais']==PAIS?'<li><a href="/pols"><b>Tus monedas</b></a></li>':'')?>
			<li><a href="/pols/cuentas">Cuentas</a></li>
			<li><a href="/empresas"><b>Empresas</b></a></li>
			<li><a href="/subasta">Subastas</a></li>
			<li><a href="/mapa">Mapa</a></li>
			<li><a href="/info/economia">Economía Global</a></li>
		</ul>
	</li>
<?php } echo notificacion('print'); ?>

	</ul>


	<div id="menu-next">

		<p id="palabras">
<?php
foreach(explode(';', $pol['config']['palabras']) as $t) {
	$t = explode(':', $t);
	echo ($t[1]!=''?'<a href="http://'.$t[1].'"><b>'.$t[2].'</b></a>':$t[2]).($pol['user_ID']==$t[0]||nucleo_acceso($vp['acceso']['control_gobierno'])?' <a href="/subasta/editar" style="float:right;color:#CCC;">#</a>':'').'<br />';
}

echo '</p>';

if (ECONOMIA) {
	echo '<a href="/mapa" class="gris" style="float:right;">Mapa</a><a href="/subasta" class="gris">Subasta</a>';
	if (!isset($cuadrado_size)) { $cuadrado_size = 12; }
	include('inc-mapa.php');
	echo '<div style="margin:4px 0 0 6px;">'.$txt_mapa.'</div>';
}
?>

	</div>
</div>




<div id="content-right">

	<div id="header">

		<div id="header-logo">
			<?=(ASAMBLEA?'':'<a href="/"><img src="'.IMG.'banderas/'.PAIS.'_60.gif" height="50" border="0" /></a>')?>
			<span class="htxt" id="header-logo-p"><?=$pol['config']['pais_des'].', '.PAIS?></span>
		</div>

		<div id="header-right">
<?php
unset($txt_header);
if (isset($pol['user_ID'])) {
	echo '<span class="htxt"><b><a href="/perfil/'.$pol['nick'].'">'.$pol['nick'].($pol['cargo']!=0&&$pol['cargo']!=99?' <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" width="16" height="16" />':'').'</a>'.($pol['estado']!='ciudadano'?' (<b class="'.$pol['estado'].'">'.ucfirst($pol['estado']).'</b>)':'').(ECONOMIA?' | <a href="/pols"><b>'.pols($pol['pols']).'</b> '.MONEDA.'</a>':'').' |'.$txt_elec.' <a href="/accion.php?a=logout">Salir</a></b></span>';
} else {
	echo boton('Crear ciudadano', REGISTRAR.'?p='.PAIS, false, 'large blue').' &nbsp; '.boton('Entrar', REGISTRAR.'login.php?r='.base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
}
?>
		</div>

		<div id="header-breadcrumbs">
			<ul class="breadcrumbs alt1">
				<li><a href="/"><img src="<?=IMG?>ico/home.png" width="18" height="18" alt="home" style="margin:-4px;" /></a></li>
				<?php foreach ($txt_nav AS $u => $a) { echo '<li><a href="'.(!is_numeric($u)?$u:'#').'">'.$a.'</a></li>'; } ?>
			</ul>
		</div>

		<div id="header-tab">
			<ul class="ttabs right">
				<?php foreach ($txt_tab AS $u => $a) { echo '<li'.(!is_numeric($u)&&$_SERVER['REQUEST_URI']==$u?' class="current"':'').'><a href="'.(!is_numeric($u)?$u:'#').'">'.$a.'</a></li>'; } ?>
			</ul>
		</div>

	</div>



	<div id="content">
		<?=$txt?>
	</div>



	<div id="footer">

		<div id="footer-right">
			<p>VirtualPol, la primera <b>Red Social Democrática</b> <?=boton('Donar', 'https://virtualpol.com/donaciones', false, 'small pill orange')?></p>
			<p>
			<a target="_blank" href="http://www.virtualpol.com/video">Vídeo</a> | <a target="_blank" href="http://www.virtualpol.com/documentacion">Documentación / Ayuda</a><br />
			<a target="_blank" href="http://www.virtualpol.com/TOS">Condiciones de Uso</a> | <a target="_blank" href="http://www.virtualpol.com/desarrollo">Desarrollo / Código fuente</a><br />
<?php
unset($txt);
if (!isset($pol['user_ID'])) { echo '<a target="_blank" href="http://gonzo.teoriza.com" title="GONZO">Javier González</a> (<a target="_blank" href="http://www.teoriza.com" title="Blogs">Teoriza</a>, <a target="_blank" href="http://www.eventuis.com" title="Eventos">eventuis</a>, <a target="_blank" href="http://www.perfectcine.com" title="Cine">PerfectCine</a>)<br />'; }
if ($pol['user_ID'] == 1) { echo num((microtime(true)-TIME_START)*1000).'ms '.num(memory_get_usage()/1000).'kb | '; }
?>
				<span title="Época antigua en IRC" style="color:#BBB;">2004-</span>2008-2012
			</p>
		</div>
		
		<div id="footer-left">
<?php
echo '<table border="0"><tr><td height="30" nowrap="nowrap"><b>'.PAIS.', '.$pol['config']['pais_des'].'</b></td>';

if (ASAMBLEA) {
	echo '<td><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://'.$_SERVER['HTTP_HOST'].'" data-text="Participa en Asamblea Virtual 15M! http://www.virtualpol.com/video" data-lang="es" data-size="large" data-related="AsambleaVirtuaI" data-count="none" data-hashtags="AsambleaVirtual">Twittear</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></td>

<td><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>
<div class="fb-like" data-href="http://'.$_SERVER['HTTP_HOST'].'" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="verdana"></div></td>
';

} else {
	echo '<td><a href="https://twitter.com/share" class="twitter-share-button" data-url="http://'.$_SERVER['HTTP_HOST'].'" data-text="VirtualPol, la primera red democrática virtual http://www.virtualpol.com/video" data-lang="es" data-related="VirtualPol" data-count="none" data-hashtags="VirtualPol">Twittear</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></td>';
}

echo '</tr></table>';


if ((isset($pol['user_ID'])) AND ($pol['config']['palabra_gob'] != ':') AND ($pol['config']['palabra_gob'] != '')) {
	echo '<div class="azul"><b><a href="http://'.explodear(':', $pol['config']['palabra_gob'], 1).'">'.explodear(':', $pol['config']['palabra_gob'], 0).'</a></b></div><br />';
}

if (!ASAMBLEA) {
	echo '<div class="amarillo"><b>'.$pol['config']['pols_frase'].'</b></div>';
	if ($pol['config']['pols_fraseedit'] == $pol['user_ID']) { echo ' <a href="/subasta/editar" class="gris">#</a>'; }
}
?>	
		</div>
	</div>
<div>

<div id="pnick" class="azul" style="display:none;"></div>

<script type="text/javascript">
/* GA */
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-59186-46']);
_gaq.push(['_setDomainName', '.virtualpol.com']);
_gaq.push(['_trackPageview']);
(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

/* CHARTBEAT */
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