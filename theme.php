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
if (!isset($txt)) { header('HTTP/1.1 404 Not Found'); $txt = '<h1 style="font-weight:normal;">'._('ERROR').' 404: <b>'._('Página inexistente').'</b></h1>'; }
//if (isset($_GET['error'])) { header('HTTP/1.1 401 Unauthorized'); $txt = '<h1 style="font-weight:normal;color:red;">'._('ERROR').': <b>'.escape(base64_decode($_GET['error'])).'</b></h1>'; }

if (isset($txt_title)) { $txt_title .= ' | VirtualPol'; }
else { $txt_title = 'VirtualPol - '._('La primera Red Social Democrática'); }

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
<meta name="google-site-verification" content="sOlnqlxPXY2K01CFCybausXX0aufZjWcadvcfJBxLCo" />
<meta name="description" content="<?=(isset($txt_description)?$txt_description:$txt_title.' - '.$kw.PAIS)?> | <?=_('La primera Red Social Democrática')?> | VirtualPol" />

<link rel="stylesheet" type="text/css" href="<?=IMG?>style_all.css?v=<?=VER?>" media="all" />
<style type="text/css">
#header { background:#FFF <?=$body_bg?> repeat scroll top left; }
</style>

<link rel="shortcut icon" href="/favicon.ico" />
<link rel="image_src" href="<?=IMG?>virtualpol-logo-cuadrado-original.gif" />

<!--[if lt IE 9]><script src="<?=($_SERVER['HTTPS']?'https://':'http://')?>html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script type="text/javascript" src="<?=($_SERVER['HTTPS']?'https://':'http://')?>ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=IMG?>scripts_all.js?v=<?=VER?>"></script>
<script type="text/javascript">
var _sf_startpt=(new Date()).getTime();
IMG = '<?=IMG?>';
p_scroll = false;
</script>

<?=$txt_header?>
</head>
<body>

<div id="content-left">
	
	<a href="http://www.virtualpol.com"><img src="<?=IMG?>logo/vp2.png" width="200" height="60" alt="VirtualPol" /></a>
	
	<ul class="menu vertical">
		<?=(isset($pol['pais'])?'<li><a href="http://'.strtolower($pol['pais']).'.'.DOMAIN.'"><b>'._('Ir a').' '.$pol['pais'].'</b></a></li>':'')?>
		<li><a href="http://www.virtualpol.com/video"><?=_('Vídeo bienvenida')?></a></li>
		<li><a href="http://www.virtualpol.com/documentacion"><?=_('Documentación')?></a></li>
		<li><a href="http://www.virtualpol.com/TOS"><?=_('Condiciones de uso')?></a></li>
		<li><a href="http://www.virtualpol.com/desarrollo"><?=_('Desarrollo')?></a></li>
	</ul>

	<div id="menu-next">

<?=(nucleo_acceso('ciudadanos_global')&&false?'<p style="text-align:center;">'.boton(_('Donaciones'), 'http://www.virtualpol.com/donaciones', false, 'small pill orange').'</p>':'')?>


<?php
$result = mysql_query("SELECT nick, pais
FROM users 
WHERE fecha_last > '".date('Y-m-d H:i:00', time() - 3600)."' AND estado != 'expulsado'
ORDER BY fecha_last DESC", $link);
while($r = mysql_fetch_array($result)){ 
	$li_online_num++; 
	if ($li_online_num <= 50) {
	$li_online .= '<a href="http://'.strtolower($r['pais']).'.'.DOMAIN.'/perfil/'.$r['nick'].'" style="color:#AAA;">'.$r['nick'].'</a> '; }
}

echo '<p><b>'.num($li_online_num).' '._('ciudadanos').'</b> '._('online').':<br />
'.$li_online.'</td>'; 
?>

	</div>
</div>




<div id="content-right">

	<div id="header">

		<div id="header-logo">
			<span class="htxt" id="header-logo-p" style="font-size:20px;"><?=_('La primera Red Social Democrática')?></span>
		</div>


		<div id="header-right">
<?php
unset($txt_header);
if (isset($pol['user_ID'])) {
	echo '<span class="htxt"><b><a href="http://'.strtolower($pol['pais']).'.virtualpol.com/perfil/'.$pol['nick'].'">'.$pol['nick'].($pol['cargo']!=0&&$pol['cargo']!=99?' <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" width="16" height="16" />':'').'</a>'.($pol['estado']!='ciudadano'?' (<b class="'.$pol['estado'].'">'.ucfirst(_($pol['estado'])).'</b>)':'').' | <a href="'.REGISTRAR.'login.php?a=panel">'._('Opciones').'</a> | <a href="'.REGISTRAR.'login.php?a=logout">'._('Salir').'</a></b></span>';
} else {
	echo boton(_('Crear ciudadano'), REGISTRAR.'?p='.PAIS, false, 'large green').' '.boton(_('Iniciar sesión'), REGISTRAR.'login.php?r='.base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']), false, 'large blue');
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
			<p><?=_('VirtualPol, la primera <b>Red Social Democrática</b>')?> <?=boton(_('Donar'), 'http://virtualpol.com/donaciones', false, 'small pill orange')?></p>
			
			<p><a target="_blank" href="http://www.virtualpol.com/video"><?=_('Vídeo')?></a> | <a target="_blank" href="http://www.virtualpol.com/documentacion"><?=_('Ayuda / Documentación')?></a><br />
			<a target="_blank" href="http://www.virtualpol.com/desarrollo"><?=_('Desarrollo / Código fuente')?></a> | <a target="_blank" href="http://www.virtualpol.com/TOS" title="Condiciones de Uso">TOS</a><br />
<?php
unset($txt);
if (!isset($pol['user_ID'])) { echo '<a target="_blank" href="http://gonzo.teoriza.com" title="GONZO">Javier González</a> (<a target="_blank" href="http://www.teoriza.com" title="Blogs">Teoriza</a>, <a target="_blank" href="http://www.eventuis.com" title="Eventos">eventuis</a>, <a target="_blank" href="http://www.perfectcine.com" title="Cine">PerfectCine</a>)<br />'; }
if ($pol['user_ID'] == 1) { echo num((microtime(true)-TIME_START)*1000).'ms '.num(memory_get_usage()/1000).'kb | '; }
?>
				<span title="<?=_('Época antigua en IRC')?>" style="color:#BBB;">2004-</span>2008-2012
			</p>
		</div>
		
		<div id="footer-left">
<?php
echo '<table border="0"><tr><td height="30" nowrap="nowrap"><b>'._('VirtualPol, la primera red social democrática').'</b></td>';
echo '</tr></table>';
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