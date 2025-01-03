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
