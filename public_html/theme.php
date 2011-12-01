<?php 

// THEME HOME de www.virtualpol.com

if ($link) { mysql_close($link); }
if (!$txt) { header('HTTP/1.1 301 Moved Permanently'); header('Location: http://' . HOST . '/'); exit; }

if (!$txt_description) { $txt_description = $txt_title . ' | VirtualPol | ' . PAIS; }
if ($txt_title) { $txt_title .= ' | VirtualPol'; } else { $txt_title = 'VirtualPol | Simulador Pol&iacute;tico Espa&ntilde;ol'; }



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$txt_title?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="language" content="es_ES" />
<meta name="description" content="<?=$txt_description?>" />

<script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>

<link href="<?=IMG?>style-home.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="/favicon.ico" />

<script type="text/javascript">
menu_ID = 0;
defcon = 5;
window.google_analytics_uacct = "UA-59186-46";
</script>
<script type="text/javascript" src="<?=($_SERVER['HTTPS']?'https://':'http://')?>ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

<style type="text/css">
body { background: <?=COLOR_BG?> url('<?=IMG?>vp.gif') repeat fixed top left; }
div#footer, div.column, div.content, div#header {
border: 1px solid #cccccc;
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
if ($pol['nick']) {
	$txt_perfil = '<b><a href="http://' . strtolower($pol['pais']) . '.virtualpol.com/perfil/' . $pol['nick'] . '/">' . $pol['nick'] . '</a></b> | <b class="' . $pol['estado'] . '">' . ucfirst($pol['estado']) . '</b> de <b>' . $pol['pais'] . '</b> | <a href="'.REGISTRAR.'login.php?a=logout">Salir</a>';
} else { // sin identificar, sin login
	$txt_perfil = boton('Crear ciudadano', REGISTRAR).' | '.boton('Login', REGISTRAR.'login.php');
}
?>
<div style="margin:10px 0 2px 0;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>

<td width="208"><span id="homelogo"><a href="http://www.virtualpol.com/" class="gris" title="Home"><img src="<?=IMG?>logo-virtualpol-1.gif" width="205" height="60" alt="VirtualPol" style="margin:-12px 0 -9px -6px;" border="0" /></a></td><td><span style="color:grey;font-size:20x;">Plataforma democr&aacute;tica autogestionada</span></span></td>

<td align="right"><?=$txt_perfil?></td>

</tr>
</table>
</div>

</div>
</div>
<div id="content-wrap" class="clear lcol">

<div class="content" style="max-width:900px;margin:0 auto;">
<div class="content-in">

<?=$txt?>

</div>
</div>
</div>
<div class="clear"></div>


<center style="margin:5px 0 -2px 0;"><span class="azul" style="padding:6px;color:grey;opacity:0.8;"><a href="http://www.virtualpol.com/"><b>VirtualPol</b></a> | Plataformas: 
<?php foreach ($vp['paises'] AS $pais) { if (!in_array($pais, $vp['paises_congelados'])) { echo '<a href="http://'.strtolower($pais).'.virtualpol.com/">'.$pais.'</a> '; } } ?>
 | <a href="http://desarrollo.virtualpol.com/">Blog Desarrollo</a> | <!--<a href="http://www.teoriza.com/">Blogs Teoriza</a> |--> <a href="http://www.virtualpol.com/legal"><b>TOS</b></a>
</span></center>

</div>

<script type="text/javascript" src="<?=IMG?>scripts.js?v=20"></script>
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
