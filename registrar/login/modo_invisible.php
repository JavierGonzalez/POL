<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


sql_old("UPDATE users SET modo_invisible = '".($_POST['modo_invisible']=='true'?'true':'false')."' WHERE ID = '".$pol['user_ID']."'");
redirect('/registrar/login/panel');