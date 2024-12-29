@include('header')
<body>
    <div class="message_board">
        @php
            $messages = getMessages();
        @endphp

        @foreach ($messages as $message)
            <p class="alert alert-{{ $message['status'] }}">
                {{ $message['text'] }}
            </p>
        @endforeach
    </div>

    @php
        use App\Enums\UserTypes;
        use App\Enums\EntryTypes;
    @endphp
    <div class="nav_bar_box"
        @if(Auth::check())
            @if(Auth::user()->type == UserTypes::ADMIN->value) 
                id="adminNavBarBox"
            @endif
        @endif
    >

    <div class="nav_link_wrapper">
        <a class="nav_link" href="vehicleReservationSelection">
            <span class="nav_link_span_1_lines">
                Sākums
            </span>
        </a>
    </div>

    <div class="nav_link_wrapper">
        <a class="nav_link" href="myReservations">
            <span class="nav_link_span_2_lines">
                Manas 
                <br>rezervācijas
            </span>
        </a>
    </div>

    <div class="nav_link_wrapper">
        <a class="nav_link" href="myDrives">
            <span class="nav_link_span_2_lines">
                Mani 
                <br>lietojumi
            </span>
        </a>
    </div>

    <div class="nav_link_wrapper">
        <a class="nav_link" href="myActiveDrives">
            <span class="nav_link_span_4_lines">
                Mani
                <br> pašlaik
                <br> lietotie
                <br>inventāri
            </span>
        </a>
    </div>
   
    @if(Auth::check())
        @if(Auth::user()->type == UserTypes::ADMIN->value) 
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::USER->value}}">
                    <span class="nav_link_span_2_lines">
                        Visi <br>
                        lietotāji
                    </span>
                </a>
            </div>
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::VEHICLE->value}}">
                    <span class="nav_link_span_2_lines">
                        Visi 
                        <br>inventāri
                    </span>
                </a>
            </div>
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::OBJECT->value}}">
                    <span class="nav_link_span_2_lines">
                        Visi 
                        <br>objekti
                    </span>
                </a>
            </div>
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::REPORT->value}}">
                    <span class="nav_link_span_2_lines">
                        Visas 
                        <br>atskaites
                    </span>
                </a>
            </div>
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::RESERVATION->value}}">
                    <span class="nav_link_span_2_lines">
                        Visas 
                        <br>rezervācijas
                    </span>
                </a>
            </div>
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::VEHICLE_USE->value}}">
                    <span class="nav_link_span_2_lines">
                        Visi 
                        <br>lietojumi
                    </span>
                </a>
            </div>
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::ERROR->value}}">
                    <span class="nav_link_span_1_lines">
                        Kļūdas
                    </span>
                </a>
            </div>
        @endif
    @endif

    <div class="nav_link_wrapper">
        <a class="nav_link" id="logoff_button" href="atteikties">
            <span class="nav_link_span_1_lines">
                Iziet
            </span>
        </a>
    </div>
    
</div>