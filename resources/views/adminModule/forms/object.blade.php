<label class="admin_edit_label" for="code">Objekta Nr.</label>
<input class="admin_edit_input" type="text" name="code" id="code" value="{{ old('code', $entry->code) }}">
@error('code')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="name">Vārds</label>
<input class="admin_edit_input" type="text" name="name" id="name" value="{{ old('name', $entry->name) }}">
@error('name')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_checkbox_label" for="active">Aktīvs</label>
<input class="admin_edit_checkbox"  type="checkbox" name="active" id="active"
@if(old('active', $entry->active))
checked
@endif
>
@error('active')
    <span class="adimnAlert">{{ $message }}</span>
@enderror

@include('dropdown', ['text' => 'Atbildīgais', 'fieldName' => 'user_in_charge', 'options' => $users, 'visualName' => 'username', 'key' => 'id'])
