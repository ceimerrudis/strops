@php 
use Carbon\Carbon;
@endphp
<td>
    {{ $entry->users_username }}
</td>
<td>
    {{ $entry->vehicles_name }}
</td>
<td>
    {{ Carbon::parse($entry->from)->translatedFormat('d-M') }}
    <br>
    {{ Carbon::parse($entry->from)->translatedFormat('H:i') }}
</td>
<td>
    {{ Carbon::parse($entry->until)->translatedFormat('d-M') }}
    <br>
    {{ Carbon::parse($entry->until)->translatedFormat('H:i') }}
</td>