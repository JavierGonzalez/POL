<?php 
if ($_SERVER['REMOTE_ADDR'] != '82.165.128.8') { echo 'Acceso denegado.'; exit; }
$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';


$_SERVER['HTTP_HOST'] = 'hispania.virtualpol.com';

include($root_dir.'source/cron/cron-proceso.php');
?>