@if($entry->object == null)
    @include('dropdown', ['text' => 'Atskaite objektam', 'fieldName' => 'object', 'options' => $objects, 'visualName' => 'code', 'key' => 'id'])
@else
    <p class="admin_view_info">Atskaite objektam {{ $entry->code }}</p>
@endif

@if(isset($justReport))
<input type="hidden" name="object" value="{{ $entry->object }}">
@endif

<label class="admin_edit_label" for="progress">Progress</label>
<input class="admin_edit_input" type="number" id="progress" name="progress" step="0.01" min="0" max="100" value="{{ old('progress', $entry->progress) }}">
@error('progress')
    <span class="adimn_alert">{{ $message }}</span>
@enderror
<label class="admin_edit_label" for="date">Līdz</label>
<input class="admin_edit_input" type="datetime-local" name="date" id="date" 
@if((!isset($CreatingNew) || !$CreatingNew) && $entry->code != null)
readonly
@endif
value="{{ old('date', $entry->date) }}">
@error('date')
    <span class="adimn_alert">{{ $message }}</span>
@enderror