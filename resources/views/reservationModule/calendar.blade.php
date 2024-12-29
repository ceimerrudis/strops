<p id="year" style="display: none;"> {{ $year }} </p>
<p id="month" style="display: none;"> {{ $month }} </p>
<p id="day" style="display: none;"> {{ $day }} </p>
<p id="monthTitleHolder" style="display: none;"> {{ $monthName }} </p>

<tr>
    <th><p>Pr</p></th>
    <th><p>Ot</p></th>
    <th><p>Tr</p></th>
    <th><p>Ce</p></th>
    <th><p>Pk</p></th>
    <th><p>Se</p></th>
    <th><p>Sv</p></th>
</tr>

@php
    $daynum = 2 -  $monthStartOn;
@endphp
@for ($i = 0; $i < $rowCount; $i++)
    <tr>
    @for ($j = 0; $j < 7; $j++)
        <td><div class="calendar_item_wrapper">
        @if($daynum < 1 || $daynum > $monthLength)
            <p class="empty_calendar_item"></p>
        @else
            @if($day == $daynum)
                <p class="calendar_item today" id="{{ $daynum }}">{{ $daynum }}</p>
            @else
                <p class="calendar_item"id="{{ $daynum }}">{{ $daynum }}</p>
            @endif
        @endif
        <p style="display: none;" id="data_{{ $daynum }}">
            @foreach ($separatedReservations as $dayobj)
                @php
                    $reservationNum = 0;
                @endphp
                @foreach ($dayobj as $reservation)
                    @if($reservation['day'] == $daynum)
                        <span id="data_day_{{ $daynum }}_reservation_{{ $reservationNum }}_vehicle"> {{ $reservation['vehicle'] }}</span>
                        <span id="data_day_{{ $daynum }}_reservation_{{ $reservationNum }}_vehicleID"> {{ $reservation['vehicleID'] }}</span>
                        <span id="data_day_{{ $daynum }}_reservation_{{ $reservationNum }}_user"> {{ $reservation['user'] }}</span>
                        <span id="data_day_{{ $daynum }}_reservation_{{ $reservationNum }}_from"> {{ $reservation['from'] }}</span>
                        <span id="data_day_{{ $daynum }}_reservation_{{ $reservationNum }}_until"> {{ $reservation['until'] }}</span>
                    @endif
                    @php
                        $reservationNum = $reservationNum + 1;
                    @endphp
                @endforeach
            @endforeach
        </p>
        </div></td>
        @php
            $daynum = $daynum + 1
        @endphp
    @endfor
    </tr>
@endfor
