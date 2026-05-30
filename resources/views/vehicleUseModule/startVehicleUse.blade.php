@php
    use App\Enums\VehicleUsageTypes;
    use App\Enums\VinjetTypes;
    
    $msg = 'type="button" onclick="warning_pop_up(() => document.querySelector(\'#makeVehicleUseForm\').submit(), { message:\'Lai lietotu šo inventāru uz galvenajiem ceļiem nepieciešama <b>vinjete</b>. <br> Ceļu saraksts - <a href=&quot;https://likumi.lv/doc.php?id=185656&quot;>likuma</a> pirmajā pielikumā.\', okLabel : \'Labi\' })"';
    
    $vinjetteAttr = $vinjet == VinjetTypes::REQUIRED->value
    ? sprintf($msg)
    : 'type="su1bmit"';
@endphp

@include('base')
@include('warning_pop_up')
<script>
    let messages = <?php echo json_encode($messages);?>;
    let dayEnumValue = <?php echo json_encode(VehicleUsageTypes::DAYS->value);?>;
    let usage_type = <?php echo json_encode($usage_type);?>;
</script>
<script src="{{ asset('js/startUsing.js') }}"></script>
<script>
$(document).ready(function() {
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
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                $("#loadingWrapper").hide();
                $("#syncText").html("Notika kļūda");
				console.log(result.output);
				console.log(result.message);
            }  
        });  
    });
});
</script>

<div class="make_vehicle_use_form_box">
<form id="makeVehicleUseForm" action="{{ route('startUsePost') }}" method="post">
@csrf
    <input type="hidden" name="endCurrentUsage" id="endCurrentUsage" value="no">
    <input type="hidden" name="vehicle" id="vehicle" value="{{ $vehicleId }}">
    @error('vehicle')
        <span class="alert">{{ $message }}</span>
    @enderror
    
    <!-- Pirmā daļa satur objektu komentāru -->
    <div id="firstPartOfMakeVehicleUseForm">
		<p class="make_vehicle_use_label"> Kurā objektā lietosi inventāru {{$vehicleName}}? </p>
        <input id="objectID" name="object" type="hidden" value="-1">
        <label for="object" class="start_using_object_label">Objekta Nr.</label>
        <input autocomplete="off" id="object" list="objects" class="object_selector" oninput="updateObjectInput()">
        <datalist id="objects">
            @foreach($objects as $object)
            <option value="{{ $object->code }}" data-id="{{ $object->id }}"></option>
            @endforeach
        </datalist>
        @error('object')
            <span class="alert">{{ $message }}</span>
        @enderror

        <!-- Objekta nosaukums ir tikai vizuāls (un lai atrastu objekta kodu pēc nosaukuma)  -->
        <label id="objectNameLabel" class="start_using_object_label">Nosaukums</label>
        <input autocomplete="off" id="objectName" list="objectNames" class="object_selector" oninput="updateObjectCode()">
        <datalist id="objectNames">
            @foreach($objects as $object)
            <option value="{{ $object->name }}" data-id="{{ $object->id }}"></option>
            @endforeach
        </datalist>

		<label style="display: none;" id="commentLabel" class="start_using_object_label">Komentārs</label>
        <input style="display: none;" id="comment" name="comment" class="object_selector" value="">
        @error('comment')
            <span class="alert">{{ $message }}</span>
        @enderror

		@if($createReservation == true)
			<label class="make_vehicle_use_label" for="days">Ievadi cik dienas plāno lietot inventāru. (Šodiena - 1 diena, šodiena un rītdiena - 2 dienas, utt.)</label>
		@endif
		<input class="day_count_input" name="days" id="days"
		required
		@if($createReservation == true)
			type="number"
			value="{{ old('days', 1) }}"
			min="1"
			step="1"
		@else 
			type="hidden"
			value="0"
		@endif
		>
		@error('days')
			<span class="alert">{{ $message }}</span>
		@enderror

    </div>
    <!-- Otrā daļa satur lietojuma pārbaudi un jauno lietojumu (ja tas nepieciešams) -->
    <div id="secondPartOfMakeVehicleUseForm">   
        <label id='confirmMotorHLabel' for='usage'> 
            @if($usage_type == VehicleUsageTypes::MOTOR_HOURS->value)
                Vai šīs motorstundas "{{$usage}}" atbilst patiesībai?
            @elseif($usage_type == VehicleUsageTypes::KILOMETERS->value)
                Vai šis nobraukums "{{$usage}}" atbilst patiesībai?
            @endif    
        </label>
        <button id='ne_poga' type='button' class='ne' name="ne_nesakrit">nē</button>
        <button id="yes_btn" class='begin_use_btn' name="ja_sakrit" {!! $vinjetteAttr !!}>jā / Sākt lietot</button>

        <div id='correctUsageBox' style="display: none;">   
            <label for='usage' class="wrong_motorh_label"> 
                @if($usage_type == VehicleUsageTypes::MOTOR_HOURS->value)
                    Ievadi pašreizējās motorstundas
                @elseif($usage_type == VehicleUsageTypes::KILOMETERS->value)
                    Ievadi pašreizējo nobraukumu
                @endif   
            </label>    
            <input class="admin_edit_input" type='numeric' name='usage' id='usage' value='{{$usage}}'>
            
        </div>
        @error('usage')
            <span class='alert'>{{ $message }}</span>
        @enderror

    </div>
    <button id="beginUse" class='get_next_part' {!! $vinjetteAttr !!}>Sākt lietot</button>
    
    <!-- Izsauc objektu sinhronizāciju -->
    <button class="sync_objects_link" type="button" id="syncBtn" name="atjaunotObjektuSarakstu">Atjaunot objektu sarakstu</button>
    <p id="syncText"></p>
    <div class="spacer"></div>
</form>
</div>

<!-- Dialoga logs kas pārliecinās ka lietotājs zin par konfliktējošajiem lietojumiem un rezervācijām. -->
<div id="confirmWrapper" class="confirm_wrapper">
    <div class="overlay"></div>
    <div style="display:none" id="overrideReservationConfirm" class="confirmation_box">
        <p><span id="overrideReservationConfirmText"></span></p>
        <button type="button" id="Reservation_NOBTN" class="no_btn">Atcelt</button>
        <button type="button" id="Reservation_OKBTN" class="ok_btn">Izveidot lietojumu tik un tā</button>
    </div>

    <div style="display:none" id="endCurrentUsageConfirm" class="confirmation_box">
        <p><span id="endCurrentUsageConfirmText"></span></p>
        <button type="button" id="EndUse_NOBTN" class="no_btn">Atcelt</button>
        <button type="button" id="EndUse_OKBTN" class="ok_btn">Pārtraukt pašreizējo lietojumu</button>
    </div>
</div>
@include('footer')
