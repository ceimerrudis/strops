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
@endphp
@foreach($uses as $vehicleUse)
    <tr>
        <td>
            <a method="GET" href="{{ route('beigtLietojumu', ['vehicleUse' => $vehicleUse->id]) }}">Pārtraukt lietot</a>
        </td>
        <td>
            {{ $vehicleUse->vehicle }}
        </td>
        <td>
            {{ $vehicleUse->object }}
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