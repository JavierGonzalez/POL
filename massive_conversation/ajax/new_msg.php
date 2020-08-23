<?php


if ($_POST['texto']) {
    
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