<?php 
if (!nucleo_acceso('privado', 'GONZO ZeroCool oportunista bradduk lasarux')) { redirect('http://www.'.DOMAIN.'/'); }
/******* DESARROLLO DEL NUEVO DISEÑO *******/

if ($_SERVER['HTTP_HOST'] == 'ninguno.'.DOMAIN) { redirect('http://www.'.DOMAIN.'/'); }
if (isset($_GET['noti'])) { notificacion('visto', $_GET['noti']); }
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

?>
<!DOCTYPE html>
<html>
<head>
<title><?=$txt_title?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="language" content="es_ES" />
<meta name="description" content="<?=$txt_description?>" />

<script type="text/javascript">
var _sf_startpt=(new Date()).getTime();
defcon = <?=$pol['config']['defcon']?>;
IMG = '<?=IMG?>';
</script>

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<link rel="stylesheet" type="text/css" href="<?=IMG?>lib/kickstart/css/kickstart.css" media="all" />
<link rel="shortcut icon" href="/favicon.ico" />

<!--<link rel="stylesheet" type="text/css" href="<?=IMG?>style2.css?v=21" />-->
<style type="text/css">

/*** PROVISIONAL ***/
.quitar { display:none; } /* Elimina elementos del diseño antiguo para la transición */
/*div { border:1px dashed #444; }*/



/*** ESTRUCTURA ***/
#content-left { 
	position:fixed;
	left:0px;
	top:0px;
	width:200px;
}
#menu-next {
	height:550px;
	border-right:1px solid #CCC;
	padding:4px 5px 10px 8px;
	box-shadow:inset -3px 11px 20px #EEE;
}

#content-right {
	position:absolute;
	left:200px;
	right:0;
	top:0px;
	min-width:760px;
	max-width:1300px;
}

#header {
	height:99px;
	border-bottom:1px solid #CCC;
	box-shadow:inset -6px -8px 15px #EEE;
	background: #FFF none repeat fixed top left;
}

#header-logo {
	position:absolute;
	top:6px;
	left:5px;
	color:#AAA;
	text-shadow:1px 1px 1px #FFF;
	font-size:18px;
}
#header-logo-p { background-color:#FFF; opacity: 0.5; position:absolute; top:15px; left:100px; white-space:nowrap; }
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
#header-tab {
	position:absolute;
	top:56px;
	right:5px;
}

#content {
	padding:0 10px 5px 20px;
	min-height:480px;
}

#footer {
	min-height:152px;
	margin:0 0 0 -200px;
	padding:2px 20px 5px 20px;
	border-top:1px solid #CCC;
	box-shadow:inset 0px 10px 20px #EEE;
	background:#FFF;
}
#footer-left {
	margin-right:300px;
	max-width:850px;
}
#footer-right {
	position:absolute;
	right:20px;
	text-align:right;
}




/*** GENERAL ***/
body {
	cursor:default;
	color: #333;
	font-family: "Arial", "Helvetica", sans-serif;
	font-size:16px;
	min-width:900px;
}


a { text-decoration:none; }
a:hover { text-decoration:underline; }

*[title] { cursor: help; }
abbr, .punteado { border-bottom:1px dotted #999; }

h1 { font-size:28px; }
h2 { font-size:22px; }
h3 { font-size:18px; }




/*** MENU ***/
.menu li {
	white-space:nowrap;
	font-size: 21px;
}
.menu li a { padding: 10px 0 10px 20px; }
.menu ul { max-width:500px; box-shadow: 6px 6px 15px #888; }

#menu-comu.hover, #menu-comu .hover, #menu-comu.menu-sel { box-shadow:inset 8px 0px 0px #FF6262; }
#menu-info.hover, #menu-info .hover, #menu-info.menu-sel { box-shadow:inset 8px 0px 0px #00DF00; }
#menu-demo.hover, #menu-demo .hover, #menu-demo.menu-sel { box-shadow:inset 8px 0px 0px #66BEFF; }
#menu-econ.hover, #menu-econ .hover, #menu-econ.menu-sel { box-shadow:inset 8px 0px 0px #FFFF51; }
#menu-noti.hover, #menu-noti .hover { box-shadow:inset 8px 0px 0px orange; }

#menu-noti.menu-sel a { color:#CCC; }
#menu-noti.menu-sel {
	text-shadow: 2px 1px 11px #FFF;
	background:url('http://www.virtualpol.com/img/ico/noti_alert.png'); 
}

.md { 
	position:absolute;
	right:8px;
}
#menu-noti.menu-sel .md { right:20px; }




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

.breadcrumbs li { background:#FFF; }
.breadcrumbs .last { font-weight:bold; }
ul.breadcrumbs.alt1 li a { border-bottom:1px solid #CCC; }


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




/*** CONTENIDO GENERAL ***/

.votacion_desc_min {
	max-height:200px;
	padding-bottom:40px;
	overflow-y:auto;
	box-shadow:inset -8px -8px 15px #FFF;
}

.vcc, .vc, .vcn, .vcnn { 
	background-color:#EEE;
	font-weight:bold;  
	font-size:18px;
	padding: 2px 5px 1px 4px;
	border-radius: 6px;
	-moz-border-radius: 6px; 
	-webkit-border-radius: 6px;
}

.vcc { 
	color:#EA9800; 
}

.vc { 
	color:orange; 
}

.vcn { 
	color:#FF3E43; 
}

.vcnn { 
	color:#D20000; 
}

.form_textarea {
	min-width:475px;
	width:90%;
	height:350px;
	color:green;
	font-weight:bold;
}

.amarillo, .azul {
	background:#FFFFDD;
	border:1px solid #FFD700;
	padding:5px 10px 5px 10px; 
	border-radius: 6px; 
	-moz-border-radius: 6px; 
	-webkit-border-radius: 6px;
}
.azul { 
	border:1px solid #00CCFF;
	background:#D3F7FE; 
}

.gris { 
	color:#808080; 
}

#pnick { 
	min-width:280px;
	text-align:left; 
	box-shadow: 6px 6px 15px #888;
}
#pnick b { 
	color:green; 
}

.pols { color:#ECC900; }

.ciudadano, .desarrollador { color:#06f; }
.turista { color:#66B3FF; }
.validar { color:#96B7CF; }
.expulsado { color:#FF8A8A; }
.extrangero { color:#CCC; }



/*** CONTENIDO CHAT ***/
#vpc img { margin-bottom:-2px; }
#vpc { box-shadow:inset -10px 5px 20px #EEE; padding-left:15px; vertical-align:bottom; height:450px; overflow:auto; overflow-x:hidden; }
#vpc ul { padding:0; margin:0; position:static; }
#vpc ul li { padding:0; margin:0; color:#666666; background:none; font-size:15px; list-style:none;}
#vpc .oldc { color:#A3A3A3; }
#vpc_msg { margin:0 0 0 30px; font-size:16px; font-weight:bold; color:black; border: 1px solid #808080; }
.vpc_accion { color:#F09100; font-size:16px; }
.vpc_priv { color:#9F009F; font-size:16px; }
.vpc_yo { color:#2D2D2D; }

/* Mensajes de chat */
#vp_c {  margin: -18px 0 0 -20px; font-family: "Arial", "Helvetica", sans-serif; font-size:17px; }
#vp_c h1 { font-size:19px; color:green; margin:0; padding:0; line-height:12px; }
#vp_c a { color:#06f;text-decoration:none; }
#vp_c a:hover { text-decoration:underline; }
#vp_c h1 a { color:#4BB000; } 

/* Lista de usuarios */
#vpc_u { float:right; width:180px; height:450px; white-space:nowrap; overflow:auto; overflow-x:hidden; margin-left:20px; }
#vpc_u ul { padding:15px 0; margin:0; position:static; }
#vpc_u ul li { padding:0; margin:0; color:#666666; background:none; font-size:18px; font-weight:bold; list-style:none;}
#vpc_u li { font-weight:bold; }
#vpc_u a { color:#808080; text-decoration:none; }
#vpc_u a:hover { text-decoration:underline; }




/*** CONTENIDO FORO ***/
.code {
	background-color:#000;
	color: #5F5;
	font-family:monospace;
}

.quote {
	background: url(bg75.png) repeat;
	border: #000000 solid thin;
	border-radius: 12px; 
	font-family:serif;
	font-style:italic;
	padding: 6px;
}

blockquote cite {
	font-style: normal;
	font-weight: bold;
	display: block;
	font-size: 0.9em;
	margin-bottom: 0;
}

.citar {
	float: right;
}
.citar input {
	font-size: 0.7em;
}

.pforo { 
	border-top: 1px solid #D4D4D4; 
}




/*** CONTENIDO DOCS ***/
#doc_pad { text-align:justify; margin:20px; } /* Conjunto de hacks para normalizar el codigo html generado por Etherpad-lite */
#doc_pad ul, #doc_pad ol { margin:4px 0 -4px 0; }
#doc_pad li { text-align:left; margin:3px 0 4px 0; }
#doc_pad ol ol { list-style-type: lower-roman; }
.indent { list-style-type:none; }



/*** CONTENIDO AYUDA ***/
.ayuda {
display:inline-block;
cursor:help;
background-image:url('varios/help.png');
width:22px;
height:22px;
}

.ayudap {
margin:-10px 0 0 -90px;
position:absolute;
background:#FFFF95;
font-size:14px;
color:#666;
border:2px solid #FFFFFF;
cursor:help;
max-width:350px;
min-height:50px;
padding:6px 8px;
z-index:10;
}
.ayudap {
border-radius:6px;
-moz-border-radius:6px;
-webkit-border-radius:6px;
}



/*** HACKS (DOCUMENTAR) ***/
strong, b {color:inherit;background:none;padding:0px;} /* Para anular una rareza de kickstart */

</style>
<?=$txt_header?>
</head>
<body>


<div id="content-left">
	
	<a href="http://www.virtualpol.com"><img src="<?=IMG?>media/logo-virtualpol-1_200.gif" width="200" height="60" alt="VirtualPol" /></a>
	
	<ul class="menu vertical">

	<li id="menu-comu"<?=($txt_menu=='comu'?' class="menu-sel"':'')?>><a href="/">Comunicación</a>
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

	<li id="menu-info"<?=($txt_menu=='info'?' class="menu-sel"':'')?>><a href="#">Información</a>
			<ul>
				<li><a href="/info/censo/">Censo<span class="md"><?=num($pol['config']['info_censo'])?></span></a></li>
				<li><a href="/doc/">Documentos<span class="md"><?=$pol['config']['info_documentos']?></span></a></li>
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

	<li id="menu-demo"<?=($txt_menu=='demo'?' class="menu-sel"':'')?>><a href="/votacion/">Democracia</a>
		<ul>
			<li><a href="/elecciones/"><b>Elecciones</b></a></li>
			<li><a href="/votacion/"><b>Votaciones</b><span class="md"><?=$pol['config']['info_consultas']?></span></a></li>
			<li><a href="/control/">Gestión</a>
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
	<li id="menu-econ"<?=($txt_menu=='econ'?' class="menu-sel"':'')?>><a href="#">Economía</a>
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
	<li id="menu-noti" class="menu-sel"><a href="/">Notificaciones<span class="md">2</span></a>
		<ul>
			<li><a href="/?noti=8037">Mensaje privado de FritzDiogenes<span class="md">5</span></a></li>
			<li><a href="/?noti=16415">Ya puedes afiliarte a un nuevo grupo: PARLAMENTO.</a></li>
			<li><a href="/?noti=16414">Mensaje privado de ethos</a></li>
			<li><a href="/?noti=8004">Mensaje privado de GONZO<span class="md">9</span></a></li>
			<li><a href="/?noti=11858">Mensaje privado de Koba</a></li>
			<li><a href="/?noti=11841">Mensaje privado de kir</a></li>
			<li><a href="/hacer">¿Qué hacer?</a></li>
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

<?php
if (ECONOMIA) {
	echo '<a href="/mapa/" class="gris" style="float:right;">Mapa</a><a href="/subasta/" class="gris">Subasta</a>';
	if (!isset($cuadrado_size)) {
		$cuadrado_size = 12;
		include('inc-mapa.php');
		echo '<div style="margin:0 auto;">'.$txt_mapa.'</div>';
	}
}
?>

	</div>
</div>




<div id="content-right">

	<div id="header">

		<div id="header-logo">
			<a href="/"><img src="<?=IMG?>banderas/<?=PAIS?>_60.gif" height="50" border="0" /></a>
			<span id="header-logo-p"><?=$pol['config']['pais_des'].', '.PAIS?></span>
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
	echo '<a href="/perfil/'.$pol['nick'].'">'.$pol['nick'].($pol['cargo']!=0&&$pol['cargo']!=99?' <img src="'.IMG.'cargos/'.$pol['cargo'].'.gif" border="0" width="16" height="16" />':'').'</a>'.($pol['estado']!='ciudadano'?' (<b class="'.$pol['estado'].'">'.ucfirst($pol['estado']).'</b>)':'').(ECONOMIA?' | <a href="/pols/"><b>'.pols($pol['pols']).'</b> '.MONEDA.'</a>':'').' | <a href="/msg/" title="Mensajes Privados (MP)"><span class="icon blue medium" data-icon="@"></span></a> |'.$txt_elec.' <a href="/accion.php?a=logout">Salir</a>';
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

		<div id="footer-right">
			<p><b><a href="http://www.virtualpol.com/">VirtualPol</a> &nbsp; Red Social Democrática</b></p>
			<p>
			<a target="_blank" href="http://www.virtualpol.com/video">Video</a> | <a target="_blank" href="http://www.virtualpol.com/desarrollo">Desarrollo</a> | <a target="_blank" href="http://www.virtualpol.com/documentacion">Documentación</a><br />
			<a target="_blank" href="http://www.virtualpol.com/TOS">Condiciones de Uso / Información legal</a><br /> 
<?php
unset($txt);
echo ($pol['user_ID']==1?'<b>'.num((microtime(true)-TIME_START)*1000).'</b>ms '.num(memory_get_usage()/1000).'kb | ':'');
?>
			2008-2012</p>
		</div>
		
		<div id="footer-left">
<?php
echo '<p><b>'.PAIS.', '.$pol['config']['pais_des'].'</b></p>';

if ((isset($pol['user_ID'])) AND ($pol['config']['palabra_gob'] != ':') AND ($pol['config']['palabra_gob'] != '')) {
	echo '<div class="azul"><b><a href="http://'.explodear(':', $pol['config']['palabra_gob'], 1).'">'.explodear(':', $pol['config']['palabra_gob'], 0).'</a></b></div>';
}

if (!ASAMBLEA) {
	echo '<br /><br /><div class="amarillo" id="pols_frase"><b>'.$pol['config']['pols_frase'].'</b></div>';
	if ($pol['config']['pols_fraseedit'] == $pol['user_ID']) { echo ' <a href="/subasta/editar/" class="gris">#</a>'; }
}
?>	
		</div>
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