<?php 
include('inc-login.php');

// CHAT PLAZA
$_GET['a'] = strtolower(PAIS);
include('inc-chats.php');

$txt_description = $pol['config']['pais_des'].'. '.PAIS;

$txt_menu = 'comu';
include('theme.php');
?>