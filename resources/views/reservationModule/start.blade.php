@include('base')

<script src="{{ asset('js/startPage.js') }}"></script>
<p style="display:none" id="reservationUrlHolder">{{route('createReservation')}}</p>
<p style="display:none" id="reservationAndUseUrlHolder">{{route('createReservationAndUse')}}</p>
<p style="display:none" id="useUrlHolder">{{route('startUse')}}</p>

<div class="flex_parent">

    <div class="calendar_box">
        <div class="calendar_box_2">
            <div class="calendar_head_box">
                <button id="calendarPreviousMonthButton"> << </button>
                    <div class="calendar_title_box">
                        <p class="calendar_month_title" id="calendarMonthTitle">month</p>
                    </div>
                <button id="calendarNextMonthButton"> >> </button>
            </div>

            <table class="calendar" id="calendar">
            
            </table>
        </div>
    </div>

    <div class="make_reservation_flex_box_item">
        <p id="collapsor" class="make_reservation_title collapsible"><span class='collapse_label'>Veikt inventāra rezervāciju -</span></p>
            <div class="make_reservation_box" id="collapse_content">
                <form id="makeReservationForm">
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
                            <input style="display: none;" checked type="radio" name="vehicle" id="all_vehicles" value="-1">
                            @elseif(empty(old('vehicle')))
                            <input style="display: none;" checked type="radio" name="vehicle" id="all_vehicles" value="-1">
                            @else
                            <input style="display: none;" type="radio" name="vehicle" id="all_vehicles" value="-1"> 
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
                                    <input class="vehicle_radio" type="radio" name="vehicle" id="{{ $vehicle->name }}" value="{{ $vehicle->id }}" checked>
                                @else    
                                    <input class="vehicle_radio" type="radio" name="vehicle" id="{{ $vehicle->name }}" value="{{ $vehicle->id }}">
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

                    <div class="reserve_btn_container">
                        <button type="button" class="create_vehicle_use_button" id="makeReservationBtn">REZERVĒT</button>
                        <button type="button" class="create_vehicle_use_button" id="startUsingWithReservationBtn">LIETOT UN REZERVĒT</button>
                        <button type="button" class="create_vehicle_use_button" id="startUsingBtn">LIETOT</button>
                    </div>
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
                <div id="timelineVisual" class="timeline_visual">
                </div>
            </div>
        </div>
    </div>
</div>

@include('footer')