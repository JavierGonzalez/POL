


refresh_chat_main();

function refresh_chat_main() {
    
    // $("#chat_msg").load("/massive_conversation/ajax/chat_msg");
    
    // $("#chat_respuestas").load("/massive_conversation/ajax/chat");
    
    setTimeout(function(){
        refresh_chat_main();
    }, 10000);
}




$("#form_new_msg").submit(function(event) {
    
    
    $.post("/massive_conversation/ajax/new_msg", { text: $("#new_msg").val() })
        .done(function( data ) {
            //
    });
    
    
    $("#new_msg").val("");
    
    event.preventDefault();
});


function votar() {    
    alert("Voto hecho!");
};
