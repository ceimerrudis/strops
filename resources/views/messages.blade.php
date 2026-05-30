<!-- Paziņojumu konteineris -->
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
</div>
