<label class="admin_edit_label" for="{{$fieldName}}">{{ $text }}</label>
<select class="admin_edit_input" id="{{$fieldName}}" name="{{$fieldName}}">
    <option value=""></option>    
    @foreach($options as $option)
        @if(old($fieldName, $entry->{$fieldName}) == $option[$key])
            <option selected="selected" value="{{ $option[$key] }}">{{ $option[$visualName] }}</option>
        @else 
            <option value="{{ $option[$key]  }}">{{ $option[$visualName] }}</option>
        @endif
    @endforeach
</select>
@error('{{$fieldName}}')
    <span class="adimn_alert">{{ $message }}</span>
@enderror