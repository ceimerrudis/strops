@include('header')
<body class="background">

<!-- Melns lapas pārklājs lai pasargātu pogas un elemetus ielādes laikā -->
<div id="loadingWrapper" class="loading_wrapper">
    <div class="overlay"></div>
</div>    
@include('messages')

@php
    use App\Enums\UserTypes;
    use App\Enums\EntryTypes;
@endphp
<!-- Galvenā lapa kurā dzīvo pārējā mājas lapa -->
<div class="system_base_box">
    
    <!-- Navigācijas josla -->
    <div class="nav_bar_box" 
        @if(Auth::check() && Auth::user()->type == UserTypes::ADMIN->value)
                id="adminNavBarBox"
        @endif
    >
    
    <div class="nav_link_wrapper">
        <a class="nav_link" href="sakums">
            <span class="nav_link_span_1_lines">
                SĀKUMS
    </span> </a> </div>

    <div class="nav_link_wrapper">
        <a class="nav_link" href="manasRezervacijas">
            <span class="nav_link_span_2_lines">
                MANAS 
                <br>REZERVĀCIJAS
    </span> </a> </div>

    <div class="mobile_visible nav_link_wrapper">
        <a class="nav_link" id="logoffButton" href="atteikties">
            <span class="nav_link_span_1_lines">
                IZIET
    </span> </a> </div>

    <div class="nav_link_wrapper">
        <a class="nav_link" href="maniPabeigtieLietojumi">
            <span class="nav_link_span_2_lines">
                MANI 
                <br>LIETOJUMI
    </span> </a> </div>

    <div class="nav_link_wrapper">
        <a class="nav_link" href="maniNepabeigtieLietojumi">
            <span class="nav_link_span_2_lines">
                PAŠREIZĒJIE
                <br>LIETOJUMI
    </span> </a> </div>

    <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitatskaites">
            <span class="nav_link_span_1_lines">
                ATSKAITES
    </span> </a> </div>

    <!-- Izņēmuma administratora poga -->
    @if(Auth::check())
        @if(Auth::user()->type == UserTypes::ADMIN->value) 
            
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::USER->value}}">
                    <span class="nav_link_span_1_lines">
                        LIETOTĀJI
            </span> </a> </div>
            @endif
    @endif

    <div class="mobile_invisible nav_link_wrapper">
        <a class="nav_link" id="logoffButton" href="atteikties">
            <span class="nav_link_span_1_lines">
                IZIET
    </span> </a> </div>

    <!-- Administratora pogas -->
    @if(Auth::check())
        @if(Auth::user()->type == UserTypes::ADMIN->value) 
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::VEHICLE->value}}">
                    <span class="nav_link_span_1_lines">
                        INVENTĀRI
            </span> </a> </div>
            
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::OBJECT->value}}">
                    <span class="nav_link_span_1_lines">
                        OBJEKTI
            </span> </a> </div>
            
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::REPORT->value}}">
                    <span class="nav_link_span_1_lines">
                        ATSKAITES
            </span> </a> </div>

            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::RESERVATION->value}}">
                    <span class="nav_link_span_2_lines">
                        VISAS 
                        <br>REZERVĀCIJAS
            </span> </a> </div>
            
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::VEHICLE_USE->value}}">
                    <span class="nav_link_span_2_lines">
                        VISI 
                        <br>LIETOJUMI 
            </span> </a> </div>
            
            <div class="nav_link_wrapper">
                <a class="nav_link" href="apskatitVisus?table={{EntryTypes::ERROR->value}}">
                    <span class="nav_link_span_1_lines">
                        KĻŪDAS
            </span> </a> </div>
        @endif
    @endif
    </div>
    
    <!-- Vai šo vēl kaut kas izmanto? -->
    <div class="page_content">
        @yield('content')
    </div>

