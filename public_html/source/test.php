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
if ($pol['user_ID'] != 1) { redirect('http://www.virtualpol.com'); }
function crono($new='') { global $crono; $the_ms = num((microtime(true)-$crono)*1000); $crono = microtime(true); return '<h3>'.$the_ms.'ms '.$new.'</h3></hr>'; }
$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }
$txt .= ' ';
/***************************************************************************/


$result2 = sql("SELECT nick, email FROM users WHERE pais = '15M' AND estado = 'ciudadano' AND email != '' ORDER BY ID ASC LIMIT 1");
while($r2 = r($result2)){ 
	$mensaje = '<p>Hola '.$r2['nick'].':</p>

<p>Con el fin de dinamizar los proyectos de los Grupos de Trabajo de la Asamblea Virtual necesitamos vuestra colaboración. Podéis hacerlo entrando en el siguiente enlace para rellenar un breve formulario con el que podremos saber la disponibilidad de cada uno para colaborar en los proyectos en curso:</p>

<p><a href="https://docs.google.com/spreadsheet/viewform?formkey=dHV0VE1LckpTRmV6NzdOQXpTRERQakE6MQ"><b style="font-size:18px;">Rellenar formulario</b></a></p>

<p>Podéis encontrar los proyectos <a href="http://15m.virtualpol.com/doc/00---proyectos-en-curso">aquí</a></p>

<p>También os recordamos que depende de todos nosotros mantener ese documento actualizado.</p>

<p>Asamblea Virtual 15M</p>';
	enviar_email(null, 'Formulario sobre Grupos de Trabajo de la Asamblea Virtual', $mensaje, $r2['email']);
	$txt .= $r2['nick'].'<br />';
}



//**************************************************************************/
$txt .= mysql_error();
$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>