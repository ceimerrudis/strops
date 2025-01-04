<td>
    {{ $entry->code }}
</td>
<td>
    {{ $entry->name }}    
</td>
<td>
    @if($entry->active)
        Aktīvs
    @else
        Slēgts
    @endif
</td>

            