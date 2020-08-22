<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


if ($_POST['pass_new'] === $_POST['pass_new2'])
    sql_old("UPDATE users SET pass = '".pass_key($_POST['pass_new'], 'md5')."', 
        pass2 = '".pass_key($_POST['pass_new'])."', 
        api_pass = '".rand(1000000,9999999)."', 
        reset_last = '".$date."' 
        WHERE ID = '".$_POST['user_ID']."' AND api_pass = '".$_POST['check']."' AND reset_last >= '".$date."' LIMIT 1");


redirect('/');
