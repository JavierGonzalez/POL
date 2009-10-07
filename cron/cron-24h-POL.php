<?php 


$_SERVER['HTTP_HOST'] = 'pol.virtualpol.com';
if ($_SERVER['HTTP_HOST'] != 'pol.virtualpol.com') { exit; }


include('cron-proceso.php');


?>