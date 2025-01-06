<!-- Kļūdu nevar rediģēt tikai apskatīt un izdzēst -->
<p class="error_view_info">Kļūdas laiks: <br> {{ $entry->time }}</p>
<p class="error_view_info">Kļūdas id: <br> {{ $entry->id }}</p>
<p class="error_view_info">Kļūdas komentārs:<br> {{ $entry->comment }}</p>
<p class="error_view_info">Kļūdas transportlīdzeklis: <br>{{ $errorVehicleUse->use_vehicle_name }}</p>
@if($errorReservation == null)
    <p class="error_view_info">Lietojums pirms:<br> {{ $entry->usagebefore }}</p>
    <p class="error_view_info">Lietojums pēc:<br> {{ $entry->usageafter }}</p>
@else
    <p class="error_view_info">Kļūdas rezervācijas lietotājs: <br>{{ $errorReservation->reservation_user_name }} {{ $errorReservation->reservation_user_lname }}</p>
    <p class="error_view_info">Kļūdas rezervācija no: <br>{{ $errorReservation->from }}</p>
    <p class="error_view_info">Kļūdas rezervācija līdz: <br>{{ $errorReservation->until }}</p>
@endif
<p class="error_view_info">Kļūdas lietojuma lietotājs: <br>{{ $errorVehicleUse->use_user_name }} {{ $errorVehicleUse->use_user_lname }}</p>
<p class="error_view_info">Kļūdas lietojums no: <br>{{ $errorVehicleUse->from }}</p>
<p class="error_view_info">Kļūdas lietojums līdz: <br>{{ $errorVehicleUse->until }}</p>