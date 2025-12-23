<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


date_default_timezone_set('Europe/Madrid');

if (is_numeric($_POST['chat_ID']) AND is_numeric($_POST['n']))
	echo chat_refresh($_POST['chat_ID'], $_POST['n']);

exit;