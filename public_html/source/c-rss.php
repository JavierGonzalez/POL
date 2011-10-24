<?php
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
Comunidad de usuarios sin admins, gestionada democráticamente por un simulador político.
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