@include('base')

@php
    use App\Enums\VehicleUsageTypes;
@endphp

<form action="beigtLietojumuNoradotLietojumu" method="post">
@csrf
    <input type="hidden" name="vehicle_use" id="vehicle_use" value="{{$id}}">

    <label class="current_usage_label" for="from">
    @if($usage_type == VehicleUsageTypes::MOTOR_HOURS->value)
        Kādas ir pašreizējās motorstundas?
    @elseif($usage_type == VehicleUsageTypes::KILOMETERS->value)
        Kāds ir pašreizējais nobraukums?
    @else
        Notika sistēmas kļūda.
    @endif
    </label>
    <input class="admin_edit_input" type="numeric" name="usage" id="usage" value="{{ old('usage') }}">
    @error('usage')
        <span class="alert">{{ $message }}</span>
    @enderror
    
    @if(session('msg'))
        <span class="alert">{{ session('msg') }}</span>
    @endif
    <br>

    <button class="current_usage_confirm_btn" type="submit">pārtraukt lietojumu</button>
</form>

@include('footer')