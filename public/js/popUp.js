function warning_pop_up(action, options = {}) {
    const {
        message = "Do you want to continue?",
        okLabel = "OK",
        cancelLabel = "Cancel",
        allowCancel = false,
    } = options;
    
    $("#warning_pop").show();
    
    $("#warning_pop_up_warning_text").html(message);
    $("#warning_pop_up_confirm_button").html(okLabel);
    $("#warning_pop_up_cancel_button").html(cancelLabel);
    
    if(allowCancel) {
        $("#warning_pop_up_cancel_button").show();
    } else {
        $("#warning_pop_up_cancel_button").hide();
    }
    
    $("#warning_pop_up_confirm_button").off("click").on("click", handleConfirm__warning_pop_up);
    $("#warning_pop_up_cancel_button").off("click").on("click", handleCancel__warning_pop_up);
     
    function close__warning_pop_up() {
        $("#warning_pop_up_warning_text").html("-");
        $("#warning_pop_up_confirm_button").html("-");
        $("#warning_pop_up_cancel_button").html("-");
        $("#warning_pop").hide();
    }

    function handleConfirm__warning_pop_up() {
        close__warning_pop_up();
        if (typeof action === "function") {
            action();
        }
    }

    function handleCancel__warning_pop_up() {
        close__warning_pop_up();
    }
}

