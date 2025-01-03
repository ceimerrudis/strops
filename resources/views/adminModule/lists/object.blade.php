<td>
    {{ $object->code }}
</td>
<td>
    {{ $object->name }}    
</td>
<td>
    @if($object->active)
        Aktīvs
    @else
        Slēgts
    @endif
</td>

            