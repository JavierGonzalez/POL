<?php
include('inc-login.php');

$dnie_autentificado = false;
$result = mysql_query("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND dnie = 'true'", $link);
while ($r = mysql_fetch_array($result)) { $dnie_autentificado = true; }

if ((isset($pol['user_ID'])) AND ($dnie_autentificado == false)) { // Es un usuario y no está autentificado con DNIe 

	require('img/tractis_identity/tractis_identity.php');

	$tractis_identity = new tractis_identity(CLAVE_API_TRACTIS, 'http://www.virtualpol.com/dnie.php', 'false', 'http://www.virtualpol.com/img/tractis_identity/images/trac_but_bg_lrg_b_es.png');

	if ($data = $tractis_identity->check_auth()) { // Redireccion desde Tractis tras identificacion correcta.

		// EJEMPLO: hash('sha256', '.VirtualPol.clave_del_sistema.72135000A.JAVIER GONZALEZ GONZALEZ.');
		
		$dnie_check = hash('sha256', '.VirtualPol.'.CLAVE_DNIE.'.'.strtoupper($data['tractis:attribute:dni']).'.'.str_replace('+', ' ', strtoupper($data['tractis:attribute:name'])).'.');
		
		unset($data); // elimina todos los datos del DNIe y no se vuelven a tratar de ninguna forma.

		mysql_query("UPDATE users SET dnie = 'true', dnie_check = '".$dnie_check."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);

		$txt .= 'La autentificaci&oacute;n ha sido realizada correctamente.';
	} else { // Autentificar.
		$txt .= 'Usuario sin autentificar.<br />'.$tractis_identity->show_form();
	}

} elseif (isset($pol['user_ID'])) {
	$txt .= 'Est&aacute;s autentificado correctamente.';
} else {
	$txt .= 'No est&aacute;s registrado.';
}

$txt = '
<style type="text/css">.content { text-align:center; width:500px; margin: 0 auto; padding: 2px 12px 30px 12px; }</style>

<h1>Autentificaci&oacute;n DNIe (FASE ALPHA)</h1>

<p>La autentificaci&oacute;n mediante DNIe (y otros certificados) es el futuro, pues permite identificar a una persona fisica con seguridad.</p>

<p>Estado: <b>'.$txt.'</b></p>

<p>&nbsp;</p>

<p><em>Seguridad:</em> no se almacenar&aacute; ningun dato proporcionado por el DNIe u otro certificado en ninguna parte del sistema. Tan solo se almacena una miniaturizaci&oacute;n irreversible de esta informaci&oacute;n. De esta forma incluso ante el peor ataque posible (acceso a contrase&ntilde;as, claves y base de datos) no se podr&iacute;a obtener informaci&oacute;n alguna. La pasarela de autentificaci&oacute;n se conf&iacute;a totalmente a una empresa importante del sector llamada Tractis.</p>';

include('theme.php');
?>




