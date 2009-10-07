<?php 
//header('Last-Modified: ' . gmdate('D, d M Y H:00:00', time() - 259200) . ' GMT');
header('Expires: ' . gmdate('D, d M Y H:00:00', time() + 259200) . ' GMT');
header('Cache-Control: max-age=259200, must-revalidate');

$pi = pathinfo($_SERVER['PHP_SELF']);
switch ($pi['extension']) {
case 'css': header('Content-type: text/css'); break;
case 'js': header('Content-type: text/javascript'); break;
}

ob_start('ob_gzhandler');
?>