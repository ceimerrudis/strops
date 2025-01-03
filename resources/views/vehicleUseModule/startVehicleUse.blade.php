@include('base')

<script src="{{ asset('js/usageConfirmationLoader.js') }}"></script>

<div class="makeVehicleUseFormBox">
<form id="makeVehicleUseForm" action="{{ route('startUsing') }}" method="post">
@csrf
    <input type="hidden" name="endUseConfirmationFlag" id="endUseConfirmationFlag" value="no">


    <div id="firstPartOfMakeVehicleUseForm">
        <input type="hidden" name="vehicle" id="vehicle" value="{{ $vehicle }}">
        <p class="makeVehicleUseLabel"> Kurā objektā lietosi inventāru 
            @if(isset($vehicleName))
                {{$vehicleName}}?
            @else
                inventāra nosaukums netika atrasts?
            @endif
            </p>
        @error('vehicle')
            <span class="alert">{ { $message }}</span>
        @enderror
        @error('id')
            <span class="alert">{ { $message }}</span>
        @enderror
        <input id="objectID" name="object" type="hidden" value="-1">
        <label for="object" class="StartUsingObjectLabel">Objekta Nr.</label>
        <input id="object" list="objects" class="objectSelector" oninput="updateObjectInput()">
        <datalist id="objects">
            @foreach($objects as $object)
            <option value="{{ $object->code }}" data-id="{{ $object->id }}"></option>
            @endforeach
        </datalist>
        @error('object')
            <span class="alert">{{ $message }}</span>
        @enderror

        <label id="objectnameLabel" class="StartUsingObjectLabel">Nosaukums</label>
        <input id="objectname" list="objectnames" class="objectSelector" oninput="updateObjectCode()">
        <datalist id="objectnames">
            @foreach($objects as $object)
            <option value="{{ $object->name }}" data-id="{{ $object->id }}"></option>
            @endforeach
        </datalist>
        <label style="display: none;" id="commentLabel" class="StartUsingObjectLabel">Komentārs</label>
        <input style="display: none;" id="comment" name="comment" class="objectSelector" value="">
        @error('comment')
            <span class="alert">{{ $message }}</span>
        @enderror

        <br>
        <button id="getNextPart" type="button" class="">Tālāk/izveidot</button>
        

        <button class="SyncObjectsLink" type="button" id="syncBtn">Atjaunot objektu sarakstu</button>
        <p id="syncText"></p>
    </div>
    <div style="display: none;" id="secondPartOfMakeVehicleUseForm">   
        Šis tiek ielādēts vēlāk.
    </div>
</form>
</div>

<div id="confirmWrapper" class="confirmWrapper">
<div class="overlay"></div>
<div class="confirmationBox">
    <p><span id="confirmText"></span></p>
    <button type="button" id="NOBTN" class="NOBTN">Atpakaļ </button>
    <button type="button" id="OKBTN" class="OKBTN">Izveidot lietojumu</button>
</div>
</div>

<div id="loadingWrapper" class="loadingWrapper">
<div class="overlay"></div>
<div class="loadingBox">
    <p><span id="loadingText">Notiek sinhronizācija</span></p>
</div>
</div>
@include('footer')