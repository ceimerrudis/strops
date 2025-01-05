@include('base')
@php
    use App\Enums\VehicleUsageTypes;
@endphp
<script>
    let messages = <?php echo json_encode($messages);?>;
    let dayEnumValue = <?php echo json_encode(VehicleUsageTypes::DAYS->value);?>;
    let usage_type = <?php echo json_encode($usage_type);?>;
</script>
<script src="{{ asset('js/startUsing.js') }}"></script>

<div class="make_vehicle_use_form_box">
<form id="makeVehicleUseForm" action="{{ route('startUsePost') }}" method="post">
@csrf
    <input type="hidden" name="endCurrentUsage" id="endCurrentUsage" value="no">
    <input type="hidden" name="from" id="from" value="{{$from}}">
    <input type="hidden" name="until" id="until" value="{{$until}}">
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
        <button id='ne_poga' type='button' class='ne'>nē</button>
        <button type='submit' id="yes_btn" class='begin_use_btn'>jā / Sākt lietot</button>

        <div id='correctUsageBox' style="display: none;">   
            <label for='usage' class="wrongMotorHLabel"> 
                @if($usage_type == VehicleUsageTypes::MOTOR_HOURS->value)
                    Ievadi pašreizējās motorstundas
                @elseif($usage_type == VehicleUsageTypes::KILOMETERS->value)
                    Ievadi pašreizējo nobraukumu
                @endif   
            </label>    
            <input class="adminEditInput" type='numeric' name='usage' id='usage' value='{{$usage}}'>
            
        </div>
        @error('usage')
            <span class='alert'>{{ $message }}</span>
        @enderror

    </div>
    <button type='submit' id="beginUse" class='getNextPart'>Sākt lietot</button>
    <!-- Izsauc objektu sinhronizāciju -->
    <button class="sync_objects_link" type="button" id="syncBtn">Atjaunot objektu sarakstu</button>
    <p id="syncText"></p>
    <div class="spacer"></div>
</form>
</div>

<!-- Dialoga logs kas pārliecinās ka lietotājs zin par konfliktējošajiem lietojumiem un rezervācijām. -->
<div id="confirmWrapper" class="confirm_wrapper">
    <div class="overlay"></div>
    <div id="overrideReservationConfirm" class="confirmation_box">
        <p><span id="confirmText"></span></p>
        <button type="button" id="Reservation_NOBTN" class="no_btn">Atcelt</button>
        <button type="button" id="Reservation_OKBTN" class="ok_btn">Izveidot lietojumu tik un tā</button>
    </div>

    <div id="endCurrentUsageConfirm" class="confirmation_box">
        <p><span id="confirmText"></span></p>
        <button type="button" id="EndUse_NOBTN" class="no_btn">Atcelt</button>
        <button type="button" id="EndUse_OKBTN" class="ok_btn">Pārtraukt pašreizējo lietojumu</button>
    </div>
</div>
@include('footer')