<!DOCTYPE html>
<html lang="lv">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>strops</title>

        <link rel="icon" type="image/x-icon" href="{{asset('logoFavIcon.png');}}">

        <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('js/js.js') }}"></script>
        <script src="{{ asset('js/telemetry.js') }}"></script>
        <script src="{{ asset('js/popUp.js') }}"></script>
        <script> 
            $(document).ready(function() {
                updateMessageBoard();
            });
        </script>
        <link rel="stylesheet" href="{{asset('css/style.css');}}">
        <link rel="stylesheet" href="{{asset('css/mobileStyles.css');}}">
    </head>
    
