<?php


include('inc-login.php');
include('inc-functions-accion.php');

$result = sql("SELECT ID, pais, nick, email FROM users WHERE nick = 'GONZO' LIMIT 1");
// WHERE estado = 'turista'


while($r = r($result)) {
	$mensaje = '<p>Hola ciudadano '.$r['nick'].':</p>

<p>Nos hemos vuelto a reunir en Pol. Eres bienvenido.</p>

<p>Las primeras elecciones comienzan el proximo viernes 6 de septiembre a las 20h hora de Madrid.</p>

<p><a href="http://pol.virtualpol.com"><b style="font-size:20px;"><b>pol.virtualpol.com</b></a><br />
El Pueblo Virtual</p>';

    echo $r['email'].' ---- '.$mensaje.'<hr />';

	enviar_email(false, 'Empezamos de cero en POL, '.$r['nick'].' eres bienvenido!', $mensaje, $r['email']);

}