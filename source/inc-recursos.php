<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/
$txt_recursos .= '<div id="currencies" class="currencies"><ul>';

$result = mysql_query("SELECT ur.quantity, r.name, r.icon from resources r, user_resources ur where ur.resource_id=r.id and ur.user_id=".$pol['user_ID']." and r.type='currency'");

while($r = mysql_fetch_array($result)) {
    $txt_recursos .= '<li><img src='.IMG.'/'.$r['icon'].' alt="'.$r['name'].'">'.$r['quantity'].'</li>'; 
}


$txt_recursos .='
</ul></div><div id="msg" class="amarillo"></div>';
?>
