<td>
    {{ $entry->user }}
</td>
<td>
    {{ $entry->vehicle }}
</td>
<td>
    {{ $entry->object }}
</td>
<td>
    {{ \App\Enums\VehicleUsageType::GetDisplayVal($entry->usageType, $entry->usage) }} 
    <br>
    {{ \App\Enums\VehicleUsageType::getName($entry->usageType); }}
</td>
<td>
    {{ \App\Enums\VehicleUsageType::GetDisplayVal($entry->usageType, $entry->usageBefore) }}  /
    {{ \App\Enums\VehicleUsageType::GetDisplayVal($entry->usageType, $entry->usageAfter) }}  
</td>
<td>
    {{ Carbon::parse($entry->from)->translatedFormat('d-M') }}
    <br>
    {{ Carbon::parse($entry->from)->translatedFormat('H:i') }}
</td>
<td>
    @if($entry->until != null)
        {{ Carbon::parse($entry->until)->translatedFormat('d-M') }}
        <br>
        {{ Carbon::parse($entry->until)->translatedFormat('H:i') }}
    @endif
</td>
<td>
    {{ $entry->comment }}
</td>