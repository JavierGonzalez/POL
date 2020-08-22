<?php

unset($maxsim['output']);

?>
<html>

<head>
<meta charset="UTF-8" />

<title>Conversación Colectiva</title>

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
    color:#00ff00;
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
        Unified Conversation
    </td>
</tr>



<tr>
    <td width=50% rowspan=2 valign=bottom style="padding:10px;">
        <div id="chat_msg">
            
        </div>
    </td>
    
    
    <td width=50% valign=bottom style="padding:10px;">
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



<script type="text/javascript">


refresh_chat_main();

function refresh_chat_main() {
    
    $("#chat_msg").load("unified_conversation/ajax/chat_msg");
    
    
    setTimeout(function(){
        refresh_chat_main();
    }, 5000);
}


refresh_chat_respuestas();

function refresh_chat_respuestas() {
    
    $("#chat_respuestas").load("unified_conversation/ajax/chat_respuestas");
    
    
    setTimeout(function(){
        refresh_chat_respuestas();
    }, 1000);
}



$("#form_new_msg").submit(function(event) {
    
    
    $.post("unified_conversation/ajax/new_msg", { texto: $("#new_msg").val() })
        .done(function( data ) {
            //
    });
    
    
    $("#new_msg").val("");
    
    event.preventDefault();
});


function votar() {    
    alert("Voto hecho!");
};



</script>

</body>
</html>