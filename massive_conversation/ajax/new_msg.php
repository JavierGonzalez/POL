<?php


if ($_POST['text']) {
    
    if (!in_array(substr($_POST['text'], -1, 1), array('?', '!')))
        $_POST['text'] .= '.';
    
    sql_insert('msg', [
        'channel'           => $channel,
        'date_creation'     => date('Y-m-d H:m:s'),
        'state'             => 'propuesto',
        'text'              => ucfirst($_POST['text']),
        'user_id'           => '',
        'ip'                => $_SERVER['REMOTE_ADDR'],
        'num'               => 1,
        'points'            => 0,
        ]);
    
    
}