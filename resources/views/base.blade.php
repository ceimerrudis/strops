@include('header')
<body class="background">
    <div id="loadingWrapper" class="loading_wrapper">
        <div class="overlay"></div>
    </div>    
    <div id="messageBoard" class="message_board">
        @php
            $messages = DeleteMessages();
        @endphp

        @foreach ($messages as $message)
            <p class="alert {{ $message['status'] }}">
                {{ $message['text'] }}
                <button class="delete_message_button" onclick="hideMessage(this)">x</button> 
            </p>
        @endforeach

        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>

    @php
        use App\Enums\UserTypes;
        use App\Enums\EntryTypes;
    @endphp
    <div class="system_base_box">
        <div class="nav_bar_box"
            @if(Auth::check())
                @if(Auth::user()->type == UserTypes::ADMIN->value) 
                    id="adminNavBarBox"
                @endif
            @endif
        </div>

        <div class="nav_link_wrapper">
            <a class="nav_link" href="sakums">
                <span class="nav_link_span_1_lines">
                    Sākums
                </span>
            </a>
        </div>

        <div class="nav_link_wrapper">
            <a class="nav_link" href="manasRezervacijas">
                <span class="nav_link_span_2_lines">
                    Manas 
                    <br>rezervācijas
                </span>
            </a>
        </div>

        <div class="nav_link_wrapper">
            <a class="nav_link" href="maniPabeigtieLietojumi">
                <span class="nav_link_span_2_lines">
                    Mani 
                    <br>lietojumi
                </span>
            </a>
        </div>

        <div class="nav_link_wrapper">
            <a class="nav_link" href="maniNepabeigtieLietojumi">
                <span class="nav_link_span_4_lines">
                    Mani
                    <br> pašlaik
                    <br> lietotie
                    <br>inventāri
                </span>
            </a>
        </div>

        <div class="nav_link_wrapper">
                @if(Auth::user()->type == UserTypes::ADMIN->value) 
                    <a class="nav_link" href="apskatitVisus?table={{EntryTypes::REPORT->value}}">
                @else
                    <a class="nav_link" href="apskatitatskaites">
                @endif
                <span class="nav_link_span_1_lines">
                    Atskaites
                </span>
            </a>
        </div>

        <div class="nav_link_wrapper">
            <a class="nav_link" id="logoffButton" href="atteikties">
                <span class="nav_link_span_1_lines">
                    Iziet
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
                        <span class="nav_link_span_2_lines">
                            Visas <br>kļūdas
                        </span>
                    </a>
                </div>
            @endif
        @endif

        
    </div>