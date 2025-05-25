<td>
    {{ $entry->code }}
</td>
<td>
    {{ $entry->name }}    
</td>
<td>
    {{ $entry->users_name }}    
    {{ $entry->users_lname }}    
</td>
<td>
    @if($entry->active)
        Aktīvs
    @else
        Slēgts
    @endif
</td>

            