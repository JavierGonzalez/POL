<?php 
if (!nucleo_acceso('privado', 'GONZO ZeroCool oportunista bradduk lasarux ddo mia')) { redirect('http://www.'.DOMAIN.'/'); }
/* DESARROLLO DEL NUEVO DISEÑO 

PODEMOS MODIFICAR LIBREMENTE:
public_html/source/theme2.php (es este archivo)
public_html/img/style2.css (aunque lo más comodo es poner el nuevo CSS aqui mismo, en el <HEAD> y cuando esté, lo pasamos al style2.css)

El resto de archivos... poco a poco
*/



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
<!DOCTYPE html>
<html>
<head>
<title><?=$txt_title?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="language" content="es_ES" />
<meta name="description" content="<?=$txt_description?>" />

<script type="text/javascript">
var _sf_startpt=(new Date()).getTime()
defcon = <?=$pol['config']['defcon']?>;
IMG = "<?=IMG?>";
</script>

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<link rel="stylesheet" type="text/css" href="<?=IMG?>lib/kickstart/css/kickstart.css" media="all" />
<link rel="shortcut icon" href="/favicon.ico" />

<!--<link rel="stylesheet" type="text/css" href="<?=IMG?>style2.css?v=21" />-->
<style type="text/css">

/*** ESTRUCTURA ***/
#content-left { 
	position:fixed;
	left:0px;
	top:0px;
	width:200px;
}
#menu-next {
	min-height:280px;
	border-right:1px solid #CCC;
	padding:10px 5px 10px 8px;
	box-shadow:inset 0px 10px 20px #EEE;
}

#content-right {
	position:absolute;
	left:200px;
	right:0;
	top:0px;
	min-width:760px;
}


#header {
	height:99px;
	border-bottom:1px solid #CCC;
	box-shadow:inset -8px -8px 15px #EEE;
	background: #FFF none repeat fixed top left;
}
ul.breadcrumbs.alt1 li a { border-bottom:1px solid #CCC; }

#header-logo {
	position:absolute;
	top:6px;
	left:5px;
	color:#999;
	text-shadow:1px 1px 8px #CCC;
	font-size:18px;
}
#header-right {
	position:absolute;
	top:5px;
	right:10px;
}
#header-breadcrumbs {
	position:absolute;
	left:-2px;
	top:53px;
}
.breadcrumbs li { background:#FFF; }
.breadcrumbs .last { font-weight:bold; }
#header-tab {
	position:absolute;
	right:5px;
	top:56px;
}

#content {
	padding:0 10px 0 20px;
	min-height:500px;
}

#footer {
	height:50px;
	margin-top:20px;
}




/*** GENERAL ***/
/*div { border:1px dashed #444; }*/
body {
	cursor:default;
	color: #333;
	font-family: "Arial", "Helvetica", sans-serif;
	font-size:16px;
	min-width:900px;
}
.quitar { display:none; }

a { 
	text-decoration:none;
}
a:hover {
	text-decoration:underline;
}

*[title] { cursor: help; }
abbr, .punteado { border-bottom:1px dotted #999; }
#vpc img { margin-bottom:-2px; }

h1 { font-size:28px; }
h2 { font-size:22px; }
h3 { font-size:18px; }

#vpc { box-shadow:inset -10px 5px 20px #EEE; padding-left:15px; }
#vp_c { margin: -18px 0 0 -20px; }




/*** MENU ***/
.menu li {
	white-space:nowrap;
	font-size: 22px;
	padding-left:8px;
	min-width:180px;
}
.menu ul { box-shadow: 6px 6px 15px #888; }

.menu li ul li ul { margin-left:-8px; }

.menu a:hover { margin-right:8px; }

#menu-comu.hover, #menu-comu .hover, #menu-comu.menu-sector { border-left:8px solid #FF6262; padding-left:0px; }
#menu-info.hover, #menu-info .hover, #menu-info.menu-sector { border-left:8px solid #00DF00; padding-left:0px; }
#menu-demo.hover, #menu-demo .hover, #menu-demo.menu-sector { border-left:8px solid #66BEFF; padding-left:0px; }
#menu-econ.hover, #menu-econ .hover, #menu-econ.menu-sector { border-left:8px solid #FFFF51; padding-left:0px; }
#menu-noti.hover, #menu-noti .hover, #menu-noti.menu-sector { border-left:8px solid orange;  padding-left:0px; }
#menu-noti li { overflow:hidden; }
#menu-noti li.menu-sector { background:red; }

#menu-extra.hover, #menu-extra .hover { border-left:8px solid #FF9900; padding-left:0px; }
/* box-shadow:inset 8px 0px 0px #FF6262; */
.md { 
	float:right;
	color:#808080; 
	font-size:16px;
	margin: 0 0 0 3px; 
}



/*** TTABS ***/
ul.ttabs{
	margin:10px 0 -1px 0;
	padding:0;
	width:100%;
	float:left;
	height:33px;
}

ul.ttabs.left{text-align:left;}
ul.ttabs.center{text-align:center;}
ul.ttabs.right{text-align:right;}

ul.ttabs li{
	list-style-type:none;
	margin:0 2px 0 0;
	padding:0;
	display:inline-block;
	*display:inline;/*IE ONLY*/
	position:relative;
	top:0;
	left:0;
	*top:1px;/*IE 7 ONLY*/
	zoom:1;
}
	
ul.ttabs li a{
	text-decoration:none;
	color:#666;
	display:inline-block;
	padding:9px 15px;
	position: relative;
	top:0;
	left:0;
	line-height:100%;
	background:#f5f5f5;
	box-shadow: inset 0px -3px 3px rgba(0,0,0,0.03);
	border:1px solid #e5e5e5;
	border-bottom:0;
	font-size:0.9em;
	zoom:1;
	border-top-left-radius: 10px; -moz-border-radius-topleft: 10px; -webkit-border-top-left-radius: 10px;
	border-top-right-radius:10px; -moz-border-radius-topright:10px; -webkit-border-top-right-radius:10px;
}
	
ul.ttabs li a:hover{
	background:#fff;
}
	
ul.ttabs li.current a{
	position:relative;
	top:1px;
	left:0;
	background:#fff;
	box-shadow: none;
	color:#222;
}



/*** NOTIFICACIONES ***/
#noti {
	margin:-3px -2px -3px -14px;
	padding:8px 0 0 0;
	width:46px;
	height:38px;
	overflow:hidden;
	cursor:pointer;
	color:#FFF;
	text-align:center;
	text-shadow:2px 2px 7px red;
	font-size:26px;
	font-weight:bold;
	border-radius: 6px;
	-moz-border-radius: 6px; 
	-webkit-border-radius: 6px;
}
#noti:hover {
	background-color:#FFF;
}
.noti_on { background: url('ico/noti_on.png') no-repeat; }
.noti_off { background: url('ico/noti_off.png') no-repeat; }
#noti_list {
	box-shadow: 6px 6px 15px #888;
	margin:0 0 0 -15px;
	padding:0 20px 0 0;
	position:absolute;
	display:none;
	max-width:500px;
	min-width:100px;
	overflow:hidden;
	white-space:nowrap;
	border:1px solid #FFF;
	border-top:none;
	background:#EEE;
	border-bottom-left-radius:8px; -moz-border-radius-bottomleft:8px; -webkit-border-bottom-left-radius:8px;
	border-bottom-right-radius:8px; -moz-border-radius-bottomright:8px; -webkit-border-bottom-right-radius:8px;
}
#noti_hacer { border-top:1px solid #FFF; }
#noti_hacer a {
	float:right;
	color:#999;
	margin:-2px 0 0 0;
}
#noti_list li:hover {
	text-decoration:underline;
}
#noti_list li {
	cursor:pointer;
	font-size:18px;
	list-style-type:none;
	margin:-18px -20px 18px -40px;
	padding:10px 15px;
	border-top:1px solid #F8F8F8;
}
.noti_nuevo {
	background:#FFF;
	font-weight:bold;
}
.noti_nuevo a {
	font-weight:bold;
	color:red;
}
.noti_rep_num {
	float:right;
	margin:-2px 0 0 15px;
	color:#BBB;
	font-size:20px;
}


/*** VIRTUALPOL CONTENT ***/
#doc_pad { text-align:justify; margin:20px; }
#doc_pad ul, #doc_pad ol { margin:4px 0 -4px 0; }
#doc_pad li { text-align:left; margin:3px 0 4px 0; }
#doc_pad ol ol { list-style-type: lower-roman; }
.indent { list-style-type:none; }



/*** HACKS ***/
strong, b {color:inherit;background:none;padding:0px;} /* Para anular una rareza de kickstart */
</style>



<?=$txt_header?>
</head>
<body>


<div id="content-left">
	
	<a href="http://www.virtualpol.com"><img src="<?=IMG?>media/logo-virtualpol-1_200.gif" width="200" height="60" alt="VirtualPol" /></a>
	
	<ul class="menu vertical">

	<li id="menu-comu"<?=($txt_menu=='comu'?' class="menu-sector"':'')?>><a href="/">Comunicación</a>
		<ul>
			<li><a href="/chats/">Chats</a></li>
			<li><a href="/foro/">Foros</a>
				<ul>
					<li><a href="/foro/ultima-actividad/">Última actividad</a>
					<?=(isset($pol['user_ID'])?'<li><a href="/foro/mis-respuestas/">Tu actividad</a></li>':'')?>
				</ul>
			</li>
			<?=(isset($pol['user_ID'])?'<li><a href="mumble://'.$pol['nick'].'@mumble.democraciarealya.es/Virtualpol/'.PAIS.'/?version=1.2.0">Voz</a><ul><li><a href="/info/voz/">Config. Mumble</a></li></ul></li>':'')?>
			<li><a href="/msg/">Mensajes Privados</a></li>
		</ul>
	</li>

	<li id="menu-info"<?=($txt_menu=='info'?' class="menu-sector"':'')?>><a href="#">Información</a>
			<ul>
				<li><a href="/info/censo/">Censo <span class="md"><?=num($pol['config']['info_censo'])?></span></a></li>
				<li><a href="/doc/">Documentos <span class="md"><?=$pol['config']['info_documentos']?></span></a></li>
				<li><a href="#" style="cursor:default;">Datos</a>
					<ul>
						<li><a href="/estadisticas/">Estadísticas</a></li>
						<!--<li><a href="http://chartbeat.com/dashboard2/?url=virtualpol.com&k=ecc15496e00f415838f6912422024d06" target="_blank" title="Estadísticas de ChartBeat">Estadísticas web</a></li>-->
						<li><a href="/log-eventos/">Log acciones</a></li>
					</ul>
				</li>
				<li><a href="/buscar/">Buscar</a></li>
				<li><a href="#" style="cursor:default;">Sobre VirtualPol...</a>
					<ul>
						<li><a href="http://www.virtualpol.com/video" target="_blank">Bienvenido (video)</a></li>
						<li><a href="http://www.virtualpol.com/manual" target="_blank">Documentación</a></li>
						<li><a href="http://www.virtualpol.com/desarrollo" target="_blank">Desarrollo</a></li>
						<li title="Condiciones de Uso"><a href="http://www.virtualpol.com/TOS" target="_blank">TOS</a></li>
					</ul>
				</li>
			</ul>
		</li>

	<li id="menu-demo"<?=($txt_menu=='demo'?' class="menu-sector"':'')?>><a href="#">Democracia</a>
		<ul>
			<li><a href="/elecciones/"><b>Elecciones</b></a></li>
			<li><a href="/votacion/">Votaciones <span class="md"><?=$pol['config']['info_consultas']?></span></a></li>
			<li><a href="/control/"><b>Gestión</b></a>
				<ul>
					<li title="Control de Gobierno"><a href="/control/gobierno/">Control</a></li>
					<li title="Bloqueos de moderación"><a href="/control/kick/">Kicks</a></li>
					<li><a href="/examenes/">Exámenes</a></li>
					<li><a href="<?=SSL_URL?>dnie.php">Autentificación</a></li>
				</ul>
			</li>
			<li><a href="/grupos/">Grupos</a></li>
			<li><a href="/cargos/">Cargos</a></li>
			<li><a href="/hacer/">¿Qué hacer?</a></li>
		</ul>
	</li>

<?php if (ECONOMIA) { ?>
	<li id="menu-econ"<?=($txt_menu=='econ'?' class="menu-sector"':'')?>><a href="#">Economía</a>
		<ul>
			<?=($pol['pais']==PAIS?'<li><a href="/pols/"><b>Tus monedas</b></a></li>':'')?>
			<li><a href="/empresas/"><b>Empresas</b></a></li>
			<li><a href="/pols/cuentas/">Cuentas</a></li>
			<li><a href="/subasta/">Subastas</a></li>
			<li><a href="/mapa/">Mapa</a></li>
			<li><a href="/info/economia/">Economía Global</a></li>
		</ul>
	</li>
<?php } ?>
	<li id="menu-noti"><a href="/">Notificaciones</a>
		<ul>
			<li onclick="window.location.href='/?noti=8037';"><a href="/?noti=8037">Mensaje privado de FritzDiogenes</a><span class="noti_rep_num">5</span></li>
			<li onclick="window.location.href='/?noti=16415';"><a href="/?noti=16415">Ya puedes afiliarte a un nuevo grupo: PARLAMENTO.</a></li>
			<li onclick="window.location.href='/?noti=16414';"><a href="/?noti=16414">Mensaje privado de ethos</a></li>
			<li onclick="window.location.href='/?noti=8004';"><a href="/?noti=8004">Mensaje privado de GONZO</a><span class="noti_rep_num">9</span></li>
			<li onclick="window.location.href='/?noti=11858';"><a href="/?noti=11858">Mensaje privado de Koba</a></li>
			<li onclick="window.location.href='/?noti=11841';"><a href="/?noti=11841">Mensaje privado de kir</a></li>
			<li onclick="window.location.href='/hacer';" id="noti_hacer"><a href="/hacer">¿Qué hacer?</a></li>
		</ul>
	</li>

	</ul>


	<div id="menu-next">

		<p id="palabras">
<?php
foreach(explode(';', $pol['config']['palabras']) as $t) {
	$t = explode(':', $t);
	echo ($t[1]!=''?'<a href="http://'.$t[1].'"><b>'.$t[2].'</b></a>':$t[2]).($pol['user_ID']==$t[0]?' <a href="/subasta/editar/" class="gris">#</a>':'')."<br />\n";
}
?>
		</p>

		<p>Más elementos en menú...</p>

	</div>
</div>




<div id="content-right">

	<div id="header">

		<div id="header-logo">
			<a href="/"><img src="<?=IMG?>banderas/<?=PAIS?>_60.gif" height="50" border="0" /></a>
			<span style="position:absolute;top:0;left:100px;white-space:nowrap;"><?=$pol['config']['pais_des'].', '.PAIS?></span>
		</div>

		<div id="header-right">
<?php
unset($txt_header);
if (isset($pol['user_ID'])) {
	if ($pol['config']['elecciones_estado'] == 'normal') {  
		$txt_elec = ' <a href="/elecciones/">Elecciones en <b style="font-size:18px;"><span class="timer" value="'.strtotime($pol['config']['elecciones_inicio']).'"></span></b></a> |'; 
	} elseif ($pol['config']['elecciones_estado'] == 'elecciones') {  
		$elec_quedan = (strtotime($pol['config']['elecciones_inicio']) + $pol['config']['elecciones_duracion']);
		switch ($pol['config']['elecciones']) {
			case 'pres1': $txt_elec = ' <a href="/elecciones/" style="color:red;"><b>1&ordf; Vuelta en curso</b>, queda <b style="font-size:18px;">'.timer(($elec_quedan - 86400), true).'</b></a> |';  break;
			case 'pres2': $txt_elec = ' <a href="/elecciones/" style="color:red;"><b>2&ordf; Vuelta en curso</b>, queda <b style="font-size:18px;">'.timer($elec_quedan, true).'</b></a> |'; break;
			case 'parl': $txt_elec = ' <a href="/elecciones/" style="color:blue;"><b>Elecciones en curso</b>, queda <b style="font-size:18px;">'.timer($elec_quedan, true).'</b></a> |';  break;
		}
	}
	echo '<a href="/perfil/'.$pol['nick'].'">'.$pol['nick'].($pol['cargo']!=0?' <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" width="16" height="16" />':'').'</a>'.($pol['estado']!='ciudadano'?' (<b class="'.$pol['estado'].'">'.ucfirst($pol['estado']).'</b>)':'').(ECONOMIA?' | <a href="/pols/"><b>'.pols($pol['pols']).'</b> '.MONEDA.'</a>':'').' | <a href="/msg/" title="Mensajes Privados (MP)"><span class="icon blue medium" data-icon="@"></span></a> |'.$txt_elec.' <a href="/accion.php?a=logout">Salir</a>';
} else {
	echo boton('Crear ciudadano', REGISTRAR.'?p='.PAIS).' | '.boton('Entrar', REGISTRAR.'login.php?r='.base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
}
?>
		</div>

		<div id="header-breadcrumbs">
			<ul class="breadcrumbs alt1">
				<li><a href="/"><span class="icon" data-icon="I" style="margin:-3px;"></span></a></li>
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

<div>


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="<?=IMG?>lib/kickstart/js/prettify.js"></script>
<script type="text/javascript" src="<?=IMG?>lib/kickstart/js/kickstart.js"></script>

<script type="text/javascript" src="<?=IMG?>scripts2.js?v=23"></script>
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