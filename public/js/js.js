//Globālais js
function hideMessage(element) {
    $(element).parent().remove();
    updateMessageBoard();
}

function updateMessageBoard() {
    let messageBoard = $('#messageBoard');
    if (messageBoard.children().length > 0) {
        messageBoard.show();
    } else {
        messageBoard.hide(); 
    }
}

$(document).ready(function() {
    updateMessageBoard();
});


function AddMessage(msg, status)
{
    $("#messageBoard").append("<p class='alert "  + status + "'>" + msg + 
        " <button class='delete_message_button' onclick='hideMessage(this)'>x</button> </p>");
}