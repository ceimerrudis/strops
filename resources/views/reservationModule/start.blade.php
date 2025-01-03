@include('base')

<script src="{{ asset('js/startPage.js') }}"></script>

<div class="flex_parent">

    <div class="calendar_box">
        <div class="calendar_box_2">
            <div class="calendar_head_box">
                <button id="calendar_previous_month_button"> << </button>
                    <div class="calendar_title_box">
                        <p class="calendar_month_title" id="calendar_month_title">month</p>
                    </div>
                <button id="calendar_next_month_button"> >> </button>
            </div>

            <table class="calendar" id="calendar">
            
            </table>
        </div>
    </div>

    <div class="make_reservation_flex_box_item">
        <p id="collapsor" class="make_reservation_title collapsible"><span class='collapse_label'>Veikt inventāra rezervāciju -</span></p>
            <div class="make_reservation_box" id="collapse_content">
                <form id="make_reservation_form" action="setByJs" method="post">
                    @csrf   
                    <div class="centered">
                        <div class="date_time_input_box">    
                            <label class="date_time_label" for="from">Sākot no</label>
                            <input class="reservation_selection_date" type="datetime-local" name="from" id="from" value="{{ old('from') }}">
                            @error('from')
                                <span class="alert">{{ $message }}</span>
                            @enderror
                            <br>
                            <input id="freeze_checkbox" type="checkbox" name="freeze_checkbox"></input> <label class="freeze_label" for="freeze_checkbox">Iesaldēt sākuma laiku</label>
                            <br>

                            <label class="date_time_label" for="until">Līdz</label>
                            <input class="reservation_selection_date" type="datetime-local" name="until" id="until" value="{{ old('until') }}">
                            @error('until')
                                <span class="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="car_choice_box">
                            <p style="margin-bottom: 15px; width: 210px; display:inline-block;"> Izvēlies inventāru</p><span> Pieejamība </span>
                            <br>
                            @if(old('vehicle') == -1)   
                            <input style="display: none;" form="make_reservation_form" checked type="radio" name="vehicle" id="all_vehicles" value="-1">
                            @elseif(empty(old('vehicle')))
                            <input style="display: none;" form="make_reservation_form" checked type="radio" name="vehicle" id="all_vehicles" value="-1">
                            @else
                            <input style="display: none;" form="make_reservation_form" type="radio" name="vehicle" id="all_vehicles" value="-1"> 
                            @endif
                            @foreach($vehicles as $vehicle)
                                <p style="display:none;" id="used_{{$vehicle->id}}">
                                @if($vehicle->usedby == "")
                                    0
                                @else
                                    1
                                @endif
                                </p>
                                @if(old('vehicle') == $vehicle->id)
                                    <input class="vehicle_radio" form="make_reservation_form" type="radio" name="vehicle" id="{{ $vehicle->name }}" value="{{ $vehicle->id }}" checked>
                                @else    
                                    <input class="vehicle_radio" form="make_reservation_form" type="radio" name="vehicle" id="{{ $vehicle->name }}" value="{{ $vehicle->id }}">
                                @endif

                            <label class="vehicle_label" for="{{ $vehicle->name }}">{{ $vehicle->name }}   
                            </label>
                            

                            @if($vehicle->usedby == "")
                            <span class="usedby_label usedby_label_green">brīvs</span>
                            @else
                            <span class="usedby_label usedby_label_red">{{$vehicle->usedby}}</span>
                            @endif 
                            <hr>
                            @endforeach
                            @error('vehicle')
                                <span class="alert">{{ $message }}</span>
                            @enderror
                                <br>
                        </div>
                    </div><!-- vards brivs zaļš lietots sarkans atdalits ar tabulatoru-->
                    <!-- palielinat datms lauku -->
                    <div class="reserve_btn_container">
                        <button type="button" class="create_vehicle_use_button" id="make_reservation_btn">REZERVĒT</button>
                        <button type="button" class="create_vehicle_use_button" id="start_using_with_reservation_btn">LIETOT UN REZERVĒT</button>
                        <button type="button" class="create_vehicle_use_button" id="start_using_btn">LIETOT</button>
                    </div>
                    <input id="type_inp" type="hidden" value="0" name="type">
                    @error('type')
                                <span class="alert">{{ $message }}</span>
                    @enderror
                </form>
            </div>
        </div>
    </div>
    <div style="display: flex;">
        <div class="timeline_box">
            <div class="timeline">
                <div class="timeline_background_pillar">
                    7<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div class="timeline_background_pillar">
                    8<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div class="timeline_background_pillar">
                    9<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div class="timeline_background_pillar">
                    10<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div class="timeline_background_pillar">
                    11<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div class="timeline_background_pillar">
                    12<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div class="timeline_background_pillar">
                    13<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div class="timeline_background_pillar">
                    14<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div class="timeline_background_pillar">
                    15<span class="timeline_pillar_time_suffix">:00 </span>
                </div>
                <div class="timeline_background_pillar">
                    16<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div class="timeline_background_pillar">
                    17<span class="timeline_pillar_time_suffix">:00</span>
                </div>
                <div id="timeline_visual" class="timeline_visual">
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer')