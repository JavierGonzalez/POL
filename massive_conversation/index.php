<?php

$maxsim['template']['title'] = 'Massive Conversation';

?>
<html>

<head>
<meta charset="UTF-8" />

<title><?=$maxsim['template']['title']?></title>

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>


<style>
body {
    font-size:18px;
	font-family: Consolas,monaco,monospace; 
	color:#00ff00;
	background-color:#000;
}


table td {
    border:2px solid #00ff00;
    padding:0px;
}

#new_msg {
    font-size:20px;
    width:100%;
    color:yellow;
    font-weight:bold;
    text-transform:uppercase;
	background-color:#000;
    border:none;
    outline:none;
}

.right {
    float:right;
}

.left {
    float:left;
}


.boton_votar {
    color:#FFF;
    cursor:pointer;
}

</style>

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
            <input id="new_msg" type=text name=texto value="" autocomplete="off" autofocus />
        </form>
    </td>
</tr>





<tr>
    <td height=30 colspan=2 align="right" style="border:none;">
        
        Javier González González &#60;gonzo@virtualpol.com&#62;
        
    </td>
</tr>


</table>

<script src="main.js"></script>

</body>
</html>