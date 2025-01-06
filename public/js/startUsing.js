$( document ).ready(function() {
    $("#Reservation_OKBTN").on('click', Reservation_AnswYes);
    $("#Reservation_NOBTN").on('click', AnswNo);
    
    $("#EndUse_OKBTN").on('click', EndUse_AnswYes);
    $("#EndUse_NOBTN").on('click', AnswNo);

    $('#ne_poga').click(AskForUsage);

    $("#beginUse").hide();
    if(usage_type == dayEnumValue)
    {
        $("#secondPartOfMakeVehicleUseForm").hide();
        $("#beginUse").show();
    }

    CheckMessages();

    $("#syncBtn").on('click', function (){
        $("#loadingWrapper").show();//Šī darbība var aizņemt kādu laiciņu tapēc uzliek lādēšanās logu
        $.ajax({
            type: "GET",
            url: "atjaunotObjektus", 
            success: function(result){
                //close loading window
                $("#loadingWrapper").hide();
                $("#syncText").html(result.message);
                location.reload();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                $("#loadingWrapper").hide();
                $("#syncText").html("Notika kļūda");
            }  
        });  
    });
});

function AskForUsage(){
    $('#correctUsageBox').show();
    $('#ne_poga').hide();
    $('#yes_btn').hide();
    $('#confirmMotorHLabel').hide();
    $("#beginUse").show();
}

function CheckMessages(){
    if(messages.length < 1)
    {
        return;
    }
    let message = messages[0];
    messages.shift();
    let statuss = message.statuss;
    if(statuss == "used"){
        OpenEndUsageConfirmWindow(message.message);
    }else if(statuss == "reservedInFuture"){
        OpenOverrideReservationConfirmWindow(message.message);
    }else if(statuss == "reserved"){
        OpenOverrideReservationConfirmWindow(message.message);
    }
}

//Šīs 3 funkcijas savieno datu sarakstu laukus vienu ar otru
function updateObjectName() {
    var objectInput = $("#object");
    var objectNameInput = $("#objectName");
    id = $("#objects option[value='" + objectInput.val() + "']").data("id");
    ToggleComment(id);
    objectNameInput.val($("#objectNames option[data-id='" + id + "']").val()); 
}

function updateObjectCode() {
    var objectInput = $("#object");
    var objectNameInput = $("#objectName");
    id = $("#objectNames option[value='" + objectNameInput.val() + "']").data("id");
    ToggleComment(id);
    objectInput.val($("#objects option[data-id='" + id + "']").val());
    $("#objectID").val(id);
}

function updateObjectInput(){
    var objectInput = $("#object");
    id = $("#objects option[value='" + objectInput.val() + "']").data("id");
    $("#objectID").val(id);
    updateObjectName();
}

//Šī funkcija aizstāj objekta nosaukumu ar komentāru vai otrādāk (atkarībā no izvēlētā objekta)
function ToggleComment(id)
{
    if(id == 0){
        //Objekts ar id 0 ir objekts Cits šis ir īpašs objekts kam  nepieciešams komentārs
        $("#objectNameLabel").hide();
        $("#objectName").hide();   

        $("#commentLabel").show();
        $("#comment").show();   
    }else{
        $("#objectNameLabel").show();
        $("#objectName").show();   

        $("#commentLabel").hide();
        $("#comment").hide();   
    }
}

function OpenOverrideReservationConfirmWindow(message){
     $("#confirmWrapper").show();
    $("#overrideReservationConfirm").show();
    $("#overrideReservationConfirmText").html(message)
}

function OpenEndUsageConfirmWindow(message){
    $("#confirmWrapper").show();
    $("#endCurrentUsageConfirm").show();
    $("#endCurrentUsageConfirmText").html(message);
}

function Reservation_AnswYes(){
    $("#confirmWrapper").hide();
    $("#overrideReservationConfirm").hide();
    CheckMessages();
}

function EndUse_AnswYes(){
    $("#confirmWrapper").hide();
    $("#endCurrentUsageConfirm").hide();
    $("#endCurrentUsage").val("yes");
    if(usage_type != dayEnumValue)
    {
        AskForUsage();
    } 
    CheckMessages();
}

function AnswNo(){
    //Ja negribam traucēt lietojumus un rezervācijas tad jāpārtrauc
    window.location.href = '/sakums';
}