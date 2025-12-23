<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 




$text = 'ñé
ok6';

___( sql_old("UPDATE ".SQL."foros_msg SET text = '".$text."' WHERE ID = '28748' LIMIT 1") );


$result = sql("SELECT * FROM pol_foros_msg WHERE ID = 28748");
___($result);


exit;



$result = sql_old("UPDATE users SET estado = 'ciudadano' WHERE nick = 'sir2100'");
___($result);


___(sql_error());

$result = sql_old("SELECT * FROM users WHERE nick = 'sir2100'");
echo sql_error();
while($r = r($result)) {
    ___($r);
    
}

//






exit;

$result = sql_old("SELECT msg_ID, nick FROM chats_msg WHERE nick LIKE '%&rarr;%'");
while($r = r($result)) {
    $nick_sender = explode('&rarr;', $r['nick'])[0];
    echo $r['msg_ID'].' - '.$r['nick'].' - '.$nick_sender.'<br />';


    sql("UPDATE chats_msg SET nick_sender = '".$nick_sender."' WHERE msg_ID = '".$r['msg_ID']."' LIMIT 1");
}






exit;

$comprobante = mt_rand(1000000000,9999999999);


__($comprobante);

exit;


$file = '404.php';

__();
__();
__( (is_file($file) AND fnmatch('*.*', $file)) );
__();
__( (fnmatch('*.*', $file) AND is_file($file)) );
__();
__( (is_file($file) AND fnmatch('*.*', $file)) );
__();







exit;

__($maxsim);

__($_GET);


__($_REQUEST);

__(dirname($maxsim['app']));







exit;




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

$result = sql_old("SELECT ID, pais, nick, email FROM users WHERE nick = 'GONZO' LIMIT 1");
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