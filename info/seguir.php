<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 





function red_social($red, $ID) {

if ($red == 'twitter') {
    return '<a href="https://twitter.com/'.$ID.'" class="twitter-follow-button" data-show-count="false" data-lang="es" data-size="large">Seguir @'.$ID.'</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
}

}

$txt_title = _('Sala de seguir');
$txt_nav = array(_('Seguir'));

echo '<table border="0">

<tr>
<td align="right" colspan="2"><em>VirtualPol</em></td>
<td>'.red_social('twitter', 'VirtualPol').'</td>
<td></td>
</tr>';


if (ASAMBLEA) {
echo '<tr>
<td align="right" colspan="2"><em>Asamblea Virtual</em></td>
<td>'.red_social('twitter', 'AsambleaVirtuaI').'</td>
<td></td>
</tr>';
}


echo '<tr>
<th>'._('Ciudadano').'</th>
<th>'._('Confianza').'</th>
<th>Twitter</th>
</tr>';

$dias = 1;
$result = mysql_query_old("SELECT ID, nick, datos, voto_confianza
FROM users
WHERE estado = 'ciudadano' AND pais = '".PAIS."' AND datos != ''
ORDER BY voto_confianza DESC
LIMIT 10000", $link);
while($r = mysqli_fetch_array($result)) { 

    $datos_array = explode('][', $r['datos']);

    // TWITTER
    $twitter_ID = false;
    $twitter = false;
    if ($datos_array[1] != '') {
        foreach (explode('/', '/'.$datos_array[1]) AS $elemento) { $twitter_ID = $elemento; }
        $twitter_ID = str_replace('#', '', str_replace('@', '', $twitter_ID));
        if (strlen($twitter_ID) >= 3) { 
            $twitter = red_social('twitter', $twitter_ID); 
        }
    }

    if ($twitter) {
        echo '<tr>
<td align="right">'.crear_link($r['nick']).'</td>
<td align="right">'.confianza($r['voto_confianza']).'</td>
<td>'.($r['ID']==$pol['user_ID']?'':$twitter).'</td>
<td></td>
</tr>';
    }

}
echo '</table>';