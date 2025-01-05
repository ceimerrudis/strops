@php 
use App\Enums\VehicleUsageTypes;
use Carbon\Carbon;
@endphp
<td>
    {{ $entry->users_username }}
</td>
<td>
    {{ $entry->vehicles_name }}
</td>
<td>
    {{ $entry->objects_code }}
</td>
<td>
    {{ VehicleUsageTypes::GetDisplayVal($entry->vehicles_usage_type, $entry->usage_before) }}
    <br>{{ VehicleUsageTypes::getName($entry->vehicles_usage_type); }}
</td>
<td>
    {{ VehicleUsageTypes::GetDisplayVal($entry->vehicles_usage_type, $entry->usage_after) }}  
    <br>{{ VehicleUsageTypes::getName($entry->vehicles_usage_type); }}
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