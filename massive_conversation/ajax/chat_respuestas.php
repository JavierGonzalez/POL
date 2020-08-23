<?php


$result = sql_old("SELECT * FROM msg ORDER BY msg_id ASC");
while ($r = r($result)) {
    echo '<span class="right">'.$r['puntos'].'</span> <span class="boton_votar" onclick="votar();">+</span> <span class="boton_votar" onclick="votar();">-</span> '.$r['texto'].'<br />';
}

