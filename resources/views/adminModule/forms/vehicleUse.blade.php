@php
    //Šis kods saglabā katram inventāram lietojuma veida nosaukumu lai to dinamiski mainītu atkarīgi no izvēlētā inventāra
    use App\Enums\VehicleUsageTypes;
    $usageNames = [];
    foreach ($vehicles as $vehicle) {
        //Mark  usage so it can be entered in before field
        $usageNames[$vehicle->id] = ['name' => VehicleUsageTypes::GetName($vehicle->usage_type), 'value' => $vehicle->usage_type];
    }
@endphp

@include('dropdown', ['text' => 'Lietotājs', 'fieldName' => 'user', 'options' => $users, 'visualName' => 'username', 'key' => 'id'])

@include('dropdown', ['text' => 'Inventārs', 'fieldName' => 'vehicle', 'options' => $vehicles, 'visualName' => 'name', 'key' => 'id'])
<script>
    //Šis kods saglabā katram inventāram lietojuma veida nosaukumu lai to dinamiski mainītu atkarīgi no izvēlētā inventāra
    $(document).ready(function() {
        const usageNames = @json($usageNames);
        $('#vehicle').change(function() {
            const selectedValue = $(this).val();
            $(".usage_name_display").html(usageNames[selectedValue].name);
            if(usageNames[$("#vehicle").val()].value == @json(VehicleUsageTypes::DAYS))
            {
                $("#recalculateTimeButton").show();
            }else
            {
                $("#recalculateTimeButton").hide();
            }
        });
        if(usageNames[$("#vehicle").val()].value == @json(VehicleUsageTypes::DAYS))
        {
            $("#recalculateTimeButton").show();
        }else
        {
            $("#recalculateTimeButton").hide();
        }

        $(".usage_name_display").html(usageNames[$('#vehicle').val()].name);
        $('#recalculateTimeButton').on('click', function() {
            if(usageNames[$("#vehicle").val()].value == @json(VehicleUsageTypes::DAYS))
            {
                $.ajax({
                    type: "GET",
                    url: "parrekinatlaiku", 
                    data: { 
                        from: $("#from").val(), 
                        until: $("#until").val(), 
                    },
                    success: function(result){
                        $("#usage_after").val((parseFloat($("#usage_before").val()) + parseFloat(result.time)).toFixed(2));
                        AddMessage("Laiks aprēķināts!", "info");
                    }
                });  
                
            }
        });
    });
</script>

@include('datalist')

<label class="admin_edit_label" for="comment">Komentārs</label>
<input id="comment" name="comment" type="text" class="admin_edit_input" value="{{ old('comment', $entry->comment) }}">
@error('comment')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="usage_before">Lietojums sākot
(<span class="usage_name_display"></span>)
</label>
<input class="admin_edit_input"  type="text" name="usage_before" id="usage_before" value="{{ old('usage_before', $entry->usage_before) }}">
@error('usage_before')
    <span class="adimn_alert">{{ $message }}</span>
@enderror

<label class="admin_edit_label" for="usage_after">Lietojums beidzot
(<span class="usage_name_display"></span>)
</label>
<input class="admin_edit_input"  type="text" name="usage_after" id="usage_after" value="{{ old('usage_after', $entry->usage_after) }}">
@error('usage_after')
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

<button type="button" id="recalculateTimeButton" class="recalculate_time_button">aprēķināt laika lietojumu</button>