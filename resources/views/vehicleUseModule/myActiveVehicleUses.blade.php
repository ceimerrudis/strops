@include('base')
<div class="table_container">
<table class="table columns_6">

<tr>
    <th>Pārtraukt lietot</th>
    <th>Inventārs</th>
    <th>Objekts</th>
    <th>Datums/laiks no</th>
</tr>

@php
use Illuminate\Support\Carbon;
use App\Enums\VehicleUsageTypes;
@endphp
@foreach($uses as $vehicleUse)
    <tr>
        <td>
            @if($vehicleUse->usage_type == VehicleUsageTypes::DAYS->value)
            <form method="post" action="{{ route('endUseShortcut') }}">
            @else
            <form method="get" action="{{ route('endUse') }}">
            @endif
                @csrf
                <input type="hidden" name="vehicle_use" id="vehicle_use" value="{{$vehicleUse->id}}">
                <button type="submit" class="">Pārtraukt lietot</button>
            </form>
        </td>
        <td>
            {{ $vehicleUse->name }}
        </td>
        <td>
            {{ $vehicleUse->code }}
        </td>
        <td>
            {{ Carbon::parse($vehicleUse->from)->translatedFormat('d-M') }}
            <br>
            {{ Carbon::parse($vehicleUse->from)->translatedFormat('H:i') }}
        </td>
    </tr>
@endforeach
</table>
</div>
@if($uses->count() == 0)
    <p style="padding-left: 10px;">Šobrīd tu nelieto nevienu inventāru.</p>
@endif
@include('footer')