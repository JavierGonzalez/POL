<?php 
include('inc-login.php');




if ($pol['user_ID']) {

	$txt .= '<p>Hola '.$pol['nick'].':</p><p>Este formulario es para informar al sistema VirtualPol de que has donado. De esta forma tu donación constará en tu perfil de usuario y además el sistema podrá darte las recompensas adquiridas por donar (por ejemplo cuenta vitalicia).</p>';

	$donacion = null;
	$result = sql("SELECT donacion FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	while ($r = r($result)) { $donacion = $r['donacion']; }

	$txt .= '
<form action="http://'.strtolower($pol['pais']).'.'.DOMAIN.'/accion.php?a=donacion" method="post">
Por favor indica la cantidad exacta que has donado a VirtualPol: <input style="text-align:right;font-weight:bold;" type="text" name="donacion" size="1" maxlength="4" value="'.($donacion?$donacion:0).'" /> euros '.boton('Guardar', 'submit', false, 'blue small').'
</form>

<p>Esto es todo. Te mantendré informado. ¡Gracias!</p>';




} else {
	$txt .= '<p style="color:red;"><b>Debes entrar con tu ciudadano de VirtualPol.</b> Si ya tienes un usuario pulsa en el boton ENTRAR (arriba derecha).</p>';
}






$txt_title = 'Donaciones';
$txt_nav = array('Donaciones');
include('theme.php');
?>