<?php


foreach (sql("SELECT * FROM msg ORDER BY msg_id ASC") AS $r)
    echo '<span class="right">'.$r['points'].'</span> <span class="boton_votar" onclick="votar();">+</span> <span class="boton_votar" onclick="votar();">-</span> '.$r['text'].'<br />';

