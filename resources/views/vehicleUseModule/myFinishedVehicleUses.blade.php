@include('base')
<div class="table_container">
<table class="table columns_6">

<tr>
    <th>Inventārs</th>
    <th>Objekts</th>
    <th>Lietojums</th>
    <th>Datums/laiks no</th>
    <th>Datums/laiks līdz</th>
</tr>

@php
use Illuminate\Support\Carbon;
use \App\Enums\VehicleUsageType;
@endphp
@foreach($uses as $vehicleUse)
    <tr>
        <td>
            {{ $vehicleUse->vehicle }}
        </td>
        <td>
            {{ $vehicleUse->object }}
        </td>
        <td>
            {{ VehicleUsageType::GetDisplayVal($vehicleUse->usageType, $vehicleUse->usage); }}    
            <br>
            {{ VehicleUsageType::getName($vehicleUse->usageType); }}
        </td>
        <td>
            {{ Carbon::parse($vehicleUse->from)->translatedFormat('d-M') }}
            <br>
            {{ Carbon::parse($vehicleUse->from)->translatedFormat('H:i') }}
        </td>
        <td>
            {{ Carbon::parse($vehicleUse->until)->translatedFormat('d-M') }}
            <br>
            {{ Carbon::parse($vehicleUse->until)->translatedFormat('H:i') }}
        </td>
    </tr>
@endforeach
</table>
</div>
@if($uses->count() == 0)
    <p  style="padding-left: 10px;">Šobrīd tu neesi beidzis nevienu lietojumu.</p>
@endif
@include('footer')