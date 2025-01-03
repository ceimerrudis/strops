<label class="admin_edit_label" for="user">Rezervētājs</label>
<select class="admin_edit_input" id="type" name="user" id="user">
    @foreach($users as $user)
        @if(old('user', $entry->user) == $user->id)
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
        @if(old('vehicle', $entry->vehicle) == $vehicle->id)
            <option selected="selected" value="{{ $vehicle->id }}">{{ $vehicle->name }}</option>
        @else
            <option value="{{ $vehicle->id }}">{{ $vehicle->name }}</option> 
        @endif
        <p style="display:none;" id="{{ $vehicle->id }}">{{ $vehicle->usageType }}</p>
    @endforeach
</select>
@error('vehicle')
    <span class="adimn_alert">{{ $message }}</span>
@enderror


<input id="objectID" name="object" type="hidden" value="{{ old('object', $entry->object) }}">
<label class="admin_edit_label" for="object">Objekts</label>
<input class="admin_edit_input" id="object" list="objects" oninput="updateObjectInput()">
<datalist id="objects">
    @foreach($objects as $object)
        <option value="{{ $object->code }}" data-id="{{ $object->id }}"></option>                
    @endforeach
</datalist>
<script>
    $( document ).ready(function() {
        var objectInput = $("#objectID");
        code = $("#objects option[data-id='" + String(Number(objectInput.val())) + "']").val();
        $("#object").val(code);
    });
    function updateObjectInput(){
        var objectInput = $("#object");
        id = $("#objects option[value='" + objectInput.val() + "']").data("id");
        $("#objectID").val(id);
    }
</script>
@error('object')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="comment">Komentārs</label>
<input id="comment" name="comment" type="text" class="admin_edit_input" value="{{ old('comment', $entry->comment) }}">
@error('comment')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="usageBefore">Lietojums sākot
{{ \App\Enums\VehicleUsageType::GetTrueName($entry->usageType); }}
</label>
<input class="admin_edit_input"  type="text" name="usageBefore" id="usageBefore" value="{{ old('usageBefore', $entry->usageBefore) }}">
@error('usageBefore')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="usageAfter">Lietojums beidzot
{{ \App\Enums\VehicleUsageType::GetTrueName($entry->usageType); }}
</label>
<input class="admin_edit_input"  type="text" name="usageAfter" id="usageAfter" value="{{ old('usageAfter', $entry->usageAfter) }}">
@error('usageAfter')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

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