<td>
    {{ $entry->comment }}
</td>
<td>
    {{ $entry->time }}
</td>
<td>
    @if($entry->reservation == null)    
        -
    @else
        {{ $entry->reservation }}
    @endif
</td>
<td>
    @if($entry->vehicle_use == null)    
        -
    @else
        {{ $entry->vehicle_use }}
    @endif
</td>
<td>
    @if($entry->usage_before == $entry->usage_after && $entry->usage_after == 0)    
        -
    @else
        {{ $entry->usage_before }}
    @endif
</td>
<td>
    @if($entry->usage_before == $entry->usage_after && $entry->usage_after == 0)    
        -  
    @else
        {{ $entry->usage_after }}
    @endif
</td>