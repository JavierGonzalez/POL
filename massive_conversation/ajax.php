<?php


unset($maxsim['output']);

if ($_REQUEST['a']=='chat_msg') {


    for ($i=0;$i<10;$i++) {
        echo mt_rand(0,100).'<br />';
    }
    
    echo '<br />';

    $result = sql_old("SELECT * FROM msg ORDER BY msg_id ASC");
    while ($r = r($result)) {
    	echo date('H:m:s', strtotime($r['fecha_creacion'])).'&nbsp; '.$r['texto'].'<br />';
    }

}



if ($_REQUEST['a']=='chat_respuestas') {

    $result = sql_old("SELECT * FROM msg ORDER BY msg_id ASC");
    while ($r = r($result)) {
    	echo '<span class="right">'.$r['puntos'].'</span> <span class="boton_votar" onclick="votar();">+</span> <span class="boton_votar" onclick="votar();">-</span> '.$r['texto'].'<br />';
    }

}



if ($_REQUEST['a']=='new_msg' AND $_POST['texto']) {
    
    
    if (!in_array(substr($_POST['texto'], -1, 1), array('?', '!')))
        $_POST['texto'] .= '.';
    
    sql_insert('msg', array(
        'canal'             => $canal,
        'fecha_creacion'    => date('Y-m-d H:m:s'),
        'estado'            => 'propuesto',
        'texto'             => ucfirst($_POST['texto']),
        'participante'      => '',
        'ip'                => $_SERVER['REMOTE_ADDR'],
        'participantes_num' => 1,
        'puntos'            => 0,
    ));
    
    
    
    
    
}