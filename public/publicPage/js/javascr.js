$(document).ready(function () {
    resizeHeight();
});
function changeIcon(button)
{
    
    var x = button.childNodes;
    //console.log(x);
    if(x[1].classList.contains("fa-angle-right"))
    {
        x[1].className = "servicesArrowIcon fas fa-angle-down";
    }else
    {
        x[1].className = "servicesArrowIcon fas fa-angle-right";
    }
}
window.onresize = resizeHeight;
function resizeHeight()
{
    $('.section').height($(window).height() - 150);
    if (($(window).height() - 300) >= 300) {
        $('.additional_picture').height(($(window).height() - 300));
    } else
    {
        $('.additional_picture').height(300);
    }
	//console.log($(window).width());
	if (($(window).width()) <= 1200) {
       document.getElementById("services").style.height = "auto";
    } else
    {
        //document.getElementById("projects").style.height = "100%";
    }
    $('#home').height($(window).height());//360
    document.getElementById("contact").style.height = "100%";
	document.getElementById("shop").style.height = "100%";
    document.getElementById("projects").style.height = "100%";
}
function openIssueSubmit()
{
    document.getElementById("issueForm").style.display = "block";
    document.getElementById("disablingDiv").style.display = "block";
    resizeHeight();
    return false;
}
function closeIssueSubmit()
{
    document.getElementById("issueForm").style.display = "none";
    document.getElementById("disablingDiv").style.display = "none";
    return false;
}
function postIssue()
{
    console.log("sddd");
    // are required fields correct?
    if(document.getElementById("comments").value == "")
    {
        alert("Lūdzu ierakstiet ziņojumu");
        return false;
    }
    document.getElementById("first_name").value = "";
    document.getElementById("comments").value = "";
    document.getElementById("last_name").value = "";
}