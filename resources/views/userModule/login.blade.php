<div class="loginBox">
    <img class="loginLogo" src="images/logo_basic.png">
    <h2 class="loginTitle">Pieteikšanās strops sistēmai</h2>
    <form action="pieteikties" method="POST" class="loginForm">
        @csrf
        <div class="loginInputDividerBox">
            <label class="loginLabel" for="username">Lietotājvārds:</label>
            <input class="loginInput" type="text" id="username" name="username" value="{{old('username')}}">
            <div class="errorBox">
                @error('username')
                    <span class="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="loginInputDividerBox">
            <label class="loginLabel" for="password">Parole:</label>
            <input class="loginInput" type="password" id="password" name="password" value="{{old('password')}}">
            <div class="errorBox">
                @error('password')
                <span class="alert">{{ $message }}</span><br>
                @enderror
                @if(session('msg') !== null)
                <span class="alert">{{ session('msg') }}</span><br>
                @endif
            </div>
        </div>
        <div class="loginInputDividerBox">
            <input id="rememberMe" class="showPass" type="checkbox">
            <label for="rememberMe" class="showPassLabel">Automātiski pieteikties</label>
            <br>
        </div>
        <div class="loginInputDividerBox">
            <input id="showPass" class="showPass" type="checkbox" onclick="ShowPassword()">
            <label for="showPass" class="showPassLabel">Rādīt paroli</label>
            <br>
        </div>
        <div class="loginInputDividerBox">
            <input class="loginInput" id="submit" type="submit" value="Pieslēgties">
        </div>
    </form>

</div>
