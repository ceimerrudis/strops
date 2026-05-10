@include('dropdown', ['text' => 'Rezervētājs', 'fieldName' => 'user', 'options' => $users, 'visualName' => 'username', 'key' => 'id'])

@include('dropdown', ['text' => 'Inventārs', 'fieldName' => 'vehicle', 'options' => $vehicles, 'visualName' => 'name', 'key' => 'id'])

<label class="admin_edit_label" for="from">Sākot no</label>
<input class="admin_edit_input" type="datetime-local" name="from" id="from" value="{{ old('from', $entry->from) }}">
@error('from')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="until">Līdz</label>
<input class="admin_edit_input" type="datetime-local" name="until" id="until" value="{{ old('until', $entry->until) }}">
@error('until')
    <span class="adimn_alert">{{ $message }}</span>
@enderror