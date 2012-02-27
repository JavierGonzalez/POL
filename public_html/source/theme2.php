<?php 
if (!in_array($pol['nick'], array('GONZO', 'ZeroCool', 'oportunista', 'bradduk', 'lasarux'))) { redirect('http://www.'.DOMAIN.'/'); }
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
	min-height:600px;
}

#content-right {
	position:absolute;
	left:200px;
	padding:0 10px 0 20px;
	top:0px;

}

#header {
	height:60px;
}

#content {
	
}

#footer {
	height:50px;
}


/*** GENERAL ***/
/*div { border:1px dashed #444; }*/
body {
	cursor:default;
	color: #333;
	font-family: "Arial", "Helvetica", sans-serif;
	font-size:16px;
}

*[title] { cursor: help; }
abbr, .punteado { border-bottom:1px dotted #999; }
#vpc img { margin-bottom:-2px; }
ul.breadcrumbs { margin-top:3px; margin-left:-22px; }


/*** MENU ***/
.menu li {
	white-space:nowrap;
	font-size: 22px;
}
.menu ul {
	box-shadow: 6px 6px 15px #888;
}

.menu li { padding-left:8px; }
.menu li ul li ul { margin-left:-8px; }
.menu li ul li ul li ul { margin-left:-8px; }
#menu-comunicacion.hover, #menu-comunicacion .hover		{ border-left:8px solid #FF6262; padding-left:0px; /* background:#FFB1B1; */ }
#menu-informacion.hover, #menu-informacion .hover		{ border-left:8px solid #00DF00; padding-left:0px; /* background:#80EF80; */ }
#menu-democracia.hover, #menu-democracia .hover			{ border-left:8px solid #66BEFF; padding-left:0px; /* background:#B3DFFF; */ }
#menu-economia.hover, #menu-economia .hover				{ border-left:8px solid #FFFF51; padding-left:0px; /* background:#FFFFA8; */ }
#menu-notificaciones.hover, #menu-notificaciones .hover	{ border-left:8px solid grey; padding-left:0px; /* background:#FFD391; */ }
#menu-extra.hover, #menu-extra .hover					{ border-left:8px solid #FF9900; padding-left:0px; /* background:#FFD391; */ }

.md { 
	float:right;
	color:#808080; 
	font-size:16px;
	margin: 0 0 0 3px; 
}
#menu-next {
	min-height:350px;
	border-right:1px solid #CCC;
	padding:15px 5px 40px 8px;
}


/*** _____ ***/




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
	
	<a href="http://www.virtualpol.com"><img src="<?=IMG?>media/logo-virtualpol-1_200.gif"></a>
	

	<ul class="menu vertical">

	<li id="menu-comunicacion"><a href="/">Comunicación</a>
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

	<li id="menu-informacion"><a href="#">Información</a>
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

	<li id="menu-democracia"><a href="#">Democracia</a>
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
	<li id="menu-economia"><a href="#">Economía</a>
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

	<li id="menu-notificaciones"><a href="/">Notificaciones</a>
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
		<p>Más elementos en menu.</p>

		<div id="palabras">
<?php
foreach(explode(';', $pol['config']['palabras']) as $t) {
	$t = explode(':', $t);
	echo ($t[1]!=''?'<a href="http://'.$t[1].'"><b>'.$t[2].'</b></a>':$t[2]).($pol['user_ID']==$t[0]?' <a href="/subasta/editar/" class="gris">#</a>':'')."<br />\n";
}
?>
		</div>

	</div>
</div>




<div id="content-right">

	<div id="header">

		<!--<?=notificacion('print')?>-->
		<a href="/" title="<?=$pol['config']['pais_des'].' de '.PAIS?>"><img src="<?=IMG?>banderas/<?=PAIS?>_60.gif" width="60" height="40" border="0" /></a>
		<span style="color:#888;font-size:18px;"><?=$pol['config']['pais_des'].' de '.PAIS?></span>

		<div style="float:right;">
<?php
/*
} elseif ($pol['estado'] == 'extranjero') { // extranjero
	echo '<a href="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/perfil/'.$pol['nick'].'/">'.$pol['nick'].'</a> <span class="icon blue medium" data-icon="@"></span> (<b class="extranjero">Extranjero</b>) | <a href="/accion.php?a=logout">Salir</a>';
} elseif ($pol['estado'] == 'turista') { // TURISTA
	echo $pol['nick'] . ' (<b class="turista">Turista</b>) ' . $pol['tiempo_ciudadanizacion'] . ' | ' . boton('Solicitar Ciudadania', REGISTRAR) . ' | <a href="/accion.php?a=logout">Salir</a>';
} elseif ($pol['estado'] == 'kickeado') { // KICKEADO
	echo $pol['nick'] . ' (<b class="expulsado">Kickeado</b>) | <a href="/control/kick/"><b>Ver Kicks</b></a>';
} elseif ($pol['estado'] == 'expulsado') { // EXPULSADO
	echo $pol['nick'] . ' (<b class="expulsado">Expulsado</b>)';
} elseif ((isset($pol['nick'])) AND ($pol['estado'] != '')) { // sin identificar, login OK
	echo '<b>'.$pol['nick'].'</b> (<span class="infog"><b>Turista</b></span>) <span class="azul">' . boton('Solicitar Ciudadania', REGISTRAR) . '</span> | <a href="/accion.php?a=logout">Salir</a>';
} else { // sin identificar, sin login
	echo boton('Crear ciudadano', REGISTRAR.'?p='.PAIS).' | '.boton('Entrar', REGISTRAR.'login.php?r='.base64_encode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
}
*/


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
	</div>


	<div id="content">


		<!-- Alternative Style -->
		<ul class="breadcrumbs alt1">
			<li><a href="/"><span class="icon" data-icon="I" style="margin:-3px;"></span></a></li>
			<li><a href="">Chats</a></li>
			<li><a href="">Plaza de 15M</a></li>
		</ul>

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
<script type="text/javascript" src="<?=IMG?>lib/kickstart/js/prettify.js"></script>                                   <!-- PRETTIFY -->
<script type="text/javascript" src="<?=IMG?>lib/kickstart/js/kickstart.js"></script>                                  <!-- KICKSTART -->

<script type="text/javascript" src="<?=IMG?>scripts2.js?v=23"></script>
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