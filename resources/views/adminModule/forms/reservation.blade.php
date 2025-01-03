<label class="admin_edit_label" for="user">Rezervētājs</label>
<select class="admin_edit_input" id="type" name="user" id="user">
    @foreach($users as $user)
        @if($entry->user == $user->id)
            <option selected="selected" value="{{ $user->id }}">{{ $user->username }}</option>
        @else
            <option value="{{ $user->id }}">{{ $user->username }}</option>
        @endif
    @endforeach
</select>
@error('user')
    <span class="adimn_alert">{{ $message }}</span>
@enderror


<label class="admin_edit_label" for="vehicle">Inventārs</label>
<select class="admin_edit_input" id="type" name="vehicle" id="vehicle">
    @foreach($vehicles as $vehicle)
        @if($entry->vehicle == $vehicle->id)
            <option selected="selected" value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
        @else
            <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option> 
        @endif
    @endforeach
</select>
@error('vehicle')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="from">Sākot no</label>
<input class="admin_edit_input" type="datetime-local" name="from" id="from" value="{{ $entry->from }}">
@error('from')
    <span class="adimn_alert">{{ $message }}</span>
@enderror


<label class="admin_edit_label" for="until">Līdz</label>
<input class="admin_edit_input" type="datetime-local" name="until" id="until" value="{{ $entry->until }}">
@error('until')
    <span class="adimn_alert">{{ $message }}</span>
@enderror