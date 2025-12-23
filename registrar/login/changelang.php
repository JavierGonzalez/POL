<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$pre_login = true;
if ($pol['user_ID']) {
    sql_old("UPDATE users SET lang = ".($_POST['lang']?"'".$_POST['lang']."'":"NULL")." WHERE ID = '".$pol['user_ID']."'");
}
redirect('/registrar/login/panel');