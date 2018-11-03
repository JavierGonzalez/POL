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

// ENVIO DE EMAILS DE AVISO

if (false) {

	$emails_enviados = 0;
	$result = sql("SELECT ID, nick, email FROM users WHERE estado != 'expulsado' LIMIT 1");
	while($r = r($result)) {

		$txt_email = '<p>Hola '.$r['nick'].'!</p>
		
<p>Me llamo Javier González, fundador de <a href="http://www.virtualpol.com">VirtualPol</a> proyecto en el que participas. Quedan 5 días para completar la campaña de crowdfunding (financiación en masa).</p>

<p>VirtualPol ya tiene más de 3.150 participantes. Destaca el éxito de la <a href="http://15m.virtualpol.com">Asamblea Virtual 15M</a>. Esta plataforma de VirtualPol crece en participación y cada vez se organiza mejor. Es la asamblea del 15M más grande de España actualmente y además la más democrática y transparente.</p>

<p>Habría sido imposible lograrlo sin las donaciones y el apoyo al proyecto VirtualPol. Gracias a ello hemos podido mejorar el sistema y llegar cada vez más lejos.</p>

<p><a href="http://www.goteo.org/project/expansion-de-virtualpol/home"><b style="font-size:20px;">¡Aún puedes contribuir impulsando VirtualPol!</b> (Crowdfunding en goteo.org)</a></p>

<p>Gracias por vuestro intenso apoyo y trabajo. Llegarémos lejos, seguid así, ¡sois geniales!</p>

<p>Un abrazo.</p>

<p>_____<br />
Javier González González<br />
<a href="http://www.virtualpol.com"><b>VirtualPol</b></a> La primera Red Social Democrática
</p>';
		$txt_titulo = 'Quedan 4 días de campaña de donaciones';

		enviar_email($r['ID'], $txt_titulo, $txt_email); 
		$emails_enviados++;

		$txt .= $votar_num.' '.$r['nick'].'<br />';
	}
}

$txt .= '<hr />'.$contador;
include('theme.php');
?>