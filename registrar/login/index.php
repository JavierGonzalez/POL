<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 



echo '<div style="width:380px;margin:0 auto;">';

if (isset($pol['user_ID'])) {
	echo '<p>'._('Ya estás logueado correctamente como').' <b>'.$pol['nick'].'</b>.</p>';
} else {
	echo '
<script type="text/javascript" src="'.IMG.'lib/md5.js"></script>
<script type="text/javascript">
timestamp_start = Math.round(+new Date()/1000);
everc_value = "";
function login_start() {
$("#boton_iniciar_sesion").html("'._('Iniciando sesión...').'");
timestamp_end = Math.round(+new Date()/1000);
$("#input_extra").val(screen.width + "x" + screen.height + "|" + screen.availWidth + "x" + screen.availHeight + "|" + Math.round(timestamp_end - timestamp_start) + "|" + screen.colorDepth + "|");
//$("#login_pass").val(hex_md5($("#login_pass").val()));
//$("#login_pass").attr("name", "pass_md5");
}
</script>
<style>
#content-right { background:url('.IMG.'bg/verde-cesped.gif); }
</style>



<form action="/registrar/login/login" method="post">
<input name="url" value="'.($_GET['r']?$_GET['r']:escape(base64_encode('/'))).'" type="hidden" />
<input type="hidden" name="extra" value="" id="input_extra" />

<fieldset><legend>'._('Iniciar sesión').'</legend>

<table border="0" style="margin:10px auto;">

<tr>
<td align="right">'._('Usuario o email').':</td>
<td><input name="user" value="" size="16" maxlength="200" type="text" style="font-size:20px;font-weight:bold;" autocomplete="off" autofocus required /></td>
</tr>

<tr>
<td align="right">'._('Contraseña').':</td>
<td><input id="login_pass" name="pass" type="password" value="" size="16" maxlength="200" style="font-size:20px;font-weight:bold;" required /></td>
</tr>

<tr>
<td align="center" colspan="2"><input type="checkbox" name="no_cerrar_sesion" value="true" id="no_cerrar_sesion" /> <label for="no_cerrar_sesion" class="inline">'._('No cerrar sesión en 30 días').'.</label></td>
</tr>

<tr>
<td colspan="2" align="center">

'.($_GET['error']?'<p style="color:red;"><b>'.escape(base64_decode($_GET['error'])).'</b></p>':'').'

<button onclick="login_start();" class="large blue" id="boton_iniciar_sesion">'._('Iniciar sesión').'</button><br />
<br />
<a href="/registrar/login/recuperar-pass">'._('¿Has olvidado tu contraseña?').'</a>
</table>

<p style="color:#888;text-align:center;">'._('Contacto').': <a href="mailto:'.CONTACTO_EMAIL.'" style="color:#888;" target="_blank">'.CONTACTO_EMAIL.'</a></p>
</fieldset>

</form>';
}

echo '</div>';

$txt_title = _('Iniciar sesión');
$txt_nav = array(_('Iniciar sesión'));
