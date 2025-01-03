<label class="admin_edit_label" for="name">Inventāra nosaukums</label>
<input class="admin_edit_input" type="text" name="name" id="name" value="{{ $entry->name }}">
@error('name')
    <span class="adimn_alert">{{ $message }}</span>
@enderror


<label class="admin_edit_label" for="usage">Nolietojums</label>
<input class="admin_edit_input" type="number" id="usage" name="usage" step="0.01" value="{{ $entry->usage }}">
@error('usage')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="usagetype">Lietojuma veids</label>
<select class="admin_edit_input" id="type" name="usagetype" id="usagetype">
    @php 
    use App\Enums\VehicleUsageTypes;
    $usagetypes = VehicleUsageTypes::GetAllEnums();
    @endphp
    @foreach($usagetypes as $usagetype)
        @if(old('usagetype', $entry->usagetype) == $usagetype['value'])
            <option selected="selected" value="{{ $usagetype['value'] }}">{{ $usagetype['name'] }}</option>
        @else 
            <option value="{{ $usagetype['value'] }}">{{ $usagetype['name'] }}</option>
        @endif
    @endforeach
</select>
@error('usagetype')
    <span class="adimn_alert">{{ $message }}</span>
@enderror