<label class="admin_edit_label" for="{{$fieldName}}">{{ $text }}</label>
<select class="admin_edit_input" id="{{$fieldName}}" name="{{$fieldName}}">
    @foreach($options as $option)
        @if(old($fieldName, $entry->{$key}) == $option[$key])
            <option selected="selected" value="{{ $option[$key] }}">{{ $option[$visualName] }}</option>
        @else 
            <option value="{{ $option[$key]  }}">{{ $option[$visualName] }}</option>
        @endif
    @endforeach
</select>
@error('{{$fieldName}}')
    <span class="adimn_alert">{{ $message }}</span>
@enderror