@include('base')
<div class="table_container">
<table class="table columns_5">

<tr>
    <th>Inventārs</th>
    <th>Datums/laiks no</th>
    <th>Datums/laiks līdz</th>
    <th>Dzēst</th>
</tr>
@php
use Illuminate\Support\Carbon;
@endphp
@foreach($myreservations as $reservation)
    <tr>
        <td>
            {{ $reservation->vehicle }}
        </td>
        <td>
            {{ Carbon::parse($reservation->from)->translatedFormat('d-M') }}
            <br>
            {{ Carbon::parse($reservation->from)->translatedFormat('H:i') }}
        </td>
        <td>
            {{ Carbon::parse($reservation->until)->translatedFormat('d-M') }}
            <br>
            {{ Carbon::parse($reservation->until)->translatedFormat('H:i') }}
        </td>
        <td>    
        <form method="POST" action="{{ route('removeReservation') }}">
            @csrf
            <input type="hidden" name="id" value="{{$reservation->id}}">
            <button type="submit">
                Izdzēst
            </button>
        </form>
        </td>
    </tr>
@endforeach
</table>
</div>
@if($myreservations->count() == 0)
    <p style="padding-left: 10px;">Šobrīd tu neesi veicis nevienu rezervāciju.</p>
@endif
@include('footer')