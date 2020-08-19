<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



/*
$result = sql("SELECT valor, dato FROM config WHERE PAIS IS NULL");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }
*/


foreach ($vp['paises'] AS $pais) {
	$result = sql("SELECT COUNT(ID) AS num FROM users WHERE estado = 'ciudadano' AND pais = '".$pais."'");
	while($r = r($result)) {
		sql("UPDATE config SET valor = '" . $r['num'] . "' WHERE pais = '".strtolower($pais)."' AND dato = 'info_censo' LIMIT 1");
	}
}



if ($pol['estado'] == 'ciudadano') {


	// load config full
	$result = sql("SELECT valor, dato FROM config WHERE pais = '".strtolower($pol['pais'])."' AND autoload = 'no'");
	while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }


	$txt_title = _('Cambiar ciudadanía');
	$txt_nav = array(_('Ciudadanía'));

	echo '<p><b>'._('Actualmente eres ciudadano en la plataforma').' '.$pol['pais'].'</b>.</p>

<blockquote>
<p style="color:red;"><b>'._('Cambiar ciudadanía').' '._('de').' '.$pol['pais'].'</b>:</p>

<ul>
<li>Siempre podrás elegir tu ciudadanía libremente pero recuerda que no puedes crear más de un usuario.</li>
<li>No es necesario tener el estatus de ciudadano para participar (parcialmente) en otras plataformas.</li>
'.($pol['pais']=='Hispania'?'
<li style="color:red;"><b>PERDERAS:</b> tus cuentas bancarias (pero tus monedas), <b>cargos</b>, examenes, <b>votos</b> en elecciones activas en este momento, tus empresas, tu partido, subastas de hoy y todos los derechos de ciudadano.</li>
<li>CONSERVARAS: tus monedas (restando un arancel del <b style="color:red;">'.$pol['config']['arancel_salida'].'%</b>), tu antiguedad, online, mensajes privados, confianza, mensajes en foro... y todo lo dem&aacute;s.</li>
':'').'
</ul>';


if (strtotime($pol['rechazo_last']) < (time() - 21600)) { // 6 horas
	echo '
<form action="/accion/rechazar-ciudadania" method="POST">
<input type="hidden" name="pais" value="'.$pol['pais'].'" />
<p>'.boton(_('Cambiar ciudadanía de la plataforma').' '.$pol['pais'], 'submit', '¿Estás seguro de querer CAMBIAR ciudadanía?', 'pill red').'</p>
</form>';

} else { echo '<p style="color:red;"><b>Solo puedes cambiar tu ciudadanía una vez cada 6 horas...</b></p>'; }

echo '</blockquote>';

} elseif (($pol['estado'] == 'turista') AND ($pol['pais'] != 'ninguno')) {
	$txt_title = 'Registrar: PASO 2 (Solicitar Ciudadania)';
	$txt_nav = array('Crear ciudadano');
	echo '<h1><span class="gris">1. Crear usuario |</span> 2. Solicitar Ciudadan&iacute;a <span class="gris">| 3. Ser Ciudadano</span></h1><hr /><p>Tu solicitud de ciudadanía en '.$pol['pais'].' está en proceso.</p>';

} elseif (($pol['estado'] == 'turista') AND ($pol['pais'] == 'ninguno')) {
	$txt_title = _('Solicitar ciudadanía');
	$txt_nav = array(_('Solicitar ciudadanía'));
	$atrack = '"/atrack/registro/solicitar.html"'; 

	if (!$_GET['pais']) { $_GET['pais'] = $vp['paises'][0]; }

	echo '
<p>'._('Dentro de VirtualPol hay diversas plataformas democraticas que son 100% independientes. Elige en la que quieres participar').'.</p>

<form action="?a=solicitar-ciudadania" method="post">

<fieldset><legend>'._('Elige tu plataforma').'</legend>

<table border="0" cellspacing="4">';
	$n = 0;
	
	$result = sql("SELECT pais, valor AS num FROM config WHERE dato = 'info_censo' ORDER BY ABS(valor) DESC LIMIT 25");
	while($r = r($result)) {

		$pais = $r['pais'];
		$ciudadanos_num = $r['num'];

		// pais_des
		$result2 = sql("SELECT dato, valor FROM config WHERE pais = '".$pais."' AND dato IN ('pais_des', 'tipo')");
		while($r2 = r($result2)) { $pais_array[$r2['dato']] = $r2['valor']; }
		$n++;
		echo '
<tr style="font-size:19px;">
<td valign="middle"><img src="'.IMG.'banderas/'.$pais.'.png" width="80" height="50" border="0" /></td>
<td><input type="radio" name="pais" id="pr_'.$pais.'" value="'.$pais.'"'.($n==1?' checked="checked"':'').' /></td>
<td valign="middle" nowrap="nowrap"><label for="pr_'.$pais.'" style="cursor:pointer;"><b>'.$pais_array['pais_des'].'</b><br /><span class="gris"><b>'.num($ciudadanos_num).'</b> '._('ciudadanos').', '.ucfirst($pais_array['tipo']).'.</span></label></td>
</tr>';
	}

	echo '
<tr>
<td colspan="2"></td>
<td>'.boton(_('Solicitar ciudadanía'), 'submit', false, 'large blue').'</td>
</tr>

</table>

</fieldset>

</form>';

} elseif ($registro_txt) {
	$txt_title = _('Registrar usuario');
	$txt_nav = array(_('Registro'));
	echo $registro_txt;
} else {


	$txt_header .= '
<script type="text/javascript">
$(document).ready(function() {
	$(".password").valid();
	$("#form_crear_ciudadano").validate();
});
</script>
<script type="text/javascript" src="'.IMG.'lib/jquery-validate.password/lib/jquery.validate.js"></script>
<script type="text/javascript" src="'.IMG.'lib/jquery-validate.password/jquery.validate.password.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="'.IMG.'lib/jquery-validate.password/jquery.validate.password.css" />
';

	$atrack = '"/atrack/registro/formulario.html"';
	$txt_title = _('Crear ciudadano');
	$txt_nav = array(_('Crear ciudadano'));

	echo '<form action="?a=registrar'.($_GET['p']?'&p='.$_GET['p']:'').($_GET['r']?'&r='.$_GET['r']:'').'" method="POST" id="form_crear_ciudadano">

<input type="hidden" name="extra" value="" id="input_extra" />
<input type="hidden" name="repid" value="' . $rn . '" />
<input type="hidden" name="crono" value="' . time() . '" />
'.($_GET['p']?'<input type="hidden" name="p" value="'.$_GET['p'].'" />':'').'
'.($_GET['r']?'<input type="hidden" name="r" value="'.$_GET['r'].'" />':'').'



<fieldset><legend>'._('Crear ciudadano').'</legend>


<div style="color:red;font-weight:bold;">'.$verror.'</div>

<table>

<tr>
<td align="right"><b>'._('Nick').'</b>:</td>
<td><input type="text" name="nick" value="'.$nick.'" size="10" maxlength="14" pattern="[A-Za-z0-9_]{3,14}" placeholder="'._('nick').'" required /> '._('Será tu identidad. Sin espacios. Solo letras, numeros y').' "_".</td>
</tr>

<tr>
<td align="right"><b>'._('Email').'</b>:</td>
<td><input type="email" name="email" value="'.$email.'" size="30" maxlength="50" placeholder="'._('tu_direccion@email.com').'" required /> '._('Recibirás un email de verificación. No se enviará spam').'.</td>
</tr>

<tr>
<td align="right" valign="top"><b>'._('Contraseña').'</b>:</td>
<td>

<div class="password-meter" style="white-space:nowrap;margin-bottom:4px">
	<div class="password-meter-message">&nbsp;</div>
	<div class="password-meter-bg">
		<div class="password-meter-bar"></div>
	</div>
</div>

<input id="pass1" class="password" type="password" autocomplete="off" name="pass1" value="" maxlength="40" required /><br />
<input id="pass2" type="password" autocomplete="off" name="pass2" value="" maxlength="40" style="margin-top:1px;" required /> '._('Introduce otra vez').'.</td>
</tr>


<tr>
<td></td>
<td><b>'._('¿Compartes conexión a Internet con otro usuario de VirtualPol?').'</b><br />
'._('En caso afirmativo indica el nick').': <input type="text" name="nick_clon" value="" size="10" maxlength="14" pattern="[A-Za-z0-9_]{0,14}" /> '._('En caso negativo dejar vacío').'.</td>
</tr>


<tr>
<td></td>
<td><br /><span style="font-size:18px;">Cosas que debes saber:</span>
<ul style="margin:0;">
	<li><b>VirtualPol es tuyo.</b> Este proyecto lo construimos entre todos. Es software libre, gratuito, sin publicidad y está <u>al servicio del procomún</u>.</li>
	<li><b>Es la primera red social democrática.</b> Una herramienta <u>pionera</u> que desde 2008 da soporte a diversas plataformas independientes entre sí.</li>
	<li><b>Todos los usuarios son iguales.</b> Es la primera comunidad de internet <u>sin administradores</u> privilegiados. Cualquier usuario se puede involucrar en la gestión en absoluta igualdad de condiciones.</li>
	<li><b>Democrático.</b> Todo se determina mediante mecanismos <u>genuinamente democráticos</u> (votaciones, elecciones, etc). El sistema es automático y esto garantiza que nadie puede acaparar el control.</li>
	<li>Hay 4 lineas de participación:
	<ol style="margin:0;">
		<li><b>Chat</b>: para conocerse.</li>
		<li><b>Foro</b>: para debatir en profundidad.</li>
		<li><b>Votaciones</b>: para tomar decisiones.</li>
		<li><b>Grupos de Trabajo</b>: para actuar y llevar las decisiones a la realidad.</li>
	</ol>
	</li>
</ul>
</td>
</tr>



<tr>
<td></td>
<td><input type="checkbox" name="condiciones" value="ok" id="checkcondiciones" required /> <label for="checkcondiciones"><b>'._('Aceptas las').' <a href="/TOS" target="_blank">'._('Condiciones de Uso de VirtualPol').' (TOS)</a>.</b></label></td>
</tr>

<tr>
<td></td>
<td><button onclick="login_start();" class="large blue">'._('Crear ciudadano').'</button></td>
</tr>

</table>

</fieldset>

</form>

<script type="text/javascript" src="'.IMG.'lib/md5.js"></script>
<script type="text/javascript">
timestamp_start = Math.round(+new Date()/1000);
function login_start() {
	timestamp_end = Math.round(+new Date()/1000);
	$("#input_extra").val(screen.width + "x" + screen.height + "|" + screen.availWidth + "x" + screen.availHeight + "|" + Math.round(timestamp_end - timestamp_start) + "|" + screen.colorDepth + "|");
}
</script>';

}