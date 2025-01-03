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
    @if($entry->vehicleUse == null)    
        -
    @else
        {{ $entry->vehicleUse }}
    @endif
</td>
<td>
    @if($entry->usagebefore == $entry->usageafter && $entry->usageafter == 0)    
        -
    @else
        {{ $entry->usagebefore }}
    @endif
</td>
<td>
    @if($entry->usagebefore == $entry->usageafter && $entry->usageafter == 0)    
        -  
    @else
        {{ $entry->usageafter }}
    @endif
</td>