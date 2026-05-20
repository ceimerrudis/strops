$(document).ready(function () {
    $('#screen_height').val(window.screen.height);
    $('#screen_width').val(window.screen.width);
});

function ShowPassword()
{
    const passwordInputObject = document.getElementById("password");
    if (passwordInputObject.type === "password") {
        passwordInputObject.type = "text";
    } else {
        passwordInputObject.type = "password";
    }
}
