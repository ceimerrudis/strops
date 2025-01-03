function ShowPassword()
{
    const passwordInputObject = document.getElementById("password");
    if (passwordInputObject.type === "password") {
        passwordInputObject.type = "text";
    } else {
        passwordInputObject.type = "password";
    }
}