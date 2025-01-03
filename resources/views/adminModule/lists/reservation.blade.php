<td>
    {{ $entry->user }}
</td>
<td>
    {{ $entry->vehicle }}
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