<input id="objectID" name="object" type="hidden" value="{{ old('object', $entry->object) }}">
<label class="admin_edit_label" for="object">Objekts</label>
<input autocomplete="off" class="admin_edit_input" id="object" list="objects" oninput="updateObjectInput()">
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