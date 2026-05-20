@include('base')

<script src="{{ asset('js/startPage.js') }}"></script>
<p style="display:none" id="reservationUrlHolder">{{route('getCreateReservation')}}</p>
<p style="display:none" id="reservationAndUseUrlHolder">{{route('createReservationAndUse')}}</p>
<p style="display:none" id="useUrlHolder">{{route('startUse')}}</p>
<input type="hidden" id="oldVehicle" value="{{ old('vehicle') }}">
<input type="hidden" id="timeErrors" 
@if ($errors->has('from') || $errors->has('until'))
	value="1"	
@else
	value="0"
@endif
>

<div class="flex_parent">

    <div class="calendar_box">
        <div class="calendar_box_2">
            <div class="calendar_head_box">
                <button name="month_backward" id="calendarPreviousMonthButton"> << </button>
                    <div class="calendar_title_box">
                        <p class="calendar_month_title" id="calendarMonthTitle">month</p>
                    </div>
                <button name="month_forward" id="calendarNextMonthButton"> >> </button>
            </div>

            <table class="calendar" id="calendar">
            
            </table>
        </div>
    </div>

    <div class="make_reservation_flex_box_item">
        
		<p id="collapsor" class="make_reservation_title collapsible"><span class='collapse_label'>Veikt inventāra rezervāciju vai lietojumu -</span></p>
		<div class="make_reservation_box" id="collapse_content">
		
				<form id="makeReservationForm">
					@csrf   
					<div class="wrapper" id="vehicleSide">
						<div class="centered">
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
									<div class="vehicle_choice_container">    
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

									
										<label class="vehicle_label" for="{{ $vehicle->name }}">
											<span class="usedby_name_label"> {{ $vehicle->name }}   </span>
											
											@if($vehicle->usedby == "")
											<span class="usedby_label usedby_label_green">brīvs</span>
											@else
											<span class="usedby_label usedby_label_red">{!! $vehicle->usedby !!}</span>
											@endif 
										</label>
									</div>

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
							<button type="button" name="lietotUnRezervet" class="create_vehicle_use_button" id="startUsingWithReservationBtn">LIETOT UN REZERVĒT</button>
							<button type="button" name="lietot" class="create_vehicle_use_button" id="startUsingBtn">LIETOT</button>
						</div>
						<p class="alert" id="angerBox"></p>
					</div>
					<div style="display:none" class="wrapper" id="TimeSide">
						<label class="date_time_label" for="from">Sākot no</label>
						<input class="reservation_selection_date" type="datetime-local" name="from" id="from" value="{{ old('from') }}">
						@error('from')
							<span class="alert">{{ $message }}</span>
						@enderror
						<br>
						<input id="freezeCheckbox" type="checkbox" name="freezeCheckbox"></input> <label class="freeze_label" for="freezeCheckbox">Iesaldēt sākuma laiku</label>
						
						<br><br>
						<label class="date_time_label" for="until">Līdz</label>
						<input class="reservation_selection_date" type="datetime-local" name="until" id="until" value="{{ old('until') }}">
						@error('until')
							<span class="alert">{{ $message }}</span>
							<br>
						@enderror
						
						<button type="button" name="rezervet" class="reservation_button" id="finishReservationBtn">REZERVĒT</button>
						<button type="button" name="atpakal" class="reservation_button" id="stopReservationBtn">ATPAKAĻ</button>
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
