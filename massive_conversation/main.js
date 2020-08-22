


refresh_chat_main();

function refresh_chat_main() {
    
    $("#chat_msg").load("massive_conversation/ajax/chat_msg");
    
    
    setTimeout(function(){
        refresh_chat_main();
    }, 10000);
}


refresh_chat_respuestas();

function refresh_chat_respuestas() {
    
    $("#chat_respuestas").load("massive_conversation/ajax/chat_respuestas");
    
    
    setTimeout(function(){
        refresh_chat_respuestas();
    }, 10000);
}



$("#form_new_msg").submit(function(event) {
    
    
    $.post("massive_conversation/ajax/new_msg", { texto: $("#new_msg").val() })
        .done(function( data ) {
            //
    });
    
    
    $("#new_msg").val("");
    
    event.preventDefault();
});


function votar() {    
    alert("Voto hecho!");
};
