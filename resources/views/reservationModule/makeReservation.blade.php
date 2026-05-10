@include('base')

<div class="make_vehicle_use_form_box">
<form id="makeVehicleUseForm" action="{{ route('createReservation') }}" method="post">
@csrf
    <input type="hidden" name="vehicle" id="vehicle" value="{{ $vehicleId }}">
	<br>
	<label class="date_time_label" for="from">Sākot no</label>
	<input class="reservation_selection_date" type="datetime-local" name="from" id="from" value="{{ old('from', $from) }}">
	@error('from')
		<span class="alert">{{ $message }}</span>
	@enderror
	

	<label class="date_time_label" for="until">Līdz</label>
	<input class="reservation_selection_date" type="datetime-local" name="until" id="until" value="{{ old('until', $until) }}">
	@error('until')
		<span class="alert">{{ $message }}</span>
	@enderror
    <br>
    <button type='submit' id="beginUse" class='get_next_part'>Rezervēt</button>
    <div class="spacer"></div>
</form>
</div>

@include('footer')