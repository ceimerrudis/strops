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
            <form method="post" action="{{ route('endUse') }}">
                @csrf
                <input type="hidden" name="vehicleUse" id="vehicleUse" value="{{$vehicleUse->id}}">
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