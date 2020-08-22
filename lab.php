<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


//print phpinfo();  exit;

//mail('gonzomail@gmail.com', 'Prueba de email 43', 'Mensaje de prueba '.date());
//mail('gonzo@virtualpol.com', 'Prueba de email 43', 'Mensaje de prueba '.date());


echo 'Email enviado';



$to      = 'gonzomail@gmail.com';
$subject = 'Test de email '.rand(1000,9999);
$message = 'hello probandooo';
$headers = 'From: virtualpol.com@virtualpol.com' . "\r\n" .
    'Reply-To: desarrollo@virtualpol.com';

$success = mail($to, $subject, $message, $headers);

if (!$success) {
    $errorMessage = error_get_last()['message'];
}


exit;

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