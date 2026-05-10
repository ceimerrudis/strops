<td>
    {{ $entry->username }}
</td>
<td>
    {{ $entry->name }}
</td>
<td>
    {{ $entry->lname }}
</td>
<td>
    @if($entry->type == 1)
        Darbinieks
    @else
        Administrators
    @endif
</td>