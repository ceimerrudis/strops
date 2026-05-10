@php
    use App\Enums\VehicleUsageTypes;
    $usage_type = null;
    foreach ($vehicles as $vehicle) {
        if($vehicle->id == $entry->vehicle){
            $usage_type = $vehicle->usage_type;
        }
    }
@endphp
@if($entry->until == null)
    @if($usage_type == VehicleUsageTypes::DAYS->value)
    <form method="post" action="{{ route('endUseShortcut') }}">
    @else
    <form method="get" action="{{ route('endUse') }}">
        <input type="hidden" name="redirectTo" id="redirectTo" value="/rediget?table={{$table}}&id={{$entry->id}}"> 
    @endif
        @csrf
        <input type="hidden" name="vehicle_use" id="vehicle_use" value="{{$entry->id}}">
        <button type="submit" class="stop_usage_button">Pārtraukt lietot</button>
    </form>
@endif