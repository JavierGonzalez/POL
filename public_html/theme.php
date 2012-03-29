<?php /******* THEME *******/

// Errores y redirecciones.
if ($_SERVER['HTTP_HOST'] == 'ninguno.'.DOMAIN) { redirect('http://www.'.DOMAIN); }
if (!isset($txt)) { header('HTTP/1.1 404 Not Found'); $txt = '<h1 style="font-weight:normal;">ERROR 404: <b>Página inexistente</b></h1>'; }
if (isset($_GET['error'])) { header('HTTP/1.1 401 Unauthorized'); $txt = '<h1 style="font-weight:normal;color:red;">ERROR: <b>'.base64_decode($_GET['error']).'</b></h1>'; }

if (isset($txt_title)) { $txt_title .= ' | '.PAIS.' | VirtualPol'; }
else { $txt_title = (isset($pol['config']['pais_des'])?$pol['config']['pais_des'].' de '.PAIS.' '.$kw.'| VirtualPol':PAIS.' '.$kw.'| VirtualPol'); }

$pol['config']['bg'] = 'tapiz-lineas-verdes.jpg';

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

<!--[if lt IE 9]><script src="<?=($_SERVER['HTTPS']?'https://':'http://')?>html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript" src="<?=($_SERVER['HTTPS']?'https://':'http://')?>ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=IMG?>scripts_all.js?v=10"></script>
<script type="text/javascript">
var _sf_startpt=(new Date()).getTime();
IMG = '<?=IMG?>';
p_scroll = false;
</script>

<?=$txt_header?>
</head>
<body>

<div id="content-left">
	
	<a href="http://www.virtualpol.com"><img src="<?=IMG?>media/logo-virtualpol-1_200.gif" width="200" height="60" alt="VirtualPol" /></a>
	
	<ul class="menu vertical">
		<li><a href="http://www.virtualpol.com/video">Vídeo bienvenida</a></li>
		<li><a href="http://www.virtualpol.com/documentacion">Documentación</a></li>
		<li><a href="http://www.virtualpol.com/TOS">Condiciones de uso</a></li>
		<li><a href="http://www.virtualpol.com/desarrollo">Desarrollo</a></li>
	</ul>

	<div id="menu-next">
		<p style="text-align:center;"><?=boton('Donaciones', 'http://www.virtualpol.com/donaciones', false, 'small pill orange')?></p>
	</div>
</div>




<div id="content-right">

	<div id="header">

		<div id="header-logo">
			<span class="htxt" id="header-logo-p">La primera Red Social Democrática</span>
		</div>


		<div id="header-right">
<?php
unset($txt_header);
if (isset($pol['user_ID'])) {
	echo '<span class="htxt"><b><a href="http://'.strtolower($pol['pais']).'.virtualpol.com/perfil/'.$pol['nick'].'">'.$pol['nick'].($pol['cargo']!=0&&$pol['cargo']!=99?' <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" width="16" height="16" />':'').'</a>'.($pol['estado']!='ciudadano'?' (<b class="'.$pol['estado'].'">'.ucfirst($pol['estado']).'</b>)':'').' |'.$txt_elec.' <a href="'.REGISTRAR.'login.php?a=logout">Salir</a></b></span>';
} else {
	echo boton('Crear ciudadano', REGISTRAR.'?p='.PAIS, false, 'large blue').' &nbsp; '.boton('Entrar', REGISTRAR.'login.php?r='.base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
}
?>
		</div>

		<div id="header-breadcrumbs">
			<ul class="breadcrumbs alt1">
				<li><a href="/"><img src="<?=IMG?>ico/home.png" width="18" height="18" alt="home" style="margin:-4px;" /></a></li>
				<?php if (is_array($txt_nav)) { foreach ($txt_nav AS $u => $a) { echo '<li><a href="'.(!is_numeric($u)?$u:'#').'">'.$a.'</a></li>'; } } ?>
			</ul>
		</div>

		<div id="header-tab">
			<ul class="ttabs right">
				<?php if (is_array($txt_tab)) { foreach ($txt_tab AS $u => $a) { echo '<li'.(!is_numeric($u)&&$_SERVER['REQUEST_URI']==$u?' class="current"':'').'><a href="'.(!is_numeric($u)?$u:'#').'">'.$a.'</a></li>'; } } ?>
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
			<a target="_blank" href="http://www.virtualpol.com/TOS" title="Condiciones de Uso">TOS</a> | <a target="_blank" href="http://www.virtualpol.com/desarrollo">Desarrollo / Código fuente</a><br />
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
echo '<table border="0"><tr><td height="30" nowrap="nowrap"><b>VirtualPol, la primera red social democrática</b></td>';
echo '</tr></table>';
?>	
		</div>
	</div>
<div>

<div id="pnick" class="azul" style="display:none;"></div>


<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
/* GA */
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