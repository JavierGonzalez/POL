<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$email = trim($_POST['email']);
$pre_login = true;
if ($pol['user_ID']) {
    sql("UPDATE users SET email = '".$email."' WHERE ID = '".$pol['user_ID']."' AND fecha_registro < '".date('Y-m-d 20:00:00', time() - 864000)."' LIMIT 1");
}
redirect('/registrar/login/panel');