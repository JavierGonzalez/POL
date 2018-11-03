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








//**************************************************************************/
$txt .= mysql_error();
$txt_title = 'Test';
$txt_nav = array('Test');
include('theme.php');
?>