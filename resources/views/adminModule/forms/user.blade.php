<label class="admin_edit_label" for="username">Lietotājvārds</label>
<input class="admin_edit_input" type="text" name="username" id="username" value="{{ old('username', $entry->username) }}">
@error('username')
    <span class="adimn_alert">{{ $message }}</span>
@enderror


<label class="admin_edit_label" for="name">Vārds</label>
<input class="admin_edit_input" type="text" name="name" id="name" value="{{ old('name', $entry->name) }}">
@error('name')
    <span class="adimn_alert">{{ $message }}</span>
@enderror


<label class="admin_edit_label" for="lname">Uzvārds</label>
<input class="admin_edit_input" type="text" name="lname" id="lname" value="{{ old('lname', $entry->lname) }}">
@error('lname')
    <span class="adimn_alert">{{ $message }}</span>
@enderror


<label class="admin_edit_label" for="password">Parole</label>
<input class="admin_edit_input" type="text" name="password" id="password" value="">
@error('password')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

@php 
    $types = App\Enums\UserTypes::GetAllEnums();
@endphp
@include('dropdown', ['text' => 'Loma', 'fieldName' => 'type', 'options' => $types, 'visualName' => 'name', 'key' => 'value'])