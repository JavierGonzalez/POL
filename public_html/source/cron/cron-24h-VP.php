<?php 
$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';

$_SERVER['HTTP_HOST'] = 'vp.virtualpol.com';

include($root_dir.'source/cron/cron-proceso.php');
?>