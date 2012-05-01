<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

include('inc-login.php');
include('inc-functions-accion.php');
if ($pol['user_ID'] != 1) { exit; }
function crono($new='') {
	 global $crono;
	 $the_ms = num((microtime(true)-$crono)*1000);
	 $crono = microtime(true);
	 return '<h3>'.$the_ms.'ms '.$new.'</h3></hr>';
}



exit;

// ENVIO DE EMAILS DE AVISO

$emails_enviados = 0;
$result = mysql_query("SELECT ID, nick, email FROM users WHERE estado = 'ciudadano' AND email != '' LIMIT 100000", $link);
while($r = mysql_fetch_array($result)) {

		$txt_email = '<p>Hola '.$r['nick'].'!</p>
	
<p>Como debes saber, completamos con éxito la primera ronda de financiación de crowdfunding (Goteo.org) para expandir VirtualPol. Hemos comenzado la segunda y última ronda.</p>

<p>Con tu ayuda cumplirémos el objetivo final: La expansión internacional de VirtualPol y grandes avances en el desarrollo del código fuente.</p>

<p>Desde el 6 de Abril se han realizado los siguientes desarrollos gracias a tu apoyo:</p>

<ul>

<li><b>Traducción a los principales idiomas</b>: este es el mayor desarrollo (junto con el nuevo diseño). Vamos a buen ritmo, aproximadamente el 22% del trabajo ya está hecho, se terminará durante la segunda ronda. Puedes probarlo <a href="https://virtualpol.com/registrar/login.php?a=panel">cambiando tu idioma</a>. Además la traducción -propiamente dicha- es colaborativa, <a href="https://www.transifex.net/projects/p/virtualpol/resource/virtualpol/">puedes ver el progreso y ayudar aquí</a>.</li>

<li><b>Nuevo sistema de Elecciones</b>: Re-diseñado desde cero. Con muchos avances: más simple, más seguro, más eficiente, voto preferencial (más democrático y preciso), modificación de voto, comprobantes de voto, elecciones independientes para cada cargo totalmente configurables y además <a href="http://15m.virtualpol.com/elecciones">resultados históricos completos</a>. Todo totalmente automático.</li>

<li><b>Cadena de sucesión automática</b>: de forma que si una persona dimite de un cargo electo el sistema asigna al siguiente más votado en las útimas elecciones. De forma 100% automática, así la democracia está garantizada. <a href="http://15m.virtualpol.com/cargos/6">Ejemplo visual de la cadena de sucesión</a>.</li>

<li><b>Comprobantes de voto</b>: te permite verificar en cualquier momento que tu voto ha sido computado correctamente -más allá de toda duda-. Esto aporta una enorme seguridad y transparencia en todas las votaciones y elecciones de VirtualPol.</li>

<li><b>Creación de plataformas</b>: para realizar esto fue necesario reestructurar la base de datos y modificar gran parte del código. Esto permite solicitar la creación de <a href="http://www.virtualpol.com/crear-plataforma.php">nuevas plataformas</a>.</li>

</ul>

<p>Todos estos desarrollos (y muchos más) se han realizado por anticipado, antes si quiera de empezar el plazo prometido. Nuestro compromiso con los participantes de VirtualPol es absoluto. Gracias por hacer esto posible.</p>

<p><a href="http://www.goteo.org/project/expansion-de-virtualpol" style="font-size:18px;"><b>¡Contribuye donando, impulsa VirtualPol!</b></a> (participarás en la segunda y última ronda)</p>

<p>Un fuerte abrazo.</p>

<p>_____<br />


Javier González González,<br />
VirtualPol <a href="http://www.virtualpol.com">http://www.virtualpol.com</a><br />
</p>';

		enviar_email($r['ID'], '¡Segunda y última ronda de donaciones!', $txt_email); 
		$emails_enviados++;

		$txt .= $votar_num.' '.$r['nick'].'<br />';

}

$txt .= '<hr />'.$contador;




$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>