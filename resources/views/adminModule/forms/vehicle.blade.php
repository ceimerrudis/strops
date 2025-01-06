<label class="admin_edit_label" for="name">Inventāra nosaukums</label>
<input class="admin_edit_input" type="text" name="name" id="name" value="{{ old('name', $entry->name) }}">
@error('name')
    <span class="adimn_alert">{{ $message }}</span>
@enderror


<label class="admin_edit_label" for="usage">Nolietojums</label>
<input class="admin_edit_input" type="number" id="usage" name="usage" step="0.01" value="{{ old('usage', $entry->usage) }}">
@error('usage')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

@php 
    use App\Enums\VehicleUsageTypes;
    $usagetypes = VehicleUsageTypes::GetAllEnums();
@endphp
@include('dropdown', ['text' => 'Lietojuma veids', 'fieldName' => 'usage_type', 'options' => $usagetypes, 'visualName' => 'name', 'key' => 'value'])
