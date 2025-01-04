@include("header")

<body class="background">
    <script src="{{ asset('js/login.js') }}"></script>
    <div class="login_box">
        <img class="login_logo" src="images/logo_basic.png">
        <h2 class="login_title">Pieteikšanās strops sistēmai</h2>
        <form action="pieteikties" method="POST" class="login_form">
            @csrf
            <div class="login_input_divider_box">
                <label class="login_label" for="username">Lietotājvārds:</label>
                <input class="login_input" type="text" id="username" name="username" value="{{old('username')}}">
                <div class="error_box">
                    @error('username')
                        <span class="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="login_input_divider_box">
                <label class="login_label" for="password">Parole:</label>
                <input class="login_input" type="password" id="password" name="password" value="{{old('password')}}">
                <div class="error_box">
                    @error('password')
                    <span class="alert">{{ $message }}</span><br>
                    @enderror
                    @if(session('msg') !== null)
                    <span class="alert">{{ session('msg') }}</span><br>
                    @endif
                </div>
            </div>
            <div class="login_input_divider_box">
                <input id="rememberMe" name="rememberMe" class="show_pass" type="checkbox">
                <label for="rememberMe" class="show_pass_label">Automātiski pieteikties</label>
                <br>
            </div>
            <div class="login_input_divider_box">
                <input id="showPass" class="show_pass" type="checkbox" onclick="ShowPassword()">
                <label for="showPass" class="show_pass_label">Rādīt paroli</label>
                <br>
            </div>
            <div class="login_input_divider_box">
                <input class="login_input" id="submit" type="submit" value="Pieslēgties">
            </div>
        </form>

    </div>
</body>
