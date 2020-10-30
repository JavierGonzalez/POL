<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


// Errores y redirecciones.
if ($_SERVER['HTTP_HOST'] == 'ninguno.'.DOMAIN) { redirect('/'); }
if (isset($_GET['noti'])) { notificacion('visto', $_GET['noti']); }
if (isset($_GET['error'])) { header('HTTP/1.1 401 Unauthorized'); $txt = '<h1 style="font-weight:normal;color:red;">ERROR: <b>'.escape(base64_decode($_GET['error'])).'</b></h1>'; }
if (!isset($pol['config']['pais_des'])) { $pol['config']['pais_des'] = _('Plataforma cerrada'); }
if (isset($txt_title)) { $txt_title .= ' - '.$pol['config']['pais_des']; }
else { $txt_title = $pol['config']['pais_des']; }



// Tapiz de fondo (1400x100)
if (isset($_GET['bg'])) { 
	$body_bg = 'url(\'//'.$_GET['bg'].'\')';
} else if (isset($pol['config']['bg'])) { 
	$body_bg = 'url(\''.IMG.'bg/'.$pol['config']['bg'].'\')'; 
} else { $body_bg = 'none'; }

?>
<!DOCTYPE html>
<html lang="<?=(isset($vp['lang'])?substr($vp['lang'],0,2):'es')?>">
<head>
<title><?=$txt_title?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="description" content="<?=(isset($txt_description)?$txt_description:$txt_title.' - '.$kw)?>" />

<link rel="stylesheet" type="text/css" href="/img/style_all.css?v=<?=VER?>" media="all" />
<link rel="stylesheet" type="text/css" href="/img/kendel_style.css" media="all" />
<style type="text/css">
#header { background:#FFF <?=$body_bg?> repeat scroll top left; }
</style>
<script type="text/javascript" src="/img/lib/jquery-1.7.1.min.js"></script>



<link href="/img/lib/font-awesome.min.css" rel="stylesheet">
<script type="text/javascript">
var _sf_startpt=(new Date()).getTime();
IMG = '/img/';
ACCION_URL = "/accion/";
p_scroll = false;
</script>


<link rel="shortcut icon" href="/favicon.ico" />
<link rel="image_src" href="/img/banderas/<?=PAIS?>.png" />

<?=$txt_header?>


<?php

foreach ((array)$maxsim['autoload'] AS $file)
	if (substr($file,-4)==='.css')
		echo '<link rel="stylesheet" enctype="text/css" href="/'.$file.'" media="all" />'."\n";

echo '
<style type="text/css">
'.$maxsim['template']['css'].'
</style>';

?>


<script type="text/javascript">
<?php
foreach ((array)$maxsim['template']['js_array'] AS $key => $value)
    echo $key.' = "'.str_replace('"', '\"', $value).'";'."\n";
?>
</script>

</head>


<body>


<div id="content-left">
	
	<a href="/"><img src="/img/banderas/<?=PAIS?>_logo.png" width="200" height="60" alt="<?=PAIS?>" /></a>
	
	<ul class="menu vertical">

	<li id="menu-comu"<?=($txt_menu=='comu'?' class="menu-sel"':'')?>><a href="/"><?=_('Comunicación')?></a>
		<ul>
			<li><a href="/chat/list"><?=_('Chats')?></a></li>
			<li><a href="/foro"><b><?=_('Foros')?></b></a>
				<ul>
					<li><a href="/foro/ultima-actividad"><?=_('Última actividad')?></a>
					<?=(isset($pol['user_ID'])?'<li><a href="/foro/mis-respuestas">'._('Tu actividad').'</a></li>':'')?>
				</ul>
			</li>
			<li><a href="/msg"><?=_('Mensajes privados')?></a></li>
		</ul>
	</li>

	<li id="menu-info"<?=($txt_menu=='info'?' class="menu-sel"':'')?>><a href="/doc"><?=_('Información')?></a>
		<ul>
			<li><a href="/info/censo"><?=_('Censo')?><span class="md"><?=num($pol['config']['info_censo'])?></span></a></li>
			<li><a href="/doc"><b><?=_('Documentos')?></b><span class="md"><?=$pol['config']['info_documentos']?></span></a></li>
			<li><a href="/geolocalizacion"><?=_('Mapa de ciudadanos')?></a></li>
			<li><a href="#" style="cursor:default;"><?=_('Estadísticas')?></a>
				<ul>
					<li><a href="/estadisticas"><?=_('Estadísticas')?></a></li>
					<li><a href="/estadisticas/macro"><?=_('Estadísticas macro')?></a></li>
					<li><a href="/log"><?=_('Log de acciones')?></a></li>
				</ul>
			</li>
			<li><a href="/info/supervision-del-censo"><?=_('Supervisión censo')?></a></li>
			<li><a href="#" style="cursor:default;"><?=_('Sobre POL')?>...</a>
				<ul>
					<li><a href="/video" target="_blank"><?=_('Vídeo de bienvenida')?></a></li>
					<li><a href="/manual" target="_blank"><?=_('Documentación')?></a></li>
					<li><a href="/TOS" target="_blank"><?=_('Condiciones de uso')?></a></li>
					<li><a href="/codigo" target="_blank"><?=_('Codigo')?></a></li>
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
			<li><a href="/control/judicial"><?=_('Judicial')?></a>
			</li>

			<li><a href="/control/gobierno"><?=_('Gobierno')?></a>
				<ul>
					<li><a href="/control/gobierno/privilegios"><?=_('Privilegios')?></a></li>
					<li><a href="/control/gobierno/notificaciones"><?=_('Notificaciones')?></a></li>
					<li><a href="/control/gobierno/foro"><?=_('Configuración foro')?></a></li>
					<li><a href="/control/kick"><?=_('Kicks')?></a></li>
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
	echo '<p style="text-align:center;">'.boton(_('Inscríbete como socio'), '/socios', false, 'orange small').'</p>';
}




echo '<p id="palabras">';

foreach(explode(';', $pol['config']['palabras']) as $t) {
	$t = explode(':', $t);
	echo ($t[1]!=''?'<a href="//'.$t[1].'">'.$t[2].'</a>':$t[2]).($pol['user_ID']==$t[0]||nucleo_acceso($vp['acceso']['control_gobierno'])?' <a href="/subasta/editar" style="float:right;color:#CCC;">#</a>':'').'<br />';
}

echo '</p>';

if (ECONOMIA AND substr($_SERVER['REQUEST_URI'], 0, 5) != '/mapa') {
	echo '<a href="/mapa" class="gris" style="float:right;">'._('mapa').'</a><a href="/subasta" class="gris">'._('Subasta').'</a>';
	if (!isset($cuadrado_size)) { $cuadrado_size = 16; }
	include('mapa/mapa.php');
	echo '<div style="margin:4px 0 0 6px;">'.$txt_mapa.'</div>';
}
?>


	</div>
</div>




<div id="content-right">

	<div id="header">



		<div id="header-logo">
			<span class="htxt" id="header-logo-p"><?=$pol['config']['pais_des']?></span>
		</div>

		<div id="header-right">
<?php
unset($txt_header);
if (isset($pol['user_ID'])) {
	echo '<span class="htxt">'.($pol['estado']=='extranjero'||$pol['estado']=='turista'?'<span style="margin-left:-10px;">'.boton(_('Solicitar ciudadanía'), '/registrar', false, 'small red').'</span>':'').' <a href="/perfil/'.$pol['nick'].'"><b>'.$pol['nick'].'</b>'.($pol['cargo']!=0&&$pol['cargo']!=99?' <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" width="16" height="16" alt="cargo" />':'').'</a>'.($pol['estado']!='ciudadano'?' (<b class="'.$pol['estado'].'">'.ucfirst($pol['estado']).'</b>)':'').(nucleo_acceso('supervisores_censo')?' | <a href="/sc">SC</a>':'').($pol['estado']=='extranjero'?'':' | <a href="/msg" title="'._('Mensajes privados').'"><span class="icon medium" data-icon="@"></span></a> ').(ECONOMIA&&$pol['estado']=='ciudadano'?' | <a href="/pols"><b>'.pols($pol['pols']).'</b> '.MONEDA.'</a>':'').' | <a href="/registrar/login/panel" title="'._('Opciones').'"><span class="icon medium" data-icon="z"></span></a> | <a href="/accion/logout"><b>'._('Salir').'</b></a></span>';
} else {
	echo boton(_('Crear ciudadano'), '/registrar', false, 'large green').' '.boton(_('Iniciar sesión'), '/registrar/login?r='.$_SERVER['REQUEST_URI'], false, 'large blue');
}
?>
		</div>

		<div id="header-breadcrumbs">
			<ul class="breadcrumbs alt1">
				<li><a href="/"><img src="/img/ico/home.png" width="18" height="18" alt="home" style="margin:-4px;" /></a></li>
				<?php foreach ($txt_nav AS $u => $a) { echo '<li><a href="'.(!is_numeric($u)?$u:'#').'">'.$a.'</a></li>'; } ?>
			</ul>
		</div>

		<div id="header-tab">
			

			<ul class="ttabs right">
			
			<?php 
			foreach ($txt_tab AS $u => $a) { echo '<li'.(!is_numeric($u)&&$_SERVER['REQUEST_URI']==$u?' class="current"':'').'><a href="'.(!is_numeric($u)?$u:'#').'">'.$a.'</a></li>'; }
			?>
			</ul>
		</div>

	</div>



	<div id="content">
	<?=$echo?>
	</div>



	<div id="footer">

		<div id="footer-right" style="text-align:center;">
			<p>
            <a href="/" title="VirtualPol"><img src="/img/logo/vp2.png" width="200" height="60" alt="VirtualPol" /></a>
			</p>
			
			<p><a target="_blank" href="/video"><?=_('Vídeo')?></a> | <a target="_blank" href="/documentacion"><?=_('Documentación')?></a> | <a target="_blank" href="/codigo"><?=_('Codigo')?></a> | <a target="_blank" href="/TOS" title="Condiciones de Uso">TOS</a><br />
			<span title="Epoca antigua en IRC" style="color:#999;">2004-</span>2008-2020
            <br />
			<span style="color:#BBB;">
				<?=implode(' ', profiler($maxsim['debug']['crono']))?>
			</span>
            </p>
		</div>
		
		<div id="footer-left">
<?php
echo '<table><tr><td height="30" nowrap="nowrap"><b>'.$pol['config']['pais_des'].'</b>'.(PRE?' &nbsp; <span style="color:red;font-size:24px;"><b>PRE-PRODUCCION</b></span>':'').'</td></tr></table>';

if ((ECONOMIA) AND (isset($pol['config']['pols_frase']))) {
	echo '<div class="amarillo"><b>'.$pol['config']['pols_frase'].'</b></div>';
	if ($pol['config']['pols_fraseedit'] == $pol['user_ID']) { echo ' <a href="/subasta/editar" class="gris">#</a>'; }
}

if ((isset($pol['user_ID'])) AND ($pol['config']['palabra_gob'] != '')) {
	echo '<fieldset class="rich">'.(nucleo_acceso($vp['accesos']['control_gobierno'])?'<span style="float:right;"><a href="/control/gobierno">Editar</a></span>':'').$pol['config']['palabra_gob'].'</fieldset>';
}
?>	
		</div>
	</div>
</div>

<fieldset id="pnick" style="display:none;"></fieldset>


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


</script>
<script type="text/javascript" src="/img/scripts_all.js?v=<?=VER?>"></script>

<?php
foreach ((array)$maxsim['autoload'] AS $file)
	if (substr($file,-3)==='.js')
		echo '<script type="module" src="/'.$file.'"></script>'."\n";
?>

<script type="text/javascript">
<?=$maxsim['template']['js']?>
</script>


</body>
<?=$txt_footer?>

</html>