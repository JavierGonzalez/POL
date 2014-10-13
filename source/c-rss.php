<?php
/* The source code packaged with this file is Free Software, Copyright (C) 2008 by
** Javier González González <desarrollo AT virtualpol.com> <gonzomail AT gmail.com>
** It's licensed under the GNU GENERAL PUBLIC LICENSE v3 unless stated otherwise.
** You can get copies of the licenses here: http://www.gnu.org/licenses/gpl.html
** The source: http://www.virtualpol.com/codigo - TOS: http://www.virtualpol.com/TOS
** VirtualPol, The first Democratic Social Network - http://www.virtualpol.com
*/

exit; // CODIGO EXTRAÑO. REVISAR ANTES DE ACTIVAR.

include('inc-login.php');
// Pillamos la constante SQL y la ponemos en una variable, para referenciarla
// dentro de sintaxis Heredoc.
$plataformaSQL=SQL;
$plataforma=PAIS;
// Escribimos la query---
$query = <<< QUERY
SELECT
    foro.title as foro,
    hilo.url as url,
    user.nick as autor,
    hilo.title as hilo,
    hilo.time as hora,
    hilo.text as texto,
    hilo.cargo as cargo
FROM
    {$plataformaSQL}foros_hilos AS hilo,
    {$plataformaSQL}foros AS foro,
    users AS user,
WHERE
    hilo.sub_ID = foro.ID
    AND hilo.user_ID = user.ID
ORDER BY time DESC
LIMIT 20
QUERY;
// La ejecutamos
$rst = mysql_query($query);
$array = mysql_fetch_array($rst);

$fecha=date("r");// Pillamos la fecha de generación del archivo
// pintamos lo que toca
echo <<< print
<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:georss="http://www.georss.org/georss" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
<channel>
<title>$plataforma - Hilos en el foro</title>
<atom:link href="http://www.meneame.net/rss/" rel="self" type="application/rss+xml"/>
<link>http://virtualpol.com</link>
<image>
<title>$plataforma</title>
<link>http://www.virtualpol.com/</link>
<url>http://www.virtualpol.com/img/banderas/{$plataformaSQL}60.gif</url>
</image>
<description>
Red social democrática
</description>
<pubDate>$fecha</pubDate>
<generator>http://desarrollo.virtualpol.com/</generator>
<language>es</language>
print;

// y ahora, para finalizar, el bucle.
foreach ($rst as $key=>$value){
    echo <<< print
<item>
<title>$hilo</title>
<link>$url</link>
<comments>$url</comments>
<pubDate>$hora</pubDate>
<dc:creator>$autor</dc:creator>
<guid>$url</guid>
<description>
<![CDATA[
$texto
]]>
</description>
</item>
print;
}
// Y.... cerramos el archivo
echo <<< print
</channel>
</rss>
print;

?>