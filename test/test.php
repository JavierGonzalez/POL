<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


exit;


function crono($new='') { global $crono; $the_ms = num((microtime(true)-$crono)*1000); $crono = microtime(true); return '<h3>'.$the_ms.'ms '.$new.'</h3></hr>'; }
$result = sql("SELECT valor, dato FROM config WHERE pais = '".PAIS."' AND autoload = 'no'");
while ($r = r($result)) { $pol['config'][$r['dato']] = $r['valor']; }
echo ' ';
/***************************************************************************/








//**************************************************************************/
echo mysqli_error($link);
$txt_title = 'Test';
$txt_nav = array('Test');
