<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

// Conecta con la base de datos, define constantes, carga el sistema de usuarios de VP, hace un par de gestiones rutinarias.
include('inc-login.php');

if (!$_SERVER['HTTPS']) { redirect(SSL_URL.'dnie.php'); } // Fuerza el uso de una conexion segura entre navegador y servidor. (https SSL)

// Comprueba si el usuario está autentificado o no.
$dnie_autentificado = false;
$result = mysql_query("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND dnie = 'true'", $link);
while ($r = mysql_fetch_array($result)) { $dnie_autentificado = true; }

if ((isset($pol['user_ID'])) AND ($dnie_autentificado == false)) {
	// Es un usuario y no está autentificado con DNIe 
	
	// Plugin de Tractis para PHP. Software Libre. Fuente: https://github.com/tractis/tractis_identity_verifications_for_php
	require('img/lib/tractis_identity/tractis_identity.php');
	$tractis_identity = new tractis_identity(CLAVE_API_TRACTIS, SSL_URL.'dnie.php', 'false', IMG.'lib/tractis_identity/images/trac_but_bg_lrg_b_es.png', 'POST');

	// Trata la redireccion desde Tractis tras una autentificacion correcta.
	if ($data = $tractis_identity->check_auth()) { 

/* LA SIGUIENTE LINEA ES EL QUID DE LA CUESTION

Consiste en una miniaturización irreversible de la información extraida del DNIe (proveeida por la pasarela de Tractis), junto con otra información estatica para evitar ataques por diccionario y hacer inéditos los hash. Se hace mediante el algoritmo de hash "sha256".

Por ejemplo, este código: 

	hash('sha256', '.VirtualPol.clave_del_sistema.72135000A.JAVIER RUBALCABA RAJOY.')

Se convierte en:

	da39a3ee5e6b4b0d3255bfef95606b4b0d3255bfef95601890afdd80709

Este resultado final no supone ninguna información en claro.
*/
		$dnie_check = hash('sha256', '.VirtualPol.'.CLAVE_DNIE.'.'.strtoupper($data['tractis:attribute:dni']).'.'.str_replace('+', ' ', strtoupper($data['tractis:attribute:name'])).'.'); // Generacion del hash anteriormente explicado.
		
		// Elimina todos los datos obtenidos del DNIe y desaparecen definitivamente a partir de aquí.
		unset($data);

		// Busca checks coincidentes (para garantizar que cada DNIe se inscribe una vez).
		$dnie_clon = false;
		$result = mysql_query("SELECT ID FROM users WHERE dnie_check = '".$dnie_check."' AND dnie = 'true' LIMIT 1", $link);
		while ($r = mysql_fetch_array($result)) { $dnie_clon = true; }

		if ($dnie_clon == true) { 
			// Persona ya identificada con otro usuario. No realiza la autentificacion. 
			$txt .= 'Ya estas autentificado con otro usuario. Envia un email a '.CONTACTO_EMAIL.' explicando la situacion. Gracias.';
			
			unset($dnie_check); // Elimina el hash.

		} else {
			// Autentificacion correcta. El DNIe es inedito. Procede a guardar el hash en la base de datos.
			mysql_query("UPDATE users SET dnie = 'true', dnie_check = '".$dnie_check."' WHERE ID = '".$pol['user_ID']."' LIMIT 1", $link);

			unset($dnie_check); // Elimina el hash.

			// Carga funciones extra para ejecutar evento_chat() que envia un mensaje en el chat.
			include('source/inc-functions-accion.php');
			evento_chat('<b>[#] '.crear_link($pol['nick']).' se ha <a href="'.SSL_URL.'dnie.php">autentificado</a> con exito.</b>', '0', '', false, 'e', $pol['pais']);

			// Cierra y redirige a esta misma pagina.
			redirect(SSL_URL.'dnie.php');
		}

	} else { 
		// Muestra controles para autentificar.
		$txt .= 'Usuario sin autentificar.<br />'.$tractis_identity->show_form(); // Muestra el boton de autentificación de Tractis.
	}
} elseif (isset($pol['user_ID'])) {
	$txt .= 'Estás autentificado correctamente.';
} else {
	$txt .= 'No estás registrado, debes crear un usuario.';
}


// Presentacion, diseño y poco más. No relevante.
$txt_title = 'Autentificación DNIe'; // Define el titulo de la pagina finalmente formada.
$txt = '
<style type="text/css">.content { text-align:center; width:500px; margin: 0 auto; padding: 2px 12px 30px 12px; }</style>

<h1>Autentificación DNIe</h1>

<p>La autentificación mediante DNIe (y otros certificados) es el futuro, pues permite identificar a una persona fisica con seguridad.</p>

<p>Estado: <b>'.$txt.'</b></p>

<p>&nbsp;</p>

<p><em>Seguridad:</em> no se almacenará ningún dato proporcionado por el DNIe u otro certificado en ninguna parte del sistema. Tan sólo se almacena una miniaturización irreversible de esta información. De esta forma incluso ante el peor ataque posible (acceso a contraseñas, claves y base de datos) no se podría obtener información alguna. La pasarela de autentificación se confía totalmente a una empresa importante del sector llamada <a href="http://www.tractis.com/">Tractis</a>.</p>

<p><a href="/documentacion">Más información aquí.</a></p>

<p>Puedes ver el código fuente de <a href="/desarrollo">esta aplicación</a>.</p>';

// Carga el diseño completo de VirtualPol. Mucho HTML, CSS y poco más.
include('theme.php');
?>