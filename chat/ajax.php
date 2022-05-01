<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 

$maxsim['output'] = 'text';

if (is_numeric($_POST['chat_ID']) AND is_numeric($_POST['n']))
	echo chat_refresh($_POST['chat_ID'], $_POST['n']);
