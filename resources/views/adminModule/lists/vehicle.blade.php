@php
use App\Enums\VehicleUsageTypes;
@endphp
<td>
    {{ $entry->name }}
</td>
<td>
    {{ VehicleUsageTypes::GetDisplayVal($entry->usage_type, $entry->usage) }}
</td>
<td>
    {{ VehicleUsageTypes::getName($entry->usage_type); }}
</td>
<td>
    {{ $entry->id }}
</td>