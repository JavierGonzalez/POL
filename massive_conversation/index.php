<?php

$maxsim['template']['title'] = 'Massive Conversation';


?>
<html>

<head>
<meta charset="UTF-8" />

<title><?=$maxsim['template']['title']?></title>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

<link href="/massive_conversation/main.css" rel="stylesheet">

</head>



<body>



<table width=100% height=100%>

<tr>
    <td height=30 colspan=2 style="border:none;">
        <span style="float:right">3 participants</span>
        <?=$maxsim['template']['title']?>
    </td>
</tr>



<tr>
    <td width=50% valign=bottom style="padding:10px;">
        <div id="chat_msg">
            
        </div>
    </td>
    
    
    <td width=50% rowspan=2 valign=bottom style="padding:10px;">
        <div id="chat_respuestas">
            
        </div>
    </td>
</tr>



<tr>
    <td width=50% height=10 valign=bottom style="padding:10px;">
        <form id="form_new_msg" style="margin:0;">
            <input id="new_msg" type=text name=text value="" autocomplete="off" autofocus />
        </form>
    </td>
</tr>





<tr>
    <td height=30 colspan=2 align="right" style="border:none;">
        
        Javier González González &#60;gonzo@virtualpol.com&#62;
        
    </td>
</tr>


</table>

<script src="/massive_conversation/main.js"></script>

</body>
</html>