<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


unset($_SESSION); 
session_destroy();

// setcookie('teorizauser', '', time()-36000, '/', USERCOOKIE);
// setcookie('teorizapass', '', time()-36000, '/', USERCOOKIE);

setcookie('pol_session', '', time()-36000, '/', USERCOOKIE);


if ($_SERVER['HTTP_REFERER']) { $url = $_SERVER['HTTP_REFERER']; }
else { $url = '/'; }
redirect($url);