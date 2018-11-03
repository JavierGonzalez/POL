<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');




if ($pol['user_ID']) {

	$txt .= '<p>Hola '.$pol['nick'].':</p><p>Este formulario es para informar al sistema VirtualPol de que has donado. De esta forma tu donación constará en tu perfil de usuario y además el sistema podrá darte las recompensas adquiridas por donar (por ejemplo cuenta vitalicia).</p>';

	$donacion = null;
	$result = sql("SELECT donacion FROM users WHERE ID = '".$pol['user_ID']."' LIMIT 1");
	while ($r = r($result)) { $donacion = $r['donacion']; }

	$txt .= '
<form action="'.accion_url($pol['pais']).'a=donacion" method="post">
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