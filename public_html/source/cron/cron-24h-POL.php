<?php 

$root_dir = '/var/www/vhosts/virtualpol.com/httpdocs/real/';

$_SERVER['HTTP_HOST'] = 'pol.virtualpol.com';
if ($_SERVER['HTTP_HOST'] != 'pol.virtualpol.com') { exit; }


include($root_dir.'source/cron/cron-proceso.php');


?>