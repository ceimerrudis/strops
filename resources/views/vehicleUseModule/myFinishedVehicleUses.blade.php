@include('base')
<div class="table_container">
<table class="table columns_8">

<tr>
    <th>Inventārs</th>
    <th>Objekts</th>
    <th>Lietojums</th>
    <th>Lietojums pirms</th>
    <th>Lietojums pēc</th>
    <th>Datums/laiks no</th>
    <th>Datums/laiks līdz</th>
</tr>

@php
use Illuminate\Support\Carbon;
use \App\Enums\VehicleUsageTypes;
@endphp
@foreach($uses as $vehicleUse)
    <tr>
        <td>
            {{ $vehicleUse->name }}
        </td>
        <td>
            {{ $vehicleUse->code }}
        </td>
        <td>
            {{ VehicleUsageTypes::GetDisplayVal($vehicleUse->usage_type, $vehicleUse->usage_after - $vehicleUse->usage_before); }}    
            <br>
            {{ VehicleUsageTypes::getName($vehicleUse->usage_type); }}
        </td>
        <td>
            {{ VehicleUsageTypes::GetDisplayVal($vehicleUse->usage_type, $vehicleUse->usage_before); }}    
            <br>
            {{ VehicleUsageTypes::getName($vehicleUse->usage_type); }}
        </td>
        <td>
            {{ VehicleUsageTypes::GetDisplayVal($vehicleUse->usage_type, $vehicleUse->usage_after); }}    
            <br>
            {{ VehicleUsageTypes::getName($vehicleUse->usage_type); }}
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