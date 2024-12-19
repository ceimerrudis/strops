@include('header')
<body>
    <div class="messageBoard">
        @php
            $messages = getMessages();
        @endphp

        @foreach ($messages as $message)
            <p class="alert alert-{{ $message['status'] }}">
                {{ $message['text'] }}
            </p>
        @endforeach
    </div>