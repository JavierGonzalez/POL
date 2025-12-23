<?php

/*
$cargo_res = sql_old("SELECT * FROM chats_msg order by time DESC LIMIT 10");
while ($c = r($cargo_res)) {
    echo json_encode($c).'<br />';
}
*/


header('Content-Type: text/plain; charset=utf-8');

$prompt = ai_context(['chat_ID' => 927]);

echo $prompt['prompt_system']."\n\n\n".$prompt['prompt_user'];

exit;