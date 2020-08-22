<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


sql_old("UPDATE users SET ser_SC = '".($_POST['ser_SC']=='true'?'true':'false')."' WHERE ser_SC IN ('true', 'false') AND ID = '".$pol['user_ID']."' LIMIT 1");
redirect('/registrar/login/panel');