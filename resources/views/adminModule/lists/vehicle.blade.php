<td>
    {{ $entry->name }}
</td>
<td>
    {{ VehicleUsageType::GetDisplayVal($entry->usagetype, $entry->usage) }}
</td>
<td>
    {{ VehicleUsageType::getName($entry->usagetype); }}
</td>
<td>
    {{ $entry->id }}
</td>