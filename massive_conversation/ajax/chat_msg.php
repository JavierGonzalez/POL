<?php


for ($i=0;$i<10;$i++) {
    echo mt_rand(0,100).'<br />';
}

echo '<br />';

$result = sql_old("SELECT * FROM msg ORDER BY msg_id ASC");
while ($r = r($result)) {
    echo date('H:m:s', strtotime($r['fecha_creacion'])).'&nbsp; '.$r['texto'].'<br />';
}
