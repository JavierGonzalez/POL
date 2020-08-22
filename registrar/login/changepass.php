<?php # POL.VirtualPol.com — Copyright (c) 2008 Javier González González <gonzo@virtualpol.com> — MIT License 


$oldpass = md5(trim($_POST['oldpass']));
$newpass = md5(trim($_POST['pass1']));
$newpass2 = md5(trim($_POST['pass2']));
$pre_login = true;

if ($pol['user_ID']) {
    $result = sql_old("SELECT ID FROM users WHERE ID = '".$pol['user_ID']."' AND pass = '".$oldpass."' LIMIT 1");
    while ($r = r($result)) { $userID = $r['ID']; }
    if (($pol['user_ID'] == $userID) AND ($newpass === $newpass2)) {
        if (strlen($newpass) != 32) { $newpass = pass_key($newpass, 'md5'); }
        sql_old("UPDATE users SET pass = '".$newpass."', pass2 = '".pass_key($_POST['pass1'])."' WHERE ID = '".$pol['user_ID']."' LIMIT 1");
    }
}

redirect('/registrar/login/panel');