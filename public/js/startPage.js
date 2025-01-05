//Šajā failā ir funkcijas specifiskas sākuma lapai
const RESERVATION_VISUAL_HEIGHT = 18;
const HOURS_IN_TIMELINE = 11;
const TIMELINE_OFFSET = 7;
const TIME_TO_RED = 5.5;
var selectedDay = -1;
var freezeFrom = false;

$( document ).ready(function() {
    setInterval(function() {
        console.log($("input[name='vehicle']:checked").val());
    }, 100);
    
    resizeTimeline();//Uzstāda laika līniju

    //Savēršamais logs
    $("#collapsor").on( "click", function() {
        $("#collapse_content").toggle();
        if($("#collapse_content").is(":hidden")){
            $("#collapsor").html("<span class='collapse_label'>Veikt inventāra rezervāciju +</span>");
        }else{
            $("#collapsor").html("<span class='collapse_label'>Veikt inventāra rezervāciju -</span>");
        }
    });

    //Rezervēt lietot, rezervēt un lietot pogas
    $("#makeReservationBtn").on("click", function(){
        console.log("asd");
        $("#makeReservationForm").attr("action", $("#reservationUrlHolder").html());
        $("#makeReservationForm").attr("method", "post");
        $("#makeReservationForm").submit();
    });
    $("#startUsingWithReservationBtn").on("click", function(){
        $("#makeReservationForm").attr("action", $("#reservationAndUseUrlHolder").html());
        $("#makeReservationForm").attr("method", "post");
        $("#makeReservationForm").submit();
    });
    $("#startUsingBtn").on("click", function(){
        $("#makeReservationForm").attr("action", $("#useUrlHolder").html());
        $("#makeReservationForm").attr("method", "get");
        $("#makeReservationForm").submit();
    });
    
    //Katru reizi kad maina izvēlēto inventāru jāmaina laika līnmijas saturs
    $('input[type="radio"][name="vehicle"]').change(function(){
        var selectedValue = $('input[name="vehicle"]:checked').val();
        SetVehicle(selectedValue);
        createReservationTimelineVisuals();
    });

    //Pazīme vai iesaldēt sākuma datumu
    $('#freezeCheckbox').click(function(){
        if($(this).is(':checked')){
            freezeFrom = true;
        } else {
            freezeFrom = false;
        }
    });

    //Kalendāra mēnešu izvēles pogas
    $("#calendarPreviousMonthButton").on('click', loadLastMonthsCalendar);
    $("#calendarNextMonthButton").on('click', loadNextMonthsCalendar);

    var currentDate = new Date();
    var currentMonth = currentDate.getMonth() + 1;
    var currentYear = currentDate.getFullYear();
    //Katru mēnesi pieprasa no servera ar ajax
    $.ajax({
        type: "GET",
        url: "kalendars", 
        data: { 
            year: currentYear, 
            month: currentMonth, 
          },
        cache: false,//Tiek  iegūti rezervācijas dati tapēc neizmanto kešu
        success: function(result){
            $("#calendar").html(result);
            //calendarMonthTitle atrodas ārpus #calendar objekta
            $("#calendarMonthTitle").html($("#monthTitleHolder").html() + " " + $("#year").html());
            AssignCalendarButtons();//Kalendāra inicializācija
            SetVehicle($('input[name="vehicle"]:checked').val());//Mainījušies izvēlētās dienas un rezervāciju dati
        }
    });  
});

//Mainot loga izmērus laika līnija var izplūst nākamajā rindā.
window.onresize = resizeTimeline;
//Funkcija kas pārrēķina un izmaina laika līnijas izmērus. 
function resizeTimeline() {
    //Laika skala tiek sadalīta Vienādās daļās. pāri palikušie pikseļi tiek pievienoti pa vienam pēc kārtas katrai kolonai.

    const timelineVisualObject = $("#timelineVisual");//Pelēkā horizontālā līnija pie kuras piestiprina rezervāciju attēlojumus
    const timelinePillarObjects = $('.timeline_background_pillar');//Saraksts ar visiem kolonu objektiem
    timelineVisualObject.empty();
    //Vecākelements ar platumu 100% 
    //Platums iegūts pikseļos un laika rindai jāiekļaujas šajā platumā
    const timelineWidth = timelineVisualObject.width();
    const columnCount = timelinePillarObjects.length;

    //Kolonas apmales biezums
    const borderWidth = 4;
    const leftoverPixels = Math.floor(timelineWidth % columnCount);
    const singlePillarWidth = Math.floor(timelineWidth / columnCount) - borderWidth;
    timelinePillarObjects.width(singlePillarWidth);
    let i = 0;
    timelinePillarObjects.each(function(index, pillar) {
        if(i >= leftoverPixels)
        {
            return;
        }
        $(pillar).width($(pillar).width() + 1);
        i++;
    });

    createReservationTimelineVisuals();
}

//Izveido rezervāciju vizualizācijas
function createReservationTimelineVisuals()
{
    //Ja nav izvēlēta diena tad nevar neko attēlot 
    if(selectedDay < 0)
    {
        return;
    }
    //Iegūst izvēlētā inventāra id
    const selectedVehicle = $('input[name="vehicle"]:checked').val();

    const timelineVisualObject = $("#timelineVisual");
    timelineVisualObject.empty();//Nodzēš pašreizējo vizualizāciju
    const selectedDaysObject = $("#data_" + selectedDay);
    
    //Nebeidzamai cikls lai nav jāraksta gara pārbaude
    for (let i = 0; i < 30; i++)//30 ir tikai drošības pēc lai nebūtu nebeidzamais cikls   
    {
        //Kad izvēlētajam objektam vairs nav rezervāciju
        if($(selectedDaysObject.find("#data_day_" + (selectedDay) + "_reservation_" + (i) + "_from")).length === 0)
        {
            //Šī ir vienīgā izeja
            break;
        }

        const reservationsVehicle = selectedDaysObject.find("#data_day_" + (selectedDay) + "_reservation_" + (i) + "_vehicleID").html();
        //Ja i'tā rezervācija nav saistīta ar izvēlēto inventāru tad tā tiek izlaista (-1 ir visas rezervācijas)
        if(parseInt(reservationsVehicle) !== parseInt(selectedVehicle) && parseInt(selectedVehicle) != -1)
        {
            continue;
        }
        
        //Izveido i'tās rezervācijas vizuālo elementu
        //Iegūst datus
        let from = $(selectedDaysObject.find("#data_day_" + (selectedDay) + "_reservation_" + (i) + "_from")).html();
        from = (from - TIMELINE_OFFSET);
        let until = $(selectedDaysObject.find("#data_day_" + (selectedDay) + "_reservation_" + (i) + "_until")).html();
        until = (until - TIMELINE_OFFSET);
        let userName = $(selectedDaysObject.find("#data_day_" + (selectedDay) + "_reservation_" + (i) + "_user")).html();
        let vehicleName = $(selectedDaysObject.find("#data_day_" + (selectedDay) + "_reservation_" + (i) + "_vehicle")).html();
        
        //Pārbauda robežas
        if(until < 0)
        {
            until = 0;
        }
        if(until > HOURS_IN_TIMELINE)
        {
            until = HOURS_IN_TIMELINE;
        }
        
        if(from < 0)
        { 
            from = 0;
        }
        if(from > HOURS_IN_TIMELINE)
        {
            from = HOURS_IN_TIMELINE;
        }
        //Aprēķina laika datus
        const length = until - from;
        const offset = from;
        const text = vehicleName + " - " + userName;
        timelineVisualObject.append(
            //Vizuālā elementa html
            '<div style="height:0px;"><div class="reservation_visual reservation"><p class="random_timeline_container">' + text + '</p><p class="vehicle" style="display: none;">' + reservationsVehicle + '</p><p class="offset" style="display: none;">' + offset + '</p><p class="length" style="display: none;">' + length +'</p></div></div>'
        );
    }
    recalculateReservationVisualsSize();
}

//Nomaina laika līnijas augstumu un vizualizāciju krāsas, kā arī sakārto tās pa augstumiem
function recalculateReservationVisualsSize()
{
    const timelineVisualObject = $("#timelineVisual");
             
    const reservationObjects = $(".reservation");
    const timelineWidth = timelineVisualObject.width();
    let reservationHeightOrder = {};//Kurā augstumā šis inventārs ir jānovieto
    let reservationCountForVehicle = {};//Cik rezervāciju šim inventāram pagaidām ir sarakstā (maina krāsu)
    let i = 1;//kopējais attēloto inventāru skaits
    reservationObjects.each(function(index, element) {
        let offset = $(element).find(".offset").html();
        let length = $(element).find(".length").html();
        let vehicle = $(element).find(".vehicle").html();
        
        //Ja šāds inventārs iepriekš netika atrasts izveido  
        if(!(vehicle in reservationHeightOrder)){
            reservationHeightOrder[vehicle] = i;
            reservationCountForVehicle[vehicle] = 0;
            i++;
        }else
        {
            reservationCountForVehicle[vehicle] = reservationCountForVehicle[vehicle] + 1;
        }

        
        let gapFromLeftSide = 2;
        let bottom = Math.round(reservationHeightOrder[vehicle] * RESERVATION_VISUAL_HEIGHT) + "px";
        let left = (Math.round(timelineWidth * (offset / HOURS_IN_TIMELINE)) + gapFromLeftSide) + "px";
        let color = GetColor(reservationCountForVehicle[vehicle], vehicle);
        let width = Math.round(timelineWidth * (length / HOURS_IN_TIMELINE));
        $(element).css({"bottom": bottom, "left": left, "background-color": color});
        $(element).width(width - gapFromLeftSide);
        
        if(width < 120){
            $(element).find(".random_timeline_container").css({"display": "none"});
        }
    });

    let numberOfDifferentVehicles = Object.keys(reservationHeightOrder).length;
    //Lai rinda nesabruktu pārāk plakana izliekamies ka ir vismaz 1 inventārs
    if(numberOfDifferentVehicles == 0)
    {
        numberOfDifferentVehicles = 1;
    }

    //Izmaina kolonu augstumu
    timelineVisualObject.css({"top": (Math.round(70 + (numberOfDifferentVehicles * RESERVATION_VISUAL_HEIGHT)) + "px")});
    $('.timelineBackgroundPillar').height(98 + (numberOfDifferentVehicles * RESERVATION_VISUAL_HEIGHT));
}

//Funkcija kas konkrētam inventāram piešķir, atkarībā no padotā indeksa, tā inventāra  gaišo vai tumšo krāsu
function GetColor(index, vehicle)
{
    //Dažādas krāsas izmanto vieglai rezervāciju atdalīšanai
    const colorCount = 10;
    const colorCountForSingleVehicle = 2;
    index = (vehicle * colorCountForSingleVehicle % (colorCount/2))*2 + (index % colorCountForSingleVehicle);
    switch (index) {
        case 0:
            return "rgba(240, 219, 84, 0.8)"; // Dzeltens
        case 1:
            return "rgba(196, 179, 67, 0.8)";
        case 2:
            return "rgba(82, 79, 201, 0.8)"; // Zils
        case 3:
            return "rgba(68, 65, 163, 0.8)"; 
        case 4:
            return "rgba(181, 74, 58, 0.8)"; // Sarkans
        case 5:
            return "rgba(140, 56, 43, 0.8)";
        case 6:
            return "rgba(98, 176, 74, 0.8)"; // Zaļšs
        case 7:
            return "rgba(70, 128, 52, 0.8)";
        case 8:
            return "rgba(74, 204, 207, 0.8)"; // Tirkīz zils
        case 9:
            return "rgba(59, 161, 163, 0.8)";
        default:
            return "rgba(255, 225, 31, 0.8)"; // Dzeltens
    }
}

//Nomaina izvēlēto inventāru
//Galvenokārt nomaina kalendāra izskatu atbilstoši tam vai inventārs tajā dienā ir pieejams
function SetVehicle(id){
    if(id == -1) 
        return;

    //HTML elementi kas satur datus par pašreizējo datumu  
    let i = $("#day").html();
    let setYear = $("#year").html();
    let setMonth = $("#month").html();

    var currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let currentMonth = currentDate.getMonth() + 1;//Lai pārietu no 0 - 11 uz 1 - 12 
    if((currentMonth > setMonth && currentYear == setYear) || currentYear > setYear){
        return;//Pagātnē neko nerāda
    }

    if(i < 1) //Ja pašreizējā diena ir < 0 tad ir izvēlēts cits mēnesis un pašreizējie lietojumi neatiecas
    {
        i = 1;
    }
    else if(Number($("#used_" + id).html()) == 1){
        //Ja izvēlētais inventārs tiek lietots pašlaik iezīmē šodienu sarkanu
        dayObj = $(("#" + i).replace(/\s/g, ''));
        dayObj.removeClass("green");
        dayObj.removeClass("orange");
        dayObj.addClass("red");
        i++;
    }

    //Katrai mēneša dienai 1 cikls
    for (; i < 40; i++) {  //40 ir tikai drošības pēc lai nebūtu nebeidzamais cikls   
        dayObj = $(("#" + i).replace(/\s/g, ''));
        if(dayObj.length == 0) 
            break;//Ja neatrodam nākošo dienu pārtraucam
        //Notīra visas klases
        dayObj.removeClass("green");
        dayObj.removeClass("orange");
        dayObj.removeClass("red");

        time_used = 0
        //Ejam cauri visām šīs dienas rezervācijām un uzskaitam rezervēto laiku
        for (let j = 0; j < 30; j++) {  //30 ir tikai drošības pēc lai nebūtu nebeidzamais cikls   
            reservVehicle = $(("#data_day_" + i + "_reservation_" + j + "_vehicleID").replace(/\s/g, ''));
            if(reservVehicle.length == 0) 
                break;
            if(Number(reservVehicle.html()) == Number(id)){
                from = $(("#data_day_" + i + "_reservation_" + j + "_from").replace(/\s/g, '')).html();
                until = $(("#data_day_" + i + "_reservation_" + j + "_until").replace(/\s/g, '')).html();
                time_used += until - from;
            }
        }
        if(time_used >= TIME_TO_RED)
        {  
            dayObj.addClass("red");
        }else if(time_used > 0)
        {
            dayObj.addClass("orange");
        }else{
            dayObj.addClass("green");
        }
    }
}

//Funkcija kas palaista kad izvēlas dienu kalendārā
function SelectDate()
{
    SetDate(this.id)
}

//Funkcija kas palaista kad kods izvēlas dienu (piemēram atverot lapu) 
function SetDate(id)
{
    //Vizuālais indikators
    $("#"+selectedDay).removeClass("calendar_date_selected");
    selectedDay = id;
    $("#"+selectedDay).addClass("calendar_date_selected");
    createReservationTimelineVisuals();//Nomaina laika līnijas datus
    FillOutDateFields(selectedDay);//Atjauno laiku laukus
}

 //Laiku lauku loģikas maiņa mainot izvēlēto dienu
function FillOutDateFields(desiredDay)
{
    setYear = $("#year").html();
    setMonth = $("#month").html();
    //date ir datuma teksts
    date = setYear.toString()+"-"+(setMonth < 10 ? '0' + setMonth.toString() : setMonth.toString())+"-"+(desiredDay < 10 ? '0'+desiredDay.toString() : desiredDay.toString());
    fromAdd = "";//Laika daļa glabāta teksta formātā
    untilAdd = "";//Laika daļa glabāta teksta formātā

    day = $("#day").html();//Kura diena ir šodien (-1 ja izvēlēts cits mēnesis/gads)
    useDefaultTime = true;//Lietot noklusējuma laikus ja netika atrasta meklētā diena
    if(day >= 0)
    {
        if(parseInt(day) == parseInt(desiredDay)){
            useDefaultTime = false;
            var currentDate = new Date();
            //Iegūst pašreizējo pilno stundu apaļotu uz augšu (robežgadījumā kur minūtes == 0 ņemam nākamo stundu)
            let wholeHour = currentDate.getHours() + 1;
            fromAdd = "T" + (wholeHour < 10 ? '0' + wholeHour.toString() : wholeHour.toString()) + ":00";//laiks no būs tāds pats kā pašreizējā stunda
            //Laiks līdz būs vai nu vienu stundu pēc pašreizējās stundas vai 17:00 šodien vai nākošajā dienā
            if(wholeHour > 16 && wholeHour <= 18)
            {
                untilAdd = "T" + (wholeHour+1) + ":00";
            }else if(wholeHour > 18){
                fromAdd = "T08:00";//Nākošā diena
                untilAdd = "T17:00";
                var dateObj = new Date(date.replace(/\s/g, ''));//Šis /\s/g, '' nepieciešams jo js.
                dateObj.setDate(dateObj.getDate() + 1);//Nākamā diena
                date = dateObj.toISOString().slice(0, 10);
            }else{
                untilAdd = "T17:00";
            }
        }
    }
    
    if(useDefaultTime){
        if (fromAdd.length === 0) {
            fromAdd = "T08:00";
        }
        if (untilAdd.length === 0) {
            untilAdd = "T17:00";
        }
    }
    
    if(freezeFrom == false)
        $("#from").val((date + fromAdd).replace(/\s/g, ""));//2024-01-25T11:45
    $("#until").val((date + untilAdd).replace(/\s/g, ""));
}

//Pievieno funkcionalitāti kalendāra pogām
function AssignCalendarButtons()
{
    let i = 1
    while($("#" + i).length > 0){
        $("#" + i).on('click', SelectDate);
        i++;
    }

    if($(".today").attr('id')){
        SetDate($(".today").attr('id'));
    }else{
        SetDate(1);//Ja nav pašreizējais mēnesis jāizvēlas pirmā mēneša diena
    }
}

function loadLastMonthsCalendar() {
    getCalendar(false);
}

function loadNextMonthsCalendar() {
    getCalendar(true);
}

//Iegūst iepriekšējā vai nākamā mēneša kalendāru
function getCalendar(nextMonth)
{
    year = $("#year").html();
    month = $("#month").html();
    year = parseInt(year, 10)
    month = parseInt(month, 10)

    month = nextMonth ? month + 1 : month - 1;
    
    if(month < 1)
    {
        year = year - 1;
        month = 12;
    }
    if(month > 12)
    {
        year = year + 1;
        month = 1;
    }
    
    $.ajax({
        type: "GET",
        url: "kalendars", 
        cache: false,
        data: {"month": month, "year": year},
        success: function(result){
            $("#calendar").html(result);
            $("#calendarMonthTitle").html($("#monthTitleHolder").html() + " " + $("#year").html());
            AssignCalendarButtons();
            SetVehicle($('input[name="vehicle"]:checked').val());
        }
    });  
}