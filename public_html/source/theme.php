<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

// Errores y redirecciones.
if ($_SERVER['HTTP_HOST'] == 'ninguno.'.DOMAIN) { redirect('http://www.'.DOMAIN); }
if (isset($_GET['noti'])) { notificacion('visto', $_GET['noti']); }
if (!isset($txt)) { $txt_nav = array('Error'); header('HTTP/1.1 404 Not Found'); $txt = '<h1 style="font-weight:normal;">ERROR 404: <b>'._('Página inexistente').'</b></h1>'; }
if (isset($_GET['error'])) { header('HTTP/1.1 401 Unauthorized'); $txt = '<h1 style="font-weight:normal;color:red;">ERROR: <b>'.escape(base64_decode($_GET['error'])).'</b></h1>'; }
if (!isset($pol['config']['pais_des'])) { $pol['config']['pais_des'] = _('Plataforma cerrada'); }
if (isset($txt_title)) { $txt_title .= ' | '.PAIS.' | VirtualPol'; }
else { $txt_title = (isset($pol['config']['pais_des'])?$pol['config']['pais_des'].' '._('de').' '.PAIS.' '.$kw.'| VirtualPol':PAIS.' '.$kw.'| VirtualPol'); }


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
<meta name="language" content="<?=(isset($vp['lang'])?$vp['lang']:'es_ES')?>" />
<meta name="description" content="<?=(isset($txt_description)?$txt_description:$txt_title.' - '.$kw.PAIS.' | VirtualPol')?>" />

<link rel="stylesheet" type="text/css" href="<?=IMG?>style_all.css" media="all" />
<style type="text/css">
#header { background:#FFF <?=$body_bg?> repeat scroll top left; }
</style>

<link rel="shortcut icon" href="/favicon.ico" />

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=IMG?>scripts_all.js?v=14"></script>
<script type="text/javascript">
var _sf_startpt=(new Date()).getTime();
IMG = '<?=IMG?>';
p_scroll = false;
_ = {<?php foreach (array('meses','días','horas','minutos','min','seg','Pocos segundos','En','Hace') AS $d) { echo '"'.$d.'":"'._($d).'",'; } ?>};
</script>

<?=$txt_header?>
</head>


<body>

<div id="content-left">
	
	<a href="http://www.virtualpol.com"><img src="<?=IMG?>logo/vp2.png" width="200" height="60" alt="VirtualPol" /></a>
	
	<ul class="menu vertical">

	<li id="menu-comu"<?=($txt_menu=='comu'?' class="menu-sel"':'')?>><a href="/"><?=_('Comunicación')?></a>
		<ul>
			<li><a href="/chats">Chats</a></li>
			<li><a href="/foro"><b><?=_('Foros')?></b></a>
				<ul>
					<li><a href="/foro/ultima-actividad"><?=_('Última actividad')?></a>
					<?=(isset($pol['user_ID'])?'<li><a href="/foro/mis-respuestas">'._('Tu actividad').'</a></li>':'')?>
				</ul>
			</li>
			<li><a href="/msg"><?=_('Mensajes privados')?></a></li>
			<?=(isset($pol['user_ID'])?'<li><a href="mumble://'.$pol['nick'].'@cryptious.net/Other/?version=1.2.0">'._('Voz').'</a><ul><li><a href="/info/voz">'._('Configurar').' <em>Mumble</em></a></li></ul></li>':'')?>
			<li><a href="#" style="cursor:default;"><?=_('Redes sociales')?></a>
				<ul>
					<li><a href="<?=(ASAMBLEA?'https://twitter.com/#!/AsambleaVirtuaI':'https://twitter.com/#!/VirtualPol')?>">Twitter</a></li>
					<?=(ASAMBLEA?'<li><a href="https://www.facebook.com/AsambleaVirtual">Facebook</a></li><li><a href="https://plus.google.com/108444972669760594358/posts?hl=es">Google+</a></li>':'')?>
					<li><a href="/info/seguir"><?=_('Seguir')?>...</a></li>
				</ul>
			</li>
		</ul>
	</li>

	<li id="menu-info"<?=($txt_menu=='info'?' class="menu-sel"':'')?>><a href="/buscar"><?=_('Información')?></a>
		<ul>
			<li><a href="/info/censo"><?=_('Censo')?><span class="md"><?=num($pol['config']['info_censo'])?></span></a></li>
			<li><a href="/doc"><b><?=_('Documentos')?></b><span class="md"><?=$pol['config']['info_documentos']?></span></a></li>
			<li><a href="/geolocalizacion"><?=_('Mapa de ciudadanos')?></a></li>
			<li><a href="#" style="cursor:default;"><?=_('Estadísticas')?></a>
				<ul>
					<li><a href="/estadisticas"><?=_('Estadísticas')?></a></li>
					<li><a href="http://chartbeat.com/dashboard/?url=virtualpol.com&k=ecc15496e00f415838f6912422024d06" target="_blank"><?=_('Estadísticas online')?></a></li>
					<li><a href="/log"><?=_('Log de acciones')?></a></li>
				</ul>
			</li>
			<li><a href="/buscar"><?=_('Buscar')?></a></li>
			<li><a href="#" style="cursor:default;"><?=_('Sobre VirtualPol')?>...</a>
				<ul>
					<li><a href="http://www.virtualpol.com/video" target="_blank"><?=_('Vídeo de bienvenida')?></a></li>
					<li><a href="http://www.virtualpol.com/manual" target="_blank"><?=_('Documentación')?></a></li>
					<li><a href="http://www.virtualpol.com/TOS" target="_blank"><?=_('Condiciones de uso')?></a></li>
					<li><a href="http://www.virtualpol.com/desarrollo" target="_blank"><?=_('Desarrollo')?></a></li>
					<li><a href="https://virtualpol.com/donaciones" target="_blank"><?=_('Donaciones')?></a></li>
				</ul>
			</li>
		</ul>
	</li>

	<li id="menu-demo"<?=($txt_menu=='demo'?' class="menu-sel"':'')?>><a href="/votacion"><?=_('Democracia')?></a>
		<ul>
			<li><a href="/elecciones"><?=_('Elecciones')?></a></li>
			<li><a href="/votacion"><b><?=_('Votaciones')?></b><span class="md"><?=$pol['config']['info_consultas']?></span></a></li>
			<li><a href="/cargos"><?=_('Cargos')?></a>
				<ul>
					<?=($pol['config']['socios_estado']=='true'?'<li><a href="/socios">'._('Socios').'</a></li>':'')?>
					<li><a href="/grupos"><?=_('Grupos')?></a></li>
					<li><a href="/examenes"><?=_('Exámenes')?></a></li>
				</ul>
			</li>
			<li><a href="/control/gobierno"><?=_('Gobierno')?></a>
				<ul>
					<li><a href="/control/gobierno/privilegios"><?=_('Privilegios')?></a></li>
					<li><a href="/control/gobierno/notificaciones"><?=_('Notificaciones')?></a></li>
					<li><a href="/control/gobierno/foro"><?=_('Configuración foro')?></a></li>
					<li><a href="/control/kick"><?=_('Kicks')?></a></li>
					<li><a href="<?=SSL_URL?>dnie.php"><?=_('Autentificación')?></a></li>
				</ul>
			</li>
			<?=(ASAMBLEA?'':'<li><a href="/partidos">'._('Partidos').' <span class="md">'.$pol['config']['info_partidos'].'</span></a></li>')?>
		</ul>
	</li>

<?php if (ECONOMIA) { ?>
	<li id="menu-econ"<?=($txt_menu=='econ'?' class="menu-sel"':'')?>><a href="/pols"><?=_('Economía')?></a>
		<ul>
			<li><a href="/pols/cuentas"><?=_('Cuentas')?></a></li>
			<li><a href="/empresas"><b><?=_('Empresas')?></b></a></li>
			<?=($pol['pais']==PAIS?'<li><a href="/pols">'._('Tus monedas').'</a></li>':'')?>
			<li><a href="/subasta"><?=_('Subastas')?></a></li>
			<li><a href="/mapa"><?=_('Mapa')?></a></li>
			<li><a href="/info/economia"><?=_('Economía global')?></a></li>
		</ul>
	</li>
<?php } echo '<div id="notif">'.notificacion('print').'</div>'; ?>

	</ul>

	<div id="menu-next">

<?php

if (($pol['config']['socios_estado']=='true') AND (nucleo_acceso('ciudadanos')) AND (!nucleo_acceso('socios'))) {
	//echo '<p style="text-align:center;">'.boton(_('Inscríbete como socio'), '/socios', false, 'orange small').'</p>';
}


echo '<p style="color:#999;"><b>40%</b> <a href="https://www.transifex.net/projects/p/virtualpol/resource/virtualpol/" target="_blank" title="'._('VirtualPol está siendo traducido desde el Español original a muchos más idiomas. Puedes ayudar en la traducción. ¡Gracias!').'">'._('Traducción VirtualPol').'</a></p>';

if (PAIS == '15M') { echo '<p style="color:#999;"><b>'.timer('2012-05-12 00:00:00').'</b> para el <a href="/doc/31-dias-para-el-12m" title="12 de Mayo: Movilización Global"><b>12M</b></a>.</p><p style="color:#999;"><b>'.timer('2012-05-15 00:00:00').'</b> para el <a href="/doc/31-dias-para-el-12m" title="15 de Mayo"><b>15M</b></a>.</p>'; }

echo '<p id="palabras">';

foreach(explode(';', $pol['config']['palabras']) as $t) {
	$t = explode(':', $t);
	echo ($t[1]!=''?'<a href="http://'.$t[1].'">'.$t[2].'</a>':$t[2]).($pol['user_ID']==$t[0]||nucleo_acceso($vp['acceso']['control_gobierno'])?' <a href="/subasta/editar" style="float:right;color:#CCC;">#</a>':'').'<br />';
}

echo '</p>';

if ((ECONOMIA) AND (substr($_SERVER['REQUEST_URI'], 0, 5) != '/mapa')) {
	echo '<a href="/mapa" class="gris" style="float:right;">'._('mapa').'</a><a href="/subasta" class="gris">'._('Subasta').'</a>';
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
			<?=(PAIS=='15M'?'':'<a href="/"><img src="'.IMG.'banderas/'.PAIS.'_60.gif" height="50" border="0" /></a>')?>
			<span class="htxt" id="header-logo-p"><?=$pol['config']['pais_des'].', '.PAIS?></span>
		</div>

		<div id="header-right">
<?php
unset($txt_header);
if (isset($pol['user_ID'])) {
	echo '<span class="htxt"><b><a href="/perfil/'.$pol['nick'].'">'.$pol['nick'].($pol['cargo']!=0&&$pol['cargo']!=99?' <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" width="16" height="16" />':'').'</a>'.($pol['estado']!='ciudadano'?' (<b class="'.$pol['estado'].'">'.ucfirst($pol['estado']).'</b>)':'').' | </b><a href="/msg">'._('Mensajes privados').'</a><b>'.(ECONOMIA&&$pol['estado']=='ciudadano'?' | <a href="/pols"><b>'.pols($pol['pols']).'</b> '.MONEDA.'</a>':'').' | <a href="/accion.php?a=logout">'._('Salir').'</a></b></span>';
} else {
	echo boton(_('Crear ciudadano'), REGISTRAR.'?p='.PAIS, false, 'large green').' '.boton(_('Iniciar sesión'), REGISTRAR.'login.php?r='.base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']), false, 'large blue');
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
			<p><?=_('VirtualPol, la primera <b>Red Social Democrática</b>')?> <?=boton(_('Donar'), 'https://virtualpol.com/donaciones', false, 'small pill orange')?></p>
			
			<p><a target="_blank" href="http://www.virtualpol.com/video"><?=_('Vídeo')?></a> | <a target="_blank" href="http://www.virtualpol.com/documentacion"><?=_('Ayuda / Documentación')?></a><br />
			<a target="_blank" href="http://www.virtualpol.com/desarrollo"><?=_('Desarrollo / Código fuente')?></a> | <a target="_blank" href="http://www.virtualpol.com/TOS" title="Condiciones de Uso">TOS</a><br />
<?php
unset($txt);
if (!isset($pol['user_ID'])) { 
	echo '<a target="_blank" href="http://gonzo.teoriza.com" title="GONZO">Javier González</a> (<a target="_blank" href="http://www.teoriza.com" title="Blogs">Teoriza</a>, <a target="_blank" href="http://www.eventuis.com" title="Eventos">eventuis</a>, <a target="_blank" href="http://www.perfectcine.com" title="Cine">PerfectCine</a>)<br />'; 
} else { 
	echo boton(_('Reportar problema'), 'https://github.com/JavierGonzalez/VirtualPol/issues/new', '¿Estás seguro de hacer un reporte a desarrollo?\n\nSolo reportar problemas tecnicos o del sistema.\nSé conciso y no olvides aportar datos.\n\n¡Gracias!', 'small pill grey').' &nbsp;'; 
	if ($pol['user_ID'] == 1) { echo num((microtime(true)-TIME_START)*1000).'ms '.num(memory_get_usage()/1000).'kb |'; } 
}
?>
				 <span title="<?=_('Época antigua en IRC')?>" style="color:#BBB;">2004-</span>2008-2012
			</p>
		</div>
		
		<div id="footer-left">
<?php
echo '<table border="0"><tr><td height="30" nowrap="nowrap"><b>'.PAIS.', '.$pol['config']['pais_des'].'</b></td>';

if (ASAMBLEA) {
	echo '<td><a href="https://twitter.com/share" class="twitter-share-button" data-text="VirtualPol, '._('la primera red social democrática').'" data-lang="'.($vp['lang']=='es_ES'?'es':'en').'" data-size="large" data-related="AsambleaVirtuaI" data-count="none" data-hashtags="15M">'.($vp['lang']=='es_ES'?'Twittear':'Twitt').'</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></td>

<td><g:plusone annotation="none"></g:plusone></td>

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
	echo '<td><a href="https://twitter.com/share" class="twitter-share-button" data-text="VirtualPol, '._('la primera red social democrática').'" data-lang="'.($vp['lang']=='es_ES'?'es':'en').'" data-size="large" data-related="VirtualPol" data-count="none" data-hashtags="VirtualPol">'.($vp['lang']=='es_ES'?'Twittear':'Twitt').'</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></td>

<td><g:plusone annotation="none" href="http://'.HOST.'"></g:plusone></td>
';
}

echo '</tr></table>';


if ((isset($pol['user_ID'])) AND ($pol['config']['palabra_gob'] != ':') AND ($pol['config']['palabra_gob'] != '')) {
	echo '<div class="azul"><b><a href="http://'.explodear(':', $pol['config']['palabra_gob'], 1).'">'.explodear(':', $pol['config']['palabra_gob'], 0).'</a></b></div><br />';
}

if ((ECONOMIA) AND (isset($pol['config']['pols_frase']))) {
	echo '<div class="amarillo"><b>'.$pol['config']['pols_frase'].'</b></div>';
	if ($pol['config']['pols_fraseedit'] == $pol['user_ID']) { echo ' <a href="/subasta/editar" class="gris">#</a>'; }
}
?>	
		</div>
	</div>
</div>

<div id="pnick" class="azul" style="display:none;"></div>


<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
{lang: 'es'}
</script>

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