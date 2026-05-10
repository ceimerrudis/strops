@if($entry->object == null)
    @include('dropdown', ['text' => 'Atskaite objektam', 'fieldName' => 'object', 'options' => $objects, 'visualName' => 'code', 'key' => 'id'])
@else
    <p class="admin_view_info">Atskaite objektam {{ $entry->code }}</p>
@endif

@if(isset($justReport))
<input type="hidden" name="object" value="{{ $entry->object }}">
@endif

<label class="admin_edit_label" for="progress">Progress <output id="output">{{ round(old('progress', $entry->progress)) }}</output>%</label>
<input class="admin_edit_input" type="range" id="progress" name="progress" min="0" max="100" value="{{ old('progress', $entry->progress ?? 0) }}" oninput="output.value = progress.value">
@error('progress')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="year">Gads</label>
<input class="admin_edit_input" type="number" id="year" 
@if((!isset($CreatingNew) || !$CreatingNew) && $entry->code != null)
readonly
@endif
name="year" min="2020" max="2100" placeholder="YYYY" value="{{ old('year', $entry->year ?? $entry->currentYear) }}">
@error('year')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="month">Mēnesis (1 = janvāris, 12 = decembris)</label>
<input class="admin_edit_input" type="number" id="month"
@if((!isset($CreatingNew) || !$CreatingNew) && $entry->code != null)
readonly
@endif
name="month" min="1" max="12" placeholder="MM" value="{{ old('month', $entry->month) }}">
@error('month')
    <span class="adimn_alert">{{ $message }}</span>
@enderror